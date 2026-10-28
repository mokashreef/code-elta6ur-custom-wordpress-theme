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
				'height'      => 60,
				'width'       => 200,
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

/**
 * Exclude course lessons from main blog archive queries (home, blog index)
 *
 * @param WP_Query $query The main query instance.
 */
function cdt_filter_main_blog_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	// Only filter on blog index / home query
	if ( $query->is_home() ) {
		$course_cat_ids = function_exists( 'cdt_get_course_category_ids' ) ? cdt_get_course_category_ids() : array();
		if ( ! empty( $course_cat_ids ) ) {
			$query->set( 'category__not_in', $course_cat_ids );
		}
	}
}
add_action( 'pre_get_posts', 'cdt_filter_main_blog_query' );

/**
 * Automatically assign correct templates for Code Elta6ur core pages & categories
 * ensuring 100% compatibility across Linux/Windows hosting environments without non-ASCII filename dependencies.
 *
 * @param string $template Path to the template.
 * @return string
 */
function cdt_template_redirect_loader( $template ) {
	if ( is_page() ) {
		$post_id   = get_the_ID();
		$raw_slug  = urldecode( get_post_field( 'post_name', $post_id ) );
		$page_slug = sanitize_title( $raw_slug );

		// 1. Blog page (/المدونة/ or ID 40)
		if ( 40 === $post_id || 'المدونة' === $raw_slug || 'blog' === $page_slug ) {
			$found = locate_template( 'template-blog.php' );
			if ( $found ) {
				return $found;
			}
		}

		// 2. Courses hub (/الكورسات-التعليمية/ or ID 832)
		if ( 832 === $post_id || 'الكورسات-التعليمية' === $raw_slug || 'courses' === $page_slug || 'الكورسات-التعليمية' === $page_slug ) {
			$found = locate_template( 'template-courses.php' );
			if ( $found ) {
				return $found;
			}
		}

		// 3. About page (/من-نحن/ or ID 233)
		if ( 233 === $post_id || 'من-نحن' === $raw_slug || 'about' === $page_slug ) {
			$found = locate_template( 'template-about.php' );
			if ( $found ) {
				return $found;
			}
		}

		// 4. Contact page (/اتصل-بنا/ or ID 43)
		if ( 43 === $post_id || 'اتصل-بنا' === $raw_slug || 'contact' === $page_slug ) {
			$found = locate_template( 'template-contact.php' );
			if ( $found ) {
				return $found;
			}
		}

		// 5. Individual Course Syllabus pages (HTML: 822, CSS: 827, Bootstrap: 1197, JS: 1273, React: 1372, C++: 2733)
		$course_page_ids = array( 822, 827, 1197, 1273, 1372, 2733 );
		if ( in_array( $post_id, $course_page_ids, true ) || 0 === strpos( $raw_slug, 'كورس-' ) || 'typescript' === $page_slug || 'كورس-typescript' === $raw_slug ) {
			$found = locate_template( 'template-course-single.php' );
			if ( $found ) {
				return $found;
			}
		}
	}

	// 6. AI Category Archive & Child Categories (/category/الذكاء-الاصطناعي/ or ID 9 or any Child of AI)
	if ( is_category() ) {
		$cat = get_queried_object();
		if ( $cat ) {
			$is_ai = ( 9 === (int) $cat->term_id || 'الذكاء-الاصطناعي' === urldecode( $cat->slug ) );
			if ( ! $is_ai && function_exists( 'cdt_is_ai_category_or_child' ) ) {
				$is_ai = cdt_is_ai_category_or_child( $cat );
			}
			if ( $is_ai ) {
				$found = locate_template( array( 'category-9.php', 'template-ai.php' ) );
				if ( $found ) {
					return $found;
				}
			}
		}
	}

	return $template;
}
add_filter( 'template_include', 'cdt_template_redirect_loader', 99 );


