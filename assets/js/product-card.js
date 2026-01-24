/**
 * Glassmorphic Product Card JavaScript
 * Handles AJAX Add to Cart and Buy Now
 */

(function($) {
    'use strict';

    // Wait for DOM ready
    $(document).ready(function() {
        initAddToCart();
        initBuyNow();
    });

    /**
     * Initialize AJAX Add to Cart
     */
    function initAddToCart() {
        // Listen for WooCommerce AJAX add to cart events
        $(document.body).on('adding_to_cart', function(e, $button, data) {
            if ($button.hasClass('rmt-btn-cart')) {
                $button.addClass('loading').removeClass('added');
            }
        });

        $(document.body).on('added_to_cart', function(e, fragments, cart_hash, $button) {
            if ($button && $button.hasClass('rmt-btn-cart')) {
                $button.removeClass('loading').addClass('added');

                // Reset after 2 seconds
                setTimeout(function() {
                    $button.removeClass('added');
                    $button.find('.btn-text').text('Add to Cart');
                }, 2000);

                // Update button text temporarily
                $button.find('.btn-text').text('Added!');
            }
        });

        // Handle add to cart error
        $(document.body).on('wc_cart_button_updated', function(e, $button) {
            if ($button.hasClass('rmt-btn-cart')) {
                $button.removeClass('loading');
            }
        });
    }

    /**
     * Initialize Buy Now functionality
     */
    function initBuyNow() {
        $(document).on('click', '.rmt-btn-buy', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var productId = $btn.data('product_id');
            var checkoutUrl = rmt_vars.checkout_url || '/checkout/';

            // Add loading state
            $btn.addClass('loading');
            $btn.text('Processing...');

            // Add to cart via AJAX then redirect to checkout
            $.ajax({
                type: 'POST',
                url: rmt_vars.ajax_url,
                data: {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    // Redirect to checkout with product added
                    window.location.href = checkoutUrl;
                },
                error: function() {
                    // Fallback: just redirect to checkout URL with add-to-cart param
                    window.location.href = checkoutUrl + '?add-to-cart=' + productId;
                }
            });
        });
    }

    /**
     * Update mini cart after add to cart
     */
    $(document.body).on('added_to_cart', function() {
        // Trigger mini cart update if theme uses it
        $(document.body).trigger('wc_fragment_refresh');
    });

})(jQuery);
