<?php
require_once __DIR__ . '/../../wordpress/wp-content/plugins/tnet-community/includes/class-tnet-community-composer-contracts.php';
$urls=TNet_Community_Composer_Contracts::https_urls('A https://example.com/a, https://example.com/b and https://example.com/a.');
if($urls!==['https://example.com/a','https://example.com/b']) exit(1);
if(TNet_Community_Composer_Contracts::representative($urls,'')!=='https://example.com/a') exit(1);
$state=TNet_Community_Composer_Contracts::staged_image(['name'=>'photo.png','type'=>'image/png','size'=>12],'blob:preview','A photo');
if($state['state']!=='staged'||$state['mime_type']!=='image/png'||$state['accessibility_description']!=='A photo') exit(1);
echo "composer contracts pass\n";
