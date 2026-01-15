<?php
/**
 * Elementor Widget: Organizer Profile Image
 * Displays the organizer's profile image with full styling options
 */

if (!defined('ABSPATH')) exit;

class MECOM_Organizer_Image_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecom-organizer-image';
    }

    public function get_title() {
        return __('Organizer Profile Image', 'mec-organizer-manager');
    }

    public function get_icon() {
        return 'eicon-image';
    }

    public function get_categories() {
        return ['mec-organizer-manager'];
    }

    public function get_keywords() {
        return ['organizer', 'image', 'photo', 'profile', 'avatar', 'picture', 'teacher'];
    }

    protected function register_controls() {
        
        // === CONTENT TAB ===
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Image', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image_source',
            [
                'label' => __('Image Source', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'organizer',
                'options' => [
                    'organizer' => __('Current Organizer', 'mec-organizer-manager'),
                    'custom' => __('Custom Image', 'mec-organizer-manager'),
                ],
            ]
        );

        $this->add_control(
            'custom_image',
            [
                'label' => __('Custom Image', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'image_source' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'fallback_image',
            [
                'label' => __('Fallback Image', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'description' => __('Shown if organizer has no profile image', 'mec-organizer-manager'),
                'condition' => [
                    'image_source' => 'organizer',
                ],
            ]
        );

        $this->add_control(
            'show_placeholder',
            [
                'label' => __('Show Placeholder Icon', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-organizer-manager'),
                'label_off' => __('No', 'mec-organizer-manager'),
                'default' => 'yes',
                'description' => __('Show a person icon if no image available', 'mec-organizer-manager'),
                'condition' => [
                    'image_source' => 'organizer',
                ],
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label' => __('Link To', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None', 'mec-organizer-manager'),
                    'profile' => __('Profile Page', 'mec-organizer-manager'),
                    'media' => __('Media File (Lightbox)', 'mec-organizer-manager'),
                    'custom' => __('Custom URL', 'mec-organizer-manager'),
                ],
            ]
        );

        $this->add_control(
            'custom_link',
            [
                'label' => __('Custom URL', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'condition' => [
                    'link_to' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => __('Alignment', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'mec-organizer-manager'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'mec-organizer-manager'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'mec-organizer-manager'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .mecom-organizer-image-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // === STYLE TAB ===
        
        // Image Style
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __('Image', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __('Width', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 800,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 300,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecom-organizer-image-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Height', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 800,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 300,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecom-organizer-image-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_fit',
            [
                'label' => __('Object Fit', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => __('Cover', 'mec-organizer-manager'),
                    'contain' => __('Contain', 'mec-organizer-manager'),
                    'fill' => __('Fill', 'mec-organizer-manager'),
                    'none' => __('None', 'mec-organizer-manager'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecom-organizer-image-container img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_position',
            [
                'label' => __('Object Position', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'center center',
                'options' => [
                    'top left' => __('Top Left', 'mec-organizer-manager'),
                    'top center' => __('Top Center', 'mec-organizer-manager'),
                    'top right' => __('Top Right', 'mec-organizer-manager'),
                    'center left' => __('Center Left', 'mec-organizer-manager'),
                    'center center' => __('Center Center', 'mec-organizer-manager'),
                    'center right' => __('Center Right', 'mec-organizer-manager'),
                    'bottom left' => __('Bottom Left', 'mec-organizer-manager'),
                    'bottom center' => __('Bottom Center', 'mec-organizer-manager'),
                    'bottom right' => __('Bottom Right', 'mec-organizer-manager'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecom-organizer-image-container img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'image_fit' => ['cover', 'contain'],
                ],
            ]
        );

        $this->end_controls_section();

        // Border & Shape
        $this->start_controls_section(
            'section_style_border',
            [
                'label' => __('Border & Shape', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_shape',
            [
                'label' => __('Shape', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'square' => __('Square', 'mec-organizer-manager'),
                    'rounded' => __('Rounded', 'mec-organizer-manager'),
                    'circle' => __('Circle', 'mec-organizer-manager'),
                    'custom' => __('Custom', 'mec-organizer-manager'),
                ],
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '12',
                    'right' => '12',
                    'bottom' => '12',
                    'left' => '12',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecom-organizer-image-container, {{WRAPPER}} .mecom-organizer-image-container img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'image_shape' => 'custom',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .mecom-organizer-image-container',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'selector' => '{{WRAPPER}} .mecom-organizer-image-container',
            ]
        );

        $this->end_controls_section();

        // Placeholder Style
        $this->start_controls_section(
            'section_style_placeholder',
            [
                'label' => __('Placeholder', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_placeholder' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'placeholder_bg_color',
            [
                'label' => __('Background Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f0f0f0',
                'selectors' => [
                    '{{WRAPPER}} .mecom-organizer-image-placeholder' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'placeholder_icon_color',
            [
                'label' => __('Icon Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .mecom-organizer-image-placeholder svg' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'placeholder_icon_size',
            [
                'label' => __('Icon Size', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 80,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecom-organizer-image-placeholder svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Hover Effects
        $this->start_controls_section(
            'section_style_hover',
            [
                'label' => __('Hover Effects', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => __('Hover Animation', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None', 'mec-organizer-manager'),
                    'zoom-in' => __('Zoom In', 'mec-organizer-manager'),
                    'zoom-out' => __('Zoom Out', 'mec-organizer-manager'),
                    'brightness' => __('Brightness', 'mec-organizer-manager'),
                    'grayscale' => __('Grayscale', 'mec-organizer-manager'),
                    'blur' => __('Blur', 'mec-organizer-manager'),
                ],
            ]
        );

        $this->add_control(
            'hover_opacity',
            [
                'label' => __('Hover Opacity', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecom-organizer-image-container:hover' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_border_color',
            [
                'label' => __('Hover Border Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecom-organizer-image-container:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_hover',
                'label' => __('Hover Box Shadow', 'mec-organizer-manager'),
                'selector' => '{{WRAPPER}} .mecom-organizer-image-container:hover',
            ]
        );

        $this->add_control(
            'transition_duration',
            [
                'label' => __('Transition Duration (ms)', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 300,
                'min' => 0,
                'max' => 2000,
                'selectors' => [
                    '{{WRAPPER}} .mecom-organizer-image-container, {{WRAPPER}} .mecom-organizer-image-container img' => 'transition: all {{VALUE}}ms ease;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get organizer data
        $organizer = mecom_get_current_organizer();
        
        // Determine image URL
        $image_url = '';
        $image_alt = '';
        
        if ($settings['image_source'] === 'custom' && !empty($settings['custom_image']['url'])) {
            $image_url = $settings['custom_image']['url'];
            $image_alt = __('Profile Image', 'mec-organizer-manager');
        } elseif ($organizer && !empty($organizer['thumbnail'])) {
            $image_url = $organizer['thumbnail'];
            $image_alt = $organizer['name'];
        } elseif (!empty($settings['fallback_image']['url'])) {
            $image_url = $settings['fallback_image']['url'];
            $image_alt = __('Profile Image', 'mec-organizer-manager');
        }
        
        // Get link URL
        $link_url = '';
        $link_target = '_self';
        
        if ($settings['link_to'] === 'profile' && $organizer) {
            $link_url = $organizer['url'];
        } elseif ($settings['link_to'] === 'media' && $image_url) {
            $link_url = $image_url;
            $link_target = '_blank';
        } elseif ($settings['link_to'] === 'custom' && !empty($settings['custom_link']['url'])) {
            $link_url = $settings['custom_link']['url'];
            $link_target = !empty($settings['custom_link']['is_external']) ? '_blank' : '_self';
        }
        
        // Shape classes
        $shape_class = '';
        if ($settings['image_shape'] === 'square') {
            $shape_class = 'mecom-shape-square';
        } elseif ($settings['image_shape'] === 'rounded') {
            $shape_class = 'mecom-shape-rounded';
        } elseif ($settings['image_shape'] === 'circle') {
            $shape_class = 'mecom-shape-circle';
        }
        
        // Hover animation class
        $hover_class = '';
        if ($settings['hover_animation'] !== 'none') {
            $hover_class = 'mecom-hover-' . $settings['hover_animation'];
        }
        
        // Lightbox attribute
        $lightbox_attr = '';
        if ($settings['link_to'] === 'media') {
            $lightbox_attr = 'data-elementor-open-lightbox="yes"';
        }
        ?>
        
        <div class="mecom-organizer-image-wrapper">
            <?php if ($link_url): ?>
            <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>" <?php echo $lightbox_attr; ?>>
            <?php endif; ?>
            
            <div class="mecom-organizer-image-container <?php echo esc_attr($shape_class . ' ' . $hover_class); ?>">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                <?php elseif ($settings['show_placeholder'] === 'yes'): ?>
                    <div class="mecom-organizer-image-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if ($link_url): ?>
            </a>
            <?php endif; ?>
        </div>
        
        <style>
            .mecom-organizer-image-wrapper {
                line-height: 0;
            }
            .mecom-organizer-image-container {
                display: inline-block;
                overflow: hidden;
                position: relative;
            }
            .mecom-organizer-image-container img {
                width: 100%;
                height: 100%;
                display: block;
            }
            .mecom-organizer-image-placeholder {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            /* Shapes */
            .mecom-shape-square {
                border-radius: 0 !important;
            }
            .mecom-shape-square img {
                border-radius: 0 !important;
            }
            .mecom-shape-rounded {
                border-radius: 12px !important;
            }
            .mecom-shape-rounded img {
                border-radius: 12px !important;
            }
            .mecom-shape-circle {
                border-radius: 50% !important;
            }
            .mecom-shape-circle img {
                border-radius: 50% !important;
            }
            
            /* Hover Effects */
            .mecom-hover-zoom-in:hover img {
                transform: scale(1.1);
            }
            .mecom-hover-zoom-out img {
                transform: scale(1.1);
            }
            .mecom-hover-zoom-out:hover img {
                transform: scale(1);
            }
            .mecom-hover-brightness:hover img {
                filter: brightness(1.2);
            }
            .mecom-hover-grayscale img {
                filter: grayscale(100%);
            }
            .mecom-hover-grayscale:hover img {
                filter: grayscale(0%);
            }
            .mecom-hover-blur:hover img {
                filter: blur(3px);
            }
        </style>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        var shapeClass = '';
        if (settings.image_shape === 'square') {
            shapeClass = 'mecom-shape-square';
        } else if (settings.image_shape === 'rounded') {
            shapeClass = 'mecom-shape-rounded';
        } else if (settings.image_shape === 'circle') {
            shapeClass = 'mecom-shape-circle';
        }
        
        var hoverClass = '';
        if (settings.hover_animation !== 'none') {
            hoverClass = 'mecom-hover-' + settings.hover_animation;
        }
        
        var imageUrl = '';
        if (settings.image_source === 'custom' && settings.custom_image.url) {
            imageUrl = settings.custom_image.url;
        }
        #>
        
        <div class="mecom-organizer-image-wrapper">
            <div class="mecom-organizer-image-container {{ shapeClass }} {{ hoverClass }}">
                <# if (imageUrl) { #>
                    <img src="{{ imageUrl }}" alt="Profile Image">
                <# } else if (settings.show_placeholder === 'yes') { #>
                    <div class="mecom-organizer-image-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                <# } #>
            </div>
        </div>
        
        <style>
            .mecom-organizer-image-wrapper {
                line-height: 0;
            }
            .mecom-organizer-image-container {
                display: inline-block;
                overflow: hidden;
                position: relative;
            }
            .mecom-organizer-image-container img {
                width: 100%;
                height: 100%;
                display: block;
            }
            .mecom-organizer-image-placeholder {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f0f0f0;
            }
            .mecom-organizer-image-placeholder svg {
                stroke: #ccc;
            }
            .mecom-shape-square, .mecom-shape-square img { border-radius: 0 !important; }
            .mecom-shape-rounded, .mecom-shape-rounded img { border-radius: 12px !important; }
            .mecom-shape-circle, .mecom-shape-circle img { border-radius: 50% !important; }
        </style>
        <?php
    }
}
