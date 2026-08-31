<?php
/**
 * Plugin Name: Teachers.Net Notifications
 * Description: Shared authenticated notification persistence and consumer API.
 * Version: 0.1.0
 * Author: Teachers.Net
 * Text Domain: tnet-notifications
 */

defined('ABSPATH') || exit;

define('TNET_NOTIFICATIONS_VERSION', '0.1.0');
define('TNET_NOTIFICATIONS_DB_VERSION', '1.0.0');
define('TNET_NOTIFICATIONS_SCHEMA_VERSION_OPTION', 'tnet_notifications_schema_version');
define('TNET_NOTIFICATIONS_SCHEMA_LAST_ERROR_OPTION', 'tnet_notifications_schema_last_error');
define('TNET_NOTIFICATIONS_PLUGIN_FILE', __FILE__);
define('TNET_NOTIFICATIONS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TNET_NOTIFICATIONS_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once TNET_NOTIFICATIONS_PLUGIN_DIR . 'includes/class-tnet-notifications-schema.php';
require_once TNET_NOTIFICATIONS_PLUGIN_DIR . 'includes/class-tnet-notifications-registry.php';
require_once TNET_NOTIFICATIONS_PLUGIN_DIR . 'includes/repositories/class-tnet-notifications-repository.php';
require_once TNET_NOTIFICATIONS_PLUGIN_DIR . 'includes/class-tnet-notifications-service.php';
require_once TNET_NOTIFICATIONS_PLUGIN_DIR . 'includes/class-tnet-notifications-rest.php';

final class TNet_Notifications {
  private static $service;

  public static function init() {
    self::$service = new TNet_Notifications_Service();
    TNet_Notifications_REST::init(self::$service);
  }

  public static function service() {
    if (!self::$service) self::$service = new TNet_Notifications_Service();
    return self::$service;
  }

  public static function activate() {
    $result = TNet_Notifications_Schema::install();
    if (is_wp_error($result)) wp_die(esc_html($result->get_error_message()));
  }

  public static function enqueue_client_assets() {
    wp_enqueue_script('tnet-notifications-icons', TNET_NOTIFICATIONS_PLUGIN_URL . 'public/js/tnet-notifications-icons.js', [], (string) filemtime(TNET_NOTIFICATIONS_PLUGIN_DIR . 'public/js/tnet-notifications-icons.js'), true);
    wp_enqueue_script('tnet-notifications-runtime', TNET_NOTIFICATIONS_PLUGIN_URL . 'public/js/tnet-notifications-runtime.js', [], (string) filemtime(TNET_NOTIFICATIONS_PLUGIN_DIR . 'public/js/tnet-notifications-runtime.js'), true);
    wp_localize_script('tnet-notifications-runtime', 'TNetNotificationsRuntimeConfig', ['root' => trailingslashit(rest_url('tnet-notifications/v1')), 'nonce' => wp_create_nonce('wp_rest')]);
  }

  public static function deactivate() {
    // Deactivation intentionally preserves recipient state and source facts.
  }
}

register_activation_hook(__FILE__, ['TNet_Notifications', 'activate']);
register_deactivation_hook(__FILE__, ['TNet_Notifications', 'deactivate']);
add_action('plugins_loaded', ['TNet_Notifications', 'init'], 20);

function tnet_notifications() {
  return TNet_Notifications::service();
}
