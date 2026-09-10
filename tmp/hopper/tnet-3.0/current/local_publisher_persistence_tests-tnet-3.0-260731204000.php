<?php
defined('ABSPATH') || exit('WordPress bootstrap required\n');
require_once ABSPATH . 'wp-content/plugins/tnet-community/tnet-community.php';
TNet_Community_Schema::install();
$make = static function(string $suffix): array { $post=['post_id'=>'post:'.$suffix,'community_id'=>'community:local','author_id'=>'user:local','thread_id'=>'thread:'.$suffix,'parent_post_id'=>null,'post_type'=>'topic','title'=>'Synthetic '.$suffix,'body'=>'Synthetic body','visibility'=>'public','moderation_state'=>'clear','publication_state'=>'published','created_at'=>'2026-07-31 00:00:00','published_at'=>'2026-07-31 00:00:00','idempotency_key'=>'key:'.$suffix,'revision'=>1,'safe_target'=>'community-post','compatibility_refs'=>[],'audit_metadata'=>[]]; return ['post'=>$post,'event'=>['event_id'=>'event:'.$suffix,'event_type'=>'community.post.published','post_id'=>$post['post_id'],'community_id'=>$post['community_id'],'thread_id'=>$post['thread_id'],'parent_post_id'=>null,'revision'=>1]]; };
$results=[]; $repo=new TNet_Community_Publisher_Repository();
foreach(['post','audit','event'] as $failure) { $r=(new TNet_Community_Publisher_Repository([$failure=>true]))->persist_publication($make('fail-'.$failure),['actor_id'=>'tester']); $results['rollback_'.$failure]=[$r['accepted']===false]; }
$first=$repo->persist_publication($make('base'),['actor_id'=>'tester']); $second=$repo->persist_publication($make('base'),['actor_id'=>'tester']); $results['duplicate_retry']=[$first['accepted']===true,$second['accepted']===true,count($repo->get_audit('post:base'))===1];
$restart=new TNet_Community_Publisher_Repository(); $results['restart_retrieval']=$restart->find_post('post:base') !== null;
$flat=[]; foreach($results as $value) $flat=array_merge($flat,(array)$value); echo wp_json_encode(['all_assertions'=>!in_array(false,$flat,true),'results'=>$results],JSON_PRETTY_PRINT)."\n";
TNet_Community_Schema::uninstall();
