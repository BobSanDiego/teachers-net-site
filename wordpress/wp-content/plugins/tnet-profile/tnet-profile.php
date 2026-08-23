<?php
/**
 * Plugin Name: Teachers.Net Profile
 * Description: Bounded first-party user profile capabilities.
 * Version: 0.1.0
 */

defined('ABSPATH') || exit;

final class TNet_Profile_Avatar {
  const META_KEY = '_tnet_profile_avatar_id';
  const ROUTE = 'profile';
  const MAX_BYTES = 5242880;

  public static function init() {
    add_action('init', [__CLASS__, 'register_route']);
    add_filter('query_vars', [__CLASS__, 'query_vars']);
    add_action('template_redirect', [__CLASS__, 'render_route']);
    add_action('admin_post_tnet_profile_avatar_upload', [__CLASS__, 'upload']);
    add_action('admin_post_tnet_profile_avatar_remove', [__CLASS__, 'remove']);
  }

  public static function activate() {
    self::register_route();
    flush_rewrite_rules(false);
  }

  public static function deactivate() { flush_rewrite_rules(false); }

  public static function register_route() {
    add_rewrite_rule('^profile/?$', 'index.php?tnet_profile_route=avatar', 'top');
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
    $fallback = get_avatar_data($user_id, ['size' => $size, 'default' => 'identicon']);
    return ['url' => esc_url_raw($fallback['url']), 'source' => 'wordpress-fallback', 'is_custom' => false];
  }

  private static function owned_image($attachment_id, $user_id) {
    $post = get_post($attachment_id);
    return $post && $post->post_type === 'attachment' && (int) $post->post_author === $user_id && strpos((string) $post->post_mime_type, 'image/') === 0;
  }

  private static function redirect($status) {
    wp_safe_redirect(add_query_arg('avatar_status', sanitize_key($status), home_url('/profile/')));
    exit;
  }

  public static function upload() {
    if (!is_user_logged_in() || !current_user_can('upload_files')) wp_die(esc_html__('You are not allowed to change this avatar.', 'tnet-profile'), 403);
    check_admin_referer('tnet_profile_avatar_upload');
    if (empty($_FILES['profile_avatar']['name']) || !empty($_FILES['profile_avatar']['error'])) self::redirect('invalid');
    $file = $_FILES['profile_avatar'];
    if ((int) $file['size'] > self::MAX_BYTES) self::redirect('invalid');
    $check = wp_check_filetype_and_ext($file['tmp_name'], $file['name']);
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (empty($check['type']) || !in_array(strtolower((string) $check['ext']), $allowed, true)) self::redirect('invalid');
    $dimensions = @getimagesize($file['tmp_name']);
    if (!$dimensions || $dimensions[0] < 64 || $dimensions[1] < 64 || $dimensions[0] > 2048 || $dimensions[1] > 2048) self::redirect('invalid');
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $attachment_id = media_handle_upload('profile_avatar', 0, ['post_title' => sanitize_text_field(pathinfo($file['name'], PATHINFO_FILENAME))], ['test_form' => false]);
    if (is_wp_error($attachment_id)) self::redirect('invalid');
    $user_id = get_current_user_id();
    $old = absint(get_user_meta($user_id, self::META_KEY, true));
    update_user_meta($user_id, self::META_KEY, $attachment_id);
    if ($old && $old !== $attachment_id && self::owned_image($old, $user_id)) wp_delete_attachment($old, true);
    self::redirect('updated');
  }

  public static function remove() {
    if (!is_user_logged_in()) wp_die(esc_html__('You are not allowed to change this avatar.', 'tnet-profile'), 403);
    check_admin_referer('tnet_profile_avatar_remove');
    $user_id = get_current_user_id();
    $old = absint(get_user_meta($user_id, self::META_KEY, true));
    delete_user_meta($user_id, self::META_KEY);
    if ($old && self::owned_image($old, $user_id)) wp_delete_attachment($old, true);
    self::redirect('removed');
  }

  public static function render_route() {
    if (get_query_var('tnet_profile_route') !== 'avatar') return;
    status_header(200);
    if (!is_user_logged_in()) { auth_redirect(); }
    $avatar = self::resolve_avatar(get_current_user_id(), 128);
    $status = isset($_GET['avatar_status']) ? sanitize_key((string) wp_unslash($_GET['avatar_status'])) : '';
    ?><!doctype html><html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?php echo esc_html__('Profile Avatar | Teachers.Net', 'tnet-profile'); ?></title><?php wp_head(); ?></head><body><main style="max-width:560px;margin:48px auto;padding:32px;font:16px/1.5 system-ui,sans-serif"><h1><?php echo esc_html__('Profile avatar', 'tnet-profile'); ?></h1><p><?php echo esc_html__('Your avatar belongs to your Teachers.Net user identity.', 'tnet-profile'); ?></p><p><img src="<?php echo esc_url($avatar['url']); ?>" width="128" height="128" alt="" style="border-radius:50%;object-fit:cover"></p><?php if ($status === 'updated') : ?><p role="status"><?php echo esc_html__('Avatar updated.', 'tnet-profile'); ?></p><?php elseif ($status === 'removed') : ?><p role="status"><?php echo esc_html__('Avatar removed.', 'tnet-profile'); ?></p><?php elseif ($status === 'invalid') : ?><p role="alert"><?php echo esc_html__('Choose a JPG, PNG, or WebP image between 64px and 2048px and no larger than 5 MB.', 'tnet-profile'); ?></p><?php endif; ?><form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data"><input type="hidden" name="action" value="tnet_profile_avatar_upload"><?php wp_nonce_field('tnet_profile_avatar_upload'); ?><label><?php echo esc_html__('Choose a replacement image', 'tnet-profile'); ?><input type="file" name="profile_avatar" accept="image/jpeg,image/png,image/webp" required></label><p><button type="submit"><?php echo esc_html__('Upload avatar', 'tnet-profile'); ?></button></p></form><?php if ($avatar['is_custom']) : ?><form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"><input type="hidden" name="action" value="tnet_profile_avatar_remove"><?php wp_nonce_field('tnet_profile_avatar_remove'); ?><button type="submit"><?php echo esc_html__('Remove avatar', 'tnet-profile'); ?></button></form><?php endif; ?></main><?php wp_footer(); ?></body></html><?php exit;
  }
}

TNet_Profile_Avatar::init();
register_activation_hook(__FILE__, ['TNet_Profile_Avatar', 'activate']);
register_deactivation_hook(__FILE__, ['TNet_Profile_Avatar', 'deactivate']);

function tnet_profile_resolve_avatar($user_id, $size = 96) {
  return TNet_Profile_Avatar::resolve_avatar($user_id, $size);
}
