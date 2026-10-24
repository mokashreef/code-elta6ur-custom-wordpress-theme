<?php
/**
 * معالجة طلبات المصادقة ونماذج الحسابات
 * Code Elta6ur Authentication Handlers & Security
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تخزين رسالة مؤقتة للمستخدم (خطأ أو نجاح)
 *
 * @param string $type error أو success.
 * @param string $message نص الرسالة.
 */
function cdt_set_auth_message( $type, $message ) {
	$key = 'cdt_auth_msg_' . md5( ( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '' ) . ( isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '' ) );
	set_transient( $key, array( 'type' => $type, 'message' => $message ), 60 );
}

/**
 * استرجاع الرسالة المؤقتة وحذفها فوراً بعد القراءة (Flash Message)
 *
 * @return array|null
 */
function cdt_get_auth_message() {
	$key = 'cdt_auth_msg_' . md5( ( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '' ) . ( isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '' ) );
	$msg = get_transient( $key );
	if ( $msg ) {
		delete_transient( $key );
		return $msg;
	}
	return null;
}

/**
 * 1. معالجة نموذج تسجيل الدخول
 */
function cdt_handle_login_action() {
	if ( ! isset( $_POST['cdt_login_submit'] ) ) {
		return;
	}

	if ( ! isset( $_POST['cdt_login_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['cdt_login_nonce'] ), 'cdt_login_action' ) ) {
		cdt_set_auth_message( 'error', __( 'انتهت صلاحية جلسة الأمان، يرجى إعادة المحاولة.', 'code-elta6ur' ) );
		return;
	}

	$username    = isset( $_POST['log'] ) ? sanitize_text_field( wp_unslash( $_POST['log'] ) ) : '';
	$password    = isset( $_POST['pwd'] ) ? (string) $_POST['pwd'] : '';
	$remember    = ! empty( $_POST['rememberme'] );
	$redirect_to = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : cdt_get_account_page_url( 'account' );

	if ( empty( $username ) || empty( $password ) ) {
		cdt_set_auth_message( 'error', __( 'يرجى إدخال اسم المستخدم أو البريد الإلكتروني وكلمة المرور.', 'code-elta6ur' ) );
		return;
	}

	// Rate Limiting على محاولات الدخول الفاشلة لحماية الحسابات من Brute Force
	$ip          = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '127.0.0.1';
	$attempt_key = 'cdt_login_attempts_' . md5( $ip );
	$attempts    = (int) get_transient( $attempt_key );

	if ( $attempts >= 7 ) {
		cdt_set_auth_message( 'error', __( 'تم تجاوز الحد الأقصى لمحاولات تسجيل الدخول. يرجى الانتظار 15 دقيقة أو استعادة كلمة المرور.', 'code-elta6ur' ) );
		return;
	}

	$credentials = array(
		'user_login'    => $username,
		'user_password' => $password,
		'remember'      => $remember,
	);

	$user = wp_signon( $credentials, is_ssl() );

	if ( is_wp_error( $user ) ) {
		set_transient( $attempt_key, $attempts + 1, 15 * MINUTE_IN_SECONDS );
		cdt_set_auth_message( 'error', __( 'بيانات تسجيل الدخول غير صحيحة، يرجى التأكد من اسم المستخدم أو البريد وكلمة المرور.', 'code-elta6ur' ) );
		return;
	}

	// تصفير عداد المحاولات عند النجاح
	delete_transient( $attempt_key );

	// تأكيد توجيه آمن يمنع هجمات Open Redirect
	$safe_redirect = wp_validate_redirect( $redirect_to, cdt_get_account_page_url( 'account' ) );
	wp_safe_redirect( $safe_redirect );
	exit;
}
add_action( 'template_redirect', 'cdt_handle_login_action', 10 );

/**
 * 2. معالجة نموذج إنشاء حساب جديد
 */
function cdt_handle_registration_action() {
	if ( ! isset( $_POST['cdt_register_submit'] ) ) {
		return;
	}

	if ( ! isset( $_POST['cdt_register_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['cdt_register_nonce'] ), 'cdt_register_action' ) ) {
		cdt_set_auth_message( 'error', __( 'انتهت صلاحية جلسة الأمان، يرجى إعادة المحاولة.', 'code-elta6ur' ) );
		return;
	}

	if ( ! get_option( 'users_can_register' ) ) {
		cdt_set_auth_message( 'error', __( 'إنشاء الحسابات الجديدة غير متاح حالياً.', 'code-elta6ur' ) );
		return;
	}

	$username    = isset( $_POST['user_login'] ) ? sanitize_user( wp_unslash( $_POST['user_login'] ), true ) : '';
	$email       = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';
	$password    = isset( $_POST['user_pass'] ) ? (string) $_POST['user_pass'] : '';
	$confirm_pw  = isset( $_POST['user_pass_confirm'] ) ? (string) $_POST['user_pass_confirm'] : '';
	$display_name= isset( $_POST['display_name'] ) ? sanitize_text_field( wp_unslash( $_POST['display_name'] ) ) : '';

	// التحقق من صحة المدخلات
	if ( empty( $username ) || empty( $email ) || empty( $password ) ) {
		cdt_set_auth_message( 'error', __( 'يرجى ملء جميع الحقول المطلوبة.', 'code-elta6ur' ) );
		return;
	}

	if ( strlen( $username ) < 3 ) {
		cdt_set_auth_message( 'error', __( 'اسم المستخدم يجب ألا يقل عن 3 أحرف.', 'code-elta6ur' ) );
		return;
	}

	if ( ! is_email( $email ) ) {
		cdt_set_auth_message( 'error', __( 'البريد الإلكتروني المدخل غير صالح.', 'code-elta6ur' ) );
		return;
	}

	if ( strlen( $password ) < 8 ) {
		cdt_set_auth_message( 'error', __( 'كلمة المرور يجب أن تتكون من 8 خانات على الأقل.', 'code-elta6ur' ) );
		return;
	}

	if ( $password !== $confirm_pw ) {
		cdt_set_auth_message( 'error', __( 'كلمتا المرور غير متطابقتين، يرجى التأكد منهما.', 'code-elta6ur' ) );
		return;
	}

	if ( username_exists( $username ) ) {
		cdt_set_auth_message( 'error', __( 'اسم المستخدم هذا مستخدم مسبقاً، يرجى اختيار اسم آخر.', 'code-elta6ur' ) );
		return;
	}

	if ( email_exists( $email ) ) {
		cdt_set_auth_message( 'error', __( 'البريد الإلكتروني هذا مسجل لدينا بالفعل، يمكنك تسجيل الدخول أو استعادة كلمة المرور.', 'code-elta6ur' ) );
		return;
	}

	// إنشاء المستخدم في ووردبريس بأمان
	$user_id = wp_create_user( $username, $password, $email );

	if ( is_wp_error( $user_id ) ) {
		cdt_set_auth_message( 'error', $user_id->get_error_message() );
		return;
	}

	if ( ! empty( $display_name ) ) {
		wp_update_user(
			array(
				'ID'           => $user_id,
				'display_name' => $display_name,
				'nickname'     => $display_name,
			)
		);
	}

	// تسجيل الدخول التلقائي للمستخدم الجديد
	wp_set_current_user( $user_id );
	wp_set_auth_cookie( $user_id, true );
	cdt_process_daily_activity( $user_id, 'login' );

	cdt_set_auth_message( 'success', __( 'أهلاً بك في منصة كود التطور! تم إنشاء حسابك وتفعيله بنجاح.', 'code-elta6ur' ) );
	wp_safe_redirect( cdt_get_account_page_url( 'account' ) );
	exit;
}
add_action( 'template_redirect', 'cdt_handle_registration_action', 10 );

/**
 * 3. معالجة طلب استعادة كلمة المرور
 */
function cdt_handle_lost_password_action() {
	if ( ! isset( $_POST['cdt_lost_password_submit'] ) ) {
		return;
	}

	if ( ! isset( $_POST['cdt_lost_password_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['cdt_lost_password_nonce'] ), 'cdt_lost_password_action' ) ) {
		cdt_set_auth_message( 'error', __( 'انتهت صلاحية جلسة الأمان، يرجى إعادة المحاولة.', 'code-elta6ur' ) );
		return;
	}

	$user_input = isset( $_POST['user_login'] ) ? sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) : '';

	// قاعدة أمنية إلزامية: لا نكشف وجود الحساب من عدمه أبداً
	$safe_generic_message = __( 'إذا كان اسم المستخدم أو البريد الإلكتروني مسجلاً لدينا، فستصلك رسالة تحتوي على رابط استعادة كلمة المرور في غضون دقائق.', 'code-elta6ur' );

	if ( empty( $user_input ) ) {
		cdt_set_auth_message( 'error', __( 'يرجى إدخال البريد الإلكتروني أو اسم المستخدم.', 'code-elta6ur' ) );
		return;
	}

	$user_data = false;
	if ( is_email( $user_input ) ) {
		$user_data = get_user_by( 'email', $user_input );
	} else {
		$user_data = get_user_by( 'login', $user_input );
	}

	if ( $user_data ) {
		// توليد مفتاح الاستعادة واستدعاء بريد ووردبريس القياسي
		$key = get_password_reset_key( $user_data );
		if ( ! is_wp_error( $key ) ) {
			$reset_url = add_query_arg(
				array(
					'action' => 'rp',
					'key'    => $key,
					'login'  => rawurlencode( $user_data->user_login ),
				),
				cdt_get_account_page_url( 'lost-password' )
			);

			$site_title = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			$subject    = sprintf( __( '[%s] استعادة كلمة المرور', 'code-elta6ur' ), $site_title );
			$message    = sprintf(
				/* translators: 1: username, 2: reset URL, 3: site name */
				__( "مرحباً %1\$s،\n\nتلقينا طلباً لتعيين كلمة مرور جديدة لحسابك في %3\$s.\n\nإذا كنت أنت صاحب هذا الطلب، يرجى النقر على الرابط أدناه:\n%2\$s\n\nإذا لم تطلب ذلك، يمكنك تجاهل هذه الرسالة بأمان.\n\nمع تحيات فريق %3\$s", 'code-elta6ur' ),
				$user_data->display_name,
				$reset_url,
				$site_title
			);

			wp_mail( $user_data->user_email, wp_specialchars_decode( $subject ), $message );
		}
	}

	cdt_set_auth_message( 'success', $safe_generic_message );
	wp_safe_redirect( cdt_get_account_page_url( 'lost-password' ) );
	exit;
}
add_action( 'template_redirect', 'cdt_handle_lost_password_action', 10 );

/**
 * 4. معالجة تعيين كلمة المرور الجديدة بعد النقر على الرابط
 */
function cdt_handle_reset_password_action() {
	if ( ! isset( $_POST['cdt_reset_password_submit'] ) ) {
		return;
	}

	if ( ! isset( $_POST['cdt_reset_password_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['cdt_reset_password_nonce'] ), 'cdt_reset_password_action' ) ) {
		cdt_set_auth_message( 'error', __( 'انتهت صلاحية جلسة الأمان، يرجى إعادة المحاولة.', 'code-elta6ur' ) );
		return;
	}

	$key      = isset( $_POST['rp_key'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_key'] ) ) : '';
	$login    = isset( $_POST['rp_login'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_login'] ) ) : '';
	$pass1    = isset( $_POST['pass1'] ) ? (string) $_POST['pass1'] : '';
	$pass2    = isset( $_POST['pass2'] ) ? (string) $_POST['pass2'] : '';

	if ( empty( $pass1 ) || strlen( $pass1 ) < 8 ) {
		cdt_set_auth_message( 'error', __( 'كلمة المرور يجب ألا تقل عن 8 خانات.', 'code-elta6ur' ) );
		return;
	}

	if ( $pass1 !== $pass2 ) {
		cdt_set_auth_message( 'error', __( 'كلمتا المرور غير متطابقتين.', 'code-elta6ur' ) );
		return;
	}

	$user = check_password_reset_key( $key, $login );

	if ( is_wp_error( $user ) ) {
		cdt_set_auth_message( 'error', __( 'رابط تعيين كلمة المرور منتهي الصلاحية أو غير صحيح. يرجى طلب رابط جديد.', 'code-elta6ur' ) );
		return;
	}

	reset_password( $user, $pass1 );

	cdt_set_auth_message( 'success', __( 'تم تعيين كلمة المرور الجديدة بنجاح! يمكنك الآن تسجيل الدخول.', 'code-elta6ur' ) );
	wp_safe_redirect( cdt_get_account_page_url( 'login' ) );
	exit;
}
add_action( 'template_redirect', 'cdt_handle_reset_password_action', 10 );

/**
 * 5. معالجة تحديث بيانات الحساب وكلمة المرور من لوحة التحكم (Settings Tab)
 */
function cdt_handle_account_settings_update() {
	if ( ! isset( $_POST['cdt_update_account_submit'] ) ) {
		return;
	}

	if ( ! is_user_logged_in() ) {
		return;
	}

	if ( ! isset( $_POST['cdt_settings_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['cdt_settings_nonce'] ), 'cdt_settings_action' ) ) {
		cdt_set_auth_message( 'error', __( 'جلسة الأمان منتهية، يرجى إعادة المحاولة.', 'code-elta6ur' ) );
		return;
	}

	$user_id      = get_current_user_id();
	$user         = get_userdata( $user_id );
	$display_name = isset( $_POST['display_name'] ) ? sanitize_text_field( wp_unslash( $_POST['display_name'] ) ) : '';
	$email        = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$bio          = isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '';

	$current_pass = isset( $_POST['current_password'] ) ? (string) $_POST['current_password'] : '';
	$new_pass     = isset( $_POST['new_password'] ) ? (string) $_POST['new_password'] : '';
	$confirm_pass = isset( $_POST['confirm_password'] ) ? (string) $_POST['confirm_password'] : '';

	$userdata = array( 'ID' => $user_id );

	if ( ! empty( $display_name ) ) {
		$userdata['display_name'] = $display_name;
	}

	if ( ! empty( $email ) && $email !== $user->user_email ) {
		if ( ! is_email( $email ) ) {
			cdt_set_auth_message( 'error', __( 'البريد الإلكتروني الجديد غير صالح.', 'code-elta6ur' ) );
			return;
		}
		if ( email_exists( $email ) && email_exists( $email ) !== $user_id ) {
			cdt_set_auth_message( 'error', __( 'البريد الإلكتروني هذا مستخدم في حساب آخر.', 'code-elta6ur' ) );
			return;
		}
		$userdata['user_email'] = $email;
	}

	$userdata['description'] = $bio;

	// تغيير كلمة المرور إذا طُلبت
	if ( ! empty( $new_pass ) ) {
		if ( empty( $current_pass ) ) {
			cdt_set_auth_message( 'error', __( 'يجب إدخال كلمة المرور الحالية لتغيير كلمة المرور.', 'code-elta6ur' ) );
			return;
		}
		if ( ! wp_check_password( $current_pass, $user->user_pass, $user_id ) ) {
			cdt_set_auth_message( 'error', __( 'كلمة المرور الحالية غير صحيحة.', 'code-elta6ur' ) );
			return;
		}
		if ( strlen( $new_pass ) < 8 ) {
			cdt_set_auth_message( 'error', __( 'كلمة المرور الجديدة يجب ألا تقل عن 8 خانات.', 'code-elta6ur' ) );
			return;
		}
		if ( $new_pass !== $confirm_pass ) {
			cdt_set_auth_message( 'error', __( 'كلمتا المرور الجديدتان غير متطابقتين.', 'code-elta6ur' ) );
			return;
		}
		$userdata['user_pass'] = $new_pass;
	}

	$updated = wp_update_user( $userdata );

	if ( is_wp_error( $updated ) ) {
		cdt_set_auth_message( 'error', $updated->get_error_message() );
		return;
	}

	// إذا تغيرت كلمة المرور، إعادة تعيين الكوكيز حتى لا يُطرد المستخدم
	if ( ! empty( $new_pass ) ) {
		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id, true );
	}

	cdt_set_auth_message( 'success', __( 'تم حفظ التعديلات وتحديث إعدادات الحساب بنجاح.', 'code-elta6ur' ) );
	wp_safe_redirect( add_query_arg( 'tab', 'settings', cdt_get_account_page_url( 'account' ) ) );
	exit;
}
add_action( 'template_redirect', 'cdt_handle_account_settings_update', 10 );
