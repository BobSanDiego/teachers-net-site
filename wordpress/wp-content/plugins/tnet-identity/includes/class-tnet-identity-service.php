<?php

defined('ABSPATH') || exit;

final class TNet_Identity_Service {
  const VERIFICATION_TTL = 86400;
  const RESEND_INTERVAL = 60;
  const AVATAR_PENDING_META = '_tnet_identity_avatar_pending';
  const PUBLIC_IDENTITY_PENDING_META = '_tnet_identity_public_identity_pending';
  const LOCATION_PENDING_META = '_tnet_identity_location_pending';
  const PROFILE_COUNTRY_CODE_META = '_tnet_profile_country_code';
  const PROFILE_REGION_CODE_META = '_tnet_profile_region_code';

  public static function create_account(array $data) {
    $email = sanitize_email((string) ($data['email'] ?? ''));
    $password = (string) ($data['password'] ?? '');
    $username = TNet_Identity_Policy::validate_username($data['username'] ?? '');
    if (is_wp_error($username)) return $username;
    if (!is_email($email)) return new WP_Error('tnet_identity_email_invalid', __('Enter a valid email address.', 'tnet-identity'));
    if (email_exists($email)) return new WP_Error('tnet_identity_email_exists', __('An account could not be created with that email address. Try logging in instead.', 'tnet-identity'));
    if (strlen($password) < 8) return new WP_Error('tnet_identity_password_short', __('Use a password of at least 8 characters.', 'tnet-identity'));
    $user_id = wp_insert_user([
      'user_login' => $username,
      'user_email' => $email,
      'user_pass' => $password,
      'display_name' => $username,
      'role' => get_option('default_role', 'subscriber'),
    ]);
    if (is_wp_error($user_id)) return $user_id;
    update_user_meta((int) $user_id, self::AVATAR_PENDING_META, '1');
    update_user_meta((int) $user_id, self::PUBLIC_IDENTITY_PENDING_META, '1');
    update_user_meta((int) $user_id, self::LOCATION_PENDING_META, '1');
    $verification = self::issue_verification($user_id, 'account', true);
    if (is_wp_error($verification)) return $verification;
    return ['user_id' => (int) $user_id, 'verification_token' => $verification['token'], 'mail_sent' => $verification['mail_sent']];
  }

  public static function needs_avatar($user_id) {
    return absint($user_id) > 0 && get_user_meta(absint($user_id), self::AVATAR_PENDING_META, true) === '1';
  }

  public static function complete_avatar($user_id) {
    delete_user_meta(absint($user_id), self::AVATAR_PENDING_META);
  }

  public static function needs_public_identity($user_id) {
    return absint($user_id) > 0 && get_user_meta(absint($user_id), self::PUBLIC_IDENTITY_PENDING_META, true) === '1';
  }

  public static function complete_public_identity($user_id) {
    delete_user_meta(absint($user_id), self::PUBLIC_IDENTITY_PENDING_META);
  }

  public static function needs_location($user_id) {
    return absint($user_id) > 0 && get_user_meta(absint($user_id), self::LOCATION_PENDING_META, true) === '1';
  }

  public static function complete_location($user_id) {
    delete_user_meta(absint($user_id), self::LOCATION_PENDING_META);
  }

  public static function location($user_id) {
    $user_id = absint($user_id);
    return [
      'country_code' => (string) get_user_meta($user_id, self::PROFILE_COUNTRY_CODE_META, true),
      'region_code' => (string) get_user_meta($user_id, self::PROFILE_REGION_CODE_META, true),
    ];
  }

  /** Persist only an explicit active Screen 5 choice. */
  public static function save_location($user_id, $mode, $selection) {
    $user_id = absint($user_id);
    if (!$user_id || !get_user_by('id', $user_id)) {
      return new WP_Error('tnet_identity_user_missing', __('The account could not be found.', 'tnet-identity'));
    }
    $mode = sanitize_key((string) $mode);
    if ($mode === 'us') {
      $region = TNet_Identity_Location_Policy::normalize_region($selection);
      if ($region === '') return new WP_Error('tnet_identity_location_state_required', __('Select your state before continuing.', 'tnet-identity'));
      update_user_meta($user_id, self::PROFILE_COUNTRY_CODE_META, 'US');
      update_user_meta($user_id, self::PROFILE_REGION_CODE_META, $region);
      return ['country_code' => 'US', 'region_code' => $region];
    }
    if ($mode === 'international') {
      $country = TNet_Identity_Location_Policy::normalize_country($selection);
      if ($country === '') return new WP_Error('tnet_identity_location_country_required', __('Select your country before continuing.', 'tnet-identity'));
      update_user_meta($user_id, self::PROFILE_COUNTRY_CODE_META, $country);
      delete_user_meta($user_id, self::PROFILE_REGION_CODE_META);
      return ['country_code' => $country, 'region_code' => null];
    }
    return new WP_Error('tnet_identity_location_mode_invalid', __('Choose a location option before continuing.', 'tnet-identity'));
  }

  public static function update_display_name($user_id, $display_name) {
    $user_id = absint($user_id);
    if (!$user_id || !get_user_by('id', $user_id)) {
      return new WP_Error('tnet_identity_user_missing', __('The account could not be found.', 'tnet-identity'));
    }
    $display_name = TNet_Identity_Policy::validate_display_name($display_name);
    if (is_wp_error($display_name)) return $display_name;
    $updated = wp_update_user(['ID' => $user_id, 'display_name' => $display_name]);
    if (is_wp_error($updated)) return $updated;
    return $display_name;
  }

  public static function is_email_verified($user_id) {
    global $wpdb;
    $user_id = absint($user_id);
    if (!$user_id) return false;
    $table = TNet_Identity::table('verification');
    return (bool) $wpdb->get_var($wpdb->prepare("SELECT id FROM {$table} WHERE user_id = %d AND used_at IS NOT NULL ORDER BY used_at DESC LIMIT 1", $user_id));
  }

  public static function issue_verification($user_id, $purpose = 'account', $force = false) {
    return self::issue_verification_for_event($user_id, $purpose, $force);
  }

  private static function issue_verification_for_event($user_id, $purpose = 'account', $force = false) {
    global $wpdb;
    $user = get_user_by('id', absint($user_id));
    if (!$user) return new WP_Error('tnet_identity_user_missing', __('The account could not be found.', 'tnet-identity'));
    $table = TNet_Identity::table('verification');
    $now = time();
    $issued = gmdate('Y-m-d H:i:s', $now);
    $expires = gmdate('Y-m-d H:i:s', $now + self::VERIFICATION_TTL);
    if (!$force) {
      $latest = $wpdb->get_var($wpdb->prepare("SELECT issued_at FROM {$table} WHERE user_id = %d AND used_at IS NULL ORDER BY issued_at DESC, id DESC LIMIT 1", (int) $user->ID));
      if ($latest && (strtotime((string) $latest . ' UTC') + self::RESEND_INTERVAL) > $now) {
        return new WP_Error('tnet_identity_resend_throttled', __('Please wait a moment before requesting another code.', 'tnet-identity'));
      }
    }
    $wpdb->query($wpdb->prepare("UPDATE {$table} SET used_at = %s WHERE user_id = %d AND used_at IS NULL", $issued, (int) $user->ID));
    $token = wp_generate_password(48, false, false);
    $confirmation_code = (string) wp_rand(100000, 999999);
    $inserted = $wpdb->insert($table, [
      'user_id' => (int) $user->ID,
      'token_hash' => hash('sha256', $token),
      'purpose' => sanitize_key($purpose),
      'issued_at' => $issued,
      'expires_at' => $expires,
      'confirmation_code_hash' => hash('sha256', $confirmation_code),
      'code_expires_at' => $expires,
    ], ['%d', '%s', '%s', '%s', '%s', '%s', '%s']);
    if (!$inserted) return new WP_Error('tnet_identity_verification_store_failed', __('The verification request could not be saved.', 'tnet-identity'));
    $url = TNet_Identity_Public::verify_url($token);
    $sent = wp_mail($user->user_email, __('Verify your Teachers.Net account', 'tnet-identity'), sprintf("Welcome to Teachers.Net.\n\nYour confirmation code is: %s\n\nVerify your account here:\n\n%s\n\nThis code and link expire in 24 hours.", $confirmation_code, $url));
    return ['token' => $token, 'mail_sent' => (bool) $sent];
  }

  public static function verify_code($email, $code) {
    global $wpdb;
    $email = sanitize_email((string) $email);
    $code = trim((string) $code);
    if (!preg_match('/^\d{6}$/D', $code)) {
      return new WP_Error('tnet_identity_code_malformed', __('Enter the 6-digit confirmation code from your email.', 'tnet-identity'));
    }
    $user = get_user_by('email', $email);
    if (!$user) return new WP_Error('tnet_identity_code_invalid', __('That confirmation code is invalid or expired.', 'tnet-identity'));
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . TNet_Identity::table('verification') . ' WHERE user_id = %d ORDER BY issued_at DESC, id DESC LIMIT 1', (int) $user->ID), ARRAY_A);
    if (!$row || !empty($row['used_at'])) return new WP_Error('tnet_identity_code_used', __('That confirmation request has already been used. Request a new code if needed.', 'tnet-identity'));
    if (strtotime((string) $row['expires_at'] . ' UTC') < time() || empty($row['confirmation_code_hash']) || (!empty($row['code_expires_at']) && strtotime((string) $row['code_expires_at'] . ' UTC') < time())) {
      return new WP_Error('tnet_identity_code_expired', __('That confirmation code has expired. Request a new code.', 'tnet-identity'));
    }
    if (!hash_equals((string) $row['confirmation_code_hash'], hash('sha256', $code))) {
      return new WP_Error('tnet_identity_code_incorrect', __('That confirmation code is incorrect.', 'tnet-identity'));
    }
    $updated = $wpdb->query($wpdb->prepare('UPDATE ' . TNet_Identity::table('verification') . ' SET used_at = %s WHERE id = %d AND used_at IS NULL', gmdate('Y-m-d H:i:s'), (int) $row['id']));
    if ($updated !== 1) return new WP_Error('tnet_identity_code_used', __('That confirmation request has already been used. Request a new code if needed.', 'tnet-identity'));
    return ['user_id' => (int) $user->ID];
  }

  public static function verify_token($token) {
    global $wpdb;
    $token = sanitize_text_field((string) $token);
    $table = TNet_Identity::table('verification');
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE token_hash = %s LIMIT 1", hash('sha256', $token)), ARRAY_A);
    if (!$row) return new WP_Error('tnet_identity_verification_invalid', __('That verification link is invalid.', 'tnet-identity'));
    if (!empty($row['used_at'])) return new WP_Error('tnet_identity_verification_used', __('That verification link has already been used.', 'tnet-identity'));
    if (strtotime($row['expires_at'] . ' UTC') < time()) return new WP_Error('tnet_identity_verification_expired', __('That verification link has expired. Request a new one below.', 'tnet-identity'));
    $updated = $wpdb->query($wpdb->prepare("UPDATE {$table} SET used_at = %s WHERE id = %d AND used_at IS NULL", gmdate('Y-m-d H:i:s'), absint($row['id'])));
    if ($updated !== 1) return new WP_Error('tnet_identity_verification_used', __('That verification link has already been used.', 'tnet-identity'));
    return ['user_id' => absint($row['user_id'])];
  }

  public static function resend_verification($token) {
    global $wpdb;
    $table = TNet_Identity::table('verification');
    $row = $wpdb->get_row($wpdb->prepare("SELECT user_id FROM {$table} WHERE token_hash = %s LIMIT 1", hash('sha256', sanitize_text_field((string) $token))), ARRAY_A);
    if (!$row) return new WP_Error('tnet_identity_verification_invalid', __('That verification request is no longer available.', 'tnet-identity'));
    return self::issue_verification(absint($row['user_id']), 'account');
  }

  public static function resend_verification_by_email($email) {
    $user = get_user_by('email', sanitize_email((string) $email));
    if (!$user || self::is_email_verified($user->ID)) return ['mail_sent' => true];
    return self::issue_verification($user->ID, 'account');
  }

  public static function change_pending_email($user_email, $new_email) {
    $user_email = sanitize_email((string) $user_email);
    $new_email = sanitize_email((string) $new_email);
    if (!is_email($new_email)) return new WP_Error('tnet_identity_email_invalid', __('Enter a valid email address.', 'tnet-identity'));
    $user = get_user_by('email', $user_email);
    if (!$user || self::is_email_verified($user->ID)) return new WP_Error('tnet_identity_email_change_unavailable', __('This pending confirmation is no longer available.', 'tnet-identity'));
    $existing = get_user_by('email', $new_email);
    if ($existing && (int) $existing->ID !== (int) $user->ID) return new WP_Error('tnet_identity_email_exists', __('That email address is already in use. Choose another.', 'tnet-identity'));
    if (strcasecmp($user->user_email, $new_email) === 0) return new WP_Error('tnet_identity_email_same', __('Enter a different email address.', 'tnet-identity'));
    $updated = wp_update_user(['ID' => (int) $user->ID, 'user_email' => $new_email]);
    if (is_wp_error($updated)) return $updated;
    $verification = self::issue_verification_for_event($user->ID, 'account', true);
    if (is_wp_error($verification)) {
      wp_update_user(['ID' => (int) $user->ID, 'user_email' => $user->user_email]);
      return $verification;
    }
    return ['user_id' => (int) $user->ID, 'email' => $new_email, 'mail_sent' => $verification['mail_sent']];
  }
}

final class TNet_Identity_Continuation_Service {
  const TTL = 86400;

  public static function create($purpose, $route_key, array $context = [], $user_id = 0) {
    global $wpdb;
    $route_key = sanitize_key($route_key);
    if (!self::allowed_route($route_key)) return new WP_Error('tnet_identity_destination_invalid', __('That continuation destination is not registered.', 'tnet-identity'));
    $purpose = sanitize_key($purpose);
    if (!in_array($purpose, ['general_member', 'jobs_recruiter', 'future_context'], true)) return new WP_Error('tnet_identity_purpose_invalid', __('That continuation purpose is not registered.', 'tnet-identity'));
    $safe_context = self::bounded_context($context);
    $token = wp_generate_password(48, false, false);
    $now = time();
    $wpdb->insert(TNet_Identity::table('continuation'), [
      'user_id' => absint($user_id) ?: null,
      'purpose' => $purpose,
      'action' => $route_key,
      'route_key' => $route_key,
      'context_json' => wp_json_encode($safe_context),
      'token_hash' => hash('sha256', $token),
      'issued_at' => gmdate('Y-m-d H:i:s', $now),
      'expires_at' => gmdate('Y-m-d H:i:s', $now + self::TTL),
    ], ['%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s']);
    if (!$wpdb->insert_id) return new WP_Error('tnet_identity_continuation_store_failed', __('The return intent could not be saved.', 'tnet-identity'));
    return $token;
  }

  public static function attach_user($token, $user_id) {
    global $wpdb;
    return $wpdb->query($wpdb->prepare("UPDATE " . TNet_Identity::table('continuation') . " SET user_id = %d WHERE token_hash = %s AND consumed_at IS NULL", absint($user_id), hash('sha256', sanitize_text_field((string) $token)))) === 1;
  }

  public static function resolve($token, $user_id = 0) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . TNet_Identity::table('continuation') . " WHERE token_hash = %s LIMIT 1", hash('sha256', sanitize_text_field((string) $token))), ARRAY_A);
    if (!$row) return new WP_Error('tnet_identity_continuation_invalid', __('That return link is invalid.', 'tnet-identity'));
    if (!empty($row['consumed_at'])) return new WP_Error('tnet_identity_continuation_consumed', __('That return link has already been used.', 'tnet-identity'));
    if (strtotime($row['expires_at'] . ' UTC') < time()) return new WP_Error('tnet_identity_continuation_expired', __('That return link has expired.', 'tnet-identity'));
    if (!empty($row['user_id']) && absint($row['user_id']) !== absint($user_id)) return new WP_Error('tnet_identity_continuation_user_mismatch', __('That return link belongs to another account.', 'tnet-identity'));
    $row['context'] = json_decode((string) $row['context_json'], true) ?: [];
    $row['token'] = sanitize_text_field((string) $token);
    return $row;
  }

  public static function consume($token, $user_id = 0) {
    global $wpdb;
    $row = self::resolve($token, $user_id);
    if (is_wp_error($row)) return $row;
    $ok = $wpdb->query($wpdb->prepare("UPDATE " . TNet_Identity::table('continuation') . " SET consumed_at = %s WHERE id = %d AND consumed_at IS NULL", gmdate('Y-m-d H:i:s'), absint($row['id'])));
    if ($ok !== 1) return new WP_Error('tnet_identity_continuation_consumed', __('That return link has already been used.', 'tnet-identity'));
    $row['destination_url'] = self::destination_url($row['route_key'], $row['context']);
    return $row;
  }

  public static function consume_for_user($user_id) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . TNet_Identity::table('continuation') . " WHERE user_id = %d AND consumed_at IS NULL AND expires_at >= %s ORDER BY issued_at DESC LIMIT 1", absint($user_id), gmdate('Y-m-d H:i:s')), ARRAY_A);
    if (!$row) return null;
    $ok = $wpdb->query($wpdb->prepare("UPDATE " . TNet_Identity::table('continuation') . " SET consumed_at = %s WHERE id = %d AND consumed_at IS NULL", gmdate('Y-m-d H:i:s'), absint($row['id'])));
    if ($ok !== 1) return null;
    $row['context'] = json_decode((string) $row['context_json'], true) ?: [];
    $row['destination_url'] = self::destination_url($row['route_key'], $row['context']);
    return $row;
  }

  public static function auth_gate_urls($purpose, $destination_url, array $context = []) {
    $route_key = self::route_key_for_url($destination_url);
    if (!$route_key) return new WP_Error('tnet_identity_destination_invalid', __('That Jobs destination is not registered.', 'tnet-identity'));
    $context = array_merge(self::context_from_url($destination_url), $context);
    $login_token = self::create($purpose, $route_key, $context);
    $signup_token = self::create($purpose, $route_key, $context);
    if (is_wp_error($login_token) || is_wp_error($signup_token)) return new WP_Error('tnet_identity_gate_failed', __('The account continuation could not be prepared.', 'tnet-identity'));
    return ['login' => wp_login_url(TNet_Identity_Public::resume_url($login_token)), 'signup' => TNet_Identity_Public::signup_url($signup_token)];
  }

  public static function route_key_for_url($url) {
    $path = trim((string) wp_parse_url($url, PHP_URL_PATH), '/');
    if ($path === 'jobs') return 'jobs_browse';
    if ($path === 'jobs/employer' || $path === 'jobs/employer/my-jobs') return 'jobs_employer_my_jobs';
    if ($path === 'jobs/employer/schools') return 'jobs_employer_schools';
    if ($path === 'jobs/employer/new') return 'jobs_employer_new';
    if ($path === 'jobs/employer/claim') return 'jobs_employer_claim';
    if ($path === 'jobs/employer/request-access') return 'jobs_employer_request_access';
    if (preg_match('#^jobs/employer/[0-9]+/edit$#', $path)) return 'jobs_employer_edit';
    return null;
  }

  private static function allowed_route($route_key) {
    return in_array($route_key, ['jobs_browse', 'jobs_employer_my_jobs', 'jobs_employer_schools', 'jobs_employer_new', 'jobs_employer_claim', 'jobs_employer_request_access', 'jobs_employer_edit'], true);
  }

  private static function bounded_context(array $context) {
    $safe = [];
    foreach (['employer_id', 'job_id', 'resource_id'] as $key) if (!empty($context[$key]) && absint($context[$key])) $safe[$key] = absint($context[$key]);
    return $safe;
  }

  private static function context_from_url($url) {
    $query = [];
    parse_str((string) wp_parse_url($url, PHP_URL_QUERY), $query);
    return self::bounded_context($query);
  }

  private static function destination_url($route_key, array $context) {
    $routes = [
      'jobs_browse' => '/jobs/',
      'jobs_employer_my_jobs' => '/jobs/employer/my-jobs/',
      'jobs_employer_schools' => '/jobs/employer/schools/',
      'jobs_employer_new' => '/jobs/employer/new/',
      'jobs_employer_claim' => '/jobs/employer/claim/',
      'jobs_employer_request_access' => '/jobs/employer/request-access/',
      'jobs_employer_edit' => !empty($context['job_id']) ? '/jobs/employer/' . absint($context['job_id']) . '/edit/' : '/jobs/employer/my-jobs/',
    ];
    $url = home_url($routes[$route_key] ?? '/jobs/');
    foreach (['employer_id', 'resource_id'] as $key) if (!empty($context[$key])) $url = add_query_arg($key, absint($context[$key]), $url);
    return $url;
  }
}
