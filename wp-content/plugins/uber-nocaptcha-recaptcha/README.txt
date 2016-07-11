=== Plugin Name ===
Contributors: cristian.raiber
Tags: comments, spam, recaptcha, login protection, comment protection, spam protection, nocaptcha, recaptcha, captcha
Requires at least: 3.9
Tested up to: 4.5.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds the reCaptcha form to the WordPress login form, recover password form, register form and comment form.

== Description ==

A very useful plugin for everyone using WordPress. Adds reCaptcha security to the WordPress login form, register form and comment form. This plugin could help your blog get rid of a lot of spam comments or brute-force attacks.

Nothing gets passed it if the reCaptcha doesn't validate.

A few notes about the plugin:

*   Supports audio or image captcha types
*   Can generate the reCaptcha image / audio type in a number of predefined languages
*   Adds reCaptcha protection to the WordPress login form
*   Adds reCaptcha protection to the WordPress register form
*   Adds reCaptcha protection to the WordPress comment form
*   Adds reCaptcha protection to the WordPress recover password form

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the whole contents of the folder `uber-recaptcha` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Fill in your Site & Secret key, which you can get here: https://www.google.com/recaptcha/
1. Select the type of captcha you want: audio / image
1. Select where you'd want the reCaptcha form to be rendered: login, register or comment form
1. Enjoy a spam free blog & extra security for your back-end panel :)



== Screenshots ==

1. Back-end UI

== Changelog ==

= 1.0.5 =
* Re-worked the UI
* Fixed a bunch of notices
* Added branding
* Added more visible notice where you can get your reCaptcha keys
* Removed /admin/view and moved the content into settings.php
* Removed readme.md

= 1.0.4 = 
* Fixed the reamde.md to display properly on the plugin page.

= 1.0.3 =
* Made sure plugin works with WP 4.5 and upwards.
* Removed UTF-8 incomptabile strings from the plugin name. This made the plugin name show up quirky on the wordpress.org plugin landing page.
* Centered the reCaptcha form on the login screen & recover/register password screens
* Changed the hook the reCaptcha form was using for being displayed on the comment form.
* Slightly re-worked the CSS so that the reCaptcha form is being nicely displayed/aligned with more themes.

= 1.0.2 =
* Fixed captcha comment not showing up on comment form where a hook was missing (only affected a couple of themes)

= 1.0.1 =
* Added reCaptcha on recover password form
* PHP 5.3.29 compatibility fix
* Minor other fixes

= 1.0.0 = 
* Initial release