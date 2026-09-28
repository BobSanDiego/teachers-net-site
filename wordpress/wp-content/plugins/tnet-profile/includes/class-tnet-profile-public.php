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
    $right = static function () { TNet_Profile_Enrichment::render_ad_slot(); };
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
  public static function build_projection($user, array $state, array $roles) {
    $scalars = (array) ($state['scalars'] ?? []);
    $show_details = !empty($scalars['profile_details_public']);
    $show_location = !empty($scalars['location_public']) && !empty($state['location']['exists']);
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
        'label' => __('Grades / Levels', 'tnet-profile'),
        'summary' => $grade_groups[0]['short_label'],
        'count' => count($grade_groups) - 1,
        'values' => array_map(static function ($group) {
          return $group['label'] . ($group['children'] ? ' (' . implode(', ', $group['children']) . ')' : '');
        }, $grade_groups),
      ];
    }
    if ($subjects) {
      $sorted_subjects = array_values(array_unique($subjects));
      usort($sorted_subjects, 'strcasecmp');
      $hero_disclosures['subjects'] = [
        'label' => __('Subjects', 'tnet-profile'),
        'summary' => $sorted_subjects[0],
        'count' => count($sorted_subjects) - 1,
        'values' => $sorted_subjects,
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
        'label' => __('Educator Roles', 'tnet-profile'),
        'summary' => $sorted_roles[0],
        'count' => count($sorted_roles) - 1,
        'values' => $sorted_roles,
      ];
    }
    $teaching_since_chip = $show_details && !empty($scalars['teaching_since'])
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
      'teaching_since_chip' => $teaching_since_chip,
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

      <section class="tnet-profile-public__hero" aria-labelledby="tnet-profile-public-name">
        <?php if ($profile['avatar_url'] !== '') : ?><img class="tnet-profile-public__avatar" src="<?php echo esc_url($profile['avatar_url']); ?>" alt="" width="216" height="216"><?php endif; ?>
        <div class="tnet-profile-public__identity">
          <h1 id="tnet-profile-public-name"><?php echo esc_html($profile['display_name']); ?></h1>
          <p class="tnet-profile-public__username">@<?php echo esc_html($profile['username']); ?></p>
          <div class="tnet-profile-public__meta">
            <?php if ($profile['location'] !== '') : ?><span><svg aria-hidden="true" viewBox="0 0 20 20"><path d="M10 18s6-5.4 6-10a6 6 0 1 0-12 0c0 4.6 6 10 6 10Z"/><circle cx="10" cy="8" r="2"/></svg><?php echo esc_html($profile['location']); ?></span><?php endif; ?>
            <?php if ($profile['member_since'] !== '') : ?><span><svg aria-hidden="true" viewBox="0 0 20 20"><rect x="3" y="4" width="14" height="13" rx="1.5"/><path d="M6 2.5v3M14 2.5v3M3 8h14"/></svg><?php echo esc_html(sprintf(__('Member since %s', 'tnet-profile'), $profile['member_since'])); ?></span><?php endif; ?>
          </div>
        </div>
        <?php if ($profile['hero_disclosures'] || $profile['teaching_since_chip'] !== '') : ?>
          <div class="tnet-profile-public__highlights">
            <ul class="tnet-profile-public__chips" aria-label="<?php echo esc_attr__('Profile highlights', 'tnet-profile'); ?>">
              <?php foreach ($profile['hero_disclosures'] as $key => $disclosure) : ?>
                <?php $spoken = $disclosure['label'] . ': ' . $disclosure['summary'] . ($disclosure['count'] ? ' +' . $disclosure['count'] : ''); ?>
                <li><button type="button" class="tnet-profile-public__chip-button" data-public-disclosure aria-expanded="false" aria-controls="tnet-profile-public-<?php echo esc_attr($key); ?>" aria-label="<?php echo esc_attr($spoken . '. ' . __('Show all', 'tnet-profile')); ?>" data-label-closed="<?php echo esc_attr($spoken . '. ' . __('Show all', 'tnet-profile')); ?>" data-label-open="<?php echo esc_attr($spoken . '. ' . __('Hide all', 'tnet-profile')); ?>"><span><?php echo esc_html($disclosure['summary']); ?><?php if ($disclosure['count']) : ?> <span class="tnet-profile-public__chip-count">+<?php echo esc_html($disclosure['count']); ?></span><?php endif; ?></span><svg aria-hidden="true" viewBox="0 0 12 8"><path d="m1 1 5 5 5-5"/></svg></button></li>
              <?php endforeach; ?>
              <?php if ($profile['teaching_since_chip'] !== '') : ?><li><span class="tnet-profile-public__chip-static"><?php echo esc_html($profile['teaching_since_chip']); ?></span></li><?php endif; ?>
            </ul>
            <?php foreach ($profile['hero_disclosures'] as $key => $disclosure) : ?>
              <section id="tnet-profile-public-<?php echo esc_attr($key); ?>" class="tnet-profile-public__disclosure tnet-profile-public__disclosure--<?php echo esc_attr($key); ?>" aria-label="<?php echo esc_attr($disclosure['label']); ?>" hidden>
                <?php if ($key === 'subjects') : ?><ul class="tnet-profile-public__subject-pills"><?php foreach ($disclosure['values'] as $value) : ?><li><?php echo esc_html($value); ?></li><?php endforeach; ?></ul>
                <?php else : ?><ul class="tnet-profile-public__disclosure-lines"><?php foreach ($disclosure['values'] as $value) : ?><li><?php echo esc_html($value); ?></li><?php endforeach; ?></ul><?php endif; ?>
              </section>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>

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
    </main>
    <?php
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
