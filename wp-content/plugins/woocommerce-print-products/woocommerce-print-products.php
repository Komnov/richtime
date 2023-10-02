<?php

/**
 * The plugin bootstrap file
 *
 *
 * @link              https://welaunch.io/plugins/woocommerce-print-products/
 * @since             1.0.
 * @package           WooCommerce_Print_Products
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Print Products
 * Plugin URI:        https://welaunch.io/plugins/woocommerce-print-products/
 * Description:       Give your visitors the option to create a PDF or Word-File from your Products.
 * Version:           1.8.8
 * Author:            weLaunch
 * Author URI:        https://welaunch.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-print-products
 * Domain Path:       /languages
 * WC tested up to:   4.2.2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-print-products-activator.php
 */
function activate_woocommerce_print_products() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-print-products-activator.php';
	WooCommerce_Print_Products_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-print-products-deactivator.php
 */
function deactivate_woocommerce_print_products() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-print-products-deactivator.php';
	WooCommerce_Print_Products_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_print_products' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_print_products' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-print-products.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_print_products() {

	$plugin_data = get_plugin_data( __FILE__ );
	$version = $plugin_data['Version'];

	$plugin = new WooCommerce_Print_Products($version);
	$plugin->run();

	return $plugin;

}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php') && (is_plugin_active('redux-dev-master/redux-framework.php') || is_plugin_active('redux-framework/redux-framework.php') ||  is_plugin_active('welaunch-framework/welaunch-framework.php') ) ){
	$WooCommerce_Print_Products = run_woocommerce_print_products();
} else {
	add_action( 'admin_notices', 'woocommerce_print_products_installed_notice' );
}

function woocommerce_print_products_installed_notice()
{
	?>
    <div class="error">
      <p><?php _e( 'WooCommerce Print Products requires the WooCommerce and weLaunch Framework plugin. Please install or activate them before: https://www.welaunch.io/updates/welaunch-framework.zip', 'woocommerce-print-products'); ?></p>
    </div>
    <?php
}