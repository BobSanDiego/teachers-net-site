<?php
defined('ABSPATH') || exit;

/** Explicit channel/frequency state and bounded legacy evidence reconciliation. */
final class TNet_Community_Notification_Preference_Service {
    public const FREQUENCIES = ['immediate','daily','weekly','never'];
    public const CHANNELS = ['bell','email'];
    public const CATEGORIES = ['community_activity','community_reply'];
    private array $tables;

    public function __construct() { $this->tables = TNet_Community_Schema::table_names(); }

    public function get(int $user_id, string $category, string $channel): ?array {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tables['preferences']} WHERE user_id=%s AND category=%s AND channel=%s LIMIT 1", 'user:' . $user_id, $category, $channel), ARRAY_A) ?: null;
    }

    public function set(int $user_id, string $category, string $channel, string $frequency, string $actor = '', array $provenance = []): array {
        if ($user_id < 1 || !in_array($category, self::CATEGORIES, true) || !in_array($channel, self::CHANNELS, true) || !in_array($frequency, self::FREQUENCIES, true)) return ['accepted'=>false, 'reason_code'=>'PREFERENCE_INPUT_UNSUPPORTED'];
        global $wpdb;
        $user = 'user:' . $user_id; $actor = $actor ?: $user; $now = current_time('mysql', true); $existing = $this->get($user_id, $category, $channel);
        $id = $existing['preference_id'] ?? ('preference:' . substr(hash('sha256', $user . '|' . $category . '|' . $channel), 0, 64));
        $wpdb->query('START TRANSACTION');
        try {
            if ($existing) { if (false === $wpdb->update($this->tables['preferences'], ['frequency'=>$frequency,'updated_at'=>$now,'provenance_json'=>$provenance ? wp_json_encode($provenance) : $existing['provenance_json']], ['id'=>(int)$existing['id']], ['%s','%s','%s'], ['%d'])) throw new RuntimeException('PREFERENCE_WRITE_FAILED'); }
            else { $row = ['preference_id'=>$id,'user_id'=>$user,'category'=>$category,'channel'=>$channel,'frequency'=>$frequency,'state'=>'explicit','source_namespace'=>$provenance['source_namespace'] ?? null,'provenance_json'=>$provenance ? wp_json_encode($provenance) : null,'created_at'=>$now,'updated_at'=>$now]; if (false === $wpdb->insert($this->tables['preferences'], $row, array_fill(0, count($row), '%s'))) throw new RuntimeException('PREFERENCE_WRITE_FAILED'); }
            $audit = ['preference_id'=>$id,'action'=>'set','actor_id'=>$actor,'previous_frequency'=>$existing['frequency'] ?? null,'new_frequency'=>$frequency,'reason'=>'explicit_preference_change','provenance_json'=>$provenance ? wp_json_encode($provenance) : null,'created_at'=>$now];
            if (false === $wpdb->insert($this->tables['preference_audit'], $audit, array_fill(0, count($audit), '%s'))) throw new RuntimeException('PREFERENCE_AUDIT_WRITE_FAILED');
            $wpdb->query('COMMIT'); return ['accepted'=>true, 'idempotent'=>(bool)$existing && $existing['frequency'] === $frequency, 'preference'=>$this->get($user_id, $category, $channel)];
        } catch (Throwable $error) { $wpdb->query('ROLLBACK'); return ['accepted'=>false, 'reason_code'=>$error->getMessage()]; }
    }

    public function reconcile(array $evidence, string $rule_version = 'relationship-notification-preference-v1'): array {
        foreach (['source_namespace','source_key','source_value'] as $field) if (!array_key_exists($field, $evidence)) return ['accepted'=>false, 'reason_code'=>'LEGACY_PREFERENCE_' . strtoupper($field) . '_REQUIRED'];
        $classification = (string)($evidence['classification'] ?? '');
        $allowed = ['deterministically_mapped','explicit_confirmation_required','NO_EMAIL','suppressed'];
        if (!in_array($classification, $allowed, true)) return ['accepted'=>false, 'reason_code'=>'LEGACY_PREFERENCE_CLASSIFICATION_UNSUPPORTED'];
        global $wpdb; $source = $evidence['source_namespace'] . ':' . $evidence['source_key']; $id = 'preference-reconciliation:' . substr(hash('sha256', $source), 0, 64); $now = current_time('mysql', true);
        $row = ['reconciliation_id'=>$id,'user_id'=>!empty($evidence['user_id']) ? 'user:' . absint($evidence['user_id']) : null,'source_namespace'=>(string)$evidence['source_namespace'],'source_key'=>(string)$evidence['source_key'],'source_value_json'=>wp_json_encode($evidence['source_value']),'classification'=>$classification,'reason_code'=>(string)($evidence['reason_code'] ?? 'bounded_evidence'),'rule_version'=>$rule_version,'evidence_json'=>wp_json_encode($evidence['evidence'] ?? []),'created_at'=>$now];
        $wpdb->query('START TRANSACTION');
        try { $existing = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tables['preference_reconciliation']} WHERE source_namespace=%s AND source_key=%s", $row['source_namespace'], $row['source_key']), ARRAY_A); if ($existing) { if ($existing['source_value_json'] !== $row['source_value_json'] || $existing['classification'] !== $row['classification']) throw new RuntimeException('LEGACY_PREFERENCE_RECONCILIATION_CONFLICT'); $wpdb->query('COMMIT'); return ['accepted'=>true,'idempotent'=>true,'reconciliation'=>$existing]; } if (false === $wpdb->insert($this->tables['preference_reconciliation'], $row, array_fill(0, count($row), '%s'))) throw new RuntimeException('LEGACY_PREFERENCE_RECONCILIATION_WRITE_FAILED'); $wpdb->query('COMMIT'); return ['accepted'=>true,'idempotent'=>false,'reconciliation'=>$row]; } catch (Throwable $error) { $wpdb->query('ROLLBACK'); return ['accepted'=>false,'reason_code'=>$error->getMessage()]; }
    }

    public function add_suppression(int $user_id, string $channel, string $scope, string $reason, array $evidence, string $source = 'local:synthetic'): array {
        if ($user_id < 1 || $channel === '' || $scope === '' || !in_array($reason, ['unsubscribe','hard_bounce','complaint','administrative'], true)) return ['accepted'=>false,'reason_code'=>'SUPPRESSION_INPUT_UNSUPPORTED'];
        global $wpdb; $now=current_time('mysql',true); $id='suppression:'.substr(hash('sha256','user:'.$user_id.'|'.$channel.'|'.$scope.'|'.$reason),0,64); $row=['suppression_id'=>$id,'user_id'=>'user:'.$user_id,'channel'=>$channel,'scope'=>$scope,'reason_code'=>$reason,'state'=>'active','source_namespace'=>$source,'evidence_json'=>wp_json_encode($evidence),'effective_at'=>$now,'lifted_at'=>null];
        $exists=$wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tables['suppressions']} WHERE suppression_id=%s",$id),ARRAY_A); if($exists) return ['accepted'=>true,'idempotent'=>true,'suppression'=>$exists];
        if(false===$wpdb->insert($this->tables['suppressions'],$row,array_fill(0,count($row),'%s'))) return ['accepted'=>false,'reason_code'=>'SUPPRESSION_WRITE_FAILED']; return ['accepted'=>true,'idempotent'=>false,'suppression'=>$row];
    }

    public function email_suppression(int $user_id, string $category): ?array {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tables['suppressions']} WHERE user_id=%s AND channel='email' AND state='active' AND (scope='global' OR scope=%s) ORDER BY id ASC LIMIT 1", 'user:' . $user_id, $category), ARRAY_A) ?: null;
    }

    public function audit_preference(string $preference_id): array { global $wpdb; return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->tables['preference_audit']} WHERE preference_id=%s ORDER BY audit_id ASC", $preference_id), ARRAY_A) ?: []; }
}
