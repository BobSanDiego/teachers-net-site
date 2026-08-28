<?php
defined('ABSPATH') || exit;

final class TNet_Notifications_Registry {
  private static $sources = [];

  public static function register_source($source_product, array $events) {
    $source_product = sanitize_key((string) $source_product);
    if ($source_product === '' || isset(self::$sources[$source_product])) return false;
    $normalized_events = [];
    foreach ($events as $event_type => $definition) {
      if (!is_array($definition) || !preg_match('/^[a-z0-9][a-z0-9_.-]*$/', (string) $event_type)) return false;
      $event_type = self::event_key($event_type);
      if (!isset($definition['versions']) || !is_array($definition['versions'])) return false;
      foreach ($definition['versions'] as $version => $schema) {
        if (!is_array($schema) || !array_key_exists('metadata_keys', $schema) || !is_array($schema['metadata_keys']) || !isset($schema['destinations']) || !is_array($schema['destinations'])) return false;
      }
      $normalized_events[$event_type] = $definition;
    }
    self::$sources[$source_product] = $normalized_events;
    return true;
  }

  public static function has_source($source_product) { return isset(self::$sources[sanitize_key((string) $source_product)]); }

  public static function definition($source_product, $event_type, $version) {
    $source_product = sanitize_key((string) $source_product);
    $event_type = self::event_key($event_type);
    if (!isset(self::$sources[$source_product][$event_type]['versions'][(int) $version])) return null;
    return self::$sources[$source_product][$event_type]['versions'][(int) $version];
  }

  public static function validate_event(array $event) {
    $required = ['event_id','source_product','event_type','payload_version','object_type','object_id','destination_key','destination_args','metadata','dedupe_key'];
    foreach ($required as $field) if (!array_key_exists($field, $event)) return new WP_Error('tnet_notifications_event_invalid', "Missing event field: {$field}.");
    $source = sanitize_key((string) $event['source_product']);
    $type = self::event_key($event['event_type']);
    $version = absint($event['payload_version']);
    $definition = self::definition($source, $type, $version);
    if (!$definition) return new WP_Error('tnet_notifications_event_unregistered', 'Source, event, or payload version is not registered.', ['source_product' => $source, 'event_type' => $type, 'payload_version' => $version]);
    if (!is_array($event['destination_args']) || !is_array($event['metadata'])) return new WP_Error('tnet_notifications_event_invalid', 'Destination arguments and metadata must be structured arrays.');
    if (!self::safe_value($event['destination_args']) || !self::safe_value($event['metadata'])) return new WP_Error('tnet_notifications_event_invalid', 'Metadata and destination arguments contain unsafe markup or URL data.');
    $destination_key = self::event_key($event['destination_key']);
    if (empty($definition['destinations'][$destination_key])) return new WP_Error('tnet_notifications_destination_invalid', 'Destination key is not registered.');
    $destination_validator = $definition['destinations'][$destination_key];
    if (is_callable($destination_validator) && !call_user_func($destination_validator, $event['destination_args'])) return new WP_Error('tnet_notifications_destination_invalid', 'Destination arguments failed validation.');
    $unknown = array_values(array_diff(array_keys($event['metadata']), $definition['metadata_keys']));
    if ($unknown) return new WP_Error('tnet_notifications_metadata_invalid', 'Metadata contains unregistered fields.', ['fields' => $unknown]);
    if (!empty($definition['metadata_validator']) && is_callable($definition['metadata_validator']) && !call_user_func($definition['metadata_validator'], $event['metadata'])) return new WP_Error('tnet_notifications_metadata_invalid', 'Metadata failed validation.');
    if (!preg_match('/^[a-z0-9][a-z0-9_.:-]{0,190}$/i', (string) $event['event_id']) || !preg_match('/^[a-z0-9][a-z0-9_.:-]{0,190}$/i', (string) $event['dedupe_key'])) return new WP_Error('tnet_notifications_event_invalid', 'Event and dedupe identities are invalid.');
    $event['source_product'] = $source;
    $event['event_type'] = $type;
    $event['payload_version'] = $version;
    $event['destination_key'] = $destination_key;
    return $event;
  }

  private static function event_key($value) {
    $value = strtolower(trim((string) $value));
    return preg_replace('/[^a-z0-9_.:-]/', '', $value);
  }

  private static function safe_value($value, $depth = 0) {
    if ($depth > 4) return false;
    if (is_array($value)) {
      foreach ($value as $key => $item) {
        if (!is_string($key) || !preg_match('/^[a-z0-9_-]{1,64}$/i', $key) || !self::safe_value($item, $depth + 1)) return false;
      }
      return true;
    }
    if (is_bool($value) || is_int($value) || is_float($value) || $value === null) return true;
    if (!is_string($value) || strlen($value) > 191) return false;
    return !preg_match('/<[^>]*>|(?:javascript|data|https?):\/\//i', $value);
  }
}
