<?php

defined('ABSPATH') || exit;

/**
 * Profile-owned V1 Basics write boundary and authenticated editor route.
 *
 * Identity, avatar media, location values, and Core Terms remain delegated to
 * their canonical owners. This class only coordinates Profile assertions and
 * visibility/scalar policy for the currently authenticated member.
 */
final class TNet_Profile_Basics {
  const QUERY_VAR = 'tnet_profile_basics_route';
  const ROUTE = 'edit';
  const NONCE = 'tnet_profile_basics_save';

  /** Historical label fallback only; this is not a live choice catalog. */
  private const LEGACY_PROFESSIONAL_IDENTITY_LABELS = [
    '1fcd2265-c4a1-47fd-97fc-6d3844e18952' => 'Teacher',
    '0838edba-879b-46c6-9e51-32fdce5dabcc' => 'Administrator',
    'f71d9a50-d539-436d-98e0-63325a22af1d' => 'Master Teacher',
    '5247b9d7-092b-4f3b-b864-7d285355c327' => 'Mentor Teacher',
    '629c3326-cff1-47d9-8015-f8b558204052' => 'Retired Educator / Teacher',
    'def4ee6a-a8ac-4db5-b86a-d8e4eb872c79' => 'Education Student',
    '0bc2b6f4-16e0-4fe2-8c23-9b97b4236b1e' => 'Student Teacher',
    'bfd5228d-4dda-4111-b123-4f6b867a840e' => 'Counselor',
    '52bd64ef-7207-4294-9e04-a24c6546f13e' => 'Tutor',
    'f533b2c8-e995-4c10-9756-79d1a5632f00' => 'Librarian / Media Specialist',
    '62c8c62a-082a-45b2-8654-03d9cc6ed23a' => 'Instructional Coach',
    'd987c1c0-9322-4021-bd7f-47d2e9a58af7' => 'Substitute Teacher',
  ];

  public static function init() {
    add_action('init', [__CLASS__, 'register_route']);
    add_filter('query_vars', [__CLASS__, 'query_vars']);
    add_action('template_redirect', [__CLASS__, 'render_route']);
  }

  public static function register_route() {
    add_rewrite_rule('^profile/edit/?$', 'index.php?' . self::QUERY_VAR . '=' . self::ROUTE, 'top');
    add_rewrite_rule('^profile/enrichment/basics/?$', 'index.php?' . self::QUERY_VAR . '=basics', 'top');
  }

  public static function query_vars($vars) {
    $vars[] = self::QUERY_VAR;
    return $vars;
  }

  public static function edit_url($guided = false) {
    return $guided ? self::guided_url() : home_url('/profile/edit/');
  }

  public static function guided_url() { return home_url('/profile/enrichment/basics/'); }

  public static function render_route() {
    $route = (string) get_query_var(self::QUERY_VAR);
    if (!in_array($route, ['edit', 'basics'], true)) return;
    if ($route === 'edit' && isset($_GET['profile_basics'])) {
      wp_safe_redirect(self::guided_url(), 302);
      exit;
    }
    status_header(200);
    if (!is_user_logged_in()) {
      wp_safe_redirect(wp_login_url($route === 'basics' ? self::guided_url() : self::edit_url()));
      exit;
    }
    $user_id = get_current_user_id();
    $result = null;
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
      $result = self::save_from_request($user_id);
      if (!is_wp_error($result)) {
        $guided = !empty($_POST['profile_basics_mode']);
        $destination = $guided
          ? (!empty($_POST['journey_action']) && sanitize_key((string) wp_unslash($_POST['journey_action'])) === 'skip'
            ? TNet_Profile_Enrichment::complete_url() : TNet_Profile_Enrichment::url())
          : add_query_arg('profile_status', 'saved', self::edit_url());
        wp_safe_redirect($destination);
        exit;
      }
    }
    self::render_editor($user_id, $result, $route === 'basics');
  }

  /** The single Profile V1 write service used by editor and future Basics entry. */
  public static function save($user_id, array $input) {
    $user_id = absint($user_id);
    if (!$user_id || $user_id !== get_current_user_id() || !get_user_by('id', $user_id)) {
      return new WP_Error('tnet_profile_basics_forbidden', __('You are not allowed to update this Profile.', 'tnet-profile'));
    }
    if (!class_exists('TNet_Identity_Service') || !class_exists('TNet_Identity_Policy')) {
      return new WP_Error('tnet_profile_basics_identity_unavailable', __('Profile identity is temporarily unavailable.', 'tnet-profile'));
    }
    if (!class_exists('CFM')) {
      return new WP_Error('tnet_profile_basics_terms_unavailable', __('Core Terms is temporarily unavailable.', 'tnet-profile'));
    }

    $display_name = TNet_Identity_Policy::validate_display_name((string) ($input['display_name'] ?? ''));
    if (is_wp_error($display_name)) return $display_name;
    $bio = self::normalize_bio((string) ($input['bio'] ?? ''));
    if (is_wp_error($bio)) return $bio;
    $teaching_since = self::normalize_teaching_since($input['teaching_since'] ?? '');
    if (is_wp_error($teaching_since)) return $teaching_since;

    $sets = [];
    foreach ([
      'teaching_grade' => 'teaching_grades',
      'teaching_subject' => 'teaching_subjects',
      'professional_identity' => 'professional_identities',
    ] as $relationship => $input_key) {
      $sets[$relationship] = self::requested_uuids($input[$input_key] ?? []);
      if (is_wp_error($sets[$relationship])) return $sets[$relationship];
    }
    $role_terms = self::professional_identity_terms();
    if (!$role_terms) return new WP_Error('tnet_profile_basics_list_unavailable', __('Profile role choices are temporarily unavailable.', 'tnet-profile'));
    $existing_selected = self::state($user_id)['selected'];
    $role_allowed = array_fill_keys(array_map(static function ($term) { return (string) $term->term_uuid; }, $role_terms), true);
    foreach ($sets['professional_identity'] as $uuid) {
      if (!isset($role_allowed[$uuid]) && !in_array($uuid, $existing_selected['professional_identity'], true)) {
        return new WP_Error('tnet_profile_basics_choice_unavailable', __('A selected Profile role is no longer available.', 'tnet-profile'));
      }
    }
    // The composition governs new/editable choices, not historical assertions.
    $sets['professional_identity'] = array_values(array_filter($sets['professional_identity'], static function ($uuid) use ($role_allowed) { return isset($role_allowed[$uuid]); }));
    // Govern new Grade/Subject choices without discarding older member facts that
    // are outside the current presentation composition.
    foreach (['teaching_grade' => 'Grade Level', 'teaching_subject' => 'Subject Area'] as $relationship => $axis_label) {
      $available = self::axis_terms($axis_label);
      if (!$available) return new WP_Error('tnet_profile_basics_list_unavailable', __('Profile choices are temporarily unavailable.', 'tnet-profile'));
      $allowed = array_fill_keys(array_map(static function ($term) { return (string) $term->term_uuid; }, $available), true);
      foreach ($sets[$relationship] as $uuid) {
        if (!isset($allowed[$uuid]) && !in_array($uuid, $existing_selected[$relationship], true)) {
          return new WP_Error('tnet_profile_basics_choice_unavailable', __('A selected Profile choice is no longer available.', 'tnet-profile'));
        }
      }
      foreach ($existing_selected[$relationship] as $uuid) {
        if (!isset($allowed[$uuid]) && !in_array($uuid, $sets[$relationship], true)) $sets[$relationship][] = $uuid;
      }
    }
    // Validate every requested term before changing identity, facts, or meta.
    foreach ($sets as $relationship => $uuids) {
      foreach ($uuids as $uuid) {
        $term = TNet_Profile_Member_Context::resolve_live_core_term_identifier($uuid);
        if (is_wp_error($term)) return $term;
      }
    }

    $location_mode = sanitize_key((string) ($input['location_mode'] ?? ''));
    $location_selection = (string) ($input['location_selection'] ?? '');
    if ($location_mode !== '') {
      if ($location_mode === 'us' && TNet_Identity_Location_Policy::normalize_region($location_selection) === '') return new WP_Error('tnet_identity_location_state_required', __('Select your state before continuing.', 'tnet-identity'));
      if ($location_mode === 'international' && TNet_Identity_Location_Policy::normalize_country($location_selection) === '') return new WP_Error('tnet_identity_location_country_required', __('Select your country before continuing.', 'tnet-identity'));
      if (!in_array($location_mode, ['us', 'international'], true)) return new WP_Error('tnet_identity_location_mode_invalid', __('Choose a location option before continuing.', 'tnet-identity'));
    }

    $identity_result = TNet_Identity_Service::update_display_name($user_id, $display_name);
    if (is_wp_error($identity_result)) return $identity_result;
    foreach ($sets as $relationship => $uuids) {
      $facts = $relationship === 'professional_identity'
        ? TNet_Profile_Member_Context::replace_professional_identity_choices($user_id, $uuids, array_keys($role_allowed))
        : TNet_Profile_Member_Context::replace_facts($user_id, $relationship, $uuids);
      if (is_wp_error($facts)) return $facts;
    }
    if ($location_mode !== '') {
      $location_result = TNet_Identity_Service::save_location($user_id, $location_mode, $location_selection);
      if (is_wp_error($location_result)) return $location_result;
    }
    $location = self::location($user_id);
    self::save_scalar_meta($user_id, $teaching_since, $bio, !empty($input['details_public']), !empty($input['location_public']) && $location['exists']);
    return self::state($user_id);
  }

  public static function state($user_id) {
    $user_id = absint($user_id);
    $facts = TNet_Profile_Member_Context::facts_for_user($user_id);
    $selected = ['teaching_grade' => [], 'teaching_subject' => [], 'professional_identity' => []];
    foreach ($facts as $fact) {
      $relationship = (string) ($fact['relationship_type'] ?? '');
      $uuid = (string) ($fact['term_uuid'] ?? '');
      if (isset($selected[$relationship]) && $uuid !== '') $selected[$relationship][] = $uuid;
    }
    $scalars = TNet_Profile_Member_Context::profile_v1_scalars($user_id);
    return [
      'selected' => array_map('array_values', $selected),
      'scalars' => $scalars,
      'location' => self::location($user_id),
      'suggestions' => self::professional_suggestions($user_id),
    ];
  }

  /** Page 2 changes only its two optional facts; Page 1 identity/privacy remain untouched. */
  public static function save_enrichment($user_id, array $input) {
    $user_id = absint($user_id);
    if (!$user_id || $user_id !== get_current_user_id() || !get_user_by('id', $user_id)) {
      return new WP_Error('tnet_profile_basics_forbidden', __('You are not allowed to update this Profile.', 'tnet-profile'));
    }
    $terms = self::professional_identity_terms();
    if (!$terms) return new WP_Error('tnet_profile_basics_list_unavailable', __('Profile role choices are temporarily unavailable.', 'tnet-profile'));
    $allowed = array_values(array_map(static function ($term) { return (string) $term->term_uuid; }, $terms));
    $selected = self::requested_uuids($input['professional_identities'] ?? []);
    if (is_wp_error($selected)) return $selected;
    foreach ($selected as $uuid) {
      if (!in_array($uuid, $allowed, true)) return new WP_Error('tnet_profile_basics_choice_unavailable', __('A selected Profile role is no longer available.', 'tnet-profile'));
      $term = TNet_Profile_Member_Context::resolve_live_core_term_identifier($uuid);
      if (is_wp_error($term)) return $term;
    }
    $year = self::normalize_teaching_since($input['teaching_since'] ?? '');
    if (is_wp_error($year)) return $year;
    $facts = TNet_Profile_Member_Context::replace_professional_identity_choices($user_id, $selected, $allowed);
    if (is_wp_error($facts)) return $facts;
    if ($year === null) delete_user_meta($user_id, TNet_Profile_Member_Context::PROFILE_TEACHING_SINCE_META);
    else update_user_meta($user_id, TNet_Profile_Member_Context::PROFILE_TEACHING_SINCE_META, $year);
    return self::state($user_id);
  }

  private static function save_from_request($user_id) {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), self::NONCE)) {
      return new WP_Error('tnet_profile_basics_nonce', __('Your edit session expired. Please try again.', 'tnet-profile'));
    }
    // Guided Page 1 does not author Page 2 roles or Teaching Since. Preserve
    // those existing facts when the shared editor write service saves Page 1.
    $guided_state = !empty($_POST['profile_basics_mode']) ? self::state($user_id) : null;
    return self::save($user_id, [
      'display_name' => wp_unslash($_POST['display_name'] ?? ''),
      'teaching_grades' => wp_unslash($_POST['teaching_grades'] ?? []),
      'teaching_subjects' => wp_unslash($_POST['teaching_subjects'] ?? []),
      'professional_identities' => $guided_state ? $guided_state['selected']['professional_identity'] : wp_unslash($_POST['professional_identities'] ?? []),
      'teaching_since' => $guided_state ? $guided_state['scalars']['teaching_since'] : wp_unslash($_POST['teaching_since'] ?? ''),
      'bio' => wp_unslash($_POST['bio'] ?? ''),
      'details_public' => $guided_state ? $guided_state['scalars']['profile_details_public'] : !empty($_POST['details_public']),
      'location_public' => !empty($_POST['location_public']),
      'location_mode' => sanitize_key(wp_unslash($_POST['location_mode'] ?? '')),
      'location_selection' => sanitize_text_field(wp_unslash($_POST['location_selection'] ?? '')),
    ]);
  }

  private static function requested_uuids($values) {
    if (!is_array($values)) $values = [$values];
    $uuids = [];
    foreach ($values as $value) {
      $value = strtolower(trim(sanitize_text_field((string) $value)));
      if ($value === '') continue;
      if (!wp_is_uuid($value)) return new WP_Error('tnet_profile_basics_term_invalid', __('One of your Profile selections is invalid. Please refresh and try again.', 'tnet-profile'));
      $uuids[] = $value;
    }
    return array_values(array_unique($uuids));
  }

  private static function normalize_bio($value) {
    $value = str_replace(["\r\n", "\r"], "\n", (string) $value);
    if (preg_match('/<\/?[a-z][^>]*>/i', $value)) {
      return new WP_Error('tnet_profile_basics_bio_plain_text', __('Bio must be plain text without markup.', 'tnet-profile'));
    }
    $value = trim(wp_strip_all_tags($value));
    $length = function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
    if ($length > 500) return new WP_Error('tnet_profile_basics_bio_length', __('Bio must be 500 characters or fewer.', 'tnet-profile'));
    return $value;
  }

  private static function normalize_teaching_since($value) {
    $value = trim((string) $value);
    if ($value === '') return null;
    $year = absint($value);
    if (!preg_match('/^\d{4}$/', $value) || $year < 1900 || $year > (int) gmdate('Y')) {
      return new WP_Error('tnet_profile_basics_teaching_since', sprintf(__('Enter a year from 1900 through %d.', 'tnet-profile'), (int) gmdate('Y')));
    }
    return $year;
  }

  private static function save_scalar_meta($user_id, $teaching_since, $bio, $details_public, $location_public) {
    if ($teaching_since === null) delete_user_meta($user_id, TNet_Profile_Member_Context::PROFILE_TEACHING_SINCE_META);
    else update_user_meta($user_id, TNet_Profile_Member_Context::PROFILE_TEACHING_SINCE_META, $teaching_since);
    update_user_meta($user_id, TNet_Profile_Member_Context::PROFILE_BIO_META, $bio);
    update_user_meta($user_id, TNet_Profile_Member_Context::PROFILE_DETAILS_PUBLIC_META, $details_public ? '1' : '0');
    update_user_meta($user_id, TNet_Profile_Member_Context::PROFILE_LOCATION_PUBLIC_META, $location_public ? '1' : '0');
  }

  private static function location($user_id) {
    $location = class_exists('TNet_Identity_Service') ? TNet_Identity_Service::location($user_id) : ['country_code' => '', 'region_code' => ''];
    $country = strtoupper((string) ($location['country_code'] ?? ''));
    $region = strtoupper((string) ($location['region_code'] ?? ''));
    $label = $country;
    if ($country === 'US' && class_exists('TNet_Identity_Location_Policy')) {
      $label = TNet_Identity_Location_Policy::us_regions()[$region] ?? 'United States';
    } elseif ($country !== '' && class_exists('TNet_Identity_Location_Policy')) {
      $label = TNet_Identity_Location_Policy::country_label($country) ?: $country;
    }
    return ['country_code' => $country, 'region_code' => $region, 'label' => $label ?: __('Not provided', 'tnet-profile'), 'exists' => $country !== ''];
  }

  private static function professional_suggestions($user_id) {
    $legacy = TNet_Profile_Member_Context::for_user($user_id)['roles'] ?? [];
    $map = ['teacher' => '1fcd2265-c4a1-47fd-97fc-6d3844e18952', 'administrator' => '0838edba-879b-46c6-9e51-32fdce5dabcc', 'tutor' => '52bd64ef-7207-4294-9e04-a24c6546f13e'];
    $allowed = array_fill_keys(array_map(static function ($term) { return (string) $term->term_uuid; }, self::professional_identity_terms()), true);
    return array_values(array_unique(array_filter(array_map(static function ($role) use ($map, $allowed) { $uuid = $map[$role] ?? ''; return isset($allowed[$uuid]) ? $uuid : ''; }, $legacy))));
  }

  private static function terms() {
    return class_exists('CFM') ? (array) CFM::get_terms(TNet_Profile_Member_Context::CORE_TERMS_FRAMEWORK) : [];
  }

  private static function axis($label) {
    foreach (self::terms() as $term) if ((string) ($term->label ?? '') === $label && (string) ($term->term_uuid ?? '') === (string) ($term->axis_uuid ?? '')) return $term;
    return null;
  }

  private static function axis_terms($label) {
    $axis = self::axis($label);
    if (!$axis) return [];
    $axis_uuid = (string) $axis->term_uuid;
    $key = $label === 'Grade Level' ? 'grade_level' : ($label === 'Subject Area' ? 'subject_area' : '');
    $bindings = (array) get_option('tnet_profile_basics_list_bindings', []);
    $binding = $key !== '' ? ($bindings[$key] ?? []) : [];
    $version_id = absint($binding['version_id'] ?? 0);
    if (!$version_id || !class_exists('CFM_Views_Service')) return [];
    $list = CFM_Views_Service::get_published_list($version_id);
    if (is_wp_error($list) || (string) ($list['parent']['key'] ?? '') !== $axis_uuid
        || (string) ($list['parent']['framework'] ?? '') !== TNet_Profile_Member_Context::CORE_TERMS_FRAMEWORK
        || (int) ($list['view']['id'] ?? 0) !== absint($binding['view_id'] ?? 0)
        || !empty($list['role'])) return [];
    $catalog = [];
    foreach (self::terms() as $term) {
      if ((string) ($term->axis_uuid ?? '') === $axis_uuid && (string) ($term->term_uuid ?? '') !== $axis_uuid) {
        $catalog[(string) $term->term_uuid] = $term;
      }
    }
    $members = [];
    foreach ((array) ($list['members'] ?? []) as $member) {
      $uuid = (string) ($member['term_uuid'] ?? '');
      if ($uuid === '' || !isset($catalog[$uuid])) return [];
      $members[] = $catalog[$uuid];
    }
    return $members;
  }

  /** Resolve the dedicated ordered Profile role List; never use an axis allowlist. */
  public static function professional_identity_terms() {
    $bindings = (array) get_option('tnet_profile_basics_list_bindings', []);
    $binding = (array) ($bindings['professional_identity'] ?? []);
    $version_id = absint($binding['version_id'] ?? 0);
    $view_id = absint($binding['view_id'] ?? 0);
    if (!$version_id || !$view_id || !class_exists('CFM_Views_Service')) return [];
    $list = CFM_Views_Service::get_published_list($version_id);
    if (is_wp_error($list)
        || (int) ($list['view']['id'] ?? 0) !== $view_id
        || (string) ($list['view']['name'] ?? '') !== 'Profile Educator Roles V1'
        || (string) ($binding['view_uuid'] ?? '') === ''
        || (string) ($list['view']['uuid'] ?? '') !== (string) $binding['view_uuid']
        || (string) ($list['structure_type'] ?? '') !== 'list'
        || (string) ($list['parent']['framework'] ?? '') !== TNet_Profile_Member_Context::CORE_TERMS_FRAMEWORK
        || (string) ($list['parent']['key'] ?? '') !== '641a5340-cb79-4b24-9382-6c9ac8d54a62'
        || !empty($list['role'])) return [];
    $catalog = [];
    foreach (self::terms() as $term) $catalog[(string) ($term->term_uuid ?? '')] = $term;
    $members = [];
    $seen = [];
    foreach ((array) ($list['members'] ?? []) as $member) {
      $uuid = (string) ($member['term_uuid'] ?? '');
      if ($uuid === '' || isset($seen[$uuid]) || (string) ($member['framework'] ?? '') !== TNet_Profile_Member_Context::CORE_TERMS_FRAMEWORK || !isset($catalog[$uuid])) return [];
      $seen[$uuid] = true;
      $term = clone $catalog[$uuid];
      $term->label = (string) ($member['label'] ?? $term->label ?? '');
      if ($term->label === '') return [];
      $members[] = $term;
    }
    return $members;
  }

  private static function render_editor($user_id, $result, $guided) {
    $user = get_user_by('id', $user_id);
    $state = self::state($user_id);
    $title = $guided ? __('Complete your profile', 'tnet-profile') : __('Edit Profile', 'tnet-profile');
    if (class_exists('TNet_Shared_Shell')) {
      self::enqueue_assets();
      if ($guided) TNet_Profile_Enrichment::enqueue_assets();
      TNet_Shared_Shell::render_host(self::shell_config($title, $user, $state, $result, $guided));
      exit;
    }
    self::render_content($user, $state, $result, $guided);
    exit;
  }

  public static function enqueue_assets() {
    TNet_Shared_Shell::enqueue_assets('community');
    TNet_Profile_Avatar::enqueue_editor_assets();
    $css = dirname(__DIR__) . '/public/css/tnet-profile-basics.css';
    $js = dirname(__DIR__) . '/public/js/tnet-profile-basics.js';
    wp_enqueue_style('tnet-profile-basics', TNET_PROFILE_PLUGIN_URL . 'public/css/tnet-profile-basics.css', ['tnet-shared-shell-community'], is_readable($css) ? filemtime($css) : '1');
    wp_enqueue_script('tnet-profile-basics', TNET_PROFILE_PLUGIN_URL . 'public/js/tnet-profile-basics.js', [], is_readable($js) ? filemtime($js) : '1', true);
  }

  /** Presentation-only projections; labels do not confer Community membership or URL authority. */
  private static function community_navigation(array $state, $terminal_complete = false) {
    $children = [];
    $terms = [];
    foreach (self::terms() as $term) $terms[(string) ($term->term_uuid ?? '')] = $term;
    $selected = (array) ($state['selected'] ?? []);
    $has_ancestor = static function ($uuid, $label) use ($terms) {
      $seen = [];
      while ($uuid !== '' && isset($terms[$uuid]) && !isset($seen[$uuid])) {
        $seen[$uuid] = true;
        if ((string) $terms[$uuid]->label === $label) return true;
        $uuid = (string) ($terms[$uuid]->parent_uuid ?? '');
      }
      return false;
    };
    foreach ((array) ($selected['teaching_grade'] ?? []) as $uuid) {
      if ($has_ancestor((string) $uuid, 'Elementary')) { $children[] = ['label' => 'Elementary Teachers']; break; }
    }
    foreach (['Mathematics' => 'Math Teachers', 'Science' => 'Science Teachers'] as $ancestor => $label) {
      foreach ((array) ($selected['teaching_subject'] ?? []) as $uuid) {
        if ($has_ancestor((string) $uuid, $ancestor)) { $children[] = ['label' => $label]; break; }
      }
    }
    $location = (array) ($state['location'] ?? []);
    if (($location['country_code'] ?? '') === 'US' && ($location['region_code'] ?? '') !== ''
        && class_exists('TNet_Identity_Location_Policy')) {
      $region = strtoupper((string) $location['region_code']);
      $regions = TNet_Identity_Location_Policy::us_regions();
      if (isset($regions[$region])) $children[] = [
        'label' => $regions[$region] . ' Teachers',
        'url' => 'https://teachers.net/states/' . strtolower($region) . '/',
      ];
    }
    $navigation = ['home_url' => home_url('/'), 'jobs_url' => home_url('/jobs/'),
      'lessons_url' => home_url('/lessonplans/'), 'chatboards_url' => home_url('/chat/'),
      'help_url' => home_url('/info/help/'), 'show_help' => false,
      'settings_url' => home_url('/account/'), 'generic_join' => false,
      'chatboards_in_platform_when_empty' => !$terminal_complete,
      'show_context' => false, 'families' => [], 'chatboard_children' => $children];
    if ($terminal_complete) $navigation['platform_order'] = ['home', 'lessons', 'jobs'];
    return $navigation;
  }

  private static function shell_config($title, $user, $state, $result, $guided) {
    $avatar = TNet_Profile_Avatar::resolve_avatar((int) $user->ID, 64);
    return [
      'contract' => 'canonical', 'adapter' => 'community3', 'workspace_owner' => 'consumer', 'fixture' => 'profile-basics', 'clean' => true, 'presentation' => 'flush', 'suppress_admin_bar' => true,
      'document_title' => $title . ' | Teachers.Net', 'route_class' => 'profile-basics', 'fixture_state' => 'auth-unread', 'logged_in' => true, 'employer_access' => false,
      'home_url' => home_url('/'), 'active_destination' => '', 'brand_image' => TNET_SHARED_SHELL_PLUGIN_URL . 'public/assets/teachers-net-wordmark.svg',
      'identity' => ['name' => $user->display_name, 'email' => $user->user_email, 'avatar_url' => $avatar['url'] ?? '', 'avatar_source' => $avatar['source'] ?? 'profile-resolver'],
      'urls' => ['post_job' => home_url('/jobs/employer/new/'), 'my_jobs' => home_url('/jobs/employer/my-jobs/'), 'schools' => home_url('/jobs/employer/schools/'), 'archived' => home_url('/jobs/employer/my-jobs/?status=archived'), 'browse_jobs' => home_url('/jobs/'), 'saved_jobs' => home_url('/jobs/'), 'job_alerts' => home_url('/jobs/'), 'new_topic' => home_url('/chatboards/'), 'profile' => home_url('/profile/'), 'logout' => wp_logout_url(home_url('/')), 'login' => wp_login_url(self::edit_url()), 'signup' => home_url('/account/sign-up/'), 'dashboard' => home_url('/jobs/'), 'wizard' => home_url('/jobs/employer/new/'), 'chatboards' => home_url('/chatboards/')],
      'taxonomy' => ['lesson_grade_levels' => [], 'lesson_subject_areas' => [], 'chatboard_grade_levels' => []],
      'footer_links' => [['About', home_url('/info/about/')], ['Mission', home_url('/info/mission/')], ['Contacts', home_url('/info/contacts/')], ['Terms', home_url('/info/policies/')], ['Privacy', home_url('/info/privacy/')]],
      'content' => static function () use ($user, $state, $result, $guided) {
        TNet_Shared_Shell::render_community_frame([
          'navigation' => self::community_navigation($state),
          'main' => static function () use ($user, $state, $result, $guided) { echo '<section class="c3-community-page tnet-profile-basics-page">'; self::render_content($user, $state, $result, $guided); echo '</section>'; },
          'right' => $guided ? static function () use ($state) { TNet_Profile_Enrichment::render_status($state, false); } : null,
          'reserve_account_actions' => true, 'wide_main' => !$guided,
        ]);
      },
    ];
  }

  /** Reuse the accepted Profile shell identity and navigation for guided Page 2. */
  public static function enrichment_shell_config($title, $user, callable $main, $right = null, $below = null, $wide_main = false, $terminal_complete = false) {
    $state = self::state((int) $user->ID);
    $config = self::shell_config($title, $user, $state, null, true);
    $navigation = self::community_navigation($state, $terminal_complete);
    $config['fixture'] = 'profile-enrichment';
    $config['route_class'] = 'profile-enrichment';
    if ($terminal_complete) $config['compact_rail_navigation'] = $navigation;
    $config['content'] = static function () use ($main, $right, $below, $navigation, $wide_main) {
      TNet_Shared_Shell::render_community_frame([
        'navigation' => $navigation,
        'main' => static function () use ($main) { echo '<section class="c3-community-page tnet-profile-basics-page tnet-profile-enrichment">'; $main(); echo '</section>'; },
        'right' => $right, 'below' => $below,
        'reserve_account_actions' => true, 'wide_main' => (bool) $wide_main,
      ]);
    };
    return $config;
  }

  /** Shared authenticated member-facing Profile shell without the enrichment status rail. */
  public static function member_shell_config($title, $user, array $state, callable $main) {
    $config = self::shell_config($title, $user, $state, null, false);
    $config['fixture'] = 'profile-member-view';
    $config['route_class'] = 'profile-member-view';
    $config['content'] = static function () use ($main, $state) {
      TNet_Shared_Shell::render_community_frame([
        'navigation' => self::community_navigation($state),
        'main' => static function () use ($main) { echo '<section class="c3-community-page tnet-profile-basics-page tnet-profile-enrichment">'; $main(); echo '</section>'; },
        'right' => null, 'reserve_account_actions' => true, 'wide_main' => true,
      ]);
    };
    return $config;
  }

  private static function render_content($user, array $state, $result, $guided) {
    $selected = $state['selected'];
    $scalars = $state['scalars'];
    $avatar = TNet_Profile_Avatar::resolve_avatar((int) $user->ID, 144);
    $status = sanitize_key((string) ($_GET['profile_status'] ?? ''));
    $title = $guided ? __('Complete your profile', 'tnet-profile') : __('Edit Profile', 'tnet-profile');
    ?>
    <div class="tnet-profile-basics-wrap<?php echo $guided ? ' tnet-profile-basics-wrap--guided' : ' tnet-profile-basics-wrap--editor'; ?>" data-tnet-profile-basics>
      <?php if (!$guided) : ?><nav class="tnet-profile-basics-crumbs" aria-label="Breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Home</a><span aria-hidden="true">›</span><span><?php echo esc_html($title); ?></span></nav><?php endif; ?>
      <header class="tnet-profile-basics-heading"><?php if (!$guided) : ?><p class="tnet-profile-basics-eyebrow"><?php echo esc_html__('Your member settings', 'tnet-profile'); ?></p><?php endif; ?><h1><?php echo esc_html($title); ?></h1><p><?php echo esc_html($guided ? __('Tell us a little about you so we can personalize Teachers.Net for you.', 'tnet-profile') : __('Choose the details you want to share. Your Profile remains under your control.', 'tnet-profile')); ?></p></header>
      <?php if ($status === 'saved') : ?><p class="tnet-profile-basics-notice" role="status"><?php echo esc_html__('Your Profile changes were saved.', 'tnet-profile'); ?></p><?php endif; ?>
      <?php if (is_wp_error($result)) : ?><div class="tnet-profile-basics-error" role="alert"><?php echo esc_html($result->get_error_message()); ?></div><?php endif; ?>
      <?php if ($guided) : TNet_Profile_Enrichment::render_profile_card($user, $state, self::professional_identity_terms(), ['live' => true, 'edit_photo' => true, 'return_to' => add_query_arg('avatar_modal', '1', self::guided_url())]); endif; ?>
      <form class="tnet-profile-basics-form" method="post" action="<?php echo esc_url(self::edit_url($guided)); ?>">
        <?php wp_nonce_field(self::NONCE); ?><?php if ($guided) : ?><input type="hidden" name="profile_basics_mode" value="1"><input type="hidden" name="display_name" value="<?php echo esc_attr($user->display_name); ?>"><input type="hidden" name="location_public" value="<?php echo esc_attr($scalars['location_public'] ? '1' : '0'); ?>"><?php endif; ?>
        <div class="tnet-profile-basics-layout">
          <div class="tnet-profile-basics-main">
            <?php if (!$guided) : self::render_identity($user, $avatar, false); endif; ?>
            <?php self::render_location($state, $scalars, $guided); ?>
            <?php if ($guided) : ?>
              <?php self::render_grades($selected['teaching_grade'], true); ?>
              <?php self::render_subjects($selected['teaching_subject'], true); ?>
              <?php self::render_about($scalars, true); ?>
            <?php else : ?>
              <section class="tnet-profile-basics-card tnet-profile-basics-teaching"><div class="tnet-profile-basics-card-title"><span aria-hidden="true">♢</span><h2><?php echo esc_html__('What you teach', 'tnet-profile'); ?></h2></div><p class="tnet-profile-basics-help"><?php echo esc_html__('Choose the grades and subjects that best describe your teaching.', 'tnet-profile'); ?></p><div class="tnet-profile-basics-teaching-grid"><?php self::render_grades($selected['teaching_grade']); ?><?php self::render_subjects($selected['teaching_subject']); ?></div></section>
              <?php self::render_professional_identities($selected['professional_identity'], $state['suggestions']); ?>
              <?php self::render_about($scalars); ?>
              <?php self::render_details_privacy($scalars); ?>
            <?php endif; ?>
            <div class="tnet-profile-basics-actions"><?php if ($guided) : ?><button class="tnet-profile-basics-secondary" type="submit" name="journey_action" value="skip"><?php echo esc_html__('Skip for now', 'tnet-profile'); ?></button><?php endif; ?><button class="tnet-profile-basics-save" type="submit"<?php if ($guided) : ?> name="journey_action" value="continue"<?php endif; ?>><?php echo esc_html($guided ? __('Save and preview my profile', 'tnet-profile') : __('Save changes', 'tnet-profile')); ?> <span aria-hidden="true">→</span></button><?php if (!$guided) : ?><a href="<?php echo esc_url(home_url('/account/launch/')); ?>"><?php echo esc_html__('Cancel', 'tnet-profile'); ?></a><?php endif; ?></div>
          </div>
          <?php if (!$guided) : self::render_editor_preview($user, $avatar, $state); endif; ?>
        </div>
      </form>
      <?php if (!$guided) TNet_Profile_Avatar::render_modal(add_query_arg('avatar_modal', '1', TNet_Profile_Basics::edit_url())); ?>
    </div>
    <?php
  }

  private static function render_identity($user, array $avatar, $guided) {
    ?>
    <section class="tnet-profile-basics-card tnet-profile-basics-identity"><?php if (!$guided) : ?><div class="tnet-profile-basics-card-title"><span aria-hidden="true">◉</span><h2><?php echo esc_html__('Your identity', 'tnet-profile'); ?></h2></div><?php endif; ?><div class="tnet-profile-basics-identity-grid"><div class="tnet-profile-basics-avatar tnet-profile-enrichment-avatar-wrap"><img src="<?php echo esc_url($avatar['url']); ?>" alt="<?php echo esc_attr(sprintf(__('%s’s current avatar', 'tnet-profile'), $user->display_name)); ?>"><button class="tnet-avatar-camera" type="button" data-open-avatar-editor aria-label="<?php echo esc_attr__('Change profile photo', 'tnet-profile'); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7.5h3l1.5-2h7l1.5 2h3a1.5 1.5 0 0 1 1.5 1.5v9a1.5 1.5 0 0 1-1.5 1.5h-16A1.5 1.5 0 0 1 2.5 18v-9A1.5 1.5 0 0 1 4 7.5Z"/><circle cx="12" cy="13" r="3.5"/></svg></button></div><div class="tnet-profile-basics-fields"><?php if ($guided) : ?><p class="tnet-profile-basics-identity-name"><?php echo esc_html($user->display_name); ?></p><p class="tnet-profile-basics-identity-handle">@<?php echo esc_html($user->user_login); ?></p><input type="hidden" name="display_name" value="<?php echo esc_attr($user->display_name); ?>"><?php else : ?><label for="tnet-profile-display-name"><?php echo esc_html__('Display name', 'tnet-profile'); ?><input id="tnet-profile-display-name" name="display_name" value="<?php echo esc_attr($user->display_name); ?>" minlength="2" maxlength="50" required></label><p class="tnet-profile-basics-identity-handle">@<?php echo esc_html($user->user_login); ?> <span><?php echo esc_html__('Your username is permanent.', 'tnet-profile'); ?></span></p><?php endif; ?></div><?php if ($guided) : ?><p class="tnet-profile-basics-account-complete"><span aria-hidden="true">✓</span><span><?php echo esc_html__('Account setup', 'tnet-profile'); ?><br><?php echo esc_html__('complete', 'tnet-profile'); ?></span></p><?php endif; ?></div></section>
    <?php
  }

  private static function render_location(array $state, array $scalars, $guided) {
    $location = $state['location'];
    if ($guided) {
      self::render_guided_location($location);
      return;
    }
    ?>
    <section class="tnet-profile-basics-card tnet-profile-basics-location-card"><div class="tnet-profile-basics-card-title"><span aria-hidden="true">⌖</span><h2><?php echo esc_html__('Your location', 'tnet-profile'); ?></h2></div><?php if (!empty($location['exists'])) : ?><p class="tnet-profile-basics-location"><strong><?php echo esc_html($location['label']); ?></strong><a href="<?php echo esc_url(home_url('/account/location/')); ?>"><?php echo esc_html__('Change location', 'tnet-profile'); ?></a></p><label class="tnet-profile-basics-toggle"><input type="checkbox" name="location_public" value="1"<?php checked($scalars['location_public']); ?>><span><?php echo esc_html__('Show my location on my Profile', 'tnet-profile'); ?></span></label><?php else : ?><p class="tnet-profile-basics-location-empty"><?php echo esc_html__('Add a city, state, or country to help members find your local community.', 'tnet-profile'); ?></p><a class="tnet-profile-basics-location-add" href="<?php echo esc_url(home_url('/account/location/')); ?>"><?php echo esc_html__('Add your location', 'tnet-profile'); ?> <span aria-hidden="true">→</span></a><?php endif; ?><p class="tnet-profile-basics-help"><?php echo esc_html__('Your location and teaching details have separate visibility settings.', 'tnet-profile'); ?></p></section>
    <?php
  }

  private static function render_guided_location(array $location) {
    $regions = class_exists('TNet_Identity_Location_Policy') ? TNet_Identity_Location_Policy::us_regions() : [];
    $countries = class_exists('TNet_Identity_Location_Policy') ? TNet_Identity_Location_Policy::country_codes() : [];
    $is_us = ($location['country_code'] ?? '') === 'US';
    $mode = !empty($location['exists']) ? ($is_us ? 'us' : 'international') : '';
    $selection = $is_us ? (string) ($location['region_code'] ?? '') : (string) ($location['country_code'] ?? '');
    $state_selection = $is_us ? $selection : '';
    $country_selection = !$is_us && !empty($location['exists']) ? $selection : '';
    ?>
    <section class="tnet-profile-basics-card tnet-profile-basics-location-card tnet-profile-basics-location-card--guided" data-tnet-profile-guided-location>
      <div class="tnet-profile-basics-guided-card-heading tnet-profile-basics-guided-location-heading">
        <span class="tnet-profile-basics-guided-card-mark tnet-profile-basics-guided-location-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></span>
        <div><h2><?php echo esc_html__('Your location', 'tnet-profile'); ?></h2><p><?php echo esc_html__('Help us personalize Teachers.Net for your area.', 'tnet-profile'); ?></p></div>
      </div>
      <div class="tnet-profile-basics-guided-location-complete" data-location-complete<?php echo empty($location['exists']) ? ' hidden' : ''; ?>>
        <div><span class="tnet-profile-basics-guided-location-check" aria-hidden="true">✓</span><div><strong data-location-label><?php echo esc_html($location['label']); ?></strong></div></div>
        <button type="button" class="tnet-profile-basics-guided-location-change" data-location-change><?php echo esc_html__('Change', 'tnet-profile'); ?></button>
      </div>
      <div class="tnet-profile-basics-guided-location-controls" data-location-controls<?php echo !empty($location['exists']) ? ' hidden' : ''; ?>>
        <div class="tnet-profile-basics-guided-location-row" data-location-row>
          <label class="screen-reader-text" for="tnet-profile-location-mode"><?php echo esc_html__('Select your location', 'tnet-profile'); ?></label>
          <span class="tnet-profile-basics-guided-location-field tnet-profile-basics-guided-location-field--mode">
            <svg class="tnet-profile-basics-guided-location-leading tnet-profile-basics-guided-location-globe" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 3.75 5.5 3.75 9S14.5 18.5 12 21c-2.5-2.5-3.75-5.5-3.75-9S9.5 5.5 12 3Z"/></svg>
            <svg class="tnet-profile-basics-guided-location-leading tnet-profile-basics-guided-location-flag" viewBox="0 0 21 16" aria-hidden="true"><path fill="#fff" d="M0 0h21v16H0z"/><path fill="#bd3847" d="M0 0h21v1.23H0zm0 2.46h21v1.23H0zm0 2.46h21v1.23H0zm0 2.46h21v1.23H0zm0 2.46h21v1.23H0zm0 2.46h21v1.23H0zm0 2.46h21V16H0z"/><path fill="#284978" d="M0 0h9v8.62H0z"/><path fill="#fff" d="M1.6 1.2h1v1h-1zm2.5 0h1v1h-1zm2.5 0h1v1h-1zM2.8 3h1v1h-1zm2.5 0h1v1h-1zM1.6 4.8h1v1h-1zm2.5 0h1v1h-1zm2.5 0h1v1h-1zM2.8 6.6h1v1h-1zm2.5 0h1v1h-1z"/></svg>
            <select id="tnet-profile-location-mode" class="tnet-profile-basics-guided-location-select" name="location_mode" data-location-mode>
              <option value=""><?php echo esc_html__('Select your location', 'tnet-profile'); ?></option>
              <option value="us"<?php selected($mode, 'us'); ?>><?php echo esc_html__('United States', 'tnet-profile'); ?></option>
              <option value="international"<?php selected($mode, 'international'); ?>><?php echo esc_html__('Outside the U.S.', 'tnet-profile'); ?></option>
            </select>
            <svg class="tnet-profile-basics-guided-location-caret" viewBox="0 0 14 8" fill="none" aria-hidden="true"><path d="m1 1 6 6 6-6"/></svg>
          </span>
          <span class="tnet-profile-basics-guided-location-field" data-location-panel="us"<?php echo $mode === 'us' ? '' : ' hidden'; ?>><label class="screen-reader-text" for="tnet-profile-location-state"><?php echo esc_html__('Select your state', 'tnet-profile'); ?></label><svg class="tnet-profile-basics-guided-location-leading" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0Z"/><circle cx="12" cy="10" r="2.5"/></svg><select id="tnet-profile-location-state" class="tnet-profile-basics-guided-location-select" data-location-state<?php disabled($mode !== 'us'); ?>><option value=""><?php echo esc_html__('Select your state', 'tnet-profile'); ?></option><?php foreach ($regions as $code => $label) : ?><option value="<?php echo esc_attr($code); ?>"<?php selected($state_selection, $code); ?>><?php echo esc_html($label); ?></option><?php endforeach; ?></select><svg class="tnet-profile-basics-guided-location-caret" viewBox="0 0 14 8" fill="none" aria-hidden="true"><path d="m1 1 6 6 6-6"/></svg></span>
          <span class="tnet-profile-basics-guided-location-field" data-location-panel="international"<?php echo $mode === 'international' ? '' : ' hidden'; ?>><label class="screen-reader-text" for="tnet-profile-location-country"><?php echo esc_html__('Select your country', 'tnet-profile'); ?></label><svg class="tnet-profile-basics-guided-location-leading" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0Z"/><circle cx="12" cy="10" r="2.5"/></svg><select id="tnet-profile-location-country" class="tnet-profile-basics-guided-location-select" data-location-country<?php disabled($mode !== 'international'); ?>><option value=""><?php echo esc_html__('Select your country', 'tnet-profile'); ?></option><?php foreach ($countries as $code) : ?><option value="<?php echo esc_attr($code); ?>"<?php selected($country_selection, $code); ?>><?php echo esc_html(TNet_Identity_Location_Policy::country_label($code)); ?></option><?php endforeach; ?></select><svg class="tnet-profile-basics-guided-location-caret" viewBox="0 0 14 8" fill="none" aria-hidden="true"><path d="m1 1 6 6 6-6"/></svg></span>
        </div>
        <input type="hidden" name="location_selection" value="<?php echo esc_attr($selection); ?>" data-location-selection>
      </div>
      <p class="tnet-profile-basics-guided-location-privacy"><svg viewBox="0 0 13 18" aria-hidden="true"><path fill="currentColor" d="M2.25 7V5a4.25 4.25 0 1 1 8.5 0v2h-2V5a2.25 2.25 0 1 0-4.5 0v2h-2Z"/><path fill="currentColor" fill-rule="evenodd" d="M2 7h9a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1Zm4.5 4a1.25 1.25 0 1 0 0 2.5 1.25 1.25 0 0 0 0-2.5Z"/></svg><span data-location-edit-copy><?php echo esc_html__('Used for personalization. Not shown on your public profile.', 'tnet-profile'); ?></span><span data-location-complete-copy><?php echo esc_html__('Used to personalize your Teachers.Net experience.', 'tnet-profile'); ?></span></p>
    </section>
    <?php
  }

  private static function render_grades(array $selected, $guided = false) {
    $axis = self::axis('Grade Level');
    $terms = self::axis_terms('Grade Level');
    $by_parent = [];
    foreach ($terms as $term) $by_parent[(string) ($term->parent_uuid ?? '')][] = $term;
    ?>
    <section class="<?php echo esc_attr($guided ? 'tnet-profile-basics-card tnet-profile-basics-guided-card tnet-profile-basics-grades' : 'tnet-profile-basics-panel tnet-profile-basics-grades'); ?>"><?php if ($guided) : self::render_guided_card_heading('grades', __('Grades you teach', 'tnet-profile'), __('Select the grade levels you teach.', 'tnet-profile')); else : ?><h3><?php echo esc_html__('Grades', 'tnet-profile'); ?></h3><p class="tnet-profile-basics-help"><?php echo esc_html__('Select the grade levels you teach.', 'tnet-profile'); ?></p><?php endif; ?><div class="tnet-profile-basics-grade-groups">
      <?php foreach (self::ordered_grade_groups(($by_parent[(string) ($axis->term_uuid ?? '')] ?? [])) as $group_index => $group) :
        $children = $by_parent[(string) ($group->term_uuid ?? '')] ?? [];
        if (!$children) { self::render_term_checkbox($group, 'teaching_grades', $selected, 'tnet-profile-basics-check--grade-top-level'); continue; }
        $selected_children = self::selected_grade_labels_for_children($group, $by_parent, $selected);
      ?><div class="tnet-profile-basics-grade-family" data-tnet-profile-grade-family data-grade-family="<?php echo esc_attr((string) $group->term_uuid); ?>">
        <input class="tnet-profile-basics-grade-parent" type="checkbox" data-grade-parent aria-label="<?php echo esc_attr(sprintf(__('Select all %s grades', 'tnet-profile'), $group->label)); ?>">
        <details data-grade-disclosure><summary><span><?php echo esc_html($group->label); ?></span></summary><div class="tnet-profile-basics-options"><?php foreach ($children as $child) self::render_grade_descendants($child, $by_parent, $selected, (string) $group->term_uuid); ?></div></details>
      </div><?php endforeach; ?>
    </div></section>
    <?php
  }

  private static function render_subjects(array $selected, $guided = false) {
    $terms = self::axis_terms('Subject Area');
    usort($terms, static function ($a, $b) { return strcasecmp((string) $a->label, (string) $b->label); });
    ?>
    <section class="<?php echo esc_attr($guided ? 'tnet-profile-basics-card tnet-profile-basics-guided-card tnet-profile-basics-subjects' : 'tnet-profile-basics-panel'); ?>"><?php if ($guided) : self::render_guided_card_heading('subjects', __('Subjects you teach', 'tnet-profile'), __('Add the approved subject areas that apply to you.', 'tnet-profile')); else : ?><h3><?php echo esc_html__('Subjects', 'tnet-profile'); ?></h3><p class="tnet-profile-basics-help"><?php echo esc_html__('Add the approved subject areas that apply to you.', 'tnet-profile'); ?></p><?php endif; ?><?php self::render_chip_picker('subject', __('Search and add a subject', 'tnet-profile'), 'teaching_subjects', $terms, $selected); ?></section>
    <?php
  }

  private static function render_professional_identities(array $selected, array $suggestions) {
    $terms = self::professional_identity_terms();
    $choice_ids = array_fill_keys(array_map(static function ($term) { return (string) $term->term_uuid; }, $terms), true);
    $all_labels = [];
    foreach (self::terms() as $term) $all_labels[(string) ($term->term_uuid ?? '')] = (string) ($term->label ?? '');
    foreach (self::LEGACY_PROFESSIONAL_IDENTITY_LABELS as $uuid => $label) $all_labels[$uuid] = $label;
    $preserved_labels = [];
    foreach ($selected as $uuid) if (!isset($choice_ids[$uuid]) && !empty($all_labels[$uuid])) $preserved_labels[$uuid] = $all_labels[$uuid];
    ?>
    <section class="tnet-profile-basics-card"><div class="tnet-profile-basics-card-title"><span aria-hidden="true">✦</span><h2><?php echo esc_html__('Educator roles', 'tnet-profile'); ?> <small><?php echo esc_html__('optional', 'tnet-profile'); ?></small></h2></div><p class="tnet-profile-basics-help"><?php echo esc_html__('Choose the roles that describe you. Add a role only when you want it shown.', 'tnet-profile'); ?></p><?php if ($suggestions) : ?><p class="tnet-profile-basics-suggestion"><?php echo esc_html__('A role was suggested from onboarding. Add it to confirm it for your Profile.', 'tnet-profile'); ?></p><?php endif; ?><details class="tnet-profile-basics-picker-details"<?php echo $selected ? ' open' : ''; ?>><summary><?php echo esc_html__('Add or manage roles', 'tnet-profile'); ?></summary><?php self::render_chip_picker('role', __('Search and add a role', 'tnet-profile'), 'professional_identities', $terms, $selected, $suggestions, $preserved_labels); ?></details></section>
    <?php
  }

  private static function render_about(array $scalars, $guided = false) {
    ?>
    <section class="tnet-profile-basics-card<?php echo $guided ? ' tnet-profile-basics-guided-card' : ''; ?> tnet-profile-basics-about-card"><?php if ($guided) : self::render_guided_card_heading('about', __('About you', 'tnet-profile'), __('Share a little about yourself (optional).', 'tnet-profile')); else : ?><div class="tnet-profile-basics-card-title"><span aria-hidden="true">▤</span><h2><?php echo esc_html__('About you', 'tnet-profile'); ?></h2></div><?php endif; ?><div class="tnet-profile-basics-about"><?php if ($guided) : ?><input type="hidden" name="teaching_since" value="<?php echo esc_attr($scalars['teaching_since'] ?: ''); ?>"><?php else : ?><label for="tnet-profile-teaching-since"><?php echo esc_html__('Teaching since', 'tnet-profile'); ?><input id="tnet-profile-teaching-since" name="teaching_since" type="number" min="1900" max="<?php echo esc_attr(gmdate('Y')); ?>" inputmode="numeric" value="<?php echo esc_attr($scalars['teaching_since'] ?: ''); ?>" placeholder="YYYY"><small><?php echo esc_html__('Optional.', 'tnet-profile'); ?></small></label><?php endif; ?><label for="tnet-profile-bio"><?php if ($guided) : ?><span class="screen-reader-text"><?php echo esc_html__('A short bio (optional)', 'tnet-profile'); ?></span><?php else : ?><?php echo esc_html__('A short bio', 'tnet-profile'); ?><?php endif; ?><textarea id="tnet-profile-bio" name="bio" maxlength="500" rows="<?php echo esc_attr($guided ? '3' : '4'); ?>" data-tnet-profile-bio placeholder="<?php echo esc_attr__('Tell other members a little about yourself.', 'tnet-profile'); ?>"><?php echo esc_textarea($scalars['bio']); ?></textarea><small><span data-tnet-profile-bio-count><?php echo esc_html(self::text_length($scalars['bio'])); ?></span>/500</small></label></div></section>
    <?php
  }

  private static function render_guided_card_heading($icon, $title, $subhead) {
    $icons = [
      'grades' => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m2.5 8.5 9.5-5 9.5 5-9.5 5-9.5-5Z"/><path d="M6.5 10.6v5.2c3.2 2.6 7.8 2.6 11 0v-5.2M21.5 8.5v6"/></svg>',
      'subjects' => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 6.5c-2.5-2-5.5-2.5-9-1.5v13c3.5-1 6.5-.5 9 1.5m0-13c2.5-2 5.5-2.5 9-1.5v13c-3.5-1-6.5-.5-9 1.5m0-13v13"/></svg>',
      'about' => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 3.5h8l4 4v13H6a2 2 0 0 1-2-2v-13a2 2 0 0 1 2-2Z"/><path d="M14 3.5v4h4M8 12h8m-8 3.5h8"/></svg>',
    ];
    if (!isset($icons[$icon])) return;
    ?>
    <div class="tnet-profile-basics-guided-card-heading">
      <span class="tnet-profile-basics-guided-card-mark" aria-hidden="true"><?php echo $icons[$icon]; // Static, allow-listed SVG. ?></span>
      <div><h2><?php echo esc_html($title); ?></h2><p><?php echo esc_html($subhead); ?></p></div>
    </div>
    <?php
  }

  private static function render_details_privacy(array $scalars) {
    ?><section class="tnet-profile-basics-card tnet-profile-basics-details-privacy"><div class="tnet-profile-basics-card-title"><span aria-hidden="true">◌</span><h2><?php echo esc_html__('Teaching details privacy', 'tnet-profile'); ?></h2></div><label class="tnet-profile-basics-toggle"><input type="checkbox" name="details_public" value="1"<?php checked($scalars['profile_details_public']); ?>><span><?php echo esc_html__('Show my grades, subjects, and educator roles on my Profile', 'tnet-profile'); ?></span></label><p class="tnet-profile-basics-help"><?php echo esc_html__('You can change this independently from your location setting.', 'tnet-profile'); ?></p></section><?php
  }

  private static function render_guided_aside() {
    ?><aside class="tnet-profile-basics-aside tnet-profile-basics-aside--guided" aria-label="Profile tips"><section><strong><?php echo esc_html__('Why we ask', 'tnet-profile'); ?></strong><p><?php echo esc_html__('Your teaching details help us make Teachers.Net more useful to you.', 'tnet-profile'); ?></p></section><section><strong><?php echo esc_html__('Your privacy', 'tnet-profile'); ?></strong><p><?php echo esc_html__('You choose which details appear on your Profile.', 'tnet-profile'); ?></p></section><section><strong><?php echo esc_html__('You can update anytime', 'tnet-profile'); ?></strong><p><?php echo esc_html__('These details are optional and always under your control.', 'tnet-profile'); ?></p></section></aside><?php
  }

  private static function render_editor_preview($user, array $avatar, array $state) {
    $labels = array_merge(self::project_grade_labels($state['selected']['teaching_grade']), self::term_labels(array_merge($state['selected']['teaching_subject'], $state['selected']['professional_identity'])));
    ?><aside class="tnet-profile-basics-aside tnet-profile-basics-aside--preview" aria-label="Profile preview"><p class="tnet-profile-basics-eyebrow"><?php echo esc_html__('Editor preview', 'tnet-profile'); ?></p><h2><?php echo esc_html__('Your Profile', 'tnet-profile'); ?></h2><div class="tnet-profile-basics-preview-identity"><img src="<?php echo esc_url($avatar['url']); ?>" alt=""><div><strong><?php echo esc_html($user->display_name); ?></strong><span>@<?php echo esc_html($user->user_login); ?></span></div></div><?php if ($state['location']['exists']) : ?><p class="tnet-profile-basics-preview-location">⌖ <?php echo esc_html($state['location']['label']); ?></p><?php endif; ?><div class="tnet-profile-basics-preview-facts"><?php foreach ($labels as $label) : ?><span><?php echo esc_html($label); ?></span><?php endforeach; ?></div><p><?php echo esc_html__('This editor-only preview helps you check your details. It does not create a public Profile route.', 'tnet-profile'); ?></p></aside><?php
  }

  private static function render_chip_picker($id, $label, $input_name, array $terms, array $selected, array $suggestions = [], array $preserved_labels = []) {
    $catalog = [];
    foreach ($terms as $term) {
      if (is_object($term)) $catalog[] = ['uuid' => (string) ($term->term_uuid ?? ''), 'label' => (string) ($term->label ?? '')];
      else $catalog[] = ['uuid' => (string) $term, 'label' => (string) (self::LEGACY_PROFESSIONAL_IDENTITY_LABELS[$term] ?? '')];
    }
    ?><div class="tnet-profile-basics-chip-picker" data-tnet-profile-picker data-picker-name="<?php echo esc_attr($input_name); ?>" data-picker-catalog="<?php echo esc_attr(wp_json_encode($catalog)); ?>" data-picker-selected="<?php echo esc_attr(wp_json_encode(array_values($selected))); ?>" data-picker-suggestions="<?php echo esc_attr(wp_json_encode(array_values($suggestions))); ?>" data-picker-preserved-labels="<?php echo esc_attr(wp_json_encode($preserved_labels)); ?>"><div class="tnet-profile-basics-chip-list" data-picker-selected aria-live="polite"><?php foreach ($selected as $uuid) : ?><input type="hidden" name="<?php echo esc_attr($input_name); ?>[]" value="<?php echo esc_attr($uuid); ?>"><?php endforeach; ?></div><label class="screen-reader-text" for="tnet-profile-picker-<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label><input id="tnet-profile-picker-<?php echo esc_attr($id); ?>" class="tnet-profile-basics-picker-search" type="search" autocomplete="off" placeholder="<?php echo esc_attr($label); ?>" data-picker-search aria-controls="tnet-profile-picker-results-<?php echo esc_attr($id); ?>"><div id="tnet-profile-picker-results-<?php echo esc_attr($id); ?>" class="tnet-profile-basics-picker-results" data-picker-results role="listbox" aria-label="<?php echo esc_attr($label); ?>"></div></div><?php
  }

  public static function term_labels(array $uuids) {
    $labels = [];
    foreach (self::terms() as $term) $labels[(string) ($term->term_uuid ?? '')] = (string) ($term->label ?? '');
    foreach (self::LEGACY_PROFESSIONAL_IDENTITY_LABELS as $uuid => $label) $labels[$uuid] = $label;
    return array_values(array_filter(array_map(static function ($uuid) use ($labels) { return $labels[$uuid] ?? ''; }, array_unique($uuids))));
  }

  private static function ordered_grade_groups(array $groups) {
    $order = ['Early Childhood', 'Elementary', 'Middle School', 'High School', 'Adult Education', 'Higher Education'];
    usort($groups, static function ($a, $b) use ($order) {
      $left = array_search((string) $a->label, $order, true);
      $right = array_search((string) $b->label, $order, true);
      return ($left === false ? 99 : $left) <=> ($right === false ? 99 : $right);
    });
    return $groups;
  }

  private static function selected_grade_labels_for_children($term, array $by_parent, array $selected) {
    $labels = [];
    $uuid = (string) ($term->term_uuid ?? '');
    foreach (($by_parent[$uuid] ?? []) as $child) {
      if (in_array((string) ($child->term_uuid ?? ''), $selected, true)) $labels[] = (string) ($child->label ?? '');
      $labels = array_merge($labels, self::selected_grade_labels_for_children($child, $by_parent, $selected));
    }
    return $labels;
  }

  /** Presentation only: never expand a persisted parent into child facts. */
  public static function project_grade_labels(array $selected): array {
    $axis = self::axis('Grade Level');
    $by_parent = [];
    foreach (self::axis_terms('Grade Level') as $term) $by_parent[(string) ($term->parent_uuid ?? '')][] = $term;
    $labels = [];
    foreach (self::ordered_grade_groups($by_parent[(string) ($axis->term_uuid ?? '')] ?? []) as $group) {
      $children = $by_parent[(string) ($group->term_uuid ?? '')] ?? [];
      if (!$children) {
        if (in_array((string) ($group->term_uuid ?? ''), $selected, true)) $labels[] = (string) $group->label;
        continue;
      }
      $chosen = self::selected_grade_labels_for_children($group, $by_parent, $selected);
      if (count($chosen) === 1) $labels[] = $chosen[0];
      elseif (count($chosen) > 1) $labels[] = (string) $group->label;
    }
    return $labels;
  }

  private static function render_term_checkbox($term, $name, array $selected, $class = '') {
    $uuid = (string) ($term->term_uuid ?? '');
    if ($uuid === '') return;
    ?><label class="tnet-profile-basics-check<?php echo $class !== '' ? ' ' . esc_attr($class) : ''; ?>"><input type="checkbox" name="<?php echo esc_attr($name); ?>[]" value="<?php echo esc_attr($uuid); ?>"<?php checked(in_array($uuid, $selected, true)); ?>><span><?php echo esc_html((string) $term->label); ?></span></label><?php
  }

  private static function render_grade_descendants($term, array $by_parent, array $selected, $family_uuid = '') {
    $uuid = (string) ($term->term_uuid ?? '');
    echo '<label class="tnet-profile-basics-check" data-grade-family-child="' . esc_attr($family_uuid) . '"><input type="checkbox" name="teaching_grades[]" value="' . esc_attr($uuid) . '"' . checked(in_array($uuid, $selected, true), true, false) . '><span>' . esc_html((string) ($term->label ?? '')) . '</span></label>';
    foreach (($by_parent[(string) ($term->term_uuid ?? '')] ?? []) as $child) {
      self::render_grade_descendants($child, $by_parent, $selected, $family_uuid);
    }
  }

  private static function text_length($value) {
    return function_exists('mb_strlen') ? mb_strlen((string) $value) : strlen((string) $value);
  }
}
