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
function strap_type_taxonomy() {

	$labels = [
		'name'                  => _x( 'Тип ремешка', 'Taxonomy тип ремешка', 'text-domain' ),
		'singular_name'         => _x( 'Тип ремешка', 'Taxonomy тип ремешка', 'text-domain' ),
		'search_items'          => __( 'Search Тип ремешка', 'text-domain' ),
		'popular_items'         => __( 'Popular Тип ремешка', 'text-domain' ),
		'all_items'             => __( 'All Тип ремешка', 'text-domain' ),
		'parent_item'           => __( 'Parent Тип ремешка', 'text-domain' ),
		'parent_item_colon'     => __( 'Parent Тип ремешка', 'text-domain' ),
		'edit_item'             => __( 'Edit Тип ремешка', 'text-domain' ),
		'update_item'           => __( 'Update Тип ремешка', 'text-domain' ),
		'add_new_item'          => __( 'Add New Тип ремешка', 'text-domain' ),
		'new_item_name'         => __( 'New Тип ремешка Name', 'text-domain' ),
		'add_or_remove_items'   => __( 'Add or remove Тип ремешка', 'text-domain' ),
		'choose_from_most_used' => __( 'Choose from most used Тип ремешка', 'text-domain' ),
		'menu_name'             => __( 'Тип ремешка', 'text-domain' ),
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

	register_taxonomy( 'strap-type', [ 'product' ], $args );
}

add_action( 'init', 'strap_type_taxonomy' );