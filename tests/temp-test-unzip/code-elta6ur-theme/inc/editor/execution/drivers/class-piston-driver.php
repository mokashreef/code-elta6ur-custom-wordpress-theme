<?php
/**
 * Piston Execution Driver
 *
 * Implements code execution via isolated Piston Docker instance or API.
 *
 * @package CodeElta6urTheme
 * @subpackage Editor/Execution/Drivers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __DIR__ ) . '/interface-execution-driver.php';

class CDT_Piston_Driver implements CDT_Execution_Driver_Interface {

	/**
	 * Default public endpoint (requires whitelist as of Feb 2026).
	 */
	const DEFAULT_ENDPOINT = 'https://emkc.org/api/v2/piston/execute';

	public function get_id() {
		return 'piston';
	}

	public function get_name() {
		return 'Piston Sandbox Engine';
	}

	public function is_available() {
		// Available if endpoint is reachable or configured
		return true;
	}

	public function execute( $language, $code, $stdin = '', $options = array() ) {
		$endpoint = defined( 'CDT_PISTON_ENDPOINT' ) ? CDT_PISTON_ENDPOINT : ( function_exists( 'get_theme_mod' ) && get_theme_mod( 'cdt_piston_endpoint', '' ) !== '' ? get_theme_mod( 'cdt_piston_endpoint' ) : self::DEFAULT_ENDPOINT );
		$api_key  = defined( 'CDT_PISTON_KEY' ) ? CDT_PISTON_KEY : ( function_exists( 'get_theme_mod' ) ? get_theme_mod( 'cdt_piston_key', '' ) : '' );

		$lang_def = isset( $options['lang_def'] ) ? $options['lang_def'] : array();
		$piston_lang = ! empty( $lang_def['piston_lang'] ) ? $lang_def['piston_lang'] : $language;
		$piston_ver  = ! empty( $lang_def['piston_version'] ) ? $lang_def['piston_version'] : '*';
		$file_name   = ! empty( $lang_def['file_name'] ) ? $lang_def['file_name'] : 'main.txt';

		$headers = array(
			'Content-Type' => 'application/json',
			'User-Agent'   => 'CodeElta6ur-Sandbox/1.0',
		);

		if ( ! empty( $api_key ) ) {
			$headers['Authorization'] = $api_key;
		}

		$payload = array(
			'language'        => $piston_lang,
			'version'         => $piston_ver,
			'files'           => array(
				array(
					'name'    => $file_name,
					'content' => $code,
				),
			),
			'stdin'           => $stdin,
			'run_timeout'     => isset( $options['timeout_ms'] ) ? (int) $options['timeout_ms'] : 5000,
			'compile_timeout' => isset( $options['timeout_ms'] ) ? (int) $options['timeout_ms'] : 5000,
		);

		$start_time = microtime( true );

		$response = wp_remote_post(
			$endpoint,
			array(
				'timeout'     => 9,
				'headers'     => $headers,
				'body'        => wp_json_encode( $payload ),
				'data_format' => 'body',
			)
		);

		$duration_ms = round( ( microtime( true ) - $start_time ) * 1000 );

		if ( is_wp_error( $response ) ) {
			return array(
				'success'     => false,
				'driver'      => $this->get_id(),
				'duration_ms' => $duration_ms,
				'error'       => 'تعذر الاتصال ببيئة Piston Sandbox المعزولة (' . $response->get_error_message() . ').',
			);
		}

		$status_code = wp_remote_retrieve_response_code( $response );
		$body        = wp_remote_retrieve_body( $response );
		$data        = json_decode( $body, true );

		if ( 401 === $status_code || 403 === $status_code ) {
			return array(
				'success'     => false,
				'driver'      => $this->get_id(),
				'duration_ms' => $duration_ms,
				'error'       => 'خادم Piston العام يتطلب تصريحاً أو استضافة مخصصة. يرجى تفعيل حاوية Piston خاصة وتحديد CDT_PISTON_ENDPOINT في wp-config.php.',
			);
		}

		if ( 200 !== $status_code || ! is_array( $data ) ) {
			$err = isset( $data['message'] ) ? $data['message'] : 'فشل تنفيذ الكود في بيئة Piston (رمز الاستجابة: ' . $status_code . ').';
			return array(
				'success'     => false,
				'driver'      => $this->get_id(),
				'duration_ms' => $duration_ms,
				'error'       => $err,
			);
		}

		$stdout   = isset( $data['run']['stdout'] ) ? $data['run']['stdout'] : '';
		$stderr   = isset( $data['run']['stderr'] ) ? $data['run']['stderr'] : '';
		$exit_code = isset( $data['run']['code'] ) ? (int) $data['run']['code'] : 0;
		$compile_out = isset( $data['compile']['output'] ) ? $data['compile']['output'] : '';

		return array(
			'success'          => true,
			'driver'           => $this->get_id(),
			'stdout'           => $stdout,
			'stderr'           => $stderr,
			'exit_code'        => $exit_code,
			'compile_output'   => $compile_out,
			'duration_ms'      => $duration_ms,
			'raw'              => $data,
		);
	}
}
