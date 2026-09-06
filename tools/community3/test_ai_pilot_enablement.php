<?php
defined('ABSPATH') || exit("WordPress bootstrap required\n");

require_once ABSPATH . 'wp-content/plugins/tnet-community/tnet-community.php';

TNet_Community_Schema::install();
global $wpdb;
$namespace = 'synthetic:ai-pilot-enablement';
$run = 'synthetic-ai-pilot-enablement-v1';
$rule = 'ai-pilot-enablement-v1';
$migration = TNet_Community_Schema::migration_table_names();
$core = TNet_Community_Schema::table_names();

$cleanup = static function () use ($wpdb, $namespace, $run, $migration, $core): void {
    $ids = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM {$core['posts']} WHERE idempotency_key LIKE %s", 'migration:' . $namespace . ':%')) ?: [];
    if ($ids) {
        $marks = implode(',', array_fill(0, count($ids), '%s'));
        $wpdb->query($wpdb->prepare("DELETE FROM {$core['events']} WHERE post_id IN ({$marks})", ...$ids));
        $wpdb->query($wpdb->prepare("DELETE FROM {$core['audit']} WHERE post_id IN ({$marks})", ...$ids));
        $wpdb->query($wpdb->prepare("DELETE FROM {$core['posts']} WHERE post_id IN ({$marks})", ...$ids));
    }
    foreach (['migration_audit','url_aliases','exceptions','migration_ledger'] as $name) $wpdb->query($wpdb->prepare("DELETE FROM {$migration[$name]} WHERE source_namespace=%s", $namespace));
    $wpdb->query($wpdb->prepare("DELETE FROM {$migration['board_maps']} WHERE source_namespace=%s", $namespace));
    $wpdb->query($wpdb->prepare("DELETE FROM {$migration['migration_runs']} WHERE run_id=%s", $run));
};
$cleanup();

$registry = new TNet_Community_Community_Registry();
$entity = $registry->ensure_ai_in_education();
$board = [
    'legacy_path_id' => '241',
    'legacy_local_path' => '/www/htdocs/mentors/ai-in-education/',
    'legacy_group_id' => '227',
    'community_id' => $entity['community_id'],
    'mapping_state' => 'explicit',
    'evidence_ref' => 'COMMUNITY3-AI-PILOT-READINESS001',
];
$source = static function (string $id, string $topic, string $type, string $status, string $disposition): array {
    $snapshot = ['fixture'=>true, 'post_id'=>$id, 'topic_id'=>$topic, 'type'=>$type, 'status'=>$status, 'path_id'=>'241', 'group_id'=>'227'];
    return [
        'source_namespace'=>'synthetic:ai-pilot-enablement', 'legacy_post_id'=>$id, 'legacy_topic_id'=>$topic, 'legacy_post_type'=>$type,
        'raw_status'=>$status, 'source_checksum'=>hash('sha256', wp_json_encode($snapshot)), 'identity_state'=>'historical_snapshot',
        'moderation_state'=>$status === '9' ? 'excluded_status_9' : 'clear', 'asset_state'=>'preserved_reference',
        'disposition'=>$disposition, 'reason_code'=>$status === '9' ? 'EXCLUDED_STATUS_9' : null, 'source_snapshot'=>$snapshot,
    ];
};
$alias = static function (array $source): array {
    return ['legacy_url'=>'https://example.invalid/legacy/' . rawurlencode($source['legacy_post_id']), 'intended_target_key'=>'legacy-topic:' . $source['legacy_topic_id'], 'verification_state'=>'synthetic-fixture', 'evidence_ref'=>'synthetic-fixture-only', 'route_state'=>'disabled'];
};
$author = static function (string $name, string $key): array { return ['state'=>'snapshot', 'legacy_identity_key'=>$key, 'legacy_user_id'=>'legacy-' . $key, 'legacy_login'=>'legacy-' . $key, 'display_name'=>$name]; };
$root = $source('fixture-root-100', 'fixture-topic-10', 'root', '0', 'MIGRATE_PUBLIC');
$reply = $source('fixture-reply-101', 'fixture-topic-10', 'reply', '0', 'MIGRATE_PUBLIC');
$excluded = $source('fixture-excluded-102', 'fixture-topic-11', 'root', '9', 'ARCHIVE_ONLY');
$unit = ['run_id'=>$run, 'rule_version'=>$rule, 'community_id'=>$entity['community_id'], 'board'=>array_merge($board, ['legacy_path_id'=>'fixture-241', 'legacy_local_path'=>'/fixture/ai-in-education/']), 'records'=>[
    ['source'=>$root, 'alias'=>$alias($root), 'historical_author'=>$author('Historical Teacher', 'author-a'), 'title'=>'Historical root fixture', 'body'=>'Root fixture body.', 'created_at'=>'2026-01-02 03:04:05'],
    ['source'=>$reply, 'alias'=>$alias($reply), 'historical_author'=>$author('Historical Reply Author', 'author-b'), 'title'=>'Historical root fixture', 'body'=>'Reply fixture body.', 'created_at'=>'2026-01-02 04:05:06'],
    ['source'=>$excluded, 'alias'=>$alias($excluded), 'historical_author'=>$author('Excluded Historical Author', 'author-c'), 'title'=>'Excluded fixture', 'body'=>'Must never become a target post.', 'created_at'=>'2026-01-02 05:06:07'],
]];

$app = new TNet_Community_Migration_Application();
$before_posts = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$core['posts']}");
$first = $app->apply($unit);
$after_first_posts = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$core['posts']}");
$root_target = $first['target_posts'][0]['post_id'] ?? '';
$thread = $root_target ? (new TNet_Community_Thread_View())->find($root_target) : null;
$ledger_rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$migration['migration_ledger']} WHERE source_namespace=%s ORDER BY legacy_post_id", $namespace), ARRAY_A) ?: [];
$alias_states = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT route_state FROM {$migration['url_aliases']} WHERE source_namespace=%s", $namespace));
$events = $root_target ? (new TNet_Community_Publisher_Repository())->get_events($root_target) : [];
$audit_before_rerun = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$migration['migration_audit']} WHERE source_namespace=%s", $namespace));
$rerun = $app->apply($unit);
$after_rerun_posts = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$core['posts']}");
$audit_after_rerun = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$migration['migration_audit']} WHERE source_namespace=%s", $namespace));

$failure_source = $source('fixture-failure-103', 'fixture-topic-12', 'root', '0', 'MIGRATE_PUBLIC');
$failure_unit = $unit;
$failure_unit['run_id'] = $run . '-failure';
$failure_unit['records'] = [[
    'source'=>$failure_source, 'alias'=>$alias($failure_source), 'historical_author'=>$author('Failure Fixture', 'author-d'), 'title'=>'Failure fixture', 'body'=>'Must roll back.', 'created_at'=>'2026-01-02 06:07:08',
]];
$failure = (new TNet_Community_Migration_Application(['audit'=>true]))->apply($failure_unit);
$failure_leaked = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$migration['migration_ledger']} WHERE source_namespace=%s AND legacy_post_id=%s", $namespace, 'fixture-failure-103'));

$rollback = $app->rollback_run($run);
$after_rollback_targets = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$core['posts']} WHERE idempotency_key LIKE 'migration:synthetic:ai-pilot-enablement:%'");
$retained_ledger = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$migration['migration_ledger']} WHERE source_namespace=%s", $namespace));
$rerun_after_rollback = $app->apply($unit);
$after_rollback_rerun = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$core['posts']} WHERE idempotency_key LIKE 'migration:synthetic:ai-pilot-enablement:%'");
$final_rollback = $app->rollback_run($run);
$cleanup();

$actual_map = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$migration['board_maps']} WHERE source_namespace=%s AND legacy_path_id=%s", 'legacy:chatpost', '241'), ARRAY_A);
$results = [
    'ai_identity_is_opaque_and_slugged' => $entity['community_id'] === TNet_Community_Community_Registry::AI_IN_EDUCATION_ID && $entity['slug'] === 'ai-in-education',
    'explicit_241_227_mapping' => $actual_map && $actual_map['legacy_group_id'] === '227' && $actual_map['community_id'] === $entity['community_id'],
    'atomic_root_reply_and_excluded_application' => !empty($first['accepted']) && $after_first_posts === $before_posts + 2 && count($first['excluded']) === 1,
    'historical_snapshot_rendering' => $thread && $thread['root']['_author_display'] === 'Historical Teacher' && ($thread['rows'][0]['_author_display'] ?? '') === 'Historical Reply Author',
    'distinct_topic_post_provenance' => ($ledger_rows[1]['legacy_post_id'] ?? '') !== ($ledger_rows[1]['legacy_topic_id'] ?? ''),
    'historical_event_policy_suppressed' => $events === [],
    'disabled_aliases' => $alias_states === ['disabled'],
    'excluded_has_no_target' => !empty($ledger_rows[0]) && $ledger_rows[0]['legacy_post_id'] === 'fixture-excluded-102' && $ledger_rows[0]['target_post_id'] === null,
    'rerun_idempotent' => !empty($rerun['accepted']) && $after_rerun_posts === $after_first_posts && $audit_after_rerun === $audit_before_rerun,
    'failure_rolls_back_target_and_ledger' => empty($failure['accepted']) && $failure_leaked === 0,
    'batch_rollback_retains_ledger' => !empty($rollback['accepted']) && $after_rollback_targets === 0 && $retained_ledger === 3,
    'rerun_after_rollback' => !empty($rerun_after_rollback['accepted']) && $after_rollback_rerun === 2 && !empty($final_rollback['accepted']),
    'fixture_cleanup' => (int) $wpdb->get_var("SELECT COUNT(*) FROM {$core['posts']} WHERE idempotency_key LIKE 'migration:synthetic:ai-pilot-enablement:%'") === 0 && (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$migration['migration_ledger']} WHERE source_namespace=%s", $namespace)) === 0,
];
echo wp_json_encode(['all_assertions'=>!in_array(false, $results, true), 'results'=>$results, 'first'=>$first, 'rerun'=>$rerun, 'rollback'=>$rollback, 'failure'=>$failure], JSON_PRETTY_PRINT) . "\n";
