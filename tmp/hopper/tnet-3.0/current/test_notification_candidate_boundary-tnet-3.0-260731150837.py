import unittest
from notification_candidate_boundary import build_audit_record, build_candidate

def evaluator(decision, reasons=(), visibility="public"):
    return {"decision":decision,"reasons":reasons,"recipient_id":"member-7","event":{"event_id":"evt-42","visibility":visibility,"content":"private fixture text"},"mapping":{"path_id":241,"group_id":227}}

class CandidateBoundaryTests(unittest.TestCase):
    def test_eligible_candidate_and_complete_audit(self):
        result=evaluator("eligible"); candidate=build_candidate(result); audit=build_audit_record(result,candidate)
        self.assertEqual(candidate["decision"],"eligible"); self.assertEqual(candidate["reason_codes"],()); self.assertEqual((candidate["path_id"],candidate["group_id"]),(241,227)); self.assertTrue(audit["redacted"]); self.assertIsNone(audit["content"])
    def test_blocked_and_ineligible_reason_codes_are_stable(self):
        for decision in ("blocked","ineligible"):
            candidate=build_candidate(evaluator(decision,("visibility_denied","member_left"),"private")); self.assertEqual(candidate["reason_codes"],("member_left","visibility_denied")); self.assertFalse(candidate["persistent"])
    def test_channels_and_side_effects_are_deferred(self):
        result=evaluator("eligible"); candidate=build_candidate(result); audit=build_audit_record(result,candidate)
        self.assertEqual(set(candidate["channels"].values()),{"deferred"}); self.assertEqual(set(audit["side_effects"].values()),{False})

if __name__ == "__main__": unittest.main()
