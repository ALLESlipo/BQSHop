/**
 * Home page background video: a small speaker button that turns the sound on/off.
 * Talks to the YouTube player through its postMessage API (the embed has enablejsapi=1).
 */
( function () {
	'use strict';

	// Interface texts are printed in the page (functions.php → mimatest_ui_texts) so
	// TranslatePress can translate them; read them from there.
	function uiText( key, fallback ) {
		var el = document.querySelector( '#mt-ui-texts [data-key="' + key + '"]' );
		return el && el.textContent.trim() ? el.textContent.trim() : fallback;
	}

	var section = document.querySelector( '.mt-video' );
	var iframe = section && section.querySelector( '.mt-video__bg iframe' );
	if ( ! iframe ) {
		return;
	}

	var labels = { on: uiText( 'sound-on', 'Turn sound on' ), off: uiText( 'sound-off', 'Turn sound off' ) };
	var muted = true;

	var btn = document.createElement( 'button' );
	btn.type = 'button';
	btn.className = 'mt-video__sound';
	btn.innerHTML = '<i class="fas fa-volume-mute" aria-hidden="true"></i>';
	section.appendChild( btn );

	function command( func, args ) {
		iframe.contentWindow.postMessage( JSON.stringify( { event: 'command', func: func, args: args || [] } ), '*' );
	}

	function render() {
		btn.setAttribute( 'aria-label', muted ? labels.on : labels.off );
		btn.setAttribute( 'aria-pressed', muted ? 'false' : 'true' );
		btn.firstChild.className = 'fas ' + ( muted ? 'fa-volume-mute' : 'fa-volume-up' );
	}

	btn.addEventListener( 'click', function () {
		muted = ! muted;
		if ( muted ) {
			command( 'mute' );
		} else {
			command( 'unMute' );
			command( 'setVolume', [ 70 ] );
			command( 'playVideo' ); // in case autoplay was blocked
		}
		render();
	} );

	render();
}() );
