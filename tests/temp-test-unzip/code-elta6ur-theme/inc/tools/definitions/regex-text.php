<?php
/**
 * Regex & Text Tools Definitions (v2.1 Final Gate Spec)
 *
 * @package CodeElta6urTheme
 * @subpackage Tools/Definitions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	// 16. Regex Tester & Explainer
	'regex-tester' => array(
		'id'                  => 'regex-tester',
		'slug'                => 'regex-tester',
		'name'                => 'Regex Tester & Explainer',
		'name_en'             => 'Regex Tester & Explainer',
		'name_ar'             => 'مختبر وشارح التعبيرات النمطية (Regex)',
		'title'               => 'Regex Tester & Explainer | اختبار التعبيرات النمطية وشرحها بالتفصيل',
		'description'         => 'اختبر تعبيرات Regex في الوقت الفعلي مع تلوين المطابقات، استخراج المجموعات (Capture Groups)، وشرح مفصل لكل جزء من التعبير بالعربية والإنجليزية.',
		'category'            => 'regex-text',
		'icon'                => 'regex',
		'badge'               => 'مهم جداً',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'pattern' => 'string', 'flags' => 'string', 'text' => 'string' ),
		'output_schema'       => array( 'matches' => 'array', 'groups' => 'array', 'explanation' => 'array' ),
		'dependencies'        => array( 'client' => 'js_regex_engine', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'مختبر وشارح التعبيرات النمطية أونلاين (Regex Tester & Explainer) | كود التطور',
		'seo_desc'            => 'اختبر وشرح تعبيرات Regex أونلاين مع دعم الأعلام (Flags) واستخراج المجموعات ومعاينة حية للمطابقات.',
		'keywords'            => array( 'Regex Tester', 'اختبار Regex', 'شرح Regex', 'Regular Expressions' ),
		'related_tools'       => array( 'regex-builder', 'jsonpath-tester', 'semver-calculator' ),
		'acceptance_criteria' => array(
			'يدعم الأعلام القياسية: g (Global), i (Case Insensitive), m (Multiline), s (DotAll), u (Unicode).',
			'يستخرج مجموعات الالتقاط المرقمة والمسمّاة (Named & Numbered Capture Groups) مع مواضع البداية والنهاية (Indices).',
			'يقدم شرحاً تفصيلياً لعناصر النمط (الرموز الحرفية، الفئات، التكرارات، وحدود الكلمات).',
			'يلتقط أخطاء التعبير النمطي (Syntax Errors) ويعرض سببه بوضوح.',
		),
		'examples'            => array( "Pattern: /([a-zA-Z0-9._%+-]+)@([a-zA-Z0-9.-]+\\.[a-zA-Z]{2,})/g\nText: Contact us at info@code-elta6ur.com or support@example.org" ),
		'faq'                 => array(
			array(
				'q' => 'ما هي الأعلام (Flags) المدعومة؟',
				'a' => 'تدعم الأداة: g (Global), i (Case Insensitive), m (Multiline), s (DotAll), u (Unicode).',
			),
		),
	),

	// 17. Regex Builder from Examples
	'regex-builder' => array(
		'id'                  => 'regex-builder',
		'slug'                => 'regex-builder',
		'name'                => 'Regex Builder from Examples',
		'name_en'             => 'Regex Builder from Examples',
		'name_ar'             => 'منشئ Regex من الأمثلة والنصوص',
		'title'               => 'Regex Builder | إنشاء تعبيرات Regex تلقائياً عبر تقديم أمثلة للنصوص',
		'description'         => 'أدخل أمثلة على النصوص التي تريد مطابقتها وأمثلة على ما تريد استبعاده، وستقوم الأداة باقتراح النمط المناسب فورياً.',
		'category'            => 'regex-text',
		'icon'                => 'magic',
		'badge'               => 'ذكي',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'match_examples' => 'array', 'ignore_examples' => 'array' ),
		'output_schema'       => array( 'generated_pattern' => 'string', 'accuracy' => 'string' ),
		'dependencies'        => array( 'client' => 'regex_heuristic_generator', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'      => 'منشئ تعبيرات Regex من الأمثلة والنصوص أونلاين | كود التطور',
		'seo_desc'       => 'ولد تعبير Regex تلقائياً بمجرد إدخال نصوص وعينات مطابقة مع فحص مباشر لصحة النمط الناتج.',
		'keywords'       => array( 'Regex Builder', 'إنشاء Regex', 'توليد تعبير نمطي', 'Regex Generator' ),
		'related_tools'  => array( 'regex-tester', 'jsonpath-tester', 'git-command-generator' ),
		'acceptance_criteria' => array(
			'يحلل العينات الإيجابية والسلبية ويكتشف القواسم المشتركة (أرقام، حروف، رموز خاصة، أطوال ثابتة أو متغيرة).',
			'يولد نمطاً دقيقاً ويتحقق فورياً من نجاحه في مطابقة جميع العينات الإيجابية وفشله في مطابقة السلبية.',
		),
		'examples'       => array( 'Match: user_123, admin_456 | Ignore: 123, user' ),
		'faq'            => array(
			array(
				'q' => 'كيف يقوم المنشئ بتوليد النمط؟',
				'a' => 'يحلل الحروف والأرقام والأنماط الشائعة (كالإيميل، الأرقام، التواريخ) ويقترح النمط الأكثر أماناً ودقة.',
			),
		),
	),
);
