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
if ( ! defined( 'CDT_THEME_VERSION' ) ) {
	define( 'CDT_THEME_VERSION', '1.2.5' );
}
if ( ! defined( 'CDT_THEME_DIR' ) ) {
	define( 'CDT_THEME_DIR', get_template_directory() );
}
if ( ! defined( 'CDT_THEME_URI' ) ) {
	define( 'CDT_THEME_URI', get_template_directory_uri() );
}

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
require_once CDT_THEME_DIR . '/inc/contact-ajax.php';

// Tools Engine (Phase 1-3)
require_once CDT_THEME_DIR . '/inc/tools/tools-registry.php';
require_once CDT_THEME_DIR . '/inc/tools/tools-router.php';

// Online Code Editor Engine (Phase 4-6)
require_once CDT_THEME_DIR . '/inc/editor/editor-registry.php';
require_once CDT_THEME_DIR . '/inc/editor/editor-router.php';
require_once CDT_THEME_DIR . '/inc/editor/editor-execution-api.php';

// Gamification & User Accounts Subsystems
require_once CDT_THEME_DIR . '/inc/gamification/gamification-init.php';
require_once CDT_THEME_DIR . '/inc/accounts/accounts-init.php';
require_once CDT_THEME_DIR . '/inc/admin/gamification-admin.php';

// SEO Health & Google Search Console Indexing Engine
require_once CDT_THEME_DIR . '/inc/seo-health/seo-health-init.php';


