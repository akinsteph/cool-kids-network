<?php

/**
 * The header for our theme.
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
	define('PERMALINK', is_front_page() ? get_bloginfo('url') : (is_single() ? get_the_permalink() : home_url(add_query_arg([], $wp->request))));
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
	<header class="header-wrapper">
		<div class="container">
			<?php
			if (has_custom_logo()):
				echo get_custom_logo();
			endif;
			?>
			<?php if (is_user_logged_in()) : ?>
				<button class="hamburger-menu" aria-label="Toggle menu">
					<span></span>
					<span></span>
					<span></span>
				</button>
				<nav class="user-nav">
					<?php if (!is_page('my-character') && !is_home() && !is_front_page()): ?>
						<a href="<?php echo esc_url(home_url('/my-character')); ?>" class="nav-link button"><?php echo esc_html__('My Character', 'cool-kids-network'); ?></a>
					<?php endif; ?>
					<?php if (!is_page('other-characters') && !is_home() && !is_front_page()): ?>
						<a href="<?php echo esc_url(home_url('/other-characters')); ?>" class="nav-link button secondary"><?php echo esc_html__('Other Characters', 'cool-kids-network'); ?></a>
					<?php endif; ?>
					<a href="<?php echo esc_url(wp_logout_url(home_url('/login'))); ?>" class="nav-link logout-icon" title="<?php echo esc_attr__('Logout', 'cool-kids-network'); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
							<polyline points="16 17 21 12 16 7"></polyline>
							<line x1="21" y1="12" x2="9" y2="12"></line>
						</svg>
						<?php echo esc_html__('Logout', 'cool-kids-network'); ?>
					</a>
				</nav>
			<?php endif; ?>
		</div>
	</header>