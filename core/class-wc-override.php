<?php
namespace RakmyatCore\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class WC_Override {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // 1. Override template using woocommerce_locate_template (PRIMARY METHOD)
        add_filter( 'woocommerce_locate_template', [ $this, 'override_locate_template' ], 9999, 3 );

        // 2. Override template part for related/upsells (BACKUP METHOD)
        add_filter( 'wc_get_template_part', [ $this, 'override_template_part' ], 9999, 3 );

        // 3. Enqueue Assets
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

        // 4. Remove conflicting theme hooks early
        add_action( 'wp', [ $this, 'remove_theme_hooks' ], 1 );
        add_action( 'woocommerce_init', [ $this, 'remove_theme_hooks' ], 1 );
    }

    /**
     * Override WooCommerce template location
     * This is the CORRECT way to override templates from a plugin
     */
    public function override_locate_template( $template, $template_name, $template_path ) {
        if ( 'content-product.php' === $template_name ) {
            $plugin_template = RMT_PATH . 'templates/content-product.php';
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }
        return $template;
    }

    /**
     * Override template part (for wc_get_template_part calls in related/upsells)
     */
    public function override_template_part( $template, $slug, $name ) {
        if ( 'content' === $slug && 'product' === $name ) {
            $plugin_template = RMT_PATH . 'templates/content-product.php';
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }
        return $template;
    }

    /**
     * Remove theme hooks that conflict with our template
     */
    public function remove_theme_hooks() {
        // Remove ALL default WooCommerce loop hooks to prevent duplication
        remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
        remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

        // Remove Martfury theme specific hooks
        remove_action( 'woocommerce_before_shop_loop_item', 'martfury_product_loop_inner_open', 5 );
        remove_action( 'woocommerce_after_shop_loop_item', 'martfury_product_loop_inner_close', 15 );
        remove_action( 'woocommerce_before_shop_loop_item_title', 'martfury_template_loop_product_thumbnail', 10 );
        remove_action( 'woocommerce_shop_loop_item_title', 'martfury_template_loop_product_title', 10 );
        remove_action( 'woocommerce_after_shop_loop_item_title', 'martfury_template_loop_price', 10 );
        remove_action( 'woocommerce_after_shop_loop_item', 'martfury_template_loop_add_to_cart', 10 );

        // Remove any Martfury class hooks
        if ( class_exists( 'Martfury_WooCommerce' ) ) {
            global $martfury_woocommerce;
            if ( $martfury_woocommerce ) {
                remove_action( 'woocommerce_before_shop_loop_item', [ $martfury_woocommerce, 'product_loop_inner_open' ], 5 );
                remove_action( 'woocommerce_after_shop_loop_item', [ $martfury_woocommerce, 'product_loop_inner_close' ], 15 );
            }
        }
    }

    /**
     * Enqueue CSS and JS assets
     */
    public function enqueue_assets() {
        wp_enqueue_style(
            'rmt-product-card',
            RMT_URL . 'assets/css/product-card.css',
            [],
            time()
        );

        wp_enqueue_script(
            'rmt-product-card',
            RMT_URL . 'assets/js/product-card.js',
            [ 'jquery' ],
            time(),
            true
        );

        wp_localize_script( 'rmt-product-card', 'rmt_vars', [
            'ajax_url'     => admin_url( 'admin-ajax.php' ),
            'checkout_url' => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '',
        ]);
    }
}
