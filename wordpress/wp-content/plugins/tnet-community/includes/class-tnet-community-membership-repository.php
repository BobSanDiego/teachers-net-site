<?php
defined('ABSPATH') || exit;

/** Canonical opaque Community membership storage and bounded migration ledger. */
final class TNet_Community_Membership_Repository {
    private array $tables;

    public function __construct() { $this->tables = TNet_Community_Schema::table_names(); }

    public function find(string $community_id, string $user_id): ?array {
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tables['memberships']} WHERE community_id=%s AND user_id=%s LIMIT 1", $community_id, $user_id), ARRAY_A);
        return $row ?: null;
    }

    public function count_active(string $community_id): int {
        global $wpdb;
        return (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$this->tables['memberships']} WHERE community_id=%s AND state='active'", $community_id));
    }

    public function audit(string $membership_id): array {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->tables['membership_audit']} WHERE membership_id=%s ORDER BY audit_id ASC", $membership_id), ARRAY_A) ?: [];
    }

    public function join(string $community_id, string $user_id, string $actor_id): array {
        global $wpdb;
        $wpdb->query('START TRANSACTION');
        try { $result = $this->join_in_transaction($community_id, $user_id, $actor_id); $wpdb->query('COMMIT'); return $result; }
        catch (Throwable $error) { $wpdb->query('ROLLBACK'); return ['accepted'=>false, 'reason_code'=>$error->getMessage()]; }
    }

    public function leave(string $community_id, string $user_id, string $actor_id): array {
        global $wpdb;
        $wpdb->query('START TRANSACTION');
        try { $result = $this->leave_in_transaction($community_id, $user_id, $actor_id); $wpdb->query('COMMIT'); return $result; }
        catch (Throwable $error) { $wpdb->query('ROLLBACK'); return ['accepted'=>false, 'reason_code'=>$error->getMessage()]; }
    }

    public function join_in_transaction(string $community_id, string $user_id, string $actor_id, array $provenance = []): array {
        global $wpdb;
        $existing = $this->find($community_id, $user_id);
        $now = current_time('mysql', true);
        if ($existing && $existing['state'] === 'active') return ['accepted'=>true, 'idempotent'=>true, 'membership'=>$existing];
        if ($existing) {
            if (false === $wpdb->update($this->tables['memberships'], ['state'=>'active','state_changed_at'=>$now,'updated_at'=>$now], ['id'=>(int)$existing['id']], ['%s','%s','%s'], ['%d'])) throw new RuntimeException('MEMBERSHIP_JOIN_WRITE_FAILED');
            $this->append_audit($existing['membership_id'], 'joined', $actor_id, 'left', 'active', 'membership joined', $provenance);
            $membership = $this->find($community_id, $user_id);
            return ['accepted'=>true, 'idempotent'=>false, 'membership'=>$membership];
        }
        $membership_id = 'membership:' . substr(hash('sha256', $community_id . '|' . $user_id), 0, 48);
        $row = ['membership_id'=>$membership_id,'community_id'=>$community_id,'user_id'=>$user_id,'state'=>'active','joined_at'=>$now,'state_changed_at'=>$now,'source_namespace'=>$provenance['source_namespace'] ?? null,'source_membership_id'=>$provenance['source_membership_id'] ?? null,'legacy_group_id'=>isset($provenance['legacy_group_id']) ? (string)$provenance['legacy_group_id'] : null,'provenance_json'=>$provenance ? wp_json_encode($provenance) : null,'created_at'=>$now,'updated_at'=>$now];
        if (false === $wpdb->insert($this->tables['memberships'], $row, array_fill(0, count($row), '%s'))) throw new RuntimeException('MEMBERSHIP_JOIN_WRITE_FAILED');
        $this->append_audit($membership_id, 'joined', $actor_id, null, 'active', 'membership joined', $provenance);
        return ['accepted'=>true, 'idempotent'=>false, 'membership'=>$this->find($community_id, $user_id)];
    }

    public function leave_in_transaction(string $community_id, string $user_id, string $actor_id): array {
        global $wpdb;
        $existing = $this->find($community_id, $user_id);
        if (!$existing || $existing['state'] === 'left') return ['accepted'=>true, 'idempotent'=>true, 'membership'=>$existing];
        $now = current_time('mysql', true);
        if (false === $wpdb->update($this->tables['memberships'], ['state'=>'left','state_changed_at'=>$now,'updated_at'=>$now], ['id'=>(int)$existing['id']], ['%s','%s','%s'], ['%d'])) throw new RuntimeException('MEMBERSHIP_LEAVE_WRITE_FAILED');
        $this->append_audit($existing['membership_id'], 'left', $actor_id, 'active', 'left', 'membership left', []);
        return ['accepted'=>true, 'idempotent'=>false, 'membership'=>$this->find($community_id, $user_id)];
    }

    public function migrate(array $source, array $mapping, array $identity, string $run_id, string $rule_version): array {
        global $wpdb;
        foreach (['source_namespace','source_membership_id','legacy_group_id','source_checksum','source_state'] as $field) if (!array_key_exists($field, $source) || $source[$field] === '') throw new InvalidArgumentException('MEMBERSHIP_SOURCE_' . strtoupper($field) . '_REQUIRED');
        $mapping_state = (string)($mapping['mapping_state'] ?? 'unresolved');
        $identity_state = (string)($identity['identity_state'] ?? 'unresolved');
        $community_id = $mapping_state === 'explicit' ? (string)($mapping['community_id'] ?? '') : '';
        $canonical_user_id = $identity_state === 'resolved' ? (string)($identity['canonical_user_id'] ?? '') : '';
        $disposition = ($mapping_state === 'explicit' && $community_id !== '' && $identity_state === 'resolved' && $canonical_user_id !== '') ? 'MIGRATED' : 'UNRESOLVED';
        $reason = $disposition === 'MIGRATED' ? null : ($mapping_state !== 'explicit' ? 'LEGACY_GROUP_MAPPING_UNRESOLVED' : 'LEGACY_USER_IDENTITY_UNRESOLVED');
        $wpdb->query('START TRANSACTION');
        try {
            $ledger = $this->migration_row($source['source_namespace'], (string)$source['source_membership_id']);
            if ($ledger) {
                if (!hash_equals((string)$ledger['source_checksum'], (string)$source['source_checksum']) || $ledger['run_id'] !== $run_id || $ledger['rule_version'] !== $rule_version) throw new RuntimeException('MEMBERSHIP_MIGRATION_CONFLICT');
                $wpdb->query('COMMIT');
                return ['accepted'=>true, 'idempotent'=>true, 'disposition'=>$ledger['disposition'], 'membership_id'=>$ledger['target_membership_id']];
            }
            $target_id = null;
            if ($disposition === 'MIGRATED') {
                $result = $this->join_in_transaction($community_id, $canonical_user_id, 'system:membership-migration', ['source_namespace'=>$source['source_namespace'],'source_membership_id'=>(string)$source['source_membership_id'],'legacy_group_id'=>(string)$source['legacy_group_id'],'source_user_id'=>$identity['source_user_id'] ?? null,'source_state'=>(string)$source['source_state'],'run_id'=>$run_id,'rule_version'=>$rule_version]);
                if (empty($result['accepted'])) throw new RuntimeException('MEMBERSHIP_MIGRATION_TARGET_FAILED');
                $target = $result['membership'];
                if ($source['source_state'] !== 'active') {
                    $left = $this->leave_in_transaction($community_id, $canonical_user_id, 'system:membership-migration');
                    if (empty($left['accepted'])) throw new RuntimeException('MEMBERSHIP_MIGRATION_STATE_FAILED');
                    $target = $left['membership'];
                }
                $target_id = $target['membership_id'];
            }
            $row = ['source_namespace'=>$source['source_namespace'],'source_membership_id'=>(string)$source['source_membership_id'],'legacy_group_id'=>(string)$source['legacy_group_id'],'legacy_user_id'=>isset($identity['source_user_id']) ? (string)$identity['source_user_id'] : null,'source_state'=>(string)$source['source_state'],'source_checksum'=>$source['source_checksum'],'mapping_state'=>$mapping_state,'identity_state'=>$identity_state,'disposition'=>$disposition,'reason_code'=>$reason,'community_id'=>$community_id ?: null,'canonical_user_id'=>$canonical_user_id ?: null,'target_membership_id'=>$target_id,'run_id'=>$run_id,'rule_version'=>$rule_version,'source_snapshot_json'=>wp_json_encode($source['source_snapshot'] ?? $source),'created_at'=>current_time('mysql', true),'updated_at'=>current_time('mysql', true)];
            if (false === $wpdb->insert($this->tables['membership_migrations'], $row, array_fill(0, count($row), '%s'))) throw new RuntimeException('MEMBERSHIP_MIGRATION_LEDGER_FAILED');
            $wpdb->query('COMMIT');
            return ['accepted'=>true, 'idempotent'=>false, 'disposition'=>$disposition, 'membership_id'=>$target_id, 'reason_code'=>$reason];
        } catch (Throwable $error) { $wpdb->query('ROLLBACK'); return ['accepted'=>false, 'reason_code'=>$error->getMessage()]; }
    }

    public function migration_row(string $namespace, string $source_id): ?array {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tables['membership_migrations']} WHERE source_namespace=%s AND source_membership_id=%s LIMIT 1", $namespace, $source_id), ARRAY_A) ?: null;
    }

    public function migration_rows(string $run_id): array {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->tables['membership_migrations']} WHERE run_id=%s ORDER BY id ASC", $run_id), ARRAY_A) ?: [];
    }

    public function rollback_migration_run(string $run_id): array {
        global $wpdb;
        $rows = $this->migration_rows($run_id);
        $wpdb->query('START TRANSACTION');
        try {
            foreach ($rows as $row) {
                if (!empty($row['target_membership_id'])) {
                    $membership = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tables['memberships']} WHERE membership_id=%s", $row['target_membership_id']), ARRAY_A);
                    if ($membership && ($membership['source_membership_id'] ?? '') === $row['source_membership_id']) {
                        $wpdb->delete($this->tables['membership_audit'], ['membership_id'=>$row['target_membership_id']], ['%s']);
                        $wpdb->delete($this->tables['memberships'], ['membership_id'=>$row['target_membership_id']], ['%s']);
                    }
                }
                $wpdb->update($this->tables['membership_migrations'], ['disposition'=>'ROLLED_BACK','target_membership_id'=>null,'updated_at'=>current_time('mysql', true)], ['id'=>(int)$row['id']], ['%s','%s','%s'], ['%d']);
            }
            $wpdb->query('COMMIT');
            return ['accepted'=>true, 'rolled_back'=>count($rows)];
        } catch (Throwable $error) { $wpdb->query('ROLLBACK'); return ['accepted'=>false, 'reason_code'=>$error->getMessage()]; }
    }

    private function append_audit(string $membership_id, string $action, string $actor_id, ?string $previous, string $next, string $reason, array $evidence): void {
        global $wpdb;
        if (false === $wpdb->insert($this->tables['membership_audit'], ['membership_id'=>$membership_id,'action'=>$action,'actor_id'=>$actor_id,'previous_state'=>$previous,'new_state'=>$next,'reason'=>$reason,'evidence_json'=>$evidence ? wp_json_encode($evidence) : null,'created_at'=>current_time('mysql', true)], array_fill(0, 9, '%s'))) throw new RuntimeException('MEMBERSHIP_AUDIT_WRITE_FAILED');
    }
}
