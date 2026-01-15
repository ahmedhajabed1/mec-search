<?php
/**
 * Login Widget
 * User login form with social login support
 */

if (!defined('ABSPATH')) exit;

class MECAS_Login_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_login';
    }
    
    public function get_title() {
        return __('Login Form', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-lock-user';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['login', 'signin', 'sign in', 'auth', 'user', 'account', 'form'];
    }
    
    protected function register_controls() {
        
        // ===== CONTENT SECTION - GENERAL =====
        $this->start_controls_section(
            'section_general',
            [
                'label' => __('General', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_logo',
            [
                'label' => __('Show Logo', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-starter-addons'),
                'label_off' => __('No', 'mec-starter-addons'),
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'logo',
            [
                'label' => __('Logo', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
                'condition' => [
                    'show_logo' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'logo_width',
            [
                'label' => __('Logo Width', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 50, 'max' => 400]],
                'default' => ['size' => 150, 'unit' => 'px'],
                'condition' => [
                    'show_logo' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-logo img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'title',
            [
                'label' => __('Title', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Welcome Back', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Sign in to your account', 'mec-starter-addons'),
                'rows' => 2,
            ]
        );
        
        $this->add_control(
            'redirect_url',
            [
                'label' => __('Redirect After Login', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('Leave empty for current page', 'mec-starter-addons'),
                'default' => ['url' => ''],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== CONTENT SECTION - FORM FIELDS =====
        $this->start_controls_section(
            'section_fields',
            [
                'label' => __('Form Fields', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'email_label',
            [
                'label' => __('Email Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Email Address', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'email_placeholder',
            [
                'label' => __('Email Placeholder', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Enter your email', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'password_label',
            [
                'label' => __('Password Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Password', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'password_placeholder',
            [
                'label' => __('Password Placeholder', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Enter your password', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'show_remember_me',
            [
                'label' => __('Show Remember Me', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-starter-addons'),
                'label_off' => __('No', 'mec-starter-addons'),
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'remember_me_text',
            [
                'label' => __('Remember Me Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Remember me', 'mec-starter-addons'),
                'condition' => [
                    'show_remember_me' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'show_forgot_password',
            [
                'label' => __('Show Forgot Password', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-starter-addons'),
                'label_off' => __('No', 'mec-starter-addons'),
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'forgot_password_text',
            [
                'label' => __('Forgot Password Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Forgot password?', 'mec-starter-addons'),
                'condition' => [
                    'show_forgot_password' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'forgot_password_url',
            [
                'label' => __('Forgot Password URL', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::URL,
                'default' => ['url' => ''],
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'show_forgot_password' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'submit_text',
            [
                'label' => __('Submit Button Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Sign In', 'mec-starter-addons'),
            ]
        );
        
        $this->end_controls_section();
        
        // ===== CONTENT SECTION - SOCIAL LOGIN =====
        $this->start_controls_section(
            'section_social',
            [
                'label' => __('Social Login', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_social_login',
            [
                'label' => __('Show Social Login', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-starter-addons'),
                'label_off' => __('No', 'mec-starter-addons'),
                'default' => 'yes',
                'description' => __('Requires Nextend Social Login plugin', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'social_divider_text',
            [
                'label' => __('Divider Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Or continue with', 'mec-starter-addons'),
                'condition' => [
                    'show_social_login' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'social_position',
            [
                'label' => __('Social Buttons Position', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'bottom',
                'options' => [
                    'top' => __('Above Form', 'mec-starter-addons'),
                    'bottom' => __('Below Form', 'mec-starter-addons'),
                ],
                'condition' => [
                    'show_social_login' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== CONTENT SECTION - SIGNUP LINK =====
        $this->start_controls_section(
            'section_signup_link',
            [
                'label' => __('Sign Up Link', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_signup_link',
            [
                'label' => __('Show Sign Up Link', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-starter-addons'),
                'label_off' => __('No', 'mec-starter-addons'),
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'signup_text',
            [
                'label' => __('Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __("Don't have an account?", 'mec-starter-addons'),
                'condition' => [
                    'show_signup_link' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'signup_link_text',
            [
                'label' => __('Link Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Sign up', 'mec-starter-addons'),
                'condition' => [
                    'show_signup_link' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'signup_url',
            [
                'label' => __('Sign Up URL', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::URL,
                'default' => ['url' => ''],
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'show_signup_link' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - CONTAINER =====
        $this->start_controls_section(
            'section_style_container',
            [
                'label' => __('Container', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'container_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-form' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => ['top' => 40, 'right' => 40, 'bottom' => 40, 'left' => 40, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => '{{WRAPPER}} .mecas-login-form',
            ]
        );
        
        $this->add_responsive_control(
            'container_max_width',
            [
                'label' => __('Max Width', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 300, 'max' => 800],
                    '%' => ['min' => 50, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-form' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - TITLE =====
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Title & Subtitle', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'heading_title_style',
            [
                'label' => __('Title', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mecas-login-title',
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'title_margin',
            [
                'label' => __('Margin Bottom', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'heading_subtitle_style',
            [
                'label' => __('Subtitle', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .mecas-login-subtitle',
            ]
        );
        
        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#6B7280',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - FORM FIELDS =====
        $this->start_controls_section(
            'section_style_fields',
            [
                'label' => __('Form Fields', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'heading_label_style',
            [
                'label' => __('Labels', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .mecas-login-label',
            ]
        );
        
        $this->add_control(
            'label_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-label' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'heading_input_style',
            [
                'label' => __('Input Fields', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} .mecas-login-input',
            ]
        );
        
        $this->add_control(
            'input_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-input' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'input_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F9FAFB',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-input' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'input_border_color',
            [
                'label' => __('Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E5E7EB',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-input' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'input_focus_border_color',
            [
                'label' => __('Focus Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3B82F6',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-input:focus' => 'border-color: {{VALUE}}; outline: none;',
                ],
            ]
        );
        
        $this->add_control(
            'input_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 8, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-input' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'input_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => 12, 'right' => 16, 'bottom' => 12, 'left' => 16, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - SUBMIT BUTTON =====
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Submit Button', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .mecas-login-submit',
            ]
        );
        
        $this->start_controls_tabs('button_tabs');
        
        $this->start_controls_tab('button_normal', ['label' => __('Normal', 'mec-starter-addons')]);
        
        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-submit' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3B82F6',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('button_hover', ['label' => __('Hover', 'mec-starter-addons')]);
        
        $this->add_control(
            'button_text_color_hover',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-submit:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2563EB',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-submit:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 8, 'unit' => 'px'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-submit' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => 14, 'right' => 24, 'bottom' => 14, 'left' => 24, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - LINKS =====
        $this->start_controls_section(
            'section_style_links',
            [
                'label' => __('Links', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'link_color',
            [
                'label' => __('Link Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3B82F6',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-link' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'link_hover_color',
            [
                'label' => __('Link Hover Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2563EB',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'signup_text_color',
            [
                'label' => __('Sign Up Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#6B7280',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-signup-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - SOCIAL BUTTONS =====
        $this->start_controls_section(
            'section_style_social',
            [
                'label' => __('Social Buttons', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'social_btn_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-social-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'social_btn_border_color',
            [
                'label' => __('Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E5E7EB',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-social-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'social_btn_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 8, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-social-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'social_divider_color',
            [
                'label' => __('Divider Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E5E7EB',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-divider::before, {{WRAPPER}} .mecas-login-divider::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'social_divider_text_color',
            [
                'label' => __('Divider Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#9CA3AF',
                'selectors' => [
                    '{{WRAPPER}} .mecas-login-divider span' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Check if user is already logged in
        if (is_user_logged_in() && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $current_user = wp_get_current_user();
            echo '<div class="mecas-login-logged-in">';
            echo '<p>' . sprintf(__('You are already logged in as %s.', 'mec-starter-addons'), '<strong>' . esc_html($current_user->display_name) . '</strong>') . '</p>';
            echo '<a href="' . esc_url(wp_logout_url(home_url())) . '" class="mecas-login-logout-btn">' . __('Logout', 'mec-starter-addons') . '</a>';
            echo '</div>';
            return;
        }
        
        // Get settings
        $redirect_url = !empty($settings['redirect_url']['url']) ? $settings['redirect_url']['url'] : '';
        $forgot_url = !empty($settings['forgot_password_url']['url']) ? $settings['forgot_password_url']['url'] : wp_lostpassword_url();
        $signup_url = !empty($settings['signup_url']['url']) ? $settings['signup_url']['url'] : wp_registration_url();
        
        // Check for Nextend Social Login
        $has_social_login = class_exists('NextendSocialLogin') || function_exists('nsl_render_login_buttons');
        
        ?>
        <div class="mecas-login-form">
            <?php if ($settings['show_logo'] === 'yes' && !empty($settings['logo']['url'])): ?>
            <div class="mecas-login-logo">
                <img src="<?php echo esc_url($settings['logo']['url']); ?>" alt="Logo">
            </div>
            <?php endif; ?>
            
            <?php if (!empty($settings['title'])): ?>
            <h2 class="mecas-login-title"><?php echo esc_html($settings['title']); ?></h2>
            <?php endif; ?>
            
            <?php if (!empty($settings['subtitle'])): ?>
            <p class="mecas-login-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
            <?php endif; ?>
            
            <?php 
            // Social login at top
            if ($settings['show_social_login'] === 'yes' && $settings['social_position'] === 'top' && $has_social_login): 
                $this->render_social_login($settings);
            endif; 
            ?>
            
            <form class="mecas-login-form-inner" method="post">
                <?php wp_nonce_field('mecas_login_nonce', 'mecas_login_nonce'); ?>
                <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_url); ?>">
                
                <div class="mecas-login-field">
                    <label class="mecas-login-label" for="mecas-login-email"><?php echo esc_html($settings['email_label']); ?></label>
                    <input type="email" 
                           id="mecas-login-email" 
                           name="user_email" 
                           class="mecas-login-input" 
                           placeholder="<?php echo esc_attr($settings['email_placeholder']); ?>"
                           required>
                </div>
                
                <div class="mecas-login-field">
                    <label class="mecas-login-label" for="mecas-login-password"><?php echo esc_html($settings['password_label']); ?></label>
                    <input type="password" 
                           id="mecas-login-password" 
                           name="user_password" 
                           class="mecas-login-input" 
                           placeholder="<?php echo esc_attr($settings['password_placeholder']); ?>"
                           required>
                </div>
                
                <div class="mecas-login-options">
                    <?php if ($settings['show_remember_me'] === 'yes'): ?>
                    <label class="mecas-login-remember">
                        <input type="checkbox" name="remember_me" value="1">
                        <span><?php echo esc_html($settings['remember_me_text']); ?></span>
                    </label>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_forgot_password'] === 'yes'): ?>
                    <a href="<?php echo esc_url($forgot_url); ?>" class="mecas-login-link mecas-login-forgot">
                        <?php echo esc_html($settings['forgot_password_text']); ?>
                    </a>
                    <?php endif; ?>
                </div>
                
                <div class="mecas-login-error" style="display: none;"></div>
                
                <button type="submit" class="mecas-login-submit">
                    <span class="mecas-login-submit-text"><?php echo esc_html($settings['submit_text']); ?></span>
                    <span class="mecas-login-submit-loading" style="display: none;">
                        <svg class="mecas-spinner" width="20" height="20" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="32" stroke-linecap="round">
                                <animate attributeName="stroke-dashoffset" values="0;64" dur="1s" repeatCount="indefinite"/>
                            </circle>
                        </svg>
                    </span>
                </button>
            </form>
            
            <?php 
            // Social login at bottom
            if ($settings['show_social_login'] === 'yes' && $settings['social_position'] === 'bottom' && $has_social_login): 
                $this->render_social_login($settings);
            endif; 
            ?>
            
            <?php if ($settings['show_signup_link'] === 'yes'): ?>
            <div class="mecas-login-signup">
                <span class="mecas-login-signup-text"><?php echo esc_html($settings['signup_text']); ?></span>
                <a href="<?php echo esc_url($signup_url); ?>" class="mecas-login-link"><?php echo esc_html($settings['signup_link_text']); ?></a>
            </div>
            <?php endif; ?>
        </div>
        
        <style>
        .mecas-login-form {
            background: #ffffff;
            padding: 40px;
            max-width: 450px;
            margin: 0 auto;
        }
        
        .mecas-login-logo {
            text-align: center;
            margin-bottom: 24px;
        }
        
        .mecas-login-logo img {
            max-width: 150px;
            height: auto;
        }
        
        .mecas-login-title {
            font-size: 28px;
            font-weight: 700;
            color: #1F2937;
            text-align: center;
            margin: 0 0 8px 0;
        }
        
        .mecas-login-subtitle {
            font-size: 14px;
            color: #6B7280;
            text-align: center;
            margin: 0 0 32px 0;
        }
        
        .mecas-login-form-inner {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .mecas-login-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        
        .mecas-login-label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }
        
        .mecas-login-input {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            color: #1F2937;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }
        
        .mecas-login-input:focus {
            outline: none;
            border-color: #3B82F6;
            background: #ffffff;
        }
        
        .mecas-login-input::placeholder {
            color: #9CA3AF;
        }
        
        .mecas-login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .mecas-login-remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #6B7280;
            cursor: pointer;
        }
        
        .mecas-login-remember input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        
        .mecas-login-link {
            font-size: 14px;
            color: #3B82F6;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        .mecas-login-link:hover {
            color: #2563EB;
        }
        
        .mecas-login-error {
            background: #FEE2E2;
            color: #DC2626;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .mecas-login-submit {
            width: 100%;
            padding: 14px 24px;
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            background: #3B82F6;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .mecas-login-submit:hover {
            background: #2563EB;
        }
        
        .mecas-login-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .mecas-spinner {
            animation: mecas-rotate 1s linear infinite;
        }
        
        @keyframes mecas-rotate {
            100% { transform: rotate(360deg); }
        }
        
        /* Divider */
        .mecas-login-divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 24px 0;
        }
        
        .mecas-login-divider::before,
        .mecas-login-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #E5E7EB;
        }
        
        .mecas-login-divider span {
            font-size: 13px;
            color: #9CA3AF;
            white-space: nowrap;
        }
        
        /* Social buttons */
        .mecas-login-social-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .mecas-login-social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            background: #ffffff;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .mecas-login-social-btn:hover {
            background: #F9FAFB;
            border-color: #D1D5DB;
        }
        
        .mecas-login-social-btn svg {
            width: 20px;
            height: 20px;
        }
        
        /* Sign up link */
        .mecas-login-signup {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
        }
        
        .mecas-login-signup-text {
            color: #6B7280;
            margin-right: 4px;
        }
        
        /* Logged in state */
        .mecas-login-logged-in {
            text-align: center;
            padding: 40px;
        }
        
        .mecas-login-logged-in p {
            margin-bottom: 20px;
            color: #374151;
        }
        
        .mecas-login-logout-btn {
            display: inline-block;
            padding: 10px 24px;
            background: #EF4444;
            color: #ffffff;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s ease;
        }
        
        .mecas-login-logout-btn:hover {
            background: #DC2626;
        }
        </style>
        
        <script>
        (function() {
            const form = document.querySelector('.mecas-login-form-inner');
            if (!form) return;
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const submitBtn = form.querySelector('.mecas-login-submit');
                const submitText = submitBtn.querySelector('.mecas-login-submit-text');
                const submitLoading = submitBtn.querySelector('.mecas-login-submit-loading');
                const errorDiv = form.querySelector('.mecas-login-error');
                
                // Get form data
                const email = form.querySelector('#mecas-login-email').value;
                const password = form.querySelector('#mecas-login-password').value;
                const rememberMe = form.querySelector('input[name="remember_me"]')?.checked || false;
                const redirectTo = form.querySelector('input[name="redirect_to"]').value;
                const nonce = form.querySelector('#mecas_login_nonce').value;
                
                // Show loading
                submitBtn.disabled = true;
                submitText.style.display = 'none';
                submitLoading.style.display = 'inline-flex';
                errorDiv.style.display = 'none';
                
                try {
                    const response = await fetch(mecas_ajax.ajax_url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'mecas_user_login',
                            nonce: nonce,
                            email: email,
                            password: password,
                            remember: rememberMe ? '1' : '0',
                            redirect_to: redirectTo
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Redirect on success
                        window.location.href = data.data.redirect_url || window.location.href;
                    } else {
                        // Show error
                        errorDiv.textContent = data.data.message || 'Login failed. Please try again.';
                        errorDiv.style.display = 'block';
                        
                        // Reset button
                        submitBtn.disabled = false;
                        submitText.style.display = 'inline';
                        submitLoading.style.display = 'none';
                    }
                } catch (error) {
                    errorDiv.textContent = 'An error occurred. Please try again.';
                    errorDiv.style.display = 'block';
                    
                    submitBtn.disabled = false;
                    submitText.style.display = 'inline';
                    submitLoading.style.display = 'none';
                }
            });
        })();
        </script>
        <?php
    }
    
    private function render_social_login($settings) {
        ?>
        <div class="mecas-login-divider">
            <span><?php echo esc_html($settings['social_divider_text']); ?></span>
        </div>
        
        <div class="mecas-login-social-buttons">
            <?php 
            // Try to render Nextend Social Login buttons
            if (function_exists('nsl_render_login_buttons')) {
                echo do_shortcode('[nextend_social_login]');
            } else {
                // Fallback - show placeholder buttons
                ?>
                <a href="#" class="mecas-login-social-btn mecas-login-google">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Continue with Google
                </a>
                <?php
            }
            ?>
        </div>
        <?php
    }
}
