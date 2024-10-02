<?php

/**
 * Login functionality for Cool Kids Network.
 *
 * @package CoolKidsNetwork
 * @subpackage Features
 */

namespace CoolKidsNetwork\Features;

use CoolKidsNetwork\Traits\Singleton;
use CoolKidsNetwork\Traits\FormRenderer;

/**
 * Class Login
 *
 * Handles login functionality for the Cool Kids Network.
 */
class Login {
  use Singleton;
  use FormRenderer;

  protected function __construct() {
    add_action('wp_ajax_nopriv_cool_kids_login', [$this, 'login_user']);
    add_shortcode('cool_kids_login_form', [$this, 'login_form_shortcode']);
  }

  /**
   * Renders the login form shortcode.
   *
   * @return string The login form HTML.
   */
  public function login_form_shortcode() {
    return $this->render_form('login', 'Login');
  }

  /**
   * Handles the login user AJAX request.
   *
   * @return void
   */
  public function login_user() {
    $this->verify_ajax_request();

    $email = $this->validate_email($_POST['email']);

    $user = get_user_by('email', $email);

    if (!$user) {
      wp_send_json_error('User not found');
    }

    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID);

    wp_send_json_success('Logged in successfully');
  }
}
