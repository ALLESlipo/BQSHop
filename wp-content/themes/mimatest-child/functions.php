<?php
/**
 * Mimatest child theme.
 *
 * The parent enqueues get_stylesheet_uri() as 'saas-company-basic-style', which in a
 * child theme resolves to THIS style.css. So we load the parent's style.css ourselves
 * and re-enqueue the child stylesheet last, so its rules override the theme.
 */

/**
 * Small interface texts used by the home page animations (hint, Replay button, sound icon).
 * Written in Albanian (the site's main language). They're printed into the page as hidden
 * text, so TranslatePress lists them like any other text: translate them in
 * TranslatePress → Translate Site, no code needed. The scripts read them from the page.
 */
function mimatest_ui_texts() {
	return array(
		'box-hint'   => 'Tërhiqeni për ta rrotulluar',
		'kit-label'  => 'Kutia Mimatest hapet dhe shfaq përmbajtjen e kitit',
		'kit-replay' => 'Përsërit',
		'sound-on'   => 'Aktivizo zërin',
		'sound-off'  => 'Çaktivizo zërin',
	);
}

add_action( 'wp_footer', function () {
	if ( ! is_front_page() ) {
		return;
	}
	echo '<div id="mt-ui-texts" hidden>';
	foreach ( mimatest_ui_texts() as $key => $text ) {
		printf( '<span data-key="%s">%s</span>', esc_attr( $key ), esc_html( $text ) );
	}
	echo '</div>';
} );

add_action( 'wp_enqueue_scripts', function () {
	$parent = wp_get_theme( get_template() );

	wp_enqueue_style(
		'saas-company-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		$parent->get( 'Version' )
	);

	// Replace the parent's handle (which points at this file) with one that loads after everything else.
	wp_dequeue_style( 'saas-company-basic-style' );
	wp_enqueue_style(
		'mimatest-child-style',
		get_stylesheet_uri(),
		array( 'saas-company-parent-style' ),
		filemtime( get_stylesheet_directory() . '/style.css' ) // bust browser cache on every edit
	);
}, 999 );

/**
 * Home page hero: the kit box becomes a 3D box you can drag to rotate (assets/js/box-3d.js).
 * Drop a photo of the back of the box at assets/img/box-back.png (same 613×382 proportions)
 * and it's used automatically; until then the back is plain white.
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_front_page() ) {
		return;
	}
	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();

	wp_enqueue_script( 'mimatest-box-3d', $uri . '/assets/js/box-3d.js', array(), filemtime( $dir . '/assets/js/box-3d.js' ), true );

	$back = $dir . '/assets/img/box-back.png';
	wp_localize_script( 'mimatest-box-3d', 'mimatestBox3d', array(
		'front' => $uri . '/assets/img/box-front.png?v=' . filemtime( $dir . '/assets/img/box-front.png' ),
		'back'  => file_exists( $back ) ? $uri . '/assets/img/box-back.png?v=' . filemtime( $back ) : '',
		'depth' => 0.2,
	) );
} );

/**
 * Home page "What does the purchase include?" section: the box opens and the kit items
 * come out (assets/js/kit-unbox.js). Item images are in assets/img/kit/.
 * x/y = where each item lands, relative to the box centre (px on the table); w/h = size.
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_front_page() ) {
		return;
	}
	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();
	$img = function ( $file ) use ( $dir, $uri ) {
		return $uri . '/assets/img/' . $file . '?v=' . filemtime( $dir . '/assets/img/' . $file );
	};

	$items = array(
		array( 'kit/folder.png',      'w' => 82,  'h' => 155, 'x' => -195, 'y' => -20,  'rot' => -6 ),
		array( 'kit/lancets.png',     'w' => 52,  'h' => 35,  'x' => -185, 'y' => -140, 'rot' => 4 ),
		array( 'kit/plaster.png',     'w' => 26,  'h' => 67,  'x' => 140,  'y' => -140, 'rot' => 14 ),
		array( 'kit/alcohol-pad.png', 'w' => 65,  'h' => 64,  'x' => 190,  'y' => -70,  'rot' => 8 ),
		array( 'kit/dry-sac.png',     'w' => 50,  'h' => 65,  'x' => 195,  'y' => 35,   'rot' => -10 ),
		array( 'kit/return-bag.png',  'w' => 112, 'h' => 138, 'x' => 100,  'y' => 140,  'rot' => -5 ),
		array( 'kit/card.png',        'w' => 140, 'h' => 88,  'x' => -120, 'y' => 135,  'rot' => 5 ),
	);
	foreach ( $items as &$item ) {
		$item['src'] = $img( $item[0] );
		unset( $item[0] );
	}

	wp_enqueue_script( 'mimatest-kit-unbox', $uri . '/assets/js/kit-unbox.js', array(), filemtime( $dir . '/assets/js/kit-unbox.js' ), true );
	wp_localize_script( 'mimatest-kit-unbox', 'mimatestKit', array(
		'lid'    => $img( 'box-front.png' ),
		'items'  => $items,
	) );
} );

/**
 * Home page background video section: speaker button to turn the sound on/off
 * (assets/js/video-sound.js).
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_front_page() ) {
		return;
	}
	$dir = get_stylesheet_directory();
	wp_enqueue_script( 'mimatest-video-sound', get_stylesheet_directory_uri() . '/assets/js/video-sound.js', array(), filemtime( $dir . '/assets/js/video-sound.js' ), true );
} );

/**
 * Language switcher for the header: both flags side by side, Albanian first (the order of
 * TranslatePress → Settings → All Languages). Used as [mimatest_language_switcher].
 */
add_shortcode( 'mimatest_language_switcher', function () {
	if ( ! function_exists( 'trp_custom_language_switcher' ) ) {
		return '';
	}
	$current = get_locale();
	$links   = '';
	foreach ( trp_custom_language_switcher() as $code => $lang ) {
		$active = ( $code === $current );
		$links .= sprintf(
			'<a href="%1$s" class="mt-lang__link%2$s" hreflang="%3$s" lang="%3$s" title="%4$s"%5$s><img src="%6$s" alt="" width="20" height="14"><span>%7$s</span></a>',
			esc_url( $lang['current_page_url'] ),
			$active ? ' is-active' : '',
			esc_attr( str_replace( '_', '-', $code ) ),
			esc_attr( $lang['language_name'] ),
			$active ? ' aria-current="true"' : '',
			esc_url( $lang['flag_link'] ),
			esc_html( strtoupper( $lang['short_language_name'] ) )
		);
	}
	return '<nav class="mt-lang" aria-label="Gjuha / Language" data-no-translation>' . $links . '</nav>';
} );

/**
 * Admin dashboard: a short "how to translate" box, so editing English never needs code.
 */
add_action( 'wp_dashboard_setup', function () {
	if ( ! function_exists( 'trp_custom_language_switcher' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	wp_add_dashboard_widget( 'mimatest_translation_help', 'Translating the site (Albanian → English)', function () {
		$translate = add_query_arg( 'trp-edit-translation', 'true', home_url( '/' ) );
		?>
		<ol style="margin-left:1.2em">
			<li><strong>Write everything in Albanian</strong>: pages, products, and the home page sections (Appearance → Editor). Albanian is the main language.</li>
			<li><strong>Add or change the English</strong>: open any page of the site and click <em>Translate Page</em> in the black bar at the top (or use the button below). Click a text, type the English, then <em>Save translation</em>.</li>
			<li><strong>Texts you can't click</strong> (button labels, hints, image descriptions, checkout texts): pick them from the <em>Select string to translate</em> list at the top of the translation panel.</li>
			<li>WordPress and WooCommerce texts (cart, checkout, account, emails) are translated automatically.</li>
		</ol>
		<p>New or changed Albanian text shows in Albanian on the English site until you translate it.</p>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( $translate ); ?>">Translate the home page</a>
			<a class="button" href="<?php echo esc_url( admin_url( 'site-editor.php' ) ); ?>">Edit Albanian content</a>
			<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=product' ) ); ?>">Products</a>
		</p>
		<?php
	} );
} );

/**
 * The parent's page-header banner (product, cart, account… pages) is a purple SaaS-dashboard
 * image baked into its patterns. Serve our brand-coloured version instead.
 */
add_filter( 'render_block_core/cover', function ( $html ) {
	return str_replace(
		get_template_directory_uri() . '/images/inner-banner.png',
		get_stylesheet_directory_uri() . '/assets/img/inner-banner.png',
		$html
	);
} );

/**
 * The parent's template files hard-code "theme":"saas-company" on their header/footer
 * template parts, so under a child theme they'd look for the parent's parts and render
 * nothing. Point them at the active (child) theme instead — on the front end and in the
 * Site Editor alike.
 */
function mimatest_child_retarget_template_parts( $template ) {
	if ( $template instanceof WP_Block_Template && is_string( $template->content ) ) {
		$template->content = str_replace(
			'"theme":"' . get_template() . '"',
			'"theme":"' . get_stylesheet() . '"',
			$template->content
		);
	}
	return $template;
}
add_filter( 'get_block_file_template', 'mimatest_child_retarget_template_parts' );
add_filter( 'get_block_template', 'mimatest_child_retarget_template_parts' );
add_filter( 'get_block_templates', function ( $templates ) {
	return array_map( 'mimatest_child_retarget_template_parts', $templates );
} );
