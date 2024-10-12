<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load Brain Monkey
require_once __DIR__ . '/../vendor/antecedent/patchwork/Patchwork.php';
require_once __DIR__ . '/../vendor/brain/monkey/inc/patchwork-loader.php';

// Load PHPUnit Polyfills
require_once __DIR__ . '/../vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';

// Define WordPress constants
define('ABSPATH', __DIR__ . '/../../..');
define('WP_DEBUG', true);

// Load WordPress functions
global $wp_filter;
$wp_filter = [];

// Mock WordPress functions
Brain\Monkey\setUp();
Brain\Monkey\Functions\stubs([
	'add_action',
	'add_filter',
	'do_action',
	'apply_filters',
	'__',
	'_e',
	'esc_html',
	'esc_attr',
	'wp_parse_args',
	'register_deactivation_hook',
	'add_role',
	'remove_role',
]);

// Clean up after each test
// Instead of using afterEach, we'll use PHPUnit's tearDown method in our test classes
