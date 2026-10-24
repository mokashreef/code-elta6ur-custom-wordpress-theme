<?php
/**
 * Template Name: كود التطور: الكورسات التعليمية
 * Template Post Type: page
 *
 * قالب مخصص لعرض فهرس الدورات والمسارات البرمجية الشاملة لمنصة كود التطور
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<!-- Course Index Hero -->
<div class="cdt-page-hero">
	<div class="cdt-container">
		<div class="cdt-hero-badge" style="margin-block-end: var(--space-3, 0.75rem);">
			<span class="cdt-badge cdt-badge-accent"><?php esc_html_e( 'المسارات البرمجية المتكاملة', 'code-elta6ur' ); ?></span>
		</div>
		<h1 class="cdt-page-hero-title">
			<?php esc_html_e( 'الكورسات التعليمية المجانية من الصفر إلى الاحتراف', 'code-elta6ur' ); ?>
		</h1>
		<p class="cdt-page-hero-desc">
			<?php esc_html_e( 'دورات برمجية تفاعلية وممنهجة باللغة العربية، تركز على التطبيق العملي وبناء مشاريع واقعية تؤهلك لسوق العمل بثقة.', 'code-elta6ur' ); ?>
		</p>
	</div>
</div>

<?php cdt_breadcrumbs(); ?>

<!-- Courses Stats Bar -->
<section class="cdt-stats-bar" aria-label="<?php esc_attr_e( 'إحصائيات الكورسات', 'code-elta6ur' ); ?>">
	<div class="cdt-container">
		<div class="cdt-stats-grid">
			<div class="cdt-stat-item">
				<div class="cdt-stat-number">7+</div>
				<div class="cdt-stat-label"><?php esc_html_e( 'مسارات برمجية كاملة', 'code-elta6ur' ); ?></div>
			</div>
			<div class="cdt-stat-item">
				<div class="cdt-stat-number">+160</div>
				<div class="cdt-stat-label"><?php esc_html_e( 'درساً تدريبياً ومقالاً تفصيلياً', 'code-elta6ur' ); ?></div>
			</div>
			<div class="cdt-stat-item">
				<div class="cdt-stat-number">100%</div>
				<div class="cdt-stat-label"><?php esc_html_e( 'محتوى عربي وعملي مجاناً', 'code-elta6ur' ); ?></div>
			</div>
			<div class="cdt-stat-item">
				<div class="cdt-stat-number">0$</div>
				<div class="cdt-stat-label"><?php esc_html_e( 'بدون أي رسوم أو اشتراكات', 'code-elta6ur' ); ?></div>
			</div>
		</div>
	</div>
</section>

<!-- Courses Grid Section -->
<section class="cdt-section" aria-labelledby="courses-grid-heading">
	<div class="cdt-container">
		<div class="cdt-section-header">
			<div class="cdt-section-badge">
				<span class="cdt-badge"><?php esc_html_e( 'جميع الدورات', 'code-elta6ur' ); ?></span>
			</div>
			<h2 id="courses-grid-heading" class="cdt-section-title">
				<?php esc_html_e( 'اختر المسار البرمجي وابدأ رحلتك الآن', 'code-elta6ur' ); ?>
			</h2>
			<p class="cdt-section-desc">
				<?php esc_html_e( 'كل دورة تشمل شروحات نظرية مبسطة، أمثلة كود قابلة للنسخ، ومشاريع حقيقية خطوة بخطوة.', 'code-elta6ur' ); ?>
			</p>
		</div>

		<div class="cdt-grid-3">
			<?php
			$all_courses = array(
				array(
					'title' => esc_html__( 'كورس HTML الشامل', 'code-elta6ur' ),
					'url'   => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-html/' ),
					'badge' => esc_html__( 'بناء الهيكل (HTML5)', 'code-elta6ur' ),
					'desc'  => esc_html__( 'تعلم لغة هيكلة الويب القياسية HTML5 مع أحدث المعايير وممارسات إمكانية الوصول وبناء العناصر الدلالية.', 'code-elta6ur' ),
					'count' => esc_html__( '17 درساً تدريبياً', 'code-elta6ur' ),
				),
				array(
					'title' => esc_html__( 'كورس CSS المتقدم', 'code-elta6ur' ),
					'url'   => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-css/' ),
					'badge' => esc_html__( 'التصميم والتنسيق (CSS3)', 'code-elta6ur' ),
					'desc'  => esc_html__( 'أتقن Flexbox و CSS Grid والأنيميشن والتأثيرات والمتغيرات وبناء واجهات متجاوبة مذهلة لجميع الشاشات.', 'code-elta6ur' ),
					'count' => esc_html__( '37 درساً تدريبياً', 'code-elta6ur' ),
				),
				array(
					'title' => esc_html__( 'كورس JavaScript الحديثة', 'code-elta6ur' ),
					'url'   => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-javascript/' ),
					'badge' => esc_html__( 'لغة الويب الأولى (ES6+)', 'code-elta6ur' ),
					'desc'  => esc_html__( 'افهم لغة البرمجة الأكثر انتشاراً عالمياً من الأساسيات وحتى الدوال غير المتزامنة والتعامل مع DOM و APIs.', 'code-elta6ur' ),
					'count' => esc_html__( '32 درساً تدريبياً', 'code-elta6ur' ),
				),
				array(
					'title' => esc_html__( 'كورس React للمحترفين', 'code-elta6ur' ),
					'url'   => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-react/' ),
					'badge' => esc_html__( 'مكتبة الواجهات (React 18)', 'code-elta6ur' ),
					'desc'  => esc_html__( 'تعلم بناء تطبيقات الويب أحادية الصفحة (SPA) مع Hooks و State Management وبناء المكونات وإعادة استخدامها.', 'code-elta6ur' ),
					'count' => esc_html__( '32 درساً تدريبياً', 'code-elta6ur' ),
				),
				array(
					'title' => esc_html__( 'كورس Bootstrap 5 المتجاوب', 'code-elta6ur' ),
					'url'   => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-bootstrap/' ),
					'badge' => esc_html__( 'إطار عمل CSS', 'code-elta6ur' ),
					'desc'  => esc_html__( 'تعلم بناء واجهات سريعة التجاوب وأنيقة باستخدام نظام الشبكة Grid والمكونات الجاهزة في Bootstrap 5.', 'code-elta6ur' ),
					'count' => esc_html__( '25 درساً تدريبياً', 'code-elta6ur' ),
				),
				array(
					'title' => esc_html__( 'كورس لغة C++ للمبتدئين', 'code-elta6ur' ),
					'url'   => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-c/' ),
					'badge' => esc_html__( 'التأسيس البرمجي والخوارزميات', 'code-elta6ur' ),
					'desc'  => esc_html__( 'تأسيس برمجي صلب يفهمك كيف يعمل الحاسوب والذاكرة والمؤشرات والبرمجة كائنية التوجه (OOP).', 'code-elta6ur' ),
					'count' => esc_html__( '31 درساً تدريبياً', 'code-elta6ur' ),
				),
				array(
					'title' => esc_html__( 'دورة TypeScript الشاملة من الصفر', 'code-elta6ur' ),
					'url'   => home_url( '/typescript/' ),
					'badge' => esc_html__( 'لغة الويب المتقدمة (TypeScript)', 'code-elta6ur' ),
					'desc'  => esc_html__( 'شرح عملي عميق لمفاهيم TypeScript، أنواع البيانات الثابتة، الواجهات (Interfaces)، ودعمها مع مشاريع الويب الحديثة.', 'code-elta6ur' ),
					'count' => esc_html__( 'مسار تدريبي متكامل', 'code-elta6ur' ),
				),
			);

			foreach ( $all_courses as $c ) {
				get_template_part( 'template-parts/cards/card-course', null, $c );
			}
			?>
		</div>
	</div>
</section>

<!-- Learning Roadmap Guide -->
<section class="cdt-section cdt-section-alt" aria-labelledby="roadmap-heading">
	<div class="cdt-container">
		<div class="cdt-section-header">
			<div class="cdt-section-badge">
				<span class="cdt-badge cdt-badge-dark"><?php esc_html_e( 'خطة التعلم', 'code-elta6ur' ); ?></span>
			</div>
			<h2 id="roadmap-heading" class="cdt-section-title">
				<?php esc_html_e( 'خارطة الطريق الموصى بها لمطوري واجهات الويب', 'code-elta6ur' ); ?>
			</h2>
			<p class="cdt-section-desc">
				<?php esc_html_e( 'إذا كنت تبدأ من الصفر تماماً، ننصحك باتباع هذا الترتيب للحصول على أقصى استفادة عملية.', 'code-elta6ur' ); ?>
			</p>
		</div>

		<div class="cdt-grid-4">
			<div class="cdt-card" style="padding: var(--space-6, 1.5rem); text-align: center; border-block-start: 4px solid var(--brand-primary, #0170B9);">
				<div style="font-size: 2rem; font-weight: 800; color: var(--brand-primary, #0170B9); margin-block-end: var(--space-2, 0.5rem);">01</div>
				<h3 style="font-size: 1.2rem; margin-block-end: var(--space-2, 0.5rem);"><?php esc_html_e( 'الأساسيات: HTML & CSS', 'code-elta6ur' ); ?></h3>
				<p style="font-size: 0.9rem; color: var(--brand-text-muted, #475569); margin-block-end: 0;">
					<?php esc_html_e( 'ابدأ بهيكلة الصفحات وتنسيقها وصناعة صفحات هبوط متجاوبة مع كافة الشاشات.', 'code-elta6ur' ); ?>
				</p>
			</div>

			<div class="cdt-card" style="padding: var(--space-6, 1.5rem); text-align: center; border-block-start: 4px solid var(--brand-secondary, #3B82F6);">
				<div style="font-size: 2rem; font-weight: 800; color: var(--brand-secondary, #3B82F6); margin-block-end: var(--space-2, 0.5rem);">02</div>
				<h3 style="font-size: 1.2rem; margin-block-end: var(--space-2, 0.5rem);"><?php esc_html_e( 'السرعة: Bootstrap 5', 'code-elta6ur' ); ?></h3>
				<p style="font-size: 0.9rem; color: var(--brand-text-muted, #475569); margin-block-end: 0;">
					<?php esc_html_e( 'تعلم بناء واجهات عمل احترافية بسرعة وبأقل جهد عبر الأنماط الجاهزة ونظام الشبكة.', 'code-elta6ur' ); ?>
				</p>
			</div>

			<div class="cdt-card" style="padding: var(--space-6, 1.5rem); text-align: center; border-block-start: 4px solid var(--brand-accent, #38BDF8);">
				<div style="font-size: 2rem; font-weight: 800; color: var(--brand-accent, #38BDF8); margin-block-end: var(--space-2, 0.5rem);">03</div>
				<h3 style="font-size: 1.2rem; margin-block-end: var(--space-2, 0.5rem);"><?php esc_html_e( 'التفاعل: JavaScript', 'code-elta6ur' ); ?></h3>
				<p style="font-size: 0.9rem; color: var(--brand-text-muted, #475569); margin-block-end: 0;">
					<?php esc_html_e( 'أضف الحيوية والمنطق التفاعلي لموقعك، وتعامل مع البيانات الخارجية وجلب الـ APIs.', 'code-elta6ur' ); ?>
				</p>
			</div>

			<div class="cdt-card" style="padding: var(--space-6, 1.5rem); text-align: center; border-block-start: 4px solid var(--brand-dark, #0F172A);">
				<div style="font-size: 2rem; font-weight: 800; color: var(--brand-dark, #0F172A); margin-block-end: var(--space-2, 0.5rem);">04</div>
				<h3 style="font-size: 1.2rem; margin-block-end: var(--space-2, 0.5rem);"><?php esc_html_e( 'الاحتراف: React.js', 'code-elta6ur' ); ?></h3>
				<p style="font-size: 0.9rem; color: var(--brand-text-muted, #475569); margin-block-end: 0;">
					<?php esc_html_e( 'ابنِ تطبيقات ويب كاملة وضخمة تنافس أفضل المواقع العالمية وتؤهلك لأعلى الوظائف.', 'code-elta6ur' ); ?>
				</p>
			</div>
		</div>
	</div>
</section>

<!-- Global CTA -->
<section class="cdt-cta-section" aria-label="<?php esc_attr_e( 'دعوة لاتخاذ إجراء', 'code-elta6ur' ); ?>">
	<div class="cdt-container">
		<div class="cdt-cta-inner">
			<h2 class="cdt-cta-title">
				<?php esc_html_e( 'هل لديك سؤال أو تحتاج لمساعدة في مسارك التعليمي؟', 'code-elta6ur' ); ?>
			</h2>
			<p class="cdt-cta-desc">
				<?php esc_html_e( 'تواصل معنا مباشرة عبر واتساب أو تيليجرام وسنكون سعداء بإرشادك ودعمك في أي خطوة.', 'code-elta6ur' ); ?>
			</p>
			<a href="<?php echo esc_url( home_url( '/%d8%a7%d8%aa%d8%b5%d9%84-%d8%a8%d9%86%d8%a7/' ) ); ?>" class="cdt-btn cdt-btn-cta cdt-btn-lg">
				<?php esc_html_e( 'تواصل معنا الآن', 'code-elta6ur' ); ?>
				<?php echo cdt_svg_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</div>
	</div>
</section>

<?php
get_footer();
