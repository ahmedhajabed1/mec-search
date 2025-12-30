<?php
/**
 * Search Results Template
 */

if (!defined('ABSPATH')) exit;

$widget_id = esc_attr($atts['widget_id']);
$columns = intval($atts['columns']);
$auto_detect = ($atts['enable_geolocation'] === 'true' && $atts['auto_detect_location'] === 'true') ? 'true' : 'false';
$show_divider = isset($atts['show_divider']) ? ($atts['show_divider'] === 'true') : true;
?>

<div class="mecas-results-wrapper" 
     id="<?php echo $widget_id; ?>"
     data-per-page="<?php echo esc_attr($atts['per_page']); ?>"
     data-no-results="<?php echo esc_attr($atts['no_results_text']); ?>">

    <?php if ($atts['show_search_bar'] === 'true'): ?>
    <!-- Search Bar -->
    <div class="mecas-search-wrapper" data-enable-geolocation="<?php echo esc_attr($atts['enable_geolocation']); ?>" data-auto-detect="<?php echo esc_attr($auto_detect); ?>" style="margin-bottom: 24px;">
        <form class="mecas-search-form" method="GET">
            <div class="mecas-search-container">
                <div class="mecas-input-group mecas-search-input-group">
                    <input type="text" name="mecas_query" class="mecas-input mecas-query-input" placeholder="<?php echo esc_attr($atts['placeholder_search']); ?>" value="<?php echo esc_attr($query); ?>" autocomplete="off">
                    <div class="mecas-suggestions mecas-query-suggestions"></div>
                </div>
                <?php if ($show_divider): ?>
                <div class="mecas-divider"></div>
                <?php endif; ?>
                <div class="mecas-input-group mecas-location-input-group">
                    <input type="text" name="mecas_location" class="mecas-input mecas-location-input" placeholder="<?php echo esc_attr($atts['placeholder_location']); ?>" value="<?php echo esc_attr($location); ?>" autocomplete="off">
                    <?php if ($atts['enable_geolocation'] === 'true'): ?>
                    <div class="mecas-location-loading" style="display:none;">
                        <svg class="mecas-spinner" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/></svg>
                    </div>
                    <?php endif; ?>
                    <div class="mecas-suggestions mecas-location-suggestions"></div>
                </div>
                <button type="submit" class="mecas-search-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <?php if ($atts['show_filters'] === 'true'): ?>
    <!-- Filters -->
    <div class="mecas-filters <?php echo $atts['filter_layout'] === 'vertical' ? 'mecas-filters-vertical' : ''; ?>">
        <?php if ($atts['show_category_filter'] === 'true' && !empty($categories)): ?>
        <div class="mecas-filter-group">
            <label class="mecas-filter-label"><?php echo esc_html($atts['label_category']); ?></label>
            <select name="mecas_category" class="mecas-filter-select">
                <option value=""><?php esc_html_e('All Categories', 'mec-advanced-search'); ?></option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($category, $cat->slug); ?>><?php echo esc_html($cat->name); ?> (<?php echo $cat->count; ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <?php if ($atts['show_label_filter'] === 'true' && !empty($labels)): ?>
        <div class="mecas-filter-group">
            <label class="mecas-filter-label"><?php echo esc_html($atts['label_label']); ?></label>
            <select name="mecas_label" class="mecas-filter-select">
                <option value=""><?php esc_html_e('All Labels', 'mec-advanced-search'); ?></option>
                <?php foreach ($labels as $lbl): ?>
                <option value="<?php echo esc_attr($lbl->slug); ?>" <?php selected($label, $lbl->slug); ?>><?php echo esc_html($lbl->name); ?> (<?php echo $lbl->count; ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <?php if ($atts['show_organizer_filter'] === 'true' && !empty($organizers)): ?>
        <div class="mecas-filter-group">
            <label class="mecas-filter-label"><?php echo esc_html($atts['label_organizer']); ?></label>
            <select name="mecas_organizer" class="mecas-filter-select">
                <option value=""><?php esc_html_e('All Organizers', 'mec-advanced-search'); ?></option>
                <?php foreach ($organizers as $org): ?>
                <option value="<?php echo esc_attr($org->slug); ?>" <?php selected($organizer, $org->slug); ?>><?php echo esc_html($org->name); ?> (<?php echo $org->count; ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <?php if ($atts['show_tag_filter'] === 'true' && !empty($tags)): ?>
        <div class="mecas-filter-group">
            <label class="mecas-filter-label"><?php echo esc_html($atts['label_tag']); ?></label>
            <select name="mecas_tag" class="mecas-filter-select">
                <option value=""><?php esc_html_e('All Tags', 'mec-advanced-search'); ?></option>
                <?php foreach ($tags as $t): ?>
                <option value="<?php echo esc_attr($t->slug); ?>" <?php selected($tag, $t->slug); ?>><?php echo esc_html($t->name); ?> (<?php echo $t->count; ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <button type="button" class="mecas-clear-filters"><?php echo esc_html($atts['label_clear']); ?></button>
    </div>
    <?php endif; ?>

    <!-- Results -->
    <div class="mecas-results-container">
        <div class="mecas-results-header">
            <span class="mecas-results-count"><?php printf(_n('%s event found', '%s events found', $results['total'], 'mec-advanced-search'), number_format_i18n($results['total'])); ?></span>
        </div>

        <?php if (!empty($results['events'])): ?>
        <div class="mecas-results-grid mecas-cols-<?php echo $columns; ?>">
            <?php foreach ($results['events'] as $event): 
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
                $image = get_the_post_thumbnail_url($event_id, 'medium');
            ?>
            <div class="mecas-event-card">
                <a href="<?php echo get_permalink($event_id); ?>">
                    <?php if ($image): ?>
                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title($event)); ?>" class="mecas-event-image">
                    <?php else: ?>
                    <div class="mecas-event-image-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <path d="m21 15-5-5L5 21"/>
                        </svg>
                    </div>
                    <?php endif; ?>
                    <div class="mecas-event-card-content">
                        <h3 class="mecas-event-title"><?php echo get_the_title($event); ?></h3>
                        <div class="mecas-event-meta">
                            <?php if ($start_date): ?>
                            <span class="mecas-event-date">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <?php echo date_i18n(get_option('date_format'), strtotime($start_date)); ?>
                            </span>
                            <?php endif; ?>
                            <?php if ($location_name): ?>
                            <span class="mecas-event-location">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <?php echo esc_html($location_name); ?>
                            </span>
                            <?php endif; ?>
                        </div>
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
                <a href="<?php echo esc_url(add_query_arg('mecas_page', $current - 1, $base_url)); ?>">&laquo; <?php esc_html_e('Prev', 'mec-advanced-search'); ?></a>
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
                <a href="<?php echo esc_url(add_query_arg('mecas_page', $current + 1, $base_url)); ?>"><?php esc_html_e('Next', 'mec-advanced-search'); ?> &raquo;</a>
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
