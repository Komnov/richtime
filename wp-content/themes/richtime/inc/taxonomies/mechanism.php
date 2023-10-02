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
function mechanism_taxonomy() {

	$labels = [
		'name'                  => _x( 'Механизм часов', 'Taxonomy механизм часов', 'text-domain' ),
		'singular_name'         => _x( 'Механизм часов', 'Taxonomy механизм часов', 'text-domain' ),
		'search_items'          => __( 'Search Механизм часов', 'text-domain' ),
		'popular_items'         => __( 'Popular Механизм часов', 'text-domain' ),
		'all_items'             => __( 'All Механизм часов', 'text-domain' ),
		'parent_item'           => __( 'Parent Механизм часов', 'text-domain' ),
		'parent_item_colon'     => __( 'Parent Механизм часов', 'text-domain' ),
		'edit_item'             => __( 'Edit Механизм часов', 'text-domain' ),
		'update_item'           => __( 'Update Механизм часов', 'text-domain' ),
		'add_new_item'          => __( 'Add New Механизм часов', 'text-domain' ),
		'new_item_name'         => __( 'New Механизм часов Name', 'text-domain' ),
		'add_or_remove_items'   => __( 'Add or remove Механизм часов', 'text-domain' ),
		'choose_from_most_used' => __( 'Choose from most used Механизм часов', 'text-domain' ),
		'menu_name'             => __( 'Механизм часов', 'text-domain' ),
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

	register_taxonomy( 'mechanism', [ 'product' ], $args );
}

add_action( 'init', 'mechanism_taxonomy' );