<?php
/**
 * The class that handles the display of admin notices in the WordPress back-end.
 *
 * @package    Uber_Recaptcha
 * @subpackage Uber_Recaptcha/helpers/admin-notices-class
 * @author     Cristian Raiber <hi@cristian.raiber.me>
 */


/**
 * Function that loads the class responsible for generating the display of the
 * admin notices on the back-end.
 *
 * Gets called only if the reCaptcha site / secret key fields are empty
 *
 * @since   1.0.0
 *
 */
function construct_uncr_admin_notices(){

    $plugin_option = get_option('uncr_settings');

    if( $plugin_option['public_key_text'] === '' || $plugin_option['private_key_text'] === '' ) { // check if site / secret key have values in them

            // instantiate the class & load everything else
            new ncr_admin_notices();
    }
}

add_action('init', 'construct_uncr_admin_notices');

class ncr_admin_notices {

    /**
     * Initialize the class and set its properties.
     */
        public function __construct() {

            add_action('admin_notices', array( $this, 'uncr_admin_notice') );
            add_action('admin_init', array( $this, 'uncr_admin_notice_message') );
        }

    /**
     * Function that handles the display of the admin notice
     *
     * @since   1.0.0
     */
        public function uncr_admin_notice() {
            global $current_user ;
            global $pagenow;

            // check to see if it's a custom menu page and if it is, don't show the nagging message :)
            if($pagenow !== 'admin.php') {
                $user_id = $current_user->ID;
                /* Check that the user hasn't already clicked to ignore the message */
                if ( ! get_user_meta($user_id, 'uncr_notice_ignore') ) {

                    echo '<div class="updated"><p>';
                    printf(__('Site / Secrete key for Uber reCaptcha haven\'t yet been completed. Please go <a href="admin.php?page=uber-ncr-settings">here</a> and enter them so the plugin can function. | <a href="%1$s">Hide Notice</a>'), '?ncr_notice_ignore=0');
                    echo "</p></div>";
                }
            }
        }

    /**
     * Function that adds a user meta if the user chooses to hide the plugin specific
     * admin notice
     *
     * @since   1.0.0
     */
    public function uncr_admin_notice_message() {
        global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['uncr_notice_ignore']) && '0' == $_GET['uncr_notice_ignore'] ) {
            add_user_meta($user_id, 'uncr_notice_ignore', 'true', true);
        }
    }
}

