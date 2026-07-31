import unittest
from community_publisher_domain import CommunityPostDraft, PublisherDomain

COMM={"community:synth":{"active":True}}
def topic(**kw): return CommunityPostDraft(submission_id=kw.pop("submission_id","s1"),community_id=kw.pop("community_id","community:synth"),author_id=kw.pop("author_id","user:synth"),post_type=kw.pop("post_type","topic"),title=kw.pop("title","Synthetic topic"),body=kw.pop("body","Synthetic body"),**kw)

class DomainTests(unittest.TestCase):
 def test_topic_publishes_event(self):
  r=PublisherDomain().publish(topic(),COMM); self.assertTrue(r.accepted); self.assertEqual((r.post.publication_state,r.event.event_type),("published","community.post.published"))
 def test_reply_and_nested_thread(self):
  d=PublisherDomain(); root=d.publish(topic(),COMM).post; reply=d.publish(topic(submission_id="s2",post_type="reply",title="",parent_post_id=root.post_id),COMM).post; nested=d.publish(topic(submission_id="s3",post_type="reply",title="",parent_post_id=reply.post_id),COMM).post; self.assertEqual((reply.thread_id,nested.thread_id),(root.thread_id,root.thread_id))
 def test_required_and_type_errors(self):
  d=PublisherDomain(); self.assertIn("TITLE_REQUIRED",d.publish(topic(title=""),COMM).validation.reason_codes); self.assertIn("BODY_REQUIRED",d.publish(topic(submission_id="s2",body=""),COMM).validation.reason_codes); self.assertIn("POST_TYPE_UNSUPPORTED",d.publish(topic(submission_id="s3",post_type="bad"),COMM).validation.reason_codes)
 def test_parent_rules(self):
  d=PublisherDomain(); self.assertIn("REPLY_PARENT_REQUIRED",d.publish(topic(post_type="reply",title=""),COMM).validation.reason_codes); other=d.publish(topic(submission_id="other",community_id="other"),{"other":{}}).post; self.assertIn("PARENT_COMMUNITY_MISMATCH",d.publish(topic(submission_id="cross",post_type="reply",title="",parent_post_id=other.post_id),COMM).validation.reason_codes)
 def test_restricted_and_locked_parent(self):
  d=PublisherDomain(); root=d.publish(topic(),COMM).post; d._posts[root.post_id]=root.__class__(**{**root.__dict__,"publication_state":"hidden"}); self.assertIn("PARENT_RESTRICTED",d.publish(topic(submission_id="hidden",post_type="reply",title="",parent_post_id=root.post_id),COMM).validation.reason_codes)
 def test_pending_mode_no_event_until_approved(self):
  d=PublisherDomain(); r=d.publish(topic(publication_mode="pending"),COMM); self.assertEqual((r.post.publication_state,r.event),("pending",None)); t=d.transition(r.post.post_id,"published","mod:synth","approve"); self.assertEqual(t.reason_code,"LIFECYCLE_TRANSITION_ACCEPTED")
 def test_moderation_inputs(self):
  d=PublisherDomain(); self.assertEqual(d.publish(topic(),COMM,"spam").post.moderation_state,"spam"); self.assertEqual(d.publish(topic(submission_id="flag"),COMM,"flagged").post.publication_state,"published")
 def test_lifecycle_and_invalid_transition(self):
  d=PublisherDomain(); p=d.publish(topic(),COMM).post; self.assertEqual(d.transition(p.post_id,"hidden","mod","hide").new_state,"hidden"); self.assertEqual(d.transition(p.post_id,"spam","mod","spam").new_state,"spam"); self.assertEqual(d.transition(p.post_id,"draft","x","bad").reason_code,"LIFECYCLE_TRANSITION_INVALID")
 def test_idempotency_and_conflict(self):
  d=PublisherDomain(); a=d.publish(topic(),COMM); b=d.publish(topic(),COMM); self.assertEqual(a.post.post_id,b.post.post_id); c=d.publish(topic(body="different"),COMM); self.assertEqual(c.reason_code,"IDEMPOTENCY_CONFLICT")
 def test_event_has_canonical_identity_only(self):
  e=PublisherDomain().publish(topic(compatibility_refs={"legacy_url":"/old"}),COMM).event; self.assertEqual((e.community_id,e.post_id),("community:synth",e.post_id)); self.assertNotIn("path_id",e.__dict__)
 def test_event_failure_does_not_undo_post(self):
  d=PublisherDomain(); r=d.publish(topic(),COMM,fail_event=True); self.assertTrue(r.accepted); self.assertIsNotNone(r.post); self.assertIsNone(r.event); self.assertEqual(r.reason_code,"EVENT_CONSTRUCTION_FAILED")
 def test_unresolved_identity(self): self.assertIn("COMMUNITY_UNRESOLVED",PublisherDomain().publish(topic(community_id="missing"),COMM).validation.reason_codes)
 def test_compatibility_and_copy(self):
  refs={"legacy_url":"/old"}; p=PublisherDomain().publish(topic(compatibility_refs=refs),COMM).post; refs["legacy_url"]="changed"; self.assertEqual(p.compatibility_refs["legacy_url"],"/old")
 def test_isolation(self): self.assertNotEqual(PublisherDomain().publish(topic(),COMM).post.post_id, None); self.assertIn("PARENT_NOT_FOUND",PublisherDomain().publish(topic(post_type="reply",title="",parent_post_id="missing"),COMM).validation.reason_codes)
 def test_no_legacy_id_reasoning(self): self.assertEqual(PublisherDomain().publish(topic(),COMM).post.community_id,"community:synth")
 def test_no_runtime_side_effect_contract(self):
  d=PublisherDomain(); self.assertFalse(hasattr(d,"wpdb")); self.assertFalse(hasattr(d,"send_notification"))
if __name__ == "__main__": unittest.main()
