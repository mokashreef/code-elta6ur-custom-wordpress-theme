<?php
/**
 * JSON & Data Tools Definitions (v2.1 Final Gate Spec)
 *
 * @package CodeElta6urTheme
 * @subpackage Tools/Definitions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	// 1. JSON Formatter & Validator
	'json-formatter' => array(
		'id'                  => 'json-formatter',
		'slug'                => 'json-formatter',
		'name'                => 'JSON Formatter & Validator',
		'name_en'             => 'JSON Formatter & Validator',
		'name_ar'             => 'منسق ومدقق JSON',
		'title'               => 'JSON Formatter & Validator | تنسيق والتحقق من صحة كود JSON أونلاين',
		'description'         => 'أداة مجانية واحترافية لتنسيق، ضغط، وفحص أخطاء كود JSON في الوقت الفعلي مع كشف أرقام الأسطر التي تحتوي على خلل تركيبي.',
		'category'            => 'json-data',
		'icon'                => 'brackets-curly',
		'badge'               => 'الأكثر استخداماً',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'string', 'format' => 'json_raw' ),
		'output_schema'       => array( 'type' => 'string', 'format' => 'json_formatted' ),
		'dependencies'        => array( 'client' => 'native_json', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'منسق ومدقق JSON أونلاين مجاني (JSON Formatter & Validator) | كود التطور',
		'seo_desc'            => 'قم بتنسيق، تجميل، والتحقق من صحة كود JSON الخاص بك أونلاين بضغطة زر واحدة مع فحص فوري للأخطاء بدقة.',
		'keywords'            => array( 'JSON Formatter', 'JSON Validator', 'تنسيق JSON', 'فحص JSON', 'تجميل JSON' ),
		'related_tools'       => array( 'json-diff', 'json-to-code', 'json-schema-validator', 'jsonpath-tester' ),
		'acceptance_criteria' => array(
			'يتحقق من صحة التراكيب الهيكلية للـ JSON ويكتشف أي خطأ تركيبي بدقة مع توضيح رسالة الخطأ والسطر.',
			'يدعم التنسيق بمسافتين (2-space indentation) أو 4 مسافات، والضغط (Minification) بدون مسافات.',
			'يعمل بسرعة فائقة (أقل من 50ms) لحجم بيانات يصل إلى 5MB دون تجميد الواجهة.',
		),
		'examples'            => array(
			'{"name":"Code Elta6ur","version":"2.0.0","active":true}',
		),
		'faq'                 => array(
			array(
				'q' => 'كيف تعمل أداة فحص وتنسيق JSON؟',
				'a' => 'تقوم الأداة بتحليل النص المدخل باستخدام محرك معالجة JSON القياسي داخل المتصفح مع كشف الأخطاء ومواضعها فورياً.',
			),
			array(
				'q' => 'هل بياناتي محمية عند استخدام هذه الأداة؟',
				'a' => 'نعم تماماً، الأداة تعمل 100% داخل متصفحك (Client-side) ولا يتم إرسال أي نص إلى خوادم الموقع.',
			),
		),
	),

	// 2. JSON Diff
	'json-diff' => array(
		'id'                  => 'json-diff',
		'slug'                => 'json-diff',
		'name'                => 'JSON Diff',
		'name_en'             => 'JSON Diff',
		'name_ar'             => 'مقارنة ملفات JSON',
		'title'               => 'JSON Diff | مقارنة ملفين وكشف الفروقات بين كائنات JSON',
		'description'         => 'قارن بين ملفي JSON واكتشف المفاتيح والقيم المضافة أو المحذوفة أو المعدلة مع تلوين واضح للفروقات الدقيقة.',
		'category'            => 'json-data',
		'icon'                => 'git-compare',
		'badge'               => 'مقارنة',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'left' => 'json_raw', 'right' => 'json_raw' ),
		'output_schema'       => array( 'type' => 'object', 'format' => 'diff_tree' ),
		'dependencies'        => array( 'client' => 'native_diff', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'أداة مقارنة كود JSON (JSON Diff Tool) أونلاين | كود التطور',
		'seo_desc'            => 'اكتشف الفروقات بين ملفات JSON بسهولة. مقارنة دقيقة للمفاتيح والقيم مع تلوين الاختلافات.',
		'keywords'            => array( 'JSON Diff', 'مقارنة JSON', 'فرق كود JSON', 'JSON Compare' ),
		'related_tools'       => array( 'json-formatter', 'json-to-code', 'openapi-diff' ),
		'acceptance_criteria' => array(
			'يدعم المقارنة العميقة للكائنات والمصفوفات المتداخلة (Recursive Deep Diff).',
			'يميز بوضوح بين 3 حالات: المفاتيح المضافة (Added +)، المفاتيح المحذوفة (Removed -)، والقيم المعدلة (Modified ~).',
			'يعرض المسار الكامل (JSON Path) لكل قيمة متغيرة مع تلوين مرئي دلالي واضح.',
		),
		'examples'            => array(
			'Left: {"env":"staging","port":3000} | Right: {"env":"production","port":8080,"ssl":true}',
		),
		'faq'                 => array(
			array(
				'q' => 'هل تدعم الأداة الكائنات المتداخلة (Nested Objects)؟',
				'a' => 'نعم، يتم فحص التداخل التكراري واستخراج الفروقات على كافة مستويات العمق بدقة.',
			),
		),
	),

	// 3. JSON to Code Generator
	'json-to-code' => array(
		'id'                  => 'json-to-code',
		'slug'                => 'json-to-code',
		'name'                => 'JSON to Code Generator',
		'name_en'             => 'JSON to Code Generator',
		'name_ar'             => 'تحويل JSON إلى كود برمجي',
		'title'          => 'JSON to Code Generator | تحويل JSON إلى TypeScript, Python, PHP, C#',
		'description'    => 'حول أي نص أو كائن JSON إلى كلاسات ونماذج وأنواع بيانات Typescript Interfaces أو Python Classes أو PHP أو C# تلقائياً.',
		'category'       => 'json-data',
		'icon'           => 'code-square',
		'badge'          => 'توليد',
		'execution_mode' => 'client',
		'input_schema'   => array( 'type' => 'string', 'format' => 'json_raw' ),
		'output_schema'  => array( 'type' => 'string', 'format' => 'source_code' ),
		'dependencies'   => array( 'client' => 'ast_type_infer', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice' => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'      => 'تحويل JSON إلى كود برمجي (JSON to TypeScript, Python, PHP) | كود التطور',
		'seo_desc'       => 'أداة توليد نماذج البيانات والكلاسات من كود JSON إلى لغات TypeScript و Python و PHP و C# بدقة عالية.',
		'keywords'       => array( 'JSON to TypeScript', 'JSON to Python', 'تحويل JSON لكود', 'JSON to Classes' ),
		'related_tools'  => array( 'json-formatter', 'openapi-to-typescript', 'json-schema-validator' ),
		'acceptance_criteria' => array(
			'يستنتج أنواع البيانات بدقة: string, number, boolean, array, object, null.',
			'يولد كود سليم لـ 4 لغات: TypeScript Interfaces, Python Pydantic/Dataclasses, PHP Typed Classes, C# Models.',
			'يتعامل مع المصفوفات المتجانسة وغير المتجانسة والكائنات الفرعية المتداخلة تلقائياً.',
		),
		'examples'       => array( '{"id":1,"username":"john_doe","roles":["admin","editor"]}' ),
		'faq'            => array(
			array(
				'q' => 'ما هي اللغات المدعومة حالياً؟',
				'a' => 'تدعم الأداة توليد TypeScript Interfaces و Python Dataclasses و PHP Typed Classes و C# Models.',
			),
		),
	),

	// 4. JSON Schema Validator
	'json-schema-validator' => array(
		'id'                  => 'json-schema-validator',
		'slug'                => 'json-schema-validator',
		'name'                => 'JSON Schema Validator',
		'name_en'             => 'JSON Schema Validator',
		'name_ar'             => 'مدقق مخطط JSON Schema',
		'title'          => 'JSON Schema Validator | التحقق من مطابقة JSON لمخطط البيانات',
		'description'    => 'تحقق من صحة بيانات JSON ومطابقتها لمخطط JSON Schema مع تقرير فوري بالحقول الناقصة أو غير المتطابقة.',
		'category'       => 'json-data',
		'icon'           => 'shield-check',
		'badge'          => 'تدقيق',
		'execution_mode' => 'client',
		'input_schema'   => array( 'data' => 'json_raw', 'schema' => 'json_schema' ),
		'output_schema'  => array( 'valid' => 'boolean', 'errors' => 'array' ),
		'dependencies'   => array( 'client' => 'json_schema_engine', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice' => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'      => 'مدقق مخطط JSON Schema أونلاين | كود التطور',
		'seo_desc'       => 'تحقق من مطابقة بياناتك لمخطط JSON Schema بدقة وسرعة مع تقرير شامل بالمخالفات.',
		'keywords'       => array( 'JSON Schema Validator', 'تدقيق مخطط JSON', 'JSON Schema Check' ),
		'related_tools'  => array( 'json-formatter', 'openapi-validator', 'json-to-code' ),
		'acceptance_criteria' => array(
			'يتحقق من القيود الأساسية: type, required, properties, items, minimum, maximum, pattern.',
			'يقدم تقريراً مفصلاً بكل حقل مخالف مع بيان نوع الخطأ والمسار الدقيق في الكائن.',
		),
		'examples'       => array( 'Schema: {"type":"object","required":["id"]} | Data: {"id":101}' ),
		'faq'            => array(
			array(
				'q' => 'ما هي الفائدة من تدقيق JSON Schema؟',
				'a' => 'يضمن التوافق التام لواجهات برمجة التطبيقات APIs والتحقق من صحة الحقول المطلوبة والأنواع.',
			),
		),
	),

	// 5. JSONPath Tester
	'jsonpath-tester' => array(
		'id'                  => 'jsonpath-tester',
		'slug'                => 'jsonpath-tester',
		'name'                => 'JSONPath Tester',
		'name_en'             => 'JSONPath Tester',
		'name_ar'             => 'مختبر مسارات JSONPath',
		'title'          => 'JSONPath Tester | استعلام وتصفية بيانات JSON باستخدام JSONPath',
		'description'    => 'اختبر تعبيرات ومسارات JSONPath واستخرج البيانات والنتائج المطابقة فورياً.',
		'category'       => 'json-data',
		'icon'           => 'search',
		'badge'          => 'استعلام',
		'execution_mode' => 'client',
		'input_schema'   => array( 'json' => 'json_raw', 'query' => 'string' ),
		'output_schema'  => array( 'matches' => 'array', 'count' => 'integer' ),
		'dependencies'   => array( 'client' => 'jsonpath_evaluator', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice' => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'      => 'مختبر مسارات JSONPath أونلاين (JSONPath Evaluator) | كود التطور',
		'seo_desc'       => 'استعلم عن كائنات ومصفوفات JSON المعقدة باستخدام JSONPath مع معاينة لحظية للنتائج المستخرجة.',
		'keywords'       => array( 'JSONPath Tester', 'JSONPath Online', 'استعلام JSON', 'تصفية JSON' ),
		'related_tools'  => array( 'json-formatter', 'regex-tester', 'json-diff' ),
		'acceptance_criteria' => array(
			'يدعم تعبيرات الاستعلام القياسية: $ (root), . (dot-child), .. (recursive descent), * (wildcard), [] (subscript).',
			'يعرض قائمة العناصر المطابقة مع عدد النتائج أو رسالة تفيد بعدم وجود مطابقات.',
		),
		'examples'       => array( 'Path: $.store.books[*].author | JSON: {"store":{"books":[{"author":"Naguib"}]}}' ),
		'faq'            => array(
			array(
				'q' => 'ما هو JSONPath؟',
				'a' => 'هو تعبير استعلام يشبه XPath ولكن مخصص لهياكل بيانات JSON لاستخراج عناصر محددة بسهولة.',
			),
		),
	),

	// 6. YAML ↔ JSON Converter
	'yaml-json-converter' => array(
		'id'                  => 'yaml-json-converter',
		'slug'                => 'yaml-json-converter',
		'name'                => 'YAML ↔ JSON Converter',
		'name_en'             => 'YAML ↔ JSON Converter',
		'name_ar'             => 'محول YAML و JSON',
		'title'          => 'YAML ↔ JSON Converter | تحويل ثنائي الاتجاه بين YAML و JSON',
		'description'    => 'أداة سريعة لتحويل ملفات YAML إلى JSON والعكس مع الحفاظ على الترتيب والأنواع والمسافات البادئة بدقة.',
		'category'       => 'json-data',
		'icon'           => 'arrow-left-right',
		'badge'          => 'تحويل',
		'execution_mode' => 'client',
		'input_schema'   => array( 'type' => 'string', 'format' => 'yaml_or_json' ),
		'output_schema'  => array( 'type' => 'string', 'format' => 'converted_text' ),
		'dependencies'   => array( 'client' => 'yaml_parser', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice' => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'      => 'محول YAML إلى JSON والعكس أونلاين (YAML ↔ JSON) | كود التطور',
		'seo_desc'       => 'تحويل سريع ودقيق بين صيغ YAML و JSON مع إمكانية التبديل العكسي والنسخ الفوري.',
		'keywords'       => array( 'YAML to JSON', 'JSON to YAML', 'تحويل YAML', 'محول جيسون ليامل' ),
		'related_tools'  => array( 'json-formatter', 'docker-compose-generator', 'env-parser' ),
		'acceptance_criteria' => array(
			'يدعم التحويل ثنائي الاتجاه (YAML to JSON و JSON to YAML) بضغطة زر واحدة.',
			'يحافظ على أنواع البيانات: الأرقام، السلاسل النصية، القيم المنطقية، والقيم الفارغة بدقة.',
		),
		'examples'       => array( 'name: Code Elta6ur\nversion: 2.0.0\nenabled: true' ),
		'faq'            => array(
			array(
				'q' => 'هل الأداة تدعم ملفات التكوين الكبيرة؟',
				'a' => 'نعم، المحرك يعمل بالكامل داخل المتصفح ويمكنه معالجة ملفات التكوين والـ manifests الضخمة بسرعة فائقة.',
			),
		),
	),
);
