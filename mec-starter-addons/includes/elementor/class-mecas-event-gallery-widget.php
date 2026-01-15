<?php
/**
 * Event Gallery Widget - Shows event photos gallery
 */

if (!defined('ABSPATH')) exit;

class MECAS_Event_Gallery_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_event_gallery';
    }
    
    public function get_title() {
        return __('Event Gallery', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-gallery-grid';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['event', 'gallery', 'photos', 'images'];
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
            'show_title',
            [
                'label' => __('Show Title', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'title_text',
            [
                'label' => __('Title', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Photos', 'mec-starter-addons'),
                'condition' => ['show_title' => 'yes'],
            ]
        );
        
        $this->add_control(
            'show_line',
            [
                'label' => __('Show Line After Title', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_title' => 'yes'],
            ]
        );
        
        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '2',
                'options' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecas-gallery-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );
        
        $this->add_control(
            'max_images',
            [
                'label' => __('Max Images', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 50,
            ]
        );
        
        $this->add_control(
            'enable_lightbox',
            [
                'label' => __('Enable Lightbox', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'hide_if_empty',
            [
                'label' => __('Hide Widget if No Images', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
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
                    '{{WRAPPER}} .mecas-gallery-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mecas-gallery-title',
            ]
        );
        
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __('Margin', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-gallery-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'line_color',
            [
                'label' => __('Line Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E5E7EB',
                'selectors' => [
                    '{{WRAPPER}} .mecas-gallery-header::after' => 'background-color: {{VALUE}};',
                ],
                'condition' => ['show_line' => 'yes'],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Grid
        $this->start_controls_section(
            'section_style_grid',
            [
                'label' => __('Grid', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'grid_gap',
            [
                'label' => __('Gap', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 15, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-gallery-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Images
        $this->start_controls_section(
            'section_style_images',
            [
                'label' => __('Images', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Height', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 80, 'max' => 300],
                ],
                'default' => ['size' => 120, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-gallery-item' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'image_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '10',
                    'right' => '10',
                    'bottom' => '10',
                    'left' => '10',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecas-gallery-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'image_hover_opacity',
            [
                'label' => __('Hover Opacity', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0.1, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['size' => 0.8],
                'selectors' => [
                    '{{WRAPPER}} .mecas-gallery-item:hover' => 'opacity: {{SIZE}};',
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
        
        if (!$event_id && $is_editor) {
            $this->render_editor_placeholder($settings);
            return;
        }
        
        if (!$event_id) {
            return;
        }
        
        // Get gallery images
        $gallery = get_post_meta($event_id, 'mecas_event_gallery', true);
        
        if (empty($gallery) || !is_array($gallery)) {
            if ($settings['hide_if_empty'] === 'yes') {
                return;
            }
            echo '<div class="mecas-gallery-empty">' . __('No photos available.', 'mec-starter-addons') . '</div>';
            return;
        }
        
        // Limit images
        $gallery = array_slice($gallery, 0, $settings['max_images']);
        
        $lightbox_class = $settings['enable_lightbox'] === 'yes' ? 'mecas-lightbox-enabled' : '';
        $show_line_class = $settings['show_line'] === 'yes' ? 'mecas-has-line' : '';
        
        ?>
        <div class="mecas-gallery-wrapper <?php echo esc_attr($lightbox_class); ?>">
            <?php if ($settings['show_title'] === 'yes'): ?>
            <div class="mecas-gallery-header <?php echo esc_attr($show_line_class); ?>">
                <h3 class="mecas-gallery-title"><?php echo esc_html($settings['title_text']); ?></h3>
            </div>
            <?php endif; ?>
            
            <div class="mecas-gallery-grid">
                <?php foreach ($gallery as $image_id): 
                    $image_url = wp_get_attachment_image_url($image_id, 'medium');
                    $image_full = wp_get_attachment_image_url($image_id, 'large');
                    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                    
                    if (!$image_url) continue;
                ?>
                <a href="<?php echo esc_url($image_full); ?>" 
                   class="mecas-gallery-item" 
                   style="background-image: url('<?php echo esc_url($image_url); ?>');"
                   data-lightbox="event-gallery"
                   title="<?php echo esc_attr($image_alt); ?>">
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <style>
        .mecas-gallery-wrapper {
            /* Base styles */
        }
        .mecas-gallery-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .mecas-gallery-header.mecas-has-line::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #E5E7EB;
        }
        .mecas-gallery-title {
            margin: 0;
            font-size: 24px;
            font-weight: 400;
            white-space: nowrap;
        }
        .mecas-gallery-grid {
            display: grid;
            gap: 15px;
        }
        .mecas-gallery-item {
            display: block;
            height: 120px;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            transition: opacity 0.2s ease, transform 0.2s ease;
            cursor: pointer;
        }
        .mecas-gallery-item:hover {
            opacity: 0.8;
            transform: scale(1.02);
        }
        .mecas-gallery-empty {
            text-align: center;
            padding: 30px;
            color: #6B7280;
        }
        </style>
        
        <?php if ($settings['enable_lightbox'] === 'yes'): ?>
        <script>
        (function($) {
            $(document).ready(function() {
                // Simple lightbox
                if (typeof $.fn.magnificPopup !== 'undefined') {
                    $('.mecas-lightbox-enabled .mecas-gallery-item').magnificPopup({
                        type: 'image',
                        gallery: {
                            enabled: true
                        }
                    });
                } else {
                    // Fallback lightbox
                    $('.mecas-lightbox-enabled .mecas-gallery-item').on('click', function(e) {
                        e.preventDefault();
                        var src = $(this).attr('href');
                        
                        var $overlay = $('<div class="mecas-lightbox-overlay"><img src="' + src + '"><span class="mecas-lightbox-close">&times;</span></div>');
                        $('body').append($overlay);
                        
                        $overlay.on('click', function() {
                            $(this).remove();
                        });
                    });
                }
            });
        })(jQuery);
        </script>
        <style>
        .mecas-lightbox-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            cursor: pointer;
        }
        .mecas-lightbox-overlay img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
        }
        .mecas-lightbox-close {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 40px;
            color: #fff;
            cursor: pointer;
        }
        </style>
        <?php endif; ?>
        <?php
    }
    
    private function render_editor_placeholder($settings) {
        $lightbox_class = $settings['enable_lightbox'] === 'yes' ? 'mecas-lightbox-enabled' : '';
        $show_line_class = $settings['show_line'] === 'yes' ? 'mecas-has-line' : '';
        ?>
        <div class="mecas-gallery-wrapper <?php echo esc_attr($lightbox_class); ?>">
            <?php if ($settings['show_title'] === 'yes'): ?>
            <div class="mecas-gallery-header <?php echo esc_attr($show_line_class); ?>">
                <h3 class="mecas-gallery-title"><?php echo esc_html($settings['title_text']); ?></h3>
            </div>
            <?php endif; ?>
            
            <div class="mecas-gallery-grid">
                <?php for ($i = 0; $i < min(6, $settings['max_images']); $i++): ?>
                <div class="mecas-gallery-item mecas-gallery-placeholder" style="background-color: #E5E7EB;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/>
                    </svg>
                </div>
                <?php endfor; ?>
            </div>
        </div>
        <p style="padding: 10px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; font-size: 12px; margin-top: 10px;">
            <strong><?php esc_html_e('Tip:', 'mec-starter-addons'); ?></strong> 
            <?php esc_html_e('Select a "Preview Event" in the Content tab to see actual gallery images. Add images via the Event Gallery meta box when editing events.', 'mec-starter-addons'); ?>
        </p>
        <style>
        .mecas-gallery-wrapper {}
        .mecas-gallery-header { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        .mecas-gallery-header.mecas-has-line::after { content: ''; flex: 1; height: 1px; background-color: #E5E7EB; }
        .mecas-gallery-title { margin: 0; font-size: 24px; font-weight: 400; color: #1F2937; white-space: nowrap; }
        .mecas-gallery-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .mecas-gallery-item { position: relative; padding-top: 100%; background-size: cover; background-position: center; border-radius: 8px; }
        .mecas-gallery-placeholder { background-color: #E5E7EB !important; }
        </style>
        <?php
    }
}
