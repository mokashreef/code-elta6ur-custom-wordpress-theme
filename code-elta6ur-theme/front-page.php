<?php
/**
 * القالب المخصص للصفحة الرئيسية لمنصة كود التطور
 * Code Elta6ur Front Page Template
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<!-- 1. Hero Section -->
<?php get_template_part( 'template-parts/hero/hero-home' ); ?>

<!-- 2. Platform Highlights / Statistics Bar -->
<section class="cdt-stats-bar" aria-label="<?php esc_attr_e( 'إحصائيات المنصة', 'code-elta6ur' ); ?>">
	<div class="cdt-container">
		<div class="cdt-stats-grid">
			<div class="cdt-stat-item">
				<div class="cdt-stat-number">+370</div>
				<div class="cdt-stat-label"><?php esc_html_e( 'درس ومقال برمجي مجاني', 'code-elta6ur' ); ?></div>
			</div>
			<div class="cdt-stat-item">
				<div class="cdt-stat-number">6+</div>
				<div class="cdt-stat-label"><?php esc_html_e( 'مسارات تعليمية متكاملة', 'code-elta6ur' ); ?></div>
			</div>
			<div class="cdt-stat-item">
				<div class="cdt-stat-number">100%</div>
				<div class="cdt-stat-label"><?php esc_html_e( 'محتوى عربي وعملي', 'code-elta6ur' ); ?></div>
			</div>
			<div class="cdt-stat-item">
				<div class="cdt-stat-number">0$</div>
				<div class="cdt-stat-label"><?php esc_html_e( 'تعلم مجاني من الصفر', 'code-elta6ur' ); ?></div>
			</div>
		</div>
	</div>
</section>

<!-- 3. Core Features & Methodology -->
<section class="cdt-section" aria-labelledby="features-heading">
	<div class="cdt-container">
		<div class="cdt-section-header">
			<div class="cdt-section-badge">
				<span class="cdt-badge"><?php esc_html_e( 'لماذا كود التطور؟', 'code-elta6ur' ); ?></span>
			</div>
			<h2 id="features-heading" class="cdt-section-title">
				<?php esc_html_e( 'منهجية برمجية تركز على فهمك وبناء مستقبلك', 'code-elta6ur' ); ?>
			</h2>
			<p class="cdt-section-desc">
				<?php esc_html_e( 'نساعدك على كسر حاجز الخوف من الأكواد وبناء مشاريع حقيقية تؤهلك لسوق العمل بثقة.', 'code-elta6ur' ); ?>
			</p>
		</div>

		<div class="cdt-grid-4">
			<?php
			$features = array(
				array(
					'title' => esc_html__( 'تبسيط المفاهيم المعقدة', 'code-elta6ur' ),
					'desc'  => esc_html__( 'شرح تدريجي وواضح للأسس الهندسية بدون تعقيد أو مصطلحات غامضة.', 'code-elta6ur' ),
					'icon'  => 'check',
				),
				array(
					'title' => esc_html__( 'تطبيقات ومشاريع حقيقية', 'code-elta6ur' ),
					'desc'  => esc_html__( 'تطبيق عملي خطوة بخطوة مع كل مفهوم، لبناء بورتفوليو احترافي يُبهر أصحاب العمل.', 'code-elta6ur' ),
					'icon'  => 'copy',
				),
				array(
					'title' => esc_html__( 'التأهيل لسوق العمل', 'code-elta6ur' ),
					'desc'  => esc_html__( 'نصائح حقيقية وإرشادات في العمل الحر (Freelancing) وبناء المسار الوظيفي المربح.', 'code-elta6ur' ),
					'icon'  => 'arrow-left',
				),
				array(
					'title' => esc_html__( 'مواكبة الذكاء الاصطناعي', 'code-elta6ur' ),
					'desc'  => esc_html__( 'تعلم استثمار أدوات AI وتسريع إنتاجيتك كمبرمج حديث يواكب متطلبات العصر.', 'code-elta6ur' ),
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

<!-- 4. Featured Courses Section -->
<section class="cdt-section cdt-section-alt" aria-labelledby="courses-heading">
	<div class="cdt-container">
		<div class="cdt-section-header">
			<div class="cdt-section-badge">
				<span class="cdt-badge cdt-badge-accent"><?php esc_html_e( 'المسارات التعليمية', 'code-elta6ur' ); ?></span>
			</div>
			<h2 id="courses-heading" class="cdt-section-title">
				<?php esc_html_e( 'ابدأ التعلم من المسار الذي يناسبك', 'code-elta6ur' ); ?>
			</h2>
			<p class="cdt-section-desc">
				<?php esc_html_e( 'دورات تعليمية مصممة خطوة بخطوة من الصفر وحتى الاحتراف.', 'code-elta6ur' ); ?>
			</p>
		</div>

		<div class="cdt-grid-3">
			<?php
			$courses = array(
				array(
					'title' => esc_html__( 'كورس HTML الشامل', 'code-elta6ur' ),
					'url'   => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-html/' ),
					'badge' => esc_html__( 'بناء الهيكل', 'code-elta6ur' ),
					'desc'  => esc_html__( 'تعلم لغة هيكلة الويب القياسية HTML5 مع أحدث المعايير وممارسات إمكانية الوصول.', 'code-elta6ur' ),
					'count' => esc_html__( '17 درساً تدريبياً', 'code-elta6ur' ),
				),
				array(
					'title' => esc_html__( 'كورس CSS المتقدم', 'code-elta6ur' ),
					'url'   => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-css/' ),
					'badge' => esc_html__( 'التصميم والتنسيق', 'code-elta6ur' ),
					'desc'  => esc_html__( 'أتقن Flexbox و CSS Grid والأنيميشن والمتغيرات وبناء واجهات متجاوبة مذهلة.', 'code-elta6ur' ),
					'count' => esc_html__( '37 درساً تدريبياً', 'code-elta6ur' ),
				),
				array(
					'title' => esc_html__( 'كورس JavaScript الحديثة', 'code-elta6ur' ),
					'url'   => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-javascript/' ),
					'badge' => esc_html__( 'لغة الويب الأولى', 'code-elta6ur' ),
					'desc'  => esc_html__( 'افهم لغة البرمجة الأكثر انتشاراً عالمياً من الأساسيات حتى ES6+ والتعامل مع DOM و APIs.', 'code-elta6ur' ),
					'count' => esc_html__( '32 درساً تدريبياً', 'code-elta6ur' ),
				),
				array(
					'title' => esc_html__( 'كورس React للمحترفين', 'code-elta6ur' ),
					'url'   => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-react/' ),
					'badge' => esc_html__( 'مكتبة الواجهات', 'code-elta6ur' ),
					'desc'  => esc_html__( 'تعلم بناء تطبيقات الويب أحادية الصفحة (SPA) مع Hooks و State Management وبناء المكونات.', 'code-elta6ur' ),
					'count' => esc_html__( '32 درساً تدريبياً', 'code-elta6ur' ),
				),
				array(
					'title' => esc_html__( 'كورس Bootstrap 5', 'code-elta6ur' ),
					'url'   => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-bootstrap/' ),
					'badge' => esc_html__( 'إطار عمل CSS', 'code-elta6ur' ),
					'desc'  => esc_html__( 'تعلم بناء واجهات سريعة التجاوب وأنيقة باستخدام أقوى مكتبة CSS في العالم.', 'code-elta6ur' ),
					'count' => esc_html__( '25 درساً تدريبياً', 'code-elta6ur' ),
				),
				array(
					'title' => esc_html__( 'كورس لغة C++ للمبتدئين', 'code-elta6ur' ),
					'url'   => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-c/' ),
					'badge' => esc_html__( 'التأسيس البرمجي', 'code-elta6ur' ),
					'desc'  => esc_html__( 'تأسيس برمجي صلب يفهمك كيف يعمل الحاسوب والذاكرة والبرمجة كائنية التوجه (OOP).', 'code-elta6ur' ),
					'count' => esc_html__( '31 درساً تدريبياً', 'code-elta6ur' ),
				),
			);

			foreach ( $courses as $course ) {
				get_template_part( 'template-parts/cards/card-course', null, $course );
			}
			?>
		</div>

		<div style="text-align: center; margin-block-start: var(--space-10, 2.5rem);">
			<a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/' ) ); ?>" class="cdt-btn cdt-btn-secondary cdt-btn-lg">
				<?php esc_html_e( 'استعراض جميع الكورسات والسلاسل', 'code-elta6ur' ); ?>
				<?php echo cdt_svg_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</div>
	</div>
</section>

<!-- 5. Latest Articles Section -->
<section class="cdt-section" aria-labelledby="articles-heading">
	<div class="cdt-container">
		<div class="cdt-section-header">
			<div class="cdt-section-badge">
				<span class="cdt-badge"><?php esc_html_e( 'أحدث المقالات', 'code-elta6ur' ); ?></span>
			</div>
			<h2 id="articles-heading" class="cdt-section-title">
				<?php esc_html_e( 'جديد المدونة والدروس البرمجية', 'code-elta6ur' ); ?>
			</h2>
			<p class="cdt-section-desc">
				<?php esc_html_e( 'مقالات تقنية مكثفة ومحدثة دورياً في لغات البرمجة والذكاء الاصطناعي وسوق العمل.', 'code-elta6ur' ); ?>
			</p>
		</div>

		<div class="cdt-grid-3">
			<?php
			$recent_posts = new WP_Query(
				array(
					'post_type'           => 'post',
					'posts_per_page'      => 6,
					'ignore_sticky_posts' => true,
					'post_status'         => 'publish',
				)
			);

			if ( $recent_posts->have_posts() ) :
				while ( $recent_posts->have_posts() ) :
					$recent_posts->the_post();
					get_template_part( 'template-parts/cards/card-post' );
				endwhile;
				wp_reset_postdata();
			else :
				get_template_part( 'template-parts/content/content-none' );
			endif;
			?>
		</div>

		<div style="text-align: center; margin-block-start: var(--space-10, 2.5rem);">
			<a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%85%d8%af%d9%88%d9%86%d8%a9/' ) ); ?>" class="cdt-btn cdt-btn-outline cdt-btn-lg">
				<?php esc_html_e( 'عرض أرشيف المقالات كاملاً (+370 مقال)', 'code-elta6ur' ); ?>
				<?php echo cdt_svg_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</div>
	</div>
</section>

<!-- 6. YouTube Showcase Section -->
<section class="cdt-section cdt-section-alt" aria-labelledby="videos-heading">
	<div class="cdt-container">
		<div class="cdt-section-header">
			<div class="cdt-section-badge">
				<span class="cdt-badge cdt-badge-dark"><?php esc_html_e( 'قناة يوتيوب', 'code-elta6ur' ); ?></span>
			</div>
			<h2 id="videos-heading" class="cdt-section-title">
				<?php esc_html_e( 'شروحات مرئية عبر قناة كود التطور', 'code-elta6ur' ); ?>
			</h2>
			<p class="cdt-section-desc">
				<?php esc_html_e( 'دروس مسجلة وتطبيقات عملية مباشرة تنشر باستمرار على قناتنا الرسمية على YouTube.', 'code-elta6ur' ); ?>
			</p>
		</div>

		<div class="cdt-grid-3">
			<?php
			$youtube_videos = array(
				array(
					'video_id' => 'u9Y_8aJd2xU',
					'title'    => esc_html__( 'دورة TypeScript الشاملة من الصفر حتى الاحتراف', 'code-elta6ur' ),
				),
				array(
					'video_id' => 'M7lc1UVf-VE',
					'title'    => esc_html__( 'أساسيات وتطبيقات لغة البرمجة والمشاريع العملية', 'code-elta6ur' ),
				),
				array(
					'video_id' => 'kJQP7kiw5Fk',
					'title'    => esc_html__( 'كيف تبدأ مسارك المهني في تطوير الويب والعمل الحر؟', 'code-elta6ur' ),
				),
			);

			foreach ( $youtube_videos as $vid ) {
				get_template_part( 'template-parts/cards/card-video', null, $vid );
			}
			?>
		</div>

		<div style="text-align: center; margin-block-start: var(--space-10, 2.5rem);">
			<a href="<?php echo esc_url( cdt_get_option( 'cdt_youtube_url' ) ); ?>" target="_blank" rel="noopener noreferrer" class="cdt-btn cdt-btn-primary cdt-btn-lg">
				<?php echo cdt_svg_icon( 'youtube' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span><?php esc_html_e( 'اشترك في قناة يوتيوب الرسمية', 'code-elta6ur' ); ?></span>
			</a>
		</div>
	</div>
</section>

<!-- 7. Global CTA Banner -->
<section class="cdt-cta-section" aria-label="<?php esc_attr_e( 'دعوة لاتخاذ إجراء', 'code-elta6ur' ); ?>">
	<div class="cdt-container">
		<div class="cdt-cta-inner">
			<h2 class="cdt-cta-title">
				<?php esc_html_e( 'جاهز لبدء رحلتك البرمجية معنا اليوم؟', 'code-elta6ur' ); ?>
			</h2>
			<p class="cdt-cta-desc">
				<?php esc_html_e( 'انضم إلى مئات الطلاب والمتعلمين واستفد من الدروس والكورسات المجانية بالكامل.', 'code-elta6ur' ); ?>
			</p>
			<a href="<?php echo esc_url( home_url( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/' ) ); ?>" class="cdt-btn cdt-btn-cta cdt-btn-lg">
				<?php esc_html_e( 'ابدأ التعلم الآن مجاناً', 'code-elta6ur' ); ?>
				<?php echo cdt_svg_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</div>
	</div>
</section>

<?php
get_footer();
