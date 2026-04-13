/* AI Header & Footer for Elementor — Admin JS */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		initTabs();
		initFileInputLabels();
	} );

	/**
	 * Tab switching — one set of tabs per content type section.
	 */
	function initTabs() {
		document.querySelectorAll( '.ahfe-tabs' ).forEach( function ( tabsEl ) {
			var buttons = tabsEl.querySelectorAll( '.ahfe-tab-btn' );
			var panels  = tabsEl.querySelectorAll( '.ahfe-tab-panel' );

			buttons.forEach( function ( btn ) {
				btn.addEventListener( 'click', function () {
					var targetId = btn.getAttribute( 'data-tab' );

					// Update button states.
					buttons.forEach( function ( b ) {
						b.classList.remove( 'ahfe-tab-btn--active' );
					} );
					btn.classList.add( 'ahfe-tab-btn--active' );

					// Show the matching panel.
					panels.forEach( function ( panel ) {
						if ( panel.id === targetId ) {
							panel.classList.add( 'ahfe-tab-panel--active' );
						} else {
							panel.classList.remove( 'ahfe-tab-panel--active' );
						}
					} );
				} );
			} );
		} );
	}

	/**
	 * Show the selected filename next to the file input.
	 */
	function initFileInputLabels() {
		document.querySelectorAll( '.ahfe-file-input' ).forEach( function ( input ) {
			var label    = input.closest( '.ahfe-file-label' );
			var textSpan = label ? label.querySelector( '.ahfe-file-text' ) : null;
			if ( ! textSpan ) return;

			var defaultText = textSpan.textContent;

			input.addEventListener( 'change', function () {
				if ( input.files && input.files.length > 0 ) {
					textSpan.textContent = input.files[ 0 ].name;
				} else {
					textSpan.textContent = defaultText;
				}
			} );
		} );
	}
} )();
