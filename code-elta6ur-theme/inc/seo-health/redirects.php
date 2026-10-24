<?php
/**
 * إدارة التحويلات 301 و 410 وحماية سلاسل التوجيه
 * Code Elta6ur Redirects Engine (Loops & Chains Prevention)
 *
 * @package CodeElta6ur
 * @subpackage SEO_Health
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalize URL paths for consistent storage and matching
 *
 * @param string $path URL or relative path.
 * @return string Normalized path without leading/trailing slashes.
 */
function cdt_seo_normalize_path( $path ) {
	if ( empty( $path ) ) {
		return '';
	}

	// Extract path component if full URL provided
	$parsed_path = wp_parse_url( $path, PHP_URL_PATH );
	if ( false !== $parsed_path && null !== $parsed_path ) {
		$path = $parsed_path;
	}

	// Decode percent-encoding (handle Arabic UTF-8)
	$decoded = rawurldecode( $path );

	// Remove multiple slashes and trim
	$cleaned = preg_replace( '#/+#', '/', $decoded );
	$trimmed = trim( (string) $cleaned, '/' );

	return mb_strtolower( $trimmed, 'UTF-8' );
}

/**
 * Find active redirect for a given path
 *
 * @param string $path Requested path.
 * @return object|null Row object or null.
 */
function cdt_seo_get_redirect_for_path( $path ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'seo_redirects';

	$normalized = cdt_seo_normalize_path( $path );
	if ( empty( $normalized ) ) {
		return null;
	}

	// First query DB table
	$redirect = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$table_name} WHERE source_path = %s LIMIT 1",
			$normalized
		)
	);

	if ( $redirect ) {
		return $redirect;
	}

	// Fallback to theme static legacy redirects if not in DB yet
	$static_map = array(
		'courses'          => home_url( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/' ),
		'كورسات-التعليمية' => home_url( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/' ),
		'privacy-policy'   => home_url( '/%d8%b3%d9%8a%d8%a7%d8%b3%d8%a9-%d8%a7%d9%84%d8%ae%d8%b5%d9%88%d8%b5%d9%8a%d8%a9/' ),
		'terms-of-use'     => home_url( '/%d8%b4%d8%b1%d9%88%d8%b7-%d8%a7%d9%84%d8%a7%d8%b3%d8%aa%d8%ae%d8%af%d8%a7%d9%85/' ),
		'terms'            => home_url( '/%d8%b4%d8%b1%d9%88%d8%b7-%d8%a7%d9%84%d8%a7%d8%b3%d8%aa%d8%ae%d8%af%d8%a7%d9%85/' ),
		'about'            => home_url( '/%d9%85%d9%86-%d9%86%d8%ad%d9%86/' ),
		'contact'          => home_url( '/%d8%a7%d8%aa%d8%b5%d9%84-%d8%a8%d9%86%d8%a7/' ),
		'blog'             => home_url( '/%d8%a7%d9%84%d9%85%d8%af%d9%88%d9%86%d8%a9/' ),
		'tool'             => home_url( '/tools/' ),
		'الأدوات'          => home_url( '/tools/' ),
		'compiler'         => home_url( '/editor/' ),
		'المحرر'           => home_url( '/editor/' ),
	);

	if ( isset( $static_map[ $normalized ] ) ) {
		return (object) array(
			'id'          => 0,
			'source_path' => $normalized,
			'target_url'  => $static_map[ $normalized ],
			'status_code' => 301,
			'hits'        => 0,
			'source_type' => 'static',
		);
	}

	return null;
}

/**
 * Resolve potential redirect chains and loops to return final destination directly
 *
 * @param string $initial_target Destination URL.
 * @param int    $depth Current recursion depth.
 * @param array  $seen_paths Visited paths for cycle detection.
 * @return string Final destination URL.
 */
function cdt_seo_resolve_final_target( $initial_target, $depth = 0, $seen_paths = array() ) {
	if ( $depth > 5 ) {
		return $initial_target; // Prevent deep recursion
	}

	$target_path = cdt_seo_normalize_path( $initial_target );
	if ( in_array( $target_path, $seen_paths, true ) ) {
		// Loop detected! Break out and return current target
		return $initial_target;
	}

	$seen_paths[] = $target_path;
	$next_hop     = cdt_seo_get_redirect_for_path( $target_path );

	if ( $next_hop && ! empty( $next_hop->target_url ) && 410 !== (int) $next_hop->status_code ) {
		return cdt_seo_resolve_final_target( $next_hop->target_url, $depth + 1, $seen_paths );
	}

	return $initial_target;
}

/**
 * Add or update a redirect record with automatic chain resolution
 *
 * @param string $source Source path.
 * @param string $target Target URL.
 * @param int    $code HTTP status code (301, 302, 410).
 * @param string $type Source type (manual, auto, slug-fix).
 * @return int|false Insert ID or affected rows.
 */
function cdt_seo_add_redirect( $source, $target, $code = 301, $type = 'manual' ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'seo_redirects';

	$norm_source = cdt_seo_normalize_path( $source );
	if ( empty( $norm_source ) ) {
		return false;
	}

	$code = in_array( (int) $code, array( 301, 302, 410 ), true ) ? (int) $code : 301;

	// Resolve chain if not a 410 Gone
	$final_target = ( 410 === $code ) ? '' : cdt_seo_resolve_final_target( $target );

	// Avoid self-redirect loop
	if ( 410 !== $code && cdt_seo_normalize_path( $final_target ) === $norm_source ) {
		return false;
	}

	$existing = $wpdb->get_var(
		$wpdb->prepare( "SELECT id FROM {$table_name} WHERE source_path = %s", $norm_source )
	);

	if ( $existing ) {
		return $wpdb->update(
			$table_name,
			array(
				'target_url'  => $final_target,
				'status_code' => $code,
				'source_type' => sanitize_text_field( $type ),
			),
			array( 'id' => $existing ),
			array( '%s', '%d', '%s' ),
			array( '%d' )
		);
	}

	return $wpdb->insert(
		$table_name,
		array(
			'source_path' => $norm_source,
			'target_url'  => $final_target,
			'status_code' => $code,
			'hits'        => 0,
			'created_at'  => current_time( 'mysql' ),
			'source_type' => sanitize_text_field( $type ),
		),
		array( '%s', '%s', '%d', '%d', '%s', '%s' )
	);
}

/**
 * Delete a redirect record by ID
 *
 * @param int $id Redirect ID.
 * @return bool
 */
function cdt_seo_delete_redirect( $id ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'seo_redirects';

	$deleted = $wpdb->delete(
		$table_name,
		array( 'id' => absint( $id ) ),
		array( '%d' )
	);

	return false !== $deleted;
}

/**
 * Execute 301 / 410 Redirects early in template_redirect
 */
function cdt_seo_execute_redirects() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	if ( empty( $request_uri ) ) {
		return;
	}

	$path = wp_parse_url( $request_uri, PHP_URL_PATH );
	if ( empty( $path ) || '/' === $path ) {
		return;
	}

	$norm_path = cdt_seo_normalize_path( $path );
	$redirect  = cdt_seo_get_redirect_for_path( $norm_path );

	if ( ! $redirect ) {
		return;
	}

	// Increment hits asynchronously in DB if record has real ID
	if ( ! empty( $redirect->id ) ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'seo_redirects';
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$table_name} SET hits = hits + 1 WHERE id = %d",
				$redirect->id
			)
		);
	}

	// 1. Handle HTTP 410 Gone (Permanently Deleted)
	if ( 410 === (int) $redirect->status_code ) {
		status_header( 410 );
		nocache_headers();
		wp_die(
			'<h1>410 - المحتوى محذوف نهائياً</h1><p>تم حذف هذه الصفحة نهائياً ولم تعد متاحة في منصة كود التطور.</p><p><a href="' . esc_url( home_url( '/' ) ) . '">العودة للرئيسية</a></p>',
			'410 Gone',
			array( 'response' => 410 )
		);
		exit;
	}

	// 2. Handle HTTP 301 / 302 Redirects
	$target = $redirect->target_url;
	if ( ! empty( $target ) ) {
		// Resolve chain directly
		$resolved_target = cdt_seo_resolve_final_target( $target );

		// Preserve query parameters if present
		$query = wp_parse_url( $request_uri, PHP_URL_QUERY );
		if ( ! empty( $query ) ) {
			$separator       = ( false !== strpos( $resolved_target, '?' ) ) ? '&' : '?';
			$resolved_target .= $separator . $query;
		}

		$status = in_array( (int) $redirect->status_code, array( 301, 302 ), true ) ? (int) $redirect->status_code : 301;
		wp_safe_redirect( $resolved_target, $status );
		exit;
	}
}
add_action( 'template_redirect', 'cdt_seo_execute_redirects', 1 );

/**
 * Scan published posts for broken internal links
 *
 * @param int $limit Number of posts to inspect.
 * @return array List of broken internal links detected.
 */
function cdt_seo_scan_broken_internal_links( $limit = 100 ) {
	global $wpdb;

	$posts = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT ID, post_title, post_content FROM {$wpdb->posts}
			WHERE post_status = 'publish' AND post_type IN ('post', 'page')
			ORDER BY ID DESC LIMIT %d",
			absint( $limit )
		)
	);

	$site_host    = wp_parse_url( home_url(), PHP_URL_HOST );
	$broken_links = array();

	foreach ( $posts as $post ) {
		if ( empty( $post->post_content ) ) {
			continue;
		}

		// Find all href attributes
		preg_match_all( '/href=[\'"]([^\'"]+)[\'"]/i', $post->post_content, $matches );
		if ( empty( $matches[1] ) ) {
			continue;
		}

		foreach ( $matches[1] as $href ) {
			$href_host = wp_parse_url( $href, PHP_URL_HOST );
			$is_internal = empty( $href_host ) || $href_host === $site_host;

			if ( ! $is_internal || 0 === strpos( $href, '#' ) || 0 === strpos( $href, 'mailto:' ) || 0 === strpos( $href, 'tel:' ) ) {
				continue;
			}

			$path      = wp_parse_url( $href, PHP_URL_PATH );
			$norm_path = cdt_seo_normalize_path( $path );

			if ( empty( $norm_path ) ) {
				continue;
			}

			// Check if path matches an existing post or page
			$post_found = get_page_by_path( $norm_path, OBJECT, array( 'post', 'page' ) );
			if ( ! $post_found ) {
				// Check redirect table
				$redirect = cdt_seo_get_redirect_for_path( $norm_path );

				$broken_links[] = array(
					'post_id'    => $post->ID,
					'post_title' => $post->post_title,
					'href'       => $href,
					'path'       => $norm_path,
					'suggestion' => $redirect ? $redirect->target_url : home_url( '/' . $norm_path . '/' ),
					'has_301'    => (bool) $redirect,
				);
			}
		}
	}

	return $broken_links;
}
