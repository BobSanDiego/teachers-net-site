<?php
defined('ABSPATH') || exit;

/**
 * Idempotent, non-publishing foundation for importing historical chatboard
 * evidence. This repository never writes Community posts, enables aliases, or
 * touches legacy source rows.
 */
final class TNet_Community_Migration_Foundation_Repository {
    private array $tables;

    public function __construct() {
        $this->tables = TNet_Community_Schema::migration_table_names();
    }

    public function process(array $source, array $board, array $alias, string $run_id, string $rule_version): array {
        $this->validate_source($source, $run_id, $rule_version);
        global $wpdb;
        $wpdb->query('START TRANSACTION');
        try {
            $this->ensure_run($run_id, $rule_version);
            $board_id = $this->upsert_board_map($source['source_namespace'], $board);
            $ledger_id = $this->upsert_ledger($source, $board_id, $run_id, $rule_version);
            $alias_id = $this->upsert_alias($source, $alias);
            foreach ((array) ($source['exceptions'] ?? []) as $exception) {
                $this->upsert_exception($source, $exception, $run_id);
            }
            $wpdb->query('COMMIT');
            return ['ledger_id' => $ledger_id, 'board_map_id' => $board_id, 'alias_id' => $alias_id];
        } catch (Throwable $error) {
            $wpdb->query('ROLLBACK');
            throw $error;
        }
    }

    public function reconciliation(array $source_keys): array {
        global $wpdb;
        $ledger = $this->tables['migration_ledger'];
        $aliases = $this->tables['url_aliases'];
        $exceptions = $this->tables['exceptions'];
        $source_count = 0;
        $missing = [];
        foreach ($source_keys as $key) {
            $source_count++;
            $found = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM {$ledger} WHERE source_namespace=%s AND legacy_post_id=%s",
                $key['source_namespace'], (string) $key['legacy_post_id']
            ));
            if (!$found) $missing[] = $key;
        }
        $ledger_count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$ledger}");
        return [
            'source_count' => $source_count,
            'ledger_count' => $ledger_count,
            'alias_count' => (int) $wpdb->get_var("SELECT COUNT(*) FROM {$aliases}"),
            'exception_count' => (int) $wpdb->get_var("SELECT COUNT(*) FROM {$exceptions}"),
            'status_9_public_count' => (int) $wpdb->get_var("SELECT COUNT(*) FROM {$ledger} WHERE raw_status='9' AND disposition='MIGRATE_PUBLIC'"),
            'missing_source_keys' => $missing,
            'reconciled' => !$missing && $ledger_count === $source_count,
        ];
    }

    public function counts(): array {
        global $wpdb;
        $counts = [];
        foreach ($this->tables as $name => $table) {
            $counts[$name] = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table}");
        }
        return $counts;
    }

    private function validate_source(array $source, string $run_id, string $rule_version): void {
        foreach (['source_namespace', 'legacy_post_id', 'legacy_topic_id', 'legacy_post_type', 'source_checksum', 'identity_state', 'moderation_state', 'asset_state', 'disposition'] as $field) {
            if (empty($source[$field])) throw new InvalidArgumentException('SOURCE_' . strtoupper($field) . '_REQUIRED');
        }
        if (!in_array($source['legacy_post_type'], ['root', 'reply'], true)) throw new InvalidArgumentException('LEGACY_POST_TYPE_INVALID');
        if (!preg_match('/^[a-f0-9]{64}$/', (string) $source['source_checksum'])) throw new InvalidArgumentException('SOURCE_CHECKSUM_INVALID');
        if ($run_id === '' || $rule_version === '') throw new InvalidArgumentException('RUN_OR_RULE_VERSION_REQUIRED');
        if ((string) ($source['raw_status'] ?? '') === '9' && $source['disposition'] === 'MIGRATE_PUBLIC') {
            throw new InvalidArgumentException('STATUS_9_REQUIRES_NONPUBLIC_DISPOSITION');
        }
    }

    private function ensure_run(string $run_id, string $rule_version): void {
        global $wpdb;
        $existing = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tables['migration_runs']} WHERE run_id=%s", $run_id), ARRAY_A);
        if ($existing) {
            if ($existing['rule_version'] !== $rule_version) throw new RuntimeException('MIGRATION_RUN_RULE_VERSION_CONFLICT');
            return;
        }
        $ok = $wpdb->insert($this->tables['migration_runs'], [
            'run_id' => $run_id, 'rule_version' => $rule_version, 'run_state' => 'foundation',
            'metadata_json' => wp_json_encode(['local_only' => true, 'route_activation' => 'disabled']),
            'created_at' => current_time('mysql', true),
        ]);
        if ($ok === false) throw new RuntimeException('MIGRATION_RUN_WRITE_FAILED');
    }

    private function upsert_board_map(string $namespace, array $board): int {
        foreach (['legacy_path_id', 'legacy_local_path', 'legacy_group_id', 'community_id', 'mapping_state', 'evidence_ref'] as $field) {
            if (!array_key_exists($field, $board) || $board[$field] === '') throw new InvalidArgumentException('BOARD_' . strtoupper($field) . '_REQUIRED');
        }
        global $wpdb;
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->tables['board_maps']} WHERE source_namespace=%s AND legacy_path_id=%s",
            $namespace, (string) $board['legacy_path_id']
        ), ARRAY_A);
        $checksum = hash('sha256', wp_json_encode([
            'legacy_local_path' => $board['legacy_local_path'], 'legacy_group_id' => (string) $board['legacy_group_id'],
            'community_id' => $board['community_id'], 'mapping_state' => $board['mapping_state'], 'evidence_ref' => $board['evidence_ref'],
        ]));
        if ($existing) {
            if (!hash_equals($existing['mapping_checksum'], $checksum)) throw new RuntimeException('BOARD_MAPPING_CONFLICT');
            return (int) $existing['id'];
        }
        $ok = $wpdb->insert($this->tables['board_maps'], [
            'source_namespace' => $namespace, 'legacy_path_id' => (string) $board['legacy_path_id'],
            'legacy_local_path' => $board['legacy_local_path'], 'legacy_group_id' => (string) $board['legacy_group_id'],
            'community_id' => $board['community_id'], 'mapping_state' => $board['mapping_state'],
            'evidence_ref' => $board['evidence_ref'], 'mapping_checksum' => $checksum,
            'created_at' => current_time('mysql', true),
        ]);
        if ($ok === false) throw new RuntimeException('BOARD_MAPPING_WRITE_FAILED');
        return (int) $wpdb->insert_id;
    }

    private function upsert_ledger(array $source, int $board_id, string $run_id, string $rule_version): int {
        global $wpdb;
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->tables['migration_ledger']} WHERE source_namespace=%s AND legacy_post_id=%s",
            $source['source_namespace'], (string) $source['legacy_post_id']
        ), ARRAY_A);
        $snapshot = wp_json_encode($source['source_snapshot'] ?? []);
        if ($existing) {
            $same = $existing['legacy_topic_id'] === (string) $source['legacy_topic_id']
                && $existing['legacy_post_type'] === $source['legacy_post_type']
                && hash_equals($existing['source_checksum'], $source['source_checksum'])
                && (int) $existing['board_map_id'] === $board_id
                && $existing['disposition'] === $source['disposition'];
            if (!$same) throw new RuntimeException('SOURCE_LEDGER_CONFLICT');
            return (int) $existing['id'];
        }
        $row = [
            'source_namespace' => $source['source_namespace'], 'legacy_post_id' => (string) $source['legacy_post_id'],
            'legacy_topic_id' => (string) $source['legacy_topic_id'], 'legacy_post_type' => $source['legacy_post_type'],
            'raw_status' => isset($source['raw_status']) ? (string) $source['raw_status'] : null,
            'source_checksum' => $source['source_checksum'], 'board_map_id' => $board_id,
            'identity_state' => $source['identity_state'], 'moderation_state' => $source['moderation_state'],
            'asset_state' => $source['asset_state'], 'disposition' => $source['disposition'],
            'reason_code' => $source['reason_code'] ?? null, 'target_community_id' => $source['target_community_id'] ?? null,
            'target_post_id' => $source['target_post_id'] ?? null, 'target_thread_id' => $source['target_thread_id'] ?? null,
            'first_run_id' => $run_id, 'rule_version' => $rule_version, 'source_snapshot_json' => $snapshot,
            'created_at' => current_time('mysql', true),
        ];
        if ($wpdb->insert($this->tables['migration_ledger'], $row) === false) throw new RuntimeException('SOURCE_LEDGER_WRITE_FAILED');
        $this->append_audit($source['source_namespace'], (string) $source['legacy_post_id'], $run_id, 'foundation_recorded', [
            'source_checksum' => $source['source_checksum'], 'disposition' => $source['disposition'], 'rule_version' => $rule_version,
        ]);
        return (int) $wpdb->insert_id;
    }

    private function upsert_alias(array $source, array $alias): int {
        foreach (['legacy_url', 'intended_target_key', 'verification_state', 'evidence_ref'] as $field) {
            if (empty($alias[$field])) throw new InvalidArgumentException('ALIAS_' . strtoupper($field) . '_REQUIRED');
        }
        if (($alias['route_state'] ?? 'disabled') !== 'disabled') throw new InvalidArgumentException('ALIAS_ROUTE_ACTIVATION_FORBIDDEN');
        $hash = hash('sha256', $alias['legacy_url']);
        global $wpdb;
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->tables['url_aliases']} WHERE source_namespace=%s AND legacy_url_hash=%s",
            $source['source_namespace'], $hash
        ), ARRAY_A);
        if ($existing) {
            $same = $existing['legacy_post_id'] === (string) $source['legacy_post_id']
                && $existing['intended_target_key'] === $alias['intended_target_key']
                && $existing['route_state'] === 'disabled';
            if (!$same) throw new RuntimeException('URL_ALIAS_CONFLICT');
            return (int) $existing['id'];
        }
        $ok = $wpdb->insert($this->tables['url_aliases'], [
            'source_namespace' => $source['source_namespace'], 'legacy_url_hash' => $hash,
            'legacy_url' => $alias['legacy_url'], 'legacy_post_id' => (string) $source['legacy_post_id'],
            'legacy_topic_id' => (string) $source['legacy_topic_id'], 'target_community_id' => $source['target_community_id'] ?? null,
            'target_post_id' => $source['target_post_id'] ?? null, 'target_thread_id' => $source['target_thread_id'] ?? null,
            'intended_target_key' => $alias['intended_target_key'], 'alias_state' => 'candidate', 'route_state' => 'disabled',
            'verification_state' => $alias['verification_state'], 'evidence_ref' => $alias['evidence_ref'],
            'created_at' => current_time('mysql', true),
        ]);
        if ($ok === false) throw new RuntimeException('URL_ALIAS_WRITE_FAILED');
        return (int) $wpdb->insert_id;
    }

    private function upsert_exception(array $source, array $exception, string $run_id): void {
        if (empty($exception['code']) || empty($exception['state'])) throw new InvalidArgumentException('EXCEPTION_CODE_AND_STATE_REQUIRED');
        global $wpdb;
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$this->tables['exceptions']} WHERE source_namespace=%s AND legacy_post_id=%s AND exception_code=%s",
            $source['source_namespace'], (string) $source['legacy_post_id'], $exception['code']
        ));
        if ($existing) return;
        $ok = $wpdb->insert($this->tables['exceptions'], [
            'source_namespace' => $source['source_namespace'], 'legacy_post_id' => (string) $source['legacy_post_id'],
            'exception_code' => $exception['code'], 'exception_state' => $exception['state'],
            'evidence_json' => wp_json_encode($exception['evidence'] ?? []), 'first_run_id' => $run_id,
            'created_at' => current_time('mysql', true),
        ]);
        if ($ok === false) throw new RuntimeException('EXCEPTION_WRITE_FAILED');
    }

    private function append_audit(string $namespace, string $post_id, string $run_id, string $action, array $evidence): void {
        global $wpdb;
        if ($wpdb->insert($this->tables['migration_audit'], [
            'source_namespace' => $namespace, 'legacy_post_id' => $post_id, 'run_id' => $run_id,
            'action' => $action, 'evidence_json' => wp_json_encode($evidence), 'created_at' => current_time('mysql', true),
        ]) === false) throw new RuntimeException('MIGRATION_AUDIT_WRITE_FAILED');
    }
}
