<?php
/**
 * Template Name: كود التطور: تسجيل الدخول
 * Template Post Type: page
 *
 * قالب مخصص لتسجيل الدخول بأسلوب تقني أنيق وآمن
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

$flash_msg   = cdt_get_auth_message();
$redirect_to = isset( $_REQUEST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) : cdt_get_account_page_url( 'account' );
?>

<div class="cdt-auth-wrapper">
	<div class="cdt-auth-card">
		<div class="cdt-auth-header">
			<div class="cdt-auth-icon-badge">
				<?php echo cdt_svg_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<h1 class="cdt-auth-title"><?php esc_html_e( 'تسجيل الدخول', 'code-elta6ur' ); ?></h1>
			<p class="cdt-auth-desc"><?php esc_html_e( 'مرحباً بك مجدداً في كود التطور! سجّل دخولك لمتابعة تقدمك ونقاطك.', 'code-elta6ur' ); ?></p>
		</div>

		<?php if ( isset( $_GET['logged_out'] ) ) : ?>
			<div class="cdt-alert cdt-alert-success">
				<span>✓</span>
				<span><?php esc_html_e( 'تم تسجيل خروجك بنجاح. نتمنى رؤيتك قريباً!', 'code-elta6ur' ); ?></span>
			</div>
		<?php endif; ?>

		<?php if ( $flash_msg ) : ?>
			<div class="cdt-alert cdt-alert-<?php echo esc_attr( $flash_msg['type'] ); ?>">
				<span><?php echo 'success' === $flash_msg['type'] ? '✓' : '⚠️'; ?></span>
				<span><?php echo esc_html( $flash_msg['message'] ); ?></span>
			</div>
		<?php endif; ?>

		<form method="post" action="" class="cdt-auth-form" novalidate>
			<?php wp_nonce_field( 'cdt_login_action', 'cdt_login_nonce' ); ?>
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>">

			<div class="cdt-form-group">
				<label for="cdt_user_login" class="cdt-form-label"><?php esc_html_e( 'اسم المستخدم أو البريد الإلكتروني', 'code-elta6ur' ); ?></label>
				<div class="cdt-input-wrapper">
					<span class="cdt-input-icon"><?php echo cdt_svg_icon( 'user' ); ?></span>
					<input type="text" name="log" id="cdt_user_login" class="cdt-form-control" autocomplete="username" required autofocus placeholder="name@example.com">
				</div>
			</div>

			<div class="cdt-form-group">
				<label for="cdt_user_pass" class="cdt-form-label"><?php esc_html_e( 'كلمة المرور', 'code-elta6ur' ); ?></label>
				<div class="cdt-input-wrapper">
					<span class="cdt-input-icon">🔒</span>
					<input type="password" name="pwd" id="cdt_user_pass" class="cdt-form-control" autocomplete="current-password" required placeholder="••••••••">
					<button type="button" class="cdt-password-toggle" aria-label="<?php esc_attr_e( 'إظهار كلمة المرور', 'code-elta6ur' ); ?>">
						👁️
					</button>
				</div>
			</div>

			<div class="cdt-form-actions-inline">
				<label class="cdt-remember-me">
					<input type="checkbox" name="rememberme" value="forever" checked>
					<span><?php esc_html_e( 'تذكرني على هذا الجهاز', 'code-elta6ur' ); ?></span>
				</label>
				<a href="<?php echo esc_url( cdt_get_account_page_url( 'lost-password' ) ); ?>" class="cdt-link-muted">
					<?php esc_html_e( 'نسيت كلمة المرور؟', 'code-elta6ur' ); ?>
				</a>
			</div>

			<button type="submit" name="cdt_login_submit" class="cdt-btn-primary">
				<span><?php esc_html_e( 'تسجيل الدخول الآن', 'code-elta6ur' ); ?></span>
				<?php echo cdt_svg_icon( 'arrow-left' ); ?>
			</button>
		</form>

		<div class="cdt-auth-footer">
			<span><?php esc_html_e( 'ليس لديك حساب بعد؟', 'code-elta6ur' ); ?></span>
			<a href="<?php echo esc_url( cdt_get_account_page_url( 'register' ) ); ?>" class="cdt-link-muted" style="margin-inline-start: 6px;">
				<?php esc_html_e( 'أنشئ حساباً مجانياً الآن', 'code-elta6ur' ); ?>
			</a>
		</div>
	</div>
</div>

<?php
get_footer();
