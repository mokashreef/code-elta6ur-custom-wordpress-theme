<?php
/**
 * قالب مخصص لقسم وتصنيفات الذكاء الاصطناعي (AI Category Archive & Dynamic Subcategories)
 * Code Elta6ur AI Hub & Subcategories Archive
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$current_term  = get_queried_object();
$ai_cat_id     = function_exists( 'cdt_get_ai_category_id' ) ? cdt_get_ai_category_id() : 9;
$is_parent_ai  = ( $current_term && ( (int) $current_term->term_id === (int) $ai_cat_id || 'الذكاء-الاصطناعي' === urldecode( $current_term->slug ?? '' ) ) );
$subcategories = function_exists( 'cdt_get_ai_child_categories' ) ? cdt_get_ai_child_categories( false ) : array();

// Determine titles and descriptions dynamically based on whether viewing parent or child
if ( $is_parent_ai || ! $current_term || empty( $current_term->term_id ) ) {
	$badge_text = esc_html__( 'عالم الذكاء الاصطناعي والتقنيات التوليدية', 'code-elta6ur' );
	$hero_title = esc_html__( 'قسم الذكاء الاصطناعي: أدوات، شروحات، وهندسة الأوامر', 'code-elta6ur' );
	$hero_desc  = esc_html__( 'دليلك المتكامل لاستثمار ثورة الذكاء الاصطناعي في البرمجة، مقارنة النماذج التوليدية الكبرى (ChatGPT, Claude, DeepSeek)، وهندسة الأوامر Prompts لبناء تطبيقات ذكية.', 'code-elta6ur' );
	$sec_title  = esc_html__( 'أحدث مقالات الذكاء الاصطناعي في المنصة', 'code-elta6ur' );
	$sec_desc   = esc_html__( 'جميع المقالات والشروحات والتحليلات المتخصصة في الذكاء الاصطناعي مرتبة لتسهيل القراءة والتطبيق.', 'code-elta6ur' );
} else {
	$badge_text = sprintf( esc_html__( 'قسم الذكاء الاصطناعي · %s', 'code-elta6ur' ), $current_term->name );
	$hero_title = single_cat_title( '', false );
	$term_desc  = category_description();
	$hero_desc  = ! empty( $term_desc ) ? wp_strip_all_tags( $term_desc ) : sprintf( esc_html__( 'دليلك وشروحاتك المتخصصة في %s ضمن قسم الذكاء الاصطناعي في كود التطور.', 'code-elta6ur' ), $current_term->name );
	$sec_title  = sprintf( esc_html__( 'مقالات وتطبيقات: %s', 'code-elta6ur' ), $current_term->name );
	$sec_desc   = sprintf( esc_html__( 'تصفح أحدث الشروحات والمقالات المنشورة في تصنيف %s والمحدثة لمواكبة أحدث تقنيات الذكاء الاصطناعي.', 'code-elta6ur' ), $current_term->name );
}
?>

<!-- AI Hub Hero Section -->
<div class="cdt-page-hero cdt-ai-hero" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(1, 112, 185, 0.9) 100%);">
	<div class="cdt-container">
		<div class="cdt-hero-badge" style="margin-block-end: var(--space-3, 0.75rem);">
			<span class="cdt-badge cdt-badge-accent">🤖 <?php echo esc_html( $badge_text ); ?></span>
			<span class="cdt-badge cdt-badge-dark" style="margin-inline-start: var(--space-2, 0.5rem);"><?php esc_html_e( 'محتوى متجدد 2026', 'code-elta6ur' ); ?></span>
		</div>
		<h1 class="cdt-page-hero-title">
			<?php echo esc_html( $hero_title ); ?>
		</h1>
		<p class="cdt-page-hero-desc">
			<?php echo esc_html( $hero_desc ); ?>
		</p>

		<!-- Dynamic AI Subcategories Filter Tabs -->
		<nav class="cdt-ai-subnav" aria-label="<?php esc_attr_e( 'تصنيفات الذكاء الاصطناعي', 'code-elta6ur' ); ?>">
			<div class="cdt-ai-subnav-list">
				<!-- All AI Posts Tab -->
				<a href="<?php echo esc_url( cdt_get_ai_section_url() ); ?>" class="cdt-ai-pill <?php echo $is_parent_ai ? 'is-active' : ''; ?>">
					<span><?php esc_html_e( 'كل المقالات', 'code-elta6ur' ); ?></span>
				</a>

				<?php if ( ! empty( $subcategories ) ) : ?>
					<?php foreach ( $subcategories as $subcat ) : ?>
						<?php
						$is_sub_active = ( $current_term && (int) $current_term->term_id === (int) $subcat->term_id );
						?>
						<a href="<?php echo esc_url( get_category_link( $subcat->term_id ) ); ?>" class="cdt-ai-pill <?php echo $is_sub_active ? 'is-active' : ''; ?>">
							<span><?php echo esc_html( $subcat->name ); ?></span>
							<?php if ( ! empty( $subcat->count ) ) : ?>
								<span class="cdt-ai-pill-count"><?php echo esc_html( number_format_i18n( $subcat->count ) ); ?></span>
							<?php endif; ?>
						</a>
					<?php endforeach; ?>
				<?php else : ?>
					<!-- Fallback feature highlights when no child categories are added yet -->
					<div class="cdt-ai-pill is-static">🧠 <?php esc_html_e( 'هندسة الأوامر (Prompt Engineering)', 'code-elta6ur' ); ?></div>
					<div class="cdt-ai-pill is-static">⚖️ <?php esc_html_e( 'مقارنات النماذج والأدوات الحية', 'code-elta6ur' ); ?></div>
					<div class="cdt-ai-pill is-static">🚀 <?php esc_html_e( 'تطبيقات المطورين وسوق العمل', 'code-elta6ur' ); ?></div>
				<?php endif; ?>
			</div>
		</nav>
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
				<?php echo esc_html( $sec_title ); ?>
			</h2>
			<p class="cdt-section-desc" style="margin-inline: 0;">
				<?php echo esc_html( $sec_desc ); ?>
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
