<?php
/**
 * صفحة نتائج البحث الموحدة
 * Code Elta6ur Search Results Template
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php get_template_part( 'template-parts/hero/hero-page' ); ?>

<?php cdt_breadcrumbs(); ?>

<div class="cdt-section">
	<div class="cdt-container">
		<?php
		$search_query       = trim( get_search_query() );
		$matching_tools     = array();
		$matching_languages = array();

		if ( ! empty( $search_query ) ) {
			$q_lower = mb_strtolower( $search_query, 'UTF-8' );

			if ( function_exists( 'cdt_get_tools_registry' ) ) {
				foreach ( cdt_get_tools_registry() as $t_slug => $t_data ) {
					$name_ar = mb_strtolower( $t_data['name'], 'UTF-8' );
					$name_en = mb_strtolower( $t_data['name_en'], 'UTF-8' );
					$desc    = mb_strtolower( $t_data['description'], 'UTF-8' );
					$kw      = mb_strtolower( implode( ' ', $t_data['keywords'] ), 'UTF-8' );

					if ( false !== mb_stripos( $name_ar, $q_lower ) ||
						false !== mb_stripos( $name_en, $q_lower ) ||
						false !== mb_stripos( $t_slug, $q_lower ) ||
						false !== mb_stripos( $kw, $q_lower ) ||
						false !== mb_stripos( $desc, $q_lower ) ) {
						$matching_tools[] = $t_data;
					}
				}
			}

			if ( function_exists( 'cdt_get_editor_languages' ) ) {
				foreach ( cdt_get_editor_languages() as $l_slug => $l_data ) {
					$l_name    = mb_strtolower( $l_data['name'], 'UTF-8' );
					$l_name_ar = mb_strtolower( $l_data['name_ar'], 'UTF-8' );

					if ( false !== mb_stripos( $l_name, $q_lower ) ||
						false !== mb_stripos( $l_name_ar, $q_lower ) ||
						false !== mb_stripos( $l_slug, $q_lower ) ) {
						$matching_languages[] = $l_data;
					}
				}
			}
		}

		$has_tools_or_editor = ! empty( $matching_tools ) || ! empty( $matching_languages );
		?>

		<?php if ( $has_tools_or_editor ) : ?>
			<div style="background: var(--brand-surface, #F1F5F9); border: 1px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-md, 16px); padding: var(--space-6, 1.5rem); margin-block-end: var(--space-10, 2.5rem);">
				<div style="display: flex; align-items: center; justify-content: space-between; margin-block-end: var(--space-4, 1rem); flex-wrap: wrap; gap: 8px;">
					<h2 style="font-size: var(--font-size-xl, 1.25rem); margin: 0; color: var(--brand-dark, #0F172A);">
						🛠️ الأدوات والمحررات البرمجية المطابقة
					</h2>
					<span class="cdt-badge cdt-badge-accent">
						<?php echo esc_html( count( $matching_tools ) + count( $matching_languages ) ); ?> نتيجة
					</span>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: var(--space-4, 1rem);">
					<?php foreach ( $matching_tools as $m_tool ) : ?>
						<a href="<?php echo esc_url( home_url( '/tools/' . $m_tool['slug'] . '/' ) ); ?>" style="display: block; background: #FFFFFF; border: 1px solid var(--brand-border, #E2E8F0); border-radius: var(--radius-sm, 10px); padding: var(--space-4, 1rem); text-decoration: none; color: inherit; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
							<div style="font-size: 11px; color: var(--brand-primary, #0170B9); font-weight: 700; margin-bottom: 4px;">أداة تقنية • <?php echo esc_html( $m_tool['name_en'] ); ?></div>
							<h3 style="font-size: var(--font-size-md, 1rem); margin: 0 0 6px 0;"><?php echo esc_html( $m_tool['name'] ); ?></h3>
							<p style="font-size: var(--font-size-xs, 0.75rem); color: var(--brand-text-muted, #475569); margin: 0; line-height: 1.5;"><?php echo esc_html( $m_tool['tagline'] ); ?></p>
						</a>
					<?php endforeach; ?>

					<?php foreach ( $matching_languages as $m_lang ) : ?>
						<a href="<?php echo esc_url( home_url( '/editor/' . $m_lang['slug'] . '/' ) ); ?>" style="display: block; background: #0F172A; color: #FFFFFF; border: 1px solid #334155; border-radius: var(--radius-sm, 10px); padding: var(--space-4, 1rem); text-decoration: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
							<div style="font-size: 11px; color: #38BDF8; font-weight: 700; margin-bottom: 4px;">محرر ومترجم أونلاين • <?php echo esc_html( $m_lang['version'] ); ?></div>
							<h3 style="font-size: var(--font-size-md, 1rem); color: #FFFFFF; margin: 0 0 6px 0;"><?php echo esc_html( $m_lang['name'] . ' (' . $m_lang['name_ar'] . ')' ); ?></h3>
							<p style="font-size: var(--font-size-xs, 0.75rem); color: #94A3B8; margin: 0; line-height: 1.5;"><?php echo esc_html( $m_lang['tagline'] ); ?></p>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div style="margin-block-end: var(--space-8, 2rem); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: var(--space-4, 1rem);">
				<p style="margin: 0; font-weight: 600; color: var(--brand-text-muted, #475569);">
					<?php
					global $wp_query;
					/* translators: %d: number of results */
					printf( esc_html__( 'المقالات والدروس المطابقة (%d مقال):', 'code-elta6ur' ), absint( $wp_query->found_posts ) );
					?>
				</p>
				<div style="max-width: 380px; width: 100%;">
					<?php get_search_form(); ?>
				</div>
			</div>

			<div class="cdt-grid-3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/cards/card-post' );
				endwhile;
				?>
			</div>

			<div class="cdt-pagination">
				<?php
				the_posts_pagination(
					array(
						'prev_text' => '&larr; ' . esc_html__( 'السابق', 'code-elta6ur' ),
						'next_text' => esc_html__( 'التالي', 'code-elta6ur' ) . ' &rarr;',
					)
				);
				?>
			</div>
		<?php elseif ( ! $has_tools_or_editor ) : ?>
			<?php get_template_part( 'template-parts/content/content-none' ); ?>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
