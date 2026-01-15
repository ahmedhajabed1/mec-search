<?php
if (!defined('ABSPATH')) exit;

class MECOM_Organizer_Bio_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'mecom-organizer-bio'; }
    public function get_title() { return __('Organizer Bio', 'mec-organizer-manager'); }
    public function get_group() { return 'mecom-organizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }

    public function render() {
        $organizer = mecom_get_current_organizer();
        echo $organizer ? wp_kses_post($organizer['bio']) : '';
    }
}
