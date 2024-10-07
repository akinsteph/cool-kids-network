<?php

/*
 * Admin functionality for Cool Kids Network.
 *
 * @package Cool Kids Network
 */

/**
 * Enqueue admin styles.
 *
 * This function enqueues the admin.css file from the assets/css directory.
 * It ensures that the styles are loaded in the WordPress admin area.
 *
 * @return void
 */
function enqueue_admin_styles() {
	wp_enqueue_style('admin-styles', COOL_KIDS_NETWORK_ASSET_URL . '/css/admin.css', [], '1.0.0');
}

add_action('admin_enqueue_scripts', 'enqueue_admin_styles');
