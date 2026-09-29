<?php
/**
 * بطاقة فيديو يوتيوب التفاعلية
 * Code Elta6ur YouTube Video Card
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$video_id    = isset( $args['video_id'] ) ? $args['video_id'] : '';
$video_title = isset( $args['title'] ) ? $args['title'] : '';
$thumb_url   = ! empty( $video_id ) ? 'https://img.youtube.com/vi/' . esc_attr( $video_id ) . '/hqdefault.jpg' : CDT_THEME_URI . '/assets/images/logo.png';
?>

<div class="cdt-video-card">
	<div class="cdt-video-thumb cdt-video-trigger" data-video-id="<?php echo esc_attr( $video_id ); ?>" role="button" tabindex="0" aria-label="<?php echo esc_attr( $video_title ); ?>">
		<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $video_title ); ?>" loading="lazy">
		<div class="cdt-video-play-btn" aria-hidden="true">
			<?php echo cdt_svg_icon( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>

	<div class="cdt-video-info">
		<h4 class="cdt-video-title">
			<a href="https://www.youtube.com/watch?v=<?php echo esc_attr( $video_id ); ?>" target="_blank" rel="noopener noreferrer" class="cdt-video-trigger" data-video-id="<?php echo esc_attr( $video_id ); ?>">
				<?php echo esc_html( $video_title ); ?>
			</a>
		</h4>
	</div>
</div>
