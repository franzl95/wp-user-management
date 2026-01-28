<?php
/*
Plugin Name: WP User Management
Description: Simple user management tools for WordPress administrators.
Version: 1.0.0
Author: Franzl Horvath
Author URI: https://einfachalles.at
License: GPLv2 or later
Text Domain: wp-user-management
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add settings page
 */
function wpum_add_settings_page() {
    add_options_page(
        esc_html__( 'WP User Management', 'wp-user-management' ),
        esc_html__( 'WP User Management', 'wp-user-management' ),
        'manage_options',
        'wp-user-management',
        'wpum_render_settings_page'
    );
}
add_action( 'admin_menu', 'wpum_add_settings_page' );

/**
 * Register setting
 */
function wpum_register_settings() {
    register_setting(
        'wpum_settings_group',
        'wpum_enabled',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'wpum_sanitize_checkbox',
            'default'           => '0',
        )
    );
}
add_action( 'admin_init', 'wpum_register_settings' );

/**
 * Sanitize checkbox
 */
function wpum_sanitize_checkbox( $value ) {
    return $value === '1' ? '1' : '0';
}

/**
 * Render settings page
 */
function wpum_render_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Handle form submit securely
    if (
        isset( $_POST['wpum_nonce'] ) &&
        wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpum_nonce'] ) ), 'wpum_save_settings' )
    ) {
        $enabled = isset( $_POST['wpum_enabled'] ) ? '1' : '0';
        update_option( 'wpum_enabled', $enabled );

        echo '<div class="notice notice-success is-dismissible"><p>';
        echo esc_html__( 'Settings saved.', 'wp-user-management' );
        echo '</p></div>';
    }

    $enabled = get_option( 'wpum_enabled', '0' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__( 'WP User Management', 'wp-user-management' ); ?></h1>

        <form method="post" action="">
            <?php wp_nonce_field( 'wpum_save_settings', 'wpum_nonce' ); ?>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="wpum_enabled">
                            <?php echo esc_html__( 'Enable feature', 'wp-user-management' ); ?>
                        </label>
                    </th>
                    <td>
                        <input
                            type="checkbox"
                            id="wpum_enabled"
                            name="wpum_enabled"
                            value="1"
                            <?php checked( '1', $enabled ); ?>
                        />
                    </td>
                </tr>
            </table>

            <?php submit_button( esc_html__( 'Save Changes', 'wp-user-management' ) ); ?>
        </form>
    </div>
    <?php
}
