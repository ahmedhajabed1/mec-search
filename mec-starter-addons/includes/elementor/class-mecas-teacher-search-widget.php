<?php
/**
 * MEC Starter Addons - Teacher Search Widget
 * Search for teachers/organizers by location with AJAX results
 */

if (!defined('ABSPATH')) exit;

class MECAS_Teacher_Search_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_teacher_search';
    }

    public function get_title() {
        return __('MEC Teacher Search', 'mec-starter-addons');
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['mec-starter-addons'];
    }

    public function get_keywords() {
        return ['search', 'teacher', 'organizer', 'mec', 'location'];
    }

    protected function register_controls() {
        // === CONTENT TAB ===
        
        // General Section
        $this->start_controls_section('section_general', [
            'label' => __('Search Bar', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('placeholder_location', [
            'label' => __('Placeholder Text', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('City, State', 'mec-starter-addons'),
        ]);

        $this->add_control('enable_geolocation', [
            'label' => __('Enable Geolocation', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('auto_detect_location', [
            'label' => __('Auto-Detect Location', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('Automatically detect user location on page load', 'mec-starter-addons'),
            'condition' => ['enable_geolocation' => 'yes'],
        ]);

        $this->end_controls_section();

        // Results Section
        $this->start_controls_section('section_results', [
            'label' => __('Results', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_count', [
            'label' => __('Show Results Count', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('count_text_singular', [
            'label' => __('Count Text (Singular)', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('%d Teacher found in %s', 'mec-starter-addons'),
            'description' => __('%d = number, %s = location', 'mec-starter-addons'),
            'condition' => ['show_count' => 'yes'],
        ]);

        $this->add_control('count_text_plural', [
            'label' => __('Count Text (Plural)', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('%d Teachers found in %s', 'mec-starter-addons'),
            'condition' => ['show_count' => 'yes'],
        ]);

        $this->add_responsive_control('columns', [
            'label' => __('Columns', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '6',
            'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'],
        ]);

        $this->add_control('per_page', [
            'label' => __('Teachers Per Page', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 24,
            'min' => 1,
            'max' => 100,
        ]);

        $this->add_control('show_pagination', [
            'label' => __('Show Pagination', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('no_results_text', [
            'label' => __('No Results Text', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('No teachers found in this location.', 'mec-starter-addons'),
        ]);

        $this->end_controls_section();

        // Card Content Section
        $this->start_controls_section('section_card_content', [
            'label' => __('Card Content', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_location_bar', [
            'label' => __('Show Location Bar', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_heart_icon', [
            'label' => __('Show Heart Icon', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_location_bar' => 'yes'],
        ]);

        $this->add_control('show_name', [
            'label' => __('Show Name', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_tagline', [
            'label' => __('Show Tagline', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();

        // === STYLE TAB ===

        // Search Bar Style
        $this->start_controls_section('section_style_search_bar', [
            'label' => __('Search Bar', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('search_bar_bg', [
            'label' => __('Background Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-teacher-search-bar' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('search_bar_border_color', [
            'label' => __('Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#D1D5DB',
            'selectors' => ['{{WRAPPER}} .mecas-teacher-search-bar' => 'border-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('search_bar_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-teacher-search-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('search_bar_max_width', [
            'label' => __('Max Width', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => ['px' => ['min' => 200, 'max' => 1000]],
            'default' => ['size' => 600, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-teacher-search-form' => 'max-width: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Input Field Style
        $this->start_controls_section('section_style_input', [
            'label' => __('Input Field', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('input_text_color', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-teacher-search-input' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('input_placeholder_color', [
            'label' => __('Placeholder Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9CA3AF',
            'selectors' => ['{{WRAPPER}} .mecas-teacher-search-input::placeholder' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'input_typography',
            'selector' => '{{WRAPPER}} .mecas-teacher-search-input',
        ]);

        $this->end_controls_section();

        // Search Button Style
        $this->start_controls_section('section_style_button', [
            'label' => __('Search Button', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('button_size', [
            'label' => __('Size', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 30, 'max' => 80]],
            'default' => ['size' => 44, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mecas-teacher-search-button' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; min-width: {{SIZE}}{{UNIT}} !important;',
            ],
        ]);

        $this->add_control('button_bg_color', [
            'label' => __('Background Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#2D3748',
            'selectors' => ['{{WRAPPER}} .mecas-teacher-search-button' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('button_icon_color', [
            'label' => __('Icon Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-teacher-search-button svg' => 'stroke: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('button_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '6', 'right' => '6', 'bottom' => '6', 'left' => '6', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-teacher-search-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // Count Style
        $this->start_controls_section('section_style_count', [
            'label' => __('Results Count', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('count_color', [
            'label' => __('Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-teacher-search-count' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'count_typography',
            'selector' => '{{WRAPPER}} .mecas-teacher-search-count',
        ]);

        $this->end_controls_section();

        // Grid Style
        $this->start_controls_section('section_style_grid', [
            'label' => __('Grid', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('grid_gap', [
            'label' => __('Gap', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-teacher-search-grid' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // Card Style
        $this->start_controls_section('section_style_card', [
            'label' => __('Card', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg_color', [
            'label' => __('Background Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-teacher-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('card_border_color', [
            'label' => __('Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => ['{{WRAPPER}} .mecas-teacher-card' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('card_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-teacher-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // Image Style
        $this->start_controls_section('section_style_image', [
            'label' => __('Image', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('image_height', [
            'label' => __('Height', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 100, 'max' => 400]],
            'default' => ['size' => 180, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-teacher-image-wrapper' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // Location Bar Style
        $this->start_controls_section('section_style_location_bar', [
            'label' => __('Location Bar', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('location_bar_bg', [
            'label' => __('Background Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E8927C',
            'selectors' => ['{{WRAPPER}} .mecas-teacher-location-bar' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('location_bar_text_color', [
            'label' => __('Text Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-teacher-location-bar' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // Content Style
        $this->start_controls_section('section_style_content', [
            'label' => __('Content', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('name_color', [
            'label' => __('Name Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-teacher-name' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('tagline_color', [
            'label' => __('Tagline Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#6B7280',
            'selectors' => ['{{WRAPPER}} .mecas-teacher-tagline' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $widget_id = 'mecas-teacher-search-' . $this->get_id();
        $columns = intval($settings['columns']);
        $per_page = intval($settings['per_page']);
        
        // Get current search location from URL
        $current_location = isset($_GET['mecas_location']) ? sanitize_text_field($_GET['mecas_location']) : '';
        
        $enable_geo = $settings['enable_geolocation'] === 'yes' ? 'true' : 'false';
        $auto_detect = ($settings['enable_geolocation'] === 'yes' && $settings['auto_detect_location'] === 'yes') ? 'true' : 'false';
        ?>
        <div class="mecas-teacher-search-wrapper" 
             id="<?php echo esc_attr($widget_id); ?>"
             data-enable-geolocation="<?php echo $enable_geo; ?>"
             data-auto-detect="<?php echo $auto_detect; ?>"
             data-per-page="<?php echo $per_page; ?>"
             data-columns="<?php echo $columns; ?>"
             data-show-count="<?php echo $settings['show_count'] === 'yes' ? 'true' : 'false'; ?>"
             data-count-singular="<?php echo esc_attr($settings['count_text_singular']); ?>"
             data-count-plural="<?php echo esc_attr($settings['count_text_plural']); ?>"
             data-show-location-bar="<?php echo $settings['show_location_bar'] === 'yes' ? 'true' : 'false'; ?>"
             data-show-heart="<?php echo $settings['show_heart_icon'] === 'yes' ? 'true' : 'false'; ?>"
             data-show-name="<?php echo $settings['show_name'] === 'yes' ? 'true' : 'false'; ?>"
             data-show-tagline="<?php echo $settings['show_tagline'] === 'yes' ? 'true' : 'false'; ?>"
             data-show-pagination="<?php echo $settings['show_pagination'] === 'yes' ? 'true' : 'false'; ?>"
             data-no-results="<?php echo esc_attr($settings['no_results_text']); ?>">
            
            <!-- Search Form -->
            <form class="mecas-teacher-search-form">
                <div class="mecas-teacher-search-bar">
                    <input type="text" 
                           name="mecas_location" 
                           class="mecas-teacher-search-input" 
                           placeholder="<?php echo esc_attr($settings['placeholder_location']); ?>" 
                           value="<?php echo esc_attr($current_location); ?>"
                           autocomplete="off">
                    <?php if ($settings['enable_geolocation'] === 'yes'): ?>
                    <div class="mecas-teacher-search-loading" style="display:none;">
                        <svg class="mecas-spinner" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" stroke-opacity="0.25"/>
                            <path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <?php endif; ?>
                    <button type="submit" class="mecas-teacher-search-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Results Count -->
            <p class="mecas-teacher-search-count" style="display: none;"></p>

            <!-- Results Grid -->
            <div class="mecas-teacher-search-grid mecas-teacher-cols-<?php echo $columns; ?>"></div>

            <!-- Pagination -->
            <div class="mecas-teacher-pagination"></div>

            <!-- No Results -->
            <div class="mecas-teacher-search-no-results" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <p><?php echo esc_html($settings['no_results_text']); ?></p>
            </div>

            <!-- Loading State -->
            <div class="mecas-teacher-search-loader" style="display: none;">
                <div class="mecas-loading-spinner"></div>
            </div>
        </div>

        <style>
        #<?php echo $widget_id; ?> .mecas-teacher-search-grid.mecas-teacher-cols-1 { grid-template-columns: 1fr; }
        #<?php echo $widget_id; ?> .mecas-teacher-search-grid.mecas-teacher-cols-2 { grid-template-columns: repeat(2, 1fr); }
        #<?php echo $widget_id; ?> .mecas-teacher-search-grid.mecas-teacher-cols-3 { grid-template-columns: repeat(3, 1fr); }
        #<?php echo $widget_id; ?> .mecas-teacher-search-grid.mecas-teacher-cols-4 { grid-template-columns: repeat(4, 1fr); }
        #<?php echo $widget_id; ?> .mecas-teacher-search-grid.mecas-teacher-cols-5 { grid-template-columns: repeat(5, 1fr); }
        #<?php echo $widget_id; ?> .mecas-teacher-search-grid.mecas-teacher-cols-6 { grid-template-columns: repeat(6, 1fr); }

        @media (max-width: 1024px) {
            #<?php echo $widget_id; ?> .mecas-teacher-search-grid { grid-template-columns: repeat(4, 1fr) !important; }
        }
        @media (max-width: 768px) {
            #<?php echo $widget_id; ?> .mecas-teacher-search-grid { grid-template-columns: repeat(3, 1fr) !important; }
        }
        @media (max-width: 576px) {
            #<?php echo $widget_id; ?> .mecas-teacher-search-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
        @media (max-width: 400px) {
            #<?php echo $widget_id; ?> .mecas-teacher-search-grid { grid-template-columns: 1fr !important; }
        }
        </style>
        <?php
    }

    protected function content_template() {
        ?>
        <div class="mecas-teacher-search-wrapper" style="display: flex; flex-direction: column; align-items: center; width: 100%;">
            <div class="mecas-teacher-search-form" style="max-width: 600px; width: 100%;">
                <div class="mecas-teacher-search-bar" style="
                    display: flex;
                    align-items: center;
                    background-color: #FFFFFF;
                    border: 1px solid #D1D5DB;
                    border-radius: 8px;
                    padding: 4px 4px 4px 16px;
                    height: 48px;
                    box-sizing: border-box;
                ">
                    <input type="text" placeholder="{{{ settings.placeholder_location }}}" style="
                        flex: 1;
                        border: none;
                        outline: none;
                        background: transparent;
                        font-size: 15px;
                        padding: 0 8px;
                        color: #1F2937;
                    ">
                    <button type="button" style="
                        width: 40px;
                        height: 40px;
                        min-width: 40px;
                        border-radius: 6px;
                        background-color: #2D3748;
                        border: none;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <p style="font-size: 24px; font-weight: 600; color: #1F2937; margin: 30px 0 20px;">X Teachers found in [location]</p>
            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 20px; width: 100%;">
                <# for (var i = 0; i < 6; i++) { #>
                <div style="background: #fff; border: 1px solid #E5E7EB; border-radius: 12px; overflow: hidden;">
                    <div style="height: 180px; background: #E5E7EB;"></div>
                    <div style="background: #E8927C; color: #fff; padding: 10px 16px; font-size: 13px;">Tampa Bay, FL</div>
                    <div style="padding: 16px; background: #fff;">
                        <div style="font-weight: 600; color: #1F2937;">Teacher Name</div>
                        <div style="color: #6B7280; font-size: 14px;">Tagline here...</div>
                    </div>
                </div>
                <# } #>
            </div>
        </div>
        <?php
    }
}
