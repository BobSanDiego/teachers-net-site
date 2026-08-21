"""Resolve and reconcile registered live companion-chat reader sources."""
from __future__ import annotations

import json
import re
from datetime import datetime, timezone
from pathlib import Path
from typing import Any


class CompanionReaderError(RuntimeError):
    """A live companion source cannot safely be resolved."""


def _atomic_json(path: Path, payload: dict[str, Any]) -> None:
    temporary = path.with_suffix(path.suffix + ".tmp")
    temporary.write_text(json.dumps(payload, indent=2, sort_keys=True) + "\n", encoding="utf-8")
    temporary.replace(path)


def _title_matches(record: dict[str, Any], title: str) -> bool:
    patterns = ((record.get("handoff_v2") or {}).get("chatgpt_title_patterns") or [])
    return bool(patterns) and any(re.search(pattern, title, re.IGNORECASE) for pattern in patterns)


def _candidate_threads(record: dict[str, Any], registered: dict[str, Any], threads: list[dict[str, Any]]) -> list[dict[str, Any]]:
    account_project = registered.get("account_project_id")
    return [
        thread for thread in threads
        if thread.get("kind") == "chatgpt"
        and isinstance(thread.get("id"), str)
        and _title_matches(record, str(thread.get("title") or ""))
        and (not account_project or thread.get("projectId") == account_project)
    ]


def resolve_and_reconcile(*, project_record_path: Path, registry_path: Path, reader_path: Path, now: datetime | None = None) -> dict[str, Any]:
    """Validate a reader capture and reconcile stale registration only if unique."""
    try:
        record = json.loads(project_record_path.read_text(encoding="utf-8"))
        registry = json.loads(registry_path.read_text(encoding="utf-8"))
        reader = json.loads(reader_path.read_text(encoding="utf-8"))
    except (OSError, json.JSONDecodeError) as exc:
        raise CompanionReaderError(f"registered companion input is unreadable: {exc}") from exc
    project = record.get("project_id")
    source = reader.get("source") or {}
    if source.get("kind") != "chatgpt" or not source.get("id") or not source.get("title"):
        raise CompanionReaderError("live reader capture has no identifiable ChatGPT source")
    entries = [item for item in registry.get("projects", []) if item.get("project_id") == project and item.get("state") == "ACTIVE"]
    if len(entries) != 1:
        raise CompanionReaderError(f"registered companion authority is ambiguous for project {project!r}: active entries={len(entries)}")
    registered = entries[0]
    if not _title_matches(record, str(source["title"])):
        raise CompanionReaderError(f"live reader title does not match governed project identity: {source['title']!r}")
    listed = reader.get("listed_threads")
    if not isinstance(listed, list):
        raise CompanionReaderError("live reader capture is missing the canonical companion listing")
    source_listing = [item for item in listed if item.get("id") == source["id"]]
    if len(source_listing) != 1:
        raise CompanionReaderError("live reader source does not have one matching canonical listing entry")
    source_project_id = source.get("projectId") or source_listing[0].get("projectId")
    if registered.get("account_project_id") and source_project_id != registered["account_project_id"]:
        raise CompanionReaderError("live reader project identity differs from registered companion account project")
    candidates = _candidate_threads(record, registered, listed)
    if source["id"] not in {item.get("id") for item in candidates}:
        raise CompanionReaderError("live reader source is not present in its canonical companion listing")
    exact = registered.get("thread_id") == source["id"] and registered.get("expected_title") == source["title"]
    if not exact and len(candidates) != 1:
        raise CompanionReaderError("registered companion is stale and live resolution is ambiguous; candidates=" + repr(sorted(str(item.get("id")) for item in candidates)))
    changed = not exact
    if changed:
        timestamp = (now or datetime.now(timezone.utc)).astimezone(timezone.utc).replace(microsecond=0).isoformat()
        history = list(registered.get("replacement_history") or [])
        history.append({"replaced_thread_id": registered.get("thread_id"), "replaced_title": registered.get("expected_title"), "reconciled_to_thread_id": source["id"], "reconciled_to_title": source["title"], "reason": "Canonical live reader listing supplied one identity-safe active companion candidate.", "reconciled_at": timestamp})
        registered.update({"thread_id": source["id"], "expected_title": source["title"], "registered_at": timestamp, "registration_provenance": "WORKFLOW-V2-HANDOFF-READER001 canonical live-reader reconciliation", "replacement_history": history})
        record["companion_chat"] = {"kind": "chatgpt", "title": source["title"], "conversation_id": source["id"], "account_project_id": source_project_id, "reconciled_at": timestamp, "reconciliation_provenance": "WORKFLOW-V2-HANDOFF-READER001 canonical live-reader reconciliation"}
        _atomic_json(registry_path, registry)
        _atomic_json(project_record_path, record)
    return {"project": project, "source_id": source["id"], "title": source["title"], "kind": source["kind"], "account_project_id": source_project_id, "reconciled": changed, "candidate_count": len(candidates)}
