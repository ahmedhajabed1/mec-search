<?php
/**
 * Elementor Widget: Host Registration Form
 * Multi-step registration with full customization
 */

if (!defined('ABSPATH')) exit;

class MECOM_Host_Registration_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mecom-host-registration';
    }

    public function get_title() {
        return __('Host Registration Form', 'mec-organizer-manager');
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_categories() {
        return ['mec-organizer-manager'];
    }

    public function get_keywords() {
        return ['registration', 'signup', 'host', 'organizer', 'form', 'multi-step'];
    }

    public function get_script_depends() {
        return ['mecom-registration'];
    }

    public function get_style_depends() {
        return ['mecom-registration'];
    }

    protected function register_controls() {
        
        // ========================================
        // CONTENT TAB
        // ========================================
        
        // Header Section
        $this->start_controls_section(
            'section_header',
            [
                'label' => __('Header', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_accent_bar',
            [
                'label' => __('Show Top Accent Bar', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-organizer-manager'),
                'label_off' => __('No', 'mec-organizer-manager'),
                'default' => '',
            ]
        );

        $this->add_control(
            'show_close_button',
            [
                'label' => __('Show Close (X) Button', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-organizer-manager'),
                'label_off' => __('No', 'mec-organizer-manager'),
                'default' => '',
            ]
        );

        $this->add_control(
            'show_logo',
            [
                'label' => __('Show Logo', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-organizer-manager'),
                'label_off' => __('No', 'mec-organizer-manager'),
                'default' => '',
            ]
        );

        $this->add_control(
            'show_step_titles',
            [
                'label' => __('Show Step Titles', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-organizer-manager'),
                'label_off' => __('No', 'mec-organizer-manager'),
                'default' => '',
                'description' => __('Show titles like "Request a Host Account"', 'mec-organizer-manager'),
            ]
        );

        $this->add_control(
            'logo_source',
            [
                'label' => __('Logo Source', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'site',
                'options' => [
                    'site' => __('Site Logo', 'mec-organizer-manager'),
                    'custom' => __('Custom Image', 'mec-organizer-manager'),
                ],
                'condition' => [
                    'show_logo' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'custom_logo',
            [
                'label' => __('Custom Logo', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
                'condition' => [
                    'show_logo' => 'yes',
                    'logo_source' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_width',
            [
                'label' => __('Logo Width', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 40,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecom-modal-logo img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_logo' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Steps Configuration
        $this->start_controls_section(
            'section_steps',
            [
                'label' => __('Steps', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_phone_verification',
            [
                'label' => __('Show Phone Verification Step', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-organizer-manager'),
                'label_off' => __('No', 'mec-organizer-manager'),
                'default' => '',
                'description' => __('Enable when you have SMS verification API integrated', 'mec-organizer-manager'),
            ]
        );

        $this->add_control(
            'show_business_step',
            [
                'label' => __('Show Business Info Step', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-organizer-manager'),
                'label_off' => __('No', 'mec-organizer-manager'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_profile_step',
            [
                'label' => __('Show Profile Setup Step', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-organizer-manager'),
                'label_off' => __('No', 'mec-organizer-manager'),
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Step 1 Content
        $this->start_controls_section(
            'section_step1_content',
            [
                'label' => __('Step 1: Account Info', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'step1_title',
            [
                'label' => __('Title', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Request a Host Account', 'mec-organizer-manager'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'name_label',
            [
                'label' => __('Name Label', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Your name', 'mec-organizer-manager'),
            ]
        );

        $this->add_control(
            'name_placeholder',
            [
                'label' => __('Name Placeholder', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Jane Doe', 'mec-organizer-manager'),
            ]
        );

        $this->add_control(
            'name_hint',
            [
                'label' => __('Name Hint', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Your name will be public on your Mahj Hub profile', 'mec-organizer-manager'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'email_label',
            [
                'label' => __('Email Label', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Email address', 'mec-organizer-manager'),
            ]
        );

        $this->add_control(
            'email_hint',
            [
                'label' => __('Email Hint', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __("We'll use your email address to send you updates", 'mec-organizer-manager'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'phone_label',
            [
                'label' => __('Phone Label', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Phone number', 'mec-organizer-manager'),
            ]
        );

        $this->add_control(
            'phone_hint',
            [
                'label' => __('Phone Hint', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __("We'll use your phone number to send you updates and verify your account", 'mec-organizer-manager'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'location_label',
            [
                'label' => __('Location Label', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Location', 'mec-organizer-manager'),
            ]
        );

        $this->add_control(
            'location_placeholder',
            [
                'label' => __('Location Placeholder', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('City, State', 'mec-organizer-manager'),
            ]
        );

        $this->add_control(
            'password_label',
            [
                'label' => __('Password Label', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Password', 'mec-organizer-manager'),
            ]
        );

        $this->end_controls_section();

        // Step 3 Content (Business)
        $this->start_controls_section(
            'section_step3_content',
            [
                'label' => __('Step 3: Business Info', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'show_business_step' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'step3_title',
            [
                'label' => __('Title', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Enter your Business Info', 'mec-organizer-manager'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'step3_note',
            [
                'label' => __('Optional Note', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('If you do not have a business registered, you can leave the following blank and continue', 'mec-organizer-manager'),
                'rows' => 2,
            ]
        );

        $this->end_controls_section();

        // Step 4 Content (Profile)
        $this->start_controls_section(
            'section_step4_content',
            [
                'label' => __('Step 4: Profile Setup', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'show_profile_step' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'step4_title',
            [
                'label' => __('Title', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Setup your profile page', 'mec-organizer-manager'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'fun_facts_label',
            [
                'label' => __('Fun Facts Label', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('What are some fun facts about you?', 'mec-organizer-manager'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'fun_facts_max',
            [
                'label' => __('Fun Facts Max Words', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 250,
                'min' => 50,
                'max' => 1000,
            ]
        );

        $this->add_control(
            'description_label',
            [
                'label' => __('Description Label', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Short description about you', 'mec-organizer-manager'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description_max',
            [
                'label' => __('Description Max Words', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 750,
                'min' => 100,
                'max' => 2000,
            ]
        );

        $this->add_control(
            'business_help_label',
            [
                'label' => __('Business Help Question', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Do you need help with setting up a business entity?', 'mec-organizer-manager'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // Step 5 Content (Thank You)
        $this->start_controls_section(
            'section_step5_content',
            [
                'label' => __('Step 5: Thank You', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'step5_title',
            [
                'label' => __('Title', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Thank you!', 'mec-organizer-manager'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'step5_message',
            [
                'label' => __('Message', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __("Thanks for signing up as a Host,\nwe will review your information", 'mec-organizer-manager'),
                'rows' => 3,
            ]
        );

        $this->add_control(
            'step5_note',
            [
                'label' => __('Note', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('You will receive an email from us when your account is verified.', 'mec-organizer-manager'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'redirect_url',
            [
                'label' => __('Redirect URL (after Continue)', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => home_url(),
                'default' => [
                    'url' => '',
                ],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // Buttons
        $this->start_controls_section(
            'section_buttons',
            [
                'label' => __('Buttons', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'cancel_text',
            [
                'label' => __('Cancel Button Text', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Cancel', 'mec-organizer-manager'),
            ]
        );

        $this->add_control(
            'continue_text',
            [
                'label' => __('Continue Button Text', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Continue', 'mec-organizer-manager'),
            ]
        );

        $this->add_control(
            'back_text',
            [
                'label' => __('Back Button Text', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Back', 'mec-organizer-manager'),
            ]
        );

        $this->add_control(
            'finish_text',
            [
                'label' => __('Finish Button Text', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Finish', 'mec-organizer-manager'),
            ]
        );

        $this->end_controls_section();

        // ========================================
        // STYLE TAB
        // ========================================

        // Container Style
        $this->start_controls_section(
            'section_container_style',
            [
                'label' => __('Container', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'container_background',
            [
                'label' => __('Background Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecom-host-registration-modal' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'accent_bar_color',
            [
                'label' => __('Top Accent Bar Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3D4F5F',
                'selectors' => [
                    '{{WRAPPER}} .mecom-host-registration-modal::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Padding', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '0',
                    'right' => '50',
                    'bottom' => '40',
                    'left' => '50',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecom-form-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecom-host-registration-modal' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'selector' => '{{WRAPPER}} .mecom-host-registration-modal',
            ]
        );

        $this->add_responsive_control(
            'container_max_width',
            [
                'label' => __('Max Width', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 300,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 50,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 700,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecom-host-registration-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Title Style
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __('Title', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mecom-form-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mecom-form-title',
            ]
        );

        $this->add_control(
            'title_line_color',
            [
                'label' => __('Decorative Line Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#dddddd',
                'selectors' => [
                    '{{WRAPPER}} .mecom-form-title::before, {{WRAPPER}} .mecom-form-title::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'show_title_lines',
            [
                'label' => __('Show Decorative Lines', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mec-organizer-manager'),
                'label_off' => __('No', 'mec-organizer-manager'),
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Labels Style
        $this->start_controls_section(
            'section_labels_style',
            [
                'label' => __('Labels', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __('Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mecom-form-group label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .mecom-form-group label',
            ]
        );

        $this->add_control(
            'hint_color',
            [
                'label' => __('Hint Text Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#888888',
                'selectors' => [
                    '{{WRAPPER}} .mecom-field-hint' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Inputs Style
        $this->start_controls_section(
            'section_inputs_style',
            [
                'label' => __('Inputs', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'input_background',
            [
                'label' => __('Background Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecom-form-group input, {{WRAPPER}} .mecom-form-group select, {{WRAPPER}} .mecom-form-group textarea' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label' => __('Text Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mecom-form-group input, {{WRAPPER}} .mecom-form-group select, {{WRAPPER}} .mecom-form-group textarea' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label' => __('Placeholder Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#999999',
                'selectors' => [
                    '{{WRAPPER}} .mecom-form-group input::placeholder, {{WRAPPER}} .mecom-form-group textarea::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label' => __('Border Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#dddddd',
                'selectors' => [
                    '{{WRAPPER}} .mecom-form-group input, {{WRAPPER}} .mecom-form-group select, {{WRAPPER}} .mecom-form-group textarea' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_focus_border_color',
            [
                'label' => __('Focus Border Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3D4F5F',
                'selectors' => [
                    '{{WRAPPER}} .mecom-form-group input:focus, {{WRAPPER}} .mecom-form-group select:focus, {{WRAPPER}} .mecom-form-group textarea:focus' => 'border-color: {{VALUE}}; box-shadow: 0 0 0 3px {{VALUE}}20;',
                ],
            ]
        );

        $this->add_control(
            'input_border_radius',
            [
                'label' => __('Border Radius', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecom-form-group input, {{WRAPPER}} .mecom-form-group select, {{WRAPPER}} .mecom-form-group textarea' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label' => __('Padding', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '14',
                    'right' => '16',
                    'bottom' => '14',
                    'left' => '16',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mecom-form-group input, {{WRAPPER}} .mecom-form-group select, {{WRAPPER}} .mecom-form-group textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Buttons Style
        $this->start_controls_section(
            'section_buttons_style',
            [
                'label' => __('Buttons', 'mec-organizer-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_primary_button',
            [
                'label' => __('Primary Button (Continue/Finish)', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'primary_btn_background',
            [
                'label' => __('Background Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3D4F5F',
                'selectors' => [
                    '{{WRAPPER}} .mecom-btn-primary' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'primary_btn_text_color',
            [
                'label' => __('Text Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecom-btn-primary' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'primary_btn_hover_background',
            [
                'label' => __('Hover Background', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2d3d4a',
                'selectors' => [
                    '{{WRAPPER}} .mecom-btn-primary:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_secondary_button',
            [
                'label' => __('Secondary Button (Cancel/Back)', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'secondary_btn_background',
            [
                'label' => __('Background Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mecom-btn-secondary' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_btn_text_color',
            [
                'label' => __('Text Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mecom-btn-secondary' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_btn_border_color',
            [
                'label' => __('Border Color', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#dddddd',
                'selectors' => [
                    '{{WRAPPER}} .mecom-btn-secondary' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_border_radius',
            [
                'label' => __('Border Radius', 'mec-organizer-manager'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .mecom-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .mecom-btn',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Check if registration is enabled
        if (get_option('mecom_registration_enabled', 'yes') !== 'yes') {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="mecom-editor-notice">Registration is disabled in settings</div>';
            }
            return;
        }
        
        // Check if already logged in
        if (is_user_logged_in() && !(\Elementor\Plugin::$instance->editor->is_edit_mode())) {
            $user_id = get_current_user_id();
            $organizer_id = get_user_meta($user_id, 'mecom_linked_organizer_id', true);
            
            if ($organizer_id) {
                echo '<div class="mecom-already-registered">';
                echo '<p>' . sprintf(
                    __('You already have a host account. <a href="%s">Go to Dashboard</a>', 'mec-organizer-manager'),
                    admin_url('edit.php?post_type=mec-events')
                ) . '</p>';
                echo '</div>';
                return;
            }
        }
        
        // Get logo URL
        $logo_url = '';
        if ($settings['show_logo'] === 'yes') {
            if ($settings['logo_source'] === 'custom' && !empty($settings['custom_logo']['url'])) {
                $logo_url = $settings['custom_logo']['url'];
            } else {
                $custom_logo_id = get_theme_mod('custom_logo');
                if ($custom_logo_id) {
                    $logo_url = wp_get_attachment_image_url($custom_logo_id, 'medium');
                }
            }
        }
        
        // Determine step flow based on settings
        $show_phone_verification = $settings['show_phone_verification'] === 'yes';
        $show_business_step = $settings['show_business_step'] === 'yes';
        $show_profile_step = $settings['show_profile_step'] === 'yes';
        
        // Calculate next steps
        $step1_next = $show_phone_verification ? 2 : ($show_business_step ? 3 : ($show_profile_step ? 4 : 5));
        $step2_next = $show_business_step ? 3 : ($show_profile_step ? 4 : 5);
        $step3_next = $show_profile_step ? 4 : 5;
        $step3_prev = $show_phone_verification ? 2 : 1;
        $step4_prev = $show_business_step ? 3 : ($show_phone_verification ? 2 : 1);
        
        // Redirect URL
        $redirect_url = !empty($settings['redirect_url']['url']) ? $settings['redirect_url']['url'] : home_url();
        
        // reCAPTCHA
        $recaptcha_enabled = get_option('mecom_recaptcha_enabled', 'no') === 'yes';
        $recaptcha_site_key = get_option('mecom_recaptcha_site_key', '');
        
        // Hide title lines if disabled
        $title_class = $settings['show_title_lines'] !== 'yes' ? 'mecom-no-lines' : '';
        $show_titles = $settings['show_step_titles'] === 'yes';
        $show_accent_bar = $settings['show_accent_bar'] === 'yes';
        ?>
        
        <div class="mecom-host-registration-wrapper">
            <div class="mecom-host-registration-modal <?php echo !$show_accent_bar ? 'mecom-no-accent-bar' : ''; ?>">
                
                <?php if ($settings['show_close_button'] === 'yes'): ?>
                <button type="button" class="mecom-modal-close" aria-label="<?php esc_attr_e('Close', 'mec-organizer-manager'); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M15 9l-6 6M9 9l6 6"/>
                    </svg>
                </button>
                <?php endif; ?>

                <?php if ($logo_url): ?>
                <div class="mecom-modal-logo">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                </div>
                <?php endif; ?>

                <form id="mecom-host-registration-form" class="mecom-host-form" method="post"
                      data-show-phone="<?php echo $show_phone_verification ? 'true' : 'false'; ?>"
                      data-show-business="<?php echo $show_business_step ? 'true' : 'false'; ?>"
                      data-show-profile="<?php echo $show_profile_step ? 'true' : 'false'; ?>"
                      data-fun-facts-max="<?php echo esc_attr($settings['fun_facts_max']); ?>"
                      data-description-max="<?php echo esc_attr($settings['description_max']); ?>">
                    
                    <?php wp_nonce_field('mecom_registration_nonce', 'mecom_reg_nonce'); ?>
                    <input type="hidden" name="action" value="mecom_register_host">

                    <!-- Step 1: Account Info -->
                    <div class="mecom-form-step active" data-step="1">
                        <?php if ($show_titles): ?>
                        <h2 class="mecom-form-title <?php echo esc_attr($title_class); ?>"><?php echo esc_html($settings['step1_title']); ?></h2>
                        <?php endif; ?>
                        
                        <p class="mecom-required-note">*<?php _e('required', 'mec-organizer-manager'); ?></p>
                        
                        <div class="mecom-form-group">
                            <label for="mecom_name"><?php echo esc_html($settings['name_label']); ?>*</label>
                            <input type="text" id="mecom_name" name="name" placeholder="<?php echo esc_attr($settings['name_placeholder']); ?>" required>
                            <?php if ($settings['name_hint']): ?>
                            <span class="mecom-field-hint"><?php echo esc_html($settings['name_hint']); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mecom-form-group">
                            <label for="mecom_email"><?php echo esc_html($settings['email_label']); ?>*</label>
                            <input type="email" id="mecom_email" name="email" placeholder="example@email.com" required>
                            <?php if ($settings['email_hint']): ?>
                            <span class="mecom-field-hint"><?php echo esc_html($settings['email_hint']); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mecom-form-group">
                            <label for="mecom_phone"><?php echo esc_html($settings['phone_label']); ?>*</label>
                            <div class="mecom-phone-input-wrapper">
                                <select id="mecom_phone_country" name="phone_country" class="mecom-phone-country">
                                    <option value="+1" selected>+1</option>
                                    <option value="+44">+44</option>
                                    <option value="+61">+61</option>
                                    <option value="+49">+49</option>
                                    <option value="+33">+33</option>
                                    <option value="+81">+81</option>
                                    <option value="+86">+86</option>
                                    <option value="+91">+91</option>
                                </select>
                                <input type="tel" id="mecom_phone" name="phone" placeholder="740 123 1234" required>
                            </div>
                            <?php if ($settings['phone_hint']): ?>
                            <span class="mecom-field-hint"><?php echo esc_html($settings['phone_hint']); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mecom-form-group">
                            <label for="mecom_location"><?php echo esc_html($settings['location_label']); ?>*</label>
                            <input type="text" id="mecom_location" name="location" placeholder="<?php echo esc_attr($settings['location_placeholder']); ?>" required>
                        </div>
                        
                        <div class="mecom-form-group">
                            <label for="mecom_password"><?php echo esc_html($settings['password_label']); ?>*</label>
                            <div class="mecom-password-wrapper">
                                <input type="password" id="mecom_password" name="password" placeholder="<?php esc_attr_e('Enter password', 'mec-organizer-manager'); ?>" required minlength="8">
                                <button type="button" class="mecom-password-toggle" aria-label="<?php esc_attr_e('Show password', 'mec-organizer-manager'); ?>">
                                    <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <?php if ($recaptcha_enabled && $recaptcha_site_key): ?>
                        <div class="mecom-form-group mecom-recaptcha-group">
                            <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($recaptcha_site_key); ?>"></div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mecom-form-buttons">
                            <button type="button" class="mecom-btn mecom-btn-secondary mecom-btn-cancel"><?php echo esc_html($settings['cancel_text']); ?></button>
                            <button type="button" class="mecom-btn mecom-btn-primary mecom-btn-next" data-next="<?php echo $step1_next; ?>"><?php echo esc_html($settings['continue_text']); ?></button>
                        </div>
                    </div>

                    <?php if ($show_phone_verification): ?>
                    <!-- Step 2: Phone Verification -->
                    <div class="mecom-form-step" data-step="2" style="display:none;">
                        <?php if ($show_titles): ?>
                        <h2 class="mecom-form-title <?php echo esc_attr($title_class); ?>"><?php _e('Verify your phone number', 'mec-organizer-manager'); ?></h2>
                        <?php endif; ?>
                        
                        <p class="mecom-verify-message"><?php _e("We've sent a verification code to your phone number", 'mec-organizer-manager'); ?></p>
                        <p class="mecom-verify-phone"><span id="mecom-display-phone">+1 123-1234-123</span></p>
                        
                        <div class="mecom-verification-code">
                            <input type="text" maxlength="1" class="mecom-code-input" data-index="0" inputmode="numeric" pattern="[0-9]">
                            <input type="text" maxlength="1" class="mecom-code-input" data-index="1" inputmode="numeric" pattern="[0-9]">
                            <input type="text" maxlength="1" class="mecom-code-input" data-index="2" inputmode="numeric" pattern="[0-9]">
                            <input type="text" maxlength="1" class="mecom-code-input" data-index="3" inputmode="numeric" pattern="[0-9]">
                        </div>
                        
                        <p class="mecom-wrong-number"><?php _e('Wrong number?', 'mec-organizer-manager'); ?> <a href="#" class="mecom-change-phone"><?php _e('Change', 'mec-organizer-manager'); ?></a></p>
                        
                        <p class="mecom-resend-timer"><?php _e('Resend in', 'mec-organizer-manager'); ?> (<span id="mecom-resend-countdown">60</span>)</p>
                        <p class="mecom-resend-link" style="text-align:center;"><a href="#" class="mecom-resend-code"><?php _e('Resend Code', 'mec-organizer-manager'); ?></a></p>
                        
                        <div class="mecom-form-buttons mecom-single-button">
                            <button type="button" class="mecom-btn mecom-btn-primary mecom-btn-verify"><?php _e('Verify', 'mec-organizer-manager'); ?></button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($show_business_step): ?>
                    <!-- Step 3: Business Info -->
                    <div class="mecom-form-step" data-step="3" style="display:none;">
                        <?php if ($show_titles): ?>
                        <h2 class="mecom-form-title <?php echo esc_attr($title_class); ?>"><?php echo esc_html($settings['step3_title']); ?></h2>
                        <?php endif; ?>
                        
                        <?php if ($settings['step3_note']): ?>
                        <p class="mecom-optional-note"><?php echo esc_html($settings['step3_note']); ?></p>
                        <?php endif; ?>
                        
                        <div class="mecom-form-group">
                            <label for="mecom_business_name"><?php _e('Business name', 'mec-organizer-manager'); ?></label>
                            <input type="text" id="mecom_business_name" name="business_name" placeholder="<?php esc_attr_e('Your Business Name', 'mec-organizer-manager'); ?>">
                        </div>
                        
                        <div class="mecom-form-group">
                            <label for="mecom_business_address"><?php _e('Registered Address', 'mec-organizer-manager'); ?></label>
                            <input type="text" id="mecom_business_address" name="business_address" placeholder="<?php esc_attr_e('123 Main St, City, State 12345', 'mec-organizer-manager'); ?>">
                        </div>
                        
                        <div class="mecom-form-group">
                            <label for="mecom_business_ein"><?php _e('Business Registration Number or EIN', 'mec-organizer-manager'); ?></label>
                            <input type="text" id="mecom_business_ein" name="business_ein" placeholder="<?php esc_attr_e('XX-XXXXXXX', 'mec-organizer-manager'); ?>">
                        </div>
                        
                        <div class="mecom-form-buttons">
                            <button type="button" class="mecom-btn mecom-btn-secondary mecom-btn-prev" data-prev="<?php echo $step3_prev; ?>"><?php echo esc_html($settings['cancel_text']); ?></button>
                            <button type="button" class="mecom-btn mecom-btn-primary mecom-btn-next" data-next="<?php echo $step3_next; ?>"><?php echo esc_html($settings['continue_text']); ?></button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($show_profile_step): ?>
                    <!-- Step 4: Profile Setup -->
                    <div class="mecom-form-step" data-step="4" style="display:none;">
                        <?php if ($show_titles): ?>
                        <h2 class="mecom-form-title <?php echo esc_attr($title_class); ?>"><?php echo esc_html($settings['step4_title']); ?></h2>
                        <?php endif; ?>
                        
                        <div class="mecom-form-group">
                            <label for="mecom_website"><?php _e('Website Link', 'mec-organizer-manager'); ?></label>
                            <input type="url" id="mecom_website" name="website" placeholder="<?php esc_attr_e('https://yourwebsite.com', 'mec-organizer-manager'); ?>">
                        </div>
                        
                        <div class="mecom-form-group">
                            <label><?php _e('Social Media links', 'mec-organizer-manager'); ?></label>
                            <div class="mecom-social-links-wrapper">
                                <div class="mecom-social-link-row">
                                    <input type="url" name="social_links[]" placeholder="<?php esc_attr_e('www.instagram.com/', 'mec-organizer-manager'); ?>">
                                </div>
                            </div>
                            <button type="button" class="mecom-add-social-btn">
                                <?php _e('Add more', 'mec-organizer-manager'); ?> 
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="mecom-form-group">
                            <label for="mecom_fun_facts"><?php echo esc_html($settings['fun_facts_label']); ?></label>
                            <textarea id="mecom_fun_facts" name="fun_facts" rows="4" placeholder="<?php printf(esc_attr__('max %d words', 'mec-organizer-manager'), $settings['fun_facts_max']); ?>"></textarea>
                            <span class="mecom-char-count"><span id="fun-facts-count">0</span>/<?php echo esc_html($settings['fun_facts_max']); ?> <?php _e('words', 'mec-organizer-manager'); ?></span>
                        </div>
                        
                        <div class="mecom-form-group">
                            <label for="mecom_description"><?php echo esc_html($settings['description_label']); ?></label>
                            <textarea id="mecom_description" name="description" rows="5" placeholder="<?php printf(esc_attr__('max %d words', 'mec-organizer-manager'), $settings['description_max']); ?>"></textarea>
                            <span class="mecom-char-count"><span id="description-count">0</span>/<?php echo esc_html($settings['description_max']); ?> <?php _e('words', 'mec-organizer-manager'); ?></span>
                        </div>
                        
                        <div class="mecom-form-group">
                            <label for="mecom_need_business_help"><?php echo esc_html($settings['business_help_label']); ?></label>
                            <select id="mecom_need_business_help" name="need_business_help">
                                <option value="no"><?php _e('No', 'mec-organizer-manager'); ?></option>
                                <option value="yes"><?php _e('Yes', 'mec-organizer-manager'); ?></option>
                            </select>
                        </div>
                        
                        <div class="mecom-form-buttons">
                            <button type="button" class="mecom-btn mecom-btn-secondary mecom-btn-prev" data-prev="<?php echo $step4_prev; ?>"><?php echo esc_html($settings['back_text']); ?></button>
                            <button type="button" class="mecom-btn mecom-btn-primary mecom-btn-submit"><?php echo esc_html($settings['finish_text']); ?></button>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- If no profile step, submit on step 3 or 1 -->
                    <?php endif; ?>

                    <!-- Step 5: Thank You -->
                    <div class="mecom-form-step mecom-thank-you-step" data-step="5" style="display:none;">
                        <?php if ($show_titles): ?>
                        <h2 class="mecom-form-title <?php echo esc_attr($title_class); ?>"><?php echo esc_html($settings['step5_title']); ?></h2>
                        <?php endif; ?>
                        
                        <div class="mecom-thank-you-content">
                            <p class="mecom-thank-you-message"><?php echo nl2br(esc_html($settings['step5_message'])); ?></p>
                            <p class="mecom-thank-you-note"><?php echo esc_html($settings['step5_note']); ?></p>
                        </div>
                        
                        <div class="mecom-form-buttons mecom-single-button">
                            <a href="<?php echo esc_url($redirect_url); ?>" class="mecom-btn mecom-btn-primary"><?php echo esc_html($settings['continue_text']); ?></a>
                        </div>
                    </div>

                    <!-- Loading overlay -->
                    <div class="mecom-form-loading" style="display:none;">
                        <div class="mecom-spinner"></div>
                        <p><?php _e('Submitting...', 'mec-organizer-manager'); ?></p>
                    </div>
                </form>
            </div>
        </div>
        
        <script>
        // Ensure mecom_reg is defined for the registration form
        if (typeof mecom_reg === 'undefined') {
            var mecom_reg = {
                ajax_url: '<?php echo esc_js(admin_url('admin-ajax.php')); ?>',
                nonce: '<?php echo esc_js(wp_create_nonce('mecom_registration_nonce')); ?>',
                recaptcha_enabled: <?php echo get_option('mecom_recaptcha_enabled', 'no') === 'yes' ? 'true' : 'false'; ?>,
                recaptcha_site_key: '<?php echo esc_js(get_option('mecom_recaptcha_site_key', '')); ?>',
                twilio_enabled: <?php echo get_option('mecom_twilio_enabled', 'no') === 'yes' ? 'true' : 'false'; ?>,
                i18n: {
                    submitting: '<?php echo esc_js(__('Submitting...', 'mec-organizer-manager')); ?>',
                    error: '<?php echo esc_js(__('An error occurred. Please try again.', 'mec-organizer-manager')); ?>',
                    required: '<?php echo esc_js(__('This field is required', 'mec-organizer-manager')); ?>',
                    invalid_email: '<?php echo esc_js(__('Please enter a valid email address', 'mec-organizer-manager')); ?>',
                    invalid_phone: '<?php echo esc_js(__('Please enter a valid phone number', 'mec-organizer-manager')); ?>',
                    password_short: '<?php echo esc_js(__('Password must be at least 8 characters', 'mec-organizer-manager')); ?>',
                    recaptcha_required: '<?php echo esc_js(__('Please complete the reCAPTCHA', 'mec-organizer-manager')); ?>',
                    confirm_cancel: '<?php echo esc_js(__('Are you sure you want to cancel?', 'mec-organizer-manager')); ?>',
                    social_placeholder: '<?php echo esc_js(__('https://...', 'mec-organizer-manager')); ?>',
                    sending_code: '<?php echo esc_js(__('Sending code...', 'mec-organizer-manager')); ?>',
                    code_sent: '<?php echo esc_js(__('Verification code sent!', 'mec-organizer-manager')); ?>',
                    verifying: '<?php echo esc_js(__('Verifying...', 'mec-organizer-manager')); ?>',
                    invalid_code: '<?php echo esc_js(__('Invalid verification code', 'mec-organizer-manager')); ?>',
                    code_expired: '<?php echo esc_js(__('Code expired. Please request a new one.', 'mec-organizer-manager')); ?>',
                    resend_in: '<?php echo esc_js(__('Resend in', 'mec-organizer-manager')); ?>'
                }
            };
        }
        console.log('MECOM: Inline mecom_reg defined', mecom_reg);
        </script>
        
        <style>
            <?php if ($settings['show_title_lines'] !== 'yes'): ?>
            .mecom-form-title.mecom-no-lines::before,
            .mecom-form-title.mecom-no-lines::after {
                display: none;
            }
            <?php endif; ?>
            
            <?php if (!$show_accent_bar): ?>
            {{WRAPPER}} .mecom-host-registration-modal.mecom-no-accent-bar::before {
                display: none;
            }
            <?php endif; ?>
        </style>
        <?php
    }

    protected function content_template() {
        ?>
        <div class="mecom-host-registration-wrapper">
            <div class="mecom-host-registration-modal <# if (settings.show_accent_bar !== 'yes') { #>mecom-no-accent-bar<# } #>">
                <# if (settings.show_close_button === 'yes') { #>
                <button type="button" class="mecom-modal-close">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M15 9l-6 6M9 9l6 6"/>
                    </svg>
                </button>
                <# } #>
                
                <# if (settings.show_logo === 'yes') { #>
                <div class="mecom-modal-logo">
                    <# if (settings.logo_source === 'custom' && settings.custom_logo.url) { #>
                    <img src="{{ settings.custom_logo.url }}" alt="">
                    <# } else { #>
                    <div style="width: 80px; height: 80px; background: #f0f0f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: #999;">Logo</div>
                    <# } #>
                </div>
                <# } #>
                
                <div class="mecom-form-step">
                    <# if (settings.show_step_titles === 'yes') { #>
                    <h2 class="mecom-form-title <# if (settings.show_title_lines !== 'yes') { #>mecom-no-lines<# } #>">{{{ settings.step1_title }}}</h2>
                    <# } #>
                    
                    <p class="mecom-required-note">*required</p>
                    
                    <div class="mecom-form-group">
                        <label>{{{ settings.name_label }}}*</label>
                        <input type="text" placeholder="{{ settings.name_placeholder }}">
                        <# if (settings.name_hint) { #>
                        <span class="mecom-field-hint">{{{ settings.name_hint }}}</span>
                        <# } #>
                    </div>
                    
                    <div class="mecom-form-group">
                        <label>{{{ settings.email_label }}}*</label>
                        <input type="email" placeholder="example@email.com">
                        <# if (settings.email_hint) { #>
                        <span class="mecom-field-hint">{{{ settings.email_hint }}}</span>
                        <# } #>
                    </div>
                    
                    <div class="mecom-form-group">
                        <label>{{{ settings.phone_label }}}*</label>
                        <div class="mecom-phone-input-wrapper">
                            <select class="mecom-phone-country">
                                <option value="+1">+1</option>
                            </select>
                            <input type="tel" placeholder="740 123 1234">
                        </div>
                        <# if (settings.phone_hint) { #>
                        <span class="mecom-field-hint">{{{ settings.phone_hint }}}</span>
                        <# } #>
                    </div>
                    
                    <div class="mecom-form-group">
                        <label>{{{ settings.location_label }}}*</label>
                        <input type="text" placeholder="{{ settings.location_placeholder }}">
                    </div>
                    
                    <div class="mecom-form-group">
                        <label>{{{ settings.password_label }}}*</label>
                        <div class="mecom-password-wrapper">
                            <input type="password" placeholder="Enter password">
                            <button type="button" class="mecom-password-toggle">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mecom-form-buttons">
                        <button type="button" class="mecom-btn mecom-btn-secondary">{{{ settings.cancel_text }}}</button>
                        <button type="button" class="mecom-btn mecom-btn-primary">{{{ settings.continue_text }}}</button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            .mecom-host-registration-modal.mecom-no-accent-bar::before {
                display: none !important;
            }
            .mecom-form-title.mecom-no-lines::before,
            .mecom-form-title.mecom-no-lines::after {
                display: none;
            }
        </style>
        <?php
    }
}
