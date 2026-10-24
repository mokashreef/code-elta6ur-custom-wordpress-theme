<?php
/**
 * نظام التوجيه والروابط لمحرر الأكواد
 * Code Elta6ur Online Code Editor Router
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register query vars for Editor
 *
 * @param array $vars Registered query vars.
 * @return array
 */
function cdt_editor_query_vars( $vars ) {
	$vars[] = 'cdt_editor_hub';
	$vars[] = 'cdt_editor_lang';
	return $vars;
}
add_filter( 'query_vars', 'cdt_editor_query_vars' );

/**
 * Add rewrite rules for Editor Engine
 */
function cdt_editor_rewrite_rules() {
	add_rewrite_rule( '^editor/?$', 'index.php?cdt_editor_hub=1', 'top' );
	add_rewrite_rule( '^editor/([^/]+)/?$', 'index.php?cdt_editor_lang=$matches[1]', 'top' );
}
add_action( 'init', 'cdt_editor_rewrite_rules' );

/**
 * Check if current request is for the editor
 *
 * @return string|false 'hub', 'lang', or false.
 */
function cdt_get_current_editor_route() {
	if ( ! empty( get_query_var( 'cdt_editor_hub' ) ) ) {
		return 'hub';
	}

	$lang = get_query_var( 'cdt_editor_lang' );
	if ( ! empty( $lang ) ) {
		return 'lang';
	}

	// Graceful fallback from REQUEST_URI
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$path        = wp_parse_url( $request_uri, PHP_URL_PATH );
	if ( ! $path ) {
		return false;
	}

	$trimmed = trim( rawurldecode( $path ), '/' );

	if ( 'editor' === $trimmed ) {
		return 'hub';
	}

	if ( 0 === strpos( $trimmed, 'editor/' ) ) {
		$parts = explode( '/', $trimmed );
		if ( 2 === count( $parts ) && ! empty( $parts[1] ) ) {
			return 'lang';
		}
	}

	return false;
}

/**
 * Get active language slug for the editor
 *
 * @return string
 */
function cdt_get_current_editor_lang() {
	$lang = get_query_var( 'cdt_editor_lang' );
	if ( ! empty( $lang ) ) {
		return sanitize_key( $lang );
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$path        = wp_parse_url( $request_uri, PHP_URL_PATH );
	if ( $path ) {
		$trimmed = trim( rawurldecode( $path ), '/' );
		if ( 0 === strpos( $trimmed, 'editor/' ) ) {
			$parts = explode( '/', $trimmed );
			if ( 2 === count( $parts ) && ! empty( $parts[1] ) ) {
				return sanitize_key( $parts[1] );
			}
		}
	}

	// Default language on /editor/ landing is Python
	return 'python';
}

/**
 * Intercept template routing for Editor
 *
 * @param string $template Default template.
 * @return string
 */
function cdt_editor_template_include( $template ) {
	$route = cdt_get_current_editor_route();

	if ( 'hub' === $route || 'lang' === $route ) {
		$lang_slug = cdt_get_current_editor_lang();
		$language  = cdt_get_editor_language( $lang_slug );

		if ( $language || 'hub' === $route ) {
			$editor_template = locate_template( array( 'template-editor.php' ) );
			if ( $editor_template ) {
				status_header( 200 );
				return $editor_template;
			}
		} else {
			// Unknown language slug -> 404
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
add_filter( 'template_include', 'cdt_editor_template_include', 99 );

/**
 * Filter document title for Editor pages
 *
 * @param array $title_parts Title parts.
 * @return array
 */
function cdt_editor_document_title_parts( $title_parts ) {
	$route = cdt_get_current_editor_route();

	if ( 'hub' === $route ) {
		$title_parts['title'] = 'محرر الأكواد البرمجية أونلاين (Online Code Editor & Compiler) | كود التطور';
	} elseif ( 'lang' === $route ) {
		$slug     = cdt_get_current_editor_lang();
		$language = cdt_get_editor_language( $slug );
		if ( $language && ! empty( $language['seo_title'] ) ) {
			$title_parts['title'] = $language['seo_title'];
		}
	}

	return $title_parts;
}
add_filter( 'document_title_parts', 'cdt_editor_document_title_parts', 99 );

/**
 * Inject Editor SEO Meta Tags & Schema in Head
 */
function cdt_editor_meta_tags() {
	$route = cdt_get_current_editor_route();
	if ( ! $route ) {
		return;
	}

	$slug     = cdt_get_current_editor_lang();
	$language = cdt_get_editor_language( $slug );

	// Scoped noindex strictly for stateful editor URLs (e.g. ?code=..., ?stdin=...)
	$state_params     = array( 'code', 'stdin', 'input', 'state' );
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

	if ( 'hub' === $route && 'python' === $slug ) {
		$desc      = 'محرر ومترجم أكواد برمجية متكامل أونلاين يدعم 15 لغة برمجية مع بيئة تشغيل سريعة ومعزولة مجاناً بدون تثبيت.';
		$canonical = home_url( '/editor/' );
		$seo_title = 'محرر ومترجم الأكواد البرمجية أونلاين (Online Code Editor) | كود التطور';
	} elseif ( $language ) {
		$desc      = $language['seo_desc'];
		$canonical = home_url( '/editor/' . $language['slug'] . '/' );
		$seo_title = $language['seo_title'];
	} else {
		return;
	}

	echo "\n<!-- Code Elta6ur Online Code Editor SEO -->\n";
	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $seo_title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $canonical ) . '">' . "\n";
	echo '<meta property="og:type" content="website">' . "\n";

	// Inject WebApplication Schema for Editor only when appropriate on clean canonical URL
	$enable_schema = apply_filters( 'cdt_enable_structured_data', true );
	if ( $enable_schema && ! $has_state_params ) {
		$schema = array(
			'@context'            => 'https://schema.org',
			'@type'               => 'WebApplication',
			'name'                => $language ? $language['title'] : 'Code Elta6ur Online Code Editor',
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
add_action( 'wp_head', 'cdt_editor_meta_tags', 5 );
