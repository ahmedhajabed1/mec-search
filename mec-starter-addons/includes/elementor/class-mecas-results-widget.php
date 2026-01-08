<?php
/**
 * MEC Starter Addons - Elementor Results Widget
 * Search results with category tabs, filters, and redesigned event cards
 */

if (!defined('ABSPATH')) exit;

class MECAS_Results_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_results';
    }

    public function get_title() {
        return __('MEC Search Results', 'mec-starter-addons');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['mec-starter-addons'];
    }

    protected function register_controls() {
        // === CONTENT TAB ===
        
        // Search Bar Section
        $this->start_controls_section('section_search_bar', [
            'label' => __('Search Bar', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_search_bar', [
            'label' => __('Show Search Bar', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('enable_geolocation', [
            'label' => __('Enable Geolocation', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_search_bar' => 'yes'],
        ]);

        $this->add_control('placeholder_search', [
            'label' => __('Search Placeholder', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Search events', 'mec-starter-addons'),
            'condition' => ['show_search_bar' => 'yes'],
        ]);

        $this->add_control('placeholder_location', [
            'label' => __('Location Placeholder', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('City, State', 'mec-starter-addons'),
            'condition' => ['show_search_bar' => 'yes'],
        ]);

        $this->end_controls_section();

        // Filters Section
        $this->start_controls_section('section_filters', [
            'label' => __('Filters', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_filters', [
            'label' => __('Show Filters', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_category_filter', [
            'label' => __('Category Filter', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_filters' => 'yes'],
        ]);

        $this->add_control('label_category', [
            'label' => __('Category Label', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('All Categories', 'mec-starter-addons'),
            'condition' => ['show_filters' => 'yes', 'show_category_filter' => 'yes'],
        ]);

        $this->add_control('show_organizer_filter', [
            'label' => __('Organizer Filter', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_filters' => 'yes'],
        ]);

        $this->add_control('label_organizer', [
            'label' => __('Organizer Label', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('All Organizers', 'mec-starter-addons'),
            'condition' => ['show_filters' => 'yes', 'show_organizer_filter' => 'yes'],
        ]);

        $this->add_control('show_tag_filter', [
            'label' => __('Tag Filter', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_filters' => 'yes'],
        ]);

        $this->add_control('label_tag', [
            'label' => __('Tag Label', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('All Tags', 'mec-starter-addons'),
            'condition' => ['show_filters' => 'yes', 'show_tag_filter' => 'yes'],
        ]);

        $this->add_control('show_sort_filter', [
            'label' => __('Sort Filter', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_filters' => 'yes'],
        ]);

        $this->add_control('label_sort', [
            'label' => __('Sort Label', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Sort By', 'mec-starter-addons'),
            'condition' => ['show_filters' => 'yes', 'show_sort_filter' => 'yes'],
        ]);

        $this->end_controls_section();

        // Results Section
        $this->start_controls_section('section_results', [
            'label' => __('Results Grid', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_responsive_control('columns', [
            'label' => __('Columns', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '4',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'],
        ]);

        $this->add_control('per_page', [
            'label' => __('Events Per Page', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 12,
            'min' => 1,
            'max' => 50,
        ]);

        $this->add_control('show_pagination', [
            'label' => __('Show Pagination', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('no_results_text', [
            'label' => __('No Results Text', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('No events found matching your criteria.', 'mec-starter-addons'),
        ]);

        $this->end_controls_section();

        // Event Card Content Section
        $this->start_controls_section('section_card_content', [
            'label' => __('Event Card Content', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('date_format', [
            'label' => __('Date Format', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'D, M j',
            'description' => __('PHP date format. D = day, M = month, j = day number', 'mec-starter-addons'),
        ]);

        $this->add_control('time_format', [
            'label' => __('Time Format', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'g:i A T',
            'description' => __('PHP time format. g = hour, i = minutes, A = AM/PM, T = timezone', 'mec-starter-addons'),
        ]);

        $this->add_control('hosted_by_text', [
            'label' => __('Hosted By Text', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Hosted by', 'mec-starter-addons'),
        ]);

        $this->add_control('currency_symbol', [
            'label' => __('Currency Symbol', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '$',
        ]);

        $this->end_controls_section();

        // === STYLE TAB ===

        // Search Bar Style
        $this->start_controls_section('section_style_search', [
            'label' => __('Search Bar', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_search_bar' => 'yes'],
        ]);

        $this->add_control('search_bg_color', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('search_border_color', [
            'label' => __('Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'border-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('search_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_control('search_input_color', [
            'label' => __('Input Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-input' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('search_placeholder_color', [
            'label' => __('Placeholder Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9CA3AF',
            'selectors' => ['{{WRAPPER}} .mecas-input::placeholder' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('search_divider_color', [
            'label' => __('Divider Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => ['{{WRAPPER}} .mecas-divider' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('heading_search_button', [
            'label' => __('Search Button', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('search_button_bg', [
            'label' => __('Button Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#D97706',
            'selectors' => ['{{WRAPPER}} .mecas-search-button' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('search_button_icon_color', [
            'label' => __('Button Icon Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-search-button svg' => 'stroke: {{VALUE}} !important;'],
        ]);

        $this->add_control('search_button_bg_hover', [
            'label' => __('Button Hover Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#B45309',
            'selectors' => ['{{WRAPPER}} .mecas-search-button:hover' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('search_button_size', [
            'label' => __('Button Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 30, 'max' => 60]],
            'default' => ['size' => 44, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mecas-search-button' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; min-width: {{SIZE}}{{UNIT}} !important;',
            ],
        ]);

        $this->add_responsive_control('search_margin', [
            'label' => __('Margin', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-search-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Filters Style
        $this->start_controls_section('section_style_filters', [
            'label' => __('Filters', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_filters' => 'yes'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'filters_typography',
            'selector' => '{{WRAPPER}} .mecas-filter-select',
        ]);

        $this->add_control('filters_bg_color', [
            'label' => __('Dropdown Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-filter-select' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('filters_text_color', [
            'label' => __('Dropdown Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#374151',
            'selectors' => ['{{WRAPPER}} .mecas-filter-select' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('filters_border_color', [
            'label' => __('Dropdown Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => ['{{WRAPPER}} .mecas-filter-select' => 'border-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('filters_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-filter-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('filters_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-filter-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('filters_gap', [
            'label' => __('Gap Between Filters', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 15, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-filters-row' => 'gap: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('filters_margin', [
            'label' => __('Margin', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-filters-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Results Grid Style
        $this->start_controls_section('section_style_results', [
            'label' => __('Results Grid', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('results_gap', [
            'label' => __('Gap', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-results-grid' => 'gap: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('results_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-results-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Event Card Style
        $this->start_controls_section('section_style_cards', [
            'label' => __('Event Cards', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        // Card Container
        $this->add_control('heading_card_container', [
            'label' => __('Card Container', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
        ]);

        $this->add_control('card_bg_color', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-event-card' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'card_border',
            'label' => __('Border', 'mec-starter-addons'),
            'selector' => '{{WRAPPER}} .mecas-event-card',
            'fields_options' => [
                'border' => ['default' => 'solid'],
                'width' => ['default' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'isLinked' => true]],
                'color' => ['default' => '#E5E7EB'],
            ],
        ]);

        $this->add_responsive_control('card_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-event-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'label' => __('Box Shadow', 'mec-starter-addons'),
            'selector' => '{{WRAPPER}} .mecas-event-card',
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => __('Card Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-event-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        // Card Hover State
        $this->add_control('heading_card_hover', [
            'label' => __('Card Hover', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('card_hover_bg_color', [
            'label' => __('Hover Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-event-card:hover' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('card_hover_border_color', [
            'label' => __('Hover Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-event-card:hover' => 'border-color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_hover_shadow',
            'label' => __('Hover Box Shadow', 'mec-starter-addons'),
            'selector' => '{{WRAPPER}} .mecas-event-card:hover',
        ]);

        $this->add_control('card_hover_transform', [
            'label' => __('Hover Lift Effect', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'selectors' => [
                '{{WRAPPER}} .mecas-event-card' => 'transition: all 0.3s ease;',
                '{{WRAPPER}} .mecas-event-card:hover' => 'transform: translateY(-4px);',
            ],
        ]);

        // Card Image
        $this->add_control('heading_card_image', [
            'label' => __('Card Image', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('card_image_height', [
            'label' => __('Image Height', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 100, 'max' => 400]],
            'default' => ['size' => 180, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mecas-card-image' => 'height: {{SIZE}}{{UNIT}} !important;',
                '{{WRAPPER}} .mecas-card-image-placeholder' => 'height: {{SIZE}}{{UNIT}} !important;',
            ],
        ]);

        $this->add_responsive_control('card_image_radius', [
            'label' => __('Image Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '12', 'right' => '12', 'bottom' => '0', 'left' => '0', 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mecas-card-image-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important; overflow: hidden;',
                '{{WRAPPER}} .mecas-card-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
            ],
        ]);

        // Price Badge
        $this->add_control('heading_price_badge', [
            'label' => __('Price Badge', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('price_badge_bg', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#D1FAE5',
            'selectors' => ['{{WRAPPER}} .mecas-price-badge' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('price_badge_text', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#065F46',
            'selectors' => ['{{WRAPPER}} .mecas-price-badge' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'price_badge_typography',
            'selector' => '{{WRAPPER}} .mecas-price-badge',
        ]);

        $this->add_responsive_control('price_badge_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '6', 'right' => '12', 'bottom' => '6', 'left' => '12', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-price-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('price_badge_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-price-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        // Date/Time Bar
        $this->add_control('heading_date_bar', [
            'label' => __('Date/Time Bar', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('date_bar_bg', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => 'rgba(0, 0, 0, 0.5)',
            'selectors' => ['{{WRAPPER}} .mecas-date-bar' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('date_bar_text', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-date-bar' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'date_bar_typography',
            'selector' => '{{WRAPPER}} .mecas-date-bar',
        ]);

        // Tag Badge
        $this->add_control('heading_tag_badge', [
            'label' => __('Tag Badge', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('tag_badge_bg', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#D1FAE5',
            'selectors' => ['{{WRAPPER}} .mecas-tag-badge' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('tag_badge_text', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#065F46',
            'selectors' => ['{{WRAPPER}} .mecas-tag-badge' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'tag_badge_typography',
            'selector' => '{{WRAPPER}} .mecas-tag-badge',
        ]);

        // Card Content
        $this->add_control('heading_card_content', [
            'label' => __('Card Content', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('card_content_padding', [
            'label' => __('Content Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => ['top' => '16', 'right' => '16', 'bottom' => '16', 'left' => '16', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        // Title
        $this->add_control('heading_card_title', [
            'label' => __('Title', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('card_title_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-card-title' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'card_title_typography',
            'selector' => '{{WRAPPER}} .mecas-card-title',
        ]);

        $this->add_responsive_control('card_title_margin', [
            'label' => __('Margin', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors' => ['{{WRAPPER}} .mecas-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        // Location
        $this->add_control('heading_card_location', [
            'label' => __('Location', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('card_location_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#6B7280',
            'selectors' => ['{{WRAPPER}} .mecas-card-location' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'card_location_typography',
            'selector' => '{{WRAPPER}} .mecas-card-location',
        ]);

        // Organizer
        $this->add_control('heading_card_organizer', [
            'label' => __('Hosted By', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('card_organizer_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9333EA',
            'selectors' => ['{{WRAPPER}} .mecas-card-organizer' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'card_organizer_typography',
            'selector' => '{{WRAPPER}} .mecas-card-organizer',
        ]);

        $this->end_controls_section();

        // Pagination Style
        $this->start_controls_section('section_style_pagination', [
            'label' => __('Pagination', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_pagination' => 'yes'],
        ]);

        $this->add_responsive_control('pagination_alignment', [
            'label' => __('Alignment', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => __('Left', 'mec-starter-addons'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'mec-starter-addons'), 'icon' => 'eicon-text-align-center'],
                'flex-end' => ['title' => __('Right', 'mec-starter-addons'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'center',
            'selectors' => ['{{WRAPPER}} .mecas-pagination' => 'justify-content: {{VALUE}} !important;'],
        ]);

        $this->add_control('pagination_bg', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-pagination a, {{WRAPPER}} .mecas-pagination span' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('pagination_color', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#374151',
            'selectors' => ['{{WRAPPER}} .mecas-pagination a, {{WRAPPER}} .mecas-pagination span' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('pagination_active_bg', [
            'label' => __('Active Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-pagination .current' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('pagination_active_color', [
            'label' => __('Active Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-pagination .current' => 'color: {{VALUE}} !important;'],
        ]);

        $this->end_controls_section();

        // No Results Style
        $this->start_controls_section('section_style_no_results', [
            'label' => __('No Results', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('no_results_color', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#6B7280',
            'selectors' => ['{{WRAPPER}} .mecas-no-results' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'no_results_typography',
            'selector' => '{{WRAPPER}} .mecas-no-results',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $columns = $settings['columns'];
        $columns_tablet = isset($settings['columns_tablet']) ? $settings['columns_tablet'] : '2';
        $columns_mobile = isset($settings['columns_mobile']) ? $settings['columns_mobile'] : '1';
        
        // Handle backwards compatibility - default to 'yes' if not set
        $show_category_filter = isset($settings['show_category_filter']) ? $settings['show_category_filter'] : 'yes';
        $show_organizer_filter = isset($settings['show_organizer_filter']) ? $settings['show_organizer_filter'] : 'yes';
        $show_tag_filter = isset($settings['show_tag_filter']) ? $settings['show_tag_filter'] : 'yes';
        $show_sort_filter = isset($settings['show_sort_filter']) ? $settings['show_sort_filter'] : 'yes';
        
        $atts = array(
            'show_search_bar' => $settings['show_search_bar'] === 'yes' ? 'true' : 'false',
            'enable_geolocation' => $settings['enable_geolocation'] === 'yes' ? 'true' : 'false',
            'placeholder_search' => $settings['placeholder_search'] ?? __('Search events', 'mec-starter-addons'),
            'placeholder_location' => $settings['placeholder_location'] ?? __('City, State', 'mec-starter-addons'),
            'show_filters' => $settings['show_filters'] === 'yes' ? 'true' : 'false',
            'show_category_filter' => $show_category_filter === 'yes' ? 'true' : 'false',
            'show_organizer_filter' => $show_organizer_filter === 'yes' ? 'true' : 'false',
            'show_tag_filter' => $show_tag_filter === 'yes' ? 'true' : 'false',
            'show_sort_filter' => $show_sort_filter === 'yes' ? 'true' : 'false',
            'label_category' => $settings['label_category'] ?? __('All Categories', 'mec-starter-addons'),
            'label_organizer' => $settings['label_organizer'] ?? __('All Organizers', 'mec-starter-addons'),
            'label_tag' => $settings['label_tag'] ?? __('All Tags', 'mec-starter-addons'),
            'label_sort' => $settings['label_sort'] ?? __('Sort By', 'mec-starter-addons'),
            'columns' => $columns,
            'columns_tablet' => $columns_tablet,
            'columns_mobile' => $columns_mobile,
            'per_page' => $settings['per_page'] ?? 12,
            'show_pagination' => $settings['show_pagination'] === 'yes' ? 'true' : 'false',
            'no_results_text' => $settings['no_results_text'] ?? __('No events found.', 'mec-starter-addons'),
            'date_format' => $settings['date_format'] ?? 'D, M j',
            'time_format' => $settings['time_format'] ?? 'g:i A T',
            'hosted_by_text' => $settings['hosted_by_text'] ?? __('Hosted by', 'mec-starter-addons'),
            'currency_symbol' => $settings['currency_symbol'] ?? '$',
            'widget_id' => 'mecas-results-' . $this->get_id(),
        );
        
        echo do_shortcode('[mec_search_results ' . $this->build_shortcode_attrs($atts) . ']');
    }

    private function build_shortcode_attrs($atts) {
        $str = '';
        foreach ($atts as $key => $value) {
            $str .= $key . '="' . esc_attr($value) . '" ';
        }
        return $str;
    }
}
