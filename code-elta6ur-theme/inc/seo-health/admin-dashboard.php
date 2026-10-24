<?php
/**
 * لوحة تحكم صحة السيو المركزية بالأدمن (SEO Health Dashboard)
 * Code Elta6ur Admin SEO Health Management Interface
 *
 * @package CodeElta6ur
 * @subpackage SEO_Health
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Top-Level Admin Menu for SEO Health
 */
function cdt_seo_register_admin_menu() {
	add_menu_page(
		esc_html__( 'صحة السيو — كود التطور', 'code-elta6ur' ),
		esc_html__( 'صحة السيو', 'code-elta6ur' ),
		'manage_options',
		'cdt-seo-health',
		'cdt_seo_render_admin_dashboard',
		'dashicons-chart-area',
		31
	);
}
add_action( 'admin_menu', 'cdt_seo_register_admin_menu' );

/**
 * Handle CSV Exports securely
 */
function cdt_seo_handle_admin_csv_exports() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( ! isset( $_GET['cdt_seo_export'] ) || ! isset( $_GET['_wpnonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'cdt_seo_export_nonce' ) ) {
		wp_die( 'فشل التحقق الأمني.' );
	}

	$type = sanitize_key( wp_unslash( $_GET['cdt_seo_export'] ) );
	global $wpdb;

	// UTF-8 BOM for Arabic Excel compatibility
	$bom = "\xEF\xBB\xBF";

	if ( 'redirects' === $type ) {
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=code-elta6ur-redirects-' . gmdate( 'Y-m-d' ) . '.csv' );

		$table_name = $wpdb->prefix . 'seo_redirects';
		$rows       = $wpdb->get_results( "SELECT source_path, target_url, status_code, hits, source_type, created_at FROM {$table_name} ORDER BY hits DESC", ARRAY_A );

		$output = fopen( 'php://output', 'w' );
		fwrite( $output, $bom );
		fputcsv( $output, array( 'المسار المصدر', 'الرابط الهدف', 'كود التوجيه', 'الزيارات', 'النوع', 'تاريخ الإنشاء' ) );

		if ( ! empty( $rows ) ) {
			foreach ( $rows as $row ) {
				fputcsv( $output, $row );
			}
		}
		fclose( $output );
		exit;
	}

	if ( '404' === $type ) {
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=code-elta6ur-404-log-' . gmdate( 'Y-m-d' ) . '.csv' );

		$table_name = $wpdb->prefix . 'seo_404_log';
		$rows       = $wpdb->get_results( "SELECT normalized_path, hits, last_seen, referrer_host, user_agent_type FROM {$table_name} ORDER BY hits DESC LIMIT 500", ARRAY_A );

		$output = fopen( 'php://output', 'w' );
		fwrite( $output, $bom );
		fputcsv( $output, array( 'المسار المفقود', 'عدد التكرارات (Hits)', 'آخر ظهور', 'موقع الإحالة', 'نوع المتصفح/البوت' ) );

		if ( ! empty( $rows ) ) {
			foreach ( $rows as $row ) {
				fputcsv( $output, $row );
			}
		}
		fclose( $output );
		exit;
	}

	if ( 'thin' === $type ) {
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=code-elta6ur-thin-content-' . gmdate( 'Y-m-d' ) . '.csv' );

		$thin_items = cdt_seo_audit_thin_content( array( 'limit' => 300 ) );

		$output = fopen( 'php://output', 'w' );
		fwrite( $output, $bom );
		fputcsv( $output, array( 'معرف المقال', 'العنوان', 'الرابط', 'عدد الكلمات', 'عناوين H2', 'يحتوي صور', 'روابط واردة', 'المشاكل', 'التوصية' ) );

		foreach ( $thin_items as $item ) {
			fputcsv(
				$output,
				array(
					$item['id'],
					$item['title'],
					$item['url'],
					$item['words'],
					$item['has_h2'] ? 'نعم' : 'لا',
					$item['has_images'] ? 'نعم' : 'لا',
					$item['incoming_links'],
					implode( ' | ', $item['issues'] ),
					$item['recommendation'],
				)
			);
		}
		fclose( $output );
		exit;
	}
}
add_action( 'admin_init', 'cdt_seo_handle_admin_csv_exports' );

/**
 * Handle Admin Actions (Redirects, Settings, DB Setup)
 */
function cdt_seo_handle_admin_post_actions() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// 1. Initialize Tables Action
	if ( isset( $_GET['cdt_seo_init_db'] ) && check_admin_referer( 'cdt_seo_init_db_nonce' ) ) {
		cdt_seo_init_tables();
		wp_safe_redirect( add_query_arg( array( 'page' => 'cdt-seo-health', 'message' => 'db_initialized' ), admin_url( 'admin.php' ) ) );
		exit;
	}

	// 2. Add Single Redirect
	if ( isset( $_POST['cdt_seo_add_redirect'] ) && check_admin_referer( 'cdt_seo_add_redirect_nonce' ) ) {
		$source = isset( $_POST['source_path'] ) ? sanitize_text_field( wp_unslash( $_POST['source_path'] ) ) : '';
		$target = isset( $_POST['target_url'] ) ? esc_url_raw( wp_unslash( $_POST['target_url'] ) ) : '';
		$code   = isset( $_POST['status_code'] ) ? absint( $_POST['status_code'] ) : 301;

		if ( ! empty( $source ) ) {
			cdt_seo_add_redirect( $source, $target, $code, 'manual' );
			wp_safe_redirect( add_query_arg( array( 'page' => 'cdt-seo-health', 'tab' => 'redirects', 'message' => 'redirect_added' ), admin_url( 'admin.php' ) ) );
			exit;
		}
	}

	// 3. Delete Redirect
	if ( isset( $_GET['action'] ) && 'delete_redirect' === $_GET['action'] && isset( $_GET['id'] ) ) {
		check_admin_referer( 'cdt_seo_delete_redirect_' . $_GET['id'] );
		cdt_seo_delete_redirect( absint( $_GET['id'] ) );
		wp_safe_redirect( add_query_arg( array( 'page' => 'cdt-seo-health', 'tab' => 'redirects', 'message' => 'redirect_deleted' ), admin_url( 'admin.php' ) ) );
		exit;
	}

	// 4. Save Indexing Policy Options
	if ( isset( $_POST['cdt_seo_save_indexing'] ) && check_admin_referer( 'cdt_seo_save_indexing_nonce' ) ) {
		$options = array(
			'noindex_accounts'          => ! empty( $_POST['noindex_accounts'] ),
			'noindex_search'            => ! empty( $_POST['noindex_search'] ),
			'noindex_author'            => ! empty( $_POST['noindex_author'] ),
			'noindex_date'              => ! empty( $_POST['noindex_date'] ),
			'noindex_attachments'       => ! empty( $_POST['noindex_attachments'] ),
			'noindex_thin_tags'         => ! empty( $_POST['noindex_thin_tags'] ),
			'tag_min_posts'             => isset( $_POST['tag_min_posts'] ) ? absint( $_POST['tag_min_posts'] ) : 5,
			'noindex_elementor_library' => ! empty( $_POST['noindex_elementor_library'] ),
			'noindex_filter_queries'    => ! empty( $_POST['noindex_filter_queries'] ),
			'keep_paged_index'          => ! empty( $_POST['keep_paged_index'] ),
		);

		update_option( 'cdt_seo_indexing_options', $options );
		wp_safe_redirect( add_query_arg( array( 'page' => 'cdt-seo-health', 'tab' => 'indexing', 'message' => 'saved' ), admin_url( 'admin.php' ) ) );
		exit;
	}

	// 5. Save General & Smart 404 Settings
	if ( isset( $_POST['cdt_seo_save_settings'] ) && check_admin_referer( 'cdt_seo_save_settings_nonce' ) ) {
		$general_options = array(
			'auto_redirect_404_high_confidence' => ! empty( $_POST['auto_redirect_404_high_confidence'] ),
			'confidence_threshold'              => isset( $_POST['confidence_threshold'] ) ? absint( $_POST['confidence_threshold'] ) : 90,
			'min_words_post'                    => isset( $_POST['min_words_post'] ) ? absint( $_POST['min_words_post'] ) : 600,
			'min_words_tool'                    => isset( $_POST['min_words_tool'] ) ? absint( $_POST['min_words_tool'] ) : 300,
		);

		update_option( 'cdt_seo_options', $general_options );
		wp_safe_redirect( add_query_arg( array( 'page' => 'cdt-seo-health', 'tab' => 'settings', 'message' => 'saved' ), admin_url( 'admin.php' ) ) );
		exit;
	}
}
add_action( 'admin_init', 'cdt_seo_handle_admin_post_actions' );

/**
 * Main Render Function for SEO Health Dashboard
 */
function cdt_seo_render_admin_dashboard() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'غير مصرح لك بالوصول لهذه الصفحة.', 'code-elta6ur' ) );
	}

	global $wpdb;
	$current_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'overview';
	$active_seo  = cdt_seo_detect_active_plugin();

	// Check table existence
	$redirects_table = $wpdb->prefix . 'seo_redirects';
	$log_404_table   = $wpdb->prefix . 'seo_404_log';
	$has_tables      = ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $redirects_table ) ) === $redirects_table );

	// Auto-init tables if not present
	if ( ! $has_tables ) {
		cdt_seo_init_tables();
		$has_tables = true;
	}

	// Metrics
	$redirects_count = $wpdb->get_var( "SELECT COUNT(id) FROM {$redirects_table}" );
	$total_404_hits  = $wpdb->get_var( "SELECT SUM(hits) FROM {$log_404_table}" );
	$unique_404s     = $wpdb->get_var( "SELECT COUNT(id) FROM {$log_404_table}" );
	$googlebot_404s  = $wpdb->get_var( "SELECT COUNT(id) FROM {$log_404_table} WHERE user_agent_type = 'googlebot'" );

	?>
	<div class="wrap" dir="rtl" style="font-family: system-ui, -apple-system, sans-serif;">
		<h1 style="display: flex; align-items: center; gap: 10px; font-weight: 800; color: #0170B9; margin-block-end: 20px;">
			<span>🛡️</span>
			<span><?php esc_html_e( 'لوحة صحة السيو والأرشفة — كود التطور', 'code-elta6ur' ); ?></span>
			<span style="font-size: 0.8rem; background: #E2E8F0; color: #475569; padding: 2px 8px; border-radius: 9999px;">v<?php echo esc_html( CDT_SEO_HEALTH_VERSION ); ?></span>
		</h1>

		<?php if ( isset( $_GET['message'] ) ) : ?>
			<div class="notice notice-success is-dismissible">
				<p>
					<?php
					$msg = sanitize_key( $_GET['message'] );
					if ( 'saved' === $msg ) {
						esc_html_e( 'تم حفظ الإعدادات بنجاح.', 'code-elta6ur' );
					} elseif ( 'redirect_added' === $msg ) {
						esc_html_e( 'تمت إضافة التحويل بنجاح.', 'code-elta6ur' );
					} elseif ( 'redirect_deleted' === $msg ) {
						esc_html_e( 'تم حذف التحويل بنجاح.', 'code-elta6ur' );
					} elseif ( 'db_initialized' === $msg ) {
						esc_html_e( 'تم تهيئة جداول قواعد البيانات بنجاح.', 'code-elta6ur' );
					}
					?>
				</p>
			</div>
		<?php endif; ?>

		<!-- Navigation Tabs -->
		<nav class="nav-tab-wrapper wp-clearfix" style="margin-block-end: 20px;">
			<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'cdt-seo-health', 'tab' => 'overview' ), admin_url( 'admin.php' ) ) ); ?>" class="nav-tab <?php echo 'overview' === $current_tab ? 'nav-tab-active' : ''; ?>">
				<?php esc_html_e( '📊 نظرة عامة', 'code-elta6ur' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'cdt-seo-health', 'tab' => 'redirects' ), admin_url( 'admin.php' ) ) ); ?>" class="nav-tab <?php echo 'redirects' === $current_tab ? 'nav-tab-active' : ''; ?>">
				<?php esc_html_e( '🔄 التحويلات وسجل 404', 'code-elta6ur' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'cdt-seo-health', 'tab' => 'indexing' ), admin_url( 'admin.php' ) ) ); ?>" class="nav-tab <?php echo 'indexing' === $current_tab ? 'nav-tab-active' : ''; ?>">
				<?php esc_html_e( '🚫 سياسة الفهرسة (Noindex)', 'code-elta6ur' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'cdt-seo-health', 'tab' => 'thin' ), admin_url( 'admin.php' ) ) ); ?>" class="nav-tab <?php echo 'thin' === $current_tab ? 'nav-tab-active' : ''; ?>">
				<?php esc_html_e( '📄 فحص المحتوى الرقيق و Orphans', 'code-elta6ur' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'cdt-seo-health', 'tab' => 'server' ), admin_url( 'admin.php' ) ) ); ?>" class="nav-tab <?php echo 'server' === $current_tab ? 'nav-tab-active' : ''; ?>">
				<?php esc_html_e( '⚙️ فحص الخادم و Googlebot', 'code-elta6ur' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'cdt-seo-health', 'tab' => 'settings' ), admin_url( 'admin.php' ) ) ); ?>" class="nav-tab <?php echo 'settings' === $current_tab ? 'nav-tab-active' : ''; ?>">
				<?php esc_html_e( '🛠️ الإعدادات العامة', 'code-elta6ur' ); ?>
			</a>
		</nav>

		<!-- TAB 1: OVERVIEW -->
		<?php if ( 'overview' === $current_tab ) : ?>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-block-end: 25px;">
				<div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
					<div style="color: #64748B; font-size: 0.85rem; font-weight: 600;"><?php esc_html_e( 'إجمالي التحويلات النشطة (301/410)', 'code-elta6ur' ); ?></div>
					<div style="font-size: 2rem; font-weight: 800; color: #0170B9; margin-block-start: 8px;"><?php echo esc_html( number_format_i18n( (int) $redirects_count ) ); ?></div>
				</div>

				<div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
					<div style="color: #64748B; font-size: 0.85rem; font-weight: 600;"><?php esc_html_e( 'روابط 404 المفقودة المسجلة', 'code-elta6ur' ); ?></div>
					<div style="font-size: 2rem; font-weight: 800; color: #DC2626; margin-block-start: 8px;"><?php echo esc_html( number_format_i18n( (int) $unique_404s ) ); ?></div>
				</div>

				<div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
					<div style="color: #64748B; font-size: 0.85rem; font-weight: 600;"><?php esc_html_e( 'طلبات 404 من Googlebot', 'code-elta6ur' ); ?></div>
					<div style="font-size: 2rem; font-weight: 800; color: #D97706; margin-block-start: 8px;"><?php echo esc_html( number_format_i18n( (int) $googlebot_404s ) ); ?></div>
				</div>

				<div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
					<div style="color: #64748B; font-size: 0.85rem; font-weight: 600;"><?php esc_html_e( 'إضافة السيو النشطة (SEO Integration)', 'code-elta6ur' ); ?></div>
					<div style="font-size: 1.25rem; font-weight: 800; color: #10B981; margin-block-start: 12px;">
						<?php
						if ( 'yoast' === $active_seo ) {
							echo 'Yoast SEO (مربوط بنجاح)';
						} elseif ( 'rank_math' === $active_seo ) {
							echo 'Rank Math (مربوط بنجاح)';
						} elseif ( 'seopress' === $active_seo ) {
							echo 'SEOPress (مربوط بنجاح)';
						} elseif ( 'aioseo' === $active_seo ) {
							echo 'AIOSEO (مربوط بنجاح)';
						} else {
							echo 'القالب المخصص (Standalone Core)';
						}
						?>
					</div>
				</div>
			</div>

			<div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4;">
				<h2 style="font-size: 1.15rem; font-weight: 700; margin-block-end: 15px; color: #1E293B;">
					<?php esc_html_e( 'ملخص حالة محركات البحث ومعالجة الأخطاء في منصة كود التطور', 'code-elta6ur' ); ?>
				</h2>
				<p style="color: #475569; line-height: 1.7; font-size: 0.95rem;">
					يعمل نظام صحة السيو والأرشفة في قالب كود التطور كطبقة حماية ذكية ومصدر موحد لمعالجة مشاكل Google Search Console. يقوم النظام بحجب صفحات الحسابات والبحث والأرشيفات الرقيقة عن الفهرسة لتوفير ميزانية الزحف (Crawl Budget)، مع توجيه الروابط المكسورة فوراً بـ 301 بدون سلاسل تحويل، وتقديم مقترحات ذكية لزوار وبوتات صفحات 404 لمنع Soft 404.
				</p>
			</div>

		<!-- TAB 2: REDIRECTS & 404 LOG -->
		<?php elseif ( 'redirects' === $current_tab ) : ?>
			<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-block-end: 25px;">
				<!-- Add Redirect Form -->
				<div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4;">
					<h3 style="font-size: 1.1rem; font-weight: 700; margin-block-end: 15px;">
						<?php esc_html_e( 'إضافة تحويل مسار جديد', 'code-elta6ur' ); ?>
					</h3>
					<form method="post" action="">
						<?php wp_nonce_field( 'cdt_seo_add_redirect_nonce' ); ?>
						<p>
							<label for="source_path" style="display: block; font-weight: 600; margin-block-end: 4px;"><?php esc_html_e( 'المسار القديم / المصدر:', 'code-elta6ur' ); ?></label>
							<input type="text" id="source_path" name="source_path" placeholder="courses أو مسار قديم" class="regular-text" style="width: 100%;" required>
						</p>
						<p>
							<label for="target_url" style="display: block; font-weight: 600; margin-block-end: 4px;"><?php esc_html_e( 'الرابط الهدف:', 'code-elta6ur' ); ?></label>
							<input type="text" id="target_url" name="target_url" placeholder="https://code-elta6ur.com/... أو اتركه فارغاً للـ 410" class="regular-text" style="width: 100%;">
						</p>
						<p>
							<label for="status_code" style="display: block; font-weight: 600; margin-block-end: 4px;"><?php esc_html_e( 'نوع الاستجابة:', 'code-elta6ur' ); ?></label>
							<select id="status_code" name="status_code" style="width: 100%;">
								<option value="301"><?php esc_html_e( '301 - تحويل دائم (موصى به)', 'code-elta6ur' ); ?></option>
								<option value="302"><?php esc_html_e( '302 - تحويل مؤقت', 'code-elta6ur' ); ?></option>
								<option value="410"><?php esc_html_e( '410 - محذوف نهائياً بلا بديل (Gone)', 'code-elta6ur' ); ?></option>
							</select>
						</p>
						<p>
							<button type="submit" name="cdt_seo_add_redirect" class="button button-primary">
								<?php esc_html_e( 'حفظ وتطبيق التحويل', 'code-elta6ur' ); ?>
							</button>
						</p>
					</form>
				</div>

				<!-- Quick Actions & CSV Export -->
				<div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4;">
					<h3 style="font-size: 1.1rem; font-weight: 700; margin-block-end: 15px;">
						<?php esc_html_e( 'تصدير واستيراد البيانات', 'code-elta6ur' ); ?>
					</h3>
					<div style="display: flex; gap: 10px; flex-wrap: wrap;">
						<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'cdt_seo_export', 'redirects' ), 'cdt_seo_export_nonce' ) ); ?>" class="button button-secondary">
							<?php esc_html_e( '📥 تصدير التحويلات النشطة (CSV)', 'code-elta6ur' ); ?>
						</a>
						<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'cdt_seo_export', '404' ), 'cdt_seo_export_nonce' ) ); ?>" class="button button-secondary">
							<?php esc_html_e( '📥 تصدير سجل أخطاء 404 (CSV)', 'code-elta6ur' ); ?>
						</a>
					</div>
					<hr style="margin-block: 15px; border: none; border-block-start: 1px solid #E2E8F0;">
					<p style="color: #64748B; font-size: 0.85rem;">
						<?php esc_html_e( 'يتم تنظيف سجل أخطاء 404 تلقائياً كل 90 يوماً لمنع تضخم قاعدة البيانات.', 'code-elta6ur' ); ?>
					</p>
				</div>
			</div>

			<!-- Table 1: Top 404 Errors -->
			<div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4; margin-block-end: 25px;">
				<h3 style="font-size: 1.15rem; font-weight: 700; color: #DC2626; margin-block-end: 15px;">
					<?php esc_html_e( '🚨 أكثر روابط 404 تكراراً (الأعلى زيارة)', 'code-elta6ur' ); ?>
				</h3>
				<?php
				$top_404s = $wpdb->get_results( "SELECT * FROM {$log_404_table} ORDER BY hits DESC LIMIT 20" );
				if ( ! empty( $top_404s ) ) :
					?>
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th><?php esc_html_e( 'المسار المطلوب', 'code-elta6ur' ); ?></th>
								<th style="width: 80px;"><?php esc_html_e( 'التكرار', 'code-elta6ur' ); ?></th>
								<th style="width: 140px;"><?php esc_html_e( 'آخر ظهور', 'code-elta6ur' ); ?></th>
								<th style="width: 100px;"><?php esc_html_e( 'نوع البوت', 'code-elta6ur' ); ?></th>
								<th style="width: 160px;"><?php esc_html_e( 'إجراء سريع', 'code-elta6ur' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $top_404s as $item ) : ?>
								<tr>
									<td><strong>/<?php echo esc_html( $item->normalized_path ); ?>/</strong></td>
									<td><span class="badge" style="background: #FEE2E2; color: #991B1B; padding: 2px 8px; border-radius: 4px; font-weight: 700;"><?php echo esc_html( $item->hits ); ?></span></td>
									<td><?php echo esc_html( $item->last_seen ); ?></td>
									<td><?php echo esc_html( $item->user_agent_type ); ?></td>
									<td>
										<button type="button" class="button button-small" onclick="document.getElementById('source_path').value = '<?php echo esc_js( $item->normalized_path ); ?>'; window.scrollTo({top: 150, behavior: 'smooth'});">
											<?php esc_html_e( 'إنشاء 301 سريع', 'code-elta6ur' ); ?>
										</button>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else : ?>
					<p><?php esc_html_e( 'لا توجد أخطاء 404 مسجلة حالياً.', 'code-elta6ur' ); ?></p>
				<?php endif; ?>
			</div>

			<!-- Table 2: Active Redirects -->
			<div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4;">
				<h3 style="font-size: 1.15rem; font-weight: 700; color: #0170B9; margin-block-end: 15px;">
					<?php esc_html_e( 'قائمة التحويلات النشطة المخزنة في قاعدة البيانات', 'code-elta6ur' ); ?>
				</h3>
				<?php
				$redirects = $wpdb->get_results( "SELECT * FROM {$redirects_table} ORDER BY id DESC LIMIT 50" );
				if ( ! empty( $redirects ) ) :
					?>
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th><?php esc_html_e( 'المسار المصدر', 'code-elta6ur' ); ?></th>
								<th><?php esc_html_e( 'الوجهة الهدف', 'code-elta6ur' ); ?></th>
								<th style="width: 80px;"><?php esc_html_e( 'الكود', 'code-elta6ur' ); ?></th>
								<th style="width: 80px;"><?php esc_html_e( 'الزيارات', 'code-elta6ur' ); ?></th>
								<th style="width: 90px;"><?php esc_html_e( 'النوع', 'code-elta6ur' ); ?></th>
								<th style="width: 80px;"><?php esc_html_e( 'حذف', 'code-elta6ur' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $redirects as $r ) : ?>
								<tr>
									<td><strong>/<?php echo esc_html( $r->source_path ); ?>/</strong></td>
									<td><a href="<?php echo esc_url( $r->target_url ); ?>" target="_blank"><?php echo esc_html( $r->target_url ); ?></a></td>
									<td><code><?php echo esc_html( $r->status_code ); ?></code></td>
									<td><?php echo esc_html( $r->hits ); ?></td>
									<td><?php echo esc_html( $r->source_type ); ?></td>
									<td>
										<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'action' => 'delete_redirect', 'id' => $r->id ) ), 'cdt_seo_delete_redirect_' . $r->id ) ); ?>" class="button button-small" style="color: #DC2626;" onclick="return confirm('هل أنت متأكد من حذف هذا التحويل؟');">
											<?php esc_html_e( 'حذف', 'code-elta6ur' ); ?>
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else : ?>
					<p><?php esc_html_e( 'لا توجد تحويلات مخصصة مسجلة بعد في قاعدة البيانات.', 'code-elta6ur' ); ?></p>
				<?php endif; ?>
			</div>

		<!-- TAB 3: INDEXING POLICY -->
		<?php elseif ( 'indexing' === $current_tab ) : ?>
			<?php $rules = cdt_seo_get_indexing_rules(); ?>
			<div style="background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #ccd0d4; max-width: 800px;">
				<h2 style="font-size: 1.2rem; font-weight: 700; margin-block-end: 20px;">
					<?php esc_html_e( 'إعدادات سياسة الفهرسة ومنع تضخم الأرشفة (Noindex Policy)', 'code-elta6ur' ); ?>
				</h2>
				<form method="post" action="">
					<?php wp_nonce_field( 'cdt_seo_save_indexing_nonce' ); ?>
					<table class="form-table">
						<tr>
							<th scope="row"><?php esc_html_e( 'صفحات الحسابات والتسجيل', 'code-elta6ur' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="noindex_accounts" value="1" <?php checked( $rules['noindex_accounts'] ); ?>>
									<?php esc_html_e( 'وضع noindex,follow على مسارات /login/ و /registration/ و /my-account/', 'code-elta6ur' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'نتائج البحث الداخلي', 'code-elta6ur' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="noindex_search" value="1" <?php checked( $rules['noindex_search'] ); ?>>
									<?php esc_html_e( 'وضع noindex,follow على صفحات نتائج البحث الداخلية (is_search)', 'code-elta6ur' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'أرشيف المؤلفين', 'code-elta6ur' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="noindex_author" value="1" <?php checked( $rules['noindex_author'] ); ?>>
									<?php esc_html_e( 'وضع noindex على أرشيف الكاتب لمنع تكرار المحتوى مع الرئيسية (منصة بمؤلف واحد)', 'code-elta6ur' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'أرشيف التواريخ', 'code-elta6ur' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="noindex_date" value="1" <?php checked( $rules['noindex_date'] ); ?>>
									<?php esc_html_e( 'وضع noindex على أرشيف السنوات والشهور لمنع هدر ميزانية الزحف', 'code-elta6ur' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'صفحات المرفقات', 'code-elta6ur' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="noindex_attachments" value="1" <?php checked( $rules['noindex_attachments'] ); ?>>
									<?php esc_html_e( 'وضع noindex على صفحات المرفقات الفردية وتوجيهها 301 إلى المقال الأب', 'code-elta6ur' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'أرشيف الوسوم الرقيقة', 'code-elta6ur' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="noindex_thin_tags" value="1" <?php checked( $rules['noindex_thin_tags'] ); ?>>
									<?php esc_html_e( 'وضع noindex على الوسوم التي تحتوي على مقالات أقل من الحد المحدد:', 'code-elta6ur' ); ?>
								</label>
								<input type="number" name="tag_min_posts" value="<?php echo esc_attr( $rules['tag_min_posts'] ); ?>" min="1" max="50" style="width: 70px; margin-inline-start: 10px;">
								<span><?php esc_html_e( 'مقالات (الافتراضي 5 مقالات)', 'code-elta6ur' ); ?></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'قوالب Elementor الداخلية', 'code-elta6ur' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="noindex_elementor_library" value="1" <?php checked( $rules['noindex_elementor_library'] ); ?>>
									<?php esc_html_e( 'حجب قوالب elementor_library ومكونات الهيدر/الفوتر الداخلية من الأرشفة', 'code-elta6ur' ); ?>
								</label>
							</td>
						</tr>
					</table>
					<p class="submit">
						<button type="submit" name="cdt_seo_save_indexing" class="button button-primary">
							<?php esc_html_e( 'حفظ سياسة الفهرسة', 'code-elta6ur' ); ?>
						</button>
					</p>
				</form>
			</div>

		<!-- TAB 4: THIN CONTENT -->
		<?php elseif ( 'thin' === $current_tab ) : ?>
			<?php $thin_results = cdt_seo_audit_thin_content( array( 'limit' => 50 ) ); ?>
			<div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4;">
				<div style="display: flex; align-items: center; justify-content: space-between; margin-block-end: 20px;">
					<div>
						<h2 style="font-size: 1.2rem; font-weight: 700; margin: 0 0 5px 0;">
							<?php esc_html_e( 'تقرير فحص المحتوى الرقيق والصفحات اليتيمة (Orphan Posts)', 'code-elta6ur' ); ?>
						</h2>
						<p style="color: #64748B; margin: 0; font-size: 0.9rem;">
							<?php esc_html_e( 'يعرض الصفحات التي تقل كلماتها عن الحد الأدنى، أو تفتقر للعناوين H2 أو الوسائط، أو ليس لديها روابط داخلية واردة.', 'code-elta6ur' ); ?>
						</p>
					</div>
					<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'cdt_seo_export', 'thin' ), 'cdt_seo_export_nonce' ) ); ?>" class="button button-secondary">
						<?php esc_html_e( '📥 تصدير التقرير كاملاً (CSV)', 'code-elta6ur' ); ?>
					</a>
				</div>

				<?php if ( ! empty( $thin_results ) ) : ?>
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th><?php esc_html_e( 'المقال / الصفحة', 'code-elta6ur' ); ?></th>
								<th style="width: 90px;"><?php esc_html_e( 'الكلمات', 'code-elta6ur' ); ?></th>
								<th style="width: 70px;"><?php esc_html_e( 'H2', 'code-elta6ur' ); ?></th>
								<th style="width: 70px;"><?php esc_html_e( 'صور', 'code-elta6ur' ); ?></th>
								<th style="width: 90px;"><?php esc_html_e( 'روابط واردة', 'code-elta6ur' ); ?></th>
								<th><?php esc_html_e( 'الملاحظات المكتشفة', 'code-elta6ur' ); ?></th>
								<th><?php esc_html_e( 'التوصية الفنية', 'code-elta6ur' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $thin_results as $item ) : ?>
								<tr>
									<td>
										<strong><a href="<?php echo esc_url( get_edit_post_link( $item['id'] ) ); ?>"><?php echo esc_html( $item['title'] ); ?></a></strong>
										<div style="font-size: 0.8rem; color: #64748B;"><a href="<?php echo esc_url( $item['url'] ); ?>" target="_blank"><?php echo esc_html( $item['url'] ); ?></a></div>
									</td>
									<td><code><?php echo esc_html( $item['words'] ); ?></code></td>
									<td><?php echo $item['has_h2'] ? '✓' : '<span style="color:#DC2626;">✗</span>'; ?></td>
									<td><?php echo $item['has_images'] ? '✓' : '<span style="color:#DC2626;">✗</span>'; ?></td>
									<td>
										<?php if ( $item['is_orphan'] ) : ?>
											<span style="background: #FEE2E2; color: #991B1B; padding: 2px 6px; border-radius: 4px; font-weight: 700;">0 (يتيم)</span>
										<?php else : ?>
											<?php echo esc_html( $item['incoming_links'] ); ?>
										<?php endif; ?>
									</td>
									<td>
										<ul style="margin: 0; padding-inline-start: 15px; font-size: 0.85rem; color: #B91C1C;">
											<?php foreach ( $item['issues'] as $issue ) : ?>
												<li><?php echo esc_html( $issue ); ?></li>
											<?php endforeach; ?>
										</ul>
									</td>
									<td><strong style="color: #0369A1;"><?php echo esc_html( $item['recommendation'] ); ?></strong></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else : ?>
					<p style="color: #10B981; font-weight: 700;"><?php esc_html_e( 'تهانينا! لم يتم اكتشاف أي محتوى رقيق أو مقالات يتيمة في العينة المفحوصة.', 'code-elta6ur' ); ?></p>
				<?php endif; ?>
			</div>

		<!-- TAB 5: SERVER & SITEMAP AUDIT -->
		<?php elseif ( 'server' === $current_tab ) : ?>
			<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-block-end: 25px;">
				<!-- Googlebot Test -->
				<div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4;">
					<h3 style="font-size: 1.1rem; font-weight: 700; margin-block-end: 15px;">
						<?php esc_html_e( '🤖 اختبار محاكاة زحف Googlebot', 'code-elta6ur' ); ?>
					</h3>
					<p style="color: #64748B; font-size: 0.85rem;">
						<?php esc_html_e( 'يقوم هذا الفحص بإرسال طلب HTTP محاكٍ لوكيل مستخدم Googlebot للتأكد من عدم حجبه بجدار حماية أو إجباره على تسجيل الدخول.', 'code-elta6ur' ); ?>
					</p>
					<?php
					$sim_result = cdt_seo_simulate_googlebot_request( home_url( '/' ) );
					?>
					<div style="padding: 15px; border-radius: 6px; background: <?php echo $sim_result['success'] ? '#ECFDF5' : '#FEF2F2'; ?>; border: 1px solid <?php echo $sim_result['success'] ? '#6EE7B7' : '#FCA5A5'; ?>; margin-block-start: 10px;">
						<div><strong><?php esc_html_e( 'الرابط المفحوص:', 'code-elta6ur' ); ?></strong> <?php echo esc_html( $sim_result['url'] ); ?></div>
						<div><strong><?php esc_html_e( 'كود الاستجابة:', 'code-elta6ur' ); ?></strong> <?php echo esc_html( $sim_result['status_code'] ); ?></div>
						<div><strong><?php esc_html_e( 'النتيجة:', 'code-elta6ur' ); ?></strong> <?php echo $sim_result['success'] ? '<span style="color:#065F46; font-weight:700;">مسموح وناجح (Googlebot Accessible)</span>' : '<span style="color:#991B1B; font-weight:700;">محجوب أو به خلل!</span>'; ?></div>
					</div>
				</div>

				<!-- Sitemap Audit -->
				<div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4;">
					<h3 style="font-size: 1.1rem; font-weight: 700; margin-block-end: 15px;">
						<?php esc_html_e( '🗺️ فحص خريطة الموقع (Sitemap Status)', 'code-elta6ur' ); ?>
					</h3>
					<?php $sitemap_url = cdt_seo_get_sitemap_url(); ?>
					<p>
						<strong><?php esc_html_e( 'رابط خريطة الموقع النشطة:', 'code-elta6ur' ); ?></strong><br>
						<a href="<?php echo esc_url( $sitemap_url ); ?>" target="_blank"><?php echo esc_html( $sitemap_url ); ?></a>
					</p>
					<p style="color: #64748B; font-size: 0.85rem;">
						<?php esc_html_e( 'خريطة الموقع تتضمن تلقائياً فقط الروابط التي ترجع 200 OK ومضبوطة على Index ذاتي، وتستثني روابط الحسابات والبحث والمرفقات والوسوم الرقيقة.', 'code-elta6ur' ); ?>
					</p>
				</div>
			</div>

			<!-- Debug.log Tail -->
			<div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4;">
				<h3 style="font-size: 1.15rem; font-weight: 700; color: #1E293B; margin-block-end: 15px;">
					<?php esc_html_e( '📜 آخر 50 سطراً من سجل أخطاء ووردبريس (debug.log)', 'code-elta6ur' ); ?>
				</h3>
				<?php $log_data = cdt_seo_get_debug_log_tail( 50 ); ?>
				<?php if ( $log_data['exists'] && ! empty( $log_data['lines'] ) ) : ?>
					<div style="direction: ltr; text-align: left; background: #0F172A; color: #F8FAFC; padding: 15px; border-radius: 6px; font-family: monospace; font-size: 0.8rem; max-height: 350px; overflow-y: auto;">
						<?php foreach ( $log_data['lines'] as $line ) : ?>
							<div><?php echo esc_html( $line ); ?></div>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<p style="color: #64748B;">
						<?php esc_html_e( 'ملف debug.log غير موجود أو لم يتم تسجيل أي أخطاء حديثة. لتفعيله أضف define("WP_DEBUG_LOG", true); في wp-config.php.', 'code-elta6ur' ); ?>
					</p>
				<?php endif; ?>
			</div>

		<!-- TAB 6: SETTINGS -->
		<?php elseif ( 'settings' === $current_tab ) : ?>
			<?php
			$options = get_option( 'cdt_seo_options', array() );
			$auto_404 = ! empty( $options['auto_redirect_404_high_confidence'] );
			$conf     = isset( $options['confidence_threshold'] ) ? absint( $options['confidence_threshold'] ) : 90;
			$words_p  = isset( $options['min_words_post'] ) ? absint( $options['min_words_post'] ) : 600;
			$words_t  = isset( $options['min_words_tool'] ) ? absint( $options['min_words_tool'] ) : 300;
			?>
			<div style="background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #ccd0d4; max-width: 800px;">
				<h2 style="font-size: 1.2rem; font-weight: 700; margin-block-end: 20px;">
					<?php esc_html_e( 'إعدادات الاقتراحات الذكية ومعايير المحتوى الرقيق', 'code-elta6ur' ); ?>
				</h2>
				<form method="post" action="">
					<?php wp_nonce_field( 'cdt_seo_save_settings_nonce' ); ?>
					<table class="form-table">
						<tr>
							<th scope="row"><?php esc_html_e( 'التحويل التلقائي لصفحات 404', 'code-elta6ur' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="auto_redirect_404_high_confidence" value="1" <?php checked( $auto_404 ); ?>>
									<?php esc_html_e( 'تحويل الزائر تلقائياً (301) إذا اقترب المسار المفقود بنسبة ثقة عالية جداً من slug موجود', 'code-elta6ur' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'نسبة الثقة المطلوبة للتحويل التلقائي', 'code-elta6ur' ); ?></th>
							<td>
								<input type="number" name="confidence_threshold" value="<?php echo esc_attr( $conf ); ?>" min="70" max="99" style="width: 80px;"> %
								<p class="description"><?php esc_html_e( 'الافتراضي 90%. إذا كانت النسبة أقل يتم عرض اقتراح "هل تقصد...؟" دون تحويل إجباري.', 'code-elta6ur' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'الحد الأدنى لكلمات المقال / الدرس', 'code-elta6ur' ); ?></th>
							<td>
								<input type="number" name="min_words_post" value="<?php echo esc_attr( $words_p ); ?>" min="200" max="2000" style="width: 100px;"> <?php esc_html_e( 'كلمة (الافتراضي 600)', 'code-elta6ur' ); ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'الحد الأدنى لكلمات صفحات الأدوات', 'code-elta6ur' ); ?></th>
							<td>
								<input type="number" name="min_words_tool" value="<?php echo esc_attr( $words_t ); ?>" min="100" max="1000" style="width: 100px;"> <?php esc_html_e( 'كلمة (الافتراضي 300)', 'code-elta6ur' ); ?>
							</td>
						</tr>
					</table>
					<p class="submit">
						<button type="submit" name="cdt_seo_save_settings" class="button button-primary">
							<?php esc_html_e( 'حفظ الإعدادات', 'code-elta6ur' ); ?>
						</button>
					</p>
				</form>
			</div>
		<?php endif; ?>
	</div>
	<?php
}
