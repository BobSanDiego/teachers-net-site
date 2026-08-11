from pathlib import Path
import unittest


ROOT = Path(__file__).resolve().parents[2]
SHARED = ROOT / "docs/process/conversation-handoff/shared"


class PackagingGuidanceTests(unittest.TestCase):
    def test_transport_rule_is_not_archive_content_limit(self):
        contract = (SHARED / "chatgpt-engineering-operating-contract.md").read_text()
        report = (SHARED / "REPORT-HOPPER-SPEC.md").read_text()
        combined = contract + "\n" + report
        self.assertIn("at most 20 directly uploaded files", combined)
        self.assertIn("ZIP counts as one", combined)
        self.assertIn("18-, 19-, or 20-file", combined)
        self.assertIn("does not limit the number of Report/Hopper", combined)
        self.assertIn("preserve source filenames, provenance", combined)


if __name__ == "__main__":
    unittest.main()
