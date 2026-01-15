<?php
/**
 * Customer Registration Form Widget
 * Multi-step registration with social login, phone verification, and welcome email
 */

if (!defined('ABSPATH')) exit;

class MECAS_Registration_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_registration';
    }
    
    public function get_title() {
        return __('Customer Registration', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-form-horizontal';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['registration', 'signup', 'customer', 'user', 'form'];
    }
    
    protected function register_controls() {
        
        // Content Section - Social Login
        $this->start_controls_section(
            'section_social_login',
            [
                'label' => __('Social Login', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_social_login',
            [
                'label' => __('Show Social Login Buttons', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Requires Nextend Social Login plugin', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'show_google',
            [
                'label' => __('Show Google', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_social_login' => 'yes'],
            ]
        );
        
        $this->add_control(
            'show_apple',
            [
                'label' => __('Show Apple', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_social_login' => 'yes'],
            ]
        );
        
        $this->add_control(
            'show_facebook',
            [
                'label' => __('Show Facebook', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_social_login' => 'yes'],
            ]
        );
        
        $this->end_controls_section();
        
        // Content Section - Steps Configuration
        $this->start_controls_section(
            'section_steps',
            [
                'label' => __('Steps Configuration', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'step2_title',
            [
                'label' => __('Step 2 Title', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Finish Signing Up', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'step3_title',
            [
                'label' => __('Step 3 Title', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Verify your phone number', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'thank_you_title',
            [
                'label' => __('Thank You Title', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Thank you!', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'thank_you_subtitle',
            [
                'label' => __('Thank You Subtitle', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Thanks for signing up!', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'thank_you_message',
            [
                'label' => __('Thank You Message', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Welcome to The Mahj Hub', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'redirect_after_signup',
            [
                'label' => __('Redirect After Signup', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'dashboard',
                'options' => [
                    'dashboard' => __('User Dashboard', 'mec-starter-addons'),
                    'home' => __('Home Page', 'mec-starter-addons'),
                    'stay' => __('Stay on Page', 'mec-starter-addons'),
                    'custom' => __('Custom URL', 'mec-starter-addons'),
                ],
            ]
        );
        
        $this->add_control(
            'custom_redirect_url',
            [
                'label' => __('Custom Redirect URL', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::URL,
                'condition' => ['redirect_after_signup' => 'custom'],
            ]
        );
        
        $this->end_controls_section();
        
        // Content Section - Labels
        $this->start_controls_section(
            'section_labels',
            [
                'label' => __('Labels & Placeholders', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'email_button_text',
            [
                'label' => __('Email Button Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Continue with Email', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'cancel_button_text',
            [
                'label' => __('Cancel Button Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Cancel', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'continue_button_text',
            [
                'label' => __('Continue Button Text', 'mec-starter-addons'),
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
        
        $this->end_controls_section();
        
        // Content Section - Links
        $this->start_controls_section(
            'section_links',
            [
                'label' => __('Links', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'login_link_url',
            [
                'label' => __('Login Link URL', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::URL,
                'default' => ['url' => ''],
                'description' => __('Leave empty to use default WordPress login URL', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'host_signup_url',
            [
                'label' => __('Host Signup Link URL', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::URL,
                'default' => ['url' => ''],
            ]
        );
        
        $this->add_control(
            'show_host_signup',
            [
                'label' => __('Show Host Signup Link', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->end_controls_section();
        
        // Content Section - Logo
        $this->start_controls_section(
            'section_logo',
            [
                'label' => __('Logo', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_logo',
            [
                'label' => __('Show Logo', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'logo_image',
            [
                'label' => __('Logo Image', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'condition' => ['show_logo' => 'yes'],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Social Buttons
        $this->start_controls_section(
            'section_style_buttons',
            [
                'label' => __('Social Buttons', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'social_button_typography',
                'selector' => '{{WRAPPER}} .mecua-social-btn',
            ]
        );
        
        $this->add_control(
            'social_button_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .mecua-social-btn' => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'social_button_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .mecua-social-btn' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'social_button_border_color',
            [
                'label' => __('Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E5E7EB',
                'selectors' => [
                    '{{WRAPPER}} .mecua-social-btn' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'social_button_border_width',
            [
                'label' => __('Border Width', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 5]],
                'default' => ['size' => 1, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-social-btn' => 'border-width: {{SIZE}}{{UNIT}} !important; border-style: solid !important;',
                ],
            ]
        );
        
        $this->add_control(
            'social_button_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 30, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-social-btn' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'social_button_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => 15,
                    'right' => 20,
                    'bottom' => 15,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecua-social-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'social_button_gap',
            [
                'label' => __('Button Spacing', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-social-buttons' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mecua-step-1' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'heading_hover_style',
            [
                'label' => __('Hover State', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'social_button_hover_bg',
            [
                'label' => __('Hover Background', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F9FAFB',
                'selectors' => [
                    '{{WRAPPER}} .mecua-social-btn:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'social_button_hover_border',
            [
                'label' => __('Hover Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#D1D5DB',
                'selectors' => [
                    '{{WRAPPER}} .mecua-social-btn:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'social_button_hover_text',
            [
                'label' => __('Hover Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-social-btn:hover' => 'color: {{VALUE}} !important;',
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
                'name' => 'form_label_typography',
                'label' => __('Label Typography', 'mec-starter-addons'),
                'selector' => '{{WRAPPER}} .mecua-form-group label',
            ]
        );
        
        $this->add_control(
            'form_label_color',
            [
                'label' => __('Label Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-form-group label' => 'color: {{VALUE}};',
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
                'default' => ['size' => 8, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-form-group input, {{WRAPPER}} .mecua-form-group select' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Primary Button
        $this->start_controls_section(
            'section_style_primary_button',
            [
                'label' => __('Primary Button', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'primary_button_bg',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecua-btn-primary' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'primary_button_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecua-btn-primary' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'primary_button_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 30, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-btn-primary' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Check if we're in Elementor editor/preview mode
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode();
        
        // Check if user is already logged in (but allow in editor mode)
        if (is_user_logged_in() && !$is_editor) {
            echo '<div class="mecua-already-logged-in">';
            echo '<p>' . __('You are already logged in.', 'mec-starter-addons') . '</p>';
            $dashboard_page = get_option('mecas_dashboard_page', '');
            if ($dashboard_page) {
                echo '<a href="' . esc_url(get_permalink($dashboard_page)) . '" class="mecua-btn mecua-btn-primary">' . __('Go to Dashboard', 'mec-starter-addons') . '</a>';
            }
            echo '</div>';
            return;
        }
        
        $show_social = $settings['show_social_login'] === 'yes';
        $login_url = !empty($settings['login_link_url']['url']) ? $settings['login_link_url']['url'] : wp_login_url();
        $host_signup_url = !empty($settings['host_signup_url']['url']) ? $settings['host_signup_url']['url'] : '';
        
        // Check for social login data in session/transient
        $social_data = $this->get_social_login_data();
        ?>
        <div class="mecua-registration-wrapper" data-social-prefill="<?php echo $social_data ? 'true' : 'false'; ?>">
            
            <!-- Step 1: Choose Signup Method -->
            <div class="mecua-step mecua-step-1 <?php echo $social_data ? '' : 'mecua-active'; ?>" data-step="1" <?php echo $social_data ? 'style="display:none;"' : ''; ?>>
                
                <?php if ($show_social): ?>
                <div class="mecua-social-buttons">
                    <?php if ($settings['show_google'] === 'yes'): ?>
                    <button type="button" class="mecua-social-btn mecua-social-google" data-provider="google">
                        <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                        <?php _e('Continue with Google', 'mec-starter-addons'); ?>
                    </button>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_apple'] === 'yes'): ?>
                    <button type="button" class="mecua-social-btn mecua-social-apple" data-provider="apple">
                        <svg width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/></svg>
                        <?php _e('Continue with Apple', 'mec-starter-addons'); ?>
                    </button>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_facebook'] === 'yes'): ?>
                    <button type="button" class="mecua-social-btn mecua-social-facebook" data-provider="facebook">
                        <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        <?php _e('Continue with Facebook', 'mec-starter-addons'); ?>
                    </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <button type="button" class="mecua-social-btn mecua-social-email mecua-btn-email">
                    <svg width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    <?php echo esc_html($settings['email_button_text']); ?>
                </button>
                
                <p class="mecua-login-link">
                    <?php _e('Already have an account?', 'mec-starter-addons'); ?> 
                    <a href="<?php echo esc_url($login_url); ?>"><?php _e('Log In', 'mec-starter-addons'); ?></a>
                </p>
                
                <?php if ($settings['show_host_signup'] === 'yes' && !empty($host_signup_url)): ?>
                <p class="mecua-host-signup-link">
                    <?php _e('Want to sign up as a host?', 'mec-starter-addons'); ?> 
                    <a href="<?php echo esc_url($host_signup_url); ?>"><?php _e('Host Sign Up', 'mec-starter-addons'); ?></a>
                </p>
                <?php endif; ?>
            </div>
            
            <!-- Step 2: Finish Signing Up Form -->
            <div class="mecua-step mecua-step-2 <?php echo $social_data ? 'mecua-active' : ''; ?>" data-step="2" <?php echo $social_data ? '' : 'style="display:none;"'; ?>>
                
                <?php if ($settings['show_logo'] === 'yes' && !empty($settings['logo_image']['url'])): ?>
                <div class="mecua-logo">
                    <img src="<?php echo esc_url($settings['logo_image']['url']); ?>" alt="Logo">
                </div>
                <?php endif; ?>
                
                <h2 class="mecua-step-title"><?php echo esc_html($settings['step2_title']); ?></h2>
                
                <p class="mecua-required-note">*<?php _e('required', 'mec-starter-addons'); ?></p>
                
                <form id="mecua-registration-form" class="mecua-form">
                    <?php wp_nonce_field('mecas_nonce', 'mecas_nonce'); ?>
                    <input type="hidden" name="social_provider" id="mecas_social_provider" value="<?php echo $social_data ? esc_attr($social_data['provider']) : ''; ?>">
                    
                    <div class="mecua-form-group">
                        <label for="mecas_name"><?php _e('Your name', 'mec-starter-addons'); ?>*</label>
                        <input type="text" id="mecas_name" name="name" placeholder="<?php esc_attr_e('Jane Doe', 'mec-starter-addons'); ?>" required value="<?php echo $social_data ? esc_attr($social_data['name']) : ''; ?>">
                        <p class="mecua-field-note"><?php _e('Your name will be public on your Mahj Hub profile', 'mec-starter-addons'); ?></p>
                    </div>
                    
                    <div class="mecua-form-group">
                        <label for="mecas_email"><?php _e('Email address', 'mec-starter-addons'); ?>*</label>
                        <input type="email" id="mecas_email" name="email" placeholder="<?php esc_attr_e('example@email.com', 'mec-starter-addons'); ?>" required value="<?php echo $social_data ? esc_attr($social_data['email']) : ''; ?>" <?php echo $social_data && !empty($social_data['email']) ? 'readonly' : ''; ?>>
                        <p class="mecua-field-note"><?php _e("We'll use your email address to send you updates", 'mec-starter-addons'); ?></p>
                    </div>
                    
                    <div class="mecua-form-group">
                        <label for="mecas_phone"><?php _e('Phone number', 'mec-starter-addons'); ?>*</label>
                        <div class="mecua-phone-wrapper">
                            <select id="mecas_phone_country" name="phone_country" class="mecua-phone-country">
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
                            <input type="tel" id="mecas_phone" name="phone" placeholder="<?php esc_attr_e('740 123 1234', 'mec-starter-addons'); ?>" required>
                        </div>
                        <p class="mecua-field-note"><?php _e("We'll use your phone number to send you updates and verify your account", 'mec-starter-addons'); ?></p>
                    </div>
                    
                    <div class="mecua-form-group mecua-location-group">
                        <label for="mecas_location"><?php _e('Location', 'mec-starter-addons'); ?>*</label>
                        <div class="mecua-location-wrapper">
                            <input type="text" id="mecas_location" name="location" placeholder="<?php esc_attr_e('City, State', 'mec-starter-addons'); ?>" required>
                            <button type="button" class="mecua-detect-location" title="<?php esc_attr_e('Detect my location', 'mec-starter-addons'); ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3c-.46-4.17-3.77-7.48-7.94-7.94V1h-2v2.06C6.83 3.52 3.52 6.83 3.06 11H1v2h2.06c.46 4.17 3.77 7.48 7.94 7.94V23h2v-2.06c4.17-.46 7.48-3.77 7.94-7.94H23v-2h-2.06zM12 19c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mecua-form-group">
                        <label for="mecas_password"><?php _e('Password', 'mec-starter-addons'); ?>*</label>
                        <div class="mecua-password-wrapper">
                            <input type="password" id="mecas_password" name="password" placeholder="<?php esc_attr_e('At least 8 characters', 'mec-starter-addons'); ?>" required minlength="8">
                            <button type="button" class="mecua-toggle-password">
                                <svg class="mecua-eye-open" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                <svg class="mecua-eye-closed" width="20" height="20" viewBox="0 0 24 24" style="display:none;"><path fill="currentColor" d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mecua-form-group mecua-recaptcha-container">
                        <?php 
                        $recaptcha_site_key = get_option('mecas_recaptcha_site_key', '');
                        if (!empty($recaptcha_site_key)): 
                        ?>
                        <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($recaptcha_site_key); ?>"></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mecua-form-buttons">
                        <button type="button" class="mecua-btn mecua-btn-secondary mecua-btn-cancel" data-step="1"><?php echo esc_html($settings['cancel_button_text']); ?></button>
                        <button type="button" class="mecua-btn mecua-btn-primary mecua-btn-continue" data-next="3">
                            <?php echo esc_html($settings['continue_button_text']); ?>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Step 3: Phone Verification (Required) -->
            <div class="mecua-step mecua-step-3" data-step="3" style="display:none;">
                
                <?php if ($settings['show_logo'] === 'yes' && !empty($settings['logo_image']['url'])): ?>
                <div class="mecua-logo">
                    <img src="<?php echo esc_url($settings['logo_image']['url']); ?>" alt="Logo">
                </div>
                <?php endif; ?>
                
                <h2 class="mecua-step-title"><?php echo esc_html($settings['step3_title']); ?></h2>
                
                <p class="mecua-verify-message"><?php _e("We've sent a verification code to your phone number", 'mec-starter-addons'); ?></p>
                <p class="mecua-verify-phone"><span id="mecua-display-phone"></span></p>
                
                <div class="mecua-verification-code">
                    <input type="text" maxlength="1" class="mecua-code-input" data-index="0" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="mecua-code-input" data-index="1" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="mecua-code-input" data-index="2" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="mecua-code-input" data-index="3" inputmode="numeric" pattern="[0-9]">
                </div>
                
                <p class="mecua-wrong-number">
                    <?php _e('Wrong number?', 'mec-starter-addons'); ?> 
                    <a href="#" class="mecua-change-phone"><?php _e('Change', 'mec-starter-addons'); ?></a>
                </p>
                
                <p class="mecua-resend-timer"><?php _e('Resend in', 'mec-starter-addons'); ?> (<span id="mecua-resend-countdown">60</span>)</p>
                <p class="mecua-resend-link" style="display:none;"><a href="#" class="mecua-resend-code"><?php _e('Resend Code', 'mec-starter-addons'); ?></a></p>
                
                <div class="mecua-form-buttons mecua-verify-buttons">
                    <button type="button" class="mecua-btn mecua-btn-primary mecua-btn-verify mecua-btn-full"><?php echo esc_html($settings['verify_button_text']); ?></button>
                </div>
            </div>
            
            <!-- Step 4: Thank You -->
            <div class="mecua-step mecua-step-4" data-step="4" style="display:none;">
                
                <?php if ($settings['show_logo'] === 'yes' && !empty($settings['logo_image']['url'])): ?>
                <div class="mecua-logo">
                    <img src="<?php echo esc_url($settings['logo_image']['url']); ?>" alt="Logo">
                </div>
                <?php endif; ?>
                
                <div class="mecua-thank-you">
                    <h2 class="mecua-thank-you-title"><?php echo esc_html($settings['thank_you_title']); ?></h2>
                    <p class="mecua-thank-you-subtitle"><?php echo esc_html($settings['thank_you_subtitle']); ?></p>
                    <p class="mecua-thank-you-message"><?php echo esc_html($settings['thank_you_message']); ?></p>
                </div>
            </div>
            
            <div class="mecua-form-loading" style="display:none;">
                <div class="mecua-spinner"></div>
            </div>
            
            <div class="mecua-form-error" style="display:none;"></div>
        </div>
        
        <script>
        if (typeof mecas_reg === 'undefined') {
            var mecas_reg = {
                ajax_url: '<?php echo esc_js(admin_url('admin-ajax.php')); ?>',
                nonce: '<?php echo esc_js(wp_create_nonce('mecas_nonce')); ?>',
                twilio_enabled: <?php echo get_option('mecas_twilio_enabled', 'no') === 'yes' || get_option('mecom_twilio_enabled', 'no') === 'yes' ? 'true' : 'false'; ?>,
                show_phone_verification: true,
                geolocation_enabled: <?php echo get_option('mecas_enable_geolocation', 1) ? 'true' : 'false'; ?>,
                social_prefill: <?php echo $social_data ? json_encode($social_data) : 'null'; ?>
            };
        }
        </script>
        <?php
    }
    
    /**
     * Get social login data from session/transient
     */
    private function get_social_login_data() {
        // Check for Nextend Social Login data
        if (isset($_GET['nsl_social_provider']) || isset($_SESSION['nsl_social_data'])) {
            $provider = isset($_GET['nsl_social_provider']) ? sanitize_text_field($_GET['nsl_social_provider']) : '';
            $transient_key = 'nsl_registration_data_' . session_id();
            $data = get_transient($transient_key);
            
            if ($data) {
                return array(
                    'provider' => $provider ?: 'social',
                    'name' => isset($data['name']) ? $data['name'] : '',
                    'email' => isset($data['email']) ? $data['email'] : '',
                );
            }
        }
        
        // Check URL parameters (alternative method)
        if (isset($_GET['social_name']) || isset($_GET['social_email'])) {
            return array(
                'provider' => sanitize_text_field($_GET['social_provider'] ?? 'social'),
                'name' => sanitize_text_field($_GET['social_name'] ?? ''),
                'email' => sanitize_email($_GET['social_email'] ?? ''),
            );
        }
        
        // Check transient with unique key
        if (isset($_COOKIE['mecas_social_key'])) {
            $key = sanitize_text_field($_COOKIE['mecas_social_key']);
            $data = get_transient('mecas_social_' . $key);
            if ($data) {
                delete_transient('mecas_social_' . $key);
                return $data;
            }
        }
        
        return null;
    }
}
