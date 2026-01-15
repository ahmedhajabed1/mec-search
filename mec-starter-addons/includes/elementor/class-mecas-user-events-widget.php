<?php
/**
 * User Events Widget - For Upcoming, Past, and Saved Events
 */

if (!defined('ABSPATH')) exit;

class MECAS_User_Events_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_user_events';
    }
    
    public function get_title() {
        return __('User Events', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-posts-grid';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['events', 'upcoming', 'past', 'saved', 'user', 'bookings'];
    }
    
    protected function register_controls() {
        
        // Content Section - General
        $this->start_controls_section(
            'section_general',
            [
                'label' => __('General', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'event_type',
            [
                'label' => __('Event Type', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'upcoming',
                'options' => [
                    'upcoming' => __('Upcoming Events (Booked)', 'mec-starter-addons'),
                    'past' => __('Past Events (Attended)', 'mec-starter-addons'),
                    'saved' => __('Saved Events (Bookmarked)', 'mec-starter-addons'),
                ],
            ]
        );
        
        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecua-events-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );
        
        $this->add_control(
            'per_page',
            [
                'label' => __('Events Per Page', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 50,
            ]
        );
        
        $this->add_control(
            'empty_message',
            [
                'label' => __('Empty Message', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('No events found.', 'mec-starter-addons'),
            ]
        );
        
        $this->end_controls_section();
        
        // Content Section - Event Card
        $this->start_controls_section(
            'section_card_content',
            [
                'label' => __('Event Card Content', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_image',
            [
                'label' => __('Show Image', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_price',
            [
                'label' => __('Show Price Badge', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_date',
            [
                'label' => __('Show Date/Time', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_category',
            [
                'label' => __('Show Category Badge', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_title',
            [
                'label' => __('Show Title', 'mec-starter-addons'),
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
            'show_organizer',
            [
                'label' => __('Show Hosted By', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Grid
        $this->start_controls_section(
            'section_style_grid',
            [
                'label' => __('Grid', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'grid_gap',
            [
                'label' => __('Gap', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-events-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
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
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .mecua-event-card',
            ]
        );
        
        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __('Content Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                    'px' => ['min' => 100, 'max' => 400],
                ],
                'default' => ['size' => 180, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-card-image' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .mecua-event-card-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Price Badge
        $this->start_controls_section(
            'section_style_price',
            [
                'label' => __('Price Badge', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'price_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#D1FAE5',
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-price' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'price_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#065F46',
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-price' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .mecua-event-price',
            ]
        );
        
        $this->add_control(
            'price_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'price_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Date/Time Bar
        $this->start_controls_section(
            'section_style_date_bar',
            [
                'label' => __('Date/Time Bar', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'date_bar_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-date-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'date_bar_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-date-bar' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'date_bar_typography',
                'selector' => '{{WRAPPER}} .mecua-event-date-bar',
            ]
        );
        
        $this->add_responsive_control(
            'date_bar_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-date-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Category Badge
        $this->start_controls_section(
            'section_style_category',
            [
                'label' => __('Category Badge', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'category_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#D1FAE5',
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-category' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'category_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#065F46',
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-category' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'category_typography',
                'selector' => '{{WRAPPER}} .mecua-event-category',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Title
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Title', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mecua-event-title',
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
                    '{{WRAPPER}} .mecua-event-location' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'location_typography',
                'selector' => '{{WRAPPER}} .mecua-event-location',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Organizer
        $this->start_controls_section(
            'section_style_organizer',
            [
                'label' => __('Hosted By', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'organizer_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-event-organizer' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'organizer_typography',
                'selector' => '{{WRAPPER}} .mecua-event-organizer',
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (!is_user_logged_in()) {
            echo '<div class="mecua-login-required">';
            echo '<p>' . __('Please log in to view your events.', 'mec-starter-addons') . '</p>';
            echo '<a href="' . wp_login_url(get_permalink()) . '" class="mecua-btn mecua-btn-primary">' . __('Log In', 'mec-starter-addons') . '</a>';
            echo '</div>';
            return;
        }
        
        $user_id = get_current_user_id();
        $events = $this->get_user_events($user_id, $settings['event_type'], $settings['per_page']);
        
        if (empty($events)) {
            echo '<div class="mecua-empty-state">';
            echo '<p>' . esc_html($settings['empty_message']) . '</p>';
            echo '</div>';
            return;
        }
        
        echo '<div class="mecua-events-grid">';
        
        foreach ($events as $event) {
            $this->render_event_card($event, $settings);
        }
        
        echo '</div>';
    }
    
    private function get_user_events($user_id, $type, $limit) {
        global $wpdb;
        $now = current_time('Y-m-d');
        
        switch ($type) {
            case 'upcoming':
                // Events user has booked (from MEC bookings) - future only
                $bookings = $wpdb->get_col($wpdb->prepare(
                    "SELECT DISTINCT pm.meta_value 
                     FROM {$wpdb->prefix}mec_bookings b
                     INNER JOIN {$wpdb->postmeta} pm ON b.id = pm.post_id AND pm.meta_key = 'mec_event_id'
                     INNER JOIN {$wpdb->postmeta} pm2 ON pm.meta_value = pm2.post_id AND pm2.meta_key = 'mec_start_date'
                     WHERE b.user_id = %d 
                     AND pm2.meta_value >= %s
                     ORDER BY pm2.meta_value ASC
                     LIMIT %d",
                    $user_id, $now, $limit
                ));
                
                // If no MEC bookings table, try alternative approach
                if (empty($bookings)) {
                    $bookings = get_posts(array(
                        'post_type' => 'mec-books',
                        'posts_per_page' => $limit,
                        'meta_query' => array(
                            array(
                                'key' => 'mec_booking_user_id',
                                'value' => $user_id,
                            ),
                        ),
                        'fields' => 'ids',
                    ));
                    
                    $event_ids = array();
                    foreach ($bookings as $booking_id) {
                        $event_id = get_post_meta($booking_id, 'mec_event_id', true);
                        $start_date = get_post_meta($event_id, 'mec_start_date', true);
                        if ($event_id && $start_date >= $now) {
                            $event_ids[] = $event_id;
                        }
                    }
                    $bookings = array_unique($event_ids);
                }
                
                return $bookings;
                
            case 'past':
                // Past booked events + expired saved events
                $past_booked = get_posts(array(
                    'post_type' => 'mec-books',
                    'posts_per_page' => $limit,
                    'meta_query' => array(
                        array(
                            'key' => 'mec_booking_user_id',
                            'value' => $user_id,
                        ),
                    ),
                    'fields' => 'ids',
                ));
                
                $event_ids = array();
                foreach ($past_booked as $booking_id) {
                    $event_id = get_post_meta($booking_id, 'mec_event_id', true);
                    $start_date = get_post_meta($event_id, 'mec_start_date', true);
                    if ($event_id && $start_date < $now) {
                        $event_ids[] = $event_id;
                    }
                }
                
                // Add expired saved events
                $saved_table = $wpdb->prefix . 'mecas_saved_events';
                $saved = $wpdb->get_col($wpdb->prepare(
                    "SELECT se.event_id 
                     FROM $saved_table se
                     INNER JOIN {$wpdb->postmeta} pm ON se.event_id = pm.post_id AND pm.meta_key = 'mec_start_date'
                     WHERE se.user_id = %d 
                     AND pm.meta_value < %s
                     ORDER BY pm.meta_value DESC",
                    $user_id, $now
                ));
                
                $event_ids = array_unique(array_merge($event_ids, $saved));
                return array_slice($event_ids, 0, $limit);
                
            case 'saved':
                // Saved events (future only)
                $saved_table = $wpdb->prefix . 'mecas_saved_events';
                return $wpdb->get_col($wpdb->prepare(
                    "SELECT se.event_id 
                     FROM $saved_table se
                     INNER JOIN {$wpdb->postmeta} pm ON se.event_id = pm.post_id AND pm.meta_key = 'mec_start_date'
                     WHERE se.user_id = %d 
                     AND pm.meta_value >= %s
                     ORDER BY pm.meta_value ASC
                     LIMIT %d",
                    $user_id, $now, $limit
                ));
        }
        
        return array();
    }
    
    private function render_event_card($event_id, $settings) {
        $event = get_post($event_id);
        if (!$event) return;
        
        $thumbnail = get_the_post_thumbnail_url($event_id, 'medium_large');
        $title = get_the_title($event_id);
        $permalink = get_permalink($event_id);
        
        // MEC data
        $start_date = get_post_meta($event_id, 'mec_start_date', true);
        $start_time = get_post_meta($event_id, 'mec_start_time_hour', true);
        $start_time_min = get_post_meta($event_id, 'mec_start_time_minutes', true);
        $start_time_ampm = get_post_meta($event_id, 'mec_start_time_ampm', true);
        
        $location_id = get_post_meta($event_id, 'mec_location_id', true);
        $location = $location_id ? get_term($location_id, 'mec_location') : null;
        $location_name = $location ? $location->name : '';
        
        $organizer_id = get_post_meta($event_id, 'mec_organizer_id', true);
        $organizer = $organizer_id ? get_term($organizer_id, 'mec_organizer') : null;
        $organizer_name = $organizer ? $organizer->name : '';
        
        // Category
        $categories = get_the_terms($event_id, 'mec_category');
        $category_name = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : '';
        
        // Price
        $tickets = get_post_meta($event_id, 'mec_tickets', true);
        $price = '';
        if (!empty($tickets) && is_array($tickets)) {
            $min_price = PHP_INT_MAX;
            foreach ($tickets as $ticket) {
                if (isset($ticket['price']) && $ticket['price'] < $min_price) {
                    $min_price = $ticket['price'];
                }
            }
            if ($min_price !== PHP_INT_MAX) {
                $price = '$' . number_format($min_price, 2);
            }
        }
        
        // Format date
        $formatted_date = '';
        if ($start_date) {
            $date_obj = new DateTime($start_date);
            $formatted_date = $date_obj->format('D, M j');
            if ($start_time) {
                $formatted_date .= ' | ' . $start_time . ':' . str_pad($start_time_min, 2, '0', STR_PAD_LEFT) . ' ' . strtoupper($start_time_ampm);
            }
        }
        
        ?>
        <div class="mecua-event-card">
            <a href="<?php echo esc_url($permalink); ?>" class="mecua-event-card-link">
                <?php if ($settings['show_image'] === 'yes'): ?>
                <div class="mecua-event-card-image" style="background-image: url('<?php echo esc_url($thumbnail ?: MECAS_PLUGIN_URL . 'assets/images/placeholder.jpg'); ?>');">
                    <?php if ($settings['show_price'] === 'yes' && $price): ?>
                    <span class="mecua-event-price"><?php echo esc_html($price); ?></span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($settings['show_date'] === 'yes' || $settings['show_category'] === 'yes'): ?>
                <div class="mecua-event-date-bar">
                    <?php if ($settings['show_date'] === 'yes' && $formatted_date): ?>
                    <span class="mecua-event-date"><?php echo esc_html($formatted_date); ?></span>
                    <?php endif; ?>
                    <?php if ($settings['show_category'] === 'yes' && $category_name): ?>
                    <span class="mecua-event-category"><?php echo esc_html($category_name); ?></span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <div class="mecua-event-card-content">
                    <?php if ($settings['show_title'] === 'yes'): ?>
                    <h3 class="mecua-event-title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_location'] === 'yes' && $location_name): ?>
                    <p class="mecua-event-location"><?php echo esc_html($location_name); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_organizer'] === 'yes' && $organizer_name): ?>
                    <p class="mecua-event-organizer"><?php _e('Hosted by', 'mec-starter-addons'); ?> <?php echo esc_html($organizer_name); ?></p>
                    <?php endif; ?>
                </div>
            </a>
        </div>
        <?php
    }
}
