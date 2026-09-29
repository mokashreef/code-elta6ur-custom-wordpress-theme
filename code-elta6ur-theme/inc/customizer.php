<?php
/**
 * إعدادات كود التطور المركزية في لوحة التخصيص
 * Code Elta6ur Centralized Customizer Settings
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Customizer Settings & Controls
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function cdt_customize_register( $wp_customize ) {

	// 1. Create Main Theme Panel
	$wp_customize->add_panel(
		'cdt_theme_panel',
		array(
			'title'       => esc_html__( '🎨 إعدادات منصة كود التطور', 'code-elta6ur' ),
			'description' => esc_html__( 'التحكم المركزي في بيانات التواصل، روابط السوشيال، والألوان بدون الحاجة لكتابة كود.', 'code-elta6ur' ),
			'priority'    => 20,
		)
	);

	// ==========================================
	// Section: Contact & Direct Communication
	// ==========================================
	$wp_customize->add_section(
		'cdt_contact_section',
		array(
			'title'    => esc_html__( '📞 بيانات التواصل المباشر', 'code-elta6ur' ),
			'panel'    => 'cdt_theme_panel',
			'priority' => 10,
		)
	);

	// Email
	$wp_customize->add_setting(
		'cdt_email',
		array(
			'default'           => 'contact@code-elta6ur.com',
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'cdt_email',
		array(
			'label'    => esc_html__( 'البريد الإلكتروني الرسمي', 'code-elta6ur' ),
			'section'  => 'cdt_contact_section',
			'type'     => 'email',
		)
	);

	// Phone
	$wp_customize->add_setting(
		'cdt_phone',
		array(
			'default'           => '+963983769230',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'cdt_phone',
		array(
			'label'    => esc_html__( 'رقم الهاتف', 'code-elta6ur' ),
			'section'  => 'cdt_contact_section',
			'type'     => 'text',
		)
	);

	// WhatsApp
	$wp_customize->add_setting(
		'cdt_whatsapp',
		array(
			'default'           => 'https://wa.me/963983769230',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'cdt_whatsapp',
		array(
			'label'    => esc_html__( 'رابط محادثة واتساب (wa.me)', 'code-elta6ur' ),
			'section'  => 'cdt_contact_section',
			'type'     => 'url',
		)
	);

	// Telegram
	$wp_customize->add_setting(
		'cdt_telegram',
		array(
			'default'           => 'https://t.me/code_elta6ur',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'cdt_telegram',
		array(
			'label'    => esc_html__( 'رابط قناة/حساب تيليجرام', 'code-elta6ur' ),
			'section'  => 'cdt_contact_section',
			'type'     => 'url',
		)
	);

	// ==========================================
	// Section: Social Media Networks
	// ==========================================
	$wp_customize->add_section(
		'cdt_social_section',
		array(
			'title'    => esc_html__( '🌐 شبكات التواصل الاجتماعي', 'code-elta6ur' ),
			'panel'    => 'cdt_theme_panel',
			'priority' => 20,
		)
	);

	// YouTube Channel URL
	$wp_customize->add_setting(
		'cdt_youtube_url',
		array(
			'default'           => 'https://www.youtube.com/@code-elta6ur',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'cdt_youtube_url',
		array(
			'label'   => esc_html__( 'رابط قناة يوتيوب', 'code-elta6ur' ),
			'section' => 'cdt_social_section',
			'type'    => 'url',
		)
	);

	// YouTube Channel ID (for automated video feed)
	$wp_customize->add_setting(
		'cdt_youtube_channel_id',
		array(
			'default'           => 'UCrATghXGpSJ2BzcWagEjRuw',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'cdt_youtube_channel_id',
		array(
			'label'       => esc_html__( 'معرف القناة البرمجي (Channel ID)', 'code-elta6ur' ),
			'description' => esc_html__( 'المعرف المستخدم لجلب أحدث الفيديوهات تلقائياً (يبدأ بـ UC)', 'code-elta6ur' ),
			'section'     => 'cdt_social_section',
			'type'        => 'text',
		)
	);

	// Instagram
	$wp_customize->add_setting(
		'cdt_instagram',
		array(
			'default'           => 'https://www.instagram.com/code_elta6ur/',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'cdt_instagram',
		array(
			'label'   => esc_html__( 'رابط حساب إنستغرام', 'code-elta6ur' ),
			'section' => 'cdt_social_section',
			'type'    => 'url',
		)
	);

	// Facebook
	$wp_customize->add_setting(
		'cdt_facebook',
		array(
			'default'           => 'https://www.facebook.com/code.elta6ur',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'cdt_facebook',
		array(
			'label'   => esc_html__( 'رابط صفحة فيسبوك', 'code-elta6ur' ),
			'section' => 'cdt_social_section',
			'type'    => 'url',
		)
	);

	// GitHub
	$wp_customize->add_setting(
		'cdt_github',
		array(
			'default'           => 'https://github.com/mokashreef',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'cdt_github',
		array(
			'label'   => esc_html__( 'رابط حساب GitHub للمؤسس', 'code-elta6ur' ),
			'section' => 'cdt_social_section',
			'type'    => 'url',
		)
	);

	// LinkedIn
	$wp_customize->add_setting(
		'cdt_linkedin',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'cdt_linkedin',
		array(
			'label'   => esc_html__( 'رابط حساب LinkedIn', 'code-elta6ur' ),
			'section' => 'cdt_social_section',
			'type'    => 'url',
		)
	);

	// ==========================================
	// Section: Brand Colors (No Purple)
	// ==========================================
	$wp_customize->add_section(
		'cdt_colors_section',
		array(
			'title'       => esc_html__( '🎨 ألوان الهوية البصرية', 'code-elta6ur' ),
			'description' => esc_html__( 'تعديل الألوان الأساسية للمنصة (تطبق في كامل الموقع تلقائياً).', 'code-elta6ur' ),
			'panel'       => 'cdt_theme_panel',
			'priority'    => 30,
		)
	);

	// Primary Color
	$wp_customize->add_setting(
		'cdt_primary_color',
		array(
			'default'           => '#0170B9',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'cdt_primary_color',
			array(
				'label'   => esc_html__( 'اللون الأساسي (Primary Color)', 'code-elta6ur' ),
				'section' => 'cdt_colors_section',
			)
		)
	);

	// Secondary Color
	$wp_customize->add_setting(
		'cdt_secondary_color',
		array(
			'default'           => '#3B82F6',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'cdt_secondary_color',
			array(
				'label'   => esc_html__( 'اللون الثانوي (Secondary Color)', 'code-elta6ur' ),
				'section' => 'cdt_colors_section',
			)
		)
	);

	// Accent Color
	$wp_customize->add_setting(
		'cdt_accent_color',
		array(
			'default'           => '#38BDF8',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'cdt_accent_color',
			array(
				'label'   => esc_html__( 'اللون المميز (Accent Color)', 'code-elta6ur' ),
				'section' => 'cdt_colors_section',
			)
		)
	);

	// ==========================================
	// Section: Hero & Header CTA
	// ==========================================
	$wp_customize->add_section(
		'cdt_hero_section',
		array(
			'title'    => esc_html__( '🚀 قسم الهيرو والرئيسية (Hero Banner)', 'code-elta6ur' ),
			'panel'    => 'cdt_theme_panel',
			'priority' => 40,
		)
	);

	$wp_customize->add_setting(
		'cdt_hero_title',
		array(
			'default'           => 'رؤية مبتكرة في عالم البرمجة والعمل وحتى الذكاء الاصطناعي',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'cdt_hero_title',
		array(
			'label'   => esc_html__( 'عنوان الهيرو الرئيسي', 'code-elta6ur' ),
			'section' => 'cdt_hero_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'cdt_hero_subtitle',
		array(
			'default'           => 'منصة تعليمية عربية تركز على تبسيط مفاهيم البرمجة وتطوير الويب خطوة بخطوة، مع كبرى الدورات التدريبية والمقالات العملية.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'cdt_hero_subtitle',
		array(
			'label'   => esc_html__( 'الوصف الفرعي للهيرو', 'code-elta6ur' ),
			'section' => 'cdt_hero_section',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'cdt_hero_cta_text',
		array(
			'default'           => 'ابدأ التعلم الآن',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'cdt_hero_cta_text',
		array(
			'label'   => esc_html__( 'نص زر الـ CTA الرئيسي', 'code-elta6ur' ),
			'section' => 'cdt_hero_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'cdt_hero_cta_url',
		array(
			'default'           => home_url( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/' ),
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'cdt_hero_cta_url',
		array(
			'label'   => esc_html__( 'رابط زر الـ CTA الرئيسي', 'code-elta6ur' ),
			'section' => 'cdt_hero_section',
			'type'    => 'url',
		)
	);

	// ==========================================
	// Section: Footer & Copyright
	// ==========================================
	$wp_customize->add_section(
		'cdt_footer_section',
		array(
			'title'    => esc_html__( '📑 تذييل الموقع وحقوق النشر', 'code-elta6ur' ),
			'panel'    => 'cdt_theme_panel',
			'priority' => 50,
		)
	);

	$wp_customize->add_setting(
		'cdt_footer_about',
		array(
			'default'           => 'منصة تعليمية عربية متخصصة بتعليم البرمجة وتطوير الويب والذكاء الاصطناعي، عبر دورات ومقالات وأدوات عملية تساعد المتعلم على بناء مهاراته خطوة بخطوة.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'cdt_footer_about',
		array(
			'label'   => esc_html__( 'نبذة المنصة في الفوتر', 'code-elta6ur' ),
			'section' => 'cdt_footer_section',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'cdt_copyright_text',
		array(
			'default'           => 'جميع الحقوق محفوظة © كود التطور 2026',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'cdt_copyright_text',
		array(
			'label'   => esc_html__( 'نص حقوق النشر', 'code-elta6ur' ),
			'section' => 'cdt_footer_section',
			'type'    => 'text',
		)
	);
}
add_action( 'customize_register', 'cdt_customize_register' );

/**
 * Output dynamic CSS variables if customized
 */
function cdt_customizer_css() {
	$primary   = cdt_get_option( 'cdt_primary_color', '#0170B9' );
	$secondary = cdt_get_option( 'cdt_secondary_color', '#3B82F6' );
	$accent    = cdt_get_option( 'cdt_accent_color', '#38BDF8' );

	$custom_css = ":root {
		--brand-primary: {$primary};
		--brand-secondary: {$secondary};
		--brand-accent: {$accent};
		--brand-border-focus: {$primary};
	}";

	wp_add_inline_style( 'cdt-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'cdt_customizer_css', 20 );
