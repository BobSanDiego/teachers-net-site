<?php
/** Deterministic public Profile routing/projection regression; no WordPress DB required. */
define('ABSPATH', __DIR__ . '/');

$rewrite_rules = [];
function add_rewrite_rule($regex, $query, $after) { global $rewrite_rules; $rewrite_rules[] = compact('regex', 'query', 'after'); }
function __($value, $domain = null) { return $value; }
function mysql2date($format, $date, $translate = true) { return 'Mar 2024'; }
function esc_html__($value, $domain = null) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function esc_attr__($value, $domain = null) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function esc_html($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function esc_attr($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function esc_url($value) { return (string) $value; }
function home_url($path = '/') { return 'https://example.test' . $path; }

final class TNet_Profile_Basics {
  public static function project_grade_labels(array $selected) { return in_array('grade-uuid', $selected, true) ? ['Middle School'] : []; }
  public static function term_labels(array $selected) { return in_array('subject-uuid', $selected, true) ? ['Science'] : []; }
}
final class TNet_Profile_Avatar {
  public static function resolve_avatar($user_id, $size) { return ['url' => 'https://example.test/avatar.png', 'source' => 'test']; }
}

require dirname(__DIR__) . '/includes/class-tnet-profile-public.php';

function expect($condition, $message) {
  if (!$condition) throw new RuntimeException($message);
}

TNet_Profile_Public::register_route();
expect(count($rewrite_rules) === 2, 'expected self and canonical public route rules only');
expect($rewrite_rules[0]['regex'] === '^profile/?$' && $rewrite_rules[0]['after'] === 'top', 'authenticated /profile/ route must retain top precedence');
expect($rewrite_rules[1]['after'] === 'top' && strpos($rewrite_rules[1]['regex'], '(?!edit/?$|enrichment/?$|complete/?$') !== false, 'public username route must precede generic pages while excluding fixed Profile routes');
expect(preg_match('~' . $rewrite_rules[1]['regex'] . '~', 'profile/qa.member/') === 1, 'canonical public route must accept the normalized username path');
foreach (['profile/edit/', 'profile/enrichment/', 'profile/complete/', 'profile/avatar-components/'] as $fixed_route) {
  expect(preg_match('~' . $rewrite_rules[1]['regex'] . '~', $fixed_route) === 0, 'username route must not capture fixed Profile route ' . $fixed_route);
}

$user = (object) [
  'ID' => 123,
  'display_name' => 'Alex Rivera',
  'user_login' => 'alex.rivera',
  'user_registered' => '2024-03-10 12:00:00',
  'user_email' => 'must-not-be-projected@example.test',
];
$roles = [(object) ['term_uuid' => 'role-uuid', 'label' => 'Classroom Teacher']];
$state = [
  'selected' => [
    'teaching_grade' => ['grade-uuid'],
    'teaching_subject' => ['subject-uuid'],
    'professional_identity' => ['role-uuid'],
  ],
  'scalars' => [
    'bio' => "A short public bio.\nSecond line.",
    'teaching_since' => 2015,
    'profile_details_public' => true,
    'location_public' => true,
  ],
  'location' => ['exists' => true, 'label' => 'Portland, Oregon, USA'],
];
$public = TNet_Profile_Public::build_projection($user, $state, $roles);
expect($public['display_name'] === 'Alex Rivera' && $public['username'] === 'alex.rivera', 'identity must use display name and immutable login separately');
expect($public['location'] === 'Portland, Oregon, USA', 'public location must honor location_public');
expect($public['about'] === "A short public bio.\nSecond line.", 'bio must appear only in About projection');
expect(count($public['teaching_rows']) === 4, 'populated profile should project all four governed teaching rows');
expect($public['teaching_rows'][0]['values'] === ['Middle School'] && $public['teaching_rows'][1]['values'] === ['Science'] && $public['teaching_rows'][2]['values'] === ['Classroom Teacher'], 'governed display labels must remain verbatim');
expect(in_array('Teaching since 2015', $public['chips'], true), 'Teaching Since must be a compact visible hero chip');
expect(!array_key_exists('email', $public) && !in_array($user->user_email, $public, true), 'email must not enter public projection');
expect($public['groups_available'] === false && $public['groups'] === [], 'missing Groups owner must not fabricate memberships');
$render = new ReflectionMethod(TNet_Profile_Public::class, 'render_profile');
$render->setAccessible(true);
ob_start();
$render->invoke(null, $public);
$full_markup = ob_get_clean();
expect(substr_count($full_markup, 'A short public bio.') === 1, 'bio must render once in About and never in the hero');
expect(strpos($full_markup, 'QA Profile') === false && strpos($full_markup, 'must-not-be-projected@example.test') === false, 'rendered Profile body must not expose account email');
expect(strpos($full_markup, 'Teaching Profile') !== false && strpos($full_markup, 'Middle School') !== false && strpos($full_markup, 'Classroom Teacher') !== false, 'populated governed teaching rows must render as one section');
expect(strpos($full_markup, 'Follow') === false && strpos($full_markup, 'Connect') === false && strpos($full_markup, 'Message') === false, 'unapproved social actions must not render');

$private_details = $state;
$private_details['scalars']['profile_details_public'] = false;
$private_details['scalars']['location_public'] = false;
$hidden = TNet_Profile_Public::build_projection($user, $private_details, $roles);
expect($hidden['about'] === '' && $hidden['teaching_rows'] === [] && $hidden['chips'] === [], 'private aggregate details must omit bio, rows, and chips');
expect($hidden['location'] === '', 'private location must be omitted independently');

$public_details_private_location = $state;
$public_details_private_location['scalars']['location_public'] = false;
$partial = TNet_Profile_Public::build_projection($user, $public_details_private_location, $roles);
expect($partial['about'] !== '' && count($partial['teaching_rows']) === 4, 'public details remain visible when location is private');
expect($partial['location'] === '', 'location privacy must remain independent from details privacy');

$sparse = $state;
$sparse['selected'] = ['teaching_grade' => [], 'teaching_subject' => [], 'professional_identity' => []];
$sparse['scalars']['bio'] = '';
$sparse['scalars']['teaching_since'] = null;
$sparse_public = TNet_Profile_Public::build_projection($user, $sparse, $roles);
expect($sparse_public['about'] === '' && $sparse_public['teaching_rows'] === [] && $sparse_public['chips'] === [], 'empty About/Teaching Profile modules must be omitted');
ob_start();
$render->invoke(null, $sparse_public);
$sparse_markup = ob_get_clean();
expect(strpos($sparse_markup, '>About<') === false && strpos($sparse_markup, 'Teaching Profile') === false, 'sparse Profile must omit empty About and Teaching Profile cards');
expect(strpos($sparse_markup, 'No groups to show yet.') !== false, 'empty Groups card must remain visible with approved copy');

echo "PASS public route precedence, visibility projection, independent location privacy, sparse modules, email exclusion, and Groups non-inference\n";
