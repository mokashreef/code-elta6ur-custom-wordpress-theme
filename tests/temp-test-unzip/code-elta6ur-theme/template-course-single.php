<?php
/**
 * Template Name: كود التطور: صفحة الكورس وفهرس الدروس
 * Template Post Type: page
 *
 * قالب مخصص لصفحات الكورسات الأساسية وفهرس الدروس (HTML, CSS, JS, Bootstrap, React, C++)
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Determine course data by slug or page ID
$raw_slug  = strtolower( urldecode( get_post_field( 'post_name', get_the_ID() ) ) );
$raw_title = strtolower( get_the_title() );

// Determine matched course key
$matched_key = null;
if ( strpos( $raw_slug, 'html' ) !== false || strpos( $raw_title, 'html' ) !== false ) {
	$matched_key = 'كورس-html';
} elseif ( strpos( $raw_slug, 'bootstrap' ) !== false || strpos( $raw_title, 'bootstrap' ) !== false ) {
	$matched_key = 'كورس-bootstrap';
} elseif ( strpos( $raw_slug, 'css' ) !== false || strpos( $raw_title, 'css' ) !== false ) {
	$matched_key = 'كورس-css';
} elseif ( strpos( $raw_slug, 'javascript' ) !== false || strpos( $raw_slug, 'js' ) !== false || strpos( $raw_title, 'javascript' ) !== false ) {
	$matched_key = 'كورس-javascript';
} elseif ( strpos( $raw_slug, 'react' ) !== false || strpos( $raw_title, 'react' ) !== false ) {
	$matched_key = 'كورس-react';
} elseif ( strpos( $raw_slug, 'typescript' ) !== false || strpos( $raw_title, 'typescript' ) !== false || strpos( $raw_slug, 'تايب-سكريبت' ) !== false || strpos( $raw_title, 'تايب سكريبت' ) !== false ) {
	$matched_key = 'typescript';
} elseif ( strpos( $raw_slug, '-c' ) !== false || strpos( $raw_slug, 'c++' ) !== false || strpos( $raw_slug, 'cplusplus' ) !== false || strpos( $raw_title, 'c++' ) !== false || strpos( $raw_title, 'لغة c' ) !== false ) {
	$matched_key = 'كورس-c';
} else {
	$matched_key = $raw_slug;
}

$courses_data = array(
	'typescript' => array(
		'title'       => esc_html__( 'دورة TypeScript الشاملة من الصفر إلى الاحتراف', 'code-elta6ur' ),
		'badge'       => 'TypeScript',
		'cat_id'      => 119,
		'tag_slug'    => 'typescript',
		'desc'        => esc_html__( 'شرح عملي عميق لمفاهيم TypeScript، أنواع البيانات الثابتة، الواجهات (Interfaces)، ودعمها مع مشاريع الويب الحديثة.', 'code-elta6ur' ),
		'count'       => esc_html__( 'مسار تدريبي متكامل', 'code-elta6ur' ),
		'next_course' => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-react/' ),
		'next_name'   => esc_html__( 'كورس React للمحترفين', 'code-elta6ur' ),
	),
	'كورس-typescript' => array(
		'title'       => esc_html__( 'دورة TypeScript الشاملة من الصفر إلى الاحتراف', 'code-elta6ur' ),
		'badge'       => 'TypeScript',
		'cat_id'      => 119,
		'tag_slug'    => 'typescript',
		'desc'        => esc_html__( 'شرح عملي عميق لمفاهيم TypeScript، أنواع البيانات الثابتة، الواجهات (Interfaces)، ودعمها مع مشاريع الويب الحديثة.', 'code-elta6ur' ),
		'count'       => esc_html__( 'مسار تدريبي متكامل', 'code-elta6ur' ),
		'next_course' => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-react/' ),
		'next_name'   => esc_html__( 'كورس React للمحترفين', 'code-elta6ur' ),
	),
	'كورس-html' => array(
		'title'       => esc_html__( 'كورس تعلم HTML5 بالعربي للمبتدئين', 'code-elta6ur' ),
		'badge'       => 'HTML5',
		'cat_id'      => 53,
		'desc'        => esc_html__( 'تعلم لغة هيكلة الويب القياسية HTML5 مع أحدث المعايير وممارسات إمكانية الوصول وبناء العناصر الدلالية خطوة بخطوة.', 'code-elta6ur' ),
		'count'       => esc_html__( '17 درساً تدريبياً', 'code-elta6ur' ),
		'next_course' => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-css/' ),
		'next_name'   => esc_html__( 'كورس CSS المتقدم', 'code-elta6ur' ),
	),
	'كورس-css' => array(
		'title'       => esc_html__( 'شرح CSS3 بالعربي 2026 وتنسيق الواجهات', 'code-elta6ur' ),
		'badge'       => 'CSS3',
		'cat_id'      => 62,
		'desc'        => esc_html__( 'أتقن Flexbox و CSS Grid والأنيميشن والتأثيرات والمتغيرات وبناء واجهات متجاوبة مذهلة لكافة الشاشات والأجهزة.', 'code-elta6ur' ),
		'count'       => esc_html__( '37 درساً تدريبياً', 'code-elta6ur' ),
		'next_course' => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-bootstrap/' ),
		'next_name'   => esc_html__( 'كورس Bootstrap 5', 'code-elta6ur' ),
	),
	'كورس-bootstrap' => array(
		'title'       => esc_html__( 'شرح Bootstrap 5 بالعربي لبناء واجهات متجاوبة', 'code-elta6ur' ),
		'badge'       => 'Bootstrap 5',
		'cat_id'      => 71,
		'desc'        => esc_html__( 'تعلم بناء واجهات سريعة التجاوب وأنيقة باستخدام نظام الشبكة Grid والمكونات الجاهزة في أقوى مكتبة CSS عالمياً.', 'code-elta6ur' ),
		'count'       => esc_html__( '25 درساً تدريبياً', 'code-elta6ur' ),
		'next_course' => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-javascript/' ),
		'next_name'   => esc_html__( 'كورس JavaScript الحديثة', 'code-elta6ur' ),
	),
	'كورس-javascript' => array(
		'title'       => esc_html__( 'شرح JavaScript بالعربي 2026 من الصفر حتى الاحتراف', 'code-elta6ur' ),
		'badge'       => 'JavaScript ES6+',
		'cat_id'      => 74,
		'desc'        => esc_html__( 'افهم لغة البرمجة الأكثر انتشاراً عالمياً من الأساسيات وحتى البرمجة الكائنية والتعامل مع الـ DOM و APIs غير المتزامنة.', 'code-elta6ur' ),
		'count'       => esc_html__( '32 درساً تدريبياً', 'code-elta6ur' ),
		'next_course' => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-react/' ),
		'next_name'   => esc_html__( 'كورس React للمحترفين', 'code-elta6ur' ),
	),
	'كورس-react' => array(
		'title'       => esc_html__( 'تعلم مكتبة React.js بالعربي وبناء تطبيقات الويب', 'code-elta6ur' ),
		'badge'       => 'React 18',
		'cat_id'      => 76,
		'desc'        => esc_html__( 'تعلم بناء تطبيقات الويب أحادية الصفحة (SPA) مع Hooks و State Management وبناء المكونات وإعادة استخدامها باحترافية.', 'code-elta6ur' ),
		'count'       => esc_html__( '32 درساً تدريبياً', 'code-elta6ur' ),
		'next_course' => home_url( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/' ),
		'next_name'   => esc_html__( 'استعراض كافة المسارات', 'code-elta6ur' ),
	),
	'كورس-c' => array(
		'title'       => esc_html__( 'شرح لغة C++ بالعربي 2026 والتأسيس البرمجي', 'code-elta6ur' ),
		'badge'       => 'C++ Programming',
		'cat_id'      => 80,
		'desc'        => esc_html__( 'تأسيس برمجي صلب يفهمك كيف يعمل الحاسوب والذاكرة والمؤشرات والخوارزميات والبرمجة كائنية التوجه (OOP).', 'code-elta6ur' ),
		'count'       => esc_html__( '31 درساً تدريبياً', 'code-elta6ur' ),
		'next_course' => home_url( '/%d9%83%d9%88%d8%b1%d8%b3-html/' ),
		'next_name'   => esc_html__( 'كورس HTML الشامل', 'code-elta6ur' ),
	),
);

// Fallback if slug not matched exactly
$course_info = isset( $courses_data[ $matched_key ] ) ? $courses_data[ $matched_key ] : array(
	'title'       => get_the_title(),
	'badge'       => 'دورة تدريبية',
	'cat_id'      => 0,
	'desc'        => get_the_excerpt(),
	'count'       => 'دروس تدريبية متكاملة',
	'next_course' => home_url( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/' ),
	'next_name'   => esc_html__( 'جميع الكورسات', 'code-elta6ur' ),
);
?>

<!-- Course Hub Hero -->
<div class="cdt-page-hero">
	<div class="cdt-container">
		<div class="cdt-hero-badge" style="margin-block-end: var(--space-3, 0.75rem);">
			<span class="cdt-badge cdt-badge-accent"><?php echo esc_html( $course_info['badge'] ); ?></span>
			<span class="cdt-badge cdt-badge-dark" style="margin-inline-start: var(--space-2, 0.5rem);"><?php esc_html_e( 'محتوى مجاني 100%', 'code-elta6ur' ); ?></span>
		</div>
		<h1 class="cdt-page-hero-title">
			<?php echo esc_html( $course_info['title'] ); ?>
		</h1>
		<p class="cdt-page-hero-desc">
			<?php echo esc_html( $course_info['desc'] ); ?>
		</p>

		<!-- Course Stats Badges -->
		<div style="display: flex; gap: var(--space-4, 1rem); justify-content: center; flex-wrap: wrap; margin-block-start: var(--space-6, 1.5rem);">
			<div style="background: rgba(255,255,255,0.12); padding: 8px 18px; border-radius: var(--radius-full, 9999px); font-size: 0.9rem; font-weight: 600;">
				📚 <?php echo esc_html( $course_info['count'] ); ?>
			</div>
			<div style="background: rgba(255,255,255,0.12); padding: 8px 18px; border-radius: var(--radius-full, 9999px); font-size: 0.9rem; font-weight: 600;">
				🎯 <?php esc_html_e( 'المستوى: من الصفر حتى الاحتراف', 'code-elta6ur' ); ?>
			</div>
			<div style="background: rgba(255,255,255,0.12); padding: 8px 18px; border-radius: var(--radius-full, 9999px); font-size: 0.9rem; font-weight: 600;">
				💻 <?php esc_html_e( 'تطبيق عملي وأكواد قابلة للنسخ', 'code-elta6ur' ); ?>
			</div>
		</div>
	</div>
</div>

<?php cdt_breadcrumbs(); ?>

<!-- Main Syllabus Section -->
<div class="cdt-section" style="padding-block: var(--space-10, 2.5rem);">
	<div class="cdt-container">

		<!-- Course Overview & Objectives Card -->
		<div class="cdt-card" style="padding: var(--space-8, 2rem) var(--space-10, 2.5rem); margin-block-end: var(--space-10, 2.5rem); background: var(--brand-white, #FFFFFF);">
			<div style="display: flex; align-items: center; gap: var(--space-3, 0.75rem); margin-block-end: var(--space-3, 0.75rem);">
				<span class="cdt-badge cdt-badge-primary"><?php echo esc_html( $course_info['badge'] ); ?></span>
				<span style="font-size: 0.85rem; color: var(--brand-text-subtle, #64748B); font-weight: 600;"><?php esc_html_e( 'دليل ومنهاج المسار التدريبي', 'code-elta6ur' ); ?></span>
			</div>
			<h2 style="font-size: var(--font-size-2xl, 1.5rem); color: var(--brand-dark, #0F172A); margin-block-end: var(--space-4, 1rem);">
				<?php esc_html_e( 'ماذا ستتعلم في هذا المسار البرمجي؟', 'code-elta6ur' ); ?>
			</h2>
			<p style="font-size: 1.05rem; line-height: 1.85; color: var(--brand-text-muted, #475569); margin-block-end: var(--space-4, 1rem);">
				<?php echo esc_html( $course_info['desc'] ); ?>
			</p>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--space-3, 0.75rem); padding-block-start: var(--space-4, 1rem); border-block-start: 1px dashed var(--brand-border, #E2E8F0);">
				<div style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; font-weight: 600; color: var(--brand-dark, #0F172A);">
					<span style="color: var(--brand-primary, #0170B9);">✓</span>
					<span><?php esc_html_e( 'تأسيس برمجي وفهم منطقي عميق', 'code-elta6ur' ); ?></span>
				</div>
				<div style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; font-weight: 600; color: var(--brand-dark, #0F172A);">
					<span style="color: var(--brand-primary, #0170B9);">✓</span>
					<span><?php esc_html_e( 'أكواد وأمثلة تطبيقية خطوة بخطوة', 'code-elta6ur' ); ?></span>
				</div>
				<div style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; font-weight: 600; color: var(--brand-dark, #0F172A);">
					<span style="color: var(--brand-primary, #0170B9);">✓</span>
					<span><?php esc_html_e( 'تمارين وتأهيل حقيقي لسوق العمل', 'code-elta6ur' ); ?></span>
				</div>
			</div>
		</div>

		<!-- Lessons List Header -->
		<div class="cdt-section-header" style="text-align: right; margin-block-end: var(--space-6, 1.5rem);">
			<div class="cdt-section-badge">
				<span class="cdt-badge"><?php esc_html_e( 'فهرس الدروس الكامل', 'code-elta6ur' ); ?></span>
			</div>
			<h2 class="cdt-section-title" style="font-size: var(--font-size-2xl, 1.5rem);">
				<?php esc_html_e( 'دروس ومقالات الدورة (مرتبة بالترتيب الزمني خطوة بخطوة)', 'code-elta6ur' ); ?>
			</h2>
			<p class="cdt-section-desc" style="margin-inline: 0;">
				<?php esc_html_e( 'انقر على أي درس للانتقال مباشرة إلى المقال والشرح التطبيقي الخاص به مع الأكواد التوضيحية.', 'code-elta6ur' ); ?>
			</p>
		</div>

		<!-- Lessons Grid / Timeline -->
		<div class="cdt-lessons-list" style="display: grid; gap: var(--space-3, 0.75rem);">
			<?php
			$cat_id   = isset( $course_info['cat_id'] ) ? (int) $course_info['cat_id'] : 0;
			$tag_slug = isset( $course_info['tag_slug'] ) ? sanitize_title( $course_info['tag_slug'] ) : '';

			$query_args = array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 100,
				'orderby'        => 'date',
				'order'          => 'ASC',
			);

			if ( ! empty( $tag_slug ) && $cat_id > 0 ) {
				$query_args['tax_query'] = array(
					'relation' => 'OR',
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'slug',
						'terms'    => $tag_slug,
					),
					array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $cat_id,
					),
				);
			} elseif ( ! empty( $tag_slug ) ) {
				$query_args['tag'] = $tag_slug;
			} elseif ( $cat_id > 0 ) {
				$query_args['cat'] = $cat_id;
			}

			$lessons_query = new WP_Query( $query_args );
			$lesson_index = 1;

			if ( $lessons_query->have_posts() ) :
				while ( $lessons_query->have_posts() ) :
					$lessons_query->the_post();
					$reading_time = cdt_get_reading_time();
					$is_lesson_done = is_user_logged_in() && function_exists( 'cdt_is_lesson_completed_by_user' ) && cdt_is_lesson_completed_by_user( get_current_user_id(), get_the_ID() );
					?>
					<a href="<?php the_permalink(); ?>" class="cdt-lesson-item cdt-card" style="padding: var(--space-4, 1rem) var(--space-6, 1.5rem); display: flex; align-items: center; justify-content: space-between; text-decoration: none; transition: transform var(--transition-fast, 0.15s ease), border-color var(--transition-fast, 0.15s ease), box-shadow var(--transition-fast, 0.15s ease); gap: var(--space-4, 1rem);">
						<div style="display: flex; align-items: center; gap: var(--space-4, 1rem); min-width: 0;">
							<span style="width: 38px; height: 38px; border-radius: var(--radius-sm, 10px); background: <?php echo $is_lesson_done ? 'rgba(16, 185, 129, 0.12)' : 'rgba(1, 112, 185, 0.1)'; ?>; color: <?php echo $is_lesson_done ? '#10B981' : 'var(--brand-primary, #0170B9)'; ?>; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.95rem; flex-shrink: 0; font-family: var(--font-en, sans-serif);">
								<?php echo $is_lesson_done ? '✓' : esc_html( sprintf( '%02d', $lesson_index ) ); ?>
							</span>
							<div style="min-width: 0;">
								<h3 style="font-size: 1.05rem; font-weight: 700; color: var(--brand-dark, #0F172A); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
									<?php the_title(); ?>
								</h3>
								<div style="font-size: 0.8rem; color: var(--brand-text-subtle, #64748B); display: flex; align-items: center; gap: var(--space-3, 0.75rem); margin-block-start: 4px;">
									<span>⏱️ <?php echo esc_html( sprintf( _n( '%s دقيقة قراءة', '%s دقائق قراءة', $reading_time, 'code-elta6ur' ), number_format_i18n( $reading_time ) ) ); ?></span>
									<span>📅 <?php echo esc_html( get_the_date( 'd F Y' ) ); ?></span>
								</div>
							</div>
						</div>

						<div style="display: flex; align-items: center; gap: var(--space-2, 0.5rem); flex-shrink: 0; color: var(--brand-primary, #0170B9); font-weight: 700; font-size: 0.9rem;">
							<?php if ( $is_lesson_done ) : ?>
								<span style="color: #10B981; background: rgba(16, 185, 129, 0.1); padding: 4px 12px; border-radius: var(--radius-full, 9999px); font-size: 0.825rem;"><?php esc_html_e( 'مكتمل', 'code-elta6ur' ); ?></span>
							<?php else : ?>
								<span><?php esc_html_e( 'ابدأ الشرح', 'code-elta6ur' ); ?></span>
								<?php echo cdt_svg_icon( 'arrow-left' ); ?>
							<?php endif; ?>
						</div>
					</a>
					<?php
					$lesson_index++;
				endwhile;
				wp_reset_postdata();
			else :
				?>
				<div class="cdt-card" style="padding: var(--space-8, 2rem); text-align: center;">
					<p style="color: var(--brand-text-muted, #475569); margin: 0;">
						<?php esc_html_e( 'يتم حالياً تجهيز ونشر مقالات ودروس هذا المسار، تابعنا عبر تيليجرام لتصلك التحديثات أولاً بأول.', 'code-elta6ur' ); ?>
					</p>
				</div>
				<?php
			endif;
			?>
		</div>

		<!-- Next Course Banner -->
		<?php if ( ! empty( $course_info['next_course'] ) ) : ?>
			<div class="cdt-card" style="margin-block-start: var(--space-10, 2.5rem); padding: var(--space-6, 1.5rem) var(--space-8, 2rem); background: linear-gradient(135deg, rgba(1, 112, 185, 0.04) 0%, rgba(59, 130, 246, 0.08) 100%); border-color: rgba(1, 112, 185, 0.2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: var(--space-4, 1rem);">
				<div>
					<span style="font-size: 0.85rem; font-weight: 700; color: var(--brand-primary, #0170B9);"><?php esc_html_e( 'المسار التالي الموصى به:', 'code-elta6ur' ); ?></span>
					<h3 style="font-size: 1.25rem; color: var(--brand-dark, #0F172A); margin: 4px 0 0 0;"><?php echo esc_html( $course_info['next_name'] ); ?></h3>
				</div>
				<a href="<?php echo esc_url( $course_info['next_course'] ); ?>" class="cdt-btn cdt-btn-primary">
					<?php esc_html_e( 'الانتقال إلى المسار التالي', 'code-elta6ur' ); ?>
					<?php echo cdt_svg_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</div>
		<?php endif; ?>

	</div>
</div>

<?php
get_footer();
