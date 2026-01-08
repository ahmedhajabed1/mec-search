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
