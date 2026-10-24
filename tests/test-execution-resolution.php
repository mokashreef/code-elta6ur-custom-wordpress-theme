<?php
/**
 * Test Execution Resolution via Theme Mod and Client Runners
 */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/../code-elta6ur-theme/' );
}

require_once __DIR__ . '/../code-elta6ur-theme/inc/editor/execution/class-provider-resolver.php';
require_once __DIR__ . '/../code-elta6ur-theme/inc/editor/execution/drivers/class-jdoodle-driver.php';

// Mock get_theme_mod if not present
if ( ! function_exists( 'get_theme_mod' ) ) {
	function get_theme_mod( $name, $default = '' ) {
		$mock_data = array(
			'cdt_judge0_key'          => 'test-rapidapi-key',
			'cdt_jdoodle_client_id'     => 'test-jdoodle-id',
			'cdt_jdoodle_client_secret' => 'test-jdoodle-secret',
		);
		return isset( $mock_data[ $name ] ) ? $mock_data[ $name ] : $default;
	}
}

$driver = new CDT_Judge0_Driver();
if ( ! $driver->is_available() ) {
	echo "FAIL: Judge0 should be available when cdt_judge0_key is provided via get_theme_mod\n";
	exit( 1 );
}
echo "[PASS] Judge0 is available via theme_mod key\n";

$jdriver = new CDT_JDoodle_Driver();
if ( ! $jdriver->is_available() ) {
	echo "FAIL: JDoodle should be available when credentials provided via get_theme_mod\n";
	exit( 1 );
}
echo "[PASS] JDoodle is available via theme_mod credentials (No credit card needed)\n";

$resolver = new CDT_Provider_Resolver();
$resolved = $resolver->resolve();
if ( $resolved->get_id() !== 'jdoodle' ) {
	echo "FAIL: Resolver should resolve to jdoodle, resolved to: " . $resolved->get_id() . "\n";
	exit( 1 );
}
echo "[PASS] Resolver correctly resolved to: " . $resolved->get_name() . "\n";

// Check that editor-engine.js includes all 4 in-browser runners
$jsContent = file_get_contents( __DIR__ . '/../code-elta6ur-theme/assets/js/editor-engine.js' );
$runners = array(
	'runBrowserJavaScript',
	'runBrowserTypeScript',
	'runBrowserPython',
	'runBrowserSQL',
);

foreach ( $runners as $runner ) {
	if ( strpos( $jsContent, $runner ) === false ) {
		echo "FAIL: Missing runner {$runner} in editor-engine.js\n";
		exit( 1 );
	}
	echo "[PASS] Found {$runner} in editor-engine.js\n";
}

echo "\n>>> ALL RESOLUTION & RUNNER TESTS PASSED! <<<\n";
