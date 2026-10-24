<?php
/**
 * بنية جداول قاعدة البيانات لنظام النقاط والتحفيز والتقدم التعليمي
 * Code Elta6ur Gamification & Learning Progress DB Schema
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CDT_GAMIFICATION_DB_VERSION', '1.0.0' );

/**
 * إنشاء وتحديث جداول قاعدة البيانات للنظام
 */
function cdt_gamification_install_tables() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	// 1. جدول سجل النقاط (Point Ledger)
	$table_ledger = $wpdb->prefix . 'cdt_points_ledger';
	$sql_ledger   = "CREATE TABLE {$table_ledger} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		user_id bigint(20) unsigned NOT NULL,
		event_type varchar(60) NOT NULL,
		points int(11) NOT NULL,
		reference_id bigint(20) unsigned NOT NULL DEFAULT 0,
		idempotency_key varchar(191) NOT NULL,
		meta_data longtext DEFAULT NULL,
		created_at datetime NOT NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY uq_idempotency (idempotency_key),
		KEY ix_user_event (user_id, event_type),
		KEY ix_created_at (created_at)
	) {$charset_collate};";

	// 2. جدول تقدم التعلم وإكمال الدروس (Learning Progress)
	$table_progress = $wpdb->prefix . 'cdt_learning_progress';
	$sql_progress   = "CREATE TABLE {$table_progress} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		user_id bigint(20) unsigned NOT NULL,
		post_id bigint(20) unsigned NOT NULL,
		course_slug varchar(100) NOT NULL DEFAULT '',
		status varchar(20) NOT NULL DEFAULT 'in_progress',
		progress_percent int(10) unsigned NOT NULL DEFAULT 0,
		completed_at datetime DEFAULT NULL,
		updated_at datetime NOT NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY uq_user_post (user_id, post_id),
		KEY ix_user_course (user_id, course_slug)
	) {$charset_collate};";

	// 3. جدول الشارات والإنجازات المكتسبة (User Badges)
	$table_badges = $wpdb->prefix . 'cdt_user_badges';
	$sql_badges   = "CREATE TABLE {$table_badges} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		user_id bigint(20) unsigned NOT NULL,
		badge_key varchar(60) NOT NULL,
		earned_at datetime NOT NULL,
		meta_data text DEFAULT NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY uq_user_badge (user_id, badge_key),
		KEY ix_user (user_id)
	) {$charset_collate};";

	// 4. جدول جلسات الوقت النشط اليومي (Active Time Sessions)
	$table_sessions = $wpdb->prefix . 'cdt_user_sessions';
	$sql_sessions   = "CREATE TABLE {$table_sessions} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		user_id bigint(20) unsigned NOT NULL,
		session_date date NOT NULL,
		active_seconds int(10) unsigned NOT NULL DEFAULT 0,
		last_heartbeat datetime NOT NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY uq_user_date (user_id, session_date)
	) {$charset_collate};";

	if ( file_exists( ABSPATH . 'wp-admin/includes/upgrade.php' ) ) {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql_ledger );
		dbDelta( $sql_progress );
		dbDelta( $sql_badges );
		dbDelta( $sql_sessions );
	} else {
		// Fallback for custom environments or CLI test harnesses
		$wpdb->query( $sql_ledger );
		$wpdb->query( $sql_progress );
		$wpdb->query( $sql_badges );
		$wpdb->query( $sql_sessions );
	}

	update_option( 'cdt_gamification_db_version', CDT_GAMIFICATION_DB_VERSION );
}

/**
 * فحص وتحديث الجداول عند إطلاق القالب أو تحديث الإصدار
 */
function cdt_gamification_check_db_update() {
	$current_ver = get_option( 'cdt_gamification_db_version', '0' );
	if ( version_compare( $current_ver, CDT_GAMIFICATION_DB_VERSION, '<' ) ) {
		cdt_gamification_install_tables();
	}
}
add_action( 'after_setup_theme', 'cdt_gamification_check_db_update' );
