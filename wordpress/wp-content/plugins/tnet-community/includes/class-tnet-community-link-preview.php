<?php
defined('ABSPATH') || exit;

final class TNet_Community_Link_Preview {
    public static function fixture(string $url): array {
        $url = esc_url_raw($url);
        if ($url === '' || !wp_http_validate_url($url)) return ['status'=>'raw_only','url'=>$url,'metadata'=>[],'source'=>'none'];
        $host = strtolower((string)wp_parse_url($url, PHP_URL_HOST));
        $metadata = $host === 'example.com' ? ['title'=>'Example Domain','description'=>'A fixture preview used for local Community QA.','image_url'=>null] : ['title'=>$host,'description'=>'Preview metadata is available from a local fixture only.','image_url'=>null];
        return ['status'=>'fixture_ready','url'=>$url,'metadata'=>$metadata,'source'=>'local-fixture','cached_at'=>gmdate('Y-m-d H:i:s')];
    }
    public static function apply(array $preview, string $choice): array {
        $choice = in_array($choice, ['keep','remove','raw'], true) ? $choice : 'keep';
        if ($choice === 'remove') return ['status'=>'removed','url'=>$preview['url'] ?? '','metadata'=>[],'source'=>$preview['source'] ?? 'local-fixture'];
        if ($choice === 'raw') return ['status'=>'raw_only','url'=>$preview['url'] ?? '','metadata'=>[],'source'=>$preview['source'] ?? 'local-fixture'];
        return $preview;
    }
}
