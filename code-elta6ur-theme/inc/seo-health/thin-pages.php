<?php
/**
 * مكافحة الصفحات الرقيقة ومنع أخطاء Soft 404
 * Code Elta6ur Thin Content Audit & Soft 404 Guard
 *
 * @package CodeElta6ur
 * @subpackage SEO_Health
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prevent Soft 404 by returning true 404 or noindex on empty archives/searches
 */
function cdt_seo_prevent_soft_404() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	global $wp_query;

	// Check if this is an archive or search that returned zero posts
	if ( ( is_archive() || is_search() ) && ! have_posts() ) {
		// Set noindex to prevent indexing empty queries
		add_filter(
			'wp_robots',
			function( $robots ) {
				$robots['noindex'] = true;
				$robots['follow']  = true;
				unset( $robots['index'] );
				return $robots;
			},
			9999
		);

		// If taxonomy archive is completely empty, send 404 status
		if ( is_tax() || is_category() || is_tag() ) {
			$term = get_queried_object();
			if ( $term && isset( $term->count ) && 0 === (int) $term->count ) {
				$wp_query->set_404();
				status_header( 404 );
				nocache_headers();
			}
		}
	}
}
add_action( 'template_redirect', 'cdt_seo_prevent_soft_404', 5 );

/**
 * Perform comprehensive thin content and orphan posts audit
 *
 * @param array $options Filter and threshold options.
 * @return array
 */
function cdt_seo_audit_thin_content( $options = array() ) {
	global $wpdb;

	$min_words_post = isset( $options['min_words_post'] ) ? absint( $options['min_words_post'] ) : 600;
	$min_words_tool = isset( $options['min_words_tool'] ) ? absint( $options['min_words_tool'] ) : 300;
	$limit          = isset( $options['limit'] ) ? absint( $options['limit'] ) : 150;

	$posts = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT ID, post_title, post_name, post_type, post_content, post_date, post_modified
			FROM {$wpdb->posts}
			WHERE post_status = 'publish' AND post_type IN ('post', 'page')
			ORDER BY ID DESC LIMIT %d",
			$limit
		)
	);

	$site_host   = wp_parse_url( home_url(), PHP_URL_HOST );
	$audit_items = array();

	foreach ( $posts as $p ) {
		$content = (string) $p->post_content;

		// 1. Calculate word count for Arabic/English UTF-8 text
		$clean_text = trim( (string) wp_strip_all_tags( $content ) );
		$words      = empty( $clean_text ) ? 0 : count( preg_split( '/\s+/u', $clean_text ) );

		// 2. Headings analysis
		$has_h2 = (bool) preg_match( '/<h2[^>]*>/i', $content );

		// 3. Media analysis
		$has_images = (bool) preg_match( '/<img[^>]*>/i', $content ) || has_post_thumbnail( $p->ID );

		// 4. Outgoing internal links
		preg_match_all( '/href=[\'"]([^\'"]+)[\'"]/i', $content, $href_matches );
		$outgoing_count = 0;
		if ( ! empty( $href_matches[1] ) ) {
			foreach ( $href_matches[1] as $u ) {
				$host = wp_parse_url( $u, PHP_URL_HOST );
				if ( empty( $host ) || $host === $site_host ) {
					$outgoing_count++;
				}
			}
		}

		// 5. Incoming internal links (Orphan check)
		$slug_pattern = '/' . $p->post_name . '/';
		$incoming_count = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(ID) FROM {$wpdb->posts}
				WHERE post_status = 'publish' AND ID != %d
				AND post_content LIKE %s",
				$p->ID,
				'%' . $wpdb->esc_like( $slug_pattern ) . '%'
			)
		);

		// Determine threshold based on type or tool template
		$is_tool   = ( 'page' === $p->post_type && false !== strpos( $p->post_name, 'tool' ) );
		$threshold = $is_tool ? $min_words_tool : $min_words_post;

		$issues = array();
		if ( $words < $threshold ) {
			$issues[] = sprintf( 'محتوى رقيق (%d كلمة مقابل حد أدنى %d)', $words, $threshold );
		}
		if ( ! $has_h2 && $words > 100 ) {
			$issues[] = 'يفتقر إلى عناوين H2 الفرعية';
		}
		if ( ! $has_images ) {
			$issues[] = 'لا يحتوي على صور أو وسائط توضيحية';
		}
		if ( 0 === $incoming_count ) {
			$issues[] = 'مقال يتيم (0 روابط واردة)';
		}
		if ( 0 === $outgoing_count && $words > 100 ) {
			$issues[] = 'لا يحتوي على روابط داخلية صادرّة';
		}

		// Calculate recommendation
		$recommendation = 'حالة ممتازة';
		if ( $words < 150 ) {
			$recommendation = 'حسّن المحتوى فوراً أو احذفه مع توجيه 301';
		} elseif ( 0 === $incoming_count ) {
			$recommendation = 'اربطه داخلياً من مقالات وتصنيفات قريبة';
		} elseif ( $words < $threshold ) {
			$recommendation = 'توسّع في الشرح وأضف أمثلة عملية';
		} elseif ( ! $has_h2 || ! $has_images ) {
			$recommendation = 'حسّن التنسيق البصري وأضف وسائط وعناوين H2';
		}

		// Add to report if has at least one issue or requested full list
		if ( ! empty( $issues ) ) {
			$audit_items[] = array(
				'id'             => $p->ID,
				'title'          => $p->post_title,
				'url'            => get_permalink( $p->ID ),
				'type'           => $p->post_type,
				'words'          => $words,
				'threshold'      => $threshold,
				'has_h2'         => $has_h2,
				'has_images'     => $has_images,
				'outgoing_links' => $outgoing_count,
				'incoming_links' => $incoming_count,
				'is_orphan'      => ( 0 === $incoming_count ),
				'issues'         => $issues,
				'recommendation' => $recommendation,
			);
		}
	}

	return $audit_items;
}
