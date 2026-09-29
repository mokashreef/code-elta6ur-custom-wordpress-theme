<?php
/**
 * تذييل الموقع الموحد
 * Code Elta6ur Theme Footer
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	</main><!-- #main-content -->

	<footer class="cdt-site-footer" role="contentinfo">
		<?php get_template_part( 'template-parts/footer/footer-widgets' ); ?>
		<?php get_template_part( 'template-parts/footer/footer-bottom' ); ?>
	</footer>
</div><!-- .cdt-site-wrapper -->

<!-- Back To Top Button -->
<button class="cdt-back-to-top" aria-label="<?php esc_attr_e( 'العودة لأعلى الصفحة', 'code-elta6ur' ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>
</button>

<!-- Video Modal Container for YouTube Lightbox -->
<div class="cdt-video-modal" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'مشغل الفيديو', 'code-elta6ur' ); ?>">
	<div class="cdt-video-modal-inner">
		<button class="cdt-video-modal-close" aria-label="<?php esc_attr_e( 'إغلاق مشغل الفيديو', 'code-elta6ur' ); ?>">
			<?php echo cdt_svg_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>
		<iframe src="" title="<?php esc_attr_e( 'فيديو كود التطور', 'code-elta6ur' ); ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
