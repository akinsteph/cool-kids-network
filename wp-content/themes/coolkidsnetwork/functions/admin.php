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
	wp_enqueue_style('admin-styles', COOL_KIDS_NETWORK_ASSET_URL . '/css/admin.css', [], COOL_KIDS_NETWORK_VERSION);
}

add_action('admin_enqueue_scripts', 'enqueue_admin_styles');

/**
 * Remove the admin bar for non-admin users.
 */
add_filter('show_admin_bar', '__return_false');
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}
