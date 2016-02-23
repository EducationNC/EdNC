(function ( window, document ) {
	'use strict';

	var supportedBrowser = ( document.querySelector && window.addEventListener ),
		loaded = false,
		secret,
		secretTimeout,
		resizing;

	function sendEmbedMessage( message, value ) {
		window.parent.postMessage( {
			message: message,
			value: value,
			secret: secret
		}, '*' );
	}

	function onLoad() {
		if ( loaded ) {
			return;
		}
		loaded = true;

		if ( window.self === window.top ) {
			return;
		}

		/**
		 * Send this document's height to the parent (embedding) site.
		 */
		sendEmbedMessage( 'height', Math.ceil( document.body.getBoundingClientRect().height ) );
	}

	/**
	 * Iframe resize handler.
	 */
	function onResize() {
		if ( window.self === window.top ) {
			return;
		}

		clearTimeout( resizing );

		resizing = setTimeout( function () {
			sendEmbedMessage( 'height', Math.ceil( document.body.getBoundingClientRect().height ) );
		}, 100 );
	}

	/**
	 * Re-get the secret when it was added later on.
	 */
	function getSecret() {
		if ( window.self === window.top || !!secret ) {
			return;
		}

		secret = window.location.hash.replace( /.*secret=([\d\w]{10}).*/, '$1' );

		clearTimeout( secretTimeout );

		secretTimeout = setTimeout( function () {
			getSecret();
		}, 100 );
	}

	if ( supportedBrowser ) {
		getSecret();
		document.documentElement.className = document.documentElement.className.replace( /\bno-js\b/, '' ) + ' js';
		document.addEventListener( 'DOMContentLoaded', onLoad, false );
		window.addEventListener( 'load', onLoad, false );
		window.addEventListener( 'resize', onResize, false );
	}
})( window, document );
