<?php
/**
 * MEC Organizer Manager - Elementor Theme Builder Condition
 * Adds "Organizer Profile" condition for Theme Builder templates
 */

if (!defined('ABSPATH')) exit;

/**
 * Organizer Profile Condition - Shows under Archives in Theme Builder
 */
class MECOM_Elementor_Organizer_Condition extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

    /**
     * Get condition type - 'archive' to show in archive dropdown
     */
    public static function get_type() {
        return 'archive';
    }

    /**
     * Get priority - higher runs first
     */
    public static function get_priority() {
        return 40;
    }

    /**
     * Get unique name
     */
    public function get_name() {
        return 'mecom_organizer_profile';
    }

    /**
     * Get human-readable label
     */
    public function get_label() {
        return __('Organizer Profiles', 'mec-organizer-manager');
    }

    /**
     * Get label for "all" option
     */
    public function get_all_label() {
        return __('All Organizer Profiles', 'mec-organizer-manager');
    }

    /**
     * Check if current page matches this condition
     */
    public function check($args) {
        // Check if we're on an organizer profile page
        $organizer_id = get_query_var('mecom_organizer_id');
        
        if (!$organizer_id) {
            return false;
        }

        // If a specific organizer is selected in condition
        if (!empty($args['id'])) {
            return intval($organizer_id) === intval($args['id']);
        }

        // Match all organizer profiles
        return true;
    }

    /**
     * Register sub-conditions for individual organizers
     */
    public function register_sub_conditions() {
        $organizers = get_terms([
            'taxonomy' => 'mec_organizer',
            'hide_empty' => false,
            'number' => 50,
        ]);

        if (is_wp_error($organizers) || empty($organizers)) {
            return;
        }

        foreach ($organizers as $organizer) {
            $condition = new MECOM_Elementor_Single_Organizer_Condition([
                'organizer_id' => $organizer->term_id,
                'organizer_name' => $organizer->name,
            ]);
            $this->register_sub_condition($condition);
        }
    }
}

/**
 * Single Organizer Condition (for specific organizer selection)
 */
class MECOM_Elementor_Single_Organizer_Condition extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

    private $organizer_id;
    private $organizer_name;

    public function __construct($args = []) {
        $this->organizer_id = $args['organizer_id'] ?? 0;
        $this->organizer_name = $args['organizer_name'] ?? '';
        parent::__construct();
    }

    public static function get_type() {
        return 'archive';
    }

    public static function get_priority() {
        return 50;
    }

    public function get_name() {
        return 'mecom_organizer_' . $this->organizer_id;
    }

    public function get_label() {
        return $this->organizer_name;
    }

    public function check($args) {
        $current_organizer_id = get_query_var('mecom_organizer_id');
        return intval($current_organizer_id) === intval($this->organizer_id);
    }
}
