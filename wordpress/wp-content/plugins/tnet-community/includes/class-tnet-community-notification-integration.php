<?php
defined('ABSPATH') || exit;

/**
 * C3-owned relationship/event policy adapter for the governed shared bell.
 * Email is evaluated and audited here, but never delivered by this adapter.
 */
final class TNet_Community_Notification_Integration {
    public static function boot(): void {
        add_action('plugins_loaded', [self::class, 'register_provider_source'], 25);
        add_action('tnet_community_publication_committed', [self::class, 'on_publication'], 10, 2);
    }

    public static function register_provider_source(): void {
        if (!class_exists('TNet_Notifications_Registry') || !function_exists('tnet_notifications')) return;
        if (TNet_Notifications_Registry::has_source('community')) return;
        TNet_Notifications_Registry::register_source('community', [
            'community.reply.created' => [
                'versions' => [1 => [
                    'metadata_keys' => ['payload','meta','reply_excerpt','target_label','relationship_basis'],
                    'destinations' => ['community.thread' => [self::class, 'valid_destination']],
                ]],
                'authorize' => [self::class, 'authorize'],
                'resolve' => [self::class, 'resolve_destination'],
            ],
            'community.activity.created' => [
                'versions' => [1 => [
                    'metadata_keys' => ['payload','meta','activity_kind','target_label','relationship_basis'],
                    'destinations' => ['community.thread' => [self::class, 'valid_destination']],
                ]],
                'authorize' => [self::class, 'authorize'],
                'resolve' => [self::class, 'resolve_destination'],
            ],
        ]);
    }

    public static function valid_destination(array $args): bool {
        return !empty($args['community_id']) && !empty($args['thread_id']) && !empty($args['post_id']);
    }

    public static function resolve_destination(array $args) {
        if (!self::valid_destination($args)) return false;
        $post = (new TNet_Community_Publisher_Repository())->find_post((string)$args['post_id']);
        if (!$post || $post['community_id'] !== $args['community_id'] || $post['thread_id'] !== $args['thread_id']) return false;
        $rows = (new TNet_Community_Publisher_Repository())->list_thread((string)$post['thread_id']);
        $root = null;
        foreach ($rows as $row) if (($row['post_type'] ?? '') === 'topic') { $root = $row; break; }
        if (!$root) return false;
        $url = TNet_Community_Canonical_Route::url($root);
        if ($url === '') $url = home_url('/community/thread/' . str_replace('%3A', ':', rawurlencode($root['post_id'])) . '/');
        $fragment = 'reply-post:' . str_replace('%3A', ':', rawurlencode((string)$post['post_id']));
        return $url . '#' . $fragment;
    }

    public static function authorize(int $recipient_user_id, array $record): bool {
        return $recipient_user_id > 0 && (int)($record['recipient_user_id'] ?? 0) === $recipient_user_id;
    }

    public static function on_publication(array $event, array $post): void {
        try {
            $actor_id = self::numeric_user_id((string)($post['author_id'] ?? ''));
            if ($actor_id > 0) (new TNet_Community_Relationship_Service())->record_inferred_participation((string)$post['thread_id'], (string)$post['community_id'], $actor_id, ['source_event_id'=>$event['event_id'] ?? null]);
            if (($post['post_type'] ?? '') === 'reply') self::emit_reply($event, $post, $actor_id);
            if (($post['post_type'] ?? '') === 'topic') self::emit_activity($event, $post, $actor_id);
            (new TNet_Community_Publisher_Repository())->mark_event_dispatched((string)($event['event_id'] ?? ''));
        } catch (Throwable $error) {
            error_log('C3 notification adapter contained failure: ' . $error->getMessage());
        }
    }

    public static function evaluate_email(int $user_id, string $category = 'community_reply'): array {
        $service = new TNet_Community_Notification_Preference_Service();
        $preference = $service->get($user_id, $category, 'email');
        if (!$preference) return ['eligible'=>false,'channel'=>'email','reason_code'=>'NO_EMAIL_PREFERENCE','preference'=>$preference,'suppression'=>null];
        if ($preference['frequency'] === 'never') return ['eligible'=>false,'channel'=>'email','reason_code'=>'PREFERENCE_NEVER','preference'=>$preference,'suppression'=>null];
        $suppression = $service->email_suppression($user_id, $category);
        if ($suppression) return ['eligible'=>false,'channel'=>'email','reason_code'=>'EMAIL_SUPPRESSED_' . strtoupper((string)$suppression['reason_code']),'preference'=>$preference,'suppression'=>$suppression];
        return ['eligible'=>true,'channel'=>'email','reason_code'=>'EMAIL_ELIGIBLE_NO_DELIVERY','preference'=>$preference,'suppression'=>null];
    }

    public static function evaluate_bell(int $user_id, string $category = 'community_reply'): array {
        $preference = (new TNet_Community_Notification_Preference_Service())->get($user_id, $category, 'bell');
        if ($preference && $preference['frequency'] === 'never') return ['eligible'=>false,'channel'=>'bell','reason_code'=>'BELL_PREFERENCE_NEVER','preference'=>$preference];
        return ['eligible'=>true,'channel'=>'bell','reason_code'=>$preference ? 'BELL_' . strtoupper($preference['frequency']) : 'BELL_DEFAULT_IMMEDIATE','preference'=>$preference];
    }

    private static function emit_reply(array $event, array $post, int $actor_id): void {
        if (!function_exists('tnet_notifications') || !class_exists('TNet_Notifications_Registry')) return;
        $repo = new TNet_Community_Publisher_Repository();
        $parent = !empty($post['parent_post_id']) ? $repo->find_post((string)$post['parent_post_id']) : null;
        $recipients = []; $basis = [];
        if ($parent) {
            $owner = self::numeric_user_id((string)($parent['author_id'] ?? ''));
            if ($owner > 0 && $owner !== $actor_id) { $recipients[$owner] = true; $basis[$owner] = 'direct_reply_parent'; }
        }
        $relationships = new TNet_Community_Relationship_Service();
        foreach ($relationships->active_followers(TNet_Community_Relationship_Service::THREAD_FOLLOW, TNet_Community_Relationship_Service::thread_target_key((string)$post['thread_id'])) as $row) {
            $recipient = self::numeric_user_id((string)$row['user_id']); if ($recipient > 0 && $recipient !== $actor_id) { $recipients[$recipient] = true; $basis[$recipient] = 'thread_follow'; }
        }
        foreach ($relationships->active_followers(TNet_Community_Relationship_Service::COMMUNITY_FOLLOW, TNet_Community_Relationship_Service::community_target_key((string)$post['community_id'])) as $row) {
            $recipient = self::numeric_user_id((string)$row['user_id']); if ($recipient > 0 && $recipient !== $actor_id) { $recipients[$recipient] = true; $basis[$recipient] = $basis[$recipient] ?? 'community_follow'; }
        }
        if (!$recipients) return;
        $actor = $actor_id > 0 ? get_userdata($actor_id) : null;
        $actor_name = $actor ? (string)$actor->display_name : 'A Community member';
        $root = $parent ?: $post;
        $label = self::safe_text(trim((string)($root['title'] ?? '')) ?: 'your discussion');
        $event_id = (string)($event['event_id'] ?? 'event:' . $post['post_id']);
        foreach (array_keys($recipients) as $recipient) {
            $bell = self::evaluate_bell((int)$recipient);
            self::record_decision($event_id, (int)$recipient, 'bell', $bell['eligible'] ? 'eligible' : 'blocked', $bell['reason_code'], $basis[$recipient] ?? 'reply', $bell['preference']['frequency'] ?? 'default', null);
            if (!$bell['eligible']) continue;
            $result = tnet_notifications()->create_for_recipients([
                'event_id'=>$event_id,
                'source_product'=>'community',
                'event_type'=>'community.reply.created',
                'payload_version'=>1,
                'actor_user_id'=>$actor_id ?: null,
                'object_type'=>'community_post',
                'object_id'=>(string)$post['post_id'],
                'destination_key'=>'community.thread',
                'destination_args'=>['community_id'=>(string)$post['community_id'],'thread_id'=>(string)$post['thread_id'],'post_id'=>(string)$post['post_id']],
                'metadata'=>[
                    'payload'=>$actor_name . ' replied to your discussion: ' . $label,
                    'meta'=>'Community · ' . $label,
                    'reply_excerpt'=>self::excerpt((string)($post['body'] ?? '')),
                    'target_label'=>$label,
                    'relationship_basis'=>$basis[$recipient] ?? 'reply',
                ],
                'dedupe_key'=>'community:reply:' . $post['post_id'] . ':recipient:' . $recipient,
                'created_at'=>$post['created_at'] ?? null,
            ], [(int)$recipient]);
            if (is_wp_error($result)) self::record_decision($event_id, (int)$recipient, 'bell', 'blocked', 'PROVIDER_REJECTED', $basis[$recipient] ?? 'reply', $bell['preference']['frequency'] ?? 'default', null);
        }
    }

    private static function emit_activity(array $event, array $post, int $actor_id): void {
        if (!function_exists('tnet_notifications') || !class_exists('TNet_Notifications_Registry')) return;
        $relationships = new TNet_Community_Relationship_Service();
        $followers = $relationships->active_followers(TNet_Community_Relationship_Service::COMMUNITY_FOLLOW, TNet_Community_Relationship_Service::community_target_key((string)$post['community_id']));
        if (!$followers) return;
        $actor = $actor_id > 0 ? get_userdata($actor_id) : null;
        $actor_name = $actor ? self::safe_text((string)$actor->display_name) : 'A Community member';
        $label = self::safe_text(trim((string)($post['title'] ?? '')) ?: 'a new discussion');
        $event_id = (string)($event['event_id'] ?? 'event:' . $post['post_id']);
        foreach ($followers as $row) {
            $recipient = self::numeric_user_id((string)$row['user_id']);
            if ($recipient < 1 || $recipient === $actor_id) continue;
            $bell = self::evaluate_bell($recipient, 'community_activity');
            self::record_decision($event_id, $recipient, 'bell', $bell['eligible'] ? 'eligible' : 'blocked', $bell['reason_code'], 'community_follow', $bell['preference']['frequency'] ?? 'default', null);
            if (!$bell['eligible']) continue;
            tnet_notifications()->create_for_recipients([
                'event_id'=>$event_id,
                'source_product'=>'community',
                'event_type'=>'community.activity.created',
                'payload_version'=>1,
                'actor_user_id'=>$actor_id ?: null,
                'object_type'=>'community_post',
                'object_id'=>(string)$post['post_id'],
                'destination_key'=>'community.thread',
                'destination_args'=>['community_id'=>(string)$post['community_id'],'thread_id'=>(string)$post['thread_id'],'post_id'=>(string)$post['post_id']],
                'metadata'=>['payload'=>$actor_name . ' started a discussion: ' . $label,'meta'=>'Community activity','activity_kind'=>'new_topic','target_label'=>$label,'relationship_basis'=>'community_follow'],
                'dedupe_key'=>'community:activity:' . $post['post_id'] . ':recipient:' . $recipient,
                'created_at'=>$post['created_at'] ?? null,
            ], [$recipient]);
        }
    }

    private static function record_decision(string $event_id, int $recipient, string $channel, string $decision, string $reason, string $relationship, string $preference, ?string $suppression): void {
        global $wpdb; $table = TNet_Community_Schema::table_names()['notification_decisions']; $id = 'notification-decision:' . substr(hash('sha256', $event_id . '|' . $recipient . '|' . $channel), 0, 64); $row=['decision_id'=>$id,'event_id'=>$event_id,'recipient_user_id'=>$recipient,'channel'=>$channel,'decision'=>$decision,'reason_code'=>$reason,'relationship_basis'=>$relationship,'preference_basis'=>$preference,'suppression_basis'=>$suppression,'created_at'=>current_time('mysql', true)]; $existing=$wpdb->get_var($wpdb->prepare("SELECT id FROM {$table} WHERE decision_id=%s",$id)); if($existing) return; $wpdb->insert($table,$row,array_fill(0,count($row),'%s'));
    }

    private static function numeric_user_id(string $value): int { return preg_match('/^user:(\d+)$/', $value, $m) ? (int)$m[1] : 0; }
    private static function safe_text(string $value): string { return trim((string) preg_replace('~(?:https?|data):[^\s]+~i', '[link]', wp_strip_all_tags($value))); }
    private static function excerpt(string $value): string { $value = self::safe_text($value); return function_exists('mb_substr') ? mb_substr($value, 0, 160) : substr($value, 0, 160); }
}
