<?php
/**
 * إدارة وتسجيل الأنماط والسكربتات
 * Code Elta6ur Enqueue Scripts & Styles
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add preconnect resource hints for Google Fonts
 */
function cdt_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.googleapis.com',
		);
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'cdt_resource_hints', 10, 2 );

/**
 * Enqueue scripts and styles.
 */
function cdt_scripts() {
	// 1. Google Fonts: Cairo + Outfit
	$fonts_url = 'https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap';
	wp_enqueue_style( 'cdt-google-fonts', $fonts_url, array(), null );

	// 2. Base Theme Style (Design Tokens & Reset)
	wp_enqueue_style( 'cdt-style', get_stylesheet_uri(), array( 'cdt-google-fonts' ), CDT_THEME_VERSION );

	// 3. Main Theme CSS Components
	wp_enqueue_style( 'cdt-main', CDT_THEME_URI . '/assets/css/main.css', array( 'cdt-style' ), CDT_THEME_VERSION );

	// 4. RTL Supplemental Style
	if ( is_rtl() ) {
		wp_enqueue_style( 'cdt-rtl', CDT_THEME_URI . '/assets/css/rtl.css', array( 'cdt-main' ), CDT_THEME_VERSION );
	}

	// 5. Elementor Compatibility CSS Layer
	wp_enqueue_style( 'cdt-elementor-compat', CDT_THEME_URI . '/assets/css/elementor-compat.css', array( 'cdt-main' ), CDT_THEME_VERSION );

	// 6. Navigation and Interactive Scripts
	wp_enqueue_script( 'cdt-navigation', CDT_THEME_URI . '/assets/js/navigation.js', array(), CDT_THEME_VERSION, true );

	// 7. Video Modal for YouTube Player
	wp_enqueue_script( 'cdt-video-modal', CDT_THEME_URI . '/assets/js/video-modal.js', array(), CDT_THEME_VERSION, true );

	// 8. Code Copy to Clipboard (for single posts & code blocks)
	if ( is_singular() ) {
		wp_enqueue_script( 'cdt-code-copy', CDT_THEME_URI . '/assets/js/code-copy.js', array(), CDT_THEME_VERSION, true );
		wp_localize_script(
			'cdt-code-copy',
			'cdtL10n',
			array(
				'copied' => esc_html__( 'تم النسخ!', 'code-elta6ur' ),
				'copy'   => esc_html__( 'نسخ الكود', 'code-elta6ur' ),
			)
		);
	}

	// Threaded comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'cdt_scripts' );
