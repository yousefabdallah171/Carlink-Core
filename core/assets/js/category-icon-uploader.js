/**
 * Product Category Icon Uploader
 * Handles media library selection for category icons
 */

(function($) {
    'use strict';

    let categoryIconFrame;

    $(document).on('click', '.rmt-upload-icon-btn', function(e) {
        e.preventDefault();

        // If the media frame already exists, reopen it
        if (categoryIconFrame) {
            categoryIconFrame.open();
            return;
        }

        // Create the media frame
        categoryIconFrame = wp.media.frames.categoryIconFrame = wp.media({
            title: wp.i18n.__('Select Category Icon', 'rakmyat-core'),
            button: {
                text: wp.i18n.__('Use this image', 'rakmyat-core')
            },
            library: {
                type: 'image'
            },
            multiple: false
        });

        // When an image is selected, run a callback
        categoryIconFrame.on('select', function() {
            const attachment = categoryIconFrame.state().get('selection').first().toJSON();

            // Update the hidden input
            $('#product_cat_icon_id').val(attachment.id);

            // Show the preview image
            $('#icon-preview-img')
                .attr('src', attachment.url)
                .show();

            // Show the remove button
            $('.rmt-remove-icon-btn').show();
        });

        // Finally, open the modal
        categoryIconFrame.open();
    });

    // Handle remove button
    $(document).on('click', '.rmt-remove-icon-btn', function(e) {
        e.preventDefault();

        // Clear the hidden input
        $('#product_cat_icon_id').val('');

        // Hide the preview image
        $('#icon-preview-img').hide();

        // Hide this button
        $(this).hide();
    });

})(jQuery);
