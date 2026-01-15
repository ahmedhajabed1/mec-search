<?php
/**
 * Plugin Name: MEC Starter Addons
 * Plugin URI: https://themajhhub.com
 * Description: Advanced Elementor widgets and features for Modern Events Calendar including search, organizer profiles, customer registration, dashboard, and more
 * Version: 5.8.1
 * Author: Ahmed Haj Abed
 * Author URI: https://themajhhub.com
 * License: GPL v2 or later
 * Text Domain: mec-starter-addons
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) exit;

define('MECAS_VERSION', '5.8.1');
define('MECAS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MECAS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MECAS_PLUGIN_BASENAME', plugin_basename(__FILE__));

class MEC_Starter_Addons {
    private static $instance = null;
    
    // Customer role constant
    const CUSTOMER_ROLE = 'mec_customer';

    public static function get_instance() {
        if (null === self::$instance) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        // Activation/Deactivation
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        add_action('plugins_loaded', array($this, 'check_mec_dependency'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Hide admin bar for customers and organizers
        add_action('after_setup_theme', array($this, 'hide_admin_bar_for_users'));
        
        // Custom avatar from user meta
        add_filter('get_avatar_url', array($this, 'custom_avatar_url'), 10, 3);
        
        // Shortcodes
        add_shortcode('mec_advanced_search', array($this, 'render_search_form'));
        add_shortcode('mec_search_results', array($this, 'render_search_results'));
        add_shortcode('mec_featured_events', array($this, 'render_featured_events'));
        add_shortcode('mec_upcoming_events', array($this, 'render_upcoming_events'));
        add_shortcode('mec_organizers_grid', array($this, 'render_organizers_grid'));
        add_shortcode('mec_teacher_search', array($this, 'render_teacher_search'));
        
        // Search AJAX handlers
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
        
        // Customer/User AJAX handlers
        add_action('wp_ajax_nopriv_mecas_register_customer', array($this, 'ajax_register_customer'));
        add_action('wp_ajax_mecas_register_customer', array($this, 'ajax_register_customer'));
        add_action('wp_ajax_nopriv_mecas_send_sms_code', array($this, 'ajax_send_sms_code'));
        add_action('wp_ajax_mecas_send_sms_code', array($this, 'ajax_send_sms_code'));
        add_action('wp_ajax_nopriv_mecas_verify_sms_code', array($this, 'ajax_verify_sms_code'));
        add_action('wp_ajax_mecas_verify_sms_code', array($this, 'ajax_verify_sms_code'));
        add_action('wp_ajax_mecas_complete_profile', array($this, 'ajax_complete_profile'));
        add_action('wp_ajax_nopriv_mecas_user_login', array($this, 'ajax_user_login'));
        add_action('wp_ajax_mecas_user_login', array($this, 'ajax_user_login'));
        add_action('wp_ajax_mecas_save_dashboard_profile', array($this, 'ajax_save_dashboard_profile'));
        add_action('wp_ajax_mecas_save_dashboard', array($this, 'ajax_save_dashboard'));
        add_action('wp_ajax_mecas_load_dashboard', array($this, 'ajax_load_dashboard'));
        add_action('wp_ajax_mecas_save_event', array($this, 'ajax_save_event'));
        add_action('wp_ajax_mecas_unsave_event', array($this, 'ajax_unsave_event'));
        add_action('wp_ajax_mecas_check_saved_events', array($this, 'ajax_check_saved_events'));
        add_action('wp_ajax_mecas_follow_organizer', array($this, 'ajax_follow_organizer'));
        add_action('wp_ajax_mecas_unfollow_organizer', array($this, 'ajax_unfollow_organizer'));
        add_action('wp_ajax_mecas_check_following', array($this, 'ajax_check_following'));
        add_action('wp_ajax_mecas_update_profile', array($this, 'ajax_update_profile'));
        
        // Admin
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Elementor
        add_action('elementor/widgets/register', array($this, 'register_elementor_widgets'));
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_category'));
        
        // Elementor Pro Theme Builder support
        add_action('elementor/theme/register_conditions', array($this, 'register_organizer_conditions'));
        add_action('elementor/dynamic_tags/register', array($this, 'register_dynamic_tags'));
        add_filter('elementor/theme/need_override_location', array($this, 'override_organizer_location'), 10, 2);
        add_action('init', array($this, 'ensure_organizer_taxonomy_public'), 20);
        
        // Override MEC single event template for Elementor - use very high priority
        add_filter('single_template', array($this, 'override_mec_single_template'), 9999);
        add_filter('template_include', array($this, 'force_elementor_template'), 9999);
        
        // Hook earlier to intercept MEC
        add_action('wp', array($this, 'maybe_disable_mec_single_content'), 1);
        
        // Organizer meta fields
        add_action('mec_organizer_add_form_fields', array($this, 'add_organizer_fields'));
        add_action('mec_organizer_edit_form_fields', array($this, 'edit_organizer_fields'));
        add_action('created_mec_organizer', array($this, 'save_organizer_fields'));
        add_action('edited_mec_organizer', array($this, 'save_organizer_fields'));
        
        // Featured event meta box
        add_action('add_meta_boxes', array($this, 'add_featured_meta_box'));
        add_action('save_post_mec-events', array($this, 'save_featured_meta'));
        
        // Featured column
        add_filter('manage_mec-events_posts_columns', array($this, 'add_featured_column'));
        add_action('manage_mec-events_posts_custom_column', array($this, 'render_featured_column'), 10, 2);
        add_action('wp_ajax_mecas_toggle_featured', array($this, 'ajax_toggle_featured'));
        
        // Event Gallery metabox
        add_action('add_meta_boxes', array($this, 'add_gallery_metabox'));
        add_action('save_post_mec-events', array($this, 'save_gallery_metabox'));
        
        // Customer login redirect
        add_filter('login_redirect', array($this, 'customer_login_redirect'), 10, 3);
    }

    // ========================================
    // ACTIVATION / DEACTIVATION
    // ========================================
    
    public function activate() {
        $this->create_customer_role();
        $this->create_tables();
        flush_rewrite_rules();
        
        // Default options
        add_option('mecas_registration_enabled', 'yes');
        add_option('mecas_require_sms_verification', 'yes');
        add_option('mecas_dashboard_page', '');
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    private function create_customer_role() {
        $capabilities = array(
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false,
            'publish_posts' => false,
            'upload_files' => true,
        );
        add_role(self::CUSTOMER_ROLE, __('MEC Customer', 'mec-starter-addons'), $capabilities);
    }
    
    private function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // Saved events table
        $table_saved = $wpdb->prefix . 'mecas_saved_events';
        $sql_saved = "CREATE TABLE IF NOT EXISTS $table_saved (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            event_id bigint(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY user_event (user_id, event_id),
            KEY user_id (user_id),
            KEY event_id (event_id)
        ) $charset_collate;";
        dbDelta($sql_saved);
        
        // Following organizers table
        $table_following = $wpdb->prefix . 'mecas_following';
        $sql_following = "CREATE TABLE IF NOT EXISTS $table_following (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            organizer_id bigint(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY user_organizer (user_id, organizer_id),
            KEY user_id (user_id),
            KEY organizer_id (organizer_id)
        ) $charset_collate;";
        dbDelta($sql_following);
    }

    // ========================================
    // ELEMENTOR PRO THEME BUILDER
    // ========================================

    public function register_organizer_conditions($conditions_manager) {
        if (!class_exists('\ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base')) {
            return;
        }
        
        // Register Organizer Archive Condition
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/conditions/class-organizer-archive-condition.php';
        if (class_exists('MECAS_Organizer_Archive_Condition')) {
            $archive = $conditions_manager->get_condition('archive');
            if ($archive) {
                $archive->register_sub_condition(new \MECAS_Organizer_Archive_Condition());
            }
        }
        
        // Register Single MEC Event Condition
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/conditions/class-single-event-condition.php';
        if (class_exists('MECAS_Single_Event_Condition')) {
            $singular = $conditions_manager->get_condition('singular');
            if ($singular) {
                $singular->register_sub_condition(new \MECAS_Single_Event_Condition());
            }
        }
    }

    public function override_organizer_location($need_override, $location) {
        // Override for organizer archive pages
        if (is_tax('mec_organizer') && $location === 'archive') {
            return true;
        }
        // Override for single MEC event pages
        if (is_singular('mec-events') && $location === 'single') {
            return true;
        }
        return $need_override;
    }
    
    /**
     * Override MEC single template when Elementor Pro has a template
     */
    public function override_mec_single_template($template) {
        if (!is_singular('mec-events')) {
            return $template;
        }
        
        // Check if override is enabled
        if (get_option('mecas_override_single_event', 'yes') !== 'yes') {
            return $template;
        }
        
        // Check if Elementor Pro Theme Builder is active
        if (!class_exists('\ElementorPro\Modules\ThemeBuilder\Module')) {
            return $template;
        }
        
        // Get Elementor's canvas or theme template
        $elementor_template = ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php';
        if (file_exists($elementor_template)) {
            return $elementor_template;
        }
        
        return $template;
    }
    
    /**
     * Force Elementor template - highest priority
     */
    public function force_elementor_template($template) {
        if (!is_singular('mec-events')) {
            return $template;
        }
        
        // Check if override is enabled
        if (get_option('mecas_override_single_event', 'yes') !== 'yes') {
            return $template;
        }
        
        // Check if Elementor Pro can handle this location
        if (!function_exists('elementor_theme_do_location')) {
            return $template;
        }
        
        // Check if there's a template for single
        if (!class_exists('\ElementorPro\Modules\ThemeBuilder\Module')) {
            return $template;
        }
        
        $conditions_manager = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager();
        $documents = $conditions_manager->get_documents_for_location('single');
        
        if (empty($documents)) {
            return $template;
        }
        
        // Return Elementor's header-footer template which allows theme builder to work
        $elementor_template = ELEMENTOR_PATH . 'modules/page-templates/templates/header-footer.php';
        if (file_exists($elementor_template)) {
            return $elementor_template;
        }
        
        // Fallback to canvas
        $canvas_template = ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php';
        if (file_exists($canvas_template)) {
            return $canvas_template;
        }
        
        return $template;
    }
    
    /**
     * Disable MEC's single event content when Elementor has a template
     */
    public function maybe_disable_mec_single_content() {
        if (!is_singular('mec-events')) {
            return;
        }
        
        // Check if override is enabled
        if (get_option('mecas_override_single_event', 'yes') !== 'yes') {
            return;
        }
        
        if (!class_exists('\ElementorPro\Modules\ThemeBuilder\Module')) {
            return;
        }
        
        $conditions_manager = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager();
        $documents = $conditions_manager->get_documents_for_location('single');
        
        if (empty($documents)) {
            return;
        }
        
        // Remove ALL the_content filters to prevent MEC from injecting content
        remove_all_filters('the_content');
        
        // Re-add only wpautop and basic formatting
        add_filter('the_content', 'wpautop');
        add_filter('the_content', 'wptexturize');
        add_filter('the_content', 'shortcode_unautop');
        add_filter('the_content', 'do_shortcode', 11);
    }

    public function ensure_organizer_taxonomy_public() {
        global $wp_taxonomies;
        if (isset($wp_taxonomies['mec_organizer'])) {
            $wp_taxonomies['mec_organizer']->public = true;
            $wp_taxonomies['mec_organizer']->publicly_queryable = true;
            $wp_taxonomies['mec_organizer']->show_ui = true;
            $wp_taxonomies['mec_organizer']->show_in_nav_menus = true;
            if (empty($wp_taxonomies['mec_organizer']->rewrite)) {
                $wp_taxonomies['mec_organizer']->rewrite = array('slug' => 'organizer', 'with_front' => false);
            }
        }
    }

    public function register_dynamic_tags($dynamic_tags_manager) {
        $dynamic_tags_manager->register_group('mecas-organizer', ['title' => __('MEC Organizer', 'mec-starter-addons')]);
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

    // ========================================
    // ASSETS
    // ========================================

    public function enqueue_assets() {
        wp_enqueue_style('mecas-styles', MECAS_PLUGIN_URL . 'assets/css/mecas-styles.css', array(), MECAS_VERSION);
        wp_enqueue_script('mecas-scripts', MECAS_PLUGIN_URL . 'assets/js/mecas-scripts.js', array('jquery'), MECAS_VERSION, true);
        
        wp_localize_script('mecas-scripts', 'mecas_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mecas_nonce'),
            'results_page' => get_option('mecas_results_page', ''),
            'is_logged_in' => is_user_logged_in(),
            'login_url' => wp_login_url(),
            'i18n' => array(
                'detecting' => __('Detecting...', 'mec-starter-addons'),
                'location_error' => __('Could not detect location', 'mec-starter-addons'),
                'no_results' => __('No events found', 'mec-starter-addons'),
                'loading' => __('Loading...', 'mec-starter-addons'),
                'enter_location' => __('City, State', 'mec-starter-addons'),
                'saving' => __('Saving...', 'mec-starter-addons'),
                'saved' => __('Saved', 'mec-starter-addons'),
                'save' => __('Save', 'mec-starter-addons'),
                'following' => __('Following', 'mec-starter-addons'),
                'follow' => __('Follow', 'mec-starter-addons'),
                'error' => __('An error occurred. Please try again.', 'mec-starter-addons'),
                'login_required' => __('Please log in to save events.', 'mec-starter-addons'),
            )
        ));
    }

    public function enqueue_admin_assets($hook) {
        global $post_type;
        $is_mecas_page = strpos($hook, 'mec-starter-addons') !== false;
        $is_mec_events = ($post_type === 'mec-events' || get_post_type() === 'mec-events');
        
        if (!$is_mecas_page && !$is_mec_events) return;
        
        wp_enqueue_style('mecas-admin-styles', MECAS_PLUGIN_URL . 'assets/css/mecas-admin.css', array(), MECAS_VERSION);
        
        if ($hook === 'edit.php' && $is_mec_events) {
            wp_enqueue_script('mecas-admin-toggle', MECAS_PLUGIN_URL . 'assets/js/mecas-admin.js', array('jquery'), MECAS_VERSION, true);
            wp_localize_script('mecas-admin-toggle', 'mecas_admin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('mecas_admin_nonce'),
            ));
        }
        
        // Gallery metabox needs media uploader
        if (in_array($hook, array('post.php', 'post-new.php')) && $is_mec_events) {
            wp_enqueue_media();
            wp_enqueue_script('jquery-ui-sortable');
        }
    }
    
    /**
     * Hide WordPress admin bar for customers and organizers
     */
    public function hide_admin_bar_for_users() {
        if (!is_user_logged_in()) return;
        
        $user = wp_get_current_user();
        $hide_for_roles = array(self::CUSTOMER_ROLE, 'mec_organizer', 'organizer', 'host');
        
        // Check if user has any of the roles that should have admin bar hidden
        $user_roles = (array) $user->roles;
        $should_hide = !empty(array_intersect($user_roles, $hide_for_roles));
        
        // Only hide if user doesn't have admin capabilities
        if ($should_hide && !current_user_can('manage_options')) {
            show_admin_bar(false);
        }
    }
    
    /**
     * Use custom avatar from user meta if available
     */
    public function custom_avatar_url($url, $id_or_email, $args) {
        $user_id = 0;
        
        // Get user ID from various input types
        if (is_numeric($id_or_email)) {
            $user_id = (int) $id_or_email;
        } elseif (is_object($id_or_email)) {
            if (!empty($id_or_email->user_id)) {
                $user_id = (int) $id_or_email->user_id;
            } elseif (!empty($id_or_email->ID)) {
                $user_id = (int) $id_or_email->ID;
            }
        } elseif (is_string($id_or_email) && is_email($id_or_email)) {
            $user = get_user_by('email', $id_or_email);
            if ($user) {
                $user_id = $user->ID;
            }
        }
        
        if ($user_id) {
            $custom_avatar = get_user_meta($user_id, 'mecas_profile_picture', true);
            if (!empty($custom_avatar)) {
                return $custom_avatar;
            }
        }
        
        return $url;
    }

    // ========================================
    // ORGANIZER META FIELDS
    // ========================================

    public function add_organizer_fields($taxonomy) {
        ?>
        <div class="form-field">
            <label for="mecas_organizer_city"><?php esc_html_e('City', 'mec-starter-addons'); ?></label>
            <input type="text" name="mecas_organizer_city" id="mecas_organizer_city" value="">
        </div>
        <div class="form-field">
            <label for="mecas_organizer_state"><?php esc_html_e('State', 'mec-starter-addons'); ?></label>
            <input type="text" name="mecas_organizer_state" id="mecas_organizer_state" value="">
        </div>
        <div class="form-field">
            <label for="mecas_organizer_tagline"><?php esc_html_e('Tagline', 'mec-starter-addons'); ?></label>
            <textarea name="mecas_organizer_tagline" id="mecas_organizer_tagline" rows="3"></textarea>
        </div>
        <div class="form-field">
            <label for="mecas_organizer_bio"><?php esc_html_e('Bio', 'mec-starter-addons'); ?></label>
            <textarea name="mecas_organizer_bio" id="mecas_organizer_bio" rows="5"></textarea>
        </div>
        <div class="form-field">
            <label for="mecas_organizer_fun_fact"><?php esc_html_e('Fun Fact', 'mec-starter-addons'); ?></label>
            <textarea name="mecas_organizer_fun_fact" id="mecas_organizer_fun_fact" rows="3"></textarea>
        </div>
        <div class="form-field">
            <label for="mecas_organizer_offerings"><?php esc_html_e('Offerings', 'mec-starter-addons'); ?></label>
            <textarea name="mecas_organizer_offerings" id="mecas_organizer_offerings" rows="5"></textarea>
        </div>
        <div class="form-field">
            <label for="mecas_organizer_tiktok"><?php esc_html_e('TikTok', 'mec-starter-addons'); ?></label>
            <input type="url" name="mecas_organizer_tiktok" id="mecas_organizer_tiktok" value="">
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
        $editor_settings = array('textarea_rows' => 8, 'media_buttons' => true, 'teeny' => false, 'quicktags' => true);
        ?>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_city"><?php esc_html_e('City', 'mec-starter-addons'); ?></label></th>
            <td><input type="text" name="mecas_organizer_city" id="mecas_organizer_city" value="<?php echo esc_attr($city); ?>"></td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_state"><?php esc_html_e('State', 'mec-starter-addons'); ?></label></th>
            <td><input type="text" name="mecas_organizer_state" id="mecas_organizer_state" value="<?php echo esc_attr($state); ?>"></td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_tagline"><?php esc_html_e('Tagline', 'mec-starter-addons'); ?></label></th>
            <td><textarea name="mecas_organizer_tagline" id="mecas_organizer_tagline" rows="3"><?php echo esc_textarea($tagline); ?></textarea></td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_bio"><?php esc_html_e('Bio', 'mec-starter-addons'); ?></label></th>
            <td><?php wp_editor($bio, 'mecas_organizer_bio', $editor_settings); ?></td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_fun_fact"><?php esc_html_e('Fun Fact', 'mec-starter-addons'); ?></label></th>
            <td><?php wp_editor($fun_fact, 'mecas_organizer_fun_fact', array_merge($editor_settings, array('textarea_rows' => 5))); ?></td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_offerings"><?php esc_html_e('Offerings', 'mec-starter-addons'); ?></label></th>
            <td><?php wp_editor($offerings, 'mecas_organizer_offerings', $editor_settings); ?></td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="mecas_organizer_tiktok"><?php esc_html_e('TikTok', 'mec-starter-addons'); ?></label></th>
            <td><input type="url" name="mecas_organizer_tiktok" id="mecas_organizer_tiktok" value="<?php echo esc_url($tiktok); ?>"></td>
        </tr>
        <?php
    }

    public function save_organizer_fields($term_id) {
        if (isset($_POST['mecas_organizer_city'])) update_term_meta($term_id, 'mecas_organizer_city', sanitize_text_field($_POST['mecas_organizer_city']));
        if (isset($_POST['mecas_organizer_state'])) update_term_meta($term_id, 'mecas_organizer_state', sanitize_text_field($_POST['mecas_organizer_state']));
        if (isset($_POST['mecas_organizer_tagline'])) update_term_meta($term_id, 'mecas_organizer_tagline', sanitize_textarea_field($_POST['mecas_organizer_tagline']));
        if (isset($_POST['mecas_organizer_bio'])) update_term_meta($term_id, 'mecas_organizer_bio', wp_kses_post($_POST['mecas_organizer_bio']));
        if (isset($_POST['mecas_organizer_fun_fact'])) update_term_meta($term_id, 'mecas_organizer_fun_fact', wp_kses_post($_POST['mecas_organizer_fun_fact']));
        if (isset($_POST['mecas_organizer_offerings'])) update_term_meta($term_id, 'mecas_organizer_offerings', wp_kses_post($_POST['mecas_organizer_offerings']));
        if (isset($_POST['mecas_organizer_tiktok'])) update_term_meta($term_id, 'mecas_organizer_tiktok', esc_url_raw($_POST['mecas_organizer_tiktok']));
    }

    // ========================================
    // FEATURED EVENT META BOX
    // ========================================

    public function add_featured_meta_box() {
        add_meta_box('mecas_featured_event', __('Featured Event', 'mec-starter-addons'), array($this, 'render_featured_meta_box'), 'mec-events', 'side', 'high');
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
        <?php
    }

    public function save_featured_meta($post_id) {
        if (!isset($_POST['mecas_featured_nonce_field']) || !wp_verify_nonce($_POST['mecas_featured_nonce_field'], 'mecas_featured_nonce')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        $is_featured = isset($_POST['mecas_featured']) ? '1' : '0';
        update_post_meta($post_id, '_mecas_featured', $is_featured);
    }

    public function add_featured_column($columns) {
        $new_columns = array();
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key === 'title') $new_columns['mecas_featured'] = __('Featured', 'mec-starter-addons');
        }
        return $new_columns;
    }

    public function render_featured_column($column, $post_id) {
        if ($column !== 'mecas_featured') return;
        $is_featured = get_post_meta($post_id, '_mecas_featured', true) === '1';
        ?>
        <label class="mecas-featured-toggle">
            <input type="checkbox" class="mecas-featured-checkbox" data-post-id="<?php echo esc_attr($post_id); ?>" <?php checked($is_featured); ?>>
            <span class="mecas-toggle-slider"></span>
        </label>
        <?php
    }

    public function ajax_toggle_featured() {
        check_ajax_referer('mecas_admin_nonce', 'nonce');
        if (!current_user_can('edit_posts')) wp_send_json_error(array('message' => 'Permission denied'));
        $post_id = intval($_POST['post_id'] ?? 0);
        $featured = sanitize_text_field($_POST['featured'] ?? '0');
        if (!$post_id) wp_send_json_error(array('message' => 'Invalid post ID'));
        update_post_meta($post_id, '_mecas_featured', $featured === '1' ? '1' : '0');
        wp_send_json_success(array('post_id' => $post_id, 'featured' => $featured === '1'));
    }

    // ========================================
    // EVENT GALLERY META BOX
    // ========================================

    public function add_gallery_metabox() {
        add_meta_box('mecas_event_gallery', __('Event Gallery', 'mec-starter-addons'), array($this, 'render_gallery_metabox'), 'mec-events', 'normal', 'default');
    }

    public function render_gallery_metabox($post) {
        wp_nonce_field('mecas_gallery_nonce', 'mecas_gallery_nonce');
        $gallery = get_post_meta($post->ID, 'mecas_event_gallery', true);
        if (!is_array($gallery)) $gallery = array();
        ?>
        <div class="mecas-gallery-metabox">
            <p class="description"><?php _e('Add photos to display in the Event Gallery widget.', 'mec-starter-addons'); ?></p>
            <div id="mecas-gallery-container" class="mecas-gallery-container">
                <?php foreach ($gallery as $image_id): 
                    $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                    if (!$image_url) continue;
                ?>
                <div class="mecas-gallery-image" data-id="<?php echo esc_attr($image_id); ?>">
                    <img src="<?php echo esc_url($image_url); ?>" alt="">
                    <button type="button" class="mecas-remove-image">&times;</button>
                    <input type="hidden" name="mecas_event_gallery[]" value="<?php echo esc_attr($image_id); ?>">
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="mecas-add-gallery-images" class="button button-secondary"><?php _e('Add Images', 'mec-starter-addons'); ?></button>
        </div>
        <style>
        .mecas-gallery-container { display: flex; flex-wrap: wrap; gap: 10px; margin: 15px 0; min-height: 50px; padding: 10px; background: #f9f9f9; border: 1px dashed #ccc; border-radius: 4px; }
        .mecas-gallery-container:empty::after { content: 'No images added yet.'; color: #999; font-style: italic; }
        .mecas-gallery-image { position: relative; width: 100px; height: 100px; border-radius: 4px; overflow: hidden; cursor: move; }
        .mecas-gallery-image img { width: 100%; height: 100%; object-fit: cover; }
        .mecas-remove-image { position: absolute; top: 2px; right: 2px; width: 22px; height: 22px; background: rgba(0,0,0,0.7); color: #fff; border: none; border-radius: 50%; cursor: pointer; font-size: 14px; line-height: 1; padding: 0; }
        .mecas-remove-image:hover { background: #d00; }
        </style>
        <script>
        jQuery(document).ready(function($) {
            var mediaFrame;
            $('#mecas-gallery-container').sortable({ items: '.mecas-gallery-image', cursor: 'move', opacity: 0.8 });
            $('#mecas-add-gallery-images').on('click', function(e) {
                e.preventDefault();
                if (mediaFrame) { mediaFrame.open(); return; }
                mediaFrame = wp.media({ title: 'Select Gallery Images', button: { text: 'Add to Gallery' }, multiple: true, library: { type: 'image' } });
                mediaFrame.on('select', function() {
                    var attachments = mediaFrame.state().get('selection').toJSON();
                    attachments.forEach(function(attachment) {
                        if ($('#mecas-gallery-container .mecas-gallery-image[data-id="' + attachment.id + '"]').length) return;
                        var thumbUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                        var html = '<div class="mecas-gallery-image" data-id="' + attachment.id + '"><img src="' + thumbUrl + '" alt=""><button type="button" class="mecas-remove-image">&times;</button><input type="hidden" name="mecas_event_gallery[]" value="' + attachment.id + '"></div>';
                        $('#mecas-gallery-container').append(html);
                    });
                });
                mediaFrame.open();
            });
            $(document).on('click', '.mecas-remove-image', function(e) { e.preventDefault(); $(this).closest('.mecas-gallery-image').remove(); });
        });
        </script>
        <?php
    }

    public function save_gallery_metabox($post_id) {
        if (!isset($_POST['mecas_gallery_nonce']) || !wp_verify_nonce($_POST['mecas_gallery_nonce'], 'mecas_gallery_nonce')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        if (isset($_POST['mecas_event_gallery'])) {
            $gallery = array_map('intval', $_POST['mecas_event_gallery']);
            $gallery = array_filter($gallery);
            update_post_meta($post_id, 'mecas_event_gallery', $gallery);
        } else {
            delete_post_meta($post_id, 'mecas_event_gallery');
        }
    }

    // ========================================
    // SHORTCODE RENDERERS
    // ========================================

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
        
        if ($mobile_mode === 'icon_popup' || $atts['mode'] === 'popup') {
            if ($mobile_mode === 'icon_popup' && $atts['mode'] === 'inline'): ?>
            <style>
                #<?php echo $widget_id; ?>-mobile-trigger { display: none; }
                @media (max-width: <?php echo $mobile_breakpoint; ?>px) {
                    #<?php echo $widget_id; ?>-mobile-trigger { display: flex !important; }
                    #<?php echo $widget_id; ?> { display: none !important; }
                }
            </style>
            <button type="button" class="mecas-mobile-trigger" id="<?php echo $widget_id; ?>-mobile-trigger" data-modal="<?php echo $widget_id; ?>-modal">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            </button>
            <?php endif;
            
            if ($atts['mode'] === 'popup'): ?>
            <div class="mecas-trigger-wrapper" id="<?php echo $widget_id; ?>-trigger-wrapper">
                <button type="button" class="mecas-trigger-button" id="<?php echo $widget_id; ?>-trigger" data-modal="<?php echo $widget_id; ?>-modal">
                    <?php if ($atts['trigger_icon'] === 'true'): ?><svg class="mecas-trigger-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg><?php endif; ?>
                    <?php if ($atts['trigger_text']): ?><span class="mecas-trigger-text"><?php echo esc_html($atts['trigger_text']); ?></span><?php endif; ?>
                </button>
            </div>
            <?php endif; ?>
            
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
        
        if ($atts['mode'] === 'inline') {
            echo $this->render_search_bar($atts, $widget_id);
        }
        
        return ob_get_clean();
    }

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
                        <?php if ($atts['show_suggestions'] === 'true'): ?><div class="mecas-suggestions mecas-query-suggestions"></div><?php endif; ?>
                    </div>
                    <?php if ($show_divider): ?><div class="mecas-divider"></div><?php endif; ?>
                    <div class="mecas-input-group mecas-location-input-group">
                        <input type="text" name="mecas_location" class="mecas-input mecas-location-input" placeholder="<?php echo esc_attr($atts['placeholder_location']); ?>" autocomplete="off">
                        <?php if ($atts['enable_geolocation'] === 'true'): ?>
                        <div class="mecas-location-loading" style="display:none;">
                            <svg class="mecas-spinner" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/></svg>
                        </div>
                        <?php endif; ?>
                        <?php if ($atts['show_suggestions'] === 'true'): ?><div class="mecas-suggestions mecas-location-suggestions"></div><?php endif; ?>
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

    public function render_search_results($atts) {
        $atts = shortcode_atts(array(
            'show_search_bar' => 'true', 'enable_geolocation' => 'true', 'auto_detect_location' => 'true',
            'placeholder_search' => __('Search events', 'mec-starter-addons'), 'placeholder_location' => __('City, State', 'mec-starter-addons'),
            'show_divider' => 'true', 'show_filters' => 'true', 'show_category_filter' => 'true', 'show_organizer_filter' => 'true',
            'show_tag_filter' => 'true', 'show_sort_filter' => 'true', 'label_category' => __('All Categories', 'mec-starter-addons'),
            'label_organizer' => __('All Organizers', 'mec-starter-addons'), 'label_tag' => __('All Tags', 'mec-starter-addons'),
            'label_sort' => __('Sort By', 'mec-starter-addons'), 'columns' => '4', 'per_page' => '12', 'show_pagination' => 'true',
            'no_results_text' => __('No events found.', 'mec-starter-addons'), 'date_format' => 'D, M j', 'time_format' => 'g:i A T',
            'hosted_by_text' => __('Hosted by', 'mec-starter-addons'), 'currency_symbol' => '$', 'widget_id' => 'mecas-results-' . uniqid(),
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

    public function render_featured_events($atts) {
        $atts = shortcode_atts(array(
            'columns' => '4', 'columns_tablet' => '2', 'columns_mobile' => '1', 'per_page' => '8',
            'date_format' => 'D, M j', 'time_format' => 'g:i A T', 'hosted_by_text' => __('Hosted by', 'mec-starter-addons'),
            'currency_symbol' => '$', 'show_price' => 'true', 'show_category_tabs' => 'true',
            'all_tab_text' => __('All', 'mec-starter-addons'), 'widget_id' => 'mecas-featured-' . uniqid(),
        ), $atts);
        
        $categories = $this->get_mec_categories();
        $args = array(
            'post_type' => 'mec-events', 'post_status' => 'publish', 'posts_per_page' => intval($atts['per_page']),
            'meta_query' => array(array('key' => '_mecas_featured', 'value' => '1', 'compare' => '=')),
            'orderby' => 'meta_value', 'meta_key' => 'mec_start_date', 'order' => 'ASC',
        );
        $events = get_posts($args);
        
        ob_start();
        include MECAS_PLUGIN_DIR . 'templates/featured-events.php';
        return ob_get_clean();
    }

    public function render_upcoming_events($atts) {
        $atts = shortcode_atts(array(
            'columns' => '4', 'columns_tablet' => '2', 'columns_mobile' => '1', 'per_page' => '8', 'hide_past_events' => 'true',
            'date_format' => 'D, M j', 'time_format' => 'g:i A T', 'hosted_by_text' => __('Hosted by', 'mec-starter-addons'),
            'currency_symbol' => '$', 'show_price' => 'true', 'show_category_tabs' => 'true',
            'all_tab_text' => __('All', 'mec-starter-addons'), 'widget_id' => 'mecas-upcoming-' . uniqid(),
        ), $atts);
        
        $categories = $this->get_mec_categories();
        $args = array('post_type' => 'mec-events', 'post_status' => 'publish', 'posts_per_page' => intval($atts['per_page']), 'order' => 'ASC');
        $meta_query = array();
        
        if ($atts['hide_past_events'] === 'true') {
            $meta_query[] = array('key' => 'mec_start_date', 'value' => date('Y-m-d'), 'compare' => '>=');
        }
        $meta_query['order_clause'] = array('key' => 'mec_start_date', 'compare' => 'EXISTS');
        $args['meta_query'] = $meta_query;
        $args['orderby'] = array('order_clause' => 'ASC');
        $events = get_posts($args);
        
        ob_start();
        include MECAS_PLUGIN_DIR . 'templates/upcoming-events.php';
        return ob_get_clean();
    }

    public function render_organizers_grid($atts) {
        $atts = shortcode_atts(array('columns' => '4', 'per_page' => '8', 'show_heart' => 'true', 'widget_id' => 'mecas-organizers-' . uniqid()), $atts);
        $organizers = get_terms(array('taxonomy' => 'mec_organizer', 'hide_empty' => true, 'number' => intval($atts['per_page'])));
        ob_start();
        include MECAS_PLUGIN_DIR . 'templates/organizers-grid.php';
        return ob_get_clean();
    }

    public function render_teacher_search($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Search Teachers', 'mec-starter-addons'), 'show_title' => 'true', 'placeholder' => __('City, State', 'mec-starter-addons'),
            'enable_geolocation' => 'true', 'auto_detect_location' => 'false', 'show_count' => 'true',
            'count_text' => __('%d Teachers found in %s', 'mec-starter-addons'), 'columns' => '6', 'per_page' => '24',
            'show_pagination' => 'true', 'no_results_text' => __('No teachers found in this area.', 'mec-starter-addons'),
            'show_location_bar' => 'true', 'show_heart_icon' => 'true', 'show_name' => 'true', 'show_tagline' => 'true',
            'widget_id' => 'mecas-teacher-search-' . uniqid(),
        ), $atts);
        
        $location = isset($_GET['mecas_teacher_location']) ? sanitize_text_field($_GET['mecas_teacher_location']) : '';
        $paged = isset($_GET['mecas_teacher_page']) ? max(1, intval($_GET['mecas_teacher_page'])) : 1;
        $results = $this->search_teachers_by_location($location, intval($atts['per_page']), $paged);
        
        ob_start();
        include MECAS_PLUGIN_DIR . 'templates/teacher-search.php';
        return ob_get_clean();
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    public function search_teachers_by_location($location = '', $per_page = 24, $paged = 1) {
        $args = array('taxonomy' => 'mec_organizer', 'hide_empty' => false, 'number' => $per_page, 'offset' => ($paged - 1) * $per_page);
        
        if ($location) {
            $all_organizers = get_terms(array('taxonomy' => 'mec_organizer', 'hide_empty' => false));
            $filtered_ids = array();
            if (!is_wp_error($all_organizers)) {
                foreach ($all_organizers as $org) {
                    $city = get_term_meta($org->term_id, 'mecas_organizer_city', true);
                    $state = get_term_meta($org->term_id, 'mecas_organizer_state', true);
                    if (stripos($city, $location) !== false || stripos($state, $location) !== false || stripos($location, $city) !== false || stripos($location, $state) !== false || stripos($org->name, $location) !== false) {
                        $filtered_ids[] = $org->term_id;
                    }
                }
            }
            if (!empty($filtered_ids)) {
                $args['include'] = $filtered_ids;
            } else {
                return array('organizers' => array(), 'total' => 0, 'max_pages' => 0, 'current_page' => $paged);
            }
            $total = count($filtered_ids);
        } else {
            $total = get_terms(array('taxonomy' => 'mec_organizer', 'hide_empty' => false, 'fields' => 'count'));
        }
        
        $organizers = get_terms($args);
        if (is_wp_error($organizers)) { $organizers = array(); $total = 0; }
        return array('organizers' => $organizers, 'total' => $total, 'max_pages' => ceil($total / $per_page), 'current_page' => $paged);
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
        $args = array('post_type' => 'mec-events', 'post_status' => 'publish', 'posts_per_page' => $per_page, 'paged' => $paged);
        
        switch ($sort) {
            case 'date_desc': $args['orderby'] = 'meta_value'; $args['meta_key'] = 'mec_start_date'; $args['order'] = 'DESC'; break;
            case 'price_high': $args['orderby'] = 'meta_value_num'; $args['meta_key'] = 'mec_cost'; $args['order'] = 'DESC'; break;
            case 'price_low': $args['orderby'] = 'meta_value_num'; $args['meta_key'] = 'mec_cost'; $args['order'] = 'ASC'; break;
            case 'title_asc': $args['orderby'] = 'title'; $args['order'] = 'ASC'; break;
            case 'title_desc': $args['orderby'] = 'title'; $args['order'] = 'DESC'; break;
            default: $args['orderby'] = 'meta_value'; $args['meta_key'] = 'mec_start_date'; $args['order'] = 'ASC'; break;
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

    public function get_event_full_data($event) {
        $id = is_object($event) ? $event->ID : $event;
        $start_date = get_post_meta($id, 'mec_start_date', true);
        $start_time_hour = get_post_meta($id, 'mec_start_time_hour', true);
        $start_time_min = get_post_meta($id, 'mec_start_time_minutes', true);
        $start_time_ampm = get_post_meta($id, 'mec_start_time_ampm', true);
        $time_string = $start_time_hour ? $start_time_hour . ':' . str_pad($start_time_min, 2, '0', STR_PAD_LEFT) . ' ' . strtoupper($start_time_ampm) : '';
        $cost = get_post_meta($id, 'mec_cost', true);
        
        $loc_id = get_post_meta($id, 'mec_location_id', true);
        $location_name = '';
        if ($loc_id) { $loc = get_term($loc_id, 'mec_location'); if ($loc && !is_wp_error($loc)) $location_name = $loc->name; }
        
        $organizer_terms = get_the_terms($id, 'mec_organizer');
        $organizer_name = ''; $organizer_id = 0;
        if ($organizer_terms && !is_wp_error($organizer_terms)) { $organizer_name = $organizer_terms[0]->name; $organizer_id = $organizer_terms[0]->term_id; }
        
        $tags = get_the_terms($id, 'post_tag'); $tag_name = '';
        if ($tags && !is_wp_error($tags)) $tag_name = $tags[0]->name;
        
        $categories = get_the_terms($id, 'mec_category'); $category_name = '';
        if ($categories && !is_wp_error($categories)) $category_name = $categories[0]->name;
        
        return array('id' => $id, 'title' => get_the_title($id), 'url' => get_permalink($id), 'image' => get_the_post_thumbnail_url($id, 'medium_large'),
            'date' => $start_date, 'date_formatted' => $start_date ? date_i18n(get_option('date_format'), strtotime($start_date)) : '', 'time' => $time_string,
            'cost' => $cost, 'location' => $location_name, 'organizer' => $organizer_name, 'organizer_id' => $organizer_id, 'tag' => $tag_name, 'category' => $category_name);
    }

    // ========================================
    // SEARCH AJAX HANDLERS
    // ========================================

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
        
        $args = array('post_type' => 'mec-events', 'post_status' => 'publish', 'posts_per_page' => $per_page,
            'meta_query' => array(array('key' => '_mecas_featured', 'value' => '1', 'compare' => '=')),
            'orderby' => 'meta_value', 'meta_key' => 'mec_start_date', 'order' => 'ASC');
        if ($category) $args['tax_query'] = array(array('taxonomy' => 'mec_category', 'field' => 'slug', 'terms' => $category));
        
        $events_posts = get_posts($args);
        $events = array();
        foreach ($events_posts as $e) $events[] = $this->get_event_full_data($e);
        wp_send_json_success(array('events' => $events, 'total' => count($events)));
    }

    public function ajax_filter_upcoming_events() {
        check_ajax_referer('mecas_nonce', 'nonce');
        $category = sanitize_text_field($_POST['category'] ?? '');
        $per_page = intval($_POST['per_page'] ?? 8);
        $hide_past = sanitize_text_field($_POST['hide_past'] ?? 'true');
        
        $args = array('post_type' => 'mec-events', 'post_status' => 'publish', 'posts_per_page' => $per_page, 'order' => 'ASC');
        $meta_query = array();
        if ($hide_past === 'true') $meta_query[] = array('key' => 'mec_start_date', 'value' => date('Y-m-d'), 'compare' => '>=');
        $meta_query['order_clause'] = array('key' => 'mec_start_date', 'compare' => 'EXISTS');
        $args['meta_query'] = $meta_query;
        $args['orderby'] = array('order_clause' => 'ASC');
        if ($category) $args['tax_query'] = array(array('taxonomy' => 'mec_category', 'field' => 'slug', 'terms' => $category));
        
        $events_posts = get_posts($args);
        $events = array();
        foreach ($events_posts as $e) $events[] = $this->get_event_full_data($e);
        wp_send_json_success(array('events' => $events, 'total' => count($events)));
    }

    public function ajax_search_teachers() {
        check_ajax_referer('mecas_nonce', 'nonce');
        $location = sanitize_text_field($_POST['location'] ?? '');
        $per_page = intval($_POST['per_page'] ?? 24);
        $page = intval($_POST['page'] ?? 1);
        
        $results = $this->search_teachers_by_location($location, $per_page, $page);
        $teachers = array();
        foreach ($results['organizers'] as $org) {
            $term_id = $org->term_id;
            $image_url = get_term_meta($term_id, 'thumbnail', true);
            $city = get_term_meta($term_id, 'mecas_organizer_city', true);
            $state = get_term_meta($term_id, 'mecas_organizer_state', true);
            $tagline = get_term_meta($term_id, 'mecas_organizer_tagline', true);
            $location_display = $city ? ($city . ($state ? ', ' . $state : '')) : '';
            $teachers[] = array('id' => $term_id, 'name' => $org->name, 'url' => get_term_link($org), 'image' => $image_url, 'location' => $location_display, 'tagline' => $tagline);
        }
        wp_send_json_success(array('teachers' => $teachers, 'total' => $results['total'], 'max_pages' => $results['max_pages'], 'current_page' => $results['current_page']));
    }

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
            if ($location_id) { $loc = get_term($location_id, 'mec_location'); if ($loc && !is_wp_error($loc)) $location_name = $loc->name; }
            $events[] = array('id' => $event_id, 'title' => get_the_title($event), 'url' => get_permalink($event_id),
                'image' => get_the_post_thumbnail_url($event_id, 'medium'), 'date' => $start_date ? date_i18n(get_option('date_format'), strtotime($start_date)) : '', 'location' => $location_name);
        }
        wp_send_json_success(array('events' => $events, 'total' => $results['total'], 'max_pages' => $results['max_pages'], 'current_page' => $results['current_page']));
    }

    private function search_events_by_location($location, $per_page = 12, $page = 1) {
        $args = array('post_type' => 'mec-events', 'post_status' => 'publish', 'posts_per_page' => $per_page, 'paged' => $page, 'orderby' => 'meta_value', 'meta_key' => 'mec_start_date', 'order' => 'ASC');
        if ($location) {
            $location_ids = $this->get_location_ids_by_city($location);
            if (!empty($location_ids)) {
                $args['meta_query'] = array(array('key' => 'mec_location_id', 'value' => $location_ids, 'compare' => 'IN'));
            } else {
                return array('events' => array(), 'total' => 0, 'max_pages' => 0, 'current_page' => $page);
            }
        }
        $query = new WP_Query($args);
        return array('events' => $query->posts, 'total' => $query->found_posts, 'max_pages' => $query->max_num_pages, 'current_page' => $page);
    }

    // ========================================
    // CUSTOMER/USER AJAX HANDLERS
    // ========================================

    public function ajax_register_customer() {
        check_ajax_referer('mecas_nonce', 'nonce');
        
        if (get_option('mecas_registration_enabled', 'yes') !== 'yes') {
            wp_send_json_error(__('Registration is disabled', 'mec-starter-addons'));
        }
        
        $name = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $phone_country = sanitize_text_field($_POST['phone_country'] ?? '+1');
        $location = sanitize_text_field($_POST['location'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($name) || empty($email) || empty($password)) {
            wp_send_json_error(__('Name, email, and password are required', 'mec-starter-addons'));
        }
        if (!is_email($email)) wp_send_json_error(__('Invalid email address', 'mec-starter-addons'));
        if (email_exists($email)) wp_send_json_error(__('Email already registered', 'mec-starter-addons'));
        if (strlen($password) < 8) wp_send_json_error(__('Password must be at least 8 characters', 'mec-starter-addons'));
        
        if (get_option('mecas_twilio_enabled', 'no') === 'yes' && get_option('mecas_require_sms_verification', 'yes') === 'yes') {
            $phone_digits = preg_replace('/[^0-9]/', '', $phone);
            $full_phone = $phone_country . $phone_digits;
            $verified_key = 'mecas_phone_verified_' . md5($full_phone);
            if (!get_transient($verified_key)) wp_send_json_error(__('Phone number not verified', 'mec-starter-addons'));
        }
        
        $username = sanitize_user($name, true);
        $username = str_replace(' ', '_', strtolower($username));
        $original_username = $username;
        $counter = 1;
        while (username_exists($username)) { $username = $original_username . '_' . $counter; $counter++; }
        
        $user_id = wp_create_user($username, $password, $email);
        if (is_wp_error($user_id)) wp_send_json_error($user_id->get_error_message());
        
        $user = new WP_User($user_id);
        $user->set_role(self::CUSTOMER_ROLE);
        
        wp_update_user(array('ID' => $user_id, 'display_name' => $name, 'first_name' => $name));
        update_user_meta($user_id, 'mecas_phone', $phone_country . $phone);
        update_user_meta($user_id, 'mecas_location', $location);
        update_user_meta($user_id, 'mecas_joined_date', current_time('mysql'));
        
        // Send welcome email
        $this->send_welcome_email($user_id, $name, $email);
        
        wp_set_auth_cookie($user_id);
        
        $dashboard_page = get_option('mecas_dashboard_page', '');
        $redirect_url = $dashboard_page ? get_permalink($dashboard_page) : home_url();
        
        wp_send_json_success(array('message' => __('Account created successfully!', 'mec-starter-addons'), 'redirect' => $redirect_url));
    }
    
    /**
     * Send welcome email to newly registered user
     */
    private function send_welcome_email($user_id, $name, $email) {
        $site_name = get_bloginfo('name');
        $site_url = home_url();
        
        $subject = sprintf(__('Welcome to %s!', 'mec-starter-addons'), $site_name);
        
        $message = sprintf(
            __("Hi %s,\n\nThank you for signing up for %s!\n\nWe're excited to have you as part of our community. You can now:\n\n• Save your favorite events\n• Follow your favorite hosts\n• Get updates on events in your area\n\nVisit %s to start exploring.\n\nBest regards,\nThe %s Team", 'mec-starter-addons'),
            $name,
            $site_name,
            $site_url,
            $site_name
        );
        
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        wp_mail($email, $subject, $message, $headers);
    }

    public function ajax_send_sms_code() {
        check_ajax_referer('mecas_nonce', 'nonce');
        
        if (get_option('mecas_twilio_enabled', 'no') !== 'yes') {
            wp_send_json_error(__('SMS verification is not enabled', 'mec-starter-addons'));
        }
        
        $phone_country = sanitize_text_field($_POST['phone_country'] ?? '+1');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        if (empty($phone)) wp_send_json_error(__('Phone number is required', 'mec-starter-addons'));
        
        $phone_digits = preg_replace('/[^0-9]/', '', $phone);
        $full_phone = $phone_country . $phone_digits;
        
        $rate_key = 'mecas_sms_rate_' . md5($full_phone);
        $rate_count = get_transient($rate_key);
        if ($rate_count && $rate_count >= 3) wp_send_json_error(__('Too many verification attempts. Please try again in an hour.', 'mec-starter-addons'));
        
        $code = sprintf('%04d', mt_rand(0, 9999));
        $code_key = 'mecas_sms_code_' . md5($full_phone);
        set_transient($code_key, $code, 10 * MINUTE_IN_SECONDS);
        set_transient($rate_key, ($rate_count ? $rate_count + 1 : 1), HOUR_IN_SECONDS);
        
        $result = $this->send_twilio_sms($full_phone, $code);
        if (is_wp_error($result)) wp_send_json_error($result->get_error_message());
        
        wp_send_json_success(array('message' => __('Verification code sent!', 'mec-starter-addons'), 'phone' => $this->mask_phone($full_phone)));
    }

    public function ajax_verify_sms_code() {
        check_ajax_referer('mecas_nonce', 'nonce');
        
        $phone_country = sanitize_text_field($_POST['phone_country'] ?? '+1');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $code = sanitize_text_field($_POST['code'] ?? '');
        
        if (empty($phone) || empty($code)) wp_send_json_error(__('Phone and code are required', 'mec-starter-addons'));
        
        $phone_digits = preg_replace('/[^0-9]/', '', $phone);
        $full_phone = $phone_country . $phone_digits;
        
        $code_key = 'mecas_sms_code_' . md5($full_phone);
        $stored_code = get_transient($code_key);
        
        if (!$stored_code) wp_send_json_error(__('Verification code expired. Please request a new one.', 'mec-starter-addons'));
        if ($code !== $stored_code) wp_send_json_error(__('Invalid verification code', 'mec-starter-addons'));
        
        delete_transient($code_key);
        $verified_key = 'mecas_phone_verified_' . md5($full_phone);
        set_transient($verified_key, true, 30 * MINUTE_IN_SECONDS);
        
        wp_send_json_success(array('message' => __('Phone verified successfully!', 'mec-starter-addons'), 'verified' => true));
    }

    private function send_twilio_sms($to, $code) {
        $account_sid = get_option('mecas_twilio_account_sid', '');
        $auth_token = get_option('mecas_twilio_auth_token', '');
        $from = get_option('mecas_twilio_phone_number', '');
        $message_template = get_option('mecas_twilio_message', 'Your verification code is: {code}');
        
        // Fallback to MEC Organizer Manager settings
        if (empty($account_sid)) $account_sid = get_option('mecom_twilio_account_sid', '');
        if (empty($auth_token)) $auth_token = get_option('mecom_twilio_auth_token', '');
        if (empty($from)) $from = get_option('mecom_twilio_phone_number', '');
        
        if (empty($account_sid) || empty($auth_token) || empty($from)) {
            return new WP_Error('twilio_config', __('Twilio is not configured properly', 'mec-starter-addons'));
        }
        
        $message = str_replace('{code}', $code, $message_template);
        $url = 'https://api.twilio.com/2010-04-01/Accounts/' . $account_sid . '/Messages.json';
        
        $response = wp_remote_post($url, array(
            'headers' => array('Authorization' => 'Basic ' . base64_encode($account_sid . ':' . $auth_token)),
            'body' => array('From' => $from, 'To' => $to, 'Body' => $message),
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) return new WP_Error('twilio_error', $response->get_error_message());
        
        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($response_code !== 201 && $response_code !== 200) {
            $error_message = isset($response_body['message']) ? $response_body['message'] : __('Failed to send SMS', 'mec-starter-addons');
            return new WP_Error('twilio_error', $error_message);
        }
        
        return true;
    }

    private function mask_phone($phone) {
        $length = strlen($phone);
        if ($length <= 4) return $phone;
        return substr($phone, 0, 3) . str_repeat('*', $length - 6) . substr($phone, -3);
    }

    /**
     * AJAX handler for completing profile (phone/location) after social login
     */
    public function ajax_complete_profile() {
        check_ajax_referer('mecas_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(__('Please log in first', 'mec-starter-addons'));
        }
        
        $user_id = get_current_user_id();
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $phone_country = sanitize_text_field($_POST['phone_country'] ?? '+1');
        $location = sanitize_text_field($_POST['location'] ?? '');
        
        // Save phone if provided
        if (!empty($phone)) {
            $full_phone = $phone_country . preg_replace('/[^0-9]/', '', $phone);
            update_user_meta($user_id, 'mecas_phone', $full_phone);
        }
        
        // Save location if provided
        if (!empty($location)) {
            update_user_meta($user_id, 'mecas_location', $location);
        }
        
        // Mark profile as complete
        update_user_meta($user_id, 'mecas_profile_complete', 'yes');
        update_user_meta($user_id, 'mecas_profile_completed_date', current_time('mysql'));
        
        wp_send_json_success(array(
            'message' => __('Profile updated successfully!', 'mec-starter-addons'),
        ));
    }
    
    /**
     * Handle user login via AJAX
     */
    public function ajax_user_login() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'mecas_login_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'mec-starter-addons')));
        }
        
        $email = sanitize_email($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = !empty($_POST['remember']);
        $redirect_to = esc_url_raw($_POST['redirect_to'] ?? '');
        
        // Validate
        if (empty($email) || empty($password)) {
            wp_send_json_error(array('message' => __('Please enter your email and password', 'mec-starter-addons')));
        }
        
        // Find user by email
        $user = get_user_by('email', $email);
        
        if (!$user) {
            wp_send_json_error(array('message' => __('No account found with this email address', 'mec-starter-addons')));
        }
        
        // Attempt login
        $creds = array(
            'user_login'    => $user->user_login,
            'user_password' => $password,
            'remember'      => $remember
        );
        
        $login = wp_signon($creds, is_ssl());
        
        if (is_wp_error($login)) {
            wp_send_json_error(array('message' => __('Invalid password. Please try again.', 'mec-starter-addons')));
        }
        
        // Set current user
        wp_set_current_user($login->ID);
        
        // Determine redirect URL
        if (empty($redirect_to)) {
            $redirect_to = home_url();
        }
        
        wp_send_json_success(array(
            'message' => __('Login successful!', 'mec-starter-addons'),
            'redirect_url' => $redirect_to,
            'user_id' => $login->ID
        ));
    }
    
    /**
     * Save user dashboard profile via AJAX (User Dashboard Edit widget)
     */
    public function ajax_save_dashboard_profile() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['mecas_dashboard_nonce'] ?? '', 'mecas_dashboard_edit_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'mec-starter-addons')));
        }
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('Please log in first', 'mec-starter-addons')));
        }
        
        $user_id = get_current_user_id();
        
        // Sanitize inputs
        $first_name = sanitize_text_field($_POST['first_name'] ?? '');
        $last_name = sanitize_text_field($_POST['last_name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $website = esc_url_raw($_POST['website'] ?? '');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $location = sanitize_text_field($_POST['location'] ?? '');
        
        // Update WordPress user data
        $user_data = array(
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'display_name' => trim($first_name . ' ' . $last_name) ?: wp_get_current_user()->user_login,
            'user_url' => $website,
        );
        
        // Only update email if changed and valid
        if (!empty($email)) {
            $current_user = wp_get_current_user();
            if ($email !== $current_user->user_email) {
                // Check if email is already in use
                if (email_exists($email) && email_exists($email) !== $user_id) {
                    wp_send_json_error(array('message' => __('This email is already in use', 'mec-starter-addons')));
                }
                $user_data['user_email'] = $email;
            }
        }
        
        $result = wp_update_user($user_data);
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        // Update custom meta fields
        update_user_meta($user_id, 'mecas_phone', $phone);
        update_user_meta($user_id, 'mecas_location', $location);
        
        // Handle profile picture upload
        $avatar_url = null;
        if (!empty($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $avatar_url = $this->handle_avatar_upload($user_id, $_FILES['profile_picture']);
        }
        
        wp_send_json_success(array(
            'message' => __('Profile updated successfully!', 'mec-starter-addons'),
            'avatar_url' => $avatar_url,
        ));
    }
    
    /**
     * Handle avatar/profile picture upload
     */
    private function handle_avatar_upload($user_id, $file) {
        // Validate file type
        $allowed_types = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        if (!in_array($file['type'], $allowed_types)) {
            return null;
        }
        
        // Validate file size (2MB max)
        if ($file['size'] > 2 * 1024 * 1024) {
            return null;
        }
        
        // Include WordPress file handling
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        
        $upload_overrides = array('test_form' => false);
        $uploaded_file = wp_handle_upload($file, $upload_overrides);
        
        if (isset($uploaded_file['error'])) {
            return null;
        }
        
        // Save the avatar URL to user meta
        update_user_meta($user_id, 'mecas_profile_picture', $uploaded_file['url']);
        
        // Also try to update using Simple Local Avatars or WP User Avatar if available
        if (function_exists('update_user_meta')) {
            update_user_meta($user_id, 'simple_local_avatar', array(
                'full' => $uploaded_file['url'],
                'media_id' => 0,
            ));
            
            // For WP User Avatar compatibility
            update_user_meta($user_id, 'wp_user_avatar', $uploaded_file['url']);
        }
        
        return $uploaded_file['url'];
    }
    
    /**
     * Save user dashboard data via AJAX
     */
    public function ajax_save_dashboard() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'mecas_dashboard_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'mec-starter-addons')));
        }
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('Please log in first', 'mec-starter-addons')));
        }
        
        $user_id = get_current_user_id();
        
        // Get form data
        $first_name = sanitize_text_field($_POST['first_name'] ?? '');
        $last_name = sanitize_text_field($_POST['last_name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $website = esc_url_raw($_POST['website'] ?? '');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $location = sanitize_text_field($_POST['location'] ?? '');
        $bio = sanitize_textarea_field($_POST['bio'] ?? '');
        
        // Update WordPress user data
        $user_data = array(
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'display_name' => trim($first_name . ' ' . $last_name) ?: get_userdata($user_id)->user_login,
            'user_url' => $website,
            'description' => $bio,
        );
        
        // Update email if changed and valid
        $current_user = get_userdata($user_id);
        if (!empty($email) && $email !== $current_user->user_email) {
            // Check if email is already in use
            if (email_exists($email) && email_exists($email) !== $user_id) {
                wp_send_json_error(array('message' => __('This email is already in use', 'mec-starter-addons')));
            }
            $user_data['user_email'] = $email;
        }
        
        $result = wp_update_user($user_data);
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        // Save custom meta
        update_user_meta($user_id, 'mecas_phone', $phone);
        update_user_meta($user_id, 'mecas_location', $location);
        
        // Also save to MEC meta if it exists
        update_user_meta($user_id, 'mec_phone', $phone);
        
        // Handle profile picture upload
        $avatar_url = null;
        if (!empty($_FILES['profile_picture']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            
            // Upload the file
            $attachment_id = media_handle_upload('profile_picture', 0);
            
            if (!is_wp_error($attachment_id)) {
                // Save as user meta for custom avatar
                update_user_meta($user_id, 'mecas_profile_picture', $attachment_id);
                update_user_meta($user_id, 'mecas_avatar_url', wp_get_attachment_url($attachment_id));
                
                // If Simple Local Avatars or similar plugin is active
                if (function_exists('simple_local_avatars')) {
                    update_user_meta($user_id, 'simple_local_avatar', array(
                        'media_id' => $attachment_id,
                        'full' => wp_get_attachment_url($attachment_id),
                    ));
                }
                
                // Get the new avatar URL
                $avatar_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
            }
        }
        
        // If no new avatar uploaded, get current custom avatar
        if (!$avatar_url) {
            $custom_avatar = get_user_meta($user_id, 'mecas_avatar_url', true);
            if ($custom_avatar) {
                $avatar_url = $custom_avatar;
            }
        }
        
        wp_send_json_success(array(
            'message' => __('Profile updated successfully!', 'mec-starter-addons'),
            'avatar_url' => $avatar_url,
            'display_name' => $user_data['display_name'],
        ));
    }
    
    /**
     * Load user dashboard via AJAX
     */
    public function ajax_load_dashboard() {
        check_ajax_referer('mecas_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('Please log in first', 'mec-starter-addons')));
        }
        
        // Render the dashboard widget
        ob_start();
        
        // Try to get widget settings from Elementor if widget_id provided
        $widget_id = sanitize_text_field($_POST['widget_id'] ?? '');
        
        // Create a basic dashboard output
        $user = wp_get_current_user();
        $user_id = $user->ID;
        
        $first_name = $user->first_name;
        $last_name = $user->last_name;
        $email = $user->user_email;
        $website = $user->user_url;
        $bio = $user->description;
        $phone = get_user_meta($user_id, 'mecas_phone', true);
        $location = get_user_meta($user_id, 'mecas_location', true);
        
        // Get avatar
        $custom_avatar = get_user_meta($user_id, 'mecas_avatar_url', true);
        $avatar_url = $custom_avatar ?: get_avatar_url($user_id, array('size' => 200));
        
        include MECAS_PLUGIN_DIR . 'templates/user-dashboard-ajax.php';
        
        $html = ob_get_clean();
        
        wp_send_json_success(array(
            'html' => $html,
        ));
    }

    public function ajax_save_event() {
        check_ajax_referer('mecas_nonce', 'nonce');
        if (!is_user_logged_in()) wp_send_json_error(__('Please log in to save events', 'mec-starter-addons'));
        
        $event_id = intval($_POST['event_id'] ?? 0);
        if (!$event_id) wp_send_json_error(__('Invalid event', 'mec-starter-addons'));
        
        // Get event name
        $event_name = get_the_title($event_id);
        if (!$event_name) $event_name = 'this event';
        
        global $wpdb;
        $table = $wpdb->prefix . 'mecas_saved_events';
        $user_id = get_current_user_id();
        
        $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE user_id = %d AND event_id = %d", $user_id, $event_id));
        if ($exists) wp_send_json_success(array('message' => __('Event already saved!', 'mec-starter-addons'), 'saved' => true, 'event_name' => $event_name));
        
        $result = $wpdb->insert($table, array('user_id' => $user_id, 'event_id' => $event_id), array('%d', '%d'));
        if ($result === false) wp_send_json_error(__('Failed to save event', 'mec-starter-addons'));
        
        wp_send_json_success(array('message' => __('Event saved!', 'mec-starter-addons'), 'saved' => true, 'event_name' => $event_name));
    }

    public function ajax_unsave_event() {
        check_ajax_referer('mecas_nonce', 'nonce');
        if (!is_user_logged_in()) wp_send_json_error(__('Please log in', 'mec-starter-addons'));
        
        $event_id = intval($_POST['event_id'] ?? 0);
        if (!$event_id) wp_send_json_error(__('Invalid event', 'mec-starter-addons'));
        
        // Get event name
        $event_name = get_the_title($event_id);
        if (!$event_name) $event_name = 'this event';
        
        global $wpdb;
        $table = $wpdb->prefix . 'mecas_saved_events';
        $user_id = get_current_user_id();
        
        $wpdb->delete($table, array('user_id' => $user_id, 'event_id' => $event_id), array('%d', '%d'));
        wp_send_json_success(array('message' => __('Event removed from saved', 'mec-starter-addons'), 'saved' => false, 'event_name' => $event_name));
    }

    /**
     * Check which events are saved by the current user
     */
    public function ajax_check_saved_events() {
        check_ajax_referer('mecas_nonce', 'nonce');
        if (!is_user_logged_in()) wp_send_json_error(__('Not logged in', 'mec-starter-addons'));
        
        $event_ids = isset($_POST['event_ids']) ? array_map('intval', (array)$_POST['event_ids']) : array();
        if (empty($event_ids)) wp_send_json_success(array('saved_ids' => array()));
        
        global $wpdb;
        $table = $wpdb->prefix . 'mecas_saved_events';
        $user_id = get_current_user_id();
        
        $placeholders = implode(',', array_fill(0, count($event_ids), '%d'));
        $query = $wpdb->prepare(
            "SELECT event_id FROM $table WHERE user_id = %d AND event_id IN ($placeholders)",
            array_merge(array($user_id), $event_ids)
        );
        
        $saved_ids = $wpdb->get_col($query);
        wp_send_json_success(array('saved_ids' => array_map('intval', $saved_ids)));
    }

    public function ajax_follow_organizer() {
        check_ajax_referer('mecas_nonce', 'nonce');
        if (!is_user_logged_in()) wp_send_json_error(__('Please log in to follow', 'mec-starter-addons'));
        
        $organizer_id = intval($_POST['organizer_id'] ?? 0);
        if (!$organizer_id) wp_send_json_error(__('Invalid organizer', 'mec-starter-addons'));
        
        // Get organizer name from taxonomy
        $organizer = get_term($organizer_id, 'mec_organizer');
        $organizer_name = ($organizer && !is_wp_error($organizer)) ? $organizer->name : 'this teacher';
        
        global $wpdb;
        $table = $wpdb->prefix . 'mecas_following';
        $user_id = get_current_user_id();
        
        $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE user_id = %d AND organizer_id = %d", $user_id, $organizer_id));
        if ($exists) wp_send_json_success(array('message' => __('Already following!', 'mec-starter-addons'), 'following' => true, 'organizer_name' => $organizer_name));
        
        $result = $wpdb->insert($table, array('user_id' => $user_id, 'organizer_id' => $organizer_id), array('%d', '%d'));
        if ($result === false) wp_send_json_error(__('Failed to follow', 'mec-starter-addons'));
        
        wp_send_json_success(array('message' => __('Now following!', 'mec-starter-addons'), 'following' => true, 'organizer_name' => $organizer_name));
    }

    public function ajax_unfollow_organizer() {
        check_ajax_referer('mecas_nonce', 'nonce');
        if (!is_user_logged_in()) wp_send_json_error(__('Please log in', 'mec-starter-addons'));
        
        $organizer_id = intval($_POST['organizer_id'] ?? 0);
        if (!$organizer_id) wp_send_json_error(__('Invalid organizer', 'mec-starter-addons'));
        
        // Get organizer name from taxonomy
        $organizer = get_term($organizer_id, 'mec_organizer');
        $organizer_name = ($organizer && !is_wp_error($organizer)) ? $organizer->name : 'this teacher';
        
        global $wpdb;
        $table = $wpdb->prefix . 'mecas_following';
        $user_id = get_current_user_id();
        
        $wpdb->delete($table, array('user_id' => $user_id, 'organizer_id' => $organizer_id), array('%d', '%d'));
        wp_send_json_success(array('message' => __('Unfollowed', 'mec-starter-addons'), 'following' => false, 'organizer_name' => $organizer_name));
    }

    /**
     * Check which organizers are followed by the current user
     */
    public function ajax_check_following() {
        check_ajax_referer('mecas_nonce', 'nonce');
        if (!is_user_logged_in()) wp_send_json_error(__('Not logged in', 'mec-starter-addons'));
        
        $organizer_ids = isset($_POST['organizer_ids']) ? array_map('intval', (array)$_POST['organizer_ids']) : array();
        if (empty($organizer_ids)) wp_send_json_success(array('following_ids' => array()));
        
        global $wpdb;
        $table = $wpdb->prefix . 'mecas_following';
        $user_id = get_current_user_id();
        
        $placeholders = implode(',', array_fill(0, count($organizer_ids), '%d'));
        $query = $wpdb->prepare(
            "SELECT organizer_id FROM $table WHERE user_id = %d AND organizer_id IN ($placeholders)",
            array_merge(array($user_id), $organizer_ids)
        );
        
        $following_ids = $wpdb->get_col($query);
        wp_send_json_success(array('following_ids' => array_map('intval', $following_ids)));
    }

    public function ajax_update_profile() {
        check_ajax_referer('mecas_nonce', 'nonce');
        if (!is_user_logged_in()) wp_send_json_error(__('Please log in', 'mec-starter-addons'));
        
        $user_id = get_current_user_id();
        $name = sanitize_text_field($_POST['name'] ?? '');
        $location = sanitize_text_field($_POST['location'] ?? '');
        
        if (empty($name)) wp_send_json_error(__('Name is required', 'mec-starter-addons'));
        
        wp_update_user(array('ID' => $user_id, 'display_name' => $name, 'first_name' => $name));
        update_user_meta($user_id, 'mecas_location', $location);
        
        if (!empty($_FILES['profile_image']['tmp_name'])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            
            $attachment_id = media_handle_upload('profile_image', 0);
            if (!is_wp_error($attachment_id)) {
                update_user_meta($user_id, 'mecas_profile_image', $attachment_id);
            }
        }
        
        wp_send_json_success(array('message' => __('Profile updated!', 'mec-starter-addons')));
    }

    public function customer_login_redirect($redirect_to, $requested_redirect_to, $user) {
        if (isset($user->roles) && in_array(self::CUSTOMER_ROLE, (array) $user->roles)) {
            $dashboard_page = get_option('mecas_dashboard_page', '');
            if ($dashboard_page) return get_permalink($dashboard_page);
        }
        return $redirect_to;
    }

    // ========================================
    // ELEMENTOR
    // ========================================

    public function register_elementor_widgets($widgets_manager) {
        // Search widgets
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
        
        // User/Customer Widgets
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-registration-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-complete-profile-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-header-auth-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-login-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-user-dashboard-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-user-dashboard-edit-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-profile-card-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-user-events-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-user-following-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-save-event-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-share-event-widget.php';
        
        // Event Single Page Widgets
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-event-title-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-event-details-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-event-gallery-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-event-description-widget.php';
        require_once MECAS_PLUGIN_DIR . 'includes/elementor/class-mecas-event-host-widget.php';
        
        // Register all widgets
        $widgets_manager->register(new \MECAS_Search_Widget());
        $widgets_manager->register(new \MECAS_Results_Widget());
        $widgets_manager->register(new \MECAS_Featured_Widget());
        $widgets_manager->register(new \MECAS_Upcoming_Widget());
        $widgets_manager->register(new \MECAS_Organizers_Widget());
        $widgets_manager->register(new \MECAS_Teacher_Search_Widget());
        $widgets_manager->register(new \MECAS_Events_Location_Search_Widget());
        
        $widgets_manager->register(new \MECAS_Organizer_Profile_Widget());
        $widgets_manager->register(new \MECAS_Organizer_Name_Widget());
        $widgets_manager->register(new \MECAS_Organizer_Bio_Widget());
        $widgets_manager->register(new \MECAS_Organizer_Fun_Fact_Widget());
        $widgets_manager->register(new \MECAS_Organizer_Offerings_Widget());
        $widgets_manager->register(new \MECAS_Organizer_Social_Widget());
        $widgets_manager->register(new \MECAS_Organizer_Events_Widget());
        
        $widgets_manager->register(new \MECAS_Registration_Widget());
        $widgets_manager->register(new \MECAS_Complete_Profile_Widget());
        $widgets_manager->register(new \MECAS_Header_Auth_Widget());
        $widgets_manager->register(new \MECAS_Login_Widget());
        $widgets_manager->register(new \MECAS_User_Dashboard_Widget());
        $widgets_manager->register(new \MECAS_User_Dashboard_Edit_Widget());
        $widgets_manager->register(new \MECAS_Profile_Card_Widget());
        $widgets_manager->register(new \MECAS_User_Events_Widget());
        $widgets_manager->register(new \MECAS_User_Following_Widget());
        $widgets_manager->register(new \MECAS_Save_Event_Widget());
        $widgets_manager->register(new \MECAS_Share_Event_Widget());
        
        $widgets_manager->register(new \MECAS_Event_Title_Widget());
        $widgets_manager->register(new \MECAS_Event_Details_Widget());
        $widgets_manager->register(new \MECAS_Event_Gallery_Widget());
        $widgets_manager->register(new \MECAS_Event_Description_Widget());
        $widgets_manager->register(new \MECAS_Event_Host_Widget());
    }

    public function add_elementor_category($elements_manager) {
        $elements_manager->add_category('mec-starter-addons', array('title' => __('MEC Starter Addons', 'mec-starter-addons'), 'icon' => 'fa fa-calendar'));
    }

    // ========================================
    // ADMIN
    // ========================================

    public function add_admin_menu() {
        add_menu_page(__('MEC Addons', 'mec-starter-addons'), __('MEC Addons', 'mec-starter-addons'), 'manage_options', 'mec-starter-addons', array($this, 'render_admin_page'), 'dashicons-calendar-alt', 30);
    }

    public function register_settings() {
        // Search settings
        register_setting('mecas_settings', 'mecas_results_page');
        register_setting('mecas_settings', 'mecas_enable_geolocation');
        
        // Theme Builder settings
        register_setting('mecas_settings', 'mecas_override_single_event');
        
        // Customer settings
        register_setting('mecas_settings', 'mecas_registration_enabled');
        register_setting('mecas_settings', 'mecas_require_sms_verification');
        register_setting('mecas_settings', 'mecas_dashboard_page');
        register_setting('mecas_settings', 'mecas_twilio_enabled');
        register_setting('mecas_settings', 'mecas_twilio_account_sid');
        register_setting('mecas_settings', 'mecas_twilio_auth_token');
        register_setting('mecas_settings', 'mecas_twilio_phone_number');
        register_setting('mecas_settings', 'mecas_twilio_message');
    }

    public function render_admin_page() {
        include MECAS_PLUGIN_DIR . 'templates/admin-settings.php';
    }
}

/**
 * Get organizer data by ID
 */
function mecas_get_organizer_data($organizer_id) {
    $term = get_term($organizer_id, 'mec_organizer');
    if (!$term || is_wp_error($term)) return null;
    
    $thumbnail = get_term_meta($organizer_id, 'thumbnail', true);
    $tel = get_term_meta($organizer_id, 'tel', true);
    $email = get_term_meta($organizer_id, 'email', true);
    $page_url = get_term_meta($organizer_id, 'url', true);
    $page_label = get_term_meta($organizer_id, 'page_label', true);
    $facebook = get_term_meta($organizer_id, 'facebook', true);
    $instagram = get_term_meta($organizer_id, 'instagram', true);
    $linkedin = get_term_meta($organizer_id, 'linkedin', true);
    $twitter = get_term_meta($organizer_id, 'twitter', true);
    
    $city = get_term_meta($organizer_id, 'mecas_organizer_city', true);
    $state = get_term_meta($organizer_id, 'mecas_organizer_state', true);
    $tagline = get_term_meta($organizer_id, 'mecas_organizer_tagline', true);
    $bio = get_term_meta($organizer_id, 'mecas_organizer_bio', true);
    $fun_fact = get_term_meta($organizer_id, 'mecas_organizer_fun_fact', true);
    $offerings = get_term_meta($organizer_id, 'mecas_organizer_offerings', true);
    $tiktok = get_term_meta($organizer_id, 'mecas_organizer_tiktok', true);
    
    $location = $city . ($state ? ($city ? ', ' : '') . $state : '');
    $url = get_term_link($term, 'mec_organizer');
    if (is_wp_error($url)) $url = '';
    
    return array('id' => $organizer_id, 'name' => $term->name, 'slug' => $term->slug, 'description' => $term->description, 'url' => $url,
        'thumbnail' => $thumbnail, 'city' => $city, 'state' => $state, 'location' => $location, 'tagline' => $tagline, 'bio' => $bio,
        'fun_fact' => $fun_fact, 'offerings' => $offerings, 'tel' => $tel, 'email' => $email, 'page_url' => $page_url, 'page_label' => $page_label,
        'facebook' => $facebook, 'instagram' => $instagram, 'linkedin' => $linkedin, 'twitter' => $twitter, 'tiktok' => $tiktok);
}

/**
 * Check if user has saved an event
 */
function mecas_is_event_saved($event_id, $user_id = null) {
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return false;
    global $wpdb;
    $table = $wpdb->prefix . 'mecas_saved_events';
    return (bool) $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE user_id = %d AND event_id = %d", $user_id, $event_id));
}

/**
 * Check if user is following an organizer
 */
function mecas_is_following($organizer_id, $user_id = null) {
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return false;
    global $wpdb;
    $table = $wpdb->prefix . 'mecas_following';
    return (bool) $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE user_id = %d AND organizer_id = %d", $user_id, $organizer_id));
}

/**
 * Get user's saved events
 */
function mecas_get_saved_events($user_id = null, $include_past = false) {
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return array();
    global $wpdb;
    $table = $wpdb->prefix . 'mecas_saved_events';
    $event_ids = $wpdb->get_col($wpdb->prepare("SELECT event_id FROM $table WHERE user_id = %d ORDER BY created_at DESC", $user_id));
    if (empty($event_ids)) return array();
    
    $events = array();
    $now = current_time('timestamp');
    foreach ($event_ids as $event_id) {
        $event_date = get_post_meta($event_id, 'mec_start_date', true);
        $event_timestamp = $event_date ? strtotime($event_date) : 0;
        $is_past = $event_timestamp && $event_timestamp < $now;
        if ($include_past || !$is_past) $events[] = array('id' => $event_id, 'is_past' => $is_past);
    }
    return $events;
}

/**
 * Get user's following list
 */
function mecas_get_following($user_id = null) {
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return array();
    global $wpdb;
    $table = $wpdb->prefix . 'mecas_following';
    return $wpdb->get_col($wpdb->prepare("SELECT organizer_id FROM $table WHERE user_id = %d ORDER BY created_at DESC", $user_id));
}

/**
 * Get user profile data
 */
function mecas_get_user_profile($user_id = null) {
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return null;
    $user = get_userdata($user_id);
    if (!$user) return null;
    
    $profile_image_id = get_user_meta($user_id, 'mecas_profile_image', true);
    $profile_image = $profile_image_id ? wp_get_attachment_image_url($profile_image_id, 'medium') : get_avatar_url($user_id, array('size' => 300));
    
    return array('id' => $user_id, 'name' => $user->display_name, 'email' => $user->user_email,
        'phone' => get_user_meta($user_id, 'mecas_phone', true), 'location' => get_user_meta($user_id, 'mecas_location', true),
        'joined_date' => get_user_meta($user_id, 'mecas_joined_date', true) ?: $user->user_registered, 'profile_image' => $profile_image, 'profile_image_id' => $profile_image_id);
}

MEC_Starter_Addons::get_instance();
