<?php
/**
 * Community's canonical-page consumer adapter for the governed Shared Shell.
 *
 * This adapter resolves Community's page facts and supplies only the opaque
 * product callback the platform contract permits. It never reimplements shell
 * markup, navigation, account, or notification behavior.
 */
defined('ABSPATH') || exit;

final class TNet_Community_Shared_Shell {
    public static function render(string $title, callable $content): void {
        if (!class_exists('TNet_Shared_Shell')) {
            wp_die('The governed Teachers.Net Shared Shell is unavailable in this local runtime.');
        }

        $user = wp_get_current_user();
        $logged_in = $user instanceof WP_User && $user->exists();
        $community_url = home_url('/community/');
        $request_path = (string) wp_unslash($_SERVER['REQUEST_URI'] ?? '/community/');
        $current_url = home_url($request_path);
        $logo = plugins_url('public/assets/images/teachers-net-logo.png', WP_PLUGIN_DIR . '/tnet-jobs/tnet-jobs.php');
        $visual_css = dirname(__DIR__) . '/assets/community-visual-language-v1.css';

        TNet_Shared_Shell::enqueue_assets('canonical');
        wp_enqueue_style(
            'tnet-community-visual-language',
            plugins_url('assets/community-visual-language-v1.css', dirname(__DIR__) . '/tnet-community.php'),
            ['tnet-shared-shell-responsive-correction'],
            is_file($visual_css) ? (string) filemtime($visual_css) : null
        );

        TNet_Shared_Shell::render_host([
            'contract' => 'canonical',
            'document_title' => $title . ' | Teachers.Net',
            'home_url' => $community_url,
            'brand_image' => $logo,
            'logged_in' => $logged_in,
            'fixture_state' => $logged_in ? 'auth-zero' : 'guest',
            'employer_access' => false,
            'active_destination' => 'chatboards',
            'adapter' => 'community3',
            'workspace_owner' => 'consumer',
            'route_class' => 'community3',
            'clean' => true,
            'presentation' => 'flush',
            'identity' => $logged_in ? [
                'name' => $user->display_name ?: $user->user_login,
                'email' => $user->user_email,
                'descriptor' => 'Teachers.Net member',
                'avatar_url' => (string) get_avatar_url((int) $user->ID, ['size' => 96]),
                'avatar_source' => 'wordpress-avatar',
            ] : ['name' => 'Guest user', 'avatar_source' => 'shell-fallback'],
            'urls' => [
                'post_job' => home_url('/jobs/'),
                'my_jobs' => home_url('/jobs/'),
                'schools' => home_url('/jobs/'),
                'archived' => home_url('/jobs/'),
                'browse_jobs' => home_url('/jobs/'),
                'saved_jobs' => home_url('/jobs/'),
                'job_alerts' => home_url('/jobs/'),
                'new_topic' => $logged_in ? home_url('/community/ai-in-education/new/') : wp_login_url(home_url('/community/ai-in-education/new/')),
                'profile' => $logged_in ? admin_url('profile.php') : wp_login_url($current_url),
                'logout' => $logged_in ? wp_logout_url($community_url) : '',
                'login' => wp_login_url($current_url),
                'signup' => wp_registration_url(),
                'dashboard' => $community_url,
                'wizard' => $community_url,
                'chatboards' => $community_url,
            ],
            'taxonomy' => [
                'lesson_grade_levels' => [],
                'lesson_subject_areas' => [],
                'chatboard_grade_levels' => [],
            ],
            'fixture' => 'community3',
            'footer_links' => [
                ['About', home_url('/about/')],
                ['Contacts', home_url('/contacts/')],
                ['Terms', home_url('/terms/')],
                ['Privacy', home_url('/privacy/')],
            ],
            'content' => $content,
        ]);
        exit;
    }
}
