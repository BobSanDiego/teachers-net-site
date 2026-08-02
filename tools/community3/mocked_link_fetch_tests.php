<?php
// Run inside WordPress with the Community plugin loaded; all fixtures are local.
defined('ABSPATH') || exit;
$app=new TNet_Community_Mocked_Link_Fetch_Application();
$cases=[['https://example.com/a',[], 'preview'],['http://example.com',[], 'raw_only'],['https://user:pass@example.com',[], 'raw_only'],['https://example.com',['destination'=>['classification'=>'private_ipv4']], 'raw_only'],['https://example.com',['transport'=>['outcome'=>'timeout_total']], 'raw_only'],['https://example.com',['transport'=>['body'=>'<script>x</script><title>Safe</title>']], 'preview']];
foreach($cases as [$url,$fixture,$expected]) { $result=$app->evaluate($url,$fixture); if($result['status']!==$expected) throw new RuntimeException('mock case failed: '.$url); if(str_contains(wp_json_encode($result),'<script')) throw new RuntimeException('unsafe metadata escaped'); }
$first=$app->evaluate('https://example.com/idempotent'); $second=$app->evaluate('https://example.com/idempotent'); if($first['normalized_url']!==$second['normalized_url']) throw new RuntimeException('idempotency failed');
echo "mocked link fetch tests passed\n";
