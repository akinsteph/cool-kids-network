<?php

namespace CoolKidsNetwork\Tests\Features;

use CoolKidsNetwork\Traits\FormRenderer;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use Mockery;

class FormRendererTest extends TestCase {
	use FormRenderer;

	protected function setUp(): void {
		parent::setUp();
		\Brain\Monkey\setUp();

		// Mock WordPress functions
		Functions\when('wp_nonce_field')->justReturn('cool-kids-test-nonce');
		Functions\when('esc_attr')->returnArg();
		Functions\when('esc_html')->returnArg();
		Functions\when('wp_verify_nonce')->justReturn(true);
		Functions\when('wp_send_json_error')->justReturn(null);
		Functions\when('sanitize_email')->returnArg();
		Functions\when('wp_unslash')->returnArg();
		Functions\when('is_email')->justReturn(true);
	}

	protected function tearDown(): void {
		Mockery::close();
		\Brain\Monkey\tearDown();
		parent::tearDown();
	}

	public function testRenderForm() {
		$action = 'test';
		$fields = [
			[
				'type' => 'text',
				'name' => 'username',
				'label' => 'Username',
				'required' => true,
				'placeholder' => 'Enter your username',
			],
			[
				'type' => 'password',
				'name' => 'password',
				'label' => 'Password',
				'required' => true,
				'placeholder' => 'Enter your password',
			],
		];
		$button_text = 'Submit';

		$form = $this->render_form($action, $fields, $button_text);

		$this->assertStringContainsString('cool-kids-test-nonce', $form);
		$this->assertStringContainsString('<form id="cool-kids-test-form"', $form);
		$this->assertStringContainsString('<input type="text" id="username"', $form);
		$this->assertStringContainsString('<input type="password" id="password"', $form);
		$this->assertStringContainsString('<button type="submit">Submit</button>', $form);
	}

	public function testRenderField() {
		$field = [
			'type' => 'email',
			'name' => 'email',
			'label' => 'Email',
			'required' => true,
			'placeholder' => 'Enter your email',
		];

		ob_start();
		$this->render_field($field);
		$output = ob_get_clean();

		$this->assertStringContainsString('type="email"', $output);
		$this->assertStringContainsString('name="email"', $output);
		$this->assertStringContainsString('Email', $output);
		$this->assertStringContainsString('required', $output);
		$this->assertStringContainsString('placeholder="Enter your email"', $output);
	}

	public function testVerifyNonce() {
		$_POST['cool-kids-test-nonce'] = 'valid_nonce';
		$this->verify_nonce('test');
		$this->addToAssertionCount(1); // If we reach this point, no exception was thrown
	}

	public function testValidateEmail() {
		$email = 'test@example.com';
		$result = $this->validate_email($email);
		$this->assertEquals($email, $result);
	}

	public function testEncryptAndDecryptData() {
		$data = ['key' => 'value', 'number' => 42];
		$encrypted = $this->encrypt_data($data);
		$decrypted = $this->decrypt_data($encrypted);
		$this->assertEquals($data, $decrypted);
	}
}
