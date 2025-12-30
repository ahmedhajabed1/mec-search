/**
 * MEC Advanced Search Admin JavaScript
 * Handles pre-indexing, settings tabs, and admin UI
 */

(function($) {
    'use strict';

    class MECASAdmin {
        constructor() {
            this.isIndexing = false;
            this.init();
        }

        init() {
            this.initTabs();
            this.initColorPickers();
            this.initCopyButtons();
            this.initIndexing();
            this.loadIndexStatus();
        }

        // Tab navigation
        initTabs() {
            $('.mecas-admin-tabs .nav-tab').on('click', function(e) {
                e.preventDefault();
                
                const target = $(this).attr('href');
                
                // Update tab active state
                $('.mecas-admin-tabs .nav-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                
                // Show target content
                $('.mecas-tab-content').removeClass('active');
                $(target).addClass('active');
                
                // Update URL hash
                if (history.pushState) {
                    history.pushState(null, null, target);
                }
            });
            
            // Check for hash on page load
            if (window.location.hash) {
                const $tab = $(`.mecas-admin-tabs .nav-tab[href="${window.location.hash}"]`);
                if ($tab.length) {
                    $tab.trigger('click');
                }
            }
        }

        // Initialize color pickers
        initColorPickers() {
            if ($.fn.wpColorPicker) {
                $('.mecas-color-picker').wpColorPicker({
                    change: function(event, ui) {
                        // Live preview could be added here
                    }
                });
            }
        }

        // Copy buttons for shortcodes
        initCopyButtons() {
            $('.mecas-copy-btn').on('click', function() {
                const textToCopy = $(this).data('copy');
                
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        const $btn = $(this);
                        const originalText = $btn.text();
                        $btn.text('Copied!');
                        setTimeout(() => $btn.text(originalText), 2000);
                    });
                } else {
                    // Fallback for older browsers
                    const $temp = $('<textarea>');
                    $('body').append($temp);
                    $temp.val(textToCopy).select();
                    document.execCommand('copy');
                    $temp.remove();
                    
                    const $btn = $(this);
                    const originalText = $btn.text();
                    $btn.text('Copied!');
                    setTimeout(() => $btn.text(originalText), 2000);
                }
            });
        }

        // Initialize indexing functionality
        initIndexing() {
            $('#mecas-reindex-btn').on('click', () => {
                if (this.isIndexing) return;
                
                if (!confirm(mecas_admin.strings.confirm_reindex)) {
                    return;
                }
                
                this.startIndexing();
            });
        }

        // Load current index status
        loadIndexStatus() {
            $.ajax({
                url: mecas_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_get_index_status',
                    nonce: mecas_admin.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.renderIndexStatus(response.data);
                    }
                },
                error: () => {
                    $('#mecas-index-status').html('<p class="error">Failed to load index status</p>');
                }
            });
        }

        // Render index status
        renderIndexStatus(data) {
            const percentage = data.total > 0 ? Math.round((data.indexed / data.total) * 100) : 0;
            const statusClass = data.indexed >= data.total ? 'success' : (data.indexed > 0 ? 'warning' : '');
            
            let lastIndexedText = 'Never';
            if (data.last_indexed) {
                const date = new Date(data.last_indexed);
                lastIndexedText = date.toLocaleString();
            }
            
            const html = `
                <div class="status-item">
                    <span class="status-label">Index Status:</span>
                    <span class="status-value ${statusClass}">${data.index_exists ? 'Active' : 'Not Created'}</span>
                </div>
                <div class="status-item">
                    <span class="status-label">Indexed Events:</span>
                    <span class="status-value ${statusClass}">${data.indexed} / ${data.total} (${percentage}%)</span>
                </div>
                <div class="status-item">
                    <span class="status-label">Last Full Index:</span>
                    <span class="status-value">${lastIndexedText}</span>
                </div>
            `;
            
            $('#mecas-index-status').html(html);
        }

        // Start indexing process
        startIndexing() {
            this.isIndexing = true;
            
            const $btn = $('#mecas-reindex-btn');
            const $spinner = $('.mecas-index-spinner');
            const $progress = $('#mecas-index-progress');
            const $progressFill = $progress.find('.mecas-progress-fill');
            const $progressPercent = $('#mecas-progress-percent');
            
            $btn.prop('disabled', true);
            $spinner.addClass('is-active');
            $progress.show();
            $progressFill.css('width', '0%');
            $progressPercent.text('0%');
            
            this.indexBatch(0);
        }

        // Index a batch of events
        indexBatch(batch) {
            $.ajax({
                url: mecas_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecas_reindex',
                    nonce: mecas_admin.nonce,
                    batch: batch
                },
                success: (response) => {
                    if (response.success) {
                        const data = response.data;
                        const percentage = Math.round((data.processed / data.total) * 100);
                        
                        // Update progress
                        $('#mecas-index-progress .mecas-progress-fill').css('width', percentage + '%');
                        $('#mecas-progress-percent').text(percentage + '%');
                        
                        if (data.complete) {
                            this.indexingComplete(data);
                        } else {
                            // Process next batch
                            this.indexBatch(data.next_batch);
                        }
                    } else {
                        this.indexingError();
                    }
                },
                error: () => {
                    this.indexingError();
                }
            });
        }

        // Indexing complete
        indexingComplete(data) {
            this.isIndexing = false;
            
            const $btn = $('#mecas-reindex-btn');
            const $spinner = $('.mecas-index-spinner');
            const $progress = $('#mecas-index-progress');
            
            $btn.prop('disabled', false);
            $spinner.removeClass('is-active');
            
            // Show success message
            const $progressText = $progress.find('.mecas-progress-text');
            $progressText.html(`<span style="color: #00a32a;">✓ ${mecas_admin.strings.indexed.replace('%d', data.total)}</span>`);
            
            // Reload status
            setTimeout(() => {
                this.loadIndexStatus();
                $progress.fadeOut();
            }, 3000);
        }

        // Indexing error
        indexingError() {
            this.isIndexing = false;
            
            const $btn = $('#mecas-reindex-btn');
            const $spinner = $('.mecas-index-spinner');
            const $progress = $('#mecas-index-progress');
            
            $btn.prop('disabled', false);
            $spinner.removeClass('is-active');
            
            const $progressText = $progress.find('.mecas-progress-text');
            $progressText.html(`<span style="color: #d63638;">✗ ${mecas_admin.strings.error}</span>`);
        }
    }

    // Initialize on document ready
    $(document).ready(function() {
        new MECASAdmin();
    });

})(jQuery);
