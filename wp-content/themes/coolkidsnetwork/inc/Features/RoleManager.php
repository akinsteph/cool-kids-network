<?php

/**
 * RoleManagement functionality for Cool Kids Network.
 *
 * @package Cool Kids Network
 */

namespace CoolKidsNetwork\Features;

use CoolKidsNetwork\Traits\Singleton;

/**
 * Class RoleManager
 *
 * Manages custom user roles for the Cool Kids Network theme.
 * This class is responsible for creating, removing, and validating custom roles.
 */
class RoleManager {
	use Singleton;

	/**
	 * @var array $custom_roles An array of custom roles and their display names.
	 */
	private $custom_roles = [
		'cool_kid' => 'Cool Kid',
		'cooler_kid' => 'Cooler Kid',
		'coolest_kid' => 'Coolest Kid',
	];

	/**
	 * RoleManager constructor.
	 * Sets up action hooks for adding custom roles and deactivation hook for removing them.
	 */
	protected function __construct() {
		add_action('init', array($this, 'add_custom_roles'));
		register_deactivation_hook(__FILE__, array($this, 'remove_custom_roles'));
	}

	/**
	 * Adds custom roles to WordPress.
	 * This method is called on the 'init' action hook.
	 */
	public function add_custom_roles() {
		add_role('cool_kid', 'Cool Kid', [
			'read' => true,
			'view_own_character' => true
		]);

		add_role('cooler_kid', 'Cooler Kid', [
			'read' => true,
			'view_own_character' => true,
			'view_others_limited' => true
		]);

		add_role('coolest_kid', 'Coolest Kid', [
			'read' => true,
			'view_own_character' => true,
			'view_others_full' => true
		]);
	}

	/**
	 * Removes custom roles from WordPress.
	 * This method is called when the theme is deactivated.
	 */
	public function remove_custom_roles() {
		foreach ($this->custom_roles as $role => $name) {
			remove_role($role);
		}
	}

	/**
	 * Gets the list of custom roles.
	 *
	 * @return array An array of custom roles and their display names.
	 */
	public function get_custom_roles() {
		return $this->custom_roles;
	}

	/**
	 * Checks if a given role is a valid custom role.
	 *
	 * @param string $role The role to check.
	 * @return bool True if the role is valid, false otherwise.
	 */
	public function is_valid_role($role) {
		return array_key_exists($role, $this->custom_roles);
	}

	public function is_maintainer($user_id = null) {
		if ($user_id === null) {
			$user_id = get_current_user_id();
		}
		$user = get_userdata($user_id);
		return $user && in_array('maintainer', $user->roles);
	}
}
