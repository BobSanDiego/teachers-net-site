import unittest
from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
CANONICAL = ROOT / "docs/process/conversation-handoff/shared/START-CODEX.md"
PROJECTED = Path("/mnt/c/Main/Active/Projects/Teachers.Net/SHARED-WORKFLOW/START-CODEX.md")
BOOTSTRAP = ROOT / "docs/process/conversation-handoff/shared/PROJECT-BOOTSTRAP-SPEC.md"


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

    def test_bootstrap_spec_is_canonical_and_projected(self):
        self.assertTrue(BOOTSTRAP.is_file())
        projected = Path("/mnt/c/Main/Active/Projects/Teachers.Net/SHARED-WORKFLOW/PROJECT-BOOTSTRAP-SPEC.md")
        self.assertTrue(projected.is_file())
        self.assertIn("CANONICAL SOURCE:", projected.read_text(encoding="utf-8"))
        text = BOOTSTRAP.read_text(encoding="utf-8")
        for phrase in ("UNREGISTERED", "ONBOARDING AUTHORIZED", "REGISTERED / LIFECYCLE READY", "Engineering Director", "Legacy migration"):
            self.assertIn(phrase, text)

    def test_hard_ticket_ceiling_is_not_soft_guidance(self):
        sources = [CANONICAL, ROOT / "docs/process/conversation-handoff/shared/chatgpt-engineering-operating-contract.md", ROOT / "docs/codex-ticket-discipline.md"]
        for source in sources:
            text = source.read_text(encoding="utf-8")
            self.assertIn("MUST NOT exceed 15,000 characters", text)
            self.assertIn("hard validity", text.lower())


if __name__ == "__main__":
    unittest.main()
