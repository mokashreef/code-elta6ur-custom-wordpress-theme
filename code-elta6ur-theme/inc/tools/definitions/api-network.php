<?php
/**
 * API & Network Tools Definitions (v2.1 Final Gate Spec)
 *
 * @package CodeElta6urTheme
 * @subpackage Tools/Definitions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	// 7. cURL to Code Converter
	'curl-to-code' => array(
		'id'                  => 'curl-to-code',
		'slug'                => 'curl-to-code',
		'name'                => 'cURL to Code Converter',
		'name_en'             => 'cURL to Code Converter',
		'name_ar'             => 'تحويل cURL إلى كود برمجي',
		'title'               => 'cURL to Code Converter | تحويل أوامر cURL إلى JavaScript, Python, PHP, Go',
		'description'         => 'حول أي أمر cURL يتضمن Headers وبيانات POST/PUT إلى كود برمجي جاهز للنسخ في JavaScript (Fetch), Python (Requests), PHP (cURL), و Go.',
		'category'            => 'api-network',
		'icon'                => 'terminal',
		'badge'               => 'توليد',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'string', 'format' => 'curl_command' ),
		'output_schema'       => array( 'type' => 'string', 'format' => 'source_code' ),
		'dependencies'        => array( 'client' => 'curl_ast_parser', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'تحويل cURL إلى كود برمجي (cURL to Code Converter) | كود التطور',
		'seo_desc'            => 'حول أوامر cURL المعقدة إلى أكواد برمجية نظيفة بلغات Python و JavaScript و PHP و Go بضغطة زر واحدة.',
		'keywords'            => array( 'cURL to Python', 'cURL to JavaScript Fetch', 'تحويل cURL', 'cURL to PHP' ),
		'related_tools'       => array( 'http-headers-analyzer', 'openapi-validator', 'json-to-code' ),
		'acceptance_criteria' => array(
			'يستخرج الطريقة (HTTP Method: GET, POST, PUT, DELETE, PATCH) سواء كانت صريحة (-X) أو ضمنية (-d).',
			'يستخرج جميع الترويسات (-H / --header) ويعالج Bearer Tokens و Content-Type.',
			'يولد كوداً سليماً لـ 4 لغات: JavaScript (Fetch API), Python (Requests), PHP (cURL), Go (net/http).',
		),
		'examples'            => array( "curl -X POST https://api.example.com/v1/users -H 'Content-Type: application/json' -d '{\"name\":\"Mohammad\"}'" ),
		'faq'                 => array(
			array(
				'q' => 'هل تدعم الأداة الترويسات و Bearer Tokens؟',
				'a' => 'نعم، يتم استخراج جميع الترويسات وطريقة الطلب والبيانات المشفرة وتنسيقها بدقة في الكود المولد.',
			),
		),
	),

	// 8. OpenAPI / Swagger Validator
	'openapi-validator' => array(
		'id'                  => 'openapi-validator',
		'slug'                => 'openapi-validator',
		'name'                => 'OpenAPI / Swagger Validator',
		'name_en'             => 'OpenAPI / Swagger Validator',
		'name_ar'             => 'مدقق مواصفات OpenAPI و Swagger',
		'title'               => 'OpenAPI / Swagger Validator | فحص والتحقق من صحة مواصفات واجهات API',
		'description'         => 'تحقق من صحة ملفات OpenAPI 3.0 و Swagger 2.0 (بصيغتي JSON و YAML) واكتشف أخطاء المسارات، المعاملات، والنماذج الناقصة.',
		'category'            => 'api-network',
		'icon'                => 'file-earmark-check',
		'badge'               => 'فحص',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'string', 'format' => 'openapi_spec' ),
		'output_schema'       => array( 'valid' => 'boolean', 'errors' => 'array', 'warnings' => 'array' ),
		'dependencies'        => array( 'client' => 'openapi_schema_validator', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'مدقق مواصفات OpenAPI و Swagger أونلاين | كود التطور',
		'seo_desc'            => 'افحص واختبر صحة وثائق OpenAPI 3.0 / Swagger مع تقرير فوري بالأخطاء البنائية والنماذج المفقودة.',
		'keywords'            => array( 'OpenAPI Validator', 'Swagger Validator', 'فحص OpenAPI', 'تدقيق Swagger' ),
		'related_tools'       => array( 'openapi-diff', 'openapi-to-typescript', 'curl-to-code' ),
		'acceptance_criteria' => array(
			'يقوم بفحص حقيقي لهيكل OpenAPI وليس مجرد التحقق من صحة صياغة الـ JSON.',
			'يتحقق من وجود الحقول الأساسية: openapi/swagger, info.title, info.version, paths.',
			'يتحقق من صحة مراجع المكونات ($ref pointers) ووجود استجابات صالحة (responses) لكل مسار.',
		),
		'examples'            => array( '{"openapi":"3.0.0","info":{"title":"Sample API","version":"1.0.0"},"paths":{"/ping":{"get":{"responses":{"200":{"description":"OK"}}}}}}' ),
		'faq'                 => array(
			array(
				'q' => 'ما هي الإصدارات المدعومة؟',
				'a' => 'تدعم الأداة Swagger 2.0 و OpenAPI 3.0.x و OpenAPI 3.1.x بصيغتي JSON و YAML.',
			),
		),
	),

	// 9. OpenAPI → TypeScript Generator
	'openapi-to-typescript' => array(
		'id'                  => 'openapi-to-typescript',
		'slug'                => 'openapi-to-typescript',
		'name'                => 'OpenAPI → TypeScript Generator',
		'name_en'             => 'OpenAPI → TypeScript Generator',
		'name_ar'             => 'توليد أنواع TypeScript من OpenAPI',
		'title'               => 'OpenAPI → TypeScript Generator | توليد نماذج وواجهات TypeScript من وثائق API',
		'description'         => 'حول ملفات مواصفات OpenAPI 3.0 / Swagger إلى أنواع وواجهات برمجية TypeScript Interfaces & Types مع الحفاظ الكامل على دقة الأنواع.',
		'category'            => 'api-network',
		'icon'                => 'filetype-tsx',
		'badge'               => 'توليد',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'string', 'format' => 'openapi_spec' ),
		'output_schema'       => array( 'type' => 'string', 'format' => 'typescript_definitions' ),
		'dependencies'        => array( 'client' => 'openapi_ts_generator', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'توليد واجهات TypeScript من OpenAPI أونلاين | كود التطور',
		'seo_desc'            => 'ولد كود Typescript متوافق وآمن الأنواع (Type-Safe) مباشرة من مواصفات الـ API الخاصة بك مجاناً.',
		'keywords'            => array( 'OpenAPI to TypeScript', 'Swagger to TypeScript', 'توليد أنواع تايب سكريبت' ),
		'related_tools'       => array( 'openapi-validator', 'json-to-code', 'openapi-diff' ),
		'acceptance_criteria' => array(
			'يولد interfaces و types متوافقة مع TypeScript 5.0 لجميع الـ schemas المعرفة في components/definitions.',
			'يحدد الحقول الإجبارية (required) والاختيارية (optional ?) بدقة مطابقة لمواصفات الوثيقة.',
		),
		'examples'            => array( '{"components":{"schemas":{"User":{"type":"object","required":["id","email"],"properties":{"id":{"type":"integer"},"email":{"type":"string"}}}}}}' ),
		'faq'                 => array(
			array(
				'q' => 'هل يتم توليد الأنواع لجميع المسارات والردود؟',
				'a' => 'تقوم الأداة بتوليد Interfaces لكافة مكونات الـ schemas والـ requests والـ responses المحددة في الوثيقة.',
			),
		),
	),

	// 10. OpenAPI Diff
	'openapi-diff' => array(
		'id'                  => 'openapi-diff',
		'slug'                => 'openapi-diff',
		'name'                => 'OpenAPI Diff',
		'name_en'             => 'OpenAPI Diff',
		'name_ar'             => 'مقارنة إصدارات OpenAPI',
		'title'               => 'OpenAPI Diff | مقارنة نسختين واكتشاف التغييرات الجذرية في واجهات API',
		'description'         => 'قارن بين نسختين من مواصفات OpenAPI واكتشف التغييرات الجذرية (Breaking Changes) والإضافات والتعديلات على الـ Endpoints بسهولة.',
		'category'            => 'api-network',
		'icon'                => 'intersect',
		'badge'               => 'مقارنة',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'old_spec' => 'openapi_spec', 'new_spec' => 'openapi_spec' ),
		'output_schema'       => array( 'breaking_changes' => 'array', 'additions' => 'array', 'modifications' => 'array' ),
		'dependencies'        => array( 'client' => 'openapi_diff_engine', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'مقارنة إصدارات OpenAPI وكشف التغييرات (OpenAPI Diff) | كود التطور',
		'seo_desc'            => 'أداة مقارنة مواصفات OpenAPI لكشف التغييرات الجذرية والتحديثات بين إصدارات الـ API الخاصة بك.',
		'keywords'            => array( 'OpenAPI Diff', 'Swagger Diff', 'مقارنة API', 'Breaking Changes API' ),
		'related_tools'       => array( 'openapi-validator', 'json-diff', 'openapi-to-typescript' ),
		'acceptance_criteria' => array(
			'يكتشف التغييرات الجذرية (Breaking Changes): مثل حذف مسار، حذف خاصية، إضافة خاصية إجبارية جديدة، أو تعديل نوع حقل.',
			'يصنف التغييرات بوضوح إلى: تغييرات كاسرة (Breaking ⚠️)، إضافات غير كاسرة (Added +)، وتعديلات طفيفة (Modified ~).',
		),
		'examples'            => array( 'Old: {"paths":{"/users":{"get":{}}}} | New: {"paths":{"/users":{"get":{},"post":{}}}}' ),
		'faq'            => array(
			array(
				'q' => 'ما المقصود بالتغييرات الجذرية Breaking Changes؟',
				'a' => 'هي التعديلات التي قد تتسبب في تعطل التطبيقات الحالية، مثل إزالة مسار أو جعل حقل اختياري إجبارياً أو تغيير نوع الحقل.',
			),
		),
	),
);
