<?php
/**
 * تذييل الموقع - أعمدة المعلومات والروابط
 * Code Elta6ur Footer Widgets & Columns
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="cdt-container">
	<div class="cdt-footer-grid">
		<!-- Column 1: About Platform -->
		<div class="cdt-footer-col">
			<div style="margin-block-end: var(--space-4, 1rem);">
				<?php cdt_the_logo(); ?>
			</div>
			<p>
				<?php echo esc_html( cdt_get_option( 'cdt_footer_about' ) ); ?>
			</p>
			<div class="cdt-footer-social">
				<?php
				$socials = array(
					'youtube'   => cdt_get_option( 'cdt_youtube_url' ),
					'telegram'  => cdt_get_option( 'cdt_telegram' ),
					'whatsapp'  => cdt_get_option( 'cdt_whatsapp' ),
					'instagram' => cdt_get_option( 'cdt_instagram' ),
					'facebook'  => cdt_get_option( 'cdt_facebook' ),
					'github'    => cdt_get_option( 'cdt_github' ),
				);

				foreach ( $socials as $icon => $url ) :
					if ( ! empty( $url ) ) :
						?>
						<a href="<?php echo esc_url( $url ); ?>" class="cdt-social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $icon ); ?>">
							<?php echo cdt_svg_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
						<?php
					endif;
				endforeach;
				?>
			</div>
		</div>

		<!-- Column 2: Navigation Links -->
		<div class="cdt-footer-col">
			<h4><?php esc_html_e( 'روابط سريعة', 'code-elta6ur' ); ?></h4>
			<ul class="cdt-footer-links">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'code-elta6ur' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>"><?php esc_html_e( 'الأدوات التقنية (30 أداة)', 'code-elta6ur' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/editor/' ) ); ?>"><?php esc_html_e( 'محرر الأكواد أونلاين', 'code-elta6ur' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/' ) ); ?>"><?php esc_html_e( 'الكورسات التعليمية', 'code-elta6ur' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%85%d8%af%d9%88%d9%86%d8%a9/' ) ); ?>"><?php esc_html_e( 'المدونة البرمجية', 'code-elta6ur' ); ?></a></li>
				<li><a href="<?php echo esc_url( function_exists( 'cdt_get_ai_section_url' ) ? cdt_get_ai_section_url() : home_url( '/category/%d8%a7%d9%84%d8%b0%d9%83%d8%a7%d8%a1-%d8%a7%d9%84%d8%a7%d8%b5%d8%b7%d9%86%d8%a7%d8%b9%d9%8a/' ) ); ?>"><?php esc_html_e( 'قسم الذكاء الاصطناعي', 'code-elta6ur' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/series/' ) ); ?>"><?php esc_html_e( 'السلاسل التعليمية', 'code-elta6ur' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/%d9%85%d9%86-%d9%86%d8%ad%d9%86/' ) ); ?>"><?php esc_html_e( 'عن كود التطور', 'code-elta6ur' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/%d8%a7%d8%aa%d8%b5%d9%84-%d8%a8%d9%86%d8%a7/' ) ); ?>"><?php esc_html_e( 'اتصل بنا', 'code-elta6ur' ); ?></a></li>
			</ul>
		</div>

		<!-- Column 3: Featured Courses -->
		<div class="cdt-footer-col">
			<h4><?php esc_html_e( 'الكورسات الأساسية', 'code-elta6ur' ); ?></h4>
			<ul class="cdt-footer-links">
				<li><a href="<?php echo esc_url( home_url( '/%d9%83%d9%88%d8%b1%d8%b3-html/' ) ); ?>"><?php esc_html_e( 'كورس HTML الشامل', 'code-elta6ur' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/%d9%83%d9%88%d8%b1%d8%b3-css/' ) ); ?>"><?php esc_html_e( 'كورس CSS المتقدم', 'code-elta6ur' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/%d9%83%d9%88%d8%b1%d8%b3-javascript/' ) ); ?>"><?php esc_html_e( 'كورس JavaScript الحديثة', 'code-elta6ur' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/%d9%83%d9%88%d8%b1%d8%b3-react/' ) ); ?>"><?php esc_html_e( 'كورس React للمحترفين', 'code-elta6ur' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/%d9%83%d9%88%d8%b1%d8%b3-bootstrap/' ) ); ?>"><?php esc_html_e( 'كورس Bootstrap 5', 'code-elta6ur' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/%d9%83%d9%88%d8%b1%d8%b3-c/' ) ); ?>"><?php esc_html_e( 'كورس C++ للمبتدئين', 'code-elta6ur' ); ?></a></li>
			</ul>
		</div>

		<!-- Column 4: Contact & Community -->
		<div class="cdt-footer-col">
			<h4><?php esc_html_e( 'التواصل والمجتمع', 'code-elta6ur' ); ?></h4>
			<ul class="cdt-footer-links">
				<?php if ( cdt_get_option( 'cdt_email' ) ) : ?>
					<li>
						<a href="mailto:<?php echo esc_attr( cdt_get_option( 'cdt_email' ) ); ?>">
							<?php echo esc_html( cdt_get_option( 'cdt_email' ) ); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if ( cdt_get_option( 'cdt_whatsapp' ) ) : ?>
					<li>
						<a href="<?php echo esc_url( cdt_get_option( 'cdt_whatsapp' ) ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'محادثة واتساب المباشرة', 'code-elta6ur' ); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if ( cdt_get_option( 'cdt_telegram' ) ) : ?>
					<li>
						<a href="<?php echo esc_url( cdt_get_option( 'cdt_telegram' ) ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'قناة التيليجرام الرسمية', 'code-elta6ur' ); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if ( cdt_get_option( 'cdt_youtube_url' ) ) : ?>
					<li>
						<a href="<?php echo esc_url( cdt_get_option( 'cdt_youtube_url' ) ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'قناة اليوتيوب التعليمية', 'code-elta6ur' ); ?>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
