<?php
/**
 * معالج إرسال نموذج اتصل بنا عبر AJAX
 * Code Elta6ur Contact Form AJAX Handler
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle AJAX contact form submission
 */
function cdt_handle_contact_form_submission() {
	// Verify nonce
	if ( ! isset( $_POST['cdt_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cdt_nonce'] ) ), 'cdt_contact_action' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'انتهت صلاحية الجلسة، يرجى تحديث الصفحة والمحاولة مجدداً.', 'code-elta6ur' ) ) );
	}

	// Validate required fields
	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( empty( $name ) || empty( $email ) || ! is_email( $email ) || empty( $message ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'يرجى إدخال اسم صحيح وبريد إلكتروني صالح ونص للرسالة.', 'code-elta6ur' ) ) );
	}

	// Target recipient email: customizer option or site admin email
	$to_email = cdt_get_option( 'cdt_email', 'contact@code-elta6ur.com' );
	if ( ! is_email( $to_email ) ) {
		$to_email = get_option( 'admin_email' );
	}

	$email_subject = sprintf(
		/* translators: 1: Site name, 2: Subject/Name */
		esc_html__( '[%1$s] رسالة تواصل جديدة: %2$s', 'code-elta6ur' ),
		get_bloginfo( 'name' ),
		$subject ? $subject : $name
	);

	$email_body  = esc_html__( 'وصلتك رسالة جديدة عبر نموذج الاتصال في منصة كود التطور:', 'code-elta6ur' ) . "\n\n";
	$email_body .= esc_html__( 'الاسم الكامل: ', 'code-elta6ur' ) . $name . "\n";
	$email_body .= esc_html__( 'البريد الإلكتروني: ', 'code-elta6ur' ) . $email . "\n";
	if ( ! empty( $phone ) ) {
		$email_body .= esc_html__( 'رقم الهاتف / واتساب: ', 'code-elta6ur' ) . $phone . "\n";
	}
	if ( ! empty( $subject ) ) {
		$email_body .= esc_html__( 'موضوع الرسالة: ', 'code-elta6ur' ) . $subject . "\n";
	}
	$email_body .= "\n" . esc_html__( 'نص الرسالة:', 'code-elta6ur' ) . "\n";
	$email_body .= str_repeat( '-', 40 ) . "\n";
	$email_body .= $message . "\n";
	$email_body .= str_repeat( '-', 40 ) . "\n\n";
	$email_body .= esc_html__( 'تاريخ الإرسال: ', 'code-elta6ur' ) . current_time( 'mysql' ) . "\n";
	$email_body .= esc_html__( 'عنوان IP المرسل: ', 'code-elta6ur' ) . ( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'N/A' ) . "\n";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	$sent = wp_mail( $to_email, $email_subject, $email_body, $headers );

	if ( $sent ) {
		wp_send_json_success( array(
			'message' => esc_html__( 'تم إرسال رسالتك بنجاح! سنقوم بمراجعتها والرد عليك في غضون 24 ساعة.', 'code-elta6ur' ),
		) );
	} else {
		wp_send_json_error( array(
			'message' => esc_html__( 'تعذر إرسال الرسالة عبر خادم البريد حالياً. يرجى التواصل معنا مباشرة عبر واتساب أو تيليجرام.', 'code-elta6ur' ),
		) );
	}
}
add_action( 'wp_ajax_cdt_contact_send', 'cdt_handle_contact_form_submission' );
add_action( 'wp_ajax_nopriv_cdt_contact_send', 'cdt_handle_contact_form_submission' );
