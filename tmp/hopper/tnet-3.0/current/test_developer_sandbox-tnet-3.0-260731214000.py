from pathlib import Path
import unittest
ROOT=Path(__file__).parents[2]; ADMIN=(ROOT/'wordpress/wp-content/plugins/tnet-community/admin/class-tnet-community-workbench.php').read_text(); SERVICE=(ROOT/'wordpress/wp-content/plugins/tnet-community/includes/class-tnet-community-workbench-service.php').read_text()
class SandboxTests(unittest.TestCase):
 def test_actions(self):
  for value in ('reply','transition','hidden','retracted','restored','deleted','get_audit','get_events'): self.assertIn(value,ADMIN+SERVICE)
 def test_protections(self): self.assertIn('check_admin_referer',ADMIN); self.assertIn('manage_options',ADMIN); self.assertIn('DDEV_PROJECT',ADMIN)
 def test_no_public_surface(self): self.assertNotIn('register_rest_route',ADMIN); self.assertNotIn('wp_ajax',ADMIN); self.assertNotIn('wp_mail',ADMIN)
 def test_escaped_output(self): self.assertIn('esc_html',ADMIN); self.assertIn('esc_attr',ADMIN)
if __name__=='__main__': unittest.main()
