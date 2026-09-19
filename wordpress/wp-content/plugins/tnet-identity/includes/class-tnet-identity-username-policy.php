<?php

defined('ABSPATH') || exit;

final class TNet_Identity_Username_Policy {
  public static function normalize($value) {
    return strtolower(trim((string) $value));
  }

  public static function validate($value, $allow_override = false) {
    $username = self::normalize($value);
    if (!preg_match('/^[a-z0-9._-]{3,30}$/D', $username)) {
      return new WP_Error('tnet_identity_username_invalid', __('Use 3–30 lowercase letters, numbers, periods, underscores, or hyphens.', 'tnet-identity'));
    }
    $override = $allow_override && current_user_can('manage_options') && apply_filters('tnet_identity_reserved_username_override', false, $username);
    if (!$override && self::is_reserved($username)) {
      return new WP_Error('tnet_identity_username_reserved', __('That username is reserved. Please choose another.', 'tnet-identity'));
    }
    if (username_exists($username)) {
      return new WP_Error('tnet_identity_username_exists', __('That username is already in use. Please choose another.', 'tnet-identity'));
    }
    return $username;
  }

  public static function is_reserved($username) {
    $username = self::normalize($username);
    $compact = preg_replace('/[^a-z0-9]/', '', $username);
    $reserved = [
      'admin', 'administrator', 'support', 'help', 'system', 'security', 'account', 'profile', 'api', 'login', 'logout', 'signup', 'register', 'notifications', 'notification', 'password', 'settings', 'wordpress', 'root',
      'teachersnet', 'teachers-net', 'tnet', 'teacherchat', 'jobcenter', 'jobs', 'community', 'lessonbank', 'lessons', 'chatboard', 'chatboards', 'employer', 'employers', 'school', 'schools', 'jobsite', 'jobsites',
      'moderator', 'mod', 'staff', 'official', 'verified', 'reviewer', 'editor', 'supportteam', 'trust', 'safety', 'adminteam',
      'teacher', 'teachers', 'principal', 'superintendent', 'counselor', 'substitute', 'tutor', 'professor', 'educator', 'education', 'schooladmin',
      'ai', 'bot', 'assistant', 'teachersnetai', 'tnetai', 'aiassistant', 'moderatorbot',
    ];
    if (in_array($username, $reserved, true) || in_array($compact, array_map(static function ($item) { return preg_replace('/[^a-z0-9]/', '', $item); }, $reserved), true)) {
      return true;
    }
    $protected_prefixes = ['teachersnet', 'tnet', 'official', 'support', 'admin', 'moderator', 'verified', 'staff', 'security'];
    $protected_terms = ['admin', 'support', 'moderator', 'official', 'security', 'staff', 'help', 'team', 'trust'];
    foreach ($protected_prefixes as $prefix) {
      if (strpos($compact, $prefix) === 0) {
        foreach ($protected_terms as $term) {
          if (strpos($compact, $term) !== false) return true;
        }
      }
    }
    return false;
  }
}
