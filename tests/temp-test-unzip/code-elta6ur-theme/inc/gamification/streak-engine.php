<?php
/**
 * محرك الاستمرارية اليومية وتسجيل الدخول والـ Streak
 * Code Elta6ur Daily Login & Streak Tracking Engine
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * الحصول على تاريخ اليوم الحالي بتوقيت ووردبريس المحلي
 *
 * @return string YYYY-MM-DD
 */
function cdt_get_today_date() {
	if ( function_exists( 'current_time' ) ) {
		return current_time( 'Y-m-d' );
	}
	return gmdate( 'Y-m-d' );
}

/**
 * الحصول على تاريخ يوم أمس بتوقيت ووردبريس المحلي
 *
 * @return string YYYY-MM-DD
 */
function cdt_get_yesterday_date() {
	$today = cdt_get_today_date();
	$dt    = new DateTime( $today, function_exists( 'wp_timezone' ) ? wp_timezone() : new DateTimeZone( 'UTC' ) );
	$dt->modify( '-1 day' );
	return $dt->format( 'Y-m-d' );
}

/**
 * فحص وتحديث الـ Streak ومنح مكافأة تسجيل الدخول اليومي
 *
 * @param int    $user_id معرف المستخدم.
 * @param string $activity_type نوع النشاط المؤهل (افتراضياً: login).
 * @return array
 */
function cdt_process_daily_activity( $user_id, $activity_type = 'login' ) {
	$user_id = absint( $user_id );
	if ( ! $user_id ) {
		return array( 'success' => false, 'error' => 'invalid_user' );
	}

	$today     = cdt_get_today_date();
	$yesterday = cdt_get_yesterday_date();

	$rewards_granted = array();

	// 1. معالجة مكافأة تسجيل الدخول اليومي
	if ( 1 === (int) cdt_get_gamification_setting( 'enabled_daily_login', 1 ) ) {
		$login_key = "daily_login_{$user_id}_{$today}";
		$award_res = cdt_award_points( $user_id, 'daily_login', null, 0, $login_key );
		if ( ! empty( $award_res['success'] ) ) {
			$rewards_granted[] = $award_res;
		}
	}

	// 2. معالجة الـ Streak
	$current_streak   = (int) get_user_meta( $user_id, 'cdt_current_streak', true );
	$longest_streak   = (int) get_user_meta( $user_id, 'cdt_longest_streak', true );
	$last_streak_date = get_user_meta( $user_id, 'cdt_last_streak_date', true );

	$streak_increased = false;
	$new_streak       = $current_streak;

	if ( $last_streak_date === $today ) {
		// النشاط مكرر في نفس اليوم: لا نزيد الـ Streak، نحافظ عليه
		$new_streak = max( 1, $current_streak );
	} elseif ( $last_streak_date === $yesterday ) {
		// نشاط في اليوم التالي مباشرة: استمرار السلسلة
		$new_streak       = $current_streak + 1;
		$streak_increased = true;
	} else {
		// انقطاع أكثر من يوم أو بداية سلسلة جديدة: إعادة الضبط إلى يوم 1
		$new_streak       = 1;
		$streak_increased = true;
	}

	// تحديث أطول سلسلة تاريخية إذا تم تجاوزها
	if ( $new_streak > $longest_streak ) {
		$longest_streak = $new_streak;
		update_user_meta( $user_id, 'cdt_longest_streak', $longest_streak );
	}

	update_user_meta( $user_id, 'cdt_current_streak', $new_streak );
	update_user_meta( $user_id, 'cdt_last_streak_date', $today );

	// 3. فحص إنجازات الـ Streak (3 أيام، 7 أيام، 30 يوماً)
	if ( $streak_increased && 1 === (int) cdt_get_gamification_setting( 'enabled_streak', 1 ) ) {
		if ( 3 === $new_streak ) {
			$milestone_key = "streak_3_{$user_id}_{$today}";
			$res = cdt_award_points( $user_id, 'streak_3', null, 3, $milestone_key );
			if ( ! empty( $res['success'] ) ) {
				$rewards_granted[] = $res;
			}
		} elseif ( 7 === $new_streak ) {
			$milestone_key = "streak_7_{$user_id}_{$today}";
			$res = cdt_award_points( $user_id, 'streak_7', null, 7, $milestone_key );
			if ( ! empty( $res['success'] ) ) {
				$rewards_granted[] = $res;
			}
		} elseif ( 30 === $new_streak ) {
			$milestone_key = "streak_30_{$user_id}_{$today}";
			$res = cdt_award_points( $user_id, 'streak_30', null, 30, $milestone_key );
			if ( ! empty( $res['success'] ) ) {
				$rewards_granted[] = $res;
			}
		}
	}

	return array(
		'success'          => true,
		'current_streak'   => $new_streak,
		'longest_streak'   => $longest_streak,
		'last_streak_date' => $today,
		'streak_increased' => $streak_increased,
		'rewards'          => $rewards_granted,
	);
}

/**
 * الحصول على بيانات الـ Streak للمستخدم مع التحقق المنطقي من الانقطاع
 *
 * @param int $user_id معرف المستخدم.
 * @return array
 */
function cdt_get_user_streak( $user_id ) {
	$user_id = absint( $user_id );
	if ( ! $user_id ) {
		return array(
			'current_streak'   => 0,
			'longest_streak'   => 0,
			'last_streak_date' => '',
			'is_active_today'  => false,
		);
	}

	$current_streak   = (int) get_user_meta( $user_id, 'cdt_current_streak', true );
	$longest_streak   = (int) get_user_meta( $user_id, 'cdt_longest_streak', true );
	$last_streak_date = get_user_meta( $user_id, 'cdt_last_streak_date', true );

	$today     = cdt_get_today_date();
	$yesterday = cdt_get_yesterday_date();

	$is_active_today = ( $last_streak_date === $today );

	// إذا انقضى يوم أمس دون أي نشاط، فإن السلسلة انكسرت منطقياً وتصبح 0 حتى يسجل دخولاً جديداً
	if ( ! empty( $last_streak_date ) && $last_streak_date !== $today && $last_streak_date !== $yesterday ) {
		$effective_streak = 0;
	} else {
		$effective_streak = $current_streak;
	}

	return array(
		'current_streak'   => $effective_streak,
		'raw_streak'       => $current_streak,
		'longest_streak'   => $longest_streak,
		'last_streak_date' => $last_streak_date,
		'is_active_today'  => $is_active_today,
	);
}

/**
 * ربط المعالجة مع حدث تسجيل الدخول الرسمي في ووردبريس (wp_login)
 * يعمل لجميع بوابات الدخول (القالب المخصص، Ultimate Member، ووردبريس الأصلي)
 *
 * @param string  $user_login اسم المستخدم.
 * @param WP_User $user كائن المستخدم.
 */
function cdt_on_user_login( $user_login, $user ) {
	if ( $user instanceof WP_User && $user->ID ) {
		cdt_process_daily_activity( $user->ID, 'login' );
	}
}
add_action( 'wp_login', 'cdt_on_user_login', 10, 2 );
