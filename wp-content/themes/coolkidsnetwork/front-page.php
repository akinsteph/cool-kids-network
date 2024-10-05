<?php

/**
 * The template for displaying the front page.
 *
 * @package CoolKidsNetwork
 */
get_header();
?>

<main id="primary" class="site-main">
	<?php
	if (have_posts()) :
		while (have_posts()) :
			the_post();
			the_content();
		endwhile;
	endif;
?>
</main>

<?php
get_footer();