<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://woocommerce-print-products.db-dzine.de
 * @since      1.0.0
 *
 * @package    WooCommerce_Print_Products
 * @subpackage WooCommerce_Print_Products/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WooCommerce_Print_Products
 * @subpackage WooCommerce_Print_Products/admin
 * @author     Daniel Barenkamp <contact@db-dzine.de>
 */
class WooCommerce_Print_Products_Admin extends WooCommerce_Print_Products {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) 
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->product_url = "";

        add_filter('manage_product_posts_columns', array($this, 'columns_head'));
        add_action('manage_product_posts_custom_column', array($this, 'columns_content'), 10, 1);

	}

    /**
     * Enqueue Admin Styles
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    http://www.welaunch.io
     * @return  boolean
     */
    public function enqueue_styles()
    {
        wp_enqueue_style('font-awesome-5', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css', array(), '5.12.1', 'all');
    }

	public function load_redux()
	{
        if(!is_admin() || !current_user_can('administrator') || (defined('DOING_AJAX') && DOING_AJAX && (isset($_POST['action']) && !$_POST['action'] == "woocommerce_print_products_options_ajax_save") )) {
            return false;
        }

        // Load the theme/plugin options
        if (file_exists(plugin_dir_path(dirname(__FILE__)).'admin/options-init.php')) {
            require_once plugin_dir_path(dirname(__FILE__)).'admin/options-init.php';
        }
        return true;
	}

    /**
     * Init
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    http://www.welaunch.io
     * @return  boolean
     */
    public function init()
    {
        global $woocommerce_print_products_options;

        if(!is_admin() || !current_user_can('administrator') || (defined('DOING_AJAX') && DOING_AJAX)){
            $woocommerce_print_products_options = get_option('woocommerce_print_products_options');
        }

        $this->options = $woocommerce_print_products_options;
    }

	public function print_product_shortcode($atts)
	{
		$args = shortcode_atts( array(
	        'id' => '',
	        'mode' => 'pdf',
	        'text' => '',
            'show_icon' => 'no',
	    ), $atts );

	    $id = $args['id'];
	    $mode = $args['mode'];
	    $text = $args['text'];
        $show_icon = $args['show_icon'];
        $icon = "";

	    if(isset($_GET['product_id']) && !empty($_GET['product_id'])) {
	    	$id = $_GET['product_id'];
	    }

	    if(empty($id)) {
	    	$url = get_permalink();
	    } else {
	    	$url = get_permalink($id);
	    }

        switch ($mode) {
            case 'word':

                $class = 'woocommerce-print-products-word-link';
                if($this->get_option('iconType') == "button") {
                    $class .= ' woocommerce-print-products-pdf-button btn theme-btn theme-button';
                }

                if(empty($text)) {
                    $text = $this->get_option('iconTypeButtonWordText');
                }

                if($show_icon == "yes") {
                    $icon = '<i class="fa fa-file-word ' . $this->get_option('iconSize') . '"></i>';
                }

                return '<a class="' . $class . '" href="' . $url . '?print-products=word' . '" target="_blank">' . $icon . $text . '</a>';
                break;
            case 'print':

                $class = 'woocommerce-print-products-print-link';
                if($this->get_option('iconType') == "button") {
                    $class .= ' woocommerce-print-products-pdf-button btn theme-btn theme-button';
                }

                if(empty($text)) {
                    $text = $this->get_option('iconTypeButtonPrintText');
                }

                if($show_icon == "yes") {
                    $icon = '<i class="fa fa-print ' . $this->get_option('iconSize') . '"></i>';
                }

                return '<a class="' . $class . '" href="#"
                onclick="print(); return false;" target="_blank">' . $icon . $text . '</a>
                <script>
                    function print() {
                        var w = window.open("' . $url . '?print-products=print");
                    }
                </script>';
                break;

            default:

                $class = 'woocommerce-print-products-pdf-link';
                if($this->get_option('iconType') == "button") {
                    $class .= ' woocommerce-print-products-pdf-button btn theme-btn theme-button';
                }

                if(empty($text)) {
                    $text = $this->get_option('iconTypeButtonPDFText');
                }

                if($show_icon == "yes") {
                    $icon = '<i class="fa fa-file-pdf ' . $this->get_option('iconSize') . '"></i>';
                }

                return '<a class="' . $class . '" href="' . $url . '?print-products=pdf' . '" target="_blank">' . $icon . $text . '</a>';
                break;
        }
    }

/**
     * Columns Head.
     *
     * @author Daniel Barenkamp
     *
     * @version 1.0.0
     *
     * @since   1.0.0
     * @link    http://www.welaunch.io
     *
     * @param string $columns Columnd
     *
     * @return string
     */
    public function columns_head($columns)
    {
    	if(!$this->get_option('enableBackend') || !$this->get_option('enableBackendList')) {
    		return $columns;
    	}

        $output = array();
        foreach ($columns as $column => $name) {
            $output[$column] = $name;

            if ($column === 'title') {
                $output['export'] = __('Export', 'woocommerce-print-products');
            }
        }

        return $output;
    }

    /**
     * Columns Content.
     *
     * @author Daniel Barenkamp
     *
     * @version 1.0.0
     *
     * @since   1.0.0
     * @link    http://www.welaunch.io
     *
     * @param string $column_name Column Name
     *
     * @return string
     */
    public function columns_content($column_name)
    {
    	if(!$this->get_option('enableBackend') || !$this->get_option('enableBackendList')) {
    		return $column_name;
    	}

        global $post;

        if ($column_name == 'export') {
            $actual_link = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

            // if( strpos($actual_link, '?') === FALSE ){ 
            //     $this->product_url = $actual_link . '?post=' . $post->ID . '&';
            // } else {
            //     $this->product_url = $actual_link . '&post=' . $post->ID . '&';
            // }
            $this->product_url = get_permalink($post->ID) . '?';

            echo '<div class="woocommerce-print-products link-wrapper">';

                echo $this->get_pdf_link();
                echo $this->get_word_link();
                echo $this->get_print_link();
            
            echo '</div>';
        }
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
    	if(!$this->get_option('enableBackendSingle')) {
    		return $column_name;
    	}

        add_meta_box('woocommerce-print-products', 'Export', array($this, 'show_metabox'), 'product', 'side', 'high');
    }

    /**
     * Display Metabox show_metabox
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://www.welaunch.io
     * @return  [type]                       [description]
     */
    public function show_metabox()
    {
        global $post;

        $actual_link = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

		if( strpos($actual_link, '?') === FALSE ){ 
			$this->product_url = $actual_link . '?';
		} else {
		 	$this->product_url = $actual_link . '&';
		}
    	echo '<div class="woocommerce-print-products link-wrapper">';

    		echo $this->get_pdf_link();
    		echo $this->get_word_link();
    		echo $this->get_print_link();
    	
    	echo '</div>';
    }

    private function get_pdf_link()
    {
    	if(!$this->get_option('enablePDF')) return FALSE;

		$class = ' woocommerce-print-products-pdf-button btn theme-btn theme-button';

    	return '<a class="' . $class . '" href="' . $this->product_url . 'print-products=pdf'.'" target="_blank"><i class="fa fa-file-pdf ' . $this->get_option('iconSize') . '"></i></a> ';
    }

    private function get_word_link()
    {
    	if(!$this->get_option('enableWord')) return FALSE;

		$class = ' woocommerce-print-products-pdf-button btn theme-btn theme-button';

    	return '<a class="' . $class . '" href="' . $this->product_url . 'print-products=word'.'" target="_blank"><i class="fa fa-file-word ' . $this->get_option('iconSize') . '"></i></a> ';
    }

    private function get_print_link()
    {
    	if(!$this->get_option('enablePrint')) return FALSE;

		$class = ' woocommerce-print-products-pdf-button btn theme-btn theme-button';

    	return '<a class="' . $class . '" href="#"
    	onclick="print(); return false;" target="_blank"><i class="fa fa-print ' . $this->get_option('iconSize') . '"></i></a> 
    	<script>
			function print() {
				var w = window.open("' . $this->product_url . 'print-products=print");
			}
    	</script>';
    }
}