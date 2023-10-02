<?php

class Main_Menu_Walker extends Walker_Nav_Menu {

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		parent::start_el( $output, $item, $depth, $args, $id ); // TODO: Change the autogenerated stub
		if ( $depth === 0 ) {
			if ( $item->type === 'taxonomy' && $item->object === 'product_cat' && in_array( 'menu-item-has-children', $item->classes ) ) {
				$output .= "<div class='sub-menu__wrapper'>";
			}
		}
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			if ( $item->type === 'taxonomy' && $item->object === 'product_cat' && in_array( 'menu-item-has-children', $item->classes ) ) {
				$output .= "</div>";
			}
		}
		parent::end_el( $output, $item, $depth, $args ); // TODO: Change the autogenerated stub
	}

}