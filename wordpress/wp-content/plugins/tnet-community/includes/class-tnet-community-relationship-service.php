<?php
defined('ABSPATH') || exit;

final class TNet_Community_Relationship_Service {
    public const COMMUNITY_FOLLOW = 'community_follow';
    public const THREAD_FOLLOW = 'thread_follow';
    public const THREAD_PARTICIPATION = 'thread_participation';
    private TNet_Community_Relationship_Repository $repository;

    public function __construct(?TNet_Community_Relationship_Repository $repository = null) { $this->repository = $repository ?: new TNet_Community_Relationship_Repository(); }

    public static function community_target_key(string $community_id): string { return $community_id; }
    public static function thread_target_key(string $thread_id): string { return $thread_id; }
    public function follow_community(string $community_id, int $user_id, array $provenance = []): array { return $this->change(self::COMMUNITY_FOLLOW, $user_id, self::community_target_key($community_id), ['community_id'=>$community_id], 'active', $provenance); }
    public function unfollow_community(string $community_id, int $user_id): array { return $this->change(self::COMMUNITY_FOLLOW, $user_id, self::community_target_key($community_id), ['community_id'=>$community_id], 'inactive'); }
    public function follow_thread(string $thread_id, string $community_id, int $user_id, array $provenance = []): array { return $this->change(self::THREAD_FOLLOW, $user_id, self::thread_target_key($thread_id), ['community_id'=>$community_id,'thread_id'=>$thread_id], 'active', $provenance); }
    public function unfollow_thread(string $thread_id, string $community_id, int $user_id): array { return $this->change(self::THREAD_FOLLOW, $user_id, self::thread_target_key($thread_id), ['community_id'=>$community_id,'thread_id'=>$thread_id], 'inactive'); }
    public function record_inferred_participation(string $thread_id, string $community_id, int $user_id, array $provenance = []): array { return $this->change(self::THREAD_PARTICIPATION, $user_id, self::thread_target_key($thread_id), ['community_id'=>$community_id,'thread_id'=>$thread_id], 'active', array_merge(['inferred'=>true], $provenance)); }
    public function state(string $type, string $user_id, string $target_key): array { return $this->repository->find($type, $user_id, $target_key) ?: ['state'=>'none','relationship_type'=>$type,'user_id'=>$user_id,'target_key'=>$target_key]; }
    public function active_followers(string $type, string $target_key): array { return $this->repository->active_followers($type, $target_key); }
    public function audit(string $relationship_id): array { return $this->repository->audit($relationship_id); }

    private function change(string $type, int $user_id, string $target_key, array $target, string $state, array $provenance = []): array {
        if ($user_id < 1) return ['accepted'=>false, 'reason_code'=>'AUTHENTICATED_USER_REQUIRED'];
        $canonical = 'user:' . $user_id;
        return $this->repository->transition($type, $canonical, $target_key, $target, $state, $canonical, $provenance);
    }
}
