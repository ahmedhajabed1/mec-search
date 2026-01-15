<?php
/**
 * Complete Profile Widget
 * For users who signed up via social login and need to add phone/location
 */

if (!defined('ABSPATH')) exit;

class MECAS_Complete_Profile_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_complete_profile';
    }
    
    public function get_title() {
        return __('Complete Profile', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-user-circle-o';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['profile', 'phone', 'complete', 'social', 'google', 'verification'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'title',
            [
                'label' => __('Title', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Complete Your Profile', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Please add your phone number and location to complete your registration.', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'show_phone',
            [
                'label' => __('Show Phone Field', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'phone_required',
            [
                'label' => __('Phone Required', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_phone' => 'yes'],
            ]
        );
        
        $this->add_control(
            'show_location',
            [
                'label' => __('Show Location Field', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'location_required',
            [
                'label' => __('Location Required', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_location' => 'yes'],
            ]
        );
        
        $this->add_control(
            'enable_phone_verification',
            [
                'label' => __('Enable Phone Verification', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Requires Twilio to be configured', 'mec-starter-addons'),
                'condition' => ['show_phone' => 'yes'],
            ]
        );
        
        $this->add_control(
            'submit_button_text',
            [
                'label' => __('Submit Button Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Continue', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'verify_button_text',
            [
                'label' => __('Verify Button Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Verify', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'success_message',
            [
                'label' => __('Success Message', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Profile updated successfully!', 'mec-starter-addons'),
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Title
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Title', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mecas-cp-title',
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-cp-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __('Margin', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .mecas-cp-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Form
        $this->start_controls_section(
            'section_style_form',
            [
                'label' => __('Form', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __('Label Typography', 'mec-starter-addons'),
                'selector' => '{{WRAPPER}} .mecas-cp-form-group label',
            ]
        );
        
        $this->add_control(
            'label_color',
            [
                'label' => __('Label Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-cp-form-group label' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'input_border_radius',
            [
                'label' => __('Input Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'selectors' => [
                    '{{WRAPPER}} .mecas-cp-form-group input, {{WRAPPER}} .mecas-cp-form-group select' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Button
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Button', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecas-cp-btn-primary' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecas-cp-btn-primary' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 30, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-cp-btn-primary' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Check if user is logged in
        if (!is_user_logged_in()) {
            echo '<div class="mecas-cp-not-logged-in">';
            echo '<p>' . __('Please log in to complete your profile.', 'mec-starter-addons') . '</p>';
            echo '</div>';
            return;
        }
        
        $current_user = wp_get_current_user();
        $user_phone = get_user_meta($current_user->ID, 'mecas_phone', true);
        $user_location = get_user_meta($current_user->ID, 'mecas_location', true);
        
        $show_phone = $settings['show_phone'] === 'yes';
        $show_location = $settings['show_location'] === 'yes';
        $phone_required = $settings['phone_required'] === 'yes';
        $location_required = $settings['location_required'] === 'yes';
        $enable_verification = $settings['enable_phone_verification'] === 'yes';
        
        // Check if Twilio is enabled
        $twilio_enabled = get_option('mecas_twilio_enabled', 'no') === 'yes' || get_option('mecom_twilio_enabled', 'no') === 'yes';
        $verification_available = $enable_verification && $twilio_enabled;
        ?>
        <div class="mecas-complete-profile-wrapper" data-verification="<?php echo $verification_available ? 'true' : 'false'; ?>">
            
            <!-- Step 1: Phone & Location Form -->
            <div class="mecas-cp-step mecas-cp-step-1 mecas-cp-active" data-step="1">
                <?php if (!empty($settings['title'])): ?>
                <h3 class="mecas-cp-title"><?php echo esc_html($settings['title']); ?></h3>
                <?php endif; ?>
                
                <?php if (!empty($settings['subtitle'])): ?>
                <p class="mecas-cp-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
                <?php endif; ?>
                
                <form class="mecas-cp-form" id="mecas-complete-profile-form">
                    <?php wp_nonce_field('mecas_nonce', 'mecas_cp_nonce'); ?>
                    
                    <?php if ($show_phone): ?>
                    <div class="mecas-cp-form-group">
                        <label for="mecas_cp_phone"><?php _e('Phone number', 'mec-starter-addons'); ?><?php echo $phone_required ? '*' : ''; ?></label>
                        <div class="mecas-cp-phone-wrapper">
                            <select id="mecas_cp_phone_country" name="phone_country" class="mecas-cp-phone-country">
                                <option value="+1">+1</option>
                                <option value="+44">+44</option>
                                <option value="+49">+49</option>
                                <option value="+33">+33</option>
                                <option value="+61">+61</option>
                                <option value="+81">+81</option>
                                <option value="+86">+86</option>
                                <option value="+91">+91</option>
                                <option value="+52">+52</option>
                                <option value="+55">+55</option>
                            </select>
                            <input type="tel" id="mecas_cp_phone" name="phone" placeholder="<?php esc_attr_e('740 123 1234', 'mec-starter-addons'); ?>" <?php echo $phone_required ? 'required' : ''; ?> value="<?php echo esc_attr(preg_replace('/^\+\d+/', '', $user_phone)); ?>">
                        </div>
                        <p class="mecas-cp-field-note"><?php _e("We'll use your phone number to send you updates", 'mec-starter-addons'); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($show_location): ?>
                    <div class="mecas-cp-form-group mecas-cp-location-group">
                        <label for="mecas_cp_location"><?php _e('Location', 'mec-starter-addons'); ?><?php echo $location_required ? '*' : ''; ?></label>
                        <div class="mecas-cp-location-wrapper">
                            <input type="text" id="mecas_cp_location" name="location" placeholder="<?php esc_attr_e('City, State', 'mec-starter-addons'); ?>" <?php echo $location_required ? 'required' : ''; ?> value="<?php echo esc_attr($user_location); ?>">
                            <button type="button" class="mecas-cp-detect-location" title="<?php esc_attr_e('Detect my location', 'mec-starter-addons'); ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3c-.46-4.17-3.77-7.48-7.94-7.94V1h-2v2.06C6.83 3.52 3.52 6.83 3.06 11H1v2h2.06c.46 4.17 3.77 7.48 7.94 7.94V23h2v-2.06c4.17-.46 7.48-3.77 7.94-7.94H23v-2h-2.06zM12 19c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/></svg>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mecas-cp-form-buttons">
                        <button type="submit" class="mecas-cp-btn mecas-cp-btn-primary mecas-cp-btn-submit">
                            <?php echo esc_html($settings['submit_button_text']); ?>
                        </button>
                    </div>
                </form>
            </div>
            
            <?php if ($verification_available && $show_phone): ?>
            <!-- Step 2: Phone Verification -->
            <div class="mecas-cp-step mecas-cp-step-2" data-step="2" style="display:none;">
                <h3 class="mecas-cp-title"><?php _e('Verify your phone number', 'mec-starter-addons'); ?></h3>
                <p class="mecas-cp-verify-message"><?php _e("We've sent a verification code to your phone number", 'mec-starter-addons'); ?></p>
                <p class="mecas-cp-verify-phone"><span id="mecas-cp-display-phone"></span></p>
                
                <div class="mecas-cp-verification-code">
                    <input type="text" maxlength="1" class="mecas-cp-code-input" data-index="0" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="mecas-cp-code-input" data-index="1" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="mecas-cp-code-input" data-index="2" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="mecas-cp-code-input" data-index="3" inputmode="numeric" pattern="[0-9]">
                </div>
                
                <p class="mecas-cp-wrong-number">
                    <?php _e('Wrong number?', 'mec-starter-addons'); ?> 
                    <a href="#" class="mecas-cp-change-phone"><?php _e('Change', 'mec-starter-addons'); ?></a>
                </p>
                
                <p class="mecas-cp-resend-timer"><?php _e('Resend in', 'mec-starter-addons'); ?> (<span id="mecas-cp-resend-countdown">60</span>)</p>
                <p class="mecas-cp-resend-link" style="display:none;"><a href="#" class="mecas-cp-resend-code"><?php _e('Resend Code', 'mec-starter-addons'); ?></a></p>
                
                <div class="mecas-cp-form-buttons">
                    <button type="button" class="mecas-cp-btn mecas-cp-btn-primary mecas-cp-btn-verify">
                        <?php echo esc_html($settings['verify_button_text']); ?>
                    </button>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Step 3: Success -->
            <div class="mecas-cp-step mecas-cp-step-3" data-step="3" style="display:none;">
                <div class="mecas-cp-success">
                    <div class="mecas-cp-success-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24"><path fill="#22c55e" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    </div>
                    <p class="mecas-cp-success-message"><?php echo esc_html($settings['success_message']); ?></p>
                </div>
            </div>
            
            <div class="mecas-cp-loading" style="display:none;">
                <div class="mecas-cp-spinner"></div>
            </div>
            
            <div class="mecas-cp-error" style="display:none;"></div>
        </div>
        
        <style>
        .mecas-complete-profile-wrapper {
            max-width: 400px;
            margin: 0 auto;
            position: relative;
        }
        .mecas-cp-step { display: none; }
        .mecas-cp-step.mecas-cp-active { display: block; }
        .mecas-cp-title {
            font-size: 24px;
            font-weight: 600;
            color: #1F2937;
            margin: 0 0 10px;
            text-align: center;
        }
        .mecas-cp-subtitle {
            color: #6B7280;
            font-size: 14px;
            margin: 0 0 25px;
            text-align: center;
        }
        .mecas-cp-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .mecas-cp-form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .mecas-cp-form-group label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }
        .mecas-cp-form-group input,
        .mecas-cp-form-group select {
            width: 100%;
            padding: 12px 15px;
            font-size: 15px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            background: #FFFFFF;
            box-sizing: border-box;
        }
        .mecas-cp-form-group input:focus,
        .mecas-cp-form-group select:focus {
            outline: none;
            border-color: #1F2937;
        }
        .mecas-cp-phone-wrapper {
            display: flex;
            gap: 10px;
        }
        .mecas-cp-phone-wrapper select {
            width: 90px;
            flex-shrink: 0;
        }
        .mecas-cp-phone-wrapper input {
            flex: 1;
        }
        .mecas-cp-location-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .mecas-cp-location-wrapper input {
            flex: 1;
            padding-right: 45px !important;
        }
        .mecas-cp-detect-location {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            padding: 5px;
            cursor: pointer;
            color: #9CA3AF;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }
        .mecas-cp-detect-location:hover {
            color: #1F2937;
        }
        .mecas-cp-detect-location.mecas-cp-detecting {
            animation: mecas-cp-pulse 1s infinite;
        }
        @keyframes mecas-cp-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .mecas-cp-field-note {
            font-size: 12px;
            color: #9CA3AF;
            margin: 4px 0 0;
            font-style: italic;
        }
        .mecas-cp-form-buttons {
            margin-top: 10px;
        }
        .mecas-cp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 30px;
            font-size: 15px;
            font-weight: 500;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
        }
        .mecas-cp-btn-primary {
            background: #1F2937;
            color: #FFFFFF;
        }
        .mecas-cp-btn-primary:hover {
            background: #374151;
        }
        /* Verification Step */
        .mecas-cp-verify-message,
        .mecas-cp-verify-phone {
            text-align: center;
            color: #6B7280;
        }
        .mecas-cp-verify-phone {
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 25px;
        }
        .mecas-cp-verification-code {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }
        .mecas-cp-code-input {
            width: 55px !important;
            height: 55px;
            text-align: center;
            font-size: 24px !important;
            font-weight: 600;
            border: 2px solid #E5E7EB !important;
            border-radius: 12px !important;
        }
        .mecas-cp-code-input:focus {
            border-color: #1F2937 !important;
        }
        .mecas-cp-wrong-number,
        .mecas-cp-resend-timer,
        .mecas-cp-resend-link {
            text-align: center;
            font-size: 14px;
            color: #6B7280;
        }
        .mecas-cp-wrong-number a,
        .mecas-cp-resend-link a {
            color: #1F2937;
            text-decoration: underline;
        }
        /* Success */
        .mecas-cp-success {
            text-align: center;
            padding: 30px 0;
        }
        .mecas-cp-success-icon {
            margin-bottom: 20px;
        }
        .mecas-cp-success-message {
            font-size: 18px;
            color: #1F2937;
        }
        /* Loading */
        .mecas-cp-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        .mecas-cp-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #E5E7EB;
            border-top-color: #1F2937;
            border-radius: 50%;
            animation: mecas-cp-spin 0.8s linear infinite;
        }
        @keyframes mecas-cp-spin {
            to { transform: rotate(360deg); }
        }
        .mecas-cp-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
            padding: 12px 16px;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 14px;
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            var $wrapper = $('.mecas-complete-profile-wrapper');
            if (!$wrapper.length) return;
            
            var verificationEnabled = $wrapper.data('verification') === true;
            var resendCountdown = 0;
            var resendTimer = null;
            
            // Form submission
            $('#mecas-complete-profile-form').on('submit', function(e) {
                e.preventDefault();
                
                var phone = $('#mecas_cp_phone').val().trim();
                var phoneCountry = $('#mecas_cp_phone_country').val();
                var location = $('#mecas_cp_location').val().trim();
                
                // Validation
                if ($('#mecas_cp_phone').prop('required') && !phone) {
                    alert('<?php _e('Please enter your phone number', 'mec-starter-addons'); ?>');
                    return;
                }
                if ($('#mecas_cp_location').prop('required') && !location) {
                    alert('<?php _e('Please enter your location', 'mec-starter-addons'); ?>');
                    return;
                }
                
                // If verification is enabled and phone provided, send code
                if (verificationEnabled && phone) {
                    sendVerificationCode(phoneCountry, phone);
                } else {
                    // Save directly without verification
                    saveProfile();
                }
            });
            
            // Detect location
            $('.mecas-cp-detect-location').on('click', function() {
                var $btn = $(this);
                var $input = $('#mecas_cp_location');
                
                if (!navigator.geolocation) return;
                
                $btn.addClass('mecas-cp-detecting');
                
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        $.ajax({
                            url: 'https://nominatim.openstreetmap.org/reverse',
                            data: { format: 'json', lat: position.coords.latitude, lon: position.coords.longitude },
                            success: function(data) {
                                $btn.removeClass('mecas-cp-detecting');
                                if (data && data.address) {
                                    var city = data.address.city || data.address.town || data.address.village || data.address.county || '';
                                    var state = data.address.state || '';
                                    var location = city && state ? city + ', ' + state : (city || state);
                                    if (location) $input.val(location);
                                }
                            },
                            error: function() { $btn.removeClass('mecas-cp-detecting'); }
                        });
                    },
                    function() { $btn.removeClass('mecas-cp-detecting'); },
                    { enableHighAccuracy: false, timeout: 10000, maximumAge: 300000 }
                );
            });
            
            // Code input handling
            $wrapper.on('input', '.mecas-cp-code-input', function() {
                var $input = $(this);
                var val = $input.val().replace(/[^0-9]/g, '');
                $input.val(val);
                
                if (val.length === 1) {
                    var index = parseInt($input.data('index'));
                    var $next = $wrapper.find('.mecas-cp-code-input[data-index="' + (index + 1) + '"]');
                    if ($next.length) $next.focus();
                }
            });
            
            $wrapper.on('keydown', '.mecas-cp-code-input', function(e) {
                if (e.key === 'Backspace' && !$(this).val()) {
                    var index = parseInt($(this).data('index'));
                    var $prev = $wrapper.find('.mecas-cp-code-input[data-index="' + (index - 1) + '"]');
                    if ($prev.length) $prev.focus();
                }
            });
            
            // Verify button
            $wrapper.on('click', '.mecas-cp-btn-verify', function() {
                verifyCode();
            });
            
            // Change phone
            $wrapper.on('click', '.mecas-cp-change-phone', function(e) {
                e.preventDefault();
                goToStep(1);
            });
            
            // Resend code
            $wrapper.on('click', '.mecas-cp-resend-code', function(e) {
                e.preventDefault();
                if (resendCountdown <= 0) {
                    var phone = $('#mecas_cp_phone').val().trim();
                    var phoneCountry = $('#mecas_cp_phone_country').val();
                    sendVerificationCode(phoneCountry, phone);
                }
            });
            
            function goToStep(step) {
                $wrapper.find('.mecas-cp-step').removeClass('mecas-cp-active').hide();
                $wrapper.find('.mecas-cp-step-' + step).addClass('mecas-cp-active').show();
            }
            
            function showLoading(show) {
                $wrapper.find('.mecas-cp-loading').toggle(show);
            }
            
            function sendVerificationCode(phoneCountry, phone) {
                showLoading(true);
                
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'mecas_send_sms_code',
                        nonce: '<?php echo wp_create_nonce('mecas_nonce'); ?>',
                        phone_country: phoneCountry,
                        phone: phone
                    },
                    success: function(response) {
                        showLoading(false);
                        
                        if (response.success) {
                            $('#mecas-cp-display-phone').text(response.data.phone);
                            goToStep(2);
                            startResendCountdown();
                            $wrapper.find('.mecas-cp-code-input').val('').first().focus();
                        } else {
                            var errorMsg = response.data || '';
                            if (errorMsg.indexOf('not enabled') !== -1) {
                                // Twilio not configured, save directly
                                saveProfile();
                            } else {
                                alert(errorMsg || '<?php _e('Failed to send verification code', 'mec-starter-addons'); ?>');
                            }
                        }
                    },
                    error: function() {
                        showLoading(false);
                        alert('<?php _e('An error occurred. Please try again.', 'mec-starter-addons'); ?>');
                    }
                });
            }
            
            function verifyCode() {
                var code = '';
                $wrapper.find('.mecas-cp-code-input').each(function() {
                    code += $(this).val();
                });
                
                if (code.length !== 4) {
                    alert('<?php _e('Please enter the 4-digit verification code', 'mec-starter-addons'); ?>');
                    return;
                }
                
                showLoading(true);
                
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'mecas_verify_sms_code',
                        nonce: '<?php echo wp_create_nonce('mecas_nonce'); ?>',
                        phone_country: $('#mecas_cp_phone_country').val(),
                        phone: $('#mecas_cp_phone').val(),
                        code: code
                    },
                    success: function(response) {
                        if (response.success) {
                            saveProfile();
                        } else {
                            showLoading(false);
                            alert(response.data || '<?php _e('Invalid verification code', 'mec-starter-addons'); ?>');
                        }
                    },
                    error: function() {
                        showLoading(false);
                        alert('<?php _e('An error occurred. Please try again.', 'mec-starter-addons'); ?>');
                    }
                });
            }
            
            function saveProfile() {
                showLoading(true);
                
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'mecas_complete_profile',
                        nonce: '<?php echo wp_create_nonce('mecas_nonce'); ?>',
                        phone_country: $('#mecas_cp_phone_country').val(),
                        phone: $('#mecas_cp_phone').val(),
                        location: $('#mecas_cp_location').val()
                    },
                    success: function(response) {
                        showLoading(false);
                        
                        if (response.success) {
                            goToStep(3);
                            // Optionally close popup or redirect
                            if (response.data && response.data.redirect) {
                                setTimeout(function() {
                                    window.location.href = response.data.redirect;
                                }, 1500);
                            }
                        } else {
                            alert(response.data || '<?php _e('Failed to save profile', 'mec-starter-addons'); ?>');
                        }
                    },
                    error: function() {
                        showLoading(false);
                        alert('<?php _e('An error occurred. Please try again.', 'mec-starter-addons'); ?>');
                    }
                });
            }
            
            function startResendCountdown() {
                resendCountdown = 60;
                $wrapper.find('.mecas-cp-resend-timer').show();
                $wrapper.find('.mecas-cp-resend-link').hide();
                
                if (resendTimer) clearInterval(resendTimer);
                
                updateResendDisplay();
                
                resendTimer = setInterval(function() {
                    resendCountdown--;
                    updateResendDisplay();
                    
                    if (resendCountdown <= 0) {
                        clearInterval(resendTimer);
                        $wrapper.find('.mecas-cp-resend-timer').hide();
                        $wrapper.find('.mecas-cp-resend-link').show();
                    }
                }, 1000);
            }
            
            function updateResendDisplay() {
                $('#mecas-cp-resend-countdown').text(resendCountdown);
            }
        });
        </script>
        <?php
    }
}
