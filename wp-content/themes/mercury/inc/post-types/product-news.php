<?php

/**
 * Registers a new post type
 * @uses $wp_post_types Inserts new post type object into the list
 *
 * @param string  Post type key, must not exceed 20 characters
 * @param array|string  See optional args description above.
 * @return object|WP_Error the registered post type object, or an error object
 */
function register_product_news() {

	$labels = array(
		'name'               => __( 'Product News', 'mercury' ),
		'singular_name'      => __( 'Product news', 'mercury' ),
		'add_new'            => _x( 'Add New Product news', 'mercury', 'mercury' ),
		'add_new_item'       => __( 'Add New Product news', 'mercury' ),
		'edit_item'          => __( 'Edit Product news', 'mercury' ),
		'new_item'           => __( 'New Product news', 'mercury' ),
		'view_item'          => __( 'View Product news', 'mercury' ),
		'search_items'       => __( 'Search Product News', 'mercury' ),
		'not_found'          => __( 'No Product News found', 'mercury' ),
		'not_found_in_trash' => __( 'No Product News found in Trash', 'mercury' ),
		'parent_item_colon'  => __( 'Parent Product news:', 'mercury' ),
		'menu_name'          => __( 'Product News', 'mercury' ),
	);

	$args = array(
		'labels'              => $labels,
		'hierarchical'        => false,
		'description'         => 'description',
		'taxonomies'          => array(),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => null,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'supports'            => array(
			'title',
			'editor',
		),
	);

	register_post_type( 'product-news', $args );
}

add_action( 'init', 'register_product_news' );
