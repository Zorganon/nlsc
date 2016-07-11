<?php


/**
 * The dashboard-specific functionality of the plugin.
 *
 *
 * @package    Uber_Recaptcha
 * @subpackage Uber_Recaptcha/admin/settings
 * @author     Cristian Raiber <hi@cristian.raiber.me>
 */
class ncr_options_panel extends ncr_render_engine {

	/**
	 * Initialize the class and set its properties.
	 *
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ncr_register_menu_page' ) );

		// load styles
		add_action( 'admin_enqueue_scripts', array( $this, 'back_end_styles' ) );

	}


	/**
	 * Function that handles the creation of a new menu page for the plugin
	 *
	 * @since   1.0.0
	 */
	public function ncr_register_menu_page() {
		add_menu_page( __( 'Uber reCaptcha', 'uncr-translate' ),       // page title
			__( 'Uber reCaptcha', 'uncr-translate' ),       // menu title
			'manage_options',                               // capability
			'uber-ncr-settings',                            // menu-slug
			array( $this, 'ncr_render_settings' ),          // callback function to render the options
			'dashicons-shield-alt'  // icon
		);

	}

	public function back_end_styles() {

		// load styles
		wp_register_style( 'uncr-back-end-style', UNCR__PLUGIN_URL . '/css/back-end-styles.css', false, '1.0' );
		wp_enqueue_style( 'uncr-back-end-style' );

	}

	/**
	 * Function that holds the required back-end field mark-up.
	 *
	 * @since   1.0.0
	 *
	 * @return  array   $settings   Holds all the mark-up required for the field rendering engine to render the fields
	 */
	public function ncr_settings_fields() {

		$settings = array(

			'public-key'       => array(
				'title' => __( 'reCaptcha site key', 'uncr_translate' ),
				'type'  => 'text',
				'id'    => 'public_key_text' // id is generated using name + '_' + type
			),
			'private-key'      => array(
				'title' => __( 'reCaptcha secret key', 'uncr_translate' ),
				'type'  => 'text',
				'id'    => 'private_key_text' // id is generated using name + '_' + type
			),
			'captcha-theme'    => array(
				'title'   => __( 'Select reCaptcha skin', 'uncr_translate' ),
				'type'    => 'radio',
				'id'      => 'captcha_theme_radio',
				'options' => array(
					'dark'  => __( 'Dark Theme', 'uncr-translate' ),
					'light' => __( 'Light Theme', 'uncr-translate' ),
				),
			),
			'captcha-type'     => array(
				'title'   => __( 'Captcha type', 'uncr_translate' ),
				'type'    => 'radio',
				'id'      => 'captcha_type_radio',
				'options' => array( //keys in the array should always be prefixed
				                    'audio' => __( 'Audio Captcha', 'uncr-translate' ),
				                    'image' => __( 'Image Captcha', 'uncr-translate' ),
				),
			),
			'captcha-language' => array(
				'title'   => __( 'reCaptcha language', 'uncr_translate' ),
				'type'    => 'select',
				'id'      => 'captcha_language_select',
				'options' => array(
					__( 'Auto Detect', 'uncr_translate' )         => '',
					__( 'English', 'uncr_translate' )             => 'en',
					__( 'Arabic', 'uncr_translate' )              => 'ar',
					__( 'Bulgarian', 'uncr_translate' )           => 'bg',
					__( 'Catalan Valencian', 'uncr_translate' )   => 'ca',
					__( 'Czech', 'uncr_translate' )               => 'cs',
					__( 'Danish', 'uncr_translate' )              => 'da',
					__( 'German', 'uncr_translate' )              => 'de',
					__( 'Greek', 'uncr_translate' )               => 'el',
					__( 'British English', 'uncr_translate' )     => 'en_gb',
					__( 'Spanish', 'uncr_translate' )             => 'es',
					__( 'Persian', 'uncr_translate' )             => 'fa',
					__( 'French', 'uncr_translate' )              => 'fr',
					__( 'Canadian French', 'uncr_translate' )     => 'fr_ca',
					__( 'Hindi', 'uncr_translate' )               => 'hi',
					__( 'Croatian', 'uncr_translate' )            => 'hr',
					__( 'Hungarian', 'uncr_translate' )           => 'hu',
					__( 'Indonesian', 'uncr_translate' )          => 'id',
					__( 'Italian', 'uncr_translate' )             => 'it',
					__( 'Hebrew', 'uncr_translate' )              => 'iw',
					__( 'Jananese', 'uncr_translate' )            => 'ja',
					__( 'Korean', 'uncr_translate' )              => 'ko',
					__( 'Lithuanian', 'uncr_translate' )          => 'lt',
					__( 'Latvian', 'uncr_translate' )             => 'lv',
					__( 'Dutch', 'uncr_translate' )               => 'nl',
					__( 'Norwegian', 'uncr_translate' )           => 'no',
					__( 'Polish', 'uncr_translate' )              => 'pl',
					__( 'Portuguese', 'uncr_translate' )          => 'pt',
					__( 'Romanian', 'uncr_translate' )            => 'ro',
					__( 'Russian', 'uncr_translate' )             => 'ru',
					__( 'Slovak', 'uncr_translate' )              => 'sk',
					__( 'Slovene', 'uncr_translate' )             => 'sl',
					__( 'Serbian', 'uncr_translate' )             => 'sr',
					__( 'Swedish', 'uncr_translate' )             => 'sv',
					__( 'Thai', 'uncr_translate' )                => 'th',
					__( 'Turkish', 'uncr_translate' )             => 'tr',
					__( 'Ukrainian', 'uncr_translate' )           => 'uk',
					__( 'Vietnamese', 'uncr_translate' )          => 'vi',
					__( 'Simplified Chinese', 'uncr_translate' )  => 'zh_cn',
					__( 'Traditional Chinese', 'uncr_translate' ) => 'zh_tw',

				),
			),


			'captcha-presence' => array(
				'title'   => __( 'Enable reCaptcha on:', 'uncr_translate' ),
				'type'    => 'checkbox',
				'id'      => 'captcha_presence_checkbox',
				'options' => array( //keys in the array should always be prefixed
				                    'uncr_login_form'    => __( 'Login Screen', 'uncr-translate' ),
				                    'uncr_register_form' => __( 'Register Screen', 'uncr-translate' ),
				                    'uncr_comment_form'  => __( 'Comment Form', 'uncr-translate' ),
				                    'uncr_lost_pwd'      => __( 'Recover Password Form', 'uncr-translate' ),
				),
			),

		);

		return $settings;

	}

	/**
	 * Function that registers the settings sections
	 */
	public function ncr_render_settings() {

		// Check that the user is allowed to update options
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'uncr-translate' ) );
		}

		// save options
		$this->ncr_save_settings();

		register_setting( 'uncr_settings_group', 'uncr_settings', array( $this, 'uncr_validate_fields' ) );
		add_settings_section( 'uncr_settings_section', null, null, 'uncr_settings_section_call' );

		foreach ( $this->ncr_settings_fields() as $settings_id => $settings_arguments ) {

			add_settings_field( $settings_id,                        // unique ID for the field
				$settings_arguments['title'],        // title of the field
				array( $this, 'ncr_render_field' ),   // function callback
				'uncr_settings_section_call',        // page name, should be the same as the last argument used in add_settings_section
				'uncr_settings_section',             // same as first argument passed to add_settings_section
				$settings_arguments                  // $args, passed as array; defined in ncr_settings_field()
			);
		} ?>

		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class='wrap uncr-wrap'>

			<h1><?php _e( 'Uber Google reCaptcha plugin', 'uncr-translate' ); ?></h1>
			<p class="uncr-about-text"><?php echo __( 'Thank you for choosing Uber Google reCaptcha plugin. An easy to use security plugin that adds Googles\' reCaptcha to comment, register & lost password forms.', 'uncr-translate' ); ?>

			<div class='uncr-badge'>
				<span><?php echo __( 'Version: ', 'uncr-translate' ) . UNCR__PLUGIN_VERSION; ?></span></div>

			<?php settings_errors(); ?>

			<form method="post" action="">

				<?php

				$options = get_option( 'uncr_settings' );

				if ( empty( $options['public_key_text'] ) || empty( $options['private_key_text'] ) ) { ?>
					<p class="get-recaptcha-key"><?php echo __( 'Get your reCaptcha site & secret keys by clicking the following link:  ', 'uncr-translate' ) . '<a href="https://www.google.com/recaptcha/intro/index.html" target="_blank" title="Google reCaptcha">' . __( 'Opens in a new tab', 'uncr-translate' ) . '</a>'; ?></p>
				<?php } ?>

				<?php settings_fields( 'uncr_settings_group' );               //settings group, defined as first argument in register_setting ?>
				<?php do_settings_sections( 'uncr_settings_section_call' );   //same as last argument used in add_settings_section ?>
				<?php submit_button(); ?>

				<?php wp_nonce_field( 'uncr_settings_nonce' ); ?>
				<div class="clear"></div>
			</form>

			<div class="clear"></div>

		</div>

	<?php }

	/**
	 * Function that calls the rendering engine
	 *
	 * @param   array $args Each array entry defiend in the ncr_settings_fields() is passed as a parameter to this
	 *                      function
	 *
	 * @since   1.0.0
	 */

	public function ncr_render_field( $args ) {

		switch ( $args['type'] ) {

			case 'text':
				echo $this->render_text_field( $args );
				break;
			case 'radio':
				echo $this->render_radio_field( $args );
				break;
			case 'checkbox':
				echo $this->render_checkbox_field( $args );
				break;
			case 'select':
				echo $this->render_select_field( $args );
				break;

		}

	}


	/**
	 * Function that saves the plugin options to the database
	 *
	 * @since   1.0.0
	 */
	public function ncr_save_settings() {

		if ( isset( $_POST['uncr_settings'] ) && check_admin_referer( 'uncr_settings_nonce', '_wpnonce' ) ) {

			update_option( 'uncr_settings', $_POST['uncr_settings'] );

		}
	}

	public function uncr_validate_fields( $input ) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach ( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if ( isset( $input[ $key ] ) ) {

				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );

			} // end if

		} // end foreach

		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'uncr_plugin_validate_settings', $output, $input );
	}
}

// instantiate the class
new ncr_options_panel();