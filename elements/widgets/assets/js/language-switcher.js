/**
 * Language Switcher Widget — JS
 * Handles dropdown open/close, outside-click dismiss, keyboard support.
 */
(function ($) {
    'use strict';

    /**
     * Initialise all .rmt-ls-switcher instances on the page.
     */
    function initLanguageSwitcher() {
        $('.rmt-ls-switcher').each(function () {
            var $switcher = $(this);
            var $btn      = $switcher.find('.rmt-ls-btn');

            // Prevent double-binding on re-init
            $btn.off('click.rmtLs');

            $btn.on('click.rmtLs', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var isOpen = $switcher.hasClass('rmt-ls-open');

                // Close every other open switcher first
                $('.rmt-ls-switcher').not($switcher)
                    .removeClass('rmt-ls-open')
                    .find('.rmt-ls-btn')
                    .attr('aria-expanded', 'false');

                // Toggle this one
                $switcher.toggleClass('rmt-ls-open', !isOpen);
                $btn.attr('aria-expanded', !isOpen ? 'true' : 'false');
            });
        });
    }

    /* ── Close on outside click ── */
    $(document).on('click.rmtLs', function (e) {
        if (!$(e.target).closest('.rmt-ls-switcher').length) {
            $('.rmt-ls-switcher')
                .removeClass('rmt-ls-open')
                .find('.rmt-ls-btn')
                .attr('aria-expanded', 'false');
        }
    });

    /* ── Close on Escape key ── */
    $(document).on('keydown.rmtLs', function (e) {
        if (e.key === 'Escape') {
            $('.rmt-ls-switcher')
                .removeClass('rmt-ls-open')
                .find('.rmt-ls-btn')
                .attr('aria-expanded', 'false');
        }
    });

    /* ── DOM ready ── */
    $(document).ready(function () {
        initLanguageSwitcher();
    });

    /* ── Elementor frontend init (re-run after widget renders) ── */
    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/language-switcher.default',
                function () {
                    initLanguageSwitcher();
                }
            );
        }
    });

})(jQuery);
