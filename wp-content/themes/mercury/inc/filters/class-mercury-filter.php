<?php

class Mercury_Filter {

	private $type;
	private $term_id;

	public function __construct( WP_Term $term ) {
		if ( 0 === $term->parent ) {
			$this->term_id = $term->term_id;
		} else {
			$this->term_id = $term->parent;
		}
		$this->type = get_field( 'global_cat_name', $term->taxonomy . '_' . $this->term_id );
	}

	private function term_attribute_relation(): array {
		return [
			'clock'       => [
				'pa_dlya-kogo',
				'pa_stil',
			],
			'jewels'      => [],
			'accessories' => [],
		];
	}

	private function get_attributes_terms() {
		$terms     = [];
		$relations = $this->term_attribute_relation();
		if ( ! empty( $relations[ $this->type ] ) ) {
			foreach ( $relations[ $this->type ] as $key => $item ) {
				$attributes     = get_terms(
					[
						'taxonomy'   => $item,
						'hide_empty' => false,
					]
				);
				$terms[ $item ] = $attributes;
			}
		}

		return $terms;
	}

	private function get_brands() {
		return get_terms(
			[
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'parent'     => $this->term_id,
			]
		);
	}

	public function get_filters( array $names, array $params ) {
		$filters['brands']     = $this->get_brands();
		$filters['individual'] = $this->get_individual_terms();

		$filters = array_merge( $filters, $this->get_attributes_terms() );
		$result  = [];
		foreach ( $params as $item ) {
			if ( ! empty( $filters[ $item ] ) ) {
				$result[ $item ] = $filters[ $item ];
			}
		}

		include get_template_directory() . '/inc/filters/partials/template-filter.php';
	}

	/**
	 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
	 * placed under a 'children' member of their parent term.
	 *
	 * @param Array   $cats     taxonomy term objects to sort
	 * @param Array   $into     result array to put them in
	 * @param integer $parentId the current parent ID to put them in
	 */
	private function sort_terms_hierarchically( array &$cats, array &$into, $parentId = 0 ) {
		foreach ( $cats as $i => $cat ) {
			if ( $cat->parent === $parentId ) {
				$into[ $cat->term_id ] = $cat;
				unset( $cats[ $i ] );
			}
		}

		foreach ( $into as $topCat ) {
			$topCat->children = [];
			$this->sort_terms_hierarchically( $cats, $topCat->children, $topCat->term_id );
		}

	}

	private function get_individual_terms() {
		$taxonomies = [ 'mechanism', 'body-material', 'dial-color', 'strap-type', 'complex-functions' ];
		$result     = [];
		foreach ( $taxonomies as $product_tax ) {
			$sorted        = [];
			$current_terms = get_terms(
				[
					'taxonomy'   => $product_tax,
					'hide_empty' => false,
				]
			);

			$this->sort_terms_hierarchically( $current_terms, $sorted );

			$result[ $product_tax ] = $sorted;
		}

		return $result;
	}

}