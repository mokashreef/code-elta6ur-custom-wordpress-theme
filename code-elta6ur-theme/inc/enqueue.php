<?php
/**
 * إدارة وتسجيل الأنماط والسكربتات
 * Code Elta6ur Enqueue Scripts & Styles
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add preconnect resource hints for Google Fonts
 */
function cdt_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.googleapis.com',
		);
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'cdt_resource_hints', 10, 2 );

/**
 * Enqueue scripts and styles.
 */
function cdt_scripts() {
	// 1. Google Fonts: Cairo + Outfit
	$fonts_url = 'https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap';
	wp_enqueue_style( 'cdt-google-fonts', $fonts_url, array(), null );

	// 2. Base Theme Style (Design Tokens & Reset)
	wp_enqueue_style( 'cdt-style', get_stylesheet_uri(), array( 'cdt-google-fonts' ), CDT_THEME_VERSION );

	// 3. Main Theme CSS Components
	wp_enqueue_style( 'cdt-main', CDT_THEME_URI . '/assets/css/main.css', array( 'cdt-style' ), CDT_THEME_VERSION );

	// 4. RTL Supplemental Style
	if ( is_rtl() ) {
		wp_enqueue_style( 'cdt-rtl', CDT_THEME_URI . '/assets/css/rtl.css', array( 'cdt-main' ), CDT_THEME_VERSION );
	}

	// 5. Elementor Compatibility CSS Layer (loads after all theme styles)
	$compat_deps = is_rtl() ? array( 'cdt-main', 'cdt-rtl' ) : array( 'cdt-main' );
	wp_enqueue_style( 'cdt-elementor-compat', CDT_THEME_URI . '/assets/css/elementor-compat.css', $compat_deps, CDT_THEME_VERSION );

	// 6. Navigation and Interactive Scripts
	wp_enqueue_script( 'cdt-navigation', CDT_THEME_URI . '/assets/js/navigation.js', array(), CDT_THEME_VERSION, true );

	// 7. Video Modal for YouTube Player
	wp_enqueue_script( 'cdt-video-modal', CDT_THEME_URI . '/assets/js/video-modal.js', array(), CDT_THEME_VERSION, true );

	// 8. Code Copy to Clipboard (for single posts & code blocks)
	if ( is_singular() ) {
		wp_enqueue_script( 'cdt-code-copy', CDT_THEME_URI . '/assets/js/code-copy.js', array(), CDT_THEME_VERSION, true );
		wp_localize_script(
			'cdt-code-copy',
			'cdtL10n',
			array(
				'copied' => esc_html__( 'تم النسخ!', 'code-elta6ur' ),
				'copy'   => esc_html__( 'نسخ الكود', 'code-elta6ur' ),
			)
		);
	}

	// 9. Contact Form AJAX Handler
	wp_enqueue_script( 'cdt-contact-form', CDT_THEME_URI . '/assets/js/contact-form.js', array(), CDT_THEME_VERSION, true );
	wp_localize_script(
		'cdt-contact-form',
		'cdtContactData',
		array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'sending'   => esc_html__( 'جاري الإرسال...', 'code-elta6ur' ),
			'send'      => esc_html__( 'إرسال الرسالة الآن', 'code-elta6ur' ),
			'success'   => esc_html__( 'تم إرسال رسالتك بنجاح! سنقوم بمراجعتها والرد عليك في غضون 24 ساعة.', 'code-elta6ur' ),
			'error'     => esc_html__( 'تعذر إرسال الرسالة عبر خادم البريد، يرجى مراسلتنا مباشرة عبر واتساب أو تيليجرام.', 'code-elta6ur' ),
			'reqFields' => esc_html__( 'يرجى ملء جميع الحقول المطلوبة (الاسم، البريد، والرسالة).', 'code-elta6ur' ),
		)
	);

	// 10. Tools Engine Assets (Loaded exclusively on /tools/* pages)
	if ( function_exists( 'cdt_get_current_tool_route' ) && cdt_get_current_tool_route() ) {
		wp_enqueue_style( 'cdt-tools-style', CDT_THEME_URI . '/assets/css/tools.css', array( 'cdt-main' ), CDT_THEME_VERSION );
		wp_enqueue_script( 'cdt-tools-script', CDT_THEME_URI . '/assets/js/tools-engine.js', array(), CDT_THEME_VERSION, true );
	}

	// 11. Code Editor Engine Assets (Loaded exclusively on /editor/* pages)
	if ( function_exists( 'cdt_get_current_editor_route' ) && cdt_get_current_editor_route() ) {
		wp_enqueue_style( 'cdt-editor-style', CDT_THEME_URI . '/assets/css/editor.css', array( 'cdt-main' ), CDT_THEME_VERSION );
		wp_enqueue_script( 'cdt-editor-script', CDT_THEME_URI . '/assets/js/editor-engine.js', array(), CDT_THEME_VERSION, true );
		wp_localize_script(
			'cdt-editor-script',
			'cdtEditorData',
			array(
				'apiEndpoint'    => esc_url_raw( rest_url( 'cdt/v1/execute' ) ),
				'editorBaseUrl'  => esc_url_raw( home_url( '/editor/' ) ),
				'activeLanguage' => cdt_get_current_editor_lang(),
			)
		);
	}

	// 12. User Accounts & Gamification Assets
	$is_account_page = is_page( array( 'login', 'registration', 'register', 'lost-password', 'my-account' ) );
	if ( $is_account_page || is_user_logged_in() || is_singular() ) {
		wp_enqueue_style( 'cdt-accounts-style', CDT_THEME_URI . '/assets/css/accounts.css', array( 'cdt-main' ), CDT_THEME_VERSION );
	}

	if ( $is_account_page ) {
		wp_enqueue_script( 'cdt-accounts-script', CDT_THEME_URI . '/assets/js/accounts.js', array(), CDT_THEME_VERSION, true );
	}

	// Real-time Gamification & Reading Tracker
	if ( is_user_logged_in() || is_singular() ) {
		wp_enqueue_script( 'cdt-gamification-tracker', CDT_THEME_URI . '/assets/js/gamification-tracker.js', array(), CDT_THEME_VERSION, true );
		wp_localize_script(
			'cdt-gamification-tracker',
			'cdtGamificationData',
			array(
				'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'cdt_gamification_nonce' ),
				'postId'     => is_singular() ? get_the_ID() : 0,
				'isLoggedIn' => is_user_logged_in(),
			)
		);
	}

	// Threaded comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'cdt_scripts' );
