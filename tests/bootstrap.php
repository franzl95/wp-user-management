<?php
/**
 * PHPUnit bootstrap file for WP User Management
 */

declare(strict_types=1);

/*
 * ------------------------------------------------------------
 * 1. Error reporting
 * ------------------------------------------------------------
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
 * ------------------------------------------------------------
 * 2. Define paths
 * ------------------------------------------------------------
 */
$_tests_dir = getenv('WP_TESTS_DIR');

if (!$_tests_dir) {
    $_tests_dir = '/tmp/wordpress-tests-lib';
}

if (!file_exists($_tests_dir . '/includes/functions.php')) {
    fwrite(STDERR, "WP test suite not found in $_tests_dir\n");
    exit(1);
}

/*
 * ------------------------------------------------------------
 * 3. Load PHPUnit Polyfills (REQUIRED)
 * --------------------------------*
