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

			// Related Posts Section (Transient Cached for Fast Performance & Deterministic Crawling)
			$related_posts = function_exists( 'cdt_seo_get_related_posts' ) ? cdt_seo_get_related_posts( get_the_ID(), 3 ) : array();
			if ( empty( $related_posts ) ) {
				$course_cats   = function_exists( 'cdt_get_course_category_ids' ) ? cdt_get_course_category_ids() : array();
				$related_posts = get_posts(
					array(
						'post_type'           => 'post',
						'post_status'         => 'publish',
						'category__in'        => wp_get_post_categories( get_the_ID() ),
						'category__not_in'    => $course_cats,
						'post__not_in'        => array( get_the_ID() ),
						'posts_per_page'      => 3,
						'ignore_sticky_posts' => 1,
					)
				);
			}
			if ( ! empty( $related_posts ) ) :
				?>
				<div class="cdt-related-posts" style="margin-block-start: var(--space-12, 3rem); padding-block-start: var(--space-8, 2rem); border-block-start: 1px solid var(--brand-border, #E2E8F0);">
					<h3 style="font-size: var(--font-size-2xl, 1.5rem); margin-block-end: var(--space-6, 1.5rem);">
						<?php esc_html_e( 'مقالات ذات صلة قد تهمك', 'code-elta6ur' ); ?>
					</h3>
					<div class="cdt-grid-3">
						<?php
						foreach ( $related_posts as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
							setup_postdata( $post );
							get_template_part( 'template-parts/cards/card-post' );
						endforeach;
						wp_reset_postdata();
						?>
					</div>
				</div>
				<?php
			endif;

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
