<?php

/**
 * The functionality that handles the display of the reCaptcha form on the WordPress register form.
 *
 * @package    Uber_Recaptcha
 * @subpackage Uber_Recaptcha/includes/captcha-on-login-form
 * @author     Cristian Raiber <hi@cristian.raiber.me>
 */


/**
 * Function that loads the class responsible for generating the display of the
 * reCaptcha form on the WordPress login form.
 *
 * Gets called only if the "display captcha on register form" option is checked
 * in the back-end
 *
 * @since   1.0.0
 *
 */
function construct_ncr_captcha_on_login_form() {

	$plugin_option = get_option( 'uncr_settings' );

	if ( ! empty( $plugin_option['uncr_login_form'] ) && $plugin_option['uncr_login_form'] == 'uncr_login_form' ) {

		// instantiate the class & load everything else
		return new ncr_captcha_on_login();
	}

}

add_action( 'init', 'construct_ncr_captcha_on_login_form' );

class ncr_captcha_on_login extends ncr_base_class {


	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {

		parent::__construct();

		// add Google API JS script on the login section of the site
		add_action( 'login_enqueue_scripts', array( $this, 'uncr_header_script' ), 10, 2 );

		// add CSS to make sure the Google Captcha fits nicely
		add_action( 'login_enqueue_scripts', array( $this, 'uncr_wp_css' ), 10, 2 );

		// adds the required HTML for the REcaptcha to the login form
		add_action( 'login_form', array( $this, 'uncr_display_captcha' ), 10, 2 );

		// authenticate the captcha answer
		add_action( 'wp_authenticate_user', array( $this, 'uncr_captcha_on_login' ), 10, 2 );

	}

	/**
	 * Handles the display of the reCaptcha form on the WordPress login form
	 *
	 * @param   string $user
	 * @param   string $password
	 *
	 * @return  object  WP_Error
	 */
	public function uncr_captcha_on_login( $user, $password ) {

		if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
			return new WP_Error( 'empty_captcha', __( 'CAPTCHA should not be empty', 'uncr_translate' ) );
		}

		if ( isset( $_POST['g-recaptcha-response'] ) && $this->uncr_validate_captcha() == 'false' ) {
			return new WP_Error( 'invalid_captcha', __( 'CAPTCHA response was incorrect', 'uncr_translate' ) );
		}

		return $user;
	}
}