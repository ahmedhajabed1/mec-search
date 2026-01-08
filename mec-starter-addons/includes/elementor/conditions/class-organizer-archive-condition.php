<?php
/**
 * Elementor Pro Condition for MEC Organizer Archives
 */

if (!defined('ABSPATH')) exit;

// Only load if Elementor Pro is active
if (!class_exists('\ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base')) {
    return;
}

class MECAS_Organizer_Archive_Condition extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

    /**
     * Get condition group
     */
    public static function get_type() {
        return 'archive';
    }

    /**
     * Get condition name
     */
    public function get_name() {
        return 'mec_organizer';
    }

    /**
     * Get condition label
     */
    public function get_label() {
        return __('MEC Organizer', 'mec-starter-addons');
    }

    /**
     * Get all label
     */
    public function get_all_label() {
        return __('All Organizers', 'mec-starter-addons');
    }

    /**
     * Check condition
     */
    public function check($args) {
        return is_tax('mec_organizer');
    }
}
