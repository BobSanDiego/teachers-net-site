<?php
defined('ABSPATH') || exit;

final class TNet_Community_Link_Preview {
    public static function resolve(string $url): array {
        $url=esc_url_raw($url); $p=wp_parse_url($url); $scheme=strtolower((string)($p['scheme']??'')); $host=strtolower((string)($p['host']??''));
        if(!in_array($scheme,['http','https'],true)||$host===''||isset($p['user'])||isset($p['pass'])||isset($p['fragment']))return ['status'=>'raw_only','url'=>$url,'metadata'=>[],'source'=>'none'];
        $ip=gethostbyname($host); if(!$ip||filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE|FILTER_FLAG_NO_RES_RANGE)===false)return ['status'=>'raw_only','url'=>$url,'metadata'=>[],'source'=>'blocked'];
        $response=wp_safe_remote_get($url,['timeout'=>3,'redirection'=>3,'limit_response_size'=>262144,'reject_unsafe_urls'=>true,'headers'=>['Accept'=>'text/html,application/xhtml+xml']]);
        if(is_wp_error($response)||wp_remote_retrieve_response_code($response)<200||wp_remote_retrieve_response_code($response)>=400)return ['status'=>'raw_only','url'=>$url,'metadata'=>[],'source'=>'fetch-failed'];
        $type=strtolower((string)wp_remote_retrieve_header($response,'content-type')); if(strpos($type,'text/html')===false&&strpos($type,'application/xhtml+xml')===false)return ['status'=>'raw_only','url'=>$url,'metadata'=>[],'source'=>'non-html'];
        $body=wp_remote_retrieve_body($response); $meta=[]; $doc=new DOMDocument(); @$doc->loadHTML($body); $nodes=$doc->getElementsByTagName('meta'); foreach($nodes as $node){$name=strtolower((string)$node->getAttribute('property'));if($name==='')$name=strtolower((string)$node->getAttribute('name'));$content=(string)$node->getAttribute('content');$map=['og:title'=>'title','og:description'=>'description','og:image'=>'image_url','og:site_name'=>'site_name','og:url'=>'canonical_url','twitter:title'=>'twitter_title','twitter:description'=>'twitter_description','twitter:image'=>'twitter_image'];if(isset($map[$name]))$meta[$map[$name]]=wp_strip_all_tags($content);}
        $titles=$doc->getElementsByTagName('title'); if(empty($meta['title'])&&$titles->length)$meta['title']=wp_strip_all_tags($titles->item(0)->textContent); if(empty($meta['title'])&&empty($meta['description'])&&empty($meta['image_url']))return ['status'=>'raw_only','url'=>$url,'metadata'=>[],'source'=>'no-metadata'];
        foreach(['twitter_title'=>'title','twitter_description'=>'description','twitter_image'=>'image_url'] as $from=>$to)if(empty($meta[$to])&&!empty($meta[$from]))$meta[$to]=$meta[$from]; foreach(['title','description','site_name'] as $k)if(isset($meta[$k]))$meta[$k]=mb_substr($meta[$k],0,500); foreach(['image_url','canonical_url'] as $k)if(!empty($meta[$k])&&!preg_match('~^https?://~i',$meta[$k]))$meta[$k]=null;
        return ['status'=>'preview','url'=>$url,'metadata'=>$meta,'source'=>'open-graph','cached_at'=>gmdate('Y-m-d H:i:s')];
    }
    public static function apply(array $preview, string $choice): array {
        $choice = in_array($choice, ['keep','remove','raw'], true) ? $choice : 'keep';
        if ($choice === 'remove') return ['status'=>'removed','url'=>$preview['url'] ?? '','metadata'=>[],'source'=>$preview['source'] ?? 'local-fixture'];
        if ($choice === 'raw') return ['status'=>'raw_only','url'=>$preview['url'] ?? '','metadata'=>[],'source'=>$preview['source'] ?? 'local-fixture'];
        return $preview;
    }
    public static function render(array $preview): string {
        if (($preview['status'] ?? '') === 'removed' || ($preview['status'] ?? '') !== 'preview') return '';
        $m = is_array($preview['metadata'] ?? null) ? $preview['metadata'] : [];
        $image = !empty($m['image_url']) ? '<img src="'.esc_url($m['image_url']).'" alt="" loading="lazy">' : '';
        $title = esc_html($m['title'] ?? 'Link preview');
        $description = esc_html($m['description'] ?? '');
        $host = esc_html($m['site_name'] ?? (string)wp_parse_url((string)($preview['url'] ?? ''), PHP_URL_HOST));
        return '<aside class="card-preview link-preview" aria-label="Link preview">'.$image.'<strong>'.$title.'</strong><p>'.$description.'</p><small>'.$host.'</small></aside>';
    }
}
