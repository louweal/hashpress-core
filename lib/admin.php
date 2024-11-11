<?php

/**
 * Template:			admin.php
 * Description:			Custom admin settings
 */

add_action('admin_menu', function () {
    // add_menu_page('HashPress', 'HashPress', 'manage_options', 'hashpress', 'hashpress_settings', 'dashicons-money-alt');
    add_submenu_page(
        'options-general.php', // Parent slug for the "Settings" menu
        'HashPress',           // Page title
        'HashPress',           // Menu title
        'manage_options',      // Capability required to access the page
        'hashpress',           // Menu slug
        'hashpress_settings'   // Callback function that displays the page content
    );
});

function hashpress_settings()
{
?>
    <h1>Hashpress</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('hashpress_settings_group');
        do_settings_sections('hashpress-settings');
        submit_button();
        ?>
    </form>
<?php
}

add_action('admin_init', 'hashpress_settings_init');
function hashpress_settings_init()
{
    register_setting('hashpress_settings_group', 'hashpress_settings');

    add_settings_section(
        'hashpress_settings_section',
        '',
        'hashpress_settings_section_callback',
        'hashpress-settings'
    );

    add_settings_field(
        'project-id',
        'WalletConnect Project ID',
        'hashpress_project_id_field_callback',
        'hashpress-settings',
        'hashpress_settings_section'
    );

    add_settings_field(
        'name',
        'Name',
        'hashpress_name_field_callback',
        'hashpress-settings',
        'hashpress_settings_section'
    );
    add_settings_field(
        'description',
        'Description',
        'hashpress_description_field_callback',
        'hashpress-settings',
        'hashpress_settings_section'
    );
    add_settings_field(
        'icon',
        'Icon URL',
        'hashpress_icon_field_callback',
        'hashpress-settings',
        'hashpress_settings_section'
    );
    add_settings_field(
        'URL',
        'URL',
        'hashpress_url_field_callback',
        'hashpress-settings',
        'hashpress_settings_section'
    );
}

function hashpress_settings_section_callback()
{
    // echo "This metadata is shown in the transaction modal.";
}

function hashpress_project_id_field_callback()
{
    $settings = get_option('hashpress_settings');
    $project_id = isset($settings['project_id']) ? esc_html($settings['project_id']) : '';
?>
    <input type="text" name="hashpress_settings[project_id]" value="<?php echo $project_id; ?>">
<?php
}

function hashpress_name_field_callback()
{
    $settings = get_option('hashpress_settings');
    $name = isset($settings['name']) ? esc_html($settings['name']) : get_bloginfo('name');
?>
    <input type="text" name="hashpress_settings[name]" value="<?php echo $name; ?>">
<?php
}

function hashpress_description_field_callback()
{
    $settings = get_option('hashpress_settings');
    $description = isset($settings['description']) ? esc_html($settings['description']) : get_bloginfo('description');
?>
    <input type="text" name="hashpress_settings[description]" value="<?php echo $description; ?>">
<?php
}

function hashpress_icon_field_callback()
{
    $settings = get_option('hashpress_settings');
    $icon = isset($settings['icon']) ? esc_html($settings['icon']) : get_site_icon_url();
?>
    <input type="text" name="hashpress_settings[icon]" value="<?php echo $icon; ?>">
<?php
}

function hashpress_url_field_callback()
{
    $settings = get_option('hashpress_settings');
    $url = isset($settings['url']) ? esc_html($settings['url']) : home_url();
?>
    <input type="text" name="hashpress_settings[url]" value="<?php echo $url; ?>">
    <?php
}

function hashpress_admin_inline_styles()
{
    $screen = get_current_screen();
    if ($screen->id === 'settings_page_hashpress') {
    ?>
        <style>
            input[type="number"],
            input[type="text"],
            input[type="email"],
            input[type="url"],
            input[type="password"],
            textarea {
                width: min(70vw, 700px);
            }
        </style>
<?php
    }
}
add_action('admin_head', 'hashpress_admin_inline_styles');
