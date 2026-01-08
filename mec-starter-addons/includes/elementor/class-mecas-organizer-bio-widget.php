<?php
/**
 * MEC Starter Addons - Organizer Bio Widget
 * Displays organizer biography with full style controls
 */

if (!defined('ABSPATH')) exit;

class MECAS_Organizer_Bio_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_organizer_bio';
    }

    public function get_title() {
        return __('Organizer Bio', 'mec-starter-addons');
    }

    public function get_icon() {
        return 'eicon-text';
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

        $this->add_control('show_title', [
            'label' => __('Show Title', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('title_text', [
            'label' => __('Title', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Bio', 'mec-starter-addons'),
            'condition' => ['show_title' => 'yes'],
        ]);

        $this->add_control('show_title_line', [
            'label' => __('Show Title Line', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_title' => 'yes'],
        ]);

        $this->add_control('bio_source', [
            'label' => __('Bio Source', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'bio',
            'options' => [
                'bio' => __('Custom Bio Field', 'mec-starter-addons'),
                'description' => __('Term Description', 'mec-starter-addons'),
            ],
        ]);

        $this->add_control('preview_organizer_id', [
            'label' => __('Preview Organizer', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $this->get_organizers_list(),
        ]);

        $this->end_controls_section();

        // Title Style
        $this->start_controls_section('section_style_title', [
            'label' => __('Title', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_title' => 'yes'],
        ]);

        $this->add_control('title_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-org-bio-title' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .mecas-org-bio-title',
        ]);

        $this->add_responsive_control('space_after_title', [
            'label' => __('Space After Title', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range' => [
                'px' => ['min' => 0, 'max' => 100],
                'em' => ['min' => 0, 'max' => 6],
            ],
            'default' => ['size' => 15, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-bio-title-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;'],
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
            'selectors' => ['{{WRAPPER}} .mecas-org-bio-title-line' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('title_line_thickness', [
            'label' => __('Line Thickness', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 1, 'max' => 5]],
            'default' => ['size' => 1, 'unit' => 'px'],
            'condition' => ['show_title_line' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-org-bio-title-line' => 'height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('title_line_gap', [
            'label' => __('Gap from Title Text', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 5, 'max' => 50]],
            'default' => ['size' => 15, 'unit' => 'px'],
            'condition' => ['show_title_line' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-org-bio-title-line' => 'margin-left: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Content Style
        $this->start_controls_section('section_style_content', [
            'label' => __('Content', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('content_color', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#4B5563',
            'selectors' => [
                '{{WRAPPER}} .mecas-org-bio-text' => 'color: {{VALUE}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text p' => 'color: {{VALUE}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text span' => 'color: {{VALUE}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text li' => 'color: {{VALUE}} !important;',
            ],
        ]);

        $this->add_control('content_font_family', [
            'label' => __('Font Family', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::FONT,
            'selectors' => [
                '{{WRAPPER}} .mecas-org-bio-text' => 'font-family: {{VALUE}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text p' => 'font-family: {{VALUE}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text span' => 'font-family: {{VALUE}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text li' => 'font-family: {{VALUE}} !important;',
            ],
        ]);

        $this->add_responsive_control('content_font_size', [
            'label' => __('Font Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem'],
            'range' => [
                'px' => ['min' => 10, 'max' => 50],
                'em' => ['min' => 0.5, 'max' => 3],
            ],
            'selectors' => [
                '{{WRAPPER}} .mecas-org-bio-text' => 'font-size: {{SIZE}}{{UNIT}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text p' => 'font-size: {{SIZE}}{{UNIT}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text span' => 'font-size: {{SIZE}}{{UNIT}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text li' => 'font-size: {{SIZE}}{{UNIT}} !important;',
            ],
        ]);

        $this->add_control('content_font_weight', [
            'label' => __('Font Weight', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                '' => __('Default', 'mec-starter-addons'),
                '300' => '300 (Light)',
                '400' => '400 (Normal)',
                '500' => '500 (Medium)',
                '600' => '600 (Semi Bold)',
                '700' => '700 (Bold)',
            ],
            'selectors' => [
                '{{WRAPPER}} .mecas-org-bio-text' => 'font-weight: {{VALUE}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text p' => 'font-weight: {{VALUE}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text span' => 'font-weight: {{VALUE}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text li' => 'font-weight: {{VALUE}} !important;',
            ],
        ]);

        $this->add_responsive_control('content_line_height', [
            'label' => __('Line Height', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range' => [
                'px' => ['min' => 15, 'max' => 80],
                'em' => ['min' => 1, 'max' => 3],
            ],
            'default' => ['size' => 1.7, 'unit' => 'em'],
            'selectors' => [
                '{{WRAPPER}} .mecas-org-bio-text' => 'line-height: {{SIZE}}{{UNIT}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text p' => 'line-height: {{SIZE}}{{UNIT}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text span' => 'line-height: {{SIZE}}{{UNIT}} !important;',
                '{{WRAPPER}} .mecas-org-bio-text li' => 'line-height: {{SIZE}}{{UNIT}} !important;',
            ],
        ]);

        $this->add_responsive_control('paragraph_spacing', [
            'label' => __('Paragraph Spacing', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range' => [
                'px' => ['min' => 0, 'max' => 50],
                'em' => ['min' => 0, 'max' => 3],
            ],
            'default' => ['size' => 15, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-bio-text p' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('content_text_align', [
            'label' => __('Text Alignment', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => __('Left', 'mec-starter-addons'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'mec-starter-addons'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => __('Right', 'mec-starter-addons'), 'icon' => 'eicon-text-align-right'],
                'justify' => ['title' => __('Justify', 'mec-starter-addons'), 'icon' => 'eicon-text-align-justify'],
            ],
            'selectors' => ['{{WRAPPER}} .mecas-org-bio-text' => 'text-align: {{VALUE}} !important;'],
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
        
        if (!$organizer) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<p style="padding: 20px; background: #f5f5f5;">' . __('No organizer found. Select a Preview Organizer.', 'mec-starter-addons') . '</p>';
            }
            return;
        }

        $bio = $settings['bio_source'] === 'description' ? $organizer['description'] : $organizer['bio'];
        
        if (empty($bio)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                $bio = 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.';
            } else {
                return;
            }
        }
        
        // Process content - apply wpautop if no HTML blocks exist
        $has_blocks = preg_match('/<(p|div|ul|ol|h[1-6]|blockquote)[^>]*>/i', $bio);
        $content = $has_blocks ? $bio : wpautop($bio);
        ?>
        <div class="mecas-org-bio-section">
            <?php if ($settings['show_title'] === 'yes'): ?>
            <div class="mecas-org-bio-title-wrap">
                <h2 class="mecas-org-bio-title"><?php echo esc_html($settings['title_text']); ?></h2>
                <?php if ($settings['show_title_line'] === 'yes'): ?>
                <span class="mecas-org-bio-title-line"></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="mecas-org-bio-text"><?php echo wp_kses_post($content); ?></div>
        </div>
        <style>
        .mecas-org-bio-title-wrap {
            display: flex;
            align-items: center;
        }
        .mecas-org-bio-title {
            margin: 0;
            white-space: nowrap;
        }
        .mecas-org-bio-title-line {
            flex: 1;
            height: 1px;
            background-color: #E5E7EB;
            margin-left: 15px;
        }
        .mecas-org-bio-text p:last-child {
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
        <div class="mecas-org-bio-section">
            <# if (settings.show_title === 'yes') { #>
            <div class="mecas-org-bio-title-wrap" style="display: flex; align-items: center; margin-bottom: 15px;">
                <h2 class="mecas-org-bio-title" style="margin: 0; white-space: nowrap;">{{{ settings.title_text }}}</h2>
                <# if (settings.show_title_line === 'yes') { #>
                <span class="mecas-org-bio-title-line" style="flex: 1; height: 1px; background-color: #E5E7EB; margin-left: 15px;"></span>
                <# } #>
            </div>
            <# } #>
            <div class="mecas-org-bio-text" style="color: #4B5563; line-height: 1.7;">
                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
            </div>
        </div>
        <?php
    }
}
