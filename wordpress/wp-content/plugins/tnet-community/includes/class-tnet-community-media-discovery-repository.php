<?php
defined('ABSPATH') || exit;

/**
 * Read-only Community media projection. Post/media tables remain the source
 * of truth; this class deliberately does not create a second media lifecycle.
 */
final class TNet_Community_Media_Discovery_Repository {
    public const PAGE_SIZE = 24;
    public const MIN_DIMENSION = 240;
    private const VARIANT_NAME = '480.webp';
    private array $tables;

    public function __construct() { $this->tables = TNet_Community_Schema::table_names(); }

    public function page(string $community_id, ?string $cursor = null): array {
        global $wpdb;
        $where = "p.community_id=%s AND p.post_type='topic' AND p.parent_post_id IS NULL AND p.publication_state IN ('published','restored') AND p.visibility='public' AND p.moderation_state='clear' AND p.published_at IS NOT NULL AND a.media_kind='image' AND a.state='ready' AND a.source_width >= %d AND a.source_height >= %d AND v.variant_name=%s AND v.state='verified'";
        $params = [$community_id, self::MIN_DIMENSION, self::MIN_DIMENSION, self::VARIANT_NAME];
        $decoded_cursor = $this->decode_cursor($cursor);
        if ($decoded_cursor) {
            $where .= " AND (p.published_at < %s OR (p.published_at = %s AND p.post_id < %s) OR (p.published_at = %s AND p.post_id = %s AND pm.position > %d) OR (p.published_at = %s AND p.post_id = %s AND pm.position = %d AND pm.media_id > %s))";
            array_push($params,
                $decoded_cursor['published_at'],
                $decoded_cursor['published_at'], $decoded_cursor['post_id'],
                $decoded_cursor['published_at'], $decoded_cursor['post_id'], $decoded_cursor['position'],
                $decoded_cursor['published_at'], $decoded_cursor['post_id'], $decoded_cursor['position'], $decoded_cursor['media_id']
            );
        }
        $sql = "SELECT p.post_id, p.title, p.compatibility_json, p.published_at, pm.media_id, pm.position, pm.alt_text, v.object_key, v.width AS variant_width, v.height AS variant_height, v.mime_type
            FROM {$this->tables['posts']} p
            INNER JOIN {$this->tables['post_media']} pm ON pm.post_id=p.post_id
            INNER JOIN {$this->tables['media_assets']} a ON a.media_id=pm.media_id
            INNER JOIN {$this->tables['media_variants']} v ON v.media_id=a.media_id
            WHERE {$where}
            ORDER BY p.published_at DESC, p.post_id DESC, pm.position ASC, pm.media_id ASC
            LIMIT %d";
        $params[] = self::PAGE_SIZE + 1;
        $rows = $wpdb->get_results($wpdb->prepare($sql, ...$params), ARRAY_A) ?: [];
        $has_more = count($rows) > self::PAGE_SIZE;
        if ($has_more) array_pop($rows);
        $items = [];
        foreach ($rows as $row) {
            $canonical_url = TNet_Community_Canonical_Route::url([
                'post_id' => (string) $row['post_id'],
                'compatibility_refs' => json_decode((string) $row['compatibility_json'], true) ?: [],
            ]);
            if ($canonical_url === '') continue;
            $media_id = (string) $row['media_id'];
            $items[] = [
                'media_id' => $media_id,
                'post_id' => (string) $row['post_id'],
                'post_title' => (string) $row['title'],
                'position' => (int) $row['position'],
                'alt_text' => (string) $row['alt_text'],
                'image_url' => 'https://media.teachers.net/' . rawurlencode($media_id) . '/' . rawurlencode(self::VARIANT_NAME),
                'variant_name' => self::VARIANT_NAME,
                'variant_width' => (int) $row['variant_width'],
                'variant_height' => (int) $row['variant_height'],
                'mime_type' => (string) $row['mime_type'],
                'canonical_url' => $canonical_url,
            ];
        }
        $last = end($items);
        return [
            'items' => $items,
            'next_cursor' => $has_more && $last ? $this->encode_cursor($rows[count($rows) - 1]) : null,
        ];
    }

    private function encode_cursor(array $row): string {
        $payload = wp_json_encode([
            'published_at' => (string) $row['published_at'],
            'post_id' => (string) $row['post_id'],
            'position' => (int) $row['position'],
            'media_id' => (string) $row['media_id'],
        ]);
        return rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
    }

    private function decode_cursor(?string $cursor): ?array {
        if (!is_string($cursor) || $cursor === '' || strlen($cursor) > 512 || !preg_match('/^[A-Za-z0-9_-]+$/', $cursor)) return null;
        $raw = base64_decode(strtr($cursor, '-_', '+/'), true);
        $payload = is_string($raw) ? json_decode($raw, true) : null;
        if (!is_array($payload) || !isset($payload['published_at'], $payload['post_id'], $payload['position'], $payload['media_id'])) return null;
        if (!is_string($payload['published_at']) || !is_string($payload['post_id']) || !is_string($payload['media_id']) || !is_int($payload['position'])) return null;
        if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $payload['published_at']) || $payload['post_id'] === '' || $payload['media_id'] === '' || $payload['position'] < 0) return null;
        return $payload;
    }
}
