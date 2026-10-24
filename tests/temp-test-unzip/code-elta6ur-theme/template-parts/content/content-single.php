<?php
/**
 * قالب محتوى المقال الفردي الشامل
 * Code Elta6ur Single Post Content
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$reading_time = cdt_get_reading_time();
?>

<?php if ( is_user_logged_in() ) : ?>
	<div class="cdt-reading-progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
<?php endif; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'cdt-single-article' ); ?>>
	<header class="cdt-article-header">
		<h1 class="cdt-article-title"><?php the_title(); ?></h1>

		<div class="cdt-article-meta">
			<span class="cdt-card-meta-item">
				<?php echo cdt_svg_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span><?php the_author_posts_link(); ?></span>
			</span>
			<span class="cdt-card-meta-item">
				<?php echo cdt_svg_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			</span>
			<span class="cdt-card-meta-item">
				<?php echo cdt_svg_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span><?php printf( esc_html__( '%d دقيقة قراءة', 'code-elta6ur' ), absint( $reading_time ) ); ?></span>
			</span>
			<?php if ( is_user_logged_in() && function_exists( 'cdt_has_user_completed_reading' ) && cdt_has_user_completed_reading( get_current_user_id(), get_the_ID() ) ) : ?>
				<span class="cdt-card-meta-item cdt-article-completed-badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981; padding: 2px 10px; border-radius: var(--radius-full, 9999px); font-size: 0.8rem; font-weight: 700;">
					✓ <?php esc_html_e( 'تمت القراءة', 'code-elta6ur' ); ?>
				</span>
			<?php endif; ?>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="cdt-article-featured-image">
			<?php the_post_thumbnail( 'cdt-single-hero', array( 'loading' => 'eager', 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
		</div>
	<?php endif; ?>

	<div class="cdt-entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="cdt-pagination"><span class="page-numbers">' . esc_html__( 'الصفحات:', 'code-elta6ur' ) . '</span>',
				'after'  => '</div>',
			)
		);
		?>
	</div>

	<?php
	// صندوق إكمال الدرس للمسارات التعليمية
	if ( function_exists( 'cdt_is_post_a_course_lesson' ) && cdt_is_post_a_course_lesson( get_the_ID() ) ) :
		$is_completed = is_user_logged_in() && function_exists( 'cdt_is_lesson_completed_by_user' ) && cdt_is_lesson_completed_by_user( get_current_user_id(), get_the_ID() );
		?>
		<div class="cdt-lesson-completion-box" style="margin-block: var(--space-8, 2rem); padding: var(--space-6, 1.5rem); background: var(--brand-surface, #F1F5F9); border: 1px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-md, 16px); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: var(--space-4, 1rem);">
			<div>
				<div style="font-size: 0.85rem; font-weight: 700; color: var(--brand-primary, #0170B9); margin-block-end: 4px;">
					🎓 <?php esc_html_e( 'التقدم التعليمي في هذا الدرس', 'code-elta6ur' ); ?>
				</div>
				<div style="font-size: 0.95rem; color: var(--brand-text-muted, #475569); font-weight: 500;">
					<?php
					if ( $is_completed ) {
						esc_html_e( 'تهانينا! لقد أكملت دراسة هذا الدرس وحصلت على نقاط المكافأة.', 'code-elta6ur' );
					} else {
						esc_html_e( 'هل أتممت فهم وتطبيق هذا الدرس؟ اضغط على الزر لتسجيل إنجازه وكسب 10 نقاط!', 'code-elta6ur' );
					}
					?>
				</div>
			</div>

			<div>
				<?php if ( ! is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( function_exists( 'cdt_get_account_page_url' ) ? cdt_get_account_page_url( 'login' ) : home_url( '/login/' ) ); ?>" class="cdt-btn-primary" style="width: auto; padding: 10px 20px; font-size: 0.9rem;">
						<span><?php esc_html_e( 'سجّل دخولك لحصد النقاط', 'code-elta6ur' ); ?></span>
						<?php echo cdt_svg_icon( 'arrow-left' ); ?>
					</a>
				<?php elseif ( $is_completed ) : ?>
					<span style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; border-radius: var(--radius-sm, 10px); font-weight: 700; font-size: 0.9rem;">
						✓ <?php esc_html_e( 'مكتمل بنجاح', 'code-elta6ur' ); ?>
					</span>
				<?php else : ?>
					<button type="button" class="cdt-btn-primary cdt-complete-lesson-btn" data-post-id="<?php the_ID(); ?>" style="width: auto; padding: 10px 24px; font-size: 0.9rem;">
						<span><?php esc_html_e( 'إكمال الدرس (+10 نقاط)', 'code-elta6ur' ); ?></span>
						<?php echo cdt_svg_icon( 'check' ); ?>
					</button>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<footer class="cdt-article-footer">
		<?php
		$tags_list = get_the_tag_list( '', ' ' );
		if ( $tags_list ) :
			?>
			<div class="cdt-article-tags">
				<span style="font-weight:700; font-size: 0.875rem; margin-inline-end: 8px;">
					<?php esc_html_e( 'الوسوم:', 'code-elta6ur' ); ?>
				</span>
				<?php echo get_the_tag_list( '', ' ' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>

		<!-- Author Box -->
		<div class="cdt-author-box">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 80, '', '', array( 'class' => 'cdt-author-avatar' ) ); ?>
			<div>
				<h4 class="cdt-author-name"><?php the_author(); ?></h4>
				<p class="cdt-author-bio">
					<?php
					$bio = get_the_author_meta( 'description' );
					if ( ! empty( $bio ) ) {
						echo esc_html( $bio );
					} else {
						esc_html_e( 'كاتب ومطور تقني في منصة كود التطور، يشارك المعرفة البرمجية لإثراء المحتوى العربي.', 'code-elta6ur' );
					}
					?>
				</p>
			</div>
		</div>
	</footer>
</article>
