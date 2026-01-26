(function ($) {
    "use strict";

    var WidgetWishlistIconHandler = function ($scope, $) {
        var $myBadge = $scope.find('.rmt-wishlist-count');

        // Check if badge element exists
        if ($myBadge.length === 0) {
            console.warn('Wishlist badge element not found');
            return;
        }

        // Fetch wishlist count from backend AJAX
        var fetchCountFromBackend = function() {
            // Check if wishlist_ajax is available
            if (typeof wishlist_ajax === 'undefined' || !wishlist_ajax.ajaxurl) {
                console.warn('Wishlist AJAX not initialized');
                return;
            }

            $.ajax({
                url: wishlist_ajax.ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'get_wishlist_count'
                },
                success: function(response) {
                    if (response && response.count !== undefined) {
                        var count = parseInt(response.count);
                        updateUI(count);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Wishlist count fetch error:', error);
                }
            });
        };

        var updateUI = function(count) {
            if (!isNaN(count) && count > 0) {
                $myBadge.text(count).css('display', 'flex');
            } else {
                $myBadge.css('display', 'none');
            }
        };

        // 1. Fetch count on page load with a small delay to ensure DOM is ready
        setTimeout(function() {
            fetchCountFromBackend();
        }, 500);

        // 2. Re-fetch after any AJAX request (wishlist/cart changes)
        $(document).on('ajaxComplete', function(event, xhr, settings) {
            if (settings && settings.data) {
                var requestData = settings.data.toString();
                if (requestData.indexOf('wishlist') !== -1 || requestData.indexOf('add_to_cart') !== -1) {
                    setTimeout(fetchCountFromBackend, 150);
                }
            }
        });

        // 3. Listen for Martfury wishlist events
        $(document).on('added_to_wishlist removed_from_wishlist', function() {
            setTimeout(fetchCountFromBackend, 150);
        });

        // 4. Listen for WCBoost wishlist events
        $(document).on('wcboost:wishlist:updated', function() {
            setTimeout(fetchCountFromBackend, 150);
        });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/wishlist-icon.default', WidgetWishlistIconHandler);
    });

})(jQuery);