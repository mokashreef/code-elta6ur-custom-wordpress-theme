<?php
/**
 * القالب الرسمي لصفحة هبوط الأدوات التقنية (/tools/)
 * Code Elta6ur Tools Hub Landing Page
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$categories = cdt_get_tool_categories();
$tools      = cdt_get_tools_registry();
$total_cnt  = count( $tools );
?>

<!-- 1. Tools Hero Section -->
<section class="cdt-tools-hero">
	<div class="cdt-container">
		<div class="cdt-tools-hero-badge">
			<span>⚡ 30 أداة تقنية وبرمجية سريعة</span>
		</div>
		<h1 class="cdt-tools-hero-title">
			أدوات المطورين والبرمجة <span>أونلاين مجاناً</span>
		</h1>
		<p class="cdt-tools-hero-desc">
			مجموعة متكاملة من 30 أداة هندسية وبرمجية تعمل بالكامل داخل متصفحك بأمان وخصوصية 100% لتسريع إنتاجيتك وتطوير تطبيقاتك دون إرسال بياناتك إلى الخادم.
		</p>

		<!-- Live Search Box -->
		<div class="cdt-tools-search-box">
			<span class="cdt-tools-search-icon" aria-hidden="true">
				<?php echo cdt_svg_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
			<input type="search" id="cdt-tools-search" class="cdt-tools-search-input" placeholder="ابحث عن أداة برمجية (مثل: JSON, Regex, cURL, Docker, SQL)..." aria-label="<?php esc_attr_e( 'البحث في الأدوات', 'code-elta6ur' ); ?>">
		</div>

		<!-- Category Filter Chips -->
		<div class="cdt-tools-categories" role="tablist" aria-label="<?php esc_attr_e( 'تصنيفات الأدوات', 'code-elta6ur' ); ?>">
			<button class="cdt-tool-cat-btn is-active" data-cat-slug="all" role="tab" aria-selected="true">
				<span>الكل</span>
				<span class="cdt-tool-cat-count"><?php echo esc_html( $total_cnt ); ?></span>
			</button>
			<?php foreach ( $categories as $cat_slug => $cat ) :
				$cat_tools = cdt_get_tools_by_category( $cat_slug );
				$count     = count( $cat_tools );
				?>
				<button class="cdt-tool-cat-btn" data-cat-slug="<?php echo esc_attr( $cat_slug ); ?>" role="tab" aria-selected="false">
					<span><?php echo esc_html( $cat['name'] ); ?></span>
					<span class="cdt-tool-cat-count"><?php echo esc_html( $count ); ?></span>
				</button>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Breadcrumbs -->
<?php cdt_breadcrumbs(); ?>

<!-- 2. Main Tools Catalog -->
<main class="cdt-section" style="padding-block: var(--space-8, 2rem) var(--space-12, 3rem);">
	<div class="cdt-container">

		<!-- Privacy Guarantee Notice -->
		<div class="cdt-privacy-banner">
			<div class="cdt-privacy-banner-info">
				<div class="cdt-privacy-banner-icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
				</div>
				<div class="cdt-privacy-banner-text">
					<strong>خصوصية تامة وأمان 100%</strong>
					<span>جميع العمليات ومعالجة البيانات تتم محلياً في متصفحك (Client-side) دون حفظ أو نقل مدخلاتك.</span>
				</div>
			</div>
			<div>
				<a href="<?php echo esc_url( home_url( '/editor/' ) ); ?>" class="cdt-btn cdt-btn-secondary cdt-btn-sm">
					<span>جرّب محرر الأكواد (15 لغة) &rarr;</span>
				</a>
			</div>
		</div>

		<!-- Tools Grid (30 Cards) -->
		<div class="cdt-tools-grid" id="cdt-tools-grid">
			<?php foreach ( $tools as $slug => $tool ) :
				$tool_url = home_url( '/tools/' . $slug . '/' );
				$cat_info = isset( $categories[ $tool['category'] ] ) ? $categories[ $tool['category'] ] : null;
				$cat_name = $cat_info ? $cat_info['name'] : $tool['category'];
				?>
				<a href="<?php echo esc_url( $tool_url ); ?>" class="cdt-tool-card" data-category="<?php echo esc_attr( $tool['category'] ); ?>" data-name="<?php echo esc_attr( $tool['name'] ); ?>" data-name-en="<?php echo esc_attr( $tool['name_en'] ); ?>" data-desc="<?php echo esc_attr( $tool['tagline'] ); ?>">
					<div>
						<div class="cdt-tool-card-top">
							<div class="cdt-tool-card-icon" aria-hidden="true">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>
							</div>
							<span class="cdt-tool-card-badge"><?php echo esc_html( $cat_name ); ?></span>
						</div>
						<h2 class="cdt-tool-card-title">
							<?php echo esc_html( $tool['name'] ); ?>
							<span class="cdt-tool-card-title-en"><?php echo esc_html( $tool['name_en'] ); ?></span>
						</h2>
						<p class="cdt-tool-card-desc">
							<?php echo esc_html( $tool['tagline'] ); ?>
						</p>
					</div>
					<div class="cdt-tool-card-footer">
						<span>فتح واستخدام الأداة</span>
						<?php echo cdt_svg_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</a>
			<?php endforeach; ?>
		</div>

		<!-- 3. Tools FAQ Section -->
		<section class="cdt-tool-faq" aria-labelledby="tools-faq-title">
			<div style="text-align: center; margin-block-end: var(--space-8, 2rem);">
				<span class="cdt-badge"><?php esc_html_e( 'الأسئلة الشائعة', 'code-elta6ur' ); ?></span>
				<h2 id="tools-faq-title" style="font-size: var(--font-size-2xl, 1.5rem); margin-block-start: var(--space-2, 0.5rem);">
					كل ما تود معرفته عن أدوات كود التطور
				</h2>
			</div>

			<div style="max-width: 860px; margin-inline: auto;">
				<div class="cdt-faq-item is-open">
					<button class="cdt-faq-question">
						<span>هل أدوات كود التطور مجانية تماماً؟</span>
						<span>▾</span>
					</button>
					<div class="cdt-faq-answer">
						نعم، جميع الأدوات الـ 30 في المنصة مجانية بنسبة 100% ولا تتطلب أي اشتراك أو تسجيل دخول أو رسوم خفية.
					</div>
				</div>

				<div class="cdt-faq-item">
					<button class="cdt-faq-question">
						<span>كيف تضمنون خصوصية وسرية بياناتي عند استخدام الأدوات؟</span>
						<span>▾</span>
					</button>
					<div class="cdt-faq-answer">
						تمت هندسة الأدوات لتعمل بنسبة 100% Client-Side داخل متصفحك باستخدام تقنيات JavaScript الحديثة. لا يتم إرسال أي نص، توكن JWT، استعلام SQL، أو كود JSON إلى خوادمنا إطلاقاً.
					</div>
				</div>

				<div class="cdt-faq-item">
					<button class="cdt-faq-question">
						<span>هل يمكنني استخدام الأدوات من هاتفي المحمول؟</span>
						<span>▾</span>
					</button>
					<div class="cdt-faq-answer">
						بالتأكيد. تم تصميم جميع الأدوات وفق نهج Mobile-First لتعمل بكفاءة وسلاسة تامة على الهواتف الذكية والأجهزة اللوحية وحواسيب سطح المكتب.
					</div>
				</div>

				<div class="cdt-faq-item">
					<button class="cdt-faq-question">
						<span>هل هناك محرر لتشغيل الأكواد البرمجية مباشرة؟</span>
						<span>▾</span>
					</button>
					<div class="cdt-faq-answer">
						نعم! توفر منصة كود التطور محرراً ومترجماً متكاملاً <a href="<?php echo esc_url( home_url( '/editor/' ) ); ?>">Online Code Editor</a> يدعم 15 لغة برمجية تشمل Python, JavaScript, TypeScript, C++, C#, Java, Go, Rust وغيرها.
					</div>
				</div>
			</div>
		</section>

	</div>
</main>

<?php
get_footer();
