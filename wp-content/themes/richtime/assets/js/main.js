'use strict';

// eslint-disable-next-line no-unused-vars,no-undef
const RTFavorite = window.RTFavorite || ( function( document, window, $ ) {
	let el = {
		$favorite: $( '.add-favorite' ),
	};

	const app = {
		/**
		 * Start the engine.
		 *
		 * @since 1.6.0
		 */
		init() {
			$( app.ready );
		},

		/**
		 * Document ready.
		 *
		 * @since 1.6.0
		 */
		ready() {
			// eslint-disable-next-line no-console
			app.events();
			app.createMainCarousel();
		},
		events() {
			el.$favorite
				.on( 'click', app.favorite );
		},
		favorite( event ) {
			event.preventDefault();
			const $obj = $( this );
			const productId = $obj.data( 'product_id' );
			// eslint-disable-next-line no-unused-expressions
			app.updateFavorite( productId, $obj );
		},
		updateFavorite( productId, button ) {
			const data = {
				action: 'richtime_update_favorite',
				pid: productId,
			};

			$.post( '/wp-admin/admin-ajax.php', data, function( response ) {
				button.text( response.data.message );
			} );
		},
		createMainCarousel() {

		},
	};

	return app;
// eslint-disable-next-line no-undef
}( document, window, jQuery ) );

RTFavorite.init();