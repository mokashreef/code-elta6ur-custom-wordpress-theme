<?php
/**
 * ترويسة الموقع العامة
 * Code Elta6ur Theme Header
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?> dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>

<a class="cdt-skip-link" href="#main-content">
	<?php esc_html_e( 'تخطي إلى المحتوى الرئيسي', 'code-elta6ur' ); ?>
</a>

<div class="cdt-site-wrapper">
	<header class="cdt-site-header" role="banner">
		<div class="cdt-container">
			<div class="cdt-header-inner">
				<?php get_template_part( 'template-parts/header/site-branding' ); ?>

				<?php get_template_part( 'template-parts/header/primary-nav' ); ?>

				<div class="cdt-header-actions">
					<button class="cdt-search-toggle" aria-label="<?php esc_attr_e( 'فتح البحث', 'code-elta6ur' ); ?>">
						<?php echo cdt_svg_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>

					<button class="cdt-mobile-toggle" aria-label="<?php esc_attr_e( 'فتح القائمة', 'code-elta6ur' ); ?>" aria-controls="cdt-mobile-drawer">
						<?php echo cdt_svg_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				</div>
			</div>
		</div>
	</header>

	<?php get_template_part( 'template-parts/header/mobile-nav' ); ?>

	<!-- Header Search Modal -->
	<div class="cdt-search-modal" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'البحث في الموقع', 'code-elta6ur' ); ?>">
		<div class="cdt-search-modal-content">
			<button class="cdt-search-modal-close" aria-label="<?php esc_attr_e( 'إغلاق البحث', 'code-elta6ur' ); ?>">
				<?php echo cdt_svg_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
			<h3 style="font-size: var(--font-size-xl, 1.25rem); margin-block-end: var(--space-4, 1rem);">
				<?php esc_html_e( 'البحث في مقالات وكورسات كود التطور', 'code-elta6ur' ); ?>
			</h3>
			<?php get_search_form(); ?>
		</div>
	</div>

	<main id="main-content" class="cdt-main-content">
