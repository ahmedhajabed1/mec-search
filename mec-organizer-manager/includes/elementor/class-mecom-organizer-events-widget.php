<?php
/**
 * MEC Organizer Manager - Organizer Events Widget
 * Displays events for the current organizer with same styling controls as MEC Upcoming Events
 */

if (!defined('ABSPATH')) exit;

class MECOM_Organizer_Events_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'mecom-organizer-events'; }
    public function get_title() { return __('Organizer Events', 'mec-organizer-manager'); }
    public function get_icon() { return 'eicon-posts-grid'; }
    public function get_categories() { return ['mec-organizer-manager']; }

    protected function register_controls() {
        // === CONTENT TAB ===
        
        // Section Title
        $this->start_controls_section('section_title', [
            'label' => __('Section Title', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('show_title', [
            'label' => __('Show Section Title', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('title_text', [
            'label' => __('Title', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Upcoming Events', 'mec-organizer-manager'),
            'condition' => ['show_title' => 'yes'],
        ]);
        
        $this->add_control('show_title_line', [
            'label' => __('Show Title Line', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_title' => 'yes'],
        ]);
        
        $this->end_controls_section();

        // General Section
        $this->start_controls_section('section_general', [
            'label' => __('General', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_responsive_control('columns', [
            'label' => __('Columns', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '3',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'],
        ]);

        $this->add_control('per_page', [
            'label' => __('Number of Events', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 6,
            'min' => 1,
            'max' => 24,
        ]);

        $this->add_control('hide_past_events', [
            'label' => __('Hide Past Events', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('preview_organizer_id', [
            'label' => __('Preview Organizer', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $this->get_organizers_list(),
        ]);

        $this->end_controls_section();

        // Event Card Content Section
        $this->start_controls_section('section_card_content', [
            'label' => __('Event Card Content', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('date_format', [
            'label' => __('Date Format', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'D, M j',
            'description' => __('PHP date format. D = day, M = month, j = day number', 'mec-organizer-manager'),
        ]);

        $this->add_control('time_format', [
            'label' => __('Time Format', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'g:i A',
            'description' => __('PHP time format. g = hour, i = minutes, A = AM/PM', 'mec-organizer-manager'),
        ]);

        $this->add_control('show_price', [
            'label' => __('Show Price Badge', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('currency_symbol', [
            'label' => __('Currency Symbol', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '$',
            'condition' => ['show_price' => 'yes'],
        ]);

        $this->add_control('show_location', [
            'label' => __('Show Location', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_category', [
            'label' => __('Show Category Badge', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();

        // === STYLE TAB ===

        // Section Title Style
        $this->start_controls_section('section_style_section_title', [
            'label' => __('Section Title', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_title' => 'yes'],
        ]);
        
        $this->add_control('section_title_color', [
            'label' => __('Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E8927C',
            'selectors' => ['{{WRAPPER}} .mecom-section-title' => 'color: {{VALUE}};'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'section_title_typography',
            'selector' => '{{WRAPPER}} .mecom-section-title',
        ]);
        
        $this->add_responsive_control('section_title_margin_bottom', [
            'label' => __('Margin Bottom', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-section-title-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};'],
        ]);
        
        $this->add_control('heading_line', [
            'label' => __('Line', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => ['show_title_line' => 'yes'],
        ]);
        
        $this->add_control('section_title_line_color', [
            'label' => __('Line Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => ['{{WRAPPER}} .mecom-section-title-line' => 'background-color: {{VALUE}};'],
            'condition' => ['show_title_line' => 'yes'],
        ]);
        
        $this->add_responsive_control('section_title_line_height', [
            'label' => __('Line Height', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 1, 'max' => 10]],
            'default' => ['size' => 1, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-section-title-line' => 'height: {{SIZE}}{{UNIT}};'],
            'condition' => ['show_title_line' => 'yes'],
        ]);
        
        $this->add_responsive_control('section_title_line_gap', [
            'label' => __('Gap Before Line', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 5, 'max' => 50]],
            'default' => ['size' => 15, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-section-title-line' => 'margin-left: {{SIZE}}{{UNIT}};'],
            'condition' => ['show_title_line' => 'yes'],
        ]);
        
        $this->end_controls_section();

        // Grid Style
        $this->start_controls_section('section_style_grid', [
            'label' => __('Grid', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_responsive_control('grid_gap', [
            'label' => __('Gap', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 60]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-grid' => 'gap: {{SIZE}}{{UNIT}} !important;'],
        ]);
        
        $this->end_controls_section();

        // Card Style
        $this->start_controls_section('section_style_card', [
            'label' => __('Card', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('card_bg', [
            'label' => __('Background', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-card' => 'background-color: {{VALUE}} !important;'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'card_border',
            'selector' => '{{WRAPPER}} .mecas-card',
        ]);
        
        $this->add_responsive_control('card_border_radius', [
            'label' => __('Border Radius', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .mecas-card',
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_hover_shadow',
            'label' => __('Hover Shadow', 'mec-organizer-manager'),
            'selector' => '{{WRAPPER}} .mecas-card:hover',
        ]);
        
        $this->end_controls_section();

        // Image Style
        $this->start_controls_section('section_style_image', [
            'label' => __('Image', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_responsive_control('image_height', [
            'label' => __('Height', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 100, 'max' => 400]],
            'default' => ['size' => 200, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mecas-card-image' => 'height: {{SIZE}}{{UNIT}} !important;',
                '{{WRAPPER}} .mecas-featured-image' => 'height: {{SIZE}}{{UNIT}} !important;',
            ],
        ]);
        
        $this->add_control('image_fit', [
            'label' => __('Object Fit', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'cover',
            'options' => [
                'cover' => __('Cover', 'mec-organizer-manager'),
                'contain' => __('Contain', 'mec-organizer-manager'),
                'fill' => __('Fill', 'mec-organizer-manager'),
            ],
            'selectors' => ['{{WRAPPER}} .mecas-featured-image' => 'object-fit: {{VALUE}} !important;'],
        ]);
        
        $this->add_responsive_control('image_border_radius', [
            'label' => __('Border Radius', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '12', 'right' => '12', 'bottom' => '0', 'left' => '0', 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mecas-card-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                '{{WRAPPER}} .mecas-featured-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
            ],
        ]);
        
        $this->add_control('image_overlay_bg', [
            'label' => __('Overlay Background', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'selectors' => ['{{WRAPPER}} .mecas-card-image::after' => 'background-color: {{VALUE}} !important;'],
        ]);
        
        $this->end_controls_section();

        // Price Badge Style
        $this->start_controls_section('section_style_price', [
            'label' => __('Price Badge', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_price' => 'yes'],
        ]);
        
        $this->add_control('price_bg', [
            'label' => __('Background', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-price-badge' => 'background-color: {{VALUE}} !important;'],
        ]);
        
        $this->add_control('price_text', [
            'label' => __('Text Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-price-badge' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'price_typography',
            'selector' => '{{WRAPPER}} .mecas-price-badge',
        ]);
        
        $this->add_responsive_control('price_padding', [
            'label' => __('Padding', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '4', 'right' => '10', 'bottom' => '4', 'left' => '10', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-price-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);
        
        $this->add_responsive_control('price_border_radius', [
            'label' => __('Border Radius', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-price-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);
        
        $this->end_controls_section();

        // Date/Time Bar Style
        $this->start_controls_section('section_style_date', [
            'label' => __('Date/Time Bar', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('date_bar_bg', [
            'label' => __('Background', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => 'rgba(0, 0, 0, 0.5)',
            'selectors' => ['{{WRAPPER}} .mecas-date-bar' => 'background-color: {{VALUE}} !important;'],
        ]);
        
        $this->add_control('date_bar_text', [
            'label' => __('Text Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-date-bar' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'date_bar_typography',
            'selector' => '{{WRAPPER}} .mecas-date-bar',
        ]);
        
        $this->add_responsive_control('date_bar_padding', [
            'label' => __('Padding', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '8', 'right' => '12', 'bottom' => '8', 'left' => '12', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-date-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);
        
        $this->end_controls_section();

        // Category Badge Style
        $this->start_controls_section('section_style_category', [
            'label' => __('Category Badge', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_category' => 'yes'],
        ]);
        
        $this->add_control('tag_badge_bg', [
            'label' => __('Background', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#D1FAE5',
            'selectors' => ['{{WRAPPER}} .mecas-tag-badge' => 'background-color: {{VALUE}} !important;'],
        ]);
        
        $this->add_control('tag_badge_text', [
            'label' => __('Text Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#065F46',
            'selectors' => ['{{WRAPPER}} .mecas-tag-badge' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'tag_badge_typography',
            'selector' => '{{WRAPPER}} .mecas-tag-badge',
        ]);
        
        $this->add_responsive_control('tag_badge_padding', [
            'label' => __('Padding', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => ['top' => '4', 'right' => '10', 'bottom' => '4', 'left' => '10', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-tag-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);
        
        $this->add_responsive_control('tag_badge_border_radius', [
            'label' => __('Border Radius', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-tag-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);
        
        $this->end_controls_section();

        // Card Content Style
        $this->start_controls_section('section_style_content', [
            'label' => __('Card Content', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_responsive_control('card_content_padding', [
            'label' => __('Content Padding', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => ['top' => '16', 'right' => '16', 'bottom' => '16', 'left' => '16', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);
        
        // Title
        $this->add_control('heading_card_title', [
            'label' => __('Title', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        
        $this->add_control('card_title_color', [
            'label' => __('Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-card-title' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_control('card_title_hover_color', [
            'label' => __('Hover Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'selectors' => ['{{WRAPPER}} .mecas-card:hover .mecas-card-title' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'card_title_typography',
            'selector' => '{{WRAPPER}} .mecas-card-title',
        ]);
        
        $this->add_responsive_control('card_title_margin', [
            'label' => __('Margin', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors' => ['{{WRAPPER}} .mecas-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);
        
        // Location
        $this->add_control('heading_card_location', [
            'label' => __('Location', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => ['show_location' => 'yes'],
        ]);
        
        $this->add_control('card_location_color', [
            'label' => __('Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#6B7280',
            'selectors' => ['{{WRAPPER}} .mecas-card-location' => 'color: {{VALUE}} !important;'],
            'condition' => ['show_location' => 'yes'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'card_location_typography',
            'selector' => '{{WRAPPER}} .mecas-card-location',
            'condition' => ['show_location' => 'yes'],
        ]);
        
        $this->end_controls_section();
    }

    private function get_organizers_list() {
        $options = ['' => __('Current Organizer', 'mec-organizer-manager')];
        $organizers = get_terms(['taxonomy' => 'mec_organizer', 'hide_empty' => false]);
        if (!is_wp_error($organizers)) { foreach ($organizers as $o) { $options[$o->term_id] = $o->name; } }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $organizer = $this->get_current_organizer($settings);
        
        if (!$organizer && !\Elementor\Plugin::$instance->editor->is_edit_mode()) return;
        
        $organizer_id = $organizer ? $organizer['id'] : 0;
        $columns = $settings['columns'] ?: 3;
        $columns_tablet = isset($settings['columns_tablet']) ? $settings['columns_tablet'] : '2';
        $columns_mobile = isset($settings['columns_mobile']) ? $settings['columns_mobile'] : '1';
        
        $args = [
            'post_type' => 'mec-events',
            'posts_per_page' => $settings['per_page'],
            'meta_query' => [['key' => 'mec_organizer_id', 'value' => $organizer_id]],
            'orderby' => 'meta_value',
            'meta_key' => 'mec_start_date',
            'order' => 'ASC',
        ];
        
        if ($settings['hide_past_events'] === 'yes') {
            $args['meta_query'][] = ['key' => 'mec_start_date', 'value' => date('Y-m-d'), 'compare' => '>=', 'type' => 'DATE'];
        }
        
        $events = get_posts($args);
        $date_format = $settings['date_format'] ?: 'D, M j';
        $time_format = $settings['time_format'] ?: 'g:i A';
        $currency = $settings['currency_symbol'] ?: '$';
        ?>
        <div class="mecom-org-events">
            <?php if ($settings['show_title'] === 'yes'): ?>
            <div class="mecom-section-title-wrap">
                <h2 class="mecom-section-title"><?php echo esc_html($settings['title_text']); ?></h2>
                <?php if ($settings['show_title_line'] === 'yes'): ?>
                <span class="mecom-section-title-line"></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($events)): ?>
            <div class="mecas-grid" data-columns="<?php echo esc_attr($columns); ?>" data-columns-tablet="<?php echo esc_attr($columns_tablet); ?>" data-columns-mobile="<?php echo esc_attr($columns_mobile); ?>">
                <?php foreach ($events as $event): 
                    $start_date = get_post_meta($event->ID, 'mec_start_date', true);
                    $start_hour = get_post_meta($event->ID, 'mec_start_time_hour', true);
                    $start_min = get_post_meta($event->ID, 'mec_start_time_minutes', true);
                    $start_ampm = get_post_meta($event->ID, 'mec_start_time_ampm', true);
                    $thumbnail = get_the_post_thumbnail_url($event->ID, 'medium_large');
                    $cost = get_post_meta($event->ID, 'mec_cost', true);
                    $location_id = get_post_meta($event->ID, 'mec_location_id', true);
                    $location = $location_id ? get_term($location_id, 'mec_location') : null;
                    
                    // Get category
                    $categories = get_the_terms($event->ID, 'mec_category');
                    $category = (!empty($categories) && !is_wp_error($categories)) ? $categories[0] : null;
                    
                    $time_str = '';
                    if ($start_hour) {
                        $time_obj = DateTime::createFromFormat('g:i A', $start_hour . ':' . str_pad($start_min, 2, '0', STR_PAD_LEFT) . ' ' . strtoupper($start_ampm));
                        if ($time_obj) {
                            $time_str = $time_obj->format($time_format);
                        }
                    }
                    $date_str = $start_date ? date_i18n($date_format, strtotime($start_date)) : '';
                ?>
                <a href="<?php echo get_permalink($event->ID); ?>" class="mecas-card">
                    <div class="mecas-card-image">
                        <?php if ($thumbnail): ?>
                        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($event->post_title); ?>" class="mecas-featured-image">
                        <?php else: ?>
                        <div class="mecas-featured-image mecas-placeholder"></div>
                        <?php endif; ?>
                        
                        <?php if ($settings['show_price'] === 'yes' && $cost): ?>
                        <span class="mecas-price-badge"><?php echo esc_html($currency . $cost); ?></span>
                        <?php endif; ?>
                        
                        <?php if ($date_str || $time_str || ($settings['show_category'] === 'yes' && $category)): ?>
                        <div class="mecas-date-bar">
                            <span class="mecas-date-text">
                                <?php echo $date_str; ?>
                                <?php if ($date_str && $time_str): ?> | <?php endif; ?>
                                <?php echo $time_str; ?>
                            </span>
                            <?php if ($settings['show_category'] === 'yes' && $category): ?>
                            <span class="mecas-tag-badge"><?php echo esc_html($category->name); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="mecas-card-content">
                        <h3 class="mecas-card-title"><?php echo esc_html($event->post_title); ?></h3>
                        <?php if ($settings['show_location'] === 'yes' && $location && !is_wp_error($location)): ?>
                        <p class="mecas-card-location"><?php echo esc_html($location->name); ?></p>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="mecom-no-events"><?php _e('No upcoming events scheduled.', 'mec-organizer-manager'); ?></p>
            <?php endif; ?>
        </div>
        <style>
        .mecom-section-title-wrap { display: flex; align-items: center; }
        .mecom-section-title { margin: 0; white-space: nowrap; }
        .mecom-section-title-line { flex: 1; }
        .mecas-grid { 
            display: grid; 
            grid-template-columns: repeat(<?php echo $columns; ?>, 1fr);
        }
        .mecas-card { 
            text-decoration: none; 
            color: inherit; 
            overflow: hidden; 
            transition: transform 0.2s, box-shadow 0.2s;
            display: block;
        }
        .mecas-card:hover { transform: translateY(-4px); }
        .mecas-card-image { 
            position: relative; 
            overflow: hidden;
        }
        .mecas-featured-image { 
            width: 100%; 
            display: block;
        }
        .mecas-placeholder { 
            background: linear-gradient(135deg, #E5E7EB, #D1D5DB);
        }
        .mecas-price-badge { 
            position: absolute; 
            top: 12px; 
            left: 12px;
            font-weight: 600;
        }
        .mecas-date-bar { 
            position: absolute; 
            bottom: 0; 
            left: 0; 
            right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .mecas-date-text {
            flex: 1;
        }
        .mecas-tag-badge {
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
        }
        .mecas-card-content { }
        .mecas-card-title { 
            font-size: 16px; 
            font-weight: 600; 
            margin: 0 0 4px 0; 
            line-height: 1.4; 
            transition: color 0.2s ease;
        }
        .mecas-card-location {
            margin: 0;
            font-size: 14px;
        }
        .mecom-no-events { text-align: center; padding: 40px; color: #6B7280; }
        @media (max-width: 1024px) { 
            .mecas-grid { grid-template-columns: repeat(<?php echo $columns_tablet; ?>, 1fr) !important; } 
        }
        @media (max-width: 767px) { 
            .mecas-grid { grid-template-columns: repeat(<?php echo $columns_mobile; ?>, 1fr) !important; } 
        }
        </style>
        <?php
    }

    private function get_current_organizer($settings) {
        $organizer_id = !empty($settings['preview_organizer_id']) ? intval($settings['preview_organizer_id']) : (get_query_var('mecom_organizer_id') ?: (is_tax('mec_organizer') && ($t = get_queried_object()) ? $t->term_id : null));
        return $organizer_id ? mecom_get_organizer_data($organizer_id) : null;
    }
}
