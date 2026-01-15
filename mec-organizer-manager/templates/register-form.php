<?php
/**
 * Registration Form Template
 */

if (!defined('ABSPATH')) exit;
?>

<div class="mecom-register-form-wrap">
    <form class="mecom-register-form" id="mecom-register-form">
        <h2 class="mecom-form-title"><?php _e('Become an Organizer', 'mec-organizer-manager'); ?></h2>
        <p class="mecom-form-subtitle"><?php _e('Create your account to start hosting events.', 'mec-organizer-manager'); ?></p>
        
        <div class="mecom-form-message" style="display:none;"></div>
        
        <div class="mecom-form-group">
            <label for="mecom_reg_name"><?php _e('Full Name', 'mec-organizer-manager'); ?> <span class="required">*</span></label>
            <input type="text" name="name" id="mecom_reg_name" class="mecom-input" required>
        </div>
        
        <div class="mecom-form-group">
            <label for="mecom_reg_email"><?php _e('Email Address', 'mec-organizer-manager'); ?> <span class="required">*</span></label>
            <input type="email" name="email" id="mecom_reg_email" class="mecom-input" required>
        </div>
        
        <div class="mecom-form-group">
            <label for="mecom_reg_password"><?php _e('Password', 'mec-organizer-manager'); ?> <span class="required">*</span></label>
            <input type="password" name="password" id="mecom_reg_password" class="mecom-input" minlength="8" required>
            <p class="mecom-field-hint"><?php _e('At least 8 characters', 'mec-organizer-manager'); ?></p>
        </div>
        
        <div class="mecom-form-group">
            <label for="mecom_reg_password_confirm"><?php _e('Confirm Password', 'mec-organizer-manager'); ?> <span class="required">*</span></label>
            <input type="password" name="password_confirm" id="mecom_reg_password_confirm" class="mecom-input" required>
        </div>
        
        <div class="mecom-form-group mecom-terms">
            <label>
                <input type="checkbox" name="terms" required>
                <?php printf(__('I agree to the %sTerms of Service%s', 'mec-organizer-manager'), '<a href="' . esc_url(get_privacy_policy_url()) . '" target="_blank">', '</a>'); ?>
            </label>
        </div>
        
        <?php wp_nonce_field('mecom_nonce', 'mecom_register_nonce'); ?>
        
        <button type="submit" class="mecom-submit-button">
            <span class="mecom-btn-text"><?php _e('Create Account', 'mec-organizer-manager'); ?></span>
            <span class="mecom-btn-loading" style="display:none;">
                <svg class="mecom-spinner" width="20" height="20" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-opacity="0.25"/>
                    <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
                </svg>
            </span>
        </button>
        
        <div class="mecom-form-links">
            <?php _e('Already have an account?', 'mec-organizer-manager'); ?>
            <a href="<?php echo esc_url(wp_login_url()); ?>"><?php _e('Login here', 'mec-organizer-manager'); ?></a>
        </div>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('#mecom-register-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $btn = $form.find('.mecom-submit-button');
        var $message = $form.find('.mecom-form-message');
        
        // Validate passwords match
        if ($form.find('[name="password"]').val() !== $form.find('[name="password_confirm"]').val()) {
            $message.removeClass('success').addClass('error').text('<?php _e('Passwords do not match', 'mec-organizer-manager'); ?>').show();
            return;
        }
        
        // Show loading
        $btn.prop('disabled', true);
        $btn.find('.mecom-btn-text').hide();
        $btn.find('.mecom-btn-loading').show();
        $message.hide();
        
        $.ajax({
            url: mecom_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'mecom_register_organizer',
                nonce: mecom_ajax.nonce,
                name: $form.find('[name="name"]').val(),
                email: $form.find('[name="email"]').val(),
                password: $form.find('[name="password"]').val()
            },
            success: function(response) {
                if (response.success) {
                    $message.removeClass('error').addClass('success').text(response.data.message).show();
                    setTimeout(function() {
                        window.location.href = response.data.redirect;
                    }, 1000);
                } else {
                    $message.removeClass('success').addClass('error').text(response.data).show();
                    $btn.prop('disabled', false);
                    $btn.find('.mecom-btn-text').show();
                    $btn.find('.mecom-btn-loading').hide();
                }
            },
            error: function() {
                $message.removeClass('success').addClass('error').text('<?php _e('An error occurred. Please try again.', 'mec-organizer-manager'); ?>').show();
                $btn.prop('disabled', false);
                $btn.find('.mecom-btn-text').show();
                $btn.find('.mecom-btn-loading').hide();
            }
        });
    });
});
</script>
