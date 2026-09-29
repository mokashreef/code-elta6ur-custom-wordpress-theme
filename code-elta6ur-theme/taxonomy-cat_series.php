<?php
/**
 * أرشيف وقالب السلاسل التعليمية (Series)
 * Code Elta6ur Educational Series Template
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
			<?php
			if ( is_tax( 'cat_series' ) ) {
				single_term_title();
			} else {
				esc_html_e( 'السلاسل التعليمية المتكاملة', 'code-elta6ur' );
			}
			?>
		</h1>
		<p class="cdt-page-hero-desc">
			<?php esc_html_e( 'سلاسل ودورات تدريبية متسلسلة تأخذك من البداية وحتى إتقان المهارة البرمجية.', 'code-elta6ur' ); ?>
		</p>
	</div>
</div>

<?php cdt_breadcrumbs(); ?>

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
