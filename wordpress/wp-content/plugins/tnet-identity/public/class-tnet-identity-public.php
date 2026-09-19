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

  private static function next_onboarding_url($user_id) {
    if (TNet_Identity_Service::needs_public_identity($user_id)) return self::public_identity_url();
    if (TNet_Identity_Service::needs_location($user_id)) return self::location_url();
    return home_url('/jobs/');
  }

  public static function render_signup() {
    $errors = [];
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
        } else {
          if ($continuation) TNet_Identity_Continuation_Service::attach_user($continuation, $result['user_id']);
          wp_safe_redirect(add_query_arg(['created' => '1', 'email' => $values['email']], home_url('/account/verify/')));
          exit;
        }
      }
    }
    self::page(__('Create your Teachers.Net account', 'tnet-identity'), function () use ($errors, $values, $continuation) {
      ?>
        <section class="tnet-identity-card" aria-labelledby="tnet-identity-join-title">
          <h1 id="tnet-identity-join-title"><span><?php echo esc_html__('Create your', 'tnet-identity'); ?></span><br><span><?php echo esc_html__('Teachers.Net account', 'tnet-identity'); ?></span></h1>
          <p class="tnet-identity-intro"><?php echo esc_html__('It only takes a moment.', 'tnet-identity'); ?></p>
          <?php self::errors($errors); ?>
          <form method="post" class="tnet-identity-form">
            <input type="hidden" name="continuation" value="<?php echo esc_attr($continuation); ?>">
            <?php wp_nonce_field('tnet_identity_signup'); ?>
            <label for="tnet-identity-email">Email
              <input id="tnet-identity-email" name="email" type="email" value="<?php echo esc_attr($values['email']); ?>" autocomplete="email" required>
            </label>
            <label for="tnet-identity-username">Username
              <span class="tnet-identity-username-guidance"><strong><?php echo esc_html__('Your username is permanent.', 'tnet-identity'); ?></strong> <?php echo esc_html__('Avoid your full name, email, school, location, or birth year.', 'tnet-identity'); ?> <button type="button" class="tnet-identity-username-why" data-tnet-identity-username-why aria-expanded="false" aria-controls="tnet-identity-username-explanation"><?php echo esc_html__('Why?', 'tnet-identity'); ?></button></span>
              <input id="tnet-identity-username" name="username" type="text" value="<?php echo esc_attr($values['username']); ?>" minlength="3" maxlength="30" pattern="[A-Za-z0-9._\-]{3,30}" autocomplete="username" required>
              <small><?php echo esc_html__('3–30 characters · Letters, numbers, periods, underscores or hyphens.', 'tnet-identity'); ?></small>
              <span id="tnet-identity-username-explanation" class="tnet-identity-username-explanation" hidden><?php echo esc_html__("Your username may be visible to others. You'll choose a separate display name that you can change anytime.", 'tnet-identity'); ?></span>
            </label>
            <label for="tnet-identity-password">Password
              <small><?php echo esc_html__('Use at least 8 characters.', 'tnet-identity'); ?></small>
              <span class="tnet-identity-password-control">
                <input id="tnet-identity-password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                <button type="button" class="tnet-identity-password-toggle" data-tnet-identity-password-toggle aria-controls="tnet-identity-password" aria-pressed="false">Show</button>
              </span>
            </label>
            <button class="tnet-identity-submit" type="submit"><span><?php echo esc_html__('Create account', 'tnet-identity'); ?></span><span class="tnet-identity-submit-arrow" aria-hidden="true">→</span></button>
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
    $errors = [];
    $saved = isset($_GET['avatar_saved']) && sanitize_key((string) wp_unslash($_GET['avatar_saved'])) === '1';
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
          wp_safe_redirect(add_query_arg('avatar_saved', '1', home_url('/account/avatar/')));
          exit;
        }
      }
    }
    $entries = TNet_Profile_Avatar::portrait_bank_entries();
    $client_entries = [];
    foreach ($entries as $entry) $client_entries[] = ['id' => $entry['portrait_id'], 'generation' => $entry['generation_retrieval_bucket'], 'presentation' => $entry['presentation_retrieval_bucket'], 'url' => $entry['url']];
    $avatar = TNet_Profile_Avatar::resolve_avatar($user_id, 160);
    self::page(__('Add a profile photo', 'tnet-identity'), function () use ($errors, $saved, $avatar, $client_entries, $generation, $presentation, $user_id) {
      ?>
      <main class="tnet-identity-card tnet-identity-card--avatar" aria-labelledby="tnet-identity-avatar-title">
        <?php if ($saved) : ?>
          <p class="tnet-identity-kicker">Profile ready</p>
          <h1 id="tnet-identity-avatar-title">Your profile photo is set</h1>
          <p class="tnet-identity-intro">You can change it anytime from your Profile.</p>
          <img class="tnet-identity-avatar-confirmation" src="<?php echo esc_url($avatar['url']); ?>" width="160" height="160" alt="Your selected profile avatar">
          <?php $next_url = self::next_onboarding_url($user_id); ?>
          <a class="tnet-identity-submit tnet-identity-submit--link" href="<?php echo esc_url($next_url); ?>"><span>Continue to Teachers.Net</span><span class="tnet-identity-submit-arrow" aria-hidden="true">→</span></a>
        <?php else : ?>
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
        <?php endif; ?>
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
    if (!TNet_Identity_Service::needs_public_identity($user_id)) {
      wp_safe_redirect(self::next_onboarding_url($user_id));
      exit;
    }
    $user = wp_get_current_user();
    $error = null;
    $value = (string) $user->user_login;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'tnet_identity_public_identity_step')) {
        $error = new WP_Error('tnet_identity_nonce', __('Security check failed. Please try again.', 'tnet-identity'));
      } else {
        $value = wp_unslash($_POST['display_name'] ?? '');
        $result = TNet_Identity_Service::update_display_name($user_id, $value);
        if (is_wp_error($result)) {
          $error = $result;
        } else {
          TNet_Identity_Service::complete_public_identity($user_id);
          wp_safe_redirect(self::next_onboarding_url($user_id));
          exit;
        }
      }
    }
    $avatar = class_exists('TNet_Profile_Avatar') ? TNet_Profile_Avatar::resolve_avatar($user_id, 112) : ['url' => get_avatar_url($user_id, ['size' => 112])];
    $profile_url = get_author_posts_url($user_id);
    self::page(__('What should we call you?', 'tnet-identity'), function () use ($user, $value, $error, $avatar, $profile_url) {
      $error_message = is_wp_error($error) ? $error->get_error_message() : '';
      ?>
      <main class="tnet-identity-card tnet-identity-card--public-identity" aria-labelledby="tnet-identity-public-identity-title">
        <a class="tnet-identity-member-cluster" href="<?php echo esc_url($profile_url); ?>" aria-label="<?php echo esc_attr(sprintf(__('Open %s’s member profile', 'tnet-identity'), $user->user_login)); ?>">
          <img src="<?php echo esc_url($avatar['url']); ?>" width="112" height="112" alt="<?php echo esc_attr(sprintf(__('%s’s selected avatar', 'tnet-identity'), $user->user_login)); ?>">
          <span class="tnet-identity-member-handle">@<?php echo esc_html($user->user_login); ?></span>
          <span class="tnet-identity-member-note"><?php echo esc_html__('Your permanent username', 'tnet-identity'); ?></span>
        </a>
        <div class="tnet-identity-public-identity-content">
          <h1 id="tnet-identity-public-identity-title"><?php echo esc_html__('What should we call you?', 'tnet-identity'); ?></h1>
          <p class="tnet-identity-intro"><?php echo esc_html__('This is the name other members will usually see.', 'tnet-identity'); ?><br><?php echo esc_html__('You can change it anytime.', 'tnet-identity'); ?></p>
          <form method="post" class="tnet-identity-public-identity-form" data-tnet-display-name-form>
            <?php wp_nonce_field('tnet_identity_public_identity_step'); ?>
            <label for="tnet-identity-display-name"><?php echo esc_html__('Display name', 'tnet-identity'); ?></label>
            <input id="tnet-identity-display-name" name="display_name" type="text" value="<?php echo esc_attr($value); ?>" maxlength="50" minlength="2" autocomplete="name" required aria-describedby="tnet-identity-display-name-help tnet-identity-display-name-status" aria-invalid="<?php echo $error_message ? 'true' : 'false'; ?>" data-tnet-display-name-input data-tnet-display-name-server-error="<?php echo esc_attr($error_message); ?>">
            <p id="tnet-identity-display-name-status" class="tnet-identity-display-name-status<?php echo $error_message ? ' is-error' : ''; ?>" role="status" aria-live="polite" data-tnet-display-name-status><?php echo esc_html($error_message); ?></p>
            <small id="tnet-identity-display-name-help"><?php echo esc_html__('Use letters, numbers, spaces, and basic punctuation.', 'tnet-identity'); ?></small>
            <button class="tnet-identity-submit tnet-identity-public-identity-submit" type="submit" data-tnet-display-name-submit><span><?php echo esc_html__('Continue', 'tnet-identity'); ?></span><span class="tnet-identity-submit-arrow" aria-hidden="true">→</span></button>
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
    if (!TNet_Identity_Service::needs_location($user_id)) {
      wp_safe_redirect(home_url('/jobs/'));
      exit;
    }
    $error = null;
    $mode = sanitize_key((string) wp_unslash($_REQUEST['location_mode'] ?? 'us'));
    if (!in_array($mode, ['us', 'international'], true)) $mode = 'us';
    $state = sanitize_text_field(wp_unslash($_REQUEST['region_code'] ?? ''));
    $country = sanitize_text_field(wp_unslash($_REQUEST['country_code'] ?? ''));
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'tnet_identity_location_step')) {
        $error = new WP_Error('tnet_identity_nonce', __('Security check failed. Please try again.', 'tnet-identity'));
      } elseif (!empty($_POST['skip_location'])) {
        TNet_Identity_Service::complete_location($user_id);
        wp_safe_redirect(home_url('/jobs/'));
        exit;
      } else {
        $selection = $mode === 'us' ? $state : $country;
        $result = TNet_Identity_Service::save_location($user_id, $mode, $selection);
        if (is_wp_error($result)) {
          $error = $result;
        } else {
          TNet_Identity_Service::complete_location($user_id);
          wp_safe_redirect(home_url('/jobs/'));
          exit;
        }
      }
    }
    self::page(__('Where are you located?', 'tnet-identity'), function () use ($error, $mode, $state, $country) {
      $error_message = is_wp_error($error) ? $error->get_error_message() : '';
      $states = TNet_Identity_Location_Policy::us_regions();
      $countries = TNet_Identity_Location_Policy::country_codes();
      ?>
      <main class="tnet-identity-card tnet-identity-card--location" aria-labelledby="tnet-identity-location-title">
        <div class="tnet-identity-location-visual" aria-hidden="true"><svg viewBox="0 0 160 88" focusable="false"><path d="M16 31l18-12 23 4 13-9 20 6 22-3 14 13-4 15-19 4-7 15-19-4-11 9-18-8-18 1-9-13-17-3z"></path><circle cx="115" cy="55" r="17"></circle><path d="M115 38v34M98 55h34M103 43c7 7 17 7 24 0M103 67c7-7 17-7 24 0"></path></svg></div>
        <h1 id="tnet-identity-location-title">Where are you located?</h1>
        <p class="tnet-identity-intro">Help us show you more relevant resources, discussions, and opportunities.</p>
        <?php if ($error_message) : ?><div class="tnet-identity-errors" role="alert"><p><?php echo esc_html($error_message); ?></p></div><?php endif; ?>
        <form method="post" class="tnet-identity-location-form" data-tnet-location-form>
          <?php wp_nonce_field('tnet_identity_location_step'); ?>
          <fieldset class="tnet-identity-location-choices"><legend class="screen-reader-text">Choose a location type</legend>
            <label class="tnet-identity-location-choice"><input type="radio" name="location_mode" value="us" <?php checked($mode, 'us'); ?> data-tnet-location-mode="us"><span class="tnet-identity-location-choice-icon" aria-hidden="true">●</span><span><strong>United States</strong><small>Select your state to get more relevant content for your area.</small></span></label>
            <section class="tnet-identity-location-selector" data-tnet-location-panel="us"<?php if ($mode !== 'us') echo ' hidden'; ?>>
              <label for="tnet-identity-region-code">State</label>
              <select id="tnet-identity-region-code" name="region_code" autocomplete="address-level1" data-tnet-location-state<?php disabled($mode, 'international'); ?>>
                <option value="">Select your state</option>
                <?php foreach ($states as $code => $name) : ?><option value="<?php echo esc_attr($code); ?>" <?php selected($state, $code); ?>><?php echo esc_html($name . ' (' . $code . ')'); ?></option><?php endforeach; ?>
              </select>
            </section>
            <label class="tnet-identity-location-choice"><input type="radio" name="location_mode" value="international" <?php checked($mode, 'international'); ?> data-tnet-location-mode="international"><span class="tnet-identity-location-choice-icon" aria-hidden="true">●</span><span><strong>Outside the U.S.?</strong><small>Choose your country instead.</small></span></label>
            <section class="tnet-identity-location-selector" data-tnet-location-panel="international"<?php if ($mode !== 'international') echo ' hidden'; ?>>
              <label for="tnet-identity-country-code">Country</label>
              <select id="tnet-identity-country-code" name="country_code" autocomplete="country" data-tnet-location-country<?php disabled($mode, 'us'); ?>>
                <option value="">Select your country</option>
                <?php foreach ($countries as $code) : ?><option value="<?php echo esc_attr($code); ?>" <?php selected($country, $code); ?>><?php echo esc_html($code); ?></option><?php endforeach; ?>
              </select>
              <button class="tnet-identity-location-return" type="button" data-tnet-location-return>In the U.S.? Choose your state</button>
            </section>
          </fieldset>
          <button class="tnet-identity-submit tnet-identity-location-submit" type="submit" data-tnet-location-submit<?php disabled($mode === 'us' ? $state === '' : $country === ''); ?>><span>Continue</span><span class="tnet-identity-submit-arrow" aria-hidden="true">→</span></button>
          <button class="tnet-identity-location-skip" type="submit" name="skip_location" value="1">Skip for now</button>
        </form>
      </main>
      <?php
    }, true, null, true);
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

  private static function page($title, callable $content, $use_shell = true, $right = null, $identity_journey = false) {
    status_header(200);
    if ($use_shell && class_exists('TNet_Shared_Shell')) {
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
        'presentation' => 'flush',
        'document_title' => $title . ' | Teachers.Net',
        'route_class' => 'identity-join',
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
        'content' => static function () use ($content, $right, $identity_journey) {
          TNet_Shared_Shell::render_community_frame([
            'navigation' => [
              'home_url' => home_url('/'),
              'jobs_url' => home_url('/jobs/'),
              'lessons_url' => home_url('/lessons/'),
              'chatboards_url' => home_url('/chatboards/'),
              'help_url' => home_url('/info/help/'),
              'settings_url' => home_url('/account/'),
              'generic_join' => true,
              'focused_identity_journey' => $identity_journey,
              'families' => [
                ['name' => 'Hot Topics', 'icon' => 'flame'],
                ['name' => 'Grade Levels', 'icon' => 'graduate'],
                ['name' => 'Subject Areas', 'icon' => 'book'],
                ['name' => 'States', 'icon' => 'pin'],
              ],
            ],
            'main' => static function () use ($content, $identity_journey) {
              if (!$identity_journey) TNet_Shared_Shell::render_community_search(home_url('/'));
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
