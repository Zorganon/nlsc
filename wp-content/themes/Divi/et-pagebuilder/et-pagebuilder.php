<?php

define( 'ET_BUILDER_THEME', true );
function et_setup_builder() {
	define( 'ET_BUILDER_DIR', get_template_directory() . '/includes/builder/' );
	define( 'ET_BUILDER_URI', get_template_directory_uri() . '/includes/builder' );
	define( 'ET_BUILDER_LAYOUT_POST_TYPE', 'et_pb_layout' );

	$theme_version = et_get_theme_version();
	define( 'ET_BUILDER_VERSION', $theme_version );

	load_theme_textdomain( 'et_builder', ET_BUILDER_DIR . 'languages' );
	require ET_BUILDER_DIR . 'framework.php';

	et_pb_register_posttypes();
}
add_action( 'init', 'et_setup_builder', 0 );

/**
 * Switch the translation of Visual Builder interface to current user's language
 * @return void
 */
if ( ! function_exists( 'et_fb_set_builder_locale' ) ) :
function et_fb_set_builder_locale() {
	// apply translations inside VB only
	if ( empty( $_GET['et_fb'] ) ) {
		return;
	}

	// make sure switch_to_locale() funciton exists. It was introduced in WP 4.7
	if ( ! function_exists( 'switch_to_locale' ) ) {
		return;
	}

	// do not proceed if user language == website language
	if ( get_user_locale() === get_locale() ) {
		return;
	}

	// switch the translation to user language
	switch_to_locale( get_user_locale() );

	// manually restore the translation for all domains except for the 'et_builder' domain
	// otherwise entire page will be translated to user language, but we need to apply it to VB interface only.

	/* The below code adapted from WordPress

	  wp-includes/class-wp-locale-switcher.php:
	    * load_translations()

	  @copyright 2015 by the WordPress contributors.
	  This program is free software; you can redistribute it and/or modify
	  it under the terms of the GNU General Public License as published by
	  the Free Software Foundation; either version 2 of the License, or
	  (at your option) any later version.

	  This program is distributed in the hope that it will be useful,
	  but WITHOUT ANY WARRANTY; without even the implied warranty of
	  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	  GNU General Public License for more details.

	  You should have received a copy of the GNU General Public License
	  along with this program; if not, write to the Free Software
	  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

	  This program incorporates work covered by the following copyright and
	  permission notices:

	  b2 is (c) 2001, 2002 Michel Valdrighi - m@tidakada.com - http://tidakada.com

	  b2 is released under the GPL

	  WordPress - Web publishing software

	  Copyright 2003-2010 by the contributors

	  WordPress is released under the GPL */

	global $l10n;

	$domains = $l10n ? array_keys( $l10n ) : array();

	load_default_textdomain( get_locale() );

	foreach ( $domains as $domain ) {
		if ( 'et_builder' === $domain ) {
			continue;
		}

		unload_textdomain( $domain );
		get_translations_for_domain( $domain );
	}
}
endif;
add_action( 'after_setup_theme', 'et_fb_set_builder_locale' );
