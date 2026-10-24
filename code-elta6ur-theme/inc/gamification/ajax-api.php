<?php
/**
 * نقاط نهاية AJAX الآمنة لنظام التحفيز والوقت والدروس
 * Code Elta6ur Gamification AJAX Handlers
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * معالجة نبضة النشاط والقراءة المشتركة (Heartbeat)
 */
function cdt_ajax_gamification_heartbeat() {
	// التحقق الأمني من الـ Nonce وتوثيق المستخدم
	check_ajax_referer( 'cdt_gamification_nonce', 'nonce' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'غير مسجل الدخول.', 'code-elta6ur' ) ), 401 );
	}

	$user_id = get_current_user_id();

	// Rate Limiting عبر Transients: حد أقصى نبضة واحدة كل 10 ثوانٍ لكل مستخدم
	$rate_key = 'cdt_rate_hb_' . $user_id;
	if ( get_transient( $rate_key ) ) {
		wp_send_json_error( array( 'message' => __( 'تكرار الطلب سريع جداً.', 'code-elta6ur' ) ), 429 );
	}
	set_transient( $rate_key, 1, 10 );

	$post_id     = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
	$scroll_pct  = isset( $_POST['scroll_percent'] ) ? min( 100, max( 0, (int) $_POST['scroll_percent'] ) ) : 0;
	$client_secs = isset( $_POST['elapsed_seconds'] ) ? min( 30, max( 1, (int) $_POST['elapsed_seconds'] ) ) : 15;

	$response = array(
		'success'  => true,
		'rewards'  => array(),
		'reading'  => null,
		'time'     => null,
	);

	// 1. معالجة قراءة المقال إذا كان هناك معرف مقال صالح
	if ( $post_id > 0 ) {
		$reading_res = cdt_process_reading_heartbeat( $user_id, $post_id, $scroll_pct, $client_secs );
		$response['reading'] = $reading_res;

		if ( ! empty( $reading_res['completed'] ) && ! empty( $reading_res['points'] ) ) {
			$response['rewards'][] = array(
				'title'   => __( 'إكمال قراءة مقال', 'code-elta6ur' ),
				'message' => $reading_res['message'],
				'points'  => $reading_res['points'],
			);
		}
	}

	// 2. معالجة وقت النشاط على المنصة
	$time_res = cdt_process_time_heartbeat( $user_id, $client_secs );
	$response['time'] = $time_res;

	if ( ! empty( $time_res['rewards'] ) ) {
		foreach ( $time_res['rewards'] as $rw ) {
			$response['rewards'][] = array(
				'title'   => __( 'وقت التعلم النشط', 'code-elta6ur' ),
				'message' => $rw['message'],
				'points'  => $rw['points_added'],
			);
		}
	}

	$response['balance'] = cdt_get_user_points( $user_id );
	$response['streak']  = cdt_get_user_streak( $user_id );

	wp_send_json_success( $response );
}
add_action( 'wp_ajax_cdt_gamification_heartbeat', 'cdt_ajax_gamification_heartbeat' );

/**
 * معالجة زر إكمال الدرس يدوياً أو بعد إكمال الشرح
 */
function cdt_ajax_complete_lesson() {
	check_ajax_referer( 'cdt_gamification_nonce', 'nonce' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يرجى تسجيل الدخول أولاً.', 'code-elta6ur' ) ), 401 );
	}

	$user_id = get_current_user_id();
	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

	if ( ! $post_id ) {
		wp_send_json_error( array( 'message' => __( 'معرف الدرس غير صالح.', 'code-elta6ur' ) ), 400 );
	}

	$result = cdt_complete_lesson( $user_id, $post_id );

	if ( ! empty( $result['success'] ) ) {
		$result['balance'] = cdt_get_user_points( $user_id );
		wp_send_json_success( $result );
	} else {
		wp_send_json_error( $result, 400 );
	}
}
add_action( 'wp_ajax_cdt_complete_lesson', 'cdt_ajax_complete_lesson' );
