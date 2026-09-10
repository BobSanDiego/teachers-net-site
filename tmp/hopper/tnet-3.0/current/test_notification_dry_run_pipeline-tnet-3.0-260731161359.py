import unittest
from notification_dry_run_pipeline import DryRunNotificationPipeline

def event(event_id="evt-42", decision="eligible", reasons=(), private=False, paused=False):
    return {"event_id":event_id,"recipient_id":"member-7","path_id":241,"group_id":227,"decision":decision,"reason_codes":reasons,"visibility":"private" if private else "public","email_paused":paused}

class DryRunPipelineTests(unittest.TestCase):
    def test_eligible_end_to_end(self):
        pipeline=DryRunNotificationPipeline(); report=pipeline.run(event()); self.assertEqual(report["eligibility_decision"],"eligible"); self.assertIsNotNone(report["candidate_id"]); self.assertEqual(report["bell_state"],"unread"); self.assertEqual((report["path_id"],report["group_id"]),(241,227)); self.assertTrue(all(value is False for value in report["side_effects"].values()))
    def test_blocked_ineligible_and_private_rejection(self):
        for item in (event("blocked","blocked",("suppressed",)), event("ineligible","ineligible",("never",)), event("private","blocked",("visibility_denied",),True)):
            report=DryRunNotificationPipeline().run(item); self.assertIsNone(report["candidate_id"]); self.assertIsNone(report["bell_id"])
    def test_paused_email_is_bell_only(self):
        report=DryRunNotificationPipeline().run(event(paused=True)); self.assertEqual(report["channels"]["bell"],"eligible"); self.assertEqual(report["channels"]["email"],"suppressed")
    def test_duplicate_event_and_repeatability(self):
        first=DryRunNotificationPipeline().run(event()); pipeline=DryRunNotificationPipeline(); a=pipeline.run(event()); b=pipeline.run(event()); self.assertEqual(a,b); self.assertEqual(pipeline.candidates.count(),1); self.assertEqual(pipeline.bells.count_unread("member-7"),1); self.assertEqual(first,a)

if __name__ == "__main__": unittest.main()
