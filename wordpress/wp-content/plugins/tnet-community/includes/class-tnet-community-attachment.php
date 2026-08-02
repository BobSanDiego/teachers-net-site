<?php
defined('ABSPATH') || exit;

final class TNet_Community_Attachment {
    private const TYPES = ['image','video','audio','document'];
    private const MIMES = ['image/jpeg','image/png','image/webp'];
    public static function fixture(string $type, string $alt=''): array {
        if (!in_array($type, self::TYPES, true)) throw new InvalidArgumentException('ATTACHMENT_TYPE_UNSUPPORTED');
        if ($type === 'image' && trim($alt) === '') throw new InvalidArgumentException('IMAGE_ALT_REQUIRED');
        return ['attachment_id'=>'fixture:'.substr(hash('sha256',$type.'|'.$alt),0,16),'attachment_type'=>$type,'source_kind'=>'local_fixture','source_reference'=>'fixture:community/'.$type,'title'=>ucfirst($type).' fixture','description'=>'Deterministic local fixture attachment.','alt_text'=>trim($alt),'mime_type'=>$type==='image'?'image/jpeg':($type==='video'?'video/mp4':($type==='audio'?'audio/mpeg':'application/pdf')),'file_size'=>1024,'width'=>$type==='image'?1200:null,'height'=>$type==='image'?800:null,'duration_seconds'=>null,'thumbnail_reference'=>null,'rights_status'=>'synthetic','moderation_state'=>'clear','lifecycle_state'=>'active','created_at'=>gmdate('Y-m-d H:i:s')];
    }
    public static function validate(array $a): array {
        $source=(string)($a['source_kind']??''); $ref=(string)($a['source_reference']??'');
        if (($source==='local_fixture'&&!preg_match('/^fixture:[A-Za-z0-9_\/-]+$/',$ref)) || ($source==='local_upload'&&!preg_match('/^wp-content\/uploads\/[A-Za-z0-9_.\/-]+$/',$ref)) || !in_array($source,['local_fixture','local_upload'],true)) throw new InvalidArgumentException('ATTACHMENT_SOURCE_UNSUPPORTED');
        $type=(string)($a['attachment_type']??''); $mime=(string)($a['mime_type']??'');
        if ($type==='image'&&!in_array($mime,self::MIMES,true)) throw new InvalidArgumentException('ATTACHMENT_MIME_MISMATCH');
        if ($type!=='image'&&!in_array($type,['video','audio','document'],true)) throw new InvalidArgumentException('ATTACHMENT_TYPE_UNSUPPORTED');
        if ($type==='image'&&trim((string)($a['alt_text']??''))==='') throw new InvalidArgumentException('IMAGE_ALT_REQUIRED');
        return $a;
    }
    public static function render(array $a): string {
        try { self::validate($a); } catch (Throwable $e) { return '<p class="attachment-fallback">Attachment unavailable.</p>'; }
        $label=esc_html($a['title']??ucfirst($a['attachment_type'])); $desc=esc_html($a['description']??''); $type=$a['attachment_type'];
        if ($type==='image') {
            if (($a['source_kind']??'')==='local_fixture') return '<figure class="attachment-card attachment-image"><div role="img" aria-label="'.esc_attr($a['alt_text']).'">Image fixture</div><figcaption>'.$label.'</figcaption></figure>';
            $ref=(string)$a['source_reference']; $url=content_url(substr($ref,strlen('wp-content/')));
            return '<figure class="attachment-card attachment-image"><img src="'.esc_url($url).'" alt="'.esc_attr($a['alt_text']).'" loading="lazy"><figcaption>'.$label.($desc!==''?'<span>'.$desc.'</span>':'').'</figcaption></figure>';
        }
        return '<div class="attachment-card"><strong>'.$label.'</strong><p>'.$desc.'</p></div>';
    }
}
