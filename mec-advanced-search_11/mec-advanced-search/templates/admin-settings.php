<?php
/**
 * Admin Settings Page
 */

if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
    <h1><?php esc_html_e('MEC Advanced Search Settings', 'mec-advanced-search'); ?></h1>
    
    <form method="post" action="options.php">
        <?php settings_fields('mecas_settings'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="mecas_results_page"><?php esc_html_e('Search Results Page URL', 'mec-advanced-search'); ?></label>
                </th>
                <td>
                    <input type="url" id="mecas_results_page" name="mecas_results_page" value="<?php echo esc_attr(get_option('mecas_results_page', '')); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e('Enter the URL of the page where you placed the [mec_search_results] shortcode or MEC Search Results widget.', 'mec-advanced-search'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="mecas_enable_geolocation"><?php esc_html_e('Enable Geolocation', 'mec-advanced-search'); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" id="mecas_enable_geolocation" name="mecas_enable_geolocation" value="1" <?php checked(get_option('mecas_enable_geolocation', 1)); ?>>
                        <?php esc_html_e('Allow automatic location detection using browser geolocation', 'mec-advanced-search'); ?>
                    </label>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>

    <hr>

    <h2><?php esc_html_e('Usage Instructions', 'mec-advanced-search'); ?></h2>
    
    <div class="card" style="max-width: 800px; padding: 20px;">
        <h3><?php esc_html_e('Elementor Widgets', 'mec-advanced-search'); ?></h3>
        <p><?php esc_html_e('This plugin provides two Elementor widgets:', 'mec-advanced-search'); ?></p>
        <ul style="list-style: disc; margin-left: 20px;">
            <li><strong><?php esc_html_e('MEC Event Search', 'mec-advanced-search'); ?></strong> - <?php esc_html_e('The search bar widget for headers, can be inline or popup mode', 'mec-advanced-search'); ?></li>
            <li><strong><?php esc_html_e('MEC Search Results', 'mec-advanced-search'); ?></strong> - <?php esc_html_e('The results page widget with filters', 'mec-advanced-search'); ?></li>
        </ul>

        <h3 style="margin-top: 20px;"><?php esc_html_e('Shortcodes', 'mec-advanced-search'); ?></h3>
        <p><?php esc_html_e('You can also use shortcodes:', 'mec-advanced-search'); ?></p>
        
        <h4><?php esc_html_e('Search Bar:', 'mec-advanced-search'); ?></h4>
        <code style="display: block; padding: 10px; background: #f5f5f5; margin-bottom: 15px;">[mec_advanced_search mode="inline" enable_geolocation="true"]</code>
        
        <h4><?php esc_html_e('Search Results with Filters:', 'mec-advanced-search'); ?></h4>
        <code style="display: block; padding: 10px; background: #f5f5f5; margin-bottom: 15px;">[mec_search_results columns="3" per_page="12" show_filters="true"]</code>

        <h3 style="margin-top: 20px;"><?php esc_html_e('Available Shortcode Attributes', 'mec-advanced-search'); ?></h3>
        
        <table class="widefat" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th><?php esc_html_e('Attribute', 'mec-advanced-search'); ?></th>
                    <th><?php esc_html_e('Default', 'mec-advanced-search'); ?></th>
                    <th><?php esc_html_e('Description', 'mec-advanced-search'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>mode</code></td>
                    <td>inline</td>
                    <td><?php esc_html_e('Display mode: "inline" or "popup"', 'mec-advanced-search'); ?></td>
                </tr>
                <tr>
                    <td><code>enable_geolocation</code></td>
                    <td>true</td>
                    <td><?php esc_html_e('Enable automatic location detection', 'mec-advanced-search'); ?></td>
                </tr>
                <tr>
                    <td><code>placeholder_search</code></td>
                    <td>Search events</td>
                    <td><?php esc_html_e('Placeholder text for search input', 'mec-advanced-search'); ?></td>
                </tr>
                <tr>
                    <td><code>placeholder_location</code></td>
                    <td>City, State</td>
                    <td><?php esc_html_e('Placeholder text for location input', 'mec-advanced-search'); ?></td>
                </tr>
                <tr>
                    <td><code>columns</code></td>
                    <td>3</td>
                    <td><?php esc_html_e('Number of columns in results grid (1-6)', 'mec-advanced-search'); ?></td>
                </tr>
                <tr>
                    <td><code>per_page</code></td>
                    <td>12</td>
                    <td><?php esc_html_e('Number of events per page', 'mec-advanced-search'); ?></td>
                </tr>
                <tr>
                    <td><code>show_filters</code></td>
                    <td>true</td>
                    <td><?php esc_html_e('Show filter dropdowns', 'mec-advanced-search'); ?></td>
                </tr>
                <tr>
                    <td><code>show_category_filter</code></td>
                    <td>true</td>
                    <td><?php esc_html_e('Show category filter', 'mec-advanced-search'); ?></td>
                </tr>
                <tr>
                    <td><code>show_label_filter</code></td>
                    <td>true</td>
                    <td><?php esc_html_e('Show label filter', 'mec-advanced-search'); ?></td>
                </tr>
                <tr>
                    <td><code>show_organizer_filter</code></td>
                    <td>true</td>
                    <td><?php esc_html_e('Show organizer filter', 'mec-advanced-search'); ?></td>
                </tr>
                <tr>
                    <td><code>show_tag_filter</code></td>
                    <td>true</td>
                    <td><?php esc_html_e('Show tag filter', 'mec-advanced-search'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
