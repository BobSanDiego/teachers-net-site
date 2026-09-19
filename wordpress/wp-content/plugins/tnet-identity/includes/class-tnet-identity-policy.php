<?php

defined('ABSPATH') || exit;

/**
 * Canonical server-side policy for public account identity.
 *
 * Usernames are permanent, unique handles. Display names are non-unique
 * presentation text. Browser validation deliberately mirrors only safe syntax
 * feedback; these methods remain the authority for every persistence path.
 */
final class TNet_Identity_Policy {
  const USERNAME_MIN_LENGTH = 3;
  const USERNAME_MAX_LENGTH = 30;
  const DISPLAY_NAME_MIN_LENGTH = 2;
  const DISPLAY_NAME_MAX_LENGTH = 50;

  private static function reservation_sets() {
    return [
      'platform' => [
        'teachersnet', 'tnet', 'admin', 'administrator', 'moderator', 'mod',
        'staff', 'support', 'help', 'security', 'system', 'official',
        'webmaster', 'postmaster',
      ],
      'generic' => [
        'teacher', 'teachers', 'tutor', 'tutors', 'professor', 'professors',
        'instructor', 'instructors', 'principal', 'principals', 'dean', 'deans',
        'student', 'students', 'pupil', 'pupils', 'educator', 'educators',
        'faculty', 'superintendent', 'superintendents', 'counselor', 'counselors',
        'librarian', 'librarians', 'substitute', 'substitutes', 'job', 'jobs',
        'lesson', 'lessons', 'lessonplan', 'lessonplans', 'staff', 'staffroom',
        'lounge', 'school', 'schools', 'schoolroom', 'schoolhouse', 'classroom',
        'classrooms', 'education', 'teaching', 'curriculum', 'resource', 'resources',
        'community', 'chat', 'chatboard', 'chatboards', 'mentor', 'mentors',
        'profile', 'account', 'notification', 'notifications', 'message', 'messages',
        'mail', 'news', 'career', 'careers', 'employment', 'recruiter', 'recruiters',
        'employer', 'employers', 'math', 'maths', 'mathematics', 'reading', 'english',
        'science', 'art', 'music', 'language', 'languages', 'manipulatives',
        'homework', 'discipline',
      ],
      'automation' => [
        'bot', 'tbot', 'tnetbot', 'teacherbot', 'ai', 'codex', 'chatgpt',
        'claude', 'gemini',
      ],
      'staff' => [
        'bob', 'robert', 'br', 'btr', 'bob.reap', 'bobreap', 'bobr', 'reap',
        'rreap', 'breap', 'tony', 'bott', 'tonybott', 'tony.bott', 'tonyb',
        'tb', 'tbott', 'dr.bott', 'kat', 'kathleen', 'kat.carpenter',
        'kathleen.carpenter', 'ron', 'mary',
      ],
    ];
  }

  public static function normalize_username($value) {
    return strtolower(trim((string) $value));
  }

  /** Conservative comparison key: case, accepted separators and basic leetspeak only. */
  public static function reservation_key($value) {
    $key = self::normalize_username($value);
    $key = preg_replace('/[^a-z0-9]/', '', $key);
    return strtr($key, ['0' => 'o', '1' => 'i', '3' => 'e', '4' => 'a', '5' => 's', '7' => 't']);
  }

  public static function username_reservation_category($value) {
    $key = self::reservation_key($value);
    if ($key === '') return null;
    foreach (self::reservation_sets() as $category => $entries) {
      foreach ($entries as $entry) {
        if ($key === self::reservation_key($entry)) return $category;
      }
    }
    // Only platform/system identities receive combination protection; the
    // generic namespace remains exact so ordinary descriptive handles survive.
    foreach (['teachersnet', 'tnet'] as $brand) {
      if (strpos($key, $brand) === 0 && preg_match('/(admin|administrator|moderator|mod|staff|support|help|security|system|official|webmaster|postmaster)/', $key)) {
        return 'platform';
      }
    }
    return null;
  }

  public static function validate_username($value, $allow_override = false) {
    $username = self::normalize_username($value);
    if (!preg_match('/^[a-z0-9._-]{3,30}$/D', $username)) {
      return new WP_Error('tnet_identity_username_invalid', __('Use 3–30 lowercase letters, numbers, periods, underscores, or hyphens.', 'tnet-identity'));
    }
    $override = $allow_override && current_user_can('manage_options') && apply_filters('tnet_identity_reserved_username_override', false, $username);
    if (!$override && self::username_reservation_category($username) !== null) {
      return new WP_Error('tnet_identity_username_reserved', __('That username is unavailable. Please choose another.', 'tnet-identity'));
    }
    if (username_exists($username)) {
      return new WP_Error('tnet_identity_username_exists', __('That username is already in use. Please choose another.', 'tnet-identity'));
    }
    return $username;
  }

  public static function normalize_display_name($value) {
    return preg_replace('/\s+/u', ' ', trim((string) $value));
  }

  public static function validate_display_name($value) {
    $raw = (string) $value;
    if ($raw !== wp_strip_all_tags($raw) || preg_match('/[\p{Cc}\p{Cf}\p{Zl}\p{Zp}]/u', $raw)) {
      return new WP_Error('tnet_identity_display_name_characters', __('Use letters, numbers, spaces, and basic punctuation only.', 'tnet-identity'));
    }
    $display_name = self::normalize_display_name($raw);
    $length = function_exists('mb_strlen') ? mb_strlen($display_name) : strlen($display_name);
    if ($display_name === '') {
      return new WP_Error('tnet_identity_display_name_required', __('Enter the name other members should see.', 'tnet-identity'));
    }
    if ($length < self::DISPLAY_NAME_MIN_LENGTH || $length > self::DISPLAY_NAME_MAX_LENGTH) {
      return new WP_Error('tnet_identity_display_name_length', sprintf(__('Use %1$d–%2$d characters.', 'tnet-identity'), self::DISPLAY_NAME_MIN_LENGTH, self::DISPLAY_NAME_MAX_LENGTH));
    }
    if (!preg_match('/[\p{L}\p{N}]/u', $display_name) || !preg_match("/^[\\p{L}\\p{M}\\p{N} .,'’()&-]+$/u", $display_name)) {
      return new WP_Error('tnet_identity_display_name_characters', __('Use letters, numbers, spaces, and basic punctuation only.', 'tnet-identity'));
    }
    if (self::display_name_impersonates_platform($display_name)) {
      return new WP_Error('tnet_identity_display_name_reserved', __('That display name is unavailable. Please choose another.', 'tnet-identity'));
    }
    return $display_name;
  }

  private static function display_name_impersonates_platform($value) {
    $key = self::reservation_key($value);
    if ($key === '') return false;
    foreach (['teachersnet', 'tnet'] as $brand) {
      if (strpos($key, $brand) === 0 && preg_match('/(admin|administrator|moderator|mod|staff|support|help|security|system|official|webmaster|postmaster)/', $key)) return true;
    }
    foreach (['admin', 'administrator', 'moderator', 'mod', 'staff', 'support', 'help', 'security', 'system', 'official', 'webmaster', 'postmaster'] as $identity) {
      if ($key === $identity) return true;
    }
    return false;
  }
}
