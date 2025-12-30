<?php
/**
 * MEC Advanced Search - Elementor Search Widget
 * Original Dark Design with Auto Geolocation
 */

if (!defined('ABSPATH')) exit;

class MECAS_Search_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_search';
    }

    public function get_title() {
        return __('MEC Event Search', 'mec-advanced-search');
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return ['mec-advanced-search'];
    }

    public function get_keywords() {
        return ['search', 'events', 'mec', 'calendar', 'location'];
    }

    protected function register_controls() {
        // === CONTENT TAB ===
        
        // General Section
        $this->start_controls_section('section_general', [
            'label' => __('General', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('mode', [
            'label' => __('Display Mode', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'inline',
            'options' => [
                'inline' => __('Inline (Direct)', 'mec-advanced-search'),
                'popup' => __('Popup (Modal)', 'mec-advanced-search'),
            ],
        ]);

        $this->add_control('results_page', [
            'label' => __('Results Page URL', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => home_url('/events-search/'),
            'default' => ['url' => get_option('mecas_results_page', '')],
        ]);

        $this->add_control('enable_geolocation', [
            'label' => __('Enable Geolocation', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'mec-advanced-search'),
            'label_off' => __('No', 'mec-advanced-search'),
            'default' => 'yes',
        ]);

        $this->add_control('auto_detect_location', [
            'label' => __('Auto-Detect Location', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'mec-advanced-search'),
            'label_off' => __('No', 'mec-advanced-search'),
            'default' => 'yes',
            'description' => __('Automatically detect user location on page load', 'mec-advanced-search'),
            'condition' => ['enable_geolocation' => 'yes'],
        ]);

        $this->add_control('show_suggestions', [
            'label' => __('Show Suggestions', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();

        // Placeholders Section
        $this->start_controls_section('section_placeholders', [
            'label' => __('Placeholders', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('placeholder_search', [
            'label' => __('Search Placeholder', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Search Teachers or Events', 'mec-advanced-search'),
        ]);

        $this->add_control('placeholder_location', [
            'label' => __('Location Placeholder', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('City, State', 'mec-advanced-search'),
        ]);

        $this->end_controls_section();

        // Trigger Button Section (for popup mode)
        $this->start_controls_section('section_trigger', [
            'label' => __('Trigger Button', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            'condition' => ['mode' => 'popup'],
        ]);

        $this->add_control('trigger_text', [
            'label' => __('Button Text', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Search Events', 'mec-advanced-search'),
        ]);

        $this->add_control('trigger_icon', [
            'label' => __('Show Icon', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();

        // Popup Section
        $this->start_controls_section('section_popup', [
            'label' => __('Popup Settings', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            'condition' => ['mode' => 'popup'],
        ]);

        $this->add_control('popup_title', [
            'label' => __('Popup Title', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Find Events', 'mec-advanced-search'),
        ]);

        $this->add_control('popup_backdrop_blur', [
            'label' => __('Backdrop Blur', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();

        // === STYLE TAB ===

        // Trigger Button Style
        $this->start_controls_section('section_style_trigger', [
            'label' => __('Trigger Button', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['mode' => 'popup'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'trigger_typography',
            'selector' => '{{WRAPPER}} .mecas-trigger-button',
        ]);

        $this->start_controls_tabs('trigger_tabs');

        $this->start_controls_tab('trigger_normal', ['label' => __('Normal', 'mec-advanced-search')]);
        $this->add_control('trigger_bg_color', [
            'label' => __('Background', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button' => 'background-color: {{VALUE}} !important;'],
        ]);
        $this->add_control('trigger_text_color', [
            'label' => __('Text Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button' => 'color: {{VALUE}} !important;'],
        ]);
        $this->add_control('trigger_border_color', [
            'label' => __('Border Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button' => 'border-color: {{VALUE}} !important;'],
        ]);
        $this->end_controls_tab();

        $this->start_controls_tab('trigger_hover', ['label' => __('Hover', 'mec-advanced-search')]);
        $this->add_control('trigger_bg_color_hover', [
            'label' => __('Background', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button:hover' => 'background-color: {{VALUE}} !important;'],
        ]);
        $this->add_control('trigger_text_color_hover', [
            'label' => __('Text Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button:hover' => 'color: {{VALUE}} !important;'],
        ]);
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control('trigger_border_radius', [
            'label' => __('Border Radius', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'separator' => 'before',
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('trigger_padding', [
            'label' => __('Padding', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-trigger-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Search Bar Container Style
        $this->start_controls_section('section_style_search_bar', [
            'label' => __('Search Bar Container', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('search_bar_bg', [
            'label' => __('Background Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#2D3748',
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('search_bar_border_color', [
            'label' => __('Border Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#4A5568',
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'border-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('search_bar_border_width', [
            'label' => __('Border Width', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 5]],
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'border-width: {{SIZE}}{{UNIT}} !important; border-style: solid !important;'],
        ]);

        $this->add_responsive_control('search_bar_border_radius', [
            'label' => __('Border Radius', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('search_bar_padding', [
            'label' => __('Padding', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('search_bar_min_height', [
            'label' => __('Min Height', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 40, 'max' => 100]],
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'min-height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('search_bar_max_width', [
            'label' => __('Max Width', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => ['px' => ['min' => 300, 'max' => 1200]],
            'selectors' => ['{{WRAPPER}} .mecas-search-wrapper' => 'max-width: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Search Input Field Style
        $this->start_controls_section('section_style_search_input', [
            'label' => __('Search Input Field', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('search_input_text_color', [
            'label' => __('Text Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-query-input' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('search_input_placeholder_color', [
            'label' => __('Placeholder Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#A0AEC0',
            'selectors' => ['{{WRAPPER}} .mecas-query-input::placeholder' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'search_input_typography',
            'selector' => '{{WRAPPER}} .mecas-query-input',
        ]);

        $this->add_responsive_control('search_input_text_align', [
            'label' => __('Text Alignment', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => __('Left', 'mec-advanced-search'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'mec-advanced-search'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => __('Right', 'mec-advanced-search'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'center',
            'selectors' => ['{{WRAPPER}} .mecas-query-input' => 'text-align: {{VALUE}} !important;'],
        ]);

        $this->add_control('heading_search_size', [
            'label' => __('Size & Layout', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('search_input_width', [
            'label' => __('Width', 'mec-advanced-search'),
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
            'label' => __('Padding', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-query-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Location Input Field Style
        $this->start_controls_section('section_style_location_input', [
            'label' => __('Location Input Field', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('location_input_text_color', [
            'label' => __('Text Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-location-input' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('location_input_placeholder_color', [
            'label' => __('Placeholder Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#A0AEC0',
            'selectors' => ['{{WRAPPER}} .mecas-location-input::placeholder' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'location_input_typography',
            'selector' => '{{WRAPPER}} .mecas-location-input',
        ]);

        $this->add_responsive_control('location_input_text_align', [
            'label' => __('Text Alignment', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => __('Left', 'mec-advanced-search'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'mec-advanced-search'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => __('Right', 'mec-advanced-search'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'center',
            'selectors' => ['{{WRAPPER}} .mecas-location-input' => 'text-align: {{VALUE}} !important;'],
        ]);

        $this->add_control('heading_location_size', [
            'label' => __('Size & Layout', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('location_input_width', [
            'label' => __('Width', 'mec-advanced-search'),
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
            'label' => __('Padding', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-location-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Divider Style
        $this->start_controls_section('section_style_divider', [
            'label' => __('Divider', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('show_divider', [
            'label' => __('Show Divider', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'return_value' => 'yes',
        ]);

        $this->add_control('divider_color', [
            'label' => __('Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#4A5568',
            'condition' => ['show_divider' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-divider' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('divider_width', [
            'label' => __('Width', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 1, 'max' => 5]],
            'default' => ['size' => 1, 'unit' => 'px'],
            'condition' => ['show_divider' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-divider' => 'width: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('divider_height', [
            'label' => __('Height', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 10, 'max' => 50]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'condition' => ['show_divider' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-divider' => 'height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('divider_margin', [
            'label' => __('Margin', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'condition' => ['show_divider' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Search Button Style
        $this->start_controls_section('section_style_button', [
            'label' => __('Search Button', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('button_width', [
            'label' => __('Width', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 30, 'max' => 150]],
            'default' => ['size' => 44, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'width: {{SIZE}}{{UNIT}} !important; min-width: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('button_height', [
            'label' => __('Height', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 30, 'max' => 100]],
            'default' => ['size' => 44, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('button_icon_size', [
            'label' => __('Icon Size', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 12, 'max' => 32]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->start_controls_tabs('button_tabs');

        $this->start_controls_tab('button_normal', ['label' => __('Normal', 'mec-advanced-search')]);
        $this->add_control('button_bg_color', [
            'label' => __('Background', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'background-color: {{VALUE}} !important;'],
        ]);
        $this->add_control('button_icon_color', [
            'label' => __('Icon Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#2D3748',
            'selectors' => ['{{WRAPPER}} .mecas-search-button svg' => 'stroke: {{VALUE}} !important;'],
        ]);
        $this->end_controls_tab();

        $this->start_controls_tab('button_hover', ['label' => __('Hover', 'mec-advanced-search')]);
        $this->add_control('button_bg_color_hover', [
            'label' => __('Background', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-search-button:hover' => 'background-color: {{VALUE}} !important;'],
        ]);
        $this->add_control('button_icon_color_hover', [
            'label' => __('Icon Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-search-button:hover svg' => 'stroke: {{VALUE}} !important;'],
        ]);
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control('heading_button_border', [
            'label' => __('Border', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('button_border_type', [
            'label' => __('Border Type', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'none' => __('None', 'mec-advanced-search'),
                'solid' => __('Solid', 'mec-advanced-search'),
                'dashed' => __('Dashed', 'mec-advanced-search'),
                'dotted' => __('Dotted', 'mec-advanced-search'),
            ],
            'default' => 'none',
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'border-style: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('button_border_width', [
            'label' => __('Border Width', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'condition' => ['button_border_type!' => 'none'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_control('button_border_color', [
            'label' => __('Border Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'condition' => ['button_border_type!' => 'none'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'border-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('button_border_radius', [
            'label' => __('Border Radius', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => '%'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('button_padding', [
            'label' => __('Padding', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('button_margin', [
            'label' => __('Margin', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Popup Style
        $this->start_controls_section('section_style_popup', [
            'label' => __('Popup', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['mode' => 'popup'],
        ]);

        $this->add_control('popup_backdrop_color', [
            'label' => __('Backdrop Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-modal-backdrop' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('popup_bg_color', [
            'label' => __('Content Background', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-modal-content' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('popup_border_radius', [
            'label' => __('Border Radius', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors' => ['{{WRAPPER}} .mecas-modal-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('popup_padding', [
            'label' => __('Padding', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-modal-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('popup_max_width', [
            'label' => __('Max Width', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => ['px' => ['min' => 300, 'max' => 800]],
            'selectors' => ['{{WRAPPER}} .mecas-modal-content' => 'max-width: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_control('popup_title_color', [
            'label' => __('Title Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'separator' => 'before',
            'selectors' => ['{{WRAPPER}} .mecas-modal-title' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'popup_title_typography',
            'selector' => '{{WRAPPER}} .mecas-modal-title',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $show_divider = $settings['show_divider'] === 'yes' ? 'true' : 'false';
        
        $atts = array(
            'mode' => $settings['mode'],
            'results_page' => $settings['results_page']['url'] ?? get_option('mecas_results_page', ''),
            'enable_geolocation' => $settings['enable_geolocation'] === 'yes' ? 'true' : 'false',
            'auto_detect_location' => $settings['auto_detect_location'] === 'yes' ? 'true' : 'false',
            'show_suggestions' => $settings['show_suggestions'] === 'yes' ? 'true' : 'false',
            'show_divider' => $show_divider,
            'placeholder_search' => $settings['placeholder_search'],
            'placeholder_location' => $settings['placeholder_location'],
            'trigger_text' => $settings['trigger_text'],
            'trigger_icon' => $settings['trigger_icon'] === 'yes' ? 'true' : 'false',
            'popup_title' => $settings['popup_title'],
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
