<?php
declare(strict_types=1);

/**
 * PHPUnit bootstrap file
 *
 * This bootstrap is intentionally minimal.
 * It does NOT load WordPress or the WP test suite.
 */

// Safety check: running via CLI
if (php_sapi_name() !== 'cli') {
    exit(1);
}

// Load the plugin main file
$plugin_file = dirname(__DIR__) . '/wp-user-management.php';

if (! file_exists($plugin_file)) {
    fwrite(STDERR, "Plugin file not found: {$plugin_file}\n");
    exit(1);
}

require_once $plugin_file;
