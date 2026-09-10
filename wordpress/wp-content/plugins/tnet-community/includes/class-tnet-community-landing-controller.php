<?php
defined('ABSPATH') || exit;

final class TNet_Community_Landing_Controller {
    public static function register(): void {
        if (!self::local()) return;
        add_rewrite_rule('^community/?$', 'index.php?tnet_community_landing=1', 'top');
        add_rewrite_rule('^community/([a-z0-9-]+)/?$', 'index.php?tnet_community_landing=1&tnet_community_community=$matches[1]', 'top');
        add_filter('query_vars', static function (array $vars): array {
            $vars[] = 'tnet_community_landing';
            $vars[] = 'tnet_community_community';
            return $vars;
        });
        add_action('template_redirect', [self::class, 'render']);
    }

    private static function local(): bool { return defined('DDEV_PROJECT') || (bool) getenv('DDEV_PROJECT'); }

    public static function render(): void {
        if (!get_query_var('tnet_community_landing')) return;
        $slug = sanitize_title((string) get_query_var('tnet_community_community'));
        $community = $slug ? (new TNet_Community_Community_Registry())->find_by_slug($slug) : null;
        if ($slug && !$community) {
            status_header(404);
            TNet_Community_Shared_Shell::render('Community not found', static function (): void {
                echo '<section class="c3-page-message"><h1>Community not found</h1><p>This local Community is unavailable.</p></section>';
            });
        }

        $rows = (new TNet_Community_Landing_View())->latest(20, $community['community_id'] ?? null);
        status_header(200);
        nocache_headers();
        header('X-Robots-Tag: noindex, nofollow');
        $name = $community['display_name'] ?? 'Community Activity';
        $new = $community ? home_url('/community/' . $community['slug'] . '/new/') : home_url('/community/new/');
        $landing = $community ? home_url('/community/' . $community['slug'] . '/') : home_url('/community/');

        TNet_Community_Shared_Shell::render($name, static function () use ($rows, $name, $new, $landing, $community): void {
            $community_id = (string) ($community['community_id'] ?? '');
            $authenticated = is_user_logged_in() && $community_id !== '';
            $launcher = '<a class="feed-composer-launcher" href="' . esc_url($authenticated ? $new : wp_login_url($new)) . '"><span aria-hidden="true">＋</span><span>Share a thought, question, or resource…</span></a>';
            $dialog = '';
            if ($authenticated) {
                $user = wp_get_current_user();
                $avatar = get_avatar($user->ID, 36, '', '', ['class' => 'feed-composer-avatar']);
                $photo = '<svg aria-hidden="true" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="15" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M8 5l1-2h6l1 2"/></svg>';
                $launcher = '<div class="feed-composer"><button type="button" class="feed-composer-launcher" data-open-composer="community-composer-dialog">' . $avatar . '<span class="feed-composer-prompt">What’s on your mind, ' . esc_html($user->display_name) . '?</span></button><div class="feed-composer-actions"><button type="button" class="feed-composer-action" data-open-composer="community-composer-dialog">' . $photo . '<span>Photo</span></button></div></div>';
                $identity = '<div class="community-composer-identity">' . $avatar . '<div><strong>' . esc_html($user->display_name) . '</strong><span>AI in Education</span></div></div>';
                $dialog = '<dialog id="community-composer-dialog" class="community-composer-dialog" aria-labelledby="community-composer-title"><div class="community-composer-dialog__inner"><header><h2 id="community-composer-title">Create post</h2><button type="button" class="secondary composer-close" data-close-composer aria-label="Close composer">×</button></header>' . $identity . TNet_Community_Topic_Composer_Controller::embedded_form($community_id, $name, $new, ['action_url' => $new, 'return_to_feed' => $landing, 'hide_cancel' => true], true) . '</div></dialog>';
            }
            $search = '<form class="c3-community-search" role="search" method="get" action="' . esc_url(home_url('/')) . '"><label class="screen-reader-text" for="c3-community-search">Search Teachers.Net</label><svg aria-hidden="true" viewBox="0 0 24 24"><circle cx="10.5" cy="10.5" r="5.5"/><path d="m15 15 4.5 4.5"/></svg><input id="c3-community-search" type="search" name="s" placeholder="Search Teachers.Net"></form>';
            $hero = '<header class="community-header c3-community-hero"><p>Community</p><h1>' . esc_html($name) . '</h1><p>Exploring how artificial intelligence can support teachers, enhance learning, and shape the future of education.</p></header>';
            $local_nav = '<nav class="c3-community-local-nav" aria-label="AI in Education navigation"><a href="' . esc_url($landing) . '" aria-current="page">Discussion</a><span>About</span><span>Members</span><span>Media</span></nav>';
            $filters = '<nav class="c3-community-feed-controls" aria-label="Discussion view"><span class="is-current">Latest</span><span>Popular</span><span>Unanswered</span></nav>';
            echo '<div class="c3-community-layout">' . TNet_Community_Rail_Parent_Service::render($community) . '<main class="c3-community-main">' . $search . $hero . $local_nav . '<section class="c3-community-page">' . $launcher . $filters . '<section aria-labelledby="activity-heading"><h2 id="activity-heading" class="screen-reader-text">Latest Activity</h2>';
            if (!$rows) {
                echo '<p class="empty-state">There is no activity to show yet. Check back soon.</p>';
            } else {
                echo '<div class="feed">';
                foreach ($rows as $row) self::card($row);
                echo '</div>';
            }
            echo '</section></section></main>' . TNet_Community_Rail_Parent_Service::render_right($community, $rows, $new, $authenticated) . '</div>' . $dialog . '<script>(function(){document.querySelectorAll("[data-expandable]").forEach(function(el){function expand(e){if(e&&e.target.closest("a,button,img,.card-preview,.story-image,video,audio"))return;if(el.getAttribute("aria-expanded")==="true")return;el.setAttribute("aria-expanded","true");el.setAttribute("aria-label","Full post");el.querySelector(".feed-excerpt-collapsed").hidden=true;el.querySelector(".feed-excerpt-expanded").hidden=false}el.addEventListener("click",expand);el.addEventListener("keydown",function(e){if(e.key==="Enter"||e.key===" "){e.preventDefault();expand(e)}})});var trigger=null;function openDialog(button,id){var dialog=document.getElementById(id);if(!dialog)return;trigger=button;dialog.showModal();var field=dialog.querySelector("textarea");if(field)field.focus()}document.querySelectorAll("[data-open-dialog],[data-open-composer]").forEach(function(button){button.addEventListener("click",function(){openDialog(button,button.dataset.openDialog||button.dataset.openComposer)})});document.querySelectorAll("[data-close-dialog],[data-close-composer]").forEach(function(button){button.addEventListener("click",function(){var dialog=button.closest("dialog");if(dialog)dialog.close()})});document.querySelectorAll("dialog").forEach(function(dialog){dialog.addEventListener("close",function(){if(trigger){trigger.focus();trigger=null}})});})();</script>';
        });
    }

    private static function card(array $row): void {
        $url = $row['canonical_url'] ?: home_url('/community/thread/' . rawurlencode($row['post_id']) . '/');
        $body = (string) ($row['body'] ?? '');
        $excerpt = wp_trim_words(wp_strip_all_tags($body), 32);
        $truncated = $excerpt !== wp_trim_words(wp_strip_all_tags($body), 9999);
        $text = $truncated ? $excerpt : $body;
        $dialog = 'context-' . substr(hash('sha256', $row['post_id']), 0, 12);
        $subjectless = TNet_Community_Authoring::is_subjectless($row);
        $title = '<h2' . ($subjectless ? ' class="screen-reader-text"' : '') . '><a href="' . esc_url($url) . '">' . esc_html($row['title']) . '</a></h2>';
        $reply_label = number_format_i18n($row['reply_count']) . ' ' . _n('reply', 'replies', $row['reply_count'], 'tnet-community');
        $discussion_icon = '<svg aria-hidden="true" viewBox="0 0 24 24"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3h11A2.5 2.5 0 0 1 20 5.5v7a2.5 2.5 0 0 1-2.5 2.5H11l-4.5 4v-4.1A2.5 2.5 0 0 1 4 12.5z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>';
        echo '<article class="feed-card"' . ($subjectless ? ' aria-label="' . esc_attr($row['title']) . '"' : '') . '><p class="feed-meta"><strong>' . esc_html($row['author_display']) . '</strong> · ' . esc_html(self::relative_time($row['last_activity'])) . '</p>' . $title . '<div class="feed-excerpt"' . ($truncated ? ' data-expandable="true" tabindex="0" role="button" aria-expanded="false" aria-label="Read full post"' : '') . '><span class="feed-excerpt-collapsed">' . TNet_Community_Authoring::markdown($text) . '</span>' . ($truncated ? '<span class="feed-excerpt-expanded" hidden>' . TNet_Community_Authoring::markdown($body) . '</span>' : '') . '</div>' . self::media($row) . '<div class="feed-engagement"><button class="discussion-entry" type="button" data-open-dialog="' . esc_attr($dialog) . '" aria-label="Open discussion">' . $discussion_icon . '<span>' . esc_html($reply_label) . '</span></button></div><dialog id="' . esc_attr($dialog) . '" aria-labelledby="' . esc_attr($dialog) . '-title"><h2 id="' . esc_attr($dialog) . '-title"' . ($subjectless ? ' class="screen-reader-text"' : '') . '>' . esc_html($row['title']) . '</h2><p>' . esc_html($row['author_display']) . '</p><div>' . TNet_Community_Authoring::markdown($text) . '</div><p class="dialog-actions"><a href="' . esc_url($url) . '">Open discussion page</a> <button type="button" data-close-dialog>Close</button></p></dialog></article>';
    }

    private static function relative_time(string $value): string {
        $timestamp = mysql2date('U', $value, false);
        if (!$timestamp) return 'recently';
        $now = current_time('timestamp');
        if ($timestamp > $now) $timestamp = $now;
        $age = max(0, $now - $timestamp);
        if ($age < MINUTE_IN_SECONDS) return 'now';
        if ($age < HOUR_IN_SECONDS) return max(1, (int) floor($age / MINUTE_IN_SECONDS)) . 'm';
        if ($age < DAY_IN_SECONDS) return max(1, (int) floor($age / HOUR_IN_SECONDS)) . 'h';
        if ($age < 2 * DAY_IN_SECONDS) return '1d';
        return human_time_diff($timestamp, $now);
    }

    private static function media(array $row): string {
        $html = '';
        foreach ($row['attachments'] as $attachment) {
            if (($attachment['moderation_state'] ?? 'clear') !== 'clear') continue;
            $html .= TNet_Community_Attachment::render($attachment);
        }
        return $html . TNet_Community_Link_Preview::render($row['preview'] ?? []);
    }
}
