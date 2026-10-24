<?php
/**
 * لوحة تحكم الإدارة لنظام النقاط والتحفيز والتعلم
 * Code Elta6ur Admin Dashboard for Gamification & Users
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل قائمة لوحة تحكم نظام كود التطور
 */
function cdt_gamification_admin_menu() {
	add_menu_page(
		__( 'نظام الأعضاء والنقاط', 'code-elta6ur' ),
		__( 'نظام النقاط والتعلم', 'code-elta6ur' ),
		'manage_options',
		'cdt-gamification',
		'cdt_gamification_admin_page',
		'dashicons-awards',
		28
	);
}
add_action( 'admin_menu', 'cdt_gamification_admin_menu' );

/**
 * معالجة حفظ إعدادات لوحة التحكم وتصحيح النقاط
 */
function cdt_gamification_admin_handle_post() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// 1. حفظ الإعدادات العامة
	if ( isset( $_POST['cdt_save_gamification_settings'] ) ) {
		check_admin_referer( 'cdt_save_settings_action', 'cdt_settings_nonce' );

		$settings = array(
			'points_daily_login'        => absint( $_POST['points_daily_login'] ),
			'points_read_article'       => absint( $_POST['points_read_article'] ),
			'points_complete_lesson'    => absint( $_POST['points_complete_lesson'] ),
			'points_articles_milestone' => absint( $_POST['points_articles_milestone'] ),
			'points_first_lesson_daily' => absint( $_POST['points_first_lesson_daily'] ),
			'points_streak_3'           => absint( $_POST['points_streak_3'] ),
			'points_streak_7'           => absint( $_POST['points_streak_7'] ),
			'points_streak_30'          => absint( $_POST['points_streak_30'] ),
			'points_time_spent'         => absint( $_POST['points_time_spent'] ),
			'time_spent_slot_minutes'   => max( 1, absint( $_POST['time_spent_slot_minutes'] ) ),
			'time_spent_daily_cap'      => absint( $_POST['time_spent_daily_cap'] ),
			'article_read_min_seconds'  => max( 5, absint( $_POST['article_read_min_seconds'] ) ),
			'article_read_min_scroll'   => min( 100, max( 10, absint( $_POST['article_read_min_scroll'] ) ) ),
			'enabled_daily_login'       => ! empty( $_POST['enabled_daily_login'] ) ? 1 : 0,
			'enabled_read_article'      => ! empty( $_POST['enabled_read_article'] ) ? 1 : 0,
			'enabled_complete_lesson'   => ! empty( $_POST['enabled_complete_lesson'] ) ? 1 : 0,
			'enabled_streak'            => ! empty( $_POST['enabled_streak'] ) ? 1 : 0,
			'enabled_time_spent'        => ! empty( $_POST['enabled_time_spent'] ) ? 1 : 0,
		);

		update_option( 'cdt_gamification_settings', $settings );
		add_settings_error( 'cdt_gamification_msg', 'settings_saved', __( 'تم حفظ الإعدادات بنجاح.', 'code-elta6ur' ), 'success' );
	}

	// 2. معالجة التصحيح الإداري للنقاط
	if ( isset( $_POST['cdt_admin_adjust_points_submit'] ) ) {
		check_admin_referer( 'cdt_adjust_points_action', 'cdt_adjust_nonce' );

		$user_id = absint( $_POST['target_user_id'] );
		$points  = (int) $_POST['points_amount'];
		$reason  = sanitize_text_field( wp_unslash( $_POST['adjust_reason'] ) );

		if ( ! $user_id ) {
			add_settings_error( 'cdt_gamification_msg', 'invalid_user', __( 'لم يتم تحديد مستخدم صالح.', 'code-elta6ur' ), 'error' );
			return;
		}

		if ( empty( $reason ) ) {
			add_settings_error( 'cdt_gamification_msg', 'missing_reason', __( 'يجب كتابة سبب توثيقي للتصحيح الإداري.', 'code-elta6ur' ), 'error' );
			return;
		}

		$result = cdt_admin_adjust_points( $user_id, $points, $reason, get_current_user_id() );

		if ( ! empty( $result['success'] ) ) {
			add_settings_error( 'cdt_gamification_msg', 'points_adjusted', sprintf( __( 'تم تصحيح النقاط بنجاح للمستخدم (الرصيد الجديد: %d).', 'code-elta6ur' ), $result['new_balance'] ), 'success' );
		} else {
			add_settings_error( 'cdt_gamification_msg', 'adjust_failed', isset( $result['message'] ) ? $result['message'] : __( 'فشل تصحيح النقاط.', 'code-elta6ur' ), 'error' );
		}
	}
}
add_action( 'admin_init', 'cdt_gamification_admin_handle_post' );

/**
 * عرض صفحة إدارة نظام النقاط والتحفيز
 */
function cdt_gamification_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	global $wpdb;
	$current_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'settings'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?>
	<div class="wrap" style="direction: rtl; text-align: right; font-family: 'Cairo', system-ui, sans-serif;">
		<h1 style="display: flex; align-items: center; gap: 10px; margin-block-end: 20px;">
			<span style="color: #0170B9;">⚡</span>
			<span><?php esc_html_e( 'لوحة إدارة نظام الأعضاء والنقاط — كود التطور', 'code-elta6ur' ); ?></span>
		</h1>

		<?php settings_errors( 'cdt_gamification_msg' ); ?>

		<nav class="nav-tab-wrapper" style="margin-block-end: 24px;">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=cdt-gamification&tab=settings' ) ); ?>" class="nav-tab <?php echo 'settings' === $current_tab ? 'nav-tab-active' : ''; ?>">
				⚙️ <?php esc_html_e( 'إعدادات المكافآت', 'code-elta6ur' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=cdt-gamification&tab=ledger' ) ); ?>" class="nav-tab <?php echo 'ledger' === $current_tab ? 'nav-tab-active' : ''; ?>">
				📜 <?php esc_html_e( 'سجل النقاط العام', 'code-elta6ur' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=cdt-gamification&tab=adjust' ) ); ?>" class="nav-tab <?php echo 'adjust' === $current_tab ? 'nav-tab-active' : ''; ?>">
				✏️ <?php esc_html_e( 'إدارة وتصحيح النقاط', 'code-elta6ur' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=cdt-gamification&tab=stats' ) ); ?>" class="nav-tab <?php echo 'stats' === $current_tab ? 'nav-tab-active' : ''; ?>">
				📊 <?php esc_html_e( 'إحصائيات المنصة', 'code-elta6ur' ); ?>
			</a>
		</nav>

		<?php if ( 'settings' === $current_tab ) : ?>
			<!-- تبويب الإعدادات العامة -->
			<div class="card" style="max-width: 900px; padding: 24px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
				<form method="post" action="">
					<?php wp_nonce_field( 'cdt_save_settings_action', 'cdt_settings_nonce' ); ?>
					
					<h2 style="font-size: 1.3rem; margin-block-end: 16px; border-block-end: 1px solid #E2E8F0; padding-block-end: 8px;">
						<?php esc_html_e( 'قواعد وجدول مكافآت النقاط', 'code-elta6ur' ); ?>
					</h2>

					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><?php esc_html_e( 'تسجيل الدخول اليومي', 'code-elta6ur' ); ?></th>
							<td>
								<input type="number" name="points_daily_login" value="<?php echo esc_attr( cdt_get_gamification_setting( 'points_daily_login', 5 ) ); ?>" class="small-text" min="0"> <?php esc_html_e( 'نقاط', 'code-elta6ur' ); ?>
								<label style="margin-inline-start: 16px;">
									<input type="checkbox" name="enabled_daily_login" value="1" <?php checked( 1, cdt_get_gamification_setting( 'enabled_daily_login', 1 ) ); ?>> <?php esc_html_e( 'تفعيل', 'code-elta6ur' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'قراءة مقال مستوفٍ للشروط', 'code-elta6ur' ); ?></th>
							<td>
								<input type="number" name="points_read_article" value="<?php echo esc_attr( cdt_get_gamification_setting( 'points_read_article', 3 ) ); ?>" class="small-text" min="0"> <?php esc_html_e( 'نقاط', 'code-elta6ur' ); ?>
								<label style="margin-inline-start: 16px;">
									<input type="checkbox" name="enabled_read_article" value="1" <?php checked( 1, cdt_get_gamification_setting( 'enabled_read_article', 1 ) ); ?>> <?php esc_html_e( 'تفعيل', 'code-elta6ur' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'شروط إكمال قراءة المقال', 'code-elta6ur' ); ?></th>
							<td>
								<?php esc_html_e( 'مدة البقاء النشطة:', 'code-elta6ur' ); ?>
								<input type="number" name="article_read_min_seconds" value="<?php echo esc_attr( cdt_get_gamification_setting( 'article_read_min_seconds', 30 ) ); ?>" class="small-text" min="5"> <?php esc_html_e( 'ثانية', 'code-elta6ur' ); ?>
								&nbsp;|&nbsp;
								<?php esc_html_e( 'نسبة التمرير المطلوبة:', 'code-elta6ur' ); ?>
								<input type="number" name="article_read_min_scroll" value="<?php echo esc_attr( cdt_get_gamification_setting( 'article_read_min_scroll', 80 ) ); ?>" class="small-text" min="10" max="100">%
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'إكمال درس تدريبي', 'code-elta6ur' ); ?></th>
							<td>
								<input type="number" name="points_complete_lesson" value="<?php echo esc_attr( cdt_get_gamification_setting( 'points_complete_lesson', 10 ) ); ?>" class="small-text" min="0"> <?php esc_html_e( 'نقاط', 'code-elta6ur' ); ?>
								<label style="margin-inline-start: 16px;">
									<input type="checkbox" name="enabled_complete_lesson" value="1" <?php checked( 1, cdt_get_gamification_setting( 'enabled_complete_lesson', 1 ) ); ?>> <?php esc_html_e( 'تفعيل', 'code-elta6ur' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'إكمال أول درس في اليوم (Bonus)', 'code-elta6ur' ); ?></th>
							<td>
								<input type="number" name="points_first_lesson_daily" value="<?php echo esc_attr( cdt_get_gamification_setting( 'points_first_lesson_daily', 5 ) ); ?>" class="small-text" min="0"> <?php esc_html_e( 'نقاط إضافية', 'code-elta6ur' ); ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'إكمال باقة 5 مقالات مختلفة', 'code-elta6ur' ); ?></th>
							<td>
								<input type="number" name="points_articles_milestone" value="<?php echo esc_attr( cdt_get_gamification_setting( 'points_articles_milestone', 10 ) ); ?>" class="small-text" min="0"> <?php esc_html_e( 'نقاط إضافية', 'code-elta6ur' ); ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'مكافآت الـ Streak المستمر', 'code-elta6ur' ); ?></th>
							<td>
								<div style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
									<span>3 أيام: <input type="number" name="points_streak_3" value="<?php echo esc_attr( cdt_get_gamification_setting( 'points_streak_3', 10 ) ); ?>" class="small-text" min="0"> نقطة</span>
									<span>7 أيام: <input type="number" name="points_streak_7" value="<?php echo esc_attr( cdt_get_gamification_setting( 'points_streak_7', 25 ) ); ?>" class="small-text" min="0"> نقطة</span>
									<span>30 يوماً: <input type="number" name="points_streak_30" value="<?php echo esc_attr( cdt_get_gamification_setting( 'points_streak_30', 100 ) ); ?>" class="small-text" min="0"> نقطة</span>
									<label><input type="checkbox" name="enabled_streak" value="1" <?php checked( 1, cdt_get_gamification_setting( 'enabled_streak', 1 ) ); ?>> تفعيل نظام الـ Streak</label>
								</div>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'مكافأة وقت التواجد والتعلم النشط', 'code-elta6ur' ); ?></th>
							<td>
								<span>كل <input type="number" name="time_spent_slot_minutes" value="<?php echo esc_attr( cdt_get_gamification_setting( 'time_spent_slot_minutes', 5 ) ); ?>" class="small-text" min="1"> دقائق نشاط:</span>
								<span>يمنح <input type="number" name="points_time_spent" value="<?php echo esc_attr( cdt_get_gamification_setting( 'points_time_spent', 1 ) ); ?>" class="small-text" min="0"> نقطة</span>
								&nbsp;|&nbsp;
								<span>الحد الأقصى اليومي: <input type="number" name="time_spent_daily_cap" value="<?php echo esc_attr( cdt_get_gamification_setting( 'time_spent_daily_cap', 12 ) ); ?>" class="small-text" min="0"> نقطة</span>
								<label style="margin-inline-start: 16px;">
									<input type="checkbox" name="enabled_time_spent" value="1" <?php checked( 1, cdt_get_gamification_setting( 'enabled_time_spent', 1 ) ); ?>> تفعيل مكافأة الوقت
								</label>
							</td>
						</tr>
					</table>

					<p class="submit" style="margin-block-start: 24px;">
						<input type="submit" name="cdt_save_gamification_settings" class="button button-primary" value="<?php esc_attr_e( 'حفظ كافة التغييرات', 'code-elta6ur' ); ?>">
					</p>
				</form>
			</div>

		<?php elseif ( 'ledger' === $current_tab ) : ?>
			<!-- تبويب سجل النقاط العام -->
			<div class="card" style="padding: 20px; border-radius: 12px;">
				<h2 style="font-size: 1.25rem; margin-block-end: 16px;"><?php esc_html_e( 'أحدث 50 حركة في سجل النقاط الذري', 'code-elta6ur' ); ?></h2>
				<?php
				$table_ledger = $wpdb->prefix . 'cdt_points_ledger';
				$records      = $wpdb->get_results( "SELECT * FROM {$table_ledger} ORDER BY id DESC LIMIT 50", ARRAY_A );
				?>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th style="width: 70px;">ID</th>
							<th><?php esc_html_e( 'المستخدم', 'code-elta6ur' ); ?></th>
							<th><?php esc_html_e( 'نوع الحدث', 'code-elta6ur' ); ?></th>
							<th><?php esc_html_e( 'النقاط', 'code-elta6ur' ); ?></th>
							<th><?php esc_html_e( 'مفتاح المنع (Idempotency Key)', 'code-elta6ur' ); ?></th>
							<th><?php esc_html_e( 'التاريخ والوقت', 'code-elta6ur' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ( empty( $records ) ) : ?>
							<tr><td colspan="6" style="text-align: center; padding: 20px;"><?php esc_html_e( 'لا توجد حركات مسجلة بعد.', 'code-elta6ur' ); ?></td></tr>
						<?php else : ?>
							<?php foreach ( $records as $r ) : 
								$user = get_userdata( $r['user_id'] );
								$user_name = $user ? $user->display_name . ' (' . $user->user_login . ')' : '#' . $r['user_id'];
								$pts_color = (int) $r['points'] >= 0 ? '#10B981' : '#EF4444';
								$pts_sign  = (int) $r['points'] >= 0 ? '+' : '';
								?>
								<tr>
									<td>#<?php echo esc_html( $r['id'] ); ?></td>
									<td><strong><?php echo esc_html( $user_name ); ?></strong></td>
									<td><span class="badge" style="background: #F1F5F9; padding: 3px 8px; border-radius: 6px;"><?php echo esc_html( cdt_get_event_type_label( $r['event_type'] ) ); ?></span></td>
									<td style="font-weight: bold; color: <?php echo esc_attr( $pts_color ); ?>; font-size: 1.05rem;"><?php echo esc_html( $pts_sign . $r['points'] ); ?></td>
									<td style="font-family: monospace; font-size: 0.8rem; color: #64748B;"><?php echo esc_html( $r['idempotency_key'] ); ?></td>
									<td><?php echo esc_html( $r['created_at'] ); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

		<?php elseif ( 'adjust' === $current_tab ) : ?>
			<!-- تبويب إدارة وتصحيح النقاط -->
			<div class="card" style="max-width: 700px; padding: 24px; border-radius: 12px;">
				<h2 style="font-size: 1.25rem; margin-block-end: 16px; border-block-end: 1px solid #E2E8F0; padding-block-end: 8px;">
					<?php esc_html_e( 'إجراء تصحيح إداري موثق لرصيد مستخدم', 'code-elta6ur' ); ?>
				</h2>
				<p style="color: #64748B; font-size: 0.9rem;">
					<?php esc_html_e( 'تنبيه: التعديلات الإدارية لا تحذف السجل التاريخي، بل تنشئ حركة تصحيح موثقة تظهر في السجل باسم المدير وسبب التعديل.', 'code-elta6ur' ); ?>
				</p>

				<form method="post" action="">
					<?php wp_nonce_field( 'cdt_adjust_points_action', 'cdt_adjust_nonce' ); ?>
					
					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><?php esc_html_e( 'المستخدم المستهدف', 'code-elta6ur' ); ?></th>
							<td>
								<?php
								$users = get_users( array( 'number' => 100, 'orderby' => 'registered', 'order' => 'DESC' ) );
								?>
								<select name="target_user_id" style="min-width: 280px;" required>
									<option value=""><?php esc_html_e( '-- اختر المستخدم --', 'code-elta6ur' ); ?></option>
									<?php foreach ( $users as $u ) : 
										$pts = cdt_get_user_points( $u->ID );
										?>
										<option value="<?php echo esc_attr( $u->ID ); ?>">
											<?php echo esc_html( $u->display_name . ' (' . $u->user_login . ') — الرصيد: ' . $pts . ' نقطة' ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'مقدار النقاط للتصحيح', 'code-elta6ur' ); ?></th>
							<td>
								<input type="number" name="points_amount" class="regular-text" style="width: 140px;" placeholder="+10 أو -5" required>
								<p class="description"><?php esc_html_e( 'أدخل رقماً موجباً للإضافة (مثلاً: 25) أو سالباً للخصم (مثلاً: -10).', 'code-elta6ur' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'سبب التصحيح (إلزامي)', 'code-elta6ur' ); ?></th>
							<td>
								<input type="text" name="adjust_reason" class="large-text" placeholder="<?php esc_attr_e( 'مثال: مكافأة تميز في مسابقة كود التطور البرمجية', 'code-elta6ur' ); ?>" required>
							</td>
						</tr>
					</table>

					<p class="submit">
						<input type="submit" name="cdt_admin_adjust_points_submit" class="button button-primary" value="<?php esc_attr_e( 'تنفيذ التصحيح وحفظه في السجل', 'code-elta6ur' ); ?>">
					</p>
				</form>
			</div>

		<?php elseif ( 'stats' === $current_tab ) : ?>
			<!-- تبويب إحصائيات المنصة -->
			<?php
			$table_ledger   = $wpdb->prefix . 'cdt_points_ledger';
			$table_progress = $wpdb->prefix . 'cdt_learning_progress';
			$table_badges   = $wpdb->prefix . 'cdt_user_badges';

			$total_points_distributed = (int) $wpdb->get_var( "SELECT COALESCE(SUM(points), 0) FROM {$table_ledger} WHERE points > 0" );
			$total_transactions       = (int) $wpdb->get_var( "SELECT COUNT(id) FROM {$table_ledger}" );
			$total_lessons_completed  = (int) $wpdb->get_var( "SELECT COUNT(id) FROM {$table_progress} WHERE status = 'completed'" );
			$total_badges_unlocked    = (int) $wpdb->get_var( "SELECT COUNT(id) FROM {$table_badges}" );
			?>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-block-end: 24px;">
				<div class="card" style="padding: 20px; border-radius: 12px; border-inline-start: 4px solid #0170B9;">
					<div style="font-size: 0.9rem; color: #64748B;"><?php esc_html_e( 'إجمالي النقاط الممنوحة', 'code-elta6ur' ); ?></div>
					<div style="font-size: 2rem; font-weight: 800; color: #0170B9; margin-block-start: 8px;"><?php echo esc_html( number_format_i18n( $total_points_distributed ) ); ?></div>
				</div>
				<div class="card" style="padding: 20px; border-radius: 12px; border-inline-start: 4px solid #10B981;">
					<div style="font-size: 0.9rem; color: #64748B;"><?php esc_html_e( 'إجمالي الدروس المكتملة', 'code-elta6ur' ); ?></div>
					<div style="font-size: 2rem; font-weight: 800; color: #10B981; margin-block-start: 8px;"><?php echo esc_html( number_format_i18n( $total_lessons_completed ) ); ?></div>
				</div>
				<div class="card" style="padding: 20px; border-radius: 12px; border-inline-start: 4px solid #F59E0B;">
					<div style="font-size: 0.9rem; color: #64748B;"><?php esc_html_e( 'الشارات المفتوحة', 'code-elta6ur' ); ?></div>
					<div style="font-size: 2rem; font-weight: 800; color: #F59E0B; margin-block-start: 8px;"><?php echo esc_html( number_format_i18n( $total_badges_unlocked ) ); ?></div>
				</div>
				<div class="card" style="padding: 20px; border-radius: 12px; border-inline-start: 4px solid #3B82F6;">
					<div style="font-size: 0.9rem; color: #64748B;"><?php esc_html_e( 'إجمالي عمليات السجل', 'code-elta6ur' ); ?></div>
					<div style="font-size: 2rem; font-weight: 800; color: #3B82F6; margin-block-start: 8px;"><?php echo esc_html( number_format_i18n( $total_transactions ) ); ?></div>
				</div>
			</div>

			<!-- أفضل المتعلمين -->
			<div class="card" style="padding: 20px; border-radius: 12px;">
				<h2 style="font-size: 1.25rem; margin-block-end: 16px;"><?php esc_html_e( 'أفضل المتعلمين على المنصة (Top Learners)', 'code-elta6ur' ); ?></h2>
				<?php
				$top_users = $wpdb->get_results(
					"SELECT user_id, SUM(points) as total_pts FROM {$table_ledger} 
					 WHERE points > 0 GROUP BY user_id ORDER BY total_pts DESC LIMIT 10",
					ARRAY_A
				);
				?>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th style="width: 60px;">#</th>
							<th><?php esc_html_e( 'المتعلم', 'code-elta6ur' ); ?></th>
							<th><?php esc_html_e( 'المستوى', 'code-elta6ur' ); ?></th>
							<th><?php esc_html_e( 'إجمالي النقاط المكتسبة', 'code-elta6ur' ); ?></th>
							<th><?php esc_html_e( 'الـ Streak الحالي', 'code-elta6ur' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ( empty( $top_users ) ) : ?>
							<tr><td colspan="5" style="text-align: center; padding: 20px;"><?php esc_html_e( 'لا توجد بيانات كافية بعد.', 'code-elta6ur' ); ?></td></tr>
						<?php else : ?>
							<?php $rank = 1; foreach ( $top_users as $tu ) : 
								$user = get_userdata( $tu['user_id'] );
								if ( ! $user ) continue;
								$lvl = cdt_get_user_level_info( $tu['user_id'] );
								$str = cdt_get_user_streak( $tu['user_id'] );
								?>
								<tr>
									<td><strong><?php echo esc_html( $rank ); ?></strong></td>
									<td><strong><?php echo esc_html( $user->display_name ); ?></strong> (<?php echo esc_html( $user->user_login ); ?>)</td>
									<td><span style="background: #F1F5F9; padding: 2px 8px; border-radius: 6px; font-weight: 600;"><?php echo esc_html( $lvl['badge_icon'] . ' ' . $lvl['title'] ); ?></span></td>
									<td style="color: #0170B9; font-weight: 800;"><?php echo esc_html( number_format_i18n( $tu['total_pts'] ) ); ?> نقطة</td>
									<td>🔥 <?php echo esc_html( $str['current_streak'] ); ?> يوم</td>
								</tr>
							<?php $rank++; endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
	<?php
}
