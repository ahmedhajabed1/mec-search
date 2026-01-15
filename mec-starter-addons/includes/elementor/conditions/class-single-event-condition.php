<?php
/**
 * Elementor Pro Condition for Single MEC Events
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('\ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base')) {
    return;
}

class MECAS_Single_Event_Condition extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

    public static function get_type() {
        return 'singular';
    }

    public function get_name() {
        return 'mec_single_event';
    }

    public function get_label() {
        return __('MEC Event', 'mec-starter-addons');
    }

    public function get_all_label() {
        return __('All MEC Events', 'mec-starter-addons');
    }

    public function check($args) {
        return is_singular('mec-events');
    }
}
