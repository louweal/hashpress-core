<?php

/**
 * Template:       		enqueue.php
 * Description:    		Add CSS and Javascript to the page
 */

add_action('wp_enqueue_scripts', 'enqueue_hashpress_script', 11);
function enqueue_hashpress_script()
{
    // Enqueue the script
    $path = plugin_dir_url(dirname(__FILE__, 1));

    wp_enqueue_script('hashpress-main-script', $path . 'dist/main.bundle.js', array(), null, array(
        'strategy'  => 'defer', 'in_footer' => false
    ));

    // Localize the REST API URL and nonce for authentication
    wp_localize_script('hashpress-main-script', 'hashpressCoreData', array(
        'nonce' => wp_create_nonce('wp_rest'),  // Generate a nonce for authentication
        'getProjectSettings' => esc_url_raw(rest_url('hashpress/v1/get-project-settings'))  // REST API endpoint URL
    ));

    wp_enqueue_script('hashpress-vendor-script', $path .  'dist/vendors.bundle.js', array(), null, array(
        'strategy'  => 'defer', 'in_footer' => false
    ));
}

add_action('wp_enqueue_scripts', 'hashpress_enqueue_styles', 5);
function hashpress_enqueue_styles()
{
    $path = plugin_dir_url(dirname(__FILE__, 1));

    wp_enqueue_style(
        'hashpress-styles', // Handle
        $path . 'src/css/hashpress.css',
        array(), // Dependencies
        null, // Version number
        'all' // Media type
    );
}
