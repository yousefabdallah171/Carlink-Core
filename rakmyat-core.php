<?php
/**
 * Plugin Name: Carlink Core
 * Description: Custom Elementor Addons for WooCommerce.
 * Version: 1.0.0
 * Author: Yousef Abdallah
 * Text Domain: rakmyat-core
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Define Constants
define( 'RMT_PATH', plugin_dir_path( __FILE__ ) );
define( 'RMT_URL', plugin_dir_url( __FILE__ ) );

// Load Core Classes
require_once RMT_PATH . 'core/class-widget-manager.php';
require_once RMT_PATH . 'core/class-wc-override.php';

// Initialize All Managers
add_action( 'plugins_loaded', function() {
    \RakmyatCore\Core\Widget_Manager::instance();
    \RakmyatCore\Core\WC_Override::instance();
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

// AJAX handler for wishlist count
add_action('wp_ajax_get_wishlist_count', 'rmt_get_wishlist_count');
add_action('wp_ajax_nopriv_get_wishlist_count', 'rmt_get_wishlist_count');

function rmt_get_wishlist_count() {
    $count = 0;
    if (class_exists('WCBoost\Wishlist\Session')) {
        // Try modern method first
        $count = \WCBoost\Wishlist\Session::get_instance()->get_count();
    } elseif (function_exists('wcboost_wishlist_get_count')) {
        // Fallback function
        $count = wcboost_wishlist_get_count();
    } else {
        // Fallback: Check Cookie manually if plugin class not found
        if (isset($_COOKIE['wcboost_wishlist_items'])) {
            $items = json_decode(stripslashes($_COOKIE['wcboost_wishlist_items']), true);
            if (is_array($items)) {
                $count = count($items);
            }
        }
    }
    wp_send_json(['count' => $count]);
}