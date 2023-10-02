<?php
/**
 * The attribute group post type class
 *
 * @link       http://woocommerce.welaunch.io
 * @since      1.0.0
 *
 * @package    WooCommerce_Print_Products_Templates_Post_Type
 */

class WooCommerce_Print_Products_Templates extends WooCommerce_Print_Products {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function init()
	{
		global $woocommerce_print_products_options;

		$this->options = $woocommerce_print_products_options;

		if(!$this->get_option('enableTemplates')) {
			return false;
		}

		$this->register_print_product_template();
	}

	public function register_print_product_template()
	{
		$labels = array(
			'name'                => __( 'Print Templates', 'woocommerce-print-products' ),
			'singular_name'       => __( 'Print Template', 'woocommerce-print-products' ),
			'add_new'             => _x( 'Add New Print Template', 'woocommerce-print-products', 'woocommerce-print-products' ),
			'add_new_item'        => __( 'Add New Print Template', 'woocommerce-print-products' ),
			'edit_item'           => __( 'Edit Print Template', 'woocommerce-print-products' ),
			'new_item'            => __( 'New Print Template', 'woocommerce-print-products' ),
			'view_item'           => __( 'View Print Template', 'woocommerce-print-products' ),
			'search_items'        => __( 'Search Print Templates', 'woocommerce-print-products' ),
			'not_found'           => __( 'No Print Templates found', 'woocommerce-print-products' ),
			'not_found_in_trash'  => __( 'No Print Templates found in Trash', 'woocommerce-print-products' ),
			'parent_item_colon'   => __( 'Parent Print Template:', 'woocommerce-print-products' ),
			'menu_name'           => __( 'Print Templates', 'woocommerce-print-products' ),
		);

		$args = array(
	      'public' => false,
	      'labels' => $labels,
	      'show_ui' => true,
	      'show_in_menu' => 'woocommerce',
	      'supports' => array('title', 'editor'),
	      'hierarchical' => false,
	    );

	    register_post_type( 'print_template', $args );
	}

	public function columns_head($columns){
		$output = array();

		$columns['menu_order'] = 'Order';

		foreach($columns as $column => $name){

			$output[$column] = $name;

			if($column === 'title'){
				$output['date'] = __('Date','woocommerce-print-products');
			}
		}
		return $output;
	}

	public function columns_content($column_name){
		global $post;

		if($column_name == 'menu_order'){
	      	$order = $post->menu_order;
     		echo $order;
		}

		if($column_name !== 'date'){
			return;
		}
		
		echo 'test';
	}
		
	/**
     * Add custom ticket metaboxes
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://www.welaunch.io
     * @param   [type]                       $post_type [description]
     * @param   [type]                       $post      [description]
     */
    public function add_custom_metaboxes($post_type, $post)
    {
        add_meta_box('woocommerce-print-products-templates-date', 'Dates', array($this, 'dates'), 'print_template', 'normal', 'high');
    }

    /**
     * Display Metabox Short Information
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://www.welaunch.io
     * @return  [type]                       [description]
     */
    public function dates()
    {
        global $post;

        wp_nonce_field(basename(__FILE__), 'woocommerce_print_products_meta_nonce');

        $prefix = 'woocommerce_print_products_';

        $from_date = get_post_meta($post->ID, $prefix . 'from_date', true);
        $until_date = get_post_meta($post->ID, $prefix . 'until_date', true);
       
        echo '<label for="' . $prefix . 'attributes">From (Y-m-d): <input name="' . $prefix . 'from_date" value="' . $from_date . '" type="date"></label>';
        echo '<label for="' . $prefix . 'attributes">Until (Y-m-d): <input name="' . $prefix . 'until_date" value="' . $until_date . '" type="date"></label>';
       	
        
    }

    /**
     * Save Custom Metaboxes
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://www.welaunch.io
     * @param   [type]                       $post_id [description]
     * @param   [type]                       $post    [description]
     * @return  [type]                                [description]
     */
    public function save_custom_metaboxes($post_id, $post)
    {
    	global $woocommerce_print_products_options;

    	if($post->post_type !== "print_template") {
    		return false;
    	}

        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post->ID)) {
            return $post->ID;
        }

        if (!isset($_POST['woocommerce_print_products_meta_nonce']) || !wp_verify_nonce($_POST['woocommerce_print_products_meta_nonce'], basename(__FILE__))) {
            return false;
        }

        $prefix = 'woocommerce_print_products_';

        update_post_meta($post->ID, $prefix . 'from_date', $_POST[$prefix . 'from_date']);
        update_post_meta($post->ID, $prefix . 'until_date', $_POST[$prefix . 'until_date']);

    }

}