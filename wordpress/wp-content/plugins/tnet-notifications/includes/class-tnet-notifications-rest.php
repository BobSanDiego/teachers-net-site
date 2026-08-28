<?php
defined('ABSPATH') || exit;

final class TNet_Notifications_REST {
  private static $service;
  public static function init($service) { self::$service = $service; add_action('rest_api_init', [__CLASS__, 'register_routes']); }
  public static function register_routes() {
    register_rest_route('tnet-notifications/v1', '/unread-count', ['methods' => WP_REST_Server::READABLE, 'callback' => [__CLASS__, 'unread_count'], 'permission_callback' => [__CLASS__, 'authenticated']]);
    register_rest_route('tnet-notifications/v1', '/notifications', ['methods' => WP_REST_Server::READABLE, 'callback' => [__CLASS__, 'list_notifications'], 'permission_callback' => [__CLASS__, 'authenticated'], 'args' => ['source' => ['sanitize_callback' => 'sanitize_key'], 'limit' => ['default' => 25, 'validate_callback' => static function ($value) { return is_numeric($value) && (int) $value >= 1 && (int) $value <= 100; }], 'after_created_at' => ['sanitize_callback' => 'sanitize_text_field'], 'after_id' => ['sanitize_callback' => 'absint']]]);
    register_rest_route('tnet-notifications/v1', '/notifications/(?P<id>\d+)/read', ['methods' => WP_REST_Server::CREATABLE, 'callback' => [__CLASS__, 'mark_read'], 'permission_callback' => [__CLASS__, 'authenticated']]);
    register_rest_route('tnet-notifications/v1', '/notifications/read-all', ['methods' => WP_REST_Server::CREATABLE, 'callback' => [__CLASS__, 'mark_all_read'], 'permission_callback' => [__CLASS__, 'authenticated'], 'args' => ['source' => ['sanitize_callback' => 'sanitize_key']]]);
  }
  public static function authenticated() { return is_user_logged_in() ? true : new WP_Error('tnet_notifications_auth_required', 'Authentication is required.', ['status' => 401]); }
  private static function user_id() { return get_current_user_id(); }
  public static function unread_count() { return rest_ensure_response(['unread_count' => self::$service->unread_count(self::user_id())]); }
  public static function list_notifications(WP_REST_Request $request) {
    $result = self::$service->list_for_recipient(self::user_id(), (string) $request->get_param('source'), (int) $request->get_param('limit'), (string) $request->get_param('after_created_at'), (int) $request->get_param('after_id'));
    return is_wp_error($result) ? $result : rest_ensure_response(['items' => $result]);
  }
  public static function mark_read(WP_REST_Request $request) {
    $result = self::$service->mark_read(self::user_id(), (int) $request['id']);
    return is_wp_error($result) ? $result : rest_ensure_response(['marked' => (bool) $result]);
  }
  public static function mark_all_read(WP_REST_Request $request) {
    $result = self::$service->mark_all_read(self::user_id(), (string) $request->get_param('source'));
    return is_wp_error($result) ? $result : rest_ensure_response(['marked_count' => (int) $result]);
  }
}
