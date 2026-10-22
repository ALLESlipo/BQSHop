<?php
/**
 * SEO basics for the live site (Rank Math + TranslatePress). IDs are the live site's.
 *
 *   wp eval-file seo-setup.php apply <og-image-attachment-id>   # settings, titles, descriptions, alts, H1
 *   (visit the /en/ pages so TranslatePress registers the new Albanian texts)
 *   wp eval-file seo-setup.php dictionary                       # store the English versions
 *
 * Titles ≤ 60 characters, descriptions ≤ 155: what Google shows without cutting off.
 * Afterwards everything is editable in the admin: Rank Math box under each page/product
 * (Albanian), and Translate Page → "SEO / Meta" strings (English).
 */

// ID => [ Albanian title, Albanian description, English title, English description ]
$meta = array(
	83  => array( // Home (front page)
		'Test i zorrëve në shtëpi me një pikë gjaku | Mimatest',
		'Mimatest®: test minimalisht invaziv për sëmundjet e zorrës së hollë, nga shtëpia. Vetëm një pikë gjaku, rezultate online brenda 2–3 javësh.',
		'At-home gut test with a single drop of blood | Mimatest',
		'Mimatest®: a minimally invasive test for small bowel diseases, done at home. Just one drop of blood, with results online within 2–3 weeks.',
	),
	5   => array( // Shop
		'Bli testin Mimatest® – kit testimi për shtëpi | Mimatest',
		'Porositni kitin Mimatest® për testimin e zorrëve nga shtëpia: një test ose paketa për dy. Dorëzim brenda 1–3 ditësh pune, transport falas.',
		'Buy the Mimatest® test – at-home testing kit | Mimatest',
		'Order the Mimatest® kit for at-home gut testing: a single test or the pair kit. Delivery within 1–3 working days, free shipping.',
	),
	12  => array( // Product: single test
		'Mimatest® (një test) – test i zorrëve në shtëpi | Mimatest',
		'Kit testimi njëpërdorimësh me udhëzime dhe zarf kthimi të parapaguar. Mjafton një pikë gjaku nga gishti; rezultatet i merrni online.',
		'Mimatest® (single) – at-home gut test | Mimatest',
		'Single-use test kit with instructions and a prepaid return envelope. One drop of blood from a finger prick is all it takes; results online.',
	),
	97  => array( // Product: pair kit
		'Mimatest® Paketë për dy – dy teste të zorrëve | Mimatest',
		'Dy kite testimi njëpërdorimëshe me udhëzime dhe dy zarfe kthimi të parapaguara. Testimi i zorrëve nga shtëpia për dy persona, transport falas.',
		'Mimatest® Pair Kit – two at-home gut tests | Mimatest',
		'Two single-use test kits with instructions and two prepaid return envelopes. At-home gut testing for two people, with free shipping.',
	),
	135 => array( // About us
		'Rreth nesh – pse Mimatest® është unik | Mimatest',
		'Mimatest® analizon zorrën e hollë përmes gjakut të tharë, plotësisht nga shtëpia. Rezultatet online dhe, sipas kërkesës, një konsultë profesionale.',
		'About us – why Mimatest® is unique | Mimatest',
		'Mimatest® analyses the small intestine using dried blood spots, entirely from home. Results online and, on request, a professional consultation.',
	),
	161 => array( // Contact and support
		'Kontakt dhe mbështetje | Mimatest',
		'Keni pyetje për testin Mimatest®, porosinë ose rezultatet tuaja? Na shkruani dhe do t’ju përgjigjemi sa më shpejt.',
		'Contact and support | Mimatest',
		'Questions about the Mimatest® test, your order or your results? Write to us and we will get back to you as soon as possible.',
	),
	3   => array( // Privacy policy
		'Politika e privatësisë | Mimatest',
		'Si i mbledhim, përdorim dhe mbrojmë të dhënat tuaja personale kur vizitoni faqen dhe blini testin Mimatest®.',
		'Privacy Policy | Mimatest',
		'How we collect, use and protect your personal data when you visit our website and buy the Mimatest® test.',
	),
	10  => array( // Terms and conditions
		'Kushtet e përdorimit | Mimatest',
		'Kushtet e përgjithshme për blerjen dhe përdorimin e testit Mimatest®: porosia, pagesa, dërgesa, e drejta e tërheqjes dhe përgjegjësia.',
		'Terms and Conditions | Mimatest',
		'General terms for buying and using the Mimatest® test: ordering, payment, delivery, right of withdrawal and liability.',
	),
);

// Pages Google shouldn't index: cart, checkout, my account, the empty "Check Testresults" page.
$noindex = array( 6, 7, 8, 213 );

// Attachment ID => [ Albanian alt, English alt ]
$alts = array(
	127 => array( 'Kiti i testimit Mimatest® – një test', 'Mimatest® test kit – single test' ),
	128 => array( 'Dy kite testimi Mimatest® – paketa për dy', 'Two Mimatest® test kits – pair kit' ),
	130 => array( 'Kutia e kitit Mimatest® mbi një tavolinë druri', 'Mimatest® kit box on a wooden table' ),
	203 => array( 'bq analytics', 'bq analytics' ),
);

$mode = isset( $args[0] ) ? $args[0] : '';

if ( 'apply' === $mode ) {
	$og_id = isset( $args[1] ) ? (int) $args[1] : 0;
	if ( ! $og_id || ! wp_get_attachment_url( $og_id ) ) {
		WP_CLI::error( 'Pass the attachment ID of the 1200×630 sharing image.' );
	}

	// Modules: only what this shop uses (no BuddyPress/forums/ACF/AI/Analytics nags).
	update_option( 'rank_math_modules', array( 'link-counter', 'seo-analysis', 'sitemap', 'rich-snippet', 'woocommerce' ) );
	update_option( 'rank_math_wizard_completed', true );
	update_option( 'rank_math_registration_skip', true );

	$t = get_option( 'rank-math-options-titles', array() );
	$t['title_separator']                = '|';
	$t['knowledgegraph_type']            = 'company';
	$t['knowledgegraph_name']            = 'Mimatest';
	$t['website_name']                   = 'Mimatest';
	$t['knowledgegraph_logo_id']         = 203;
	$t['knowledgegraph_logo']            = wp_get_attachment_url( 203 );
	$t['open_graph_image_id']            = $og_id;
	$t['open_graph_image']               = wp_get_attachment_url( $og_id );
	$t['disable_author_archives']        = 'on';  // single author: don't expose the username
	$t['pt_page_default_rich_snippet']   = 'off'; // pages aren't articles
	$t['tax_product_cat_custom_robots']  = 'on';  // the "Teste" category only repeats the shop
	$t['tax_product_cat_robots']         = array( 'noindex' );
	$t['tax_category_custom_robots']     = 'on';  // blog category archives: thin duplicates of the posts
	$t['tax_category_robots']            = array( 'noindex' );
	update_option( 'rank-math-options-titles', $t );

	$s = get_option( 'rank-math-options-sitemap', array() );
	$s['authors_sitemap']      = 'off';
	$s['tax_category_sitemap'] = 'off';
	update_option( 'rank-math-options-sitemap', $s );

	foreach ( $meta as $id => $m ) {
		update_post_meta( $id, 'rank_math_title', $m[0] );
		update_post_meta( $id, 'rank_math_description', $m[1] );
	}
	foreach ( $noindex as $id ) {
		update_post_meta( $id, 'rank_math_robots', array( 'noindex' ) );
	}

	// Image descriptions: on the attachments, and in the footer gallery markup (stored inline).
	foreach ( $alts as $id => $a ) {
		update_post_meta( $id, '_wp_attachment_image_alt', $a[0] );
	}
	$footer = get_post( 120 );
	$html   = $footer->post_content;
	foreach ( $alts as $id => $a ) {
		$html = preg_replace( '/alt=""(\s+class="wp-image-' . $id . '")/', 'alt="' . esc_attr( $a[0] ) . '"$1', $html );
	}
	wp_update_post( array( 'ID' => 120, 'post_content' => wp_slash( $html ) ) );

	// Home hero headline becomes the page's H1 (it was an H2, so the page had no H1).
	$home  = get_post( 72 );
	$hero  = '<!-- wp:heading {"style":{"typography":{"fontStyle":"normal","fontWeight":"1000","fontSize":"35px"}';
	$count = 0;
	$c     = str_replace( $hero, '<!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"1000","fontSize":"35px"}', $home->post_content, $count );
	if ( 1 === $count ) {
		$c = preg_replace( '#<h2 (class="wp-block-heading has-heading-color[^>]*font-weight:1000">)(.*?)</h2>#s', '<h1 $1$2</h1>', $c, 1 );
		wp_update_post( array( 'ID' => 72, 'post_content' => wp_slash( $c ) ) );
	} else {
		WP_CLI::warning( 'Hero heading not found; H1 unchanged.' );
	}

	WP_CLI::success( 'SEO settings, titles, descriptions, alts and H1 applied.' );

} elseif ( 'dictionary' === $mode ) {
	global $wpdb;
	$table = $wpdb->prefix . 'trp_dictionary_sq_en_us';
	$norm  = function ( $t ) {
		$t = html_entity_decode( wp_strip_all_tags( $t ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$t = str_replace( array( '’', '‘' ), "'", $t );
		return trim( preg_replace( '/\s+/u', ' ', $t ) );
	};
	$map = array();
	foreach ( $meta as $m ) {
		$map[ $norm( $m[0] ) ] = array( $m[0], $m[2] );
		$map[ $norm( $m[1] ) ] = array( $m[1], $m[3] );
	}
	foreach ( $alts as $a ) {
		if ( $a[0] !== $a[1] ) {
			$map[ $norm( $a[0] ) ] = array( $a[0], $a[1] );
		}
	}
	$fill = function () use ( $wpdb, $table, $norm, $map ) {
		$done = array();
		foreach ( $wpdb->get_results( "SELECT id, original FROM {$table}" ) as $row ) {
			$key = $norm( $row->original );
			if ( isset( $map[ $key ] ) ) {
				$wpdb->update( $table, array( 'translated' => $map[ $key ][1], 'status' => 2 ), array( 'id' => $row->id ) );
				$done[ $key ] = true;
			}
		}
		return $done;
	};
	$done   = $fill();
	$unseen = array();
	foreach ( $map as $key => $pair ) {
		if ( ! isset( $done[ $key ] ) ) {
			$unseen[] = $pair[0];
		}
	}
	if ( $unseen ) {
		TRP_Translate_Press::get_trp_instance()->get_component( 'query' )->insert_strings( $unseen, 'en_US' );
		$done = $fill();
	}
	WP_CLI::success( 'English SEO texts saved: ' . count( $done ) . ' of ' . count( $map ) . '.' );

} else {
	WP_CLI::error( 'Usage: wp eval-file seo-setup.php apply <og-image-id> | dictionary' );
}
