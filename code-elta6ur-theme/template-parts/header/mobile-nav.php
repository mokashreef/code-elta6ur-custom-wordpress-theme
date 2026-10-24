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

		<!-- Mobile User Quick Widget -->
		<div style="padding: 12px 16px; margin: 12px 16px; background: var(--brand-surface, #F1F5F9); border-radius: var(--radius-sm, 10px);">
			<?php if ( is_user_logged_in() ) : 
				$m_uid = get_current_user_id();
				$m_user = wp_get_current_user();
				$m_pts = function_exists( 'cdt_get_user_points' ) ? cdt_get_user_points( $m_uid ) : 0;
				$m_streak = function_exists( 'cdt_get_user_streak' ) ? cdt_get_user_streak( $m_uid ) : array( 'current_streak' => 0 );
				?>
				<div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
					<div style="display: flex; align-items: center; gap: 10px;">
						<?php echo get_avatar( $m_uid, 36, '', '', array( 'style' => 'border-radius: 50%;' ) ); ?>
						<div>
							<div style="font-weight: 700; font-size: 0.95rem; color: var(--brand-dark, #0F172A);"><?php echo esc_html( $m_user->display_name ); ?></div>
							<div style="font-size: 0.8rem; color: var(--brand-primary, #0170B9); font-weight: 700;">💎 <?php echo esc_html( $m_pts ); ?> نقطة | 🔥 <?php echo esc_html( $m_streak['current_streak'] ); ?> يوم</div>
						</div>
					</div>
					<a href="<?php echo esc_url( function_exists( 'cdt_get_account_page_url' ) ? cdt_get_account_page_url( 'account' ) : home_url( '/my-account/' ) ); ?>" class="cdt-link-muted" style="font-size: 0.85rem;">
						<?php esc_html_e( 'حسابي ←', 'code-elta6ur' ); ?>
					</a>
				</div>
			<?php else : ?>
				<div style="display: flex; gap: 10px;">
					<a href="<?php echo esc_url( function_exists( 'cdt_get_account_page_url' ) ? cdt_get_account_page_url( 'login' ) : home_url( '/login/' ) ); ?>" class="cdt-btn cdt-btn-primary" style="flex: 1; padding: 8px 12px; font-size: 0.85rem; justify-content: center;">
						<?php esc_html_e( 'دخول', 'code-elta6ur' ); ?>
					</a>
					<a href="<?php echo esc_url( function_exists( 'cdt_get_account_page_url' ) ? cdt_get_account_page_url( 'register' ) : home_url( '/registration/' ) ); ?>" class="cdt-btn cdt-btn-primary" style="flex: 1; padding: 8px 12px; font-size: 0.85rem; background: var(--brand-dark, #0F172A); justify-content: center;">
						<?php esc_html_e( 'حساب جديد', 'code-elta6ur' ); ?>
					</a>
				</div>
			<?php endif; ?>
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
					<li><a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>"><?php esc_html_e( 'الأدوات التقنية', 'code-elta6ur' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/editor/' ) ); ?>"><?php esc_html_e( 'محرر الأكواد', 'code-elta6ur' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%85%d8%af%d9%88%d9%86%d8%a9/' ) ); ?>"><?php esc_html_e( 'المدونة', 'code-elta6ur' ); ?></a></li>
					<li><a href="<?php echo esc_url( function_exists( 'cdt_get_ai_section_url' ) ? cdt_get_ai_section_url() : home_url( '/category/%d8%a7%d9%84%d8%b0%d9%83%d8%a7%d8%a1-%d8%a7%d9%84%d8%a7%d8%b5%d8%b7%d9%86%d8%a7%d8%b9%d9%8a/' ) ); ?>"><?php esc_html_e( 'الذكاء الاصطناعي', 'code-elta6ur' ); ?></a></li>
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
