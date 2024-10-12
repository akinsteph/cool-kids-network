<?php
/*
Template Name: Dashboard
*/

if (!is_user_logged_in()) {
	wp_redirect(home_url('/login'));
	exit;
}

get_header();

?>

<main class="dashboard">
	<div class="container">
		<h1><?php echo esc_html__('Dashboard', 'cool-kids-network'); ?></h1>
		<div class="other-characters">
			<h2><?php echo esc_html__('Other Characters', 'cool-kids-network'); ?></h2>
			<?php echo do_shortcode('[cool_kids_other_characters]'); ?>
		</div>
	</div>
</main>

<?php
get_footer();
?>