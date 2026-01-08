<?php
/**
 * Elementor Dynamic Tag - Organizer Bio
 */

if (!defined('ABSPATH')) exit;

class MECAS_Organizer_Bio_Tag extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'mecas-organizer-bio';
    }

    public function get_title() {
        return __('Organizer Bio', 'mec-starter-addons');
    }

    public function get_group() {
        return 'mecas-organizer';
    }

    public function get_categories() {
        return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY];
    }

    protected function register_controls() {
        $this->add_control('bio_source', [
            'label' => __('Source', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'bio',
            'options' => [
                'bio' => __('Custom Bio Field', 'mec-starter-addons'),
                'description' => __('MEC Description', 'mec-starter-addons'),
            ],
        ]);
    }

    public function render() {
        $organizer_id = $this->get_current_organizer_id();
        
        if (!$organizer_id) {
            return;
        }

        $source = $this->get_settings('bio_source');
        
        if ($source === 'description') {
            $term = get_term($organizer_id, 'mec_organizer');
            if ($term && !is_wp_error($term)) {
                echo wp_kses_post($term->description);
            }
        } else {
            $bio = get_term_meta($organizer_id, 'mecas_organizer_bio', true);
            echo wp_kses_post($bio);
        }
    }

    private function get_current_organizer_id() {
        if (is_tax('mec_organizer')) {
            $term = get_queried_object();
            return $term ? $term->term_id : null;
        }
        return null;
    }
}
