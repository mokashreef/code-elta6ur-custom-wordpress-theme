<?php
/**
 * Template Name: كود التطور: من نحن
 * Template Post Type: page
 *
 * قالب صفحة التعريف بالمنصة والمؤسس ورسالة كود التطور
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<!-- About Hero -->
<div class="cdt-page-hero">
	<div class="cdt-container">
		<div class="cdt-hero-badge" style="margin-block-end: var(--space-3, 0.75rem);">
			<span class="cdt-badge cdt-badge-accent"><?php esc_html_e( 'الرؤية والرسالة التعليمية', 'code-elta6ur' ); ?></span>
		</div>
		<h1 class="cdt-page-hero-title">
			<?php esc_html_e( 'عن منصة كود التطور التعليمية', 'code-elta6ur' ); ?>
		</h1>
		<p class="cdt-page-hero-desc">
			<?php esc_html_e( 'نبني جيلاً من المبرمجين العرب القادرين على الفهم العميق، حل المشكلات، وصناعة منتجات برمجية حقيقية تنافس عالمياً.', 'code-elta6ur' ); ?>
		</p>
	</div>
</div>

<?php cdt_breadcrumbs(); ?>

<!-- Mission & Vision Section -->
<section class="cdt-section">
	<div class="cdt-container-narrow">
		<div class="cdt-card" style="padding: var(--space-8, 2rem) var(--space-10, 2.5rem); margin-block-end: var(--space-12, 3rem);">
			<h2 style="font-size: var(--font-size-2xl, 1.5rem); color: var(--brand-dark, #0F172A); margin-block-end: var(--space-4, 1rem);">
				<?php esc_html_e( 'رسالتنا: كسر حاجز الخوف من البرمجة', 'code-elta6ur' ); ?>
			</h2>
			<p style="font-size: 1.05rem; line-height: 1.9; color: var(--brand-text-muted, #475569); margin-block-end: var(--space-4, 1rem);">
				<?php esc_html_e( 'انطلقت منصة "كود التطور" بإيمان راسخ بأن البرمجة ليست مجرد كتابة أكواد معقدة، بل هي أسلوب تفكير منطقي وأداة لبناء المستقبل. نحن نرفض الحفظ الأعمى والدروس التقليدية الجافة، ونركز على الفهم الهندسي من الصفر حتى يستطيع كل طالب فهم ما يدور خلف الكواليس.', 'code-elta6ur' ); ?>
			</p>
			<p style="font-size: 1.05rem; line-height: 1.9; color: var(--brand-text-muted, #475569); margin-block-end: 0;">
				<?php esc_html_e( 'نوفر محتوى تعليمياً عربياً عالي الجودة ومجانياً 100%، يشمل مسارات متكاملة في تطوير الويب (HTML, CSS, JavaScript, React, Bootstrap) والبرمجة التأسيسية (C++)، بالإضافة لدورات TypeScript والذكاء الاصطناعي.', 'code-elta6ur' ); ?>
			</p>
		</div>
	</div>
</section>

<!-- Core 4 Pillars -->
<section class="cdt-section cdt-section-alt" aria-labelledby="pillars-heading">
	<div class="cdt-container">
		<div class="cdt-section-header">
			<div class="cdt-section-badge">
				<span class="cdt-badge"><?php esc_html_e( 'ركائزنا الأربعة', 'code-elta6ur' ); ?></span>
			</div>
			<h2 id="pillars-heading" class="cdt-section-title">
				<?php esc_html_e( 'المبادئ التي بنيت عليها منصة كود التطور', 'code-elta6ur' ); ?>
			</h2>
			<p class="cdt-section-desc">
				<?php esc_html_e( 'أربعة معايير تحكم كل درس ودورة ومقال نقدمه لطلابنا ومتابعينا.', 'code-elta6ur' ); ?>
			</p>
		</div>

		<div class="cdt-grid-4">
			<?php
			$features = array(
				array(
					'title' => esc_html__( 'تبسيط المفاهيم المعقدة', 'code-elta6ur' ),
					'desc'  => esc_html__( 'شرح تدريجي وواضح للأسس الهندسية بدون تعقيد أو مصطلحات غامضة وغير مفهومة.', 'code-elta6ur' ),
					'icon'  => 'check',
				),
				array(
					'title' => esc_html__( 'تطبيقات ومشاريع حقيقية', 'code-elta6ur' ),
					'desc'  => esc_html__( 'تطبيق عملي خطوة بخطوة مع كل مفهوم، لبناء بورتفوليو احترافي يُبهر أصحاب العمل.', 'code-elta6ur' ),
					'icon'  => 'copy',
				),
				array(
					'title' => esc_html__( 'التأهيل لسوق العمل', 'code-elta6ur' ),
					'desc'  => esc_html__( 'إرشادات عملية في العمل الحر عبر منصات خمسات ومستقل وبناء المسار الوظيفي المربح.', 'code-elta6ur' ),
					'icon'  => 'arrow-left',
				),
				array(
					'title' => esc_html__( 'مواكبة الذكاء الاصطناعي', 'code-elta6ur' ),
					'desc'  => esc_html__( 'تعلم استثمار أدوات AI وتسريع إنتاجيتك كمبرمج حديث يواكب متطلبات العصر الجديد.', 'code-elta6ur' ),
					'icon'  => 'play',
				),
			);

			foreach ( $features as $feat ) {
				get_template_part( 'template-parts/cards/card-feature', null, $feat );
			}
			?>
		</div>
	</div>
</section>

<!-- Code Elta6ur Digital Ecosystem & Network Sites -->
<section class="cdt-section" id="cdt-ecosystem" aria-labelledby="ecosystem-heading">
	<div class="cdt-container">
		<div class="cdt-section-header">
			<div class="cdt-section-badge">
				<span class="cdt-badge cdt-badge-accent"><?php esc_html_e( 'منظومة كود التطور الرقمية', 'code-elta6ur' ); ?></span>
			</div>
			<h2 id="ecosystem-heading" class="cdt-section-title">
				<?php esc_html_e( 'شبكة ومواقع كود التطور المتخصصة', 'code-elta6ur' ); ?>
			</h2>
			<p class="cdt-section-desc">
				<?php esc_html_e( 'منظومة متكاملة من المنصات الرقمية الشقيقة التابعة لكود التطور، صُممت لتمكين المبرمجين، ودعم طلاب المدارس، وتوفير حلول العتاد الصلب والخدمات البرمجية الاحترافية.', 'code-elta6ur' ); ?>
			</p>
		</div>

		<div class="cdt-ecosystem-grid">
			<?php
			$ecosystem_sites = array(
				array(
					'name'        => esc_html__( 'أدوات المبرمج السوري | Code Elta6ur Tools', 'code-elta6ur' ),
					'domain'      => 'code-elta6ur.sy',
					'url'         => 'https://code-elta6ur.sy',
					'tag'         => esc_html__( 'أدوات المطورين والعمل الحر', 'code-elta6ur' ),
					'badge_class' => 'cdt-badge-accent',
					'desc'        => esc_html__( 'منصة متكاملة توفر أدوات رقمية ذكية ومجانية للمطورين والمستقلين: إنشاء السيرة الذاتية الاحترافية (CV Generator)، بناء البورتفوليو، ومولد عروض العمل التقديمية (Proposals) للمستقلين في خمسات ومستقل.', 'code-elta6ur' ),
					'features'    => array(
						esc_html__( 'مولد سيرة ذاتية CV احترافي ومجاني', 'code-elta6ur' ),
						esc_html__( 'بناء بورتفوليو شخصي مميز', 'code-elta6ur' ),
						esc_html__( 'صياغة عروض تقديمية للعمل الحر', 'code-elta6ur' ),
					),
					'btn_text'    => esc_html__( 'زيارة منصة الأدوات', 'code-elta6ur' ),
				),
				array(
					'name'        => esc_html__( 'منصة كود اورا للتعليم التفاعلي | Code Ora', 'code-elta6ur' ),
					'domain'      => 'code-ora.com',
					'url'         => 'https://code-ora.com',
					'tag'         => esc_html__( 'التعليم التفاعلي والاختبارات', 'code-elta6ur' ),
					'badge_class' => 'cdt-badge-primary',
					'desc'        => esc_html__( 'المنصة الشقيقة لكود التطور المتخصصة في التعليم التفاعلي والاختبارات البرمجية (Quizzes) لتقييم الفهم واستيعاب المفاهيم البرمجية، بالإضافة إلى تحديات الكود ومجتمع تفاعلي للمطورين.', 'code-elta6ur' ),
					'features'    => array(
						esc_html__( 'اختبارات برمجية تفاعلية وتقييم ذاتي', 'code-elta6ur' ),
						esc_html__( 'تحديات كود وتطبيقات عملية', 'code-elta6ur' ),
						esc_html__( 'مجتمع تعليمي للمطورين والطلاب', 'code-elta6ur' ),
					),
					'btn_text'    => esc_html__( 'استكشاف منصة كود اورا', 'code-elta6ur' ),
				),
				array(
					'name'        => esc_html__( 'وكالة كود التطور البرمجية | Code Elta6ur Agency', 'code-elta6ur' ),
					'domain'      => 'code-elta6ur.net',
					'url'         => 'https://code-elta6ur.net',
					'tag'         => esc_html__( 'حلول برمجية وتطوير أعمال', 'code-elta6ur' ),
					'badge_class' => 'cdt-badge-dark',
					'desc'        => esc_html__( 'الذراع التقني والخدمي للمجموعة، يقدم حلولاً متطورة لبناء المواقع والأنظمة المخصصة للشركات، تطوير تطبيقات SaaS السحابية، المتاجر الإلكترونية وحلول ووردبريس المتقدمة مع أعلى معايير الأمان والسرعة.', 'code-elta6ur' ),
					'features'    => array(
						esc_html__( 'تطوير تطبيقات الويب السحابية SaaS', 'code-elta6ur' ),
						esc_html__( 'متاجر إلكترونية وحلول ووردبريس', 'code-elta6ur' ),
						esc_html__( 'استشارات رقمية وحلول أعمال مخصصة', 'code-elta6ur' ),
					),
					'btn_text'    => esc_html__( 'زيارة الوكالة البرمجية', 'code-elta6ur' ),
				),
				array(
					'name'        => esc_html__( 'هاردوير بيس | HardwareBase', 'code-elta6ur' ),
					'domain'      => 'hardwarebase.code-elta6ur.com',
					'url'         => 'https://hardwarebase.code-elta6ur.com',
					'tag'         => esc_html__( 'صيانة وهندسة الإلكترونيات', 'code-elta6ur' ),
					'badge_class' => 'cdt-badge-accent',
					'desc'        => esc_html__( 'المرجع التقني المتخصص في هندسة العتاد الصلب وصيانة الهواتف والأجهزة الذكية، يوفر مكتبة شاملة من المخططات الهندسية (Schematics & Boardviews) وقاعدة بيانات لأكواد الأعطال للفنيين والمهندسين.', 'code-elta6ur' ),
					'features'    => array(
						esc_html__( 'مخططات تفصيلية Schematics و Boardviews', 'code-elta6ur' ),
						esc_html__( 'شروحات صيانة الدوائر الإلكترونية', 'code-elta6ur' ),
						esc_html__( 'قاعدة بيانات حلول وأكواد الأعطال', 'code-elta6ur' ),
					),
					'btn_text'    => esc_html__( 'الدخول إلى HardwareBase', 'code-elta6ur' ),
				),
				array(
					'name'        => esc_html__( 'منصة البكالوريا السورية الذكية', 'code-elta6ur' ),
					'domain'      => 'baccalaureate.code-elta6ur.sy',
					'url'         => 'https://baccalaureate.code-elta6ur.sy',
					'tag'         => esc_html__( 'التعليم المدرسي والشهادة الثانوية', 'code-elta6ur' ),
					'badge_class' => 'cdt-badge-primary',
					'desc'        => esc_html__( 'منصة وطنية تعليمية مؤتمتة ومبتكرة موجهة لطلاب الشهادة الثانوية العامة في سوريا، توفر بنكاً شاملاً من الأسئلة والاختبارات المؤتمتة وفق المناهج الحديثة مع تصحيح ذكي فوري وتفسير للإجابات.', 'code-elta6ur' ),
					'features'    => array(
						esc_html__( 'بنك أسئلة واختبارات مؤتمتة شاملة', 'code-elta6ur' ),
						esc_html__( 'تصحيح إلكتروني ذكي وفوري مع التفسير', 'code-elta6ur' ),
						esc_html__( 'دعم مجاني لطلاب الشهادة الثانوية', 'code-elta6ur' ),
					),
					'btn_text'    => esc_html__( 'زيارة منصة البكالوريا', 'code-elta6ur' ),
				),
			);

			foreach ( $ecosystem_sites as $site ) :
				?>
				<div class="cdt-ecosystem-card">
					<div>
						<div class="cdt-ecosystem-header">
							<span class="cdt-badge <?php echo esc_attr( $site['badge_class'] ); ?>">
								<?php echo esc_html( $site['tag'] ); ?>
							</span>
							<span class="cdt-ecosystem-domain">
								<?php echo esc_html( $site['domain'] ); ?>
							</span>
						</div>
						<h3 class="cdt-ecosystem-title">
							<?php echo esc_html( $site['name'] ); ?>
						</h3>
						<p class="cdt-ecosystem-desc">
							<?php echo esc_html( $site['desc'] ); ?>
						</p>
						<ul class="cdt-ecosystem-features">
							<?php foreach ( $site['features'] as $item ) : ?>
								<li><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="cdt-ecosystem-footer">
						<a href="<?php echo esc_url( $site['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="cdt-btn cdt-btn-outline" style="width: 100%; justify-content: center; gap: 8px;">
							<span><?php echo esc_html( $site['btn_text'] ); ?></span>
							<?php echo cdt_svg_icon( 'external-link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Founder Section -->
<section class="cdt-section" aria-labelledby="founder-heading">
	<div class="cdt-container-narrow">
		<div class="cdt-card" style="padding: var(--space-8, 2rem) var(--space-10, 2.5rem); display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: var(--space-8, 2rem); align-items: center;">
			<div>
				<span class="cdt-badge cdt-badge-dark" style="margin-block-end: var(--space-3, 0.75rem);"><?php esc_html_e( 'مؤسس المنصة', 'code-elta6ur' ); ?></span>
				<h2 id="founder-heading" style="font-size: var(--font-size-2xl, 1.5rem); margin-block-end: var(--space-2, 0.5rem); color: var(--brand-dark, #0F172A);">
					Mohammad Abu Khashreef
				</h2>
				<p style="font-weight: 600; color: var(--brand-primary, #0170B9); margin-block-end: var(--space-3, 0.75rem);">
					<?php esc_html_e( 'مهندس برمجيات ومطور ويب متكامل ومصمم واجهات', 'code-elta6ur' ); ?>
				</p>
				<p style="font-size: 0.95rem; color: var(--brand-text-muted, #475569); line-height: 1.8; margin-block-end: var(--space-4, 1rem);">
					<?php esc_html_e( 'مطور ومستقل شغوف بالتعليم التقني، يمتلك سنوات من الخبرة في بناء المواقع والأنظمة وتدريب المئات من الطلاب على احتراف لغات البرمجة وتطوير الويب من البداية وحتى الاستقلال المهني.', 'code-elta6ur' ); ?>
				</p>

				<div style="display: flex; gap: var(--space-2, 0.5rem); flex-wrap: wrap;">
					<a href="https://mohammad.code-elta6ur.com/" target="_blank" rel="noopener noreferrer" class="cdt-btn cdt-btn-primary cdt-btn-sm">
						<?php esc_html_e( 'الموقع الشخصي للمؤسس', 'code-elta6ur' ); ?>
					</a>
					<a href="https://mostaql.com/u/mohammad_qaseem" target="_blank" rel="noopener noreferrer" class="cdt-btn cdt-btn-outline cdt-btn-sm">
						<?php esc_html_e( 'الملف في مستقل', 'code-elta6ur' ); ?>
					</a>
					<a href="https://khamsat.com/user/syr_moha" target="_blank" rel="noopener noreferrer" class="cdt-btn cdt-btn-outline cdt-btn-sm">
						<?php esc_html_e( 'الملف في خمسات', 'code-elta6ur' ); ?>
					</a>
				</div>
			</div>

			<div style="text-align: center; background: var(--brand-surface, #F1F5F9); padding: var(--space-8, 2rem); border-radius: var(--radius-md, 16px);">
				<div style="font-size: 3rem; font-weight: 800; color: var(--brand-primary, #0170B9); line-height: 1;">+370</div>
				<div style="font-weight: 700; color: var(--brand-dark, #0F172A); margin-block: var(--space-2, 0.5rem);">
					<?php esc_html_e( 'مقال ودرس تقني منشور', 'code-elta6ur' ); ?>
				</div>
				<p style="font-size: 0.85rem; color: var(--brand-text-muted, #475569); margin-block-end: 0;">
					<?php esc_html_e( 'تحديثات مستمرة وإثراء دائم للمحتوى العربي البرمجي.', 'code-elta6ur' ); ?>
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
				<?php esc_html_e( 'انضم إلى مجتمع كود التطور اليوم', 'code-elta6ur' ); ?>
			</h2>
			<p class="cdt-cta-desc">
				<?php esc_html_e( 'ابدأ التعلم الآن مجاناً وشاركنا رحلتك وتطورك في عالم البرمجة.', 'code-elta6ur' ); ?>
			</p>
			<a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/' ) ); ?>" class="cdt-btn cdt-btn-cta cdt-btn-lg">
				<?php esc_html_e( 'استعرض جميع الكورسات', 'code-elta6ur' ); ?>
				<?php echo cdt_svg_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</div>
	</div>
</section>

<?php
get_footer();
