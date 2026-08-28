<?php
/** Local WP-CLI smoke test for TNET-NOTIFICATIONS-PERSISTENCE-API001. */
defined('ABSPATH') || exit;

$fail = static function ($message) { throw new RuntimeException($message); };
$ok = static function ($condition, $message) use ($fail) { if (!$condition) $fail($message); };

$definition = [
  'versions' => [1 => ['metadata_keys' => ['title', 'count'], 'destinations' => ['test.item' => static function ($args) { return isset($args['id']) && absint($args['id']) > 0; }], 'authorize' => static function ($recipient, $record) { return (int) $record['recipient_user_id'] === (int) $recipient; }]],
];
$ok(TNet_Notifications_Registry::register_source('test', ['test.created' => $definition]), 'test source registration failed');

global $wpdb;
$table = TNet_Notifications_Schema::table_name();
$wpdb->query("DELETE FROM {$table} WHERE source_product = 'test'");
$jobs_table = $wpdb->prefix . 'tnet_jobs';
$jobs_before = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$jobs_table}");
$ok(TNet_Notifications_Schema::assert_schema() === true, 'schema assertion failed');
$first_install = TNet_Notifications_Schema::install();
$second_install = TNet_Notifications_Schema::install();
$ok(!is_wp_error($first_install) && !is_wp_error($second_install), 'repeat migration failed');
$ok($first_install['version'] === $second_install['version'], 'repeat migration was not a no-op at the same schema version');
do_action('rest_api_init');
$routes = rest_get_server()->get_routes();
$ok(isset($routes['/tnet-notifications/v1/unread-count']) && isset($routes['/tnet-notifications/v1/notifications']), 'authenticated REST routes were not registered');
wp_set_current_user(0);
$anonymous_response = rest_do_request(new WP_REST_Request('GET', '/tnet-notifications/v1/unread-count'));
$ok($anonymous_response->is_error() && (int) $anonymous_response->get_status() === 401, 'unauthenticated REST request was accepted');

$event = [
  'event_id' => 'test:event:1', 'source_product' => 'test', 'event_type' => 'test.created', 'payload_version' => 1,
  'actor_user_id' => null, 'object_type' => 'item', 'object_id' => '1', 'destination_key' => 'test.item',
  'destination_args' => ['id' => 1], 'metadata' => ['title' => 'Smoke', 'count' => 1], 'dedupe_key' => 'test:item:1',
];
$service = TNet_Notifications::service();
$unknown = $event; $unknown['source_product'] = 'unknown';
$ok(is_wp_error($service->create_for_recipients($unknown, [1])), 'unknown source was accepted');
$bad_metadata = $event; $bad_metadata['metadata']['html'] = '<b>no</b>';
$ok(is_wp_error($service->create_for_recipients($bad_metadata, [1])), 'invalid metadata was accepted');
$bad_url = $event; $bad_url['metadata']['title'] = 'https://evil.example';
$ok(is_wp_error($service->create_for_recipients($bad_url, [1])), 'arbitrary URL metadata was accepted');
$bad_destination = $event; $bad_destination['destination_args'] = ['id' => 0];
$ok(is_wp_error($service->create_for_recipients($bad_destination, [1])), 'invalid destination was accepted');

$first = $service->create_for_recipients($event, [1]);
$retry = $service->create_for_recipients($event, [1]);
$other = $service->create_for_recipients($event, [317]);
if (is_wp_error($first) || is_wp_error($retry) || is_wp_error($other)) {
  $errors = [];
  foreach ([$first, $retry, $other] as $result) if (is_wp_error($result)) $errors[] = $result->get_error_code() . ': ' . $result->get_error_message();
  $fail('valid event creation failed: ' . implode(' | ', $errors));
}
$ok($first[1] === $retry[1], 'dedupe retry created a second notification');
$ok($first[1] !== $other[317], 'different recipients were not independently persisted');
$ok($service->unread_count(1) === 1 && $service->unread_count(317) === 1, 'unread counts incorrect');
$ok(count($service->list_for_recipient(1, 'test')) === 1, 'source-filtered list incorrect');
$unknown_list = $service->list_for_recipient(1, 'unknown');
$ok(is_wp_error($unknown_list), 'unknown source list did not fail closed');
$ok(count($service->list_for_recipient(1)) === 1, 'recipient list incorrect');
$ok(count($service->list_for_recipient(317)) === 1, 'second recipient list incorrect');
wp_set_current_user(1);
$rest_user_one = rest_do_request(new WP_REST_Request('GET', '/tnet-notifications/v1/notifications'));
$rest_user_one_data = $rest_user_one->get_data();
$ok(!is_wp_error($rest_user_one) && count($rest_user_one_data['items']) === 1 && (int) $rest_user_one_data['items'][0]['recipient_user_id'] === 1, 'REST recipient scoping failed for user one');
wp_set_current_user(317);
$rest_user_two = rest_do_request(new WP_REST_Request('GET', '/tnet-notifications/v1/notifications'));
$rest_user_two_data = $rest_user_two->get_data();
$ok(!is_wp_error($rest_user_two) && count($rest_user_two_data['items']) === 1 && (int) $rest_user_two_data['items'][0]['recipient_user_id'] === 317, 'REST recipient scoping failed for user two');
$ok($service->mark_read(1, $first[1]) === true, 'mark-one-read failed');
$ok($service->unread_count(1) === 0 && $service->unread_count(317) === 1, 'mark-one crossed recipient boundary');
$ok($service->mark_all_read(317, 'test') === 1, 'mark-all-read failed');
$ok($service->unread_count(317) === 0, 'mark-all unread count incorrect');
$ok($service->archive_event('test', 'test:event:1', 'retracted') === 2, 'archive/retract did not affect both recipients');
$ok($service->unread_count(1) === 0 && $service->unread_count(317) === 0, 'inactive records affected unread count');
$jobs_after = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$jobs_table}");
$ok($jobs_before === $jobs_after, 'Jobs table changed during Notifications smoke test');
$wpdb->query("DELETE FROM {$table} WHERE source_product = 'test'");
WP_CLI::success('Notifications persistence/API smoke tests passed: schema, validation, idempotency, recipient isolation, read state, archive, and Jobs-table preservation.');
