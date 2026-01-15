/**
 * MEC Organizer Manager - Frontend Scripts
 */

(function($) {
    'use strict';

    const MECOM = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            // Share profile functionality
            $(document).on('click', '.mecom-share-button', this.handleShare);
            
            // Favorite functionality (future)
            $(document).on('click', '.mecom-favorite-button', this.handleFavorite);
        },

        handleShare: function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var title = $btn.data('title') || document.title;
            var url = $btn.data('url') || window.location.href;
            
            if (navigator.share) {
                navigator.share({
                    title: title,
                    url: url
                }).catch(function(err) {
                    // User cancelled or error
                    console.log('Share cancelled or failed', err);
                });
            } else {
                // Fallback to clipboard
                navigator.clipboard.writeText(url).then(function() {
                    MECOM.showNotification(mecom_ajax.i18n.link_copied || 'Link copied!');
                }).catch(function() {
                    // Fallback prompt
                    prompt('Copy this link:', url);
                });
            }
        },

        handleFavorite: function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var organizerId = $btn.data('organizer-id');
            
            // Toggle visual state immediately
            $btn.toggleClass('is-favorite');
            
            // TODO: Implement AJAX save to user favorites
            console.log('Toggle favorite for organizer:', organizerId);
        },

        showNotification: function(message, type) {
            type = type || 'success';
            
            var $notification = $('<div class="mecom-notification mecom-notification-' + type + '">' + message + '</div>');
            
            $('body').append($notification);
            
            setTimeout(function() {
                $notification.addClass('show');
            }, 10);
            
            setTimeout(function() {
                $notification.removeClass('show');
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            }, 3000);
        }
    };

    $(document).ready(function() {
        MECOM.init();
    });

})(jQuery);
