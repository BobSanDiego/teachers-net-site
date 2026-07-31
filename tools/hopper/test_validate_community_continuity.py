import json, tempfile, unittest
from pathlib import Path
from validate_community_continuity import validate_paths

class ContinuityValidationTests(unittest.TestCase):
    def write_set(self, cursor, handoff, ticket="C3-IMP005"):
        root=Path(tempfile.mkdtemp()); c=root/"cursor.md"; h=root/"handoff.md"; j=root/"cycle.json"; m=root/"manifest.txt"
        c.write_text(f"Bounded implementation preparation — {ticket} complete; no delivery implementation begun.\n## Next Authorized Ticket\nNo next ticket.\n## Next Decision\nReview.")
        h.write_text(f"Bounded implementation preparation — {ticket} complete; no delivery implementation begun.\n{ticket} is complete.")
        j.write_text(json.dumps({"project":"tnet-3.0","ticket":ticket,"cycle_id":"x","status":"complete","commit":"abc","push":"successful","artifacts":[]}))
        m.write_text(f"ticket={ticket}\ncycle_id=x\ncommit=abc\npush=successful\n")
        if cursor: c.write_text(c.read_text().replace(f"{ticket} complete", "C3-IMP004 complete"))
        if handoff: h.write_text(h.read_text().replace(f"{ticket} is complete", "C3-IMP004 is complete"))
        return c,h,j,m
    def test_corrected_state_passes(self):
        paths=self.write_set(False,False); validate_paths(*paths)
    def test_stale_cursor_is_rejected(self):
        paths=self.write_set(True,False)
        with self.assertRaises(SystemExit): validate_paths(*paths)

if __name__ == "__main__": unittest.main()
