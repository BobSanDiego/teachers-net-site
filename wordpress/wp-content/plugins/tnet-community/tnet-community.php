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
add_action('admin_menu', static function (): void { TNet_Community_Workbench::register(); });
add_action('init', static function (): void { TNet_Community_Thread_Controller::register(); });
add_action('init', static function (): void { TNet_Community_Landing_Controller::register(); });
add_action('init', static function (): void { TNet_Community_Topic_Composer_Controller::register(); });
