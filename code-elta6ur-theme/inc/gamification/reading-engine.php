<?php
/**
 * محرك مكافآت قراءة المقالات والتحقق من الاستيعاب
 * Code Elta6ur Article Reading Progress & Verification Engine
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * فحص أهلية المقال لمكافأة القراءة
 *
 * @param int $post_id معرف المقال.
 * @return bool
 */
function cdt_is_post_eligible_for_reading_reward( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post || 'post' !== $post->post_type || 'publish' !== $post->post_status ) {
		return false;
	}

	// استبعاد صفحات تسجيل الدخول أو الصفحات الخاصة
	if ( is_admin() ) {
		return false;
	}

	return true;
}

/**
 * فحص ما إذا كان المستخدم قد كسب مكافأة هذا المقال مسبقاً
 *
 * @param int $user_id معرف المستخدم.
 * @param int $post_id معرف المقال.
 * @return bool
 */
function cdt_has_user_completed_reading( $user_id, $post_id ) {
	global $wpdb;
	$table_ledger    = $wpdb->prefix . 'cdt_points_ledger';
	$idempotency_key = "read_article_{$user_id}_{$post_id}";

	$exists = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT id FROM {$table_ledger} WHERE idempotency_key = %s LIMIT 1",
			$idempotency_key
		)
	);

	return ! empty( $exists );
}

/**
 * معالجة نبضة قراءة المقال (Heartbeat Ping) والتحقق من اكتمال الشروط من الخادم
 *
 * @param int $user_id معرف المستخدم.
 * @param int $post_id معرف المقال.
 * @param int $scroll_percent نسبة التمرير المرسلة من المتصفح (0 - 100).
 * @param int $client_seconds_elapsed الثواني المقترحة من المتصفح للنبضة الحالية.
 * @return array
 */
function cdt_process_reading_heartbeat( $user_id, $post_id, $scroll_percent, $client_seconds_elapsed ) {
	$user_id = absint( $user_id );
	$post_id = absint( $post_id );

	if ( ! $user_id || ! $post_id ) {
		return array( 'success' => false, 'error' => 'invalid_params' );
	}

	if ( ! cdt_is_post_eligible_for_reading_reward( $post_id ) ) {
		return array( 'success' => false, 'error' => 'not_eligible' );
	}

	// إذا كان قد حصل على النقاط مسبقاً
	if ( cdt_has_user_completed_reading( $user_id, $post_id ) ) {
		return array(
			'success'          => true,
			'already_rewarded' => true,
			'completed'        => true,
			'points'           => 0,
		);
	}

	// جلب جلسة القراءة المخزنة مؤقتاً في Transients لمنع التلاعب وحفظ حالة التراكم
	$transient_key = "cdt_reading_{$user_id}_{$post_id}";
	$session_data  = get_transient( $transient_key );

	$now = time();

	if ( ! is_array( $session_data ) ) {
		$session_data = array(
			'start_time'        => $now,
			'last_ping'         => $now,
			'accumulated_secs'  => 0,
			'max_scroll'        => 0,
		);
	}

	// تقييد الزيادة الزمنية: الخادم لا يعتمد أكثر من 25 ثانية لكل نبضة دورية (15-20 ثانية)
	$time_diff = $now - (int) $session_data['last_ping'];
	$safe_increment = min( 25, max( 0, min( $time_diff, (int) $client_seconds_elapsed ) ) );

	$session_data['accumulated_secs'] += $safe_increment;
	$session_data['last_ping']         = $now;
	$session_data['max_scroll']        = max( (int) $session_data['max_scroll'], min( 100, max( 0, (int) $scroll_percent ) ) );

	// حفظ الجلسة لمدة 2 ساعة
	set_transient( $transient_key, $session_data, 2 * HOUR_IN_SECONDS );

	// حساب الحد الأدنى المطلوب بناءً على طول المقال
	$min_seconds = (int) cdt_get_gamification_setting( 'article_read_min_seconds', 30 );
	$min_scroll  = (int) cdt_get_gamification_setting( 'article_read_min_scroll', 80 );

	// إذا كان المقال قصيراً جداً (أقل من 150 كلمة)، خفض المهلة إلى 15 ثانية كحد أدنى
	$word_count = count( preg_split( '/\s+/u', wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) ) );
	if ( $word_count < 150 ) {
		$min_seconds = min( $min_seconds, 15 );
	}

	$is_time_met   = ( $session_data['accumulated_secs'] >= $min_seconds );
	$is_scroll_met = ( $session_data['max_scroll'] >= $min_scroll );

	if ( $is_time_met && $is_scroll_met ) {
		// تم استيفاء الشروط! منح النقاط بشكل نهائي وذري
		$idempotency_key = "read_article_{$user_id}_{$post_id}";
		$award_res       = cdt_award_points(
			$user_id,
			'read_article',
			null,
			$post_id,
			$idempotency_key,
			array(
				'reading_seconds' => $session_data['accumulated_secs'],
				'scroll_percent'  => $session_data['max_scroll'],
			)
		);

		// تنظيف الجلسة المؤقتة
		delete_transient( $transient_key );

		// فحص إنجاز قراءة 5 مقالات مختلفة
		cdt_check_articles_milestone( $user_id );

		return array(
			'success'          => true,
			'completed'        => true,
			'already_rewarded' => false,
			'points'           => isset( $award_res['points_added'] ) ? $award_res['points_added'] : 0,
			'message'          => isset( $award_res['message'] ) ? $award_res['message'] : __( 'أحسنت! أكملت قراءة المقال وكسبت النقاط.', 'code-elta6ur' ),
			'new_balance'      => isset( $award_res['new_balance'] ) ? $award_res['new_balance'] : cdt_get_user_points( $user_id ),
		);
	}

	return array(
		'success'          => true,
		'completed'        => false,
		'accumulated_secs' => $session_data['accumulated_secs'],
		'required_secs'    => $min_seconds,
		'max_scroll'       => $session_data['max_scroll'],
		'required_scroll'  => $min_scroll,
	);
}

/**
 * فحص مكافأة الوصول إلى 5 مقالات مختلفة مكتملة
 *
 * @param int $user_id معرف المستخدم.
 */
function cdt_check_articles_milestone( $user_id ) {
	global $wpdb;
	$table_ledger = $wpdb->prefix . 'cdt_points_ledger';

	// عد المقالات الفريدة المكتملة
	$unique_articles = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(DISTINCT reference_id) FROM {$table_ledger} WHERE user_id = %d AND event_type = 'read_article'",
			$user_id
		)
	);

	if ( $unique_articles >= 5 ) {
		$milestone_key = "articles_milestone_5_{$user_id}";
		cdt_award_points( $user_id, 'articles_milestone_5', null, 5, $milestone_key, array( 'articles_count' => $unique_articles ) );
	}
}

/**
 * الحصول على عدد المقالات المكتملة للمستخدم
 *
 * @param int $user_id معرف المستخدم.
 * @return int
 */
function cdt_get_user_completed_articles_count( $user_id ) {
	global $wpdb;
	$table_ledger = $wpdb->prefix . 'cdt_points_ledger';

	$count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(DISTINCT reference_id) FROM {$table_ledger} WHERE user_id = %d AND event_type = 'read_article'",
			$user_id
		)
	);

	return absint( $count );
}
