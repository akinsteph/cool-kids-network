<?php

/**
 * Character Management functionality for Cool Kids Network.
 *
 * @package Cool Kids Network
 */

namespace CoolKidsNetwork\Features;

use CoolKidsNetwork\Traits\Singleton;
use CoolKidsNetwork\Features\RoleManager;

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
  private function get_user_data($user_id) {
    $user = get_userdata($user_id);

    return [
      'name' => $user->first_name . ' ' . $user->last_name,
      'country' => get_user_meta($user_id, 'country', true),
      'email' => $user->user_email,
      'role' => get_user_meta($user_id, 'cool_kids_role', true),
    ];
  }

  /**
   * Fetch characters based on user roles and permissions
   *
   * @param string $current_user_role The role of the current user
   * @return array An array of character data
   */
  public function fetch_characters($current_user_role) {
    $role_manager = RoleManager::get_instance();
    $allowed_roles = array_keys($role_manager->get_custom_roles());

    // if (!in_array($current_user_role, $allowed_roles)) {
    //   return [];
    // }

    $users = get_users([
      'role__in' => $allowed_roles,
      'exclude' => [get_current_user_id()],
    ]);

    $characters = [];
    foreach ($users as $user) {
      $character = [
        'name' => $user->display_name,
        'role' => ucwords(str_replace('_', ' ', $user->roles[0])),
      ];

      if ($current_user_role === 'cooler_kid' || $current_user_role === 'coolest_kid') {
        $character['country'] = get_user_meta($user->ID, 'country', true);
      }

      if ($current_user_role === 'coolest_kid') {
        $character['email'] = $user->user_email;
      }

      $characters[] = $character;
    }

    return $characters;
  }
}
