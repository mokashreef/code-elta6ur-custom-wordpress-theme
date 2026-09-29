<?php
/**
 * نموذج البحث الموحد
 * Code Elta6ur Search Form
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<form role="search" method="get" class="cdt-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; gap: var(--space-2, 0.5rem); width: 100%;">
	<div style="position: relative; flex: 1;">
		<input type="search" class="cdt-search-field" placeholder="<?php echo esc_attr_x( 'ابحث عن مقال، كورس، أو مفهوم برمجي...', 'placeholder', 'code-elta6ur' ); ?>" value="<?php echo get_search_query(); ?>" name="s" required style="width: 100%; padding: var(--space-3, 0.75rem) var(--space-4, 1rem); border: 1px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-sm, 10px); font-family: inherit; font-size: var(--font-size-sm, 0.875rem);">
	</div>
	<button type="submit" class="cdt-btn cdt-btn-primary" aria-label="<?php esc_attr_e( 'بحث', 'code-elta6ur' ); ?>">
		<?php echo cdt_svg_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span><?php esc_html_e( 'بحث', 'code-elta6ur' ); ?></span>
	</button>
</form>
