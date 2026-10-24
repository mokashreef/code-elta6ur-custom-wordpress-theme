<?php
/**
 * جسم الصفحة العامة
 * Code Elta6ur General Page Content
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'cdt-page-article' ); ?>>
	<div class="cdt-page-meta-header">
		<span class="cdt-page-badge">
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
			<?php esc_html_e( 'وثيقة رسمية معتمدة', 'code-elta6ur' ); ?>
		</span>
		<span class="cdt-page-date">
			<?php
			/* translators: %s: modified date */
			printf( esc_html__( 'آخر تحديث: %s', 'code-elta6ur' ), esc_html( get_the_modified_date( 'd F Y' ) ) );
			?>
		</span>
	</div>

	<?php if ( ! cdt_is_elementor_page() && has_post_thumbnail() ) : ?>
		<div class="cdt-article-featured-image">
			<?php the_post_thumbnail( 'cdt-single-hero', array( 'loading' => 'lazy', 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
		</div>
	<?php endif; ?>

	<div class="cdt-entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="cdt-pagination"><span class="page-numbers">' . esc_html__( 'الصفحات:', 'code-elta6ur' ) . '</span>',
				'after'  => '</div>',
			)
		);
		?>
	</div>
</article>
