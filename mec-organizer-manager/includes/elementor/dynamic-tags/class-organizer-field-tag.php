<?php
if (!defined('ABSPATH')) exit;

class MECOM_Organizer_Field_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'mecom-organizer-field'; }
    public function get_title() { return __('Organizer Field', 'mec-organizer-manager'); }
    public function get_group() { return 'mecom-organizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }

    protected function register_controls() {
        $this->add_control('field', [
            'label' => __('Field', 'mec-organizer-manager'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'name' => __('Name', 'mec-organizer-manager'),
                'tagline' => __('Tagline', 'mec-organizer-manager'),
                'location' => __('Location', 'mec-organizer-manager'),
                'city' => __('City', 'mec-organizer-manager'),
                'state' => __('State', 'mec-organizer-manager'),
                'bio' => __('Bio', 'mec-organizer-manager'),
                'fun_fact' => __('Fun Fact', 'mec-organizer-manager'),
                'offerings' => __('Offerings', 'mec-organizer-manager'),
                'email' => __('Email', 'mec-organizer-manager'),
                'tel' => __('Phone', 'mec-organizer-manager'),
            ],
            'default' => 'name',
        ]);
    }

    public function render() {
        $organizer = mecom_get_current_organizer();
        if (!$organizer) return;
        
        $field = $this->get_settings('field');
        echo isset($organizer[$field]) ? esc_html($organizer[$field]) : '';
    }
}
