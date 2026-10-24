<?php
/**
 * Execution Manager
 *
 * Coordinates execution drivers via Provider Resolver, enforces configurable security limits,
 * rate-limiting, and error normalization.
 *
 * @package CodeElta6urTheme
 * @subpackage Editor/Execution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/interface-execution-driver.php';
require_once __DIR__ . '/class-provider-resolver.php';

class CDT_Execution_Manager {

	/**
	 * Provider resolver instance.
	 *
	 * @var CDT_Provider_Resolver
	 */
	protected $resolver;

	/**
	 * Singleton instance.
	 *
	 * @var CDT_Execution_Manager|null
	 */
	protected static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	protected function __construct() {
		$this->resolver = new CDT_Provider_Resolver();
	}

	/**
	 * Get provider resolver.
	 *
	 * @return CDT_Provider_Resolver
	 */
	public function get_resolver() {
		return $this->resolver;
	}

	/**
	 * Get active execution driver resolved dynamically.
	 *
	 * @return CDT_Execution_Driver_Interface
	 */
	public function get_active_driver() {
		return $this->resolver->resolve();
	}

	/**
	 * Execute code through the active driver with Defense in Depth:
	 * Configurable limits, rate-limiting, strict language allowlist, and output truncation.
	 *
	 * @param string $language Language slug.
	 * @param string $code Source code.
	 * @param string $stdin Standard input.
	 * @param array  $lang_def Full language definition.
	 * @return array Normalized response array.
	 */
	public function execute( $language, $code, $stdin = '', $lang_def = array() ) {
		// 1. Configurable Runtime / Security Settings
		$rate_limit  = defined( 'CDT_EXEC_RATE_LIMIT' ) ? (int) CDT_EXEC_RATE_LIMIT : 20;
		$rate_window = defined( 'CDT_EXEC_RATE_WINDOW' ) ? (int) CDT_EXEC_RATE_WINDOW : 60;
		$max_code    = defined( 'CDT_MAX_CODE_SIZE' ) ? (int) CDT_MAX_CODE_SIZE : 65536;
		$max_stdin   = defined( 'CDT_MAX_STDIN_SIZE' ) ? (int) CDT_MAX_STDIN_SIZE : 16384;
		$max_output  = defined( 'CDT_MAX_OUTPUT_SIZE' ) ? (int) CDT_MAX_OUTPUT_SIZE : 51200;
		$timeout_ms  = defined( 'CDT_EXEC_TIMEOUT_MS' ) ? (int) CDT_EXEC_TIMEOUT_MS : 5000;

		// 2. Strict Language Allowlist Validation (Zero-trust)
		if ( function_exists( 'cdt_get_editor_languages' ) ) {
			$allowed_langs = array_keys( cdt_get_editor_languages() );
			if ( ! in_array( $language, $allowed_langs, true ) ) {
				return array(
					'success' => false,
					'error'   => 'اللغة البرمجية المحددة غير مصرح بها أو غير مدعومة.',
				);
			}
		}

		// 3. Validate Code
		if ( '' === trim( $code ) ) {
			return array(
				'success' => false,
				'error'   => 'يرجى كتابة كود برمجي لتنفيذه.',
			);
		}

		// 4. Validate Code Size Limit
		if ( strlen( $code ) > $max_code ) {
			return array(
				'success' => false,
				'error'   => sprintf( 'حجم الكود البرمجي يتجاوز الحد الأقصى المسموح به (%d كيلوبايت).', round( $max_code / 1024 ) ),
			);
		}

		// 5. Validate Stdin Size Limit
		if ( strlen( $stdin ) > $max_stdin ) {
			return array(
				'success' => false,
				'error'   => sprintf( 'حجم المدخلات (stdin) يتجاوز الحد الأقصى المسموح به (%d كيلوبايت).', round( $max_stdin / 1024 ) ),
			);
		}

		// 6. Rate Limiting Check (Configurable executions per window per client IP)
		$ip_address = $this->get_client_ip();
		$rate_key   = 'cdt_rate_' . md5( $ip_address );
		$req_count  = (int) get_transient( $rate_key );

		if ( $req_count >= $rate_limit ) {
			return array(
				'success' => false,
				'error'   => sprintf( 'تجاوزت الحد الأقصى المسموح لطلبات التشغيل (%d طلب في غضون %d ثانية). يرجى الانتظار قليلاً.', $rate_limit, $rate_window ),
			);
		}
		set_transient( $rate_key, $req_count + 1, $rate_window );

		// 7. Resolve Driver and Execute
		$driver  = $this->get_active_driver();
		$options = array(
			'lang_def'   => $lang_def,
			'timeout_ms' => $timeout_ms,
		);

		$result = $driver->execute( $language, $code, $stdin, $options );

		// 8. Enforce Output Truncation
		if ( ! empty( $result['stdout'] ) && strlen( $result['stdout'] ) > $max_output ) {
			$result['stdout'] = substr( $result['stdout'], 0, $max_output ) . "\n\n[تم اقتطاع المخرجات لتجاوزها الحد الأقصى المسموح به]";
		}
		if ( ! empty( $result['stderr'] ) && strlen( $result['stderr'] ) > $max_output ) {
			$result['stderr'] = substr( $result['stderr'], 0, $max_output ) . "\n\n[تم اقتطاع رسائل الخطأ لتجاوزها الحد الأقصى المسموح به]";
		}
		if ( ! empty( $result['compile_output'] ) && strlen( $result['compile_output'] ) > $max_output ) {
			$result['compile_output'] = substr( $result['compile_output'], 0, $max_output ) . "\n\n[تم اقتطاع مخرجات المترجم لتجاوزها الحد الأقصى]";
		}

		return $result;
	}

	/**
	 * Retrieve client IP address safely.
	 *
	 * @return string
	 */
	protected function get_client_ip() {
		$ip = '127.0.0.1';
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip_list = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
			$ip      = trim( $ip_list[0] );
		} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}
		return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '127.0.0.1';
	}
}
