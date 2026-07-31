from pathlib import Path
import unittest
ROOT=Path(__file__).parents[2]; VIEW=(ROOT/'wordpress/wp-content/plugins/tnet-community/includes/class-tnet-community-thread-view.php').read_text(); CTRL=(ROOT/'wordpress/wp-content/plugins/tnet-community/includes/class-tnet-community-thread-controller.php').read_text()
class ThreadViewTests(unittest.TestCase):
 def test_safe_fields_and_visibility(self):
  for value in ('post_id','thread_id','parent_post_id','publication_state','_tombstone'): self.assertIn(value,VIEW)
 def test_local_noindex_route(self): self.assertIn('DDEV_PROJECT',CTRL); self.assertIn('noindex',CTRL); self.assertIn('add_rewrite_rule',CTRL)
 def test_no_raw_sql_or_internal_leak(self):
  self.assertNotIn('$wpdb',VIEW); self.assertNotIn('idempotency_key',CTRL); self.assertNotIn('audit_metadata',CTRL)
 def test_escaped_rendering(self): self.assertIn('esc_html',CTRL); self.assertIn('wp_kses_post',CTRL)
if __name__=='__main__': unittest.main()
