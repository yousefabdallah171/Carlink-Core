(function ($) {
    "use strict";

    /**
     * Woo Category Search Widget Handler
     * 
     * @param {jQuery} $scope The widget wrapper element as a jQuery element
     * @param {jQuery} $      The jQuery context
     */
    var WidgetWooCategorySearchHandler = function ($scope, $) {
        var $form = $scope.find('.rmt-amazon-search');
        var $select = $scope.find('select[name="product_cat"]');
        var $input = $scope.find('.rmt-input-col');

        // 1. Auto-focus input when category is changed
        $select.on('change', function () {
            $input.focus();
        });

        // 2. Add 'active' class to wrapper when input is focused (for styling)
        $input.on('focus', function () {
            $form.addClass('rmt-search-focused');
        }).on('blur', function () {
            $form.removeClass('rmt-search-focused');
        });

        // 3. Prevent empty searches (Optional)
        $form.on('submit', function (e) {
            if ($input.val().trim() === "" && $select.val() === "") {
                e.preventDefault();
                $input.addClass('rmt-input-error');
                setTimeout(function() {
                    $input.removeClass('rmt-input-error');
                }, 500);
            }
        });
    };

    // Make sure to run this when Elementor is ready
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/woo-category-search.default', WidgetWooCategorySearchHandler);
    });

})(jQuery);