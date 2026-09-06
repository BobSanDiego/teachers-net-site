<?php
defined('ABSPATH') || exit;

/** Local-only implementation of the ratified Community permalink contract. */
final class TNet_Community_Canonical_Route {
    public static function decorate_topic(array $post, TNet_Community_Publisher_Repository $repository): array {
        if (($post['post_type'] ?? '') !== 'topic') return $post;
        $refs = is_array($post['compatibility_refs'] ?? null) ? $post['compatibility_refs'] : [];
        if (!empty($refs['canonical_route']['thread_slug'])) return $post;
        $community = (new TNet_Community_Community_Registry())->find((string) ($post['community_id'] ?? ''));
        if (!$community) throw new RuntimeException('CANONICAL_COMMUNITY_UNAVAILABLE');
        $base = sanitize_title((string) ($post['title'] ?? '')) ?: 'discussion';
        $slug = $base;
        for ($suffix = 2; $repository->find_topic_by_canonical_slug((string) $post['community_id'], $slug); $suffix++) {
            if ($suffix > 100) throw new RuntimeException('CANONICAL_THREAD_SLUG_EXHAUSTED');
            $slug = $base . '-' . $suffix;
        }
        $refs['canonical_route'] = [
            'community_slug' => (string) $community['slug'],
            'thread_slug' => $slug,
            'contract_version' => 'v1',
        ];
        $post['compatibility_refs'] = $refs;
        return $post;
    }

    public static function url(array $topic): string {
        $refs = is_array($topic['compatibility_refs'] ?? null) ? $topic['compatibility_refs'] : [];
        $route = is_array($refs['canonical_route'] ?? null) ? $refs['canonical_route'] : [];
        $community = sanitize_title((string) ($route['community_slug'] ?? ''));
        $thread = sanitize_title((string) ($route['thread_slug'] ?? ''));
        if ($community === '' || $thread === '') return '';
        return home_url('/community/' . $community . '/' . $thread . '/');
    }
}
