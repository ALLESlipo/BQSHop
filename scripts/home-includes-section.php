<?php
/**
 * One-off: add the "What does the purchase of a Mimatest® include?" section to the home
 * page, right after "Here's how it works". Styles live in the child theme (.mt-includes).
 *   docker compose run --rm wpcli wp eval-file /scripts/home-includes-section.php
 */

$template_id = 72;   // front-page template
$bg_id       = 86;   // mimatest_web: the box on light wood
$bg_url      = wp_get_attachment_url( $bg_id );
$content     = get_post( $template_id )->post_content;

if ( false !== strpos( $content, 'mt-includes' ) ) {
	WP_CLI::error( 'Section already exists. Nothing changed.' );
}

$left = array(
	array( 'fa-vial',        'The set contains all the materials needed for safe and accurate sample collection, making home testing easy and convenient.' ),
	array( 'fa-user-secret', 'The test kit ID, in combination with a personal access code, offers anonymity and secure online retrieval of results.' ),
	array( 'fa-envelope',    'A prepaid return envelope for the completed test samples ensures uncomplicated and free shipping to the laboratory.' ),
);
$right = array(
	array( 'fa-map',         'The package includes easy-to-understand instructions for blood collection, which are also available online in several languages.' ),
	array( 'fa-hands-wash',  'Designed for single use, the Mimatest® ensures hygienic application.' ),
	array( 'fa-globe',       'The results can be accessed securely and anonymously at any time on yourtestresults.net.' ),
);

function mt_card( $icon, $text ) {
	return '<!-- wp:group {"className":"mt-include-card","layout":{"type":"default"}} -->' . "\n"
		. '<div class="wp-block-group mt-include-card"><!-- wp:html -->' . "\n"
		. '<i class="fas ' . $icon . '" aria-hidden="true"></i>' . "\n"
		. '<!-- /wp:html -->' . "\n\n"
		. '<!-- wp:paragraph -->' . "\n"
		. '<p>' . $text . '</p>' . "\n"
		. '<!-- /wp:paragraph --></div>' . "\n"
		. '<!-- /wp:group -->';
}

function mt_column( $width, $class, $inner ) {
	return '<!-- wp:column {"verticalAlignment":"center","width":"' . $width . '","className":"' . $class . '"} -->' . "\n"
		. '<div class="wp-block-column is-vertically-aligned-center ' . $class . '" style="flex-basis:' . $width . '">' . $inner . '</div>' . "\n"
		. '<!-- /wp:column -->';
}

$cards = function ( $items ) {
	return implode( "\n\n", array_map( function ( $c ) { return mt_card( $c[0], $c[1] ); }, $items ) );
};

$section = '<!-- wp:cover {"url":"' . $bg_url . '","id":' . $bg_id . ',"dimRatio":0,"isUserOverlayColor":true,"minHeight":720,"minHeightUnit":"px","isDark":false,"sizeSlug":"full","className":"mt-includes","layout":{"type":"constrained","contentSize":"92%"}} -->' . "\n"
	. '<div class="wp-block-cover is-light mt-includes" style="min-height:720px"><img class="wp-block-cover__image-background wp-image-' . $bg_id . ' size-full" alt="" src="' . $bg_url . '" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background"></span><div class="wp-block-cover__inner-container">'
	. '<!-- wp:heading {"className":"mt-includes__title","style":{"typography":{"textAlign":"center"}}} -->' . "\n"
	. '<h2 class="wp-block-heading has-text-align-center mt-includes__title">What does the purchase of a Mimatest® include?</h2>' . "\n"
	. '<!-- /wp:heading -->' . "\n\n"
	. '<!-- wp:paragraph {"className":"mt-includes__intro","style":{"typography":{"textAlign":"center"}}} -->' . "\n"
	. '<p class="has-text-align-center mt-includes__intro">The comprehensive set is based on proven technology and offers high accuracy according to professional standards.</p>' . "\n"
	. '<!-- /wp:paragraph -->' . "\n\n"
	. '<!-- wp:columns {"verticalAlignment":"center","className":"mt-includes__cols"} -->' . "\n"
	. '<div class="wp-block-columns are-vertically-aligned-center mt-includes__cols">'
	. mt_column( '34%', 'mt-includes__col', $cards( $left ) ) . "\n\n"
	. mt_column( '32%', 'mt-includes__gap', '' ) . "\n\n"
	. mt_column( '34%', 'mt-includes__col', $cards( $right ) )
	. '</div>' . "\n"
	. '<!-- /wp:columns --></div></div>' . "\n"
	. '<!-- /wp:cover -->';

// Insert at the end of <main>, i.e. right after the "How it works" group.
$anchor = '<!-- /wp:group --></main>';
if ( 1 !== substr_count( $content, $anchor ) ) {
	WP_CLI::error( 'Insertion point not found exactly once. Nothing changed.' );
}
$content = str_replace( $anchor, "<!-- /wp:group -->\n\n" . $section . "</main>", $content );

wp_update_post( array( 'ID' => $template_id, 'post_content' => wp_slash( $content ) ) );
WP_CLI::success( 'Added the "What does the purchase include" section.' );
