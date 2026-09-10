import json
from pathlib import Path
import unittest

FIXTURE=Path(__file__).parents[2]/'tests/fixtures/community3/publisher-domain/shared-parity.json'
class SharedParityFixtureTests(unittest.TestCase):
    def test_shared_contract_has_required_cases(self):
        data=json.loads(FIXTURE.read_text())
        self.assertEqual(len(data),20)
        self.assertEqual(data['accepted_topic']['event_type'],'community.post.published')
        self.assertEqual(data['invalid_transition']['reason'],'LIFECYCLE_TRANSITION_INVALID')
