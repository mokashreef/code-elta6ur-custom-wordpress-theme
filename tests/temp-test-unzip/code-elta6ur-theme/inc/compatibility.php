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
 * Register Theme Support for Elementor
 */
function cdt_register_builder_support() {
	add_theme_support( 'elementor' );
}
add_action( 'after_setup_theme', 'cdt_register_builder_support' );

/**
 * Strip Elementor builder hijacking from native Code Elta6ur pages
 */
function cdt_strip_elementor_from_native_templates() {
	if ( ! is_page() ) {
		return;
	}

	$post = get_queried_object();
	if ( ! $post || ! isset( $post->post_name ) ) {
		return;
	}

	$slug              = urldecode( $post->post_name );
	$title             = isset( $post->post_title ) ? $post->post_title : '';
	$assigned_template = get_page_template_slug( $post->ID );

	$is_theme_native_page = false;

	if (
		in_array( $assigned_template, array( 'template-contact.php', 'template-course-single.php', 'template-courses.php', 'template-about.php', 'template-blog.php' ), true ) ||
		in_array( $slug, array( 'اتصل-بنا', 'contact', 'الكورسات-التعليمية', 'courses', 'من-نحن', 'about', 'المدونة', 'blog' ), true ) ||
		in_array( (int) $post->ID, array( 43, 832, 233, 40 ), true ) ||
		0 === strpos( $slug, 'كورس-' ) ||
		0 === strpos( $slug, 'course-' ) ||
		in_array( $slug, array( 'كورس-html', 'كورس-css', 'كورس-bootstrap', 'كورس-javascript', 'كورس-react', 'كورس-c' ), true ) ||
		( function_exists( 'mb_stripos' ) && false !== mb_stripos( $title, 'كورس' ) )
	) {
		$is_theme_native_page = true;
	}

	if ( $is_theme_native_page ) {
		// 1. Remove Elementor frontend content filter so it never dumps old layouts
		if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->frontend ) ) {
			remove_filter( 'the_content', array( \Elementor\Plugin::$instance->frontend, 'apply_builder_in_content' ) );
		}

		// 2. Prevent Header Footer Elementor plugin from overriding theme header/footer
		add_filter( 'hfe_header_enabled', '__return_false', 99999 );
		add_filter( 'hfe_footer_enabled', '__return_false', 99999 );
		add_filter( 'hfe_before_header_enabled', '__return_false', 99999 );
	}
}
add_action( 'template_redirect', 'cdt_strip_elementor_from_native_templates', 1 );

/**
 * Flush Elementor and rewrite caches upon theme activation
 */
function cdt_flush_caches() {
	flush_rewrite_rules();
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}
add_action( 'after_switch_theme', 'cdt_flush_caches' );

/**
 * Check if the current post/page is built using Elementor
 *
 * Checks WordPress queried object, Elementor Public API, post meta,
 * template assignments, and serialized data.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function cdt_is_elementor_page( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_queried_object_id();
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
	}

	if ( ! $post_id ) {
		return false;
	}

	// 1. Elementor Public Document API
	if ( class_exists( '\Elementor\Plugin' ) ) {
		if ( isset( \Elementor\Plugin::$instance->documents ) ) {
			$document = \Elementor\Plugin::$instance->documents->get( $post_id );
			if ( $document && method_exists( $document, 'is_built_with_elementor' ) && $document->is_built_with_elementor() ) {
				return true;
			}
		}
		if ( isset( \Elementor\Plugin::$instance->db ) && method_exists( \Elementor\Plugin::$instance->db, 'is_built_with_elementor' ) ) {
			if ( \Elementor\Plugin::$instance->db->is_built_with_elementor( $post_id ) ) {
				return true;
			}
		}
	}

	// 2. Post meta check for builder mode
	$edit_mode = get_post_meta( $post_id, '_elementor_edit_mode', true );
	if ( 'builder' === $edit_mode ) {
		return true;
	}

	// 3. Page template check (e.g. elementor_header_footer or elementor_canvas)
	$template = get_page_template_slug( $post_id );
	if ( in_array( $template, array( 'elementor_header_footer', 'elementor_canvas' ), true ) ) {
		return true;
	}

	// 4. Elementor serialized data check
	$elementor_data = get_post_meta( $post_id, '_elementor_data', true );
	if ( ! empty( $elementor_data ) && '[]' !== $elementor_data && '""' !== $elementor_data ) {
		return true;
	}

	return false;
}

/**
 * Add custom body classes for Elementor and theme integration
 *
 * @param array $classes Existing body classes.
 * @return array
 */
function cdt_compatibility_body_classes( $classes ) {
	$current_id = get_queried_object_id();
	if ( ! $current_id ) {
		$current_id = get_the_ID();
	}

	$is_elem = false;

	if ( is_singular() && cdt_is_elementor_page( $current_id ) ) {
		$is_elem = true;
	} elseif ( is_front_page() ) {
		$front_id = (int) get_option( 'page_on_front' );
		if ( $front_id && cdt_is_elementor_page( $front_id ) && ! cdt_get_option( 'cdt_enable_native_front_page', false ) ) {
			$is_elem = true;
		}
	}

	if ( $is_elem ) {
		$classes[] = 'cdt-has-elementor';
		$classes[] = 'elementor-page';
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

/**
 * Force Code Elta6ur templates over Elementor page template hijack
 *
 * Ensures WordPress renders the new Code Elta6ur templates even if the page
 * was previously saved with an Elementor template or edit mode in database.
 *
 * @param string $template Current template file path.
 * @return string Modified template file path.
 */
function cdt_force_theme_templates( $template ) {
	// 1. Front Page: Always use theme front-page.php
	if ( is_front_page() ) {
		$front = locate_template( array( 'front-page.php' ) );
		if ( $front ) {
			return $front;
		}
	}

	// 2. Specific Pages by slug or ID
	if ( is_page() ) {
		$post = get_queried_object();
		if ( $post && isset( $post->post_name ) ) {
			$slug = urldecode( $post->post_name );

			// Courses Page
			if ( 'الكورسات-التعليمية' === $slug || 'courses' === $slug || 832 === (int) $post->ID ) {
				$courses = locate_template( array( 'page-الكورسات-التعليمية.php', 'template-courses.php' ) );
				if ( $courses ) {
					return $courses;
				}
			}

			// Contact Page
			if ( 'اتصل-بنا' === $slug || 'contact' === $slug || 43 === (int) $post->ID ) {
				$contact = locate_template( array( 'page-اتصل-بنا.php', 'template-contact.php' ) );
				if ( $contact ) {
					return $contact;
				}
			}

			// About Page
			if ( 'من-نحن' === $slug || 'about' === $slug || 233 === (int) $post->ID ) {
				$about = locate_template( array( 'page-من-نحن.php', 'template-about.php' ) );
				if ( $about ) {
					return $about;
				}
			}

			// Blog Page
			if ( 'المدونة' === $slug || 'blog' === $slug || 40 === (int) $post->ID ) {
				$blog = locate_template( array( 'page-المدونة.php', 'template-blog.php' ) );
				if ( $blog ) {
					return $blog;
				}
			}

			// Single Course Hub & Lesson Index Pages (e.g. كورس-html, كورس-css, etc.)
			$title             = isset( $post->post_title ) ? $post->post_title : '';
			$assigned_template = get_page_template_slug( $post->ID );
			if (
				'template-course-single.php' === $assigned_template ||
				0 === strpos( $slug, 'كورس-' ) ||
				0 === strpos( $slug, 'course-' ) ||
				in_array( $slug, array( 'كورس-html', 'كورس-css', 'كورس-bootstrap', 'كورس-javascript', 'كورس-react', 'كورس-c' ), true ) ||
				( function_exists( 'mb_stripos' ) && false !== mb_stripos( $title, 'كورس' ) && ( false !== mb_stripos( $title, 'html' ) || false !== mb_stripos( $title, 'css' ) || false !== mb_stripos( $title, 'javascript' ) || false !== mb_stripos( $title, 'bootstrap' ) || false !== mb_stripos( $title, 'react' ) || false !== mb_stripos( $title, 'c++' ) ) )
			) {
				$course_single = locate_template( array( 'template-course-single.php' ) );
				if ( $course_single ) {
					return $course_single;
				}
			}
		}
	}

	return $template;
}
add_filter( 'template_include', 'cdt_force_theme_templates', 9999999 );


