<?php
/**
 * MEC Starter Addons - Organizer Social Links Widget
 * Displays organizer social media links with customizable icons
 */

if (!defined('ABSPATH')) exit;

class MECAS_Organizer_Social_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_organizer_social';
    }

    public function get_title() {
        return __('Organizer Social Links', 'mec-starter-addons');
    }

    public function get_icon() {
        return 'eicon-share';
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
            'default' => __('Links', 'mec-starter-addons'),
            'condition' => ['show_title' => 'yes'],
        ]);

        $this->add_control('show_title_line', [
            'label' => __('Show Title Line', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_title' => 'yes'],
        ]);

        $this->add_control('info_notice', [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => '<div style="background: #f0f0f1; padding: 10px; border-radius: 4px; font-size: 12px; color: #50575e;"><strong>Note:</strong> Only social links with URLs will be displayed. Edit the organizer to add/remove links.</div>',
            'separator' => 'before',
        ]);

        $this->add_control('preview_organizer_id', [
            'label' => __('Preview Organizer', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $this->get_organizers_list(),
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        // Icons Section
        $this->start_controls_section('section_icons', [
            'label' => __('Custom Icons', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

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

        $this->add_control('icon_linkedin', [
            'label' => __('LinkedIn Icon', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
            'default' => [],
        ]);

        $this->add_control('icon_website', [
            'label' => __('Website Icon', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'skin' => 'inline',
            'label_block' => false,
            'default' => [],
        ]);

        $this->end_controls_section();

        // Container Style
        $this->start_controls_section('section_style_box', [
            'label' => __('Container', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('box_bg_color', [
            'label' => __('Background Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .mecas-org-social' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'box_border',
            'selector' => '{{WRAPPER}} .mecas-org-social',
        ]);

        $this->add_responsive_control('box_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-social' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('box_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-org-social' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
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
            'selectors' => ['{{WRAPPER}} .mecas-org-social-title' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .mecas-org-social-title',
        ]);

        $this->add_responsive_control('title_margin', [
            'label' => __('Margin', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '0', 'right' => '0', 'bottom' => '15', 'left' => '0', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-social-title-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
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
            'selectors' => ['{{WRAPPER}} .mecas-org-social-title-line' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('title_line_height', [
            'label' => __('Line Height', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 1, 'max' => 5]],
            'default' => ['size' => 1, 'unit' => 'px'],
            'condition' => ['show_title_line' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-org-social-title-line' => 'height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('title_line_gap', [
            'label' => __('Gap from Title', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 5, 'max' => 30]],
            'default' => ['size' => 15, 'unit' => 'px'],
            'condition' => ['show_title_line' => 'yes'],
            'selectors' => ['{{WRAPPER}} .mecas-org-social-title-line' => 'margin-left: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Icons Style
        $this->start_controls_section('section_style_icons', [
            'label' => __('Icons', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('icon_size', [
            'label' => __('Icon Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 12, 'max' => 40]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mecas-org-social-link svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
                '{{WRAPPER}} .mecas-org-social-link i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
            ],
        ]);

        $this->add_responsive_control('button_size', [
            'label' => __('Button Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 24, 'max' => 60]],
            'default' => ['size' => 32, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-social-link' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('icons_gap', [
            'label' => __('Gap', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'default' => ['size' => 10, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-social-links' => 'gap: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_control('icon_color', [
            'label' => __('Icon Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => [
                '{{WRAPPER}} .mecas-org-social-link svg' => 'fill: {{VALUE}} !important;',
                '{{WRAPPER}} .mecas-org-social-link i' => 'color: {{VALUE}} !important;',
            ],
        ]);

        $this->add_control('icon_bg_color', [
            'label' => __('Background Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-org-social-link' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('icon_bg_color_hover', [
            'label' => __('Background Color (Hover)', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#374151',
            'selectors' => ['{{WRAPPER}} .mecas-org-social-link:hover' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('icon_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => ['px' => ['min' => 0, 'max' => 30], '%' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 50, 'unit' => '%'],
            'selectors' => ['{{WRAPPER}} .mecas-org-social-link' => 'border-radius: {{SIZE}}{{UNIT}} !important;'],
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
        
        $links = [];
        
        if ($organizer) {
            // Only add links that have URLs
            if (!empty($organizer['instagram'])) {
                $links['instagram'] = $organizer['instagram'];
            }
            if (!empty($organizer['twitter'])) {
                $links['x'] = $organizer['twitter']; // Changed to 'x'
            }
            if (!empty($organizer['facebook'])) {
                $links['facebook'] = $organizer['facebook'];
            }
            if (!empty($organizer['tiktok'])) {
                $links['tiktok'] = $organizer['tiktok'];
            }
            if (!empty($organizer['linkedin'])) {
                $links['linkedin'] = $organizer['linkedin'];
            }
            if (!empty($organizer['page_url'])) {
                $links['website'] = $organizer['page_url'];
            }
        }
        
        // Show placeholder in editor only if no links exist
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if (empty($links) && $is_editor) {
            echo '<div class="mecas-org-social" style="padding: 20px; background: #f9fafb; border: 1px dashed #d1d5db; border-radius: 8px; text-align: center; color: #6b7280;">';
            echo '<p style="margin: 0;">No social links found for this organizer.</p>';
            echo '<p style="margin: 5px 0 0; font-size: 12px;">Add links in the organizer settings.</p>';
            echo '</div>';
            return;
        }

        // Don't render anything if no links on frontend
        if (empty($links)) {
            return;
        }

        $default_icons = $this->get_default_icons();
        ?>
        <div class="mecas-org-social">
            <?php if ($settings['show_title'] === 'yes'): ?>
            <div class="mecas-org-social-title-wrap">
                <h3 class="mecas-org-social-title"><?php echo esc_html($settings['title_text']); ?></h3>
                <?php if ($settings['show_title_line'] === 'yes'): ?>
                <span class="mecas-org-social-title-line"></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="mecas-org-social-links">
                <?php foreach ($links as $platform => $url): 
                    $icon_key = 'icon_' . $platform;
                    $custom_icon = isset($settings[$icon_key]) && !empty($settings[$icon_key]['value']) ? $settings[$icon_key] : null;
                    $platform_label = $platform === 'x' ? 'X' : ucfirst($platform);
                ?>
                <a href="<?php echo esc_url($url); ?>" class="mecas-org-social-link mecas-social-<?php echo esc_attr($platform); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo esc_attr($platform_label); ?>">
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
        </div>
        <style>
        .mecas-org-social-title-wrap {
            display: flex;
            align-items: center;
            margin: 0 0 15px 0;
        }
        .mecas-org-social-title {
            font-size: 18px;
            font-weight: 500;
            margin: 0;
            white-space: nowrap;
        }
        .mecas-org-social-title-line {
            flex: 1;
            height: 1px;
            background-color: #E5E7EB;
            margin-left: 15px;
        }
        .mecas-org-social-links {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .mecas-org-social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #1F2937;
            transition: background-color 0.2s, transform 0.2s;
            text-decoration: none;
        }
        .mecas-org-social-link:hover {
            transform: scale(1.1);
        }
        .mecas-org-social-link svg {
            width: 16px;
            height: 16px;
            fill: #fff;
        }
        .mecas-org-social-link i {
            font-size: 16px;
            color: #fff;
        }
        </style>
        <?php
    }

    private function get_default_icons() {
        return [
            'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
            'x' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
            'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
            'tiktok' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>',
            'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
            'website' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm1 16.057v-3.057h2.994c-.059 1.143-.212 2.24-.456 3.279-.823-.12-1.674-.188-2.538-.222zm1.957 2.162c-.499 1.33-1.159 2.497-1.957 3.456v-3.62c.666.028 1.319.081 1.957.164zm-1.957-7.219v-3.015c.868-.034 1.721-.103 2.548-.224.238 1.027.389 2.111.446 3.239h-2.994zm0-5.014v-3.661c.806.969 1.471 2.15 1.971 3.496-.642.084-1.3.137-1.971.165zm2.703-3.267c1.237.496 2.354 1.228 3.29 2.146-.642.234-1.311.442-2.019.607-.344-.992-.775-1.91-1.271-2.753zm-7.241 13.56c-.244-1.039-.398-2.136-.456-3.279h2.994v3.057c-.865.034-1.714.102-2.538.222zm2.538 1.776v3.62c-.798-.959-1.458-2.126-1.957-3.456.638-.083 1.291-.136 1.957-.164zm-2.994-7.055c.057-1.128.207-2.212.446-3.239.827.121 1.68.19 2.548.224v3.015h-2.994zm1.024-5.179c.5-1.346 1.165-2.527 1.97-3.496v3.661c-.671-.028-1.329-.081-1.97-.165zm-2.005-.35c-.708-.165-1.377-.373-2.018-.607.937-.918 2.053-1.65 3.29-2.146-.496.844-.927 1.762-1.272 2.753zm-.549 1.918c-.264 1.151-.434 2.36-.492 3.611h-3.933c.165-1.658.739-3.197 1.617-4.518.88.361 1.816.67 2.808.907zm.009 9.262c-.988.236-1.92.542-2.797.9-.89-1.328-1.471-2.879-1.637-4.551h3.934c.058 1.265.231 2.488.5 3.651zm.553 1.917c.342.976.768 1.881 1.257 2.712-1.223-.49-2.326-1.211-3.256-2.115.636-.229 1.299-.435 1.999-.597zm9.924 0c.7.163 1.362.367 1.999.597-.931.903-2.034 1.625-3.257 2.116.489-.832.915-1.737 1.258-2.713zm.553-1.917c.27-1.163.442-2.386.501-3.651h3.934c-.167 1.672-.748 3.223-1.638 4.551-.877-.358-1.81-.664-2.797-.9zm.501-5.651c-.058-1.251-.229-2.46-.492-3.611.992-.237 1.929-.546 2.809-.907.877 1.321 1.451 2.86 1.616 4.518h-3.933z"/></svg>',
        ];
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
        <div class="mecas-org-social">
            <# if (settings.show_title === 'yes') { #>
            <div class="mecas-org-social-title-wrap" style="display: flex; align-items: center; margin-bottom: 15px;">
                <h3 class="mecas-org-social-title" style="font-size: 18px; font-weight: 500; margin: 0; white-space: nowrap;">{{{ settings.title_text }}}</h3>
                <# if (settings.show_title_line === 'yes') { #>
                <span class="mecas-org-social-title-line" style="flex: 1; height: 1px; background-color: #E5E7EB; margin-left: 15px;"></span>
                <# } #>
            </div>
            <# } #>
            <div class="mecas-org-social-links" style="display: flex; gap: 10px;">
                <div class="mecas-org-social-link" style="width: 32px; height: 32px; background: #1F2937; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#fff"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </div>
                <div class="mecas-org-social-link" style="width: 32px; height: 32px; background: #1F2937; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#fff"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </div>
                <div class="mecas-org-social-link" style="width: 32px; height: 32px; background: #1F2937; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#fff"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </div>
                <div class="mecas-org-social-link" style="width: 32px; height: 32px; background: #1F2937; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#fff"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                </div>
            </div>
            <p style="font-size: 11px; color: #9ca3af; margin-top: 10px;">Preview only - actual links pulled from organizer data</p>
        </div>
        <?php
    }
}
