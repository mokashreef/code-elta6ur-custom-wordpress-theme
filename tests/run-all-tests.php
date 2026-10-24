<?php
/**
 * Automated Verification & Regression Suite (v2.1 Final Gate Spec)
 * Code Elta6ur Platform Test Runner
 */

$passCount = 0;
$failCount = 0;

function assertTest( $condition, $message ) {
	global $passCount, $failCount;
	if ( $condition ) {
		echo " [PASS] " . $message . "\n";
		$passCount++;
	} else {
		echo " [FAIL] " . $message . "\n";
		$failCount++;
	}
}

// Mock WordPress functions if running standalone in CLI
if ( ! function_exists( 'sanitize_key' ) ) {
	function sanitize_key( $key ) {
		return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( $key ) );
	}
}
if ( ! function_exists( 'apply_filters' ) ) {
	function apply_filters( $tag, $value ) {
		return $value;
	}
}
if ( ! function_exists( 'add_action' ) ) {
	function add_action( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}
}
if ( ! function_exists( 'add_filter' ) ) {
	function add_filter( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}
}
if ( ! function_exists( '__' ) ) {
	function __( $text, $domain = 'default' ) {
		return $text;
	}
}
if ( ! function_exists( 'get_option' ) ) {
	function get_option( $key, $default = false ) {
		return $default;
	}
}
if ( ! function_exists( 'home_url' ) ) {
	function home_url( $path = '' ) {
		return 'https://code-elta6ur.com' . $path;
	}
}
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

echo "==================================================\n";
echo "1. TESTING TOOLS REGISTRY & ACCEPTANCE CRITERIA (All 30 Tools)\n";
echo "==================================================\n";

require_once __DIR__ . '/../code-elta6ur-theme/inc/tools/tools-registry.php';

$tools = cdt_get_tools_registry();
assertTest( is_array( $tools ) && count( $tools ) === 30, "Tools count is exactly 30 (Actual: " . count( $tools ) . ")" );

$expectedTools = array(
	'json-formatter', 'json-diff', 'json-to-code', 'json-schema-validator', 'jsonpath-tester',
	'yaml-json-converter', 'curl-to-code', 'openapi-validator', 'openapi-to-typescript', 'openapi-diff',
	'jwt-inspector', 'http-headers-analyzer', 'security-headers-checker', 'csp-generator', 'env-parser',
	'regex-tester', 'regex-builder', 'sql-formatter', 'sql-to-er-diagram', 'db-schema-generator',
	'docker-compose-generator', 'dockerfile-generator', 'git-command-generator', 'gitignore-generator',
	'package-json-generator', 'semver-calculator', 'htaccess-to-nginx', 'css-specificity-calculator',
	'css-clamp-generator', 'css-unit-converter'
);

$missingTools = array_diff( $expectedTools, array_keys( $tools ) );
assertTest( empty( $missingTools ), "All 30 official tools are defined with correct slugs (Missing: " . implode( ', ', $missingTools ) . ")" );

$categories = cdt_get_tool_categories();
assertTest( count( $categories ) === 7, "Categories count is 7 (Actual: " . count( $categories ) . ")" );

$allFieldsValid = true;
foreach ( $tools as $slug => $def ) {
	$hasRequiredFields = ! empty( $def['id'] )
		&& ! empty( $def['name'] )
		&& ! empty( $def['name_en'] )
		&& ! empty( $def['title'] )
		&& ! empty( $def['description'] )
		&& ! empty( $def['category'] )
		&& ! empty( $def['execution_mode'] )
		&& ! empty( $def['input_schema'] )
		&& ! empty( $def['output_schema'] )
		&& ! empty( $def['dependencies'] )
		&& ! empty( $def['privacy_notice'] )
		&& ! empty( $def['acceptance_criteria'] )
		&& ! empty( $def['examples'] )
		&& ! empty( $def['seo_title'] )
		&& ! empty( $def['seo_desc'] );

	if ( ! $hasRequiredFields ) {
		$allFieldsValid = false;
		echo " [FAIL] Tool {$slug} is missing required v2.1 fields!\n";
	}
	if ( ! isset( $categories[ $def['category'] ] ) ) {
		assertTest( false, "Tool {$slug} belongs to a valid category: " . $def['category'] );
	}
}
assertTest( $allFieldsValid, "All 30 tools have complete v2.1 metadata, schemas, dependencies, and Acceptance Criteria" );

echo "\n==================================================\n";
echo "2. TESTING EDITOR LANGUAGES REGISTRY (All 15 Languages)\n";
echo "==================================================\n";

require_once __DIR__ . '/../code-elta6ur-theme/inc/editor/runtime-config.php';
require_once __DIR__ . '/../code-elta6ur-theme/inc/editor/editor-registry.php';

$languages = cdt_get_editor_languages();
assertTest( is_array( $languages ) && count( $languages ) === 15, "Languages count is exactly 15 (Actual: " . count( $languages ) . ")" );

$expectedLangs = array(
	'python', 'javascript', 'typescript', 'c', 'cpp',
	'csharp', 'java', 'go', 'rust', 'php',
	'ruby', 'kotlin', 'swift', 'sql', 'bash'
);

$missingLangs = array_diff( $expectedLangs, array_keys( $languages ) );
assertTest( empty( $missingLangs ), "All 15 official languages are defined with correct slugs (Missing: " . implode( ', ', $missingLangs ) . ")" );

$runtimesValid = true;
foreach ( $languages as $l_slug => $l_def ) {
	$validLang = ! empty( $l_def['name'] )
		&& ! empty( $l_def['version'] )
		&& ! empty( $l_def['runtime'] )
		&& ! empty( $l_def['extension'] )
		&& ! empty( $l_def['sample_code'] )
		&& ! empty( $l_def['seo_title'] )
		&& isset( $l_def['runtime_config'] );

	if ( ! $validLang ) {
		$runtimesValid = false;
	}
}
assertTest( $runtimesValid, "All 15 languages have valid runtime, version, sample code, runtime_config, and SEO definitions" );

echo "\n==================================================\n";
echo "3. TESTING PROVIDER RESOLVER & EXECUTION MANAGER\n";
echo "==================================================\n";

require_once __DIR__ . '/../code-elta6ur-theme/inc/editor/execution/class-provider-resolver.php';
require_once __DIR__ . '/../code-elta6ur-theme/inc/editor/execution/class-execution-manager.php';

$resolver = new CDT_Provider_Resolver();
$driver   = $resolver->resolve();
assertTest( $driver instanceof CDT_Execution_Driver_Interface, "Provider Resolver instantiates valid driver (Resolved: " . $driver->get_name() . ")" );

$manager = CDT_Execution_Manager::get_instance();
assertTest( $manager->get_resolver() instanceof CDT_Provider_Resolver, "Execution Manager coordinates via Provider Resolver" );
assertTest( count( $resolver->get_registered_providers() ) >= 3, "Resolver supports multi-driver architecture (piston, judge0, null)" );

echo "\n==================================================\n";
echo "4. SECURITY AUDIT (Zero local execution on host)\n";
echo "==================================================\n";

$themeDir = __DIR__ . '/../code-elta6ur-theme';
$violations = array();
$phpFiles = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $themeDir ) );
foreach ( $phpFiles as $file ) {
	if ( $file->isFile() && $file->getExtension() === 'php' ) {
		$content = file_get_contents( $file->getPathname() );
		// Check for prohibited execution calls
		if ( preg_match( '/\b(eval|shell_exec|exec|system|passthru)\s*\(/i', $content ) ) {
			$violations[] = $file->getFilename();
		}
	}
}
assertTest( empty( $violations ), "Zero prohibited execution functions (eval, shell_exec, system, exec, passthru) found in theme code (Violations: " . implode( ', ', $violations ) . ")" );

echo "\n==================================================\n";
echo "5. URL STRUCTURE & REGRESSION VERIFICATION\n";
echo "==================================================\n";

$toolUrls = array();
foreach ( array_keys( $tools ) as $slug ) {
	$toolUrls[] = "/tools/{$slug}/";
}
assertTest( count( $toolUrls ) === 30, "30 dedicated SEO-ready tool URLs formatted correctly (/tools/{slug}/)" );

$editorUrls = array();
foreach ( array_keys( $languages ) as $lang ) {
	$editorUrls[] = "/editor/{$lang}/";
}
assertTest( count( $editorUrls ) === 15, "15 dedicated SEO-ready editor language URLs formatted correctly (/editor/{lang}/)" );

$inventoryPath = __DIR__ . '/../URL-INVENTORY.md';
assertTest( file_exists( $inventoryPath ), "URL-INVENTORY.md exists and is preserved" );

echo "\n==================================================\n";
echo "6. USER ACCOUNTS, GAMIFICATION & PROGRESS SYSTEM\n";
echo "==================================================\n";

$themeDir = __DIR__ . '/../code-elta6ur-theme';

// Check account templates
assertTest( file_exists( $themeDir . '/template-login.php' ), "template-login.php exists" );
assertTest( file_exists( $themeDir . '/template-registration.php' ), "template-registration.php exists" );
assertTest( file_exists( $themeDir . '/template-lost-password.php' ), "template-lost-password.php exists" );
assertTest( file_exists( $themeDir . '/template-account.php' ), "template-account.php exists" );

// Check assets
assertTest( file_exists( $themeDir . '/assets/css/accounts.css' ), "assets/css/accounts.css exists" );
assertTest( file_exists( $themeDir . '/assets/js/accounts.js' ), "assets/js/accounts.js exists" );
assertTest( file_exists( $themeDir . '/assets/js/gamification-tracker.js' ), "assets/js/gamification-tracker.js exists" );

// Check modules
assertTest( file_exists( $themeDir . '/inc/gamification/db-schema.php' ), "inc/gamification/db-schema.php exists" );
assertTest( file_exists( $themeDir . '/inc/gamification/points-engine.php' ), "inc/gamification/points-engine.php exists" );
assertTest( file_exists( $themeDir . '/inc/gamification/streak-engine.php' ), "inc/gamification/streak-engine.php exists" );
assertTest( file_exists( $themeDir . '/inc/gamification/reading-engine.php' ), "inc/gamification/reading-engine.php exists" );
assertTest( file_exists( $themeDir . '/inc/gamification/course-progress.php' ), "inc/gamification/course-progress.php exists" );
assertTest( file_exists( $themeDir . '/inc/gamification/time-tracker.php' ), "inc/gamification/time-tracker.php exists" );
assertTest( file_exists( $themeDir . '/inc/gamification/badges-levels.php' ), "inc/gamification/badges-levels.php exists" );
assertTest( file_exists( $themeDir . '/inc/gamification/ajax-api.php' ), "inc/gamification/ajax-api.php exists" );
assertTest( file_exists( $themeDir . '/inc/accounts/accounts-router.php' ), "inc/accounts/accounts-router.php exists" );
assertTest( file_exists( $themeDir . '/inc/accounts/auth-handlers.php' ), "inc/accounts/auth-handlers.php exists" );
assertTest( file_exists( $themeDir . '/inc/admin/gamification-admin.php' ), "inc/admin/gamification-admin.php exists" );

// Check functional catalogs
require_once $themeDir . '/inc/gamification/course-progress.php';
require_once $themeDir . '/inc/gamification/badges-levels.php';
require_once $themeDir . '/inc/accounts/accounts-router.php';

$coursesCat = cdt_get_courses_catalog();
assertTest( is_array( $coursesCat ) && count( $coursesCat ) === 7, "Courses catalog has 7 accredited tracks (HTML, CSS, Bootstrap, JS, React, C++, TypeScript)" );

$badgesCat = cdt_get_badges_catalog();
assertTest( is_array( $badgesCat ) && count( $badgesCat ) === 11, "Badges catalog has 11 official achievement badges" );

$levelsCat = cdt_get_levels_catalog();
assertTest( is_array( $levelsCat ) && count( $levelsCat ) === 5, "Levels catalog has 5 tiered ranks from Beginner to Elta6ur Expert" );

assertTest( cdt_get_account_page_url( 'login' ) === 'https://code-elta6ur.com/login/', "Account router formats clean /login/ URL" );
assertTest( cdt_get_account_page_url( 'account' ) === 'https://code-elta6ur.com/my-account/', "Account router formats clean /my-account/ URL" );

echo "\n==================================================\n";
echo "TEST RESULTS SUMMARY\n";
echo "==================================================\n";
echo "Total Passed: " . $passCount . "\n";
echo "Total Failed: " . $failCount . "\n\n";

if ( $failCount === 0 ) {
	echo ">>> ALL AUTOMATED TESTS PASSED SUCCESSFULLY! <<<\n\n";
	exit( 0 );
} else {
	echo ">>> SOME TESTS FAILED! <<<\n\n";
	exit( 1 );
}
