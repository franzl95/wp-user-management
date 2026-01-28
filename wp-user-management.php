<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Plugin Name: WP User Management
 * Description: Lightweight user management with logging and optional reCAPTCHA protection.
 * Version: 1.0.2
 * Author: Your Name
 * Text Domain: wpum
 * Domain Path: /languages
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Disable licensing for wordpress.org version
 */
if ( ! defined( 'WPUM_ENABLE_LICENSING' ) ) {
    define( 'WPUM_ENABLE_LICENSING', false );
}

class WP_User_Management {

    const OPT_LOG_LEVEL         = 'wpum_log_level';
    const OPT_RECAPTCHA_ENABLED = 'wpum_recaptcha_enabled';
    const OPT_RECAPTCHA_SITE    = 'wpum_recaptcha_site_key';
    const OPT_RECAPTCHA_SECRET  = 'wpum_recaptcha_secret_key';

    private $log_levels = [
        'error'   => 1,
        'warning' => 2,
        'info'    => 3,
    ];

    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
        add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_shortcode( 'wpum_register', [ $this, 'shortcode_register' ] );
    }

    /* -------------------------------------------------------------------------
     * i18n
     * ---------------------------------------------------------------------- */
    public function load_textdomain() {
        load_plugin_textdomain(
            'wpum',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages'
        );
    }

    /* -------------------------------------------------------------------------
     * Admin
     * ---------------------------------------------------------------------- */
    public function register_admin_menu() {
        add_options_page(
            __( 'WP User Management', 'wpum' ),
            __( 'WP User Management', 'wpum' ),
            'manage_options',
            'wpum-settings',
            [ $this, 'render_settings_page' ]
        );
    }

    public function register_settings() {

        register_setting(
            'wpum_settings',
            self::OPT_LOG_LEVEL,
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => 'error',
            ]
        );

        register_setting(
            'wpum_settings',
            self::OPT_RECAPTCHA_ENABLED,
            [
                'type'    => 'boolean',
                'default' => false,
            ]
        );

        register_setting(
            'wpum_settings',
            self::OPT_RECAPTCHA_SITE,
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        register_setting(
            'wpum_settings',
            self::OPT_RECAPTCHA_SECRET,
            [
                'type'              => 'string',
