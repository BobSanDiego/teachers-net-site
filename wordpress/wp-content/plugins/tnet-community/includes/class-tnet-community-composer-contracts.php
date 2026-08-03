<?php
defined('ABSPATH') || exit;
final class TNet_Community_Composer_Contracts {
    public static function https_urls(string $body): array { preg_match_all('~https://[^\s<>"\']+~i', $body, $matches); $urls=[]; foreach($matches[0]??[] as $url){$url=rtrim($url,'.,!?;:)');if(!in_array($url,$urls,true))$urls[]=$url;} return $urls; }
    public static function representative(array $urls,string $selected=''): string { return in_array($selected,$urls,true)?$selected:($urls[0]??''); }
    public static function markdown_help(): string { return 'Use **bold**, *italic*, inline code, quotes, lists, or [links](https://example.com).'; }
    public static function staged_image(array $file=[],string $preview='',string $alt='',array $errors=[]): array { return ['file_name'=>sanitize_file_name((string)($file['name']??'')),'mime_type'=>(string)($file['type']??''),'file_size'=>(int)($file['size']??0),'preview_url'=>$preview,'accessibility_description'=>sanitize_text_field($alt),'validation_errors'=>array_values($errors),'state'=>$file?'staged':'empty']; }
    public static function upload_image(string $alt=''): ?array {
        if (empty($_FILES['image_file']['tmp_name'])) return null;
        $file=$_FILES['image_file']; if ((int)$file['error']!==UPLOAD_ERR_OK) throw new RuntimeException('Image upload failed.');
        if ((int)$file['size']>10485760) throw new RuntimeException('Choose an image up to 10 MB.');
        $check=wp_check_filetype_and_ext($file['tmp_name'],$file['name']); if(!in_array($check['type']??'', ['image/jpeg','image/png','image/webp'],true)) throw new RuntimeException('Choose a JPEG, PNG, or WebP image.');
        if(trim($alt)==='') throw new RuntimeException('Add image description before publishing.');
        require_once ABSPATH.'wp-admin/includes/file.php'; $result=wp_handle_upload($file,['test_form'=>false,'mimes'=>['jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','webp'=>'image/webp']]);
        if(isset($result['error'])) throw new RuntimeException('Image upload failed.');
        $relative=str_replace(wp_upload_dir()['basedir'].'/','',$result['file']);
        return ['attachment_id'=>'upload:'.substr(hash('sha256',$relative),0,16),'attachment_type'=>'image','source_kind'=>'local_upload','source_reference'=>'wp-content/uploads/'.$relative,'title'=>sanitize_file_name($file['name']),'description'=>'','alt_text'=>sanitize_text_field($alt),'mime_type'=>$result['type'],'file_size'=>(int)$file['size'],'rights_status'=>'author_declared','moderation_state'=>'clear','lifecycle_state'=>'active','created_at'=>gmdate('Y-m-d H:i:s')];
    }
}
