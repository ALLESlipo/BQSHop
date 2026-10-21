<?php
/**
 * Plugin Name: Local mail catcher
 * Description: Sends all WordPress email to the Mailpit container (http://localhost:8025). Local development only.
 */
add_action( 'phpmailer_init', function ( $phpmailer ) {
	$phpmailer->isSMTP();
	$phpmailer->Host     = 'mailpit';
	$phpmailer->Port     = 1025;
	$phpmailer->SMTPAuth = false;
} );
