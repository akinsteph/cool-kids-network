<?php

/**
 * Base functionality for Cool Kids Network.
 *
 * @package Cool Kids Network
 */

use CoolKidsNetwork\Core\Theme;

/**
 * Enqueues the assets for the theme.
 *
 * @return void
 */
function ckn_enqueue_assets() {
	wp_enqueue_style('coolkidsnetwork-styles', get_stylesheet_uri(), [], COOL_KIDS_NETWORK_VERSION, 'all');
	wp_enqueue_script('coolkidsnetwork-scripts', COOL_KIDS_NETWORK_URI . '/assets/js/coolkidsnetwork-scripts.js', ['jquery'], COOL_KIDS_NETWORK_VERSION, true);

	// Localize script for AJAX calls
	wp_localize_script('coolkidsnetwork-scripts', 'coolkidsnetworkData', array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('coolkidsnetwork-nonce')
	));
}
add_action('wp_enqueue_scripts', 'ckn_enqueue_assets');

/**
 * Sets up the theme by loading the text domain.
 *
 * @return void
 */
function setup_theme() {

	// Add theme support for title tag, post thumbnails, HTML5, custom logo, custom header, custom background, custom footer, custom menu, custom post type, custom category
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
	add_theme_support('custom-logo');
	add_theme_support('custom-header');
	add_theme_support('custom-background');
	add_theme_support('custom-footer');
	add_theme_support('custom-menu');
	add_theme_support('custom-post-type');


	load_theme_textdomain('coolkidsnetwork', get_template_directory() . '/languages');

	// Initialize core theme functionality
	\CoolKidsNetwork\Core\Theme::get_instance();
}
add_action('after_setup_theme', 'setup_theme');
