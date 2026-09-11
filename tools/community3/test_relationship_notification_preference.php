<?php
/** Local-only deterministic proof for the C3 relationship/notification preference foundation. */
defined('ABSPATH') || exit("WordPress bootstrap required\n");
require_once ABSPATH . 'wp-content/plugins/tnet-community/tnet-community.php';
TNet_Community_Schema::install();
global $wpdb;
$tables = TNet_Community_Schema::table_names();
$community = TNet_Community_Community_Registry::AI_IN_EDUCATION_ID;
$prefix = 'synthetic:rnp:' . substr(hash('sha256', __FILE__), 0, 10);
$thread_ids = [];
$expected_event_ids = [];
foreach (['topic','reply','self-reply'] as $suffix) {
    $post_id = 'post:' . substr(hash('sha256', $prefix . ':' . $suffix), 0, 16);
    $expected_event_ids[] = 'event:' . substr(hash('sha256', $post_id), 0, 16);
}

$cleanup = static function () use ($wpdb, $tables, $prefix, &$thread_ids, &$expected_event_ids): void {
    $notification = $wpdb->prefix . 'tnet_notifications';
    if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $notification)) === $notification && $expected_event_ids) foreach ($expected_event_ids as $event_id) $wpdb->delete($notification, ['event_id'=>$event_id, 'source_product'=>'community'], ['%s','%s']);
    $rows = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM {$tables['posts']} WHERE idempotency_key LIKE %s", $prefix . '%')) ?: [];
    foreach ($rows as $post_id) { $wpdb->delete($tables['events'], ['post_id'=>$post_id], ['%s']); $wpdb->delete($tables['audit'], ['post_id'=>$post_id], ['%s']); $wpdb->delete($tables['posts'], ['post_id'=>$post_id], ['%s']); }
    if ($thread_ids) { foreach ($thread_ids as $thread_id) { $wpdb->delete($tables['relationships'], ['target_key'=>$thread_id], ['%s']); $wpdb->delete($tables['relationships'], ['target_key'=>'thread:' . $thread_id], ['%s']); } }
    foreach (['relationship_audit','relationships'] as $name) $wpdb->query($wpdb->prepare("DELETE FROM {$tables[$name]} WHERE provenance_json LIKE %s", '%' . $wpdb->esc_like($prefix) . '%'));
    $preference_ids = $wpdb->get_col($wpdb->prepare("SELECT preference_id FROM {$tables['preferences']} WHERE user_id LIKE %s", 'user:9910%')) ?: [];
    foreach ($preference_ids as $preference_id) $wpdb->delete($tables['preference_audit'], ['preference_id'=>$preference_id], ['%s']);
    $wpdb->query($wpdb->prepare("DELETE FROM {$tables['preferences']} WHERE user_id LIKE %s", 'user:9910%'));
    $wpdb->query($wpdb->prepare("DELETE FROM {$tables['suppressions']} WHERE user_id LIKE %s", 'user:9910%'));
    $wpdb->query($wpdb->prepare("DELETE FROM {$tables['preference_reconciliation']} WHERE source_namespace=%s", $prefix));
    foreach ($expected_event_ids as $event_id) $wpdb->delete($tables['notification_decisions'], ['event_id'=>$event_id], ['%s']);
};
$cleanup();

$relationships = new TNet_Community_Relationship_Service();
$prefs = new TNet_Community_Notification_Preference_Service();
$before_membership = (new TNet_Community_Membership_Service())->state($community, 991001);
$community_follow = $relationships->follow_community($community, 991001, ['test_prefix'=>$prefix]);
$community_follow_again = $relationships->follow_community($community, 991001, ['test_prefix'=>$prefix]);
$community_unfollow = $relationships->unfollow_community($community, 991001);
$community_unfollow_again = $relationships->unfollow_community($community, 991001);
$thread_follow = $relationships->follow_thread('thread:synthetic-rnp', $community, 991001, ['test_prefix'=>$prefix]);
$thread_follow_again = $relationships->follow_thread('thread:synthetic-rnp', $community, 991001, ['test_prefix'=>$prefix]);
$inferred = $relationships->record_inferred_participation('thread:synthetic-rnp', $community, 991002, ['test_prefix'=>$prefix]);
$after_membership = (new TNet_Community_Membership_Service())->state($community, 991001);

$preference_results = [];
$preference_results[] = $prefs->set(991001, 'community_activity', 'bell', 'immediate', '', ['test_prefix'=>$prefix]);
$preference_results[] = $prefs->set(991001, 'community_activity', 'email', 'daily', '', ['test_prefix'=>$prefix]);
$preference_results[] = $prefs->set(991001, 'community_reply', 'bell', 'weekly', '', ['test_prefix'=>$prefix]);
$preference_results[] = $prefs->set(991001, 'community_reply', 'email', 'never', '', ['test_prefix'=>$prefix]);
$legacy_results = [];
foreach ([
    ['key'=>'email_posts', 'value'=>['value'=>'yes'], 'classification'=>'deterministically_mapped', 'reason_code'=>'EXPLICIT_LEGACY_GROUP_SETTING'],
    ['key'=>'email_responses', 'value'=>['value'=>'unknown'], 'classification'=>'explicit_confirmation_required', 'reason_code'=>'AMBIGUOUS_LEGACY_VALUE'],
    ['key'=>'unsubscribe', 'value'=>['value'=>true], 'classification'=>'NO_EMAIL', 'reason_code'=>'UNRESOLVED_OPT_IN'],
    ['key'=>'bounce', 'value'=>['value'=>'hard'], 'classification'=>'suppressed', 'reason_code'=>'HARD_BOUNCE'],
] as $fixture) $legacy_results[] = $prefs->reconcile(['source_namespace'=>$prefix,'source_key'=>$fixture['key'],'source_value'=>$fixture['value'],'classification'=>$fixture['classification'],'reason_code'=>$fixture['reason_code'],'evidence'=>['synthetic'=>true]]);
$legacy_repeat = $prefs->reconcile(['source_namespace'=>$prefix,'source_key'=>'email_posts','source_value'=>['value'=>'yes'],'classification'=>'deterministically_mapped','reason_code'=>'EXPLICIT_LEGACY_GROUP_SETTING']);
$email_pref = $prefs->set(991003, 'community_reply', 'email', 'immediate', '', ['test_prefix'=>$prefix]);
$suppression_results = [];
foreach ([991003=>'unsubscribe',991004=>'hard_bounce',991005=>'complaint'] as $user => $reason) { $prefs->set($user, 'community_reply', 'email', 'immediate', '', ['test_prefix'=>$prefix]); $suppression_results[] = $prefs->add_suppression($user, 'email', 'community_reply', $reason, ['synthetic'=>true], $prefix); }
$email_evaluations = [
    'no_preference' => TNet_Community_Notification_Integration::evaluate_email(991006),
    'preference_never' => TNet_Community_Notification_Integration::evaluate_email(991001),
    'unsubscribe' => TNet_Community_Notification_Integration::evaluate_email(991003),
    'hard_bounce' => TNet_Community_Notification_Integration::evaluate_email(991004),
    'complaint' => TNet_Community_Notification_Integration::evaluate_email(991005),
];
$bell_with_email_suppression = TNet_Community_Notification_Integration::evaluate_bell(991003);

$wp_user_a = 1; $wp_user_b = 2; wp_set_current_user($wp_user_a);
$topic = (new TNet_Community_Publisher_Application())->publish_and_persist(['submission_id'=>$prefix . ':topic','community_id'=>$community,'author_id'=>'user:' . $wp_user_a,'post_type'=>'topic','title'=>'Synthetic relationship notification topic','body'=>'A bounded notification foundation topic.','visibility'=>'public','publication_mode'=>'post_first','moderation_input'=>'clear','compatibility_refs'=>[],'audit_context'=>['test_prefix'=>$prefix]], [$community=>['active'=>true]], ['actor_id'=>'user:' . $wp_user_a]);
$topic_id = $topic['post']['post_id'] ?? ''; $thread_ids[] = $topic['post']['thread_id'] ?? ''; if (!empty($topic['event']['event_id'])) $expected_event_ids[] = $topic['event']['event_id'];
$reply = (new TNet_Community_Publisher_Application())->publish_reply(['submission_id'=>$prefix . ':reply','community_id'=>$community,'author_id'=>'user:' . $wp_user_b,'post_type'=>'reply','title'=>'','body'=>'Synthetic reply for the notification foundation.','visibility'=>'public','publication_mode'=>'post_first','moderation_input'=>'clear','compatibility_refs'=>[],'audit_context'=>['test_prefix'=>$prefix]], $topic['post'], [$community=>['active'=>true]], ['actor_id'=>'user:' . $wp_user_b]);
$reply_id = $reply['post']['post_id'] ?? ''; $reply_event_id = (string)($reply['event']['event_id'] ?? ''); if ($reply_event_id) $expected_event_ids[] = $reply_event_id; wp_set_current_user($wp_user_a);
$provider_rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}tnet_notifications WHERE source_product='community' AND event_id=%s", $reply_event_id), ARRAY_A) ?: [];
$duplicate_event = $reply['event'] ?? ['event_id'=>$reply_event_id]; if ($reply_id) TNet_Community_Notification_Integration::on_publication($duplicate_event, $reply['post']);
$provider_rows_after_duplicate = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}tnet_notifications WHERE source_product='community' AND event_id=%s", $reply_event_id), ARRAY_A) ?: [];
$self_reply = (new TNet_Community_Publisher_Application())->publish_reply(['submission_id'=>$prefix . ':self-reply','community_id'=>$community,'author_id'=>'user:' . $wp_user_a,'post_type'=>'reply','title'=>'','body'=>'Self notification must be suppressed.','visibility'=>'public','publication_mode'=>'post_first','moderation_input'=>'clear','compatibility_refs'=>[],'audit_context'=>['test_prefix'=>$prefix]], $topic['post'], [$community=>['active'=>true]], ['actor_id'=>'user:' . $wp_user_a]);
$self_event_id = (string)($self_reply['event']['event_id'] ?? ''); if ($self_event_id) $expected_event_ids[] = $self_event_id;
$self_notifications = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}tnet_notifications WHERE source_product='community' AND event_id=%s", $self_event_id));
$destination = $provider_rows[0]['destination_args_json'] ?? '';
$resolved_destination = $destination ? TNet_Community_Notification_Integration::resolve_destination(json_decode($destination, true) ?: []) : false;
$provider_available = class_exists('TNet_Notifications_Registry') && function_exists('tnet_notifications') && $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}tnet_notifications'") === $wpdb->prefix . 'tnet_notifications';

$checks = [
    'community_follow_persisted_and_idempotent' => !empty($community_follow['accepted']) && !empty($community_follow_again['idempotent']) && !empty($community_unfollow['accepted']) && !empty($community_unfollow_again['idempotent']),
    'thread_follow_persisted_and_idempotent' => !empty($thread_follow['accepted']) && !empty($thread_follow_again['idempotent']),
    'inferred_participation_is_distinct' => !empty($inferred['accepted']) && $relationships->state(TNet_Community_Relationship_Service::THREAD_PARTICIPATION, 'user:991002', 'thread:synthetic-rnp')['relationship_type'] === TNet_Community_Relationship_Service::THREAD_PARTICIPATION && $relationships->state(TNet_Community_Relationship_Service::THREAD_FOLLOW, 'user:991001', 'thread:synthetic-rnp')['relationship_type'] === TNet_Community_Relationship_Service::THREAD_FOLLOW,
    'membership_unchanged_by_follow' => $before_membership['state'] === $after_membership['state'] && $before_membership['member_count'] === $after_membership['member_count'],
    'all_four_explicit_preferences_distinct' => count(array_filter($preference_results, static fn($r) => !empty($r['accepted']))) === 4 && $prefs->get(991001,'community_reply','bell')['frequency'] === 'weekly',
    'legacy_reconciliation_classified_and_idempotent' => count(array_filter($legacy_results, static fn($r) => !empty($r['accepted']))) === 4 && !empty($legacy_repeat['idempotent']),
    'suppression_precedence_for_all_reasons' => count(array_filter($suppression_results, static fn($r) => !empty($r['accepted']))) === 3 && !$email_evaluations['unsubscribe']['eligible'] && !$email_evaluations['hard_bounce']['eligible'] && !$email_evaluations['complaint']['eligible'],
    'email_suppression_does_not_block_bell' => $bell_with_email_suppression['eligible'] && $bell_with_email_suppression['reason_code'] === 'BELL_DEFAULT_IMMEDIATE',
    'no_email_preference_from_actions' => !$prefs->get($wp_user_a, 'community_reply', 'email') && !$prefs->get($wp_user_b, 'community_reply', 'email') && !$email_evaluations['no_preference']['eligible'] && $email_evaluations['no_preference']['reason_code'] === 'NO_EMAIL_PREFERENCE',
    'provider_event_exactly_once' => $provider_available && count($provider_rows) === 1 && count($provider_rows_after_duplicate) === 1,
    'self_event_suppressed' => (int)$self_notifications === 0,
    'canonical_target_args' => strpos($destination, 'community_id') !== false && strpos($destination, 'thread_id') !== false && strpos($destination, 'post_id') !== false,
    'canonical_resolved_reply_destination' => is_string($resolved_destination) && str_ends_with($resolved_destination, '#reply-post:' . $reply_id),
];
echo wp_json_encode(['all_assertions'=>!in_array(false, $checks, true),'checks'=>$checks,'provider_available'=>$provider_available,'provider_count'=>count($provider_rows),'provider_count_after_duplicate'=>count($provider_rows_after_duplicate),'destination_args_json'=>$destination,'resolved_destination'=>$resolved_destination,'email_evaluations'=>$email_evaluations,'legacy_classifications'=>array_map(static fn($r)=>$r['reconciliation']['classification'] ?? null,$legacy_results)], JSON_PRETTY_PRINT) . "\n";
$cleanup(); wp_set_current_user(1);
