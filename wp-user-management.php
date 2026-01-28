<?php
/**
 * Plugin Name: WP User Management
 * Description: Benutzerregistrierung mit E-Mail-Aktivierung, Profilbearbeitung, Login-Sperre, Logging und Admin-Aktivierung.
 * Version: 1.5.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * Text Domain: wpum
 */

defined('ABSPATH') || exit;

final class WPUM_Plugin {

    const VERSION = '1.5.0';

    const OPT_LOG_LEVEL = 'wpum_log_level';

    const META_STATUS  = 'wpum_status';
    const META_TOKEN   = 'wpum_activation';
    const META_EXPIRES = 'wpum_activation_expires';

    private $levels = [
        'error'   => 1,
        'warning' => 2,
        'info'    => 3,
    ];

    public function __construct() {

        add_action('init', [$this, 'load_textdomain']);
        add_action('init', [$this, 'handle_activation_link']);

        add_action('show_user_profile', [$this, 'admin_user_activation']);
        add_action('edit_user_profile', [$this, 'admin_user_activation']);
        add_action('personal_options_update', [$this, 'save_admin_activation']);
        add_action('edit_user_profile_update', [$this, 'save_admin_activation']);

        add_shortcode('wpum_register', [$this, 'shortcode_register']);
        add_shortcode('wpum_edit_profile', [$this, 'shortcode_profile']);
        add_shortcode('wpum_resend_activation', [$this, 'shortcode_resend']);

        add_filter('wp_authenticate_user', [$this, 'block_pending_users'], 10, 1);

        add_filter('wp_mail', [$this, 'log_mail_attempt']);
        add_action('wp_mail_failed', [$this, 'log_mail_error']);
    }

    /* ================= LOGGER ================= */

    private function log($level, $message, $context = []) {

        $current = get_option(self::OPT_LOG_LEVEL, 'error');

        if (!isset($this->levels[$level]) || $this->levels[$level] > $this->levels[$current]) {
            return;
        }

        if (!empty($context)) {
            $message .= ' | ' . wp_json_encode($context);
        }

        error_log('[WPUM][' . strtoupper($level) . '] ' . $message);
    }

    /* ================= MAIL LOGGING ================= */

    public function log_mail_attempt($args) {

        $this->log('info', 'Mail gesendet', [
            'to'      => $args['to'],
            'subject' => $args['subject']
        ]);

        return $args;
    }

    public function log_mail_error($wp_error) {

        $this->log('error', 'Mail Fehler', [
            'error' => $wp_error->get_error_message()
        ]);
    }

    /* ================= AKTIVIERUNGSMAIL ================= */

    private function send_activation_mail($user_id) {

        $user = get_userdata($user_id);
        if (!$user) {
            return;
        }

        $token   = wp_generate_password(32, false, false);
        $expires = time() + DAY_IN_SECONDS;

        update_user_meta($user_id, self::META_TOKEN, $token);
        update_user_meta($user_id, self::META_EXPIRES, $expires);

        $url = add_query_arg([
            'wpum_activate' => 1,
            'uid'           => $user_id,
            'key'           => $token,
            '_wpnonce'      => wp_create_nonce('wpum_activate_' . $user_id),
        ], site_url('/'));

        add_filter('wp_mail_content_type', [$this, 'mail_html']);

        $subject = __('Account aktivieren', 'wpum');

        $message = '
            <h2>' . esc_html__('Account aktivieren', 'wpum') . '</h2>
            <p>' . esc_html__('Bitte bestätige deine Registrierung:', 'wpum') . '</p>
            <p>
                <a href="' . esc_url($url) . '" style="padding:12px 18px;background:#2271b1;color:#fff;text-decoration:none;border-radius:4px">
                    ' . esc_html__('Jetzt aktivieren', 'wpum') . '
                </a>
            </p>
            <p><small>' . esc_html__('Der Link ist 24 Stunden gültig.', 'wpum') . '</small></p>
        ';

        wp_mail($user->user_email, $subject, $message);

        remove_filter('wp_mail_content_type', [$this, 'mail_html']);

        $this->log('info', 'Aktivierungs-Mail versendet', ['user_id' => $user_id]);
    }

    public function mail_html() {
        return 'text/html';
    }

    /* ================= AKTIVIERUNG ================= */

    public function handle_activation_link() {

        if (!isset($_GET['wpum_activate'], $_GET['uid'], $_GET['key'], $_GET['_wpnonce'])) {
            return;
        }

        $user_id = absint($_GET['uid']);

        if (!wp_verify_nonce($_GET['_wpnonce'], 'wpum_activate_' . $user_id)) {
            return;
        }

        $token   = get_user_meta($user_id, self::META_TOKEN, true);
        $expires = (int) get_user_meta($user_id, self::META_EXPIRES, true);

        if (!$token || time() > $expires || !hash_equals($token, $_GET['key'])) {
            return;
        }

        update_user_meta($user_id, self::META_STATUS, 'active');
        delete_user_meta($user_id, self::META_TOKEN);
        delete_user_meta($user_id, self::META_EXPIRES);

        wp_safe_redirect(add_query_arg('activated', '1', wp_login_url()));
        exit;
    }

    /* ================= SHORTCODES ================= */

    public function shortcode_register() {

        if (is_user_logged_in()) {
            return esc_html__('Du bist bereits eingeloggt.', 'wpum');
        }

        if (isset($_POST['wpum_register']) && wp_verify_nonce($_POST['_wpnonce'], 'wpum_register')) {

            $user_id = register_new_user(
                sanitize_user($_POST['username']),
                sanitize_email($_POST['email'])
            );

            if (!is_wp_error($user_id)) {

                wp_set_password($_POST['password'], $user_id);
                update_user_meta($user_id, self::META_STATUS, 'pending');

                $this->send_activation_mail($user_id);

                return '<p class="wpum-success">' .
                    esc_html__('Registrierung erfolgreich. Bitte E-Mail bestätigen.', 'wpum') .
                '</p>';
            }

            return '<p class="wpum-error">' . esc_html($user_id->get_error_message()) . '</p>';
        }

        return '
        <form method="post" class="wpum-box">
            ' . wp_nonce_field('wpum_register', '_wpnonce', true, false) . '
            <input class="wpum-in" name="username" required placeholder="' . esc_attr__('Benutzername', 'wpum') . '">
            <input class="wpum-in" type="email" name="email" required placeholder="' . esc_attr__('E-Mail', 'wpum') . '">
            <input class="wpum-in" type="password" name="password" required placeholder="' . esc_attr__('Passwort', 'wpum') . '">
            <button class="wpum-btn" name="wpum_register">' . esc_html__('Registrieren', 'wpum') . '</button>
        </form>';
    }

    public function shortcode_resend() {

        if (!is_user_logged_in()) {
            return '';
        }

        $user_id = get_current_user_id();

        if (get_user_meta($user_id, self::META_STATUS, true) === 'active') {
            return esc_html__('Account ist bereits aktiv.', 'w_
