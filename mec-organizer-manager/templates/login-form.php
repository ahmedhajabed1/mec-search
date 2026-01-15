<?php
/**
 * Login Form Template
 */

if (!defined('ABSPATH')) exit;

$redirect = isset($atts['redirect']) ? $atts['redirect'] : admin_url('edit.php?post_type=mec-events');
?>

<div class="mecom-login-form-wrap">
    <form class="mecom-login-form" method="post" action="<?php echo esc_url(wp_login_url($redirect)); ?>">
        <h2 class="mecom-form-title"><?php _e('Organizer Login', 'mec-organizer-manager'); ?></h2>
        
        <div class="mecom-form-group">
            <label for="mecom_user_login"><?php _e('Username or Email', 'mec-organizer-manager'); ?></label>
            <input type="text" name="log" id="mecom_user_login" class="mecom-input" required>
        </div>
        
        <div class="mecom-form-group">
            <label for="mecom_user_pass"><?php _e('Password', 'mec-organizer-manager'); ?></label>
            <input type="password" name="pwd" id="mecom_user_pass" class="mecom-input" required>
        </div>
        
        <div class="mecom-form-group mecom-remember">
            <label>
                <input type="checkbox" name="rememberme" value="forever">
                <?php _e('Remember me', 'mec-organizer-manager'); ?>
            </label>
        </div>
        
        <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect); ?>">
        
        <button type="submit" class="mecom-submit-button"><?php _e('Login', 'mec-organizer-manager'); ?></button>
        
        <div class="mecom-form-links">
            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php _e('Forgot password?', 'mec-organizer-manager'); ?></a>
            
            <?php if (get_option('mecom_registration_enabled', 'no') === 'yes'): ?>
                <span class="mecom-separator">|</span>
                <a href="<?php echo esc_url(add_query_arg('action', 'register', home_url())); ?>"><?php _e('Create an account', 'mec-organizer-manager'); ?></a>
            <?php endif; ?>
        </div>
    </form>
</div>
