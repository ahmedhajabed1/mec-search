/**
 * MEC Starter Addons - Scripts
 * Version 4.3.0 - Separate Teacher and Events Search Widgets
 */

(function($) {
    'use strict';

    const MECAS = {
        debounceTimer: null,
        debounceDelay: 300,
        geoDetected: false,

        init: function() {
            this.bindEvents();
            this.initAutoGeolocation();
            this.initTeacherSearch();
            this.initSaveFollowButtons();
            this.initDashboardEditToggle();
            this.initToastContainer();
        },
        
        // ========================================
        // TOAST NOTIFICATION SYSTEM
        // ========================================
        
        initToastContainer: function() {
            // Create toast container if it doesn't exist
            if (!$('#mecas-toast-container').length) {
                $('body').append('<div id="mecas-toast-container" class="mecas-toast-container"></div>');
            }
        },
        
        /**
         * Show a toast notification
         * @param {Object} options - Toast options
         * @param {string} options.title - Toast title
         * @param {string} options.message - Toast message
         * @param {string} options.type - Toast type: 'follow', 'unfollow', 'save', 'success', 'error'
         * @param {number} options.duration - Duration in ms (default: 4000)
         */
        showToast: function(options) {
            var defaults = {
                title: '',
                message: '',
                type: 'success',
                duration: 4000
            };
            
            var settings = $.extend({}, defaults, options);
            var $container = $('#mecas-toast-container');
            
            if (!$container.length) {
                this.initToastContainer();
                $container = $('#mecas-toast-container');
            }
            
            // Heart icon for follow/unfollow
            var heartIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>';
            
            // Bookmark icon for save
            var bookmarkIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>';
            
            // Check icon for success
            var checkIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
            
            // Select icon based on type
            var icon = heartIcon;
            if (settings.type === 'save' || settings.type === 'unsave') {
                icon = bookmarkIcon;
            } else if (settings.type === 'success') {
                icon = checkIcon;
            }
            
            var toastId = 'mecas-toast-' + Date.now();
            
            var toastHtml = '<div id="' + toastId + '" class="mecas-toast mecas-toast-' + settings.type + '">' +
                '<span class="mecas-toast-icon">' + icon + '</span>' +
                '<div class="mecas-toast-content">' +
                    '<p class="mecas-toast-title">' + settings.title + '</p>' +
                    '<p class="mecas-toast-message">' + settings.message + '</p>' +
                '</div>' +
                '<button type="button" class="mecas-toast-close">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
                '</button>' +
            '</div>';
            
            var $toast = $(toastHtml);
            $container.prepend($toast);
            
            // Close button handler
            $toast.find('.mecas-toast-close').on('click', function() {
                $toast.addClass('mecas-toast-hide');
                setTimeout(function() {
                    $toast.remove();
                }, 300);
            });
            
            // Auto remove after duration
            if (settings.duration > 0) {
                setTimeout(function() {
                    if ($toast.length && !$toast.hasClass('mecas-toast-hide')) {
                        $toast.addClass('mecas-toast-hide');
                        setTimeout(function() {
                            $toast.remove();
                        }, 300);
                    }
                }, settings.duration);
            }
            
            return $toast;
        },

        bindEvents: function() {
            var self = this;
            
            // Modal triggers (desktop and mobile)
            $(document).on('click', '.mecas-trigger-button, .mecas-mobile-trigger', function(e) {
                self.openModal(e);
            });
            
            $(document).on('click', '.mecas-modal-close', function(e) {
                self.closeModal(e);
            });
            
            $(document).on('click', '.mecas-modal-backdrop', function(e) {
                self.handleBackdropClick(e);
            });
            
            $(document).on('keydown', function(e) {
                self.handleEscKey(e);
            });

            // Category tabs
            $(document).on('click', '.mecas-category-tab', this.handleCategoryTab.bind(this));

            // Filter changes
            $(document).on('change', '.mecas-filter-select', this.handleFilterChange.bind(this));

            // Form submit (old event search - just submits normally, no preview)
            $(document).on('submit', '.mecas-search-form', this.handleFormSubmit.bind(this));
            
            // Teacher Search Widget form submit (AJAX)
            $(document).on('submit', '.mecas-teacher-search-form', this.handleTeacherSearchSubmit.bind(this));
            
            // Events Location Search Widget form submit (AJAX)
            $(document).on('submit', '.mecas-events-loc-form', this.handleEventsLocationSearchSubmit.bind(this));
            
            // Save Event buttons (universal class)
            $(document).on('click', '.mecas-save-event-btn', this.handleSaveEventClick.bind(this));
            
            // Follow Organizer buttons (universal class)
            $(document).on('click', '.mecas-follow-btn', this.handleFollowOrganizerClick.bind(this));
            
            // ========================================
            // EDIT PROFILE BUTTON - Opens Dashboard Edit Widget
            // ========================================
            $(document).on('click', '.mecas-ajax-edit-trigger, .mecas-edit-profile-trigger, [data-edit-profile]', function(e) {
                e.preventDefault();
                self.openDashboardEdit();
            });
            
            // Close button for Dashboard Edit
            $(document).on('click', '.mecas-dashboard-close', function(e) {
                e.preventDefault();
                self.closeDashboardEdit();
            });
        },

        // ========================================
        // AUTO GEOLOCATION ON PAGE LOAD
        // ========================================

        initAutoGeolocation: function() {
            const self = this;
            
            // Handle old event search wrappers
            $('.mecas-search-wrapper').each(function() {
                const $wrapper = $(this);
                const autoDetect = $wrapper.data('auto-detect');
                const enableGeo = $wrapper.data('enable-geolocation');
                const $input = $wrapper.find('.mecas-location-input');
                
                if ((autoDetect === 'true' || autoDetect === true) && 
                    (enableGeo === 'true' || enableGeo === true) && 
                    $input.length && !$input.val()) {
                    
                    setTimeout(function() {
                        self.autoDetectLocation($wrapper, $input, '.mecas-location-loading');
                    }, 500);
                }
            });
            
            // Handle Teacher Search wrappers
            $('.mecas-teacher-search-wrapper').each(function() {
                const $wrapper = $(this);
                const autoDetect = $wrapper.data('auto-detect');
                const enableGeo = $wrapper.data('enable-geolocation');
                const $input = $wrapper.find('.mecas-teacher-search-input');
                
                if ((autoDetect === 'true' || autoDetect === true) && 
                    (enableGeo === 'true' || enableGeo === true) && 
                    $input.length && !$input.val()) {
                    
                    setTimeout(function() {
                        self.autoDetectLocation($wrapper, $input, '.mecas-teacher-search-loading');
                    }, 500);
                }
            });
            
            // Handle Events Location Search wrappers
            $('.mecas-events-loc-wrapper').each(function() {
                const $wrapper = $(this);
                const autoDetect = $wrapper.data('auto-detect');
                const enableGeo = $wrapper.data('enable-geolocation');
                const $input = $wrapper.find('.mecas-events-loc-input');
                
                if ((autoDetect === 'true' || autoDetect === true) && 
                    (enableGeo === 'true' || enableGeo === true) && 
                    $input.length && !$input.val()) {
                    
                    setTimeout(function() {
                        self.autoDetectLocation($wrapper, $input, '.mecas-events-loc-loading');
                    }, 500);
                }
            });
        },

        autoDetectLocation: function($wrapper, $input, loadingSelector) {
            const self = this;
            const $loading = $wrapper.find(loadingSelector);
            const originalPlaceholder = $input.attr('placeholder');

            if (!navigator.geolocation) {
                return;
            }

            $loading.show();
            $input.attr('placeholder', mecas_ajax.i18n.detecting);

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    self.reverseGeocode(position.coords.latitude, position.coords.longitude, $input, $loading, originalPlaceholder, $wrapper);
                },
                function(error) {
                    console.log('Geolocation error:', error.message);
                    $loading.hide();
                    $input.attr('placeholder', originalPlaceholder || mecas_ajax.i18n.enter_location);
                },
                {
                    enableHighAccuracy: false,
                    timeout: 10000,
                    maximumAge: 300000
                }
            );
        },

        reverseGeocode: function(lat, lng, $input, $loading, originalPlaceholder, $wrapper) {
            const self = this;
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_reverse_geocode',
                    nonce: mecas_ajax.nonce,
                    lat: lat,
                    lng: lng
                },
                success: function(response) {
                    $loading.hide();
                    
                    if (response.success && response.data.location) {
                        $input.val(response.data.location);
                        $input.attr('placeholder', originalPlaceholder || 'City, State');
                        self.geoDetected = true;
                        
                        // If it's a teacher search widget, auto-search
                        if ($wrapper.hasClass('mecas-teacher-search-wrapper')) {
                            self.searchTeachers($wrapper, response.data.location, 1);
                        }
                    } else {
                        $input.attr('placeholder', originalPlaceholder || mecas_ajax.i18n.enter_location);
                    }
                },
                error: function() {
                    $loading.hide();
                    $input.attr('placeholder', originalPlaceholder || mecas_ajax.i18n.enter_location);
                }
            });
        },

        // ========================================
        // SAVE EVENT & FOLLOW ORGANIZER BUTTONS
        // ========================================
        
        /**
         * Initialize save/follow buttons - check initial state
         */
        initSaveFollowButtons: function() {
            var self = this;
            
            // ========================================
            // SAVE EVENT WIDGET HANDLER (gets ID from body class)
            // ========================================
            $(document).on('click', '.mecas-save-event-btn', this.handleSaveEventWidget.bind(this));
            
            // ========================================
            // GLOBAL CLASS-BASED SAVE EVENT HANDLER
            // ========================================
            // Add class "mecas-save-event" to any element with data-event-id="123"
            // The element will automatically toggle saved state on click
            // Classes added: mecas-saved (when saved), mecas-saving (while processing)
            $(document).on('click', '.mecas-save-event', this.handleGlobalSaveEvent.bind(this));
            
            // ========================================
            // GLOBAL CLASS-BASED FOLLOW ORGANIZER HANDLER
            // ========================================
            // Add class "mecas-follow-organizer" to any element with data-organizer-id="123"
            // Classes added: mecas-following (when following), mecas-processing (while processing)
            $(document).on('click', '.mecas-follow-organizer', this.handleGlobalFollowOrganizer.bind(this));
            
            // Check saved state for all save event buttons on page
            if (typeof mecas_ajax !== 'undefined' && mecas_ajax.is_logged_in) {
                // Get all event IDs on page (both old and new class)
                var eventIds = [];
                $('.mecas-save-event-btn[data-event-id], .mecas-save-event[data-event-id]').each(function() {
                    var id = $(this).data('event-id');
                    if (id && eventIds.indexOf(id) === -1) {
                        eventIds.push(id);
                    }
                });
                
                if (eventIds.length > 0) {
                    self.checkSavedEvents(eventIds);
                }
                
                // Get all organizer IDs on page (both old and new class)
                var organizerIds = [];
                $('.mecas-follow-btn[data-organizer-id], .mecas-follow-organizer[data-organizer-id]').each(function() {
                    var id = $(this).data('organizer-id');
                    if (id && organizerIds.indexOf(id) === -1) {
                        organizerIds.push(id);
                    }
                });
                
                if (organizerIds.length > 0) {
                    self.checkFollowingOrganizers(organizerIds);
                }
            }
        },
        
        // ============================================
        // Save Event Widget Handler (detects ID from body class)
        // ============================================
        handleSaveEventWidget: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var self = this;
            var $el = $(e.currentTarget);
            
            // Get event ID from body class (post-XXXX)
            var eventId = 0;
            var bodyClasses = $('body').attr('class').split(/\s+/);
            for (var i = 0; i < bodyClasses.length; i++) {
                if (bodyClasses[i].match(/^post-\d+$/)) {
                    eventId = parseInt(bodyClasses[i].replace('post-', ''), 10);
                    break;
                }
            }
            
            // Fallback to data attribute if body class not found
            if (!eventId) {
                eventId = $el.data('event-id');
            }
            
            if (!eventId) {
                console.error('MECAS: Could not detect event ID');
                return;
            }
            
            // Check if user is logged in
            if (!mecas_ajax.is_logged_in) {
                var loginUrl = $el.data('login-url') || mecas_ajax.login_url;
                if (confirm(mecas_ajax.i18n.login_to_save || 'Please log in to save events.\n\nWould you like to log in now?')) {
                    window.location.href = loginUrl;
                }
                return;
            }
            
            // Prevent double-click
            if ($el.hasClass('mecas-saving')) return;
            
            $el.addClass('mecas-saving');
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_save_event',
                    nonce: mecas_ajax.nonce,
                    event_id: eventId
                },
                success: function(response) {
                    $el.removeClass('mecas-saving');
                    
                    if (response.success) {
                        var eventName = response.data.event_name || 'this event';
                        
                        // Show toast notification
                        self.showToast({
                            title: 'Event Saved!',
                            message: eventName + ' has been added to your saved events',
                            type: 'save',
                            duration: 4000
                        });
                    } else {
                        self.showToast({
                            title: 'Already Saved',
                            message: 'This event is already in your saved events',
                            type: 'save',
                            duration: 3000
                        });
                    }
                },
                error: function() {
                    $el.removeClass('mecas-saving');
                    alert(mecas_ajax.i18n.error || 'An error occurred');
                }
            });
        },
        
        // ============================================
        // GLOBAL Save Event Handler (class-based)
        // ============================================
        // Usage: Add class "mecas-save-event" and data-event-id="123" to any element
        // Optional: data-login-url="..." for custom login redirect
        // CSS classes toggled: mecas-saved, mecas-saving
        
        handleGlobalSaveEvent: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var self = this;
            var $el = $(e.currentTarget);
            var eventId = $el.data('event-id');
            
            if (!eventId) {
                console.error('MECAS: Missing data-event-id attribute on .mecas-save-event element');
                return;
            }
            
            // Check if user is logged in
            if (!mecas_ajax.is_logged_in) {
                var loginUrl = $el.data('login-url') || mecas_ajax.login_url;
                if (confirm(mecas_ajax.i18n.login_to_save || 'Please log in to save events.\n\nWould you like to log in now?')) {
                    window.location.href = loginUrl;
                }
                return;
            }
            
            // Prevent double-click
            if ($el.hasClass('mecas-saving')) return;
            
            var isSaved = $el.hasClass('mecas-saved');
            var action = isSaved ? 'mecas_unsave_event' : 'mecas_save_event';
            
            // Get button text data attributes (for Save Event widget)
            var saveText = $el.data('save-text') || 'Save';
            var savedText = $el.data('saved-text') || 'Saved';
            
            $el.addClass('mecas-saving');
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: action,
                    nonce: mecas_ajax.nonce,
                    event_id: eventId
                },
                success: function(response) {
                    $el.removeClass('mecas-saving');
                    
                    if (response.success) {
                        var eventName = response.data.event_name || 'this event';
                        
                        if (response.data.saved) {
                            $el.addClass('mecas-saved');
                            
                            // Update button text if it has text element
                            $el.find('.mecas-btn-text').text(savedText);
                            
                            // Add pop animation
                            $el.addClass('mecas-heart-pop');
                            setTimeout(function() {
                                $el.removeClass('mecas-heart-pop');
                            }, 600);
                            
                            // Show toast notification
                            self.showToast({
                                title: 'Event Saved!',
                                message: eventName + ' has been added to your saved events',
                                type: 'save',
                                duration: 4000
                            });
                            
                            // Trigger custom event for additional handling
                            $el.trigger('mecas:saved', [eventId, eventName]);
                        } else {
                            $el.removeClass('mecas-saved');
                            
                            // Update button text if it has text element
                            $el.find('.mecas-btn-text').text(saveText);
                            
                            // Show toast notification
                            self.showToast({
                                title: 'Event Removed',
                                message: eventName + ' has been removed from your saved events',
                                type: 'unsave',
                                duration: 4000
                            });
                            
                            // Trigger custom event for additional handling
                            $el.trigger('mecas:unsaved', [eventId, eventName]);
                        }
                    } else {
                        alert(response.data || mecas_ajax.i18n.error || 'An error occurred');
                    }
                },
                error: function() {
                    $el.removeClass('mecas-saving');
                    alert(mecas_ajax.i18n.error || 'An error occurred');
                }
            });
        },
        
        // ============================================
        // GLOBAL Follow Organizer Handler (class-based)
        // ============================================
        // Usage: Add class "mecas-follow-organizer" and data-organizer-id="123" to any element
        // Optional: data-login-url="..." for custom login redirect
        // CSS classes toggled: mecas-following, mecas-processing
        
        handleGlobalFollowOrganizer: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var self = this;
            var $el = $(e.currentTarget);
            var organizerId = $el.data('organizer-id');
            
            if (!organizerId) {
                console.error('MECAS: Missing data-organizer-id attribute on .mecas-follow-organizer element');
                return;
            }
            
            // Check if user is logged in
            if (!mecas_ajax.is_logged_in) {
                var loginUrl = $el.data('login-url') || mecas_ajax.login_url;
                if (confirm(mecas_ajax.i18n.login_to_follow || 'Please log in to follow.\n\nWould you like to log in now?')) {
                    window.location.href = loginUrl;
                }
                return;
            }
            
            // Prevent double-click
            if ($el.hasClass('mecas-processing')) return;
            
            var isFollowing = $el.hasClass('mecas-following');
            var action = isFollowing ? 'mecas_unfollow_organizer' : 'mecas_follow_organizer';
            
            $el.addClass('mecas-processing');
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: action,
                    nonce: mecas_ajax.nonce,
                    organizer_id: organizerId
                },
                success: function(response) {
                    $el.removeClass('mecas-processing');
                    
                    if (response.success) {
                        var organizerName = response.data.organizer_name || 'this teacher';
                        
                        if (response.data.following) {
                            // Add following state
                            $el.addClass('mecas-following');
                            
                            // Add pop animation
                            $el.addClass('mecas-heart-pop');
                            setTimeout(function() {
                                $el.removeClass('mecas-heart-pop');
                            }, 600);
                            
                            // Show toast notification
                            self.showToast({
                                title: 'Following!',
                                message: 'You are now following ' + organizerName,
                                type: 'follow',
                                duration: 4000
                            });
                            
                            // Trigger custom event
                            $el.trigger('mecas:followed', [organizerId, organizerName]);
                        } else {
                            // Remove following state
                            $el.removeClass('mecas-following');
                            
                            // Show toast notification
                            self.showToast({
                                title: 'Unfollowed',
                                message: 'You are no longer following ' + organizerName,
                                type: 'unfollow',
                                duration: 4000
                            });
                            
                            // Trigger custom event
                            $el.trigger('mecas:unfollowed', [organizerId, organizerName]);
                        }
                    } else {
                        alert(response.data || mecas_ajax.i18n.error || 'An error occurred');
                    }
                },
                error: function() {
                    $el.removeClass('mecas-processing');
                    alert(mecas_ajax.i18n.error || 'An error occurred');
                }
            });
        },
        
        /**
         * Check which events are already saved
         */
        checkSavedEvents: function(eventIds) {
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_check_saved_events',
                    nonce: mecas_ajax.nonce,
                    event_ids: eventIds
                },
                success: function(response) {
                    if (response.success && response.data.saved_ids) {
                        response.data.saved_ids.forEach(function(id) {
                            // Old class (widget-based)
                            $('.mecas-save-event-btn[data-event-id="' + id + '"]').addClass('is-saved');
                            // New class (global class-based)
                            $('.mecas-save-event[data-event-id="' + id + '"]').addClass('mecas-saved');
                        });
                    }
                }
            });
        },
        
        /**
         * Check which organizers are already followed
         */
        checkFollowingOrganizers: function(organizerIds) {
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_check_following',
                    nonce: mecas_ajax.nonce,
                    organizer_ids: organizerIds
                },
                success: function(response) {
                    if (response.success && response.data.following_ids) {
                        response.data.following_ids.forEach(function(id) {
                            // Old class (widget-based)
                            $('.mecas-follow-btn[data-organizer-id="' + id + '"]').addClass('is-following');
                            // New class (global class-based)
                            $('.mecas-follow-organizer[data-organizer-id="' + id + '"]').addClass('mecas-following');
                        });
                    }
                }
            });
        },
        
        // ========================================
        // DASHBOARD EDIT TOGGLE (jQuery-based)
        // ========================================
        // This handles showing/hiding the User Dashboard Edit widget
        // when clicking Edit Profile buttons
        
        initDashboardEditToggle: function() {
            var self = this;
            
            // Click handler for any Edit Profile trigger
            // Supports: .mecas-ajax-edit-trigger, .mecas-edit-profile-trigger, [data-edit-profile]
            $(document).on('click', '.mecas-ajax-edit-trigger, .mecas-edit-profile-trigger, [data-edit-profile]', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('MECAS: Edit Profile button clicked');
                self.openDashboardEdit();
            });
            
            // Close button handler
            $(document).on('click', '.mecas-dashboard-close', function(e) {
                e.preventDefault();
                console.log('MECAS: Close button clicked');
                self.closeDashboardEdit();
            });
            
            // Also expose globally for other scripts
            window.mecasOpenDashboardEdit = function() {
                self.openDashboardEdit();
            };
            
            window.mecasCloseDashboardEdit = function() {
                self.closeDashboardEdit();
            };
            
            console.log('MECAS: Dashboard edit toggle initialized');
        },
        
        openDashboardEdit: function() {
            // Find the dashboard edit container
            var $dashboard = $('#mecas-dashboard-edit-container');
            
            // Also try to find by class if ID not found
            if (!$dashboard.length) {
                $dashboard = $('.mecas-dashboard-edit').first();
            }
            
            console.log('MECAS: Looking for dashboard, found:', $dashboard.length);
            
            if ($dashboard.length) {
                // Use jQuery slideDown for smooth animation
                $dashboard.slideDown(400, function() {
                    // After slideDown completes, scroll to it
                    $('html, body').animate({
                        scrollTop: $dashboard.offset().top - 50
                    }, 400);
                });
                
                console.log('MECAS: Dashboard edit opened');
            } else {
                console.error('MECAS: Dashboard edit container not found. Make sure User Dashboard Edit widget is on the page.');
                alert('Edit Profile section not found. Please make sure the User Dashboard Edit widget is added to this page.');
            }
        },
        
        closeDashboardEdit: function() {
            var $dashboard = $('#mecas-dashboard-edit-container');
            
            if (!$dashboard.length) {
                $dashboard = $('.mecas-dashboard-edit').first();
            }
            
            if ($dashboard.length) {
                // Use jQuery slideUp for smooth animation
                $dashboard.slideUp(400);
                console.log('MECAS: Dashboard edit closed');
            }
        },
        
        /**
         * Handle Save Event button click
         */
        handleSaveEventClick: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $btn = $(e.currentTarget);
            var eventId = $btn.data('event-id');
            
            if (!eventId) {
                console.error('MECAS: No event ID found on save button');
                return;
            }
            
            // Check if logged in
            if (typeof mecas_ajax === 'undefined' || !mecas_ajax.is_logged_in) {
                // Trigger login popup or redirect
                if (typeof mecas_ajax !== 'undefined' && mecas_ajax.login_url) {
                    window.location.href = mecas_ajax.login_url;
                } else {
                    alert('Please log in to save events');
                }
                return;
            }
            
            // Prevent double-click
            if ($btn.hasClass('is-loading')) return;
            $btn.addClass('is-loading');
            
            var isSaved = $btn.hasClass('is-saved');
            var action = isSaved ? 'mecas_unsave_event' : 'mecas_save_event';
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: action,
                    nonce: mecas_ajax.nonce,
                    event_id: eventId
                },
                success: function(response) {
                    $btn.removeClass('is-loading');
                    
                    if (response.success) {
                        // Toggle saved state on ALL buttons for this event
                        var $allBtns = $('.mecas-save-event-btn[data-event-id="' + eventId + '"]');
                        
                        if (response.data.saved) {
                            $allBtns.addClass('is-saved');
                        } else {
                            $allBtns.removeClass('is-saved');
                        }
                        
                        // Trigger custom event for any listeners
                        $(document).trigger('mecas:event_save_toggled', [eventId, response.data.saved]);
                    } else {
                        console.error('MECAS Save Event Error:', response.data);
                    }
                },
                error: function() {
                    $btn.removeClass('is-loading');
                    console.error('MECAS: Failed to save event');
                }
            });
        },
        
        /**
         * Handle Follow Organizer button click
         */
        handleFollowOrganizerClick: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $btn = $(e.currentTarget);
            var organizerId = $btn.data('organizer-id');
            
            if (!organizerId) {
                console.error('MECAS: No organizer ID found on follow button');
                return;
            }
            
            // Check if logged in
            if (typeof mecas_ajax === 'undefined' || !mecas_ajax.is_logged_in) {
                // Trigger login popup or redirect
                if (typeof mecas_ajax !== 'undefined' && mecas_ajax.login_url) {
                    window.location.href = mecas_ajax.login_url;
                } else {
                    alert('Please log in to follow organizers');
                }
                return;
            }
            
            // Prevent double-click
            if ($btn.hasClass('is-loading')) return;
            $btn.addClass('is-loading');
            
            var isFollowing = $btn.hasClass('is-following');
            var action = isFollowing ? 'mecas_unfollow_organizer' : 'mecas_follow_organizer';
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: action,
                    nonce: mecas_ajax.nonce,
                    organizer_id: organizerId
                },
                success: function(response) {
                    $btn.removeClass('is-loading');
                    
                    if (response.success) {
                        // Toggle following state on ALL buttons for this organizer
                        var $allBtns = $('.mecas-follow-btn[data-organizer-id="' + organizerId + '"]');
                        
                        if (response.data.following) {
                            $allBtns.addClass('is-following');
                        } else {
                            $allBtns.removeClass('is-following');
                        }
                        
                        // Trigger custom event for any listeners
                        $(document).trigger('mecas:organizer_follow_toggled', [organizerId, response.data.following]);
                    } else {
                        console.error('MECAS Follow Error:', response.data);
                    }
                },
                error: function() {
                    $btn.removeClass('is-loading');
                    console.error('MECAS: Failed to follow organizer');
                }
            });
        },

        // ========================================
        // TEACHER SEARCH WIDGET
        // ========================================

        initTeacherSearch: function() {
            // Check if there's a location in URL params and auto-search
            const self = this;
            
            $('.mecas-teacher-search-wrapper').each(function() {
                const $wrapper = $(this);
                const $input = $wrapper.find('.mecas-teacher-search-input');
                const location = $input.val();
                
                // If has location, search immediately
                if (location) {
                    self.searchTeachers($wrapper, location, 1);
                }
            });
        },

        handleTeacherSearchSubmit: function(e) {
            e.preventDefault();
            
            const $form = $(e.currentTarget);
            const $wrapper = $form.closest('.mecas-teacher-search-wrapper');
            const location = $form.find('.mecas-teacher-search-input').val().trim();
            
            if (!location) {
                return;
            }
            
            this.searchTeachers($wrapper, location, 1);
        },

        searchTeachers: function($wrapper, location, page) {
            const self = this;
            const $grid = $wrapper.find('.mecas-teacher-search-grid');
            const $count = $wrapper.find('.mecas-teacher-search-count');
            const $noResults = $wrapper.find('.mecas-teacher-search-no-results');
            const $loader = $wrapper.find('.mecas-teacher-search-loader');
            const $pagination = $wrapper.find('.mecas-teacher-pagination');
            
            // Helper function to handle both boolean true and string 'true'
            const isTrue = function(val) {
                return val === true || val === 'true';
            };
            
            const perPage = parseInt($wrapper.data('per-page')) || 24;
            const columns = parseInt($wrapper.data('columns')) || 6;
            const showCount = isTrue($wrapper.data('show-count'));
            const countSingular = $wrapper.data('count-singular') || '%d Teacher found in %s';
            const countPlural = $wrapper.data('count-plural') || '%d Teachers found in %s';
            const showLocationBar = isTrue($wrapper.data('show-location-bar'));
            const showHeart = isTrue($wrapper.data('show-heart'));
            const showName = isTrue($wrapper.data('show-name'));
            const showTagline = isTrue($wrapper.data('show-tagline'));
            const showPagination = isTrue($wrapper.data('show-pagination'));
            const noResultsText = $wrapper.data('no-results') || 'No teachers found in this location.';
            
            // Show loader
            $grid.empty();
            $count.hide();
            $noResults.hide();
            $pagination.empty();
            $loader.show();
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_search_teachers',
                    nonce: mecas_ajax.nonce,
                    location: location,
                    per_page: perPage,
                    page: page
                },
                success: function(response) {
                    console.log('Teacher search response:', response);
                    $loader.hide();
                    
                    if (response.success && response.data.teachers && response.data.teachers.length > 0) {
                        // Show count
                        if (showCount) {
                            const total = response.data.total;
                            const countText = total === 1 ? countSingular : countPlural;
                            $count.text(countText.replace('%d', total).replace('%s', location)).show();
                        }
                        
                        // Render teachers
                        self.renderTeachers($grid, response.data.teachers, {
                            showLocationBar: showLocationBar,
                            showHeart: showHeart,
                            showName: showName,
                            showTagline: showTagline
                        });
                        
                        // Render pagination
                        if (showPagination && response.data.max_pages > 1) {
                            self.renderTeacherPagination($pagination, response.data.current_page, response.data.max_pages, $wrapper, location);
                        }
                    } else {
                        // No results
                        $noResults.find('p').text(noResultsText);
                        $noResults.show();
                    }
                },
                error: function() {
                    $loader.hide();
                    $noResults.find('p').text('Error loading results. Please try again.');
                    $noResults.show();
                }
            });
        },

        renderTeachers: function($grid, teachers, options) {
            console.log('renderTeachers called with options:', options);
            console.log('Teachers data:', teachers);
            
            let html = '';
            
            teachers.forEach(function(teacher) {
                const imageHtml = teacher.image 
                    ? '<img src="' + teacher.image + '" alt="' + teacher.name + '" class="mecas-teacher-image">'
                    : '<div class="mecas-teacher-image-placeholder"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 0 0-16 0"/></svg></div>';
                
                html += '<div class="mecas-teacher-card">';
                html += '<a href="' + teacher.url + '">';
                html += '<div class="mecas-teacher-image-wrapper">' + imageHtml + '</div>';
                
                if (options.showLocationBar && teacher.location) {
                    html += '<div class="mecas-teacher-location-bar">';
                    html += '<span class="mecas-teacher-location-text">' + teacher.location + '</span>';
                    if (options.showHeart) {
                        html += '<span class="mecas-teacher-heart-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span>';
                    }
                    html += '</div>';
                }
                
                html += '<div class="mecas-teacher-content">';
                if (options.showName) {
                    html += '<h4 class="mecas-teacher-name">' + teacher.name + '</h4>';
                }
                if (options.showTagline && teacher.tagline) {
                    html += '<p class="mecas-teacher-tagline">' + teacher.tagline + '</p>';
                }
                html += '</div>';
                html += '</a>';
                html += '</div>';
            });
            
            $grid.html(html);
        },

        renderTeacherPagination: function($pagination, currentPage, maxPages, $wrapper, location) {
            const self = this;
            let html = '';
            
            // Previous
            if (currentPage > 1) {
                html += '<a href="#" class="mecas-page-link" data-page="' + (currentPage - 1) + '">&laquo; Prev</a>';
            }
            
            // Page numbers
            for (let i = 1; i <= maxPages; i++) {
                if (i === currentPage) {
                    html += '<span class="current">' + i + '</span>';
                } else if (i === 1 || i === maxPages || Math.abs(i - currentPage) <= 2) {
                    html += '<a href="#" class="mecas-page-link" data-page="' + i + '">' + i + '</a>';
                } else if (Math.abs(i - currentPage) === 3) {
                    html += '<span class="dots">...</span>';
                }
            }
            
            // Next
            if (currentPage < maxPages) {
                html += '<a href="#" class="mecas-page-link" data-page="' + (currentPage + 1) + '">Next &raquo;</a>';
            }
            
            $pagination.html(html);
            
            // Bind pagination clicks
            $pagination.find('.mecas-page-link').on('click', function(e) {
                e.preventDefault();
                const page = parseInt($(this).data('page'));
                self.searchTeachers($wrapper, location, page);
                
                // Scroll to top of widget
                $('html, body').animate({
                    scrollTop: $wrapper.offset().top - 100
                }, 300);
            });
        },

        // ========================================
        // MODAL
        // ========================================

        openModal: function(e) {
            e.preventDefault();
            const modalId = $(e.currentTarget).data('modal');
            const $modal = $('#' + modalId);
            
            if ($modal.length) {
                $modal.addClass('active');
                $('body').css('overflow', 'hidden');
                $modal.find('.mecas-query-input, .mecas-location-input').first().focus();
                
                if (!this.geoDetected) {
                    const $wrapper = $modal.find('.mecas-search-wrapper');
                    if ($wrapper.data('auto-detect') === 'true' || $wrapper.data('auto-detect') === true) {
                        this.autoDetectLocation($wrapper, $wrapper.find('.mecas-location-input'), '.mecas-location-loading');
                    }
                }
            }
        },

        closeModal: function(e) {
            $('.mecas-modal-overlay.active').removeClass('active');
            $('body').css('overflow', '');
        },

        handleBackdropClick: function(e) {
            const $modal = $(e.target).closest('.mecas-modal-overlay');
            const closeOnBackdrop = $modal.data('close-on-backdrop');
            
            if (closeOnBackdrop !== 'false' && closeOnBackdrop !== false) {
                this.closeModal(e);
            }
        },

        handleEscKey: function(e) {
            if (e.key === 'Escape') {
                this.closeModal(e);
            }
        },

        // ========================================
        // CATEGORY TABS
        // ========================================

        handleCategoryTab: function(e) {
            e.preventDefault();
            const $tab = $(e.currentTarget);
            const $container = $tab.closest('.mecas-event-cards-widget');
            const category = $tab.data('category');
            
            $container.find('.mecas-category-tab').removeClass('active');
            $tab.addClass('active');
            
            this.loadCategoryEvents($container, category);
        },

        loadCategoryEvents: function($container, category) {
            const $grid = $container.find('.mecas-events-grid');
            const columns = parseInt($container.data('columns')) || 3;
            const perPage = parseInt($container.data('per-page')) || 6;
            const showFeatured = $container.data('show-featured') === 'true';
            const showCategory = $container.data('show-category') === 'true';
            const showOrganizer = $container.data('show-organizer') === 'true';
            const showOrganizerImage = $container.data('show-organizer-image') === 'true';
            const showDate = $container.data('show-date') === 'true';
            const showPrice = $container.data('show-price') === 'true';
            const showLocation = $container.data('show-location') === 'true';
            
            $grid.html('<div class="mecas-loading"><div class="mecas-loading-spinner"></div></div>');
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_filter_events',
                    nonce: mecas_ajax.nonce,
                    category: category,
                    per_page: perPage,
                    page: 1
                },
                success: function(response) {
                    if (response.success && response.data.events) {
                        let html = '';
                        
                        if (response.data.events.length === 0) {
                            html = '<div class="mecas-no-results" style="grid-column: 1/-1; text-align: center; padding: 40px;"><p>No events found in this category.</p></div>';
                        } else {
                            response.data.events.forEach(function(event) {
                                html += '<div class="mecas-event-card">';
                                html += '<a href="' + event.url + '">';
                                
                                // Image
                                html += '<div class="mecas-card-image-wrapper">';
                                if (event.image) {
                                    html += '<img src="' + event.image + '" alt="' + event.title + '" class="mecas-event-image">';
                                } else {
                                    html += '<div class="mecas-event-image-placeholder"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></div>';
                                }
                                
                                // Price badge
                                if (showPrice && event.price) {
                                    html += '<span class="mecas-price-badge">' + event.price + '</span>';
                                }
                                
                                // Featured badge
                                if (showFeatured && event.is_featured) {
                                    html += '<span class="mecas-featured-badge">Featured</span>';
                                }
                                
                                // Category badge
                                if (showCategory && event.category) {
                                    html += '<span class="mecas-category-badge">' + event.category + '</span>';
                                }
                                
                                html += '</div>';
                                
                                // Content
                                html += '<div class="mecas-event-card-content">';
                                
                                // Organizer
                                if (showOrganizer && event.organizer) {
                                    html += '<div class="mecas-event-organizer">';
                                    if (showOrganizerImage && event.organizer_image) {
                                        html += '<img src="' + event.organizer_image + '" alt="" class="mecas-organizer-thumb">';
                                    }
                                    html += '<span>' + event.organizer + '</span>';
                                    html += '</div>';
                                }
                                
                                html += '<h3 class="mecas-event-title">' + event.title + '</h3>';
                                
                                html += '<div class="mecas-event-meta">';
                                if (showDate && event.date_formatted) {
                                    html += '<span class="mecas-event-date"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>' + event.date_formatted + '</span>';
                                }
                                if (showLocation && event.location) {
                                    html += '<span class="mecas-event-location"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + event.location + '</span>';
                                }
                                html += '</div>';
                                
                                html += '</div>';
                                html += '</a>';
                                html += '</div>';
                            });
                        }
                        
                        $grid.html(html);
                    }
                },
                error: function() {
                    $grid.html('<div class="mecas-no-results" style="grid-column: 1/-1; text-align: center; padding: 40px;"><p>Error loading events.</p></div>');
                }
            });
        },

        // ========================================
        // FILTERS (for search results page)
        // ========================================

        handleFilterChange: function(e) {
            const $select = $(e.currentTarget);
            const $container = $select.closest('.mecas-results-wrapper');
            
            this.filterEvents($container);
        },

        filterEvents: function($container) {
            const $grid = $container.find('.mecas-results-grid');
            const filters = {};

            $container.find('.mecas-filter-select').each(function() {
                const name = $(this).attr('name');
                const value = $(this).val();
                if (value) {
                    filters[name.replace('mecas_', '')] = value;
                }
            });

            const $searchForm = $container.find('.mecas-search-form');
            if ($searchForm.length) {
                const query = $searchForm.find('.mecas-query-input').val();
                const location = $searchForm.find('.mecas-location-input').val();
                if (query) filters.query = query;
                if (location) filters.location = location;
            }

            $grid.html('<div class="mecas-loading"><div class="mecas-loading-spinner"></div></div>');

            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_filter_events',
                    nonce: mecas_ajax.nonce,
                    ...filters,
                    per_page: $container.data('per-page') || 12,
                    page: 1
                },
                success: function(response) {
                    if (response.success) {
                        // Render events (simplified - you may want full template here)
                        if (response.data.events.length === 0) {
                            $grid.html('<div class="mecas-no-results" style="grid-column: 1/-1;"><p>No events found.</p></div>');
                        } else {
                            let html = '';
                            response.data.events.forEach(function(event) {
                                html += '<div class="mecas-event-card">';
                                html += '<a href="' + event.url + '">';
                                if (event.image) {
                                    html += '<img src="' + event.image + '" class="mecas-event-image">';
                                }
                                html += '<div class="mecas-event-card-content">';
                                html += '<h3 class="mecas-event-title">' + event.title + '</h3>';
                                html += '<div class="mecas-event-meta">';
                                if (event.date_formatted) {
                                    html += '<span class="mecas-event-date">' + event.date_formatted + '</span>';
                                }
                                html += '</div></div></a></div>';
                            });
                            $grid.html(html);
                        }
                        
                        $container.find('.mecas-results-count').text(response.data.total + ' events found');
                    }
                }
            });
        },

        // ========================================
        // FORM SUBMIT
        // ========================================

        handleFormSubmit: function(e) {
            // Just let the form submit normally - no preview/suggestions
            const $modal = $(e.currentTarget).closest('.mecas-modal-overlay');
            if ($modal.length) {
                $modal.removeClass('active');
                $('body').css('overflow', '');
            }
        },

        // ========================================
        // EVENTS LOCATION SEARCH (AJAX)
        // ========================================

        handleEventsLocationSearchSubmit: function(e) {
            e.preventDefault();
            
            const $form = $(e.currentTarget);
            const $wrapper = $form.closest('.mecas-events-loc-wrapper');
            const location = $form.find('.mecas-events-loc-input').val().trim();
            
            if (!location) {
                return;
            }
            
            this.searchEventsByLocation($wrapper, location, 1);
        },

        searchEventsByLocation: function($wrapper, location, page) {
            const self = this;
            const $grid = $wrapper.find('.mecas-events-loc-grid');
            const $count = $wrapper.find('.mecas-events-loc-count');
            const $pagination = $wrapper.find('.mecas-events-loc-pagination');
            const $noResults = $wrapper.find('.mecas-events-loc-no-results');
            const $loader = $wrapper.find('.mecas-events-loc-loader');
            
            const perPage = $wrapper.data('per-page') || 12;
            const columns = $wrapper.data('columns') || 3;
            const showCount = $wrapper.data('show-count') === true || $wrapper.data('show-count') === 'true';
            const showPagination = $wrapper.data('show-pagination') === true || $wrapper.data('show-pagination') === 'true';
            const countSingular = $wrapper.data('count-singular') || '%d Event found in %s';
            const countPlural = $wrapper.data('count-plural') || '%d Events found in %s';
            const noResultsText = $wrapper.data('no-results') || 'No events found in this location.';
            
            // Show loader, hide others
            $grid.empty();
            $count.hide();
            $pagination.empty();
            $noResults.hide();
            $loader.show();
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_search_events_by_location',
                    nonce: mecas_ajax.nonce,
                    location: location,
                    per_page: perPage,
                    page: page
                },
                success: function(response) {
                    $loader.hide();
                    
                    if (response.success && response.data.events.length > 0) {
                        // Show count
                        if (showCount) {
                            const total = response.data.total;
                            const countText = total === 1 
                                ? countSingular.replace('%d', total).replace('%s', location)
                                : countPlural.replace('%d', total).replace('%s', location);
                            $count.text(countText).show();
                        }
                        
                        // Render events
                        let html = '';
                        response.data.events.forEach(function(event) {
                            html += '<div class="mecas-events-loc-card">';
                            html += '<a href="' + event.url + '">';
                            
                            if (event.image) {
                                html += '<img src="' + event.image + '" alt="' + event.title + '" class="mecas-events-loc-card-image">';
                            } else {
                                html += '<div class="mecas-events-loc-card-placeholder">';
                                html += '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>';
                                html += '</div>';
                            }
                            
                            html += '<div class="mecas-events-loc-card-content">';
                            html += '<h3 class="mecas-events-loc-card-title">' + event.title + '</h3>';
                            html += '<div class="mecas-events-loc-card-meta">';
                            
                            if (event.date) {
                                html += '<span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>' + event.date + '</span>';
                            }
                            
                            if (event.location) {
                                html += '<span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + event.location + '</span>';
                            }
                            
                            html += '</div></div></a></div>';
                        });
                        
                        $grid.html(html);
                        
                        // Render pagination
                        if (showPagination && response.data.max_pages > 1) {
                            let paginationHtml = '';
                            const currentPage = response.data.current_page;
                            const maxPages = response.data.max_pages;
                            
                            if (currentPage > 1) {
                                paginationHtml += '<a href="#" data-page="' + (currentPage - 1) + '">&laquo; Prev</a>';
                            }
                            
                            for (let i = 1; i <= maxPages; i++) {
                                if (i === currentPage) {
                                    paginationHtml += '<span class="current">' + i + '</span>';
                                } else if (i === 1 || i === maxPages || Math.abs(i - currentPage) <= 2) {
                                    paginationHtml += '<a href="#" data-page="' + i + '">' + i + '</a>';
                                } else if (Math.abs(i - currentPage) === 3) {
                                    paginationHtml += '<span>...</span>';
                                }
                            }
                            
                            if (currentPage < maxPages) {
                                paginationHtml += '<a href="#" data-page="' + (currentPage + 1) + '">Next &raquo;</a>';
                            }
                            
                            $pagination.html(paginationHtml);
                            
                            // Bind pagination clicks
                            $pagination.find('a').on('click', function(e) {
                                e.preventDefault();
                                const newPage = $(this).data('page');
                                self.searchEventsByLocation($wrapper, location, newPage);
                                
                                // Scroll to top of widget
                                $('html, body').animate({
                                    scrollTop: $wrapper.offset().top - 100
                                }, 300);
                            });
                        }
                        
                    } else {
                        // No results
                        $noResults.find('p').text(noResultsText);
                        $noResults.show();
                    }
                },
                error: function() {
                    $loader.hide();
                    $noResults.show();
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        MECAS.init();
    });

})(jQuery);

/* ======================================== */
/* MEC USER ADDON FUNCTIONALITY */
/* ======================================== */
/**
 * MEC User Addon Scripts
 */

(function($) {
    'use strict';

    // Main object
    var MECUA = {
        
        init: function() {
            this.bindEvents();
            this.initRegistrationForm();
            this.initEditProfileForm();
        },
        
        bindEvents: function() {
            // Save event button
            $(document).on('click', '.mecua-save-btn', this.handleSaveEvent.bind(this));
            
            // Follow/Unfollow
            $(document).on('click', '.mecua-follow-btn', this.handleFollow.bind(this));
            $(document).on('click', '.mecua-unfollow-btn', this.handleUnfollow.bind(this));
        },
        
        // ============================================
        // Registration Form
        // ============================================
        
        initRegistrationForm: function() {
            var $wrapper = $('.mecua-registration-wrapper');
            if (!$wrapper.length) return;
            
            var self = this;
            this.regForm = {
                $wrapper: $wrapper,
                $form: $wrapper.find('#mecua-registration-form'),
                phoneVerified: false,
                resendCountdown: 0,
                resendTimer: null
            };
            
            // Email button click
            $wrapper.on('click', '.mecua-btn-email', function() {
                self.goToStep(2);
            });
            
            // Social login buttons
            $wrapper.on('click', '.mecua-social-btn[data-provider]', function() {
                var provider = $(this).data('provider');
                self.handleSocialLogin(provider);
            });
            
            // Continue button
            $wrapper.on('click', '.mecua-btn-continue', function() {
                var nextStep = $(this).data('next');
                if (self.validateStep(2)) {
                    if (nextStep === 3 && typeof mecas_reg !== 'undefined' && mecas_reg.show_phone_verification) {
                        self.sendVerificationCode();
                    } else {
                        self.submitRegistration();
                    }
                }
            });
            
            // Back button
            $wrapper.on('click', '.mecua-btn-back', function() {
                var step = $(this).data('step');
                self.goToStep(step);
            });
            
            // Verify button
            $wrapper.on('click', '.mecua-btn-verify', function() {
                self.verifyCode();
            });
            
            // Code inputs auto-focus
            $wrapper.on('input', '.mecua-code-input', function() {
                var $input = $(this);
                var val = $input.val().replace(/[^0-9]/g, '');
                $input.val(val);
                
                if (val.length === 1) {
                    var index = parseInt($input.data('index'));
                    var $next = $wrapper.find('.mecua-code-input[data-index="' + (index + 1) + '"]');
                    if ($next.length) {
                        $next.focus();
                    }
                }
            });
            
            $wrapper.on('keydown', '.mecua-code-input', function(e) {
                if (e.key === 'Backspace' && !$(this).val()) {
                    var index = parseInt($(this).data('index'));
                    var $prev = $wrapper.find('.mecua-code-input[data-index="' + (index - 1) + '"]');
                    if ($prev.length) {
                        $prev.focus();
                    }
                }
            });
            
            // Resend code
            $wrapper.on('click', '.mecua-resend-code', function(e) {
                e.preventDefault();
                if (self.regForm.resendCountdown <= 0) {
                    self.sendVerificationCode();
                }
            });
            
            // Change phone
            $wrapper.on('click', '.mecua-change-phone', function(e) {
                e.preventDefault();
                self.goToStep(2);
            });
            
            // Toggle password visibility
            $wrapper.on('click', '.mecua-toggle-password', function() {
                var $input = $(this).siblings('input');
                var $eyeOpen = $(this).find('.mecua-eye-open');
                var $eyeClosed = $(this).find('.mecua-eye-closed');
                
                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $eyeOpen.hide();
                    $eyeClosed.show();
                } else {
                    $input.attr('type', 'password');
                    $eyeOpen.show();
                    $eyeClosed.hide();
                }
            });
            
            // Detect location button
            $wrapper.on('click', '.mecua-detect-location', function() {
                self.detectLocation();
            });
            
            // Cancel button (back to step 1)
            $wrapper.on('click', '.mecua-btn-cancel', function() {
                self.goToStep(1);
            });
            
            // Auto-detect location on page load if enabled
            if (typeof mecas_reg !== 'undefined' && mecas_reg.geolocation_enabled) {
                var $locationField = $wrapper.find('#mecas_location');
                if ($locationField.length && !$locationField.val()) {
                    setTimeout(function() { self.detectLocation(); }, 1000);
                }
            }
        },
        
        goToStep: function(step) {
            var $wrapper = this.regForm.$wrapper;
            $wrapper.find('.mecua-step').removeClass('mecua-active').hide();
            $wrapper.find('.mecua-step-' + step).addClass('mecua-active').show();
        },
        
        validateStep: function(step) {
            var $wrapper = this.regForm.$wrapper;
            
            if (step === 2) {
                var name = $wrapper.find('#mecas_name').val().trim();
                var email = $wrapper.find('#mecas_email').val().trim();
                var phone = $wrapper.find('#mecas_phone').val().trim();
                var password = $wrapper.find('#mecas_password').val();
                
                if (!name) {
                    alert('Please enter your name');
                    $wrapper.find('#mecas_name').focus();
                    return false;
                }
                
                if (!email || !this.isValidEmail(email)) {
                    alert('Please enter a valid email address');
                    $wrapper.find('#mecas_email').focus();
                    return false;
                }
                
                if (!phone) {
                    alert('Please enter your phone number');
                    $wrapper.find('#mecas_phone').focus();
                    return false;
                }
                
                if (!password || password.length < 8) {
                    alert('Password must be at least 8 characters');
                    $wrapper.find('#mecas_password').focus();
                    return false;
                }
                
                var location = $wrapper.find('#mecas_location').val().trim();
                if (!location) {
                    alert('Please enter your location');
                    $wrapper.find('#mecas_location').focus();
                    return false;
                }
                
                return true;
            }
            
            return true;
        },
        
        isValidEmail: function(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        },
        
        detectLocation: function() {
            var self = this;
            var $wrapper = this.regForm.$wrapper;
            var $locationField = $wrapper.find('#mecas_location');
            var $detectBtn = $wrapper.find('.mecua-detect-location');
            if (!navigator.geolocation) return;
            $detectBtn.addClass('mecua-detecting');
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    var lat = position.coords.latitude, lng = position.coords.longitude;
                    $.ajax({
                        url: 'https://nominatim.openstreetmap.org/reverse',
                        data: { format: 'json', lat: lat, lon: lng },
                        success: function(data) {
                            $detectBtn.removeClass('mecua-detecting');
                            if (data && data.address) {
                                var city = data.address.city || data.address.town || data.address.village || data.address.county || '';
                                var state = data.address.state || '';
                                var location = city && state ? city + ', ' + state : (city || state);
                                if (location) $locationField.val(location);
                            }
                        },
                        error: function() { $detectBtn.removeClass('mecua-detecting'); }
                    });
                },
                function() { $detectBtn.removeClass('mecua-detecting'); },
                { enableHighAccuracy: false, timeout: 10000, maximumAge: 300000 }
            );
        },
        
        sendVerificationCode: function() {
            var self = this;
            var $wrapper = this.regForm.$wrapper;
            
            var phoneCountry = $wrapper.find('#mecas_phone_country').val();
            var phone = $wrapper.find('#mecas_phone').val().trim();
            
            this.showLoading(true);
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_send_sms_code',
                    nonce: mecas_ajax.nonce,
                    phone_country: phoneCountry,
                    phone: phone
                },
                success: function(response) {
                    self.showLoading(false);
                    
                    if (response.success) {
                        $wrapper.find('#mecua-display-phone').text(response.data.phone);
                        self.goToStep(3);
                        self.startResendCountdown();
                        $wrapper.find('.mecua-code-input').val('').first().focus();
                    } else {
                        var errorMsg = response.data || '';
                        if (errorMsg.indexOf('not enabled') !== -1 || errorMsg.indexOf('not configured') !== -1) {
                            self.submitRegistration();
                        } else {
                            alert(errorMsg || 'Failed to send verification code');
                        }
                    }
                },
                error: function() {
                    self.showLoading(false);
                    alert('An error occurred. Please try again.');
                }
            });
        },
        
        startResendCountdown: function() {
            var self = this;
            var $wrapper = this.regForm.$wrapper;
            
            this.regForm.resendCountdown = 60;
            $wrapper.find('.mecua-resend-timer').show();
            $wrapper.find('.mecua-resend-link').hide();
            
            if (this.regForm.resendTimer) {
                clearInterval(this.regForm.resendTimer);
            }
            
            this.updateResendDisplay();
            
            this.regForm.resendTimer = setInterval(function() {
                self.regForm.resendCountdown--;
                self.updateResendDisplay();
                
                if (self.regForm.resendCountdown <= 0) {
                    clearInterval(self.regForm.resendTimer);
                    $wrapper.find('.mecua-resend-timer').hide();
                    $wrapper.find('.mecua-resend-link').show();
                }
            }, 1000);
        },
        
        updateResendDisplay: function() {
            var $wrapper = this.regForm.$wrapper;
            $wrapper.find('#mecua-resend-countdown').text(this.regForm.resendCountdown);
        },
        
        verifyCode: function() {
            var self = this;
            var $wrapper = this.regForm.$wrapper;
            
            var code = '';
            $wrapper.find('.mecua-code-input').each(function() {
                code += $(this).val();
            });
            
            if (code.length !== 4) {
                alert('Please enter the 4-digit code');
                return;
            }
            
            var phoneCountry = $wrapper.find('#mecas_phone_country').val();
            var phone = $wrapper.find('#mecas_phone').val().trim();
            
            this.showLoading(true);
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_verify_sms_code',
                    nonce: mecas_ajax.nonce,
                    phone_country: phoneCountry,
                    phone: phone,
                    code: code
                },
                success: function(response) {
                    self.showLoading(false);
                    
                    if (response.success) {
                        self.regForm.phoneVerified = true;
                        self.submitRegistration();
                    } else {
                        alert(response.data || 'Invalid verification code');
                    }
                },
                error: function() {
                    self.showLoading(false);
                    alert('An error occurred. Please try again.');
                }
            });
        },
        
        submitRegistration: function() {
            var self = this;
            var $wrapper = this.regForm.$wrapper;
            
            var data = {
                action: 'mecas_register_customer',
                nonce: mecas_ajax.nonce,
                name: $wrapper.find('#mecas_name').val().trim(),
                email: $wrapper.find('#mecas_email').val().trim(),
                phone_country: $wrapper.find('#mecas_phone_country').val(),
                phone: $wrapper.find('#mecas_phone').val().trim(),
                location: $wrapper.find('#mecas_location').val().trim(),
                password: $wrapper.find('#mecas_password').val()
            };
            
            this.showLoading(true);
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: data,
                success: function(response) {
                    self.showLoading(false);
                    
                    if (response.success) {
                        self.goToStep(4);
                        
                        // Auto redirect after a delay
                        if (response.data.redirect) {
                            setTimeout(function() {
                                window.location.href = response.data.redirect;
                            }, 2000);
                        }
                    } else {
                        alert(response.data || 'Registration failed');
                    }
                },
                error: function() {
                    self.showLoading(false);
                    alert('An error occurred. Please try again.');
                }
            });
        },
        
        handleSocialLogin: function(provider) {
            // Try to use Nextend Social Login
            if (typeof NextendSocialLogin !== 'undefined') {
                NextendSocialLogin.openPopup(provider);
            } else {
                // Fallback - redirect to social login URL if available
                var socialUrl = '/wp-login.php?loginSocial=' + provider;
                window.location.href = socialUrl;
            }
        },
        
        showLoading: function(show) {
            var $loading = this.regForm.$wrapper.find('.mecua-form-loading');
            if (show) {
                $loading.show();
            } else {
                $loading.hide();
            }
        },
        
        // ============================================
        // Edit Profile Form
        // ============================================
        
        initEditProfileForm: function() {
            var $form = $('#mecua-edit-profile-form');
            if (!$form.length) return;
            
            var self = this;
            
            // Image preview
            $form.on('change', '#mecua-profile-image-input', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#mecua-image-preview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Toggle password fields
            $form.on('click', '.mecua-change-password-btn', function() {
                var $fields = $form.find('.mecua-password-fields');
                $fields.slideToggle();
            });
            
            // Submit form
            $form.on('submit', function(e) {
                e.preventDefault();
                self.submitProfileUpdate($(this));
            });
        },
        
        submitProfileUpdate: function($form) {
            var self = this;
            var formData = new FormData($form[0]);
            formData.append('action', 'mecas_update_profile');
            
            var $message = $form.find('.mecua-form-message');
            var $submit = $form.find('.mecua-btn-save');
            
            $submit.prop('disabled', true).text('Saving...');
            $message.hide();
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $submit.prop('disabled', false).text('Save Changes');
                    
                    if (response.success) {
                        $message.removeClass('error').addClass('success')
                            .text(response.data.message).show();
                    } else {
                        $message.removeClass('success').addClass('error')
                            .text(response.data || 'Update failed').show();
                    }
                },
                error: function() {
                    $submit.prop('disabled', false).text('Save Changes');
                    $message.removeClass('success').addClass('error')
                        .text('An error occurred. Please try again.').show();
                }
            });
        },
        
        // ============================================
        // Save Event
        // ============================================
        
        handleSaveEvent: function(e) {
            e.preventDefault();
            
            var $btn = $(e.currentTarget);
            var eventId = $btn.data('event-id');
            var isSaved = $btn.hasClass('mecua-saved');
            
            if (!mecas_ajax.is_logged_in) {
                if (confirm(mecas_ajax.i18n.login_required + '\n\nWould you like to log in now?')) {
                    window.location.href = mecas_ajax.login_url;
                }
                return;
            }
            
            var action = isSaved ? 'mecas_unsave_event' : 'mecas_save_event';
            
            $btn.addClass('mecua-saving');
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: action,
                    nonce: mecas_ajax.nonce,
                    event_id: eventId
                },
                success: function(response) {
                    $btn.removeClass('mecua-saving');
                    
                    if (response.success) {
                        if (response.data.saved) {
                            $btn.addClass('mecua-saved');
                            $btn.find('.mecua-save-btn-text').text($btn.data('saved-text'));
                        } else {
                            $btn.removeClass('mecua-saved');
                            $btn.find('.mecua-save-btn-text').text($btn.data('save-text'));
                        }
                    } else {
                        alert(response.data || mecas_ajax.i18n.error);
                    }
                },
                error: function() {
                    $btn.removeClass('mecua-saving');
                    alert(mecas_ajax.i18n.error);
                }
            });
        },
        
        // ============================================
        // Follow/Unfollow
        // ============================================
        
        handleFollow: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $btn = $(e.currentTarget);
            var organizerId = $btn.data('organizer-id');
            
            if (!mecas_ajax.is_logged_in) {
                if (confirm(mecas_ajax.i18n.login_required + '\n\nWould you like to log in now?')) {
                    window.location.href = mecas_ajax.login_url;
                }
                return;
            }
            
            $btn.prop('disabled', true);
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_follow_organizer',
                    nonce: mecas_ajax.nonce,
                    organizer_id: organizerId
                },
                success: function(response) {
                    $btn.prop('disabled', false);
                    
                    if (response.success) {
                        $btn.removeClass('mecua-follow-btn').addClass('mecua-unfollow-btn');
                        // Update heart icon to filled
                        $btn.find('svg').replaceWith('<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>');
                    } else {
                        alert(response.data || mecas_ajax.i18n.error);
                    }
                },
                error: function() {
                    $btn.prop('disabled', false);
                    alert(mecas_ajax.i18n.error);
                }
            });
        },
        
        handleUnfollow: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $btn = $(e.currentTarget);
            var organizerId = $btn.data('organizer-id');
            
            $btn.prop('disabled', true);
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_unfollow_organizer',
                    nonce: mecas_ajax.nonce,
                    organizer_id: organizerId
                },
                success: function(response) {
                    $btn.prop('disabled', false);
                    
                    if (response.success) {
                        // Remove card from Following widget
                        var $card = $btn.closest('.mecua-following-card');
                        if ($card.length) {
                            $card.fadeOut(300, function() {
                                $(this).remove();
                                
                                // Show empty message if no more following
                                if (!$('.mecua-following-card').length) {
                                    $('.mecua-following-grid').html('<p class="mecua-empty-state">You are not following anyone yet.</p>');
                                }
                            });
                        } else {
                            // Just toggle the button
                            $btn.removeClass('mecua-unfollow-btn').addClass('mecua-follow-btn');
                            $btn.find('svg').replaceWith('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>');
                        }
                    } else {
                        alert(response.data || mecas_ajax.i18n.error);
                    }
                },
                error: function() {
                    $btn.prop('disabled', false);
                    alert(mecas_ajax.i18n.error);
                }
            });
        }
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        MECUA.init();
    });
    
    // Also init on Elementor frontend
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/global', function() {
                MECUA.init();
            });
        }
    });
    
})(jQuery);

/* =============================================
 * MEC User Addon Features
 * ============================================= */
/**
 * MEC User Addon Scripts
 */

(function($) {
    'use strict';

    // Main object
    var MECUA = {
        
        init: function() {
            this.bindEvents();
            this.initRegistrationForm();
            this.initEditProfileForm();
        },
        
        bindEvents: function() {
            // Save event button
            $(document).on('click', '.mecua-save-btn', this.handleSaveEvent.bind(this));
            
            // Follow/Unfollow
            $(document).on('click', '.mecua-follow-btn', this.handleFollow.bind(this));
            $(document).on('click', '.mecua-unfollow-btn', this.handleUnfollow.bind(this));
        },
        
        // ============================================
        // Registration Form
        // ============================================
        
        initRegistrationForm: function() {
            var $wrapper = $('.mecua-registration-wrapper');
            if (!$wrapper.length) return;
            
            var self = this;
            this.regForm = {
                $wrapper: $wrapper,
                $form: $wrapper.find('#mecua-registration-form'),
                phoneVerified: false,
                resendCountdown: 0,
                resendTimer: null
            };
            
            // Email button click
            $wrapper.on('click', '.mecua-btn-email', function() {
                self.goToStep(2);
            });
            
            // Social login buttons
            $wrapper.on('click', '.mecua-social-btn[data-provider]', function() {
                var provider = $(this).data('provider');
                self.handleSocialLogin(provider);
            });
            
            // Continue button
            $wrapper.on('click', '.mecua-btn-continue', function() {
                var nextStep = $(this).data('next');
                if (self.validateStep(2)) {
                    if (nextStep === 3 && typeof mecas_reg !== 'undefined' && mecas_reg.show_phone_verification) {
                        self.sendVerificationCode();
                    } else {
                        self.submitRegistration();
                    }
                }
            });
            
            // Back button
            $wrapper.on('click', '.mecua-btn-back', function() {
                var step = $(this).data('step');
                self.goToStep(step);
            });
            
            // Verify button
            $wrapper.on('click', '.mecua-btn-verify', function() {
                self.verifyCode();
            });
            
            // Code inputs auto-focus
            $wrapper.on('input', '.mecua-code-input', function() {
                var $input = $(this);
                var val = $input.val().replace(/[^0-9]/g, '');
                $input.val(val);
                
                if (val.length === 1) {
                    var index = parseInt($input.data('index'));
                    var $next = $wrapper.find('.mecua-code-input[data-index="' + (index + 1) + '"]');
                    if ($next.length) {
                        $next.focus();
                    }
                }
            });
            
            $wrapper.on('keydown', '.mecua-code-input', function(e) {
                if (e.key === 'Backspace' && !$(this).val()) {
                    var index = parseInt($(this).data('index'));
                    var $prev = $wrapper.find('.mecua-code-input[data-index="' + (index - 1) + '"]');
                    if ($prev.length) {
                        $prev.focus();
                    }
                }
            });
            
            // Resend code
            $wrapper.on('click', '.mecua-resend-code', function(e) {
                e.preventDefault();
                if (self.regForm.resendCountdown <= 0) {
                    self.sendVerificationCode();
                }
            });
            
            // Change phone
            $wrapper.on('click', '.mecua-change-phone', function(e) {
                e.preventDefault();
                self.goToStep(2);
            });
            
            // Toggle password visibility
            $wrapper.on('click', '.mecua-toggle-password', function() {
                var $input = $(this).siblings('input');
                var $eyeOpen = $(this).find('.mecua-eye-open');
                var $eyeClosed = $(this).find('.mecua-eye-closed');
                
                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $eyeOpen.hide();
                    $eyeClosed.show();
                } else {
                    $input.attr('type', 'password');
                    $eyeOpen.show();
                    $eyeClosed.hide();
                }
            });
        },
        
        goToStep: function(step) {
            var $wrapper = this.regForm.$wrapper;
            $wrapper.find('.mecua-step').removeClass('mecua-active').hide();
            $wrapper.find('.mecua-step-' + step).addClass('mecua-active').show();
        },
        
        validateStep: function(step) {
            var $wrapper = this.regForm.$wrapper;
            
            if (step === 2) {
                var name = $wrapper.find('#mecas_name').val().trim();
                var email = $wrapper.find('#mecas_email').val().trim();
                var phone = $wrapper.find('#mecas_phone').val().trim();
                var password = $wrapper.find('#mecas_password').val();
                
                if (!name) {
                    alert('Please enter your name');
                    $wrapper.find('#mecas_name').focus();
                    return false;
                }
                
                if (!email || !this.isValidEmail(email)) {
                    alert('Please enter a valid email address');
                    $wrapper.find('#mecas_email').focus();
                    return false;
                }
                
                if (!phone) {
                    alert('Please enter your phone number');
                    $wrapper.find('#mecas_phone').focus();
                    return false;
                }
                
                if (!password || password.length < 8) {
                    alert('Password must be at least 8 characters');
                    $wrapper.find('#mecas_password').focus();
                    return false;
                }
                
                return true;
            }
            
            return true;
        },
        
        isValidEmail: function(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        },
        
        sendVerificationCode: function() {
            var self = this;
            var $wrapper = this.regForm.$wrapper;
            
            var phoneCountry = $wrapper.find('#mecas_phone_country').val();
            var phone = $wrapper.find('#mecas_phone').val().trim();
            
            this.showLoading(true);
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_send_sms_code',
                    nonce: mecas_ajax.nonce,
                    phone_country: phoneCountry,
                    phone: phone
                },
                success: function(response) {
                    self.showLoading(false);
                    
                    if (response.success) {
                        $wrapper.find('#mecua-display-phone').text(response.data.phone);
                        self.goToStep(3);
                        self.startResendCountdown();
                        $wrapper.find('.mecua-code-input').val('').first().focus();
                    } else {
                        var errorMsg = response.data || '';
                        if (errorMsg.indexOf('not enabled') !== -1 || errorMsg.indexOf('not configured') !== -1) {
                            self.submitRegistration();
                        } else {
                            alert(errorMsg || 'Failed to send verification code');
                        }
                    }
                },
                error: function() {
                    self.showLoading(false);
                    alert('An error occurred. Please try again.');
                }
            });
        },
        
        startResendCountdown: function() {
            var self = this;
            var $wrapper = this.regForm.$wrapper;
            
            this.regForm.resendCountdown = 60;
            $wrapper.find('.mecua-resend-timer').show();
            $wrapper.find('.mecua-resend-link').hide();
            
            if (this.regForm.resendTimer) {
                clearInterval(this.regForm.resendTimer);
            }
            
            this.updateResendDisplay();
            
            this.regForm.resendTimer = setInterval(function() {
                self.regForm.resendCountdown--;
                self.updateResendDisplay();
                
                if (self.regForm.resendCountdown <= 0) {
                    clearInterval(self.regForm.resendTimer);
                    $wrapper.find('.mecua-resend-timer').hide();
                    $wrapper.find('.mecua-resend-link').show();
                }
            }, 1000);
        },
        
        updateResendDisplay: function() {
            var $wrapper = this.regForm.$wrapper;
            $wrapper.find('#mecua-resend-countdown').text(this.regForm.resendCountdown);
        },
        
        verifyCode: function() {
            var self = this;
            var $wrapper = this.regForm.$wrapper;
            
            var code = '';
            $wrapper.find('.mecua-code-input').each(function() {
                code += $(this).val();
            });
            
            if (code.length !== 4) {
                alert('Please enter the 4-digit code');
                return;
            }
            
            var phoneCountry = $wrapper.find('#mecas_phone_country').val();
            var phone = $wrapper.find('#mecas_phone').val().trim();
            
            this.showLoading(true);
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_verify_sms_code',
                    nonce: mecas_ajax.nonce,
                    phone_country: phoneCountry,
                    phone: phone,
                    code: code
                },
                success: function(response) {
                    self.showLoading(false);
                    
                    if (response.success) {
                        self.regForm.phoneVerified = true;
                        self.submitRegistration();
                    } else {
                        alert(response.data || 'Invalid verification code');
                    }
                },
                error: function() {
                    self.showLoading(false);
                    alert('An error occurred. Please try again.');
                }
            });
        },
        
        submitRegistration: function() {
            var self = this;
            var $wrapper = this.regForm.$wrapper;
            
            var data = {
                action: 'mecas_register_customer',
                nonce: mecas_ajax.nonce,
                name: $wrapper.find('#mecas_name').val().trim(),
                email: $wrapper.find('#mecas_email').val().trim(),
                phone_country: $wrapper.find('#mecas_phone_country').val(),
                phone: $wrapper.find('#mecas_phone').val().trim(),
                location: $wrapper.find('#mecas_location').val().trim(),
                password: $wrapper.find('#mecas_password').val()
            };
            
            this.showLoading(true);
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: data,
                success: function(response) {
                    self.showLoading(false);
                    
                    if (response.success) {
                        self.goToStep(4);
                        
                        // Auto redirect after a delay
                        if (response.data.redirect) {
                            setTimeout(function() {
                                window.location.href = response.data.redirect;
                            }, 2000);
                        }
                    } else {
                        alert(response.data || 'Registration failed');
                    }
                },
                error: function() {
                    self.showLoading(false);
                    alert('An error occurred. Please try again.');
                }
            });
        },
        
        handleSocialLogin: function(provider) {
            // Try to use Nextend Social Login
            if (typeof NextendSocialLogin !== 'undefined') {
                NextendSocialLogin.openPopup(provider);
            } else {
                // Fallback - redirect to social login URL if available
                var socialUrl = '/wp-login.php?loginSocial=' + provider;
                window.location.href = socialUrl;
            }
        },
        
        showLoading: function(show) {
            var $loading = this.regForm.$wrapper.find('.mecua-form-loading');
            if (show) {
                $loading.show();
            } else {
                $loading.hide();
            }
        },
        
        // ============================================
        // Edit Profile Form
        // ============================================
        
        initEditProfileForm: function() {
            var $form = $('#mecua-edit-profile-form');
            if (!$form.length) return;
            
            var self = this;
            
            // Image preview
            $form.on('change', '#mecua-profile-image-input', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#mecua-image-preview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Toggle password fields
            $form.on('click', '.mecua-change-password-btn', function() {
                var $fields = $form.find('.mecua-password-fields');
                $fields.slideToggle();
            });
            
            // Submit form
            $form.on('submit', function(e) {
                e.preventDefault();
                self.submitProfileUpdate($(this));
            });
        },
        
        submitProfileUpdate: function($form) {
            var self = this;
            var formData = new FormData($form[0]);
            formData.append('action', 'mecas_update_profile');
            
            var $message = $form.find('.mecua-form-message');
            var $submit = $form.find('.mecua-btn-save');
            
            $submit.prop('disabled', true).text('Saving...');
            $message.hide();
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $submit.prop('disabled', false).text('Save Changes');
                    
                    if (response.success) {
                        $message.removeClass('error').addClass('success')
                            .text(response.data.message).show();
                    } else {
                        $message.removeClass('success').addClass('error')
                            .text(response.data || 'Update failed').show();
                    }
                },
                error: function() {
                    $submit.prop('disabled', false).text('Save Changes');
                    $message.removeClass('success').addClass('error')
                        .text('An error occurred. Please try again.').show();
                }
            });
        },
        
        // ============================================
        // Save Event
        // ============================================
        
        handleSaveEvent: function(e) {
            e.preventDefault();
            
            var $btn = $(e.currentTarget);
            var eventId = $btn.data('event-id');
            var isSaved = $btn.hasClass('mecua-saved');
            
            if (!mecas_ajax.is_logged_in) {
                if (confirm(mecas_ajax.i18n.login_required + '\n\nWould you like to log in now?')) {
                    window.location.href = mecas_ajax.login_url;
                }
                return;
            }
            
            var action = isSaved ? 'mecas_unsave_event' : 'mecas_save_event';
            
            $btn.addClass('mecua-saving');
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: action,
                    nonce: mecas_ajax.nonce,
                    event_id: eventId
                },
                success: function(response) {
                    $btn.removeClass('mecua-saving');
                    
                    if (response.success) {
                        if (response.data.saved) {
                            $btn.addClass('mecua-saved');
                            $btn.find('.mecua-save-btn-text').text($btn.data('saved-text'));
                        } else {
                            $btn.removeClass('mecua-saved');
                            $btn.find('.mecua-save-btn-text').text($btn.data('save-text'));
                        }
                    } else {
                        alert(response.data || mecas_ajax.i18n.error);
                    }
                },
                error: function() {
                    $btn.removeClass('mecua-saving');
                    alert(mecas_ajax.i18n.error);
                }
            });
        },
        
        // ============================================
        // Follow/Unfollow
        // ============================================
        
        handleFollow: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $btn = $(e.currentTarget);
            var organizerId = $btn.data('organizer-id');
            
            if (!mecas_ajax.is_logged_in) {
                if (confirm(mecas_ajax.i18n.login_required + '\n\nWould you like to log in now?')) {
                    window.location.href = mecas_ajax.login_url;
                }
                return;
            }
            
            $btn.prop('disabled', true);
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_follow_organizer',
                    nonce: mecas_ajax.nonce,
                    organizer_id: organizerId
                },
                success: function(response) {
                    $btn.prop('disabled', false);
                    
                    if (response.success) {
                        $btn.removeClass('mecua-follow-btn').addClass('mecua-unfollow-btn');
                        // Update heart icon to filled
                        $btn.find('svg').replaceWith('<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>');
                    } else {
                        alert(response.data || mecas_ajax.i18n.error);
                    }
                },
                error: function() {
                    $btn.prop('disabled', false);
                    alert(mecas_ajax.i18n.error);
                }
            });
        },
        
        handleUnfollow: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $btn = $(e.currentTarget);
            var organizerId = $btn.data('organizer-id');
            
            $btn.prop('disabled', true);
            
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_unfollow_organizer',
                    nonce: mecas_ajax.nonce,
                    organizer_id: organizerId
                },
                success: function(response) {
                    $btn.prop('disabled', false);
                    
                    if (response.success) {
                        // Remove card from Following widget
                        var $card = $btn.closest('.mecua-following-card');
                        if ($card.length) {
                            $card.fadeOut(300, function() {
                                $(this).remove();
                                
                                // Show empty message if no more following
                                if (!$('.mecua-following-card').length) {
                                    $('.mecua-following-grid').html('<p class="mecua-empty-state">You are not following anyone yet.</p>');
                                }
                            });
                        } else {
                            // Just toggle the button
                            $btn.removeClass('mecua-unfollow-btn').addClass('mecua-follow-btn');
                            $btn.find('svg').replaceWith('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>');
                        }
                    } else {
                        alert(response.data || mecas_ajax.i18n.error);
                    }
                },
                error: function() {
                    $btn.prop('disabled', false);
                    alert(mecas_ajax.i18n.error);
                }
            });
        }
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        MECUA.init();
    });
    
    // Also init on Elementor frontend
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/global', function() {
                MECUA.init();
            });
        }
    });
    
})(jQuery);

// ========================================
// BACKUP: Standalone Dashboard Edit Toggle
// ========================================
// This is a backup in case the main MECAS object doesn't initialize properly
jQuery(document).ready(function($) {
    'use strict';
    
    // Edit Profile button click - opens dashboard edit
    $(document).on('click', '.mecas-ajax-edit-trigger, .mecas-edit-profile-trigger, [data-edit-profile]', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $dashboard = $('#mecas-dashboard-edit-container');
        if (!$dashboard.length) {
            $dashboard = $('.mecas-dashboard-edit').first();
        }
        
        if ($dashboard.length) {
            $dashboard.slideDown(400, function() {
                $('html, body').animate({
                    scrollTop: $dashboard.offset().top - 50
                }, 400);
            });
        }
    });
    
    // Close button click - closes dashboard edit
    $(document).on('click', '.mecas-dashboard-close', function(e) {
        e.preventDefault();
        
        var $dashboard = $(this).closest('.mecas-dashboard-edit');
        if ($dashboard.length) {
            $dashboard.slideUp(400);
        }
    });
    
    // Global functions
    window.mecasOpenDashboardEdit = function() {
        var $dashboard = $('#mecas-dashboard-edit-container');
        if (!$dashboard.length) {
            $dashboard = jQuery('.mecas-dashboard-edit').first();
        }
        if ($dashboard.length) {
            $dashboard.slideDown(400, function() {
                jQuery('html, body').animate({
                    scrollTop: $dashboard.offset().top - 50
                }, 400);
            });
        }
    };
    
    window.mecasCloseDashboardEdit = function() {
        var $dashboard = jQuery('#mecas-dashboard-edit-container');
        if (!$dashboard.length) {
            $dashboard = jQuery('.mecas-dashboard-edit').first();
        }
        if ($dashboard.length) {
            $dashboard.slideUp(400);
        }
    };
});
