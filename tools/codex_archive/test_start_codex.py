import unittest
from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
CANONICAL = ROOT / "docs/process/conversation-handoff/shared/START-CODEX.md"
PROJECTED = Path("/mnt/c/Main/Active/Projects/Teachers.Net/SHARED-WORKFLOW/START-CODEX.md")


class StartCodexTests(unittest.TestCase):
    def test_canonical_and_projected_front_door_exist(self):
        self.assertTrue(CANONICAL.is_file())
        self.assertTrue(PROJECTED.is_file())

    def test_projection_identifies_canonical_source_and_body(self):
        canonical = CANONICAL.read_text(encoding="utf-8")
        projected = PROJECTED.read_text(encoding="utf-8")
        self.assertIn("CANONICAL SOURCE: docs/process/conversation-handoff/shared/START-CODEX.md", projected)
        self.assertTrue(projected.endswith(canonical))

    def test_front_door_routes_existing_and_new_projects(self):
        text = CANONICAL.read_text(encoding="utf-8")
        for phrase in ("Existing registered project", "New project", "Engineering Director", "PREPARE HANDOFF", "TICKET READY FOR CODEX"):
            self.assertIn(phrase, text)
        self.assertNotIn("Job Center (8/10/26)", text)
        self.assertNotIn("Profile", text)


if __name__ == "__main__":
    unittest.main()
