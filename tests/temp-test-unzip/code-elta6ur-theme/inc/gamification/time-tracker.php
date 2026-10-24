<?php
/**
 * محرك احتساب وقت التعلم النشط على المنصة
 * Code Elta6ur Active Learning Time Tracking Engine
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * معالجة نبضة الوقت النشط للمستخدم والتحقق من الاستحقاق
 *
 * @param int $user_id معرف المستخدم.
 * @param int $client_seconds الثواني المقترحة للنبضة الحالية (عادة 15 - 20 ثانية).
 * @return array
 */
function cdt_process_time_heartbeat( $user_id, $client_seconds ) {
	global $wpdb;

	$user_id = absint( $user_id );
	if ( ! $user_id ) {
		return array( 'success' => false, 'error' => 'invalid_user' );
	}

	if ( 0 === (int) cdt_get_gamification_setting( 'enabled_time_spent', 1 ) ) {
		return array( 'success' => false, 'error' => 'disabled' );
	}

	$today          = cdt_get_today_date();
	$table_sessions = $wpdb->prefix . 'cdt_user_sessions';
	$now_mysql      = function_exists( 'current_time' ) ? current_time( 'mysql' ) : gmdate( 'Y-m-d H:i:s' );
	$now_timestamp  = time();

	// جلب جلسة اليوم
	$session = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$table_sessions} WHERE user_id = %d AND session_date = %s LIMIT 1",
			$user_id,
			$today
		)
	);

	$safe_increment = 0;
	if ( $session ) {
		$last_hb_time = strtotime( $session->last_heartbeat );
		$diff         = $now_timestamp - $last_hb_time;
		// حظر القفزات الكبيرة والتلاعب بالوقت: أقصى زيادة مسموحة هي 25 ثانية لكل نبضة
		$safe_increment = min( 25, max( 0, min( $diff, (int) $client_seconds ) ) );
		$new_seconds    = (int) $session->active_seconds + $safe_increment;

		$wpdb->update(
			$table_sessions,
			array(
				'active_seconds' => $new_seconds,
				'last_heartbeat' => $now_mysql,
			),
			array( 'id' => $session->id ),
			array( '%d', '%s' ),
			array( '%d' )
		);
	} else {
		$safe_increment = min( 20, max( 1, (int) $client_seconds ) );
		$new_seconds    = $safe_increment;

		$wpdb->insert(
			$table_sessions,
			array(
				'user_id'        => $user_id,
				'session_date'   => $today,
				'active_seconds' => $new_seconds,
				'last_heartbeat' => $now_mysql,
			),
			array( '%d', '%s', '%d', '%s' )
		);
	}

	// حساب النقاط بناءً على الفترات المنجزة
	$slot_minutes = (int) cdt_get_gamification_setting( 'time_spent_slot_minutes', 5 );
	$slot_seconds = max( 60, $slot_minutes * 60 );
	$daily_cap    = (int) cdt_get_gamification_setting( 'time_spent_daily_cap', 12 );

	$completed_slots = (int) floor( $new_seconds / $slot_seconds );
	$eligible_slots  = min( $daily_cap, $completed_slots );

	$rewards_awarded = array();

	for ( $i = 1; $i <= $eligible_slots; $i++ ) {
		$idempotency_key = "time_spent_{$user_id}_{$today}_slot_{$i}";
		$award_res       = cdt_award_points(
			$user_id,
			'time_spent',
			null,
			$i,
			$idempotency_key,
			array(
				'slot_index'     => $i,
				'active_seconds' => $new_seconds,
			)
		);

		if ( ! empty( $award_res['success'] ) ) {
			$rewards_awarded[] = $award_res;
		}
	}

	return array(
		'success'        => true,
		'active_seconds' => $new_seconds,
		'active_minutes' => round( $new_seconds / 60, 1 ),
		'daily_cap'      => $daily_cap,
		'rewards'        => $rewards_awarded,
	);
}

/**
 * الحصول على إجمالي دقائق النشاط للمستخدم اليوم
 *
 * @param int $user_id معرف المستخدم.
 * @return int
 */
function cdt_get_user_today_active_minutes( $user_id ) {
	global $wpdb;
	$user_id        = absint( $user_id );
	$today          = cdt_get_today_date();
	$table_sessions = $wpdb->prefix . 'cdt_user_sessions';

	$seconds = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT active_seconds FROM {$table_sessions} WHERE user_id = %d AND session_date = %s LIMIT 1",
			$user_id,
			$today
		)
	);

	return (int) round( ( (int) $seconds ) / 60 );
}
