<?php
// Run inside WordPress with the Community plugin loaded.
defined('ABSPATH') || exit;

$body = "A meaningful first sentence gives the discussion a stable internal label.\n\nThe user never enters a separate subject.";
$label = TNet_Community_Authoring::body_label($body);
if ($label !== 'A meaningful first sentence gives the discussion a stable internal label. The user…') throw new RuntimeException('body label is not deterministic');
if (!TNet_Community_Authoring::is_subjectless(['compatibility_refs'=>['presentation'=>['subjectless'=>true]]])) throw new RuntimeException('subjectless presentation marker was lost');
if (TNet_Community_Authoring::is_subjectless(['compatibility_refs'=>[]])) throw new RuntimeException('historical records must retain title presentation');
$draft = ['submission_id'=>'subjectless-contract','community_id'=>'community:local-demo','author_id'=>'user:1','post_type'=>'topic','title'=>$label,'body'=>$body,'visibility'=>'public','publication_mode'=>'post_first','parent_post_id'=>null,'compatibility_refs'=>['presentation'=>['subjectless'=>true]]];
$result = (new TNet_Community_Publisher_Domain())->publish($draft, ['community:local-demo'=>['active'=>true]]);
if (empty($result['accepted']) || ($result['post']['title'] ?? '') !== $label) throw new RuntimeException('body-derived label did not satisfy canonical topic persistence');
$filter = static function ($pre, $args, $url) {
    if (str_ends_with($url, '/first-fails')) return new WP_Error('fixture_failure');
    return ['headers'=>['content-type'=>'text/html'],'body'=>'<html><head><title>Second link wins</title></head></html>','response'=>['code'=>200,'message'=>'OK'],'cookies'=>[],'filename'=>null];
};
add_filter('pre_http_request', $filter, 10, 3);
$preview = (new TNet_Community_Link_Attachment_Service())->prepare_first_eligible(['https://example.com/first-fails','https://example.com/second-works']);
remove_filter('pre_http_request', $filter, 10);
if (($preview['status'] ?? '') !== 'preview' || ($preview['url'] ?? '') !== 'https://example.com/second-works') throw new RuntimeException('later eligible link did not win');
echo "subjectless authoring contracts pass\n";
