<?php
defined('ABSPATH') || exit;

final class TNet_Community_Publisher_Domain {
    private array $posts = [];
    private array $submissions = [];
    private const TRANSITIONS = ['draft'=>['validated','failed'],'validated'=>['published','pending'],'pending'=>['published','hidden','spam','failed'],'published'=>['hidden','moderated','spam','retracted','deleted'],'hidden'=>['published','spam','deleted','restored'],'moderated'=>['published','spam','deleted','restored'],'spam'=>['restored','deleted'],'retracted'=>['restored','deleted'],'deleted'=>['restored'],'restored'=>['published']];

    public function seed_post(array $post): void { if (!empty($post['post_id'])) $this->posts[$post['post_id']] = $post; }

    public function publish(array $draft, array $communities, string $moderation = 'clear', bool $fail_event = false): array {
        $key = (string)($draft['submission_id'] ?? '');
        if (isset($this->submissions[$key])) {
            $old = $this->submissions[$key];
            return $old['draft'] === $draft ? $old['result'] : ['accepted'=>false,'reason_code'=>'IDEMPOTENCY_CONFLICT'];
        }
        $validation = $this->validate($draft, $communities, $moderation);
        if (!$validation['valid']) return $this->remember($key, $draft, ['accepted'=>false,'validation'=>$validation,'reason_code'=>$validation['reason_codes'][0]]);
        $mod = $this->moderate($moderation);
        $state = ($draft['publication_mode'] ?? 'post_first') === 'pending' || $mod['state'] === 'pending' ? 'pending' : $mod['state'];
        $id = 'post:' . substr(hash('sha256', $key), 0, 16);
        $parent = !empty($draft['parent_post_id']) ? ($this->posts[$draft['parent_post_id']] ?? null) : null;
        $thread = $validation['parent_thread_id'] ?? ($draft['thread_id'] ?? 'thread:' . substr(hash('sha256', $key), 0, 16));
        try {
            $subject = !empty($draft['subject_reference']) ? TNet_Community_Subject_Reference::from_array($draft['subject_reference']) : ($draft['post_type'] === 'topic' ? TNet_Community_Subject_Reference::for_topic($id) : $this->inherited_subject($parent));
        } catch (Throwable $e) {
            return $this->remember($key, $draft, ['accepted'=>false,'validation'=>['valid'=>false,'reason_codes'=>['SUBJECT_REFERENCE_INVALID']], 'reason_code'=>'SUBJECT_REFERENCE_INVALID']);
        }
        $subject_values = $subject?->to_array() ?? ['owner_product'=>null,'subject_type'=>null,'subject_id'=>null,'source_namespace'=>null,'subject_revision'=>null];
        $root = $draft['post_type'] === 'reply' ? (($parent['post_type'] ?? '') === 'topic' ? $id : (($parent['conversation_root_id'] ?? null) ?: ($parent['post_id'] ?? null))) : null;
        $now = $draft['created_at'] ?? gmdate('Y-m-d H:i:s');
        $post = ['post_id'=>$id,'community_id'=>$draft['community_id'],'author_id'=>$draft['author_id'],'thread_id'=>$thread,'parent_post_id'=>$draft['parent_post_id'] ?? null,'post_type'=>$draft['post_type'],'title'=>trim($draft['title'] ?? ''),'body'=>trim($draft['body'] ?? ''),'visibility'=>$draft['visibility'],'moderation_state'=>$mod['classification'],'publication_state'=>$state,'created_at'=>$now,'published_at'=>$state === 'published' ? $now : null,'idempotency_key'=>$key,'revision'=>1,'safe_target'=>'community-post','compatibility_refs'=>$draft['compatibility_refs'] ?? [],'audit_metadata'=>$draft['audit_context'] ?? [],'conversation_root_id'=>$root,'reply_to_post_id'=>$parent['post_id'] ?? null,'reply_to_author_id'=>$parent['author_id'] ?? null,'owner_product'=>$subject_values['owner_product'],'subject_type'=>$subject_values['subject_type'],'subject_id'=>$subject_values['subject_id'],'source_namespace'=>$subject_values['source_namespace'],'subject_revision'=>$subject_values['subject_revision']];
        $event = null;
        if ($state === 'published') {
            if ($fail_event) return $this->remember($key, $draft, ['accepted'=>true,'post'=>$post,'event'=>null,'reason_code'=>'EVENT_CONSTRUCTION_FAILED']);
            $event = ['event_id'=>'event:' . substr(hash('sha256', $id), 0, 16),'event_type'=>'community.post.published','post_id'=>$id,'community_id'=>$post['community_id'],'thread_id'=>$thread,'parent_post_id'=>$post['parent_post_id'],'revision'=>1];
        }
        $result = ['accepted'=>true,'post'=>$post,'event'=>$event,'validation'=>$validation,'moderation'=>$mod];
        $this->posts[$id] = $post;
        return $this->remember($key, $draft, $result);
    }

    public function validate(array $d, array $communities, string $moderation): array {
        $r=[]; $parent=null;
        if (empty($d['community_id']) || !isset($communities[$d['community_id']])) $r[]='COMMUNITY_UNRESOLVED';
        if (empty($d['author_id'])) $r[]='AUTHENTICATED_AUTHOR_REQUIRED';
        if (!in_array($d['post_type'] ?? '', ['topic','reply'], true)) $r[]='POST_TYPE_UNSUPPORTED';
        if (($d['post_type'] ?? '') === 'topic' && !empty($d['parent_post_id'])) $r[]='ROOT_TOPIC_PARENT_FORBIDDEN';
        if (($d['post_type'] ?? '') === 'topic' && !trim((string)($d['title'] ?? ''))) $r[]='TITLE_REQUIRED';
        if (!trim((string)($d['body'] ?? ''))) $r[]='BODY_REQUIRED';
        if (!in_array($d['visibility'] ?? '', ['public','members','private'], true)) $r[]='VISIBILITY_UNSUPPORTED';
        if (!in_array($d['publication_mode'] ?? '', ['post_first','pending'], true)) $r[]='PUBLICATION_MODE_UNSUPPORTED';
        if (($d['post_type'] ?? '') === 'reply') {
            if (empty($d['parent_post_id'])) $r[]='REPLY_PARENT_REQUIRED';
            elseif (!isset($this->posts[$d['parent_post_id']])) $r[]='PARENT_NOT_FOUND';
            else { $parent=$this->posts[$d['parent_post_id']]; if (($parent['community_id'] ?? '') !== $d['community_id']) $r[]='PARENT_COMMUNITY_MISMATCH'; if (in_array($parent['publication_state'] ?? '', ['hidden','spam','retracted','deleted'], true)) $r[]='PARENT_RESTRICTED'; if (!empty($parent['audit_metadata']['thread_locked'])) $r[]='THREAD_LOCKED'; if (!empty($d['thread_id']) && $d['thread_id'] !== ($parent['thread_id'] ?? '')) $r[]='THREAD_MISMATCH'; if ($this->has_cycle($parent['post_id'])) $r[]='PARENT_CYCLE'; }
        }
        if (!in_array($moderation, ['clear','flagged','spam','hidden','moderator_hold'], true)) $r[]='MODERATION_INPUT_UNSUPPORTED';
        if (isset($d['subject_reference'])) { try { TNet_Community_Subject_Reference::from_array((array)$d['subject_reference']); } catch (Throwable $e) { $r[]='SUBJECT_REFERENCE_INVALID'; } }
        return ['valid'=>!$r,'reason_codes'=>$r,'parent_thread_id'=>$parent['thread_id'] ?? null];
    }
    private function has_cycle(string $post_id): bool { $seen=[]; while (isset($this->posts[$post_id])) { if (isset($seen[$post_id])) return true; $seen[$post_id]=true; $post_id=(string)($this->posts[$post_id]['parent_post_id'] ?? ''); if ($post_id==='') break; } return false; }
    private function inherited_subject(?array $parent): ?TNet_Community_Subject_Reference { if (!$parent || empty($parent['owner_product'])) return null; return TNet_Community_Subject_Reference::from_array($parent); }
    private function remember(string $key, array $draft, array $result): array { $this->submissions[$key]=['draft'=>$draft,'result'=>$result]; return $result; }
    public function moderate(string $value): array { $m=['clear'=>['clear','published','MODERATION_CLEAR'],'flagged'=>['flagged','published','MODERATION_FLAGGED'],'spam'=>['spam','spam','MODERATION_SPAM'],'hidden'=>['hidden','hidden','MODERATION_HIDDEN'],'moderator_hold'=>['moderator_hold','pending','MODERATION_HOLD']]; $v=$m[$value]??['unknown','failed','MODERATION_INPUT_UNSUPPORTED']; return ['classification'=>$v[0],'state'=>$v[1],'reason_code'=>$v[2],'evidence'=>['synthetic'=>true]]; }
    public function transition(array $post,string $new,string $actor,string $reason): array { if (!in_array($new,self::TRANSITIONS[$post['publication_state']]??[],true)) return ['accepted'=>false,'reason_code'=>'LIFECYCLE_TRANSITION_INVALID','previous_state'=>$post['publication_state'],'new_state'=>$post['publication_state']]; return ['accepted'=>true,'reason_code'=>'LIFECYCLE_TRANSITION_ACCEPTED','previous_state'=>$post['publication_state'],'new_state'=>$new,'actor'=>$actor,'reason'=>$reason,'reversible'=>$new!=='deleted','visibility_effect'=>in_array($new,['published','restored'],true)?'visible':'restricted','notification_effect'=>$new==='published'?'post_commit_event':'moderation_audit','audit'=>['accepted'=>true]]; }
}
