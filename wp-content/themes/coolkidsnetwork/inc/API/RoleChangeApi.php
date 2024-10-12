<?php

/**
 * RoleChangeAPI.php.
 *
 * This file contains the RoleChangeAPI class, which handles the REST API endpoints for changing user roles.
 *
 * @package CoolKidsNetwork
 */

namespace CoolKidsNetwork\API;

use CoolKidsNetwork\Traits\Singleton;
use CoolKidsNetwork\Features\RoleManager;

/**
 * RoleChangeAPI.php.
 *
 * This file contains the RoleChangeAPI class, which handles the REST API endpoints for changing user roles.
 *
 * @package CoolKidsNetwork
 */
class RoleChangeAPI {
	use Singleton;

	/**
	 * @var RoleManager $role_manager Instance of the RoleManager class.
	 */
	private $role_manager;

	/**
	 * Constructor for the RoleChangeAPI class.
	 *
	 * Hooks the REST API endpoints for changing user roles.
	 */
	protected function __construct() {
		$this->role_manager = RoleManager::get_instance();
		add_action('rest_api_init', array($this, 'register_endpoints'));
	}

	/**
	 * Registers the REST API endpoints for changing user roles.
	 */
	public function register_endpoints() {
		register_rest_route('cool-kids-network/v1', '/change-role', [
			'methods' => 'POST',
			'callback' => [$this, 'change_user_role'],
			'permission_callback' => [$this, 'check_api_permissions'],
			'args' => [
				'user_identifier' => [
					'required' => true,
					'type' => 'string',
				],
				'new_role' => [
					'required' => true,
					'type' => 'string',
					'enum' => array_keys($this->role_manager->get_custom_roles()),
				],
			],
		]);
	}

	/**
	 * Changes the user role based on the provided user identifier and new role.
	 *
	 * @param \WP_REST_Request $request The REST API request object.
	 * @return \WP_REST_Response|\WP_Error The REST API response or error.
	 */
	public function change_user_role($request) {
		$user_identifier = sanitize_text_field($request['user_identifier']);
		$new_role = sanitize_text_field($request['new_role']);

		if (!$this->role_manager->is_valid_role($new_role)) {
			return new \WP_REST_Response([
				'success' => false,
				'message' => 'Invalid role specified.',
				'error_code' => 'invalid_role'
			], 400);
		}

		$user = $this->get_user_by_identifier($user_identifier);

		if (!$user) {
			return new \WP_REST_Response([
				'success' => false,
				'message' => 'User not found.',
				'error_code' => 'user_not_found'
			], 404);
		}

		$old_role = reset($user->roles);
		$user->set_role($new_role);

		$user_data = [
			'id' => $user->ID,
			'username' => $user->user_login,
			'email' => $user->user_email,
			'display_name' => $user->display_name,
			'old_role' => $old_role,
			'new_role' => $new_role
		];

		return new \WP_REST_Response([
			'success' => true,
			'message' => 'User role updated successfully.',
			'user' => $user_data
		], 200);
	}


	/**
	 * Checks if the current user has permission to change roles.
	 *
	 * @return bool True if the user has permission, false otherwise.
	 */
	public function check_api_permissions($request) {
		$api_key = $request->get_header('X-API-Key');

		if (!$api_key) {
			return false;
		}

		return $api_key === '8f7d9e2a3b1c5f4e6g8h7i9j0k1l2m3n';
	}

	/**
	 * Retrieves a user by email or name.
	 *
	 * @param string $identifier The user's email or name.
	 * @return WP_User|false The user object if found, false otherwise.
	 */
	private function get_user_by_identifier($identifier) {
		if (is_email($identifier)) {
			return get_user_by('email', $identifier);
		} else {
			// Search by display name
			$users = get_users([
				'search' => $identifier,
				'search_columns' => ['display_name'],
				'number' => 1
			]);

			if (!empty($users)) {
				return $users[0];
			}

			// If not found, try searching by first and last name
			$name_parts = explode(' ', $identifier, 2);
			$first_name = $name_parts[0];
			$last_name = isset($name_parts[1]) ? $name_parts[1] : '';

			$users = get_users([
				'meta_query' => [
					'relation' => 'AND',
					[
						'key' => 'first_name',
						'value' => $first_name,
						'compare' => '='
					],
					[
						'key' => 'last_name',
						'value' => $last_name,
						'compare' => '='
					]
				],
				'number' => 1
			]);

			return !empty($users) ? $users[0] : false;
		}
	}
}
