<?php
/**
 * Welcome Email Template
 * Variables available: $site_name, $login_url, $logo_url, $username, $password, $email, $name
 */

if (!defined('ABSPATH')) exit;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($site_name); ?> - Welcome</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; background-color: #f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                    
                    <!-- Header with dark bar -->
                    <tr>
                        <td style="background-color: #3D4F5F; height: 8px;"></td>
                    </tr>
                    
                    <!-- Logo -->
                    <?php if ($logo_url): ?>
                    <tr>
                        <td align="center" style="padding: 40px 40px 20px;">
                            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($site_name); ?>" style="max-width: 100px; height: auto;">
                        </td>
                    </tr>
                    <?php endif; ?>
                    
                    <!-- Title -->
                    <tr>
                        <td align="center" style="padding: 20px 40px;">
                            <h1 style="margin: 0; font-family: 'Georgia', serif; font-size: 28px; font-weight: 400; color: #1a1a1a;">
                                <?php _e('Welcome to', 'mec-organizer-manager'); ?> <?php echo esc_html($site_name); ?>!
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 20px 40px;">
                            <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6; color: #333;">
                                <?php printf(__('Hi %s,', 'mec-organizer-manager'), esc_html($name)); ?>
                            </p>
                            
                            <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6; color: #333;">
                                <?php _e('Your host account has been approved! You can now log in and start managing your events.', 'mec-organizer-manager'); ?>
                            </p>
                            
                            <!-- Credentials Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-radius: 8px; margin: 30px 0;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <p style="margin: 0 0 15px; font-size: 14px; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 1px;">
                                            <?php _e('Your Login Credentials', 'mec-organizer-manager'); ?>
                                        </p>
                                        
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 15px; color: #666; width: 100px;">
                                                    <?php _e('Email:', 'mec-organizer-manager'); ?>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 15px; color: #333; font-weight: 600;">
                                                    <?php echo esc_html($email); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 15px; color: #666;">
                                                    <?php _e('Password:', 'mec-organizer-manager'); ?>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 15px; color: #333; font-weight: 600;">
                                                    <?php echo esc_html($password); ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0 0 30px; font-size: 14px; color: #888;">
                                <?php _e('For security, we recommend changing your password after your first login.', 'mec-organizer-manager'); ?>
                            </p>
                            
                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="<?php echo esc_url($login_url); ?>" style="display: inline-block; padding: 16px 40px; background-color: #3D4F5F; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500; border-radius: 50px;">
                                            <?php _e('Log In to Your Account', 'mec-organizer-manager'); ?>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- What's Next -->
                    <tr>
                        <td style="padding: 30px 40px; background-color: #f8f9fa;">
                            <h3 style="margin: 0 0 15px; font-size: 16px; color: #333;">
                                <?php _e("What's Next?", 'mec-organizer-manager'); ?>
                            </h3>
                            <ul style="margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.8; color: #666;">
                                <li><?php _e('Complete your profile with photos and bio', 'mec-organizer-manager'); ?></li>
                                <li><?php _e('Create your first event', 'mec-organizer-manager'); ?></li>
                                <li><?php _e('Share your profile page with potential attendees', 'mec-organizer-manager'); ?></li>
                            </ul>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px 40px; border-top: 1px solid #eee;">
                            <p style="margin: 0; font-size: 13px; color: #888; text-align: center;">
                                <?php printf(__('Questions? Reply to this email or visit %s', 'mec-organizer-manager'), '<a href="' . home_url() . '" style="color: #3D4F5F;">' . esc_html($site_name) . '</a>'); ?>
                            </p>
                        </td>
                    </tr>
                    
                </table>
                
                <!-- Copyright -->
                <table width="600" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding: 20px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #999;">
                                &copy; <?php echo date('Y'); ?> <?php echo esc_html($site_name); ?>. <?php _e('All rights reserved.', 'mec-organizer-manager'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                
            </td>
        </tr>
    </table>
</body>
</html>
