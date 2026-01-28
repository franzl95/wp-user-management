<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'wpum_log_level' );
delete_option( 'wpum_recaptcha_enabled' );
delete_option( 'wpum_recaptcha_site_key' );
delete_option( 'wpum_recaptcha_secret_key' );

/**
 * User meta cleanup
 */
delete_metadata(
    'user',
    0,
    '_status',
    '',
    true
);

delete_metadata(
    'user',
    0,
    'wpum_activation',
    '',
    true
);
