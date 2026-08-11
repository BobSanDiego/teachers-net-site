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

    def test_visible_thread_derivative_exhausts_pages_and_preserves_content(self):
        pages = [
            {"thread": {"id": "exact", "title": "Example"}, "page": {"hasMore": True},
             "turns": [{"id": "t2", "items": [{"type": "agentMessage", "id": "a2", "text": "second"}, {"type": "reasoning", "id": "r2", "summary": ["omit"]}]}]},
            {"thread": {"id": "exact", "title": "Example"}, "page": {"hasMore": False},
             "turns": [{"id": "t1", "items": [{"type": "userMessage", "id": "u1", "content": [{"type": "text", "text": "first"}]}, {"type": "fileChange", "id": "f1"}]}]},
        ]
        with tempfile.TemporaryDirectory() as tmp:
            out = Path(tmp) / "derivative.txt"
            result = archive.render_visible_thread_derivative(pages, out)
            text = out.read_text(encoding="utf-8")
        self.assertEqual(result["provenance"], "CODEX_VISIBLE_THREAD_DERIVATIVE")
        self.assertEqual(result["visible_message_count"], 2)
        self.assertIn("first", text)
        self.assertIn("second", text)
        self.assertNotIn("omit", text)
        self.assertNotIn("fileChange", text)

    def test_visible_thread_derivative_fails_closed(self):
        page = {"thread": {"id": "exact"}, "page": {"hasMore": True}, "turns": []}
        with tempfile.TemporaryDirectory() as tmp:
            with self.assertRaises(ValueError):
                archive.render_visible_thread_derivative([page], Path(tmp) / "x")
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

    def test_recursive_discovery_groups_duplicate_superset_sources(self) -> None:
        with tempfile.TemporaryDirectory() as tmp:
            base = Path(tmp)
            session_id = "019f605b-5be2-7802-8857-4d545657645a"
            shorter = base / "archived_sessions" / "a.jsonl"
            longer = base / "archived_sessions" / "nested" / "b.jsonl"
            shorter.parent.mkdir(parents=True)
            longer.parent.mkdir(parents=True)
            short_records = session_records(session_id)
            write_jsonl(shorter, short_records)
            write_jsonl(longer, short_records + [
                {"type": "response_item", "payload": {"type": "message", "role": "assistant", "content": [{"text": "Later visible message"}]}}
            ])

            found = archive.discover([base / "archived_sessions"])
            self.assertEqual(len(found["include"]), 1)
            self.assertEqual(found["include"][0]["relationship_status"], "PROVEN_SUPERSET")
            self.assertEqual(Path(found["include"][0]["path"]), longer)
            self.assertEqual(len(found["ambiguous"]), 0)

            reverse = archive.discover([base / "archived_sessions" / "nested", base / "archived_sessions"])
            self.assertEqual(reverse["include"][0]["path"], found["include"][0]["path"])

    def test_identical_duplicate_sources_are_deduplicated(self) -> None:
        with tempfile.TemporaryDirectory() as tmp:
            base = Path(tmp)
            session_id = "019fdupe0-aaaa-bbbb-cccc-ddddeeeeeeee"
            one = base / "one.jsonl"
            two = base / "nested" / "two.jsonl"
            two.parent.mkdir()
            records = session_records(session_id)
            write_jsonl(one, records)
            write_jsonl(two, records)

            found = archive.discover([base])
            self.assertEqual(len(found["include"]), 1)
            self.assertEqual(found["include"][0]["relationship_status"], "IDENTICAL_DUPLICATES")

    def test_conflicting_duplicate_sources_are_not_included(self) -> None:
        with tempfile.TemporaryDirectory() as tmp:
            base = Path(tmp)
            session_id = "019fconf0-aaaa-bbbb-cccc-ddddeeeeeeee"
            one = base / "one.jsonl"
            two = base / "two.jsonl"
            records = session_records(session_id)
            write_jsonl(one, records + [
                {"type": "response_item", "payload": {"type": "message", "role": "assistant", "content": [{"text": "Branch A"}]}}
            ])
            write_jsonl(two, records + [
                {"type": "response_item", "payload": {"type": "message", "role": "assistant", "content": [{"text": "Branch B"}]}}
            ])

            found = archive.discover([base])
            self.assertEqual(len(found["include"]), 0)
            self.assertEqual(found["ambiguous"][0]["relationship_status"], "SOURCE_CONFLICT")

    def test_active_recursive_source_is_deferred_not_included(self) -> None:
        with tempfile.TemporaryDirectory() as tmp:
            base = Path(tmp)
            active = base / "sessions" / "2026" / "08" / "10" / "active.jsonl"
            active.parent.mkdir(parents=True)
            write_jsonl(active, session_records("019factive-aaaa-bbbb-cccc-ddddeeeeeeee"))

            previous_roots = archive.DEFAULT_SOURCE_DIRS
            try:
                archive.DEFAULT_SOURCE_DIRS = [base / "sessions"]
                found = archive.discover([base / "sessions"])
            finally:
                archive.DEFAULT_SOURCE_DIRS = previous_roots

            self.assertEqual(len(found["include"]), 0)
            self.assertEqual(len(found["active_deferred"]), 1)
            self.assertEqual(found["active_deferred"][0]["classification"], "ACTIVE / DEFERRED")

    def test_ticket_terminator_contract_rejects_truncation(self) -> None:
        valid = """TICKET READY FOR CODEX
Ticket: JC999-TEST

Objective
Do the bounded thing.

END TICKET — JC999-TEST
"""
        self.assertTrue(archive.validate_ticket_payload(valid)["terminator_valid"])
        with self.assertRaisesRegex(ValueError, "terminator mismatch"):
            archive.validate_ticket_payload(valid.replace("END TICKET — JC999-TEST", ""))
        with self.assertRaisesRegex(ValueError, "terminator mismatch"):
            archive.validate_ticket_payload(valid.replace("JC999-TEST\n", "JC999-OTHER\n", 1))

    def test_ticket_authority_preserves_continuation_payload(self) -> None:
        ticket = """TICKET READY FOR CODEX
Ticket: JC999-TEST

Objective
Original.

END TICKET — JC999-TEST
"""
        continuation = "TICKET CONTINUATION — JC999-TEST\nAdditional bounded authority."
        composed = archive.compose_ticket_authority([ticket, continuation])
        self.assertIn("Original.", composed)
        self.assertIn("Additional bounded authority.", composed)
        self.assertIn("--- CONTINUATION / AMENDMENT ---", composed)


if __name__ == "__main__":
    unittest.main()
