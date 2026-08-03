<?php
require_once __DIR__ . '/../../wordpress/wp-content/plugins/tnet-community/includes/class-tnet-community-composer-contracts.php';
require_once __DIR__ . '/../../wordpress/wp-content/plugins/tnet-community/includes/class-tnet-community-composer-view.php';
$html='<form method="post" enctype="multipart/form-data"><input id="title" name="title"><textarea id="body" name="body"></textarea></form>';
$out=TNet_Community_Composer_View::topic($html);
foreach(['data-composer-view="shared-topic"','name="title"','id="body"','composer-formatting-help'] as $needle) if(strpos($out,$needle)===false) exit(1);
if(strpos($out,'reply-target')!==false) exit(1);
$reply=TNet_Community_Composer_View::reply('<form id="reply-composer"><input name="submission_id"><input id="reply-target" name="parent_post_id"><textarea id="reply-body" name="body"></textarea><details><summary>Formatting help</summary><p>Use **bold**, *italic*, \`code\`, quotes, lists, or [links](https://example.com).</p></details></form>');
foreach(['data-composer-view="shared-reply"','name="submission_id"','id="reply-target"','id="reply-body"','composer-formatting-help'] as $needle) if(strpos($reply,$needle)===false) exit(1);
if(strpos($reply,'community_id')!==false||strpos($reply,'post_mode')!==false||strpos($reply,'name="title"')!==false) exit(1);
echo "composer view pass\n";
