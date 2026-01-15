<?php
/**
 * Event Description Widget - Shows event description with read more and tags
 */

if (!defined('ABSPATH')) exit;

class MECAS_Event_Description_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'mecas_event_description';
    }
    
    public function get_title() {
        return __('Event Description', 'mec-starter-addons');
    }
    
    public function get_icon() {
        return 'eicon-text';
    }
    
    public function get_categories() {
        return ['mec-starter-addons'];
    }
    
    public function get_keywords() {
        return ['event', 'description', 'content', 'tags', 'read more'];
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
                'default' => __('Description', 'mec-starter-addons'),
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
        
        $this->add_control(
            'excerpt_length',
            [
                'label' => __('Excerpt Length (words)', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 50,
                'min' => 10,
                'max' => 500,
                'description' => __('Number of words to show before "Read more"', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'read_more_text',
            [
                'label' => __('Read More Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Read more', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'read_less_text',
            [
                'label' => __('Read Less Text', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Read less', 'mec-starter-addons'),
            ]
        );
        
        $this->add_control(
            'show_tags',
            [
                'label' => __('Show Tags', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'preview_event_id',
            [
                'label' => __('Preview Event', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_events_list(),
                'description' => __('Select an event for preview.', 'mec-starter-addons'),
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
                'condition' => ['show_title' => 'yes'],
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecas-desc-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mecas-desc-title',
            ]
        );
        
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __('Margin', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-desc-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .mecas-desc-header.mecas-has-line::after' => 'background-color: {{VALUE}};',
                ],
                'condition' => ['show_line' => 'yes'],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Description
        $this->start_controls_section(
            'section_style_description',
            [
                'label' => __('Description', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'description_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4B5563',
                'selectors' => [
                    '{{WRAPPER}} .mecas-desc-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .mecas-desc-content',
            ]
        );
        
        $this->add_responsive_control(
            'description_margin',
            [
                'label' => __('Margin', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-desc-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Read More
        $this->start_controls_section(
            'section_style_read_more',
            [
                'label' => __('Read More Link', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'read_more_color',
            [
                'label' => __('Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1F2937',
                'selectors' => [
                    '{{WRAPPER}} .mecas-read-more' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'read_more_hover_color',
            [
                'label' => __('Hover Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mecas-read-more:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'read_more_typography',
                'selector' => '{{WRAPPER}} .mecas-read-more',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Tags
        $this->start_controls_section(
            'section_style_tags',
            [
                'label' => __('Tags', 'mec-starter-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_tags' => 'yes'],
            ]
        );
        
        $this->add_control(
            'tags_text_color',
            [
                'label' => __('Text Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .mecas-tag' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'tags_bg_color',
            [
                'label' => __('Background Color', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F3F4F6',
                'selectors' => [
                    '{{WRAPPER}} .mecas-tag' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'tags_hover_bg_color',
            [
                'label' => __('Hover Background', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#D1FAE5',
                'selectors' => [
                    '{{WRAPPER}} .mecas-tag:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tags_typography',
                'selector' => '{{WRAPPER}} .mecas-tag',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tags_border',
                'selector' => '{{WRAPPER}} .mecas-tag',
            ]
        );
        
        $this->add_responsive_control(
            'tags_border_radius',
            [
                'label' => __('Border Radius', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '20',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecas-tag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'tags_padding',
            [
                'label' => __('Padding', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '6',
                    'right' => '16',
                    'bottom' => '6',
                    'left' => '16',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecas-tag' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'tags_gap',
            [
                'label' => __('Gap', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 8, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mecas-tags' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'tags_margin',
            [
                'label' => __('Section Margin', 'mec-starter-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '20',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecas-tags' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        
        // Get event ID
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
        
        // Get event content
        $event = get_post($event_id);
        $full_content = apply_filters('the_content', $event->post_content);
        $full_content_stripped = wp_strip_all_tags($event->post_content);
        $words = explode(' ', $full_content_stripped);
        $excerpt_length = intval($settings['excerpt_length']);
        
        $needs_truncation = count($words) > $excerpt_length;
        $excerpt = $needs_truncation ? implode(' ', array_slice($words, 0, $excerpt_length)) . '...' : $full_content_stripped;
        
        // Get tags
        $tags = [];
        if ($settings['show_tags'] === 'yes') {
            // Get MEC labels (tags)
            $labels = wp_get_post_terms($event_id, 'mec_label');
            if (!empty($labels) && !is_wp_error($labels)) {
                $tags = array_merge($tags, $labels);
            }
            // Also get regular post tags if assigned
            $post_tags = wp_get_post_terms($event_id, 'post_tag');
            if (!empty($post_tags) && !is_wp_error($post_tags)) {
                $tags = array_merge($tags, $post_tags);
            }
            // Get MEC categories as well
            $categories = wp_get_post_terms($event_id, 'mec_category');
            if (!empty($categories) && !is_wp_error($categories)) {
                $tags = array_merge($tags, $categories);
            }
        }
        
        $show_line_class = $settings['show_line'] === 'yes' ? 'mecas-has-line' : '';
        $unique_id = 'mecas-desc-' . $this->get_id();
        
        ?>
        <div class="mecas-desc-wrapper" id="<?php echo esc_attr($unique_id); ?>">
            <?php if ($settings['show_title'] === 'yes'): ?>
            <div class="mecas-desc-header <?php echo esc_attr($show_line_class); ?>">
                <h3 class="mecas-desc-title"><?php echo esc_html($settings['title_text']); ?></h3>
            </div>
            <?php endif; ?>
            
            <div class="mecas-desc-content">
                <div class="mecas-desc-excerpt"><?php echo esc_html($excerpt); ?></div>
                <?php if ($needs_truncation): ?>
                <div class="mecas-desc-full" style="display: none;"><?php echo $full_content; ?></div>
                <?php endif; ?>
            </div>
            
            <?php if ($needs_truncation): ?>
            <a href="#" class="mecas-read-more" data-more="<?php echo esc_attr($settings['read_more_text']); ?>" data-less="<?php echo esc_attr($settings['read_less_text']); ?>">
                <?php echo esc_html($settings['read_more_text']); ?>
            </a>
            <?php endif; ?>
            
            <?php if ($settings['show_tags'] === 'yes' && !empty($tags)): ?>
            <div class="mecas-tags">
                <?php foreach ($tags as $tag): ?>
                <span class="mecas-tag"><?php echo esc_html($tag->name); ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <style>
        .mecas-desc-wrapper {}
        .mecas-desc-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .mecas-desc-header.mecas-has-line::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #E5E7EB;
        }
        .mecas-desc-title {
            margin: 0;
            font-size: 24px;
            font-weight: 400;
            color: #1F2937;
            white-space: nowrap;
        }
        .mecas-desc-content {
            line-height: 1.8;
        }
        .mecas-read-more {
            display: inline-block;
            margin-top: 10px;
            text-decoration: underline;
            cursor: pointer;
            font-weight: 500;
        }
        .mecas-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 20px;
        }
        .mecas-tag {
            display: inline-block;
            padding: 6px 16px;
            font-size: 13px;
            border-radius: 20px;
            background: #F3F4F6;
            color: #374151;
            transition: background 0.2s ease;
        }
        .mecas-tag:hover {
            background: #D1FAE5;
        }
        </style>
        
        <script>
        (function($) {
            $(document).ready(function() {
                $('#<?php echo esc_js($unique_id); ?> .mecas-read-more').on('click', function(e) {
                    e.preventDefault();
                    var $wrapper = $(this).closest('.mecas-desc-wrapper');
                    var $excerpt = $wrapper.find('.mecas-desc-excerpt');
                    var $full = $wrapper.find('.mecas-desc-full');
                    var isExpanded = $full.is(':visible');
                    
                    if (isExpanded) {
                        $full.hide();
                        $excerpt.show();
                        $(this).text($(this).data('more'));
                    } else {
                        $excerpt.hide();
                        $full.show();
                        $(this).text($(this).data('less'));
                    }
                });
            });
        })(jQuery);
        </script>
        <?php
    }
    
    private function render_editor_placeholder($settings) {
        $show_line_class = $settings['show_line'] === 'yes' ? 'mecas-has-line' : '';
        $sample_text = "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose";
        
        $words = explode(' ', $sample_text);
        $excerpt_length = intval($settings['excerpt_length']);
        $excerpt = implode(' ', array_slice($words, 0, $excerpt_length)) . '...';
        
        ?>
        <div class="mecas-desc-wrapper">
            <?php if ($settings['show_title'] === 'yes'): ?>
            <div class="mecas-desc-header <?php echo esc_attr($show_line_class); ?>">
                <h3 class="mecas-desc-title"><?php echo esc_html($settings['title_text']); ?></h3>
            </div>
            <?php endif; ?>
            
            <div class="mecas-desc-content">
                <div class="mecas-desc-excerpt"><?php echo esc_html($excerpt); ?></div>
            </div>
            
            <a href="#" class="mecas-read-more"><?php echo esc_html($settings['read_more_text']); ?></a>
            
            <?php if ($settings['show_tags'] === 'yes'): ?>
            <div class="mecas-tags">
                <span class="mecas-tag"><?php _e('Social Play', 'mec-starter-addons'); ?></span>
                <span class="mecas-tag"><?php _e('Beginner', 'mec-starter-addons'); ?></span>
                <span class="mecas-tag"><?php _e('Social Play', 'mec-starter-addons'); ?></span>
                <span class="mecas-tag"><?php _e('Beginner', 'mec-starter-addons'); ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <p style="padding: 10px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; font-size: 12px; margin-top: 15px;">
            <strong><?php esc_html_e('Tip:', 'mec-starter-addons'); ?></strong> 
            <?php esc_html_e('Select a "Preview Event" in the Content tab to see actual event data.', 'mec-starter-addons'); ?>
        </p>
        
        <style>
        .mecas-desc-wrapper {}
        .mecas-desc-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .mecas-desc-header.mecas-has-line::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #E5E7EB;
        }
        .mecas-desc-title {
            margin: 0;
            font-size: 24px;
            font-weight: 400;
            color: #1F2937;
            white-space: nowrap;
        }
        .mecas-desc-content {
            line-height: 1.8;
        }
        .mecas-read-more {
            display: inline-block;
            margin-top: 10px;
            text-decoration: underline;
            cursor: pointer;
            font-weight: 500;
        }
        .mecas-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 20px;
        }
        .mecas-tag {
            display: inline-block;
            padding: 6px 16px;
            font-size: 13px;
            border-radius: 20px;
            background: #F3F4F6;
            color: #374151;
        }
        </style>
        <?php
    }
}
