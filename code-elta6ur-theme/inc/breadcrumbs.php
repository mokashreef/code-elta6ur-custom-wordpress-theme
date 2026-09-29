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

	if ( is_single() ) {
		echo $delimiter;
		$post_type = get_post_type();

		if ( 'cat_series' === $post_type ) {
			echo '<a href="' . esc_url( home_url( '/series/' ) ) . '" class="cdt-breadcrumb-link">' . esc_html__( 'السلاسل التعليمية', 'code-elta6ur' ) . '</a>';
			echo $delimiter;
		} else {
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				$category = $categories[0];
				echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="cdt-breadcrumb-link">' . esc_html( $category->name ) . '</a>';
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
