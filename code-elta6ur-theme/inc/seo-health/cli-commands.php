<?php
/**
 * أوامر WP-CLI لأتمتة فحص وإصلاح مشاكل السيو والأرشفة
 * Code Elta6ur WP-CLI SEO Health Commands Suite
 *
 * @package CodeElta6ur
 * @subpackage SEO_Health
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * Manage Code Elta6ur SEO Health & Google Search Console fixes
 */
class CDT_SEO_Health_CLI_Command {

	/**
	 * Display report of all noindexed URLs grouped by reason and type
	 *
	 * ## EXAMPLES
	 *
	 *     wp seo-health noindex-report
	 *
	 * @when after_wp_load
	 */
	public function noindex_report( $args, $assoc_args ) {
		WP_CLI::line( WP_CLI::colorize( '%G=== تقرير سياسة الفهرسة وحجب الصفحات الرقيقة (Noindex Report) ===%n' ) );

		$rules = cdt_seo_get_indexing_rules();
		global $wpdb;

		// 1. Account Pages
		$accounts = array( '/login/', '/registration/', '/lost-password/', '/my-account/' );
		WP_CLI::line( WP_CLI::colorize( '%C[1] صفحات الحسابات والمصادقة (Accounts & Auth):%n' ) );
		WP_CLI::line( '  - الحالة: ' . ( $rules['noindex_accounts'] ? '%Gnoindex,follow (مفعل)%n' : '%Rindex (غير موصى به)%n' ) );
		foreach ( $accounts as $acc ) {
			WP_CLI::line( '    * ' . home_url( $acc ) );
		}

		// 2. Thin Tags
		$min_tag = absint( $rules['tag_min_posts'] );
		$thin_tags = get_terms(
			array(
				'taxonomy'   => 'post_tag',
				'hide_empty' => false,
			)
		);
		$thin_count = 0;
		$thin_samples = array();
		if ( ! is_wp_error( $thin_tags ) ) {
			foreach ( $thin_tags as $t ) {
				if ( $t->count < $min_tag ) {
					$thin_count++;
					if ( count( $thin_samples ) < 5 ) {
						$thin_samples[] = get_term_link( $t ) . ' (' . $t->count . ' مقال)';
					}
				}
			}
		}

		WP_CLI::line( '' );
		WP_CLI::line( WP_CLI::colorize( "%C[2] أرشيف الوسوم الرقيقة (< {$min_tag} مقالات):%n" ) );
		WP_CLI::line( "  - عدد الوسوم المحجوبة بـ noindex: {$thin_count}" );
		foreach ( $thin_samples as $sample ) {
			WP_CLI::line( '    * ' . $sample );
		}

		// 3. Attachments
		$att_count = $wpdb->get_var( "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_type = 'attachment'" );
		WP_CLI::line( '' );
		WP_CLI::line( WP_CLI::colorize( '%C[3] صفحات المرفقات الفردية (Media Attachments):%n' ) );
		WP_CLI::line( "  - العدد الإجمالي: {$att_count} (يتم تحويلها 301 إلى المقال الأب مع noindex)" );

		WP_CLI::success( 'اكتمل تقرير الفهرسة.' );
	}

	/**
	 * Scan published posts for broken internal links
	 *
	 * ## EXAMPLES
	 *
	 *     wp seo-health broken-links
	 *
	 * @when after_wp_load
	 */
	public function broken_links( $args, $assoc_args ) {
		WP_CLI::line( 'جاري فحص الروابط الداخلية داخل محتوى المقالات...' );
		$broken = cdt_seo_scan_broken_internal_links( 200 );

		if ( empty( $broken ) ) {
			WP_CLI::success( 'لم يتم العثور على روابط داخلية مكسورة في المحتوى المفحوص!' );
			return;
		}

		$table_data = array();
		foreach ( $broken as $b ) {
			$table_data[] = array(
				'المقال المصدر'  => mb_substr( $b['post_title'], 0, 30, 'UTF-8' ) . ' (#' . $b['post_id'] . ')',
				'المسار المكسور' => $b['path'],
				'التحويل المقترح' => $b['suggestion'],
				'يوجد 301'       => $b['has_301'] ? 'نعم' : 'لا',
			);
		}

		WP_CLI\Utils\format_items( 'table', $table_data, array( 'المقال المصدر', 'المسار المكسور', 'التحويل المقترح', 'يوجد 301' ) );
		WP_CLI::warning( sprintf( 'تم اكتشاف %d رابط داخلي مكسور يحتاج مراجعة.', count( $broken ) ) );
	}

	/**
	 * Scan, repair, or rollback corrupted UTF-8 hex slugs missing % signs
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : Run analysis and display proposed fixes without altering the database (default).
	 *
	 * [--apply]
	 * : Execute updates, create 301 redirects, and update content links.
	 *
	 * [--rollback]
	 * : Restore previous slugs from metadata backups.
	 *
	 * ## EXAMPLES
	 *
	 *     wp seo-health slugs --dry-run
	 *     wp seo-health slugs --apply
	 *     wp seo-health slugs --rollback
	 *
	 * @when after_wp_load
	 */
	public function slugs( $args, $assoc_args ) {
		$apply    = isset( $assoc_args['apply'] );
		$rollback = isset( $assoc_args['rollback'] );
		$dry_run  = ! $apply && ! $rollback;

		if ( $rollback ) {
			WP_CLI::confirm( 'هل أنت متأكد من رغبتك في التراجع عن آخر عملية إصلاح للروابط؟' );
			$res = cdt_seo_rollback_slug_repairs();
			WP_CLI::success( sprintf( 'تم استرجاع %d رابط إلى قيمتها السابقة بنجاح.', count( $res ) ) );
			return;
		}

		WP_CLI::line( 'جاري فحص قاعدة البيانات بحثاً عن روابط Hex المعطوبة...' );
		$items = cdt_seo_scan_corrupted_slugs();

		if ( empty( $items ) ) {
			WP_CLI::success( 'رائع! لا توجد روابط معطوبة بتسلسل Hex مفقود في قاعدة البيانات.' );
			return;
		}

		$table_data = array();
		foreach ( $items as $item ) {
			$table_data[] = array(
				'ID'            => $item['id'],
				'الرابط الحالي' => $item['old_slug'],
				'النص المفكوك'  => $item['decoded'],
				'الرابط النظيف' => $item['clean_slug'],
			);
		}

		WP_CLI\Utils\format_items( 'table', $table_data, array( 'ID', 'الرابط الحالي', 'النص المفكوك', 'الرابط النظيف' ) );

		if ( $dry_run ) {
			WP_CLI::line( WP_CLI::colorize( '%Y[وضع المعاينة Dry-Run مفعل افتراضياً]%n لتطبيق التعديلات وإنشاء تحويلات 301 شغّل: wp seo-health slugs --apply' ) );
			return;
		}

		if ( $apply ) {
			WP_CLI::confirm( sprintf( 'سيتم الآن تعديل %d رابط وإنشاء تحويلات 301 وتحديث الروابط بالمحتوى. متابعة؟', count( $items ) ) );
			$success_count = 0;
			foreach ( $items as $item ) {
				$rep = cdt_seo_repair_post_slug( $item['id'], $item['clean_slug'], false );
				if ( ! empty( $rep['success'] ) ) {
					$success_count++;
				}
			}
			WP_CLI::success( sprintf( 'تم إصلاح وتحديث %d رابط بنجاح!', $success_count ) );
		}
	}

	/**
	 * Run thin content and orphan posts audit
	 *
	 * ## OPTIONS
	 *
	 * [--min-words=<number>]
	 * : Minimum word count threshold (default: 600).
	 *
	 * [--format=<format>]
	 * : Output format: table, csv, json. Default: table.
	 *
	 * ## EXAMPLES
	 *
	 *     wp seo-health thin --min-words=600
	 *
	 * @when after_wp_load
	 */
	public function thin( $args, $assoc_args ) {
		$min_words = isset( $assoc_args['min_words'] ) ? absint( $assoc_args['min_words'] ) : 600;
		$format    = isset( $assoc_args['format'] ) ? sanitize_key( $assoc_args['format'] ) : 'table';

		WP_CLI::line( "جاري فحص المحتوى بالحد الأدنى ({$min_words} كلمة)..." );
		$items = cdt_seo_audit_thin_content( array( 'min_words_post' => $min_words, 'limit' => 150 ) );

		if ( empty( $items ) ) {
			WP_CLI::success( 'لم يتم اكتشاف محتوى رقيق!' );
			return;
		}

		$table_data = array();
		foreach ( $items as $item ) {
			$table_data[] = array(
				'ID'          => $item['id'],
				'العنوان'     => mb_substr( $item['title'], 0, 30, 'UTF-8' ),
				'الكلمات'     => $item['words'],
				'H2'          => $item['has_h2'] ? 'نعم' : 'لا',
				'صور'         => $item['has_images'] ? 'نعم' : 'لا',
				'واردة'       => $item['incoming_links'],
				'التوصية'     => $item['recommendation'],
			);
		}

		WP_CLI\Utils\format_items( $format, $table_data, array( 'ID', 'العنوان', 'الكلمات', 'H2', 'صور', 'واردة', 'التوصية' ) );
		WP_CLI::warning( sprintf( 'تم رصد %d مقال/صفحة تحتاج إلى تحسين.', count( $items ) ) );
	}

	/**
	 * Audit active sitemap URLs
	 *
	 * ## OPTIONS
	 *
	 * [--sample=<number>]
	 * : Number of URLs to probe (default: 30).
	 *
	 * ## EXAMPLES
	 *
	 *     wp seo-health sitemap-audit --sample=20
	 *
	 * @when after_wp_load
	 */
	public function sitemap_audit( $args, $assoc_args ) {
		$sample = isset( $assoc_args['sample'] ) ? absint( $assoc_args['sample'] ) : 30;
		WP_CLI::line( "جاري فحص خريطة الموقع على عينة من {$sample} رابطاً..." );

		$res = cdt_seo_audit_sitemap_urls( $sample );
		if ( ! $res['success'] ) {
			WP_CLI::error( $res['error'] );
			return;
		}

		$table_data = array();
		foreach ( $res['items'] as $it ) {
			$table_data[] = array(
				'الرابط' => $it['url'],
				'الحالة' => $it['status'],
				'noindex' => $it['has_noindex'] ? 'نعم (تحذير!)' : 'لا (سليم)',
			);
		}

		WP_CLI\Utils\format_items( 'table', $table_data, array( 'الرابط', 'الحالة', 'noindex' ) );
		WP_CLI::success( 'اكتمل فحص خريطة الموقع.' );
	}
}

WP_CLI::add_command( 'seo-health', 'CDT_SEO_Health_CLI_Command' );
