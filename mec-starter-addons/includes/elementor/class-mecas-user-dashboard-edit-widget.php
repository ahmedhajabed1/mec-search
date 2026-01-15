<?php
/**
 * User Dashboard Edit Widget
 * Custom user profile editing with AJAX loading and full Elementor control
 */

if (!defined('ABSPATH')) exit;

class MECAS_User_Dashboard_Edit_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_user_dashboard_edit';
    }
    
    public function get_title() {
        return __('User Dashboard Edit', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-user-circle-o';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['user', 'dashboard', 'edit', 'profile', 'account', 'settings'];
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
            'preview_mode',
            [
                'label' => __('Preview Mode', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __('Auto (Current User)', 'mec-starter-addons'),
                    'preview' => __('Preview with Example Data', 'mec-starter-addons'),
                    'hidden' => __('Hidden (As on Frontend)', 'mec-starter-addons'),
                ],
                'description' => __('Select how to display the widget in the editor. Use "Preview" to see example content for styling.', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'start_hidden',
            [
                'label' => __('Start Hidden', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-starter-addons'),
                'label_off' => __('No', 'mec-starter-addons'),
                'default' => 'yes',
                'description' => __('When enabled, widget is hidden until Edit Profile is clicked. Disable to always show.', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'ajax_container_id',
            [
                'label' => __('AJAX Container ID', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'mecas-dashboard-edit-container',
                'description' => __('ID for AJAX loading. Use this ID as target when clicking Edit Profile button.', 'mec-starter-addons'),
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
            'show_profile_picture',
            [
                'label' => __('Show Profile Picture Upload', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-starter-addons'),
                'label_off' => __('No', 'mec-starter-addons'),
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_logout',
            [
                'label' => __('Show Logout Button', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-starter-addons'),
                'label_off' => __('No', 'mec-starter-addons'),
                'default' => 'yes',
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
            'heading_standard_fields',
            [
                'label' => __('Standard Fields', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control(
            'show_first_name',
            [
                'label' => __('First Name', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_last_name',
            [
                'label' => __('Last Name', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_email',
            [
                'label' => __('Email', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_website',
            [
                'label' => __('Website', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'heading_custom_fields',
            [
                'label' => __('Custom Fields', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'show_phone',
            [
                'label' => __('Phone Number', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_location',
            [
                'label' => __('Location/Address', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'enable_location_detect',
            [
                'label' => __('Enable Location Auto-Detect', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'show_location' => 'yes',
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
            'title_text',
            [
                'label' => __('Section Title', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Edit Profile', 'mec-starter-addons'),
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
            'website_label',
            [
                'label' => __('Website Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Website', 'mec-starter-addons'),
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
            'profile_picture_label',
            [
                'label' => __('Profile Picture Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Profile Picture', 'mec-starter-addons'),
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
            'logout_text',
            [
                'label' => __('Logout Button Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Logout', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'tickets_label',
            [
                'label' => __('Tickets Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Tickets', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'events_label',
            [
                'label' => __('Events Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Events', 'mec-starter-addons'),
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
                    '{{WRAPPER}} .mecas-dashboard-edit' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .mecas-dashboard-edit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .mecas-dashboard-edit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => '{{WRAPPER}} .mecas-dashboard-edit',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .mecas-dashboard-edit',
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
                'selector' => '{{WRAPPER}} .mecas-dashboard-edit-title',
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-edit-title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .mecas-dashboard-edit-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                'default' => '#40CDB2',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-stat-number' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'stats_number_typography',
                'label' => __('Number Typography', 'mec-starter-addons'),
                'selector' => '{{WRAPPER}} .mecas-dashboard-stat-number',
            ]
        );
        
        $this->add_control(
            'stats_label_color',
            [
                'label' => __('Label Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#40CDB2',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-stat-label' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'stats_label_typography',
                'label' => __('Label Typography', 'mec-starter-addons'),
                'selector' => '{{WRAPPER}} .mecas-dashboard-stat-label',
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
                'selector' => '{{WRAPPER}} .mecas-dashboard-label',
            ]
        );
        
        $this->add_control(
            'label_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-label' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .mecas-dashboard-input',
            ]
        );
        
        $this->add_control(
            'input_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-input' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'input_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-input' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .mecas-dashboard-input' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'input_focus_border_color',
            [
                'label' => __('Focus Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#40CDB2',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-input:focus' => 'border-color: {{VALUE}}; outline: none;',
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
                    '{{WRAPPER}} .mecas-dashboard-input' => 'border-radius: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .mecas-dashboard-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'input_icon_color',
            [
                'label' => __('Icon Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#9CA3AF',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-input-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mecas-dashboard-input-icon svg' => 'fill: {{VALUE}};',
                ],
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
                'label' => __('Avatar Size', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 60, 'max' => 200]],
                'default' => ['size' => 80, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mecas-dashboard-avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .mecas-dashboard-avatar, {{WRAPPER}} .mecas-dashboard-avatar img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'avatar_border',
                'selector' => '{{WRAPPER}} .mecas-dashboard-avatar',
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - CLOSE BUTTON =====
        $this->start_controls_section(
            'section_style_close_button',
            [
                'label' => __('Close Button', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'close_button_size',
            [
                'label' => __('Button Size', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 20, 'max' => 60, 'step' => 1],
                ],
                'default' => ['size' => 32, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-close' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'close_button_icon_size',
            [
                'label' => __('Icon Size', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 40, 'step' => 1],
                ],
                'default' => ['size' => 18, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-close svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'close_button_position_top',
            [
                'label' => __('Position Top', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100, 'step' => 1],
                    '%' => ['min' => 0, 'max' => 100, 'step' => 1],
                ],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-close' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'close_button_position_right',
            [
                'label' => __('Position Right', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100, 'step' => 1],
                    '%' => ['min' => 0, 'max' => 100, 'step' => 1],
                ],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-close' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'close_button_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50, 'step' => 1],
                    '%' => ['min' => 0, 'max' => 50, 'step' => 1],
                ],
                'default' => ['size' => 50, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-close' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs('close_button_tabs');
        
        $this->start_controls_tab('close_button_normal', ['label' => __('Normal', 'mec-starter-addons')]);
        
        $this->add_control(
            'close_button_icon_color',
            [
                'label' => __('Icon Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#6B7280',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-close' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mecas-dashboard-close svg' => 'stroke: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'close_button_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F3F4F6',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-close' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'close_button_border',
                'selector' => '{{WRAPPER}} .mecas-dashboard-close',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('close_button_hover', ['label' => __('Hover', 'mec-starter-addons')]);
        
        $this->add_control(
            'close_button_icon_color_hover',
            [
                'label' => __('Icon Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-close:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mecas-dashboard-close:hover svg' => 'stroke: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'close_button_bg_color_hover',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E5E7EB',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-close:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'close_button_border_color_hover',
            [
                'label' => __('Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-close:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'close_button_box_shadow',
                'selector' => '{{WRAPPER}} .mecas-dashboard-close',
                'separator' => 'before',
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
                'name' => 'save_button_typography',
                'selector' => '{{WRAPPER}} .mecas-dashboard-save',
            ]
        );
        
        $this->start_controls_tabs('save_button_tabs');
        
        $this->start_controls_tab('save_button_normal', ['label' => __('Normal', 'mec-starter-addons')]);
        
        $this->add_control(
            'save_button_text_color',
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
            'save_button_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#40CDB2',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-save' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('save_button_hover', ['label' => __('Hover', 'mec-starter-addons')]);
        
        $this->add_control(
            'save_button_text_color_hover',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-save:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'save_button_bg_color_hover',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#38B89E',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-save:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
            'save_button_border_radius',
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
            'save_button_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => 14, 'right' => 32, 'bottom' => 14, 'left' => 32, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-save' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // ===== STYLE SECTION - LOGOUT BUTTON =====
        $this->start_controls_section(
            'section_style_logout_button',
            [
                'label' => __('Logout Button', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'logout_button_typography',
                'selector' => '{{WRAPPER}} .mecas-dashboard-logout',
            ]
        );
        
        $this->start_controls_tabs('logout_button_tabs');
        
        $this->start_controls_tab('logout_button_normal', ['label' => __('Normal', 'mec-starter-addons')]);
        
        $this->add_control(
            'logout_button_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#DC2626',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-logout' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'logout_button_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-logout' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'logout_button_border_color',
            [
                'label' => __('Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#DC2626',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-logout' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('logout_button_hover', ['label' => __('Hover', 'mec-starter-addons')]);
        
        $this->add_control(
            'logout_button_text_color_hover',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-logout:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'logout_button_bg_color_hover',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#DC2626',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-logout:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
            'logout_button_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 8, 'unit' => 'px'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-logout' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'logout_button_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => 12, 'right' => 24, 'bottom' => 12, 'left' => 24, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-dashboard-logout' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode();
        $preview_mode = $settings['preview_mode'] ?? 'auto';
        $start_hidden = $settings['start_hidden'] ?? 'yes';
        
        // Check if user is logged in
        if (!is_user_logged_in() && !$is_editor) {
            return; // Don't show anything if not logged in
        }
        
        // Handle preview modes in editor
        if ($is_editor && $preview_mode === 'hidden') {
            echo '<div class="mecas-dashboard-edit-preview-notice" style="padding: 40px; text-align: center; background: #f5f5f5; border: 2px dashed #ccc; border-radius: 8px;">';
            echo '<p style="color: #666; margin: 0;"><strong>' . __('User Dashboard Edit Widget', 'mec-starter-addons') . '</strong></p>';
            echo '<p style="color: #999; margin: 8px 0 0 0; font-size: 13px;">' . __('Hidden on frontend. Change Preview Mode to see content.', 'mec-starter-addons') . '</p>';
            echo '</div>';
            return;
        }
        
        // Determine if we should use preview/example data
        $use_preview_data = ($is_editor && $preview_mode === 'preview');
        
        // Get user data
        $user_id = get_current_user_id();
        $user = wp_get_current_user();
        
        // Use example data for preview mode OR if not logged in while in editor
        if ($use_preview_data || ($is_editor && !$user_id)) {
            $first_name = 'Jane';
            $last_name = 'Doe';
            $email = 'janedoe123@gmail.com';
            $website = 'https://themahjhub.com';
            $phone = '+1 (555) 123-4567';
            $location = 'Location, Location, FL';
            $avatar_url = 'https://i.pravatar.cc/150?img=5';
            $tickets_count = 0;
            $events_count = 40;
        } else {
            $first_name = $user->first_name;
            $last_name = $user->last_name;
            $email = $user->user_email;
            $website = $user->user_url;
            $phone = get_user_meta($user_id, 'mecas_phone', true);
            $location = get_user_meta($user_id, 'mecas_location', true);
            $avatar_url = get_avatar_url($user_id, array('size' => 200));
            
            // Get ticket and event counts
            $tickets_count = $this->get_user_tickets_count($user_id);
            $events_count = $this->get_user_events_count($user_id);
        }
        
        $container_id = $settings['ajax_container_id'] ?: 'mecas-dashboard-edit-container';
        
        // Determine if widget should be hidden
        // Hidden on frontend when start_hidden is yes, always visible in editor
        $should_hide = ($start_hidden === 'yes' && !$is_editor);
        $inline_style = $should_hide ? 'display: none;' : '';
        
        ?>
        <div class="mecas-dashboard-edit" id="<?php echo esc_attr($container_id); ?>" style="<?php echo esc_attr($inline_style); ?>">
            
            <!-- Close button -->
            <button type="button" class="mecas-dashboard-close" title="<?php esc_attr_e('Close', 'mec-starter-addons'); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            
            <?php if (!empty($settings['title_text'])): ?>
            <h2 class="mecas-dashboard-edit-title"><?php echo esc_html($settings['title_text']); ?></h2>
            <?php endif; ?>
            
            <?php if ($settings['show_stats'] === 'yes'): ?>
            <div class="mecas-dashboard-stats">
                <div class="mecas-dashboard-stat">
                    <span class="mecas-dashboard-stat-number"><?php echo esc_html($tickets_count); ?></span>
                    <span class="mecas-dashboard-stat-label"><?php echo esc_html($settings['tickets_label']); ?></span>
                </div>
                <div class="mecas-dashboard-stat">
                    <span class="mecas-dashboard-stat-number"><?php echo esc_html($events_count); ?></span>
                    <span class="mecas-dashboard-stat-label"><?php echo esc_html($settings['events_label']); ?></span>
                </div>
            </div>
            <?php endif; ?>
            
            <form class="mecas-dashboard-form" enctype="multipart/form-data">
                <?php wp_nonce_field('mecas_dashboard_edit_nonce', 'mecas_dashboard_nonce'); ?>
                
                <div class="mecas-dashboard-fields-grid">
                    <?php if ($settings['show_first_name'] === 'yes'): ?>
                    <div class="mecas-dashboard-field">
                        <label class="mecas-dashboard-label"><?php echo esc_html($settings['first_name_label']); ?></label>
                        <div class="mecas-dashboard-input-wrap">
                            <span class="mecas-dashboard-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                            <input type="text" name="first_name" class="mecas-dashboard-input" value="<?php echo esc_attr($first_name); ?>" placeholder="First">
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_last_name'] === 'yes'): ?>
                    <div class="mecas-dashboard-field">
                        <label class="mecas-dashboard-label"><?php echo esc_html($settings['last_name_label']); ?></label>
                        <div class="mecas-dashboard-input-wrap">
                            <span class="mecas-dashboard-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                            <input type="text" name="last_name" class="mecas-dashboard-input" value="<?php echo esc_attr($last_name); ?>" placeholder="Last">
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_email'] === 'yes'): ?>
                    <div class="mecas-dashboard-field">
                        <label class="mecas-dashboard-label"><?php echo esc_html($settings['email_label']); ?></label>
                        <div class="mecas-dashboard-input-wrap">
                            <span class="mecas-dashboard-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </span>
                            <input type="email" name="email" class="mecas-dashboard-input" value="<?php echo esc_attr($email); ?>" placeholder="email@example.com">
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_website'] === 'yes'): ?>
                    <div class="mecas-dashboard-field">
                        <label class="mecas-dashboard-label"><?php echo esc_html($settings['website_label']); ?></label>
                        <div class="mecas-dashboard-input-wrap">
                            <span class="mecas-dashboard-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                </svg>
                            </span>
                            <input type="url" name="website" class="mecas-dashboard-input" value="<?php echo esc_attr($website); ?>" placeholder="https://yourwebsite.com">
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_phone'] === 'yes'): ?>
                    <div class="mecas-dashboard-field">
                        <label class="mecas-dashboard-label"><?php echo esc_html($settings['phone_label']); ?></label>
                        <div class="mecas-dashboard-input-wrap">
                            <span class="mecas-dashboard-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                            </span>
                            <input type="tel" name="phone" class="mecas-dashboard-input" value="<?php echo esc_attr($phone); ?>" placeholder="+1 (555) 123-4567">
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_location'] === 'yes'): ?>
                    <div class="mecas-dashboard-field">
                        <label class="mecas-dashboard-label"><?php echo esc_html($settings['location_label']); ?></label>
                        <div class="mecas-dashboard-input-wrap mecas-dashboard-location-wrap">
                            <span class="mecas-dashboard-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </span>
                            <input type="text" name="location" class="mecas-dashboard-input mecas-dashboard-location-input" value="<?php echo esc_attr($location); ?>" placeholder="City, State">
                            <?php if ($settings['enable_location_detect'] === 'yes'): ?>
                            <button type="button" class="mecas-dashboard-detect-location" title="Detect my location">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M12 2v4M12 18v4M2 12h4M18 12h4"></path>
                                </svg>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($settings['show_profile_picture'] === 'yes'): ?>
                <div class="mecas-dashboard-avatar-section">
                    <label class="mecas-dashboard-label"><?php echo esc_html($settings['profile_picture_label']); ?></label>
                    <div class="mecas-dashboard-avatar-upload">
                        <div class="mecas-dashboard-avatar">
                            <img src="<?php echo esc_url($avatar_url); ?>" alt="Profile Picture" id="mecas-avatar-preview">
                        </div>
                        <div class="mecas-dashboard-avatar-actions">
                            <label class="mecas-dashboard-avatar-btn">
                                <input type="file" name="profile_picture" accept="image/*" id="mecas-avatar-input" style="display: none;">
                                <span><?php _e('Change Profile Picture', 'mec-starter-addons'); ?></span>
                            </label>
                            <span class="mecas-dashboard-avatar-hint"><?php _e('JPG, PNG or GIF. Max 2MB.', 'mec-starter-addons'); ?></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="mecas-dashboard-message" style="display: none;"></div>
                
                <div class="mecas-dashboard-actions">
                    <button type="submit" class="mecas-dashboard-save">
                        <span class="mecas-dashboard-save-text"><?php echo esc_html($settings['save_button_text']); ?></span>
                        <span class="mecas-dashboard-save-loading" style="display: none;">
                            <svg class="mecas-spinner" width="20" height="20" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="32" stroke-linecap="round">
                                    <animate attributeName="stroke-dashoffset" values="0;64" dur="1s" repeatCount="indefinite"/>
                                </circle>
                            </svg>
                        </span>
                    </button>
                    
                    <?php if ($settings['show_logout'] === 'yes'): ?>
                    <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="mecas-dashboard-logout">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <?php echo esc_html($settings['logout_text']); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <style>
        /* Dashboard Edit Widget Styles */
        .mecas-dashboard-edit {
            background: #ffffff;
            padding: 30px;
            position: relative;
        }
        
        /* Close button - layout only, colors/sizes controlled by Elementor */
        .mecas-dashboard-close {
            position: absolute;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            z-index: 10;
            padding: 0;
        }
        
        .mecas-dashboard-close svg {
            flex-shrink: 0;
        }
        
        .mecas-dashboard-edit-title {
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
        
        .mecas-dashboard-stat-number {
            display: block;
            font-size: 36px;
            font-weight: 600;
            color: #40CDB2;
            line-height: 1.2;
        }
        
        .mecas-dashboard-stat-label {
            display: block;
            font-size: 14px;
            color: #40CDB2;
            margin-top: 4px;
        }
        
        /* Form grid */
        .mecas-dashboard-fields-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 767px) {
            .mecas-dashboard-fields-grid {
                grid-template-columns: 1fr;
            }
            .mecas-dashboard-stats {
                grid-template-columns: 1fr;
            }
        }
        
        .mecas-dashboard-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .mecas-dashboard-label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }
        
        .mecas-dashboard-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .mecas-dashboard-input-icon {
            position: absolute;
            left: 14px;
            color: #9CA3AF;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }
        
        .mecas-dashboard-input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            font-size: 14px;
            color: #1F2937;
            background: #ffffff;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }
        
        .mecas-dashboard-input:focus {
            outline: none;
            border-color: #40CDB2;
        }
        
        .mecas-dashboard-input::placeholder {
            color: #9CA3AF;
        }
        
        /* Location detect button */
        .mecas-dashboard-location-wrap {
            position: relative;
        }
        
        .mecas-dashboard-location-wrap .mecas-dashboard-input {
            padding-right: 44px;
        }
        
        .mecas-dashboard-detect-location {
            position: absolute;
            right: 8px;
            background: transparent;
            border: none;
            padding: 8px;
            cursor: pointer;
            color: #9CA3AF;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }
        
        .mecas-dashboard-detect-location:hover {
            color: #40CDB2;
        }
        
        .mecas-dashboard-detect-location.loading svg {
            animation: mecas-rotate 1s linear infinite;
        }
        
        @keyframes mecas-rotate {
            100% { transform: rotate(360deg); }
        }
        
        /* Avatar section */
        .mecas-dashboard-avatar-section {
            margin-bottom: 30px;
            padding-top: 20px;
            border-top: 1px dashed #E5E7EB;
        }
        
        .mecas-dashboard-avatar-upload {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 12px;
        }
        
        .mecas-dashboard-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #E5E7EB;
            flex-shrink: 0;
        }
        
        .mecas-dashboard-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .mecas-dashboard-avatar-actions {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .mecas-dashboard-avatar-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: #F3F4F6;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            transition: background 0.2s ease;
        }
        
        .mecas-dashboard-avatar-btn:hover {
            background: #E5E7EB;
        }
        
        .mecas-dashboard-avatar-hint {
            font-size: 12px;
            color: #9CA3AF;
        }
        
        /* Message */
        .mecas-dashboard-message {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .mecas-dashboard-message.success {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .mecas-dashboard-message.error {
            background: #FEE2E2;
            color: #DC2626;
        }
        
        /* Actions */
        .mecas-dashboard-actions {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .mecas-dashboard-save {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 32px;
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            background: #40CDB2;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .mecas-dashboard-save:hover {
            background: #38B89E;
        }
        
        .mecas-dashboard-save:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .mecas-dashboard-logout {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 500;
            color: #DC2626;
            background: transparent;
            border: 1px solid #DC2626;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .mecas-dashboard-logout:hover {
            background: #DC2626;
            color: #ffffff;
        }
        
        .mecas-spinner {
            animation: mecas-rotate 1s linear infinite;
        }
        
        /* Not logged in state */
        .mecas-dashboard-not-logged-in {
            text-align: center;
            padding: 40px;
            color: #6B7280;
        }
        </style>
        
        <script>
        (function() {
            const container = document.getElementById('<?php echo esc_js($container_id); ?>');
            const form = document.querySelector('#<?php echo esc_js($container_id); ?> .mecas-dashboard-form');
            if (!container || !form) return;
            
            // Note: Toggle (open/close) is handled globally by mecas-scripts.js initDashboardEditToggle()
            // This script only handles form functionality (avatar preview, geolocation, form submission)
            
            // Avatar preview
            const avatarInput = document.getElementById('mecas-avatar-input');
            const avatarPreview = document.getElementById('mecas-avatar-preview');
            
            if (avatarInput && avatarPreview) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Check file size (2MB max)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('<?php _e('File size must be less than 2MB', 'mec-starter-addons'); ?>');
                            this.value = '';
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            avatarPreview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            
            // Location detection
            const detectBtn = document.querySelector('.mecas-dashboard-detect-location');
            const locationInput = document.querySelector('.mecas-dashboard-location-input');
            
            if (detectBtn && locationInput) {
                detectBtn.addEventListener('click', function() {
                    if (!navigator.geolocation) {
                        alert('<?php _e('Geolocation is not supported by your browser', 'mec-starter-addons'); ?>');
                        return;
                    }
                    
                    this.classList.add('loading');
                    
                    navigator.geolocation.getCurrentPosition(
                        async (position) => {
                            try {
                                const response = await fetch(
                                    `https://nominatim.openstreetmap.org/reverse?lat=${position.coords.latitude}&lon=${position.coords.longitude}&format=json`
                                );
                                const data = await response.json();
                                
                                if (data.address) {
                                    const city = data.address.city || data.address.town || data.address.village || '';
                                    const state = data.address.state || '';
                                    locationInput.value = city && state ? `${city}, ${state}` : data.display_name.split(',').slice(0, 2).join(',');
                                }
                            } catch (error) {
                                console.error('Error getting location:', error);
                            }
                            this.classList.remove('loading');
                        },
                        (error) => {
                            console.error('Geolocation error:', error);
                            this.classList.remove('loading');
                            alert('<?php _e('Could not detect your location', 'mec-starter-addons'); ?>');
                        }
                    );
                });
            }
            
            // Form submission
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const saveBtn = form.querySelector('.mecas-dashboard-save');
                const saveText = saveBtn.querySelector('.mecas-dashboard-save-text');
                const saveLoading = saveBtn.querySelector('.mecas-dashboard-save-loading');
                const messageDiv = form.querySelector('.mecas-dashboard-message');
                
                // Show loading
                saveBtn.disabled = true;
                saveText.style.display = 'none';
                saveLoading.style.display = 'inline-flex';
                messageDiv.style.display = 'none';
                
                // Build form data
                const formData = new FormData(form);
                formData.append('action', 'mecas_save_dashboard_profile');
                
                try {
                    const response = await fetch(mecas_ajax.ajax_url, {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        messageDiv.textContent = data.data.message;
                        messageDiv.className = 'mecas-dashboard-message success';
                        messageDiv.style.display = 'block';
                        
                        // Update avatar if new one was uploaded
                        if (data.data.avatar_url && avatarPreview) {
                            avatarPreview.src = data.data.avatar_url;
                        }
                    } else {
                        messageDiv.textContent = data.data.message || '<?php _e('An error occurred', 'mec-starter-addons'); ?>';
                        messageDiv.className = 'mecas-dashboard-message error';
                        messageDiv.style.display = 'block';
                    }
                } catch (error) {
                    messageDiv.textContent = '<?php _e('An error occurred. Please try again.', 'mec-starter-addons'); ?>';
                    messageDiv.className = 'mecas-dashboard-message error';
                    messageDiv.style.display = 'block';
                }
                
                // Reset button
                saveBtn.disabled = false;
                saveText.style.display = 'inline';
                saveLoading.style.display = 'none';
                
                // Scroll to message
                messageDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        })();
        </script>
        
        <!-- Standalone jQuery Toggle Script - No Dependencies -->
        <script>
        jQuery(document).ready(function($) {
            var $dashboardEdit = $('#<?php echo esc_js($container_id); ?>');
            var isElementorEditor = $('body').hasClass('elementor-editor-active') || $('body').hasClass('elementor-editor-preview');
            
            // Make sure it's hidden on page load (frontend only, not in Elementor editor)
            if (!isElementorEditor) {
                $dashboardEdit.hide();
            }
            
            // Close button click
            $dashboardEdit.find('.mecas-dashboard-close').on('click', function(e) {
                e.preventDefault();
                $dashboardEdit.slideUp(400);
            });
            
            // Global function to open
            window.mecasOpenDashboardEdit = function() {
                $dashboardEdit.slideDown(400, function() {
                    $('html, body').animate({
                        scrollTop: $dashboardEdit.offset().top - 50
                    }, 400);
                });
            };
            
            // Listen for Edit Profile clicks - multiple selectors for compatibility
            $(document).on('click', '.mecas-ajax-edit-trigger, .mecas-edit-profile-trigger, [data-edit-profile], .mecua-profile-edit-btn, .mecua-profile-edit-icon', function(e) {
                e.preventDefault();
                e.stopPropagation();
                window.mecasOpenDashboardEdit();
            });
            
            console.log('MECAS Dashboard Edit: Initialized for #<?php echo esc_js($container_id); ?>', 'Hidden:', !isElementorEditor);
        });
        </script>
        <?php
    }
    
    /**
     * Get user tickets count (MEC bookings)
     */
    private function get_user_tickets_count($user_id) {
        global $wpdb;
        
        // Try MEC bookings table
        $table = $wpdb->prefix . 'mec_bookings';
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") === $table) {
            return (int) $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE user_id = %d",
                $user_id
            ));
        }
        
        // Fallback to post meta
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->posts} p 
             INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id 
             WHERE p.post_type = 'mec-books' 
             AND pm.meta_key = 'mec_booking_user_id' 
             AND pm.meta_value = %d",
            $user_id
        ));
    }
    
    /**
     * Get user attended/registered events count
     */
    private function get_user_events_count($user_id) {
        global $wpdb;
        
        // Count MEC events the user has registered for
        $count = (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT pm.meta_value) FROM {$wpdb->posts} p 
             INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id 
             INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id 
             WHERE p.post_type = 'mec-books' 
             AND pm.meta_key = 'mec_event_id'
             AND pm2.meta_key = 'mec_booking_user_id' 
             AND pm2.meta_value = %d",
            $user_id
        ));
        
        // If no MEC bookings, count published events (for demo)
        if ($count === 0) {
            $count = wp_count_posts('mec-events')->publish;
        }
        
        return $count;
    }
}
