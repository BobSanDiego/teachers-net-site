<?php
defined('ABSPATH') || exit("WordPress bootstrap required\n");

require_once ABSPATH . 'wp-content/plugins/tnet-community/tnet-community.php';

/**
 * Deliberately narrow local-only pilot runner. It reads only the ratified eight
 * source IDs from the separate local compatibility database; it has no route,
 * no production hostname, and no source write path.
 */
final class TNet_Community_AI_Migration_Pilot_Runner {
    private const IDS = [464935, 464936, 464937, 464938, 464939, 464940, 464942, 464963];
    private const PUBLIC_IDS = [464935, 464938, 464963];
    private const RUN_ID = 'ai-in-education-local-pilot-v1';
    private const RULE_VERSION = 'ai-pilot-v1';

    public static function run(string $action): array {
        if (!TNet_Community_Runtime_Authority::is_local()) throw new RuntimeException('LOCAL_RUNTIME_REQUIRED');
        TNet_Community_Schema::install();
        $registry = new TNet_Community_Community_Registry();
        $entity = $registry->ensure_ai_in_education();
        $app = new TNet_Community_Migration_Application();
        if ($action === 'rollback') return self::rollback($app, $entity);
        if ($action === 'reset') return self::reset($app, $entity);
        $records = self::source_records();
        $unit = [
            'run_id' => self::RUN_ID,
            'rule_version' => self::RULE_VERSION,
            'community_id' => $entity['community_id'],
            'board' => self::board($entity['community_id']),
            'records' => $records,
        ];
        $result = $app->apply($unit);
        if (empty($result['accepted'])) throw new RuntimeException('PILOT_APPLICATION_FAILED:' . ($result['reason_code'] ?? 'unknown'));
        return self::verify($entity, $records, $result);
    }

    private static function source_records(): array {
        $legacy = new wpdb('root', 'root', 'db', 'ddev-teachers-net-live-db');
        $legacy->suppress_errors(true);
        if (!$legacy->check_connection()) throw new RuntimeException('LOCAL_LEGACY_SOURCE_UNAVAILABLE');
        $marks = implode(',', array_map('intval', self::IDS));
        $rows = $legacy->get_results("SELECT post_id, topic_id, post_type, status, parent_id, wordpress_id, wordpress_name, post_author, post_datetime, post_title, post_content, post_url, chatboard_url, link_url, image_url, video_url FROM tnet_chatposts WHERE post_id IN ({$marks}) ORDER BY post_id ASC", ARRAY_A) ?: [];
        if (count($rows) !== 8 || array_map('intval', array_column($rows, 'post_id')) !== self::IDS) throw new RuntimeException('AI_PILOT_SOURCE_CENSUS_MISMATCH');
        $meta_rows = $legacy->get_results("SELECT post_id,image_name,imglink,image_width,image_height,og_title,og_description,og_site_name,site_domain,og_url,og_image,og_type,author_id,datetime FROM tnet_chatposts_meta WHERE post_id IN ({$marks})", ARRAY_A) ?: [];
        $meta = [];
        foreach ($meta_rows as $row) $meta[(string) $row['post_id']] = $row;
        $records = [];
        foreach ($rows as $row) {
            $id = (int) $row['post_id'];
            $public = in_array($id, self::PUBLIC_IDS, true);
            if ($public !== ((int) $row['status'] === 0)) throw new RuntimeException('AI_PILOT_STATUS_DISPOSITION_MISMATCH');
            $snapshot = [
                'source_namespace'=>'legacy:chatpost', 'legacy_post_id'=>(string) $row['post_id'], 'legacy_topic_id'=>(string) $row['topic_id'],
                'legacy_post_type'=>$row['post_type'] === 'reply' ? 'reply' : 'root', 'raw_status'=>(string) $row['status'],
                'parent_id'=>(string) $row['parent_id'], 'wordpress_id'=>(string) $row['wordpress_id'], 'wordpress_name'=>(string) $row['wordpress_name'],
                'post_author'=>(string) $row['post_author'], 'post_datetime'=>(string) $row['post_datetime'], 'post_title'=>(string) $row['post_title'],
                'post_content'=>(string) $row['post_content'], 'post_url'=>(string) $row['post_url'], 'chatboard_url'=>(string) $row['chatboard_url'],
                'link_url'=>(string) $row['link_url'], 'image_url'=>(string) $row['image_url'], 'video_url'=>(string) $row['video_url'],
                'media_provenance'=>$meta[(string) $row['post_id']] ?? [],
            ];
            $source = [
                'source_namespace'=>'legacy:chatpost', 'legacy_post_id'=>(string) $row['post_id'], 'legacy_topic_id'=>(string) $row['topic_id'],
                'legacy_post_type'=>$snapshot['legacy_post_type'], 'raw_status'=>(string) $row['status'], 'source_checksum'=>hash('sha256', wp_json_encode($snapshot)),
                'identity_state'=>'historical_snapshot', 'moderation_state'=>$public ? 'clear' : 'excluded_status_9',
                'asset_state'=>empty($snapshot['media_provenance']) ? 'none' : 'preserved_reference',
                'disposition'=>$public ? 'MIGRATE_PUBLIC' : 'ARCHIVE_ONLY', 'reason_code'=>$public ? null : 'EXCLUDED_STATUS_9',
                'source_snapshot'=>$snapshot,
                'exceptions'=>$public ? [] : [['code'=>'STATUS_9_EXCLUDED','state'=>'recorded','evidence'=>['raw_status'=>'9','source_post_id'=>(string) $row['post_id']]]],
            ];
            $records[] = [
                'source'=>$source,
                'alias'=>['legacy_url'=>self::legacy_url((string) $row['post_url']), 'intended_target_key'=>'legacy-topic:' . $row['topic_id'], 'verification_state'=>'verified_local_source', 'evidence_ref'=>'COMMUNITY3-AI-PILOT-READINESS001', 'route_state'=>'disabled'],
                'historical_author'=>['state'=>'snapshot', 'legacy_identity_key'=>'legacy:wordpress:' . $row['wordpress_id'] . ':' . $row['wordpress_name'], 'legacy_user_id'=>(string) $row['wordpress_id'], 'legacy_login'=>(string) $row['wordpress_name'], 'display_name'=>(string) $row['post_author']],
                'title'=>(string) $row['post_title'], 'body'=>(string) $row['post_content'], 'created_at'=>(string) $row['post_datetime'],
            ];
        }
        return $records;
    }

    private static function board(string $community_id): array {
        return ['legacy_path_id'=>'241', 'legacy_local_path'=>'/www/htdocs/mentors/ai-in-education/', 'legacy_group_id'=>'227', 'community_id'=>$community_id, 'mapping_state'=>'explicit', 'evidence_ref'=>'COMMUNITY3-AI-PILOT-READINESS001'];
    }

    private static function legacy_url(string $raw): string {
        $raw = trim($raw);
        if (!preg_match('~^https?://~i', $raw)) $raw = 'https://' . $raw;
        return $raw;
    }

    private static function verify(array $entity, array $records, array $result): array {
        global $wpdb;
        $migration = TNet_Community_Schema::migration_table_names();
        $core = TNet_Community_Schema::table_names();
        $ledger = $wpdb->get_results($wpdb->prepare("SELECT legacy_post_id, legacy_topic_id, legacy_post_type, raw_status, disposition, target_post_id, target_thread_id FROM {$migration['migration_ledger']} WHERE source_namespace=%s ORDER BY CAST(legacy_post_id AS UNSIGNED)", 'legacy:chatpost'), ARRAY_A) ?: [];
        $aliases = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$migration['url_aliases']} WHERE source_namespace=%s", 'legacy:chatpost'));
        $targets = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$core['posts']} WHERE community_id=%s", $entity['community_id']));
        $reconciliation = (new TNet_Community_Migration_Foundation_Repository())->reconciliation(array_map(static fn(array $record): array => ['source_namespace'=>'legacy:chatpost','legacy_post_id'=>$record['source']['legacy_post_id']], $records));
        $status_nine_targets = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$migration['migration_ledger']} WHERE source_namespace='legacy:chatpost' AND raw_status='9' AND target_post_id IS NOT NULL");
        if (count($ledger) !== 8 || $aliases !== 8 || $targets !== 3 || $status_nine_targets !== 0 || empty($reconciliation['reconciled'])) throw new RuntimeException('AI_PILOT_RECONCILIATION_MISMATCH');
        return ['accepted'=>true, 'run_id'=>self::RUN_ID, 'target_posts'=>$result['target_posts'], 'excluded'=>$result['excluded'], 'ledger'=>$ledger, 'aliases'=>$aliases, 'reconciliation'=>$reconciliation, 'target_count'=>$targets, 'status_nine_targets'=>$status_nine_targets];
    }

    private static function rollback(TNet_Community_Migration_Application $app, array $entity): array {
        $before = (int) $GLOBALS['wpdb']->get_var($GLOBALS['wpdb']->prepare("SELECT COUNT(*) FROM " . TNet_Community_Schema::table_names()['posts'] . " WHERE community_id=%s", $entity['community_id']));
        $result = $app->rollback_run(self::RUN_ID);
        $after = (int) $GLOBALS['wpdb']->get_var($GLOBALS['wpdb']->prepare("SELECT COUNT(*) FROM " . TNet_Community_Schema::table_names()['posts'] . " WHERE community_id=%s", $entity['community_id']));
        if (empty($result['accepted']) || $before !== 3 || $after !== 0) throw new RuntimeException('AI_PILOT_ROLLBACK_MISMATCH');
        return ['accepted'=>true, 'before_target_count'=>$before, 'after_target_count'=>$after, 'rollback'=>$result];
    }

    /**
     * Recovery-only reset for a failed local pilot attempt.  It is deliberately
     * narrower than rollback: it may run only after targets are gone and removes
     * records tied to this exact run, never source data, board identity, or maps.
     */
    private static function reset(TNet_Community_Migration_Application $app, array $entity): array {
        self::rollback($app, $entity);
        global $wpdb;
        $migration = TNet_Community_Schema::migration_table_names();
        $namespace = 'legacy:chatpost';
        if (false === $wpdb->query($wpdb->prepare("DELETE FROM {$migration['migration_audit']} WHERE run_id=%s", self::RUN_ID))) throw new RuntimeException('AI_PILOT_RESET_FAILED:migration_audit');
        if (false === $wpdb->query($wpdb->prepare(
            "DELETE aliases FROM {$migration['url_aliases']} aliases INNER JOIN {$migration['migration_ledger']} ledger ON ledger.source_namespace=aliases.source_namespace AND ledger.legacy_post_id=aliases.legacy_post_id WHERE ledger.first_run_id=%s",
            self::RUN_ID
        ))) throw new RuntimeException('AI_PILOT_RESET_FAILED:url_aliases');
        if (false === $wpdb->query($wpdb->prepare("DELETE FROM {$migration['exceptions']} WHERE first_run_id=%s", self::RUN_ID))) throw new RuntimeException('AI_PILOT_RESET_FAILED:exceptions');
        if (false === $wpdb->query($wpdb->prepare("DELETE FROM {$migration['migration_ledger']} WHERE first_run_id=%s", self::RUN_ID))) throw new RuntimeException('AI_PILOT_RESET_FAILED:migration_ledger');
        if (false === $wpdb->query($wpdb->prepare("DELETE FROM {$migration['migration_runs']} WHERE run_id=%s", self::RUN_ID))) throw new RuntimeException('AI_PILOT_RESET_FAILED:migration_runs');
        return ['accepted'=>true, 'reset_run_id'=>self::RUN_ID, 'targets'=>0, 'pilot_rows_remaining'=>(int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$migration['migration_ledger']} WHERE source_namespace=%s AND first_run_id=%s", $namespace, self::RUN_ID))];
    }
}

$action = getenv('TNET_AI_PILOT_ACTION') ?: ($argv[1] ?? 'apply');
if (!in_array($action, ['apply','rollback','reset'], true)) throw new InvalidArgumentException('ACTION_MUST_BE_APPLY_OR_ROLLBACK_OR_RESET');
echo wp_json_encode(TNet_Community_AI_Migration_Pilot_Runner::run($action), JSON_PRETTY_PRINT) . "\n";
