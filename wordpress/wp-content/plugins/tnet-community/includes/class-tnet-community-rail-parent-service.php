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

    public static function render(?array $community = null, bool $include_families = true): string {
        $lists = self::lists();
        if (!$lists) return '';

        $active_slug = self::canonical_term_slug($community);
        $active_uuid = self::term_uuid_for_slug($active_slug);
        $families = $include_families ? self::visible_families($lists, $active_uuid) : [];
        if (!class_exists('TNet_Shared_Shell')) return '';
        $active_url = !empty($community['slug']) ? home_url('/community/' . sanitize_title((string) $community['slug']) . '/') : '';
        foreach ($families as &$family) {
            if (!empty($family['active_label'])) $family['active_url'] = $active_url;
        }
        unset($family);
        ob_start();
        TNet_Shared_Shell::render_community_rail([
            'home_url' => home_url('/'),
            'jobs_url' => home_url('/jobs/'),
            'lessons_url' => home_url('/lessons/'),
            'chatboards_url' => home_url('/chatboards/'),
            'help_url' => home_url('/help/'),
            'show_help' => $include_families,
            'generic_join' => true,
            'families' => $families,
        ]);
        return (string) ob_get_clean();
    }

    public static function breadcrumb_parent(?array $community): ?array {
        $active_slug = self::canonical_term_slug($community);
        $active_uuid = self::term_uuid_for_slug($active_slug);
        if ($active_uuid === '') return null;
        foreach (self::visible_families(self::lists(), $active_uuid) as $family) {
            if (!empty($family['active_label'])) {
                return [
                    'label' => (string) $family['name'],
                    'url' => home_url('/chatboards/'),
                ];
            }
        }
        return null;
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

}
