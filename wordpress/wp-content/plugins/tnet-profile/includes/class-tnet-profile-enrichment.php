<?php

defined('ABSPATH') || exit;

/**
 * Authenticated, optional guided enrichment and shared Profile card renderer.
 */
final class TNet_Profile_Enrichment {
  const QUERY_VAR = 'tnet_profile_enrichment_route';
  const NONCE = 'tnet_profile_enrichment_save';

  public static function init() {
    add_action('init', [__CLASS__, 'register_route']);
    add_filter('query_vars', [__CLASS__, 'query_vars']);
    add_action('template_redirect', [__CLASS__, 'render_route']);
  }

  public static function register_route() {
    add_rewrite_rule('^profile/enrichment/roles/?$', 'index.php?' . self::QUERY_VAR . '=roles', 'top');
    add_rewrite_rule('^profile/enrichment/complete/?$', 'index.php?' . self::QUERY_VAR . '=complete', 'top');
    add_rewrite_rule('^profile/enrichment/?$', 'index.php?' . self::QUERY_VAR . '=legacy_roles', 'top');
    add_rewrite_rule('^profile/complete/?$', 'index.php?' . self::QUERY_VAR . '=legacy_complete', 'top');
  }

  public static function query_vars($vars) {
    $vars[] = self::QUERY_VAR;
    return $vars;
  }

  public static function url() {
    return home_url('/profile/enrichment/roles/');
  }

  public static function basics_url() {
    return TNet_Profile_Basics::guided_url();
  }

  public static function complete_url() {
    return home_url('/profile/enrichment/complete/');
  }

  public static function enqueue_assets() {
    TNet_Profile_Avatar::enqueue_editor_assets();
    $css = dirname(__DIR__) . '/public/css/tnet-profile-enrichment.css';
    $js = dirname(__DIR__) . '/public/js/tnet-profile-enrichment.js';
    wp_enqueue_style('tnet-profile-enrichment', TNET_PROFILE_PLUGIN_URL . 'public/css/tnet-profile-enrichment.css', ['tnet-profile-basics'], is_readable($css) ? filemtime($css) : '1');
    wp_enqueue_script('tnet-profile-enrichment', TNET_PROFILE_PLUGIN_URL . 'public/js/tnet-profile-enrichment.js', [], is_readable($js) ? filemtime($js) : '1', true);
  }

  public static function render_route() {
    $route = get_query_var(self::QUERY_VAR);
    if ($route === 'legacy_roles' || $route === 'legacy_complete') {
      wp_safe_redirect($route === 'legacy_roles' ? self::url() : self::complete_url(), 302);
      exit;
    }
    if (!in_array($route, ['roles', 'complete'], true)) return;
    status_header(200);
    if (!is_user_logged_in()) {
      wp_safe_redirect(wp_login_url($route === 'complete' ? self::complete_url() : self::url()));
      exit;
    }
    $user_id = get_current_user_id();
    $result = null;
    if ($route === 'roles' && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
      $nonce = sanitize_text_field(wp_unslash($_POST['_wpnonce'] ?? ''));
      if (!wp_verify_nonce($nonce, self::NONCE)) {
        $result = new WP_Error('tnet_profile_enrichment_nonce', __('Your edit session expired. Please try again.', 'tnet-profile'));
      } else {
        $result = TNet_Profile_Basics::save_enrichment($user_id, [
          'professional_identities' => wp_unslash($_POST['professional_identities'] ?? []),
          'teaching_since' => wp_unslash($_POST['teaching_since'] ?? ''),
        ]);
        if (!is_wp_error($result)) {
          wp_safe_redirect(self::complete_url());
          exit;
        }
      }
    }
    $user = get_user_by('id', $user_id);
    $state = TNet_Profile_Basics::state($user_id);
    $roles = TNet_Profile_Basics::professional_identity_terms();
    if (class_exists('TNet_Shared_Shell')) {
      TNet_Profile_Basics::enqueue_assets();
      self::enqueue_assets();
      $shell_config = TNet_Profile_Basics::enrichment_shell_config(
        $route === 'complete' ? __('You’re all set!', 'tnet-profile') : __('Complete your profile', 'tnet-profile'),
        $user,
        static function () use ($route, $user, $state, $roles, $result) {
          if ($route === 'complete') self::render_complete();
          else self::render_main($user, $state, $roles, $result);
        },
        $route === 'complete' ? static function () { self::render_complete_aside(); } : static function () use ($state) { self::render_status($state, true); },
        null,
        false,
        $route === 'complete'
      );
      if ($route === 'complete') $shell_config['route_class'] = 'profile-enrichment-complete';
      TNet_Shared_Shell::render_host($shell_config);
      exit;
    }
    if ($route === 'complete') self::render_complete();
    else self::render_main($user, $state, $roles, $result);
    exit;
  }

  private static function render_main($user, array $state, array $roles, $result) {
    $selected = (array) ($state['selected'] ?? []);
    $scalars = (array) ($state['scalars'] ?? []);
    $allowed = array_fill_keys(array_map(static function ($term) { return (string) $term->term_uuid; }, $roles), true);
    $role_labels = [];
    foreach ($roles as $role) $role_labels[(string) $role->term_uuid] = (string) $role->label;
    $suggested = (array) ($state['suggestions'] ?? []);
    $suggestions = array_values(array_filter(array_keys($role_labels), static function ($uuid) use ($allowed, $suggested) { return isset($allowed[$uuid]) && in_array($uuid, $suggested, true); }));
    ?>
    <main class="tnet-profile-enrichment-main">
      <header class="tnet-profile-basics-heading">
        <h1><?php echo esc_html__('Complete your profile', 'tnet-profile'); ?></h1>
        <p><?php echo esc_html__('Tell us a little about you so we can personalize Teachers.Net for you.', 'tnet-profile'); ?></p>
      </header>
      <?php if (is_wp_error($result)) : ?><p class="tnet-profile-basics-error" role="alert"><?php echo esc_html($result->get_error_message()); ?></p><?php endif; ?>
      <?php self::render_profile_card($user, $state, $roles, ['live' => true, 'edit_photo' => true, 'return_to' => add_query_arg('avatar_modal', '1', self::url())]); ?>

      <form class="tnet-profile-enrichment-form" method="post" action="<?php echo esc_url(self::url()); ?>">
        <?php wp_nonce_field(self::NONCE); ?>
        <section class="tnet-profile-enrichment-card tnet-profile-enrichment-roles" aria-labelledby="tnet-profile-enrichment-roles-title">
          <h2 id="tnet-profile-enrichment-roles-title"><?php echo esc_html__('Your educator roles (optional)', 'tnet-profile'); ?></h2>
          <p class="tnet-profile-enrichment-help"><?php echo esc_html__('Select all roles that describe you. This helps other educators find and connect with you.', 'tnet-profile'); ?></p>
          <?php if ($suggestions) : ?>
            <div class="tnet-profile-enrichment-suggestions" aria-label="<?php echo esc_attr__('Suggested for you', 'tnet-profile'); ?>">
              <strong><?php echo esc_html__('Suggested for you', 'tnet-profile'); ?></strong>
              <p><?php echo esc_html__('Based on information you shared earlier. Select any that apply.', 'tnet-profile'); ?></p>
              <div class="tnet-profile-enrichment-suggestion-list">
                <?php foreach ($suggestions as $uuid) : ?><button type="button" data-suggest-role="<?php echo esc_attr($uuid); ?>" aria-pressed="<?php echo in_array($uuid, (array) ($selected['professional_identity'] ?? []), true) ? 'true' : 'false'; ?>"><?php echo esc_html($role_labels[$uuid]); ?></button><?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
          <div class="tnet-profile-enrichment-role-grid">
            <?php foreach ($roles as $role) : $uuid = (string) $role->term_uuid; ?>
              <label><input type="checkbox" name="professional_identities[]" value="<?php echo esc_attr($uuid); ?>" data-role-choice<?php checked(in_array($uuid, (array) ($selected['professional_identity'] ?? []), true)); ?>><span><?php echo esc_html($role->label); ?></span></label>
            <?php endforeach; ?>
          </div>
        </section>
        <section class="tnet-profile-enrichment-card tnet-profile-enrichment-year" aria-labelledby="tnet-profile-enrichment-year-title">
          <h2 id="tnet-profile-enrichment-year-title"><?php echo esc_html__('Teaching since (optional)', 'tnet-profile'); ?></h2>
          <p class="tnet-profile-enrichment-help"><?php echo esc_html__('The year you started teaching.', 'tnet-profile'); ?></p>
          <label class="screen-reader-text" for="tnet-profile-enrichment-year"><?php echo esc_html__('Teaching since', 'tnet-profile'); ?></label>
          <select id="tnet-profile-enrichment-year" name="teaching_since">
            <option value=""><?php echo esc_html__('Select year', 'tnet-profile'); ?></option>
            <?php for ($year = (int) gmdate('Y'); $year >= 1900; $year--) : ?><option value="<?php echo esc_attr($year); ?>"<?php selected((int) ($scalars['teaching_since'] ?? 0), $year); ?>><?php echo esc_html($year); ?></option><?php endfor; ?>
          </select>
        </section>
        <div class="tnet-profile-enrichment-actions">
          <button class="tnet-profile-basics-secondary" type="submit" name="journey_action" value="done"><?php echo esc_html__("I'm done for now", 'tnet-profile'); ?></button>
          <button class="tnet-profile-basics-save" type="submit" name="journey_action" value="add"><?php echo esc_html__('Add these to my profile', 'tnet-profile'); ?> <span aria-hidden="true">→</span></button>
        </div>
      </form>
    </main>
    <?php
  }

  /** One shared fact card for live enrichment, public-view, and completion surfaces. */
  public static function render_profile_card($user, array $state, array $roles, array $options = []) {
    $live = !empty($options['live']);
    $public_view = !empty($options['public_view']);
    $return_to = (string) ($options['return_to'] ?? TNet_Profile_Avatar::editor_url());
    $selected = (array) ($state['selected'] ?? []);
    $scalars = (array) ($state['scalars'] ?? []);
    $avatar = TNet_Profile_Avatar::resolve_avatar((int) $user->ID, 120);
    $show_details = !$public_view || !empty($scalars['profile_details_public']);
    $show_location = !$public_view || !empty($scalars['location_public']);
    $grade_labels = TNet_Profile_Basics::project_grade_labels((array) ($selected['teaching_grade'] ?? []));
    $subject_labels = TNet_Profile_Basics::term_labels((array) ($selected['teaching_subject'] ?? []));
    $role_map = [];
    foreach ($roles as $role) $role_map[(string) $role->term_uuid] = (string) $role->label;
    $role_labels = [];
    foreach ((array) ($selected['professional_identity'] ?? []) as $uuid) {
      if (isset($role_map[$uuid])) $role_labels[] = $role_map[$uuid];
    }
    $member_since = !empty($user->user_registered) ? mysql2date('Y', $user->user_registered, false) : '';
    ?>
    <section class="tnet-profile-enrichment-card tnet-profile-enrichment-payoff" aria-label="<?php echo esc_attr__('Your Profile', 'tnet-profile'); ?>"<?php echo $live ? ' data-profile-live-card' : ''; ?> data-profile-location-public="<?php echo esc_attr($show_location ? '1' : '0'); ?>" data-profile-details-public="<?php echo esc_attr($show_details ? '1' : '0'); ?>">
      <div class="tnet-profile-enrichment-payoff-top">
        <div class="tnet-profile-enrichment-person">
          <span class="tnet-profile-enrichment-avatar-wrap"><?php if (!empty($avatar['url'])) : ?><img data-profile-avatar src="<?php echo esc_url($avatar['url']); ?>" alt="" width="120" height="120"><?php endif; ?><button class="tnet-avatar-camera" type="button" data-open-avatar-editor aria-label="<?php echo esc_attr__('Change profile photo', 'tnet-profile'); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7.5h3l1.5-2h7l1.5 2h3a1.5 1.5 0 0 1 1.5 1.5v9a1.5 1.5 0 0 1-1.5 1.5h-16A1.5 1.5 0 0 1 2.5 18v-9A1.5 1.5 0 0 1 4 7.5Z"/><circle cx="12" cy="13" r="3.5"/></svg></button></span>
          <div><h2 data-profile-name><?php echo esc_html($user->display_name); ?></h2><p data-profile-username>@<?php echo esc_html($user->user_login); ?></p>
          <p class="tnet-profile-enrichment-location" data-profile-fact-row="location"<?php echo (!$show_location || empty($state['location']['exists'])) ? ' hidden' : ''; ?>><svg viewBox="0 0 20 20" aria-hidden="true"><path d="M10 18s6-5.4 6-10a6 6 0 1 0-12 0c0 4.6 6 10 6 10Z"/><circle cx="10" cy="8" r="2"/></svg><span data-profile-fact-value><?php echo $show_location ? esc_html($state['location']['label'] ?? '') : ''; ?></span></p></div>
        </div>
        <div class="tnet-profile-enrichment-facts" aria-label="<?php echo esc_attr__('Profile details', 'tnet-profile'); ?>">
          <div class="tnet-profile-enrichment-member-row"><span class="tnet-profile-enrichment-fact-icon" aria-hidden="true"><svg viewBox="0 0 20 20"><rect x="3" y="4" width="14" height="13" rx="1.5"/><path d="M6 2.5v3M14 2.5v3M3 8h14"/></svg></span><span><?php echo esc_html(sprintf(__('Member since %s', 'tnet-profile'), $member_since)); ?></span><?php if (!empty($options['edit_photo'])) : ?><button class="tnet-profile-enrichment-action tnet-profile-enrichment-action--edit-photo" type="button" data-open-avatar-editor><?php echo esc_html__('Edit photo', 'tnet-profile'); ?></button><?php else : ?><a class="tnet-profile-enrichment-action" href="<?php echo esc_url(TNet_Profile_Basics::edit_url()); ?>"><?php echo esc_html__('Edit profile', 'tnet-profile'); ?></a><?php endif; ?></div>
          <div class="tnet-profile-enrichment-fact-row" data-profile-fact-row="grade" aria-label="<?php echo esc_attr__('Grades', 'tnet-profile'); ?>"<?php echo (!$show_details || !$grade_labels) ? ' hidden' : ''; ?>><span class="tnet-profile-enrichment-fact-icon" aria-hidden="true"><svg viewBox="0 0 20 20"><path d="m2 7 8-4 8 4-8 4-8-4Z"/><path d="M5 9v4c3 2 7 2 10 0V9m3-2v6"/></svg></span><span data-profile-fact-value><?php echo $show_details ? esc_html(implode(', ', $grade_labels)) : ''; ?></span></div>
          <div class="tnet-profile-enrichment-fact-row" data-profile-fact-row="subject" aria-label="<?php echo esc_attr__('Subjects', 'tnet-profile'); ?>"<?php echo (!$show_details || !$subject_labels) ? ' hidden' : ''; ?>><span class="tnet-profile-enrichment-fact-icon" aria-hidden="true"><svg viewBox="0 0 20 20"><path d="M10 5c-2-1.5-4.5-2-8-1v12c3.5-1 6-.5 8 1m0-12c2-1.5 4.5-2 8-1v12c-3.5-1-6-.5-8 1m0-12v12"/></svg></span><span data-profile-fact-value><?php echo $show_details ? esc_html(implode(', ', $subject_labels)) : ''; ?></span></div>
          <div class="tnet-profile-enrichment-fact-row" data-profile-fact-row="role" aria-label="<?php echo esc_attr__('Educator roles', 'tnet-profile'); ?>"<?php echo (!$show_details || !$role_labels) ? ' hidden' : ''; ?>><span class="tnet-profile-enrichment-fact-icon" aria-hidden="true"><svg viewBox="0 0 20 20"><rect x="2.5" y="5.5" width="15" height="11" rx="1.5"/><path d="M7 5.5v-2h6v2M2.5 9h15m-9 0v2h3V9"/></svg></span><span data-profile-fact-value><?php echo $show_details ? esc_html(implode(', ', $role_labels)) : ''; ?></span></div>
          <div class="tnet-profile-enrichment-fact-row" data-profile-fact-row="teaching_since" aria-label="<?php echo esc_attr__('Teaching since', 'tnet-profile'); ?>"<?php echo (!$show_details || empty($scalars['teaching_since'])) ? ' hidden' : ''; ?>><span class="tnet-profile-enrichment-fact-icon" aria-hidden="true"><svg viewBox="0 0 20 20"><rect x="3" y="4" width="14" height="13" rx="1.5"/><path d="M6 2.5v3M14 2.5v3M3 8h14"/></svg></span><span>Teaching since <span data-profile-fact-value><?php echo $show_details && !empty($scalars['teaching_since']) ? esc_html((string) (int) $scalars['teaching_since']) : ''; ?></span></span></div>
        </div>
      </div>
      <div class="tnet-profile-enrichment-bio" data-profile-fact-row="bio"<?php echo (!$show_details || trim((string) ($scalars['bio'] ?? '')) === '') ? ' hidden' : ''; ?>><span class="screen-reader-text">About</span><span data-profile-fact-value><?php echo $show_details ? nl2br(esc_html($scalars['bio'] ?? '')) : ''; ?></span></div>
    </section>
    <?php TNet_Profile_Avatar::render_modal($return_to); ?>
    <?php
  }

  private static function render_complete() {
    ?>
    <main class="tnet-profile-enrichment-main tnet-profile-enrichment-main--complete">
      <header class="tnet-profile-enrichment-heading">
        <h1><span class="tnet-profile-enrichment-heading__title"><?php echo esc_html__('You’re all set!', 'tnet-profile'); ?><img class="tnet-profile-enrichment-heading__celebration" src="<?php echo esc_url(TNET_PROFILE_PLUGIN_URL . 'public/assets/enrichment-launch/celebration-party-streamers.png'); ?>" alt="" aria-hidden="true" width="42" height="40" decoding="async"></span></h1>
        <p><?php echo esc_html__('Your profile is complete. Here are a few ways to get started on Teachers.Net.', 'tnet-profile'); ?></p>
      </header>
      <?php self::render_next(); ?>
    </main>
    <?php
  }

  /** Layout-only reservation; no ad creative or development ad request. */
  private static function render_complete_aside() {
    echo '<div class="tnet-profile-enrichment-ad-slot" aria-label="' . esc_attr__('Advertisement space', 'tnet-profile') . '"><span>' . esc_html__('Advertisement', 'tnet-profile') . '</span><small>300 × 250</small></div>';
  }

  private static function render_next() {
    // Supplied filenames are reversed: jobs.png is the books scene and
    // lesson-plans.png is the laptop scene. Keep the immutable assets and
    // map them by their approved visual subject.
    ?>
      <section class="tnet-profile-enrichment-launch" aria-label="<?php echo esc_attr__('Ways to get started', 'tnet-profile'); ?>">
        <a class="tnet-profile-enrichment-launch-card" href="<?php echo esc_url(home_url('/chat/')); ?>" data-profile-launch-destination="discussion">
          <span class="tnet-profile-enrichment-launch-card__image" data-profile-launch-image-slot="discussion"><img src="<?php echo esc_url(TNET_PROFILE_PLUGIN_URL . 'public/assets/enrichment-launch/discussion.png'); ?>" alt="" loading="eager" decoding="async"></span>
          <span class="tnet-profile-enrichment-launch-card__content">
            <span class="tnet-profile-enrichment-launch-card__icon tnet-profile-enrichment-launch-card__icon--discussion" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><path d="M20.5 11a8.5 8.5 0 0 1-8.5 8.5 8.8 8.8 0 0 1-3.4-.7L4 20l1.2-4.2A8.5 8.5 0 1 1 20.5 11Z"/></svg></span>
            <strong><?php echo esc_html__('Join a discussion', 'tnet-profile'); ?></strong>
            <span class="tnet-profile-enrichment-launch-card__copy"><?php echo esc_html__('Connect with educators, ask questions, share ideas, and be part of a supportive teaching community.', 'tnet-profile'); ?></span>
            <span class="tnet-profile-enrichment-launch-card__cta"><?php echo esc_html__('Explore discussions', 'tnet-profile'); ?><span aria-hidden="true">→</span></span>
          </span>
        </a>
        <a class="tnet-profile-enrichment-launch-card" href="<?php echo esc_url(home_url('/lessonplans/')); ?>" data-profile-launch-destination="lessons">
          <span class="tnet-profile-enrichment-launch-card__image" data-profile-launch-image-slot="lessons"><img src="<?php echo esc_url(TNET_PROFILE_PLUGIN_URL . 'public/assets/enrichment-launch/jobs.png'); ?>" alt="" loading="lazy" decoding="async"></span>
          <span class="tnet-profile-enrichment-launch-card__content">
            <span class="tnet-profile-enrichment-launch-card__icon tnet-profile-enrichment-launch-card__icon--lessons" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><path d="M4 3h10l6 6v13H4Z"/><path d="M14 3v6h6M8 13h8M8 17h8M8 9h3"/></svg></span>
            <strong><?php echo esc_html__('Browse free lesson plans', 'tnet-profile'); ?></strong>
            <span class="tnet-profile-enrichment-launch-card__copy"><?php echo esc_html__('Discover classroom-ready lesson plans created by teachers, for teachers.', 'tnet-profile'); ?></span>
            <span class="tnet-profile-enrichment-launch-card__cta"><?php echo esc_html__('Explore lesson plans', 'tnet-profile'); ?><span aria-hidden="true">→</span></span>
          </span>
        </a>
        <a class="tnet-profile-enrichment-launch-card" href="<?php echo esc_url(home_url('/jobs/')); ?>" data-profile-launch-destination="jobs">
          <span class="tnet-profile-enrichment-launch-card__image" data-profile-launch-image-slot="jobs"><img src="<?php echo esc_url(TNET_PROFILE_PLUGIN_URL . 'public/assets/enrichment-launch/lesson-plans.png'); ?>" alt="" loading="lazy" decoding="async"></span>
          <span class="tnet-profile-enrichment-launch-card__content">
            <span class="tnet-profile-enrichment-launch-card__icon tnet-profile-enrichment-launch-card__icon--jobs" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5h8v2M3 12h18"/></svg></span>
            <strong><?php echo esc_html__('Find or post a job', 'tnet-profile'); ?></strong>
            <span class="tnet-profile-enrichment-launch-card__copy"><?php echo esc_html__('Explore new opportunities or share a job with the Teachers.Net community.', 'tnet-profile'); ?></span>
            <span class="tnet-profile-enrichment-launch-card__cta"><?php echo esc_html__('Explore jobs', 'tnet-profile'); ?><span aria-hidden="true">→</span></span>
          </span>
        </a>
      </section>
    <?php
  }

  public static function render_status(array $state, $include_enrichment) {
    $selected = (array) ($state['selected'] ?? []);
    $scalars = (array) ($state['scalars'] ?? []);
    $rows = [
      [__('Add location', 'tnet-profile'), !empty($state['location']['exists'])],
      [__('Add grade level(s)', 'tnet-profile'), !empty($selected['teaching_grade'])],
      [__('Add Subject area(s)', 'tnet-profile'), !empty($selected['teaching_subject'])],
      [__('Add a brief bio', 'tnet-profile'), trim((string) ($scalars['bio'] ?? '')) !== ''],
    ];
    if ($include_enrichment) {
      $rows[] = [__('Add educator role(s)', 'tnet-profile'), !empty($selected['professional_identity'])];
      $rows[] = [__('Add teaching start year', 'tnet-profile'), !empty($scalars['teaching_since'])];
    }
    ?>
    <aside class="tnet-profile-enrichment-aside" aria-label="<?php echo esc_attr__('Profile progress', 'tnet-profile'); ?>">
      <section class="tnet-profile-enrichment-card">
        <h2><?php echo esc_html__('Your profile expresses your background', 'tnet-profile'); ?></h2>
        <p class="tnet-profile-enrichment-progress-intro"><?php echo esc_html__('Your profile helps other educators connect with you and helps us personalize your Teachers.Net experience.', 'tnet-profile'); ?></p>
        <ul><?php foreach ($rows as $index => $row) : $fact = ['location', 'grade', 'subject', 'bio', 'role', 'teaching_since'][$index]; ?><li class="<?php echo $row[1] ? 'is-complete' : 'is-pending'; ?>" data-profile-status-fact="<?php echo esc_attr($fact); ?>"><span aria-hidden="true"><?php echo $row[1] ? '✓' : ''; ?></span> <?php echo esc_html($row[0]); ?></li><?php endforeach; ?></ul>
        <p class="tnet-profile-enrichment-tip"><?php echo esc_html__('Tip: You can update your profile anytime from your account menu.', 'tnet-profile'); ?></p>
      </section>
    </aside>
    <?php
  }
}
