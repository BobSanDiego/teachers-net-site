#!/usr/bin/env python3
"""Build bounded Job Center current conversation master records.

The ChatGPT source is resolved directly by the Codex app before this tool is
run; this tool records that proof and reuses the existing safe exported
baseline without pretending that a stale export is the live source.
"""

from __future__ import annotations

import argparse
import datetime as dt
import json
import time
from pathlib import Path

from codex_transcript_archive import render_session, sha256_file


PROJECT_RECORD = Path("docs/process/conversation-handoff/projects/jobcenter.json")


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--out", type=Path, required=True)
    parser.add_argument("--chatgpt-baseline", type=Path, required=True)
    parser.add_argument("--codex-fossil", type=Path, required=True)
    parser.add_argument("--chatgpt-live-continuation", type=Path, required=True)
    parser.add_argument("--active-source", type=Path)
    parser.add_argument("--project-record", type=Path, default=PROJECT_RECORD)
    args = parser.parse_args()
    generated = dt.datetime.now(dt.timezone.utc).replace(microsecond=0).isoformat()
    args.out.mkdir(parents=True, exist_ok=True)
    project_record = json.loads(args.project_record.read_text(encoding="utf-8"))
    conversation = project_record.get("companion_chat", project_record.get("conversation", {}))
    chatgpt_id = conversation.get("conversation_id", "")
    chatgpt_title = conversation.get("title", project_record.get("display_name", "Project"))
    active_source = args.active_source or Path(project_record.get("codex_active_source", ""))
    if not active_source.is_file():
        raise SystemExit("project record or --active-source must identify a readable active Codex source")

    baseline_sha = sha256_file(args.chatgpt_baseline)
    baseline_bytes = args.chatgpt_baseline.stat().st_size
    baseline_text = args.chatgpt_baseline.read_text(encoding="utf-8")
    live_text = args.chatgpt_live_continuation.read_text(encoding="utf-8")
    direct_proof = {
        "method": "DIRECT LIVE CHATGPT CONVERSATION",
        "title": chatgpt_title,
        "conversation_id": chatgpt_id,
        "retrieval_note": "Resolved with exact conversation ID and bounded newest-page read by Codex app.",
        "retrieval_result": "PASS",
        "stale_export_substitution": False,
        "baseline_relation": "Existing exported baseline retained as a safe coverage reference; live overlap is not silently deduplicated.",
    }
    chatgpt_path = args.out / "chatgpt-complete-current-record.md"
    chatgpt_path.write_text(
        "# JOB CENTER — CHATGPT COMPLETE CURRENT RECORD\n\n"
        f"Generated: `{generated}`\n\n"
        "## Evidence status\n\n"
        "This is a handoff record, not product authority. Conversation history may contain brainstorming, rejected ideas, incorrect statements, superseded decisions, and unresolved matters. Resolve authority through the project hierarchy and explicit Engineering Director acceptance.\n\n"
        "## Direct live source\n\n"
        f"- Title: `{chatgpt_title}`\n- Conversation ID: `{chatgpt_id}`\n"
        "- Retrieval: `DIRECT LIVE CHATGPT CONVERSATION`\n"
        "- Retrieval result: `PASS`\n"
        "- Coverage boundary: latest bounded direct-read page available at generation time; no claim is made that the live conversation is closed.\n"
        "- Known limitation: the Codex app reader exposes bounded pages, so the existing safe exported baseline is retained as a reference rather than silently rewritten or deduplicated.\n\n"
        "## Embedded historical baseline\n\n"
        f"- Source: `{args.chatgpt_baseline}`\n- SHA-256: `{baseline_sha}`\n- Bytes: `{baseline_bytes}`\n"
        "- Publication: `EMBEDDED_SAFE_BASELINE`\n\n"
        "```text\n" + baseline_text + "\n```\n\n"
        "## Embedded live current continuation\n\n"
        "The following is the exact bounded live continuation captured from the direct app read. It is kept separate because overlap with the historical baseline is not fully proven.\n\n"
        "```text\n" + live_text + "\n```\n\n"
        "## Provenance\n\n"
        f"```json\n{json.dumps(direct_proof, indent=2)}\n```\n",
        encoding="utf-8",
    )

    snapshot_start = time.perf_counter()
    rendered = render_session(active_source, verify_stats=False)
    snapshot_elapsed = time.perf_counter() - snapshot_start
    codex_path = args.out / "codex-complete-current-record.md"
    codex_path.write_text(
        "# JOB CENTER — CODEX COMPLETE CURRENT RECORD\n\n"
        f"Generated: `{generated}`\n\n"
        "## Evidence status\n\n"
        "This is a handoff record, not product authority. Conversation history may contain brainstorming, rejected ideas, incorrect statements, superseded decisions, and unresolved matters.\n\n"
        "## Closed canonical fossil (external forensic evidence)\n\n"
        f"- Fossil: `{args.codex_fossil}`\n- The full fossil remains preserved and unchanged in the repository.\n"
        "- It is intentionally not embedded in this routine portable handoff record.\n"
        "- Historical source conflicts remain quarantined and disclosed by the canonical manifest.\n\n"
        "## ACTIVE HANDOFF SNAPSHOT — NOT CLOSED ARCHIVAL SOURCE\n\n"
        f"- Session ID: `{rendered.session_id}`\n- Raw source: `{rendered.raw_source_path}`\n"
        f"- Source bytes: `{rendered.raw_bytes}`\n- Source lines consumed: `{rendered.raw_lines}`\n"
        f"- Last included timestamp: `{rendered.last_timestamp or ''}`\n"
        f"- Derived SHA-256: `{rendered.transcript_sha256}`\n- Derived bytes: `{rendered.transcript_bytes}`\n"
        f"- User messages: `{rendered.user_message_count}`\n- Assistant messages: `{rendered.assistant_message_count}`\n"
        f"- Credential/publication: `{rendered.publication_status}` / `{rendered.credential_status}`\n"
        f"- Extraction seconds: `{snapshot_elapsed:.6f}`\n"
        "- Classification: `ACTIVE HANDOFF SNAPSHOT — NOT CLOSED ARCHIVAL SOURCE`\n\n"
        "The raw active source was not modified, frozen, or added to the closed-session manifest.\n\n"
        "## Embedded active snapshot body\n\n"
        "```text\n" + (rendered.redacted_text or rendered.transcript_text) + "\n```\n",
        encoding="utf-8",
    )
    manifest = {
        "schema_version": "1.0",
        "project": "jobcenter",
        "generated_at": generated,
        "chatgpt": {"canonical_master_path": str(chatgpt_path), "live_conversation_title": chatgpt_title, "live_conversation_id": chatgpt_id, "retrieval_method": "exact-id bounded direct app read", "direct_live_retrieval": True, "baseline_sha256": baseline_sha, "baseline_bytes": baseline_bytes, "embedded_transcript_content": True, "external_transcript_dependency": False, "publication_status": "EMBEDDED_SAFE_BASELINE_PLUS_LIVE_BOUNDARY"},
        "codex": {"canonical_master_path": str(codex_path), "closed_fossil_path": str(args.codex_fossil), "closed_fossil_embedded": False, "closed_fossil_sha256": sha256_file(args.codex_fossil), "active_snapshot_session_id": rendered.session_id, "active_snapshot_source": rendered.raw_source_path, "active_snapshot_boundary_bytes": rendered.raw_bytes, "active_snapshot_boundary_line": rendered.raw_lines, "active_snapshot_last_timestamp": rendered.last_timestamp, "active_snapshot_user_messages": rendered.user_message_count, "active_snapshot_assistant_messages": rendered.assistant_message_count, "sha256": rendered.transcript_sha256, "bytes": rendered.transcript_bytes, "embedded_transcript_content": True, "external_transcript_dependency": False, "publication_status": rendered.publication_status, "credential_status": rendered.credential_status, "historical_source_conflicts": ["019f5133-2e24-72c2-9f5a-725c2fba64de", "019f605b-5be2-7802-8857-4d545657645a"]},
        "project_record": project_record,
    }
    (args.out / "conversation-master-manifest.json").write_text(json.dumps(manifest, indent=2, sort_keys=True) + "\n", encoding="utf-8")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
