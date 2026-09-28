<?php

defined('ABSPATH') || exit;

/** Owner-only Profile composition shared by self-view and edit mode. */
final class TNet_Profile_Self {
  private const SECTIONS = ['about', 'teaching', 'location', 'visibility'];

  public static function render_route($user_id, $edit_mode = true) {
    $user_id = absint($user_id);
    if (!$user_id || $user_id !== get_current_user_id()) {
      wp_safe_redirect(wp_login_url(home_url($edit_mode ? '/profile/edit/' : '/profile/')));
      exit;
    }
    $user = get_user_by('id', $user_id);
    if (!$user) return;
    $error = null;
    $error_section = '';
    if ($edit_mode && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
      $error_section = sanitize_key((string) wp_unslash($_POST['profile_edit_section'] ?? ''));
      $nonce = sanitize_text_field((string) wp_unslash($_POST['_wpnonce'] ?? ''));
      if (!in_array($error_section, self::SECTIONS, true)) {
        $error = new WP_Error('tnet_profile_edit_section_invalid', __('Choose a Profile section to edit.', 'tnet-profile'));
      } elseif (!wp_verify_nonce($nonce, TNet_Profile_Basics::NONCE)) {
        $error = new WP_Error('tnet_profile_edit_nonce', __('Your edit session expired. Please try again.', 'tnet-profile'));
      } else {
        $saved = TNet_Profile_Basics::save_section($user_id, $error_section, wp_unslash($_POST));
        if (is_wp_error($saved)) $error = $saved;
        else {
          wp_safe_redirect(add_query_arg('profile_status', 'saved', TNet_Profile_Basics::edit_url()));
          exit;
        }
      }
    }

    $state = TNet_Profile_Basics::state($user_id);
    $roles = TNet_Profile_Basics::professional_identity_terms();
    TNet_Profile_Basics::enqueue_assets();
    TNet_Profile_Enrichment::enqueue_assets();
    TNet_Profile_Basics::enqueue_public_profile_assets();
    self::enqueue_assets();
    $main = static function () use ($user, $state, $roles, $edit_mode, $error, $error_section) {
      self::render_content($user, $state, $roles, $edit_mode, $error, $error_section);
    };
    $right = static function () use ($user, $edit_mode) { self::render_right_rail($user, $edit_mode); };
    if (class_exists('TNet_Shared_Shell')) {
      TNet_Shared_Shell::render_host(TNet_Profile_Basics::member_shell_config(
        $edit_mode ? __('Edit Profile', 'tnet-profile') : __('My Profile', 'tnet-profile'),
        $user, $state, $main, $right
      ));
      exit;
    }
    $main();
    exit;
  }

  private static function enqueue_assets() {
    $css = dirname(__DIR__) . '/public/css/tnet-profile-self.css';
    $js = dirname(__DIR__) . '/public/js/tnet-profile-self.js';
    wp_enqueue_style('tnet-profile-self', TNET_PROFILE_PLUGIN_URL . 'public/css/tnet-profile-self.css', ['tnet-profile-public'], is_readable($css) ? filemtime($css) : '1');
    wp_enqueue_script('tnet-profile-self', TNET_PROFILE_PLUGIN_URL . 'public/js/tnet-profile-self.js', [], is_readable($js) ? filemtime($js) : '1', true);
  }

  private static function render_content($user, array $state, array $roles, $edit_mode, $error, $error_section) {
    $selected = $state['selected'];
    $scalars = $state['scalars'];
    $location = $state['location'];
    $grade_labels = TNet_Profile_Basics::project_grade_labels($selected['teaching_grade']);
    $subject_labels = TNet_Profile_Basics::term_labels($selected['teaching_subject']);
    $role_map = [];
    foreach ($roles as $role) $role_map[(string) $role->term_uuid] = (string) $role->label;
    $role_labels = [];
    foreach ($selected['professional_identity'] as $uuid) {
      $label = $role_map[$uuid] ?? (TNet_Profile_Basics::term_labels([$uuid])[0] ?? '');
      if ($label !== '') $role_labels[] = $label;
    }
    $chips = array_slice(array_values(array_unique(array_merge($grade_labels, $subject_labels, $role_labels))), 0, 4);
    $avatar = TNet_Profile_Avatar::resolve_avatar((int) $user->ID, 144);
    $public_url = add_query_arg('view_as_public', '1', home_url(user_trailingslashit('/profile/' . rawurlencode((string) $user->user_login))));
    $status = sanitize_key((string) ($_GET['profile_status'] ?? ''));
    ?>
    <div class="tnet-profile-self<?php echo $edit_mode ? ' tnet-profile-self--editing' : ''; ?>" data-tnet-profile-self data-edit-mode="<?php echo $edit_mode ? 'on' : 'off'; ?>">
      <nav class="tnet-profile-self__breadcrumbs" aria-label="<?php echo esc_attr__('Breadcrumb', 'tnet-profile'); ?>">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('Teachers', 'tnet-profile'); ?></a><span aria-hidden="true">›</span><span aria-current="page"><?php echo esc_html($edit_mode ? __('My Profile (Editing)', 'tnet-profile') : __('My Profile', 'tnet-profile')); ?></span>
      </nav>
      <?php if ($status === 'saved' && $edit_mode) : ?><p class="tnet-profile-self__notice" role="status"><?php echo esc_html__('Your Profile changes were saved.', 'tnet-profile'); ?></p><?php endif; ?>

      <section class="tnet-profile-self__hero" aria-labelledby="tnet-profile-self-name">
        <span class="tnet-profile-self__avatar-wrap"><img src="<?php echo esc_url((string) ($avatar['url'] ?? '')); ?>" alt="" width="128" height="128"><button type="button" class="tnet-avatar-camera" data-open-avatar-editor aria-label="<?php echo esc_attr__('Change profile photo', 'tnet-profile'); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7.5h3l1.5-2h7l1.5 2h3a1.5 1.5 0 0 1 1.5 1.5v9a1.5 1.5 0 0 1-1.5 1.5h-16A1.5 1.5 0 0 1 2.5 18v-9A1.5 1.5 0 0 1 4 7.5Z"/><circle cx="12" cy="13" r="3.5"/></svg></button></span>
        <div class="tnet-profile-self__identity">
          <h1 id="tnet-profile-self-name"><?php echo esc_html($user->display_name); ?></h1>
          <p class="tnet-profile-self__username">@<?php echo esc_html($user->user_login); ?></p>
          <div class="tnet-profile-self__meta">
            <?php if (!empty($location['exists'])) : ?><span><?php echo self::icon('location'); ?><?php echo esc_html($location['label']); ?></span><?php endif; ?>
            <?php if (!empty($user->user_registered)) : ?><span><?php echo self::icon('calendar'); ?><?php echo esc_html(sprintf(__('Member since %s', 'tnet-profile'), mysql2date('M Y', $user->user_registered, false))); ?></span><?php endif; ?>
          </div>
          <?php if ($chips) : ?><ul class="tnet-profile-self__chips" aria-label="<?php echo esc_attr__('Profile highlights', 'tnet-profile'); ?>"><?php foreach ($chips as $chip) : ?><li><?php echo esc_html($chip); ?></li><?php endforeach; ?></ul><?php endif; ?>
        </div>
        <div class="tnet-profile-self__hero-actions">
          <?php if ($edit_mode) : ?><a class="tnet-profile-self__button tnet-profile-self__button--secondary" href="<?php echo esc_url($public_url); ?>"><?php echo self::icon('visibility'); ?><?php echo esc_html__('View as Public', 'tnet-profile'); ?></a><a class="tnet-profile-self__button tnet-profile-self__button--primary" href="<?php echo esc_url(home_url('/profile/')); ?>"><?php echo esc_html__('Done Editing', 'tnet-profile'); ?></a>
          <?php else : ?><a class="tnet-profile-self__button tnet-profile-self__button--secondary" href="<?php echo esc_url($public_url); ?>"><?php echo self::icon('visibility'); ?><?php echo esc_html__('View as Public', 'tnet-profile'); ?></a><a class="tnet-profile-self__button tnet-profile-self__button--primary" href="<?php echo esc_url(TNet_Profile_Basics::edit_url()); ?>"><?php echo esc_html__('Edit Profile', 'tnet-profile'); ?></a><?php endif; ?>
        </div>
      </section>

      <section class="tnet-profile-self__card" aria-labelledby="tnet-profile-self-about">
        <?php self::render_card_header('about', __('About', 'tnet-profile'), 'tnet-profile-self-about', $edit_mode, $scalars['profile_details_public']); ?>
        <?php if (trim((string) $scalars['bio']) !== '') : ?><p class="tnet-profile-self__bio"><?php echo nl2br(esc_html($scalars['bio'])); ?></p><?php else : ?><p class="tnet-profile-self__empty"><?php echo esc_html__('Add a little about yourself.', 'tnet-profile'); ?></p><?php endif; ?>
      </section>

      <section class="tnet-profile-self__card tnet-profile-self__teaching" aria-labelledby="tnet-profile-self-teaching">
        <?php self::render_card_header('teaching', __('Teaching Profile', 'tnet-profile'), 'tnet-profile-self-teaching', $edit_mode, $scalars['profile_details_public']); ?>
        <dl>
          <?php foreach ([
            [__('Grades / Levels', 'tnet-profile'), $grade_labels],
            [__('Subjects', 'tnet-profile'), $subject_labels],
            [__('Educator Roles', 'tnet-profile'), $role_labels],
            [__('Teaching Since', 'tnet-profile'), !empty($scalars['teaching_since']) ? [(string) (int) $scalars['teaching_since']] : []],
          ] as $row) : ?><div><dt><?php echo esc_html($row[0]); ?></dt><dd class="<?php echo $row[1] ? '' : 'is-empty'; ?>"><?php echo esc_html($row[1] ? implode(', ', $row[1]) : __('Not added yet', 'tnet-profile')); ?></dd></div><?php endforeach; ?>
        </dl>
      </section>

      <section class="tnet-profile-self__card" aria-labelledby="tnet-profile-self-location">
        <?php self::render_card_header('location', __('Location', 'tnet-profile'), 'tnet-profile-self-location', $edit_mode, !empty($scalars['location_public']) && !empty($location['exists'])); ?>
        <p class="tnet-profile-self__location"><?php echo esc_html(!empty($location['exists']) ? $location['label'] : __('No location added yet.', 'tnet-profile')); ?></p>
      </section>

      <section class="tnet-profile-self__card" aria-labelledby="tnet-profile-self-groups">
        <?php self::render_card_header('groups', __('Groups', 'tnet-profile'), 'tnet-profile-self-groups', false); ?>
        <p class="tnet-profile-self__empty"><?php echo esc_html__('No groups to show yet.', 'tnet-profile'); ?></p>
      </section>

      <section class="tnet-profile-self__card tnet-profile-self__visibility" aria-labelledby="tnet-profile-self-visibility">
        <?php self::render_card_header('visibility', __('Profile Visibility', 'tnet-profile'), 'tnet-profile-self-visibility', $edit_mode); ?>
        <p class="tnet-profile-self__help"><?php echo esc_html__('Control who can see your profile information.', 'tnet-profile'); ?></p>
        <dl><div><dt><?php echo esc_html__('About and Teaching Profile', 'tnet-profile'); ?></dt><dd><?php echo esc_html(!empty($scalars['profile_details_public']) ? __('Everyone', 'tnet-profile') : __('Only you', 'tnet-profile')); ?></dd></div><div><dt><?php echo esc_html__('Location', 'tnet-profile'); ?></dt><dd><?php echo esc_html(!empty($scalars['location_public']) && !empty($location['exists']) ? __('Everyone', 'tnet-profile') : __('Only you', 'tnet-profile')); ?></dd></div></dl>
      </section>
    </div>
    <?php if ($edit_mode) foreach (self::SECTIONS as $section) self::render_modal($section, $state, $error_section === $section ? $error : null); ?>
    <?php TNet_Profile_Avatar::render_modal(add_query_arg('avatar_modal', '1', $edit_mode ? TNet_Profile_Basics::edit_url() : home_url('/profile/'))); ?>
    <?php
  }

  private static function render_card_header($section, $title, $id, $edit_mode, $visible = null) {
    ?><header class="tnet-profile-self__card-header"><span class="tnet-profile-self__card-icon"><?php echo self::icon($section); ?></span><h2 id="<?php echo esc_attr($id); ?>"><?php echo esc_html($title); ?></h2>
      <?php if ($visible !== null) : ?><span class="tnet-profile-self__badge<?php echo $visible ? ' is-public' : ''; ?>"><?php echo self::icon($visible ? 'visibility' : 'lock'); ?><?php echo esc_html($visible ? __('Visible to others', 'tnet-profile') : __('Only you', 'tnet-profile')); ?></span><?php endif; ?>
      <?php if ($edit_mode && in_array($section, self::SECTIONS, true)) : ?><button type="button" class="tnet-profile-self__button tnet-profile-self__button--edit" data-profile-editor-open="<?php echo esc_attr($section); ?>" aria-label="<?php echo esc_attr(sprintf(__('Edit %s', 'tnet-profile'), $title)); ?>"><?php echo self::icon('edit'); ?><?php echo esc_html__('Edit', 'tnet-profile'); ?></button><?php endif; ?>
    </header><?php
  }

  private static function render_modal($section, array $state, $error) {
    $titles = [
      'about' => __('Edit About', 'tnet-profile'),
      'teaching' => __('Edit Teaching Profile', 'tnet-profile'),
      'location' => __('Edit Location', 'tnet-profile'),
      'visibility' => __('Edit Profile Visibility', 'tnet-profile'),
    ];
    $id = 'tnet-profile-self-modal-' . $section;
    ?><dialog id="<?php echo esc_attr($id); ?>" class="tnet-profile-self-modal" data-profile-editor-dialog="<?php echo esc_attr($section); ?>"<?php echo $error ? ' data-profile-editor-open-on-load' : ''; ?> aria-labelledby="<?php echo esc_attr($id . '-title'); ?>">
      <form method="post" action="<?php echo esc_url(TNet_Profile_Basics::edit_url()); ?>" data-profile-editor-form>
        <?php wp_nonce_field(TNet_Profile_Basics::NONCE); ?><input type="hidden" name="profile_edit_section" value="<?php echo esc_attr($section); ?>">
        <header class="tnet-profile-self-modal__header"><h2 id="<?php echo esc_attr($id . '-title'); ?>"><?php echo esc_html($titles[$section]); ?></h2><button type="button" data-profile-editor-close aria-label="<?php echo esc_attr__('Close editor', 'tnet-profile'); ?>">×</button></header>
        <div class="tnet-profile-self-modal__body">
          <?php if ($error) : ?><p class="tnet-profile-self-modal__error" role="alert"><?php echo esc_html($error->get_error_message()); ?></p><?php endif; ?>
          <?php if ($section === 'about') : TNet_Profile_Basics::render_self_bio_control($state['scalars']); ?>
          <?php elseif ($section === 'teaching') : ?><div class="tnet-profile-basics-wrap--guided tnet-profile-self-modal__teaching-controls">
            <?php TNet_Profile_Basics::render_self_teaching_controls($state); ?>
            <section class="tnet-profile-basics-card tnet-profile-self-modal__year"><h3><?php echo esc_html__('Teaching Since', 'tnet-profile'); ?></h3><p><?php echo esc_html__('The year you started teaching (optional).', 'tnet-profile'); ?></p><label class="screen-reader-text" for="<?php echo esc_attr($id . '-year'); ?>"><?php echo esc_html__('Teaching since', 'tnet-profile'); ?></label><input id="<?php echo esc_attr($id . '-year'); ?>" name="teaching_since" type="number" inputmode="numeric" min="1900" max="<?php echo esc_attr(gmdate('Y')); ?>" step="1" value="<?php echo esc_attr($state['scalars']['teaching_since'] ?? ''); ?>" placeholder="YYYY"></section>
          </div><?php
          elseif ($section === 'location') : ?><div class="tnet-profile-basics-wrap--guided tnet-profile-self-modal__location-controls"><?php TNet_Profile_Basics::render_self_location_control($state['location']); ?></div><p class="tnet-profile-self-modal__location-note"><?php echo esc_html__('Location visibility is controlled separately in Profile Visibility.', 'tnet-profile'); ?></p>
          <?php else : ?><p class="tnet-profile-self-modal__intro"><?php echo esc_html__('Choose what other educators can see on your profile. Sharing your background helps colleagues understand your experience, discover common interests, and connect with you. You can change these settings anytime.', 'tnet-profile'); ?></p>
            <label class="tnet-profile-self-modal__choice"><input type="checkbox" name="details_public" value="1"<?php checked($state['scalars']['profile_details_public']); ?>><span><?php echo esc_html__('Show my About and Teaching Profile publicly', 'tnet-profile'); ?><small><?php echo esc_html__('Share your teaching background and experience with other educators.', 'tnet-profile'); ?></small></span></label>
            <label class="tnet-profile-self-modal__choice"><input type="checkbox" name="location_public" value="1"<?php checked($state['scalars']['location_public']); ?><?php disabled(empty($state['location']['exists'])); ?>><span><?php echo esc_html__('Show my location publicly', 'tnet-profile'); ?><small><?php echo esc_html__('Help nearby educators recognize local colleagues and communities.', 'tnet-profile'); ?></small></span></label>
          <?php endif; ?>
        </div>
        <footer class="tnet-profile-self-modal__actions"><button type="button" class="tnet-profile-self__button tnet-profile-self__button--secondary" data-profile-editor-close><?php echo esc_html__('Cancel', 'tnet-profile'); ?></button><button type="submit" class="tnet-profile-self__button tnet-profile-self__button--primary"><?php echo esc_html__('Save', 'tnet-profile'); ?></button></footer>
      </form>
    </dialog><?php
  }

  private static function render_right_rail($user, $edit_mode) {
    TNet_Profile_Enrichment::render_ad_slot();
    if (!$edit_mode) return;
    $url = add_query_arg('view_as_public', '1', home_url(user_trailingslashit('/profile/' . rawurlencode((string) $user->user_login))));
    ?><aside class="tnet-profile-self__rail-card" aria-label="<?php echo esc_attr__('Editing your profile', 'tnet-profile'); ?>"><h2><?php echo esc_html__('Editing your profile', 'tnet-profile'); ?></h2><p><?php echo esc_html__('Changes are saved section by section. Your profile helps other educators get to know you and connect.', 'tnet-profile'); ?></p><a href="<?php echo esc_url($url); ?>"><?php echo self::icon('visibility'); ?><?php echo esc_html__('View as Public', 'tnet-profile'); ?></a><small><?php echo esc_html__('See how your profile appears to other members.', 'tnet-profile'); ?></small></aside><?php
  }

  private static function icon($name) {
    $paths = [
      'about' => '<path d="M6 3.5h8l4 4v13H6a2 2 0 0 1-2-2v-13a2 2 0 0 1 2-2Z"/><path d="M14 3.5v4h4M8 12h8m-8 3.5h8"/>',
      'teaching' => '<path d="m2.5 8.5 9.5-5 9.5 5-9.5 5-9.5-5Z"/><path d="M6.5 10.6v5.2c3.2 2.6 7.8 2.6 11 0v-5.2M21.5 8.5v6"/>',
      'location' => '<path d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0Z"/><circle cx="12" cy="10" r="2.5"/>',
      'groups' => '<circle cx="12" cy="8" r="3"/><path d="M5 20v-2a7 7 0 0 1 14 0v2M4 8a2.5 2.5 0 0 0 0 5m16-5a2.5 2.5 0 0 1 0 5"/>',
      'visibility' => '<path d="M2 12s4-6 10-6 10 6 10 6-4 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.5"/>',
      'lock' => '<rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
      'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 9h18"/>',
      'edit' => '<path d="m4 17 12-12 3 3L7 20l-4 1 1-4ZM14 7l3 3"/>',
    ];
    if (!isset($paths[$name])) return '';
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths[$name] . '</svg>';
  }
}
