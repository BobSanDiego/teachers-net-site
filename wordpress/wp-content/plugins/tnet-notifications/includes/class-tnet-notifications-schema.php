<?php
defined('ABSPATH') || exit;

final class TNet_Notifications_Schema {
  public static function table_name() {
    global $wpdb;
    return $wpdb->prefix . 'tnet_notifications';
  }

  public static function installed_version() {
    $version = get_option(TNET_NOTIFICATIONS_SCHEMA_VERSION_OPTION, '');
    return $version ? (string) $version : null;
  }

  public static function target_version() { return TNET_NOTIFICATIONS_DB_VERSION; }

  public static function create_sql() {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();
    $table = self::table_name();
    return "CREATE TABLE {$table} (
      notification_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
      recipient_user_id BIGINT UNSIGNED NOT NULL,
      source_product VARCHAR(64) NOT NULL,
      event_id VARCHAR(191) NOT NULL,
      event_type VARCHAR(100) NOT NULL,
      payload_version SMALLINT UNSIGNED NOT NULL,
      actor_user_id BIGINT UNSIGNED NULL,
      object_type VARCHAR(64) NOT NULL,
      object_id VARCHAR(191) NOT NULL,
      destination_key VARCHAR(100) NOT NULL,
      destination_args_json LONGTEXT NOT NULL,
      metadata_json LONGTEXT NOT NULL,
      created_at DATETIME NOT NULL,
      read_at DATETIME NULL,
      active_state VARCHAR(16) NOT NULL DEFAULT 'active',
      archived_at DATETIME NULL,
      dedupe_key CHAR(64) NOT NULL,
      PRIMARY KEY  (notification_id),
      UNIQUE KEY recipient_source_dedupe (recipient_user_id, source_product, dedupe_key),
      KEY recipient_active_read_created (recipient_user_id, active_state, read_at, created_at),
      KEY recipient_active_source_created (recipient_user_id, active_state, source_product, created_at),
      KEY source_event (source_product, event_id),
      KEY created_notification (created_at, notification_id)
    ) {$charset};";
  }

  public static function install() {
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta(self::create_sql());
    $assertion = self::assert_schema();
    if (is_wp_error($assertion)) {
      update_option(TNET_NOTIFICATIONS_SCHEMA_LAST_ERROR_OPTION, $assertion->get_error_data(), false);
      return $assertion;
    }
    update_option(TNET_NOTIFICATIONS_SCHEMA_VERSION_OPTION, self::target_version(), false);
    delete_option(TNET_NOTIFICATIONS_SCHEMA_LAST_ERROR_OPTION);
    return ['created' => 1, 'version' => self::target_version(), 'table' => self::table_name()];
  }

  public static function assert_schema() {
    global $wpdb;
    $table = self::table_name();
    $exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table));
    if ($exists !== $table) return new WP_Error('tnet_notifications_schema_missing', 'Notifications table is missing.', ['table' => $table]);
    $columns = $wpdb->get_col("SHOW COLUMNS FROM {$table}", 0);
    $required = ['notification_id','recipient_user_id','source_product','event_id','event_type','payload_version','actor_user_id','object_type','object_id','destination_key','destination_args_json','metadata_json','created_at','read_at','active_state','archived_at','dedupe_key'];
    $missing = array_values(array_diff($required, $columns));
    $indexes = $wpdb->get_results("SHOW INDEX FROM {$table}", ARRAY_A);
    $names = array_values(array_unique(array_map(static function ($row) { return (string) $row['Key_name']; }, $indexes)));
    $required_indexes = ['PRIMARY','recipient_source_dedupe','recipient_active_read_created','recipient_active_source_created','source_event'];
    $missing_indexes = array_values(array_diff($required_indexes, $names));
    if ($missing || $missing_indexes) return new WP_Error('tnet_notifications_schema_incomplete', 'Notifications schema assertions failed.', ['missing_columns' => $missing, 'missing_indexes' => $missing_indexes]);
    return true;
  }
}
