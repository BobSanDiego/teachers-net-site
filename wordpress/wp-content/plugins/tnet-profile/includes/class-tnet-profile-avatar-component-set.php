<?php
/**
 * Review-only illustrated avatar component set.
 *
 * This class deliberately does not participate in the Profile avatar resolver
 * until component-set-v1 receives product approval.
 */

defined('ABSPATH') || exit;

final class TNet_Profile_Avatar_Component_Set {
  const VERSION = 'component-set-v1-r4';
  const SVG_ROUTE = 'profile/avatar-component.svg';
  const LAB_ROUTE = 'profile/avatar-components';

  private static $heads = [
    'oval' => 'Oval', 'round' => 'Round', 'long' => 'Long',
    'broad' => 'Broad', 'soft-square' => 'Soft square',
  ];

  private static $hair = [
    'close' => ['label' => 'Close crop', 'heads' => ['oval', 'round', 'long', 'broad', 'soft-square']],
    'crop' => ['label' => 'Textured crop', 'heads' => ['oval', 'round', 'broad', 'soft-square']],
    'wave' => ['label' => 'Soft waves', 'heads' => ['oval', 'round', 'long', 'soft-square']],
    'curls' => ['label' => 'Curls', 'heads' => ['oval', 'round', 'broad', 'soft-square']],
    'bob' => ['label' => 'Classic bob', 'heads' => ['oval', 'round', 'long', 'soft-square']],
    'locs' => ['label' => 'Locs', 'heads' => ['oval', 'round', 'long', 'broad']],
    'long' => ['label' => 'Long layers', 'heads' => ['oval', 'long', 'soft-square']],
    'bun' => ['label' => 'Low bun', 'heads' => ['oval', 'round', 'long', 'soft-square']],
    'pixie' => ['label' => 'Pixie cut', 'heads' => ['oval', 'round', 'long', 'soft-square']],
    'undercut' => ['label' => 'Tapered undercut', 'heads' => ['oval', 'round', 'broad', 'soft-square']],
  ];

  private static $pool_hair = [
    'all' => ['close', 'crop', 'wave', 'curls', 'bob', 'locs', 'long', 'bun', 'pixie', 'undercut'],
    'masculine' => ['close', 'crop', 'wave', 'curls', 'locs', 'undercut'],
    'feminine' => ['wave', 'curls', 'bob', 'locs', 'long', 'bun', 'pixie'],
    'neutral' => ['close', 'crop', 'curls', 'locs', 'bob', 'pixie'],
  ];

  private static $skin = ['#f7d7bd', '#edc29f', '#dba47d', '#bf7c55', '#985b3d', '#74442f', '#4f3029', '#3b2524'];
  private static $hair_colors = ['#211a1a', '#423029', '#68442f', '#8a5634', '#b47743', '#d3a15b', '#6b6971', '#e1d3c6'];
  private static $backgrounds = ['#d9e9f8', '#d8eee6', '#f8e2c7', '#e9def7', '#f8dce0', '#e6ebd6', '#dce7f4', '#f0e4d4'];
  private static $tops = ['#153f75', '#28796b', '#9b3f47', '#78558f', '#bd6f38', '#516a38', '#396f9e', '#6f5560'];
  private static $eyes = ['calm', 'bright', 'focused'];
  private static $brows = ['soft', 'defined', 'arched'];
  private static $mouths = ['rest', 'soft-smile', 'warm-smile'];

  public static function version() { return self::VERSION; }

  public static function svg_url($seed, $pool = 'all', $size = 160, $overrides = []) {
    $args = array_merge([
      'v' => self::VERSION,
      'seed' => strtolower((string) $seed),
      'pool' => self::valid_pool($pool),
      'size' => max(32, min(512, absint($size) ?: 160)),
    ], array_filter($overrides, function ($value) { return $value !== null && $value !== ''; }));
    if (isset($overrides['head'], $overrides['hair']) && !isset($overrides['headwear'])) $args['headwear'] = 'none';
    return add_query_arg($args, home_url('/' . self::SVG_ROUTE . '/'));
  }

  public static function sample_seed($index) {
    return hash('sha256', self::VERSION . '|review-sample|' . absint($index));
  }

  public static function valid_pool($pool) {
    return isset(self::$pool_hair[$pool]) ? $pool : 'all';
  }

  public static function valid_seed($seed) {
    return is_string($seed) && (bool) preg_match('/^[a-f0-9]{64}$/', $seed);
  }

  public static function render_svg_response() {
    $version = isset($_GET['v']) ? sanitize_key(wp_unslash($_GET['v'])) : '';
    $seed = isset($_GET['seed']) ? strtolower(sanitize_text_field(wp_unslash($_GET['seed']))) : '';
    if ($version !== self::VERSION || !self::valid_seed($seed)) {
      status_header(404);
      header('Content-Type: text/plain; charset=utf-8');
      echo 'Avatar representation not found.';
      exit;
    }
    $pool = self::valid_pool(isset($_GET['pool']) ? sanitize_key(wp_unslash($_GET['pool'])) : 'all');
    $size = max(32, min(512, absint(isset($_GET['size']) ? $_GET['size'] : 160) ?: 160));
    $overrides = [];
    foreach (['head', 'hair', 'eyes', 'brows', 'mouth', 'headwear'] as $key) {
      if (isset($_GET[$key])) $overrides[$key] = sanitize_key(wp_unslash($_GET[$key]));
    }
    $svg = self::render_svg($seed, $pool, $size, $overrides);
    $etag = '"' . hash('sha256', self::VERSION . '|' . $seed . '|' . $pool . '|' . $size . '|' . wp_json_encode($overrides)) . '"';
    header('Content-Type: image/svg+xml; charset=utf-8');
    header('Cache-Control: public, max-age=31536000, immutable');
    header('ETag: ' . $etag);
    if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim((string) $_SERVER['HTTP_IF_NONE_MATCH']) === $etag) {
      status_header(304);
      exit;
    }
    echo $svg;
    exit;
  }

  public static function render_svg($seed, $pool = 'all', $size = 160, $overrides = []) {
    $spec = self::specification($seed, $pool, $overrides);
    $face = self::face_path($spec['head']);
    $hair = self::hair_markup($spec);
    $eyes = self::eye_markup($spec);
    $features = self::feature_markup($spec);
    $clothes = self::clothing_markup($spec);
    $uid = substr(hash('sha256', self::VERSION . '|' . $seed . '|' . $pool), 0, 12);
    return '<svg xmlns="http://www.w3.org/2000/svg" width="' . absint($size) . '" height="' . absint($size) . '" viewBox="0 0 160 160" role="img" aria-label="Teachers.Net illustrated avatar" data-component-set="' . esc_attr(self::VERSION) . '"><rect width="160" height="160" rx="26" fill="' . esc_attr($spec['background']) . '"/><path d="M26 160c3-27 18-43 42-48h24c24 5 39 21 42 48" fill="' . esc_attr($spec['top']) . '"/><path d="M67 104h26v25c-6 8-20 8-26 0z" fill="' . esc_attr($spec['skin']) . '"/><path d="M46 72c0-11 7-19 16-19v42c-9 0-16-9-16-23zm68 0c0-11-7-19-16-19v42c9 0 16-9 16-23z" fill="' . esc_attr($spec['skin']) . '" opacity=".92"/><path d="' . $face . '" fill="' . esc_attr($spec['skin']) . '" stroke="#3d3030" stroke-opacity=".18" stroke-width="1.5"/><g id="hair-' . $uid . '">' . $hair . '</g>' . $eyes . $features . $clothes . '</svg>';
  }

  public static function specification($seed, $pool = 'all', $overrides = []) {
    $pool = self::valid_pool($pool);
    $seed = self::valid_seed($seed) ? $seed : self::sample_seed(0);
    $head = self::valid_head(isset($overrides['head']) ? $overrides['head'] : self::pick($seed, 'head', array_keys(self::$heads)));
    $hair_options = self::compatible_hair($head, $pool);
    $hair = isset($overrides['hair']) && in_array($overrides['hair'], $hair_options, true)
      ? $overrides['hair'] : self::pick($seed, 'hair', $hair_options);
    $eyes = self::valid_from($overrides, 'eyes', self::$eyes, self::pick($seed, 'eyes', self::$eyes));
    $brows = self::valid_from($overrides, 'brows', self::$brows, self::pick($seed, 'brows', self::$brows));
    $mouth = self::valid_from($overrides, 'mouth', self::$mouths, self::pick($seed, 'mouth', self::$mouths));
    $headwear_options = in_array($hair, ['close', 'crop', 'pixie', 'undercut'], true) ? ['none', 'beanie', 'cap', 'headband'] : ['none', 'headband'];
    $facial_hair_options = $mouth === 'warm-smile' ? ['none', 'stubble', 'moustache'] : ['none', 'stubble', 'moustache', 'short-beard'];
    return [
      'head' => $head,
      'hair' => $hair,
      'skin' => self::pick($seed, 'skin', self::$skin),
      'hair_color' => self::pick($seed, 'hair-color', self::$hair_colors),
      'background' => self::pick($seed, 'background', self::$backgrounds),
      'top' => self::pick($seed, 'top', self::$tops),
      'eyes' => $eyes,
      'brows' => $brows,
      'mouth' => $mouth,
      'glasses' => self::pick($seed, 'glasses', ['none', 'none', 'round', 'square']),
      'facial_hair' => self::pick($seed, 'facial-hair', $facial_hair_options),
      'accessory' => self::pick($seed, 'accessory', ['none', 'none', 'earrings', 'necklace', 'scarf']),
      'headwear' => self::valid_from($overrides, 'headwear', $headwear_options, self::pick($seed, 'headwear', $headwear_options)),
      'detail_stage' => self::pick($seed, 'detail-stage', ['fresh', 'established', 'seasoned']),
    ];
  }

  public static function compatibility_rows() { return self::$heads; }
  public static function compatibility_columns() { return self::$hair; }
  public static function is_compatible($head, $hair) { return isset(self::$hair[$hair]) && in_array($head, self::$hair[$hair]['heads'], true); }
  public static function expression_values() { return ['eyes' => self::$eyes, 'brows' => self::$brows, 'mouths' => self::$mouths]; }

  private static function valid_from($overrides, $key, $values, $fallback) {
    return isset($overrides[$key]) && in_array($overrides[$key], $values, true) ? $overrides[$key] : $fallback;
  }
  private static function valid_head($head) { return isset(self::$heads[$head]) ? $head : 'oval'; }
  private static function compatible_hair($head, $pool) {
    $choices = array_values(array_filter(self::$pool_hair[$pool], function ($hair) use ($head) { return self::is_compatible($head, $hair); }));
    return $choices ?: ['close'];
  }
  private static function pick($seed, $key, $choices) {
    $index = hexdec(substr(hash('sha256', self::VERSION . '|' . $seed . '|' . $key), 0, 8)) % count($choices);
    return $choices[$index];
  }

  private static function face_path($head) {
    $paths = [
      'oval' => 'M50 61c0-24 13-37 30-37s30 13 30 37v26c0 23-13 39-30 39S50 110 50 87z',
      'round' => 'M45 62c0-24 15-38 35-38s35 14 35 38v25c0 23-15 38-35 38S45 110 45 87z',
      'long' => 'M54 54c0-20 11-31 26-31s26 11 26 31v36c0 25-11 40-26 40S54 115 54 90z',
      'broad' => 'M42 62c0-23 16-37 38-37s38 14 38 37v26c0 22-16 37-38 37S42 110 42 88z',
      'soft-square' => 'M47 57c0-21 13-33 33-33s33 12 33 33v33c0 22-13 36-33 36S47 112 47 90z',
    ];
    return $paths[$head];
  }

  private static function hair_markup($spec) {
    $color = esc_attr($spec['hair_color']);
    $head = $spec['head'];
    $cap = [
      'oval' => 'M49 68c0-29 15-45 31-45s31 16 31 45c-12-9-19-13-31-13s-20 4-31 13z',
      'round' => 'M44 69c0-30 16-46 36-46s36 16 36 46c-13-9-21-13-36-13s-23 4-36 13z',
      'long' => 'M53 59c0-24 12-37 27-37s27 13 27 37c-10-8-18-12-27-12s-17 4-27 12z',
      'broad' => 'M41 69c0-29 17-45 39-45s39 16 39 45c-13-8-24-12-39-12s-26 4-39 12z',
      'soft-square' => 'M46 64c0-26 14-41 34-41s34 15 34 41c-11-9-21-13-34-13s-23 4-34 13z',
    ][$head];
    $base = '<path d="' . $cap . '" fill="' . $color . '"/>';
    $styles = [
      'close' => '<path d="M48 61c7-25 20-34 32-34 13 0 27 10 32 34-12-8-22-11-32-11s-21 3-32 11z" fill="' . $color . '"/>',
      'crop' => '<path d="M48 62c5-25 19-36 32-36 15 0 28 12 32 36-10-10-19-15-32-15-12 0-23 5-32 15z" fill="' . $color . '"/><path d="M57 43l5-10 5 9 7-11 6 10 7-10 5 11 8-8 2 15" fill="none" stroke="' . $color . '" stroke-width="5" stroke-linecap="round"/>',
      'wave' => '<path d="M47 65c4-25 18-39 33-39 16 0 29 14 33 39-7-7-13-8-18-14-5 7-11 8-16 4-6 7-14 5-19 10-4-4-8-3-13 0z" fill="' . $color . '"/><path d="M52 60c0 23 4 39 12 48M108 60c0 23-4 39-12 48" fill="none" stroke="' . $color . '" stroke-width="10" stroke-linecap="round"/>',
      'curls' => '<g fill="' . $color . '"><circle cx="53" cy="53" r="12"/><circle cx="67" cy="42" r="13"/><circle cx="83" cy="39" r="14"/><circle cx="99" cy="46" r="13"/><circle cx="109" cy="59" r="11"/><circle cx="52" cy="69" r="10"/><circle cx="108" cy="72" r="10"/></g>',
      'bob' => '<path d="M45 67c1-28 16-43 35-43s34 15 35 43v47H99V61c-5-5-11-7-19-7-7 0-14 2-19 7v53H45z" fill="' . $color . '"/><path d="M52 58c8-10 17-14 28-14s21 4 28 14" fill="none" stroke="' . $color . '" stroke-width="12" stroke-linecap="round"/>',
      'locs' => '<path d="M48 65c5-26 19-40 32-40s28 14 32 40" fill="' . $color . '"/><path d="M52 54c-4 22-4 42-1 59M61 47c-3 17-3 31-1 44M108 54c4 22 4 42 1 59M99 47c3 17 3 31 1 44" fill="none" stroke="' . $color . '" stroke-width="7" stroke-linecap="round"/>',
      'long' => '<path d="M47 66c4-28 18-42 33-42s29 14 33 42v57H99V59c-6-5-12-7-19-7s-14 2-19 7v64H47z" fill="' . $color . '"/><path d="M52 60c6-13 16-20 28-20s22 7 28 20" fill="none" stroke="' . $color . '" stroke-width="13" stroke-linecap="round"/>',
      'bun' => '<circle cx="101" cy="28" r="17" fill="' . $color . '"/><path d="M49 65c4-26 18-40 31-40 15 0 28 13 32 40-10-10-21-14-32-14-12 0-22 4-31 14z" fill="' . $color . '"/><path d="M54 58c5 16 17 23 26 23s20-7 26-23" fill="none" stroke="' . $color . '" stroke-width="9" stroke-linecap="round"/>',
      'pixie' => '<path d="M48 66c1-27 15-42 32-42 15 0 28 12 33 34-12-5-18-10-25-17-7 8-18 13-40 25z" fill="' . $color . '"/><path d="M52 56c11-6 21-15 27-26 5 11 14 17 28 20" fill="none" stroke="' . $color . '" stroke-width="8" stroke-linecap="round"/>',
      'undercut' => '<path d="M48 63c5-25 18-38 32-38 15 0 28 12 32 38-14-7-25-17-34-27-7 11-18 20-30 27z" fill="' . $color . '"/><path d="M55 58c-1 16 1 24 6 31M105 58c1 16-1 24-6 31" fill="none" stroke="' . $color . '" stroke-width="4" opacity=".65"/>',
    ];
    $markup = $styles[$spec['hair']] . $base;
    if ($spec['headwear'] === 'beanie') $markup .= '<path d="M48 55c4-23 17-32 32-32s28 9 32 32H48z" fill="#365d81"/><path d="M47 53h66v12H47z" fill="#27496c"/>';
    if ($spec['headwear'] === 'cap') $markup .= '<path d="M49 56c4-20 17-31 31-31 15 0 27 11 31 31H49z" fill="#445e7d"/><path d="M81 54c20-1 31 3 36 10-17 2-29-1-37-5z" fill="#354d69"/>';
    if ($spec['headwear'] === 'headband') $markup .= '<path d="M48 57c10-9 21-13 32-13s23 4 32 13v9c-10-7-21-10-32-10s-22 3-32 10z" fill="#c86b68" opacity=".92"/>';
    return $markup;
  }

  private static function eye_markup($spec) {
    $eye = $spec['eyes'] === 'bright' ? '<circle cx="68" cy="77" r="4"/><circle cx="92" cy="77" r="4"/>' : ($spec['eyes'] === 'focused' ? '<ellipse cx="68" cy="77" rx="3.2" ry="4.4"/><ellipse cx="92" cy="77" rx="3.2" ry="4.4"/>' : '<ellipse cx="68" cy="77" rx="4" ry="3.2"/><ellipse cx="92" cy="77" rx="4" ry="3.2"/>');
    $brow = $spec['brows'] === 'arched' ? 'M62 69q6-6 12 0M86 69q6-6 12 0' : ($spec['brows'] === 'defined' ? 'M61 69l13-2M86 67l13 2' : 'M62 69q6-3 12 0M86 69q6-3 12 0');
    $glasses = '';
    if ($spec['glasses'] === 'round') $glasses = '<g fill="none" stroke="#3f4b5c" stroke-width="2.5"><circle cx="68" cy="77" r="9"/><circle cx="92" cy="77" r="9"/><path d="M77 77h6M59 75l-7-2M101 75l7-2"/></g>';
    if ($spec['glasses'] === 'square') $glasses = '<g fill="none" stroke="#3f4b5c" stroke-width="2.5"><rect x="58" y="68" width="19" height="17" rx="4"/><rect x="83" y="68" width="19" height="17" rx="4"/><path d="M77 77h6M58 74l-6-2M102 74l6-2"/></g>';
    return '<path d="' . $brow . '" fill="none" stroke="#563b33" stroke-width="2.4" stroke-linecap="round"/><g fill="#26313b">' . $eye . '</g>' . $glasses;
  }

  private static function feature_markup($spec) {
    $mouths = ['rest' => 'M72 104q8 2 16 0', 'soft-smile' => 'M71 102q9 8 18 0', 'warm-smile' => 'M69 101q11 11 22 0'];
    $markup = '<path d="M80 80v13l-4 4" fill="none" stroke="#8f5c49" stroke-width="2" stroke-linecap="round"/><path d="' . $mouths[$spec['mouth']] . '" fill="none" stroke="#7d3f42" stroke-width="2.5" stroke-linecap="round"/>';
    if ($spec['detail_stage'] === 'established') $markup .= '<path d="M57 92q4 3 7 1M96 93q4 2 7-1" fill="none" stroke="#9e6a59" stroke-opacity=".45" stroke-width="1.1"/>';
    if ($spec['detail_stage'] === 'seasoned') $markup .= '<path d="M56 89q5 4 9 2M95 91q5 2 9-2M67 108q13 5 26 0" fill="none" stroke="#9e6a59" stroke-opacity=".52" stroke-width="1.15"/>';
    if ($spec['facial_hair'] === 'stubble') $markup .= '<path d="M61 96q3 18 19 20 16-2 19-20" fill="none" stroke="#6b514a" stroke-opacity=".42" stroke-width="3" stroke-dasharray="1 3"/>';
    if ($spec['facial_hair'] === 'moustache') $markup .= '<path d="M79 99q-7-5-12 0 7 7 13 3 6 4 13-3-5-5-12 0" fill="#5b4037" opacity=".9"/>';
    if ($spec['facial_hair'] === 'short-beard') $markup .= '<path d="M61 96q4 24 19 25 15-1 19-25-8 8-19 8t-19-8z" fill="#5b4037" opacity=".78"/><path d="M72 103q8 4 16 0" fill="none" stroke="#7d3f42" stroke-width="2.3" stroke-linecap="round"/>';
    if ($spec['accessory'] === 'earrings') $markup .= '<g fill="#c58c32"><circle cx="55" cy="94" r="3"/><circle cx="105" cy="94" r="3"/></g>';
    if ($spec['accessory'] === 'necklace') $markup .= '<path d="M68 124q12 11 24 0" fill="none" stroke="#cf9d42" stroke-width="2"/><circle cx="80" cy="133" r="2.5" fill="#cf9d42"/>';
    if ($spec['accessory'] === 'scarf') $markup .= '<path d="M61 122q19 13 38 0l6 18H55z" fill="#d88a5b" opacity=".95"/>';
    return $markup;
  }

  private static function clothing_markup($spec) {
    $collars = [
      0 => '<path d="M65 126l15 13 15-13" fill="none" stroke="#fff" stroke-opacity=".42" stroke-width="2"/>',
      1 => '<path d="M69 124l11 16 11-16" fill="#fff" fill-opacity=".34"/>',
      2 => '<path d="M62 127q18 13 36 0" fill="none" stroke="#fff" stroke-opacity=".4" stroke-width="4"/>',
    ];
    return $collars[hexdec(substr(hash('sha256', $spec['top'] . '|' . $spec['hair']), 0, 2)) % 3];
  }
}

final class TNet_Profile_Avatar_Component_Lab {
  public static function render() {
    if (!current_user_can('manage_options')) wp_die(esc_html__('This review surface is restricted to Teachers.Net administrators.', 'tnet-profile'), 403);
    $version = TNet_Profile_Avatar_Component_Set::version();
    $initial = TNet_Profile_Avatar_Component_Set::sample_seed(1);
    add_action('wp_footer', static function () {
      echo '<style id="tnet-avatar-component-review-layout">.tnet-scale-grid{grid-template-columns:repeat(auto-fit,minmax(310px,1fr))}</style>';
    });
    ?><!doctype html><html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?php echo esc_html__('Avatar component review | Teachers.Net', 'tnet-profile'); ?></title><?php wp_head(); ?><style>
      :root{color:#17233e;background:#edf2f8;font:16px/1.45 system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.tnet-avatar-lab{max-width:1180px;margin:0 auto;padding:32px 24px 80px;color:#17233e}.tnet-avatar-lab *{box-sizing:border-box}.tnet-avatar-lab h1,.tnet-avatar-lab h2,.tnet-avatar-lab h3{color:#122875;line-height:1.15}.tnet-avatar-lab h1{font-size:clamp(2rem,4vw,3.1rem);margin:0 0 8px}.tnet-avatar-lab h2{font-size:1.45rem;margin:0 0 12px}.tnet-avatar-lab p{max-width:75ch}.tnet-avatar-lab .lead{font-size:1.08rem;color:#4b5b75}.tnet-avatar-lab .notice{border-left:4px solid #315ac6;background:#fff;padding:14px 16px;margin:22px 0}.tnet-avatar-lab section{background:#fff;border:1px solid #d5dfed;border-radius:16px;padding:22px;margin-top:22px;box-shadow:0 5px 16px rgba(31,55,92,.06)}.tnet-avatar-controls{display:flex;gap:10px;align-items:end;flex-wrap:wrap}.tnet-avatar-pools{display:flex;gap:7px;flex-wrap:wrap}.tnet-avatar-lab button{appearance:none;border:1px solid #aabbd6;border-radius:7px;background:#fff;color:#173e86;font:inherit;font-weight:650;padding:9px 12px;cursor:pointer}.tnet-avatar-lab button[aria-pressed="true"],.tnet-avatar-lab button.primary{background:#174cbe;border-color:#174cbe;color:#fff}.tnet-avatar-lab label{display:grid;gap:5px;font-weight:650;color:#3f5170}.tnet-avatar-lab input{min-height:40px;border:1px solid #9eb0ce;border-radius:7px;padding:8px 10px;font:inherit;color:#17233e;max-width:100%}.tnet-avatar-seed{width:min(100%,420px)}.tnet-avatar-count{margin:14px 0 0;font-size:.92rem;color:#546683}.tnet-avatar-rolls{display:grid;grid-template-columns:repeat(auto-fill,minmax(132px,1fr));gap:12px;margin-top:18px}.tnet-avatar-card{min-width:0;text-align:center;border:1px solid #dde5f1;border-radius:12px;padding:12px 8px;background:#fbfcff}.tnet-avatar-card img{display:block;margin:0 auto 8px;border-radius:17%;background:#e9eef5}.tnet-avatar-card code{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.68rem;color:#62718b}.tnet-avatar-matrix{overflow:auto}.tnet-avatar-matrix table{border-collapse:separate;border-spacing:6px;min-width:760px;width:100%}.tnet-avatar-matrix th{font-size:.78rem;text-align:left;color:#4c5e7e;vertical-align:bottom;padding:4px}.tnet-avatar-matrix td{min-width:112px;text-align:center;padding:8px;border:1px solid #dce5f1;border-radius:10px;background:#fbfcff}.tnet-avatar-matrix td img{border-radius:16%;display:block;margin:0 auto 5px}.tnet-avatar-matrix .excluded{color:#7b5b62;background:#f9f1f2;border-color:#efd7db;font-size:.74rem;min-height:108px;display:grid;place-items:center}.tnet-expression-grid,.tnet-diversity-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(122px,1fr));gap:12px}.tnet-expression-grid figure,.tnet-diversity-grid figure{margin:0;padding:10px;text-align:center;border:1px solid #dee6f0;border-radius:11px;background:#fbfcff}.tnet-expression-grid img,.tnet-diversity-grid img{border-radius:16%;display:block;margin:0 auto 7px}.tnet-expression-grid figcaption,.tnet-diversity-grid figcaption{font-size:.73rem;color:#51617c}.tnet-scale-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px}.tnet-scale-card{border:1px solid #dee6f0;border-radius:11px;padding:12px;text-align:center;background:#fbfcff}.tnet-scale-row{display:flex;min-height:128px;align-items:end;justify-content:center;gap:12px}.tnet-scale-row img{border-radius:17%;display:block}.tnet-scale-row span{font-size:.72rem;color:#526480;display:grid;gap:4px;justify-items:center}.tnet-avatar-lab code{font-family:ui-monospace,SFMono-Regular,Consolas,monospace}@media(max-width:520px){.tnet-avatar-lab{padding:22px 16px 56px}.tnet-avatar-lab section{padding:16px}.tnet-avatar-controls{align-items:stretch}.tnet-avatar-controls label,.tnet-avatar-seed{width:100%}.tnet-avatar-pools,.tnet-avatar-controls>button{width:100%}.tnet-avatar-pools button{flex:1 1 42%}.tnet-avatar-rolls{grid-template-columns:repeat(2,minmax(0,1fr));gap:9px}.tnet-expression-grid,.tnet-diversity-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:9px}}
    </style></head><body><main class="tnet-avatar-lab" data-svg-base="<?php echo esc_url(home_url('/' . TNet_Profile_Avatar_Component_Set::SVG_ROUTE . '/')); ?>" data-version="<?php echo esc_attr($version); ?>"><h1>Illustrated avatar component review</h1><p class="lead">Component set v1 is a Profile-owned, review-only candidate. It does not alter any member avatar, resolver precedence, or member attribute.</p><p class="notice"><strong>Review boundary:</strong> pool labels are temporary browsing controls only. They are not stored on a member and do not represent gender, pronouns, age, race, ethnicity, or identity.</p><section aria-labelledby="roller-title"><h2 id="roller-title">Candidate roller</h2><div class="tnet-avatar-controls"><div class="tnet-avatar-pools" role="group" aria-label="Presentation pool"><button type="button" data-pool="all" aria-pressed="true">All</button><button type="button" data-pool="masculine" aria-pressed="false">Masculine</button><button type="button" data-pool="feminine" aria-pressed="false">Feminine</button><button type="button" data-pool="neutral" aria-pressed="false">Neutral</button></div><button type="button" class="primary" data-roll="20">Roll 20</button><button type="button" data-roll="100">Roll 100</button><label>Opaque review seed<input class="tnet-avatar-seed" data-seed value="<?php echo esc_attr($initial); ?>" inputmode="text" maxlength="64" pattern="[a-fA-F0-9]{64}"></label><button type="button" data-rerender>Same-seed rerender</button></div><p class="tnet-avatar-count" data-roll-status aria-live="polite"></p><div class="tnet-avatar-rolls" data-roll-results></div></section><section aria-labelledby="compatibility-title"><h2 id="compatibility-title">Head / hair compatibility matrix</h2><p>Every approved pairing is generated by the same server SVG endpoint. Muted cells are intentionally excluded from component-set-v1.</p><div class="tnet-avatar-matrix"><table><thead><tr><th>Head family</th><?php foreach (TNet_Profile_Avatar_Component_Set::compatibility_columns() as $hair => $definition) : ?><th><?php echo esc_html($definition['label']); ?></th><?php endforeach; ?></tr></thead><tbody><?php foreach (TNet_Profile_Avatar_Component_Set::compatibility_rows() as $head => $label) : ?><tr><th scope="row"><?php echo esc_html($label); ?></th><?php foreach (TNet_Profile_Avatar_Component_Set::compatibility_columns() as $hair => $definition) : ?><td class="<?php echo TNet_Profile_Avatar_Component_Set::is_compatible($head, $hair) ? 'allowed' : 'excluded'; ?>"><?php if (TNet_Profile_Avatar_Component_Set::is_compatible($head, $hair)) : ?><img src="<?php echo esc_url(TNet_Profile_Avatar_Component_Set::svg_url(TNet_Profile_Avatar_Component_Set::sample_seed(crc32($head . $hair)), 'all', 96, ['head' => $head, 'hair' => $hair])); ?>" width="96" height="96" alt="<?php echo esc_attr($label . ' with ' . $definition['label']); ?>"><small>approved</small><?php else : ?><span>Excluded<br><small>not reviewed together</small></span><?php endif; ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody></table></div></section><section aria-labelledby="expression-title"><h2 id="expression-title">Expression matrix</h2><p>Controlled reference heads use only open-eye, closed-mouth expressions. This makes eyebrow, eye, and mouth review explicit.</p><div class="tnet-expression-grid"><?php $expressions = TNet_Profile_Avatar_Component_Set::expression_values(); foreach ($expressions['eyes'] as $eyes) foreach ($expressions['brows'] as $brows) foreach ($expressions['mouths'] as $mouth) : ?><figure><img src="<?php echo esc_url(TNet_Profile_Avatar_Component_Set::svg_url(TNet_Profile_Avatar_Component_Set::sample_seed(crc32($eyes . $brows . $mouth)), 'neutral', 112, ['head' => 'oval', 'hair' => 'crop', 'eyes' => $eyes, 'brows' => $brows, 'mouth' => $mouth])); ?>" width="112" height="112" alt="Open-eye <?php echo esc_attr($eyes . ', ' . $brows . ' brows, ' . $mouth); ?>"><figcaption><?php echo esc_html($eyes . ' / ' . $brows . ' / ' . $mouth); ?></figcaption></figure><?php endforeach; ?></div></section><section aria-labelledby="scale-title"><h2 id="scale-title">Feed-scale contact sheet</h2><p>Representative candidates at their intended 32px, 48px, 64px, and profile-preview rendering scales.</p><div class="tnet-scale-grid"><?php for ($i = 1; $i <= 6; $i++) : $seed = TNet_Profile_Avatar_Component_Set::sample_seed(500 + $i); ?><div class="tnet-scale-card"><div class="tnet-scale-row"><?php foreach ([32, 48, 64, 112] as $size) : ?><span><img src="<?php echo esc_url(TNet_Profile_Avatar_Component_Set::svg_url($seed, 'all', $size)); ?>" width="<?php echo absint($size); ?>" height="<?php echo absint($size); ?>" alt=""><small><?php echo absint($size); ?>px</small></span><?php endforeach; ?></div></div><?php endfor; ?></div></section><section aria-labelledby="diversity-title"><h2 id="diversity-title">Adult visual-variety contact sheet</h2><p>These candidate illustrations deliberately span facial construction, skin/hair colors, styling detail, clothing, backgrounds, glasses, facial hair, and accessories. They are visual components only—not member demographic data.</p><div class="tnet-diversity-grid"><?php for ($i = 1; $i <= 30; $i++) : $seed = TNet_Profile_Avatar_Component_Set::sample_seed(700 + $i); ?><figure><img src="<?php echo esc_url(TNet_Profile_Avatar_Component_Set::svg_url($seed, 'all', 112)); ?>" width="112" height="112" alt="Illustrated avatar candidate <?php echo absint($i); ?>"><figcaption>candidate <?php echo absint($i); ?></figcaption></figure><?php endfor; ?></div></section></main><script>
      (()=>{const root=document.querySelector('.tnet-avatar-lab'),base=root.dataset.svgBase,version=root.dataset.version,seedInput=root.querySelector('[data-seed]'),results=root.querySelector('[data-roll-results]'),status=root.querySelector('[data-roll-status]');let pool='all',count=20,seed=seedInput.value;const clean=s=>/^[a-f0-9]{64}$/i.test(s)?s.toLowerCase():null;const randomSeed=()=>{const values=new Uint32Array(8);crypto.getRandomValues(values);return [...values].map(v=>v.toString(16).padStart(8,'0')).join('')};const derived=(rootSeed,index)=>rootSeed.slice(0,56)+index.toString(16).padStart(8,'0');const url=(item,size=96)=>`${base}?v=${encodeURIComponent(version)}&seed=${encodeURIComponent(item)}&pool=${encodeURIComponent(pool)}&size=${size}`;const render=()=>{const safe=clean(seedInput.value);if(!safe){status.textContent='Use a 64-character hexadecimal opaque seed.';return}seed=safe;results.replaceChildren();const fragment=document.createDocumentFragment();for(let i=0;i<count;i++){const item=derived(seed,i);const card=document.createElement('article');card.className='tnet-avatar-card';const image=document.createElement('img');image.src=url(item,96);image.width=96;image.height=96;image.alt=`${pool} presentation-pool candidate ${i+1}`;const code=document.createElement('code');code.textContent=item.slice(0,12);card.append(image,code);fragment.append(card)}results.append(fragment);status.textContent=`${count} server-composed ${pool} pool candidates. Same seed and pool re-render identically.`};root.querySelectorAll('[data-pool]').forEach(button=>button.addEventListener('click',()=>{pool=button.dataset.pool;root.querySelectorAll('[data-pool]').forEach(item=>item.setAttribute('aria-pressed',String(item===button)));render()}));root.querySelectorAll('[data-roll]').forEach(button=>button.addEventListener('click',()=>{count=Number(button.dataset.roll);seed=randomSeed();seedInput.value=seed;render()}));root.querySelector('[data-rerender]').addEventListener('click',render);seedInput.addEventListener('change',render);render();})();
    </script><?php wp_footer(); ?></body></html><?php exit;
  }
}
