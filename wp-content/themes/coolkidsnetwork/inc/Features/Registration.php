<?php

namespace CoolKidsNetwork\Features;

use CoolKidsNetwork\API\RandomUserAPI;
use CoolKidsNetwork\Traits\Singleton;
use CoolKidsNetwork\Traits\FormRenderer;

class Registration
{
  use Singleton;
  use FormRenderer;

  private $random_user_api;

  protected function __construct()
  {
    $this->random_user_api = RandomUserAPI::get_instance();
    add_action('wp_ajax_nopriv_cool_kids_register', [$this, 'register_user']);
    add_shortcode('cool_kids_registration_form', [$this, 'registration_form_shortcode']);
  }

  public function registration_form_shortcode()
  {
    return $this->render_form('registration', 'Register');
  }

  public function register_user()
  {
    $this->verify_ajax_request();

    $email = $this->validate_email($_POST['email']);

    if (email_exists($email)) {
      wp_send_json_error('Email already registered');
    }

    $random_user = $this->random_user_api->get_random_user();

    $user_id = wp_insert_user([
      'user_login' => $email,
      'user_email' => $email,
      'user_pass'  => wp_generate_password(),
      'role'       => 'subscriber'
    ]);

    if (is_wp_error($user_id)) {
      wp_send_json_error('Failed to create user');
    }

    $this->update_user_meta($user_id, $random_user);

    wp_send_json_success('User registered successfully');
  }

  private function update_user_meta($user_id, $user_data)
  {
    update_user_meta($user_id, 'first_name', $user_data['first_name']);
    update_user_meta($user_id, 'last_name', $user_data['last_name']);
    update_user_meta($user_id, 'country', $user_data['country']);
    update_user_meta($user_id, 'cool_kids_role', 'Cool Kid');
  }
}
