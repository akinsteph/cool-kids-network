<?php

namespace CoolKidsNetwork\Features;

use CoolKidsNetwork\Traits\Singleton;

class CharacterManagement
{
  use Singleton;

  protected function __construct()
  {
    add_shortcode('cool_kids_character_data', [$this, 'display_character_data']);
  }

  public function display_character_data()
  {
    if (!is_user_logged_in()) {
      return 'Please log in to view your character data.';
    }

    $user_id = get_current_user_id();
    $user_data = $this->get_user_data($user_id);

    ob_start();
?>
    <div class="cool-kids-character-data">
      <h2>Your Character</h2>
      <?php foreach ($user_data as $key => $value): ?>
        <p><strong><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?>:</strong> <?php echo esc_html($value); ?></p>
      <?php endforeach; ?>
    </div>
<?php
    return ob_get_clean();
  }

  private function get_user_data($user_id)
  {
    $user = get_userdata($user_id);
    return [
      'name' => $user->first_name . ' ' . $user->last_name,
      'country' => get_user_meta($user_id, 'country', true),
      'email' => $user->user_email,
      'role' => get_user_meta($user_id, 'cool_kids_role', true)
    ];
  }
}
