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
    flush_rewrite_rules(false);
  }

  public static function deactivate() { flush_rewrite_rules(false); }

  public static function register_route() {
    add_rewrite_rule('^profile/?$', 'index.php?tnet_profile_route=avatar', 'top');
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
    wp_safe_redirect(add_query_arg('avatar_status', sanitize_key($status), home_url('/profile/')));
    exit;
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
    if ($route !== 'avatar') return;
    status_header(200);
    if (!is_user_logged_in()) { auth_redirect(); }
    $avatar = self::resolve_avatar(get_current_user_id(), 128);
    $status = isset($_GET['avatar_status']) ? sanitize_key((string) wp_unslash($_GET['avatar_status'])) : '';
    ?><!doctype html><html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?php echo esc_html__('Profile Avatar | Teachers.Net', 'tnet-profile'); ?></title><?php wp_head(); ?><style>
      .tnet-avatar-page{box-sizing:border-box;max-width:560px;margin:48px auto;padding:32px;font:16px/1.5 system-ui,sans-serif;color:#122875}.tnet-avatar-page *{box-sizing:border-box}.tnet-avatar-current{border-radius:50%;object-fit:cover;display:block}.tnet-avatar-crop{display:none;margin:24px 0;padding:20px;border:1px solid #dbe4f0;border-radius:12px;background:#f7f8fa}.tnet-avatar-crop.is-open{display:block}.tnet-avatar-crop-stage{position:relative;width:min(100%,360px);aspect-ratio:1;margin:auto;overflow:hidden;background:#122875;border-radius:8px;touch-action:none;cursor:grab}.tnet-avatar-crop-stage.is-dragging{cursor:grabbing}.tnet-avatar-crop-stage canvas{display:block;width:100%;height:100%}.tnet-avatar-controls{display:grid;gap:12px;margin-top:16px}.tnet-avatar-controls label{display:grid;gap:4px}.tnet-avatar-controls input[type=range]{width:100%}.tnet-avatar-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}.tnet-avatar-actions button{font:inherit;padding:8px 14px;border:1px solid #b8c7dd;border-radius:6px;background:#fff;color:#122875;cursor:pointer}.tnet-avatar-actions .tnet-avatar-confirm{background:#123bc6;border-color:#123bc6;color:#fff}.tnet-avatar-message{min-height:1.5em;color:#8a1f11}.tnet-avatar-help{font-size:.92rem;color:#40536f}.tnet-avatar-remove{margin-top:16px}.tnet-avatar-file{display:grid;gap:8px}.tnet-avatar-file button{width:max-content}.tnet-avatar-visually-hidden{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
    </style></head><body><main class="tnet-avatar-page"><h1><?php echo esc_html__('Profile avatar', 'tnet-profile'); ?></h1><p><?php echo esc_html__('Your avatar belongs to your Teachers.Net user identity.', 'tnet-profile'); ?></p><p><img class="tnet-avatar-current" src="<?php echo esc_url($avatar['url']); ?>" width="128" height="128" alt="<?php echo esc_attr__('Current avatar', 'tnet-profile'); ?>"></p><?php if ($status === 'updated') : ?><p role="status"><?php echo esc_html__('Avatar updated.', 'tnet-profile'); ?></p><?php elseif ($status === 'removed') : ?><p role="status"><?php echo esc_html__('Avatar removed.', 'tnet-profile'); ?></p><?php elseif ($status === 'invalid') : ?><p role="alert"><?php echo esc_html__('Choose a JPG, PNG, or WebP image between 64px and 2048px and no larger than 5 MB.', 'tnet-profile'); ?></p><?php endif; ?><form id="tnet-avatar-upload" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data"><input type="hidden" name="action" value="tnet_profile_avatar_upload"><?php wp_nonce_field('tnet_profile_avatar_upload'); ?><label class="tnet-avatar-file"><?php echo esc_html__('Choose a replacement image', 'tnet-profile'); ?><input id="tnet-avatar-file" type="file" name="profile_avatar" accept="image/jpeg,image/png,image/webp" required></label><section id="tnet-avatar-crop" class="tnet-avatar-crop" aria-labelledby="tnet-avatar-crop-title"><h2 id="tnet-avatar-crop-title"><?php echo esc_html__('Frame your avatar', 'tnet-profile'); ?></h2><p class="tnet-avatar-help"><?php echo esc_html__('Drag the image to reposition it, then adjust zoom. The square inside the frame is what will be saved.', 'tnet-profile'); ?></p><div id="tnet-avatar-stage" class="tnet-avatar-crop-stage" tabindex="0" role="img" aria-label="Avatar crop preview"><canvas id="tnet-avatar-canvas" width="512" height="512"></canvas></div><div class="tnet-avatar-controls"><label for="tnet-avatar-zoom"><?php echo esc_html__('Zoom', 'tnet-profile'); ?><input id="tnet-avatar-zoom" type="range" min="1" max="3" step="0.01" value="1"></label></div><p id="tnet-avatar-message" class="tnet-avatar-message" role="alert" aria-live="polite"></p><div class="tnet-avatar-actions"><button id="tnet-avatar-confirm" class="tnet-avatar-confirm" type="button"><?php echo esc_html__('Save cropped avatar', 'tnet-profile'); ?></button><button id="tnet-avatar-cancel" type="button"><?php echo esc_html__('Cancel', 'tnet-profile'); ?></button></div></section></form><?php if ($avatar['is_custom']) : ?><form class="tnet-avatar-remove" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"><input type="hidden" name="action" value="tnet_profile_avatar_remove"><?php wp_nonce_field('tnet_profile_avatar_remove'); ?><button type="submit"><?php echo esc_html__('Remove avatar', 'tnet-profile'); ?></button></form><?php endif; ?></main><script>
      (() => { const file = document.getElementById('tnet-avatar-file'), form = document.getElementById('tnet-avatar-upload'), crop = document.getElementById('tnet-avatar-crop'), stage = document.getElementById('tnet-avatar-stage'), canvas = document.getElementById('tnet-avatar-canvas'), zoom = document.getElementById('tnet-avatar-zoom'), cancel = document.getElementById('tnet-avatar-cancel'), message = document.getElementById('tnet-avatar-message'), ctx = canvas.getContext('2d'); let image = null, objectUrl = '', scale = 1, x = 0, y = 0, drag = null;
        const clamp = (value, min, max) => Math.max(min, Math.min(max, value));
        const bounds = () => { const side = Math.max(image.naturalWidth, image.naturalHeight), base = 512 / Math.min(image.naturalWidth, image.naturalHeight), width = image.naturalWidth * base * scale, height = image.naturalHeight * base * scale; return { width, height, minX: 512 - width, minY: 512 - height }; };
        const draw = () => { if (!image) return; const b = bounds(); x = clamp(x, b.minX, 0); y = clamp(y, b.minY, 0); ctx.clearRect(0, 0, 512, 512); ctx.fillStyle = '#122875'; ctx.fillRect(0, 0, 512, 512); ctx.drawImage(image, x, y, b.width, b.height); };
        const point = event => { const rect = stage.getBoundingClientRect(); return { x: event.clientX - rect.left, y: event.clientY - rect.top }; };
        const stopDrag = () => { drag = null; stage.classList.remove('is-dragging'); };
        file.addEventListener('change', () => { const selected = file.files && file.files[0]; if (!selected) return; if (!/^image\/(jpeg|png|webp)$/.test(selected.type) || selected.size > 5242880) { message.textContent = 'Choose a JPG, PNG, or WebP image no larger than 5 MB.'; file.value = ''; crop.classList.remove('is-open'); return; } objectUrl = URL.createObjectURL(selected); image = new Image(); image.onload = () => { if (image.naturalWidth < 64 || image.naturalHeight < 64 || image.naturalWidth > 2048 || image.naturalHeight > 2048) { message.textContent = 'Image dimensions must be between 64px and 2048px.'; file.value = ''; crop.classList.remove('is-open'); URL.revokeObjectURL(objectUrl); return; } scale = 1; zoom.value = '1'; const b = bounds(); x = (512 - b.width) / 2; y = (512 - b.height) / 2; crop.classList.add('is-open'); message.textContent = ''; draw(); stage.focus(); }; image.onerror = () => { message.textContent = 'The selected image could not be read.'; file.value = ''; crop.classList.remove('is-open'); }; image.src = objectUrl; });
        zoom.addEventListener('input', () => { if (!image) return; const before = bounds(); const centerX = (256 - x) / before.width, centerY = (256 - y) / before.height; scale = Number(zoom.value); const after = bounds(); x = 256 - centerX * after.width; y = 256 - centerY * after.height; draw(); });
        stage.addEventListener('pointerdown', event => { if (!image) return; stage.setPointerCapture(event.pointerId); drag = { pointerId: event.pointerId, start: point(event), x, y }; stage.classList.add('is-dragging'); event.preventDefault(); });
        stage.addEventListener('pointermove', event => { if (!drag || drag.pointerId !== event.pointerId) return; const p = point(event); x = drag.x + p.x - drag.start.x; y = drag.y + p.y - drag.start.y; draw(); event.preventDefault(); });
        stage.addEventListener('pointerup', stopDrag); stage.addEventListener('pointercancel', stopDrag); stage.addEventListener('keydown', event => { if (!image) return; const amount = event.shiftKey ? 20 : 5; if (event.key === 'ArrowLeft') x -= amount; else if (event.key === 'ArrowRight') x += amount; else if (event.key === 'ArrowUp') y -= amount; else if (event.key === 'ArrowDown') y += amount; else return; draw(); event.preventDefault(); });
        cancel.addEventListener('click', () => { if (objectUrl) URL.revokeObjectURL(objectUrl); image = null; objectUrl = ''; file.value = ''; crop.classList.remove('is-open'); message.textContent = ''; });
        form.noValidate = true; document.getElementById('tnet-avatar-confirm').addEventListener('click', event => { if (!image) return; event.preventDefault(); canvas.toBlob(async blob => { if (!blob) { message.textContent = 'The cropped image could not be prepared.'; return; } const data = new FormData(form); data.set('profile_avatar', blob, 'profile-avatar.jpg'); try { const response = await fetch(form.getAttribute("action"), { method: 'POST', body: data, credentials: 'same-origin' }); window.location.assign(response.url); } catch (error) { message.textContent = 'The cropped image could not be saved. Please try again.'; } }, 'image/jpeg', 0.9); });
      })();
    </script><?php wp_footer(); ?></body></html><?php exit;
  }
}

TNet_Profile_Avatar::init();
TNet_Profile_Member_Context::init();
TNet_Profile_Basics::init();
register_activation_hook(__FILE__, ['TNet_Profile_Avatar', 'activate']);
register_deactivation_hook(__FILE__, ['TNet_Profile_Avatar', 'deactivate']);

function tnet_profile_resolve_avatar($user_id, $size = 96) {
  return TNet_Profile_Avatar::resolve_avatar($user_id, $size);
}
