<?php
defined('ABSPATH') || exit;

/**
 * Historical migration identity is provenance, not authentication. A legacy
 * numeric user id is never a shortcut to a current Community account.
 */
final class TNet_Community_Historical_Author {
    public static function normalize(array $author): array {
        $state = (string) ($author['state'] ?? '');
        if ($state === 'mapped') {
            if (empty($author['mapped_author_id']) || empty($author['mapping_evidence'])) throw new InvalidArgumentException('HISTORICAL_AUTHOR_MAPPING_EVIDENCE_REQUIRED');
            return [
                'state' => 'mapped',
                'target_author_id' => (string) $author['mapped_author_id'],
                'display_name' => (string) ($author['display_name'] ?? 'Historical member'),
                'mapping_evidence' => (string) $author['mapping_evidence'],
                'legacy_snapshot' => self::snapshot($author),
            ];
        }
        if ($state !== 'snapshot' || empty($author['display_name']) || empty($author['legacy_identity_key'])) throw new InvalidArgumentException('HISTORICAL_AUTHOR_SNAPSHOT_REQUIRED');
        return [
            'state' => 'snapshot',
            'target_author_id' => 'historical:' . substr(hash('sha256', (string) $author['legacy_identity_key']), 0, 32),
            'display_name' => (string) $author['display_name'],
            'mapping_evidence' => null,
            'legacy_snapshot' => self::snapshot($author),
        ];
    }

    public static function display(array $post): string {
        $refs = $post['compatibility_refs'] ?? $post['compatibility_json'] ?? [];
        $author = is_array($refs['historical_author'] ?? null) ? $refs['historical_author'] : [];
        $display = trim((string) ($author['display_name'] ?? ''));
        return $display !== '' ? $display : 'Local synthetic author';
    }

    private static function snapshot(array $author): array {
        return [
            'legacy_identity_key' => (string) ($author['legacy_identity_key'] ?? ''),
            'legacy_user_id' => isset($author['legacy_user_id']) ? (string) $author['legacy_user_id'] : null,
            'legacy_login' => isset($author['legacy_login']) ? (string) $author['legacy_login'] : null,
            'display_name' => (string) ($author['display_name'] ?? 'Historical member'),
        ];
    }
}
