<?php

/**
 * Login functionality for Cool Kids Network.
 *
 * @package Cool Kids Network
 */

namespace CoolKidsNetwork\Features;

use CoolKidsNetwork\Traits\FormRenderer;
use CoolKidsNetwork\Traits\Singleton;

/**
 * Class Login.
 *
 * Handles login functionality for the Cool Kids Network.
 */
class Login
{
	use Singleton, FormRenderer;

	protected function __construct()
	{
		add_action('wp_ajax_nopriv_cool_kids_login', [$this, 'login_user']);
		add_shortcode('cool_kids_login_form', [$this, 'login_form_shortcode']);
	}

	/**
	 * Renders the login form shortcode.
	 *
	 * @return string The login form HTML.
	 */
	public function login_form_shortcode()
	{
		$email_field = [
			'type' => 'email',
			'name' => 'email',
			'id' => 'login-email',
			'placeholder' => 'Email',
			'required' => true,
		];

		return $this->render_form('login', ['email' => $email_field], 'Login');
	}

	/**
	 * Handles the login user AJAX request.
	 *
	 * @return void
	 */
	public function login_user()
	{
		$encrypted_data = $_POST['data'];
		$decrypted_data = $this->decrypt_data($encrypted_data);

		if (!isset($decrypted_data['nonce']) || !wp_verify_nonce($decrypted_data['nonce'], 'cool-kids-login')) {
			wp_send_json_error('Invalid nonce');
		}

		if (!isset($decrypted_data['email'])) {
			wp_send_json_error('Email is required');
		}

		$email = $this->validate_email($decrypted_data['email']);

		$user = get_user_by('email', $email);

		if (!$user) {
			wp_send_json_error('User not found');
		}

		wp_set_current_user($user->ID);
		wp_set_auth_cookie($user->ID);

		wp_send_json_success('Logged in successfully');
	}
}
