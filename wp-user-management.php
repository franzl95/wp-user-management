<?php
/**
 * Plugin Name: WP User Management
 * Plugin URI:  https://example.com/wp-user-management
 * Description: Einfaches User-Management Plugin.
 * Version:     1.0.0
 * Author:      Dein Name
 * License:     GPL v2 or later
 * Text Domain: wp-user-management
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPUM_Plugin {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'init', [ $this, 'init' ] );
    }

    /**
     * Init hook
     */
    public function init() {
        // Hier später Plugin-Logik
    }

    /**
     * Beispiel-Methode (für PHPUnit sinnvoll)
     */
    public function is_active() {
        return true;
    }
}

/**
 * Plugin starten
 */
new WPUM_Plugin();
