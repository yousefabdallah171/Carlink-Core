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
        // Customize form field output
        \add_filter( 'woocommerce_form_field', [ $this, 'customize_form_field' ], 10, 4 );

        // Always enable coupons
        \add_filter( 'woocommerce_coupons_enabled', '__return_true' );

        // Reorder checkout elements
        \add_action( 'woocommerce_checkout_before_customer_details', [ $this, 'open_customer_details_wrapper' ] );
        \add_action( 'woocommerce_checkout_after_customer_details', [ $this, 'close_customer_details_wrapper' ] );

        // Move coupon form inside order review
        \add_action( 'woocommerce_review_order_before_payment', [ $this, 'open_order_review_card' ] );
        \add_action( 'woocommerce_review_order_after_payment', [ $this, 'close_order_review_card' ] );

        // Display coupon form before payment section (between table and payment methods)
        \add_action( 'woocommerce_review_order_before_payment', [ $this, 'render_coupon_form' ], 5 );

        // Add product images to order review items
        \add_filter( 'woocommerce_cart_item_name', [ $this, 'add_product_image_to_order_item' ], 10, 3 );

        // Add coupon AJAX script on checkout page
        \add_action( 'wp_footer', [ $this, 'render_coupon_script' ] );
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
     * Uses a div (NOT a form) to avoid nested form inside the checkout form.
     * Coupon is applied via WooCommerce AJAX.
     */
    public function render_coupon_form() {
        if ( \wc_coupons_enabled() ) {
            ?>
            <div class="checkout-coupon-wrapper">
                <div class="checkout_coupon">
                    <input
                        type="text"
                        class="input-text"
                        id="rmt_coupon_code"
                        value=""
                        placeholder="<?php \esc_attr_e( 'Coupon Code', 'woocommerce' ); ?>"
                    />
                    <button
                        type="button"
                        class="button"
                        id="rmt_apply_coupon"
                    >
                        <?php \esc_html_e( 'Apply Coupon', 'woocommerce' ); ?>
                    </button>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Add product image to cart item name (works on checkout)
     * Displays product image before product title
     *
     * @param string $product_name Product name HTML
     * @param array $cart_item Cart item data
     * @param string $cart_item_key Cart item key
     */
    public function add_product_image_to_order_item( $product_name, $cart_item, $cart_item_key ) {
        // Only on checkout page front-end rendering, NOT during AJAX order processing
        if ( ! \is_checkout() || \wp_doing_ajax() ) {
            return $product_name;
        }

        // Get product safely
        if ( ! isset( $cart_item['data'] ) || ! $cart_item['data'] instanceof \WC_Product ) {
            return $product_name;
        }

        $product = $cart_item['data'];

        // Get product image
        $image_html = '';
        $image_id = $product->get_image_id();
        if ( $image_id ) {
            $image_url = \wp_get_attachment_image_url( $image_id, 'thumbnail' );
            if ( $image_url ) {
                $image_html = '<img src="' . \esc_url( $image_url ) . '" alt="' . \esc_attr( $product->get_name() ) . '" class="checkout-product-image" />';
            }
        }

        // Wrap in flex container for image + name side by side
        return '<div class="checkout-product-item">' . $image_html . '<div class="checkout-product-name">' . $product_name . '</div></div>';
    }

    /**
     * Render inline script to handle coupon application via WooCommerce AJAX
     */
    public function render_coupon_script() {
        if ( ! \is_checkout() ) {
            return;
        }
        ?>
        <script>
        (function($) {
            $(document).on('click', '#rmt_apply_coupon', function(e) {
                e.preventDefault();
                var couponCode = $('#rmt_coupon_code').val().trim();
                if ( ! couponCode ) {
                    return;
                }

                var $btn = $(this);
                $btn.prop('disabled', true).text('Applying...');

                $.ajax({
                    type: 'POST',
                    url: wc_checkout_params.ajax_url,
                    data: {
                        action: 'woocommerce_apply_coupon' ,
                        security: wc_checkout_params.apply_coupon_nonce,
                        coupon_code: couponCode
                    },
                    success: function(response) {
                        $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();
                        if ( response ) {
                            $('form.checkout').before(response);
                        }
                        $(document.body).trigger('update_checkout');
                        $('#rmt_coupon_code').val('');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text('<?php echo \esc_js( __( 'Apply Coupon', 'woocommerce' ) ); ?>');
                    }
                });
            });

            // Allow Enter key to apply coupon
            $(document).on('keypress', '#rmt_coupon_code', function(e) {
                if ( e.keyCode === 13 ) {
                    e.preventDefault();
                    $('#rmt_apply_coupon').trigger('click');
                }
            });
        })(jQuery);
        </script>
        <?php
    }
}
