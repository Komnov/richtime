<?php

/**
 * Create a taxonomy
 *
 * @param string  Name of taxonomy object
 * @param array|string  Name of the object type for the taxonomy object.
 * @param array|string  Taxonomy arguments
 *
 * @return null|WP_Error WP_Error if errors, otherwise null.
 * @uses  Inserts new taxonomy object into the list
 * @uses  Adds query vars
 *
 */
function complex_functions_taxonomy() {

	$labels = [
		'name'                  => _x( 'Сложные функции', 'Taxonomy plural name', 'text-domain' ),
		'singular_name'         => _x( 'Сложные функции', 'Taxonomy singular name', 'text-domain' ),
		'search_items'          => __( 'Search Сложные функции', 'text-domain' ),
		'popular_items'         => __( 'Popular Сложные функции', 'text-domain' ),
		'all_items'             => __( 'All Сложные функции', 'text-domain' ),
		'parent_item'           => __( 'Parent Сложные функции', 'text-domain' ),
		'parent_item_colon'     => __( 'Parent Сложные функции', 'text-domain' ),
		'edit_item'             => __( 'Edit Сложные функции', 'text-domain' ),
		'update_item'           => __( 'Update Сложные функции', 'text-domain' ),
		'add_new_item'          => __( 'Add New Сложные функции', 'text-domain' ),
		'new_item_name'         => __( 'New Сложные функции Name', 'text-domain' ),
		'add_or_remove_items'   => __( 'Add or remove Сложные функции', 'text-domain' ),
		'choose_from_most_used' => __( 'Choose from most used Сложные функции', 'text-domain' ),
		'menu_name'             => __( 'Сложные функции', 'text-domain' ),
	];

	$args = [
		'labels'            => $labels,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_admin_column' => false,
		'hierarchical'      => true,
		'show_tagcloud'     => true,
		'show_ui'           => true,
		'query_var'         => true,
		'rewrite'           => true,
		'capabilities'      => [],
	];

	register_taxonomy( 'complex-functions', [ 'product' ], $args );
}

add_action( 'init', 'complex_functions_taxonomy' );