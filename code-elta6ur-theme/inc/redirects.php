<?php
/**
 * توجيه المسارات ومعالجة الروابط المعطوبة 301
 * Code Elta6ur 301 Redirects Handler
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle 301 redirects for legacy, broken, or alternate paths
 */
function cdt_handle_redirects() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	if ( empty( $request_uri ) ) {
		return;
	}

	// Parse request path and decode Arabic characters
	$path = wp_parse_url( $request_uri, PHP_URL_PATH );
	if ( ! $path ) {
		return;
	}

	$decoded_path = rawurldecode( $path );
	$trimmed_path = trim( $decoded_path, '/' );

	// Redirect Mapping Dictionary
	$redirect_map = array(
		'كورسات-التعليمية' => '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/',
		'courses'          => '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/',
		'privacy-policy'   => '/%d8%b3%d9%8a%d8%a7%d8%b3%d8%a9-%d8%a7%d9%84%d8%ae%d8%b5%d9%88%d8%b5%d9%8a%d8%a9/',
		'terms-of-use'     => '/%d8%b4%d8%b1%d9%88%d8%b7-%d8%a7%d9%84%d8%a7%d8%b3%d8%aa%d8%ae%d8%af%d8%a7%d9%85/',
		'terms'            => '/%d8%b4%d8%b1%d9%88%d8%b7-%d8%a7%d9%84%d8%a7%d8%b3%d8%aa%d8%ae%d8%af%d8%a7%d9%85/',
		'about'            => '/%d9%85%d9%86-%d9%86%d8%ad%d9%86/',
		'contact'          => '/%d8%a7%d8%aa%d8%b5%d9%84-%d8%a8%d9%86%d8%a7/',
		'blog'             => '/%d8%a7%d9%84%d9%85%d8%af%d9%88%d9%86%d8%a9/',
		'tool'             => '/tools/',
		'الأدوات'          => '/tools/',
		'compiler'         => '/editor/',
		'المحرر'           => '/editor/',
	);

	if ( array_key_exists( $trimmed_path, $redirect_map ) ) {
		$target_url = home_url( $redirect_map[ $trimmed_path ] );
		wp_safe_redirect( $target_url, 301 );
		exit;
	}
}
add_action( 'template_redirect', 'cdt_handle_redirects', 1 );
