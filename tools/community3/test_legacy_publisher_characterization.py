import unittest
from legacy_publisher_characterization import UNKNOWN, characterize, unsupported_behavior

BASE = {"board":"/chatboard/teachers", "title":"Synthetic topic", "body":"Redacted fixture body", "local_path":"/legacy/teachers", "path_id":241, "group_id":227, "mapping_evidence":True, "timestamp":"2014-01-14T12:34:56"}

class LegacyCharacterizationTests(unittest.TestCase):
    def test_valid_topic(self): self.assertEqual(characterize(BASE)["outcome"], "accepted")
    def test_reply_preserves_parent_and_thread(self):
        result = characterize({**BASE, "parent_id":"legacy-42", "thread_id":"legacy-thread-7"})
        self.assertEqual((result["post_type"], result["parent_id"], result["thread_id"]), ("reply", "legacy-42", "legacy-thread-7"))
    def test_required_field_rejected(self): self.assertEqual(characterize({**BASE, "body":""})["reason_code"], "required_field_missing")
    def test_abuse_gate(self): self.assertEqual(characterize({**BASE, "profanity":True})["reason_code"], "abuse_gate_rejected")
    def test_url_and_timestamp_shape(self):
        result = characterize(BASE)
        self.assertEqual(result["url_pattern"], "/chatboard/teachers/topic1/01.14.2014.12.34.56.html")
        self.assertEqual(result["timestamp_format"], "%m.%d.%Y.%H.%M.%S.html")
    def test_chat_posts_contract(self): self.assertIn("wordpress_id", characterize(BASE)["chat_posts_fields"])
    def test_local_path_not_group_identity(self):
        result = characterize(BASE)
        self.assertEqual((result["path_id"], result["group_id"]), (241, 227))
        self.assertTrue(result["mapping_required"])
    def test_missing_mapping_is_not_guessed(self): self.assertEqual(characterize({**BASE, "mapping_evidence":False})["outcome"], UNKNOWN)
    def test_duplicate_is_explicit(self): self.assertEqual(characterize({**BASE, "duplicate":True})["outcome"], "idempotency_classification")
    def test_partial_write_is_inconsistent(self): self.assertEqual(characterize({**BASE, "partial_write":True})["outcome"], "inconsistent_state")
    def test_archive_reference_immutable(self): self.assertTrue(characterize(BASE)["archive_reference"]["immutable"])
    def test_unknowns_not_invented(self):
        self.assertEqual(unsupported_behavior("edit_delete_admin")["outcome"], UNKNOWN)
    def test_no_side_effect_surface(self):
        self.assertFalse(any(name in characterize(BASE) for name in ("database_write", "filesystem_write", "network_call", "notification")))

if __name__ == "__main__": unittest.main()
