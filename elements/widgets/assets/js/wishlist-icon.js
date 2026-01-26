(function($) {
    "use strict";

    // Get current count from badge
    function getCurrentCount() {
        var $badge = $('.rmt-wishlist-count');
        if ($badge.length) {
            return parseInt($badge.text()) || 0;
        }
        return 0;
    }

    // Update badge with new count
    function updateBadge(count) {
        var $badge = $('.rmt-wishlist-count');
        if ($badge.length) {
            $badge.text(count);
            if (count > 0) {
                $badge.addClass('show');
            } else {
                $badge.removeClass('show');
            }
        }
    }

    // When item is ADDED to wishlist
    $(document).on('added_to_wishlist', function() {
        var newCount = getCurrentCount() + 1;
        updateBadge(newCount);
    });

    // When item is REMOVED from wishlist
    $(document).on('removed_from_wishlist', function() {
        var newCount = getCurrentCount() - 1;
        if (newCount < 0) newCount = 0;
        updateBadge(newCount);
    });

    // WCBoost specific event
    $(document).on('wcboost:wishlist:updated', function(e, data) {
        // If WCBoost provides count in event data, use it
        if (data && typeof data.count !== 'undefined') {
            updateBadge(parseInt(data.count));
        }
    });

})(jQuery);
