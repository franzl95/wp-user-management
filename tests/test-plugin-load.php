<?php
/**
 * Plugin load test
 */

class WPUM_Plugin_Load_Test extends WP_UnitTestCase {

    public function test_plugin_is_loaded() {
        $this->assertTrue(
            defined( 'WPUM_VERSION' ),
            'WP User Management plugin did not load properly.'
        );
    }

    public function test_plugin_file_exists() {
        $this->assertFileExists(
            WP_PLUGIN_DIR . '/wp-user-management/wp-user-management.php'
        );
    }
}
