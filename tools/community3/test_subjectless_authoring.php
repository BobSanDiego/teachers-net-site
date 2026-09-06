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
echo "subjectless authoring contracts pass\n";
