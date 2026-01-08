<?php
/**
 * MEC Starter Addons - Elementor Search Widget
 * Original Dark Design with Auto Geolocation
 */

if (!defined('ABSPATH')) exit;

class MECAS_Search_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_search';
    }

    public function get_title() {
        return __('MEC Event Search', 'mec-starter-addons');
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return ['mec-starter-addons'];
    }

    public function get_keywords() {
        return ['search', 'events', 'mec', 'calendar', 'location'];
    }

    protected function register_controls() {
        // === CONTENT TAB ===
        
        // General Section
        $this->start_controls_section('section_general', [
            'label' => __('General', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('mode', [
            'label' => __('Display Mode', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'inline',
            'options' => [
                'inline' => __('Inline (Direct)', 'mec-starter-addons'),
                'popup' => __('Popup (Modal)', 'mec-starter-addons'),
            ],
        ]);

        $this->add_control('results_page', [
            'label' => __('Results Page URL', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => home_url('/events-search/'),
            'default' => ['url' => get_option('mecas_results_page', '')],
            'description' => __('REQUIRED: Enter the URL of the page containing the MEC Search Results widget. Without this, search won\'t work.', 'mec-starter-addons'),
        ]);

        $this->add_control('enable_geolocation', [
            'label' => __('Enable Geolocation', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'mec-starter-addons'),
            'label_off' => __('No', 'mec-starter-addons'),
            'default' => 'yes',
        ]);

        $this->add_control('auto_detect_location', [
            'label' => __('Auto-Detect Location', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'mec-starter-addons'),
            'label_off' => __('No', 'mec-starter-addons'),
            'default' => 'yes',
            'description' => __('Automatically detect user location on page load', 'mec-starter-addons'),
            'condition' => ['enable_geolocation' => 'yes'],
        ]);

        $this->add_control('show_suggestions', [
            'label' => __('Show Suggestions (Deprecated)', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('This feature has been removed for better user experience', 'mec-starter-addons'),
        ]);

        $this->end_controls_section();

        // Placeholders Section
        $this->start_controls_section('section_placeholders', [
            'label' => __('Placeholders', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('placeholder_search', [
            'label' => __('Search Placeholder', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Search Teachers or Events', 'mec-starter-addons'),
        ]);

        $this->add_control('placeholder_location', [
            'label' => __('Location Placeholder', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('City, State', 'mec-starter-addons'),
        ]);

        $this->end_controls_section();

        // Mobile Display Section
        $this->start_controls_section('section_mobile', [
            'label' => __('Mobile Display', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('mobile_mode', [
            'label' => __('Mobile Display Mode', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'icon_popup',
            'options' => [
                'same' => __('Same as Desktop', 'mec-starter-addons'),
                'icon_popup' => __('Icon Only → Popup', 'mec-starter-addons'),
            ],
            'description' => __('On mobile, show only a search icon that opens a popup when clicked.', 'mec-starter-addons'),
        ]);

        $this->add_control('mobile_breakpoint', [
            'label' => __('Mobile Breakpoint', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 768,
            'min' => 320,
            'max' => 1200,
            'description' => __('Screen width (px) below which mobile mode activates.', 'mec-starter-addons'),
            'condition' => ['mobile_mode' => 'icon_popup'],
        ]);

        $this->end_controls_section();

        // Trigger Button Section (for popup mode)
        $this->start_controls_section('section_trigger', [
            'label' => __('Trigger Button', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            'condition' => ['mode' => 'popup'],
        ]);

        $this->add_control('trigger_text', [
            'label' => __('Button Text', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Search Events', 'mec-starter-addons'),
        ]);

        $this->add_control('trigger_icon', [
            'label' => __('Show Icon', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();

        // Popup Section
        $this->start_controls_section('section_popup', [
            'label' => __('Popup Settings', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('popup_title', [
            'label' => __('Popup Title', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Find Events', 'mec-starter-addons'),
        ]);

        $this->add_control('popup_show_title', [
            'label' => __('Show Title', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('popup_backdrop_blur', [
            'label' => __('Backdrop Blur', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('popup_close_on_backdrop', [
            'label' => __('Close on Backdrop Click', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('popup_animation', [
            'label' => __('Animation', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'fade-scale',
            'options' => [
                'fade-scale' => __('Fade & Scale', 'mec-starter-addons'),
                'slide-up' => __('Slide Up', 'mec-starter-addons'),
                'slide-down' => __('Slide Down', 'mec-starter-addons'),
                'fade' => __('Fade Only', 'mec-starter-addons'),
            ],
        ]);

        $this->end_controls_section();

        // === STYLE TAB ===

        // Trigger Button Style
        $this->start_controls_section('section_style_trigger', [
            'label' => __('Trigger Button', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['mode' => 'popup'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'trigger_typography',
            'selector' => '{{WRAPPER}} .mecas-trigger-button',
        ]);

        $this->start_controls_tabs('trigger_tabs');

        $this->start_controls_tab('trigger_normal', ['label' => __('Normal', 'mec-starter-addons')]);
        $this->add_control('trigger_bg_color', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button' => 'background-color: {{VALUE}} !important;'],
        ]);
        $this->add_control('trigger_text_color', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button' => 'color: {{VALUE}} !important;'],
        ]);
        $this->add_control('trigger_border_color', [
            'label' => __('Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button' => 'border-color: {{VALUE}} !important;'],
        ]);
        $this->end_controls_tab();

        $this->start_controls_tab('trigger_hover', ['label' => __('Hover', 'mec-starter-addons')]);
        $this->add_control('trigger_bg_color_hover', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button:hover' => 'background-color: {{VALUE}} !important;'],
        ]);
        $this->add_control('trigger_text_color_hover', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button:hover' => 'color: {{VALUE}} !important;'],
        ]);
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control('trigger_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'separator' => 'before',
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('trigger_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Search Bar Container Style
        $this->start_controls_section('section_style_search_bar', [
            'label' => __('Search Bar Container', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('search_bar_bg', [
            'label' => __('Background Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#2D3748',
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('search_bar_border_color', [
            'label' => __('Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#4A5568',
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'border-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('search_bar_border_width', [
            'label' => __('Border Width', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 5]],
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'border-width: {{SIZE}}{{UNIT}} !important; border-style: solid !important;'],
        ]);

        $this->add_responsive_control('search_bar_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('search_bar_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('search_bar_min_height', [
            'label' => __('Min Height', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 40, 'max' => 100]],
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'min-height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('search_bar_max_width', [
            'label' => __('Max Width', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => ['px' => ['min' => 300, 'max' => 1200]],
            'selectors' => ['{{WRAPPER}} .mecas-search-wrapper' => 'max-width: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Search Input Field Style
        $this->start_controls_section('section_style_search_input', [
            'label' => __('Search Input Field', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('search_input_text_color', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-query-input' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('search_input_placeholder_color', [
            'label' => __('Placeholder Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#A0AEC0',
            'selectors' => ['{{WRAPPER}} .mecas-query-input::placeholder' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'search_input_typography',
            'selector' => '{{WRAPPER}} .mecas-query-input',
        ]);

        $this->add_responsive_control('search_input_text_align', [
            'label' => __('Text Alignment', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => __('Left', 'mec-starter-addons'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'mec-starter-addons'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => __('Right', 'mec-starter-addons'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'center',
            'selectors' => ['{{WRAPPER}} .mecas-query-input' => 'text-align: {{VALUE}} !important;'],
        ]);

        $this->add_control('heading_search_size', [
            'label' => __('Size & Layout', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('search_input_width', [
            'label' => __('Width', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%', 'px'],
            'range' => [
                'px' => ['min' => 50, 'max' => 500],
                '%' => ['min' => 10, 'max' => 80],
            ],
            'default' => ['size' => 50, 'unit' => '%'],
            'selectors' => ['{{WRAPPER}} .mecas-search-input-group' => 'flex: 0 0 {{SIZE}}{{UNIT}} !important; max-width: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('search_input_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-query-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Location Input Field Style
        $this->start_controls_section('section_style_location_input', [
            'label' => __('Location Input Field', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('location_input_text_color', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-location-input' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('location_input_placeholder_color', [
            'label' => __('Placeholder Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#A0AEC0',
            'selectors' => ['{{WRAPPER}} .mecas-location-input::placeholder' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'location_input_typography',
            'selector' => '{{WRAPPER}} .mecas-location-input',
        ]);

        $this->add_responsive_control('location_input_text_align', [
            'label' => __('Text Alignment', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => __('Left', 'mec-starter-addons'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'mec-starter-addons'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => __('Right', 'mec-starter-addons'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'center',
            'selectors' => ['{{WRAPPER}} .mecas-location-input' => 'text-align: {{VALUE}} !important;'],
        ]);

        $this->add_control('heading_location_size', [
            'label' => __('Size & Layout', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('location_input_width', [
            'label' => __('Width', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%', 'px'],
            'range' => [
                'px' => ['min' => 50, 'max' => 500],
                '%' => ['min' => 10, 'max' => 80],
            ],
            'default' => ['size' => 50, 'unit' => '%'],
            'selectors' => ['{{WRAPPER}} .mecas-location-input-group' => 'flex: 0 0 {{SIZE}}{{UNIT}} !important; max-width: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('location_input_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-location-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Divider Style
        $this->start_controls_section('section_style_divider', [
            'label' => __('Divider', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('show_divider', [
            'label' => __('Show Divider', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'return_value' => 'yes',
        ]);

        $this->add_control('divider_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#4A5568',
            'condition' => ['show_divider' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-divider' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('divider_width', [
            'label' => __('Width', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 1, 'max' => 5]],
            'default' => ['size' => 1, 'unit' => 'px'],
            'condition' => ['show_divider' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-divider' => 'width: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('divider_height', [
            'label' => __('Height', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 10, 'max' => 50]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'condition' => ['show_divider' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-divider' => 'height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('divider_margin', [
            'label' => __('Margin', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'condition' => ['show_divider' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Search Button Style
        $this->start_controls_section('section_style_button', [
            'label' => __('Search Button', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('button_width', [
            'label' => __('Width', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 30, 'max' => 150]],
            'default' => ['size' => 44, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'width: {{SIZE}}{{UNIT}} !important; min-width: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('button_height', [
            'label' => __('Height', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 30, 'max' => 100]],
            'default' => ['size' => 44, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('button_icon_size', [
            'label' => __('Icon Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 12, 'max' => 32]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->start_controls_tabs('button_tabs');

        $this->start_controls_tab('button_normal', ['label' => __('Normal', 'mec-starter-addons')]);
        $this->add_control('button_bg_color', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'background-color: {{VALUE}} !important;'],
        ]);
        $this->add_control('button_icon_color', [
            'label' => __('Icon Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#2D3748',
            'selectors' => ['{{WRAPPER}} .mecas-search-button svg' => 'stroke: {{VALUE}} !important;'],
        ]);
        $this->end_controls_tab();

        $this->start_controls_tab('button_hover', ['label' => __('Hover', 'mec-starter-addons')]);
        $this->add_control('button_bg_color_hover', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-search-button:hover' => 'background-color: {{VALUE}} !important;'],
        ]);
        $this->add_control('button_icon_color_hover', [
            'label' => __('Icon Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-search-button:hover svg' => 'stroke: {{VALUE}} !important;'],
        ]);
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control('heading_button_border', [
            'label' => __('Border', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('button_border_type', [
            'label' => __('Border Type', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'none' => __('None', 'mec-starter-addons'),
                'solid' => __('Solid', 'mec-starter-addons'),
                'dashed' => __('Dashed', 'mec-starter-addons'),
                'dotted' => __('Dotted', 'mec-starter-addons'),
            ],
            'default' => 'none',
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'border-style: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('button_border_width', [
            'label' => __('Border Width', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'condition' => ['button_border_type!' => 'none'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_control('button_border_color', [
            'label' => __('Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'condition' => ['button_border_type!' => 'none'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'border-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('button_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => '%'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('button_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('button_margin', [
            'label' => __('Margin', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Mobile Trigger Icon Style
        $this->start_controls_section('section_style_mobile_trigger', [
            'label' => __('Mobile Trigger Icon', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['mobile_mode' => 'icon_popup'],
        ]);

        $this->add_responsive_control('mobile_icon_size', [
            'label' => __('Icon Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 16, 'max' => 48]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mecas-mobile-trigger svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
            ],
        ]);

        $this->add_responsive_control('mobile_icon_box_size', [
            'label' => __('Box Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 30, 'max' => 80]],
            'default' => ['size' => 44, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mecas-mobile-trigger' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
            ],
        ]);

        $this->add_control('mobile_icon_color', [
            'label' => __('Icon Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#A0AEC0',
            'selectors' => ['{{WRAPPER}} .mecas-mobile-trigger svg' => 'stroke: {{VALUE}} !important;'],
        ]);

        $this->add_control('mobile_icon_bg', [
            'label' => __('Background Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#2D3748',
            'selectors' => ['{{WRAPPER}} .mecas-mobile-trigger' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'mobile_icon_border',
            'selector' => '{{WRAPPER}} .mecas-mobile-trigger',
        ]);

        $this->add_responsive_control('mobile_icon_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => '%'],
            'selectors' => ['{{WRAPPER}} .mecas-mobile-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'mobile_icon_shadow',
            'selector' => '{{WRAPPER}} .mecas-mobile-trigger',
        ]);

        $this->end_controls_section();

        // Popup Style
        $this->start_controls_section('section_style_popup', [
            'label' => __('Popup', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        // Backdrop
        $this->add_control('heading_popup_backdrop', [
            'label' => __('Backdrop', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
        ]);

        $this->add_control('popup_backdrop_color', [
            'label' => __('Backdrop Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => 'rgba(0, 0, 0, 0.6)',
            'selectors' => ['{{WRAPPER}} .mecas-modal-backdrop' => 'background-color: {{VALUE}} !important;'],
        ]);

        // Popup Container
        $this->add_control('heading_popup_container', [
            'label' => __('Popup Container', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('popup_bg_color', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-modal-content' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'popup_border',
            'selector' => '{{WRAPPER}} .mecas-modal-content',
        ]);

        $this->add_responsive_control('popup_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '16', 'right' => '16', 'bottom' => '16', 'left' => '16', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-modal-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'popup_shadow',
            'selector' => '{{WRAPPER}} .mecas-modal-content',
            'fields_options' => [
                'box_shadow_type' => ['default' => 'yes'],
                'box_shadow' => [
                    'default' => [
                        'horizontal' => 0,
                        'vertical' => 10,
                        'blur' => 40,
                        'spread' => 0,
                        'color' => 'rgba(0,0,0,0.15)',
                    ],
                ],
            ],
        ]);

        $this->add_responsive_control('popup_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => ['top' => '32', 'right' => '32', 'bottom' => '32', 'left' => '32', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-modal-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('popup_width', [
            'label' => __('Width', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw'],
            'range' => [
                'px' => ['min' => 280, 'max' => 800],
                '%' => ['min' => 50, 'max' => 100],
                'vw' => ['min' => 50, 'max' => 100],
            ],
            'default' => ['size' => 550, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-modal-content' => 'width: {{SIZE}}{{UNIT}} !important; max-width: calc(100vw - 40px) !important;'],
        ]);

        $this->add_responsive_control('popup_max_height', [
            'label' => __('Max Height', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh'],
            'range' => [
                'px' => ['min' => 200, 'max' => 800],
                '%' => ['min' => 30, 'max' => 100],
                'vh' => ['min' => 30, 'max' => 100],
            ],
            'default' => ['size' => 90, 'unit' => 'vh'],
            'selectors' => ['{{WRAPPER}} .mecas-modal-content' => 'max-height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        // Popup Title
        $this->add_control('heading_popup_title', [
            'label' => __('Title', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('popup_title_color', [
            'label' => __('Title Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-modal-title' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'popup_title_typography',
            'selector' => '{{WRAPPER}} .mecas-modal-title',
        ]);

        $this->add_responsive_control('popup_title_margin', [
            'label' => __('Title Margin', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '0', 'right' => '0', 'bottom' => '24', 'left' => '0', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-modal-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        // Close Button
        $this->add_control('heading_popup_close', [
            'label' => __('Close Button', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('popup_close_size', [
            'label' => __('Button Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 24, 'max' => 48]],
            'default' => ['size' => 32, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-modal-close' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_control('popup_close_color', [
            'label' => __('Icon Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#6B7280',
            'selectors' => ['{{WRAPPER}} .mecas-modal-close' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('popup_close_bg', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#F9FAFB',
            'selectors' => ['{{WRAPPER}} .mecas-modal-close' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('popup_close_hover_color', [
            'label' => __('Hover Icon Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-modal-close:hover' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('popup_close_hover_bg', [
            'label' => __('Hover Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => ['{{WRAPPER}} .mecas-modal-close:hover' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('popup_close_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => '%'],
            'selectors' => ['{{WRAPPER}} .mecas-modal-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        // Popup Search Bar
        $this->add_control('heading_popup_search_bar', [
            'label' => __('Search Bar Inside Popup', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('popup_search_bg', [
            'label' => __('Search Bar Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#F9FAFB',
            'selectors' => ['{{WRAPPER}} .mecas-modal-content .mecas-search-container' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('popup_search_border_color', [
            'label' => __('Search Bar Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => ['{{WRAPPER}} .mecas-modal-content .mecas-search-container' => 'border-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('popup_search_text_color', [
            'label' => __('Input Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-modal-content .mecas-input' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('popup_search_placeholder_color', [
            'label' => __('Input Placeholder Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9CA3AF',
            'selectors' => ['{{WRAPPER}} .mecas-modal-content .mecas-input::placeholder' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('popup_search_border_radius', [
            'label' => __('Search Bar Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-modal-content .mecas-search-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $show_divider = $settings['show_divider'] === 'yes' ? 'true' : 'false';
        $mobile_mode = isset($settings['mobile_mode']) ? $settings['mobile_mode'] : 'icon_popup';
        $mobile_breakpoint = isset($settings['mobile_breakpoint']) ? intval($settings['mobile_breakpoint']) : 768;
        $popup_show_title = isset($settings['popup_show_title']) && $settings['popup_show_title'] === 'yes' ? 'true' : 'false';
        $popup_close_on_backdrop = isset($settings['popup_close_on_backdrop']) && $settings['popup_close_on_backdrop'] === 'yes' ? 'true' : 'false';
        $popup_animation = isset($settings['popup_animation']) ? $settings['popup_animation'] : 'fade-scale';
        
        $atts = array(
            'mode' => $settings['mode'],
            'results_page' => $settings['results_page']['url'] ?? get_option('mecas_results_page', ''),
            'enable_geolocation' => $settings['enable_geolocation'] === 'yes' ? 'true' : 'false',
            'auto_detect_location' => $settings['auto_detect_location'] === 'yes' ? 'true' : 'false',
            'show_suggestions' => $settings['show_suggestions'] === 'yes' ? 'true' : 'false',
            'show_divider' => $show_divider,
            'placeholder_search' => $settings['placeholder_search'],
            'placeholder_location' => $settings['placeholder_location'],
            'trigger_text' => $settings['trigger_text'] ?? '',
            'trigger_icon' => isset($settings['trigger_icon']) && $settings['trigger_icon'] === 'yes' ? 'true' : 'false',
            'popup_title' => $settings['popup_title'] ?? '',
            'popup_show_title' => $popup_show_title,
            'popup_close_on_backdrop' => $popup_close_on_backdrop,
            'popup_animation' => $popup_animation,
            'mobile_mode' => $mobile_mode,
            'mobile_breakpoint' => $mobile_breakpoint,
            'widget_id' => 'mecas-' . $this->get_id(),
        );
        
        echo do_shortcode('[mec_advanced_search ' . $this->build_shortcode_attrs($atts) . ']');
    }

    private function build_shortcode_attrs($atts) {
        $str = '';
        foreach ($atts as $key => $value) {
            $str .= $key . '="' . esc_attr($value) . '" ';
        }
        return $str;
    }

    protected function content_template() {
        ?>
        <#
        var barBg = settings.search_bar_bg || '#2D3748';
        var borderColor = settings.search_bar_border_color || '#4A5568';
        var dividerColor = settings.divider_color || '#4A5568';
        var buttonBg = settings.button_bg_color || '#FFFFFF';
        var buttonIcon = settings.button_icon_color || '#2D3748';
        var searchPlaceholderColor = settings.search_input_placeholder_color || '#A0AEC0';
        var locationPlaceholderColor = settings.location_input_placeholder_color || '#A0AEC0';
        var showDivider = settings.show_divider === 'yes';
        #>
        <div class="mecas-search-wrapper" style="max-width: 600px; margin: 0 auto;">
            <div class="mecas-search-container" style="
                display: flex;
                align-items: center;
                background-color: {{{ barBg }}};
                border: 1px solid {{{ borderColor }}};
                border-radius: 50px;
                padding: 5px 5px 5px 20px;
                min-height: 54px;
            ">
                <div class="mecas-search-input-group" style="flex: 1; display: flex; align-items: center; justify-content: center;">
                    <span style="color: {{{ searchPlaceholderColor }}}; font-size: 15px;">{{{ settings.placeholder_search }}}</span>
                </div>
                <# if (showDivider) { #>
                <div class="mecas-divider" style="width: 1px; height: 24px; background-color: {{{ dividerColor }}}; margin: 0 8px;"></div>
                <# } #>
                <div class="mecas-location-input-group" style="flex: 1; display: flex; align-items: center; justify-content: center;">
                    <span style="color: {{{ locationPlaceholderColor }}}; font-size: 15px;">{{{ settings.placeholder_location }}}</span>
                </div>
                <button type="button" class="mecas-search-button" style="
                    width: 44px;
                    height: 44px;
                    min-width: 44px;
                    border-radius: 50%;
                    background-color: {{{ buttonBg }}};
                    border: none;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-left: 8px;
                    cursor: pointer;
                ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="{{{ buttonIcon }}}" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
            </div>
        </div>
        <?php
    }
}
