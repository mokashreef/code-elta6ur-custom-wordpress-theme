<?php
/**
 * صفحة أرشيف المدونة والمقالات
 * Code Elta6ur Blog Index Template
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="cdt-page-hero">
	<div class="cdt-container">
		<h1 class="cdt-page-hero-title">
			<?php esc_html_e( 'المدونة والمقالات التقنية', 'code-elta6ur' ); ?>
		</h1>
		<p class="cdt-page-hero-desc">
			<?php esc_html_e( 'شروحات ومقالات برمجية متخصصة ومصنفة لتساعدك في رحلتك التعليمية وتطوير مهاراتك خطوة بخطوة.', 'code-elta6ur' ); ?>
		</p>
	</div>
</div>

<?php cdt_breadcrumbs(); ?>

<!-- Category Filter Pills Bar -->
<section style="background: var(--brand-white, #FFFFFF); padding-block: var(--space-4, 1rem); border-block-end: 1px solid var(--brand-border, #E2E8F0); overflow-x: auto;">
	<div class="cdt-container">
		<div style="display: flex; align-items: center; gap: var(--space-2, 0.5rem); flex-wrap: wrap;">
			<a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%85%d8%af%d9%88%d9%86%d8%a9/' ) ); ?>" class="cdt-badge cdt-badge-dark" style="padding: 6px 16px; font-size: 0.85rem;">
				<?php esc_html_e( 'جميع المقالات', 'code-elta6ur' ); ?>
			</a>

			<?php
			$categories = get_categories(
				array(
					'orderby'    => 'count',
					'order'      => 'DESC',
					'number'     => 10,
					'hide_empty' => true,
				)
			);

			foreach ( $categories as $cat ) :
				?>
				<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="cdt-badge" style="padding: 6px 14px; font-size: 0.85rem; text-decoration: none;">
					<?php echo esc_html( $cat->name ); ?> (<?php echo esc_html( $cat->count ); ?>)
				</a>
				<?php
			endforeach;
			?>
		</div>
	</div>
</section>

<div class="cdt-section">
	<div class="cdt-container">
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
