<?php
// Run only with: ddev wp eval-file tools/community3/local_publisher_persistence_smoke.php
defined('ABSPATH') || exit('WordPress bootstrap required\n');
require_once ABSPATH . 'wp-content/plugins/tnet-community/tnet-community.php';
TNet_Community_Schema::install();
$repo = new TNet_Community_Publisher_Repository();
$post = ['post_id'=>'post:local-synth','community_id'=>'community:local-synth','author_id'=>'user:local-synth','thread_id'=>'thread:local-synth','parent_post_id'=>null,'post_type'=>'topic','title'=>'Synthetic local topic','body'=>'Synthetic local body','visibility'=>'public','moderation_state'=>'clear','publication_state'=>'published','created_at'=>'2026-07-31 00:00:00','published_at'=>'2026-07-31 00:00:00','idempotency_key'=>'submission:local-synth','revision'=>1,'safe_target'=>'community-post','compatibility_refs'=>[],'audit_metadata'=>[]];
$event = ['event_id'=>'event:local-synth','event_type'=>'community.post.published','post_id'=>$post['post_id'],'community_id'=>$post['community_id'],'thread_id'=>$post['thread_id'],'parent_post_id'=>null,'revision'=>1];
$first = $repo->persist_publication(['post'=>$post,'event'=>$event], ['actor_id'=>'user:local-synth']);
$again = $repo->persist_publication(['post'=>$post,'event'=>$event], ['actor_id'=>'user:local-synth']);
$found = $repo->find_post($post['post_id']);
echo wp_json_encode(['persisted'=>(bool)$first['accepted'],'idempotent_repeat'=>(bool)$again['accepted'],'post_id'=>$found['post_id'] ?? null,'thread_id'=>$found['thread_id'] ?? null,'audit_count'=>count($repo->get_audit($post['post_id'])),'pending_event_count'=>count($repo->get_pending_events())], JSON_PRETTY_PRINT) . "\n";
TNet_Community_Schema::uninstall();
