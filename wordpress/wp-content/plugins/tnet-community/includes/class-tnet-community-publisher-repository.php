<?php
defined('ABSPATH') || exit;

final class TNet_Community_Publisher_Repository {
    private array $tables;
    private array $failures;
    public function __construct(array $failures = []) { $this->tables = TNet_Community_Schema::table_names(); $this->failures = $failures; }

    public function persist_publication(array $publication, array $actor = []): array {
        global $wpdb;
        $post = $publication['post'] ?? null;
        if (!is_array($post) || empty($post['post_id'])) return ['accepted' => false, 'reason_code' => 'PUBLICATION_INVALID'];
        $existing = $this->find_by_submission_key($post['community_id'], $post['author_id'], $post['idempotency_key']);
        if ($existing) {
            return $existing['post']['body'] === $post['body'] && $existing['post']['title'] === $post['title']
                ? $existing : ['accepted' => false, 'reason_code' => 'IDEMPOTENCY_CONFLICT'];
        }
        $wpdb->query('START TRANSACTION');
        try {
            $now = current_time('mysql', true);
            $row = ['post_id'=>$post['post_id'],'community_id'=>$post['community_id'],'author_id'=>$post['author_id'],'thread_id'=>$post['thread_id'],'parent_post_id'=>$post['parent_post_id'] ?? null,'post_type'=>$post['post_type'],'title'=>$post['title'],'body'=>$post['body'],'visibility'=>$post['visibility'],'moderation_state'=>$post['moderation_state'],'publication_state'=>$post['publication_state'],'created_at'=>$post['created_at'] ?? $now,'updated_at'=>$now,'published_at'=>$post['published_at'] ?? null,'idempotency_key'=>$post['idempotency_key'],'revision'=>(int)($post['revision'] ?? 1),'safe_target'=>$post['safe_target'] ?? 'community-post','compatibility_json'=>wp_json_encode($post['compatibility_refs'] ?? []),'audit_json'=>wp_json_encode($post['audit_metadata'] ?? []),'conversation_root_id'=>$post['conversation_root_id'] ?? null,'reply_to_post_id'=>$post['reply_to_post_id'] ?? null,'reply_to_author_id'=>$post['reply_to_author_id'] ?? null,'owner_product'=>$post['owner_product'] ?? null,'subject_type'=>$post['subject_type'] ?? null,'subject_id'=>$post['subject_id'] ?? null,'source_namespace'=>$post['source_namespace'] ?? null,'subject_revision'=>$post['subject_revision'] ?? null];
            if (!empty($this->failures['post'])) throw new RuntimeException('POST_WRITE_FAILED');
            $post_formats = array_fill(0, count($row), '%s'); $post_formats[15] = '%d';
            if (false === $wpdb->insert($this->tables['posts'], $row, $post_formats)) throw new RuntimeException('POST_WRITE_FAILED');
            $audit = ['post_id'=>$post['post_id'],'action'=>'publish','actor_id'=>$actor['actor_id'] ?? $post['author_id'],'reason'=>'initial publication','previous_state'=>'draft','new_state'=>$post['publication_state'],'evidence_json'=>wp_json_encode($actor),'created_at'=>$now];
            if (!empty($this->failures['audit'])) throw new RuntimeException('AUDIT_WRITE_FAILED');
            if (false === $wpdb->insert($this->tables['audit'], $audit, array_fill(0, count($audit), '%s'))) throw new RuntimeException('AUDIT_WRITE_FAILED');
            if (!empty($publication['event'])) {
                $event=$publication['event']; $event_row=['event_id'=>$event['event_id'],'event_type'=>$event['event_type'],'post_id'=>$event['post_id'],'community_id'=>$event['community_id'],'thread_id'=>$event['thread_id'],'parent_post_id'=>$event['parent_post_id'] ?? null,'event_version'=>(int)($event['revision'] ?? 1),'payload_json'=>wp_json_encode($event),'delivery_status'=>'pending','dedupe_key'=>$event['event_id'],'created_at'=>$now];
                if (!empty($this->failures['event'])) throw new RuntimeException('EVENT_WRITE_FAILED');
                if (false === $wpdb->insert($this->tables['events'], $event_row, array_fill(0, count($event_row), '%s'))) throw new RuntimeException('EVENT_WRITE_FAILED');
            }
            $wpdb->query('COMMIT'); return ['accepted'=>true,'post'=>$post,'event'=>$publication['event'] ?? null];
        } catch (Throwable $e) { $wpdb->query('ROLLBACK'); return ['accepted'=>false,'reason_code'=>$e->getMessage()]; }
    }
    public function find_post(string $post_id): ?array { global $wpdb; $row=$wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tables['posts']} WHERE post_id=%s",$post_id),ARRAY_A); return $row ? $this->decode($row) : null; }
    public function find_by_submission_key(string $community_id,string $author_id,string $key): ?array { global $wpdb; $row=$wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tables['posts']} WHERE community_id=%s AND author_id=%s AND idempotency_key=%s",$community_id,$author_id,$key),ARRAY_A); return $row ? ['accepted'=>true,'post'=>$this->decode($row),'event'=>null] : null; }
    public function list_thread(string $thread_id,int $limit=100): array { global $wpdb; $rows=$wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->tables['posts']} WHERE thread_id=%s ORDER BY created_at ASC, id ASC LIMIT %d",$thread_id,$limit),ARRAY_A); return array_map([$this,'decode'],$rows ?: []); }
    public function list_latest_topics(int $limit=20): array { global $wpdb; $sql="SELECT p.*, (SELECT COUNT(*) FROM {$this->tables['posts']} r WHERE r.thread_id=p.thread_id AND r.post_type='reply' AND r.publication_state IN ('published','restored')) AS reply_count, (SELECT MAX(a.updated_at) FROM {$this->tables['posts']} a WHERE a.thread_id=p.thread_id AND a.publication_state IN ('published','restored')) AS last_activity FROM {$this->tables['posts']} p WHERE p.post_type='topic' AND p.publication_state IN ('published','restored') ORDER BY last_activity DESC, p.created_at DESC, p.id DESC LIMIT %d"; return array_map([$this,'decode'],$wpdb->get_results($wpdb->prepare($sql,$limit),ARRAY_A) ?: []); }
    public function get_audit(string $post_id): array { global $wpdb; return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->tables['audit']} WHERE post_id=%s ORDER BY audit_id ASC",$post_id),ARRAY_A) ?: []; }
    public function get_events(string $post_id): array { global $wpdb; return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->tables['events']} WHERE post_id=%s ORDER BY id ASC",$post_id),ARRAY_A) ?: []; }
    public function transition_post(string $post_id,string $new_state,string $actor,string $reason): array { global $wpdb; $post=$this->find_post($post_id); if (!$post) return ['accepted'=>false,'reason_code'=>'POST_NOT_FOUND']; $transition=(new TNet_Community_Publisher_Domain())->transition($post,$new_state,$actor,$reason); if (empty($transition['accepted'])) return $transition; $wpdb->query('START TRANSACTION'); $updated=$wpdb->update($this->tables['posts'],['publication_state'=>$new_state,'updated_at'=>current_time('mysql',true)],['post_id'=>$post_id],['%s','%s'],['%s']); $audit=$wpdb->insert($this->tables['audit'],['post_id'=>$post_id,'action'=>$reason,'actor_id'=>$actor,'reason'=>$reason,'previous_state'=>$post['publication_state'],'new_state'=>$new_state,'evidence_json'=>wp_json_encode($transition),'created_at'=>current_time('mysql',true)],array_fill(0,8,'%s')); if (false===$updated||false===$audit) { $wpdb->query('ROLLBACK'); return ['accepted'=>false,'reason_code'=>'LIFECYCLE_PERSISTENCE_FAILED']; } $wpdb->query('COMMIT'); return $transition; }
    public function get_pending_events(int $limit=100): array { global $wpdb; return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->tables['events']} WHERE delivery_status='pending' ORDER BY id ASC LIMIT %d",$limit),ARRAY_A) ?: []; }
    public function mark_event_dispatched(string $event_id): bool { global $wpdb; return false !== $wpdb->update($this->tables['events'],['delivery_status'=>'dispatched','dispatched_at'=>current_time('mysql',true)],['event_id'=>$event_id],['%s','%s'],['%s']); }
    public function persist_cached_preview(string $post_id, array $preview): bool {
        global $wpdb; $post=$this->find_post($post_id); if (!$post) return false;
        $refs=$post['compatibility_json'] ?? []; $refs['composer']['preview']=$preview;
        return false !== $wpdb->update($this->tables['posts'],['compatibility_json'=>wp_json_encode($refs),'updated_at'=>current_time('mysql',true)],['post_id'=>$post_id],['%s','%s'],['%s']);
    }
    private function decode(array $row): array { foreach(['compatibility_json','audit_json'] as $key) if (isset($row[$key])) $row[$key]=json_decode($row[$key],true) ?: []; return $row; }
}
