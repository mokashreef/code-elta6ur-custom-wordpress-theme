<?php
/**
 * JDoodle Execution Driver
 *
 * Implements code execution via JDoodle API (Free tier requires NO credit card).
 *
 * @package CodeElta6urTheme
 * @subpackage Editor/Execution/Drivers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __DIR__ ) . '/interface-execution-driver.php';

class CDT_JDoodle_Driver implements CDT_Execution_Driver_Interface {

	const ENDPOINT = 'https://api.jdoodle.com/v1/execute';

	public function get_id() {
		return 'jdoodle';
	}

	public function get_name() {
		return 'JDoodle Runner (No Card Required)';
	}

	public function is_available() {
		$client_id = defined( 'CDT_JDOODLE_CLIENT_ID' ) ? CDT_JDOODLE_CLIENT_ID : ( function_exists( 'get_theme_mod' ) ? get_theme_mod( 'cdt_jdoodle_client_id', '' ) : '' );
		$secret    = defined( 'CDT_JDOODLE_CLIENT_SECRET' ) ? CDT_JDOODLE_CLIENT_SECRET : ( function_exists( 'get_theme_mod' ) ? get_theme_mod( 'cdt_jdoodle_client_secret', '' ) : '' );
		return ! empty( $client_id ) && ! empty( $secret );
	}

	public function execute( $language, $code, $stdin = '', $options = array() ) {
		if ( ! $this->is_available() ) {
			return array(
				'success' => false,
				'driver'  => $this->get_id(),
				'error'   => 'مشغل JDoodle غير مهيأ. يرجى إدخال Client ID و Client Secret في تخصيص القالب أو wp-config.php (حساب مجاني بدون بطاقة).',
			);
		}

		$client_id = defined( 'CDT_JDOODLE_CLIENT_ID' ) ? CDT_JDOODLE_CLIENT_ID : ( function_exists( 'get_theme_mod' ) ? get_theme_mod( 'cdt_jdoodle_client_id', '' ) : '' );
		$secret    = defined( 'CDT_JDOODLE_CLIENT_SECRET' ) ? CDT_JDOODLE_CLIENT_SECRET : ( function_exists( 'get_theme_mod' ) ? get_theme_mod( 'cdt_jdoodle_client_secret', '' ) : '' );

		// JDoodle Language Code Mapping
		$lang_map = array(
			'c'          => array( 'lang' => 'c', 'ver' => '5' ),
			'cpp'        => array( 'lang' => 'cpp17', 'ver' => '1' ),
			'csharp'     => array( 'lang' => 'csharp', 'ver' => '4' ),
			'java'       => array( 'lang' => 'java', 'ver' => '4' ),
			'python'     => array( 'lang' => 'python3', 'ver' => '4' ),
			'javascript' => array( 'lang' => 'nodejs', 'ver' => '4' ),
			'typescript' => array( 'lang' => 'typescript', 'ver' => '0' ),
			'php'        => array( 'lang' => 'php', 'ver' => '4' ),
			'ruby'       => array( 'lang' => 'ruby', 'ver' => '4' ),
			'go'         => array( 'lang' => 'go', 'ver' => '4' ),
			'rust'       => array( 'lang' => 'rust', 'ver' => '4' ),
			'bash'       => array( 'lang' => 'bash', 'ver' => '4' ),
			'sql'        => array( 'lang' => 'sql', 'ver' => '4' ),
			'swift'      => array( 'lang' => 'swift', 'ver' => '4' ),
			'kotlin'     => array( 'lang' => 'kotlin', 'ver' => '3' ),
		);

		if ( ! isset( $lang_map[ $language ] ) ) {
			return array(
				'success' => false,
				'driver'  => $this->get_id(),
				'error'   => 'اللغة غير مدعومة في مشغل JDoodle.',
			);
		}

		$mapped = $lang_map[ $language ];

		$payload = array(
			'clientId'     => $client_id,
			'clientSecret' => $secret,
			'script'       => $code,
			'stdin'        => $stdin,
			'language'     => $mapped['lang'],
			'versionIndex' => $mapped['ver'],
		);

		$start_time = microtime( true );

		$response = wp_remote_post(
			self::ENDPOINT,
			array(
				'timeout'     => 12,
				'headers'     => array( 'Content-Type' => 'application/json' ),
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
				'error'       => 'تعذر الاتصال بخادم JDoodle (' . $response->get_error_message() . ').',
			);
		}

		$status_code = wp_remote_retrieve_response_code( $response );
		$body        = wp_remote_retrieve_body( $response );
		$data        = json_decode( $body, true );

		if ( 200 !== $status_code || ! is_array( $data ) ) {
			$msg = isset( $data['error'] ) ? $data['error'] : 'فشل تنفيذ الكود في بيئة JDoodle (رمز الاستجابة: ' . $status_code . ').';
			return array(
				'success'     => false,
				'driver'      => $this->get_id(),
				'duration_ms' => $duration_ms,
				'error'       => $msg,
			);
		}

		$output   = isset( $data['output'] ) ? $data['output'] : '';
		$is_error = false;
		if ( isset( $data['statusCode'] ) && (int) $data['statusCode'] !== 200 ) {
			$is_error = true;
		}

		return array(
			'success'     => true,
			'driver'      => $this->get_id(),
			'stdout'      => $output,
			'stderr'      => '',
			'exit_code'   => $is_error ? 1 : 0,
			'duration_ms' => $duration_ms,
			'raw'         => $data,
		);
	}
}
