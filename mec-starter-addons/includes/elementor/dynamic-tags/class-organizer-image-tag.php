<?php
if (!defined('ABSPATH')) exit;

class MECAS_Organizer_Image_Tag extends \Elementor\Core\DynamicTags\Data_Tag {
    public function get_name() { return 'mecas-organizer-image'; }
    public function get_title() { return __('Organizer Image', 'mec-starter-addons'); }
    public function get_group() { return 'mecas-organizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY]; }
    public function get_value(array $options = []) {
        $organizer_id = $this->get_current_organizer_id();
        if (!$organizer_id) return ['id' => '', 'url' => ''];
        $thumbnail = get_term_meta($organizer_id, 'thumbnail', true);
        if ($thumbnail) {
            if (is_numeric($thumbnail)) return ['id' => $thumbnail, 'url' => wp_get_attachment_url($thumbnail)];
            else return ['id' => '', 'url' => $thumbnail];
        }
        return ['id' => '', 'url' => ''];
    }
    private function get_current_organizer_id() {
        if (is_tax('mec_organizer')) { $term = get_queried_object(); return $term ? $term->term_id : null; }
        return null;
    }
}
