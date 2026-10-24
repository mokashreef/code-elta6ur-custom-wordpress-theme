<?php
/**
 * المحمل المركزي لنظام التحفيز والنقاط والتقدم التعليمي
 * Code Elta6ur Gamification Subsystem Loader
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/db-schema.php';
require_once __DIR__ . '/points-engine.php';
require_once __DIR__ . '/streak-engine.php';
require_once __DIR__ . '/reading-engine.php';
require_once __DIR__ . '/course-progress.php';
require_once __DIR__ . '/time-tracker.php';
require_once __DIR__ . '/badges-levels.php';
require_once __DIR__ . '/ajax-api.php';
