<?php
/**
 * Plugin Name: Teachers.Net Profile
 * Description: Bounded first-party user profile capabilities.
 * Version: 0.1.0
 */

defined('ABSPATH') || exit;

define('TNET_PROFILE_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once __DIR__ . '/includes/class-tnet-profile-avatar-component-set.php';
require_once __DIR__ . '/includes/class-tnet-profile-member-context.php';
require_once __DIR__ . '/includes/class-tnet-profile-basics.php';
require_once __DIR__ . '/includes/class-tnet-profile-enrichment.php';
require_once __DIR__ . '/includes/class-tnet-profile-public.php';

final class TNet_Profile_Avatar {
  const META_KEY = '_tnet_profile_avatar_id';
  const PORTRAIT_META_KEY = '_tnet_profile_avatar_portrait_id';
  const ROUTE = 'profile';
  const MAX_BYTES = 5242880;
  private static $resolving_wordpress_fallback = false;

  public static function init() {
    add_action('init', [__CLASS__, 'register_route']);
    add_filter('query_vars', [__CLASS__, 'query_vars']);
    add_action('template_redirect', [__CLASS__, 'render_route']);
    add_action('admin_post_tnet_profile_avatar_upload', [__CLASS__, 'upload']);
    add_action('admin_post_tnet_profile_avatar_remove', [__CLASS__, 'remove']);
    add_filter('pre_get_avatar_data', [__CLASS__, 'filter_wordpress_avatar'], 10, 2);
  }

  public static function activate() {
    TNet_Profile_Member_Context::activate();
    self::register_route();
    TNet_Profile_Basics::register_route();
    TNet_Profile_Enrichment::register_route();
    TNet_Profile_Public::register_route();
    flush_rewrite_rules(false);
  }

  public static function deactivate() { flush_rewrite_rules(false); }

  public static function register_route() {
    add_rewrite_rule('^profile/edit/avatar/?$', 'index.php?tnet_profile_route=avatar_editor', 'top');
    add_rewrite_rule('^profile/avatar-component\.svg/?$', 'index.php?tnet_profile_route=avatar_component_svg', 'top');
    add_rewrite_rule('^profile/avatar-components/?$', 'index.php?tnet_profile_route=avatar_component_lab', 'top');
  }

  public static function query_vars($vars) { $vars[] = 'tnet_profile_route'; return $vars; }

  public static function resolve_avatar($user_id, $size = 96) {
    $user_id = absint($user_id);
    $size = max(16, min(512, absint($size) ?: 96));
    $attachment_id = $user_id ? absint(get_user_meta($user_id, self::META_KEY, true)) : 0;
    if ($attachment_id && self::owned_image($attachment_id, $user_id)) {
      $url = wp_get_attachment_image_url($attachment_id, [ $size, $size ]);
      if ($url) return ['url' => $url, 'source' => 'first-party', 'is_custom' => true];
    }
    $portrait_id = $user_id ? sanitize_text_field((string) get_user_meta($user_id, self::PORTRAIT_META_KEY, true)) : '';
    $portrait = $portrait_id ? self::portrait_entry($portrait_id) : null;
    if ($portrait) return ['url' => esc_url_raw($portrait['url']), 'source' => 'portrait-bank-v1', 'portrait_id' => $portrait['portrait_id'], 'is_custom' => true];
    $legacy_buddypress = self::legacy_buddypress_avatar($user_id, $size);
    if ($legacy_buddypress) return $legacy_buddypress;
    $fallback = self::wordpress_fallback($user_id, $size);
    return ['url' => esc_url_raw($fallback['url']), 'source' => 'wordpress-fallback', 'is_custom' => false];
  }

  public static function portrait_bank_entries($generation = '', $presentation = '') {
    $entries = [];
    foreach (self::portrait_bank_manifest() as $entry) {
      if ($generation !== '' && (string) ($entry['generation_retrieval_bucket'] ?? '') !== (string) $generation) continue;
      if ($presentation !== '' && (string) ($entry['presentation_retrieval_bucket'] ?? '') !== (string) $presentation) continue;
      $entry['url'] = self::portrait_asset_url((string) ($entry['asset_path'] ?? ''));
      if ($entry['url'] !== '') $entries[] = $entry;
    }
    return $entries;
  }

  public static function set_portrait_avatar($user_id, $portrait_id) {
    $user_id = absint($user_id);
    $portrait_id = sanitize_text_field((string) $portrait_id);
    $entry = self::portrait_entry($portrait_id);
    if (!$user_id || !$entry) return new WP_Error('tnet_profile_portrait_invalid', __('That illustrated avatar is no longer available.', 'tnet-profile'));
    $old_attachment = absint(get_user_meta($user_id, self::META_KEY, true));
    delete_user_meta($user_id, self::META_KEY);
    update_user_meta($user_id, self::PORTRAIT_META_KEY, $portrait_id);
    if ($old_attachment && self::owned_image($old_attachment, $user_id)) wp_delete_attachment($old_attachment, true);
    return $entry;
  }

  public static function save_uploaded_avatar($user_id, array $file) {
    $user_id = absint($user_id);
    if (!$user_id || empty($file['name']) || !empty($file['error'])) return new WP_Error('tnet_profile_avatar_invalid', __('Choose a readable image file.', 'tnet-profile'));
    if ((int) ($file['size'] ?? 0) > self::MAX_BYTES) return new WP_Error('tnet_profile_avatar_invalid', __('Choose an image no larger than 5 MB.', 'tnet-profile'));
    $check = wp_check_filetype_and_ext($file['tmp_name'], $file['name']);
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (empty($check['type']) || !in_array(strtolower((string) $check['ext']), $allowed, true)) return new WP_Error('tnet_profile_avatar_invalid', __('Choose a JPG, PNG, or WebP image.', 'tnet-profile'));
    $dimensions = @getimagesize($file['tmp_name']);
    if (!$dimensions || $dimensions[0] < 64 || $dimensions[1] < 64 || $dimensions[0] > 2048 || $dimensions[1] > 2048) return new WP_Error('tnet_profile_avatar_invalid', __('Image dimensions must be between 64px and 2048px.', 'tnet-profile'));
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $title = sanitize_text_field(pathinfo($file['name'], PATHINFO_FILENAME));
    $attachment_id = media_handle_sideload($file, 0, $title, ['post_author' => $user_id, 'post_title' => $title, 'post_status' => 'inherit']);
    if (is_wp_error($attachment_id)) return $attachment_id;
    self::store_attachment($user_id, absint($attachment_id));
    return absint($attachment_id);
  }

  public static function filter_wordpress_avatar($args, $id_or_email) {
    if (self::$resolving_wordpress_fallback) return $args;
    $user_id = self::avatar_user_id($id_or_email);
    if (!$user_id) return $args;
    $avatar = self::resolve_avatar($user_id, isset($args['size']) ? $args['size'] : 96);
    if (empty($avatar['url'])) return $args;
    $args['url'] = esc_url_raw((string) $avatar['url']);
    $args['found_avatar'] = !empty($avatar['is_custom']);
    return $args;
  }

  private static function wordpress_fallback($user_id, $size) {
    $previous = self::$resolving_wordpress_fallback;
    self::$resolving_wordpress_fallback = true;
    try {
      return get_avatar_data($user_id, ['size' => $size, 'default' => 'mystery']);
    } finally {
      self::$resolving_wordpress_fallback = $previous;
    }
  }

  private static function legacy_buddypress_avatar($user_id, $size) {
    if (!$user_id || !function_exists('bp_get_user_has_avatar') || !function_exists('bp_core_fetch_avatar')) return null;
    if (!bp_get_user_has_avatar($user_id)) return null;
    $url = bp_core_fetch_avatar([
      'item_id' => $user_id,
      'object' => 'user',
      'type' => 'full',
      'width' => $size,
      'height' => $size,
      'html' => false,
    ]);
    return $url ? ['url' => esc_url_raw($url), 'source' => 'legacy-buddypress', 'is_custom' => true] : null;
  }

  private static function avatar_user_id($id_or_email) {
    if ($id_or_email instanceof WP_User) return absint($id_or_email->ID);
    if ($id_or_email instanceof WP_Comment) return absint($id_or_email->user_id);
    if ($id_or_email instanceof WP_Post) return absint($id_or_email->post_author);
    if (is_numeric($id_or_email)) return absint($id_or_email);
    if (is_string($id_or_email) && is_email($id_or_email) && function_exists('get_user_by')) {
      $user = get_user_by('email', $id_or_email);
      return $user ? absint($user->ID) : 0;
    }
    return 0;
  }

  private static function owned_image($attachment_id, $user_id) {
    $post = get_post($attachment_id);
    return $post && $post->post_type === 'attachment' && (int) $post->post_author === $user_id && strpos((string) $post->post_mime_type, 'image/') === 0;
  }

  private static function redirect($status) {
    $fallback = self::editor_url();
    $return_to = isset($_POST['return_to']) ? esc_url_raw(wp_unslash($_POST['return_to'])) : $fallback;
    $return_to = wp_validate_redirect($return_to, $fallback);
    $return_host = strtolower((string) wp_parse_url($return_to, PHP_URL_HOST));
    $home_host = strtolower((string) wp_parse_url(home_url('/'), PHP_URL_HOST));
    if ($return_host !== $home_host) $return_to = $fallback;
    wp_safe_redirect(add_query_arg('avatar_status', sanitize_key($status), $return_to));
    exit;
  }

  public static function editor_url() { return home_url('/profile/edit/avatar/'); }

  public static function enqueue_editor_assets() {
    if (class_exists('TNet_Identity_Public')) TNet_Identity_Public::enqueue_avatar_photo_components();
    $path = __DIR__ . '/public/js/tnet-profile-avatar-editor.js';
    wp_enqueue_script('tnet-profile-avatar-editor', TNET_PROFILE_PLUGIN_URL . 'public/js/tnet-profile-avatar-editor.js', ['tnet-identity-avatar-photo'], is_readable($path) ? filemtime($path) : '1', true);
  }

  /** Render the shared avatar editor as the standalone page or an owner modal. */
  public static function render_modal($return_to) {
    if (!is_user_logged_in()) return;
    self::render_editor_surface(get_current_user_id(), $return_to, true);
  }

  public static function upload() {
    if (!is_user_logged_in()) wp_die(esc_html__('You are not allowed to change this avatar.', 'tnet-profile'), 403);
    check_admin_referer('tnet_profile_avatar_upload');
    $attachment_id = self::save_uploaded_avatar(get_current_user_id(), (array) ($_FILES['profile_avatar'] ?? []));
    if (is_wp_error($attachment_id)) self::redirect('invalid');
    self::redirect('updated');
  }

  private static function store_attachment($user_id, $attachment_id) {
    $user_id = absint($user_id);
    $old = absint(get_user_meta($user_id, self::META_KEY, true));
    update_user_meta($user_id, self::META_KEY, $attachment_id);
    delete_user_meta($user_id, self::PORTRAIT_META_KEY);
    if ($old && $old !== $attachment_id && self::owned_image($old, $user_id)) wp_delete_attachment($old, true);
  }

  public static function remove() {
    if (!is_user_logged_in()) wp_die(esc_html__('You are not allowed to change this avatar.', 'tnet-profile'), 403);
    check_admin_referer('tnet_profile_avatar_remove');
    $user_id = get_current_user_id();
    $old = absint(get_user_meta($user_id, self::META_KEY, true));
    delete_user_meta($user_id, self::META_KEY);
    delete_user_meta($user_id, self::PORTRAIT_META_KEY);
    if ($old && self::owned_image($old, $user_id)) wp_delete_attachment($old, true);
    self::redirect('removed');
  }

  private static function portrait_bank_manifest() {
    static $entries = null;
    if ($entries !== null) return $entries;
    $path = __DIR__ . '/assets/portrait-bank-v1/manifest.json';
    $raw = is_readable($path) ? file_get_contents($path) : false;
    $manifest = $raw ? json_decode($raw, true) : null;
    $entries = [];
    if (!is_array($manifest) || ($manifest['selection_state'] ?? '') !== 'FROZEN') return $entries;
    foreach ((array) ($manifest['entries'] ?? []) as $entry) {
      if (!is_array($entry) || ($entry['final_bank_state'] ?? '') !== 'RETAIN') continue;
      if (empty($entry['portrait_id']) || empty($entry['asset_path'])) continue;
      $entry['url'] = self::portrait_asset_url((string) $entry['asset_path']);
      if ($entry['url'] !== '') $entries[] = $entry;
    }
    return $entries;
  }

  private static function portrait_entry($portrait_id) {
    foreach (self::portrait_bank_manifest() as $entry) {
      if ((string) ($entry['portrait_id'] ?? '') === (string) $portrait_id) {
        $entry['url'] = self::portrait_asset_url((string) $entry['asset_path']);
        return $entry;
      }
    }
    return null;
  }

  private static function portrait_asset_url($asset_path) {
    $asset_path = ltrim(str_replace('\\', '/', $asset_path), '/');
    if ($asset_path === '' || strpos($asset_path, '..') !== false) return '';
    return esc_url_raw(TNET_PROFILE_PLUGIN_URL . 'assets/portrait-bank-v1/' . $asset_path);
  }

  public static function render_route() {
    $route = get_query_var('tnet_profile_route');
    if ($route === 'avatar_component_svg') TNet_Profile_Avatar_Component_Set::render_svg_response();
    if ($route === 'avatar_component_lab') TNet_Profile_Avatar_Component_Lab::render();
    if ($route !== 'avatar_editor') return;
    status_header(200);
    if (!is_user_logged_in()) auth_redirect();
    $return_to = self::editor_url();
    TNet_Shared_Shell::enqueue_assets('community');
    self::enqueue_editor_assets();
    TNet_Profile_Basics::enqueue_assets();
    ?><!doctype html><html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?php echo esc_html__('Profile Avatar | Teachers.Net', 'tnet-profile'); ?></title><?php wp_head(); ?></head><body><?php self::render_editor_surface(get_current_user_id(), $return_to, false); wp_footer(); ?></body></html><?php
    exit;
  }

  private static function render_editor_surface($user_id, $return_to, $modal) {
    $avatar = self::resolve_avatar($user_id, 128);
    $status = isset($_GET['avatar_status']) ? sanitize_key((string) wp_unslash($_GET['avatar_status'])) : '';
    $id = $modal ? 'profile-avatar-modal' : 'profile-avatar-page';
    $current_photo = ($avatar['source'] ?? '') === 'first-party' ? $avatar['url'] : '';
    ?>
    <?php if ($modal) : ?>
      <dialog id="<?php echo esc_attr($id); ?>" class="tnet-profile-avatar-dialog" data-profile-avatar-modal aria-label="<?php echo esc_attr__('Edit profile photo', 'tnet-profile'); ?>">
        <button class="tnet-profile-avatar-dialog-close" type="button" data-avatar-dialog-close aria-label="<?php echo esc_attr__('Close photo editor', 'tnet-profile'); ?>">×</button>
    <?php endif; ?>
      <main class="tnet-profile-avatar-dialog-content" data-profile-avatar-editor>
        <h2><?php echo esc_html__('Edit your profile photo', 'tnet-profile'); ?></h2>
        <?php if (!$modal) : ?><p><?php echo esc_html__('Your avatar belongs to your Teachers.Net user identity.', 'tnet-profile'); ?></p><?php endif; ?>
        <?php if ($status === 'updated') : ?><p role="status"><?php echo esc_html__('Avatar updated.', 'tnet-profile'); ?></p><?php elseif ($status === 'removed') : ?><p role="status"><?php echo esc_html__('Avatar removed.', 'tnet-profile'); ?></p><?php elseif ($status === 'invalid') : ?><p role="alert"><?php echo esc_html__('Choose a JPG, PNG, or WebP image between 64px and 2048px and no larger than 5 MB.', 'tnet-profile'); ?></p><?php endif; ?>
        <?php if (!$current_photo) : ?><img class="tnet-profile-avatar-current" src="<?php echo esc_url($avatar['url']); ?>" width="96" height="96" alt="<?php echo esc_attr__('Current profile avatar', 'tnet-profile'); ?>"><?php endif; ?>
        <form data-profile-avatar-upload method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data"><input type="hidden" name="action" value="tnet_profile_avatar_upload"><input type="hidden" name="return_to" value="<?php echo esc_attr($return_to); ?>"><?php wp_nonce_field('tnet_profile_avatar_upload'); ?>
          <?php if (class_exists('TNet_Identity_Public')) TNet_Identity_Public::render_avatar_photo_surface(['id' => $id . '-file', 'preview_url' => $current_photo, 'save_label' => 'Save photo', 'show_help' => true]); ?>
          <p data-profile-avatar-message role="alert" aria-live="polite"></p>
        </form>
        <?php if ($avatar['is_custom']) : ?><form class="tnet-profile-avatar-remove-form" data-profile-avatar-remove-form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"><input type="hidden" name="action" value="tnet_profile_avatar_remove"><input type="hidden" name="return_to" value="<?php echo esc_attr($return_to); ?>"><?php wp_nonce_field('tnet_profile_avatar_remove'); ?><button type="submit"><?php echo esc_html__('Remove photo', 'tnet-profile'); ?></button></form><?php endif; ?>
      </main>
      <?php if (class_exists('TNet_Identity_Public')) TNet_Identity_Public::render_avatar_crop_dialog($id . '-crop-title'); ?>
    <?php if ($modal) : ?></dialog><?php endif; ?>
    <?php
  }
}

TNet_Profile_Avatar::init();
TNet_Profile_Member_Context::init();
TNet_Profile_Basics::init();
TNet_Profile_Enrichment::init();
TNet_Profile_Public::init();
register_activation_hook(__FILE__, ['TNet_Profile_Avatar', 'activate']);
register_deactivation_hook(__FILE__, ['TNet_Profile_Avatar', 'deactivate']);

function tnet_profile_resolve_avatar($user_id, $size = 96) {
  return TNet_Profile_Avatar::resolve_avatar($user_id, $size);
}
