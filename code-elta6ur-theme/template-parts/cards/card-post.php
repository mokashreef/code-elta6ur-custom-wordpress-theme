<?php
/**
 * بطاقة المقال الإخباري والتعليمي
 * Code Elta6ur Post Card
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_the_category();
$category_name = ! empty( $categories ) ? $categories[0]->name : '';
$category_link = ! empty( $categories ) ? get_category_link( $categories[0]->term_id ) : '';
$reading_time = cdt_get_reading_time();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'cdt-card cdt-post-card' ); ?>>
	<div class="cdt-card-media">
		<a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'cdt-card-thumb', array( 'loading' => 'lazy', 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
			<?php else : ?>
				<div style="width:100%; height:100%; background: linear-gradient(135deg, #0170B9 0%, #38BDF8 100%); display:flex; align-items:center; justify-content:center;">
					<img src="<?php echo esc_url( CDT_THEME_URI . '/assets/images/logo.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>" style="width: 48px; height: 48px; opacity: 0.85;">
				</div>
			<?php endif; ?>
		</a>

		<?php if ( $category_name && $category_link ) : ?>
			<div class="cdt-card-badge">
				<a href="<?php echo esc_url( $category_link ); ?>" class="cdt-badge">
					<?php echo esc_html( $category_name ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>

	<div class="cdt-card-body">
		<div class="cdt-card-meta">
			<span class="cdt-card-meta-item">
				<?php echo cdt_svg_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			</span>
			<span class="cdt-card-meta-item">
				<?php echo cdt_svg_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span><?php printf( esc_html__( '%d دقيقة قراءة', 'code-elta6ur' ), absint( $reading_time ) ); ?></span>
			</span>
		</div>

		<h3 class="cdt-card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<div class="cdt-card-excerpt">
			<?php echo wp_trim_words( get_the_excerpt(), 18, '...' ); ?>
		</div>

		<div class="cdt-card-footer">
			<div class="cdt-card-author">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 26, '', '', array( 'class' => 'cdt-card-author-avatar' ) ); ?>
				<span><?php the_author(); ?></span>
			</div>
			<a href="<?php the_permalink(); ?>" class="cdt-btn cdt-btn-outline cdt-btn-sm">
				<?php esc_html_e( 'اقرأ المزيد', 'code-elta6ur' ); ?>
			</a>
		</div>
	</div>
</article>
