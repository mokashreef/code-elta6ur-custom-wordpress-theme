<?php
/**
 * القالب الرسمي لعرض الأداة التقنية المستقلة (/tools/{slug}/)
 * Code Elta6ur Single Tool Template
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$slug = cdt_get_current_tool_slug();
$tool = cdt_get_tool( $slug );

if ( ! $tool ) {
	global $wp_query;
	if ( $wp_query ) {
		$wp_query->set_404();
	}
	status_header( 404 );
	get_template_part( '404' );
	exit;
}

get_header();

$categories = cdt_get_tool_categories();
$cat_info   = isset( $categories[ $tool['category'] ] ) ? $categories[ $tool['category'] ] : null;
$cat_name   = $cat_info ? $cat_info['name'] : $tool['category'];
?>

<!-- Tool Header -->
<header class="cdt-tool-header">
	<div class="cdt-container">
		<div class="cdt-tool-header-meta">
			<a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>" class="cdt-badge">
				<?php echo esc_html( $cat_name ); ?>
			</a>
			<span class="cdt-badge cdt-badge-accent">
				🔒 خصوصية 100% داخل المتصفح
			</span>
		</div>
		<h1 class="cdt-tool-header-title">
			<?php echo esc_html( $tool['name'] ); ?>
		</h1>
		<p class="cdt-tool-header-desc">
			<?php echo esc_html( $tool['description'] ); ?>
		</p>
	</div>
</header>

<!-- Breadcrumbs -->
<?php cdt_breadcrumbs(); ?>

<!-- Main Tool Workspace -->
<main class="cdt-section" style="padding-block: var(--space-4, 1rem) var(--space-12, 3rem);">
	<div class="cdt-container" data-tool-slug="<?php echo esc_attr( $slug ); ?>">

		<!-- Interactive Workspace Container -->
		<div class="cdt-tool-workspace">
			<!-- Workspace Toolbar -->
			<div class="cdt-tool-toolbar">
				<div class="cdt-tool-actions-left">
					<button type="button" id="cdt-btn-sample" class="cdt-tool-btn">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
						<span>تحميل مثال (Sample)</span>
					</button>

					<?php if ( 'json-formatter' === $slug ) : ?>
						<button type="button" id="cdt-btn-format" class="cdt-tool-btn cdt-tool-btn-primary">
							<span>تنسيق (Format 2 Spaces)</span>
						</button>
						<button type="button" id="cdt-btn-minify" class="cdt-tool-btn">
							<span>ضغط (Minify)</span>
						</button>
					<?php elseif ( 'json-diff' === $slug ) : ?>
						<button type="button" id="cdt-btn-compare" class="cdt-tool-btn cdt-tool-btn-primary">
							<span>مقارنة الفروقات (Compare Diff)</span>
						</button>
					<?php elseif ( 'json-to-code' === $slug ) : ?>
						<label for="cdt-target-lang" style="font-size: 12px; font-weight: 600; margin-inline-end: 4px;">اللغة المستهدفة:</label>
						<select id="cdt-target-lang" class="cdt-tool-btn" style="cursor: pointer;">
							<option value="typescript">TypeScript Interfaces</option>
							<option value="python">Python Dataclasses</option>
							<option value="go">Go Structs</option>
							<option value="csharp">C# Classes</option>
						</select>
					<?php elseif ( 'curl-to-code' === $slug ) : ?>
						<label for="cdt-curl-target-lang" style="font-size: 12px; font-weight: 600; margin-inline-end: 4px;">اللغة المستهدفة:</label>
						<select id="cdt-curl-target-lang" class="cdt-tool-btn" style="cursor: pointer;">
							<option value="fetch">JavaScript (Fetch API)</option>
							<option value="python">Python (Requests)</option>
							<option value="php">PHP (cURL Native)</option>
						</select>
					<?php elseif ( 'yaml-json-converter' === $slug ) : ?>
						<button type="button" id="cdt-btn-yaml-to-json" class="cdt-tool-btn cdt-tool-btn-primary">
							<span>YAML &rarr; JSON</span>
						</button>
						<button type="button" id="cdt-btn-json-to-yaml" class="cdt-tool-btn cdt-tool-btn-primary">
							<span>JSON &rarr; YAML</span>
						</button>
					<?php endif; ?>

					<button type="button" id="cdt-btn-reset" class="cdt-tool-btn cdt-tool-btn-danger">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
						<span>مسح (Clear)</span>
					</button>
				</div>

				<div class="cdt-tool-actions-right">
					<button type="button" id="cdt-btn-copy" class="cdt-tool-btn">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
						<span>نسخ النتيجة</span>
					</button>
					<button type="button" id="cdt-btn-download" class="cdt-tool-btn">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
						<span>تنزيل (Download)</span>
					</button>
				</div>
			</div>

			<!-- Specialized Tool Workspace Panels -->
			<?php if ( 'json-diff' === $slug ) : ?>
				<div class="cdt-tool-panels-dual">
					<div class="cdt-tool-panel">
						<div class="cdt-tool-panel-header">
							<span>الملف الأول (Original JSON)</span>
						</div>
						<textarea id="cdt-tool-input" class="cdt-tool-textarea" placeholder="أدخل كائن JSON الأصلي هنا..."></textarea>
					</div>
					<div class="cdt-tool-panel">
						<div class="cdt-tool-panel-header">
							<span>الملف الثاني (Modified JSON)</span>
						</div>
						<textarea id="cdt-tool-input-2" class="cdt-tool-textarea" placeholder="أدخل كائن JSON المعدل هنا..."></textarea>
					</div>
				</div>
				<div style="background: #FAFCFE; border-block-start: 1px solid var(--brand-border); padding: 1rem;">
					<h4 style="margin: 0 0 8px 0; font-size: 14px;">نتائج المقارنة المرئية:</h4>
					<div id="cdt-tool-output" style="min-height: 120px;"></div>
				</div>

			<?php elseif ( 'json-schema-validator' === $slug ) : ?>
				<div class="cdt-tool-panels-dual">
					<div class="cdt-tool-panel">
						<div class="cdt-tool-panel-header">
							<span>كائن JSON المطلوب فحصه</span>
						</div>
						<textarea id="cdt-tool-input" class="cdt-tool-textarea" placeholder="أدخل كائن JSON هنا..."></textarea>
					</div>
					<div class="cdt-tool-panel">
						<div class="cdt-tool-panel-header">
							<span>مواصفات JSON Schema</span>
							<button type="button" id="cdt-btn-validate-schema" class="cdt-tool-btn cdt-tool-btn-primary" style="padding: 2px 8px;">تحقق الآن</button>
						</div>
						<textarea id="cdt-tool-schema" class="cdt-tool-textarea" placeholder="أدخل مخطط JSON Schema هنا..."></textarea>
					</div>
				</div>
				<textarea id="cdt-tool-output" class="cdt-tool-output-area" style="min-height: 100px; border-block-start: 1px solid var(--brand-border);" readonly placeholder="نتائج التحقق..."></textarea>

			<?php elseif ( 'jsonpath-tester' === $slug ) : ?>
				<div style="padding: 1rem; background: var(--brand-bg-alt); border-block-end: 1px solid var(--brand-border); display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
					<label for="cdt-jsonpath-expr" style="font-weight: 700; font-size: 14px;">تعبير JSONPath:</label>
					<input type="text" id="cdt-jsonpath-expr" value="$.store.book[*].title" style="flex: 1; padding: 6px 12px; font-family: monospace; border: 1px solid var(--brand-border); border-radius: 6px;">
					<button type="button" id="cdt-btn-eval-jsonpath" class="cdt-tool-btn cdt-tool-btn-primary">استخراج</button>
				</div>
				<div class="cdt-tool-panels-dual">
					<div class="cdt-tool-panel">
						<div class="cdt-tool-panel-header"><span>بيانات JSON الأصلية</span></div>
						<textarea id="cdt-tool-input" class="cdt-tool-textarea" placeholder="ألصق كائن JSON هنا..."></textarea>
					</div>
					<div class="cdt-tool-panel">
						<div class="cdt-tool-panel-header"><span>النتيجة المستخرجة</span></div>
						<textarea id="cdt-tool-output" class="cdt-tool-output-area" readonly placeholder="ستظهر النتيجة هنا..."></textarea>
					</div>
				</div>

			<?php elseif ( 'regex-tester' === $slug ) : ?>
				<div style="padding: 1rem; background: var(--brand-bg-alt); border-block-end: 1px solid var(--brand-border); display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
					<label for="cdt-regex-pattern" style="font-weight: 700; font-size: 14px;">نمط Regex:</label>
					<input type="text" id="cdt-regex-pattern" placeholder="([a-zA-Z0-9._%+-]+)@([a-zA-Z0-9.-]+\.[a-zA-Z]{2,})" style="flex: 2; padding: 6px 12px; font-family: monospace; border: 1px solid var(--brand-border); border-radius: 6px; direction: ltr;">
					<label for="cdt-regex-flags" style="font-weight: 700; font-size: 14px;">الأعلام (Flags):</label>
					<input type="text" id="cdt-regex-flags" value="g" style="width: 60px; padding: 6px 12px; font-family: monospace; border: 1px solid var(--brand-border); border-radius: 6px; direction: ltr;">
				</div>
				<div class="cdt-tool-panels-dual">
					<div class="cdt-tool-panel">
						<div class="cdt-tool-panel-header"><span>النص المراد اختباره ومطابقته</span></div>
						<textarea id="cdt-tool-input" class="cdt-tool-textarea" placeholder="أدخل النص هنا لتجربة المطابقة..."></textarea>
					</div>
					<div class="cdt-tool-panel">
						<div class="cdt-tool-panel-header"><span>المطابقات والمجموعات (Matches)</span></div>
						<textarea id="cdt-tool-output" class="cdt-tool-output-area" readonly placeholder="ستظهر المطابقات والمجموعات هنا..."></textarea>
					</div>
				</div>

			<?php elseif ( 'regex-builder' === $slug ) : ?>
				<div style="padding: 1rem; background: var(--brand-bg-alt); border-block-end: 1px solid var(--brand-border); display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
					<label for="cdt-regex-template-select" style="font-weight: 700; font-size: 14px;">اختر قالباً جاهزاً:</label>
					<select id="cdt-regex-template-select" class="cdt-tool-btn">
						<option value="email">البريد الإلكتروني (Email Address)</option>
						<option value="phone">رقم الهاتف الدولي (Phone Number)</option>
						<option value="url">رابط الويب (URL - HTTP/HTTPS)</option>
						<option value="ipv4">عنوان IP (IPv4)</option>
						<option value="slug">المسار اللطيف (Slug)</option>
					</select>
				</div>
				<div style="padding: 1.5rem;">
					<textarea id="cdt-tool-output" class="cdt-tool-output-area" style="min-height: 240px; border: 1px solid var(--brand-border); border-radius: 8px;" readonly></textarea>
				</div>

			<?php elseif ( 'csp-generator' === $slug ) : ?>
				<div style="padding: 1.5rem; background: var(--brand-bg-alt); border-block-end: 1px solid var(--brand-border);">
					<h4 style="margin: 0 0 1rem 0;">تخصيص سياسات أمان المحتوى (CSP Options):</h4>
					<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;">
						<label style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
							<input type="checkbox" id="cdt-csp-self" class="cdt-csp-opt" checked>
							<span>تقييد المصادر بنفس النطاق ('self')</span>
						</label>
						<label style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
							<input type="checkbox" id="cdt-csp-fonts" class="cdt-csp-opt" checked>
							<span>السماح بخطوط Google Fonts</span>
						</label>
						<label style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
							<input type="checkbox" id="cdt-csp-yt" class="cdt-csp-opt" checked>
							<span>السماح بتضمين فيديوهات YouTube</span>
						</label>
					</div>
				</div>
				<textarea id="cdt-tool-output" class="cdt-tool-output-area" style="min-height: 280px;" readonly></textarea>

			<?php elseif ( 'docker-compose-generator' === $slug ) : ?>
				<div style="padding: 1.5rem; background: var(--brand-bg-alt); border-block-end: 1px solid var(--brand-border);">
					<h4 style="margin: 0 0 1rem 0;">اختر الخدمات المراد تضمينها في ملف Compose:</h4>
					<div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
						<label style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
							<input type="checkbox" class="cdt-compose-svc" value="node" checked>
							<span>تطبيق Node.js (Port 3000)</span>
						</label>
						<label style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
							<input type="checkbox" class="cdt-compose-svc" value="postgres" checked>
							<span>قاعدة بيانات PostgreSQL (Port 5432)</span>
						</label>
						<label style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
							<input type="checkbox" class="cdt-compose-svc" value="redis" checked>
							<span>كاش Redis (Port 6379)</span>
						</label>
						<label style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
							<input type="checkbox" class="cdt-compose-svc" value="nginx">
							<span>خادم ويب Nginx (Port 80)</span>
						</label>
					</div>
				</div>
				<textarea id="cdt-tool-output" class="cdt-tool-output-area" style="min-height: 380px;" data-ext="yml" readonly></textarea>

			<?php elseif ( 'dockerfile-generator' === $slug ) : ?>
				<div style="padding: 1rem; background: var(--brand-bg-alt); border-block-end: 1px solid var(--brand-border); display: flex; gap: 12px; align-items: center;">
					<label for="cdt-df-lang" style="font-weight: 700; font-size: 14px;">اختر بيئة التشغيل:</label>
					<select id="cdt-df-lang" class="cdt-tool-btn">
						<option value="node">Node.js (Multi-stage Alpine)</option>
						<option value="python">Python (Slim)</option>
						<option value="go">Go (Multi-stage Alpine)</option>
					</select>
				</div>
				<textarea id="cdt-tool-output" class="cdt-tool-output-area" style="min-height: 380px;" data-ext="dockerfile" readonly></textarea>

			<?php elseif ( 'git-command-generator' === $slug ) : ?>
				<div style="padding: 1rem; background: var(--brand-bg-alt); border-block-end: 1px solid var(--brand-border); display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
					<label for="cdt-git-scenario" style="font-weight: 700; font-size: 14px;">اختر سيناريو Git المطلوب:</label>
					<select id="cdt-git-scenario" class="cdt-tool-btn" style="flex: 1; max-width: 480px;">
						<option value="undo_commit">التراجع عن آخر Commit دون فقدان التعديلات (Undo Last Commit)</option>
						<option value="discard_local">إلغاء وتفريغ كافة التعديلات المحلية (Discard All Local Changes)</option>
						<option value="stash">حفظ التعديلات مؤقتاً في الـ Stash (Stash Work)</option>
						<option value="branch_create">إنشاء فرع جديد والانتقال إليه (Create & Checkout Branch)</option>
						<option value="rename_branch">إعادة تسمية الفرع الحالي (Rename Current Branch)</option>
					</select>
				</div>
				<textarea id="cdt-tool-output" class="cdt-tool-output-area" style="min-height: 280px;" readonly></textarea>

			<?php elseif ( 'gitignore-generator' === $slug ) : ?>
				<div style="padding: 1.5rem; background: var(--brand-bg-alt); border-block-end: 1px solid var(--brand-border);">
					<h4 style="margin: 0 0 1rem 0;">حدد التقنيات المراد استبعاد ملفاتها:</h4>
					<div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
						<label style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
							<input type="checkbox" class="cdt-gi-opt" value="node" checked>
							<span>Node.js / npm (node_modules, .env)</span>
						</label>
						<label style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
							<input type="checkbox" class="cdt-gi-opt" value="python" checked>
							<span>Python (__pycache__, venv)</span>
						</label>
						<label style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
							<input type="checkbox" class="cdt-gi-opt" value="os" checked>
							<span>مخلفات أنظمة التشغيل (.DS_Store, Thumbs.db)</span>
						</label>
						<label style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
							<input type="checkbox" class="cdt-gi-opt" value="ide" checked>
							<span>مجلدات المحررات (.vscode, .idea)</span>
						</label>
					</div>
				</div>
				<textarea id="cdt-tool-output" class="cdt-tool-output-area" style="min-height: 340px;" data-ext="gitignore" readonly></textarea>

			<?php elseif ( 'package-json-generator' === $slug ) : ?>
				<div style="padding: 1.5rem; background: var(--brand-bg-alt); border-block-end: 1px solid var(--brand-border); display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
					<div>
						<label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 4px;">اسم المشروع (Name):</label>
						<input type="text" id="cdt-pkg-name" value="my-app" style="width: 100%; padding: 6px 12px; border: 1px solid var(--brand-border); border-radius: 6px;">
					</div>
					<div>
						<label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 4px;">الإصدار (Version):</label>
						<input type="text" id="cdt-pkg-version" value="1.0.0" style="width: 100%; padding: 6px 12px; border: 1px solid var(--brand-border); border-radius: 6px;">
					</div>
					<div style="grid-column: 1 / -1;">
						<label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 4px;">الوصف (Description):</label>
						<input type="text" id="cdt-pkg-desc" value="مشروع مبني بنظام كود التطور" style="width: 100%; padding: 6px 12px; border: 1px solid var(--brand-border); border-radius: 6px;">
					</div>
				</div>
				<textarea id="cdt-tool-output" class="cdt-tool-output-area" style="min-height: 320px;" data-ext="json" readonly></textarea>

			<?php elseif ( 'semver-calculator' === $slug ) : ?>
				<div style="padding: 1rem; background: var(--brand-bg-alt); border-block-end: 1px solid var(--brand-border); display: flex; gap: 12px; align-items: center;">
					<label for="cdt-semver-input" style="font-weight: 700; font-size: 14px;">الإصدار الحالي (Current Version):</label>
					<input type="text" id="cdt-semver-input" value="1.2.3" style="padding: 6px 12px; font-family: monospace; border: 1px solid var(--brand-border); border-radius: 6px; width: 120px;">
				</div>
				<textarea id="cdt-tool-output" class="cdt-tool-output-area" style="min-height: 280px;" readonly></textarea>

			<?php elseif ( 'css-clamp-generator' === $slug ) : ?>
				<div style="padding: 1.5rem; background: var(--brand-bg-alt); border-block-end: 1px solid var(--brand-border); display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
					<div>
						<label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 4px;">أصغر حجم خط (px):</label>
						<input type="number" id="cdt-clamp-min-font" value="16" style="width: 100%; padding: 6px 12px; border: 1px solid var(--brand-border); border-radius: 6px;">
					</div>
					<div>
						<label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 4px;">أكبر حجم خط (px):</label>
						<input type="number" id="cdt-clamp-max-font" value="32" style="width: 100%; padding: 6px 12px; border: 1px solid var(--brand-border); border-radius: 6px;">
					</div>
					<div>
						<label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 4px;">عرض أصغر شاشة (px):</label>
						<input type="number" id="cdt-clamp-min-vp" value="360" style="width: 100%; padding: 6px 12px; border: 1px solid var(--brand-border); border-radius: 6px;">
					</div>
					<div>
						<label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 4px;">عرض أكبر شاشة (px):</label>
						<input type="number" id="cdt-clamp-max-vp" value="1200" style="width: 100%; padding: 6px 12px; border: 1px solid var(--brand-border); border-radius: 6px;">
					</div>
				</div>
				<textarea id="cdt-tool-output" class="cdt-tool-output-area" style="min-height: 280px;" data-ext="css" readonly></textarea>

			<?php elseif ( 'css-unit-converter' === $slug ) : ?>
				<div style="padding: 1.5rem; background: var(--brand-bg-alt); border-block-end: 1px solid var(--brand-border); display: flex; gap: 1.5rem; flex-wrap: wrap;">
					<div>
						<label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 4px;">القيمة بالبكسل (px):</label>
						<input type="number" id="cdt-unit-px" value="24" style="padding: 6px 12px; border: 1px solid var(--brand-border); border-radius: 6px; width: 140px;">
					</div>
					<div>
						<label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 4px;">حجم الخط الأساسي Base (px):</label>
						<input type="number" id="cdt-unit-base" value="16" style="padding: 6px 12px; border: 1px solid var(--brand-border); border-radius: 6px; width: 140px;">
					</div>
				</div>
				<textarea id="cdt-tool-output" class="cdt-tool-output-area" style="min-height: 280px;" readonly></textarea>

			<?php elseif ( 'db-schema-generator' === $slug ) : ?>
				<div style="padding: 1rem; background: var(--brand-bg-alt); border-block-end: 1px solid var(--brand-border); display: flex; gap: 12px; align-items: center;">
					<label for="cdt-schema-table-name" style="font-weight: 700; font-size: 14px;">اسم الجدول (Table Name):</label>
					<input type="text" id="cdt-schema-table-name" value="articles" style="padding: 6px 12px; border: 1px solid var(--brand-border); border-radius: 6px;">
					<button type="button" id="cdt-btn-gen-schema" class="cdt-tool-btn cdt-tool-btn-primary">توليد كود SQL</button>
				</div>
				<textarea id="cdt-tool-output" class="cdt-tool-output-area" style="min-height: 380px;" data-ext="sql" readonly></textarea>

			<?php else : ?>
				<!-- Default Clean Dual Panels (Input on Left, Output on Right) -->
				<div class="cdt-tool-panels-dual">
					<div class="cdt-tool-panel">
						<div class="cdt-tool-panel-header">
							<span>المدخلات (Input)</span>
						</div>
						<textarea id="cdt-tool-input" class="cdt-tool-textarea" placeholder="أدخل البيانات أو الكود هنا..."></textarea>
					</div>
					<div class="cdt-tool-panel">
						<div class="cdt-tool-panel-header">
							<span>المخرجات (Output)</span>
						</div>
						<textarea id="cdt-tool-output" class="cdt-tool-output-area" readonly placeholder="ستظهر النتيجة المنسقة هنا..."></textarea>
					</div>
				</div>
			<?php endif; ?>

			<!-- Status Bar -->
			<div id="cdt-tool-status" class="cdt-tool-status">
				<span>جاهز للاستخدام. أدخل بياناتك أو اضغط على تحميل مثال.</span>
			</div>
		</div>

		<!-- Usage Guide / How it Works -->
		<section class="cdt-tool-card-box" style="margin-block-start: var(--space-8, 2rem);">
			<h2 style="font-size: var(--font-size-xl, 1.25rem); margin-block-end: var(--space-3, 0.75rem);">
				طريقة استخدام أداة <?php echo esc_html( $tool['name'] ); ?>
			</h2>
			<p style="margin-block-end: var(--space-4, 1rem); line-height: 1.8;">
				<?php echo esc_html( $tool['description'] ); ?>
			</p>
			<ol style="margin-block-end: 0; padding-inline-start: 1.5rem; line-height: 1.8;">
				<li>أدخل الكود أو البيانات المطلوبة في حقل المدخلات، أو انقر على <strong>تحميل مثال</strong> لتجربة سريعة.</li>
				<li>تقوم الأداة فورياً بمعالجة المدخلات واكتشاف الأخطاء وتنسيق المخرجات بالكامل في المتصفح.</li>
				<li>يمكنك بنقرة واحدة نسخ النتيجة النظيفة إلى الحافظة أو تنزيلها كملف مستقل لجهازك.</li>
			</ol>
		</section>

		<!-- FAQ Section for the Tool -->
		<?php if ( ! empty( $tool['faq'] ) ) : ?>
			<section class="cdt-tool-faq" aria-labelledby="tool-faq-title">
				<h3 id="tool-faq-title" style="font-size: var(--font-size-xl, 1.25rem); margin-block-end: var(--space-4, 1rem);">
					الأسئلة الشائعة حول <?php echo esc_html( $tool['name'] ); ?>
				</h3>
				<div style="max-width: 100%;">
					<?php foreach ( $tool['faq'] as $idx => $item ) : ?>
						<div class="cdt-faq-item <?php echo 0 === $idx ? 'is-open' : ''; ?>">
							<button class="cdt-faq-question">
								<span><?php echo esc_html( $item['q'] ); ?></span>
								<span>▾</span>
							</button>
							<div class="cdt-faq-answer">
								<?php echo esc_html( $item['a'] ); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<!-- Related Tools Section -->
		<?php if ( ! empty( $tool['related_tools'] ) ) : ?>
			<section class="cdt-tool-related">
				<h3 style="font-size: var(--font-size-xl, 1.25rem); margin-block-end: var(--space-4, 1rem);">
					أدوات برمجية ذات صلة
				</h3>
				<div class="cdt-tools-grid">
					<?php foreach ( $tool['related_tools'] as $rel_slug ) :
						$rel_tool = cdt_get_tool( $rel_slug );
						if ( ! $rel_tool ) continue;
						?>
						<a href="<?php echo esc_url( home_url( '/tools/' . $rel_slug . '/' ) ); ?>" class="cdt-tool-card" data-category="<?php echo esc_attr( $rel_tool['category'] ); ?>">
							<div>
								<div class="cdt-tool-card-top">
									<div class="cdt-tool-card-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>
									</div>
									<span class="cdt-tool-card-badge"><?php echo esc_html( $rel_tool['name_en'] ); ?></span>
								</div>
								<h4 class="cdt-tool-card-title"><?php echo esc_html( $rel_tool['name'] ); ?></h4>
								<p class="cdt-tool-card-desc"><?php echo esc_html( $rel_tool['tagline'] ); ?></p>
							</div>
							<div class="cdt-tool-card-footer">
								<span>استخدام الأداة</span>
								<?php echo cdt_svg_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
