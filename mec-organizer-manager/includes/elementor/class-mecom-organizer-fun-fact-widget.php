<?php
if (!defined('ABSPATH')) exit;

class MECOM_Organizer_Fun_Fact_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'mecom-organizer-fun-fact'; }
    public function get_title() { return __('Organizer Fun Fact', 'mec-organizer-manager'); }
    public function get_icon() { return 'eicon-info-circle'; }
    public function get_categories() { return ['mec-organizer-manager']; }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'mec-organizer-manager'),
        ]);
        
        $this->add_control('show_title', [
            'label' => __('Show Title', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('title_text', [
            'label' => __('Title', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Fun Fact', 'mec-organizer-manager'),
            'condition' => ['show_title' => 'yes'],
        ]);
        
        $this->add_control('show_title_line', [
            'label' => __('Show Title Line', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_title' => 'yes'],
        ]);
        
        $this->add_control('preview_organizer_id', [
            'label' => __('Preview Organizer', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $this->get_organizers_list(),
        ]);
        
        $this->end_controls_section();

        // Title Style Section
        $this->start_controls_section('section_style_title', [
            'label' => __('Title', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_title' => 'yes'],
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E8927C',
            'selectors' => ['{{WRAPPER}} .mecom-org-fun-fact-title' => 'color: {{VALUE}};'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .mecom-org-fun-fact-title',
        ]);
        
        $this->add_responsive_control('title_margin_bottom', [
            'label' => __('Margin Bottom', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 10, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-fun-fact-title-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};'],
        ]);
        
        $this->add_control('heading_line', [
            'label' => __('Line', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => ['show_title_line' => 'yes'],
        ]);
        
        $this->add_control('title_line_color', [
            'label' => __('Line Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => ['{{WRAPPER}} .mecom-org-fun-fact-title-line' => 'background-color: {{VALUE}};'],
            'condition' => ['show_title_line' => 'yes'],
        ]);
        
        $this->add_responsive_control('title_line_height', [
            'label' => __('Line Height', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 1, 'max' => 10]],
            'default' => ['size' => 1, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-fun-fact-title-line' => 'height: {{SIZE}}{{UNIT}};'],
            'condition' => ['show_title_line' => 'yes'],
        ]);
        
        $this->add_responsive_control('title_line_gap', [
            'label' => __('Gap Before Line', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 5, 'max' => 50]],
            'default' => ['size' => 15, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-fun-fact-title-line' => 'margin-left: {{SIZE}}{{UNIT}};'],
            'condition' => ['show_title_line' => 'yes'],
        ]);
        
        $this->end_controls_section();

        // Content Style Section
        $this->start_controls_section('section_style_content', [
            'label' => __('Content', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('content_color', [
            'label' => __('Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#4B5563',
            'selectors' => ['{{WRAPPER}} .mecom-org-fun-fact-text' => 'color: {{VALUE}};'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'content_typography',
            'selector' => '{{WRAPPER}} .mecom-org-fun-fact-text',
        ]);
        
        $this->end_controls_section();
    }

    private function get_organizers_list() {
        $options = ['' => __('Current Organizer', 'mec-organizer-manager')];
        $organizers = get_terms(['taxonomy' => 'mec_organizer', 'hide_empty' => false]);
        if (!is_wp_error($organizers)) { foreach ($organizers as $o) { $options[$o->term_id] = $o->name; } }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $organizer = $this->get_current_organizer($settings);
        $fun_fact = $organizer ? $organizer['fun_fact'] : '';
        
        if (empty($fun_fact) && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $fun_fact = 'This is a sample fun fact. Add your fun fact in the organizer settings.';
        }
        
        if (empty($fun_fact)) return;
        
        $content = preg_match('/<(p|div)[^>]*>/i', $fun_fact) ? $fun_fact : wpautop($fun_fact);
        ?>
        <div class="mecom-org-fun-fact">
            <?php if ($settings['show_title'] === 'yes'): ?>
            <div class="mecom-org-fun-fact-title-wrap">
                <h3 class="mecom-org-fun-fact-title"><?php echo esc_html($settings['title_text']); ?></h3>
                <?php if ($settings['show_title_line'] === 'yes'): ?>
                <span class="mecom-org-fun-fact-title-line"></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="mecom-org-fun-fact-text"><?php echo wp_kses_post($content); ?></div>
        </div>
        <style>
        .mecom-org-fun-fact-title-wrap { display: flex; align-items: center; }
        .mecom-org-fun-fact-title { margin: 0; white-space: nowrap; }
        .mecom-org-fun-fact-title-line { flex: 1; }
        .mecom-org-fun-fact-text p:last-child { margin-bottom: 0; }
        </style>
        <?php
    }

    private function get_current_organizer($settings) {
        $organizer_id = !empty($settings['preview_organizer_id']) ? intval($settings['preview_organizer_id']) : (get_query_var('mecom_organizer_id') ?: (is_tax('mec_organizer') && ($t = get_queried_object()) ? $t->term_id : null));
        return $organizer_id ? mecom_get_organizer_data($organizer_id) : null;
    }
}
