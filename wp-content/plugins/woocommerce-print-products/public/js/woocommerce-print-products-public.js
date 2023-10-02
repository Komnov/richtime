(function( $ ) {
	'use strict';


	var productURL = location.protocol + '//' + location.host + location.pathname;

	var template = $('#woocommerce_print_product_template');
	if(template.length > 0) {

		template.on('change', function(e) {
			e.preventDefault();

			var templateValue = template.val();
			var newURL = '?print-products=pdf&template=' + templateValue;

			$('.woocommerce-print-products-pdf-link').attr('href', productURL + newURL);
		});
	}

	var variationIdField = $('input[name="variation_id"]');
	var variationId = 0;
	var URLParameter = "";

	if(variationIdField.length > 0) {

		var oldURL = "";

		variationIdField.on('change', function(e) {

			e.preventDefault();

			variationId = $(this).val();

			var newURL = '?print-products=pdf&variation=' + variationId + '&' + URLParameter;

			$('.woocommerce-print-products-pdf-link').attr('href', productURL + newURL);
			$('.woocommerce-print-products-pdf-link a').attr('href', productURL + newURL);

		}).trigger('change');
	}

	
	var tmExtraProductOptionsFields = $('#tm-extra-product-options-fields');
	var changeRunning = false;
	if(tmExtraProductOptionsFields.length > 0) {

		tmExtraProductOptionsFields.on('change', 'input, select', function(e) {


			setTimeout(function() { 

				var options = {};
				var allFields = $('#tm-extra-product-options-fields').find('input, select');

				options['Menge'] = $('input[name="quantity"]').val();
				options['Menge_price'] = parseFloat( $('[data-tiered-pricing-price]').text().replace('.', '') );

				allFields.each(function(i, index) {
					var field = $(this);
					var price = 0;
					var name = field.closest('.tc-row').find('.tm-epo-element-label').text();

					if(field.is("select")) {
						var value = field.find('option:selected').text();
					} else {
						var value = field.val();
					}
						
					var price = field.parents('li').first().find('.price').text().replace('.', '').replace(',', '.');
					var price = parseFloat( price );
					if(value !== "" && name !== "") {
						options[name] = value;

						if(price > 0) {
							options[name + '_price'] = price;
						}
						// options.push( {
						// 	'name': name,
						// 	'value': value,
						// 	'price': price,
						// });
					}
				});

				options['Gesamtpreis'] = $('.tm-final-totals .price').text().replace('.', '');

				var URLParameter = new URLSearchParams(options).toString();
				if(variationId) {
					var newURL = '?print-products=pdf&variation=' + variationId + '&' + URLParameter;
				} else {
					var newURL = '?print-products=pdf&' + URLParameter;
				}
				
				$('.woocommerce-print-products-pdf-link').attr('href', productURL + newURL);
				$('.woocommerce-print-products-pdf-link a').attr('href', productURL + newURL);

				changeRunning = false;

			}, 500 );
		});

		$('input[name="quantity"]').on('change', function(e) {


			if(changeRunning) {
				return;
			}

			changeRunning = true;
			tmExtraProductOptionsFields.find('input, select').first().trigger('change');

			changeRunning = false;
		});

		setTimeout(function() { 
			tmExtraProductOptionsFields.find('input, select').first().trigger('change');
		}, 2000 );
	}

})( jQuery );