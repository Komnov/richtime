<?php

/**
 * Term image
 *
 * @param        $term_id
 * @param string $size
 *
 * @return false|mixed|string
 */
function get_term_image( $term_id, $size = 'thumbnail_id' ) {
	$thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );

	if ( 'thumbnail_id' !== $size ) {
		return wp_get_attachment_image_src( $thumbnail_id, $size )[0];
	}

	return wp_get_attachment_url( $thumbnail_id );
}
