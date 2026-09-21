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

  private const PROFESSIONAL_IDENTITIES = [
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
  }

  public static function query_vars($vars) {
    $vars[] = self::QUERY_VAR;
    return $vars;
  }

  public static function edit_url($guided = false) {
    return add_query_arg($guided ? ['profile_basics' => '1'] : [], home_url('/profile/edit/'));
  }

  public static function render_route() {
    if (get_query_var(self::QUERY_VAR) !== self::ROUTE) return;
    status_header(200);
    if (!is_user_logged_in()) {
      wp_safe_redirect(wp_login_url(self::edit_url()));
      exit;
    }
    $user_id = get_current_user_id();
    $result = null;
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
      $result = self::save_from_request($user_id);
      if (!is_wp_error($result)) {
        wp_safe_redirect(add_query_arg('profile_status', 'saved', self::edit_url(!empty($_POST['profile_basics_mode']))));
        exit;
      }
    }
    self::render_editor($user_id, $result);
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
    // Validate every requested term before changing identity, facts, or meta.
    foreach ($sets as $relationship => $uuids) {
      foreach ($uuids as $uuid) {
        $term = TNet_Profile_Member_Context::resolve_live_core_term_identifier($uuid);
        if (is_wp_error($term)) return $term;
      }
    }

    $identity_result = TNet_Identity_Service::update_display_name($user_id, $display_name);
    if (is_wp_error($identity_result)) return $identity_result;
    foreach ($sets as $relationship => $uuids) {
      $facts = TNet_Profile_Member_Context::replace_facts($user_id, $relationship, $uuids);
      if (is_wp_error($facts)) return $facts;
    }
    self::save_scalar_meta($user_id, $teaching_since, $bio, !empty($input['details_public']), !empty($input['location_public']));
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

  private static function save_from_request($user_id) {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), self::NONCE)) {
      return new WP_Error('tnet_profile_basics_nonce', __('Your edit session expired. Please try again.', 'tnet-profile'));
    }
    return self::save($user_id, [
      'display_name' => wp_unslash($_POST['display_name'] ?? ''),
      'teaching_grades' => wp_unslash($_POST['teaching_grades'] ?? []),
      'teaching_subjects' => wp_unslash($_POST['teaching_subjects'] ?? []),
      'professional_identities' => wp_unslash($_POST['professional_identities'] ?? []),
      'teaching_since' => wp_unslash($_POST['teaching_since'] ?? ''),
      'bio' => wp_unslash($_POST['bio'] ?? ''),
      'details_public' => !empty($_POST['details_public']),
      'location_public' => !empty($_POST['location_public']),
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
    }
    return ['country_code' => $country, 'region_code' => $region, 'label' => $label ?: __('Not provided', 'tnet-profile')];
  }

  private static function professional_suggestions($user_id) {
    $legacy = TNet_Profile_Member_Context::for_user($user_id)['roles'] ?? [];
    $map = ['teacher' => '1fcd2265-c4a1-47fd-97fc-6d3844e18952', 'administrator' => '0838edba-879b-46c6-9e51-32fdce5dabcc', 'education_student' => 'def4ee6a-a8ac-4db5-b86a-d8e4eb872c79', 'retired_teacher' => '629c3326-cff1-47d9-8015-f8b558204052', 'tutor' => '52bd64ef-7207-4294-9e04-a24c6546f13e'];
    return array_values(array_unique(array_filter(array_map(static function ($role) use ($map) { return $map[$role] ?? ''; }, $legacy))));
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
    return array_values(array_filter(self::terms(), static function ($term) use ($axis_uuid) {
      return (string) ($term->axis_uuid ?? '') === $axis_uuid && (string) ($term->term_uuid ?? '') !== $axis_uuid;
    }));
  }

  private static function render_editor($user_id, $result) {
    $user = get_user_by('id', $user_id);
    $state = self::state($user_id);
    $guided = !empty($_GET['profile_basics']);
    $title = $guided ? __('Complete your profile', 'tnet-profile') : __('Edit Profile', 'tnet-profile');
    if (class_exists('TNet_Shared_Shell')) {
      self::enqueue_assets();
      TNet_Shared_Shell::render_host(self::shell_config($title, $user, $state, $result, $guided));
      exit;
    }
    self::render_content($user, $state, $result, $guided);
    exit;
  }

  private static function enqueue_assets() {
    TNet_Shared_Shell::enqueue_assets('community');
    $css = dirname(__DIR__) . '/public/css/tnet-profile-basics.css';
    $js = dirname(__DIR__) . '/public/js/tnet-profile-basics.js';
    wp_enqueue_style('tnet-profile-basics', TNET_PROFILE_PLUGIN_URL . 'public/css/tnet-profile-basics.css', ['tnet-shared-shell-community'], is_readable($css) ? filemtime($css) : '1');
    wp_enqueue_script('tnet-profile-basics', TNET_PROFILE_PLUGIN_URL . 'public/js/tnet-profile-basics.js', [], is_readable($js) ? filemtime($js) : '1', true);
  }

  private static function shell_config($title, $user, $state, $result, $guided) {
    $avatar = TNet_Profile_Avatar::resolve_avatar((int) $user->ID, 64);
    return [
      'contract' => 'canonical', 'adapter' => 'community3', 'workspace_owner' => 'consumer', 'fixture' => 'profile-basics', 'clean' => true, 'presentation' => 'flush',
      'document_title' => $title . ' | Teachers.Net', 'route_class' => 'profile-basics', 'fixture_state' => 'auth-unread', 'logged_in' => true, 'employer_access' => false,
      'home_url' => home_url('/'), 'active_destination' => '', 'brand_image' => TNET_SHARED_SHELL_PLUGIN_URL . 'public/assets/teachers-net-wordmark.svg',
      'identity' => ['name' => $user->display_name, 'email' => $user->user_email, 'avatar_url' => $avatar['url'] ?? '', 'avatar_source' => $avatar['source'] ?? 'profile-resolver'],
      'urls' => ['post_job' => home_url('/jobs/employer/new/'), 'my_jobs' => home_url('/jobs/employer/my-jobs/'), 'schools' => home_url('/jobs/employer/schools/'), 'archived' => home_url('/jobs/employer/my-jobs/?status=archived'), 'browse_jobs' => home_url('/jobs/'), 'saved_jobs' => home_url('/jobs/'), 'job_alerts' => home_url('/jobs/'), 'new_topic' => home_url('/chatboards/'), 'profile' => self::edit_url(), 'logout' => wp_logout_url(home_url('/')), 'login' => wp_login_url(self::edit_url()), 'signup' => home_url('/account/sign-up/'), 'dashboard' => home_url('/jobs/'), 'wizard' => home_url('/jobs/employer/new/'), 'chatboards' => home_url('/chatboards/')],
      'taxonomy' => ['lesson_grade_levels' => [], 'lesson_subject_areas' => [], 'chatboard_grade_levels' => []],
      'footer_links' => [['About', home_url('/info/about/')], ['Mission', home_url('/info/mission/')], ['Contacts', home_url('/info/contacts/')], ['Terms', home_url('/info/policies/')], ['Privacy', home_url('/info/privacy/')]],
      'content' => static function () use ($user, $state, $result, $guided) {
        TNet_Shared_Shell::render_community_frame([
          'navigation' => ['home_url' => home_url('/'), 'jobs_url' => home_url('/jobs/'), 'lessons_url' => home_url('/lessons/'), 'chatboards_url' => home_url('/chatboards/'), 'help_url' => home_url('/info/help/'), 'show_help' => false, 'settings_url' => home_url('/account/'), 'generic_join' => true, 'families' => []],
          'main' => static function () use ($user, $state, $result, $guided) { echo '<section class="c3-community-page tnet-profile-basics-page">'; self::render_content($user, $state, $result, $guided); echo '</section>'; },
          'right' => null, 'reserve_account_actions' => true,
        ]);
      },
    ];
  }

  private static function render_content($user, array $state, $result, $guided) {
    $selected = $state['selected'];
    $scalars = $state['scalars'];
    $avatar = TNet_Profile_Avatar::resolve_avatar((int) $user->ID, 144);
    $status = sanitize_key((string) ($_GET['profile_status'] ?? ''));
    $title = $guided ? __('Complete your profile', 'tnet-profile') : __('Edit Profile', 'tnet-profile');
    ?>
    <div class="tnet-profile-basics-wrap" data-tnet-profile-basics>
      <nav class="tnet-profile-basics-crumbs" aria-label="Breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Home</a><span aria-hidden="true">›</span><span><?php echo esc_html($title); ?></span></nav>
      <header class="tnet-profile-basics-heading"><h1><?php echo esc_html($title); ?></h1><p><?php echo esc_html($guided ? __('You’re almost there! Tell us a little about what you teach so we can personalize Teachers.Net for you.', 'tnet-profile') : __('Keep your information up to date. Your Profile details are always under your control.', 'tnet-profile')); ?></p></header>
      <?php if ($status === 'saved') : ?><p class="tnet-profile-basics-notice" role="status"><?php echo esc_html__('Your Profile changes were saved.', 'tnet-profile'); ?></p><?php endif; ?>
      <?php if (is_wp_error($result)) : ?><div class="tnet-profile-basics-error" role="alert"><?php echo esc_html($result->get_error_message()); ?></div><?php endif; ?>
      <form class="tnet-profile-basics-form" method="post" action="<?php echo esc_url(self::edit_url($guided)); ?>">
        <?php wp_nonce_field(self::NONCE); ?><?php if ($guided) : ?><input type="hidden" name="profile_basics_mode" value="1"><?php endif; ?>
        <section class="tnet-profile-basics-card tnet-profile-basics-identity"><div class="tnet-profile-basics-card-title"><span aria-hidden="true">◉</span><h2><?php echo esc_html__('Profile basics', 'tnet-profile'); ?></h2></div><div class="tnet-profile-basics-identity-grid"><div class="tnet-profile-basics-avatar"><img src="<?php echo esc_url($avatar['url']); ?>" alt="<?php echo esc_attr(sprintf(__('%s’s current avatar', 'tnet-profile'), $user->display_name)); ?>"><a href="<?php echo esc_url(home_url('/profile/')); ?>"><?php echo esc_html__('Change photo', 'tnet-profile'); ?></a></div><div class="tnet-profile-basics-fields"><label for="tnet-profile-display-name"><?php echo esc_html__('Display name', 'tnet-profile'); ?><input id="tnet-profile-display-name" name="display_name" value="<?php echo esc_attr($user->display_name); ?>" minlength="2" maxlength="50" required></label><label for="tnet-profile-username">@<?php echo esc_html__('Username', 'tnet-profile'); ?><input id="tnet-profile-username" value="<?php echo esc_attr($user->user_login); ?>" readonly aria-describedby="tnet-profile-username-help"></label><small id="tnet-profile-username-help"><?php echo esc_html__('Your username is permanent and can’t be changed.', 'tnet-profile'); ?></small></div></div></section>
        <?php self::render_grades($selected['teaching_grade']); ?>
        <?php self::render_subjects($selected['teaching_subject']); ?>
        <?php self::render_professional_identities($selected['professional_identity'], $state['suggestions']); ?>
        <section class="tnet-profile-basics-card"><div class="tnet-profile-basics-card-title"><span aria-hidden="true">▤</span><h2><?php echo esc_html__('About', 'tnet-profile'); ?></h2></div><div class="tnet-profile-basics-about"><label for="tnet-profile-teaching-since"><?php echo esc_html__('Teaching since', 'tnet-profile'); ?><input id="tnet-profile-teaching-since" name="teaching_since" type="number" min="1900" max="<?php echo esc_attr(gmdate('Y')); ?>" inputmode="numeric" value="<?php echo esc_attr($scalars['teaching_since'] ?: ''); ?>" placeholder="YYYY"><small><?php echo esc_html__('Optional. Enter a four-digit year.', 'tnet-profile'); ?></small></label><label for="tnet-profile-bio"><?php echo esc_html__('Bio', 'tnet-profile'); ?><textarea id="tnet-profile-bio" name="bio" maxlength="500" rows="5" data-tnet-profile-bio><?php echo esc_textarea($scalars['bio']); ?></textarea><small><span data-tnet-profile-bio-count><?php echo esc_html(self::text_length($scalars['bio'])); ?></span>/500</small></label></div></section>
        <section class="tnet-profile-basics-card"><div class="tnet-profile-basics-card-title"><span aria-hidden="true">⌖</span><h2><?php echo esc_html__('Location & privacy', 'tnet-profile'); ?></h2></div><p class="tnet-profile-basics-location"><strong><?php echo esc_html($state['location']['label']); ?></strong><a href="<?php echo esc_url(home_url('/account/location/')); ?>"><?php echo esc_html__('Change location', 'tnet-profile'); ?></a></p><label class="tnet-profile-basics-toggle"><input type="checkbox" name="location_public" value="1"<?php checked($scalars['location_public']); ?>><span><?php echo esc_html__('Show my location on my Profile', 'tnet-profile'); ?></span></label><label class="tnet-profile-basics-toggle"><input type="checkbox" name="details_public" value="1"<?php checked($scalars['profile_details_public']); ?>><span><?php echo esc_html__('Show my teaching details on my Profile', 'tnet-profile'); ?></span></label><p class="tnet-profile-basics-help"><?php echo esc_html__('Location and teaching-detail visibility are controlled separately.', 'tnet-profile'); ?></p></section>
        <div class="tnet-profile-basics-actions"><button class="tnet-profile-basics-save" type="submit"><?php echo esc_html($guided ? __('Save and preview my Profile', 'tnet-profile') : __('Save changes', 'tnet-profile')); ?> <span aria-hidden="true">→</span></button><?php if (!$guided) : ?><a href="<?php echo esc_url(home_url('/account/launch/')); ?>"><?php echo esc_html__('Cancel', 'tnet-profile'); ?></a><?php else : ?><a href="<?php echo esc_url(home_url('/account/launch/')); ?>"><?php echo esc_html__('Skip for now', 'tnet-profile'); ?></a><?php endif; ?></div>
      </form>
    </div>
    <?php
  }

  private static function render_grades(array $selected) {
    $axis = self::axis('Grade Level');
    $terms = self::axis_terms('Grade Level');
    $by_parent = [];
    foreach ($terms as $term) $by_parent[(string) ($term->parent_uuid ?? '')][] = $term;
    ?>
    <section class="tnet-profile-basics-card"><div class="tnet-profile-basics-card-title"><span aria-hidden="true">♢</span><h2><?php echo esc_html__('Grades you teach', 'tnet-profile'); ?></h2></div><p class="tnet-profile-basics-help"><?php echo esc_html__('Select all that apply. You can choose a broad level or specific grades.', 'tnet-profile'); ?></p><div class="tnet-profile-basics-grade-groups">
      <?php foreach (($by_parent[(string) ($axis->term_uuid ?? '')] ?? []) as $group) : ?><details><summary><?php echo esc_html($group->label); ?></summary><div class="tnet-profile-basics-options"><?php self::render_grade_descendants($group, $by_parent, $selected); ?></div></details><?php endforeach; ?>
    </div></section>
    <?php
  }

  private static function render_subjects(array $selected) {
    $terms = self::axis_terms('Subject Area');
    usort($terms, static function ($a, $b) { return strcasecmp((string) $a->label, (string) $b->label); });
    ?>
    <section class="tnet-profile-basics-card"><div class="tnet-profile-basics-card-title"><span aria-hidden="true">▥</span><h2><?php echo esc_html__('Subjects you teach', 'tnet-profile'); ?></h2></div><p class="tnet-profile-basics-help"><?php echo esc_html__('Search the approved Subject Area catalogue and select every subject that applies.', 'tnet-profile'); ?></p><label class="screen-reader-text" for="tnet-profile-subject-search"><?php echo esc_html__('Search subjects', 'tnet-profile'); ?></label><input id="tnet-profile-subject-search" type="search" placeholder="<?php echo esc_attr__('Search subjects', 'tnet-profile'); ?>" data-tnet-profile-subject-search><label for="tnet-profile-subjects" class="screen-reader-text"><?php echo esc_html__('Subjects taught', 'tnet-profile'); ?></label><select id="tnet-profile-subjects" class="tnet-profile-subject-select" name="teaching_subjects[]" multiple size="7" data-tnet-profile-subject-select><?php foreach ($terms as $term) : ?><option value="<?php echo esc_attr($term->term_uuid); ?>"<?php selected(in_array((string) $term->term_uuid, $selected, true)); ?>><?php echo esc_html($term->label); ?></option><?php endforeach; ?></select><small><?php echo esc_html__('Use Ctrl or Command to select more than one subject.', 'tnet-profile'); ?></small></section>
    <?php
  }

  private static function render_professional_identities(array $selected, array $suggestions) {
    ?>
    <section class="tnet-profile-basics-card"><div class="tnet-profile-basics-card-title"><span aria-hidden="true">✦</span><h2><?php echo esc_html__('Educator roles', 'tnet-profile'); ?> <small><?php echo esc_html__('optional', 'tnet-profile'); ?></small></h2></div><p class="tnet-profile-basics-help"><?php echo esc_html__('Choose the professional identities you describe yourself with.', 'tnet-profile'); ?></p><?php if ($suggestions) : ?><p class="tnet-profile-basics-suggestion"><?php echo esc_html__('Suggested from your onboarding selections. Select a role below to confirm it for your Profile.', 'tnet-profile'); ?></p><?php endif; ?><div class="tnet-profile-basics-options tnet-profile-basics-role-options"><?php foreach (self::PROFESSIONAL_IDENTITIES as $uuid => $label) : ?><label class="tnet-profile-basics-check"><input type="checkbox" name="professional_identities[]" value="<?php echo esc_attr($uuid); ?>"<?php checked(in_array($uuid, $selected, true)); ?>><span><?php echo esc_html($label); ?><?php if (in_array($uuid, $suggestions, true) && !in_array($uuid, $selected, true)) : ?> <em><?php echo esc_html__('Suggested', 'tnet-profile'); ?></em><?php endif; ?></span></label><?php endforeach; ?></div></section>
    <?php
  }

  private static function render_term_checkbox($term, $name, array $selected) {
    $uuid = (string) ($term->term_uuid ?? '');
    if ($uuid === '') return;
    ?><label class="tnet-profile-basics-check"><input type="checkbox" name="<?php echo esc_attr($name); ?>[]" value="<?php echo esc_attr($uuid); ?>"<?php checked(in_array($uuid, $selected, true)); ?>><span><?php echo esc_html((string) $term->label); ?></span></label><?php
  }

  private static function render_grade_descendants($term, array $by_parent, array $selected) {
    self::render_term_checkbox($term, 'teaching_grades', $selected);
    foreach (($by_parent[(string) ($term->term_uuid ?? '')] ?? []) as $child) {
      self::render_grade_descendants($child, $by_parent, $selected);
    }
  }

  private static function text_length($value) {
    return function_exists('mb_strlen') ? mb_strlen((string) $value) : strlen((string) $value);
  }
}
