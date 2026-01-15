<?php
/**
 * Search Results Template
 * New design with category tabs, filters in one row, and redesigned event cards
 */

if (!defined('ABSPATH')) exit;

$widget_id = esc_attr($atts['widget_id']);
$columns = intval($atts['columns']);
$columns_tablet = isset($atts['columns_tablet']) ? intval($atts['columns_tablet']) : 2;
$columns_mobile = isset($atts['columns_mobile']) ? intval($atts['columns_mobile']) : 1;
$auto_detect = ($atts['enable_geolocation'] === 'true' && isset($atts['auto_detect_location']) && $atts['auto_detect_location'] === 'true') ? 'true' : 'false';
$show_divider = isset($atts['show_divider']) ? ($atts['show_divider'] === 'true') : true;
$date_format = isset($atts['date_format']) ? $atts['date_format'] : 'D, M j';
$time_format = isset($atts['time_format']) ? $atts['time_format'] : 'g:i A T';
$hosted_by_text = isset($atts['hosted_by_text']) ? $atts['hosted_by_text'] : __('Hosted by', 'mec-starter-addons');
$currency_symbol = isset($atts['currency_symbol']) ? $atts['currency_symbol'] : '$';

// Get MEC plugin instance for accessing settings
$mec_main = class_exists('MEC') ? MEC::instance() : null;
?>

<style>
    #<?php echo $widget_id; ?> .mecas-results-grid {
        grid-template-columns: repeat(<?php echo $columns; ?>, 1fr);
    }
    @media (max-width: 1024px) {
        #<?php echo $widget_id; ?> .mecas-results-grid {
            grid-template-columns: repeat(<?php echo $columns_tablet; ?>, 1fr);
        }
    }
    @media (max-width: 767px) {
        #<?php echo $widget_id; ?> .mecas-results-grid {
            grid-template-columns: repeat(<?php echo $columns_mobile; ?>, 1fr);
        }
    }
</style>

<div class="mecas-results-wrapper" 
     id="<?php echo $widget_id; ?>"
     data-per-page="<?php echo esc_attr($atts['per_page']); ?>"
     data-no-results="<?php echo esc_attr($atts['no_results_text']); ?>"
     data-date-format="<?php echo esc_attr($date_format); ?>"
     data-time-format="<?php echo esc_attr($time_format); ?>"
     data-hosted-by="<?php echo esc_attr($hosted_by_text); ?>"
     data-currency="<?php echo esc_attr($currency_symbol); ?>">

    <?php if ($atts['show_search_bar'] === 'true'): ?>
    <!-- Search Bar -->
    <div class="mecas-search-wrapper mecas-results-search-bar" data-enable-geolocation="<?php echo esc_attr($atts['enable_geolocation']); ?>" data-auto-detect="<?php echo esc_attr($auto_detect); ?>">
        <form class="mecas-search-form" method="GET">
            <div class="mecas-search-container mecas-search-light">
                <div class="mecas-input-group mecas-search-input-group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mecas-input-icon"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input type="text" name="mecas_query" class="mecas-input mecas-query-input" placeholder="<?php echo esc_attr($atts['placeholder_search']); ?>" value="<?php echo esc_attr($query); ?>" autocomplete="off">
                </div>
                <?php if ($show_divider): ?>
                <div class="mecas-divider"></div>
                <?php endif; ?>
                <div class="mecas-input-group mecas-location-input-group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mecas-input-icon"><circle cx="12" cy="10" r="3"/><path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 6.9 8 11.7z"/></svg>
                    <input type="text" name="mecas_location" class="mecas-input mecas-location-input" placeholder="<?php echo esc_attr($atts['placeholder_location']); ?>" value="<?php echo esc_attr($location); ?>" autocomplete="off">
                    <?php if ($atts['enable_geolocation'] === 'true'): ?>
                    <div class="mecas-location-loading" style="display:none;">
                        <svg class="mecas-spinner" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/></svg>
                    </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="mecas-search-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <?php if ($atts['show_filters'] === 'true'): ?>
    <!-- Filters Row (all in one line) -->
    <div class="mecas-filters-row" style="display: flex !important; flex-direction: row !important; flex-wrap: wrap; gap: 15px; align-items: center;">
        <?php if (isset($atts['show_category_filter']) && $atts['show_category_filter'] === 'true' && !empty($categories)): ?>
        <select name="mecas_category" class="mecas-filter-select" style="width: auto !important; flex: 0 0 auto !important;">
            <option value=""><?php echo esc_html($atts['label_category'] ?? __('Category', 'mec-starter-addons')); ?></option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($category, $cat->slug); ?>><?php echo esc_html($cat->name); ?></option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>

        <?php if (isset($atts['show_organizer_filter']) && $atts['show_organizer_filter'] === 'true' && !empty($organizers)): ?>
        <select name="mecas_organizer" class="mecas-filter-select" style="width: auto !important; flex: 0 0 auto !important;">
            <option value=""><?php echo esc_html($atts['label_organizer']); ?></option>
            <?php foreach ($organizers as $org): ?>
            <option value="<?php echo esc_attr($org->slug); ?>" <?php selected($organizer, $org->slug); ?>><?php echo esc_html($org->name); ?></option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>

        <?php if (isset($atts['show_tag_filter']) && $atts['show_tag_filter'] === 'true' && !empty($tags)): ?>
        <select name="mecas_tag" class="mecas-filter-select" style="width: auto !important; flex: 0 0 auto !important;">
            <option value=""><?php echo esc_html($atts['label_tag']); ?></option>
            <?php foreach ($tags as $t): ?>
            <option value="<?php echo esc_attr($t->slug); ?>" <?php selected($tag, $t->slug); ?>><?php echo esc_html($t->name); ?></option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>

        <?php if (isset($atts['show_sort_filter']) && $atts['show_sort_filter'] === 'true'): ?>
        <select name="mecas_sort" class="mecas-filter-select" style="width: auto !important; flex: 0 0 auto !important;">
            <option value="date_asc" <?php selected($sort, 'date_asc'); ?>><?php esc_html_e('Date (Ascending)', 'mec-starter-addons'); ?></option>
            <option value="date_desc" <?php selected($sort, 'date_desc'); ?>><?php esc_html_e('Date (Descending)', 'mec-starter-addons'); ?></option>
            <option value="price_high" <?php selected($sort, 'price_high'); ?>><?php esc_html_e('Price (High to Low)', 'mec-starter-addons'); ?></option>
            <option value="price_low" <?php selected($sort, 'price_low'); ?>><?php esc_html_e('Price (Low to High)', 'mec-starter-addons'); ?></option>
            <option value="title_asc" <?php selected($sort, 'title_asc'); ?>><?php esc_html_e('Title (A-Z)', 'mec-starter-addons'); ?></option>
            <option value="title_desc" <?php selected($sort, 'title_desc'); ?>><?php esc_html_e('Title (Z-A)', 'mec-starter-addons'); ?></option>
        </select>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Results -->
    <div class="mecas-results-container">
        <?php if (!empty($results['events'])): ?>
        <div class="mecas-results-grid">
            <?php foreach ($results['events'] as $event): 
                $event_id = $event->ID;
                
                // Get event data
                $start_date = get_post_meta($event_id, 'mec_start_date', true);
                $start_time_hour = get_post_meta($event_id, 'mec_start_time_hour', true);
                $start_time_min = get_post_meta($event_id, 'mec_start_time_minutes', true);
                $start_time_ampm = get_post_meta($event_id, 'mec_start_time_ampm', true);
                $cost = get_post_meta($event_id, 'mec_cost', true);
                
                // Format date and time
                $formatted_date = $start_date ? date_i18n($date_format, strtotime($start_date)) : '';
                $formatted_time = '';
                if ($start_time_hour) {
                    $formatted_time = $start_time_hour . ':' . str_pad($start_time_min, 2, '0', STR_PAD_LEFT) . ' ' . strtoupper($start_time_ampm);
                    // Add timezone if available
                    $timezone = get_option('timezone_string');
                    if ($timezone) {
                        $tz_abbr = (new DateTime('now', new DateTimeZone($timezone)))->format('T');
                        $formatted_time .= ' ' . $tz_abbr;
                    }
                }
                
                // Location
                $location_id = get_post_meta($event_id, 'mec_location_id', true);
                $location_name = '';
                if ($location_id) {
                    $loc = get_term($location_id, 'mec_location');
                    if ($loc && !is_wp_error($loc)) {
                        $location_name = $loc->name;
                    }
                }
                
                // Organizer
                $organizer_terms = get_the_terms($event_id, 'mec_organizer');
                $organizer_name = '';
                if ($organizer_terms && !is_wp_error($organizer_terms)) {
                    $organizer_name = $organizer_terms[0]->name;
                }
                
                // Category badge (show category instead of tag)
                $event_categories = get_the_terms($event_id, 'mec_category');
                $badge_name = '';
                if ($event_categories && !is_wp_error($event_categories)) {
                    $badge_name = $event_categories[0]->name;
                }
                
                $image = get_the_post_thumbnail_url($event_id, 'medium_large');
            ?>
            <div class="mecas-event-card">
                <a href="<?php echo get_permalink($event_id); ?>">
                    <div class="mecas-card-image-wrapper">
                        <?php if ($image): ?>
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title($event)); ?>" class="mecas-card-image">
                        <?php else: ?>
                        <div class="mecas-card-image mecas-card-image-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <path d="m21 15-5-5L5 21"/>
                            </svg>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($cost): ?>
                        <span class="mecas-price-badge"><?php echo esc_html($currency_symbol . number_format((float)$cost, 2)); ?></span>
                        <?php endif; ?>
                        
                        <div class="mecas-date-bar">
                            <span class="mecas-date-text"><?php echo esc_html($formatted_date); ?><?php if ($formatted_time): ?> | <?php echo esc_html($formatted_time); ?><?php endif; ?></span>
                            <?php if ($badge_name): ?>
                            <span class="mecas-tag-badge"><?php echo esc_html($badge_name); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mecas-card-content">
                        <h3 class="mecas-card-title"><?php echo get_the_title($event); ?></h3>
                        <?php if ($location_name): ?>
                        <p class="mecas-card-location"><?php echo esc_html($location_name); ?></p>
                        <?php endif; ?>
                        <?php if ($organizer_name): ?>
                        <p class="mecas-card-organizer"><?php echo esc_html($hosted_by_text . ' ' . $organizer_name); ?></p>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($atts['show_pagination'] === 'true' && $results['max_pages'] > 1): ?>
        <!-- Pagination -->
        <div class="mecas-pagination">
            <?php
            $base_url = remove_query_arg('mecas_page');
            $current = $results['current_page'];
            $total = $results['max_pages'];

            if ($current > 1): ?>
                <a href="<?php echo esc_url(add_query_arg('mecas_page', $current - 1, $base_url)); ?>" class="mecas-page-prev">&laquo;</a>
            <?php endif;

            for ($i = 1; $i <= $total; $i++):
                if ($i == $current): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php elseif ($i == 1 || $i == $total || abs($i - $current) <= 2): ?>
                    <a href="<?php echo esc_url(add_query_arg('mecas_page', $i, $base_url)); ?>"><?php echo $i; ?></a>
                <?php elseif (abs($i - $current) == 3): ?>
                    <span class="dots">...</span>
                <?php endif;
            endfor;

            if ($current < $total): ?>
                <a href="<?php echo esc_url(add_query_arg('mecas_page', $current + 1, $base_url)); ?>" class="mecas-page-next">&raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <!-- No Results -->
        <div class="mecas-no-results">
            <svg class="mecas-no-results-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <p class="mecas-no-results-text"><?php echo esc_html($atts['no_results_text']); ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>
