<?php
/**
 * محرك المستويات والشارات والإنجازات
 * Code Elta6ur Levels & Badges Engine
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * الحصول على مصفوفة المستويات المعتمدة في المنصة
 *
 * @return array
 */
function cdt_get_levels_catalog() {
	$custom_levels = get_option( 'cdt_levels_config', array() );
	if ( ! empty( $custom_levels ) && is_array( $custom_levels ) ) {
		return $custom_levels;
	}

	return array(
		1 => array(
			'level'       => 1,
			'title'       => __( 'مبتدئ', 'code-elta6ur' ),
			'points_min'  => 0,
			'points_max'  => 99,
			'badge_icon'  => '🌱',
			'description' => __( 'البداية الأولى في رحلة البرمجة والتعلم التقني.', 'code-elta6ur' ),
		),
		2 => array(
			'level'       => 2,
			'title'       => __( 'مستكشف', 'code-elta6ur' ),
			'points_min'  => 100,
			'points_max'  => 299,
			'badge_icon'  => '🧭',
			'description' => __( 'استكشاف لغات البرمجة وتطبيق الأساسيات.', 'code-elta6ur' ),
		),
		3 => array(
			'level'       => 3,
			'title'       => __( 'متعلم نشط', 'code-elta6ur' ),
			'points_min'  => 300,
			'points_max'  => 699,
			'badge_icon'  => '⚡',
			'description' => __( 'التزام متواصل بالدروس وقراءة المقالات التخصصية.', 'code-elta6ur' ),
		),
		4 => array(
			'level'       => 4,
			'title'       => __( 'مبرمج مثابر', 'code-elta6ur' ),
			'points_min'  => 700,
			'points_max'  => 1499,
			'badge_icon'  => '💻',
			'description' => __( 'إتقان المسارات وحل المشاكل وكتابة الأكواد باحتراف.', 'code-elta6ur' ),
		),
		5 => array(
			'level'       => 5,
			'title'       => __( 'خبير التطور', 'code-elta6ur' ),
			'points_min'  => 1500,
			'points_max'  => null,
			'badge_icon'  => '👑',
			'description' => __( 'أعلى رتبة برمجية في مجتمع كود التطور.', 'code-elta6ur' ),
		),
	);
}

/**
 * حساب المستوى الحالي للمستخدم ومقدار التقدم نحو المستوى التالي
 *
 * @param int $user_id معرف المستخدم.
 * @return array
 */
function cdt_get_user_level_info( $user_id ) {
	$total_points = cdt_get_user_total_earned_points( $user_id );
	$levels       = cdt_get_levels_catalog();

	$current_level = 1;
	$level_info    = $levels[1];

	foreach ( $levels as $lvl_num => $data ) {
		if ( $total_points >= $data['points_min'] ) {
			$current_level = $lvl_num;
			$level_info    = $data;
		}
	}

	$next_level_num  = $current_level + 1;
	$has_next_level  = isset( $levels[ $next_level_num ] );
	$next_level_data = $has_next_level ? $levels[ $next_level_num ] : null;

	$progress_percent = 100;
	$points_needed    = 0;

	if ( $has_next_level ) {
		$min_pts          = $level_info['points_min'];
		$target_pts       = $next_level_data['points_min'];
		$points_in_level  = max( 0, $total_points - $min_pts );
		$level_span       = max( 1, $target_pts - $min_pts );
		$progress_percent = min( 100, max( 0, round( ( $points_in_level / $level_span ) * 100 ) ) );
		$points_needed    = max( 0, $target_pts - $total_points );
	}

	return array(
		'level'            => $current_level,
		'title'            => $level_info['title'],
		'badge_icon'       => $level_info['badge_icon'],
		'description'      => $level_info['description'],
		'total_points'     => $total_points,
		'progress_percent' => $progress_percent,
		'next_level'       => $next_level_data ? $next_level_data['title'] : null,
		'points_needed'    => $points_needed,
	);
}

/**
 * دليل الشارات والإنجازات المتاحة في المنصة
 *
 * @return array
 */
function cdt_get_badges_catalog() {
	return array(
		'first_article' => array(
			'key'         => 'first_article',
			'title'       => __( 'قارئ شغوف', 'code-elta6ur' ),
			'description' => __( 'أكملت قراءة أول مقال تقني في المنصة.', 'code-elta6ur' ),
			'icon'        => '📖',
			'category'    => 'reading',
		),
		'first_lesson'  => array(
			'key'         => 'first_lesson',
			'title'       => __( 'بداية المبرمج', 'code-elta6ur' ),
			'description' => __( 'أكملت أول درس تدريبي في الكورسات البرمجية.', 'code-elta6ur' ),
			'icon'        => '🚀',
			'category'    => 'courses',
		),
		'articles_10'   => array(
			'key'         => 'articles_10',
			'title'       => __( 'موسوعة المعرفة', 'code-elta6ur' ),
			'description' => __( 'أتممت قراءة 10 مقالات تعليمية مختلفة بنجاح.', 'code-elta6ur' ),
			'icon'        => '📚',
			'category'    => 'reading',
		),
		'lessons_10'    => array(
			'key'         => 'lessons_10',
			'title'       => __( 'خطوات الاحتراف', 'code-elta6ur' ),
			'description' => __( 'أكملت 10 دروس تدريبية في مسارات كود التطور.', 'code-elta6ur' ),
			'icon'        => '🎓',
			'category'    => 'courses',
		),
		'streak_3'      => array(
			'key'         => 'streak_3',
			'title'       => __( 'شعلة الالتزام (3 أيام)', 'code-elta6ur' ),
			'description' => __( 'حافظت على الاستمرارية اليومية لمدة 3 أيام متتالية.', 'code-elta6ur' ),
			'icon'        => '🔥',
			'category'    => 'streak',
		),
		'streak_7'      => array(
			'key'         => 'streak_7',
			'title'       => __( 'عزيمة أسبوع (7 أيام)', 'code-elta6ur' ),
			'description' => __( 'استمررت في التعلم والنشاط لمدة أسبوع كامل بلا انقطاع.', 'code-elta6ur' ),
			'icon'        => '⚡',
			'category'    => 'streak',
		),
		'streak_30'     => array(
			'key'         => 'streak_30',
			'title'       => __( 'أسطورة الاستمرار (30 يوماً)', 'code-elta6ur' ),
			'description' => __( 'إنجاز استثنائي: شهر كامل من الاستمرارية والتعلم اليومي.', 'code-elta6ur' ),
			'icon'        => '🏆',
			'category'    => 'streak',
		),
		'level_2'       => array(
			'key'         => 'level_2',
			'title'       => __( 'وسام المستكشف', 'code-elta6ur' ),
			'description' => __( 'وصلت بنجاح إلى المستوى الثاني (مستكشف).', 'code-elta6ur' ),
			'icon'        => '🧭',
			'category'    => 'levels',
		),
		'level_3'       => array(
			'key'         => 'level_3',
			'title'       => __( 'وسام المتعلم النشط', 'code-elta6ur' ),
			'description' => __( 'وصلت بنجاح إلى المستوى الثالث (متعلم نشط).', 'code-elta6ur' ),
			'icon'        => '🌟',
			'category'    => 'levels',
		),
		'level_4'       => array(
			'key'         => 'level_4',
			'title'       => __( 'وسام المبرمج المثابر', 'code-elta6ur' ),
			'description' => __( 'وصلت بنجاح إلى المستوى الرابع (مبرمج مثابر).', 'code-elta6ur' ),
			'icon'        => '💻',
			'category'    => 'levels',
		),
		'level_5'       => array(
			'key'         => 'level_5',
			'title'       => __( 'وسام خبير التطور', 'code-elta6ur' ),
			'description' => __( 'حققت أعلى مرتبة في المنصة ووصلت للمستوى الخامس.', 'code-elta6ur' ),
			'icon'        => '👑',
			'category'    => 'levels',
		),
	);
}

/**
 * منح شارة للمستخدم وحفظها في قاعدة البيانات
 *
 * @param int    $user_id معرف المستخدم.
 * @param string $badge_key مفتاح الشارة.
 * @param array  $metadata بيانات إضافية.
 * @return bool
 */
function cdt_award_badge( $user_id, $badge_key, $metadata = array() ) {
	global $wpdb;

	$user_id   = absint( $user_id );
	$badge_key = sanitize_key( $badge_key );

	if ( ! $user_id || empty( $badge_key ) ) {
		return false;
	}

	$catalog = cdt_get_badges_catalog();
	if ( ! isset( $catalog[ $badge_key ] ) ) {
		return false;
	}

	$table_badges = $wpdb->prefix . 'cdt_user_badges';

	$exists = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT id FROM {$table_badges} WHERE user_id = %d AND badge_key = %s LIMIT 1",
			$user_id,
			$badge_key
		)
	);

	if ( $exists ) {
		return false;
	}

	$now_mysql = function_exists( 'current_time' ) ? current_time( 'mysql' ) : gmdate( 'Y-m-d H:i:s' );
	$meta_json = ! empty( $metadata ) ? wp_json_encode( $metadata ) : null;

	$inserted = $wpdb->insert(
		$table_badges,
		array(
			'user_id'   => $user_id,
			'badge_key' => $badge_key,
			'earned_at' => $now_mysql,
			'meta_data' => $meta_json,
		),
		array( '%d', '%s', '%s', '%s' )
	);

	if ( $inserted ) {
		do_action( 'cdt_badge_unlocked', $user_id, $badge_key, $catalog[ $badge_key ] );
		return true;
	}

	return false;
}

/**
 * فحص شروط جميع الشارات للمستخدم ومنح المستحق منها تلقائياً
 *
 * @param int $user_id معرف المستخدم.
 */
function cdt_evaluate_user_badges( $user_id ) {
	$user_id = absint( $user_id );
	if ( ! $user_id ) {
		return;
	}

	// 1. فحص شارات المقالات
	$articles_count = cdt_get_user_completed_articles_count( $user_id );
	if ( $articles_count >= 1 ) {
		cdt_award_badge( $user_id, 'first_article' );
	}
	if ( $articles_count >= 10 ) {
		cdt_award_badge( $user_id, 'articles_10' );
	}

	// 2. فحص شارات الدروس
	$lessons_count = cdt_get_user_completed_lessons_count( $user_id );
	if ( $lessons_count >= 1 ) {
		cdt_award_badge( $user_id, 'first_lesson' );
	}
	if ( $lessons_count >= 10 ) {
		cdt_award_badge( $user_id, 'lessons_10' );
	}

	// 3. فحص شارات الـ Streak
	$streak_info = cdt_get_user_streak( $user_id );
	if ( $streak_info['longest_streak'] >= 3 ) {
		cdt_award_badge( $user_id, 'streak_3' );
	}
	if ( $streak_info['longest_streak'] >= 7 ) {
		cdt_award_badge( $user_id, 'streak_7' );
	}
	if ( $streak_info['longest_streak'] >= 30 ) {
		cdt_award_badge( $user_id, 'streak_30' );
	}

	// 4. فحص شارات المستوى
	$lvl_info = cdt_get_user_level_info( $user_id );
	if ( $lvl_info['level'] >= 2 ) {
		cdt_award_badge( $user_id, 'level_2' );
	}
	if ( $lvl_info['level'] >= 3 ) {
		cdt_award_badge( $user_id, 'level_3' );
	}
	if ( $lvl_info['level'] >= 4 ) {
		cdt_award_badge( $user_id, 'level_4' );
	}
	if ( $lvl_info['level'] >= 5 ) {
		cdt_award_badge( $user_id, 'level_5' );
	}
}
add_action( 'cdt_points_awarded', 'cdt_evaluate_user_badges', 10, 1 );

/**
 * الحصول على قائمة كاملة بجميع الشارات مع حالة كل شارة للمستخدم (مكتسبة / مقفلة)
 *
 * @param int $user_id معرف المستخدم.
 * @return array
 */
function cdt_get_user_badges_status( $user_id ) {
	global $wpdb;

	$user_id      = absint( $user_id );
	$catalog      = cdt_get_badges_catalog();
	$table_badges = $wpdb->prefix . 'cdt_user_badges';

	$earned_raw = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT badge_key, earned_at FROM {$table_badges} WHERE user_id = %d",
			$user_id
		),
		ARRAY_A
	);

	$earned_map = array();
	if ( ! empty( $earned_raw ) && is_array( $earned_raw ) ) {
		foreach ( $earned_raw as $er ) {
			$earned_map[ $er['badge_key'] ] = $er['earned_at'];
		}
	}

	$results = array();
	foreach ( $catalog as $key => $badge ) {
		$is_unlocked = isset( $earned_map[ $key ] );
		$results[ $key ] = array_merge(
			$badge,
			array(
				'unlocked'  => $is_unlocked,
				'earned_at' => $is_unlocked ? $earned_map[ $key ] : null,
			)
		);
	}

	return $results;
}
