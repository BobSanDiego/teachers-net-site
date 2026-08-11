import json
import unittest
from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
CHECKPOINT_ROOT = Path("/mnt/c/Main/Active/Projects/Teachers.Net/HANDOFFS")


class HandoffLifecycleTests(unittest.TestCase):
    def test_checkpoint_has_immutable_timestamped_job_center_shape(self):
        checkpoints = sorted(CHECKPOINT_ROOT.glob("Job-Center-*") )
        self.assertTrue(checkpoints)
        checkpoint = checkpoints[-1]
        self.assertRegex(checkpoint.name, r"^Job-Center-\d{8}-\d{6}$")
        manifest = json.loads((checkpoint / "handoff-manifest.json").read_text())
        self.assertTrue(manifest["immutable_checkpoint"])
        self.assertEqual(manifest["publication_status"], "VALIDATED_IMMUTABLE_CHECKPOINT")

    def test_checkpoint_is_safe_and_hashes_match(self):
        checkpoint = sorted(CHECKPOINT_ROOT.glob("Job-Center-*"))[-1]
        names = {p.name for p in checkpoint.rglob("*") if p.is_file()}
        self.assertNotIn("codex-conversation-fossil.md", names)
        self.assertFalse(any(p.suffix == ".jsonl" for p in checkpoint.rglob("*")))
        manifest = json.loads((checkpoint / "handoff-manifest.json").read_text())
        for member in manifest["checkpoint_members"]:
            path = checkpoint / member["filename"]
            self.assertTrue(path.is_file())
            self.assertEqual(path.stat().st_size, member["bytes"])

    def test_shared_projection_points_to_tracked_sources(self):
        projection = Path("/mnt/c/Main/Active/Projects/Teachers.Net/SHARED-WORKFLOW")
        self.assertTrue(projection.is_dir())
        for path in projection.iterdir():
            text = path.read_text(encoding="utf-8")
            if path.suffix == ".json":
                self.assertIn("_canonical_source", json.loads(text))
            else:
                self.assertIn("CANONICAL SOURCE:", text)


if __name__ == "__main__":
    unittest.main()
