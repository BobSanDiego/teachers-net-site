from __future__ import annotations

import json
import tempfile
import unittest
from pathlib import Path

try:
    from openai_share_archive import _decode_html, archive
    from openai_share_index import build_indexes
    from prepare_chatgpt_handoff import parse_chatgpt_export
except ImportError:
    from tools.codex_archive.openai_share_archive import _decode_html, archive
    from tools.codex_archive.openai_share_index import build_indexes
    from tools.codex_archive.prepare_chatgpt_handoff import parse_chatgpt_export


ROOT = Path(__file__).resolve().parents[2]
FIXTURE = ROOT / "tmp/hopper/shared-workflow/openai-share-archive001/raw-share-01.html"


class OpenAIShareArchiveTests(unittest.TestCase):
    def test_proven_share_decodes_to_meaningful_uuid_records(self):
        data = _decode_html(FIXTURE.read_bytes())
        self.assertEqual(data["conversation_id"], "6a81dc34-37ac-83e8-937c-2cd4d2e1967b")
        self.assertEqual(data["records"][0]["id"], "2666f042-2cfb-476c-ad8e-e3a35b49355d")
        self.assertEqual(len(data["records"]), 784)
        self.assertTrue(all(item["role"] in {"user", "assistant"} for item in data["records"]))
        text = "\n".join(item["text"] for item in data["records"])
        self.assertIn("JC053-MULTILOC001", text)

    def test_archive_and_indexes_are_idempotent(self):
        with tempfile.TemporaryDirectory() as tmp:
            root = Path(tmp)
            first = archive("https://chatgpt.com/share/fixture", "jobcenter", root, FIXTURE.read_bytes())
            second = archive("https://chatgpt.com/share/fixture", "jobcenter", root, FIXTURE.read_bytes())
            first_json = Path(first["directory"]) / "canonical-transcript.json"
            second_json = Path(second["directory"]) / "canonical-transcript.json"
            self.assertEqual(first_json.read_bytes(), second_json.read_bytes())
            indexes = build_indexes(root, first)
            self.assertTrue(Path(indexes["session_ledger"]).is_file())
            self.assertTrue(Path(indexes["ticket_ledger"]).is_file())
            self.assertTrue(Path(indexes["index"]).is_file())
            ledger = json.loads(Path(indexes["ticket_ledger"]).read_text())
            self.assertTrue(ledger["tickets"])
            self.assertTrue(all(item["status"].startswith("UNRESOLVED") for item in ledger["tickets"]))

    def test_handoff_parser_uses_openai_uuid_as_message_identity(self):
        data = _decode_html(FIXTURE.read_bytes())
        with tempfile.TemporaryDirectory() as tmp:
            path = Path(tmp) / "canonical-transcript.json"
            path.write_text(json.dumps(data), encoding="utf-8")
            snapshot = parse_chatgpt_export(path)
            self.assertEqual(snapshot.messages[0].message_id, data["records"][0]["id"])
            self.assertEqual(snapshot.messages[-1].message_id, data["records"][-1]["id"])


if __name__ == "__main__":
    unittest.main()
