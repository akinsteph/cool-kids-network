<?php
/*
Template Name: Avatar Profile
*/

use CoolKidsNetwork\Features\CharacterManagement;

if (!is_user_logged_in()) {
	wp_redirect(home_url('/login'));
	exit;
}

get_header();

$character_management = CoolKidsNetwork\Features\CharacterManagement::get_instance();
$user_data = $character_management->get_user_data(get_current_user_id());
?>

<main class="avatar-profile">
	<div class="container">
		<h1><?php echo esc_html__('Your Character', 'cool-kids-network'); ?></h1>
		<div class="character-details">
			<?php foreach ($user_data as $key => $value) : ?>
				<p><strong><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?>:</strong> <?php echo esc_html($value); ?></p>
			<?php endforeach; ?>
		</div>
	</div>
</main>

<?php
get_footer();
?>