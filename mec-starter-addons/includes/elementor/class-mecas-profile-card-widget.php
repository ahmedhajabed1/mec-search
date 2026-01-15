<?php
/**
 * User Profile Card Widget
 */

if (!defined('ABSPATH')) exit;

class MECAS_Profile_Card_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_profile_card';
    }
    
    public function get_title() {
        return __('User Profile Card', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-person';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
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
            'preview_mode',
            [
                'label' => __('Preview Mode', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __('Auto (Current User)', 'mec-starter-addons'),
                    'preview' => __('Preview with Example Data', 'mec-starter-addons'),
                ],
                'description' => __('Use "Preview" to see example content for styling.', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'show_image',
            [
                'label' => __('Show Profile Image', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_edit_icon',
            [
                'label' => __('Show Edit Icon on Image', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_name',
            [
                'label' => __('Show Name', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_email',
            [
                'label' => __('Show Email', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_location',
            [
                'label' => __('Show Location', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_joined_date',
            [
                'label' => __('Show Joined Date', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_edit_button',
            [
                'label' => __('Show Edit Profile Button', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'edit_button_text',
            [
                'label' => __('Edit Button Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Edit Profile', 'mec-starter-addons'),
                'condition' => ['show_edit_button' => 'yes'],
            ]
        );
        
        $this->add_control(
            'edit_action',
            [
                'label' => __('Edit Button Action', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'ajax',
                'options' => [
                    'ajax' => __('Show Dashboard Edit Widget', 'mec-starter-addons'),
                    'page' => __('Link to Page', 'mec-starter-addons'),
                ],
                'condition' => ['show_edit_button' => 'yes'],
            ]
        );
        
        $this->add_control(
            'dashboard_container',
            [
                'label' => __('Dashboard Container ID', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'mecas-dashboard-edit-container',
                'description' => __('ID of the User Dashboard Edit widget container. Default works with User Dashboard Edit widget.', 'mec-starter-addons'),
                'condition' => [
                    'show_edit_button' => 'yes',
                    'edit_action' => 'ajax',
                ],
            ]
        );
        
        $this->add_control(
            'edit_profile_page',
            [
                'label' => __('Edit Profile Page', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_pages(),
                'condition' => [
                    'show_edit_button' => 'yes',
                    'edit_action' => 'page',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Card
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => __('Card', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'card_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .mecua-profile-card',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .mecua-profile-card',
            ]
        );
        
        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'content_alignment',
            [
                'label' => __('Alignment', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'mec-starter-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'mec-starter-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'mec-starter-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-card' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Image
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __('Profile Image', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'image_width',
            [
                'label' => __('Width', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 400],
                    '%' => ['min' => 20, 'max' => 100],
                ],
                'default' => ['size' => 100, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-image-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'image_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Name
        $this->start_controls_section(
            'section_style_name',
            [
                'label' => __('Name', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'name_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-name' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .mecua-profile-name',
            ]
        );
        
        $this->add_responsive_control(
            'name_margin',
            [
                'label' => __('Margin', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Email
        $this->start_controls_section(
            'section_style_email',
            [
                'label' => __('Email', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'email_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-email' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'email_typography',
                'selector' => '{{WRAPPER}} .mecua-profile-email',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Location & Date
        $this->start_controls_section(
            'section_style_meta',
            [
                'label' => __('Location & Date', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'meta_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-location, {{WRAPPER}} .mecua-profile-joined' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .mecua-profile-location, {{WRAPPER}} .mecua-profile-joined',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Button
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Button', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .mecua-profile-edit-btn',
            ]
        );
        
        $this->start_controls_tabs('button_tabs');
        
        $this->start_controls_tab('button_normal', ['label' => __('Normal', 'mec-starter-addons')]);
        
        $this->add_control(
            'button_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-edit-btn' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-edit-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('button_hover', ['label' => __('Hover', 'mec-starter-addons')]);
        
        $this->add_control(
            'button_color_hover',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-edit-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-edit-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-edit-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecua-profile-edit-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    private function get_pages() {
        $pages = get_pages();
        $options = ['' => __('Select Page', 'mec-starter-addons')];
        foreach ($pages as $page) {
            $options[$page->ID] = $page->post_title;
        }
        return $options;
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode();
        $preview_mode = $settings['preview_mode'] ?? 'auto';
        
        // Use preview data in editor when preview mode selected
        $use_preview_data = ($is_editor && $preview_mode === 'preview');
        
        if (!is_user_logged_in() && !$is_editor) {
            echo '<div class="mecua-login-required">';
            echo '<p>' . __('Please log in to view your profile.', 'mec-starter-addons') . '</p>';
            echo '<a href="' . wp_login_url(get_permalink()) . '" class="mecua-btn mecua-btn-primary">' . __('Log In', 'mec-starter-addons') . '</a>';
            echo '</div>';
            return;
        }
        
        // Get profile data - use example data for preview mode
        if ($use_preview_data || (!is_user_logged_in() && $is_editor)) {
            $profile = array(
                'name' => 'Jane Doe',
                'email' => 'janedoe123@gmail.com',
                'location' => 'Location, Location, FL',
                'joined_date' => '2025-12-20',
                'profile_image' => 'https://i.pravatar.cc/300?img=5',
            );
        } else {
            $profile = mecas_get_user_profile();
            if (!$profile) return;
        }
        
        $edit_action = $settings['edit_action'] ?? 'ajax';
        $edit_url = '#';
        $use_ajax = ($edit_action === 'ajax');
        $dashboard_container = $settings['dashboard_container'] ?? 'mecas-dashboard-edit-container';
        
        if (!$use_ajax && !empty($settings['edit_profile_page'])) {
            $edit_url = get_permalink($settings['edit_profile_page']);
        }
        
        $joined_date = '';
        if (!empty($profile['joined_date'])) {
            $date = new DateTime($profile['joined_date']);
            $joined_date = __('Joined on', 'mec-starter-addons') . ' ' . $date->format('j M Y');
        }
        
        ?>
        <div class="mecua-profile-card" data-ajax-edit="<?php echo $use_ajax ? 'true' : 'false'; ?>" data-container="<?php echo esc_attr($dashboard_container); ?>">
            <?php if ($settings['show_image'] === 'yes'): ?>
            <div class="mecua-profile-image-wrapper">
                <img src="<?php echo esc_url($profile['profile_image']); ?>" alt="<?php echo esc_attr($profile['name']); ?>" class="mecua-profile-image mecas-profile-avatar">
                <?php if ($settings['show_edit_icon'] === 'yes'): ?>
                <a href="<?php echo esc_url($edit_url); ?>" class="mecua-profile-edit-icon <?php echo $use_ajax ? 'mecas-ajax-edit-trigger' : ''; ?>" title="<?php esc_attr_e('Edit Profile', 'mec-starter-addons'); ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <div class="mecua-profile-info">
                <?php if ($settings['show_name'] === 'yes'): ?>
                <h2 class="mecua-profile-name mecas-profile-name"><?php echo esc_html($profile['name']); ?></h2>
                <?php endif; ?>
                
                <?php if ($settings['show_email'] === 'yes'): ?>
                <p class="mecua-profile-email"><?php echo esc_html($profile['email']); ?></p>
                <?php endif; ?>
                
                <?php if ($settings['show_location'] === 'yes' && $profile['location']): ?>
                <p class="mecua-profile-location"><?php echo esc_html($profile['location']); ?></p>
                <?php endif; ?>
                
                <?php if ($settings['show_joined_date'] === 'yes' && $joined_date): ?>
                <p class="mecua-profile-joined"><?php echo esc_html($joined_date); ?></p>
                <?php endif; ?>
                
                <?php if ($settings['show_edit_button'] === 'yes'): ?>
                <a href="<?php echo esc_url($edit_url); ?>" class="mecua-profile-edit-btn <?php echo $use_ajax ? 'mecas-ajax-edit-trigger' : ''; ?>">
                    <?php echo esc_html($settings['edit_button_text']); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php
        // Note: Click handling for .mecas-ajax-edit-trigger is done globally in mecas-scripts.js
        // The initDashboardEditToggle() function handles opening the User Dashboard Edit widget
        ?>
        <?php
    }
}
