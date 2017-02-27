
 
<?php
// wp-content/plugins/user-emails/user-emails.php
/*
Plugin Name: NLSC Registration Email
Description: Changes the default user emails
Version: 1.0
Author: Dan Hudson
Author URI: https://www.josephmsexton.com
*/


/**
 * redefine new user notification function
 *
 * emails new users their login info
 *
 * @author  Dan Hudson <dan.the.hudson@gmail.com>
 * @param 	string integer $user_id user id
 * @param 	string $plaintext_pass optional password
 */
if ( !function_exists( 'wp_new_user_notification' ) ) {
  function wp_new_user_notification( $user_login, $plaintext_pass = '' ) {

    // set content type to html
    add_filter( 'wp_mail_content_type', 'wpmail_content_type' );

    // user
    $user = new WP_User( $user_login );
    $userEmail = stripslashes( $user->user_email );
    $siteUrl = get_site_url();
    $logoUrl = plugin_dir_url( __FILE__ ).'/sitelogo.gif';

    $subject = 'Northern Lights Sailing Club Membership';
    $headers = 'From: NLSC <membership@nlsc.org>';

    // admin email
    $message  = "A new user has been created"."\r\n\r\n";
    $message .= 'Email: '.$userEmail."\r\n";
    @wp_mail( get_option( 'admin_email' ), 'New User Created', $message, $headers );

    ob_start();
    include plugin_dir_path( __FILE__ ).'/email_welcome.php';
    $message = ob_get_contents();
    ob_end_clean();

    @wp_mail( $userEmail, $subject, $message, $headers );

    // remove html content type
    remove_filter ( 'wp_mail_content_type', 'wpmail_content_type' );

  }
}


 
/**
 * wpmail_content_type
 * allow html emails
 *
 * @author Dan Hudson <dan.the.hudson@gmail.com>
 * @return string
 */
function wpmail_content_type() {
 
    return 'text/html';
}

?>