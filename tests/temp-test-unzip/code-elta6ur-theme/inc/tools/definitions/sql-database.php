<?php
/**
 * SQL & Database Tools Definitions (v2.1 Final Gate Spec)
 *
 * @package CodeElta6urTheme
 * @subpackage Tools/Definitions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	// 18. SQL Formatter & Validator
	'sql-formatter' => array(
		'id'                  => 'sql-formatter',
		'slug'                => 'sql-formatter',
		'name'                => 'SQL Formatter & Validator',
		'name_en'             => 'SQL Formatter & Validator',
		'name_ar'             => 'منسق ومدقق استعلامات SQL',
		'title'               => 'SQL Formatter & Validator | تنسيق وتجميل استعلامات قواعد البيانات SQL',
		'description'         => 'قم بتنسيق وتجميل استعلامات SQL المعقدة، ترتيب الكلمات المفتاحية بأحرف كبيرة (UPPERCASE)، وضبط المسافات البادئة لأنظمة MySQL, PostgreSQL, SQLite, و SQL Server.',
		'category'            => 'sql-database',
		'icon'                => 'database-fill-gear',
		'badge'               => 'تنسيق',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'string', 'format' => 'sql_query_raw' ),
		'output_schema'       => array( 'type' => 'string', 'format' => 'sql_formatted' ),
		'dependencies'        => array( 'client' => 'sql_tokenizer_formatter', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. استعلامات قواعد بياناتك لا تغادر جهازك أبداً.',
		'seo_title'           => 'منسق ومدقق استعلامات SQL أونلاين (SQL Formatter) | كود التطور',
		'seo_desc'            => 'نسق وجمل استعلامات SQL بسهولة مع تكبير الكلمات المحجوزة وترتيب الشروط والجمل المعقدة.',
		'keywords'            => array( 'SQL Formatter', 'تنسيق SQL', 'تجميل استعلام SQL', 'SQL Validator' ),
		'related_tools'       => array( 'sql-to-er-diagram', 'db-schema-generator', 'json-to-code' ),
		'acceptance_criteria' => array(
			'يقدم تنسيقاً هيكلياً حقيقياً وليس مجرد استبدال مسافات (Real SQL Tokenization).',
			'يفصل الجمل الأساسية في أسطر مستقلة مع مسافات بادئة دقيقة: SELECT, FROM, WHERE, JOIN, LEFT JOIN, INNER JOIN, GROUP BY, HAVING, ORDER BY, LIMIT.',
			'يحول الكلمات المفتاحية تلقائياً إلى أحرف كبيرة (UPPERCASE) مع الحفاظ على أسماء الجداول والحقول كما هي.',
		),
		'examples'            => array( "select u.id, u.name, count(o.id) as total_orders from users u left join orders o on u.id = o.user_id where u.status = 'active' group by u.id, u.name order by total_orders desc limit 10;" ),
		'faq'                 => array(
			array(
				'q' => 'ما هي قواعد البيانات المدعومة؟',
				'a' => 'يدعم التنسيق القياسي المتوافق مع MySQL, PostgreSQL, MariaDB, SQLite, و MS SQL Server.',
			),
		),
	),

	// 19. SQL → ER Diagram
	'sql-to-er-diagram' => array(
		'id'                  => 'sql-to-er-diagram',
		'slug'                => 'sql-to-er-diagram',
		'name'                => 'SQL → ER Diagram',
		'name_en'             => 'SQL → ER Diagram',
		'name_ar'             => 'تحويل كود SQL إلى مخطط علاقات ER Diagram',
		'title'               => 'SQL to ER Diagram | تحويل استعلامات إنشاء الجداول DDL إلى رسم بياني للعلاقات',
		'description'         => 'ألصق أوامر CREATE TABLE واستخرج مخطط الكيانات والعلاقات (Entity-Relationship Diagram) تلقائياً مع توضيح المفاتيح الأساسية والخارجية والعلاقات بين الجداول.',
		'category'            => 'sql-database',
		'icon'                => 'diagram-3',
		'badge'               => 'مخطط',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'string', 'format' => 'sql_ddl' ),
		'output_schema'       => array( 'tables' => 'array', 'relationships' => 'array', 'diagram_svg' => 'string' ),
		'dependencies'        => array( 'client' => 'sql_ddl_parser', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'تحويل كود SQL إلى مخطط علاقات (SQL to ER Diagram) | كود التطور',
		'seo_desc'            => 'حول أوامر DDL إلى مخطط ERD بياني احترافي يوضح الجداول والعلاقات والمفاتيح بضغطة زر واحدة.',
		'keywords'            => array( 'SQL to ER Diagram', 'مخطط علاقات SQL', 'رسم جداول SQL', 'ERD Generator' ),
		'related_tools'       => array( 'db-schema-generator', 'sql-formatter', 'docker-compose-generator' ),
		'acceptance_criteria' => array(
			'يستخرج الجداول والحقول وأنواع البيانات والمفاتيح الأساسية (PRIMARY KEY) بدقة من أوامر CREATE TABLE.',
			'يستخرج العلاقات الحقيقية بين الجداول من خلال قراءة قيود المفاتيح الخارجية (FOREIGN KEY ... REFERENCES other_table).',
			'يرسم أو يولد مخطط علاقات بياني تفاعلي يوضح اتجاهات العلاقات (1:N, N:M) بين الكيانات.',
		),
		'examples'            => array( "CREATE TABLE users ( id INT PRIMARY KEY, name VARCHAR(100) );\nCREATE TABLE posts ( id INT PRIMARY KEY, user_id INT, title VARCHAR(200), FOREIGN KEY (user_id) REFERENCES users(id) );" ),
		'faq'                 => array(
			array(
				'q' => 'هل تدعم الأداة المفاتيح الخارجية Foreign Keys؟',
				'a' => 'نعم، يتم اكتشاف جمل REFERENCES ورسم خطوط العلاقات والروابط بين الجداول بدقة.',
			),
		),
	),

	// 20. Database Schema / SQL Generator
	'db-schema-generator' => array(
		'id'                  => 'db-schema-generator',
		'slug'                => 'db-schema-generator',
		'name'                => 'Database Schema / SQL Generator',
		'name_en'             => 'Database Schema / SQL Generator',
		'name_ar'             => 'مولد مخطط وجداول قواعد البيانات SQL',
		'title'               => 'Database Schema Generator | مولد أوامر إنشاء الجداول DDL لقواعد البيانات',
		'description'         => 'صمم جداول قاعدة بياناتك تفاعلياً، حدد أسماء الحقول، أنواع البيانات، القيود، والمفاتيح، وقم بتوليد كود CREATE TABLE جاهز لـ MySQL أو PostgreSQL أو SQLite.',
		'category'            => 'sql-database',
		'icon'                => 'table',
		'badge'               => 'توليد',
		'execution_mode'      => 'client',
		'input_schema'        => array( 'type' => 'object', 'format' => 'schema_definition_json' ),
		'output_schema'       => array( 'type' => 'string', 'format' => 'sql_ddl' ),
		'dependencies'        => array( 'client' => 'sql_schema_emitter', 'size' => '0KB', 'loading' => 'builtin', 'license' => 'MIT' ),
		'privacy_notice'      => 'يعمل بالكامل داخل المتصفح. لا يتم إرسال أي بيانات إلى الخادم.',
		'seo_title'           => 'مولد مخطط وجداول قواعد البيانات (SQL Schema Generator) | كود التطور',
		'seo_desc'            => 'أنشئ جداول SQL احترافية بسهولة عبر واجهة تفاعلية مع توليد كود DDL سليم ونظيف.',
		'keywords'            => array( 'Database Schema Generator', 'توليد جداول SQL', 'SQL DDL Generator' ),
		'related_tools'       => array( 'sql-to-er-diagram', 'sql-formatter', 'package-json-generator' ),
		'acceptance_criteria' => array(
			'يولد كود DDL سليم نحوياً لأنظمة MySQL و PostgreSQL و SQLite.',
			'يدعم تحديد الحقول: Primary Key, Auto Increment, Not Null, Unique, Default Value, Foreign Key.',
		),
		'examples'            => array( 'Table: products (id: int pk, sku: varchar(50) unique, price: decimal(10,2) not null, created_at: timestamp)' ),
		'faq'                 => array(
			array(
				'q' => 'هل يمكن تصدير المخطط إلى ملف .sql؟',
				'a' => 'نعم، توفر الأداة زر تحميل مباشر لملف SQL جاهز للاستيراد في phpMyAdmin أو DBeaver.',
			),
		),
	),
);
