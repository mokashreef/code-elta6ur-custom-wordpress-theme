<?php
/**
 * القالب الرسمي لمحرر ومترجم الأكواد البرمجية أونلاين (/editor/ و /editor/{lang}/)
 * Code Elta6ur Online Code Editor Template
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$active_lang_slug = cdt_get_current_editor_lang();
$language         = cdt_get_editor_language( $active_lang_slug );
$all_languages    = cdt_get_editor_languages();

if ( ! $language ) {
	$language = $all_languages['python'];
}

get_header();
?>

<div class="cdt-editor-page">
	<!-- Editor Studio Top Bar -->
	<div class="cdt-editor-header-bar">
		<div class="cdt-editor-brand">
			<label for="cdt-editor-lang-select" style="font-size: 13px; font-weight: 700; color: #94A3B8;">اللغة البرمجية:</label>
			<select id="cdt-editor-lang-select" class="cdt-editor-lang-select" aria-label="<?php esc_attr_e( 'اختر لغة البرمجة', 'code-elta6ur' ); ?>">
				<?php foreach ( $all_languages as $l_slug => $l_def ) : ?>
					<option value="<?php echo esc_attr( $l_slug ); ?>" <?php selected( $active_lang_slug, $l_slug ); ?>>
						<?php echo esc_html( $l_def['name'] . ' (' . $l_def['name_ar'] . ')' ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<span style="font-size: 11px; background: rgba(56, 189, 248, 0.15); color: #38BDF8; padding: 3px 8px; border-radius: 6px; font-family: monospace;">
				<?php echo esc_html( $language['version'] ); ?>
			</span>
		</div>

		<!-- Action Buttons -->
		<div class="cdt-editor-actions">
			<button type="button" id="cdt-btn-run" class="cdt-run-btn" title="تشغيل الكود (Ctrl + Enter)">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
				<span>تشغيل الكود (Run)</span>
			</button>

			<button type="button" id="cdt-tab-stdin-toggle" class="cdt-editor-btn" title="إدخال مدخلات stdin">
				<span>المدخلات (Stdin)</span>
			</button>

			<button type="button" id="cdt-btn-copy-code" class="cdt-editor-btn" title="نسخ الكود البرمجي">
				<span>نسخ</span>
			</button>

			<button type="button" id="cdt-btn-download-code" class="cdt-editor-btn" title="تنزيل ملف المصدر">
				<span>تنزيل</span>
			</button>

			<button type="button" id="cdt-btn-reset-code" class="cdt-editor-btn" title="إعادة تعيين للقالب الافتراضي">
				<span>إعادة ضبط</span>
			</button>

			<button type="button" id="cdt-btn-fullscreen" class="cdt-editor-btn" title="تبديل وضع ملء الشاشة">
				<span>ملء الشاشة</span>
			</button>
		</div>
	</div>

	<!-- Mobile Switcher Tabs -->
	<div class="cdt-editor-mobile-tabs">
		<button type="button" id="cdt-tab-code" class="cdt-editor-mobile-tab-btn is-active">
			<span>محرر الكود</span>
		</button>
		<button type="button" id="cdt-tab-terminal" class="cdt-editor-mobile-tab-btn">
			<span>الطرفية والنتائج</span>
		</button>
	</div>

	<!-- Main Code Studio Workspace -->
	<div id="cdt-editor-container" class="cdt-editor-workspace" data-lang="<?php echo esc_attr( $active_lang_slug ); ?>" data-ext="<?php echo esc_attr( $language['extension'] ); ?>">
		<!-- Code Editor Pane -->
		<div class="cdt-editor-pane">
			<div class="cdt-editor-pane-header">
				<span><?php echo esc_html( $language['file_name'] ); ?></span>
				<span>UTF-8 • <?php echo esc_html( $language['runtime'] ); ?></span>
			</div>
			<div class="cdt-code-surface">
				<textarea id="cdt-code-textarea" class="cdt-fallback-editor" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off" aria-label="<?php esc_attr_e( 'محرر الكود', 'code-elta6ur' ); ?>"><?php echo esc_textarea( $language['sample_code'] ); ?></textarea>
			</div>
		</div>

		<!-- Terminal & Console Pane -->
		<div class="cdt-terminal-pane">
			<div class="cdt-terminal-header">
				<div class="cdt-terminal-tabs">
					<button type="button" class="cdt-terminal-tab-btn is-active">طرفية التشغيل (Console)</button>
					<button type="button" id="cdt-btn-clear-terminal" class="cdt-terminal-tab-btn" style="color: #64748B;">مسح</button>
				</div>
				<div class="cdt-terminal-status-indicator">
					<span id="cdt-status-dot" class="cdt-status-dot is-idle"></span>
					<span id="cdt-terminal-status">جاهز للتشغيل</span>
				</div>
			</div>

			<!-- Stdin Input Drawer -->
			<div id="cdt-terminal-stdin-box" class="cdt-terminal-stdin-box">
				<label for="cdt-terminal-stdin" style="font-size: 11px; color: #94A3B8; margin-bottom: 4px;">المدخلات القياسية (Standard Input - Stdin):</label>
				<textarea id="cdt-terminal-stdin" class="cdt-terminal-stdin-input" placeholder="اكتب هنا المدخلات التي سيقرأها برنامجك عبر input() أو cin أو Scanner..."></textarea>
			</div>

			<pre id="cdt-terminal-output" class="cdt-terminal-output" aria-live="polite">مرحباً بك في محرر ومترجم كود التطور!
اضغط على زر "تشغيل الكود" أو اختصار (Ctrl + Enter) لتنفيذ الكود فوراً.</pre>
		</div>
	</div>

	<!-- Language Information & Educational SEO Content -->
	<div class="cdt-editor-details">
		<div class="cdt-container">
			<div class="cdt-lang-info-grid">
				<div>
					<h2 style="font-size: var(--font-size-2xl, 1.5rem); margin-block-end: var(--space-4, 1rem);">
						عن لغة <?php echo esc_html( $language['name'] . ' (' . $language['name_ar'] . ')' ); ?> وبيئة التشغيل
					</h2>
					<p style="font-size: var(--font-size-md, 1rem); line-height: 1.8; color: var(--brand-text-muted, #475569); margin-block-end: var(--space-6, 1.5rem);">
						<?php echo esc_html( $language['description'] ); ?>
					</p>

					<div style="background: var(--brand-surface, #F1F5F9); border: 1px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-sm, 10px); padding: var(--space-6, 1.5rem); margin-block-end: var(--space-8, 2rem);">
						<h3 style="font-size: var(--font-size-md, 1rem); margin-block-end: var(--space-3, 0.75rem);">
							⚡ كيفية عمل بيئة التشغيل المعزولة (Isolated Sandbox)
						</h3>
						<ul style="margin-block-end: 0; padding-inline-start: 1.5rem; line-height: 1.8; color: var(--brand-text-muted, #475569);">
							<li><strong>أمان كامل وعزل تام:</strong> يتم بناء وتشغيل الكود داخل حاوية Docker مؤقتة ومجردة بالكامل من أي وصول لنظام الملفات أو قاعدة بيانات ووردبريس.</li>
							<li><strong>مهلة زمنية محددة (Execution Timeout):</strong> حد أقصى 5 ثوانٍ للتنفيذ لمنع الحلقات اللانهائية (Infinite Loops) واستنزاف المعالج.</li>
							<li><strong>حجم كود محدد:</strong> سعة برمجية تصل إلى 64 كيلوبايت كافية للمشاريع والخوارزميات والتحديات البرمجية.</li>
						</ul>
					</div>

					<!-- Language FAQ -->
					<?php if ( ! empty( $language['faq'] ) ) : ?>
						<h3 style="font-size: var(--font-size-xl, 1.25rem); margin-block-end: var(--space-4, 1rem);">
							الأسئلة الشائعة حول تشغيل <?php echo esc_html( $language['name'] ); ?>
						</h3>
						<div style="margin-block-end: var(--space-8, 2rem);">
							<?php foreach ( $language['faq'] as $faq_item ) : ?>
								<div class="cdt-faq-item is-open">
									<button class="cdt-faq-question">
										<span><?php echo esc_html( $faq_item['q'] ); ?></span>
										<span>▾</span>
									</button>
									<div class="cdt-faq-answer">
										<?php echo esc_html( $faq_item['a'] ); ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<!-- Sidebar Info -->
				<div>
					<div style="background: var(--brand-surface, #F1F5F9); border: 1px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-sm, 10px); padding: var(--space-5, 1.25rem); margin-block-end: var(--space-6, 1.5rem);">
						<h4 style="font-size: var(--font-size-md, 1rem); margin-block-end: var(--space-3, 0.75rem);">اختصارات لوحة المفاتيح:</h4>
						<ul style="list-style: none; padding: 0; margin: 0; font-size: 13px; line-height: 2;">
							<li><kbd style="background: #E2E8F0; padding: 2px 6px; border-radius: 4px; font-family: monospace;">Ctrl + Enter</kbd> : تشغيل الكود مباشرة</li>
							<li><kbd style="background: #E2E8F0; padding: 2px 6px; border-radius: 4px; font-family: monospace;">Tab</kbd> : إدراج 4 مسافات برمجية</li>
							<li><kbd style="background: #E2E8F0; padding: 2px 6px; border-radius: 4px; font-family: monospace;">Esc</kbd> : مغادرة وضع ملء الشاشة</li>
						</ul>
					</div>

					<!-- Other Languages Quick Links -->
					<div style="background: var(--brand-white, #FFFFFF); border: 1px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-sm, 10px); padding: var(--space-5, 1.25rem);">
						<h4 style="font-size: var(--font-size-md, 1rem); margin-block-end: var(--space-3, 0.75rem);">اللغات البرمجية الأخرى (15 لغة):</h4>
						<div style="display: flex; flex-wrap: wrap; gap: 6px;">
							<?php foreach ( $all_languages as $l_slug => $l_def ) : ?>
								<a href="<?php echo esc_url( home_url( '/editor/' . $l_slug . '/' ) ); ?>" class="cdt-btn cdt-btn-secondary cdt-btn-sm" style="<?php echo $l_slug === $active_lang_slug ? 'background: var(--brand-primary); color: #fff;' : ''; ?>">
									<?php echo esc_html( $l_def['name'] ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
