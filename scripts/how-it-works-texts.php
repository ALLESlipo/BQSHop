<?php
/**
 * One-off: fix the "How it works" step texts on the home page.
 *   docker compose run --rm wpcli wp eval-file /scripts/how-it-works-texts.php
 */

$template_id = 72; // front-page template
$content     = get_post( $template_id )->post_content;

$professionals = 'If you are a professional and would like to offer Mimatest® as a service to your clients, please contact us on the email: <a href="mailto:lab@mimatest.net">lab@mimatest.net</a>';

$replacements = array(
	// Step 1: text now matches its heading.
	$professionals
		=> 'Order your Mimatest® kit online and it will be delivered to your door within 1–3 working days, with free shipping.',

	// Step 6: heading + grammar.
	'<strong>Download the results and the foodguide</strong>'
		=> '<strong>Download the results and the food guide</strong>',
	'You can download anytime and multiple times in our system the results for your gut test and with that you will had included a detailed food guide to start a diete. '
		=> 'You can download your gut test results from our platform as often as you like. They come with a detailed food guide to help you start your diet.',

	// The professionals note moves to its own line under the six steps.
	'<!-- wp:spacer {"height":"38px"} -->'
		=> '<!-- wp:paragraph {"className":"benefits-sub-heading","style":{"typography":{"fontSize":"16px","textAlign":"center"},"spacing":{"margin":{"top":"var:preset|spacing|60","bottom":"0"}}}} -->' . "\n"
		. '<p class="has-text-align-center benefits-sub-heading" style="margin-top:var(--wp--preset--spacing--60);margin-bottom:0;font-size:16px">' . $professionals . '</p>' . "\n"
		. '<!-- /wp:paragraph -->' . "\n\n"
		. '<!-- wp:spacer {"height":"38px"} -->',
);

foreach ( $replacements as $old => $new ) {
	if ( 1 !== substr_count( $content, $old ) ) {
		WP_CLI::error( 'Expected exactly one match for: ' . wp_strip_all_tags( substr( $old, 0, 60 ) ) . '… Nothing changed.' );
	}
	$content = str_replace( $old, $new, $content );
}

wp_update_post( array( 'ID' => $template_id, 'post_content' => wp_slash( $content ) ) );
WP_CLI::success( 'Updated ' . count( $replacements ) . ' texts.' );
