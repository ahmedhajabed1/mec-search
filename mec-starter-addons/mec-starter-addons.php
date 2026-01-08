<?php
/**
 * Plugin Name: MEC Starter Addons
 * Plugin URI: https://themajhhub.com
 * Description: Advanced Elementor widgets and features for Modern Events Calendar including search, organizer profiles, and more
 * Version: 5.3.6
 * Author: Ahmed Haj Abed
 * Author URI: https://themajhhub.com
 * License: GPL v2 or later
 * Text Domain: mec-starter-addons
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) exit;

define('MECAS_VERSION', '5.3.5');
define('MECAS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MECAS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MECAS_PLUGIN_BASENAME', plugin_basename(__FILE__));

class MEC_Starter_Addons {
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
        add_shortcode('mec_featured_events', array($this, 'render_featured_events'));
        add_shortcode('mec_upcoming_events', array($this, 'render_upcoming_events'));
        add_shortcode('mec_organizers_grid', array($this, 'render_organizers_grid'));
        add_shortcode('mec_teacher_search', array($this, 'render_teacher_search'));
        
        add_action('wp_ajax_mecas_search', array($this, 'ajax_search'));
        add_action('wp_ajax_nopriv_mecas_search', array($this, 'ajax_search'));
        add_action('wp_ajax_mecas_get_locations', array($this, 'ajax_get_locations'));
        add_action('wp_ajax_nopriv_mecas_get_locations', array($this, 'ajax_get_locations'));
        add_action('wp_ajax_mecas_reverse_geocode', array($this, 'ajax_reverse_geocode'));
        add_action('wp_ajax_nopriv_mecas_reverse_geocode', array($this, 'ajax_reverse_geocode'));
        add_action('wp_ajax_mecas_filter_events', array($this, 'ajax_filter_events'));
        add_action('wp_ajax_nopriv_mecas_filter_events', array($this, 'ajax_filter_events'));
        add_action('wp_ajax_mecas_filter_featured_events', array($this, 'ajax_filter_featured_events'));
        add_action('wp_ajax_nopriv_mecas_filter_featured_events', array($this, 'ajax_filter_featured_events'));
        add_action('wp_ajax_mecas_filter_upcoming_events', array($this, 'ajax_filter_upcoming_events'));
        add_action('wp_ajax_nopriv_mecas_filter_upcoming_events', array($this, 'ajax_filter_upcoming_events'));
        add_action('wp_ajax_mecas_search_teachers', array($this, 'ajax_search_teachers'));
        add_action('wp_ajax_nopriv_mecas_search_teachers', array($this, 'ajax_search_teachers'));
        add_action('wp_ajax_mecas_search_events_by_location', array($this, 'ajax_search_events_by_location'));
        add_action('wp_ajax_nopriv_mecas_search_events_by_location', array($this, 'ajax_search_events_by_location'));
        
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        add_action('elementor/widgets/register', array($this, 'register_elementor_widgets'));
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_category'));
        
        // Elementor Pro Theme Builder support for organizers
        add_action('elementor/theme/register_conditions', array($this, 'register_organizer_conditions'));
        add_action('elementor/dynamic_tags/register', array($this, 'register_dynamic_tags'));
        add_filter('elementor/theme/need_override_location', array($this, 'override_organizer_location'), 10, 2);
        add_action('init', array($this, 'ensure_organizer_taxonomy_public'), 20);
        
        // Add organizer meta fields
        add_action('mec_organizer_add_form_fields', array($this, 'add_organizer_fields'));
        add_action('mec_organizer_edit_form_fields', array($this, 'edit_organizer_fields'));
        add_action('created_mec_organizer', array($this, 'save_organizer_fields'));
        add_action('edited_mec_organizer', array($this, 'save_organizer_fields'));
        
        // Add featured event meta box
        add_action('add_meta_boxes', array($this, 'add_featured_meta_box'));
        add_action('save_post_mec-events', array($this, 'save_featured_meta'));
        
        // Add featured column to events list
        add_filter('manage_mec-events_posts_columns', array($this, 'add_featured_column'));
        add_action('manage_mec-events_posts_custom_column', array($this, 'render_featured_column'), 10, 2);
        add_action('wp_ajax_mecas_toggle_featured', array($this, 'ajax_toggle_featured'));
    }

    /**
     * Register Elementor Pro conditions for organizer archives
     */
    public function register_organizer_conditions($conditions_manager) {
        // Check if Elementor Pro Theme Builder exists
        if (!class_exists('\ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base')) {
            return;
        }

        require_once MECAS_PLUGIN_DIR . 'includes/elementor/conditions/class-organizer-archive-condition.php';
        
        // Check if our class was loaded successfully
        if (!class_exists('MECAS_Organizer_Archive_Condition')) {
            return;
        }
        
        // Get the archive condition group
        $archive = $conditions_manager->get_condition('archive');
        
        if ($archive) {
            $archive->register_sub_condition(new \MECAS_Organizer_Archive_Condition());
        }
    }

    /**
     * Register Elementor Theme Builder locations for organizers
     */
    public function register_organizer_locations($location_manager) {
        // This allows templates to be assigned to organizer archives
    }

    /**
     * Override location for organizer archives
     */
    public function override_organizer_location($need_override, $location) {
        if (is_tax('mec_organizer') && $location === 'archive') {
            return true;
        }
        return $need_override;
    }

    /**
     * Ensure mec_organizer taxonomy is public and has archive support
     */
    public function ensure_organizer_taxonomy_public() {
        global $wp_taxonomies;
        
        if (isset($wp_taxonomies['mec_organizer'])) {
            // Make sure the taxonomy is properly configured for archives
            $wp_taxonomies['mec_organizer']->public = true;
            $wp_taxonomies['mec_organizer']->publicly_queryable = true;
            $wp_taxonomies['mec_organizer']->show_ui = true;
            $wp_taxonomies['mec_organizer']->show_in_nav_menus = true;
            
            // Ensure rewrite is enabled
            if (empty($wp_taxonomies['mec_organizer']->rewrite)) {
                $wp_taxonomies['mec_organizer']->rewrite = array(
                    'slug' => 'organizer',
                    'with_front' => false,
                );
            }
        }
    }

    /**
     * Register Elementor Dynamic Tags for organizer data
     */
    public function register_dynamic_tags($dynamic_tags_manager) {
        // Register dynamic tag group
        $dynamic_tags_manager->register_group('mecas-organizer', [
            'title' => __('MEC Organizer', 'mec-starter-addons')
        ]);
        
        // Register individual dynamic tags
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/dynamic-tags/class-organizer-name-tag.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/dynamic-tags/class-organizer-image-tag.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/dynamic-tags/class-organizer-bio-tag.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/dynamic-tags/class-organizer-field-tag.php';
        
        $dynamic_tags_manager->register(new \MECAS_Organizer_Name_Tag());
        $dynamic_tags_manager->register(new \MECAS_Organizer_Image_Tag());
        $dynamic_tags_manager->register(new \MECAS_Organizer_Bio_Tag());
        $dynamic_tags_manager->register(new \MECAS_Organizer_Field_Tag());
    }

    public function check_mec_dependency() {
        if (!class_exists('MEC')) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>MEC Starter Addons requires Modern Events Calendar.</p></div>';
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
                'detecting' => __('Detecting...', 'mec-starter-addons'),
                'location_error' => __('Could not detect location', 'mec-starter-addons'),
                'no_results' => __('No events found', 'mec-starter-addons'),
                'loading' => __('Loading...', 'mec-starter-addons'),
                'enter_location' => __('City, State', 'mec-starter-addons'),
            )
        ));
    }

    public function enqueue_admin_assets($hook) {
        global $post_type;
        
        // Load on MEC Advanced Search admin page
        $is_mecas_page = strpos($hook, 'mec-starter-addons') !== false;
        
        // Load on MEC events list and edit pages
        $is_mec_events = ($post_type === 'mec-events' || get_post_type() === 'mec-events');
        
        if (!$is_mecas_page && !$is_mec_events) return;
        
        wp_enqueue_style('mecas-admin-styles', MECAS_PLUGIN_URL . 'assets/css/mecas-admin.css', array(), MECAS_VERSION);
        
        // Add inline script for featured toggle on events list
        if ($hook === 'edit.php' && $is_mec_events) {
            wp_enqueue_script('mecas-admin-toggle', MECAS_PLUGIN_URL . 'assets/js/mecas-admin.js', array('jquery'), MECAS_VERSION, true);
            wp_localize_script('mecas-admin-toggle', 'mecas_admin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('mecas_admin_nonce'),
            ));
        }
    }

    // ========================================
    // ORGANIZER META FIELDS
    // ========================================

    public function add_organizer_fields($taxonomy) {
        ?>
        <div class="form-field">
            <label for="mecas_organizer_city"><?php esc_html_e('City', 'mec-starter-addons'); ?></label>
            <input type="text" name="mecas_organizer_city" id="mecas_organizer_city" value="">
            <p class="description"><?php esc_html_e('City where the organizer is located', 'mec-starter-addons'); ?></p>
        </div>
        <div class="form-field">
            <label for="mecas_organizer_state"><?php esc_html_e('State', 'mec-starter-addons'); ?></label>
            <input type="text" name="mecas_organizer_state" id="mecas_organizer_state" value="">
            <p class="description"><?php esc_html_e('State/Region (e.g., FL, NY)', 'mec-starter-addons'); ?></p>
        </div>
        <div class="form-field">
            <label for="mecas_organizer_tagline"><?php esc_html_e('Tagline', 'mec-starter-addons'); ?></label>
            <textarea name="mecas_organizer_tagline" id="mecas_organizer_tagline" rows="3"></textarea>
            <p class="description"><?php esc_html_e('A short tagline or description for the organizer', 'mec-starter-addons'); ?></p>
        </div>
        <div class="form-field">
            <label for="mecas_organizer_bio"><?php esc_html_e('Bio', 'mec-starter-addons'); ?></label>
            <textarea name="mecas_organizer_bio" id="mecas_organizer_bio" rows="5"></textarea>
            <p class="description"><?php esc_html_e('Full biography of the organizer. Rich text editor available when editing.', 'mec-starter-addons'); ?></p>
        </div>
        <div class="form-field">
            <label for="mecas_organizer_fun_fact"><?php esc_html_e('Fun Fact', 'mec-starter-addons'); ?></label>
            <textarea name="mecas_organizer_fun_fact" id="mecas_organizer_fun_fact" rows="3"></textarea>
            <p class="description"><?php esc_html_e('An interesting fun fact about the organizer. Rich text editor available when editing.', 'mec-starter-addons'); ?></p>
        </div>
        <div class="form-field">
            <label for="mecas_organizer_offerings"><?php esc_html_e('Offerings', 'mec-starter-addons'); ?></label>
            <textarea name="mecas_organizer_offerings" id="mecas_organizer_offerings" rows="5"></textarea>
            <p class="description"><?php esc_html_e('List of offerings/services. Rich text editor available when editing.', 'mec-starter-addons'); ?></p>
        </div>
        <div class="form-field">
            <label for="mecas_organizer_tiktok"><?php esc_html_e('TikTok', 'mec-starter-addons'); ?></label>
            <input type="url" name="mecas_organizer_tiktok" id="mecas_organizer_tiktok" value="">
            <p class="description"><?php esc_html_e('TikTok profile URL', 'mec-starter-addons'); ?></p>
        </div>
        <?php
    }

    public function edit_organizer_fields($term) {
        $city = get_term_meta($term->term_id, 'mecas_organizer_city', true);
        $state = get_term_meta($term->term_id, 'mecas_organizer_state', true);
        $tagline = get_term_meta($term->term_id, 'mecas_organizer_tagline', true);
        $bio = get_term_meta($term->term_id, 'mecas_organizer_bio', true);
        $fun_fact = get_term_meta($term->term_id, 'mecas_organizer_fun_fact', true);
        $offerings = get_term_meta($term->term_id, 'mecas_organizer_offerings', true);
        $tiktok = get_term_meta($term->term_id, 'mecas_organizer_tiktok', true);
        
        $editor_settings = array(
            'textarea_rows' => 8,
            'media_buttons' => true,
            'teeny' => false,
            'quicktags' => true,
        );
        ?>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_city"><?php esc_html_e('City', 'mec-starter-addons'); ?></label></th>
            <td>
                <input type="text" name="mecas_organizer_city" id="mecas_organizer_city" value="<?php echo esc_attr($city); ?>">
                <p class="description"><?php esc_html_e('City where the organizer is located', 'mec-starter-addons'); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_state"><?php esc_html_e('State', 'mec-starter-addons'); ?></label></th>
            <td>
                <input type="text" name="mecas_organizer_state" id="mecas_organizer_state" value="<?php echo esc_attr($state); ?>">
                <p class="description"><?php esc_html_e('State/Region (e.g., FL, NY)', 'mec-starter-addons'); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_tagline"><?php esc_html_e('Tagline', 'mec-starter-addons'); ?></label></th>
            <td>
                <textarea name="mecas_organizer_tagline" id="mecas_organizer_tagline" rows="3"><?php echo esc_textarea($tagline); ?></textarea>
                <p class="description"><?php esc_html_e('A short tagline or description for the organizer', 'mec-starter-addons'); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_bio"><?php esc_html_e('Bio', 'mec-starter-addons'); ?></label></th>
            <td>
                <?php wp_editor($bio, 'mecas_organizer_bio', $editor_settings); ?>
                <p class="description"><?php esc_html_e('Full biography of the organizer', 'mec-starter-addons'); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_fun_fact"><?php esc_html_e('Fun Fact', 'mec-starter-addons'); ?></label></th>
            <td>
                <?php wp_editor($fun_fact, 'mecas_organizer_fun_fact', array_merge($editor_settings, array('textarea_rows' => 5))); ?>
                <p class="description"><?php esc_html_e('An interesting fun fact about the organizer', 'mec-starter-addons'); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_offerings"><?php esc_html_e('Offerings', 'mec-starter-addons'); ?></label></th>
            <td>
                <?php wp_editor($offerings, 'mecas_organizer_offerings', $editor_settings); ?>
                <p class="description"><?php esc_html_e('List of offerings/services the organizer provides', 'mec-starter-addons'); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_tiktok"><?php esc_html_e('TikTok', 'mec-starter-addons'); ?></label></th>
            <td>
                <input type="url" name="mecas_organizer_tiktok" id="mecas_organizer_tiktok" value="<?php echo esc_url($tiktok); ?>">
                <p class="description"><?php esc_html_e('TikTok profile URL', 'mec-starter-addons'); ?></p>
            </td>
        </tr>
        <?php
    }

    public function save_organizer_fields($term_id) {
        if (isset($_POST['mecas_organizer_city'])) {
            update_term_meta($term_id, 'mecas_organizer_city', sanitize_text_field($_POST['mecas_organizer_city']));
        }
        if (isset($_POST['mecas_organizer_state'])) {
            update_term_meta($term_id, 'mecas_organizer_state', sanitize_text_field($_POST['mecas_organizer_state']));
        }
        if (isset($_POST['mecas_organizer_tagline'])) {
            update_term_meta($term_id, 'mecas_organizer_tagline', sanitize_textarea_field($_POST['mecas_organizer_tagline']));
        }
        if (isset($_POST['mecas_organizer_bio'])) {
            update_term_meta($term_id, 'mecas_organizer_bio', wp_kses_post($_POST['mecas_organizer_bio']));
        }
        if (isset($_POST['mecas_organizer_fun_fact'])) {
            update_term_meta($term_id, 'mecas_organizer_fun_fact', wp_kses_post($_POST['mecas_organizer_fun_fact']));
        }
        if (isset($_POST['mecas_organizer_offerings'])) {
            update_term_meta($term_id, 'mecas_organizer_offerings', wp_kses_post($_POST['mecas_organizer_offerings']));
        }
        if (isset($_POST['mecas_organizer_tiktok'])) {
            update_term_meta($term_id, 'mecas_organizer_tiktok', esc_url_raw($_POST['mecas_organizer_tiktok']));
        }
    }

    // ========================================
    // FEATURED EVENT META BOX
    // ========================================

    public function add_featured_meta_box() {
        add_meta_box(
            'mecas_featured_event',
            __('Featured Event', 'mec-starter-addons'),
            array($this, 'render_featured_meta_box'),
            'mec-events',
            'side',
            'high'
        );
    }

    public function render_featured_meta_box($post) {
        wp_nonce_field('mecas_featured_nonce', 'mecas_featured_nonce_field');
        $is_featured = get_post_meta($post->ID, '_mecas_featured', true);
        ?>
        <p>
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="mecas_featured" value="1" <?php checked($is_featured, '1'); ?> style="width: 18px; height: 18px;">
                <span style="display: flex; align-items: center; gap: 5px;">
                    <span class="dashicons dashicons-star-filled" style="color: <?php echo $is_featured ? '#f0b849' : '#ccc'; ?>;"></span>
                    <?php esc_html_e('Mark as Featured Event', 'mec-starter-addons'); ?>
                </span>
            </label>
        </p>
        <p class="description"><?php esc_html_e('Featured events will appear in the Featured Events widget.', 'mec-starter-addons'); ?></p>
        <?php
    }

    public function save_featured_meta($post_id) {
        if (!isset($_POST['mecas_featured_nonce_field']) || !wp_verify_nonce($_POST['mecas_featured_nonce_field'], 'mecas_featured_nonce')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        
        $is_featured = isset($_POST['mecas_featured']) ? '1' : '0';
        update_post_meta($post_id, '_mecas_featured', $is_featured);
    }

    /**
     * Render search form - Original Dark Design
     */
    public function render_search_form($atts) {
        $atts = shortcode_atts(array(
            'results_page' => get_option('mecas_results_page', ''),
            'enable_geolocation' => 'true',
            'auto_detect_location' => 'true',
            'placeholder_search' => __('Search Teachers or Events', 'mec-starter-addons'),
            'placeholder_location' => __('City, State', 'mec-starter-addons'),
            'mode' => 'inline',
            'show_divider' => 'true',
            'trigger_text' => __('Search Events', 'mec-starter-addons'),
            'trigger_icon' => 'true',
            'popup_title' => __('Find Events', 'mec-starter-addons'),
            'popup_show_title' => 'true',
            'popup_close_on_backdrop' => 'true',
            'popup_animation' => 'fade-scale',
            'show_suggestions' => 'true',
            'mobile_mode' => 'icon_popup',
            'mobile_breakpoint' => '768',
            'widget_id' => 'mecas-' . uniqid(),
        ), $atts);
        
        $widget_id = esc_attr($atts['widget_id']);
        $mobile_mode = $atts['mobile_mode'];
        $mobile_breakpoint = intval($atts['mobile_breakpoint']);
        $popup_animation = $atts['popup_animation'];
        $popup_show_title = $atts['popup_show_title'] === 'true';
        $popup_close_on_backdrop = $atts['popup_close_on_backdrop'] === 'true';
        
        ob_start();
        
        // Always output the popup for mobile mode
        if ($mobile_mode === 'icon_popup' || $atts['mode'] === 'popup') {
            ?>
            <!-- Mobile Trigger (shown only on mobile when mobile_mode is icon_popup) -->
            <?php if ($mobile_mode === 'icon_popup' && $atts['mode'] === 'inline'): ?>
            <style>
                #<?php echo $widget_id; ?>-mobile-trigger { display: none; }
                @media (max-width: <?php echo $mobile_breakpoint; ?>px) {
                    #<?php echo $widget_id; ?>-mobile-trigger { display: flex !important; }
                    #<?php echo $widget_id; ?> { display: none !important; }
                }
            </style>
            <button type="button" class="mecas-mobile-trigger" id="<?php echo $widget_id; ?>-mobile-trigger" data-modal="<?php echo $widget_id; ?>-modal" onclick="console.log('Mobile trigger clicked via onclick');">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            </button>
            <?php endif; ?>
            
            <!-- Desktop Trigger (for popup mode) -->
            <?php if ($atts['mode'] === 'popup'): ?>
            <div class="mecas-trigger-wrapper" id="<?php echo $widget_id; ?>-trigger-wrapper">
                <button type="button" class="mecas-trigger-button" id="<?php echo $widget_id; ?>-trigger" data-modal="<?php echo $widget_id; ?>-modal" onclick="console.log('Desktop trigger clicked via onclick');">
                    <?php if ($atts['trigger_icon'] === 'true'): ?>
                    <svg class="mecas-trigger-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <?php endif; ?>
                    <?php if ($atts['trigger_text']): ?><span class="mecas-trigger-text"><?php echo esc_html($atts['trigger_text']); ?></span><?php endif; ?>
                </button>
            </div>
            <?php endif; ?>
            
            <!-- Popup Modal -->
            <div class="mecas-modal-overlay" id="<?php echo $widget_id; ?>-modal" data-animation="<?php echo esc_attr($popup_animation); ?>" data-close-on-backdrop="<?php echo $popup_close_on_backdrop ? 'true' : 'false'; ?>">
                <div class="mecas-modal-backdrop"></div>
                <div class="mecas-modal-content mecas-modal-<?php echo esc_attr($popup_animation); ?>">
                    <button type="button" class="mecas-modal-close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                    <?php if ($popup_show_title && $atts['popup_title']): ?><h3 class="mecas-modal-title"><?php echo esc_html($atts['popup_title']); ?></h3><?php endif; ?>
                    <?php echo $this->render_search_bar($atts, $widget_id . '-modal'); ?>
                </div>
            </div>
            <?php
        }
        
        // Output inline search bar (hidden on mobile if mobile_mode is icon_popup)
        if ($atts['mode'] === 'inline') {
            echo $this->render_search_bar($atts, $widget_id);
        }
        
        return ob_get_clean();
    }

    /**
     * Render the search bar HTML
     */
    private function render_search_bar($atts, $form_id) {
        $auto_detect = ($atts['enable_geolocation'] === 'true' && $atts['auto_detect_location'] === 'true') ? 'true' : 'false';
        $show_divider = $atts['show_divider'] === 'true';
        ob_start();
        ?>
        <div class="mecas-search-wrapper" id="<?php echo esc_attr($form_id); ?>" data-enable-geolocation="<?php echo esc_attr($atts['enable_geolocation']); ?>" data-auto-detect="<?php echo esc_attr($auto_detect); ?>">
            <form class="mecas-search-form" action="<?php echo esc_url($atts['results_page']); ?>" method="GET">
                <div class="mecas-search-container">
                    <div class="mecas-input-group mecas-search-input-group">
                        <input type="text" name="mecas_query" class="mecas-input mecas-query-input" placeholder="<?php echo esc_attr($atts['placeholder_search']); ?>" autocomplete="off">
                        <?php if ($atts['show_suggestions'] === 'true'): ?>
                        <div class="mecas-suggestions mecas-query-suggestions"></div>
                        <?php endif; ?>
                    </div>
                    <?php if ($show_divider): ?>
                    <div class="mecas-divider"></div>
                    <?php endif; ?>
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
            'placeholder_search' => __('Search events', 'mec-starter-addons'),
            'placeholder_location' => __('City, State', 'mec-starter-addons'),
            'show_divider' => 'true',
            'show_filters' => 'true',
            'show_category_filter' => 'true',
            'show_organizer_filter' => 'true',
            'show_tag_filter' => 'true',
            'show_sort_filter' => 'true',
            'label_category' => __('All Categories', 'mec-starter-addons'),
            'label_organizer' => __('All Organizers', 'mec-starter-addons'),
            'label_tag' => __('All Tags', 'mec-starter-addons'),
            'label_sort' => __('Sort By', 'mec-starter-addons'),
            'columns' => '4',
            'per_page' => '12',
            'show_pagination' => 'true',
            'no_results_text' => __('No events found.', 'mec-starter-addons'),
            'date_format' => 'D, M j',
            'time_format' => 'g:i A T',
            'hosted_by_text' => __('Hosted by', 'mec-starter-addons'),
            'currency_symbol' => '$',
            'widget_id' => 'mecas-results-' . uniqid(),
        ), $atts);
        
        $query = isset($_GET['mecas_query']) ? sanitize_text_field($_GET['mecas_query']) : '';
        $location = isset($_GET['mecas_location']) ? sanitize_text_field($_GET['mecas_location']) : '';
        $category = isset($_GET['mecas_category']) ? sanitize_text_field($_GET['mecas_category']) : '';
        $organizer = isset($_GET['mecas_organizer']) ? sanitize_text_field($_GET['mecas_organizer']) : '';
        $tag = isset($_GET['mecas_tag']) ? sanitize_text_field($_GET['mecas_tag']) : '';
        $sort = isset($_GET['mecas_sort']) ? sanitize_text_field($_GET['mecas_sort']) : 'date_asc';
        $paged = isset($_GET['mecas_page']) ? max(1, intval($_GET['mecas_page'])) : 1;
        
        $categories = $this->get_mec_categories();
        $organizers = $this->get_mec_organizers();
        $tags = $this->get_event_tags();
        
        $results = $this->search_events_with_filters($query, $location, $category, '', $organizer, $tag, intval($atts['per_page']), $paged, $sort);
        
        ob_start();
        include MECAS_PLUGIN_DIR . 'templates/search-results.php';
        return ob_get_clean();
    }

    /**
     * Render featured events
     */
    public function render_featured_events($atts) {
        $atts = shortcode_atts(array(
            'columns' => '4',
            'columns_tablet' => '2',
            'columns_mobile' => '1',
            'per_page' => '8',
            'date_format' => 'D, M j',
            'time_format' => 'g:i A T',
            'hosted_by_text' => __('Hosted by', 'mec-starter-addons'),
            'currency_symbol' => '$',
            'show_price' => 'true',
            'show_category_tabs' => 'true',
            'all_tab_text' => __('All', 'mec-starter-addons'),
            'widget_id' => 'mecas-featured-' . uniqid(),
        ), $atts);
        
        // Get categories for tabs
        $categories = $this->get_mec_categories();
        
        $args = array(
            'post_type' => 'mec-events',
            'post_status' => 'publish',
            'posts_per_page' => intval($atts['per_page']),
            'meta_query' => array(
                array(
                    'key' => '_mecas_featured',
                    'value' => '1',
                    'compare' => '='
                )
            ),
            'orderby' => 'meta_value',
            'meta_key' => 'mec_start_date',
            'order' => 'ASC',
        );
        
        $events = get_posts($args);
        
        ob_start();
        include MECAS_PLUGIN_DIR . 'templates/featured-events.php';
        return ob_get_clean();
    }

    /**
     * Render upcoming events grid
     */
    public function render_upcoming_events($atts) {
        $atts = shortcode_atts(array(
            'columns' => '4',
            'columns_tablet' => '2',
            'columns_mobile' => '1',
            'per_page' => '8',
            'hide_past_events' => 'true',
            'date_format' => 'D, M j',
            'time_format' => 'g:i A T',
            'hosted_by_text' => __('Hosted by', 'mec-starter-addons'),
            'currency_symbol' => '$',
            'show_price' => 'true',
            'show_category_tabs' => 'true',
            'all_tab_text' => __('All', 'mec-starter-addons'),
            'widget_id' => 'mecas-upcoming-' . uniqid(),
        ), $atts);
        
        // Get categories for tabs
        $categories = $this->get_mec_categories();
        
        $args = array(
            'post_type' => 'mec-events',
            'post_status' => 'publish',
            'posts_per_page' => intval($atts['per_page']),
            'order' => 'ASC',
        );
        
        // Build meta query
        $meta_query = array();
        
        // Hide past events if enabled - compare as string since MEC stores Y-m-d format
        if ($atts['hide_past_events'] === 'true') {
            $today = date('Y-m-d');
            $meta_query[] = array(
                'key' => 'mec_start_date',
                'value' => $today,
                'compare' => '>='
            );
        }
        
        // Add meta query for ordering
        $meta_query['order_clause'] = array(
            'key' => 'mec_start_date',
            'compare' => 'EXISTS'
        );
        
        $args['meta_query'] = $meta_query;
        $args['orderby'] = array('order_clause' => 'ASC');
        
        $events = get_posts($args);
        
        ob_start();
        include MECAS_PLUGIN_DIR . 'templates/upcoming-events.php';
        return ob_get_clean();
    }

    /**
     * Render organizers grid
     */
    public function render_organizers_grid($atts) {
        $atts = shortcode_atts(array(
            'columns' => '4',
            'per_page' => '8',
            'show_heart' => 'true',
            'widget_id' => 'mecas-organizers-' . uniqid(),
        ), $atts);
        
        $organizers = get_terms(array(
            'taxonomy' => 'mec_organizer',
            'hide_empty' => true,
            'number' => intval($atts['per_page']),
        ));
        
        ob_start();
        include MECAS_PLUGIN_DIR . 'templates/organizers-grid.php';
        return ob_get_clean();
    }

    /**
     * Render Teacher Search
     */
    public function render_teacher_search($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Search Teachers', 'mec-starter-addons'),
            'show_title' => 'true',
            'placeholder' => __('City, State', 'mec-starter-addons'),
            'enable_geolocation' => 'true',
            'auto_detect_location' => 'false',
            'show_count' => 'true',
            'count_text' => __('%d Teachers found in %s', 'mec-starter-addons'),
            'columns' => '6',
            'per_page' => '24',
            'show_pagination' => 'true',
            'no_results_text' => __('No teachers found in this area.', 'mec-starter-addons'),
            'show_location_bar' => 'true',
            'show_heart_icon' => 'true',
            'show_name' => 'true',
            'show_tagline' => 'true',
            'widget_id' => 'mecas-teacher-search-' . uniqid(),
        ), $atts);
        
        $location = isset($_GET['mecas_teacher_location']) ? sanitize_text_field($_GET['mecas_teacher_location']) : '';
        $paged = isset($_GET['mecas_teacher_page']) ? max(1, intval($_GET['mecas_teacher_page'])) : 1;
        
        $results = $this->search_teachers_by_location($location, intval($atts['per_page']), $paged);
        
        ob_start();
        include MECAS_PLUGIN_DIR . 'templates/teacher-search.php';
        return ob_get_clean();
    }

    /**
     * Search teachers/organizers by location
     */
    public function search_teachers_by_location($location = '', $per_page = 24, $paged = 1) {
        $args = array(
            'taxonomy' => 'mec_organizer',
            'hide_empty' => false,
            'number' => $per_page,
            'offset' => ($paged - 1) * $per_page,
        );
        
        // Get all organizers first to filter by location
        if ($location) {
            // Get all organizers and filter by location
            $all_organizers = get_terms(array(
                'taxonomy' => 'mec_organizer',
                'hide_empty' => false,
            ));
            
            $filtered_ids = array();
            if (!is_wp_error($all_organizers)) {
                foreach ($all_organizers as $org) {
                    $city = get_term_meta($org->term_id, 'mecas_organizer_city', true);
                    $state = get_term_meta($org->term_id, 'mecas_organizer_state', true);
                    
                    // Check if location matches city or state
                    $location_lower = strtolower($location);
                    $city_lower = strtolower($city);
                    $state_lower = strtolower($state);
                    
                    if (
                        stripos($city, $location) !== false ||
                        stripos($state, $location) !== false ||
                        stripos($location, $city) !== false ||
                        stripos($location, $state) !== false ||
                        stripos($org->name, $location) !== false
                    ) {
                        $filtered_ids[] = $org->term_id;
                    }
                }
            }
            
            if (!empty($filtered_ids)) {
                $args['include'] = $filtered_ids;
            } else {
                // No matches found
                return array(
                    'organizers' => array(),
                    'total' => 0,
                    'max_pages' => 0,
                    'current_page' => $paged
                );
            }
            
            // Recalculate for pagination
            $total = count($filtered_ids);
            $args['number'] = $per_page;
            $args['offset'] = ($paged - 1) * $per_page;
        } else {
            // Count total without location filter
            $count_args = array(
                'taxonomy' => 'mec_organizer',
                'hide_empty' => false,
                'fields' => 'count',
            );
            $total = get_terms($count_args);
        }
        
        $organizers = get_terms($args);
        
        if (is_wp_error($organizers)) {
            $organizers = array();
            $total = 0;
        }
        
        $max_pages = ceil($total / $per_page);
        
        return array(
            'organizers' => $organizers,
            'total' => $total,
            'max_pages' => $max_pages,
            'current_page' => $paged
        );
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

    public function search_events_with_filters($query = '', $location = '', $category = '', $label = '', $organizer = '', $tag = '', $per_page = 12, $paged = 1, $sort = 'date_asc') {
        $args = array(
            'post_type' => 'mec-events',
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'paged' => $paged,
        );
        
        // Handle sorting
        switch ($sort) {
            case 'date_desc':
                $args['orderby'] = 'meta_value';
                $args['meta_key'] = 'mec_start_date';
                $args['order'] = 'DESC';
                break;
            case 'price_high':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'mec_cost';
                $args['order'] = 'DESC';
                break;
            case 'price_low':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'mec_cost';
                $args['order'] = 'ASC';
                break;
            case 'title_asc':
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
                break;
            case 'title_desc':
                $args['orderby'] = 'title';
                $args['order'] = 'DESC';
                break;
            case 'date_asc':
            default:
                $args['orderby'] = 'meta_value';
                $args['meta_key'] = 'mec_start_date';
                $args['order'] = 'ASC';
                break;
        }
        
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

    /**
     * Get event data helper with full details
     */
    public function get_event_full_data($event) {
        $id = is_object($event) ? $event->ID : $event;
        
        // Date and time
        $start_date = get_post_meta($id, 'mec_start_date', true);
        $start_time_hour = get_post_meta($id, 'mec_start_time_hour', true);
        $start_time_min = get_post_meta($id, 'mec_start_time_minutes', true);
        $start_time_ampm = get_post_meta($id, 'mec_start_time_ampm', true);
        
        // Format time
        $time_string = '';
        if ($start_time_hour) {
            $time_string = $start_time_hour . ':' . str_pad($start_time_min, 2, '0', STR_PAD_LEFT) . ' ' . strtoupper($start_time_ampm);
        }
        
        // Cost
        $cost = get_post_meta($id, 'mec_cost', true);
        
        // Location
        $loc_id = get_post_meta($id, 'mec_location_id', true);
        $location_name = '';
        if ($loc_id) {
            $loc = get_term($loc_id, 'mec_location');
            if ($loc && !is_wp_error($loc)) {
                $location_name = $loc->name;
            }
        }
        
        // Organizer
        $organizer_terms = get_the_terms($id, 'mec_organizer');
        $organizer_name = '';
        $organizer_id = 0;
        if ($organizer_terms && !is_wp_error($organizer_terms)) {
            $organizer = $organizer_terms[0];
            $organizer_name = $organizer->name;
            $organizer_id = $organizer->term_id;
        }
        
        // Tags (for category badge)
        $tags = get_the_terms($id, 'post_tag');
        $tag_name = '';
        if ($tags && !is_wp_error($tags)) {
            $tag_name = $tags[0]->name;
        }
        
        // Categories
        $categories = get_the_terms($id, 'mec_category');
        $category_name = '';
        if ($categories && !is_wp_error($categories)) {
            $category_name = $categories[0]->name;
        }
        
        return array(
            'id' => $id,
            'title' => get_the_title($id),
            'url' => get_permalink($id),
            'image' => get_the_post_thumbnail_url($id, 'medium_large'),
            'date' => $start_date,
            'date_formatted' => $start_date ? date_i18n(get_option('date_format'), strtotime($start_date)) : '',
            'time' => $time_string,
            'cost' => $cost,
            'location' => $location_name,
            'organizer' => $organizer_name,
            'organizer_id' => $organizer_id,
            'tag' => $tag_name,
            'category' => $category_name,
        );
    }

    public function ajax_search() {
        check_ajax_referer('mecas_nonce', 'nonce');
        $query = isset($_POST['query']) ? sanitize_text_field($_POST['query']) : '';
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 5;
        
        $events = get_posts(array('post_type' => 'mec-events', 'post_status' => 'publish', 'posts_per_page' => $limit, 's' => $query));
        $results = array();
        foreach ($events as $event) $results[] = $this->get_event_full_data($event);
        wp_send_json_success($results);
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
        $sort = sanitize_text_field($_POST['sort'] ?? 'date_asc');
        $per_page = intval($_POST['per_page'] ?? 12);
        $paged = intval($_POST['page'] ?? 1);
        
        $results = $this->search_events_with_filters($query, $location, $category, $label, $organizer, $tag, $per_page, $paged, $sort);
        $events = array();
        foreach ($results['events'] as $e) $events[] = $this->get_event_full_data($e);
        wp_send_json_success(array('events' => $events, 'total' => $results['total'], 'max_pages' => $results['max_pages']));
    }

    public function ajax_filter_featured_events() {
        check_ajax_referer('mecas_nonce', 'nonce');
        $category = sanitize_text_field($_POST['category'] ?? '');
        $per_page = intval($_POST['per_page'] ?? 8);
        
        $args = array(
            'post_type' => 'mec-events',
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'meta_query' => array(
                array(
                    'key' => '_mecas_featured',
                    'value' => '1',
                    'compare' => '='
                )
            ),
            'orderby' => 'meta_value',
            'meta_key' => 'mec_start_date',
            'order' => 'ASC',
        );
        
        // Filter by category if provided
        if ($category) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'mec_category',
                    'field' => 'slug',
                    'terms' => $category
                )
            );
        }
        
        $events_posts = get_posts($args);
        $events = array();
        foreach ($events_posts as $e) $events[] = $this->get_event_full_data($e);
        wp_send_json_success(array('events' => $events, 'total' => count($events)));
    }

    /**
     * AJAX handler for filtering upcoming events
     */
    public function ajax_filter_upcoming_events() {
        check_ajax_referer('mecas_nonce', 'nonce');
        $category = sanitize_text_field($_POST['category'] ?? '');
        $per_page = intval($_POST['per_page'] ?? 8);
        $hide_past = sanitize_text_field($_POST['hide_past'] ?? 'true');
        
        $args = array(
            'post_type' => 'mec-events',
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'order' => 'ASC',
        );
        
        // Build meta query
        $meta_query = array();
        
        // Hide past events if enabled
        if ($hide_past === 'true') {
            $today = date('Y-m-d');
            $meta_query[] = array(
                'key' => 'mec_start_date',
                'value' => $today,
                'compare' => '>='
            );
        }
        
        // Add meta query for ordering
        $meta_query['order_clause'] = array(
            'key' => 'mec_start_date',
            'compare' => 'EXISTS'
        );
        
        $args['meta_query'] = $meta_query;
        $args['orderby'] = array('order_clause' => 'ASC');
        
        // Filter by category if provided
        if ($category) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'mec_category',
                    'field' => 'slug',
                    'terms' => $category
                )
            );
        }
        
        $events_posts = get_posts($args);
        $events = array();
        foreach ($events_posts as $e) $events[] = $this->get_event_full_data($e);
        wp_send_json_success(array('events' => $events, 'total' => count($events)));
    }

    /**
     * AJAX handler for teacher search
     */
    public function ajax_search_teachers() {
        check_ajax_referer('mecas_nonce', 'nonce');
        $location = sanitize_text_field($_POST['location'] ?? '');
        $per_page = intval($_POST['per_page'] ?? 24);
        $page = intval($_POST['page'] ?? 1);
        
        $results = $this->search_teachers_by_location($location, $per_page, $page);
        
        $teachers = array();
        foreach ($results['organizers'] as $org) {
            $term_id = $org->term_id;
            // MEC stores thumbnail as direct URL, not attachment ID
            $image_url = get_term_meta($term_id, 'thumbnail', true);
            $city = get_term_meta($term_id, 'mecas_organizer_city', true);
            $state = get_term_meta($term_id, 'mecas_organizer_state', true);
            $tagline = get_term_meta($term_id, 'mecas_organizer_tagline', true);
            
            $location_display = '';
            if ($city) {
                $location_display = $city;
                if ($state) $location_display .= ', ' . $state;
            }
            
            $teachers[] = array(
                'id' => $term_id,
                'name' => $org->name,
                'url' => get_term_link($org),
                'image' => $image_url,
                'location' => $location_display,
                'tagline' => $tagline,
            );
        }
        
        wp_send_json_success(array(
            'teachers' => $teachers,
            'total' => $results['total'],
            'max_pages' => $results['max_pages'],
            'current_page' => $results['current_page']
        ));
    }

    /**
     * Search events by location (AJAX handler)
     */
    public function ajax_search_events_by_location() {
        check_ajax_referer('mecas_nonce', 'nonce');
        
        $location = sanitize_text_field($_POST['location'] ?? '');
        $per_page = intval($_POST['per_page'] ?? 12);
        $page = intval($_POST['page'] ?? 1);
        
        $results = $this->search_events_by_location($location, $per_page, $page);
        
        $events = array();
        foreach ($results['events'] as $event) {
            $event_id = $event->ID;
            $start_date = get_post_meta($event_id, 'mec_start_date', true);
            $location_id = get_post_meta($event_id, 'mec_location_id', true);
            $location_name = '';
            
            if ($location_id) {
                $loc = get_term($location_id, 'mec_location');
                if ($loc && !is_wp_error($loc)) {
                    $location_name = $loc->name;
                }
            }
            
            $events[] = array(
                'id' => $event_id,
                'title' => get_the_title($event),
                'url' => get_permalink($event_id),
                'image' => get_the_post_thumbnail_url($event_id, 'medium'),
                'date' => $start_date ? date_i18n(get_option('date_format'), strtotime($start_date)) : '',
                'location' => $location_name,
            );
        }
        
        wp_send_json_success(array(
            'events' => $events,
            'total' => $results['total'],
            'max_pages' => $results['max_pages'],
            'current_page' => $results['current_page']
        ));
    }

    /**
     * Search events by location
     */
    private function search_events_by_location($location, $per_page = 12, $page = 1) {
        $args = array(
            'post_type' => 'mec-events',
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'paged' => $page,
            'orderby' => 'meta_value',
            'meta_key' => 'mec_start_date',
            'order' => 'ASC',
        );
        
        // Filter by location if provided
        if ($location) {
            $location_ids = $this->get_location_ids_by_city($location);
            if (!empty($location_ids)) {
                $args['meta_query'] = array(
                    array(
                        'key' => 'mec_location_id',
                        'value' => $location_ids,
                        'compare' => 'IN'
                    )
                );
            } else {
                // No matching locations found - return empty
                return array(
                    'events' => array(),
                    'total' => 0,
                    'max_pages' => 0,
                    'current_page' => $page
                );
            }
        }
        
        $query = new WP_Query($args);
        
        return array(
            'events' => $query->posts,
            'total' => $query->found_posts,
            'max_pages' => $query->max_num_pages,
            'current_page' => $page
        );
    }

    /**
     * Add Featured column to MEC events list
     */
    public function add_featured_column($columns) {
        $new_columns = array();
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            // Add Featured column after Title
            if ($key === 'title') {
                $new_columns['mecas_featured'] = __('Featured', 'mec-starter-addons');
            }
        }
        return $new_columns;
    }

    /**
     * Render Featured column content with toggle switch
     */
    public function render_featured_column($column, $post_id) {
        if ($column !== 'mecas_featured') return;
        
        $is_featured = get_post_meta($post_id, '_mecas_featured', true) === '1';
        $checked = $is_featured ? 'checked' : '';
        ?>
        <label class="mecas-featured-toggle">
            <input type="checkbox" 
                   class="mecas-featured-checkbox" 
                   data-post-id="<?php echo esc_attr($post_id); ?>" 
                   <?php echo $checked; ?>>
            <span class="mecas-toggle-slider"></span>
        </label>
        <?php
    }

    /**
     * AJAX handler to toggle featured status
     */
    public function ajax_toggle_featured() {
        check_ajax_referer('mecas_admin_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => 'Permission denied'));
        }
        
        $post_id = intval($_POST['post_id'] ?? 0);
        $featured = sanitize_text_field($_POST['featured'] ?? '0');
        
        if (!$post_id) {
            wp_send_json_error(array('message' => 'Invalid post ID'));
        }
        
        update_post_meta($post_id, '_mecas_featured', $featured === '1' ? '1' : '0');
        
        wp_send_json_success(array(
            'post_id' => $post_id,
            'featured' => $featured === '1'
        ));
    }

    public function register_elementor_widgets($widgets_manager) {
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-search-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-results-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-featured-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-upcoming-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-organizers-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-teacher-search-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-events-location-search-widget.php';
        
        // Organizer Profile Widgets
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-organizer-profile-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-organizer-name-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-organizer-bio-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-organizer-fun-fact-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-organizer-offerings-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-organizer-social-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-organizer-events-widget.php';
        
        $widgets_manager->register(new \MECAS_Search_Widget());
        $widgets_manager->register(new \MECAS_Results_Widget());
        $widgets_manager->register(new \MECAS_Featured_Widget());
        $widgets_manager->register(new \MECAS_Upcoming_Widget());
        $widgets_manager->register(new \MECAS_Organizers_Widget());
        $widgets_manager->register(new \MECAS_Teacher_Search_Widget());
        $widgets_manager->register(new \MECAS_Events_Location_Search_Widget());
        
        // Organizer Profile Widgets
        $widgets_manager->register(new \MECAS_Organizer_Profile_Widget());
        $widgets_manager->register(new \MECAS_Organizer_Name_Widget());
        $widgets_manager->register(new \MECAS_Organizer_Bio_Widget());
        $widgets_manager->register(new \MECAS_Organizer_Fun_Fact_Widget());
        $widgets_manager->register(new \MECAS_Organizer_Offerings_Widget());
        $widgets_manager->register(new \MECAS_Organizer_Social_Widget());
        $widgets_manager->register(new \MECAS_Organizer_Events_Widget());
    }

    public function add_elementor_category($elements_manager) {
        $elements_manager->add_category('mec-starter-addons', array('title' => __('MEC Starter Addons', 'mec-starter-addons'), 'icon' => 'fa fa-calendar'));
    }

    public function add_admin_menu() {
        add_menu_page(__('MEC Addons', 'mec-starter-addons'), __('MEC Addons', 'mec-starter-addons'), 'manage_options', 'mec-starter-addons', array($this, 'render_admin_page'), 'dashicons-calendar-alt', 30);
    }

    public function register_settings() {
        register_setting('mecas_settings', 'mecas_results_page');
        register_setting('mecas_settings', 'mecas_enable_geolocation');
    }

    public function render_admin_page() {
        include MECAS_PLUGIN_DIR . 'templates/admin-settings.php';
    }
}

/**
 * Get organizer data by ID
 * Helper function to retrieve all organizer metadata
 */
function mecas_get_organizer_data($organizer_id) {
    $term = get_term($organizer_id, 'mec_organizer');
    
    if (!$term || is_wp_error($term)) {
        return null;
    }
    
    // Get MEC default fields
    $thumbnail = get_term_meta($organizer_id, 'thumbnail', true);
    $tel = get_term_meta($organizer_id, 'tel', true);
    $email = get_term_meta($organizer_id, 'email', true);
    $page_url = get_term_meta($organizer_id, 'url', true);
    $page_label = get_term_meta($organizer_id, 'page_label', true);
    $facebook = get_term_meta($organizer_id, 'facebook', true);
    $instagram = get_term_meta($organizer_id, 'instagram', true);
    $linkedin = get_term_meta($organizer_id, 'linkedin', true);
    $twitter = get_term_meta($organizer_id, 'twitter', true);
    
    // Get custom MECAS fields
    $city = get_term_meta($organizer_id, 'mecas_organizer_city', true);
    $state = get_term_meta($organizer_id, 'mecas_organizer_state', true);
    $tagline = get_term_meta($organizer_id, 'mecas_organizer_tagline', true);
    $bio = get_term_meta($organizer_id, 'mecas_organizer_bio', true);
    $fun_fact = get_term_meta($organizer_id, 'mecas_organizer_fun_fact', true);
    $offerings = get_term_meta($organizer_id, 'mecas_organizer_offerings', true);
    $tiktok = get_term_meta($organizer_id, 'mecas_organizer_tiktok', true);
    
    // Build location string
    $location = $city;
    if ($state) {
        $location .= $city ? ', ' . $state : $state;
    }
    
    // Get organizer archive URL
    $url = get_term_link($term, 'mec_organizer');
    if (is_wp_error($url)) {
        $url = '';
    }
    
    return array(
        'id' => $organizer_id,
        'name' => $term->name,
        'slug' => $term->slug,
        'description' => $term->description,
        'url' => $url,
        'thumbnail' => $thumbnail,
        'city' => $city,
        'state' => $state,
        'location' => $location,
        'tagline' => $tagline,
        'bio' => $bio,
        'fun_fact' => $fun_fact,
        'offerings' => $offerings,
        'tel' => $tel,
        'email' => $email,
        'page_url' => $page_url,
        'page_label' => $page_label,
        'facebook' => $facebook,
        'instagram' => $instagram,
        'linkedin' => $linkedin,
        'twitter' => $twitter,
        'tiktok' => $tiktok,
    );
}

MEC_Starter_Addons::get_instance();
