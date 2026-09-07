<?php
defined('ABSPATH') || exit;

$html = TNet_Community_Topic_Composer_Controller::embedded_form(
    'community:ai-in-education',
    'AI in Education',
    home_url('/community/ai-in-education/new/'),
    [
        'action_url' => home_url('/community/ai-in-education/new/'),
        'return_to_feed' => home_url('/community/ai-in-education/'),
        'hide_cancel' => true,
    ],
    true
);
foreach ([
    'class="composer-footer"',
    'id="add-photo"',
    'type="submit">Post',
    'id="image-preview"',
    'data-preview-endpoint=',
] as $needle) {
    if (strpos($html, $needle) === false) throw new RuntimeException('Missing CONT7 composer presentation contract: ' . $needle);
}
if (strpos($html, '>Cancel<') !== false) throw new RuntimeException('Modal Cancel control still rendered.');

$landing = file_get_contents(WP_PLUGIN_DIR . '/tnet-community/includes/class-tnet-community-landing-controller.php');
foreach ([
    'class="feed-composer-actions"',
    'class="discussion-entry"',
    'private static function relative_time',
    'Open conversation',
    'Quick view',
] as $needle) {
    $present = strpos($landing, $needle) !== false;
    if (in_array($needle, ['Open conversation', 'Quick view'], true) ? $present : !$present) {
        throw new RuntimeException('Unexpected CONT7 landing presentation contract: ' . $needle);
    }
}

echo "CONT7 presentation contract pass\n";
