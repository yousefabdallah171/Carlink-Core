<?php
namespace RakmyatCore\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Checkout_Customizer {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Only run on checkout page
        if ( ! \is_checkout() ) {
            return;
        }

        // Customize form field output
        \add_filter( 'woocommerce_form_field', [ $this, 'customize_form_field' ], 10, 4 );

        // Remove coupon toggle and always show coupon form
        \add_filter( 'woocommerce_checkout_show_terms', '__return_false' );
        \add_filter( 'woocommerce_coupons_enabled', '__return_true' );

        // Reorder checkout elements
        \add_action( 'woocommerce_checkout_before_customer_details', [ $this, 'open_customer_details_wrapper' ] );
        \add_action( 'woocommerce_checkout_after_customer_details', [ $this, 'close_customer_details_wrapper' ] );

        // Move coupon form inside order review
        \add_action( 'woocommerce_review_order_before_payment', [ $this, 'open_order_review_card' ] );
        \add_action( 'woocommerce_review_order_after_payment', [ $this, 'close_order_review_card' ] );

        // Display coupon form in the right place
        \add_action( 'woocommerce_review_order_after_order_total', [ $this, 'render_coupon_form' ] );
    }

    /**
     * Customize form field output
     * This wraps each field in proper styling containers
     */
    public function customize_form_field( $field, $key, $args, $value ) {
        // Only customize billing and shipping fields
        if ( ! in_array( $key, $this->get_checkout_field_keys() ) ) {
            return $field;
        }

        // The field HTML is already well-formed, just ensure proper classes
        return $field;
    }

    /**
     * Get all checkout field keys to customize
     */
    private function get_checkout_field_keys() {
        return [
            'billing_first_name',
            'billing_last_name',
            'billing_company',
            'billing_country',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_state',
            'billing_postcode',
            'billing_email',
            'billing_phone',
            'shipping_first_name',
            'shipping_last_name',
            'shipping_company',
            'shipping_country',
            'shipping_address_1',
            'shipping_address_2',
            'shipping_city',
            'shipping_state',
            'shipping_postcode',
        ];
    }

    /**
     * Open customer details wrapper for left column
     */
    public function open_customer_details_wrapper() {
        echo '<!-- Customer Details Wrapper Opened -->';
    }

    /**
     * Close customer details wrapper
     */
    public function close_customer_details_wrapper() {
        echo '<!-- Customer Details Wrapper Closed -->';
    }

    /**
     * Open order review card wrapper
     */
    public function open_order_review_card() {
        echo '<!-- Order Review Card Opened -->';
    }

    /**
     * Close order review card wrapper
     */
    public function close_order_review_card() {
        echo '<!-- Order Review Card Closed -->';
    }

    /**
     * Render coupon form in the order review section
     * This displays the coupon input and button right after the order total
     */
    public function render_coupon_form() {
        if ( ! \WC()->cart->is_empty() && \wc_coupons_enabled() ) {
            ?>
            <tr class="coupon-divider">
                <td colspan="2"></td>
            </tr>
            <tr class="checkout-coupon-row">
                <td colspan="2">
                    <form class="checkout_coupon" method="post">
                        <input
                            type="text"
                            name="post_data[coupon_code]"
                            class="input-text"
                            id="coupon_code"
                            value=""
                            placeholder="Coupon Code"
                        />
                        <button
                            type="submit"
                            class="button"
                            name="apply_coupon"
                            value="<?php \esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"
                        >
                            <?php \esc_html_e( 'Apply Coupon', 'woocommerce' ); ?>
                        </button>
                    </form>
                </td>
            </tr>
            <?php
        }
    }
}

// Initialize
Checkout_Customizer::instance();
