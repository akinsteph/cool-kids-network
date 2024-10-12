<?php

/**
 * Define paths for Cool Kids Network.
 *
 * @package Cool Kids Network
 */

// Define theme version.
define('COOL_KIDS_NETWORK_VERSION', '1.0.0');

// Define theme directory path.
define('COOL_KIDS_NETWORK_DIR', get_template_directory());

// Define theme directory URI.
define('COOL_KIDS_NETWORK_URI', get_template_directory_uri());

// Define asset directory path.
define('COOL_KIDS_NETWORK_ASSET_DIR', COOL_KIDS_NETWORK_DIR . '/assets');

// Define asset URL.
define('COOL_KIDS_NETWORK_ASSET_URL', COOL_KIDS_NETWORK_URI . '/assets');

// Define templates path for includes.
define('COOL_KIDS_NETWORK_TEMPLATES_PATH', COOL_KIDS_NETWORK_DIR . '/templates');

// Define languages path.
define('COOL_KIDS_NETWORK_LANGUAGES_PATH', COOL_KIDS_NETWORK_DIR . '/languages');

// Define API base route and base URL.
define('COOL_KIDS_NETWORK_API_BASE_ROUTE', 'cool-kids-network/v2');
define('COOL_KIDS_NETWORK_API_BASE', get_home_url() . '/wp-json/' . COOL_KIDS_NETWORK_API_BASE_ROUTE);
