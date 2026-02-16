<?php
/**
 * Polylang + WooCommerce Integration
 *
 * Fixes WooCommerce page ID resolution when switching languages with Polylang Free.
 *
 * The Problem:
 * WooCommerce stores page IDs (Shop, Cart, Checkout, My Account) statically
 * in the database, always pointing to the default-language page. When Polylang
 * switches the active language, WooCommerce still looks up the original ID →
 * the translated page is never found → 404 errors and broken links.
 *
 * The Solution:
 * Hook into every WooCommerce page-ID filter and pass the ID through
 * pll_get_post(), which returns the correct translated page ID for the
 * current active language. Falls back to the original ID if no translation
 * exists, so the site never fully breaks.
 *
 * Covered pages: Shop, Cart, Checkout, My Account, Pay (Order Pay), Terms,
 *                Order Tracking.
 *
 * Requirements:
 * - Polylang (free) must be installed and active.
 * - Translated pages must be linked in Polylang (Language → + button).
 */

namespace RakmyatCore\Multilang;

if ( ! defined( 'ABSPATH' ) ) exit;

class Polylang_WooCommerce {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Only register hooks when Polylang is active.
        if ( ! function_exists( 'pll_get_post' ) ) {
            return;
        }
        $this->register_filters();
    }

    /**
     * Attach translate_page_id() to every WooCommerce page-ID filter.
     */
    private function register_filters() {
        add_filter( 'woocommerce_get_shop_page_id',           [ $this, 'translate_page_id' ] );
        add_filter( 'woocommerce_get_cart_page_id',           [ $this, 'translate_page_id' ] );
        add_filter( 'woocommerce_get_checkout_page_id',       [ $this, 'translate_page_id' ] );
        add_filter( 'woocommerce_get_myaccount_page_id',      [ $this, 'translate_page_id' ] );
        add_filter( 'woocommerce_get_pay_page_id',            [ $this, 'translate_page_id' ] );
        add_filter( 'woocommerce_get_terms_page_id',          [ $this, 'translate_page_id' ] );

        // Order Tracking page — makes WooCommerce generate Arabic tracking URLs
        // for Arabic customers, and recognises the translated page as the
        // tracking page (so is_page() checks and shortcode routing work correctly).
        add_filter( 'woocommerce_get_order_tracking_page_id', [ $this, 'translate_page_id' ] );
    }

    /**
     * Replace a WooCommerce page ID with its Polylang translation for the
     * current active language.
     *
     * @param  int $id  Original page ID stored in WooCommerce options.
     * @return int      Translated page ID, or the original if none found.
     */
    public function translate_page_id( $id ) {
        $translated_id = pll_get_post( $id );
        return $translated_id ? $translated_id : $id;
    }
}
