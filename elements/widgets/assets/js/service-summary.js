(function($) {
    "use strict";

    function initServiceSummary($scope) {
        var $widget = $scope ? $scope.find('.rmt-svc-summary') : $('.rmt-svc-summary');

        if (!$widget.length) return;

        $widget.each(function() {
            var $card     = $(this);
            var taxPct    = parseFloat($card.data('tax-pct')) || 0;
            var currency  = $card.data('currency') || '$';

            // Read URL parameters
            var params    = new URLSearchParams(window.location.search);
            var service   = params.get('service')   || $card.data('default-service');
            var price     = params.get('price')      || $card.data('default-price');
            var duration  = params.get('duration')   || $card.data('default-duration');
            var workshop  = params.get('workshop')   || $card.data('default-workshop');
            var address   = params.get('address')    || $card.data('default-address');

            var priceNum  = parseFloat(price) || 0;
            var taxAmount = priceNum * (taxPct / 100);
            var total     = priceNum + taxAmount;

            // Populate dynamic fields
            $card.find('[data-field="service"]').text(service);
            $card.find('[data-field="duration"]').text(duration);
            $card.find('[data-field="meta-price"]').text(currency + formatPrice(priceNum));
            $card.find('[data-field="workshop"]').text(workshop);
            $card.find('[data-field="address"]').text(address);
            $card.find('[data-field="price-value"]').text(currency + formatPrice(priceNum));
            $card.find('[data-field="tax-value"]').text(currency + formatPrice(taxAmount));
            $card.find('[data-field="total-value"]').text(currency + formatPrice(total));

            // Fill CF7 hidden fields (if present on the page)
            fillCF7HiddenFields(service, price, duration, workshop, total);
        });
    }

    /**
     * Format number to 2 decimal places with commas
     */
    function formatPrice(num) {
        return num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    /**
     * Fill Contact Form 7 hidden fields with URL param values.
     * CF7 hidden fields use: [hidden field_name default:get]
     * They render as <input type="hidden" name="field_name" value="">
     * We fill them via JS in case default:get doesn't work or for extra safety.
     */
    function fillCF7HiddenFields(service, price, duration, workshop, total) {
        // Target CF7 hidden inputs by name
        var mappings = {
            'service':  service  || '',
            'price':    price    || '',
            'duration': duration || '',
            'workshop': workshop || '',
            'total':    total ? total.toFixed(2) : ''
        };

        $.each(mappings, function(name, value) {
            $('input[name="' + name + '"]').val(value);
        });
    }

    // Initialize on document ready
    $(document).ready(function() {
        initServiceSummary(null);
    });

    // Reinitialize for Elementor editor (live preview)
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/service-summary.default', function($scope) {
                initServiceSummary($scope);
            });
        }
    });

})(jQuery);
