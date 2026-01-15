/**
 * MEC Starter Addons Admin JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Featured event checkbox star color change (for edit page)
        $('input[name="mecas_featured"]').on('change', function() {
            const $star = $(this).siblings('span').find('.dashicons-star-filled');
            if ($(this).is(':checked')) {
                $star.css('color', '#f0b849');
            } else {
                $star.css('color', '#ccc');
            }
        });
        
        // Featured toggle in events list
        initFeaturedToggle();
    });
    
    function initFeaturedToggle() {
        $(document).on('change', '.mecas-featured-checkbox', function() {
            const $checkbox = $(this);
            const $toggle = $checkbox.closest('.mecas-featured-toggle');
            const postId = $checkbox.data('post-id');
            const featured = $checkbox.is(':checked') ? '1' : '0';
            
            // Check if mecas_admin is available
            if (typeof mecas_admin === 'undefined') {
                console.error('MECAS Admin: mecas_admin not defined');
                return;
            }
            
            // Add loading state
            $toggle.addClass('loading');
            
            $.ajax({
                url: mecas_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_toggle_featured',
                    nonce: mecas_admin.nonce,
                    post_id: postId,
                    featured: featured
                },
                success: function(response) {
                    $toggle.removeClass('loading');
                    
                    if (response.success) {
                        // Show brief success indicator
                        $toggle.css('opacity', '1');
                    } else {
                        // Revert checkbox state on error
                        $checkbox.prop('checked', !$checkbox.is(':checked'));
                        alert('Error: ' + (response.data.message || 'Could not update featured status'));
                    }
                },
                error: function() {
                    $toggle.removeClass('loading');
                    // Revert checkbox state on error
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                    alert('Error: Could not connect to server');
                }
            });
        });
    }

})(jQuery);
