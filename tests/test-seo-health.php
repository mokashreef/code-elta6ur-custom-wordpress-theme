<?php
/**
 * Automated Verification Test Suite for SEO Health Engine
 * Run with: php tests/test-seo-health.php
 */

// Define mock WP constants and functions if running outside WordPress core
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/../' );
}

if ( ! function_exists( 'sanitize_title' ) ) {
	function sanitize_title( $title ) {
		$title = preg_replace( '/[^\p{Arabic}\p{L}\p{N}\s\-_]+/u', '', $title );
		$title = preg_replace( '/[\s_]+/', '-', $title );
		return trim( mb_strtolower( (string) $title, 'UTF-8' ), '-' );
	}
}

if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( $str ) {
		return trim( strip_tags( (string) $str ) );
	}
}

if ( ! function_exists( 'wp_parse_url' ) ) {
	function wp_parse_url( $url, $component = -1 ) {
		return parse_url( $url, $component );
	}
}

if ( ! function_exists( 'home_url' ) ) {
	function home_url( $path = '' ) {
		return 'https://code-elta6ur.com' . $path;
	}
}

if ( ! function_exists( 'get_option' ) ) {
	function get_option( $opt, $default = array() ) {
		return $default;
	}
}

if ( ! function_exists( 'esc_url' ) ) {
	function esc_url( $url ) {
		return $url;
	}
}

if ( ! function_exists( 'cdt_seo_detect_active_plugin' ) ) {
	function cdt_seo_detect_active_plugin() {
		return 'none';
	}
}

if ( ! function_exists( 'add_action' ) ) {
	function add_action( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}
}

if ( ! function_exists( 'add_filter' ) ) {
	function add_filter( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}
}

if ( ! function_exists( 'remove_action' ) ) {
	function remove_action( $tag, $callback, $priority = 10 ) {}
}

// Load SEO Health modules
require_once __DIR__ . '/../code-elta6ur-theme/inc/seo-health/redirects.php';
require_once __DIR__ . '/../code-elta6ur-theme/inc/seo-health/slug-repair.php';
require_once __DIR__ . '/../code-elta6ur-theme/inc/seo-health/404-log.php';
require_once __DIR__ . '/../code-elta6ur-theme/inc/seo-health/canonical-urls.php';
require_once __DIR__ . '/../code-elta6ur-theme/inc/seo-health/robots.php';

$test_passes = 0;
$test_fails  = 0;

function assert_test( $condition, $name ) {
	global $test_passes, $test_fails;
	if ( $condition ) {
		echo " [PASS] " . $name . "\n";
		$test_passes++;
	} else {
		echo " [FAIL] " . $name . "\n";
		$test_fails++;
	}
}

echo "=== اختبارات محرك صحة السيو (SEO Health Verification Suite) ===\n\n";

// Test 1: Hex-encoded corrupted Arabic slug decoding
$corrupted_slug = 'cv-d985d8a8d8b1d985d8ac-d985d8a8aad8afd8a6';
$decoded = cdt_seo_decode_corrupted_hex_slug( $corrupted_slug );
assert_test( 'cv-مبرمج-مبتدئ' === $decoded, 'Decodes hex UTF-8 Arabic bytes without percent signs: ' . $decoded );

// Test 2: Clean slug generation
$clean = cdt_seo_generate_clean_slug( $decoded );
assert_test( 'cv-مبرمج-مبتدئ' === $clean, 'Generates valid sanitized slug from Arabic text' );

// Test 3: Path normalization
$norm1 = cdt_seo_normalize_path( '/courses///' );
assert_test( 'courses' === $norm1, 'Trims and collapses multiple slashes in path' );

$norm2 = cdt_seo_normalize_path( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa/' );
assert_test( 'الكورسات' === $norm2, 'Normalizes percent-encoded Arabic URLs to UTF-8 text' );

// Test 4: Arabic text fuzzy matching normalization
$ar_norm = cdt_seo_normalize_arabic_text( 'أكاديمية_الكورساتِ' );
assert_test( 'اكاديميه الكورسات' === $ar_norm, 'Removes tashkeel and normalizes Alef and Taa Marbuta' );

// Test 5: Ignorable static assets & security exploits for 404 logger
assert_test( cdt_seo_is_ignorable_404( '/style.css' ) === true, 'Ignores static .css files in 404 log' );
assert_test( cdt_seo_is_ignorable_404( '/wp-config.php' ) === true, 'Ignores malicious vulnerability probes in 404 log' );
assert_test( cdt_seo_is_ignorable_404( '/old-broken-article/' ) === false, 'Logs legitimate missing content pages' );

// Test 6: Tracking parameters removal list
$params = cdt_seo_get_tracking_params();
assert_test( in_array( 'utm_source', $params, true ) && in_array( 'replytocom', $params, true ) && in_array( 'fbclid', $params, true ), 'Comprehensive tracking params list includes utm, replytocom, fbclid' );

// Test 7: Comment reply link cleaner
$dirty_link = '<a href="/post-title/?replytocom=42#respond">رد</a>';
$clean_link = cdt_seo_clean_comment_reply_link( $dirty_link );
assert_test( false === strpos( $clean_link, 'replytocom' ), 'Strips ?replytocom= from comment reply link' );

// Test 8: Dynamic robots.txt generation
$robots_out = cdt_seo_filter_robots_txt( '', true );
assert_test( false !== strpos( $robots_out, 'Disallow: /wp-admin/' ), 'robots.txt blocks /wp-admin/' );
assert_test( false !== strpos( $robots_out, 'Allow: /wp-admin/admin-ajax.php' ), 'robots.txt allows admin-ajax.php' );
assert_test( false !== strpos( $robots_out, 'Allow: /wp-content/' ), 'robots.txt explicitly allows /wp-content/' );
assert_test( false !== strpos( $robots_out, 'Sitemap: https://code-elta6ur.com' ), 'robots.txt includes valid Sitemap directive' );

echo "\n--- Summary: {$test_passes} Passed, {$test_fails} Failed ---\n";

exit( $test_fails > 0 ? 1 : 0 );
