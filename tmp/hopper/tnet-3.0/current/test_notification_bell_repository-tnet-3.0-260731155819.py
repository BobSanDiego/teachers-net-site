import unittest
from notification_bell_repository import InMemoryBellRepository
from notification_candidate_boundary import build_candidate

def candidate(recipient="member-7", event="evt-42"):
    return build_candidate({"decision":"eligible","reasons":(),"recipient_id":recipient,"event":{"event_id":event,"visibility":"public"},"mapping":{"path_id":241,"group_id":227}})

class BellRepositoryTests(unittest.TestCase):
    def test_create_read_unread_archive_lifecycle(self):
        repo=InMemoryBellRepository(); bell=repo.create_bell(candidate()); self.assertEqual(bell["state"],"unread"); self.assertEqual(repo.mark_read(bell["bell_id"])["state"],"read"); self.assertEqual(repo.mark_unread(bell["bell_id"])["state"],"unread"); self.assertEqual(repo.archive(bell["bell_id"])["state"],"archived"); self.assertEqual(repo.count_unread("member-7"),0)
    def test_unread_counts_and_recipient_isolation(self):
        repo=InMemoryBellRepository(); first=repo.create_bell(candidate()); repo.create_bell(candidate("member-8","evt-43")); self.assertEqual(repo.count_unread("member-7"),1); self.assertEqual(repo.count_unread("member-8"),1); self.assertEqual(len(repo.list_unread("member-9")),0); repo.mark_read(first["bell_id"]); self.assertEqual(repo.count_unread("member-7"),0)
    def test_mapping_and_candidate_are_not_mutated(self):
        original=candidate(); repo=InMemoryBellRepository(); bell=repo.create_bell(original); original["path_id"]=999; self.assertEqual((bell["path_id"],bell["group_id"]),(241,227)); stored=repo.get(bell["bell_id"]); stored["state"]="archived"; self.assertEqual(repo.get(bell["bell_id"])["state"],"unread")
    def test_noneligible_and_duplicate_are_rejected(self):
        repo=InMemoryBellRepository(); blocked=build_candidate({"decision":"blocked","reasons":("x",),"recipient_id":"member-7","event":{"event_id":"evt-42","visibility":"public"},"mapping":{"path_id":241,"group_id":227}})
        with self.assertRaises(ValueError): repo.create_bell(blocked)
        bell=repo.create_bell(candidate())
        with self.assertRaises(ValueError): repo.create_bell(candidate())
    def test_clear_is_memory_only(self):
        repo=InMemoryBellRepository(); repo.create_bell(candidate()); repo.clear(); self.assertIsNone(repo.get("bell:cand:evt-42:member-7")); self.assertEqual(repo.count_unread("member-7"),0)

if __name__ == "__main__": unittest.main()
