#!/usr/bin/env python3
"""Build a self-contained, project-record-driven ChatGPT startup payload.

This is the central owner for the Workflow V2 ``PREPARE HANDOFF`` command.
It deliberately keeps routine ChatGPT startup preparation separate from the
heavier immutable recovery-checkpoint lifecycle.
"""
from __future__ import annotations

import hashlib
import json
import os
import re
import shutil
import tempfile
import zipfile
from dataclasses import dataclass
from datetime import datetime, timezone
from pathlib import Path
from typing import Any

try:
    from codex_transcript_archive import render_session
except ImportError:  # pragma: no cover - imported as a package by workflow.py
    from tools.codex_archive.codex_transcript_archive import render_session


RENDERER_VERSION = "handoff-v2.1"
CHATGPT_ROLE = re.compile(r"(?m)^\*\*(🙍🏻‍♂️ You|🤖 ChatGPT):\*\*\s*$")
EXPORTED = re.compile(r"(?mi)^\*\*Exported:\*\*\s*(.+?)\s*$")
DECLARED_MESSAGES = re.compile(r"(?mi)^\*\*Messages:\*\*\s*(\d+)\s*$")
CREDENTIAL = re.compile(
    r"(?im)^(?P<prefix>\s*(?:password|passphrase|api[_ -]?key|client[_ -]?secret|access[_ -]?token)\s*[:=]\s*)(?P<value>\S.*)$"
)


class HandoffError(RuntimeError):
    """Fail-closed handoff preparation error."""


@dataclass(frozen=True)
class ChatMessage:
    message_id: str
    role: str
    content: str
    content_sha256: str


@dataclass(frozen=True)
class ChatSnapshot:
    title: str
    session_key: str
    exported_boundary: str
    declared_messages: int | None
    status: str
    source_sha256: str
    source_bytes: int
    source_path: str
    messages: tuple[ChatMessage, ...]
    redaction_count: int


def sha_bytes(value: bytes) -> str:
    return hashlib.sha256(value).hexdigest()


def sha_file(path: Path) -> str:
    digest = hashlib.sha256()
    with path.open("rb") as stream:
        for block in iter(lambda: stream.read(1024 * 1024), b""):
            digest.update(block)
    return digest.hexdigest()


def _slug(value: str) -> str:
    value = re.sub(r"[^a-z0-9]+", "-", value.lower()).strip("-")
    return value or "conversation"


def _clean_message(raw: str) -> str:
    value = raw.strip()
    value = re.sub(r"\n\s*---\s*$", "", value).strip()
    return value


def _redact(value: str) -> tuple[str, int]:
    count = 0

    def replace(match: re.Match[str]) -> str:
        nonlocal count
        count += 1
        return f"{match.group('prefix')}[REDACTED FOR PORTABLE HANDOFF]"

    return CREDENTIAL.sub(replace, value), count


def _parse_openai_canonical(path: Path, source_status: str) -> ChatSnapshot:
    try:
        payload = json.loads(path.read_text(encoding="utf-8"))
    except (OSError, json.JSONDecodeError) as exc:
        raise HandoffError(f"OpenAI canonical transcript is unreadable: {path}") from exc
    records = payload.get("records")
    if not isinstance(records, list) or not records:
        raise HandoffError("OpenAI canonical transcript contains no visible records")
    messages: list[ChatMessage] = []
    redactions = 0
    for record in records:
        role = record.get("role")
        if role not in {"user", "assistant"}:
            raise HandoffError(f"OpenAI canonical transcript has invalid role: {role!r}")
        source_id = str(record.get("id") or "").strip()
        if not source_id:
            raise HandoffError("OpenAI canonical transcript contains a record without stable message ID")
        content = str(record.get("text") or "")
        safe_content, found = _redact(content)
        redactions += found
        messages.append(
            ChatMessage(
                message_id=source_id,
                role=role,
                content=safe_content,
                content_sha256=sha_bytes(content.encode("utf-8")),
            )
        )
    timestamps = [record.get("timestamp") for record in records if record.get("timestamp") is not None]
    if timestamps:
        last = max(float(value) for value in timestamps)
        boundary = datetime.fromtimestamp(last, timezone.utc).isoformat()
    else:
        boundary = "UNKNOWN"
    title = str(payload.get("title") or "OpenAI Share Conversation")
    conversation_id = str(payload.get("conversation_id") or payload.get("source_conversation_id") or "")
    if not conversation_id:
        raise HandoffError("OpenAI canonical transcript has no conversation ID")
    return ChatSnapshot(
        title=title,
        session_key=_slug(title),
        exported_boundary=boundary,
        declared_messages=len(messages),
        status=source_status,
        source_sha256=sha_file(path),
        source_bytes=path.stat().st_size,
        source_path=str(path.resolve()),
        messages=tuple(messages),
        redaction_count=redactions,
    )


def parse_chatgpt_export(path: Path, status: str = "OPEN/INCOMPLETE") -> ChatSnapshot:
    if not path.is_file() or path.stat().st_size == 0:
        raise HandoffError(f"ChatGPT transcript is missing or empty: {path}")
    if path.suffix.lower() == ".json":
        return _parse_openai_canonical(path, status)
    raw_bytes = path.read_bytes()
    text = raw_bytes.decode("utf-8")
    first = next((line.strip() for line in text.splitlines() if line.strip()), "")
    if not first.startswith("# "):
        raise HandoffError("ChatGPT transcript title heading is missing")
    title = first[2:].strip()
    exported_match = EXPORTED.search(text)
    boundary = exported_match.group(1).strip() if exported_match else "UNKNOWN"
    declared_match = DECLARED_MESSAGES.search(text)
    declared = int(declared_match.group(1)) if declared_match else None
    session_key = _slug(title)
    markers = list(CHATGPT_ROLE.finditer(text))
    if not markers:
        raise HandoffError("ChatGPT transcript contains no recognizable user/assistant messages")
    if declared is not None and declared != len(markers):
        raise HandoffError(
            f"ChatGPT transcript message count mismatch: header={declared} parsed={len(markers)}"
        )
    messages: list[ChatMessage] = []
    redactions = 0
    for index, marker in enumerate(markers, start=1):
        end = markers[index].start() if index < len(markers) else len(text)
        content = _clean_message(text[marker.end():end])
        role = "user" if "You" in marker.group(1) else "assistant"
        safe_content, found = _redact(content)
        redactions += found
        message_id = f"CHATGPT-{sha_bytes(session_key.encode())[:10]}-{index:06d}-{role[0].upper()}"
        messages.append(
            ChatMessage(
                message_id=message_id,
                role=role,
                content=safe_content,
                content_sha256=sha_bytes(content.encode("utf-8")),
            )
        )
    return ChatSnapshot(
        title=title,
        session_key=session_key,
        exported_boundary=boundary,
        declared_messages=declared,
        status=status,
        source_sha256=sha_bytes(raw_bytes),
        source_bytes=len(raw_bytes),
        source_path=str(path.resolve()),
        messages=tuple(messages),
        redaction_count=redactions,
    )


def _conversation_paths(root: Path, record: dict[str, Any]) -> dict[str, Path]:
    project = record["project_id"]
    conversation = record.get("conversation") or {}
    codex = record.get("codex") or {}
    v2 = record.get("handoff_v2") or {}
    chatgpt_master = record.get("chatgpt_master") or conversation.get("master")
    codex_master = record.get("codex_portable_handoff") or codex.get("portable_record")
    if not chatgpt_master:
        raise HandoffError(f"project {project} has no registered ChatGPT master")
    if not codex_master:
        raise HandoffError(f"project {project} has no registered Codex portable master")
    return {
        "chatgpt_master": root / chatgpt_master,
        "codex_master": root / codex_master,
        "manifest": root / v2.get(
            "conversation_manifest",
            f"docs/process/conversation-handoff/{project}/handoff-v2-conversation-manifest.json",
        ),
    }


def _identity_patterns(record: dict[str, Any]) -> list[str]:
    return list((record.get("handoff_v2") or {}).get("chatgpt_title_patterns") or [])


def validate_project_identity(record: dict[str, Any], snapshot: ChatSnapshot) -> None:
    patterns = _identity_patterns(record)
    if not patterns:
        raise HandoffError(
            f"project {record['project_id']} has no governed ChatGPT title patterns; identity is AMBIGUOUS"
        )
    if not any(re.search(pattern, snapshot.title, re.IGNORECASE) for pattern in patterns):
        raise HandoffError(
            f"project identity mismatch: transcript title {snapshot.title!r} does not match {record['project_id']!r}"
        )


def _empty_manifest(record: dict[str, Any], master: Path, codex_master: Path) -> dict[str, Any]:
    return {
        "schema_version": "2.0",
        "renderer_version": RENDERER_VERSION,
        "project": {"id": record["project_id"], "name": record["display_name"]},
        "chatgpt": {"master_path": str(master), "sessions": {}},
        "codex": {
            "portable_master_path": str(codex_master),
            "status": "EXISTING_PORTABLE / FRESHNESS UNPROVEN",
            "sources": [],
            "warnings": [],
        },
        "house_context": {
            "supervisory_project": "jobcenter",
            "included": False,
            "classification": "NOT INCLUDED",
        },
    }


def _load_manifest(path: Path, record: dict[str, Any], master: Path, codex_master: Path) -> dict[str, Any]:
    if not path.is_file():
        return _empty_manifest(record, master, codex_master)
    payload = json.loads(path.read_text(encoding="utf-8"))
    if payload.get("project", {}).get("id") != record["project_id"]:
        raise HandoffError(f"conversation manifest project identity conflicts with {record['project_id']}")
    return payload


def _reconcile_chatgpt(
    record: dict[str, Any], snapshot: ChatSnapshot, master_path: Path, manifest: dict[str, Any], generated: str
) -> tuple[str, dict[str, Any], dict[str, Any]]:
    master = master_path.read_text(encoding="utf-8") if master_path.is_file() else (
        f"# {record['display_name']} — PORTABLE CHATGPT CURRENT RECORD\n\n"
        "Conversation evidence only. Current repository authority and explicit Engineering Director acceptance control.\n"
    )
    sessions = manifest.setdefault("chatgpt", {}).setdefault("sessions", {})
    prior = sessions.get(snapshot.session_key)
    old_messages = {item["id"]: item for item in (prior or {}).get("messages", [])}
    if prior and len(snapshot.messages) < len(old_messages):
        raise HandoffError(
            "later ChatGPT snapshot regresses the established message boundary; refusing history loss"
        )
    for message in snapshot.messages:
        existing = old_messages.get(message.message_id)
        if existing and existing["content_sha256"] != message.content_sha256:
            raise HandoffError(
                f"conflicting historical ChatGPT source at {message.message_id}; established content hash changed"
            )

    source_seen = any(
        item.get("source_sha256") == snapshot.source_sha256 for item in (prior or {}).get("snapshots", [])
    )
    additions: list[ChatMessage] = []
    for message in snapshot.messages:
        if message.message_id in old_messages:
            continue
        # Initial V2 adoption can overlap a pre-V2 portable master. Preserve the
        # historical body and avoid visibly duplicating exact message content.
        if not prior and len(message.content) >= 32 and message.content in master:
            continue
        additions.append(message)

    if additions:
        master += (
            f"\n\n## HANDOFF V2 {snapshot.status} SNAPSHOT — {snapshot.title}\n\n"
            f"- Session identity: `{snapshot.session_key}`\n"
            f"- Incorporated through: `{snapshot.exported_boundary}`\n"
            f"- Generated: `{generated}`\n"
            "- Freshness warning: newer messages may exist after this boundary.\n"
            "- Classification: conversation evidence, not project authority.\n"
        )
        for message in additions:
            master += (
                f"\n### {message.message_id} — {message.role.upper()}\n\n"
                f"{message.content}\n"
            )

    snapshots = list((prior or {}).get("snapshots", []))
    if not source_seen:
        snapshots.append(
            {
                "source_sha256": snapshot.source_sha256,
                "source_bytes": snapshot.source_bytes,
                "source_path": snapshot.source_path,
                "generated_at": generated,
                "incorporated_through": snapshot.exported_boundary,
                "status": snapshot.status,
                "message_count": len(snapshot.messages),
            }
        )
    all_messages = [
        {"id": item.message_id, "role": item.role, "content_sha256": item.content_sha256}
        for item in snapshot.messages
    ]
    sessions[snapshot.session_key] = {
        "title": snapshot.title,
        "status": snapshot.status,
        "incorporated_through": snapshot.exported_boundary,
        "declared_message_count": snapshot.declared_messages,
        "parsed_message_count": len(snapshot.messages),
        "first_message_id": snapshot.messages[0].message_id,
        "last_message_id": snapshot.messages[-1].message_id,
        "messages": all_messages,
        "snapshots": snapshots,
        "redaction_count": snapshot.redaction_count,
        "attachment_reference_count": sum(
            len(re.findall(r"(?i)\b[^\s/\\]+\.(?:md|txt|json|pdf|docx?|xlsx?|png|jpe?g|zip)\b", item.content))
            for item in snapshot.messages
        ),
        "attachment_warning": "Transcript attachment labels are references only unless the attachment payload is separately included.",
        "freshness_warning": "Source is open/incomplete; newer messages may exist after the stated boundary.",
    }
    manifest["renderer_version"] = RENDERER_VERSION
    manifest["generated_at"] = generated
    manifest["chatgpt"].update(
        {
            "master_path": str(master_path),
            "latest_session": snapshot.session_key,
            "latest_status": snapshot.status,
            "latest_boundary": snapshot.exported_boundary,
            "source_unchanged": source_seen,
        }
    )
    return master, manifest, {
        "source_unchanged": source_seen,
        "messages_added": len(additions),
        "snapshot_recorded": not source_seen,
    }


def _reconcile_codex(
    source: Path | None, codex_master_path: Path, manifest: dict[str, Any], generated: str
) -> tuple[str, dict[str, Any]]:
    current = codex_master_path.read_text(encoding="utf-8") if codex_master_path.is_file() else ""
    codex_meta = manifest.setdefault("codex", {})
    codex_meta.setdefault("sources", [])
    codex_meta.setdefault("warnings", [])
    if source is None:
        if current and codex_meta["sources"]:
            codex_meta["status"] = "LATEST INCORPORATED CODEX SNAPSHOT / NEWER ACTIVE STATE UNPROVEN"
            warning = "No current Codex source was resolved for this preparation; the latest incorporated snapshot was preserved."
            if warning not in codex_meta["warnings"]:
                codex_meta["warnings"].append(warning)
        elif current:
            codex_meta["status"] = "EXISTING PORTABLE RECORD / CURRENT SESSION NOT PROVEN"
        else:
            codex_meta["status"] = "INCOMPLETE / NO PORTABLE CODEX SOURCE"
            warning = "No accessible current Codex source was supplied or registered."
            if warning not in codex_meta["warnings"]:
                codex_meta["warnings"].append(warning)
        return current, {"status": codex_meta["status"], "updated": False}
    if not source.is_file():
        raise HandoffError(f"registered/current Codex source is unavailable: {source}")
    rendered = render_session(source, verify_stats=False)
    source_sha = sha_file(source)
    known = next((item for item in codex_meta["sources"] if item.get("session_id") == rendered.session_id), None)
    if known and known.get("source_sha256") == source_sha:
        codex_meta["status"] = "CURRENT ACCESSIBLE SOURCE / UNCHANGED"
        return current, {"status": codex_meta["status"], "updated": False}
    if known and known.get("source_sha256") != source_sha:
        raise HandoffError(
            f"Codex source for incorporated session {rendered.session_id} changed; refusing silent replacement"
        )
    body = rendered.redacted_text or rendered.transcript_text
    if not current:
        current = "# PORTABLE CODEX CURRENT RECORD\n\nConversation evidence only; repository authority controls.\n"
    current += (
        f"\n\n## HANDOFF V2 CODEX SNAPSHOT — {rendered.session_id}\n\n"
        f"- Generated: `{generated}`\n"
        f"- Incorporated through: `{rendered.last_timestamp or 'UNKNOWN'}`\n"
        f"- Source SHA-256: `{source_sha}`\n"
        f"- Publication: `{rendered.publication_status}`\n\n"
        f"{body}\n"
    )
    codex_meta["sources"].append(
        {
            "session_id": rendered.session_id,
            "source_path": str(source.resolve()),
            "source_sha256": source_sha,
            "incorporated_through": rendered.last_timestamp,
            "publication_status": rendered.publication_status,
            "credential_status": rendered.credential_status,
        }
    )
    codex_meta["status"] = "CURRENT ACCESSIBLE SOURCE INCORPORATED"
    codex_meta["portable_master_path"] = str(codex_master_path)
    return current, {"status": codex_meta["status"], "updated": True}


def _report_route(root: Path, record: dict[str, Any]) -> Path:
    handoff = record.get("handoff") or {}
    base = record.get("report_hopper") or handoff.get("report_hopper")
    label = record.get("report_label")
    if not base or not label:
        raise HandoffError(f"project {record['project_id']} has no registered Report/Hopper route")
    return root / str(base).strip("/") / f"Report ({label})"


def _copy_authority(root: Path, record: dict[str, Any], out: Path) -> list[dict[str, Any]]:
    out.mkdir()
    entries = []
    for index, item in enumerate(record.get("guidance_sources", []), start=1):
        source = root / item["path"]
        if not source.is_file():
            raise HandoffError(f"registered authority source is unavailable: {source}")
        dest = out / f"{index:02d}-{source.name}"
        shutil.copy2(source, dest)
        entries.append(
            {
                "filename": dest.name,
                "source": item["path"],
                "authority_role": item.get("role", "registered authority"),
                "sha256": sha_file(dest),
                "bytes": dest.stat().st_size,
            }
        )
    (out / "00-AUTHORITY-INDEX.json").write_text(
        json.dumps({"project": record["project_id"], "entries": entries}, indent=2, sort_keys=True) + "\n",
        encoding="utf-8",
    )
    return entries


def _terminal_state(root: Path, record: dict[str, Any], out: Path) -> dict[str, Any]:
    out.mkdir()
    report_dir = _report_route(root, record)
    candidates = [item for item in report_dir.iterdir() if item.is_file()] if report_dir.is_dir() else []
    candidates.sort(key=lambda item: (item.stat().st_mtime_ns, item.name), reverse=True)
    latest = next(
        (
            item for item in candidates
            if item.suffix.lower() in {".txt", ".md"}
            and "report" in item.name.lower()
            and not item.name.lower().startswith("manifest")
        ),
        None,
    )
    if latest is None:
        latest = next((item for item in candidates if item.suffix.lower() in {".txt", ".md"}), None)
    if latest:
        shutil.copy2(latest, out / f"latest-report{latest.suffix.lower()}")
    base = report_dir.parent
    ledger = base / "workflow-ledger.json"
    objective: dict[str, Any] | None = None
    cycles = [item for item in candidates if item.name.startswith("cycle-") and item.suffix == ".json"]
    if cycles:
        current_cycle = cycles[0]
        cycle_payload = json.loads(current_cycle.read_text(encoding="utf-8"))
        shutil.copy2(current_cycle, out / "current-cycle.json")
        objective = {
            key: cycle_payload.get(key)
            for key in (
                "ticket", "objective_id", "status", "cycle_id", "mode",
                "objective_owner", "acceptance_fixture", "commit", "push",
            )
            if cycle_payload.get(key) is not None
        }
        objective["source"] = "CURRENT VALIDATED REPORT/HOPPER CYCLE"
    if ledger.is_file():
        shutil.copy2(ledger, out / "workflow-ledger.json")
        if objective is None:
            tickets = json.loads(ledger.read_text(encoding="utf-8")).get("tickets", [])
            objective = tickets[-1] if tickets else None
    state = {
        "project": record["project_id"],
        "latest_report_source": str(latest) if latest else None,
        "latest_report_included": bool(latest),
        "objective": objective or {"status": "UNKNOWN", "warning": "No current workflow-ledger objective was available."},
    }
    (out / "terminal-state.json").write_text(json.dumps(state, indent=2, sort_keys=True) + "\n", encoding="utf-8")
    return state


def _startup_text(record: dict[str, Any], snapshot: ChatSnapshot, terminal: dict[str, Any], warnings: list[str]) -> str:
    objective = terminal.get("objective") or {}
    objective_name = objective.get("ticket") or objective.get("objective") or "UNKNOWN"
    objective_state = objective.get("status") or "UNKNOWN"
    missing = "; ".join(warnings) if warnings else "none recorded"
    semantic_state = "semantic authority unavailable"
    semantic_path = Path(__file__).resolve().parents[2] / "docs/process/conversation-handoff/shared/semantic-authority.json"
    if semantic_path.is_file():
        semantic = json.loads(semantic_path.read_text(encoding="utf-8"))
        semantic_state = f"catalog revision {semantic['catalog_revision']}; semantic revision {semantic['semantic_revision']}"
    return f"""# LOAD STARTUP — {record['display_name']}

When the Engineering Director says exactly `LOAD STARTUP`, perform this startup
procedure using only the supplied package. Essential startup must not depend on
access to the engineer's repository or filesystem.

1. Read `99-PACKAGE-MANIFEST.json` and verify every required component exists,
   is non-empty, hashes correctly, and identifies project `{record['project_id']}`.
2. Read `01-project-record.json`, then `02-authority/00-AUTHORITY-INDEX.json`
   and its files. Workflow V2 and current project authorities control.
3. Read `03-terminal/terminal-state.json` and its included latest report/ledger.
4. Read `05-chatgpt-master-manifest.json`, then `04-chatgpt-portable-master.md`.
5. Read the Codex manifest and portable record when present and materially useful.
6. Treat transcripts as continuity/provenance evidence, not automatic product,
   architecture, implementation, or approval authority.
7. Verify all components agree on the target project. Refuse silent cross-project
   blending. Any explicitly supplied Job Center house context is contextual
   evidence only and never target-project authority.
8. Surface missing, stale, ambiguous, or contradictory sources. This ChatGPT
   snapshot is `{snapshot.status}` through `{snapshot.exported_boundary}`; newer
   messages may exist after that boundary.
9. Preserve the Engineering Director's product authority and the project's stop
   boundaries. Do not infer authorization from historical conversation.
10. Carry forward relevant semantic authority state ({semantic_state}); it is
    distinct from transcript evidence and consumer-adoption decisions.

Current terminal objective at package generation: `{objective_name}` / `{objective_state}`.
Known missing/stale sources: {missing}.
Semantic authority: {semantic_state}.

Reply concisely:

```text
STARTUP LOADED
Project: {record['display_name']} ({record['project_id']})
Workflow: V2
Conversation through: {snapshot.exported_boundary} ({snapshot.status})
Current objective: {objective_name} / {objective_state}
Missing/stale sources: <none or list>
Semantic authority: <catalog/semantic revision>
READY
```
"""


def _write_component_manifest(package: Path, record: dict[str, Any], generated: str) -> dict[str, Any]:
    roles = {
        "00-LOAD-STARTUP.md": "STARTUP_INSTRUCTIONS",
        "01-project-record.json": "PROJECT_IDENTITY",
        "04-chatgpt-portable-master.md": "CHATGPT_PORTABLE_MASTER",
        "05-chatgpt-master-manifest.json": "CONVERSATION_PROVENANCE",
        "06-codex-portable-master.md": "CODEX_PORTABLE_MASTER",
        "07-codex-master-manifest.json": "CODEX_PROVENANCE",
    }
    components = []
    for path in sorted(item for item in package.rglob("*") if item.is_file() and item.name != "99-PACKAGE-MANIFEST.json"):
        relative = path.relative_to(package).as_posix()
        components.append(
            {
                "path": relative,
                "logical_role": roles.get(relative, "AUTHORITY_OR_TERMINAL_EVIDENCE"),
                "required": relative in roles or relative.startswith("02-authority/") or relative == "03-terminal/terminal-state.json",
                "bytes": path.stat().st_size,
                "sha256": sha_file(path),
            }
        )
    manifest = {
        "schema_version": "2.0",
        "workflow": "V2",
        "project": {"id": record["project_id"], "name": record["display_name"]},
        "generated_at": generated,
        "physical_representation": "VISIBLE_FILES_DIRECTORY",
        "delivery_format_decision": "DEFERRED; logical roles are format-independent",
        "transport_candidates": ["VISIBLE_FILES_DIRECTORY", "OPTIONAL_ZIP_WRAPPER"],
        "self_contained_for_chatgpt": True,
        "components": components,
    }
    required = {name for name in roles}
    present = {item["path"] for item in components}
    missing = required - present
    if missing:
        raise HandoffError(f"startup package is missing required components: {sorted(missing)}")
    if any(item["bytes"] <= 0 for item in components):
        raise HandoffError("startup package contains an empty component")
    (package / "99-PACKAGE-MANIFEST.json").write_text(
        json.dumps(manifest, indent=2, sort_keys=True) + "\n", encoding="utf-8"
    )
    return manifest


def _atomic_write(path: Path, text: str) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    fd, temporary = tempfile.mkstemp(prefix=f".{path.name}.", dir=path.parent)
    try:
        with os.fdopen(fd, "w", encoding="utf-8") as stream:
            stream.write(text)
        os.replace(temporary, path)
    finally:
        if os.path.exists(temporary):
            os.unlink(temporary)


def prepare(
    *,
    root: Path,
    project_record: Path,
    transcript: Path,
    output_root: Path,
    source_status: str = "OPEN/INCOMPLETE",
    codex_source: Path | None = None,
    include_house_context: bool = False,
    now: datetime | None = None,
) -> dict[str, Any]:
    record = json.loads(project_record.read_text(encoding="utf-8"))
    project = record.get("project_id")
    if not project:
        raise HandoffError("project record has no project_id")
    if project == "shared-workflow":
        raise HandoffError(
            "Shared Workflow has no independent ChatGPT project; prepare the explicit Job Center house context instead"
        )
    snapshot = parse_chatgpt_export(transcript, source_status)
    validate_project_identity(record, snapshot)  # decisive: no writes precede this
    paths = _conversation_paths(root, record)
    manifest = _load_manifest(paths["manifest"], record, paths["chatgpt_master"], paths["codex_master"])
    generated_dt = now or datetime.now(timezone.utc)
    generated = generated_dt.astimezone(timezone.utc).replace(microsecond=0).isoformat()
    chatgpt_master, manifest, chat_result = _reconcile_chatgpt(
        record, snapshot, paths["chatgpt_master"], manifest, generated
    )
    codex_master, codex_result = _reconcile_codex(codex_source, paths["codex_master"], manifest, generated)
    manifest["chatgpt"]["rendered_master_sha256"] = sha_bytes(chatgpt_master.encode("utf-8"))
    manifest["chatgpt"]["rendered_master_bytes"] = len(chatgpt_master.encode("utf-8"))
    manifest["codex"]["rendered_master_sha256"] = sha_bytes(codex_master.encode("utf-8")) if codex_master else None
    manifest["codex"]["rendered_master_bytes"] = len(codex_master.encode("utf-8")) if codex_master else 0
    manifest["project"] = {"id": project, "name": record["display_name"]}
    manifest["generated_at"] = generated
    manifest["house_context"] = {
        "supervisory_project": "jobcenter",
        "included": include_house_context,
        "classification": "CONTEXTUAL EVIDENCE / NOT TARGET AUTHORITY" if include_house_context else "NOT INCLUDED",
    }
    warnings = list(manifest.get("codex", {}).get("warnings", []))
    if source_status != "CLOSED":
        warnings.append(f"ChatGPT source is {source_status} through {snapshot.exported_boundary}.")

    prefix = (record.get("handoff") or {}).get("checkpoint_prefix") or record["display_name"].replace(" ", "-")
    package_name = f"{prefix}-CHATGPT-STARTUP-{generated_dt.strftime('%Y%m%d-%H%M%S')}"
    output_root.mkdir(parents=True, exist_ok=True)
    destination = output_root / package_name
    if destination.exists():
        raise HandoffError(f"refusing to overwrite startup package: {destination}")

    with tempfile.TemporaryDirectory(prefix=f".{project}-startup-", dir=output_root) as temporary:
        package = Path(temporary) / package_name
        package.mkdir()
        (package / "01-project-record.json").write_text(
            json.dumps(record, indent=2, sort_keys=True) + "\n", encoding="utf-8"
        )
        authority = _copy_authority(root, record, package / "02-authority")
        terminal = _terminal_state(root, record, package / "03-terminal")
        (package / "04-chatgpt-portable-master.md").write_text(chatgpt_master, encoding="utf-8")
        (package / "05-chatgpt-master-manifest.json").write_text(
            json.dumps(manifest, indent=2, sort_keys=True) + "\n", encoding="utf-8"
        )
        (package / "06-codex-portable-master.md").write_text(
            codex_master or "# CODEX PORTABLE RECORD\n\nINCOMPLETE: no portable Codex source is currently available.\n",
            encoding="utf-8",
        )
        (package / "07-codex-master-manifest.json").write_text(
            json.dumps(manifest["codex"], indent=2, sort_keys=True) + "\n", encoding="utf-8"
        )
        if include_house_context and project != "jobcenter":
            house_record_path = root / "docs/process/conversation-handoff/projects/jobcenter.json"
            if not house_record_path.is_file():
                raise HandoffError("explicit Job Center house context was requested but its project record is unavailable")
            house_record = json.loads(house_record_path.read_text(encoding="utf-8"))
            house = _conversation_paths(root, house_record)["chatgpt_master"]
            if not house.is_file():
                raise HandoffError("explicit Job Center house context was requested but is unavailable")
            context = package / "08-context"
            context.mkdir()
            shutil.copy2(house, context / "jobcenter-house-chatgpt-context.md")
            (context / "classification.json").write_text(
                json.dumps(
                    {
                        "source_project": "jobcenter",
                        "target_project": project,
                        "classification": "CONTEXTUAL EVIDENCE / NOT TARGET AUTHORITY",
                        "explicitly_requested": True,
                    },
                    indent=2,
                    sort_keys=True,
                ) + "\n",
                encoding="utf-8",
            )
        (package / "00-LOAD-STARTUP.md").write_text(
            _startup_text(record, snapshot, terminal, warnings), encoding="utf-8"
        )
        package_manifest = _write_component_manifest(package, record, generated)
        startup = (package / "00-LOAD-STARTUP.md").read_text(encoding="utf-8")
        if "/home/" in startup or "C:\\" in startup or "file://" in startup:
            raise HandoffError("startup instructions depend on an inaccessible local filesystem path")

        # All potentially failing identity, source, and package validation has
        # completed. Publish the updated transport records and package.
        _atomic_write(paths["chatgpt_master"], chatgpt_master)
        _atomic_write(paths["codex_master"], codex_master or (package / "06-codex-portable-master.md").read_text())
        _atomic_write(paths["manifest"], json.dumps(manifest, indent=2, sort_keys=True) + "\n")
        package.rename(destination)

    zip_destination = output_root / f"{package_name}.zip"
    if zip_destination.exists():
        raise HandoffError(f"refusing to overwrite startup package wrapper: {zip_destination}")
    fd, temporary_zip = tempfile.mkstemp(prefix=f".{package_name}.", suffix=".zip", dir=output_root)
    os.close(fd)
    try:
        with zipfile.ZipFile(temporary_zip, "w", zipfile.ZIP_DEFLATED) as archive:
            for member in sorted(path for path in destination.rglob("*") if path.is_file()):
                archive.write(member, member.relative_to(destination).as_posix())
        os.replace(temporary_zip, zip_destination)
    finally:
        if os.path.exists(temporary_zip):
            os.unlink(temporary_zip)

    return {
        "status": "HANDOFF READY",
        "project": project,
        "display_name": record["display_name"],
        "workflow": "V2",
        "chatgpt": {
            "status": snapshot.status,
            "boundary": snapshot.exported_boundary,
            **chat_result,
        },
        "codex": codex_result,
        "startup_payload": "VALIDATED",
        "package_directory": str(destination),
        "package_zip_candidate": str(zip_destination),
        "package_manifest": str(destination / "99-PACKAGE-MANIFEST.json"),
        "component_count": len(package_manifest["components"]),
        "authority_count": len(authority),
        "house_context_included": include_house_context,
        "warnings": warnings,
    }


def prepare_from_share(
    *,
    root: Path,
    project_record: Path,
    share_url: str,
    output_root: Path,
    archive_root: Path,
    source_status: str = "CLOSED",
    codex_source: Path | None = None,
    include_house_context: bool = False,
    now: datetime | None = None,
) -> dict[str, Any]:
    """Retrieve one OpenAI share, archive it, then use its JSON as handoff input."""
    from tools.codex_archive.openai_share_archive import archive as archive_share
    from tools.codex_archive.openai_share_index import build_indexes

    record = json.loads(project_record.read_text(encoding="utf-8"))
    if record.get("project_id") == "shared-workflow":
        raise HandoffError("Shared Workflow is an objective owner, not an independent ChatGPT target")
    archived = archive_share(share_url, record["project_id"], archive_root)
    indexes = build_indexes(archive_root, archived)
    canonical = Path(archived["directory"]) / "canonical-transcript.json"
    result = prepare(
        root=root,
        project_record=project_record,
        transcript=canonical,
        output_root=output_root,
        source_status=source_status,
        codex_source=codex_source,
        include_house_context=include_house_context,
        now=now,
    )
    result["share_archive"] = {
        "directory": archived["directory"],
        "manifest": str(Path(archived["directory"]) / "provenance-manifest.json"),
        **indexes,
    }
    return result
