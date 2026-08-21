#!/usr/bin/env python3
"""Regression coverage for live companion resolution and stale reconciliation."""
from __future__ import annotations

import json
import tempfile
import unittest
from datetime import datetime, timezone
from pathlib import Path

try:
    from companion_reader import CompanionReaderError, resolve_and_reconcile
except ImportError:  # pragma: no cover - package test invocation
    from tools.workflow.companion_reader import CompanionReaderError, resolve_and_reconcile


def reader(source_id: str, title: str, listed: list[dict]) -> dict:
    return {
        "source": {"id": source_id, "kind": "chatgpt", "title": title, "projectId": "account-project"},
        "listed_threads": listed,
        "pages": [{"page": {"order": "newest_first", "hasMore": False}, "turns": []}],
    }


class CompanionReaderTests(unittest.TestCase):
    def setUp(self) -> None:
        self.temp = tempfile.TemporaryDirectory()
        root = Path(self.temp.name)
        self.record = root / "project.json"
        self.registry = root / "registry.json"
        self.reader = root / "reader.json"
        self.record.write_text(json.dumps({"project_id": "fixture", "handoff_v2": {"chatgpt_title_patterns": [r"^Fixture \(8/20/26\)$"]}}), encoding="utf-8")
        self.registry.write_text(json.dumps({"projects": [{"project_id": "fixture", "state": "ACTIVE", "thread_id": "old", "expected_title": "Fixture (8/19/26)", "account_project_id": "account-project", "replacement_history": []}]}), encoding="utf-8")

    def test_stale_unique_live_source_reconciles_canonically(self) -> None:
        current = {"id": "new", "kind": "chatgpt", "title": "Fixture (8/20/26)", "projectId": "account-project"}
        self.reader.write_text(json.dumps(reader("new", "Fixture (8/20/26)", [current])), encoding="utf-8")
        result = resolve_and_reconcile(project_record_path=self.record, registry_path=self.registry, reader_path=self.reader, now=datetime(2026, 8, 20, tzinfo=timezone.utc))
        self.assertTrue(result["reconciled"])
        self.assertEqual(json.loads(self.registry.read_text())["projects"][0]["thread_id"], "new")
        self.assertEqual(json.loads(self.record.read_text())["companion_chat"]["conversation_id"], "new")

    def test_ambiguous_stale_source_fails_closed(self) -> None:
        first = {"id": "new", "kind": "chatgpt", "title": "Fixture (8/20/26)", "projectId": "account-project"}
        second = {"id": "also-new", "kind": "chatgpt", "title": "Fixture (8/20/26)", "projectId": "account-project"}
        self.reader.write_text(json.dumps(reader("new", "Fixture (8/20/26)", [first, second])), encoding="utf-8")
        with self.assertRaisesRegex(CompanionReaderError, "ambiguous"):
            resolve_and_reconcile(project_record_path=self.record, registry_path=self.registry, reader_path=self.reader)


if __name__ == "__main__":
    unittest.main()
