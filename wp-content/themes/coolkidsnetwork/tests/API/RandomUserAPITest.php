<?php

namespace CoolKidsNetwork\Tests\API;

use CoolKidsNetwork\API\RandomUserAPI;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use Mockery;

class RandomUserAPITest extends TestCase {
	protected $random_user_api;

	protected function setUp(): void {
		parent::setUp();
		\Brain\Monkey\setUp();

		// Mock WordPress functions
		Functions\when('wp_remote_get')->justReturn(['body' => json_encode([
			'results' => [
				[
					'name' => ['first' => 'John', 'last' => 'Doe'],
					'email' => 'john.doe@example.com',
					'location' => [
						'country' => 'USA',
						'street' => ['name' => 'Main St', 'number' => '123'],
						'city' => 'Anytown',
						'state' => 'CA',
						'postcode' => '12345'
					],
					'picture' => ['medium' => 'http://example.com/avatar.jpg']
				]
			]
		])]);
		Functions\when('is_wp_error')->justReturn(false);
		Functions\when('wp_remote_retrieve_body')->returnArg();
		Functions\when('sanitize_text_field')->returnArg();
		Functions\when('sanitize_email')->returnArg();

		$this->random_user_api = RandomUserAPI::get_instance();
	}

	protected function tearDown(): void {
		Mockery::close();
		\Brain\Monkey\tearDown();
		parent::tearDown();
	}

	public function testGetRandomUserSuccess() {
		$mock_response = [
			'results' => [
				[
					'name' => ['first' => 'John', 'last' => 'Doe'],
					'email' => 'john.doe@example.com',
					'location' => [
						'country' => 'USA',
						'street' => ['name' => 'Main St', 'number' => '123'],
						'city' => 'Anytown',
						'state' => 'CA',
						'postcode' => '12345'
					],
					'picture' => ['medium' => 'http://example.com/avatar.jpg']
				]
			]
		];

		Functions\expect('wp_remote_get')
			->once()
			->andReturn(['body' => json_encode($mock_response)]);

		Functions\expect('wp_remote_retrieve_body')
			->once()
			->andReturn(json_encode($mock_response));

		Functions\expect('download_url')
			->once()
			->andReturn('/tmp/avatar.jpg');

		Functions\expect('media_handle_sideload')
			->once()
			->andReturn(1);

		$result = $this->random_user_api->get_random_user();

		$this->assertIsArray($result);
		$this->assertEquals('John', $result['first_name']);
		$this->assertEquals('Doe', $result['last_name']);
		$this->assertEquals('john.doe@example.com', $result['email']);
		$this->assertEquals('USA', $result['country']);
		$this->assertEquals('Main St 123', $result['address']['street']);
		$this->assertEquals('Anytown', $result['address']['city']);
		$this->assertEquals('CA', $result['address']['state']);
		$this->assertEquals('12345', $result['address']['postcode']);
		$this->assertEquals(1, $result['avatar_id']);
	}

	public function testGetRandomUserFailure() {
		Functions\expect('wp_remote_get')
			->once()
			->andReturn(new \WP_Error('http_request_failed', 'Request failed'));

		Functions\expect('is_wp_error')
			->once()
			->andReturn(true);

		$result = $this->random_user_api->get_random_user();

		$this->assertFalse($result);
	}

	public function testGetRandomUserEmptyResponse() {
		Functions\expect('wp_remote_get')
			->once()
			->andReturn(['body' => '{"results":[]}']);

		Functions\expect('wp_remote_retrieve_body')
			->once()
			->andReturn('{"results":[]}');

		$result = $this->random_user_api->get_random_user();

		$this->assertFalse($result);
	}

	public function testUploadAvatarFailure() {
		$mock_response = [
			'results' => [
				[
					'name' => ['first' => 'John', 'last' => 'Doe'],
					'email' => 'john.doe@example.com',
					'location' => [
						'country' => 'USA',
						'street' => ['name' => 'Main St', 'number' => '123'],
						'city' => 'Anytown',
						'state' => 'CA',
						'postcode' => '12345'
					],
					'picture' => ['medium' => 'http://example.com/avatar.jpg']
				]
			]
		];

		Functions\expect('wp_remote_get')
			->once()
			->andReturn(['body' => json_encode($mock_response)]);

		Functions\expect('wp_remote_retrieve_body')
			->once()
			->andReturn(json_encode($mock_response));

		Functions\expect('download_url')
			->once()
			->andReturn(new \WP_Error('download_failed', 'Download failed'));

		$result = $this->random_user_api->get_random_user();

		$this->assertIsArray($result);
		$this->assertFalse($result['avatar_id']);
	}
}
