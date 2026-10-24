<?php
/**
 * المحمل المركزي لنظام الحسابات وتجربة المستخدم
 * Code Elta6ur Accounts Subsystem Loader
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/accounts-router.php';
require_once __DIR__ . '/auth-handlers.php';
