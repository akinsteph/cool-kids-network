<?php

/**
 * RoleChangeAPI.php
 *
 * This file contains the RoleChangeAPI class, which handles the REST API endpoints for changing user roles.
 *
 * @package CoolKidsNetwork
 */

namespace CoolKidsNetwork\API;

use CoolKidsNetwork\Traits\Singleton;

/**
 * RoleChangeAPI.php
 *
 * This file contains the RoleChangeAPI class, which handles the REST API endpoints for changing user roles.
 *
 * @package CoolKidsNetwork
 */
class RoleChangeAPI {
	use Singleton;

	/**
	 * Constructor for the RoleChangeAPI class.
	 *
	 * Hooks the REST API endpoints for changing user roles.
	 */
	protected function __construct() {
		add_action('rest_api_init', array($this, 'register_endpoints'));
	}

	/**
	 * Registers the REST API endpoints for changing user roles.
	 */
	public function register_endpoints() {
		register_rest_route('cool-kids-network/v1', '/change-role', array(
			'methods' => 'POST',
			'callback' => array($this, 'change_user_role'),
			'permission_callback' => array($this, 'check_admin_permissions'),
			'args' => array(
				'user_identifier' => array(
					'required' => true,
					'type' => 'string',
				),
				'new_role' => array(
					'required' => true,
					'type' => 'string',
					'enum' => array('cool_kid', 'cooler_kid', 'coolest_kid'),
				),
			),
		));
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

		// Check if user identifier is an email
		if (is_email($user_identifier)) {
			$user = get_user_by('email', $user_identifier);
		} else {
			// Assume it's a name
			$name_parts = explode(' ', $user_identifier);
			$first_name = $name_parts[0];
			$last_name = isset($name_parts[1]) ? $name_parts[1] : '';
			$user = get_user_by('login', $first_name . ' ' . $last_name);
		}

		if (!$user) {
			return new \WP_Error('user_not_found', 'User not found', array('status' => 404));
		}

		$user->set_role($new_role);

		return new \WP_REST_Response(array('message' => 'User role updated successfully'), 200);
	}

	/**
	 * Checks if the current user has admin permissions.
	 *
	 * @return bool True if the user has admin permissions, false otherwise.
	 */
	public function check_admin_permissions() {
		return current_user_can('manage_options');
	}
}
