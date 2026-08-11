import tempfile
import unittest
from pathlib import Path
import sys
sys.path.insert(0, str(Path(__file__).resolve().parent))
from bootstrap_lifecycle import assert_lifecycle_ready, is_bounded_bootstrap_authorization, resolve_report_owner


class BootstrapLifecycleTests(unittest.TestCase):
    def test_explicit_command_is_single_bounded_authorization(self):
        self.assertTrue(is_bounded_bootstrap_authorization("BOOTSTRAP", "Profile"))
        self.assertFalse(is_bounded_bootstrap_authorization("bootstrap this project as directed", "Profile"))
        self.assertFalse(is_bounded_bootstrap_authorization("authorize product implementation", "Profile"))

    def test_report_owner_is_not_replaced_by_acceptance_fixture(self):
        self.assertEqual(resolve_report_owner("shared-workflow", "profile"), "shared-workflow")
        self.assertEqual(resolve_report_owner("profile", "profile"), "profile")
        with self.assertRaises(ValueError):
            resolve_report_owner("", "profile")

    def test_readiness_requires_report_hopper_cycle_and_checkpoint(self):
        with tempfile.TemporaryDirectory() as tmp:
            root = Path(tmp); report = root / "report"; hopper = root / "hopper"; checkpoint = root / "checkpoint"
            report.mkdir(); hopper.mkdir(); checkpoint.mkdir(); (checkpoint / "handoff-manifest.json").write_text("{}")
            record = {"project_id": "fixture", "display_name": "Fixture"}
            with self.assertRaisesRegex(ValueError, "cycle record"):
                assert_lifecycle_ready(record, report_dir=report, hopper_dir=hopper, checkpoint=checkpoint)
            (hopper / "cycle-fixture.json").write_text('{"project":"fixture","status":"complete","report_file":"report.txt","manifest_file":"manifest.txt","cycle_record_file":"cycle-fixture.json"}')
            for name in ("report.txt", "manifest.txt"):
                (report / name).write_text("ok"); (hopper / name).write_text("ok")
            (report / "cycle-fixture.json").write_text("ok")
            assert_lifecycle_ready(record, report_dir=report, hopper_dir=hopper, checkpoint=checkpoint)


if __name__ == "__main__":
    unittest.main()
