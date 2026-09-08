<?php
defined('ABSPATH') || exit;

/**
 * Community consumer for the public Core Terms / Views Rail Parent contract.
 * Community never reads Profilaxes tables or embeds View/version IDs.
 */
final class TNet_Community_Rail_Parent_Service {
    private const FRAMEWORK = 'teachers-net';
    private const ROLE = 'c3_rail_parent';

    public static function lists(): array {
        if (!class_exists('CFM_Views_Service')) return [];
        return CFM_Views_Service::discover_published_lists(self::FRAMEWORK, self::ROLE);
    }

    public static function render(?array $community = null): string {
        $lists = self::lists();
        if (!$lists) return '';

        $active_slug = self::canonical_term_slug($community);
        $active_uuid = self::term_uuid_for_slug($active_slug);
        $html = '<aside class="c3-community-rail" aria-label="Community categories"><div class="c3-community-rail__heading">Explore Communities</div>';
        foreach ($lists as $list) {
            $name = (string) ($list['view']['name'] ?? '');
            if ($name === '') continue;
            $html .= '<section class="c3-community-rail__family"><h2>' . esc_html($name) . '</h2><ul>';
            foreach ((array) ($list['members'] ?? []) as $member) {
                $uuid = (string) ($member['term_uuid'] ?? '');
                $label = (string) ($member['label'] ?? '');
                if ($label === '') continue;
                $active = $active_uuid !== '' && $uuid === $active_uuid;
                $class = $active ? ' class="is-current"' : '';
                if ($active && !empty($community['slug'])) {
                    $html .= '<li' . $class . '><a href="' . esc_url(home_url('/community/' . sanitize_title((string) $community['slug']) . '/')) . '" aria-current="page">' . esc_html($label) . '</a></li>';
                } else {
                    $html .= '<li' . $class . '><span>' . esc_html($label) . '</span></li>';
                }
            }
            $html .= '</ul></section>';
        }
        return $html . '</aside>';
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
