<?php
defined('ABSPATH') || exit;

/** Local v1 authoring surface for a context-owned Community discussion. */
final class TNet_Community_Topic_Composer_Controller {
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

    private static function submit(): void {
        if (!isset($_POST['tnet_topic_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tnet_topic_nonce'])), 'tnet_community_topic')) self::form(['Nonce verification failed.']);
        $community=sanitize_text_field(wp_unslash($_POST['community_id']??'')); $sid=sanitize_text_field(wp_unslash($_POST['submission_id']??''))?:'web-'.wp_generate_uuid4(); $body=sanitize_textarea_field(wp_unslash($_POST['body']??'')); $urls=self::urls($body); $choice=sanitize_key(wp_unslash($_POST['preview_choice']??'keep')); $alt=sanitize_text_field(wp_unslash($_POST['attachment_alt']??'')); $errors=[]; $requested_media=json_decode(wp_unslash($_POST['media_attachments']??'[]'),true); if(!is_array($requested_media))$requested_media=[];
        if (!isset(self::communities()[$community])) $errors[]='This Community is not available.';
        if ($body==='') $errors[]='Write something to post.';
        $media_items=[]; try { $media_items=(new TNet_Community_Media_Registry())->prepare_for_publication($requested_media,(int)get_current_user_id()); } catch (Throwable $e) { if ($requested_media) $errors[]=$e->getMessage(); }
        if ($errors) self::form($errors, compact('community','sid','body','choice','alt'));
        try {
            $preview=(new TNet_Community_Link_Attachment_Service())->prepare_first_eligible($urls,$choice);
            $draft=['submission_id'=>$sid,'community_id'=>$community,'author_id'=>'user:'.(int)get_current_user_id(),'post_type'=>'topic','title'=>TNet_Community_Authoring::body_label($body),'body'=>$body,'parent_post_id'=>null,'visibility'=>'public','publication_mode'=>'post_first','moderation_input'=>'clear','media_items'=>$media_items,'compatibility_refs'=>['presentation'=>['subjectless'=>true,'label_origin'=>'body_first_v1'],'composer'=>['attachments'=>$media_items,'links'=>array_map(static fn($url)=>['url'=>$url,'enrichment'=>'mocked'],$urls),'preview'=>$preview,'representative_url'=>(string)($preview['url']??'')]],'audit_context'=>['source'=>'local-natural-composer']];
            $result=(new TNet_Community_Publisher_Application())->publish_and_persist($draft,array_fill_keys(array_keys(self::communities()),['active'=>true]),['actor_id'=>$draft['author_id']]);
            if (empty($result['accepted'])) throw new RuntimeException('The discussion could not be published. Please try again.');
        } catch (Throwable $e) { self::form([$e->getMessage()], compact('community','sid','body','choice','alt')); }
        $return = self::return_to_feed($community);
        if ($return !== '') { wp_safe_redirect(add_query_arg('published', rawurlencode((string) $result['post']['post_id']), $return)); exit; }
        $url=TNet_Community_Canonical_Route::url($result['post']); if ($url==='') $url=home_url('/community/thread/'.str_replace('%3A',':',rawurlencode($result['post']['post_id'])).'/'); wp_safe_redirect($url); exit;
    }

    private static function form(array $errors, array $values=[]): void {
        $community=$values['community']??'community:local-demo'; $sid=$values['sid']??'web-'.wp_generate_uuid4(); $body=$values['body']??''; $choice=$values['choice']??'keep'; $alt=$values['alt']??''; $current=(new TNet_Community_Community_Registry())->find($community); $name=$current['display_name']??(self::communities()[$community]??'Community'); $back=$current?home_url('/community/'.$current['slug'].'/'):home_url('/community/');
        status_header(200); nocache_headers(); header('X-Robots-Tag: noindex,nofollow');
        $html='<p class="thread-navigation"><a class="back-to-community" href="'.esc_url($back).'">← Back to '.esc_html($name).'</a></p><header class="composer-context"><p>Posting to</p><h1>'.esc_html($name).'</h1></header>';
        if ($errors) $html.='<div class="errors" role="alert"><ul>'.implode('',array_map(static fn($error)=>'<li>'.esc_html($error).'</li>',$errors)).'</ul><p>Please reselect an image if the browser cleared the file input.</p></div>';
        $html .= self::embedded_form($community, $name, $back, array_merge(compact('sid', 'body', 'choice', 'alt'), ['action_url' => self::composer_url($community)]));
        TNet_Community_Shared_Shell::render('Post to '.$name, static function () use ($html): void { echo '<section class="c3-community-page c3-composer-page">'.$html.'</section>'; });
    }

    private static function composer_url(string $community): string {
        $current = (new TNet_Community_Community_Registry())->find($community);
        return $current ? home_url('/community/' . $current['slug'] . '/new/') : home_url('/community/new/');
    }

    private static function return_to_feed(string $community): string {
        $candidate = esc_url_raw(wp_unslash($_POST['return_to_feed'] ?? ''));
        $current = (new TNet_Community_Community_Registry())->find($community);
        if (!$current) return '';
        $expected = home_url('/community/' . $current['slug'] . '/');
        return untrailingslashit($candidate) === untrailingslashit($expected) ? $expected : '';
    }

    /** One canonical topic form, usable by direct entry and the feed dialog. */
    public static function embedded_form(string $community, string $name, string $cancel, array $values=[], bool $modal=false): string {
        $sid=$values['sid']??'web-'.wp_generate_uuid4(); $body=$values['body']??''; $choice=$values['choice']??'keep'; $alt=$values['alt']??''; $action=$values['action_url']??self::composer_url($community); $media_nonce='<input type="hidden" name="c3_media_nonce" value="'.esc_attr(wp_create_nonce('wp_rest')).'">'; $return=($modal ? '<input type="hidden" name="return_to_feed" value="'.esc_attr($values['return_to_feed']??'').'">' : '').$media_nonce; if (!($modal && !empty($values['hide_cancel']))) $return.='<div id="media-attachments" class="media-attachments"></div><input id="media_attachments" name="media_attachments" value="[]" type="hidden">';
        $cancel_control=$modal && !empty($values['hide_cancel']) ? '' : ($modal ? '<button type="button" class="secondary" data-close-composer>Cancel</button>' : '<a href="'.esc_url($cancel).'">Cancel</a>');
        if ($modal && !empty($values['hide_cancel'])) {
            $form = '<form action="' . esc_url($action) . '" data-preview-endpoint="' . esc_url($action) . '" data-media-nonce="' . esc_attr(wp_create_nonce('wp_rest')) . '" data-composer-view="scoped-topic" class="c3-topic-composer" method="post">';
            $form .= wp_nonce_field('tnet_community_topic', 'tnet_topic_nonce', true, false);
            $form .= '<input type="hidden" name="submission_id" value="' . esc_attr($sid) . '">' . $return . '<input type="hidden" name="community_id" value="' . esc_attr($community) . '">';
            $form .= '<label class="screen-reader-text" for="body">Share with ' . esc_html($name) . '</label><textarea id="body" name="body" rows="8" placeholder="Share a thought, question, or resource with ' . esc_attr($name) . '…" required>' . esc_textarea($body) . '</textarea>';
            $form .= '<section id="staged-content" class="staged" aria-live="polite"><p id="image-status" class="screen-reader-text">Image staging status.</p><div id="media-attachments" class="media-attachments"></div><input id="media_attachments" name="media_attachments" value="[]" type="hidden"><input id="preview_choice" name="preview_choice" value="' . esc_attr($choice) . '" type="hidden"><div id="link-preview" class="preview" hidden></div></section>';
            $form .= '<div class="composer-footer"><div id="dropzone" class="composer-media-actions"><button type="button" class="secondary photo-picker" id="add-photo" aria-label="Add photos"><svg aria-hidden="true" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="15" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M8 5l1-2h6l1 2"/></svg><span class="screen-reader-text">Add photos</span></button><input id="image_file" type="file" accept="image/jpeg,image/png,image/webp" multiple hidden></div><div class="actions"><button type="submit">Post</button></div></div></form>';
            return $form . self::script();
        }
        return '<form action="'.esc_url($action).'" data-preview-endpoint="'.esc_url($action).'" data-composer-view="scoped-topic" class="c3-topic-composer" method="post" enctype="multipart/form-data">'.wp_nonce_field('tnet_community_topic','tnet_topic_nonce',true,false).'<input type="hidden" name="submission_id" value="'.esc_attr($sid).'">'.$return.'<input type="hidden" name="community_id" value="'.esc_attr($community).'"><label class="screen-reader-text" for="body">Share with '.esc_html($name).'</label><textarea id="body" name="body" rows="8" placeholder="Share a thought, question, or resource with '.esc_attr($name).'…" required>'.esc_textarea($body).'</textarea><section id="staged-content" class="staged" aria-live="polite"><p id="image-status" class="screen-reader-text">Image staging status.</p><div id="image-preview" class="image-preview"><button type="button" class="image-remove" id="remove-image" aria-label="Remove image" hidden><svg aria-hidden="true" viewBox="0 0 24 24"><path d="m7 7 10 10M17 7 7 17"/></svg><span class="screen-reader-text">Remove image</span></button></div><div id="dropzone" class="composer-media-actions"><button type="button" class="secondary photo-picker" id="add-photo" aria-label="Add photo"><svg aria-hidden="true" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="15" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M8 5l1-2h6l1 2"/></svg><span class="screen-reader-text">Add photo</span></button><input id="image_file" name="image_file" type="file" accept="image/jpeg,image/png,image/webp" hidden></div><input id="attachment_alt" name="attachment_alt" value="'.esc_attr($alt).'" type="hidden"><input id="preview_choice" name="preview_choice" value="'.esc_attr($choice).'" type="hidden"><div id="link-preview" class="preview" hidden></div></section><div class="actions"><button type="submit">Post</button>'.$cancel_control.'</div></form>'.self::script();
    }

    private static function script(): string { return <<<'HTML'
<script>(function(){
const form=document.querySelector('.c3-topic-composer');
if(!form)return;
const body=document.getElementById('body'),file=document.getElementById('image_file'),add=document.getElementById('add-photo'),zone=document.getElementById('dropzone'),status=document.getElementById('image-status'),shell=document.getElementById('staged-content'),list=document.getElementById('media-attachments')||document.createElement('div'),hidden=document.getElementById('media_attachments')||document.createElement('input'),link=document.getElementById('link-preview'),choice=document.getElementById('preview_choice'),post=form.querySelector('button[type="submit"]'),nonce=(form.querySelector('[name="c3_media_nonce"]')||{}).value||'';
if(!list.id){list.id='media-attachments';list.className='media-attachments';shell.insertBefore(list,shell.firstChild.nextSibling)}if(!hidden.id){hidden.type='hidden';hidden.id='media_attachments';hidden.name='media_attachments';hidden.value='[]';form.appendChild(hidden)}file.removeAttribute('name');
const allowed=['image/jpeg','image/png','image/webp'],maxBytes=10485760,api='/wp-json/tnet-community/v1/media',items=[];let previewTimer=null,suppressed='';
const urls=()=>[...new Set((body.value.match(/https:\/\/[^\s<>"']+/gi)||[]).map(url=>url.replace(/[.,!?;:)]+$/,'')))];
function message(text){status.textContent=text;}
function sync(){hidden.value=JSON.stringify(items.map(item=>({media_id:item.media_id||'',alt_text:item.alt.value.trim()})));const ready=!items.length||items.every(item=>item.state==='ready'&&item.alt.value.trim());post.disabled=!ready;post.setAttribute('aria-disabled',String(!ready));}
function card(item){const element=document.createElement('article');element.className='media-attachment-card';element.dataset.mediaId=item.id;const image=document.createElement('img');image.src=item.url;image.alt='';element.appendChild(image);const details=document.createElement('div');details.className='media-attachment-details';const label=document.createElement('label');label.textContent='Alt text';const alt=document.createElement('input');alt.type='text';alt.required=true;alt.maxLength=240;alt.placeholder='Describe this image';alt.value='';label.appendChild(alt);details.appendChild(label);const state=document.createElement('p');state.className='media-attachment-state';state.setAttribute('aria-live','polite');details.appendChild(state);const actions=document.createElement('div');actions.className='media-attachment-actions';const retry=document.createElement('button');retry.type='button';retry.className='secondary';retry.textContent='Retry';retry.hidden=true;const remove=document.createElement('button');remove.type='button';remove.className='secondary';remove.textContent='Remove';actions.append(retry,remove);details.appendChild(actions);element.appendChild(details);list.appendChild(element);item.alt=alt;item.state='uploading';item.stateNode=state;item.retry=retry;item.urlNode=image;item.remove=remove;alt.addEventListener('input',sync);remove.addEventListener('click',()=>{const index=items.indexOf(item);if(index>=0)items.splice(index,1);if(item.objectUrl)URL.revokeObjectURL(item.objectUrl);element.remove();sync();message(items.length?'Update the remaining images before posting.':'No images staged.');});retry.addEventListener('click',()=>upload(item,true));return item;}
function valid(file){return file&&allowed.includes(file.type)&&file.size>0&&file.size<=maxBytes;}
function dimensions(file){return new Promise(resolve=>{const url=URL.createObjectURL(file),image=new Image();image.onload=()=>{const result={width:image.naturalWidth,height:image.naturalHeight};URL.revokeObjectURL(url);resolve(result)};image.onerror=()=>{URL.revokeObjectURL(url);resolve({width:0,height:0})};image.src=url;});}
async function upload(item,retry){item.state='uploading';item.retry.hidden=true;item.stateNode.textContent=retry?'Preparing retry…':'Preparing secure upload…';sync();let size=await dimensions(item.file);let response;try{response=await fetch(api+'/presign',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','X-WP-Nonce':nonce},body:JSON.stringify({file_name:item.file.name,mime_type:item.file.type,file_size:item.file.size,media_id:retry?item.media_id:'',source_width:size.width,source_height:size.height})});const data=await response.json();if(!response.ok||!data.upload)throw new Error('Unable to prepare this image.');item.media_id=data.media_id;item.key=data.key;item.state='uploading';item.stateNode.textContent='Uploading directly to secure quarantine…';const payload=new FormData();Object.entries(data.upload.fields||{}).forEach(([key,value])=>payload.append(key,value));payload.append('file',item.file);const uploadResponse=await fetch(data.upload.url,{method:'POST',body:payload});if(![201,204].includes(uploadResponse.status))throw new Error('Direct upload failed.');await poll(item);}catch(error){item.state='failed';item.retry.hidden=false;item.stateNode.textContent='Upload failed. Remove or retry this image.';message(error.message||'Image upload failed.');sync();}}
async function poll(item){item.state='processing';item.stateNode.textContent='Processing…';sync();for(let attempt=0;attempt<45;attempt++){await new Promise(resolve=>setTimeout(resolve,2000));try{const response=await fetch(api+'/'+encodeURIComponent(item.media_id),{credentials:'same-origin',headers:{'X-WP-Nonce':nonce}});const data=await response.json();if(data.state==='ready'){item.state='ready';item.variants=data.variant_urls||{};item.retry.hidden=true;item.stateNode.textContent='Ready to post';message('All staged images must be ready before posting.');sync();return}if(data.state==='failed'){item.state='failed';item.retry.hidden=false;item.stateNode.textContent='Processing failed. Remove or retry this image.';message('One image failed processing; the other staged images remain available.');sync();return}}catch(error){}}item.state='failed';item.retry.hidden=false;item.stateNode.textContent='Processing timed out. Retry or remove this image.';message('Processing is taking too long; retry or remove the unresolved image.');sync();}
async function stage(file){if(!valid(file)){message('Choose a JPEG, PNG, or WebP image up to 10 MB.');return}const objectUrl=URL.createObjectURL(file),item={id:crypto.randomUUID(),file,objectUrl,url:objectUrl};card(item);message('Preparing image upload…');sync();upload(item,false);}
function dropped(event){return [...(event.dataTransfer?.files||[])].filter(file=>allowed.includes(file.type));}
function resolve(){const candidates=urls().filter(url=>url!==suppressed);if(!candidates.length){link.hidden=true;return}clearTimeout(previewTimer);previewTimer=setTimeout(async()=>{for(const url of candidates){try{const response=await fetch(form.dataset.previewEndpoint+'?tnet_preview_url='+encodeURIComponent(url),{credentials:'same-origin'});const data=await response.json();if(data.status==='preview'){link.textContent=data.metadata?.title||new URL(url).hostname;link.hidden=false;return}}catch(error){}}link.hidden=true},350)}
add.addEventListener('click',()=>file.click());file.addEventListener('change',event=>{[...event.target.files].forEach(stage);file.value=''});['dragenter','dragover'].forEach(type=>zone.addEventListener(type,event=>{if(dropped(event).length){event.preventDefault();zone.classList.add('active')}}));['dragleave','drop'].forEach(type=>zone.addEventListener(type,event=>{zone.classList.remove('active');if(type==='drop'&&dropped(event).length){event.preventDefault();dropped(event).forEach(stage)}}));body.addEventListener('paste',event=>{[...(event.clipboardData?.items||[])].filter(item=>item.kind==='file'&&allowed.includes(item.type)).forEach(item=>{event.preventDefault();stage(item.getAsFile())})});body.addEventListener('input',resolve);form.addEventListener('submit',event=>{sync();if(post.disabled){event.preventDefault();message('Wait for every retained image to be ready, or remove the unresolved image.')}});sync();resolve();})();</script>
HTML;
    }
}
