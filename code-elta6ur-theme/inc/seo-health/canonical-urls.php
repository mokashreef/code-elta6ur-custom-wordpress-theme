<?php
/**
 * إدارة وتوحيد الروابط القانونية (Canonical URLs) وإزالة باراميترات التتبع
 * Code Elta6ur Canonical URL Engine & Tracking Cleaner
 *
 * @package CodeElta6ur
 * @subpackage SEO_Health
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * List of tracking and duplicate query parameters to strip from canonicals
 *
 * @return array
 */
function cdt_seo_get_tracking_params() {
	return array(
		'utm_source',
		'utm_medium',
		'utm_campaign',
		'utm_term',
		'utm_content',
		'fbclid',
		'gclid',
		'replytocom',
		'ref',
		'share',
		'amp',
		'print',
		'mc_cid',
		'mc_eid',
	);
}

/**
 * Calculate accurate self-canonical URL for the current request
 *
 * @return string Clean canonical URL.
 */
function cdt_seo_get_canonical_url() {
	global $wp, $wp_query;

	$canonical = '';

	// 1. Front page
	if ( is_front_page() ) {
		$canonical = home_url( '/' );
	}
	// 2. Tools Engine Routes
	elseif ( function_exists( 'cdt_get_current_tool_route' ) && 'hub' === cdt_get_current_tool_route() ) {
		$canonical = home_url( '/tools/' );
	} elseif ( function_exists( 'cdt_get_current_tool_route' ) && 'tool' === cdt_get_current_tool_route() ) {
		$slug      = function_exists( 'cdt_get_current_tool_slug' ) ? cdt_get_current_tool_slug() : '';
		$canonical = home_url( '/tools/' . rawurlencode( $slug ) . '/' );
	}
	// 3. Editor Engine Routes
	elseif ( function_exists( 'cdt_get_current_editor_route' ) && 'hub' === cdt_get_current_editor_route() ) {
		$canonical = home_url( '/editor/' );
	} elseif ( function_exists( 'cdt_get_current_editor_route' ) && 'lang' === cdt_get_current_editor_route() ) {
		$lang      = function_exists( 'cdt_get_current_editor_lang' ) ? cdt_get_current_editor_lang() : '';
		$canonical = home_url( '/editor/' . rawurlencode( $lang ) . '/' );
	}
	// 4. Singular post/page
	elseif ( is_singular() ) {
		$post_id   = get_the_ID();
		$canonical = get_permalink( $post_id );

		// Handle multipage posts (<!--nextpage-->)
		$page = get_query_var( 'page' );
		if ( $page > 1 ) {
			$canonical = trailingslashit( $canonical ) . user_trailingslashit( $page, 'single_paged' );
		}
	}
	// 5. Taxonomy archive (Category, Tag, Series)
	elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term && ! is_wp_error( $term ) ) {
			$term_link = get_term_link( $term );
			if ( ! is_wp_error( $term_link ) ) {
				$canonical = $term_link;
			}
		}
	}
	// 6. Post Type Archive
	elseif ( is_post_type_archive() ) {
		$post_type = get_query_var( 'post_type' );
		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}
		$canonical = get_post_type_archive_link( $post_type );
	}
	// 7. Author archive
	elseif ( is_author() ) {
		$author_id = get_query_var( 'author' );
		$canonical = get_author_posts_url( $author_id );
	}
	// 8. General fallback via $wp
	else {
		$canonical = home_url( add_query_arg( array(), $wp->request ) );
	}

	// Handle pagination (paged > 1) to ensure self-canonical on each page
	$paged = get_query_var( 'paged' );
	if ( $paged > 1 && ! is_singular() ) {
		$canonical = get_pagenum_link( $paged );
	}

	// Clean tracking parameters from canonical URL
	$tracking_params = cdt_seo_get_tracking_params();
	$canonical       = remove_query_arg( $tracking_params, $canonical );

	// Force consistent scheme based on home_url
	$home_scheme = wp_parse_url( home_url(), PHP_URL_SCHEME );
	if ( 'https' === $home_scheme ) {
		$canonical = set_url_scheme( $canonical, 'https' );
	}

	// Ensure consistent trailing slash matching permalink_structure
	$structure = get_option( 'permalink_structure' );
	if ( ! empty( $structure ) && '/' === substr( $structure, -1 ) ) {
		$parsed_path = wp_parse_url( $canonical, PHP_URL_PATH );
		if ( ! empty( $parsed_path ) && false === strpos( basename( $parsed_path ), '.' ) ) {
			$canonical = trailingslashit( $canonical );
		}
	}

	return apply_filters( 'cdt_seo_canonical_url', $canonical );
}

/**
 * Output Canonical Link in wp_head safely when no third-party SEO plugin is handling it
 */
function cdt_seo_output_canonical_tag() {
	if ( is_404() || is_admin() ) {
		return;
	}

	// If third-party SEO plugin is active, it handles the output
	if ( 'none' !== cdt_seo_detect_active_plugin() ) {
		return;
	}

	$canonical = cdt_seo_get_canonical_url();
	if ( ! empty( $canonical ) ) {
		echo '<link rel="canonical" href="' . esc_url( $canonical ) . '" />' . "\n";
	}
}

/**
 * Remove default WordPress core canonical and hook our normalized engine
 */
function cdt_seo_setup_canonical_hooks() {
	// Remove core canonical to prevent duplicates
	remove_action( 'wp_head', 'rel_canonical' );

	// Hook custom canonical output
	add_action( 'wp_head', 'cdt_seo_output_canonical_tag', 2 );
}
add_action( 'init', 'cdt_seo_setup_canonical_hooks' );

/**
 * Filter Yoast SEO Canonical
 */
add_filter(
	'wpseo_canonical',
	function( $canonical ) {
		$clean = remove_query_arg( cdt_seo_get_tracking_params(), $canonical );
		return $clean;
	},
	999
);

/**
 * Filter Rank Math Canonical
 */
add_filter(
	'rank_math/canonical',
	function( $canonical ) {
		$clean = remove_query_arg( cdt_seo_get_tracking_params(), $canonical );
		return $clean;
	},
	999
);

/**
 * 301 Redirect Media Attachment Pages to parent post or attachment file
 */
function cdt_seo_redirect_attachments() {
	if ( ! is_attachment() || is_admin() ) {
		return;
	}

	global $post;
	if ( ! empty( $post->post_parent ) && $post->post_parent > 0 ) {
		$parent_url = get_permalink( $post->post_parent );
		if ( $parent_url ) {
			wp_safe_redirect( $parent_url, 301 );
			exit;
		}
	}

	// If orphan attachment, redirect to media file URL or home
	$file_url = wp_get_attachment_url( $post->ID );
	if ( $file_url ) {
		wp_safe_redirect( $file_url, 301 );
		exit;
	}

	wp_safe_redirect( home_url( '/' ), 301 );
	exit;
}
add_action( 'template_redirect', 'cdt_seo_redirect_attachments', 5 );

/**
 * 301 Redirect replytocom and empty search queries to clean URLs
 */
function cdt_seo_clean_query_redirects() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	// 1. Redirect ?replytocom=ID to comment anchor
	if ( isset( $_GET['replytocom'] ) ) {
		$comment_id = absint( $_GET['replytocom'] );
		$clean_url  = remove_query_arg( 'replytocom' );
		if ( $comment_id > 0 ) {
			$clean_url .= '#comment-' . $comment_id;
		}
		wp_safe_redirect( $clean_url, 301 );
		exit;
	}

	// 2. Redirect empty ?s= or ?s="" to homepage
	if ( isset( $_GET['s'] ) && '' === trim( sanitize_text_field( wp_unslash( $_GET['s'] ) ) ) ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'cdt_seo_clean_query_redirects', 8 );

/**
 * Disable replytocom in comment reply links to eliminate thousands of crawler waste URLs
 *
 * @param string $link The comment reply link.
 * @return string
 */
function cdt_seo_clean_comment_reply_link( $link ) {
	if ( empty( $link ) ) {
		return $link;
	}

	// Remove replytocom parameter and replace with javascript onclick or data attribute
	$cleaned = preg_replace( '/[?&]replytocom=\d+/', '', $link );
	return $cleaned;
}
add_filter( 'comment_reply_link', 'cdt_seo_clean_comment_reply_link' );
