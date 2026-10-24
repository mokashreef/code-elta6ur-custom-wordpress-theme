<?php
/**
 * تسجيل أخطاء 404 والاقتراح الذكي للروابط البديلة
 * Code Elta6ur 404 Logger & Smart Suggestion Engine
 *
 * @package CodeElta6ur
 * @subpackage SEO_Health
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Identify if requested path belongs to static files or bot vulnerability probes
 *
 * @param string $path Requested path.
 * @return bool True if ignorable.
 */
function cdt_seo_is_ignorable_404( $path ) {
	$path = strtolower( trim( $path ) );

	// 1. Static asset extensions
	$static_exts = array(
		'css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'svg',
		'webp', 'avif', 'woff', 'woff2', 'ttf', 'eot', 'map',
		'mp4', 'mp3', 'zip', 'gz', 'rar', 'pdf', 'txt', 'xml',
	);

	$ext = pathinfo( $path, PATHINFO_EXTENSION );
	if ( in_array( $ext, $static_exts, true ) ) {
		return true;
	}

	// 2. Known malicious security probes & exploits
	$probe_patterns = array(
		'wp-config',
		'.env',
		'xmlrpc.php',
		'phpmyadmin',
		'eval-stdin',
		'.git',
		'.svn',
		'autoload.php',
		'wlwmanifest.xml',
		'vendor/',
		'config.json',
	);

	foreach ( $probe_patterns as $pattern ) {
		if ( false !== strpos( $path, $pattern ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Detect User-Agent classification for 404 logging
 *
 * @return string 'googlebot' | 'bingbot' | 'other_crawler' | 'browser'
 */
function cdt_seo_get_ua_classification() {
	$ua = isset( $_SERVER['HTTP_USER_AGENT'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) ) : '';

	if ( false !== strpos( $ua, 'googlebot' ) ) {
		return 'googlebot';
	}
	if ( false !== strpos( $ua, 'bingbot' ) ) {
		return 'bingbot';
	}
	if ( false !== strpos( $ua, 'bot' ) || false !== strpos( $ua, 'crawler' ) || false !== strpos( $ua, 'spider' ) ) {
		return 'other_crawler';
	}

	return 'browser';
}

/**
 * Log 404 errors with UPSERT aggregation
 */
function cdt_seo_log_404_request() {
	if ( ! is_404() || is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	if ( empty( $request_uri ) ) {
		return;
	}

	$path = wp_parse_url( $request_uri, PHP_URL_PATH );
	if ( empty( $path ) || cdt_seo_is_ignorable_404( $path ) ) {
		return;
	}

	$norm_path = cdt_seo_normalize_path( $path );
	if ( empty( $norm_path ) ) {
		return;
	}

	// Extract referrer host safely without storing personal identifiers or full referrer URLs
	$referrer_host = '';
	if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
		$ref_parsed    = wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) );
		$referrer_host = isset( $ref_parsed['host'] ) ? sanitize_text_field( $ref_parsed['host'] ) : '';
	}

	$ua_type = cdt_seo_get_ua_classification();

	global $wpdb;
	$table_name = $wpdb->prefix . 'seo_404_log';

	// UPSERT with query aggregation
	$wpdb->query(
		$wpdb->prepare(
			"INSERT INTO {$table_name} (normalized_path, hits, last_seen, referrer_host, user_agent_type)
			VALUES (%s, 1, %s, %s, %s)
			ON DUPLICATE KEY UPDATE
				hits = hits + 1,
				last_seen = VALUES(last_seen),
				referrer_host = IF(referrer_host = '', VALUES(referrer_host), referrer_host),
				user_agent_type = VALUES(user_agent_type)",
			$norm_path,
			current_time( 'mysql' ),
			$referrer_host,
			$ua_type
		)
	);
}
add_action( 'template_redirect', 'cdt_seo_log_404_request', 20 );

/**
 * Arabic String Normalization for accurate fuzzy matching
 *
 * @param string $string Arabic text.
 * @return string Normalized text.
 */
function cdt_seo_normalize_arabic_text( $string ) {
	$string = rawurldecode( $string );

	// Remove tashkeel / diacritics
	$tashkeel = array( 'َ', 'ً', 'ُ', 'ٌ', 'ِ', 'ٍ', 'ْ', 'ّ' );
	$string   = str_replace( $tashkeel, '', $string );

	// Normalize Alef variations
	$alef_patterns = array( 'أ', 'إ', 'آ', 'ٱ' );
	$string        = str_replace( $alef_patterns, 'ا', $string );

	// Normalize Taa Marbuta and Haa
	$string = str_replace( 'ة', 'ه', $string );

	// Normalize Yaa
	$string = str_replace( array( 'ى', 'ئ' ), 'ي', $string );

	// Replace dashes, underscores with single space
	$string = preg_replace( '/[-_+]+/', ' ', $string );

	return trim( mb_strtolower( (string) $string, 'UTF-8' ) );
}

/**
 * Smart Slug Suggestion Algorithm using Levenshtein & similar_text
 *
 * @param string $broken_path The missing requested slug.
 * @param int    $limit Max suggestions to return.
 * @return array Suggested items with similarity score and URL.
 */
function cdt_seo_find_smart_404_suggestions( $broken_path, $limit = 3 ) {
	global $wpdb;

	$broken_clean = cdt_seo_normalize_arabic_text( $broken_path );
	if ( mb_strlen( $broken_clean, 'UTF-8' ) < 3 ) {
		return array();
	}

	// Fetch recent published posts and pages
	$candidates = $wpdb->get_results(
		"SELECT ID, post_title, post_name, post_type FROM {$wpdb->posts}
		WHERE post_status = 'publish' AND post_type IN ('post', 'page')
		ORDER BY post_date DESC LIMIT 300"
	);

	$suggestions = array();

	foreach ( $candidates as $candidate ) {
		$target_slug_clean  = cdt_seo_normalize_arabic_text( $candidate->post_name );
		$target_title_clean = cdt_seo_normalize_arabic_text( $candidate->post_title );

		// Calculate similarity percentage against slug
		similar_text( $broken_clean, $target_slug_clean, $slug_percent );

		// Calculate similarity percentage against title
		similar_text( $broken_clean, $target_title_clean, $title_percent );

		$best_score = max( $slug_percent, $title_percent );

		if ( $best_score > 45 ) {
			$suggestions[] = array(
				'id'         => $candidate->ID,
				'title'      => $candidate->post_title,
				'url'        => get_permalink( $candidate->ID ),
				'score'      => round( $best_score, 1 ),
				'slug'       => $candidate->post_name,
			);
		}
	}

	// Sort by similarity score descending
	usort(
		$suggestions,
		function( $a, $b ) {
			return $b['score'] <=> $a['score'];
		}
	);

	return array_slice( $suggestions, 0, $limit );
}

/**
 * Check if 404 has ultra-high confidence match (>90%) for optional auto-redirect
 */
function cdt_seo_check_404_smart_redirect() {
	if ( ! is_404() || is_admin() ) {
		return;
	}

	$options = get_option( 'cdt_seo_options', array() );
	if ( empty( $options['auto_redirect_404_high_confidence'] ) ) {
		return;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$path        = wp_parse_url( $request_uri, PHP_URL_PATH );
	$norm_path   = cdt_seo_normalize_path( $path );

	if ( empty( $norm_path ) || cdt_seo_is_ignorable_404( $norm_path ) ) {
		return;
	}

	$suggestions = cdt_seo_find_smart_404_suggestions( $norm_path, 1 );
	if ( ! empty( $suggestions[0] ) && $suggestions[0]['score'] >= 90.0 ) {
		// Log 301 redirect into redirects table
		cdt_seo_add_redirect( $norm_path, $suggestions[0]['url'], 301, 'auto-smart' );
		wp_safe_redirect( $suggestions[0]['url'], 301 );
		exit;
	}
}
add_action( 'template_redirect', 'cdt_seo_check_404_smart_redirect', 15 );
