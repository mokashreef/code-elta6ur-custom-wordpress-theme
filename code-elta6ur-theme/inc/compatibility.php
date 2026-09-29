<?php
/**
 * طبقة التوافق مع Elementor والإضافات الأساسية
 * Code Elta6ur Compatibility Layer
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if the current post/page is built using Elementor
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function cdt_is_elementor_page( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	if ( ! $post_id ) {
		return false;
	}
	$edit_mode = get_post_meta( $post_id, '_elementor_edit_mode', true );
	return ( 'builder' === $edit_mode );
}

/**
 * Add custom body classes for Elementor and theme integration
 *
 * @param array $classes Existing body classes.
 * @return array
 */
function cdt_compatibility_body_classes( $classes ) {
	if ( is_singular() && cdt_is_elementor_page() ) {
		$classes[] = 'cdt-has-elementor';
	} else {
		$classes[] = 'cdt-native-page';
	}

	// Ultimate Member Pages
	if ( function_exists( 'um_is_core_page' ) ) {
		if ( um_is_core_page( 'login' ) || um_is_core_page( 'register' ) || um_is_core_page( 'account' ) || um_is_core_page( 'members' ) || um_is_core_page( 'user' ) ) {
			$classes[] = 'cdt-um-core-page';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'cdt_compatibility_body_classes' );

/**
 * Safe Elementor container rendering wrapper
 *
 * @param string $content Content to render.
 * @return string Wrapped content.
 */
function cdt_render_elementor_content_safe( $content ) {
	if ( is_singular() && cdt_is_elementor_page() ) {
		return '<div class="cdt-elementor-wrapper">' . $content . '</div>';
	}
	return $content;
}
add_filter( 'the_content', 'cdt_render_elementor_content_safe', 99 );
