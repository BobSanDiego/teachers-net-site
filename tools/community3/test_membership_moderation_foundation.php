<?php
// Run inside WordPress/DDEV with the integrated Community plugin loaded.
defined('ABSPATH') || exit("WordPress bootstrap required\n");

require_once ABSPATH . 'wp-content/plugins/tnet-community/tnet-community.php';
TNet_Community_Schema::install();
global $wpdb;

$core = TNet_Community_Schema::table_names();
$fixture_community = TNet_Community_Community_Registry::AI_IN_EDUCATION_ID;
$membership_namespace = 'synthetic:membership-foundation';
$migration_run = 'synthetic-membership-foundation-v1';
$post_key = 'membership-moderation-foundation-' . substr(hash('sha256', __FILE__), 0, 12);

$cleanup = static function () use ($wpdb, $core, $membership_namespace, $migration_run, $post_key): void {
    $report_ids = $wpdb->get_col($wpdb->prepare("SELECT report_id FROM {$core['reports']} WHERE idempotency_key LIKE %s", $post_key . '%')) ?: [];
    foreach ($report_ids as $report_id) {
        $wpdb->delete($core['report_audit'], ['report_id'=>$report_id], ['%s']);
        $wpdb->delete($core['reports'], ['report_id'=>$report_id], ['%s']);
    }
    $post_ids = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM {$core['posts']} WHERE idempotency_key=%s", $post_key)) ?: [];
    $thread_ids = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT thread_id FROM {$core['posts']} WHERE idempotency_key=%s", $post_key)) ?: [];
    foreach ($thread_ids as $thread_id) {
        $relationship_ids = $wpdb->get_col($wpdb->prepare("SELECT relationship_id FROM {$core['relationships']} WHERE target_key=%s", $thread_id)) ?: [];
        foreach ($relationship_ids as $relationship_id) $wpdb->delete($core['relationship_audit'], ['relationship_id'=>$relationship_id], ['%s']);
        $wpdb->delete($core['relationships'], ['target_key'=>$thread_id], ['%s']);
    }
    foreach ($post_ids as $post_id) {
        $wpdb->delete($core['events'], ['post_id'=>$post_id], ['%s']);
        $wpdb->delete($core['audit'], ['post_id'=>$post_id], ['%s']);
        $wpdb->delete($core['posts'], ['post_id'=>$post_id], ['%s']);
    }
    $membership_ids = $wpdb->get_col($wpdb->prepare("SELECT membership_id FROM {$core['memberships']} WHERE source_namespace=%s", $membership_namespace)) ?: [];
    foreach ($membership_ids as $membership_id) {
        $wpdb->delete($core['membership_audit'], ['membership_id'=>$membership_id], ['%s']);
        $wpdb->delete($core['memberships'], ['membership_id'=>$membership_id], ['%s']);
    }
    $wpdb->delete($core['membership_migrations'], ['source_namespace'=>$membership_namespace], ['%s']);
    $wpdb->delete($core['membership_migrations'], ['run_id'=>$migration_run], ['%s']);
    foreach (['user:991001','user:991002'] as $synthetic_user) {
        $ids = $wpdb->get_col($wpdb->prepare("SELECT membership_id FROM {$core['memberships']} WHERE community_id=%s AND user_id=%s", TNet_Community_Community_Registry::AI_IN_EDUCATION_ID, $synthetic_user)) ?: [];
        foreach ($ids as $membership_id) {
            $wpdb->delete($core['membership_audit'], ['membership_id'=>$membership_id], ['%s']);
            $wpdb->delete($core['memberships'], ['membership_id'=>$membership_id], ['%s']);
        }
    }
};
$cleanup();

$membership = new TNet_Community_Membership_Service();
$member_user = 'user:foundation-member';
$initial = $membership->state($fixture_community, 991001);
$joined = $membership->join($fixture_community, 991001);
$joined_again = $membership->join($fixture_community, 991001);
$left = $membership->leave($fixture_community, 991001);
$left_again = $membership->leave($fixture_community, 991001);
$reloaded = $membership->state($fixture_community, 991001);

$source_snapshot = ['source_membership_id'=>'legacy-membership-1','legacy_group_id'=>'227','legacy_user_id'=>'legacy-user-1','state'=>'active'];
$source = ['source_namespace'=>$membership_namespace,'source_membership_id'=>'legacy-membership-1','legacy_group_id'=>'227','source_state'=>'active','source_checksum'=>hash('sha256', wp_json_encode($source_snapshot)),'source_snapshot'=>$source_snapshot];
$mapping = ['mapping_state'=>'explicit','community_id'=>$fixture_community];
$identity = ['identity_state'=>'resolved','canonical_user_id'=>'user:991002','source_user_id'=>'legacy-user-1'];
$migrated = $membership->migrate($source, $mapping, $identity, $migration_run, 'membership-foundation-v1');
$migrated_again = $membership->migrate($source, $mapping, $identity, $migration_run, 'membership-foundation-v1');
$unresolved = $membership->migrate(['source_namespace'=>$membership_namespace,'source_membership_id'=>'legacy-membership-unresolved','legacy_group_id'=>'999','source_state'=>'active','source_checksum'=>hash('sha256','unresolved'),'source_snapshot'=>['fixture'=>true]], ['mapping_state'=>'unresolved'], ['identity_state'=>'unresolved'], $migration_run, 'membership-foundation-v1');
$rolled_back = $membership->rollback($migration_run);

wp_set_current_user(1);
$publication = (new TNet_Community_Publisher_Application())->publish_and_persist([
    'submission_id'=>$post_key,
    'community_id'=>$fixture_community,
    'author_id'=>'user:1',
    'post_type'=>'topic',
    'title'=>'Moderation foundation fixture',
    'body'=>'Local-only moderation foundation fixture.',
    'visibility'=>'public',
    'publication_mode'=>'post_first',
    'moderation_input'=>'clear',
    'compatibility_refs'=>['fixture'=>'membership-moderation-foundation'],
    'audit_context'=>['source'=>'local-test'],
], [$fixture_community=>['active'=>true]], ['actor_id'=>'user:1']);
$post_id = $publication['post']['post_id'] ?? '';
$moderation = new TNet_Community_Moderation_Service();
$reported = $moderation->report($post_id, 'user:991003', 'spam', 'synthetic report', $post_key . '-report');
$reported_again = $moderation->report($post_id, 'user:991003', 'spam', 'synthetic report', $post_key . '-report');
wp_set_current_user(0);
$forbidden = $moderation->queue();
wp_set_current_user(1);
$queue = $moderation->queue();
$report_id = $reported['report']['report_id'] ?? '';
$resolved = $moderation->resolve($report_id, 'user:1', 'action_taken', 'hidden');
$invalid = $moderation->resolve($report_id, 'user:1', 'action_taken', 'hidden');
$post_after = $post_id ? (new TNet_Community_Publisher_Repository())->find_post($post_id) : null;
$report_audit = $report_id ? $moderation->audit($report_id) : [];

$checks = [
    'initial_not_joined' => $initial['state'] === 'none' && $initial['joined'] === false,
    'join_persists_active' => !empty($joined['accepted']) && ($joined['membership']['state'] ?? '') === 'active',
    'repeat_join_idempotent' => !empty($joined_again['accepted']) && !empty($joined_again['idempotent']),
    'leave_persists_left' => !empty($left['accepted']) && ($left['membership']['state'] ?? '') === 'left',
    'repeat_leave_idempotent' => !empty($left_again['accepted']) && !empty($left_again['idempotent']),
    'reload_preserves_left' => $reloaded['state'] === 'left' && $reloaded['joined'] === false,
    'mapped_membership_has_target' => $migrated['disposition'] === 'MIGRATED' && !empty($migrated['membership_id']),
    'migration_rerun_idempotent' => !empty($migrated_again['idempotent']),
    'unresolved_mapping_recorded' => $unresolved['disposition'] === 'UNRESOLVED' && empty($unresolved['membership_id']),
    'migration_rollback_removes_target' => !empty($rolled_back['accepted']) && !$membership->state($fixture_community, 991002)['joined'],
    'report_persisted' => !empty($reported['accepted']) && ($reported['report']['state'] ?? '') === 'open',
    'repeat_report_idempotent' => !empty($reported_again['idempotent']),
    'unauthorized_queue_denied' => empty($forbidden['accepted']) && ($forbidden['reason_code'] ?? '') === 'MODERATION_FORBIDDEN',
    'authorized_queue_reads_open_report' => !empty($queue['accepted']) && count($queue['reports']) === 1,
    'moderation_reuses_publisher_lifecycle' => !empty($resolved['accepted']) && ($post_after['publication_state'] ?? '') === 'hidden',
    'report_and_content_audit_agree' => count($report_audit) === 2 && ($invalid['reason_code'] ?? '') === 'REPORT_ALREADY_RESOLVED',
];
echo wp_json_encode(['all_assertions'=>!in_array(false, $checks, true), 'checks'=>$checks, 'report_audit_count'=>count($report_audit), 'report_audit'=>$report_audit, 'content_audit_count'=>count($post_id ? (new TNet_Community_Publisher_Repository())->get_audit($post_id) : []), 'initial'=>$initial, 'joined'=>$joined, 'migrated'=>$migrated, 'unresolved'=>$unresolved, 'rolled_back'=>$rolled_back, 'reported'=>$reported, 'forbidden'=>$forbidden, 'resolved'=>$resolved, 'invalid'=>$invalid], JSON_PRETTY_PRINT) . "\n";
$cleanup();
wp_set_current_user(1);
