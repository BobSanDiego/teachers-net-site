<?php
/**
 * Plugin Name: Teachers.Net Community (prototype)
 * Description: Local-only Community publisher persistence prototype.
 * Version: 0.1.0-prototype
 */
defined('ABSPATH') || exit;
require_once __DIR__ . '/includes/class-tnet-community-schema.php';
require_once __DIR__ . '/includes/class-tnet-community-subject-reference.php';
require_once __DIR__ . '/includes/class-tnet-community-link-preview.php';
require_once __DIR__ . '/includes/class-tnet-community-link-attachment-service.php';
require_once __DIR__ . '/includes/class-tnet-community-attachment.php';
require_once __DIR__ . '/includes/class-tnet-community-mocked-link-fetch.php';
require_once __DIR__ . '/includes/class-tnet-community-publisher-repository.php';
require_once __DIR__ . '/includes/class-tnet-community-publisher-domain.php';
require_once __DIR__ . '/includes/class-tnet-community-publisher-application.php';
require_once __DIR__ . '/includes/class-tnet-community-workbench-service.php';
require_once __DIR__ . '/admin/class-tnet-community-workbench.php';
require_once __DIR__ . '/includes/class-tnet-community-thread-view.php';
require_once __DIR__ . '/includes/class-tnet-community-thread-controller.php';
require_once __DIR__ . '/includes/class-tnet-community-landing-view.php';
require_once __DIR__ . '/includes/class-tnet-community-landing-controller.php';
require_once __DIR__ . '/includes/class-tnet-community-topic-composer-controller.php';
require_once __DIR__ . '/includes/class-tnet-community-authoring.php';
require_once __DIR__ . '/includes/class-tnet-community-composer-contracts.php';
require_once __DIR__ . '/includes/class-tnet-community-composer-view.php';
require_once __DIR__ . '/includes/class-tnet-community-runtime-authority.php';
require_once __DIR__ . '/includes/class-tnet-community-legacy-feed-contract.php';
require_once __DIR__ . '/includes/class-tnet-community-legacy-feed-adapter.php';
add_action('admin_menu', static function (): void { TNet_Community_Workbench::register(); });
add_action('init', static function (): void { TNet_Community_Thread_Controller::register(); });
add_action('init', static function (): void { TNet_Community_Landing_Controller::register(); });
add_action('init', static function (): void { TNet_Community_Topic_Composer_Controller::register(); });
add_action('template_redirect', static function (): void {
    if ((!defined('DDEV_PROJECT') && !getenv('DDEV_PROJECT')) || (!get_query_var('tnet_community_landing') && !get_query_var('tnet_community_thread') && !get_query_var('tnet_community_topic_composer'))) return;
    TNet_Community_Runtime_Authority::header();
    ob_start(static function (string $html): string {
        $facts = TNet_Community_Runtime_Authority::facts();
        $badge = TNet_Community_Runtime_Authority::badge($facts);
        $href = esc_url(plugins_url('assets/community-visual-language-v1.css', __FILE__));
        $html = preg_replace('/<body([^>]*)>/', '<body$1 class="c3-visual-language">'.$badge, $html, 1) ?? $html;
        $html = str_replace('</head>', '<link rel="stylesheet" href="'.$href.'">\n</head>', $html);
        if (strpos($html, 'feed-card') !== false) $html = str_replace('</body>', '<script>(function(){document.querySelectorAll(".feed-card").forEach(function(card){card.addEventListener("click",function(e){if(e.target.closest("a,button,input,select,textarea,img,video,audio,.card-preview,.feed-excerpt"))return;var link=card.querySelector("h2 a");if(link)location.href=link.href})})})();</script></body>', $html);
        if (strpos($html, 'reply-level-') !== false) $html = str_replace('</body>', '<script>(function(){var levels=[].slice.call(document.querySelectorAll("article.reply-level-1"));levels.forEach(function(root){var group=document.createElement("div"),items=[],n=root.nextElementSibling;while(n&&n.matches("article.reply-level-2")){items.push(n);n=n.nextElementSibling}if(!items.length)return;group.className="reply-group";var button=document.createElement("button");button.type="button";button.className="reply-group-toggle";button.textContent="View "+items.length+" replies";button.setAttribute("aria-expanded","false");root.after(button);items.forEach(function(item){group.appendChild(item)});button.after(group);group.hidden=true;button.addEventListener("click",function(){group.hidden=!group.hidden;button.setAttribute("aria-expanded",String(!group.hidden));button.textContent=(group.hidden?"View ":"Hide ")+items.length+" replies";if(!group.hidden){var composer=document.getElementById("reply-composer-shell");if(composer)group.appendChild(composer)}})});})();</script></body>', $html);
        $html = str_replace('\\n', '', $html);
        $html = str_replace(')} })();', ')});})();', $html);
        return $html;
    });
}, 0);
