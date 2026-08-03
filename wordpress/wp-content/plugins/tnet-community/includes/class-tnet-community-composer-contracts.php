<?php
defined('ABSPATH') || exit;
final class TNet_Community_Composer_Contracts {
    public static function https_urls(string $body): array { preg_match_all('~https://[^\s<>"\']+~i', $body, $matches); $urls=[]; foreach($matches[0]??[] as $url){$url=rtrim($url,'.,!?;:)');if(!in_array($url,$urls,true))$urls[]=$url;} return $urls; }
    public static function representative(array $urls,string $selected=''): string { return in_array($selected,$urls,true)?$selected:($urls[0]??''); }
    public static function markdown_help(): string { return 'Use **bold**, *italic*, inline code, quotes, lists, or [links](https://example.com).'; }
    public static function staged_image(array $file=[],string $preview='',string $alt='',array $errors=[]): array { return ['file_name'=>sanitize_file_name((string)($file['name']??'')),'mime_type'=>(string)($file['type']??''),'file_size'=>(int)($file['size']??0),'preview_url'=>$preview,'accessibility_description'=>sanitize_text_field($alt),'validation_errors'=>array_values($errors),'state'=>$file?'staged':'empty']; }
}
