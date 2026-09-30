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
    $is_post = ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
    $visibility_toggle = $is_post ? sanitize_key((string) wp_unslash($_POST['profile_visibility_toggle'] ?? '')) : '';
    if (($edit_mode && $is_post) || $visibility_toggle !== '') {
      $error_section = $visibility_toggle !== '' ? 'visibility' : sanitize_key((string) wp_unslash($_POST['profile_edit_section'] ?? ''));
      $nonce = sanitize_text_field((string) wp_unslash($_POST['_wpnonce'] ?? ''));
      if ($visibility_toggle !== '' && !in_array($visibility_toggle, ['details', 'location'], true)) {
        $error = new WP_Error('tnet_profile_visibility_action_invalid', __('Choose a Profile visibility setting to update.', 'tnet-profile'));
      } elseif (!in_array($error_section, self::SECTIONS, true)) {
        $error = new WP_Error('tnet_profile_edit_section_invalid', __('Choose a Profile section to edit.', 'tnet-profile'));
      } elseif (!wp_verify_nonce($nonce, TNet_Profile_Basics::NONCE)) {
        $error = new WP_Error('tnet_profile_edit_nonce', __('Your edit session expired. Please try again.', 'tnet-profile'));
      } else {
        if ($visibility_toggle !== '') {
          $current = TNet_Profile_Basics::state($user_id)['scalars'];
          $key = $visibility_toggle === 'details' ? 'profile_details_public' : 'location_public';
          $saved = TNet_Profile_Basics::save_visibility_settings($user_id, [$visibility_toggle => empty($current[$key])]);
        } else {
          $saved = TNet_Profile_Basics::save_section($user_id, $error_section, wp_unslash($_POST));
        }
        if (is_wp_error($saved)) $error = $saved;
        else {
          $return_url = $visibility_toggle !== '' && !$edit_mode ? home_url('/profile/') : TNet_Profile_Basics::edit_url();
          $status = $visibility_toggle !== '' ? 'visibility-updated' : 'saved';
          wp_safe_redirect(add_query_arg('profile_status', $status, $return_url));
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
    $public_url = add_query_arg('view_as_public', '1', home_url(user_trailingslashit('/profile/' . rawurlencode((string) $user->user_login))));
    $card_projection = TNet_Profile_Public::build_projection($user, $state, $roles, true);
    $card_edit_url = $edit_mode ? home_url('/profile/') : TNet_Profile_Basics::edit_url();
    $status = sanitize_key((string) ($_GET['profile_status'] ?? ''));
    $requested_section = sanitize_key((string) ($_GET['profile_edit_section'] ?? ''));
    if (!in_array($requested_section, self::SECTIONS, true)) $requested_section = '';
    ?>
    <div class="tnet-profile-self<?php echo $edit_mode ? ' tnet-profile-self--editing' : ''; ?>" data-tnet-profile-self data-edit-mode="<?php echo $edit_mode ? 'on' : 'off'; ?>">
      <nav class="tnet-profile-self__breadcrumbs" aria-label="<?php echo esc_attr__('Breadcrumb', 'tnet-profile'); ?>">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('Teachers', 'tnet-profile'); ?></a><span aria-hidden="true">›</span><span aria-current="page"><?php echo esc_html($edit_mode ? __('My Profile (Editing)', 'tnet-profile') : __('My Profile', 'tnet-profile')); ?></span>
      </nav>
      <?php if ($status === 'saved' && $edit_mode) : ?><p class="tnet-profile-self__notice" role="status"><?php echo esc_html__('Your Profile changes were saved.', 'tnet-profile'); ?></p><?php elseif ($status === 'visibility-updated') : ?><p class="tnet-profile-self__notice" role="status"><?php echo esc_html__('Profile visibility updated.', 'tnet-profile'); ?></p><?php elseif ($error && !$edit_mode) : ?><p class="tnet-profile-self__error" role="alert"><?php echo esc_html($error->get_error_message()); ?></p><?php endif; ?>

      <?php TNet_Profile_Public::render_owner_card($card_projection, $edit_mode, $public_url, $card_edit_url, self::icon('visibility')); ?>

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
        <?php self::render_card_header('location', __('Location', 'tnet-profile'), 'tnet-profile-self-location', $edit_mode, !empty($scalars['location_public']) && !empty($location['exists']), !empty($location['exists'])); ?>
        <p class="tnet-profile-self__location"><?php echo esc_html(!empty($location['exists']) ? $location['label'] : __('No location added yet.', 'tnet-profile')); ?></p>
      </section>

      <section class="tnet-profile-self__card" aria-labelledby="tnet-profile-self-groups">
        <?php self::render_card_header('groups', __('Groups', 'tnet-profile'), 'tnet-profile-self-groups', false); ?>
        <p class="tnet-profile-self__empty"><?php echo esc_html__('No groups to show yet.', 'tnet-profile'); ?></p>
      </section>

      <section class="tnet-profile-self__card tnet-profile-self__visibility" aria-labelledby="tnet-profile-self-visibility">
        <?php self::render_card_header('visibility', __('Profile Visibility', 'tnet-profile'), 'tnet-profile-self-visibility', $edit_mode); ?>
        <p class="tnet-profile-self__help"><?php echo esc_html__('Control who can see your profile information.', 'tnet-profile'); ?></p>
        <dl><div><dt><strong><?php echo esc_html__('Profile details', 'tnet-profile'); ?></strong><small><?php echo esc_html__('About + Teaching Profile', 'tnet-profile'); ?></small></dt><dd><?php self::render_visibility_value(!empty($scalars['profile_details_public']), __('Everyone', 'tnet-profile'), __('Just me', 'tnet-profile')); ?></dd></div><div><dt><strong><?php echo esc_html__('Location', 'tnet-profile'); ?></strong></dt><dd><?php self::render_visibility_value(!empty($scalars['location_public']) && !empty($location['exists']), __('Everyone', 'tnet-profile'), __('Just me', 'tnet-profile')); ?></dd></div></dl>
      </section>
    </div>
    <?php if ($edit_mode) foreach (self::SECTIONS as $section) self::render_modal($section, $state, $error_section === $section ? $error : null, $requested_section === $section); ?>
    <?php TNet_Profile_Avatar::render_modal(add_query_arg('avatar_modal', '1', $edit_mode ? TNet_Profile_Basics::edit_url() : home_url('/profile/'))); ?>
    <?php
  }

  private static function render_card_header($section, $title, $id, $edit_mode, $visible = null, $location_exists = false) {
    $has_menu = in_array($section, ['about', 'teaching', 'location', 'visibility'], true);
    ?><header class="tnet-profile-self__card-header"><span class="tnet-profile-self__card-title"><span class="tnet-profile-self__card-icon"><?php echo self::icon($section); ?></span><h2 id="<?php echo esc_attr($id); ?>"><?php echo esc_html($title); ?></h2><?php if ($edit_mode && in_array($section, ['about', 'teaching', 'location'], true) && $visible !== null) self::render_status_icon($visible); ?></span>
      <?php if ($has_menu) self::render_card_menu($section, $edit_mode, $visible, $location_exists); ?>
    </header><?php
  }

  private static function render_card_menu($section, $edit_mode, $visible = null, $location_exists = false) {
    $id = 'tnet-profile-self-card-menu-' . $section;
    $title = [
      'about' => __('About', 'tnet-profile'),
      'teaching' => __('Teaching Profile', 'tnet-profile'),
      'location' => __('Location', 'tnet-profile'),
      'visibility' => __('Profile Visibility', 'tnet-profile'),
    ][$section];
    $edit_url = add_query_arg('profile_edit_section', $section, TNet_Profile_Basics::edit_url());
    $form_action = $edit_mode ? TNet_Profile_Basics::edit_url() : home_url('/profile/');
    $is_self_hover_menu = !$edit_mode && in_array($section, ['about', 'teaching', 'location'], true);
    ?>
    <div class="tnet-profile-self__card-menu<?php echo $is_self_hover_menu ? ' is-self-hover-menu' : ''; ?>" data-profile-card-menu>
      <button type="button" class="tnet-profile-self__menu-trigger tnet-profile-self__card-menu-trigger" data-profile-editor-section="<?php echo esc_attr($section); ?>" aria-label="<?php echo esc_attr(sprintf(__('%s actions', 'tnet-profile'), $title)); ?>" aria-haspopup="menu" aria-expanded="false" aria-controls="<?php echo esc_attr($id); ?>">
        <svg aria-hidden="true" viewBox="0 0 24 24"><circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/></svg>
      </button>
      <div id="<?php echo esc_attr($id); ?>" class="tnet-profile-self__card-menu-panel" role="menu" aria-label="<?php echo esc_attr(sprintf(__('%s actions', 'tnet-profile'), $title)); ?>" hidden>
        <?php if ($section === 'visibility') : ?>
          <?php self::render_edit_menu_item('visibility', __('Edit Profile Visibility', 'tnet-profile'), $edit_mode, $edit_url); ?>
        <?php else : ?>
          <?php self::render_edit_menu_item($section, sprintf(__('Edit %s', 'tnet-profile'), $title), $edit_mode, $edit_url); ?>
          <?php if ($section === 'about' || $section === 'teaching') : ?>
            <?php self::render_visibility_menu_item('details', !empty($visible), $form_action, __('About + Teaching Profile', 'tnet-profile')); ?>
          <?php elseif ($section === 'location' && $location_exists) : ?>
            <?php self::render_visibility_menu_item('location', !empty($visible), $form_action); ?>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
    <?php
  }

  private static function render_edit_menu_item($section, $label, $edit_mode, $edit_url) {
    if ($edit_mode) : ?><button type="button" class="tnet-profile-self__menu-item" role="menuitem" data-profile-editor-open="<?php echo esc_attr($section); ?>"><?php echo self::icon('edit'); ?><span><?php echo esc_html($label); ?></span></button><?php
    else : ?><a class="tnet-profile-self__menu-item" role="menuitem" href="<?php echo esc_url($edit_url); ?>"><?php echo self::icon('edit'); ?><span><?php echo esc_html($label); ?></span></a><?php endif;
  }

  private static function render_visibility_menu_item($setting, $is_public, $form_action, $secondary = '') {
    $label = $setting === 'details'
      ? ($is_public ? __('Hide profile details', 'tnet-profile') : __('Show profile details', 'tnet-profile'))
      : ($is_public ? __('Hide location', 'tnet-profile') : __('Show location', 'tnet-profile'));
    ?>
    <form class="tnet-profile-self__menu-form" method="post" action="<?php echo esc_url($form_action); ?>" role="none">
      <?php wp_nonce_field(TNet_Profile_Basics::NONCE); ?><input type="hidden" name="profile_visibility_toggle" value="<?php echo esc_attr($setting); ?>">
      <button type="submit" class="tnet-profile-self__menu-item" role="menuitem">
        <?php echo self::icon($is_public ? 'eye-off' : 'visibility'); ?><span><?php echo esc_html($label); ?><?php if ($secondary !== '') : ?><small><?php echo esc_html($secondary); ?></small><?php endif; ?></span>
      </button>
    </form>
    <?php
  }

  private static function render_visibility_value($is_public, $public_label, $private_label) {
    ?><span class="tnet-profile-self__visibility-value"><?php echo esc_html($is_public ? $public_label : $private_label); ?><?php self::render_status_icon($is_public); ?></span><?php
  }

  private static function render_status_icon($is_public) {
    $label = $is_public ? __('Visible to everyone', 'tnet-profile') : __('Visible only to you', 'tnet-profile');
    ?><span class="tnet-profile-self__status-icon" role="img" aria-label="<?php echo esc_attr($label); ?>" title="<?php echo esc_attr($label); ?>"><?php echo self::icon($is_public ? 'visibility' : 'eye-off'); ?></span><?php
  }

  private static function render_modal($section, array $state, $error, $auto_open = false) {
    $titles = [
      'about' => __('Edit About', 'tnet-profile'),
      'teaching' => __('Edit Teaching Profile', 'tnet-profile'),
      'location' => __('Edit Location', 'tnet-profile'),
      'visibility' => __('Edit Profile Visibility', 'tnet-profile'),
    ];
    $id = 'tnet-profile-self-modal-' . $section;
    ?><dialog id="<?php echo esc_attr($id); ?>" class="tnet-profile-self-modal" data-profile-editor-dialog="<?php echo esc_attr($section); ?>"<?php echo ($error || $auto_open) ? ' data-profile-editor-open-on-load' : ''; ?> aria-labelledby="<?php echo esc_attr($id . '-title'); ?>">
      <form method="post" action="<?php echo esc_url(TNet_Profile_Basics::edit_url()); ?>" data-profile-editor-form>
        <?php wp_nonce_field(TNet_Profile_Basics::NONCE); ?><input type="hidden" name="profile_edit_section" value="<?php echo esc_attr($section); ?>">
        <header class="tnet-profile-self-modal__header"><h2 id="<?php echo esc_attr($id . '-title'); ?>"><?php echo esc_html($titles[$section]); ?></h2><button type="button" data-profile-editor-close aria-label="<?php echo esc_attr__('Close editor', 'tnet-profile'); ?>">×</button></header>
        <div class="tnet-profile-self-modal__body">
          <?php if ($error) : ?><p class="tnet-profile-self-modal__error" role="alert"><?php echo esc_html($error->get_error_message()); ?></p><?php endif; ?>
          <?php if ($section === 'about') : ?><?php if (empty($state['scalars']['profile_details_public'])) : ?><p class="tnet-profile-self-modal__privacy-note"><?php echo esc_html__('Only you can see these profile details right now.', 'tnet-profile'); ?></p><?php endif; ?><?php TNet_Profile_Basics::render_self_bio_control($state['scalars']); ?>
          <?php elseif ($section === 'teaching') : ?><div class="tnet-profile-basics-wrap--guided tnet-profile-self-modal__teaching-controls">
            <?php if (empty($state['scalars']['profile_details_public'])) : ?><p class="tnet-profile-self-modal__privacy-note"><?php echo esc_html__('Only you can see these profile details right now.', 'tnet-profile'); ?></p><?php endif; ?>
            <?php TNet_Profile_Basics::render_self_teaching_controls($state); ?>
            <section class="tnet-profile-basics-card tnet-profile-self-modal__year"><h3><?php echo esc_html__('Teaching Since', 'tnet-profile'); ?></h3><p><?php echo esc_html__('The year you started teaching (optional).', 'tnet-profile'); ?></p><label class="screen-reader-text" for="<?php echo esc_attr($id . '-year'); ?>"><?php echo esc_html__('Teaching since', 'tnet-profile'); ?></label><input id="<?php echo esc_attr($id . '-year'); ?>" name="teaching_since" type="number" inputmode="numeric" min="1900" max="<?php echo esc_attr(gmdate('Y')); ?>" step="1" value="<?php echo esc_attr($state['scalars']['teaching_since'] ?? ''); ?>" placeholder="YYYY"></section>
          </div><?php
          elseif ($section === 'location') : ?><?php if (!empty($state['location']['exists']) && empty($state['scalars']['location_public'])) : ?><p class="tnet-profile-self-modal__privacy-note"><?php echo esc_html__('Only you can see your location right now.', 'tnet-profile'); ?></p><?php endif; ?><div class="tnet-profile-basics-wrap--guided tnet-profile-self-modal__location-controls"><?php TNet_Profile_Basics::render_self_location_control($state['location']); ?></div><p class="tnet-profile-self-modal__location-note"><?php echo esc_html__('Location visibility is controlled separately in Profile Visibility.', 'tnet-profile'); ?></p>
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
      'about' => '<path d="M6 2.5h8l5.5 5.5v12a1.5 1.5 0 0 1-1.5 1.5H6a1.5 1.5 0 0 1-1.5-1.5V4A1.5 1.5 0 0 1 6 2.5Z"/><path d="M14 2.5V8h5.5M8 7h1.5M8 12h8M8 16h8"/>',
      'teaching' => '<path d="m2.5 8.5 9.5-5 9.5 5-9.5 5-9.5-5Z"/><path d="M6.5 10.6v5.2c3.2 2.6 7.8 2.6 11 0v-5.2M21.5 8.5v6"/>',
      'location' => '<path d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0Z"/><circle cx="12" cy="10" r="2.5"/>',
      'groups' => '<g fill="currentColor" stroke="none"><circle cx="12" cy="6.5" r="3.2"/><circle cx="4.5" cy="9" r="2.5"/><circle cx="19.5" cy="9" r="2.5"/><path d="M12 11.2c-3.3 0-5.7 2.2-5.7 5.2v3.1c0 .7.5 1.2 1.2 1.2h9c.7 0 1.2-.5 1.2-1.2v-3.1c0-3-2.4-5.2-5.7-5.2ZM4.5 13c-2.3 0-4 1.8-4 4v1.5c0 .6.4 1 1 1h3.3v-3.1c0-1.1.3-2.2.8-3.2-.4-.1-.7-.2-1.1-.2Zm15 0c-.4 0-.7.1-1.1.2.5 1 .8 2.1.8 3.2v3.1h3.3c.6 0 1-.4 1-1V17c0-2.2-1.7-4-4-4Z"/></g>',
      'visibility' => '<path d="M2 12s4-6 10-6 10 6 10 6-4 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.5"/>',
      'eye-off' => '<path d="M2 12s4-6 10-6 10 6 10 6-4 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.5"/><path d="m4 4 16 16"/>',
      'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 9h18"/>',
      'edit' => '<path d="m4 17 12-12 3 3L7 20l-4 1 1-4ZM14 7l3 3"/>',
    ];
    if (!isset($paths[$name])) return '';
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths[$name] . '</svg>';
  }
}
