<?php
define('ABSPATH', __DIR__ . '/../../wordpress/');
require_once __DIR__ . '/../../wordpress/wp-content/plugins/tnet-community/includes/class-tnet-community-legacy-feed-contract.php';
require_once __DIR__ . '/../../wordpress/wp-content/plugins/tnet-community/includes/class-tnet-community-legacy-feed-adapter.php';

final class Legacy_Feed_Adapter_Fake_DB {
    public int $queries = 0;
    public array $sql = [];
    public function prepare(string $query, ...$args): string { return $query . ' /* ' . count($args) . ' args */'; }
    public function get_results(string $query): array {
        $this->queries++;
        $this->sql[] = $query;
        if (strpos($query, 'SELECT post_id, topic_id, post_type') !== false) {
            $rows = [];
            foreach ([464938, 464935] as $id) $rows[] = (object)['post_id'=>$id,'topic_id'=>$id+100000,'post_type'=>'post','post_datetime'=>'2026-08-28 10:00:00','chatboard_url'=>'/main/','post_title'=>'Fixture '.$id,'post_author'=>'Teacher','wordpress_id'=>7,'post_url'=>'teachers.net/topic/'.$id,'post_content'=>'Text','image_url'=>'','video_url'=>'','views'=>3,'local_id'=>168];
            for ($id = 100; count($rows) < 20; $id++) $rows[] = (object)['post_id'=>$id,'topic_id'=>$id+100000,'post_type'=>'post','post_datetime'=>'2026-08-27 10:00:00','chatboard_url'=>'/main/','post_title'=>'Fixture '.$id,'post_author'=>'Teacher','wordpress_id'=>7,'post_url'=>'teachers.net/topic/'.$id,'post_content'=>'Text','image_url'=>'','video_url'=>'','views'=>3,'local_id'=>168];
            return $rows;
        }
        if (strpos($query, 'FROM tnet_local_data') !== false) return [(object)['path_id'=>168,'local_short'=>'Main','local_name'=>'Main','local_path'=>'main']];
        if (strpos($query, 'FROM tnet_groups') !== false) return [(object)['group_id'=>1,'local_path'=>'main']];
        if (strpos($query, 'COUNT(1) AS member_count') !== false) return [(object)['group_id'=>1,'member_count'=>2]];
        if (strpos($query, 'SELECT group_id FROM tnet_memberships') !== false) return [];
        if (strpos($query, 'COUNT(1) AS reply_count') !== false) return [];
        if (strpos($query, 'COUNT(1) AS like_count') !== false) return [];
        if (strpos($query, 'SELECT post_id FROM tnet_likes') !== false) return [];
        if (strpos($query, 'FROM tnet_chatposts_meta') !== false) return [
            (object)['id'=>1,'post_id'=>464938,'imglink'=>'https://teachers.net/wp-content/uploads/chatposts/2026/7/6a673a7d1.png','image_width'=>300,'image_height'=>255],
            (object)['id'=>2,'post_id'=>464938,'imglink'=>'https://teachers.net/wp-content/uploads/chatposts/2026/7/second.png','image_width'=>120,'image_height'=>80],
            (object)['id'=>2,'post_id'=>464935,'imglink'=>'','image_width'=>0,'image_height'=>0],
        ];
        return [];
    }
}

$db = new Legacy_Feed_Adapter_Fake_DB();
$items = TNet_Community_Legacy_Feed_Adapter::get_items(20, 0, $db);
if (count($items) !== 20) throw new RuntimeException('Expected 20 items.');
if ((int)$items[0]['post']['id'] !== 464938 || (int)$items[1]['post']['id'] !== 464935) throw new RuntimeException('Candidate order changed.');
if ($items[0]['media']['state'] !== 'present' || count($items[0]['media']['items']) !== 2 || $items[0]['media']['items'][0]['width'] !== 300) throw new RuntimeException('AI media fixture failed.');
if ($items[1]['media']['state'] !== 'absent' || $items[1]['media']['items']) throw new RuntimeException('Text-only fixture failed.');
if ($db->queries !== 7) throw new RuntimeException('Expected 7 guest queries, got ' . $db->queries);
if (count(array_filter($db->sql, static fn($sql) => strpos($sql, 'FROM tnet_chatposts_meta') !== false)) !== 1) throw new RuntimeException('Expected one media query.');
$auth_db = new Legacy_Feed_Adapter_Fake_DB();
TNet_Community_Legacy_Feed_Adapter::get_items(20, 42, $auth_db);
if ($auth_db->queries !== 9) throw new RuntimeException('Expected 9 authenticated queries, got ' . $auth_db->queries);
echo "legacy feed adapter fixtures passed: items=20 guest_queries={$db->queries} auth_queries={$auth_db->queries} media_queries=1\n";
