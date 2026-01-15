<?php
/**
 * MEC Starter Addons - Elementor Upcoming Events Widget
 * Displays upcoming events in a grid (same design as featured)
 */

if (!defined('ABSPATH')) exit;

class MECAS_Upcoming_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_upcoming';
    }

    public function get_title() {
        return __('MEC Upcoming Events', 'mec-starter-addons');
    }

    public function get_icon() {
        return 'eicon-calendar';
    }

    public function get_categories() {
        return ['mec-starter-addons'];
    }

    protected function register_controls() {
        // === CONTENT TAB ===
        
        // General Section
        $this->start_controls_section('section_general', [
            'label' => __('General', 'mec-starter-addons'),
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
            'label' => __('Number of Events', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 4,
            'min' => 1,
            'max' => 20,
        ]);

        $this->add_control('hide_past_events', [
            'label' => __('Hide Past Events', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('Only show events with start date today or in the future', 'mec-starter-addons'),
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

        $this->add_control('show_price', [
            'label' => __('Show Price Badge', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('currency_symbol', [
            'label' => __('Currency Symbol', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '$',
            'condition' => ['show_price' => 'yes'],
        ]);

        $this->end_controls_section();

        // Category Tabs Section
        $this->start_controls_section('section_category_tabs', [
            'label' => __('Category Tabs', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_category_tabs', [
            'label' => __('Show Category Tabs', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('all_tab_text', [
            'label' => __('All Tab Text', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('All', 'mec-starter-addons'),
            'condition' => ['show_category_tabs' => 'yes'],
        ]);

        $this->end_controls_section();

        // === STYLE TAB ===

        // Category Tabs Style
        $this->start_controls_section('section_style_tabs', [
            'label' => __('Category Tabs', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_category_tabs' => 'yes'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'tabs_typography',
            'selector' => '{{WRAPPER}} .mecas-category-tab',
        ]);

        $this->add_control('tabs_bg_color', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-category-tab' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('tabs_text_color', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#374151',
            'selectors' => ['{{WRAPPER}} .mecas-category-tab' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('tabs_border_color', [
            'label' => __('Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => ['{{WRAPPER}} .mecas-category-tab' => 'border-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('tabs_active_bg', [
            'label' => __('Active Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#D1FAE5',
            'selectors' => ['{{WRAPPER}} .mecas-category-tab.active' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('tabs_active_text', [
            'label' => __('Active Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#065F46',
            'selectors' => ['{{WRAPPER}} .mecas-category-tab.active' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('tabs_active_border', [
            'label' => __('Active Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#6EE7B7',
            'selectors' => ['{{WRAPPER}} .mecas-category-tab.active' => 'border-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('tabs_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-category-tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('tabs_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => ['top' => '8', 'right' => '16', 'bottom' => '8', 'left' => '16', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-category-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('tabs_gap', [
            'label' => __('Gap Between Tabs', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'default' => ['size' => 10, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-category-tabs' => 'gap: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('tabs_margin', [
            'label' => __('Margin', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-category-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Grid Style
        $this->start_controls_section('section_style_grid', [
            'label' => __('Grid', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('grid_gap', [
            'label' => __('Gap', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-featured-grid' => 'gap: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('grid_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-featured-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
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
            'default' => '#FEF9F3',
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

        // Category Badge (on date bar)
        $this->add_control('heading_tag_badge', [
            'label' => __('Category Badge', 'mec-starter-addons'),
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

        $this->add_responsive_control('tag_badge_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => ['top' => '4', 'right' => '10', 'bottom' => '4', 'left' => '10', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-tag-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('tag_badge_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-tag-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
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
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $columns = $settings['columns'];
        $columns_tablet = isset($settings['columns_tablet']) ? $settings['columns_tablet'] : '2';
        $columns_mobile = isset($settings['columns_mobile']) ? $settings['columns_mobile'] : '1';
        
        $atts = array(
            'columns' => $columns,
            'columns_tablet' => $columns_tablet,
            'columns_mobile' => $columns_mobile,
            'per_page' => $settings['per_page'],
            'hide_past_events' => $settings['hide_past_events'] === 'yes' ? 'true' : 'false',
            'date_format' => $settings['date_format'],
            'time_format' => $settings['time_format'],
            'hosted_by_text' => $settings['hosted_by_text'],
            'currency_symbol' => $settings['currency_symbol'],
            'show_price' => $settings['show_price'] === 'yes' ? 'true' : 'false',
            'show_category_tabs' => $settings['show_category_tabs'] === 'yes' ? 'true' : 'false',
            'all_tab_text' => $settings['all_tab_text'],
            'widget_id' => 'mecas-upcoming-' . $this->get_id(),
        );
        
        echo do_shortcode('[mec_upcoming_events ' . $this->build_shortcode_attrs($atts) . ']');
    }

    private function build_shortcode_attrs($atts) {
        $str = '';
        foreach ($atts as $key => $value) {
            $str .= $key . '="' . esc_attr($value) . '" ';
        }
        return $str;
    }
}
