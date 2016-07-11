<?php
/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       http://www.tamewp.com/uber-recaptcha/
 * @since      1.0.0
 *
 * @package    Uber_Recaptcha
 */


// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/**
 * If it's a multisite, loop over all the blogs where the plugin is activated
 * and delete the options from the DB.
 */
if (is_multisite())
{
    global $wpdb;
    $blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);

    if(!empty($blogs))
    {
        foreach($blogs as $blog)
        {
            switch_to_blog($blog['blog_id']);
            delete_option('uncr_settings');
        }
    }
} else {
    delete_option('uncr_settings');
}