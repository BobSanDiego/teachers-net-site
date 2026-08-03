<?php
defined('ABSPATH') || exit;

final class TNet_Community_Thread_Controller {
    public static function register(): void {
        if (!self::local()) return;
        add_rewrite_rule('^community/thread/([^/]+)/?$', 'index.php?tnet_community_thread=$matches[1]', 'top');
        add_filter('query_vars', static function (array $vars): array { $vars[] = 'tnet_community_thread'; return $vars; });
        add_action('template_redirect', [self::class, 'render']);
    }

    private static function local(): bool { return defined('DDEV_PROJECT') || (bool) getenv('DDEV_PROJECT'); }

    public static function render(): void {
        $id = get_query_var('tnet_community_thread');
        if (!$id) return;
        $post_id = urldecode(sanitize_text_field($id));
        $data = (new TNet_Community_Thread_View())->find($post_id, current_user_can('manage_options'));
        if (!$data) { status_header(404); self::shell('<main><h1>Thread not found</h1><p>This local Community thread is unavailable.</p></main>'); }
        $errors = [];
        if ('POST' === strtoupper($_SERVER['REQUEST_METHOD'] ?? '')) {
            $errors = self::submit($data);
            if (!$errors) exit;
        }
        status_header(200); nocache_headers(); header('X-Robots-Tag: noindex, nofollow');
        $root = $data['root'];
        $html = '<main><p class="meta">Local Community thread</p><h1>' . esc_html($root['title']) . '</h1><article class="thread-card"><p class="meta">' . esc_html($root['_author_display'] . ' · ' . $root['created_at']) . '</p><div>' . TNet_Community_Authoring::markdown($root['body']) . '</div>' . self::attachments($root) . '</article>';
        $html .= self::reply_form_normalized($root, $data['rows'], $errors);
        $html .= '<section aria-labelledby="replies"><h2 id="replies">Replies</h2>';
        $reply_count = 0;
        foreach ($data['rows'] as $row) {
            if ($row['post_id'] === $root['post_id']) continue;
            $reply_count++;
            $class = 'reply reply-level-' . (int)$row['_level'];
            $html .= '<article id="reply-post:' . esc_attr($row['post_id']) . '" class="' . esc_attr($class) . '" aria-label="' . esc_attr($row['_level'] === 1 ? 'Level 1 reply' : 'Level 2 reply') . '"><p class="meta">' . esc_html($row['_author_display'] . ' · ' . $row['created_at']) . '</p>';
            if (!empty($row['_target_display'])) {
                $target = $row['_target_display'];
                $html .= '<p class="reply-target"><span class="screen-reader-text">Reply target: </span>';
                $html .= $target['post_id'] ? '<a href="#reply-post:' . esc_attr($target['post_id']) . '">' . esc_html($target['label']) . '</a>' : esc_html($target['label']);
                $html .= '</p>';
            }
            $html .= '<div>' . TNet_Community_Authoring::markdown($row['body']) . '</div>' . self::attachments($row);
            if (is_user_logged_in()) $html .= '<p><a href="#reply-composer" data-reply-target="' . esc_attr($row['post_id']) . '">Reply to this ' . esc_html($row['_level'] === 1 ? 'comment' : 'reply') . '</a></p>';
            $html .= '</article>';
        }
        if (!$reply_count) $html .= '<p>No replies yet.</p>';
        $html .= '</section><p class="meta">Reply composition is available to authenticated local users.</p></main>';
        self::shell($html);
    }

    private static function submit(array $data): array {
        if (!is_user_logged_in() || !current_user_can('read')) { auth_redirect(); }
        if (!isset($_POST['tnet_reply_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tnet_reply_nonce'])), 'tnet_community_reply')) return ['Nonce verification failed. Please try again.'];
        $parent_id = sanitize_text_field(wp_unslash($_POST['parent_post_id'] ?? ''));
        $body = sanitize_textarea_field(wp_unslash($_POST['body'] ?? ''));
        $submission_id = sanitize_text_field(wp_unslash($_POST['submission_id'] ?? '')) ?: 'reply-' . wp_generate_uuid4();
        if ($body === '') return ['Enter a reply.'];
        $parent = (new TNet_Community_Publisher_Repository())->find_post($parent_id);
        if (!$parent || $parent['thread_id'] !== $data['thread_id'] || $parent['community_id'] !== $data['root']['community_id']) return ['That reply target is not available in this thread.'];
        $draft = ['submission_id' => $submission_id, 'community_id' => $data['root']['community_id'], 'author_id' => 'user:' . (int) get_current_user_id(), 'post_type' => 'reply', 'title' => '', 'body' => $body, 'parent_post_id' => $parent_id, 'thread_id' => $data['thread_id'], 'visibility' => 'public', 'publication_mode' => 'post_first', 'moderation_input' => 'clear', 'compatibility_refs' => [], 'audit_context' => ['source' => 'local-reply-composer']];
        $result = (new TNet_Community_Publisher_Application())->publish_reply($draft, $parent, [$draft['community_id'] => ['active' => true]], ['actor_id' => $draft['author_id']]);
        if (empty($result['accepted']) || empty($result['post']['post_id'])) return ['The reply could not be published. Please try again.'];
        $reply_path = str_replace('%3A', ':', rawurlencode($result['post']['post_id']));
        $root_path = str_replace('%3A', ':', rawurlencode($data['root']['post_id']));
        wp_safe_redirect(home_url('/community/thread/' . $root_path . '/#reply-post:' . $reply_path));
        return [];
    }

    private static function reply_form_normalized(array $root, array $rows, array $errors): string {
        if (!is_user_logged_in()) { $url = home_url('/community/thread/' . str_replace('%3A', ':', rawurlencode($root['post_id'])) . '/'); return '<p class="meta"><a href="' . esc_url(wp_login_url($url)) . '">Log in to reply.</a></p>'; }
        $key = 'reply-' . wp_generate_uuid4(); $html = '';
        if ($errors) { $html .= '<div class="errors" role="alert"><ul>'; foreach ($errors as $error) $html .= '<li>' . esc_html($error) . '</li>'; $html .= '</ul></div>'; }
        $nonce = wp_nonce_field('tnet_community_reply', 'tnet_reply_nonce', true, false);
        $submission = '<input type="hidden" name="submission_id" value="' . esc_attr($key) . '">';
        $target = '<input type="hidden" id="reply-target" name="parent_post_id" value="' . esc_attr($root['post_id']) . '">';
        $context = '<p id="reply-context"><strong>Reply</strong></p>';
        $body = '<label for="reply-body">Your reply</label><textarea id="reply-body" name="body" rows="5" required></textarea>';
        $help = '<details><summary>Formatting help</summary><p>Use **bold**, *italic*, \`code\`, quotes, lists, or [links](https://example.com).</p></details>';
        $script = '<script>document.querySelectorAll("[data-reply-target]").forEach(function(a){a.addEventListener("click",function(){document.getElementById("reply-target").value=a.dataset.replyTarget;document.getElementById("reply-context").textContent="Replying to "+a.textContent;document.getElementById("reply-body").focus()})});</script>';
        return $html . '<form id="reply-composer" class="reply-composer" method="post">' . $nonce . $submission . $target . $context . $body . $help . '<button type="submit">Post Reply</button></form>' . $script;
    }

    private static function reply_form(array $root, array $rows, array $errors): string {
        if (!is_user_logged_in()) return '<p class="meta"><a href="' . esc_url(wp_login_url(home_url('/community/thread/' . str_replace('%3A', ':', rawurlencode($root['post_id'])) . '/'))) . '">Log in to reply.</a></p>';
        $key = 'reply-' . wp_generate_uuid4();
        $html = '';
        if ($errors) { $html .= '<div class="errors" role="alert"><ul>'; foreach ($errors as $error) $html .= '<li>' . esc_html($error) . '</li>'; $html .= '</ul></div>'; }
        return $html . '<form id="reply-composer" class="reply-composer" method="post">' . wp_nonce_field('tnet_community_reply', 'tnet_reply_nonce', true, false) . '<input type="hidden" name="submission_id" value="' . esc_attr($key) . '"><input type="hidden" id="reply-target" name="parent_post_id" value="'.esc_attr($root['post_id']).'"><p id="reply-context"><strong>Reply</strong></p><label for="reply-body">Your reply</label><textarea id="reply-body" name="body" rows="5" required></textarea><details><summary>Formatting help</summary><p>Use **bold**, *italic*, `code`, quotes, lists, or [links](https://example.com).</p></details><button type="submit">Post Reply</button></form><script>document.querySelectorAll("[data-reply-target]").forEach(function(a){a.addEventListener("click",function(){document.getElementById("reply-target").value=a.dataset.replyTarget;document.getElementById("reply-context").textContent="Replying to "+a.textContent;document.getElementById("reply-body").focus()})});</script>';
    }
    private static function attachments(array $row): string { if (!empty($row['attachments']) && in_array($row['publication_state'], ['published','restored'], true)) { $html=''; foreach($row['attachments'] as $attachment) $html.=TNet_Community_Attachment::render((array)$attachment); return $html; } return ''; }

    private static function shell(string $body): void {
        echo '<!doctype html><html><head><meta charset="utf-8"><meta name="robots" content="noindex,nofollow"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Community Thread</title><style>body{font-family:system-ui,sans-serif;max-width:760px;margin:2rem auto;padding:0 1rem;line-height:1.55;color:#1d2327}.thread-card,.reply{border:1px solid #ccd0d4;border-radius:6px;padding:1rem;margin:1rem 0}.reply{margin-left:min(3rem,8vw);background:#f6f7f7}.tombstone{color:#646970;font-style:italic}.meta{color:#646970;font-size:.9rem}h1{line-height:1.2}.reply-composer{background:#fff;border:1px solid #ccd0d4;border-radius:6px;padding:1rem;margin:1rem 0}.reply-composer label{display:block;font-weight:600;margin:.5rem 0 .3rem}.reply-composer textarea{box-sizing:border-box;width:100%;min-height:6rem;padding:.6rem;font:inherit;resize:vertical}.reply-composer button{background:#135e96;color:#fff;border:0;border-radius:4px;padding:.6rem .9rem;font:inherit;font-weight:600;margin-top:.7rem}.attachment-card{border:1px solid #ccd0d4;border-radius:6px;padding:1rem;margin-top:1rem;background:#fff}.attachment-image div{min-height:8rem;background:#e5e7eb;display:grid;place-items:center}.attachment-card figcaption span{display:block;color:#646970}.attachment-fallback{color:#646970}.errors{border-left:4px solid #b32d2e;background:#fcf0f1;padding:.5rem 1rem}</style></head><body>' . $body . '</body></html>'; exit;
    }
}
