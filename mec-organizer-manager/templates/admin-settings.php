<?php
/**
 * Admin Settings Page with Tabs
 * Includes: Settings, User Linking, Pending Registrations, Help
 */

if (!defined('ABSPATH')) exit;

$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'settings';
$teacher_slug = get_option('mecom_teacher_slug', 'teacher');

// Get all organizers
$organizers = get_terms(array(
    'taxonomy' => 'mec_organizer',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC',
));

// Get all users for linking dropdown
$users = get_users(array(
    'orderby' => 'display_name',
    'order' => 'ASC',
    'number' => 200,
));

// Create lookup of users already linked
$linked_users = array();
foreach ($organizers as $org) {
    $user_id = get_term_meta($org->term_id, 'mecom_linked_user_id', true);
    if ($user_id) {
        $linked_users[$user_id] = $org->term_id;
    }
}

// Get pending registrations
global $wpdb;
$table_name = $wpdb->prefix . 'mecom_pending_registrations';
$pending_registrations = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 'pending' ORDER BY created_at DESC");
$pending_count = count($pending_registrations);
?>

<div class="wrap mecom-admin-wrap">
    <h1><?php esc_html_e('MEC Organizer Manager', 'mec-organizer-manager'); ?></h1>
    
    <nav class="nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=mec-organizer-manager&tab=settings'); ?>" 
           class="nav-tab <?php echo $current_tab === 'settings' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Settings', 'mec-organizer-manager'); ?>
        </a>
        <a href="<?php echo admin_url('admin.php?page=mec-organizer-manager&tab=user-linking'); ?>" 
           class="nav-tab <?php echo $current_tab === 'user-linking' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('User Linking', 'mec-organizer-manager'); ?>
        </a>
        <a href="<?php echo admin_url('admin.php?page=mec-organizer-manager&tab=registrations'); ?>" 
           class="nav-tab <?php echo $current_tab === 'registrations' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Registrations', 'mec-organizer-manager'); ?>
            <?php if ($pending_count > 0): ?>
            <span class="mecom-badge"><?php echo $pending_count; ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo admin_url('admin.php?page=mec-organizer-manager&tab=help'); ?>" 
           class="nav-tab <?php echo $current_tab === 'help' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Help', 'mec-organizer-manager'); ?>
        </a>
    </nav>
    
    <div class="mecom-tab-content">
        
        <?php if ($current_tab === 'settings'): ?>
        <!-- SETTINGS TAB -->
        <form method="post" action="options.php">
            <?php settings_fields('mecom_settings'); ?>
            
            <div class="mecom-settings-section">
                <h2><?php esc_html_e('User Account Settings', 'mec-organizer-manager'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="mecom_auto_create_user"><?php esc_html_e('Auto-Create User', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <select id="mecom_auto_create_user" name="mecom_auto_create_user">
                                <option value="no" <?php selected(get_option('mecom_auto_create_user', 'no'), 'no'); ?>><?php _e('No', 'mec-organizer-manager'); ?></option>
                                <option value="yes" <?php selected(get_option('mecom_auto_create_user', 'no'), 'yes'); ?>><?php _e('Yes', 'mec-organizer-manager'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Automatically create a WordPress user when a new organizer is created (requires organizer email).', 'mec-organizer-manager'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mecom_user_role"><?php esc_html_e('User Role', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <select id="mecom_user_role" name="mecom_user_role">
                                <option value="mec_event_organizer" <?php selected(get_option('mecom_user_role', 'mec_event_organizer'), 'mec_event_organizer'); ?>><?php _e('Event Organizer (Custom)', 'mec-organizer-manager'); ?></option>
                                <option value="author" <?php selected(get_option('mecom_user_role', 'mec_event_organizer'), 'author'); ?>><?php _e('Author', 'mec-organizer-manager'); ?></option>
                                <option value="contributor" <?php selected(get_option('mecom_user_role', 'mec_event_organizer'), 'contributor'); ?>><?php _e('Contributor', 'mec-organizer-manager'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('The role assigned to new organizer users.', 'mec-organizer-manager'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mecom_default_password_email"><?php esc_html_e('Send Welcome Email', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <select id="mecom_default_password_email" name="mecom_default_password_email">
                                <option value="yes" <?php selected(get_option('mecom_default_password_email', 'yes'), 'yes'); ?>><?php _e('Yes', 'mec-organizer-manager'); ?></option>
                                <option value="no" <?php selected(get_option('mecom_default_password_email', 'yes'), 'no'); ?>><?php _e('No', 'mec-organizer-manager'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Send email with login credentials when a new user is created.', 'mec-organizer-manager'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="mecom-settings-section">
                <h2><?php esc_html_e('Registration Settings', 'mec-organizer-manager'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="mecom_registration_enabled"><?php esc_html_e('Enable Registration', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <select id="mecom_registration_enabled" name="mecom_registration_enabled">
                                <option value="yes" <?php selected(get_option('mecom_registration_enabled', 'yes'), 'yes'); ?>><?php _e('Yes', 'mec-organizer-manager'); ?></option>
                                <option value="no" <?php selected(get_option('mecom_registration_enabled', 'yes'), 'no'); ?>><?php _e('No', 'mec-organizer-manager'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Allow new hosts to register via the frontend form.', 'mec-organizer-manager'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mecom_require_approval"><?php esc_html_e('Require Admin Approval', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <select id="mecom_require_approval" name="mecom_require_approval">
                                <option value="yes" <?php selected(get_option('mecom_require_approval', 'yes'), 'yes'); ?>><?php _e('Yes', 'mec-organizer-manager'); ?></option>
                                <option value="no" <?php selected(get_option('mecom_require_approval', 'yes'), 'no'); ?>><?php _e('No (Instant Approval)', 'mec-organizer-manager'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('If yes, registrations go to "Pending" and must be approved. If no, accounts are created immediately.', 'mec-organizer-manager'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="mecom-settings-section">
                <h2><?php esc_html_e('Google reCAPTCHA', 'mec-organizer-manager'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="mecom_recaptcha_enabled"><?php esc_html_e('Enable reCAPTCHA', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <select id="mecom_recaptcha_enabled" name="mecom_recaptcha_enabled">
                                <option value="no" <?php selected(get_option('mecom_recaptcha_enabled', 'no'), 'no'); ?>><?php _e('No', 'mec-organizer-manager'); ?></option>
                                <option value="yes" <?php selected(get_option('mecom_recaptcha_enabled', 'no'), 'yes'); ?>><?php _e('Yes', 'mec-organizer-manager'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Enable Google reCAPTCHA v2 on registration form.', 'mec-organizer-manager'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mecom_recaptcha_site_key"><?php esc_html_e('Site Key', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mecom_recaptcha_site_key" name="mecom_recaptcha_site_key" value="<?php echo esc_attr(get_option('mecom_recaptcha_site_key', '')); ?>" class="regular-text">
                            <p class="description"><?php printf(__('Get your keys from %s', 'mec-organizer-manager'), '<a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA</a>'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mecom_recaptcha_secret_key"><?php esc_html_e('Secret Key', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <input type="password" id="mecom_recaptcha_secret_key" name="mecom_recaptcha_secret_key" value="<?php echo esc_attr(get_option('mecom_recaptcha_secret_key', '')); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="mecom-settings-section">
                <h2><?php esc_html_e('Twilio SMS Verification', 'mec-organizer-manager'); ?></h2>
                <p class="description"><?php esc_html_e('Send SMS verification codes to verify phone numbers during registration.', 'mec-organizer-manager'); ?></p>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="mecom_twilio_enabled"><?php esc_html_e('Enable SMS Verification', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <select id="mecom_twilio_enabled" name="mecom_twilio_enabled">
                                <option value="no" <?php selected(get_option('mecom_twilio_enabled', 'no'), 'no'); ?>><?php _e('No', 'mec-organizer-manager'); ?></option>
                                <option value="yes" <?php selected(get_option('mecom_twilio_enabled', 'no'), 'yes'); ?>><?php _e('Yes', 'mec-organizer-manager'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Enable phone verification via SMS on the registration form.', 'mec-organizer-manager'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mecom_twilio_account_sid"><?php esc_html_e('Account SID', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mecom_twilio_account_sid" name="mecom_twilio_account_sid" value="<?php echo esc_attr(get_option('mecom_twilio_account_sid', '')); ?>" class="regular-text">
                            <p class="description"><?php printf(__('Find this in your %s', 'mec-organizer-manager'), '<a href="https://console.twilio.com/" target="_blank">Twilio Console</a>'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mecom_twilio_auth_token"><?php esc_html_e('Auth Token', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <input type="password" id="mecom_twilio_auth_token" name="mecom_twilio_auth_token" value="<?php echo esc_attr(get_option('mecom_twilio_auth_token', '')); ?>" class="regular-text">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mecom_twilio_phone_number"><?php esc_html_e('Twilio Phone Number', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mecom_twilio_phone_number" name="mecom_twilio_phone_number" value="<?php echo esc_attr(get_option('mecom_twilio_phone_number', '')); ?>" class="regular-text" placeholder="+15551234567">
                            <p class="description"><?php esc_html_e('Your Twilio phone number in E.164 format (e.g., +15551234567)', 'mec-organizer-manager'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mecom_twilio_message"><?php esc_html_e('SMS Message', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mecom_twilio_message" name="mecom_twilio_message" value="<?php echo esc_attr(get_option('mecom_twilio_message', 'Your verification code is: {code}')); ?>" class="large-text">
                            <p class="description"><?php esc_html_e('Use {code} where the verification code should appear.', 'mec-organizer-manager'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="mecom-settings-section">
                <h2><?php esc_html_e('Profile Page Settings', 'mec-organizer-manager'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="mecom_teacher_slug"><?php esc_html_e('Profile URL Slug', 'mec-organizer-manager'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mecom_teacher_slug" name="mecom_teacher_slug" value="<?php echo esc_attr($teacher_slug); ?>" class="regular-text">
                            <p class="description">
                                <?php printf(__('Example: %s', 'mec-organizer-manager'), '<code>' . home_url('/' . $teacher_slug . '/jane-smith/') . '</code>'); ?>
                            </p>
                            <p class="description" style="color:#d63638;">
                                <?php esc_html_e('After changing, go to Settings → Permalinks and save to update.', 'mec-organizer-manager'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <?php submit_button(); ?>
        </form>
        
        <?php elseif ($current_tab === 'user-linking'): ?>
        <!-- USER LINKING TAB -->
        <div class="mecom-settings-section">
            <h2><?php esc_html_e('Link Organizers to WordPress Users', 'mec-organizer-manager'); ?></h2>
            <p><?php esc_html_e('Link each organizer to a WordPress user account. Linked users can log in and manage their own events.', 'mec-organizer-manager'); ?></p>
            
            <div class="mecom-linking-stats">
                <?php 
                $total = count($organizers);
                $linked = 0;
                foreach ($organizers as $org) {
                    if (get_term_meta($org->term_id, 'mecom_linked_user_id', true)) $linked++;
                }
                $unlinked = $total - $linked;
                ?>
                <span class="stat stat-total"><strong><?php echo $total; ?></strong> <?php esc_html_e('Total Organizers', 'mec-organizer-manager'); ?></span>
                <span class="stat stat-linked"><strong><?php echo $linked; ?></strong> <?php esc_html_e('Linked', 'mec-organizer-manager'); ?></span>
                <span class="stat stat-unlinked"><strong><?php echo $unlinked; ?></strong> <?php esc_html_e('Not Linked', 'mec-organizer-manager'); ?></span>
            </div>
            
            <?php if (!empty($organizers)): ?>
            <table class="wp-list-table widefat fixed striped mecom-linking-table">
                <thead>
                    <tr>
                        <th style="width: 30%;"><?php esc_html_e('Organizer', 'mec-organizer-manager'); ?></th>
                        <th style="width: 25%;"><?php esc_html_e('Email', 'mec-organizer-manager'); ?></th>
                        <th style="width: 30%;"><?php esc_html_e('Linked User', 'mec-organizer-manager'); ?></th>
                        <th style="width: 15%;"><?php esc_html_e('Actions', 'mec-organizer-manager'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($organizers as $org): 
                        $org_email = get_term_meta($org->term_id, 'email', true);
                        $linked_user_id = get_term_meta($org->term_id, 'mecom_linked_user_id', true);
                        $linked_user = $linked_user_id ? get_user_by('ID', $linked_user_id) : null;
                        $profile_url = home_url('/' . $teacher_slug . '/' . $org->slug . '/');
                    ?>
                    <tr class="mecom-organizer-row" data-term-id="<?php echo esc_attr($org->term_id); ?>">
                        <td>
                            <strong><?php echo esc_html($org->name); ?></strong>
                            <div class="row-actions">
                                <span><a href="<?php echo get_edit_term_link($org->term_id, 'mec_organizer'); ?>"><?php esc_html_e('Edit', 'mec-organizer-manager'); ?></a></span>
                                |
                                <span><a href="<?php echo esc_url($profile_url); ?>" target="_blank"><?php esc_html_e('View Profile', 'mec-organizer-manager'); ?></a></span>
                            </div>
                        </td>
                        <td>
                            <?php if ($org_email): ?>
                                <code><?php echo esc_html($org_email); ?></code>
                            <?php else: ?>
                                <em style="color: #999;"><?php esc_html_e('No email', 'mec-organizer-manager'); ?></em>
                            <?php endif; ?>
                        </td>
                        <td class="mecom-linked-user-cell">
                            <?php if ($linked_user): ?>
                                <div class="mecom-user-linked">
                                    <span class="dashicons dashicons-yes-alt" style="color: #00a32a;"></span>
                                    <strong><?php echo esc_html($linked_user->display_name); ?></strong>
                                    <br><small><?php echo esc_html($linked_user->user_email); ?></small>
                                </div>
                            <?php else: ?>
                                <div class="mecom-user-not-linked">
                                    <select class="mecom-user-select" data-term-id="<?php echo esc_attr($org->term_id); ?>">
                                        <option value=""><?php esc_html_e('— Select User —', 'mec-organizer-manager'); ?></option>
                                        <?php foreach ($users as $u): 
                                            if (isset($linked_users[$u->ID])) continue;
                                        ?>
                                        <option value="<?php echo esc_attr($u->ID); ?>">
                                            <?php echo esc_html($u->display_name); ?> (<?php echo esc_html($u->user_email); ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="mecom-actions-cell">
                            <?php if ($linked_user): ?>
                                <button type="button" class="button button-small mecom-unlink-btn" data-term-id="<?php echo esc_attr($org->term_id); ?>">
                                    <?php esc_html_e('Unlink', 'mec-organizer-manager'); ?>
                                </button>
                                <a href="<?php echo get_edit_user_link($linked_user_id); ?>" class="button button-small">
                                    <?php esc_html_e('Edit User', 'mec-organizer-manager'); ?>
                                </a>
                            <?php else: ?>
                                <button type="button" class="button button-primary button-small mecom-link-btn" data-term-id="<?php echo esc_attr($org->term_id); ?>">
                                    <?php esc_html_e('Link', 'mec-organizer-manager'); ?>
                                </button>
                                <?php if ($org_email): ?>
                                <button type="button" class="button button-small mecom-create-user-btn" data-term-id="<?php echo esc_attr($org->term_id); ?>" title="<?php esc_attr_e('Create a new user with this organizer\'s email', 'mec-organizer-manager'); ?>">
                                    <?php esc_html_e('Create User', 'mec-organizer-manager'); ?>
                                </button>
                                <?php endif; ?>
                            <?php endif; ?>
                            <span class="spinner"></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p><?php esc_html_e('No organizers found. Create organizers in MEC first.', 'mec-organizer-manager'); ?></p>
            <?php endif; ?>
        </div>
        
        <?php elseif ($current_tab === 'registrations'): ?>
        <!-- REGISTRATIONS TAB -->
        <div class="mecom-settings-section">
            <h2><?php esc_html_e('Pending Host Registrations', 'mec-organizer-manager'); ?></h2>
            <p><?php esc_html_e('Review and approve new host registration requests.', 'mec-organizer-manager'); ?></p>
            
            <?php if (!empty($pending_registrations)): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 20%;"><?php esc_html_e('Name', 'mec-organizer-manager'); ?></th>
                        <th style="width: 20%;"><?php esc_html_e('Email', 'mec-organizer-manager'); ?></th>
                        <th style="width: 15%;"><?php esc_html_e('Phone', 'mec-organizer-manager'); ?></th>
                        <th style="width: 15%;"><?php esc_html_e('Location', 'mec-organizer-manager'); ?></th>
                        <th style="width: 15%;"><?php esc_html_e('Date', 'mec-organizer-manager'); ?></th>
                        <th style="width: 15%;"><?php esc_html_e('Actions', 'mec-organizer-manager'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_registrations as $reg): ?>
                    <tr class="mecom-registration-row" data-id="<?php echo esc_attr($reg->id); ?>">
                        <td>
                            <strong><?php echo esc_html($reg->name); ?></strong>
                            <?php if ($reg->business_name): ?>
                            <br><small><?php echo esc_html($reg->business_name); ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html($reg->email); ?></td>
                        <td><?php echo esc_html($reg->phone_country . ' ' . $reg->phone); ?></td>
                        <td><?php echo esc_html($reg->location); ?></td>
                        <td><?php echo date_i18n(get_option('date_format'), strtotime($reg->created_at)); ?></td>
                        <td>
                            <button type="button" class="button button-primary button-small mecom-approve-btn" data-id="<?php echo esc_attr($reg->id); ?>">
                                <?php esc_html_e('Approve', 'mec-organizer-manager'); ?>
                            </button>
                            <button type="button" class="button button-small mecom-reject-btn" data-id="<?php echo esc_attr($reg->id); ?>">
                                <?php esc_html_e('Reject', 'mec-organizer-manager'); ?>
                            </button>
                            <button type="button" class="button button-small mecom-view-details-btn" data-id="<?php echo esc_attr($reg->id); ?>">
                                <?php esc_html_e('Details', 'mec-organizer-manager'); ?>
                            </button>
                            <span class="spinner"></span>
                        </td>
                    </tr>
                    <tr class="mecom-registration-details" data-id="<?php echo esc_attr($reg->id); ?>" style="display:none;">
                        <td colspan="6">
                            <div class="mecom-details-content">
                                <h4><?php esc_html_e('Registration Details', 'mec-organizer-manager'); ?></h4>
                                <div class="mecom-details-grid">
                                    <div>
                                        <strong><?php esc_html_e('Business Address:', 'mec-organizer-manager'); ?></strong><br>
                                        <?php echo esc_html($reg->business_address ?: '—'); ?>
                                    </div>
                                    <div>
                                        <strong><?php esc_html_e('EIN:', 'mec-organizer-manager'); ?></strong><br>
                                        <?php echo esc_html($reg->business_ein ?: '—'); ?>
                                    </div>
                                    <div>
                                        <strong><?php esc_html_e('Website:', 'mec-organizer-manager'); ?></strong><br>
                                        <?php echo $reg->website ? '<a href="' . esc_url($reg->website) . '" target="_blank">' . esc_html($reg->website) . '</a>' : '—'; ?>
                                    </div>
                                    <div>
                                        <strong><?php esc_html_e('Social Links:', 'mec-organizer-manager'); ?></strong><br>
                                        <?php 
                                        $social = json_decode($reg->social_links, true);
                                        if (!empty($social)) {
                                            foreach ($social as $link) {
                                                echo '<a href="' . esc_url($link) . '" target="_blank">' . esc_html($link) . '</a><br>';
                                            }
                                        } else {
                                            echo '—';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php if ($reg->fun_facts): ?>
                                <div style="margin-top: 15px;">
                                    <strong><?php esc_html_e('Fun Facts:', 'mec-organizer-manager'); ?></strong><br>
                                    <?php echo nl2br(esc_html($reg->fun_facts)); ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($reg->description): ?>
                                <div style="margin-top: 15px;">
                                    <strong><?php esc_html_e('Description:', 'mec-organizer-manager'); ?></strong><br>
                                    <?php echo nl2br(esc_html($reg->description)); ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($reg->need_business_help === 'yes'): ?>
                                <div style="margin-top: 15px; color: #2271b1;">
                                    <strong><?php esc_html_e('✓ Needs help setting up business entity', 'mec-organizer-manager'); ?></strong>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="mecom-empty-state">
                <span class="dashicons dashicons-yes-alt" style="font-size: 48px; color: #00a32a;"></span>
                <p><?php esc_html_e('No pending registrations.', 'mec-organizer-manager'); ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <?php elseif ($current_tab === 'help'): ?>
        <!-- HELP TAB -->
        <div class="mecom-settings-section">
            <h2><?php esc_html_e('Shortcodes', 'mec-organizer-manager'); ?></h2>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Shortcode', 'mec-organizer-manager'); ?></th>
                        <th><?php esc_html_e('Description', 'mec-organizer-manager'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>[mecom_host_registration]</code></td>
                        <td><?php esc_html_e('Multi-step host registration form (recommended).', 'mec-organizer-manager'); ?></td>
                    </tr>
                    <tr>
                        <td><code>[mecom_login_form]</code></td>
                        <td><?php esc_html_e('Simple login form for hosts.', 'mec-organizer-manager'); ?></td>
                    </tr>
                    <tr>
                        <td><code>[mecom_register_form]</code></td>
                        <td><?php esc_html_e('Simple registration form (basic version).', 'mec-organizer-manager'); ?></td>
                    </tr>
                    <tr>
                        <td><code>[mecom_organizer_dashboard]</code></td>
                        <td><?php esc_html_e('Dashboard for logged-in hosts.', 'mec-organizer-manager'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="mecom-settings-section">
            <h2><?php esc_html_e('Elementor Widgets', 'mec-organizer-manager'); ?></h2>
            <p><?php esc_html_e('The following Elementor widgets are available for building organizer profile pages:', 'mec-organizer-manager'); ?></p>
            <ul>
                <li><strong>Organizer Profile</strong> - <?php esc_html_e('Complete profile with photo, name, location, and share button', 'mec-organizer-manager'); ?></li>
                <li><strong>Organizer Name</strong> - <?php esc_html_e('Organizer name with optional icon', 'mec-organizer-manager'); ?></li>
                <li><strong>Organizer Bio</strong> - <?php esc_html_e('Biography section with title and content', 'mec-organizer-manager'); ?></li>
                <li><strong>Organizer Fun Fact</strong> - <?php esc_html_e('Fun fact section with decorative title', 'mec-organizer-manager'); ?></li>
                <li><strong>Organizer Offerings</strong> - <?php esc_html_e('List of services/offerings', 'mec-organizer-manager'); ?></li>
                <li><strong>Organizer Social</strong> - <?php esc_html_e('Social media links (Instagram, X, Facebook, TikTok)', 'mec-organizer-manager'); ?></li>
                <li><strong>Organizer Events</strong> - <?php esc_html_e('Grid of events by this organizer', 'mec-organizer-manager'); ?></li>
            </ul>
        </div>
        
        <div class="mecom-settings-section">
            <h2><?php esc_html_e('How It Works', 'mec-organizer-manager'); ?></h2>
            <ol>
                <li><strong><?php esc_html_e('Host Registration', 'mec-organizer-manager'); ?></strong> - <?php esc_html_e('Add the [mecom_host_registration] shortcode to a page. Users fill out the multi-step form.', 'mec-organizer-manager'); ?></li>
                <li><strong><?php esc_html_e('Review Registrations', 'mec-organizer-manager'); ?></strong> - <?php esc_html_e('Go to "Registrations" tab to approve or reject pending registrations.', 'mec-organizer-manager'); ?></li>
                <li><strong><?php esc_html_e('Account Created', 'mec-organizer-manager'); ?></strong> - <?php esc_html_e('On approval, a WordPress user and MEC Organizer are created and linked.', 'mec-organizer-manager'); ?></li>
                <li><strong><?php esc_html_e('Welcome Email', 'mec-organizer-manager'); ?></strong> - <?php esc_html_e('User receives email with credentials and login link.', 'mec-organizer-manager'); ?></li>
                <li><strong><?php esc_html_e('Host Dashboard', 'mec-organizer-manager'); ?></strong> - <?php esc_html_e('Host logs in and is redirected to their events dashboard.', 'mec-organizer-manager'); ?></li>
            </ol>
        </div>
        <?php endif; ?>
        
    </div>
</div>

<style>
.mecom-admin-wrap { max-width: 1200px; }
.mecom-tab-content { margin-top: 20px; }
.mecom-settings-section { background: #fff; padding: 20px; margin: 20px 0; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,0.04); }
.mecom-settings-section h2 { margin-top: 0; padding-bottom: 10px; border-bottom: 1px solid #eee; }
.mecom-settings-section ul, .mecom-settings-section ol { padding-left: 20px; }
.mecom-settings-section code { background: #f0f0f1; padding: 3px 6px; }

.mecom-badge { background: #d63638; color: #fff; border-radius: 10px; padding: 2px 8px; font-size: 11px; margin-left: 5px; }

.mecom-linking-stats { display: flex; gap: 30px; margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 4px; }
.mecom-linking-stats .stat { display: flex; align-items: center; gap: 8px; }
.mecom-linking-stats .stat strong { font-size: 24px; }
.mecom-linking-stats .stat-linked strong { color: #00a32a; }
.mecom-linking-stats .stat-unlinked strong { color: #d63638; }

.mecom-linking-table .mecom-user-linked { display: flex; align-items: flex-start; flex-wrap: wrap; gap: 5px; }
.mecom-linking-table .mecom-user-linked .dashicons { margin-top: 2px; }
.mecom-linking-table .mecom-user-select { width: 100%; max-width: 250px; }
.mecom-linking-table .mecom-actions-cell { display: flex; flex-wrap: wrap; gap: 5px; align-items: center; }
.mecom-linking-table .mecom-actions-cell .spinner { float: none; margin: 0; }
.mecom-linking-table .row-actions { color: #999; font-size: 12px; }
.mecom-linking-table .row-actions a { color: #2271b1; text-decoration: none; }
.mecom-organizer-row.processing, .mecom-registration-row.processing { opacity: 0.6; pointer-events: none; }
.mecom-organizer-row.processing .spinner, .mecom-registration-row.processing .spinner { visibility: visible; }

.mecom-empty-state { text-align: center; padding: 40px; }
.mecom-empty-state .dashicons { display: block; margin: 0 auto 15px; width: 48px; height: 48px; }

.mecom-details-content { background: #f9f9f9; padding: 20px; border-radius: 4px; }
.mecom-details-content h4 { margin: 0 0 15px; }
.mecom-details-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
</style>
