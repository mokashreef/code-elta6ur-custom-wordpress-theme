<?php
/**
 * سجل وتعريفات محرك الأدوات البرمجية (Modular Tools Registry)
 * Code Elta6ur Tools Engine Modular Registry
 *
 * Discovers and aggregates modular tool definition files from inc/tools/definitions/.
 *
 * @package CodeElta6ur
 * @subpackage Tools
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the 7 curated tool categories.
 *
 * @return array
 */
function cdt_get_tool_categories() {
	return array(
		'json-data'     => array(
			'name'        => 'بيانات و JSON',
			'name_en'     => 'JSON & Data',
			'description' => 'أدوات معالجة، تدقيق، تحويل، واستعلام هياكل بيانات JSON و YAML.',
			'icon'        => 'brackets-curly',
		),
		'api-network'   => array(
			'name'        => 'واجهات API والشبكات',
			'name_en'     => 'API & Network',
			'description' => 'أدوات فحص واجهات برمجة التطبيقات، وثائق OpenAPI، ومولدات أوامر الشبكة.',
			'icon'        => 'hdd-network',
		),
		'security-auth' => array(
			'name'        => 'الأمان والمصادقة',
			'name_en'     => 'Security & Auth',
			'description' => 'أدوات فحص التوكنات، ترويسات الحماية، سياسات CSP، ومتغيرات البيئة الحساسة.',
			'icon'        => 'shield-lock',
		),
		'regex-text'    => array(
			'name'        => 'التعبيرات النمطية والنصوص',
			'name_en'     => 'Regex & Text',
			'description' => 'أدوات اختبار وشرح وإنشاء تعبيرات Regex لمعالجة وتحليل النصوص.',
			'icon'        => 'regex',
		),
		'sql-database'  => array(
			'name'        => 'قواعد البيانات و SQL',
			'name_en'     => 'SQL & Database',
			'description' => 'تنسيق استعلامات SQL، رسم مخططات العلاقات ERD، ومولدات كود DDL للجداول.',
			'icon'        => 'database-fill-gear',
		),
		'devops-git'    => array(
			'name'        => 'العمليات و Git',
			'name_en'     => 'DevOps & Git',
			'description' => 'مولدات تكوينات الحاويات Docker، أوامر ومستودعات Git، وحزم التطوير.',
			'icon'        => 'boxes',
		),
		'css-frontend'  => array(
			'name'        => 'تطوير الواجهات و CSS',
			'name_en'     => 'CSS & Frontend',
			'description' => 'حساب أولويات الأنماط Specificity، الخطوط المتجاوبة clamp()، ومحولات الوحدات.',
			'icon'        => 'palette',
		),
	);
}

/**
 * Discovers and returns all 30 registered tools from modular definition files.
 *
 * @return array<string, array>
 */
function cdt_get_tools() {
	static $tools = null;
	if ( null !== $tools ) {
		return $tools;
	}

	$tools = array();
	$definitions_dir = __DIR__ . '/definitions';

	// Category definition modules
	$definition_files = array(
		'json-data.php',
		'api-network.php',
		'security-auth.php',
		'regex-text.php',
		'sql-database.php',
		'devops-git.php',
		'css-frontend.php',
	);

	foreach ( $definition_files as $file ) {
		$file_path = $definitions_dir . '/' . $file;
		if ( file_exists( $file_path ) ) {
			$category_tools = require $file_path;
			if ( is_array( $category_tools ) ) {
				foreach ( $category_tools as $k => $t ) {
					if ( empty( $t['name_en'] ) && ! empty( $t['name'] ) ) {
						$category_tools[ $k ]['name_en'] = $t['name'];
					}
				}
				$tools = array_merge( $tools, $category_tools );
			}
		}
	}

	/**
	 * Filter all registered tools allowing extensions.
	 *
	 * @param array $tools Array of registered tool definitions.
	 */
	if ( function_exists( 'apply_filters' ) ) {
		$tools = apply_filters( 'cdt_registered_tools', $tools );
	}

	return $tools;
}

/**
 * Backward-compatible alias for cdt_get_tools
 *
 * @return array
 */
function cdt_get_tools_registry() {
	return cdt_get_tools();
}

/**
 * Retrieve a single tool definition by slug.
 *
 * @param string $slug Tool slug.
 * @return array|null Tool definition array or null if not found.
 */
function cdt_get_tool( $slug ) {
	$tools = cdt_get_tools();
	$slug  = sanitize_key( $slug );
	return isset( $tools[ $slug ] ) ? $tools[ $slug ] : null;
}

/**
 * Retrieve tools belonging to a specific category.
 *
 * @param string $category Category slug.
 * @return array Filtered tools array.
 */
function cdt_get_tools_by_category( $category ) {
	$all_tools = cdt_get_tools();
	$filtered  = array();
	foreach ( $all_tools as $slug => $tool ) {
		if ( isset( $tool['category'] ) && $tool['category'] === $category ) {
			$filtered[ $slug ] = $tool;
		}
	}
	return $filtered;
}
