<?php
/**
 * Elementor Dynamic Tag - Organizer Field (General)
 * Access any organizer meta field
 */

if (!defined('ABSPATH')) exit;

class MECAS_Organizer_Field_Tag extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'mecas-organizer-field';
    }

    public function get_title() {
        return __('Organizer Field', 'mec-starter-addons');
    }

    public function get_group() {
        return 'mecas-organizer';
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
        ];
    }

    protected function register_controls() {
        $this->add_control('field', [
            'label' => __('Field', 'mec-starter-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'tagline',
            'options' => [
                'tagline' => __('Tagline', 'mec-starter-addons'),
                'bio' => __('Bio', 'mec-starter-addons'),
                'fun_fact' => __('Fun Fact', 'mec-starter-addons'),
                'offerings' => __('Offerings', 'mec-starter-addons'),
                'city' => __('City', 'mec-starter-addons'),
                'state' => __('State', 'mec-starter-addons'),
                'location' => __('Location (City, State)', 'mec-starter-addons'),
                'tel' => __('Phone', 'mec-starter-addons'),
                'email' => __('Email', 'mec-starter-addons'),
                'page_url' => __('Website URL', 'mec-starter-addons'),
                'facebook' => __('Facebook URL', 'mec-starter-addons'),
                'instagram' => __('Instagram URL', 'mec-starter-addons'),
                'twitter' => __('Twitter/X URL', 'mec-starter-addons'),
                'linkedin' => __('LinkedIn URL', 'mec-starter-addons'),
                'tiktok' => __('TikTok URL', 'mec-starter-addons'),
            ],
        ]);
    }

    public function render() {
        $organizer_id = $this->get_current_organizer_id();
        
        if (!$organizer_id) {
            return;
        }

        $field = $this->get_settings('field');
        $value = '';

        switch ($field) {
            case 'tagline':
                $value = get_term_meta($organizer_id, 'mecas_organizer_tagline', true);
                break;
            case 'bio':
                $value = get_term_meta($organizer_id, 'mecas_organizer_bio', true);
                break;
            case 'fun_fact':
                $value = get_term_meta($organizer_id, 'mecas_organizer_fun_fact', true);
                break;
            case 'offerings':
                $value = get_term_meta($organizer_id, 'mecas_organizer_offerings', true);
                break;
            case 'city':
                $value = get_term_meta($organizer_id, 'mecas_organizer_city', true);
                break;
            case 'state':
                $value = get_term_meta($organizer_id, 'mecas_organizer_state', true);
                break;
            case 'location':
                $city = get_term_meta($organizer_id, 'mecas_organizer_city', true);
                $state = get_term_meta($organizer_id, 'mecas_organizer_state', true);
                $value = $city;
                if ($state) {
                    $value .= $city ? ', ' . $state : $state;
                }
                break;
            case 'tel':
                $value = get_term_meta($organizer_id, 'tel', true);
                break;
            case 'email':
                $value = get_term_meta($organizer_id, 'email', true);
                break;
            case 'page_url':
                $value = get_term_meta($organizer_id, 'url', true);
                break;
            case 'facebook':
                $value = get_term_meta($organizer_id, 'facebook', true);
                break;
            case 'instagram':
                $value = get_term_meta($organizer_id, 'instagram', true);
                break;
            case 'twitter':
                $value = get_term_meta($organizer_id, 'twitter', true);
                break;
            case 'linkedin':
                $value = get_term_meta($organizer_id, 'linkedin', true);
                break;
            case 'tiktok':
                $value = get_term_meta($organizer_id, 'mecas_organizer_tiktok', true);
                break;
        }

        echo wp_kses_post($value);
    }

    private function get_current_organizer_id() {
        if (is_tax('mec_organizer')) {
            $term = get_queried_object();
            return $term ? $term->term_id : null;
        }
        return null;
    }
}
