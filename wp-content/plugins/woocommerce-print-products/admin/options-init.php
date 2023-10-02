<?php

    /**
     * For full documentation, please visit: http://docs.reduxframework.com/
     * For a more extensive sample-config file, you may look at:
     * https://github.com/reduxframework/redux-framework/blob/master/sample/sample-config.php
     */

    if ( ! class_exists( 'weLaunch' ) && ! class_exists( 'Redux' ) ) {
        return;
    }

    if( class_exists( 'weLaunch' ) ) {
        $framework = new weLaunch();
    } else {
        $framework = new Redux();
    }

    $woocommerce_print_products_options = get_option('woocommerce_print_products_options');

    // This is your option name where all the Redux data is stored.
    $opt_name = "woocommerce_print_products_options";

    $woocommerce_print_products_options_meta_keys = array();
    $atts = array();

    if(isset($_GET['page']) && $_GET['page'] == "woocommerce_print_products_options_options") {

        // Get Custom Meta Keys for product
        $transient_name = 'woocommerce_print_products_options_meta_keys';
        $woocommerce_print_products_options_meta_keys = get_transient( $transient_name );
        
        $atts = wc_get_attribute_taxonomies();
        $temp = array();
        if(!empty($atts)) {
            foreach ($atts as $value) {
                $temp[$value->attribute_name] = $value->attribute_label;
            }
        }
        $atts = $temp;

        if ( false === $woocommerce_print_products_options_meta_keys ) { 

            global $wpdb;
            $sql = "SELECT DISTINCT meta_key
                            FROM " . $wpdb->postmeta . "
                            INNER JOIN  " . $wpdb->posts . " 
                            ON post_id = ID
                            WHERE post_type = 'product'
                            ORDER BY meta_key ASC";

            $meta_keys = $wpdb->get_results( $sql, 'ARRAY_A' );
            $meta_keys_to_exclude = array('_crosssell_ids', '_children', '_default_attributes', '_height', '_length', '_max_price_variation_id', '_max_regular_price_variation_id', '_max_sale_price_variation_id', '_max_variation_price', '_max_variation_regular_price', '_max_variation_sale_price', '_min_price_variation_id', '_min_regular_price_variation_id', '_min_sale_price_variation_id', '_min_variation_price', '_min_variation_regular_price', '_min_variation_sale_price', '_price', '_product_attributes', '_product_image_gallery', '_sku', '_regular_price', '_sale_price', '_sale_price_dates_from', '_sale_price_dates_to', '_sku', '_upsell_ids', '_thumbnail_id', '_weight', '_width', 'order');

            $woocommerce_print_products_options_meta_keys = array(
                        array(
                            'id'       => 'customMetaKeysSeparator',
                            'type'     => 'text',
                            'title'    => __( 'Custom Meta Keys Separator', 'woocommerce-print-products' ),
                            'subtitle'    => __( 'You can also use HTML like "br".', 'woocommerce-print-products' ),
                            'default'   => ' | ',
                        ),
                        'order' => array(
                            'id'      => 'customMetaKeys',
                            'type'    => 'sorter',
                            'title'   => 'Reorder / Disable Custom Data.',
                            'subtitle'   => 'You need to enable a custom meta field below first. Then reload this page.',
                            'options' => array(
                                'enabled'  => array(

                                ),
                                'disabled'  => array(

                                ),
                            ),
                        ),
            );

            $meta_keys_rearranged = array();
            foreach ($meta_keys as $key => $meta_key) {
                $meta_key = preg_replace('/[^\w-]/', '', $meta_key['meta_key']);

                if(in_array($meta_key, $meta_keys_to_exclude) || (substr( $meta_key, 0, 7 ) === "_oembed")) {
                    continue;
                }

                if(isset($woocommerce_print_products_options['showCustomMetaKey_' . $meta_key]) && $woocommerce_print_products_options['showCustomMetaKey_' . $meta_key] == "1") {
                    $meta_keys_rearranged[] = $meta_key;    
                }

                $woocommerce_print_products_options_meta_keys[$meta_key] = $meta_key;

                $woocommerce_print_products_options_meta_keys[] = 
                    array(
                        'id'       => 'showCustomMetaKey_' . $meta_key,
                        'type'     => 'checkbox',
                        'title'    => __( 'Show Custom Meta Key ' . $meta_key, 'woocommerce-print-products' ),
                        'default'   => 0,
                    );

                $woocommerce_print_products_options_meta_keys[] = 
                    array(
                        'id'       => 'showCustomMetaKeyText_' . $meta_key,
                        'type'     => 'text',
                        'title'    => __( 'Text before Custom Meta Key ' . $meta_key, 'woocommerce-print-products' ),
                        'default'   => $meta_key,
                        'required' => array('showCustomMetaKey_' . $meta_key, 'equals' , '1'),
                    );    
                    
                $woocommerce_print_products_options_meta_keys[] = 
                    array(
                        'id'       => 'showCustomMetaKeyACF_' . $meta_key,
                        'type'     => 'checkbox',
                        'title'    => __( 'Is ACF Field' . $meta_key, 'woocommerce-print-products' ),
                        'default'   => 0,
                        'required' => array('showCustomMetaKey_' . $meta_key, 'equals' , '1'),
                    );    
            }

            $woocommerce_print_products_options_meta_keys['order']['options']['enabled'] = $meta_keys_rearranged;
            set_transient( $transient_name, $woocommerce_print_products_options_meta_keys, WEEK_IN_SECONDS);

        } else {

            $meta_keys_rearranged = array();
            
            foreach ($woocommerce_print_products_options_meta_keys as $meta_key => $meta_val) {

                if( (stristr($meta_key, '___text') !== FALSE) || (stristr($meta_key, '___acf') !== FALSE) || $meta_key == "order") {
                    continue;
                }

                if(isset($woocommerce_print_products_options['showCustomMetaKey_' . $meta_key]) && $woocommerce_print_products_options['showCustomMetaKey_' . $meta_key] == "1") {
                    $meta_keys_rearranged[] = $meta_key;    
                }
            }
            $woocommerce_print_products_options_meta_keys['order']['options']['enabled'] = $meta_keys_rearranged;
        }
    }
    
    $args = array(
        'opt_name' => 'woocommerce_print_products_options',
        'use_cdn' => TRUE,
        'dev_mode' => FALSE,
        'display_name' => __('WooCommerce Print Products', 'woocommerce-print-products'),
        'display_version' => '1.8.8',
        'page_title' => __('WooCommerce Print Products', 'woocommerce-print-products'),
        'update_notice' => TRUE,
        'intro_text' => '',
        'footer_text' => '&copy; '.date('Y').' weLaunch',
        'admin_bar' => TRUE,
        'menu_type' => 'submenu',
        'menu_title' => __('Print Products', 'woocommerce-print-products'),
        'allow_sub_menu' => TRUE,
        'page_parent' => 'woocommerce',
        'page_parent_post_type' => 'your_post_type',
        'customizer' => FALSE,
        'default_mark' => '*',
        'hints' => array(
            'icon_position' => 'right',
            'icon_color' => 'lightgray',
            'icon_size' => 'normal',
            'tip_style' => array(
                'color' => 'light',
            ),
            'tip_position' => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect' => array(
                'show' => array(
                    'duration' => '500',
                    'event' => 'mouseover',
                ),
                'hide' => array(
                    'duration' => '500',
                    'event' => 'mouseleave unfocus',
                ),
            ),
        ),
        'output' => TRUE,
        'output_tag' => TRUE,
        'settings_api' => TRUE,
        'cdn_check_time' => '1440',
        'compiler' => TRUE,
        'page_permissions' => 'manage_options',
        'save_defaults' => TRUE,
        'show_import_export' => TRUE,
        'database' => 'options',
        'transient_time' => '3600',
        'network_sites' => TRUE,
    );

    global $weLaunchLicenses;
    if( (isset($weLaunchLicenses['woocommerce-print-products']) && !empty($weLaunchLicenses['woocommerce-print-products'])) || (isset($weLaunchLicenses['woocommerce-plugin-bundle']) && !empty($weLaunchLicenses['woocommerce-plugin-bundle'])) ) {
        $args['display_name'] = '<span class="dashicons dashicons-yes-alt" style="color: #9CCC65 !important;"></span> ' . $args['display_name'];
    } else {
        $args['display_name'] = '<span class="dashicons dashicons-dismiss" style="color: #EF5350 !important;"></span> ' . $args['display_name'];
    }

    $framework::setArgs( $opt_name, $args );

    $framework::setSection( $opt_name, array(
        'title'  => __( 'Print Products', 'woocommerce-print-products' ),
        'id'     => 'general',
        'desc'   => __( 'Need support? Please use the comment function on codecanyon.', 'woocommerce-print-products' ),
        'icon'   => 'el el-home',
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'General', 'woocommerce-print-products' ),
        'desc'       => sprintf('%s<a href="' . admin_url('tools.php?page=welaunch-framework') . '">%s</a>', esc_html__( 'To get auto updates please ', 'woocommerce-print-products' ), esc_html__( 'register your License here.', 'woocommerce-print-products' )),
        'id'         => 'general-settings',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'enable',
                'type'     => 'switch',
                'title'    => __( 'Enable', 'woocommerce-print-products' ),
                'subtitle' => __( 'Enable Print Products to use the options below', 'woocommerce-print-products' ),
                'default' => 1,
            ),
            array(
                'id'       => 'enableTemplates',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Templates', 'woocommerce-print-products' ),
                'subtitle' => __( 'Templates allow you to create special content between dates, that a user can select > <a href="https://www.welaunch.io/en/knowledge-base/faq/create-use-print-pdf-templates/" target="_blank">Learn More</a>.', 'woocommerce-print-products' ),
                'default' => 0,
                'required' => array('enable','equals','1'),
            ),
            array(
                'id'       => 'enableVariations',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Variations Support', 'woocommerce-print-products' ),
                'subtitle' => __( 'If enabled and user switches select field on variations it will generate PDF based on single variation.', 'woocommerce-print-products' ),
                'default' => 1,
                'required' => array('enable','equals','1'),
            ),
            array(
                'id'       => 'enableFrontend',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Frontend', 'woocommerce-print-products' ),
                'subtitle' => __( 'Show export buttons in frontend.', 'woocommerce-print-products' ),
                'default' => 1,
                'required' => array('enable','equals','1'),
            ),
            array(
                'id'       => 'enableBackend',
                'type'     => 'checkbox',
                'title'    => __( 'Enable backend', 'woocommerce-print-products' ),
                'subtitle' => __( 'Show export buttons in backend.', 'woocommerce-print-products' ),
                'default' => 1,
                'required' => array('enable','equals','1'),
            ),
            array(
                'id'       => 'enableBackendList',
                'type'     => 'checkbox',
                'title'    => __( 'Show export button in Backend Product List', 'woocommerce-print-products' ),
                'default' => 1,
                'required' => array('enableBackend','equals','1'),
            ),
            array(
                'id'       => 'enableBackendSingle',
                'type'     => 'checkbox',
                'title'    => __( 'Show export button in Backend Product Page.', 'woocommerce-print-products' ),
                'default' => 1,
                'required' => array('enableBackend','equals','1'),
            ),
            array(
                'id'       => 'enablePDF',
                'type'     => 'checkbox',
                'title'    => __( 'Enable PDF', 'woocommerce-print-products' ),
                'subtitle' => __( 'Show PDF export button.', 'woocommerce-print-products' ),
                'default' => 1,
                'required' => array('enable','equals','1'),
            ),
            array(
                'id'       => 'enableWord',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Word', 'woocommerce-print-products' ),
                'subtitle' => __( 'Show Word export button.', 'woocommerce-print-products' ),
                'default' => 1,
                'required' => array('enable','equals','1'),
            ),
            array(
                'id'       => 'enablePrint',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Print', 'woocommerce-print-products' ),
                'subtitle' => __( 'Show print export button.', 'woocommerce-print-products' ),
                'default' => 1,
                'required' => array('enable','equals','1'),
            ),
            array(
                'id'       => 'iconPosition',
                'type'     => 'select',
                'title'    => __( 'Icon position', 'woocommerce-print-products' ),
                'subtitle' => __( 'Choose the position of the icons on the product page.', 'woocommerce-print-products' ),
                'options'  => array(
                    'woocommerce_before_single_product_summary' => 'woocommerce_before_single_product_summary',
                    'woocommerce_single_product_summary' => 'woocommerce_single_product_summary',
                    'woocommerce_before_add_to_cart_form' => 'woocommerce_before_add_to_cart_form',
                    'woocommerce_before_add_to_cart_button' => 'woocommerce_before_add_to_cart_button',
                    'woocommerce_before_add_to_cart_quantity' => 'woocommerce_before_add_to_cart_quantity',
                    'woocommerce_after_add_to_cart_quantity' => 'woocommerce_after_add_to_cart_quantity',
                    'woocommerce_after_add_to_cart_button' => 'woocommerce_after_add_to_cart_button',
                    'woocommerce_product_meta_start' => 'woocommerce_product_meta_start',
                    'woocommerce_product_meta_end' => 'woocommerce_product_meta_end',
                    'woocommerce_after_single_product_summary' => 'woocommerce_after_single_product_summary',
                    'woocommerce_after_single_product' => 'woocommerce_after_single_product',
                    'woocommerce_after_main_content' => __('After Main Product', 'woocommerce-print-products'),
                    'the_content' => __('The content', 'woocommerce-print-products'),
                ),
                'default' => 'woocommerce_product_meta_end',
                'required' => array('enable','equals','1'),
            ),
            array(
                'id'       => 'iconPositionPriority',
                'type'     => 'spinner',
                'title'    => __( 'Icon Priority', 'woocommerce-print-products' ),
                'min'      => '1',
                'step'     => '1',
                'max'      => '999',
                'default'  => '90',
                'required' => array('enable','equals','1'),
            ),
            array(
                'id'       => 'iconSize',
                'type'     => 'select',
                'title'    => __( 'Icon Size', 'woocommerce-print-products' ),
                'subtitle' => __( 'Choose the icon size.', 'woocommerce-print-products' ),
                'options'  => array(
                    'fa-lg' => __('Large', 'woocommerce-print-products' ),
                    'fa-2x' => __('2x larger', 'woocommerce-print-products' ),
                    'fa-3x' => __('3x larger', 'woocommerce-print-products' ),
                    'fa-4x' => __('4x larger', 'woocommerce-print-products' ),
                    'fa-5x' => __('5x larger', 'woocommerce-print-products' ),
                    //'productAttribute' => __('Show best Selling Products', 'woocommerce-custom-tabs' ),
                ),
                 'default' => 'fa-2x',
                 'required' => array('enable','equals','1'),
            ),
            array(
                'id'       => 'iconType',
                'type'     => 'select',
                'title'    => __( 'Icon Type', 'woocommerce-print-products' ),
                'subtitle' => __( 'Display icon or as button.', 'woocommerce-print-products' ),
                'options'  => array(
                    'icon' => __('Icon Only', 'woocommerce-print-products' ),
                    'button' => __('Button', 'woocommerce-print-products' ),
                ),
                 'default' => 'icon',
                 'required' => array('enable','equals','1'),
            ),
                array(
                    'id'       => 'iconTypeButtonPDFText',
                    'type'     => 'text',
                    'title'    => __('PDF Button Text', 'woocommerce-print-products'),
                    'default'  => __('Export as PDF', 'woocommerce-print-products'),
                    'required' => array('iconType','equals','button'),
                ), 
                array(
                    'id'       => 'iconTypeButtonWordText',
                    'type'     => 'text',
                    'title'    => __('Word Button Text', 'woocommerce-print-products'),
                    'default'  => __('Export as Word', 'woocommerce-print-products'),
                    'required' => array('iconType','equals','button'),
                ), 
                array(
                    'id'       => 'iconTypeButtonPrintText',
                    'type'     => 'text',
                    'title'    => __('Print Button Text', 'woocommerce-print-products'),
                    'default'  => __('Print', 'woocommerce-print-products'),
                    'required' => array('iconType','equals','button'),
                ), 



            array(
                'id'       => 'iconDisplay',
                'type'     => 'select',
                'title'    => __( 'Icon Display', 'woocommerce-print-products' ),
                'subtitle' => __( 'Choose how the icons should appear.', 'woocommerce-print-products' ),
                'options'  => array(
                    'horizontal' => __('Horizontal', 'woocommerce-print-products' ),
                    'vertical' => __('Vertical', 'woocommerce-print-products' ),
                ),
                'default' => 'horizontal',
                'required' => array('enable','equals','1'),
            ),
        )
    ) );


    $framework::setSection( $opt_name, array(
        'title'      => __( 'Exclusions', 'woocommerce-print-products' ),
        // 'desc'       => __( '', 'woocommerce-print-products' ),
        'id'         => 'exclude',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'     =>'excludeProductCategories',
                'type' => 'select',
                'data' => 'categories',
                'args' => array('taxonomy' => array('product_cat')),
                'multi' => true,
                'title' => __('Exclude Product Categories', 'woocommerce-print-products'), 
                'subtitle' => __('Which product categories should be excluded by the catalog mode.', 'woocommerce-print-products'),
                'required' => array('enable','equals','1'),
            ),            
            array(
                'id'       => 'excludeProductCategoriesRevert',
                'type'     => 'checkbox',
                'title'    => __( 'Revert Categories Exclusion', 'woocommerce-print-products' ),
                'subtitle' => __( 'Instead of exclusion it will include.', 'woocommerce-print-products' ),
                'required' => array('enable','equals','1'),
            ),
            array(
                'id'     =>'excludeProducts',
                'type' => 'select',
                'data' => 'posts',
                'args' => array('post_type' => array('product'), 'posts_per_page' => -1),
                'ajax'  => true,
                'multi' => true,
                'title' => __('Exclude Products', 'woocommerce-print-products'), 
                'subtitle' => __('Which products should be excluded by the catalog mode.', 'woocommerce-print-products'),
                'required' => array('enable','equals','1'),
            ),
            array(
                'id'       => 'excludeProductsRevert',
                'type'     => 'checkbox',
                'title'    => __( 'Revert Products Exclusion', 'woocommerce-print-products' ),
                'subtitle' => __( 'Instead of exclusion it will include.', 'woocommerce-print-products' ),
                'required' => array('enable','equals','1'),
            ),
        )
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Watermark', 'woocommerce-print-products' ),
        'desc' => __( 'Add a watermark to your catalog.', 'woocommerce-print-products' ), 
        'id'         => 'watermark',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'watermarkEnable',
                'type'     => 'switch',
                'title'    => __( 'Enable Watermark', 'woocommerce-print-products' ),
                'subtitle'    => __( 'Show a Watermark in your PDF.', 'woocommerce-print-products' ),
                'default' => 0,
            ),
            array(
                'id'     =>'watermarkType',
                'type'  => 'select',
                'title' => __('Watermark Type', 'woocommerce-print-products'), 
                'options'  => array(
                    'text' => __('Text', 'woocommerce-print-products' ),
                    'image' => __('Image', 'woocommerce-print-products' ),
                ),
                'default' => 'text',
                'required' => array('watermarkEnable','equals','1'),
            ),
            array(
                'id'     =>'watermarkImage',
                'type' => 'media',
                'url'      => true,
                'title' => __('Watermark Image', 'woocommerce-print-products'), 
                'required' => array('watermarkType','equals','image'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'       => 'watermarkText',
                'type'     => 'text',
                'title'    => __('Watermark Text', 'woocommerce-print-products'),
                'subtitle' => __('Enter your watermark text here.', 'woocommerce-print-products'),
                'default'  => 'CONFIDENTIAL',
                'required' => array('watermarkType','equals','text'),
            ), 
            array(
                'id'       => 'watermarkTransparency',
                'type'     => 'text',
                'title'    => __('Watermark Transparency', 'woocommerce-print-products'),
                'subtitle' => __('A value from 0 to 1. Example: 0.2', 'woocommerce-print-products'),
                'default'  => '0.2',
                'required' => array('watermarkEnable','equals','1'),
            ), 
        )
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Header', 'woocommerce-print-products' ),
        // 'desc'       => __( '', 'woocommerce-print-products' ),
        'id'         => 'header',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'enableHeader',
                'type'     => 'switch',
                'title'    => __( 'Enable', 'woocommerce-print-products' ),
                'subtitle' => __( 'Enable header', 'woocommerce-print-products' ),
                'default' => 1,
            ),
            array(
                'id'     =>'headerBackgroundColor',
                'type' => 'color',
                'title' => __('Header background color', 'woocommerce-print-products'), 
                'validate' => 'color',
                'required' => array('enableHeader','equals','1'),
                'default' => '#f7f7f7',
            ),
            array(
                'id'     =>'headerTextColor',
                'type'  => 'color',
                'title' => __('Header text color', 'woocommerce-print-products'), 
                'validate' => 'color',
                'required' => array('enableHeader','equals','1'),
                'default' => '#000000',
            ),
            array(
                'id'     =>'headerLayout',
                'type'  => 'select',
                'title' => __('Header Layout', 'woocommerce-print-products'), 
                'required' => array('enableHeader','equals','1'),
                'options'  => array(
                    'oneCol' => __('1/1', 'woocommerce-print-products' ),
                    'twoCols' => __('1/2 + 1/2', 'woocommerce-print-products' ),
                    'threeCols' => __('1/3 + 1/3 + 1/3', 'woocommerce-print-products' ),
                    'fourCols' => __('1/4 + 1/4 + 1/4 + 1/4', 'woocommerce-print-products' ),
                ),
                'default' => 'twoCols',
            ),
            array(
                'id'     =>'headerTopMargin',
                'type'     => 'spinner', 
                'title'    => __('Header Margin', 'woocommerce-print-products'),
                'default'  => '30',
                'min'      => '0',
                'step'     => '1',
                'max'      => '200',
            ),
            array(
                'id'     =>'headerHeight',
                'type'     => 'spinner', 
                'title'    => __('Header Height', 'woocommerce-print-products'),
                'default'  => '40',
                'min'      => '0',
                'step'     => '1',
                'max'      => '200',
            ),
            array(
                'id'     =>'headerVAlign',
                'type'  => 'select',
                'title' => __('Vertical Align', 'woocommerce-print-products'), 
                'required' => array('enableHeader','equals','1'),
                'options'  => array(
                    'top' => __('Top', 'woocommerce-print-products' ),
                    'middle' => __('Middle', 'woocommerce-print-products' ),
                    'bottom' => __('Bottom', 'woocommerce-print-products' ),
                ),
                'default' => 'middle',
            ),
            array(
                'id'     =>'headerTopLeft',
                'type'  => 'select',
                'title' => __('Top Left Header', 'woocommerce-print-products'), 
                'required' => array('enableHeader','equals','1'),
                'options'  => array(
                    'none' => __('None', 'woocommerce-print-products' ),
                    'bloginfo' => __('Blog information', 'woocommerce-print-products' ),
                    'text' => __('Custom text', 'woocommerce-print-products' ),
                    'pagenumber' => __('Pagenumber', 'woocommerce-print-products' ),
                    'productinfo' => __('Product info', 'woocommerce-print-products' ),
                    'categories' => __('Product Categories', 'woocommerce-print-products' ),
                    'categorydescription' => __('Category Description', 'woocommerce-print-products' ),
                    'image' => __('Image', 'woocommerce-print-products' ),
                    'exportinfo' => __('Export Information', 'woocommerce-print-products' ),
                    'qr' => __('QR-Code', 'woocommerce-print-products' ),
                ),
                'default' => 'text'
            ),
            array(
                'id'     =>'headerTopLeftText',
                'type'  => 'editor',
                'title' => __('Top Left Header Text', 'woocommerce-print-products'), 
                'subtitle' => __('Use {{current_date}}, {{sku}}, {{product_name}}', 'woocommerce-print-products'),
                'required' => array('headerTopLeft','equals','text'),
                'args'   => array(
                    'teeny'            => false,
                ),
                'default' => 'I am a custom Text'
            ),
            array(
                'id'     =>'headerTopLeftImage',
                'type' => 'media',
                'url'      => true,
                'title' => __('Top Left Header Image', 'woocommerce-print-products'), 
                'required' => array('headerTopLeft','equals','image'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'headerTopMiddle',
                'type'  => 'select',
                'title' => __('Top Middle Header', 'woocommerce-print-products'), 
                'required' => array('headerLayout','equals', array('fourCols', 'threeCols') ),
                'options'  => array(
                    'none' => __('None', 'woocommerce-print-products' ),
                    'bloginfo' => __('Blog information', 'woocommerce-print-products' ),
                    'text' => __('Custom text', 'woocommerce-print-products' ),
                    'pagenumber' => __('Pagenumber', 'woocommerce-print-products' ),
                    'productinfo' => __('Product info', 'woocommerce-print-products' ),
                    'categories' => __('Product Categories', 'woocommerce-print-products' ),
                    'categorydescription' => __('Category Description', 'woocommerce-print-products' ),
                    'image' => __('Image', 'woocommerce-print-products' ),
                    'exportinfo' => __('Export Information', 'woocommerce-print-products' ),
                    'qr' => __('QR-Code', 'woocommerce-print-products' ),
                ),
            ),
            array(
                'id'     =>'headerTopMiddleText',
                'type'  => 'editor',
                'title' => __('Top Middle Header Text', 'woocommerce-print-products'), 
                'subtitle' => __('Use {{current_date}}, {{sku}}, {{product_name}}', 'woocommerce-print-products'),
                'required' => array('headerTopMiddle','equals','text'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'headerTopMiddleImage',
                'type' => 'media',
                'url'      => true,
                'title' => __('Top Middle Header Image', 'woocommerce-print-products'), 
                'required' => array('headerTopMiddle','equals','image'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
           array(
                'id'     =>'headerTopMiddle2',
                'type'  => 'select',
                'title' => __('Top Middle Header 2', 'woocommerce-print-products'), 
                'required' => array('headerLayout','equals','fourCols'),
                'options'  => array(
                    'none' => __('None', 'woocommerce-print-products' ),
                    'bloginfo' => __('Blog information', 'woocommerce-print-products' ),
                    'text' => __('Custom text', 'woocommerce-print-products' ),
                    'pagenumber' => __('Pagenumber', 'woocommerce-print-products' ),
                    'productinfo' => __('Product info', 'woocommerce-print-products' ),
                    'categories' => __('Product Categories', 'woocommerce-print-products' ),
                    'categorydescription' => __('Category Description', 'woocommerce-print-products' ),
                    'image' => __('Image', 'woocommerce-print-products' ),
                    'exportinfo' => __('Export Information', 'woocommerce-print-products' ),
                    'qr' => __('QR-Code', 'woocommerce-print-products' ),
                ),
            ),
            array(
                'id'     =>'headerTopMiddle2Text',
                'type'  => 'editor',
                'title' => __('Top Middle Header 2 Text', 'woocommerce-print-products'), 
                'required' => array('headerTopMiddle2','equals','text'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'headerTopMiddle2Image',
                'type' => 'media',
                'url'      => true,
                'title' => __('Top Middle Header 2 Image', 'woocommerce-print-products'), 
                'required' => array('headerTopMiddle2','equals','image'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'headerTopRight',
                'type'  => 'select',
                'title' => __('Top Right Header', 'woocommerce-print-products'), 
                'required' => array('headerLayout','equals',array('threeCols', 'twoCols', 'fourCols' )),
                'options'  => array(
                    'none' => __('None', 'woocommerce-print-products' ),
                    'bloginfo' => __('Blog information', 'woocommerce-print-products' ),
                    'text' => __('Custom text', 'woocommerce-print-products' ),
                    'pagenumber' => __('Pagenumber', 'woocommerce-print-products' ),
                    'productinfo' => __('Product info', 'woocommerce-print-products' ),
                    'categories' => __('Product Categories', 'woocommerce-print-products' ),
                    'categorydescription' => __('Category Description', 'woocommerce-print-products' ),
                    'image' => __('Image', 'woocommerce-print-products' ),
                    'exportinfo' => __('Export Information', 'woocommerce-print-products' ),
                    'qr' => __('QR-Code', 'woocommerce-print-products' ),
                ),
                'default' => 'bloginfo'
            ),
            array(
                'id'     =>'headerTopRightText',
                'type'  => 'editor',
                'title' => __('Top Right Header Text', 'woocommerce-print-products'), 
                'subtitle' => __('Use {{current_date}}, {{sku}}, {{product_name}}', 'woocommerce-print-products'),
                'required' => array('headerTopRight','equals','text'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'headerTopRightImage',
                'type' => 'media',
                'url'      => true,
                'title' => __('Top Right Header Image', 'woocommerce-print-products'), 
                'required' => array('headerTopRight','equals','image'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'headerTextAfterHeader',
                'type'  => 'editor',
                'title' => __('Text after Header', 'woocommerce-print-products'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
        )
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Layout', 'woocommerce-print-products' ),
        'id'         => 'layout',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'format',
                'type'     => 'select',
                'title'    => __( 'Format', 'woocommerce-print-products' ),
                'subtitle' => __( 'Choose a pre-defined page size. A4 is recommended!', 'woocommerce-print-products' ),
                'options'  => array(
                    'A4' => __('A4', 'woocommerce-print-products'),
                    'A4-L' => __('A4 Landscape', 'woocommerce-print-products'),
                    'A0' => __('A0', 'woocommerce-print-products'),
                    'A1' => __('A1', 'woocommerce-print-products'),
                    'A3' => __('A3', 'woocommerce-print-products'),
                    'A5' => __('A5', 'woocommerce-print-products'),
                    'A6' => __('A6', 'woocommerce-print-products'),
                    'A7' => __('A7', 'woocommerce-print-products'),
                    'A8' => __('A8', 'woocommerce-print-products'),
                    'A9' => __('A9', 'woocommerce-print-products'),
                    'A10' => __('A10', 'woocommerce-print-products'),
                    'Letter' => __('Letter', 'woocommerce-print-products'),
                    'Legal' => __('Legal', 'woocommerce-print-products'),
                    'Executive' => __('Executive', 'woocommerce-print-products'),
                    'Folio' => __('Folio', 'woocommerce-print-products'),
                ),
                'default' => 'A4',
            ),
            // array(
            //     'id'       => 'orientation',
            //     'type'     => 'select',
            //     'title'    => __( 'Orientation', 'woocommerce-print-products' ),
            //     'subtitle' => __( 'Choose landscape or portrait. Portrait recommended!', 'woocommerce-print-products' ),
            //     'options'  => array(
            //         'P' => __('P', 'woocommerce-print-products'),
            //         'L' => __('L', 'woocommerce-print-products'),
            //     ),
            //     'default' => 'P',
            // ),
            array(
                'id'       => 'layout',
                'type'     => 'image_select',
                'title'    => __( 'Select Layout', 'woocommerce-print-products' ),
                'options'  => array(
                    '1'      => array(
                        'img'   => plugin_dir_url( __FILE__ ) . 'img/1.png'
                    ),
                    '2'      => array(
                        'img'   => plugin_dir_url( __FILE__ ). 'img/2.png'
                    ),
                    '3'      => array(
                        'img'   => plugin_dir_url( __FILE__ ). 'img/3.png'
                    ),
                    '4'      => array(
                        'img'   => plugin_dir_url( __FILE__ ). 'img/4.png'
                    ),
                    '5'      => array(
                        'img'   => plugin_dir_url( __FILE__ ). 'img/5.png'
                    ),
                    '6'      => array(
                        'img'   => plugin_dir_url( __FILE__ ). 'img/6.png'
                    ),
                    '7'      => array(
                        'img'   => plugin_dir_url( __FILE__ ). 'img/7.png'
                    ),
                    '8'      => array(
                        'img'   => plugin_dir_url( __FILE__ ). 'img/8.png'
                    ),
                    '9'      => array(
                        'img'   => plugin_dir_url( __FILE__ ). 'img/9.png'
                    ),
                ),
                'default' => '2'
            ),
            array(
                'id'     =>'featuredImageBackgroundColor',
                'type'  => 'color',
                'url'      => true,
                'title' => __('Featured Image Background Color', 'woocommerce-print-products'), 
                'validate' => 'color',
                'required' => array('layout','equals','2'),
                'default' => '#EEEEEE',
            ),
            array(
                'id'      => 'informationOrder',
                'type'    => 'sorter',
                'title'   => 'Reorder / Disable some blocks.',
                'options' => array(
                    'enabled'  => array(
                        'variations' => 'Variations',
                        'gallery_images' => 'Gallery Images',
                        'description' => 'Description',
                        'pagebreak-1' => 'Pagebreak',
                        'attributes_table' => 'Attributes',
                        'pagebreak-2' => 'Pagebreak',
                        'customTabs' => 'Custom Tabs',
                        'reviews' => 'Reviews',
                        'pagebreak-3' => 'Pagebreak',
                        'upsells' => 'Upsells',
                    ),
                    'disabled' => array(
                        'notes' => 'Notes',
                        'short_description' => 'Short Description',
                        'pagebreak-4' => 'Pagebreak',
                        'pagebreak-5' => 'Pagebreak',
                        'pagebreak-6' => 'Pagebreak',
                        'pagebreak-7' => 'Pagebreak',
                        'custom-1' => 'Custom Filter 1',
                        'custom-2' => 'Custom Filter 2',
                        'custom-3' => 'Custom Filter 3',

                    )
                ),
            ),
            array(
                'id'     =>'textAlign',
                'type'  => 'select',
                'title' => __('Text Align', 'woocommerce-print-products'), 
                'options'  => array(
                    'left' => __('Left', 'woocommerce-print-products' ),
                    'center' => __('Center', 'woocommerce-print-products' ),
                    'right' => __('Right', 'woocommerce-print-products' ),
                ),
                'default' => 'center'
            ),
            array(
                'id'     =>'backgroundColor',
                'type'  => 'color',
                'title' => __('Background color', 'woocommerce-print-products'), 
                'validate' => 'color',
            ),
            array(
                'id'     =>'textColor',
                'type'  => 'color',
                'title' => __('Text Color', 'woocommerce-print-products'), 
                'validate' => 'color',
            ),
            array(
                'id'     =>'linkColor',
                'type'  => 'color',
                'title' => __('Link Color', 'woocommerce-print-products'), 
                'validate' => 'color',
            ),
            array(
                'id'     =>'fontFamily',
                'type'  => 'select',
                'title' => __('Default Font', 'woocommerce-print-products'), 
                'options'  => array(
                    'dejavusans' => __('Sans', 'woocommerce-print-products' ),
                    'dejavuserif' => __('Serif', 'woocommerce-print-products' ),
                    'dejavusansmono' => __('Mono', 'woocommerce-print-products' ),
                    'droidsans' => __('Droid Sans', 'woocommerce-print-products'),
                    'droidserif' => __('Droid Serif', 'woocommerce-print-products'),
                    'lato' => __('Lato', 'woocommerce-print-products'),
                    'lora' => __('Lora', 'woocommerce-print-products'),
                    'martelsans' => __('Martel Sans', 'woocommerce-print-products'),
                    'merriweather' => __('Merriweather', 'woocommerce-print-products'),
                    'montserrat' => __('Montserrat', 'woocommerce-print-products'),
                    'mulish' => __('Mulish', 'woocommerce-print-products'),
                    'opensans' => __('Open sans', 'woocommerce-print-products'),
                    'opensanscondensed' => __('Open Sans Condensed', 'woocommerce-print-products'),
                    'oswald' => __('Oswald', 'woocommerce-print-products'),
                    'ptsans' => __('PT Sans', 'woocommerce-print-products'),
                    'poppins' => __('Poppins', 'woocommerce-print-products'),
                    'sourcesanspro' => __('Source Sans Pro', 'woocommerce-print-products'),
                    'slabo' => __('Slabo', 'woocommerce-print-products'),
                    'roboto' => __('Roboto', 'woocommerce-print-products'),
                    'raleway' => __('Raleway', 'woocommerce-print-products'),
                ),
            ),
            array(
                'id'     =>'fontSize',
                'type'     => 'spinner', 
                'title'    => __('Default font size', 'woocommerce-print-products'),
                'default'  => '11',
                'min'      => '0',
                'step'     => '1',
                'max'      => '40',
            ),
            array(
                'id'     =>'fontLineHeight',
                'type'     => 'spinner', 
                'title'    => __('Default line height', 'woocommerce-print-products'),
                'default'  => '16',
                'min'      => '0',
                'step'     => '1',
                'max'      => '40',
            ),
            array(
                'id'     =>'headingsFontFamily',
                'type'  => 'select',
                'title' => __('Headings Font', 'woocommerce-print-products'), 
                'options'  => array(
                    'dejavusans' => __('Sans', 'woocommerce-print-products' ),
                    'dejavuserif' => __('Serif', 'woocommerce-print-products' ),
                    'dejavusansmono' => __('Mono', 'woocommerce-print-products' ),
                    'droidsans' => __('Droid Sans', 'woocommerce-print-products'),
                    'droidserif' => __('Droid Serif', 'woocommerce-print-products'),
                    'lato' => __('Lato', 'woocommerce-print-products'),
                    'lora' => __('Lora', 'woocommerce-print-products'),
                    'martelsans' => __('Martel Sans', 'woocommerce-print-products'),
                    'merriweather' => __('Merriweather', 'woocommerce-print-products'),
                    'montserrat' => __('Montserrat', 'woocommerce-print-products'),
                    'mulish' => __('Mulish', 'woocommerce-print-products'),
                    'opensans' => __('Open sans', 'woocommerce-print-products'),
                    'opensanscondensed' => __('Open Sans Condensed', 'woocommerce-print-products'),
                    'oswald' => __('Oswald', 'woocommerce-print-products'),
                    'ptsans' => __('PT Sans', 'woocommerce-print-products'),
                    'poppins' => __('Poppins', 'woocommerce-print-products'),
                    'sourcesanspro' => __('Source Sans Pro', 'woocommerce-print-products'),
                    'slabo' => __('Slabo', 'woocommerce-print-products'),
                    'roboto' => __('Roboto', 'woocommerce-print-products'),
                    'raleway' => __('Raleway', 'woocommerce-print-products'),
                ),
            ),
            array(
                'id'     =>'headingsFontSize',
                'type'     => 'spinner', 
                'title'    => __('Headings font size', 'woocommerce-print-products'),
                'default'  => '16',
                'min'      => '1',
                'step'     => '1',
                'max'      => '100',
            ),
            array(
                'id'     =>'headingsLineHeight',
                'type'     => 'spinner', 
                'title'    => __('Headings line height', 'woocommerce-print-products'),
                'default'  => '22',
                'min'      => '1',
                'step'     => '1',
                'max'      => '100',
            ),

        )
    ) );
        

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Data to show', 'woocommerce-print-products' ),
        'id'         => 'data',
        'subsection' => true,
        'fields'     =>         array(
            array(
                'id'       => 'showImage',
                'type'     => 'switch',
                'title'    => __( 'Show Product Image', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
                array(
                    'id'     =>'showImageSize',
                    'type'     => 'spinner', 
                    'title'    => __('Product Image Size', 'woocommerce-print-products'),
                    'default'  => '350',
                    'min'      => '1',
                    'step'     => '1',
                    'max'      => '99999',
                    'required' => array('showImage','equals','1'),
                ),
            array(
                'id'       => 'showGalleryImages',
                'type'     => 'switch',
                'title'    => __( 'Show Gallery Images', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
                array(
                    'id'       => 'showGalleryImagesIntroTitle',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Gallery Images Intro Title', 'woocommerce-print-products' ),
                    'default'   => 1,
                    'required' => array('showGalleryImages','equals','1'),
                ),
                    array(
                        'id'       => 'showGalleryImagesIntroTitleText',
                        'type'     => 'text',
                        'title'    => __('Gallery Imagage Headline', 'woocommerce-print-products'),
                        'default'  => __( 'Gallery Images', 'woocommerce-print-products' ),
                        'required' => array('showGalleryImagesIntroTitle','equals','1'),
                    ), 
                array(
                    'id'     =>'showGalleryImagesSize',
                    'type'     => 'spinner', 
                    'title'    => __('Gallery Image Size', 'woocommerce-print-products'),
                    'default'  => '200',
                    'min'      => '1',
                    'step'     => '1',
                    'max'      => '99999',
                    'required' => array('showGalleryImages','equals','1'),
                ),
                array(
                    'id'       => 'showGalleryImageSizeType',
                    'type'     => 'text',
                    'title'    => __('Gallery Image Size Type', 'woocommerce-print-products'),
                    'subtitle' => __('You can use e.g. full, large, thumbnail, woocommerce_single, shop_single, shop_catalog...', 'woocommerce-print-products'),
                    'default'  => 'shop_single',
                    'required' => array('showGalleryImages','equals','1'),
                ), 
                array(
                    'id'     =>'showGalleryImagesColumns',
                    'type'     => 'spinner', 
                    'title'    => __('Gallery Image Columns', 'woocommerce-print-products'),
                    'default'  => '3',
                    'min'      => '1',
                    'step'     => '1',
                    'max'      => '6',
                    'required' => array('showGalleryImages','equals','1'),
                ),
                array(
                    'id'     =>'showGalleryImagesMax',
                    'type'     => 'spinner', 
                    'title'    => __('Max Gallery Images', 'woocommerce-print-products'),
                    'default'  => '4',
                    'min'      => '1',
                    'step'     => '1',
                    'max'      => '99999',
                    'required' => array('showGalleryImages','equals','1'),
                ),
                array(
                    'id'       => 'showGalleryImagesTitle',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Gallery Images Title', 'woocommerce-print-products' ),
                    'default'   => 0,
                    'required' => array('showGalleryImages','equals','1'),
                ),
                array(
                    'id'       => 'showGalleryImagesCaption',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Gallery Images Caption', 'woocommerce-print-products' ),
                    'default'   => 0,
                    'required' => array('showGalleryImages','equals','1'),
                ),
                array(
                    'id'       => 'showGalleryImagesAlt',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Gallery Images Alt Text', 'woocommerce-print-products' ),
                    'default'   => 0,
                    'required' => array('showGalleryImages','equals','1'),
                ),
                array(
                    'id'       => 'showGalleryImagesDescription',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Gallery Images Description', 'woocommerce-print-products' ),
                    'default'   => 0,
                    'required' => array('showGalleryImages','equals','1'),
                ),
            array(
                'id'       => 'showTitle',
                'type'     => 'switch',
                'title'    => __( 'Show Product Title', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
                array(
                    'id'       => 'showTitleLink',
                    'type'     => 'checkbox',
                    'title'    => __( 'Link Product Title', 'woocommerce-print-products' ),
                    'default'   => 0,
                    'required' => array('showTitle','equals','1'),
                ),
            array(
                'id'       => 'showPrice',
                'type'     => 'switch',
                'title'    => __( 'Show Product Price', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
                array(
                    'id'       => 'showPriceTiered',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Tiered Pricing', 'woocommerce-print-products' ),
                    'subtitle'    => __( 'Requires tiered pricing plugin: https://woocommerce.com/products/tiered-pricing-table-for-woocommerce/', 'woocommerce-print-products' ),
                    'default'   => 1,
                    'required' => array('showPrice','equals','1'),
                ),
            array(
                'id'       => 'showShortDescription',
                'type'     => 'switch',
                'title'    => __( 'Show Short Description', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
                array(
                    'id'       => 'showShortDescriptionStripImages',
                    'type'     => 'checkbox',
                    'title'    => __( 'Strip Short Description Images?', 'woocommerce-print-products' ),
                    'default'   => 0,
                    'required' => array('showShortDescription','equals','1'),
                ),
            array(
                'id'       => 'showMetaFreetext',
                'type'     => 'switch',
                'title'    => __( 'Show Meta Free Text', 'woocommerce-print-products' ),
                'default'   => 0,
            ),
                array(
                    'id'       => 'metaFreeText',
                    'type'  => 'editor',
                    'title' => __('Meta Free Text', 'woocommerce-print-products'),
                    'args'   => array(
                        'teeny'            => false,
                    ),
                    'required' => array('showMetaFreetext','equals','1'),
                ),
            array(
                'id'       => 'showSKU',
                'type'     => 'switch',
                'title'    => __( 'Show Product SKU', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
                array(
                    'id'       => 'showSKUMoveUnderTitle',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show SKU under Title', 'woocommerce-print-products' ),
                    'default'   => 0,
                    'required' => array('showSKU','equals','1'),
                ),
            array(
                'id'       => 'showStock',
                'type'     => 'switch',
                'title'    => __( 'Show Product Stock Status', 'woocommerce-print-products' ),
                'default'   => 0,
            ),
            array(
                'id'       => 'showCategories',
                'type'     => 'switch',
                'title'    => __( 'Show Product Categories', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
            array(
                'id'       => 'showTags',
                'type'     => 'switch',
                'title'    => __( 'Show Product Tags', 'woocommerce-print-products' ),
                'default'   => 1,
            ),

            array(
                'id'       => 'showReadMore',
                'type'     => 'switch',
                'title'    => __( 'Show Read More Link', 'woocommerce-print-products' ),
                'default'   => 0,
            ),
                array(
                    'id'       => 'showReadMoreText',
                    'type'     => 'text',
                    'title'    => __( 'Read More Text', 'woocommerce-print-products' ),
                    'default'  => __( 'Read More', 'woocommerce-print-products' ),
                    'required' => array('showReadMore','equals','1'),
                ),

            array(
                'id'       => 'showQR',
                'type'     => 'switch',
                'title'    => __( 'Show QR-Code', 'woocommerce-print-products' ),
                'default'   => 0,
            ),
                array(
                    'id'       => 'qrSize',
                    'type'     => 'text',
                    'title'    => __( 'QR Code Size', 'woocommerce-print-products' ),
                    'subtitle' => __('Float value (e.g. 0.6 or 1.5). Default is 0.8.', 'woocommerce-print-products'),
                    'default'  => '0.8',
                    'required' => array('showQR','equals','1'),
                ),

            array(
                'id'       => 'showBarcode',
                'type'     => 'switch',
                'title'    => __( 'Show Barcode', 'woocommerce-print-products' ),
                'default'   => 0,
            ),
                array(
                    'id'     =>'barcodeType',
                    'type'  => 'select',
                    'title' => __('Barcode Type', 'woocommerce-print-products'), 
                    'options'  => array(
                        'EAN13' => 'EAN13',
                        'ISBN' => 'ISBN',
                        'ISSN' => 'ISSN',
                        'UPCA' => 'UPCA',
                        'UPCE' => 'UPCE',
                        'EAN8' => 'EAN8',
                        'EAN13P2' => 'EAN13P2',
                        'ISBNP2' => 'ISBNP2',
                        'ISSNP2' => 'ISSNP2',
                        'UPCAP2' => 'UPCAP2',
                        'UPCEP2' => 'UPCEP2',
                        'EAN8P2' => 'EAN8P2',
                        'EAN13P5' => 'EAN13P5',
                        'ISBNP5' => 'ISBNP5',
                        'ISSNP5' => 'ISSNP5',
                        'UPCAP5' => 'UPCAP5',
                        'UPCEP5' => 'UPCEP5',
                        'EAN8P5' => 'EAN8P5',
                        'IMB' => 'IMB',
                        'RM4SCC' => 'RM4SCC',
                        'KIX' => 'KIX',
                        'POSTNET' => 'POSTNET',
                        'PLANET' => 'PLANET',
                        'C128A' => 'C128A',
                        'C128B' => 'C128B',
                        'C128C' => 'C128C',
                        'EAN128A' => 'EAN128A',
                        'EAN128B' => 'EAN128B',
                        'EAN128C' => 'EAN128C',
                        'C39' => 'C39',
                        'C39+' => 'C39+',
                        'C39E' => 'C39E',
                        'C39E+' => 'C39E+',
                        'S25' => 'S25',
                        'S25+' => 'S25+',
                        'I25' => 'I25',
                        'I25+' => 'I25+',
                        'I25B' => 'I25B',
                        'I25B+' => 'I25B+',
                        'C93' => 'C93',
                        'MSI' => 'MSI',
                        'MSI+' => 'MSI+',
                        'CODABAR' => 'CODABAR',
                        'CODE11' => 'CODE11',
                    ),
                    'default' => 'EAN13',
                    'required' => array('showBarcode','equals','1'),
                ),
                array(
                    'id'       => 'barcodeAttributeKey',
                    'type'     => 'text',
                    'title'    => __( 'Your Barcode Attribute Key', 'woocommerce-print-products' ),
                    'subtitle' => __('On Product level you need an attribute, where the bar code itself is stored. Enter the slug with pa_ like pa_ean', 'woocommerce-print-products'),
                    'default'  => 'pa_ean',
                    'required' => array('showBarcode','equals','1'),
                ),
                array(
                    'id'       => 'barcodeSize',
                    'type'     => 'text',
                    'title'    => __( 'Barcode Size', 'woocommerce-print-products' ),
                    'subtitle' => __('Float value (e.g. 0.6 or 1.5). Default is 1.', 'woocommerce-print-products'),
                    'default'  => '1',
                    'required' => array('showBarcode','equals','1'),
                ),

            array(
                'id'       => 'showDescription',
                'type'     => 'switch',
                'title'    => __( 'Show Product Description', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
                array(
                    'id'       => 'showDescriptionTitle',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Description Title', 'woocommerce-print-products' ),
                    'default'   => 1,
                    'required' => array('showDescription','equals','1'),
                ),
                array(
                    'id'       => 'showDescriptionTitleValue',
                    'type'     => 'text',
                    'title'    => __('Description Title', 'woocommerce-print-products'),
                    'default'  => __( 'Product Description', 'woocommerce-print-products' ),
                    'required' => array('showDescriptionTitle','equals','1'),
                ), 
                
                array(
                    'id'       => 'showDescriptionStripImages',
                    'type'     => 'checkbox',
                    'title'    => __( 'Strip Description Images?', 'woocommerce-print-products' ),
                    'default'   => 0,
                    'required' => array('showDescription','equals','1'),
                ),
                array(
                    'id'       => 'showDescriptionDoShortcodes',
                    'type'     => 'checkbox',
                    'title'    => __( 'Try executing shortcodes in description', 'woocommerce-print-products' ),
                    'default'   => 1,
                    'required' => array('showDescription','equals','1'),
                ),
                array(
                    'id'       => 'showDescriptionNoTable',
                    'type'     => 'checkbox',
                    'title'    => __( 'Do not convert Description to Table', 'woocommerce-print-products' ),
                    'default'   => 0,
                    'required' => array('showDescription','equals','1'),
                ),

            array(
                'id'       => 'showAttributes',
                'type'     => 'switch',
                'title'    => __( 'Show Product Attributes', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
                array(
                    'id'       => 'showAttributesTitle',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Attributes Title', 'woocommerce-print-products' ),
                    'default'   => 1,
                    'required' => array('showAttributes','equals','1'),
                ),
                array(
                    'id'     =>'showAttributesHiddenAttributes',
                    'type'  => 'select',
                    'multi' => true,
                    'title' => __('Hide Attributes', 'woocommerce-print-products'), 
                    'required' => array('showAttributes','equals', '1'),
                    'options'  => $atts,
                ),
                
                array(
                    'id'       => 'showAttributesTitleName',
                    'type'     => 'text',
                    'title'    => __('Attributes Title Name', 'woocommerce-print-products'),
                    'default'  => __( 'Additional Information', 'woocommerce-print-products' ),
                    'required' => array('showAttributesTitle','equals','1'),
                ), 
                array(
                    'id'       => 'showAttributesLink',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Attributes Link', 'woocommerce-print-products' ),
                    'default'   => 1,
                    'required' => array('showAttributes','equals','1'),
                ),
                array(
                    'id'       => 'showAttributesValueDescription',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Attributes Value Decription', 'woocommerce-print-products' ),
                    'subtitle'    => __( 'Display attribute description value below the attribute value.', 'woocommerce-print-products' ),
                    'default'   => 0,
                    'required' => array('showAttributes','equals','1'),
                ),
                array(
                    'id'       => 'showAttributesGroup',
                    'type'     => 'checkbox',
                    'title'    => __( 'Attribute Group Support', 'woocommerce-print-products' ),
                    'subtitle'    => __( 'Enable support for our <a href="https://www.welaunch.io/en/product/woocommerce-group-attributes/" target="_blank">Group Attribute plugin</a> to display attributes in groups.', 'woocommerce-print-products' ),
                    'default'   => 1,
                    'required' => array('showAttributes','equals','1'),
                ),

                    array(
                        'id'       => 'showAttributesGroupHideMore',
                        'type'     => 'checkbox',
                        'title'    => __( 'Hide More Group', 'woocommerce-print-products' ),
                        'subtitle'    => __( 'Hide the more group of group attributes plugin.' ),
                        'default'   => 0,
                        'required' => array('showAttributesGroup','equals','1'),
                    ),
                    array(
                        'id'       => 'showAttributesGroupHideGroupName',
                        'type'     => 'checkbox',
                        'title'    => __( 'Hide Group name', 'woocommerce-print-products' ),
                        'subtitle'    => __( 'Hide the group name of group attributes plugin.', 'woocommerce-print-products' ),
                        'default'   => 0,
                        'required' => array('showAttributesGroup','equals','1'),
                    ),

                array(
                    'id'       => 'showAttributesImages',
                    'type'     => 'checkbox',
                    'title'    => __( 'Attribute images', 'woocommerce-print-products' ),
                    'subtitle'    => __( 'Enable support for our <a href="https://www.welaunch.io/en/product/woocommerce-attribute-images/" target="_blank">attribute images plugin</a> to display attributes as images.', 'woocommerce-print-products' ),
                    'default'   => 0,
                    'required' => array('showAttributes','equals','1'),
                ),
                array(
                    'id'     =>'attributeImageWidth',
                    'type'     => 'spinner', 
                    'title'    => __('Attribute Image Width (px)', 'woocommerce-print-products'),
                    'default'  => '20',
                    'min'      => '1',
                    'step'     => '1',
                    'max'      => '9999',
                    'required' => array('showAttributesImages','equals','1'),
                ),
                // array(
                //     'id'       => 'showTextBelow',
                //     'type'     => 'checkbox',
                //     'title'    => __('Attribute Image - Show Text Below', 'woocommerce-print-products' ),
                //     'subtitle' => __('Show the attribute name below the attribute image.', 'woocommerce-print-products' ),
                //     'default'  => '0',
                //     'required' => array('showAttributesImages','equals','1'),
                // ),
            array(
                'id'       => 'showReviews',
                'type'     => 'switch',
                'title'    => __( 'Show Product Reviews', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
            array(
                'id'       => 'showUpsells',
                'type'     => 'switch',
                'title'    => __( 'Show Product Upsells', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
            array(
                'id'       => 'showVariationImage',
                'type'     => 'switch',
                'title'    => __( 'Show Variation Image', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
                array(
                    'id'     =>'showVariationImageSize',
                    'type'     => 'spinner', 
                    'title'    => __('Variation Image Size', 'woocommerce-print-products'),
                    'default'  => '150',
                    'min'      => '1',
                    'step'     => '1',
                    'max'      => '99999',
                    'required' => array('showVariationImage','equals','1'),
                ),
            array(
                'id'       => 'showVariationSKU',
                'type'     => 'switch',
                'title'    => __( 'Show Variation SKU', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
            array(
                'id'       => 'showVariationPrice',
                'type'     => 'switch',
                'title'    => __( 'Show Variation Price', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
                array(
                    'id'       => 'showVariationPriceLastColumn',
                    'type'     => 'checkbox',
                    'title'    => __( 'Move Variation Price to last column', 'woocommerce-print-products' ),
                    'default'   => 1,
                    'required' => array('showVariationPrice','equals','1'),
                ),
            array(
                'id'       => 'showVariationStockStatus',
                'type'     => 'switch',
                'title'    => __( 'Show Variation Stock Status', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
            array(
                'id'       => 'showVariationStockQuantity',
                'type'     => 'switch',
                'title'    => __( 'Show Variation Stock Quantity', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
            array(
                'id'       => 'showVariationDescription',
                'type'     => 'switch',
                'title'    => __( 'Show Variation Description', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
            array(
                'id'       => 'showVariationAttributes',
                'type'     => 'switch',
                'title'    => __( 'Show Variation Attributes', 'woocommerce-print-products' ),
                'default'   => 1,
            ),
            array(
                'id'       => 'showNotesLinesCount',
                'type'     => 'spinner', 
                'title'    => __('Number of Notes Lines', 'woocommerce-print-products'),
                'default'  => '6',
                'min'      => '1',
                'step'     => '1',
                'max'      => '999',
            ),
        )
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Extra Texts', 'woocommerce-print-products' ),
        'id'         => 'extra-texts',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'     =>'extraTextBefore',
                'type'  => 'editor',
                'title' => __('Extra Text Before Post', 'woocommerce-print-products'), 
            ),
            array(
                'id'     =>'extraTextAfter',
                'type'  => 'editor',
                'title' => __('Extra Text After Post', 'woocommerce-print-products'), 
            ),
        )
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Custom Post Fields', 'woocommerce-print-products' ),
        'desc' => __( 'With the below settings you can show custom post meta keys for the posts.', 'woocommerce-print-products' ),
        'id'         => 'customData',
        'subsection' => true,
        'fields'     => $woocommerce_print_products_options_meta_keys
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Footer', 'woocommerce-print-products' ),
        // 'desc'       => __( '', 'woocommerce-print-products' ),
        'id'         => 'footer',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'enableFooter',
                'type'     => 'switch',
                'title'    => __( 'Enable', 'woocommerce-print-products' ),
                'subtitle' => __( 'Enable footer', 'woocommerce-print-products' ),
                'default' => 1,
            ),
            array(
                'id'     =>'footerBackgroundColor',
                'type' => 'color',
                'url'      => true,
                'title' => __('Footer background color', 'woocommerce-print-products'), 
                'validate' => 'color',
                'required' => array('enableFooter','equals','1'),
                'default' => '#000000',
            ),
            array(
                'id'     =>'footerTextColor',
                'type'  => 'color',
                'url'      => true,
                'title' => __('Footer text color', 'woocommerce-print-products'), 
                'validate' => 'color',
                'required' => array('enableFooter','equals','1'),
                'default' => '#ffffff',
            ),
            array(
                'id'     =>'footerLayout',
                'type'  => 'select',
                'title' => __('Footer Layout', 'woocommerce-print-products'), 
                'required' => array('enableFooter','equals','1'),
                'options'  => array(
                    'oneCol' => __('1/1', 'woocommerce-print-products' ),
                    'twoCols' => __('1/2 + 1/2', 'woocommerce-print-products' ),
                    'threeCols' => __('1/3 + 1/3 + 1/3', 'woocommerce-print-products' ),
                    'fourCols' => __('1/4 + 1/4 + 1/4 + 1/4', 'woocommerce-print-products' ),
                ),
                'default' => 'oneCol',
            ),
            array(
                'id'     =>'footerTopMargin',
                'type'     => 'spinner', 
                'title'    => __('Footer Margin', 'woocommerce-print-products'),
                'default'  => '20',
                'min'      => '0',
                'step'     => '1',
                'max'      => '200',
            ),
            array(
                'id'     =>'footerHeight',
                'type'     => 'spinner', 
                'title'    => __('Footer Height', 'woocommerce-print-products'),
                'default'  => '20',
                'min'      => '0',
                'step'     => '1',
                'max'      => '200',
            ),
            array(
                'id'     =>'footerVAlign',
                'type'  => 'select',
                'title' => __('Vertical Align', 'woocommerce-print-products'), 
                'required' => array('enableFooter','equals','1'),
                'options'  => array(
                    'top' => __('Top', 'woocommerce-print-products' ),
                    'middle' => __('Middle', 'woocommerce-print-products' ),
                    'bottom' => __('Bottom', 'woocommerce-print-products' ),
                ),
                'default' => 'middle',
            ),
            array(
                'id'     =>'footerTopLeft',
                'type'  => 'select',
                'title' => __('Top Left Footer', 'woocommerce-print-products'), 
                'required' => array('enableFooter','equals','1'),
                'options'  => array(
                    'none' => __('None', 'woocommerce-print-products' ),
                    'bloginfo' => __('Blog information', 'woocommerce-print-products' ),
                    'text' => __('Custom text', 'woocommerce-print-products' ),
                    'pagenumber' => __('Pagenumber', 'woocommerce-print-products' ),
                    'productinfo' => __('Product info', 'woocommerce-print-products' ),
                    'categories' => __('Product Categories', 'woocommerce-print-products' ),
                    'categorydescription' => __('Category Description', 'woocommerce-print-products' ),
                    'image' => __('Image', 'woocommerce-print-products' ),
                    'exportinfo' => __('Export Information', 'woocommerce-print-products' ),
                    'qr' => __('QR-Code', 'woocommerce-print-products' ),
                ),
                'default' => 'pagenumber'
            ),
            array(
                'id'     =>'footerTopLeftText',
                'type'  => 'editor',
                'title' => __('Top Left Footer Text', 'woocommerce-print-products'), 
                'required' => array('footerTopLeft','equals','text'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'footerTopLeftImage',
                'type' => 'media',
                'url'      => true,
                'title' => __('Top Left Footer Image', 'woocommerce-print-products'), 
                'required' => array('footerTopLeft','equals','image'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'footerTopMiddle',
                'type'  => 'select',
                'title' => __('Top Middle Footer', 'woocommerce-print-products'), 
                'required' => array('footerLayout','equals', array('threeCols', 'fourCols') ),
                'options'  => array(
                    'none' => __('None', 'woocommerce-print-products' ),
                    'bloginfo' => __('Blog information', 'woocommerce-print-products' ),
                    'text' => __('Custom text', 'woocommerce-print-products' ),
                    'pagenumber' => __('Pagenumber', 'woocommerce-print-products' ),
                    'productinfo' => __('Product info', 'woocommerce-print-products' ),
                    'categories' => __('Product Categories', 'woocommerce-print-products' ),
                    'categorydescription' => __('Category Description', 'woocommerce-print-products' ),
                    'image' => __('Image', 'woocommerce-print-products' ),
                    'exportinfo' => __('Export Information', 'woocommerce-print-products' ),
                    'qr' => __('QR-Code', 'woocommerce-print-products' ),
                ),
            ),
            array(
                'id'     =>'footerTopMiddleText',
                'type'  => 'editor',
                'title' => __('Top Middle Footer Text', 'woocommerce-print-products'), 
                'required' => array('footerTopMiddle','equals','text'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'footerTopMiddleImage',
                'type' => 'media',
                'url'      => true,
                'title' => __('Top Middle Footer Image', 'woocommerce-print-products'), 
                'required' => array('footerTopMiddle','equals','image'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'footerTopMiddle2',
                'type'  => 'select',
                'title' => __('Top Middle Footer 2', 'woocommerce-print-products'), 
                'required' => array('footerLayout','equals', 'fourCols'),
                'options'  => array(
                    'none' => __('None', 'woocommerce-print-products' ),
                    'bloginfo' => __('Blog information', 'woocommerce-print-products' ),
                    'text' => __('Custom text', 'woocommerce-print-products' ),
                    'pagenumber' => __('Pagenumber', 'woocommerce-print-products' ),
                    'productinfo' => __('Product info', 'woocommerce-print-products' ),
                    'categories' => __('Product Categories', 'woocommerce-print-products' ),
                    'categorydescription' => __('Category Description', 'woocommerce-print-products' ),
                    'image' => __('Image', 'woocommerce-print-products' ),
                    'exportinfo' => __('Export Information', 'woocommerce-print-products' ),
                    'qr' => __('QR-Code', 'woocommerce-print-products' ),
                ),
            ),
            array(
                'id'     =>'footerTopMiddle2Text',
                'type'  => 'editor',
                'title' => __('Top Middle Footer 2 Text', 'woocommerce-print-products'), 
                'required' => array('footerTopMiddle2','equals','text'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'footerTopMiddle2Image',
                'type' => 'media',
                'url'      => true,
                'title' => __('Top Middle Footer 2 Image', 'woocommerce-print-products'), 
                'required' => array('footerTopMiddle2','equals','image'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'footerTopRight',
                'type'  => 'select',
                'title' => __('Top Right Footer', 'woocommerce-print-products'), 
                'required' => array('footerLayout','equals',array('fourCols', 'threeCols','twoCols')),
                'options'  => array(
                    'none' => __('None', 'woocommerce-print-products' ),
                    'bloginfo' => __('Blog information', 'woocommerce-print-products' ),
                    'text' => __('Custom text', 'woocommerce-print-products' ),
                    'pagenumber' => __('Pagenumber', 'woocommerce-print-products' ),
                    'productinfo' => __('Product info', 'woocommerce-print-products' ),
                    'categories' => __('Product Categories', 'woocommerce-print-products' ),
                    'categorydescription' => __('Category Description', 'woocommerce-print-products' ),
                    'image' => __('Image', 'woocommerce-print-products' ),
                    'exportinfo' => __('Export Information', 'woocommerce-print-products' ),
                    'qr' => __('QR-Code', 'woocommerce-print-products' ),
                ),
            ),
            array(
                'id'     =>'footerTopRightText',
                'type'  => 'editor',
                'title' => __('Top Right Footer Text', 'woocommerce-print-products'), 
                'required' => array('footerTopRight','equals','text'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'footerTopRightImage',
                'type' => 'media',
                'url'      => true,
                'title' => __('Top Right Footer Image', 'woocommerce-print-products'), 
                'required' => array('footerTopRight','equals','image'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
            array(
                'id'     =>'foooterTextBeforeFooter',
                'type'  => 'editor',
                'title' => __('Text before Footer', 'woocommerce-print-products'),
                'args'   => array(
                    'teeny'            => false,
                )
            ),
        )
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Limit Access', 'woocommerce-print-products' ),
        // 'desc'       => __( '', 'woocommerce-print-products' ),
        'id'         => 'limit-access-settings',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'enableLimitAccess',
                'type'     => 'checkbox',
                'title'    => __( 'Enable', 'woocommerce-print-products' ),
                'subtitle' => __( 'Enable the limit access. This will activate the below settings.', 'woocommerce-print-products' ),
            ),
            array(
                'id'     =>'role',
                'type' => 'select',
                'data' => 'roles',
                'title' => __('User Role', 'woocommerce-print-products'),
                'subtitle' => __('Select a custom user Role (Default is: administrator) who can use this plugin.', 'woocommerce-print-products'),
                'multi' => true,
                'default' => 'administrator',
            ),
        )
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Advanced settings', 'woocommerce-print-products' ),
        'desc'       => __( 'Custom stylesheet / javascript.', 'woocommerce-print-products' ),
        'id'         => 'advanced',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'debugMode',
                'type'     => 'switch',
                'title'    => __( 'Enable Debug Mode', 'woocommerce-print-products' ),
                'subtitle' => __( 'This stops creating the PDF and shows the plain HTML.', 'woocommerce-print-products' ),
                'default'   => 0,
            ),
            array(
                'id'       => 'debugMPDF',
                'type'     => 'switch',
                'title'    => __( 'Enable MPDF Debug Mode', 'woocommerce-print-products' ),
                'subtitle' => __( 'Show image , font or other errors in the PDF Rendering engine.', 'woocommerce-print-products' ),
                'default'   => 0,
            ),
           array(
                'id'       => 'useImageLocally',
                'type'     => 'switch',
                'title'    => __( 'Use Images Locally', 'woocommerce-print-products' ),
                'subtitle' => __( 'This will get images directly from your server paths rather than requesting it from a http or https. Only enable it if your images are on the same server!', 'woocommerce-print-products' ),
                'default'  => 1,
            ), 
            array(
                'id'       => 'customFileName',
                'type'     => 'switch',
                'title'    => __( 'Use Custom File Name', 'woocommerce-print-products' ),
                'default' => 0,
            ),
                array(
                    'id'       => 'customFileNamePattern',
                    'type'     => 'text',
                    'title'    => __('Custom File Name', 'woocommerce-print-products'),
                    'subtitle'  => __('Use {{sku}}, {{title}}, {{day}}, {{month}} and {{year}} variables.', 'woocommerce-print-products'),
                    'default'  => '{{sku}}-{{title}}-{{day}}-{{month}}-{{year}}',
                    'required' => array('customFileName','equals','1'),
                ), 
           
            array(
                'id'       => 'useSKUasFileName',
                'type'     => 'switch',
                'title'    => __( 'Use SKU as Filename', 'woocommerce-print-products' ),
                'subtitle' => __( 'Instead of product name use SKU as file name. Fallback is still product name.', 'woocommerce-print-products' ),
                'default' => 0,
            ),
           array(
                'id'       => 'appendPDFs',
                'type'     => 'switch',
                'title'    => __( 'Append other PDFs', 'woocommerce-print-products' ),
                'subtitle' => __( 'Add other PDF files to our printed products pdfs. PDFs must be compressed with acrobat version 1.4 or below.', 'woocommerce-print-products' ),
                'default'  => 1,
            ), 

                array(
                    'id'       => 'appendPDFsGlobal',
                    'type'     => 'text',
                    'title'    => __('Global Appended PDFs', 'woocommerce-print-products'),
                    'subtitle'  => __('Enter the absolute file path, seperate multiple values by comma. Example: wp-content/uploads/9/2023/01/test1.pdf', 'woocommerce-print-products'),
                    'default'  => '',
                    'required' => array('appendPDFs','equals','1'),
                ), 
                array(
                    'id'       => 'appendPDFsMetaKey',
                    'type'     => 'text',
                    'title'    => __('Post Meta Kay Appended PDFs', 'woocommerce-print-products'),
                    'subtitle'  => __('Enter a post meta key, where you added PDFs on product level.', 'woocommerce-print-products'),
                    'default'  => '',
                    'required' => array('appendPDFs','equals','1'),
                ), 

            array(
                'id'       => 'curlFollowLocation',
                'type'     => 'switch',
                'title'    => __( 'CURL Follow Location', 'woocommerce-pdf-catalog' ),
                'subtitle' => __( 'Enable when having when images are not rendered / displayed as X.', 'woocommerce-pdf-catalog' ),
                'default'   => 0,
            ),
            array(
                'id'       => 'curlAllowUnsafeSslRequests',
                'type'     => 'switch',
                'title'    => __( 'CURL AllowU nsafe SSL Requests', 'woocommerce-pdf-catalog' ),
                'subtitle' => __( 'Enable when having when images are not rendered / displayed as X.', 'woocommerce-pdf-catalog' ),
                'default'   => 0,
            ),
            array(
                'id'       => 'tableView',
                'type'     => 'switch',
                'title'    => __( 'Use Tables instead of DIVs', 'woocommerce-print-products' ),
                'subtitle' => __( 'DIVs are better for custom styling, but DIVs float over pages sometimes. Below Verson 1.4.7 we used tables only.', 'woocommerce-print-products' ),
                'default'   => 0,
            ),
            array(
                'id'       => 'customCSS',
                'type'     => 'ace_editor',
                'mode'     => 'css',
                'title'    => __( 'Custom CSS', 'woocommerce-print-products' ),
                'subtitle' => __( 'Add some custom CSS styles for the PDF document here.', 'woocommerce-print-products' ),
            ),
            array(
                'id'       => 'customJS',
                'type'     => 'ace_editor',
                'mode'     => 'javascript',
                'title'    => __( 'Custom JS', 'woocommerce-print-products' ),
                'subtitle' => __( 'Add some javascript for the frontend. JS does not work in PDF files.', 'woocommerce-print-products' ),
            ),
        )
    ));

    /*
     * <--- END SECTIONS
     */
