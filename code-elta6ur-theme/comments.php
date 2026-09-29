<?php
/**
 * منطقة التعليقات
 * Code Elta6ur Comments Template
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="cdt-comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="cdt-comments-title">
			<?php
			$cdt_comment_count = get_comments_number();
			if ( '1' === $cdt_comment_count ) {
				esc_html_e( 'تعليق واحد', 'code-elta6ur' );
			} else {
				printf(
					/* translators: 1: comment count number. */
					esc_html( _nx( '%1$s تعليق', '%1$s تعليقات', $cdt_comment_count, 'comments title', 'code-elta6ur' ) ),
					number_format_i18n( $cdt_comment_count )
				);
			}
			?>
		</h2>

		<ol class="cdt-comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => '&larr; ' . esc_html__( 'السابق', 'code-elta6ur' ),
				'next_text' => esc_html__( 'التالي', 'code-elta6ur' ) . ' &rarr;',
			)
		);
		?>

	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'التعليقات مغلقة لهذا المنشور.', 'code-elta6ur' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'          => esc_html__( 'اترك تعليقاً أو استفساراً', 'code-elta6ur' ),
			'title_reply_to'       => esc_html__( 'الرد على %s', 'code-elta6ur' ),
			'cancel_reply_link'    => esc_html__( 'إلغاء الرد', 'code-elta6ur' ),
			'label_submit'         => esc_html__( 'إرسال التعليق', 'code-elta6ur' ),
			'class_submit'         => 'cdt-btn cdt-btn-primary',
			'comment_notes_before' => '<p class="cdt-comment-notes">' . esc_html__( 'لن يتم نشر عنوان بريدك الإلكتروني. الحقول الإلزامية مشار إليها بـ *', 'code-elta6ur' ) . '</p>',
		)
	);
	?>
</div>
