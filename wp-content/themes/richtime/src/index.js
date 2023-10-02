'use strict';

import 'slick-carousel';
// import 'masonry-layout/dist/masonry.pkgd.min';
import 'bootstrap';
import flatpickr from 'flatpickr';
import {
	Russian as ru,
} from 'flatpickr/dist/l10n/ru.js';

// eslint-disable-next-line no-unused-vars,no-undef
const RTFavorite = window.RTFavorite || (function (document, window, $) {
	const app = {
		/**
		 * Start the engine.
		 *
		 * @since 1.6.0
		 */
		init() {
			$(app.ready);
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
			app.buildMoscowGallery();
			app.searchBlock();
			app.clearWishlist();
			app.recipientFields();
			app.clearFilter();
			app.datePicker();
		},
		events() {
			$(document).on('click', '.add-favorite', app.favorite);
		},
		favorite(event) {
			event.preventDefault();
			const $obj = $(this);
			const productId = $obj.data('product_id');
			const account = $obj.data('account');
			// eslint-disable-next-line no-unused-expressions
			if (account !== '') {
				window.location.href = account + '?fp=' + productId;
			} else {
				app.updateFavorite(productId, $obj);
			}
		},
		updateFavorite(productId, button) {
			const data = {
				action: 'richtime_update_favorite',
				pid: productId,
			};

			$.post('/wp-admin/admin-ajax.php', data, function (response) {
				const icon = button.find('i');
				if ('added' === response.data.message) {
					icon.attr('class', 'bi bi-star-fill');
				} else if ('removed' === response.data.message) {
					icon.attr('class', 'bi bi-star');
				}
			});
		},
		createMainCarousel() {
			const banners = $('#banners');
			const settings = {
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: true,
				infinite: true,
				prevArrow: $('.banners_prev'),
				nextArrow: $('.banners_next'),
				lazyLoad: 'ondemand',
				fade: true,
				autoplay: true,
				autoplaySpeed: 2500,
				speed: 1500,
				cssEase: 'ease-in-out',
				responsive: [
					{
						breakpoint: 992,
						settings: {
							autoplay: true,
							autoplaySpeed: 3000,
							arrows: false,
						},
					},
				],
			};
			banners.on('init', function (event, slick) {
				app.buildSlickArrows(slick, 0, settings);
			});
			banners.slick(settings);
			banners.on('beforeChange', function (event, slick, currentIn, nextIndex) {
				app.buildSlickArrows(slick, nextIndex, settings);
			});
		},

		buildSlickArrows(slick, nextIndex, settings) {
			if (!slick.$prevArrow || !slick.$nextArrow) {
				return;
			}
			let prevArrowIndex;
			let nextArrowIndex;
			const totalCount = slick.slideCount - 1;

			if (settings.infinite) {
				prevArrowIndex = nextIndex - 1 < 0 ? totalCount : nextIndex - 1;
				nextArrowIndex = nextIndex + 1 > totalCount ? 0 : nextIndex + 1;
			} else {
				prevArrowIndex = nextIndex - 1 <= 0 ? 0 : nextIndex - 1;
				nextArrowIndex = nextIndex + 1 >= totalCount ? totalCount : nextIndex + 1;
			}

			const nextSlide = $(slick.$slides.get(nextArrowIndex));
			const prevSlide = $(slick.$slides.get(prevArrowIndex));
			;
			const prevImg = prevSlide.find('.carousel-item').data('thumbnail');
			const nextImg = nextSlide.find('.carousel-item').data('thumbnail');
			slick.$prevArrow.find('img').attr('src', prevImg);
			slick.$nextArrow.find('img').attr('src', nextImg);
		},

		createNewProductsCarousel() {
			$('#new-products').slick({
				slidesToShow: 6,
				slidesToScroll: 1,
				autoplay: true,
				autoplaySpeed: 1500,
				arrows: true,
				prevArrow: '<i class="bi bi-chevron-compact-left"></i>',
				nextArrow: '<i class="bi bi-chevron-compact-right"></i>',
				responsive: [
					{
						breakpoint: 480,
						settings: {
							slidesToShow: 2,
						},
					},
					{
						breakpoint: 576,
						settings: {
							slidesToShow: 2,
						},
					},
					{
						breakpoint: 992,
						settings: {
							slidesToShow: 3,
						},
					},
				],
			});
		},
		sideMenu() {
			$('.hamburger').on('click', function () {
				const sideMenu = $('.side-menu');
				sideMenu.animate({
					width: 'show',
				});
				$('.side-menu__close-wrapper').on('click', 'button', function () {
					sideMenu.animate({
						width: 'hide',
					});
				});
				$('#side-menu').on('click', '.menu-item-has-children a', function (e) {
					e.preventDefault();
					const parent = $(this).parent();
					parent.find('.sub-menu').slideDown();
					parent.find('a').click( function () {
						window.location.href = $(this).attr('href');
					} );
				});
			});
		},
		buildMoscowGallery() {
			$('#moscow-gallery').slick({
				slidesToShow: 3,
				autoplay: true,
				arrows: false,
			});
		},
		searchBlock() {
			const searchWrapper = $('.search-input-wrapper');
			$('#search-modal').on('click', function (e) {
				e.preventDefault();
				searchWrapper.slideDown();
			});
			$('.site').on('click', '.search-close', function () {
				searchWrapper.slideUp();
				$('#search-results').slideUp();
			});
			$('#ajax-search-form').on('submit', function (e) {
				e.preventDefault();
				const data = $(this).serialize();
				app.ajaxSearch(data);
			});
		},
		ajaxSearch(data) {
			$.ajax({
				url: '/wp-admin/admin-ajax.php?action=ajax_search',
				type: 'POST',
				data,
			})
					.done(function (response) {
						const resultBlock = $('#search-results');
						resultBlock.slideDown();
						resultBlock.html(response.data);
					})
					.always(function () {
						console.log('complete');
					});
		},
		clearWishlist() {
			$('#clear-wishlist').on('click', function () {
				$(this).prop('disabled', true);
				$.post('/wp-admin/admin-ajax.php?action=richtime_clear_favorites')
						.done(function (response) {
							if (response.data === true) {
								window.location.reload();
							}
						});
			});
		},
		recipientFields() {
			const checkbox = $('#custom_recipient');
			let status = this.getRecipientStatus(checkbox);

			this.changeRecipientStatus(status);

			checkbox.on('click', (e) => {
				status = this.getRecipientStatus(checkbox);
				this.changeRecipientStatus(status);
			});
		},
		getRecipientStatus(object) {
			return object.prop('checked');
		},
		changeRecipientStatus(status) {
			const required = $('.recipient-required');
			const show = $('.recipient-field');
			if (status) {
				required.prop('required', true);
				show.show();
			} else {
				required.prop('required', false);
				show.hide();
			}
		},
		clearFilter() {
			$('#clear-filter').on('click', function () {
				window.location.href = window.location.origin + window.location.pathname;
			});
		},
		datePicker() {
			const locale = document.documentElement.lang;
			$('#custom_recipient_date').flatpickr({
				locale: locale.slice(0, 2),
				minDate: 'today',
				dateFormat: 'd-m-Y',
			});
		},
	};

	return app;
// eslint-disable-next-line no-undef
}(document, window, jQuery));

RTFavorite.init();
