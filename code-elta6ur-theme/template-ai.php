<?php
/**
 * Template Name: كود التطور: قسم الذكاء الاصطناعي
 * Template Post Type: page
 *
 * قالب مخصص لصفحة قسم الذكاء الاصطناعي وعرض مقالاته وتصنيفاته الفرعية
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ai_cat_id     = function_exists( 'cdt_get_ai_category_id' ) ? cdt_get_ai_category_id() : 9;
$subcategories = function_exists( 'cdt_get_ai_child_categories' ) ? cdt_get_ai_child_categories( false ) : array();

// Support filtering by subcategory if passed via query param (e.g. ?subcat=123)
$active_subcat_id = 0;
if ( isset( $_GET['subcat'] ) && is_numeric( $_GET['subcat'] ) ) {
	$requested_subcat = (int) $_GET['subcat'];
	if ( function_exists( 'cdt_is_ai_category_or_child' ) && cdt_is_ai_category_or_child( $requested_subcat ) ) {
		$active_subcat_id = $requested_subcat;
	}
}

$query_cat_id = $active_subcat_id > 0 ? $active_subcat_id : $ai_cat_id;
$is_filtered  = ( $active_subcat_id > 0 && $active_subcat_id !== $ai_cat_id );
$active_term  = $is_filtered ? get_category( $active_subcat_id ) : null;

$badge_text = ( $is_filtered && $active_term ) ? sprintf( esc_html__( 'قسم الذكاء الاصطناعي · %s', 'code-elta6ur' ), $active_term->name ) : esc_html__( 'عالم الذكاء الاصطناعي والتقنيات التوليدية', 'code-elta6ur' );
$hero_title = ( $is_filtered && $active_term ) ? $active_term->name : esc_html__( 'قسم الذكاء الاصطناعي: أدوات، شروحات، وهندسة الأوامر', 'code-elta6ur' );
$hero_desc  = ( $is_filtered && $active_term && ! empty( $active_term->description ) ) ? wp_strip_all_tags( $active_term->description ) : esc_html__( 'دليلك المتكامل لاستثمار ثورة الذكاء الاصطناعي في البرمجة، مقارنة النماذج التوليدية الكبرى (ChatGPT, Claude, DeepSeek)، وهندسة الأوامر Prompts لبناء تطبيقات ذكية.', 'code-elta6ur' );
$sec_title  = ( $is_filtered && $active_term ) ? sprintf( esc_html__( 'مقالات وتطبيقات: %s', 'code-elta6ur' ), $active_term->name ) : esc_html__( 'أحدث مقالات الذكاء الاصطناعي في المنصة', 'code-elta6ur' );
$sec_desc   = ( $is_filtered && $active_term ) ? sprintf( esc_html__( 'تصفح أحدث الشروحات والمقالات المتخصصة في تصنيف %s ضمن قسم الذكاء الاصطناعي.', 'code-elta6ur' ), $active_term->name ) : esc_html__( 'جميع المقالات والشروحات والتحليلات المتخصصة في الذكاء الاصطناعي مرتبة لتسهيل القراءة والتطبيق.', 'code-elta6ur' );
?>

<!-- AI Page Hero Section -->
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
				<a href="<?php echo esc_url( get_permalink() ); ?>" class="cdt-ai-pill <?php echo ! $is_filtered ? 'is-active' : ''; ?>">
					<span><?php esc_html_e( 'كل المقالات', 'code-elta6ur' ); ?></span>
				</a>

				<?php if ( ! empty( $subcategories ) ) : ?>
					<?php foreach ( $subcategories as $subcat ) : ?>
						<?php
						$is_sub_active = ( (int) $active_subcat_id === (int) $subcat->term_id );
						$subcat_url    = add_query_arg( 'subcat', (int) $subcat->term_id, get_permalink() );
						?>
						<a href="<?php echo esc_url( $subcat_url ); ?>" class="cdt-ai-pill <?php echo $is_sub_active ? 'is-active' : ''; ?>">
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

		<?php
		$current_paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );

		$ai_query = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'cat'                 => $query_cat_id,
				'posts_per_page'      => 12,
				'paged'               => $current_paged,
				'ignore_sticky_posts' => true,
			)
		);

		if ( $ai_query->have_posts() ) :
			?>
			<div class="cdt-grid-3">
				<?php
				while ( $ai_query->have_posts() ) :
					$ai_query->the_post();
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
						'total'     => $ai_query->max_num_pages,
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
