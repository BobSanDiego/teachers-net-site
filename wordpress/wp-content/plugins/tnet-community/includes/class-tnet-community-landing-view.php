<?php
defined('ABSPATH') || exit;
final class TNet_Community_Landing_View {
    public function latest(int $limit=20): array { $rows=(new TNet_Community_Publisher_Repository())->list_latest_topics($limit); return array_map(function(array $row): array { $refs=$row['compatibility_refs']??$row['compatibility_json']??[]; $composer=$refs['composer']??[]; $attachments=is_array($composer['attachments']??null)?$composer['attachments']:[]; $preview=is_array($composer['preview']??null)?$composer['preview']:[]; $excerpt=wp_trim_words(wp_strip_all_tags((string)($row['body']??'')),32); return ['post_id'=>(string)($row['post_id']??''),'title'=>(string)($row['title']??'Untitled discussion'),'excerpt'=>$excerpt,'author_display'=>'Local synthetic author','reply_count'=>(int)($row['reply_count']??0),'last_activity'=>(string)($row['last_activity']?:$row['created_at']??''),'publication_state'=>(string)($row['publication_state']??''),'attachments'=>$attachments,'preview'=>$preview]; },$rows); }
}
