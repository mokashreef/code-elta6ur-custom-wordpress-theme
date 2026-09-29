<?php
/**
 * تكامل السيو والبيانات المنظمة للموقع
 * Code Elta6ur SEO & Schema.org Integration
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output JSON-LD Schema safely without conflicting with active SEO plugins
 */
function cdt_output_schema_jsonld() {
	// If an active SEO plugin handles schema, do not create duplicate markup
	if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) {
		return;
	}

	$schema = array();

	// 1. Organization & WebSite Schema on Front Page
	if ( is_front_page() ) {
		$schema[] = array(
			'@context' => 'https://schema.org',
			'@type'    => 'EducationalOrganization',
			'name'     => get_bloginfo( 'name' ),
			'url'      => home_url( '/' ),
			'description' => get_bloginfo( 'description' ),
			'logo'     => esc_url( CDT_THEME_URI . '/assets/images/logo.png' ),
			'sameAs'   => array_filter(
				array(
					cdt_get_option( 'cdt_youtube_url' ),
					cdt_get_option( 'cdt_telegram' ),
					cdt_get_option( 'cdt_instagram' ),
					cdt_get_option( 'cdt_facebook' ),
					cdt_get_option( 'cdt_github' ),
				)
			),
		);

		$schema[] = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'WebSite',
			'name'            => get_bloginfo( 'name' ),
			'url'             => home_url( '/' ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			),
		);
	}

	// 2. Article Schema on Single Posts
	if ( is_single() ) {
		$post_id    = get_the_ID();
		$post_thumb = get_the_post_thumbnail_url( $post_id, 'full' );
		$author_id  = get_post_field( 'post_author', $post_id );

		$article_schema = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'TechArticle',
			'headline'         => get_the_title( $post_id ),
			'datePublished'    => get_the_date( 'c', $post_id ),
			'dateModified'     => get_the_modified_date( 'c', $post_id ),
			'author'           => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', $author_id ),
				'url'   => get_author_posts_url( $author_id ),
			),
			'publisher'        => array(
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
				'logo'  => array(
					'@type' => 'ImageObject',
					'url'   => esc_url( CDT_THEME_URI . '/assets/images/logo.png' ),
				),
			),
			'mainEntityOfPage' => array(
				'@type' => 'WebPage',
				'@id'   => get_permalink( $post_id ),
			),
		);

		if ( $post_thumb ) {
			$article_schema['image'] = esc_url( $post_thumb );
		}

		$schema[] = $article_schema;
	}

	// Output Schema Tags
	if ( ! empty( $schema ) ) {
		foreach ( $schema as $item ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "</script>\n";
		}
	}
}
add_action( 'wp_head', 'cdt_output_schema_jsonld', 30 );
