<?php
/**
 * Template Name: كود التطور: إنشاء حساب جديد
 * Template Post Type: page
 *
 * قالب مخصص لإنشاء حساب جديد بأسلوب تقني متكامل
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_user_logged_in() ) {
	wp_safe_redirect( cdt_get_account_page_url( 'account' ) );
	exit;
}

get_header();

$flash_msg = cdt_get_auth_message();
?>

<div class="cdt-auth-wrapper">
	<div class="cdt-auth-card">
		<div class="cdt-auth-header">
			<div class="cdt-auth-icon-badge">
				🚀
			</div>
			<h1 class="cdt-auth-title"><?php esc_html_e( 'إنشاء حساب جديد', 'code-elta6ur' ); ?></h1>
			<p class="cdt-auth-desc"><?php esc_html_e( 'انضم إلى منصة كود التطور وابدأ رحلتك البرمجية، واكسب النقاط والشارات مع كل درس.', 'code-elta6ur' ); ?></p>
		</div>

		<?php if ( $flash_msg ) : ?>
			<div class="cdt-alert cdt-alert-<?php echo esc_attr( $flash_msg['type'] ); ?>">
				<span><?php echo 'success' === $flash_msg['type'] ? '✓' : '⚠️'; ?></span>
				<span><?php echo esc_html( $flash_msg['message'] ); ?></span>
			</div>
		<?php endif; ?>

		<form method="post" action="" class="cdt-auth-form" novalidate>
			<?php wp_nonce_field( 'cdt_register_action', 'cdt_register_nonce' ); ?>

			<div class="cdt-form-group">
				<label for="cdt_reg_display_name" class="cdt-form-label"><?php esc_html_e( 'الاسم الظاهر / الاسم الكامل', 'code-elta6ur' ); ?></label>
				<div class="cdt-input-wrapper">
					<span class="cdt-input-icon"><?php echo cdt_svg_icon( 'user' ); ?></span>
					<input type="text" name="display_name" id="cdt_reg_display_name" class="cdt-form-control" required placeholder="<?php esc_attr_e( 'مثال: محمد أحمد', 'code-elta6ur' ); ?>">
				</div>
			</div>

			<div class="cdt-form-group">
				<label for="cdt_reg_username" class="cdt-form-label"><?php esc_html_e( 'اسم المستخدم (بالأحرف الإنجليزية)', 'code-elta6ur' ); ?></label>
				<div class="cdt-input-wrapper">
					<span class="cdt-input-icon">@</span>
					<input type="text" name="user_login" id="cdt_reg_username" class="cdt-form-control" autocomplete="username" required placeholder="mohammad_dev">
				</div>
			</div>

			<div class="cdt-form-group">
				<label for="cdt_reg_email" class="cdt-form-label"><?php esc_html_e( 'البريد الإلكتروني', 'code-elta6ur' ); ?></label>
				<div class="cdt-input-wrapper">
					<span class="cdt-input-icon"><?php echo cdt_svg_icon( 'email' ); ?></span>
					<input type="email" name="user_email" id="cdt_reg_email" class="cdt-form-control" autocomplete="email" required placeholder="name@example.com">
				</div>
			</div>

			<div class="cdt-form-group">
				<label for="cdt_reg_pass" class="cdt-form-label"><?php esc_html_e( 'كلمة المرور (8 خانات على الأقل)', 'code-elta6ur' ); ?></label>
				<div class="cdt-input-wrapper">
					<span class="cdt-input-icon">🔒</span>
					<input type="password" name="user_pass" id="cdt_reg_pass" class="cdt-form-control" autocomplete="new-password" required placeholder="••••••••">
					<button type="button" class="cdt-password-toggle" aria-label="<?php esc_attr_e( 'إظهار كلمة المرور', 'code-elta6ur' ); ?>">
						👁️
					</button>
				</div>
			</div>

			<div class="cdt-form-group">
				<label for="cdt_reg_pass_confirm" class="cdt-form-label"><?php esc_html_e( 'تأكيد كلمة المرور', 'code-elta6ur' ); ?></label>
				<div class="cdt-input-wrapper">
					<span class="cdt-input-icon">🔒</span>
					<input type="password" name="user_pass_confirm" id="cdt_reg_pass_confirm" class="cdt-form-control" autocomplete="new-password" required placeholder="••••••••">
				</div>
			</div>

			<button type="submit" name="cdt_register_submit" class="cdt-btn-primary" style="margin-block-start: var(--space-4, 1rem);">
				<span><?php esc_html_e( 'إنشاء الحساب والبدء الآن', 'code-elta6ur' ); ?></span>
				<?php echo cdt_svg_icon( 'arrow-left' ); ?>
			</button>
		</form>

		<div class="cdt-auth-footer">
			<span><?php esc_html_e( 'لديك حساب بالفعل؟', 'code-elta6ur' ); ?></span>
			<a href="<?php echo esc_url( cdt_get_account_page_url( 'login' ) ); ?>" class="cdt-link-muted" style="margin-inline-start: 6px;">
				<?php esc_html_e( 'سجّل دخولك هنا', 'code-elta6ur' ); ?>
			</a>
		</div>
	</div>
</div>

<?php
get_footer();
