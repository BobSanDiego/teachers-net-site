<?php

defined('ABSPATH') || exit;

/** Canonical public Profile route; member facts retain their existing privacy policy. */
final class TNet_Profile_Public {
  const QUERY_VAR = 'tnet_profile_public_route';

  public static function init() {
    add_action('init', [__CLASS__, 'register_route']);
    add_filter('query_vars', [__CLASS__, 'query_vars']);
    add_action('template_redirect', [__CLASS__, 'render_route']);
  }

  public static function register_route() {
    add_rewrite_rule('^profile/?$', 'index.php?' . self::QUERY_VAR . '=view', 'top');
    // Place the one-segment username route ahead of WordPress's generic page
    // catch-all, while explicitly leaving current fixed Profile paths to their
    // existing route owners.
    add_rewrite_rule('^profile/(?!edit/?$|enrichment/?$|complete/?$|avatar-components/?$|avatar-component\.svg/?$)([^/]+)/?$', 'index.php?' . self::QUERY_VAR . '=$matches[1]', 'top');
  }

  public static function query_vars($vars) {
    $vars[] = self::QUERY_VAR;
    return $vars;
  }

  public static function render_route() {
    $route = (string) get_query_var(self::QUERY_VAR);
    if ($route === '') return;
    if ($route === 'view') {
      if (!is_user_logged_in()) {
        wp_safe_redirect(wp_login_url(home_url('/profile/')));
        exit;
      }
      $user = get_user_by('id', get_current_user_id());
      if (!$user) self::render_not_found();
      self::render_current_member_view($user);
      return;
    }

    $user = self::find_user($route);
    if (!$user) self::render_not_found();
    $request_path = (string) wp_parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    $canonical_path = (string) wp_parse_url(self::canonical_url($user), PHP_URL_PATH);
    if ($request_path !== $canonical_path) {
      wp_safe_redirect(self::canonical_url($user), 301, 'Teachers.Net Profile');
      exit;
    }
    self::render_public_member_view($user);
  }

  private static function render_current_member_view($user) {
    TNet_Profile_Self::render_route((int) $user->ID, false);
  }

  private static function find_user($username) {
    $username = strtolower(rawurldecode((string) $username));
    if (!preg_match('/^[a-z0-9._-]{3,30}$/D', $username)) return null;
    $user = get_user_by('login', $username);
    if (!$user || (string) $user->user_login !== $username) return null;
    return $user;
  }

  private static function canonical_url($user) {
    return home_url(user_trailingslashit('/profile/' . rawurlencode((string) $user->user_login)));
  }

  private static function render_public_member_view($user) {
    $state = TNet_Profile_Basics::state((int) $user->ID);
    $roles = TNet_Profile_Basics::professional_identity_terms();
    TNet_Profile_Basics::enqueue_public_profile_assets();
    $projection = self::build_projection($user, $state, $roles);
    $owner_preview = is_user_logged_in() && get_current_user_id() === (int) $user->ID
      && ($_GET['view_as_public'] ?? null) === '1';
    if ($owner_preview) {
      add_filter('wp_robots', static function ($robots) { $robots['noindex'] = true; $robots['nofollow'] = true; return $robots; });
      nocache_headers();
    }
    $main = static function () use ($projection, $owner_preview) { self::render_profile($projection, $owner_preview); };
    $right = static function () use ($user) {
      TNet_Profile_Enrichment::render_ad_slot();
      if (!is_user_logged_in()) self::render_join_promotion($user);
    };
    if (class_exists('TNet_Shared_Shell')) {
      $viewer = is_user_logged_in() ? get_user_by('id', get_current_user_id()) : null;
      $config = TNet_Profile_Basics::public_profile_shell_config(__('Member Profile', 'tnet-profile'), $viewer, $main, $right);
      TNet_Shared_Shell::render_host($config);
      exit;
    }
    $main();
    exit;
  }

  /** Build the public projection only from the established Profile read owners. */
  public static function build_projection($user, array $state, array $roles, $owner_view = false) {
    $scalars = (array) ($state['scalars'] ?? []);
    $show_details = $owner_view || !empty($scalars['profile_details_public']);
    $show_location = !empty($state['location']['exists'])
      && ($owner_view || !empty($scalars['location_public']));
    $grades = $show_details ? TNet_Profile_Basics::project_grade_labels((array) ($state['selected']['teaching_grade'] ?? [])) : [];
    $subjects = $show_details ? TNet_Profile_Basics::term_labels((array) ($state['selected']['teaching_subject'] ?? [])) : [];
    $role_map = [];
    foreach ($roles as $role) $role_map[(string) ($role->term_uuid ?? '')] = (string) ($role->label ?? '');
    $role_labels = [];
    if ($show_details) foreach ((array) ($state['selected']['professional_identity'] ?? []) as $uuid) {
      if (!empty($role_map[$uuid])) $role_labels[] = $role_map[$uuid];
    }

    $rows = [];
    if ($grades) $rows[] = ['label' => __('Grades / Levels', 'tnet-profile'), 'values' => $grades, 'icon' => 'grades'];
    if ($subjects) $rows[] = ['label' => __('Subjects', 'tnet-profile'), 'values' => $subjects, 'icon' => 'subjects'];
    if ($role_labels) $rows[] = ['label' => __('Educator Roles', 'tnet-profile'), 'values' => $role_labels, 'icon' => 'roles'];
    if ($show_details && !empty($scalars['teaching_since'])) {
      $rows[] = ['label' => __('Teaching Since', 'tnet-profile'), 'values' => [(string) (int) $scalars['teaching_since']], 'icon' => 'calendar'];
    }

    $grade_groups = $show_details ? TNet_Profile_Basics::project_grade_hero_groups((array) ($state['selected']['teaching_grade'] ?? [])) : [];
    $hero_disclosures = [];
    if ($grade_groups) {
      $hero_disclosures['grades'] = [
        'label' => __('Grades', 'tnet-profile'),
        'summary_values' => array_column($grade_groups, 'label'),
        'has_details' => (bool) array_filter(array_column($grade_groups, 'children')),
        'values' => array_map(static function ($group) {
          return $group['label'] . ($group['children'] ? ' (' . implode(', ', $group['children']) . ')' : '');
        }, $grade_groups),
      ];
    }
    if ($subjects) {
      $short_subjects = TNet_Profile_Basics::term_short_labels((array) ($state['selected']['teaching_subject'] ?? []));
      $sorted_subjects = array_values($short_subjects);
      usort($sorted_subjects, 'strcasecmp');
      $expanded_subjects = array_values($subjects);
      usort($expanded_subjects, 'strcasecmp');
      $hero_disclosures['subjects'] = [
        'label' => __('Subjects', 'tnet-profile'),
        'summary_values' => $sorted_subjects,
        'has_details' => $sorted_subjects !== $expanded_subjects,
        'values' => $expanded_subjects,
      ];
    }
    if ($role_labels) {
      $priority = ['Administrator', 'Mentor Teacher', 'Teacher', 'Student Teacher', 'Substitute Teacher', 'School Counselor', 'Instructional Coach', 'Librarian / Media Specialist', 'Tutor'];
      $sorted_roles = array_values(array_unique($role_labels));
      usort($sorted_roles, static function ($a, $b) use ($priority) {
        $left = array_search($a, $priority, true);
        $right = array_search($b, $priority, true);
        return ($left === false ? count($priority) : $left) <=> ($right === false ? count($priority) : $right);
      });
      $hero_disclosures['roles'] = [
        'label' => __('Roles', 'tnet-profile'),
        'summary_values' => in_array('Mentor Teacher', $sorted_roles, true) ? array_values(array_diff($sorted_roles, ['Teacher'])) : $sorted_roles,
        'has_details' => true,
        'suppressed_count' => in_array('Mentor Teacher', $sorted_roles, true) && in_array('Teacher', $sorted_roles, true) ? 1 : 0,
        'values' => $sorted_roles,
      ];
    }
    $teaching_since_summary = $show_details && !empty($scalars['teaching_since'])
      ? sprintf(__('Teaching since %s', 'tnet-profile'), (string) (int) $scalars['teaching_since']) : '';

    $bio = $show_details ? trim((string) ($scalars['bio'] ?? '')) : '';
    $avatar = TNet_Profile_Avatar::resolve_avatar((int) $user->ID, 216);
    return [
      'display_name' => (string) $user->display_name,
      'username' => (string) $user->user_login,
      'avatar_url' => (string) ($avatar['url'] ?? ''),
      'location' => $show_location ? (string) ($state['location']['label'] ?? '') : '',
      'member_since' => !empty($user->user_registered) ? mysql2date('M Y', $user->user_registered, false) : '',
      'hero_disclosures' => $hero_disclosures,
      'teaching_since_summary' => $teaching_since_summary,
      'about' => $bio,
      'teaching_rows' => $rows,
      // No Groups/BuddyPress membership provider is active in this stack. Keep
      // the approved empty card rather than deriving memberships from rail data.
      'groups_available' => false,
      'groups' => [],
    ];
  }

  private static function render_profile(array $profile, $owner_preview = false) {
    ?>
    <?php if ($owner_preview) : ?><aside class="tnet-profile-public__preview" data-owner-preview><span><?php echo esc_html__('Viewing your profile as others see it', 'tnet-profile'); ?></span><a href="<?php echo esc_url(home_url('/profile/')); ?>"><?php echo esc_html__('← Back to My Profile', 'tnet-profile'); ?></a></aside><?php endif; ?>
    <main class="tnet-profile-public" data-tnet-public-profile>
      <nav class="tnet-profile-public__breadcrumbs" aria-label="<?php echo esc_attr__('Breadcrumb', 'tnet-profile'); ?>">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('Teachers', 'tnet-profile'); ?></a>
        <span aria-hidden="true">›</span><span><?php echo esc_html__('Members', 'tnet-profile'); ?></span>
        <span aria-hidden="true">›</span><span aria-current="page"><?php echo esc_html($profile['display_name']); ?></span>
      </nav>

      <div class="tnet-profile-public__content-column">
      <?php self::render_hero_card($profile); ?>

      <?php if ($profile['about'] !== '') : ?>
        <section class="tnet-profile-public__card" aria-labelledby="tnet-profile-public-about"><h2 id="tnet-profile-public-about"><?php echo esc_html__('About', 'tnet-profile'); ?></h2><p class="tnet-profile-public__bio"><?php echo nl2br(esc_html($profile['about'])); ?></p></section>
      <?php endif; ?>

      <?php if ($profile['teaching_rows']) : ?>
        <section class="tnet-profile-public__card tnet-profile-public__teaching" aria-labelledby="tnet-profile-public-teaching"><h2 id="tnet-profile-public-teaching"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="m2.5 8.5 9.5-5 9.5 5-9.5 5-9.5-5Z"/><path d="M6.5 10.6v5.2c3.2 2.6 7.8 2.6 11 0v-5.2M21.5 8.5v6"/></svg><?php echo esc_html__('Teaching Profile', 'tnet-profile'); ?></h2>
          <dl><?php foreach ($profile['teaching_rows'] as $row) : ?><div class="tnet-profile-public__teaching-row"><dt><?php echo esc_html($row['label']); ?></dt><dd><?php echo esc_html(implode(', ', $row['values'])); ?></dd></div><?php endforeach; ?></dl>
        </section>
      <?php endif; ?>

      <section class="tnet-profile-public__card tnet-profile-public__groups" aria-labelledby="tnet-profile-public-groups">
        <h2 id="tnet-profile-public-groups"><?php echo esc_html__('Groups', 'tnet-profile'); ?></h2>
        <p><?php echo esc_html__('No groups to show yet.', 'tnet-profile'); ?></p>
      </section>
      </div>
    </main>
    <?php
  }

  /** Render the same accepted card on owner routes, showing private owner facts. */
  public static function render_owner_card(array $profile, $edit_mode, $public_url, $edit_url, $visibility_icon) {
    self::render_hero_card($profile, [
      'edit_mode' => (bool) $edit_mode,
      'public_url' => $public_url,
      'edit_url' => $edit_url,
      'visibility_icon' => $visibility_icon,
    ]);
  }

  private static function render_hero_card(array $profile, array $owner = []) {
    $owner_view = !empty($owner);
    $compact_self_view = $owner_view && empty($owner['edit_mode']);
    $owner_edit_view = $owner_view && !empty($owner['edit_mode']);
    ?>
      <section class="tnet-profile-public__hero<?php echo $owner_view ? ' tnet-profile-public__hero--owner' : ''; ?><?php echo $compact_self_view ? ' tnet-profile-public__hero--owner-self' : ''; ?><?php echo $owner_edit_view ? ' tnet-profile-public__hero--owner-edit' : ''; ?>" aria-labelledby="tnet-profile-public-name">
        <?php if ($profile['avatar_url'] !== '') : ?><span class="tnet-profile-public__avatar-wrap"><img class="tnet-profile-public__avatar" src="<?php echo esc_url($profile['avatar_url']); ?>" alt="" width="216" height="216"><?php if ($owner_view) : ?><button type="button" class="tnet-avatar-camera" data-open-avatar-editor aria-label="<?php echo esc_attr__('Change profile photo', 'tnet-profile'); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7.5h3l1.5-2h7l1.5 2h3a1.5 1.5 0 0 1 1.5 1.5v9a1.5 1.5 0 0 1-1.5 1.5h-16A1.5 1.5 0 0 1 2.5 18v-9A1.5 1.5 0 0 1 4 7.5Z"/><circle cx="12" cy="13" r="3.5"/></svg></button><?php endif; ?></span><?php endif; ?>
        <div class="tnet-profile-public__identity<?php echo $compact_self_view ? ' tnet-profile-public__identity--owner-self' : ''; ?><?php echo $owner_edit_view ? ' tnet-profile-public__identity--owner-edit' : ''; ?>">
          <h1 id="tnet-profile-public-name"><span class="tnet-profile-public__owner-display-name"><?php echo esc_html($profile['display_name']); ?></span><span class="tnet-profile-public__owner-handle"><span>@<?php echo esc_html($profile['username']); ?></span></span></h1>
        </div>
        <?php if ($compact_self_view) : ?><div class="tnet-profile-self__menu-wrap"><button type="button" class="tnet-profile-self__menu-trigger" aria-label="<?php echo esc_attr__('Profile actions', 'tnet-profile'); ?>" aria-haspopup="menu" aria-expanded="false" aria-controls="tnet-profile-self-actions"><svg aria-hidden="true" viewBox="0 0 24 24"><circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/></svg></button><div id="tnet-profile-self-actions" class="tnet-profile-self__menu" role="menu" aria-label="<?php echo esc_attr__('Profile actions', 'tnet-profile'); ?>" hidden><a role="menuitem" href="<?php echo esc_url($owner['edit_url']); ?>"><?php echo esc_html__('Edit Profile', 'tnet-profile'); ?></a><a role="menuitem" href="<?php echo esc_url($owner['public_url']); ?>"><?php echo esc_html__('View as Public', 'tnet-profile'); ?></a></div></div>
        <?php elseif ($owner_edit_view) : ?><div class="tnet-profile-self__hero-actions"><a class="tnet-profile-self__button tnet-profile-self__button--primary" href="<?php echo esc_url($owner['edit_url']); ?>"><?php echo esc_html__('Done Editing', 'tnet-profile'); ?></a></div><?php endif; ?>
        <?php if ($profile['hero_disclosures'] || $profile['location'] !== '' || $profile['teaching_since_summary'] !== '' || $profile['member_since'] !== '') : ?>
          <div class="tnet-profile-public__highlights">
            <?php if ($profile['hero_disclosures'] || $profile['location'] !== '') : ?><div class="tnet-profile-public__summary" aria-label="<?php echo esc_attr__('Profile highlights', 'tnet-profile'); ?>">
              <?php if ($profile['location'] !== '') : ?><div class="tnet-profile-public__summary-row tnet-profile-public__summary-row--location"><svg class="tnet-profile-public__summary-icon tnet-profile-public__summary-location-icon" aria-hidden="true" focusable="false" viewBox="0 0 20 20"><path d="M10 18s6-5.4 6-10a6 6 0 1 0-12 0c0 4.6 6 10 6 10Z"/><circle cx="10" cy="8" r="2"/></svg><strong class="tnet-profile-public__summary-label"><?php echo esc_html__('Location', 'tnet-profile'); ?></strong><div class="tnet-profile-public__summary-content tnet-profile-public__summary-content--location"><span class="tnet-profile-public__summary-values"><span class="tnet-profile-public__summary-value"><span class="tnet-profile-public__summary-term"><?php echo esc_html($profile['location']); ?></span></span></span></div></div><?php endif; ?>
              <?php foreach ($profile['hero_disclosures'] as $key => $disclosure) : ?>
                <div class="tnet-profile-public__summary-row" data-public-summary data-suppressed-count="<?php echo esc_attr((string) ($disclosure['suppressed_count'] ?? 0)); ?>" data-has-details="<?php echo !empty($disclosure['has_details']) ? '1' : '0'; ?>">
                  <?php if ($key === 'grades') : ?>
                    <svg class="tnet-profile-public__summary-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24"><path d="M1.5 8.1 12 3l10.5 5.1L12 13.2 1.5 8.1Z" fill="currentColor"/><path d="M5 11v5.2c4.1 3.1 9.9 3.1 14 0V11M21.5 8.5v7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  <?php elseif ($key === 'subjects') : ?>
                    <svg class="tnet-profile-public__summary-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24"><path d="M12 6.2C9.5 4 6.3 3.5 2 4.6v14c4.3-1.1 7.5-.6 10 1.6 2.5-2.2 5.7-2.7 10-1.6v-14c-4.3-1.1-7.5-.6-10 1.6Zm0 0v14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  <?php else : ?>
                    <svg class="tnet-profile-public__summary-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24"><circle cx="12" cy="7" r="3.1" fill="currentColor"/><circle cx="4.7" cy="9" r="2.4" fill="currentColor"/><circle cx="19.3" cy="9" r="2.4" fill="currentColor"/><path d="M5.5 18.7c.3-4 2.5-6.2 6.5-6.2s6.2 2.2 6.5 6.2c-3.9 2.1-9.1 2.1-13 0ZM1.3 18c0-2.8 1.1-4.6 3.3-5.2.8-.2 1.5-.2 2.2 0-1.5 1.2-2.4 3-2.6 5.4-1.1.1-2.1 0-2.9-.2Zm21.4 0c-1 .2-2 .3-2.9.2-.2-2.4-1.1-4.2-2.6-5.4.7-.2 1.4-.2 2.2 0 2.2.6 3.3 2.4 3.3 5.2Z" fill="currentColor"/></svg>
                  <?php endif; ?>
                  <strong class="tnet-profile-public__summary-label"><?php echo esc_html($disclosure['label']); ?></strong>
                  <div class="tnet-profile-public__summary-content"><span class="tnet-profile-public__summary-values"><?php foreach ($disclosure['summary_values'] as $index => $value) : ?><span class="tnet-profile-public__summary-value" data-summary-value><?php if ($index) : ?><span class="tnet-profile-public__separator" aria-hidden="true">·</span><?php endif; ?><span class="tnet-profile-public__summary-term"><?php echo esc_html($value); ?></span></span><?php endforeach; ?></span><button type="button" class="tnet-profile-public__more" data-public-disclosure aria-expanded="false" aria-controls="tnet-profile-public-<?php echo esc_attr($key); ?>" aria-label="<?php echo esc_attr(sprintf(__('Show all %s', 'tnet-profile'), $disclosure['label'])); ?>" data-label-closed="<?php echo esc_attr(sprintf(__('Show all %s', 'tnet-profile'), $disclosure['label'])); ?>" data-label-open="<?php echo esc_attr(sprintf(__('Hide all %s', 'tnet-profile'), $disclosure['label'])); ?>"><span data-more-label></span><svg aria-hidden="true" viewBox="0 0 12 8"><path d="m1 1 5 5 5-5"/></svg></button></div>
                </div>
              <?php endforeach; ?>
            </div><?php endif; ?>
            <?php if ($profile['teaching_since_summary'] !== '' || $profile['member_since'] !== '') : ?><p class="tnet-profile-public__since"><svg aria-hidden="true" focusable="false" viewBox="0 0 24 24"><rect x="2.5" y="4.5" width="19" height="17" rx="2"/><path d="M7 2.5v4M17 2.5v4M2.5 9.5h19M7 14h3M14 14h3M7 18h3"/></svg><?php if ($profile['teaching_since_summary'] !== '') : ?><span><?php echo esc_html($profile['teaching_since_summary']); ?></span><?php endif; ?><?php if ($profile['teaching_since_summary'] !== '' && $profile['member_since'] !== '') : ?><span class="tnet-profile-public__since-separator" aria-hidden="true">·</span><?php endif; ?><?php if ($profile['member_since'] !== '') : ?><span><?php echo esc_html(sprintf(__('Member since %s', 'tnet-profile'), $profile['member_since'])); ?></span><?php endif; ?></p><?php endif; ?>
            <?php foreach ($profile['hero_disclosures'] as $key => $disclosure) : ?>
              <section id="tnet-profile-public-<?php echo esc_attr($key); ?>" class="tnet-profile-public__disclosure tnet-profile-public__disclosure--<?php echo esc_attr($key); ?>" role="region" aria-label="<?php echo esc_attr($disclosure['label']); ?>" hidden>
                <div class="tnet-profile-public__disclosure-header"><strong><?php echo esc_html(sprintf(__('All %s', 'tnet-profile'), $disclosure['label'])); ?></strong><button type="button" data-public-hide="<?php echo esc_attr($key); ?>" aria-label="<?php echo esc_attr(sprintf(__('Hide all %s', 'tnet-profile'), $disclosure['label'])); ?>">Hide <span aria-hidden="true">⌃</span></button></div>
                <?php if ($key === 'subjects') : ?><ul class="tnet-profile-public__subject-pills"><?php foreach ($disclosure['values'] as $value) : ?><li><?php echo esc_html($value); ?></li><?php endforeach; ?></ul>
                <?php else : ?><ul class="tnet-profile-public__disclosure-lines"><?php foreach ($disclosure['values'] as $value) : ?><li><?php echo esc_html($value); ?></li><?php endforeach; ?></ul><?php endif; ?>
              </section>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>
    <?php
  }

  private static function render_join_promotion($user) {
    $signup = home_url('/account/sign-up/');
    if ($signup === '') return;
    ?><aside class="tnet-profile-public__join" aria-label="<?php echo esc_attr__('Join Teachers.Net', 'tnet-profile'); ?>"><h2><?php echo esc_html__('Join Teachers.Net', 'tnet-profile'); ?></h2><p><?php echo esc_html(sprintf(__('Join the Teachers.Net community and explore educator discussions alongside %s.', 'tnet-profile'), (string) $user->display_name)); ?></p><a class="tnet-profile-public__join-cta" href="<?php echo esc_url($signup); ?>"><?php echo esc_html__('Join Teachers.Net', 'tnet-profile'); ?></a><p><?php echo esc_html__('Already a member?', 'tnet-profile'); ?> <a href="<?php echo esc_url(wp_login_url(self::canonical_url($user))); ?>"><?php echo esc_html__('Log in', 'tnet-profile'); ?></a></p></aside><?php
  }

  private static function render_not_found() {
    global $wp_query;
    if (is_object($wp_query) && method_exists($wp_query, 'set_404')) $wp_query->set_404();
    status_header(404);
    nocache_headers();
    $template = get_404_template();
    if ($template) include $template;
    exit;
  }
}
