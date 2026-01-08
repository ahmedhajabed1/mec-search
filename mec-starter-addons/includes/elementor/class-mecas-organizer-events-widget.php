<?php
/**
 * MEC Starter Addons - Organizer Events Widget
 * Displays upcoming events for the current organizer
 * Uses EXACT same class names and style controls as MEC Upcoming Events for copy/paste compatibility
 */

if (!defined('ABSPATH')) exit;

class MECAS_Organizer_Events_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_organizer_events';
    }

    public function get_title() {
        return __('Organizer Events', 'mec-starter-addons');
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

        $this->add_control('show_section_title', [
            'label' => __('Show Section Title', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('section_title_text', [
            'label' => __('Section Title', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Upcoming Events', 'mec-starter-addons'),
            'condition' => ['show_section_title' => 'yes'],
        ]);

        $this->add_control('show_title_line', [
            'label' => __('Show Title Line', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_section_title' => 'yes'],
        ]);

        $this->add_responsive_control('columns', [
            'label' => __('Columns', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '3',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'],
        ]);

        $this->add_control('per_page', [
            'label' => __('Number of Events', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 6,
            'min' => 1,
            'max' => 24,
        ]);

        $this->add_control('preview_organizer_id', [
            'label' => __('Preview Organizer', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $this->get_organizers_list(),
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

        $this->add_control('show_date', [
            'label' => __('Show Date/Time', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_category', [
            'label' => __('Show Category Badge', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_location', [
            'label' => __('Show Location', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_host', [
            'label' => __('Show Hosted By', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('no_events_text', [
            'label' => __('No Events Text', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('No upcoming events scheduled.', 'mec-starter-addons'),
        ]);

        $this->end_controls_section();

        // === STYLE TAB ===

        // Section Title Style
        $this->start_controls_section('section_style_section_title', [
            'label' => __('Section Title', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_section_title' => 'yes'],
        ]);

        $this->add_control('section_title_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-section-title' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'section_title_typography',
            'selector' => '{{WRAPPER}} .mecas-section-title',
        ]);

        $this->add_responsive_control('section_title_margin', [
            'label' => __('Margin', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '0', 'right' => '0', 'bottom' => '20', 'left' => '0', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-section-title-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_control('title_line_color', [
            'label' => __('Line Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'condition' => ['show_title_line' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-section-title-line' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->end_controls_section();

        // Grid Style - EXACT SAME AS MEC UPCOMING
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

        // Event Card Style - EXACT SAME AS MEC UPCOMING
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

    private function get_organizers_list() {
        $options = ['' => __('Current Organizer (Auto)', 'mec-starter-addons')];
        $terms = get_terms(['taxonomy' => 'mec_organizer', 'hide_empty' => false]);
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->term_id] = $term->name;
            }
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $organizer = $this->get_current_organizer($settings);
        
        if (!$organizer) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<p style="padding: 20px; background: #f5f5f5;">' . __('Select a Preview Organizer to see events.', 'mec-starter-addons') . '</p>';
            }
            return;
        }

        $events = $this->get_organizer_events($organizer['id'], intval($settings['per_page']));
        $columns = $settings['columns'];
        $columns_tablet = isset($settings['columns_tablet']) ? $settings['columns_tablet'] : '2';
        $columns_mobile = isset($settings['columns_mobile']) ? $settings['columns_mobile'] : '1';
        
        $date_format = $settings['date_format'] ?: 'D, M j';
        $time_format = $settings['time_format'] ?: 'g:i A T';
        $hosted_by_text = $settings['hosted_by_text'] ?: __('Hosted by', 'mec-starter-addons');
        $currency_symbol = $settings['currency_symbol'] ?: '$';
        
        $widget_id = 'mecas-org-events-' . $this->get_id();
        ?>
        <style>
            #<?php echo $widget_id; ?> .mecas-featured-grid {
                grid-template-columns: repeat(<?php echo $columns; ?>, 1fr);
            }
            @media (max-width: 1024px) {
                #<?php echo $widget_id; ?> .mecas-featured-grid {
                    grid-template-columns: repeat(<?php echo $columns_tablet; ?>, 1fr);
                }
            }
            @media (max-width: 767px) {
                #<?php echo $widget_id; ?> .mecas-featured-grid {
                    grid-template-columns: repeat(<?php echo $columns_mobile; ?>, 1fr);
                }
            }
        </style>
        
        <div class="mecas-featured-wrapper mecas-organizer-events-wrapper" id="<?php echo esc_attr($widget_id); ?>">
            <?php if ($settings['show_section_title'] === 'yes'): ?>
            <div class="mecas-section-title-wrap">
                <h2 class="mecas-section-title"><?php echo esc_html($settings['section_title_text']); ?></h2>
                <?php if ($settings['show_title_line'] === 'yes'): ?>
                <span class="mecas-section-title-line"></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if (empty($events)): ?>
            <div class="mecas-no-results">
                <p class="mecas-no-results-text"><?php echo esc_html($settings['no_events_text']); ?></p>
            </div>
            <?php else: ?>
            <div class="mecas-featured-grid">
                <?php foreach ($events as $event): ?>
                <div class="mecas-event-card">
                    <a href="<?php echo esc_url($event['url']); ?>">
                        <div class="mecas-card-image-wrapper">
                            <?php if ($event['image']): ?>
                            <img src="<?php echo esc_url($event['image']); ?>" alt="<?php echo esc_attr($event['title']); ?>" class="mecas-card-image">
                            <?php else: ?>
                            <div class="mecas-card-image mecas-card-image-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="m21 15-5-5L5 21"/>
                                </svg>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($settings['show_price'] === 'yes' && $event['cost']): ?>
                            <span class="mecas-price-badge"><?php echo esc_html($event['cost']); ?></span>
                            <?php endif; ?>
                            
                            <?php if ($settings['show_date'] === 'yes'): ?>
                            <div class="mecas-date-bar">
                                <span class="mecas-date-text"><?php echo esc_html($event['date_formatted']); ?></span>
                                <?php if ($settings['show_category'] === 'yes' && $event['category']): ?>
                                <span class="mecas-tag-badge"><?php echo esc_html($event['category']); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mecas-card-content">
                            <h3 class="mecas-card-title"><?php echo esc_html($event['title']); ?></h3>
                            <?php if ($settings['show_location'] === 'yes' && $event['location']): ?>
                            <p class="mecas-card-location"><?php echo esc_html($event['location']); ?></p>
                            <?php endif; ?>
                            <?php if ($settings['show_host'] === 'yes'): ?>
                            <p class="mecas-card-organizer"><?php echo esc_html($hosted_by_text . ' ' . $organizer['name']); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <style>
        #<?php echo esc_attr($widget_id); ?> .mecas-section-title-wrap {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        #<?php echo esc_attr($widget_id); ?> .mecas-section-title {
            margin: 0;
            white-space: nowrap;
        }
        #<?php echo esc_attr($widget_id); ?> .mecas-section-title-line {
            flex: 1;
            height: 1px;
            background-color: #E5E7EB;
            margin-left: 20px;
        }
        </style>
        <?php
    }

    private function get_organizer_events($organizer_id, $count = 6) {
        $settings = $this->get_settings_for_display();
        $date_format = $settings['date_format'] ?: 'D, M j';
        $time_format = $settings['time_format'] ?: 'g:i A T';
        $currency = $settings['currency_symbol'] ?: '$';
        
        $args = [
            'post_type' => 'mec-events',
            'post_status' => 'publish',
            'posts_per_page' => $count,
            'meta_key' => 'mec_start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => 'mec_start_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE'
                ]
            ],
            'tax_query' => [
                [
                    'taxonomy' => 'mec_organizer',
                    'field' => 'term_id',
                    'terms' => $organizer_id,
                ]
            ]
        ];
        
        $query = new WP_Query($args);
        $events = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $id = get_the_ID();
                
                $start_date = get_post_meta($id, 'mec_start_date', true);
                $start_time_hour = get_post_meta($id, 'mec_start_time_hour', true);
                $start_time_min = get_post_meta($id, 'mec_start_time_minutes', true);
                $start_time_ampm = get_post_meta($id, 'mec_start_time_ampm', true);
                
                // Format date and time
                $formatted_date = $start_date ? date_i18n($date_format, strtotime($start_date)) : '';
                $formatted_time = '';
                if ($start_time_hour) {
                    $formatted_time = $start_time_hour . ':' . str_pad($start_time_min, 2, '0', STR_PAD_LEFT) . ' ' . strtoupper($start_time_ampm);
                    // Add timezone if available
                    $timezone = get_option('timezone_string');
                    if ($timezone) {
                        try {
                            $tz_abbr = (new DateTime('now', new DateTimeZone($timezone)))->format('T');
                            $formatted_time .= ' ' . $tz_abbr;
                        } catch (Exception $e) {}
                    }
                }
                
                $date_formatted = $formatted_date;
                if ($formatted_time) {
                    $date_formatted .= ' | ' . $formatted_time;
                }
                
                $cost = get_post_meta($id, 'mec_cost', true);
                $cost_display = '';
                if ($cost) {
                    $cost_display = is_numeric($cost) ? $currency . number_format(floatval($cost), 2) : $cost;
                }
                
                $loc_id = get_post_meta($id, 'mec_location_id', true);
                $location = '';
                if ($loc_id) {
                    $loc = get_term($loc_id, 'mec_location');
                    if ($loc && !is_wp_error($loc)) {
                        $location = $loc->name;
                    }
                }
                
                $categories = get_the_terms($id, 'mec_category');
                $category = ($categories && !is_wp_error($categories)) ? $categories[0]->name : '';
                
                $events[] = [
                    'id' => $id,
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'image' => get_the_post_thumbnail_url($id, 'medium_large'),
                    'date' => $start_date,
                    'date_formatted' => $date_formatted,
                    'cost' => $cost_display,
                    'location' => $location,
                    'category' => $category,
                ];
            }
        }
        wp_reset_postdata();
        
        return $events;
    }

    private function get_current_organizer($settings) {
        $organizer_id = null;
        
        if (!empty($settings['preview_organizer_id'])) {
            $organizer_id = intval($settings['preview_organizer_id']);
        } elseif (is_tax('mec_organizer')) {
            $term = get_queried_object();
            if ($term) $organizer_id = $term->term_id;
        }

        if (!$organizer_id) return null;
        return mecas_get_organizer_data($organizer_id);
    }

    protected function content_template() {
        ?>
        <div class="mecas-featured-wrapper mecas-organizer-events-wrapper">
            <# if (settings.show_section_title === 'yes') { #>
            <div class="mecas-section-title-wrap" style="display: flex; align-items: center; margin-bottom: 20px;">
                <h2 class="mecas-section-title" style="margin: 0; white-space: nowrap;">{{{ settings.section_title_text }}}</h2>
                <# if (settings.show_title_line === 'yes') { #>
                <span class="mecas-section-title-line" style="flex: 1; height: 1px; background: #E5E7EB; margin-left: 20px;"></span>
                <# } #>
            </div>
            <# } #>
            <div class="mecas-featured-grid" style="display: grid; grid-template-columns: repeat({{{ settings.columns }}}, 1fr); gap: 24px;">
                <# for (var i = 0; i < 3; i++) { #>
                <div class="mecas-event-card" style="background: #FEF9F3; border: 1px solid #E5E7EB; border-radius: 12px; overflow: hidden;">
                    <div class="mecas-card-image-wrapper" style="position: relative;">
                        <div class="mecas-card-image" style="height: 180px; background: #E5E7EB;"></div>
                        <# if (settings.show_price === 'yes') { #>
                        <span class="mecas-price-badge" style="position: absolute; top: 12px; left: 12px; background: #D1FAE5; color: #065F46; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">$35.00</span>
                        <# } #>
                        <# if (settings.show_date === 'yes') { #>
                        <div class="mecas-date-bar" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.5); color: #fff; padding: 8px 12px; display: flex; justify-content: space-between; align-items: center;">
                            <span class="mecas-date-text">Fri, Nov 28 | 8:00 PM EST</span>
                            <# if (settings.show_category === 'yes') { #>
                            <span class="mecas-tag-badge" style="background: #D1FAE5; color: #065F46; padding: 4px 10px; border-radius: 12px; font-size: 12px;">Social Play</span>
                            <# } #>
                        </div>
                        <# } #>
                    </div>
                    <div class="mecas-card-content" style="padding: 16px;">
                        <h3 class="mecas-card-title" style="font-size: 16px; font-weight: 600; margin: 0 0 8px 0; color: #1F2937;">Mahjong Social Event</h3>
                        <# if (settings.show_location === 'yes') { #>
                        <p class="mecas-card-location" style="font-size: 14px; color: #6B7280; margin: 0 0 4px 0;">Tampa, FL</p>
                        <# } #>
                        <# if (settings.show_host === 'yes') { #>
                        <p class="mecas-card-organizer" style="font-size: 13px; color: #9333EA; margin: 0;">{{{ settings.hosted_by_text }}} Jane Doe</p>
                        <# } #>
                    </div>
                </div>
                <# } #>
            </div>
        </div>
        <?php
    }
}
