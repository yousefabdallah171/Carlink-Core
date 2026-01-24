(function ($) {
    "use strict";

    var WidgetTestimonialSliderHandler = function ($scope, $) {
        var $wrapper = $scope.find('.rmt-slider-wrapper');
        var $sliderContainer = $scope.find('.rmt-testimonial-slider');

        if (!$sliderContainer.length) return;

        var settings = $sliderContainer.data('settings');

        new Swiper($sliderContainer[0], {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: settings.loop,
            autoplay: settings.autoplay,
            navigation: {
                nextEl: $wrapper.find('.rmt-nav-next')[0],
                prevEl: $wrapper.find('.rmt-nav-prev')[0],
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/modern-testimonial-slider.default', WidgetTestimonialSliderHandler);
    });

})(jQuery);