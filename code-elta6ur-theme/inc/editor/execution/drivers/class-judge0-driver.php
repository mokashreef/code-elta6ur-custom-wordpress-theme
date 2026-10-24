<?php
/**
 * Judge0 Execution Driver
 *
 * Implements code execution via Judge0 CE API or self-hosted instance.
 *
 * @package CodeElta6urTheme
 * @subpackage Editor/Execution/Drivers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __DIR__ ) . '/interface-execution-driver.php';

class CDT_Judge0_Driver implements CDT_Execution_Driver_Interface {

	public function get_id() {
		return 'judge0';
	}

	public function get_name() {
		return 'Judge0 Isolated Runner';
	}

	public function is_available() {
		if ( defined( 'CDT_JUDGE0_ENDPOINT' ) ) {
			return true;
		}
		if ( function_exists( 'get_theme_mod' ) && get_theme_mod( 'cdt_judge0_key', '' ) !== '' ) {
			return true;
		}
		return false;
	}

	public function execute( $language, $code, $stdin = '', $options = array() ) {
		if ( ! $this->is_available() ) {
			return array(
				'success' => false,
				'driver'  => $this->get_id(),
				'error'   => 'مشغل Judge0 غير مهيأ. يرجى إدخال مفتاح RapidAPI في تخصيص القالب أو ضبط CDT_JUDGE0_KEY في wp-config.php.',
			);
		}

		$raw_endpoint = defined( 'CDT_JUDGE0_ENDPOINT' ) ? CDT_JUDGE0_ENDPOINT : ( function_exists( 'get_theme_mod' ) ? get_theme_mod( 'cdt_judge0_endpoint', 'https://judge0-ce.p.rapidapi.com' ) : 'https://judge0-ce.p.rapidapi.com' );
		$endpoint     = rtrim( $raw_endpoint, '/' ) . '/submissions?wait=true';
		$api_key      = defined( 'CDT_JUDGE0_KEY' ) ? CDT_JUDGE0_KEY : ( function_exists( 'get_theme_mod' ) ? get_theme_mod( 'cdt_judge0_key', '' ) : '' );

		// Judge0 Language ID Mapping
		$lang_map = array(
			'python'     => 71, // Python (3.8.1)
			'javascript' => 63, // JavaScript (Node.js 12.14.0)
			'typescript' => 74, // TypeScript (3.7.4)
			'c'          => 50, // C (GCC 9.2.0)
			'cpp'        => 54, // C++ (GCC 9.2.0)
			'csharp'     => 51, // C# (Mono 6.6.0)
			'java'       => 62, // Java (OpenJDK 13.0.1)
			'go'         => 60, // Go (1.13.5)
			'rust'       => 73, // Rust (1.40.0)
			'php'        => 68, // PHP (7.4.1)
			'ruby'       => 72, // Ruby (2.7.0)
			'kotlin'     => 78, // Kotlin (1.3.70)
			'swift'      => 83, // Swift (5.2.3)
			'sql'        => 82, // SQL (SQLite 3.27.2)
			'bash'       => 46, // Bash (5.0.0)
		);

		if ( ! isset( $lang_map[ $language ] ) ) {
			return array(
				'success' => false,
				'driver'  => $this->get_id(),
				'error'   => 'اللغة غير مدعومة في مشغل Judge0.',
			);
		}

		$headers = array(
			'Content-Type' => 'application/json',
			'User-Agent'   => 'CodeElta6ur-Sandbox/1.0',
		);
		if ( ! empty( $api_key ) ) {
			$headers['X-RapidAPI-Key'] = $api_key;
		}

		$payload = array(
			'source_code' => base64_encode( $code ),
			'language_id' => $lang_map[ $language ],
			'stdin'       => base64_encode( $stdin ),
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
				'error'       => 'تعذر الاتصال بـ Judge0: ' . $response->get_error_message(),
			);
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! is_array( $body ) ) {
			return array(
				'success'     => false,
				'driver'      => $this->get_id(),
				'duration_ms' => $duration_ms,
				'error'       => 'استجابة غير صالحة من Judge0.',
			);
		}

		$stdout  = ! empty( $body['stdout'] ) ? base64_decode( $body['stdout'] ) : '';
		$stderr  = ! empty( $body['stderr'] ) ? base64_decode( $body['stderr'] ) : '';
		$compile = ! empty( $body['compile_output'] ) ? base64_decode( $body['compile_output'] ) : '';

		return array(
			'success'        => true,
			'driver'         => $this->get_id(),
			'stdout'         => $stdout,
			'stderr'         => $stderr,
			'exit_code'      => isset( $body['status']['id'] ) && 3 === (int) $body['status']['id'] ? 0 : 1,
			'compile_output' => $compile,
			'duration_ms'    => $duration_ms,
			'raw'            => $body,
		);
	}
}
