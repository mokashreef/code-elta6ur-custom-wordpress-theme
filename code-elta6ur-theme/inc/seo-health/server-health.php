<?php
/**
 * مراقبة استقرار الخادم وفحص أسباب أخطاء 5xx و 403
 * Code Elta6ur Server Health & Googlebot Simulation
 *
 * @package CodeElta6ur
 * @subpackage SEO_Health
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Safe Remote Request Wrapper with Try/Catch & Error Resilience
 *
 * @param string $url Target endpoint.
 * @param array  $args Request arguments.
 * @return array|WP_Error
 */
function cdt_seo_safe_remote_get( $url, $args = array() ) {
	$defaults = array(
		'timeout'     => 10,
		'redirection' => 5,
		'httpversion' => '1.1',
		'user-agent'  => 'CodeElta6ur-HealthCheck/1.0',
		'sslverify'   => true,
	);

	$parsed_args = wp_parse_args( $args, $defaults );

	try {
		$response = wp_remote_get( $url, $parsed_args );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		return $response;
	} catch ( Throwable $e ) {
		return new WP_Error( 'http_failure', $e->getMessage() );
	}
}

/**
 * Send optimized HTTP Cache-Control headers for public vs private views
 */
function cdt_seo_send_cache_headers() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	// 1. Private and Auth Views (Never Cache)
	if ( is_user_logged_in() || cdt_seo_is_account_request() || is_404() ) {
		nocache_headers();
		return;
	}

	// 2. Public Static Content (Safe Cache Headers)
	if ( ! headers_sent() && is_singular() || is_front_page() || is_home() || is_archive() ) {
		header( 'Cache-Control: public, max-age=1800, stale-while-revalidate=86400' );
	}
}
add_action( 'send_headers', 'cdt_seo_send_cache_headers' );

/**
 * Simulate Googlebot Crawl Request on a given site URL to test accessibility
 *
 * @param string $url URL to probe (defaults to home_url).
 * @return array Test results.
 */
function cdt_seo_simulate_googlebot_request( $url = '' ) {
	if ( empty( $url ) ) {
		$url = home_url( '/' );
	}

	$googlebot_ua = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';

	$response = cdt_seo_safe_remote_get(
		$url,
		array(
			'user-agent' => $googlebot_ua,
			'timeout'    => 12,
		)
	);

	if ( is_wp_error( $response ) ) {
		return array(
			'success'     => false,
			'status_code' => 0,
			'error'       => $response->get_error_message(),
			'url'         => $url,
		);
	}

	$code    = wp_remote_retrieve_response_code( $response );
	$headers = wp_remote_retrieve_headers( $response );
	$body    = wp_remote_retrieve_body( $response );

	$is_blocked = ( 403 === $code || 500 === $code || 502 === $code || 503 === $code );
	$has_login  = ( false !== strpos( (string) $body, 'wp-login.php' ) && ! cdt_seo_is_account_request() );

	return array(
		'success'     => ! $is_blocked && ! $has_login && ( 200 === $code || 301 === $code ),
		'status_code' => $code,
		'is_blocked'  => $is_blocked,
		'has_login'   => $has_login,
		'url'         => $url,
		'server'      => isset( $headers['server'] ) ? $headers['server'] : 'Unknown',
	);
}

/**
 * Read the last N lines of debug.log securely
 *
 * @param int $lines Number of lines to retrieve.
 * @return array Lines of logs or error message.
 */
function cdt_seo_get_debug_log_tail( $lines = 50 ) {
	$log_file = WP_CONTENT_DIR . '/debug.log';

	if ( ! file_exists( $log_file ) || ! is_readable( $log_file ) ) {
		return array(
			'exists' => false,
			'path'   => $log_file,
			'lines'  => array(),
		);
	}

	$file_handle = fopen( $log_file, 'r' );
	if ( ! $file_handle ) {
		return array(
			'exists' => true,
			'path'   => $log_file,
			'lines'  => array( 'تعذر فتح ملف السجل للقراءة.' ),
		);
	}

	$buffer    = array();
	$max_lines = absint( $lines );

	// Read lines efficiently from end of file
	$pos = -2;
	$line_count = 0;
	$current_line = '';

	fseek( $file_handle, 0, SEEK_END );
	$file_size = ftell( $file_handle );

	while ( $file_size > 0 && abs( $pos ) <= $file_size && $line_count < $max_lines ) {
		fseek( $file_handle, $pos, SEEK_END );
		$char = fgetc( $file_handle );

		if ( "\n" === $char ) {
			if ( ! empty( $current_line ) ) {
				$buffer[] = strrev( $current_line );
				$current_line = '';
				$line_count++;
			}
		} else {
			$current_line .= $char;
		}
		$pos--;
	}

	if ( ! empty( $current_line ) && $line_count < $max_lines ) {
		$buffer[] = strrev( $current_line );
	}

	fclose( $file_handle );

	return array(
		'exists' => true,
		'path'   => $log_file,
		'lines'  => array_reverse( $buffer ),
	);
}
