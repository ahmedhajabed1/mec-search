<?php
/**
 * User Dashboard AJAX Template
 * Rendered when Edit Profile is clicked
 */

if (!defined('ABSPATH')) exit;

// Variables should be set by the calling function
$user = wp_get_current_user();
$user_id = $user->ID;

$first_name = $first_name ?? $user->first_name;
$last_name = $last_name ?? $user->last_name;
$email = $email ?? $user->user_email;
$website = $website ?? $user->user_url;
$bio = $bio ?? $user->description;
$phone = $phone ?? get_user_meta($user_id, 'mecas_phone', true);
$location = $location ?? get_user_meta($user_id, 'mecas_location', true);

$custom_avatar = get_user_meta($user_id, 'mecas_avatar_url', true);
$avatar_url = $avatar_url ?? ($custom_avatar ?: get_avatar_url($user_id, array('size' => 200)));
?>

<div class="mecas-user-dashboard mecas-dashboard-ajax-loaded">
    <h2 class="mecas-dashboard-title"><?php _e('Edit Profile', 'mec-starter-addons'); ?></h2>
    
    <form class="mecas-dashboard-form" enctype="multipart/form-data">
        <?php wp_nonce_field('mecas_dashboard_nonce', 'mecas_dashboard_nonce'); ?>
        
        <div class="mecas-dashboard-fields">
            <!-- Name Fields Row -->
            <div class="mecas-fields-row mecas-fields-row-2">
                <div class="mecas-field-group">
                    <label class="mecas-field-label"><?php _e('First Name', 'mec-starter-addons'); ?></label>
                    <div class="mecas-field-wrapper has-icon">
                        <span class="mecas-field-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        <input type="text" name="first_name" class="mecas-field-input" value="<?php echo esc_attr($first_name); ?>" placeholder="First">
                    </div>
                </div>
                
                <div class="mecas-field-group">
                    <label class="mecas-field-label"><?php _e('Last Name', 'mec-starter-addons'); ?></label>
                    <div class="mecas-field-wrapper has-icon">
                        <span class="mecas-field-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        <input type="text" name="last_name" class="mecas-field-input" value="<?php echo esc_attr($last_name); ?>" placeholder="Last">
                    </div>
                </div>
            </div>
            
            <!-- Email & Website Row -->
            <div class="mecas-fields-row mecas-fields-row-2">
                <div class="mecas-field-group">
                    <label class="mecas-field-label"><?php _e('Email', 'mec-starter-addons'); ?></label>
                    <div class="mecas-field-wrapper has-icon">
                        <span class="mecas-field-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </span>
                        <input type="email" name="email" class="mecas-field-input" value="<?php echo esc_attr($email); ?>">
                    </div>
                </div>
                
                <div class="mecas-field-group">
                    <label class="mecas-field-label"><?php _e('Website', 'mec-starter-addons'); ?></label>
                    <div class="mecas-field-wrapper has-icon">
                        <span class="mecas-field-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                        </span>
                        <input type="url" name="website" class="mecas-field-input" value="<?php echo esc_attr($website); ?>" placeholder="https://">
                    </div>
                </div>
            </div>
            
            <!-- Phone & Location Row -->
            <div class="mecas-fields-row mecas-fields-row-2">
                <div class="mecas-field-group">
                    <label class="mecas-field-label"><?php _e('Phone Number', 'mec-starter-addons'); ?></label>
                    <div class="mecas-field-wrapper has-icon">
                        <span class="mecas-field-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </span>
                        <input type="tel" name="phone" class="mecas-field-input" value="<?php echo esc_attr($phone); ?>" placeholder="+1 (555) 123-4567">
                    </div>
                </div>
                
                <div class="mecas-field-group">
                    <label class="mecas-field-label"><?php _e('Location', 'mec-starter-addons'); ?></label>
                    <div class="mecas-field-wrapper has-icon">
                        <span class="mecas-field-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </span>
                        <input type="text" name="location" class="mecas-field-input" value="<?php echo esc_attr($location); ?>" placeholder="City, State">
                    </div>
                </div>
            </div>
            
            <!-- Profile Picture -->
            <div class="mecas-fields-row">
                <div class="mecas-field-group mecas-field-full">
                    <label class="mecas-field-label"><?php _e('Profile Picture', 'mec-starter-addons'); ?></label>
                    <div class="mecas-avatar-upload">
                        <div class="mecas-avatar-preview">
                            <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($user->display_name); ?>">
                        </div>
                        <div class="mecas-avatar-actions">
                            <label class="mecas-avatar-change-btn">
                                <span><?php _e('Change Profile Picture', 'mec-starter-addons'); ?></span>
                                <input type="file" name="profile_picture" accept="image/*" style="display: none;">
                            </label>
                            <span class="mecas-avatar-filename"><?php _e('No file chosen', 'mec-starter-addons'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Messages -->
        <div class="mecas-dashboard-message mecas-dashboard-success" style="display: none;"></div>
        <div class="mecas-dashboard-message mecas-dashboard-error" style="display: none;"></div>
        
        <!-- Buttons -->
        <div class="mecas-dashboard-buttons">
            <div class="mecas-buttons-left">
                <button type="submit" class="mecas-dashboard-btn mecas-dashboard-save">
                    <span class="mecas-btn-text"><?php _e('Save Changes', 'mec-starter-addons'); ?></span>
                    <span class="mecas-btn-loading" style="display: none;">
                        <svg class="mecas-spinner" width="20" height="20" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="32" stroke-linecap="round">
                                <animate attributeName="stroke-dashoffset" values="0;64" dur="1s" repeatCount="indefinite"/>
                            </circle>
                        </svg>
                    </span>
                </button>
                
                <button type="button" class="mecas-dashboard-btn mecas-dashboard-cancel">
                    <?php _e('Cancel', 'mec-starter-addons'); ?>
                </button>
            </div>
            
            <div class="mecas-buttons-right">
                <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="mecas-dashboard-btn mecas-dashboard-logout">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <?php _e('Logout', 'mec-starter-addons'); ?>
                </a>
            </div>
        </div>
    </form>
</div>

<script>
(function() {
    const dashboard = document.querySelector('.mecas-dashboard-ajax-loaded');
    if (!dashboard) return;
    
    const form = dashboard.querySelector('.mecas-dashboard-form');
    const saveBtn = form.querySelector('.mecas-dashboard-save');
    const cancelBtn = form.querySelector('.mecas-dashboard-cancel');
    const fileInput = form.querySelector('input[name="profile_picture"]');
    const filenameDisplay = form.querySelector('.mecas-avatar-filename');
    const avatarPreview = form.querySelector('.mecas-avatar-preview img');
    const successMsg = form.querySelector('.mecas-dashboard-success');
    const errorMsg = form.querySelector('.mecas-dashboard-error');
    
    // File input change
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                filenameDisplay.textContent = file.name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Cancel button
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            const container = dashboard.closest('.mecas-dashboard-ajax-container');
            if (container) {
                container.innerHTML = '';
                container.style.display = 'none';
            }
        });
    }
    
    // Form submit
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btnText = saveBtn.querySelector('.mecas-btn-text');
        const btnLoading = saveBtn.querySelector('.mecas-btn-loading');
        
        saveBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline-flex';
        successMsg.style.display = 'none';
        errorMsg.style.display = 'none';
        
        const formData = new FormData(form);
        formData.append('action', 'mecas_save_dashboard');
        formData.append('nonce', form.querySelector('#mecas_dashboard_nonce').value);
        
        try {
            const response = await fetch(mecas_ajax.ajax_url, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                successMsg.textContent = data.data.message;
                successMsg.style.display = 'block';
                
                if (data.data.avatar_url) {
                    avatarPreview.src = data.data.avatar_url;
                    const profileCardAvatar = document.querySelector('.mecas-profile-avatar img');
                    if (profileCardAvatar) {
                        profileCardAvatar.src = data.data.avatar_url;
                    }
                }
                
                if (data.data.display_name) {
                    const profileCardName = document.querySelector('.mecas-profile-name');
                    if (profileCardName) {
                        profileCardName.textContent = data.data.display_name;
                    }
                }
                
                successMsg.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                errorMsg.textContent = data.data.message || 'An error occurred';
                errorMsg.style.display = 'block';
            }
        } catch (error) {
            errorMsg.textContent = 'An error occurred. Please try again.';
            errorMsg.style.display = 'block';
        }
        
        saveBtn.disabled = false;
        btnText.style.display = 'inline';
        btnLoading.style.display = 'none';
    });
})();
</script>
