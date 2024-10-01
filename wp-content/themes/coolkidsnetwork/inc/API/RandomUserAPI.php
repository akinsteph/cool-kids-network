<?php

namespace CoolKidsNetwork\API;

use CoolKidsNetwork\Traits\Singleton;

class RandomUserAPI
{
  use Singleton;

  private $api_url = 'https://randomuser.me/api/';

  public function get_random_user()
  {
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

    return [
      'first_name' => $user['name']['first'],
      'last_name'  => $user['name']['last'],
      'country'    => $user['location']['country']
    ];
  }
}
