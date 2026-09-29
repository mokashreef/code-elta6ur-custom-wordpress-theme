<?php
/**
 * القائمة الجانبية للهاتف المحمول
 * Code Elta6ur Mobile Navigation Drawer
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="cdt-mobile-overlay" aria-hidden="true"></div>

<aside class="cdt-mobile-drawer" id="cdt-mobile-drawer" aria-label="<?php esc_attr_e( 'قائمة الهاتف', 'code-elta6ur' ); ?>">
	<div>
		<div class="cdt-mobile-header">
			<?php cdt_the_logo(); ?>
			<button class="cdt-mobile-close" aria-label="<?php esc_attr_e( 'إغلاق القائمة', 'code-elta6ur' ); ?>">
				<?php echo cdt_svg_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		</div>

		<nav class="cdt-mobile-navigation">
			<?php
			if ( has_nav_menu( 'primary-menu' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary-menu',
						'menu_class'     => 'cdt-mobile-menu',
						'container'      => false,
						'fallback_cb'    => false,
					)
				);
			} else {
				?>
				<ul class="cdt-mobile-menu">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'code-elta6ur' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/' ) ); ?>"><?php esc_html_e( 'الكورسات التعليمية', 'code-elta6ur' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%85%d8%af%d9%88%d9%86%d8%a9/' ) ); ?>"><?php esc_html_e( 'المدونة', 'code-elta6ur' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/series/' ) ); ?>"><?php esc_html_e( 'السلاسل', 'code-elta6ur' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/%d9%85%d9%86-%d9%86%d8%ad%d9%86/' ) ); ?>"><?php esc_html_e( 'من نحن', 'code-elta6ur' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/%d8%a7%d8%aa%d8%b5%d9%84-%d8%a8%d9%86%d8%a7/' ) ); ?>"><?php esc_html_e( 'اتصل بنا', 'code-elta6ur' ); ?></a></li>
				</ul>
				<?php
			}
			?>
		</nav>
	</div>

	<div class="cdt-mobile-social">
		<?php
		$social_channels = array(
			'youtube'   => cdt_get_option( 'cdt_youtube_url' ),
			'telegram'  => cdt_get_option( 'cdt_telegram' ),
			'whatsapp'  => cdt_get_option( 'cdt_whatsapp' ),
			'instagram' => cdt_get_option( 'cdt_instagram' ),
			'facebook'  => cdt_get_option( 'cdt_facebook' ),
			'github'    => cdt_get_option( 'cdt_github' ),
		);

		foreach ( $social_channels as $key => $url ) :
			if ( ! empty( $url ) ) :
				?>
				<a href="<?php echo esc_url( $url ); ?>" class="cdt-social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $key ); ?>">
					<?php echo cdt_svg_icon( $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
				<?php
			endif;
		endforeach;
		?>
	</div>
</aside>
