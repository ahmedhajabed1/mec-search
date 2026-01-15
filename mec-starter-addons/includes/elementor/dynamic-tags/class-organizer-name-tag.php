<?php
if (!defined('ABSPATH')) exit;

class MECAS_Organizer_Name_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'mecas-organizer-name'; }
    public function get_title() { return __('Organizer Name', 'mec-starter-addons'); }
    public function get_group() { return 'mecas-organizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }
    public function render() {
        $organizer_id = $this->get_current_organizer_id();
        if (!$organizer_id) return;
        $term = get_term($organizer_id, 'mec_organizer');
        if ($term && !is_wp_error($term)) echo esc_html($term->name);
    }
    private function get_current_organizer_id() {
        if (is_tax('mec_organizer')) { $term = get_queried_object(); return $term ? $term->term_id : null; }
        return null;
    }
}
