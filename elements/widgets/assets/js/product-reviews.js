(function($) {
    'use strict';

    var RMTProductReviews = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            var self = this;

            // Like button click
            $(document).on('click', '.rmt-like-btn', function(e) {
                e.preventDefault();
                self.handleVote($(this), 'like');
            });

            // Dislike button click
            $(document).on('click', '.rmt-dislike-btn', function(e) {
                e.preventDefault();
                self.handleVote($(this), 'dislike');
            });
        },

        handleVote: function($btn, action) {
            var $reviewItem = $btn.closest('.rmt-review-item');
            var reviewId = $reviewItem.data('review-id');
            var $actions = $reviewItem.find('.rmt-review-actions');
            var $likeBtn = $actions.find('.rmt-like-btn');
            var $dislikeBtn = $actions.find('.rmt-dislike-btn');

            // Check if already voted
            var storageKey = 'rmt_review_vote_' + reviewId;
            var existingVote = localStorage.getItem(storageKey);

            // If clicking the same button that's already active, remove the vote
            if ($btn.hasClass('active')) {
                $btn.removeClass('active');
                localStorage.removeItem(storageKey);
                return;
            }

            // Remove active from both buttons
            $likeBtn.removeClass('active');
            $dislikeBtn.removeClass('active');

            // Add active to clicked button
            $btn.addClass('active');

            // Store the vote in localStorage
            localStorage.setItem(storageKey, action);

            // Optional: Send AJAX to save vote (uncomment if backend support is added)
            /*
            $.ajax({
                url: rmt_reviews.ajax_url,
                type: 'POST',
                data: {
                    action: 'rmt_review_vote',
                    review_id: reviewId,
                    vote_type: action,
                    nonce: rmt_reviews.nonce
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Vote recorded');
                    }
                }
            });
            */
        },

        // Restore votes from localStorage on page load
        restoreVotes: function() {
            $('.rmt-review-item').each(function() {
                var reviewId = $(this).data('review-id');
                var storageKey = 'rmt_review_vote_' + reviewId;
                var savedVote = localStorage.getItem(storageKey);

                if (savedVote) {
                    var $actions = $(this).find('.rmt-review-actions');
                    if (savedVote === 'like') {
                        $actions.find('.rmt-like-btn').addClass('active');
                    } else if (savedVote === 'dislike') {
                        $actions.find('.rmt-dislike-btn').addClass('active');
                    }
                }
            });
        }
    };

    $(document).ready(function() {
        RMTProductReviews.init();
        RMTProductReviews.restoreVotes();
    });

    // Also init on Elementor frontend load
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/product-reviews.default', function() {
            RMTProductReviews.restoreVotes();
        });
    });

})(jQuery);
