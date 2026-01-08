<?php
/**
 * MEC Starter Addons - Organizer Profile Widget
 * Displays organizer photo, name, share button for single organizer pages
 */

if (!defined('ABSPATH')) exit;

class MECAS_Organizer_Profile_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_organizer_profile';
    }

    public function get_title() {
        return __('Organizer Profile Header', 'mec-starter-addons');
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['mec-starter-addons'];
    }

    public function get_keywords() {
        return ['organizer', 'teacher', 'profile', 'mec'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_photo', [
            'label' => __('Show Photo', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_name', [
            'label' => __('Show Name', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_favorite_button', [
            'label' => __('Show Favorite Button', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_share_button', [
            'label' => __('Show Share Button', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('share_button_text', [
            'label' => __('Share Button Text', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Share Profile', 'mec-starter-addons'),
            'condition' => ['show_share_button' => 'yes'],
        ]);

        $this->add_control('layout', [
            'label' => __('Layout', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'vertical',
            'options' => [
                'vertical' => __('Vertical (Stacked)', 'mec-starter-addons'),
                'horizontal' => __('Horizontal (Side by Side)', 'mec-starter-addons'),
            ],
        ]);

        $this->add_control('preview_organizer_id', [
            'label' => __('Preview Organizer', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $this->get_organizers_list(),
            'description' => __('Select an organizer for preview. On live pages, the current organizer will be used.', 'mec-starter-addons'),
        ]);

        $this->end_controls_section();

        // Photo Style
        $this->start_controls_section('section_style_photo', [
            'label' => __('Photo', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_photo' => 'yes'],
        ]);

        $this->add_responsive_control('photo_width', [
            'label' => __('Width', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => ['px' => ['min' => 50, 'max' => 500]],
            'default' => ['size' => 200, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-photo' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('photo_height', [
            'label' => __('Height', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 50, 'max' => 500]],
            'default' => ['size' => 250, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-photo' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('photo_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-photo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'photo_border',
            'selector' => '{{WRAPPER}} .mecas-org-photo',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'photo_shadow',
            'selector' => '{{WRAPPER}} .mecas-org-photo',
        ]);

        $this->end_controls_section();

        // Name Style
        $this->start_controls_section('section_style_name', [
            'label' => __('Name', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_name' => 'yes'],
        ]);

        $this->add_control('name_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-org-name' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'name_typography',
            'selector' => '{{WRAPPER}} .mecas-org-name',
        ]);

        $this->add_responsive_control('name_margin', [
            'label' => __('Margin', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .mecas-org-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // Favorite Button Style
        $this->start_controls_section('section_style_favorite', [
            'label' => __('Favorite Button', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_favorite_button' => 'yes'],
        ]);

        $this->add_control('favorite_color', [
            'label' => __('Icon Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9CA3AF',
            'selectors' => ['{{WRAPPER}} .mecas-org-favorite svg' => 'stroke: {{VALUE}};'],
        ]);

        $this->add_control('favorite_color_active', [
            'label' => __('Active Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#EF4444',
            'selectors' => ['{{WRAPPER}} .mecas-org-favorite.active svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};'],
        ]);

        $this->add_responsive_control('favorite_size', [
            'label' => __('Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 16, 'max' => 48]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-favorite svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // Share Button Style
        $this->start_controls_section('section_style_share', [
            'label' => __('Share Button', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_share_button' => 'yes'],
        ]);

        $this->add_control('share_bg_color', [
            'label' => __('Background Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#2D3748',
            'selectors' => ['{{WRAPPER}} .mecas-org-share-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('share_text_color', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-org-share-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'share_typography',
            'selector' => '{{WRAPPER}} .mecas-org-share-btn',
        ]);

        $this->add_responsive_control('share_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-share-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('share_padding', [
            'label' => __('Padding', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => ['top' => '12', 'right' => '24', 'bottom' => '12', 'left' => '24', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-org-share-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
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
                echo '<p style="padding: 20px; background: #f5f5f5; text-align: center;">' . __('No organizer found. Select a preview organizer or use this widget on an organizer archive page.', 'mec-starter-addons') . '</p>';
            }
            return;
        }

        $layout_class = $settings['layout'] === 'horizontal' ? 'mecas-org-profile-horizontal' : 'mecas-org-profile-vertical';
        ?>
        <div class="mecas-org-profile <?php echo esc_attr($layout_class); ?>">
            <?php if ($settings['show_photo'] === 'yes'): ?>
            <div class="mecas-org-photo-wrap">
                <?php if ($organizer['thumbnail']): ?>
                    <img src="<?php echo esc_url($organizer['thumbnail']); ?>" alt="<?php echo esc_attr($organizer['name']); ?>" class="mecas-org-photo">
                <?php else: ?>
                    <div class="mecas-org-photo mecas-org-photo-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                <?php endif; ?>
                
                <?php if ($settings['show_share_button'] === 'yes'): ?>
                <button type="button" class="mecas-org-share-btn" onclick="mecasShareProfile('<?php echo esc_js($organizer['name']); ?>', '<?php echo esc_js($organizer['url']); ?>')">
                    <?php echo esc_html($settings['share_button_text']); ?>
                </button>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($settings['show_name'] === 'yes'): ?>
            <div class="mecas-org-name-wrap">
                <h1 class="mecas-org-name"><?php echo esc_html($organizer['name']); ?></h1>
                <?php if ($settings['show_favorite_button'] === 'yes'): ?>
                <button type="button" class="mecas-org-favorite" data-organizer-id="<?php echo esc_attr($organizer['id']); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                </button>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <style>
        .mecas-org-profile-vertical {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .mecas-org-profile-horizontal {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 30px;
        }
        .mecas-org-photo-wrap {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .mecas-org-photo {
            object-fit: cover;
        }
        .mecas-org-photo-placeholder {
            background: #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .mecas-org-photo-placeholder svg {
            width: 40%;
            height: 40%;
            stroke: #9CA3AF;
        }
        .mecas-org-share-btn {
            display: inline-block;
            text-align: center;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: opacity 0.2s;
            width: 100%;
        }
        .mecas-org-share-btn:hover {
            opacity: 0.9;
        }
        .mecas-org-name-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .mecas-org-name {
            margin: 0;
            font-size: 32px;
            font-weight: 400;
        }
        .mecas-org-favorite {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
        }
        .mecas-org-favorite:hover svg {
            transform: scale(1.1);
        }
        .mecas-org-favorite svg {
            transition: transform 0.2s;
        }
        </style>

        <script>
        function mecasShareProfile(name, url) {
            if (navigator.share) {
                navigator.share({ title: name, url: url });
            } else {
                navigator.clipboard.writeText(url).then(function() {
                    alert('<?php esc_html_e('Profile link copied to clipboard!', 'mec-starter-addons'); ?>');
                });
            }
        }
        </script>
        <?php
    }

    private function get_current_organizer($settings) {
        $organizer_id = null;
        
        // Check for preview organizer in editor
        if (!empty($settings['preview_organizer_id'])) {
            $organizer_id = intval($settings['preview_organizer_id']);
        } 
        // Check if we're on an organizer archive
        elseif (is_tax('mec_organizer')) {
            $term = get_queried_object();
            if ($term) {
                $organizer_id = $term->term_id;
            }
        }

        if (!$organizer_id) {
            return null;
        }

        return mecas_get_organizer_data($organizer_id);
    }

    protected function content_template() {
        ?>
        <div class="mecas-org-profile mecas-org-profile-vertical">
            <div class="mecas-org-photo-wrap">
                <div style="width: 200px; height: 250px; background: #E5E7EB; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <# if (settings.show_share_button === 'yes') { #>
                <button type="button" style="background: #2D3748; color: #fff; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer;">
                    {{{ settings.share_button_text }}}
                </button>
                <# } #>
            </div>
            <# if (settings.show_name === 'yes') { #>
            <div style="display: flex; align-items: center; gap: 10px; margin-top: 20px;">
                <h1 style="margin: 0; font-size: 32px; font-weight: 400;">Jane Doe</h1>
                <# if (settings.show_favorite_button === 'yes') { #>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
                <# } #>
            </div>
            <# } #>
        </div>
        <?php
    }
}
