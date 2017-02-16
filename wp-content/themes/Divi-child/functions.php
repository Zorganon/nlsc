<?php
function admin_default_page() {
  return '/members-home-page';
}
/**
 * Function Name: front_end_login_fail.
 * Description: This redirects the failed login to the custom login page instead of default login page with a modified url
**/
add_action( 'wp_login_failed', 'front_end_login_fail' );
function front_end_login_fail( $username ) {

// Getting URL of the login page
$referrer = $_SERVER['HTTP_REFERER'];    
// if there's a valid referrer, and it's not the default log-in screen
if( !empty( $referrer ) && !strstr( $referrer,'wp-login' ) && !strstr( $referrer,'wp-admin' ) ) {
  wp_redirect( get_permalink( 430 ) . "?login=failed" ); 
  exit;
}

}

/**
 * Function Name: check_username_password.
 * Description: This redirects to the custom login page if user name or password is   empty with a modified url
**/
add_action( 'authenticate', 'check_username_password', 1, 3);
function check_username_password( $login, $username, $password ) {

  // Getting URL of the login page
  $referrer = $_SERVER['HTTP_REFERER'];

  // if there's a valid referrer, and it's not the default log-in screen
  if( !empty( $referrer ) && !strstr( $referrer,'wp-login' ) && !strstr( $referrer,'wp-admin' ) ) { 
    if( $username == "" || $password == "" ){
      wp_redirect( get_permalink( 430 ) . "?login=empty" );
      exit;
    }
  }

}

/**
 * Code for a Login/Logout Button to be displayed in the Navigation Menu
 * 
 **/
add_filter( 'wp_nav_menu_primary_items','wpsites_loginout_menu_link' );

function wpsites_loginout_menu_link( $menu ) {
  $loginout = wp_loginout($_SERVER['REQUEST_URI'], false );
  $menu .= $loginout;
  return $menu;
}
?>