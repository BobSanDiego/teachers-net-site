<?php
defined('ABSPATH') || exit;
final class TNet_Community_Authoring {
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
