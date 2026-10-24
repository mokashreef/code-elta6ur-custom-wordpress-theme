<?php
/**
 * كشف وإصلاح الروابط المعطوبة المسقط منها علامة الـ % (Hex UTF-8 Corrupted Slugs)
 * Code Elta6ur Corrupted Slug Repair Engine & Rollback System
 *
 * @package CodeElta6ur
 * @subpackage SEO_Health
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Detect and decode hex-encoded Arabic UTF-8 sequences missing % symbols
 * Example: cv-d985d8a8d8b1d985d8ac-d985d8a8aad8afd8a6 -> cv-مبرمج-مبتدئ
 *
 * @param string $slug Raw post name / slug.
 * @return string Decoded Arabic text or original string if no pattern matched.
 */
function cdt_seo_decode_corrupted_hex_slug( $slug ) {
	if ( empty( $slug ) ) {
		return $slug;
	}

	// Match sequences of hex characters that begin with an Arabic lead byte (d7, d8, d9, da, db)
	$pattern = '/(?:d[7-9a-b][0-9a-f]{2,})+/i';

	$decoded = preg_replace_callback(
		$pattern,
		function( $matches ) {
			$hex_str     = strtolower( $matches[0] );
			$len         = strlen( $hex_str );
			$percent_str = '';
			$i           = 0;

			while ( $i < $len ) {
				$byte = substr( $hex_str, $i, 2 );

				if ( in_array( $byte, array( 'd7', 'd8', 'd9', 'da', 'db' ), true ) ) {
					// Valid lead byte
					if ( $i + 2 < $len ) {
						$trail_byte   = substr( $hex_str, $i + 2, 2 );
						$percent_str .= '%' . $byte . '%' . $trail_byte;
						$i           += 4;
					} else {
						$percent_str .= '%' . $byte;
						$i           += 2;
					}
				} elseif ( hexdec( $byte ) >= 0x80 && hexdec( $byte ) <= 0xbf ) {
					// Orphaned trail byte (reconstruct missing lead byte d8)
					$percent_str .= '%d8%' . $byte;
					$i           += 2;
				} else {
					$percent_str .= '%' . $byte;
					$i           += 2;
				}
			}

			$utf8_text = rawurldecode( $percent_str );

			// Verify valid UTF-8
			if ( mb_check_encoding( $utf8_text, 'UTF-8' ) ) {
				return $utf8_text;
			}

			return $matches[0];
		},
		$slug
	);

	return $decoded;
}

/**
 * Generate a clean, validated slug from decoded text
 *
 * @param string $decoded_slug
 * @return string
 */
function cdt_seo_generate_clean_slug( $decoded_slug ) {
	// Remove excessive hyphens and sanitize title
	$clean = sanitize_title( $decoded_slug );
	$clean = preg_replace( '/-+/', '-', $clean );
	return trim( (string) $clean, '-' );
}

/**
 * Scan database for corrupted slugs
 *
 * @return array List of affected posts with repair details.
 */
function cdt_seo_scan_corrupted_slugs() {
	global $wpdb;

	// Query posts that have suspected hex sequences
	$posts = $wpdb->get_results(
		"SELECT ID, post_name, post_title, post_type FROM {$wpdb->posts}
		WHERE post_status IN ('publish', 'future', 'draft', 'private')
		AND post_name REGEXP 'd[7-9a-b][0-9a-f]{2}d[7-9a-b][0-9a-f]{2}'"
	);

	$results = array();

	foreach ( $posts as $post ) {
		$decoded    = cdt_seo_decode_corrupted_hex_slug( $post->post_name );
		$clean_slug = cdt_seo_generate_clean_slug( $decoded );

		if ( $clean_slug !== $post->post_name ) {
			$results[] = array(
				'id'         => $post->ID,
				'title'      => $post->post_title,
				'type'       => $post->post_type,
				'old_slug'   => $post->post_name,
				'decoded'    => $decoded,
				'clean_slug' => $clean_slug,
			);
		}
	}

	return $results;
}

/**
 * Apply or Dry-Run slug repair on a specific post
 *
 * @param int    $post_id Post ID.
 * @param string $new_slug Clean slug to apply.
 * @param bool   $dry_run Dry-run flag (default true).
 * @return array Outcome details.
 */
function cdt_seo_repair_post_slug( $post_id, $new_slug, $dry_run = true ) {
	global $wpdb;

	$post = get_post( $post_id );
	if ( ! $post ) {
		return array( 'success' => false, 'error' => 'المقال غير موجود' );
	}

	$old_slug = $post->post_name;
	if ( $old_slug === $new_slug ) {
		return array( 'success' => true, 'changed' => false );
	}

	if ( $dry_run ) {
		return array(
			'success'  => true,
			'dry_run'  => true,
			'post_id'  => $post_id,
			'old_slug' => $old_slug,
			'new_slug' => $new_slug,
		);
	}

	// 1. Ensure unique slug
	$unique_slug = wp_unique_post_slug( $new_slug, $post_id, $post->post_status, $post->post_type, $post->post_parent );

	// 2. Backup old slug in post meta for safe rollback
	update_post_meta( $post_id, '_cdt_seo_repaired_slug_backup', $old_slug );
	update_post_meta( $post_id, '_cdt_seo_repaired_at', current_time( 'mysql' ) );

	// 3. Update post_name in database
	$updated = $wpdb->update(
		$wpdb->posts,
		array( 'post_name' => $unique_slug ),
		array( 'ID' => $post_id ),
		array( '%s' ),
		array( '%d' )
	);

	clean_post_cache( $post_id );

	// 4. Create 301 redirect in seo_redirects table
	$new_url = get_permalink( $post_id );
	cdt_seo_add_redirect( $old_slug, $new_url, 301, 'slug-fix' );

	// 5. Update internal links in post_content across site
	$site_host = wp_parse_url( home_url(), PHP_URL_HOST );
	$old_path  = '/' . $old_slug . '/';
	$new_path  = '/' . $unique_slug . '/';

	$wpdb->query(
		$wpdb->prepare(
			"UPDATE {$wpdb->posts}
			SET post_content = REPLACE(post_content, %s, %s)
			WHERE post_content LIKE %s",
			$old_path,
			$new_path,
			'%' . $wpdb->esc_like( $old_path ) . '%'
		)
	);

	return array(
		'success'  => false !== $updated,
		'dry_run'  => false,
		'post_id'  => $post_id,
		'old_slug' => $old_slug,
		'new_slug' => $unique_slug,
	);
}

/**
 * Rollback slug repairs using stored metadata backups
 *
 * @return array Rollback report.
 */
function cdt_seo_rollback_slug_repairs() {
	global $wpdb;

	$backed_up = $wpdb->get_results(
		"SELECT post_id, meta_value AS old_slug FROM {$wpdb->postmeta}
		WHERE meta_key = '_cdt_seo_repaired_slug_backup'"
	);

	$results = array();

	foreach ( $backed_up as $record ) {
		$post_id  = absint( $record->post_id );
		$old_slug = $record->old_slug;

		$current_slug = $wpdb->get_var(
			$wpdb->prepare( "SELECT post_name FROM {$wpdb->posts} WHERE ID = %d", $post_id )
		);

		// Restore old slug
		$wpdb->update(
			$wpdb->posts,
			array( 'post_name' => $old_slug ),
			array( 'ID' => $post_id ),
			array( '%s' ),
			array( '%d' )
		);

		clean_post_cache( $post_id );

		// Clean meta
		delete_post_meta( $post_id, '_cdt_seo_repaired_slug_backup' );
		delete_post_meta( $post_id, '_cdt_seo_repaired_at' );

		// Remove redirect
		$norm_old = cdt_seo_normalize_path( $old_slug );
		$wpdb->delete(
			$wpdb->prefix . 'seo_redirects',
			array( 'source_path' => $norm_old ),
			array( '%s' )
		);

		$results[] = array(
			'post_id'       => $post_id,
			'restored_slug' => $old_slug,
			'reverted_from' => $current_slug,
		);
	}

	return $results;
}
