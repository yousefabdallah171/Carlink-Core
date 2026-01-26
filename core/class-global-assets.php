<?php
namespace RakmyatCore\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Global_Assets {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Enqueue global plugin assets
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_global_assets' ] );

        // Add trust badges to cart page
        add_action( 'woocommerce_after_cart_totals', [ $this, 'add_trust_badges' ] );
    }

    /**
     * Enqueue global assets that apply to all pages
     */
    public function enqueue_global_assets() {
        // Enqueue global breadcrumb CSS
        wp_enqueue_style(
            'rmt-breadcrumbs',
            RMT_URL . 'assets/css/breadcrumbs.css',
            [],
            filemtime( RMT_PATH . 'assets/css/breadcrumbs.css' )
        );

        // Enqueue WooCommerce Cart custom styling
        if ( is_cart() ) {
            wp_enqueue_style(
                'rmt-woo-cart',
                RMT_URL . 'elements/widgets/assets/css/woo-cart.css',
                [],
                filemtime( RMT_PATH . 'elements/widgets/assets/css/woo-cart.css' )
            );
        }

        // Enqueue WooCommerce Checkout custom styling
        if ( is_checkout() ) {
            wp_enqueue_style(
                'rmt-woo-checkout',
                RMT_URL . 'elements/widgets/assets/css/woo-checkout.css',
                [],
                filemtime( RMT_PATH . 'elements/widgets/assets/css/woo-checkout.css' )
            );
        }
    }

    /**
     * Add trust badges to cart page
     */
    public function add_trust_badges() {
        ?>
        <div class="trust-badges">
            <div class="trust-badge">Secure checkout</div>
            <div class="trust-badge">Free returns within 30 days</div>
            <div class="trust-badge">Quality guaranteed</div>
        </div>
        <?php
    }
}
