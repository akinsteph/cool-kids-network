<?php

/**
 * Character Management functionality for Cool Kids Network.
 *
 * @package Cool Kids Network
 */

namespace CoolKidsNetwork\Features;

use CoolKidsNetwork\Features\RoleManager;
use CoolKidsNetwork\Traits\Singleton;

/**
 * Class CharacterManagement.
 *
 * Handles character management functionality for the Cool Kids Network.
 */
class CharacterManagement {
	use Singleton;

	/**
	 * Constructor for the CharacterManagement class.
	 *
	 * Hooks the shortcode for displaying character data.
	 */
	protected function __construct() {
		add_shortcode('cool_kids_character_data', [$this, 'display_character_data']);
		add_shortcode('cool_kids_other_characters', [$this, 'display_other_characters']);
	}

	/**
	 * Displays the character data shortcode.
	 *
	 * @return string The character data HTML.
	 */
	public function display_character_data() {
		if (!is_user_logged_in()) {
			return 'Please log in to view your character data.';
		}

		$user_id = get_current_user_id();
		$user_data = $this->get_user_data($user_id);

		ob_start();
?>
		<div class="cool-kids-character-data">
			<?php if (!empty($user_data['avatar_url'])): ?>
				<div class="character-avatar">
					<img src="<?php echo esc_url($user_data['avatar_url']); ?>" alt="<?php echo esc_attr($user_data['name']); ?>" />
				</div>
			<?php endif; ?>
			<div class="character-info">
				<?php foreach ($user_data as $key => $value): ?>
					<?php if ($key === 'address'): ?>
						<div><strong><?php echo esc_html__('Address', 'cool-kids-network'); ?>:</strong></div>
						<ul>
							<?php foreach ($value as $address_key => $address_value): ?>
								<li><?php echo esc_html(ucfirst($address_key)); ?>: <?php echo esc_html($address_value); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php elseif ($key !== 'avatar_url'): ?>
						<p><strong><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?>:</strong> <span><?php echo esc_html($value); ?></span></p>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	<?php
		return ob_get_clean();
	}

	/**
	 * Retrieves the user data based on the user ID.
	 *
	 * @param int $user_id The user ID.
	 * @return array The user data.
	 */
	private function get_user_data($user_id) {
		$user = get_userdata($user_id);
		$avatar_id = get_user_meta($user_id, 'avatar_id', true);

		return [
			'name' => $user->first_name . ' ' . $user->last_name,
			'country' => get_user_meta($user_id, 'country', true),
			'email' => $user->user_email,
			'role' => ucwords(str_replace('_', ' ', $user->roles[0])),
			'address' => [
				'street' => get_user_meta($user_id, 'address_street', true),
				'city' => get_user_meta($user_id, 'address_city', true),
				'state' => get_user_meta($user_id, 'address_state', true),
				'postcode' => get_user_meta($user_id, 'address_postcode', true),
			],
			'avatar_url' => $avatar_id ? wp_get_attachment_url($avatar_id) : '',
		];
	}

	/**
	 * Fetch characters based on user roles and permissions.
	 *
	 * @param string $current_user_role The role of the current user
	 * @return array An array of character data
	 */
	private function fetch_characters($current_user_role) {
		$role_manager = RoleManager::get_instance();
		$allowed_roles = array_keys($role_manager->get_custom_roles());

		$users = get_users([
			'role__in' => $allowed_roles,
			'exclude' => [get_current_user_id()],
		]);

		$characters = [];
		foreach ($users as $user) {
			$character = [
				'name' => $user->display_name,
				'avatar_url' => get_user_meta($user->ID, 'avatar_id', true) ? wp_get_attachment_url(get_user_meta($user->ID, 'avatar_id', true)) : '',
			];

			if ($current_user_role === 'cooler_kid' || $current_user_role === 'coolest_kid') {
				$character['country'] = get_user_meta($user->ID, 'country', true);
			}

			if ($current_user_role === 'coolest_kid') {
				$character['role'] = ucwords(str_replace('_', ' ', $user->roles[0]));
				$character['email'] = $user->user_email;
			}

			$characters[] = $character;
		}

		return $characters;
	}

	public function display_other_characters() {
		if (!is_user_logged_in()) {
			return 'Please log in to view other characters.';
		}

		$current_user = wp_get_current_user();
		$current_user_role = $current_user->roles[0];

		$allowed_roles = ['cooler_kid', 'coolest_kid'];

		if (!in_array($current_user_role, $allowed_roles)) {
			return '<p>' . __('You do not have permission to view other characters. Upgrade your plan to see other characters.', 'cool-kids-network') . '</p>';
		}

		$characters = $this->fetch_characters($current_user_role);

		if (empty($characters)) {
			return '<p>' . __('No other characters found.', 'cool-kids-network') . '</p>';
		}

		ob_start();
	?>
		<div class="other-characters">
			<ul>
				<?php foreach ($characters as $character): ?>
					<li>
						<?php if (!empty($character['avatar_url'])): ?>
							<img src="<?php echo esc_url($character['avatar_url']); ?>" alt="<?php echo esc_attr($character['name']); ?>" class="character-avatar" />
						<?php endif; ?>
						<div class="character-info">
							<h3><?php echo esc_html($character['name']); ?></h3>
							<?php if (isset($character['country'])): ?>
								<p><strong><?php echo esc_html__('Country:', 'cool-kids-network'); ?></strong> <?php echo esc_html($character['country']); ?></p>
							<?php endif; ?>
							<?php if (isset($character['role'])): ?>
								<p><strong><?php echo esc_html__('Role:', 'cool-kids-network'); ?></strong> <?php echo esc_html($character['role']); ?></p>
							<?php endif; ?>
							<?php if (isset($character['email'])): ?>
								<p><strong><?php echo esc_html__('Email:', 'cool-kids-network'); ?></strong> <?php echo esc_html($character['email']); ?></p>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
<?php
		return ob_get_clean();
	}
}
