<?php
/**
 * Execution Driver Interface
 *
 * Defines the contract for all remote and local code execution drivers.
 *
 * @package CodeElta6urTheme
 * @subpackage Editor/Execution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface CDT_Execution_Driver_Interface {

	/**
	 * Get unique driver identifier.
	 *
	 * @return string
	 */
	public function get_id();

	/**
	 * Get human-readable driver name.
	 *
	 * @return string
	 */
	public function get_name();

	/**
	 * Check if the driver is currently configured and available.
	 *
	 * @return bool
	 */
	public function is_available();

	/**
	 * Execute code through the provider.
	 *
	 * @param string $language Programming language slug.
	 * @param string $code Source code string.
	 * @param string $stdin Standard input string.
	 * @param array  $options Additional options (timeout, memory, etc.).
	 * @return array Unified execution result.
	 */
	public function execute( $language, $code, $stdin = '', $options = array() );
}
