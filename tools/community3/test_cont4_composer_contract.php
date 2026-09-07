<?php
// Run inside WordPress with the Community plugin loaded.
defined('ABSPATH') || exit;

$html = TNet_Community_Topic_Composer_Controller::embedded_form(
    'community:ai-in-education',
    'AI in Education',
    home_url('/community/ai-in-education/new/'),
    [
        'action_url' => home_url('/community/ai-in-education/new/'),
        'return_to_feed' => home_url('/community/ai-in-education/'),
    ],
    true
);
foreach ([
    'data-composer-view="scoped-topic"',
    'action="' . esc_url(home_url('/community/ai-in-education/new/')) . '"',
    'data-preview-endpoint="' . esc_url(home_url('/community/ai-in-education/new/')) . '"',
    'name="return_to_feed"',
    'name="image_file"',
    'accept="image/jpeg,image/png,image/webp"',
    'data-close-composer',
    'id="add-photo"',
    'aria-label="Add photo"',
    'id="remove-image"',
    'aria-label="Remove image"',
] as $needle) {
    if (strpos($html, $needle) === false) throw new RuntimeException('Missing composer contract: ' . $needle);
}
if (strpos($html, 'name="title"') !== false) throw new RuntimeException('Subject field leaked into the subjectless composer.');
foreach (['Add an image, paste one, or drop one here.', 'Paste or drop an image directly onto the post.'] as $removed) {
    if (strpos($html, $removed) !== false) throw new RuntimeException('Removed composer instruction is still visible: ' . $removed);
}

$source = file_get_contents(WP_PLUGIN_DIR . '/tnet-community/includes/class-tnet-community-topic-composer-controller.php');
foreach (["enableDrop(body)", "body.addEventListener('paste'", 'prepare_first_eligible', 'form.dataset.previewEndpoint', 'return_to_feed'] as $needle) {
    if (strpos($source, $needle) === false) throw new RuntimeException('Missing input ownership: ' . $needle);
}

echo "CONT4 composer contract pass\n";
