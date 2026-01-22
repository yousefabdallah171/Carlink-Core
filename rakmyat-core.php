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

// Load Widget Manager
require_once RMT_PATH . 'core/class-widget-manager.php';

// Initialize the Manager
add_action( 'plugins_loaded', function() {
    \RakmyatCore\Core\Widget_Manager::instance();
});

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