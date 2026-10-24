<?php
/**
 * صفحة الخطأ 404
 * Code Elta6ur 404 Error Template
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="cdt-section">
	<div class="cdt-container-narrow">
		<div class="cdt-404-wrapper">
			<div class="cdt-404-code">404</div>

			<h1 class="cdt-404-title">
				<?php esc_html_e( 'عذراً! الصفحة المطلوبة غير موجودة', 'code-elta6ur' ); ?>
			</h1>

			<p class="cdt-404-desc">
				<?php esc_html_e( 'يبدو أن الرابط الذي حاولت الوصول إليه قد تم نقله أو تغييره، أو ربما كتبت العنوان بشكل غير دقيق.', 'code-elta6ur' ); ?>
			</p>

			<div style="max-width: 480px; margin-inline: auto; margin-block-end: var(--space-8, 2rem);">
				<?php get_search_form(); ?>
			</div>

			<div style="display: flex; align-items: center; justify-content: center; gap: var(--space-3, 0.75rem); flex-wrap: wrap;">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cdt-btn cdt-btn-primary">
					<?php esc_html_e( 'العودة للصفحة الرئيسية', 'code-elta6ur' ); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/' ) ); ?>" class="cdt-btn cdt-btn-secondary">
					<?php esc_html_e( 'تصفح الكورسات', 'code-elta6ur' ); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%85%d8%af%d9%88%d9%86%d8%a9/' ) ); ?>" class="cdt-btn cdt-btn-secondary">
					<?php esc_html_e( 'المدونة التقنية', 'code-elta6ur' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
