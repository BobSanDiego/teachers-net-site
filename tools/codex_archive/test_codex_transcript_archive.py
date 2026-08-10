#!/usr/bin/env python3
"""Synthetic tests for the maintained Codex transcript archive tool."""

from __future__ import annotations

import json
import tempfile
import time
import unittest
from pathlib import Path
import sys

sys.path.insert(0, str(Path(__file__).resolve().parent))
import codex_transcript_archive as archive


def write_jsonl(path: Path, records: list[dict]) -> None:
    path.write_text("\n".join(json.dumps(record) for record in records) + "\n", encoding="utf-8")


def session_records(session_id: str = "019ftest0-aaaa-bbbb-cccc-ddddeeeeeeee") -> list[dict]:
    return [
        {"type": "session_meta", "payload": {"id": session_id, "cwd": "/home/bobreap/projects/teachers-net-site", "title": "Job Center Test"}},
        {"type": "response_item", "payload": {"type": "message", "role": "system", "content": [{"text": "SYSTEM MUST NOT APPEAR"}]}},
        {"type": "response_item", "payload": {"type": "message", "role": "developer", "content": [{"text": "DEVELOPER MUST NOT APPEAR"}]}},
        {"type": "response_item", "payload": {"type": "message", "role": "user", "content": [{"type": "input_text", "text": "Hello with attachment report.txt"}]}, "timestamp": "2026-01-01T00:00:01Z"},
        {"type": "event_msg", "payload": {"type": "user_message", "message": "Hello with attachment report.txt"}},
        {"type": "response_item", "payload": {"type": "function_call", "name": "exec", "arguments": "RAW TOOL CALL MUST NOT APPEAR"}},
        {"type": "response_item", "payload": {"type": "function_call_output", "output": "RAW TOOL OUTPUT MUST NOT APPEAR"}},
        {"type": "response_item", "payload": {"type": "reasoning", "summary": "HIDDEN REASONING MUST NOT APPEAR"}},
        {"type": "response_item", "payload": {"type": "message", "role": "assistant", "content": [{"type": "output_text", "text": "Visible answer"}]}, "timestamp": "2026-01-01T00:00:02Z"},
        {"type": "response_item", "payload": {"type": "message", "role": "user", "content": [{"type": "input_text", "text": "<app-context>\nINJECTED MUST NOT APPEAR"}]}, "timestamp": "2026-01-01T00:00:03Z"},
    ]


class CodexTranscriptArchiveTests(unittest.TestCase):
    def test_schema_allowlist_order_ids_and_exclusions(self) -> None:
        with tempfile.TemporaryDirectory() as tmp:
            source = Path(tmp) / "session.jsonl"
            write_jsonl(source, session_records())
            result = archive.render_session(source, verify_stats=True)
            text = result.transcript_text

            self.assertIn("CODEX-019ftest0-U0001", text)
            self.assertIn("CODEX-019ftest0-A0001", text)
            self.assertLess(text.index("Hello with attachment report.txt"), text.index("Visible answer"))
            self.assertIn("report.txt", text)
            self.assertNotIn("SYSTEM MUST NOT APPEAR", text)
            self.assertNotIn("DEVELOPER MUST NOT APPEAR", text)
            self.assertNotIn("RAW TOOL CALL MUST NOT APPEAR", text)
            self.assertNotIn("RAW TOOL OUTPUT MUST NOT APPEAR", text)
            self.assertNotIn("HIDDEN REASONING MUST NOT APPEAR", text)
            self.assertNotIn("INJECTED MUST NOT APPEAR", text)
            self.assertEqual(result.user_message_count, 1)
            self.assertEqual(result.assistant_message_count, 1)

    def test_credential_gate_and_redacted_publication_derivative(self) -> None:
        with tempfile.TemporaryDirectory() as tmp:
            source = Path(tmp) / "session.jsonl"
            records = session_records()
            records.insert(4, {"type": "response_item", "payload": {"type": "message", "role": "user", "content": [{"type": "input_text", "text": "Password: swordfish"}]}})
            write_jsonl(source, records)
            result = archive.render_session(source)

            self.assertEqual(result.credential_status, "POTENTIAL_CREDENTIAL_MATCHES")
            self.assertEqual(result.publication_status, "REDACTED_PUBLICATION_DERIVATIVE")
            self.assertIsNotNone(result.redacted_text)
            self.assertIn("[REDACTED:password_assignment]", result.redacted_text or "")
            self.assertIn("Password: swordfish", result.transcript_text)

    def test_incremental_manifest_idempotence_and_no_rerender(self) -> None:
        with tempfile.TemporaryDirectory() as tmp:
            base = Path(tmp)
            source = base / "session.jsonl"
            out = base / "archive"
            write_jsonl(source, session_records())

            first = archive.incorporate(source, out, "fast")
            fossil_before = (out / "codex-conversation-fossil.md").read_text(encoding="utf-8")
            second = archive.incorporate(source, out, "fast")
            fossil_after = (out / "codex-conversation-fossil.md").read_text(encoding="utf-8")

            self.assertEqual(first["status"], "INCORPORATED")
            self.assertEqual(second["status"], "NO_NEW_CLOSED_SESSIONS")
            self.assertFalse(second["rerendered"])
            self.assertEqual(fossil_before, fossil_after)

    def test_changed_incorporated_source_stops(self) -> None:
        with tempfile.TemporaryDirectory() as tmp:
            base = Path(tmp)
            source = base / "session.jsonl"
            out = base / "archive"
            write_jsonl(source, session_records())
            archive.incorporate(source, out, "fast")
            time.sleep(0.01)
            with source.open("a", encoding="utf-8") as f:
                f.write(json.dumps({"type": "response_item", "payload": {"type": "message", "role": "user", "content": [{"text": "Changed"}]}}) + "\n")

            with self.assertRaisesRegex(RuntimeError, "source size changed|source mtime changed"):
                archive.incorporate(source, out, "fast")

    def test_fast_and_verify_use_same_core_extraction(self) -> None:
        with tempfile.TemporaryDirectory() as tmp:
            source = Path(tmp) / "session.jsonl"
            write_jsonl(source, session_records())
            fast = archive.render_session(source, verify_stats=False)
            verify = archive.render_session(source, verify_stats=True)
            self.assertEqual(fast.transcript_sha256, verify.transcript_sha256)


if __name__ == "__main__":
    unittest.main()
