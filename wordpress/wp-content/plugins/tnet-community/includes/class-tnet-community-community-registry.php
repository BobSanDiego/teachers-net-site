<?php
defined('ABSPATH') || exit;

/** Governed, opaque Community identity registry. It never derives identity from legacy ids. */
final class TNet_Community_Community_Registry {
    public const AI_IN_EDUCATION_ID = 'community:60e1ab24-56c6-4a16-8b2b-a5a0f9854ea4';

    public function ensure_ai_in_education(): array {
        return $this->ensure([
            'community_id' => self::AI_IN_EDUCATION_ID,
            'slug' => 'ai-in-education',
            'display_name' => 'AI in Education',
            'lifecycle_state' => 'pilot-enabled',
            'visibility' => 'public',
            'compatibility' => [
                'source_namespace' => 'legacy:chatpost',
                'legacy_path_id' => '241',
                'legacy_group_id' => '227',
                'mapping_state' => 'explicit',
                'evidence_ref' => 'COMMUNITY3-AI-PILOT-READINESS001',
            ],
        ]);
    }

    public function ensure(array $entity): array {
        foreach (['community_id','slug','display_name','lifecycle_state','visibility'] as $field) if (empty($entity[$field])) throw new InvalidArgumentException('COMMUNITY_' . strtoupper($field) . '_REQUIRED');
        global $wpdb;
        $tables = TNet_Community_Schema::table_names();
        $compatibility = (array) ($entity['compatibility'] ?? []);
        $checksum = wp_json_encode($compatibility);
        $wpdb->query('START TRANSACTION');
        try {
            $existing = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tables['communities']} WHERE community_id=%s", $entity['community_id']), ARRAY_A);
            if ($existing) {
                if ($existing['slug'] !== $entity['slug'] || $existing['compatibility_json'] !== $checksum) throw new RuntimeException('COMMUNITY_IDENTITY_CONFLICT');
            } else {
                $now = current_time('mysql', true);
                if (false === $wpdb->insert($tables['communities'], ['community_id'=>$entity['community_id'], 'slug'=>$entity['slug'], 'display_name'=>$entity['display_name'], 'lifecycle_state'=>$entity['lifecycle_state'], 'visibility'=>$entity['visibility'], 'compatibility_json'=>$checksum, 'created_at'=>$now, 'updated_at'=>$now], array_fill(0, 8, '%s'))) throw new RuntimeException('COMMUNITY_IDENTITY_WRITE_FAILED');
            }
            if (!empty($compatibility['source_namespace'])) {
                (new TNet_Community_Migration_Foundation_Repository())->ensure_board_map_in_transaction((string) $compatibility['source_namespace'], [
                    'legacy_path_id' => (string) $compatibility['legacy_path_id'],
                    'legacy_local_path' => '/www/htdocs/mentors/ai-in-education/',
                    'legacy_group_id' => (string) $compatibility['legacy_group_id'],
                    'community_id' => (string) $entity['community_id'],
                    'mapping_state' => (string) $compatibility['mapping_state'],
                    'evidence_ref' => (string) $compatibility['evidence_ref'],
                ]);
            }
            $wpdb->query('COMMIT');
            return $this->find((string) $entity['community_id']) ?? throw new RuntimeException('COMMUNITY_IDENTITY_READ_FAILED');
        } catch (Throwable $error) {
            $wpdb->query('ROLLBACK');
            throw $error;
        }
    }

    public function find(string $community_id): ?array {
        global $wpdb;
        $table = TNet_Community_Schema::table_names()['communities'];
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE community_id=%s", $community_id), ARRAY_A);
        if (!$row) return null;
        $row['compatibility'] = json_decode((string) $row['compatibility_json'], true) ?: [];
        return $row;
    }

    public function find_by_slug(string $slug): ?array {
        global $wpdb;
        $table = TNet_Community_Schema::table_names()['communities'];
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE slug=%s", sanitize_title($slug)), ARRAY_A);
        if (!$row) return null;
        $row['compatibility'] = json_decode((string) $row['compatibility_json'], true) ?: [];
        return $row;
    }
}
