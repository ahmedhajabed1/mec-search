<?php
/**
 * Event Host Widget - Shows organizer info on event pages (photo, name, bio, social)
 */

if (!defined('ABSPATH')) exit;

class MECAS_Event_Host_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_event_host';
    }
    
    public function get_title() {
        return __('About the Host', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-person';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['event', 'host', 'organizer', 'teacher', 'about', 'profile'];
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
                'label' => __('Show Photo', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_name',
            [
                'label' => __('Show Name', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'link_name',
            [
                'label' => __('Link Name to Profile', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_name' => 'yes'],
            ]
        );
        
        $this->add_control(
            'show_follow',
            [
                'label' => __('Show Follow Button', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_name' => 'yes'],
            ]
        );
        
        $this->add_control(
            'show_bio',
            [
                'label' => __('Show Bio', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'bio_length',
            [
                'label' => __('Bio Length (words)', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 80,
                'min' => 20,
                'max' => 500,
                'condition' => ['show_bio' => 'yes'],
            ]
        );
        
        $this->add_control(
            'show_social',
            [
                'label' => __('Show Social Links', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => __('Horizontal (Image Left)', 'mec-starter-addons'),
                    'vertical' => __('Vertical (Image Top)', 'mec-starter-addons'),
                ],
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'preview_organizer_id',
            [
                'label' => __('Preview Organizer', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_organizers_list(),
                'description' => __('Select an organizer for preview.', 'mec-starter-addons'),
                'separator' => 'before',
            ]
        );
        
        $this->end_controls_section();
        
        // Custom Icons Section
        $this->start_controls_section(
            'section_custom_icons',
            [
                'label' => __('Custom Icons', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => ['show_social' => 'yes'],
            ]
        );
        
        $this->add_control('icons_info', [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => '<div style="background: #e8f4fc; padding: 10px; border-radius: 4px; font-size: 12px; color: #1e3a5f;"><strong>Custom Icons:</strong> Override default icons by selecting from the library or uploading custom SVGs.</div>',
        ]);

        $this->add_control('icon_instagram', [
            'label' => __('Instagram Icon', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
            'default' => [],
        ]);

        $this->add_control('icon_x', [
            'label' => __('X (Twitter) Icon', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
            'default' => [],
        ]);

        $this->add_control('icon_facebook', [
            'label' => __('Facebook Icon', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
            'default' => [],
        ]);

        $this->add_control('icon_tiktok', [
            'label' => __('TikTok Icon', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
            'default' => [],
        ]);

        $this->add_control('icon_youtube', [
            'label' => __('YouTube Icon', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
            'default' => [],
        ]);

        $this->add_control('icon_linkedin', [
            'label' => __('LinkedIn Icon', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
            'default' => [],
        ]);
        
        $this->end_controls_section();
        
        // Style Section - Container
        $this->start_controls_section(
            'section_style_container',
            [
                'label' => __('Container', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'container_bg',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .mecas-host-wrapper',
            ]
        );
        
        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => '{{WRAPPER}} .mecas-host-wrapper',
            ]
        );
        
        $this->add_responsive_control(
            'content_gap',
            [
                'label' => __('Gap Between Image & Content', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 80]],
                'default' => ['size' => 30, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-wrapper.mecas-layout-horizontal' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mecas-host-wrapper.mecas-layout-vertical' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Image
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __('Photo', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_image' => 'yes'],
            ]
        );
        
        $this->add_responsive_control(
            'image_width',
            [
                'label' => __('Width', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 80, 'max' => 400],
                    '%' => ['min' => 10, 'max' => 50],
                ],
                'default' => ['size' => 180, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-image' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Height', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 80, 'max' => 400]],
                'default' => ['size' => 220, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '8',
                    'right' => '8',
                    'bottom' => '8',
                    'left' => '8',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .mecas-host-image',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'selector' => '{{WRAPPER}} .mecas-host-image',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Name
        $this->start_controls_section(
            'section_style_name',
            [
                'label' => __('Name', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_name' => 'yes'],
            ]
        );
        
        $this->add_control(
            'name_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-name' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mecas-host-name a' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'name_hover_color',
            [
                'label' => __('Hover Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-name a:hover' => 'color: {{VALUE}};',
                ],
                'condition' => ['link_name' => 'yes'],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .mecas-host-name, {{WRAPPER}} .mecas-host-name a',
            ]
        );
        
        $this->add_responsive_control(
            'name_margin',
            [
                'label' => __('Margin', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '15',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-name-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Follow Button
        $this->start_controls_section(
            'section_style_follow',
            [
                'label' => __('Follow Button', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_follow' => 'yes'],
            ]
        );
        
        $this->add_control(
            'follow_color',
            [
                'label' => __('Icon Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#9CA3AF',
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-follow svg' => 'stroke: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'follow_active_color',
            [
                'label' => __('Active/Hover Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#EF4444',
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-follow:hover svg' => 'stroke: {{VALUE}} !important;',
                    '{{WRAPPER}} .mecas-host-follow.mecas-following svg' => 'stroke: {{VALUE}} !important; fill: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'follow_size',
            [
                'label' => __('Icon Size', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 16, 'max' => 40]],
                'default' => ['size' => 24, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-follow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Bio
        $this->start_controls_section(
            'section_style_bio',
            [
                'label' => __('Bio', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_bio' => 'yes'],
            ]
        );
        
        $this->add_control(
            'bio_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4B5563',
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-bio' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'bio_typography',
                'selector' => '{{WRAPPER}} .mecas-host-bio',
            ]
        );
        
        $this->add_responsive_control(
            'bio_margin',
            [
                'label' => __('Margin', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '20',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecas-host-bio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Social Icons
        $this->start_controls_section(
            'section_style_social',
            [
                'label' => __('Social Icons', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_social' => 'yes'],
            ]
        );
        
        $this->add_responsive_control('social_icon_size', [
            'label' => __('Icon Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 12, 'max' => 40]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mecas-host-wrapper .mecas-host-social a svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
                '{{WRAPPER}} .mecas-host-wrapper .mecas-host-social a i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
            ],
        ]);

        $this->add_responsive_control('social_button_size', [
            'label' => __('Button Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 24, 'max' => 60]],
            'default' => ['size' => 40, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-host-wrapper .mecas-host-social a' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('social_gap', [
            'label' => __('Gap', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'default' => ['size' => 12, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-host-wrapper .mecas-host-social' => 'gap: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_control('social_icon_color', [
            'label' => __('Icon Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => [
                '{{WRAPPER}} .mecas-host-wrapper .mecas-host-social a svg' => 'fill: {{VALUE}} !important;',
                '{{WRAPPER}} .mecas-host-wrapper .mecas-host-social a i' => 'color: {{VALUE}} !important;',
            ],
        ]);

        $this->add_control('social_bg_color', [
            'label' => __('Background Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-host-wrapper .mecas-host-social a' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('social_bg_color_hover', [
            'label' => __('Background Color (Hover)', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#374151',
            'selectors' => ['{{WRAPPER}} .mecas-host-wrapper .mecas-host-social a:hover' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('social_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => ['px' => ['min' => 0, 'max' => 30], '%' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 50, 'unit' => '%'],
            'selectors' => ['{{WRAPPER}} .mecas-host-wrapper .mecas-host-social a' => 'border-radius: {{SIZE}}{{UNIT}} !important;'],
        ]);
        
        $this->end_controls_section();
    }
    
    private function get_organizers_list() {
        $options = ['' => __('— Select Organizer —', 'mec-starter-addons')];
        $organizers = get_terms([
            'taxonomy' => 'mec_organizer',
            'hide_empty' => false,
            'number' => 100,
        ]);
        if (!empty($organizers) && !is_wp_error($organizers)) {
            foreach ($organizers as $organizer) {
                $options[$organizer->term_id] = $organizer->name;
            }
        }
        return $options;
    }
    
    private function get_default_icons() {
        return [
            'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
            'twitter' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
            'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
            'tiktok' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>',
            'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
            'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
        ];
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        
        // Get organizer - priority: preview selection, then event's organizer
        $organizer_id = 0;
        $organizer = null;
        
        if (!empty($settings['preview_organizer_id'])) {
            $organizer_id = intval($settings['preview_organizer_id']);
            $organizer = get_term($organizer_id, 'mec_organizer');
        } else {
            $event_id = get_the_ID();
            if (get_post_type($event_id) !== 'mec-events') {
                $event_id = get_query_var('mec_event_id', 0);
            }
            if ($event_id) {
                $organizer_id = get_post_meta($event_id, 'mec_organizer_id', true);
                $organizer = $organizer_id ? get_term($organizer_id, 'mec_organizer') : null;
            }
        }
        
        if (!$organizer || is_wp_error($organizer)) {
            if ($is_editor) {
                $this->render_editor_placeholder($settings);
            }
            return;
        }
        
        $organizer_data = function_exists('mecas_get_organizer_data') ? mecas_get_organizer_data($organizer_id) : [];
        
        $name = $organizer->name;
        $bio = isset($organizer_data['bio']) ? $organizer_data['bio'] : $organizer->description;
        $thumbnail = isset($organizer_data['thumbnail']) ? $organizer_data['thumbnail'] : '';
        
        if (!empty($bio)) {
            $words = explode(' ', wp_strip_all_tags($bio));
            $bio_length = intval($settings['bio_length']);
            if (count($words) > $bio_length) {
                $bio = implode(' ', array_slice($words, 0, $bio_length)) . '...';
            }
        }
        
        $teacher_slug = get_option('mecom_teacher_slug', 'teacher');
        $profile_url = home_url('/' . $teacher_slug . '/' . $organizer->slug . '/');
        
        // Get social links - only those that have URLs
        $social_links = [];
        $social_platforms = ['instagram', 'twitter', 'facebook', 'tiktok', 'youtube', 'linkedin'];
        foreach ($social_platforms as $platform) {
            $key = 'mecas_organizer_' . $platform;
            if (!empty($organizer_data[$platform])) {
                $social_links[$platform] = $organizer_data[$platform];
            } else {
                $meta_value = get_term_meta($organizer_id, $key, true);
                if (!empty($meta_value)) {
                    $social_links[$platform] = $meta_value;
                }
            }
        }
        
        $is_following = is_user_logged_in() && function_exists('mecas_is_following') && mecas_is_following($organizer_id);
        $layout_class = 'mecas-layout-' . $settings['layout'];
        $default_icons = $this->get_default_icons();
        
        ?>
        <div class="mecas-host-wrapper <?php echo esc_attr($layout_class); ?>">
            <?php if ($settings['show_image'] === 'yes'): ?>
            <div class="mecas-host-image" style="background-image: url('<?php echo esc_url($thumbnail); ?>');"></div>
            <?php endif; ?>
            
            <div class="mecas-host-content">
                <?php if ($settings['show_name'] === 'yes'): ?>
                <div class="mecas-host-name-row">
                    <h3 class="mecas-host-name">
                        <?php if ($settings['link_name'] === 'yes'): ?>
                        <a href="<?php echo esc_url($profile_url); ?>"><?php echo esc_html($name); ?></a>
                        <?php else: ?>
                        <?php echo esc_html($name); ?>
                        <?php endif; ?>
                    </h3>
                    
                    <?php if ($settings['show_follow'] === 'yes'): ?>
                    <button type="button" class="mecas-host-follow <?php echo $is_following ? 'mecas-following' : ''; ?>" data-organizer-id="<?php echo esc_attr($organizer_id); ?>" style="background: transparent !important; background-color: transparent !important; border: none !important; box-shadow: none !important;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="fill: none !important;">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($settings['show_bio'] === 'yes' && !empty($bio)): ?>
                <p class="mecas-host-bio"><?php echo esc_html($bio); ?></p>
                <?php endif; ?>
                
                <?php if ($settings['show_social'] === 'yes' && !empty($social_links)): ?>
                <div class="mecas-host-social">
                    <?php foreach ($social_links as $platform => $url): 
                        $icon_key = 'icon_' . ($platform === 'twitter' ? 'x' : $platform);
                        $custom_icon = !empty($settings[$icon_key]['value']) ? $settings[$icon_key] : null;
                    ?>
                    <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" title="<?php echo esc_attr(ucfirst($platform)); ?>">
                        <?php 
                        if ($custom_icon) {
                            \Elementor\Icons_Manager::render_icon($custom_icon, ['aria-hidden' => 'true']);
                        } else {
                            echo $default_icons[$platform] ?? '';
                        }
                        ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <style>
        .mecas-host-wrapper { display: flex; }
        .mecas-host-wrapper.mecas-layout-horizontal { flex-direction: row; align-items: flex-start; gap: 30px; }
        .mecas-host-wrapper.mecas-layout-vertical { flex-direction: column; align-items: center; text-align: center; gap: 20px; }
        .mecas-host-image { width: 180px; min-width: 180px; height: 220px; background-size: cover; background-position: center; background-color: #E5E7EB; border-radius: 8px; }
        .mecas-host-content { flex: 1; }
        .mecas-host-name-row { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; }
        .mecas-layout-vertical .mecas-host-name-row { justify-content: center; }
        .mecas-host-name { margin: 0; font-size: 28px; font-weight: 400; color: #1F2937; }
        .mecas-host-name a { text-decoration: none; color: inherit; font-size: inherit; font-weight: inherit; font-family: inherit; }
        .mecas-host-name a:hover { color: #4B5563; }
        /* Follow button - NO background */
        .mecas-host-follow,
        button.mecas-host-follow,
        .mecas-host-wrapper .mecas-host-follow { 
            background: transparent !important; 
            background-color: transparent !important; 
            border: none !important; 
            padding: 0 !important; 
            cursor: pointer; 
            color: #9CA3AF; 
            transition: color 0.2s ease; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center;
            box-shadow: none !important;
            outline: none !important;
        }
        .mecas-host-follow:hover { color: #EF4444 !important; }
        .mecas-host-follow.mecas-following { color: #EF4444 !important; }
        .mecas-host-follow.mecas-following svg { fill: currentColor !important; }
        .mecas-host-follow svg { width: 24px; height: 24px; fill: none !important; stroke: currentColor; }
        .mecas-host-bio { margin: 0 0 20px 0; font-size: 15px; line-height: 1.7; color: #4B5563; }
        /* Social icons - defaults that Elementor controls can override */
        .mecas-host-social { display: flex; align-items: center; flex-wrap: wrap; gap: 12px; }
        .mecas-layout-vertical .mecas-host-social { justify-content: center; }
        .mecas-host-social a { 
            display: flex; 
            align-items: center; 
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #1F2937;
            border-radius: 50%;
            transition: background-color 0.2s, transform 0.2s; 
            text-decoration: none; 
        }
        .mecas-host-social a:hover { transform: scale(1.1); background-color: #374151; }
        .mecas-host-social a svg { width: 16px; height: 16px; fill: #fff; }
        .mecas-host-social a i { font-size: 16px; color: #fff; }
        </style>
        
        <script>
        (function($) {
            $(document).ready(function() {
                $('.mecas-host-follow').off('click').on('click', function() {
                    var $btn = $(this);
                    var organizerId = $btn.data('organizer-id');
                    var isFollowing = $btn.hasClass('mecas-following');
                    var action = isFollowing ? 'mecas_unfollow_organizer' : 'mecas_follow_organizer';
                    
                    $.ajax({
                        url: mecas_ajax.ajax_url,
                        type: 'POST',
                        data: {
                            action: action,
                            organizer_id: organizerId,
                            nonce: mecas_ajax.nonce
                        },
                        success: function(response) {
                            if (response.success) {
                                $btn.toggleClass('mecas-following');
                            }
                        }
                    });
                });
            });
        })(jQuery);
        </script>
        <?php
    }
    
    private function render_editor_placeholder($settings) {
        $layout_class = 'mecas-layout-' . $settings['layout'];
        ?>
        <div class="mecas-host-wrapper <?php echo esc_attr($layout_class); ?>">
            <?php if ($settings['show_image'] === 'yes'): ?>
            <div class="mecas-host-image" style="background-color: #E5E7EB; position: relative;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <?php endif; ?>
            
            <div class="mecas-host-content">
                <?php if ($settings['show_name'] === 'yes'): ?>
                <div class="mecas-host-name-row">
                    <h3 class="mecas-host-name">Jane Doe</h3>
                    
                    <?php if ($settings['show_follow'] === 'yes'): ?>
                    <button type="button" class="mecas-host-follow" style="background: transparent !important; background-color: transparent !important; border: none !important; box-shadow: none !important;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="fill: none !important;">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($settings['show_bio'] === 'yes'): ?>
                <p class="mecas-host-bio">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content here, making it look like readable English.</p>
                <?php endif; ?>
                
                <?php if ($settings['show_social'] === 'yes'): ?>
                <div class="mecas-host-social">
                    <span class="mecas-social-placeholder"><?php esc_html_e('Social icons will appear based on organizer settings', 'mec-starter-addons'); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <p style="padding: 10px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; font-size: 12px; margin-top: 15px;">
            <strong><?php esc_html_e('Tip:', 'mec-starter-addons'); ?></strong> 
            <?php esc_html_e('Select a "Preview Organizer" in the Content tab to see actual host data.', 'mec-starter-addons'); ?>
        </p>
        
        <style>
        .mecas-host-wrapper { display: flex; }
        .mecas-host-wrapper.mecas-layout-horizontal { flex-direction: row; align-items: flex-start; gap: 30px; }
        .mecas-host-wrapper.mecas-layout-vertical { flex-direction: column; align-items: center; text-align: center; gap: 20px; }
        .mecas-host-image { width: 180px; min-width: 180px; height: 220px; background-size: cover; background-position: center; background-color: #E5E7EB; border-radius: 8px; position: relative; }
        .mecas-host-content { flex: 1; }
        .mecas-host-name-row { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; }
        .mecas-layout-vertical .mecas-host-name-row { justify-content: center; }
        .mecas-host-name { margin: 0; font-size: 28px; font-weight: 400; color: #1F2937; }
        .mecas-host-name a { text-decoration: none; color: inherit; font-size: inherit; font-weight: inherit; font-family: inherit; }
        .mecas-host-follow,
        button.mecas-host-follow { 
            background: transparent !important; 
            background-color: transparent !important; 
            border: none !important; 
            padding: 0 !important; 
            cursor: pointer; 
            color: #9CA3AF; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center;
            box-shadow: none !important;
        }
        .mecas-host-follow svg { width: 24px; height: 24px; fill: none !important; stroke: currentColor; }
        .mecas-host-bio { margin: 0 0 20px 0; font-size: 15px; line-height: 1.7; color: #4B5563; }
        .mecas-host-social { display: flex; align-items: center; gap: 12px; }
        .mecas-layout-vertical .mecas-host-social { justify-content: center; }
        .mecas-social-placeholder { font-size: 13px; color: #9CA3AF; font-style: italic; }
        </style>
        <?php
    }
}
