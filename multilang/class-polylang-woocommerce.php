<?php
/**
 * Polylang + WooCommerce Integration
 *
 * Fixes WooCommerce page ID resolution when switching languages with Polylang Free.
 *
 * ─── The Problem ────────────────────────────────────────────────────────────
 * WooCommerce stores page IDs (Shop, Cart, Checkout, My Account, etc.) as
 * static integers in wp_options. They always point to the default-language
 * (English) page. When Polylang switches the active language to Arabic:
 *   • WooCommerce still returns the English page ID
 *   • get_permalink( $id ) produces the English URL
 *   • Arabic users land on the English page, or get redirected to the English
 *     version of every WC page (shop, cart, checkout, tracking, …)
 *
 * ─── The Solution ───────────────────────────────────────────────────────────
 * Hook into every WooCommerce page-ID filter and pass the ID through
 * pll_get_post(), which returns the translated page ID for the currently
 * active Polylang language. Falls back to the original ID if no translation
 * exists, so the site never fully breaks.
 *
 * ─── Covered Pages & Filters ────────────────────────────────────────────────
 * Filter                                  Page
 * ────────────────────────────────────────────────────────────────────
 * woocommerce_get_shop_page_id            /shop/          → /ar/المتجر/
 * woocommerce_get_cart_page_id            /cart/          → /ar/السلة/
 * woocommerce_get_checkout_page_id        /checkout/      → /ar/الدفع/
 * woocommerce_get_myaccount_page_id       /my-account/    → /ar/حسابي/
 * woocommerce_get_pay_page_id             /checkout/pay/  → /ar/الدفع/pay/
 * woocommerce_get_terms_page_id           /terms/         → /ar/الشروط/
 * woocommerce_get_order_tracking_page_id  /order-tracking/→ /ar/تتبع-الطلب/
 *
 * The Order Tracking filter also ensures Arabic order-confirmation emails
 * contain the Arabic tracking URL, not the English one.
 *
 * ─── Requirements ───────────────────────────────────────────────────────────
 * - Polylang (free) must be installed and active.
 * - Each WooCommerce page must have an Arabic translation created in WordPress.
 * - The Arabic translation must be linked to the English page inside Polylang
 *   (Pages list → Language column → click the + icon for Arabic).
 *
 * ─── Related Files ──────────────────────────────────────────────────────────
 * - multilang/class-string-overrides.php  — gettext filter for untranslated
 *   strings from WooCommerce, Martfury theme, WCFM, and wishlist plugins
 * - elements/widgets/language-switcher.php — Elementor language switcher
 *   widget (Polylang-powered, works on WC archive pages)
 * - languages/rakmyat-core-ar.po/.mo      — Arabic translations for all
 *   plugin-owned strings (100 entries)
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
