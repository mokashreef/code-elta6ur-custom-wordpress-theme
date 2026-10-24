<?php
/**
 * القالب الموحد لصفحات الموقع العامة
 * Code Elta6ur General Page Template
 *
 * Handles both native Gutenberg/Classic pages and existing Elementor pages seamlessly.
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Code Elta6ur Page Header & Breadcrumbs
get_template_part( 'template-parts/hero/hero-page' );
cdt_breadcrumbs();

$is_elementor = cdt_is_elementor_page();
?>

<div class="cdt-section" style="padding-block: var(--space-8, 2rem);">
	<div class="<?php echo $is_elementor ? 'cdt-container' : 'cdt-container-narrow'; ?>">
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content/content-page' );

			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;
		?>
	</div>
</div>

<?php
get_footer();

