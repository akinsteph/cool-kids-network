<?php

namespace CoolKidsNetwork\Tests\Features;

use CoolKidsNetwork\Features\CharacterManagement;
use CoolKidsNetwork\Features\RoleManager;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use Mockery;

class CharacterManagementTest extends TestCase {
	protected $character_management;

	protected function setUp(): void {
		parent::setUp();
		\Brain\Monkey\setUp();

		// Mock WordPress functions
		Functions\when('add_shortcode')->justReturn(true);
		Functions\when('is_user_logged_in')->justReturn(true);
		Functions\when('get_current_user_id')->justReturn(1);
		Functions\when('get_userdata')->justReturn((object)['first_name' => 'John', 'last_name' => 'Doe', 'user_email' => 'john@example.com', 'roles' => ['cool_kid']]);
		Functions\when('get_user_meta')->justReturn('test_value');
		Functions\when('wp_get_attachment_url')->justReturn('http://example.com/avatar.jpg');
		Functions\when('esc_url')->returnArg();
		Functions\when('esc_attr')->returnArg();
		Functions\when('esc_html')->returnArg();
		Functions\when('esc_html__')->returnArg();

		$this->character_management = CharacterManagement::get_instance();
	}

	protected function tearDown(): void {
		Mockery::close();
		\Brain\Monkey\tearDown();
		parent::tearDown();
	}

	public function testDisplayCharacterData() {
		$output = $this->character_management->display_character_data();

		$this->assertStringContainsString('cool-kids-character-data', $output);
		$this->assertStringContainsString('John Doe', $output);
		$this->assertStringContainsString('john@example.com', $output);
		$this->assertStringContainsString('Cool Kid', $output);
	}

	public function testDisplayOtherCharacters() {
		Functions\when('wp_get_current_user')->justReturn((object)['roles' => ['cooler_kid']]);
		Functions\when('get_users')->justReturn([
			(object)['ID' => 2, 'display_name' => 'Jane Doe', 'roles' => ['cool_kid'], 'user_email' => 'jane@example.com'],
		]);

		$output = $this->character_management->display_other_characters();

		$this->assertStringContainsString('other-characters', $output);
		$this->assertStringContainsString('Jane Doe', $output);
		$this->assertStringContainsString('Country:', $output);
		$this->assertStringNotContainsString('Email:', $output);
	}

	public function testDisplayOtherCharactersAsNonPrivilegedUser() {
		Functions\when('wp_get_current_user')->justReturn((object)['roles' => ['cool_kid']]);

		$output = $this->character_management->display_other_characters();

		$this->assertStringContainsString('You do not have permission to view other characters.', $output);
	}

	public function testDisplayOtherCharactersAsCoolestKid() {
		Functions\when('wp_get_current_user')->justReturn((object)['roles' => ['coolest_kid']]);
		Functions\when('get_users')->justReturn([
			(object)['ID' => 2, 'display_name' => 'Jane Doe', 'roles' => ['cool_kid'], 'user_email' => 'jane@example.com'],
		]);

		$output = $this->character_management->display_other_characters();

		$this->assertStringContainsString('other-characters', $output);
		$this->assertStringContainsString('Jane Doe', $output);
		$this->assertStringContainsString('Country:', $output);
		$this->assertStringContainsString('Role:', $output);
		$this->assertStringContainsString('Email:', $output);
	}
}
