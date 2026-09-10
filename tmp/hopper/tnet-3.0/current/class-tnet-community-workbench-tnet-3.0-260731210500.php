<?php
defined('ABSPATH') || exit;

final class TNet_Community_Workbench {
    public static function register(): void {
        if (!self::is_local()) return;
        add_management_page('Community Publisher Workbench','Community Publisher Workbench','manage_options','tnet-community-workbench',[self::class,'render']);
    }
    private static function is_local(): bool {
        // This project reports WordPress' generic environment as production even
        // inside DDEV; the container marker is the positive local boundary.
        return defined('DDEV_PROJECT') || (bool)getenv('DDEV_PROJECT');
    }
    private static function ready(): bool { global $wpdb; $table=TNet_Community_Schema::table_names()['posts']; return (bool)$wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s',$table)); }
    public static function render(): void {
        if (!self::is_local() || !current_user_can('manage_options')) wp_die(esc_html__('Unauthorized local workbench access.','tnet-community'),'', ['response'=>403]);
        $notice=''; $result=null;
        if ('POST' === ($_SERVER['REQUEST_METHOD'] ?? '') && check_admin_referer('tnet_community_workbench','tnet_workbench_nonce')) {
            $action=sanitize_key($_POST['tnet_action'] ?? '');
            if ('install' === $action) { TNet_Community_Schema::install(); $notice='Prototype tables installed locally.'; }
            elseif ('remove' === $action && !empty($_POST['confirm_remove'])) { TNet_Community_Schema::uninstall(); $notice='Prototype tables removed locally.'; }
            elseif ('publish' === $action && self::ready()) { $result=(new TNet_Community_Workbench_Service())->publish(wp_unslash($_POST)); $notice=$result['accepted']?'Topic persisted locally.':('Publish rejected: '.($result['reason_code']??'unknown')); }
        }
        $ready=self::ready(); $default_key='workbench-'.wp_generate_uuid4();
        echo '<div class="wrap"><h1>Community Publisher Workbench</h1><div class="notice notice-warning"><p><strong>LOCAL DEVELOPMENT ONLY.</strong> Synthetic data is written only to the three prototype tables. No production, legacy CGI, notification, or public route is connected.</p></div>';
        echo '<h2>Schema status</h2><p><strong>'.esc_html($ready?'Schema ready':'Schema missing').'</strong></p><form method="post">'.wp_nonce_field('tnet_community_workbench','tnet_workbench_nonce',true,false).'<input type="hidden" name="tnet_action" value="install"><button class="button" type="submit">Install prototype tables</button></form><form method="post" style="margin-top:8px">'.wp_nonce_field('tnet_community_workbench','tnet_workbench_nonce',true,false).'<input type="hidden" name="tnet_action" value="remove"><label><input type="checkbox" name="confirm_remove" value="1"> Confirm removal of only community_posts, community_post_audit, community_publication_events</label> <button class="button" type="submit">Remove prototype tables</button></form>';
        if ($notice) echo '<div class="notice notice-info"><p>'.esc_html($notice).'</p></div>';
        echo '<h2>Publish synthetic topic</h2><form method="post">'.wp_nonce_field('tnet_community_workbench','tnet_workbench_nonce',true,false).'<input type="hidden" name="tnet_action" value="publish"><table class="form-table"><tr><th><label for="community_id">Community ID</label></th><td><input class="regular-text" id="community_id" name="community_id" value="community:local-demo" required></td></tr><tr><th><label for="author_id">Author ID</label></th><td><input class="regular-text" id="author_id" name="author_id" value="user:local-admin" required></td></tr><tr><th><label for="submission_key">Submission key</label></th><td><input class="regular-text" id="submission_key" name="submission_key" value="'.esc_attr($default_key).'" required></td></tr><tr><th><label for="title">Title</label></th><td><input class="regular-text" id="title" name="title" required></td></tr><tr><th><label for="body">Body</label></th><td><textarea class="large-text" rows="6" id="body" name="body" required></textarea></td></tr><tr><th><label for="visibility">Visibility</label></th><td><select id="visibility" name="visibility"><option>public</option><option>members</option><option>private</option></select></td></tr><tr><th><label for="publication_mode">Publication mode</label></th><td><select id="publication_mode" name="publication_mode"><option value="post_first">post-first</option><option value="pending">pending</option></select></td></tr></table><p><button class="button button-primary" type="submit"'.disabled(!$ready,true,false).'>Publish Topic</button></p></form>';
        if (is_array($result)) self::result($result);
        echo '</div>';
    }
    private static function result(array $result): void { echo '<h2>Persisted result</h2><div class="notice '.($result['accepted']?'notice-success':'notice-error').' "><p>'.esc_html($result['accepted']?'accepted':'rejected').' — '.esc_html($result['reason_code']??'').'</p></div>'; if (empty($result['post'])) return; $p=$result['post']; $repo=new TNet_Community_Publisher_Repository(); $audit=$repo->get_audit($p['post_id']); $events=$repo->get_pending_events(); echo '<table class="widefat striped"><tbody>'; foreach(['post_id','community_id','thread_id','parent_post_id','publication_state','moderation_state','author_id','created_at','published_at'] as $k) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)($p[$k]??'')).'</td></tr>'; echo '<tr><th>audit rows</th><td>'.esc_html((string)count($audit)).'</td></tr><tr><th>pending events</th><td>'.esc_html((string)count($events)).'</td></tr>'; if (!empty($result['event'])) echo '<tr><th>event</th><td>'.esc_html($result['event']['event_id'].' / '.$result['event']['event_type']).'</td></tr>'; echo '</tbody></table>'; }
}
