<?php
defined('ABSPATH') || exit;
final class TNet_Community_Publisher_Application {
    private TNet_Community_Publisher_Domain $domain;
    public function __construct(?TNet_Community_Publisher_Domain $domain=null) { $this->domain=$domain?:new TNet_Community_Publisher_Domain(); }
    public function publish_and_persist(array $draft,array $communities,array $actor=[]): array { $result=$this->domain->publish($draft,$communities,(string)($draft['moderation_input']??'clear')); if (empty($result['accepted'])) return $result; return (new TNet_Community_Publisher_Repository())->persist_publication($result,$actor); }
    public function domain(): TNet_Community_Publisher_Domain { return $this->domain; }
    public function publish_reply(array $draft,array $parent,array $communities,array $actor=[]): array { $this->domain->seed_post($parent); return $this->publish_and_persist($draft,$communities,$actor); }
}
