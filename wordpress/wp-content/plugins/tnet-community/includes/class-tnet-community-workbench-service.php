<?php
defined('ABSPATH') || exit;

final class TNet_Community_Workbench_Service {
    public function publish(array $input): array {
        $required = ['community_id','author_id','submission_key','title','body','visibility','publication_mode'];
        foreach ($required as $field) if ('' === trim((string)($input[$field] ?? ''))) return ['accepted'=>false,'reason_code'=>'WORKBENCH_'.$field.'_REQUIRED'];
        if (!in_array($input['visibility'], ['public','members','private'], true) || !in_array($input['publication_mode'], ['post_first','pending'], true)) return ['accepted'=>false,'reason_code'=>'WORKBENCH_OPTION_INVALID'];
        $key = sanitize_key($input['submission_key']); $community = sanitize_text_field($input['community_id']); $author = sanitize_text_field($input['author_id']);
        $draft=['submission_id'=>$key,'community_id'=>$community,'author_id'=>$author,'post_type'=>'topic','title'=>sanitize_text_field($input['title']),'body'=>sanitize_textarea_field($input['body']),'parent_post_id'=>null,'visibility'=>$input['visibility'],'publication_mode'=>$input['publication_mode'],'moderation_input'=>'clear','compatibility_refs'=>['workbench_namespace'=>'tnet-community-local'],'audit_context'=>['source'=>'local-workbench']];
        return (new TNet_Community_Publisher_Application())->publish_and_persist($draft,[$community=>['active'=>true]],['actor_id'=>$author]);
    }
    public function publish_reply(array $input): array { $parent=(new TNet_Community_Publisher_Repository())->find_post(sanitize_text_field($input['parent_post_id']??'')); if (!$parent) return ['accepted'=>false,'reason_code'=>'PARENT_NOT_FOUND']; $key=sanitize_key($input['submission_key']); $draft=['submission_id'=>$key,'community_id'=>sanitize_text_field($input['community_id']),'author_id'=>sanitize_text_field($input['author_id']),'post_type'=>'reply','title'=>'','body'=>sanitize_textarea_field($input['body']??''),'parent_post_id'=>$parent['post_id'],'thread_id'=>$parent['thread_id'],'visibility'=>'public','publication_mode'=>'post_first','moderation_input'=>'clear','compatibility_refs'=>['workbench_namespace'=>'tnet-community-local'],'audit_context'=>['source'=>'local-workbench']]; return (new TNet_Community_Publisher_Application())->publish_reply($draft,$parent,[$draft['community_id']=>['active'=>true]],['actor_id'=>$draft['author_id']]); }
}
