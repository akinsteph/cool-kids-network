<?php

/**
 * RoleManagement functionality for Cool Kids Network.
 *
 * @package Cool Kids Network
 */

namespace CoolKidsNetwork\Features;

use CoolKidsNetwork\Traits\Singleton;

/**
 * Class RoleManager.
 *
 * Manages custom user roles for the Cool Kids Network theme.
 * This class is responsible for creating, removing, and validating custom roles.
 */
class RoleManager {
	use Singleton;

	/**
	 * @var array An array of custom roles and their display names.
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
		add_action('init', [$this, 'add_custom_roles']);

		register_deactivation_hook(__FILE__, [$this, 'remove_custom_roles']);
	}

	/**
	 * Adds custom roles to WordPress.
	 * This method is called on the 'init' action hook.
	 */
	public function add_custom_roles() {
		$role_permissions = [
			'cool_kid' => [
				'read' => true,
				'view_own_character' => true,
			],
			'cooler_kid' => [
				'read' => true,
				'view_own_character' => true,
				'view_others_limited' => true,
			],
			'coolest_kid' => [
				'read' => true,
				'view_own_character' => true,
				'view_others_full' => true,
			],
		];

		foreach ($this->custom_roles as $role => $display_name) {
			add_role($role, $display_name, $role_permissions[$role]);
		}
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
}
