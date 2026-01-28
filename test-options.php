<?php

class WPUM_Test_Options extends WP_UnitTestCase {

    public function test_default_log_level() {
        $this->assertEquals(
            'error',
            get_option( 'wpum_log_level' )
        );
    }

    public function test_recaptcha_disabled_by_default() {
        $this->assertFalse(
            (bool) get_option( 'wpum_recaptcha_enabled' )
        );
    }

    public function test_setting_update() {
        update_option( 'wpum_log_level', 'info' );
        $this->assertEquals(
            'info',
            get_option( 'wpum_log_level' )
        );
    }
}
