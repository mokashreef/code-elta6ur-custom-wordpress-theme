<?php
/**
 * قالب مخصص لقسم الذكاء الاصطناعي (AI Category Archive)
 * Code Elta6ur AI Hub & Articles Archive
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<!-- AI Hub Hero Section -->
<div class="cdt-page-hero" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(1, 112, 185, 0.9) 100%);">
	<div class="cdt-container">
		<div class="cdt-hero-badge" style="margin-block-end: var(--space-3, 0.75rem);">
			<span class="cdt-badge cdt-badge-accent">🤖 <?php esc_html_e( 'عالم الذكاء الاصطناعي والتقنيات التوليدية', 'code-elta6ur' ); ?></span>
			<span class="cdt-badge cdt-badge-dark" style="margin-inline-start: var(--space-2, 0.5rem);"><?php esc_html_e( 'محتوى متجدد 2026', 'code-elta6ur' ); ?></span>
		</div>
		<h1 class="cdt-page-hero-title">
			<?php esc_html_e( 'قسم الذكاء الاصطناعي: أدوات، شروحات، وهندسة الأوامر', 'code-elta6ur' ); ?>
		</h1>
		<p class="cdt-page-hero-desc">
			<?php esc_html_e( 'دليلك المتكامل لاستثمار ثورة الذكاء الاصطناعي في البرمجة، مقارنة النماذج التوليدية الكبرى (ChatGPT, Claude, DeepSeek)، وهندسة الأوامر Prompts لبناء تطبيقات ذكية.', 'code-elta6ur' ); ?>
		</p>

		<!-- AI Hub Feature Pills -->
		<div style="display: flex; gap: var(--space-3, 0.75rem); justify-content: center; flex-wrap: wrap; margin-block-start: var(--space-6, 1.5rem);">
			<div style="background: rgba(255,255,255,0.12); padding: 8px 18px; border-radius: var(--radius-full, 9999px); font-size: 0.9rem; font-weight: 600; color: #FFFFFF;">
				🧠 <?php esc_html_e( 'هندسة الأوامر (Prompt Engineering)', 'code-elta6ur' ); ?>
			</div>
			<div style="background: rgba(255,255,255,0.12); padding: 8px 18px; border-radius: var(--radius-full, 9999px); font-size: 0.9rem; font-weight: 600; color: #FFFFFF;">
				⚖️ <?php esc_html_e( 'مقارنات النماذج والأدوات الحية', 'code-elta6ur' ); ?>
			</div>
			<div style="background: rgba(255,255,255,0.12); padding: 8px 18px; border-radius: var(--radius-full, 9999px); font-size: 0.9rem; font-weight: 600; color: #FFFFFF;">
				🚀 <?php esc_html_e( 'تطبيقات المطورين وسوق العمل', 'code-elta6ur' ); ?>
			</div>
		</div>
	</div>
</div>

<?php cdt_breadcrumbs(); ?>

<!-- AI Articles Section -->
<div class="cdt-section">
	<div class="cdt-container">
		<div class="cdt-section-header" style="text-align: right; margin-block-end: var(--space-8, 2rem);">
			<div class="cdt-section-badge">
				<span class="cdt-badge"><?php esc_html_e( 'مقالات وشروحات القسم', 'code-elta6ur' ); ?></span>
			</div>
			<h2 class="cdt-section-title" style="font-size: var(--font-size-2xl, 1.5rem);">
				<?php esc_html_e( 'أحدث مقالات الذكاء الاصطناعي في المنصة', 'code-elta6ur' ); ?>
			</h2>
			<p class="cdt-section-desc" style="margin-inline: 0;">
				<?php esc_html_e( 'جميع المقالات والشروحات والتحليلات المتخصصة في الذكاء الاصطناعي مرتبة لتسهيل القراءة والتطبيق.', 'code-elta6ur' ); ?>
			</p>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="cdt-grid-3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/cards/card-post' );
				endwhile;
				?>
			</div>

			<div class="cdt-pagination">
				<?php
				the_posts_pagination(
					array(
						'prev_text' => '&larr; ' . esc_html__( 'السابق', 'code-elta6ur' ),
						'next_text' => esc_html__( 'التالي', 'code-elta6ur' ) . ' &rarr;',
					)
				);
				?>
			</div>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content/content-none' ); ?>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
