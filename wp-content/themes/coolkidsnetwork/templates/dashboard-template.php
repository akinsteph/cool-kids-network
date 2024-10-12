<?php
/*
Template Name: Dashboard
*/

if (!is_user_logged_in()) {
	wp_redirect(home_url('/login'));
	exit;
}

get_header();

$character_management = CoolKidsNetwork\Features\CharacterManagement::get_instance();
$current_user = wp_get_current_user();
$current_user_role = $current_user->roles[0];
$characters = $character_management->fetch_characters($current_user_role);
?>

<main class="dashboard">
	<div class="container">
		<h1><?php echo esc_html__('Dashboard', 'cool-kids-network'); ?></h1>
		<div class="other-characters">
			<h2><?php echo esc_html__('Other Characters', 'cool-kids-network'); ?></h2>
			<?php if (!empty($characters)) : ?>
				<ul>
					<?php foreach ($characters as $character) : ?>
						<li>
							<h3><?php echo esc_html($character['name']); ?></h3>
							<p><strong><?php echo esc_html__('Role:', 'cool-kids-network'); ?></strong> <?php echo esc_html($character['role']); ?></p>
							<?php if (isset($character['country'])) : ?>
								<p><strong><?php echo esc_html__('Country:', 'cool-kids-network'); ?></strong> <?php echo esc_html($character['country']); ?></p>
							<?php endif; ?>
							<?php if (isset($character['email'])) : ?>
								<p><strong><?php echo esc_html__('Email:', 'cool-kids-network'); ?></strong> <?php echo esc_html($character['email']); ?></p>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<p><?php echo esc_html__('No other characters found.', 'cool-kids-network'); ?></p>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php
get_footer();
?>