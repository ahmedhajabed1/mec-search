<?php
/**
 * MEC Starter Addons - Elementor Organizers Widget
 * Displays organizers/teachers in a grid with their info
 */

if (!defined('ABSPATH')) exit;

class MECAS_Organizers_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_organizers';
    }

    public function get_title() {
        return __('MEC Teachers/Organizers', 'mec-starter-addons');
    }

    public function get_icon() {
        return 'eicon-person';
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
            'label' => __('Number of Organizers', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 4,
            'min' => 1,
            'max' => 20,
        ]);

        $this->add_control('show_heart', [
            'label' => __('Show Heart Icon', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('link_to', [
            'label' => __('Link To', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'events',
            'options' => [
                'events' => __('Events Archive (filtered)', 'mec-starter-addons'),
                'none' => __('No Link', 'mec-starter-addons'),
            ],
        ]);

        $this->add_control('events_page_url', [
            'label' => __('Events Page URL', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => home_url('/events/'),
            'condition' => ['link_to' => 'events'],
        ]);

        $this->end_controls_section();

        // === STYLE TAB ===

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
            'selectors' => ['{{WRAPPER}} .mecas-organizers-grid' => 'gap: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('grid_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-organizers-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Card Style
        $this->start_controls_section('section_style_cards', [
            'label' => __('Organizer Cards', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg_color', [
            'label' => __('Card Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-organizer-card' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('card_border_style', [
            'label' => __('Border Style', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'solid',
            'options' => [
                'none' => __('None', 'mec-starter-addons'),
                'solid' => __('Solid', 'mec-starter-addons'),
                'dashed' => __('Dashed', 'mec-starter-addons'),
                'dotted' => __('Dotted', 'mec-starter-addons'),
            ],
            'selectors' => ['{{WRAPPER}} .mecas-organizer-card' => 'border-style: {{VALUE}} !important;'],
        ]);

        $this->add_control('card_border_color', [
            'label' => __('Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'condition' => ['card_border_style!' => 'none'],
            'selectors' => ['{{WRAPPER}} .mecas-organizer-card' => 'border-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('card_border_width', [
            'label' => __('Border Width', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 10]],
            'default' => ['size' => 1, 'unit' => 'px'],
            'condition' => ['card_border_style!' => 'none'],
            'selectors' => ['{{WRAPPER}} .mecas-organizer-card' => 'border-width: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('card_border_radius', [
            'label' => __('Card Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-organizer-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .mecas-organizer-card',
        ]);

        $this->end_controls_section();

        // Image Style
        $this->start_controls_section('section_style_image', [
            'label' => __('Image', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('image_height', [
            'label' => __('Image Height', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 100, 'max' => 400]],
            'default' => ['size' => 200, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-organizer-image' => 'height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('image_border_radius', [
            'label' => __('Image Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '11', 'right' => '11', 'bottom' => '0', 'left' => '0', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-organizer-image, {{WRAPPER}} .mecas-organizer-image-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Location Bar Style
        $this->start_controls_section('section_style_location_bar', [
            'label' => __('Location Bar', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('location_bar_bg', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E8927C',
            'selectors' => ['{{WRAPPER}} .mecas-organizer-location-bar' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('location_bar_text', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-organizer-location-bar' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'location_bar_typography',
            'selector' => '{{WRAPPER}} .mecas-organizer-location-bar',
        ]);

        $this->add_responsive_control('location_bar_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '10', 'right' => '16', 'bottom' => '10', 'left' => '16', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-organizer-location-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Heart Icon Style
        $this->start_controls_section('section_style_heart', [
            'label' => __('Heart Icon', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_heart' => 'yes'],
        ]);

        $this->add_control('heart_color', [
            'label' => __('Icon Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-heart-icon svg' => 'stroke: {{VALUE}} !important;'],
        ]);

        $this->add_control('heart_bg_color', [
            'label' => __('Background', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'selectors' => ['{{WRAPPER}} .mecas-heart-icon' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('heart_size', [
            'label' => __('Icon Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 12, 'max' => 32]],
            'default' => ['size' => 18, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-heart-icon svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('heart_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 20]],
            'default' => ['size' => 0, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-heart-icon' => 'padding: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('heart_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 0, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-heart-icon' => 'border-radius: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Content Style
        $this->start_controls_section('section_style_content', [
            'label' => __('Content', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('content_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => ['top' => '16', 'right' => '16', 'bottom' => '16', 'left' => '16', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-organizer-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        // Name
        $this->add_control('heading_name', [
            'label' => __('Name', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('name_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-organizer-name' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'name_typography',
            'selector' => '{{WRAPPER}} .mecas-organizer-name',
        ]);

        $this->add_responsive_control('name_margin', [
            'label' => __('Margin', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors' => ['{{WRAPPER}} .mecas-organizer-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        // Tagline
        $this->add_control('heading_tagline', [
            'label' => __('Tagline', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('tagline_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#6B7280',
            'selectors' => ['{{WRAPPER}} .mecas-organizer-tagline' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'tagline_typography',
            'selector' => '{{WRAPPER}} .mecas-organizer-tagline',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $columns = $settings['columns'];
        $columns_tablet = isset($settings['columns_tablet']) ? $settings['columns_tablet'] : '2';
        $columns_mobile = isset($settings['columns_mobile']) ? $settings['columns_mobile'] : '1';
        
        $events_url = '';
        if ($settings['link_to'] === 'events' && !empty($settings['events_page_url']['url'])) {
            $events_url = $settings['events_page_url']['url'];
        }
        
        $atts = array(
            'columns' => $columns,
            'columns_tablet' => $columns_tablet,
            'columns_mobile' => $columns_mobile,
            'per_page' => $settings['per_page'],
            'show_heart' => $settings['show_heart'] === 'yes' ? 'true' : 'false',
            'link_to' => $settings['link_to'],
            'events_url' => $events_url,
            'widget_id' => 'mecas-organizers-' . $this->get_id(),
        );
        
        echo do_shortcode('[mec_organizers_grid ' . $this->build_shortcode_attrs($atts) . ']');
    }

    private function build_shortcode_attrs($atts) {
        $str = '';
        foreach ($atts as $key => $value) {
            $str .= $key . '="' . esc_attr($value) . '" ';
        }
        return $str;
    }
}
