<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://woocommerce-print-products.welaunch.io
 * @since      1.0.0
 *
 * @package    WooCommerce_Print_Products
 * @subpackage WooCommerce_Print_Products/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WooCommerce_Print_Products
 * @subpackage WooCommerce_Print_Products/public
 * @author     Daniel Barenkamp <support@welaunch.io>
 */
class WooCommerce_Print_Products_Public extends WooCommerce_Print_Products {

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
	 * options of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $options
	 */
	protected $options;

	/**
	 * Product URL
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $product_url
	 */
	private $product_url;

	/**
	 * Product
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $product
	 */
	private $product;

	/**
	 * Post
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $post
	 */
	private $post;

	/**
	 * Data
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      mixed    $data
	 */
	private $data;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) 
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->data = new stdClass;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() 
	{

		global $woocommerce_print_products_options;

		$this->options = $woocommerce_print_products_options;

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-print-products-public.css', array(), $this->version, 'all' );
		wp_enqueue_style('font-awesome', plugin_dir_url( __FILE__ ) . 'css/fontawesome-free-5.15.3-web/css/all.min.css', array(), '5.15.3', 'all');
		
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() 
	{

		global $woocommerce_print_products_options;

		$this->options = $woocommerce_print_products_options;

		wp_enqueue_script( $this->plugin_name. '-public', plugin_dir_url( __FILE__ ) . 'js/woocommerce-print-products-public.js', array( 'jquery' ), $this->version, true );

		$customJS = $this->get_option('customJS');
		if(empty($customJS)) {
			return false;
		}

		file_put_contents( dirname(__FILE__)  . '/js/woocommerce-print-products-custom.js', $customJS);

		wp_enqueue_script( $this->plugin_name. '-custom', plugin_dir_url( __FILE__ ) . 'js/woocommerce-print-products-custom.js', array( 'jquery' ), $this->version, true );
	}
	
	/**
	 * Inits the print products
	 *
	 * @since    1.0.0
	 */
    public function init()
    {

		global $woocommerce_print_products_options;

		$this->options = $woocommerce_print_products_options;

		if (!$this->get_option('enable')) {
			return false;
		}

		// Enable User check
		if($this->get_option('enableLimitAccess')) {
			$roles = $this->get_option('role');
			if(empty($roles)) {
				$roles[] = 'administrator';
			}

			$currentUserRole = $this->get_user_role();

			if(!in_array($currentUserRole, $roles)) {
				return FALSE;
			}
		}

		$actual_link = get_permalink();
		if( strpos($actual_link, '?') === FALSE ){ 
			$this->product_url = $actual_link . '?';
		} else {
		 	$this->product_url = $actual_link . '&';
		}

		$iconPosition = $this->get_option('iconPosition');
		$iconPositionPriority = $this->get_option('iconPositionPriority') ? $this->get_option('iconPositionPriority') : 90;
		add_action( $iconPosition, array($this, 'show_print_links'), $iconPositionPriority );
    }

    public function show_print_links()
    {
    	$apply = true;

		if (!$this->get_option('enableFrontend')) {
			return false;
		}

		$excludeProductCategories = $this->get_option('excludeProductCategories');
		if(!empty($excludeProductCategories)) 
		{
			if($this->excludeProductCategories()) {
				$apply = FALSE;
			}
		}

		$excludeProducts = $this->get_option('excludeProducts');
		if(!empty($excludeProducts)) 
		{
			if($this->excludeProducts()) {
				$apply = FALSE;
			}
		}

		if(!$apply) {
			return;
		} 

    	echo '<div class="woocommerce-print-products link-wrapper">';

    	if($this->get_option('enableTemplates')) {
    		$templates = get_posts(array(
				'post_type' => 'print_template',
				'post_status' => 'publish',
				'posts_per_page' => -1
			));
			if(!empty($templates)){

			?>		

				<div class="woocommerce-print-products-templates-wrapper">

					<label for="woocommerce_print_product_template"><?php echo __('Select Print Template', 'woocommerce-print-products') ?>
					<select id="woocommerce_print_product_template" name="woocommerce_print_product_template" class="woocommerce_print_product_template">
						<option value=""><?php _e('Default template','woocommerce-print-products'); ?></option>
						<?php 

						$currentDate = date('Y-m-d');
						// $currentDate = date('Y-m-d', strtotime($currentDate));

						foreach ($templates as $template) {
							$from_date = get_post_meta($template->ID, 'woocommerce_print_products_from_date', true);
							$until_date = get_post_meta($template->ID, 'woocommerce_print_products_until_date', true);

							if($from_date && $until_date) {

								if (($currentDate >= $from_date) && ($currentDate <= $until_date)){
								    echo '<option value="' . $template->ID . '">' . $template->post_title . '</option>';
								} else {
								    continue;
								}
							} else {
								echo '<option value="' . $template->ID . '">' . $template->post_title . '</option>';
							}					
						}
						?>
					</select></label>

				</div>
			<?php

			}
    	}


    	if($this->get_option('iconDisplay') == "horizontal") {
    		echo $this->get_pdf_link();
    		echo $this->get_word_link();
    		echo $this->get_print_link();
    	}
    	if($this->get_option('iconDisplay') == "vertical") {
    		echo '<ul class="fa-ul">';
    		
			  echo '<li>' . $this->get_pdf_link() . '</li>';
			  echo '<li>' . $this->get_word_link() . '</li>';
			  echo '<li>' . $this->get_print_link() . '</li>';
			echo '</ul>';
		}
    	
    	echo '</div>';
    }

    private function get_pdf_link()
    {
    	if(!$this->get_option('enablePDF')) return FALSE;

    	$class = 'woocommerce-print-products-pdf-link';
    	$txt = '';
		if($this->get_option('iconType') == "button") {
			$class .= ' woocommerce-print-products-pdf-button btn theme-btn theme-button';
			$txt = $this->get_option('iconTypeButtonPDFText');
		}

    	return '<a class="' . $class . '" href="' .$this->product_url. 'print-products=pdf' . '" target="_blank"><i class="fa fa-file-pdf ' . $this->get_option('iconSize') . '"></i>' . $txt . '</a>';
    }

    private function get_word_link()
    {
    	if(!$this->get_option('enableWord')) return FALSE;

    	$class = 'woocommerce-print-products-word-link';
    	$txt = '';
		if($this->get_option('iconType') == "button") {
			$class .= ' woocommerce-print-products-pdf-button btn theme-btn theme-button';
			$txt = $this->get_option('iconTypeButtonWordText');
		}

    	return '<a class="' . $class . '" href="' .$this->product_url. 'print-products=word' . '" target="_blank"><i class="fa fa-file-word ' . $this->get_option('iconSize') . '"></i>' . $txt . '</a>';
    }

    private function get_print_link()
    {
    	if(!$this->get_option('enablePrint')) return FALSE;

    	$class = 'woocommerce-print-products-print-link';
    	$txt = '';
		if($this->get_option('iconType') == "button") {
			$class .= ' woocommerce-print-products-pdf-button btn theme-btn theme-button';
			$txt = $this->get_option('iconTypeButtonPrintText');
		}

    	return '<a class="' . $class . '" href="#"
    	onclick="print(); return false;" target="_blank"><i class="fa fa-print ' . $this->get_option('iconSize') . '"></i>' . $txt . '</a>
    	<script>
			function print() {
				var w = window.open("' .$this->product_url. 'print-products=print");
			}
    	</script>';
    }

    public function watch()
    {
    	$this->setup_data();

		if($_GET['print-products'] == "pdf")
		{
			$this->init_pdf();
		}
		if($_GET['print-products'] == "word")
		{
			$this->init_word();
		}
		if($_GET['print-products'] == "print")
		{
			$this->init_print();
		}
	}

	public function setup_data()
	{
    	global $post, $woocommerce, $wpdb;

    	$this->woocommerce_version = $woocommerce->version;

    	// default Variables
		$this->data->blog_name = get_bloginfo('name');
		$this->data->blog_description  = get_bloginfo('description');

		if(!$post && isset($_GET['post']) && !empty($_GET['post'])) {
			$post = get_post($_GET['post']);
		}

		if(!$post) {
			wp_die('Post not found');
		} 

		if(isset($_GET['variation']) && !$this->get_option('enableVariations')) {
			unset($_GET['variation']);
		}

		$parentPostId = $post->ID;
		if(isset($_GET['variation']) && !empty($_GET['variation'])) {
			$post->ID = (int) $_GET['variation'];
		}

		$this->data->ID = $post->ID;

		$product = wc_get_product( $this->data->ID );

		if(!$product) {
			wp_die('Not a product');
		}

		$this->product = $product;
		$this->post = $post;

		// Description
		if(class_exists('WPBMap')) {
			WPBMap::addAllMappedShortcodes();
		}
		
	    if( !defined('ONLY_ONCE_ap3_divi_do_shortcodes') && function_exists('et_builder_init_global_settings') && function_exists('et_builder_add_main_elements') ) {
	        define('ONLY_ONCE_ap3_divi_do_shortcodes', true);
	        // et_builder_init_global_settings();
	        et_builder_add_main_elements();

	    }


		if($product->is_type('variation')) {
			$parentProduct = wc_get_product($product->get_parent_id());
		}

		// product variables
		$this->data->title = apply_filters('woocommerce_print_products_title', $product->get_name());

		$this->data->link = get_permalink( $this->data->ID );
		if($this->get_option('showTitleLink')) {
			$this->data->title = '<a class="product-title-link" href="' . $this->data->link . '">' . $this->data->title .'</a>';
		}

		if($product->is_type('variation')) {
			$variationDescription = $product->get_description();
			if(!empty($variationDescription)) {
				$this->data->short_description = apply_filters('woocommerce_print_products_short_description', do_shortcode($variationDescription ) );
			} else {
				$this->data->short_description = apply_filters('woocommerce_print_products_short_description', do_shortcode($parentProduct->get_short_description() ) );	
			}
			
		} else {
			$this->data->short_description = apply_filters('woocommerce_print_products_short_description', do_shortcode($product->get_short_description() ) );	
		}
		

		if($this->get_option('showShortDescriptionStripImages')) {
			$this->post->post_excerpt = preg_replace("/<img[^>]+\>/i", "", $this->post->post_excerpt); 	
		}

		$price = $this->product->get_price_html();

		$price = htmlspecialchars_decode($price);
	    $price = str_replace(array('&#8381;'), 'RUB', $price);
		
		$this->data->stock_status = strip_tags( wc_get_stock_html( $this->product ) );
		if(empty($this->data->stock_status)) {
			$this->data->stock_status = __( 'N/A', 'woocommerce-print-products' );
		}

		if($this->get_option('showPriceTiered')) {

			$tieredPricing = get_post_meta($this->data->ID, '_fixed_price_rules', true);
			if(empty($tieredPricing) && $product->is_type('variation')) {
				$tieredPricing = get_post_meta($product->get_parent_id(), '_fixed_price_rules', true);
			}

			if(!empty($tieredPricing)) {
				$tieredPricingSettings = get_option('tiered_pricing_table_settings');
				$tieredPricinghead_quantity_text = get_option('tier_pricing_table_head_quantity_text');
				$tieredPricinghead_price_text = get_option('tier_pricing_table_head_price_text');
				$tieredPricinglowest_prefix = get_option('tier_pricing_table_lowest_prefix');
				
				$price = $tieredPricinglowest_prefix . ' ' . wc_price( $this->product->get_price() );
				
				$tieredPricingHTML = 
				'<table class="tiered-pricing-table">
					<thead>
						<tr class="tiered-pricing-table-thead-tr">
							<td>' . $tieredPricinghead_quantity_text . '</td>
							<td>' . $tieredPricinghead_price_text . '</td>
						</tr>
					</thead>';

				if(!isset($tieredPricing[1])) {
					$tieredPricingOne = array(1 => $this->product->get_price());
					$tieredPricing = $tieredPricingOne + $tieredPricing;
				}
				

				foreach ($tieredPricing as $tieredPricingQuantity => $tieredPricing) {
					$tieredPricingHTML .= 
					'<tr class="tiered-pricing-table-tbody-tr">
						<td>' . $tieredPricingQuantity . '+</td>
						<td>' . wc_price($tieredPricing) . '</td>
					</tr>';
				}
				$tieredPricingHTML .= '</table>';

				$tieredPricingHTML = apply_filters('woocommerce_print_products_tiered_pricing_html', $tieredPricingHTML, $this->data->ID);

				$price .= $tieredPricingHTML;
			}
		}

		$this->data->price = apply_filters('woocommerce_print_products_price', $price);


		$sku = $this->product->get_sku();
		$this->data->sku = !empty($sku) ? $sku : __( 'N/A', 'woocommerce-print-products' );


		// Description
		if($this->get_option('showDescriptionDoShortcodes')) {
			$this->data->description = apply_filters('woocommerce_print_products_description', do_shortcode( $this->post->post_content ));
		} else {
			$this->data->description = apply_filters('woocommerce_print_products_description', $this->post->post_content);
		}
		
		if($this->get_option('showDescriptionStripImages')) {
			$this->data->description = preg_replace("/<img[^>]+\>/i", "", $this->data->description); 	
		}

		if($product->is_type('variation') && !class_exists('WooCommerce_Single_Variations')) {
			$this->data->categories = wc_get_product_category_list($parentProduct->get_id(), ', ', '<b>' . _n( 'Category:', 'Categories:', 2, 'woocommerce-print-products' ) . '</b> ');
			$this->data->tags = wc_get_product_tag_list($parentProduct->get_id(), ', ', '<b>' . _n( 'Tag:', 'Tags:', 2, 'woocommerce-print-products' ) . '</b> ');	
		} else {
			$this->data->categories = wc_get_product_category_list($this->data->ID, ', ', '<b>' . _n( 'Category:', 'Categories:', 2, 'woocommerce-print-products' ) . '</b> ');
			$this->data->tags = wc_get_product_tag_list($this->data->ID, ', ', '<b>' . _n( 'Tag:', 'Tags:', 2, 'woocommerce-print-products' ) . '</b> ');	
		}

		$thumbnail = false;
		if ( has_post_thumbnail($this->post->ID)) { 
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($this->post->ID), 'full' ); 
		} elseif($product->is_type('variation') && has_post_thumbnail($parentProduct->get_id()) ) { 
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($parentProduct->get_id()), 'full' ); 
		}

		if(isset($thumbnail[0]) && !empty($thumbnail[0])) {

			if($this->get_option('useImageLocally') && !empty($thumbnail[0])) {
			    $uploads = wp_upload_dir();
				$thumbnail[0] = str_replace( $uploads['baseurl'], $uploads['basedir'], $thumbnail[0] );
			}

			$this->data->src = $thumbnail[0];

		} else {
			$this->data->src = plugin_dir_url( __FILE__ ) . 'img/placeholder.png';
		}


		// New custom meta data
		$customMetaKeys = $this->get_option('customMetaKeys');
		if(isset($customMetaKeys['enabled'])) {
			unset($customMetaKeys['enabled']['placebo']);
		}

		if(isset($customMetaKeys['enabled']) ) {
			$customMetaKeys = $customMetaKeys['enabled'];

			$temp = array();
		    foreach ($customMetaKeys as $key => $meta_key) {
		        if($this->get_option('showCustomMetaKeyACF_' . $meta_key) && function_exists('get_field')) {

		        	$field_name = get_post_meta( $parentPostId, $meta_key, true);
		        	$field_value = get_field($field_name, $parentPostId);
		        	
	        		if(empty($field_value)) {
	        			continue;
	        		}
		        	
		        	// ACF repeater
		        	if(is_array( $field_value) ) {

		        		foreach ($field_value as $field_value_key => $field_value_value) {

		        			if(!is_array($field_value_value)) {

								$temp[] = array (
									'key' => $meta_key,
									'before' => $this->get_option('showCustomMetaKeyText_' . $meta_key),
									'value' => $field_value_value,
								);
							} else {

			        			$field_value_value = array_values($field_value_value);
								$temp[] = array (
									'key' => $meta_key,
									'before' => isset($field_value_value[0]) ? $field_value_value[0] . ': ' : $meta_key,
									'value' => isset($field_value_value[1]) ? $field_value_value[1] : $meta_key,
								);
							}
		        		}

		        	} else {

						$temp[] = array (
							'key' => $meta_key,
							'before' => $this->get_option('showCustomMetaKeyText_' . $meta_key),
							'value' => get_field($field_name, $parentPostId),
						);
					}

	        	} else {
					$temp[] = array (
						'key' => $meta_key,
						'before' => $this->get_option('showCustomMetaKeyText_' . $meta_key),
						'value' => get_post_meta( $parentPostId, $meta_key, true),
					);
				}

		    }

		// old version fallback
		} else {

		    $sql = "SELECT DISTINCT meta_key
		                    FROM " . $wpdb->postmeta . "
		                    INNER JOIN  " . $wpdb->posts . " 
		                    ON post_id = ID
		                    WHERE post_type = 'product'
		                    ORDER BY meta_key ASC";

		    $meta_keys = (array) $wpdb->get_results( $sql, 'ARRAY_A' );
		    $meta_keys_to_exclude = array('_crosssell_ids', '_children', '_default_attributes', '_height', '_length', '_max_price_variation_id', '_max_regular_price_variation_id', '_max_sale_price_variation_id', '_max_variation_price', '_max_variation_regular_price', '_max_variation_sale_price', '_min_price_variation_id', '_min_regular_price_variation_id', '_min_sale_price_variation_id', '_min_variation_price', '_min_variation_regular_price', '_min_variation_sale_price', '_price', '_product_attributes', '_product_image_gallery', '_sku', '_regular_price', '_sale_price', '_sale_price_dates_from', '_sale_price_dates_to', '_sku', '_upsell_ids', '_thumbnail_id', '_weight', '_width');

		    $temp = array();
		    foreach ($meta_keys as $key => $meta_key) {
		        $meta_key = preg_replace('/[^\w-]/', '', $meta_key['meta_key']);

		        if(in_array($meta_key, $meta_keys_to_exclude) || (substr( $meta_key, 0, 7 ) === "_oembed") || (!$this->get_option('showCustomMetaKey_' . $meta_key)) ) {
		            continue;
		        }
	        
		        if($this->get_option('showCustomMetaKeyACF_' . $meta_key) && function_exists('get_field')) {

		        	if($product->is_type('variation') && !class_exists('WooCommerce_Single_Variations')) {
			        	$field_name = get_post_meta( $parentProduct->get_id(), $meta_key, true);
		        		$meta_value = get_field($field_name, $parentProduct->get_id());
	        		} else {
			        	$field_name = get_post_meta( $this->data->ID, $meta_key, true);
		        		$meta_value = get_field($field_name, $post->ID);
	        		}

	        		if(empty($meta_value)) {
	        			continue;
	        		}

		        	// ACF repeater
		        	if(is_array( $meta_value) ) {

		        		foreach ($meta_value as $field_value_key => $field_value_value) {

		        			$field_value_value = array_values($field_value_value);
		        			
							$temp[] = array (
								'key' => $meta_key,
								'before' => isset($field_value_value[0]) ? $field_value_value[0] : $meta_key,
								'value' => isset($field_value_value[1]) ? $field_value_value[1] : $meta_key,
							);
		        		}

		        	} else {

						$temp[] = array (
							'key' => $meta_key,
							'before' => $this->get_option('showCustomMetaKeyText_' . $meta_key),
							'value' => get_field($field_name, $post->ID),
						);
					}
					continue;

		        } 

	        	if($product->is_type('variation') && !class_exists('WooCommerce_Single_Variations')) {
		        	$meta_value = get_post_meta( $parentProduct->get_id(), $meta_key, true);
	        	} else {
	        		$meta_value = get_post_meta( $this->data->ID, $meta_key, true);
	        	}

		        if(empty($meta_value)) {
		        	continue;
		        }

	 			if(is_array($meta_value)) {
	 				$meta_value = implode(', ', $meta_value);
	 			}

		        $temp[] = array (
		        	'key' => $meta_key,
		        	'before' => $this->get_option('showCustomMetaKeyText_' . $meta_key),
		        	'value' => $meta_value,
	        	);
		    }
	    }

	    $this->data->meta_keys = apply_filters('woocommerce_print_products_meta_keys', $temp);

	    if($this->get_option('enableTemplates') && isset($_GET['template']) && !empty($_GET['template'])) {
	    	add_filter('woocommerce_print_products_product_html', array($this, 'add_template_data'), 20, 1);
	    }

		return TRUE;
	}

    public function init_pdf()
    {
    	if(!class_exists('\Mpdf\Mpdf')) return FALSE;
    	if(!$this->get_option('enablePDF')) return FALSE;

    	require_once(plugin_dir_path( dirname( __FILE__ ) ) . 'fonts/customFonts.php');

    	$headerTopMargin = $this->get_option('headerTopMargin');
    	$footerTopMargin = $this->get_option('footerTopMargin');
    	$format = $this->get_option('format');

		$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
		$fontData = $defaultFontConfig['fontdata'];

    	try {
			$mpdfConfig = array(
				'mode' => 'utf-8', 
				'format' => $format,    // format - A4, for example, default ''
				'default_font_size' => 0,     // font size - default 0
				'default_font' => '',    // default font family
				'margin_left' => 0,    	// 15 margin_left
				'margin_right' => 0,    	// 15 margin right
				'margin_top' => $headerTopMargin,     // 16 margin top
				'margin_bottom' => $footerTopMargin,    	// margin bottom
				'margin_header' => 0,     // 9 margin header
				'margin_footer' => 0,     // 9 margin footer
				'orientation' => 'P',  	// L - landscape, P - portrait
				'tempDir' => dirname( __FILE__ ) . '/../cache/',
				'fontDir' => array(
					plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/mpdf/mpdf/ttfonts/',
					plugin_dir_path( dirname( __FILE__ ) ) . 'fonts/',
				),
			    'fontdata' => array_merge($fontData, $customFonts),
			    'curlFollowLocation' => $this->get_option('curlFollowLocation'),
			    'curlAllowUnsafeSslRequests' => $this->get_option('curlAllowUnsafeSslRequests'),
			    'curlUserAgent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:110.0) Gecko/20100101 Firefox/110.0',
			);

			$mpdfConfig = apply_filters('woocommerce_print_products_mpdf_config', $mpdfConfig);

			$mpdf = new \Mpdf\Mpdf($mpdfConfig);	

			if($this->get_option('debugMPDF')) {
				$mpdf->debug = true;
				$mpdf->debugfonts = true;
				$mpdf->showImageErrors = true;
			}

			if($this->get_option('watermarkEnable')) {

				$watermarkType = $this->get_option('watermarkType');
				$watermarkTransparency = $this->get_option('watermarkTransparency');
				
				if($watermarkType == "text") {
					$watermarkText = $this->get_option('watermarkText');
					if(!empty($watermarkText)) {
						$mpdf->SetWatermarkText($watermarkText, $watermarkTransparency);
						$mpdf->showWatermarkText = true;
					}
				} elseif($watermarkType == "image") {
					$watermarkImage = $this->get_option('watermarkImage');
					if(!empty($watermarkImage['url'])) {
						$mpdf->SetWatermarkImage($watermarkImage['url'], $watermarkTransparency);
						$mpdf->showWatermarkImage = true;
					}
				}
			}

			$css = $this->build_CSS();

			$header = "";
			if($this->get_option('enableHeader')) {
				$header = $this->get_header();
				$mpdf->SetHTMLHeader($header);
			}

			$footer = "";
			if($this->get_option('enableFooter')) {
				$footer = $this->get_footer();
				$mpdf->SetHTMLFooter($footer);
			}

			$layout = $this->get_option('layout');
			$order = $this->get_option('informationOrder');
			$enabledBlocks = $order['enabled'];
			unset($enabledBlocks['placebo']);

			if($layout == 1) {
				$html = apply_filters('woocommerce_print_products_product_html', $this->get_first_layout(), $this->data->ID, $this->data); 
			} elseif($layout == 2) {
				$html = apply_filters('woocommerce_print_products_product_html', $this->get_second_layout(), $this->data->ID, $this->data); 
			} elseif($layout == 3) {
				$html = apply_filters('woocommerce_print_products_product_html', $this->get_third_layout(), $this->data->ID, $this->data); 
			} elseif($layout == 4) {
				$html = apply_filters('woocommerce_print_products_product_html', $this->get_fourth_layout(), $this->data->ID, $this->data); 
			} elseif($layout == 5) {
				$html = apply_filters('woocommerce_print_products_product_html', $this->get_fifth_layout(), $this->data->ID, $this->data); 
			} elseif($layout == 6) {
				$html = apply_filters('woocommerce_print_products_product_html', $this->get_sixth_layout(), $this->data->ID, $this->data); 
			} elseif($layout == 7) {
				$html = apply_filters('woocommerce_print_products_product_html', $this->get_seventh_layout(), $this->data->ID, $this->data); 
			} elseif($layout == 8) {
				$html = apply_filters('woocommerce_print_products_product_html', $this->get_eigth_layout(), $this->data->ID, $this->data); 
			} elseif($layout == 9) {
				$html = apply_filters('woocommerce_print_products_product_html', $this->get_ninth_layout(), $this->data->ID, $this->data); 
			}

			$skipNextPagebreak = false;
			foreach ($enabledBlocks as $key => $value) {
				$temp = explode('-', $key);
				$block = $temp[0];

				if($block == "pagebreak" && $skipNextPagebreak == true){
					$skipNextPagebreak = false;
					continue;
				} else {
					$skipNextPagebreak = false;
				}		

				if($block == "custom") {
					$return = apply_filters('woocommerce_print_products_custom_block_' . $temp[1], '', $this->data->ID, $this->data); 
				} else {
					$func = 'get_' . $block;
					$return = call_user_func(array($this, $func));
				}


				if($return === false){
					$skipNextPagebreak = true;
				} else {
					$html .= $return;
				}

			}

			if($this->get_option('customFileName')) {
				$customFileNamePattern = $this->get_option('customFileNamePattern');
				if(empty($customFileNamePattern)) {
					$customFileNamePattern = $this->data->title;
				}

				$customFileNamePattern = str_replace( 
					array( '{{sku}}', '{{title}}', '{{day}}', '{{month}}', '{{year}}'),
					array( $this->data->sku, $this->data->title, date('d'), date('m'), date('Y') ),
					$customFileNamePattern
				);

				$filename = $this->escape_filename($customFileNamePattern, false);
			} elseif($this->get_option('useSKUasFileName') && !empty($this->data->sku)) {
				$filename = $this->escape_filename($this->data->sku);
			} else {
				$filename = $this->escape_filename($this->data->title);
			}


			$extraTextBefore = $this->get_option('extraTextBefore');
			if(!empty($extraTextBefore)) {
				$html = '<div class="frame">' . wpautop( do_shortcode($extraTextBefore) ) . '</div>' . $html;
			}

			$extraTextAfter = $this->get_option('extraTextAfter');
			if(!empty($extraTextAfter)) {
				$html = $html . '<div class="frame">' . wpautop( do_shortcode($extraTextAfter) ) . '</div>';
			}

			$html = apply_filters('woocommerce_print_products_product_final_html', $html, $this->data->ID, $this->data); 
			
			if($this->get_option('debugMode')) {
				echo $header;
				echo $css.$html;
				echo $footer;
				die();
			}


// appendPDFs



			$mpdf->useAdobeCJK = true;
			$mpdf->autoScriptToLang = true;
			$mpdf->autoLangToFont = true;
			$mpdf->WriteHTML($css.$html);

			if($this->get_option('appendPDFs')) {

				$mpdf->SetHTMLHeader('');
				

				$appendPDFsGlobal = $this->get_option('appendPDFsGlobal');
				if(!empty($appendPDFsGlobal)) {
					$appendPDFsGlobal = array_filter( explode(',', $appendPDFsGlobal) );
					if(!empty($appendPDFsGlobal)) {
						foreach($appendPDFsGlobal as $appendPDFGlobal) {
							$appendPDFGlobalPath = ABSPATH . $appendPDFGlobal;
							if(!file_exists($appendPDFGlobalPath)) {
								continue;
							}
							
							$pagecount = $mpdf->SetSourceFile($appendPDFGlobalPath);
							for ($i=1; $i<=($pagecount); $i++) {
							    $mpdf->AddPage();
							    $mpdf->SetHTMLFooter('');
							    $import_page = $mpdf->ImportPage($i);
							    $mpdf->UseTemplate($import_page);
							}

						}
					}
				}

				$appendPDFsMetaKey = $this->get_option('appendPDFsMetaKey');
				if(!empty($appendPDFsMetaKey)) {
					$appendPDFsMeta = get_post_meta($this->data->ID, $appendPDFsMetaKey, true);
					if(!empty($appendPDFsMeta)) {
						$appendPDFsMeta = array_filter( explode(',', $appendPDFsMeta) );
						if(!empty($appendPDFsMeta)) {
							foreach($appendPDFsMeta as $appendPDFMeta) {
								$appendPDFMetaPath = ABSPATH . $appendPDFMeta;
								if(!file_exists($appendPDFMetaPath)) {
									continue;
								}
								
								$pagecount = $mpdf->SetSourceFile($appendPDFMetaPath);
								for ($i=1; $i<=($pagecount); $i++) {
								    $mpdf->AddPage();
								    $import_page = $mpdf->ImportPage($i);
								    $mpdf->UseTemplate($import_page);
								}

							}
						}
					}
				}
			}

			$mpdf->Output($filename. '.pdf', 'I');

    	} catch (Exception $e) {
    		echo $e->getMessage();
    	}

		exit;
    }

    public function init_word()
    {
		global $post;

		if(!$this->get_option('enableWord')) return FALSE;

		$css = $this->build_CSS();

		if($this->get_option('enableHeader'))
		{
			$header = $this->get_header();
		}

		if($this->get_option('enableFooter'))
		{
			$footer = $this->get_footer();
		}

		$layout = $this->get_option('layout');
		$order = $this->get_option('informationOrder');
		$enabledBlocks = $order['enabled'];
		unset($enabledBlocks['placebo']);
		
		if($layout == 1) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_first_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 2) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_second_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 3) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_third_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 4) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_fourth_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 5) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_fifth_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 6) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_sixth_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 7) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_seventh_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 8) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_eigth_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 9) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_ninth_layout(), $this->data->ID, $this->data); 
		}

		foreach ($enabledBlocks as $key => $value) {
			$temp = explode('-', $key);
			$block = $temp[0];

			$func = 'get_' .$block;
			$html .= call_user_func(array($this, $func));

		}

		$extraTextBefore = $this->get_option('extraTextBefore');
		if(!empty($extraTextBefore)) {
			$html = '<div class="frame">' . wpautop( do_shortcode($extraTextBefore) ) . '</div>' . $html;
		}

		$extraTextAfter = $this->get_option('extraTextAfter');
		if(!empty($extraTextAfter)) {
			$html = $html . '<div class="frame">' . wpautop( do_shortcode($extraTextAfter) ) . '</div>';
		}

		$html = apply_filters('woocommerce_print_products_product_final_html', $html, $this->data->ID, $this->data); 

		if($this->get_option('customFileName')) {
			$customFileNamePattern = $this->get_option('customFileNamePattern');
			if(empty($customFileNamePattern)) {
				$customFileNamePattern = $this->data->title;
			}

			$customFileNamePattern = str_replace( 
				array( '{{sku}}', '{{title}}', '{{day}}', '{{month}}', '{{year}}'),
				array( $this->data->sku, $this->data->title, date('d'), date('m'), date('Y') ),
				$customFileNamePattern
			);

			$filename = $this->escape_filename($customFileNamePattern, false);
		} elseif($this->get_option('useSKUasFileName') && !empty($this->data->sku)) {
			$filename = $this->escape_filename($this->data->sku);
		} else {
			$filename = $this->escape_filename($this->data->title);
		}

		header("Content-type: application/vnd.ms-word");
		header("Content-Disposition: attachment;Filename=" . $filename . ".doc");

		echo "<html>";
		echo $css;
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
		echo "<body>";
		echo $header;
		echo $html;
		echo $footer;
		echo "</body>";
		echo "</html>";
    }

    public function init_print()
    {
    	if(!$this->get_option('enablePrint')) return FALSE;

		$css = $this->build_CSS();

		$header = "";
		if($this->get_option('enableHeader'))
		{
			$header = $this->get_header();
		}

		$footer = "";
		if($this->get_option('enableFooter'))
		{
			$footer = $this->get_footer();
		}

		$layout = $this->get_option('layout');
		$order = $this->get_option('informationOrder');
		$enabledBlocks = $order['enabled'];
		unset($enabledBlocks['placebo']);
		
		if($layout == 1) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_first_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 2) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_second_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 3) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_third_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 4) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_fourth_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 5) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_fifth_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 6) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_sixth_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 7) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_seventh_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 8) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_eigth_layout(), $this->data->ID, $this->data); 
		} elseif($layout == 9) {
			$html = apply_filters('woocommerce_print_products_product_html', $this->get_ninth_layout(), $this->data->ID, $this->data); 
		}

		foreach ($enabledBlocks as $key => $value) {
			$temp = explode('-', $key);
			$block = $temp[0];

			$func = 'get_' .$block;
			$html .= call_user_func(array($this, $func));

		}
		
		$extraTextBefore = $this->get_option('extraTextBefore');
		if(!empty($extraTextBefore)) {
			$html = '<div class="frame">' . wpautop( do_shortcode($extraTextBefore) ) . '</div>' . $html;
		}

		$extraTextAfter = $this->get_option('extraTextAfter');
		if(!empty($extraTextAfter)) {
			$html = $html . '<div class="frame">' . wpautop( do_shortcode($extraTextAfter) ) . '</div>';
		}

		$html = apply_filters('woocommerce_print_products_product_final_html', $html, $this->data->ID, $this->data); 

		$pagebreak_css = '<style>
								@media print {
				    				.page-break {display:block; page-break-after: always;}
							}
							</style>';
		$print_js = '
				<script>
				var w = window;
				var d = document;

				var printAndClose = function () {
					if (w.document.readyState == "complete") {
						clearInterval(sched);
						setTimeout(function() {
							w.focus();
							w.print();
							w.close();
						},
						500);
					}
				};
      			var sched = setInterval(printAndClose, 200);
      			</script>';
		$css = $css.$pagebreak_css.$print_js;
		echo $css.$header.$html.$footer;
		exit();
    }

    public function build_CSS()
    {
    	$layout = $this->get_option('layout');
    	$backgroundColor = $this->get_option('backgroundColor');
    	$textAlign = $this->get_option('textAlign') ? $this->get_option('textAlign') : 'center';
    	$textColor = $this->get_option('textColor');
    	$linkColor = $this->get_option('linkColor');

    	// Font
    	$fontFamily = $this->get_option('fontFamily') ? $this->get_option('fontFamily') : 'dejavusans';
    	$fontSize = $this->get_option('fontSize') ? $this->get_option('fontSize') : '11';
    	$headingsFontFamily = $this->get_option('headingsFontFamily') ? $this->get_option('headingsFontFamily') : 'dejavusans';
    	$headingsFontSize = $this->get_option('headingsFontSize') ? $this->get_option('headingsFontSize') : '16';

    	$fontSize = intval($fontSize);
    	$headingsFontSize = intval($headingsFontSize);

    	$fontLineHeight =  $this->get_option('fontLineHeight') ? $this->get_option('fontLineHeight') : $fontSize + 6; 
    	$headingsLineHeight =  $this->get_option('headingsLineHeight') ? $this->get_option('headingsLineHeight') : $headingsFontSize + 6; 

    	$fontLineHeight = intval($fontLineHeight);
    	$headingsLineHeight = intval($headingsLineHeight);

    	$headerBackgroundColor = $this->get_option('headerBackgroundColor');
    	$headerTextColor = $this->get_option('headerTextColor');

    	$footerBackgroundColor = $this->get_option('footerBackgroundColor');
    	$footerTextColor = $this->get_option('footerTextColor');

    	$featuredImageBackgroundColor = $this->get_option('featuredImageBackgroundColor') ? $this->get_option('featuredImageBackgroundColor')  : '#EEEEEE';

		$css = '
		<head>
			<style media="all">';

		if(!empty($backgroundColor)) {
			$css .= 'body { background-color: ' . $backgroundColor . ';}';
		}
		if(!empty($textColor)) {
			$css .= 'body { color: ' . $textColor . ';}';
		}
		if(!empty($linkColor)) {
			$css .= 'a, a:hover { color: ' . $linkColor . ';}';
		}

		$css .= ' .title, .description, table, .custom-tabs, .product-info, div.description { text-align: ' . $textAlign . '; }';

		$css .= '

				body, table { font-family: ' . $fontFamily . ', sans-serif; font-size: ' . $fontSize . 'pt; line-height: ' . $fontLineHeight . 'pt; } 
				
				body, table { 
					margin: 0;
					padding: 0;
					width: 100%;
		 		}

				table {
					width: 100%;
				}

				.frame, .header, .footer, .after-header, .pre-footer {
					padding: 20px;
				}
				table {
					border-spacing: 0;
				}
				.gallery-images-table {
					padding: 0;
				}
				.product-info {
					padding: 20px;
				}

	 			.row {
		 			clear: both;
		 			float: none;
		 		}

				.two-cols, .three-cols, .four-cols {
					width: 100% !important;
				}
				.two-cols .col {
					width: 49.5%;
					float: left;
				}
				.three-cols .col {
					width: 33%;
					float: left;
				}
				.four-cols .col {
					width: 24%;
					float: left;
				}


				.tiered-pricing-table {
					padding: 0;
					margin-bottom: 20px;
					margin-top: 20px;
				}
				.tiered-pricing-table-thead-tr {
					background-color: #333333;
					color: #FFF;
					font-weight: bold;
				}
				.tiered-pricing-table-thead-tr td {
					color: #ffffff;
					padding: 5px;
					font-size: 95%;
				}

				.header-left,
				.footer-left {
					text-align: left;
				}

				.header-center,
				.footer-center {
					text-align: center;
				}

				.header-right,
				.footer-right {
					text-align: right;	
				}

				.frame .frame {
					padding: 0;
				}

				.layout-8 .attributes {
					padding: 0;
				}
				.layout-8 .product-info {
					padding-top: 0;
				}
				.layout-9 .hr {
					margin-top: 20px;
					border-bottom: 2px solid #eaeaea;
				}

				.layout-9 .image-one-container {
					margin-bottom: 20px;
				}

				.attributes p {
					margin: 0;
				}

				.tiered-pricing-table-tbody-tr td {
					padding: 5px;
					font-size: 95%;
					border-bottom: 1px solid #eaeaea;
				}
				.two-cols .col {
					width: 49.5%;
					float: left;
				}
				.two-cols .col-75 {
					width: 75%;
					float: left;
				}
				.two-cols .col-25 {
					width: 25%;
					float: left;
				}

				.layout-two .product-title {
					text-align: center;
				}
				.woocommerce-attribute-image-container {
					float: left;
				}

				.three-cols .col {
					width: 33%;
					float: left;
				}
				.three-cols .col-two {
					width: 66%;
					float: left;
				}
				.four-cols .col {
					width: 24%;
					float: left;
				}
				.product-info .hr {
				    color: #555555;
				    display: block;
				    height: 1px;
				    background-color: #555;
				    width: 50%;
				    margin: 20px auto;
				}
				.clear { float: none; clear: both;}
				p { margin-bottom: 10px; }
				.pagebreak { display: none; }
				table { width:100%; padding: 10px 25px; }
				h1,h2,h3,h4,h5,h6 { font-family: ' . $headingsFontFamily . ', sans-serif;}
				h1, .title { font-size: ' . $headingsFontSize . 'px; text-transform: uppercase; line-height: ' . $headingsLineHeight . 'px;}
				h2, .title { font-size: ' . $headingsFontSize . 'px; text-transform: uppercase; line-height: ' . $headingsLineHeight . 'px;}
				.attributes { width: 100%; }
				.attributes th { width:33%; text-align:left; padding-top:5px; padding-bottom: 5px;}
				.attributes td { width:66%; text-align:left; }
				.meta { font-size: 10pt; }
				.title { width: 100%; }
				.title td { padding-bottom: 10px; padding-top: 40px; }
				td.product-title { padding-bottom: 30px; }
				.featured-image.layout-2 { background-color: ' . $featuredImageBackgroundColor  . '; }
				.layout-5 .product-sku { font-size: 28px; line-height: 36px; padding-bottom: 10px; }
				.layout-5 .product-title { font-size: 28px; line-height: 36px; border-bottom: 1px solid #434346; border-top: 1px solid #434346; padding: 15px 0; font-weight: bold; margin-bottom: 0; }
				.layout-5 .product-short-description { font-size: 22px; line-height: 30px; padding: 15px 0; border-bottom: 1px solid #434346; }
				.layout-5 .product-description-container { padding: 15px 0; border-bottom: 1px solid #434346; }
				.layout-5 .product-short-description p { margin: 0; }
				.layout-5 .product-info-container { padding: 20px; width: 50%; }
				.layout-5 .image-column { width: 40%; }
				.layout-5 table { padding: 0; }
				.layout-5 .product-attributes { padding: 15px 0; }
				.layout-5 .product-info-container { padding: 20px; width: 50%; }
				.layout-5 .image-column { width: 40%; }
				.layout-5 h2.product-description-title {
					font-size: 14px;
					margin-top: 0;
					margin-bottom: 0;
				}
				.layout-5 .product-attributes { padding: 15px 0; }
				.layout-5 .product-short-description {
				    margin-bottom: 0;
				}
				.product-short-description h2 {
				    margin: 0;
				    font-weight: normal;
				}
				.layout-5 .product-info-container {
				    padding-top: 0;
				}

				.layout-6 table {
					padding: 0;
				}
	
				.layout-6 .title td {
					padding-top: 0;
					padding-bottom: 0;
				}

				.layout-7 table {
					padding: 0;
				}
	
				.layout-7 .title td {
					padding-top: 0;
					padding-bottom: 0;
				}

				.notes-container {
					margin-top: 30px;
				}

				.notes-line {
					margin-top: 30px;
				    display: block;
    				border-bottom: 1px solid #000;
				}
				.header {
					vertical-align: bottom; 
					font-size: 9pt; 
					background-color: ' . $headerBackgroundColor . '; 
					color: ' . $headerTextColor . ';
				}
				.after-header, .pre-footer {
					vertical-align: bottom; 
					font-size: 9pt;
				}
				.after-header td {
					text-align: center;
					padding-bottom: 40px;
				}
				.pre-footer td {
					text-align: center;
				}
				.footer {
					vertical-align: bottom; 
					font-size: 9pt; 
					background-color: ' . $footerBackgroundColor . '; 
					color: ' . $footerTextColor . ';
				}
				';

		if($layout == 3){
			$css .= ' .attributes-title, .attributes {
						padding: 0;
					}';
		}

		$customCSS = $this->get_option('customCSS');
		if(!empty($customCSS))
		{
			$css .= $customCSS;
		}

		$css .= '
			</style>

		</head>';

		return $css;
    }

    public function get_header()
    {
    	$headerLayout = $this->get_option('headerLayout');
    	$this->get_option('headerHeight') ? $headerHeight = $this->get_option('headerHeight') : $headerHeight = 'auto';
		$headerVAlign = $this->get_option('headerVAlign');

    	$topLeft = $this->get_option('headerTopLeft');
    	$topMiddle = $this->get_option('headerTopMiddle');
    	$topMiddle2 = $this->get_option('headerTopMiddle2');
    	$topRight = $this->get_option('headerTopRight');

    	$headerTextAfterHeader = $this->get_option('headerTextAfterHeader');

    	$header = "";

    	if($headerLayout == "oneCol")
    	{
			$header .= '
			<div class="header one-col">
				<div class="header-center header-block-one">' . $this->get_header_footer_type($topLeft, 'headerTopLeft') . '</div>
			</div>';
    	} elseif($headerLayout == "threeCols") {
			$header .= '
			<div class="header three-cols">
				<div  class="col header-left header-block-one">' . $this->get_header_footer_type($topLeft, 'headerTopLeft') . '</div>
				<div  class="col header-center header-block-two">' . $this->get_header_footer_type($topMiddle, 'headerTopMiddle') . '</div>
				<div  class="col header-right header-block-three">' . $this->get_header_footer_type($topRight, 'headerTopRight') . '</div>
			</div>';
		} elseif($headerLayout == "fourCols") {
			$header .= '
			<div class="header four-cols">
				<div  class="col header-left header-block-one">' . $this->get_header_footer_type($topLeft, 'headerTopLeft') . '</div>
				<div  class="col header-left header-block-two">' . $this->get_header_footer_type($topMiddle, 'headerTopMiddle') . '</div>
				<div  class="col header-left header-block-three">' . $this->get_header_footer_type($topMiddle2, 'headerTopRight') . '</div>
				<div  class="col header-left header-block-four">' . $this->get_header_footer_type($topRight, 'headerTopRight') . '</div>
			</div>';
		} else {
			$header .= '
			<div class="header two-cols">
				<div  class="col header-left header-block-one">' . $this->get_header_footer_type($topLeft, 'headerTopLeft') . '</div>
				<div  class="col header-right header-block-two">' . $this->get_header_footer_type($topRight, 'headerTopRight') . '</div>
			</div>';
		}

    	if(!empty($headerTextAfterHeader)) {
			$header .= '
			<div class="after-header" width="100%">
				<div class="after-header-text">' . wpautop( $headerTextAfterHeader ) . '</div>
			</div>';
		}

		$header = apply_filters('woocommerce_print_products_header_html', $header);

		return $header;
    }

    public function get_footer()
    {
    	$footerLayout = $this->get_option('footerLayout');
    	$this->get_option('footerHeight') ? $footerHeight = $this->get_option('footerHeight') : $footerHeight = 'auto';
		$footerVAlign = $this->get_option('footerVAlign');

    	$topLeft = $this->get_option('footerTopLeft');
    	$topRight = $this->get_option('footerTopRight');
    	$topMiddle = $this->get_option('footerTopMiddle');
    	$topMiddle2 = $this->get_option('footerTopMiddle2');

    	$foooterTextBeforeFooter = $this->get_option('foooterTextBeforeFooter');
    	
    	$footer = "";

    	if(!empty($foooterTextBeforeFooter)) {
    		$footer .= '
    		<div class="pre-footer" width="100%">
				<div class="pre-footer-text">' . wpautop( $foooterTextBeforeFooter ) . '</div>
			</div>';
    	}

    	if($footerLayout == "oneCol")
    	{
			$footer .= '
			<div class="footer one-col">
				<div class="footer-center footer-block-one">' . $this->get_header_footer_type($topLeft, 'footerTopLeft') . '</div>
			</div>';
    	} elseif($footerLayout == "threeCols") {
			$footer .= '
			<div class="footer three-cols">
				<div  class="col footer-left footer-block-one">' . $this->get_header_footer_type($topLeft, 'footerTopLeft') . '</div>
				<div  class="col footer-center footer-block-two">' . $this->get_header_footer_type($topMiddle, 'footerTopMiddle') . '</div>
				<div  class="col footer-right footer-block-three">' . $this->get_header_footer_type($topRight, 'footerTopRight') . '</div>
			</div>';
		} elseif($footerLayout == "fourCols") {
			$footer .= '
			<div class="footer four-cols">
				<div  class="col footer-left footer-block-one">' . $this->get_header_footer_type($topLeft, 'footerTopLeft') . '</div>
				<div  class="col footer-left footer-block-two">' . $this->get_header_footer_type($topMiddle, 'footerTopMiddle') . '</div>
				<div  class="col footer-left footer-block-three">' . $this->get_header_footer_type($topMiddle, 'footerTopMiddle2') . '</div>
				<div  class="col footer-left footer-block-four">' . $this->get_header_footer_type($topRight, 'footerTopRight') . '</div>
			</div>';
		} else {
			$footer .= '
			<div class="footer two-cols">
				<div  class="col footer-left footer-block-one">' . $this->get_header_footer_type($topLeft, 'footerTopLeft') . '</div>
				<div  class="col footer-right footer-block-two">' . $this->get_header_footer_type($topRight, 'footerTopRight') . '</div>
			</div>';
		}

		$footer = apply_filters('woocommerce_print_products_footer_html', $footer);

		return $footer;
    }

    private function get_header_footer_type($type, $position)
    {

    	$customHeaderFooterData = apply_filters('woocommerce_print_products_header_footer_data', '', $position, $type);
    	if(!empty($customHeaderFooterData)) {
    		return $customHeaderFooterData;
    	}

    	switch ($type) {
    		case 'text':
    			$text = wpautop( do_shortcode( $this->get_option($position. 'Text') ) );
    			$text = str_replace( array('{{current_date}}', '{{sku}}', '{{product_name}}'), array( date_i18n(get_option('date_format')), $this->data->sku, $this->data->title ), $text);
    			return $text;
    			break;
    		case 'bloginfo':
    			return $this->data->blog_name. '<br/>' .$this->data->blog_description;
    			break;
    		case 'pagenumber':
    			if($_GET['print-products'] == "pdf") {
    				return __( 'Page:', 'woocommerce-print-products'). ' {PAGENO}';
    			} else {
    				return '';
    			}
    			break;
    		case 'productinfo':
    			return $this->data->title. '<br/>' .get_permalink();
    			break;
			case 'categories':

				if($this->product->is_type('variation') && !class_exists('WooCommerce_Single_Variations')) {
					return wc_get_product_category_list( $this->product->get_parent_id() );
				} else {
					return wc_get_product_category_list($this->data->ID);	
				}

				
			case 'categorydescription':


				$primary_cat_id = get_post_meta($this->data->ID, '_yoast_wpseo_primary_product_cat', true);
				if($primary_cat_id){
					$terms = array( get_term($primary_cat_id, 'product_cat') );
				} else {
					$terms = get_the_terms( $this->data->ID, 'product_cat' );
				}
				
				$txt = "";
				if(!empty($terms)) {
					foreach ($terms as $term) {
						if(isset($term->description) && !empty($term->description)) {
							$txt = $term->description;
							break;
						}
					}
				}
				return $txt;
    		case 'image':
    			$image = $this->get_option($position. 'Image');
    			$imageSrc = $image['url'];
    			$imageHTML = '<img src="' . $image['url'] . '">';
    			return $imageHTML;
    			break;
    		case 'exportinfo':
    			return date_i18n( get_option('date_format') );
    			break;
			case 'qr':
				return '<barcode code="' . get_permalink($this->data->ID) . '" type="QR" class="qr-code" size="' . $this->get_option('qrSize') . '" error="M" />';
				break;
    		default:
    			return '';
    			break;
    	}
    }

    public function get_first_layout()
    {
    	$showImage = $this->get_option('showImage');
    	$showImageSize = $this->get_option('showImageSize');
		$showTitle = $this->get_option('showTitle');
		$showPrice = $this->get_option('showPrice');
		$showStock = $this->get_option('showStock');
		$showShortDescription = $this->get_option('showShortDescription');
		$showSKU = $this->get_option('showSKU');
		$showSKUMoveUnderTitle = $this->get_option('showSKUMoveUnderTitle');
		$showCategories = $this->get_option('showCategories');
		$showTags = $this->get_option('showTags');
		$showQR = $this->get_option('showQR');
		$showMetaFreetext = $this->get_option('showMetaFreetext');
		$metaFreeText = $this->get_option('metaFreeText');

		$featured_image = '<img width="' . $showImageSize . 'px" src="' . $this->data->src . '">';


		$html = '<div class="frame layout-1">';

			$html .= '<div class="two-cols">';

				$html .= '<div class="col image-column">';
				if($showImage) {
					$html .= '<div class="featured-image">' .$featured_image. '</div>';
				}
				$html .= '</div>';

				$html .= '<div class="col product-info-col">';
					$html .= '<div class="product-info">';

						$html .= apply_filters('woocommerce_print_products_before_product_info_html', '', $this->data->ID); 

						if($showTitle) {
							$html .= '<h1 class="product-title">' . $this->data->title. '</h1>';
						}

						if($showSKU && $showSKUMoveUnderTitle) {
							$html .= '<span class="product-sku"><b>' .__( 'SKU:', 'woocommerce-print-products'  ). '</b> ' . $this->data->sku . '</span><br>';
						}

						if($showPrice) {
							$html .= '<span class="product-price" style="font-weight:bold;">' . $this->data->price. '</span>';
						}
						if($showShortDescription) {
							$html .=  '<div class="product-short-description">' . wpautop($this->data->short_description). '</div>';
						}

						if($showMetaFreetext && !empty($metaFreeText)) {
							$html .= '<div class="product-meta-free-text">' . wpautop($metaFreeText) . '</div>';
						}
						if($showQR) {
							$html .= '<barcode code="' . get_permalink($this->data->ID) . '" type="QR" class="qr-code" size="' . $this->get_option('qrSize') . '" error="M" />';
						}

						$html .= '<div class="hr"></div>';

						if($showSKU && !$showSKUMoveUnderTitle) {
							$html .= '<span class="product-sku"><b>' .__( 'SKU:', 'woocommerce-print-products'  ). '</b> ' . $this->data->sku . '</span><br>';
						}

						if($showStock) {
							$html .= '<span class="product-stock"><b>' .__( 'Stock:', 'woocommerce-print-products'  ). '</b> ' . $this->data->stock_status . '</span><br>';
						}

						if($showCategories) {
							$html .= '<span class="product-categories">' . $this->data->categories. '</span><br>';
						}
						if($showTags) {
							$html .= '<span class="product-tags">' . $this->data->tags. '</span><br>';
						}
					 	if(is_array($this->data->meta_keys)) {
					 		$html .= '<div class="product-meta-keys">';
					 		$metaKeySeparator = $this->get_option('customMetaKeysSeparator');
					 		foreach ( $this->data->meta_keys as $meta_key){
					 			if(empty($meta_key['value'])) {
					 				continue;
					 			}
					 			$html .= '<b class="product-meta-key">' . $meta_key['before'] . '</b> <span class="product-meta-value">' . $meta_key['value'] . '</span>' . $metaKeySeparator;
					 		}
					 		$html .= '</div>';
					 	}

					 	$html .= apply_filters('woocommerce_print_products_after_product_info_html', '', $this->data->ID); 

					$html .= '</div>';
				$html .= '</div>';
				$html .= '<div class="clear"></div>';
			$html .= '</div>';
		$html .= '</div>';

		return $html;
    }

    public function get_second_layout()
    {
    	$showImage = $this->get_option('showImage');
    	$showImageSize = $this->get_option('showImageSize');
		$showTitle = $this->get_option('showTitle');
		$showPrice = $this->get_option('showPrice');
		$showStock = $this->get_option('showStock');
		$showShortDescription = $this->get_option('showShortDescription');
		$showSKU = $this->get_option('showSKU');
		$showSKUMoveUnderTitle = $this->get_option('showSKUMoveUnderTitle');
		$showCategories = $this->get_option('showCategories');
		$showTags = $this->get_option('showTags');
		$showQR = $this->get_option('showQR');
		$showMetaFreetext = $this->get_option('showMetaFreetext');
		$metaFreeText = $this->get_option('metaFreeText');

		$featured_image = '<img width="' . $showImageSize . 'px" src="' . $this->data->src . '" >';

		$html = '<div class="frame layout-two" width="100%">';
			if($showTitle) {
				$html .= '<div class="product-title">
								<h1>' . $this->data->title . '</h1>
							</div>';
			}

			if($showSKU && $showSKUMoveUnderTitle) {
				$html .= '<span class="product-sku"><b>' .__( 'SKU:', 'woocommerce-print-products'  ). '</b> ' . $this->data->sku . '</span>';
			}

			if($showImage) {
				$html .= '<div class="featured-image layout-2" style="text-align:center;">'
							. $featured_image .
						'</div>';
			}	
		
			$html .= '<div class="product-info">';

				$html .= apply_filters('woocommerce_print_products_before_product_info_html', '', $this->data->ID); 

				if($showPrice) {
					$html .= '<p class="product-price" style="font-weight:bold; font-size:20pt;">' . $this->data->price. '</p>';
				}
				if($showShortDescription) {
					$html .= '<div class="product-short-description">' . wpautop($this->data->short_description). '</div>';
				}

				if($showMetaFreetext && !empty($metaFreeText)) {
					$html .= '<div class="product-meta-free-text">' . wpautop($metaFreeText) . '</div>';
				}

				$html .= '<p class="meta">';
				if($showSKU && !$showSKUMoveUnderTitle) {
					$html .= '<span class="product-sku"><b>' .__( 'SKU:', 'woocommerce-print-products'  ). '</b> ' . $this->data->sku . '</span>';
				}

				if($showStock) {
					if($showSKU && !$showSKUMoveUnderTitle) {
						$html .= ' | ';	
					}
					$html .= '<span class="product-stock"><b>' .__( 'Stock:', 'woocommerce-print-products'  ). '</b> ' . $this->data->stock_status . '</span>';
				}

				if($showCategories) {
					if($showStock || ($showSKU && !$showSKUMoveUnderTitle)) {
						$html .= ' | ';	
					}
					$html .= '<span class="product-categories">' . $this->data->categories . '</span>';
				}

				if($showTags) {
					if($showStock || $showCategories || ($showSKU && !$showSKUMoveUnderTitle)) {
						$html .= ' | ';	
					}
					$html .= '<span class="product-tags">' . $this->data->tags. '</span>';
				}

				$html .= '</p>';

			 	if(is_array($this->data->meta_keys)) {
			 		$metaKeySeparator = $this->get_option('customMetaKeysSeparator');
			 		$html .= '<p class="meta-keys">';
			 		foreach ( $this->data->meta_keys as $meta_key){
			 			if(empty($meta_key['value'])) {
			 				continue;
			 			}
			 			$html .= '<b class="meta-key">' . $meta_key['before'] . '</b> ' . $meta_key['value'] . $metaKeySeparator;
			 		}
			 		$html .= '</p>';
			 	}

			 	$html .= apply_filters('woocommerce_print_products_after_product_info_html', '', $this->data->ID); 

			$html .= '</div>';

			if($showQR) {
				$html .= '<div class="qr-code-container">
							<div class="qr-code">
								<barcode code="' . get_permalink($this->data->ID) . '" type="QR" class="qr-code" size="' . $this->get_option('qrSize') . '" error="M" />
							</div>
						</div>';
			}
		$html .= '</div>';
					
		return $html;
	}

	public function get_third_layout()
    {

    	$product = $this->product;

    	$showImage = $this->get_option('showImage');
    	$showImageSize = $this->get_option('showImageSize');
		$showTitle = $this->get_option('showTitle');
		$showPrice = $this->get_option('showPrice');
		$showStock = $this->get_option('showStock');
		$showShortDescription = $this->get_option('showShortDescription');
		$showSKU = $this->get_option('showSKU');
		$showSKUMoveUnderTitle = $this->get_option('showSKUMoveUnderTitle');
		$showCategories = $this->get_option('showCategories');
		$showTags = $this->get_option('showTags');
		$showQR = $this->get_option('showQR');
		$showMetaFreetext = $this->get_option('showMetaFreetext');
		$metaFreeText = $this->get_option('metaFreeText');

		$featured_image = '<img width="' . $showImageSize . 'px" src="' . $this->data->src . '" >';

		if( version_compare( $this->woocommerce_version, '3.0.0', ">=" ) ) {
			$attachment_ids = $product->get_gallery_image_ids();
		} else {
			$attachment_ids = $product->get_gallery_attachment_ids();
		}

		if(isset($attachment_ids[0])) {
			$thumbnail = wp_get_attachment_image_src( $attachment_ids[0], 'shop_single' ); 
			$src = $thumbnail[0];
			if(!empty($src)) {
				$featured_image .= '<img width="' . $showImageSize . 'px" src="' . $src . '" >';
			}
		}

		$html = '<div class="frame layout-third">';
		if($showTitle) {
			$html .= 	'<div class="product-title">
							<h1>' . $this->data->title . '</h1>
						</div>';
		}

		if($showSKU && $showSKUMoveUnderTitle) {
			$html .= '<div class="product-sku"><b>' .__( 'SKU:', 'woocommerce-print-products'  ). '</b> ' . $this->data->sku . '</div>';
		}

		if($showShortDescription) {
			$html .= 	'<div>
							<div class="product-short-description">' . wpautop($this->data->short_description) . '</div>
						</div>';
		}

		$html .= '<div class="three-cols">';

			$html .= '<div class="col image-column">';
				$html .= '<div class="featured-image">';
				if($showImage) {
					$html .= $featured_image;
				}
				$html .= '</div>';
			$html .= '</div>';

			$html .= '<div class="col-two product-info-column">';
				$html .= '<div class="product-info">';

					$html .= apply_filters('woocommerce_print_products_before_product_info_html', '', $this->data->ID); 

					$html .= '<div class="product-meta-container">';
						$html .= '<div class="product-metas" width="100%" style="padding: 0 0 10px 0;">';
							if($showQR) {
								$html .=  '<div class="qr-code">';
								$html .= '<barcode code="' . get_permalink($this->data->ID) . '" type="QR" class="qr-code" size="' . $this->get_option('qrSize') . '" error="M" />';
								$html .=  '</div>';
							}
							if($showMetaFreetext && !empty($metaFreeText)) {
								$html .= '<div class="meta-free-text">';
								$html .= '<div class="product-meta-free-text">' . wpautop($metaFreeText) . '</div>';
								$html .= '</div>';
							}
						$html .= '</div>';

						if($showSKU && !$showSKUMoveUnderTitle) {
							$html .= '<div class="product-sku"><b>' .__( 'SKU:', 'woocommerce-print-products'  ). '</b> ' . $this->data->sku . '</div>';
						}
						if($showPrice) {
							$html .= '<div class="product-price"><b>' .__( 'Price:', 'woocommerce-print-products'  ). '</b> ' . $this->data->price . '</div>';
						}
						if($showPrice) {
							$html .= '<div class="product-stock"><b>' .__( 'Stock:', 'woocommerce-print-products'  ). '</b> ' . $this->data->stock_status . '</div>';
						}
						if($showCategories) {
							$html .= '<div class="product-categories">' . $this->data->categories . '</div>';
						}
						if($showTags) {
							$html .= '<div class="product-tags">' . $this->data->tags . '</div>';
						}
					 	if(is_array($this->data->meta_keys)) {
					 		$html .= '<p>';
					 		$metaKeySeparator = $this->get_option('customMetaKeysSeparator');
					 		foreach ( $this->data->meta_keys as $meta_key){
					 			if(empty($meta_key['value'])) {
					 				continue;
					 			}

					 			$html .= '<b>' . $meta_key['before'] . '</b> ' . $meta_key['value'] . $metaKeySeparator;
					 		}
					 		$html .= '</p>';
					 	}

						$html .= $this->get_attributes_table();

					$html .= '</div>';

					$html .= apply_filters('woocommerce_print_products_after_product_info_html', '', $this->data->ID); 

				$html .= '</div>';
				$html .= '<div class="clear"></div>';
			$html .= '</div>';
		$html .= '</div>';
					
		return $html;
	}

	public function get_fourth_layout()
    {
    	$showImage = $this->get_option('showImage');
    	$showImageSize = $this->get_option('showImageSize');
		$showTitle = $this->get_option('showTitle');
		$showPrice = $this->get_option('showPrice');
		$showStock = $this->get_option('showStock');
		$showShortDescription = $this->get_option('showShortDescription');
		$showSKU = $this->get_option('showSKU');
		$showSKUMoveUnderTitle = $this->get_option('showSKUMoveUnderTitle');
		$showCategories = $this->get_option('showCategories');
		$showTags = $this->get_option('showTags');
		$showQR = $this->get_option('showQR');
		$showMetaFreetext = $this->get_option('showMetaFreetext');
		$metaFreeText = $this->get_option('metaFreeText');

		$featured_image = '<img width="' . $showImageSize . 'px" src="' . $this->data->src . '">';


		$html = '<div class="frame layout-4">';

			if($showTitle) {
				$html .= '<h1 class="product-title">' . $this->data->title. '</h1>';
			}
			
			if($showPrice) {
				$html .= '<span class="product-price" style="font-weight:bold;">' . $this->data->price. '</span>';
			}

			if($showSKU && $showSKUMoveUnderTitle) {
				$html .= '<span class="product-sku"><b>' .__( 'SKU:', 'woocommerce-print-products'  ). '</b> ' . $this->data->sku . '</span><br>';
			}

			$html .= '<div class="two-cols">';

				$html .= '<div class="col image-column">';
				if($showImage) {
					$html .= '<div class="featured-image" style="text-align:left;">' .$featured_image. '</div>';
				}
				$html .= '</div>';

				$html .= '<div class="col product-attr-col">';

					$html .= $this->get_attributes_table();

				$html .= '</div>';

				$html .= '<div class="clear"></div>';
			$html .= '</div>';

			$html .= '<div class="product-info">';

				$html .= apply_filters('woocommerce_print_products_before_product_info_html', '', $this->data->ID); 

				if($showShortDescription) {
					$html .=  '<div class="product-short-description">' . wpautop($this->data->short_description). '</div>';
				}
				if($showMetaFreetext && !empty($metaFreeText)) {
					$html .= '<div class="product-meta-free-text">' . wpautop($metaFreeText) . '</div>';
				}
				if($showQR) {
					$html .= '<barcode code="' . get_permalink($this->data->ID) . '" type="QR" class="qr-code" size="' . $this->get_option('qrSize') . '" error="M" />';
				}

				$html .= '<div class="hr"></div>';

				if($showSKU && !$showSKUMoveUnderTitle) {
					$html .= '<span class="product-sku"><b>' .__( 'SKU:', 'woocommerce-print-products'  ). '</b> ' . $this->data->sku . '</span><br>';
				}

				if($showStock) {
					$html .= '<span class="product-stock"><b>' .__( 'Stock:', 'woocommerce-print-products'  ). '</b> ' . $this->data->stock_status . '</span><br>';
				}

				if($showCategories) {
					$html .= '<span class="product-categories">' . $this->data->categories . '</span><br>';
				}
				if($showTags) {
					$html .= '<span class="product-tags">' . $this->data->tags . '</span><br>';
				}
			 	if(is_array($this->data->meta_keys)) {
			 		$html .= '<div class="product-meta-keys">';
			 		$metaKeySeparator = $this->get_option('customMetaKeysSeparator');
			 		foreach ( $this->data->meta_keys as $meta_key){
			 			if(empty($meta_key['value'])) {
			 				continue;
			 			}
			 			$html .= '<b class="product-meta-key">' . $meta_key['before'] . '</b> <span class="product-meta-value">' . $meta_key['value'] . '</span>' . $metaKeySeparator;
			 		}
			 		$html .= '</div>';
			 	}

			 	$html .= apply_filters('woocommerce_print_products_after_product_info_html', '', $this->data->ID); 

			$html .= '</div>';
		$html .= '</div>';

		return $html;
    }

    public function get_fifth_layout()
    {
    	$showImage = $this->get_option('showImage');
    	$showImageSize = $this->get_option('showImageSize');
		$showTitle = $this->get_option('showTitle');
		$showPrice = $this->get_option('showPrice');
		$showStock = $this->get_option('showStock');
		$showShortDescription = $this->get_option('showShortDescription');
		$showDescription = $this->get_option('showDescription');
		$showSKU = $this->get_option('showSKU');
		$showSKUMoveUnderTitle = $this->get_option('showSKUMoveUnderTitle');
		$showCategories = $this->get_option('showCategories');
		$showTags = $this->get_option('showTags');
		$showQR = $this->get_option('showQR');
		$showMetaFreetext = $this->get_option('showMetaFreetext');
		$metaFreeText = $this->get_option('metaFreeText');

		$featured_image = '<img width="' . $showImageSize . 'px" src="' . $this->data->src . '">';


		$html = '<div class="frame layout-5">';

			$html .= '<div class="two-cols">';

				$html .= '<div class="col product-info-container">';

					$html .= apply_filters('woocommerce_print_products_before_product_info_html', '', $this->data->ID); 

					if($showSKU) {
						$html .= '<div class="product-sku">' . $this->data->sku . '</div>';
					}

					if($showTitle) {
						$html .= '<div class="product-title">' . $this->data->title. '</div>';
					}
					
					if($showShortDescription) {
						$html .=  '<div class="product-short-description">' . wpautop($this->data->short_description). '</div>';
					}

					if($showPrice) {
						$html .= '<span class="product-price">' . $this->data->price. '</span>';
					}

					$html .= '<div class="product-description-container">';
						if($showDescription) {
							$html .= '<h2 class="product-description-title">' . $this->get_option('showDescriptionTitleValue') . '</h2>';
							$html .= '<div class="product-description">' . wpautop($this->data->description) . '</div>';
						}

						if($showMetaFreetext && !empty($metaFreeText)) {
							$html .= '<div class="product-meta-free-text">' . wpautop($metaFreeText) . '</div>';
						}
						if($showQR) {
							$html .= '<barcode code="' . get_permalink($this->data->ID) . '" type="QR" class="qr-code" size="' . $this->get_option('qrSize') . '" error="M" />';
						}

						if($showStock) {
							$html .= '<span class="product-stock"><b>' .__( 'Stock:', 'woocommerce-print-products'  ). '</b> ' . $this->data->stock_status . '</span><br>';
						}

						if($showCategories) {
							$html .= '<span class="product-categories">' . $this->data->categories . '</span><br>';
						}
						if($showTags) {
							$html .= '<span class="product-tags">' . $this->data->tags . '</span><br>';
						}
					 	if(is_array($this->data->meta_keys)) {
					 		$html .= '<div class="product-meta-keys">';
					 		$metaKeySeparator = $this->get_option('customMetaKeysSeparator');
					 		foreach ( $this->data->meta_keys as $meta_key){
					 			if(empty($meta_key['value'])) {
					 				continue;
					 			}
					 			$html .= '<b class="product-meta-key">' . $meta_key['before'] . '</b> <span class="product-meta-value">' . $meta_key['value'] . '</span>' . $metaKeySeparator;
					 		}
					 		$html .= '</div>';
					 	}

				 		$html .= apply_filters('woocommerce_print_products_after_product_info_html', '', $this->data->ID); 

			 		$html .= '</div>';

					$html .= '<div class="product-attributes">';

						$html .= $this->get_attributes_table();
						
					$html .= '</div>';

				$html .= '</div>';


				$html .= '<div class="col image-column">';
				if($showImage) {
					$html .= '<div class="featured-image" style="text-align:left;">' .$featured_image. '</div>';
				}
				$html .= '</div>';	

			$html .= '<div class="clear"></div>';
			$html .= '</div>';	

		$html .= '</div>';	

		return $html;
    }

    public function get_sixth_layout()
    {
    	$showImage = $this->get_option('showImage');
    	$showImageSize = $this->get_option('showImageSize');
		$showTitle = $this->get_option('showTitle');
		$showPrice = $this->get_option('showPrice');
		$showStock = $this->get_option('showStock');
		$showShortDescription = $this->get_option('showShortDescription');
		$showDescription = $this->get_option('showDescription');
		$showSKU = $this->get_option('showSKU');
		$showSKUMoveUnderTitle = $this->get_option('showSKUMoveUnderTitle');
		$showCategories = $this->get_option('showCategories');
		$showTags = $this->get_option('showTags');
		$showQR = $this->get_option('showQR');
		$showMetaFreetext = $this->get_option('showMetaFreetext');
		$metaFreeText = $this->get_option('metaFreeText');

		$showGalleryImageSizeType = $this->get_option('showGalleryImageSizeType');
		if(!empty($showGalleryImageSizeType)) {
			$galleryImageSizeType = $showGalleryImageSizeType;
		} else {
			$galleryImageSizeType = 'shop_single';
		}

		$product = $this->product;

		$featured_image = '<img width="' . $showImageSize . 'px" src="' . $this->data->src . '">';
		$second_image = '';
		$attachment_ids = array();

		if(isset($_GET['variation']) && !empty($_GET['variation'])) {
			$variationGalleryImage = get_post_meta($_GET['variation'], 'woo_variation_gallery_images', true);
			if(!empty($variationGalleryImage)) {
				$attachment_ids = $variationGalleryImage;
			}
		} 

		if(empty($attachment_ids)) {
			if(isset($_GET['variation']) && !empty($_GET['variation'])) {
				$product = wc_get_product( wp_get_post_parent_id( $_GET['variation']) );
			} else {
				$product = $this->product;
			}

			$attachment_ids = $product->get_gallery_image_ids();
		}

		if(!empty($attachment_ids)) {

			$attachment_id = $attachment_ids[0];
			$thumbnail = wp_get_attachment_image_src( $attachment_id, $galleryImageSizeType ); 
			$src = $thumbnail[0];

			if($this->get_option('useImageLocally') && !empty($src)) {
			    $uploads = wp_upload_dir();
				$src = str_replace( $uploads['baseurl'], $uploads['basedir'], $src );
			}

			$second_image = '<img width="' . $showImageSize . 'px" src="' . $src . '" >';
		}

		$html = '<div class="frame layout-6">';

			if($showTitle) {
				$html .= '<div class="product-title">' . $this->data->title. '</div>';
			}

			if($showSKU) {
				$html .= '<span class="product-sku"><b>' .__( 'SKU:', 'woocommerce-print-products'  ). '</b> ' . $this->data->sku . '</span><br>';
			}

			$html .= '<div class="product-attributes">';

				$html .= $this->get_attributes_table();
				
			$html .= '</div>';


			if($showImage) {
				$html .= '<div class="two-cols">';

					$html .= '<div class="col image-column">';
						$html .= '<div class="featured-image">' . $featured_image . '</div>';
					$html .= '</div>';	

					if($second_image) {
						$html .= '<div class="col image-column">';
							$html .= '<div class="second-image">' . $second_image . '</div>';
						$html .= '</div>';
					}	
					$html .= '<div class="clear"></div>';	
				$html .= '</div>';	
			}


		$html .= '</div>';	

		return $html;
    }

    public function get_seventh_layout()
    {
    	$showImage = $this->get_option('showImage');
    	$showImageSize = $this->get_option('showImageSize');
		$showTitle = $this->get_option('showTitle');
		$showPrice = $this->get_option('showPrice');
		$showStock = $this->get_option('showStock');
		$showShortDescription = $this->get_option('showShortDescription');
		$showDescription = $this->get_option('showDescription');
		$showSKU = $this->get_option('showSKU');
		$showSKUMoveUnderTitle = $this->get_option('showSKUMoveUnderTitle');
		$showCategories = $this->get_option('showCategories');
		$showTags = $this->get_option('showTags');
		$showQR = $this->get_option('showQR');
		$showMetaFreetext = $this->get_option('showMetaFreetext');
		$metaFreeText = $this->get_option('metaFreeText');

		$showGalleryImageSizeType = $this->get_option('showGalleryImageSizeType');
		if(!empty($showGalleryImageSizeType)) {
			$galleryImageSizeType = $showGalleryImageSizeType;
		} else {
			$galleryImageSizeType = 'shop_single';
		}

		$product = $this->product;

		$featured_image = '<img width="' . $showImageSize . 'px" src="' . $this->data->src . '">';
		$second_image = '';
		$attachment_ids = array();

		if(isset($_GET['variation']) && !empty($_GET['variation'])) {
			$variationGalleryImage = get_post_meta($_GET['variation'], 'woo_variation_gallery_images', true);
			if(!empty($variationGalleryImage)) {
				$attachment_ids = $variationGalleryImage;
			}
		} 

		if(empty($attachment_ids)) {
			if(isset($_GET['variation']) && !empty($_GET['variation'])) {
				$product = wc_get_product( wp_get_post_parent_id( $_GET['variation']) );
			} else {
				$product = $this->product;
			}

			$attachment_ids = $product->get_gallery_image_ids();
		}

		if(!empty($attachment_ids)) {

			$attachment_id = $attachment_ids[0];
			$thumbnail = wp_get_attachment_image_src( $attachment_id, $galleryImageSizeType ); 
			$src = $thumbnail[0];

			if($this->get_option('useImageLocally') && !empty($src)) {
			    $uploads = wp_upload_dir();
				$src = str_replace( $uploads['baseurl'], $uploads['basedir'], $src );
			}

			$second_image = '<img width="' . $showImageSize . 'px" src="' . $src . '" >';
		}

		$html = '<div class="frame layout-7">';

			$html .= '<div class="title-sku-container">';
				if($showTitle) {
					$html .= $this->data->title . '<br>';
				}

				if($showSKU) {
					$html .= '<a class="product-sku-link" href="' . $this->data->link . '">' . $this->data->sku . '</a>';
				}
			$html .= '</div>';

			if($showImage) {
				$html .= '<div class="two-cols image-container">';

					$html .= '<div class="col image-column">';
						$html .= '<div class="featured-image">' . $featured_image . '</div>';
					$html .= '</div>';	

					if($second_image) {
						$html .= '<div class="col image-column">';
							$html .= '<div class="second-image">' . $second_image . '</div>';
						$html .= '</div>';
					}	
					$html .= '<div class="clear"></div>';	
				$html .= '</div>';	
			}

			$html .= '<div class="product-attributes">';

				$html .= $this->get_attributes_table();
				
			$html .= '</div>';

		$html .= '</div>';	

		return $html;
    }

   public function get_eigth_layout()
    {
    	$showImage = $this->get_option('showImage');
    	$showImageSize = $this->get_option('showImageSize');
		$showTitle = $this->get_option('showTitle');
		$showPrice = $this->get_option('showPrice');
		$showStock = $this->get_option('showStock');
		$showShortDescription = $this->get_option('showShortDescription');
		$showSKU = $this->get_option('showSKU');
		$showSKUMoveUnderTitle = $this->get_option('showSKUMoveUnderTitle');
		$showCategories = $this->get_option('showCategories');
		$showTags = $this->get_option('showTags');
		$showQR = $this->get_option('showQR');
		$showMetaFreetext = $this->get_option('showMetaFreetext');
		$metaFreeText = $this->get_option('metaFreeText');

		$featured_image = '<img width="' . $showImageSize . 'px" src="' . $this->data->src . '">';


		$html = '<div class="frame layout-8">';

			$html .= '<div class="three-cols">';

				$html .= '<div class="col image-column">';
				if($showImage) {
					$html .= '<div class="featured-image">' .$featured_image. '</div>';
				}

				if($this->get_option('showGalleryImages')) {
					$html .= $this->get_gallery_images();
				}

				$html .= '</div>';

				$html .= '<div class="col-two product-info-col">';
					$html .= '<div class="product-info">';

						$html .= apply_filters('woocommerce_print_products_before_product_info_html', '', $this->data->ID); 

						if($showTitle) {
							$html .= '<h1 class="product-title">' . $this->data->title. '</h1>';
						}

						if($showSKU && $showSKUMoveUnderTitle) {
							$html .= '<span class="product-sku"><b>' .__( 'SKU:', 'woocommerce-print-products'  ). '</b> ' . $this->data->sku . '</span><br>';
						}

						if($showPrice) {
							$html .= '<span class="product-price" style="font-weight:bold;">' . $this->data->price. '</span>';
						}
						if($showShortDescription) {
							$html .=  '<div class="product-short-description">' . wpautop($this->data->short_description). '</div>';
						}

						if($showMetaFreetext && !empty($metaFreeText)) {
							$html .= '<div class="product-meta-free-text">' . wpautop($metaFreeText) . '</div>';
						}

						if($this->get_option('showAttributes')) {
							$html .= $this->get_attributes_table();
						}

						if($this->get_option('showReadMore')) {
							$html .= '<div class="product-read-more-container"><a class="product-read-more" href="' . $this->product->get_permalink() . '">' . $this->get_option('showReadMoreText') . '</a></div>';
						}
						
						if($this->get_option('showBarcode')) {
							$barcodeType = $this->get_option('barcodeType');
							$barcodeAttributeKey = $this->get_option('barcodeAttributeKey');
							$barcodeMetaValue = $this->product->get_attribute($barcodeAttributeKey);
							if(!empty($barcodeMetaValue)) {
								$html .= apply_filters('woocommerce_print_products_barcode', '<barcode size="' . $this->get_option('barcodeSize') . '" code="' . $barcodeMetaValue . '" type="' . $barcodeType .'" class="barcode"  />', $barcodeMetaValue, $barcodeType);
							}
						}

						if($showQR) {
							$html .= '<barcode code="' . get_permalink($this->data->ID) . '" type="QR" class="qr-code" size="' . $this->get_option('qrSize') . '" error="M" />';
						}
						
						$html .= '<div class="product-meta-container">';

							if($showSKU && !$showSKUMoveUnderTitle) {
								$html .= '<span class="product-sku"><b>' .__( 'SKU:', 'woocommerce-print-products'  ). '</b> ' . $this->data->sku . '</span><br>';
							}

							if($showStock) {
								$html .= '<span class="product-stock"><b>' .__( 'Stock:', 'woocommerce-print-products'  ). '</b> ' . $this->data->stock_status . '</span><br>';
							}

							if($showCategories) {
								$html .= '<span class="product-categories">' . $this->data->categories. '</span><br>';
							}
							if($showTags) {
								$html .= '<span class="product-tags">' . $this->data->tags. '</span><br>';
							}
						 	if(is_array($this->data->meta_keys)) {
						 		$html .= '<div class="product-meta-keys">';
						 		$metaKeySeparator = $this->get_option('customMetaKeysSeparator');
						 		foreach ( $this->data->meta_keys as $meta_key){
						 			if(empty($meta_key['value'])) {
						 				continue;
						 			}
						 			$html .= '<b class="product-meta-key">' . $meta_key['before'] . '</b> <span class="product-meta-value">' . $meta_key['value'] . '</span>' . $metaKeySeparator;
						 		}
						 		$html .= '</div>';
						 	}

						 	$html .= apply_filters('woocommerce_print_products_after_product_info_html', '', $this->data->ID); 
						 	
					 	$html .= '</div>';

					$html .= '</div>';
				$html .= '</div>';
				$html .= '<div class="clear"></div>';
			$html .= '</div>';
		$html .= '</div>';

		return $html;
    }

   	public function get_ninth_layout()
    {
    	$showImage = $this->get_option('showImage');
    	$showImageSize = $this->get_option('showImageSize');
		$showTitle = $this->get_option('showTitle');
		$showPrice = $this->get_option('showPrice');
		$showStock = $this->get_option('showStock');
		$showShortDescription = $this->get_option('showShortDescription');
		$showSKU = $this->get_option('showSKU');
		$showSKUMoveUnderTitle = $this->get_option('showSKUMoveUnderTitle');
		$showCategories = $this->get_option('showCategories');
		$showTags = $this->get_option('showTags');
		$showQR = $this->get_option('showQR');
		$showMetaFreetext = $this->get_option('showMetaFreetext');
		$metaFreeText = $this->get_option('metaFreeText');

		$featured_image = '<img width="' . $showImageSize . 'px" src="' . $this->data->src . '">';
		$product = $this->product;

		$html = '<div class="frame layout-9">';

			$html .= '<div class="two-cols">';

				$html .= '<div class="col-75 image-column">';

					if($showCategories) {

						$html .= '<div class="product-categories">';						
						$html .= $this->data->categories . '</div>';
					}

					$html .= '<div class="three-cols">';
						$html .= '<div class="col-two">';

						if($showImage) {
							$html .= '<div class="featured-image">' . $featured_image . '</div>';
						}

						$html .= '</div>';
						$html .= '<div class="col">';

						$attachment_ids = $product->get_gallery_image_ids();
						if(!empty($attachment_ids)) {

							$showGalleryImagesSize = $this->get_option('showGalleryImagesSize');

							if(isset($attachment_ids[0]) && !empty( $attachment_ids[0])) {
								$attachment_id = $attachment_ids[0];
								$thumbnail = wp_get_attachment_image_src( $attachment_id, 'full' ); 
								$src = $thumbnail[0];

								if($this->get_option('useImageLocally') && !empty($src)) {
								    $uploads = wp_upload_dir();
									$src = str_replace( $uploads['baseurl'], $uploads['basedir'], $src );
								}

								$html .= '<div class="image-one-container"><img width="' . $showGalleryImagesSize . 'px" class="image-one" src="' . $src . '" ></div>';
							}

							if(isset($attachment_ids[1]) && !empty( $attachment_ids[1])) {
								$attachment_id = $attachment_ids[1];
								$thumbnail = wp_get_attachment_image_src( $attachment_id, 'full' ); 
								$src = $thumbnail[0];

								if($this->get_option('useImageLocally') && !empty($src)) {
								    $uploads = wp_upload_dir();
									$src = str_replace( $uploads['baseurl'], $uploads['basedir'], $src );
								}

								$html .= '<div class="image-two-container"><img width="' . $showGalleryImagesSize . 'px" class="image-two" src="' . $src . '" ></div>';
							}
						}

						$html .= '</div>';

						$html .= '<div class="hr"></div>';
						$html .= apply_filters('woocommerce_print_products_after_product_images', '', $this->data->ID); 

					$html .= '</div>';


				$html .= '</div>';

				$html .= '<div class="col-25 product-info-col">';
					$html .= '<div class="product-info">';

						$html .= apply_filters('woocommerce_print_products_before_product_info_html', '', $this->data->ID); 

						if($showTitle) {
							$html .= '<h1 class="product-title">' . $this->data->title. '</h1>';
						}

						if($showSKU && $showSKUMoveUnderTitle) {
							$html .= '<span class="product-sku"><b>' .__( 'SKU:', 'woocommerce-print-products'  ). '</b> ' . $this->data->sku . '</span><br>';
						}

						if($showPrice) {
							$html .= '<span class="product-price" style="font-weight:bold;">' . $this->data->price. '</span>';
						}

						if($this->get_option('showDescription')) {
							$html .= '<div class="product-description">' . wpautop($this->data->description) . '</div>';
						}

						if($showShortDescription) {
							$html .=  '<div class="product-short-description">' . wpautop($this->data->short_description). '</div>';
						}

						if($showMetaFreetext && !empty($metaFreeText)) {
							$html .= '<div class="product-meta-free-text">' . wpautop($metaFreeText) . '</div>';
						}

						if($this->get_option('showReadMore')) {
							$html .= '<div class="product-read-more-container"><a class="product-read-more" href="' . $this->product->get_permalink() . '">' . $this->get_option('showReadMoreText') . '</a></div>';
						}

						if($showQR) {
							$html .= 
							'<div class="qr-code-container">
								<barcode code="' . get_permalink($this->data->ID) . '" type="QR" class="qr-code" size="' . $this->get_option('qrSize') . '" error="M" />
							</div>';
						}

						if($this->get_option('showBarcode')) {
							$barcodeType = $this->get_option('barcodeType');
							$barcodeAttributeKey = $this->get_option('barcodeAttributeKey');
							$barcodeMetaValue = $this->product->get_attribute($barcodeAttributeKey);
							if(!empty($barcodeMetaValue)) {
								$html .= apply_filters('woocommerce_print_products_barcode', '<div class="barcode-container"><barcode size="' . $this->get_option('barcodeSize') . '" code="' . $barcodeMetaValue . '" type="' . $barcodeType .'" class="barcode"  /></div>', $barcodeMetaValue, $barcodeType);
							}
						}

						$html .= '<div class="product-meta-container">';

							if($showSKU && !$showSKUMoveUnderTitle) {
								$html .= '<span class="product-sku"><b>' .__( 'SKU:', 'woocommerce-print-products'  ). '</b> ' . $this->data->sku . '</span><br>';
							}

							if($showStock) {
								$html .= '<span class="product-stock"><b>' .__( 'Stock:', 'woocommerce-print-products'  ). '</b> ' . $this->data->stock_status . '</span><br>';
							}

							if($showTags) {
								$html .= '<span class="product-tags">' . $this->data->tags. '</span><br>';
							}

						 	if(is_array($this->data->meta_keys)) {
						 		$html .= '<div class="product-meta-keys">';
						 		$metaKeySeparator = $this->get_option('customMetaKeysSeparator');
						 		foreach ( $this->data->meta_keys as $meta_key){
						 			if(empty($meta_key['value'])) {
						 				continue;
					 				}
						 			$html .= '<b class="product-meta-key">' . $meta_key['before'] . '</b> <span class="product-meta-value">' . $meta_key['value'] . '</span>' . $metaKeySeparator;
						 		}
						 		$html .= '</div>';
						 	}

						 	$html .= apply_filters('woocommerce_print_products_after_product_info_html', '', $this->data->ID); 

					 	$html .= '</div>';

					$html .= '</div>';
				$html .= '</div>';
				$html .= '<div class="clear"></div>';
			$html .= '</div>';
		$html .= '</div>';

		return $html;
    }

	public function get_pagebreak()
	{
		$html = '<div class="page-break"></div><pagebreak />';
		return $html;
	}

	public function get_notes()
	{
		$html = apply_filters('woocommerce_print_products_before_notes_html', '', $this->data->ID);

		$html .= '<div class="frame notes-container">';

			$html .= '<h2 class="notes-title">' . __('Notes', 'woocommerce-print-products') . '</h2>';

			$showNotesLinesCount = $this->get_option('showNotesLinesCount');
			for ($i=1; $i < $showNotesLinesCount; $i++) { 
				$html .= '<div class="notes-line"></div>';
			}

			$html .= '<div class="notes-line"></div>';

		$html .= '</div>';

		$html = apply_filters('woocommerce_print_products_notes_html', $html, $this->data->ID);

		return $html;
	}


    public function get_description()
    {
    	if(!$this->get_option('showDescription')) return FALSE;

    	if(empty($this->data->description)) return FALSE;

    	ob_start();

    	?>
		<div class="frame">

			<?php if($this->get_option('showDescriptionTitle')) { ?>

			<div class="title description-title">
				<h2><?php echo $this->get_option('showDescriptionTitleValue') ?></h2>
			</div>	

			<?php } ?>

			<div class="description">
				<?php
					echo wpautop($this->data->description)
				?>
			</div>
		</div>
		
		<?php
		return apply_filters('woocommerce_print_products_product_description_html', ob_get_clean(), $this->data->ID);
    }

    public function get_short_description()
    {
    	if(empty($this->data->short_description)) return FALSE;

    	ob_start();

    	?>
		<div class="frame">

			<div class="short-description">
				<?php
					echo wpautop($this->data->short_description)
				?>
			</div>
		</div>
		
		<?php
		return apply_filters('woocommerce_print_products_product_short_description_html', ob_get_clean(), $this->data->ID);
    }

    public function get_attributes_table()
    {
    	if(!$this->get_option('showAttributes')) return FALSE;

    	$variation = null;
		if(isset($_GET['variation']) && !empty($_GET['variation'])) {
			$variation = wc_get_product( $_GET['variation'] ) ;
			$product = wc_get_product( wp_get_post_parent_id( $_GET['variation']) );
		} else {
			$product = $this->product;
		}

		$attributeImageWidth = $this->get_option('attributeImageWidth');
		$attributeImageTermData = get_option( 'woocommerce_attribute_image_term_data' );

		$has_row    = false;
		$alt        = 1;
		$attributes = $product->get_attributes();

		if($variation) {
			$variationAttributes = $variation->get_attributes();

			if(!empty($variationAttributes)) {
				foreach($variationAttributes as $variationAttributeKey => $variationAttributeValue) {
					$originalAttribute = $attributes[$variationAttributeKey];

					$variationAttributeTerm = get_term_by( 'slug', $variationAttributeValue, $variationAttributeKey);
					if(!$variationAttributeTerm) {
						continue;
					}

					$originalAttribute->set_options( array( esc_html( $variationAttributeTerm->name ) ) );
					$attributes[$variationAttributeKey] = $originalAttribute;
				}
			}
		}
		if(empty($attributes)) {
			return false;
		}

		$display_dimensions = apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() );

		ob_start();

		echo '<div class="frame attributes-table-frame">';

			if($this->get_option('showAttributesTitle') && !empty($this->get_option('showAttributesTitleName'))) {
			?>
				
			<div class="title attributes-title">
				<h2><?php echo $this->get_option('showAttributesTitleName') ?></h2>
			</div>

			<?php
			}

			if(class_exists('WooCommerce_Group_Attributes') && $this->get_option('showAttributesGroup')) {

				global $woocommerce_group_attributes_options;

				$layout = $woocommerce_group_attributes_options['layout'];
				$layout = apply_filters('woocommerce_group_attributes_layout', $layout, $product->get_id());

				include ABSPATH . 'wp-content/plugins/woocommerce-group-attributes/public/partials/woocommerce-group-attributes-output-layout-' . $layout . '.php';

				echo '</div>';
				
				return ob_get_clean();
			}
			?>

			<table class="attributes">

				<?php if ( $variation && $variation->has_weight() ) { ?>
					<tr class="<?php if ( ( $alt = $alt * -1 ) === 1 ) echo 'alt'; ?>">
						<th><?php _e( 'Weight', 'woocommerce-print-products' ) ?></th>
						<td>
						<?php
							if( version_compare( $this->woocommerce_version, '3.0.0', ">=" ) ) {
								echo esc_html( wc_format_weight( $variation->get_weight() ) );
							} else {
								echo $variation->get_weight() . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) );
							}
						?></td>
					</tr>
				<?php } elseif( $display_dimensions && $product->has_weight() ) { ?>
					<tr class="<?php if ( ( $alt = $alt * -1 ) === 1 ) echo 'alt'; ?>">
						<th><?php _e( 'Weight', 'woocommerce-print-products' ) ?></th>
						<td>
						<?php
							if( version_compare( $this->woocommerce_version, '3.0.0', ">=" ) ) {
								echo esc_html( wc_format_weight( $product->get_weight() ) );
							} else {
								echo $product->get_weight() . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) );
							}
						?></td>
					</tr>
				<?php }  ?>

				<?php if ( $variation && $variation->has_dimensions() ) { ?>
					<tr class="<?php if ( ( $alt = $alt * -1 ) === 1 ) echo 'alt'; ?>">
						<th><?php _e( 'Dimensions', 'woocommerce-print-products' ) ?></th>
						<td>
						<?php
							if( version_compare( $this->woocommerce_version, '3.0.0', ">=" ) ) {
								echo esc_html( wc_format_dimensions( $variation->get_dimensions( false ) ) );
							} else {
								echo $variation->get_dimensions(); 
							}
						?></td>
					</tr>
				<?php } elseif( $display_dimensions && $product->has_dimensions() ) { ?>
					<tr class="<?php if ( ( $alt = $alt * -1 ) === 1 ) echo 'alt'; ?>">
						<th><?php _e( 'Dimensions', 'woocommerce-print-products' ) ?></th>
						<td>
						<?php
							if( version_compare( $this->woocommerce_version, '3.0.0', ">=" ) ) {
								echo esc_html( wc_format_dimensions( $product->get_dimensions( false ) ) );
							} else {
								echo $product->get_dimensions(); 
							}
						?></td>
					</tr>
				<?php } ?>

				<?php 
				$hiddenAttributes = $this->get_option('showAttributesHiddenAttributes');
				foreach ( $attributes as $attribute ) {

					if ( empty( $attribute['is_visible'] ) || ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) ) {
						continue;
					} else {
						$has_row = true;
					}

					if(!empty($hiddenAttributes)) {

						if(in_array( str_replace('pa_', '', $attribute['name']), $hiddenAttributes)) {
							continue;
						}
					}

					?>
					<tr class="<?php if ( ( $alt = $alt * -1 ) == 1 ) echo 'alt'; ?> <?php echo $attribute['name'] ?>">
						<th><?php echo wc_attribute_label( $attribute['name'] ); ?></th>
						<td><?php
							$values = array();

							if ( $attribute->is_taxonomy() ) {
								$attribute_taxonomy = $attribute->get_taxonomy_object();
								$attribute_values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

								if($variation && $attribute->get_variation()) {
									$variationOptions = $attribute->get_options();
									foreach($attribute_values as $attribute_value_key => $attribute_value) {
										if(!in_array($attribute_value->name, $variationOptions)) {
											unset($attribute_values[$attribute_value_key]);
										}
									}
								}

								$hasAttributeValueDescription = false;
								foreach ( $attribute_values as $attribute_value ) {
									$value_name = esc_html( $attribute_value->name );

									if($this->get_option('showAttributesImages')) {

										if(isset($attributeImageTermData[$attribute_value->term_id])) {
											$attributeImageData = $attributeImageTermData[$attribute_value->term_id];
											if(isset($attributeImageData['thumbnail']) && !empty($attributeImageData['thumbnail'])) {

												// $value_name = '<div class="woocommerce-attribute-image-container" style="width: ' . $attributeImageWidth . 'px">';

													$attribute_value_image_src = wp_get_attachment_image_src($attributeImageData['thumbnail'], 'full');
													$value_name = '<img width="' . $attributeImageWidth . 'px" src="' . $attribute_value_image_src[0] . '">';

													// if($this->get_option('showTextBelow')) {
													// 	$value_name .= '<br><span class="woocommerce-attribute-image-desc">' . $attribute_value->name . '</span>';
													// }
												// $value_name .= '</div>';
											} else 
											{
												$value_name = esc_html( $attribute_value->name );	
											}
										}
									}

									if ( $attribute_taxonomy->attribute_public && $this->get_option('showAttributesLink')) {
										$value_name = '<a href="' . esc_url( get_term_link( $attribute_value->term_id, $attribute->get_name() ) ) . '" rel="tag">' . $value_name . '</a>';
									}

									if($this->get_option('showAttributesValueDescription') && isset($attribute_value->description) && !empty($attribute_value->description)) {
										$hasAttributeValueDescription = true;
										$value_name .= '<div class="attribute-value-description">' . $attribute_value->description . '</div>';
									}

									$values[] = $value_name;
								}
							} else {
								
								$values = $attribute->get_options();
								foreach ( $values as &$value ) {
									$value = make_clickable( esc_html( $value ) );
								}
							}

							if($hasAttributeValueDescription && $this->get_option('showAttributesValueDescription')) {
								echo str_replace( array('<p>', '</p>'), '', apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( '', $values ) ) ), $attribute, $values ) );
							}  else {
								echo str_replace( array('<p>', '</p>'), '', apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values ) );
							}

						?></td>
					</tr>
				<?php } ?>

			</table>
		<?php

		echo '</div>';

		if ( $has_row ) {
			return apply_filters('woocommerce_print_products_product_attributes_html', ob_get_clean(), $this->data->ID);
		} else {
			return apply_filters('woocommerce_print_products_product_attributes_html', ob_end_clean(), $this->data->ID);
		}
    }

    public function get_reviews()
    {
    	if(!$this->get_option('showReviews')) return FALSE;

		if(isset($_GET['variation']) && !empty($_GET['variation'])) {
			$product = wc_get_product( wp_get_post_parent_id( $_GET['variation']) );
		} else {
			$product = $this->product;
		}

		if ( ! comments_open() ) {
			return;
		}

		$comments = get_comments(array(
			'post_id' => $product->get_id(),
			'status' => 'approve' //Change this to the type of comments to be displayed
		));

		ob_start();

		?>
		<table class="title reviews-title">
			<tr>
				<td>
					<h2>
					<?php
						if(empty($comments))
						{
							echo '<p class="woocommerce-noreviews">' . __( 'There are no reviews yet. ', 'woocommerce-print-products' ) . '</p>';
						} 
						elseif ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_review_count() ) )
						{
							echo sprintf( _n( '%s review for %s', '%s reviews for %s', $count, 'woocommerce-print-products' ), $count, get_the_title() );
						}
						else {
							echo __( 'Reviews', 'woocommerce-print-products' );
						}
					?>
					</h2>
				</td>
			</tr>
		</table>
		<table class="comments" style="vertical-align: top;">
		<?php

		foreach ($comments as $comment) {
			$rating   = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
			$verified = wc_review_is_from_verified_owner( $comment->comment_ID );

		?>
			<tr class="comment_container">

				<td class="avatar" style="padding-bottom: 50px;">
					<?php echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '60' ), '' ); ?>
				</td>

				<td class="comment-text" style="text-align: left;" valign="top">

					<p class="meta">
						<strong itemprop="author"><?= $comment->comment_author ?></strong> &ndash; <time itemprop="datePublished"><?php echo $comment->comment_date ?></time>
						<?php if ( $rating && get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) : ?>

							<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( __( 'Rated %d out of 5', 'woocommerce-print-products' ), $rating ) ?>">
								<span style="width:<?php echo ( $rating / 5 ) * 100; ?>%"><strong itemprop="ratingValue"><?php echo $rating; ?></strong> <?php _e( 'out of 5', 'woocommerce-print-products' ); ?></span>
							</div>

						<?php endif; ?>

						
					</p>

					<p><?= $comment->comment_content; ?></div>

				</td>
			</tr>
			<?php
		}

		?>
		</table>
		<?php
		return apply_filters('woocommerce_print_products_product_reviews_html', ob_get_clean(), $this->data->ID);
    }

    public function get_upsells()
    {
		if(!$this->get_option('showUpsells')) return FALSE;

		$product = $this->product;

		if( version_compare( $this->woocommerce_version, '3.0.0', ">=" ) ) {
			$upsells = $product->get_upsell_ids();
		} else {
			$upsells = $product->get_upsells();
		}

		if ( sizeof( $upsells ) === 0 ) {
			return;
		}

		ob_start();

		$meta_query = WC()->query->get_meta_query();

		$args = array(
			'post_type'           => 'product',
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => 1,
			'posts_per_page'      => $posts_per_page,
			'orderby'             => $orderby,
			'post__in'            => $upsells,
			'post__not_in'        => array( $product->get_id() ),
			'meta_query'          => $meta_query
		);

		$upsells = new WP_Query( $args );
		$upsells = $upsells->get_posts();

		if ( !empty($upsells)) { ?>

			<table class="title upsells-title">
				<tr>
					<td>
						<h2>
						<?php echo __( 'You may also like&hellip;', 'woocommerce-print-products' ) ?></h2>
					</td>
				</tr>
			</table>

			<table class="upsells products">

				<?php
				$max = 4;
				echo '<tr>';
				for ($i=0; $i < $max; $i++) { 

					if ( has_post_thumbnail($upsells[$i]->ID)) { 
						$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($upsells[$i]->ID), 'shop_single' ); 
						$src = $thumbnail[0];
					} else { 
						$src = plugin_dir_url( __FILE__ ) . 'img/placeholder.png';
					}
					$featured_image = '<img width="200px" src="' . $src . '" >';
					$permalink = get_permalink($upsells[$i]->ID);
					$title = $upsells[$i]->post_title;
					$short_description = $upsells[$i]->post_excerpt;

					echo '<td width="25%;">';
					if(isset($upsells[$i]) && !empty($upsells[$i]))
					{
						echo '<a href="' . $permalink . '" target="_blank">';
						echo $featured_image;
						echo '<br/><br/>';
						echo '<h3>' . $title . '</h3>';
						echo '</a>';
						//echo $upsells[$i]->post_excerpt;
					}
					echo '</td>';
				}
				echo '</tr>';
				?>

			</table>

		<?php
		} else {
			return FALSE;
		}

		wp_reset_postdata();

		return apply_filters('woocommerce_print_products_product_upsells_html', ob_get_clean(), $this->data->ID);
    }

    public function get_gallery_images()
    {
    	global $woocommerce;
		if(!$this->get_option('showGalleryImages')) return FALSE;
		$layout = $this->get_option('layout');

		$product = $this->product;

		ob_start();

		$attachment_ids = array();

		if(isset($_GET['variation']) && !empty($_GET['variation'])) {
			$variationGalleryImage = get_post_meta($_GET['variation'], 'woo_variation_gallery_images', true);
			if(!empty($variationGalleryImage)) {
				$attachment_ids = $variationGalleryImage;
			}
		} 

		if(empty($attachment_ids)) {
			if(isset($_GET['variation']) && !empty($_GET['variation'])) {
				$product = wc_get_product( wp_get_post_parent_id( $_GET['variation']) );
			} else {
				$product = $this->product;
			}
		
			$attachment_ids = $product->get_gallery_image_ids();
		}

		$count_attachment_ids = count($attachment_ids);
		if($layout == "3" || $layout == "6") {
			$count_attachment_ids--;
		}

		if ( $count_attachment_ids < 1) {
			return false;
		}
		
		echo '<div class="frame gallery-images-frame">';

		if($this->get_option('showGalleryImagesIntroTitle') && !empty($this->get_option('showGalleryImagesIntroTitleText'))) {
		?>
		
			<div class="title gallery-images-title">
				<tr>
					<td>
						<h2>
						<?php echo $this->get_option('showGalleryImagesIntroTitleText') ?></h2>
					</td>
				</tr>
			</div>
		<?php
		}
	
		$loop 				= 0;
		$custom_columns = $this->get_option('showGalleryImagesColumns');
		isset($custom_columns) ? $columns = $custom_columns : $columns = 3;

		$showGalleryImagesSize = $this->get_option('showGalleryImagesSize');
		if(!empty($showGalleryImagesSize)) {
			$galleryImageSize = $showGalleryImagesSize;
		} else {
			$galleryImageSize = '200';
		}

		$showGalleryImageSizeType = $this->get_option('showGalleryImageSizeType');
		if(!empty($showGalleryImageSizeType)) {
			$galleryImageSizeType = $showGalleryImageSizeType;
		} else {
			$galleryImageSizeType = 'shop_single';
		}

		$maxGalleryImages = $this->get_option('showGalleryImagesMax');

		?>

		<table class="woocommerce-print-products-gallery-images gallery-images-table">
		<?php
			$customBreak = true;
			foreach ( $attachment_ids as $attachment_id ) {

				if($layout == "3" && $customBreak == true) {
					$customBreak = false;
					continue;
				}

				$classes = array( 'zoom' );

				if ( $loop === 0 || $loop % $columns === 0 )
				{
					echo "<tr>";
					$classes[] = 'first';
				}

				$thumbnail = wp_get_attachment_image_src( $attachment_id, $galleryImageSizeType ); 
				

				if($this->get_option('showGalleryImagesTitle')) {
					$image_title 	= '<br/>' . esc_attr( get_the_title( $attachment_id ) );
				} else {
					$image_title 	= '';
				}
				if($this->get_option('showGalleryImagesCaption')) {
					$image_caption 	= '<br/>' . esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );
				} else {
					$image_caption 	= '';
				}
				if($this->get_option('showGalleryImagesAlt')) {
					$image_alt = '<br/>' . get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
				} else {
					$image_alt 	= '';
				}
				if($this->get_option('showGalleryImagesDescription')) {
					$image_description 	= '<br/>' . esc_attr( get_post_field( 'post_content', $attachment_id ) );
				} else {
					$image_description 	= '';
				}						

				$src = $thumbnail[0];

				if($this->get_option('useImageLocally') && !empty($src)) {
				    $uploads = wp_upload_dir();
					$src = str_replace( $uploads['baseurl'], $uploads['basedir'], $src );
				}

				$gallery_image = '<img width="' . $galleryImageSize . 'px" src="' . $src . '" >';

				$image_class = esc_attr( implode( ' ', $classes ) );

				echo sprintf( '<td valign="top" class="%s">%s %s %s %s %s</td>', $image_class, $gallery_image, $image_title, $image_caption, $image_alt, $image_description);

				if ( ( $loop + 1 ) % $columns === 0 )
				{
					echo "</tr>";
					$classes[] = 'last';
				}

				$loop++;

				if($loop == $maxGalleryImages) {
					break;
				}
			}

		?>
		</table>

		</div>

		<?php
		return apply_filters('woocommerce_print_products_product_gallery_images_html', ob_get_clean(), $this->data->ID);
    }

    public function get_variations()
    {
    	global $woocommerce;
    	
		$product = $this->product;

		ob_start();

		if($product->is_type( 'variable' ))
		{
			$attributes = $product->get_attributes();
			$available_variations = $product->get_available_variations();
			$showVariationImageSize = $this->get_option('showVariationImageSize');
			if(empty($showVariationImageSize)) {
				$showVariationImageSize = 150;
			}

			if(!empty($available_variations))
			{
			?>
			<div class="frame">
				<div class="title variations-title">
					<tr>
						<td>
							<h2>
							<?php echo __( 'Variations', 'woocommerce-print-products' ) ?></h2>
						</td>
					</tr>
				</div>
				<table class="woocommerce-print-products-variations variations-table">
			 		<thead>
			            <tr>
			            	<?php if($this->get_option('showVariationImage')){ ?>
			                <th><?php _e( 'Image', 'woocommerce-print-products' ); ?></th>
			                <?php } ?>

			            	<?php if($this->get_option('showVariationSKU')){ ?>
			                <th><?php _e( 'SKU', 'woocommerce-print-products' ); ?></th>
			                <?php } ?>

				            <?php if($this->get_option('showVariationPrice') && !$this->get_option('showVariationPriceLastColumn')){ ?>
				            <th><?php _e('Price', 'woocommerce-print-products') ?></th>
				            <?php } ?>

				            <?php if($this->get_option('showVariationStockStatus')){ ?>
				            <th><?php _e('Stock Status', 'woocommerce-print-products') ?></th>
				            <?php } ?>

				            <?php if($this->get_option('showVariationStockQuantity')){ ?>
				            <th><?php _e('Stock Quantity', 'woocommerce-print-products') ?></th>
				            <?php } ?>

			                <?php if($this->get_option('showVariationDescription')){ ?>
			                <th><?php _e('Description', 'woocommerce-print-products') ?></th>
			                <?php } ?>

			                <?php 
			                if($this->get_option('showVariationAttributes')){ 
		                		$variation_attributes = $this->product->get_variation_attributes();
			                	foreach ($variation_attributes as $key => $value) {
			                		echo '<th class="variation-attribute-head-' . $key . '">' . wc_attribute_label($key) . '</th>';
			                	}
		                	} 
		                	?>

				            <?php if($this->get_option('showVariationPrice') && $this->get_option('showVariationPriceLastColumn')){ ?>
				            <th><?php _e('Price', 'woocommerce-print-products') ?></th>
				            <?php } ?>
							
			            </tr>
			        </thead>
					<tbody>
			        <?php foreach ($available_variations as $variation) : ?>
			            <?php
       		            $variation = new WC_Product_Variation($variation['variation_id']);

			            $variation_image_ID = $variation->get_image_id();
			            if(!empty($variation_image_ID)) {
			            	$variation_image = wp_get_attachment_image_src($variation_image_ID, 'full');
			            	if(isset($variation_image[0]) && !empty($variation_image[0])) {
		            			$variation_image = $variation_image[0];
			            	}
			            } else {
			            	$variation_image = false;
			            }

						if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
							if (!$variation_image) $variation_image = wc_placeholder_img_src();
						} else {
							if (!$variation_image) $variation_image = woocommerce_placeholder_img_src();
						}

						if($this->get_option('useImageLocally') && !empty($variation_image)) {
						    $uploads = wp_upload_dir();
							$variation_image = str_replace( $uploads['baseurl'], $uploads['basedir'], $variation_image );
						}

			            ?>
			            <tr>
							<?php if($this->get_option('showVariationImage')){ ?>
							<td class="variations-image"><?php echo '<img width="' . $showVariationImageSize . 'px" src="' . $variation_image . '">' ?></td>
							<?php } ?>
			            	<?php if($this->get_option('showVariationSKU')){ ?>
			                <td class="variations-sku"><?php echo $variation->get_sku(); ?></td>
			                <?php } ?>

			                <?php if($this->get_option('showVariationPrice') && !$this->get_option('showVariationPriceLastColumn')){ ?>
			                <td class="variations-price"><?php echo $variation->get_price_html(); ?></td>
			                <?php } ?>

			                <?php if($this->get_option('showVariationStockStatus')){ ?>
			                <td class="variations-stock-status"><?php echo wc_get_stock_html($variation); ?></td>
			                <?php } ?>

			                <?php if($this->get_option('showVariationStockQuantity')){ ?>
			                <td class="variations-stock-quantity"><?php echo $variation->get_stock_quantity(); ?></td>
			                <?php } ?>

			                <?php if($this->get_option('showVariationDescription')){ ?>
			                <td class="variations-description"><?php echo $variation->get_description(); ?></td>
			            	<?php } ?>

			                <?php if($this->get_option('showVariationAttributes')){ ?>
				           		<?php foreach ($variation->get_variation_attributes() as $attr_name => $attr_value) : ?>
				                <td class="variations-attributes variation-attribute-value-<?php echo $attr_name ?>">
				                <?php
				                    // Get the correct variation values
				                    if (strpos($attr_name, '_pa_')){ // variation is a pre-definted attribute
				                        $attr_name = substr($attr_name, 10);
				                        $attr = get_term_by('slug', $attr_value, $attr_name);
				                        $attr_value = $attr->name;

				                        $attr_name = wc_attribute_label($attr_name);
				                    } else {
				                        $attr = maybe_unserialize( get_post_meta( $this->product->id, '_product_attributes' ) );
				                        $attr_name = substr($attr_name, 10);
				                        $attr_name = $attr[0][$attr_name]['name'];
				                    }
				                    if(empty($attr_value)) {
				                    	echo sprintf( __('Any %s', 'woocommerce-print-products'), $attr_name);
			                    	} else {
			                    		echo $attr_value;
		                    		}
				                ?>
				                </td>
					            <?php 
					            endforeach; ?>
				             <?php } ?>

			                <?php if($this->get_option('showVariationPrice') && $this->get_option('showVariationPriceLastColumn')){ ?>
			                <td class="variations-price"><?php echo $variation->get_price_html(); ?></td>
			                <?php } ?>

			            </tr>
			        <?php endforeach;?>
			        </tbody>
			    </table>
		    </div>
        <?php
        	} else {
        		return FALSE;
        	}
	    } else{
	    	return FALSE;
	    }

	    return apply_filters('woocommerce_print_products_product_variations_html', ob_get_clean(), $this->data->ID);
    }

    private function escape_filename($file, $lowercase = true)
    {
		// everything to lower and no spaces begin or end
		if($lowercase) {
			$file = strtolower($file);
		}
		$file = trim($file);

		// adding - for spaces and union characters
		$find = array(' ', '&', '\r\n', '\n', '+',',');
		$file = str_replace ($find, '-', $file);

		//delete and replace rest of special chars
		$find = array('/[^A-Za-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
		$repl = array('', '-', '');
		$file = preg_replace ($find, $repl, $file);

		return $file;
    }

	/**
	 * Exclude Product categories
	 *
	 * @since    1.1.8
	 */
    public function excludeProductCategories()
    {
    	global $post;

		$excludeProductCategories = $this->get_option('excludeProductCategories');
		$excludeProductCategoriesRevert = $this->get_option('excludeProductCategoriesRevert');

		$terms = get_the_terms( $post->ID, 'product_cat' );
		if($terms) {
			foreach ($terms as $term) {
				if($excludeProductsRevert) {
					if(!in_array($term->term_id, $excludeProductCategories)) {
						return TRUE;
					}
				} else {
					if(in_array($term->term_id, $excludeProductCategories)) {
						return TRUE;
					}
				}
			}
		}
    }

	/**
	 * Exclude Products
	 *
	 * @since    1.1.8
	 */
    public function excludeProducts()
    {
    	global $post;

		$excludeProducts = $this->get_option('excludeProducts');
		$excludeProductsRevert = $this->get_option('excludeProductsRevert');
		if($excludeProductsRevert) {
			if(!in_array($post->ID, $excludeProducts)) {
				return TRUE;
			}
		} else {
			if(in_array($post->ID, $excludeProducts)) {
				return TRUE;
			}
		}
    }

	/**
	 * Return the current user role
	 *
	 * @since    1.0.0
	 */
	private function get_user_role()
	{
		global $current_user;

		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);

		return $user_role;
	}

	public function get_customTabs()
	{
		global $product;
		$product = $this->product;

		$tabs = apply_filters( 'woocommerce_product_tabs', array() );
		
		if(empty($tabs)) {
			return;
		}

		unset($tabs['description']);
		unset($tabs['additional_information']);
		unset($tabs['reviews']);
		
		ob_start();

		echo '<div class="custom-tabs frame">';

		foreach ($tabs as $key => $tab) {
			$heading = $tab['title'];
			echo '<div id="custom-tab-' . $key . '" class="custom-tab">';
				call_user_func( $tab['callback'], $key, $tab );
			echo '</div>';
		}
		echo '</div>';

		return ob_get_clean();
	}

	public function add_template_data($html)
	{
		$template = intval($_GET['template']);
		$post = get_post($template);
		if(!$post) {
			return $html;
		}

		if($post->post_type != "print_template") {
			return $html;
		}


		$html = do_shortcode($post->post_content) . $html;
		return $html;
	}
}