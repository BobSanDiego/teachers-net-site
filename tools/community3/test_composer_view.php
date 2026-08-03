<?php
require_once __DIR__ . '/../../wordpress/wp-content/plugins/tnet-community/includes/class-tnet-community-composer-contracts.php';
require_once __DIR__ . '/../../wordpress/wp-content/plugins/tnet-community/includes/class-tnet-community-composer-view.php';
$html='<form method="post" enctype="multipart/form-data"><input id="title" name="title"><textarea id="body" name="body"></textarea></form>';
$out=TNet_Community_Composer_View::topic($html);
foreach(['data-composer-view="shared-topic"','name="title"','id="body"','composer-formatting-help'] as $needle) if(strpos($out,$needle)===false) exit(1);
if(strpos($out,'reply-target')!==false) exit(1);
echo "composer view pass\n";
