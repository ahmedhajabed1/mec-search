<?php
/**
 * Event Details Card Widget - Shows date, time, location, price, and attend button
 */

if (!defined('ABSPATH')) exit;

class MECAS_Event_Details_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_event_details';
    }
    
    public function get_title() {
        return __('Event Details Card', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-info-box';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['event', 'details', 'date', 'time', 'price', 'location', 'attend'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_image',
            [
                'label' => __('Show Featured Image', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_date',
            [
                'label' => __('Show Date', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'date_format',
            [
                'label' => __('Date Format', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'l j F Y',
                'description' => __('PHP date format. Example: l j F Y = Wednesday 22 November 2025', 'mec-starter-addons'),
                'condition' => ['show_date' => 'yes'],
            ]
        );
        
        $this->add_control(
            'show_time',
            [
                'label' => __('Show Time', 'mec-starter-addons'),
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
            'show_price',
            [
                'label' => __('Show Price', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'price_label',
            [
                'label' => __('Price Label', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Price:', 'mec-starter-addons'),
                'condition' => ['show_price' => 'yes'],
            ]
        );
        
        $this->add_control(
            'show_button',
            [
                'label' => __('Show Attend Button', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Attend Event →', 'mec-starter-addons'),
                'condition' => ['show_button' => 'yes'],
            ]
        );
        
        $this->add_control(
            'button_link',
            [
                'label' => __('Button Link', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'booking',
                'options' => [
                    'booking' => __('Scroll to Booking Form', 'mec-starter-addons'),
                    'event' => __('Event Page', 'mec-starter-addons'),
                    'custom' => __('Custom URL', 'mec-starter-addons'),
                ],
                'condition' => ['show_button' => 'yes'],
            ]
        );
        
        $this->add_control(
            'custom_button_url',
            [
                'label' => __('Custom URL', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::URL,
                'condition' => [
                    'show_button' => 'yes',
                    'button_link' => 'custom',
                ],
            ]
        );
        
        $this->add_control(
            'preview_event_id',
            [
                'label' => __('Preview Event', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_events_list(),
                'description' => __('Select an event for preview. On live pages, the current event will be used.', 'mec-starter-addons'),
                'separator' => 'before',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Card
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => __('Card', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'card_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '12',
                    'right' => '12',
                    'bottom' => '12',
                    'left' => '12',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .mecas-details-card',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .mecas-details-card',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Image
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __('Image', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Height', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 100, 'max' => 500],
                ],
                'default' => ['size' => 250, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'image_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Date
        $this->start_controls_section(
            'section_style_date',
            [
                'label' => __('Date', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'date_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-date' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'selector' => '{{WRAPPER}} .mecas-details-date',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Time
        $this->start_controls_section(
            'section_style_time',
            [
                'label' => __('Time', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'time_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-time' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'time_typography',
                'selector' => '{{WRAPPER}} .mecas-details-time',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Location
        $this->start_controls_section(
            'section_style_location',
            [
                'label' => __('Location', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'location_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-location' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'location_typography',
                'selector' => '{{WRAPPER}} .mecas-details-location',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Details Box (Date/Time/Location container)
        $this->start_controls_section(
            'section_style_details_box',
            [
                'label' => __('Details Box', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'details_box_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'details_box_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'details_box_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'details_box_border',
                'selector' => '{{WRAPPER}} .mecas-details-content',
            ]
        );
        
        $this->add_responsive_control(
            'details_box_gap',
            [
                'label' => __('Gap Between Items', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 40]],
                'default' => ['size' => 8, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-content' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mecas-details-date' => 'margin-bottom: 0 !important;',
                    '{{WRAPPER}} .mecas-details-time' => 'margin-bottom: 0 !important;',
                    '{{WRAPPER}} .mecas-details-location' => 'margin-bottom: 0 !important;',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Price & Button
        $this->start_controls_section(
            'section_style_footer',
            [
                'label' => __('Price & Button', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'footer_bg_color',
            [
                'label' => __('Footer Background', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F9FAFB',
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-footer' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'price_color',
            [
                'label' => __('Price Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-price' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .mecas-details-price',
            ]
        );
        
        $this->add_control(
            'button_heading',
            [
                'label' => __('Button', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-btn' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#A7F3D0',
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_bg_hover',
            [
                'label' => __('Background Hover', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .mecas-details-btn',
            ]
        );
        
        $this->add_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-details-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    private function get_events_list() {
        $options = ['' => __('— Select Event —', 'mec-starter-addons')];
        $events = get_posts([
            'post_type' => 'mec-events',
            'posts_per_page' => 50,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish',
        ]);
        foreach ($events as $event) {
            $options[$event->ID] = $event->post_title;
        }
        return $options;
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        
        // Get event ID - check preview first, then current page
        $event_id = 0;
        if (!empty($settings['preview_event_id'])) {
            $event_id = intval($settings['preview_event_id']);
        } else {
            $event_id = get_the_ID();
            if (get_post_type($event_id) !== 'mec-events') {
                $event_id = get_query_var('mec_event_id', 0);
            }
        }
        
        if (!$event_id && $is_editor) {
            $this->render_editor_placeholder($settings);
            return;
        }
        
        if (!$event_id) {
            return;
        }
        
        // Get event data
        $thumbnail = get_the_post_thumbnail_url($event_id, 'large');
        
        // Date
        $start_date = get_post_meta($event_id, 'mec_start_date', true);
        $formatted_date = '';
        if ($start_date) {
            $date_obj = new DateTime($start_date);
            $formatted_date = $date_obj->format($settings['date_format']);
        }
        
        // Time
        $start_hour = get_post_meta($event_id, 'mec_start_time_hour', true);
        $start_min = get_post_meta($event_id, 'mec_start_time_minutes', true);
        $end_hour = get_post_meta($event_id, 'mec_end_time_hour', true);
        $end_min = get_post_meta($event_id, 'mec_end_time_minutes', true);
        
        $time_string = '';
        if ($start_hour !== '') {
            $time_string = sprintf('%02d:%02d', $start_hour, $start_min);
            if ($end_hour !== '') {
                $time_string .= ' - ' . sprintf('%02d:%02d', $end_hour, $end_min);
            }
            
            // Get timezone
            $timezone = get_post_meta($event_id, 'mec_timezone', true);
            if ($timezone) {
                $time_string .= ' ' . $timezone;
            } else {
                $time_string .= ' ' . wp_timezone_string();
            }
        }
        
        // Location
        $location_id = get_post_meta($event_id, 'mec_location_id', true);
        $location = $location_id ? get_term($location_id, 'mec_location') : null;
        $location_address = '';
        if ($location) {
            $location_address = get_term_meta($location_id, 'address', true);
            if (!$location_address) {
                $location_address = $location->name;
            }
        }
        
        // Price - check multiple sources
        $tickets = get_post_meta($event_id, 'mec_tickets', true);
        $price = '';
        
        // First try to get price from tickets
        if (!empty($tickets) && is_array($tickets)) {
            $min_price = PHP_INT_MAX;
            foreach ($tickets as $ticket) {
                if (isset($ticket['price']) && floatval($ticket['price']) > 0 && floatval($ticket['price']) < $min_price) {
                    $min_price = floatval($ticket['price']);
                }
            }
            if ($min_price !== PHP_INT_MAX) {
                $price = '$' . number_format($min_price, 2);
            }
        }
        
        // If no ticket price, try mec_cost field
        if (empty($price)) {
            $mec_cost = get_post_meta($event_id, 'mec_cost', true);
            if (!empty($mec_cost)) {
                // If it's just a number, format it
                if (is_numeric($mec_cost)) {
                    $price = '$' . number_format(floatval($mec_cost), 2);
                } else {
                    // It might already have currency symbol
                    $price = $mec_cost;
                }
            }
        }
        
        // Also check for free events
        if (empty($price)) {
            $mec_cost = get_post_meta($event_id, 'mec_cost', true);
            if ($mec_cost === '0' || $mec_cost === 0) {
                $price = __('Free', 'mec-starter-addons');
            }
        }
        
        // Button URL
        $button_url = '#';
        if ($settings['button_link'] === 'booking') {
            $button_url = get_permalink($event_id) . '#mec-booking';
        } elseif ($settings['button_link'] === 'event') {
            $button_url = get_permalink($event_id);
        } elseif ($settings['button_link'] === 'custom' && !empty($settings['custom_button_url']['url'])) {
            $button_url = $settings['custom_button_url']['url'];
        }
        
        ?>
        <div class="mecas-details-card">
            <?php if ($settings['show_image'] === 'yes' && $thumbnail): ?>
            <div class="mecas-details-image" style="background-image: url('<?php echo esc_url($thumbnail); ?>');"></div>
            <?php endif; ?>
            
            <div class="mecas-details-content">
                <?php if ($settings['show_date'] === 'yes' && $formatted_date): ?>
                <p class="mecas-details-date"><?php echo esc_html($formatted_date); ?></p>
                <?php endif; ?>
                
                <?php if ($settings['show_time'] === 'yes' && $time_string): ?>
                <p class="mecas-details-time"><?php echo esc_html($time_string); ?></p>
                <?php endif; ?>
                
                <?php if ($settings['show_location'] === 'yes' && $location_address): ?>
                <p class="mecas-details-location"><?php echo esc_html($location_address); ?></p>
                <?php endif; ?>
            </div>
            
            <?php if (($settings['show_price'] === 'yes' && $price) || $settings['show_button'] === 'yes'): ?>
            <div class="mecas-details-footer">
                <?php if ($settings['show_price'] === 'yes' && $price): ?>
                <span class="mecas-details-price"><?php echo esc_html($settings['price_label']); ?> <?php echo esc_html($price); ?></span>
                <?php endif; ?>
                
                <?php if ($settings['show_button'] === 'yes'): ?>
                <a href="<?php echo esc_url($button_url); ?>" class="mecas-details-btn"><?php echo esc_html($settings['button_text']); ?></a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <style>
        .mecas-details-card {
            overflow: hidden;
        }
        .mecas-details-image {
            width: 100%;
            height: 250px;
            background-size: cover;
            background-position: center;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .mecas-details-content {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 0;
        }
        .mecas-details-date {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
            color: #1F2937;
        }
        .mecas-details-time {
            font-size: 15px;
            color: #6B7280;
            margin: 0;
        }
        .mecas-details-location {
            font-size: 15px;
            color: #6B7280;
            margin: 0;
        }
        .mecas-details-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            margin-top: 20px;
            background: #F9FAFB;
            border-radius: 12px;
        }
        .mecas-details-price {
            font-size: 16px;
            font-weight: 600;
            color: #1F2937;
        }
        .mecas-details-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #A7F3D0;
            color: #1F2937;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border-radius: 25px;
            transition: background 0.2s ease;
        }
        .mecas-details-btn:hover {
            background: #6EE7B7;
        }
        </style>
        <?php
    }
    
    private function render_editor_placeholder($settings) {
        ?>
        <div class="mecas-details-card">
            <?php if ($settings['show_image'] === 'yes'): ?>
            <div class="mecas-details-image" style="background-image: url(''); background-color: #E5E7EB;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/>
                </svg>
            </div>
            <?php endif; ?>
            
            <div class="mecas-details-content">
                <?php if ($settings['show_date'] === 'yes'): ?>
                <p class="mecas-details-date"><?php esc_html_e('Wednesday 22 November 2025', 'mec-starter-addons'); ?></p>
                <?php endif; ?>
                
                <?php if ($settings['show_time'] === 'yes'): ?>
                <p class="mecas-details-time"><?php esc_html_e('10:00 - 18:00 EST', 'mec-starter-addons'); ?></p>
                <?php endif; ?>
                
                <?php if ($settings['show_location'] === 'yes'): ?>
                <p class="mecas-details-location"><?php esc_html_e('123 Event Street, City, State 12345', 'mec-starter-addons'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mecas-details-footer">
                <?php if ($settings['show_price'] === 'yes'): ?>
                <span class="mecas-details-price"><?php echo esc_html($settings['price_label']); ?> $25.00</span>
                <?php endif; ?>
                
                <?php if ($settings['show_button'] === 'yes'): ?>
                <a href="#" class="mecas-details-btn"><?php echo esc_html($settings['button_text']); ?></a>
                <?php endif; ?>
            </div>
        </div>
        <p style="padding: 10px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; font-size: 12px; margin-top: 10px;">
            <strong><?php esc_html_e('Tip:', 'mec-starter-addons'); ?></strong> 
            <?php esc_html_e('Select a "Preview Event" in the Content tab to see actual event data.', 'mec-starter-addons'); ?>
        </p>
        <style>
        .mecas-details-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .mecas-details-image { height: 200px; background-size: cover; background-position: center; position: relative; }
        .mecas-details-content { display: flex; flex-direction: column; gap: 8px; padding: 20px; }
        .mecas-details-date { font-size: 18px; font-weight: 600; margin: 0; color: #1F2937; }
        .mecas-details-time { font-size: 15px; color: #6B7280; margin: 0; }
        .mecas-details-location { font-size: 15px; color: #6B7280; margin: 0; }
        .mecas-details-footer { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; margin-top: 20px; background: #F9FAFB; border-radius: 12px; }
        .mecas-details-price { font-size: 16px; font-weight: 600; color: #1F2937; }
        .mecas-details-btn { display: inline-block; padding: 10px 20px; background: #A7F3D0; color: #1F2937; font-size: 14px; font-weight: 500; text-decoration: none; border-radius: 25px; }
        </style>
        <?php
    }
}
