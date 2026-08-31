    <!doctype html>
    <html <?php language_attributes(); ?>>
    <head>
      <meta charset="<?php bloginfo('charset'); ?>">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo esc_html__('Employer Shell Lab | Teachers.Net Job Center', 'tnet-shared-shell'); ?></title>
      <?php wp_head(); ?>
    </head>
    <body <?php body_class(['tnet-jobs-app-canvas', 'tnet-jobs-shell-lab-body', 'tnet-jobs-route-employer-shell-lab', $anonymous_fixture ? 'tnet-jobs-route-browse' : '', $clean ? 'tnet-jobs-shell-clean-preview' : '']); ?>>
      <?php if (function_exists('wp_body_open')) wp_body_open(); ?>
      <div class="tnet-jobs-shell-lab tnet-shared-shell__canonical" data-tnet-shared-shell="true" data-shell-contract="canonical" data-shell-contract-version="<?php echo esc_attr($contract_version); ?>" data-shell-owner="shared-shell-host" data-shell-adapter="shell-lab" data-shell-clean="<?php echo $clean ? 'true' : 'false'; ?>" data-fixture="<?php echo esc_attr($fixture); ?>" data-shell-presentation="<?php echo esc_attr($presentation); ?>">
        <header class="tnet-jobs-shell-lab-header">
          <div class="tnet-jobs-shell-lab-header-inner">
            <span class="tnet-jobs-shell-lab-rail-divider" aria-hidden="true"></span>
            <a class="tnet-jobs-shell-lab-brand" href="<?php echo esc_url($shell_home_url); ?>" aria-label="<?php echo esc_attr__('Teachers.Net home', 'tnet-shared-shell'); ?>">
              <img src="<?php echo esc_url($brand_image); ?>" alt="<?php echo esc_attr__('Teachers.Net', 'tnet-shared-shell'); ?>">
            </a>
            <div class="tnet-jobs-shell-lab-navbar-right">
              <nav class="tnet-jobs-shell-lab-main-nav" aria-label="<?php echo esc_attr__('Teachers.Net navigation', 'tnet-shared-shell'); ?>">
                <div class="tnet-jobs-shell-lab-nav-menu tnet-jobs-shell-lab-product-menu is-current" data-product-flow="employer" data-employer-access="<?php echo $shell_has_employer_access ? 'true' : 'false'; ?>">
                  <button type="button" class="tnet-jobs-shell-lab-nav-trigger" aria-expanded="false" aria-controls="tnet-jobs-shell-product-navigation" aria-haspopup="true"><?php echo esc_html__('Job Center', 'tnet-shared-shell'); ?><span class="tnet-jobs-shell-lab-nav-chevron" aria-hidden="true"></span></button>
                  <div id="tnet-jobs-shell-product-navigation" class="tnet-jobs-shell-lab-popover tnet-jobs-shell-lab-product-popover">
                    <section class="tnet-jobs-shell-lab-product-section" data-product-flow-panel="employer">
                      <p class="tnet-jobs-shell-lab-product-heading"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('school'); ?></span><?php echo esc_html__('For Employers', 'tnet-shared-shell'); ?></p>
                      <a href="<?php echo esc_url($post_job_url); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('plus'); ?></span><?php echo esc_html__('Post a Job', 'tnet-shared-shell'); ?></a>
                      <?php if ($shell_has_employer_access) : ?>
                        <a class="is-current" href="<?php echo esc_url($urls["my_jobs"]); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('briefcase'); ?></span><?php echo esc_html__('My Jobs', 'tnet-shared-shell'); ?></a>
                        <a href="<?php echo esc_url($urls["schools"]); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('school'); ?></span><?php echo esc_html__('Schools / Jobsites', 'tnet-shared-shell'); ?></a>
                        <a href="<?php echo esc_url($urls["archived"]); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('archive'); ?></span><?php echo esc_html__('Archived Jobs', 'tnet-shared-shell'); ?></a>
                      <?php endif; ?>
                    </section>
                    <section class="tnet-jobs-shell-lab-product-section" data-product-flow-panel="jobseeker">
                      <p class="tnet-jobs-shell-lab-product-heading"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('user'); ?></span><?php echo esc_html__('For Job Seekers', 'tnet-shared-shell'); ?></p>
                      <a href="<?php echo esc_url($urls["browse_jobs"]); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('search'); ?></span><?php echo esc_html__('Browse Jobs', 'tnet-shared-shell'); ?></a>
                      <a href="<?php echo esc_url($saved_jobs_nav_url); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('bookmark'); ?></span><?php echo esc_html__('Saved Jobs', 'tnet-shared-shell'); ?></a>
                      <a href="<?php echo esc_url($job_alerts_nav_url); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('alert'); ?></span><?php echo esc_html__('Job Alerts', 'tnet-shared-shell'); ?></a>
                    </section>
                    <button type="button" class="tnet-jobs-shell-lab-product-switch" data-product-flow-switch="jobseeker"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('user'); ?></span><span><?php echo esc_html__('For Job Seekers', 'tnet-shared-shell'); ?></span></button>
                    <button type="button" class="tnet-jobs-shell-lab-product-switch" data-product-flow-switch="employer"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('school'); ?></span><span><?php echo esc_html__('For Employers', 'tnet-shared-shell'); ?></span></button>
                  </div>
                </div>
                <div class="tnet-jobs-shell-lab-nav-menu tnet-jobs-shell-lab-chatboards-menu">
                  <button type="button" class="tnet-jobs-shell-lab-nav-trigger" aria-expanded="false" aria-controls="tnet-jobs-shell-chatboards-navigation" aria-haspopup="true"><?php echo esc_html__('Chatboards', 'tnet-shared-shell'); ?><span class="tnet-jobs-shell-lab-nav-chevron" aria-hidden="true"></span></button>
                  <div id="tnet-jobs-shell-chatboards-navigation" class="tnet-jobs-shell-lab-popover tnet-jobs-shell-lab-chatboards-popover">
                    <section class="tnet-jobs-shell-lab-chatboards-section">
                      <button type="button" class="tnet-jobs-shell-lab-chatboards-item" data-shell-preview-demo="teacher-chatboard"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('chat'); ?></span><span>Teacher Chatboard</span></button>
                      <button type="button" class="tnet-jobs-shell-lab-chatboards-item" data-shell-preview-demo="latest-posts"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('flame'); ?></span><span>Latest Posts</span></button>
                      <button type="button" class="tnet-jobs-shell-lab-chatboards-item tnet-jobs-shell-lab-lessonplans-taxonomy-parent tnet-jobs-shell-lab-lessonplans-taxonomy-parent--grade" data-lesson-accordion-trigger="chatboard-grade" aria-controls="tnet-jobs-shell-lab-chatboard-grade-taxonomy" aria-expanded="false"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('school'); ?></span><span class="tnet-jobs-shell-lab-lessonplans-parent-label">Grade Level</span></button>
                      <div id="tnet-jobs-shell-lab-chatboard-grade-taxonomy" class="tnet-jobs-shell-lab-lessonplans-taxonomy" data-lesson-accordion-panel="chatboard-grade" hidden><?php foreach ($chatboard_grade_levels as $term_index => $label) : ?><button type="button" data-shell-preview-demo="chatboard-grade-<?php echo esc_attr(sanitize_title($label)); ?>"><?php echo esc_html($label); ?><?php if ($term_index < count($chatboard_grade_levels) - 1) : ?>,<?php endif; ?></button><?php if ($term_index < count($chatboard_grade_levels) - 1) : ?><span class="tnet-jobs-shell-lab-lessonplans-taxonomy-separator" aria-hidden="true"> </span><?php endif; ?><?php endforeach; ?></div>
                      <a href="<?php echo esc_url($urls["chatboards"]); ?>" class="tnet-jobs-shell-lab-chatboards-item tnet-jobs-shell-lab-lessonplans-taxonomy-parent tnet-jobs-shell-lab-lessonplans-taxonomy-parent--subject"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('graduate'); ?></span><span class="tnet-jobs-shell-lab-lessonplans-parent-label">Subject Area Chatboards</span></a>
                      <a href="<?php echo esc_url($urls["chatboards"]); ?>" class="tnet-jobs-shell-lab-chatboards-item"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('pin'); ?></span><span>State Chatboards</span></a>
                    </section>
                    <a href="<?php echo esc_url($new_topic_url); ?>" class="tnet-jobs-shell-lab-chatboards-item tnet-jobs-shell-lab-chatboards-browse"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('compose'); ?></span><span>Start a Topic</span></a>
                  </div>
                </div>
                <div class="tnet-jobs-shell-lab-nav-menu tnet-jobs-shell-lab-lessonplans-menu">
                  <button type="button" class="tnet-jobs-shell-lab-nav-trigger" aria-expanded="false" aria-controls="tnet-jobs-shell-lessonplans-navigation" aria-haspopup="true"><?php echo esc_html__('Lesson Plans', 'tnet-shared-shell'); ?><span class="tnet-jobs-shell-lab-nav-chevron" aria-hidden="true"></span></button>
                  <div id="tnet-jobs-shell-lessonplans-navigation" class="tnet-jobs-shell-lab-popover tnet-jobs-shell-lab-lessonplans-popover">
                    <section class="tnet-jobs-shell-lab-lessonplans-section">
                      <button type="button" class="tnet-jobs-shell-lab-lessonplans-item" data-shell-preview-demo="browse-lessons"><span class="tnet-jobs-shell-lab-chatboards-icon"><?php self::render_parity_product_icon('search'); ?></span><span>Browse Lesson Plans</span></button>
                    </section>
                    <section class="tnet-jobs-shell-lab-lessonplans-section">
                      <button type="button" class="tnet-jobs-shell-lab-lessonplans-item tnet-jobs-shell-lab-lessonplans-taxonomy-parent tnet-jobs-shell-lab-lessonplans-taxonomy-parent--grade" data-lesson-accordion-trigger="grade" aria-controls="tnet-jobs-shell-lab-grade-taxonomy" aria-expanded="false"><span class="tnet-jobs-shell-lab-chatboards-icon"><?php self::render_parity_product_icon('school'); ?></span><span class="tnet-jobs-shell-lab-lessonplans-parent-label">Grade Level</span></button>
                      <div id="tnet-jobs-shell-lab-grade-taxonomy" class="tnet-jobs-shell-lab-lessonplans-taxonomy" data-lesson-accordion-panel="grade" hidden><?php foreach ($lesson_plan_grade_levels as $term_index => $label) : ?><button type="button" data-shell-preview-demo="grade-<?php echo esc_attr(sanitize_title($label)); ?>"><?php echo esc_html($label); ?><?php if ($term_index < count($lesson_plan_grade_levels) - 1) : ?>,<?php endif; ?></button><?php if ($term_index < count($lesson_plan_grade_levels) - 1) : ?><span class="tnet-jobs-shell-lab-lessonplans-taxonomy-separator" aria-hidden="true"> </span><?php endif; ?><?php endforeach; ?></div>
                      <button type="button" class="tnet-jobs-shell-lab-lessonplans-item tnet-jobs-shell-lab-lessonplans-taxonomy-parent tnet-jobs-shell-lab-lessonplans-taxonomy-parent--subject" data-lesson-accordion-trigger="subject" aria-controls="tnet-jobs-shell-lab-subject-taxonomy" aria-expanded="false"><span class="tnet-jobs-shell-lab-chatboards-icon"><?php self::render_parity_product_icon('graduate'); ?></span><span class="tnet-jobs-shell-lab-lessonplans-parent-label">Subject Area</span></button>
                      <div id="tnet-jobs-shell-lab-subject-taxonomy" class="tnet-jobs-shell-lab-lessonplans-taxonomy" data-lesson-accordion-panel="subject" hidden><?php foreach ($lesson_plan_subject_areas as $term_index => $label) : ?><button type="button" data-shell-preview-demo="subject-<?php echo esc_attr(sanitize_title($label)); ?>"><?php echo esc_html($label); ?><?php if ($term_index < count($lesson_plan_subject_areas) - 1) : ?>,<?php endif; ?></button><?php if ($term_index < count($lesson_plan_subject_areas) - 1) : ?><span class="tnet-jobs-shell-lab-lessonplans-taxonomy-separator" aria-hidden="true"> </span><?php endif; ?><?php endforeach; ?></div>
                    </section>
                  </div>
                </div>
              </nav>
              <div class="tnet-jobs-shell-lab-account-area<?php echo $anonymous_fixture ? ' tnet-jobs-shell-lab-anonymous-account-area' : ''; ?>">
                <?php if ($anonymous_fixture) : ?>
                  <a class="tnet-jobs-shell-lab-anonymous-login" href="<?php echo esc_url($login_url); ?>"><?php echo esc_html__('Log In', 'tnet-shared-shell'); ?></a>
                  <a class="tnet-jobs-shell-lab-anonymous-signup" href="<?php echo esc_url($signup_url); ?>"><?php echo esc_html__('Sign Up', 'tnet-shared-shell'); ?></a>
                <?php elseif ($effective_logged_in) : ?>
                <div class="tnet-jobs-shell-lab-notification-menu" data-notification-center data-notification-provider="<?php echo $clean ? 'fixture' : 'runtime'; ?>" data-notification-fixture-only="<?php echo $clean ? 'true' : 'false'; ?>">
                  <button id="tnet-jobs-shell-notification-toggle" type="button" class="tnet-jobs-shell-lab-bell tnet-jobs-shell-lab-notification-toggle" aria-expanded="false" aria-controls="tnet-jobs-shell-notification-center" aria-haspopup="dialog" aria-label="<?php echo esc_attr__('Notifications, loading synthetic fixture data', 'tnet-shared-shell'); ?>">
                    <?php self::render_parity_bell_icon(); ?>
                    <span data-notification-unread-count aria-hidden="true">0</span>
                  </button>
                  <section id="tnet-jobs-shell-notification-center" class="tnet-jobs-shell-lab-popover tnet-jobs-shell-lab-notification-popover" role="dialog" aria-modal="false" aria-labelledby="tnet-jobs-shell-notification-title" data-notification-panel>
                    <div class="tnet-jobs-shell-lab-notification-panel" data-notification-fixture-only="<?php echo $clean ? 'true' : 'false'; ?>">
                      <p class="tnet-jobs-shell-lab-notification-loading"><?php echo esc_html__($clean ? 'Loading synthetic notification fixture…' : 'Loading notifications…', 'tnet-shared-shell'); ?></p>
                    </div>
                  </section>
                </div>
                <?php endif; ?>
                <?php if (!$anonymous_fixture) : ?><div class="tnet-jobs-shell-lab-account-menu">
                  <button type="button" class="tnet-jobs-shell-lab-disclosure-toggle" aria-expanded="false" aria-controls="tnet-jobs-shell-account-navigation" aria-haspopup="true" data-avatar-source="<?php echo esc_attr($avatar_source); ?>" aria-label="<?php echo esc_attr(sprintf(__('Account menu for %s', 'tnet-shared-shell'), $user_name)); ?>">
                    <?php if ($avatar_url) : ?><img src="<?php echo esc_url($avatar_url); ?>" alt=""><?php else : ?><span class="tnet-jobs-shell-lab-avatar-fallback" aria-hidden="true"><?php echo esc_html(strtoupper(substr($user_name, 0, 1))); ?></span><?php endif; ?>
                  </button>
                  <div id="tnet-jobs-shell-account-navigation" class="tnet-jobs-shell-lab-popover">
                    <div class="tnet-jobs-shell-lab-account-popover">
                      <div class="tnet-jobs-shell-lab-account-identity">
                        <?php if ($avatar_url) : ?><img src="<?php echo esc_url($avatar_url); ?>" alt=""><?php else : ?><span class="tnet-jobs-shell-lab-avatar-fallback" aria-hidden="true"><?php echo esc_html(strtoupper(substr($user_name, 0, 1))); ?></span><?php endif; ?>
                        <div><strong><?php echo esc_html($user_name); ?></strong><?php if ($user_email) : ?><span><?php echo esc_html($user_email); ?></span><?php endif; ?><?php if ($account_descriptor) : ?><span><?php echo esc_html($account_descriptor); ?></span><?php endif; ?></div>
                      </div>
                      <div class="tnet-jobs-shell-lab-account-actions">
                        <a href="<?php echo esc_url($profile_url); ?>"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('user'); ?></span><span><?php echo esc_html__('View Profile', 'tnet-shared-shell'); ?></span></a>
                        <button type="button" data-shell-preview-demo="account-settings"><span aria-hidden="true">⚙</span><span><?php echo esc_html__('Account Settings', 'tnet-shared-shell'); ?></span></button>
                        <button type="button" data-shell-preview-demo="notification-preferences"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('alert'); ?></span><span><?php echo esc_html__('Notification Preferences', 'tnet-shared-shell'); ?></span></button>
                      </div>
                      <?php if ($logout_url) : ?><div class="tnet-jobs-shell-lab-account-session"><a href="<?php echo esc_url($logout_url); ?>"><span aria-hidden="true">↪</span><span><?php echo esc_html__('Sign Out', 'tnet-shared-shell'); ?></span></a></div><?php endif; ?>
                    </div>
                  </div>
                </div><?php endif; ?>
              </div>
              <div class="tnet-jobs-shell-lab-mobile-menu">
                <button type="button" class="tnet-jobs-shell-lab-disclosure-toggle" aria-expanded="false" aria-controls="tnet-jobs-shell-compact-navigation" aria-haspopup="true"><span class="screen-reader-text"><?php echo esc_html__('Open navigation', 'tnet-shared-shell'); ?></span><svg class="tnet-jobs-shell-lab-mobile-menu-icon" viewBox="0 0 38 38" aria-hidden="true" focusable="false"><path d="M4 9h30M4 19h30M4 29h30" /></svg></button>
                <div id="tnet-jobs-shell-compact-navigation" class="tnet-jobs-shell-lab-popover tnet-jobs-shell-lab-compact-navigation">
                  <section class="tnet-jobs-shell-lab-compact-panel is-active" data-compact-panel="root" data-compact-flow="<?php echo $shell_has_employer_access ? 'employer' : 'jobseeker'; ?>">
                    <?php if ($anonymous_fixture) : ?><div class="tnet-jobs-shell-lab-compact-auth"><a href="<?php echo esc_url($login_url); ?>"><?php echo esc_html__('Log In', 'tnet-shared-shell'); ?></a><a href="<?php echo esc_url($signup_url); ?>"><?php echo esc_html__('Sign Up', 'tnet-shared-shell'); ?></a></div><?php endif; ?>
                    <div class="tnet-jobs-shell-lab-compact-resource" data-compact-resource="employer">
                      <button type="button" data-compact-accordion-toggle="employer" aria-expanded="<?php echo $shell_has_employer_access ? 'true' : 'false'; ?>" aria-controls="tnet-jobs-shell-compact-employer" class="<?php echo $shell_has_employer_access ? '' : 'is-muted'; ?>"><span class="tnet-jobs-shell-lab-compact-caret" aria-hidden="true"></span><span><?php echo esc_html__('For Employers', 'tnet-shared-shell'); ?></span></button>
                      <div id="tnet-jobs-shell-compact-employer" data-compact-accordion-panel="employer" <?php echo $shell_has_employer_access ? '' : 'hidden'; ?>>
                        <a href="<?php echo esc_url($post_job_url); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('plus'); ?></span><span><?php echo esc_html__('Post a Job', 'tnet-shared-shell'); ?></span></a>
                        <?php if ($shell_has_employer_access) : ?><a href="<?php echo esc_url($urls["my_jobs"]); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('briefcase'); ?></span><span><?php echo esc_html__('My Jobs', 'tnet-shared-shell'); ?></span></a><a href="<?php echo esc_url($urls["schools"]); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('school'); ?></span><span><?php echo esc_html__('Schools / Jobsites', 'tnet-shared-shell'); ?></span></a><a href="<?php echo esc_url($urls["archived"]); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('archive'); ?></span><span><?php echo esc_html__('Archived Jobs', 'tnet-shared-shell'); ?></span></a><?php endif; ?>
                      </div>
                    </div>
                    <div class="tnet-jobs-shell-lab-compact-resource" data-compact-resource="jobseeker">
                      <button type="button" data-compact-accordion-toggle="jobseeker" aria-expanded="<?php echo $shell_has_employer_access ? 'false' : 'true'; ?>" aria-controls="tnet-jobs-shell-compact-jobseeker"><span class="tnet-jobs-shell-lab-compact-caret" aria-hidden="true"></span><span><?php echo esc_html__('For Job Seekers', 'tnet-shared-shell'); ?></span></button>
                      <div id="tnet-jobs-shell-compact-jobseeker" data-compact-accordion-panel="jobseeker" <?php echo $shell_has_employer_access ? 'hidden' : ''; ?>><a href="<?php echo esc_url($urls["browse_jobs"]); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('search'); ?></span><span><?php echo esc_html__('Browse Jobs', 'tnet-shared-shell'); ?></span></a><a href="<?php echo esc_url($saved_jobs_nav_url); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('bookmark'); ?></span><span><?php echo esc_html__('Saved Jobs', 'tnet-shared-shell'); ?></span></a><a href="<?php echo esc_url($job_alerts_nav_url); ?>"><span class="tnet-jobs-shell-lab-product-icon" aria-hidden="true"><?php self::render_parity_product_icon('alert'); ?></span><span><?php echo esc_html__('Job Alerts', 'tnet-shared-shell'); ?></span></a></div>
                    </div>
                      <button type="button" data-compact-accordion-toggle="chatboards" aria-expanded="false"><span class="tnet-jobs-shell-lab-compact-caret" aria-hidden="true"></span><span><?php echo esc_html__('Chatboards', 'tnet-shared-shell'); ?></span></button>
                    <button type="button" data-compact-accordion-toggle="lesson-plans" aria-expanded="false"><span class="tnet-jobs-shell-lab-compact-caret" aria-hidden="true"></span><span><?php echo esc_html__('Lesson Plans', 'tnet-shared-shell'); ?></span></button>
                  </section>
                  <section class="tnet-jobs-shell-lab-compact-panel" data-compact-panel="chatboards" hidden>
                    <button type="button" class="tnet-jobs-shell-lab-chatboards-item" data-shell-preview-demo="compact-teacher-chatboard"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('chat'); ?></span><span>Teacher Chatboard</span></button>
                    <button type="button" class="tnet-jobs-shell-lab-chatboards-item" data-shell-preview-demo="compact-latest-posts"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('flame'); ?></span><span>Latest Posts</span></button>
                    <button type="button" class="tnet-jobs-shell-lab-chatboards-item tnet-jobs-shell-lab-compact-taxonomy-trigger tnet-jobs-shell-lab-compact-taxonomy-trigger--grade" data-compact-taxonomy-toggle="chatboard-grade" aria-controls="tnet-jobs-shell-lab-compact-chatboard-grade" aria-expanded="false"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('school'); ?></span><span>Grade Level</span></button>
                    <div id="tnet-jobs-shell-lab-compact-chatboard-grade" class="tnet-jobs-shell-lab-compact-taxonomy" data-compact-taxonomy-panel="chatboard-grade" hidden><?php foreach ($chatboard_grade_levels as $term_index => $label) : ?><button type="button" data-shell-preview-demo="compact-chatboard-grade-<?php echo esc_attr(sanitize_title($label)); ?>"><?php echo esc_html($label); ?><?php if ($term_index < count($chatboard_grade_levels) - 1) : ?>,<?php endif; ?></button><?php if ($term_index < count($chatboard_grade_levels) - 1) : ?><span aria-hidden="true"> </span><?php endif; ?><?php endforeach; ?></div>
                    <a href="<?php echo esc_url($urls["chatboards"]); ?>" class="tnet-jobs-shell-lab-chatboards-item tnet-jobs-shell-lab-compact-link tnet-jobs-shell-lab-compact-link--subject"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('graduate'); ?></span><span>Subject Areas</span></a>
                    <a href="<?php echo esc_url($urls["chatboards"]); ?>" class="tnet-jobs-shell-lab-chatboards-item tnet-jobs-shell-lab-compact-link"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('pin'); ?></span><span>State Chatboards</span></a>
                    <a href="<?php echo esc_url($new_topic_url); ?>" class="tnet-jobs-shell-lab-chatboards-item tnet-jobs-shell-lab-compact-link tnet-jobs-shell-lab-compact-start-topic"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('compose'); ?></span><span>Start a Topic</span></a>
                  </section>
                  <section class="tnet-jobs-shell-lab-compact-panel" data-compact-panel="lesson-plans" hidden>
                    <button type="button" class="tnet-jobs-shell-lab-chatboards-item" data-shell-preview-demo="compact-browse-lessons"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('search'); ?></span><span>Browse Lesson Plans</span></button>
                    <button type="button" class="tnet-jobs-shell-lab-chatboards-item tnet-jobs-shell-lab-compact-taxonomy-trigger tnet-jobs-shell-lab-compact-taxonomy-trigger--grade tnet-jobs-shell-lab-lessonplans-taxonomy-parent--grade" data-compact-taxonomy-toggle="lesson-grade" aria-controls="tnet-jobs-shell-lab-compact-lesson-grade" aria-expanded="false"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('school'); ?></span><span>Grade Level</span></button>
                    <div id="tnet-jobs-shell-lab-compact-lesson-grade" class="tnet-jobs-shell-lab-compact-taxonomy" data-compact-taxonomy-panel="lesson-grade" hidden><?php foreach ($lesson_plan_grade_levels as $term_index => $label) : ?><button type="button" data-shell-preview-demo="compact-grade-<?php echo esc_attr(sanitize_title($label)); ?>"><?php echo esc_html($label); ?><?php if ($term_index < count($lesson_plan_grade_levels) - 1) : ?>,<?php endif; ?></button><?php if ($term_index < count($lesson_plan_grade_levels) - 1) : ?><span aria-hidden="true"> </span><?php endif; ?><?php endforeach; ?></div>
                    <button type="button" class="tnet-jobs-shell-lab-chatboards-item tnet-jobs-shell-lab-compact-taxonomy-trigger tnet-jobs-shell-lab-compact-taxonomy-trigger--subject tnet-jobs-shell-lab-lessonplans-taxonomy-parent--subject" data-compact-taxonomy-toggle="lesson-subject" aria-controls="tnet-jobs-shell-lab-compact-lesson-subject" aria-expanded="false"><span class="tnet-jobs-shell-lab-chatboards-icon" aria-hidden="true"><?php self::render_parity_product_icon('graduate'); ?></span><span>Subject Area</span></button>
                    <div id="tnet-jobs-shell-lab-compact-lesson-subject" class="tnet-jobs-shell-lab-compact-taxonomy" data-compact-taxonomy-panel="lesson-subject" hidden><?php foreach ($lesson_plan_subject_areas as $term_index => $label) : ?><button type="button" data-shell-preview-demo="compact-subject-<?php echo esc_attr(sanitize_title($label)); ?>"><?php echo esc_html($label); ?><?php if ($term_index < count($lesson_plan_subject_areas) - 1) : ?>,<?php endif; ?><?php endforeach; ?></div>
                  </section>
                  <?php if (!$clean) : ?><span class="tnet-jobs-shell-lab-mobile-fixture-label"><?php echo esc_html__('Shell Lab fixture', 'tnet-shared-shell'); ?></span>
                  <nav class="tnet-jobs-shell-lab-fixture-switcher" aria-label="<?php echo esc_attr__('Shell Lab fixture mode', 'tnet-shared-shell'); ?>">
                    <a class="<?php echo $fixture === 'dashboard' ? 'is-current' : ''; ?>" href="<?php echo esc_url($dashboard_url); ?>"><?php echo esc_html__('Dashboard / List', 'tnet-shared-shell'); ?></a>
                    <a class="<?php echo $fixture === 'wizard' ? 'is-current' : ''; ?>" href="<?php echo esc_url($wizard_url); ?>"><?php echo esc_html__('Wizard / Form', 'tnet-shared-shell'); ?></a>
                  </nav><?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </header>
        <div class="tnet-jobs-shell-lab-body-frame<?php echo $anonymous_fixture ? ' tnet-jobs-shell-lab-anonymous-state' : ''; ?>">
          <?php if (!$anonymous_fixture && $shell_has_employer_access) : ?>
          <aside class="tnet-jobs-shell-lab-rail" aria-label="<?php echo esc_attr__('Employer navigation', 'tnet-shared-shell'); ?>">
            <nav>
              <a class="is-active" aria-current="page" href="<?php echo esc_url($urls["my_jobs"]); ?>"><span class="tnet-jobs-shell-lab-nav-main"><span class="tnet-jobs-shell-lab-nav-icon" aria-hidden="true"><?php self::render_parity_product_icon('briefcase'); ?></span><strong><?php echo esc_html__('My Jobs', 'tnet-shared-shell'); ?></strong><b aria-label="90 jobs">90</b></span></a>
              <a href="<?php echo esc_url($urls["post_job"]); ?>"><span class="tnet-jobs-shell-lab-nav-main"><span class="tnet-jobs-shell-lab-nav-icon" aria-hidden="true"><?php self::render_parity_product_icon('plus'); ?></span><strong><?php echo esc_html__('Post a Job', 'tnet-shared-shell'); ?></strong></span></a>
              <a href="<?php echo esc_url($urls["schools"]); ?>"><span class="tnet-jobs-shell-lab-nav-main"><span class="tnet-jobs-shell-lab-nav-icon" aria-hidden="true"><?php self::render_parity_product_icon('school'); ?></span><strong><?php echo esc_html__('Schools / Jobsites', 'tnet-shared-shell'); ?></strong><b aria-label="3 schools or jobsites">3</b></span></a>
              <a href="<?php echo esc_url($urls["archived"]); ?>"><span class="tnet-jobs-shell-lab-nav-main"><span class="tnet-jobs-shell-lab-nav-icon" aria-hidden="true"><?php self::render_parity_product_icon('archive'); ?></span><strong><?php echo esc_html__('Archived Jobs', 'tnet-shared-shell'); ?></strong></span></a>
            </nav>
            <div class="tnet-jobs-shell-lab-rail-divider-rule" aria-hidden="true"></div>
            <section class="tnet-jobs-shell-lab-help-card">
              <p><?php echo esc_html__('First time posting?', 'tnet-shared-shell'); ?></p>
              <div><?php echo esc_html__('Our quick wizard helps you create a job listing in just a few minutes', 'tnet-shared-shell'); ?></div>
              <a href="<?php echo esc_url($urls["post_job"]); ?>"><?php echo esc_html__('View Posting Tips', 'tnet-shared-shell'); ?></a>
            </section>
            <?php if (!$clean) : ?><section class="tnet-jobs-shell-lab-instrumentation" aria-label="<?php echo esc_attr__('Shell Lab controls', 'tnet-shared-shell'); ?>">
              <div class="tnet-jobs-shell-lab-instrumentation-heading"><span><?php echo esc_html__('Shell Lab', 'tnet-shared-shell'); ?></span><small><?php echo esc_html__('Local only', 'tnet-shared-shell'); ?></small></div>
              <nav class="tnet-jobs-shell-lab-fixture-switcher" aria-label="<?php echo esc_attr__('Shell Lab fixture mode', 'tnet-shared-shell'); ?>">
                <a class="<?php echo $fixture === 'dashboard' ? 'is-current' : ''; ?>" href="<?php echo esc_url($dashboard_url); ?>"><?php echo esc_html__('Dashboard / List', 'tnet-shared-shell'); ?></a>
                <a class="<?php echo $fixture === 'wizard' ? 'is-current' : ''; ?>" href="<?php echo esc_url($wizard_url); ?>"><?php echo esc_html__('Wizard / Form', 'tnet-shared-shell'); ?></a>
              </nav>
              <fieldset class="tnet-jobs-shell-lab-presentation-controls"><legend><?php echo esc_html__('Presentation', 'tnet-shared-shell'); ?></legend>
                <?php foreach (['floating' => __('Floating', 'tnet-shared-shell'), 'flush' => __('Flush', 'tnet-shared-shell'), 'pinned' => __('Pinned', 'tnet-shared-shell')] as $value => $label) : ?>
                  <button type="button" data-shell-presentation-control="<?php echo esc_attr($value); ?>" aria-pressed="<?php echo $presentation === $value ? 'true' : 'false'; ?>"><?php echo esc_html($label); ?></button>
                <?php endforeach; ?>
              </fieldset>
              <fieldset class="tnet-jobs-shell-lab-structural-controls"><legend><?php echo esc_html__('Structure', 'tnet-shared-shell'); ?></legend>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-rail-width"><?php echo esc_html__('Left rail', 'tnet-shared-shell'); ?></label><input id="shell-lab-rail-width" type="range" data-shell-setting="railWidth" min="180" max="320" step="1" value="250"><output data-shell-output="railWidth">250px</output></div>
                <div class="tnet-jobs-shell-lab-presets" aria-label="<?php echo esc_attr__('Left rail presets', 'tnet-shared-shell'); ?>"><?php foreach ([190, 220, 250, 280, 300] as $preset) : ?><button type="button" data-shell-rail-preset="<?php echo esc_attr($preset); ?>"><?php echo esc_html($preset); ?></button><?php endforeach; ?></div>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-logo-width"><?php echo esc_html__('Logo width', 'tnet-shared-shell'); ?></label><input id="shell-lab-logo-width" type="range" data-shell-setting="logoWidth" min="80" max="220" step="1" value="190"><output data-shell-output="logoWidth">190px</output></div>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-nav-height"><?php echo esc_html__('Navbar height', 'tnet-shared-shell'); ?></label><input id="shell-lab-nav-height" type="range" data-shell-setting="navHeight" min="54" max="100" step="1" value="80"><output data-shell-output="navHeight">80px</output></div>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-nav-link-font"><?php echo esc_html__('Nav link size', 'tnet-shared-shell'); ?></label><input id="shell-lab-nav-link-font" type="range" data-shell-setting="navLinkFont" min="13" max="20" step="1" value="16"><output data-shell-output="navLinkFont">16px</output></div>
              </fieldset>
              <details class="tnet-jobs-shell-lab-tuning-controls">
                <summary><?php echo esc_html__('Visual settings', 'tnet-shared-shell'); ?></summary>
                <p class="tnet-jobs-shell-lab-control-note"><?php echo esc_html__('Canvas is outside the application. Page/Frame is the application surface.', 'tnet-shared-shell'); ?></p>
                <div class="tnet-jobs-shell-lab-color-control"><label for="shell-lab-canvas-color"><?php echo esc_html__('Canvas', 'tnet-shared-shell'); ?></label><input id="shell-lab-canvas-color" type="color" data-shell-setting="canvas" value="#f4f5f7"><input type="text" data-shell-setting-hex="canvas" value="#f4f5f7" maxlength="7" spellcheck="false"></div>
                <div class="tnet-jobs-shell-lab-color-control"><label for="shell-lab-surface-color"><?php echo esc_html__('Page / Frame', 'tnet-shared-shell'); ?></label><input id="shell-lab-surface-color" type="color" data-shell-setting="surface" value="#ffffff"><input type="text" data-shell-setting-hex="surface" value="#ffffff" maxlength="7" spellcheck="false"></div>
                <div class="tnet-jobs-shell-lab-color-control"><label for="shell-lab-page-edge-color"><?php echo esc_html__('Page Edge', 'tnet-shared-shell'); ?></label><input id="shell-lab-page-edge-color" type="color" data-shell-setting="pageEdge" value="#cbd5e1"><input type="text" data-shell-setting-hex="pageEdge" value="#cbd5e1" maxlength="7" spellcheck="false"></div>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-page-edge-width"><?php echo esc_html__('Edge width', 'tnet-shared-shell'); ?></label><input id="shell-lab-page-edge-width" type="range" data-shell-setting="pageEdgeWidth" min="0" max="4" step="1" value="0"><output data-shell-output="pageEdgeWidth">0px</output></div>
                <div class="tnet-jobs-shell-lab-color-control"><label for="shell-lab-nav-start-color"><?php echo esc_html__('Nav start', 'tnet-shared-shell'); ?></label><input id="shell-lab-nav-start-color" type="color" data-shell-setting="navStart" value="#ffffff"><input type="text" data-shell-setting-hex="navStart" value="#ffffff" maxlength="7" spellcheck="false"></div>
                <div class="tnet-jobs-shell-lab-color-control"><label for="shell-lab-nav-end-color"><?php echo esc_html__('Nav end', 'tnet-shared-shell'); ?></label><input id="shell-lab-nav-end-color" type="color" data-shell-setting="navEnd" value="#ffffff"><input type="text" data-shell-setting-hex="navEnd" value="#ffffff" maxlength="7" spellcheck="false"></div>
                <div class="tnet-jobs-shell-lab-color-control"><label for="shell-lab-nav-text-color"><?php echo esc_html__('Nav text', 'tnet-shared-shell'); ?></label><input id="shell-lab-nav-text-color" type="color" data-shell-setting="navText" value="#102a75"><input type="text" data-shell-setting-hex="navText" value="#102a75" maxlength="7" spellcheck="false"></div>
                <label class="tnet-jobs-shell-lab-toggle-control"><input type="checkbox" data-shell-setting="gradientEnabled"> <?php echo esc_html__('Navbar gradient', 'tnet-shared-shell'); ?></label>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-radius"><?php echo esc_html__('Outer radius', 'tnet-shared-shell'); ?></label><input id="shell-lab-radius" type="range" data-shell-setting="radius" min="0" max="32" value="18"><output data-shell-output="radius">18px</output></div>
                <p class="tnet-jobs-shell-lab-control-group-label"><?php echo esc_html__('Application shadow', 'tnet-shared-shell'); ?></p>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-shadow-x"><?php echo esc_html__('X', 'tnet-shared-shell'); ?></label><input id="shell-lab-shadow-x" type="range" data-shell-setting="shadowX" min="-24" max="24" value="0"><output data-shell-output="shadowX">0px</output></div>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-shadow-y"><?php echo esc_html__('Y', 'tnet-shared-shell'); ?></label><input id="shell-lab-shadow-y" type="range" data-shell-setting="shadowY" min="0" max="32" value="14"><output data-shell-output="shadowY">14px</output></div>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-shadow-blur"><?php echo esc_html__('Blur', 'tnet-shared-shell'); ?></label><input id="shell-lab-shadow-blur" type="range" data-shell-setting="shadowBlur" min="0" max="64" value="42"><output data-shell-output="shadowBlur">42px</output></div>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-shadow-spread"><?php echo esc_html__('Spread', 'tnet-shared-shell'); ?></label><input id="shell-lab-shadow-spread" type="range" data-shell-setting="shadowSpread" min="-16" max="24" value="0"><output data-shell-output="shadowSpread">0px</output></div>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-shadow-opacity"><?php echo esc_html__('Opacity', 'tnet-shared-shell'); ?></label><input id="shell-lab-shadow-opacity" type="range" data-shell-setting="shadowOpacity" min="0" max="50" value="10"><output data-shell-output="shadowOpacity">.10</output></div>
                <p class="tnet-jobs-shell-lab-control-group-label"><?php echo esc_html__('Navbar shadow', 'tnet-shared-shell'); ?></p>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-nav-shadow-x"><?php echo esc_html__('X', 'tnet-shared-shell'); ?></label><input id="shell-lab-nav-shadow-x" type="range" data-shell-setting="navShadowX" min="-24" max="24" value="0"><output data-shell-output="navShadowX">0px</output></div>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-nav-shadow-y"><?php echo esc_html__('Y', 'tnet-shared-shell'); ?></label><input id="shell-lab-nav-shadow-y" type="range" data-shell-setting="navShadowY" min="-8" max="32" value="0"><output data-shell-output="navShadowY">0px</output></div>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-nav-shadow-blur"><?php echo esc_html__('Blur', 'tnet-shared-shell'); ?></label><input id="shell-lab-nav-shadow-blur" type="range" data-shell-setting="navShadowBlur" min="0" max="64" value="0"><output data-shell-output="navShadowBlur">0px</output></div>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-nav-shadow-spread"><?php echo esc_html__('Spread', 'tnet-shared-shell'); ?></label><input id="shell-lab-nav-shadow-spread" type="range" data-shell-setting="navShadowSpread" min="-16" max="24" value="0"><output data-shell-output="navShadowSpread">0px</output></div>
                <div class="tnet-jobs-shell-lab-range-control"><label for="shell-lab-nav-shadow-opacity"><?php echo esc_html__('Opacity', 'tnet-shared-shell'); ?></label><input id="shell-lab-nav-shadow-opacity" type="range" data-shell-setting="navShadowOpacity" min="0" max="50" value="0"><output data-shell-output="navShadowOpacity">.00</output></div>
              </details>
              <div class="tnet-jobs-shell-lab-candidates">
                <label for="shell-lab-candidate-name"><?php echo esc_html__('Candidate', 'tnet-shared-shell'); ?></label><input id="shell-lab-candidate-name" type="text" placeholder="<?php echo esc_attr__('Candidate A', 'tnet-shared-shell'); ?>" maxlength="40">
                <div><button type="button" data-shell-candidate-save><?php echo esc_html__('Save / update', 'tnet-shared-shell'); ?></button><button type="button" data-shell-candidate-reset><?php echo esc_html__('Reset', 'tnet-shared-shell'); ?></button></div>
                <select data-shell-candidate-select aria-label="<?php echo esc_attr__('Saved shell candidates', 'tnet-shared-shell'); ?>"><option value=""><?php echo esc_html__('Saved candidates', 'tnet-shared-shell'); ?></option></select>
                <div><button type="button" data-shell-candidate-load><?php echo esc_html__('Load', 'tnet-shared-shell'); ?></button><button type="button" data-shell-candidate-delete><?php echo esc_html__('Delete', 'tnet-shared-shell'); ?></button><button type="button" data-shell-settings-copy><?php echo esc_html__('Copy settings', 'tnet-shared-shell'); ?></button></div>
                <label class="tnet-jobs-shell-lab-toggle-control"><input type="checkbox" data-shell-setting="stress"> <?php echo esc_html__('Scroll stress', 'tnet-shared-shell'); ?></label>
                <p class="tnet-jobs-shell-lab-instrumentation-status" data-shell-lab-status aria-live="polite"></p>
              </div>
            </section><?php endif; ?>
          </aside>
          <?php endif; ?>
          <main class="tnet-jobs-shell-lab-workspace" id="tnet-jobs-shell-lab-workspace">
            <?php if (!$anonymous_fixture && $shell_has_employer_access) : ?>
            <div class="tnet-jobs-shell-lab-employer-menu<?php echo $anonymous_fixture ? ' tnet-jobs-shell-lab-anonymous-hidden' : ''; ?>">
              <button type="button" class="tnet-jobs-shell-lab-disclosure-toggle" aria-expanded="false" aria-controls="tnet-jobs-shell-employer-workspace-navigation" aria-haspopup="true"><span><?php echo esc_html__('Employer workspace', 'tnet-shared-shell'); ?></span><span aria-hidden="true">My Jobs</span><span class="tnet-jobs-shell-lab-chevron" aria-hidden="true"></span></button>
              <div id="tnet-jobs-shell-employer-workspace-navigation" class="tnet-jobs-shell-lab-popover">
                <a href="<?php echo esc_url($urls["my_jobs"]); ?>"><?php echo esc_html__('My Jobs', 'tnet-shared-shell'); ?></a>
                <a href="<?php echo esc_url($urls["post_job"]); ?>"><?php echo esc_html__('Post a Job', 'tnet-shared-shell'); ?></a>
                <a href="<?php echo esc_url($urls["schools"]); ?>"><?php echo esc_html__('Schools / Jobsites', 'tnet-shared-shell'); ?></a>
                <a href="<?php echo esc_url($urls["archived"]); ?>"><?php echo esc_html__('Archived Jobs', 'tnet-shared-shell'); ?></a>
              </div>
            </div>
            <?php endif; ?>
            <?php call_user_func($content_renderer); ?>
          </main>
        </div>
        <footer class="tnet-jobs-shell-lab-footer" data-shell-footer>
          <a class="tnet-jobs-shell-lab-footer-brand" href="<?php echo esc_url($shell_home_url); ?>" aria-label="<?php echo esc_attr__('Teachers.Net home', 'tnet-shared-shell'); ?>">
            <img src="<?php echo esc_url($brand_image); ?>" alt="<?php echo esc_attr__('Teachers.Net', 'tnet-shared-shell'); ?>">
          </a>
          <div class="tnet-jobs-shell-lab-footer-utility">
            <nav aria-label="<?php echo esc_attr__('Employer shell footer links', 'tnet-shared-shell'); ?>">
              <?php foreach ($shell_footer_links as [$label, $url]) : ?>
                <a href="<?php echo esc_url($url); ?>"><?php echo esc_html__($label, 'tnet-shared-shell'); ?></a>
              <?php endforeach; ?>
            </nav>
            <span class="tnet-jobs-shell-lab-footer-copyright">&copy; 2026 <?php echo esc_html__('Teachers.Net', 'tnet-shared-shell'); ?></span>
          </div>
        </footer>
      </div>
      <?php wp_footer(); ?>
    </body>
    </html>
    <?php
