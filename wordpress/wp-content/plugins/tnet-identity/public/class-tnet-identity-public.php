<?php

defined('ABSPATH') || exit;

final class TNet_Identity_Public {
  public static function signup_url($token = '') {
    $url = home_url('/account/sign-up/');
    return $token ? add_query_arg('continuation', sanitize_text_field($token), $url) : $url;
  }

  public static function verify_url($token, $continuation = '') {
    $url = add_query_arg('token', sanitize_text_field($token), home_url('/account/verify/'));
    return $continuation ? add_query_arg('continuation', sanitize_text_field($continuation), $url) : $url;
  }

  public static function resume_url($token) {
    return add_query_arg('token', sanitize_text_field($token), home_url('/account/continue/'));
  }

  public static function public_identity_url() {
    return home_url('/account/identity/');
  }

  public static function location_url() {
    return home_url('/account/location/');
  }

  public static function member_context_url() {
    return home_url('/account/context/');
  }

  public static function launch_router_url() {
    return home_url('/account/launch/');
  }

  private static function next_onboarding_url($user_id) {
    /* Public identity and location intentionally share one compact step. */
    if (TNet_Identity_Service::needs_public_identity($user_id) || TNet_Identity_Service::needs_location($user_id)) return self::public_identity_url();
    if (TNet_Identity_Service::needs_member_context($user_id)) return self::member_context_url();
    $continuation = TNet_Identity_Continuation_Service::consume_for_user($user_id);
    if ($continuation && !empty($continuation['destination_url'])) return $continuation['destination_url'];
    if (TNet_Identity_Service::needs_launch_router($user_id)) return self::launch_router_url();
    return home_url('/');
  }

  public static function render_signup() {
    $errors = [];
    $username_error_message = '';
    $continuation = isset($_REQUEST['continuation']) ? sanitize_text_field(wp_unslash($_REQUEST['continuation'])) : '';
    $values = ['email' => '', 'username' => ''];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'tnet_identity_signup')) {
        $errors[] = new WP_Error('tnet_identity_nonce', __('Security check failed. Please try again.', 'tnet-identity'));
      }
      $values['email'] = sanitize_email(wp_unslash($_POST['email'] ?? ''));
      $values['username'] = sanitize_text_field(wp_unslash($_POST['username'] ?? ''));
      if (!$errors) {
        $result = TNet_Identity_Service::create_account([
          'email' => $values['email'],
          'password' => wp_unslash($_POST['password'] ?? ''),
          'username' => $values['username'],
        ]);
        if (is_wp_error($result)) {
          $errors[] = $result;
          foreach ($result->get_error_codes() as $code) {
            if (strpos((string) $code, 'tnet_identity_username_') === 0) {
              $username_error_message = $result->get_error_message($code);
              break;
            }
          }
        } else {
          if ($continuation) TNet_Identity_Continuation_Service::attach_user($continuation, $result['user_id']);
          wp_safe_redirect(add_query_arg(['created' => '1', 'email' => $values['email']], home_url('/account/verify/')));
          exit;
        }
      }
    }
    $general_errors = array_values(array_filter($errors, static function ($error) {
      if (!is_wp_error($error)) return true;
      foreach ($error->get_error_codes() as $code) {
        if (strpos((string) $code, 'tnet_identity_username_') === 0) return false;
      }
      return true;
    }));
    $username_rules = TNet_Identity_Policy::username_client_rules();
    self::page(__('Create your Teachers.Net account', 'tnet-identity'), function () use ($general_errors, $username_error_message, $username_rules, $values, $continuation) {
      ?>
        <section class="tnet-identity-card" aria-labelledby="tnet-identity-join-title">
          <h1 id="tnet-identity-join-title"><span><?php echo esc_html__('Create your', 'tnet-identity'); ?></span><br><span><?php echo esc_html__('Teachers.Net account', 'tnet-identity'); ?></span></h1>
          <?php self::errors($general_errors); ?>
          <form method="post" class="tnet-identity-form" data-tnet-signup-form>
            <input type="hidden" name="continuation" value="<?php echo esc_attr($continuation); ?>">
            <?php wp_nonce_field('tnet_identity_signup'); ?>
            <label for="tnet-identity-email">Email
              <input id="tnet-identity-email" name="email" type="email" value="<?php echo esc_attr($values['email']); ?>" autocomplete="email" required data-tnet-signup-email>
            </label>
            <label for="tnet-identity-username">Username
              <span class="tnet-identity-username-guidance"><strong><?php echo esc_html__('Your username is permanent.', 'tnet-identity'); ?></strong> <?php echo esc_html__('Avoid your full name, email, school, location, or birth year.', 'tnet-identity'); ?> <button type="button" class="tnet-identity-username-why" data-tnet-identity-username-why aria-expanded="false" aria-controls="tnet-identity-username-explanation"><?php echo esc_html__('Why?', 'tnet-identity'); ?></button></span>
              <input id="tnet-identity-username" name="username" type="text" value="<?php echo esc_attr($values['username']); ?>" minlength="<?php echo esc_attr($username_rules['min']); ?>" maxlength="<?php echo esc_attr($username_rules['max']); ?>" pattern="<?php echo esc_attr($username_rules['pattern']); ?>" autocomplete="username" required aria-describedby="tnet-identity-username-status" aria-invalid="<?php echo $username_error_message ? 'true' : 'false'; ?>" data-tnet-signup-username data-tnet-signup-username-min="<?php echo esc_attr($username_rules['min']); ?>" data-tnet-signup-username-max="<?php echo esc_attr($username_rules['max']); ?>" data-tnet-signup-username-pattern="<?php echo esc_attr($username_rules['pattern']); ?>" data-tnet-signup-username-message="<?php echo esc_attr($username_rules['message']); ?>" data-tnet-signup-username-server-error="<?php echo esc_attr($username_error_message); ?>">
              <span id="tnet-identity-username-status" class="tnet-identity-username-status<?php echo $username_error_message ? ' is-error' : ''; ?>" role="status" aria-live="polite" data-tnet-signup-username-status><?php echo esc_html($username_error_message); ?></span>
              <span id="tnet-identity-username-explanation" class="tnet-identity-username-explanation" hidden><?php echo esc_html__("Your username may be visible to others. You'll choose a separate display name that you can change anytime.", 'tnet-identity'); ?></span>
            </label>
            <label for="tnet-identity-password">Password
              <small><?php echo esc_html__('Use at least 8 characters.', 'tnet-identity'); ?></small>
              <span class="tnet-identity-password-control">
                <input id="tnet-identity-password" name="password" type="password" autocomplete="new-password" minlength="8" required data-tnet-signup-password>
                <button type="button" class="tnet-identity-password-toggle" data-tnet-identity-password-toggle aria-controls="tnet-identity-password" aria-pressed="false">Show</button>
              </span>
            </label>
            <button class="tnet-identity-submit" type="submit" data-tnet-signup-submit disabled><span><?php echo esc_html__('Create account', 'tnet-identity'); ?></span><span class="tnet-identity-submit-arrow" aria-hidden="true">→</span></button>
          </form>
          <p class="tnet-identity-login-prompt"><?php echo esc_html__('Already have an account?', 'tnet-identity'); ?> <a href="<?php echo esc_url(wp_login_url(self::signup_url($continuation))); ?>"><?php echo esc_html__('Log in', 'tnet-identity'); ?></a></p>
        </section>
      <?php
    }, true, null, true);
  }

  public static function render_verify() {
    $token = isset($_REQUEST['token']) ? sanitize_text_field(wp_unslash($_REQUEST['token'])) : '';
    $email = sanitize_email(wp_unslash($_REQUEST['email'] ?? ''));
    $result = null;
    $resent = false;
    $changed = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tnet_identity_resend'])) {
      if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'tnet_identity_resend')) {
        $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
        $result = TNet_Identity_Service::resend_verification_by_email($email);
        if (!is_wp_error($result)) $resent = true;
      } else {
        $result = new WP_Error('tnet_identity_nonce', __('Security check failed. Please try again.', 'tnet-identity'));
      }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tnet_identity_change_email'])) {
      if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'tnet_identity_change_email')) {
        $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
        $new_email = sanitize_email(wp_unslash($_POST['new_email'] ?? ''));
        $result = TNet_Identity_Service::change_pending_email($email, $new_email);
        if (!is_wp_error($result)) {
          $email = $result['email'];
          $changed = true;
          $result = null;
        }
      } else {
        $result = new WP_Error('tnet_identity_nonce', __('Security check failed. Please try again.', 'tnet-identity'));
      }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tnet_identity_verify_code'])) {
      if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'tnet_identity_verify_code')) {
        $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
        $result = TNet_Identity_Service::verify_code($email, wp_unslash($_POST['confirmation_code'] ?? ''));
        if (!is_wp_error($result)) self::complete_verification($result['user_id']);
      } else {
        $result = new WP_Error('tnet_identity_nonce', __('Security check failed. Please try again.', 'tnet-identity'));
      }
    } elseif ($token !== '') {
      $result = TNet_Identity_Service::verify_token($token);
      if (!is_wp_error($result)) self::complete_verification($result['user_id']);
    }
    self::page(__('Confirm your email', 'tnet-identity'), function () use ($result, $resent, $changed, $email) {
      ?>
      <main class="tnet-identity-card tnet-identity-card--verification" aria-labelledby="tnet-identity-verify-title">
        <h1 id="tnet-identity-verify-title"><?php echo esc_html__('Confirm your email', 'tnet-identity'); ?></h1>
        <p class="tnet-identity-intro"><?php echo esc_html__('Enter the 6-digit code we sent to', 'tnet-identity'); ?><br><strong><?php echo esc_html($email ?: __('your email address', 'tnet-identity')); ?></strong></p>
        <?php if ($resent) : ?><p class="tnet-identity-success" role="status"><?php echo esc_html__('A new confirmation code was sent. The previous code and link can no longer be used.', 'tnet-identity'); ?></p><?php elseif ($changed) : ?><p class="tnet-identity-success" role="status"><?php echo esc_html__('Your email was updated. We sent a new confirmation code.', 'tnet-identity'); ?></p><?php elseif (is_wp_error($result)) : ?><div class="tnet-identity-errors" role="alert"><p><?php echo esc_html($result->get_error_message()); ?></p></div><?php endif; ?>
        <form method="post" class="tnet-identity-code-form">
          <?php wp_nonce_field('tnet_identity_verify_code'); ?><input type="hidden" name="tnet_identity_verify_code" value="1"><input type="hidden" name="email" value="<?php echo esc_attr($email); ?>">
          <label for="tnet-identity-confirmation-code">Confirmation code
            <input id="tnet-identity-confirmation-code" name="confirmation_code" type="text" inputmode="numeric" pattern="[0-9]{6}" autocomplete="one-time-code" maxlength="6" aria-describedby="tnet-identity-code-hint" required>
          </label>
          <small id="tnet-identity-code-hint"><?php echo esc_html__('Enter all 6 digits.', 'tnet-identity'); ?></small>
          <button class="tnet-identity-submit" type="submit"><span><?php echo esc_html__('Continue', 'tnet-identity'); ?></span><span class="tnet-identity-submit-arrow" aria-hidden="true">→</span></button>
        </form>
        <div class="tnet-identity-verification-actions">
          <form method="post"><?php wp_nonce_field('tnet_identity_resend'); ?><input type="hidden" name="tnet_identity_resend" value="1"><input type="hidden" name="email" value="<?php echo esc_attr($email); ?>"><button type="submit"><?php echo esc_html__('Resend code', 'tnet-identity'); ?></button></form>
          <span aria-hidden="true">·</span>
          <details><summary><?php echo esc_html__('Change email', 'tnet-identity'); ?></summary>
            <form method="post" class="tnet-identity-change-email"><?php wp_nonce_field('tnet_identity_change_email'); ?><input type="hidden" name="tnet_identity_change_email" value="1"><input type="hidden" name="email" value="<?php echo esc_attr($email); ?>"><label for="tnet-identity-new-email">New email<input id="tnet-identity-new-email" name="new_email" type="email" autocomplete="email" required></label><button type="submit"><?php echo esc_html__('Update email', 'tnet-identity'); ?></button></form>
          </details>
        </div>
        <p class="tnet-identity-screen-note"><?php echo esc_html__('You can also click the verification link in the email.', 'tnet-identity'); ?></p>
      </main>
      <?php
    }, true, null, true);
  }

  private static function complete_verification($user_id) {
    wp_set_auth_cookie((int) $user_id);
    $destination = home_url('/jobs/');
    $continued = TNet_Identity_Continuation_Service::consume_for_user((int) $user_id);
    if (is_array($continued) && !empty($continued['destination_url'])) $destination = $continued['destination_url'];
    if (!$continued && TNet_Identity_Service::needs_avatar((int) $user_id)) $destination = home_url('/account/avatar/');
    wp_safe_redirect($destination);
    exit;
  }

  public static function render_avatar() {
    if (!is_user_logged_in()) {
      wp_safe_redirect(wp_login_url(home_url('/account/avatar/')));
      exit;
    }
    show_admin_bar(false);
    $user_id = get_current_user_id();
    if (!TNet_Identity_Service::needs_avatar($user_id)) {
      wp_safe_redirect(self::next_onboarding_url($user_id));
      exit;
    }
    $errors = [];
    $generation = sanitize_text_field(wp_unslash($_REQUEST['generation'] ?? ''));
    $presentation = sanitize_text_field(wp_unslash($_REQUEST['presentation'] ?? ''));
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'tnet_identity_avatar_step')) {
        $errors[] = new WP_Error('tnet_identity_avatar_nonce', __('Security check failed. Please try again.', 'tnet-identity'));
      } else {
        $generation = sanitize_text_field(wp_unslash($_POST['generation'] ?? ''));
        $presentation = sanitize_text_field(wp_unslash($_POST['presentation'] ?? ''));
        if ($presentation === 'Feminine') $presentation = 'Women';
        if ($presentation === 'Masculine') $presentation = 'Men';
        if (in_array($presentation, ['Show me both', 'both'], true)) $presentation = '';
        $result = null;
        if (!empty($_POST['skip_avatar'])) {
          $pool = TNet_Profile_Avatar::portrait_bank_entries($generation, $presentation);
          if ($pool) $result = TNet_Profile_Avatar::set_portrait_avatar($user_id, $pool[wp_rand(0, count($pool) - 1)]['portrait_id']);
        } elseif (!empty($_FILES['profile_avatar']['name'])) {
          $result = TNet_Profile_Avatar::save_uploaded_avatar($user_id, (array) $_FILES['profile_avatar']);
        } else {
          $portrait_id = sanitize_text_field(wp_unslash($_POST['portrait_id'] ?? ''));
          if ($portrait_id !== '') $result = TNet_Profile_Avatar::set_portrait_avatar($user_id, $portrait_id);
        }
        if (!$result) $errors[] = new WP_Error('tnet_identity_avatar_missing', __('Choose a photo or illustrated avatar, or skip to receive one at random.', 'tnet-identity'));
        elseif (is_wp_error($result)) $errors[] = $result;
        else {
          TNet_Identity_Service::complete_avatar($user_id);
          wp_safe_redirect(self::next_onboarding_url($user_id));
          exit;
        }
      }
    }
    $entries = TNet_Profile_Avatar::portrait_bank_entries();
    $client_entries = [];
    foreach ($entries as $entry) $client_entries[] = ['id' => $entry['portrait_id'], 'generation' => $entry['generation_retrieval_bucket'], 'presentation' => $entry['presentation_retrieval_bucket'], 'url' => $entry['url']];
    self::page(__('Add a profile photo', 'tnet-identity'), function () use ($errors, $client_entries, $generation, $presentation) {
      ?>
      <main class="tnet-identity-card tnet-identity-card--avatar" aria-labelledby="tnet-identity-avatar-title">
          <div data-avatar-photo-context>
            <p class="tnet-identity-kicker">Next step</p>
            <h1 id="tnet-identity-avatar-title">Add a profile photo</h1>
            <p class="tnet-identity-intro">Help people recognize you. Members with a photo get more responses and feel more connected. You can change or remove it anytime.</p>
          </div>
          <?php self::errors($errors); ?>
          <form method="post" class="tnet-identity-avatar-form" enctype="multipart/form-data">
            <?php wp_nonce_field('tnet_identity_avatar_step'); ?>
            <input type="hidden" name="generation" value="<?php echo esc_attr($generation); ?>" data-avatar-generation-value>
            <input type="hidden" name="presentation" value="<?php echo esc_attr($presentation); ?>" data-avatar-presentation-value>
            <input type="hidden" name="portrait_id" value="" data-avatar-selected-id>
            <section class="tnet-identity-photo-choice" id="tnet-identity-photo-mode" role="tabpanel" aria-label="Choose a photo" data-avatar-mode-panel="photo">
              <div class="tnet-identity-upload-surface" data-avatar-dropzone>
                <div class="tnet-identity-photo-placeholder" data-avatar-placeholder aria-hidden="true"><svg viewBox="0 0 64 64" focusable="false"><circle cx="32" cy="21" r="10"></circle><path d="M13 55c1-11 8-18 19-18s18 7 19 18"></path></svg></div>
                <img class="tnet-identity-avatar-preview" data-avatar-preview width="160" height="160" alt="" hidden>
                <p class="tnet-identity-drop-copy" data-avatar-drop-copy>Drag and drop a photo here<br><span>or</span></p>
                <label class="tnet-identity-file-choice" for="tnet-identity-avatar-file"><svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M12 16V4m0 0L7 9m5-5 5 5M5 14v5h14v-5"></path></svg><span data-avatar-file-label>Choose a photo</span><input id="tnet-identity-avatar-file" name="profile_avatar" type="file" accept="image/jpeg,image/png,image/webp"></label>
                <div class="tnet-identity-photo-tools" data-avatar-photo-tools hidden><button type="button" data-avatar-crop-open>Crop &amp; adjust</button><button type="button" data-avatar-photo-remove>Remove photo</button></div>
                <p class="tnet-identity-avatar-help">JPG, PNG, or WebP · up to 5 MB<br><span>For best results, use a square photo at least 600 × 600 px.</span></p>
                <button class="tnet-identity-submit" data-avatar-photo-submit type="submit" hidden><span>Use this photo and continue</span><span class="tnet-identity-submit-arrow" aria-hidden="true">→</span></button>
              </div>
            </section>
            <section class="tnet-identity-avatar-chooser" id="tnet-identity-avatar-mode" role="tabpanel" data-avatar-mode-panel="avatar" hidden aria-labelledby="tnet-identity-chooser-title">
              <h1 id="tnet-identity-chooser-title">Choose an avatar</h1>
              <p class="tnet-identity-avatar-chooser-intro">Pick a style that feels like you. You can always change this later.</p>
              <button type="button" class="tnet-identity-avatar-photo-link" data-avatar-choose-photo>← Prefer a photo? <span>Choose a photo</span></button>
              <div class="tnet-identity-avatar-filters"><fieldset class="tnet-identity-avatar-presentation"><legend>Presentation</legend><div class="tnet-identity-avatar-presentation-options" role="group" aria-label="Presentation"><button type="button" data-avatar-presentation-choice="both" aria-pressed="true">Show me both</button><button type="button" data-avatar-presentation-choice="Feminine" aria-pressed="false">Feminine</button><button type="button" data-avatar-presentation-choice="Masculine" aria-pressed="false">Masculine</button></div><input type="hidden" data-avatar-presentation-filter value="both"></fieldset><label>Generation<select data-avatar-generation-filter><option value="" selected>Any generation</option><option>Gen Z</option><option>Millennial</option><option>Gen X</option><option>Boomer+</option></select></label></div>
              <div class="tnet-identity-avatar-carousel" data-avatar-carousel><button type="button" data-avatar-carousel-prev aria-label="Previous avatars">‹</button><div class="tnet-identity-avatar-viewport" data-avatar-carousel-viewport><div class="tnet-identity-avatar-track" data-avatar-results aria-live="polite"></div></div><button type="button" data-avatar-carousel-next aria-label="Next avatars">›</button></div>
              <p class="tnet-identity-avatar-count" data-avatar-count aria-live="polite"></p>
              <section class="tnet-identity-avatar-selected" data-avatar-selected-preview hidden aria-label="Selected avatar preview"><div class="tnet-identity-avatar-selected-image-wrap"><img data-avatar-selected-image width="144" height="144" alt="Selected illustrated avatar"></div><div class="tnet-identity-avatar-selected-copy"><h3>Preview</h3><p>This is how you’ll appear on Teachers.Net.</p><p class="tnet-identity-avatar-selected-note">You can change your avatar anytime in your profile settings.</p><button class="tnet-identity-submit" data-avatar-submit type="submit" hidden disabled><span>Use this avatar and continue</span><span class="tnet-identity-submit-arrow" aria-hidden="true">→</span></button></div></section>
            </section>
            <section class="tnet-identity-avatar-random-result" data-avatar-random-result hidden aria-labelledby="tnet-identity-random-result-title">
              <p class="tnet-identity-kicker">Next step</p>
              <h2 id="tnet-identity-random-result-title">You’re all set!</h2>
              <p class="tnet-identity-avatar-help">We've chosen an avatar for you. You can change it or add a photo anytime.</p>
              <img data-avatar-random-image width="160" height="160" alt="Assigned illustrated avatar">
              <div class="tnet-identity-random-actions"><button type="button" data-avatar-change-avatar>Change avatar</button><button type="button" data-avatar-result-photo>Choose a photo</button></div>
              <button class="tnet-identity-submit" data-avatar-assigned-submit type="submit"><span>Continue</span><span class="tnet-identity-submit-arrow" aria-hidden="true">→</span></button>
            </section>
            <button class="tnet-identity-avatar-skip" type="button" data-avatar-skip>Skip for now</button>
          </form>
          <section class="tnet-identity-avatar-skip-modal" data-avatar-skip-modal hidden role="dialog" aria-modal="true" aria-labelledby="tnet-identity-skip-title">
            <div class="tnet-identity-avatar-modal-card">
              <button type="button" class="tnet-identity-crop-close" data-avatar-skip-close aria-label="Close skip options">×</button>
              <header class="tnet-identity-avatar-modal-intro">
                <h2 id="tnet-identity-skip-title">We'll give you an avatar</h2>
                <p>No photo is required. Choose an avatar yourself, or let us pick one so you can continue.</p>
              </header>
              <div class="tnet-identity-avatar-modal-actions">
                <button type="button" class="tnet-identity-avatar-choice" data-avatar-choose>
                  <span class="tnet-identity-avatar-choice-visual tnet-identity-avatar-choice-visual--portraits" aria-hidden="true"><?php foreach (array_slice($client_entries, 0, 3) as $choice_avatar) : ?><img src="<?php echo esc_url($choice_avatar['url']); ?>" width="112" height="112" alt=""><?php endforeach; ?></span>
                  <span class="tnet-identity-avatar-choice-title">Browse avatars</span>
                  <span class="tnet-identity-avatar-choice-copy">Explore our collection and choose the one you like.</span>
                  <span class="tnet-identity-avatar-choice-arrow" aria-hidden="true">→</span>
                </button>
                <button type="button" class="tnet-identity-avatar-choice" data-avatar-pick-open>
                  <span class="tnet-identity-avatar-choice-visual tnet-identity-avatar-choice-visual--random" aria-hidden="true"><svg viewBox="0 0 64 64" focusable="false"><circle cx="32" cy="21" r="10"></circle><path d="M13 55c1-11 8-18 19-18s18 7 19 18"></path></svg></span>
                  <span class="tnet-identity-avatar-choice-title">Surprise me</span>
                  <span class="tnet-identity-avatar-choice-copy">Let us pick one for you and continue.</span>
                  <span class="tnet-identity-avatar-choice-arrow" aria-hidden="true">→</span>
                </button>
                <div class="tnet-identity-avatar-modal-footer"><button type="button" class="tnet-identity-avatar-modal-cancel" data-avatar-skip-cancel>← Go back</button></div>
              </div>
            </div>
          </section>
          <section class="tnet-identity-avatar-pick-modal" data-avatar-pick-modal hidden role="dialog" aria-modal="true" aria-labelledby="tnet-identity-pick-title">
            <div class="tnet-identity-avatar-modal-card"><button type="button" class="tnet-identity-crop-close" data-avatar-pick-close aria-label="Close pick one options">×</button><h2 id="tnet-identity-pick-title">What kind of avatar should we choose?</h2><p>No personal demographic information is required. Pick a style if you want to, or leave it at “Either is fine.”</p><fieldset><legend>Presentation</legend><div class="tnet-identity-preference-options"><label><input type="radio" name="avatar_pick_presentation" value="Feminine">Feminine</label><label><input type="radio" name="avatar_pick_presentation" value="Masculine">Masculine</label><label><input type="radio" name="avatar_pick_presentation" value="Either is fine" checked>Either is fine</label></div></fieldset><label class="tnet-identity-pick-generation">Generation (optional)<select data-avatar-pick-generation><option value="">Any generation</option><option>Gen Z</option><option>Millennial</option><option>Gen X</option><option>Boomer+</option></select></label><div class="tnet-identity-avatar-modal-actions"><button type="button" class="tnet-identity-submit" data-avatar-pick-submit>Pick one for me &amp; continue</button><button type="button" class="tnet-identity-avatar-modal-cancel" data-avatar-pick-cancel>Go back</button></div></div>
          </section>
          <section class="tnet-identity-crop-modal" data-avatar-crop-modal hidden role="dialog" aria-modal="true" aria-labelledby="tnet-identity-crop-title">
            <div class="tnet-identity-crop-dialog"><button type="button" class="tnet-identity-crop-close" data-avatar-crop-close aria-label="Close crop adjustment">×</button><h2 id="tnet-identity-crop-title">Adjust your photo</h2><div class="tnet-identity-crop-layout"><div class="tnet-identity-crop-stage" data-avatar-crop-stage><canvas width="360" height="360" data-avatar-crop-canvas aria-label="Circular crop preview"></canvas></div><div class="tnet-identity-crop-side"><p>Drag to reposition, then adjust zoom. Your photo will be cropped to a circle.</p><div class="tnet-identity-crop-preview"><span>Preview</span><div data-avatar-crop-result></div></div></div></div><label class="tnet-identity-zoom">Zoom<input type="range" min="1" max="3" step="0.01" value="1" data-avatar-crop-zoom></label><div class="tnet-identity-crop-actions"><button type="button" data-avatar-crop-reset>Reset</button><button type="button" class="tnet-identity-submit" data-avatar-crop-apply>Apply</button></div></div>
          </section>
      </main>
      <script type="application/json" data-avatar-bank><?php echo wp_json_encode($client_entries); ?></script>
      <?php
    }, true, null, true);
  }

  public static function render_public_identity() {
    if (!is_user_logged_in()) {
      wp_safe_redirect(wp_login_url(self::public_identity_url()));
      exit;
    }
    show_admin_bar(false);
    $user_id = get_current_user_id();
    $public_identity_pending = TNet_Identity_Service::needs_public_identity($user_id);
    $location_pending = TNet_Identity_Service::needs_location($user_id);
    if (!$public_identity_pending && !$location_pending) {
      wp_safe_redirect(self::next_onboarding_url($user_id));
      exit;
    }
    $user = wp_get_current_user();
    $error = null;
    $value = (string) ($user->display_name ?: $user->user_login);
    $location = TNet_Identity_Service::location($user_id);
    $mode = $location['country_code'] === 'US' ? 'us' : ($location['country_code'] !== '' ? 'international' : '');
    $state = $location['region_code'];
    $country = $location['country_code'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'tnet_identity_public_identity_step')) {
        $error = new WP_Error('tnet_identity_nonce', __('Security check failed. Please try again.', 'tnet-identity'));
      } else {
        $value = wp_unslash($_POST['display_name'] ?? '');
        $mode = sanitize_key((string) wp_unslash($_POST['location_mode'] ?? ''));
        if (!in_array($mode, ['us', 'international'], true)) $mode = '';
        $state = sanitize_text_field(wp_unslash($_POST['region_code'] ?? ''));
        $country = sanitize_text_field(wp_unslash($_POST['country_code'] ?? ''));
        $display_name = TNet_Identity_Policy::validate_display_name($value);
        if (is_wp_error($display_name)) {
          $error = $display_name;
        } elseif ($location_pending && empty($_POST['skip_location'])) {
          if ($mode === '') {
            $error = new WP_Error('tnet_identity_location_choice_required', __('Select a location or choose Skip for now.', 'tnet-identity'));
          } else {
            $location_result = TNet_Identity_Service::save_location($user_id, $mode, $mode === 'us' ? $state : $country);
            if (is_wp_error($location_result)) $error = $location_result;
          }
        }
        if (!is_wp_error($error) && $error === null) {
          if ($public_identity_pending) {
            $result = TNet_Identity_Service::update_display_name($user_id, $display_name);
            if (is_wp_error($result)) $error = $result;
            else TNet_Identity_Service::complete_public_identity($user_id);
          }
          if ($error === null && $location_pending) TNet_Identity_Service::complete_location($user_id);
          if ($error === null) {
          wp_safe_redirect(self::next_onboarding_url($user_id));
          exit;
          }
        }
      }
    }
    $avatar = class_exists('TNet_Profile_Avatar') ? TNet_Profile_Avatar::resolve_avatar($user_id, 112) : ['url' => get_avatar_url($user_id, ['size' => 112])];
    $profile_url = get_author_posts_url($user_id);
    self::page(__('Tell us about yourself', 'tnet-identity'), function () use ($user, $value, $error, $avatar, $profile_url, $mode, $state, $country, $location_pending) {
      $error_message = is_wp_error($error) ? $error->get_error_message() : '';
      $states = TNet_Identity_Location_Policy::us_regions();
      $countries = TNet_Identity_Location_Policy::country_codes();
      ?>
      <main class="tnet-identity-card tnet-identity-card--public-identity" aria-labelledby="tnet-identity-public-identity-title">
        <a class="tnet-identity-member-cluster" href="<?php echo esc_url($profile_url); ?>" aria-label="<?php echo esc_attr(sprintf(__('Open %s’s member profile', 'tnet-identity'), $user->user_login)); ?>">
          <img src="<?php echo esc_url($avatar['url']); ?>" width="112" height="112" alt="<?php echo esc_attr(sprintf(__('%s’s selected avatar', 'tnet-identity'), $user->user_login)); ?>">
          <span class="tnet-identity-member-handle">@<?php echo esc_html($user->user_login); ?></span>
          <span class="tnet-identity-member-note"><?php echo esc_html__('Your permanent username', 'tnet-identity'); ?></span>
        </a>
        <div class="tnet-identity-public-identity-content">
          <h1 id="tnet-identity-public-identity-title"><?php echo esc_html__('Tell us about yourself', 'tnet-identity'); ?></h1>
          <p class="tnet-identity-intro"><?php echo esc_html__('This is the name other members will usually see.', 'tnet-identity'); ?><br><?php echo esc_html__('You can change it anytime.', 'tnet-identity'); ?></p>
          <form method="post" class="tnet-identity-public-identity-form" data-tnet-display-name-form data-tnet-location-form>
            <?php wp_nonce_field('tnet_identity_public_identity_step'); ?>
            <label for="tnet-identity-display-name"><?php echo esc_html__('Display name', 'tnet-identity'); ?></label>
            <input id="tnet-identity-display-name" name="display_name" type="text" value="<?php echo esc_attr($value); ?>" maxlength="50" minlength="2" autocomplete="name" required aria-describedby="tnet-identity-display-name-help tnet-identity-display-name-status" aria-invalid="<?php echo $error_message ? 'true' : 'false'; ?>" data-tnet-display-name-input data-tnet-display-name-server-error="<?php echo esc_attr($error_message); ?>">
            <p id="tnet-identity-display-name-status" class="tnet-identity-display-name-status<?php echo $error_message ? ' is-error' : ''; ?>" role="status" aria-live="polite" data-tnet-display-name-status><?php echo esc_html($error_message); ?></p>
            <small id="tnet-identity-display-name-help"><?php echo esc_html__('Use letters, numbers, spaces, and basic punctuation.', 'tnet-identity'); ?></small>
            <?php if ($location_pending) : ?>
              <section class="tnet-identity-combined-location" aria-labelledby="tnet-identity-location-heading">
                <div class="tnet-identity-combined-location-heading"><h2 id="tnet-identity-location-heading"><?php echo esc_html__('Location', 'tnet-identity'); ?></h2><p><?php echo esc_html__('Optional', 'tnet-identity'); ?></p></div>
                <select id="tnet-identity-location-mode" name="location_mode" data-tnet-location-mode-select autocomplete="country">
                  <option value=""<?php selected($mode, ''); ?>><?php echo esc_html__('Select your location', 'tnet-identity'); ?></option>
                  <option value="us"<?php selected($mode, 'us'); ?>><?php echo esc_html__('United States', 'tnet-identity'); ?></option>
                  <option value="international"<?php selected($mode, 'international'); ?>><?php echo esc_html__('Outside the U.S.', 'tnet-identity'); ?></option>
                </select>
                <section class="tnet-identity-combined-location-selector" data-tnet-location-panel="us"<?php if ($mode !== 'us') echo ' hidden'; ?>>
                  <label for="tnet-identity-region-code"><?php echo esc_html__('State', 'tnet-identity'); ?></label>
                  <select id="tnet-identity-region-code" name="region_code" autocomplete="address-level1" data-tnet-location-state<?php disabled($mode !== 'us'); ?>><option value=""><?php echo esc_html__('Select your state', 'tnet-identity'); ?></option><?php foreach ($states as $code => $name) : ?><option value="<?php echo esc_attr($code); ?>" <?php selected($state, $code); ?>><?php echo esc_html($name . ' (' . $code . ')'); ?></option><?php endforeach; ?></select>
                </section>
                <section class="tnet-identity-combined-location-selector" data-tnet-location-panel="international"<?php if ($mode !== 'international') echo ' hidden'; ?>>
                  <label for="tnet-identity-country-code"><?php echo esc_html__('Country', 'tnet-identity'); ?></label>
                  <select id="tnet-identity-country-code" name="country_code" autocomplete="country" data-tnet-location-country<?php disabled($mode !== 'international'); ?>><option value=""><?php echo esc_html__('Select your country', 'tnet-identity'); ?></option><?php foreach ($countries as $code) : ?><option value="<?php echo esc_attr($code); ?>" <?php selected($country, $code); ?>><?php echo esc_html($code); ?></option><?php endforeach; ?></select>
                </section>
              </section>
            <?php endif; ?>
            <button class="tnet-identity-submit tnet-identity-public-identity-submit" type="submit" data-tnet-display-name-submit><span><?php echo esc_html__('Continue', 'tnet-identity'); ?></span><span class="tnet-identity-submit-arrow" aria-hidden="true">→</span></button>
            <?php if ($location_pending) : ?><button class="tnet-identity-location-skip" type="submit" name="skip_location" value="1"><?php echo esc_html__('Skip location', 'tnet-identity'); ?></button><?php endif; ?>
          </form>
        </div>
      </main>
      <?php
    }, true, null, true);
  }

  public static function render_location() {
    if (!is_user_logged_in()) {
      wp_safe_redirect(wp_login_url(self::location_url()));
      exit;
    }
    show_admin_bar(false);
    $user_id = get_current_user_id();
    wp_safe_redirect(TNet_Identity_Service::needs_public_identity($user_id) || TNet_Identity_Service::needs_location($user_id) ? self::public_identity_url() : self::next_onboarding_url($user_id));
    exit;
  }

  public static function render_member_context() {
    if (!is_user_logged_in()) {
      wp_safe_redirect(wp_login_url(self::member_context_url()));
      exit;
    }
    show_admin_bar(false);
    $user_id = get_current_user_id();
    if (!TNet_Identity_Service::needs_member_context($user_id)) {
      wp_safe_redirect(self::next_onboarding_url($user_id));
      exit;
    }

    $error = null;
    $context = TNet_Identity_Service::member_context($user_id);
    $roles = (array) ($context['roles'] ?? []);
    $intents = (array) ($context['intents'] ?? []);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'tnet_identity_member_context_step')) {
        $error = new WP_Error('tnet_identity_nonce', __('Security check failed. Please try again.', 'tnet-identity'));
      } elseif (!empty($_POST['skip_member_context'])) {
        TNet_Identity_Service::clear_member_context($user_id);
        TNet_Identity_Service::complete_member_context($user_id);
        wp_safe_redirect(self::next_onboarding_url($user_id));
        exit;
      } else {
        $roles = array_map('sanitize_key', (array) wp_unslash($_POST['roles'] ?? []));
        $intents = array_map('sanitize_key', (array) wp_unslash($_POST['intents'] ?? []));
        $result = TNet_Identity_Service::save_member_context($user_id, $roles, $intents);
        if (is_wp_error($result)) {
          $error = $result;
        } else {
          TNet_Identity_Service::complete_member_context($user_id);
          wp_safe_redirect(self::next_onboarding_url($user_id));
          exit;
        }
      }
    }

    $choices = [
      ['type' => 'role', 'value' => 'teacher', 'label' => 'I am a teacher', 'detail' => 'Teach in a school or classroom', 'icon' => 'teacher'],
      ['type' => 'role', 'value' => 'administrator', 'label' => 'I am a school administrator', 'detail' => 'Lead or manage a school or district', 'icon' => 'administrator'],
      ['type' => 'role', 'value' => 'education_student', 'label' => 'I am studying to become an educator', 'detail' => 'Currently a student', 'icon' => 'student'],
      ['type' => 'role', 'value' => 'retired_teacher', 'label' => 'I am a retired teacher', 'detail' => 'Formerly worked in education', 'icon' => 'retired'],
      ['type' => 'intent', 'value' => 'hiring', 'label' => 'I hire or recruit educators', 'detail' => 'Post and manage teaching jobs', 'icon' => 'hiring'],
      ['type' => 'role', 'value' => 'vendor', 'label' => 'I am an education vendor', 'detail' => 'Provide products or services to schools', 'icon' => 'vendor'],
      ['type' => 'role', 'value' => 'tutor', 'label' => 'I am a tutor', 'detail' => 'Provide one-to-one or small group instruction', 'icon' => 'tutor'],
      ['type' => 'role', 'value' => 'other', 'label' => 'Other', 'detail' => 'Something else', 'icon' => 'other'],
    ];
    self::page(__('Select all that apply', 'tnet-identity'), function () use ($error, $roles, $intents, $choices) {
      $error_message = is_wp_error($error) ? $error->get_error_message() : '';
      ?>
      <main class="tnet-identity-card tnet-identity-card--member-context" aria-labelledby="tnet-identity-member-context-title">
        <h1 id="tnet-identity-member-context-title">Select all that apply</h1>
        <p class="tnet-identity-intro">Tell us more about you — we’ll use this to make Teachers.Net more relevant to you.</p>
        <?php if ($error_message) : ?><div class="tnet-identity-errors" role="alert"><p><?php echo esc_html($error_message); ?></p></div><?php endif; ?>
        <form method="post" class="tnet-identity-member-context-form">
          <?php wp_nonce_field('tnet_identity_member_context_step'); ?>
          <fieldset class="tnet-identity-member-context-choices"><legend class="screen-reader-text">Choose every role or intent that applies</legend>
            <?php foreach ($choices as $choice) :
              $name = $choice['type'] === 'role' ? 'roles[]' : 'intents[]';
              $selected = $choice['type'] === 'role' ? in_array($choice['value'], $roles, true) : in_array($choice['value'], $intents, true);
            ?>
              <label class="tnet-identity-member-context-choice">
                <input type="checkbox" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($choice['value']); ?>"<?php checked($selected); ?>>
                <span class="tnet-identity-member-context-choice-icon tnet-identity-member-context-choice-icon--<?php echo esc_attr($choice['icon']); ?>" aria-hidden="true"><?php self::member_context_icon($choice['icon']); ?></span>
                <span><strong><?php echo esc_html($choice['label']); ?></strong><small><?php echo esc_html($choice['detail']); ?></small></span>
              </label>
            <?php endforeach; ?>
          </fieldset>
          <button class="tnet-identity-submit tnet-identity-member-context-submit" type="submit"><span>Continue</span><span class="tnet-identity-submit-arrow" aria-hidden="true">→</span></button>
          <button class="tnet-identity-member-context-skip" type="submit" name="skip_member_context" value="1">Skip for now</button>
        </form>
      </main>
      <?php
    }, true, null, true);
  }

  /**
   * Release destinations are configuration, not a probe of the current local
   * route table. Required destinations can therefore be represented before
   * their owning product has supplied its canonical URL.
   */
  public static function launch_destinations() {
    $destinations = [
      'profile' => [
        'label' => __('Build my profile', 'tnet-identity'),
        'copy' => __('Add your grades, subjects, educator badges and a short bio.', 'tnet-identity'),
        'icon' => 'profile',
        'url' => '',
        'enabled' => true,
        'release_required' => true,
        'availability' => 'unresolved',
      ],
      'community' => [
        'label' => __('Join teacher discussions', 'tnet-identity'),
        'copy' => __('See what educators are talking about now.', 'tnet-identity'),
        'icon' => 'community',
        'url' => home_url('/community/'),
        'enabled' => true,
        'release_required' => true,
        'availability' => 'ready',
      ],
      'lessons' => [
        'label' => __('Find lesson plans', 'tnet-identity'),
        'copy' => __('Browse classroom ideas and teaching resources.', 'tnet-identity'),
        'icon' => 'lessons',
        'url' => '',
        'enabled' => true,
        'release_required' => true,
        'availability' => 'unresolved',
      ],
      'jobs' => [
        'label' => __('Explore education jobs', 'tnet-identity'),
        'copy' => __('Find and manage teaching opportunities.', 'tnet-identity'),
        'icon' => 'jobs',
        'url' => home_url('/jobs/'),
        'enabled' => false,
        'release_required' => false,
        'availability' => 'feature_disabled',
      ],
      'home' => [
        'label' => __('Explore Teachers.Net on my own', 'tnet-identity'),
        'copy' => '',
        'icon' => 'home',
        'url' => home_url('/'),
        'enabled' => true,
        'release_required' => true,
        'availability' => 'ready',
        'quiet' => true,
      ],
    ];
    return apply_filters('tnet_identity_launch_destinations', $destinations);
  }

  /**
   * The Shared Shell owns the rail markup. Identity only resolves the member's
   * canonical, explicitly persisted location into its existing family input.
   * State destinations are intentionally production-canonical: this local
   * runtime does not mirror the legacy /states/ route collection.
   */
  private static function launch_rail_families($user_id) {
    $families = [
      ['name' => 'Hot Topics', 'icon' => 'flame'],
      ['name' => 'Grade Levels', 'icon' => 'graduate'],
      ['name' => 'Subject Areas', 'icon' => 'book'],
      ['name' => 'States', 'icon' => 'pin'],
    ];
    $location = TNet_Identity_Service::location($user_id);
    if (($location['country_code'] ?? '') !== 'US') return $families;

    $region = strtoupper((string) ($location['region_code'] ?? ''));
    $regions = TNet_Identity_Location_Policy::us_regions();
    if (!isset($regions[$region])) return $families;

    $families[3] = [
      'name' => $regions[$region] . ' Teachers',
      'icon' => 'pin',
      'direct_url' => 'https://teachers.net/states/' . strtolower($region) . '/',
    ];
    return $families;
  }

  private static function render_launch_destination_card($key, array $destination) {
    $url = self::launch_destination_url($destination);
    $class = 'tnet-identity-launch-destination tnet-identity-launch-destination--' . sanitize_html_class($key);
    $content = static function () use ($destination) {
      ?>
        <span class="tnet-identity-launch-icon tnet-identity-launch-icon--<?php echo esc_attr($destination['icon']); ?>" aria-hidden="true"><?php self::launch_icon($destination['icon']); ?></span>
        <span class="tnet-identity-launch-destination-copy"><strong><?php echo esc_html($destination['label']); ?></strong><span><?php echo esc_html($destination['copy']); ?></span></span>
        <span class="tnet-identity-launch-destination-arrow" aria-hidden="true">→</span>
      <?php
    };
    if ($url !== '') {
      ?>
        <form class="tnet-identity-launch-card-form" method="post">
          <?php wp_nonce_field('tnet_identity_launch_router'); ?>
          <input type="hidden" name="destination" value="<?php echo esc_attr($key); ?>">
          <button class="<?php echo esc_attr($class); ?>" type="submit"><?php $content(); ?></button>
        </form>
      <?php
      return;
    }
    ?>
      <article class="<?php echo esc_attr($class); ?>"><?php $content(); ?></article>
    <?php
  }

  public static function render_launch_router() {
    if (!is_user_logged_in()) {
      wp_safe_redirect(wp_login_url(self::launch_router_url()));
      exit;
    }
    show_admin_bar(false);
    $user_id = get_current_user_id();
    if (!TNet_Identity_Service::needs_launch_router($user_id)) {
      wp_safe_redirect(home_url('/'));
      exit;
    }

    $destinations = self::launch_destinations();
    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'tnet_identity_launch_router')) {
        $error = new WP_Error('tnet_identity_nonce', __('Security check failed. Please try again.', 'tnet-identity'));
      } else {
        $destination_key = sanitize_key(wp_unslash($_POST['destination'] ?? ''));
        $destination = $destinations[$destination_key] ?? null;
        $url = is_array($destination) ? self::launch_destination_url($destination) : '';
        if (!$destination || empty($destination['enabled']) || $url === '') {
          $error = new WP_Error('tnet_identity_launch_destination_unavailable', __('That destination is not available yet. Choose another option.', 'tnet-identity'));
        } else {
          TNet_Identity_Service::complete_launch_router($user_id, $destination_key);
          wp_safe_redirect($url);
          exit;
        }
      }
    }
    TNet_Identity_Service::mark_launch_router_offered($user_id);

    self::page(__('Choose what to do next', 'tnet-identity'), function () use ($destinations, $error) {
      $profile = $destinations['profile'] ?? [];
      $secondary = ['community', 'lessons'];
      $profile_url = is_array($profile) ? self::launch_destination_url($profile) : '';
      ?>
      <main class="tnet-identity-launch" aria-labelledby="tnet-identity-launch-title">
        <div class="tnet-identity-launch-success" aria-hidden="true">✓</div>
        <h1 id="tnet-identity-launch-title"><?php echo esc_html__('You’re all set!', 'tnet-identity'); ?></h1>
        <p class="tnet-identity-launch-intro"><?php echo esc_html__('Choose where to begin. You can always return to complete your profile later.', 'tnet-identity'); ?></p>
        <?php if (is_wp_error($error)) : ?><div class="tnet-identity-errors" role="alert"><p><?php echo esc_html($error->get_error_message()); ?></p></div><?php endif; ?>
        <?php if (is_array($profile) && !empty($profile['enabled'])) : ?>
          <?php if ($profile_url !== '') : ?><form class="tnet-identity-launch-feature-form" method="post"><?php wp_nonce_field('tnet_identity_launch_router'); ?><input type="hidden" name="destination" value="profile"><button class="tnet-identity-launch-feature" type="submit"><?php else : ?><article class="tnet-identity-launch-feature"><?php endif; ?>
            <span class="tnet-identity-launch-feature-icon tnet-identity-launch-icon tnet-identity-launch-icon--profile" aria-hidden="true"><?php self::launch_icon('profile'); ?></span>
            <span class="tnet-identity-launch-feature-copy"><span class="tnet-identity-launch-kicker"><?php echo esc_html__('Recommended', 'tnet-identity'); ?></span><strong><?php echo esc_html__('Complete your profile', 'tnet-identity'); ?></strong><span><?php echo esc_html__('Add your grades, subjects and educator badges so we can personalize Teachers.Net for what you teach.', 'tnet-identity'); ?></span></span>
            <span class="tnet-identity-launch-feature-action"><?php echo esc_html__('Complete my profile', 'tnet-identity'); ?><span aria-hidden="true">→</span></span>
          <?php if ($profile_url !== '') : ?></button></form><?php else : ?></article><?php endif; ?>
        <?php endif; ?>
        <section class="tnet-identity-launch-exploration" aria-labelledby="tnet-identity-launch-exploration-title">
          <h2 id="tnet-identity-launch-exploration-title"><?php echo esc_html__('Or start exploring', 'tnet-identity'); ?></h2>
          <div class="tnet-identity-launch-options" aria-label="<?php echo esc_attr__('Explore Teachers.Net', 'tnet-identity'); ?>">
            <?php foreach ($secondary as $key) : $destination = $destinations[$key] ?? null; if (is_array($destination) && !empty($destination['enabled'])) self::render_launch_destination_card($key, $destination); endforeach; ?>
          </div>
        </section>
        <?php $home = $destinations['home'] ?? null; $home_url = is_array($home) ? self::launch_destination_url($home) : ''; ?>
        <?php if ($home && !empty($home['enabled']) && $home_url !== '') : ?>
          <form class="tnet-identity-launch-home" method="post">
            <?php wp_nonce_field('tnet_identity_launch_router'); ?>
            <input type="hidden" name="destination" value="home">
            <button type="submit"><?php echo esc_html($home['label']); ?><span aria-hidden="true">→</span></button>
          </form>
        <?php endif; ?>
      </main>
      <?php
    }, true, null, false, false, true, 'identity-launch');
  }

  private static function launch_destination_url(array $destination) {
    $url = isset($destination['url']) ? trim((string) $destination['url']) : '';
    return $url === '' ? '' : wp_validate_redirect($url, '');
  }

  private static function launch_icon($icon) {
    $icons = [
      'profile' => '<svg viewBox="0 0 48 48" focusable="false"><circle cx="24" cy="15" r="7"></circle><path d="M9 41c1-10 6-16 15-16s14 6 15 16M35 11h7m-3.5-3.5v7"></path></svg>',
      'community' => '<svg viewBox="0 0 48 48" focusable="false"><path d="M8 11h32v21H23l-8 7v-7H8V11z"></path><path d="M16 19h16m-16 6h11"></path></svg>',
      'lessons' => '<svg viewBox="0 0 48 48" focusable="false"><path d="M7 10c7-2 13 0 17 5v24c-5-5-11-7-17-5V10zm34 0c-7-2-13 0-17 5v24c5-5 11-7 17-5V10z"></path></svg>',
      'jobs' => '<svg viewBox="0 0 48 48" focusable="false"><path d="M7 15h34v26H7V15zm11 0v-5h12v5m-23 9h34m-20 0h6"></path></svg>',
      'home' => '<svg viewBox="0 0 48 48" focusable="false"><path d="m6 23 18-15 18 15v18H29V29H19v12H6V23z"></path></svg>',
    ];
    echo $icons[$icon] ?? $icons['home']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
  }

  private static function member_context_icon($icon) {
    $icons = [
      'teacher' => '<svg viewBox="0 0 48 48" focusable="false"><path d="m5 18 19-10 19 10-19 10L5 18Zm8 5v8c0 5 22 5 22 0v-8M43 18v13"></path></svg>',
      'administrator' => '<svg viewBox="0 0 48 48" focusable="false"><path d="M6 20h36L24 7 6 20zM10 20v22m28-22v22M10 42h28M18 42V31h12v11M16 25h4m8 0h4"></path></svg>',
      'student' => '<svg viewBox="0 0 48 48" focusable="false"><path d="M6 12c8-3 13 0 18 4v24c-5-4-10-7-18-4V12zm36 0c-8-3-13 0-18 4v24c5-4 10-7 18-4V12z"></path></svg>',
      'retired' => '<svg viewBox="0 0 48 48" focusable="false"><circle cx="18" cy="14" r="6"></circle><path d="M7 40c1-10 5-16 11-16s10 6 11 16M31 31a8 8 0 1 0 16 0 8 8 0 0 0-16 0zm8-5v5l3 2"></path></svg>',
      'hiring' => '<svg viewBox="0 0 48 48" focusable="false"><circle cx="24" cy="14" r="6"></circle><circle cx="11" cy="19" r="4"></circle><circle cx="37" cy="19" r="4"></circle><path d="M14 40c0-10 4-16 10-16s10 6 10 16M3 39c0-7 3-12 8-12s8 5 8 12M29 39c0-7 3-12 8-12s8 5 8 12"></path></svg>',
      'vendor' => '<svg viewBox="0 0 48 48" focusable="false"><path d="M7 18h34v23H7V18zm11 0v-5h12v5M7 26h34m-20 0h6"></path></svg>',
      'tutor' => '<svg viewBox="0 0 48 48" focusable="false"><circle cx="14" cy="15" r="6"></circle><path d="M4 40c1-10 5-16 10-16s9 6 10 16M27 10h16v22H27zm4 7h8m-8 6h6"></path></svg>',
      'other' => '<svg viewBox="0 0 48 48" focusable="false"><circle cx="10" cy="24" r="4"></circle><circle cx="24" cy="24" r="4"></circle><circle cx="38" cy="24" r="4"></circle></svg>',
    ];
    echo $icons[$icon] ?? $icons['other']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
  }

  public static function render_continue() {
    $token = isset($_GET['token']) ? sanitize_text_field(wp_unslash($_GET['token'])) : '';
    if ($token === '') {
      self::page(__('Continue to Teachers.Net', 'tnet-identity'), function () { ?><main class="tnet-identity-card tnet-identity-card--narrow"><h1><?php echo esc_html__('We could not continue that request', 'tnet-identity'); ?></h1><div class="tnet-identity-errors" role="alert"><p><?php echo esc_html__('That return link is missing or invalid.', 'tnet-identity'); ?></p></div><p><a href="<?php echo esc_url(home_url('/jobs/')); ?>"><?php echo esc_html__('Return to Job Center', 'tnet-identity'); ?></a></p></main><?php }, false);
      return;
    }
    if (!is_user_logged_in()) {
      wp_safe_redirect(wp_login_url(self::resume_url($token)));
      exit;
    }
    $result = TNet_Identity_Continuation_Service::consume($token, get_current_user_id());
    if (!is_wp_error($result)) { wp_safe_redirect($result['destination_url']); exit; }
    self::page(__('Continue to Teachers.Net', 'tnet-identity'), function () use ($result) { ?><main class="tnet-identity-card tnet-identity-card--narrow"><h1><?php echo esc_html__('We could not continue that request', 'tnet-identity'); ?></h1><div class="tnet-identity-errors" role="alert"><p><?php echo esc_html($result->get_error_message()); ?></p></div><p><a href="<?php echo esc_url(home_url('/jobs/')); ?>"><?php echo esc_html__('Return to Job Center', 'tnet-identity'); ?></a></p></main><?php }, false);
  }

  private static function page($title, callable $content, $use_shell = true, $right = null, $identity_journey = false, $suppress_anonymous_actions = false, $suppress_search = false, $route_class = 'identity-join') {
    status_header(200);
    if ($use_shell && class_exists('TNet_Shared_Shell')) {
      // The launch route suppresses the WordPress toolbar but WordPress can
      // still enqueue its 32px offset stylesheet before the shell is emitted.
      // That invisible offset is enough to push a short route's footer below
      // the viewport, so remove it only for this owned route state.
      if ($route_class === 'identity-launch') {
        show_admin_bar(false);
        wp_dequeue_style('admin-bar');
      }
      $logged_in = is_user_logged_in();
      $current_user = $logged_in ? wp_get_current_user() : null;
      $identity_name = $logged_in && $current_user && $current_user->display_name ? $current_user->display_name : 'Guest user';
      $identity = ['name' => $identity_name, 'email' => $logged_in && $current_user ? $current_user->user_email : '', 'descriptor' => ''];
      if ($logged_in && $current_user && class_exists('TNet_Profile_Avatar')) {
        $resolved_avatar = TNet_Profile_Avatar::resolve_avatar((int) $current_user->ID, 48);
        if (!empty($resolved_avatar['url'])) {
          $identity['avatar_url'] = $resolved_avatar['url'];
          $identity['avatar_source'] = $resolved_avatar['source'] ?? 'profile-resolver';
        }
      }
      TNet_Shared_Shell::enqueue_assets('community');
      wp_enqueue_style('tnet-identity-public', TNET_IDENTITY_PLUGIN_URL . 'public/css/tnet-identity-public.css', ['tnet-shared-shell-community'], self::asset_version('public/css/tnet-identity-public.css'));
      wp_enqueue_script('tnet-identity-public', TNET_IDENTITY_PLUGIN_URL . 'public/js/tnet-identity-public.js', [], self::asset_version('public/js/tnet-identity-public.js'), true);
      $login_url = wp_login_url(self::signup_url());
      TNet_Shared_Shell::render_host([
        'contract' => 'canonical',
        'adapter' => 'community3',
        'workspace_owner' => 'consumer',
        'fixture' => 'identity-join',
        'clean' => true,
        'focused_identity_journey' => $identity_journey,
        'suppress_anonymous_actions' => $suppress_anonymous_actions,
        'presentation' => 'flush',
        'document_title' => $title . ' | Teachers.Net',
        'route_class' => $route_class,
        'fixture_state' => $logged_in ? 'auth-unread' : 'guest',
        'logged_in' => $logged_in,
        'employer_access' => false,
        'home_url' => home_url('/'),
        'active_destination' => '',
        'brand_image' => TNET_SHARED_SHELL_PLUGIN_URL . 'public/assets/teachers-net-wordmark.svg',
        'identity' => $identity,
        'urls' => [
          'post_job' => home_url('/jobs/employer/new/'),
          'my_jobs' => home_url('/jobs/employer/my-jobs/'),
          'schools' => home_url('/jobs/employer/schools/'),
          'archived' => home_url('/jobs/employer/my-jobs/?status=archived'),
          'browse_jobs' => home_url('/jobs/'),
          'saved_jobs' => home_url('/jobs/'),
          'job_alerts' => home_url('/jobs/'),
          'new_topic' => home_url('/chatboards/'),
          'profile' => home_url('/profile/'),
          'logout' => wp_logout_url(home_url('/')),
          'login' => $login_url,
          'signup' => self::signup_url(),
          'dashboard' => home_url('/jobs/'),
          'wizard' => home_url('/jobs/employer/new/'),
          'chatboards' => home_url('/chatboards/'),
        ],
        'taxonomy' => [
          'lesson_grade_levels' => [],
          'lesson_subject_areas' => [],
          'chatboard_grade_levels' => [],
        ],
        'footer_links' => [
          ['About', home_url('/info/about/')],
          ['Mission', home_url('/info/mission/')],
          ['Contacts', home_url('/info/contacts/')],
          ['Terms', home_url('/info/policies/')],
          ['Privacy', home_url('/info/privacy/')],
        ],
        'content' => static function () use ($content, $right, $identity_journey, $suppress_search, $logged_in, $current_user) {
          $rail_families = self::launch_rail_families($logged_in && $current_user ? (int) $current_user->ID : 0);
          TNet_Shared_Shell::render_community_frame([
            'navigation' => [
              'home_url' => home_url('/'),
              'jobs_url' => home_url('/jobs/'),
              'lessons_url' => home_url('/lessons/'),
              'chatboards_url' => home_url('/chatboards/'),
              'help_url' => home_url('/info/help/'),
              'show_help' => false,
              'settings_url' => home_url('/account/'),
              'generic_join' => true,
              'focused_identity_journey' => $identity_journey,
              'families' => $rail_families,
            ],
            'main' => static function () use ($content, $identity_journey, $suppress_search) {
              if (!$identity_journey && !$suppress_search) TNet_Shared_Shell::render_community_search(home_url('/'));
              echo '<section class="c3-community-page tnet-identity-join-page">';
              call_user_func($content);
              echo '</section>';
            },
            'right' => $right,
            'focused_identity_journey' => $identity_journey,
            'reserve_account_actions' => true,
          ]);
        },
      ]);
      exit;
    }
    self::legacy_page($title, $content);
  }

  private static function legacy_page($title, callable $content) {
    ?><!doctype html><html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?php echo esc_html($title . ' | Teachers.Net'); ?></title><?php wp_head(); ?><style>
      .tnet-identity-page{min-height:100vh;background:#f5f7fb;color:#172b4d;font:16px/1.5 system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;padding:clamp(24px,7vw,72px) 16px;box-sizing:border-box}.tnet-identity-page *{box-sizing:border-box}.tnet-identity-card{width:min(100%,560px);margin:0 auto;background:#fff;border:1px solid #dbe4f0;border-radius:16px;padding:clamp(24px,5vw,40px);box-shadow:0 10px 30px rgba(18,40,78,.08)}.tnet-identity-kicker{margin:0 0 8px;color:#526a8e;font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.tnet-identity-card h1{margin:0 0 12px;color:#122875;font-size:clamp(28px,5vw,38px);line-height:1.1}.tnet-identity-card>p{color:#526a8e}.tnet-identity-form{display:grid;gap:18px;margin-top:28px}.tnet-identity-form label{display:grid;gap:7px;color:#172b4d;font-weight:650}.tnet-identity-form input{width:100%;border:1px solid #b9c8dc;border-radius:8px;padding:11px 12px;font:inherit;color:#172b4d}.tnet-identity-form small{color:#526a8e;font-weight:400}.tnet-identity-form button,.tnet-identity-resend button{width:max-content;border:1px solid #123bc6;border-radius:8px;padding:11px 18px;background:#123bc6;color:#fff;font:inherit;font-weight:700;cursor:pointer}.tnet-identity-privacy{border-left:4px solid #f0b429;background:#fff8e1;padding:14px 16px;color:#40536f;font-size:14px}.tnet-identity-privacy strong{color:#172b4d}.tnet-identity-privacy p{margin:8px 0 0}.tnet-identity-errors{margin:18px 0;padding:12px 14px;border-radius:8px;background:#fff0ef;color:#8a1f11}.tnet-identity-errors p{margin:0}.tnet-identity-success{padding:12px 14px;border-radius:8px;background:#edf8f0;color:#17633a}.tnet-identity-resend{margin-top:24px}.tnet-identity-page a{color:#123bc6}
    </style></head><body class="tnet-identity-page"><?php $content(); ?><?php wp_footer(); ?></body></html><?php exit;
  }

  private static function asset_version($relative_path) {
    $path = TNET_IDENTITY_PLUGIN_DIR . ltrim($relative_path, '/');
    return is_readable($path) ? TNET_IDENTITY_VERSION . '-' . filemtime($path) : TNET_IDENTITY_VERSION;
  }

  private static function errors(array $errors) {
    if (!$errors) return;
    echo '<div class="tnet-identity-errors" role="alert">';
    foreach ($errors as $error) echo '<p>' . esc_html($error->get_error_message()) . '</p>';
    echo '</div>';
  }
}
