<?php
defined('ABSPATH') || exit;

final class TNet_Notifications_Repository {
  private $table;
  public function __construct() { $this->table = TNet_Notifications_Schema::table_name(); }

  public function insert(array $row) {
    global $wpdb;
    $suppress = $wpdb->suppress_errors(true);
    $result = $wpdb->insert($this->table, $row, ['%d','%s','%s','%s','%d','%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s']);
    $last_error = $wpdb->last_error;
    $wpdb->suppress_errors($suppress);
    if ($result !== false) return (int) $wpdb->insert_id;
    if (strpos(strtolower((string) $last_error), 'duplicate') !== false) {
      $existing = $this->find_by_dedupe($row['recipient_user_id'], $row['source_product'], $row['dedupe_key']);
      return $existing ? (int) $existing['notification_id'] : new WP_Error('tnet_notifications_insert_race', 'Notification insert conflicted but the existing record could not be read.');
    }
    return new WP_Error('tnet_notifications_insert_failed', 'Failed to insert notification.', ['last_error' => $last_error]);
  }

  public function find_by_dedupe($recipient_user_id, $source_product, $dedupe_key) {
    global $wpdb;
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table} WHERE recipient_user_id = %d AND source_product = %s AND dedupe_key = %s LIMIT 1", absint($recipient_user_id), sanitize_key($source_product), (string) $dedupe_key), ARRAY_A) ?: null;
  }

  public function unread_count($recipient_user_id) {
    global $wpdb;
    return (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$this->table} WHERE recipient_user_id = %d AND active_state = 'active' AND read_at IS NULL", absint($recipient_user_id)));
  }

  public function list($recipient_user_id, $source_product = '', $limit = 25, $after_created_at = '', $after_id = 0) {
    global $wpdb;
    $where = "recipient_user_id = %d AND active_state = 'active'";
    $values = [absint($recipient_user_id)];
    if ($source_product !== '') { $where .= ' AND source_product = %s'; $values[] = sanitize_key($source_product); }
    if ($after_created_at !== '' && $after_id) { $where .= ' AND (created_at < %s OR (created_at = %s AND notification_id < %d))'; $values[] = $after_created_at; $values[] = $after_created_at; $values[] = absint($after_id); }
    $limit = max(1, min(100, absint($limit) ?: 25));
    $values[] = $limit;
    return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->table} WHERE {$where} ORDER BY created_at DESC, notification_id DESC LIMIT %d", $values), ARRAY_A);
  }

  public function mark_read($recipient_user_id, $notification_id) {
    global $wpdb;
    $result = $wpdb->query($wpdb->prepare("UPDATE {$this->table} SET read_at = COALESCE(read_at, %s) WHERE notification_id = %d AND recipient_user_id = %d AND active_state = 'active'", current_time('mysql', true), absint($notification_id), absint($recipient_user_id)));
    return $result !== false && (int) $result > 0;
  }

  public function mark_all_read($recipient_user_id, $source_product = '') {
    global $wpdb;
    $where = "recipient_user_id = %d AND active_state = 'active' AND read_at IS NULL";
    $values = [current_time('mysql', true), absint($recipient_user_id)];
    if ($source_product !== '') { $where .= ' AND source_product = %s'; $values[] = sanitize_key($source_product); }
    $result = $wpdb->query($wpdb->prepare("UPDATE {$this->table} SET read_at = %s WHERE {$where}", $values));
    return $result === false ? new WP_Error('tnet_notifications_mark_all_failed', 'Failed to mark notifications read.') : (int) $result;
  }

  public function archive_event($source_product, $event_id, $state = 'archived') {
    global $wpdb;
    $state = in_array($state, ['archived','retracted'], true) ? $state : 'archived';
    return $wpdb->query($wpdb->prepare("UPDATE {$this->table} SET active_state = %s, archived_at = COALESCE(archived_at, %s) WHERE source_product = %s AND event_id = %s", $state, current_time('mysql', true), sanitize_key($source_product), (string) $event_id));
  }
}
