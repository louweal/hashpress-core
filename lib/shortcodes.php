<?php

/**
 * Template:       		shortcodes.php
 * Description:    		Adds shortcodes to the page
 */

//Register Paired Account shortcode
add_shortcode('hashpress_account', 'hashpress_account_function');
function hashpress_account_function()
{
    return '<span class="hashpress-account"></span>';
}

// Register the hashpress connect wallet shortcode
add_shortcode('hashpress_connect', 'hashpress_connect_function');

function hashpress_connect_function($atts)
{
    $network = isset($atts['network']) ? esc_html($atts['network']) : 'testnet';
    $connect_text = isset($atts['connect_text']) ? esc_html($atts['connect_text']) : 'Connect wallet';
    $disconnect_text = isset($atts['disconnect_text']) ? esc_html($atts['disconnect_text']) : 'Disconnect wallet';

    $badge = $network != "mainnet" ? '<span class="badge">' . $network . '</span>' : '';

    // Construct the button HTML
    return '<div data-network="' . $network . '"
                data-connect-text="' . $connect_text . '"
                data-disconnect-text="' . $disconnect_text . '"
                class="btn hashpress-btn connect">
                <span>' . $connect_text . '</span>'
        . $badge . '
        </div>';
}
