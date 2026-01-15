<?php
/**
 * Save Event Button Widget - Full Elementor Button Style
 */

if (!defined('ABSPATH')) exit;

class MECAS_Save_Event_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_save_event';
    }
    
    public function get_title() {
        return __('Save Event Button', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-bookmark';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['save', 'bookmark', 'event', 'button', 'favorite'];
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
                'default' => __('Save', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'saved_text',
            [
                'label' => __('Saved Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Saved', 'mec-starter-addons'),
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
                    'value' => 'fas fa-bookmark',
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
                    '{{WRAPPER}} .mecas-save-btn .mecas-btn-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mecas-save-btn .mecas-btn-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['selected_icon[value]!' => ''],
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
                'selector' => '{{WRAPPER}} .mecas-save-btn',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .mecas-save-btn',
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
                    '{{WRAPPER}} .mecas-save-btn:not(.mecas-saved)' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .mecas-save-btn:not(.mecas-saved)',
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
                    '{{WRAPPER}} .mecas-save-btn:not(.mecas-saved):hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background_hover',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .mecas-save-btn:not(.mecas-saved):hover',
            ]
        );
        
        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __('Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-save-btn:not(.mecas-saved):hover' => 'border-color: {{VALUE}};',
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
        
        // Saved Tab
        $this->start_controls_tab('tab_button_saved', ['label' => __('Saved', 'mec-starter-addons')]);
        
        $this->add_control(
            'saved_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#065F46',
                'selectors' => [
                    '{{WRAPPER}} .mecas-save-btn.mecas-saved' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'saved_background',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .mecas-save-btn.mecas-saved',
                'fields_options' => [
                    'background' => ['default' => 'classic'],
                    'color' => ['default' => '#D1FAE5'],
                ],
            ]
        );
        
        $this->add_control(
            'saved_border_color',
            [
                'label' => __('Border Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-save-btn.mecas-saved' => 'border-color: {{VALUE}};',
                ],
                'condition' => ['border_border!' => ''],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .mecas-save-btn',
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
                    '{{WRAPPER}} .mecas-save-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .mecas-save-btn',
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
                    '{{WRAPPER}} .mecas-save-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        
        // For editor preview, use preview_event_id if set
        $preview_id = !empty($settings['preview_event_id']) ? intval($settings['preview_event_id']) : 0;
        
        ?>
        <div class="mecas-save-btn-wrapper">
            <button class="mecas-save-btn mecas-save-event-btn" type="button" data-event-id="<?php echo esc_attr($preview_id); ?>" data-save-text="<?php echo esc_attr($settings['button_text']); ?>" data-saved-text="<?php echo esc_attr($settings['saved_text']); ?>">
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
        </div>
        
        <style>
        .mecas-save-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .mecas-save-btn .mecas-btn-icon {
            display: inline-flex;
            align-items: center;
        }
        .mecas-save-btn .mecas-btn-icon svg,
        .mecas-save-btn .mecas-btn-icon i {
            width: 1em;
            height: 1em;
        }
        </style>
        <?php
    }
}
