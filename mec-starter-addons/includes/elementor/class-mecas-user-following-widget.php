<?php
/**
 * User Following Widget - Shows organizers/teachers the user follows
 */

if (!defined('ABSPATH')) exit;

class MECAS_User_Following_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_user_following';
    }
    
    public function get_title() {
        return __('User Following', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-heart';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['following', 'favorites', 'teachers', 'organizers', 'heart'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('General', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
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
                    '{{WRAPPER}} .mecua-following-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );
        
        $this->add_control(
            'per_page',
            [
                'label' => __('Per Page', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 9,
                'min' => 1,
                'max' => 50,
            ]
        );
        
        $this->add_control(
            'empty_message',
            [
                'label' => __('Empty Message', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('You are not following anyone yet.', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'show_tagline',
            [
                'label' => __('Show Tagline', 'mec-starter-addons'),
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
            'show_heart',
            [
                'label' => __('Show Heart Icon', 'mec-starter-addons'),
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
                    '{{WRAPPER}} .mecua-following-grid' => 'gap: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .mecua-following-card' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .mecua-following-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .mecua-following-card',
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
                'default' => ['size' => 200, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-following-card-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Location Bar
        $this->start_controls_section(
            'section_style_location_bar',
            [
                'label' => __('Location Bar', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'location_bar_bg',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E8B4A0',
                'selectors' => [
                    '{{WRAPPER}} .mecua-following-location-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'location_bar_text',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .mecua-following-location-bar' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'location_bar_typography',
                'selector' => '{{WRAPPER}} .mecua-following-location-bar',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Heart Icon
        $this->start_controls_section(
            'section_style_heart',
            [
                'label' => __('Heart Icon', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'heart_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E8B4A0',
                'selectors' => [
                    '{{WRAPPER}} .mecua-following-heart' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mecua-following-heart svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'heart_size',
            [
                'label' => __('Size', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 12, 'max' => 48],
                ],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-following-heart svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Name
        $this->start_controls_section(
            'section_style_name',
            [
                'label' => __('Name', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'name_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-following-name' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .mecua-following-name',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Tagline
        $this->start_controls_section(
            'section_style_tagline',
            [
                'label' => __('Tagline', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'tagline_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-following-tagline' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tagline_typography',
                'selector' => '{{WRAPPER}} .mecua-following-tagline',
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (!is_user_logged_in()) {
            echo '<div class="mecua-login-required">';
            echo '<p>' . __('Please log in to see who you follow.', 'mec-starter-addons') . '</p>';
            echo '<a href="' . wp_login_url(get_permalink()) . '" class="mecua-btn mecua-btn-primary">' . __('Log In', 'mec-starter-addons') . '</a>';
            echo '</div>';
            return;
        }
        
        $user_id = get_current_user_id();
        $following = mecas_get_following($user_id);
        
        if (empty($following)) {
            echo '<div class="mecua-empty-state">';
            echo '<p>' . esc_html($settings['empty_message']) . '</p>';
            echo '</div>';
            return;
        }
        
        // Limit results
        $following = array_slice($following, 0, $settings['per_page']);
        
        echo '<div class="mecua-following-grid">';
        
        foreach ($following as $organizer_id) {
            $this->render_organizer_card($organizer_id, $settings);
        }
        
        echo '</div>';
    }
    
    private function render_organizer_card($organizer_id, $settings) {
        $organizer = get_term($organizer_id, 'mec_organizer');
        if (!$organizer || is_wp_error($organizer)) return;
        
        $thumbnail = get_term_meta($organizer_id, 'thumbnail', true);
        $city = get_term_meta($organizer_id, 'mecas_organizer_city', true);
        $state = get_term_meta($organizer_id, 'mecas_organizer_state', true);
        $tagline = get_term_meta($organizer_id, 'mecas_organizer_tagline', true);
        
        $location = '';
        if ($city && $state) {
            $location = $city . ', ' . $state;
        } elseif ($city) {
            $location = $city;
        } elseif ($state) {
            $location = $state;
        }
        
        // Get profile URL
        $teacher_slug = get_option('mecom_teacher_slug', 'teacher');
        $profile_url = home_url('/' . $teacher_slug . '/' . $organizer->slug . '/');
        
        ?>
        <div class="mecua-following-card" data-organizer-id="<?php echo esc_attr($organizer_id); ?>">
            <a href="<?php echo esc_url($profile_url); ?>" class="mecua-following-card-link">
                <div class="mecua-following-card-image" style="background-image: url('<?php echo esc_url($thumbnail ?: MECAS_PLUGIN_URL . 'assets/images/placeholder-person.jpg'); ?>');">
                </div>
                
                <?php if ($settings['show_location'] === 'yes' || $settings['show_heart'] === 'yes'): ?>
                <div class="mecua-following-location-bar">
                    <?php if ($settings['show_location'] === 'yes' && $location): ?>
                    <span class="mecua-following-location"><?php echo esc_html($location); ?></span>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_heart'] === 'yes'): ?>
                    <button type="button" class="mecua-following-heart mecua-unfollow-btn" data-organizer-id="<?php echo esc_attr($organizer_id); ?>" title="<?php esc_attr_e('Unfollow', 'mec-starter-addons'); ?>">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <div class="mecua-following-card-content">
                    <h3 class="mecua-following-name"><?php echo esc_html($organizer->name); ?></h3>
                    
                    <?php if ($settings['show_tagline'] === 'yes' && $tagline): ?>
                    <p class="mecua-following-tagline">"<?php echo esc_html($tagline); ?>"</p>
                    <?php endif; ?>
                </div>
            </a>
        </div>
        <?php
    }
}
