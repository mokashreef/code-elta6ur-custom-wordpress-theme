<?php
/**
 * Template Name: كود التطور: استعادة كلمة المرور
 * Template Post Type: page
 *
 * قالب مخصص لطلب وإعادة تعيين كلمة المرور بأمان عالٍ
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$flash_msg  = cdt_get_auth_message();
$action     = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : 'lostpassword';
$rp_key     = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
$rp_login   = isset( $_GET['login'] ) ? sanitize_text_field( wp_unslash( $_GET['login'] ) ) : '';
$is_resetting = ( 'rp' === $action || 'resetpass' === $action ) && ! empty( $rp_key ) && ! empty( $rp_login );
?>

<div class="cdt-auth-wrapper">
	<div class="cdt-auth-card">
		<div class="cdt-auth-header">
			<div class="cdt-auth-icon-badge">
				🔑
			</div>
			<?php if ( $is_resetting ) : ?>
				<h1 class="cdt-auth-title"><?php esc_html_e( 'تعيين كلمة المرور الجديدة', 'code-elta6ur' ); ?></h1>
				<p class="cdt-auth-desc"><?php esc_html_e( 'أدخل كلمة المرور الجديدة لحسابك وقم بتأكيدها.', 'code-elta6ur' ); ?></p>
			<?php else : ?>
				<h1 class="cdt-auth-title"><?php esc_html_e( 'استعادة كلمة المرور', 'code-elta6ur' ); ?></h1>
				<p class="cdt-auth-desc"><?php esc_html_e( 'أدخل اسم المستخدم أو البريد الإلكتروني المسجل، وسنرسل لك رابطاً آمناً لتعيين كلمة مرور جديدة.', 'code-elta6ur' ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $flash_msg ) : ?>
			<div class="cdt-alert cdt-alert-<?php echo esc_attr( $flash_msg['type'] ); ?>">
				<span><?php echo 'success' === $flash_msg['type'] ? '✓' : '⚠️'; ?></span>
				<span><?php echo esc_html( $flash_msg['message'] ); ?></span>
			</div>
		<?php endif; ?>

		<?php if ( $is_resetting ) : ?>
			<!-- نموذج تعيين كلمة المرور الجديدة -->
			<form method="post" action="" class="cdt-auth-form" novalidate>
				<?php wp_nonce_field( 'cdt_reset_password_action', 'cdt_reset_password_nonce' ); ?>
				<input type="hidden" name="rp_key" value="<?php echo esc_attr( $rp_key ); ?>">
				<input type="hidden" name="rp_login" value="<?php echo esc_attr( $rp_login ); ?>">

				<div class="cdt-form-group">
					<label for="cdt_new_pass1" class="cdt-form-label"><?php esc_html_e( 'كلمة المرور الجديدة (8 خانات على الأقل)', 'code-elta6ur' ); ?></label>
					<div class="cdt-input-wrapper">
						<span class="cdt-input-icon">🔒</span>
						<input type="password" name="pass1" id="cdt_new_pass1" class="cdt-form-control" autocomplete="new-password" required placeholder="••••••••">
						<button type="button" class="cdt-password-toggle" aria-label="<?php esc_attr_e( 'إظهار كلمة المرور', 'code-elta6ur' ); ?>">
							👁️
						</button>
					</div>
				</div>

				<div class="cdt-form-group">
					<label for="cdt_new_pass2" class="cdt-form-label"><?php esc_html_e( 'تأكيد كلمة المرور الجديدة', 'code-elta6ur' ); ?></label>
					<div class="cdt-input-wrapper">
						<span class="cdt-input-icon">🔒</span>
						<input type="password" name="pass2" id="cdt_new_pass2" class="cdt-form-control" autocomplete="new-password" required placeholder="••••••••">
					</div>
				</div>

				<button type="submit" name="cdt_reset_password_submit" class="cdt-btn-primary" style="margin-block-start: var(--space-4, 1rem);">
					<span><?php esc_html_e( 'حفظ كلمة المرور وتسجيل الدخول', 'code-elta6ur' ); ?></span>
					<?php echo cdt_svg_icon( 'arrow-left' ); ?>
				</button>
			</form>
		<?php else : ?>
			<!-- نموذج طلب رابط الاستعادة -->
			<form method="post" action="" class="cdt-auth-form" novalidate>
				<?php wp_nonce_field( 'cdt_lost_password_action', 'cdt_lost_password_nonce' ); ?>

				<div class="cdt-form-group">
					<label for="cdt_lost_user_login" class="cdt-form-label"><?php esc_html_e( 'اسم المستخدم أو البريد الإلكتروني', 'code-elta6ur' ); ?></label>
					<div class="cdt-input-wrapper">
						<span class="cdt-input-icon"><?php echo cdt_svg_icon( 'email' ); ?></span>
						<input type="text" name="user_login" id="cdt_lost_user_login" class="cdt-form-control" required autofocus placeholder="name@example.com">
					</div>
				</div>

				<button type="submit" name="cdt_lost_password_submit" class="cdt-btn-primary" style="margin-block-start: var(--space-4, 1rem);">
					<span><?php esc_html_e( 'إرسال رابط الاستعادة الآمن', 'code-elta6ur' ); ?></span>
					<?php echo cdt_svg_icon( 'arrow-left' ); ?>
				</button>
			</form>
		<?php endif; ?>

		<div class="cdt-auth-footer">
			<a href="<?php echo esc_url( cdt_get_account_page_url( 'login' ) ); ?>" class="cdt-link-muted">
				← <?php esc_html_e( 'العودة لصفحة تسجيل الدخول', 'code-elta6ur' ); ?>
			</a>
		</div>
	</div>
</div>

<?php
get_footer();
