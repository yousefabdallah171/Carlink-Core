<?php
/**
 * Plugin Name: Carlink Core
 * Description: Custom Elementor Addons for WooCommerce.
 * Version: 1.0.0
 * Author: osbash
 * Text Domain: rakmyat-core
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Define Constants
define( 'RMT_PATH', plugin_dir_path( __FILE__ ) );
define( 'RMT_URL', plugin_dir_url( __FILE__ ) );

// Load Core Classes
require_once RMT_PATH . 'core/class-global-assets.php';
require_once RMT_PATH . 'core/class-widget-manager.php';
require_once RMT_PATH . 'core/class-wc-override.php';
require_once RMT_PATH . 'core/class-shop-page.php';
require_once RMT_PATH . 'core/class-shop-customizer.php';
require_once RMT_PATH . 'core/class-shop-sidebar.php';

// Initialize All Managers
add_action( 'plugins_loaded', function() {
    \RakmyatCore\Core\Global_Assets::instance();
    \RakmyatCore\Core\Widget_Manager::instance();
    \RakmyatCore\Core\WC_Override::instance();
    \RakmyatCore\Core\Shop_Page::instance();
    \RakmyatCore\Core\Shop_Customizer::instance();
    \RakmyatCore\Core\Shop_Sidebar::instance();
});

// Disable WooCommerce Coming Soon mode (to allow shop to display)
add_action( 'init', function() {
    // Remove the coming soon template redirect
    if ( class_exists( 'Automattic\WooCommerce\Admin\Features\LaunchYourStore' ) ) {
        remove_action( 'template_redirect', array( 'Automattic\WooCommerce\Admin\Features\LaunchYourStore', 'coming_soon_redirect' ) );
    }
}, 1 );

// Alternative: Filter to disable coming soon
add_filter( 'woocommerce_coming_soon_exclude', '__return_true' );

// No AJAX needed - wishlist count is rendered server-side by the widget