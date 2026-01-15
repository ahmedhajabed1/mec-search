<?php
/**
 * Admin Settings Page - Merged MEC Starter Addons + User Addon
 */

if (!defined('ABSPATH')) exit;

$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
?>

<div class="wrap mecas-admin-wrap">
    <h1><?php esc_html_e('MEC Starter Addons Settings', 'mec-starter-addons'); ?></h1>
    
    <nav class="nav-tab-wrapper">
        <a href="?page=mec-starter-addons&tab=general" class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('General', 'mec-starter-addons'); ?></a>
        <a href="?page=mec-starter-addons&tab=themebuilder" class="nav-tab <?php echo $active_tab === 'themebuilder' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Theme Builder', 'mec-starter-addons'); ?></a>
        <a href="?page=mec-starter-addons&tab=customers" class="nav-tab <?php echo $active_tab === 'customers' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Customers', 'mec-starter-addons'); ?></a>
        <a href="?page=mec-starter-addons&tab=sms" class="nav-tab <?php echo $active_tab === 'sms' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('SMS Verification', 'mec-starter-addons'); ?></a>
        <a href="?page=mec-starter-addons&tab=usage" class="nav-tab <?php echo $active_tab === 'usage' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Usage Guide', 'mec-starter-addons'); ?></a>
    </nav>

    <form method="post" action="options.php">
        <?php settings_fields('mecas_settings'); ?>
        
        <?php if ($active_tab === 'general'): ?>
        <h2><?php esc_html_e('Search Settings', 'mec-starter-addons'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="mecas_results_page"><?php esc_html_e('Search Results Page URL', 'mec-starter-addons'); ?></label></th>
                <td>
                    <input type="url" id="mecas_results_page" name="mecas_results_page" value="<?php echo esc_attr(get_option('mecas_results_page', '')); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e('Enter the URL of the page where you placed the MEC Search Results widget.', 'mec-starter-addons'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="mecas_enable_geolocation"><?php esc_html_e('Enable Geolocation', 'mec-starter-addons'); ?></label></th>
                <td>
                    <label><input type="checkbox" id="mecas_enable_geolocation" name="mecas_enable_geolocation" value="1" <?php checked(get_option('mecas_enable_geolocation', 1)); ?>> <?php esc_html_e('Allow automatic location detection using browser geolocation', 'mec-starter-addons'); ?></label>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
        
        <?php elseif ($active_tab === 'themebuilder'): ?>
        <h2><?php esc_html_e('Elementor Theme Builder Settings', 'mec-starter-addons'); ?></h2>
        
        <div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px; margin-bottom: 20px;">
            <h3 style="margin-top: 0;"><?php esc_html_e('Debug Information', 'mec-starter-addons'); ?></h3>
            <?php
            $elementor_pro_active = class_exists('\ElementorPro\Modules\ThemeBuilder\Module');
            $has_single_template = false;
            $template_ids = [];
            
            if ($elementor_pro_active) {
                $conditions_manager = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager();
                $documents = $conditions_manager->get_documents_for_location('single');
                $has_single_template = !empty($documents);
                if ($has_single_template) {
                    $template_ids = array_keys($documents);
                }
            }
            
            // Check MEC single event settings
            $mec_single_style = get_option('mec_settings', []);
            $mec_single_style_value = isset($mec_single_style['single_event_style']) ? $mec_single_style['single_event_style'] : 'Not set';
            ?>
            <table class="widefat" style="max-width: 600px;">
                <tr>
                    <td><strong><?php esc_html_e('Elementor Pro Active:', 'mec-starter-addons'); ?></strong></td>
                    <td><?php echo $elementor_pro_active ? '<span style="color:green;">✓ Yes</span>' : '<span style="color:red;">✗ No</span>'; ?></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e('MEC Post Type:', 'mec-starter-addons'); ?></strong></td>
                    <td><?php echo post_type_exists('mec-events') ? '<span style="color:green;">✓ mec-events exists</span>' : '<span style="color:red;">✗ Not found</span>'; ?></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e('Single Template Found:', 'mec-starter-addons'); ?></strong></td>
                    <td><?php echo $has_single_template ? '<span style="color:green;">✓ Yes (IDs: ' . implode(', ', $template_ids) . ')</span>' : '<span style="color:orange;">✗ No template assigned to "single" location</span>'; ?></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e('MEC Single Event Style:', 'mec-starter-addons'); ?></strong></td>
                    <td><?php echo esc_html($mec_single_style_value); ?></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e('Override Enabled:', 'mec-starter-addons'); ?></strong></td>
                    <td><?php echo get_option('mecas_override_single_event', 'yes') === 'yes' ? '<span style="color:green;">✓ Yes</span>' : '<span style="color:red;">✗ No</span>'; ?></td>
                </tr>
            </table>
            
            <?php if (!$has_single_template): ?>
            <div style="background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-top: 15px;">
                <strong><?php esc_html_e('⚠️ No Single Template Found!', 'mec-starter-addons'); ?></strong>
                <p style="margin-bottom: 0;"><?php esc_html_e('Make sure your Elementor template has the condition set to: Singular → MEC Event → All MEC Events', 'mec-starter-addons'); ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <table class="form-table">
            <tr>
                <th scope="row"><label for="mecas_override_single_event"><?php esc_html_e('Override MEC Single Event', 'mec-starter-addons'); ?></label></th>
                <td>
                    <select id="mecas_override_single_event" name="mecas_override_single_event">
                        <option value="yes" <?php selected(get_option('mecas_override_single_event', 'yes'), 'yes'); ?>><?php esc_html_e('Yes - Use Elementor Template', 'mec-starter-addons'); ?></option>
                        <option value="no" <?php selected(get_option('mecas_override_single_event', 'yes'), 'no'); ?>><?php esc_html_e('No - Use MEC Default', 'mec-starter-addons'); ?></option>
                    </select>
                    <p class="description"><?php esc_html_e('When enabled, single MEC event pages will use your Elementor Theme Builder template instead of MEC\'s default layout.', 'mec-starter-addons'); ?></p>
                </td>
            </tr>
        </table>
        
        <div style="background: #fff3cd; padding: 15px; border: 1px solid #ffc107; border-radius: 4px; margin-top: 20px;">
            <h4 style="margin-top: 0;"><?php esc_html_e('Important: MEC Settings Required', 'mec-starter-addons'); ?></h4>
            <p><?php esc_html_e('For this to work, you may need to adjust MEC settings:', 'mec-starter-addons'); ?></p>
            <ol style="margin-bottom: 0;">
                <li><?php esc_html_e('Go to MEC Settings → Single Event', 'mec-starter-addons'); ?></li>
                <li><?php esc_html_e('Look for "Single Event Style" and try setting it to "Default" or "Theme"', 'mec-starter-addons'); ?></li>
                <li><?php esc_html_e('If there\'s a "Single Event Page" option, make sure it\'s NOT set to a specific page', 'mec-starter-addons'); ?></li>
                <li><?php esc_html_e('Clear all caches after making changes', 'mec-starter-addons'); ?></li>
            </ol>
        </div>
        <?php submit_button(); ?>
        
        <?php elseif ($active_tab === 'customers'): ?>
        <h2><?php esc_html_e('Customer Registration Settings', 'mec-starter-addons'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="mecas_registration_enabled"><?php esc_html_e('Enable Registration', 'mec-starter-addons'); ?></label></th>
                <td>
                    <select id="mecas_registration_enabled" name="mecas_registration_enabled">
                        <option value="yes" <?php selected(get_option('mecas_registration_enabled', 'yes'), 'yes'); ?>><?php esc_html_e('Yes', 'mec-starter-addons'); ?></option>
                        <option value="no" <?php selected(get_option('mecas_registration_enabled', 'yes'), 'no'); ?>><?php esc_html_e('No', 'mec-starter-addons'); ?></option>
                    </select>
                    <p class="description"><?php esc_html_e('Allow new customers to register.', 'mec-starter-addons'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="mecas_dashboard_page"><?php esc_html_e('Customer Dashboard Page', 'mec-starter-addons'); ?></label></th>
                <td>
                    <?php wp_dropdown_pages(array('name' => 'mecas_dashboard_page', 'id' => 'mecas_dashboard_page', 'selected' => get_option('mecas_dashboard_page', ''), 'show_option_none' => __('— Select —', 'mec-starter-addons'), 'option_none_value' => '')); ?>
                    <p class="description"><?php esc_html_e('Page where customers will be redirected after login.', 'mec-starter-addons'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="mecas_require_sms_verification"><?php esc_html_e('Require SMS Verification', 'mec-starter-addons'); ?></label></th>
                <td>
                    <select id="mecas_require_sms_verification" name="mecas_require_sms_verification">
                        <option value="yes" <?php selected(get_option('mecas_require_sms_verification', 'yes'), 'yes'); ?>><?php esc_html_e('Yes', 'mec-starter-addons'); ?></option>
                        <option value="no" <?php selected(get_option('mecas_require_sms_verification', 'yes'), 'no'); ?>><?php esc_html_e('No', 'mec-starter-addons'); ?></option>
                    </select>
                    <p class="description"><?php esc_html_e('Require phone verification during registration (requires Twilio setup).', 'mec-starter-addons'); ?></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
        
        <?php elseif ($active_tab === 'sms'): ?>
        <h2><?php esc_html_e('Twilio SMS Settings', 'mec-starter-addons'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="mecas_twilio_enabled"><?php esc_html_e('Enable Twilio', 'mec-starter-addons'); ?></label></th>
                <td>
                    <select id="mecas_twilio_enabled" name="mecas_twilio_enabled">
                        <option value="no" <?php selected(get_option('mecas_twilio_enabled', 'no'), 'no'); ?>><?php esc_html_e('No', 'mec-starter-addons'); ?></option>
                        <option value="yes" <?php selected(get_option('mecas_twilio_enabled', 'no'), 'yes'); ?>><?php esc_html_e('Yes', 'mec-starter-addons'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="mecas_twilio_account_sid"><?php esc_html_e('Account SID', 'mec-starter-addons'); ?></label></th>
                <td><input type="text" id="mecas_twilio_account_sid" name="mecas_twilio_account_sid" value="<?php echo esc_attr(get_option('mecas_twilio_account_sid', '')); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="mecas_twilio_auth_token"><?php esc_html_e('Auth Token', 'mec-starter-addons'); ?></label></th>
                <td><input type="password" id="mecas_twilio_auth_token" name="mecas_twilio_auth_token" value="<?php echo esc_attr(get_option('mecas_twilio_auth_token', '')); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="mecas_twilio_phone_number"><?php esc_html_e('Phone Number', 'mec-starter-addons'); ?></label></th>
                <td><input type="text" id="mecas_twilio_phone_number" name="mecas_twilio_phone_number" value="<?php echo esc_attr(get_option('mecas_twilio_phone_number', '')); ?>" class="regular-text" placeholder="+1234567890"></td>
            </tr>
            <tr>
                <th scope="row"><label for="mecas_twilio_message"><?php esc_html_e('Message Template', 'mec-starter-addons'); ?></label></th>
                <td>
                    <input type="text" id="mecas_twilio_message" name="mecas_twilio_message" value="<?php echo esc_attr(get_option('mecas_twilio_message', 'Your verification code is: {code}')); ?>" class="large-text">
                    <p class="description"><?php esc_html_e('Use {code} as placeholder for the verification code.', 'mec-starter-addons'); ?></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
        
        <?php elseif ($active_tab === 'usage'): ?>
        <div class="mecas-usage-card" style="background:#fff;padding:20px;border:1px solid #ccd0d4;margin-top:20px;">
            <h2>Elementor Widgets</h2>
            <h3>Search & Events</h3>
            <ul><li><strong>MEC Event Search</strong> - Search bar (inline/popup)</li><li><strong>MEC Search Results</strong> - Results with filters</li><li><strong>MEC Featured Events</strong> - Featured events grid</li><li><strong>MEC Upcoming Events</strong> - Upcoming events grid</li><li><strong>MEC Organizers Grid</strong> - Organizers display</li><li><strong>MEC Teacher Search</strong> - Search organizers by location</li><li><strong>MEC Events by Location</strong> - Search events by location</li></ul>
            <h3>Organizer Profile</h3>
            <ul><li><strong>Organizer Profile Card</strong></li><li><strong>Organizer Name</strong></li><li><strong>Organizer Bio</strong></li><li><strong>Organizer Fun Fact</strong></li><li><strong>Organizer Offerings</strong></li><li><strong>Organizer Social Links</strong></li><li><strong>Organizer Events</strong></li></ul>
            <h3>Customer/User</h3>
            <ul><li><strong>Customer Registration</strong> - Multi-step form with SMS</li><li><strong>User Profile Card</strong></li><li><strong>User Events</strong> - Upcoming/Past/Saved</li><li><strong>User Following</strong> - Followed organizers</li><li><strong>Edit Profile Form</strong></li><li><strong>Save Event Button</strong></li><li><strong>Share Event Button</strong></li></ul>
            <h3>Event Page</h3>
            <ul><li><strong>Event Title</strong> - With "Hosted by"</li><li><strong>Event Details Card</strong> - Date, time, location, price</li><li><strong>Event Gallery</strong> - Photo gallery with lightbox</li></ul>
            <hr style="margin:30px 0">
            <h2>Shortcodes</h2>
            <p><code>[mec_advanced_search mode="inline"]</code></p>
            <p><code>[mec_search_results columns="4" per_page="12"]</code></p>
            <p><code>[mec_featured_events columns="4" per_page="8"]</code></p>
            <p><code>[mec_upcoming_events columns="4" per_page="8"]</code></p>
            <p><code>[mec_organizers_grid columns="4" per_page="8"]</code></p>
            <p><code>[mec_teacher_search columns="6" per_page="24"]</code></p>
        </div>
        <?php endif; ?>
    </form>
</div>
<style>.mecas-admin-wrap{max-width:1200px}.mecas-usage-card h2{margin-top:30px;border-bottom:1px solid #eee;padding-bottom:10px}.mecas-usage-card h2:first-child{margin-top:0}.mecas-usage-card h3{color:#23282d;margin-top:20px}.mecas-usage-card ul{margin-left:20px}.mecas-usage-card li{margin-bottom:5px}.mecas-usage-card code{display:inline-block;background:#f5f5f5;padding:8px 12px;border-radius:4px}</style>
