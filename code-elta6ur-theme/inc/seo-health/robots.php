<?php
/**
 * إدارة وتوليد ملف robots.txt الديناميكي المحسن
 * Code Elta6ur Dynamic robots.txt Handler
 *
 * @package CodeElta6ur
 * @subpackage SEO_Health
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine accurate sitemap index URL based on active setup
 *
 * @return string Sitemap URL.
 */
function cdt_seo_get_sitemap_url() {
	$plugin = cdt_seo_detect_active_plugin();

	if ( 'yoast' === $plugin || 'rank_math' === $plugin || 'seopress' === $plugin ) {
		return home_url( '/sitemap_index.xml' );
	}

	return home_url( '/wp-sitemap.xml' );
}

/**
 * Filter robots.txt dynamically to optimize crawl budget and eliminate blocking errors
 *
 * @param string $output Default robots.txt content.
 * @param bool   $public Whether the site is public.
 * @return string
 */
function cdt_seo_filter_robots_txt( $output, $public ) {
	if ( '0' === (string) $public ) {
		return $output; // Respect site privacy settings if set to discourage search engines
	}

	$sitemap_url = cdt_seo_get_sitemap_url();

	$rules   = array();
	$rules[] = 'User-agent: *';
	$rules[] = 'Disallow: /wp-admin/';
	$rules[] = 'Allow: /wp-admin/admin-ajax.php';
	$rules[] = 'Allow: /wp-admin/images/';
	$rules[] = 'Disallow: /?s=';
	$rules[] = 'Disallow: /search/';
	$rules[] = 'Disallow: /login/';
	$rules[] = 'Disallow: /registration/';
	$rules[] = 'Disallow: /lost-password/';
	$rules[] = 'Disallow: /my-account/';
	$rules[] = 'Allow: /wp-content/';
	$rules[] = 'Allow: /wp-includes/';
	$rules[] = '';
	$rules[] = 'Sitemap: ' . esc_url( $sitemap_url );

	return implode( "\n", $rules ) . "\n";
}
add_filter( 'robots_txt', 'cdt_seo_filter_robots_txt', 999, 2 );

/**
 * Warn administrator if a physical robots.txt file overrides the dynamic filter
 */
function cdt_seo_check_physical_robots_file() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$physical_file = ABSPATH . 'robots.txt';
	if ( file_exists( $physical_file ) ) {
		?>
		<div class="notice notice-warning is-dismissible">
			<p>
				<strong><?php esc_html_e( '⚠️ تنبيه robots.txt من قالب كود التطور:', 'code-elta6ur' ); ?></strong>
				<?php esc_html_e( 'تم اكتشاف ملف فيزيائي (robots.txt) في المجلد الرئيسي لموقعك. وجود هذا الملف يمنع ووردبريس من تطبيق قواعد robots.txt الديناميكية الخاصة بالقالب. يُنصح بحذف الملف الفيزيائي أو دمجه مع الفلتر الديناميكي.', 'code-elta6ur' ); ?>
			</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'cdt_seo_check_physical_robots_file' );
