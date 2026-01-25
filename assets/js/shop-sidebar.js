(function($) {
    'use strict';

    var RMTShopSidebar = {
        init: function() {
            this.bindEvents();
            this.initPriceSlider();
            this.createOverlay();
        },

        bindEvents: function() {
            var self = this;

            // Accordion toggle
            $(document).on('click', '.rmt-filter-header', function() {
                var $section = $(this).closest('.rmt-filter-section');
                $section.toggleClass('rmt-filter-open');
            });

            // Checkbox/Radio filter change
            $(document).on('change', '.rmt-filter-item input[type="checkbox"], .rmt-filter-item input[type="radio"]', function() {
                self.applyFilters();
            });

            // Price apply button
            $(document).on('click', '.rmt-price-apply', function() {
                self.applyFilters();
            });

            // Mobile toggle
            $(document).on('click', '.rmt-filter-toggle-btn', function() {
                self.openSidebar();
            });

            // Mobile close
            $(document).on('click', '.rmt-filter-close-btn, .rmt-sidebar-overlay', function() {
                self.closeSidebar();
            });

            // Price input change
            $(document).on('change', '.rmt-price-min, .rmt-price-max', function() {
                self.updatePriceSlider();
            });
        },

        createOverlay: function() {
            if ($('.rmt-sidebar-overlay').length === 0) {
                $('body').append('<div class="rmt-sidebar-overlay"></div>');
            }
        },

        openSidebar: function() {
            $('.rmt-shop-sidebar').addClass('rmt-sidebar-open');
            $('.rmt-sidebar-overlay').addClass('active');
            $('body').css('overflow', 'hidden');
        },

        closeSidebar: function() {
            $('.rmt-shop-sidebar').removeClass('rmt-sidebar-open');
            $('.rmt-sidebar-overlay').removeClass('active');
            $('body').css('overflow', '');
        },

        initPriceSlider: function() {
            var self = this;
            var $slider = $('.rmt-price-slider');

            if ($slider.length === 0) return;

            var min = parseFloat($slider.data('min'));
            var max = parseFloat($slider.data('max'));
            var currentMin = parseFloat($slider.data('current-min'));
            var currentMax = parseFloat($slider.data('current-max'));

            var $rangeMin = $slider.find('.rmt-range-min');
            var $rangeMax = $slider.find('.rmt-range-max');
            var $fill = $slider.find('.rmt-price-range-fill');

            // Update fill position
            function updateFill() {
                var minVal = parseFloat($rangeMin.val());
                var maxVal = parseFloat($rangeMax.val());
                var range = max - min;

                var leftPercent = ((minVal - min) / range) * 100;
                var rightPercent = ((maxVal - min) / range) * 100;

                $fill.css({
                    'left': leftPercent + '%',
                    'width': (rightPercent - leftPercent) + '%'
                });

                // Update inputs
                $('.rmt-price-min').val(Math.floor(minVal));
                $('.rmt-price-max').val(Math.ceil(maxVal));
            }

            // Range slider events
            $rangeMin.on('input', function() {
                var minVal = parseFloat($(this).val());
                var maxVal = parseFloat($rangeMax.val());

                if (minVal > maxVal - 10) {
                    $(this).val(maxVal - 10);
                }
                updateFill();
            });

            $rangeMax.on('input', function() {
                var minVal = parseFloat($rangeMin.val());
                var maxVal = parseFloat($(this).val());

                if (maxVal < minVal + 10) {
                    $(this).val(minVal + 10);
                }
                updateFill();
            });

            // Initial fill
            updateFill();
        },

        updatePriceSlider: function() {
            var minVal = parseFloat($('.rmt-price-min').val());
            var maxVal = parseFloat($('.rmt-price-max').val());

            $('.rmt-range-min').val(minVal);
            $('.rmt-range-max').val(maxVal);

            // Trigger input event to update fill
            $('.rmt-range-min').trigger('input');
        },

        applyFilters: function() {
            var params = new URLSearchParams(window.location.search);

            // Clear existing filter params
            params.delete('product_cat');
            params.delete('product_brand');
            params.delete('min_price');
            params.delete('max_price');
            params.delete('availability');
            params.delete('collection');
            params.delete('rating_filter');

            // Category filter
            var categories = [];
            $('input[name="product_cat"]:checked').each(function() {
                categories.push($(this).val());
            });
            if (categories.length > 0) {
                params.set('product_cat', categories.join(','));
            }

            // Brand filter
            var brands = [];
            $('input[name="product_brand"]:checked').each(function() {
                brands.push($(this).val());
            });
            if (brands.length > 0) {
                params.set('product_brand', brands.join(','));
            }

            // Price filter
            var minPrice = $('.rmt-price-min').val();
            var maxPrice = $('.rmt-price-max').val();
            var $slider = $('.rmt-price-slider');

            if ($slider.length > 0) {
                var defaultMin = $slider.data('min');
                var defaultMax = $slider.data('max');

                if (parseFloat(minPrice) > defaultMin) {
                    params.set('min_price', minPrice);
                }
                if (parseFloat(maxPrice) < defaultMax) {
                    params.set('max_price', maxPrice);
                }
            }

            // Availability filter
            var availability = $('input[name="availability"]:checked').val();
            if (availability) {
                params.set('availability', availability);
            }

            // Collections filter
            var collections = [];
            $('input[name="collection"]:checked').each(function() {
                collections.push($(this).val());
            });
            if (collections.length > 0) {
                params.set('collection', collections.join(','));
            }

            // Rating filter
            var rating = $('input[name="rating_filter"]:checked').val();
            if (rating) {
                params.set('rating_filter', rating);
            }

            // Build URL and redirect
            var newUrl = rmt_sidebar.shop_url;
            var paramString = params.toString();

            if (paramString) {
                newUrl += '?' + paramString;
            }

            window.location.href = newUrl;
        }
    };

    $(document).ready(function() {
        RMTShopSidebar.init();
    });

})(jQuery);
