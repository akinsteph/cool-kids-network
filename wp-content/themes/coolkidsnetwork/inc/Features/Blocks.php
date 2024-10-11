<?php

namespace CoolKidsNetwork\Features;

use CoolKidsNetwork\Traits\Singleton;

/**
 * Class Blocks
 *
 * Manages the registration and rendering of custom blocks for the Cool Kids Network theme.
 * This class handles both static and dynamic blocks, integrating with the block.json structure.
 */
class Blocks {
	use Singleton;

	/**
	 * @var array $blocks List of custom blocks to be registered.
	 */
	private $blocks = [
		'hero',
		'character-list',
		'user-dashboard',
	];

	/**
	 * Blocks constructor.
	 * Sets up action hooks for registering blocks and block categories.
	 */
	protected function __construct() {
		add_filter('block_categories_all', array($this, 'register_block_category'), 1, 1);
		add_action('init', array($this, 'register_blocks'));
	}

	/**
	 * Registers all custom blocks.
	 */
	public function register_blocks() {
		foreach ($this->blocks as $block) {
			$this->register_block($block);
		}
	}

	/**
	 * Registers an individual block.
	 *
	 * @param string $block_name The name of the block to register.
	 */
	private function register_block($block_name) {
		$block_json_file = COOL_KIDS_NETWORK_DIR . "/build/{$block_name}/block.json";

		if (!file_exists($block_json_file)) {
			error_log("Block JSON file not found for {$block_name}");
			return;
		}

		// Add render callback for the hero block
		if (in_array($block_name, ['hero', 'character-list'])) {
			register_block_type_from_metadata(COOL_KIDS_NETWORK_DIR . "/build/{$block_name}/", [
				'render_callback' => [$this, 'render_' . str_replace('-', '_', $block_name) . '_block'],
			]);
		}

		register_block_type($block_json_file);
	}

	/**
	 * Registers a custom block category for Cool Kids Network blocks.
	 *
	 * @param array $categories Array of block categories.
	 * @param WP_Post $post The post being edited.
	 * @return array Modified array of block categories.
	 */
	public function register_block_category($categories) {

		$cool_kids_network_block = array(
			'slug'  => 'cool-kids-network',
			'title' => __('Cool Kids Network Blocks', 'cool-kids-network'),
			'icon'  => 'superhero',
		);

		$categories_sorted = array();
		$categories_sorted[0] = $cool_kids_network_block;

		foreach ($categories as $category) {
			$categories_sorted[] = $category;
		}

		return $categories_sorted;
	}


	/**
	 * Render callback for the hero block.
	 *
	 * @param array $attributes The block attributes.
	 * @param string $content The block content.
	 * @return string The rendered block.
	 */
	public function render_hero_block($attributes, $content) {
		$title = $attributes['title'] ?? '';
		$description = $attributes['description'] ?? '';
		$additional_content = $attributes['content'] ?? '';
		$alignment = $attributes['alignment'] ?? 'center';
		$background_color = $attributes['backgroundColor'] ?? '#000000';
		$background_image = $attributes['backgroundImage'] ?? '';
		$logged_out_buttons = $attributes['loggedOutButtons'] ?? [];
		$logged_in_buttons = $attributes['loggedInButtons'] ?? [];

		$style = "background-color: " . esc_attr($background_color) . ";";
		if ($background_image) {
			$style .= " background-image: url('" . esc_url($background_image) . "'); background-size: cover; background-position: center;";
		}

		$logo = get_custom_logo();
		ob_start();

?>
		<section class="wp-block-cool-kids-network-hero hero-block alignment-<?php echo esc_attr($alignment); ?> alignfull" style="<?php echo $style; ?>">
			<div class="hero-overlay"></div>
			<div class="hero-content">
				<?php if ($logo) : ?>
					<div class="hero-logo">
						<?php echo $logo; ?>
					</div>
				<?php endif; ?>
				<?php if ($title) : ?>
					<h2><?php echo wp_kses_post($title); ?></h2>
				<?php endif; ?>

				<?php if ($description) : ?>
					<p><?php echo wp_kses_post($description); ?></p>
				<?php endif; ?>

				<?php if ($additional_content) : ?>
					<div><?php echo wp_kses_post($additional_content); ?></div>
				<?php endif; ?>

				<div class="hero-buttons">
					<?php if (!is_user_logged_in()) : ?>
						<?php foreach ($logged_out_buttons as $index => $button) : ?>
							<a href="<?php echo esc_url($button['link']); ?>" class="button wp-block-button__link<?php echo ($index === 1) ? ' secondary' : ''; ?>">
								<?php echo esc_html($button['text']); ?>
							</a>
						<?php endforeach; ?>
					<?php else : ?>
						<?php foreach ($logged_in_buttons as $index => $button) : ?>
							<a href="<?php echo esc_url($button['link']); ?>" class="button wp-block-button__link<?php echo ($index === 1) ? ' secondary' : ''; ?>">
								<?php echo esc_html($button['text']); ?>
							</a>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</section>
<?php
		return ob_get_clean();
	}

	/**
	 * Renders the other characters block.
	 *
	 * @param array $attributes The block attributes.
	 * @param string $content The block content.
	 * @return string The rendered block.
	 */
	public function render_character_list_block($attributes, $content) {
		if (!is_user_logged_in()) {
			return '<p>' . __('Please log in to view other characters.', 'cool-kids-network') . '</p>';
		}

		$current_user = wp_get_current_user();
		$current_user_role = $current_user->roles[0];

		// $allowed_roles = ['adminsitrator', 'cool_kid', 'cooler_kid', 'coolest_kid'];
		// if (!in_array($current_user_role, $allowed_roles)) {
		// 	return '<p>' . __('You do not have permission to view other characters.', 'cool-kids-network') . '</p>';
		// }

		$character_management = CharacterManagement::get_instance();
		$characters = $character_management->fetch_characters($current_user_role);

		if (empty($characters)) {
			return '<p>' . __('No other characters found.', 'cool-kids-network') . '</p>';
		}

		$output = '<div class="other-characters">';
		$output .= '<h2>' . __('Other Characters', 'cool-kids-network') . '</h2>';
		$output .= '<ul>';

		foreach ($characters as $character) {
			$output .= '<li>';
			$output .= '<h3>' . esc_html($character['name']) . '</h3>';
			$output .= '<p><strong>' . __('Role:', 'cool-kids-network') . '</strong> ' . esc_html($character['role']) . '</p>';

			if (isset($character['country'])) {
				$output .= '<p><strong>' . __('Country:', 'cool-kids-network') . '</strong> ' . esc_html($character['country']) . '</p>';
			}

			if (isset($character['email'])) {
				$output .= '<p><strong>' . __('Email:', 'cool-kids-network') . '</strong> ' . esc_html($character['email']) . '</p>';
			}

			$output .= '</li>';
		}

		$output .= '</ul></div>';

		return $output;
	}
}
