<?php

namespace CoolKidsNetwork\Traits;

trait FormRenderer
{
  protected function render_form($action, $button_text)
  {
    ob_start();
?>
    <form id="cool-kids-<?php echo esc_attr($action); ?>-form" class="cool-kids-form">
      <input type="email" name="email" required placeholder="Enter your email">
      <?php wp_nonce_field("cool-kids-{$action}", "cool-kids-{$action}-nonce"); ?>
      <button type="submit"><?php echo esc_html($button_text); ?></button>
    </form>
<?php
    return ob_get_clean();
  }

  protected function verify_ajax_request()
  {
    check_ajax_referer('cool-kids-network-nonce', 'nonce');
  }

  protected function validate_email($email)
  {
    $email = sanitize_email($email);
    if (!is_email($email)) {
      wp_send_json_error('Invalid email address');
    }
    return $email;
  }
}
