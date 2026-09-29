<?php
/**
 * إعدادات وخصائص القالب الأساسية
 * Code Elta6ur Theme Setup
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'cdt_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function cdt_theme_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'code-elta6ur', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 800, 450, true ); // 16:9 default ratio
		add_image_size( 'cdt-card-thumb', 600, 338, true );
		add_image_size( 'cdt-single-hero', 1200, 675, true );

		// Register Navigation Menus
		register_nav_menus(
			array(
				'primary-menu'     => esc_html__( 'القائمة الرئيسية (Header Navigation)', 'code-elta6ur' ),
				'footer-menu'      => esc_html__( 'روابط الفوتر الهامة (Footer Links)', 'code-elta6ur' ),
				'footer-policies'  => esc_html__( 'السياسات القانونية (Legal Policies)', 'code-elta6ur' ),
			)
		);

		// Switch default core markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Support Custom Logo
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 118,
				'width'       => 118,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		// Gutenberg Block Editor Supports
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/editor-style.css' );
	}
endif;
add_action( 'after_setup_theme', 'cdt_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function cdt_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'cdt_content_width', 1240 );
}
add_action( 'after_setup_theme', 'cdt_content_width', 0 );
