<?php

/**
 * The functionality that handles the display of the reCaptcha form on the WordPress recover password form.
 *
 *
 * @package    Uber_Recaptcha
 * @subpackage Uber_Recaptcha/includes/captcha-on-comment-form
 * @author     Cristian Raiber <hi@cristian.raiber.me>
 */


/**
 * Function that loads the class responsible for generating the display of the
 * reCaptcha form on the WordPress recover password form.
 *
 * Gets called only if the "display captcha on lost password form"
 * option is checked in the back-end
 *
 * @since   1.0.1
 *
 */
function construct_ncr_captcha_on_recover_password_form() {

	$plugin_option = get_option( 'uncr_settings' );

	if ( ! empty( $plugin_option['uncr_lost_pwd'] ) && $plugin_option['uncr_lost_pwd'] == 'uncr_lost_pwd' ) {

		// instantiate the class & load everything else
		return new ncr_captcha_recover_password_form();

	}
}

add_action( 'init', 'construct_ncr_captcha_on_recover_password_form' );


class ncr_captcha_recover_password_form extends ncr_base_class {


	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {

		parent::__construct();

		add_action( 'lostpassword_form', array( $this, 'uncr_display_captcha' ) );
		add_action( 'lostpassword_post', array( $this, 'uncr_validate_recover_pwd_form' ) );
	}

	/**
	 * Function that handles the validation & error messages
	 * for the noCaptcha reCaptcha form
	 *
	 * @since   1.0.1
	 */
	public function uncr_validate_recover_pwd_form() {

		if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
			wp_die( new WP_Error( 'empty_captcha', __( 'CAPTCHA should not be empty', 'uncr_translate' ) ) );
		}

		if ( isset( $_POST['g-recaptcha-response'] ) && $this->uncr_validate_captcha() == 'false' ) {
			wp_die( new WP_Error( 'invalid_captcha', __( 'CAPTCHA response was incorrect', 'uncr_translate' ) ) );
		}
	}
}