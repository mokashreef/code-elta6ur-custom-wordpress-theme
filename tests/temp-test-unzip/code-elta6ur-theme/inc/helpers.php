<?php
/**
 * الدوال المساعدة وأيقونات المنصة
 * Code Elta6ur Helper Functions & SVGs
 *
 * @package CodeElta6ur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get centralized theme option with default value
 *
 * @param string $key Option key.
 * @param mixed  $default Default value.
 * @return mixed
 */
function cdt_get_option( $key, $default = '' ) {
	$defaults = array(
		'cdt_email'              => 'contact@code-elta6ur.com',
		'cdt_phone'              => '+963983769230',
		'cdt_whatsapp'           => 'https://wa.me/963983769230',
		'cdt_telegram'          => 'https://t.me/code_elta6ur',
		'cdt_youtube_url'        => 'https://www.youtube.com/@code-elta6ur',
		'cdt_youtube_channel_id' => 'UCrATghXGpSJ2BzcWagEjRuw',
		'cdt_instagram'          => 'https://www.instagram.com/code_elta6ur/',
		'cdt_facebook'           => 'https://www.facebook.com/code.elta6ur',
		'cdt_github'             => 'https://github.com/mokashreef',
		'cdt_linkedin'           => '',
		'cdt_primary_color'      => '#0170B9',
		'cdt_secondary_color'    => '#3B82F6',
		'cdt_accent_color'       => '#38BDF8',
		'cdt_hero_title'         => 'رؤية مبتكرة في عالم البرمجة والعمل وحتى الذكاء الاصطناعي',
		'cdt_hero_subtitle'      => 'منصة تعليمية عربية تركز على تبسيط مفاهيم البرمجة وتطوير الويب خطوة بخطوة، مع كبرى الدورات التدريبية والمقالات العملية.',
		'cdt_hero_cta_text'      => 'ابدأ التعلم الآن',
		'cdt_hero_cta_url'       => home_url( '/%d8%a7%d9%84%d9%83%d9%88%d8%b1%d8%b3%d8%a7%d8%aa-%d8%a7%d9%84%d8%aa%d8%b9%d9%84%d9%8a%d9%85%d9%8a%d8%a9/' ),
		'cdt_footer_about'       => 'منصة تعليمية عربية متخصصة بتعليم البرمجة وتطوير الويب والذكاء الاصطناعي، عبر دورات ومقالات وأدوات عملية تساعد المتعلم على بناء مهاراته خطوة بخطوة.',
		'cdt_copyright_text'     => 'جميع الحقوق محفوظة © كود التطور 2026',
	);

	$effective_default = isset( $defaults[ $key ] ) ? $defaults[ $key ] : $default;
	return get_theme_mod( $key, $effective_default );
}

/**
 * Returns clean inline SVG icons without external font dependencies
 *
 * @param string $icon Icon name.
 * @return string SVG markup.
 */
function cdt_svg_icon( $icon ) {
	$icons = array(
		'youtube'   => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
		'telegram'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.196 1.006.128.832.941z"/></svg>',
		'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
		'facebook'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
		'github'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>',
		'whatsapp'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>',
		'email'     => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>',
		'search'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
		'menu'      => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>',
		'close'     => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
		'play'      => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>',
		'clock'     => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
		'user'      => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
		'calendar'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
		'arrow-left' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
		'copy'      => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>',
		'check'     => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>',
		'external-link' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>',
	);

	return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
}

/**
 * Calculates estimated reading time for Arabic content
 *
 * @param int $post_id Post ID.
 * @return int Minutes.
 */
function cdt_get_reading_time( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	$content = get_post_field( 'post_content', $post_id );
	$word_count = count( preg_split( '/\s+/u', wp_strip_all_tags( $content ) ) );
	$reading_speed = 180; // Words per minute in Arabic
	$minutes = ceil( $word_count / $reading_speed );
	return max( 1, $minutes );
}

/**
 * Outputs clean custom logo or default image
 */
function cdt_the_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
	} else {
		$logo_url = CDT_THEME_URI . '/assets/images/logo.png';
		echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="cdt-site-logo" rel="home">';
		echo '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="46" height="46">';
		echo '<span class="cdt-logo-text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
		echo '</a>';
	}
}

/**
 * Retrieves the latest YouTube videos from Code Elta6ur channel dynamically
 *
 * Automatically fetches the real feed from https://www.youtube.com/@code-elta6ur/videos,
 * caches it using a WordPress transient for 6 hours ("أول بأول"), and falls back
 * to verified channel uploads if offline or rate-limited.
 *
 * @param int $limit Number of videos to retrieve. Default 6.
 * @return array List of video items with 'video_id' and 'title'.
 */
function cdt_get_latest_youtube_videos( $limit = 6 ) {
	$limit = absint( $limit );
	if ( $limit <= 0 ) {
		$limit = 6;
	}

	$transient_key = 'cdt_latest_yt_videos_' . $limit;
	$cached_videos = get_transient( $transient_key );

	if ( false !== $cached_videos && is_array( $cached_videos ) && ! empty( $cached_videos ) ) {
		return $cached_videos;
	}

	$videos = array();

	// 1. Fetch channel feed dynamically from YouTube
	$channel_url = cdt_get_option( 'cdt_youtube_url', 'https://www.youtube.com/@code-elta6ur' );
	$fetch_url   = rtrim( $channel_url, '/' ) . '/videos';

	$response = wp_remote_get(
		$fetch_url,
		array(
			'timeout'    => 8,
			'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
		)
	);

	if ( ! is_wp_error( $response ) && 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
		$body = wp_remote_retrieve_body( $response );

		if ( preg_match( '/var ytInitialData\s*=\s*({.+?});<\/script>/s', $body, $matches ) || preg_match( '/ytInitialData\s*=\s*({.+?});/s', $body, $matches ) ) {
			$json_data = json_decode( $matches[1], true );
			if ( $json_data && is_array( $json_data ) ) {
				$extracted = array();
				$search_fn = function( $item ) use ( &$search_fn, &$extracted, $limit ) {
					if ( count( $extracted ) >= $limit ) {
						return;
					}
					if ( is_array( $item ) ) {
						if ( isset( $item['lockupViewModel'] ) ) {
							$lm    = $item['lockupViewModel'];
							$vid   = isset( $lm['contentId'] ) ? $lm['contentId'] : '';
							$title = isset( $lm['metadata']['lockupMetadataViewModel']['title']['content'] ) ? $lm['metadata']['lockupMetadataViewModel']['title']['content'] : '';
							if ( $vid && $title ) {
								$extracted[ $vid ] = array(
									'video_id' => $vid,
									'title'    => wp_strip_all_tags( $title ),
								);
							}
						} elseif ( isset( $item['videoRenderer'] ) ) {
							$vr    = $item['videoRenderer'];
							$vid   = isset( $vr['videoId'] ) ? $vr['videoId'] : '';
							$title = '';
							if ( isset( $vr['title']['runs'][0]['text'] ) ) {
								$title = $vr['title']['runs'][0]['text'];
							} elseif ( isset( $vr['title']['simpleText'] ) ) {
								$title = $vr['title']['simpleText'];
							}
							if ( $vid && $title ) {
								$extracted[ $vid ] = array(
									'video_id' => $vid,
									'title'    => wp_strip_all_tags( $title ),
								);
							}
						}
						foreach ( $item as $sub ) {
							if ( is_array( $sub ) ) {
								$search_fn( $sub );
							}
						}
					}
				};

				$search_fn( $json_data );

				if ( ! empty( $extracted ) ) {
					$videos = array_values( $extracted );
				}
			}
		}
	}

	// 2. Fallback to verified real channel uploads from @code-elta6ur
	if ( empty( $videos ) ) {
		$videos = array(
			array(
				'video_id' => 'YrbvArm6Lt0',
				'title'    => esc_html__( 'The Complete Story of JavaScript | How It Started and Why It Became the World\'s Language', 'code-elta6ur' ),
			),
			array(
				'video_id' => '0lBfrJ5W8FM',
				'title'    => esc_html__( 'بعد سنتين من البرمجة... أخيرًا رح أريكم ماذا بنيت! | Code Ora', 'code-elta6ur' ),
			),
			array(
				'video_id' => 'QaUWW9WIeYc',
				'title'    => esc_html__( 'Is it worth wasting two years of your life on one project?', 'code-elta6ur' ),
			),
			array(
				'video_id' => 'N6xC8NC9uE0',
				'title'    => esc_html__( 'Do You Use VS Code? These Are the AI Extensions You Need Now', 'code-elta6ur' ),
			),
			array(
				'video_id' => 'UwKH4VEBaWo',
				'title'    => esc_html__( 'Shed GPT 5.6 vs. Claude Fable 5 | Which Is Better for Your Work?', 'code-elta6ur' ),
			),
			array(
				'video_id' => 'x6QbqCUnc1M',
				'title'    => esc_html__( 'The Journey of Building Code Ora: Challenges, Mistakes, and Results!', 'code-elta6ur' ),
			),
			array(
				'video_id' => '63fDnnA6VyI',
				'title'    => esc_html__( 'I stopped using AI for a day... and the difference is huge!', 'code-elta6ur' ),
			),
			array(
				'video_id' => 'YNP9nig4x3c',
				'title'    => esc_html__( 'The Hardest Part of Programming Isn\'t Learning It... It\'s Surviving It', 'code-elta6ur' ),
			),
			array(
				'video_id' => 'gbgitGWIa24',
				'title'    => esc_html__( 'البرمجة اليدوية انتهت؟ تسريبات ChatGPT تقول نعم!', 'code-elta6ur' ),
			),
		);
	}

	$videos = array_slice( $videos, 0, $limit );

	// Cache in transient for 6 hours
	set_transient( $transient_key, $videos, 6 * HOUR_IN_SECONDS );

	return $videos;
}

/**
 * Returns the accredited course category IDs that represent learning tracks
 * (HTML: 53, CSS: 62, Bootstrap: 71, JavaScript: 74, React: 76, C++: 80, TypeScript: 119)
 *
 * @return int[] List of course category IDs.
 */
function cdt_get_course_category_ids() {
	$course_cat_ids = array( 53, 62, 71, 74, 76, 80, 119 );

	// If gamification course progress engine is loaded, sync dynamically
	if ( function_exists( 'cdt_get_courses_catalog' ) ) {
		$catalog = cdt_get_courses_catalog();
		foreach ( $catalog as $course ) {
			if ( ! empty( $course['cat_id'] ) ) {
				$course_cat_ids[] = absint( $course['cat_id'] );
			}
		}
		$course_cat_ids = array_values( array_unique( $course_cat_ids ) );
	}

	return apply_filters( 'cdt_course_category_ids', $course_cat_ids );
}

/**
 * Checks whether a given category ID belongs to a course track
 *
 * @param int $category_id Category Term ID.
 * @return bool
 */
function cdt_is_course_category( $category_id ) {
	$category_id = absint( $category_id );
	if ( ! $category_id ) {
		return false;
	}
	return in_array( $category_id, cdt_get_course_category_ids(), true );
}

/**
 * Returns the primary category ID for Artificial Intelligence
 *
 * @return int Term ID (Default: 9).
 */
function cdt_get_ai_category_id() {
	$term = get_category_by_slug( 'الذكاء-الاصطناعي' );
	if ( $term && ! is_wp_error( $term ) ) {
		return (int) $term->term_id;
	}
	return 9;
}

/**
 * Returns the canonical URL for the Artificial Intelligence section
 *
 * @return string
 */
function cdt_get_ai_section_url() {
	$ai_id = cdt_get_ai_category_id();
	$link  = get_category_link( $ai_id );
	if ( ! empty( $link ) && ! is_wp_error( $link ) ) {
		return $link;
	}
	return home_url( '/category/%d8%a7%d9%84%d8%b0%d9%83%d8%a7%d8%a1-%d8%a7%d9%84%d8%a7%d8%b5%d8%b7%d9%86%d8%a7%d8%b9%d9%8a/' );
}

/**
 * Determines the category object to visually display to the visitor on post cards and article views.
 *
 * Requirements:
 * - Visually hides "الذكاء الاصطناعي" from the category badge on cards and single articles.
 * - If the post has multiple categories (e.g. AI + Tech or AI + Learn Programming),
 *   it selects the companion general category so a badge can still be displayed.
 * - If the post ONLY belongs to "الذكاء الاصطناعي", it returns null so no badge displaying
 *   the forbidden category name is shown to the visitor.
 * - Preserves the category relationship in the database, REST API, sitemaps, and search engines 100%.
 *
 * @param int|null $post_id Post ID.
 * @return WP_Term|null Category object to display, or null if hidden.
 */
function cdt_get_display_category( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	if ( ! $post_id ) {
		return null;
	}

	$categories = get_the_category( $post_id );
	if ( empty( $categories ) || is_wp_error( $categories ) ) {
		return null;
	}

	$ai_cat_id = cdt_get_ai_category_id();

	// Search for the first category that is NOT AI
	foreach ( $categories as $cat ) {
		$cat_id = (int) $cat->term_id;
		$slug   = urldecode( $cat->slug );
		if ( $cat_id !== $ai_cat_id && 'الذكاء-الاصطناعي' !== $slug && esc_html__( 'الذكاء الاصطناعي', 'code-elta6ur' ) !== $cat->name && 'الذكاء الاصطناعي' !== $cat->name ) {
			return $cat;
		}
	}

	// Post only belongs to AI category - hide visual name from visitor
	return null;
}

/**
 * Retrieves general blog categories excluding course-specific categories
 *
 * @param array $args Additional get_categories arguments.
 * @return WP_Term[]
 */
function cdt_get_general_blog_categories( $args = array() ) {
	$defaults = array(
		'orderby'    => 'count',
		'order'      => 'DESC',
		'hide_empty' => true,
		'exclude'    => cdt_get_course_category_ids(),
	);

	$parsed_args = wp_parse_args( $args, $defaults );
	// Ensure course categories are always excluded
	if ( ! empty( $parsed_args['exclude'] ) && is_array( $parsed_args['exclude'] ) ) {
		$parsed_args['exclude'] = array_unique( array_merge( $parsed_args['exclude'], cdt_get_course_category_ids() ) );
	} else {
		$parsed_args['exclude'] = cdt_get_course_category_ids();
	}

	return get_categories( $parsed_args );
}

