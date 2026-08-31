<?php

/**
 * Plugin Name: Teachers.Net Shared Shell
 * Description: Canonical platform shell primitives and consumer configuration contract.
 * Version: 0.1.0
 * Author: Teachers.Net
 * Text Domain: tnet-shared-shell
 */

if (!defined('ABSPATH')) {
  exit;
}

define('TNET_SHARED_SHELL_VERSION', '0.1.0');
define('TNET_SHARED_SHELL_PLUGIN_FILE', __FILE__);
define('TNET_SHARED_SHELL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TNET_SHARED_SHELL_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once TNET_SHARED_SHELL_PLUGIN_DIR . 'includes/class-tnet-shared-shell.php';

add_action('plugins_loaded', ['TNet_Shared_Shell', 'init']);
