(function () {
    'use strict';

    function initFaqAccordion(scope) {
        var container = scope.querySelector
            ? scope.querySelector('.rmt-faq-accordion')
            : scope[0] && scope[0].querySelector('.rmt-faq-accordion');
        if (!container) return;

        var headers = container.querySelectorAll('.rmt-faq-header');

        headers.forEach(function (header) {
            header.addEventListener('click', function (e) {
                e.preventDefault();
                var item = header.closest('.rmt-faq-item');
                var body = item.querySelector('.rmt-faq-body');
                var isOpen = item.classList.contains('is-open');

                // Close all siblings
                var allItems = container.querySelectorAll('.rmt-faq-item');
                allItems.forEach(function (other) {
                    if (other !== item && other.classList.contains('is-open')) {
                        other.classList.remove('is-open');
                        var otherBody = other.querySelector('.rmt-faq-body');
                        slideUp(otherBody);
                        other.querySelector('.rmt-faq-header').setAttribute('aria-expanded', 'false');
                    }
                });

                // Toggle current
                if (isOpen) {
                    item.classList.remove('is-open');
                    slideUp(body);
                    header.setAttribute('aria-expanded', 'false');
                } else {
                    item.classList.add('is-open');
                    slideDown(body);
                    header.setAttribute('aria-expanded', 'true');
                }
            });
        });
    }

    function slideDown(el) {
        el.setAttribute('data-animating', 'true');
        el.style.display = 'block';
        var height = el.scrollHeight;
        el.style.overflow = 'hidden';
        el.style.height = '0';
        el.style.transition = 'height 0.3s ease';
        requestAnimationFrame(function () {
            el.style.height = height + 'px';
        });
        el.addEventListener('transitionend', function handler() {
            el.style.height = '';
            el.style.overflow = '';
            el.style.transition = '';
            el.removeAttribute('data-animating');
            el.removeEventListener('transitionend', handler);
        });
    }

    function slideUp(el) {
        el.setAttribute('data-animating', 'true');
        el.style.height = el.scrollHeight + 'px';
        el.style.overflow = 'hidden';
        el.style.transition = 'height 0.3s ease';
        requestAnimationFrame(function () {
            el.style.height = '0';
        });
        el.addEventListener('transitionend', function handler() {
            el.style.display = 'none';
            el.style.height = '';
            el.style.overflow = '';
            el.style.transition = '';
            el.removeAttribute('data-animating');
            el.removeEventListener('transitionend', handler);
        });
    }

    // Frontend init
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.rmt-faq-accordion').forEach(function (el) {
            initFaqAccordion(el.closest('.elementor-widget') || el.parentElement);
        });
    });

    // Elementor editor init
    if (window.elementorFrontend && elementorFrontend.hooks) {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/faq-accordion.default',
            function ($scope) {
                initFaqAccordion($scope[0] || $scope);
            }
        );
    } else {
        document.addEventListener('DOMContentLoaded', function () {
            if (window.elementorFrontend && elementorFrontend.hooks) {
                elementorFrontend.hooks.addAction(
                    'frontend/element_ready/faq-accordion.default',
                    function ($scope) {
                        initFaqAccordion($scope[0] || $scope);
                    }
                );
            }
        });
    }
})();
