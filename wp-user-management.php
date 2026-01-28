<?php
/**
 * Plugin Name: WP User Management
 * Description: Lightweight user management with activation workflow and optional reCAPTCHA.
 * Version: 1.0.1
 * Author: Your Name
 * Text Domain: wp-user-management
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'WPUM_VERSION', '1.0.1' );
define( 'WPUM_PLUGIN_FILE', __FILE__ );
define( 'WPUM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Load textdomain
 */
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain(
		'wp-user-management',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
} );

/**
 * Enqueue frontend styles
 */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'wpum-style',
		plugins_url( 'assets/css/wpum-style.css', __FILE__ ),
		[],
		WPUM_VERSION
	);
} );

/**
 * Add admin menu
 */
add_action( 'admin_menu', function () {
	add_options_page(
		__( 'WP User Management', 'wp-user-management' ),
		__( 'User Management', 'wp-user-management' ),
		'manage_options',
		'wpum-settings',
		'wpum_render_settings_page'
	);
} );

/**
 * Register settings
 */
add_action( 'admin_init', function () {
	register_setting( 'wpum_settings', 'wpum_enable_logging' );
	register_setting( 'wpum_settings', 'wpum_log_level' );
	register_setting( 'wpum_settings', 'wpum_recaptcha_enabled' );
	register_setting( 'wpum_
