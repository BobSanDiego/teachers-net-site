<?php
defined('ABSPATH') || exit;

final class TNet_Community_Thread_View {
    public function find(string $post_id, bool $admin=false): ?array {
        $repo = new TNet_Community_Publisher_Repository();
        $root = $repo->find_post($post_id);
        if (!$root) return null;
        $rows = $repo->list_thread($root['thread_id']);
        $by_id = [];
        foreach ($rows as $row) $by_id[$row['post_id']] = $row;
        $visible = [];
        foreach ($rows as $row) {
            if ($row['post_id'] === $root['post_id']) continue;
            $state = $row['publication_state'];
            $restricted = !in_array($state, ['published','restored'], true);
            if ($restricted && !$admin) continue;
            $lineage = $this->lineage($row, $by_id);
            if ($lineage['cycle'] || !$lineage['l1_id']) continue;
            $l1 = $by_id[$lineage['l1_id']] ?? null;
            if (!$l1) continue;
            $l1_restricted = !in_array($l1['publication_state'], ['published','restored'], true);
            if (!$admin && $l1_restricted) continue;
            $row['_level'] = $row['post_id'] === $lineage['l1_id'] ? 1 : 2;
            $row['_branch_id'] = $lineage['l1_id'];
            $row['_branch_created_at'] = $l1['created_at'];
            $row['_branch_post_id'] = $l1['post_id'];
            $row['_author_display'] = 'Local synthetic author';
            $row['_target_display'] = $this->target_display($row, $by_id, $admin);
            $visible[] = $this->safe($row);
        }
        usort($visible, [$this, 'compare_rows']);
        return ['root'=>$this->safe(array_merge($root, ['_level'=>0,'_author_display'=>'Local synthetic author'])), 'rows'=>$visible, 'thread_id'=>$root['thread_id']];
    }

    private function lineage(array $row, array $by_id): array {
        if (!empty($row['conversation_root_id']) && isset($by_id[$row['conversation_root_id']])) {
            return ['l1_id'=>$row['conversation_root_id'],'cycle'=>false];
        }
        $current = $row; $seen = [];
        while (!empty($current['parent_post_id'])) {
            $id = $current['post_id'];
            if (isset($seen[$id])) return ['l1_id'=>null,'cycle'=>true];
            $seen[$id] = true;
            $parent_id = $current['parent_post_id'];
            if (!isset($by_id[$parent_id])) return ['l1_id'=>null,'cycle'=>false];
            $parent = $by_id[$parent_id];
            if (($parent['post_type'] ?? '') === 'topic') return ['l1_id'=>$current['post_id'],'cycle'=>false];
            $current = $parent;
        }
        return ['l1_id'=>null,'cycle'=>false];
    }

    private function target_display(array $row, array $by_id, bool $admin): ?array {
        if (($row['_level'] ?? 0) < 2 || empty($row['reply_to_post_id'])) return null;
        $target = $by_id[$row['reply_to_post_id']] ?? null;
        if (!$target || (!$admin && !in_array($target['publication_state'], ['published','restored'], true))) {
            return ['label'=>'Replying to a previous reply','post_id'=>null];
        }
        return ['label'=>'Replying to Local synthetic author','post_id'=>$target['post_id']];
    }

    private function compare_rows(array $a, array $b): int {
        $branch_time = strcmp((string)$a['_branch_created_at'], (string)$b['_branch_created_at']);
        if ($branch_time !== 0) return $branch_time;
        $branch = strcmp((string)$a['_branch_post_id'], (string)$b['_branch_post_id']);
        if ($branch !== 0) return $branch;
        if ($a['_level'] !== $b['_level']) return $a['_level'] <=> $b['_level'];
        $time = strcmp((string)$a['created_at'], (string)$b['created_at']);
        return $time !== 0 ? $time : strcmp((string)$a['post_id'], (string)$b['post_id']);
    }

    private function safe(array $row): array {
        return ['post_id'=>$row['post_id'],'community_id'=>$row['community_id'],'thread_id'=>$row['thread_id'],'parent_post_id'=>$row['parent_post_id'] ?? null,'reply_to_post_id'=>$row['reply_to_post_id'] ?? null,'reply_to_author_id'=>$row['reply_to_author_id'] ?? null,'post_type'=>$row['post_type'],'title'=>$row['title'],'body'=>$row['body'],'publication_state'=>$row['publication_state'],'created_at'=>$row['created_at'],'published_at'=>$row['published_at'],'_level'=>$row['_level'] ?? 0,'_branch_id'=>$row['_branch_id'] ?? null,'_branch_created_at'=>$row['_branch_created_at'] ?? $row['created_at'],'_branch_post_id'=>$row['_branch_post_id'] ?? $row['post_id'],'_author_display'=>$row['_author_display'] ?? 'Local synthetic author','_target_display'=>$row['_target_display'] ?? null,'_tombstone'=>false];
    }
}
