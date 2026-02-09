(function ($) {
    'use strict';

    function initShowcaseSlider($scope) {
        var $slider = $scope.find('.rmt-showcase-slider');
        if (!$slider.length) return;

        var settings = $slider.data('settings') || {};
        var $slides  = $slider.find('.rmt-showcase-slider__slide');
        var $dots    = $slider.find('.rmt-showcase-slider__dot');
        var total    = $slides.length;

        if (total <= 1) return;

        var current       = 0;
        var isAnimating   = false;
        var autoplayTimer = null;
        var effect        = settings.effect || 'fade';
        var speed         = settings.speed || 600;

        /**
         * Go to a specific slide
         */
        function goToSlide(index, direction) {
            if (isAnimating || index === current) return;

            // Handle looping
            if (settings.loop) {
                index = ((index % total) + total) % total;
            } else {
                if (index < 0 || index >= total) return;
            }

            isAnimating = true;

            var $currentSlide = $slides.eq(current);
            var $nextSlide    = $slides.eq(index);

            if (effect === 'slide') {
                // Determine direction
                var dir = direction || (index > current ? 'left' : 'right');

                // Position the entering slide
                $nextSlide
                    .removeClass('rmt-showcase-slider__slide--exit-left rmt-showcase-slider__slide--exit-right')
                    .addClass(dir === 'left' ? 'rmt-showcase-slider__slide--enter-right' : 'rmt-showcase-slider__slide--enter-left')
                    .addClass('rmt-showcase-slider__slide--active');

                // Force reflow
                $nextSlide[0].offsetHeight;

                // Remove enter class to trigger transition
                $nextSlide.removeClass('rmt-showcase-slider__slide--enter-left rmt-showcase-slider__slide--enter-right');

                // Exit current slide
                $currentSlide.addClass(dir === 'left' ? 'rmt-showcase-slider__slide--exit-left' : 'rmt-showcase-slider__slide--exit-right');

                setTimeout(function () {
                    $currentSlide
                        .removeClass('rmt-showcase-slider__slide--active rmt-showcase-slider__slide--exit-left rmt-showcase-slider__slide--exit-right');
                    isAnimating = false;
                }, speed);

            } else {
                // Fade
                $currentSlide.removeClass('rmt-showcase-slider__slide--active');
                $nextSlide.addClass('rmt-showcase-slider__slide--active');

                setTimeout(function () {
                    isAnimating = false;
                }, speed);
            }

            // Update dots
            $dots.removeClass('rmt-showcase-slider__dot--active');
            $dots.eq(index).addClass('rmt-showcase-slider__dot--active');

            current = index;
        }

        /**
         * Autoplay
         */
        function startAutoplay() {
            if (settings.autoplay && total > 1) {
                autoplayTimer = setInterval(function () {
                    goToSlide(current + 1, 'left');
                }, settings.autoplay_speed || 5000);
            }
        }

        function stopAutoplay() {
            if (autoplayTimer) {
                clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        }

        function resetAutoplay() {
            stopAutoplay();
            startAutoplay();
        }

        // Set transition speed via CSS custom property
        $slider.css('--rmt-slider-speed', speed + 'ms');
        $slides.each(function () {
            if (effect === 'fade') {
                $(this).css({
                    'transition-duration': speed + 'ms'
                });
            } else {
                $(this).css({
                    'transition-duration': speed + 'ms'
                });
            }
        });

        // Arrow navigation
        $slider.find('.rmt-showcase-slider__arrow--prev').on('click', function () {
            goToSlide(current - 1, 'right');
            resetAutoplay();
        });

        $slider.find('.rmt-showcase-slider__arrow--next').on('click', function () {
            goToSlide(current + 1, 'left');
            resetAutoplay();
        });

        // Dot navigation
        $dots.on('click', function () {
            var index = parseInt($(this).data('index'), 10);
            var direction = index > current ? 'left' : 'right';
            goToSlide(index, direction);
            resetAutoplay();
        });

        // Pause on hover
        if (settings.pause_on_hover) {
            $slider.on('mouseenter', function () {
                stopAutoplay();
            });
            $slider.on('mouseleave', function () {
                startAutoplay();
            });
        }

        // Keyboard navigation
        $slider.attr('tabindex', '0');
        $slider.on('keydown', function (e) {
            if (e.key === 'ArrowLeft') {
                goToSlide(current - 1, 'right');
                resetAutoplay();
            } else if (e.key === 'ArrowRight') {
                goToSlide(current + 1, 'left');
                resetAutoplay();
            }
        });

        // Touch / swipe support
        var touchStartX = 0;
        var touchEndX   = 0;
        var touchThreshold = 50;

        $slider[0].addEventListener('touchstart', function (e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        $slider[0].addEventListener('touchend', function (e) {
            touchEndX = e.changedTouches[0].screenX;
            var diff  = touchStartX - touchEndX;
            if (Math.abs(diff) > touchThreshold) {
                if (diff > 0) {
                    goToSlide(current + 1, 'left');
                } else {
                    goToSlide(current - 1, 'right');
                }
                resetAutoplay();
            }
        }, { passive: true });

        // Init
        startAutoplay();
    }

    // Elementor frontend hook
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/brand-showcase-slider.default',
            initShowcaseSlider
        );
    });

})(jQuery);
