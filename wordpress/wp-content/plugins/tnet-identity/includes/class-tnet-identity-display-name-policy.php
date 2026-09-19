<?php

defined('ABSPATH') || exit;

final class TNet_Identity_Display_Name_Policy {
  const MAX_LENGTH = TNet_Identity_Policy::DISPLAY_NAME_MAX_LENGTH;

  /**
   * Display names are presentation text, never a unique login or lookup key.
   * Keep this intentionally small, readable, and Unicode-aware; persistence
   * remains on WordPress's canonical user display_name field.
   */
  public static function validate($value) {
    return TNet_Identity_Policy::validate_display_name($value);
  }
}
