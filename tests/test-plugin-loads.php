<?php

class PluginLoadsTest extends WP_UnitTestCase {

    public function test_plugin_is_loaded() {
        $this->assertTrue( class_exists( 'WPUM_Plugin' ) );
    }
}
