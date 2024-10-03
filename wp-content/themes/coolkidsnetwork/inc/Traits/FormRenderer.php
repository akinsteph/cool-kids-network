<?php

/**
 * Trait FormRenderer.
 *
 * Provides methods for rendering forms.
 * 
 * @package Cool Kids Network
 */

namespace CoolKidsNetwork\Traits;

/**
 * Trait FormRenderer.
 *
 * Provides methods for rendering forms.
 */
trait FormRenderer {
  /**
   * Renders a form with the specified action and button text.
   *
   * @param string $action The action for the form.
   * @param string $button_text The text for the form submit button.
   * @return string The form HTML.
   */
  protected function render_form($action, $button_text) {
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

  /**
   * Verifies the AJAX request by checking the nonce.
   *
   * @return void
   */
  protected function verify_ajax_request() {
    check_ajax_referer('cool-kids-network-nonce', 'nonce');
  }

  /**
   * Validates the email address.
   *
   * @param string $email The email address to validate.
   * @return void
   */
  protected function validate_email($email) {
    $email = isset($email) ? sanitize_email(wp_unslash($email)) : '';

    if (!is_email($email)) {
      wp_send_json_error('Invalid email address');
    }

    return $email;
  }
}
