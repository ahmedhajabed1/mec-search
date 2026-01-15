<?php
/**
 * Plugin Name: MEC Organizer Manager
 * Plugin URI: https://themahjhub.com
 * Description: Complete organizer management system for Modern Events Calendar - User accounts, profile pages, permissions, multi-step registration, and Elementor widgets
 * Version: 1.2.21
 * Author: Ahmed Haj Abed
 * Author URI: https://themahjhub.com
 * License: GPL v2 or later
 * Text Domain: mec-organizer-manager
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) exit;

define('MECOM_VERSION', '1.2.21');
define('MECOM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MECOM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MECOM_PLUGIN_BASENAME', plugin_basename(__FILE__));

class MEC_Organizer_Manager {
    private static $instance = null;
    
    const ORGANIZER_ROLE = 'mec_event_organizer';
    const TEACHER_SLUG = 'teacher';

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        // Activation/Deactivation
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Core functionality
        add_action('plugins_loaded', array($this, 'check_dependencies'));
        add_action('init', array($this, 'register_rewrite_rules'));
        add_action('init', array($this, 'ensure_organizer_role'));
        add_action('parse_request', array($this, 'setup_organizer_page_early'), 1);
        add_action('template_redirect', array($this, 'handle_teacher_page'));
        
        // Assets
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Organizer-User Connection
        add_action('created_mec_organizer', array($this, 'on_organizer_created'), 10, 2);
        add_action('edited_mec_organizer', array($this, 'on_organizer_updated'), 10, 2);
        add_action('delete_mec_organizer', array($this, 'on_organizer_deleted'), 10, 4);
        
        // Add user field to organizer form
        add_action('mec_organizer_add_form_fields', array($this, 'add_organizer_user_fields'));
        add_action('mec_organizer_edit_form_fields', array($this, 'edit_organizer_user_fields'));
        add_action('created_mec_organizer', array($this, 'save_organizer_user_fields'));
        add_action('edited_mec_organizer', array($this, 'save_organizer_user_fields'));
        
        // Filter events in admin for organizers
        add_action('pre_get_posts', array($this, 'filter_events_for_organizer'));
        
        // Hide other organizers in dropdown
        add_filter('mec_organizer_dropdown_args', array($this, 'filter_organizer_dropdown'));
        
        // Auto-assign organizer when creating event
        add_action('save_post_mec-events', array($this, 'auto_assign_organizer'), 10, 3);
        
        // Elementor integration
        add_action('elementor/widgets/register', array($this, 'register_elementor_widgets'));
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_category'));
        add_action('elementor/dynamic_tags/register', array($this, 'register_dynamic_tags'));
        add_action('elementor/theme/register_conditions', array($this, 'register_elementor_conditions'));
        add_filter('elementor/theme/conditions/cache', array($this, 'filter_elementor_conditions_cache'), 10, 2);
        
        // Add profile link to admin bar
        add_action('admin_bar_menu', array($this, 'add_profile_link_to_admin_bar'), 100);
        
        // Dashboard widget for organizers
        add_action('wp_dashboard_setup', array($this, 'add_organizer_dashboard_widget'));
        
        // Shortcodes
        add_shortcode('mecom_login_form', array($this, 'render_login_form'));
        add_shortcode('mecom_register_form', array($this, 'render_register_form'));
        add_shortcode('mecom_host_registration', array($this, 'render_host_registration_form'));
        add_shortcode('mecom_organizer_dashboard', array($this, 'render_organizer_dashboard'));
        
        // AJAX handlers
        add_action('wp_ajax_mecom_sync_organizer', array($this, 'ajax_sync_organizer'));
        add_action('wp_ajax_mecom_link_user', array($this, 'ajax_link_user'));
        add_action('wp_ajax_mecom_unlink_user', array($this, 'ajax_unlink_user'));
        add_action('wp_ajax_mecom_create_user_for_organizer', array($this, 'ajax_create_user_for_organizer'));
        add_action('wp_ajax_mecom_approve_registration', array($this, 'ajax_approve_registration'));
        add_action('wp_ajax_mecom_reject_registration', array($this, 'ajax_reject_registration'));
        
        // Public AJAX
        add_action('wp_ajax_nopriv_mecom_register_host', array($this, 'ajax_register_host'));
        add_action('wp_ajax_mecom_register_host', array($this, 'ajax_register_host'));
        
        // SMS verification AJAX
        add_action('wp_ajax_nopriv_mecom_send_sms_code', array($this, 'ajax_send_sms_code'));
        add_action('wp_ajax_mecom_send_sms_code', array($this, 'ajax_send_sms_code'));
        add_action('wp_ajax_nopriv_mecom_verify_sms_code', array($this, 'ajax_verify_sms_code'));
        add_action('wp_ajax_mecom_verify_sms_code', array($this, 'ajax_verify_sms_code'));
        
        // Login redirect for organizers
        add_filter('login_redirect', array($this, 'organizer_login_redirect'), 10, 3);
    }

    /**
     * Plugin activation
     */
    public function activate() {
        $this->create_organizer_role();
        $this->register_rewrite_rules();
        $this->create_pending_registrations_table();
        flush_rewrite_rules();
        
        // Create default options
        add_option('mecom_auto_create_user', 'no');
        add_option('mecom_user_role', self::ORGANIZER_ROLE);
        add_option('mecom_teacher_slug', self::TEACHER_SLUG);
        add_option('mecom_sync_fields', array('name', 'email', 'bio', 'thumbnail', 'social'));
        add_option('mecom_default_password_email', 'yes');
        add_option('mecom_registration_enabled', 'yes');
        add_option('mecom_require_approval', 'yes');
        add_option('mecom_recaptcha_enabled', 'no');
        add_option('mecom_recaptcha_site_key', '');
        add_option('mecom_recaptcha_secret_key', '');
    }

    /**
     * Create pending registrations table
     */
    private function create_pending_registrations_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mecom_pending_registrations';
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            phone varchar(50) DEFAULT '',
            phone_country varchar(10) DEFAULT '+1',
            location varchar(255) DEFAULT '',
            password_hash varchar(255) NOT NULL,
            business_name varchar(255) DEFAULT '',
            business_address text DEFAULT '',
            business_ein varchar(100) DEFAULT '',
            website varchar(255) DEFAULT '',
            social_links text DEFAULT '',
            fun_facts text DEFAULT '',
            description text DEFAULT '',
            need_business_help varchar(10) DEFAULT 'no',
            status varchar(20) DEFAULT 'pending',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY email (email),
            KEY status (status)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Check for required plugins
     */
    public function check_dependencies() {
        if (!class_exists('MEC')) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p><strong>MEC Organizer Manager</strong> requires Modern Events Calendar to be installed and activated.</p></div>';
            });
        }
    }

    /**
     * Create custom organizer role
     */
    public function create_organizer_role() {
        remove_role(self::ORGANIZER_ROLE);
        
        add_role(self::ORGANIZER_ROLE, __('Event Organizer', 'mec-organizer-manager'), array(
            'read' => true,
            'upload_files' => true,
            'edit_mec_events' => true,
            'edit_published_mec_events' => true,
            'publish_mec_events' => true,
            'delete_mec_events' => true,
            'delete_published_mec_events' => true,
            'edit_others_mec_events' => false,
            'delete_others_mec_events' => false,
            'read_private_mec_events' => false,
            'manage_mec_category' => false,
            'manage_mec_label' => false,
            'manage_mec_location' => false,
            'manage_mec_organizer' => false,
        ));
    }

    /**
     * Ensure role exists on init
     */
    public function ensure_organizer_role() {
        if (!get_role(self::ORGANIZER_ROLE)) {
            $this->create_organizer_role();
        }
    }

    /**
     * Register rewrite rules
     */
    public function register_rewrite_rules() {
        $slug = get_option('mecom_teacher_slug', self::TEACHER_SLUG);
        
        add_rewrite_rule(
            '^' . $slug . '/([^/]+)/?$',
            'index.php?mecom_teacher=$matches[1]',
            'top'
        );
        
        add_rewrite_tag('%mecom_teacher%', '([^&]+)');
    }

    /**
     * Setup organizer page early in the request lifecycle
     * This ensures Elementor conditions check the right flags
     */
    public function setup_organizer_page_early($wp) {
        if (empty($wp->query_vars['mecom_teacher'])) {
            return;
        }
        
        $teacher_slug = $wp->query_vars['mecom_teacher'];
        $organizer = get_term_by('slug', $teacher_slug, 'mec_organizer');
        
        if ($organizer) {
            // Store for later use
            $wp->query_vars['mecom_organizer_id'] = $organizer->term_id;
            $wp->query_vars['mecom_organizer'] = $organizer;
            
            // Hook into pre_get_posts to modify query flags
            add_action('pre_get_posts', function($query) {
                if ($query->is_main_query() && get_query_var('mecom_teacher')) {
                    $query->is_author = false;
                    $query->is_archive = true;
                    $query->is_singular = false;
                    $query->is_page = false;
                }
            }, 1);
        }
    }

    /**
     * Handle teacher profile page requests
     */
    public function handle_teacher_page() {
        $teacher_slug = get_query_var('mecom_teacher');
        
        if (!$teacher_slug) {
            return;
        }
        
        $organizer = get_term_by('slug', $teacher_slug, 'mec_organizer');
        
        if (!$organizer) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            return;
        }
        
        // Set query vars for Elementor widgets to use
        set_query_var('mecom_organizer_id', $organizer->term_id);
        set_query_var('mecom_organizer', $organizer);
        
        // Set this as an archive page for Elementor Theme Builder
        global $wp_query;
        $wp_query->is_author = false;
        $wp_query->is_archive = true;
        $wp_query->is_singular = false;
        
        // Check if theme has custom template
        $template = locate_template('teacher-profile.php');
        
        if (!$template) {
            // Check if Elementor has a template for organizer profiles
            if ($this->has_elementor_organizer_template()) {
                // Let Elementor handle it - don't exit, just return
                return;
            }
            // Use plugin's default template
            $template = MECOM_PLUGIN_DIR . 'templates/teacher-profile.php';
        }
        
        if (file_exists($template)) {
            include $template;
            exit;
        }
    }

    /**
     * Check if Elementor has a template for organizer profiles
     */
    private function has_elementor_organizer_template() {
        if (!class_exists('\Elementor\Plugin')) {
            return false;
        }
        
        if (!class_exists('\ElementorPro\Modules\ThemeBuilder\Module')) {
            return false;
        }
        
        $theme_builder = \ElementorPro\Plugin::instance()->modules_manager->get_modules('theme-builder');
        if (!$theme_builder) {
            return false;
        }
        
        $conditions_manager = $theme_builder->get_conditions_manager();
        if (!$conditions_manager) {
            return false;
        }
        
        // Check for archive templates (our organizer profiles use archive condition)
        $documents = $conditions_manager->get_documents_for_location('archive');
        
        return !empty($documents);
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        wp_enqueue_style('mecom-styles', MECOM_PLUGIN_URL . 'assets/css/mecom-styles.css', array(), MECOM_VERSION);
        wp_enqueue_script('mecom-scripts', MECOM_PLUGIN_URL . 'assets/js/mecom-scripts.js', array('jquery'), MECOM_VERSION, true);
        
        // Registration form styles and scripts
        wp_enqueue_style('mecom-registration', MECOM_PLUGIN_URL . 'assets/css/mecom-registration.css', array(), MECOM_VERSION);
        wp_enqueue_script('mecom-registration', MECOM_PLUGIN_URL . 'assets/js/mecom-registration.js', array('jquery'), MECOM_VERSION, true);
        
        // reCAPTCHA if enabled
        if (get_option('mecom_recaptcha_enabled', 'no') === 'yes') {
            $site_key = get_option('mecom_recaptcha_site_key', '');
            if ($site_key) {
                wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true);
            }
        }
        
        wp_localize_script('mecom-scripts', 'mecom_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mecom_nonce'),
            'i18n' => array(
                'loading' => __('Loading...', 'mec-organizer-manager'),
                'error' => __('An error occurred', 'mec-organizer-manager'),
                'success' => __('Success!', 'mec-organizer-manager'),
            )
        ));
        
        wp_localize_script('mecom-registration', 'mecom_reg', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mecom_registration_nonce'),
            'recaptcha_enabled' => get_option('mecom_recaptcha_enabled', 'no') === 'yes',
            'recaptcha_site_key' => get_option('mecom_recaptcha_site_key', ''),
            'twilio_enabled' => get_option('mecom_twilio_enabled', 'no') === 'yes',
            'i18n' => array(
                'submitting' => __('Submitting...', 'mec-organizer-manager'),
                'error' => __('An error occurred. Please try again.', 'mec-organizer-manager'),
                'required' => __('This field is required', 'mec-organizer-manager'),
                'invalid_email' => __('Please enter a valid email address', 'mec-organizer-manager'),
                'invalid_phone' => __('Please enter a valid phone number', 'mec-organizer-manager'),
                'password_short' => __('Password must be at least 8 characters', 'mec-organizer-manager'),
                'recaptcha_required' => __('Please complete the reCAPTCHA', 'mec-organizer-manager'),
                'confirm_cancel' => __('Are you sure you want to cancel?', 'mec-organizer-manager'),
                'social_placeholder' => __('https://...', 'mec-organizer-manager'),
                'sending_code' => __('Sending code...', 'mec-organizer-manager'),
                'code_sent' => __('Verification code sent!', 'mec-organizer-manager'),
                'verifying' => __('Verifying...', 'mec-organizer-manager'),
                'invalid_code' => __('Invalid verification code', 'mec-organizer-manager'),
                'code_expired' => __('Code expired. Please request a new one.', 'mec-organizer-manager'),
                'resend_in' => __('Resend in', 'mec-organizer-manager'),
            )
        ));
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        global $taxonomy;
        
        $is_organizer_page = ($taxonomy === 'mec_organizer');
        $is_plugin_page = strpos($hook, 'mec-organizer-manager') !== false;
        
        if (!$is_organizer_page && !$is_plugin_page) {
            return;
        }
        
        wp_enqueue_style('mecom-admin-styles', MECOM_PLUGIN_URL . 'assets/css/mecom-admin.css', array(), MECOM_VERSION);
        wp_enqueue_script('mecom-admin-scripts', MECOM_PLUGIN_URL . 'assets/js/mecom-admin.js', array('jquery'), MECOM_VERSION, true);
        
        wp_localize_script('mecom-admin-scripts', 'mecom_admin', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mecom_admin_nonce'),
            'i18n' => array(
                'confirm_unlink' => __('Are you sure you want to unlink this user?', 'mec-organizer-manager'),
                'confirm_approve' => __('Approve this registration?', 'mec-organizer-manager'),
                'confirm_reject' => __('Reject this registration? This cannot be undone.', 'mec-organizer-manager'),
                'linking' => __('Linking...', 'mec-organizer-manager'),
                'unlinking' => __('Unlinking...', 'mec-organizer-manager'),
                'creating' => __('Creating user...', 'mec-organizer-manager'),
                'approving' => __('Approving...', 'mec-organizer-manager'),
                'rejecting' => __('Rejecting...', 'mec-organizer-manager'),
                'success' => __('Success!', 'mec-organizer-manager'),
                'error' => __('Error occurred', 'mec-organizer-manager'),
            )
        ));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'mec-intro',
            __('Organizer Manager', 'mec-organizer-manager'),
            __('Organizer Manager', 'mec-organizer-manager'),
            'manage_options',
            'mec-organizer-manager',
            array($this, 'render_admin_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('mecom_settings', 'mecom_auto_create_user');
        register_setting('mecom_settings', 'mecom_user_role');
        register_setting('mecom_settings', 'mecom_teacher_slug');
        register_setting('mecom_settings', 'mecom_sync_fields');
        register_setting('mecom_settings', 'mecom_default_password_email');
        register_setting('mecom_settings', 'mecom_registration_enabled');
        register_setting('mecom_settings', 'mecom_require_approval');
        register_setting('mecom_settings', 'mecom_recaptcha_enabled');
        register_setting('mecom_settings', 'mecom_recaptcha_site_key');
        register_setting('mecom_settings', 'mecom_recaptcha_secret_key');
        
        // Twilio settings
        register_setting('mecom_settings', 'mecom_twilio_enabled');
        register_setting('mecom_settings', 'mecom_twilio_account_sid');
        register_setting('mecom_settings', 'mecom_twilio_auth_token');
        register_setting('mecom_settings', 'mecom_twilio_phone_number');
        register_setting('mecom_settings', 'mecom_twilio_message');
    }

    /**
     * Render admin page
     */
    public function render_admin_page() {
        include MECOM_PLUGIN_DIR . 'templates/admin-settings.php';
    }

    /**
     * When a new organizer is created
     */
    public function on_organizer_created($term_id, $tt_id) {
        if (get_option('mecom_auto_create_user', 'no') !== 'yes') {
            return;
        }
        
        $term = get_term($term_id, 'mec_organizer');
        if (!$term || is_wp_error($term)) {
            return;
        }
        
        $existing_user_id = get_term_meta($term_id, 'mecom_linked_user_id', true);
        if ($existing_user_id) {
            return;
        }
        
        $email = get_term_meta($term_id, 'email', true);
        
        if (empty($email)) {
            return;
        }
        
        $existing_user = get_user_by('email', $email);
        
        if ($existing_user) {
            $this->link_organizer_to_user($term_id, $existing_user->ID);
        } else {
            $this->create_user_for_organizer($term_id, $term, $email);
        }
    }

    /**
     * Create WordPress user for organizer
     */
    public function create_user_for_organizer($term_id, $term, $email, $password = null) {
        $username = sanitize_user($term->name, true);
        $username = str_replace(' ', '_', strtolower($username));
        
        $original_username = $username;
        $counter = 1;
        while (username_exists($username)) {
            $username = $original_username . '_' . $counter;
            $counter++;
        }
        
        if (!$password) {
            $password = wp_generate_password(12, true, true);
        }
        
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            error_log('MEC Organizer Manager: Failed to create user for organizer ' . $term_id . ': ' . $user_id->get_error_message());
            return false;
        }
        
        $user = new WP_User($user_id);
        $user->set_role(get_option('mecom_user_role', self::ORGANIZER_ROLE));
        
        wp_update_user(array(
            'ID' => $user_id,
            'display_name' => $term->name,
            'first_name' => $term->name,
            'description' => $term->description,
        ));
        
        $this->link_organizer_to_user($term_id, $user_id);
        $this->sync_organizer_to_user($term_id, $user_id);
        
        if (get_option('mecom_default_password_email', 'yes') === 'yes') {
            $this->send_welcome_email($user_id, $username, $password, $email, $term->name);
        }
        
        return $user_id;
    }

    /**
     * Link organizer to user (bidirectional)
     */
    public function link_organizer_to_user($term_id, $user_id) {
        $old_user_id = get_term_meta($term_id, 'mecom_linked_user_id', true);
        if ($old_user_id && $old_user_id != $user_id) {
            delete_user_meta($old_user_id, 'mecom_linked_organizer_id');
        }
        
        $old_term_id = get_user_meta($user_id, 'mecom_linked_organizer_id', true);
        if ($old_term_id && $old_term_id != $term_id) {
            delete_term_meta($old_term_id, 'mecom_linked_user_id');
        }
        
        update_term_meta($term_id, 'mecom_linked_user_id', $user_id);
        update_user_meta($user_id, 'mecom_linked_organizer_id', $term_id);
    }

    /**
     * Unlink organizer from user
     */
    public function unlink_organizer_from_user($term_id) {
        $user_id = get_term_meta($term_id, 'mecom_linked_user_id', true);
        
        if ($user_id) {
            delete_user_meta($user_id, 'mecom_linked_organizer_id');
        }
        
        delete_term_meta($term_id, 'mecom_linked_user_id');
    }

    /**
     * Sync organizer data to user
     */
    private function sync_organizer_to_user($term_id, $user_id) {
        $sync_fields = get_option('mecom_sync_fields', array('name', 'email', 'bio', 'thumbnail', 'social'));
        $term = get_term($term_id, 'mec_organizer');
        
        if (!$term || is_wp_error($term)) {
            return;
        }
        
        $user_data = array('ID' => $user_id);
        
        if (in_array('name', $sync_fields)) {
            $user_data['display_name'] = $term->name;
            $user_data['first_name'] = $term->name;
        }
        
        if (in_array('email', $sync_fields)) {
            $email = get_term_meta($term_id, 'email', true);
            if ($email && is_email($email)) {
                $user_data['user_email'] = $email;
            }
        }
        
        if (in_array('bio', $sync_fields)) {
            $bio = get_term_meta($term_id, 'mecas_organizer_bio', true);
            if (!$bio) {
                $bio = $term->description;
            }
            $user_data['description'] = $bio;
        }
        
        wp_update_user($user_data);
        
        if (in_array('thumbnail', $sync_fields)) {
            $thumbnail = get_term_meta($term_id, 'thumbnail', true);
            if ($thumbnail) {
                update_user_meta($user_id, 'mecom_profile_image', $thumbnail);
            }
        }
        
        if (in_array('social', $sync_fields)) {
            $social_fields = array('instagram', 'twitter', 'facebook', 'mecas_organizer_tiktok');
            foreach ($social_fields as $field) {
                $value = get_term_meta($term_id, $field, true);
                if ($value) {
                    update_user_meta($user_id, 'mecom_' . $field, $value);
                }
            }
        }
        
        $custom_fields = array(
            'mecas_organizer_city' => 'mecom_city',
            'mecas_organizer_state' => 'mecom_state',
            'mecas_organizer_tagline' => 'mecom_tagline',
            'mecas_organizer_fun_fact' => 'mecom_fun_fact',
            'mecas_organizer_offerings' => 'mecom_offerings',
            'tel' => 'mecom_phone',
        );
        
        foreach ($custom_fields as $org_field => $user_field) {
            $value = get_term_meta($term_id, $org_field, true);
            if ($value) {
                update_user_meta($user_id, $user_field, $value);
            }
        }
    }

    /**
     * Send welcome email with branded template
     */
    public function send_welcome_email($user_id, $username, $password, $email, $name) {
        $site_name = get_bloginfo('name');
        $login_url = wp_login_url();
        $logo_url = get_option('mecom_email_logo', '');
        
        // Get custom logo or use site icon
        if (!$logo_url) {
            $custom_logo_id = get_theme_mod('custom_logo');
            if ($custom_logo_id) {
                $logo_url = wp_get_attachment_image_url($custom_logo_id, 'medium');
            }
        }
        
        $subject = sprintf(__('Welcome to %s - Your Host Account', 'mec-organizer-manager'), $site_name);
        
        ob_start();
        include MECOM_PLUGIN_DIR . 'templates/email-welcome.php';
        $message = ob_get_clean();
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $site_name . ' <' . get_option('admin_email') . '>',
        );
        
        wp_mail($email, $subject, $message, $headers);
    }

    /**
     * When organizer is updated
     */
    public function on_organizer_updated($term_id, $tt_id) {
        $user_id = get_term_meta($term_id, 'mecom_linked_user_id', true);
        
        if ($user_id) {
            $this->sync_organizer_to_user($term_id, $user_id);
        }
    }

    /**
     * When organizer is deleted
     */
    public function on_organizer_deleted($term_id, $tt_id, $deleted_term, $object_ids) {
        $user_id = get_term_meta($term_id, 'mecom_linked_user_id', true);
        
        if ($user_id) {
            delete_user_meta($user_id, 'mecom_linked_organizer_id');
        }
    }

    /**
     * Add user fields to organizer add form
     */
    public function add_organizer_user_fields() {
        $auto_create = get_option('mecom_auto_create_user', 'no') === 'yes';
        ?>
        <div class="form-field">
            <label><?php _e('Linked WordPress User', 'mec-organizer-manager'); ?></label>
            <?php if ($auto_create): ?>
            <p class="description"><?php _e('A WordPress user will be automatically created when you save this organizer (if email is provided).', 'mec-organizer-manager'); ?></p>
            <?php else: ?>
            <p class="description"><?php _e('After creating this organizer, you can link it to a user from the Organizer Manager settings.', 'mec-organizer-manager'); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Add user fields to organizer edit form
     */
    public function edit_organizer_user_fields($term) {
        $user_id = get_term_meta($term->term_id, 'mecom_linked_user_id', true);
        $user = $user_id ? get_user_by('ID', $user_id) : null;
        
        $users = get_users(array(
            'orderby' => 'display_name',
            'order' => 'ASC',
            'number' => 100,
        ));
        ?>
        <tr class="form-field">
            <th scope="row"><label><?php _e('Linked WordPress User', 'mec-organizer-manager'); ?></label></th>
            <td>
                <?php if ($user): ?>
                    <div class="mecom-linked-user-info">
                        <p>
                            <strong><?php echo esc_html($user->display_name); ?></strong> 
                            (<?php echo esc_html($user->user_email); ?>)
                            - <a href="<?php echo get_edit_user_link($user_id); ?>"><?php _e('Edit User', 'mec-organizer-manager'); ?></a>
                        </p>
                        <p>
                            <button type="button" class="button mecom-unlink-user" data-term-id="<?php echo esc_attr($term->term_id); ?>">
                                <?php _e('Unlink User', 'mec-organizer-manager'); ?>
                            </button>
                        </p>
                    </div>
                <?php else: ?>
                    <div class="mecom-link-user-options">
                        <p>
                            <label><strong><?php _e('Link Existing User:', 'mec-organizer-manager'); ?></strong></label><br>
                            <select name="mecom_link_existing_user" id="mecom_link_existing_user" style="width: 300px;">
                                <option value=""><?php _e('— Select User —', 'mec-organizer-manager'); ?></option>
                                <?php foreach ($users as $u): 
                                    $already_linked = get_user_meta($u->ID, 'mecom_linked_organizer_id', true);
                                    if ($already_linked) continue;
                                ?>
                                <option value="<?php echo esc_attr($u->ID); ?>">
                                    <?php echo esc_html($u->display_name); ?> (<?php echo esc_html($u->user_email); ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                        <p style="margin-top: 15px;">
                            <strong><?php _e('— OR —', 'mec-organizer-manager'); ?></strong>
                        </p>
                        <p>
                            <label>
                                <input type="checkbox" name="mecom_create_user_now" value="1">
                                <?php _e('Create a new user with organizer email', 'mec-organizer-manager'); ?>
                            </label>
                        </p>
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }

    /**
     * Save organizer user fields
     */
    public function save_organizer_user_fields($term_id) {
        if (!empty($_POST['mecom_link_existing_user'])) {
            $user_id = intval($_POST['mecom_link_existing_user']);
            if ($user_id) {
                $this->link_organizer_to_user($term_id, $user_id);
                $this->sync_organizer_to_user($term_id, $user_id);
            }
        }
        elseif (isset($_POST['mecom_create_user_now']) && $_POST['mecom_create_user_now'] === '1') {
            $term = get_term($term_id, 'mec_organizer');
            $email = get_term_meta($term_id, 'email', true);
            
            if (empty($email) || !is_email($email)) {
                return;
            }
            
            $existing_user = get_user_by('email', $email);
            
            if ($existing_user) {
                $this->link_organizer_to_user($term_id, $existing_user->ID);
            } else {
                $this->create_user_for_organizer($term_id, $term, $email);
            }
        }
    }

    /**
     * Filter events for organizers in admin
     */
    public function filter_events_for_organizer($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }
        
        $current_user = wp_get_current_user();
        
        if (in_array(self::ORGANIZER_ROLE, $current_user->roles)) {
            $organizer_id = get_user_meta($current_user->ID, 'mecom_linked_organizer_id', true);
            
            if ($organizer_id) {
                $query->set('tax_query', array(
                    array(
                        'taxonomy' => 'mec_organizer',
                        'field' => 'term_id',
                        'terms' => $organizer_id,
                    ),
                ));
            } else {
                $query->set('post__in', array(0));
            }
        }
    }

    /**
     * Filter organizer dropdown
     */
    public function filter_organizer_dropdown($args) {
        $current_user = wp_get_current_user();
        
        if (in_array(self::ORGANIZER_ROLE, $current_user->roles)) {
            $organizer_id = get_user_meta($current_user->ID, 'mecom_linked_organizer_id', true);
            
            if ($organizer_id) {
                $args['include'] = array($organizer_id);
            }
        }
        
        return $args;
    }

    /**
     * Auto-assign organizer when creating event
     */
    public function auto_assign_organizer($post_id, $post, $update) {
        if ($update) {
            return;
        }
        
        $current_user = wp_get_current_user();
        
        if (in_array(self::ORGANIZER_ROLE, $current_user->roles)) {
            $organizer_id = get_user_meta($current_user->ID, 'mecom_linked_organizer_id', true);
            
            if ($organizer_id) {
                wp_set_object_terms($post_id, array(intval($organizer_id)), 'mec_organizer');
            }
        }
    }

    /**
     * Redirect organizers after login
     */
    public function organizer_login_redirect($redirect_to, $requested_redirect_to, $user) {
        if (!is_wp_error($user) && isset($user->roles) && is_array($user->roles)) {
            if (in_array(self::ORGANIZER_ROLE, $user->roles)) {
                $organizer_id = get_user_meta($user->ID, 'mecom_linked_organizer_id', true);
                
                if ($organizer_id) {
                    // Redirect to their organizer page in admin (edit events)
                    return admin_url('edit.php?post_type=mec-events');
                }
            }
        }
        
        return $redirect_to;
    }

    /**
     * Add Elementor category
     */
    public function add_elementor_category($elements_manager) {
        $elements_manager->add_category('mec-organizer-manager', [
            'title' => __('MEC Organizer', 'mec-organizer-manager'),
            'icon' => 'eicon-person',
        ]);
    }

    /**
     * Register Elementor widgets
     */
    public function register_elementor_widgets($widgets_manager) {
        $widgets = array(
            'class-mecom-organizer-profile-widget.php' => 'MECOM_Organizer_Profile_Widget',
            'class-mecom-organizer-name-widget.php' => 'MECOM_Organizer_Name_Widget',
            'class-mecom-organizer-bio-widget.php' => 'MECOM_Organizer_Bio_Widget',
            'class-mecom-organizer-fun-fact-widget.php' => 'MECOM_Organizer_Fun_Fact_Widget',
            'class-mecom-organizer-offerings-widget.php' => 'MECOM_Organizer_Offerings_Widget',
            'class-mecom-organizer-social-widget.php' => 'MECOM_Organizer_Social_Widget',
            'class-mecom-organizer-events-widget.php' => 'MECOM_Organizer_Events_Widget',
            'class-mecom-organizer-image-widget.php' => 'MECOM_Organizer_Image_Widget',
            'class-mecom-host-registration-widget.php' => 'MECOM_Host_Registration_Widget',
        );
        
        foreach ($widgets as $file => $class) {
            $path = MECOM_PLUGIN_DIR . 'includes/elementor/' . $file;
            if (file_exists($path)) {
                require_once $path;
                if (class_exists($class)) {
                    $widgets_manager->register(new $class());
                }
            }
        }
    }

    /**
     * Register dynamic tags
     */
    public function register_dynamic_tags($dynamic_tags_manager) {
        $dynamic_tags_manager->register_group('mec-organizer', [
            'title' => __('MEC Organizer', 'mec-organizer-manager'),
        ]);
        
        $tags = array(
            'class-organizer-name-tag.php' => 'MECOM_Organizer_Name_Tag',
            'class-organizer-bio-tag.php' => 'MECOM_Organizer_Bio_Tag',
            'class-organizer-image-tag.php' => 'MECOM_Organizer_Image_Tag',
            'class-organizer-field-tag.php' => 'MECOM_Organizer_Field_Tag',
        );
        
        foreach ($tags as $file => $class) {
            $path = MECOM_PLUGIN_DIR . 'includes/elementor/dynamic-tags/' . $file;
            if (file_exists($path)) {
                require_once $path;
                if (class_exists($class)) {
                    $dynamic_tags_manager->register(new $class());
                }
            }
        }
    }

    /**
     * Register Elementor Theme Builder conditions for Organizer Profile pages
     */
    public function register_elementor_conditions($conditions_manager) {
        // Only if Elementor Pro is active
        if (!class_exists('\ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base')) {
            return;
        }
        
        require_once MECOM_PLUGIN_DIR . 'includes/elementor/class-mecom-elementor-conditions.php';
        
        // Register under 'archive' to show in the archive dropdown
        $archive_condition = $conditions_manager->get_condition('archive');
        if ($archive_condition) {
            $archive_condition->register_sub_condition(new MECOM_Elementor_Organizer_Condition());
        }
    }

    /**
     * Filter Elementor conditions to prevent author archive from matching on organizer pages
     */
    public function filter_elementor_conditions_cache($cache, $conditions_manager) {
        // If we're on an organizer profile page, prevent author archive from matching
        if (get_query_var('mecom_organizer_id')) {
            // This is an organizer profile page, not an author archive
            // The conditions will now properly match our custom condition instead
        }
        return $cache;
    }

    /**
     * Add profile link to admin bar
     */
    public function add_profile_link_to_admin_bar($wp_admin_bar) {
        if (!is_user_logged_in()) {
            return;
        }
        
        $user_id = get_current_user_id();
        $organizer_id = get_user_meta($user_id, 'mecom_linked_organizer_id', true);
        
        if (!$organizer_id) {
            return;
        }
        
        $organizer = get_term($organizer_id, 'mec_organizer');
        
        if (!$organizer || is_wp_error($organizer)) {
            return;
        }
        
        $slug = get_option('mecom_teacher_slug', 'teacher');
        $profile_url = home_url('/' . $slug . '/' . $organizer->slug . '/');
        
        $wp_admin_bar->add_node(array(
            'id' => 'mecom-view-profile',
            'title' => __('View My Profile', 'mec-organizer-manager'),
            'href' => $profile_url,
            'meta' => array('target' => '_blank'),
        ));
    }

    /**
     * Add organizer dashboard widget
     */
    public function add_organizer_dashboard_widget() {
        $current_user = wp_get_current_user();
        
        if (in_array(self::ORGANIZER_ROLE, $current_user->roles)) {
            wp_add_dashboard_widget(
                'mecom_organizer_dashboard',
                __('Your Events', 'mec-organizer-manager'),
                array($this, 'render_dashboard_widget')
            );
        }
    }

    /**
     * Render dashboard widget
     */
    public function render_dashboard_widget() {
        $user_id = get_current_user_id();
        $organizer_id = get_user_meta($user_id, 'mecom_linked_organizer_id', true);
        
        if (!$organizer_id) {
            echo '<p>' . __('Your account is not linked to an organizer profile.', 'mec-organizer-manager') . '</p>';
            return;
        }
        
        $events = get_posts(array(
            'post_type' => 'mec-events',
            'posts_per_page' => 5,
            'tax_query' => array(
                array(
                    'taxonomy' => 'mec_organizer',
                    'field' => 'term_id',
                    'terms' => $organizer_id,
                ),
            ),
        ));
        
        if (empty($events)) {
            echo '<p>' . __('No events found.', 'mec-organizer-manager') . '</p>';
            return;
        }
        
        echo '<ul>';
        foreach ($events as $event) {
            echo '<li><a href="' . get_edit_post_link($event->ID) . '">' . esc_html($event->post_title) . '</a></li>';
        }
        echo '</ul>';
        
        echo '<p><a href="' . admin_url('edit.php?post_type=mec-events') . '" class="button">' . __('View All Events', 'mec-organizer-manager') . '</a></p>';
    }

    /**
     * Login form shortcode
     */
    public function render_login_form($atts) {
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            $organizer_id = get_user_meta($user_id, 'mecom_linked_organizer_id', true);
            
            if ($organizer_id) {
                return '<p>' . sprintf(
                    __('You are logged in. <a href="%s">Go to Dashboard</a>', 'mec-organizer-manager'),
                    admin_url('edit.php?post_type=mec-events')
                ) . '</p>';
            }
        }
        
        ob_start();
        include MECOM_PLUGIN_DIR . 'templates/login-form.php';
        return ob_get_clean();
    }

    /**
     * Registration form shortcode (simple)
     */
    public function render_register_form($atts) {
        if (get_option('mecom_registration_enabled', 'yes') !== 'yes') {
            return '<p>' . __('Registration is currently disabled.', 'mec-organizer-manager') . '</p>';
        }
        
        if (is_user_logged_in()) {
            return $this->render_login_form($atts);
        }
        
        ob_start();
        include MECOM_PLUGIN_DIR . 'templates/register-form.php';
        return ob_get_clean();
    }

    /**
     * Multi-step host registration form shortcode
     */
    public function render_host_registration_form($atts) {
        $atts = shortcode_atts(array(
            'logo' => '',
            'redirect' => home_url(),
        ), $atts);
        
        if (get_option('mecom_registration_enabled', 'yes') !== 'yes') {
            return '<p>' . __('Registration is currently disabled.', 'mec-organizer-manager') . '</p>';
        }
        
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            $organizer_id = get_user_meta($user_id, 'mecom_linked_organizer_id', true);
            
            if ($organizer_id) {
                return '<p>' . sprintf(
                    __('You already have a host account. <a href="%s">Go to Dashboard</a>', 'mec-organizer-manager'),
                    admin_url('edit.php?post_type=mec-events')
                ) . '</p>';
            }
        }
        
        // Get logo
        $logo_url = $atts['logo'];
        if (!$logo_url) {
            $custom_logo_id = get_theme_mod('custom_logo');
            if ($custom_logo_id) {
                $logo_url = wp_get_attachment_image_url($custom_logo_id, 'medium');
            }
        }
        
        $recaptcha_enabled = get_option('mecom_recaptcha_enabled', 'no') === 'yes';
        $recaptcha_site_key = get_option('mecom_recaptcha_site_key', '');
        $redirect_url = $atts['redirect'];
        
        ob_start();
        include MECOM_PLUGIN_DIR . 'templates/host-registration-form.php';
        return ob_get_clean();
    }

    /**
     * Organizer dashboard shortcode
     */
    public function render_organizer_dashboard($atts) {
        if (!is_user_logged_in()) {
            return $this->render_login_form($atts);
        }
        
        $user_id = get_current_user_id();
        $organizer_id = get_user_meta($user_id, 'mecom_linked_organizer_id', true);
        
        if (!$organizer_id) {
            return '<p>' . __('Your account is not linked to an organizer profile.', 'mec-organizer-manager') . '</p>';
        }
        
        ob_start();
        include MECOM_PLUGIN_DIR . 'templates/organizer-dashboard.php';
        return ob_get_clean();
    }

    // ========================================
    // AJAX HANDLERS
    // ========================================

    /**
     * AJAX: Register host (multi-step form)
     */
    public function ajax_register_host() {
        // Suppress any PHP errors/warnings from breaking JSON output
        @ini_set('display_errors', 0);
        
        try {
            check_ajax_referer('mecom_registration_nonce', 'nonce');
            
            if (get_option('mecom_registration_enabled', 'yes') !== 'yes') {
                wp_send_json_error(__('Registration is disabled', 'mec-organizer-manager'));
            }
            
            // Verify reCAPTCHA if enabled
            if (get_option('mecom_recaptcha_enabled', 'no') === 'yes') {
                $recaptcha_response = isset($_POST['recaptcha_response']) ? $_POST['recaptcha_response'] : '';
                $secret_key = get_option('mecom_recaptcha_secret_key', '');
                
                if ($secret_key && $recaptcha_response) {
                    $verify = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
                        'body' => array(
                            'secret' => $secret_key,
                            'response' => $recaptcha_response,
                        ),
                    ));
                    
                    if (!is_wp_error($verify)) {
                        $verify_body = json_decode(wp_remote_retrieve_body($verify), true);
                        if (empty($verify_body['success'])) {
                            wp_send_json_error(__('reCAPTCHA verification failed', 'mec-organizer-manager'));
                        }
                    }
                }
            }
            
            // Sanitize inputs
            $name = sanitize_text_field($_POST['name'] ?? '');
            $email = sanitize_email($_POST['email'] ?? '');
            $phone = sanitize_text_field($_POST['phone'] ?? '');
            $phone_country = sanitize_text_field($_POST['phone_country'] ?? '+1');
            $location = sanitize_text_field($_POST['location'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Business info
            $business_name = sanitize_text_field($_POST['business_name'] ?? '');
            $business_address = sanitize_textarea_field($_POST['business_address'] ?? '');
            $business_ein = sanitize_text_field($_POST['business_ein'] ?? '');
            
            // Profile info
            $website = esc_url_raw($_POST['website'] ?? '');
            $social_links = isset($_POST['social_links']) ? array_map('esc_url_raw', (array)$_POST['social_links']) : array();
            $fun_facts = sanitize_textarea_field($_POST['fun_facts'] ?? '');
            $description = sanitize_textarea_field($_POST['description'] ?? '');
            $need_business_help = sanitize_text_field($_POST['need_business_help'] ?? 'no');
            
            // Validation
            if (empty($name) || empty($email) || empty($password)) {
                wp_send_json_error(__('Name, email, and password are required', 'mec-organizer-manager'));
            }
            
            if (!is_email($email)) {
                wp_send_json_error(__('Invalid email address', 'mec-organizer-manager'));
            }
            
            if (email_exists($email)) {
                wp_send_json_error(__('Email already registered', 'mec-organizer-manager'));
            }
            
            if (strlen($password) < 8) {
                wp_send_json_error(__('Password must be at least 8 characters', 'mec-organizer-manager'));
            }
            
            // Check if approval is required
            $require_approval = get_option('mecom_require_approval', 'yes') === 'yes';
            
            if ($require_approval) {
                // Ensure table exists
                $this->create_pending_registrations_table();
                
                // Store in pending registrations table
                global $wpdb;
                $table_name = $wpdb->prefix . 'mecom_pending_registrations';
                
                // Check if email already pending
                $existing = $wpdb->get_var($wpdb->prepare(
                    "SELECT id FROM $table_name WHERE email = %s AND status = 'pending'",
                    $email
                ));
                
                if ($existing) {
                    wp_send_json_error(__('A registration with this email is already pending review', 'mec-organizer-manager'));
                }
                
                $result = $wpdb->insert($table_name, array(
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'phone_country' => $phone_country,
                    'location' => $location,
                    'password_hash' => wp_hash_password($password),
                    'business_name' => $business_name,
                    'business_address' => $business_address,
                    'business_ein' => $business_ein,
                    'website' => $website,
                    'social_links' => json_encode($social_links),
                    'fun_facts' => $fun_facts,
                    'description' => $description,
                    'need_business_help' => $need_business_help,
                    'status' => 'pending',
                ), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
                
                if ($result === false) {
                    wp_send_json_error(__('Failed to submit registration: ', 'mec-organizer-manager') . $wpdb->last_error);
                }
                
                // Notify admin
                $this->notify_admin_new_registration($name, $email);
                
                wp_send_json_success(array(
                    'message' => __('Thank you for signing up! We will review your information and send you an email when your account is verified.', 'mec-organizer-manager'),
                    'pending' => true,
                ));
            } else {
                // Create user and organizer immediately
                $result = $this->create_host_account($name, $email, $password, $phone, $phone_country, $location, $business_name, $business_address, $business_ein, $website, $social_links, $fun_facts, $description, $need_business_help);
                
                if (is_wp_error($result)) {
                    wp_send_json_error($result->get_error_message());
                }
                
                wp_send_json_success(array(
                    'message' => __('Registration successful! You will receive an email with your login credentials.', 'mec-organizer-manager'),
                    'pending' => false,
                    'redirect' => wp_login_url(),
                ));
            }
        } catch (Exception $e) {
            wp_send_json_error('Server error: ' . $e->getMessage());
        }
    }

    /**
     * Create host account (user + organizer)
     */
    private function create_host_account($name, $email, $password, $phone, $phone_country, $location, $business_name, $business_address, $business_ein, $website, $social_links, $fun_facts, $description, $need_business_help) {
        // Create username
        $username = sanitize_user($name, true);
        $username = str_replace(' ', '_', strtolower($username));
        
        $original_username = $username;
        $counter = 1;
        while (username_exists($username)) {
            $username = $original_username . '_' . $counter;
            $counter++;
        }
        
        // Create user
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            return $user_id;
        }
        
        // Set role
        $user = new WP_User($user_id);
        $user->set_role(get_option('mecom_user_role', self::ORGANIZER_ROLE));
        
        // Update user data
        wp_update_user(array(
            'ID' => $user_id,
            'display_name' => $name,
            'first_name' => $name,
            'description' => $description,
        ));
        
        // Store phone
        update_user_meta($user_id, 'mecom_phone', $phone_country . $phone);
        
        // Create organizer
        $term = wp_insert_term($name, 'mec_organizer');
        
        if (is_wp_error($term)) {
            return $term;
        }
        
        $term_id = $term['term_id'];
        
        // Set organizer meta
        update_term_meta($term_id, 'email', $email);
        update_term_meta($term_id, 'tel', $phone_country . $phone);
        
        // Parse location for city/state
        if ($location) {
            $parts = array_map('trim', explode(',', $location));
            if (count($parts) >= 2) {
                update_term_meta($term_id, 'mecas_organizer_city', $parts[0]);
                update_term_meta($term_id, 'mecas_organizer_state', $parts[1]);
            } else {
                update_term_meta($term_id, 'mecas_organizer_city', $location);
            }
        }
        
        // Profile data
        if ($website) {
            update_term_meta($term_id, 'url', $website);
        }
        
        // Social links
        if (!empty($social_links)) {
            foreach ($social_links as $social_url) {
                if (strpos($social_url, 'instagram') !== false) {
                    update_term_meta($term_id, 'instagram', $social_url);
                } elseif (strpos($social_url, 'facebook') !== false) {
                    update_term_meta($term_id, 'facebook', $social_url);
                } elseif (strpos($social_url, 'twitter') !== false || strpos($social_url, 'x.com') !== false) {
                    update_term_meta($term_id, 'twitter', $social_url);
                } elseif (strpos($social_url, 'tiktok') !== false) {
                    update_term_meta($term_id, 'mecas_organizer_tiktok', $social_url);
                }
            }
        }
        
        // Bio and fun facts
        if ($fun_facts) {
            update_term_meta($term_id, 'mecas_organizer_fun_fact', $fun_facts);
        }
        if ($description) {
            update_term_meta($term_id, 'mecas_organizer_bio', $description);
        }
        
        // Business info (stored in user meta for now)
        if ($business_name) {
            update_user_meta($user_id, 'mecom_business_name', $business_name);
        }
        if ($business_address) {
            update_user_meta($user_id, 'mecom_business_address', $business_address);
        }
        if ($business_ein) {
            update_user_meta($user_id, 'mecom_business_ein', $business_ein);
        }
        if ($need_business_help === 'yes') {
            update_user_meta($user_id, 'mecom_need_business_help', 'yes');
        }
        
        // Link user and organizer
        $this->link_organizer_to_user($term_id, $user_id);
        
        // Send welcome email
        $this->send_welcome_email($user_id, $username, $password, $email, $name);
        
        return $user_id;
    }

    /**
     * Notify admin of new registration
     */
    private function notify_admin_new_registration($name, $email) {
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        
        $subject = sprintf(__('[%s] New Host Registration Pending', 'mec-organizer-manager'), $site_name);
        
        $message = sprintf(__('A new host registration is pending review:', 'mec-organizer-manager')) . "\n\n";
        $message .= sprintf(__('Name: %s', 'mec-organizer-manager'), $name) . "\n";
        $message .= sprintf(__('Email: %s', 'mec-organizer-manager'), $email) . "\n\n";
        $message .= sprintf(__('Review at: %s', 'mec-organizer-manager'), admin_url('admin.php?page=mec-organizer-manager&tab=registrations')) . "\n";
        
        wp_mail($admin_email, $subject, $message);
    }

    /**
     * AJAX: Approve registration
     */
    public function ajax_approve_registration() {
        check_ajax_referer('mecom_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied', 'mec-organizer-manager'));
        }
        
        $registration_id = intval($_POST['registration_id']);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'mecom_pending_registrations';
        
        $registration = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d AND status = 'pending'",
            $registration_id
        ));
        
        if (!$registration) {
            wp_send_json_error(__('Registration not found', 'mec-organizer-manager'));
        }
        
        // Generate new password (we can't recover the original)
        $password = wp_generate_password(12, true, true);
        
        // Create the account
        $social_links = json_decode($registration->social_links, true) ?: array();
        
        $result = $this->create_host_account(
            $registration->name,
            $registration->email,
            $password,
            $registration->phone,
            $registration->phone_country,
            $registration->location,
            $registration->business_name,
            $registration->business_address,
            $registration->business_ein,
            $registration->website,
            $social_links,
            $registration->fun_facts,
            $registration->description,
            $registration->need_business_help
        );
        
        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }
        
        // Update status
        $wpdb->update($table_name, array('status' => 'approved'), array('id' => $registration_id));
        
        wp_send_json_success(__('Registration approved. User has been created and notified.', 'mec-organizer-manager'));
    }

    /**
     * AJAX: Reject registration
     */
    public function ajax_reject_registration() {
        check_ajax_referer('mecom_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied', 'mec-organizer-manager'));
        }
        
        $registration_id = intval($_POST['registration_id']);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'mecom_pending_registrations';
        
        $registration = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d AND status = 'pending'",
            $registration_id
        ));
        
        if (!$registration) {
            wp_send_json_error(__('Registration not found', 'mec-organizer-manager'));
        }
        
        // Update status
        $wpdb->update($table_name, array('status' => 'rejected'), array('id' => $registration_id));
        
        // Optionally send rejection email
        // $this->send_rejection_email($registration->email, $registration->name);
        
        wp_send_json_success(__('Registration rejected.', 'mec-organizer-manager'));
    }

    /**
     * AJAX: Sync organizer data
     */
    public function ajax_sync_organizer() {
        check_ajax_referer('mecom_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }
        
        $term_id = intval($_POST['term_id']);
        $user_id = get_term_meta($term_id, 'mecom_linked_user_id', true);
        
        if ($user_id) {
            $this->sync_organizer_to_user($term_id, $user_id);
            wp_send_json_success('Synced successfully');
        } else {
            wp_send_json_error('No linked user found');
        }
    }

    /**
     * AJAX: Link user to organizer
     */
    public function ajax_link_user() {
        check_ajax_referer('mecom_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied', 'mec-organizer-manager'));
        }
        
        $term_id = intval($_POST['term_id']);
        $user_id = intval($_POST['user_id']);
        
        if (!$term_id || !$user_id) {
            wp_send_json_error(__('Invalid data', 'mec-organizer-manager'));
        }
        
        $user = get_user_by('ID', $user_id);
        if (!$user) {
            wp_send_json_error(__('User not found', 'mec-organizer-manager'));
        }
        
        $term = get_term($term_id, 'mec_organizer');
        if (!$term || is_wp_error($term)) {
            wp_send_json_error(__('Organizer not found', 'mec-organizer-manager'));
        }
        
        $this->link_organizer_to_user($term_id, $user_id);
        $this->sync_organizer_to_user($term_id, $user_id);
        
        wp_send_json_success(array(
            'message' => __('User linked successfully', 'mec-organizer-manager'),
            'user_name' => $user->display_name,
            'user_email' => $user->user_email,
            'user_id' => $user_id,
        ));
    }

    /**
     * AJAX: Unlink user from organizer
     */
    public function ajax_unlink_user() {
        check_ajax_referer('mecom_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied', 'mec-organizer-manager'));
        }
        
        $term_id = intval($_POST['term_id']);
        
        if (!$term_id) {
            wp_send_json_error(__('Invalid data', 'mec-organizer-manager'));
        }
        
        $this->unlink_organizer_from_user($term_id);
        
        wp_send_json_success(__('User unlinked successfully', 'mec-organizer-manager'));
    }

    /**
     * AJAX: Create user for organizer
     */
    public function ajax_create_user_for_organizer() {
        check_ajax_referer('mecom_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied', 'mec-organizer-manager'));
        }
        
        $term_id = intval($_POST['term_id']);
        
        if (!$term_id) {
            wp_send_json_error(__('Invalid data', 'mec-organizer-manager'));
        }
        
        $term = get_term($term_id, 'mec_organizer');
        if (!$term || is_wp_error($term)) {
            wp_send_json_error(__('Organizer not found', 'mec-organizer-manager'));
        }
        
        $existing = get_term_meta($term_id, 'mecom_linked_user_id', true);
        if ($existing) {
            wp_send_json_error(__('Organizer already has a linked user', 'mec-organizer-manager'));
        }
        
        $email = get_term_meta($term_id, 'email', true);
        
        if (empty($email) || !is_email($email)) {
            wp_send_json_error(__('Organizer does not have a valid email address', 'mec-organizer-manager'));
        }
        
        $existing_user = get_user_by('email', $email);
        if ($existing_user) {
            $this->link_organizer_to_user($term_id, $existing_user->ID);
            wp_send_json_success(array(
                'message' => __('Linked to existing user with this email', 'mec-organizer-manager'),
                'user_name' => $existing_user->display_name,
                'user_email' => $existing_user->user_email,
                'user_id' => $existing_user->ID,
            ));
            return;
        }
        
        $user_id = $this->create_user_for_organizer($term_id, $term, $email);
        
        if ($user_id) {
            $user = get_user_by('ID', $user_id);
            wp_send_json_success(array(
                'message' => __('User created and linked successfully', 'mec-organizer-manager'),
                'user_name' => $user->display_name,
                'user_email' => $user->user_email,
                'user_id' => $user_id,
            ));
        } else {
            wp_send_json_error(__('Failed to create user', 'mec-organizer-manager'));
        }
    }
    
    /**
     * AJAX: Send SMS verification code via Twilio
     */
    public function ajax_send_sms_code() {
        check_ajax_referer('mecom_registration_nonce', 'nonce');
        
        // Check if Twilio is enabled
        if (get_option('mecom_twilio_enabled', 'no') !== 'yes') {
            wp_send_json_error(__('SMS verification is not enabled', 'mec-organizer-manager'));
        }
        
        $phone_country = sanitize_text_field($_POST['phone_country'] ?? '+1');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        
        if (empty($phone)) {
            wp_send_json_error(__('Phone number is required', 'mec-organizer-manager'));
        }
        
        // Format phone number (remove non-digits except leading +)
        $phone_digits = preg_replace('/[^0-9]/', '', $phone);
        $full_phone = $phone_country . $phone_digits;
        
        // Rate limiting - max 3 SMS per phone per hour
        $rate_key = 'mecom_sms_rate_' . md5($full_phone);
        $rate_count = get_transient($rate_key);
        if ($rate_count && $rate_count >= 3) {
            wp_send_json_error(__('Too many verification attempts. Please try again in an hour.', 'mec-organizer-manager'));
        }
        
        // Generate 4-digit code
        $code = sprintf('%04d', mt_rand(0, 9999));
        
        // Store code in transient (expires in 10 minutes)
        $code_key = 'mecom_sms_code_' . md5($full_phone);
        set_transient($code_key, $code, 10 * MINUTE_IN_SECONDS);
        
        // Store attempt count
        set_transient($rate_key, ($rate_count ? $rate_count + 1 : 1), HOUR_IN_SECONDS);
        
        // Send SMS via Twilio
        $result = $this->send_twilio_sms($full_phone, $code);
        
        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }
        
        wp_send_json_success(array(
            'message' => __('Verification code sent!', 'mec-organizer-manager'),
            'phone' => $this->mask_phone($full_phone),
        ));
    }
    
    /**
     * AJAX: Verify SMS code
     */
    public function ajax_verify_sms_code() {
        check_ajax_referer('mecom_registration_nonce', 'nonce');
        
        $phone_country = sanitize_text_field($_POST['phone_country'] ?? '+1');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $code = sanitize_text_field($_POST['code'] ?? '');
        
        if (empty($phone) || empty($code)) {
            wp_send_json_error(__('Phone and code are required', 'mec-organizer-manager'));
        }
        
        // Format phone number
        $phone_digits = preg_replace('/[^0-9]/', '', $phone);
        $full_phone = $phone_country . $phone_digits;
        
        // Get stored code
        $code_key = 'mecom_sms_code_' . md5($full_phone);
        $stored_code = get_transient($code_key);
        
        if (!$stored_code) {
            wp_send_json_error(__('Verification code expired. Please request a new one.', 'mec-organizer-manager'));
        }
        
        if ($code !== $stored_code) {
            wp_send_json_error(__('Invalid verification code', 'mec-organizer-manager'));
        }
        
        // Code is valid - delete it so it can't be reused
        delete_transient($code_key);
        
        // Set a verification token for the registration process
        $verified_key = 'mecom_phone_verified_' . md5($full_phone);
        set_transient($verified_key, true, 30 * MINUTE_IN_SECONDS);
        
        wp_send_json_success(array(
            'message' => __('Phone verified successfully!', 'mec-organizer-manager'),
            'verified' => true,
        ));
    }
    
    /**
     * Send SMS via Twilio API
     */
    private function send_twilio_sms($to, $code) {
        $account_sid = get_option('mecom_twilio_account_sid', '');
        $auth_token = get_option('mecom_twilio_auth_token', '');
        $from = get_option('mecom_twilio_phone_number', '');
        $message_template = get_option('mecom_twilio_message', 'Your verification code is: {code}');
        
        if (empty($account_sid) || empty($auth_token) || empty($from)) {
            return new WP_Error('twilio_config', __('Twilio is not configured properly', 'mec-organizer-manager'));
        }
        
        // Build message
        $message = str_replace('{code}', $code, $message_template);
        
        // Twilio API endpoint
        $url = 'https://api.twilio.com/2010-04-01/Accounts/' . $account_sid . '/Messages.json';
        
        // Make API request
        $response = wp_remote_post($url, array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode($account_sid . ':' . $auth_token),
            ),
            'body' => array(
                'From' => $from,
                'To' => $to,
                'Body' => $message,
            ),
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            return new WP_Error('twilio_error', $response->get_error_message());
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($response_code !== 201 && $response_code !== 200) {
            $error_message = isset($response_body['message']) ? $response_body['message'] : __('Failed to send SMS', 'mec-organizer-manager');
            return new WP_Error('twilio_error', $error_message);
        }
        
        return true;
    }
    
    /**
     * Mask phone number for display
     */
    private function mask_phone($phone) {
        $length = strlen($phone);
        if ($length <= 4) {
            return $phone;
        }
        return substr($phone, 0, 3) . str_repeat('*', $length - 6) . substr($phone, -3);
    }
}

/**
 * Helper function to get organizer data
 */
function mecom_get_organizer_data($organizer_id) {
    $term = get_term($organizer_id, 'mec_organizer');
    
    if (!$term || is_wp_error($term)) {
        return null;
    }
    
    $thumbnail = get_term_meta($organizer_id, 'thumbnail', true);
    $tel = get_term_meta($organizer_id, 'tel', true);
    $email = get_term_meta($organizer_id, 'email', true);
    $page_url = get_term_meta($organizer_id, 'url', true);
    $page_label = get_term_meta($organizer_id, 'page_label', true);
    $facebook = get_term_meta($organizer_id, 'facebook', true);
    $instagram = get_term_meta($organizer_id, 'instagram', true);
    $twitter = get_term_meta($organizer_id, 'twitter', true);
    
    $city = get_term_meta($organizer_id, 'mecas_organizer_city', true);
    $state = get_term_meta($organizer_id, 'mecas_organizer_state', true);
    $tagline = get_term_meta($organizer_id, 'mecas_organizer_tagline', true);
    $bio = get_term_meta($organizer_id, 'mecas_organizer_bio', true);
    $fun_fact = get_term_meta($organizer_id, 'mecas_organizer_fun_fact', true);
    $offerings = get_term_meta($organizer_id, 'mecas_organizer_offerings', true);
    $tiktok = get_term_meta($organizer_id, 'mecas_organizer_tiktok', true);
    
    $linked_user_id = get_term_meta($organizer_id, 'mecom_linked_user_id', true);
    
    $location = $city;
    if ($state) {
        $location .= $city ? ', ' . $state : $state;
    }
    
    $slug = get_option('mecom_teacher_slug', 'teacher');
    $url = home_url('/' . $slug . '/' . $term->slug . '/');
    
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
        'twitter' => $twitter,
        'tiktok' => $tiktok,
        'linked_user_id' => $linked_user_id,
    );
}

/**
 * Get current organizer (for template use)
 */
function mecom_get_current_organizer() {
    $organizer_id = get_query_var('mecom_organizer_id');
    
    if (!$organizer_id && is_tax('mec_organizer')) {
        $term = get_queried_object();
        if ($term) {
            $organizer_id = $term->term_id;
        }
    }
    
    if (!$organizer_id) {
        return null;
    }
    
    return mecom_get_organizer_data($organizer_id);
}

// Initialize plugin
MEC_Organizer_Manager::get_instance();
