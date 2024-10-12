<?php

namespace CoolKidsNetwork\Tests\Features;

use CoolKidsNetwork\Features\Registration;
use CoolKidsNetwork\API\RandomUserAPI;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use Mockery;

class RegistrationTest extends TestCase {
	protected $registration;
	protected $random_user_api_mock;

	protected function setUp(): void {
		parent::setUp();
		\Brain\Monkey\setUp();

		// Mock WordPress functions
		Functions\when('add_action')->justReturn(true);
		Functions\when('add_shortcode')->justReturn(true);
		Functions\when('wp_verify_nonce')->justReturn(true);
		Functions\when('wp_send_json_error')->justReturn(null);
		Functions\when('wp_send_json_success')->justReturn(null);
		Functions\when('email_exists')->justReturn(false);
		Functions\when('wp_insert_user')->justReturn(1);
		Functions\when('is_wp_error')->justReturn(false);
		Functions\when('update_user_meta')->justReturn(true);

		$this->random_user_api_mock = Mockery::mock('CoolKidsNetwork\API\RandomUserAPI');
		$this->random_user_api_mock->shouldReceive('get_instance')->andReturn($this->random_user_api_mock);

		$this->registration = Registration::get_instance();
		$this->registration->random_user_api = $this->random_user_api_mock;
	}

	protected function tearDown(): void {
		Mockery::close();
		\Brain\Monkey\tearDown();
		parent::tearDown();
	}

	public function testRegistrationFormShortcode() {
		$form = $this->registration->registration_form_shortcode();

		$this->assertStringContainsString('id="cool-kids-registration-form"', $form);
		$this->assertStringContainsString('name="email"', $form);
		$this->assertStringContainsString('type="email"', $form);
		$this->assertStringContainsString('Register', $form);
	}

	public function testRegisterUserWithInvalidNonce() {
		$_POST['data'] = $this->registration->encrypt_data(['nonce' => 'invalid_nonce', 'email' => 'test@example.com']);

		Functions\expect('wp_verify_nonce')
			->once()
			->andReturn(false);

		Functions\expect('wp_send_json_error')
			->once()
			->with('Invalid nonce');

		$this->registration->register_user();
	}

	public function testRegisterUserWithMissingEmail() {
		$_POST['data'] = $this->registration->encrypt_data(['nonce' => 'valid_nonce']);

		Functions\expect('wp_send_json_error')
			->once()
			->with('Email is required');

		$this->registration->register_user();
	}

	public function testRegisterUserWithExistingEmail() {
		$_POST['data'] = $this->registration->encrypt_data(['nonce' => 'valid_nonce', 'email' => 'existing@example.com']);

		Functions\expect('email_exists')
			->once()
			->with('existing@example.com')
			->andReturn(true);

		Functions\expect('wp_send_json_error')
			->once()
			->with('Email already registered');

		$this->registration->register_user();
	}

	public function testRegisterUserSuccess() {
		$_POST['data'] = $this->registration->encrypt_data(['nonce' => 'valid_nonce', 'email' => 'new@example.com']);

		$random_user_data = [
			'first_name' => 'John',
			'last_name' => 'Doe',
			'country' => 'USA',
			'address' => [
				'street' => '123 Main St',
				'city' => 'Anytown',
				'state' => 'CA',
				'postcode' => '12345',
			],
			'avatar_id' => 123,
		];

		$this->random_user_api_mock->shouldReceive('get_random_user')
			->once()
			->andReturn($random_user_data);

		Functions\expect('wp_insert_user')
			->once()
			->andReturn(1);

		Functions\expect('update_user_meta')
			->times(8)
			->andReturn(true);

		Functions\expect('wp_send_json_success')
			->once()
			->with('User registered successfully');

		$this->registration->register_user();
	}

	public function testRegisterUserFailure() {
		$_POST['data'] = $this->registration->encrypt_data(['nonce' => 'valid_nonce', 'email' => 'new@example.com']);

		$this->random_user_api_mock->shouldReceive('get_random_user')
			->once()
			->andReturn([
				'first_name' => 'John',
				'last_name' => 'Doe',
			]);

		Functions\expect('wp_insert_user')
			->once()
			->andReturn(new \WP_Error('registration_failed', 'Failed to create user'));

		Functions\expect('is_wp_error')
			->once()
			->andReturn(true);

		Functions\expect('wp_send_json_error')
			->once()
			->with('Failed to create user');

		$this->registration->register_user();
	}
}
