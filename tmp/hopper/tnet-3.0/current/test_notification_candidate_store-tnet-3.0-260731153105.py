import unittest
from notification_candidate_boundary import build_candidate
from notification_candidate_store import InMemoryCandidateStore

def candidate(decision, recipient="member-7", event="evt-42", reasons=()):
    return build_candidate({"decision":decision,"reasons":reasons,"recipient_id":recipient,"event":{"event_id":event,"visibility":"public"},"mapping":{"path_id":241,"group_id":227}})

class CandidateStoreTests(unittest.TestCase):
    def test_add_get_and_query_preserves_identity_and_order(self):
        store=InMemoryCandidateStore(); first=candidate("eligible"); second=candidate("blocked","member-8","evt-43",("z_reason","a_reason")); store.add(first); store.add(second)
        self.assertEqual(store.get(first["candidate_id"]),first); self.assertEqual(store.list_for_recipient("member-7"),[first]); self.assertEqual(store.list_for_event("evt-43"),[second]); self.assertEqual(store.count(),2); self.assertEqual((first["path_id"],first["group_id"]),(241,227)); self.assertEqual(second["reason_codes"],("a_reason","z_reason"))
    def test_all_decisions_and_validation(self):
        store=InMemoryCandidateStore()
        for decision in ("eligible","blocked","ineligible"): store.add(candidate(decision, f"{decision}-member", f"{decision}-event"))
        with self.assertRaises(ValueError): store.add({"candidate_id":"bad"})
        duplicate = candidate("eligible")
        store.add(duplicate)
        with self.assertRaises(ValueError): store.add(duplicate)
    def test_duplicate_and_external_mutation_are_safe(self):
        store=InMemoryCandidateStore(); item=candidate("eligible"); store.add(item); item["reason_codes"]=("mutated",); self.assertEqual(store.get(item["candidate_id"])["reason_codes"],());
        with self.assertRaises(ValueError): store.add(candidate("eligible"))
    def test_clear_removes_process_local_state(self):
        store=InMemoryCandidateStore(); store.add(candidate("ineligible")); store.clear(); self.assertEqual(store.count(),0); self.assertFalse(store.contains("cand:evt-42:member-7"))

if __name__ == "__main__": unittest.main()
