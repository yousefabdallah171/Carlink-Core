(function($) {
    "use strict";

    // Initialize Add to Cart Widget
    function initAddToCartWidget() {

        // Quantity controls
        $(document).on('click', '.rmt-qty-minus', function(e) {
            e.preventDefault();
            var $input = $(this).siblings('.rmt-qty-input');
            var val = parseInt($input.val()) || 1;
            var min = parseInt($input.attr('min')) || 1;
            if (val > min) {
                $input.val(val - 1).trigger('change');
            }
        });

        $(document).on('click', '.rmt-qty-plus', function(e) {
            e.preventDefault();
            var $input = $(this).siblings('.rmt-qty-input');
            var val = parseInt($input.val()) || 1;
            var max = parseInt($input.attr('max')) || 999;
            if (val < max) {
                $input.val(val + 1).trigger('change');
            }
        });

        // Validate quantity input
        $(document).on('change', '.rmt-qty-input', function() {
            var $input = $(this);
            var val = parseInt($input.val()) || 1;
            var min = parseInt($input.attr('min')) || 1;
            var max = parseInt($input.attr('max')) || 999;

            if (val < min) val = min;
            if (val > max) val = max;

            $input.val(val);
        });

        // Add to Cart
        $(document).on('click', '.rmt-add-to-cart-btn', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var $widget = $btn.closest('.rmt-add-to-cart-widget');
            var productId = $btn.data('product-id');
            var quantity = $widget.find('.rmt-qty-input').val() || 1;

            if ($btn.hasClass('loading')) return;

            $btn.addClass('loading');

            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                type: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    }

                    // Trigger WooCommerce events
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $btn]);

                    // Update mini cart
                    if (response.fragments) {
                        $.each(response.fragments, function(key, value) {
                            $(key).replaceWith(value);
                        });
                    }

                    $btn.addClass('added');
                    setTimeout(function() {
                        $btn.removeClass('added');
                    }, 2000);
                },
                error: function() {
                    console.error('Add to cart failed');
                },
                complete: function() {
                    $btn.removeClass('loading');
                }
            });
        });

        // Buy Now
        $(document).on('click', '.rmt-buy-now-btn', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var $widget = $btn.closest('.rmt-add-to-cart-widget');
            var productId = $btn.data('product-id');
            var quantity = $widget.find('.rmt-qty-input').val() || 1;

            // Add to cart then redirect to checkout
            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                type: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    if (!response.error) {
                        // Redirect to checkout
                        window.location.href = wc_add_to_cart_params.cart_url ?
                            wc_add_to_cart_params.cart_url.replace('cart', 'checkout') :
                            '/checkout/';
                    }
                },
                error: function() {
                    console.error('Buy now failed');
                }
            });
        });

        // Wishlist Toggle (WCBoost integration)
        $(document).on('click', '.rmt-wishlist-btn', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var productId = $btn.data('product-id');
            var isActive = $btn.hasClass('active');

            // Toggle visual state immediately for better UX
            $btn.toggleClass('active');
            $btn.find('.rmt-wishlist-icon-default').toggle();
            $btn.find('.rmt-wishlist-icon-active').toggle();

            // Update aria label
            if ($btn.hasClass('active')) {
                $btn.attr('aria-label', 'Remove from wishlist');
            } else {
                $btn.attr('aria-label', 'Add to wishlist');
            }

            // If WCBoost Wishlist is available
            if (typeof wcboost_wishlist_params !== 'undefined') {
                var action = isActive ? 'remove' : 'add';

                $.ajax({
                    url: wcboost_wishlist_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: isActive ? 'wcboost_wishlist_remove_item' : 'wcboost_wishlist_add_item',
                        product_id: productId,
                        nonce: wcboost_wishlist_params.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Trigger events for other widgets
                            if (isActive) {
                                $(document).trigger('removed_from_wishlist');
                            } else {
                                $(document).trigger('added_to_wishlist');
                            }
                        } else {
                            // Revert visual state on error
                            $btn.toggleClass('active');
                            $btn.find('.rmt-wishlist-icon-default').toggle();
                            $btn.find('.rmt-wishlist-icon-active').toggle();
                        }
                    },
                    error: function() {
                        // Revert visual state on error
                        $btn.toggleClass('active');
                        $btn.find('.rmt-wishlist-icon-default').toggle();
                        $btn.find('.rmt-wishlist-icon-active').toggle();
                    }
                });
            } else {
                // Fallback: Try clicking WCBoost's own button if present
                var $wcboostBtn = $('.wcboost-wishlist-button[data-product_id="' + productId + '"]');
                if ($wcboostBtn.length) {
                    $wcboostBtn.trigger('click');
                }
            }
        });

        // Listen for WCBoost wishlist events to sync state
        $(document).on('wcboost_wishlist_added wcboost_wishlist_removed', function(e, data) {
            if (data && data.product_id) {
                var $btn = $('.rmt-wishlist-btn[data-product-id="' + data.product_id + '"]');
                if ($btn.length) {
                    if (e.type === 'wcboost_wishlist_added') {
                        $btn.addClass('active');
                        $btn.find('.rmt-wishlist-icon-default').hide();
                        $btn.find('.rmt-wishlist-icon-active').show();
                    } else {
                        $btn.removeClass('active');
                        $btn.find('.rmt-wishlist-icon-default').show();
                        $btn.find('.rmt-wishlist-icon-active').hide();
                    }
                }
            }
        });
    }

    // Initialize on document ready
    $(document).ready(function() {
        initAddToCartWidget();
    });

    // Reinitialize for Elementor editor
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/add-to-cart.default', function() {
                initAddToCartWidget();
            });
        }
    });

})(jQuery);
