<?php
/**
 * PHPUnit bootstrap file for WP User Management
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
    $_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
    fwrite(
        STDERR,
        "ERROR: WordPress Test Suite not found.\n" .
        "Set WP_TESTS_DIR environment variable.\n"
    );
    exit( 1 );
}

/**
 * Load WP testing functions
 */
require_once $_tests_dir . '/includes/functions.php';

/**
 * Load plugin
 */
tests_add_filter( 'muplugins_loaded', function () {
    require dirname( __DIR__ ) . '/wp-user-management.php';
});

/**
 * Bootstrap WordPress
 */
require $_tests_dir . '/includes/bootstrap.php';
