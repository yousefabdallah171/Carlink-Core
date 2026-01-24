(function ($) {
    "use strict";

    var WidgetGlassSliderHandler = function ($scope, $) {
        var $slider = $scope.find('.rmt-glass-slider');

        if (!$slider.length) return;

        var autoplay = $slider.data('autoplay') === 'yes';
        var delay = $slider.data('delay') || 5000;

        var swiperOptions = {
            slidesPerView: 1,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        };

        if (autoplay) {
            swiperOptions.autoplay = {
                delay: delay,
            };
        }

        new Swiper($slider[0], swiperOptions);
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/glassmorphic-hero-slider.default', WidgetGlassSliderHandler);
    });

})(jQuery);