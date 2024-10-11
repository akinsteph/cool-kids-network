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
	protected function render_form($action, $fields, $button_text) {
		ob_start();
?>
		<form id="cool-kids-<?php echo esc_attr($action); ?>-form" class="authentication-form-element">
			<?php wp_nonce_field("cool-kids-{$action}", "cool-kids-{$action}-nonce"); ?>
			<div id="<?php echo esc_attr($action); ?>-error" class="error-message" style="display: none;"></div>
			<div id="<?php echo esc_attr($action); ?>-success" class="success-message" style="display: none;"></div>
			<?php
			foreach ($fields as $field) {
				$this->render_field($field);
			}
			?>
			<button type="submit"><?php echo esc_html($button_text); ?></button>
		</form>
<?php
		return ob_get_clean();
	}

	/**
	 * Renders a form field.
	 *
	 * @param array $field The field data.
	 * @return void
	 */
	protected function render_field($field) {
		$type = isset($field['type']) ? $field['type'] : 'text';
		$name = isset($field['name']) ? $field['name'] : '';
		$label = isset($field['label']) ? $field['label'] : '';
		$required = isset($field['required']) && $field['required'] ? 'required' : '';
		$placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
		$id = isset($field['id']) ? $field['id'] : $name;

		echo '<div class="form-field">';
		if ($label) {
			echo '<label for="' . esc_attr($id) . '">' . esc_html($label) . '</label>';
		}
		echo '<input type="' . esc_attr($type) . '" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" placeholder="' . esc_attr($placeholder) . '" ' . $required . '>';
		echo '</div>';
	}

	/**
	 * Verifies the AJAX request by checking the nonce.
	 *
	 * @return void
	 */
	protected function verify_nonce($action) {
		$nonce = $_POST["cool-kids-{$action}-nonce"];
		if (!wp_verify_nonce($nonce, "cool-kids-{$action}")) {
			wp_send_json_error('Invalid nonce');
		}
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

	protected function encrypt_data($data) {
		$key = 'coolkidsnetwork-crypt';
		$encrypted = '';
		$data_string = json_encode($data);
		for ($i = 0; $i < strlen($data_string); $i++) {
			$encrypted .= chr(ord($data_string[$i]) ^ ord($key[$i % strlen($key)]));
		}
		return base64_encode($encrypted);
	}

	protected function decrypt_data($encrypted_data) {
		$key = 'coolkidsnetwork-crypt';
		$decrypted = '';
		$data = base64_decode($encrypted_data);
		for ($i = 0; $i < strlen($data); $i++) {
			$decrypted .= chr(ord($data[$i]) ^ ord($key[$i % strlen($key)]));
		}
		return json_decode($decrypted, true);
	}
}
