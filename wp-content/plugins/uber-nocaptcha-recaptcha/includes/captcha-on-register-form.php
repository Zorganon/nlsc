<?php

/**
 * The functionality that handles the display of the reCaptcha form on the WordPress register form.
 *
 * @package    Uber_Recaptcha
 * @subpackage Uber_Recaptcha/includes/captcha-on-register-form
 * @author     Cristian Raiber <hi@cristian.raiber.me>
 */


/**
 * Function that loads the class responsible for generating the display of the
 * reCaptcha form on the WordPress register form.
 *
 * Gets called only if the "display captcha on register form" option is checked
 * in the back-end
 *
 * @since   1.0.0
 *
 */
function construct_ncr_captcha_on_register_form() {

	$plugin_option = get_option( 'uncr_settings' );

	if ( ! empty( $plugin_option['uncr_register_form'] ) && $plugin_option['uncr_register_form'] == 'uncr_register_form' ) {
		// instantiate the class & load everything else
		return new ncr_captcha_on_register();
	}
}

add_action( 'init', 'construct_ncr_captcha_on_register_form' );

class ncr_captcha_on_register extends ncr_base_class {

	/**
	 * Initialize the class and set its properties.
	 *
	 */
	public function __construct() {

		parent::__construct();

		add_action( 'register_form', array( $this, 'uncr_display_captcha' ), 10, 2 );

		add_action( 'registration_errors', array( $this, 'uncr_validate_captcha_registration_field' ), 10, 2 );
	}

	/**
	 * @param   string $sanitized_user_login
	 * @param   string $user_email
	 *
	 * @return  object    WP_Error
	 */
	public function uncr_validate_captcha_registration_field( $sanitized_user_login, $user_email ) {

		if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
			return new WP_Error( 'empty_captcha', __( 'CAPTCHA should not be empty', 'uncr_translate' ) );
		}

		if ( isset( $_POST['g-recaptcha-response'] ) && $this->uncr_validate_captcha() == 'false' ) {
			return new WP_Error( 'invalid_captcha', __( 'CAPTCHA response was incorrect', 'uncr_translate' ) );
		}

		return $sanitized_user_login;
	}
}