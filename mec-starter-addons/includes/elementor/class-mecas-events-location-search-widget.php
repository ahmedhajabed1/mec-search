<?php
/**
 * MEC Starter Addons - Events Location Search Widget
 * AJAX-based search for events by location (shows results on same page)
 */

if (!defined('ABSPATH')) exit;

class MECAS_Events_Location_Search_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecas_events_location_search';
    }

    public function get_title() {
        return __('MEC Events Location Search', 'mec-starter-addons');
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return ['mec-starter-addons'];
    }

    public function get_keywords() {
        return ['search', 'events', 'mec', 'location', 'city'];
    }

    protected function register_controls() {
        // === CONTENT TAB ===
        
        // Search Bar Section
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
            'default' => __('%d Event found in %s', 'mec-starter-addons'),
            'description' => __('%d = number, %s = location', 'mec-starter-addons'),
            'condition' => ['show_count' => 'yes'],
        ]);

        $this->add_control('count_text_plural', [
            'label' => __('Count Text (Plural)', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('%d Events found in %s', 'mec-starter-addons'),
            'condition' => ['show_count' => 'yes'],
        ]);

        $this->add_responsive_control('columns', [
            'label' => __('Columns', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '3',
            'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'],
        ]);

        $this->add_control('per_page', [
            'label' => __('Events Per Page', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 12,
            'min' => 1,
            'max' => 50,
        ]);

        $this->add_control('show_pagination', [
            'label' => __('Show Pagination', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('no_results_text', [
            'label' => __('No Results Text', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('No events found in this location.', 'mec-starter-addons'),
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
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-bar' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('search_bar_border_color', [
            'label' => __('Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#D1D5DB',
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-bar' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('search_bar_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('search_bar_max_width', [
            'label' => __('Max Width', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => ['px' => ['min' => 200, 'max' => 1000]],
            'default' => ['size' => 600, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-form' => 'max-width: {{SIZE}}{{UNIT}};'],
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
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-input' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('input_placeholder_color', [
            'label' => __('Placeholder Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9CA3AF',
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-input::placeholder' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'input_typography',
            'selector' => '{{WRAPPER}} .mecas-events-loc-input',
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
                '{{WRAPPER}} .mecas-events-loc-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('button_bg_color', [
            'label' => __('Background Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#2D3748',
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-button' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('button_icon_color', [
            'label' => __('Icon Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-button svg' => 'stroke: {{VALUE}};'],
        ]);

        $this->add_responsive_control('button_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => '6', 'right' => '6', 'bottom' => '6', 'left' => '6', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
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
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-count' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'count_typography',
            'selector' => '{{WRAPPER}} .mecas-events-loc-count',
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
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-grid' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // Card Style
        $this->start_controls_section('section_style_card', [
            'label' => __('Event Cards', 'mec-starter-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg_color', [
            'label' => __('Background Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('card_border_color', [
            'label' => __('Border Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-card' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('card_border_radius', [
            'label' => __('Border Radius', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => ['top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('card_image_height', [
            'label' => __('Image Height', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 100, 'max' => 300]],
            'default' => ['size' => 160, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-card-image' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('card_title_color', [
            'label' => __('Title Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-card-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('card_meta_color', [
            'label' => __('Meta Color', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#6B7280',
            'selectors' => ['{{WRAPPER}} .mecas-events-loc-card-meta' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $widget_id = 'mecas-events-loc-' . $this->get_id();
        $columns = intval($settings['columns']);
        $per_page = intval($settings['per_page']);
        
        // Get current location from URL
        $current_location = isset($_GET['mecas_location']) ? sanitize_text_field($_GET['mecas_location']) : '';
        
        $enable_geo = $settings['enable_geolocation'] === 'yes' ? 'true' : 'false';
        $auto_detect = ($settings['enable_geolocation'] === 'yes' && $settings['auto_detect_location'] === 'yes') ? 'true' : 'false';
        ?>
        <div class="mecas-events-loc-wrapper" 
             id="<?php echo esc_attr($widget_id); ?>"
             data-enable-geolocation="<?php echo $enable_geo; ?>"
             data-auto-detect="<?php echo $auto_detect; ?>"
             data-per-page="<?php echo $per_page; ?>"
             data-columns="<?php echo $columns; ?>"
             data-show-count="<?php echo $settings['show_count'] === 'yes' ? 'true' : 'false'; ?>"
             data-count-singular="<?php echo esc_attr($settings['count_text_singular']); ?>"
             data-count-plural="<?php echo esc_attr($settings['count_text_plural']); ?>"
             data-show-pagination="<?php echo $settings['show_pagination'] === 'yes' ? 'true' : 'false'; ?>"
             data-no-results="<?php echo esc_attr($settings['no_results_text']); ?>">
            
            <!-- Search Form -->
            <form class="mecas-events-loc-form">
                <div class="mecas-events-loc-bar">
                    <input type="text" 
                           name="mecas_location" 
                           class="mecas-events-loc-input" 
                           placeholder="<?php echo esc_attr($settings['placeholder_location']); ?>" 
                           value="<?php echo esc_attr($current_location); ?>"
                           autocomplete="off">
                    <?php if ($settings['enable_geolocation'] === 'yes'): ?>
                    <div class="mecas-events-loc-loading" style="display:none;">
                        <svg class="mecas-spinner" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2">
                            <circle cx="12" cy="12" r="10" stroke-opacity="0.25"/>
                            <path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <?php endif; ?>
                    <button type="submit" class="mecas-events-loc-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Results Count -->
            <p class="mecas-events-loc-count" style="display: none;"></p>

            <!-- Results Grid -->
            <div class="mecas-events-loc-grid mecas-events-cols-<?php echo $columns; ?>"></div>

            <!-- Pagination -->
            <div class="mecas-events-loc-pagination"></div>

            <!-- No Results -->
            <div class="mecas-events-loc-no-results" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <p><?php echo esc_html($settings['no_results_text']); ?></p>
            </div>

            <!-- Loading State -->
            <div class="mecas-events-loc-loader" style="display: none;">
                <div class="mecas-loading-spinner"></div>
            </div>
        </div>

        <style>
        #<?php echo $widget_id; ?> .mecas-events-loc-grid.mecas-events-cols-1 { grid-template-columns: 1fr; }
        #<?php echo $widget_id; ?> .mecas-events-loc-grid.mecas-events-cols-2 { grid-template-columns: repeat(2, 1fr); }
        #<?php echo $widget_id; ?> .mecas-events-loc-grid.mecas-events-cols-3 { grid-template-columns: repeat(3, 1fr); }
        #<?php echo $widget_id; ?> .mecas-events-loc-grid.mecas-events-cols-4 { grid-template-columns: repeat(4, 1fr); }
        #<?php echo $widget_id; ?> .mecas-events-loc-grid.mecas-events-cols-5 { grid-template-columns: repeat(5, 1fr); }
        #<?php echo $widget_id; ?> .mecas-events-loc-grid.mecas-events-cols-6 { grid-template-columns: repeat(6, 1fr); }

        @media (max-width: 1024px) {
            #<?php echo $widget_id; ?> .mecas-events-loc-grid { grid-template-columns: repeat(3, 1fr) !important; }
        }
        @media (max-width: 768px) {
            #<?php echo $widget_id; ?> .mecas-events-loc-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
        @media (max-width: 480px) {
            #<?php echo $widget_id; ?> .mecas-events-loc-grid { grid-template-columns: 1fr !important; }
        }
        </style>
        <?php
    }

    protected function content_template() {
        ?>
        <div class="mecas-events-loc-wrapper" style="display: flex; flex-direction: column; align-items: center; width: 100%;">
            <div class="mecas-events-loc-form" style="max-width: 600px; width: 100%;">
                <div class="mecas-events-loc-bar" style="
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
                        cursor: pointer;
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <p style="font-size: 20px; font-weight: 600; color: #1F2937; margin: 30px 0 20px;">45 Events found in [location]</p>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; width: 100%;">
                <# for (var i = 0; i < 3; i++) { #>
                <div style="background: #fff; border: 1px solid #E5E7EB; border-radius: 8px; overflow: hidden;">
                    <div style="height: 160px; background: #E5E7EB;"></div>
                    <div style="padding: 16px;">
                        <div style="font-weight: 600; color: #1F2937; margin-bottom: 8px;">Event Title</div>
                        <div style="color: #6B7280; font-size: 14px;">Jan 15, 2025 • Location</div>
                    </div>
                </div>
                <# } #>
            </div>
        </div>
        <?php
    }
}
