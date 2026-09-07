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
                $launcher = '<button type="button" class="feed-composer-launcher" data-open-composer="community-composer-dialog">' . $avatar . '<span class="feed-composer-prompt">What’s on your mind, ' . esc_html($user->display_name) . '?</span><span class="feed-composer-photo" aria-hidden="true">' . $photo . '<span>Photo</span></span></button>';
                $identity = '<div class="community-composer-identity">' . $avatar . '<div><strong>' . esc_html($user->display_name) . '</strong><span>AI in Education</span></div></div>';
                $dialog = '<dialog id="community-composer-dialog" class="community-composer-dialog" aria-labelledby="community-composer-title"><div class="community-composer-dialog__inner"><header><h2 id="community-composer-title">Create post</h2><button type="button" class="secondary composer-close" data-close-composer aria-label="Close composer">×</button></header>' . $identity . TNet_Community_Topic_Composer_Controller::embedded_form($community_id, $name, $new, ['action_url' => $new, 'return_to_feed' => $landing], true) . '</div></dialog>';
            }
            echo '<section class="c3-community-page"><header class="community-header"><p>Teachers.Net Community</p><h1>' . esc_html($name) . '</h1><p>Recent conversations from teachers and education professionals.</p></header>' . $launcher . '<section aria-labelledby="activity-heading"><h2 id="activity-heading">Latest Activity</h2>';
            if (!$rows) {
                echo '<p class="empty-state">There is no activity to show yet. Check back soon.</p>';
            } else {
                echo '<div class="feed">';
                foreach ($rows as $row) self::card($row);
                echo '</div>';
            }
            echo '</section></section>' . $dialog . '<script>(function(){document.querySelectorAll("[data-expandable]").forEach(function(el){function expand(e){if(e&&e.target.closest("a,button,img,.card-preview,.story-image,video,audio"))return;if(el.getAttribute("aria-expanded")==="true")return;el.setAttribute("aria-expanded","true");el.setAttribute("aria-label","Full post");el.querySelector(".feed-excerpt-collapsed").hidden=true;el.querySelector(".feed-excerpt-expanded").hidden=false}el.addEventListener("click",expand);el.addEventListener("keydown",function(e){if(e.key==="Enter"||e.key===" "){e.preventDefault();expand(e)}})});var trigger=null;function openDialog(button,id){trigger=button;var dialog=document.getElementById(id);dialog.showModal();var field=dialog.querySelector("textarea");if(field)field.focus()}document.querySelectorAll("[data-open-dialog]").forEach(function(button){button.addEventListener("click",function(){openDialog(button,button.dataset.openDialog)})});document.querySelectorAll("[data-open-composer]").forEach(function(button){button.addEventListener("click",function(){openDialog(button,button.dataset.openComposer)})});document.querySelectorAll("[data-close-dialog],[data-close-composer]").forEach(function(button){button.addEventListener("click",function(){button.closest("dialog").close()})});document.querySelectorAll("dialog").forEach(function(dialog){dialog.addEventListener("close",function(){if(trigger){trigger.focus();trigger=null}})});})();</script>';
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
        echo '<article class="feed-card"' . ($subjectless ? ' aria-label="' . esc_attr($row['title']) . '"' : '') . '><p class="feed-meta">' . esc_html($row['author_display']) . ' · ' . esc_html($row['last_activity']) . '</p>' . $title . '<div class="feed-excerpt"' . ($truncated ? ' data-expandable="true" tabindex="0" role="button" aria-expanded="false" aria-label="Read full post"' : '') . '><span class="feed-excerpt-collapsed">' . TNet_Community_Authoring::markdown($text) . '</span>' . ($truncated ? '<span class="feed-excerpt-expanded" hidden>' . TNet_Community_Authoring::markdown($body) . '</span>' : '') . '</div>' . self::media($row) . '<p class="feed-meta">' . esc_html(number_format_i18n($row['reply_count'])) . ' ' . esc_html(_n('reply', 'replies', $row['reply_count'], 'tnet-community')) . ' · <a href="' . esc_url($url) . '">Open conversation</a> <button class="context-action" type="button" data-open-dialog="' . esc_attr($dialog) . '">Quick view</button></p><dialog id="' . esc_attr($dialog) . '" aria-labelledby="' . esc_attr($dialog) . '-title"><h2 id="' . esc_attr($dialog) . '-title"' . ($subjectless ? ' class="screen-reader-text"' : '') . '>' . esc_html($row['title']) . '</h2><p>' . esc_html($row['author_display']) . '</p><div>' . TNet_Community_Authoring::markdown($text) . '</div><p class="dialog-actions"><a href="' . esc_url($url) . '">Open discussion page</a> <button type="button" data-close-dialog>Close</button></p></dialog></article>';
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
