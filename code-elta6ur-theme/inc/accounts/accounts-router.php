<?php
/**
 * توجيه صفحات الحسابات والتحقق من صلاحيات الوصول
 * Code Elta6ur Accounts Router & Access Control
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * الحصول على الروابط المركزية لصفحات الحساب
 *
 * @param string $page_key نوع الصفحة: login, register, lost-password, account, logout.
 * @return string الرابط المباشر.
 */
function cdt_get_account_page_url( $page_key = 'account' ) {
	switch ( $page_key ) {
		case 'login':
			return home_url( '/login/' );
		case 'register':
		case 'registration':
			return home_url( '/registration/' );
		case 'lost-password':
		case 'lost_password':
			return home_url( '/lost-password/' );
		case 'logout':
			return wp_logout_url( home_url( '/login/' ) );
		case 'account':
		case 'my-account':
		default:
			return home_url( '/my-account/' );
	}
}

/**
 * تسجيل قوالب الصفحات الخاصة بالحسابات وتطبيقها تلقائياً عند طلب المسار
 *
 * @param string $template مسار القالب الافتراضي.
 * @return string مسار القالب المختار.
 */
function cdt_accounts_template_include( $template ) {
	if ( is_admin() || wp_doing_ajax() ) {
		return $template;
	}

	$theme_dir = get_template_directory();

	// 1. فحص مسار تسجيل الخروج المخصص
	if ( is_page( 'logout' ) || ( isset( $_GET['cdt_action'] ) && 'logout' === $_GET['cdt_action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		wp_logout();
		wp_safe_redirect( home_url( '/login/?logged_out=1' ) );
		exit;
	}

	// 2. فحص صفحة تسجيل الدخول (/login/)
	if ( is_page( 'login' ) || ( function_exists( 'get_query_var' ) && 'login' === get_query_var( 'pagename' ) ) ) {
		if ( is_user_logged_in() ) {
			wp_safe_redirect( cdt_get_account_page_url( 'account' ) );
			exit;
		}
		$custom_login = $theme_dir . '/template-login.php';
		if ( file_exists( $custom_login ) ) {
			return $custom_login;
		}
	}

	// 3. فحص صفحة التسجيل (/registration/)
	if ( is_page( 'registration' ) || is_page( 'register' ) || ( function_exists( 'get_query_var' ) && in_array( get_query_var( 'pagename' ), array( 'registration', 'register' ), true ) ) ) {
		if ( is_user_logged_in() ) {
			wp_safe_redirect( cdt_get_account_page_url( 'account' ) );
			exit;
		}
		$custom_reg = $theme_dir . '/template-registration.php';
		if ( file_exists( $custom_reg ) ) {
			return $custom_reg;
		}
	}

	// 4. فحص صفحة استعادة كلمة المرور (/lost-password/)
	if ( is_page( 'lost-password' ) || ( function_exists( 'get_query_var' ) && 'lost-password' === get_query_var( 'pagename' ) ) ) {
		$custom_lost = $theme_dir . '/template-lost-password.php';
		if ( file_exists( $custom_lost ) ) {
			return $custom_lost;
		}
	}

	// 5. فحص صفحة الحساب الشخصي (/my-account/)
	if ( is_page( 'my-account' ) || ( function_exists( 'get_query_var' ) && 'my-account' === get_query_var( 'pagename' ) ) ) {
		if ( ! is_user_logged_in() ) {
			$login_url = add_query_arg( 'redirect_to', rawurlencode( cdt_get_account_page_url( 'account' ) ), cdt_get_account_page_url( 'login' ) );
			wp_safe_redirect( $login_url );
			exit;
		}
		$custom_account = $theme_dir . '/template-account.php';
		if ( file_exists( $custom_account ) ) {
			return $custom_account;
		}
	}

	return $template;
}
add_filter( 'template_include', 'cdt_accounts_template_include', 99 );

/**
 * تعطيل تحكم Ultimate Member في نماذج القالب المخصصة لضمان تجربة مستخدم موحدة
 */
function cdt_disable_um_on_custom_account_pages() {
	if ( is_page( array( 'login', 'registration', 'register', 'lost-password', 'my-account' ) ) ) {
		// منع Ultimate Member من تحويل الصفحة أو استبدال المحتوى بالـ Shortcodes القديمة
		remove_filter( 'the_content', 'um_filter_content', 999 );
	}
}
add_action( 'template_redirect', 'cdt_disable_um_on_custom_account_pages', 5 );
