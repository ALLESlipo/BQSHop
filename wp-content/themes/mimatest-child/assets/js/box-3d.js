/**
 * Mimatest 3D box — turns the hero image into a box you can drag to rotate 360°.
 *
 * The hero <img> (a transparent PNG with the box centred on it) stays in the page for
 * layout and as a no-JS fallback; a CSS 3D box is laid exactly over the box in it.
 * Settings come from functions.php via window.mimatestBox3d.
 */
( function () {
	'use strict';

	// Interface texts are printed in the page (functions.php → mimatest_ui_texts) so
	// TranslatePress can translate them; read them from there.
	function uiText( key, fallback ) {
		var el = document.querySelector( '#mt-ui-texts [data-key="' + key + '"]' );
		return el && el.textContent.trim() ? el.textContent.trim() : fallback;
	}

	var cfg = window.mimatestBox3d || {};
	var figure = document.querySelector( '.banner-img-box .main-img' );
	var img = figure && figure.querySelector( 'img' );
	if ( ! figure || ! img || ! cfg.front ) {
		return;
	}

	// Where the box sits inside the 1024×682 hero PNG.
	var SRC_W = 1024, SRC_H = 682, BOX_W = 613, BOX_H = 382;
	var DEPTH = cfg.depth || 0.2; // box thickness as a fraction of its width

	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	// ---- Build the box -------------------------------------------------------
	var scene = document.createElement( 'div' );
	scene.className = 'mt-box3d';
	scene.tabIndex = 0;
	scene.setAttribute( 'role', 'img' );
	scene.setAttribute( 'aria-label', ( img.alt || 'Mimatest box' ) + ' — drag or use the arrow keys to rotate' );

	var cube = document.createElement( 'div' );
	cube.className = 'mt-box3d__cube';
	scene.appendChild( cube );

	[ 'front', 'back', 'left', 'right', 'top', 'bottom' ].forEach( function ( name ) {
		var face = document.createElement( 'div' );
		face.className = 'mt-box3d__face mt-box3d__face--' + name;
		cube.appendChild( face );
	} );
	cube.querySelector( '.mt-box3d__face--front' ).style.backgroundImage = 'url("' + cfg.front + '")';
	if ( cfg.back ) {
		cube.querySelector( '.mt-box3d__face--back' ).style.backgroundImage = 'url("' + cfg.back + '")';
	}

	var hint = document.createElement( 'span' );
	hint.className = 'mt-box3d__hint';
	hint.setAttribute( 'aria-hidden', 'true' );
	hint.textContent = uiText( 'box-hint', 'Drag to rotate' );
	scene.appendChild( hint );

	figure.classList.add( 'has-box3d' );
	figure.appendChild( scene );

	// ---- Size it to match the box in the image -------------------------------
	// The theme gives the <img> a fixed height; with object-fit: contain the picture is
	// letterboxed, so work out the drawn size and place the 3D box over it.
	function layout() {
		var w = img.clientWidth, h = img.clientHeight;
		if ( ! w || ! h ) {
			return;
		}
		var scale = Math.min( w / SRC_W, h / SRC_H );
		var bw = BOX_W * scale, bh = BOX_H * scale;
		scene.style.setProperty( '--w', bw + 'px' );
		scene.style.setProperty( '--h', bh + 'px' );
		scene.style.setProperty( '--d', bw * DEPTH + 'px' );
		scene.style.left = img.offsetLeft + ( w - bw ) / 2 + 'px';
		scene.style.top = img.offsetTop + ( h - bh ) / 2 + 'px';
	}
	if ( 'ResizeObserver' in window ) {
		new ResizeObserver( layout ).observe( img );
	} else {
		window.addEventListener( 'resize', layout );
	}
	if ( img.complete ) {
		layout();
	} else {
		img.addEventListener( 'load', layout );
	}

	// ---- Rotation ------------------------------------------------------------
	var rotX = -12, rotY = 18;   // a slight angle at rest so it reads as a box
	var velX = 0, velY = 0;
	var dragging = false, lastX = 0, lastY = 0, lastT = 0, frame = 0;

	function render() {
		cube.style.transform = 'rotateX(' + rotX + 'deg) rotateY(' + rotY + 'deg)';
	}

	// Keep the tilt where the box can't end up upside-down; spin around freely.
	function clampX( x ) {
		return Math.max( -80, Math.min( 80, x ) );
	}

	function glide() {
		velX *= 0.94;
		velY *= 0.94;
		rotX = clampX( rotX + velX );
		rotY += velY;
		render();
		if ( Math.abs( velX ) + Math.abs( velY ) > 0.05 ) {
			frame = requestAnimationFrame( glide );
		}
	}

	scene.addEventListener( 'pointerdown', function ( e ) {
		if ( e.button !== 0 ) {
			return;
		}
		dragging = true;
		cancelAnimationFrame( frame );
		lastX = e.clientX;
		lastY = e.clientY;
		lastT = e.timeStamp;
		velX = velY = 0;
		scene.setPointerCapture( e.pointerId );
		scene.classList.add( 'is-dragging', 'has-interacted' );
		e.preventDefault();
	} );

	scene.addEventListener( 'pointermove', function ( e ) {
		if ( ! dragging ) {
			return;
		}
		var dx = e.clientX - lastX, dy = e.clientY - lastY;
		var dt = Math.max( 1, e.timeStamp - lastT );
		rotY += dx * 0.5;
		rotX = clampX( rotX - dy * 0.5 );
		// Velocity in degrees per ~16ms frame, for the glide after release.
		velY = ( dx * 0.5 ) / dt * 16;
		velX = ( -dy * 0.5 ) / dt * 16;
		lastX = e.clientX;
		lastY = e.clientY;
		lastT = e.timeStamp;
		render();
	} );

	function endDrag() {
		if ( ! dragging ) {
			return;
		}
		dragging = false;
		scene.classList.remove( 'is-dragging' );
		if ( ! reduceMotion ) {
			frame = requestAnimationFrame( glide );
		}
	}
	scene.addEventListener( 'pointerup', endDrag );
	scene.addEventListener( 'pointercancel', endDrag );

	scene.addEventListener( 'keydown', function ( e ) {
		var step = 15;
		switch ( e.key ) {
			case 'ArrowLeft': rotY -= step; break;
			case 'ArrowRight': rotY += step; break;
			case 'ArrowUp': rotX = clampX( rotX + step ); break;
			case 'ArrowDown': rotX = clampX( rotX - step ); break;
			default: return;
		}
		e.preventDefault();
		scene.classList.add( 'has-interacted' );
		render();
	} );

	render();
}() );
