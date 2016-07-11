<?php
/**
 * The functionality that handles the display of the reCaptcha form on the WordPress comment form.
 *
 * Because we're using is_user_logged_in which is a pluggable function and gets triggered only after the plugins are
 * loaded we needed to hook onto to the init function and run our class only after the core has completely loaded.
 *
 * This makes it possible to check if the user is logged in (or not) and not to display the captcha form
 * if he / she is
 *
 * @package    Uber_Recaptcha
 * @subpackage Uber_Recaptcha/includes/captcha-on-comment-form
 * @author     Cristian Raiber <hi@cristian.raiber.me>
 */


/**
 * Function that loads the class responsible for generating the display of the
 * reCaptcha form on the WordPress comment form.
 *
 * Gets called only if the "display captcha on register form" option is checked
 * in the back-end
 *
 * @since   1.0.0
 *
 */
function construct_ncr_captcha_on_comment_form() {

	$plugin_option = get_option( 'uncr_settings' );

	if ( ! empty( $plugin_option['uncr_comment_form'] ) && $plugin_option['uncr_comment_form'] == 'uncr_comment_form' ) { // check if captcha form on comment form is enabled

		if ( ! is_user_logged_in() ) { // check if user is logged in or not; we shouldn't be loading the class if the user is logged in (works on the principle that only users with privileges will be logged in, such as : admins)

			// instantiate the class & load everything else
			return new ncr_captcha_on_comment_form();
		}
	}
}

add_action( 'init', 'construct_ncr_captcha_on_comment_form' );

class ncr_captcha_on_comment_form extends ncr_base_class {


	/**
	 * Holds the errors for the plugin. Usually used with if the validation function returns a 'false' response
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $error Holds the errors for the plugin
	 */
	protected $error;


	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {

		parent::__construct();

		// add captcha header script to WordPress header
		add_action( 'wp_head', array( $this, 'uncr_header_script' ) );
		add_action( 'wp_head', array( $this, 'uncr_wp_css' ) );


		// adds captcha above the submit button
		add_filter( 'comment_form_after_fields', array( $this, 'uncr_display_captcha_comment_form' ), 10, 2 );

		// authenticate the captcha answer
		add_filter( 'preprocess_comment', array( $this, 'uncr_validate_captcha_comment_field' ), 10, 2 );

		// redirect location for comment
		add_filter( 'comment_post_redirect', array( $this, 'uncr_redirect_fail_captcha_comment' ), 10, 2 );
	}


	/**
	 * Function that handles the generation of the HTML mark-up used by the reCaptcha API to generate the reCaptcha form
	 *
	 * @param   string $default
	 *
	 * @return  string  $default    Holds the HTML mark-up used by the reCaptcha API to generate the reCaptcha form
	 *
	 * @since   1.0.0
	 */
	public function uncr_display_captcha_comment_form( $default ) {


		//ob_start();
		echo '<div class="g-recaptcha" data-sitekey="' . $this->recaptcha_public_key . '" data-theme="' . $this->data_theme . '" data-type="' . $this->data_type . '" ></div>';
		//$default['comment_notes_after'] .= ob_get_contents();
		//ob_end_clean();

		//return $default;

	}

	/**
	 * Function that handles the deletion of the comment if the reCaptcha validation
	 * function returns false.
	 *
	 * @param   string $location
	 * @param   string $comment
	 *
	 * @return  string  $location
	 *
	 * @since   1.0.0
	 *
	 */
	public function uncr_redirect_fail_captcha_comment( $location, $comment ) {

		if ( ! empty( $this->error ) ) {

			// delete the failed captcha comment
			wp_delete_comment( absint( $comment->comment_ID ) );

			// error handling for CAPTCHA verifications
			if ( array_key_exists( 'empty_captcha', $this->error->{'errors'} ) ) {

				wp_die( $this->error->{'errors'}['empty_captcha'][0] ); // captcha field is empty (not checked)

			} else if ( array_key_exists( 'invalid_captcha', $this->error->{'errors'} ) ) {

				wp_die( $this->error->{'errors'}['invalid_captcha'][0] ); // captcha is invalid ( failed verification )
			}
		}

		return $location;
	}

	/**
	 * Function that handles the reCaptcha form validation
	 *
	 * @param   array $commentdata
	 *
	 * @return  array   $commendata    Returns the commentdata as it was sent by the WordPress post
	 */
	public function uncr_validate_captcha_comment_field( $commentdata ) {

		if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
			$this->error = new WP_Error( 'empty_captcha', __( 'CAPTCHA should not be empty', 'uncr_translate' ) );
		}

		if ( isset( $_POST['g-recaptcha-response'] ) && $this->uncr_validate_captcha() == 'false' ) {
			$this->error = new WP_Error( 'invalid_captcha', __( 'CAPTCHA response was incorrect', 'uncr_translate' ) );
		}

		return $commentdata;

	}
}