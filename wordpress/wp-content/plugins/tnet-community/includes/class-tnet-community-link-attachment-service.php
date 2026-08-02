<?php
defined('ABSPATH') || exit;

final class TNet_Community_Link_Attachment_Service {
    public function prepare(string $url, string $choice='keep'): array {
        return TNet_Community_Link_Preview::apply(TNet_Community_Link_Preview::fixture($url), $choice);
    }
    public function render_placeholder(array $preview): string {
        $status = $preview['status'] ?? 'raw_only';
        if ($status === 'removed') return '<p class="preview-status">Preview removed; the raw link will remain available.</p>';
        if ($status === 'raw_only') return '<p class="preview-status">Raw link only; no preview will be stored.</p>';
        $meta = $preview['metadata'] ?? [];
        return '<aside class="link-preview" aria-label="Link preview"><strong>'.esc_html($meta['title'] ?? 'Link preview').'</strong><p>'.esc_html($meta['description'] ?? 'Fixture preview ready for local QA.').'</p><small>Local fixture preview; no live fetch performed.</small></aside>';
    }
}
