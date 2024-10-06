<?php

namespace CoolKidsNetwork\Core;

use CoolKidsNetwork\Features\Registration;
use CoolKidsNetwork\Features\Login;
use CoolKidsNetwork\Features\CharacterManagement;
use CoolKidsNetwork\Traits\Singleton;

class Theme
{
  use Singleton;

  protected function __construct()
  {
    $this->init_features();
    add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
  }

  private function init_features()
  {
    Registration::get_instance();
    Login::get_instance();
    CharacterManagement::get_instance();
  }

  public function enqueue_scripts()
  {
    wp_enqueue_script('cool-kids-network', get_template_directory_uri() . '/assets/js/cool-kids-network.js', ['jquery'], '1.0', true);
    wp_localize_script('cool-kids-network', 'coolKidsNetwork', [
      'ajaxurl' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('cool-kids-network-nonce')
    ]);
  }
}
