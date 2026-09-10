<?php
defined('ABSPATH') || exit;

/** Durable, typed relationship state. Explicit follows never share rows with inferred participation. */
final class TNet_Community_Relationship_Repository {
    private array $tables;

    public function __construct() { $this->tables = TNet_Community_Schema::table_names(); }

    public function find(string $type, string $user_id, string $target_key): ?array {
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tables['relationships']} WHERE relationship_type=%s AND user_id=%s AND target_key=%s LIMIT 1", $type, $user_id, $target_key), ARRAY_A);
        return $row ?: null;
    }

    public function active_followers(string $type, string $target_key): array {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->tables['relationships']} WHERE relationship_type=%s AND target_key=%s AND state='active' ORDER BY id ASC", $type, $target_key), ARRAY_A) ?: [];
    }

    public function transition(string $type, string $user_id, string $target_key, array $target, string $next_state, string $actor_id, array $provenance = []): array {
        global $wpdb;
        $existing = $this->find($type, $user_id, $target_key);
        if ($existing && $existing['state'] === $next_state) return ['accepted'=>true, 'idempotent'=>true, 'relationship'=>$existing];
        $now = current_time('mysql', true);
        $relationship_id = 'relationship:' . substr(hash('sha256', $type . '|' . $user_id . '|' . $target_key), 0, 64);
        $wpdb->query('START TRANSACTION');
        try {
            if ($existing) {
                if (false === $wpdb->update($this->tables['relationships'], ['state'=>$next_state, 'state_changed_at'=>$now, 'updated_at'=>$now, 'provenance_json'=>$provenance ? wp_json_encode($provenance) : $existing['provenance_json']], ['id'=>(int)$existing['id']], ['%s','%s','%s','%s'], ['%d'])) throw new RuntimeException('RELATIONSHIP_WRITE_FAILED');
                $previous = (string) $existing['state'];
            } else {
                $row = ['relationship_id'=>$relationship_id,'relationship_type'=>$type,'user_id'=>$user_id,'community_id'=>$target['community_id'] ?? null,'thread_id'=>$target['thread_id'] ?? null,'target_key'=>$target_key,'state'=>$next_state,'provenance_json'=>$provenance ? wp_json_encode($provenance) : null,'created_at'=>$now,'state_changed_at'=>$now,'updated_at'=>$now];
                if (false === $wpdb->insert($this->tables['relationships'], $row, array_fill(0, count($row), '%s'))) throw new RuntimeException('RELATIONSHIP_WRITE_FAILED');
                $previous = null;
            }
            $audit = ['relationship_id'=>$relationship_id,'action'=>$next_state === 'active' ? 'activated' : 'deactivated','actor_id'=>$actor_id,'previous_state'=>$previous,'new_state'=>$next_state,'reason'=>$type,'provenance_json'=>$provenance ? wp_json_encode($provenance) : null,'created_at'=>$now];
            if (false === $wpdb->insert($this->tables['relationship_audit'], $audit, array_fill(0, count($audit), '%s'))) throw new RuntimeException('RELATIONSHIP_AUDIT_WRITE_FAILED');
            $wpdb->query('COMMIT');
            return ['accepted'=>true, 'idempotent'=>false, 'relationship'=>$this->find($type, $user_id, $target_key)];
        } catch (Throwable $error) { $wpdb->query('ROLLBACK'); return ['accepted'=>false, 'reason_code'=>$error->getMessage()]; }
    }

    public function audit(string $relationship_id): array {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->tables['relationship_audit']} WHERE relationship_id=%s ORDER BY audit_id ASC", $relationship_id), ARRAY_A) ?: [];
    }
}
