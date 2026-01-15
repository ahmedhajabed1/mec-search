<?php
/**
 * Elementor Pro Condition for MEC Organizer Archives
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('\ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base')) {
    return;
}

class MECAS_Organizer_Archive_Condition extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

    public static function get_type() {
        return 'archive';
    }

    public function get_name() {
        return 'mec_organizer';
    }

    public function get_label() {
        return __('MEC Organizer', 'mec-starter-addons');
    }

    public function get_all_label() {
        return __('All Organizers', 'mec-starter-addons');
    }

    public function check($args) {
        return is_tax('mec_organizer');
    }
}
