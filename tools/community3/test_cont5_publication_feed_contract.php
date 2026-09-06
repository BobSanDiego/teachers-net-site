<?php
// Run inside WordPress with the Community plugin loaded. Creates and removes one local test record.
defined('ABSPATH') || exit;

$community = (new TNet_Community_Community_Registry())->find_by_slug('ai-in-education');
if (!$community) throw new RuntimeException('AI Community identity is unavailable.');
$submission = 'cont5-feed-' . wp_generate_uuid4();
$body = 'CONT5 disposable publication-to-feed contract fixture.';
$draft = [
    'submission_id' => $submission,
    'community_id' => $community['community_id'],
    'author_id' => 'user:1',
    'post_type' => 'topic',
    'title' => TNet_Community_Authoring::body_label($body),
    'body' => $body,
    'parent_post_id' => null,
    'visibility' => 'public',
    'publication_mode' => 'post_first',
    'moderation_input' => 'clear',
    'compatibility_refs' => ['presentation' => ['subjectless' => true]],
    'audit_context' => ['source' => 'cont5-contract-test'],
];
$result = (new TNet_Community_Publisher_Application())->publish_and_persist(
    $draft,
    [$community['community_id'] => ['active' => true]],
    ['actor_id' => 'user:1']
);
if (empty($result['accepted']) || empty($result['post']['post_id'])) throw new RuntimeException('Canonical publication failed.');
$post_id = $result['post']['post_id'];
try {
    $rows = (new TNet_Community_Publisher_Repository())->list_latest_topics(20, $community['community_id']);
    if (!array_filter($rows, static fn(array $row): bool => ($row['post_id'] ?? '') === $post_id && ($row['publication_state'] ?? '') === 'published')) {
        throw new RuntimeException('Published scoped topic was absent from the canonical feed query.');
    }
} finally {
    global $wpdb;
    $tables = TNet_Community_Schema::table_names();
    $wpdb->delete($tables['events'], ['post_id' => $post_id], ['%s']);
    $wpdb->delete($tables['audit'], ['post_id' => $post_id], ['%s']);
    $wpdb->delete($tables['posts'], ['post_id' => $post_id], ['%s']);
}

echo "CONT5 publication-to-feed contract pass\n";
