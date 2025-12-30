<?php
/**
 * Plugin Name: MEC Advanced Search
 * Plugin URI: https://themajhhub.com
 * Description: Advanced search functionality for Modern Events Calendar with geolocation and filters
 * Version: 3.0.0
 * Author: Ahmed Haj Abed
 * Author URI: https://themajhhub.com
 * License: GPL v2 or later
 * Text Domain: mec-advanced-search
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) exit;

define('MECAS_VERSION', '3.0.0');
define('MECAS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MECAS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MECAS_PLUGIN_BASENAME', plugin_basename(__FILE__));

class MEC_Advanced_Search {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        add_action('plugins_loaded', array($this, 'check_mec_dependency'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        add_shortcode('mec_advanced_search', array($this, 'render_search_form'));
        add_shortcode('mec_search_results', array($this, 'render_search_results'));
        
        add_action('wp_ajax_mecas_search', array($this, 'ajax_search'));
        add_action('wp_ajax_nopriv_mecas_search', array($this, 'ajax_search'));
        add_action('wp_ajax_mecas_get_locations', array($this, 'ajax_get_locations'));
        add_action('wp_ajax_nopriv_mecas_get_locations', array($this, 'ajax_get_locations'));
        add_action('wp_ajax_mecas_reverse_geocode', array($this, 'ajax_reverse_geocode'));
        add_action('wp_ajax_nopriv_mecas_reverse_geocode', array($this, 'ajax_reverse_geocode'));
        add_action('wp_ajax_mecas_filter_events', array($this, 'ajax_filter_events'));
        add_action('wp_ajax_nopriv_mecas_filter_events', array($this, 'ajax_filter_events'));
        
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        add_action('elementor/widgets/register', array($this, 'register_elementor_widgets'));
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_category'));
    }

    public function check_mec_dependency() {
        if (!class_exists('MEC')) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>MEC Advanced Search requires Modern Events Calendar.</p></div>';
            });
        }
    }

    public function enqueue_assets() {
        wp_enqueue_style('mecas-styles', MECAS_PLUGIN_URL . 'assets/css/mecas-styles.css', array(), MECAS_VERSION);
        wp_enqueue_script('mecas-scripts', MECAS_PLUGIN_URL . 'assets/js/mecas-scripts.js', array('jquery'), MECAS_VERSION, true);
        
        wp_localize_script('mecas-scripts', 'mecas_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mecas_nonce'),
            'results_page' => get_option('mecas_results_page', ''),
            'i18n' => array(
                'detecting' => __('Detecting...', 'mec-advanced-search'),
                'location_error' => __('Could not detect location', 'mec-advanced-search'),
                'no_results' => __('No events found', 'mec-advanced-search'),
                'loading' => __('Loading...', 'mec-advanced-search'),
                'enter_location' => __('City, State', 'mec-advanced-search'),
            )
        ));
    }

    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'mec-advanced-search') === false) return;
        wp_enqueue_style('mecas-admin-styles', MECAS_PLUGIN_URL . 'assets/css/mecas-admin.css', array(), MECAS_VERSION);
    }

    /**
     * Render search form - Original Dark Design
     */
    public function render_search_form($atts) {
        $atts = shortcode_atts(array(
            'results_page' => get_option('mecas_results_page', ''),
            'enable_geolocation' => 'true',
            'auto_detect_location' => 'true',
            'placeholder_search' => __('Search Teachers or Events', 'mec-advanced-search'),
            'placeholder_location' => __('City, State', 'mec-advanced-search'),
            'mode' => 'inline',
            'show_divider' => 'true',
            'trigger_text' => __('Search Events', 'mec-advanced-search'),
            'trigger_icon' => 'true',
            'popup_title' => __('Find Events', 'mec-advanced-search'),
            'show_suggestions' => 'true',
            'widget_id' => 'mecas-' . uniqid(),
        ), $atts);
        
        $widget_id = esc_attr($atts['widget_id']);
        ob_start();
        
        if ($atts['mode'] === 'popup') {
            ?>
            <div class="mecas-trigger-wrapper" id="<?php echo $widget_id; ?>-trigger-wrapper">
                <button type="button" class="mecas-trigger-button" id="<?php echo $widget_id; ?>-trigger" data-modal="<?php echo $widget_id; ?>-modal">
                    <?php if ($atts['trigger_icon'] === 'true'): ?>
                    <svg class="mecas-trigger-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <?php endif; ?>
                    <?php if ($atts['trigger_text']): ?><span class="mecas-trigger-text"><?php echo esc_html($atts['trigger_text']); ?></span><?php endif; ?>
                </button>
            </div>
            <div class="mecas-modal-overlay" id="<?php echo $widget_id; ?>-modal">
                <div class="mecas-modal-backdrop"></div>
                <div class="mecas-modal-content">
                    <button type="button" class="mecas-modal-close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                    <?php if ($atts['popup_title']): ?><h3 class="mecas-modal-title"><?php echo esc_html($atts['popup_title']); ?></h3><?php endif; ?>
                    <?php echo $this->render_search_bar($atts, $widget_id . '-modal'); ?>
                </div>
            </div>
            <?php
        } else {
            echo $this->render_search_bar($atts, $widget_id);
        }
        return ob_get_clean();
    }

    /**
     * Render the search bar HTML - Clean Dark Design with Auto Geolocation
     */
    private function render_search_bar($atts, $form_id) {
        $auto_detect = ($atts['enable_geolocation'] === 'true' && $atts['auto_detect_location'] === 'true') ? 'true' : 'false';
        $show_divider = $atts['show_divider'] === 'true';
        ob_start();
        ?>
        <div class="mecas-search-wrapper" id="<?php echo esc_attr($form_id); ?>" data-enable-geolocation="<?php echo esc_attr($atts['enable_geolocation']); ?>" data-auto-detect="<?php echo esc_attr($auto_detect); ?>">
            <form class="mecas-search-form" action="<?php echo esc_url($atts['results_page']); ?>" method="GET">
                <div class="mecas-search-container">
                    <!-- Search Input -->
                    <div class="mecas-input-group mecas-search-input-group">
                        <input type="text" name="mecas_query" class="mecas-input mecas-query-input" placeholder="<?php echo esc_attr($atts['placeholder_search']); ?>" autocomplete="off">
                        <?php if ($atts['show_suggestions'] === 'true'): ?>
                        <div class="mecas-suggestions mecas-query-suggestions"></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Divider -->
                    <?php if ($show_divider): ?>
                    <div class="mecas-divider"></div>
                    <?php endif; ?>
                    
                    <!-- Location Input -->
                    <div class="mecas-input-group mecas-location-input-group">
                        <input type="text" name="mecas_location" class="mecas-input mecas-location-input" placeholder="<?php echo esc_attr($atts['placeholder_location']); ?>" autocomplete="off">
                        <?php if ($atts['enable_geolocation'] === 'true'): ?>
                        <div class="mecas-location-loading" style="display:none;">
                            <svg class="mecas-spinner" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/></svg>
                        </div>
                        <?php endif; ?>
                        <?php if ($atts['show_suggestions'] === 'true'): ?>
                        <div class="mecas-suggestions mecas-location-suggestions"></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="mecas-search-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render search results with filters
     */
    public function render_search_results($atts) {
        $atts = shortcode_atts(array(
            'show_search_bar' => 'true',
            'enable_geolocation' => 'true',
            'auto_detect_location' => 'true',
            'placeholder_search' => __('Search Teachers or Events', 'mec-advanced-search'),
            'placeholder_location' => __('City, State', 'mec-advanced-search'),
            'show_divider' => 'true',
            'show_filters' => 'true',
            'show_category_filter' => 'true',
            'show_label_filter' => 'true',
            'show_organizer_filter' => 'true',
            'show_tag_filter' => 'true',
            'filter_layout' => 'horizontal',
            'label_category' => __('Category', 'mec-advanced-search'),
            'label_label' => __('Label', 'mec-advanced-search'),
            'label_organizer' => __('Organizer', 'mec-advanced-search'),
            'label_tag' => __('Tag', 'mec-advanced-search'),
            'label_clear' => __('Clear Filters', 'mec-advanced-search'),
            'columns' => '3',
            'per_page' => '12',
            'show_pagination' => 'true',
            'no_results_text' => __('No events found.', 'mec-advanced-search'),
            'widget_id' => 'mecas-results-' . uniqid(),
        ), $atts);
        
        $query = isset($_GET['mecas_query']) ? sanitize_text_field($_GET['mecas_query']) : '';
        $location = isset($_GET['mecas_location']) ? sanitize_text_field($_GET['mecas_location']) : '';
        $category = isset($_GET['mecas_category']) ? sanitize_text_field($_GET['mecas_category']) : '';
        $label = isset($_GET['mecas_label']) ? sanitize_text_field($_GET['mecas_label']) : '';
        $organizer = isset($_GET['mecas_organizer']) ? sanitize_text_field($_GET['mecas_organizer']) : '';
        $tag = isset($_GET['mecas_tag']) ? sanitize_text_field($_GET['mecas_tag']) : '';
        $paged = isset($_GET['mecas_page']) ? max(1, intval($_GET['mecas_page'])) : 1;
        
        $categories = $this->get_mec_categories();
        $labels = $this->get_mec_labels();
        $organizers = $this->get_mec_organizers();
        $tags = $this->get_event_tags();
        
        $results = $this->search_events_with_filters($query, $location, $category, $label, $organizer, $tag, intval($atts['per_page']), $paged);
        
        ob_start();
        include MECAS_PLUGIN_DIR . 'templates/search-results.php';
        return ob_get_clean();
    }

    public function get_mec_categories() {
        $terms = get_terms(array('taxonomy' => 'mec_category', 'hide_empty' => true));
        return !is_wp_error($terms) ? $terms : array();
    }

    public function get_mec_labels() {
        $terms = get_terms(array('taxonomy' => 'mec_label', 'hide_empty' => true));
        return !is_wp_error($terms) ? $terms : array();
    }

    public function get_mec_organizers() {
        $terms = get_terms(array('taxonomy' => 'mec_organizer', 'hide_empty' => true));
        return !is_wp_error($terms) ? $terms : array();
    }

    public function get_event_tags() {
        $event_ids = get_posts(array('post_type' => 'mec-events', 'posts_per_page' => -1, 'fields' => 'ids'));
        if (empty($event_ids)) return array();
        $terms = get_terms(array('taxonomy' => 'post_tag', 'hide_empty' => true, 'object_ids' => $event_ids));
        return !is_wp_error($terms) ? $terms : array();
    }

    public function search_events_with_filters($query = '', $location = '', $category = '', $label = '', $organizer = '', $tag = '', $per_page = 12, $paged = 1) {
        $args = array(
            'post_type' => 'mec-events',
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'paged' => $paged,
            'orderby' => 'meta_value',
            'meta_key' => 'mec_start_date',
            'order' => 'ASC',
        );
        
        if ($query) $args['s'] = $query;
        
        $tax_query = array('relation' => 'AND');
        if ($category) $tax_query[] = array('taxonomy' => 'mec_category', 'field' => 'slug', 'terms' => $category);
        if ($label) $tax_query[] = array('taxonomy' => 'mec_label', 'field' => 'slug', 'terms' => $label);
        if ($organizer) $tax_query[] = array('taxonomy' => 'mec_organizer', 'field' => 'slug', 'terms' => $organizer);
        if ($tag) $tax_query[] = array('taxonomy' => 'post_tag', 'field' => 'slug', 'terms' => $tag);
        if (count($tax_query) > 1) $args['tax_query'] = $tax_query;
        
        if ($location) {
            $location_ids = $this->get_location_ids_by_city($location);
            if ($location_ids) $args['meta_query'][] = array('key' => 'mec_location_id', 'value' => $location_ids, 'compare' => 'IN');
        }
        
        $wp_query = new WP_Query($args);
        return array('events' => $wp_query->posts, 'total' => $wp_query->found_posts, 'max_pages' => $wp_query->max_num_pages, 'current_page' => $paged);
    }

    private function get_location_ids_by_city($city) {
        $ids = array();
        $terms = get_terms(array('taxonomy' => 'mec_location', 'hide_empty' => false));
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $addr = get_term_meta($term->term_id, 'address', true);
                $c = get_term_meta($term->term_id, 'city', true);
                if (stripos($addr, $city) !== false || stripos($c, $city) !== false || stripos($term->name, $city) !== false) $ids[] = $term->term_id;
            }
        }
        return $ids;
    }

    public function ajax_search() {
        check_ajax_referer('mecas_nonce', 'nonce');
        $query = isset($_POST['query']) ? sanitize_text_field($_POST['query']) : '';
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 5;
        
        $events = get_posts(array('post_type' => 'mec-events', 'post_status' => 'publish', 'posts_per_page' => $limit, 's' => $query));
        $results = array();
        foreach ($events as $event) $results[] = $this->get_event_data($event);
        wp_send_json_success($results);
    }

    private function get_event_data($event) {
        $id = $event->ID;
        $date = get_post_meta($id, 'mec_start_date', true);
        $loc_id = get_post_meta($id, 'mec_location_id', true);
        $loc = $loc_id ? get_term($loc_id, 'mec_location') : null;
        return array(
            'id' => $id,
            'title' => get_the_title($event),
            'url' => get_permalink($id),
            'image' => get_the_post_thumbnail_url($id, 'medium'),
            'date' => $date ? date_i18n(get_option('date_format'), strtotime($date)) : '',
            'location' => ($loc && !is_wp_error($loc)) ? $loc->name : '',
        );
    }

    public function ajax_get_locations() {
        check_ajax_referer('mecas_nonce', 'nonce');
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        $locations = array();
        if (strlen($search) >= 2) {
            $terms = get_terms(array('taxonomy' => 'mec_location', 'hide_empty' => true, 'search' => $search));
            if (!is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $city = get_term_meta($term->term_id, 'city', true);
                    $state = get_term_meta($term->term_id, 'state', true);
                    $display = $city ?: $term->name;
                    if ($state) $display .= ', ' . $state;
                    $locations[] = array('id' => $term->term_id, 'display' => $display);
                }
            }
        }
        wp_send_json_success($locations);
    }

    public function ajax_reverse_geocode() {
        check_ajax_referer('mecas_nonce', 'nonce');
        $lat = isset($_POST['lat']) ? floatval($_POST['lat']) : 0;
        $lng = isset($_POST['lng']) ? floatval($_POST['lng']) : 0;
        
        if ($lat && $lng) {
            $url = sprintf('https://nominatim.openstreetmap.org/reverse?format=json&lat=%s&lon=%s&zoom=10&addressdetails=1', $lat, $lng);
            $response = wp_remote_get($url, array('headers' => array('User-Agent' => 'MEC Advanced Search'), 'timeout' => 10));
            
            if (!is_wp_error($response)) {
                $body = json_decode(wp_remote_retrieve_body($response), true);
                if ($body && isset($body['address'])) {
                    $a = $body['address'];
                    $city = $a['city'] ?? $a['town'] ?? $a['village'] ?? '';
                    $state = $a['state'] ?? '';
                    $abbr = $this->get_state_abbr($state);
                    $loc = $city . ($abbr ? ', ' . $abbr : '');
                    wp_send_json_success(array('location' => $loc));
                }
            }
        }
        wp_send_json_error();
    }

    private function get_state_abbr($state) {
        $map = array('Alabama'=>'AL','Alaska'=>'AK','Arizona'=>'AZ','Arkansas'=>'AR','California'=>'CA','Colorado'=>'CO','Connecticut'=>'CT','Delaware'=>'DE','Florida'=>'FL','Georgia'=>'GA','Hawaii'=>'HI','Idaho'=>'ID','Illinois'=>'IL','Indiana'=>'IN','Iowa'=>'IA','Kansas'=>'KS','Kentucky'=>'KY','Louisiana'=>'LA','Maine'=>'ME','Maryland'=>'MD','Massachusetts'=>'MA','Michigan'=>'MI','Minnesota'=>'MN','Mississippi'=>'MS','Missouri'=>'MO','Montana'=>'MT','Nebraska'=>'NE','Nevada'=>'NV','New Hampshire'=>'NH','New Jersey'=>'NJ','New Mexico'=>'NM','New York'=>'NY','North Carolina'=>'NC','North Dakota'=>'ND','Ohio'=>'OH','Oklahoma'=>'OK','Oregon'=>'OR','Pennsylvania'=>'PA','Rhode Island'=>'RI','South Carolina'=>'SC','South Dakota'=>'SD','Tennessee'=>'TN','Texas'=>'TX','Utah'=>'UT','Vermont'=>'VT','Virginia'=>'VA','Washington'=>'WA','West Virginia'=>'WV','Wisconsin'=>'WI','Wyoming'=>'WY');
        return $map[$state] ?? $state;
    }

    public function ajax_filter_events() {
        check_ajax_referer('mecas_nonce', 'nonce');
        $query = sanitize_text_field($_POST['query'] ?? '');
        $location = sanitize_text_field($_POST['location'] ?? '');
        $category = sanitize_text_field($_POST['category'] ?? '');
        $label = sanitize_text_field($_POST['label'] ?? '');
        $organizer = sanitize_text_field($_POST['organizer'] ?? '');
        $tag = sanitize_text_field($_POST['tag'] ?? '');
        $per_page = intval($_POST['per_page'] ?? 12);
        $paged = intval($_POST['page'] ?? 1);
        
        $results = $this->search_events_with_filters($query, $location, $category, $label, $organizer, $tag, $per_page, $paged);
        $events = array();
        foreach ($results['events'] as $e) $events[] = $this->get_event_data($e);
        wp_send_json_success(array('events' => $events, 'total' => $results['total'], 'max_pages' => $results['max_pages']));
    }

    public function register_elementor_widgets($widgets_manager) {
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-search-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-results-widget.php';
        $widgets_manager->register(new \MECAS_Search_Widget());
        $widgets_manager->register(new \MECAS_Results_Widget());
    }

    public function add_elementor_category($elements_manager) {
        $elements_manager->add_category('mec-advanced-search', array('title' => __('MEC Advanced Search', 'mec-advanced-search'), 'icon' => 'fa fa-search'));
    }

    public function add_admin_menu() {
        add_menu_page(__('MEC Search', 'mec-advanced-search'), __('MEC Search', 'mec-advanced-search'), 'manage_options', 'mec-advanced-search', array($this, 'render_admin_page'), 'dashicons-search', 30);
    }

    public function register_settings() {
        register_setting('mecas_settings', 'mecas_results_page');
        register_setting('mecas_settings', 'mecas_enable_geolocation');
    }

    public function render_admin_page() {
        include MECAS_PLUGIN_DIR . 'templates/admin-settings.php';
    }
}

MEC_Advanced_Search::get_instance();
