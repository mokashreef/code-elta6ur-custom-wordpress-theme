<?php
/**
 * محرك النقاط المركزي وسجل المعاملات
 * Code Elta6ur Central Points Ledger & Gamification Engine
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * الحصول على إعدادات نظام النقاط مع القيم الافتراضية
 *
 * @param string $key اسم الإعداد.
 * @param mixed  $default القيمة الافتراضية.
 * @return mixed
 */
function cdt_get_gamification_setting( $key, $default = null ) {
	$defaults = array(
		'points_daily_login'        => 5,
		'points_read_article'       => 3,
		'points_complete_lesson'    => 10,
		'points_articles_milestone' => 10,
		'points_first_lesson_daily' => 5,
		'points_streak_3'           => 10,
		'points_streak_7'           => 25,
		'points_streak_30'          => 100,
		'points_time_spent'         => 1,
		'time_spent_slot_minutes'   => 5,
		'time_spent_daily_cap'      => 12,
		'article_read_min_seconds'  => 30,
		'article_read_min_scroll'   => 80,
		'enabled_daily_login'       => 1,
		'enabled_read_article'      => 1,
		'enabled_complete_lesson'   => 1,
		'enabled_streak'            => 1,
		'enabled_time_spent'        => 1,
	);

	$settings = get_option( 'cdt_gamification_settings', array() );
	if ( ! is_array( $settings ) ) {
		$settings = array();
	}

	if ( array_key_exists( $key, $settings ) ) {
		return $settings[ $key ];
	}

	if ( array_key_exists( $key, $defaults ) ) {
		return $defaults[ $key ];
	}

	return $default;
}

/**
 * الترجمة والدلالة العربية لأنواع أحداث النقاط
 *
 * @param string $event_type نوع الحدث.
 * @return string
 */
function cdt_get_event_type_label( $event_type ) {
	$labels = array(
		'daily_login'           => __( 'تسجيل الدخول اليومي', 'code-elta6ur' ),
		'read_article'          => __( 'قراءة مقال تعليمي', 'code-elta6ur' ),
		'complete_lesson'       => __( 'إكمال درس تدريبي', 'code-elta6ur' ),
		'articles_milestone_5'  => __( 'إنجاز قراءة 5 مقالات متنوعة', 'code-elta6ur' ),
		'first_lesson_daily'    => __( 'إكمال أول درس في اليوم', 'code-elta6ur' ),
		'streak_3'              => __( 'استمرارية 3 أيام متتالية (Streak)', 'code-elta6ur' ),
		'streak_7'              => __( 'استمرارية 7 أيام متتالية (Streak)', 'code-elta6ur' ),
		'streak_30'             => __( 'أسطورة الاستمرار 30 يوماً متتالية', 'code-elta6ur' ),
		'time_spent'            => __( 'وقت التعلم النشط على المنصة', 'code-elta6ur' ),
		'admin_adjustment'      => __( 'تصحيح إداري للنقاط', 'code-elta6ur' ),
		'course_completed'      => __( 'إكمال مسار تدريبي كامل', 'code-elta6ur' ),
	);

	return isset( $labels[ $event_type ] ) ? $labels[ $event_type ] : __( 'مكافأة نشاط', 'code-elta6ur' );
}

/**
 * منح نقاط للمستخدم وتسجيلها في السجل الذري (Point Ledger)
 *
 * @param int         $user_id معرف المستخدم.
 * @param string      $event_type نوع الحدث.
 * @param int|null    $points عدد النقاط (إذا كان null يُجلب من الإعدادات).
 * @param int         $reference_id معرف العنصر المرتبط (مقال/درس).
 * @param string      $idempotency_key مفتاح منع التكرار.
 * @param array       $metadata بيانات وصفية إضافية.
 * @return array نتيجة العملية.
 */
function cdt_award_points( $user_id, $event_type, $points = null, $reference_id = 0, $idempotency_key = '', $metadata = array() ) {
	global $wpdb;

	$user_id = absint( $user_id );
	if ( ! $user_id ) {
		return array(
			'success' => false,
			'error'   => 'invalid_user',
			'message' => __( 'معرف مستخدم غير صالح.', 'code-elta6ur' ),
		);
	}

	// تحقق من تفعيل نوع المكافأة
	$enabled_key = 'enabled_' . $event_type;
	if ( 0 === (int) cdt_get_gamification_setting( $enabled_key, 1 ) && 'admin_adjustment' !== $event_type ) {
		return array(
			'success' => false,
			'error'   => 'event_disabled',
			'message' => __( 'هذا النوع من المكافآت معطل حالياً.', 'code-elta6ur' ),
		);
	}

	// حساب النقاط
	if ( null === $points ) {
		$setting_map = array(
			'daily_login'          => 'points_daily_login',
			'read_article'         => 'points_read_article',
			'complete_lesson'      => 'points_complete_lesson',
			'articles_milestone_5' => 'points_articles_milestone',
			'first_lesson_daily'   => 'points_first_lesson_daily',
			'streak_3'             => 'points_streak_3',
			'streak_7'             => 'points_streak_7',
			'streak_30'            => 'points_streak_30',
			'time_spent'           => 'points_time_spent',
		);

		$cfg_key = isset( $setting_map[ $event_type ] ) ? $setting_map[ $event_type ] : '';
		$points  = $cfg_key ? (int) cdt_get_gamification_setting( $cfg_key, 0 ) : 0;
	}

	$points = (int) $points;
	if ( 0 === $points ) {
		return array(
			'success' => false,
			'error'   => 'zero_points',
			'message' => __( 'قيمة النقاط الممنوحة هي صفر.', 'code-elta6ur' ),
		);
	}

	// توليد مفتاح Idempotency تلقائي إذا لم يُمرر
	if ( empty( $idempotency_key ) ) {
		$today_date      = function_exists( 'current_time' ) ? current_time( 'Y-m-d' ) : gmdate( 'Y-m-d' );
		$idempotency_key = "{$event_type}_{$user_id}_{$reference_id}_{$today_date}";
	}

	$table_ledger = $wpdb->prefix . 'cdt_points_ledger';

	// التحقق أولاً من السجل لمنع أي تكرار
	$exists = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT id FROM {$table_ledger} WHERE idempotency_key = %s LIMIT 1",
			$idempotency_key
		)
	);

	if ( $exists ) {
		return array(
			'success'         => false,
			'already_awarded' => true,
			'points'          => 0,
			'balance'         => cdt_get_user_points( $user_id ),
			'message'         => __( 'تم الحصول على هذه المكافأة مسبقاً.', 'code-elta6ur' ),
		);
	}

	$created_at = function_exists( 'current_time' ) ? current_time( 'mysql' ) : gmdate( 'Y-m-d H:i:s' );
	$meta_json  = ! empty( $metadata ) ? wp_json_encode( $metadata ) : null;

	// إدراج ذري في قاعدة البيانات
	$inserted = $wpdb->insert(
		$table_ledger,
		array(
			'user_id'         => $user_id,
			'event_type'      => sanitize_key( $event_type ),
			'points'          => $points,
			'reference_id'    => absint( $reference_id ),
			'idempotency_key' => sanitize_text_field( $idempotency_key ),
			'meta_data'       => $meta_json,
			'created_at'      => $created_at,
		),
		array( '%d', '%s', '%d', '%d', '%s', '%s', '%s' )
	);

	if ( false === $inserted ) {
		// في حال فشل الإدراج بسبب تضارب المفتاح الفريد (Race Condition)
		return array(
			'success'         => false,
			'already_awarded' => true,
			'points'          => 0,
			'balance'         => cdt_get_user_points( $user_id ),
			'message'         => __( 'تم تسجيل العملية مسبقاً.', 'code-elta6ur' ),
		);
	}

	$ledger_id = $wpdb->insert_id;

	// تحديث الكاش في user_meta بطريقة ذرية
	$current_balance = (int) get_user_meta( $user_id, 'cdt_points_balance', true );
	$new_balance     = max( 0, $current_balance + $points );
	update_user_meta( $user_id, 'cdt_points_balance', $new_balance );

	if ( $points > 0 ) {
		$total_earned = (int) get_user_meta( $user_id, 'cdt_total_points_earned', true );
		$new_total    = $total_earned + $points;
		update_user_meta( $user_id, 'cdt_total_points_earned', $new_total );
	}

	// إطلاق حدث للأجهزة الأخرى (مثل المستويات والشارات)
	do_action( 'cdt_points_awarded', $user_id, $event_type, $points, $reference_id, $ledger_id );

	return array(
		'success'      => true,
		'ledger_id'    => $ledger_id,
		'points_added' => $points,
		'new_balance'  => $new_balance,
		'event_type'   => $event_type,
		'label'        => cdt_get_event_type_label( $event_type ),
		'message'      => sprintf(
			/* translators: %d: points count, %s: event label */
			__( 'مبروك! كسبت +%1$d نقطة مقابل %2$s.', 'code-elta6ur' ),
			$points,
			cdt_get_event_type_label( $event_type )
		),
	);
}

/**
 * الحصول على رصيد نقاط المستخدم الحالي
 *
 * @param int $user_id معرف المستخدم.
 * @return int
 */
function cdt_get_user_points( $user_id ) {
	$user_id = absint( $user_id );
	if ( ! $user_id ) {
		return 0;
	}

	$balance = get_user_meta( $user_id, 'cdt_points_balance', true );
	if ( '' === $balance || false === $balance ) {
		return cdt_recalculate_user_points( $user_id );
	}

	return (int) $balance;
}

/**
 * الحصول على إجمالي النقاط التراكمية التاريخية للمستخدم (لحساب المستوى)
 *
 * @param int $user_id معرف المستخدم.
 * @return int
 */
function cdt_get_user_total_earned_points( $user_id ) {
	$user_id = absint( $user_id );
	if ( ! $user_id ) {
		return 0;
	}

	$total = get_user_meta( $user_id, 'cdt_total_points_earned', true );
	if ( '' === $total || false === $total ) {
		global $wpdb;
		$table_ledger = $wpdb->prefix . 'cdt_points_ledger';
		$sum_positive = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COALESCE(SUM(points), 0) FROM {$table_ledger} WHERE user_id = %d AND points > 0",
				$user_id
			)
		);
		$total = (int) $sum_positive;
		update_user_meta( $user_id, 'cdt_total_points_earned', $total );
	}

	return (int) $total;
}

/**
 * إعادة حساب وتدقيق رصيد المستخدم من السجل مباشرة
 *
 * @param int $user_id معرف المستخدم.
 * @return int
 */
function cdt_recalculate_user_points( $user_id ) {
	global $wpdb;

	$user_id      = absint( $user_id );
	$table_ledger = $wpdb->prefix . 'cdt_points_ledger';

	$sum_balance = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COALESCE(SUM(points), 0) FROM {$table_ledger} WHERE user_id = %d",
			$user_id
		)
	);

	$sum_total_earned = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COALESCE(SUM(points), 0) FROM {$table_ledger} WHERE user_id = %d AND points > 0",
			$user_id
		)
	);

	$balance      = max( 0, (int) $sum_balance );
	$total_earned = max( 0, (int) $sum_total_earned );

	update_user_meta( $user_id, 'cdt_points_balance', $balance );
	update_user_meta( $user_id, 'cdt_total_points_earned', $total_earned );

	return $balance;
}

/**
 * جلب سجل المعاملات الحديثة للمستخدم
 *
 * @param int $user_id معرف المستخدم.
 * @param int $limit عدد العمليات.
 * @param int $offset الإزاحة.
 * @return array
 */
function cdt_get_user_ledger( $user_id, $limit = 20, $offset = 0 ) {
	global $wpdb;

	$user_id      = absint( $user_id );
	$limit        = max( 1, min( 100, absint( $limit ) ) );
	$offset       = absint( $offset );
	$table_ledger = $wpdb->prefix . 'cdt_points_ledger';

	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$table_ledger} WHERE user_id = %d ORDER BY id DESC LIMIT %d OFFSET %d",
			$user_id,
			$limit,
			$offset
		),
		ARRAY_A
	);

	if ( ! $results ) {
		return array();
	}

	foreach ( $results as &$row ) {
		$row['label'] = cdt_get_event_type_label( $row['event_type'] );
		$row['meta']  = ! empty( $row['meta_data'] ) ? json_decode( $row['meta_data'], true ) : array();
	}

	return $results;
}

/**
 * تصحيح النقاط بواسطة المدير بصلاحيات موثقة
 *
 * @param int    $user_id معرف المستخدم المستهدف.
 * @param int    $points عدد النقاط (موجب للإضافة، سالب للخصم).
 * @param string $reason سبب التعديل الإداري (إلزامي).
 * @param int    $admin_id معرف المدير المنفذ.
 * @return array
 */
function cdt_admin_adjust_points( $user_id, $points, $reason, $admin_id ) {
	$reason = trim( sanitize_text_field( $reason ) );
	if ( empty( $reason ) ) {
		return array(
			'success' => false,
			'message' => __( 'يجب تحديد سبب واضح وموثق للتصحيح الإداري.', 'code-elta6ur' ),
		);
	}

	$rand_suffix     = function_exists( 'wp_generate_password' ) ? wp_generate_password( 6, false ) : bin2hex( random_bytes( 3 ) );
	$idempotency_key = 'admin_adj_' . $user_id . '_' . time() . '_' . $rand_suffix;

	$metadata = array(
		'admin_id'    => absint( $admin_id ),
		'reason'      => $reason,
		'adjusted_at' => function_exists( 'current_time' ) ? current_time( 'mysql' ) : gmdate( 'Y-m-d H:i:s' ),
	);

	return cdt_award_points( $user_id, 'admin_adjustment', $points, 0, $idempotency_key, $metadata );
}
