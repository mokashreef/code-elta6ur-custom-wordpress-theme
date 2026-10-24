<?php
/**
 * CSS & Frontend Tools Definitions (v2.1 Final Gate Spec)
 *
 * @package CodeElta6urTheme
 * @subpackage Tools/Definitions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	// 28. CSS Specificity Calculator
	'css-specificity-calculator' => array(
		'id'                  => 'css-specificity-calculator',
		'slug'                => 'css-specificity-calculator',
		'name'                => 'CSS Specificity Calculator',
		'name_en'             => 'CSS Specificity Calculator',
		'name_ar'             => 'حاسبة أولوية وقوة محددات CSS',
		'title'               => 'CSS Specificity Calculator | حساب وزن وتفضيل محددات الأنماط CSS',
		'description'         => 'احسب بدقة وزن وقوة أي محدد CSS (IDs, Classes, Attributes, Pseudo-classes, Elements) واكتشف لماذا يتغلب كود نمط على آخر دون استخدام !important.',
		'category'            => 'css-frontend',
		'icon'                => 'palette',
		'badge'               => 'حساب',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'string', 'format' => 'css_selectors' ),
		'output_schema'       => array( 'specificity_tuple' => 'array', 'score' => 'integer', 'breakdown' => 'object' ),
		'dependencies'        => array( 'client' => 'css_selector_lexer', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'حاسبة أولوية محددات CSS أونلاين (CSS Specificity Calculator) | كود التطور',
		'seo_desc'            => 'احسب درجات Specificity لمحددات CSS وقارن بين المحددات لحل تعارضات التنسيق باحترافية.',
		'keywords'            => array( 'CSS Specificity Calculator', 'أولوية محددات CSS', 'حساب وزن CSS', 'CSS Selector Specificity' ),
		'related_tools'       => array( 'css-clamp-generator', 'css-unit-converter', 'regex-tester' ),
		'acceptance_criteria' => array(
			'يحسب وزن المحدد الثلاثي بدقة: (A, B, C) حيث A = معرفات ID، و B = الفئات Classes والسمات Attributes وأشباه الفئات Pseudo-classes، و C = العناصر Elements وأشباه العناصر Pseudo-elements.',
			'يتعامل مع المحددات المعقدة والمتسلسلة وفواصل الأنماط (> , + , ~ , space) ومحددات :not() و :is().',
		),
		'examples'            => array( 'header.site-header nav#main-nav ul > li.active a:hover' ),
		'faq'                 => array(
			array(
				'q' => 'كيف يتم حساب قيمة الـ Specificity؟',
				'a' => 'تحسب كقيمة ثلاثية: (a, b, c) حيث a يمثل عدد الـ IDs، و b يمثل الـ Classes والخصائص، و c يمثل وسوم العناصر.',
			),
		),
	),

	// 29. CSS Clamp Generator
	'css-clamp-generator' => array(
		'id'                  => 'css-clamp-generator',
		'slug'                => 'css-clamp-generator',
		'name'                => 'CSS Clamp Generator',
		'name_en'             => 'CSS Clamp Generator',
		'name_ar'             => 'مولد خطوط وأبعاد clamp() المتجاوبة',
		'title'               => 'CSS Clamp Generator | حساب دالة clamp() للخطوط والأبعاد المتجاوبة بسلاسة',
		'description'         => 'احسب دالة CSS clamp(min, val, max) للطباعة والخطوط المتجاوبة (Fluid Typography) والمسافات السلسة التي تتغير تلقائياً مع حجم الشاشة دون الحاجة لاستعلامات Media Queries متعددة.',
		'category'            => 'css-frontend',
		'icon'                => 'aspect-ratio',
		'badge'               => 'شائع',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'min_viewport' => 'number', 'max_viewport' => 'number', 'min_size' => 'number', 'max_size' => 'number' ),
		'output_schema'       => array( 'clamp_css' => 'string', 'slope' => 'number', 'intercept' => 'number' ),
		'dependencies'        => array( 'client' => 'math_linear_interpolation', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'مولد دالة CSS clamp() للخطوط المتجاوبة أونلاين | كود التطور',
		'seo_desc'            => 'ولد معادلة clamp() الاحترافية لتنسيق نصوص ومسافات متجاوبة بمرونة كاملة عبر جميع أحجام الشاشات.',
		'keywords'            => array( 'CSS Clamp Generator', 'دالة clamp CSS', 'Fluid Typography', 'خطوط متجاوبة CSS' ),
		'related_tools'       => array( 'css-unit-converter', 'css-specificity-calculator', 'semver-calculator' ),
		'acceptance_criteria' => array(
			'يحسب معادلة الميل الرياضي الدقيقة: slope = (max_size - min_size) / (max_viewport - min_viewport).',
			'يولد دالة صالحة: clamp(MINrem, INTERCEPTrem + SLOPEvw, MAXrem) مع إتاحة المعاينة التفاعلية لتغير الحجم أثناء تغيير مقاس النافذة.',
		),
		'examples'            => array( 'Min: 16px (Viewport 375px) | Max: 32px (Viewport 1440px)' ),
		'faq'                 => array(
			array(
				'q' => 'ما الفائدة من استخدام clamp()؟',
				'a' => 'تمنحك تحكماً سلساً بحجم الخط والمسافات بحيث يكبر ويصغر تلقائياً بين حد أدنى وحد أقصى وفق عرض شاشة المستخدم.',
			),
		),
	),

	// 30. CSS Unit Converter
	'css-unit-converter' => array(
		'id'                  => 'css-unit-converter',
		'slug'                => 'css-unit-converter',
		'name'                => 'CSS Unit Converter',
		'name_en'             => 'CSS Unit Converter',
		'name_ar'             => 'محول وحدات القياس في CSS',
		'title'               => 'CSS Unit Converter | تحويل فوري بين px, rem, em, vw, vh, pt',
		'description'         => 'محول وحدات CSS التفاعلي: تحويل فوري بين البكسل (px)، وحدات الجذر (rem)، والوحدات النسبية (em) ونسب عرض وارتفاع الشاشة (vw/vh) ونقاط الطباعة (pt) مع تخصيص حجم الخط الأساسي (Root Font Size).',
		'category'            => 'css-frontend',
		'icon'                => 'rulers',
		'badge'               => 'تحويل',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'value' => 'number', 'from_unit' => 'string', 'root_font_size' => 'number' ),
		'output_schema'       => array( 'converted' => 'object' ),
		'dependencies'        => array( 'client' => 'unit_math_engine', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'محول وحدات CSS أونلاين (PX to REM / EM / VW) | كود التطور',
		'seo_desc'            => 'حول وحدات قياس CSS بسهولة بين px و rem و em مع إمكانية تحديد الحجم الأساسي لـ HTML ومعاينة حية للنتائج.',
		'keywords'            => array( 'CSS Unit Converter', 'PX to REM', 'تحويل بكسل إلى ريم', 'محول وحدات CSS' ),
		'related_tools'       => array( 'css-clamp-generator', 'css-specificity-calculator', 'json-to-code' ),
		'acceptance_criteria' => array(
			'يحول بدقة بين: px, rem, em, %, vw, vh, pt.',
			'يتيح ضبط Root Font Size (الافتراضي 16px) وأبعاد نافذة العرض للمعاينة الآنية الحية.',
		),
		'examples'            => array( '24px with 16px base -> 1.5rem / 1.5em / 150%' ),
		'faq'                 => array(
			array(
				'q' => 'ما هو الحجم الأساسي الافتراضي لـ REM؟',
				'a' => 'الحجم القياسي الافتراضي لمعظم المتصفحات هو 16px، أي أن 1rem = 16px.',
			),
		),
	),
);
