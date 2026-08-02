<?php
defined('ABSPATH') || exit;

interface TNet_Community_Url_Admission_Policy { public function admit(string $url): array; }
interface TNet_Community_Destination_Authorizer { public function authorize(string $host, array $fixture=[]): array; }
interface TNet_Community_Bounded_Transport { public function fetch(array $request, array $fixture=[]): array; }
interface TNet_Community_Metadata_Extractor { public function extract(array $response): array; }
interface TNet_Community_Metadata_Sanitizer { public function sanitize(array $metadata, string $base_url): array; }
interface TNet_Community_Provider_Adapter { public function classify(string $url): string; }

final class TNet_Community_Mock_Url_Policy implements TNet_Community_Url_Admission_Policy {
    public function admit(string $url): array {
        if (!filter_var($url,FILTER_VALIDATE_URL)) return $this->bad('URL_MALFORMED'); $p=wp_parse_url($url); $scheme=strtolower((string)($p['scheme']??'')); $host=(string)($p['host']??'');
        if ($scheme!=='https') return $this->bad('SCHEME_UNSUPPORTED'); if ($host===''||isset($p['user'])||isset($p['pass'])||isset($p['fragment'])) return $this->bad('URL_COMPONENT_UNSUPPORTED'); if (isset($p['port'])&&(int)$p['port']!==443) return $this->bad('PORT_UNSUPPORTED'); if (preg_match('/[%*\\\s]/',$host)) return $this->bad('HOST_AMBIGUOUS'); if (!preg_match('/^[A-Za-z0-9.-]+$/',$host)&&filter_var($host,FILTER_VALIDATE_IP)===false) return $this->bad('HOST_INVALID');
        $normalized='https://'.strtolower(rtrim($host,'.')).(isset($p['path'])?$p['path']:'').(isset($p['query'])?'?'.$p['query']:''); return ['accepted'=>true,'reason_code'=>'URL_ACCEPTED','submitted_url'=>$url,'normalized_url'=>$normalized,'fetch_identity'=>hash('sha256',$normalized),'host'=>strtolower($host),'port'=>443];
    }
    private function bad(string $reason): array { return ['accepted'=>false,'reason_code'=>$reason,'submitted_url'=>'','normalized_url'=>null,'fetch_identity'=>null]; }
}
final class TNet_Community_Mock_Destination_Authorizer implements TNet_Community_Destination_Authorizer {
    public function authorize(string $host,array $fixture=[]): array { $class=$fixture['classification']??$this->classify($host); $allowed=$class==='public'; return ['allowed'=>$allowed,'classification'=>$class,'reason_code'=>$allowed?'DESTINATION_ALLOWED':'DESTINATION_RESTRICTED','host'=>$host]; }
    private function classify(string $host): string { $h=strtolower($host); if(in_array($h,['localhost','localhost.localdomain'],true)) return 'internal'; if(filter_var($h,FILTER_VALIDATE_IP)){ if(filter_var($h,FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE|FILTER_FLAG_NO_RES_RANGE)===false) return str_contains($h,':')?'private_ipv6':'private_ipv4'; } return 'public'; }
}
final class TNet_Community_Mock_Transport implements TNet_Community_Bounded_Transport {
    public function fetch(array $request,array $fixture=[]): array { $out=$fixture['outcome']??'success'; $allowed=['success','timeout_connection','timeout_total','tls_failure','redirect','redirect_loop','headers_oversize','body_oversize','decompression_oversize','mime_unsupported','failure']; if(!in_array($out,$allowed,true)) $out='failure'; if($out!=='success'&&$out!=='redirect') return ['ok'=>false,'outcome'=>$out,'reason_code'=>'TRANSPORT_'.strtoupper($out)]; return ['ok'=>true,'outcome'=>$out,'status'=>200,'mime'=>'text/html','body'=>$fixture['body']??'<title>Fixture</title><meta name="description" content="Mock preview">','redirect_url'=>$fixture['redirect_url']??null]; }
}
final class TNet_Community_Mock_Metadata_Extractor implements TNet_Community_Metadata_Extractor { public function extract(array $response): array { if(empty($response['ok'])) return ['status'=>'extract_failed','reason_code'=>$response['reason_code']??'TRANSPORT_FAILED']; $body=(string)($response['body']??''); preg_match('/<title[^>]*>(.*?)<\/title>/is',$body,$t); preg_match('/name=["\']description["\']\s+content=["\'](.*?)["\']/is',$body,$d); preg_match('/property=["\']og:title["\']\s+content=["\'](.*?)["\']/is',$body,$og); return ['status'=>'extracted','title'=>$og[1]??($t[1]??''),'description'=>$d[1]??'','canonical_url'=>null,'image_url'=>null,'provider'=>'generic']; } }
final class TNet_Community_Mock_Metadata_Sanitizer implements TNet_Community_Metadata_Sanitizer { public function sanitize(array $m,string $base_url): array { foreach(['title','description','canonical_url','image_url'] as $k) if(isset($m[$k])) {$m[$k]=wp_strip_all_tags((string)$m[$k]); if(strlen($m[$k])>500) $m[$k]=substr($m[$k],0,500);} foreach(['canonical_url','image_url'] as $k) if(!empty($m[$k])&&!preg_match('/^https:\/\//i',$m[$k])) $m[$k]=null; return $m; } }
final class TNet_Community_Mock_Provider implements TNet_Community_Provider_Adapter { public function classify(string $url): string { $host=strtolower((string)wp_parse_url($url,PHP_URL_HOST)); if($host==='youtube.com'||$host==='www.youtube.com') return 'youtube'; if($host==='vimeo.com'||$host==='www.vimeo.com') return 'vimeo'; if($host==='example.com') return 'generic'; return 'unsupported'; } }
final class TNet_Community_Mock_Preview_Cache {
    private array $items=[]; private array $history=[]; private array $last_refresh=[];
    public function get(string $identity): ?array { return $this->items[$identity]??null; }
    public function put(string $identity,array $preview,string $state='positive'): array { $item=['identity'=>$identity,'state'=>$state,'preview'=>$preview,'updated_at'=>gmdate('Y-m-d H:i:s'),'policy_version'=>'mock-v1','provider_version'=>'mock-v1']; $this->items[$identity]=$item; $this->history[]=$item; return $item; }
    public function refresh(string $identity,array $preview): array { if(isset($this->last_refresh[$identity])) return $this->items[$identity]+['reason_code'=>'REFRESH_THROTTLED']; $this->last_refresh[$identity]=time(); return $this->put($identity,$preview); }
    public function suppress(string $identity,string $state): array { return $this->put($identity,$this->items[$identity]['preview']??[],$state); }
    public function history(): array { return $this->history; }
}
final class TNet_Community_Mocked_Link_Fetch_Application {
    public function __construct(private ?TNet_Community_Url_Admission_Policy $policy=null,private ?TNet_Community_Destination_Authorizer $destination=null,private ?TNet_Community_Bounded_Transport $transport=null,private ?TNet_Community_Metadata_Extractor $extractor=null,private ?TNet_Community_Metadata_Sanitizer $sanitizer=null,private ?TNet_Community_Provider_Adapter $provider=null,private ?TNet_Community_Mock_Preview_Cache $cache=null){$this->policy??=new TNet_Community_Mock_Url_Policy();$this->destination??=new TNet_Community_Mock_Destination_Authorizer();$this->transport??=new TNet_Community_Mock_Transport();$this->extractor??=new TNet_Community_Mock_Metadata_Extractor();$this->sanitizer??=new TNet_Community_Mock_Metadata_Sanitizer();$this->provider??=new TNet_Community_Mock_Provider();$this->cache??=new TNet_Community_Mock_Preview_Cache();}
    public function evaluate(string $url,array $fixtures=[]): array { $admit=$this->policy->admit($url); if(empty($admit['accepted'])) return ['status'=>'raw_only','raw_url'=>$url,'reason_code'=>$admit['reason_code']]; $dest=$this->destination->authorize($admit['host'],$fixtures['destination']??[]); if(empty($dest['allowed'])) return ['status'=>'raw_only','raw_url'=>$url,'reason_code'=>$dest['reason_code']]; $transport=$this->transport->fetch($admit,$fixtures['transport']??[]); if(empty($transport['ok'])) return ['status'=>'raw_only','raw_url'=>$url,'reason_code'=>$transport['reason_code']]; $meta=$this->sanitizer->sanitize($this->extractor->extract($transport),$admit['normalized_url']); $meta['provider']=$this->provider->classify($admit['normalized_url']); $cached=$this->cache->put($admit['fetch_identity'],$meta,empty($meta['title'])?'negative':'positive'); return ['status'=>'preview','raw_url'=>$url,'normalized_url'=>$admit['normalized_url'],'preview'=>$meta,'cache'=>$cached,'reason_code'=>'MOCK_PREVIEW_READY']; }
    public function cache(): TNet_Community_Mock_Preview_Cache { return $this->cache; }
}
