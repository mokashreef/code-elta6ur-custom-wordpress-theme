<?php
/**
 * نظام التوجيه والروابط لقسم الأدوات التقنية
 * Code Elta6ur Tools Router & Permalinks
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register query vars for Tools
 *
 * @param array $vars Registered query vars.
 * @return array
 */
function cdt_tools_query_vars( $vars ) {
	$vars[] = 'cdt_tools_hub';
	$vars[] = 'cdt_tool_slug';
	return $vars;
}
add_filter( 'query_vars', 'cdt_tools_query_vars' );

/**
 * Add rewrite rules for Tools Engine
 */
function cdt_tools_rewrite_rules() {
	add_rewrite_rule( '^tools/?$', 'index.php?cdt_tools_hub=1', 'top' );
	add_rewrite_rule( '^tools/([^/]+)/?$', 'index.php?cdt_tool_slug=$matches[1]', 'top' );
}
add_action( 'init', 'cdt_tools_rewrite_rules' );

/**
 * Check if the current request is for tools hub or a specific tool
 *
 * @return string|false 'hub', 'tool', or false.
 */
function cdt_get_current_tool_route() {
	global $wp_query;

	if ( ! empty( get_query_var( 'cdt_tools_hub' ) ) ) {
		return 'hub';
	}

	$tool_slug = get_query_var( 'cdt_tool_slug' );
	if ( ! empty( $tool_slug ) ) {
		return 'tool';
	}

	// Graceful fallback for environments before rewrite flush
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$path        = wp_parse_url( $request_uri, PHP_URL_PATH );
	if ( ! $path ) {
		return false;
	}

	$trimmed = trim( rawurldecode( $path ), '/' );

	if ( 'tools' === $trimmed ) {
		return 'hub';
	}

	if ( 0 === strpos( $trimmed, 'tools/' ) ) {
		$parts = explode( '/', $trimmed );
		if ( 2 === count( $parts ) && ! empty( $parts[1] ) ) {
			return 'tool';
		}
	}

	return false;
}

/**
 * Get active tool slug from current request
 *
 * @return string|null
 */
function cdt_get_current_tool_slug() {
	$slug = get_query_var( 'cdt_tool_slug' );
	if ( ! empty( $slug ) ) {
		return sanitize_key( $slug );
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$path        = wp_parse_url( $request_uri, PHP_URL_PATH );
	if ( $path ) {
		$trimmed = trim( rawurldecode( $path ), '/' );
		if ( 0 === strpos( $trimmed, 'tools/' ) ) {
			$parts = explode( '/', $trimmed );
			if ( 2 === count( $parts ) && ! empty( $parts[1] ) ) {
				return sanitize_key( $parts[1] );
			}
		}
	}

	return null;
}

/**
 * Intercept template routing for Tools
 *
 * @param string $template Default template path.
 * @return string
 */
function cdt_tools_template_include( $template ) {
	$route = cdt_get_current_tool_route();

	if ( 'hub' === $route ) {
		$hub_template = locate_template( array( 'template-tools-hub.php' ) );
		if ( $hub_template ) {
			status_header( 200 );
			return $hub_template;
		}
	}

	if ( 'tool' === $route ) {
		$tool_slug = cdt_get_current_tool_slug();
		$tool      = cdt_get_tool( $tool_slug );

		if ( $tool ) {
			$single_template = locate_template( array( 'template-tool-single.php' ) );
			if ( $single_template ) {
				status_header( 200 );
				return $single_template;
			}
		} else {
			// Unknown tool slug -> 404
			global $wp_query;
			if ( $wp_query ) {
				$wp_query->set_404();
			}
			status_header( 404 );
			$tpl_404 = get_404_template();
			return $tpl_404 ? $tpl_404 : $template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'cdt_tools_template_include', 99 );

/**
 * Filter document title for Tools pages
 *
 * @param array $title_parts Title parts.
 * @return array
 */
function cdt_tools_document_title_parts( $title_parts ) {
	$route = cdt_get_current_tool_route();

	if ( 'hub' === $route ) {
		$title_parts['title'] = 'أدوات المطورين والبرمجة أونلاين مجاناً | كود التطور';
	} elseif ( 'tool' === $route ) {
		$slug = cdt_get_current_tool_slug();
		$tool = cdt_get_tool( $slug );
		if ( $tool && ! empty( $tool['seo_title'] ) ) {
			$title_parts['title'] = $tool['seo_title'];
		}
	}

	return $title_parts;
}
add_filter( 'document_title_parts', 'cdt_tools_document_title_parts', 99 );

/**
 * Inject Tools SEO Meta Tags in Head
 */
function cdt_tools_meta_tags() {
	$route = cdt_get_current_tool_route();
	if ( ! $route ) {
		return;
	}

	// Scoped noindex strictly for stateful tool URLs (e.g. ?input=..., ?data=...)
	$state_params     = array( 'input', 'data', 'code', 'state', 'diff', 'pattern', 'sql', 'query', 'schema' );
	$has_state_params = false;
	foreach ( $state_params as $param ) {
		if ( isset( $_GET[ $param ] ) ) {
			$has_state_params = true;
			break;
		}
	}

	if ( $has_state_params ) {
		echo '<meta name="robots" content="noindex, follow">' . "\n";
		if ( ! headers_sent() ) {
			header( 'X-Robots-Tag: noindex, follow', true );
		}
	}

	if ( 'hub' === $route ) {
		$desc      = 'منصة أدوات تقنية وبرمجية مجانية وسريعة تعمل داخل المتصفح بأمان وخصوصية 100%. منسقات كود، أدوات JSON، محللات API، تعابير Regex، ومولدات DevOps.';
		$canonical = home_url( '/tools/' );
		echo "\n<!-- Code Elta6ur Tools Hub SEO -->\n";
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
		echo '<meta property="og:title" content="' . esc_attr( 'أدوات المطورين والبرمجة أونلاين | كود التطور' ) . '">' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
		echo '<meta property="og:url" content="' . esc_url( $canonical ) . '">' . "\n";
		echo '<meta property="og:type" content="website">' . "\n";
	} elseif ( 'tool' === $route ) {
		$slug = cdt_get_current_tool_slug();
		$tool = cdt_get_tool( $slug );
		if ( $tool ) {
			$desc      = isset( $tool['seo_desc'] ) ? $tool['seo_desc'] : $tool['description'];
			$canonical = home_url( '/tools/' . $tool['slug'] . '/' );
			echo "\n<!-- Code Elta6ur Single Tool SEO -->\n";
			echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
			echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
			echo '<meta property="og:title" content="' . esc_attr( $tool['seo_title'] ) . '">' . "\n";
			echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
			echo '<meta property="og:url" content="' . esc_url( $canonical ) . '">' . "\n";
			echo '<meta property="og:type" content="website">' . "\n";

			// Inject SoftwareApplication Schema only when appropriate and on clean canonical URL
			$enable_schema = apply_filters( 'cdt_enable_structured_data', true );
			if ( $enable_schema && ! $has_state_params ) {
				$schema = array(
					'@context'            => 'https://schema.org',
					'@type'               => 'SoftwareApplication',
					'name'                => $tool['name_en'],
					'headline'            => $tool['title'],
					'description'         => $desc,
					'url'                 => $canonical,
					'applicationCategory' => 'DeveloperApplication',
					'operatingSystem'     => 'All',
					'offers'              => array(
						'@type'         => 'Offer',
						'price'         => '0',
						'priceCurrency' => 'USD',
					),
					'publisher'           => array(
						'@type' => 'Organization',
						'name'  => get_bloginfo( 'name' ),
						'url'   => home_url( '/' ),
					),
				);
				echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "</script>\n";
			}
		}
	}
}
add_action( 'wp_head', 'cdt_tools_meta_tags', 5 );
