<?php
/**
 * القائمة الرئيسية للموقع
 * Code Elta6ur Primary Navigation Menu
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<nav class="cdt-primary-nav" aria-label="<?php esc_attr_e( 'القائمة الرئيسية', 'code-elta6ur' ); ?>">
	<?php
	if ( has_nav_menu( 'primary-menu' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary-menu',
				'menu_class'     => 'cdt-nav-menu',
				'container'      => false,
				'fallback_cb'    => false,
				'depth'          => 2,
			)
		);
	} else {
		// Default fallback menu based on Code Elta6ur actual pages
		?>
		<ul class="cdt-nav-menu">
			<li class="<?php echo is_front_page() ? 'current-menu-item' : ''; ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'code-elta6ur' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/' ) ); ?>"><?php esc_html_e( 'الكورسات التعليمية', 'code-elta6ur' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%85%d8%af%d9%88%d9%86%d8%a9/' ) ); ?>"><?php esc_html_e( 'المدونة', 'code-elta6ur' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_url( home_url( '/series/' ) ); ?>"><?php esc_html_e( 'السلاسل', 'code-elta6ur' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_url( home_url( '/%d9%85%d9%86-%d9%86%d8%ad%d9%86/' ) ); ?>"><?php esc_html_e( 'من نحن', 'code-elta6ur' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_url( home_url( '/%d8%a7%d8%aa%d8%b5%d9%84-%d8%a8%d9%86%d8%a7/' ) ); ?>"><?php esc_html_e( 'اتصل بنا', 'code-elta6ur' ); ?></a>
			</li>
		</ul>
		<?php
	}
	?>
</nav>
