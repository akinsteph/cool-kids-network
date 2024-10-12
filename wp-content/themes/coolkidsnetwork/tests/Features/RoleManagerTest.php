<?php

namespace CoolKidsNetwork\Tests\Features;

use CoolKidsNetwork\Features\RoleManager;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use Mockery;

class RoleManagerTest extends TestCase {
	protected $role_manager;

	protected function setUp(): void {
		parent::setUp();
		\Brain\Monkey\setUp();

		// Mock WordPress functions
		Functions\when('add_action')->justReturn(true);
		Functions\when('register_deactivation_hook')->justReturn(true);

		$this->role_manager = RoleManager::get_instance();
	}

	protected function tearDown(): void {
		\Brain\Monkey\tearDown();
		Mockery::close();
		parent::tearDown();
	}

	public function testAddCustomRoles() {
		Functions\expect('add_role')
			->times(3)
			->andReturn(true);

		$this->role_manager->add_custom_roles();

		$this->addToAssertionCount(1);
	}

	public function testRemoveCustomRoles() {
		Functions\expect('remove_role')
			->times(3)
			->andReturn(true);

		$this->role_manager->remove_custom_roles();

		$this->addToAssertionCount(1);
	}

	public function testGetCustomRoles() {
		$expected_roles = [
			'cool_kid' => 'Cool Kid',
			'cooler_kid' => 'Cooler Kid',
			'coolest_kid' => 'Coolest Kid',
		];

		$this->assertEquals($expected_roles, $this->role_manager->get_custom_roles());
	}

	public function testIsValidRole() {
		$this->assertTrue($this->role_manager->is_valid_role('cool_kid'));
		$this->assertTrue($this->role_manager->is_valid_role('cooler_kid'));
		$this->assertTrue($this->role_manager->is_valid_role('coolest_kid'));
		$this->assertFalse($this->role_manager->is_valid_role('invalid_role'));
	}

	public function testConstructorHooks() {
		Functions\expect('add_action')
			->once()
			->with(Mockery::type('string'), [$this->role_manager, 'add_custom_roles']);

		Functions\expect('register_deactivation_hook')
			->once()
			->with(Mockery::type('string'), [$this->role_manager, 'remove_custom_roles']);

		// Re-instantiate to trigger constructor
		$new_instance = RoleManager::get_instance();

		$this->addToAssertionCount(1); // Assert that we've reached this point without errors
	}
}
