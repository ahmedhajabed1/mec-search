/**
 * MEC Organizer Manager - Admin Scripts
 */

(function($) {
    'use strict';

    const MECOMAdmin = {
        init: function() {
            this.bindEvents();
        },
        
        bindEvents: function() {
            // Link user to organizer
            $(document).on('click', '.mecom-link-btn', this.handleLinkUser.bind(this));
            
            // Unlink user from organizer
            $(document).on('click', '.mecom-unlink-btn, .mecom-unlink-user', this.handleUnlinkUser.bind(this));
            
            // Create user for organizer
            $(document).on('click', '.mecom-create-user-btn', this.handleCreateUser.bind(this));
            
            // Approve registration
            $(document).on('click', '.mecom-approve-btn', this.handleApproveRegistration.bind(this));
            
            // Reject registration
            $(document).on('click', '.mecom-reject-btn', this.handleRejectRegistration.bind(this));
            
            // View registration details
            $(document).on('click', '.mecom-view-details-btn', this.handleViewDetails.bind(this));
        },
        
        handleLinkUser: function(e) {
            e.preventDefault();
            
            const $btn = $(e.currentTarget);
            const $row = $btn.closest('.mecom-organizer-row');
            const termId = $btn.data('term-id');
            const $select = $row.find('.mecom-user-select');
            const userId = $select.val();
            
            if (!userId) {
                alert('Please select a user to link');
                return;
            }
            
            $row.addClass('processing');
            $btn.text(mecom_admin.i18n.linking);
            
            $.ajax({
                url: mecom_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecom_link_user',
                    nonce: mecom_admin.nonce,
                    term_id: termId,
                    user_id: userId
                },
                success: function(response) {
                    if (response.success) {
                        // Reload page to show updated state
                        location.reload();
                    } else {
                        alert(response.data || mecom_admin.i18n.error);
                        $row.removeClass('processing');
                        $btn.text('Link');
                    }
                },
                error: function() {
                    alert(mecom_admin.i18n.error);
                    $row.removeClass('processing');
                    $btn.text('Link');
                }
            });
        },
        
        handleUnlinkUser: function(e) {
            e.preventDefault();
            
            if (!confirm(mecom_admin.i18n.confirm_unlink)) {
                return;
            }
            
            const $btn = $(e.currentTarget);
            const $row = $btn.closest('.mecom-organizer-row, tr');
            const termId = $btn.data('term-id');
            
            $row.addClass('processing');
            $btn.text(mecom_admin.i18n.unlinking);
            
            $.ajax({
                url: mecom_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecom_unlink_user',
                    nonce: mecom_admin.nonce,
                    term_id: termId
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data || mecom_admin.i18n.error);
                        $row.removeClass('processing');
                        $btn.text('Unlink');
                    }
                },
                error: function() {
                    alert(mecom_admin.i18n.error);
                    $row.removeClass('processing');
                    $btn.text('Unlink');
                }
            });
        },
        
        handleCreateUser: function(e) {
            e.preventDefault();
            
            const $btn = $(e.currentTarget);
            const $row = $btn.closest('.mecom-organizer-row');
            const termId = $btn.data('term-id');
            
            $row.addClass('processing');
            $btn.text(mecom_admin.i18n.creating);
            
            $.ajax({
                url: mecom_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecom_create_user_for_organizer',
                    nonce: mecom_admin.nonce,
                    term_id: termId
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data || mecom_admin.i18n.error);
                        $row.removeClass('processing');
                        $btn.text('Create User');
                    }
                },
                error: function() {
                    alert(mecom_admin.i18n.error);
                    $row.removeClass('processing');
                    $btn.text('Create User');
                }
            });
        },
        
        handleApproveRegistration: function(e) {
            e.preventDefault();
            
            if (!confirm(mecom_admin.i18n.confirm_approve)) {
                return;
            }
            
            const $btn = $(e.currentTarget);
            const $row = $btn.closest('.mecom-registration-row');
            const regId = $btn.data('id');
            
            $row.addClass('processing');
            $btn.text(mecom_admin.i18n.approving);
            
            $.ajax({
                url: mecom_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecom_approve_registration',
                    nonce: mecom_admin.nonce,
                    registration_id: regId
                },
                success: function(response) {
                    if (response.success) {
                        // Remove row and details row
                        $row.next('.mecom-registration-details').remove();
                        $row.fadeOut(300, function() {
                            $(this).remove();
                            
                            // Check if no more pending
                            if ($('.mecom-registration-row').length === 0) {
                                location.reload();
                            }
                        });
                        
                        // Update badge count if visible
                        const $badge = $('.nav-tab .mecom-badge');
                        if ($badge.length) {
                            const count = parseInt($badge.text()) - 1;
                            if (count > 0) {
                                $badge.text(count);
                            } else {
                                $badge.remove();
                            }
                        }
                    } else {
                        alert(response.data || mecom_admin.i18n.error);
                        $row.removeClass('processing');
                        $btn.text('Approve');
                    }
                },
                error: function() {
                    alert(mecom_admin.i18n.error);
                    $row.removeClass('processing');
                    $btn.text('Approve');
                }
            });
        },
        
        handleRejectRegistration: function(e) {
            e.preventDefault();
            
            if (!confirm(mecom_admin.i18n.confirm_reject)) {
                return;
            }
            
            const $btn = $(e.currentTarget);
            const $row = $btn.closest('.mecom-registration-row');
            const regId = $btn.data('id');
            
            $row.addClass('processing');
            $btn.text(mecom_admin.i18n.rejecting);
            
            $.ajax({
                url: mecom_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'mecom_reject_registration',
                    nonce: mecom_admin.nonce,
                    registration_id: regId
                },
                success: function(response) {
                    if (response.success) {
                        $row.next('.mecom-registration-details').remove();
                        $row.fadeOut(300, function() {
                            $(this).remove();
                            
                            if ($('.mecom-registration-row').length === 0) {
                                location.reload();
                            }
                        });
                        
                        const $badge = $('.nav-tab .mecom-badge');
                        if ($badge.length) {
                            const count = parseInt($badge.text()) - 1;
                            if (count > 0) {
                                $badge.text(count);
                            } else {
                                $badge.remove();
                            }
                        }
                    } else {
                        alert(response.data || mecom_admin.i18n.error);
                        $row.removeClass('processing');
                        $btn.text('Reject');
                    }
                },
                error: function() {
                    alert(mecom_admin.i18n.error);
                    $row.removeClass('processing');
                    $btn.text('Reject');
                }
            });
        },
        
        handleViewDetails: function(e) {
            e.preventDefault();
            
            const $btn = $(e.currentTarget);
            const regId = $btn.data('id');
            const $detailsRow = $('.mecom-registration-details[data-id="' + regId + '"]');
            
            $detailsRow.toggle();
        }
    };
    
    $(document).ready(function() {
        MECOMAdmin.init();
    });
    
})(jQuery);
