<?php
defined('ABSPATH') || exit;

/** Local v1 authoring surface for a context-owned Community discussion. */
final class TNet_Community_Topic_Composer_Controller {
    private static array $created = [];

    public static function register(): void {
        if (!self::local()) return;
        add_rewrite_rule('^community/new/?$', 'index.php?tnet_community_topic_composer=1', 'top');
        add_rewrite_rule('^community/([a-z0-9-]+)/new/?$', 'index.php?tnet_community_topic_composer=1&tnet_community_composer_community=$matches[1]', 'top');
        add_filter('query_vars', static function (array $vars): array {
            $vars[] = 'tnet_community_topic_composer';
            $vars[] = 'tnet_community_composer_community';
            return $vars;
        });
        add_action('template_redirect', [self::class, 'render']);
    }

    private static function local(): bool { return defined('DDEV_PROJECT') || (bool) getenv('DDEV_PROJECT'); }

    public static function render(): void {
        if (!get_query_var('tnet_community_topic_composer')) return;
        if (!is_user_logged_in() || !current_user_can('read')) auth_redirect();
        if (isset($_GET['tnet_preview_url'])) self::preview_endpoint();
        if ('POST' === strtoupper($_SERVER['REQUEST_METHOD'] ?? '')) self::submit();

        $slug = sanitize_title((string) get_query_var('tnet_community_composer_community'));
        $community = $slug ? (new TNet_Community_Community_Registry())->find_by_slug($slug) : null;
        if ($slug && !$community) {
            status_header(404);
            TNet_Community_Shared_Shell::render('Community not found', static function (): void {
                echo '<section class="c3-page-message"><h1>Community not found</h1></section>';
            });
        }
        self::form([], ['community' => $community['community_id'] ?? 'community:local-demo']);
        exit;
    }

    private static function communities(): array {
        $communities = ['community:local-demo' => 'Local Community'];
        $ai = (new TNet_Community_Community_Registry())->find_by_slug('ai-in-education');
        if ($ai) $communities[$ai['community_id']] = $ai['display_name'];
        return $communities;
    }

    private static function preview_endpoint(): void {
        $url = esc_url_raw(wp_unslash($_GET['tnet_preview_url'] ?? ''));
        $preview = (new TNet_Community_Link_Attachment_Service())->prepare($url, 'keep');
        nocache_headers();
        header('Content-Type: application/json; charset=' . get_bloginfo('charset'));
        echo wp_json_encode($preview);
        exit;
    }

    private static function urls(string $body): array { return TNet_Community_Composer_Contracts::https_urls($body); }

    private static function upload(string $alt): ?array {
        if (empty($_FILES['image_file']['tmp_name'])) return null;
        $file = $_FILES['image_file'];
        if ((int) $file['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Image upload failed.');
        if ((int) $file['size'] > 10485760) throw new RuntimeException('Choose an image up to 10 MB.');
        $check = wp_check_filetype_and_ext($file['tmp_name'], $file['name']);
        if (!in_array($check['type'] ?? '', ['image/jpeg', 'image/png', 'image/webp'], true)) throw new RuntimeException('Choose a JPEG, PNG, or WebP image.');
        if (trim($alt) === '') $alt = 'Community image';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        $result = wp_handle_upload($file, ['test_form' => false, 'mimes' => ['jpg'=>'image/jpeg', 'jpeg'=>'image/jpeg', 'png'=>'image/png', 'webp'=>'image/webp']]);
        if (isset($result['error'])) throw new RuntimeException('Image upload failed.');
        $relative = str_replace(wp_upload_dir()['basedir'] . '/', '', $result['file']);
        self::$created[] = $result['file'];
        return ['attachment_id'=>'upload:'.substr(hash('sha256', $relative),0,16),'attachment_type'=>'image','source_kind'=>'local_upload','source_reference'=>'wp-content/uploads/'.$relative,'title'=>sanitize_file_name($file['name']),'description'=>'Locally staged Community image.','alt_text'=>$alt,'mime_type'=>$result['type'],'file_size'=>(int)$file['size'],'rights_status'=>'author_declared','moderation_state'=>'clear','lifecycle_state'=>'active','created_at'=>gmdate('Y-m-d H:i:s')];
    }

    private static function cleanup(): void { foreach (self::$created as $path) if (is_string($path) && is_file($path)) @unlink($path); self::$created=[]; }

    private static function submit(): void {
        if (!isset($_POST['tnet_topic_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tnet_topic_nonce'])), 'tnet_community_topic')) self::form(['Nonce verification failed.']);
        $community=sanitize_text_field(wp_unslash($_POST['community_id']??'')); $sid=sanitize_text_field(wp_unslash($_POST['submission_id']??''))?:'web-'.wp_generate_uuid4(); $body=sanitize_textarea_field(wp_unslash($_POST['body']??'')); $urls=self::urls($body); $choice=sanitize_key(wp_unslash($_POST['preview_choice']??'keep')); $alt=sanitize_text_field(wp_unslash($_POST['attachment_alt']??'')); $errors=[];
        if (!isset(self::communities()[$community])) $errors[]='This Community is not available.';
        if ($body==='') $errors[]='Write something to post.';
        $attachment=null; try { $attachment=self::upload($alt); } catch (Throwable $e) { $errors[]=$e->getMessage(); }
        if ($errors) { self::cleanup(); self::form($errors, compact('community','sid','body','choice','alt')); }
        try {
            $preview=(new TNet_Community_Link_Attachment_Service())->prepare_first_eligible($urls,$choice);
            $draft=['submission_id'=>$sid,'community_id'=>$community,'author_id'=>'user:'.(int)get_current_user_id(),'post_type'=>'topic','title'=>TNet_Community_Authoring::body_label($body),'body'=>$body,'parent_post_id'=>null,'visibility'=>'public','publication_mode'=>'post_first','moderation_input'=>'clear','compatibility_refs'=>['presentation'=>['subjectless'=>true,'label_origin'=>'body_first_v1'],'composer'=>['attachments'=>$attachment?[$attachment]:[],'links'=>array_map(static fn($url)=>['url'=>$url,'enrichment'=>'mocked'],$urls),'preview'=>$preview,'representative_url'=>(string)($preview['url']??'')]],'audit_context'=>['source'=>'local-natural-composer']];
            $result=(new TNet_Community_Publisher_Application())->publish_and_persist($draft,array_fill_keys(array_keys(self::communities()),['active'=>true]),['actor_id'=>$draft['author_id']]);
            if (empty($result['accepted'])) throw new RuntimeException('The discussion could not be published. Please try again.');
        } catch (Throwable $e) { self::cleanup(); self::form([$e->getMessage()], compact('community','sid','body','choice','alt')); }
        $url=TNet_Community_Canonical_Route::url($result['post']); if ($url==='') $url=home_url('/community/thread/'.str_replace('%3A',':',rawurlencode($result['post']['post_id'])).'/'); wp_safe_redirect($url); exit;
    }

    private static function form(array $errors, array $values=[]): void {
        $community=$values['community']??'community:local-demo'; $sid=$values['sid']??'web-'.wp_generate_uuid4(); $body=$values['body']??''; $choice=$values['choice']??'keep'; $alt=$values['alt']??''; $current=(new TNet_Community_Community_Registry())->find($community); $name=$current['display_name']??(self::communities()[$community]??'Community'); $back=$current?home_url('/community/'.$current['slug'].'/'):home_url('/community/');
        status_header(200); nocache_headers(); header('X-Robots-Tag: noindex,nofollow');
        $html='<p class="thread-navigation"><a class="back-to-community" href="'.esc_url($back).'">← Back to '.esc_html($name).'</a></p><header class="composer-context"><p>Posting to</p><h1>'.esc_html($name).'</h1></header>';
        if ($errors) $html.='<div class="errors" role="alert"><ul>'.implode('',array_map(static fn($error)=>'<li>'.esc_html($error).'</li>',$errors)).'</ul><p>Please reselect an image if the browser cleared the file input.</p></div>';
        $html.='<form data-composer-view="scoped-topic" class="c3-topic-composer" method="post" enctype="multipart/form-data">'.wp_nonce_field('tnet_community_topic','tnet_topic_nonce',true,false).'<input type="hidden" name="submission_id" value="'.esc_attr($sid).'"><input type="hidden" name="community_id" value="'.esc_attr($community).'"><label class="screen-reader-text" for="body">Share with '.esc_html($name).'</label><textarea id="body" name="body" rows="8" placeholder="Share a thought, question, or resource with '.esc_attr($name).'…" required>'.esc_textarea($body).'</textarea><section id="staged-content" class="staged" aria-live="polite"><p id="image-status" class="status">Add an image, paste one, or drop one here.</p><div id="image-preview"></div><div id="dropzone" class="dropzone"><button type="button" class="secondary" id="add-photo">Add Photo</button><input id="image_file" name="image_file" type="file" accept="image/jpeg,image/png,image/webp" hidden><span class="drop-hint"> Drop a JPEG, PNG, or WebP image</span></div><button type="button" class="secondary" id="remove-image" hidden>Remove image</button><input id="attachment_alt" name="attachment_alt" value="'.esc_attr($alt).'" type="hidden"><input id="preview_choice" name="preview_choice" value="'.esc_attr($choice).'" type="hidden"><div id="link-preview" class="preview" hidden></div></section><div class="actions"><button type="submit">Post</button><a href="'.esc_url($back).'">Cancel</a></div></form>'.self::script();
        TNet_Community_Shared_Shell::render('Post to '.$name, static function () use ($html): void { echo '<section class="c3-community-page c3-composer-page">'.$html.'</section>'; });
    }

    private static function script(): string { return <<<'HTML'
<script>(function(){
const body=document.getElementById('body'),file=document.getElementById('image_file'),add=document.getElementById('add-photo'),zone=document.getElementById('dropzone'),staged=document.getElementById('staged-content'),status=document.getElementById('image-status'),preview=document.getElementById('image-preview'),remove=document.getElementById('remove-image'),alt=document.getElementById('attachment_alt'),link=document.getElementById('link-preview'),choice=document.getElementById('preview_choice');let objectUrl=null,timer=null,suppressed='';
const urls=()=>[...new Set((body.value.match(/https:\/\/[^\s<>"']+/gi)||[]).map(url=>url.replace(/[.,!?;:)]+$/,'')))];
function clear(){if(objectUrl)URL.revokeObjectURL(objectUrl);objectUrl=null;file.value='';preview.innerHTML='';remove.hidden=true;staged.classList.remove('has-image');status.textContent='Add an image, paste one, or drop one here.'}
function stage(item){if(!item)return;if(!['image/jpeg','image/png','image/webp'].includes(item.type)||item.size>10485760){clear();status.textContent='Choose a JPEG, PNG, or WebP image up to 10 MB.';return}if(objectUrl)URL.revokeObjectURL(objectUrl);objectUrl=URL.createObjectURL(item);try{const data=new DataTransfer();data.items.add(item);file.files=data.files}catch(error){status.textContent='Choose the image with Add Photo.';return}staged.classList.add('has-image');status.textContent='Image ready';preview.innerHTML='<img src="'+objectUrl+'" alt="">';remove.hidden=false;alt.value=alt.value||'Community image'}
function show(data){const meta=data.metadata||{};link.innerHTML='';if(data.status!=='preview'||choice.value==='remove'){link.hidden=true;return}const card=document.createElement('div');card.className='link-card';if(meta.image_url){const image=document.createElement('img');image.src=meta.image_url;image.alt='';card.appendChild(image)}const title=document.createElement('strong');title.textContent=meta.title||new URL(data.url).hostname;card.appendChild(title);if(meta.description){const description=document.createElement('p');description.textContent=meta.description;card.appendChild(description)}const action=document.createElement('button');action.type='button';action.className='secondary';action.textContent='Remove preview';action.onclick=()=>{suppressed=data.url;choice.value='remove';link.hidden=true};card.appendChild(action);link.appendChild(card);link.hidden=false}
function resolve(){const candidates=urls().filter(url=>url!==suppressed);if(!candidates.length){link.hidden=true;return}clearTimeout(timer);timer=setTimeout(async()=>{for(const url of candidates){try{const response=await fetch(location.pathname+'?tnet_preview_url='+encodeURIComponent(url),{credentials:'same-origin'});const data=await response.json();if(data.status==='preview'){show(data);return}}catch(error){}}link.hidden=true},350)}
add.addEventListener('click',()=>file.click());file.addEventListener('change',event=>stage(event.target.files[0]));remove.addEventListener('click',clear);['dragenter','dragover'].forEach(type=>zone.addEventListener(type,event=>{event.preventDefault();zone.classList.add('active')}));['dragleave','drop'].forEach(type=>zone.addEventListener(type,event=>{event.preventDefault();zone.classList.remove('active')}));zone.addEventListener('drop',event=>stage(event.dataTransfer.files[0]));body.addEventListener('paste',event=>{for(const item of (event.clipboardData?.items||[])){if(item.kind==='file'&&item.type.startsWith('image/')){event.preventDefault();stage(item.getAsFile());break}}});body.addEventListener('input',resolve);document.querySelector('.c3-topic-composer').addEventListener('submit',()=>clearTimeout(timer));resolve();window.addEventListener('unload',()=>{if(objectUrl)URL.revokeObjectURL(objectUrl)});
})();</script>
HTML;
    }
}
