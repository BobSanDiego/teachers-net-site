<?php

defined('ABSPATH') || exit;

final class TNet_Identity_Username_Policy {
  public static function normalize($value) {
    return TNet_Identity_Policy::normalize_username($value);
  }

  public static function validate($value, $allow_override = false) {
    return TNet_Identity_Policy::validate_username($value, $allow_override);
  }

  public static function is_reserved($username) {
    return TNet_Identity_Policy::username_reservation_category($username) !== null;
  }
}
