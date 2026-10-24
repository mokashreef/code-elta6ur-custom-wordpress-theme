<?php
/**
 * واجهة برمجة التطبيقات لتشغيل الأكواد في بيئة معزولة وآمنة
 * Code Elta6ur Isolated Code Execution API
 *
 * Architecture:
 * Browser -> WP REST API (/wp-json/cdt/v1/execute) -> Execution Manager -> Adapter/Driver -> Isolated Sandbox
 * Strictly zero local shell_exec/eval/exec on host server.
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/execution/class-execution-manager.php';

/**
 * Register REST API Route for isolated code execution
 */
function cdt_register_execution_api() {
	register_rest_route(
		'cdt/v1',
		'/execute',
		array(
			'methods'             => 'POST',
			'callback'            => 'cdt_handle_code_execution',
			'permission_callback' => '__return_true', // Public with strict rate limiting & sanitization
		)
	);
}
add_action( 'rest_api_init', 'cdt_register_execution_api' );

/**
 * Handle Code Execution Request
 *
 * @param WP_REST_Request $request REST Request.
 * @return WP_REST_Response
 */
function cdt_handle_code_execution( $request ) {
	$lang_slug = sanitize_key( $request->get_param( 'language' ) );
	$code      = (string) $request->get_param( 'code' );
	$stdin     = (string) $request->get_param( 'stdin' );

	$languages = cdt_get_editor_languages();
	if ( ! isset( $languages[ $lang_slug ] ) ) {
		return new WP_REST_Response(
			array(
				'success' => false,
				'error'   => 'اللغة البرمجية المحددة غير مدعومة.',
			),
			400
		);
	}

	$lang_def = $languages[ $lang_slug ];

	// Delegate to Execution Manager (handles limits, rate-limiting, and driver adapter)
	$manager = CDT_Execution_Manager::get_instance();
	$result  = $manager->execute( $lang_slug, $code, $stdin, $lang_def );

	if ( empty( $result['success'] ) ) {
		return new WP_REST_Response(
			array(
				'success' => false,
				'error'   => ! empty( $result['error'] ) ? $result['error'] : 'فشل تنفيذ الكود البرمجي.',
				'driver'  => ! empty( $result['driver'] ) ? $result['driver'] : 'unknown',
			),
			200
		);
	}

	// Format response for UI compatibility
	$response_data = array(
		'success'     => true,
		'driver'      => $result['driver'],
		'duration_ms' => $result['duration_ms'],
		'run'         => array(
			'stdout' => isset( $result['stdout'] ) ? $result['stdout'] : '',
			'stderr' => isset( $result['stderr'] ) ? $result['stderr'] : '',
			'code'   => isset( $result['exit_code'] ) ? $result['exit_code'] : 0,
		),
		'compile'     => array(
			'output' => isset( $result['compile_output'] ) ? $result['compile_output'] : '',
		),
	);

	return new WP_REST_Response( $response_data, 200 );
}
