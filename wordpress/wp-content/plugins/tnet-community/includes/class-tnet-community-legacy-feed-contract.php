<?php
defined('ABSPATH') || exit;

/** Presentation-neutral compatibility shape for one legacy feed item. */
final class TNet_Community_Legacy_Feed_Contract {
    public static function from_legacy(object $row, array $media): array {
        $media_items = [];
        foreach ($media as $item) {
            $url = trim((string)($item->imglink ?? ''));
            $width = (int)($item->image_width ?? 0);
            $height = (int)($item->image_height ?? 0);
            if ($url === '' || $width < 1 || $height < 1 || !filter_var($url, FILTER_VALIDATE_URL)) continue;
            $media_items[] = [
                'type' => 'image',
                'source_url' => $url,
                'width' => $width,
                'height' => $height,
            ];
        }
        return [
            'post' => [
                'id' => (int)$row->post_id,
                'topic_id' => (int)$row->topic_id,
                'type' => (string)$row->post_type,
            ],
            'board' => [
                'url' => (string)$row->chatboard_url,
                'local_id' => (int)$row->local_id,
                'short_name' => (string)($row->local_short ?? ''),
                'name' => (string)($row->local_name ?? ''),
                'group_id' => (int)($row->group_id ?? 0),
                'member_count' => (int)($row->member_count ?? 0),
                'is_member' => !empty($row->is_member),
            ],
            'title' => (string)$row->post_title,
            'author' => [
                'name' => (string)$row->post_author,
                'wordpress_id' => (int)$row->wordpress_id,
            ],
            'published_at' => (string)$row->post_datetime,
            'body' => (string)$row->post_content,
            'excerpt_source' => (string)$row->post_content,
            'replies' => ['count' => (int)($row->reply_count ?? 0)],
            'likes' => [
                'count' => (int)($row->like_count ?? 0),
                'liked' => !empty($row->liked),
            ],
            'views' => (int)$row->views,
            'canonical_url' => (string)$row->post_url,
            'media' => [
                'state' => $media_items ? 'present' : 'absent',
                'items' => $media_items,
            ],
        ];
    }
}
