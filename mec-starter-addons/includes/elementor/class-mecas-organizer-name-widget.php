<?php
/**
 * MEC Starter Addons - Organizer Name Widget
 * Displays organizer name with optional icon
 */

if (!defined('ABSPATH')) exit;

class MECAS_Organizer_Name_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_organizer_name';
    }

    public function get_title() {
        return __('Organizer Name', 'mec-starter-addons');
    }

    public function get_icon() {
        return 'eicon-heading';
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

        $this->add_control('show_icon', [
            'label' => __('Show Icon', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('icon', [
            'label' => __('Icon', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-user',
                'library' => 'fa-solid',
            ],
            'condition' => ['show_icon' => 'yes'],
        ]);

        $this->add_control('icon_position', [
            'label' => __('Icon Position', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'before',
            'options' => [
                'before' => __('Before Name', 'mec-starter-addons'),
                'after' => __('After Name', 'mec-starter-addons'),
            ],
            'condition' => ['show_icon' => 'yes'],
        ]);

        $this->add_control('html_tag', [
            'label' => __('HTML Tag', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'h1',
            'options' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'p' => 'p',
                'span' => 'span',
                'div' => 'div',
            ],
        ]);

        $this->add_control('link_to_archive', [
            'label' => __('Link to Archive', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
        ]);

        $this->add_control('preview_organizer_id', [
            'label' => __('Preview Organizer', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $this->get_organizers_list(),
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        // Style - Name
        $this->start_controls_section('section_style_name', [
            'label' => __('Name', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('name_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-org-name-text' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('name_color_hover', [
            'label' => __('Hover Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'condition' => ['link_to_archive' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-org-name-link:hover .mecas-org-name-text' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'name_typography',
            'selector' => '{{WRAPPER}} .mecas-org-name-text',
        ]);

        $this->add_responsive_control('name_alignment', [
            'label' => __('Alignment', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => __('Left', 'mec-starter-addons'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'mec-starter-addons'), 'icon' => 'eicon-text-align-center'],
                'flex-end' => ['title' => __('Right', 'mec-starter-addons'), 'icon' => 'eicon-text-align-right'],
            ],
            'selectors' => ['{{WRAPPER}} .mecas-org-name-wrap' => 'justify-content: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // Style - Icon
        $this->start_controls_section('section_style_icon', [
            'label' => __('Icon', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_icon' => 'yes'],
        ]);

        $this->add_control('icon_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => [
                '{{WRAPPER}} .mecas-org-name-icon' => 'color: {{VALUE}} !important;',
                '{{WRAPPER}} .mecas-org-name-icon svg' => 'fill: {{VALUE}} !important;',
            ],
        ]);

        $this->add_responsive_control('icon_size', [
            'label' => __('Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range' => ['px' => ['min' => 10, 'max' => 100]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mecas-org-name-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
                '{{WRAPPER}} .mecas-org-name-icon svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
            ],
        ]);

        $this->add_responsive_control('icon_gap', [
            'label' => __('Gap', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 10, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-name-wrap' => 'gap: {{SIZE}}{{UNIT}} !important;'],
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
                echo '<p style="padding: 10px; background: #f5f5f5; text-align: center;">' . __('Select an organizer to preview', 'mec-starter-addons') . '</p>';
            }
            return;
        }

        $tag = $settings['html_tag'];
        $link_url = $settings['link_to_archive'] === 'yes' ? $organizer['url'] : '';
        $show_icon = $settings['show_icon'] === 'yes';
        $icon_position = $settings['icon_position'];
        ?>
        <div class="mecas-org-name-wrap <?php echo $icon_position === 'after' ? 'icon-after' : 'icon-before'; ?>">
            <?php if ($link_url): ?>
            <a href="<?php echo esc_url($link_url); ?>" class="mecas-org-name-link">
            <?php endif; ?>

            <?php if ($show_icon && $icon_position === 'before'): ?>
            <span class="mecas-org-name-icon">
                <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
            </span>
            <?php endif; ?>

            <<?php echo esc_attr($tag); ?> class="mecas-org-name-text"><?php echo esc_html($organizer['name']); ?></<?php echo esc_attr($tag); ?>>

            <?php if ($show_icon && $icon_position === 'after'): ?>
            <span class="mecas-org-name-icon">
                <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
            </span>
            <?php endif; ?>

            <?php if ($link_url): ?>
            </a>
            <?php endif; ?>
        </div>
        <style>
        .mecas-org-name-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .mecas-org-name-text {
            margin: 0;
        }
        .mecas-org-name-link {
            display: flex;
            align-items: center;
            gap: inherit;
            text-decoration: none;
            color: inherit;
        }
        .mecas-org-name-icon {
            display: flex;
            align-items: center;
            line-height: 1;
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
        <#
        var iconPosition = settings.icon_position || 'before';
        var showIcon = settings.show_icon === 'yes';
        var tag = settings.html_tag || 'h1';
        #>
        <div class="mecas-org-name-wrap" style="display: flex; align-items: center; gap: 10px;">
            <# if (showIcon && iconPosition === 'before') { #>
            <span class="mecas-org-name-icon">
                <i class="{{ settings.icon.value }}"></i>
            </span>
            <# } #>
            <{{{ tag }}} class="mecas-org-name-text" style="margin: 0;">Organizer Name</{{{ tag }}}>
            <# if (showIcon && iconPosition === 'after') { #>
            <span class="mecas-org-name-icon">
                <i class="{{ settings.icon.value }}"></i>
            </span>
            <# } #>
        </div>
        <?php
    }
}
