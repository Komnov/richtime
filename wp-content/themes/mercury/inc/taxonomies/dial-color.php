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
function dial_color_taxonomy() {

	$labels = [
		'name'                  => _x( 'Цвет циферблата', 'Taxonomy цвет циферблата', 'text-domain' ),
		'singular_name'         => _x( 'Цвет циферблата', 'Taxonomy цвет циферблата', 'text-domain' ),
		'search_items'          => __( 'Search Цвет циферблата', 'text-domain' ),
		'popular_items'         => __( 'Popular Цвет циферблата', 'text-domain' ),
		'all_items'             => __( 'All Цвет циферблата', 'text-domain' ),
		'parent_item'           => __( 'Parent Цвет циферблата', 'text-domain' ),
		'parent_item_colon'     => __( 'Parent Цвет циферблата', 'text-domain' ),
		'edit_item'             => __( 'Edit Цвет циферблата', 'text-domain' ),
		'update_item'           => __( 'Update Цвет циферблата', 'text-domain' ),
		'add_new_item'          => __( 'Add New Цвет циферблата', 'text-domain' ),
		'new_item_name'         => __( 'New Цвет циферблата Name', 'text-domain' ),
		'add_or_remove_items'   => __( 'Add or remove Цвет циферблата', 'text-domain' ),
		'choose_from_most_used' => __( 'Choose from most used Цвет циферблата', 'text-domain' ),
		'menu_name'             => __( 'Цвет циферблата', 'text-domain' ),
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

	register_taxonomy( 'dial-color', [ 'product' ], $args );
}

add_action( 'init', 'dial_color_taxonomy' );