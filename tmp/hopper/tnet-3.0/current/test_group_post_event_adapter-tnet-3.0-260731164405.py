import unittest
from group_post_event_adapter import GroupPostEventAdapter
from notification_application_service import NotificationApplicationService

def source(post="p-42", privacy="public", visibility="visible", moderation="clear"):
    return {"post_id":post,"author_id":"author-1","path_id":241,"local_path":"ai-education","group_id":227,"mapping_evidence":"local_path:ai-education->group:227","publication_state":"published","moderation_state":moderation,"visibility_state":visibility,"group_privacy":privacy,"created_at":"2026-07-31T00:00:00Z","event_family":"group_post","content_ref":"fixture-post"}
def recipient(member="member-7", access=True, self_event=False):
    return {"recipient_id":member,"authenticated":True,"current_member":access,"group_access":access,"self_event":self_event}
def policy(frequency="immediate", paused=False, bell=True, mute=False, suppressed=False, kill=False):
    return {"frequency":frequency,"category_enabled":True,"bell_enabled":bell,"email_paused":paused,"group_mute":mute,"suppressed":suppressed,"kill_switch":kill}

class GroupPostAdapterTests(unittest.TestCase):
    def test_eligible_adapter_integrates_to_one_candidate_and_bell(self):
        event=GroupPostEventAdapter.adapt(source(),recipient(),policy()); report=NotificationApplicationService().notify(event); self.assertEqual(report["eligibility_decision"],"eligible"); self.assertIsNotNone(report["candidate_id"]); self.assertEqual(report["bell_state"],"unread"); self.assertEqual((event["path_id"],event["group_id"]),(241,227))
    def test_self_private_hidden_former_frequency_and_policy_cases(self):
        cases=[(recipient(self_event=True),policy(),"ineligible"),(recipient(access=False),policy(),"ineligible"),(recipient(access=False),policy(),"blocked"),(recipient(),policy(frequency="never"),"ineligible")]
        events=[GroupPostEventAdapter.adapt(source(),cases[0][0],cases[0][1]),GroupPostEventAdapter.adapt(source(privacy="private"),cases[1][0],cases[1][1]),GroupPostEventAdapter.adapt(source(visibility="hidden"),recipient(),policy()),GroupPostEventAdapter.adapt(source(),cases[3][0],cases[3][1])]
        for event, expected in zip(events,("ineligible","blocked","blocked","ineligible")):
            report=NotificationApplicationService().notify(event); self.assertEqual(report["eligibility_decision"],expected); self.assertIsNone(report["candidate_id"])
    def test_email_paused_and_bell_kill_switch(self):
        paused=GroupPostEventAdapter.adapt(source(),recipient(),policy(paused=True)); self.assertEqual(NotificationApplicationService().notify(paused)["channels"]["email"],"suppressed")
        killed=GroupPostEventAdapter.adapt(source(),recipient(),policy(kill=True)); report=NotificationApplicationService().notify(killed); self.assertIsNotNone(report["candidate_id"]); self.assertIsNone(report["bell_id"])
    def test_duplicate_post_recipient_and_different_recipients(self):
        first=GroupPostEventAdapter.adapt(source(),recipient(),policy()); repeat=GroupPostEventAdapter.adapt(source(),recipient(),policy()); other=GroupPostEventAdapter.adapt(source(),recipient("member-8"),policy()); self.assertEqual(first["event_id"],repeat["event_id"]); self.assertNotEqual(first["event_id"],other["event_id"])
        service=NotificationApplicationService(); self.assertEqual(service.notify(first),service.notify(repeat)); self.assertNotEqual(service.notify(first)["candidate_id"],service.notify(other)["candidate_id"])
    def test_unmapped_and_malformed_sources_rejected(self):
        bad=source(); bad["mapping_evidence"]=""
        with self.assertRaises(ValueError): GroupPostEventAdapter.adapt(bad,recipient(),policy())
    def test_unsupported_family_rejected(self):
        bad=source(); bad["event_family"]="reply"
        with self.assertRaises(ValueError): GroupPostEventAdapter.adapt(bad,recipient(),policy())

if __name__ == "__main__": unittest.main()
