<?php

class WPUM_Test_Plugin_Load extends WP_UnitTestCase {

    public function test_plugin_loaded() {
        $this->assertTrue(
            class_exists( 'WP_User_Management' ),
            'Plugin main class should be loaded'
        );
    }

    public function test_textdomain_loaded() {
        do_action( 'plugins_loaded' );
        $this->assertTrue( is_textdomain_loaded( 'wpum' ) );
    }
}
