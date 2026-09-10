<?php
// Run inside WordPress with the Community plugin source available.
defined('ABSPATH') || exit("WordPress bootstrap required\n");

require_once ABSPATH . 'wp-content/plugins/tnet-community/includes/class-tnet-community-subject-reference.php';

$reference = TNet_Community_Subject_Reference::from_array([
    'owner_product' => 'community',
    'subject_type' => 'community_topic',
    'subject_id' => 'topic:fixture-100',
    'source_namespace' => 'synthetic:baseline',
    'subject_revision' => 'r1',
]);
$round_trip = $reference->to_array();
if (($round_trip['subject_id'] ?? '') !== 'topic:fixture-100') {
    throw new RuntimeException('Subject reference did not round-trip its identity.');
}
if (($round_trip['source_namespace'] ?? '') !== 'synthetic:baseline') {
    throw new RuntimeException('Subject reference lost its source namespace.');
}

$rejected = false;
try {
    TNet_Community_Subject_Reference::from_array([
        'owner_product' => 'community',
        'subject_type' => 'article',
        'subject_id' => 'topic:invalid-pair',
    ]);
} catch (InvalidArgumentException $exception) {
    $rejected = $exception->getMessage() === 'SUBJECT_REFERENCE_INVALID';
}
if (!$rejected) throw new RuntimeException('Unsupported subject pair was accepted.');

echo "subject reference contract pass\n";
