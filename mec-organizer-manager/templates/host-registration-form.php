<?php
/**
 * Host Registration Form - Multi-Step
 * Shortcode: [mecom_host_registration]
 */

if (!defined('ABSPATH')) exit;
?>

<div class="mecom-host-registration-wrapper">
    <div class="mecom-host-registration-modal">
        <!-- Close Button -->
        <button type="button" class="mecom-modal-close" aria-label="<?php esc_attr_e('Close', 'mec-organizer-manager'); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M15 9l-6 6M9 9l6 6"/>
            </svg>
        </button>

        <!-- Logo -->
        <?php if ($logo_url): ?>
        <div class="mecom-modal-logo">
            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
        </div>
        <?php endif; ?>

        <!-- Form Container -->
        <form id="mecom-host-registration-form" class="mecom-host-form" method="post">
            <?php wp_nonce_field('mecom_registration_nonce', 'mecom_reg_nonce'); ?>
            <input type="hidden" name="action" value="mecom_register_host">

            <!-- Step 1: Account Info -->
            <div class="mecom-form-step active" data-step="1">
                <h2 class="mecom-form-title"><?php _e('Request a Host Account', 'mec-organizer-manager'); ?></h2>
                
                <p class="mecom-required-note">*<?php _e('required', 'mec-organizer-manager'); ?></p>
                
                <div class="mecom-form-group">
                    <label for="mecom_name"><?php _e('Your name', 'mec-organizer-manager'); ?>*</label>
                    <input type="text" id="mecom_name" name="name" placeholder="<?php esc_attr_e('Jane Doe', 'mec-organizer-manager'); ?>" required>
                    <span class="mecom-field-hint"><?php _e('Your name will be public on your Mahj Hub profile', 'mec-organizer-manager'); ?></span>
                </div>
                
                <div class="mecom-form-group">
                    <label for="mecom_email"><?php _e('Email address', 'mec-organizer-manager'); ?>*</label>
                    <input type="email" id="mecom_email" name="email" placeholder="<?php esc_attr_e('example@email.com', 'mec-organizer-manager'); ?>" required>
                    <span class="mecom-field-hint"><?php _e("We'll use your email address to send you updates", 'mec-organizer-manager'); ?></span>
                </div>
                
                <div class="mecom-form-group">
                    <label for="mecom_phone"><?php _e('Phone number', 'mec-organizer-manager'); ?>*</label>
                    <div class="mecom-phone-input-wrapper">
                        <select id="mecom_phone_country" name="phone_country" class="mecom-phone-country">
                            <option value="+1" selected>+1</option>
                            <option value="+44">+44</option>
                            <option value="+61">+61</option>
                            <option value="+49">+49</option>
                            <option value="+33">+33</option>
                            <option value="+81">+81</option>
                            <option value="+86">+86</option>
                            <option value="+91">+91</option>
                        </select>
                        <input type="tel" id="mecom_phone" name="phone" placeholder="<?php esc_attr_e('740 123 1234', 'mec-organizer-manager'); ?>" required>
                    </div>
                    <span class="mecom-field-hint"><?php _e("We'll use your phone number to send you updates and verify your account", 'mec-organizer-manager'); ?></span>
                </div>
                
                <div class="mecom-form-group">
                    <label for="mecom_location"><?php _e('Location', 'mec-organizer-manager'); ?>*</label>
                    <input type="text" id="mecom_location" name="location" placeholder="<?php esc_attr_e('City, State', 'mec-organizer-manager'); ?>" required>
                </div>
                
                <div class="mecom-form-group">
                    <label for="mecom_password"><?php _e('Password', 'mec-organizer-manager'); ?>*</label>
                    <div class="mecom-password-wrapper">
                        <input type="password" id="mecom_password" name="password" placeholder="<?php esc_attr_e('Enter password', 'mec-organizer-manager'); ?>" required minlength="8">
                        <button type="button" class="mecom-password-toggle" aria-label="<?php esc_attr_e('Show password', 'mec-organizer-manager'); ?>">
                            <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <?php if ($recaptcha_enabled && $recaptcha_site_key): ?>
                <div class="mecom-form-group mecom-recaptcha-group">
                    <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($recaptcha_site_key); ?>"></div>
                </div>
                <?php endif; ?>
                
                <div class="mecom-form-buttons">
                    <button type="button" class="mecom-btn mecom-btn-secondary mecom-btn-cancel"><?php _e('Cancel', 'mec-organizer-manager'); ?></button>
                    <button type="button" class="mecom-btn mecom-btn-primary mecom-btn-next" data-next="2"><?php _e('Continue', 'mec-organizer-manager'); ?></button>
                </div>
            </div>

            <!-- Step 2: Phone Verification (Placeholder - Skip for now) -->
            <div class="mecom-form-step" data-step="2" style="display:none;">
                <h2 class="mecom-form-title"><?php _e('Verify your phone number', 'mec-organizer-manager'); ?></h2>
                
                <p class="mecom-verify-message"><?php _e("We've sent a verification code to your phone number", 'mec-organizer-manager'); ?></p>
                <p class="mecom-verify-phone"><span id="mecom-display-phone">+1 123-1234-123</span></p>
                
                <div class="mecom-verification-code">
                    <input type="text" maxlength="1" class="mecom-code-input" data-index="0">
                    <input type="text" maxlength="1" class="mecom-code-input" data-index="1">
                    <input type="text" maxlength="1" class="mecom-code-input" data-index="2">
                    <input type="text" maxlength="1" class="mecom-code-input" data-index="3">
                </div>
                
                <p class="mecom-wrong-number"><?php _e('Wrong number?', 'mec-organizer-manager'); ?> <a href="#" class="mecom-change-phone"><?php _e('Change', 'mec-organizer-manager'); ?></a></p>
                
                <p class="mecom-resend-timer"><?php _e('Resend in', 'mec-organizer-manager'); ?> (<span id="mecom-resend-countdown">60</span>)</p>
                
                <div class="mecom-form-buttons mecom-single-button">
                    <button type="button" class="mecom-btn mecom-btn-primary mecom-btn-next" data-next="3"><?php _e('Verify', 'mec-organizer-manager'); ?></button>
                </div>
            </div>

            <!-- Step 3: Business Info -->
            <div class="mecom-form-step" data-step="3" style="display:none;">
                <h2 class="mecom-form-title"><?php _e('Enter your Business Info', 'mec-organizer-manager'); ?></h2>
                
                <p class="mecom-optional-note"><?php _e('If you do not have a business registered, you can leave the following blank and continue', 'mec-organizer-manager'); ?></p>
                
                <div class="mecom-form-group">
                    <label for="mecom_business_name"><?php _e('Business name', 'mec-organizer-manager'); ?></label>
                    <input type="text" id="mecom_business_name" name="business_name" placeholder="<?php esc_attr_e('Your Business Name', 'mec-organizer-manager'); ?>">
                </div>
                
                <div class="mecom-form-group">
                    <label for="mecom_business_address"><?php _e('Registered Address', 'mec-organizer-manager'); ?></label>
                    <input type="text" id="mecom_business_address" name="business_address" placeholder="<?php esc_attr_e('123 Main St, City, State 12345', 'mec-organizer-manager'); ?>">
                </div>
                
                <div class="mecom-form-group">
                    <label for="mecom_business_ein"><?php _e('Business Registration Number or EIN', 'mec-organizer-manager'); ?></label>
                    <input type="text" id="mecom_business_ein" name="business_ein" placeholder="<?php esc_attr_e('XX-XXXXXXX', 'mec-organizer-manager'); ?>">
                </div>
                
                <div class="mecom-form-buttons">
                    <button type="button" class="mecom-btn mecom-btn-secondary mecom-btn-prev" data-prev="1"><?php _e('Cancel', 'mec-organizer-manager'); ?></button>
                    <button type="button" class="mecom-btn mecom-btn-primary mecom-btn-next" data-next="4"><?php _e('Continue', 'mec-organizer-manager'); ?></button>
                </div>
            </div>

            <!-- Step 4: Profile Setup -->
            <div class="mecom-form-step" data-step="4" style="display:none;">
                <h2 class="mecom-form-title"><?php _e('Setup your profile page', 'mec-organizer-manager'); ?></h2>
                
                <div class="mecom-form-group">
                    <label for="mecom_website"><?php _e('Website Link', 'mec-organizer-manager'); ?></label>
                    <input type="url" id="mecom_website" name="website" placeholder="<?php esc_attr_e('https://yourwebsite.com', 'mec-organizer-manager'); ?>">
                </div>
                
                <div class="mecom-form-group">
                    <label><?php _e('Social Media links', 'mec-organizer-manager'); ?></label>
                    <div class="mecom-social-links-wrapper">
                        <div class="mecom-social-link-row">
                            <input type="url" name="social_links[]" placeholder="<?php esc_attr_e('www.instagram.com/', 'mec-organizer-manager'); ?>">
                        </div>
                    </div>
                    <button type="button" class="mecom-add-social-btn">
                        <?php _e('Add more', 'mec-organizer-manager'); ?> 
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                </div>
                
                <div class="mecom-form-group">
                    <label for="mecom_fun_facts"><?php _e('What are some fun facts about you?', 'mec-organizer-manager'); ?></label>
                    <textarea id="mecom_fun_facts" name="fun_facts" rows="4" placeholder="<?php esc_attr_e('max 250 words', 'mec-organizer-manager'); ?>" maxlength="1500"></textarea>
                    <span class="mecom-char-count"><span id="fun-facts-count">0</span>/250 <?php _e('words', 'mec-organizer-manager'); ?></span>
                </div>
                
                <div class="mecom-form-group">
                    <label for="mecom_description"><?php _e('Short description about you', 'mec-organizer-manager'); ?></label>
                    <textarea id="mecom_description" name="description" rows="5" placeholder="<?php esc_attr_e('max 750 words', 'mec-organizer-manager'); ?>" maxlength="4500"></textarea>
                    <span class="mecom-char-count"><span id="description-count">0</span>/750 <?php _e('words', 'mec-organizer-manager'); ?></span>
                </div>
                
                <div class="mecom-form-group">
                    <label for="mecom_need_business_help"><?php _e('Do you need help with setting up a business entity?', 'mec-organizer-manager'); ?></label>
                    <select id="mecom_need_business_help" name="need_business_help">
                        <option value="no"><?php _e('No', 'mec-organizer-manager'); ?></option>
                        <option value="yes"><?php _e('Yes', 'mec-organizer-manager'); ?></option>
                    </select>
                </div>
                
                <div class="mecom-form-buttons">
                    <button type="button" class="mecom-btn mecom-btn-secondary mecom-btn-prev" data-prev="3"><?php _e('Back', 'mec-organizer-manager'); ?></button>
                    <button type="button" class="mecom-btn mecom-btn-primary mecom-btn-submit"><?php _e('Finish', 'mec-organizer-manager'); ?></button>
                </div>
            </div>

            <!-- Step 5: Thank You -->
            <div class="mecom-form-step mecom-thank-you-step" data-step="5" style="display:none;">
                <h2 class="mecom-form-title"><?php _e('Thank you!', 'mec-organizer-manager'); ?></h2>
                
                <div class="mecom-thank-you-content">
                    <p class="mecom-thank-you-message">
                        <strong><?php _e('Thanks for signing up as a Host,', 'mec-organizer-manager'); ?></strong><br>
                        <?php _e('we will review your information', 'mec-organizer-manager'); ?>
                    </p>
                    
                    <p class="mecom-thank-you-note">
                        <?php _e('You will receive an email from us when your account is verified.', 'mec-organizer-manager'); ?>
                    </p>
                </div>
                
                <div class="mecom-form-buttons mecom-single-button">
                    <a href="<?php echo esc_url($redirect_url); ?>" class="mecom-btn mecom-btn-primary"><?php _e('Continue', 'mec-organizer-manager'); ?></a>
                </div>
            </div>

            <!-- Loading overlay -->
            <div class="mecom-form-loading" style="display:none;">
                <div class="mecom-spinner"></div>
                <p><?php _e('Submitting...', 'mec-organizer-manager'); ?></p>
            </div>
        </form>
    </div>
</div>
