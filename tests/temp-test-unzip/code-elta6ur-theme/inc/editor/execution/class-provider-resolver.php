<?php
/**
 * Provider Resolver for Code Execution
 *
 * Resolves and instantiates the configured execution driver based on
 * runtime configuration, environment constants, or admin settings.
 *
 * Architecture:
 * Execution Manager -> Provider Resolver -> Configured Driver
 *
 * @package CodeElta6urTheme
 * @subpackage Editor/Execution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/interface-execution-driver.php';
require_once __DIR__ . '/drivers/class-piston-driver.php';
require_once __DIR__ . '/drivers/class-judge0-driver.php';
require_once __DIR__ . '/drivers/class-jdoodle-driver.php';
require_once __DIR__ . '/drivers/class-null-driver.php';

class CDT_Provider_Resolver {

	/**
	 * Available driver instances.
	 *
	 * @var array<string, CDT_Execution_Driver_Interface>
	 */
	protected $drivers = array();

	public function __construct() {
		// Register known drivers
		$this->register_driver( new CDT_Piston_Driver() );
		$this->register_driver( new CDT_Judge0_Driver() );
		$this->register_driver( new CDT_JDoodle_Driver() );
		$this->register_driver( new CDT_Null_Driver() );
	}

	/**
	 * Register a driver instance.
	 *
	 * @param CDT_Execution_Driver_Interface $driver Driver instance.
	 */
	public function register_driver( CDT_Execution_Driver_Interface $driver ) {
		$this->drivers[ $driver->get_id() ] = $driver;
	}

	/**
	 * Resolve the active driver according to configuration.
	 *
	 * Precedence:
	 * 1. Constant CDT_EXECUTION_PROVIDER
	 * 2. Filter cdt_execution_provider
	 * 3. Fallback to 'null' driver (Editor Only mode)
	 *
	 * @return CDT_Execution_Driver_Interface
	 */
	public function resolve() {
		$provider_id = 'null';

		// Check if a specific provider is configured
		if ( defined( 'CDT_EXECUTION_PROVIDER' ) ) {
			$provider_id = sanitize_key( CDT_EXECUTION_PROVIDER );
		} elseif ( defined( 'CDT_PISTON_ENDPOINT' ) ) {
			// If Piston endpoint is explicitly configured, resolve to piston
			$provider_id = 'piston';
		} elseif ( defined( 'CDT_JUDGE0_ENDPOINT' ) ) {
			$provider_id = 'judge0';
		} elseif ( defined( 'CDT_JDOODLE_CLIENT_ID' ) ) {
			$provider_id = 'jdoodle';
		} elseif ( function_exists( 'get_theme_mod' ) ) {
			$mod_provider = get_theme_mod( 'cdt_execution_provider', '' );
			if ( ! empty( $mod_provider ) ) {
				$provider_id = sanitize_key( $mod_provider );
			} elseif ( get_theme_mod( 'cdt_jdoodle_client_id', '' ) !== '' ) {
				$provider_id = 'jdoodle';
			} elseif ( get_theme_mod( 'cdt_judge0_key', '' ) !== '' ) {
				$provider_id = 'judge0';
			} elseif ( get_theme_mod( 'cdt_piston_endpoint', '' ) !== '' ) {
				$provider_id = 'piston';
			}
		}

		/**
		 * Filter resolved provider ID allowing programmatic overrides.
		 *
		 * @param string $provider_id
		 */
		if ( function_exists( 'apply_filters' ) ) {
			$provider_id = apply_filters( 'cdt_execution_provider', $provider_id );
		}

		if ( isset( $this->drivers[ $provider_id ] ) && $this->drivers[ $provider_id ]->is_available() ) {
			return $this->drivers[ $provider_id ];
		}

		// Fallback to null driver (Editor Only mode)
		return isset( $this->drivers['null'] ) ? $this->drivers['null'] : new CDT_Null_Driver();
	}

	/**
	 * Get list of all registered driver IDs.
	 *
	 * @return array<string>
	 */
	public function get_registered_providers() {
		return array_keys( $this->drivers );
	}
}
