<?php
/**
 * MEC Organizer Manager - Organizer Social Widget
 * Displays social media links with customizable icons from Elementor library
 */

if (!defined('ABSPATH')) exit;

class MECOM_Organizer_Social_Widget extends \Elementor\Widget_Base {
    
    public function get_name() { 
        return 'mecom-organizer-social'; 
    }
    
    public function get_title() { 
        return __('Organizer Social', 'mec-organizer-manager'); 
    }
    
    public function get_icon() { 
        return 'eicon-social-icons'; 
    }
    
    public function get_categories() { 
        return ['mec-organizer-manager']; 
    }

    protected function register_controls() {
        
        // === CONTENT TAB ===
        
        // Title Section
        $this->start_controls_section('section_title', [
            'label' => __('Title', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('show_title', [
            'label' => __('Show Title', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('title_text', [
            'label' => __('Title', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Links', 'mec-organizer-manager'),
            'condition' => ['show_title' => 'yes'],
        ]);
        
        $this->add_control('show_title_line', [
            'label' => __('Show Title Line', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_title' => 'yes'],
        ]);
        
        $this->end_controls_section();

        // Platform Icons Section - ALWAYS VISIBLE
        $this->start_controls_section('section_platform_icons', [
            'label' => __('Platform Icons', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('instagram_icon', [
            'label' => __('Instagram Icon', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'fab fa-instagram',
                'library' => 'fa-brands',
            ],
            'recommended' => [
                'fa-brands' => ['instagram'],
                'fa-solid' => ['camera', 'image'],
            ],
        ]);

        $this->add_control('twitter_icon', [
            'label' => __('X (Twitter) Icon', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'fab fa-x-twitter',
                'library' => 'fa-brands',
            ],
            'recommended' => [
                'fa-brands' => ['x-twitter', 'twitter'],
            ],
        ]);

        $this->add_control('facebook_icon', [
            'label' => __('Facebook Icon', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'fab fa-facebook-f',
                'library' => 'fa-brands',
            ],
            'recommended' => [
                'fa-brands' => ['facebook-f', 'facebook'],
            ],
        ]);

        $this->add_control('tiktok_icon', [
            'label' => __('TikTok Icon', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'fab fa-tiktok',
                'library' => 'fa-brands',
            ],
            'recommended' => [
                'fa-brands' => ['tiktok'],
            ],
        ]);

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section('section_layout', [
            'label' => __('Layout', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_responsive_control('alignment', [
            'label' => __('Alignment', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => __('Left', 'mec-organizer-manager'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'mec-organizer-manager'),
                    'icon' => 'eicon-text-align-center',
                ],
                'flex-end' => [
                    'title' => __('Right', 'mec-organizer-manager'),
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'default' => 'flex-start',
            'selectors' => [
                '{{WRAPPER}} .mecom-social-links' => 'justify-content: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('preview_organizer_id', [
            'label' => __('Preview Organizer', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $this->get_organizers_list(),
            'description' => __('Select an organizer for preview.', 'mec-organizer-manager'),
        ]);

        $this->end_controls_section();

        // === STYLE TAB ===

        // Title Style
        $this->start_controls_section('section_style_title', [
            'label' => __('Title', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_title' => 'yes'],
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecom-org-social-title' => 'color: {{VALUE}};'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .mecom-org-social-title',
        ]);
        
        $this->add_responsive_control('title_margin', [
            'label' => __('Margin Bottom', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-social-title-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};'],
        ]);
        
        $this->add_control('title_line_color', [
            'label' => __('Line Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#D1D5DB',
            'selectors' => ['{{WRAPPER}} .mecom-org-social-title-wrapper::after' => 'background-color: {{VALUE}};'],
            'condition' => ['show_title_line' => 'yes'],
        ]);
        
        $this->add_responsive_control('title_line_height', [
            'label' => __('Line Height', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 1, 'max' => 5]],
            'default' => ['size' => 1, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-social-title-wrapper::after' => 'height: {{SIZE}}{{UNIT}};'],
            'condition' => ['show_title_line' => 'yes'],
        ]);
        
        $this->add_responsive_control('title_gap', [
            'label' => __('Gap Between Title & Line', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 5, 'max' => 40]],
            'default' => ['size' => 15, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-social-title-wrapper' => 'gap: {{SIZE}}{{UNIT}};'],
            'condition' => ['show_title_line' => 'yes'],
        ]);
        
        $this->end_controls_section();

        // Icons Style
        $this->start_controls_section('section_style_icons', [
            'label' => __('Icons', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_responsive_control('button_size', [
            'label' => __('Button Size', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 24, 'max' => 100]],
            'default' => ['size' => 50, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-social-link' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'],
        ]);
        
        $this->add_responsive_control('icon_size', [
            'label' => __('Icon Size', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 10, 'max' => 60]],
            'default' => ['size' => 22, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mecom-social-link i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .mecom-social-link svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_responsive_control('icon_spacing', [
            'label' => __('Spacing Between Icons', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 15, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-social-links' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('heading_shape', [
            'label' => __('Shape', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        
        $this->add_control('icon_shape', [
            'label' => __('Icon Shape', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'circle',
            'options' => [
                'circle' => __('Circle', 'mec-organizer-manager'),
                'rounded' => __('Rounded Square', 'mec-organizer-manager'),
                'square' => __('Square', 'mec-organizer-manager'),
                'custom' => __('Custom', 'mec-organizer-manager'),
            ],
        ]);
        
        $this->add_responsive_control('icon_border_radius', [
            'label' => __('Border Radius', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => [
                'top' => '8',
                'right' => '8',
                'bottom' => '8',
                'left' => '8',
                'unit' => 'px',
            ],
            'selectors' => ['{{WRAPPER}} .mecom-social-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            'condition' => ['icon_shape' => 'custom'],
        ]);
        
        // Normal State
        $this->add_control('heading_normal', [
            'label' => __('Normal State', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        
        $this->add_control('icon_bg_color', [
            'label' => __('Background Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#3D4F5F',
            'selectors' => ['{{WRAPPER}} .mecom-social-link' => 'background-color: {{VALUE}};'],
        ]);
        
        $this->add_control('icon_color', [
            'label' => __('Icon Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => [
                '{{WRAPPER}} .mecom-social-link' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mecom-social-link i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mecom-social-link svg' => 'fill: {{VALUE}};',
                '{{WRAPPER}} .mecom-social-link svg path' => 'fill: {{VALUE}};',
            ],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'icon_border',
            'selector' => '{{WRAPPER}} .mecom-social-link',
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'icon_shadow',
            'selector' => '{{WRAPPER}} .mecom-social-link',
        ]);
        
        // Hover State
        $this->add_control('heading_hover', [
            'label' => __('Hover State', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        
        $this->add_control('icon_bg_hover', [
            'label' => __('Background Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#2d3d4a',
            'selectors' => ['{{WRAPPER}} .mecom-social-link:hover' => 'background-color: {{VALUE}};'],
        ]);
        
        $this->add_control('icon_color_hover', [
            'label' => __('Icon Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .mecom-social-link:hover' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mecom-social-link:hover i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mecom-social-link:hover svg' => 'fill: {{VALUE}};',
                '{{WRAPPER}} .mecom-social-link:hover svg path' => 'fill: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('icon_border_hover', [
            'label' => __('Border Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecom-social-link:hover' => 'border-color: {{VALUE}};'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'icon_shadow_hover',
            'label' => __('Box Shadow', 'mec-organizer-manager'),
            'selector' => '{{WRAPPER}} .mecom-social-link:hover',
        ]);
        
        $this->add_control('hover_animation', [
            'label' => __('Hover Animation', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'scale',
            'options' => [
                'none' => __('None', 'mec-organizer-manager'),
                'scale' => __('Scale Up', 'mec-organizer-manager'),
                'rotate' => __('Rotate', 'mec-organizer-manager'),
                'bounce' => __('Bounce', 'mec-organizer-manager'),
            ],
        ]);
        
        $this->end_controls_section();

        // Container Style
        $this->start_controls_section('section_style_container', [
            'label' => __('Container', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('container_bg', [
            'label' => __('Background Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'selectors' => ['{{WRAPPER}} .mecom-org-social' => 'background-color: {{VALUE}};'],
        ]);
        
        $this->add_responsive_control('container_padding', [
            'label' => __('Padding', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecom-org-social' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'container_border',
            'selector' => '{{WRAPPER}} .mecom-org-social',
        ]);
        
        $this->add_responsive_control('container_border_radius', [
            'label' => __('Border Radius', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-social' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);
        
        $this->end_controls_section();
    }

    private function get_organizers_list() {
        $options = ['' => __('Current Organizer', 'mec-organizer-manager')];
        $organizers = get_terms(['taxonomy' => 'mec_organizer', 'hide_empty' => false, 'number' => 50]);
        if (!is_wp_error($organizers)) {
            foreach ($organizers as $o) {
                $options[$o->term_id] = $o->name;
            }
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Shape class
        $shape_class = 'mecom-shape-' . $settings['icon_shape'];
        
        // Hover animation class
        $hover_class = '';
        if ($settings['hover_animation'] !== 'none') {
            $hover_class = 'mecom-hover-' . $settings['hover_animation'];
        }
        
        // Title line class
        $title_line_class = $settings['show_title_line'] === 'yes' ? 'mecom-has-line' : '';
        
        // Get organizer data
        $organizer = $this->get_current_organizer($settings);
        
        // Build social links array
        $social_links = [];
        
        if ($organizer) {
            if (!empty($organizer['instagram'])) {
                $url = $organizer['instagram'];
                if (strpos($url, 'http') !== 0) {
                    $url = 'https://instagram.com/' . ltrim($url, '@/');
                }
                $social_links[] = [
                    'icon' => $settings['instagram_icon'],
                    'url' => $url,
                    'label' => 'Instagram',
                ];
            }
            if (!empty($organizer['twitter'])) {
                $url = $organizer['twitter'];
                if (strpos($url, 'http') !== 0) {
                    $url = 'https://x.com/' . ltrim($url, '@/');
                }
                $social_links[] = [
                    'icon' => $settings['twitter_icon'],
                    'url' => $url,
                    'label' => 'X',
                ];
            }
            if (!empty($organizer['facebook'])) {
                $url = $organizer['facebook'];
                if (strpos($url, 'http') !== 0) {
                    $url = 'https://facebook.com/' . ltrim($url, '/');
                }
                $social_links[] = [
                    'icon' => $settings['facebook_icon'],
                    'url' => $url,
                    'label' => 'Facebook',
                ];
            }
            if (!empty($organizer['tiktok'])) {
                $url = $organizer['tiktok'];
                if (strpos($url, 'http') !== 0) {
                    $url = 'https://tiktok.com/@' . ltrim($url, '@/');
                }
                $social_links[] = [
                    'icon' => $settings['tiktok_icon'],
                    'url' => $url,
                    'label' => 'TikTok',
                ];
            }
        }
        
        // Preview mode - show placeholders
        if (empty($social_links) && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $social_links = [
                ['icon' => $settings['instagram_icon'], 'url' => '#', 'label' => 'Instagram'],
                ['icon' => $settings['twitter_icon'], 'url' => '#', 'label' => 'X'],
                ['icon' => $settings['facebook_icon'], 'url' => '#', 'label' => 'Facebook'],
                ['icon' => $settings['tiktok_icon'], 'url' => '#', 'label' => 'TikTok'],
            ];
        }
        
        if (empty($social_links)) return;
        ?>
        <div class="mecom-org-social">
            <?php if ($settings['show_title'] === 'yes'): ?>
            <div class="mecom-org-social-title-wrapper <?php echo esc_attr($title_line_class); ?>">
                <h3 class="mecom-org-social-title"><?php echo esc_html($settings['title_text']); ?></h3>
            </div>
            <?php endif; ?>
            <div class="mecom-social-links">
                <?php foreach ($social_links as $link): 
                    if (empty($link['icon']['value'])) continue;
                ?>
                <a href="<?php echo esc_url($link['url']); ?>" 
                   class="mecom-social-link <?php echo esc_attr($shape_class . ' ' . $hover_class); ?>" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   title="<?php echo esc_attr($link['label']); ?>" 
                   aria-label="<?php echo esc_attr($link['label']); ?>">
                    <?php \Elementor\Icons_Manager::render_icon($link['icon'], ['aria-hidden' => 'true']); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <style>
        .mecom-org-social-title-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .mecom-org-social-title-wrapper.mecom-has-line {
            width: 100%;
        }
        .mecom-org-social-title-wrapper.mecom-has-line::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #D1D5DB;
        }
        .mecom-org-social-title {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
            white-space: nowrap;
        }
        .mecom-social-links {
            display: flex;
            flex-wrap: wrap;
        }
        .mecom-social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .mecom-social-link i,
        .mecom-social-link svg {
            transition: all 0.3s ease;
        }
        
        /* Shapes */
        .mecom-shape-circle {
            border-radius: 50%;
        }
        .mecom-shape-rounded {
            border-radius: 12px;
        }
        .mecom-shape-square {
            border-radius: 0;
        }
        
        /* Hover Animations */
        .mecom-hover-scale:hover {
            transform: scale(1.15);
        }
        .mecom-hover-rotate:hover {
            transform: rotate(10deg);
        }
        .mecom-hover-bounce:hover {
            animation: mecom-bounce 0.4s ease;
        }
        @keyframes mecom-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        </style>
        <?php
    }

    private function get_current_organizer($settings) {
        $organizer_id = null;
        
        if (!empty($settings['preview_organizer_id'])) {
            $organizer_id = intval($settings['preview_organizer_id']);
        } elseif (get_query_var('mecom_organizer_id')) {
            $organizer_id = get_query_var('mecom_organizer_id');
        } elseif (is_tax('mec_organizer')) {
            $term = get_queried_object();
            if ($term) {
                $organizer_id = $term->term_id;
            }
        }
        
        return $organizer_id ? mecom_get_organizer_data($organizer_id) : null;
    }

    protected function content_template() {
        ?>
        <#
        var showTitle = settings.show_title === 'yes';
        var titleText = settings.title_text || 'Links';
        var showLine = settings.show_title_line === 'yes';
        var lineClass = showLine ? 'mecom-has-line' : '';
        var shapeClass = 'mecom-shape-' + settings.icon_shape;
        var hoverClass = settings.hover_animation !== 'none' ? 'mecom-hover-' + settings.hover_animation : '';
        
        var icons = [
            { icon: settings.instagram_icon, label: 'Instagram' },
            { icon: settings.twitter_icon, label: 'X' },
            { icon: settings.facebook_icon, label: 'Facebook' },
            { icon: settings.tiktok_icon, label: 'TikTok' }
        ];
        #>
        <div class="mecom-org-social">
            <# if (showTitle) { #>
            <div class="mecom-org-social-title-wrapper {{ lineClass }}">
                <h3 class="mecom-org-social-title">{{{ titleText }}}</h3>
            </div>
            <# } #>
            <div class="mecom-social-links">
                <# _.each(icons, function(item) {
                    if (item.icon && item.icon.value) {
                        var iconHTML = elementor.helpers.renderIcon(view, item.icon, { 'aria-hidden': true }, 'i', 'object');
                #>
                    <a class="mecom-social-link {{ shapeClass }} {{ hoverClass }}" href="#" title="{{ item.label }}">
                        <# if (iconHTML && iconHTML.rendered) { #>
                            {{{ iconHTML.value }}}
                        <# } #>
                    </a>
                <# 
                    }
                }); 
                #>
            </div>
        </div>
        <style>
        .mecom-org-social-title-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .mecom-org-social-title-wrapper.mecom-has-line::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #D1D5DB;
        }
        .mecom-org-social-title {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
        }
        .mecom-social-links {
            display: flex;
            gap: 15px;
        }
        .mecom-social-link {
            width: 50px;
            height: 50px;
            background: #3D4F5F;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .mecom-social-link i {
            font-size: 22px;
            color: inherit;
        }
        .mecom-social-link svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
        }
        .mecom-shape-circle { border-radius: 50%; }
        .mecom-shape-rounded { border-radius: 12px; }
        .mecom-shape-square { border-radius: 0; }
        </style>
        <?php
    }
}
