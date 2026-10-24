<?php
/**
 * DevOps & Git Tools Definitions (v2.1 Final Gate Spec)
 *
 * @package CodeElta6urTheme
 * @subpackage Tools/Definitions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	// 21. Docker Compose Generator
	'docker-compose-generator' => array(
		'id'                  => 'docker-compose-generator',
		'slug'                => 'docker-compose-generator',
		'name'                => 'Docker Compose Generator',
		'name_en'             => 'Docker Compose Generator',
		'name_ar'             => 'مولد ملفات Docker Compose',
		'title'               => 'Docker Compose Generator | بناء ملفات docker-compose.yml تفاعلياً',
		'description'         => 'اختر الخدمات التي تحتاجها (Node, Python, PHP, Nginx, MySQL, PostgreSQL, Redis) وولد ملف docker-compose.yml متكامل بالمنافذ والأحجام والشبكات.',
		'category'            => 'devops-git',
		'icon'                => 'boxes',
		'badge'               => 'شائع',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'object', 'format' => 'services_selection_config' ),
		'output_schema'       => array( 'type' => 'string', 'format' => 'yaml_docker_compose' ),
		'dependencies'        => array( 'client' => 'compose_spec_builder', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'مولد ملفات Docker Compose أونلاين (Docker Compose Generator) | كود التطور',
		'seo_desc'            => 'أنشئ ملفات docker-compose.yml احترافية لمشاريع الويب وقواعد البيانات بضغطة زر واحدة.',
		'keywords'            => array( 'Docker Compose Generator', 'توليد docker-compose', 'ملفات دوكر', 'Docker YAML' ),
		'related_tools'       => array( 'dockerfile-generator', 'env-parser', 'yaml-json-converter' ),
		'acceptance_criteria' => array(
			'يولد ملف YAML متوافق مع معيار Compose Specification الحديث.',
			'يضبط أسماء الحاويات، الصور، المنافذ (ports)، الأحجام (volumes)، الشبكات (networks)، وبيئة التشغيل (environment) بشكل صحيح.',
		),
		'examples'            => array( 'Services: Node.js (Port 3000) + Redis (Port 6379)' ),
		'faq'                 => array(
			array(
				'q' => 'هل الملف المولد متوافق مع Docker Compose v2؟',
				'a' => 'نعم، التنسيق يعتمد مواصفات Compose Specification الحديثة الخالية من وسوم الإصدارات القديمة.',
			),
		),
	),

	// 22. Dockerfile Generator
	'dockerfile-generator' => array(
		'id'                  => 'dockerfile-generator',
		'slug'                => 'dockerfile-generator',
		'name'                => 'Dockerfile Generator',
		'name_en'             => 'Dockerfile Generator',
		'name_ar'             => 'مولد ملفات Dockerfile',
		'title'               => 'Dockerfile Generator | بناء ملفات Dockerfile مخصصة لمختلف لغات البرمجة',
		'description'         => 'ولد Dockerfile أمثل للإنتاج ومبني وفق أفضل ممارسات الحاويات متعددة المراحل (Multi-stage builds) لتطبيقات Node.js, Python, Go, PHP, و Rust.',
		'category'            => 'devops-git',
		'icon'                => 'box-seam',
		'badge'               => 'توليد',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'object', 'format' => 'dockerfile_options' ),
		'output_schema'       => array( 'type' => 'string', 'format' => 'dockerfile_text' ),
		'dependencies'        => array( 'client' => 'dockerfile_templates', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'مولد ملفات Dockerfile أونلاين (Dockerfile Generator) | كود التطور',
		'seo_desc'            => 'ابنِ ملفات Dockerfile سريعة وخفيفة وآمنة لتطبيقاتك البرمجية مع دعم التخزين المؤقت والبناء متعدد المراحل.',
		'keywords'            => array( 'Dockerfile Generator', 'توليد Dockerfile', 'إنشاء ملف دوكر', 'Docker build' ),
		'related_tools'       => array( 'docker-compose-generator', 'package-json-generator', 'htaccess-to-nginx' ),
		'acceptance_criteria' => array(
			'يولد Dockerfile يعتمد البناء متعدد المراحل (Multi-stage build) لتقليل حجم الصورة النهائية إلى الحد الأدنى.',
			'يضمن أفضل ممارسات الأمان: مستخدم غير الرووت (USER node/appuser)، أمر CMD/ENTRYPOINT سليم، وتثبيت التبعيات في طبقة معزولة.',
		),
		'examples'            => array( 'Stack: Node.js 20 LTS Alpine, Production Stage, Non-root' ),
		'faq'                 => array(
			array(
				'q' => 'هل تدعم الأداة التشغيل بمستخدم غير الرووت (Non-root user)؟',
				'a' => 'نعم، يتم تضمين إرشادات الأمان القياسية لتقليل صلاحيات الحاوية أثناء الإنتاج.',
			),
		),
	),

	// 23. Git Command Generator
	'git-command-generator' => array(
		'id'                  => 'git-command-generator',
		'slug'                => 'git-command-generator',
		'name'                => 'Git Command Generator',
		'name_en'             => 'Git Command Generator',
		'name_ar'             => 'مولد أوامر Git التفاعلي',
		'title'               => 'Git Command Generator | دليل ومولد أوامر Git للمهام والعمليات الشائعة',
		'description'         => 'اختر ما تريد تحقيقه في Git (إلغاء Commit، تعديل آخر رسالة، دمج فروع، التعامل مع التخزين المؤقت Stash، أو حل التعارضات) واحصل على الأمر الصحيح فورياً.',
		'category'            => 'devops-git',
		'icon'                => 'git',
		'badge'               => 'مساعد',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'object', 'format' => 'git_task_selection' ),
		'output_schema'       => array( 'command' => 'string', 'explanation' => 'string', 'is_destructive' => 'boolean' ),
		'dependencies'        => array( 'client' => 'git_kb_engine', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'مولد ومساعد أوامر Git أونلاين (Git Command Generator) | كود التطور',
		'seo_desc'            => 'ابحث عن أوامر Git وحلول المشاكل الشائعة في الفروع والـ Commits واحصل على الأوامر الدقيقة فورياً.',
		'keywords'            => array( 'Git Command Generator', 'أوامر Git', 'شرح Git', 'حل مشاكل Git' ),
		'related_tools'       => array( 'gitignore-generator', 'semver-calculator', 'curl-to-code' ),
		'acceptance_criteria' => array(
			'يغطي 30 سيناريو Git شائعاً: Undo Commit, Rename Branch, Cherry-pick, Stash, Rebase, Squashing, Clean Untracked.',
			'يصنف الأوامر بوضوح مع وضع تحذير بارز للأوامر التدميرية (Destructive Commands مثل reset --hard).',
		),
		'examples'            => array( 'Scenario: Undo last commit without losing changes -> git reset --soft HEAD~1' ),
		'faq'                 => array(
			array(
				'q' => 'هل الأوامر آمنة ولا تؤدي لفقدان الكود؟',
				'a' => 'توضح الأداة الأوامر الآمنة والأوامر التي تتضمن إتلافاً أو تعديلاً لسجل الفروع مع تحذيرات واضحة.',
			),
		),
	),

	// 24. .gitignore Generator
	'gitignore-generator' => array(
		'id'                  => 'gitignore-generator',
		'slug'                => 'gitignore-generator',
		'name'                => '.gitignore Generator',
		'name_en'             => '.gitignore Generator',
		'name_ar'             => 'مولد ملفات .gitignore',
		'title'               => '.gitignore Generator | توليد ملفات التجاهل للمشاريع وبيئات العمل',
		'description'         => 'اختر لغات البرمجة وأطر العمل وبيئات التطوير الخاصة بمشروعك (مثل Node.js, Python, WordPress, VSCode, macOS) وولد ملف .gitignore شامل ونظيف.',
		'category'            => 'devops-git',
		'icon'                => 'slash-circle',
		'badge'               => 'أساسي',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'array', 'items' => 'string' ),
		'output_schema'       => array( 'type' => 'string', 'format' => 'gitignore_text' ),
		'dependencies'        => array( 'client' => 'gitignore_templates_db', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'مولد ملفات .gitignore أونلاين مجاني (.gitignore Generator) | كود التطور',
		'seo_desc'            => 'ولد ملف .gitignore متكامل لمشروعك البرمجي لحماية المستودع من رفع الملفات المؤقتة وأسرار البيئة.',
		'keywords'            => array( '.gitignore Generator', 'توليد gitignore', 'ملف تجاهل git', 'Git ignore templates' ),
		'related_tools'       => array( 'git-command-generator', 'package-json-generator', 'env-parser' ),
		'acceptance_criteria' => array(
			'يدعم دمج قوالب بيئات العمل المتعددة في ملف واحد منظم بأقسام واضحة (Node.js + Python + macOS + VSCode).',
			'يزيل التكرارات التلقائية ويستبعد ملفات النظام المؤقتة ومجلدات الحزم (.env, node_modules, __pycache__).',
		),
		'examples'            => array( 'Selected: Node.js, WordPress, macOS, Windows' ),
		'faq'                 => array(
			array(
				'q' => 'هل يشمل القوالب ملفات أنظمة التشغيل؟',
				'a' => 'نعم، يتم استبعاد ملفات النظام غير المرغوب فيها مثل .DS_Store في Mac و Thumbs.db في Windows تلقائياً.',
			),
		),
	),

	// 25. Package.json Generator
	'package-json-generator' => array(
		'id'                  => 'package-json-generator',
		'slug'                => 'package-json-generator',
		'name'                => 'Package.json Generator',
		'name_en'             => 'Package.json Generator',
		'name_ar'             => 'مولد ملف package.json',
		'title'               => 'Package.json Generator | إنشاء ملفات تكوين حزم Node.js تفاعلياً',
		'description'         => 'أنشئ ملف package.json لمشاريع Node.js و npm/yarn/pnpm، حدد السكربتات (build, dev, test, start)، التراخيص، الحزم الأساسية، ونوع الوحدة (ESM / CommonJS).',
		'category'            => 'devops-git',
		'icon'                => 'filetype-json',
		'badge'               => 'توليد',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'object', 'format' => 'package_fields_config' ),
		'output_schema'       => array( 'type' => 'string', 'format' => 'json_package' ),
		'dependencies'        => array( 'client' => 'npm_pkg_builder', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'مولد ملف package.json أونلاين (Package.json Generator) | كود التطور',
		'seo_desc'            => 'أنشئ ملف package.json لمشروعك البرمجي مع إعداد السكربتات والتبعيات والتراخيص بسهولة وسرعة.',
		'keywords'            => array( 'package.json Generator', 'توليد package.json', 'Node.js config', 'npm package.json' ),
		'related_tools'       => array( 'semver-calculator', 'gitignore-generator', 'dockerfile-generator' ),
		'acceptance_criteria' => array(
			'يولد كود JSON سليم مع الحقول الإلزامية لـ npm: name, version, description, main, scripts, author, license.',
			'يتيح التبديل السهل بين "type": "module" و "type": "commonjs".',
		),
		'examples'            => array( 'Package: @elta6ur/tools-core, Version: 1.0.0, License: MIT' ),
		'faq'                 => array(
			array(
				'q' => 'هل تدعم الأداة "type": "module"؟',
				'a' => 'نعم، يمكنك التبديل بين نظام وحدات ES Modules أو CommonJS بضغطة زر واحدة.',
			),
		),
	),

	// 26. Semantic Version Calculator
	'semver-calculator' => array(
		'id'                  => 'semver-calculator',
		'slug'                => 'semver-calculator',
		'name'                => 'Semantic Version Calculator',
		'name_en'             => 'Semantic Version Calculator',
		'name_ar'             => 'حاسبة الترقيم الدلالي SemVer',
		'title'               => 'Semantic Version Calculator | حساب وفحص إصدارات البرمجيات (Major.Minor.Patch)',
		'description'         => 'احسب الإصدار القادم وفق معيار SemVer 2.0.0 عند إضافة ميزات جديدة أو إصلاح أخطاء أو كسر التوافقية، مع دعم وسوم ما قبل الإطلاق (Alpha, Beta, RC).',
		'category'            => 'devops-git',
		'icon'                => '123',
		'badge'               => 'حساب',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'current_version' => 'string', 'change_type' => 'string', 'prerelease' => 'string' ),
		'output_schema'       => array( 'next_version' => 'string', 'diff_summary' => 'string' ),
		'dependencies'        => array( 'client' => 'semver_spec_calculator', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'حاسبة الترقيم الدلالي SemVer أونلاين (Semantic Version Calculator) | كود التطور',
		'seo_desc'            => 'احسب رقم الإصدار البرمجي التالي لمشروعك بدقة وفق معايير Semantic Versioning الرسمية.',
		'keywords'            => array( 'SemVer Calculator', 'الترقيم الدلالي', 'حساب رقم الإصدار', 'Semantic Versioning' ),
		'related_tools'       => array( 'package-json-generator', 'git-command-generator', 'regex-tester' ),
		'acceptance_criteria' => array(
			'يتحقق من صحة صياغة الإصدار الحالي وفق مواصفات SemVer 2.0.0 الرسمية.',
			'يحسب الإصدار التالي بدقة لجميع أنواع الترقية: Major (Breaking), Minor (Feature), Patch (Fix), Pre-major, Pre-minor, Pre-patch.',
		),
		'examples'            => array( 'Current: 1.4.2 -> Major: 2.0.0 | Minor: 1.5.0 | Patch: 1.4.3' ),
		'faq'                 => array(
			array(
				'q' => 'متى يجب ترقية رقم الـ Major؟',
				'a' => 'عند إجراء تغييرات غير متوافقة عكسياً (Breaking Changes) تتطلب تعديل كود المستخدمين الحاليين.',
			),
		),
	),

	// 27. .htaccess ↔ Nginx Config Converter
	'htaccess-to-nginx' => array(
		'id'                  => 'htaccess-to-nginx',
		'slug'                => 'htaccess-to-nginx',
		'name'                => '.htaccess ↔ Nginx Config Converter',
		'name_en'             => '.htaccess ↔ Nginx Config Converter',
		'name_ar'             => 'محول قواعد .htaccess إلى Nginx',
		'title'               => '.htaccess to Nginx Converter | تحويل قواعد Apache إلى إعدادات خادم Nginx',
		'description'         => 'حول قواعد إعادة التوجيه RewriteRule، التخزين المؤقت، حظر الملفات، ورؤوس الأمان من صياغة Apache .htaccess إلى صياغة كتل Nginx server {} المقابلة.',
		'category'            => 'devops-git',
		'icon'                => 'arrow-repeat',
		'badge'               => 'تحويل',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'string', 'format' => 'htaccess_rules' ),
		'output_schema'       => array( 'type' => 'string', 'format' => 'nginx_conf' ),
		'dependencies'        => array( 'client' => 'webserver_rule_translator', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'تحويل كود .htaccess إلى Nginx أونلاين (.htaccess to Nginx) | كود التطور',
		'seo_desc'            => 'أداة تحويل قواعد وقوانين ملفات .htaccess إلى إعدادات خادم Nginx بدقة وسرعة عالية مجاناً.',
		'keywords'            => array( '.htaccess to Nginx', 'تحويل htaccess إلى nginx', 'RewriteRule Nginx', 'Nginx config' ),
		'related_tools'       => array( 'csp-generator', 'security-headers-checker', 'dockerfile-generator' ),
		'acceptance_criteria' => array(
			'يترجم قواعد RewriteRule و RewriteCond وأعلام التوجيه [R=301,L] إلى كتل rewrite أو return في Nginx.',
			'يترجم قواعد حظر الوصول إلى الملفات الحساسة (FilesMatch) إلى كتل location ~ /\\.(?!well-known) { deny all; }.',
			'يترجم قواعد إعادة توجيه ووردبريس الأساسية إلى try_files $uri $uri/ /index.php?$args; القياسية.',
		),
		'examples'            => array( "RewriteEngine On\nRewriteRule ^old-page/?$ /new-page/ [R=301,L]" ),
		'faq'                 => array(
			array(
				'q' => 'هل الأداة تدعم تحويل قواعد ووردبريس الأساسية؟',
				'a' => 'نعم، تحول قواعد ووردبريس لإعادة التوجيه (index.php?$args) إلى كتل try_files القياسية في Nginx.',
			),
		),
	),
);
