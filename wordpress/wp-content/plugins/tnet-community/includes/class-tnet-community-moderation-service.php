<?php
defined('ABSPATH') || exit;

final class TNet_Community_Moderation_Service {
    private const REASONS = ['abuse','harassment','spam','privacy','copyright','other'];
    private TNet_Community_Moderation_Repository $repository;
    public function __construct(?TNet_Community_Moderation_Repository $repository = null) { $this->repository = $repository ?: new TNet_Community_Moderation_Repository(); }
    public function can_review(): bool { return current_user_can('manage_options') || current_user_can('moderate_community'); }
    public function report(string $target_post_id, string $reporter_id, string $reason, string $note = '', ?string $idempotency_key = null): array {
        $post=(new TNet_Community_Publisher_Repository())->find_post($target_post_id); if (!$post) return ['accepted'=>false,'reason_code'=>'REPORT_TARGET_NOT_FOUND'];
        if (!in_array($post['publication_state'], ['published','restored'], true)) return ['accepted'=>false,'reason_code'=>'REPORT_TARGET_NOT_REPORTABLE'];
        if (!in_array($reason, self::REASONS, true)) return ['accepted'=>false,'reason_code'=>'REPORT_REASON_UNSUPPORTED'];
        $key=sanitize_key($idempotency_key ?: wp_generate_uuid4()); $existing=$this->repository->find_by_submission($reporter_id,$key); if ($existing) return ['accepted'=>true,'idempotent'=>true,'report'=>$existing];
        global $wpdb; $wpdb->query('START TRANSACTION'); try { $now=current_time('mysql',true); $row=['report_id'=>'report:'.substr(hash('sha256',$reporter_id.'|'.$target_post_id.'|'.$key),0,48),'target_post_id'=>$target_post_id,'target_type'=>$post['post_type'],'community_id'=>$post['community_id'],'reporter_id'=>$reporter_id,'reason_code'=>$reason,'note'=>sanitize_textarea_field($note),'evidence_json'=>wp_json_encode(['target_post_id'=>$target_post_id,'target_type'=>$post['post_type'],'community_id'=>$post['community_id'],'submission'=>'user-report']),'state'=>'open','idempotency_key'=>$key,'created_at'=>$now,'updated_at'=>$now,'resolved_at'=>null,'resolved_by'=>null,'resolution_code'=>null]; $report=$this->repository->create_in_transaction($row); $wpdb->query('COMMIT'); return ['accepted'=>true,'idempotent'=>false,'report'=>$report]; } catch (Throwable $error) { $wpdb->query('ROLLBACK'); return ['accepted'=>false,'reason_code'=>$error->getMessage()]; }
    }
    public function queue(): array { if (!$this->can_review()) return ['accepted'=>false,'reason_code'=>'MODERATION_FORBIDDEN']; return ['accepted'=>true,'reports'=>$this->repository->queue()]; }
    public function resolve(string $report_id, string $actor_id, string $resolution, ?string $target_action = null): array {
        if (!$this->can_review()) return ['accepted'=>false,'reason_code'=>'MODERATION_FORBIDDEN'];
        if (!in_array($resolution, ['dismissed','action_taken'], true)) return ['accepted'=>false,'reason_code'=>'REPORT_RESOLUTION_UNSUPPORTED'];
        if ($resolution === 'action_taken' && !in_array($target_action, ['hidden','spam','retracted'], true)) return ['accepted'=>false,'reason_code'=>'MODERATION_ACTION_UNSUPPORTED'];
        global $wpdb; $wpdb->query('START TRANSACTION'); try { $report=$this->repository->find($report_id); if (!$report) throw new RuntimeException('REPORT_NOT_FOUND'); if (!in_array($report['state'], ['open','under_review'], true)) throw new RuntimeException('REPORT_ALREADY_RESOLVED'); $transition=null; if ($target_action) { $transition=(new TNet_Community_Publisher_Repository())->transition_post_in_transaction($report['target_post_id'],$target_action,$actor_id,'report:'.$resolution); if (empty($transition['accepted'])) throw new RuntimeException((string)($transition['reason_code'] ?? 'MODERATION_TRANSITION_REJECTED')); } $resolved=$this->repository->resolve_in_transaction($report_id,$actor_id,'resolved',$resolution,$target_action,$transition); $wpdb->query('COMMIT'); return ['accepted'=>true,'report'=>$resolved,'transition'=>$transition]; } catch (Throwable $error) { $wpdb->query('ROLLBACK'); return ['accepted'=>false,'reason_code'=>$error->getMessage()]; }
    }
    public function audit(string $report_id): array { if (!$this->can_review()) return []; return $this->repository->audit($report_id); }
}
