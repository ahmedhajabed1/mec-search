<?php
/**
 * Header Auth Widget
 * Login/Signup buttons with dropdowns and logged-in user menu with avatar
 */

if (!defined('ABSPATH')) exit;

class MECAS_Header_Auth_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_header_auth';
    }
    
    public function get_title() {
        return __('Header Auth', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-lock-user';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['login', 'signup', 'register', 'auth', 'header', 'user', 'account', 'avatar'];
    }
    
    protected function register_controls() {
        
        // ===== CONTENT SECTION - PREVIEW =====
        $this->start_controls_section(
            'section_preview',
            [
                'label' => __('Preview Mode', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'preview_state',
            [
                'label' => __('Preview State', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __('Auto (Current User State)', 'mec-starter-addons'),
                    'logged_out' => __('Logged Out (Login/Signup)', 'mec-starter-addons'),
                    'logged_in' => __('Logged In (Avatar/Menu)', 'mec-starter-addons'),
                ],
                'description' => __('Choose which state to preview while editing', 'mec-starter-addons'),
            ]
        );
        
        $this->end_controls_section();
        
        // ===== CONTENT SECTION - LINKS =====
        $this->start_controls_section(
            'section_links',
            [
                'label' => __('Links', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'login_url',
            [
                'label' => __('Login URL', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-site.com/login', 'mec-starter-addons'),
                'default' => ['url' => ''],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        
        $this->add_control(
            'host_signup_url',
            [
                'label' => __('Host Signup URL', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-site.com/host-signup', 'mec-starter-addons'),
                'default' => ['url' => ''],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        
        $this->add_control(
            'customer_signup_url',
            [
                'label' => __('Customer Signup URL', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-site.com/signup', 'mec-starter-addons'),
                'default' => ['url' => ''],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        
        $this->add_control(
            'account_settings_url',
            [
                'label' => __('Account Settings URL', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-site.com/account', 'mec-starter-addons'),
                'default' => ['url' => ''],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== CONTENT SECTION - LABELS =====
        $this->start_controls_section(
            'section_labels',
            [
                'label' => __('Labels', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'heading_logged_out',
            [
                'label' => __('Logged Out State', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control(
            'login_text',
            [
                'label' => __('Login Button Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Login', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'signup_text',
            [
                'label' => __('Sign-up Button Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Sign-up', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'host_signup_text',
            [
                'label' => __('Host Signup Dropdown Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Sign up as Host', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'customer_signup_text',
            [
                'label' => __('Customer Signup Dropdown Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Sign up as Customer', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'heading_logged_in',
            [
                'label' => __('Logged In State', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'account_settings_text',
            [
                'label' => __('Account Settings Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Account Settings', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'logout_text',
            [
                'label' => __('Logout Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Logout', 'mec-starter-addons'),
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - LAYOUT =====
        $this->start_controls_section(
            'section_style_layout',
            [
                'label' => __('Layout', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'buttons_gap',
            [
                'label' => __('Buttons Gap', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 10, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-header-auth' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - LOGIN BUTTON =====
        $this->start_controls_section(
            'section_style_login',
            [
                'label' => __('Login Button', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'login_typography',
                'selector' => '{{WRAPPER}} .mecas-auth-login',
            ]
        );
        
        $this->start_controls_tabs('login_tabs');
        
        $this->start_controls_tab('login_normal', ['label' => __('Normal', 'mec-starter-addons')]);
        
        $this->add_control(
            'login_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-login' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'login_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-login' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'login_border',
                'selector' => '{{WRAPPER}} .mecas-auth-login',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('login_hover', ['label' => __('Hover', 'mec-starter-addons')]);
        
        $this->add_control(
            'login_text_color_hover',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-login:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'login_bg_color_hover',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-login:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'login_border_color_hover',
            [
                'label' => __('Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-login:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
            'login_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-login' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'login_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-login' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - SIGNUP BUTTON =====
        $this->start_controls_section(
            'section_style_signup',
            [
                'label' => __('Sign-up Button', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'signup_typography',
                'selector' => '{{WRAPPER}} .mecas-auth-signup',
            ]
        );
        
        $this->start_controls_tabs('signup_tabs');
        
        $this->start_controls_tab('signup_normal', ['label' => __('Normal', 'mec-starter-addons')]);
        
        $this->add_control(
            'signup_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-signup' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'signup_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-signup' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'signup_border',
                'selector' => '{{WRAPPER}} .mecas-auth-signup',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('signup_hover', ['label' => __('Hover', 'mec-starter-addons')]);
        
        $this->add_control(
            'signup_text_color_hover',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-signup:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'signup_bg_color_hover',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-signup:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'signup_border_color_hover',
            [
                'label' => __('Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-signup:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
            'signup_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-signup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'signup_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-signup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - USER CONTAINER (Logged In) =====
        $this->start_controls_section(
            'section_style_user_container',
            [
                'label' => __('User Container (Logged In)', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'user_container_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-user-trigger' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'user_container_bg_color_hover',
            [
                'label' => __('Background Hover Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-user-trigger:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'user_container_border',
                'selector' => '{{WRAPPER}} .mecas-auth-user-trigger',
            ]
        );
        
        $this->add_control(
            'user_container_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-user-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'user_container_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-user-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'user_container_gap',
            [
                'label' => __('Gap (Text & Avatar)', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 40]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-user-trigger' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - AVATAR =====
        $this->start_controls_section(
            'section_style_avatar',
            [
                'label' => __('Avatar (Logged In)', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'avatar_size',
            [
                'label' => __('Avatar Size', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 30, 'max' => 100]],
                'default' => ['size' => 50, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mecas-auth-avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'avatar_border',
                'selector' => '{{WRAPPER}} .mecas-auth-avatar',
            ]
        );
        
        $this->add_control(
            'avatar_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                    '%' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 50, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-avatar, {{WRAPPER}} .mecas-auth-avatar img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'avatar_shadow',
                'selector' => '{{WRAPPER}} .mecas-auth-avatar',
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - LOGOUT TEXT =====
        $this->start_controls_section(
            'section_style_logout_text',
            [
                'label' => __('Logout Text (Logged In)', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'logout_text_typography',
                'selector' => '{{WRAPPER}} .mecas-auth-logout-text',
            ]
        );
        
        $this->add_control(
            'logout_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-logout-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'logout_text_hover_color',
            [
                'label' => __('Hover Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-user-trigger:hover .mecas-auth-logout-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - DROPDOWN =====
        $this->start_controls_section(
            'section_style_dropdown',
            [
                'label' => __('Dropdown', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'dropdown_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-dropdown' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_border',
                'selector' => '{{WRAPPER}} .mecas-auth-dropdown',
            ]
        );
        
        $this->add_control(
            'dropdown_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 20]],
                'default' => ['size' => 8, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-dropdown' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_shadow',
                'selector' => '{{WRAPPER}} .mecas-auth-dropdown',
            ]
        );
        
        $this->add_responsive_control(
            'dropdown_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => 8, 'right' => 0, 'bottom' => 8, 'left' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'heading_dropdown_items',
            [
                'label' => __('Dropdown Items', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'dropdown_item_typography',
                'selector' => '{{WRAPPER}} .mecas-auth-dropdown a',
            ]
        );
        
        $this->add_control(
            'dropdown_item_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-dropdown a' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'dropdown_item_hover_bg',
            [
                'label' => __('Hover Background', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F3F4F6',
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-dropdown a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'dropdown_item_hover_color',
            [
                'label' => __('Hover Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-dropdown a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dropdown_item_padding',
            [
                'label' => __('Item Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => 10, 'right' => 20, 'bottom' => 10, 'left' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-auth-dropdown a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Check if in editor mode
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode();
        
        // Determine which state to show
        $preview_state = $settings['preview_state'];
        $show_logged_in = false;
        
        if ($is_editor) {
            // In editor, respect preview setting
            if ($preview_state === 'logged_in') {
                $show_logged_in = true;
            } elseif ($preview_state === 'logged_out') {
                $show_logged_in = false;
            } else {
                // Auto - use actual state
                $show_logged_in = is_user_logged_in();
            }
        } else {
            // On frontend, always use actual state
            $show_logged_in = is_user_logged_in();
        }
        
        // URLs
        $login_url = !empty($settings['login_url']['url']) ? $settings['login_url']['url'] : wp_login_url();
        $host_signup_url = !empty($settings['host_signup_url']['url']) ? $settings['host_signup_url']['url'] : '';
        $customer_signup_url = !empty($settings['customer_signup_url']['url']) ? $settings['customer_signup_url']['url'] : wp_registration_url();
        $account_settings_url = !empty($settings['account_settings_url']['url']) ? $settings['account_settings_url']['url'] : '';
        
        // Labels
        $login_text = $settings['login_text'] ?: __('Login', 'mec-starter-addons');
        $signup_text = $settings['signup_text'] ?: __('Sign-up', 'mec-starter-addons');
        $host_signup_text = $settings['host_signup_text'] ?: __('Sign up as Host', 'mec-starter-addons');
        $customer_signup_text = $settings['customer_signup_text'] ?: __('Sign up as Customer', 'mec-starter-addons');
        $account_settings_text = $settings['account_settings_text'] ?: __('Account Settings', 'mec-starter-addons');
        $logout_text = $settings['logout_text'] ?: __('Logout', 'mec-starter-addons');
        
        // Get user data
        $display_name = '';
        $avatar_url = '';
        
        if ($show_logged_in) {
            if (is_user_logged_in()) {
                $current_user = wp_get_current_user();
                $display_name = $current_user->display_name ?: $current_user->user_login;
                $avatar_url = get_avatar_url($current_user->ID, array('size' => 100));
            } else {
                // Preview mode - use placeholder
                $display_name = __('Jane Doe', 'mec-starter-addons');
                $avatar_url = 'https://i.pravatar.cc/100?img=5';
            }
        }
        
        ?>
        <div class="mecas-header-auth">
            <?php if ($show_logged_in): ?>
            
            <!-- Logged In State -->
            <div class="mecas-auth-user-menu">
                <button type="button" class="mecas-auth-user-trigger">
                    <span class="mecas-auth-logout-text"><?php echo esc_html($logout_text); ?></span>
                    <div class="mecas-auth-avatar">
                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($display_name); ?>">
                    </div>
                </button>
                <div class="mecas-auth-dropdown mecas-auth-user-dropdown">
                    <?php if ($account_settings_url): ?>
                    <a href="<?php echo esc_url($account_settings_url); ?>"><?php echo esc_html($account_settings_text); ?></a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>"><?php echo esc_html($logout_text); ?></a>
                </div>
            </div>
            
            <?php else: ?>
            
            <!-- Logged Out State -->
            <a href="<?php echo esc_url($login_url); ?>" class="mecas-auth-btn mecas-auth-login">
                <?php echo esc_html($login_text); ?>
            </a>
            
            <div class="mecas-auth-signup-menu">
                <button type="button" class="mecas-auth-btn mecas-auth-signup">
                    <span><?php echo esc_html($signup_text); ?></span>
                </button>
                <div class="mecas-auth-dropdown mecas-auth-signup-dropdown">
                    <?php if ($host_signup_url): ?>
                    <a href="<?php echo esc_url($host_signup_url); ?>"><?php echo esc_html($host_signup_text); ?></a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url($customer_signup_url); ?>"><?php echo esc_html($customer_signup_text); ?></a>
                </div>
            </div>
            
            <?php endif; ?>
        </div>
        
        <style>
        .mecas-header-auth {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .mecas-auth-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        .mecas-auth-login {
            background: transparent;
            color: #ffffff;
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 25px;
        }
        
        .mecas-auth-login:hover {
            background: rgba(255,255,255,0.1);
            border-color: #ffffff;
        }
        
        .mecas-auth-signup {
            background: #ffffff;
            color: #1F2937;
            border-radius: 25px;
        }
        
        .mecas-auth-signup:hover {
            background: #F3F4F6;
        }
        
        /* Dropdown containers */
        .mecas-auth-signup-menu,
        .mecas-auth-user-menu {
            position: relative;
            display: inline-flex;
        }
        
        .mecas-auth-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            min-width: 180px;
            background: #ffffff;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 8px 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
            z-index: 1000;
        }
        
        .mecas-auth-signup-menu:hover .mecas-auth-dropdown,
        .mecas-auth-user-menu:hover .mecas-auth-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .mecas-auth-dropdown a {
            display: block;
            padding: 10px 20px;
            color: #374151;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.15s ease;
        }
        
        .mecas-auth-dropdown a:hover {
            background: #F3F4F6;
            color: #1F2937;
        }
        
        /* User trigger (logged in state) */
        .mecas-auth-user-trigger {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            padding: 0;
            transition: all 0.2s ease;
        }
        
        .mecas-auth-user-trigger:hover {
            opacity: 0.9;
        }
        
        .mecas-auth-logout-text {
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
        }
        
        /* Avatar */
        .mecas-auth-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(255,255,255,0.3);
            flex-shrink: 0;
        }
        
        .mecas-auth-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        
        /* Mobile responsive */
        @media (max-width: 767px) {
            .mecas-header-auth {
                gap: 8px;
            }
            
            .mecas-auth-btn {
                padding: 8px 16px;
                font-size: 13px;
            }
            
            .mecas-auth-dropdown {
                min-width: 160px;
            }
            
            .mecas-auth-avatar {
                width: 40px;
                height: 40px;
            }
        }
        </style>
        <?php
    }
}
