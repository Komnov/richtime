'use strict';

import "slick-carousel";
import "masonry-layout/dist/masonry.pkgd.min";
import "bootstrap";

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
			app.createNewProductsCarousel();
			app.sideMenu();
			app.buildTiles();
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
				action: 'mercury_update_favorite',
				pid: productId,
			};

			$.post( '/wp-admin/admin-ajax.php', data, function( response ) {
				button.text( response.data.message );
			} );
		},
		createMainCarousel() {
			$( '#banners' ).slick( {
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: false,
			} );
		},
		createNewProductsCarousel() {
			$( '#new-products' ).slick( {
				slidesToShow: 4,
				slidesToScroll: 1,
				autoplay: true,
				autoplaySpeed: 1500,
				arrows: false,
			} );
		},
		sideMenu() {
			$( '.hamburger' ).on( 'click', function() {
				$( this ).toggleClass( 'is-active' );
			} );
		},
		buildTiles() {
			$( '.grid' ).masonry( {
				// options
				itemSelector: '.grid-item',
				columnWidth: 70,
			} );
		},
	};

	return app;
// eslint-disable-next-line no-undef
}( document, window, jQuery ) );

RTFavorite.init();