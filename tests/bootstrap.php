<?php
/**
 * PHPUnit bootstrap file for WP User Management
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
    $_tests_dir = '/tmp/wordpress-tests-lib';
}

/**
 * Load PHPUnit Polyfills (Pflicht seit WP 6.x)
 */
if ( ! defined( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH' ) ) {
    define(
        'WP_TESTS_PHPUNIT_POLYFILLS_PATH',
        $_tests_dir . '/vendor/yoast/phpunit-polyfills'
    );
}

/**
 * Load WordPress test functions
 */
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin
 */
function _manually_load_plugin() {
    require dirname( __DIR__ ) . '/wp-user-management.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

/**
 * Start up the WP testing environment
 */
require $_tests_dir . '/includes/bootstrap.php';
