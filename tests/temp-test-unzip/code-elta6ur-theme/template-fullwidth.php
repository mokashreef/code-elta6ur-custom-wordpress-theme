<?php
/**
 * Template Name: كود التطور: صفحة بعرض كامل (Full Width)
 * Template Post Type: page
 *
 * قالب مخصص لعرض الصفحات بعرض كامل الشاشة
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part( 'template-parts/hero/hero-page' );
cdt_breadcrumbs();
?>

<div class="cdt-section" style="padding-block: var(--space-8, 2rem);">
	<div class="cdt-container">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</div>
</div>

<?php
get_footer();
