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
