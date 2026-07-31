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
}
