/**
 * MEC Advanced Search - Scripts
 * Version 3.0.0 - Auto Geolocation
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
        },

        bindEvents: function() {
            // Modal triggers
            $(document).on('click', '.mecas-trigger-button', this.openModal.bind(this));
            $(document).on('click', '.mecas-modal-close, .mecas-modal-backdrop', this.closeModal.bind(this));
            $(document).on('keydown', this.handleEscKey.bind(this));

            // Search input
            $(document).on('input', '.mecas-query-input', this.handleSearchInput.bind(this));
            $(document).on('focus', '.mecas-query-input', this.handleSearchFocus.bind(this));

            // Location input
            $(document).on('input', '.mecas-location-input', this.handleLocationInput.bind(this));
            $(document).on('focus', '.mecas-location-input', this.handleLocationFocus.bind(this));

            // Suggestions
            $(document).on('click', '.mecas-suggestion-item', this.selectSuggestion.bind(this));
            $(document).on('keydown', '.mecas-input', this.handleKeyNavigation.bind(this));

            // Close suggestions on outside click
            $(document).on('click', this.closeSuggestions.bind(this));

            // Filter changes
            $(document).on('change', '.mecas-filter-select', this.handleFilterChange.bind(this));
            $(document).on('click', '.mecas-clear-filters', this.clearFilters.bind(this));

            // Form submit
            $(document).on('submit', '.mecas-search-form', this.handleFormSubmit.bind(this));
        },

        // ========================================
        // AUTO GEOLOCATION ON PAGE LOAD
        // ========================================

        initAutoGeolocation: function() {
            const self = this;
            
            // Find all search wrappers with auto-detect enabled
            $('.mecas-search-wrapper').each(function() {
                const $wrapper = $(this);
                const autoDetect = $wrapper.data('auto-detect');
                const enableGeo = $wrapper.data('enable-geolocation');
                const $input = $wrapper.find('.mecas-location-input');
                
                // Only auto-detect if enabled and input is empty
                if ((autoDetect === 'true' || autoDetect === true) && 
                    (enableGeo === 'true' || enableGeo === true) && 
                    $input.length && !$input.val()) {
                    
                    // Small delay to let page load
                    setTimeout(function() {
                        self.autoDetectLocation($wrapper);
                    }, 500);
                }
            });
        },

        autoDetectLocation: function($wrapper) {
            const $input = $wrapper.find('.mecas-location-input');
            const $loading = $wrapper.find('.mecas-location-loading');
            const originalPlaceholder = $input.attr('placeholder');

            if (!navigator.geolocation) {
                return;
            }

            // Show loading
            $loading.show();
            $input.attr('placeholder', mecas_ajax.i18n.detecting);

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.reverseGeocode(position.coords.latitude, position.coords.longitude, $input, $loading, originalPlaceholder);
                },
                (error) => {
                    console.log('Geolocation error:', error.message);
                    $loading.hide();
                    $input.attr('placeholder', originalPlaceholder || mecas_ajax.i18n.enter_location);
                },
                {
                    enableHighAccuracy: false,
                    timeout: 10000,
                    maximumAge: 300000 // Cache for 5 minutes
                }
            );
        },

        reverseGeocode: function(lat, lng, $input, $loading, originalPlaceholder) {
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_reverse_geocode',
                    nonce: mecas_ajax.nonce,
                    lat: lat,
                    lng: lng
                },
                success: (response) => {
                    $loading.hide();
                    
                    if (response.success && response.data.location) {
                        $input.val(response.data.location);
                        $input.attr('placeholder', originalPlaceholder || 'City, State');
                        this.geoDetected = true;
                    } else {
                        $input.attr('placeholder', originalPlaceholder || mecas_ajax.i18n.enter_location);
                    }
                },
                error: () => {
                    $loading.hide();
                    $input.attr('placeholder', originalPlaceholder || mecas_ajax.i18n.enter_location);
                }
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
                $modal.find('.mecas-query-input').first().focus();
                
                // Try auto-detect in modal if not already detected
                if (!this.geoDetected) {
                    const $wrapper = $modal.find('.mecas-search-wrapper');
                    if ($wrapper.data('auto-detect') === 'true' || $wrapper.data('auto-detect') === true) {
                        this.autoDetectLocation($wrapper);
                    }
                }
            }
        },

        closeModal: function(e) {
            if ($(e.target).hasClass('mecas-modal-backdrop') || $(e.target).closest('.mecas-modal-close').length) {
                $('.mecas-modal-overlay.active').removeClass('active');
                $('body').css('overflow', '');
            }
        },

        handleEscKey: function(e) {
            if (e.key === 'Escape') {
                this.closeModal({ target: document.querySelector('.mecas-modal-backdrop') });
            }
        },

        // ========================================
        // SEARCH SUGGESTIONS
        // ========================================

        handleSearchInput: function(e) {
            const $input = $(e.currentTarget);
            const query = $input.val().trim();
            const $wrapper = $input.closest('.mecas-input-group');
            const $suggestions = $wrapper.find('.mecas-suggestions');

            clearTimeout(this.debounceTimer);

            if (query.length < 2) {
                $suggestions.removeClass('active').empty();
                return;
            }

            this.debounceTimer = setTimeout(() => {
                this.fetchSearchSuggestions(query, $suggestions);
            }, this.debounceDelay);
        },

        handleSearchFocus: function(e) {
            const $input = $(e.currentTarget);
            const query = $input.val().trim();
            
            if (query.length >= 2) {
                const $wrapper = $input.closest('.mecas-input-group');
                const $suggestions = $wrapper.find('.mecas-suggestions');
                if ($suggestions.children().length > 0) {
                    $suggestions.addClass('active');
                }
            }
        },

        fetchSearchSuggestions: function(query, $suggestions) {
            $suggestions.html('<div class="mecas-suggestions-loading">' + mecas_ajax.i18n.loading + '</div>').addClass('active');

            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_search',
                    nonce: mecas_ajax.nonce,
                    query: query,
                    limit: 5
                },
                success: (response) => {
                    if (response.success && response.data.length > 0) {
                        this.renderSearchSuggestions(response.data, $suggestions);
                    } else {
                        $suggestions.html('<div class="mecas-suggestions-empty">' + mecas_ajax.i18n.no_results + '</div>');
                    }
                },
                error: () => {
                    $suggestions.removeClass('active').empty();
                }
            });
        },

        renderSearchSuggestions: function(events, $suggestions) {
            let html = '';
            
            events.forEach((event, index) => {
                const image = event.image 
                    ? `<img src="${event.image}" alt="" class="mecas-suggestion-image">`
                    : '<div class="mecas-suggestion-image"></div>';
                
                html += `
                    <div class="mecas-suggestion-item" data-url="${event.url}" data-index="${index}">
                        ${image}
                        <div class="mecas-suggestion-content">
                            <div class="mecas-suggestion-title">${event.title}</div>
                            <div class="mecas-suggestion-meta">${event.date}${event.location ? ' • ' + event.location : ''}</div>
                        </div>
                    </div>
                `;
            });
            
            $suggestions.html(html).addClass('active');
        },

        // ========================================
        // LOCATION SUGGESTIONS
        // ========================================

        handleLocationInput: function(e) {
            const $input = $(e.currentTarget);
            const query = $input.val().trim();
            const $wrapper = $input.closest('.mecas-input-group');
            const $suggestions = $wrapper.find('.mecas-suggestions');

            clearTimeout(this.debounceTimer);

            if (query.length < 2) {
                $suggestions.removeClass('active').empty();
                return;
            }

            this.debounceTimer = setTimeout(() => {
                this.fetchLocationSuggestions(query, $suggestions);
            }, this.debounceDelay);
        },

        handleLocationFocus: function(e) {
            const $input = $(e.currentTarget);
            const query = $input.val().trim();
            
            if (query.length >= 2) {
                const $wrapper = $input.closest('.mecas-input-group');
                const $suggestions = $wrapper.find('.mecas-suggestions');
                if ($suggestions.children().length > 0) {
                    $suggestions.addClass('active');
                }
            }
        },

        fetchLocationSuggestions: function(query, $suggestions) {
            $.ajax({
                url: mecas_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_get_locations',
                    nonce: mecas_ajax.nonce,
                    search: query
                },
                success: (response) => {
                    if (response.success && response.data.length > 0) {
                        this.renderLocationSuggestions(response.data, $suggestions);
                    } else {
                        $suggestions.removeClass('active').empty();
                    }
                }
            });
        },

        renderLocationSuggestions: function(locations, $suggestions) {
            let html = '';
            
            locations.forEach((loc, index) => {
                html += `
                    <div class="mecas-suggestion-item mecas-location-suggestion" data-value="${loc.display}" data-index="${index}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;color:#6B7280;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <div class="mecas-suggestion-content">
                            <div class="mecas-suggestion-title">${loc.display}</div>
                        </div>
                    </div>
                `;
            });
            
            $suggestions.html(html).addClass('active');
        },

        // ========================================
        // SUGGESTION SELECTION
        // ========================================

        selectSuggestion: function(e) {
            const $item = $(e.currentTarget);
            
            if ($item.hasClass('mecas-location-suggestion')) {
                const value = $item.data('value');
                $item.closest('.mecas-input-group').find('.mecas-location-input').val(value);
            } else if ($item.data('url')) {
                window.location.href = $item.data('url');
                return;
            }
            
            $item.closest('.mecas-suggestions').removeClass('active');
        },

        handleKeyNavigation: function(e) {
            const $input = $(e.currentTarget);
            const $suggestions = $input.closest('.mecas-input-group').find('.mecas-suggestions');
            const $items = $suggestions.find('.mecas-suggestion-item');
            const $active = $items.filter('.active');

            if (!$suggestions.hasClass('active') || $items.length === 0) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if ($active.length === 0) {
                    $items.first().addClass('active');
                } else {
                    $active.removeClass('active').next('.mecas-suggestion-item').addClass('active');
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if ($active.length) {
                    $active.removeClass('active').prev('.mecas-suggestion-item').addClass('active');
                }
            } else if (e.key === 'Enter' && $active.length) {
                e.preventDefault();
                $active.click();
            }
        },

        closeSuggestions: function(e) {
            if (!$(e.target).closest('.mecas-input-group').length) {
                $('.mecas-suggestions').removeClass('active');
            }
        },

        // ========================================
        // FILTERS
        // ========================================

        handleFilterChange: function(e) {
            const $select = $(e.currentTarget);
            const $container = $select.closest('.mecas-results-wrapper');
            
            this.filterEvents($container);
        },

        clearFilters: function(e) {
            e.preventDefault();
            const $container = $(e.currentTarget).closest('.mecas-results-wrapper');
            
            $container.find('.mecas-filter-select').val('');
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

            // Get search values if present
            const $searchForm = $container.find('.mecas-search-form');
            if ($searchForm.length) {
                const query = $searchForm.find('.mecas-query-input').val();
                const location = $searchForm.find('.mecas-location-input').val();
                if (query) filters.query = query;
                if (location) filters.location = location;
            }

            // Show loading
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
                success: (response) => {
                    if (response.success) {
                        this.renderFilteredEvents(response.data, $grid, $container);
                    }
                }
            });
        },

        renderFilteredEvents: function(data, $grid, $container) {
            if (data.events.length === 0) {
                $grid.html(`
                    <div class="mecas-no-results" style="grid-column: 1/-1;">
                        <svg class="mecas-no-results-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <p class="mecas-no-results-text">${$container.data('no-results') || mecas_ajax.i18n.no_results}</p>
                    </div>
                `);
                return;
            }

            let html = '';
            data.events.forEach(event => {
                const image = event.image 
                    ? `<img src="${event.image}" alt="${event.title}" class="mecas-event-image">`
                    : `<div class="mecas-event-image-placeholder"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></div>`;

                html += `
                    <div class="mecas-event-card">
                        <a href="${event.url}">
                            ${image}
                            <div class="mecas-event-card-content">
                                <h3 class="mecas-event-title">${event.title}</h3>
                                <div class="mecas-event-meta">
                                    ${event.date ? `<span class="mecas-event-date"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>${event.date}</span>` : ''}
                                    ${event.location ? `<span class="mecas-event-location"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>${event.location}</span>` : ''}
                                </div>
                            </div>
                        </a>
                    </div>
                `;
            });

            $grid.html(html);

            // Update count
            $container.find('.mecas-results-count').text(data.total + ' events found');
        },

        // ========================================
        // FORM SUBMIT
        // ========================================

        handleFormSubmit: function(e) {
            // Close modal if in popup mode
            const $modal = $(e.currentTarget).closest('.mecas-modal-overlay');
            if ($modal.length) {
                $modal.removeClass('active');
                $('body').css('overflow', '');
            }
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        MECAS.init();
    });

})(jQuery);
