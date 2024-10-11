<?php
/*
Template Name: Signup Page
*/

get_header();
?>

<main class="authentication-wrapper">
	<div class="container">
		<div class="authentication-form">
			<h1><?php _e('Sign Up', 'coolkidsnetwork'); ?></h1>
			<?php echo do_shortcode('[cool_kids_registration_form]'); ?>
		</div>
	</div>
	<div class="sparkles"></div>
</main>

<?php
get_footer();
