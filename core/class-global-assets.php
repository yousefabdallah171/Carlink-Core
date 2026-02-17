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

        // Add Order Summary title to cart page (fires on cart page, before cart totals)
        add_action( 'woocommerce_before_cart_totals', [ $this, 'add_order_summary_title' ] );

        // Add trust badges to cart page (fires after cart totals section)
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

        // Enqueue WooCommerce Order Tracking custom styling
        // Scoped under .rmt-order-tracking so safe to load on all WooCommerce pages
        if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() || $this->is_tracking_page() ) {
            wp_enqueue_style(
                'rmt-woo-order-tracking',
                RMT_URL . 'elements/widgets/assets/css/woo-order-tracking.css',
                [],
                filemtime( RMT_PATH . 'elements/widgets/assets/css/woo-order-tracking.css' )
            );
        }

        // Enqueue WooCommerce My Account custom styling
        if ( is_account_page() && ! is_checkout() ) {
            wp_enqueue_style(
                'rmt-woo-myaccount',
                RMT_URL . 'elements/widgets/assets/css/woo-myaccount.css',
                [],
                filemtime( RMT_PATH . 'elements/widgets/assets/css/woo-myaccount.css' )
            );
        }
    }

    /**
     * Check if current page contains the order tracking shortcode
     */
    private function is_tracking_page() {
        global $post;
        if ( $post && has_shortcode( $post->post_content, 'woocommerce_order_tracking' ) ) {
            return true;
        }
        return false;
    }

    /**
     * Add Order Summary title to cart page order review section
     */
    public function add_order_summary_title() {
        ?>
        <h2><?php esc_html_e( 'Order Summary', 'rakmyat-core' ); ?></h2>
        <?php
    }

    /**
     * Add trust badges to cart page
     */
    public function add_trust_badges() {
        ?>
        <div class="trust-badges">
            <div class="trust-badge"><?php esc_html_e( 'Secure checkout', 'rakmyat-core' ); ?></div>
            <div class="trust-badge"><?php esc_html_e( 'Free returns within 30 days', 'rakmyat-core' ); ?></div>
            <div class="trust-badge"><?php esc_html_e( 'Quality guaranteed', 'rakmyat-core' ); ?></div>
        </div>
        <?php
    }
}
