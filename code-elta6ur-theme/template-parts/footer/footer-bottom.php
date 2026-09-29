<?php
/**
 * شريط الحقوق أسفل الموقع
 * Code Elta6ur Footer Bottom Bar
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="cdt-footer-bottom">
	<div class="cdt-container">
		<div class="cdt-footer-bottom-inner">
			<div class="cdt-copyright">
				<?php echo esc_html( cdt_get_option( 'cdt_copyright_text' ) ); ?>
			</div>

			<ul class="cdt-footer-policies">
				<li>
					<a href="<?php echo esc_url( home_url( '/%d8%b3%d9%8a%d8%a7%d8%b3%d8%a9-%d8%a7%d9%84%d8%ae%d8%b5%d9%88%d8%b5%d9%8a%d8%a9/' ) ); ?>">
						<?php esc_html_e( 'سياسة الخصوصية', 'code-elta6ur' ); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo esc_url( home_url( '/%d8%b4%d8%1%d9%88%d8%b7-%d8%a7%d9%84%d8%a7%d8%b3%d8%aa%d8%ae%d8%af%d8%a7%d9%85/' ) ); ?>">
						<?php esc_html_e( 'شروط الاستخدام', 'code-elta6ur' ); ?>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
