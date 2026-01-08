<?php
/**
 * Admin Settings Page
 */

if (!defined('ABSPATH')) exit;
?>

<div class="wrap mecas-admin-wrap">
    <h1><?php esc_html_e('MEC Starter Addons Settings', 'mec-starter-addons'); ?></h1>
    
    <form method="post" action="options.php">
        <?php settings_fields('mecas_settings'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="mecas_results_page"><?php esc_html_e('Search Results Page URL', 'mec-starter-addons'); ?></label>
                </th>
                <td>
                    <input type="url" id="mecas_results_page" name="mecas_results_page" value="<?php echo esc_attr(get_option('mecas_results_page', '')); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e('Enter the URL of the page where you placed the MEC Search Results widget.', 'mec-starter-addons'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="mecas_enable_geolocation"><?php esc_html_e('Enable Geolocation', 'mec-starter-addons'); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" id="mecas_enable_geolocation" name="mecas_enable_geolocation" value="1" <?php checked(get_option('mecas_enable_geolocation', 1)); ?>>
                        <?php esc_html_e('Allow automatic location detection using browser geolocation', 'mec-starter-addons'); ?>
                    </label>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>

    <hr>

    <h2><?php esc_html_e('Usage Instructions', 'mec-starter-addons'); ?></h2>
    
    <div class="mecas-usage-card">
        <h3><?php esc_html_e('Elementor Widgets', 'mec-starter-addons'); ?></h3>
        <p><?php esc_html_e('This plugin provides four Elementor widgets:', 'mec-starter-addons'); ?></p>
        <ul>
            <li><strong><?php esc_html_e('MEC Event Search', 'mec-starter-addons'); ?></strong> - <?php esc_html_e('The search bar widget for headers (inline or popup mode)', 'mec-starter-addons'); ?></li>
            <li><strong><?php esc_html_e('MEC Search Results', 'mec-starter-addons'); ?></strong> - <?php esc_html_e('The results page with category tabs, filters, and event cards', 'mec-starter-addons'); ?></li>
            <li><strong><?php esc_html_e('MEC Featured Events', 'mec-starter-addons'); ?></strong> - <?php esc_html_e('Display events marked as featured', 'mec-starter-addons'); ?></li>
            <li><strong><?php esc_html_e('MEC Teachers/Organizers', 'mec-starter-addons'); ?></strong> - <?php esc_html_e('Display organizers in a grid with location and tagline', 'mec-starter-addons'); ?></li>
        </ul>

        <h3 style="margin-top: 30px;"><?php esc_html_e('Featured Events', 'mec-starter-addons'); ?></h3>
        <p><?php esc_html_e('To mark an event as featured:', 'mec-starter-addons'); ?></p>
        <ol>
            <li><?php esc_html_e('Edit any MEC event', 'mec-starter-addons'); ?></li>
            <li><?php esc_html_e('Look for the "Featured Event" meta box on the right sidebar', 'mec-starter-addons'); ?></li>
            <li><?php esc_html_e('Check the box to mark as featured', 'mec-starter-addons'); ?></li>
        </ol>

        <h3 style="margin-top: 30px;"><?php esc_html_e('Organizer Fields', 'mec-starter-addons'); ?></h3>
        <p><?php esc_html_e('New fields have been added to MEC Organizers:', 'mec-starter-addons'); ?></p>
        <ul>
            <li><strong><?php esc_html_e('City', 'mec-starter-addons'); ?></strong> - <?php esc_html_e('The organizer\'s city', 'mec-starter-addons'); ?></li>
            <li><strong><?php esc_html_e('State', 'mec-starter-addons'); ?></strong> - <?php esc_html_e('State/Region abbreviation (e.g., FL, NY)', 'mec-starter-addons'); ?></li>
            <li><strong><?php esc_html_e('Tagline', 'mec-starter-addons'); ?></strong> - <?php esc_html_e('A short description or tagline', 'mec-starter-addons'); ?></li>
        </ul>
        <p><?php esc_html_e('Edit organizers at: MEC Settings → Organizers', 'mec-starter-addons'); ?></p>

        <h3 style="margin-top: 30px;"><?php esc_html_e('Shortcodes', 'mec-starter-addons'); ?></h3>
        
        <h4><?php esc_html_e('Search Bar:', 'mec-starter-addons'); ?></h4>
        <code>[mec_advanced_search mode="inline" enable_geolocation="true"]</code>
        
        <h4><?php esc_html_e('Search Results:', 'mec-starter-addons'); ?></h4>
        <code>[mec_search_results columns="4" per_page="12" show_category_tabs="true" show_filters="true"]</code>
        
        <h4><?php esc_html_e('Featured Events:', 'mec-starter-addons'); ?></h4>
        <code>[mec_featured_events columns="4" per_page="8"]</code>
        
        <h4><?php esc_html_e('Organizers Grid:', 'mec-starter-addons'); ?></h4>
        <code>[mec_organizers_grid columns="4" per_page="8" show_heart="true"]</code>
    </div>
</div>
