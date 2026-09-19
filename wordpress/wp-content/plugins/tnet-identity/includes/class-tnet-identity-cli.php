<?php

defined('ABSPATH') || exit;

/**
 * Development-only lifecycle inspection and cleanup for disposable identities.
 *
 * This class is registered only in WP-CLI. Reset additionally requires the
 * explicit DDEV markers supplied by the local project runtime.
 */
final class TNet_Identity_CLI {
  /**
   * Inspect one identity without returning reusable verification material.
   *
   * ## OPTIONS
   *
   * <email>
   * : The one explicit email address to inspect.
   *
   * ## EXAMPLES
   *
   *     wp tnet identity inspect qa@example.test
   *
   * @param array $args Positional arguments.
   * @param array $assoc_args Associative arguments.
   */
  public function inspect($args, $assoc_args) {
    $email = $this->target_email($args);
    $user = get_user_by('email', $email);
    $payload = [
      'query' => ['email' => $email],
      'found' => (bool) $user,
      'identity_owner' => 'tnet-identity over WordPress wp_users',
    ];

    if (!$user) {
      $payload['state'] = 'not_found';
      $payload['reservations'] = [
        'email_in_use' => false,
        'username_in_use' => null,
        'separate_identity_reservation_tables' => [],
      ];
      $payload['onboarding'] = [
        'owner' => 'not owned by tnet-identity',
        'state' => 'not_present in current Identity schema',
      ];
      $payload['artifacts'] = [
        'verification_rows' => 0,
        'continuation_rows' => 0,
        'wordpress_user_meta_keys' => 0,
      ];
      $this->emit($payload);
      return;
    }

    global $wpdb;
    $verification_rows = $wpdb->get_results($wpdb->prepare(
      'SELECT id, purpose, issued_at, expires_at, used_at, confirmation_code_hash, code_expires_at FROM ' . TNet_Identity::table('verification') . ' WHERE user_id = %d ORDER BY issued_at DESC, id DESC',
      (int) $user->ID
    ), ARRAY_A);
    $continuation_rows = $wpdb->get_results($wpdb->prepare(
      'SELECT id, purpose, action, route_key, issued_at, expires_at, consumed_at FROM ' . TNet_Identity::table('continuation') . ' WHERE user_id = %d ORDER BY issued_at DESC, id DESC',
      (int) $user->ID
    ), ARRAY_A);
    $latest_verification = $verification_rows[0] ?? null;
    $verification_state = 'not_issued';
    if ($latest_verification) {
      if (!empty($latest_verification['used_at'])) {
        $verification_state = 'verified';
      } elseif (strtotime((string) $latest_verification['expires_at'] . ' UTC') < time()) {
        $verification_state = 'pending_expired';
      } else {
        $verification_state = 'pending';
      }
    }
    $continuation_state = 'none';
    foreach ($continuation_rows as $row) {
      if (empty($row['consumed_at']) && strtotime((string) $row['expires_at'] . ' UTC') >= time()) {
        $continuation_state = 'active';
        break;
      }
      if (!empty($row['consumed_at'])) $continuation_state = 'consumed';
      elseif ($continuation_state === 'none') $continuation_state = 'expired';
    }
    $meta_key_count = (int) $wpdb->get_var($wpdb->prepare(
      'SELECT COUNT(DISTINCT meta_key) FROM ' . $wpdb->usermeta . ' WHERE user_id = %d',
      (int) $user->ID
    ));

    $payload['state'] = 'present';
    $payload['user'] = [
      'id' => (int) $user->ID,
      'email' => (string) $user->user_email,
      'username' => (string) $user->user_login,
      'display_name' => (string) $user->display_name,
      'registered_at' => (string) $user->user_registered,
      'roles' => array_values((array) $user->roles),
    ];
    $payload['verification'] = [
      'state' => $verification_state,
      'issued' => $latest_verification ? (string) $latest_verification['issued_at'] : null,
      'expires' => $latest_verification ? (string) $latest_verification['expires_at'] : null,
      'consumed_at' => $latest_verification ? ($latest_verification['used_at'] ?: null) : null,
      'purpose' => $latest_verification ? (string) $latest_verification['purpose'] : null,
      'row_count' => count($verification_rows),
      'reusable_secret_exposed' => false,
      'confirmation_code' => [
        'state' => $latest_verification && !empty($latest_verification['confirmation_code_hash']) ? 'issued' : 'not_issued',
        'expires' => $latest_verification && !empty($latest_verification['code_expires_at']) ? (string) $latest_verification['code_expires_at'] : null,
        'consumed_at' => $latest_verification ? ($latest_verification['used_at'] ?: null) : null,
        'reusable_secret_exposed' => false,
      ],
    ];
    $payload['continuation'] = [
      'state' => $continuation_state,
      'row_count' => count($continuation_rows),
      'rows' => array_map(static function ($row) {
        return [
          'id' => (int) $row['id'],
          'purpose' => (string) $row['purpose'],
          'action' => (string) $row['action'],
          'route_key' => (string) $row['route_key'],
          'issued' => (string) $row['issued_at'],
          'expires' => (string) $row['expires_at'],
          'consumed_at' => $row['consumed_at'] ?: null,
        ];
      }, $continuation_rows),
    ];
    $payload['onboarding'] = [
      'owner' => 'tnet-identity over WordPress user meta',
      'avatar_pending' => TNet_Identity_Service::needs_avatar((int) $user->ID),
      'public_identity_pending' => TNet_Identity_Service::needs_public_identity((int) $user->ID),
      'location_pending' => TNet_Identity_Service::needs_location((int) $user->ID),
      'location' => TNet_Identity_Service::location((int) $user->ID),
    ];
    $payload['reservations'] = [
      'email_in_use' => (bool) email_exists($user->user_email),
      'username_in_use' => (bool) username_exists($user->user_login),
      'separate_identity_reservation_tables' => [],
    ];
    $payload['artifacts'] = [
      'verification_rows' => count($verification_rows),
      'continuation_rows' => count($continuation_rows),
      'wordpress_user_meta_keys' => $meta_key_count,
    ];
    $this->emit($payload);
  }

  /**
   * Reset one explicitly named disposable identity in DDEV only.
   *
   * ## OPTIONS
   *
   * <email>
   * : The one explicit email address to reset.
   *
   * ## EXAMPLES
   *
   *     wp tnet identity reset qa@example.test
   *
   * @param array $args Positional arguments.
   * @param array $assoc_args Associative arguments.
   */
  public function reset($args, $assoc_args) {
    $this->require_ddev();
    $email = $this->target_email($args);
    $user = get_user_by('email', $email);
    if (!$user) {
      $this->emit([
        'query' => ['email' => $email],
        'reset' => 'no_matching_identity',
        'mutated' => false,
      ]);
      return;
    }

    global $wpdb;
    $user_id = (int) $user->ID;
    $wpdb->query('START TRANSACTION');
    $verification_deleted = $wpdb->query($wpdb->prepare(
      'DELETE FROM ' . TNet_Identity::table('verification') . ' WHERE user_id = %d',
      $user_id
    ));
    $continuation_deleted = $wpdb->query($wpdb->prepare(
      'DELETE FROM ' . TNet_Identity::table('continuation') . ' WHERE user_id = %d',
      $user_id
    ));
    $user_deleted = wp_delete_user($user_id);
    if (!$user_deleted) {
      $wpdb->query('ROLLBACK');
      WP_CLI::error('Identity reset failed while deleting the explicit WordPress user; transaction rolled back.');
    }
    $wpdb->query('COMMIT');

    $remaining_verification = (int) $wpdb->get_var($wpdb->prepare(
      'SELECT COUNT(*) FROM ' . TNet_Identity::table('verification') . ' WHERE user_id = %d',
      $user_id
    ));
    $remaining_continuation = (int) $wpdb->get_var($wpdb->prepare(
      'SELECT COUNT(*) FROM ' . TNet_Identity::table('continuation') . ' WHERE user_id = %d',
      $user_id
    ));
    $this->emit([
      'query' => ['email' => $email],
      'reset' => 'complete',
      'mutated' => true,
      'user_id' => $user_id,
      'verification_rows_deleted' => max(0, (int) $verification_deleted),
      'continuation_rows_deleted' => max(0, (int) $continuation_deleted),
      'wordpress_user_deleted' => true,
      'remaining_identity_rows' => [
        'verification' => $remaining_verification,
        'continuation' => $remaining_continuation,
      ],
      'reusable_secret_exposed' => false,
    ]);
  }

  /**
   * Reset only the voluntary Screen 5 location step for one local QA identity.
   * Existing account, verification, avatar, and public identity state remain.
   *
   * ## OPTIONS
   *
   * <email>
   * : The one explicit local QA email address.
   */
  public function replay_location($args, $assoc_args) {
    $this->require_ddev();
    $email = $this->target_email($args);
    $user = get_user_by('email', $email);
    if (!$user) WP_CLI::error('No matching local identity was found.');
    $user_id = (int) $user->ID;
    delete_user_meta($user_id, TNet_Identity_Service::PROFILE_COUNTRY_CODE_META);
    delete_user_meta($user_id, TNet_Identity_Service::PROFILE_REGION_CODE_META);
    update_user_meta($user_id, TNet_Identity_Service::LOCATION_PENDING_META, '1');
    $this->emit([
      'query' => ['email' => $email],
      'replay_location' => 'complete',
      'mutated' => true,
      'user_id' => $user_id,
      'location_pending' => true,
      'location' => TNet_Identity_Service::location($user_id),
    ]);
  }

  private function target_email($args) {
    if (count($args) !== 1) WP_CLI::error('Provide exactly one explicit email address. Wildcard or bulk reset is not supported.');
    $email = sanitize_email((string) $args[0]);
    if (!is_email($email)) WP_CLI::error('The explicit target must be a valid email address.');
    return $email;
  }

  private function require_ddev() {
    if (getenv('DDEV_PROJECT') === false || trim((string) getenv('DDEV_PROJECT')) === '' || getenv('IS_DDEV_PROJECT') !== 'true') {
      WP_CLI::error('Identity reset is disabled outside an approved local DDEV runtime.');
    }
  }

  private function emit(array $payload) {
    WP_CLI::log(wp_json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }
}
