<?php
/**
 * رأس الصفحات الداخلية النظيف
 * Code Elta6ur Internal Page Hero
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="cdt-page-hero">
	<div class="cdt-container">
		<h1 class="cdt-page-hero-title">
			<?php
			if ( is_archive() ) {
				the_archive_title();
			} elseif ( is_search() ) {
				/* translators: %s: Search query */
				printf( esc_html__( 'نتائج البحث عن: %s', 'code-elta6ur' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
			} elseif ( is_home() ) {
				esc_html_e( 'المدونة والمقالات التقنية', 'code-elta6ur' );
			} else {
				the_title();
			}
			?>
		</h1>
		<?php if ( is_archive() && get_the_archive_description() ) : ?>
			<div class="cdt-page-hero-desc">
				<?php the_archive_description(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
