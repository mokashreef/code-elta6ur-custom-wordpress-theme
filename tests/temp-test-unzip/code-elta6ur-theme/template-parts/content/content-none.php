<?php
/**
 * قالب في حال عدم وجود نتائج
 * Code Elta6ur No Results Template Part
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="cdt-no-results" style="text-align: center; padding-block: var(--space-12, 3rem);">
	<h2 style="font-size: var(--font-size-2xl, 1.5rem); margin-block-end: var(--space-4, 1rem);">
		<?php esc_html_e( 'لم يتم العثور على أي محتوى', 'code-elta6ur' ); ?>
	</h2>
	<p style="color: var(--brand-text-muted, #475569); max-width: 500px; margin-inline: auto; margin-block-end: var(--space-6, 1.5rem);">
		<?php esc_html_e( 'عذراً، لم نتمكن من العثور على ما تبحث عنه. يمكنك تجربة البحث بكلمات أخرى أو تصفح الأقسام الرئيسية.', 'code-elta6ur' ); ?>
	</p>
	<div style="max-width: 480px; margin-inline: auto;">
		<?php get_search_form(); ?>
	</div>
</div>
