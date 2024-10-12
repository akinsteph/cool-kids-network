<?php
/*
Template Name: Avatar Profile
*/

if (!is_user_logged_in()) {
	wp_redirect(home_url('/login'));
	exit;
}

get_header();

?>

<main class="avatar-profile">
	<div class="container">
		<h1><?php echo esc_html__('Your Character', 'cool-kids-network'); ?></h1>
		<?php echo do_shortcode('[cool_kids_character_data]'); ?>
	</div>
</main>

<?php
get_footer();
?>