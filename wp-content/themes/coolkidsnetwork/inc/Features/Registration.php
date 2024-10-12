<?php

/**
 * Registration functionality for Cool Kids Network.
 *
 * @package Cool Kids Network
 */

namespace CoolKidsNetwork\Features;

use CoolKidsNetwork\API\RandomUserAPI;
use CoolKidsNetwork\Traits\FormRenderer;
use CoolKidsNetwork\Traits\Singleton;

/**
 * Class Registration.
 *
 * Handles registration functionality for the Cool Kids Network.
 */
class Registration
{
	use Singleton;
	use FormRenderer;

	private $random_user_api;

	/**
	 * Constructor for the Registration class.
	 *
	 * Initializes the RandomUserAPI instance and hooks the AJAX request and shortcode.
	 */
	protected function __construct()
	{
		$this->random_user_api = RandomUserAPI::get_instance();
		add_action('wp_ajax_nopriv_cool_kids_register', [$this, 'register_user']);
		add_shortcode('cool_kids_registration_form', [$this, 'registration_form_shortcode']);
	}

	/**
	 * Renders the registration form shortcode.
	 *
	 * @return string The registration form HTML.
	 */
	public function registration_form_shortcode()
	{
		$email_field = [
		  'type' => 'email',
		  'name' => 'email',
		  'id' => 'signup-email',
		  'placeholder' => 'Email',
		  'required' => true,
		];

		return $this->render_form('registration', ['email' => $email_field], 'Register');
	}

	/**
	 * Handles the registration user AJAX request.
	 *
	 * @return void
	 */
	public function register_user()
	{
		$encrypted_data = $_POST['data'];
		$decrypted_data = $this->decrypt_data($encrypted_data);

		if (!isset($decrypted_data['nonce']) || !wp_verify_nonce($decrypted_data['nonce'], 'cool-kids-registration')) {
			wp_send_json_error('Invalid nonce');
		}

		if (!isset($decrypted_data['email'])) {
			wp_send_json_error('Email is required');
		}

		$email = $this->validate_email($decrypted_data['email']);

		if (email_exists($email)) {
			wp_send_json_error('Email already registered');
		}

		$random_user = $this->random_user_api->get_random_user();

		$user_id = wp_insert_user([
		  'user_login' => $email,
		  'user_email' => $email,
		  'first_name' => $random_user['first_name'],
		  'last_name' => $random_user['last_name'],
		  'role' => 'cool_kid',
		]);

		if (is_wp_error($user_id)) {
			wp_send_json_error('Failed to create user');
		}

		$this->update_user_meta($user_id, $random_user);

		wp_send_json_success('User registered successfully');
	}

	/**
	 * Updates the user meta with the random user data.
	 *
	 * @param int $user_id The user ID.
	 * @param array $user_data The random user data.
	 * @return void
	 */
	private function update_user_meta($user_id, $user_data)
	{
		update_user_meta($user_id, 'first_name', $user_data['first_name']);
		update_user_meta($user_id, 'last_name', $user_data['last_name']);
		update_user_meta($user_id, 'country', $user_data['country']);
		update_user_meta($user_id, 'address_street', $user_data['address']['street']);
		update_user_meta($user_id, 'address_city', $user_data['address']['city']);
		update_user_meta($user_id, 'address_state', $user_data['address']['state']);
		update_user_meta($user_id, 'address_postcode', $user_data['address']['postcode']);
		update_user_meta($user_id, 'avatar_id', $user_data['avatar_id']);
	}
}
