<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Basic sanity test for wp-user-management plugin.
 */
final class PluginLoadsTest extends TestCase
{
    public function test_plugin_file_exists(): void
    {
        $plugin_file = dirname(__DIR__) . '/wp-user-management.php';

        $this->assertFileExists(
            $plugin_file,
            'Plugin main file does not exist.'
        );
    }

    public function test_plugin_loaded_without_fatal_error(): void
    {
        // If bootstrap.php required the plugin successfully,
        // reaching this assertion means: no fatal error occurred.
        $this->assertTrue(true);
    }
}
