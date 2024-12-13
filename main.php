<?php
/*
Plugin Name: HashPress Core
Description: General settings for HashPress, required when using other HashPress plugins
Version: 0.1
Author: HashPress
Author URI: https://hashpress.io/
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (function_exists('opcache_reset')) {
    opcache_reset();
}

require_once plugin_dir_path(__FILE__) . 'lib/admin.php';
require_once plugin_dir_path(__FILE__) . 'lib/rest.php';
require_once plugin_dir_path(__FILE__) . 'lib/enqueue.php';
require_once plugin_dir_path(__FILE__) . 'lib/helpers.php';
require_once plugin_dir_path(__FILE__) . 'lib/shortcodes.php';
