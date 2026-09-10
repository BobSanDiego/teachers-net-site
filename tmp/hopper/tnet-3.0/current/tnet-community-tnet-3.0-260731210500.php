<?php
/**
 * Plugin Name: Teachers.Net Community (prototype)
 * Description: Local-only Community publisher persistence prototype.
 * Version: 0.1.0-prototype
 */
defined('ABSPATH') || exit;
require_once __DIR__ . '/includes/class-tnet-community-schema.php';
require_once __DIR__ . '/includes/class-tnet-community-publisher-repository.php';
require_once __DIR__ . '/includes/class-tnet-community-workbench-service.php';
require_once __DIR__ . '/admin/class-tnet-community-workbench.php';
add_action('admin_menu', static function (): void { TNet_Community_Workbench::register(); });
