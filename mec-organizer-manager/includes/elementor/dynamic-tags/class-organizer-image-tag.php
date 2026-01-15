<?php
if (!defined('ABSPATH')) exit;

class MECOM_Organizer_Image_Tag extends \Elementor\Core\DynamicTags\Data_Tag {
    public function get_name() { return 'mecom-organizer-image'; }
    public function get_title() { return __('Organizer Image', 'mec-organizer-manager'); }
    public function get_group() { return 'mecom-organizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY]; }

    public function get_value(array $options = []) {
        $organizer = mecom_get_current_organizer();
        if ($organizer && !empty($organizer['thumbnail'])) {
            return ['url' => $organizer['thumbnail'], 'id' => ''];
        }
        return ['url' => '', 'id' => ''];
    }
}
