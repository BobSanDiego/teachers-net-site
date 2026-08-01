<?php
defined('ABSPATH') || exit;
final class TNet_Community_Landing_Controller {
    public static function register(): void {
        if (!self::local()) return;
        add_rewrite_rule('^community/?$', 'index.php?tnet_community_landing=1', 'top');
        add_filter('query_vars', static function (array $vars): array { $vars[] = 'tnet_community_landing'; return $vars; });
        add_action('template_redirect', [self::class, 'render']);
    }
    private static function local(): bool { return defined('DDEV_PROJECT') || (bool) getenv('DDEV_PROJECT'); }
    public static function render(): void {
        if (!get_query_var('tnet_community_landing')) return;
        $rows = (new TNet_Community_Landing_View())->latest();
        status_header(200);
        nocache_headers();
        header('X-Robots-Tag: noindex, nofollow');
        $thread_base = home_url('/community/thread/');
        echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="robots" content="noindex,nofollow"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Community | Teachers.Net</title><style>body{font-family:system-ui,sans-serif;max-width:860px;margin:2rem auto;padding:0 1rem;line-height:1.5;color:#1d2327}.community-header{border-bottom:1px solid #ccd0d4;margin-bottom:1.5rem;padding-bottom:1rem}.community-header h1{margin:0 0 .25rem}.community-header p,.discussion-meta,.empty-state{color:#646970}.discussion-list{display:grid;gap:1rem}.discussion{border:1px solid #ccd0d4;border-radius:8px;padding:1rem}.discussion h2{font-size:1.15rem;margin:0 0 .4rem}.discussion h2 a{color:#135e96}.discussion-meta{font-size:.9rem;margin:0}.start-discussion{background:#f6f7f7;border:1px solid #ccd0d4;border-radius:6px;color:#646970;cursor:not-allowed;padding:.65rem 1rem}</style></head><body><main><header class="community-header"><p>Teachers.Net Community</p><h1>Community</h1><p>Join the latest conversations from teachers and education professionals.</p><button class="start-discussion" type="button" disabled>Start Discussion · Coming next</button></header><section aria-labelledby="latest-discussions"><h2 id="latest-discussions">Latest Discussions</h2>';
        if (!$rows) { echo '<p class="empty-state">There are no discussions to show yet. Check back soon.</p>'; }
        else { echo '<div class="discussion-list">'; foreach ($rows as $row) { $url = trailingslashit($thread_base . $row['post_id']); echo '<article class="discussion"><h2><a href="' . esc_url($url) . '">' . esc_html($row['title']) . '</a></h2><p class="discussion-meta">Started by ' . esc_html($row['author_display']) . ' · ' . esc_html(number_format_i18n($row['reply_count'])) . ' ' . esc_html(_n('reply', 'replies', $row['reply_count'], 'tnet-community')) . ' · Last activity ' . esc_html($row['last_activity']) . '</p></article>'; } echo '</div>'; }
        echo '</section></main></body></html>'; exit;
    }
}
