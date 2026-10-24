<?php
/**
 * Template Name: كود التطور: صفحة المدونة والمقالات
 * Template Post Type: page
 *
 * قالب مخصص لصفحة المدونة لعرض كافة المقالات والتصنيفات بنظام كود التطور
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<!-- Blog Page Hero -->
<div class="cdt-page-hero">
	<div class="cdt-container">
		<div class="cdt-hero-badge" style="margin-block-end: var(--space-3, 0.75rem);">
			<span class="cdt-badge cdt-badge-dark"><?php esc_html_e( 'المدونة البرمجية', 'code-elta6ur' ); ?></span>
		</div>
		<h1 class="cdt-page-hero-title">
			<?php esc_html_e( 'المقالات والشروحات التقنية', 'code-elta6ur' ); ?>
		</h1>
		<p class="cdt-page-hero-desc">
			<?php esc_html_e( 'أكثر من 370 مقالاً ودرساً متخصصاً في لغات البرمجة، تطوير الويب، أدوات الذكاء الاصطناعي، وسوق العمل.', 'code-elta6ur' ); ?>
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

			<a href="<?php echo esc_url( function_exists( 'cdt_get_ai_section_url' ) ? cdt_get_ai_section_url() : home_url( '/category/%d8%a7%d9%84%d8%b0%d9%83%d8%a7%d8%a1-%d8%a7%d9%84%d8%a7%d8%b5%d8%b7%d9%86%d8%a7%d8%b9%d9%8a/' ) ); ?>" class="cdt-badge cdt-badge-accent" style="padding: 6px 16px; font-size: 0.85rem; text-decoration: none;">
				🤖 <?php esc_html_e( 'قسم الذكاء الاصطناعي', 'code-elta6ur' ); ?>
			</a>

			<?php
			$categories = function_exists( 'cdt_get_general_blog_categories' ) ? cdt_get_general_blog_categories( array( 'number' => 12 ) ) : get_categories( array( 'number' => 12, 'hide_empty' => true ) );

			foreach ( $categories as $cat ) :
				// Skip AI category here as it has its own dedicated prominent badge above
				if ( (int) $cat->term_id === 9 || 'الذكاء-الاصطناعي' === urldecode( $cat->slug ) ) {
					continue;
				}
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

<!-- Blog Posts Grid -->
<div class="cdt-section">
	<div class="cdt-container">
		<?php
		$current_paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
		$course_cat_ids = function_exists( 'cdt_get_course_category_ids' ) ? cdt_get_course_category_ids() : array();

		$blog_query_args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 12,
			'paged'               => $current_paged,
			'ignore_sticky_posts' => true,
		);

		// Exclude courses lessons from general blog
		if ( ! empty( $course_cat_ids ) ) {
			$blog_query_args['category__not_in'] = $course_cat_ids;
		}

		$blog_query = new WP_Query( $blog_query_args );

		if ( $blog_query->have_posts() ) :
			?>
			<div class="cdt-grid-3">
				<?php
				while ( $blog_query->have_posts() ) :
					$blog_query->the_post();
					get_template_part( 'template-parts/cards/card-post' );
				endwhile;
				?>
			</div>

			<div class="cdt-pagination">
				<?php
				$big = 999999999;
				echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					array(
						'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'    => '?paged=%#%',
						'current'   => $current_paged,
						'total'     => $blog_query->max_num_pages,
						'prev_text' => '&larr; ' . esc_html__( 'السابق', 'code-elta6ur' ),
						'next_text' => esc_html__( 'التالي', 'code-elta6ur' ) . ' &rarr;',
					)
				);
				?>
			</div>
			<?php
			wp_reset_postdata();
		else :
			get_template_part( 'template-parts/content/content-none' );
		endif;
		?>
	</div>
</div>

<?php
get_footer();
