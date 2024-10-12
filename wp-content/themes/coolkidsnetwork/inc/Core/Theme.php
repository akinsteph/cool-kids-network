<?php

/**
 * Theme class for the Cool Kids Network.
 */

namespace CoolKidsNetwork\Core;

use CoolKidsNetwork\API\RandomUserAPI;
use CoolKidsNetwork\API\RoleChangeAPI;
use CoolKidsNetwork\Features\CharacterManagement;
use CoolKidsNetwork\Features\Login;
use CoolKidsNetwork\Features\Registration;
use CoolKidsNetwork\Features\RoleManager;
use CoolKidsNetwork\Features\Blocks;
use CoolKidsNetwork\Traits\Singleton;

/**
 * Theme class for the Cool Kids Network.
 */
class Theme {
	use Singleton;

	/**
	 * Constructor for the Theme class.
	 *
	 * Initializes the theme and hooks the necessary actions.
	 */
	protected function __construct() {
		$this->init_features();
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
	}

	/**
	 * Initializes the features for the theme.
	 *
	 * @return void
	 */
	private function init_features() {
		ErrorHandler::get_instance();
		Registration::get_instance();
		Login::get_instance();
		CharacterManagement::get_instance();
		RandomUserAPI::get_instance();
		RoleManager::get_instance();
		RoleChangeAPI::get_instance();
		Blocks::get_instance();
	}

	/**
	 * Enqueues the scripts for the theme.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
	}
}
