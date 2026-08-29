from pathlib import Path
import sys
import unittest
sys.path.insert(0, str(Path(__file__).resolve().parent))
import terminalize

class TerminalizeEntrypointTest(unittest.TestCase):
    def test_terminalize_entrypoint_exists(self):
        self.assertTrue(callable(terminalize.main))
