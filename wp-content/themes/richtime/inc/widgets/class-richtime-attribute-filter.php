<?php

if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	return;
}

class Richtime_Attribute_Widget extends WC_Widget_Layered_Nav {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Init settings after post types are registered.
	 */
	public function init_settings() {
		$attribute_array      = [];
		$std_attribute        = '';
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $tax ) {
				if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
					$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
				}
			}
			$std_attribute = current( $attribute_array );
		}

		$this->settings = [
			'title'        => [
				'type'  => 'text',
				'std'   => __( 'Filter by', 'woocommerce' ),
				'label' => __( 'Title', 'woocommerce' ),
			],
			'attribute'    => [
				'type'    => 'select',
				'std'     => $std_attribute,
				'label'   => __( 'Attribute', 'woocommerce' ),
				'options' => $attribute_array,
			],
			'display_type' => [
				'type'    => 'select',
				'std'     => 'list',
				'label'   => __( 'Display type', 'woocommerce' ),
				'options' => [
					'list'     => __( 'List', 'woocommerce' ),
					'dropdown' => __( 'Dropdown', 'woocommerce' ),
					'checkbox' => __( 'Checkbox', 'woocommerce' ),
				],
			],
			'query_type'   => [
				'type'    => 'select',
				'std'     => 'and',
				'label'   => __( 'Query type', 'woocommerce' ),
				'options' => [
					'and' => __( 'AND', 'woocommerce' ),
					'or'  => __( 'OR', 'woocommerce' ),
				],
			],
		];
	}

	public function widget( $args, $instance ) {
		if ( ! is_shop() && ! is_product_taxonomy() ) {
			return;
		}

		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
		$taxonomy           = $this->get_instance_taxonomy( $instance );
		$query_type         = $this->get_instance_query_type( $instance );
		$display_type       = $this->get_instance_display_type( $instance );

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		$terms = get_terms( $taxonomy, [ 'hide_empty' => '1' ] );

		if ( 0 === count( $terms ) ) {
			return;
		}

		ob_start();

		$this->widget_start( $args, $instance );

		if ( 'dropdown' === $display_type ) {
			wp_enqueue_script( 'selectWoo' );
			wp_enqueue_style( 'select2' );
			$found = $this->layered_nav_dropdown( $terms, $taxonomy, $query_type );
		} elseif ( 'checkbox' ) {
			$found = $this->layered_nav_checkbox( $terms, $taxonomy, $query_type );
		} else {
			$found = $this->layered_nav_list( $terms, $taxonomy, $query_type );
		}

		$this->widget_end( $args );

		// Force found when option is selected - do not force found on taxonomy attributes.
		if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
			$found = true;
		}

		if ( ! $found ) {
			ob_end_clean();
		} else {
			echo ob_get_clean(); // @codingStandardsIgnoreLine
		}
	}

	public function layered_nav_checkbox( array $terms, string $taxonomy, string $query_type ) {

	}

}