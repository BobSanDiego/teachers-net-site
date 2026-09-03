<?php

if (!defined('ABSPATH')) {
  exit;
}

final class TNet_Shared_Shell {
  const CONTRACT_VERSION = '2.0';

  public static function init() {
    // The platform owner exposes assets and configuration primitives. Consumers
    // opt in explicitly; no production consumer is migrated by registration.
  }

  public static function enqueue_assets($context = 'canonical') {
    $css = TNET_SHARED_SHELL_PLUGIN_DIR . 'public/css/tnet-shared-shell.css';
    $js = TNET_SHARED_SHELL_PLUGIN_DIR . 'public/js/tnet-shared-shell.js';
    $parity_css = TNET_SHARED_SHELL_PLUGIN_DIR . 'public/css/tnet-shared-shell-parity.css';
    $parity_js = TNET_SHARED_SHELL_PLUGIN_DIR . 'public/js/tnet-shared-shell-parity.js';
    $responsive_correction_css = TNET_SHARED_SHELL_PLUGIN_DIR . 'public/css/tnet-shared-shell-responsive-correction.css';

    wp_enqueue_style(
      'tnet-shared-shell',
      TNET_SHARED_SHELL_PLUGIN_URL . 'public/css/tnet-shared-shell.css',
      [],
      self::asset_version($css)
    );
    wp_enqueue_script(
      'tnet-shared-shell',
      TNET_SHARED_SHELL_PLUGIN_URL . 'public/js/tnet-shared-shell.js',
      [],
      self::asset_version($js),
      true
    );
    // Canonical v2 uses the HUMAN-accepted presentation assets. The legacy
    // shell-lab context remains a temporary comparison-oracle alias only.
    if (in_array(sanitize_key((string) $context), ['canonical', 'shell-lab'], true)) {
      wp_enqueue_style(
        'tnet-shared-shell-parity',
        TNET_SHARED_SHELL_PLUGIN_URL . 'public/css/tnet-shared-shell-parity.css',
        ['tnet-shared-shell'],
        self::asset_version($parity_css)
      );
      wp_enqueue_script(
        'tnet-shared-shell-parity',
        TNET_SHARED_SHELL_PLUGIN_URL . 'public/js/tnet-shared-shell-parity.js',
        ['tnet-shared-shell'],
        self::asset_version($parity_js),
        true
      );
      wp_enqueue_style(
        'tnet-shared-shell-responsive-correction',
        TNET_SHARED_SHELL_PLUGIN_URL . 'public/css/tnet-shared-shell-responsive-correction.css',
        ['tnet-shared-shell-parity'],
        self::asset_version($responsive_correction_css)
      );
    }
    wp_localize_script('tnet-shared-shell', 'TNetSharedShellConfig', [
      'contractVersion' => self::CONTRACT_VERSION,
      'context' => sanitize_key((string) $context),
    ]);
    if (class_exists('TNet_Notifications')) {
      TNet_Notifications::enqueue_client_assets();
    }
  }

  public static function navigation_config(array $destinations, array $available = []) {
    $available = array_fill_keys(array_map('sanitize_key', $available), true);
    return array_values(array_filter($destinations, static function ($destination) use ($available) {
      $key = sanitize_key((string) ($destination['key'] ?? ''));
      return $key !== '' && (empty($available) || isset($available[$key]));
    }));
  }

  /**
   * Render the reusable shell. Consumers provide data and a content callback;
   * this owner retains the shell DOM and interaction seams.
   */
  public static function render_host(array $config = []) {
    $contract = sanitize_key((string) ($config['contract'] ?? 'legacy'));
    if ($contract === 'canonical') {
      self::render_canonical_presentation($config);
      return;
    }
    $brand = (array) ($config['brand'] ?? []);
    $product_identity = (array) ($config['product_identity'] ?? []);
    $navigation = self::navigation_config((array) ($config['navigation'] ?? []), (array) ($config['available_navigation'] ?? []));
    $account = (array) ($config['account'] ?? []);
    $notifications = (array) ($config['notifications'] ?? []);
    $compact = (array) ($config['compact_navigation'] ?? []);
    $footer = (array) ($config['footer'] ?? []);
    $classes = array_filter(array_map('sanitize_html_class', (array) ($config['body_classes'] ?? [])));
    $root_classes = array_filter(array_map('sanitize_html_class', (array) ($config['root_classes'] ?? [])));
    $content_classes = array_filter(array_map('sanitize_html_class', (array) ($config['content_classes'] ?? [])));
    $root_attributes = (array) ($config['root_attributes'] ?? []);
    $root_attributes['data-shell-owner'] = 'tnet-shared-shell';
    $root_attributes['data-tnet-shared-shell'] = true;
    $root_attributes['data-shell-contract-version'] = self::CONTRACT_VERSION;
    $document_title = (string) ($config['document_title'] ?? 'Teachers.Net');
    $content = $config['content'] ?? null;
    $esc_attr = static function ($value) { return esc_attr((string) $value); };
    $render_link = static function (array $link, $class = '') use ($esc_attr) {
      $label = (string) ($link['label'] ?? '');
      $url = (string) ($link['url'] ?? '#');
      if ($label === '') return;
      printf('<a%s href="%s">%s</a>', $class ? ' class="' . esc_attr($class) . '"' : '', esc_url($url), esc_html($label));
    };
    $render_item = null;
    $render_item = static function (array $item, $id, $class = '') use (&$render_item, $render_link, $esc_attr) {
      $label = (string) ($item['label'] ?? '');
      $children = (array) ($item['children'] ?? []);
      if ($label === '') return;
      if (!$children) {
        if (!empty($item['url'])) $render_link($item, $class);
        else printf('<span%s>%s</span>', $class ? ' class="' . esc_attr($class) . '"' : '', esc_html($label));
        return;
      }
      $panel_id = 'tnet-shared-shell-nested-' . sanitize_html_class($id);
      ?>
      <div class="tnet-shared-shell__nested">
        <button type="button" class="tnet-shared-shell__nested-trigger" data-tnet-shell-nested-disclosure aria-expanded="false" aria-controls="<?php echo esc_attr($panel_id); ?>"><span><?php echo esc_html($label); ?></span><span class="tnet-shared-shell__caret" aria-hidden="true"></span></button>
        <div id="<?php echo esc_attr($panel_id); ?>" class="tnet-shared-shell__nested-panel" data-tnet-shell-nested-panel hidden>
          <?php foreach ($children as $child_index => $child) : $render_item((array) $child, $id . '-' . $child_index, 'tnet-shared-shell__taxonomy-item'); endforeach; ?>
        </div>
      </div>
      <?php
    };
    $render_menu_contents = static function (array $item, $menu_id) use ($render_item, $render_link) {
      $flows = (array) ($item['flows'] ?? []);
      $sections = (array) ($item['menu_sections'] ?? []);
      $menu = (array) ($item['menu'] ?? []);
      if ($flows) : $initial_flow = sanitize_key((string) ($item['initial_flow'] ?? ($flows[0]['key'] ?? ''))); ?>
        <div class="tnet-shared-shell__flow-menu" data-tnet-shell-flow-menu>
          <?php foreach ($flows as $flow_index => $flow) : $flow_key = sanitize_key((string) ($flow['key'] ?? $flow_index)); ?>
            <section class="tnet-shared-shell__menu-section" data-tnet-shell-flow-panel="<?php echo esc_attr($flow_key); ?>"<?php echo $flow_key === $initial_flow ? '' : ' hidden'; ?>>
              <p class="tnet-shared-shell__menu-heading"><?php echo esc_html((string) ($flow['label'] ?? '')); ?></p>
              <?php foreach ((array) ($flow['items'] ?? []) as $entry_index => $entry) : $render_item((array) $entry, $menu_id . '-flow-' . $flow_index . '-' . $entry_index, 'tnet-shared-shell__menu-link'); endforeach; ?>
            </section>
          <?php endforeach; ?>
          <div class="tnet-shared-shell__flow-controls" aria-label="Choose navigation group">
            <?php foreach ($flows as $flow_index => $flow) : $flow_key = sanitize_key((string) ($flow['key'] ?? $flow_index)); ?><button type="button" data-tnet-shell-flow-switch="<?php echo esc_attr($flow_key); ?>" aria-pressed="<?php echo $flow_key === $initial_flow ? 'true' : 'false'; ?>"><?php echo esc_html((string) ($flow['label'] ?? '')); ?></button><?php endforeach; ?>
          </div>
        </div>
      <?php elseif ($sections) : ?>
        <?php foreach ($sections as $section_index => $section) : ?>
          <section class="tnet-shared-shell__menu-section">
            <?php if (!empty($section['label'])) : ?><p class="tnet-shared-shell__menu-heading"><?php echo esc_html((string) $section['label']); ?></p><?php endif; ?>
            <?php foreach ((array) ($section['items'] ?? []) as $entry_index => $entry) : $render_item((array) $entry, $menu_id . '-section-' . $section_index . '-' . $entry_index, 'tnet-shared-shell__menu-link'); endforeach; ?>
          </section>
        <?php endforeach; ?>
      <?php else : ?>
        <?php foreach ($menu as $entry_index => $entry) : $render_item((array) $entry, $menu_id . '-item-' . $entry_index, 'tnet-shared-shell__menu-link'); endforeach; ?>
      <?php endif;
    };
    ?>
    <!doctype html>
    <html <?php language_attributes(); ?>><head>
      <meta charset="<?php bloginfo('charset'); ?>">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo esc_html($document_title); ?></title>
      <?php wp_head(); ?>
    </head><body <?php body_class($classes); ?>>
      <?php if (function_exists('wp_body_open')) wp_body_open(); ?>
      <div class="tnet-shared-shell<?php echo $root_classes ? ' ' . esc_attr(implode(' ', $root_classes)) : ''; ?>"<?php foreach ($root_attributes as $name => $value) : ?> <?php echo esc_attr($name); ?>="<?php echo $value === true ? 'true' : $esc_attr($value); ?>"<?php endforeach; ?>>
        <header class="tnet-shared-shell__header" data-tnet-shell-header>
          <div class="tnet-shared-shell__header-inner">
            <a class="tnet-shared-shell__brand" href="<?php echo esc_url((string) ($brand['url'] ?? '#')); ?>" aria-label="<?php echo esc_attr((string) ($brand['aria_label'] ?? $brand['alt'] ?? 'Home')); ?>">
              <?php if (!empty($brand['image'])) : ?><img src="<?php echo esc_url((string) $brand['image']); ?>" alt="<?php echo esc_attr((string) ($brand['alt'] ?? '')); ?>"><?php else : ?><span><?php echo esc_html((string) ($brand['label'] ?? 'Teachers.Net')); ?></span><?php endif; ?>
            </a>
            <div class="tnet-shared-shell__header-main">
              <?php if (!empty($product_identity['label'])) : ?><span class="tnet-shared-shell__product-identity"><?php echo esc_html((string) $product_identity['label']); ?></span><?php endif; ?>
            <nav class="tnet-shared-shell__navigation" aria-label="<?php echo esc_attr((string) ($config['navigation_label'] ?? 'Primary navigation')); ?>">
              <?php foreach ($navigation as $index => $item) : $menu_id = 'tnet-shared-shell-menu-' . absint($index); $has_menu = !empty($item['menu']) || !empty($item['menu_sections']) || !empty($item['flows']); ?>
                <?php if ($has_menu) : ?><div class="tnet-shared-shell__menu<?php echo !empty($item['current']) ? ' is-current' : ''; ?>" data-tnet-shell-menu>
                  <button type="button" class="tnet-shared-shell__trigger" data-tnet-shell-disclosure aria-expanded="false" aria-controls="<?php echo esc_attr($menu_id); ?>" aria-haspopup="true"><span class="tnet-shared-shell__trigger-label"><?php echo esc_html((string) ($item['label'] ?? 'Menu')); ?></span><span class="tnet-shared-shell__caret" aria-hidden="true"></span></button>
                  <div id="<?php echo esc_attr($menu_id); ?>" class="tnet-shared-shell__panel" data-tnet-shell-panel hidden>
                    <?php $render_menu_contents((array) $item, $menu_id); ?>
                  </div>
                </div><?php else : ?><a href="<?php echo esc_url((string) ($item['url'] ?? '#')); ?>"><?php echo esc_html((string) ($item['label'] ?? '')); ?></a><?php endif; ?>
              <?php endforeach; ?>
            </nav>
            <div class="tnet-shared-shell__utilities">
              <?php if ($notifications) : $notification_id = 'tnet-shared-shell-notifications'; ?><div class="tnet-shared-shell__menu" data-tnet-shell-menu>
                <button type="button" class="tnet-shared-shell__utility tnet-shared-shell__notification-trigger" data-tnet-shell-disclosure aria-expanded="false" aria-controls="<?php echo esc_attr($notification_id); ?>" aria-haspopup="true" aria-label="<?php echo esc_attr((string) ($notifications['aria_label'] ?? 'Notifications')); ?>"><span class="tnet-shared-shell__notification-icon" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg></span><span class="tnet-shared-shell__utility-label"><?php echo esc_html((string) ($notifications['label'] ?? 'Notifications')); ?></span><?php if (isset($notifications['count'])) : ?><b class="tnet-shared-shell__notification-count"><?php echo esc_html((string) $notifications['count']); ?></b><?php endif; ?></button>
                <div id="<?php echo esc_attr($notification_id); ?>" class="tnet-shared-shell__panel tnet-shared-shell__notification-panel" data-tnet-shell-panel hidden>
                  <?php if (!empty($notifications['heading'])) : ?><div class="tnet-shared-shell__panel-header"><strong><?php echo esc_html((string) $notifications['heading']); ?></strong></div><?php endif; ?>
                  <?php $notification_sections = (array) ($notifications['sections'] ?? []); if (!$notification_sections && !empty($notifications['items'])) $notification_sections = [['items' => $notifications['items']]]; foreach ($notification_sections as $section) : ?>
                    <section class="tnet-shared-shell__notification-section"><?php if (!empty($section['label'])) : ?><p class="tnet-shared-shell__notification-heading"><?php echo esc_html((string) $section['label']); ?></p><?php endif; ?><?php foreach ((array) ($section['items'] ?? []) as $entry) : $entry = is_array($entry) ? $entry : ['title' => $entry]; ?>
                      <div class="tnet-shared-shell__notification-row<?php echo !empty($entry['unread']) ? ' is-unread' : ''; ?>"><div><strong><?php echo esc_html((string) ($entry['title'] ?? '')); ?></strong><?php if (!empty($entry['meta'])) : ?><span><?php echo esc_html((string) $entry['meta']); ?></span><?php endif; ?></div><?php if (!empty($entry['unread'])) : ?><i aria-label="Unread"></i><?php endif; ?></div>
                    <?php endforeach; ?></section>
                  <?php endforeach; ?>
                </div>
              </div><?php endif; ?>
              <?php if ($account) : $account_id = 'tnet-shared-shell-account'; $account_avatar = (string) ($account['avatar_url'] ?? ''); ?><div class="tnet-shared-shell__menu" data-tnet-shell-menu>
                <button type="button" class="tnet-shared-shell__utility tnet-shared-shell__account-trigger" data-tnet-shell-disclosure aria-expanded="false" aria-controls="<?php echo esc_attr($account_id); ?>" aria-haspopup="true" aria-label="<?php echo esc_attr((string) ($account['label'] ?? $account['name'] ?? 'Account')); ?>"><span class="tnet-shared-shell__avatar" aria-hidden="true"><?php if ($account_avatar) : ?><img src="<?php echo esc_url($account_avatar); ?>" alt=""><?php else : ?><?php echo esc_html(mb_strtoupper(mb_substr((string) ($account['name'] ?? $account['label'] ?? 'A'), 0, 1))); ?><?php endif; ?></span><span class="tnet-shared-shell__account-copy"><strong><?php echo esc_html((string) ($account['label'] ?? $account['name'] ?? 'Account')); ?></strong><span><?php echo esc_html((string) ($account['name'] ?? 'Account')); ?></span></span><span class="tnet-shared-shell__caret" aria-hidden="true"></span></button>
                <div id="<?php echo esc_attr($account_id); ?>" class="tnet-shared-shell__panel tnet-shared-shell__account-panel" data-tnet-shell-panel hidden>
                  <?php if (!empty($account['identity'])) : $identity = (array) $account['identity']; $identity_avatar = (string) ($identity['avatar_url'] ?? $account_avatar); ?><div class="tnet-shared-shell__account-identity"><span class="tnet-shared-shell__avatar" aria-hidden="true"><?php if ($identity_avatar) : ?><img src="<?php echo esc_url($identity_avatar); ?>" alt=""><?php else : ?><?php echo esc_html(mb_strtoupper(mb_substr((string) ($identity['name'] ?? $account['name'] ?? 'A'), 0, 1))); ?><?php endif; ?></span><div><strong><?php echo esc_html((string) ($identity['name'] ?? '')); ?></strong><?php if (!empty($identity['email'])) : ?><span><?php echo esc_html((string) $identity['email']); ?></span><?php endif; ?><?php if (!empty($identity['descriptor'])) : ?><span><?php echo esc_html((string) $identity['descriptor']); ?></span><?php endif; ?></div></div><?php endif; ?>
                  <div class="tnet-shared-shell__account-actions"><?php foreach ((array) ($account['items'] ?? []) as $link) : $render_link((array) $link); endforeach; ?></div>
                  <?php if (!empty($account['session'])) : ?><div class="tnet-shared-shell__account-session"><?php $render_link((array) $account['session']); ?></div><?php endif; ?>
                </div>
              </div><?php endif; ?>
            </div>
            </div>
            <button type="button" class="tnet-shared-shell__compact-toggle" data-tnet-shell-compact-toggle aria-expanded="false" aria-controls="tnet-shared-shell-compact-navigation">Menu</button>
          </div>
          <nav id="tnet-shared-shell-compact-navigation" class="tnet-shared-shell__compact-navigation" data-tnet-shell-compact-panel hidden aria-label="Compact navigation">
            <?php $compact_sections = (array) ($compact['sections'] ?? []); if ($compact_sections) : foreach ($compact_sections as $section_index => $section) : $section_id = 'tnet-shared-shell-compact-' . absint($section_index); $items = (array) ($section['items'] ?? []); ?>
              <?php if ($items) : ?><section class="tnet-shared-shell__compact-section"><button type="button" class="tnet-shared-shell__compact-section-trigger" data-tnet-shell-compact-disclosure aria-expanded="<?php echo !empty($section['expanded']) ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr($section_id); ?>"><span><?php echo esc_html((string) ($section['label'] ?? '')); ?></span><span class="tnet-shared-shell__caret" aria-hidden="true"></span></button><div id="<?php echo esc_attr($section_id); ?>" class="tnet-shared-shell__compact-section-panel" data-tnet-shell-compact-section-panel<?php echo !empty($section['expanded']) ? '' : ' hidden'; ?>><?php foreach ($items as $entry_index => $entry) : $render_item((array) $entry, 'compact-' . $section_index . '-' . $entry_index, 'tnet-shared-shell__compact-link'); endforeach; ?></div></section><?php else : $render_link((array) $section, 'tnet-shared-shell__compact-link'); endif; ?>
            <?php endforeach; else : foreach ($compact ?: $navigation as $item) : $render_link((array) $item, 'tnet-shared-shell__compact-link'); endforeach; endif; ?>
          </nav>
        </header>
        <main class="tnet-shared-shell__content<?php echo $content_classes ? ' ' . esc_attr(implode(' ', $content_classes)) : ''; ?>"><?php if (is_callable($content)) call_user_func($content); elseif ($content !== null) echo $content; ?></main>
        <footer class="tnet-shared-shell__footer" data-shell-footer data-tnet-shell-footer>
          <?php if (!empty($footer['brand'])) : ?><a class="tnet-shared-shell__footer-brand" href="<?php echo esc_url((string) ($footer['brand']['url'] ?? '#')); ?>"><?php echo esc_html((string) ($footer['brand']['label'] ?? 'Teachers.Net')); ?></a><?php endif; ?>
          <nav aria-label="<?php echo esc_attr((string) ($footer['navigation_label'] ?? 'Footer')); ?>"><?php foreach ((array) ($footer['links'] ?? []) as $link) : $render_link((array) $link); endforeach; ?></nav>
          <span><?php echo esc_html((string) ($footer['copyright'] ?? '')); ?></span>
        </footer>
      </div>
      <?php wp_footer(); ?>
    </body></html>
    <?php
  }

  /**
   * CONT7 parity host. The exact accepted Lab component tree lives with the
   * shared owner; the consumer supplies only product facts, destinations and
   * its content callback.
   */
  /**
   * Canonical v2 presentation. This is platform-owned markup and behavior;
   * consumers supply only their resolved destinations, identity, notification
   * provider facts, taxonomy data, and product-content callback.
   */
  private static function render_canonical_presentation(array $config) {
    $urls = (array) ($config['urls'] ?? []);
    $identity = (array) ($config['identity'] ?? []);
    $taxonomy = (array) ($config['taxonomy'] ?? []);
    $fixture = sanitize_key((string) ($config['fixture'] ?? 'dashboard'));
    // Consumer identity and workspace ownership are explicit configuration
    // seams. The shell never infers product layout or depends on a consumer.
    $adapter = sanitize_key((string) ($config['adapter'] ?? 'shell-lab'));
    $workspace_owner = sanitize_key((string) ($config['workspace_owner'] ?? 'shell'));
    $workspace_owner = $workspace_owner === 'consumer' ? 'consumer' : 'shell';
    $route_class = sanitize_html_class((string) ($config['route_class'] ?? 'employer-shell-lab'));
    $document_title = (string) ($config['document_title'] ?? 'Teachers.Net');
    $active_destination = sanitize_key((string) ($config['active_destination'] ?? ''));
    $clean = !empty($config['clean']);
    // A canonical application shell is page-aligned and square at its outer
    // boundary. Pinned remains a supported behavior, but floating card chrome
    // is not part of the canonical v2 presentation.
    $presentation = sanitize_key((string) ($config['presentation'] ?? 'flush'));
    $presentation = $presentation === 'pinned' ? 'pinned' : 'flush';
    $fixture_state = sanitize_key((string) ($config['fixture_state'] ?? (!empty($config['anonymous']) ? 'guest' : 'auth-unread')));
    if (!in_array($fixture_state, ['auth-unread', 'auth-zero', 'guest'], true)) {
      $fixture_state = !empty($config['anonymous']) ? 'guest' : 'auth-unread';
    }
    $anonymous_fixture = $fixture_state === 'guest';
    $effective_logged_in = !empty($config['logged_in']);
    $shell_has_employer_access = !empty($config['employer_access']);
    $user_name = (string) ($identity['name'] ?? 'Guest user');
    $user_email = (string) ($identity['email'] ?? '');
    $account_descriptor = (string) ($identity['descriptor'] ?? '');
    $avatar_url = (string) ($identity['avatar_url'] ?? '');
    $avatar_source = sanitize_key((string) ($identity['avatar_source'] ?? 'shell-fallback'));
    $post_job_url = (string) ($urls['post_job'] ?? '#');
    $saved_jobs_nav_url = (string) ($urls['saved_jobs'] ?? '#');
    $job_alerts_nav_url = (string) ($urls['job_alerts'] ?? '#');
    $new_topic_url = (string) ($urls['new_topic'] ?? '#');
    $profile_url = (string) ($urls['profile'] ?? '#');
    $logout_url = (string) ($urls['logout'] ?? '');
    $login_url = (string) ($urls['login'] ?? '#');
    $signup_url = (string) ($urls['signup'] ?? '#');
    $dashboard_url = (string) ($urls['dashboard'] ?? '#');
    $wizard_url = (string) ($urls['wizard'] ?? '#');
    $shell_home_url = (string) ($config['home_url'] ?? '#');
    $shell_footer_links = (array) ($config['footer_links'] ?? []);
    $brand_image = (string) ($config['brand_image'] ?? '');
    $lesson_plan_grade_levels = (array) ($taxonomy['lesson_grade_levels'] ?? []);
    $lesson_plan_subject_areas = (array) ($taxonomy['lesson_subject_areas'] ?? []);
    $chatboard_grade_levels = (array) ($taxonomy['chatboard_grade_levels'] ?? []);
    $notification_fixture_only = !empty($config['notification_fixture_only']);
    $notification_fixture_state = $notification_fixture_only ? ($fixture_state === 'auth-zero' ? 'zero' : 'unread') : '';
    $content_renderer = $config['content'] ?? null;
    // Consumer chrome is an opaque callback. The platform supplies only the
    // placement seam and never imports product classes, routes, or facts.
    $rail_renderer = $config['rail'] ?? null;
    $has_consumer_rail = is_callable($rail_renderer);
    $contract_version = self::CONTRACT_VERSION;
    if (!is_callable($content_renderer)) {
      return;
    }
    require TNET_SHARED_SHELL_PLUGIN_DIR . 'templates/accepted-shell-lab.php';
  }

  private static function render_parity_bell_icon() {
    echo '<svg class="tnet-jobs-shell-lab-bell-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 3.25a5.15 5.15 0 0 0-5.15 5.15v3.09c0 1.42-.48 2.8-1.36 3.91l-.76.96a.83.83 0 0 0 .65 1.35h13.24a.83.83 0 0 0 .65-1.35l-.76-.96a6.2 6.2 0 0 1-1.36-3.91V8.4A5.15 5.15 0 0 0 12 3.25Z"/><path fill="currentColor" d="M9.45 19.25a2.7 2.7 0 0 0 5.1 0H9.45Z"/></svg>';
  }

  private static function render_parity_product_icon($icon) {
    $paths = ['home' => '<path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/>', 'user' => '<circle cx="12" cy="8" r="3.5"/><path d="M4.5 20c.8-3.4 3.3-5.1 7.5-5.1s6.7 1.7 7.5 5.1"/>', 'search' => '<circle cx="10.5" cy="10.5" r="5.7"/><path d="m15 15 4.3 4.3"/>', 'bookmark' => '<path d="M6.5 4.5h11v15l-5.5-3.8-5.5 3.8z"/>', 'alert' => '<path d="M18 10a6 6 0 0 0-12 0c0 3.2-1.2 4.8-2.2 5.9-.5.6-.1 1.4.7 1.4h15c.8 0 1.2-.9.7-1.4C19.2 14.8 18 13.2 18 10Z"/><path d="M9.8 20a2.4 2.4 0 0 0 4.4 0"/>', 'chat' => '<path d="M20.5 10.8a6.3 6.3 0 0 1-6.5 6.1 7.4 7.4 0 0 1-3-.6L7 18l1-3.1a6 6 0 0 1-1-3.6 6.3 6.3 0 0 1 6.5-6.1 6.3 6.3 0 0 1 6 3.5Z"/><path d="M6.7 8.4a5.8 5.8 0 0 0-3.2 5.1 5.5 5.5 0 0 0 1 3.2L3.5 19l3.2-1.2"/>', 'book' => '<path d="M4 5.5c2.7-.7 5.3-.2 8 1.5v12c-2.7-1.7-5.3-2.2-8-1.5zM20 5.5c-2.7-.7-5.3-.2-8 1.5v12c2.7-1.7 5.3-2.2 8-1.5z"/><path d="M12 7v12"/>', 'flame' => '<path d="M13.8 3.6c.5 3.3-1.4 4.4-2.5 5.8-.8-1.2-1-2.2-.7-3.6C7.4 8 5.4 10.7 5.4 14a6.6 6.6 0 0 0 13.2 0c0-3.7-1.7-7-4.8-10.4Z"/>', 'graduate' => '<path d="m3 10 9-5 9 5-9 5-9-5Z"/><path d="M6.6 12v4.2c2.9 2 7.9 2 10.8 0V12M21 10v5"/>', 'pin' => '<path d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0Z"/><circle cx="12" cy="10" r="2.3"/>', 'compose' => '<path d="M4 20h4l10.5-10.5a2.8 2.8 0 0 0-4-4L4 16v4Z"/><path d="m12.8 7.2 4 4"/>', 'plus' => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M12 8v8M8 12h8"/>', 'briefcase' => '<rect x="4" y="7" width="16" height="12" rx="2"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M4 12h16M10 12v1h4v-1"/>', 'school' => '<path d="M4 20V9l8-4 8 4v11M7 20v-8h10v8M10 20v-4h4v4M9 12h.01M12 12h.01M15 12h.01"/>', 'archive' => '<path d="M4 7h16v13H4zM3 4h18v3H3zM9 11h6"/>'];
    $paths['home'] = '<path fill-rule="evenodd" d="M3 10 12 3l9 7v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10Zm7 11h4v-6h-4v6Z"/><path d="M16 6V3.8h2.5v4.1"/>';
    $paths['chat'] = '<path d="M3.5 10.7a6.4 6.4 0 0 1 6.7-6.1 6.4 6.4 0 0 1 6.7 6.1 6.4 6.4 0 0 1-6.7 6.1 7.2 7.2 0 0 1-2.7-.5l-3.5 1.4 1-3a5.9 5.9 0 0 1-1.5-4Z"/><path d="M12.2 8.1a6.5 6.5 0 0 1 5.4-2.7 6.5 6.5 0 0 1 6.2 6.1 6.1 6.1 0 0 1-1.5 4l1 3-3.5-1.4a7.2 7.2 0 0 1-2.7.5"/>';
    $paths['book'] = '<path d="M4 5.5c2.7-.7 5.3-.2 8 1.5v12c-2.7-1.7-5.3-2.2-8-1.5zM20 5.5c-2.7-.7-5.3-.2-8 1.5v12c-2.7-1.7-5.3-2.2-8-1.5z"/><path d="M12 7v12M6.5 9c1.7-.2 3.5.1 5.5 1M17.5 9c-1.7-.2-3.5.1-5.5 1M6.5 12.5c1.7-.2 3.5.1 5.5 1M17.5 12.5c-1.7-.2-3.5.1-5.5 1"/>';
    // Platform navigation has a dedicated visual vocabulary. Generic icons
    // below retain their existing use by menu and product-local surfaces.
    $paths['home'] = '<path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/>';
    $paths['chat'] = '<path d="M20.5 10.8a6.3 6.3 0 0 1-6.5 6.1 7.4 7.4 0 0 1-3-.6L7 18l1-3.1a6 6 0 0 1-1-3.6 6.3 6.3 0 0 1 6.5-6.1 6.3 6.3 0 0 1 6 3.5Z"/><path d="M6.7 8.4a5.8 5.8 0 0 0-3.2 5.1 5.5 5.5 0 0 0 1 3.2L3.5 19l3.2-1.2"/>';
    $paths['book'] = '<path d="M4 5.5c2.7-.7 5.3-.2 8 1.5v12c-2.7-1.7-5.3-2.2-8-1.5zM20 5.5c-2.7-.7-5.3-.2-8 1.5v12c2.7-1.7 5.3-2.2-8-1.5z"/><path d="M12 7v12"/>';
    $paths['platform-home'] = '<path fill="currentColor" d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z"/><path fill="currentColor" d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z"/>';
    $paths['platform-job-center'] = '<g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2.75" y="7.4" width="18.5" height="12.1" rx="1.8"/><path d="M8 7.4V5.9c0-.78.62-1.4 1.4-1.4h5.2c.78 0 1.4.62 1.4 1.4v1.5"/><path d="M2.75 11.7h18.5"/><rect x="10.1" y="10.7" width="3.8" height="2.5" rx=".55" fill="white"/></g>';
    $paths['platform-chatboards'] = '<path fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>';
    $paths['platform-lesson-plans'] = '<path d="M2.75 5.15c3.1-.72 6.35-.08 9.25 2.05v12.15c-2.9-2.13-6.15-2.77-9.25-2.05V5.15Zm18.5 0c-3.1-.72-6.35-.08-9.25 2.05v12.15c2.9-2.13 6.15-2.77 9.25-2.05V5.15Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 7.2v12.15M5.2 8.65c1.7-.12 3.25.27 4.65 1.15M5.2 11.55c1.7-.12 3.25.27 4.65 1.15M18.8 8.65c-1.7-.12-3.25.27-4.65 1.15M18.8 11.55c-1.7-.12-3.25.27-4.65 1.15" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round"/>';
    $icon = array_key_exists($icon, $paths) ? $icon : 'briefcase';
    $view_box = '0 0 24 24';
    echo '<svg data-product-icon="' . esc_attr($icon) . '" viewBox="' . esc_attr($view_box) . '" aria-hidden="true" focusable="false">' . $paths[$icon] . '</svg>';
  }

  private static function asset_version($path) {
    return is_readable($path) ? TNET_SHARED_SHELL_VERSION . '-' . filemtime($path) : TNET_SHARED_SHELL_VERSION;
  }
}
