<?php

/**
 * Character Management functionality for Cool Kids Network.
 *
 * @package Cool Kids Network
 */

namespace CoolKidsNetwork\Features;

use CoolKidsNetwork\Traits\Singleton;

/**
 * Class CharacterManagement.
 *
 * Handles character management functionality for the Cool Kids Network.
 */
class CharacterManagement
{
	use Singleton;

	/**
	 * Constructor for the CharacterManagement class.
	 *
	 * Hooks the shortcode for displaying character data.
	 */
	protected function __construct()
	{
		add_shortcode('cool_kids_character_data', [$this, 'display_character_data']);
	}

	/**
	 * Displays the character data shortcode.
	 *
	 * @return string The character data HTML.
	 */
	public function display_character_data()
	{
		if (!is_user_logged_in()) {
			return 'Please log in to view your character data.';
		}

		$user_id = get_current_user_id();
		$user_data = $this->get_user_data($user_id);

		ob_start();
		?>
    <div class="cool-kids-character-data">
      <h2><?php echo esc_html('Your Character', 'cool-kids-network'); ?></h2>
      <?php foreach ($user_data as $key => $value): ?>
        <p><strong><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?>:</strong> <?php echo esc_html($value); ?></p>
      <?php endforeach; ?>
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
	private function get_user_data($user_id)
	{
		$user = get_userdata($user_id);

		return [
		  'name' => $user->first_name . ' ' . $user->last_name,
		  'country' => get_user_meta($user_id, 'country', true),
		  'email' => $user->user_email,
		  'role' => get_user_meta($user_id, 'cool_kids_role', true),
		];
	}
}
