<?php

/**
 * Actions
 *
 * @see mercury_product_open_wrapper()
 * @see mercury_product_close_wrapper()
 * @see add_favorite_button()
 * @see get_user_favorites_list()
 * @see mercury_update_favorite()
 * @see mercury_remove_favorite()
 * @see mercury_filter_products()
 * @see mercury_woocommerce_init()
 */
add_action( 'mercury_before_product_link', 'mercury_product_open_wrapper' );
add_action( 'mercury_after_product_link', 'mercury_product_close_wrapper' );
add_action( 'mercury_product_item', 'add_favorite_button' );
add_action( 'woocommerce_shop_loop_item_title', 'mercury_before_product_title', 1 );
add_action( 'woocommerce_after_shop_loop_item_title', 'mercury_after_product_title', 999 );
add_action( 'woocommerce_shop_loop_item_title', 'get_product_tags', 20 );
add_action( 'woocommerce_shop_loop_item_title', 'get_product_chars', 30 );
add_action( 'init', 'get_user_favorites_list' );
add_action( 'wp_ajax_mercury_update_favorite', 'mercury_update_favorite' );
add_action( 'wp_ajax_mercury_remove_favorite', 'mercury_remove_favorite' );
add_action( 'pre_get_posts', 'mercury_filter_products' );
add_action( 'woocommerce_init', 'mercury_woocommerce_init' );
add_action( 'pre_get_posts', 'mercury_news_filter' );
add_action( 'woocommerce_before_add_to_cart_button', 'mercury_add_to_cart_before_wrapper' );
add_action( 'woocommerce_after_add_to_cart_button', 'mercury_add_favorite_single', 5 );
add_action( 'woocommerce_after_add_to_cart_button', 'mercury_add_to_cart_after_wrapper', 100 );
add_action( 'woocommerce_single_product_summary', 'mercury_product_article', 1 );
add_action( 'woocommerce_single_product_summary', 'mercury_product_short_description', 6 );
add_action( 'mercury_woocommerce_after_content', 'mercury_woocommerce_actions' );

/**
 * Remove actions and filters
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

/**
 * Filters
 *
 * @see set_attribute_hierarchy()
 * @see mercury_product_tabs()
 */
add_filter( 'woocommerce_taxonomy_args_pa_individualnyj-podbor', 'set_attribute_hierarchy', 10, 1 );
add_filter( 'woocommerce_product_tabs', 'mercury_product_tabs' );
add_filter( 'woocommerce_product_loop_start', 'mercury_category_loop_display', 10, 1 );
add_filter( 'excerpt_length', 'mercury_excerpt_length' );
add_filter( 'comment_form_fields', 'move_comment_field' );
add_filter( 'woocommerce_process_registration_errors', 'mercury_user_registration', 10, 1 );
add_filter( 'woocommerce_single_product_carousel_options', 'mercury_gallery_settings' );
add_filter( 'woocommerce_checkout_fields', 'custom_checkout_fields' );
add_filter( 'woocommerce_gateway_icon', 'mastercard_change_icon', 10, 2 );

function add_favorite_button() {
	global $product, $favorites;
	$favorites = (array) $favorites;
	echo sprintf( '<span class="add-favorite" data-product_id="%d"><svg width="24" height="22" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path opacity="0.5" d="M23.9597 8.32198C23.8841 8.11156 23.7464 7.92674 23.5635 7.78993C23.3805 7.65312 23.1602 7.5702 22.9291 7.55122L16.1104 6.59641L13.0546 0.648941C12.9565 0.454445 12.8032 0.290416 12.6125 0.175644C12.4217 0.0608723 12.2012 0 11.9761 0C11.7509 0 11.5304 0.0608723 11.3396 0.175644C11.1489 0.290416 10.9957 0.454445 10.8975 0.648941L7.84168 6.5849L1.02296 7.55122C0.801172 7.58148 0.592658 7.67082 0.421083 7.80909C0.249508 7.94736 0.121742 8.12903 0.0522846 8.33348C-0.0112949 8.53327 -0.0170004 8.74591 0.0357803 8.94858C0.0885611 9.15125 0.197836 9.33629 0.351877 9.48386L5.30114 14.0854L4.10277 20.6195C4.05999 20.8352 4.08239 21.0581 4.16733 21.262C4.25227 21.4658 4.3962 21.6421 4.58212 21.7699C4.76333 21.8943 4.97707 21.9677 5.19934 21.9819C5.4216 21.9961 5.6436 21.9506 5.84041 21.8504L11.9761 18.7789L18.0877 21.8619C18.2559 21.953 18.4459 22.0006 18.639 22C18.8928 22.0009 19.1404 21.9243 19.346 21.7814C19.5319 21.6536 19.6759 21.4773 19.7608 21.2735C19.8458 21.0696 19.8682 20.8467 19.8254 20.631L18.627 14.0969L23.5763 9.49536C23.7493 9.35465 23.8772 9.16969 23.945 8.96199C24.0129 8.75428 24.018 8.53235 23.9597 8.32198ZM16.5898 12.9235C16.4492 13.054 16.3441 13.2155 16.2835 13.394C16.2229 13.5724 16.2088 13.7624 16.2422 13.9473L17.1051 18.7674L12.5992 16.4667C12.4258 16.378 12.2324 16.3317 12.036 16.3317C11.8395 16.3317 11.6461 16.378 11.4727 16.4667L6.96687 18.7674L7.8297 13.9473C7.86318 13.7624 7.84901 13.5724 7.78843 13.394C7.72786 13.2155 7.62271 13.054 7.48217 12.9235L3.88707 9.47236L8.9322 8.77062C9.12633 8.7447 9.31088 8.67346 9.46967 8.56316C9.62845 8.45285 9.75663 8.30683 9.84296 8.13791L11.9761 3.75497L14.229 8.14942C14.3153 8.31834 14.4435 8.46435 14.6023 8.57466C14.7611 8.68497 14.9456 8.7562 15.1397 8.78213L20.1849 9.48386L16.5898 12.9235Z" fill="#232323" fill-opacity="0.7"/>
							</svg>
							</span>',
	              $product->get_id()
	);
}

function set_attribute_hierarchy( $data ) {
	$data['hierarchical'] = true;

	return $data;
}

/**
 * Get user favorites products list
 */
function get_user_favorites_list() {
	global $favorites;
	if ( ! is_user_logged_in() ) {
		$favorites = [];

		return;
	}

	$favorites = get_user_meta( get_current_user_id(), 'favorites_products', true );
}

/**
 * Add product to user favorites list
 */
function mercury_update_favorite() {
	if ( is_user_logged_in() && ! empty( $_POST['pid'] ) ) {
		$user_id        = get_current_user_id();
		$favorites_list = get_user_meta( $user_id, 'favorites_products', true );
		$pid            = (int) $_POST['pid'];
		$key            = array_search( $pid, $favorites_list, true );
		if ( false !== $key ) {
			unset( $favorites_list[ $key ] );
			$updated = update_user_meta( $user_id, 'favorites_products', $favorites_list );
			$message = __( 'Removed', 'mercury' );
		} else {
			array_push( $favorites_list, $pid );
			$updated = update_user_meta( get_current_user_id(), 'favorites_products', $favorites_list );
			$message = __( 'Added', 'mercury' );
		}
		$updated ? wp_send_json_success( [ 'message' => $message ] ) : wp_send_json_error();
	}
}

function mercury_filter_products( $query ) {
	if ( ! $query->is_admin && is_product_taxonomy() ) {

		$args = [];

		if ( ! empty( $_GET['filter'] ) ) {
			foreach ( $_GET['filter'] as $key => $items ) {
				if ( 'brands' === $key ) {
					$item   = [
						'taxonomy' => 'product_cat',
						'field'    => 'id',
						'terms'    => $items,
						'operator' => 'IN',
					];
					$args[] = $item;
				} else {
					$item   = [
						'taxonomy' => $key,
						'field'    => 'id',
						'terms'    => $items,
						'operator' => 'IN',
					];
					$args[] = $item;
				}
			}
		}

		if ( ! empty( $args ) ) {
			$query->set(
				'tax_query',
				$args
			);
		}
	}
}

function mercury_woocommerce_init() {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
}

function mercury_product_tabs( $tabs ) {

	unset( $tabs['additional_information'] );

	$tabs['characteristics'] = [
		'title'    => 'Характеристики',
		'priority' => 5,
		'callback' => 'mercury_chars_callback',
	];

	$tabs['delivery'] = [
		'title'    => 'Доставка',
		'priority' => 10,
		'callback' => 'mercury_delivery_callback',
	];

	$tabs['product_news'] = [
		'title'    => 'Новости',
		'priority' => 20,
		'callback' => 'mercury_product_news_callback',
	];

	return $tabs;
}

function mercury_chars_callback() {
	get_template_part( 'template-parts/tabs/characteristics' );
}

function mercury_product_news_callback() {
	get_template_part( 'template-parts/tabs/news' );
}

function mercury_delivery_callback() {
	get_template_part( 'template-parts/tabs/delivery' );
}

function mercury_product_open_wrapper() {
	echo '<div class="mercury-loop__product">';
}

function mercury_product_close_wrapper() {
	echo '</div>';
}

function get_product_tags() {
	global $product;
	$product_tags = get_the_terms( $product->get_id(), 'product_tag' );
	$result       = [];
	foreach ( $product_tags as $product_tag ) {
		$result[] = $product_tag->name;
	}

	echo '<p class="mercury-product__tags">' . implode( ', ', $result ) . '</p>';
}

function mercury_before_product_title() {
	echo '<div class="mercury-loop__product-title-wrapper">';
}

function mercury_after_product_title() {
	echo "</div>";
}

function get_product_chars() {
	$str = "Автоматический подзавод<br>
	Диаметр Камня:  30 mm<br>
	Толщина: 7.40 mm<br>
	CFB 1967 47 ";

	echo "<p class='mercury-product__chars'>'. $str .'</p>";
}

function mercury_category_loop_display( $html ) {
	return '';
}

function mercury_excerpt_length() {
	return 20;
}

function mercury_news_filter( $query ) {
	if ( ! is_admin() && $query->is_home && $query->is_main_query() ) {
		$query->set( 'offset', 1 );
	}
}

function move_comment_field( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;

	return $fields;
}

function mercury_user_registration( $errors ) {
	if ( isset( $_POST['register'] ) ) {

		if ( $_POST['password'] !== $_POST['password2'] ) {
			$errors->add( 'error_password_match', __( 'Password mismatch', 'mercury' ) );
		}
	}

	return $errors;
}

function mercury_gallery_settings( $options ) {

	$options['directionNav'] = true;
	$options['prevText']     = '<svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M0.869981 8.82833L7.47331 15.42C7.58177 15.5294 7.71081 15.6161 7.85297 15.6754C7.99514 15.7346 8.14763 15.7651 8.30165 15.7651C8.45566 15.7651 8.60815 15.7346 8.75032 15.6754C8.89249 15.6161 9.02152 15.5294 9.12998 15.42C9.34727 15.2014 9.46924 14.9057 9.46924 14.5975C9.46924 14.2893 9.34727 13.9936 9.12998 13.775L3.35498 7.94167L9.12998 2.16667C9.34727 1.94808 9.46924 1.65238 9.46924 1.34417C9.46924 1.03595 9.34727 0.740256 9.12998 0.521666C9.02193 0.41143 8.89308 0.323731 8.75089 0.263653C8.6087 0.203573 8.45601 0.172308 8.30165 0.171668C8.14729 0.172308 7.99459 0.203573 7.85241 0.263653C7.71022 0.323731 7.58137 0.41143 7.47332 0.521666L0.869982 7.11333C0.751559 7.22258 0.657049 7.35518 0.592407 7.50276C0.527765 7.65034 0.494393 7.80971 0.494393 7.97083C0.494393 8.13195 0.527765 8.29132 0.592407 8.43891C0.657049 8.58649 0.751559 8.71908 0.869981 8.82833Z" fill="black"/>
								</svg>
								';
	$options['nextText']     = '<svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M9.13002 7.17167L2.52669 0.579999C2.41823 0.47065 2.28919 0.383857 2.14703 0.324626C2.00486 0.265396 1.85237 0.234901 1.69835 0.234901C1.54434 0.234901 1.39185 0.265396 1.24968 0.324626C1.10751 0.383857 0.978476 0.47065 0.87002 0.579999C0.652727 0.798589 0.530762 1.09428 0.530762 1.4025C0.530762 1.71072 0.652727 2.00641 0.87002 2.225L6.64502 8.05833L0.87002 13.8333C0.652727 14.0519 0.530762 14.3476 0.530762 14.6558C0.530762 14.9641 0.652727 15.2597 0.87002 15.4783C0.97807 15.5886 1.10692 15.6763 1.24911 15.7363C1.3913 15.7964 1.54399 15.8277 1.69835 15.8283C1.85271 15.8277 2.00541 15.7964 2.1476 15.7363C2.28978 15.6763 2.41864 15.5886 2.52669 15.4783L9.13002 8.88667C9.24844 8.77742 9.34295 8.64482 9.40759 8.49724C9.47224 8.34966 9.50561 8.19029 9.50561 8.02917C9.50561 7.86805 9.47224 7.70868 9.40759 7.56109C9.34295 7.41351 9.24844 7.28092 9.13002 7.17167Z" fill="black"/>
								</svg>
								';

	return $options;
}

function mercury_add_favorite_single() {
	echo '<div class="product-favorite">';
	add_favorite_button();
	echo '</div>';
}

function mercury_add_to_cart_before_wrapper() {
	echo "<div class='add-to-cart-wrapper'>";
}

function mercury_add_to_cart_after_wrapper() {
	echo "</div>";
}

function mercury_product_article() {
	get_template_part( 'template-parts/product/single-article' );
}

function mercury_product_short_description() {
	get_template_part( 'template-parts/product/single-short-description' );
}

function custom_checkout_fields( $fields ) {
	unset( $fields['billing']['billing_company'] );
	unset( $fields['billing']['billing_postcode'] );
	unset( $fields['billing']['billing_address_1'] );
	unset( $fields['billing']['billing_state'] );
//	unset( $fields['billing']['billing_country'] );
	unset( $fields['billing']['billing_city'] );

	$fields['billing']['middle_name'] = [
		'class'       => [
			'form-row-wide',
		],
		'id'          => 'middle_name',
		'required'    => true,
		'label'       => 'Отчество',
		'placeholder' => '',
		'priority'    => 30,
	];

	return $fields;
}

function mastercard_change_icon( $icon, $id ) {
	if ( 'fondy' === $id ) {
		$icon = "<img src='" . get_template_directory_uri() . '/assets/images/visa.png' . "'>";
	} elseif ( 'paypal' === $id ) {
		$icon = "<img src='" . get_template_directory_uri() . '/assets/images/paypal.png' . "'>";
	}

	return $icon;
}

function mercury_woocommerce_actions() {
	if ( is_product() ) {
		woocommerce_output_related_products();
	}
}