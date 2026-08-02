<?php
defined('ABSPATH') || exit;

final class TNet_Community_Attachment {
    private const TYPES = ['image','video','audio','document'];
    private const MIMES = ['image'=>'image/jpeg','video'=>'video/mp4','audio'=>'audio/mpeg','document'=>'application/pdf'];
    public static function fixture(string $type, string $alt=''): array {
        if (!in_array($type, self::TYPES, true)) throw new InvalidArgumentException('ATTACHMENT_TYPE_UNSUPPORTED');
        if ($type === 'image' && trim($alt) === '') throw new InvalidArgumentException('IMAGE_ALT_REQUIRED');
        return ['attachment_id'=>'fixture:'.substr(hash('sha256',$type.'|'.$alt),0,16),'attachment_type'=>$type,'source_kind'=>'local_fixture','source_reference'=>'fixture:community/'.$type,'title'=>ucfirst($type).' fixture','description'=>'Deterministic local fixture attachment.','alt_text'=>trim($alt),'mime_type'=>self::MIMES[$type],'file_size'=>$type==='document'?24576:1024,'width'=>$type==='image'?1200:null,'height'=>$type==='image'?800:null,'duration_seconds'=>in_array($type,['video','audio'],true)?42:null,'thumbnail_reference'=>$type==='image'?'fixture:community/image-thumb':null,'rights_status'=>'synthetic','moderation_state'=>'clear','lifecycle_state'=>'active','created_at'=>gmdate('Y-m-d H:i:s')];
    }
    public static function validate(array $attachment): array {
        if (($attachment['source_kind'] ?? '') !== 'local_fixture' || !preg_match('/^fixture:[A-Za-z0-9_\/-]+$/',(string)($attachment['source_reference']??''))) throw new InvalidArgumentException('ATTACHMENT_SOURCE_UNSUPPORTED');
        $type=(string)($attachment['attachment_type']??''); if (!isset(self::MIMES[$type])) throw new InvalidArgumentException('ATTACHMENT_TYPE_UNSUPPORTED'); if (($attachment['mime_type']??'')!==self::MIMES[$type]) throw new InvalidArgumentException('ATTACHMENT_MIME_MISMATCH'); if ($type==='image'&&trim((string)($attachment['alt_text']??''))==='') throw new InvalidArgumentException('IMAGE_ALT_REQUIRED'); return $attachment;
    }
    public static function render(array $a): string { try { self::validate($a); } catch(Throwable $e) { return '<p class="attachment-fallback">Attachment unavailable.</p>'; } $label=esc_html($a['title']??ucfirst($a['attachment_type'])); $desc=esc_html($a['description']??''); $type=$a['attachment_type']; if($type==='image') return '<figure class="attachment-card attachment-image"><div role="img" aria-label="'.esc_attr($a['alt_text']).'">Image fixture</div><figcaption>'.$label.($desc!==''?'<span>'.$desc.'</span>':'').'</figcaption></figure>'; if($type==='video') return '<div class="attachment-card"><strong>'.$label.'</strong><p>'.$desc.'</p><p>Video fixture · no embedded player.</p></div>'; if($type==='audio') return '<div class="attachment-card"><strong>'.$label.'</strong><p>'.$desc.'</p><p>Audio fixture · playback is disabled in this foundation.</p></div>'; return '<div class="attachment-card"><strong>'.$label.'</strong><p>PDF document · '.esc_html(size_format((int)($a['file_size']??0))).'</p><p>'.$desc.'</p></div>'; }
}
