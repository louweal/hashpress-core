<?php

function register_project_settings_endpoint()
{
    // Register a custom endpoint
    register_rest_route('hashpress/v1', '/get-project-settings', array(
        'methods' => 'GET',
        'callback' => 'get_project_settings',
        'permission_callback' => 'project_settings_permission_check'
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

// Permission check to ensure only authorized users can access this endpoint
function project_settings_permission_check()
{
    // Allow only users with the 'manage_options' capability (usually admins)
    return current_user_can('manage_options');
}

// Hook the function into the REST API initialization
add_action('rest_api_init', 'register_project_settings_endpoint');
