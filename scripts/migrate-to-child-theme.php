<?php
/**
 * One-off: move site customisations from saas-company to mimatest-child, then activate it.
 *   docker compose run --rm wpcli wp eval-file /scripts/migrate-to-child-theme.php
 */

$from = 'saas-company';
$to   = 'mimatest-child';

// Logo, sidebars etc. live in theme_mods_<theme>. Additional CSS now lives in the child's style.css.
$mods = get_option( "theme_mods_{$from}", array() );
unset( $mods['custom_css_post_id'], $mods[0] );
update_option( "theme_mods_{$to}", $mods );
WP_CLI::log( 'Copied theme mods: ' . implode( ', ', array_keys( $mods ) ) );

// Templates and template parts edited in the Site Editor are tagged with the theme they belong to.
$posts = get_posts( array(
	'post_type'      => array( 'wp_template', 'wp_template_part' ),
	'post_status'    => 'any',
	'posts_per_page' => -1,
	'tax_query'      => array( array( 'taxonomy' => 'wp_theme', 'field' => 'slug', 'terms' => $from ) ),
) );
foreach ( $posts as $post ) {
	wp_update_post( array(
		'ID'           => $post->ID,
		'post_content' => str_replace( "\"theme\":\"{$from}\"", "\"theme\":\"{$to}\"", $post->post_content ),
	) );
	wp_set_object_terms( $post->ID, $to, 'wp_theme' );
	WP_CLI::log( "Moved {$post->post_type} '{$post->post_name}' (#{$post->ID})" );
}

switch_theme( $to );
WP_CLI::success( "Active theme: " . get_stylesheet() );
