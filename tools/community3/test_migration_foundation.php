<?php
defined('ABSPATH') || exit("WordPress bootstrap required\n");

require_once ABSPATH . 'wp-content/plugins/tnet-community/tnet-community.php';

TNet_Community_Schema::remove_migration_foundation();
TNet_Community_Schema::install();
$repository = new TNet_Community_Migration_Foundation_Repository();
$core_tables = TNet_Community_Schema::table_names();
global $wpdb;
$core_counts_before = [];
foreach ($core_tables as $name => $table) $core_counts_before[$name] = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table}");
$run = 'local-foundation-fixture-v1';
$rule = 'migration-foundation-v1';
$board = [
    'legacy_path_id' => '241',
    'legacy_local_path' => 'ai-in-education',
    'legacy_group_id' => '227',
    'community_id' => 'community:fixture-ai-in-education',
    'mapping_state' => 'verified',
    'evidence_ref' => 'local-fixture:path-241-to-group-227',
];

$source = static function (string $post_id, string $type, string $status = '0', string $disposition = 'MIGRATE_PUBLIC', array $exceptions = []): array {
    $snapshot = [
        'post_id' => $post_id,
        'topic_id' => '303873',
        'post_type' => $type,
        'status' => $status,
        'path_id' => '241',
        'group_id' => '227',
        'post_url' => "https://teachers.net/chatboard/topic303873/fixture-{$post_id}.html",
    ];
    return [
        'source_namespace' => 'legacy:chatpost',
        'legacy_post_id' => $post_id,
        'legacy_topic_id' => '303873',
        'legacy_post_type' => $type,
        'raw_status' => $status,
        'source_checksum' => hash('sha256', wp_json_encode($snapshot)),
        'identity_state' => $status === '9' ? 'unresolved' : 'resolved',
        'moderation_state' => $status === '9' ? 'excluded_status_9' : 'clear',
        'asset_state' => $status === '9' ? 'missing' : 'preserved_reference',
        'disposition' => $disposition,
        'reason_code' => $status === '9' ? 'EXCLUDED_STATUS_9' : null,
        'target_community_id' => 'community:fixture-ai-in-education',
        'source_snapshot' => $snapshot,
        'exceptions' => $exceptions,
    ];
};
$alias = static function (array $record): array {
    return [
        'legacy_url' => $record['source_snapshot']['post_url'],
        'intended_target_key' => 'legacy-topic:' . $record['legacy_topic_id'],
        'verification_state' => 'verified_source_candidate',
        'evidence_ref' => 'local-fixture:legacy-url-record',
        'route_state' => 'disabled',
    ];
};
$root = $source('464978', 'root');
$reply = $source('464979', 'reply');
$excluded = $source('464980', 'root', '9', 'ARCHIVE_ONLY', [
    ['code' => 'STATUS_9_EXCLUDED', 'state' => 'recorded', 'evidence' => ['raw_status' => '9']],
    ['code' => 'UNRESOLVED_IDENTITY', 'state' => 'review', 'evidence' => ['wordpress_id' => null]],
    ['code' => 'ASSET_REFERENCE_MISSING', 'state' => 'recorded', 'evidence' => ['asset' => 'unavailable']],
]);

$first = $repository->process($root, $board, $alias($root), $run, $rule);
$repository->process($reply, $board, $alias($reply), $run, $rule);
$repository->process($excluded, $board, $alias($excluded), $run, $rule);
$before_rerun = $repository->counts();
$rerun = $repository->process($root, $board, $alias($root), $run, $rule);
$after_rerun = $repository->counts();
$reconciliation = $repository->reconciliation([
    ['source_namespace' => 'legacy:chatpost', 'legacy_post_id' => '464978'],
    ['source_namespace' => 'legacy:chatpost', 'legacy_post_id' => '464979'],
    ['source_namespace' => 'legacy:chatpost', 'legacy_post_id' => '464980'],
]);

$expect_error = static function (callable $callback, string $expected): bool {
    try { $callback(); } catch (Throwable $error) { return $error->getMessage() === $expected; }
    return false;
};
$collision_checksum = $root;
$collision_checksum['source_checksum'] = str_repeat('a', 64);
$board_conflict = $board;
$board_conflict['legacy_group_id'] = '228';
$alias_conflict = $alias($root);
$alias_conflict['intended_target_key'] = 'legacy-topic:other';
$status_nine_public = $source('464981', 'root', '9', 'MIGRATE_PUBLIC');

$tables = TNet_Community_Schema::migration_table_names();
$stored_root = $wpdb->get_row($wpdb->prepare(
    "SELECT legacy_post_id, legacy_topic_id, board_map_id FROM {$tables['migration_ledger']} WHERE id=%d", $first['ledger_id']
), ARRAY_A);
$route_states = $wpdb->get_col("SELECT DISTINCT route_state FROM {$tables['url_aliases']}");
$table_exists = static function (string $table) use ($wpdb): bool {
    return (string) $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table)) === $table;
};
$all_foundation_tables_before_rollback = !in_array(false, array_map($table_exists, $tables), true);

$results = [
    'source_key_uniqueness' => $before_rerun === $after_rerun && $first === $rerun,
    'distinct_topic_and_post_provenance' => $stored_root['legacy_post_id'] === '464978' && $stored_root['legacy_topic_id'] === '303873' && $stored_root['legacy_post_id'] !== $stored_root['legacy_topic_id'],
    'explicit_path_group_mapping' => $board['legacy_path_id'] !== $board['legacy_group_id'] && $stored_root['board_map_id'] > 0,
    'alias_candidate_routing_disabled' => $route_states === ['disabled'],
    'exception_recording' => $before_rerun['exceptions'] === 3 && $reconciliation['status_9_public_count'] === 0,
    'reconciliation' => $reconciliation['reconciled'] && $reconciliation['ledger_count'] === 3 && $reconciliation['alias_count'] === 3,
    'source_collision_rejected' => $expect_error(fn() => $repository->process($collision_checksum, $board, $alias($collision_checksum), $run, $rule), 'SOURCE_LEDGER_CONFLICT'),
    'board_collision_rejected' => $expect_error(fn() => $repository->process($root, $board_conflict, $alias($root), $run, $rule), 'BOARD_MAPPING_CONFLICT'),
    'alias_collision_rejected' => $expect_error(fn() => $repository->process($root, $board, $alias_conflict, $run, $rule), 'URL_ALIAS_CONFLICT'),
    'status_nine_public_rejected' => $expect_error(fn() => $repository->process($status_nine_public, $board, $alias($status_nine_public), $run, $rule), 'STATUS_9_REQUIRES_NONPUBLIC_DISPOSITION'),
    'foundation_tables_present' => $all_foundation_tables_before_rollback,
];

TNet_Community_Schema::remove_migration_foundation();
$rollback_removed = !in_array(true, array_map($table_exists, $tables), true);
TNet_Community_Schema::install();
$reinstalled_empty = !in_array(false, array_map($table_exists, $tables), true)
    && array_sum($repository->counts()) === 0
    && get_option('tnet_community_schema_version') === TNet_Community_Schema::VERSION;
$results['rollback_and_reinstall'] = $rollback_removed && $reinstalled_empty;
$core_counts_after = [];
foreach ($core_tables as $name => $table) $core_counts_after[$name] = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table}");
$results['publisher_tables_unchanged'] = $core_counts_before === $core_counts_after;

echo wp_json_encode([
    'all_assertions' => !in_array(false, $results, true),
    'results' => $results,
    'counts_before_rerun' => $before_rerun,
    'counts_after_rerun' => $after_rerun,
    'reconciliation' => $reconciliation,
    'rollback_removed' => $rollback_removed,
    'reinstalled_empty' => $reinstalled_empty,
    'core_counts_before' => $core_counts_before,
    'core_counts_after' => $core_counts_after,
], JSON_PRETTY_PRINT) . "\n";
