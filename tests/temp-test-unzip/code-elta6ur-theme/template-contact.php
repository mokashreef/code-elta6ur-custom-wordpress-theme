<?php
/**
 * Template Name: كود التطور: اتصل بنا
 * Template Post Type: page
 *
 * قالب صفحة التواصل وإرسال الاستفسارات لمنصة كود التطور
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<!-- Contact Hero -->
<div class="cdt-page-hero">
	<div class="cdt-container">
		<div class="cdt-hero-badge" style="margin-block-end: var(--space-3, 0.75rem);">
			<span class="cdt-badge cdt-badge-accent"><?php esc_html_e( 'الدعم والتواصل المباشر', 'code-elta6ur' ); ?></span>
		</div>
		<h1 class="cdt-page-hero-title">
			<?php esc_html_e( 'تواصل مع منصة كود التطور', 'code-elta6ur' ); ?>
		</h1>
		<p class="cdt-page-hero-desc">
			<?php esc_html_e( 'نسعد دائماً باستقبال استفساراتك، اقتراحاتك، وطلبات التعاون البرمجي. فريقنا ومؤسس المنصة جاهزون للرد عليك بأسرع وقت.', 'code-elta6ur' ); ?>
		</p>
	</div>
</div>

<?php cdt_breadcrumbs(); ?>

<!-- Quick Contact Cards Grid -->
<section class="cdt-section" style="padding-block-end: var(--space-8, 2rem);">
	<div class="cdt-container">
		<div class="cdt-grid-4">
			<!-- WhatsApp Card -->
			<a href="<?php echo esc_url( cdt_get_option( 'cdt_whatsapp', 'https://wa.me/963983769230' ) ); ?>" target="_blank" rel="noopener noreferrer" class="cdt-card" style="padding: var(--space-6, 1.5rem); text-align: center; text-decoration: none; transition: transform var(--transition-fast, 0.15s ease), box-shadow var(--transition-fast, 0.15s ease); display: block;">
				<div style="width: 56px; height: 56px; border-radius: var(--radius-md, 16px); background: rgba(16, 185, 129, 0.12); color: #10B981; display: flex; align-items: center; justify-content: center; margin-inline: auto; margin-block-end: var(--space-4, 1rem);">
					<?php echo cdt_svg_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<h3 style="font-size: 1.15rem; color: var(--brand-dark, #0F172A); margin-block-end: var(--space-1, 0.25rem);">
					<?php esc_html_e( 'واتساب المباشر', 'code-elta6ur' ); ?>
				</h3>
				<p style="font-size: 0.85rem; color: var(--brand-text-muted, #475569); margin-block-end: var(--space-2, 0.5rem);">
					<?php esc_html_e( 'محادثة سريعة وفورية', 'code-elta6ur' ); ?>
				</p>
				<span style="font-size: 0.85rem; font-weight: 700; color: #10B981;">
					+963 983 769 230 &larr;
				</span>
			</a>

			<!-- Telegram Card -->
			<a href="<?php echo esc_url( cdt_get_option( 'cdt_telegram', 'https://t.me/code_elta6ur' ) ); ?>" target="_blank" rel="noopener noreferrer" class="cdt-card" style="padding: var(--space-6, 1.5rem); text-align: center; text-decoration: none; transition: transform var(--transition-fast, 0.15s ease), box-shadow var(--transition-fast, 0.15s ease); display: block;">
				<div style="width: 56px; height: 56px; border-radius: var(--radius-md, 16px); background: rgba(56, 189, 248, 0.12); color: var(--brand-accent, #38BDF8); display: flex; align-items: center; justify-content: center; margin-inline: auto; margin-block-end: var(--space-4, 1rem);">
					<?php echo cdt_svg_icon( 'telegram' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<h3 style="font-size: 1.15rem; color: var(--brand-dark, #0F172A); margin-block-end: var(--space-1, 0.25rem);">
					<?php esc_html_e( 'قناة تيليجرام', 'code-elta6ur' ); ?>
				</h3>
				<p style="font-size: 0.85rem; color: var(--brand-text-muted, #475569); margin-block-end: var(--space-2, 0.5rem);">
					<?php esc_html_e( 'تحديثات ومناقشات مستمرة', 'code-elta6ur' ); ?>
				</p>
				<span style="font-size: 0.85rem; font-weight: 700; color: var(--brand-primary, #0170B9);">
					@code_elta6ur &larr;
				</span>
			</a>

			<!-- Email Card -->
			<a href="mailto:<?php echo esc_attr( cdt_get_option( 'cdt_email', 'contact@code-elta6ur.com' ) ); ?>" class="cdt-card" style="padding: var(--space-6, 1.5rem); text-align: center; text-decoration: none; transition: transform var(--transition-fast, 0.15s ease), box-shadow var(--transition-fast, 0.15s ease); display: block;">
				<div style="width: 56px; height: 56px; border-radius: var(--radius-md, 16px); background: rgba(1, 112, 185, 0.12); color: var(--brand-primary, #0170B9); display: flex; align-items: center; justify-content: center; margin-inline: auto; margin-block-end: var(--space-4, 1rem);">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
				</div>
				<h3 style="font-size: 1.15rem; color: var(--brand-dark, #0F172A); margin-block-end: var(--space-1, 0.25rem);">
					<?php esc_html_e( 'البريد الإلكتروني', 'code-elta6ur' ); ?>
				</h3>
				<p style="font-size: 0.85rem; color: var(--brand-text-muted, #475569); margin-block-end: var(--space-2, 0.5rem);">
					<?php esc_html_e( 'للمراسلات الرسمية والاستفسارات', 'code-elta6ur' ); ?>
				</p>
				<span style="font-size: 0.85rem; font-weight: 700; color: var(--brand-primary, #0170B9);">
					contact@code-elta6ur.com &larr;
				</span>
			</a>

			<!-- Phone Card -->
			<a href="tel:<?php echo esc_attr( cdt_get_option( 'cdt_phone', '+963983769230' ) ); ?>" class="cdt-card" style="padding: var(--space-6, 1.5rem); text-align: center; text-decoration: none; transition: transform var(--transition-fast, 0.15s ease), box-shadow var(--transition-fast, 0.15s ease); display: block;">
				<div style="width: 56px; height: 56px; border-radius: var(--radius-md, 16px); background: rgba(59, 130, 246, 0.12); color: var(--brand-secondary, #3B82F6); display: flex; align-items: center; justify-content: center; margin-inline: auto; margin-block-end: var(--space-4, 1rem);">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
				</div>
				<h3 style="font-size: 1.15rem; color: var(--brand-dark, #0F172A); margin-block-end: var(--space-1, 0.25rem);">
					<?php esc_html_e( 'الهاتف المباشر', 'code-elta6ur' ); ?>
				</h3>
				<p style="font-size: 0.85rem; color: var(--brand-text-muted, #475569); margin-block-end: var(--space-2, 0.5rem);">
					<?php esc_html_e( 'أوقات العمل الرسمية', 'code-elta6ur' ); ?>
				</p>
				<span style="font-size: 0.85rem; font-weight: 700; color: var(--brand-secondary, #3B82F6);">
					+963 983 769 230 &larr;
				</span>
			</a>
		</div>
	</div>
</section>

<!-- Contact Form & Details Section -->
<section class="cdt-section cdt-section-alt" style="padding-block: var(--space-12, 3rem);">
	<div class="cdt-container-narrow">
		<div class="cdt-card" style="padding: var(--space-8, 2rem) var(--space-10, 2.5rem); background: var(--brand-white, #FFFFFF);">
			<div style="text-align: center; margin-block-end: var(--space-8, 2rem);">
				<span class="cdt-badge cdt-badge-accent" style="margin-block-end: var(--space-2, 0.5rem);"><?php esc_html_e( 'نموذج المراسلة', 'code-elta6ur' ); ?></span>
				<h2 style="font-size: var(--font-size-2xl, 1.5rem); color: var(--brand-dark, #0F172A); margin-block-end: var(--space-2, 0.5rem);">
					<?php esc_html_e( 'أرسل لنا رسالتك أو استفسارك', 'code-elta6ur' ); ?>
				</h2>
				<p style="color: var(--brand-text-muted, #475569); font-size: 0.95rem;">
					<?php esc_html_e( 'املأ النموذج أدناه وسيقوم فريق كود التطور بالرد عليك في غضون 24 ساعة.', 'code-elta6ur' ); ?>
				</p>
			</div>

			<div class="cdt-contact-form-wrapper">
				<?php
				$has_rendered_form = false;

				// 1. Try rendering Forminator Form (ID: 452) if plugin is active and produces valid HTML
				if ( shortcode_exists( 'forminator_form' ) ) {
					$forminator_output = do_shortcode( '[forminator_form id="452"]' );
					if ( ! empty( $forminator_output ) && false !== strpos( $forminator_output, '<form' ) ) {
						echo $forminator_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						$has_rendered_form = true;
					}
				}

				// 2. Check if page has any external form shortcode (CF7, WPForms, etc.) that actually renders a <form>
				if ( ! $has_rendered_form && have_posts() ) {
					while ( have_posts() ) {
						the_post();
						$raw_content = get_post_field( 'post_content', get_the_ID() );
						if ( preg_match( '/(\[(?:contact-form-7|wpforms|fluentform|ninja_form)[^\]]*\])/i', $raw_content, $matches ) ) {
							$rendered_plugin_form = do_shortcode( $matches[1] );
							if ( ! empty( $rendered_plugin_form ) && false !== strpos( $rendered_plugin_form, '<form' ) ) {
								echo $rendered_plugin_form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								$has_rendered_form = true;
							}
						}
					}
				}

				// 3. Reliable Native AJAX Contact Form (Works out of the box, no plugins needed!)
				if ( ! $has_rendered_form ) :
					?>
					<form id="cdt-contact-form" class="cdt-contact-form" method="post" novalidate style="display: grid; gap: var(--space-4, 1rem);">
						<?php wp_nonce_field( 'cdt_contact_action', 'cdt_nonce' ); ?>
						<input type="hidden" name="action" value="cdt_contact_send">

						<!-- Alert Notification Box -->
						<div id="cdt-contact-alert" class="cdt-contact-alert" style="display: none; padding: 14px 18px; border-radius: var(--radius-sm, 10px); font-size: 0.95rem; font-weight: 600; text-align: center;"></div>

						<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: var(--space-4, 1rem);">
							<div>
								<label for="cdt-contact-name" style="display: block; font-weight: 700; margin-block-end: var(--space-2, 0.5rem); color: var(--brand-dark, #0F172A); font-size: 0.95rem;">
									<?php esc_html_e( 'الاسم الكامل', 'code-elta6ur' ); ?> <span style="color: #EF4444;">*</span>
								</label>
								<input type="text" id="cdt-contact-name" name="name" required placeholder="<?php esc_attr_e( 'مثال: محمد أحمد', 'code-elta6ur' ); ?>" style="width: 100%; padding: 14px 18px; border: 1.5px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-sm, 10px); font-family: inherit; font-size: 0.95rem; background: var(--brand-light, #F8FAFC); transition: border-color var(--transition-fast, 0.15s ease);">
							</div>
							<div>
								<label for="cdt-contact-email" style="display: block; font-weight: 700; margin-block-end: var(--space-2, 0.5rem); color: var(--brand-dark, #0F172A); font-size: 0.95rem;">
									<?php esc_html_e( 'البريد الإلكتروني', 'code-elta6ur' ); ?> <span style="color: #EF4444;">*</span>
								</label>
								<input type="email" id="cdt-contact-email" name="email" required placeholder="example@domain.com" style="width: 100%; padding: 14px 18px; border: 1.5px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-sm, 10px); font-family: inherit; font-size: 0.95rem; direction: ltr; text-align: right; background: var(--brand-light, #F8FAFC); transition: border-color var(--transition-fast, 0.15s ease);">
							</div>
						</div>

						<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: var(--space-4, 1rem);">
							<div>
								<label for="cdt-contact-phone" style="display: block; font-weight: 700; margin-block-end: var(--space-2, 0.5rem); color: var(--brand-dark, #0F172A); font-size: 0.95rem;">
									<?php esc_html_e( 'رقم الهاتف / واتساب (اختياري)', 'code-elta6ur' ); ?>
								</label>
								<input type="tel" id="cdt-contact-phone" name="phone" placeholder="+963..." style="width: 100%; padding: 14px 18px; border: 1.5px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-sm, 10px); font-family: inherit; font-size: 0.95rem; direction: ltr; text-align: right; background: var(--brand-light, #F8FAFC);">
							</div>
							<div>
								<label for="cdt-contact-subject" style="display: block; font-weight: 700; margin-block-end: var(--space-2, 0.5rem); color: var(--brand-dark, #0F172A); font-size: 0.95rem;">
									<?php esc_html_e( 'موضوع الرسالة', 'code-elta6ur' ); ?>
								</label>
								<select id="cdt-contact-subject" name="subject" style="width: 100%; padding: 14px 18px; border: 1.5px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-sm, 10px); font-family: inherit; font-size: 0.95rem; background: var(--brand-light, #F8FAFC); cursor: pointer;">
									<option value="استفسار عام"><?php esc_html_e( 'استفسار عام عن المنصة والمحتوى', 'code-elta6ur' ); ?></option>
									<option value="استفسار عن الكورسات البرمجية"><?php esc_html_e( 'استفسار عن الكورسات والمسارات', 'code-elta6ur' ); ?></option>
									<option value="طلب استشارة أو خدمة برمجية"><?php esc_html_e( 'طلب خدمة برمجية أو استشارة أعمال', 'code-elta6ur' ); ?></option>
									<option value="اقتراح أو تعاون"><?php esc_html_e( 'اقتراح تطوير أو فرصة تعاون', 'code-elta6ur' ); ?></option>
									<option value="مشكلة تقنية أو ملاحظة"><?php esc_html_e( 'إبلاغ عن ملاحظة أو مشكلة تقنية', 'code-elta6ur' ); ?></option>
								</select>
							</div>
						</div>

						<div>
							<label for="cdt-contact-message" style="display: block; font-weight: 700; margin-block-end: var(--space-2, 0.5rem); color: var(--brand-dark, #0F172A); font-size: 0.95rem;">
								<?php esc_html_e( 'نص الرسالة والاستفسار', 'code-elta6ur' ); ?> <span style="color: #EF4444;">*</span>
							</label>
							<textarea id="cdt-contact-message" name="message" rows="5" required placeholder="<?php esc_attr_e( 'اكتب تفاصيل استفسارك أو رسالتك بوضوح...', 'code-elta6ur' ); ?>" style="width: 100%; padding: 14px 18px; border: 1.5px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-sm, 10px); font-family: inherit; font-size: 0.95rem; resize: vertical; min-height: 130px; background: var(--brand-light, #F8FAFC); transition: border-color var(--transition-fast, 0.15s ease);"></textarea>
						</div>

						<div style="text-align: center; margin-block-start: var(--space-2, 0.5rem);">
							<button type="submit" id="cdt-contact-submit-btn" class="cdt-btn cdt-btn-primary cdt-btn-lg" style="width: 100%; justify-content: center; gap: 8px;">
								<span class="cdt-btn-text"><?php esc_html_e( 'إرسال الرسالة الآن', 'code-elta6ur' ); ?></span>
								<span class="cdt-btn-icon"><?php echo cdt_svg_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							</button>
						</div>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
