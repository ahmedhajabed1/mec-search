<?php
if (!defined('ABSPATH')) exit;

class MECOM_Organizer_Name_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'mecom-organizer-name'; }
    public function get_title() { return __('Organizer Name', 'mec-organizer-manager'); }
    public function get_group() { return 'mecom-organizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }

    public function render() {
        $organizer = mecom_get_current_organizer();
        echo $organizer ? esc_html($organizer['name']) : '';
    }
}
