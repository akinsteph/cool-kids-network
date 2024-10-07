<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * 
 * @package CoolKidsNetwork
 */

if (!defined('TITLE')) {
	define('TITLE', is_single() ? get_the_title() : get_bloginfo('title'));
}
if (!defined('DESCRIPTION')) {
	define('DESCRIPTION', is_single() ? get_the_excerpt() : get_bloginfo('description'));
}
if (!defined('PERMALINK')) {
	global $wp;
	define('PERMALINK', is_front_page() ? get_bloginfo('url') : (is_single() ? get_the_permalink() : home_url(add_query_arg(array(), $wp->request))));
}

?>

<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<title><?php echo esc_html(TITLE); ?></title>
	<link rel="canonical" href="<?php echo esc_attr(PERMALINK) ?>" />
	<meta name="description" content="<?php echo esc_attr(DESCRIPTION) ?>">
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<header>
		<div class="container">
			<div class="header-wrapper">
				<?php
				if (has_custom_logo()):
					echo get_custom_logo();
				endif;
				?>
			</div>
		</div>
	</header>