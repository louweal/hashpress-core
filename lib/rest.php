<?php

function register_project_settings_endpoint()
{
    // Register a custom endpoint
    register_rest_route('hashpress/v1', '/get-project-settings', array(
        'methods' => 'GET',
        'callback' => 'get_project_settings',
        'permission_callback' => 'hashpress_core_validate_nonce'
    ));
}

function get_project_settings()
{
    $settings = get_option('hashpress_settings');

    if ($settings) {
        $project_id = sanitize_text_field($settings['project_id']);
        $name = sanitize_text_field($settings['name']);
        $description = sanitize_text_field($settings['description']);
        $icon = sanitize_text_field($settings['icon']);
        $url = sanitize_text_field($settings['url']);
        return new WP_REST_Response(array('project_id' => $project_id, 'name' => $name, 'description' => $description, 'icon' => $icon, 'url' => $url), 200);
    } else {
        return new WP_REST_Response(array('error' => 'HashPress settings not found'), 404);
    }
}

function hashpress_core_validate_nonce(WP_REST_Request $request)
{
    $nonce = $request->get_header('X-WP-Nonce');
    if (wp_verify_nonce($nonce, 'wp_rest')) {
        return true;
    }
    return new WP_Error('rest_forbidden', __('Invalid nonce.'), ['status' => 403]);
}

// Hook the function into the REST API initialization
add_action('rest_api_init', 'register_project_settings_endpoint');
