#!/usr/bin/env python3
"""Focused, synthetic coverage for the bounded ChatGPT-sync package owner."""
from __future__ import annotations

import json
import tempfile
import unittest
from pathlib import Path

import sync
from sync import SyncError, acknowledge, build, recommend


def item(item_id: str, role: str, text: str, truncated: bool = False) -> dict:
    if role == "user":
        return {"type": "userMessage", "id": item_id, "content": [{"type": "text", "text": text}], "truncated": truncated}
    return {"type": "agentMessage", "id": item_id, "text": text, "truncated": truncated}


def page(project: str, thread: str, title: str, turns: list[dict], more: bool = False) -> dict:
    return {"thread": {"id": thread, "title": title, "projectId": "account"}, "turns": turns, "page": {"hasMore": more}}


class SyncTests(unittest.TestCase):
    def setUp(self) -> None:
        self.temp = tempfile.TemporaryDirectory()
        self.root = Path(self.temp.name)
        self.registry = self.root / "registry.json"
        self.state = self.root / "state.json"
        self.archive = self.root / "archive"
        self.registry.write_text(json.dumps({"projects": [
            {"project_id": "alpha", "state": "ACTIVE", "thread_id": "thread-alpha", "expected_title": "Alpha", "account_project_id": "account"},
            {"project_id": "beta", "state": "ACTIVE", "thread_id": "thread-beta", "expected_title": "Beta", "account_project_id": "account"},
            {"project_id": "community", "state": "UNREGISTERED", "thread_id": None, "expected_title": None},
        ]}), encoding="utf-8")

    def tearDown(self) -> None:
        self.temp.cleanup()

    def reader(self, alpha_items: list[dict], beta_items: list[dict]) -> Path:
        path = self.root / "reader.json"
        path.write_text(json.dumps({"sources": [
            {"project": "alpha", "pages": [page("alpha", "thread-alpha", "Alpha", [{"id": "ta", "items": alpha_items}])]},
            {"project": "beta", "pages": [page("beta", "thread-beta", "Beta", [{"id": "tb", "items": beta_items}])]},
        ]}), encoding="utf-8")
        return path

    def seed_state(self) -> None:
        self.state.write_text(json.dumps({"schema_version": 1, "next_generation": 1, "sources": {
            "alpha": {"last_item_id": "a-old"}, "beta": {"last_item_id": "b-old"}
        }, "generations": []}), encoding="utf-8")

    def test_incremental_package_and_ack_are_independent(self) -> None:
        self.seed_state()
        result = build(self.registry, self.state, self.reader([item("a-new", "user", "new alpha"), item("a-old", "assistant", "old")], [item("b-new", "assistant", "new beta"), item("b-old", "user", "old")]), self.archive)
        self.assertEqual("G1", result["id"])
        self.assertEqual("a-new", result["sources"][0]["end_item_id"])
        self.assertEqual({"alpha": "PENDING", "beta": "PENDING"}, result["recipients"])
        payload = Path(result["payload"]).read_text(encoding="utf-8")
        self.assertIn(f"SYNC ACK: G1 {result['payload_sha256']}", payload)
        ack = self.root / "ack.json"
        ack.write_text(json.dumps(page("alpha", "thread-alpha", "Alpha", [{"id": "ack-turn", "items": [item("ack", "assistant", f"SYNC ACK: G1 {result['payload_sha256']}")]}])), encoding="utf-8")
        acknowledged = acknowledge(self.registry, self.state, ack, "alpha", "G1")
        self.assertEqual("ACKNOWLEDGED", acknowledged["recipients"]["alpha"])
        self.assertEqual("PENDING", acknowledged["recipients"]["beta"])

    def test_identity_missing_boundary_and_truncation_fail_closed(self) -> None:
        self.seed_state()
        bad = self.reader([item("a-new", "user", "new")], [item("b-new", "assistant", "new")])
        with self.assertRaisesRegex(SyncError, "prior boundary"):
            build(self.registry, self.state, bad, self.archive)
        self.seed_state()
        truncated = self.reader([item("a-new", "user", "new", True), item("a-old", "assistant", "old")], [item("b-new", "assistant", "new"), item("b-old", "user", "old")])
        with self.assertRaisesRegex(SyncError, "truncated"):
            build(self.registry, self.state, truncated, self.archive)
        data = json.loads(bad.read_text(encoding="utf-8"))
        data["sources"][0]["pages"][0]["thread"]["id"] = "wrong"
        bad.write_text(json.dumps(data), encoding="utf-8")
        with self.assertRaisesRegex(SyncError, "identity mismatch"):
            build(self.registry, self.state, bad, self.archive)

    def test_recommendation_is_metadata_only(self) -> None:
        self.seed_state()
        result = recommend(self.state, "CROSS_PROJECT_IMPACT")
        self.assertFalse(result["reader_accessed"])
        self.assertEqual("UPDATE CHATGPT", result["command"])

    def test_source_quota_blocks_before_package_write(self) -> None:
        self.seed_state()
        original = sync.MAX_SOURCE_CHARS
        sync.MAX_SOURCE_CHARS = 3
        try:
            source = self.reader([item("a-new", "user", "long"), item("a-old", "assistant", "old")], [item("b-new", "assistant", "long"), item("b-old", "user", "old")])
            with self.assertRaisesRegex(SyncError, "DELTA TOO LARGE"):
                build(self.registry, self.state, source, self.archive)
            self.assertFalse(self.archive.exists())
        finally:
            sync.MAX_SOURCE_CHARS = original

    def test_stale_recipient_remains_pending_across_generations(self) -> None:
        self.seed_state()
        first = build(self.registry, self.state, self.reader([item("a-one", "user", "one"), item("a-old", "assistant", "old")], [item("b-one", "assistant", "one"), item("b-old", "user", "old")]), self.archive)
        ack = self.root / "ack.json"
        ack.write_text(json.dumps(page("alpha", "thread-alpha", "Alpha", [{"id": "ack-turn", "items": [item("ack", "assistant", f"SYNC ACK: G1 {first['payload_sha256']}")]}])), encoding="utf-8")
        acknowledge(self.registry, self.state, ack, "alpha", "G1")
        second = build(self.registry, self.state, self.reader([item("a-two", "user", "two"), item("a-one", "user", "one")], [item("b-two", "assistant", "two"), item("b-one", "assistant", "one")]), self.archive)
        self.assertEqual("PENDING", second["recipients"]["alpha"])
        self.assertEqual("PENDING", second["recipients"]["beta"])
        state = json.loads(self.state.read_text(encoding="utf-8"))
        self.assertEqual("ACKNOWLEDGED", state["generations"][0]["recipients"]["alpha"])
        self.assertEqual("PENDING", state["generations"][0]["recipients"]["beta"])


if __name__ == "__main__":
    unittest.main()
