<?php
/**
 * One-off: replace the 6 "How it works" step images on the home page with Font Awesome
 * icons (bundled with the SaaS Company theme) in the logo colours.
 *   docker compose run --rm wpcli wp eval-file /scripts/how-it-works-icons.php
 */

$template_id = 72; // front-page template

// In page order. Light circles get a dark icon so it stays readable.
$steps = array(
	array( 'icon' => 'fa-truck',         'bg' => '#B5524F', 'fg' => '#ffffff' ), // Order a test kit
	array( 'icon' => 'fa-tint',          'bg' => '#CFB6AA', 'fg' => '#111111' ), // Finger-prick blood sample
	array( 'icon' => 'fa-envelope',      'bg' => '#666654', 'fg' => '#ffffff' ), // Return in prepaid envelope
	array( 'icon' => 'fa-microscope',    'bg' => '#B3A3B2', 'fg' => '#111111' ), // Lab analysis
	array( 'icon' => 'fa-bell',          'bg' => '#858C7F', 'fg' => '#ffffff' ), // Results notification
	array( 'icon' => 'fa-file-download', 'bg' => '#111111', 'fg' => '#ffffff' ), // Download results & food guide
);

$post    = get_post( $template_id );
$i       = 0;
$content = preg_replace_callback(
	'#<!-- wp:group \{"className":"icon-box".*?<!-- /wp:group -->#s',
	function () use ( &$i, $steps ) {
		$s = $steps[ $i++ ];
		$attrs = wp_json_encode( array(
			'className' => 'icon-box',
			'style'     => array(
				'color'   => array( 'background' => $s['bg'], 'text' => $s['fg'] ),
				'spacing' => array(
					'padding' => array( 'top' => '0', 'bottom' => '0', 'left' => '0', 'right' => '0' ),
					'margin'  => array( 'top' => '0', 'bottom' => '0' ),
				),
			),
			'layout'    => array( 'type' => 'default' ),
		), JSON_UNESCAPED_SLASHES );
		return "<!-- wp:group {$attrs} -->\n"
			. "<div class=\"wp-block-group icon-box has-text-color has-background\" style=\"color:{$s['fg']};background-color:{$s['bg']};margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0\">"
			. "<!-- wp:html -->\n<i class=\"fas {$s['icon']}\" aria-hidden=\"true\"></i>\n<!-- /wp:html --></div>\n"
			. '<!-- /wp:group -->';
	},
	$post->post_content
);

if ( 6 !== $i ) {
	WP_CLI::error( "Expected 6 icon boxes, found {$i}. Nothing changed." );
}
wp_update_post( array( 'ID' => $template_id, 'post_content' => wp_slash( $content ) ) );
WP_CLI::success( "Replaced {$i} step images with icons." );
