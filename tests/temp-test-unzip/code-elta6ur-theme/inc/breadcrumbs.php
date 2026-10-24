<?php
/**
 * مسار التنقل المهيأ لمحركات البحث
 * Code Elta6ur Breadcrumbs
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output semantic accessible breadcrumbs
 */
function cdt_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	$delimiter = '<span class="cdt-breadcrumb-sep" aria-hidden="true">/</span>';
	$home_title = esc_html__( 'الرئيسية', 'code-elta6ur' );

	echo '<nav class="cdt-breadcrumbs" aria-label="' . esc_attr__( 'مسار التنقل', 'code-elta6ur' ) . '">';
	echo '<div class="cdt-breadcrumbs-container">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="cdt-breadcrumb-link">' . esc_html( $home_title ) . '</a>';

	// Tools Engine Routes
	if ( function_exists( 'cdt_get_current_tool_route' ) && 'hub' === cdt_get_current_tool_route() ) {
		echo $delimiter;
		echo '<span class="cdt-breadcrumb-current" aria-current="page">' . esc_html__( 'الأدوات التقنية', 'code-elta6ur' ) . '</span>';
	} elseif ( function_exists( 'cdt_get_current_tool_route' ) && 'tool' === cdt_get_current_tool_route() ) {
		echo $delimiter;
		echo '<a href="' . esc_url( home_url( '/tools/' ) ) . '" class="cdt-breadcrumb-link">' . esc_html__( 'الأدوات التقنية', 'code-elta6ur' ) . '</a>';
		echo $delimiter;
		$tool_slug = cdt_get_current_tool_slug();
		$tool      = cdt_get_tool( $tool_slug );
		$tool_name = $tool ? $tool['name'] : $tool_slug;
		echo '<span class="cdt-breadcrumb-current" aria-current="page">' . esc_html( $tool_name ) . '</span>';
	} elseif ( function_exists( 'cdt_get_current_editor_route' ) && 'hub' === cdt_get_current_editor_route() ) {
		echo $delimiter;
		echo '<span class="cdt-breadcrumb-current" aria-current="page">' . esc_html__( 'محرر الأكواد أونلاين', 'code-elta6ur' ) . '</span>';
	} elseif ( function_exists( 'cdt_get_current_editor_route' ) && 'lang' === cdt_get_current_editor_route() ) {
		echo $delimiter;
		echo '<a href="' . esc_url( home_url( '/editor/' ) ) . '" class="cdt-breadcrumb-link">' . esc_html__( 'محرر الأكواد', 'code-elta6ur' ) . '</a>';
		echo $delimiter;
		$lang_slug = cdt_get_current_editor_lang();
		$lang      = cdt_get_editor_language( $lang_slug );
		$lang_name = $lang ? $lang['name'] : strtoupper( $lang_slug );
		echo '<span class="cdt-breadcrumb-current" aria-current="page">' . esc_html( $lang_name ) . '</span>';
	} elseif ( is_single() ) {
		echo $delimiter;
		$post_type = get_post_type();

		if ( 'cat_series' === $post_type ) {
			echo '<a href="' . esc_url( home_url( '/series/' ) ) . '" class="cdt-breadcrumb-link">' . esc_html__( 'السلاسل التعليمية', 'code-elta6ur' ) . '</a>';
			echo $delimiter;
		} elseif ( function_exists( 'cdt_is_post_a_course_lesson' ) && cdt_is_post_a_course_lesson( get_the_ID() ) && function_exists( 'cdt_get_course_slug_for_post' ) ) {
			$c_slug = cdt_get_course_slug_for_post( get_the_ID() );
			$c_url  = home_url( '/' . rawurlencode( $c_slug ) . '/' );
			echo '<a href="' . esc_url( $c_url ) . '" class="cdt-breadcrumb-link">' . esc_html__( 'الكورس التدريبي', 'code-elta6ur' ) . '</a>';
			echo $delimiter;
		} else {
			$display_cat = function_exists( 'cdt_get_display_category' ) ? cdt_get_display_category() : null;
			if ( $display_cat ) {
				echo '<a href="' . esc_url( get_category_link( $display_cat->term_id ) ) . '" class="cdt-breadcrumb-link">' . esc_html( $display_cat->name ) . '</a>';
				echo $delimiter;
			} else {
				echo '<a href="' . esc_url( home_url( '/%d8%a7%d9%84%d9%85%d8%af%d9%88%d9%86%d8%a9/' ) ) . '" class="cdt-breadcrumb-link">' . esc_html__( 'المدونة', 'code-elta6ur' ) . '</a>';
				echo $delimiter;
			}
		}
		echo '<span class="cdt-breadcrumb-current" aria-current="page">' . wp_trim_words( get_the_title(), 7 ) . '</span>';
	} elseif ( is_page() ) {
		global $post;
		if ( $post->post_parent ) {
			$ancestors = get_post_ancestors( $post->ID );
			$ancestors = array_reverse( $ancestors );
			foreach ( $ancestors as $ancestor ) {
				echo $delimiter;
				echo '<a href="' . esc_url( get_permalink( $ancestor ) ) . '" class="cdt-breadcrumb-link">' . esc_html( get_the_title( $ancestor ) ) . '</a>';
			}
		}
		echo $delimiter;
		echo '<span class="cdt-breadcrumb-current" aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_category() ) {
		echo $delimiter;
		echo '<span class="cdt-breadcrumb-current" aria-current="page">' . esc_html( single_cat_title( '', false ) ) . '</span>';
	} elseif ( is_tag() ) {
		echo $delimiter;
		echo '<span class="cdt-breadcrumb-current" aria-current="page">' . esc_html__( 'وسم: ', 'code-elta6ur' ) . esc_html( single_tag_title( '', false ) ) . '</span>';
	} elseif ( is_author() ) {
		echo $delimiter;
		echo '<span class="cdt-breadcrumb-current" aria-current="page">' . esc_html__( 'المؤلف: ', 'code-elta6ur' ) . esc_html( get_the_author() ) . '</span>';
	} elseif ( is_search() ) {
		echo $delimiter;
		echo '<span class="cdt-breadcrumb-current" aria-current="page">' . esc_html__( 'نتائج البحث عن: ', 'code-elta6ur' ) . esc_html( get_search_query() ) . '</span>';
	} elseif ( is_404() ) {
		echo $delimiter;
		echo '<span class="cdt-breadcrumb-current" aria-current="page">' . esc_html__( 'الصفحة غير موجودة 404', 'code-elta6ur' ) . '</span>';
	} elseif ( is_post_type_archive( 'cat_series' ) ) {
		echo $delimiter;
		echo '<span class="cdt-breadcrumb-current" aria-current="page">' . esc_html__( 'السلاسل التعليمية', 'code-elta6ur' ) . '</span>';
	}

	echo '</div>';
	echo '</nav>';
}
