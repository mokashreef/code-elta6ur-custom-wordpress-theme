<?php
/**
 * قالب المقال الفردي الشامل
 * Code Elta6ur Single Post Template
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php cdt_breadcrumbs(); ?>

<div class="cdt-section" style="padding-block-start: var(--space-6, 1.5rem);">
	<div class="cdt-container-narrow">
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content/content-single' );

			// Post Navigation
			the_post_navigation(
				array(
					'prev_text' => '<span class="cdt-nav-subtitle">' . esc_html__( 'المقال السابق:', 'code-elta6ur' ) . '</span> <span class="cdt-nav-title">%title</span>',
					'next_text' => '<span class="cdt-nav-subtitle">' . esc_html__( 'المقال التالي:', 'code-elta6ur' ) . '</span> <span class="cdt-nav-title">%title</span>',
				)
			);

			// Related Posts Section
			$current_categories = wp_get_post_categories( get_the_ID() );
			if ( ! empty( $current_categories ) ) {
				$related_query = new WP_Query(
					array(
						'category__in'   => $current_categories,
						'post__not_in'   => array( get_the_ID() ),
						'posts_per_page' => 3,
						'orderby'        => 'rand',
					)
				);

				if ( $related_query->have_posts() ) :
					?>
					<div class="cdt-related-posts" style="margin-block-start: var(--space-12, 3rem); padding-block-start: var(--space-8, 2rem); border-block-start: 1px solid var(--brand-border, #E2E8F0);">
						<h3 style="font-size: var(--font-size-2xl, 1.5rem); margin-block-end: var(--space-6, 1.5rem);">
							<?php esc_html_e( 'مقالات ذات صلة قد تهمك', 'code-elta6ur' ); ?>
						</h3>
						<div class="cdt-grid-3">
							<?php
							while ( $related_query->have_posts() ) :
								$related_query->the_post();
								get_template_part( 'template-parts/cards/card-post' );
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</div>
					<?php
				endif;
			}

			// Comments
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;
		?>
	</div>
</div>

<?php
get_footer();
