<?php

defined('ABSPATH') || exit;

/**
 * Canonical, explicitly reported member-context relation.
 *
 * This relation intentionally models independent contexts rather than a
 * mutually exclusive member type. A member can, for example, be both a
 * teacher and an administrator while also reporting hiring intent.
 */
final class TNet_Profile_Member_Context {
  const DB_VERSION = '2.0.0';
  const DB_OPTION = 'tnet_profile_member_context_db_version';
  const PROVENANCE_SELF_REPORTED = 'self_reported';
  const PROVENANCE_VERIFIED = 'verified';
  const PROVENANCE_INFERRED_CONTEXTUAL = 'inferred_contextual';
  const CORE_TERMS_FRAMEWORK = 'teachers-net';
  const VISIBILITY_PROFILE_DETAILS = 'profile_details';
  const LIFECYCLE_ACTIVE = 'active';

  const PROFILE_TEACHING_SINCE_META = '_tnet_profile_teaching_since';
  const PROFILE_BIO_META = '_tnet_profile_bio';
  const PROFILE_DETAILS_PUBLIC_META = '_tnet_profile_details_public';
  const PROFILE_LOCATION_PUBLIC_META = '_tnet_profile_location_public';

  private const ROLE_VALUES = [
    'teacher',
    'administrator',
    'education_student',
    'retired_teacher',
    'vendor',
    'tutor',
    'other',
  ];
  private const INTENT_VALUES = ['hiring'];
  private const FACT_RELATIONSHIP_TYPES = [
    'professional_identity',
    'teaching_grade',
    'teaching_subject',
    'interest',
  ];

  public static function init() {
    add_action('init', [__CLASS__, 'maybe_upgrade'], 1);
  }

  public static function table() {
    global $wpdb;
    return $wpdb->prefix . 'tnet_profile_member_context';
  }

  public static function activate() {
    self::install_schema();
  }

  public static function maybe_upgrade() {
    if (get_option(self::DB_OPTION) !== self::DB_VERSION) self::install_schema();
  }

  public static function install_schema() {
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table = self::table();
    $charset = $wpdb->get_charset_collate();
    dbDelta("CREATE TABLE {$table} (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      user_id bigint(20) unsigned NOT NULL,
      context_type varchar(32) NOT NULL,
      context_value varchar(64) NOT NULL,
      provenance varchar(32) NOT NULL DEFAULT 'self_reported',
      term_framework_slug varchar(64) NULL,
      term_uuid char(36) NULL,
      visibility_scope varchar(32) NOT NULL DEFAULT 'profile_details',
      lifecycle_state varchar(32) NOT NULL DEFAULT 'active',
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      PRIMARY KEY  (id),
      UNIQUE KEY user_context_provenance (user_id, context_type, context_value, provenance),
      UNIQUE KEY user_fact_term_provenance (user_id, context_type, term_uuid, provenance),
      KEY context_user (context_type, context_value, provenance, user_id),
      KEY user_context (user_id, context_type, context_value, provenance),
      KEY fact_user (user_id, context_type, provenance, term_uuid),
      KEY fact_context (context_type, term_uuid, provenance, user_id)
    ) {$charset};");
    update_option(self::DB_OPTION, self::DB_VERSION, false);
  }

  public static function role_values() {
    return self::ROLE_VALUES;
  }

  public static function intent_values() {
    return self::INTENT_VALUES;
  }

  /** Return every currently stored context for one member, grouped by domain. */
  public static function for_user($user_id, $provenance = self::PROVENANCE_SELF_REPORTED) {
    global $wpdb;
    $user_id = absint($user_id);
    $provenance = self::normalize_provenance($provenance);
    $rows = $user_id ? $wpdb->get_results($wpdb->prepare(
      'SELECT context_type, context_value, provenance, term_framework_slug, term_uuid, visibility_scope, lifecycle_state, created_at, updated_at FROM ' . self::table() . ' WHERE user_id = %d AND provenance = %s ORDER BY context_type, context_value',
      $user_id,
      $provenance
    ), ARRAY_A) : [];
    $result = ['roles' => [], 'intents' => [], 'entries' => []];
    foreach ((array) $rows as $row) {
      $entry = [
        'type' => (string) $row['context_type'],
        'value' => (string) $row['context_value'],
        'provenance' => (string) $row['provenance'],
        'term_framework_slug' => (string) ($row['term_framework_slug'] ?? ''),
        'term_uuid' => (string) ($row['term_uuid'] ?? ''),
        'visibility_scope' => (string) ($row['visibility_scope'] ?? self::VISIBILITY_PROFILE_DETAILS),
        'lifecycle_state' => (string) ($row['lifecycle_state'] ?? self::LIFECYCLE_ACTIVE),
        'created_at' => (string) $row['created_at'],
        'updated_at' => (string) $row['updated_at'],
      ];
      $result['entries'][] = $entry;
      if ($entry['type'] === 'role') $result['roles'][] = $entry['value'];
      if ($entry['type'] === 'intent') $result['intents'][] = $entry['value'];
    }
    return $result;
  }

  /**
   * Replace only the legacy Screen 6 self-reported role/intent set. Canonical
   * Profile facts and future inferred rows are deliberately untouched.
   */
  public static function replace_self_reported($user_id, array $roles, array $intents) {
    global $wpdb;
    $user_id = absint($user_id);
    if (!$user_id || !get_user_by('id', $user_id)) {
      return new WP_Error('tnet_profile_member_context_user_missing', __('The account could not be found.', 'tnet-profile'));
    }
    $rows = self::normalized_rows($roles, $intents);
    $table = self::table();
    $now = gmdate('Y-m-d H:i:s');
    $wpdb->query('START TRANSACTION');
    try {
      $existing = $wpdb->get_results($wpdb->prepare(
      'SELECT id, context_type, context_value FROM ' . $table . ' WHERE user_id = %d AND provenance = %s AND context_type IN (%s, %s)',
      $user_id,
      self::PROVENANCE_SELF_REPORTED,
      'role',
      'intent'
      ), ARRAY_A);
      $wanted = [];
      foreach ($rows as $row) $wanted[$row['type'] . ':' . $row['value']] = true;
      foreach ((array) $existing as $row) {
        $key = (string) $row['context_type'] . ':' . (string) $row['context_value'];
        if (!isset($wanted[$key])) {
          $deleted = $wpdb->delete($table, ['id' => absint($row['id'])], ['%d']);
          if ($deleted === false) throw new RuntimeException('Could not remove a deselected member context.');
        }
      }
      foreach ($rows as $row) {
        $inserted = $wpdb->query($wpdb->prepare(
          "INSERT INTO {$table} (user_id, context_type, context_value, provenance, created_at, updated_at)
           VALUES (%d, %s, %s, %s, %s, %s)
           ON DUPLICATE KEY UPDATE updated_at = VALUES(updated_at)",
          $user_id,
          $row['type'],
          $row['value'],
          self::PROVENANCE_SELF_REPORTED,
          $now,
          $now
        ));
        if ($inserted === false) throw new RuntimeException('Could not save the member context.');
      }
      $wpdb->query('COMMIT');
    } catch (Throwable $exception) {
      $wpdb->query('ROLLBACK');
      return new WP_Error('tnet_profile_member_context_save_failed', __('Your selections could not be saved. Please try again.', 'tnet-profile'));
    }
    return self::for_user($user_id);
  }

  public static function clear_self_reported($user_id) {
    global $wpdb;
    $user_id = absint($user_id);
    if (!$user_id) return;
    $wpdb->query($wpdb->prepare(
      'DELETE FROM ' . self::table() . ' WHERE user_id = %d AND provenance = %s AND context_type IN (%s, %s)',
      $user_id,
      self::PROVENANCE_SELF_REPORTED,
      'role',
      'intent'
    ));
  }

  /**
   * Persist one explicit Profile fact using a live Core Terms UUID. Legacy
   * Screen 6 role/intent values remain separate compatibility rows.
   */
  public static function add_fact($user_id, $relationship_type, $term_uuid, $provenance = self::PROVENANCE_SELF_REPORTED) {
    global $wpdb;
    $user_id = absint($user_id);
    $relationship_type = self::normalize_fact_relationship_type($relationship_type);
    $provenance = self::normalize_fact_provenance($provenance);
    $term = self::resolve_live_core_term($term_uuid);
    if (!$user_id || !get_user_by('id', $user_id)) return new WP_Error('tnet_profile_member_fact_user_missing', __('The account could not be found.', 'tnet-profile'));
    if ($relationship_type === '') return new WP_Error('tnet_profile_member_fact_relationship_invalid', __('That Profile fact relationship is not supported.', 'tnet-profile'));
    if ($provenance === '') return new WP_Error('tnet_profile_member_fact_provenance_invalid', __('That Profile fact provenance is not supported.', 'tnet-profile'));
    if (is_wp_error($term)) return $term;

    $now = gmdate('Y-m-d H:i:s');
    $saved = $wpdb->query($wpdb->prepare(
      'INSERT INTO ' . self::table() . ' (user_id, context_type, context_value, provenance, term_framework_slug, term_uuid, visibility_scope, lifecycle_state, created_at, updated_at)
       VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s)
       ON DUPLICATE KEY UPDATE visibility_scope = VALUES(visibility_scope), lifecycle_state = VALUES(lifecycle_state), updated_at = VALUES(updated_at)',
      $user_id,
      $relationship_type,
      $term['term_uuid'],
      $provenance,
      self::CORE_TERMS_FRAMEWORK,
      $term['term_uuid'],
      self::VISIBILITY_PROFILE_DETAILS,
      self::LIFECYCLE_ACTIVE,
      $now,
      $now
    ));
    if ($saved === false) return new WP_Error('tnet_profile_member_fact_save_failed', __('Your Profile fact could not be saved. Please try again.', 'tnet-profile'));
    return self::fact_for_user($user_id, $relationship_type, $term['term_uuid'], $provenance);
  }

  public static function remove_fact($user_id, $relationship_type, $term_uuid, $provenance = self::PROVENANCE_SELF_REPORTED) {
    global $wpdb;
    $user_id = absint($user_id);
    $relationship_type = self::normalize_fact_relationship_type($relationship_type);
    $provenance = self::normalize_fact_provenance($provenance);
    $term_uuid = strtolower(trim((string) $term_uuid));
    if (!$user_id || $relationship_type === '' || $provenance === '' || !wp_is_uuid($term_uuid)) return false;
    return false !== $wpdb->delete(self::table(), [
      'user_id' => $user_id,
      'context_type' => $relationship_type,
      'term_uuid' => $term_uuid,
      'provenance' => $provenance,
    ], ['%d', '%s', '%s', '%s']);
  }

  /** Return explicit canonical-term facts for one member without legacy Screen 6 rows. */
  public static function facts_for_user($user_id, $provenance = self::PROVENANCE_SELF_REPORTED) {
    global $wpdb;
    $user_id = absint($user_id);
    $provenance = self::normalize_fact_provenance($provenance);
    if (!$user_id || $provenance === '') return [];
    $placeholders = implode(', ', array_fill(0, count(self::FACT_RELATIONSHIP_TYPES), '%s'));
    $params = array_merge([$user_id, $provenance, self::LIFECYCLE_ACTIVE], self::FACT_RELATIONSHIP_TYPES);
    $sql = 'SELECT context_type AS relationship_type, term_framework_slug, term_uuid, provenance, visibility_scope, lifecycle_state, created_at, updated_at FROM ' . self::table() . ' WHERE user_id = %d AND provenance = %s AND lifecycle_state = %s AND context_type IN (' . $placeholders . ') AND term_uuid IS NOT NULL ORDER BY context_type, term_uuid';
    return (array) $wpdb->get_results($wpdb->prepare($sql, $params), ARRAY_A);
  }

  /** Indexed reverse lookup for an explicit Profile fact. */
  public static function user_ids_for_fact($relationship_type, $term_uuid, $provenance = self::PROVENANCE_SELF_REPORTED) {
    global $wpdb;
    $relationship_type = self::normalize_fact_relationship_type($relationship_type);
    $provenance = self::normalize_fact_provenance($provenance);
    $term_uuid = strtolower(trim((string) $term_uuid));
    if ($relationship_type === '' || $provenance === '' || !wp_is_uuid($term_uuid)) return [];
    return array_map('absint', (array) $wpdb->get_col($wpdb->prepare(
      'SELECT user_id FROM ' . self::table() . ' WHERE context_type = %s AND term_uuid = %s AND provenance = %s AND lifecycle_state = %s ORDER BY user_id',
      $relationship_type,
      $term_uuid,
      $provenance,
      self::LIFECYCLE_ACTIVE
    )));
  }

  /**
   * Indexed fact intersection with the existing Screen 5 country/region seam.
   * Location stays canonical user meta and is never copied into this relation.
   */
  public static function user_ids_matching_facts(array $facts, array $location = []) {
    global $wpdb;
    $normalized = [];
    foreach ($facts as $fact) {
      if (!is_array($fact)) continue;
      $relationship_type = self::normalize_fact_relationship_type($fact['relationship_type'] ?? '');
      $term_uuid = strtolower(trim((string) ($fact['term_uuid'] ?? '')));
      $provenance = self::normalize_fact_provenance($fact['provenance'] ?? self::PROVENANCE_SELF_REPORTED);
      if ($relationship_type === '' || $provenance === '' || !wp_is_uuid($term_uuid)) continue;
      $normalized[$relationship_type . ':' . $term_uuid . ':' . $provenance] = compact('relationship_type', 'term_uuid', 'provenance');
    }
    $normalized = array_values($normalized);
    if (!$normalized) return [];

    $table = self::table();
    $joins = [];
    $where = [];
    $params = [];
    foreach ($normalized as $index => $fact) {
      $alias = 'fact_' . $index;
      if ($index === 0) $from = "{$table} {$alias}";
      else $joins[] = "INNER JOIN {$table} {$alias} ON {$alias}.user_id = fact_0.user_id";
      $where[] = "{$alias}.context_type = %s AND {$alias}.term_uuid = %s AND {$alias}.provenance = %s AND {$alias}.lifecycle_state = %s";
      array_push($params, $fact['relationship_type'], $fact['term_uuid'], $fact['provenance'], self::LIFECYCLE_ACTIVE);
    }

    $country = strtoupper(sanitize_text_field((string) ($location['country_code'] ?? '')));
    $region = strtoupper(sanitize_text_field((string) ($location['region_code'] ?? '')));
    if ($country !== '') {
      $meta = $wpdb->usermeta;
      $joins[] = "INNER JOIN {$meta} location_country ON location_country.user_id = fact_0.user_id AND location_country.meta_key = '" . esc_sql('_tnet_profile_country_code') . "'";
      $where[] = 'location_country.meta_value = %s';
      $params[] = $country;
      if ($region !== '') {
        $joins[] = "INNER JOIN {$meta} location_region ON location_region.user_id = fact_0.user_id AND location_region.meta_key = '" . esc_sql('_tnet_profile_region_code') . "'";
        $where[] = 'location_region.meta_value = %s';
        $params[] = $region;
      }
    }
    $sql = 'SELECT DISTINCT fact_0.user_id FROM ' . $from . ' ' . implode(' ', $joins) . ' WHERE ' . implode(' AND ', $where) . ' ORDER BY fact_0.user_id';
    return array_map('absint', (array) $wpdb->get_col($wpdb->prepare($sql, $params)));
  }

  /** Resolve a live term by UUID or canonical slug for discovery/documentation only. */
  public static function resolve_live_core_term_identifier($identifier) {
    $identifier = trim((string) $identifier);
    if ($identifier === '') return new WP_Error('tnet_profile_member_fact_term_missing', __('A canonical Core Term is required.', 'tnet-profile'));
    if (wp_is_uuid($identifier)) return self::resolve_live_core_term($identifier);
    if (!class_exists('CFM')) return new WP_Error('tnet_profile_member_fact_core_terms_unavailable', __('Core Terms is unavailable.', 'tnet-profile'));
    $term = CFM::get_term_by_identifier(self::CORE_TERMS_FRAMEWORK, $identifier);
    if ($term) return self::term_reference_from_runtime_term($term);
    return new WP_Error('tnet_profile_member_fact_term_unresolved', __('That Core Term is not in the active vocabulary.', 'tnet-profile'));
  }

  /** Scalar representation contract only; no Profile UI writer is introduced here. */
  public static function profile_v1_scalars($user_id) {
    $user_id = absint($user_id);
    if (!$user_id) return ['teaching_since' => null, 'bio' => '', 'profile_details_public' => false, 'location_public' => false];
    $teaching_since = absint(get_user_meta($user_id, self::PROFILE_TEACHING_SINCE_META, true));
    return [
      'teaching_since' => $teaching_since >= 1000 && $teaching_since <= 9999 ? $teaching_since : null,
      'bio' => (string) get_user_meta($user_id, self::PROFILE_BIO_META, true),
      'profile_details_public' => get_user_meta($user_id, self::PROFILE_DETAILS_PUBLIC_META, true) === '1',
      'location_public' => get_user_meta($user_id, self::PROFILE_LOCATION_PUBLIC_META, true) === '1',
    ];
  }

  /** Indexed reverse lookup for one current context value. */
  public static function user_ids_for_context($type, $value, $provenance = self::PROVENANCE_SELF_REPORTED) {
    global $wpdb;
    $context = self::normalize_context($type, $value);
    if (!$context) return [];
    return array_map('absint', (array) $wpdb->get_col($wpdb->prepare(
      'SELECT user_id FROM ' . self::table() . ' WHERE context_type = %s AND context_value = %s AND provenance = %s ORDER BY user_id',
      $context['type'],
      $context['value'],
      self::normalize_provenance($provenance)
    )));
  }

  /**
   * Indexed intersection seam for future matching. Context predicates are
   * independently joined; optional Screen 5 location is added without
   * reclassifying the member or copying location into this relation.
   */
  public static function user_ids_matching(array $contexts, array $location = []) {
    global $wpdb;
    $normalized = [];
    foreach ($contexts as $context) {
      if (!is_array($context)) continue;
      $item = self::normalize_context($context['type'] ?? '', $context['value'] ?? '');
      if ($item) $normalized[$item['type'] . ':' . $item['value']] = $item;
    }
    $normalized = array_values($normalized);
    if (!$normalized) return [];

    $table = self::table();
    $joins = [];
    $where = [];
    $params = [];
    foreach ($normalized as $index => $context) {
      $alias = 'context_' . $index;
      if ($index === 0) {
        $from = "{$table} {$alias}";
      } else {
        $joins[] = "INNER JOIN {$table} {$alias} ON {$alias}.user_id = context_0.user_id";
      }
      $where[] = "{$alias}.context_type = %s AND {$alias}.context_value = %s AND {$alias}.provenance = %s";
      array_push($params, $context['type'], $context['value'], self::PROVENANCE_SELF_REPORTED);
    }

    $country = strtoupper(sanitize_text_field((string) ($location['country_code'] ?? '')));
    $region = strtoupper(sanitize_text_field((string) ($location['region_code'] ?? '')));
    if ($country !== '') {
      $meta = $wpdb->usermeta;
      $joins[] = "INNER JOIN {$meta} location_country ON location_country.user_id = context_0.user_id AND location_country.meta_key = '" . esc_sql('_tnet_profile_country_code') . "'";
      $where[] = 'location_country.meta_value = %s';
      $params[] = $country;
      if ($region !== '') {
        $joins[] = "INNER JOIN {$meta} location_region ON location_region.user_id = context_0.user_id AND location_region.meta_key = '" . esc_sql('_tnet_profile_region_code') . "'";
        $where[] = 'location_region.meta_value = %s';
        $params[] = $region;
      }
    }

    $sql = 'SELECT DISTINCT context_0.user_id FROM ' . $from . ' ' . implode(' ', $joins) . ' WHERE ' . implode(' AND ', $where) . ' ORDER BY context_0.user_id';
    return array_map('absint', (array) $wpdb->get_col($wpdb->prepare($sql, $params)));
  }

  private static function normalized_rows(array $roles, array $intents) {
    $rows = [];
    foreach (array_unique(array_map('sanitize_key', $roles)) as $value) {
      if (in_array($value, self::ROLE_VALUES, true)) $rows[] = ['type' => 'role', 'value' => $value];
    }
    foreach (array_unique(array_map('sanitize_key', $intents)) as $value) {
      if (in_array($value, self::INTENT_VALUES, true)) $rows[] = ['type' => 'intent', 'value' => $value];
    }
    return $rows;
  }

  private static function normalize_context($type, $value) {
    $type = sanitize_key((string) $type);
    $value = sanitize_key((string) $value);
    if ($type === 'role' && in_array($value, self::ROLE_VALUES, true)) return ['type' => $type, 'value' => $value];
    if ($type === 'intent' && in_array($value, self::INTENT_VALUES, true)) return ['type' => $type, 'value' => $value];
    return null;
  }

  private static function normalize_provenance($provenance) {
    $provenance = sanitize_key((string) $provenance);
    return $provenance !== '' ? $provenance : self::PROVENANCE_SELF_REPORTED;
  }

  private static function normalize_fact_relationship_type($relationship_type) {
    $relationship_type = sanitize_key((string) $relationship_type);
    return in_array($relationship_type, self::FACT_RELATIONSHIP_TYPES, true) ? $relationship_type : '';
  }

  private static function normalize_fact_provenance($provenance) {
    $provenance = sanitize_key((string) $provenance);
    return in_array($provenance, [self::PROVENANCE_SELF_REPORTED, self::PROVENANCE_VERIFIED, self::PROVENANCE_INFERRED_CONTEXTUAL], true) ? $provenance : '';
  }

  private static function resolve_live_core_term($term_uuid) {
    $term_uuid = strtolower(trim((string) $term_uuid));
    if (!wp_is_uuid($term_uuid)) return new WP_Error('tnet_profile_member_fact_term_invalid', __('A canonical Core Term UUID is required.', 'tnet-profile'));
    if (!class_exists('CFM')) return new WP_Error('tnet_profile_member_fact_core_terms_unavailable', __('Core Terms is unavailable.', 'tnet-profile'));
    foreach ((array) CFM::get_terms(self::CORE_TERMS_FRAMEWORK) as $term) {
      if (strtolower((string) ($term->term_uuid ?? '')) === $term_uuid) return self::term_reference_from_runtime_term($term);
    }
    return new WP_Error('tnet_profile_member_fact_term_unresolved', __('That Core Term is not in the active vocabulary.', 'tnet-profile'));
  }

  private static function term_reference_from_runtime_term($term) {
    $term_uuid = (string) ($term->term_uuid ?? '');
    $axis_uuid = (string) ($term->axis_uuid ?? '');
    if ($term_uuid === '' || $term_uuid === $axis_uuid) return new WP_Error('tnet_profile_member_fact_axis_not_assignable', __('A Profile fact must reference a specific Core Term, not a top-level axis.', 'tnet-profile'));
    return [
      'term_uuid' => $term_uuid,
      'slug' => (string) ($term->slug ?? ''),
      'label' => (string) ($term->label ?? ''),
      'axis_uuid' => $axis_uuid,
    ];
  }

  private static function fact_for_user($user_id, $relationship_type, $term_uuid, $provenance) {
    global $wpdb;
    return $wpdb->get_row($wpdb->prepare(
      'SELECT context_type AS relationship_type, term_framework_slug, term_uuid, provenance, visibility_scope, lifecycle_state, created_at, updated_at FROM ' . self::table() . ' WHERE user_id = %d AND context_type = %s AND term_uuid = %s AND provenance = %s LIMIT 1',
      absint($user_id),
      $relationship_type,
      $term_uuid,
      $provenance
    ), ARRAY_A);
  }
}
