import json
import sys
import tempfile
import unittest
from datetime import datetime, timezone
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))
from project_handoff_builder import load_record, publish, resolve_record


class ProjectHandoffBuilderTests(unittest.TestCase):
    def test_registered_records_resolve_without_job_center_defaults(self):
        root = Path(__file__).resolve().parents[2] / "docs/process/conversation-handoff/projects"
        for project in ("jobcenter", "views", "community"):
            record, path = resolve_record(project, root)
            self.assertEqual(record["project_id"], project)
            self.assertTrue(path.is_file())

    def test_unknown_and_invalid_records_fail_closed(self):
        with tempfile.TemporaryDirectory() as tmp:
            root = Path(tmp)
            (root / "bad.json").write_text('{"project_id":"bad"}', encoding="utf-8")
            with self.assertRaisesRegex(ValueError, "resolved to 0 records"):
                resolve_record("missing", root)
            with self.assertRaisesRegex(ValueError, "resolved to 0 records"):
                resolve_record("bad", root)

    def test_publish_is_immutable_and_hashes_members(self):
        with tempfile.TemporaryDirectory() as tmp:
            root = Path(tmp)
            source = root / "source"
            source.mkdir()
            (source / "00-START-HERE.txt").write_text("start", encoding="utf-8")
            (source / "handoff-manifest.json").write_text("{}\n", encoding="utf-8")
            record = {"project_id": "fixture", "display_name": "Fixture", "root_repository": "/repo"}
            stamp = datetime(2026, 8, 11, tzinfo=timezone.utc)
            receipt = publish(record, source, root / "archive", stamp=stamp)
            destination = Path(receipt["wsl_path"])
            self.assertTrue(destination.is_dir())
            self.assertTrue(all(member["bytes"] > 0 for member in receipt["members"]))
            with self.assertRaises(FileExistsError):
                publish(record, source, root / "archive", stamp=stamp)


if __name__ == "__main__":
    unittest.main()
