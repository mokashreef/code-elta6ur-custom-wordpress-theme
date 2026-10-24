<?php
/**
 * نظام صحة السيو والأرشفة - ملف التهيئة الرئيسي
 * Code Elta6ur SEO & Indexing Health Engine - Bootstrap
 *
 * @package CodeElta6ur
 * @subpackage SEO_Health
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'CDT_SEO_HEALTH_VERSION' ) ) {
	define( 'CDT_SEO_HEALTH_VERSION', '1.0.0' );
}

if ( ! defined( 'CDT_SEO_HEALTH_DIR' ) ) {
	define( 'CDT_SEO_HEALTH_DIR', get_template_directory() . '/inc/seo-health' );
}

if ( ! defined( 'CDT_SEO_HEALTH_URI' ) ) {
	define( 'CDT_SEO_HEALTH_URI', get_template_directory_uri() . '/inc/seo-health' );
}

/**
 * Detect Active Third-Party SEO Plugins to prevent conflicts and tag duplication
 *
 * @return string 'yoast' | 'rank_math' | 'seopress' | 'aioseo' | 'none'
 */
function cdt_seo_detect_active_plugin() {
	if ( defined( 'WPSEO_VERSION' ) ) {
		return 'yoast';
	}
	if ( class_exists( 'RankMath' ) || defined( 'RANK_MATH_VERSION' ) ) {
		return 'rank_math';
	}
	if ( defined( 'SEOPRESS_VERSION' ) ) {
		return 'seopress';
	}
	if ( defined( 'AIOSEO_VERSION' ) ) {
		return 'aioseo';
	}
	return 'none';
}

/**
 * Initialize Database Tables for Redirects and 404 Log using dbDelta
 */
function cdt_seo_init_tables() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	$redirects_table = $wpdb->prefix . 'seo_redirects';
	$log_404_table   = $wpdb->prefix . 'seo_404_log';

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	// Table 1: Redirects
	$sql_redirects = "CREATE TABLE {$redirects_table} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		source_path varchar(255) NOT NULL,
		target_url text NOT NULL,
		status_code smallint(5) unsigned NOT NULL DEFAULT 301,
		hits bigint(20) unsigned NOT NULL DEFAULT 0,
		created_at datetime NOT NULL,
		source_type varchar(50) NOT NULL DEFAULT 'manual',
		PRIMARY KEY  (id),
		UNIQUE KEY source_path (source_path(191)),
		KEY status_code (status_code),
		KEY source_type (source_type)
	) {$charset_collate};";

	dbDelta( $sql_redirects );

	// Table 2: 404 Log
	$sql_404 = "CREATE TABLE {$log_404_table} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		normalized_path varchar(255) NOT NULL,
		hits bigint(20) unsigned NOT NULL DEFAULT 1,
		last_seen datetime NOT NULL,
		referrer_host varchar(191) NOT NULL DEFAULT '',
		user_agent_type varchar(50) NOT NULL DEFAULT 'other',
		PRIMARY KEY  (id),
		UNIQUE KEY normalized_path (normalized_path(191)),
		KEY hits (hits),
		KEY last_seen (last_seen),
		KEY user_agent_type (user_agent_type)
	) {$charset_collate};";

	dbDelta( $sql_404 );

	// Record schema version
	update_option( 'cdt_seo_db_version', CDT_SEO_HEALTH_VERSION );
}

/**
 * Schedule daily maintenance cron for log rotation and cleanup
 */
function cdt_seo_schedule_maintenance() {
	if ( ! wp_next_scheduled( 'cdt_seo_daily_maintenance_cron' ) ) {
		wp_schedule_event( time(), 'daily', 'cdt_seo_daily_maintenance_cron' );
	}
}
add_action( 'init', 'cdt_seo_schedule_maintenance' );

/**
 * Clean up 404 logs older than 90 days to prevent database bloating
 */
function cdt_seo_run_maintenance() {
	global $wpdb;
	$log_table = $wpdb->prefix . 'seo_404_log';

	// Check table existence before querying
	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $log_table ) ) === $log_table ) {
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$log_table} WHERE last_seen < %s",
				gmdate( 'Y-m-d H:i:s', strtotime( '-90 days' ) )
			)
		);
	}
}
add_action( 'cdt_seo_daily_maintenance_cron', 'cdt_seo_run_maintenance' );

// Load Submodules
require_once CDT_SEO_HEALTH_DIR . '/indexing-policy.php';
require_once CDT_SEO_HEALTH_DIR . '/redirects.php';
require_once CDT_SEO_HEALTH_DIR . '/404-log.php';
require_once CDT_SEO_HEALTH_DIR . '/slug-repair.php';
require_once CDT_SEO_HEALTH_DIR . '/canonical-urls.php';
require_once CDT_SEO_HEALTH_DIR . '/robots.php';
require_once CDT_SEO_HEALTH_DIR . '/server-health.php';
require_once CDT_SEO_HEALTH_DIR . '/thin-pages.php';
require_once CDT_SEO_HEALTH_DIR . '/quality-signals.php';

if ( is_admin() ) {
	require_once CDT_SEO_HEALTH_DIR . '/admin-dashboard.php';
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once CDT_SEO_HEALTH_DIR . '/cli-commands.php';
}
