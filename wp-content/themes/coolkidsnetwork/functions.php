<?php

/**
 * Cool Kids Network functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @since Cool Kids Network 1.0
 * 
 * @package Cool Kids Network
 */

// Define theme version.
define('COOL_KIDS_NETWORK_VERSION', '1.0.0');

/**
 * Load Composer autoload.
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load theme functions.
 */
require_once __DIR__ . '/functions/define-paths.php';
require_once __DIR__ . '/functions/base.php';
require_once __DIR__ . '/functions/helpers.php';
require_once __DIR__ . '/functions/admin.php';
