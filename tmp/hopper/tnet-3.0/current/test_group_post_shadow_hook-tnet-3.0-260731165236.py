import unittest
from group_post_shadow_hook import GroupPostPublicationShadowHook
from notification_application_service import NotificationApplicationService

def source(post="p-42", visibility="visible", moderation="clear", mapping=True, status="published"):
    return {"post_id":post,"author_id":"author-1","path_id":241,"local_path":"ai-education","group_id":227,"mapping_evidence":"local_path:ai-education->group:227" if mapping else "","publication_state":status,"moderation_state":moderation,"visibility_state":visibility,"group_privacy":"public","created_at":"2026-07-31T00:00:00Z","event_family":"group_post","content_ref":"fixture-post"}
def recipient(member="member-7", access=True, self_event=False): return {"recipient_id":member,"authenticated":True,"current_member":access,"group_access":access,"self_event":self_event}
def policy(): return {"frequency":"immediate","category_enabled":True,"bell_enabled":True,"email_paused":False,"group_mute":False,"suppressed":False,"kill_switch":False}

class ShadowHookTests(unittest.TestCase):
    def test_disabled_does_not_invoke_service(self):
        seen=[]; hook=GroupPostPublicationShadowHook(recorder=seen.append); self.assertIsNone(hook.observe(source(),recipient(),policy())); self.assertEqual(seen,[])
    def test_enabled_uses_adapter_and_service(self):
        service=NotificationApplicationService(); seen=[]; hook=GroupPostPublicationShadowHook(test_mode=True,enabled=True,recorder=lambda event: seen.append(event) or service.notify(event)); report=hook.observe(source(),recipient(),policy()); self.assertEqual(report["eligibility_decision"],"eligible"); self.assertEqual((seen[0]["path_id"],seen[0]["group_id"]),(241,227))
    def test_missing_mapping_hidden_draft_and_exception_do_not_affect_publication(self):
        service=NotificationApplicationService(); hook=GroupPostPublicationShadowHook(test_mode=True,enabled=True,recorder=lambda event: service.notify(event));
        self.assertIsNone(hook.observe(source(mapping=False),recipient(),policy()))
        self.assertEqual(hook.observe(source(visibility="hidden"),recipient(),policy())["eligibility_decision"],"blocked"); self.assertIsNone(hook.observe(source(status="draft"),recipient(),policy()))
        errors=[]; failing=GroupPostPublicationShadowHook(test_mode=True,enabled=True,recorder=lambda event: (_ for _ in ()).throw(RuntimeError("test"))); self.assertIsNone(failing.observe(source(),recipient(),policy()))
    def test_duplicate_callback_and_no_live_recipient_enumeration(self):
        service=NotificationApplicationService(); hook=GroupPostPublicationShadowHook(test_mode=True,enabled=True,recorder=lambda event: service.notify(event)); first=hook.observe(source(),recipient(),policy()); second=hook.observe(source(),recipient(),policy()); self.assertEqual(first,second); self.assertEqual(service._pipeline.candidates.count(),1); self.assertEqual(service._pipeline.bells.count_unread("member-7"),1)
    def test_recorder_is_test_owned_and_redacted(self):
        seen=[]; hook=GroupPostPublicationShadowHook(test_mode=True,enabled=True,recorder=seen.append); hook.observe(source(),recipient(),policy()); self.assertEqual(set(seen[0]) - {"event_id","recipient_id","path_id","group_id","event_family","decision","reason_codes","visibility","email_paused","bell_enabled","created_at","content_ref"},set()); self.assertEqual(seen[0]["content_ref"],"fixture-post")

if __name__ == "__main__": unittest.main()
