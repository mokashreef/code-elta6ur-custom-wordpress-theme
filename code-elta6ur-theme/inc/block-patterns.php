<?php
/**
 * تسجيل أنماط المكونات المخصصة لمنصة كود التطور
 * Code Elta6ur Gutenberg Block Patterns
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Code Elta6ur Pattern Category & Block Patterns
 */
function cdt_register_block_patterns() {
	if ( ! function_exists( 'register_block_pattern_category' ) ) {
		return;
	}

	// 1. Register Dedicated Pattern Category
	register_block_pattern_category(
		'code-elta6ur',
		array(
			'label' => esc_html__( 'أنماط كود التطور | Code Elta6ur', 'code-elta6ur' ),
		)
	);

	// 2. Hero Section Pattern
	register_block_pattern(
		'code-elta6ur/hero',
		array(
			'title'       => esc_html__( 'بانر الهيرو الرئيسي (Hero Section)', 'code-elta6ur' ),
			'description' => esc_html__( 'بانر ترحيبي عريض مع عنوان بارز وزرين لاتخاذ الإجراء.', 'code-elta6ur' ),
			'categories'  => array( 'code-elta6ur', 'featured' ),
			'content'     => '<!-- wp:group {"align":"full","className":"cdt-hero-pattern","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull cdt-hero-pattern has-white-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:heading {"textAlign":"center","level":1,"className":"cdt-hero-title","fontSize":"x-large"} -->
<h1 class="wp-block-heading has-text-align-center cdt-hero-title has-x-large-font-size">رؤية مبتكرة في عالم البرمجة والعمل وحتى الذكاء الاصطناعي</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textAlign":"center","className":"cdt-hero-desc","fontSize":"medium"} -->
<p class="has-text-align-center cdt-hero-desc has-medium-font-size">منصة تعليمية عربية تركز على تبسيط مفاهيم البرمجة وتطوير الويب خطوة بخطوة، مع كبرى الدورات التدريبية والمقالات العملية.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"cdt-btn-primary"} -->
<div class="wp-block-button cdt-btn-primary"><a class="wp-block-button__link wp-element-button" href="/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/">ابدأ التعلم الآن</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"cdt-btn-outline is-style-outline"} -->
<div class="wp-block-button is-style-outline cdt-btn-outline"><a class="wp-block-button__link wp-element-button" href="/%d8%a7%d9%84%d9%85%d8%af%d9%88%d9%86%d8%a9/">تصفّح المقالات</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
		)
	);

	// 3. Goals & Features Pattern
	register_block_pattern(
		'code-elta6ur/features',
		array(
			'title'       => esc_html__( 'أهداف ومميزات كود التطور الأربعة (Platform Goals)', 'code-elta6ur' ),
			'description' => esc_html__( 'شبكة من 4 بطاقات للأهداف الرئيسية لمنصة كود التطور.', 'code-elta6ur' ),
			'categories'  => array( 'code-elta6ur' ),
			'content'     => '<!-- wp:group {"align":"wide","className":"cdt-features-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide cdt-features-section"><!-- wp:heading {"textAlign":"center","level":2,"className":"cdt-section-title"} -->
<h2 class="wp-block-heading has-text-align-center cdt-section-title">الأهداف الرئيسية للموقع</h2>
<!-- /wp:heading -->

<!-- wp:columns {"columns":4,"className":"cdt-features-grid"} -->
<div class="wp-block-columns cdt-features-grid"><!-- wp:column {"className":"cdt-feature-card"} -->
<div class="wp-block-column cdt-feature-card"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">تقديم محتوى عربي احترافي</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>شروحات دقيقة باللغة العربية تجمع بين البساطة والعمق التقني لجميع المستويات.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"cdt-feature-card"} -->
<div class="wp-block-column cdt-feature-card"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">توفير دورات شاملة</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>مسارات تعليمية متسلسلة تبدأ من الأساسيات وتتدرج حتى بناء مشاريع ويب وتطبيقات حقيقية.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"cdt-feature-card"} -->
<div class="wp-block-column cdt-feature-card"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">رفع مستوى المطور العربي</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>التركيز على مهارات التفكير البرمجي وكتابة Clean Code وتأهيل المتعلمين للعمل الحر.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"cdt-feature-card"} -->
<div class="wp-block-column cdt-feature-card"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">بناء مجتمع تفاعلي</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>منصة تفاعلية تتيح تبادل الخبرات ومشاركة الحلول والنقاشات البرمجية الهادفة.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
		)
	);

	// 4. CTA Pattern
	register_block_pattern(
		'code-elta6ur/cta',
		array(
			'title'       => esc_html__( 'بانر الدعوة لاتخاذ إجراء (Call To Action)', 'code-elta6ur' ),
			'description' => esc_html__( 'بانر جذاب يحفز الزائر على بدء مساره التعليمي أو التواصل.', 'code-elta6ur' ),
			'categories'  => array( 'code-elta6ur' ),
			'content'     => '<!-- wp:group {"align":"wide","className":"cdt-cta-box","style":{"spacing":{"padding":{"top":"40px","bottom":"40px","left":"30px","right":"30px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide cdt-cta-box" style="padding-top:40px;padding-right:30px;padding-bottom:40px;padding-left:30px"><!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">هل أنت جاهز لتطوير مهاراتك البرمجية؟</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"textAlign":"center"} -->
<p class="has-text-align-center">انضم إلى آلاف المتعلمين واستكشف دوراتنا المجانية في تطوير الويب والبرمجيات خطوة بخطوة.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"cdt-btn-primary"} -->
<div class="wp-block-button cdt-btn-primary"><a class="wp-block-button__link wp-element-button" href="/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/">تصفح كافة الكورسات</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
		)
	);

	// 5. FAQ Pattern
	register_block_pattern(
		'code-elta6ur/faq',
		array(
			'title'       => esc_html__( 'الأسئلة الشائعة (FAQ Accordion)', 'code-elta6ur' ),
			'description' => esc_html__( 'قسم أسئلة وأجوبة تفاعلية لطلاب البرمجة.', 'code-elta6ur' ),
			'categories'  => array( 'code-elta6ur' ),
			'content'     => '<!-- wp:group {"align":"wide","className":"cdt-faq-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide cdt-faq-section"><!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">الأسئلة الشائعة</h2>
<!-- /wp:heading -->

<!-- wp:details {"className":"cdt-faq-item"} -->
<details class="wp-block-details cdt-faq-item"><summary>هل دورات كود التطور مجانية بالكامل؟</summary><!-- wp:paragraph -->
<p>نعم، جميع المقالات والدروس والشروحات الأساسية المتاحة على المنصة وقناة اليوتيوب مقدمة مجاناً لدعم المحتوى العربي.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details {"className":"cdt-faq-item"} -->
<details class="wp-block-details cdt-faq-item"><summary>من أين أبدأ إذا كنت مبتدئاً في البرمجة؟</summary><!-- wp:paragraph -->
<p>ننصح بالبدء بدورة HTML ثم CSS لتأسيس مهارات بناء واجهات الويب، يليهما تعلم لغة JavaScript لإضافة التفاعلية.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details {"className":"cdt-faq-item"} -->
<details class="wp-block-details cdt-faq-item"><summary>كيف يمكنني التواصل مع إدارة المنصة أو طرح استفسار؟</summary><!-- wp:paragraph -->
<p>يمكنك التواصل مباشرة عبر صفحة اتصل بنا أو من خلال قناة التيليجرام أو الواتساب المتاحة في تذييل الموقع.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details --></div>
<!-- /wp:group -->',
		)
	);
}
add_action( 'init', 'cdt_register_block_patterns' );
