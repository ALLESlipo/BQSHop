<?php
/**
 * One-off: make Albanian the site's source language and keep the English as translations.
 *
 *   wp --user=admin eval-file /scripts/translate-sq-en.php content     # 1. English → Albanian in the content
 *   (visit the /en/ pages so TranslatePress registers the new Albanian strings)
 *   wp --user=admin eval-file /scripts/translate-sq-en.php dictionary  # 2. store the English as their translations
 *
 * Each pair is [ English (current text), Albanian (new source text) ].
 */

$pairs = array(
	// Home: hero, How it works.
	// TranslatePress splits headings at <br>, so each line is its own pair.
	array( 'YOU CAN ACCESS ALL YOUR', 'TË GJITHA ANALIZAT E ZORRËVE' ),
	array( 'GUT ANALYSES IN ONE PLACE', 'NË NJË VEND TË VETËM' ),
	array( 'We are a long established company in gut health industry', 'Jemi një kompani me përvojë të gjatë në fushën e shëndetit të zorrëve' ),
	array( 'Shop Now', 'Bli tani' ),
	array( "Here's how it works", 'Si funksionon' ),
	array( 'A new, minimally invasive test kit', 'Një kit testimi i ri dhe minimalisht invaziv' ),
	array( 'For diagnosing small bowel diseases at home', 'Për diagnostikimin e sëmundjeve të zorrës së hollë nga shtëpia' ),
	array( 'Order a test kit and have it conveniently delivered to your home', 'Porositni një kit testimi dhe merreni lehtësisht në shtëpi' ),
	array( 'Order your Mimatest® kit online and it will be delivered to your door within 1–3 working days, with free shipping.', 'Porositni kitin tuaj Mimatest® online dhe do t’ju dorëzohet në derë brenda 1–3 ditëve të punës, me transport falas.' ),
	array( 'Follow the enclosed instructions.', 'Ndiqni udhëzimet e përfshira.' ),
	array( "Once you receive the test, please collect your dried blood sample according to the instructions. Collection is quick and easy. You don't need a stool sample or any other complicated or unpleasant steps – just a single drop of blood from a finger prick.", 'Pasi ta merrni testin, mblidhni mostrën e gjakut të tharë sipas udhëzimeve. Mbledhja është e shpejtë dhe e lehtë. Nuk ju nevojitet mostër feçesh apo ndonjë hap tjetër i ndërlikuar ose i pakëndshëm – vetëm një pikë gjaku nga shpimi i gishtit.' ),
	array( 'Return the sample in the enclosed prepaid envelope.', 'Kthejeni mostrën me zarfin e parapaguar të përfshirë.' ),
	array( 'You do not need to address the envelope. Simply place your sample in the envelope and drop it in the mailbox.', 'Nuk ka nevojë të shkruani adresën në zarf. Thjesht vendoseni mostrën në zarf dhe hidheni në kutinë postare.' ),
	array( 'Wait for your lab analysis', 'Prisni analizën laboratorike' ),
	array( 'Your sample will be analyzed using state-of-the-art technology, providing a comprehensive insight into the state of your microbiome. This process takes approximately 2–3 weeks.', 'Mostra juaj do të analizohet me teknologjinë më të fundit, duke ofruar një pasqyrë të plotë të gjendjes së mikrobiomës suaj. Ky proces zgjat afërsisht 2–3 javë.' ),
	array( 'Receive your results', 'Merrni rezultatet tuaja' ),
	array( 'You will be notified as soon as your results are available. A detailed document will be securely stored on our platform. You can share this document with an expert of your choice or book a consultation with one of our experts.', 'Do të njoftoheni sapo rezultatet tuaja të jenë gati. Një dokument i detajuar do të ruhet në mënyrë të sigurt në platformën tonë. Mund ta ndani këtë dokument me një specialist sipas zgjedhjes suaj ose të rezervoni një konsultë me një nga ekspertët tanë.' ),
	array( 'Download the results and the food guide', 'Shkarkoni rezultatet dhe udhëzuesin ushqimor' ),
	array( 'You can download your gut test results from our platform as often as you like. They come with a detailed food guide to help you start your diet.', 'Mund t’i shkarkoni rezultatet e testit të zorrëve nga platforma jonë sa herë të dëshironi. Ato shoqërohen me një udhëzues të detajuar ushqimor që t’ju ndihmojë të nisni dietën tuaj.' ),
	array( 'If you are a professional and would like to offer Mimatest® as a service to your clients, please contact us on the email:', 'Nëse jeni profesionist dhe dëshironi ta ofroni Mimatest® si shërbim për klientët tuaj, na kontaktoni në email-in:' ),

	// Home: What does the purchase include?
	array( 'What does the purchase of a Mimatest® include?', 'Çfarë përfshin blerja e një Mimatest®?' ),
	array( 'The comprehensive set is based on proven technology and offers high accuracy according to professional standards.', 'Kompleti i plotë bazohet në teknologji të provuar dhe ofron saktësi të lartë sipas standardeve profesionale.' ),
	array( 'The set contains all the materials needed for safe and accurate sample collection, making home testing easy and convenient.', 'Kompleti përmban të gjitha materialet e nevojshme për mbledhjen e sigurt dhe të saktë të mostrës, duke e bërë testimin në shtëpi të lehtë dhe të përshtatshëm.' ),
	array( 'The test kit ID, in combination with a personal access code, offers anonymity and secure online retrieval of results.', 'ID-ja e kitit, e kombinuar me një kod personal aksesi, garanton anonimitet dhe marrje të sigurt të rezultateve online.' ),
	array( 'A prepaid return envelope for the completed test samples ensures uncomplicated and free shipping to the laboratory.', 'Një zarf kthimi i parapaguar për mostrat e përfunduara siguron dërgim të thjeshtë dhe falas në laborator.' ),
	array( 'The package includes easy-to-understand instructions for blood collection, which are also available online in several languages.', 'Paketa përfshin udhëzime të qarta për marrjen e gjakut, të cilat janë të disponueshme edhe online në disa gjuhë.' ),
	array( 'Designed for single use, the Mimatest® ensures hygienic application.', 'I projektuar për një përdorim të vetëm, Mimatest® garanton përdorim higjienik.' ),
	array( 'The results can be accessed securely and anonymously at any time on yourtestresults.net.', 'Rezultatet mund të shihen në mënyrë të sigurt dhe anonime në çdo kohë në yourtestresults.net.' ),

	// Home: video section, image alt texts.
	array( 'Order your test kit', 'Porositni kitin e testit' ),
	array( 'Mimatest® sample collection kit box', 'Kutia e kitit Mimatest® për mbledhjen e mostrës' ),

	// Home: animation/interface texts (child theme → mimatest_ui_texts()).
	array( 'Drag to rotate', 'Tërhiqeni për ta rrotulluar' ),
	array( 'The Mimatest box opening to show the kit contents', 'Kutia Mimatest hapet dhe shfaq përmbajtjen e kitit' ),
	array( 'Replay', 'Përsërit' ),
	array( 'Turn sound on', 'Aktivizo zërin' ),
	array( 'Turn sound off', 'Çaktivizo zërin' ),

	// Footer (template part "footer"). TranslatePress splits at <br>, so one pair per line.
	array( 'About Us', 'Rreth nesh' ),
	array( 'A new, minimally invasive test kit.', 'Një kit testimi i ri dhe minimalisht invaziv.' ),
	array( 'For diagnosing small bowel diseases at home.', 'Për diagnostikimin e sëmundjeve të zorrës së hollë nga shtëpia.' ),
	array( 'Your health begins in the gut. This is precisely where Mimatest® comes in.', 'Shëndeti juaj fillon nga zorrët. Pikërisht këtu ndërhyn Mimatest®.' ),
	array( 'Our Links', 'Lidhje të dobishme' ),
	array( 'Support', 'Mbështetje' ),
	array( 'Terms &amp; Condition', 'Kushtet e përdorimit' ),
	array( 'Latest News', 'Lajmet e fundit' ),
	array( 'Gallery', 'Galeria' ),
	array( 'Copyright © 2026 B.Q Analytics', 'Të drejtat e autorit © 2026 B.Q Analytics' ),

	// Shop banner.
	array( 'Health begins in the gut.', 'Shëndeti fillon nga zorrët.' ),

	// Menu + page titles.
	array( 'Home', 'Kreu' ),
	array( 'Shop', 'Dyqani' ),
	array( 'Cart', 'Shporta' ),
	array( 'Checkout', 'Arka' ),
	array( 'My account', 'Llogaria ime' ),
	array( 'Terms and Conditions', 'Kushtet e përdorimit' ),
	array( 'Privacy Policy', 'Politika e privatësisë' ),

	// Products + category.
	array( 'Mimatest® (single)', 'Mimatest® (një test)' ),
	array( 'Mimatest® Pair Kit', 'Mimatest® Paketë për dy' ),
	array( 'Includes a single-use test kit, instructions, and a prepaid return envelope.', 'Përfshin një kit testimi njëpërdorimësh, udhëzime dhe një zarf kthimi të parapaguar.' ),
	array( 'Contains two disposable test kits, instructions and two prepaid return envelopes.', 'Përmban dy kite testimi njëpërdorimëshe, udhëzime dhe dy zarfe kthimi të parapaguara.' ),
	array( 'Delivery in 1 to 3 working days,', 'Dorëzimi brenda 1 deri në 3 ditë pune,' ),
	array( 'free shipping', 'transport falas' ),
	array( 'Test kit for collecting a dried blood spot sample at home.', 'Kit testimi për mbledhjen e mostrës së gjakut të tharë në shtëpi.' ),
	array( 'Tests', 'Teste' ),

	// Shipping + payment methods.
	array( 'Standard shipping', 'Transport standard' ),
	array( 'Free shipping', 'Transport falas' ),
	array( 'Cash on delivery', 'Pagesë në dorëzim' ),
	array( 'Pay with cash when your order arrives.', 'Paguani me para në dorë kur t’ju mbërrijë porosia.' ),
	array( 'Pay with cash upon delivery.', 'Pagesa bëhet me para në dorë në momentin e dorëzimit.' ),
	array( 'Direct bank transfer', 'Transfertë bankare' ),
	array( 'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.', 'Kryeni pagesën direkt në llogarinë tonë bankare. Ju lutemi përdorni numrin e porosisë si referencë pagese. Porosia juaj nuk do të dërgohet derisa pagesa të mbërrijë në llogarinë tonë.' ),
);

$mode = isset( $args[0] ) ? $args[0] : '';

if ( 'content' === $mode ) {
	// Longest first, so e.g. "Order your test kit" isn't hit by a shorter phrase first.
	usort( $pairs, function ( $a, $b ) { return strlen( $b[0] ) - strlen( $a[0] ); } );
	$exact = array_column( $pairs, 1, 0 ); // whole-field values (alt texts, method titles…)
	$to_sq = function ( $text ) use ( $pairs ) {
		foreach ( $pairs as $p ) {
			// Short one-word labels are only replaced as whole text / JSON values, never inside sentences.
			if ( strlen( $p[0] ) < 20 ) {
				$text = preg_replace( '/(?<=[>"])' . preg_quote( $p[0], '/' ) . '(?=[<"])/u', $p[1], $text );
			} else {
				$text = str_replace( $p[0], $p[1], $text );
			}
		}
		return $text;
	};

	// Home template, shop template, header menu, footer.
	foreach ( array( 72, 87, 32, 120 ) as $id ) {
		$post = get_post( $id );
		wp_update_post( array( 'ID' => $id, 'post_content' => wp_slash( $to_sq( $post->post_content ) ) ) );
	}
	// Hero image alt text lives on the attachment too.
	foreach ( get_posts( array( 'post_type' => 'attachment', 'posts_per_page' => -1, 'fields' => 'ids' ) ) as $att ) {
		$alt = get_post_meta( $att, '_wp_attachment_image_alt', true );
		if ( isset( $exact[ $alt ] ) ) {
			update_post_meta( $att, '_wp_attachment_image_alt', $exact[ $alt ] );
		}
	}

	// Page titles.
	$titles = array( 'Shop' => 'Dyqani', 'Cart' => 'Shporta', 'Checkout' => 'Arka', 'My account' => 'Llogaria ime', 'Terms and Conditions' => 'Kushtet e përdorimit', 'Privacy Policy' => 'Politika e privatësisë' );
	foreach ( get_pages( array( 'post_status' => array( 'publish', 'draft' ) ) ) as $page ) {
		if ( isset( $titles[ $page->post_title ] ) ) {
			wp_update_post( array( 'ID' => $page->ID, 'post_title' => $titles[ $page->post_title ] ) );
		}
	}

	// Products.
	foreach ( array( 12, 97 ) as $pid ) {
		$product = wc_get_product( $pid );
		$product->set_name( $exact[ $product->get_name() ] ?? $product->get_name() );
		$product->set_description( $to_sq( $product->get_description() ) );
		if ( 12 === $pid ) {
			// Replaces the old "buy more, save more with our bundles" placeholder.
			$product->set_short_description( '<p>Kit testimi për mbledhjen e mostrës së gjakut të tharë në shtëpi.</p>' );
		}
		$product->save();
	}
	wp_update_term( 20, 'product_cat', array( 'name' => 'Teste' ) );

	// Payment + shipping method names.
	foreach ( array( 'woocommerce_cod_settings', 'woocommerce_bacs_settings' ) as $opt ) {
		$s = get_option( $opt );
		foreach ( array( 'title', 'description', 'instructions' ) as $k ) {
			if ( ! empty( $s[ $k ] ) && isset( $exact[ $s[ $k ] ] ) ) {
				$s[ $k ] = $exact[ $s[ $k ] ];
			}
		}
		update_option( $opt, $s );
	}
	foreach ( array( 'woocommerce_flat_rate_1_settings', 'woocommerce_free_shipping_2_settings' ) as $opt ) {
		$s = get_option( $opt );
		if ( $s && isset( $exact[ $s['title'] ] ) ) {
			$s['title'] = $exact[ $s['title'] ];
			update_option( $opt, $s );
		}
	}
	wc_delete_product_transients();
	WP_CLI::success( 'Content is now in Albanian.' );

} elseif ( 'dictionary' === $mode ) {
	global $wpdb;
	$table = $wpdb->prefix . 'trp_dictionary_sq_en_us';
	$norm  = function ( $s ) {
		$s = html_entity_decode( wp_strip_all_tags( $s ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$s = str_replace( array( '’', '‘' ), "'", $s );
		return trim( preg_replace( '/\s+/u', ' ', $s ) );
	};
	$by_sq = array();
	foreach ( $pairs as $p ) {
		$by_sq[ $norm( $p[1] ) ] = wptexturize( html_entity_decode( $p[0], ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
	}
	$fill = function () use ( $wpdb, $table, $norm, $by_sq ) {
		$updated = array();
		foreach ( $wpdb->get_results( "SELECT id, original FROM {$table}" ) as $row ) {
			$key = $norm( $row->original );
			if ( isset( $by_sq[ $key ] ) ) {
				$wpdb->update( $table, array( 'translated' => $by_sq[ $key ], 'status' => 2 ), array( 'id' => $row->id ) );
				$updated[ $key ] = true;
			}
		}
		return $updated;
	};
	$updated = $fill();

	// Strings TranslatePress never saw in the page HTML (e.g. payment method names that the
	// block checkout renders in the browser): register them so its live translation finds them.
	$unseen = array();
	foreach ( $pairs as $p ) {
		if ( ! isset( $updated[ $norm( $p[1] ) ] ) ) {
			$unseen[] = $p[1];
		}
	}
	if ( $unseen ) {
		TRP_Translate_Press::get_trp_instance()->get_component( 'query' )->insert_strings( $unseen, 'en_US' );
		$updated = $fill();
	}
	$missing = array_diff( array_keys( $by_sq ), array_keys( $updated ) );
	WP_CLI::log( 'Translated ' . count( $updated ) . ' of ' . count( $by_sq ) . ' strings.' );
	if ( $missing ) {
		WP_CLI::log( "Not seen on the English pages yet:\n  - " . implode( "\n  - ", $missing ) );
	}
	WP_CLI::success( 'English translations saved.' );

} else {
	WP_CLI::error( 'Usage: wp eval-file translate-sq-en.php content|dictionary' );
}
