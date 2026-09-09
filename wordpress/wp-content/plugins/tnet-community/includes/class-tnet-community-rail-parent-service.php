<?php
defined('ABSPATH') || exit;

/**
 * Community consumer for the public Core Terms / Views Rail Parent contract.
 * Community never reads Profilaxes tables or embeds View/version IDs.
 */
final class TNet_Community_Rail_Parent_Service {
    private const FRAMEWORK = 'teachers-net';
    private const ROLE = 'c3_rail_parent';
    private const PRIMARY_FAMILIES = ['Hot Topics', 'Grade Levels', 'Subject Areas'];

    public static function lists(): array {
        if (!class_exists('CFM_Views_Service')) return [];
        return CFM_Views_Service::discover_published_lists(self::FRAMEWORK, self::ROLE);
    }

    public static function render(?array $community = null): string {
        $lists = self::lists();
        if (!$lists) return '';

        $active_slug = self::canonical_term_slug($community);
        $active_uuid = self::term_uuid_for_slug($active_slug);
        $families = self::visible_families($lists, $active_uuid);
        $html = '<aside class="c3-community-rail" aria-label="Community navigation">';
        $html .= '<nav class="c3-community-rail__platform" aria-label="Teachers.Net"><a href="' . esc_url(home_url('/')) . '">' . self::icon('home') . '<span>Home</span></a><a href="' . esc_url(home_url('/jobs/')) . '">' . self::icon('briefcase') . '<span>Jobs</span></a><a href="' . esc_url(home_url('/lessons/')) . '">' . self::icon('book') . '<span>Lesson Plans</span></a></nav>';
        $html .= '<div class="c3-community-rail__divider" aria-hidden="true"></div><div class="c3-community-rail__section-label">' . self::icon('chat') . '<span>Chatboards</span></div>';
        foreach ($families as $family) {
            $html .= '<section class="c3-community-rail__family"><h2>' . self::icon(self::family_icon((string) $family['name'])) . '<span>' . esc_html((string) $family['name']) . '</span></h2>';
            if (!empty($family['active_label'])) {
                $url = !empty($community['slug']) ? home_url('/community/' . sanitize_title((string) $community['slug']) . '/') : '';
                $html .= '<ul><li class="is-current">' . ($url ? '<a href="' . esc_url($url) . '" aria-current="page">' . esc_html((string) $family['active_label']) . '</a>' : '<span>' . esc_html((string) $family['active_label']) . '</span>') . '</li></ul>';
            }
            $html .= '</section>';
        }
        $html .= '<section class="c3-community-rail__family c3-community-rail__family--context"><h2>' . self::icon('pin') . '<span>CA Teachers</span></h2></section>';
        $html .= '<nav class="c3-community-rail__utility" aria-label="Community support"><a href="' . esc_url(home_url('/help/')) . '">' . self::icon('help') . '<span>Help</span></a><a href="' . esc_url(home_url('/settings/')) . '">' . self::icon('settings') . '<span>Settings</span></a></nav>';
        return $html . '</aside>';
    }

    public static function render_right(?array $community, array $rows, string $new_url, bool $authenticated): string {
        $post_url = $authenticated ? $new_url : wp_login_url($new_url);
        $name = (string) ($community['display_name'] ?? 'This Community');
        $recent = array_slice($rows, 0, 4);
        $html = '<aside class="c3-community-right" aria-label="Community details"><div class="c3-community-right__actions"><a class="c3-community-create-post" href="' . esc_url($post_url) . '"><span aria-hidden="true">＋</span><span>Create Post</span></a></div>';
        $html .= '<section class="c3-community-module"><h2>About This Community</h2><p>Recent conversations from teachers and education professionals in ' . esc_html($name) . '.</p><p class="c3-community-module__meta">Public Community</p></section>';
        $html .= '<section class="c3-community-ad-slot" aria-label="Advertisement"><span>Advertisement</span><small>300 × 250</small></section>';
        $html .= '<section class="c3-community-module"><h2>Trending in ' . esc_html($name) . '</h2>';
        if ($recent) {
            $html .= '<ol class="c3-community-module__list">';
            foreach ($recent as $index => $row) {
                $url = (string) ($row['canonical_url'] ?? '');
                $label = (string) ($row['title'] ?? 'Discussion');
                $html .= '<li><span>' . (int) ($index + 1) . '</span>' . ($url ? '<a href="' . esc_url($url) . '">' . esc_html($label) . '</a>' : '<span>' . esc_html($label) . '</span>') . '</li>';
            }
            $html .= '</ol>';
        } else {
            $html .= '<p class="c3-community-module__empty">Trending discussions will appear as activity grows.</p>';
        }
        $html .= '</section><section class="c3-community-module"><h2>Related Chatboards</h2><p class="c3-community-module__empty">Related Communities will appear as additional C3 routes become available.</p></section></aside>';
        return $html;
    }

    private static function visible_families(array $lists, string $active_uuid): array {
        $by_name = [];
        foreach ($lists as $list) {
            $name = (string) ($list['view']['name'] ?? '');
            if ($name !== '') $by_name[$name] = $list;
        }
        $names = self::PRIMARY_FAMILIES;
        foreach ($by_name as $name => $list) {
            foreach ((array) ($list['members'] ?? []) as $member) {
                if ($active_uuid !== '' && (string) ($member['term_uuid'] ?? '') === $active_uuid && !in_array($name, $names, true)) {
                    $names[] = $name;
                }
            }
        }
        $visible = [];
        foreach ($names as $name) {
            if (empty($by_name[$name])) continue;
            $active_label = '';
            foreach ((array) ($by_name[$name]['members'] ?? []) as $member) {
                if ($active_uuid !== '' && (string) ($member['term_uuid'] ?? '') === $active_uuid) {
                    $active_label = (string) ($member['label'] ?? '');
                    break;
                }
            }
            $visible[] = ['name' => $name, 'active_label' => $active_label];
        }
        return $visible;
    }

    private static function canonical_term_slug(?array $community): string {
        $compatibility = is_array($community['compatibility'] ?? null) ? $community['compatibility'] : [];
        return sanitize_title((string) ($compatibility['canonical_term_slug'] ?? $community['slug'] ?? ''));
    }

    private static function term_uuid_for_slug(string $slug): string {
        if ($slug === '' || !class_exists('CFM')) return '';
        foreach ((array) CFM::get_terms(self::FRAMEWORK) as $term) {
            if (sanitize_title((string) ($term->slug ?? '')) === $slug) return (string) ($term->term_uuid ?? '');
        }
        return '';
    }

    private static function family_icon(string $name): string {
        return match ($name) {
            'Hot Topics' => 'flame',
            'Grade Levels' => 'layers',
            'Subject Areas' => 'book',
            default => 'chat',
        };
    }

    private static function icon(string $name): string {
        $paths = [
            'home' => '<path d="m3 10 9-7 9 7v10H15v-6H9v6H3z"/>',
            'briefcase' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5h8v2M3 12h18"/>',
            'book' => '<path d="M4 5.5c2.8-.8 5.4-.3 8 1.4v12c-2.6-1.7-5.2-2.2-8-1.4zM20 5.5c-2.8-.8-5.4-.3-8 1.4v12c2.6-1.7 5.2-2.2 8-1.4zM12 7v12"/>',
            'chat' => '<path d="M20 11a7 7 0 0 1-8 6.9L7 20l1-4a7 7 0 1 1 12-5z"/>',
            'flame' => '<path d="M13.8 3.6c.5 3.3-1.4 4.4-2.5 5.8-.8-1.2-1-2.2-.7-3.6C7.4 8 5.4 10.7 5.4 14a6.6 6.6 0 0 0 13.2 0c0-3.7-1.7-7-4.8-10.4Z"/>',
            'layers' => '<path d="m3 7 9-4 9 4-9 4zM3 12l9 4 9-4M3 17l9 4 9-4"/>',
            'pin' => '<path d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0z"/><circle cx="12" cy="10" r="2"/>',
            'help' => '<circle cx="12" cy="12" r="9"/><path d="M9.5 9a2.6 2.6 0 1 1 4.6 1.7c-1.2 1.2-2.1 1.6-2.1 3.3M12 17h.01"/>',
            'settings' => '<path d="M12 3.5 13.4 5a7.3 7.3 0 0 1 1.8.7l2-.7 1.8 1.8-.7 2a7.3 7.3 0 0 1 .7 1.8l1.5 1.4v2.6L19 16a7.3 7.3 0 0 1-.7 1.8l.7 2-1.8 1.8-2-.7a7.3 7.3 0 0 1-1.8.7L12 23h-2.6L8 21.5a7.3 7.3 0 0 1-1.8-.7l-2 .7-1.8-1.8.7-2a7.3 7.3 0 0 1-.7-1.8l-1.4-1.4V12l1.4-1.4a7.3 7.3 0 0 1 .7-1.8l-.7-2 1.8-1.8 2 .7A7.3 7.3 0 0 1 8 5l1.4-1.5H12Z"/><circle cx="12" cy="12" r="3"/>',
        ];
        return '<svg class="c3-community-icon c3-community-icon--' . esc_attr($name) . '" aria-hidden="true" viewBox="0 0 24 24" focusable="false">' . ($paths[$name] ?? '') . '</svg>';
    }
}
