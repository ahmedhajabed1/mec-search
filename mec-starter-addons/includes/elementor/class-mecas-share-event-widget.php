<?php
/**
 * Share Event Button Widget - Full Elementor Button Style
 */

if (!defined('ABSPATH')) exit;

if (class_exists('MECAS_Share_Event_Widget')) {
    return;
}

class MECAS_Share_Event_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_share_event';
    }
    
    public function get_title() {
        return __('Share Event Button', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-share';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['share', 'event', 'button', 'social'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Button', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'button_text',
            [
                'label' => __('Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Share', 'mec-starter-addons'),
            ]
        );
        
        $this->add_responsive_control(
            'align',
            [
                'label' => __('Alignment', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => ['title' => __('Left', 'mec-starter-addons'), 'icon' => 'eicon-text-align-left'],
                    'center' => ['title' => __('Center', 'mec-starter-addons'), 'icon' => 'eicon-text-align-center'],
                    'right' => ['title' => __('Right', 'mec-starter-addons'), 'icon' => 'eicon-text-align-right'],
                    'justify' => ['title' => __('Justified', 'mec-starter-addons'), 'icon' => 'eicon-text-align-justify'],
                ],
                'prefix_class' => 'elementor%s-align-',
                'default' => '',
            ]
        );
        
        $this->add_control(
            'selected_icon',
            [
                'label' => __('Icon', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-share-alt',
                    'library' => 'fa-solid',
                ],
                'skin' => 'inline',
                'label_block' => false,
            ]
        );
        
        $this->add_control(
            'icon_align',
            [
                'label' => __('Icon Position', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before', 'mec-starter-addons'),
                    'right' => __('After', 'mec-starter-addons'),
                ],
                'condition' => ['selected_icon[value]!' => ''],
            ]
        );
        
        $this->add_control(
            'icon_indent',
            [
                'label' => __('Icon Spacing', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => ['px' => ['max' => 50]],
                'default' => ['size' => 8],
                'selectors' => [
                    '{{WRAPPER}} .mecas-share-btn .mecas-btn-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mecas-share-btn .mecas-btn-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['selected_icon[value]!' => ''],
            ]
        );
        
        $this->add_control(
            'share_platforms',
            [
                'label' => __('Share Platforms', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => ['facebook', 'twitter', 'email', 'copy'],
                'options' => [
                    'facebook' => __('Facebook', 'mec-starter-addons'),
                    'twitter' => __('Twitter/X', 'mec-starter-addons'),
                    'linkedin' => __('LinkedIn', 'mec-starter-addons'),
                    'whatsapp' => __('WhatsApp', 'mec-starter-addons'),
                    'email' => __('Email', 'mec-starter-addons'),
                    'copy' => __('Copy Link', 'mec-starter-addons'),
                ],
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'preview_event_id',
            [
                'label' => __('Preview Event', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_events_list(),
                'description' => __('Select an event for preview.', 'mec-starter-addons'),
                'separator' => 'before',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Button
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Button', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .mecas-share-btn',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .mecas-share-btn',
            ]
        );
        
        $this->start_controls_tabs('tabs_button_style');
        
        // Normal Tab
        $this->start_controls_tab('tab_button_normal', ['label' => __('Normal', 'mec-starter-addons')]);
        
        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .mecas-share-btn' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .mecas-share-btn',
                'fields_options' => [
                    'background' => ['default' => 'classic'],
                    'color' => ['default' => '#1F2937'],
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        // Hover Tab
        $this->start_controls_tab('tab_button_hover', ['label' => __('Hover', 'mec-starter-addons')]);
        
        $this->add_control(
            'hover_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-share-btn:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background_hover',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .mecas-share-btn:hover',
            ]
        );
        
        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __('Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-share-btn:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => ['border_border!' => ''],
            ]
        );
        
        $this->add_control(
            'hover_animation',
            [
                'label' => __('Hover Animation', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .mecas-share-btn',
                'separator' => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '30',
                    'right' => '30',
                    'bottom' => '30',
                    'left' => '30',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecas-share-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .mecas-share-btn',
            ]
        );
        
        $this->add_responsive_control(
            'text_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '12',
                    'right' => '24',
                    'bottom' => '12',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecas-share-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Dropdown
        $this->start_controls_section(
            'section_style_dropdown',
            [
                'label' => __('Dropdown', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'dropdown_bg',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .mecas-share-dropdown' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'dropdown_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .mecas-share-option' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'dropdown_hover_bg',
            [
                'label' => __('Hover Background', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F3F4F6',
                'selectors' => [
                    '{{WRAPPER}} .mecas-share-option:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dropdown_border_radius',
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
                    '{{WRAPPER}} .mecas-share-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_box_shadow',
                'selector' => '{{WRAPPER}} .mecas-share-dropdown',
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
        
        // Get event ID
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
            $event_url = home_url('/sample-event/');
            $event_title = 'Sample Event';
        } elseif (!$event_id) {
            return;
        } else {
            $event_url = get_permalink($event_id);
            $event_title = get_the_title($event_id);
        }
        
        $event_title_encoded = rawurlencode($event_title);
        $event_url_encoded = rawurlencode($event_url);
        $platforms = $settings['share_platforms'];
        
        $button_class = 'mecas-share-btn';
        if (!empty($settings['hover_animation'])) {
            $button_class .= ' elementor-animation-' . $settings['hover_animation'];
        }
        
        ?>
        <div class="mecas-share-wrapper">
            <button type="button" class="<?php echo esc_attr($button_class); ?>">
                <?php if (!empty($settings['selected_icon']['value']) && $settings['icon_align'] === 'left'): ?>
                <span class="mecas-btn-icon mecas-btn-icon-left">
                    <?php \Elementor\Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                </span>
                <?php endif; ?>
                
                <span class="mecas-btn-text"><?php echo esc_html($settings['button_text']); ?></span>
                
                <?php if (!empty($settings['selected_icon']['value']) && $settings['icon_align'] === 'right'): ?>
                <span class="mecas-btn-icon mecas-btn-icon-right">
                    <?php \Elementor\Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                </span>
                <?php endif; ?>
            </button>
            
            <div class="mecas-share-dropdown" style="display:none;">
                <?php if (in_array('facebook', $platforms)): ?>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $event_url_encoded; ?>" target="_blank" rel="noopener" class="mecas-share-option">
                    <svg viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    <?php _e('Facebook', 'mec-starter-addons'); ?>
                </a>
                <?php endif; ?>
                
                <?php if (in_array('twitter', $platforms)): ?>
                <a href="https://twitter.com/intent/tweet?text=<?php echo $event_title_encoded; ?>&url=<?php echo $event_url_encoded; ?>" target="_blank" rel="noopener" class="mecas-share-option">
                    <svg viewBox="0 0 24 24" fill="#000000"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    <?php _e('Twitter/X', 'mec-starter-addons'); ?>
                </a>
                <?php endif; ?>
                
                <?php if (in_array('linkedin', $platforms)): ?>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $event_url_encoded; ?>&title=<?php echo $event_title_encoded; ?>" target="_blank" rel="noopener" class="mecas-share-option">
                    <svg viewBox="0 0 24 24" fill="#0A66C2"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    <?php _e('LinkedIn', 'mec-starter-addons'); ?>
                </a>
                <?php endif; ?>
                
                <?php if (in_array('whatsapp', $platforms)): ?>
                <a href="https://wa.me/?text=<?php echo $event_title_encoded; ?>%20<?php echo $event_url_encoded; ?>" target="_blank" rel="noopener" class="mecas-share-option">
                    <svg viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    <?php _e('WhatsApp', 'mec-starter-addons'); ?>
                </a>
                <?php endif; ?>
                
                <?php if (in_array('email', $platforms)): ?>
                <a href="mailto:?subject=<?php echo $event_title_encoded; ?>&body=<?php echo $event_url_encoded; ?>" class="mecas-share-option">
                    <svg viewBox="0 0 24 24" fill="#6B7280"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    <?php _e('Email', 'mec-starter-addons'); ?>
                </a>
                <?php endif; ?>
                
                <?php if (in_array('copy', $platforms)): ?>
                <button type="button" class="mecas-share-option mecas-copy-link" data-url="<?php echo esc_attr($event_url); ?>">
                    <svg viewBox="0 0 24 24" fill="#6B7280"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
                    <span class="mecas-copy-text"><?php _e('Copy Link', 'mec-starter-addons'); ?></span>
                </button>
                <?php endif; ?>
            </div>
        </div>
        
        <style>
        .mecas-share-wrapper {
            position: relative;
            display: inline-block;
        }
        .mecas-share-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .mecas-share-btn .mecas-btn-icon {
            display: inline-flex;
            align-items: center;
        }
        .mecas-share-btn .mecas-btn-icon svg,
        .mecas-share-btn .mecas-btn-icon i {
            width: 1em;
            height: 1em;
        }
        .mecas-share-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 8px;
            min-width: 180px;
            background: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            z-index: 100;
            overflow: hidden;
        }
        .mecas-share-option {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            color: #374151;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .mecas-share-option:hover {
            background: #F3F4F6;
        }
        .mecas-share-option svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        .mecas-share-dropdown.mecas-active {
            display: block !important;
        }
        </style>
        
        <script>
        (function($) {
            $(document).ready(function() {
                // Toggle dropdown
                $('.mecas-share-btn').off('click').on('click', function(e) {
                    e.stopPropagation();
                    var $dropdown = $(this).siblings('.mecas-share-dropdown');
                    $('.mecas-share-dropdown').not($dropdown).removeClass('mecas-active').hide();
                    $dropdown.toggleClass('mecas-active').toggle();
                });
                
                // Close dropdown on outside click
                $(document).on('click', function() {
                    $('.mecas-share-dropdown').removeClass('mecas-active').hide();
                });
                
                // Copy link
                $('.mecas-copy-link').off('click').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var url = $(this).data('url');
                    var $text = $(this).find('.mecas-copy-text');
                    
                    navigator.clipboard.writeText(url).then(function() {
                        $text.text('<?php _e('Copied!', 'mec-starter-addons'); ?>');
                        setTimeout(function() {
                            $text.text('<?php _e('Copy Link', 'mec-starter-addons'); ?>');
                        }, 2000);
                    });
                });
            });
        })(jQuery);
        </script>
        <?php
    }
}
