<?php
/**
 * بطاقة مميزات وأهداف المنصة
 * Code Elta6ur Feature & Goal Card
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title = isset( $args['title'] ) ? $args['title'] : '';
$desc  = isset( $args['desc'] ) ? $args['desc'] : '';
$icon  = isset( $args['icon'] ) ? $args['icon'] : 'check';
?>

<div class="cdt-feature-card">
	<div class="cdt-feature-icon" aria-hidden="true">
		<?php echo cdt_svg_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<h3 class="cdt-feature-title"><?php echo esc_html( $title ); ?></h3>
	<p class="cdt-feature-desc"><?php echo esc_html( $desc ); ?></p>
</div>
