<?php

/**
 * RandomUserAPI class for the Cool Kids Network.
 *
 * @package CoolKidsNetwork
 */

namespace CoolKidsNetwork\API;

use CoolKidsNetwork\Traits\Singleton;

/**
 * Class RandomUserAPI.
 *
 * Handles API requests to the Random User Generator API.
 */
class RandomUserAPI {
  use Singleton;

  private $api_url = 'https://randomuser.me/api/';

  /**
   * Constructor for the RandomUserAPI class.
   *
   * @return void
   */
  protected function __construct() {
    // Private constructor to prevent direct creation
  }

  /**
   * Retrieves a random user from the API.
   *
   * @return array|false The user data or false if the request fails.
   */
  public function get_random_user() {
    $response = wp_remote_get($this->api_url);

    if (is_wp_error($response)) {
      return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data['results'][0])) {
      return false;
    }

    $user = $data['results'][0];
    $avatar_id = $this->upload_avatar($user['picture']['medium']);

    return [
      'first_name' => sanitize_text_field($user['name']['first']),
      'last_name' => sanitize_text_field($user['name']['last']),
      'email' => sanitize_email($user['email']),
      'country' => sanitize_text_field($user['location']['country']),
      'address' => [
        'street' => sanitize_text_field($user['location']['street']['name'] . ' ' . $user['location']['street']['number']),
        'city' => sanitize_text_field($user['location']['city']),
        'state' => sanitize_text_field($user['location']['state']),
        'postcode' => sanitize_text_field($user['location']['postcode']),
      ],
      'avatar_id' => $avatar_id,
    ];
  }

  private function upload_avatar($image_url) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $tmp = download_url($image_url);
    if (is_wp_error($tmp)) {
      return false;
    }

    $file_array = array(
      'name' => basename($image_url),
      'tmp_name' => $tmp
    );

    $id = media_handle_sideload($file_array, 0);

    if (is_wp_error($id)) {
      @unlink($file_array['tmp_name']);
      return false;
    }

    return $id;
  }
}
