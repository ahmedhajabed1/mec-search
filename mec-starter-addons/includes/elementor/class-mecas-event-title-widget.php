<?php
/**
 * Event Title Widget - Shows event title and "Hosted by" organizer
 */

if (!defined('ABSPATH')) exit;

class MECAS_Event_Title_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_event_title';
    }
    
    public function get_title() {
        return __('Event Title', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-post-title';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['event', 'title', 'hosted', 'organizer'];
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
            'title_tag',
            [
                'label' => __('Title HTML Tag', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h1',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'div' => 'DIV',
                ],
            ]
        );
        
        $this->add_control(
            'show_hosted_by',
            [
                'label' => __('Show "Hosted by"', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'hosted_by_text',
            [
                'label' => __('Hosted By Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Hosted by', 'mec-starter-addons'),
                'condition' => ['show_hosted_by' => 'yes'],
            ]
        );
        
        $this->add_control(
            'link_organizer',
            [
                'label' => __('Link to Organizer Profile', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_hosted_by' => 'yes'],
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
                    '{{WRAPPER}} .mecas-event-title-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mecas-event-title-text',
            ]
        );
        
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __('Margin', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-event-title-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Hosted By
        $this->start_controls_section(
            'section_style_hosted',
            [
                'label' => __('Hosted By', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_hosted_by' => 'yes'],
            ]
        );
        
        $this->add_control(
            'hosted_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-event-hosted-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'hosted_typography',
                'label' => __('Typography', 'mec-starter-addons'),
                'selector' => '{{WRAPPER}} .mecas-event-hosted-text',
            ]
        );
        
        $this->add_responsive_control(
            'hosted_text_margin',
            [
                'label' => __('Margin', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-event-hosted-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'organizer_name_heading',
            [
                'label' => __('Organizer Name', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'hosted_link_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-event-organizer-name' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'hosted_link_color_hover',
            [
                'label' => __('Hover Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-event-organizer-name:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'organizer_name_typography',
                'label' => __('Typography', 'mec-starter-addons'),
                'selector' => '{{WRAPPER}} .mecas-event-organizer-name',
            ]
        );
        
        $this->add_control(
            'organizer_underline',
            [
                'label' => __('Underline Organizer Name', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .mecas-event-organizer-name' => 'text-decoration: underline;',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'organizer_name_margin',
            [
                'label' => __('Margin', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-event-organizer-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        
        // If no event and in editor, show placeholder with example
        if (!$event_id && $is_editor) {
            $this->render_editor_placeholder($settings);
            return;
        }
        
        // If no event on frontend, don't show anything
        if (!$event_id) {
            return;
        }
        
        $title = get_the_title($event_id);
        $tag = $settings['title_tag'];
        
        // Get organizer
        $organizer_id = get_post_meta($event_id, 'mec_organizer_id', true);
        $organizer = $organizer_id ? get_term($organizer_id, 'mec_organizer') : null;
        $organizer_name = $organizer ? $organizer->name : '';
        
        // Get organizer profile URL
        $organizer_url = '';
        if ($organizer && $settings['link_organizer'] === 'yes') {
            $teacher_slug = get_option('mecom_teacher_slug', 'teacher');
            $organizer_url = home_url('/' . $teacher_slug . '/' . $organizer->slug . '/');
        }
        
        ?>
        <div class="mecas-event-title-wrapper">
            <<?php echo esc_attr($tag); ?> class="mecas-event-title-text"><?php echo esc_html($title); ?></<?php echo esc_attr($tag); ?>>
            
            <?php if ($settings['show_hosted_by'] === 'yes' && $organizer_name): ?>
            <p class="mecas-event-hosted-section">
                <span class="mecas-event-hosted-text"><?php echo esc_html($settings['hosted_by_text']); ?></span>
                <?php if ($organizer_url): ?>
                <a href="<?php echo esc_url($organizer_url); ?>" class="mecas-event-organizer-name"><?php echo esc_html($organizer_name); ?></a>
                <?php else: ?>
                <span class="mecas-event-organizer-name"><?php echo esc_html($organizer_name); ?></span>
                <?php endif; ?>
            </p>
            <?php elseif ($settings['show_hosted_by'] === 'yes' && $is_editor): ?>
            <p class="mecas-event-hosted-section">
                <span class="mecas-event-hosted-text"><?php echo esc_html($settings['hosted_by_text']); ?></span>
                <span class="mecas-event-organizer-name"><?php esc_html_e('Organizer Name', 'mec-starter-addons'); ?></span>
            </p>
            <?php endif; ?>
        </div>
        
        <style>
        .mecas-event-title-wrapper {}
        .mecas-event-title-text { margin: 0 0 10px 0; }
        .mecas-event-hosted-section { margin: 0; }
        .mecas-event-hosted-text { margin-right: 5px; }
        .mecas-event-organizer-name { text-decoration: none; }
        </style>
        <?php
    }
    
    private function render_editor_placeholder($settings) {
        $tag = $settings['title_tag'];
        ?>
        <div class="mecas-event-title-wrapper">
            <<?php echo esc_attr($tag); ?> class="mecas-event-title-text"><?php esc_html_e('Event Title Preview', 'mec-starter-addons'); ?></<?php echo esc_attr($tag); ?>>
            
            <?php if ($settings['show_hosted_by'] === 'yes'): ?>
            <p class="mecas-event-hosted-section">
                <span class="mecas-event-hosted-text"><?php echo esc_html($settings['hosted_by_text']); ?></span>
                <span class="mecas-event-organizer-name"><?php esc_html_e('Organizer Name', 'mec-starter-addons'); ?></span>
            </p>
            <?php endif; ?>
        </div>
        <p style="padding: 10px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; font-size: 12px; margin-top: 10px;">
            <strong><?php esc_html_e('Tip:', 'mec-starter-addons'); ?></strong> 
            <?php esc_html_e('Select a "Preview Event" in the Content tab to see actual event data.', 'mec-starter-addons'); ?>
        </p>
        <style>
        .mecas-event-title-wrapper {}
        .mecas-event-title-text { margin: 0 0 10px 0; }
        .mecas-event-hosted-section { margin: 0; }
        .mecas-event-hosted-text { margin-right: 5px; }
        .mecas-event-organizer-name { text-decoration: none; }
        </style>
        <?php
    }
}
