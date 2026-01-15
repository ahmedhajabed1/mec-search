<?php
if (!defined('ABSPATH')) exit;

class MECOM_Organizer_Name_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'mecom-organizer-name'; }
    public function get_title() { return __('Organizer Name', 'mec-organizer-manager'); }
    public function get_icon() { return 'eicon-heading'; }
    public function get_categories() { return ['mec-organizer-manager']; }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'mec-organizer-manager'),
        ]);
        
        $this->add_control('html_tag', [
            'label' => __('HTML Tag', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'h1',
            'options' => ['h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'p' => 'P', 'span' => 'Span'],
        ]);
        
        $this->add_control('preview_organizer_id', [
            'label' => __('Preview Organizer', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $this->get_organizers_list(),
        ]);
        
        $this->end_controls_section();

        // Wishlist Icon Section
        $this->start_controls_section('section_icon', [
            'label' => __('Wishlist Icon', 'mec-organizer-manager'),
        ]);
        
        $this->add_control('show_icon', [
            'label' => __('Show Icon', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('wishlist_icon', [
            'label' => __('Icon', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'far fa-heart',
                'library' => 'fa-regular',
            ],
            'recommended' => [
                'fa-solid' => ['heart', 'star', 'bookmark'],
                'fa-regular' => ['heart', 'star', 'bookmark'],
            ],
            'condition' => ['show_icon' => 'yes'],
        ]);
        
        $this->add_control('icon_position', [
            'label' => __('Icon Position', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'after',
            'options' => [
                'before' => __('Before Name', 'mec-organizer-manager'),
                'after' => __('After Name', 'mec-organizer-manager'),
            ],
            'condition' => ['show_icon' => 'yes'],
        ]);
        
        $this->end_controls_section();

        // Name Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Name', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('name_color', [
            'label' => __('Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => ['{{WRAPPER}} .mecom-org-name' => 'color: {{VALUE}};'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'name_typography',
            'selector' => '{{WRAPPER}} .mecom-org-name',
        ]);
        
        $this->add_responsive_control('alignment', [
            'label' => __('Alignment', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => __('Left', 'mec-organizer-manager'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'mec-organizer-manager'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => __('Right', 'mec-organizer-manager'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'left',
            'selectors' => ['{{WRAPPER}} .mecom-org-name-wrapper' => 'justify-content: {{VALUE}};'],
        ]);
        
        $this->end_controls_section();

        // Icon Style Section
        $this->start_controls_section('section_style_icon', [
            'label' => __('Icon', 'mec-organizer-manager'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_icon' => 'yes'],
        ]);
        
        $this->add_responsive_control('icon_size', [
            'label' => __('Icon Size', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range' => [
                'px' => ['min' => 10, 'max' => 100],
                'em' => ['min' => 0.5, 'max' => 5],
            ],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mecom-org-name-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .mecom-org-name-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_responsive_control('icon_spacing', [
            'label' => __('Spacing', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 10, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-name-wrapper' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);
        
        $this->add_control('icon_color', [
            'label' => __('Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1F2937',
            'selectors' => [
                '{{WRAPPER}} .mecom-org-name-icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mecom-org-name-icon svg' => 'fill: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('icon_color_hover', [
            'label' => __('Hover Color', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#E8927C',
            'selectors' => [
                '{{WRAPPER}} .mecom-org-name-icon:hover' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mecom-org-name-icon:hover svg' => 'fill: {{VALUE}};',
            ],
        ]);
        
        $this->add_responsive_control('icon_vertical_align', [
            'label' => __('Vertical Alignment', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => -20, 'max' => 20]],
            'default' => ['size' => 0, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mecom-org-name-icon' => 'margin-top: {{SIZE}}{{UNIT}};'],
        ]);
        
        $this->end_controls_section();
    }

    private function get_organizers_list() {
        $options = ['' => __('Current Organizer', 'mec-organizer-manager')];
        $organizers = get_terms(['taxonomy' => 'mec_organizer', 'hide_empty' => false]);
        if (!is_wp_error($organizers)) { foreach ($organizers as $o) { $options[$o->term_id] = $o->name; } }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $organizer = $this->get_current_organizer($settings);
        $name = $organizer ? $organizer['name'] : 'Jane Doe';
        $tag = $settings['html_tag'];
        $show_icon = $settings['show_icon'] === 'yes';
        $icon_position = $settings['icon_position'];
        ?>
        <div class="mecom-org-name-wrapper">
            <?php if ($show_icon && $icon_position === 'before'): ?>
            <span class="mecom-org-name-icon mecom-wishlist-icon" data-organizer-id="<?php echo $organizer ? $organizer['id'] : ''; ?>">
                <?php \Elementor\Icons_Manager::render_icon($settings['wishlist_icon'], ['aria-hidden' => 'true']); ?>
            </span>
            <?php endif; ?>
            
            <<?php echo $tag; ?> class="mecom-org-name"><?php echo esc_html($name); ?></<?php echo $tag; ?>>
            
            <?php if ($show_icon && $icon_position === 'after'): ?>
            <span class="mecom-org-name-icon mecom-wishlist-icon" data-organizer-id="<?php echo $organizer ? $organizer['id'] : ''; ?>">
                <?php \Elementor\Icons_Manager::render_icon($settings['wishlist_icon'], ['aria-hidden' => 'true']); ?>
            </span>
            <?php endif; ?>
        </div>
        <style>
        .mecom-org-name-wrapper {
            display: flex;
            align-items: center;
        }
        .mecom-org-name {
            margin: 0;
        }
        .mecom-org-name-icon {
            display: inline-flex;
            cursor: pointer;
            transition: color 0.3s ease, transform 0.2s ease;
        }
        .mecom-org-name-icon:hover {
            transform: scale(1.1);
        }
        </style>
        <?php
    }

    private function get_current_organizer($settings) {
        $organizer_id = !empty($settings['preview_organizer_id']) ? intval($settings['preview_organizer_id']) : (get_query_var('mecom_organizer_id') ?: (is_tax('mec_organizer') && ($t = get_queried_object()) ? $t->term_id : null));
        return $organizer_id ? mecom_get_organizer_data($organizer_id) : null;
    }

    protected function content_template() {
        ?>
        <#
        var tag = settings.html_tag;
        var showIcon = settings.show_icon === 'yes';
        var iconPosition = settings.icon_position;
        var iconHTML = '';
        if (showIcon && settings.wishlist_icon && settings.wishlist_icon.value) {
            iconHTML = elementor.helpers.renderIcon(view, settings.wishlist_icon, { 'aria-hidden': true }, 'i', 'object');
        }
        #>
        <div class="mecom-org-name-wrapper">
            <# if (showIcon && iconPosition === 'before' && iconHTML.rendered) { #>
            <span class="mecom-org-name-icon mecom-wishlist-icon">{{{ iconHTML.value }}}</span>
            <# } #>
            
            <{{{ tag }}} class="mecom-org-name">Jane Doe</{{{ tag }}}>
            
            <# if (showIcon && iconPosition === 'after' && iconHTML.rendered) { #>
            <span class="mecom-org-name-icon mecom-wishlist-icon">{{{ iconHTML.value }}}</span>
            <# } #>
        </div>
        <style>
        .mecom-org-name-wrapper { display: flex; align-items: center; gap: 10px; }
        .mecom-org-name { margin: 0; }
        .mecom-org-name-icon { display: inline-flex; cursor: pointer; }
        </style>
        <?php
    }
}
