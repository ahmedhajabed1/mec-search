<?php
/**
 * User Dashboard Widget
 * AJAX-loaded profile editing with full Elementor customization
 */

if (!defined('ABSPATH')) exit;

class MECAS_User_Dashboard_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_user_dashboard';
    }
    
    public function get_title() {
        return __('User Dashboard', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-user-circle-o';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['user', 'dashboard', 'profile', 'edit', 'account', 'settings'];
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
            'title',
            [
                'label' => __('Title', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Edit Profile', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'show_stats',
            [
                'label' => __('Show Stats (Tickets/Events)', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-starter-addons'),
                'label_off' => __('No', 'mec-starter-addons'),
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'tickets_label',
            [
                'label' => __('Tickets Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Tickets', 'mec-starter-addons'),
                'condition' => ['show_stats' => 'yes'],
            ]
        );
        
        $this->add_control(
            'events_label',
            [
                'label' => __('Events Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Events', 'mec-starter-addons'),
                'condition' => ['show_stats' => 'yes'],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== CONTENT SECTION - FIELDS =====
        $this->start_controls_section(
            'section_fields',
            [
                'label' => __('Form Fields', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_profile_picture',
            [
                'label' => __('Show Profile Picture', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_first_name',
            [
                'label' => __('Show First Name', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_last_name',
            [
                'label' => __('Show Last Name', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_email',
            [
                'label' => __('Show Email', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'email_readonly',
            [
                'label' => __('Email Read-only', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => ['show_email' => 'yes'],
            ]
        );
        
        $this->add_control(
            'show_phone',
            [
                'label' => __('Show Phone', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_location',
            [
                'label' => __('Show Location', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_website',
            [
                'label' => __('Show Website', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_bio',
            [
                'label' => __('Show Bio', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );
        
        $this->end_controls_section();
        
        // ===== CONTENT SECTION - LABELS =====
        $this->start_controls_section(
            'section_labels',
            [
                'label' => __('Field Labels', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'first_name_label',
            [
                'label' => __('First Name Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('First Name', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'last_name_label',
            [
                'label' => __('Last Name Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Last Name', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'email_label',
            [
                'label' => __('Email Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Email', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'phone_label',
            [
                'label' => __('Phone Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Phone Number', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'location_label',
            [
                'label' => __('Location Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Location', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'website_label',
            [
                'label' => __('Website Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Website', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'bio_label',
            [
                'label' => __('Bio Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Bio', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'profile_picture_label',
            [
                'label' => __('Profile Picture Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Profile Picture', 'mec-starter-addons'),
            ]
        );
        
        $this->end_controls_section();
        
        // ===== CONTENT SECTION - BUTTONS =====
        $this->start_controls_section(
            'section_buttons',
            [
                'label' => __('Buttons', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'save_button_text',
            [
                'label' => __('Save Button Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Save Changes', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'show_logout',
            [
                'label' => __('Show Logout Button', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'logout_text',
            [
                'label' => __('Logout Button Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Logout', 'mec-starter-addons'),
                'condition' => ['show_logout' => 'yes'],
            ]
        );
        
        $this->add_control(
            'show_cancel',
            [
                'label' => __('Show Cancel Button', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'cancel_text',
            [
                'label' => __('Cancel Button Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Cancel', 'mec-starter-addons'),
                'condition' => ['show_cancel' => 'yes'],
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
                    '{{WRAPPER}} .mecas-user-dashboard' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => ['top' => 30, 'right' => 30, 'bottom' => 30, 'left' => 30, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-user-dashboard' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .mecas-user-dashboard' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => '{{WRAPPER}} .mecas-user-dashboard',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .mecas-user-dashboard',
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - TITLE =====
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
                'selector' => '{{WRAPPER}} .mecas-dashboard-title',
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __('Margin Bottom', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 60]],
                'default' => ['size' => 30, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - STATS =====
        $this->start_controls_section(
            'section_style_stats',
            [
                'label' => __('Stats Boxes', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'stats_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-stat' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'stats_border',
                'selector' => '{{WRAPPER}} .mecas-dashboard-stat',
            ]
        );
        
        $this->add_control(
            'stats_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 8, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-stat' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'stats_number_color',
            [
                'label' => __('Number Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3B82F6',
                'selectors' => [
                    '{{WRAPPER}} .mecas-stat-number' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'stats_number_typography',
                'label' => __('Number Typography', 'mec-starter-addons'),
                'selector' => '{{WRAPPER}} .mecas-stat-number',
            ]
        );
        
        $this->add_control(
            'stats_label_color',
            [
                'label' => __('Label Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#6B7280',
                'selectors' => [
                    '{{WRAPPER}} .mecas-stat-label' => 'color: {{VALUE}};',
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
            'heading_field_labels',
            [
                'label' => __('Labels', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'field_label_typography',
                'selector' => '{{WRAPPER}} .mecas-field-label',
            ]
        );
        
        $this->add_control(
            'field_label_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .mecas-field-label' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'heading_field_inputs',
            [
                'label' => __('Input Fields', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'field_input_typography',
                'selector' => '{{WRAPPER}} .mecas-field-input',
            ]
        );
        
        $this->add_control(
            'field_input_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecas-field-input' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'field_input_bg',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F9FAFB',
                'selectors' => [
                    '{{WRAPPER}} .mecas-field-input' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'field_input_border_color',
            [
                'label' => __('Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E5E7EB',
                'selectors' => [
                    '{{WRAPPER}} .mecas-field-input' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'field_input_focus_border',
            [
                'label' => __('Focus Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3B82F6',
                'selectors' => [
                    '{{WRAPPER}} .mecas-field-input:focus' => 'border-color: {{VALUE}}; outline: none;',
                ],
            ]
        );
        
        $this->add_control(
            'field_input_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 8, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-field-input' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'field_input_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => 12, 'right' => 16, 'bottom' => 12, 'left' => 16, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-field-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'show_field_icons',
            [
                'label' => __('Show Field Icons', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'field_icon_color',
            [
                'label' => __('Icon Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#9CA3AF',
                'selectors' => [
                    '{{WRAPPER}} .mecas-field-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mecas-field-icon svg' => 'fill: {{VALUE}};',
                ],
                'condition' => ['show_field_icons' => 'yes'],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - PROFILE PICTURE =====
        $this->start_controls_section(
            'section_style_avatar',
            [
                'label' => __('Profile Picture', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'avatar_size',
            [
                'label' => __('Size', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 60, 'max' => 200]],
                'default' => ['size' => 100, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-avatar-preview' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mecas-avatar-preview img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .mecas-avatar-preview, {{WRAPPER}} .mecas-avatar-preview img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'avatar_border',
                'selector' => '{{WRAPPER}} .mecas-avatar-preview',
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - SAVE BUTTON =====
        $this->start_controls_section(
            'section_style_save_button',
            [
                'label' => __('Save Button', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'save_btn_typography',
                'selector' => '{{WRAPPER}} .mecas-dashboard-save',
            ]
        );
        
        $this->start_controls_tabs('save_btn_tabs');
        
        $this->start_controls_tab('save_btn_normal', ['label' => __('Normal', 'mec-starter-addons')]);
        
        $this->add_control(
            'save_btn_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-save' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'save_btn_bg',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3B82F6',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-save' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('save_btn_hover', ['label' => __('Hover', 'mec-starter-addons')]);
        
        $this->add_control(
            'save_btn_color_hover',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-save:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'save_btn_bg_hover',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2563EB',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-save:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
            'save_btn_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 8, 'unit' => 'px'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-save' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'save_btn_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => 14, 'right' => 28, 'bottom' => 14, 'left' => 28, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-save' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - SECONDARY BUTTONS =====
        $this->start_controls_section(
            'section_style_secondary_buttons',
            [
                'label' => __('Secondary Buttons (Cancel/Logout)', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'secondary_btn_typography',
                'selector' => '{{WRAPPER}} .mecas-dashboard-cancel, {{WRAPPER}} .mecas-dashboard-logout',
            ]
        );
        
        $this->add_control(
            'secondary_btn_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#6B7280',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-cancel, {{WRAPPER}} .mecas-dashboard-logout' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'secondary_btn_bg',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F3F4F6',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-cancel, {{WRAPPER}} .mecas-dashboard-logout' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'secondary_btn_hover_bg',
            [
                'label' => __('Hover Background', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E5E7EB',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-cancel:hover, {{WRAPPER}} .mecas-dashboard-logout:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'secondary_btn_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 8, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-cancel, {{WRAPPER}} .mecas-dashboard-logout' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'logout_btn_color',
            [
                'label' => __('Logout Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#DC2626',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-logout' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Check if user is logged in
        if (!is_user_logged_in()) {
            echo '<div class="mecas-dashboard-login-required">';
            echo '<p>' . __('Please log in to view your dashboard.', 'mec-starter-addons') . '</p>';
            echo '</div>';
            return;
        }
        
        $user = wp_get_current_user();
        $user_id = $user->ID;
        
        // Get user data
        $first_name = $user->first_name;
        $last_name = $user->last_name;
        $email = $user->user_email;
        $website = $user->user_url;
        $bio = $user->description;
        $phone = get_user_meta($user_id, 'mecas_phone', true);
        $location = get_user_meta($user_id, 'mecas_location', true);
        $avatar_url = get_avatar_url($user_id, array('size' => 200));
        
        // Get MEC specific data if available
        $mec_phone = get_user_meta($user_id, 'mec_phone', true);
        if (empty($phone) && !empty($mec_phone)) {
            $phone = $mec_phone;
        }
        
        // Get stats
        $tickets_count = $this->get_user_tickets_count($user_id);
        $events_count = $this->get_user_events_count($user_id);
        
        $show_icons = $settings['show_field_icons'] === 'yes';
        
        ?>
        <div class="mecas-user-dashboard" data-widget-id="<?php echo esc_attr($this->get_id()); ?>">
            <?php if (!empty($settings['title'])): ?>
            <h2 class="mecas-dashboard-title"><?php echo esc_html($settings['title']); ?></h2>
            <?php endif; ?>
            
            <?php if ($settings['show_stats'] === 'yes'): ?>
            <div class="mecas-dashboard-stats">
                <div class="mecas-dashboard-stat">
                    <span class="mecas-stat-number"><?php echo esc_html($tickets_count); ?></span>
                    <span class="mecas-stat-label"><?php echo esc_html($settings['tickets_label']); ?></span>
                </div>
                <div class="mecas-dashboard-stat">
                    <span class="mecas-stat-number"><?php echo esc_html($events_count); ?></span>
                    <span class="mecas-stat-label"><?php echo esc_html($settings['events_label']); ?></span>
                </div>
            </div>
            <?php endif; ?>
            
            <form class="mecas-dashboard-form" enctype="multipart/form-data">
                <?php wp_nonce_field('mecas_dashboard_nonce', 'mecas_dashboard_nonce'); ?>
                
                <div class="mecas-dashboard-fields">
                    <!-- Name Fields Row -->
                    <?php if ($settings['show_first_name'] === 'yes' || $settings['show_last_name'] === 'yes'): ?>
                    <div class="mecas-fields-row mecas-fields-row-2">
                        <?php if ($settings['show_first_name'] === 'yes'): ?>
                        <div class="mecas-field-group">
                            <label class="mecas-field-label"><?php echo esc_html($settings['first_name_label']); ?></label>
                            <div class="mecas-field-wrapper <?php echo $show_icons ? 'has-icon' : ''; ?>">
                                <?php if ($show_icons): ?>
                                <span class="mecas-field-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                                <?php endif; ?>
                                <input type="text" name="first_name" class="mecas-field-input" value="<?php echo esc_attr($first_name); ?>" placeholder="First">
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($settings['show_last_name'] === 'yes'): ?>
                        <div class="mecas-field-group">
                            <label class="mecas-field-label"><?php echo esc_html($settings['last_name_label']); ?></label>
                            <div class="mecas-field-wrapper <?php echo $show_icons ? 'has-icon' : ''; ?>">
                                <?php if ($show_icons): ?>
                                <span class="mecas-field-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                                <?php endif; ?>
                                <input type="text" name="last_name" class="mecas-field-input" value="<?php echo esc_attr($last_name); ?>" placeholder="Last">
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Email & Website Row -->
                    <?php if ($settings['show_email'] === 'yes' || $settings['show_website'] === 'yes'): ?>
                    <div class="mecas-fields-row mecas-fields-row-2">
                        <?php if ($settings['show_email'] === 'yes'): ?>
                        <div class="mecas-field-group">
                            <label class="mecas-field-label"><?php echo esc_html($settings['email_label']); ?></label>
                            <div class="mecas-field-wrapper <?php echo $show_icons ? 'has-icon' : ''; ?>">
                                <?php if ($show_icons): ?>
                                <span class="mecas-field-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </span>
                                <?php endif; ?>
                                <input type="email" name="email" class="mecas-field-input" value="<?php echo esc_attr($email); ?>" <?php echo $settings['email_readonly'] === 'yes' ? 'readonly' : ''; ?>>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($settings['show_website'] === 'yes'): ?>
                        <div class="mecas-field-group">
                            <label class="mecas-field-label"><?php echo esc_html($settings['website_label']); ?></label>
                            <div class="mecas-field-wrapper <?php echo $show_icons ? 'has-icon' : ''; ?>">
                                <?php if ($show_icons): ?>
                                <span class="mecas-field-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="2" y1="12" x2="22" y2="12"></line>
                                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                    </svg>
                                </span>
                                <?php endif; ?>
                                <input type="url" name="website" class="mecas-field-input" value="<?php echo esc_attr($website); ?>" placeholder="https://">
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Phone & Location Row -->
                    <?php if ($settings['show_phone'] === 'yes' || $settings['show_location'] === 'yes'): ?>
                    <div class="mecas-fields-row mecas-fields-row-2">
                        <?php if ($settings['show_phone'] === 'yes'): ?>
                        <div class="mecas-field-group">
                            <label class="mecas-field-label"><?php echo esc_html($settings['phone_label']); ?></label>
                            <div class="mecas-field-wrapper <?php echo $show_icons ? 'has-icon' : ''; ?>">
                                <?php if ($show_icons): ?>
                                <span class="mecas-field-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </span>
                                <?php endif; ?>
                                <input type="tel" name="phone" class="mecas-field-input" value="<?php echo esc_attr($phone); ?>" placeholder="+1 (555) 123-4567">
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($settings['show_location'] === 'yes'): ?>
                        <div class="mecas-field-group">
                            <label class="mecas-field-label"><?php echo esc_html($settings['location_label']); ?></label>
                            <div class="mecas-field-wrapper <?php echo $show_icons ? 'has-icon' : ''; ?>">
                                <?php if ($show_icons): ?>
                                <span class="mecas-field-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </span>
                                <?php endif; ?>
                                <input type="text" name="location" class="mecas-field-input" value="<?php echo esc_attr($location); ?>" placeholder="City, State">
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Bio -->
                    <?php if ($settings['show_bio'] === 'yes'): ?>
                    <div class="mecas-fields-row">
                        <div class="mecas-field-group mecas-field-full">
                            <label class="mecas-field-label"><?php echo esc_html($settings['bio_label']); ?></label>
                            <textarea name="bio" class="mecas-field-input mecas-field-textarea" rows="4"><?php echo esc_textarea($bio); ?></textarea>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Profile Picture -->
                    <?php if ($settings['show_profile_picture'] === 'yes'): ?>
                    <div class="mecas-fields-row">
                        <div class="mecas-field-group mecas-field-full">
                            <label class="mecas-field-label"><?php echo esc_html($settings['profile_picture_label']); ?></label>
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
                    <?php endif; ?>
                </div>
                
                <!-- Messages -->
                <div class="mecas-dashboard-message mecas-dashboard-success" style="display: none;"></div>
                <div class="mecas-dashboard-message mecas-dashboard-error" style="display: none;"></div>
                
                <!-- Buttons -->
                <div class="mecas-dashboard-buttons">
                    <div class="mecas-buttons-left">
                        <button type="submit" class="mecas-dashboard-btn mecas-dashboard-save">
                            <span class="mecas-btn-text"><?php echo esc_html($settings['save_button_text']); ?></span>
                            <span class="mecas-btn-loading" style="display: none;">
                                <svg class="mecas-spinner" width="20" height="20" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="32" stroke-linecap="round">
                                        <animate attributeName="stroke-dashoffset" values="0;64" dur="1s" repeatCount="indefinite"/>
                                    </circle>
                                </svg>
                            </span>
                        </button>
                        
                        <?php if ($settings['show_cancel'] === 'yes'): ?>
                        <button type="button" class="mecas-dashboard-btn mecas-dashboard-cancel">
                            <?php echo esc_html($settings['cancel_text']); ?>
                        </button>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($settings['show_logout'] === 'yes'): ?>
                    <div class="mecas-buttons-right">
                        <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="mecas-dashboard-btn mecas-dashboard-logout">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <?php echo esc_html($settings['logout_text']); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <style>
        .mecas-user-dashboard {
            background: #ffffff;
            padding: 30px;
        }
        
        .mecas-dashboard-title {
            font-size: 24px;
            font-weight: 600;
            color: #1F2937;
            margin: 0 0 30px 0;
        }
        
        /* Stats */
        .mecas-dashboard-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .mecas-dashboard-stat {
            background: #ffffff;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 24px;
            text-align: center;
        }
        
        .mecas-stat-number {
            display: block;
            font-size: 32px;
            font-weight: 700;
            color: #3B82F6;
            line-height: 1;
            margin-bottom: 8px;
        }
        
        .mecas-stat-label {
            font-size: 14px;
            color: #6B7280;
        }
        
        /* Form Fields */
        .mecas-dashboard-fields {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 24px;
        }
        
        .mecas-fields-row {
            display: grid;
            gap: 20px;
        }
        
        .mecas-fields-row-2 {
            grid-template-columns: repeat(2, 1fr);
        }
        
        @media (max-width: 768px) {
            .mecas-fields-row-2 {
                grid-template-columns: 1fr;
            }
        }
        
        .mecas-field-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .mecas-field-full {
            grid-column: 1 / -1;
        }
        
        .mecas-field-label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }
        
        .mecas-field-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .mecas-field-wrapper.has-icon .mecas-field-input {
            padding-left: 48px;
        }
        
        .mecas-field-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }
        
        .mecas-field-input {
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
        
        .mecas-field-input:focus {
            outline: none;
            border-color: #3B82F6;
            background: #ffffff;
        }
        
        .mecas-field-input[readonly] {
            background: #F3F4F6;
            cursor: not-allowed;
        }
        
        .mecas-field-textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        /* Avatar Upload */
        .mecas-avatar-upload {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: #F9FAFB;
            border: 2px dashed #E5E7EB;
            border-radius: 12px;
        }
        
        .mecas-avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            background: #E5E7EB;
        }
        
        .mecas-avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .mecas-avatar-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .mecas-avatar-change-btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 16px;
            background: #ffffff;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .mecas-avatar-change-btn:hover {
            background: #F3F4F6;
            border-color: #D1D5DB;
        }
        
        .mecas-avatar-filename {
            font-size: 13px;
            color: #9CA3AF;
        }
        
        /* Messages */
        .mecas-dashboard-message {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .mecas-dashboard-success {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .mecas-dashboard-error {
            background: #FEE2E2;
            color: #DC2626;
        }
        
        /* Buttons */
        .mecas-dashboard-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
        }
        
        .mecas-buttons-left {
            display: flex;
            gap: 12px;
        }
        
        .mecas-dashboard-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 28px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .mecas-dashboard-save {
            background: #3B82F6;
            color: #ffffff;
        }
        
        .mecas-dashboard-save:hover {
            background: #2563EB;
        }
        
        .mecas-dashboard-save:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .mecas-dashboard-cancel {
            background: #F3F4F6;
            color: #6B7280;
        }
        
        .mecas-dashboard-cancel:hover {
            background: #E5E7EB;
        }
        
        .mecas-dashboard-logout {
            background: #FEE2E2;
            color: #DC2626;
        }
        
        .mecas-dashboard-logout:hover {
            background: #FECACA;
        }
        
        .mecas-spinner {
            animation: mecas-rotate 1s linear infinite;
        }
        
        @keyframes mecas-rotate {
            100% { transform: rotate(360deg); }
        }
        
        /* Login required */
        .mecas-dashboard-login-required {
            text-align: center;
            padding: 40px;
            background: #F9FAFB;
            border-radius: 12px;
        }
        </style>
        
        <script>
        (function() {
            const dashboard = document.querySelector('.mecas-user-dashboard');
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
                        // Preview image
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
                    // Hide the dashboard container or trigger custom event
                    const container = dashboard.closest('.mecas-dashboard-ajax-container');
                    if (container) {
                        container.style.display = 'none';
                    }
                    // Dispatch custom event
                    dashboard.dispatchEvent(new CustomEvent('mecas-dashboard-cancel'));
                });
            }
            
            // Form submit
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const btnText = saveBtn.querySelector('.mecas-btn-text');
                const btnLoading = saveBtn.querySelector('.mecas-btn-loading');
                
                // Show loading
                saveBtn.disabled = true;
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline-flex';
                successMsg.style.display = 'none';
                errorMsg.style.display = 'none';
                
                // Build form data
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
                        
                        // Update avatar if new one was uploaded
                        if (data.data.avatar_url) {
                            avatarPreview.src = data.data.avatar_url;
                            // Also update profile card if exists
                            const profileCardAvatar = document.querySelector('.mecas-profile-avatar img');
                            if (profileCardAvatar) {
                                profileCardAvatar.src = data.data.avatar_url;
                            }
                        }
                        
                        // Update profile card name if exists
                        if (data.data.display_name) {
                            const profileCardName = document.querySelector('.mecas-profile-name');
                            if (profileCardName) {
                                profileCardName.textContent = data.data.display_name;
                            }
                        }
                        
                        // Scroll to message
                        successMsg.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } else {
                        errorMsg.textContent = data.data.message || 'An error occurred';
                        errorMsg.style.display = 'block';
                    }
                } catch (error) {
                    errorMsg.textContent = 'An error occurred. Please try again.';
                    errorMsg.style.display = 'block';
                }
                
                // Reset button
                saveBtn.disabled = false;
                btnText.style.display = 'inline';
                btnLoading.style.display = 'none';
            });
        })();
        </script>
        <?php
    }
    
    /**
     * Get user's ticket count from MEC
     */
    private function get_user_tickets_count($user_id) {
        global $wpdb;
        
        // Try MEC bookings table
        $table_name = $wpdb->prefix . 'mec_bookings';
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
            $count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE user_id = %d",
                $user_id
            ));
            return intval($count);
        }
        
        // Fallback - check post meta
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = 'mec-books'
            AND pm.meta_key = 'mec_booking_user_id'
            AND pm.meta_value = %d",
            $user_id
        ));
        
        return intval($count);
    }
    
    /**
     * Get events count (bookings/registrations)
     */
    private function get_user_events_count($user_id) {
        global $wpdb;
        
        // Count unique events the user has booked
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT pm2.meta_value) FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id
            WHERE p.post_type = 'mec-books'
            AND pm.meta_key = 'mec_booking_user_id'
            AND pm.meta_value = %d
            AND pm2.meta_key = 'mec_event_id'",
            $user_id
        ));
        
        return intval($count);
    }
}
