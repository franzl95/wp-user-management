=== WP User Management ===
Contributors: deinname
Donate link:
Tags: user management, user registration, login, security, recaptcha
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.5.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Frontend user registration with email activation, profile editing and login restriction for inactive accounts.

== Description ==

WP User Management extends WordPress with frontend-based user registration and profile management.

The plugin focuses on controlled user activation, secure login handling and clear separation between frontend functionality and administrative control.

It is suitable for websites that require moderated registrations instead of immediate user access.

== Features ==

* Frontend user registration via shortcode
* Email-based account activation
* Login restriction for non-activated users
* Frontend profile editing for logged-in users
* Manual user activation via wp-admin user profile
* Google reCAPTCHA v3 support
* Nonce-based CSRF protection
* Configurable logging (error / warning / info)
* Mail logging via `wp_mail` hooks
* Internationalization ready

== Data Handling ==

This plugin stores additional user meta data:

* `_status`  
  Possible values: `pending`, `active`

* `wpum_activation`  
  Stores a hashed activation token used for email verification

If reCAPTCHA is enabled, data is transmitted to Google according to their privacy policy.

== Installation ==

1. Upload the folder `wp-user-management` to `/wp-content/plugins/`
2. Activate the plugin through the WordPress admin area
3. Configure the plugin via **WP User Management** in the admin menu
4. Add the provided shortcodes to a page

== Usage ==

=== Available Shortcodes ===

`[wpum_register]`  
Displays the frontend registration form.

`[wpum_edit_profile]`  
Allows logged-in users to edit their profile.

`[wpum_resend_activation]`  
Allows users to request a new activation email.

== Frequently Asked Questions ==

= Does the plugin activate users automatically? =
No. Newly registered users must activate their account via email or be activated manually by an administrator.

= Does the plugin work without reCAPTCHA? =
Yes. reCAPTCHA is optional and can be enabled or disabled in the settings.

= Is this plugin compatible with multisite? =
The plugin is compatible with standard WordPress multisite installations.

== Screenshots ==

1. Frontend registration form
2. Admin user activation field
3. Plugin settings page

== Changelog ==

= 1.5.0 =
* Added admin settings page
* Improved logging configuration
* Minor internal improvements

== Upgrade Notice ==

= 1.5.0 =
Recommended update.
