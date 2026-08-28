<?php
defined('ABSPATH') || exit;

final class TNet_Notifications_Service {
  private $repository;
  public function __construct(?TNet_Notifications_Repository $repository = null) { $this->repository = $repository ?: new TNet_Notifications_Repository(); }

  public function create_for_recipients(array $event, array $recipient_ids) {
    $validated = TNet_Notifications_Registry::validate_event($event);
    if (is_wp_error($validated)) return $validated;
    $created_at = !empty($validated['created_at']) ? sanitize_text_field((string) $validated['created_at']) : current_time('mysql', true);
    $ids = array_values(array_unique(array_filter(array_map('absint', $recipient_ids))));
    if (!$ids) return new WP_Error('tnet_notifications_recipient_invalid', 'At least one recipient is required.');
    $results = [];
    foreach ($ids as $recipient_id) {
      $row = [
        'recipient_user_id' => $recipient_id,
        'source_product' => $validated['source_product'],
        'event_id' => (string) $validated['event_id'],
        'event_type' => $validated['event_type'],
        'payload_version' => $validated['payload_version'],
        'actor_user_id' => !empty($validated['actor_user_id']) ? absint($validated['actor_user_id']) : null,
        'object_type' => sanitize_key((string) $validated['object_type']),
        'object_id' => sanitize_text_field((string) $validated['object_id']),
        'destination_key' => $validated['destination_key'],
        'destination_args_json' => wp_json_encode($validated['destination_args']),
        'metadata_json' => wp_json_encode($validated['metadata']),
        'created_at' => $created_at,
        'read_at' => null,
        'active_state' => 'active',
        'archived_at' => null,
        'dedupe_key' => hash('sha256', (string) $validated['dedupe_key']),
      ];
      $results[$recipient_id] = $this->repository->insert($row);
      if (is_wp_error($results[$recipient_id])) return $results[$recipient_id];
    }
    return $results;
  }

  public function unread_count($recipient_user_id) { return $this->repository->unread_count(absint($recipient_user_id)); }

  public function list_for_recipient($recipient_user_id, $source_product = '', $limit = 25, $after_created_at = '', $after_id = 0) {
    $recipient_user_id = absint($recipient_user_id);
    $source_product = $source_product === '' ? '' : sanitize_key($source_product);
    if ($source_product !== '' && !TNet_Notifications_Registry::has_source($source_product)) return new WP_Error('tnet_notifications_source_invalid', 'Source filter is not registered.');
    $rows = $this->repository->list($recipient_user_id, $source_product, $limit, $after_created_at, $after_id);
    $output = [];
    foreach ($rows as $row) {
      $definition = TNet_Notifications_Registry::definition($row['source_product'], $row['event_type'], (int) $row['payload_version']);
      if (!$definition) continue;
      $authorization = $definition['authorize'] ?? null;
      $record = $this->public_record($row);
      if (!$record) continue;
      if (is_callable($authorization) && !call_user_func($authorization, $recipient_user_id, $record)) continue;
      $output[] = $record;
    }
    return $output;
  }

  public function mark_read($recipient_user_id, $notification_id) { return $this->repository->mark_read(absint($recipient_user_id), absint($notification_id)); }
  public function mark_all_read($recipient_user_id, $source_product = '') {
    if ($source_product !== '' && !TNet_Notifications_Registry::has_source($source_product)) return new WP_Error('tnet_notifications_source_invalid', 'Source filter is not registered.');
    return $this->repository->mark_all_read(absint($recipient_user_id), $source_product);
  }
  public function archive_event($source_product, $event_id, $state = 'archived') {
    if (!TNet_Notifications_Registry::has_source($source_product)) return new WP_Error('tnet_notifications_source_invalid', 'Source is not registered.');
    return $this->repository->archive_event($source_product, $event_id, $state);
  }

  private function public_record(array $row) {
    $definition = TNet_Notifications_Registry::definition($row['source_product'], $row['event_type'], (int) $row['payload_version']);
    $resolved_destination = null;
    if ($definition && is_callable($definition['resolve'] ?? null)) {
      $resolved_destination = call_user_func($definition['resolve'], json_decode($row['destination_args_json'], true) ?: []);
      if (!$resolved_destination) return null;
    }
    return [
      'notification_id' => (int) $row['notification_id'],
      'recipient_user_id' => (int) $row['recipient_user_id'],
      'source_product' => $row['source_product'],
      'event_id' => $row['event_id'],
      'event_type' => $row['event_type'],
      'payload_version' => (int) $row['payload_version'],
      'actor_user_id' => $row['actor_user_id'] === null ? null : (int) $row['actor_user_id'],
      'object_type' => $row['object_type'],
      'object_id' => $row['object_id'],
      'destination_key' => $row['destination_key'],
      'destination_args' => json_decode($row['destination_args_json'], true) ?: [],
      'destination' => $resolved_destination,
      'metadata' => json_decode($row['metadata_json'], true) ?: [],
      'created_at' => $row['created_at'],
      'read_at' => $row['read_at'],
      'active_state' => $row['active_state'],
      'archived_at' => $row['archived_at'],
    ];
  }
}
