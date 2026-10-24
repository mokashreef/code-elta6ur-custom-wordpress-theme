<?php
/**
 * إشارات الجودة ومكافحة "تم الزحف ولم يُفهرس"
 * Code Elta6ur Quality Signals, Internal Linking, Rich Schemas & Sitemap Auditor
 *
 * @package CodeElta6ur
 * @subpackage SEO_Health
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fetch Deterministic, Transient-Cached Related Posts
 *
 * @param int $post_id Post ID.
 * @param int $count Number of related posts.
 * @return array WP_Post objects.
 */
function cdt_seo_get_related_posts( $post_id, $count = 3 ) {
	$transient_key = 'cdt_seo_rel_' . $post_id;
	$cached        = get_transient( $transient_key );

	if ( false !== $cached ) {
		return $cached;
	}

	$categories = wp_get_post_categories( $post_id );
	$tags       = wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) );

	$args = array(
		'post__not_in'        => array( $post_id ),
		'posts_per_page'      => absint( $count ),
		'orderby'             => 'date',
		'order'               => 'DESC',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
	);

	// Try finding by tags first, then categories
	if ( ! empty( $tags ) ) {
		$args['tag__in'] = $tags;
	} elseif ( ! empty( $categories ) ) {
		$args['category__in'] = $categories;
	}

	$query = new WP_Query( $args );
	$posts = $query->posts;

	// Cache for 12 hours
	set_transient( $transient_key, $posts, 12 * HOUR_IN_SECONDS );

	return $posts;
}

/**
 * Clear related posts transient upon post update
 */
function cdt_seo_clear_related_transient( $post_id ) {
	delete_transient( 'cdt_seo_rel_' . $post_id );
}
add_action( 'save_post', 'cdt_seo_clear_related_transient' );

/**
 * Output Rich Schema for Tools and Editor (/tools/ and /editor/)
 */
function cdt_seo_output_tool_schema() {
	if ( 'none' !== cdt_seo_detect_active_plugin() ) {
		return;
	}

	// Single Tool Schema
	if ( function_exists( 'cdt_get_current_tool_route' ) && 'tool' === cdt_get_current_tool_route() ) {
		$tool_slug = cdt_get_current_tool_slug();
		$tool      = cdt_get_tool( $tool_slug );

		if ( $tool ) {
			$schema = array(
				'@context'            => 'https://schema.org',
				'@type'               => 'WebApplication',
				'name'                => $tool['name'],
				'alternateName'       => $tool['name_en'],
				'description'         => $tool['description'],
				'applicationCategory' => 'DeveloperApplication',
				'operatingSystem'     => 'All',
				'browserRequirements' => 'Requires JavaScript. Requires HTML5.',
				'url'                 => home_url( '/tools/' . $tool_slug . '/' ),
				'offers'              => array(
					'@type' => 'Offer',
					'price' => '0',
					'priceCurrency' => 'USD',
				),
			);

			echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "</script>\n";
		}
	}

	// Online Code Editor Schema
	if ( function_exists( 'cdt_get_current_editor_route' ) && cdt_get_current_editor_route() ) {
		$editor_schema = array(
			'@context'            => 'https://schema.org',
			'@type'               => 'WebApplication',
			'name'                => 'محرر الأكواد أونلاين — كود التطور',
			'alternateName'       => 'Code Elta6ur Online Code Compiler & IDE',
			'description'         => 'محرر أكواد برمجية ومترجم فوري يدعم أكثر من 18 لغة برمجية سحابياً مجاناً.',
			'applicationCategory' => 'DeveloperApplication',
			'operatingSystem'     => 'All',
			'url'                 => home_url( '/editor/' ),
			'offers'              => array(
				'@type' => 'Offer',
				'price' => '0',
				'priceCurrency' => 'USD',
			),
		);

		echo '<script type="application/ld+json">' . wp_json_encode( $editor_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "</script>\n";
	}
}
add_action( 'wp_head', 'cdt_seo_output_tool_schema', 15 );

/**
 * Fallback Meta Description Generator for Pages & Posts missing meta
 */
function cdt_seo_output_meta_description() {
	if ( is_admin() || is_404() || 'none' !== cdt_seo_detect_active_plugin() ) {
		return;
	}

	$desc = '';

	if ( is_front_page() || is_home() ) {
		$desc = get_bloginfo( 'description' );
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		if ( $post ) {
			if ( ! empty( $post->post_excerpt ) ) {
				$desc = $post->post_excerpt;
			} else {
				$clean = wp_strip_all_tags( $post->post_content );
				$clean = preg_replace( '/\s+/', ' ', $clean );
				$desc  = mb_substr( trim( (string) $clean ), 0, 155, 'UTF-8' );
			}
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term && ! empty( $term->description ) ) {
			$desc = wp_strip_all_tags( $term->description );
		}
	}

	if ( ! empty( $desc ) ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'cdt_seo_output_meta_description', 5 );

/**
 * Suggest internal linking candidates for orphan posts
 *
 * @param int $orphan_id Post ID.
 * @param int $limit Number of suggestions.
 * @return array Suggested posts to link from.
 */
function cdt_seo_suggest_internal_links_for_orphan( $orphan_id, $limit = 3 ) {
	global $wpdb;

	$orphan = get_post( $orphan_id );
	if ( ! $orphan ) {
		return array();
	}

	$cats = wp_get_post_categories( $orphan_id );
	if ( empty( $cats ) ) {
		return array();
	}

	$slug_pattern = '/' . $orphan->post_name . '/';

	// Find published posts in same categories that DO NOT currently link to this orphan post
	$candidates = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT p.ID, p.post_title FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->term_relationships} tr ON (p.ID = tr.object_id)
			WHERE tr.term_taxonomy_id IN (" . implode( ',', array_map( 'absint', $cats ) ) . ")
			AND p.post_status = 'publish' AND p.ID != %d
			AND p.post_content NOT LIKE %s
			GROUP BY p.ID
			ORDER BY p.ID DESC LIMIT %d",
			$orphan_id,
			'%' . $wpdb->esc_like( $slug_pattern ) . '%',
			absint( $limit )
		)
	);

	$suggestions = array();
	foreach ( $candidates as $c ) {
		$suggestions[] = array(
			'id'        => $c->ID,
			'title'     => $c->post_title,
			'url'       => get_permalink( $c->ID ),
			'edit_link' => get_edit_post_link( $c->ID ),
		);
	}

	return $suggestions;
}

/**
 * Audit Sitemap URLs to verify 200 status and lack of noindex
 *
 * @param int $sample_limit Number of URLs to probe.
 * @return array Audit results.
 */
function cdt_seo_audit_sitemap_urls( $sample_limit = 30 ) {
	$sitemap_url = cdt_seo_get_sitemap_url();
	$response    = cdt_seo_safe_remote_get( $sitemap_url, array( 'timeout' => 15 ) );

	if ( is_wp_error( $response ) ) {
		return array(
			'success'     => false,
			'sitemap_url' => $sitemap_url,
			'error'       => 'تعذر جلب خريطة الموقع: ' . $response->get_error_message(),
			'items'       => array(),
		);
	}

	$xml_content = wp_remote_retrieve_body( $response );
	if ( empty( $xml_content ) ) {
		return array(
			'success'     => false,
			'sitemap_url' => $sitemap_url,
			'error'       => 'خريطة الموقع فارغة أو غير قابلة للقراءة.',
			'items'       => array(),
		);
	}

	// Parse <loc> elements
	preg_match_all( '/<loc>([^<]+)<\/loc>/i', $xml_content, $matches );
	$all_urls = isset( $matches[1] ) ? array_slice( $matches[1], 0, absint( $sample_limit ) ) : array();

	$results = array();

	foreach ( $all_urls as $url ) {
		$head = cdt_seo_safe_remote_get(
			$url,
			array(
				'method'  => 'HEAD',
				'timeout' => 8,
			)
		);

		if ( is_wp_error( $head ) ) {
			$results[] = array(
				'url'         => $url,
				'status'      => 'Error',
				'status_code' => 0,
				'has_noindex' => false,
				'is_healthy'  => false,
			);
			continue;
		}

		$code        = wp_remote_retrieve_response_code( $head );
		$x_robots    = wp_remote_retrieve_header( $head, 'x-robots-tag' );
		$has_noindex = ( false !== stripos( (string) $x_robots, 'noindex' ) );
		$is_healthy  = ( 200 === $code && ! $has_noindex );

		$results[] = array(
			'url'         => $url,
			'status'      => $is_healthy ? 'سليم (200 OK)' : 'تحذير (' . $code . ')',
			'status_code' => $code,
			'has_noindex' => $has_noindex,
			'is_healthy'  => $is_healthy,
		);
	}

	return array(
		'success'     => true,
		'sitemap_url' => $sitemap_url,
		'tested_urls' => count( $results ),
		'items'       => $results,
	);
}
