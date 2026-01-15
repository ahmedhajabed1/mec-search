<?php
/**
 * Organizer Profile Widget for Elementor
 */

if (!defined('ABSPATH')) exit;

class MECOM_Organizer_Profile_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecom-organizer-profile';
    }

    public function get_title() {
        return __('Organizer Profile', 'mec-organizer-manager');
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['mec-organizer-manager'];
    }

    public function get_keywords() {
        return ['organizer', 'teacher', 'profile', 'mec'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'mec-organizer-manager'),
        ]);

        $this->add_control('layout', [
            'label' => __('Layout', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'vertical',
            'options' => [
                'vertical' => __('Vertical', 'mec-organizer-manager'),
                'horizontal' => __('Horizontal', 'mec-organizer-manager'),
            ],
        ]);

        $this->add_control('show_photo', [
            'label' => __('Show Photo', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_name', [
            'label' => __('Show Name', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_location', [
            'label' => __('Show Location', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_tagline', [
            'label' => __('Show Tagline', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_share_button', [
            'label' => __('Show Share Button', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('share_button_text', [
            'label' => __('Share Button Text', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Share Profile', 'mec-organizer-manager'),
            'condition' => ['show_share_button' => 'yes'],
        ]);

        $this->add_control('show_favorite_button', [
            'label' => __('Show Favorite Button', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'no',
        ]);

        $this->add_control('preview_organizer_id', [
            'label' => __('Preview Organizer', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $this->get_organizers_list(),
            'description' => __('Select an organizer to preview in the editor', 'mec-organizer-manager'),
        ]);

        $this->end_controls_section();

        // Photo Style
        $this->start_controls_section('section_style_photo', [
            'label' => __('Photo', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_photo' => 'yes'],
        ]);

        $this->add_responsive_control('photo_width', [
            'label' => __('Width', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 100, 'max' => 400]],
            'default' => ['size' => 200, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-photo' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('photo_height', [
            'label' => __('Height', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 100, 'max' => 500]],
            'default' => ['size' => 250, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-photo' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('photo_border_radius', [
            'label' => __('Border Radius', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-photo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // Name Style
        $this->start_controls_section('section_style_name', [
            'label' => __('Name', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_name' => 'yes'],
        ]);

        $this->add_control('name_color', [
            'label' => __('Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecom-org-name' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'name_typography',
            'selector' => '{{WRAPPER}} .mecom-org-name',
        ]);

        $this->end_controls_section();

        // Location Style
        $this->start_controls_section('section_style_location', [
            'label' => __('Location', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_location' => 'yes'],
        ]);

        $this->add_control('location_color', [
            'label' => __('Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#6B7280',
            'selectors' => ['{{WRAPPER}} .mecom-org-location' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'location_typography',
            'selector' => '{{WRAPPER}} .mecom-org-location',
        ]);

        $this->end_controls_section();

        // Share Button Style
        $this->start_controls_section('section_style_share', [
            'label' => __('Share Button', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_share_button' => 'yes'],
        ]);

        $this->add_control('share_bg_color', [
            'label' => __('Background Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#2D3748',
            'selectors' => ['{{WRAPPER}} .mecom-org-share-button' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('share_text_color', [
            'label' => __('Text Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecom-org-share-button' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('share_padding', [
            'label' => __('Padding', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => ['top' => '12', 'right' => '24', 'bottom' => '12', 'left' => '24', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-share-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('share_border_radius', [
            'label' => __('Border Radius', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-share-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();
    }

    private function get_organizers_list() {
        $options = ['' => __('Current Organizer', 'mec-organizer-manager')];
        
        $organizers = get_terms([
            'taxonomy' => 'mec_organizer',
            'hide_empty' => false,
        ]);
        
        if (!is_wp_error($organizers)) {
            foreach ($organizers as $organizer) {
                $options[$organizer->term_id] = $organizer->name;
            }
        }
        
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $organizer = $this->get_current_organizer($settings);
        
        if (!$organizer && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            return;
        }
        
        // Placeholder for editor
        if (!$organizer) {
            $organizer = [
                'name' => 'Jane Doe',
                'thumbnail' => '',
                'location' => 'Tampa, FL',
                'tagline' => 'Mahjong Enthusiast & Teacher',
            ];
        }
        
        $layout_class = 'mecom-org-profile-' . $settings['layout'];
        ?>
        <div class="mecom-org-profile <?php echo esc_attr($layout_class); ?>">
            <?php if ($settings['show_photo'] === 'yes'): ?>
            <div class="mecom-org-photo-wrap">
                <?php if (!empty($organizer['thumbnail'])): ?>
                    <img src="<?php echo esc_url($organizer['thumbnail']); ?>" alt="<?php echo esc_attr($organizer['name']); ?>" class="mecom-org-photo">
                <?php else: ?>
                    <div class="mecom-org-photo mecom-org-photo-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                <?php endif; ?>
                
                <?php if ($settings['show_share_button'] === 'yes'): ?>
                <button type="button" class="mecom-org-share-button mecom-share-button" data-url="<?php echo esc_url($organizer['url'] ?? ''); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="18" cy="5" r="3"></circle>
                        <circle cx="6" cy="12" r="3"></circle>
                        <circle cx="18" cy="19" r="3"></circle>
                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                    </svg>
                    <?php echo esc_html($settings['share_button_text']); ?>
                </button>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <div class="mecom-org-info">
                <?php if ($settings['show_name'] === 'yes'): ?>
                <div class="mecom-org-name-wrap">
                    <h1 class="mecom-org-name"><?php echo esc_html($organizer['name']); ?></h1>
                    <?php if ($settings['show_favorite_button'] === 'yes'): ?>
                    <button type="button" class="mecom-org-favorite-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($settings['show_location'] === 'yes' && !empty($organizer['location'])): ?>
                <p class="mecom-org-location">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <?php echo esc_html($organizer['location']); ?>
                </p>
                <?php endif; ?>
                
                <?php if ($settings['show_tagline'] === 'yes' && !empty($organizer['tagline'])): ?>
                <p class="mecom-org-tagline"><?php echo esc_html($organizer['tagline']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <style>
        .mecom-org-profile {
            display: flex;
            gap: 30px;
        }
        .mecom-org-profile-vertical {
            flex-direction: column;
            align-items: flex-start;
        }
        .mecom-org-profile-horizontal {
            flex-direction: row;
            align-items: flex-start;
        }
        .mecom-org-photo-wrap {
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }
        .mecom-org-photo {
            object-fit: cover;
        }
        .mecom-org-photo-placeholder {
            background: linear-gradient(135deg, #E5E7EB 0%, #D1D5DB 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .mecom-org-photo-placeholder svg {
            width: 80px;
            height: 80px;
            color: #9CA3AF;
        }
        .mecom-org-share-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: opacity 0.2s;
        }
        .mecom-org-share-button:hover {
            opacity: 0.9;
        }
        .mecom-org-name-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .mecom-org-name {
            margin: 0;
        }
        .mecom-org-favorite-button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            color: #9CA3AF;
            transition: color 0.2s;
        }
        .mecom-org-favorite-button:hover {
            color: #EF4444;
        }
        .mecom-org-location {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 8px 0 0 0;
        }
        .mecom-org-tagline {
            font-style: italic;
            margin: 8px 0 0 0;
        }
        </style>
        <?php
    }

    private function get_current_organizer($settings) {
        $organizer_id = null;
        
        // Check for preview organizer in editor
        if (!empty($settings['preview_organizer_id'])) {
            $organizer_id = intval($settings['preview_organizer_id']);
        }
        // Check query var (from /teacher/slug/ pages)
        elseif (get_query_var('mecom_organizer_id')) {
            $organizer_id = get_query_var('mecom_organizer_id');
        }
        // Check if we're on an organizer archive
        elseif (is_tax('mec_organizer')) {
            $term = get_queried_object();
            if ($term) {
                $organizer_id = $term->term_id;
            }
        }

        if (!$organizer_id) {
            return null;
        }

        return mecom_get_organizer_data($organizer_id);
    }
}
