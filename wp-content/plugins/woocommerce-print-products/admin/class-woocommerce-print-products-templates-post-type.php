<?php
/**
 * The attribute group post type class
 *
 * @link       http://woocommerce.db-dzine.de
 * @since      1.0.0
 *
 * @package    WooCommerce_Print_Products_Templates_Post_Type
 */

class WooCommerce_Print_Products_Templates_Post_Type  extends WooCommerce_Print_Products {

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

		$this->register_print_product_template();
	}

	public function register_print_product_template()
	{
		$labels = array(
			'name'                => __( 'Print Product Templates', 'woocommerce-group-attributes' ),
			'singular_name'       => __( 'Print Product Template', 'woocommerce-group-attributes' ),
			'add_new'             => _x( 'Add New Print Product Template', 'woocommerce-group-attributes', 'woocommerce-group-attributes' ),
			'add_new_item'        => __( 'Add New Print Product Template', 'woocommerce-group-attributes' ),
			'edit_item'           => __( 'Edit Print Product Template', 'woocommerce-group-attributes' ),
			'new_item'            => __( 'New Print Product Template', 'woocommerce-group-attributes' ),
			'view_item'           => __( 'View Print Product Template', 'woocommerce-group-attributes' ),
			'search_items'        => __( 'Search Print Product Templates', 'woocommerce-group-attributes' ),
			'not_found'           => __( 'No Print Product Templates found', 'woocommerce-group-attributes' ),
			'not_found_in_trash'  => __( 'No Print Product Templates found in Trash', 'woocommerce-group-attributes' ),
			'parent_item_colon'   => __( 'Parent Print Product Template:', 'woocommerce-group-attributes' ),
			'menu_name'           => __( 'Print Product Templates', 'woocommerce-group-attributes' ),
		);

		$args = array(
	      'public' => false,
	      'labels' => $labels,
	      'show_ui' => true,
	      'supports' => array('title'),
	      'show_in_menu' => 'edit.php?post_type=product',
	      'supports' => array('title', 'description'),
	      'hierarchical' => false,	      
	    );
		// if($this->get_option('enableAttributeGroupCategories')) {
		// 	$args['taxonomies'] = 'print_product_template_categories';
		// }

	    register_post_type( 'print_product_template', $args );
	}

	public function columns_head($columns){
		$output = array();

		$columns['menu_order'] = 'Order';

		foreach($columns as $column => $name){

			$output[$column] = $name;

			if($column === 'title'){
				$output['date'] = __('Date','woocommerce-group-attributes');
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
     * [show_print_product_template_toolbar description]
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function show_print_product_template_toolbar()
    {
		add_thickbox(); 

		$print_product_templates = get_posts(array(
			'post_type' => 'print_product_template',
			'post_status' => 'publish',
			'posts_per_page' => -1
		));

		?>
		
		<p class="toolbar">
			
			<select id="woocommerce_print_product_templates" name="woocommerce_print_product_templates" class="woocommerce_print_product_templates" style="float: right;margin: 0 0 0 6px;">
				<option value=""><?php _e('Print Product Templates','woocommerce-group-attributes'); ?></option>
				<?php 
				foreach ($print_product_templates as $print_product_template) {
					echo '<option value="' . $print_product_template->ID . '">' . $print_product_template->post_title . '</option>';
				}
				?>
			</select>
			
			<a href="<?php echo admin_url('edit.php?post_type=print_product_template' ); ?>" class="button" onclick="return confirm('<?php _e('Are you sure you want to navigate away.','woocommerce-group-attributes'); ?>');"><?php _e('Manage Print Product Templates','woocommerce-group-attributes'); ?></a>
		</p>
		<?php
    }

		
    // function add_attribute_categories_menu() { 

    //     add_submenu_page(
    //         'edit.php?post_type=product', 
    //         __('Print Product Template Categories', 'wordpress-gdpr'), 
    //         __('Print Product Template Categories', 'wordpress-gdpr'), 
    //         'manage_options', 
    //         'edit-tags.php?taxonomy=print_product_template_categories&post_type=print_product_template'
    //     ); 
    // } 

}