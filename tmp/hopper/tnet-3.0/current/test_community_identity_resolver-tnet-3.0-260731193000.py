import unittest
from community_identity_resolver import Community, CommunityIdentityResolver

def resolver():
    r = CommunityIdentityResolver()
    r.register(Community("community:synth-a", legacy_paths=({"path_id": "path-a", "local_path": "board-a"},), legacy_groups=({"group_id": "group-a"},), group_context={"scope":"group-a"}, publisher_context={"board":"board-a"}))
    r.register(Community("community:synth-inactive", lifecycle="inactive", legacy_paths=({"path_id":"path-inactive","local_path":"board-inactive"},)))
    r.register(Community("community:synth-b", legacy_paths=({"path_id":"path-b","local_path":"board-b"},)))
    r.register(Community("community:synth-c", legacy_paths=({"path_id":"path-c","local_path":"board-c"},)))
    r._sources[("path_id", "path-ambiguous")] = [("community:synth-b","e1","active"),("community:synth-c","e2","active")]
    r._sources[("path_id", "path-duplicate")] = [("community:synth-a","e1","active"),("community:synth-a","e2","active")]
    r.add_orphaned_reference("group_id", "group-orphan")
    return r

class ResolverTests(unittest.TestCase):
    def test_path_local_group(self):
        r=resolver(); self.assertEqual(r.resolve_community_by_legacy_path(path_id="path-a")["community_id"],"community:synth-a"); self.assertEqual(r.resolve_community_by_legacy_path(local_path="board-a")["community_id"],"community:synth-a"); self.assertEqual(r.resolve_community_by_legacy_group("group-a")["community_id"],"community:synth-a")
    def test_multiple_refs_same_identity(self):
        r=resolver(); self.assertEqual({r.resolve_community_by_legacy_path(path_id="path-a")["community_id"],r.resolve_community_by_legacy_group("group-a")["community_id"]},{"community:synth-a"})
    def test_missing_has_no_identity(self):
        result=resolver().resolve_community_by_legacy_path(path_id="missing"); self.assertIsNone(result["community_id"]); self.assertEqual(result["status"],"missing")
    def test_ambiguous_and_duplicate(self):
        r=resolver(); self.assertEqual(r.resolve_community_by_legacy_path(path_id="path-ambiguous")["status"],"ambiguous"); self.assertEqual(r.resolve_community_by_legacy_path(path_id="path-duplicate")["status"],"duplicate")
    def test_inactive_orphaned(self):
        r=resolver(); self.assertEqual(r.resolve_community_by_legacy_path(path_id="path-inactive")["status"],"inactive"); self.assertEqual(r.resolve_community_by_legacy_group("group-orphan")["status"],"orphaned")
    def test_no_silent_overwrite(self):
        r=resolver();
        with self.assertRaises(ValueError): r.register(Community("community:synth-a"))
    def test_returned_references_are_immutable_copies(self):
        r=resolver(); refs=r.get_legacy_references("community:synth-a"); refs["legacy_paths"][0]["path_id"]="changed"; self.assertEqual(r.get_legacy_references("community:synth-a")["legacy_paths"][0]["path_id"],"path-a")
    def test_instances_are_isolated_and_deterministic(self):
        a=resolver(); b=CommunityIdentityResolver(); self.assertEqual(a.resolve_community_by_legacy_path(path_id="path-a"),a.resolve_community_by_legacy_path(path_id="path-a")); self.assertEqual(b.resolve_community_by_legacy_path(path_id="path-a")["status"],"missing")
    def test_context_requires_canonical_identity(self):
        r=resolver(); self.assertEqual(r.get_group_context("community:synth-a"),{"scope":"group-a"}); self.assertEqual(r.get_publisher_context("community:synth-a"),{"board":"board-a"})
    def test_distinct_legacy_ids_are_not_compared(self):
        r=resolver(); self.assertEqual(r.resolve_community_by_legacy_path(path_id="path-a")["community_id"],r.resolve_community_by_legacy_group("group-a")["community_id"])
    def test_zero_side_effect_contract(self):
        import sys
        self.assertNotIn("sqlite", sys.modules); self.assertNotIn("requests", sys.modules)
if __name__ == "__main__": unittest.main()
