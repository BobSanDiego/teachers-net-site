<?php
defined('ABSPATH') || exit;

final class TNet_Community_Membership_Service {
    private TNet_Community_Membership_Repository $repository;
    public function __construct(?TNet_Community_Membership_Repository $repository = null) { $this->repository = $repository ?: new TNet_Community_Membership_Repository(); }
    public function canonical_user_id(?int $user_id = null): string { $user_id = $user_id ?: (int)get_current_user_id(); if ($user_id < 1) throw new RuntimeException('AUTHENTICATED_USER_REQUIRED'); return 'user:' . $user_id; }
    public function state(string $community_id, ?int $user_id = null): array { $user = $this->canonical_user_id($user_id); $membership = $this->repository->find($community_id, $user); return ['state'=>$membership['state'] ?? 'none','joined'=>($membership['state'] ?? '') === 'active','membership'=>$membership,'member_count'=>$this->repository->count_active($community_id)]; }
    public function join(string $community_id, ?int $user_id = null): array { $user = $this->canonical_user_id($user_id); return $this->repository->join($community_id, $user, $user); }
    public function leave(string $community_id, ?int $user_id = null): array { $user = $this->canonical_user_id($user_id); return $this->repository->leave($community_id, $user, $user); }
    public function migrate(array $source, array $mapping, array $identity, string $run_id, string $rule_version): array { return $this->repository->migrate($source, $mapping, $identity, $run_id, $rule_version); }
    public function rollback(string $run_id): array { return $this->repository->rollback_migration_run($run_id); }
}
