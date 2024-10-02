<?php

use CoolKidsNetwork\Core\Theme;

/**
 * Enqueues the assets for the theme.
 *
 * @return void
 */
function ckn_enqueue_assets() {
  wp_enqueue_style('ckn-styles', get_stylesheet_uri(), [], COOL_KIDS_NETWORK_VERSION, 'all');
  wp_enqueue_script('ckn-scripts', get_template_directory_uri() . '/assets/js/ckn-scripts.js', array('jquery'), COOL_KIDS_NETWORK_VERSION, true);
}

/**
 * Sets up the theme by loading the text domain.
 *
 * @return void
 */
function setup_theme() {
  load_theme_textdomain('ckn-wp', get_template_directory() . '/languages');
}

Theme::get_instance();
