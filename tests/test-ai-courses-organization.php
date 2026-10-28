<?php
/**
 * Test Suite: AI Section, Course Separation, Category Masking & Regressions
 * Code Elta6ur Theme Verification Suite
 */

$passCount = 0;
$failCount = 0;

function assertCondition( $condition, $message ) {
	global $passCount, $failCount;
	if ( $condition ) {
		echo " [PASS] " . $message . "\n";
		$passCount++;
	} else {
		echo " [FAIL] " . $message . "\n";
		$failCount++;
	}
}

// 1. Setup mock environment if needed
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/../code-elta6ur-theme/' );
}
if ( ! defined( 'CDT_THEME_DIR' ) ) {
	define( 'CDT_THEME_DIR', __DIR__ . '/../code-elta6ur-theme' );
}
if ( ! defined( 'CDT_THEME_URI' ) ) {
	define( 'CDT_THEME_URI', 'https://code-elta6ur.com/wp-content/themes/code-elta6ur-theme' );
}

// Global category mock database
$mock_categories = array(
	9   => (object) array( 'term_id' => 9, 'name' => 'الذكاء الاصطناعي', 'slug' => 'الذكاء-الاصطناعي', 'count' => 18, 'parent' => 0 ),
	8   => (object) array( 'term_id' => 8, 'name' => 'تعلم البرمجة', 'slug' => 'تعلم-البرمجة', 'count' => 48, 'parent' => 0 ),
	6   => (object) array( 'term_id' => 6, 'name' => 'البرمجة', 'slug' => 'البرمجة', 'count' => 30, 'parent' => 0 ),
	5   => (object) array( 'term_id' => 5, 'name' => 'تطوير المواقع', 'slug' => 'تطوير-المواقع', 'count' => 33, 'parent' => 0 ),
	10  => (object) array( 'term_id' => 10, 'name' => 'التقنية', 'slug' => 'التقنية', 'count' => 27, 'parent' => 0 ),
	7   => (object) array( 'term_id' => 7, 'name' => 'العمل في البرمجة', 'slug' => 'العمل-في-البرمجة', 'count' => 33, 'parent' => 0 ),
	11  => (object) array( 'term_id' => 11, 'name' => 'لغات البرمجة', 'slug' => 'لغات-البرمجة', 'count' => 20, 'parent' => 0 ),
	53  => (object) array( 'term_id' => 53, 'name' => 'HTML', 'slug' => 'html', 'count' => 17, 'parent' => 0 ),
	62  => (object) array( 'term_id' => 62, 'name' => 'css', 'slug' => 'css', 'count' => 37, 'parent' => 0 ),
	71  => (object) array( 'term_id' => 71, 'name' => 'Bootstrap', 'slug' => 'bootstrap', 'count' => 25, 'parent' => 0 ),
	74  => (object) array( 'term_id' => 74, 'name' => 'JavaScript', 'slug' => 'javascript', 'count' => 32, 'parent' => 0 ),
	76  => (object) array( 'term_id' => 76, 'name' => 'React', 'slug' => 'react', 'count' => 32, 'parent' => 0 ),
	80  => (object) array( 'term_id' => 80, 'name' => 'C++', 'slug' => 'c', 'count' => 31, 'parent' => 0 ),
	119 => (object) array( 'term_id' => 119, 'name' => 'كورس typescript', 'slug' => 'typescript', 'count' => 13, 'parent' => 0 ),
	// Child categories under AI (parent: 9)
	201 => (object) array( 'term_id' => 201, 'name' => 'أدوات الذكاء الاصطناعي', 'slug' => 'ai-tools', 'count' => 8, 'parent' => 9 ),
	202 => (object) array( 'term_id' => 202, 'name' => 'شات جي بي تي', 'slug' => 'chatgpt', 'count' => 6, 'parent' => 9 ),
);

$mock_post_categories = array(
	// Only AI post
	3394 => array( 9 ),
	// Multi-category post: AI + Learn Programming
	3389 => array( 9, 8 ),
	// Multi-category post: Programming + AI
	3319 => array( 6, 9 ),
	// General Web Dev post
	1001 => array( 5 ),
	// HTML course lesson
	527  => array( 53 ),
	// JavaScript course lesson
	1214 => array( 74 ),
	// Post in AI Child Category (Tools)
	4001 => array( 201 ),
	// Post in AI Parent + AI Child Category (ChatGPT)
	4002 => array( 9, 202 ),
);

if ( ! function_exists( 'get_the_category' ) ) {
	function get_the_category( $post_id = null ) {
		global $mock_categories, $mock_post_categories;
		if ( ! $post_id ) {
			$post_id = 3394;
		}
		if ( isset( $mock_post_categories[ $post_id ] ) ) {
			$cats = array();
			foreach ( $mock_post_categories[ $post_id ] as $cid ) {
				if ( isset( $mock_categories[ $cid ] ) ) {
					$cats[] = $mock_categories[ $cid ];
				}
			}
			return $cats;
		}
		return array();
	}
}

if ( ! function_exists( 'get_category_by_slug' ) ) {
	function get_category_by_slug( $slug ) {
		global $mock_categories;
		foreach ( $mock_categories as $cat ) {
			if ( $cat->slug === $slug || urldecode( $cat->slug ) === $slug ) {
				return $cat;
			}
		}
		return false;
	}
}

if ( ! function_exists( 'get_category' ) ) {
	function get_category( $category ) {
		global $mock_categories;
		$id = is_object( $category ) ? $category->term_id : (int) $category;
		return isset( $mock_categories[ $id ] ) ? $mock_categories[ $id ] : false;
	}
}

if ( ! function_exists( 'get_terms' ) ) {
	function get_terms( $args = array() ) {
		global $mock_categories;
		$results = array();
		$parent  = isset( $args['parent'] ) ? (int) $args['parent'] : null;

		foreach ( $mock_categories as $cat ) {
			if ( null !== $parent ) {
				$cat_parent = isset( $cat->parent ) ? (int) $cat->parent : 0;
				if ( $cat_parent !== $parent ) {
					continue;
				}
			}
			$results[] = $cat;
		}
		return $results;
	}
}

if ( ! function_exists( 'cat_is_ancestor_of' ) ) {
	function cat_is_ancestor_of( $cat1, $cat2 ) {
		global $mock_categories;
		$cat1_id = is_object( $cat1 ) ? $cat1->term_id : (int) $cat1;
		$cat2_id = is_object( $cat2 ) ? $cat2->term_id : (int) $cat2;

		if ( isset( $mock_categories[ $cat2_id ] ) ) {
			$parent = isset( $mock_categories[ $cat2_id ]->parent ) ? (int) $mock_categories[ $cat2_id ]->parent : 0;
			return ( $parent === $cat1_id );
		}
		return false;
	}
}

if ( ! function_exists( 'get_category_link' ) ) {
	function get_category_link( $category_id ) {
		global $mock_categories;
		if ( isset( $mock_categories[ $category_id ] ) ) {
			return 'https://code-elta6ur.com/category/' . rawurlencode( $mock_categories[ $category_id ]->slug ) . '/';
		}
		return 'https://code-elta6ur.com/category/' . $category_id . '/';
	}
}

if ( ! function_exists( 'get_categories' ) ) {
	function get_categories( $args = array() ) {
		global $mock_categories;
		$res = $mock_categories;
		if ( ! empty( $args['exclude'] ) && is_array( $args['exclude'] ) ) {
			foreach ( $args['exclude'] as $exc_id ) {
				unset( $res[ $exc_id ] );
			}
		}
		return array_values( $res );
	}
}

if ( ! function_exists( 'wp_parse_args' ) ) {
	function wp_parse_args( $args, $defaults = array() ) {
		if ( is_object( $args ) ) {
			$r = get_object_vars( $args );
		} elseif ( is_array( $args ) ) {
			$r = &$args;
		} else {
			wp_parse_str( $args, $r );
		}
		if ( is_array( $defaults ) ) {
			return array_merge( $defaults, $r );
		}
		return $r;
	}
}

if ( ! function_exists( 'esc_html__' ) ) {
	function esc_html__( $text, $domain = 'default' ) {
		return $text;
	}
}

if ( ! function_exists( 'absint' ) ) {
	function absint( $val ) {
		return abs( (int) $val );
	}
}

if ( ! function_exists( 'is_wp_error' ) ) {
	function is_wp_error( $thing ) {
		return false;
	}
}

if ( ! function_exists( 'apply_filters' ) ) {
	function apply_filters( $tag, $value ) {
		return $value;
	}
}

if ( ! function_exists( 'home_url' ) ) {
	function home_url( $path = '' ) {
		return 'https://code-elta6ur.com' . $path;
	}
}

// Load Theme helpers
require_once __DIR__ . '/../code-elta6ur-theme/inc/helpers.php';

echo "==================================================\n";
echo "1. VERIFYING COURSE CATEGORIES SEPARATION\n";
echo "==================================================\n";

$course_cats = cdt_get_course_category_ids();
assertCondition( is_array( $course_cats ), "Course category IDs returned as array" );
assertCondition( count( $course_cats ) === 7, "Exactly 7 accredited course tracks identified (Actual: " . count( $course_cats ) . ")" );

$expected_cats = array( 53, 62, 71, 74, 76, 80, 119 );
$diff = array_diff( $expected_cats, $course_cats );
assertCondition( empty( $diff ), "All 7 course category IDs match specification: " . implode( ',', $expected_cats ) );

assertCondition( cdt_is_course_category( 53 ) === true, "Cat 53 (HTML) identified as course category" );
assertCondition( cdt_is_course_category( 74 ) === true, "Cat 74 (JavaScript) identified as course category" );
assertCondition( cdt_is_course_category( 9 ) === false, "Cat 9 (AI) is NOT a course category" );
assertCondition( cdt_is_course_category( 8 ) === false, "Cat 8 (Learn Programming) is NOT a course category" );
assertCondition( cdt_is_course_category( 6 ) === false, "Cat 6 (General Programming) is NOT a course category" );

$general_blog_cats = cdt_get_general_blog_categories();
$found_course_in_blog = false;
foreach ( $general_blog_cats as $gcat ) {
	if ( in_array( (int) $gcat->term_id, $expected_cats, true ) ) {
		$found_course_in_blog = true;
	}
}
assertCondition( ! $found_course_in_blog, "General blog categories successfully exclude all course categories" );

echo "\n==================================================\n";
echo "2. VERIFYING AI SECTION CONFIGURATION & ACCESS\n";
echo "==================================================\n";

$ai_cat_id = cdt_get_ai_category_id();
assertCondition( $ai_cat_id === 9, "AI Category ID is correctly resolved to 9 (Actual: $ai_cat_id)" );

$ai_url = cdt_get_ai_section_url();
assertCondition( strpos( $ai_url, 'category' ) !== false, "AI section URL generates valid category archive route: $ai_url" );

$theme_dir = __DIR__ . '/../code-elta6ur-theme';
assertCondition( file_exists( $theme_dir . '/category-9.php' ), "Standard ASCII category-9.php template exists" );
assertCondition( file_exists( $theme_dir . '/template-ai.php' ), "Dedicated page template template-ai.php exists" );

// Verification of AI child categories dynamic engine
$ai_children = cdt_get_ai_child_categories( false );
assertCondition( is_array( $ai_children ) && count( $ai_children ) === 2, "AI dynamic child categories successfully retrieved (Actual count: " . count( $ai_children ) . ")" );
assertCondition( cdt_is_ai_category_or_child( 9 ) === true, "Cat 9 is recognized as AI parent" );
assertCondition( cdt_is_ai_category_or_child( 201 ) === true, "Cat 201 (AI Tools) is recognized as child of AI" );
assertCondition( cdt_is_ai_category_or_child( 202 ) === true, "Cat 202 (ChatGPT) is recognized as child of AI" );
assertCondition( cdt_is_ai_category_or_child( 6 ) === false, "Cat 6 (General Programming) is NOT recognized as child of AI" );

echo "\n==================================================\n";
echo "3. VERIFYING AI CATEGORY MASKING ON CARDS & VIEWS\n";
echo "==================================================\n";

// Case A: Post with ONLY AI category (Post 3394)
$display_cat_3394 = cdt_get_display_category( 3394 );
assertCondition( $display_cat_3394 === null, "Post with ONLY AI category returns NULL display category (hidden from badge)" );

// Case B: Post with AI + Learn Programming (Post 3389)
$display_cat_3389 = cdt_get_display_category( 3389 );
assertCondition( $display_cat_3389 !== null && $display_cat_3389->term_id === 8, "Post with AI + Cat 8 displays Cat 8 ('" . ( $display_cat_3389 ? $display_cat_3389->name : '' ) . "') without exposing AI name" );

// Case C: Post with Programming + AI (Post 3319)
$display_cat_3319 = cdt_get_display_category( 3319 );
assertCondition( $display_cat_3319 !== null && $display_cat_3319->term_id === 6, "Post with AI + Cat 6 displays Cat 6 ('" . ( $display_cat_3319 ? $display_cat_3319->name : '' ) . "') without exposing AI name" );

// Case D: General Web Dev post (Post 1001)
$display_cat_1001 = cdt_get_display_category( 1001 );
assertCondition( $display_cat_1001 !== null && $display_cat_1001->term_id === 5, "General post displays its own category: " . ( $display_cat_1001 ? $display_cat_1001->name : '' ) );

// Case E: Post in AI Child Category (Tools - Post 4001)
$display_cat_4001 = cdt_get_display_category( 4001 );
assertCondition( $display_cat_4001 !== null && $display_cat_4001->term_id === 201, "Post in AI child category displays child category badge: " . ( $display_cat_4001 ? $display_cat_4001->name : '' ) );

// Case F: Post in AI Parent + AI Child Category (ChatGPT - Post 4002)
$display_cat_4002 = cdt_get_display_category( 4002 );
assertCondition( $display_cat_4002 !== null && $display_cat_4002->term_id === 202, "Post in AI parent + child displays specific child badge: " . ( $display_cat_4002 ? $display_cat_4002->name : '' ) );

echo "\n==================================================\n";
echo "4. VERIFYING CODE FILES & INTEGRATION ACCURACY\n";
echo "==================================================\n";

$card_content = file_get_contents( $theme_dir . '/template-parts/cards/card-post.php' );
assertCondition( strpos( $card_content, 'cdt_get_display_category' ) !== false, "card-post.php utilizes cdt_get_display_category()" );

$blog_template = file_get_contents( $theme_dir . '/template-blog.php' );
assertCondition( strpos( $blog_template, 'cdt_get_course_category_ids' ) !== false, "template-blog.php excludes course category IDs" );
assertCondition( strpos( $blog_template, 'category__not_in' ) !== false, "template-blog.php queries with category__not_in filter" );
assertCondition( strpos( $blog_template, 'cdt_get_ai_section_url' ) !== false, "template-blog.php features prominent AI section badge" );

$home_template = file_get_contents( $theme_dir . '/home.php' );
assertCondition( strpos( $home_template, 'cdt_get_ai_section_url' ) !== false, "home.php features prominent AI section badge" );

$front_template = file_get_contents( $theme_dir . '/front-page.php' );
assertCondition( strpos( $front_template, 'category__not_in' ) !== false, "front-page.php excludes courses from recent blog articles" );

$single_template = file_get_contents( $theme_dir . '/single.php' );
assertCondition( strpos( $single_template, 'category__not_in' ) !== false, "single.php prevents general posts from pulling course lessons into related posts" );

$breadcrumbs = file_get_contents( $theme_dir . '/inc/breadcrumbs.php' );
assertCondition( strpos( $breadcrumbs, 'cdt_get_display_category' ) !== false, "breadcrumbs.php respects display category without leaking masked AI term" );

$setup = file_get_contents( $theme_dir . '/inc/theme-setup.php' );
assertCondition( strpos( $setup, 'cdt_filter_main_blog_query' ) !== false, "theme-setup.php has pre_get_posts filter for main blog query" );

$redirects = file_get_contents( $theme_dir . '/inc/redirects.php' );
assertCondition( strpos( $redirects, '%d8%b4%d8%b1%d9%88%d8%b7-%d8%a7%d9%84%d8%a7%d8%b3%d8%aa%d8%ae%d8%af%d8%a7%d9%85' ) !== false, "redirects.php has correct encoding for terms-of-use redirect destination" );

$primary_nav = file_get_contents( $theme_dir . '/template-parts/header/primary-nav.php' );
assertCondition( strpos( $primary_nav, 'الذكاء الاصطناعي' ) !== false, "primary-nav.php contains AI section link" );

$mobile_nav = file_get_contents( $theme_dir . '/template-parts/header/mobile-nav.php' );
assertCondition( strpos( $mobile_nav, 'الذكاء الاصطناعي' ) !== false, "mobile-nav.php contains AI section link" );

$footer_widgets = file_get_contents( $theme_dir . '/template-parts/footer/footer-widgets.php' );
assertCondition( strpos( $footer_widgets, 'قسم الذكاء الاصطناعي' ) !== false, "footer-widgets.php contains AI section link" );

$cat9_content = file_get_contents( $theme_dir . '/category-9.php' );
assertCondition( strpos( $cat9_content, 'cdt_get_ai_child_categories' ) !== false, "category-9.php queries dynamic AI child categories" );
assertCondition( strpos( $cat9_content, 'cdt-ai-subnav' ) !== false, "category-9.php renders dynamic AI subcategories navigation" );

$tmpl_ai_content = file_get_contents( $theme_dir . '/template-ai.php' );
assertCondition( strpos( $tmpl_ai_content, 'cdt_get_ai_child_categories' ) !== false, "template-ai.php queries dynamic AI child categories" );
assertCondition( strpos( $tmpl_ai_content, 'cdt-ai-subnav' ) !== false, "template-ai.php renders dynamic AI subcategories navigation" );

assertCondition( strpos( $setup, 'cdt_is_ai_category_or_child' ) !== false, "theme-setup.php routes child categories of AI to AI template" );

echo "\n==================================================\n";
echo "SUMMARY RESULTS\n";
echo "==================================================\n";
echo "Total Passed: $passCount\n";
echo "Total Failed: $failCount\n";

if ( $failCount === 0 ) {
	echo "\n>>> ALL VERIFICATION TESTS PASSED SUCCESSFULLY! <<<\n";
	exit( 0 );
} else {
	echo "\n>>> SOME VERIFICATION TESTS FAILED! <<<\n";
	exit( 1 );
}
