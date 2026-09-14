<?php
defined('ABSPATH') || exit;

final class TNet_Community_Media_Registry {
    private const MAX_BYTES = 10485760;
    private const TIMEOUT = 90;
    private const VARIANTS = ['master.jpg' => ['mime' => 'image/jpeg', 'format' => 'JPEG', 'edge' => 2048], '1440.webp' => ['mime' => 'image/webp', 'format' => 'WEBP', 'edge' => 1440], '960.webp' => ['mime' => 'image/webp', 'format' => 'WEBP', 'edge' => 960], '480.webp' => ['mime' => 'image/webp', 'format' => 'WEBP', 'edge' => 480]];
    private array $tables;

    public function __construct() { $this->tables = TNet_Community_Schema::table_names(); }

    public static function register_rest_routes(): void {
        register_rest_route('tnet-community/v1', '/media/presign', ['methods' => WP_REST_Server::CREATABLE, 'permission_callback' => static fn(): bool => is_user_logged_in() && current_user_can('read'), 'callback' => [self::class, 'presign_request']]);
        register_rest_route('tnet-community/v1', '/media/(?P<media_id>[A-Za-z0-9][A-Za-z0-9._-]{0,127})', ['methods' => WP_REST_Server::READABLE, 'permission_callback' => static fn(): bool => is_user_logged_in() && current_user_can('read'), 'callback' => [self::class, 'status_request']]);
    }

    public static function presign_request(WP_REST_Request $request): WP_REST_Response {
        try {
            $data = $request->get_json_params();
            $file_name = sanitize_file_name((string) ($data['file_name'] ?? ''));
            $mime = sanitize_text_field((string) ($data['mime_type'] ?? ''));
            $size = (int) ($data['file_size'] ?? 0);
            $media_id = sanitize_text_field((string) ($data['media_id'] ?? ''));
            $source_width = max(0, (int) ($data['source_width'] ?? 0));
            $source_height = max(0, (int) ($data['source_height'] ?? 0));
            $registry = new self();
            if ($media_id !== '') $asset = $registry->reset_for_retry($media_id, $file_name, $mime, $size, $source_width, $source_height);
            else $asset = $registry->create_asset($file_name, $mime, $size, $source_width, $source_height);
            $upload = TNet_Community_Media_Aws::presigned_post($asset['source_key'], $mime, $size);
            return new WP_REST_Response(['media_id' => $asset['media_id'], 'key' => $asset['source_key'], 'upload' => $upload, 'state' => $asset['state']], 201);
        } catch (Throwable $error) { return new WP_REST_Response(['code' => 'media_presign_failed', 'message' => 'Media upload could not be prepared.'], 400); }
    }

    public static function status_request(WP_REST_Request $request): WP_REST_Response {
        $registry = new self();
        try { $asset = $registry->reconcile((string) $request['media_id'], (int) get_current_user_id()); }
        catch (Throwable $error) { return new WP_REST_Response(['code' => 'media_status_failed', 'message' => 'Media status is unavailable.'], 404); }
        if (!$asset) return new WP_REST_Response(['code' => 'media_not_found', 'message' => 'Media not found.'], 404);
        return new WP_REST_Response($registry->public_asset($asset), 200);
    }

    public function create_asset(string $file_name, string $mime, int $size, int $width = 0, int $height = 0): array {
        global $wpdb;
        [$mime, $extension] = $this->validate_upload($file_name, $mime, $size);
        $media_id = 'media-' . strtolower(str_replace('-', '', wp_generate_uuid4()));
        $now = current_time('mysql', true);
        $row = ['media_id' => $media_id, 'owner_id' => (int) get_current_user_id(), 'owner_type' => 'wordpress_user', 'media_kind' => 'image', 'processing_profile' => 'ordinary-photo-v1', 'source_key' => 'quarantine/' . $media_id . '/original.' . $extension, 'source_bytes' => $size, 'source_width' => $width ?: null, 'source_height' => $height ?: null, 'source_format' => strtoupper($extension === 'jpg' || $extension === 'jpeg' ? 'JPEG' : $extension), 'source_mime' => $mime, 'state' => 'uploading', 'error_code' => null, 'created_at' => $now, 'updated_at' => $now, 'uploaded_at' => null, 'processing_deadline_at' => gmdate('Y-m-d H:i:s', time() + self::TIMEOUT), 'processed_at' => null];
        if (false === $wpdb->insert($this->tables['media_assets'], $row, ['%s','%d','%s','%s','%s','%s','%d','%d','%d','%s','%s','%s','%s','%s','%s','%s','%s','%s'])) throw new RuntimeException('MEDIA_REGISTRY_CREATE_FAILED');
        return $this->find($media_id, (int) get_current_user_id());
    }

    public function reset_for_retry(string $media_id, string $file_name, string $mime, int $size, int $width = 0, int $height = 0): array {
        global $wpdb;
        [$mime, $extension] = $this->validate_upload($file_name, $mime, $size);
        $asset = $this->find($media_id, (int) get_current_user_id());
        if (!$asset || !in_array($asset['state'], ['failed', 'uploading', 'processing'], true)) throw new RuntimeException('MEDIA_RETRY_NOT_ALLOWED');
        $key = 'quarantine/' . $media_id . '/original.' . $extension;
        $updated = $wpdb->update($this->tables['media_assets'], ['source_key' => $key, 'source_bytes' => $size, 'source_width' => $width ?: null, 'source_height' => $height ?: null, 'source_format' => strtoupper($extension === 'jpg' || $extension === 'jpeg' ? 'JPEG' : $extension), 'source_mime' => $mime, 'state' => 'uploading', 'error_code' => null, 'updated_at' => current_time('mysql', true), 'processing_deadline_at' => gmdate('Y-m-d H:i:s', time() + self::TIMEOUT)], ['media_id' => $media_id, 'owner_id' => (int) get_current_user_id()], ['%s','%d','%d','%s','%s','%s','%s','%s','%s'], ['%s','%d']);
        if (false === $updated) throw new RuntimeException('MEDIA_RETRY_FAILED');
        return $this->find($media_id, (int) get_current_user_id());
    }

    public function reconcile(string $media_id, int $owner_id): ?array {
        global $wpdb;
        $asset = $this->find($media_id, $owner_id);
        if (!$asset) return null;
        if ($asset['state'] === 'uploading') {
            $wpdb->update($this->tables['media_assets'], ['state' => 'processing', 'uploaded_at' => current_time('mysql', true), 'updated_at' => current_time('mysql', true)], ['media_id' => $media_id], ['%s','%s','%s'], ['%s']);
            $asset = $this->find($media_id, $owner_id);
        }
        if (in_array($asset['state'], ['ready', 'failed'], true)) return $asset;
        $verified = [];
        foreach (self::VARIANTS as $name => $spec) {
            $url = self::public_url($media_id, $name);
            $response = wp_remote_get($url, ['timeout' => 6, 'limit_response_size' => 8388608, 'redirection' => 0]);
            $code = is_wp_error($response) ? 0 : (int) wp_remote_retrieve_response_code($response);
            $body = is_wp_error($response) ? '' : wp_remote_retrieve_body($response);
            $content_type = is_wp_error($response) ? '' : strtolower((string) wp_remote_retrieve_header($response, 'content-type'));
            if ($code !== 200 || strlen($body) < 1 || strpos($content_type, $spec['mime']) !== 0) continue;
            $size = strlen($body);
            $dimensions = @getimagesizefromstring($body);
            if (!is_array($dimensions) || (int) $dimensions[0] < 1 || (int) $dimensions[1] < 1 || max((int) $dimensions[0], (int) $dimensions[1]) > $spec['edge']) continue;
            $verified[$name] = ['bytes' => $size, 'width' => (int) $dimensions[0], 'height' => (int) $dimensions[1], 'mime' => $spec['mime'], 'format' => $spec['format'], 'object_key' => 'ready/' . $media_id . '/' . $name];
        }
        foreach ($verified as $name => $variant) $this->upsert_variant($media_id, $name, $variant);
        if (count($verified) === count(self::VARIANTS)) {
            $wpdb->update($this->tables['media_assets'], ['state' => 'ready', 'processed_at' => current_time('mysql', true), 'updated_at' => current_time('mysql', true), 'error_code' => null], ['media_id' => $media_id], ['%s','%s','%s','%s'], ['%s']);
        } elseif (!empty($asset['processing_deadline_at']) && strtotime((string) $asset['processing_deadline_at']) < time()) {
            $wpdb->update($this->tables['media_assets'], ['state' => 'failed', 'error_code' => 'processing_timeout', 'updated_at' => current_time('mysql', true)], ['media_id' => $media_id], ['%s','%s','%s'], ['%s']);
        }
        return $this->find($media_id, $owner_id);
    }

    public function prepare_for_publication(array $requested, int $owner_id): array {
        $items = [];
        foreach (array_values($requested) as $position => $item) {
            $media_id = sanitize_text_field((string) ($item['media_id'] ?? ''));
            $alt = sanitize_text_field((string) ($item['alt_text'] ?? ''));
            if ($media_id === '' || $alt === '') throw new RuntimeException('MEDIA_ALT_REQUIRED');
            $asset = $this->reconcile($media_id, $owner_id);
            if (!$asset || $asset['state'] !== 'ready') throw new RuntimeException('MEDIA_NOT_READY');
            $items[] = ['media_id' => $media_id, 'attachment_id' => 'media:' . $media_id, 'attachment_type' => 'image', 'source_kind' => 'c3_media', 'source_reference' => 'media:' . $media_id, 'title' => 'Community image', 'description' => '', 'alt_text' => $alt, 'mime_type' => 'image/jpeg', 'file_size' => (int) $asset['source_bytes'], 'width' => (int) $asset['source_width'], 'height' => (int) $asset['source_height'], 'rights_status' => 'author_declared', 'moderation_state' => 'clear', 'lifecycle_state' => 'active', 'created_at' => (string) $asset['created_at'], 'variant_urls' => $this->variant_urls($media_id), 'position' => $position];
        }
        return $items;
    }

    public function attach_in_transaction(string $post_id, array $items): void {
        global $wpdb;
        foreach ($items as $item) {
            if (false === $wpdb->insert($this->tables['post_media'], ['post_id' => $post_id, 'media_id' => $item['media_id'], 'position' => (int) $item['position'], 'alt_text' => $item['alt_text'], 'created_at' => current_time('mysql', true)], ['%s','%s','%d','%s','%s'])) throw new RuntimeException('MEDIA_RELATIONSHIP_WRITE_FAILED');
        }
    }

    public function attachments_for_post(string $post_id): array {
        global $wpdb;
        $rows = $wpdb->get_results($wpdb->prepare("SELECT pm.media_id, pm.position, pm.alt_text, a.* FROM {$this->tables['post_media']} pm INNER JOIN {$this->tables['media_assets']} a ON a.media_id=pm.media_id WHERE pm.post_id=%s ORDER BY pm.position ASC", $post_id), ARRAY_A) ?: [];
        $items = [];
        foreach ($rows as $row) if ($row['state'] === 'ready') $items[] = ['media_id' => $row['media_id'], 'attachment_id' => 'media:' . $row['media_id'], 'attachment_type' => 'image', 'source_kind' => 'c3_media', 'source_reference' => 'media:' . $row['media_id'], 'title' => 'Community image', 'description' => '', 'alt_text' => $row['alt_text'], 'mime_type' => 'image/jpeg', 'file_size' => (int) $row['source_bytes'], 'width' => (int) $row['source_width'], 'height' => (int) $row['source_height'], 'rights_status' => 'author_declared', 'moderation_state' => 'clear', 'lifecycle_state' => 'active', 'created_at' => $row['created_at'], 'variant_urls' => $this->variant_urls($row['media_id']), 'position' => (int) $row['position']];
        return $items;
    }

    private function find(string $media_id, int $owner_id): ?array {
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tables['media_assets']} WHERE media_id=%s AND owner_id=%d", $media_id, $owner_id), ARRAY_A);
        return $row ?: null;
    }

    private function upsert_variant(string $media_id, string $name, array $variant): void {
        global $wpdb;
        $wpdb->query($wpdb->prepare("INSERT INTO {$this->tables['media_variants']} (media_id,variant_name,object_key,byte_count,width,height,format,mime_type,state,verified_at,created_at,updated_at) VALUES (%s,%s,%s,%d,%d,%d,%s,%s,'verified',%s,%s,%s) ON DUPLICATE KEY UPDATE object_key=VALUES(object_key),byte_count=VALUES(byte_count),width=VALUES(width),height=VALUES(height),format=VALUES(format),mime_type=VALUES(mime_type),state='verified',verified_at=VALUES(verified_at),updated_at=VALUES(updated_at)", $media_id, $name, $variant['object_key'], $variant['bytes'], $variant['width'], $variant['height'], $variant['format'], $variant['mime'], current_time('mysql', true), current_time('mysql', true), current_time('mysql', true)));
    }

    private function validate_upload(string $file_name, string $mime, int $size): array {
        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if ($extension === 'jpeg') $extension = 'jpg';
        $map = ['jpg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
        if (!isset($map[$extension]) || $map[$extension] !== $mime || $size < 1 || $size > self::MAX_BYTES) throw new InvalidArgumentException('MEDIA_UPLOAD_INVALID');
        return [$mime, $extension];
    }

    private function public_asset(array $asset): array {
        return ['media_id' => $asset['media_id'], 'state' => $asset['state'], 'error_code' => $asset['error_code'], 'source_key' => $asset['source_key'], 'variant_urls' => $asset['state'] === 'ready' ? $this->variant_urls($asset['media_id']) : []];
    }
    private function variant_urls(string $media_id): array { $urls = []; foreach (array_keys(self::VARIANTS) as $name) $urls[pathinfo($name, PATHINFO_FILENAME)] = self::public_url($media_id, $name); return $urls; }
    private static function public_url(string $media_id, string $name): string { return 'https://media.teachers.net/' . rawurlencode($media_id) . '/' . rawurlencode($name); }
}
