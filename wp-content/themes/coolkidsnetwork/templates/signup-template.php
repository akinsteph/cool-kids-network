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
			<p><?php _e("Already have an avatar?", 'coolkidsnetwork'); ?> <a href="<?php echo esc_url(home_url('/login')); ?>"><?php _e('Login', 'coolkidsnetwork'); ?></a></p>
		</div>
	</div>
	<div class="sparkles"></div>
</main>

<?php
get_footer();
