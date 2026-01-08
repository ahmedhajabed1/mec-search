<?php
/**
 * Teacher Search Template (Shortcode version)
 */

if (!defined('ABSPATH')) exit;

$widget_id = esc_attr($atts['widget_id']);
$columns = intval($atts['columns']);
$auto_detect = ($atts['enable_geolocation'] === 'true' && $atts['auto_detect_location'] === 'true') ? 'true' : 'false';
?>

<div class="mecas-teacher-search-wrapper" 
     id="<?php echo $widget_id; ?>"
     data-enable-geolocation="<?php echo esc_attr($atts['enable_geolocation']); ?>"
     data-auto-detect="<?php echo esc_attr($auto_detect); ?>"
     data-per-page="<?php echo esc_attr($atts['per_page']); ?>"
     data-columns="<?php echo $columns; ?>"
     data-show-count="<?php echo esc_attr($atts['show_count']); ?>"
     data-count-singular="<?php echo esc_attr($atts['count_text_singular']); ?>"
     data-count-plural="<?php echo esc_attr($atts['count_text_plural']); ?>"
     data-show-location-bar="<?php echo esc_attr($atts['show_location_bar']); ?>"
     data-show-heart="<?php echo esc_attr($atts['show_heart_icon']); ?>"
     data-show-name="<?php echo esc_attr($atts['show_name']); ?>"
     data-show-tagline="<?php echo esc_attr($atts['show_tagline']); ?>"
     data-show-pagination="<?php echo esc_attr($atts['show_pagination']); ?>"
     data-no-results="<?php echo esc_attr($atts['no_results_text']); ?>">
    
    <!-- Search Form -->
    <form class="mecas-teacher-search-form">
        <div class="mecas-teacher-search-bar">
            <input type="text" 
                   name="mecas_location" 
                   class="mecas-teacher-search-input" 
                   placeholder="<?php echo esc_attr($atts['placeholder_location']); ?>" 
                   value="<?php echo esc_attr($location); ?>"
                   autocomplete="off">
            <?php if ($atts['enable_geolocation'] === 'true'): ?>
            <div class="mecas-teacher-search-loading" style="display:none;">
                <svg class="mecas-spinner" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" stroke-opacity="0.25"/>
                    <path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/>
                </svg>
            </div>
            <?php endif; ?>
            <button type="submit" class="mecas-teacher-search-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </button>
        </div>
    </form>

    <!-- Results Count -->
    <p class="mecas-teacher-search-count" style="display: none;"></p>

    <!-- Results Grid -->
    <div class="mecas-teacher-search-grid mecas-teacher-cols-<?php echo $columns; ?>"></div>

    <!-- Pagination -->
    <div class="mecas-teacher-pagination"></div>

    <!-- No Results -->
    <div class="mecas-teacher-search-no-results" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.35-4.35"></path>
        </svg>
        <p><?php echo esc_html($atts['no_results_text']); ?></p>
    </div>

    <!-- Loading State -->
    <div class="mecas-teacher-search-loader" style="display: none;">
        <div class="mecas-loading-spinner"></div>
    </div>
</div>
