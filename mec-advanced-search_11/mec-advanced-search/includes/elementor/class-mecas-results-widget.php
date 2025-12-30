<?php
/**
 * MEC Advanced Search - Elementor Results Widget
 * Search results with filters
 */

if (!defined('ABSPATH')) exit;

class MECAS_Results_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_results';
    }

    public function get_title() {
        return __('MEC Search Results', 'mec-advanced-search');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['mec-advanced-search'];
    }

    protected function register_controls() {
        // === CONTENT TAB ===
        
        // Search Bar Section
        $this->start_controls_section('section_search_bar', [
            'label' => __('Search Bar', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_search_bar', [
            'label' => __('Show Search Bar', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('enable_geolocation', [
            'label' => __('Enable Geolocation', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_search_bar' => 'yes'],
        ]);

        $this->add_control('placeholder_search', [
            'label' => __('Search Placeholder', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Search events', 'mec-advanced-search'),
            'condition' => ['show_search_bar' => 'yes'],
        ]);

        $this->add_control('placeholder_location', [
            'label' => __('Location Placeholder', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('City, State', 'mec-advanced-search'),
            'condition' => ['show_search_bar' => 'yes'],
        ]);

        $this->end_controls_section();

        // Filters Section
        $this->start_controls_section('section_filters', [
            'label' => __('Filters', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_filters', [
            'label' => __('Show Filters', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('filter_layout', [
            'label' => __('Filter Layout', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'horizontal',
            'options' => [
                'horizontal' => __('Horizontal', 'mec-advanced-search'),
                'vertical' => __('Vertical', 'mec-advanced-search'),
            ],
            'condition' => ['show_filters' => 'yes'],
        ]);

        $this->add_control('show_category_filter', [
            'label' => __('Category Filter', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_filters' => 'yes'],
        ]);

        $this->add_control('label_category', [
            'label' => __('Category Label', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Category', 'mec-advanced-search'),
            'condition' => ['show_filters' => 'yes', 'show_category_filter' => 'yes'],
        ]);

        $this->add_control('show_label_filter', [
            'label' => __('Label Filter', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_filters' => 'yes'],
        ]);

        $this->add_control('label_label', [
            'label' => __('Label Text', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Label', 'mec-advanced-search'),
            'condition' => ['show_filters' => 'yes', 'show_label_filter' => 'yes'],
        ]);

        $this->add_control('show_organizer_filter', [
            'label' => __('Organizer Filter', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_filters' => 'yes'],
        ]);

        $this->add_control('label_organizer', [
            'label' => __('Organizer Label', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Organizer', 'mec-advanced-search'),
            'condition' => ['show_filters' => 'yes', 'show_organizer_filter' => 'yes'],
        ]);

        $this->add_control('show_tag_filter', [
            'label' => __('Tag Filter', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_filters' => 'yes'],
        ]);

        $this->add_control('label_tag', [
            'label' => __('Tag Label', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Tag', 'mec-advanced-search'),
            'condition' => ['show_filters' => 'yes', 'show_tag_filter' => 'yes'],
        ]);

        $this->add_control('label_clear', [
            'label' => __('Clear Button Text', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Clear Filters', 'mec-advanced-search'),
            'condition' => ['show_filters' => 'yes'],
        ]);

        $this->end_controls_section();

        // Results Section
        $this->start_controls_section('section_results', [
            'label' => __('Results', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('layout', [
            'label' => __('Layout', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'grid',
            'options' => [
                'grid' => __('Grid', 'mec-advanced-search'),
                'list' => __('List', 'mec-advanced-search'),
            ],
        ]);

        $this->add_responsive_control('columns', [
            'label' => __('Columns', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '3',
            'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'],
            'condition' => ['layout' => 'grid'],
        ]);

        $this->add_control('per_page', [
            'label' => __('Events Per Page', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 12,
            'min' => 1,
            'max' => 50,
        ]);

        $this->add_control('show_pagination', [
            'label' => __('Show Pagination', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('no_results_text', [
            'label' => __('No Results Text', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('No events found matching your criteria.', 'mec-advanced-search'),
        ]);

        $this->end_controls_section();

        // === STYLE TAB ===

        // Search Bar Style
        $this->start_controls_section('section_style_search', [
            'label' => __('Search Bar', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_search_bar' => 'yes'],
        ]);

        $this->add_control('search_bg_color', [
            'label' => __('Background', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'search_border',
            'selector' => '{{WRAPPER}} .mecas-search-container',
        ]);

        $this->add_responsive_control('search_border_radius', [
            'label' => __('Border Radius', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => ['{{WRAPPER}} .mecas-search-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('search_margin', [
            'label' => __('Margin', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-search-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // Filters Style
        $this->start_controls_section('section_style_filters', [
            'label' => __('Filters', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_filters' => 'yes'],
        ]);

        $this->add_control('filters_bg_color', [
            'label' => __('Background', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-filters' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('filters_padding', [
            'label' => __('Padding', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('filters_margin', [
            'label' => __('Margin', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-filters' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('filters_gap', [
            'label' => __('Gap Between Filters', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => ['{{WRAPPER}} .mecas-filters' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('filter_dropdown_heading', [
            'label' => __('Dropdowns', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('dropdown_bg_color', [
            'label' => __('Background', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-filter-select' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('dropdown_text_color', [
            'label' => __('Text Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-filter-select' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'dropdown_typography',
            'selector' => '{{WRAPPER}} .mecas-filter-select',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'dropdown_border',
            'selector' => '{{WRAPPER}} .mecas-filter-select',
        ]);

        $this->add_responsive_control('dropdown_border_radius', [
            'label' => __('Border Radius', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors' => ['{{WRAPPER}} .mecas-filter-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('dropdown_padding', [
            'label' => __('Padding', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-filter-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('clear_button_heading', [
            'label' => __('Clear Button', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('clear_btn_bg', [
            'label' => __('Background', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-clear-filters' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('clear_btn_color', [
            'label' => __('Text Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-clear-filters' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // Results Grid Style
        $this->start_controls_section('section_style_results', [
            'label' => __('Results Grid', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('results_gap', [
            'label' => __('Gap', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => ['{{WRAPPER}} .mecas-results-grid' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('results_padding', [
            'label' => __('Padding', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-results-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // Event Card Style
        $this->start_controls_section('section_style_cards', [
            'label' => __('Event Cards', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg_color', [
            'label' => __('Background', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-event-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'card_border',
            'selector' => '{{WRAPPER}} .mecas-event-card',
        ]);

        $this->add_responsive_control('card_border_radius', [
            'label' => __('Border Radius', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors' => ['{{WRAPPER}} .mecas-event-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .mecas-event-card',
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => __('Padding', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-event-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('card_title_heading', [
            'label' => __('Title', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('card_title_color', [
            'label' => __('Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-event-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'card_title_typography',
            'selector' => '{{WRAPPER}} .mecas-event-title',
        ]);

        $this->add_control('card_meta_heading', [
            'label' => __('Meta (Date, Location)', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('card_meta_color', [
            'label' => __('Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-event-meta' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'card_meta_typography',
            'selector' => '{{WRAPPER}} .mecas-event-meta',
        ]);

        $this->end_controls_section();

        // Pagination Style
        $this->start_controls_section('section_style_pagination', [
            'label' => __('Pagination', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_pagination' => 'yes'],
        ]);

        $this->add_responsive_control('pagination_alignment', [
            'label' => __('Alignment', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => __('Left', 'mec-advanced-search'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'mec-advanced-search'), 'icon' => 'eicon-text-align-center'],
                'flex-end' => ['title' => __('Right', 'mec-advanced-search'), 'icon' => 'eicon-text-align-right'],
            ],
            'selectors' => ['{{WRAPPER}} .mecas-pagination' => 'justify-content: {{VALUE}};'],
        ]);

        $this->add_control('pagination_color', [
            'label' => __('Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-pagination a' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('pagination_active_color', [
            'label' => __('Active Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-pagination .current' => 'color: {{VALUE}}; border-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // No Results Style
        $this->start_controls_section('section_style_no_results', [
            'label' => __('No Results', 'mec-advanced-search'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('no_results_color', [
            'label' => __('Text Color', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-no-results' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'no_results_typography',
            'selector' => '{{WRAPPER}} .mecas-no-results',
        ]);

        $this->add_responsive_control('no_results_alignment', [
            'label' => __('Alignment', 'mec-advanced-search'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => __('Left', 'mec-advanced-search'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'mec-advanced-search'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => __('Right', 'mec-advanced-search'), 'icon' => 'eicon-text-align-right'],
            ],
            'selectors' => ['{{WRAPPER}} .mecas-no-results' => 'text-align: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $atts = array(
            'show_search_bar' => $settings['show_search_bar'] === 'yes' ? 'true' : 'false',
            'enable_geolocation' => $settings['enable_geolocation'] === 'yes' ? 'true' : 'false',
            'placeholder_search' => $settings['placeholder_search'],
            'placeholder_location' => $settings['placeholder_location'],
            'show_filters' => $settings['show_filters'] === 'yes' ? 'true' : 'false',
            'filter_layout' => $settings['filter_layout'],
            'show_category_filter' => $settings['show_category_filter'] === 'yes' ? 'true' : 'false',
            'show_label_filter' => $settings['show_label_filter'] === 'yes' ? 'true' : 'false',
            'show_organizer_filter' => $settings['show_organizer_filter'] === 'yes' ? 'true' : 'false',
            'show_tag_filter' => $settings['show_tag_filter'] === 'yes' ? 'true' : 'false',
            'label_category' => $settings['label_category'],
            'label_label' => $settings['label_label'],
            'label_organizer' => $settings['label_organizer'],
            'label_tag' => $settings['label_tag'],
            'label_clear' => $settings['label_clear'],
            'columns' => $settings['columns'],
            'per_page' => $settings['per_page'],
            'show_pagination' => $settings['show_pagination'] === 'yes' ? 'true' : 'false',
            'layout' => $settings['layout'],
            'no_results_text' => $settings['no_results_text'],
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

    protected function content_template() {
        ?>
        <div class="mecas-results-preview">
            <div class="mecas-search-wrapper" style="margin-bottom: 20px;">
                <div class="mecas-search-container" style="background: #fff; border-radius: 50px; padding: 5px; display: flex; align-items: center; border: 1px solid #E5E7EB;">
                    <div style="flex: 1; display: flex; align-items: center; padding: 0 15px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <span style="color: #9CA3AF; padding: 10px;">Search events</span>
                    </div>
                    <div style="width: 1px; height: 24px; background: #E5E7EB;"></div>
                    <div style="flex: 1; display: flex; align-items: center; padding: 0 15px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span style="color: #9CA3AF; padding: 10px;">City, State</span>
                    </div>
                    <button style="width: 44px; height: 44px; border-radius: 50%; background: #D97706; border: none; display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </button>
                </div>
            </div>
            <div class="mecas-filters" style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
                <select style="padding: 8px 12px; border: 1px solid #E5E7EB; border-radius: 6px; background: #fff;"><option>Category</option></select>
                <select style="padding: 8px 12px; border: 1px solid #E5E7EB; border-radius: 6px; background: #fff;"><option>Label</option></select>
                <select style="padding: 8px 12px; border: 1px solid #E5E7EB; border-radius: 6px; background: #fff;"><option>Organizer</option></select>
                <select style="padding: 8px 12px; border: 1px solid #E5E7EB; border-radius: 6px; background: #fff;"><option>Tag</option></select>
                <button style="padding: 8px 16px; background: #F3F4F6; border: none; border-radius: 6px; color: #6B7280; cursor: pointer;">Clear Filters</button>
            </div>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <# for (var i = 0; i < 3; i++) { #>
                <div style="background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div style="height: 150px; background: #E5E7EB;"></div>
                    <div style="padding: 15px;">
                        <div style="font-weight: 600; margin-bottom: 8px;">Event Title</div>
                        <div style="color: #6B7280; font-size: 14px;">Jan 15, 2025 • Location</div>
                    </div>
                </div>
                <# } #>
            </div>
        </div>
        <?php
    }
}
