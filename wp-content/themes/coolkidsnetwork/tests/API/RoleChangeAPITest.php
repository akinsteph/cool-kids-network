<?php

namespace CoolKidsNetwork\Tests\API;

use CoolKidsNetwork\API\RoleChangeAPI;
use CoolKidsNetwork\Features\RoleManager;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use Mockery;
use WP_REST_Request;
use WP_REST_Response;
use WP_User;

class RoleChangeAPITest extends TestCase {
	protected $role_change_api;
	protected $role_manager_mock;

	protected function setUp(): void {
		parent::setUp();
		\Brain\Monkey\setUp();

		// Mock WordPress functions
		Functions\when('add_action')->justReturn(true);
		Functions\when('register_rest_route')->justReturn(true);
		Functions\when('sanitize_text_field')->returnArg();
		Functions\when('is_email')->justReturn(false);
		Functions\when('get_user_by')->justReturn(false);
		Functions\when('get_users')->justReturn([]);

		$this->role_manager_mock = Mockery::mock('CoolKidsNetwork\Features\RoleManager');
		$this->role_manager_mock->shouldReceive('get_instance')->andReturn($this->role_manager_mock);
		$this->role_manager_mock->shouldReceive('get_custom_roles')->andReturn([
			'cool_kid' => 'Cool Kid',
			'cooler_kid' => 'Cooler Kid',
			'coolest_kid' => 'Coolest Kid',
		]);

		$this->role_change_api = RoleChangeAPI::get_instance();
		$this->role_change_api->role_manager = $this->role_manager_mock;
	}

	protected function tearDown(): void {
		Mockery::close();
		\Brain\Monkey\tearDown();
		parent::tearDown();
	}

	public function testRegisterEndpoints() {
		Functions\expect('register_rest_route')
			->once()
			->with('cool-kids-network/v1', '/change-role', Mockery::type('array'));

		$this->role_change_api->register_endpoints();
	}

	public function testChangeUserRoleInvalidRole() {
		$request = new WP_REST_Request('POST', '/cool-kids-network/v1/change-role');
		$request->set_param('user_identifier', 'john@example.com');
		$request->set_param('new_role', 'invalid_role');

		$this->role_manager_mock->shouldReceive('is_valid_role')
			->with('invalid_role')
			->andReturn(false);

		$response = $this->role_change_api->change_user_role($request);

		$this->assertInstanceOf(WP_REST_Response::class, $response);
		$this->assertEquals(400, $response->get_status());
		$this->assertEquals('Invalid role specified.', $response->get_data()['message']);
	}

	public function testChangeUserRoleUserNotFound() {
		$request = new WP_REST_Request('POST', '/cool-kids-network/v1/change-role');
		$request->set_param('user_identifier', 'john@example.com');
		$request->set_param('new_role', 'cool_kid');

		$this->role_manager_mock->shouldReceive('is_valid_role')
			->with('cool_kid')
			->andReturn(true);

		Functions\expect('get_user_by')
			->once()
			->with('email', 'john@example.com')
			->andReturn(false);

		$response = $this->role_change_api->change_user_role($request);

		$this->assertInstanceOf(WP_REST_Response::class, $response);
		$this->assertEquals(404, $response->get_status());
		$this->assertEquals('User not found.', $response->get_data()['message']);
	}

	public function testChangeUserRoleSuccess() {
		$request = new WP_REST_Request('POST', '/cool-kids-network/v1/change-role');
		$request->set_param('user_identifier', 'john@example.com');
		$request->set_param('new_role', 'cool_kid');

		$this->role_manager_mock->shouldReceive('is_valid_role')
			->with('cool_kid')
			->andReturn(true);

		$user_mock = Mockery::mock('WP_User');
		$user_mock->ID = 1;
		$user_mock->user_login = 'john';
		$user_mock->user_email = 'john@example.com';
		$user_mock->display_name = 'John Doe';
		$user_mock->roles = ['subscriber'];
		$user_mock->shouldReceive('set_role')->once()->with('cool_kid');

		Functions\expect('get_user_by')
			->once()
			->with('email', 'john@example.com')
			->andReturn($user_mock);

		$response = $this->role_change_api->change_user_role($request);

		$this->assertInstanceOf(WP_REST_Response::class, $response);
		$this->assertEquals(200, $response->get_status());
		$this->assertEquals('User role updated successfully.', $response->get_data()['message']);
		$this->assertEquals('cool_kid', $response->get_data()['user']['new_role']);
	}

	public function testCheckApiPermissions() {
		$request = new WP_REST_Request('POST', '/cool-kids-network/v1/change-role');
		$request->add_header('X-API-Key', '8f7d9e2a3b1c5f4e6g8h7i9j0k1l2m3n');

		$result = $this->role_change_api->check_api_permissions($request);

		$this->assertTrue($result);
	}

	public function testCheckApiPermissionsInvalidKey() {
		$request = new WP_REST_Request('POST', '/cool-kids-network/v1/change-role');
		$request->add_header('X-API-Key', 'invalid_key');

		$result = $this->role_change_api->check_api_permissions($request);

		$this->assertFalse($result);
	}
}
