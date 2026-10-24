<?php
/**
 * Null Execution Driver
 *
 * Fallback driver when execution engine is disabled or unconfigured.
 * Ensures the editor remains 100% usable in "Editor Only" mode.
 *
 * @package CodeElta6urTheme
 * @subpackage Editor/Execution/Drivers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __DIR__ ) . '/interface-execution-driver.php';

class CDT_Null_Driver implements CDT_Execution_Driver_Interface {

	public function get_id() {
		return 'null';
	}

	public function get_name() {
		return 'Editor Only (Sandbox Disabled)';
	}

	public function is_available() {
		return true;
	}

	public function execute( $language, $code, $stdin = '', $options = array() ) {
		return array(
			'success'     => false,
			'driver'      => $this->get_id(),
			'duration_ms' => 0,
			'error'       => 'تشغيل لغات الأنظمة والمترجمات (مثل ' . strtoupper( esc_html( $language ) ) . ') يتطلب تفعيل بيئة تشغيل معزولة (Sandbox). اللغات التي تعمل فورياً داخل المتصفح حالياً: JavaScript, TypeScript, Python, SQL. لتفعيل تشغيل كافة اللغات عبر الخادم، يمكنك تفعيل مشغل Judge0 في wp-config.php.',
		);
	}
}
