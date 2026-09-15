<?php
defined('ABSPATH') || exit;

final class TNet_Community_Media_Discovery_Controller {
    public static function register(): void {
        if (!defined('DDEV_PROJECT') && !getenv('DDEV_PROJECT')) return;
        add_rewrite_rule('^community/([a-z0-9-]+)/media/?$', 'index.php?tnet_community_media=1&tnet_community_community=$matches[1]', 'top');
        add_filter('query_vars', static function (array $vars): array { $vars[] = 'tnet_community_media'; return $vars; });
        add_action('template_redirect', [self::class, 'render']);
    }

    public static function render(): void {
        if (!get_query_var('tnet_community_media')) return;
        $slug = sanitize_title((string) get_query_var('tnet_community_community'));
        $community = $slug ? (new TNet_Community_Community_Registry())->find_by_slug($slug) : null;
        if (!$community || ($community['visibility'] ?? '') !== 'public') {
            status_header(404);
            TNet_Community_Shared_Shell::render('Community not found', static function (): void { echo '<section class="c3-page-message"><h1>Community not found</h1><p>This local Community is unavailable.</p></section>'; });
            return;
        }
        $cursor = sanitize_text_field(wp_unslash($_GET['media_cursor'] ?? ''));
        $page = (new TNet_Community_Media_Discovery_Repository())->page((string) $community['community_id'], $cursor ?: null);
        $name = (string) $community['display_name'];
        $landing = home_url('/community/' . sanitize_title((string) $community['slug']) . '/');
        $media_url = home_url('/community/' . sanitize_title((string) $community['slug']) . '/media/');
        $next_url = !empty($page['next_cursor']) ? add_query_arg('media_cursor', $page['next_cursor'], $media_url) : '';
        status_header(200);
        nocache_headers();
        header('X-Robots-Tag: noindex, nofollow');
        TNet_Community_Shared_Shell::render($name . ' Media', static function () use ($name, $landing, $media_url, $page, $next_url): void {
            $nav = '<nav class="c3-community-local-nav" aria-label="' . esc_attr($name) . ' navigation"><a href="' . esc_url($landing) . '">Discussion</a><span>About</span><span>Members</span><a href="' . esc_url($media_url) . '" aria-current="page">Media</a></nav>';
            $html = '<div class="c3-media-page-shell"><header class="c3-media-hero"><p>Community</p><h1>' . esc_html($name) . ' Media</h1><p>Images shared in published discussions.</p></header>' . $nav . '<main class="c3-media-page" aria-labelledby="c3-media-heading"><div class="c3-media-page-heading"><h2 id="c3-media-heading">Latest images</h2><p>Open an image to view its discussion.</p></div>';
            if (empty($page['items'])) {
                $html .= '<p class="c3-media-empty">No eligible images are available yet.</p>';
            } else {
                $html .= '<div class="c3-media-gallery" data-media-page-size="24">';
                foreach ($page['items'] as $item) {
                    $label = 'Open discussion: ' . (string) $item['post_title'];
                    $html .= '<a class="c3-media-tile" href="' . esc_url($item['canonical_url']) . '" aria-label="' . esc_attr($label) . '"><img src="' . esc_url($item['image_url']) . '" alt="' . esc_attr($item['alt_text']) . '" width="' . (int) $item['variant_width'] . '" height="' . (int) $item['variant_height'] . '" loading="lazy" decoding="async"></a>';
                }
                $html .= '</div>';
                if ($next_url !== '') $html .= '<nav class="c3-media-pagination" aria-label="Media pagination"><a href="' . esc_url($next_url) . '">Older images</a></nav>';
            }
            echo $html . '</main></div>';
        });
    }
}
