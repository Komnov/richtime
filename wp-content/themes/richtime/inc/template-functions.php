<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package richtime
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function richtime_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}

add_filter( 'body_class', 'richtime_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function richtime_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}

add_action( 'wp_head', 'richtime_pingback_header' );

function get_product_cat_image( $term_id ) {

	// get the thumbnail id using the queried category term_id
	$thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );
	if ( '0' !== $thumbnail_id ) {
		// get the image URL
		$image = wp_get_attachment_url( $thumbnail_id );

		// print the IMG HTML
		return "<img src='{$image}' alt='' />";
	}
}
