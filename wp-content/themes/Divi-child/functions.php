<?php
function admin_default_page() {
  return '/members-home-page';
}
/*---------------------new Stuff ------------------------*/
function my_theme_enqueue_styles() {

    $parent_style = 'Divi';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'Divi-child', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), wp_get_theme()->get('Version') );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
/*---------------------end new Stuff ------------------------*/
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

/** THIS NOT WORKING
 * Code for a Login/Logout Button to be displayed in the Navigation Menu
 * 
add_filter( 'wp_nav_menu_primary_items','wpsites_loginout_menu_link' );

function wpsites_loginout_menu_link( $menu ) {
  $loginout = wp_loginout($_SERVER['REQUEST_URI'], false );
  $menu .= $loginout;
  return $menu;
}
 **/

add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 );

function wti_loginout_menu_link( $items, $args ) {
   if ($args->theme_location == 'primary') {
      if (is_user_logged_in()) {
         $items .= '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="'. wp_logout_url() .'">'. __("Log Out") .'</a></li>';
      } else {
         $items .= '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="'. wp_login_url(get_permalink()) .'">'. __("Log In") .'</a></li>';
      }
   }
   return $items;
}

/*-----------DEBUG ------------  
$debug_tags = array();
add_action( 'all', function ( $tag ) {
    global $debug_tags;
    if ( in_array( $tag, $debug_tags ) ) {
        return;
    }
    echo "<pre>" . $tag . "</pre>";
    $debug_tags[] = $tag;
} );
 */

?>

