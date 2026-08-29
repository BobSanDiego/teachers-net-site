<?php
defined('ABSPATH') || exit;

/** Read-only, bounded adapter for the currently proven legacy feed. */
final class TNet_Community_Legacy_Feed_Adapter {
    public static function get_items(int $limit = 20, ?int $user_id = null, ?object $db = null): array {
        if (!$db) { global $wpdb; $db = $wpdb; }
        if (!$db) return [];
        $user_id = $user_id === null && function_exists('get_current_user_id') ? (int)get_current_user_id() : (int)$user_id;
        $limit = max(1, min(20, $limit));
        $rows = $db->get_results("SELECT post_id, topic_id, post_type, post_datetime, chatboard_url, post_title, post_author, wordpress_id, post_url, post_content, image_url, video_url, views, local_id FROM tnet_chatposts WHERE status = 0 AND post_type = 'post' ORDER BY post_datetime DESC, post_id DESC LIMIT {$limit}");
        if (empty($rows)) return [];

        $local_ids = self::unique_ids($rows, 'local_id');
        $local_map = [];
        if ($local_ids) {
            $ph = implode(',', array_fill(0, count($local_ids), '%d'));
            foreach ($db->get_results($db->prepare("SELECT path_id, local_short, local_name, local_path FROM tnet_local_data WHERE path_id IN ({$ph})", $local_ids)) as $local) $local_map[(int)$local->path_id] = $local;
        }
        $group_map = [];
        $paths = [];
        foreach ($local_map as $local) if ((string)$local->local_path !== '') $paths[] = (string)$local->local_path;
        $paths = array_values(array_unique($paths));
        if ($paths) {
            $ph = implode(',', array_fill(0, count($paths), '%s'));
            foreach ($db->get_results($db->prepare("SELECT group_id, local_path FROM tnet_groups WHERE local_path IN ({$ph})", $paths)) as $group) $group_map[(string)$group->local_path] = (int)$group->group_id;
        }
        $group_ids = [];
        foreach ($local_map as $local) if (isset($group_map[(string)$local->local_path])) $group_ids[] = $group_map[(string)$local->local_path];
        $group_ids = array_values(array_unique($group_ids));
        $member_counts = $memberships = [];
        if ($group_ids) {
            $ph = implode(',', array_fill(0, count($group_ids), '%d'));
            foreach ($db->get_results($db->prepare("SELECT group_id, COUNT(1) AS member_count FROM tnet_memberships WHERE group_id IN ({$ph}) AND status = 1 GROUP BY group_id", $group_ids)) as $member) $member_counts[(int)$member->group_id] = (int)$member->member_count;
            if ($user_id) {
                $args = array_merge([$user_id], $group_ids);
                foreach ($db->get_results($db->prepare("SELECT group_id FROM tnet_memberships WHERE user_id = %d AND group_id IN ({$ph}) AND status = 1", $args)) as $membership) $memberships[(int)$membership->group_id] = true;
            }
        }
        $topic_ids = self::unique_ids($rows, 'topic_id');
        $post_ids = self::unique_ids($rows, 'post_id');
        $reply_counts = $like_counts = $liked = $latest_activity = [];
        if ($topic_ids) {
            $ph = implode(',', array_fill(0, count($topic_ids), '%d'));
            foreach ($db->get_results($db->prepare("SELECT topic_id, COUNT(1) AS reply_count FROM tnet_chatposts WHERE topic_id IN ({$ph}) AND post_type = 'reply' AND status = 0 GROUP BY topic_id", $topic_ids)) as $reply) $reply_counts[(int)$reply->topic_id] = (int)$reply->reply_count;
            foreach ($db->get_results($db->prepare("SELECT topic_id, MAX(post_datetime) AS latest_activity_at FROM tnet_chatposts WHERE topic_id IN ({$ph}) AND status = 0 GROUP BY topic_id", $topic_ids)) as $activity) $latest_activity[(int)$activity->topic_id] = (string)$activity->latest_activity_at;
        }
        if ($post_ids) {
            $ph = implode(',', array_fill(0, count($post_ids), '%d'));
            foreach ($db->get_results($db->prepare("SELECT post_id, COUNT(1) AS like_count FROM tnet_likes WHERE post_id IN ({$ph}) AND status = 1 GROUP BY post_id", $post_ids)) as $like) $like_counts[(int)$like->post_id] = (int)$like->like_count;
            if ($user_id) {
                $args = array_merge([$user_id], $post_ids);
                foreach ($db->get_results($db->prepare("SELECT post_id FROM tnet_likes WHERE user_id = %d AND post_id IN ({$ph}) AND status = 1", $args)) as $like) $liked[(int)$like->post_id] = true;
            }
        }

        // Exactly one bounded media lookup; no card/file reads and no per-item query.
        $media_map = [];
        if ($post_ids) {
            $ph = implode(',', array_fill(0, count($post_ids), '%d'));
            $media_rows = $db->get_results($db->prepare("SELECT id, post_id, imglink, image_width, image_height, og_title, og_description, og_site_name, site_domain, og_url, og_image, og_type FROM tnet_chatposts_meta WHERE post_id IN ({$ph}) ORDER BY post_id ASC, id ASC", $post_ids));
            foreach ($media_rows as $media) $media_map[(int)$media->post_id][] = $media;
        }
        $items = [];
        foreach ($rows as $row) {
            $local = $local_map[(int)$row->local_id] ?? null;
            $group_id = $local && isset($group_map[(string)$local->local_path]) ? $group_map[(string)$local->local_path] : 0;
            $row->local_short = $local->local_short ?? '';
            $row->local_name = $local->local_name ?? '';
            $row->group_id = $group_id;
            $row->member_count = $member_counts[$group_id] ?? 0;
            $row->is_member = !empty($memberships[$group_id]);
            $row->reply_count = $reply_counts[(int)$row->topic_id] ?? 0;
            $row->latest_activity_at = $latest_activity[(int)$row->topic_id] ?? (string)$row->post_datetime;
            $meta = $media_map[(int)$row->post_id][0] ?? null;
            if ($meta) { foreach (['imglink','image_width','image_height','og_title','og_description','og_site_name','site_domain','og_url','og_image','og_type'] as $field) { $row->{$field} = $meta->{$field} ?? ''; } }
            $row->like_count = $like_counts[(int)$row->post_id] ?? 0;
            $row->liked = !empty($liked[(int)$row->post_id]);
            $items[] = TNet_Community_Legacy_Feed_Contract::from_legacy($row, $media_map[(int)$row->post_id] ?? []);
        }
        return $items;
    }

    private static function unique_ids(array $rows, string $key): array {
        $ids = [];
        foreach ($rows as $row) $ids[] = (int)$row->{$key};
        return array_values(array_unique($ids));
    }
}
