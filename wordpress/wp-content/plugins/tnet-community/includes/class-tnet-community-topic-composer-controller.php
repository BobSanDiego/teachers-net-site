<?php
defined('ABSPATH') || exit;

final class TNet_Community_Topic_Composer_Controller {
    private const COMMUNITIES = ['community:local-demo' => 'Local Community Demo'];

    public static function register(): void {
        if (!self::local()) return;
        add_rewrite_rule('^community/new/?$', 'index.php?tnet_community_topic_composer=1', 'top');
        add_filter('query_vars', static function (array $vars): array { $vars[] = 'tnet_community_topic_composer'; return $vars; });
        add_action('template_redirect', [self::class, 'render']);
    }

    private static function local(): bool { return defined('DDEV_PROJECT') || (bool) getenv('DDEV_PROJECT'); }

    public static function render(): void {
        if (!get_query_var('tnet_community_topic_composer')) return;
        if (!is_user_logged_in() || !current_user_can('read')) {
            auth_redirect();
        }
        if ('POST' === strtoupper($_SERVER['REQUEST_METHOD'] ?? '')) self::submit();
        self::form([]);
        exit;
    }

    private static function submit(): void {
        if (!isset($_POST['tnet_topic_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tnet_topic_nonce'])), 'tnet_community_topic')) {
            self::form(['Nonce verification failed. Please try again.']);
        }
        $community = sanitize_text_field(wp_unslash($_POST['community_id'] ?? ''));
        $submission_id = sanitize_text_field(wp_unslash($_POST['submission_id'] ?? ''));
        if ($submission_id === '') $submission_id = 'web-' . wp_generate_uuid4();
        $title = sanitize_text_field(wp_unslash($_POST['title'] ?? ''));
        $body = sanitize_textarea_field(wp_unslash($_POST['body'] ?? ''));
        $errors = [];
        if (!isset(self::COMMUNITIES[$community])) $errors[] = 'Choose a valid Community.';
        if ($title === '') $errors[] = 'Enter a topic title.';
        if ($body === '') $errors[] = 'Enter the topic body.';
        if ($errors) self::form($errors, ['community_id' => $community, 'submission_id' => $submission_id, 'title' => $title, 'body' => $body]);

        $draft = [
            'submission_id' => $submission_id,
            'community_id' => $community,
            'author_id' => 'user:' . (int) get_current_user_id(),
            'post_type' => 'topic', 'title' => $title, 'body' => $body,
            'parent_post_id' => null, 'visibility' => 'public',
            'publication_mode' => 'post_first', 'moderation_input' => 'clear',
            'compatibility_refs' => [], 'audit_context' => ['source' => 'local-topic-composer'],
        ];
        $result = (new TNet_Community_Publisher_Application())->publish_and_persist($draft, self::COMMUNITIES, ['actor_id' => $draft['author_id']]);
        if (empty($result['accepted']) || empty($result['post']) || empty($result['post']['post_id'])) {
            self::form(['The topic could not be published. Please try again.'], ['community_id' => $community, 'submission_id' => $submission_id, 'title' => $title, 'body' => $body]);
        }
        $thread_path = str_replace('%3A', ':', rawurlencode($result['post']['post_id']));
        wp_safe_redirect(home_url('/community/thread/' . $thread_path . '/'));
        exit;
    }

    private static function form(array $errors, array $values = []): void {
        status_header(200); nocache_headers(); header('X-Robots-Tag: noindex, nofollow');
        $community = $values['community_id'] ?? 'community:local-demo';
        $submission_id = $values['submission_id'] ?? 'web-' . wp_generate_uuid4();
        $title = $values['title'] ?? ''; $body = $values['body'] ?? '';
        echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Start a Discussion | Teachers.Net</title><style>body{font-family:system-ui,sans-serif;max-width:760px;margin:2rem auto;padding:0 1rem;line-height:1.5;color:#1d2327}main{border:1px solid #ccd0d4;border-radius:8px;padding:clamp(1rem,4vw,2rem)}label{display:block;font-weight:600;margin:.9rem 0 .35rem}input,select,textarea{box-sizing:border-box;width:100%;border:1px solid #8c8f94;border-radius:4px;padding:.65rem;font:inherit}textarea{min-height:12rem;resize:vertical}.actions{display:flex;gap:1rem;align-items:center;margin-top:1.25rem}button{background:#135e96;color:#fff;border:0;border-radius:4px;padding:.7rem 1rem;font:inherit;font-weight:600;cursor:pointer}.back{color:#135e96}.errors{border-left:4px solid #b32d2e;background:#fcf0f1;padding:.75rem 1rem}.errors li+li{margin-top:.3rem}</style></head><body><main><p><a class="back" href="'.esc_url(home_url('/community/')).'">← Community</a></p><h1>Start a Discussion</h1><p>Share a question or idea with the Community.</p>';
        if ($errors) { echo '<div class="errors" role="alert"><ul>'; foreach ($errors as $error) echo '<li>'.esc_html($error).'</li>'; echo '</ul></div>'; }
        echo '<form method="post">'.wp_nonce_field('tnet_community_topic','tnet_topic_nonce',true,false).'<input type="hidden" name="submission_id" value="'.esc_attr($submission_id).'" /><label for="community_id">Community</label><select id="community_id" name="community_id">'; foreach (self::COMMUNITIES as $id => $label) echo '<option value="'.esc_attr($id).'"'.selected($community,$id,false).'>'.esc_html($label).'</option>'; echo '</select><label for="title">Title</label><input id="title" name="title" type="text" value="'.esc_attr($title).'" required><label for="body">Body</label><textarea id="body" name="body" required>'.esc_textarea($body).'</textarea><div class="actions"><button type="submit">Publish Topic</button><a class="back" href="'.esc_url(home_url('/community/')).'">Cancel</a></div></form></main></body></html>';
    }
}
