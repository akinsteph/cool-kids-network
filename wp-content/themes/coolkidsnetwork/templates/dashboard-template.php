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

<main class="dashboard-wrapper">
	<div class="container">
		<h1><?php echo esc_html__('Other Characters', 'cool-kids-network'); ?></h1>

		<?php echo do_shortcode('[cool_kids_other_characters]'); ?>
	</div>
	<div class="sparkles"></div>
</main>

<?php
get_footer();
?>