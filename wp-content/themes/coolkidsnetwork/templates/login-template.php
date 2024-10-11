<?php
/*
Template Name: Login Page
*/

get_header();
?>

<main class="authentication-wrapper">
	<div class="container">
		<div class="authentication-form">
			<h1><?php _e('Login', 'coolkidsnetwork'); ?></h1>
			<?php echo do_shortcode('[cool_kids_login_form]'); ?>
		</div>
	</div>
	<div class="sparkles"></div>
</main>
<?php
get_footer();
