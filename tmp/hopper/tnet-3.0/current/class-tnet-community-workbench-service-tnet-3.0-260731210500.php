<?php
defined('ABSPATH') || exit;

final class TNet_Community_Workbench_Service {
    public function publish(array $input): array {
        $required = ['community_id','author_id','submission_key','title','body','visibility','publication_mode'];
        foreach ($required as $field) if ('' === trim((string)($input[$field] ?? ''))) return ['accepted'=>false,'reason_code'=>'WORKBENCH_'.$field.'_REQUIRED'];
        if (!in_array($input['visibility'], ['public','members','private'], true) || !in_array($input['publication_mode'], ['post_first','pending'], true)) return ['accepted'=>false,'reason_code'=>'WORKBENCH_OPTION_INVALID'];
        $key = sanitize_key($input['submission_key']); $community = sanitize_text_field($input['community_id']); $author = sanitize_text_field($input['author_id']);
        $post_id = 'post:workbench-' . substr(hash('sha256', $key), 0, 16); $thread_id = 'thread:workbench-' . substr(hash('sha256', $key), 0, 16);
        $state = $input['publication_mode'] === 'pending' ? 'pending' : 'published'; $now = gmdate('Y-m-d H:i:s');
        $post = ['post_id'=>$post_id,'community_id'=>$community,'author_id'=>$author,'thread_id'=>$thread_id,'parent_post_id'=>null,'post_type'=>'topic','title'=>sanitize_text_field($input['title']),'body'=>sanitize_textarea_field($input['body']),'visibility'=>$input['visibility'],'moderation_state'=>'clear','publication_state'=>$state,'created_at'=>$now,'published_at'=>$state === 'published' ? $now : null,'idempotency_key'=>$key,'revision'=>1,'safe_target'=>'community-workbench','compatibility_refs'=>['workbench_namespace'=>'tnet-community-local'],'audit_metadata'=>['source'=>'local-workbench']];
        $result=['post'=>$post]; if ($state === 'published') $result['event']=['event_id'=>'event:workbench-'.substr(hash('sha256',$key),0,16),'event_type'=>'community.post.published','post_id'=>$post_id,'community_id'=>$community,'thread_id'=>$thread_id,'parent_post_id'=>null,'revision'=>1];
        return (new TNet_Community_Publisher_Repository())->persist_publication($result, ['actor_id'=>$author]);
    }
}
