from pathlib import Path
import unittest

ROOT = Path(__file__).parents[2]
PLUGIN = ROOT / "wordpress/wp-content/plugins/tnet-community"

class WorkbenchContractTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.admin = (PLUGIN / "admin/class-tnet-community-workbench.php").read_text()
        cls.service = (PLUGIN / "includes/class-tnet-community-workbench-service.php").read_text()
    def test_local_gate_and_capability(self): self.assertIn("DDEV_PROJECT", self.admin); self.assertIn("manage_options", self.admin)
    def test_nonce_and_post_flow(self): self.assertIn("check_admin_referer", self.admin); self.assertIn("tnet_action", self.admin)
    def test_no_public_api(self): self.assertNotIn("register_rest_route", self.admin); self.assertNotIn("wp_ajax", self.admin)
    def test_sanitization(self):
        self.assertIn("sanitize_text_field", self.service); self.assertIn("sanitize_textarea_field", self.service); self.assertIn("sanitize_key", self.service)
    def test_workbench_namespace(self): self.assertIn("workbench_namespace", self.service)
    def test_no_external_delivery(self):
        self.assertNotIn("wp_mail", self.admin + self.service); self.assertNotIn("send_notification", self.admin + self.service); self.assertNotIn("chatboard.cgi", self.admin + self.service)
    def test_schema_controls_are_local(self): self.assertIn("TNet_Community_Schema::install", self.admin); self.assertIn("TNet_Community_Schema::uninstall", self.admin)
    def test_output_escaped(self): self.assertIn("esc_html", self.admin); self.assertIn("esc_attr", self.admin)
if __name__ == "__main__": unittest.main()
