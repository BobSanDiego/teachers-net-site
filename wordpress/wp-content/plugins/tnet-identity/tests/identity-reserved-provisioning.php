<?php
/**
 * Deterministic source-level regression harness; no WordPress database or
 * account state is touched. Run: php tests/identity-reserved-provisioning.php
 */
if (!defined('ABSPATH')) define('ABSPATH', __DIR__ . '/');
if (!defined('IDENTITY_TEST_ROOT')) define('IDENTITY_TEST_ROOT', dirname(__DIR__));

final class WP_Error {
  private $code;
  public function __construct($code, $message = '') { $this->code = $code; }
  public function get_error_code() { return $this->code; }
}

final class Identity_Test_DB {
  public function prepare($query, ...$args) { return $query; }
  public function get_var($query) { return null; }
  public function query($query) { return 1; }
  public function insert($table, $data, $formats = []) { return 1; }
}

final class TNet_Identity {
  public static function table($name) { return 'wp_tnet_identity_' . $name; }
}

final class TNet_Identity_Public {
  public static function verify_url($token) { return 'https://example.test/verify/'; }
}

$GLOBALS['identity_test_users'] = [
  'admin' => ['ID' => 1, 'user_email' => 'admin@example.test'],
];
$GLOBALS['identity_test_next_id'] = 2;
$GLOBALS['identity_test_filters'] = [];
$GLOBALS['identity_test_admin'] = false;
$GLOBALS['wpdb'] = new Identity_Test_DB();

function is_wp_error($value) { return $value instanceof WP_Error; }
function __($value, $domain = null) { return $value; }
function sanitize_email($value) { return filter_var((string) $value, FILTER_SANITIZE_EMAIL); }
function sanitize_key($value) { return strtolower(preg_replace('/[^a-z0-9_\\-]/', '', (string) $value)); }
function is_email($value) { return (bool) filter_var((string) $value, FILTER_VALIDATE_EMAIL); }
function current_user_can($capability) { return $capability === 'manage_options' && $GLOBALS['identity_test_admin']; }
function username_exists($username) { return isset($GLOBALS['identity_test_users'][strtolower((string) $username)]); }
function email_exists($email) {
  foreach ($GLOBALS['identity_test_users'] as $user) if (($user['user_email'] ?? '') === $email) return (int) $user['ID'];
  return false;
}
function get_option($key, $default = false) { return $default; }
function wp_insert_user($data) {
  if (username_exists($data['user_login'])) return new WP_Error('existing_user_login');
  $id = $GLOBALS['identity_test_next_id']++;
  $GLOBALS['identity_test_users'][$data['user_login']] = ['ID' => $id, 'user_email' => $data['user_email']];
  return $id;
}
function get_user_by($field, $value) {
  foreach ($GLOBALS['identity_test_users'] as $login => $user) {
    if (($field === 'id' && (int) $user['ID'] === (int) $value) || ($field === 'email' && ($user['user_email'] ?? '') === $value)) {
      return (object) ['ID' => (int) $user['ID'], 'user_email' => $user['user_email'], 'user_login' => $login];
    }
  }
  return false;
}
function update_user_meta($id, $key, $value) { return true; }
function absint($value) { return abs((int) $value); }
function wp_generate_password($length = 12, $special_chars = true, $extra_special_chars = false) { return str_repeat('x', $length); }
function wp_rand($min = 0, $max = 0) { return 123456; }
function wp_mail($to, $subject, $message) { return true; }
function add_filter($tag, $callback, $priority = 10, $accepted_args = 1) {
  $GLOBALS['identity_test_filters'][$tag][$priority][] = [$callback, $accepted_args];
  return true;
}
function remove_filter($tag, $callback, $priority = 10) {
  foreach ($GLOBALS['identity_test_filters'][$tag][$priority] ?? [] as $index => $entry) {
    if ($entry[0] === $callback) unset($GLOBALS['identity_test_filters'][$tag][$priority][$index]);
  }
  return true;
}
function apply_filters($tag, $value, ...$args) {
  $groups = $GLOBALS['identity_test_filters'][$tag] ?? [];
  ksort($groups);
  foreach ($groups as $entries) foreach ($entries as [$callback, $accepted_args]) {
    $value = $callback(...array_slice([$value, ...$args], 0, $accepted_args));
  }
  return $value;
}

require_once __DIR__ . '/../includes/class-tnet-identity-policy.php';
require_once __DIR__ . '/../includes/class-tnet-identity-service.php';

function identity_assert($condition, $message) {
  if (!$condition) throw new RuntimeException($message);
}
function identity_error_code($value) {
  return is_wp_error($value) ? $value->get_error_code() : null;
}
function identity_account_data($username, $suffix) {
  return [
    'username' => $username,
    'email' => 'qa-identity-' . $suffix . '@example.test',
    'password' => 'disposable-test-password',
  ];
}

$created_username = 'edit';
try {
  identity_assert(identity_error_code(TNet_Identity_Service::create_account(identity_account_data('admin', 'admin'))) === 'tnet_identity_username_reserved', 'ordinary signup must reject admin');

  $profile_routes = [
    'edit', 'enrichment', 'complete', 'manage', 'settings', 'privacy',
    'visibility', 'preview', 'public', 'me', 'self', 'new', 'create',
    'admin', 'api', 'avatar-components', 'avatar-component.svg',
  ];
  foreach ($profile_routes as $slug) {
    identity_assert(TNet_Identity_Policy::username_reservation_category($slug) !== null, 'route slug is not reserved: ' . $slug);
    $result = TNet_Identity_Service::create_account(identity_account_data($slug, 'ordinary-' . md5($slug)));
    $expected = $slug === 'me' ? 'tnet_identity_username_invalid' : 'tnet_identity_username_reserved';
    identity_assert(identity_error_code($result) === $expected, 'ordinary signup did not fail for the expected reason: ' . $slug);
  }
  identity_assert(TNet_Identity_Policy::username_reservation_category('me') === 'profile_routes', 'me must remain conceptually reserved');

  foreach (['teacher.loves.art', 'math.lover', 'sciencegal72'] as $handle) {
    identity_assert(TNet_Identity_Policy::validate_username($handle) === $handle, 'individualized handle was rejected: ' . $handle);
  }
  identity_assert(TNet_Identity_Policy::reservation_key(' A.D.M.1.N ') === 'admin', 'punctuation/leetspeak normalization changed');
  identity_assert(TNet_Identity_Policy::username_reservation_category('A.D.M.1.N') !== null, 'normalized reserved identity escaped policy');
  identity_assert(TNet_Identity_Policy::username_reservation_category('teachersnet-staff-tools') === 'platform', 'platform combination protection changed');
  identity_assert(TNet_Identity_Policy::username_reservation_category('admin') === 'platform', 'existing platform category precedence changed');
  identity_assert(TNet_Identity_Policy::username_reservation_category('teacher') === 'generic', 'generic category changed');
  identity_assert(TNet_Identity_Policy::username_reservation_category('bot') === 'automation', 'automation category changed');
  identity_assert(TNet_Identity_Policy::username_reservation_category('bobreap') === 'staff', 'staff category changed');

  $no_intent = identity_account_data($created_username, 'no-intent');
  identity_assert(identity_error_code(TNet_Identity_Service::provision_official_account($no_intent)) === 'tnet_identity_official_intent_required', 'official provisioning must require explicit intent');

  $explicit = identity_account_data($created_username, 'unprivileged');
  $explicit['provision_official_identity'] = true;
  identity_assert(identity_error_code(TNet_Identity_Service::provision_official_account($explicit)) === 'tnet_identity_official_provisioning_forbidden', 'unprivileged official provisioning must fail closed');

  $ordinary_payload = identity_account_data($created_username, 'normal-path-flag');
  $ordinary_payload['provision_official_identity'] = true;
  identity_assert(identity_error_code(TNet_Identity_Service::create_account($ordinary_payload)) === 'tnet_identity_username_reserved', 'signup payload must not activate the privileged path');

  $GLOBALS['identity_test_admin'] = true;
  $bad_syntax = identity_account_data('invalid name', 'invalid-syntax');
  $bad_syntax['provision_official_identity'] = true;
  identity_assert(identity_error_code(TNet_Identity_Service::provision_official_account($bad_syntax)) === 'tnet_identity_username_invalid', 'privileged path must preserve syntax validation');

  $bad_email = identity_account_data($created_username, 'invalid-email');
  $bad_email['email'] = 'not-an-email';
  $bad_email['provision_official_identity'] = true;
  identity_assert(identity_error_code(TNet_Identity_Service::provision_official_account($bad_email)) === 'tnet_identity_email_invalid', 'privileged path must preserve email validation');

  $short_password = identity_account_data($created_username, 'short-password');
  $short_password['password'] = 'short';
  $short_password['provision_official_identity'] = true;
  identity_assert(identity_error_code(TNet_Identity_Service::provision_official_account($short_password)) === 'tnet_identity_password_short', 'privileged path must preserve password validation');

  $official = identity_account_data($created_username, 'official');
  $official['provision_official_identity'] = true;
  $created = TNet_Identity_Service::provision_official_account($official);
  identity_assert(is_array($created) && (int) $created['user_id'] > 1, 'privileged explicit reserved identity was not provisioned');
  identity_assert(username_exists($created_username), 'provisioned identity missing from test account store');

  $duplicate = identity_account_data($created_username, 'duplicate');
  $duplicate['provision_official_identity'] = true;
  identity_assert(identity_error_code(TNet_Identity_Service::provision_official_account($duplicate)) === 'tnet_identity_username_exists', 'privileged path must preserve uniqueness validation');

  identity_assert((int) $GLOBALS['identity_test_users']['admin']['ID'] === 1, 'sanctioned admin fixture changed');
  $client_js = file_get_contents(IDENTITY_TEST_ROOT . '/public/js/tnet-identity-public.js');
  identity_assert(strpos($client_js, 'avatar-components') === false && strpos($client_js, 'avatar-component.svg') === false, 'client code contains a divergent route reservation list');
  echo "PASS Identity reservation/provisioning regression suite; synthetic accounts only; no database or external mail\n";
} finally {
  unset($GLOBALS['identity_test_users'][$created_username]);
  $GLOBALS['identity_test_admin'] = false;
}

identity_assert(!username_exists($created_username), 'synthetic official account cleanup failed');
identity_assert((int) $GLOBALS['identity_test_users']['admin']['ID'] === 1, 'sanctioned admin fixture did not remain intact');
echo "PASS deterministic cleanup; sanctioned admin fixture unchanged\n";
