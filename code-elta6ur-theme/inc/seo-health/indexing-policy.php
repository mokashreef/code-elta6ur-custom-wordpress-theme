<?php
/**
 * سياسة الفهرسة المركزية ومنع تضخم الأرشفة (noindex / index Matrix)
 * Code Elta6ur Indexing Policy Engine
 *
 * @package CodeElta6ur
 * @subpackage SEO_Health
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get centralized indexing policy settings
 *
 * @return array
 */
function cdt_seo_get_indexing_rules() {
	$defaults = array(
		'noindex_accounts'          => true,
		'noindex_search'            => true,
		'noindex_author'            => true, // Code Elta6ur is a single-publisher technical platform
		'noindex_date'              => true,
		'noindex_attachments'       => true,
		'noindex_thin_tags'         => true,
		'tag_min_posts'             => 5,
		'noindex_elementor_library' => true,
		'noindex_filter_queries'    => true,
		'keep_paged_index'          => true,
	);

	$saved = get_option( 'cdt_seo_indexing_options', array() );
	return wp_parse_args( is_array( $saved ) ? $saved : array(), $defaults );
}

/**
 * Determine if the current WordPress request is an Account or Authentication endpoint
 *
 * @return bool
 */
function cdt_seo_is_account_request() {
	if ( is_admin() ) {
		return false;
	}

	// 1. Core Account Pages
	if ( is_page( array( 'login', 'registration', 'register', 'lost-password', 'my-account', 'account' ) ) ) {
		return true;
	}

	// 2. Query var or rewrite check
	$page_slug = get_query_var( 'pagename' );
	if ( in_array( $page_slug, array( 'login', 'registration', 'lost-password', 'my-account' ), true ) ) {
		return true;
	}

	// 3. Fallback URL path inspection
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$path        = trim( (string) wp_parse_url( $request_uri, PHP_URL_PATH ), '/' );

	if ( in_array( $path, array( 'login', 'registration', 'register', 'lost-password', 'my-account' ), true ) ) {
		return true;
	}

	return false;
}

/**
 * Determine whether the current page is protected (Must ALWAYS be index,follow)
 *
 * @return bool
 */
function cdt_seo_is_protected_canonical_page() {
	// Front Page / Home
	if ( is_front_page() || is_home() ) {
		return true;
	}

	// Single Blog Posts, Lessons, or Course syllabus
	if ( is_single() ) {
		$post_type = get_post_type();
		if ( in_array( $post_type, array( 'post', 'course', 'lesson' ), true ) ) {
			return true;
		}
	}

	// Series taxonomy archive
	if ( is_tax( 'cat_series' ) ) {
		return true;
	}

	// Tools Hub & Single Tools
	if ( function_exists( 'cdt_get_current_tool_route' ) && cdt_get_current_tool_route() ) {
		return true;
	}

	// Editor Hub & Specific Languages
	if ( function_exists( 'cdt_get_current_editor_route' ) && cdt_get_current_editor_route() ) {
		return true;
	}

	// Core Pages (About, Contact, Courses hub, Blog hub, Privacy policy)
	if ( is_page() ) {
		$post_id   = get_the_ID();
		$raw_slug  = urldecode( (string) get_post_field( 'post_name', $post_id ) );
		$page_slug = sanitize_title( $raw_slug );

		$protected_slugs = array(
			'من-نحن',
			'about',
			'اتصل-بنا',
			'contact',
			'الكورسات-التعليمية',
			'courses',
			'المدونة',
			'blog',
			'سياسة-الخصوصية',
			'privacy-policy',
			'شروط-الاستخدام',
			'terms',
		);

		if ( in_array( $raw_slug, $protected_slugs, true ) || in_array( $page_slug, $protected_slugs, true ) || 0 === strpos( $raw_slug, 'كورس-' ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Central evaluation of current request robots directive
 *
 * @return array {
 *     @type string $decision 'noindex'|'index'|'neutral'
 *     @type bool   $follow
 *     @type string $reason
 *     @type bool   $is_protected
 * }
 */
function cdt_seo_evaluate_robots_policy() {
	$rules = cdt_seo_get_indexing_rules();

	// 1. Check Protected Critical Pages (Index, Follow)
	if ( cdt_seo_is_protected_canonical_page() ) {
		return array(
			'decision'     => 'index',
			'follow'       => true,
			'reason'       => 'صفحة أساسية محمية من نوع المحتوى الرئيسي (Core Protected Content)',
			'is_protected' => true,
		);
	}

	// 2. 404 Error Pages
	if ( is_404() ) {
		return array(
			'decision'     => 'noindex',
			'follow'       => false,
			'reason'       => 'صفحة خطأ 404 (404 Not Found)',
			'is_protected' => false,
		);
	}

	// 3. User Accounts & Auth Pages
	if ( ! empty( $rules['noindex_accounts'] ) && cdt_seo_is_account_request() ) {
		return array(
			'decision'     => 'noindex',
			'follow'       => true,
			'reason'       => 'صفحة تسجيل أو دخول أو إدارة حساب (User Account & Auth)',
			'is_protected' => false,
		);
	}

	// 4. Internal Search Results & Filter Parameters
	if ( ! empty( $rules['noindex_search'] ) && ( is_search() || isset( $_GET['s'] ) ) ) {
		return array(
			'decision'     => 'noindex',
			'follow'       => true,
			'reason'       => 'صفحة نتائج بحث داخلي (Internal Search Query)',
			'is_protected' => false,
		);
	}

	if ( ! empty( $rules['noindex_filter_queries'] ) && ! empty( $_GET ) ) {
		// If query contains sorting or filter query strings
		$filter_keys = array( 'filter', 'sort', 'orderby', 'order', 'view', 'cdt_filter' );
		foreach ( $filter_keys as $k ) {
			if ( isset( $_GET[ $k ] ) ) {
				return array(
					'decision'     => 'noindex',
					'follow'       => true,
					'reason'       => 'رابط يحتوي معايير تصفية أو ترتيب (Filter/Sorting Query String)',
					'is_protected' => false,
				);
			}
		}
	}

	// 5. Media Attachment Pages
	if ( ! empty( $rules['noindex_attachments'] ) && is_attachment() ) {
		return array(
			'decision'     => 'noindex',
			'follow'       => true,
			'reason'       => 'صفحة مرفق وسائط (Attachment Page)',
			'is_protected' => false,
		);
	}

	// 6. Elementor Internal Templates
	if ( ! empty( $rules['noindex_elementor_library'] ) ) {
		if ( is_singular( 'elementor_library' ) || is_post_type_archive( 'elementor_library' ) ) {
			return array(
				'decision'     => 'noindex',
				'follow'       => false,
				'reason'       => 'قالب داخلي لـ Elementor Library',
				'is_protected' => false,
			);
		}
	}

	// 7. Date Archives
	if ( ! empty( $rules['noindex_date'] ) && is_date() ) {
		return array(
			'decision'     => 'noindex',
			'follow'       => true,
			'reason'       => 'أرشيف التواريخ (Date Archive)',
			'is_protected' => false,
		);
	}

	// 8. Author Archives (Single-author technical blog)
	if ( ! empty( $rules['noindex_author'] ) && is_author() ) {
		return array(
			'decision'     => 'noindex',
			'follow'       => true,
			'reason'       => 'أرشيف المؤلف لمنصة بناشر تقني موحد (Author Archive)',
			'is_protected' => false,
		);
	}

	// 9. Tag Archives with few articles (Thin Tag Archives)
	if ( ! empty( $rules['noindex_thin_tags'] ) && is_tag() ) {
		$term = get_queried_object();
		$min  = isset( $rules['tag_min_posts'] ) ? absint( $rules['tag_min_posts'] ) : 5;
		if ( $term && isset( $term->count ) && $term->count < $min ) {
			return array(
				'decision'     => 'noindex',
				'follow'       => true,
				'reason'       => sprintf( 'أرشيف وسم رقيق يحتوي أقل من %d مقالات (Thin Tag Archive)', $min ),
				'is_protected' => false,
			);
		}
	}

	// 10. Paginated Archives (paged > 1) explicitly remain index,follow
	if ( is_paged() ) {
		return array(
			'decision'     => 'index',
			'follow'       => true,
			'reason'       => 'صفحة ترقيم أرشيف فرعية (Paginated Page)',
			'is_protected' => false,
		);
	}

	return array(
		'decision'     => 'neutral',
		'follow'       => true,
		'reason'       => 'السلوك القياسي للقالب وووردبريس',
		'is_protected' => false,
	);
}

/**
 * Apply indexing policy via WordPress Core wp_robots filter
 *
 * @param array $robots Core robots array.
 * @return array
 */
function cdt_seo_apply_wp_robots( $robots ) {
	$eval = cdt_seo_evaluate_robots_policy();

	if ( 'noindex' === $eval['decision'] ) {
		$robots['noindex'] = true;
		$robots['follow']  = $eval['follow'];
		unset( $robots['index'] );
	} elseif ( 'index' === $eval['decision'] ) {
		$robots['noindex'] = false;
		$robots['index']   = true;
		$robots['follow']  = true;
		unset( $robots['noindex'] );
	}

	return $robots;
}
add_filter( 'wp_robots', 'cdt_seo_apply_wp_robots', 999 );

/**
 * Integrate with Yoast SEO if active
 *
 * @param string $robots Yoast robots string.
 * @return string
 */
function cdt_seo_filter_yoast_robots( $robots ) {
	$eval = cdt_seo_evaluate_robots_policy();

	if ( 'noindex' === $eval['decision'] ) {
		return 'noindex,follow';
	}
	if ( 'index' === $eval['decision'] ) {
		return 'index,follow';
	}

	return $robots;
}
add_filter( 'wpseo_robots', 'cdt_seo_filter_yoast_robots', 999 );

/**
 * Integrate with Rank Math SEO if active
 *
 * @param array $robots Rank Math robots array.
 * @return array
 */
function cdt_seo_filter_rank_math_robots( $robots ) {
	$eval = cdt_seo_evaluate_robots_policy();

	if ( 'noindex' === $eval['decision'] ) {
		$robots['index']   = 'noindex';
		$robots['follow']  = 'follow';
	} elseif ( 'index' === $eval['decision'] ) {
		$robots['index']   = 'index';
		$robots['follow']  = 'follow';
	}

	return $robots;
}
add_filter( 'rank_math/frontend/robots', 'cdt_seo_filter_rank_math_robots', 999 );

/**
 * Integrate with SEOPress if active
 */
add_filter(
	'seopress_titles_robots',
	function( $robots ) {
		$eval = cdt_seo_evaluate_robots_policy();
		if ( 'noindex' === $eval['decision'] ) {
			return array( 'noindex' => 'yes', 'follow' => 'yes' );
		}
		if ( 'index' === $eval['decision'] ) {
			return array( 'noindex' => 'no', 'follow' => 'yes' );
		}
		return $robots;
	},
	999
);

/**
 * Admin Safety Guard: Alert if a protected core page is marked noindex by mistake
 */
function cdt_seo_admin_protected_page_warning() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || 'post' !== $screen->base ) {
		return;
	}

	global $post;
	if ( ! $post || empty( $post->ID ) ) {
		return;
	}

	$post_id   = $post->ID;
	$raw_slug  = urldecode( (string) $post->post_name );
	$page_slug = sanitize_title( $raw_slug );

	$protected_slugs = array(
		'من-نحن',
		'about',
		'اتصل-بنا',
		'contact',
		'الكورسات-التعليمية',
		'courses',
		'المدونة',
		'blog',
		'سياسة-الخصوصية',
		'privacy-policy',
	);

	$is_protected = in_array( $raw_slug, $protected_slugs, true ) || in_array( $page_slug, $protected_slugs, true ) || 0 === strpos( $raw_slug, 'كورس-' );

	if ( $is_protected ) {
		$yoast_noindex = get_post_meta( $post_id, '_yoast_wpseo_meta-robots-noindex', true );
		$rm_robots     = get_post_meta( $post_id, 'rank_math_robots', true );
		$is_noindexed  = ( '1' === (string) $yoast_noindex ) || ( is_array( $rm_robots ) && in_array( 'noindex', $rm_robots, true ) );

		if ( $is_noindexed ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p>
					<strong><?php esc_html_e( '⚠️ تحذير سيو عالي الخطورة من قالب كود التطور:', 'code-elta6ur' ); ?></strong>
					<?php esc_html_e( 'هذه الصفحة من أصول الموقع الأساسية المحمية، ولكنها مضبوطة يدويًا على (noindex)! تم تجاوز ذلك في القالب لضمان فهرستها، ولكن يُرجى تعديل خيار إضافة السيو إلى Index فورًا.', 'code-elta6ur' ); ?>
				</p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'cdt_seo_admin_protected_page_warning' );

/**
 * Exclude noindexed post types and taxonomy terms from WordPress Core XML Sitemaps
 */
function cdt_seo_filter_sitemap_post_types( $post_types ) {
	$rules = cdt_seo_get_indexing_rules();

	if ( ! empty( $rules['noindex_attachments'] ) ) {
		unset( $post_types['attachment'] );
	}
	if ( ! empty( $rules['noindex_elementor_library'] ) ) {
		unset( $post_types['elementor_library'] );
	}

	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'cdt_seo_filter_sitemap_post_types' );

/**
 * Exclude thin tags from Core XML Sitemap
 */
function cdt_seo_filter_sitemap_taxonomies_query( $args, $taxonomy ) {
	$rules = cdt_seo_get_indexing_rules();

	if ( 'post_tag' === $taxonomy && ! empty( $rules['noindex_thin_tags'] ) ) {
		$min = isset( $rules['tag_min_posts'] ) ? absint( $rules['tag_min_posts'] ) : 5;
		$args['hide_empty'] = true;
		$args['count']      = true;
		$args['hierarchical'] = false;
		// WP terms query min count
		$args['minimum_count'] = $min;
	}

	return $args;
}
add_filter( 'wp_sitemaps_taxonomies_query_args', 'cdt_seo_filter_sitemap_taxonomies_query', 10, 2 );

/**
 * Exclude author sitemap provider if single-author platform
 */
function cdt_seo_filter_sitemap_providers( $providers ) {
	$rules = cdt_seo_get_indexing_rules();

	if ( ! empty( $rules['noindex_author'] ) && isset( $providers['users'] ) ) {
		unset( $providers['users'] );
	}

	return $providers;
}
add_filter( 'wp_sitemaps_add_provider', 'cdt_seo_filter_sitemap_providers' );
