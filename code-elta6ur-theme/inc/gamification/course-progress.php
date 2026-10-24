<?php
/**
 * محرك تقدم الكورسات وإكمال الدروس
 * Code Elta6ur Courses & Lessons Progress Engine
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * خريطة الكورسات المعتمدة وتصنيفاتها الرسمية في منصة كود التطور
 *
 * @return array
 */
function cdt_get_courses_catalog() {
	return array(
		'كورس-html' => array(
			'slug'        => 'كورس-html',
			'title'       => __( 'كورس HTML5 التأسيسي', 'code-elta6ur' ),
			'badge'       => 'HTML5',
			'cat_id'      => 53,
			'icon'        => 'code',
			'url'         => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-html/' ),
		),
		'كورس-css' => array(
			'slug'        => 'كورس-css',
			'title'       => __( 'كورس CSS3 وتنسيق الواجهات', 'code-elta6ur' ),
			'badge'       => 'CSS3',
			'cat_id'      => 62,
			'icon'        => 'palette',
			'url'         => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-css/' ),
		),
		'كورس-bootstrap' => array(
			'slug'        => 'كورس-bootstrap',
			'title'       => __( 'شرح Bootstrap 5 لبناء الواجهات', 'code-elta6ur' ),
			'badge'       => 'Bootstrap 5',
			'cat_id'      => 71,
			'icon'        => 'grid',
			'url'         => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-bootstrap/' ),
		),
		'كورس-javascript' => array(
			'slug'        => 'كورس-javascript',
			'title'       => __( 'شرح JavaScript الحديثة من الصفر', 'code-elta6ur' ),
			'badge'       => 'JavaScript ES6+',
			'cat_id'      => 74,
			'icon'        => 'cpu',
			'url'         => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-javascript/' ),
		),
		'كورس-react' => array(
			'slug'        => 'كورس-react',
			'title'       => __( 'تعلم مكتبة React.js وتطبيقات الويب', 'code-elta6ur' ),
			'badge'       => 'React 18',
			'cat_id'      => 76,
			'icon'        => 'layers',
			'url'         => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-react/' ),
		),
		'كورس-c' => array(
			'slug'        => 'كورس-c',
			'title'       => __( 'شرح لغة C++ والتأسيس البرمجي', 'code-elta6ur' ),
			'badge'       => 'C++ Programming',
			'cat_id'      => 80,
			'icon'        => 'terminal',
			'url'         => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-c/' ),
		),
		'typescript' => array(
			'slug'        => 'typescript',
			'title'       => __( 'دورة TypeScript الشاملة من الصفر إلى الاحتراف', 'code-elta6ur' ),
			'badge'       => 'TypeScript',
			'cat_id'      => 119,
			'tag_slug'    => 'typescript',
			'icon'        => 'code',
			'url'         => home_url( '/typescript/' ),
		),
	);
}

/**
 * تحديد الكورس الذي ينتمي إليه منشور/درس معين
 *
 * @param int $post_id معرف المنشور.
 * @return string|null slug الكورس أو null.
 */
function cdt_get_course_slug_for_post( $post_id ) {
	$categories = wp_get_post_categories( $post_id );
	$post_tags  = wp_get_post_tags( $post_id, array( 'fields' => 'slugs' ) );
	if ( is_wp_error( $post_tags ) || ! is_array( $post_tags ) ) {
		$post_tags = array();
	}

	$catalog = cdt_get_courses_catalog();
	foreach ( $catalog as $course_slug => $course_data ) {
		if ( ! empty( $course_data['cat_id'] ) && in_array( $course_data['cat_id'], $categories, true ) ) {
			return $course_slug;
		}
		if ( ! empty( $course_data['tag_slug'] ) && in_array( $course_data['tag_slug'], $post_tags, true ) ) {
			return $course_slug;
		}
	}

	return null;
}

/**
 * تحديد ما إذا كان المنشور درساً تابعاً لأحد الكورسات
 *
 * @param int $post_id معرف المنشور.
 * @return bool
 */
function cdt_is_post_a_course_lesson( $post_id ) {
	return null !== cdt_get_course_slug_for_post( $post_id );
}

/**
 * فحص حالة إكمال الدرس للمستخدم
 *
 * @param int $user_id معرف المستخدم.
 * @param int $post_id معرف الدرس.
 * @return bool
 */
function cdt_is_lesson_completed_by_user( $user_id, $post_id ) {
	global $wpdb;
	$table_progress = $wpdb->prefix . 'cdt_learning_progress';

	$status = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT status FROM {$table_progress} WHERE user_id = %d AND post_id = %d LIMIT 1",
			$user_id,
			$post_id
		)
	);

	return 'completed' === $status;
}

/**
 * إكمال درس تعليمي ومنح النقاط والتحقق من تقدم المسار
 *
 * @param int $user_id معرف المستخدم.
 * @param int $post_id معرف الدرس.
 * @return array
 */
function cdt_complete_lesson( $user_id, $post_id ) {
	global $wpdb;

	$user_id = absint( $user_id );
	$post_id = absint( $post_id );

	if ( ! $user_id || ! $post_id ) {
		return array( 'success' => false, 'error' => 'invalid_params' );
	}

	$course_slug = cdt_get_course_slug_for_post( $post_id );
	if ( ! $course_slug ) {
		$course_slug = 'general';
	}

	$table_progress = $wpdb->prefix . 'cdt_learning_progress';
	$now            = function_exists( 'current_time' ) ? current_time( 'mysql' ) : gmdate( 'Y-m-d H:i:s' );

	// التحقق إذا كان الدرس مكتملاً مسبقاً
	$existing = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT id, status FROM {$table_progress} WHERE user_id = %d AND post_id = %d LIMIT 1",
			$user_id,
			$post_id
		)
	);

	if ( $existing && 'completed' === $existing->status ) {
		return array(
			'success'          => true,
			'already_rewarded' => true,
			'message'          => __( 'لقد أكملت هذا الدرس مسبقاً.', 'code-elta6ur' ),
		);
	}

	if ( $existing ) {
		$wpdb->update(
			$table_progress,
			array(
				'status'           => 'completed',
				'progress_percent' => 100,
				'completed_at'     => $now,
				'updated_at'       => $now,
			),
			array( 'id' => $existing->id ),
			array( '%s', '%d', '%s', '%s' ),
			array( '%d' )
		);
	} else {
		$wpdb->insert(
			$table_progress,
			array(
				'user_id'          => $user_id,
				'post_id'          => $post_id,
				'course_slug'      => $course_slug,
				'status'           => 'completed',
				'progress_percent' => 100,
				'completed_at'     => $now,
				'updated_at'       => $now,
			),
			array( '%d', '%d', '%s', '%s', '%d', '%s', '%s' )
		);
	}

	// حفظ آخر درس تمت زيارته / إكماله
	update_user_meta( $user_id, 'cdt_last_lesson_id', $post_id );
	update_user_meta( $user_id, 'cdt_last_lesson_time', $now );

	$rewards_granted = array();

	// 1. منح نقاط إكمال الدرس الأساسية (10 نقاط)
	$idempotency_key = "complete_lesson_{$user_id}_{$post_id}";
	$award_res       = cdt_award_points( $user_id, 'complete_lesson', null, $post_id, $idempotency_key, array( 'course_slug' => $course_slug ) );
	if ( ! empty( $award_res['success'] ) ) {
		$rewards_granted[] = $award_res;
	}

	// 2. فحص مكافأة أول درس في اليوم (+5 نقاط إضافية)
	$today           = cdt_get_today_date();
	$first_daily_key = "first_lesson_daily_{$user_id}_{$today}";
	$first_res       = cdt_award_points( $user_id, 'first_lesson_daily', null, $post_id, $first_daily_key );
	if ( ! empty( $first_res['success'] ) ) {
		$rewards_granted[] = $first_res;
	}

	// 3. فحص تقدم الكورس بالكامل
	$course_progress = cdt_get_course_completion_stats( $user_id, $course_slug );
	if ( 100 === $course_progress['percent'] ) {
		$course_key = "course_completed_{$user_id}_{$course_slug}";
		cdt_award_points( $user_id, 'course_completed', 50, 0, $course_key, array( 'course_slug' => $course_slug ) );
	}

	return array(
		'success'         => true,
		'completed'       => true,
		'rewards'         => $rewards_granted,
		'course_progress' => $course_progress,
		'message'         => __( 'رائع! أكملت الدرس بنجاح وكسبت نقاط الإنجاز.', 'code-elta6ur' ),
	);
}

/**
 * حساب إحصائيات إكمال كورس معين بدقة من المنشورات الفعلية
 *
 * @param int    $user_id معرف المستخدم.
 * @param string $course_slug اسم الكورس.
 * @return array
 */
function cdt_get_course_completion_stats( $user_id, $course_slug ) {
	global $wpdb;

	$catalog = cdt_get_courses_catalog();
	if ( ! isset( $catalog[ $course_slug ] ) ) {
		return array( 'total' => 0, 'completed' => 0, 'percent' => 0 );
	}

	$course_info = $catalog[ $course_slug ];
	$cat_id      = isset( $course_info['cat_id'] ) ? (int) $course_info['cat_id'] : 0;
	$tag_slug    = isset( $course_info['tag_slug'] ) ? sanitize_title( $course_info['tag_slug'] ) : '';

	// حساب إجمالي الدروس المنشورة في التصنيف أو الوسم
	$query_args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 200,
		'fields'         => 'ids',
	);

	if ( ! empty( $tag_slug ) && $cat_id > 0 ) {
		$query_args['tax_query'] = array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => $tag_slug,
			),
			array(
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => $cat_id,
			),
		);
	} elseif ( ! empty( $tag_slug ) ) {
		$query_args['tag'] = $tag_slug;
	} elseif ( $cat_id > 0 ) {
		$query_args['cat'] = $cat_id;
	} else {
		return array( 'total' => 0, 'completed' => 0, 'percent' => 0 );
	}

	$course_posts  = get_posts( $query_args );
	$total_lessons = count( $course_posts );

	if ( 0 === $total_lessons ) {
		return array( 'total' => 0, 'completed' => 0, 'percent' => 0 );
	}

	// حساب الدروس المكتملة للمستخدم
	$table_progress = $wpdb->prefix . 'cdt_learning_progress';
	$placeholders   = implode( ',', array_fill( 0, count( $course_posts ), '%d' ) );

	$query = $wpdb->prepare(
		"SELECT COUNT(DISTINCT post_id) FROM {$table_progress} 
		 WHERE user_id = %d AND status = 'completed' AND post_id IN ({$placeholders})",
		array_merge( array( $user_id ), $course_posts )
	);

	$completed_count = (int) $wpdb->get_var( $query );
	$percent         = (int) round( ( $completed_count / $total_lessons ) * 100 );

	return array(
		'total'     => $total_lessons,
		'completed' => $completed_count,
		'percent'   => min( 100, $percent ),
	);
}

/**
 * الحصول على إجمالي الدروس المكتملة للمستخدم في كل الكورسات
 *
 * @param int $user_id معرف المستخدم.
 * @return int
 */
function cdt_get_user_completed_lessons_count( $user_id ) {
	global $wpdb;
	$table_progress = $wpdb->prefix . 'cdt_learning_progress';

	$count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(DISTINCT post_id) FROM {$table_progress} WHERE user_id = %d AND status = 'completed'",
			$user_id
		)
	);

	return absint( $count );
}

/**
 * الحصول على آخر درس زاره المستخدم لمواصلة التعلم
 *
 * @param int $user_id معرف المستخدم.
 * @return WP_Post|null
 */
function cdt_get_user_last_lesson( $user_id ) {
	$last_lesson_id = get_user_meta( $user_id, 'cdt_last_lesson_id', true );
	if ( $last_lesson_id ) {
		$post = get_post( $last_lesson_id );
		if ( $post && 'publish' === $post->post_status ) {
			return $post;
		}
	}
	return null;
}
