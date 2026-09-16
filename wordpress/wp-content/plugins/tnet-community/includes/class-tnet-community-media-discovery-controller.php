<?php
defined('ABSPATH') || exit;

final class TNet_Community_Media_Discovery_Controller {
    public static function register(): void {
        if (!defined('DDEV_PROJECT') && !getenv('DDEV_PROJECT')) return;
        add_rewrite_rule('^community/([a-z0-9-]+)/media/?$', 'index.php?tnet_community_media=1&tnet_community_community=$matches[1]', 'top');
        add_filter('query_vars', static function (array $vars): array {
            $vars[] = 'tnet_community_media';
            $vars[] = 'tnet_community_community';
            return $vars;
        });
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
        $sort = sanitize_key(wp_unslash($_GET['media_sort'] ?? 'newest')) === 'oldest' ? 'oldest' : 'newest';
        $page = (new TNet_Community_Media_Discovery_Repository())->page((string) $community['community_id'], $cursor ?: null, $sort);
        $name = (string) $community['display_name'];
        $landing = home_url('/community/' . sanitize_title((string) $community['slug']) . '/');
        $media_url = home_url('/community/' . sanitize_title((string) $community['slug']) . '/media/');
        $next_url = '';
        if (!empty($page['next_cursor'])) {
            $next_url = add_query_arg(['media_cursor' => $page['next_cursor']] + ($sort === 'oldest' ? ['media_sort' => 'oldest'] : []), $media_url);
        }
        status_header(200);
        nocache_headers();
        header('X-Robots-Tag: noindex, nofollow');
        TNet_Community_Shared_Shell::render($name . ' Media', static function () use ($community, $name, $landing, $media_url, $page, $next_url, $sort): void {
            $nav = '<nav class="c3-community-local-nav" aria-label="' . esc_attr($name) . ' navigation"><a href="' . esc_url($landing) . '">Discussion</a><span>About</span><span>Members</span><a href="' . esc_url($media_url) . '" aria-current="page">Media</a></nav>';
            $hero = TNet_Community_Landing_Controller::community_hero($community, $name);
            $sort_form = '<form class="c3-media-sort" method="get" action="' . esc_url($media_url) . '"><label for="c3-media-sort-select">Sort images</label><select id="c3-media-sort-select" name="media_sort"><option value="newest"' . selected($sort, 'newest', false) . '>Newest first</option><option value="oldest"' . selected($sort, 'oldest', false) . '>Oldest first</option></select><button class="screen-reader-text" type="submit">Apply sort</button></form>';
            $html = '<section class="c3-media-discovery-content" aria-labelledby="c3-media-heading"><div class="c3-media-page-heading"><h2 id="c3-media-heading">Latest images</h2><div class="c3-media-page-heading__tools">' . $sort_form . '</div></div>';
            if (empty($page['items'])) {
                $html .= '<p class="c3-media-empty">No eligible images are available yet.</p>';
            } else {
                $html .= '<div class="c3-media-gallery" data-media-page-size="24">';
                foreach ($page['items'] as $item) {
                    $label = 'Open discussion: ' . (string) $item['post_title'];
                    $html .= '<a class="c3-media-tile" href="' . esc_url($item['canonical_url']) . '" aria-label="' . esc_attr($label) . '"><img src="' . esc_url($item['image_url']) . '" alt="' . esc_attr($item['alt_text']) . '" width="' . (int) $item['variant_width'] . '" height="' . (int) $item['variant_height'] . '" loading="lazy" decoding="async"></a>';
                }
                $html .= '</div>';
                if ($next_url !== '') {
                    $html .= '<div class="c3-media-continuation" data-next-url="' . esc_attr($next_url) . '"><span class="c3-media-continuation__status" role="status" aria-live="polite">More images load as you scroll.</span><a class="c3-media-continuation__fallback" href="' . esc_url($next_url) . '">Continue loading images</a></div>';
                }
            }
            $script = $next_url !== '' ? '<script>(function(){var continuation=document.querySelector(".c3-media-continuation");var gallery=document.querySelector(".c3-media-gallery");if(!continuation||!gallery||!window.IntersectionObserver)return;var status=continuation.querySelector(".c3-media-continuation__status"),fallback=continuation.querySelector(".c3-media-continuation__fallback"),nextUrl=continuation.dataset.nextUrl,busy=false,observer;function fail(){busy=false;if(status)status.textContent="More images could not be loaded. Use the retry link.";if(fallback)fallback.hidden=false}function load(){if(busy||!nextUrl)return;busy=true;if(status)status.textContent="Loading more images…";fetch(nextUrl,{credentials:"same-origin",headers:{Accept:"text/html"}}).then(function(response){if(!response.ok)throw new Error("media continuation failed");return response.text()}).then(function(markup){var parsed=new DOMParser().parseFromString(markup,"text/html"),incoming=parsed.querySelector(".c3-media-gallery"),incomingContinuation=parsed.querySelector(".c3-media-continuation");if(!incoming)throw new Error("media continuation missing");var seen=new Set(Array.prototype.map.call(gallery.querySelectorAll(".c3-media-tile"),function(tile){return tile.href}));Array.prototype.forEach.call(incoming.querySelectorAll(".c3-media-tile"),function(tile){if(!seen.has(tile.href))gallery.appendChild(document.importNode(tile,true))});nextUrl=incomingContinuation?incomingContinuation.dataset.nextUrl:"";if(nextUrl){continuation.dataset.nextUrl=nextUrl;if(fallback)fallback.href=nextUrl;if(status)status.textContent="More images load as you scroll."}else{if(status)status.textContent="All images loaded.";observer.disconnect();continuation.remove()}}).catch(fail).finally(function(){busy=false})}fallback.hidden=true;observer=new IntersectionObserver(function(entries){if(entries.some(function(entry){return entry.isIntersecting}))load()},{rootMargin:"480px 0px"});observer.observe(continuation);fallback.addEventListener("click",function(){fallback.hidden=true;load()})})();</script>' : '';
            echo '<div class="c3-media-composition"><main class="c3-media-fullwidth-main">' . $hero . $nav . '<section class="c3-media-discovery-surface">' . $html . '</section></main></div>' . $script;
        }, ['community_media' => true]);
    }

    private static function breadcrumb_icon(string $name): string {
        $paths = [
            'home' => '<path d="M2.5 10.5 12 2.8l9.5 7.7"/><path d="M5.5 12.2 12 6.9l6.5 5.3V21H5.5z"/>',
            'chat' => '<path d="M20.5 11a8.5 8.5 0 0 1-8.5 8.5 8.8 8.8 0 0 1-3.4-.7L4 20l1.2-4.2A8.5 8.5 0 1 1 20.5 11Z"/>',
            'flame' => '<path d="M13.8 3.6c.5 3.3-1.4 4.4-2.5 5.8-.8-1.2-1-2.2-.7-3.6C7.4 8 5.4 10.7 5.4 14a6.6 6.6 0 0 0 13.2 0c0-3.7-1.7-7-4.8-10.4Z"/>',
        ];
        return '<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">' . ($paths[$name] ?? $paths['chat']) . '</svg>';
    }
}
