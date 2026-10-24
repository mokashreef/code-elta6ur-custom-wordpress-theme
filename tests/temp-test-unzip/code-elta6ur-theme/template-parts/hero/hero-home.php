<?php
/**
 * بانر الهيرو في الصفحة الرئيسية
 * Code Elta6ur Home Hero Section
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero_title    = cdt_get_option( 'cdt_hero_title' );
$hero_subtitle = cdt_get_option( 'cdt_hero_subtitle' );
$cta_text      = cdt_get_option( 'cdt_hero_cta_text' );
$cta_url       = cdt_get_option( 'cdt_hero_cta_url' );
?>

<section class="cdt-hero" aria-label="<?php esc_attr_e( 'القسم الترحيبي', 'code-elta6ur' ); ?>">
	<div class="cdt-container">
		<div class="cdt-hero-content">
			<div class="cdt-hero-badge">
				<span class="cdt-badge cdt-badge-accent">
					✨ <?php esc_html_e( 'منصة كود التطور التعليمية', 'code-elta6ur' ); ?>
				</span>
			</div>

			<h1 class="cdt-hero-title">
				<?php echo esc_html( $hero_title ); ?>
			</h1>

			<p class="cdt-hero-desc">
				<?php echo esc_html( $hero_subtitle ); ?>
			</p>

			<div class="cdt-hero-buttons">
				<?php if ( ! empty( $cta_url ) && ! empty( $cta_text ) ) : ?>
					<a href="<?php echo esc_url( $cta_url ); ?>" class="cdt-btn cdt-btn-primary cdt-btn-lg">
						<?php echo esc_html( $cta_text ); ?>
						<?php echo cdt_svg_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				<?php endif; ?>

				<a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%85%d8%af%d9%88%d9%86%d8%a9/' ) ); ?>" class="cdt-btn cdt-btn-secondary cdt-btn-lg">
					<?php esc_html_e( 'تصفّح المقالات التقنية', 'code-elta6ur' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
