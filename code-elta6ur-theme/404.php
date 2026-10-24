<?php
/**
 * صفحة الخطأ 404 المهيأة للسيو وتجربة المستخدم
 * Code Elta6ur 404 Error Template (Smart Suggestions & Clean Status)
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Send true HTTP 404 status and prevent caching of missing endpoints
status_header( 404 );
nocache_headers();

get_header();

// Fetch smart suggestions based on requested path
$requested_path    = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
$norm_path         = function_exists( 'cdt_seo_normalize_path' ) ? cdt_seo_normalize_path( $requested_path ) : '';
$smart_suggestions = function_exists( 'cdt_seo_find_smart_404_suggestions' ) ? cdt_seo_find_smart_404_suggestions( $norm_path, 3 ) : array();
?>

<div class="cdt-section">
	<div class="cdt-container-narrow">
		<div class="cdt-404-wrapper" style="text-align: center; padding-block: var(--space-8, 2rem);">
			<div class="cdt-404-code" style="font-size: clamp(4rem, 12vw, 7rem); font-weight: 900; line-height: 1; color: var(--brand-primary, #0170B9); margin-block-end: var(--space-2, 0.5rem);">404</div>

			<h1 class="cdt-404-title" style="font-size: clamp(1.5rem, 4vw, 2rem); font-weight: 800; margin-block-end: var(--space-4, 1rem);">
				<?php esc_html_e( 'عذراً! الصفحة المطلوبة غير موجودة', 'code-elta6ur' ); ?>
			</h1>

			<p class="cdt-404-desc" style="color: var(--brand-text-muted, #64748B); font-size: 1.05rem; max-width: 580px; margin-inline: auto; margin-block-end: var(--space-6, 1.5rem); line-height: 1.6;">
				<?php esc_html_e( 'يبدو أن الرابط الذي حاولت الوصول إليه قد تم نقله أو تغييره، أو ربما كُتب العنوان بشكل غير دقيق.', 'code-elta6ur' ); ?>
			</p>

			<?php if ( ! empty( $smart_suggestions ) ) : ?>
				<div class="cdt-404-suggestions-box" style="margin-block-end: var(--space-8, 2rem); padding: var(--space-6, 1.5rem); background: rgba(1, 112, 185, 0.04); border: 1px solid rgba(1, 112, 185, 0.15); border-radius: var(--radius-lg, 18px); text-align: right;">
					<h3 style="font-size: 1.1rem; color: var(--brand-primary, #0170B9); margin-block-end: var(--space-3, 0.75rem); display: flex; align-items: center; gap: 8px;">
						<span>💡</span>
						<span><?php esc_html_e( 'هل كنت تقصد إحدى هذه الصفحات؟', 'code-elta6ur' ); ?></span>
					</h3>
					<ul style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 10px;">
						<?php foreach ( $smart_suggestions as $suggestion ) : ?>
							<li style="display: flex; align-items: center; justify-content: space-between; background: #ffffff; padding: 12px 16px; border-radius: var(--radius-sm, 10px); border: 1px solid var(--brand-border, #E2E8F0);">
								<a href="<?php echo esc_url( $suggestion['url'] ); ?>" style="font-weight: 700; color: var(--brand-text, #0F172A); text-decoration: none;">
									<?php echo esc_html( $suggestion['title'] ); ?>
								</a>
								<a href="<?php echo esc_url( $suggestion['url'] ); ?>" class="cdt-btn-secondary" style="padding: 6px 14px; font-size: 0.85rem; border-radius: var(--radius-full, 9999px);">
									<?php esc_html_e( 'زيارة الصفحة', 'code-elta6ur' ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

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
				<a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>" class="cdt-btn cdt-btn-secondary">
					<?php esc_html_e( 'الأدوات التقنية', 'code-elta6ur' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
