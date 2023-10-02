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
function body_material_taxonomy() {

	$labels = [
		'name'                  => _x( 'Материал корпуса', 'Taxonomy plural name', 'text-domain' ),
		'singular_name'         => _x( 'Материал корпуса', 'Taxonomy singular name', 'text-domain' ),
		'search_items'          => __( 'Search Материал корпуса', 'text-domain' ),
		'popular_items'         => __( 'Popular Материал корпуса', 'text-domain' ),
		'all_items'             => __( 'All Материал корпуса', 'text-domain' ),
		'parent_item'           => __( 'Parent Материал корпуса', 'text-domain' ),
		'parent_item_colon'     => __( 'Parent Материал корпуса', 'text-domain' ),
		'edit_item'             => __( 'Edit Материал корпуса', 'text-domain' ),
		'update_item'           => __( 'Update Материал корпуса', 'text-domain' ),
		'add_new_item'          => __( 'Add New Материал корпуса', 'text-domain' ),
		'new_item_name'         => __( 'New Материал корпуса Name', 'text-domain' ),
		'add_or_remove_items'   => __( 'Add or remove Материал корпуса', 'text-domain' ),
		'choose_from_most_used' => __( 'Choose from most used Материал корпуса', 'text-domain' ),
		'menu_name'             => __( 'Материал корпуса', 'text-domain' ),
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

	register_taxonomy( 'body-material', [ 'product' ], $args );
}

add_action( 'init', 'body_material_taxonomy' );