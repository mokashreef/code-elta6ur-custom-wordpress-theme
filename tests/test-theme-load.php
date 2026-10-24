<?php
/**
 * Test simulating WordPress environment loading functions.php and core components
 */
define('ABSPATH', __DIR__ . '/../');
define('CDT_THEME_VERSION', '1.2.5');

// Mock essential WordPress functions if not defined
if (!function_exists('get_template_directory')) {
    function get_template_directory() {
        return realpath(__DIR__ . '/../code-elta6ur-theme');
    }
}
if (!function_exists('get_template_directory_uri')) {
    function get_template_directory_uri() {
        return 'https://example.com/wp-content/themes/code-elta6ur-theme';
    }
}
if (!function_exists('get_stylesheet_uri')) {
    function get_stylesheet_uri() {
        return 'https://example.com/wp-content/themes/code-elta6ur-theme/style.css';
    }
}
if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $args = 1) {}
}
if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $args = 1) {}
}
if (!function_exists('add_theme_support')) {
    function add_theme_support($feature, $args = array()) {}
}
if (!function_exists('register_nav_menus')) {
    function register_nav_menus($menus) {}
}
if (!function_exists('__')) {
    function __($text, $domain = 'default') { return $text; }
}
if (!function_exists('esc_html__')) {
    function esc_html__($text, $domain = 'default') { return $text; }
}
if (!function_exists('esc_attr__')) {
    function esc_attr__($text, $domain = 'default') { return $text; }
}
if (!function_exists('esc_url')) {
    function esc_url($url) { return $url; }
}
if (!function_exists('esc_url_raw')) {
    function esc_url_raw($url) { return $url; }
}
if (!function_exists('home_url')) {
    function home_url($path = '') { return 'https://example.com' . $path; }
}
if (!function_exists('admin_url')) {
    function admin_url($path = '') { return 'https://example.com/wp-admin/' . $path; }
}
if (!function_exists('rest_url')) {
    function rest_url($path = '') { return 'https://example.com/wp-json/' . $path; }
}
if (!function_exists('get_option')) {
    function get_option($opt, $default = false) { return $default; }
}
if (!function_exists('update_option')) {
    function update_option($opt, $val) { return true; }
}
if (!function_exists('is_rtl')) {
    function is_rtl() { return true; }
}
if (!function_exists('is_admin')) {
    function is_admin() { return false; }
}
if (!function_exists('wp_doing_ajax')) {
    function wp_doing_ajax() { return false; }
}
if (!function_exists('current_user_can')) {
    function current_user_can($cap) { return true; }
}
if (!function_exists('wp_logout_url')) {
    function wp_logout_url($redirect = '') { return 'https://example.com/wp-login.php?action=logout'; }
}

$themeDir = realpath(__DIR__ . '/../code-elta6ur-theme');
require_once $themeDir . '/functions.php';

echo "SUCCESS: functions.php and all theme modules loaded with ZERO runtime fatal errors!\n";
