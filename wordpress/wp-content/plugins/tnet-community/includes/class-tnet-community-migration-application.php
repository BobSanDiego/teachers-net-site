<?php
defined('ABSPATH') || exit;

/**
 * Production-shaped local application seam. It coordinates target publication
 * with immutable migration evidence in one transaction; it does not discover
 * legacy source data or activate routes.
 */
final class TNet_Community_Migration_Application {
    private TNet_Community_Migration_Foundation_Repository $foundation;
    private TNet_Community_Community_Registry $communities;
    private array $failures;

    public function __construct(array $failures = []) {
        $this->foundation = new TNet_Community_Migration_Foundation_Repository();
        $this->communities = new TNet_Community_Community_Registry();
        $this->failures = $failures;
    }

    public function apply(array $unit): array {
        foreach (['run_id','rule_version','community_id','board','records'] as $field) if (empty($unit[$field])) throw new InvalidArgumentException('MIGRATION_UNIT_' . strtoupper($field) . '_REQUIRED');
        $community = $this->communities->find((string) $unit['community_id']);
        if (!$community) throw new RuntimeException('MIGRATION_COMMUNITY_UNRESOLVED');
        $records = (array) $unit['records'];
        usort($records, static fn(array $a, array $b): int => (($a['source']['legacy_post_type'] ?? '') === 'root' ? -1 : 1) <=> (($b['source']['legacy_post_type'] ?? '') === 'root' ? -1 : 1));
        $domain = new TNet_Community_Publisher_Domain();
        $publisher = new TNet_Community_Publisher_Repository($this->failures);
        $targets = [];
        $result = ['accepted'=>false, 'target_posts'=>[], 'excluded'=>[]];
        global $wpdb;
        $wpdb->query('START TRANSACTION');
        try {
            foreach ($records as $record) {
                $source = (array) ($record['source'] ?? []);
                $alias = (array) ($record['alias'] ?? []);
                $disposition = (string) ($source['disposition'] ?? '');
                if ($disposition !== 'MIGRATE_PUBLIC') {
                    $this->foundation->record_in_transaction($source, $unit['board'], $alias, $unit['run_id'], $unit['rule_version']);
                    $result['excluded'][] = (string) $source['legacy_post_id'];
                    continue;
                }
                $author = TNet_Community_Historical_Author::normalize((array) ($record['historical_author'] ?? []));
                $parent_id = null;
                if (($source['legacy_post_type'] ?? '') === 'reply') {
                    $parent_id = $targets[(string) $source['legacy_topic_id']] ?? null;
                    if (!$parent_id) throw new RuntimeException('MIGRATION_REPLY_ROOT_UNRESOLVED');
                }
                $draft = [
                    'submission_id' => 'migration:' . $source['source_namespace'] . ':' . $source['legacy_post_id'] . ':' . $source['source_checksum'],
                    'community_id' => $community['community_id'],
                    'author_id' => $author['target_author_id'],
                    'post_type' => $source['legacy_post_type'] === 'root' ? 'topic' : 'reply',
                    'parent_post_id' => $parent_id,
                    'title' => (string) ($record['title'] ?? 'Historical discussion'),
                    'body' => (string) ($record['body'] ?? ''),
                    'visibility' => 'public',
                    'publication_mode' => 'post_first',
                    'created_at' => (string) ($record['created_at'] ?? ''),
                    'compatibility_refs' => [
                        'historical_author' => array_merge($author['legacy_snapshot'], ['state'=>$author['state'], 'display_name'=>$author['display_name'], 'mapping_evidence'=>$author['mapping_evidence']]),
                        'legacy_source' => ['source_namespace'=>$source['source_namespace'], 'legacy_post_id'=>(string) $source['legacy_post_id'], 'legacy_topic_id'=>(string) $source['legacy_topic_id'], 'source_checksum'=>$source['source_checksum']],
                        'legacy_media_provenance' => (array) (($source['source_snapshot'] ?? [])['media_provenance'] ?? []),
                    ],
                    'audit_context' => ['actor_id'=>'system:migration', 'event_policy'=>'suppressed_historical', 'run_id'=>$unit['run_id']],
                ];
                $publication = $domain->publish($draft, [$community['community_id'] => $community['display_name']]);
                if (empty($publication['accepted'])) throw new RuntimeException((string) ($publication['reason_code'] ?? 'MIGRATION_PUBLICATION_REJECTED'));
                if ($parent_id) $publication['post']['conversation_root_id'] = $parent_id;
                $publication['event'] = null; // Historical import is audited but does not emit current-publication events.
                $persisted = $publisher->persist_publication_in_transaction($publication, ['actor_id'=>'system:migration', 'event_policy'=>'suppressed_historical', 'run_id'=>$unit['run_id']]);
                if (empty($persisted['accepted'])) throw new RuntimeException((string) ($persisted['reason_code'] ?? 'MIGRATION_TARGET_WRITE_FAILED'));
                $post = $persisted['post'];
                $source['target_community_id'] = $community['community_id'];
                $source['target_post_id'] = $post['post_id'];
                $source['target_thread_id'] = $post['thread_id'];
                $this->foundation->record_in_transaction($source, $unit['board'], $alias, $unit['run_id'], $unit['rule_version']);
                $this->foundation->assign_target_in_transaction($source, $community['community_id'], $post['post_id'], $post['thread_id'], $unit['run_id']);
                if (($source['legacy_post_type'] ?? '') === 'root') $targets[(string) $source['legacy_topic_id']] = $post['post_id'];
                $domain->seed_post($post);
                $result['target_posts'][] = $post;
            }
            $wpdb->query('COMMIT');
            $result['accepted'] = true;
            return $result;
        } catch (Throwable $error) {
            $wpdb->query('ROLLBACK');
            return ['accepted'=>false, 'reason_code'=>$error->getMessage(), 'target_posts'=>[], 'excluded'=>[]];
        }
    }

    /** Keep source/ledger/alias provenance; retract only target records created by one local run. */
    public function rollback_run(string $run_id): array {
        global $wpdb;
        $migration = $this->foundation->table_names();
        $posts = TNet_Community_Schema::table_names();
        $rows = $wpdb->get_results($wpdb->prepare("SELECT source_namespace, legacy_post_id, target_post_id FROM {$migration['migration_ledger']} WHERE first_run_id=%s AND target_post_id IS NOT NULL", $run_id), ARRAY_A) ?: [];
        $ids = array_values(array_filter(array_column($rows, 'target_post_id')));
        $wpdb->query('START TRANSACTION');
        try {
            if ($ids) {
                $marks = implode(',', array_fill(0, count($ids), '%s'));
                $wpdb->query($wpdb->prepare("DELETE FROM {$posts['events']} WHERE post_id IN ({$marks})", ...$ids));
                $wpdb->query($wpdb->prepare("DELETE FROM {$posts['audit']} WHERE post_id IN ({$marks})", ...$ids));
                $wpdb->query($wpdb->prepare("DELETE FROM {$posts['posts']} WHERE post_id IN ({$marks})", ...$ids));
            }
            if (false === $wpdb->query($wpdb->prepare("UPDATE {$migration['migration_ledger']} SET target_community_id=NULL, target_post_id=NULL, target_thread_id=NULL WHERE first_run_id=%s", $run_id))) throw new RuntimeException('MIGRATION_ROLLBACK_LEDGER_FAILED');
            if (false === $wpdb->query($wpdb->prepare("UPDATE {$migration['url_aliases']} a JOIN {$migration['migration_ledger']} l ON l.source_namespace=a.source_namespace AND l.legacy_post_id=a.legacy_post_id SET a.target_community_id=NULL, a.target_post_id=NULL, a.target_thread_id=NULL WHERE l.first_run_id=%s", $run_id))) throw new RuntimeException('MIGRATION_ROLLBACK_ALIAS_FAILED');
            foreach ($rows as $row) {
                if (false === $wpdb->insert($migration['migration_audit'], ['source_namespace'=>$row['source_namespace'], 'legacy_post_id'=>$row['legacy_post_id'], 'run_id'=>$run_id, 'action'=>'target_rolled_back', 'evidence_json'=>wp_json_encode(['target_post_id'=>$row['target_post_id']]), 'created_at'=>current_time('mysql', true)], array_fill(0, 6, '%s'))) throw new RuntimeException('MIGRATION_ROLLBACK_AUDIT_FAILED');
            }
            $wpdb->query('COMMIT');
            return ['accepted'=>true, 'removed_target_posts'=>count($ids), 'ledger_retained'=>count($rows)];
        } catch (Throwable $error) {
            $wpdb->query('ROLLBACK');
            return ['accepted'=>false, 'reason_code'=>$error->getMessage()];
        }
    }
}
