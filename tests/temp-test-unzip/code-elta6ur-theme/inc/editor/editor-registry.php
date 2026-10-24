<?php
/**
 * سجل وتعريفات محرر الأكواد واللغات البرمجية الـ 15
 * Code Elta6ur Online Code Editor Languages Registry
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/runtime-config.php';

/**
 * Returns the complete registry of all 15 supported languages
 *
 * @return array
 */
function cdt_get_editor_languages() {
	static $languages = null;
	if ( null !== $languages ) {
		return $languages;
	}

	$runtimes = cdt_get_editor_runtimes();

	$raw_languages = array(
		// 1. Python
		'python' => array(
			'slug'              => 'python',
			'name'              => 'Python',
			'name_ar'           => 'بايثون',
			'title'             => 'Python Online Compiler & Code Editor | محرر ومترجم بايثون أونلاين',
			'tagline'           => 'محرر ومترجم بايثون أونلاين لتشغيل واختبار سكربتات Python 3 في الوقت الفعلي.',
			'description'       => 'بيئة برمجية متكاملة لكتابة وتشغيل كود بايثون (Python 3.10+) مباشرة من المتصفح مع معالجة المدخلات وطباعة المخرجات واكتشاف أخطاء التنفيذ والترجمة بأمان وعزل تام.',
			'version'           => '3.10+',
			'runtime'           => 'CPython 3.10 Sandbox',
			'piston_lang'       => 'python',
			'piston_version'    => '3.10.0',
			'monaco_lang'       => 'python',
			'extension'         => 'py',
			'file_name'         => 'main.py',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "# كود التطور - تجربة لغة بايثون\ndef fibonacci(n):\n    sequence = [0, 1]\n    while len(sequence) < n:\n        sequence.append(sequence[-1] + sequence[-2])\n    return sequence[:n]\n\nprint(\"مرحباً بك في محرر كود التطور!\")\nprint(\"متتالية فيبوناتشي لأول 8 أرقام:\", fibonacci(8))\n",
			'seo_title'         => 'محرر ومترجم بايثون أونلاين (Python Online Compiler) | كود التطور',
			'seo_desc'          => 'اكتب وشغّل كود بايثون Python 3 أونلاين في المتصفح مجاناً مع دعم المدخلات واكتشاف الأخطاء بدقة.',
			'keywords'          => array( 'Python Online Compiler', 'محرر بايثون', 'تشغيل بايثون أونلاين', 'Python IDE' ),
			'related_languages' => array( 'javascript', 'typescript', 'go', 'rust' ),
			'faq'               => array(
				array(
					'q' => 'هل يدعم المحرر استيراد مكتبات بايثون القياسية؟',
					'a' => 'نعم، يمكنك استيراد مكتبات بايثون القياسية المدمجة مثل math, json, random, datetime, sys وغيرها.',
				),
			),
		),

		// 2. JavaScript
		'javascript' => array(
			'slug'              => 'javascript',
			'name'              => 'JavaScript',
			'name_ar'           => 'جافاسكريبت',
			'title'             => 'JavaScript Online Playground & Editor | محرر جافاسكريبت أونلاين',
			'tagline'           => 'محرر وبيئة تشغيل JavaScript حديثة (ES2024 / Node.js) مع تشغيل فوري وسريع.',
			'description'       => 'بيئة تفاعلية لكتابة وتشغيل كود JavaScript تدعم ميزات ES6+، الدوال السهمية، الوعود (Promises)، والبرمجة غير المتزامنة async/await مع إخراج مريح لكافة رسائل console.',
			'version'           => 'ES2024 / Node 18+',
			'runtime'           => 'Node.js 18.15 & Browser Sandbox',
			'piston_lang'       => 'javascript',
			'piston_version'    => '18.15.0',
			'monaco_lang'       => 'javascript',
			'extension'         => 'js',
			'file_name'         => 'index.js',
			'can_run'           => true,
			'execution_mode'    => 'hybrid_sandbox',
			'sample_code'       => "// كود التطور - تجربة لغة JavaScript\nconst developers = [\n  { name: 'محمد', role: 'Full Stack', level: 'Senior' },\n  { name: 'سعيد', role: 'Backend', level: 'Intermediate' }\n];\n\nconsole.log('مرحباً بك في محرر JavaScript!');\nconsole.table(developers);\n\nconst summary = developers.map(dev => `\${dev.name} يعمل كـ \${dev.role}`).join('، ');\nconsole.log('الملخص:', summary);\n",
			'seo_title'         => 'محرر كود JavaScript أونلاين (Online JS Playground) | كود التطور',
			'seo_desc'          => 'محرر جافاسكريبت لتجربة وتشغيل كود JS حديث وميزات ES6+ في المتصفح مع عرض مخرجات console.',
			'keywords'          => array( 'JavaScript Online Editor', 'محرر جافاسكريبت', 'تشغيل JS أونلاين', 'JS Playground' ),
			'related_languages' => array( 'typescript', 'python', 'php', 'bash' ),
			'faq'               => array(
				array(
					'q' => 'هل يعمل الكود في المتصفح أم في بيئة Node.js معزولة؟',
					'a' => 'يدعم المحرر تشغيلاً آمناً وسريعاً مع استجابة فورية لعرض النتائج مباشرة.',
				),
			),
		),

		// 3. TypeScript
		'typescript' => array(
			'slug'              => 'typescript',
			'name'              => 'TypeScript',
			'name_ar'           => 'تايب سكريبت',
			'title'             => 'TypeScript Online Compiler & Editor | محرر ومترجم تايب سكريبت أونلاين',
			'tagline'           => 'اكتب كود TypeScript مع التحقق الصارم من الأنواع (Type Checking) وترجمته الفورية.',
			'description'       => 'بيئة متطورة لتطوير واختبار كود TypeScript 5.x. توفر فحصاً حياً للأنواع (Type Checking)، ودعم الواجهات (Interfaces)، والـ Generics، مع ترجمة الكود وتنفيذه فوراً.',
			'version'           => '5.0+',
			'runtime'           => 'TypeScript 5.0.3 Runtime',
			'piston_lang'       => 'typescript',
			'piston_version'    => '5.0.3',
			'monaco_lang'       => 'typescript',
			'extension'         => 'ts',
			'file_name'         => 'index.ts',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "// كود التطور - تجربة لغة TypeScript\ninterface Course {\n  id: number;\n  title: string;\n  isPublished: boolean;\n}\n\nconst course: Course = {\n  id: 101,\n  title: 'دورة TypeScript من الصفر إلى الاحتراف',\n  isPublished: true\n};\n\nfunction displayCourse(c: Course): void {\n  console.log(`[كود التطور] الكورس: \${c.title} (الحالة: \${c.isPublished ? 'نشط' : 'مسودة'})`);\n}\n\ndisplayCourse(course);\n",
			'seo_title'         => 'محرر ومترجم تايب سكريبت TypeScript أونلاين | كود التطور',
			'seo_desc'          => 'محرر ومترجم TypeScript أونلاين للتحقق من الأنواع Interfaces & Types وتشغيل الكود فوراً.',
			'keywords'          => array( 'TypeScript Online Compiler', 'محرر تايب سكريبت', 'TypeScript Playground' ),
			'related_languages' => array( 'javascript', 'python', 'go', 'csharp' ),
			'faq'               => array(
				array(
					'q' => 'هل يتم تنبيهي عند وجود خطأ في الأنواع (Type Error)؟',
					'a' => 'نعم، يوضح المترجم أي تعارض في الأنواع ويوجهك للسطر المطلوب تعديله.',
				),
			),
		),

		// 4. C
		'c' => array(
			'slug'              => 'c',
			'name'              => 'C',
			'name_ar'           => 'لغة سي (C)',
			'title'             => 'C Online Compiler & Code Editor | محرر ومترجم لغة C أونلاين',
			'tagline'           => 'ترجمة وتشغيل كود لغة C عبر مترجم GCC 10+ في بيئة معزولة وآمنة.',
			'description'       => 'مترجم C أونلاين حديث مدعوم بـ GCC. يوفر بيئة مثالية لتعلم أساسيات البرمجة، المؤشرات (Pointers)، إدارة الذاكرة، والمصفوفات مع دعم المدخلات وطباعة النتائج فوراً.',
			'version'           => 'C11 / GCC 10.2',
			'runtime'           => 'GCC 10.2.0 Sandbox',
			'piston_lang'       => 'c',
			'piston_version'    => '10.2.0',
			'monaco_lang'       => 'c',
			'extension'         => 'c',
			'file_name'         => 'main.c',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "#include <stdio.h>\n\nint main() {\n    printf(\"مرحباً بك في كود التطور!\\n\");\n    int numbers[] = {10, 20, 30, 40, 50};\n    int sum = 0;\n    for(int i = 0; i < 5; i++) {\n        sum += numbers[i];\n    }\n    printf(\"مجموع الأعداد: %d\\n\", sum);\n    return 0;\n}\n",
			'seo_title'         => 'محرر ومترجم لغة C أونلاين (Online C Compiler) | كود التطور',
			'seo_desc'          => 'مترجم لغة C أونلاين مدعوم بمترجم GCC لترجمة وتشغيل كود C في المتصفح مجاناً وبسرعة.',
			'keywords'          => array( 'C Online Compiler', 'محرر لغة C', 'ترجمة كود C', 'C GCC Online' ),
			'related_languages' => array( 'cpp', 'rust', 'go', 'csharp' ),
			'faq'               => array(
				array(
					'q' => 'هل يدعم مترجم C مكتبة stdio و stdlib القياسية؟',
					'a' => 'نعم، كافة المكتبات القياسية للغة C مدعومة وجاهزة للترجمة والتنفيذ.',
				),
			),
		),

		// 5. C++
		'cpp' => array(
			'slug'              => 'cpp',
			'name'              => 'C++',
			'name_ar'           => 'سي بلس بلس (C++)',
			'title'             => 'C++ Online Compiler & Editor | محرر ومترجم C++ أونلاين',
			'tagline'           => 'ترجمة وتشغيل كود C++20 الحديث عبر مترجم G++ مع دعم STL والبرمجة الكائنية.',
			'description'       => 'بيئة ترجمة متقدمة لـ C++ تدعم معايير C++17 و C++20، ومكتبة القوالب القياسية (STL)، و Vectors، و Classes. مثالية لطلاب البرمجة وحل مسائل الخوارزميات والتحديات البرمجية.',
			'version'           => 'C++20 / G++ 10.2',
			'runtime'           => 'G++ 10.2.0 Sandbox',
			'piston_lang'       => 'c++',
			'piston_version'    => '10.2.0',
			'monaco_lang'       => 'cpp',
			'extension'         => 'cpp',
			'file_name'         => 'main.cpp',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "#include <iostream>\n#include <vector>\n#include <numeric>\n\nint main() {\n    std::cout << \"مرحباً بك في كود التطور - C++!\" << std::endl;\n    std::vector<int> scores = {85, 92, 78, 96, 88};\n    double avg = std::accumulate(scores.begin(), scores.end(), 0.0) / scores.size();\n    std::cout << \"متوسط الدرجات: \" << avg << std::endl;\n    return 0;\n}\n",
			'seo_title'         => 'محرر ومترجم لغة C++ أونلاين (Online C++ Compiler) | كود التطور',
			'seo_desc'          => 'مترجم C++ أونلاين مجاني مع دعم مكتبة STL وميزات C++20 لترجمة وتشغيل الكود في المتصفح.',
			'keywords'          => array( 'C++ Online Compiler', 'محرر C++', 'مترجم سي بلس بلس', 'G++ Online' ),
			'related_languages' => array( 'c', 'rust', 'java', 'csharp' ),
			'faq'               => array(
				array(
					'q' => 'هل يدعم مترجم C++ خوارزميات ومكتبات STL؟',
					'a' => 'نعم، تدعم البيئة كامل مكتبات C++ القياسية بما في ذلك vector, map, algorithm, string.',
				),
			),
		),

		// 6. C#
		'csharp' => array(
			'slug'              => 'csharp',
			'name'              => 'C#',
			'name_ar'           => 'سي شارب (C#)',
			'title'             => 'C# Online Compiler & Editor | محرر ومترجم لغة C# أونلاين',
			'tagline'           => 'ترجمة وتشغيل كود .NET و C# أونلاين مع دعم LINQ والبرمجة الكائنية OOP.',
			'description'       => 'محرر ومترجم C# يتيح لك كتابة كود بيئة .NET، واستعلامات LINQ، والفئات الكائنية، مع ترجمة فورية وطباعة للمخرجات في طرفية تشغيل نظيفة وسريعة.',
			'version'           => 'C# 10 / Mono & .NET',
			'runtime'           => 'Mono 6.12 Sandbox',
			'piston_lang'       => 'csharp',
			'piston_version'    => '6.12.0',
			'monaco_lang'       => 'csharp',
			'extension'         => 'cs',
			'file_name'         => 'Program.cs',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "using System;\nusing System.Collections.Generic;\nusing System.Linq;\n\nclass Program {\n    static void Main() {\n        Console.WriteLine(\"كود التطور - C# Online Playground\");\n        var numbers = new List<int> { 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 };\n        var evens = numbers.Where(n => n % 2 == 0);\n        Console.WriteLine(\"الأعداد الزوجية: \" + string.Join(\", \", evens));\n    }\n}\n",
			'seo_title'         => 'محرر ومترجم لغة C# أونلاين (Online C# Compiler) | كود التطور',
			'seo_desc'          => 'ترجمة وتشغيل كود C# و .NET أونلاين في المتصفح مع دعم LINQ والبرمجة كائنية التوجه.',
			'keywords'          => array( 'C# Online Compiler', 'محرر سي شارب', 'مترجم C# أونلاين', '.NET Playground' ),
			'related_languages' => array( 'java', 'typescript', 'cpp', 'python' ),
			'faq'               => array(
				array(
					'q' => 'هل يمكنني استخدام LINQ مع المجموعات؟',
					'a' => 'نعم، مكتبات System.Linq مدعومة بالكامل وتعمل بسلاسة.',
				),
			),
		),

		// 7. Java
		'java' => array(
			'slug'              => 'java',
			'name'              => 'Java',
			'name_ar'           => 'جافا',
			'title'             => 'Java Online Compiler & Code Editor | محرر ومترجم جافا أونلاين',
			'tagline'           => 'ترجمة وتنفيذ برامج Java (OpenJDK 15+) مع دعم فئات OOP و Java Collections.',
			'description'       => 'محرر ومترجم Java أونلاين متكامل يعمل بـ OpenJDK. يتيح لك بناء الفئات (Classes)، الكائنات، معالجة الاستثناءات، ومجموعات البيانات (Collections) مع إخراج واضح للنتائج والأخطاء.',
			'version'           => 'Java 15 (OpenJDK)',
			'runtime'           => 'OpenJDK 15.0.2 Sandbox',
			'piston_lang'       => 'java',
			'piston_version'    => '15.0.2',
			'monaco_lang'       => 'java',
			'extension'         => 'java',
			'file_name'         => 'Main.java',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "import java.util.ArrayList;\nimport java.util.List;\n\npublic class Main {\n    public static void main(String[] args) {\n        System.out.println(\"مرحباً بك في كود التطور - Java Online!\");\n        List<String> tracks = new ArrayList<>();\n        tracks.add(\"تطوير الويب\");\n        tracks.add(\"الذكاء الاصطناعي\");\n        tracks.add(\"قواعد البيانات\");\n        \n        for (String track : tracks) {\n            System.out.println(\"- مسار: \" + track);\n        }\n    }\n}\n",
			'seo_title'         => 'محرر ومترجم لغة جافا Java أونلاين (Online Java Compiler) | كود التطور',
			'seo_desc'          => 'مترجم جافا Java أونلاين مجاني لترجمة وتشغيل كود جافا في المتصفح مع دعم كامل لـ OOP.',
			'keywords'          => array( 'Java Online Compiler', 'محرر جافا', 'مترجم جافا أونلاين', 'Java IDE Online' ),
			'related_languages' => array( 'kotlin', 'csharp', 'cpp', 'python' ),
			'faq'               => array(
				array(
					'q' => 'هل اسم الفئة الرئيسية يجب أن يكون Main؟',
					'a' => 'نعم، يجب أن تكون الفئة الرئيسية المعرفة هي `public class Main` ليتم استدعاء `main` بنجاح.',
				),
			),
		),

		// 8. Go
		'go' => array(
			'slug'              => 'go',
			'name'              => 'Go',
			'name_ar'           => 'لغة غو (Golang)',
			'title'             => 'Go Online Compiler & Editor | محرر ومترجم لغة Go (Golang) أونلاين',
			'tagline'           => 'ترجمة وتشغيل كود Go بسرعة فائقة مع دعم Goroutines, Channels, و Structs.',
			'description'       => 'بيئة تشغيل لغة Go الرسمية من Google. تمكنك من كتابة حزم Go وتجربة التزامن (Concurrency)، قنوات البيانات (Channels)، والتعامل مع JSON وتراكيب البيانات بسرعة استثنائية.',
			'version'           => 'Go 1.16+',
			'runtime'           => 'Go 1.16.2 Sandbox',
			'piston_lang'       => 'go',
			'piston_version'    => '1.16.2',
			'monaco_lang'       => 'go',
			'extension'         => 'go',
			'file_name'         => 'main.go',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "package main\n\nimport (\n\t\"fmt\"\n\t\"strings\"\n)\n\nfunc main() {\n\tfmt.Println(\"مرحباً بك في كود التطور - Go!\")\n\tphrase := \"Code Elta6ur Platform\"\n\tfmt.Printf(\"الحروف الكبيرة: %s\\n\", strings.ToUpper(phrase))\n\tfmt.Printf(\"عدد الكلمات: %d\\n\", len(strings.Fields(phrase)))\n}\n",
			'seo_title'         => 'محرر ومترجم لغة Go (Golang) أونلاين | كود التطور',
			'seo_desc'          => 'مترجم لغة Go أونلاين سريع لترجمة وتشغيل كود Golang في المتصفح مجاناً بأمان تام.',
			'keywords'          => array( 'Go Online Compiler', 'Golang Playground', 'محرر لغة جو', 'ترجمة Go أونلاين' ),
			'related_languages' => array( 'rust', 'c', 'python', 'typescript' ),
			'faq'               => array(
				array(
					'q' => 'ما هي سرعة ترجمة كود Go في المحرر؟',
					'a' => 'لغة Go معروفة بسرعتها العالية في الترجمة وتستغرق عادة أقل من ثانية واحدة لتنفيذ الكود.',
				),
			),
		),

		// 9. Rust
		'rust' => array(
			'slug'              => 'rust',
			'name'              => 'Rust',
			'name_ar'           => 'لغة رَست (Rust)',
			'title'             => 'Rust Online Compiler & Playground | محرر ومترجم لغة Rust أونلاين',
			'tagline'           => 'ترجمة كود Rust مع الاستفادة من مدقق الملكية (Borrow Checker) وأمان الذاكرة.',
			'description'       => 'بيئة متقدمة لتجربة لغة Rust ومترجم rustc. تتيح لك اختبار مفاهيم الملكية (Ownership)، الاستعارة (Borrowing)، مطابقة الأنماط (Pattern Matching)، والتراكيب بدون كلفة إضافية للذاكرة.',
			'version'           => 'Rust 1.68+',
			'runtime'           => 'rustc 1.68.2 Sandbox',
			'piston_lang'       => 'rust',
			'piston_version'    => '1.68.2',
			'monaco_lang'       => 'rust',
			'extension'         => 'rs',
			'file_name'         => 'main.rs',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "fn main() {\n    println!(\"مرحباً بك في كود التطور - لغة Rust!\");\n    let numbers = vec![1, 2, 3, 4, 5];\n    let doubled: Vec<i32> = numbers.iter().map(|&x| x * 2).collect();\n    println!(\"المصفوفة بعد المضاعفة: {:?}\", doubled);\n}\n",
			'seo_title'         => 'محرر ومترجم لغة Rust أونلاين (Rust Online Playground) | كود التطور',
			'seo_desc'          => 'محرر ومترجم لغة Rust أونلاين لاختبار كود رَست وأمان الذاكرة في المتصفح مجاناً.',
			'keywords'          => array( 'Rust Online Compiler', 'Rust Playground', 'محرر لغة رست', 'مترجم Rust' ),
			'related_languages' => array( 'c', 'cpp', 'go', 'python' ),
			'faq'               => array(
				array(
					'q' => 'هل يعرض المحرر رسائل أخطاء المترجم rustc التفصيلية؟',
					'a' => 'نعم، رسائل المترجم الدقيقة الغنية بالتلميحات التوجيهية تظهر كاملة في لوحة الإخراج.',
				),
			),
		),

		// 10. PHP
		'php' => array(
			'slug'              => 'php',
			'name'              => 'PHP',
			'name_ar'           => 'بي إتش بي (PHP)',
			'title'             => 'PHP Online Sandbox & Code Editor | محرر ومترجم لغة PHP أونلاين',
			'tagline'           => 'كتابة وتنفيذ سكربتات PHP 8.2 الحديثة في بيئة معزولة ومحمية بالكامل.',
			'description'       => 'بيئة آمنة ومعزولة كلياً لتجربة لغة PHP 8.2+ في المتصفح. تتيح لك اختبار المصفوفات، دوال النصوص، ميزات OOP الحديثة، والـ Enums دون أي تداخل مع خادم الويب الأساسي.',
			'version'           => 'PHP 8.2+',
			'runtime'           => 'PHP 8.2.3 Isolated Sandbox',
			'piston_lang'       => 'php',
			'piston_version'    => '8.2.3',
			'monaco_lang'       => 'php',
			'extension'         => 'php',
			'file_name'         => 'index.php',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "<?php\n// كود التطور - تجربة لغة PHP\n\$platform = \"كود التطور\";\n\$topics = [\"PHP 8.2\", \"WordPress\", \"Full Stack\", \"Security\"];\n\necho \"مرحباً بك في منصة {\$platform}!\\n\";\necho \"المواضيع الأكثر طلباً:\\n\";\nforeach (\$topics as \$index => \$topic) {\n    echo \" \" . (\$index + 1) . \". {\$topic}\\n\";\n}\n",
			'seo_title'         => 'محرر لغة PHP أونلاين (Online PHP Sandbox) | كود التطور',
			'seo_desc'          => 'محرر PHP أونلاين لتشغيل واختبار سكربتات PHP 8 في بيئة معزولة وآمنة تماماً.',
			'keywords'          => array( 'PHP Online Editor', 'PHP Sandbox', 'تشغيل PHP أونلاين', 'محرر بي إتش بي' ),
			'related_languages' => array( 'javascript', 'python', 'ruby', 'sql' ),
			'faq'               => array(
				array(
					'q' => 'هل الكود ينفذ على سيرفر ووردبريس الحالي؟',
					'a' => 'مستحيل! يتم عزل التنفيذ بالكامل داخل Sandbox خارجي مغلق ولا يمتلك أي وصول لنظام الملفات أو خادم الموقع.',
				),
			),
		),

		// 11. Ruby
		'ruby' => array(
			'slug'              => 'ruby',
			'name'              => 'Ruby',
			'name_ar'           => 'روبي (Ruby)',
			'title'             => 'Ruby Online Compiler & Code Editor | محرر لغة روبي أونلاين',
			'tagline'           => 'كتابة وتشغيل كود لغة Ruby 3 التفاعلي مع دعم الكتل البرمجية Blocks ومصفوفات Enumerable.',
			'description'       => 'محرر تفاعلي للغة Ruby 3.0+. يمنحك تجربة كتابة سريعة وأنيقة للاستفادة من مرونة لغة روبي، الـ Blocks، والـ Hashes، ومعالجة النصوص البرمجية في بيئة تشغيل سريعة.',
			'version'           => 'Ruby 3.0+',
			'runtime'           => 'Ruby 3.0.1 Sandbox',
			'piston_lang'       => 'ruby',
			'piston_version'    => '3.0.1',
			'monaco_lang'       => 'ruby',
			'extension'         => 'rb',
			'file_name'         => 'main.rb',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "# كود التطور - تجربة لغة Ruby\nclass Platform\n  attr_reader :name\n  def initialize(name)\n    @name = name\n  end\n  def welcome\n    \"أهلاً بك في منصة #{@name} - بيئة تشغيل Ruby!\"\n  end\nend\n\napp = Platform.new('كود التطور')\nputs app.welcome\n",
			'seo_title'         => 'محرر ومترجم لغة روبي Ruby أونلاين | كود التطور',
			'seo_desc'          => 'اكتب وشغل كود Ruby 3 أونلاين في المتصفح مجاناً مع دعم البرمجة الكائنية والكتل البرمجية.',
			'keywords'          => array( 'Ruby Online Compiler', 'محرر لغة روبي', 'Ruby Playground', 'تشغيل Ruby' ),
			'related_languages' => array( 'python', 'php', 'javascript', 'crystal' ),
			'faq'               => array(
				array(
					'q' => 'هل يدعم المحرر أساليب التكرار القياسية في Ruby؟',
					'a' => 'نعم، تدعم البيئة methods الشهيرة مثل each, map, select, reduce.',
				),
			),
		),

		// 12. Kotlin
		'kotlin' => array(
			'slug'              => 'kotlin',
			'name'              => 'Kotlin',
			'name_ar'           => 'كوتلن (Kotlin)',
			'title'             => 'Kotlin Online Compiler & Editor | محرر ومترجم لغة كوتلن أونلاين',
			'tagline'           => 'ترجمة وتشغيل كود Kotlin 1.8 مع ميزات Null Safety والـ Data Classes الحديثة.',
			'description'       => 'محرر لغة Kotlin الحديثة المعتمدة لتطبيقات أندرويد والأنظمة الخلفية. يتيح لك تجربة أمان المؤشرات (Null Safety)، فئات البيانات (Data Classes)، ودوال الإطالة (Extension Functions).',
			'version'           => 'Kotlin 1.8+',
			'runtime'           => 'Kotlin 1.8.20 Sandbox',
			'piston_lang'       => 'kotlin',
			'piston_version'    => '1.8.20',
			'monaco_lang'       => 'kotlin',
			'extension'         => 'kt',
			'file_name'         => 'Main.kt',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "// كود التطور - تجربة لغة Kotlin\ndata class Article(val title: String, val author: String, val views: Int)\n\nfun main() {\n    println(\"مرحباً بك في كود التطور - Kotlin!\")\n    val article = Article(\"تعلم البرمجة من الصفر\", \"م. محمد أبو خشريف\", 1500)\n    println(\"المقال: \${article.title} بقلم \${article.author} (المشاهدات: \${article.views})\")\n}\n",
			'seo_title'         => 'محرر ومترجم لغة كوتلن Kotlin أونلاين | كود التطور',
			'seo_desc'          => 'مترجم كوتلن Kotlin أونلاين لتجربة وتشغيل كود Kotlin مع ميزات Null Safety في المتصفح.',
			'keywords'          => array( 'Kotlin Online Compiler', 'Kotlin Playground', 'محرر كوتلن أونلاين', 'مترجم كوتلن' ),
			'related_languages' => array( 'java', 'swift', 'csharp', 'typescript' ),
			'faq'               => array(
				array(
					'q' => 'هل كود Kotlin متوافق مع مكتبات Java؟',
					'a' => 'البيئة تعمل فوق JVM وتوفر توافقية كاملة مع استدعاء فئات جافا القياسية.',
				),
			),
		),

		// 13. Swift
		'swift' => array(
			'slug'              => 'swift',
			'name'              => 'Swift',
			'name_ar'           => 'سويفت (Swift)',
			'title'             => 'Swift Online Compiler & Code Editor | محرر ومترجم لغة سويفت أونلاين',
			'tagline'           => 'ترجمة وتنفيذ كود لغة Swift 5 الخاصة بأنظمة Apple وتطبيقات iOS أونلاين.',
			'description'       => 'بيئة ترجمة سريعة للغة Swift من Apple. تمكنك من كتابة وتجربة Structs، والـ Optionals، و Protocols، ومعالجة النصوص والخوارزميات في بيئة Linux Swift Runtime معزولة.',
			'version'           => 'Swift 5.3+',
			'runtime'           => 'Swift 5.3.3 Sandbox',
			'piston_lang'       => 'swift',
			'piston_version'    => '5.3.3',
			'monaco_lang'       => 'swift',
			'extension'         => 'swift',
			'file_name'         => 'main.swift',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "// كود التطور - تجربة لغة Swift\nimport Foundation\n\nstruct TechTool {\n    var name: String\n    var category: String\n}\n\nlet tools = [\n    TechTool(name: \"JSON Formatter\", category: \"Data\"),\n    TechTool(name: \"Code Editor\", category: \"Developer\")\n]\n\nprint(\"أهلاً بك في كود التطور - Swift Online!\")\nfor tool in tools {\n    print(\"- الأداة: \\(tool.name) ضمن تصنيف: \\(tool.category)\")\n}\n",
			'seo_title'         => 'محرر ومترجم لغة سويفت Swift أونلاين | كود التطور',
			'seo_desc'          => 'ترجمة وتشغيل كود سويفت Swift أونلاين في المتصفح لتعلم أساسيات برمجة تطبيقات Apple.',
			'keywords'          => array( 'Swift Online Compiler', 'محرر سويفت', 'Swift Playground Online', 'ترجمة سويفت' ),
			'related_languages' => array( 'kotlin', 'rust', 'csharp', 'python' ),
			'faq'               => array(
				array(
					'q' => 'هل يتطلب استخدام مترجم Swift وجود جهاز Mac؟',
					'a' => 'لا، يعمل المترجم عبر الخادم في بيئة Swift الرسمية المستقلة ويمكنك تشغيله من أي جهاز أو هاتف.',
				),
			),
		),

		// 14. SQL
		'sql' => array(
			'slug'              => 'sql',
			'name'              => 'SQL',
			'name_ar'           => 'لغة الاستعلامات SQL',
			'title'             => 'SQL Online Playground & Editor | محرر ومختبر استعلامات SQL أونلاين',
			'tagline'           => 'إنشاء الجداول وتنفيذ استعلامات SELECT, INSERT, UPDATE, JOIN في بيئة SQLite فورية.',
			'description'       => 'مختبر قواعد بيانات تفاعلي ومباشر مدعوم بمحرك SQLite. يتيح لك إنشاء جداول، إدخال بيانات تجريبية، وتشغيل استعلامات SQL المعقدة مع عرض النتائج كجداول منظمة وواضحة.',
			'version'           => 'SQLite 3.36+',
			'runtime'           => 'SQLite 3.36.0 Sandbox',
			'piston_lang'       => 'sqlite3',
			'piston_version'    => '3.36.0',
			'monaco_lang'       => 'sql',
			'extension'         => 'sql',
			'file_name'         => 'query.sql',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "-- كود التطور - تجربة استعلامات SQL\nCREATE TABLE students (\n    id INTEGER PRIMARY KEY,\n    name TEXT NOT NULL,\n    track TEXT NOT NULL,\n    grade REAL\n);\n\nINSERT INTO students VALUES (1, 'أحمد', 'Web Development', 92.5);\nINSERT INTO students VALUES (2, 'سارة', 'Data Science', 95.0);\nINSERT INTO students VALUES (3, 'خالد', 'DevOps', 88.0);\n\nSELECT * FROM students WHERE grade >= 90.0;\n",
			'seo_title'         => 'محرر ومختبر استعلامات SQL أونلاين (SQL Playground) | كود التطور',
			'seo_desc'          => 'محرر استعلامات SQL تفاعلي لتجربة أوامر CREATE و SELECT و JOIN وعرض النتائج كجدول فوري.',
			'keywords'          => array( 'SQL Online Editor', 'SQL Playground', 'محرر SQL', 'تشغيل استعلامات SQL' ),
			'related_languages' => array( 'python', 'php', 'javascript', 'bash' ),
			'faq'               => array(
				array(
					'q' => 'هل قاعدة البيانات دائمة أم مؤقتة؟',
					'a' => 'هي قاعدة بيانات معزولة ومؤقتة لكل جلسة تنفيذ لضمان سرية بياناتك وتوفير بيئة تجريبية نظيفة.',
				),
			),
		),

		// 15. Bash
		'bash' => array(
			'slug'              => 'bash',
			'name'              => 'Bash',
			'name_ar'           => 'باش (Bash Shell)',
			'title'             => 'Bash Shell Online Terminal & Script Editor | محرر سكربتات باش أونلاين',
			'tagline'           => 'كتابة واختبار سكربتات أوامر لينكس Bash Shell في طرفية معزولة وآمنة.',
			'description'       => 'محرر ومترجم لسكربتات Bash Shell (v5.2+). يتيح لك تجربة حلقات التكرار، الشروط، المتغيرات، وتنسيق الأوامر والأنابيب (Pipes) بأمان تام داخل بيئة معزولة مخصصة.',
			'version'           => 'Bash 5.2+',
			'runtime'           => 'GNU Bash 5.2.0 Sandbox',
			'piston_lang'       => 'bash',
			'piston_version'    => '5.2.0',
			'monaco_lang'       => 'shell',
			'extension'         => 'sh',
			'file_name'         => 'script.sh',
			'can_run'           => true,
			'execution_mode'    => 'isolated_sandbox',
			'sample_code'       => "#!/bin/bash\n# كود التطور - تجربة سكربتات Bash Shell\necho \"مرحباً بك في طرفية كود التطور!\"\nDATE=\$(date +'%Y-%m-%d')\necho \"تاريخ اليوم: \$DATE\"\n\nITEMS=(\"كود نظيف\" \"أمان فائق\" \"أداء استثنائي\")\necho \"مبادئ المنصة الأساسية:\"\nfor item in \"\${ITEMS[@]}\"; do\n    echo \" [✓] \$item\"\ndone\n",
			'seo_title'         => 'محرر ومترجم سكربتات باش Bash أونلاين (Online Bash Terminal) | كود التطور',
			'seo_desc'          => 'اكتب واختبر سكربتات لينكس Bash Shell في المتصفح أونلاين مجاناً في بيئة تشغيل آمنة.',
			'keywords'          => array( 'Bash Online Compiler', 'Bash Terminal Online', 'محرر باش', 'سكربتات لينكس' ),
			'related_languages' => array( 'python', 'go', 'php', 'sql' ),
			'faq'               => array(
				array(
					'q' => 'هل بيئة Bash معزولة عن سيرفر الاستضافة؟',
					'a' => 'نعم، البيئة تعمل داخل حاوية Docker معزولة ومجردة بالكامل من أي صلاحيات وتدمر فور انتهاء الأمر.',
				),
			),
		),
	);

	$languages = array();
	foreach ( $raw_languages as $slug => $def ) {
		$def['runtime_config'] = isset( $runtimes[ $slug ] ) ? $runtimes[ $slug ] : array();
		if ( ! empty( $def['runtime_config']['version'] ) ) {
			$def['version'] = $def['runtime_config']['version'];
		}
		$languages[ $slug ] = $def;
	}

	return $languages;
}

/**
 * Get language by slug
 *
 * @param string $slug Language slug.
 * @return array|null Language definition or null.
 */
function cdt_get_editor_language( $slug ) {
	$languages = cdt_get_editor_languages();
	$slug      = sanitize_key( $slug );
	return isset( $languages[ $slug ] ) ? $languages[ $slug ] : null;
}
