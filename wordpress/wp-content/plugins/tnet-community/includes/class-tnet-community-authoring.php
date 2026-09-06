<?php
defined('ABSPATH') || exit;
final class TNet_Community_Authoring {
    /** Durable label for slug/document identity; never a visible v1 subject field. */
    public static function body_label(string $body): string {
        $text = trim(preg_replace('/\s+/', ' ', wp_strip_all_tags($body)) ?? '');
        if ($text === '') return 'Community discussion';
        if (mb_strlen($text) <= 84) return $text;
        $slice = mb_substr($text, 0, 84);
        $boundary = mb_strrpos($slice, ' ');
        return rtrim($boundary === false ? $slice : mb_substr($slice, 0, $boundary)) . '…';
    }

    public static function is_subjectless(array $post): bool {
        $refs = is_array($post['compatibility_refs'] ?? null) ? $post['compatibility_refs'] : [];
        return !empty($refs['presentation']['subjectless']);
    }

    public static function markdown(string $source): string {
        $source = esc_html($source);
        $source = preg_replace_callback('/\[([^\]]+)\]\((https:\/\/[^)]+)\)/', static fn($m) => '<a href="'.esc_url($m[2]).'">'.$m[1].'</a>', $source);
        $source = preg_replace('/`([^`]+)`/', '<code>$1</code>', $source);
        $source = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $source);
        $source = preg_replace('/(?<!\*)\*([^*]+)\*(?!\*)/', '<em>$1</em>', $source);
        $lines = explode("\n", $source); $html=[]; $list=null;
        foreach ($lines as $line) {
            if (preg_match('/^\s*[-*]\s+(.+)$/', $line, $m)) { if ($list!=='ul') { if ($list) $html[]='</'.$list.'>'; $html[]='<ul>'; $list='ul'; } $html[]='<li>'.$m[1].'</li>'; continue; }
            if (preg_match('/^\s*\d+\.\s+(.+)$/', $line, $m)) { if ($list!=='ol') { if ($list) $html[]='</'.$list.'>'; $html[]='<ol>'; $list='ol'; } $html[]='<li>'.$m[1].'</li>'; continue; }
            if ($list) { $html[]='</'.$list.'>'; $list=null; }
            if (preg_match('/^\s*&gt;\s?(.*)$/', $line, $m)) $html[]='<blockquote>'.$m[1].'</blockquote>'; elseif (trim($line)==='---') $html[]='<hr>'; elseif (trim($line)!=='') $html[]='<p>'.$line.'</p>';
        }
        if ($list) $html[]='</'.$list.'>'; return implode('', $html);
    }
    public static function image_alt(string $title, string $body, string $override=''): string { $override=trim(sanitize_text_field($override)); if ($override!=='') return $override; $context=wp_trim_words(sanitize_textarea_field($body),12); return trim($title.($context!==''?' — '.$context:'')); }
}
