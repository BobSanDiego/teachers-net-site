"""Generated navigation indexes for the shared OpenAI conversation archive."""
from __future__ import annotations

import html
import json
import re
from pathlib import Path
from typing import Any

TICKET_RE = re.compile(r"(?m)^(?:TICKET READY FOR CODEX\s*)?([A-Z][A-Z0-9-]{4,})\s+[—-]\s+(.+?)\s*$")


def _write(path: Path, payload: Any) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    if isinstance(payload, str):
        path.write_text(payload, encoding="utf-8")
    else:
        path.write_text(json.dumps(payload, ensure_ascii=False, indent=2, sort_keys=True) + "\n", encoding="utf-8")


def build_indexes(archive_root: Path, archive: dict[str, Any]) -> dict[str, str]:
    data = archive["data"]
    directory = Path(archive["directory"])
    project = archive["manifest"]["project"]
    records = data["records"]
    session = {
        "project": project,
        "title": data.get("title"),
        "openai_conversation_id": data.get("conversation_id"),
        "share_url": archive["manifest"]["share_url"],
        "date_range": archive["manifest"]["boundary"],
        "canonical_transcript": str(directory / "canonical-transcript.md"),
        "structured_record": str(directory / "canonical-transcript.json"),
        "provenance": str(directory / "provenance-manifest.json"),
        "accepted_source_boundary": archive["manifest"]["boundary"]["last_id"],
        "aspects": [
            aspect for aspect, marker in (
                ("Job Center wizard / Pro Editor", "JC053-"),
                ("Multiple Locations", "MULTILOC"),
                ("Shared Workflow handoff", "SHARED-"),
            ) if any(marker in (record.get("text") or "") for record in records)
        ],
        "visible_turns": len(records),
    }
    session_path = archive_root / "session-ledger.json"
    existing_sessions = json.loads(session_path.read_text()) if session_path.exists() else {"schema_version": "1", "sessions": []}
    existing_sessions["sessions"] = [item for item in existing_sessions["sessions"]
                                     if item.get("openai_conversation_id") != session["openai_conversation_id"]]
    existing_sessions["sessions"].append(session)
    _write(session_path, existing_sessions)

    ticket_entries: dict[str, dict[str, Any]] = {}
    for record in records:
        if record.get("role") != "user":
            continue
        for match in TICKET_RE.finditer(record.get("text") or ""):
            ticket_id, title = match.group(1), match.group(2).strip()
            if ticket_id.startswith(("TICKET", "END")):
                continue
            ticket_entries.setdefault(ticket_id, {
                "ticket_id": ticket_id,
                "title": title,
                "project": project,
                "objective_owner": "UNKNOWN / NOT INFERRED",
                "source_message_uuid": record.get("id"),
                "status": "UNRESOLVED / NO TERMINAL EVIDENCE INDEXED",
                "report_hopper_cycle": None,
                "commit": None,
                "source_transcript": str(directory / "canonical-transcript.md"),
                "terminal_evidence": None,
            })
    ticket_path = archive_root / "ticket-ledger.json"
    existing_tickets = json.loads(ticket_path.read_text()) if ticket_path.exists() else {"schema_version": "1", "tickets": []}
    prior = {item["ticket_id"]: item for item in existing_tickets["tickets"]}
    prior.update(ticket_entries)
    existing_tickets["tickets"] = sorted(prior.values(), key=lambda item: item["ticket_id"])
    _write(ticket_path, existing_tickets)

    project_name = html.escape(str(project))
    session_title = html.escape(str(session["title"]))
    rel_transcript = html.escape(str(Path(session["canonical_transcript"]).relative_to(archive_root)))
    rows = [
        "<!doctype html><meta charset='utf-8'><title>Teachers.Net OpenAI Share Archive</title>",
        "<style>body{font:15px system-ui;max-width:980px;margin:2rem auto}summary{cursor:pointer}code{font-size:.9em}</style>",
        "<h1>Teachers.Net OpenAI Share Archive</h1>",
        f"<details open><summary>{project_name}</summary><ul>",
        f"<li><strong>{session_title}</strong> — {len(records)} visible turns<br>"
        f"<a href='{rel_transcript}'>canonical transcript</a></li>",
        "<li>Tickets: generated ledger; outcomes are not inferred from issuance.</li>",
        "</ul></details>",
    ]
    _write(archive_root / "index.html", "\n".join(rows) + "\n")
    return {"session_ledger": str(session_path), "ticket_ledger": str(ticket_path), "index": str(archive_root / "index.html")}
