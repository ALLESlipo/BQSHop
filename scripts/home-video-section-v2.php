<?php
/**
 * One-off: simplify the home page video section — no heading/text, only the
 * "Order your test kit" button, and let the player be controlled (mute toggle) from JS.
 * Run as admin so the <iframe> isn't stripped:
 *   docker compose run --rm wpcli wp --user=admin eval-file /scripts/home-video-section-v2.php
 */

$template_id = 72;
$content     = get_post( $template_id )->post_content;

$remove = array(
	// Heading + paragraph.
	'#<!-- wp:heading \{"className":"mt-video__title".*?<!-- /wp:paragraph -->\s*#s',
	// "Watch with sound" button.
	'#\s*<!-- wp:button \{"className":"mt-video__btn mt-video__btn--ghost"\} -->.*?<!-- /wp:button -->#s',
);
foreach ( $remove as $pattern ) {
	$content = preg_replace( $pattern, '', $content, 1, $count );
	if ( 1 !== $count ) {
		WP_CLI::error( "Pattern not found: {$pattern}. Nothing changed." );
	}
}

// enablejsapi lets the sound button talk to the player.
$old = 'youtube-nocookie.com/embed/FXTKBjIK9XY?autoplay=1';
if ( 1 !== substr_count( $content, $old ) ) {
	WP_CLI::error( 'Video embed not found. Nothing changed.' );
}
$content = str_replace( $old, 'youtube-nocookie.com/embed/FXTKBjIK9XY?enablejsapi=1&amp;autoplay=1', $content );

wp_update_post( array( 'ID' => $template_id, 'post_content' => wp_slash( $content ) ) );
WP_CLI::success( 'Video section simplified.' );
