<?php
/**
 * بطاقة الكورس التعليمي
 * Code Elta6ur Course Card
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$course_title = isset( $args['title'] ) ? $args['title'] : get_the_title();
$course_url   = isset( $args['url'] ) ? $args['url'] : get_permalink();
$course_badge = isset( $args['badge'] ) ? $args['badge'] : esc_html__( 'دورة تدريبية', 'code-elta6ur' );
$course_desc  = isset( $args['desc'] ) ? $args['desc'] : get_the_excerpt();
$course_count = isset( $args['count'] ) ? $args['count'] : '';
?>

<div class="cdt-course-card">
	<div class="cdt-course-header">
		<span class="cdt-course-badge"><?php echo esc_html( $course_badge ); ?></span>
		<h3 class="cdt-course-title">
			<a href="<?php echo esc_url( $course_url ); ?>"><?php echo esc_html( $course_title ); ?></a>
		</h3>
	</div>

	<div class="cdt-course-body">
		<p class="cdt-course-desc">
			<?php echo esc_html( $course_desc ); ?>
		</p>

		<div class="cdt-course-meta">
			<?php if ( ! empty( $course_count ) ) : ?>
				<span><?php echo esc_html( $course_count ); ?></span>
			<?php else : ?>
				<span><?php esc_html_e( 'متاح بالكامل مجاناً', 'code-elta6ur' ); ?></span>
			<?php endif; ?>

			<a href="<?php echo esc_url( $course_url ); ?>" class="cdt-btn cdt-btn-primary cdt-btn-sm">
				<?php esc_html_e( 'ابدأ الدورة', 'code-elta6ur' ); ?>
			</a>
		</div>
	</div>
</div>
