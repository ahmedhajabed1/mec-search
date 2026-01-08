<?php
/**
 * MEC Starter Addons - Organizer Fun Fact Widget
 * Displays organizer fun fact in a styled box
 */

if (!defined('ABSPATH')) exit;

class MECAS_Organizer_Fun_Fact_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_organizer_fun_fact';
    }

    public function get_title() {
        return __('Organizer Fun Fact', 'mec-starter-addons');
    }

    public function get_icon() {
        return 'eicon-info-box';
    }

    public function get_categories() {
        return ['mec-starter-addons'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('title_text', [
            'label' => __('Title', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Fun Fact', 'mec-starter-addons'),
        ]);

        $this->add_control('show_title_line', [
            'label' => __('Show Title Line', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('preview_organizer_id', [
            'label' => __('Preview Organizer', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $this->get_organizers_list(),
        ]);

        $this->end_controls_section();

        // Box Style
        $this->start_controls_section('section_style_box', [
            'label' => __('Box', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('box_bg_color', [
            'label' => __('Background Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'box_border',
            'selector' => '{{WRAPPER}} .mecas-org-fun-fact',
        ]);

        $this->add_responsive_control('box_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'unit' => 'px', 'isLinked' => true],
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('box_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => ['top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit' => 'px', 'isLinked' => true],
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow',
            'selector' => '{{WRAPPER}} .mecas-org-fun-fact',
        ]);

        $this->end_controls_section();

        // Title Style
        $this->start_controls_section('section_style_title', [
            'label' => __('Title', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('title_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E8927C',
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact-title' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .mecas-org-fun-fact-title',
            'fields_options' => [
                'typography' => ['default' => 'yes'],
                'font_size' => ['default' => ['size' => 18, 'unit' => 'px']],
                'font_weight' => ['default' => '500'],
            ],
        ]);

        $this->add_responsive_control('title_spacing', [
            'label' => __('Space After Title', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range' => [
                'px' => ['min' => 0, 'max' => 100],
                'em' => ['min' => 0, 'max' => 6],
            ],
            'default' => ['size' => 10, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact-title-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_control('title_line_heading', [
            'label' => __('Title Line', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => ['show_title_line' => 'yes'],
        ]);

        $this->add_control('title_line_color', [
            'label' => __('Line Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'condition' => ['show_title_line' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact-title-line' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('title_line_height', [
            'label' => __('Line Height', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 1, 'max' => 5]],
            'default' => ['size' => 1, 'unit' => 'px'],
            'condition' => ['show_title_line' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact-title-line' => 'height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('title_line_gap', [
            'label' => __('Gap from Title', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 5, 'max' => 30]],
            'default' => ['size' => 15, 'unit' => 'px'],
            'condition' => ['show_title_line' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact-title-line' => 'margin-left: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Text Style
        $this->start_controls_section('section_style_text', [
            'label' => __('Text', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('text_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#4B5563',
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact-text, {{WRAPPER}} .mecas-org-fun-fact-text p, {{WRAPPER}} .mecas-org-fun-fact-text li, {{WRAPPER}} .mecas-org-fun-fact-text span, {{WRAPPER}} .mecas-org-fun-fact-text div' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('text_font_family', [
            'label' => __('Font Family', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::FONT,
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact-text, {{WRAPPER}} .mecas-org-fun-fact-text p, {{WRAPPER}} .mecas-org-fun-fact-text li, {{WRAPPER}} .mecas-org-fun-fact-text span, {{WRAPPER}} .mecas-org-fun-fact-text div' => 'font-family: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('text_font_size', [
            'label' => __('Font Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem'],
            'range' => [
                'px' => ['min' => 8, 'max' => 100],
                'em' => ['min' => 0.5, 'max' => 6],
                'rem' => ['min' => 0.5, 'max' => 6],
            ],
            'default' => ['size' => 14, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact-text, {{WRAPPER}} .mecas-org-fun-fact-text p, {{WRAPPER}} .mecas-org-fun-fact-text li, {{WRAPPER}} .mecas-org-fun-fact-text span, {{WRAPPER}} .mecas-org-fun-fact-text div' => 'font-size: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_control('text_font_weight', [
            'label' => __('Font Weight', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                '' => __('Default', 'mec-starter-addons'),
                '100' => '100',
                '200' => '200',
                '300' => '300',
                '400' => '400',
                '500' => '500',
                '600' => '600',
                '700' => '700',
                '800' => '800',
                '900' => '900',
            ],
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact-text, {{WRAPPER}} .mecas-org-fun-fact-text p, {{WRAPPER}} .mecas-org-fun-fact-text li, {{WRAPPER}} .mecas-org-fun-fact-text span, {{WRAPPER}} .mecas-org-fun-fact-text div' => 'font-weight: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('text_line_height', [
            'label' => __('Line Height', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range' => [
                'px' => ['min' => 10, 'max' => 100],
                'em' => ['min' => 0.5, 'max' => 5],
            ],
            'default' => ['size' => 1.6, 'unit' => 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact-text, {{WRAPPER}} .mecas-org-fun-fact-text p, {{WRAPPER}} .mecas-org-fun-fact-text li, {{WRAPPER}} .mecas-org-fun-fact-text span, {{WRAPPER}} .mecas-org-fun-fact-text div' => 'line-height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('paragraph_spacing', [
            'label' => __('Paragraph Spacing', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range' => [
                'px' => ['min' => 0, 'max' => 50],
                'em' => ['min' => 0, 'max' => 3],
            ],
            'default' => ['size' => 10, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-fun-fact-text p' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();
    }

    private function get_organizers_list() {
        $options = ['' => __('Current Organizer (Auto)', 'mec-starter-addons')];
        $terms = get_terms(['taxonomy' => 'mec_organizer', 'hide_empty' => false]);
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->term_id] = $term->name;
            }
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $organizer = $this->get_current_organizer($settings);
        
        $fun_fact = $organizer ? $organizer['fun_fact'] : '';
        
        if (empty($fun_fact) && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $fun_fact = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry. Lorem Ipsum has been the industry.';
        }

        if (empty($fun_fact) && !$organizer) {
            return;
        }
        
        // Only apply wpautop if content doesn't already have block-level HTML
        $has_blocks = preg_match('/<(p|div|ul|ol|h[1-6]|blockquote)[^>]*>/i', $fun_fact);
        $content = $has_blocks ? $fun_fact : wpautop($fun_fact);
        
        $widget_id = 'mecas-funfact-' . $this->get_id();
        ?>
        <div class="mecas-org-fun-fact" id="<?php echo esc_attr($widget_id); ?>">
            <div class="mecas-org-fun-fact-title-wrap">
                <h3 class="mecas-org-fun-fact-title"><?php echo esc_html($settings['title_text']); ?></h3>
                <?php if ($settings['show_title_line'] === 'yes'): ?>
                <span class="mecas-org-fun-fact-title-line"></span>
                <?php endif; ?>
            </div>
            <div class="mecas-org-fun-fact-text"><?php echo wp_kses_post($content); ?></div>
        </div>
        <style>
        #<?php echo esc_attr($widget_id); ?> .mecas-org-fun-fact-title-wrap {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        #<?php echo esc_attr($widget_id); ?> .mecas-org-fun-fact-title {
            margin: 0;
            white-space: nowrap;
        }
        #<?php echo esc_attr($widget_id); ?> .mecas-org-fun-fact-title-line {
            flex: 1;
            height: 1px;
            background-color: #E5E7EB;
            margin-left: 15px;
        }
        #<?php echo esc_attr($widget_id); ?> .mecas-org-fun-fact-text p:last-child {
            margin-bottom: 0 !important;
        }
        </style>
        <?php
    }

    private function get_current_organizer($settings) {
        $organizer_id = null;
        
        if (!empty($settings['preview_organizer_id'])) {
            $organizer_id = intval($settings['preview_organizer_id']);
        } elseif (is_tax('mec_organizer')) {
            $term = get_queried_object();
            if ($term) $organizer_id = $term->term_id;
        }

        if (!$organizer_id) return null;
        return mecas_get_organizer_data($organizer_id);
    }

    protected function content_template() {
        ?>
        <div class="mecas-org-fun-fact">
            <div class="mecas-org-fun-fact-title-wrap" style="display: flex; align-items: center; margin-bottom: 10px;">
                <h3 class="mecas-org-fun-fact-title" style="margin: 0; white-space: nowrap;">{{{ settings.title_text }}}</h3>
                <# if (settings.show_title_line === 'yes') { #>
                <span class="mecas-org-fun-fact-title-line" style="flex: 1; height: 1px; background-color: #E5E7EB; margin-left: 15px;"></span>
                <# } #>
            </div>
            <div class="mecas-org-fun-fact-text">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry. Lorem Ipsum has been the industry.
            </div>
        </div>
        <?php
    }
}
