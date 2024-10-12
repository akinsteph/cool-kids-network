<?php

namespace CoolKidsNetwork\Tests\Features;

use CoolKidsNetwork\Features\Login;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use Mockery;

class LoginTest extends TestCase {
	protected $login;

	protected function setUp(): void {
		parent::setUp();
		\Brain\Monkey\setUp();

		// Mock WordPress functions
		Functions\when('add_action')->justReturn(true);
		Functions\when('add_shortcode')->justReturn(true);
		Functions\when('wp_verify_nonce')->justReturn(true);
		Functions\when('wp_send_json_error')->justReturn(null);
		Functions\when('wp_send_json_success')->justReturn(null);
		Functions\when('get_user_by')->justReturn(null);
		Functions\when('wp_set_current_user')->justReturn(null);
		Functions\when('wp_set_auth_cookie')->justReturn(null);

		$this->login = Login::get_instance();
	}

	protected function tearDown(): void {
		Mockery::close();
		\Brain\Monkey\tearDown();
		parent::tearDown();
	}

	public function testLoginFormShortcode() {
		$form = $this->login->login_form_shortcode();

		$this->assertStringContainsString('id="cool-kids-login-form"', $form);
		$this->assertStringContainsString('name="email"', $form);
		$this->assertStringContainsString('type="email"', $form);
		$this->assertStringContainsString('Login', $form);
	}

	public function testLoginUserWithInvalidNonce() {
		$_POST['data'] = $this->login->encrypt_data(['nonce' => 'invalid_nonce', 'email' => 'test@example.com']);

		Functions\expect('wp_verify_nonce')
			->once()
			->andReturn(false);

		Functions\expect('wp_send_json_error')
			->once()
			->with('Invalid nonce');

		$this->login->login_user();
	}

	public function testLoginUserWithMissingEmail() {
		$_POST['data'] = $this->login->encrypt_data(['nonce' => 'valid_nonce']);

		Functions\expect('wp_send_json_error')
			->once()
			->with('Email is required');

		$this->login->login_user();
	}

	public function testLoginUserWithInvalidEmail() {
		$_POST['data'] = $this->login->encrypt_data(['nonce' => 'valid_nonce', 'email' => 'invalid_email']);

		Functions\expect('wp_send_json_error')
			->once()
			->with('Invalid email address');

		$this->login->login_user();
	}

	public function testLoginUserWithNonExistentUser() {
		$_POST['data'] = $this->login->encrypt_data(['nonce' => 'valid_nonce', 'email' => 'test@example.com']);

		Functions\expect('get_user_by')
			->once()
			->with('email', 'test@example.com')
			->andReturn(false);

		Functions\expect('wp_send_json_error')
			->once()
			->with('User not found');

		$this->login->login_user();
	}

	public function testLoginUserSuccess() {
		$_POST['data'] = $this->login->encrypt_data(['nonce' => 'valid_nonce', 'email' => 'test@example.com']);

		$user = Mockery::mock('WP_User');
		$user->ID = 1;

		Functions\expect('get_user_by')
			->once()
			->with('email', 'test@example.com')
			->andReturn($user);

		Functions\expect('wp_set_current_user')
			->once()
			->with(1);

		Functions\expect('wp_set_auth_cookie')
			->once()
			->with(1);

		Functions\expect('wp_send_json_success')
			->once()
			->with('Logged in successfully');

		$this->login->login_user();
	}
}
