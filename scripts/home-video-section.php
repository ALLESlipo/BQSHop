<?php
/**
 * One-off: add a section with a muted, looping YouTube video in the background to the home
 * page, right after "What does the purchase of a Mimatest® include?". Styles: .mt-video in
 * the child theme. Run as admin so the <iframe> isn't stripped:
 *   docker compose run --rm wpcli wp --user=admin eval-file /scripts/home-video-section.php
 */

$template_id = 72; // front-page template
$video_id    = 'FXTKBjIK9XY';
$watch_url   = 'https://www.youtube.com/watch?v=' . $video_id;
$content     = get_post( $template_id )->post_content;

if ( false !== strpos( $content, 'mt-video' ) ) {
	WP_CLI::error( 'Section already exists. Nothing changed.' );
}

// Background-only player: muted (required for autoplay), looping, no controls/related videos.
$embed = 'https://www.youtube-nocookie.com/embed/' . $video_id . '?' . http_build_query( array(
	'autoplay'       => 1,
	'mute'           => 1,
	'loop'           => 1,
	'playlist'       => $video_id, // needed for loop to work
	'controls'       => 0,
	'playsinline'    => 1,
	'rel'            => 0,
	'disablekb'      => 1,
	'iv_load_policy' => 3,
), '', '&amp;' );

$section = '<!-- wp:group {"className":"mt-video","layout":{"type":"constrained","contentSize":"760px"}} -->' . "\n"
	. '<div class="wp-block-group mt-video"><!-- wp:html -->' . "\n"
	. '<div class="mt-video__bg" aria-hidden="true"><iframe src="' . $embed . '" title="Background video: how to collect a dried blood spot sample" allow="autoplay; encrypted-media; picture-in-picture" tabindex="-1" loading="lazy" frameborder="0"></iframe></div><div class="mt-video__tint" aria-hidden="true"></div>' . "\n"
	. '<!-- /wp:html -->' . "\n\n"
	. '<!-- wp:heading {"className":"mt-video__title","style":{"typography":{"textAlign":"center"}}} -->' . "\n"
	. '<h2 class="wp-block-heading has-text-align-center mt-video__title">Sample collection in a few minutes</h2>' . "\n"
	. '<!-- /wp:heading -->' . "\n\n"
	. '<!-- wp:paragraph {"className":"mt-video__text","style":{"typography":{"textAlign":"center"}}} -->' . "\n"
	. '<p class="has-text-align-center mt-video__text">A single drop of blood from a finger prick is all it takes. Watch how easy it is to collect your dried blood spot sample at home.</p>' . "\n"
	. '<!-- /wp:paragraph -->' . "\n\n"
	. '<!-- wp:buttons {"className":"mt-video__buttons"} -->' . "\n"
	. '<div class="wp-block-buttons mt-video__buttons"><!-- wp:button {"className":"mt-video__btn"} -->' . "\n"
	. '<div class="wp-block-button mt-video__btn"><a class="wp-block-button__link wp-element-button" href="/shop/">Order your test kit</a></div>' . "\n"
	. '<!-- /wp:button -->' . "\n\n"
	. '<!-- wp:button {"className":"mt-video__btn mt-video__btn--ghost"} -->' . "\n"
	. '<div class="wp-block-button mt-video__btn mt-video__btn--ghost"><a class="wp-block-button__link wp-element-button" href="' . $watch_url . '" target="_blank" rel="noreferrer noopener">Watch with sound</a></div>' . "\n"
	. '<!-- /wp:button --></div>' . "\n"
	. '<!-- /wp:buttons --></div>' . "\n"
	. '<!-- /wp:group -->';

// Insert at the end of <main>, right after the "What does the purchase include" cover.
$anchor = '<!-- /wp:cover --></main>';
if ( 1 !== substr_count( $content, $anchor ) ) {
	WP_CLI::error( 'Insertion point not found exactly once. Nothing changed.' );
}
$content = str_replace( $anchor, "<!-- /wp:cover -->\n\n" . $section . '</main>', $content );

wp_update_post( array( 'ID' => $template_id, 'post_content' => wp_slash( $content ) ) );
WP_CLI::success( 'Added the background video section.' );
