<?php
/**
 * Security & Auth Tools Definitions (v2.1 Final Gate Spec)
 *
 * @package CodeElta6urTheme
 * @subpackage Tools/Definitions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	// 11. JWT Inspector & Expiration Analyzer
	'jwt-inspector' => array(
		'id'                  => 'jwt-inspector',
		'slug'                => 'jwt-inspector',
		'name'                => 'JWT Inspector & Expiration Analyzer',
		'name_en'             => 'JWT Inspector & Expiration Analyzer',
		'name_ar'             => 'محلل ومفحص توكن JWT',
		'title'               => 'JWT Inspector | فك تشفير وفحص توكن JSON Web Token وحساب الصلاحية',
		'description'         => 'فك تشفير رأس (Header) وحمولة (Payload) توكن JWT فورياً، وفحص المطالبات (Claims) وحساب الوقت المتبقي لانتهاء الصلاحية. تنبيه: هذه الأداة مخصصة لفك التشفير والفحص (Decode & Inspect) وليست للتحقق من صحة التوقيع المشفر (Signature Verification) ما لم يتم تزويد المفتاح السري.',
		'category'            => 'security-auth',
		'icon'                => 'key',
		'badge'               => 'حماية',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'string', 'format' => 'jwt_token' ),
		'output_schema'       => array( 'header' => 'object', 'payload' => 'object', 'expired' => 'boolean', 'expires_in_seconds' => 'integer' ),
		'dependencies'        => array( 'client' => 'base64url_decoder', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. التوكن لا يغادر جهازك نهائياً لضمان أعلى درجات الأمان.',
		'seo_title'           => 'محلل توكن JWT أونلاين (JWT Debugger & Inspector) | كود التطور',
		'seo_desc'            => 'فك تشفير وفحص توكنات JWT أونلاين مجاناً وحساب موعد انتهاء الصلاحية بدقة وأمان تام داخل المتصفح.',
		'keywords'            => array( 'JWT Inspector', 'JWT Debugger', 'فك تشفير JWT', 'فحص توكن JWT' ),
		'related_tools'       => array( 'security-headers-checker', 'env-parser', 'http-headers-analyzer' ),
		'acceptance_criteria' => array(
			'يفك تشفير أجزاء التوكن الثلاثة (Header, Payload, Signature) عبر خوارزمية Base64URL القياسية في المتصفح.',
			'يحلل الحقول الزمنية القياسية (exp, iat, nbf) ويحولها لتواريخ مقروءة مع حساب العد التنازلي الدقيق لصلاحية التوكن.',
			'يوضح للمستخدم بشارة صريحة أن الفحص هو Decode/Inspect للمطالبات ولا يتحقق من التوقيع التشفيري إلا إذا تم توفير السر.',
		),
		'examples'            => array( 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6Ik1vaGFtbWFkIiwiaWF0IjoxNTE2MjM5MDIyLCJleHAiOjE5MTYyMzkwMjJ9.4pcPyMD09olUV_AlFnNp_xQ6uR9i8Y1vLz_vA_kQ2vA' ),
		'faq'                 => array(
			array(
				'q' => 'هل يتم حفظ أو إرسال التوكن إلى خادم كود التطور؟',
				'a' => 'مستحيل! يتم فك تشفير وقراءة التوكن محلياً 100% داخل المتصفح عبر خوارزميات Base64URL العادية.',
			),
			array(
				'q' => 'هل تثبت الأداة صحة التوقيع الرقمي للتوكن؟',
				'a' => 'الأداة تفحص بنية التوكن ومطالباته (Inspect & Decode)؛ للتحقق الرياضي من التوقيع يلزم توفير المفتاح السري الذي وقع التوكن به.',
			),
		),
	),

	// 12. HTTP Headers Analyzer
	'http-headers-analyzer' => array(
		'id'                  => 'http-headers-analyzer',
		'slug'                => 'http-headers-analyzer',
		'name'                => 'HTTP Headers Analyzer',
		'name_en'             => 'HTTP Headers Analyzer',
		'name_ar'             => 'محلل ترويسات HTTP',
		'title'               => 'HTTP Headers Analyzer | تحليل ترويسات الاستجابة وتقديم توصيات الأداء',
		'description'         => 'تحليل شامل لترويسات استجابة خادم الويب (HTTP Response Headers)، وفحص إعدادات التخزين المؤقت (Caching)، والضغط، وترويسات الأمان.',
		'category'            => 'security-auth',
		'icon'                => 'hdd-network',
		'badge'               => 'تحليل',
		'execution_mode'      => 'client', // Client mode for raw headers text; server mode for URL inspection
		'input_schema'        => array( 'type' => 'string', 'format' => 'raw_http_headers' ),
		'output_schema'       => array( 'parsed_headers' => 'object', 'caching' => 'object', 'recommendations' => 'array' ),
		'dependencies'        => array( 'client' => 'headers_parser', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل داخل المتصفح لتحليل الترويسات المدخلة. عند طلب فحص رابط مباشر يتم إرسال الطلب عبر سيرفر وسيط لتجاوز قيود CORS.',
		'seo_title'           => 'محلل ترويسات HTTP أونلاين (HTTP Headers Analyzer) | كود التطور',
		'seo_desc'            => 'حلل ترويسات خادم الويب واكتشف فرص تحسين الأداء وحماية الاستجابة عبر توصيات فورية ودقيقة.',
		'keywords'            => array( 'HTTP Headers Analyzer', 'تحليل ترويسات HTTP', 'فحص ترويسات الويب' ),
		'related_tools'       => array( 'security-headers-checker', 'csp-generator', 'curl-to-code' ),
		'acceptance_criteria' => array(
			'يحلل ترويسات Cache-Control و ETag و Expires و Last-Modified ويوضح مدة التخزين المؤقت بالدقائق أو الأيام.',
			'يحلل ترويسات الضغط Content-Encoding (gzip, br, deflate) وترويسات الخادم Server / X-Powered-By.',
			'يقدم توصيات محددة لتحسين الأداء وتقليل تسريب معلومات الخادم الحساسة.',
		),
		'examples'            => array( "HTTP/1.1 200 OK\nContent-Type: text/html; charset=UTF-8\nCache-Control: public, max-age=86400\nServer: nginx" ),
		'faq'                 => array(
			array(
				'q' => 'لماذا تعتبر ترويسات HTTP مهمة؟',
				'a' => 'تتحكم الترويسات في سلوك التخزين المؤقت، حماية المستخدمين، ضغط البيانات، وتأمين الاتصال بين المتصفح والخادم.',
			),
		),
	),

	// 13. Security Headers Checker
	'security-headers-checker' => array(
		'id'                  => 'security-headers-checker',
		'slug'                => 'security-headers-checker',
		'name'                => 'Security Headers Checker',
		'name_en'             => 'Security Headers Checker',
		'name_ar'             => 'مدقق ترويسات الأمان والحماية',
		'title'               => 'Security Headers Checker | فحص وتقييم ترويسات الأمان والحماية للمواقع',
		'description'         => 'فحص متقدم لترويسات الحماية الأساسية: HSTS, X-Frame-Options, X-Content-Type-Options, CSP, Referrer-Policy, و Permissions-Policy مع درجة تقييم أمني وتوصيات علاجية.',
		'category'            => 'security-auth',
		'icon'                => 'shield-lock',
		'badge'               => 'أمان',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'string', 'format' => 'security_headers' ),
		'output_schema'       => array( 'grade' => 'string', 'score' => 'integer', 'present' => 'array', 'missing' => 'array' ),
		'dependencies'        => array( 'client' => 'security_auditor', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل داخل المتصفح لتحليل الترويسات المدخلة وإعطاء درجة التقييم الأمني مباشرة.',
		'seo_title'           => 'فحص ترويسات الأمان للمواقع (Security Headers Checker) | كود التطور',
		'seo_desc'            => 'افحص ترويسات الحماية لموقعك واحصل على تقرير أمني شامل لترويسات HSTS و CSP و X-Frame-Options.',
		'keywords'            => array( 'Security Headers', 'فحص أمان المواقع', 'HSTS Checker', 'CSP Checker' ),
		'related_tools'       => array( 'csp-generator', 'http-headers-analyzer', 'jwt-inspector' ),
		'acceptance_criteria' => array(
			'يفحص بدقة الترويسات الست الأساسية: Strict-Transport-Security, Content-Security-Policy, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy.',
			'يمنح تقييماً من A+ إلى F بناءً على معايير OWASP القياسية مع تعليمات كود التكوين لإصلاح الترويسات المفقودة.',
		),
		'examples'            => array( "Strict-Transport-Security: max-age=31536000; includeSubDomains\nX-Content-Type-Options: nosniff\nX-Frame-Options: SAMEORIGIN" ),
		'faq'                 => array(
			array(
				'q' => 'ما هي أهم ترويسة أمان يجب توفرها في أي موقع؟',
				'a' => 'ترويسات HSTS لمنع هجمات خفض التشفير، وترويسة Content-Security-Policy (CSP) للحماية من هجمات XSS.',
			),
		),
	),

	// 14. Content Security Policy Generator
	'csp-generator' => array(
		'id'                  => 'csp-generator',
		'slug'                => 'csp-generator',
		'name'                => 'Content Security Policy Generator',
		'name_en'             => 'Content Security Policy Generator',
		'name_ar'             => 'مولد سياسة أمان المحتوى (CSP)',
		'title'               => 'CSP Generator | مولد ترويسة Content-Security-Policy التفاعلي للمواقع',
		'description'         => 'ابنِ ترويسة CSP قوية ومخصصة لموقعك خطوة بخطوة للحد من هجمات XSS وحقن البيانات، مع دعم لمصادر السكربتات، الأنماط، الصور، والوسائط المتعددة.',
		'category'            => 'security-auth',
		'icon'                => 'shield-shaded',
		'badge'               => 'توليد',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'object', 'format' => 'csp_directives_config' ),
		'output_schema'       => array( 'type' => 'string', 'format' => 'csp_header_value' ),
		'dependencies'        => array( 'client' => 'csp_builder', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'مولد سياسة أمان المحتوى أونلاين (CSP Generator) | كود التطور',
		'seo_desc'            => 'أنشئ ترويسة Content Security Policy آمنة ومخصصة لموقعك لحمايته من هجمات XSS وحقن الأكواد الخبيثة.',
		'keywords'            => array( 'CSP Generator', 'توليد CSP', 'Content Security Policy', 'حماية الموقع XSS' ),
		'related_tools'       => array( 'security-headers-checker', 'http-headers-analyzer', 'htaccess-to-nginx' ),
		'acceptance_criteria' => array(
			'يدعم التوجيهات القياسية: default-src, script-src, style-src, img-src, connect-src, font-src, frame-ancestors, object-src.',
			'يتيح تحديد الكلمات الخاصة (\'self\', \'none\', \'unsafe-inline\', \'unsafe-eval\') وإضافة نطاقات مخصصة.',
			'يولد الترويسة بصيغة صالحة للاستخدام في خوادم Apache (.htaccess) و Nginx و meta tags.',
		),
		'examples'            => array( 'default-src \'self\'; script-src \'self\' https://cdn.jsdelivr.net; img-src \'self\' data:' ),
		'faq'                 => array(
			array(
				'q' => 'كيف أطبق ترويسة CSP على موقعي؟',
				'a' => 'يمكنك إضافتها في ملف تكوين خادم الويب (مثل .htaccess أو Nginx) أو كترويسة HTTP في كود الباك إند.',
			),
		),
	),

	// 15. .env Parser & Converter
	'env-parser' => array(
		'id'                  => 'env-parser',
		'slug'                => 'env-parser',
		'name'                => '.env Parser & Converter',
		'name_en'             => '.env Parser & Converter',
		'name_ar'             => 'محلل ومحول ملفات .env',
		'title'               => '.env Parser & Converter | تحليل وتحويل متغيرات البيئة إلى JSON, YAML, Docker',
		'description'         => 'حلل ملفات متغيرات البيئة .env، تحقق من سلامة الصياغة، وحولها فورياً إلى صيغ JSON, YAML, أو Docker Compose Environment Variables بأمان تام.',
		'category'            => 'security-auth',
		'icon'                => 'file-earmark-lock',
		'badge'               => 'تحويل',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'string', 'format' => 'dotenv_raw' ),
		'output_schema'       => array( 'type' => 'string', 'format' => 'json_or_yaml_or_docker' ),
		'dependencies'        => array( 'client' => 'dotenv_lexer', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. أسرار ومتغيرات بيئة عملك لا تخرج من جهازك أبداً.',
		'seo_title'           => 'محلل ومحول ملفات .env أونلاين (.env Parser) | كود التطور',
		'seo_desc'            => 'حلل وحول ملفات .env إلى JSON و YAML و Docker مع الحفاظ الكامل على سرية وخصوصية مفاتيحك البرمجية.',
		'keywords'            => array( '.env Parser', '.env to JSON', 'تحويل ملفات env', 'Docker environment' ),
		'related_tools'       => array( 'docker-compose-generator', 'yaml-json-converter', 'jwt-inspector' ),
		'acceptance_criteria' => array(
			'يتعامل مع التعليقات (#)، والأسطر الفارغة، والاقتباسات الفردية والمزدوجة، والمسافات المحيطة بالقيم.',
			'يكتشف المفاتيح المكررة (Duplicate Keys) وينبه المستخدم بشأنها.',
			'يحول بدقة إلى: JSON Object, YAML Map, Docker Compose Environment block.',
		),
		'examples'            => array( "DB_HOST=127.0.0.1\nDB_PORT=3306\nDB_USER=root\nDB_PASS=\"s3cr3t#pass\"" ),
		'faq'                 => array(
			array(
				'q' => 'هل من الآمن وضع متغيرات بيئية حقيقية هنا؟',
				'a' => 'نعم، المعالجة تتم حصرياً داخل الذاكرة المحلية لمتصفحك ولا يتم إرسال أي نص عبر الإنترنت.',
			),
		),
	),
);
