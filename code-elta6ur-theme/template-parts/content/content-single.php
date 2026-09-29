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
