<?php
/**
 * Plugin Name: Teachers.Net Identity
 * Description: Universal Teachers.Net account, verification, and continuation foundation.
 * Version: 0.3.0
 * Author: Teachers.Net
 * Text Domain: tnet-identity
 */

defined('ABSPATH') || exit;

define('TNET_IDENTITY_VERSION', '0.3.0');
define('TNET_IDENTITY_DB_VERSION', '0.2.0');
define('TNET_IDENTITY_ROUTE_VERSION', '0.3.0');
define('TNET_IDENTITY_PLUGIN_FILE', __FILE__);
define('TNET_IDENTITY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TNET_IDENTITY_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once TNET_IDENTITY_PLUGIN_DIR . 'includes/class-tnet-identity-policy.php';
require_once TNET_IDENTITY_PLUGIN_DIR . 'includes/class-tnet-identity-username-policy.php';
require_once TNET_IDENTITY_PLUGIN_DIR . 'includes/class-tnet-identity-display-name-policy.php';
require_once TNET_IDENTITY_PLUGIN_DIR . 'includes/class-tnet-identity-service.php';
if (defined('WP_CLI') && WP_CLI) {
  require_once TNET_IDENTITY_PLUGIN_DIR . 'includes/class-tnet-identity-cli.php';
  WP_CLI::add_command('tnet identity', 'TNet_Identity_CLI');
}
require_once TNET_IDENTITY_PLUGIN_DIR . 'public/class-tnet-identity-public.php';

TNet_Identity::init();
register_activation_hook(__FILE__, ['TNet_Identity', 'activate']);
register_deactivation_hook(__FILE__, ['TNet_Identity', 'deactivate']);

function tnet_identity_signup_url($continuation_token = '') {
  return TNet_Identity_Public::signup_url($continuation_token);
}

function tnet_identity_resume_url($continuation_token) {
  return TNet_Identity_Public::resume_url($continuation_token);
}

function tnet_identity_route_key_for_url($url) {
  return TNet_Identity_Continuation_Service::route_key_for_url($url);
}

function tnet_identity_auth_gate_urls($purpose, $destination_url, array $context = []) {
  return TNet_Identity_Continuation_Service::auth_gate_urls($purpose, $destination_url, $context);
}

function tnet_identity_is_user_email_verified($user_id) {
  return TNet_Identity_Service::is_email_verified($user_id);
}

final class TNet_Identity {
  const QUERY_VAR = 'tnet_identity_route';

  public static function init() {
    add_action('init', [__CLASS__, 'maybe_upgrade'], 1);
    add_action('init', [__CLASS__, 'register_routes']);
    add_filter('query_vars', [__CLASS__, 'query_vars']);
    add_action('template_redirect', [__CLASS__, 'render_route']);
  }

  public static function activate() {
    self::install_schema();
    self::register_routes();
    flush_rewrite_rules(false);
    update_option('tnet_identity_route_version', TNET_IDENTITY_ROUTE_VERSION, false);
  }

  public static function deactivate() {
    flush_rewrite_rules(false);
  }

  public static function maybe_upgrade() {
    if (get_option('tnet_identity_schema_version') !== TNET_IDENTITY_DB_VERSION) {
      self::install_schema();
    }
    if (get_option('tnet_identity_route_version') !== TNET_IDENTITY_ROUTE_VERSION) {
      self::register_routes();
      flush_rewrite_rules(false);
      update_option('tnet_identity_route_version', TNET_IDENTITY_ROUTE_VERSION, false);
    }
  }

  public static function register_routes() {
    add_rewrite_rule('^account/sign-up/?$', 'index.php?' . self::QUERY_VAR . '=signup', 'top');
    add_rewrite_rule('^account/verify/?$', 'index.php?' . self::QUERY_VAR . '=verify', 'top');
    add_rewrite_rule('^account/avatar/?$', 'index.php?' . self::QUERY_VAR . '=avatar', 'top');
    add_rewrite_rule('^account/identity/?$', 'index.php?' . self::QUERY_VAR . '=identity', 'top');
    add_rewrite_rule('^account/continue/?$', 'index.php?' . self::QUERY_VAR . '=continue', 'top');
  }

  public static function query_vars($vars) {
    $vars[] = self::QUERY_VAR;
    return $vars;
  }

  public static function table($name) {
    global $wpdb;
    $tables = [
      'verification' => $wpdb->prefix . 'tnet_identity_email_verifications',
      'continuation' => $wpdb->prefix . 'tnet_identity_continuations',
    ];
    return $tables[$name] ?? '';
  }

  public static function install_schema() {
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $charset = $wpdb->get_charset_collate();
    $verification = self::table('verification');
    $continuation = self::table('continuation');
    dbDelta("CREATE TABLE {$verification} (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      user_id bigint(20) unsigned NOT NULL,
      token_hash char(64) NOT NULL,
      purpose varchar(40) NOT NULL DEFAULT 'account',
      issued_at datetime NOT NULL,
      expires_at datetime NOT NULL,
      used_at datetime NULL,
      confirmation_code_hash char(64) NULL,
      code_expires_at datetime NULL,
      PRIMARY KEY  (id),
      UNIQUE KEY token_hash (token_hash),
      KEY user_state (user_id, used_at, expires_at)
    ) {$charset};");
    dbDelta("CREATE TABLE {$continuation} (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      user_id bigint(20) unsigned NULL,
      purpose varchar(50) NOT NULL,
      action varchar(80) NOT NULL,
      route_key varchar(80) NOT NULL,
      context_json text NOT NULL,
      token_hash char(64) NOT NULL,
      issued_at datetime NOT NULL,
      expires_at datetime NOT NULL,
      consumed_at datetime NULL,
      PRIMARY KEY  (id),
      UNIQUE KEY token_hash (token_hash),
      KEY continuation_state (user_id, consumed_at, expires_at),
      KEY route_key (route_key)
    ) {$charset};");
    update_option('tnet_identity_schema_version', TNET_IDENTITY_DB_VERSION, false);
  }

  public static function render_route() {
    $route = get_query_var(self::QUERY_VAR);
    if ($route === 'signup') {
      TNet_Identity_Public::render_signup();
    } elseif ($route === 'verify') {
      TNet_Identity_Public::render_verify();
    } elseif ($route === 'avatar') {
      TNet_Identity_Public::render_avatar();
    } elseif ($route === 'identity') {
      TNet_Identity_Public::render_public_identity();
    } elseif ($route === 'continue') {
      TNet_Identity_Public::render_continue();
    }
  }
}
