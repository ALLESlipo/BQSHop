/**
 * Mimatest kit unboxing — home page, "What does the purchase of a Mimatest® include?".
 *
 * Builds a 3D box lying on the table in the empty middle column. When the section scrolls
 * into view the lid opens and the kit items rise out and settle around the box.
 * Item list/positions come from functions.php via window.mimatestKit.
 *
 * Plane coordinates: everything lives on a tilted "table" plane; x → right, y → towards
 * the viewer, z → up off the table. Units are px before the fit-to-column scale.
 */
( function () {
	'use strict';

	// Interface texts are printed in the page (functions.php → mimatest_ui_texts) so
	// TranslatePress can translate them; read them from there.
	function uiText( key, fallback ) {
		var el = document.querySelector( '#mt-ui-texts [data-key="' + key + '"]' );
		return el && el.textContent.trim() ? el.textContent.trim() : fallback;
	}

	var cfg = window.mimatestKit || {};
	var host = document.querySelector( '.mt-includes__gap' );
	if ( ! host || ! cfg.items || ! cfg.lid ) {
		return;
	}

	var BOX_W = 230, BOX_H = 144, BOX_D = 44;   // box size on the table
	var PLANE = 470;                            // plane width the layout is designed for
	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function el( tag, cls, parent ) {
		var e = document.createElement( tag );
		e.className = cls;
		if ( parent ) {
			parent.appendChild( e );
		}
		return e;
	}

	// ---- Build ---------------------------------------------------------------
	var stage = el( 'div', 'mt-kit', host );
	stage.setAttribute( 'role', 'img' );
	stage.setAttribute( 'aria-label', uiText( 'kit-label', 'The Mimatest box opening to show the kit contents' ) );
	stage.style.setProperty( '--bw', BOX_W + 'px' );
	stage.style.setProperty( '--bh', BOX_H + 'px' );
	stage.style.setProperty( '--bd', BOX_D + 'px' );

	var table = el( 'div', 'mt-kit__table', stage );

	el( 'div', 'mt-kit__shadow', table );

	var box = el( 'div', 'mt-kit__box', table );
	[ 'floor', 'wall wall--front', 'wall wall--back', 'wall wall--left', 'wall wall--right' ].forEach( function ( c ) {
		el( 'div', 'mt-kit__' + c.split( ' ' ).join( ' mt-kit__' ), box );
	} );

	cfg.items.forEach( function ( item, i ) {
		var wrap = el( 'div', 'mt-kit__item', table );
		wrap.style.width = item.w + 'px';
		wrap.style.height = item.h + 'px';
		wrap.style.left = -item.w / 2 + 'px';  // centred on the box; --tx/--ty move it out
		wrap.style.top = -item.h / 2 + 'px';
		wrap.style.setProperty( '--tx', item.x + 'px' );
		wrap.style.setProperty( '--ty', item.y + 'px' );
		wrap.style.setProperty( '--rot', ( item.rot || 0 ) + 'deg' );
		wrap.style.setProperty( '--delay', ( 1.5 + i * 0.22 ) + 's' );
		wrap.style.setProperty( '--delay-in', ( ( cfg.items.length - 1 - i ) * 0.12 ) + 's' ); // reverse order when packing up

		// Shadow stays flat on the table; the item itself leans up towards the viewer,
		// with a few darker copies behind it to give it an edge (thickness).
		image( wrap, item.src, 'mt-kit__item-shadow' );
		var lean = el( 'div', 'mt-kit__lean', wrap );
		for ( var e = 3; e >= 1; e-- ) {
			image( lean, item.src, 'mt-kit__edge' ).style.setProperty( '--z', -e + 'px' );
		}
		image( lean, item.src, 'mt-kit__img' );
	} );

	function image( parent, src, cls ) {
		var img = el( 'img', cls, parent );
		img.src = src;
		img.alt = '';
		img.decoding = 'async';
		img.draggable = false;
		return img;
	}

	// The lid goes last so it's drawn over the items while closed.
	var lid = el( 'div', 'mt-kit__lid', box );
	el( 'div', 'mt-kit__lid-top', lid ).style.backgroundImage = 'url("' + cfg.lid + '")';
	el( 'div', 'mt-kit__lid-under', lid );

	var replay = el( 'button', 'mt-kit__replay', stage );
	replay.type = 'button';
	replay.textContent = uiText( 'kit-replay', 'Replay' );

	// ---- Fit the plane into the column --------------------------------------
	function fit() {
		var w = host.clientWidth;
		if ( w ) {
			// Phones: the frame clips at its edges, so leave a bit more margin.
			stage.style.setProperty( '--s', Math.min( 1.15, w / ( w < 500 ? PLANE * 1.15 : PLANE ) ) );
		}
	}
	fit();
	if ( 'ResizeObserver' in window ) {
		new ResizeObserver( fit ).observe( host );
	} else {
		window.addEventListener( 'resize', fit );
	}

	// ---- Play ----------------------------------------------------------------
	var n = cfg.items.length;
	var OPEN_MS = ( 1.5 + n * 0.22 + 1.4 ) * 1000;   // lid opens, items come out and land
	var PACK_MS = ( ( n - 1 ) * 0.12 + 0.9 ) * 1000; // items go back into the box
	var CLOSE_MS = 1700;                              // lid closes (transition + delay)
	var timers = [];

	function later( fn, ms ) {
		timers.push( setTimeout( fn, ms ) );
	}

	function play() {
		timers.forEach( clearTimeout );
		timers = [];
		stage.classList.remove( 'is-open', 'is-done', 'is-closing' );
		void stage.offsetWidth; // restart the CSS animations
		stage.classList.add( 'is-open' );
		later( function () {
			stage.classList.add( 'is-done' );
		}, reduceMotion ? 0 : OPEN_MS );
	}

	// Replay: pack the items back in, close the lid, then unbox again.
	function replayAll() {
		if ( reduceMotion ) {
			return;
		}
		timers.forEach( clearTimeout );
		timers = [];
		stage.classList.remove( 'is-done' );
		stage.classList.add( 'is-closing' );
		later( function () {
			stage.classList.remove( 'is-open', 'is-closing' ); // lid swings shut
		}, PACK_MS );
		later( play, PACK_MS + CLOSE_MS );
	}

	replay.addEventListener( 'click', replayAll );

	if ( reduceMotion ) {
		stage.classList.add( 'no-motion' );
		play();
	} else if ( 'IntersectionObserver' in window ) {
		var io = new IntersectionObserver( function ( entries ) {
			if ( entries[ 0 ].isIntersecting ) {
				io.disconnect();
				play();
			}
		}, { threshold: 0.45 } );
		io.observe( stage );
	} else {
		play();
	}
}() );
