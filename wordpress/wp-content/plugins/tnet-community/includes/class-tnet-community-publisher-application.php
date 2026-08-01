<?php
defined('ABSPATH') || exit;
final class TNet_Community_Publisher_Application {
    private TNet_Community_Publisher_Domain $domain;
    public function __construct(?TNet_Community_Publisher_Domain $domain=null) { $this->domain=$domain?:new TNet_Community_Publisher_Domain(); }
    public function publish_and_persist(array $draft,array $communities,array $actor=[]): array { $result=$this->domain->publish($draft,$communities,(string)($draft['moderation_input']??'clear')); if (empty($result['accepted'])) return $result; return (new TNet_Community_Publisher_Repository())->persist_publication($result,$actor); }
    public function publish(array $draft,array $communities,array $actor=[]): array { return $this->publish_and_persist($draft,$communities,$actor); }
    public function domain(): TNet_Community_Publisher_Domain { return $this->domain; }
    public function publish_reply(array $draft,array $parent,array $communities,array $actor=[]): array { $this->domain->seed_post($parent); $draft['parent_post_id']=$parent['post_id']; $draft['thread_id']=$parent['thread_id']; if (empty($draft['subject_reference']) && !empty($parent['owner_product'])) $draft['subject_reference']=['owner_product'=>$parent['owner_product'],'subject_type'=>$parent['subject_type'],'subject_id'=>$parent['subject_id'],'source_namespace'=>$parent['source_namespace'] ?? null,'subject_revision'=>$parent['subject_revision'] ?? null]; return $this->publish_and_persist($draft,$communities,$actor); }
}
