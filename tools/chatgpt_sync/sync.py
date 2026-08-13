#!/usr/bin/env python3
"""Build and acknowledge bounded, reader-visible cross-project sync packages.

This module never calls a ChatGPT API.  Codex supplies bounded pages read through
the app reader, then this owner validates identity, boundaries, and provenance.
"""
from __future__ import annotations

import argparse
import hashlib
import json
import re
from datetime import datetime, timezone
from pathlib import Path
from typing import Any

ROOT = Path(__file__).resolve().parents[2]
DEFAULT_REGISTRY = ROOT / "docs/process/conversation-handoff/shared/chatgpt-sync-registry.json"
DEFAULT_STATE = ROOT / "tmp/hopper/shared-workflow/chatgpt-sync/ledger.json"
DEFAULT_ARCHIVE = ROOT / "tmp/hopper/shared-workflow/chatgpt-sync/archive"
MAX_SOURCE_CHARS = 50_000
MAX_GENERATION_CHARS = 125_000
MAX_PAGES = 6


class SyncError(RuntimeError):
    pass


def _read_json(path: Path) -> dict[str, Any]:
    return json.loads(path.read_text(encoding="utf-8"))


def _write_json(path: Path, value: dict[str, Any]) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(json.dumps(value, indent=2, sort_keys=True) + "\n", encoding="utf-8")


def _sha(text: str) -> str:
    return hashlib.sha256(text.encode("utf-8")).hexdigest()


def _state() -> dict[str, Any]:
    return {"schema_version": 1, "next_generation": 1, "sources": {}, "generations": []}


def load_state(path: Path) -> dict[str, Any]:
    return _read_json(path) if path.is_file() else _state()


def projects(registry: dict[str, Any]) -> dict[str, dict[str, Any]]:
    return {entry["project_id"]: entry for entry in registry["projects"]}


def _message(item: dict[str, Any]) -> tuple[str, str, str] | None:
    if item.get("type") == "userMessage":
        content = item.get("content", [])
        text = "\n".join(str(part.get("text", "")) for part in content if part.get("type") == "text")
        return item.get("id", ""), "user", text
    if item.get("type") == "agentMessage":
        return item.get("id", ""), "assistant", str(item.get("text", ""))
    return None


def _validate_thread(project: dict[str, Any], page: dict[str, Any]) -> None:
    thread = page.get("thread") or {}
    if project.get("state") != "ACTIVE":
        raise SyncError(f"{project['project_id']}: thread is not ACTIVE")
    if thread.get("id") != project.get("thread_id"):
        raise SyncError(f"{project['project_id']}: exact thread identity mismatch")
    if project.get("expected_title") != thread.get("title"):
        raise SyncError(f"{project['project_id']}: expected title mismatch")
    expected_account = project.get("account_project_id")
    if expected_account and thread.get("projectId") != expected_account:
        raise SyncError(f"{project['project_id']}: account/project identity mismatch")


def _source_delta(project: dict[str, Any], pages: list[dict[str, Any]], prior: str | None) -> dict[str, Any]:
    if not pages:
        raise SyncError(f"{project['project_id']}: no reader page supplied")
    items: list[dict[str, Any]] = []
    chars = 0
    found_boundary = False
    for index, page in enumerate(pages, start=1):
        if index > MAX_PAGES:
            raise SyncError(f"UPDATE CHATGPT BLOCKED — DELTA TOO LARGE: {project['project_id']} exceeds {MAX_PAGES} pages")
        _validate_thread(project, page)
        for turn in page.get("turns", []):
            for raw in turn.get("items", []):
                parsed = _message(raw)
                if not parsed:
                    continue
                item_id, role, text = parsed
                if raw.get("truncated"):
                    raise SyncError(f"{project['project_id']}: reader item {item_id} is truncated")
                if item_id == prior:
                    found_boundary = True
                    break
                chars += len(text)
                if chars > MAX_SOURCE_CHARS:
                    raise SyncError(f"UPDATE CHATGPT BLOCKED — DELTA TOO LARGE: {project['project_id']} exceeds {MAX_SOURCE_CHARS} characters")
                items.append({"id": item_id, "turn_id": turn.get("id"), "role": role, "text": text, "timestamp": turn.get("completedAt") or turn.get("startedAt")})
            if found_boundary:
                break
        if found_boundary:
            break
        # An initial sync has no known lower boundary.  It is complete only
        # when the supplied reader pages reach the reader-visible beginning;
        # accepting an arbitrary latest page would silently omit history.
        if not page.get("page", {}).get("hasMore", False):
            if prior is None:
                found_boundary = True
            else:
                raise SyncError(f"{project['project_id']}: prior boundary {prior!r} was not found")
    if not found_boundary:
        raise SyncError(f"{project['project_id']}: prior boundary requires more than {MAX_PAGES} pages")
    items.reverse()  # reader pages are newest-first; preserve source chronology.
    return {"project": project["project_id"], "thread_id": project["thread_id"], "items": items, "start_item_id": items[0]["id"] if items else prior, "end_item_id": items[-1]["id"] if items else prior, "characters": chars}


def build(registry_path: Path, state_path: Path, reader_path: Path, archive: Path) -> dict[str, Any]:
    registry, state, supplied = _read_json(registry_path), load_state(state_path), _read_json(reader_path)
    known = projects(registry)
    supplied_projects = [source.get("project") for source in supplied.get("sources", [])]
    active_projects = {project_id for project_id, entry in known.items() if entry.get("state") == "ACTIVE"}
    if len(supplied_projects) != len(set(supplied_projects)):
        raise SyncError("duplicate project source supplied")
    missing = active_projects - set(supplied_projects)
    if missing:
        raise SyncError(f"active registered sources missing: {', '.join(sorted(missing))}")
    deltas: list[dict[str, Any]] = []
    for source in supplied.get("sources", []):
        project_id = source.get("project")
        if project_id not in known:
            raise SyncError(f"unregistered source project: {project_id}")
        prior = (state.get("sources", {}).get(project_id) or {}).get("last_item_id")
        deltas.append(_source_delta(known[project_id], source.get("pages", []), prior))
    if not deltas:
        raise SyncError("no active source pages supplied")
    total = sum(item["characters"] for item in deltas)
    if total > MAX_GENERATION_CHARS:
        raise SyncError(f"UPDATE CHATGPT BLOCKED — DELTA TOO LARGE: generation exceeds {MAX_GENERATION_CHARS} characters")
    number = int(state.get("next_generation", 1))
    generation_id = f"G{number}"
    recipients = {pid: "PENDING" for pid, entry in known.items() if entry.get("state") == "ACTIVE"}
    created = datetime.now(timezone.utc).isoformat()
    body = [f"# TNET CHATGPT SYNC {generation_id}", "", "Reader-visible conversation evidence only; repository authority remains controlling.", ""]
    body.append("## Source boundaries")
    for delta in deltas:
        body.append(f"- {delta['project']}: `{delta['start_item_id']}` → `{delta['end_item_id']}` ({delta['characters']} characters)")
    for delta in deltas:
        body += ["", f"## {delta['project']}"]
        for item in delta["items"]:
            body += ["", f"### {item['role'].upper()} `{item['id']}`", item["text"]]
    body += ["", "## Acknowledgment", "After ingesting this entire package, emit exactly:", f"`SYNC ACK: {generation_id} <payload-sha256>`"]
    provisional = "\n".join(body) + "\n"
    # The payload checksum is deliberately calculated over the fixed marker
    # form.  Self-hashing rendered text has no stable fixed point.
    payload_sha = _sha(provisional)
    rendered = provisional.replace("<payload-sha256>", payload_sha)
    archive.mkdir(parents=True, exist_ok=True)
    payload_path = archive / f"{generation_id}-chatgpt-sync.md"
    manifest_path = archive / f"{generation_id}-manifest.json"
    payload_path.write_text(rendered, encoding="utf-8")
    generation = {"id": generation_id, "created_at": created, "payload": str(payload_path), "payload_sha256": payload_sha, "file_sha256": _sha(rendered), "sources": deltas, "recipients": recipients, "completeness": "READER_VISIBLE / NOT LOSSLESS EXPORT", "warnings": []}
    manifest_path.write_text(json.dumps(generation, indent=2, sort_keys=True) + "\n", encoding="utf-8")
    for delta in deltas:
        state.setdefault("sources", {})[delta["project"]] = {"thread_id": delta["thread_id"], "last_item_id": delta["end_item_id"], "updated_at": created}
    state["next_generation"] = number + 1
    state.setdefault("generations", []).append(generation)
    _write_json(state_path, state)
    return generation


def acknowledge(registry_path: Path, state_path: Path, reader_path: Path, recipient: str, generation_id: str) -> dict[str, Any]:
    registry, state, reader = _read_json(registry_path), load_state(state_path), _read_json(reader_path)
    project = projects(registry).get(recipient)
    generation = next((item for item in state.get("generations", []) if item["id"] == generation_id), None)
    if not project or not generation:
        raise SyncError("unknown recipient or generation")
    # A direct reader response is itself the page and has `thread`/`turns`;
    # only a caller wrapper may place that response under `page`.
    page = reader if reader.get("thread") else reader.get("page")
    if not isinstance(page, dict):
        raise SyncError(f"{recipient}: no reader page supplied for acknowledgment")
    _validate_thread(project, page)
    marker = f"SYNC ACK: {generation_id} {generation['payload_sha256']}"
    seen = any(parsed and parsed[1] == "assistant" and marker in parsed[2] for turn in page.get("turns", []) for raw in turn.get("items", []) if (parsed := _message(raw)))
    if not seen:
        raise SyncError(f"{recipient}: exact acknowledgment marker not found")
    generation["recipients"][recipient] = "ACKNOWLEDGED"
    generation.setdefault("ack_provenance", {})[recipient] = {"verified_at": datetime.now(timezone.utc).isoformat(), "thread_id": project["thread_id"]}
    _write_json(state_path, state)
    return generation


def recommend(state_path: Path, reason: str) -> dict[str, Any]:
    """Emit a metadata-only recommendation; this never reads conversation."""
    state = load_state(state_path)
    pending = [
        generation["id"]
        for generation in state.get("generations", [])
        if any(value == "PENDING" for value in generation.get("recipients", {}).values())
    ]
    return {
        "message": "GLOBAL CHATGPT SYNC RECOMMENDED",
        "reason": reason,
        "command": "UPDATE CHATGPT",
        "pending_generations": pending,
        "reader_accessed": False,
    }


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("command", choices=["build", "ack", "status", "recommend"])
    parser.add_argument("--registry", type=Path, default=DEFAULT_REGISTRY)
    parser.add_argument("--state", type=Path, default=DEFAULT_STATE)
    parser.add_argument("--reader-json", type=Path)
    parser.add_argument("--archive", type=Path, default=DEFAULT_ARCHIVE)
    parser.add_argument("--recipient")
    parser.add_argument("--generation")
    args = parser.parse_args()
    try:
        if args.command == "build":
            if not args.reader_json: raise SyncError("build requires --reader-json")
            result = build(args.registry, args.state, args.reader_json, args.archive)
        elif args.command == "ack":
            if not args.reader_json or not args.recipient or not args.generation: raise SyncError("ack requires reader, recipient, generation")
            result = acknowledge(args.registry, args.state, args.reader_json, args.recipient, args.generation)
        elif args.command == "recommend":
            result = recommend(args.state, "structured workflow signal")
        else:
            result = load_state(args.state)
    except SyncError as error:
        print(str(error))
        return 2
    print(json.dumps(result, indent=2, sort_keys=True))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
