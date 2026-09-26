<?php

defined('ABSPATH') || exit;

/** Current member's public-view route; Profile facts retain their existing privacy policy. */
final class TNet_Profile_Public {
  const QUERY_VAR = 'tnet_profile_public_route';

  public static function init() {
    add_action('init', [__CLASS__, 'register_route']);
    add_filter('query_vars', [__CLASS__, 'query_vars']);
    add_action('template_redirect', [__CLASS__, 'render_route']);
  }

  public static function register_route() {
    add_rewrite_rule('^profile/?$', 'index.php?' . self::QUERY_VAR . '=view', 'top');
  }

  public static function query_vars($vars) {
    $vars[] = self::QUERY_VAR;
    return $vars;
  }

  public static function render_route() {
    if (get_query_var(self::QUERY_VAR) !== 'view') return;
    if (!is_user_logged_in()) {
      wp_safe_redirect(wp_login_url(home_url('/profile/')));
      exit;
    }
    $user = get_user_by('id', get_current_user_id());
    $state = TNet_Profile_Basics::state((int) $user->ID);
    $roles = TNet_Profile_Basics::professional_identity_terms();
    TNet_Profile_Basics::enqueue_assets();
    TNet_Profile_Enrichment::enqueue_assets();
    $main = static function () use ($user, $state, $roles) {
      echo '<main class="tnet-profile-enrichment-main tnet-profile-enrichment-main--public">';
      echo '<header class="tnet-profile-basics-heading"><h1>' . esc_html__('Your Profile', 'tnet-profile') . '</h1><p>' . esc_html__('This is how your public Profile appears to other members.', 'tnet-profile') . '</p></header>';
      TNet_Profile_Enrichment::render_profile_card($user, $state, $roles, [
        'public_view' => true,
        'return_to' => add_query_arg('avatar_modal', '1', home_url('/profile/')),
      ]);
      echo '</main>';
    };
    if (class_exists('TNet_Shared_Shell')) {
      TNet_Shared_Shell::render_host(TNet_Profile_Basics::member_shell_config(__('Your Profile', 'tnet-profile'), $user, $state, $main));
      exit;
    }
    $main();
    exit;
  }
}
