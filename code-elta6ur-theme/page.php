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

$is_elementor = cdt_is_elementor_page();
?>

<?php if ( ! $is_elementor ) : ?>
	<?php get_template_part( 'template-parts/hero/hero-page' ); ?>
	<?php cdt_breadcrumbs(); ?>
<?php endif; ?>

<div class="<?php echo $is_elementor ? 'cdt-elementor-page-container' : 'cdt-section'; ?>">
	<div class="<?php echo $is_elementor ? 'cdt-elementor-full-container' : 'cdt-container-narrow'; ?>">
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
