<?php
/**
 * كود التطور - وظائف القالب الأساسية
 * Code Elta6ur Theme Functions & Definitions
 *
 * @package CodeElta6ur
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define Theme Constants
define( 'CDT_THEME_VERSION', '1.0.0' );
define( 'CDT_THEME_DIR', get_template_directory() );
define( 'CDT_THEME_URI', get_template_directory_uri() );

/**
 * Load Modular Theme Components
 */
require_once CDT_THEME_DIR . '/inc/theme-setup.php';
require_once CDT_THEME_DIR . '/inc/helpers.php';
require_once CDT_THEME_DIR . '/inc/breadcrumbs.php';
require_once CDT_THEME_DIR . '/inc/enqueue.php';
require_once CDT_THEME_DIR . '/inc/customizer.php';
require_once CDT_THEME_DIR . '/inc/block-patterns.php';
require_once CDT_THEME_DIR . '/inc/seo-schema.php';
require_once CDT_THEME_DIR . '/inc/compatibility.php';
require_once CDT_THEME_DIR . '/inc/redirects.php';
