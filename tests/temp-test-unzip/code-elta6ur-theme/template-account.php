<?php
/**
 * Template Name: كود التطور: لوحة تحكم الحساب الشخصي
 * Template Post Type: page
 *
 * لوحة التحكم المركزية للحساب الشخصي ونظام التحفيز والتقدم التعليمي
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_user_logged_in() ) {
	$login_url = add_query_arg( 'redirect_to', rawurlencode( cdt_get_account_page_url( 'account' ) ), cdt_get_account_page_url( 'login' ) );
	wp_safe_redirect( $login_url );
	exit;
}

get_header();

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;

$points_balance   = cdt_get_user_points( $user_id );
$total_earned     = cdt_get_user_total_earned_points( $user_id );
$level_info       = cdt_get_user_level_info( $user_id );
$streak_info      = cdt_get_user_streak( $user_id );
$completed_arts   = cdt_get_user_completed_articles_count( $user_id );
$completed_less   = cdt_get_user_completed_lessons_count( $user_id );
$last_lesson      = cdt_get_user_last_lesson( $user_id );
$flash_msg        = cdt_get_auth_message();
$active_tab       = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'overview';

$courses_catalog  = cdt_get_courses_catalog();
$user_badges      = cdt_get_user_badges_status( $user_id );
$ledger_records   = cdt_get_user_ledger( $user_id, 30 );
?>

<div class="cdt-account-container">
	<div class="cdt-container">

		<?php if ( $flash_msg ) : ?>
			<div class="cdt-alert cdt-alert-<?php echo esc_attr( $flash_msg['type'] ); ?>">
				<span><?php echo 'success' === $flash_msg['type'] ? '✓' : '⚠️'; ?></span>
				<span><?php echo esc_html( $flash_msg['message'] ); ?></span>
			</div>
		<?php endif; ?>

		<!-- Hero Summary Banner -->
		<div class="cdt-account-hero">
			<div class="cdt-account-profile-main">
				<div class="cdt-account-avatar-wrap">
					<?php echo get_avatar( $user_id, 80, '', '', array( 'class' => 'cdt-account-avatar' ) ); ?>
				</div>
				<div>
					<h1 class="cdt-account-name"><?php echo esc_html( $current_user->display_name ); ?></h1>
					<div class="cdt-account-meta">
						<span>@<?php echo esc_html( $current_user->user_login ); ?></span>
						<span>•</span>
						<span><?php printf( esc_html__( 'عضو منذ: %s', 'code-elta6ur' ), esc_html( get_date_from_gmt( $current_user->user_registered, 'd F Y' ) ) ); ?></span>
						<span>•</span>
						<span style="background: rgba(255,255,255,0.2); padding: 2px 10px; border-radius: var(--radius-full, 9999px); font-weight: 700;">
							<?php echo esc_html( $level_info['badge_icon'] . ' ' . $level_info['title'] ); ?>
						</span>
					</div>
				</div>
			</div>

			<div class="cdt-account-hero-stats">
				<div class="cdt-hero-stat-box">
					<div class="cdt-hero-stat-val"><?php echo esc_html( number_format_i18n( $points_balance ) ); ?></div>
					<div class="cdt-hero-stat-lbl">💎 <?php esc_html_e( 'رصيد النقاط', 'code-elta6ur' ); ?></div>
				</div>
				<div class="cdt-hero-stat-box">
					<div class="cdt-hero-stat-val">🔥 <?php echo esc_html( $streak_info['current_streak'] ); ?></div>
					<div class="cdt-hero-stat-lbl"><?php esc_html_e( 'أيام Streak', 'code-elta6ur' ); ?></div>
				</div>
				<div class="cdt-hero-stat-box">
					<div class="cdt-hero-stat-val"><?php echo esc_html( $completed_less ); ?></div>
					<div class="cdt-hero-stat-lbl">🎓 <?php esc_html_e( 'دروس مكتملة', 'code-elta6ur' ); ?></div>
				</div>
			</div>
		</div>

		<!-- Dashboard Layout: Navigation + Content -->
		<div class="cdt-dashboard-layout">
			<!-- Sidebar Tabs Navigation -->
			<aside>
				<nav class="cdt-account-tabs-nav" aria-label="<?php esc_attr_e( 'أقسام الحساب', 'code-elta6ur' ); ?>">
					<button type="button" class="cdt-tab-link <?php echo 'overview' === $active_tab ? 'is-active' : ''; ?>" data-tab="overview">
						<span>📊</span>
						<span><?php esc_html_e( 'نظرة عامة', 'code-elta6ur' ); ?></span>
					</button>
					<button type="button" class="cdt-tab-link <?php echo 'points' === $active_tab ? 'is-active' : ''; ?>" data-tab="points">
						<span>💎</span>
						<span><?php esc_html_e( 'النقاط والمكافآت', 'code-elta6ur' ); ?></span>
					</button>
					<button type="button" class="cdt-tab-link <?php echo 'progress' === $active_tab ? 'is-active' : ''; ?>" data-tab="progress">
						<span>🎓</span>
						<span><?php esc_html_e( 'تقدمي التعليمي', 'code-elta6ur' ); ?></span>
					</button>
					<button type="button" class="cdt-tab-link <?php echo 'badges' === $active_tab ? 'is-active' : ''; ?>" data-tab="badges">
						<span>🏆</span>
						<span><?php esc_html_e( 'الإنجازات والشارات', 'code-elta6ur' ); ?></span>
					</button>
					<button type="button" class="cdt-tab-link <?php echo 'activity' === $active_tab ? 'is-active' : ''; ?>" data-tab="activity">
						<span>📜</span>
						<span><?php esc_html_e( 'سجل النشاط', 'code-elta6ur' ); ?></span>
					</button>
					<button type="button" class="cdt-tab-link <?php echo 'settings' === $active_tab ? 'is-active' : ''; ?>" data-tab="settings">
						<span>⚙️</span>
						<span><?php esc_html_e( 'إعدادات الحساب', 'code-elta6ur' ); ?></span>
					</button>
					<a href="<?php echo esc_url( cdt_get_account_page_url( 'logout' ) ); ?>" class="cdt-tab-link is-logout">
						<span>🚪</span>
						<span><?php esc_html_e( 'تسجيل الخروج', 'code-elta6ur' ); ?></span>
					</a>
				</nav>
			</aside>

			<!-- Main Tab Panels -->
			<div class="cdt-dashboard-content">

				<!-- 1. OVERVIEW TAB -->
				<div class="cdt-tab-panel <?php echo 'overview' === $active_tab ? 'is-active' : ''; ?>" id="cdt-tab-overview">
					
					<!-- 4 Stat Cards Grid -->
					<div class="cdt-stats-grid-4">
						<div class="cdt-stat-card">
							<div class="cdt-stat-icon">💎</div>
							<div>
								<div class="cdt-stat-number"><?php echo esc_html( number_format_i18n( $points_balance ) ); ?></div>
								<div class="cdt-stat-label"><?php esc_html_e( 'إجمالي النقاط الحالية', 'code-elta6ur' ); ?></div>
							</div>
						</div>
						<div class="cdt-stat-card">
							<div class="cdt-stat-icon" style="color: #F59E0B; background: rgba(245, 158, 11, 0.1);">🔥</div>
							<div>
								<div class="cdt-stat-number"><?php echo esc_html( $streak_info['current_streak'] ); ?> <?php esc_html_e( 'أيام', 'code-elta6ur' ); ?></div>
								<div class="cdt-stat-label"><?php printf( esc_html__( 'أطول سلسلة: %d أيام', 'code-elta6ur' ), $streak_info['longest_streak'] ); ?></div>
							</div>
						</div>
						<div class="cdt-stat-card">
							<div class="cdt-stat-icon" style="color: #10B981; background: rgba(16, 185, 129, 0.1);">📖</div>
							<div>
								<div class="cdt-stat-number"><?php echo esc_html( $completed_arts ); ?></div>
								<div class="cdt-stat-label"><?php esc_html_e( 'مقالات مكتملة القراءة', 'code-elta6ur' ); ?></div>
							</div>
						</div>
						<div class="cdt-stat-card">
							<div class="cdt-stat-icon" style="color: #38BDF8; background: rgba(56, 189, 248, 0.1);">🎓</div>
							<div>
								<div class="cdt-stat-number"><?php echo esc_html( $completed_less ); ?></div>
								<div class="cdt-stat-label"><?php esc_html_e( 'دروس برمجية مكتملة', 'code-elta6ur' ); ?></div>
							</div>
						</div>
					</div>

					<!-- Level Progress Card -->
					<div class="cdt-level-card">
						<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
							<div>
								<span style="font-size: 0.85rem; font-weight: 700; color: var(--brand-primary, #0170B9);"><?php esc_html_e( 'المستوى والخبرة', 'code-elta6ur' ); ?></span>
								<h3 style="font-size: 1.25rem; font-weight: 800; margin-block-start: 2px; color: var(--brand-dark, #0F172A);">
									<?php echo esc_html( $level_info['badge_icon'] . ' المستوى ' . $level_info['level'] . ': ' . $level_info['title'] ); ?>
								</h3>
							</div>
							<div style="text-align: end;">
								<?php if ( $level_info['next_level'] ) : ?>
									<span style="font-size: 0.85rem; color: var(--brand-text-muted, #475569);">
										<?php printf( esc_html__( 'متبقي %d نقطة للوصول إلى (%s)', 'code-elta6ur' ), $level_info['points_needed'], esc_html( $level_info['next_level'] ) ); ?>
									</span>
								<?php else : ?>
									<span style="font-size: 0.85rem; color: var(--status-success, #10B981); font-weight: 700;">
										👑 <?php esc_html_e( 'وصلت إلى أقصى مستوى في المنصة!', 'code-elta6ur' ); ?>
									</span>
								<?php endif; ?>
							</div>
						</div>

						<div class="cdt-progress-track">
							<div class="cdt-progress-fill" style="width: <?php echo esc_attr( $level_info['progress_percent'] ); ?>%;"></div>
						</div>

						<div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--brand-text-subtle, #64748B); font-weight: 600;">
							<span><?php printf( esc_html__( 'إجمالي النقاط المكتسبة: %s', 'code-elta6ur' ), number_format_i18n( $total_earned ) ); ?></span>
							<span><?php echo esc_html( $level_info['progress_percent'] ); ?>%</span>
						</div>
					</div>

					<!-- Resume Last Lesson Card -->
					<?php if ( $last_lesson ) : ?>
						<div class="cdt-resume-card">
							<div>
								<div style="font-size: 0.8rem; font-weight: 700; color: var(--brand-primary, #0170B9); margin-block-end: 4px;">
									⚡ <?php esc_html_e( 'تابع من حيث توقفت', 'code-elta6ur' ); ?>
								</div>
								<h4 style="font-size: 1.15rem; font-weight: 800; color: var(--brand-dark, #0F172A); margin: 0;">
									<?php echo esc_html( $last_lesson->post_title ); ?>
								</h4>
							</div>
							<a href="<?php echo esc_url( get_permalink( $last_lesson->ID ) ); ?>" class="cdt-btn-primary" style="width: auto; padding: 10px 20px; font-size: 0.9rem;">
								<span><?php esc_html_e( 'متابعة الدرس', 'code-elta6ur' ); ?></span>
								<?php echo cdt_svg_icon( 'arrow-left' ); ?>
							</a>
						</div>
					<?php endif; ?>

					<!-- Quick Badges Preview -->
					<div class="cdt-card" style="padding: var(--space-6, 1.5rem); background: var(--brand-white, #FFFFFF); border: 1px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-md, 16px); margin-block-end: var(--space-6, 1.5rem);">
						<div style="display: flex; justify-content: space-between; align-items: center; margin-block-end: var(--space-4, 1rem);">
							<h3 style="font-size: 1.15rem; font-weight: 800; margin: 0; color: var(--brand-dark, #0F172A);">
								🏆 <?php esc_html_e( 'أحدث الشارات والإنجازات المكتسبة', 'code-elta6ur' ); ?>
							</h3>
							<button type="button" class="cdt-link-muted" onclick="document.querySelector('.cdt-tab-link[data-tab=\'badges\']').click();" style="background: none; border: none; cursor: pointer; font-size: 0.85rem;">
								<?php esc_html_e( 'عرض الكل ←', 'code-elta6ur' ); ?>
							</button>
						</div>

						<div class="cdt-badges-grid">
							<?php
							$preview_count = 0;
							foreach ( $user_badges as $badge ) :
								if ( $badge['unlocked'] && $preview_count < 3 ) :
									$preview_count++;
									?>
									<div class="cdt-badge-item">
										<div class="cdt-badge-icon-lg"><?php echo esc_html( $badge['icon'] ); ?></div>
										<div class="cdt-badge-title"><?php echo esc_html( $badge['title'] ); ?></div>
										<p class="cdt-badge-desc"><?php echo esc_html( $badge['description'] ); ?></p>
										<div class="cdt-badge-date">✓ <?php printf( esc_html__( 'أُنجزت في %s', 'code-elta6ur' ), esc_html( date_i18n( 'd M Y', strtotime( $badge['earned_at'] ) ) ) ); ?></div>
									</div>
									<?php
								endif;
							endforeach;

							if ( 0 === $preview_count ) :
								?>
								<div style="grid-column: 1 / -1; text-align: center; padding: 24px; color: var(--brand-text-muted, #475569);">
									<p style="margin: 0;"><?php esc_html_e( 'ابدأ بقراءة أول مقال أو إكمال درسك الأول لفتح أول شارة لك!', 'code-elta6ur' ); ?></p>
								</div>
							<?php endif; ?>
						</div>
					</div>

				</div>

				<!-- 2. POINTS & REWARDS TAB -->
				<div class="cdt-tab-panel <?php echo 'points' === $active_tab ? 'is-active' : ''; ?>" id="cdt-tab-points">
					<div class="cdt-card" style="padding: var(--space-6, 1.5rem); background: var(--brand-white, #FFFFFF); border: 1px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-md, 16px); margin-block-end: var(--space-6, 1.5rem);">
						<h3 style="font-size: 1.25rem; font-weight: 800; margin-block-end: var(--space-4, 1rem); color: var(--brand-dark, #0F172A);">
							💎 <?php esc_html_e( 'كيف تكسب النقاط على كود التطور؟', 'code-elta6ur' ); ?>
						</h3>
						<p style="color: var(--brand-text-muted, #475569); margin-block-end: var(--space-5, 1.25rem);">
							<?php esc_html_e( 'نظام النقاط في كود التطور يكافئ التزامك وسعيك الحقيقي للتعلم، إليك قائمة الأنشطة المعتمدة:', 'code-elta6ur' ); ?>
						</p>

						<div class="cdt-table-wrap">
							<table class="cdt-activity-table">
								<thead>
									<tr>
										<th><?php esc_html_e( 'النشاط', 'code-elta6ur' ); ?></th>
										<th><?php esc_html_e( 'النقاط', 'code-elta6ur' ); ?></th>
										<th><?php esc_html_e( 'شروط الاستحقاق', 'code-elta6ur' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><strong><?php esc_html_e( 'تسجيل الدخول اليومي', 'code-elta6ur' ); ?></strong></td>
										<td style="color: var(--brand-primary, #0170B9); font-weight: 800;">+5 نقاط</td>
										<td><?php esc_html_e( 'مرة واحدة لكل يوم تقويمي', 'code-elta6ur' ); ?></td>
									</tr>
									<tr>
										<td><strong><?php esc_html_e( 'قراءة مقال تعليمي', 'code-elta6ur' ); ?></strong></td>
										<td style="color: var(--brand-primary, #0170B9); font-weight: 800;">+3 نقاط</td>
										<td><?php esc_html_e( 'قراءة 80% من المقال مع بقاء 30 ثانية نشطة', 'code-elta6ur' ); ?></td>
									</tr>
									<tr>
										<td><strong><?php esc_html_e( 'إكمال درس تدريبي', 'code-elta6ur' ); ?></strong></td>
										<td style="color: var(--brand-primary, #0170B9); font-weight: 800;">+10 نقاط</td>
										<td><?php esc_html_e( 'إكمال الدرس ومراجعة الأكواد التطبيقية', 'code-elta6ur' ); ?></td>
									</tr>
									<tr>
										<td><strong><?php esc_html_e( 'أول درس في اليوم (Bonus)', 'code-elta6ur' ); ?></strong></td>
										<td style="color: var(--brand-primary, #0170B9); font-weight: 800;">+5 نقاط إضافية</td>
										<td><?php esc_html_e( 'يُمنح عند إكمال أول درس في اليوم', 'code-elta6ur' ); ?></td>
									</tr>
									<tr>
										<td><strong><?php esc_html_e( 'قراءة 5 مقالات متنوعة', 'code-elta6ur' ); ?></strong></td>
										<td style="color: var(--brand-primary, #0170B9); font-weight: 800;">+10 نقاط إضافية</td>
										<td><?php esc_html_e( 'مرة واحدة عند إكمال 5 مقالات مختلفة', 'code-elta6ur' ); ?></td>
									</tr>
									<tr>
										<td><strong><?php esc_html_e( 'الاستمرارية (Streak 3 أيام)', 'code-elta6ur' ); ?></strong></td>
										<td style="color: var(--brand-primary, #0170B9); font-weight: 800;">+10 نقاط إضافية</td>
										<td><?php esc_html_e( 'تحقيق 3 أيام متتالية من النشاط', 'code-elta6ur' ); ?></td>
									</tr>
									<tr>
										<td><strong><?php esc_html_e( 'الاستمرارية (Streak 7 أيام)', 'code-elta6ur' ); ?></strong></td>
										<td style="color: var(--brand-primary, #0170B9); font-weight: 800;">+25 نقطة إضافية</td>
										<td><?php esc_html_e( 'أسبوع كامل من التعلم المتواصل', 'code-elta6ur' ); ?></td>
									</tr>
									<tr>
										<td><strong><?php esc_html_e( 'الاستمرارية (Streak 30 يوماً)', 'code-elta6ur' ); ?></strong></td>
										<td style="color: var(--brand-primary, #0170B9); font-weight: 800;">+100 نقطة إضافية</td>
										<td><?php esc_html_e( 'شهر كامل من المثابرة البرمجية', 'code-elta6ur' ); ?></td>
									</tr>
									<tr>
										<td><strong><?php esc_html_e( 'وقت التعلم النشط على المنصة', 'code-elta6ur' ); ?></strong></td>
										<td style="color: var(--brand-primary, #0170B9); font-weight: 800;">+1 نقطة / 5 دقائق</td>
										<td><?php esc_html_e( 'بحد أقصى 12 نقطة يومياً أثناء وجود نشاط حقيقي', 'code-elta6ur' ); ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<!-- 3. LEARNING PROGRESS TAB -->
				<div class="cdt-tab-panel <?php echo 'progress' === $active_tab ? 'is-active' : ''; ?>" id="cdt-tab-progress">
					<div style="margin-block-end: var(--space-6, 1.5rem);">
						<h3 style="font-size: 1.25rem; font-weight: 800; color: var(--brand-dark, #0F172A); margin-block-end: 4px;">
							🎓 <?php esc_html_e( 'المسارات والكورسات التعليمية', 'code-elta6ur' ); ?>
						</h3>
						<p style="color: var(--brand-text-muted, #475569); margin: 0;">
							<?php esc_html_e( 'تتبع نسبة إنجازك في كل مسار برمجي بناءً على الدروس التي أكملتها فعلياً.', 'code-elta6ur' ); ?>
						</p>
					</div>

					<div class="cdt-courses-progress-grid">
						<?php foreach ( $courses_catalog as $slug => $c_data ) : 
							$stats = cdt_get_course_completion_stats( $user_id, $slug );
							?>
							<div class="cdt-course-progress-card">
								<div>
									<div style="display: flex; justify-content: space-between; align-items: center; margin-block-end: 8px;">
										<span class="cdt-badge cdt-badge-primary"><?php echo esc_html( $c_data['badge'] ); ?></span>
										<span style="font-size: 0.85rem; font-weight: 700; color: var(--brand-primary, #0170B9);">
											<?php echo esc_html( $stats['percent'] ); ?>%
										</span>
									</div>
									<h4 style="font-size: 1.1rem; font-weight: 800; color: var(--brand-dark, #0F172A); margin-block-end: 6px;">
										<?php echo esc_html( $c_data['title'] ); ?>
									</h4>
									<div style="font-size: 0.825rem; color: var(--brand-text-muted, #475569); margin-block-end: var(--space-3, 0.75rem);">
										<?php printf( esc_html__( 'أكملت %1$d من أصل %2$d درساً', 'code-elta6ur' ), $stats['completed'], $stats['total'] ); ?>
									</div>
								</div>

								<div>
									<div class="cdt-progress-track">
										<div class="cdt-progress-fill" style="width: <?php echo esc_attr( $stats['percent'] ); ?>%;"></div>
									</div>

									<a href="<?php echo esc_url( $c_data['url'] ); ?>" class="cdt-btn-primary" style="margin-block-start: var(--space-4, 1rem); padding: 8px 16px; font-size: 0.85rem;">
										<span><?php esc_html_e( 'استعراض المسار والدروس', 'code-elta6ur' ); ?></span>
										<?php echo cdt_svg_icon( 'arrow-left' ); ?>
									</a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- 4. ACHIEVEMENTS & BADGES TAB -->
				<div class="cdt-tab-panel <?php echo 'badges' === $active_tab ? 'is-active' : ''; ?>" id="cdt-tab-badges">
					<div style="margin-block-end: var(--space-6, 1.5rem);">
						<h3 style="font-size: 1.25rem; font-weight: 800; color: var(--brand-dark, #0F172A); margin-block-end: 4px;">
							🏆 <?php esc_html_e( 'لوحة الأوسمة والإنجازات', 'code-elta6ur' ); ?>
						</h3>
						<p style="color: var(--brand-text-muted, #475569); margin: 0;">
							<?php esc_html_e( 'شاهد جميع الشارات التي حققتها، واستكشف الشارات القادمة وشروط فتحها.', 'code-elta6ur' ); ?>
						</p>
					</div>

					<div class="cdt-badges-grid">
						<?php foreach ( $user_badges as $badge ) : ?>
							<div class="cdt-badge-item <?php echo $badge['unlocked'] ? 'is-unlocked' : 'is-locked'; ?>">
								<div class="cdt-badge-icon-lg"><?php echo esc_html( $badge['icon'] ); ?></div>
								<div class="cdt-badge-title"><?php echo esc_html( $badge['title'] ); ?></div>
								<p class="cdt-badge-desc"><?php echo esc_html( $badge['description'] ); ?></p>
								
								<?php if ( $badge['unlocked'] ) : ?>
									<div class="cdt-badge-date">
										✓ <?php printf( esc_html__( 'تم الحصول عليها: %s', 'code-elta6ur' ), esc_html( date_i18n( 'd F Y', strtotime( $badge['earned_at'] ) ) ) ); ?>
									</div>
								<?php else : ?>
									<div style="font-size: 0.75rem; color: var(--brand-text-subtle, #64748B); margin-block-start: 8px; font-weight: 600;">
										🔒 <?php esc_html_e( 'قيد الانتظار', 'code-elta6ur' ); ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- 5. ACTIVITY LOG TAB -->
				<div class="cdt-tab-panel <?php echo 'activity' === $active_tab ? 'is-active' : ''; ?>" id="cdt-tab-activity">
					<div class="cdt-card" style="padding: var(--space-6, 1.5rem); background: var(--brand-white, #FFFFFF); border: 1px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-md, 16px);">
						<h3 style="font-size: 1.25rem; font-weight: 800; color: var(--brand-dark, #0F172A); margin-block-end: var(--space-4, 1rem);">
							📜 <?php esc_html_e( 'سجل العمليات والنقاط الذري', 'code-elta6ur' ); ?>
						</h3>

						<?php if ( empty( $ledger_records ) ) : ?>
							<div style="text-align: center; padding: 40px; color: var(--brand-text-muted, #475569);">
								<p><?php esc_html_e( 'لا توجد عمليات مسجلة حتى الآن. تصفح الموقع وابدأ التعلم لتسجيل نشاطك الأول!', 'code-elta6ur' ); ?></p>
							</div>
						<?php else : ?>
							<div class="cdt-table-wrap">
								<table class="cdt-activity-table">
									<thead>
										<tr>
											<th><?php esc_html_e( 'النشاط / السبب', 'code-elta6ur' ); ?></th>
											<th><?php esc_html_e( 'النقاط', 'code-elta6ur' ); ?></th>
											<th><?php esc_html_e( 'التاريخ والوقت', 'code-elta6ur' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $ledger_records as $rec ) : 
											$is_positive = (int) $rec['points'] >= 0;
											$color       = $is_positive ? 'var(--status-success, #10B981)' : 'var(--status-error, #EF4444)';
											$sign        = $is_positive ? '+' : '';
											?>
											<tr>
												<td>
													<strong><?php echo esc_html( $rec['label'] ); ?></strong>
													<?php if ( ! empty( $rec['meta']['reason'] ) ) : ?>
														<div style="font-size: 0.8rem; color: var(--brand-text-subtle, #64748B);"><?php echo esc_html( $rec['meta']['reason'] ); ?></div>
													<?php endif; ?>
												</td>
												<td style="font-weight: 800; color: <?php echo esc_attr( $color ); ?>; font-family: var(--font-en, sans-serif);">
													<?php echo esc_html( $sign . $rec['points'] ); ?>
												</td>
												<td style="font-size: 0.85rem; color: var(--brand-text-subtle, #64748B);">
													<?php echo esc_html( date_i18n( 'd M Y — H:i', strtotime( $rec['created_at'] ) ) ); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<!-- 6. SETTINGS TAB -->
				<div class="cdt-tab-panel <?php echo 'settings' === $active_tab ? 'is-active' : ''; ?>" id="cdt-tab-settings">
					<div class="cdt-card" style="padding: var(--space-8, 2rem); background: var(--brand-white, #FFFFFF); border: 1px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-md, 16px);">
						<h3 style="font-size: 1.25rem; font-weight: 800; color: var(--brand-dark, #0F172A); margin-block-end: var(--space-6, 1.5rem);">
							⚙️ <?php esc_html_e( 'تعديل بيانات الحساب وكلمة المرور', 'code-elta6ur' ); ?>
						</h3>

						<form method="post" action="" class="cdt-auth-form" style="max-width: 600px;">
							<?php wp_nonce_field( 'cdt_settings_action', 'cdt_settings_nonce' ); ?>

							<div class="cdt-form-group">
								<label for="cdt_set_display_name" class="cdt-form-label"><?php esc_html_e( 'الاسم الظاهر', 'code-elta6ur' ); ?></label>
								<div class="cdt-input-wrapper">
									<input type="text" name="display_name" id="cdt_set_display_name" class="cdt-form-control" value="<?php echo esc_attr( $current_user->display_name ); ?>" required>
								</div>
							</div>

							<div class="cdt-form-group">
								<label for="cdt_set_email" class="cdt-form-label"><?php esc_html_e( 'البريد الإلكتروني', 'code-elta6ur' ); ?></label>
								<div class="cdt-input-wrapper">
									<input type="email" name="email" id="cdt_set_email" class="cdt-form-control" value="<?php echo esc_attr( $current_user->user_email ); ?>" required>
								</div>
							</div>

							<div class="cdt-form-group">
								<label for="cdt_set_bio" class="cdt-form-label"><?php esc_html_e( 'نبذة عنك (Bio)', 'code-elta6ur' ); ?></label>
								<textarea name="description" id="cdt_set_bio" class="cdt-form-control" rows="3"><?php echo esc_textarea( $current_user->description ); ?></textarea>
							</div>

							<div style="margin-block: var(--space-8, 2rem); border-block-start: 1px solid var(--brand-border, #E2E8F0); padding-block-start: var(--space-6, 1.5rem);">
								<h4 style="font-size: 1.1rem; font-weight: 700; color: var(--brand-dark, #0F172A); margin-block-end: var(--space-4, 1rem);">
									🔒 <?php esc_html_e( 'تغيير كلمة المرور (اختياري)', 'code-elta6ur' ); ?>
								</h4>

								<div class="cdt-form-group">
									<label for="cdt_curr_pass" class="cdt-form-label"><?php esc_html_e( 'كلمة المرور الحالية', 'code-elta6ur' ); ?></label>
									<div class="cdt-input-wrapper">
										<input type="password" name="current_password" id="cdt_curr_pass" class="cdt-form-control" placeholder="••••••••">
									</div>
								</div>

								<div class="cdt-form-group">
									<label for="cdt_new_pass" class="cdt-form-label"><?php esc_html_e( 'كلمة المرور الجديدة', 'code-elta6ur' ); ?></label>
									<div class="cdt-input-wrapper">
										<input type="password" name="new_password" id="cdt_new_pass" class="cdt-form-control" placeholder="••••••••">
									</div>
								</div>

								<div class="cdt-form-group">
									<label for="cdt_conf_pass" class="cdt-form-label"><?php esc_html_e( 'تأكيد كلمة المرور الجديدة', 'code-elta6ur' ); ?></label>
									<div class="cdt-input-wrapper">
										<input type="password" name="confirm_password" id="cdt_conf_pass" class="cdt-form-control" placeholder="••••••••">
									</div>
								</div>
							</div>

							<button type="submit" name="cdt_update_account_submit" class="cdt-btn-primary" style="width: auto; padding: 12px 28px;">
								<span><?php esc_html_e( 'حفظ كافة التغييرات', 'code-elta6ur' ); ?></span>
								<?php echo cdt_svg_icon( 'arrow-left' ); ?>
							</button>
						</form>
					</div>
				</div>

			</div>
		</div>

	</div>
</div>

<?php
get_footer();
