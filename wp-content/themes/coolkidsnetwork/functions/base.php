<?php

use CoolKidsNetwork\Core\Theme;

function ckn_enqueue_assets()
{
  wp_enqueue_style('ckn-styles', get_stylesheet_uri(), [], COOL_KIDS_NETWORK_VERSION, 'all');
  wp_enqueue_script('ckn-scripts', get_template_directory_uri() . '/assets/js/ckn-scripts.js', ['jquery'], COOL_KIDS_NETWORK_VERSION, true);
}

function setup_theme()
{
  load_theme_textdomain('ckn-wp', get_template_directory() . '/languages');
}

Theme::get_instance();
