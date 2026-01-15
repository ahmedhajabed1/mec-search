<?php
if (!defined('ABSPATH')) exit;
$widget_id = esc_attr($atts['widget_id']);
$columns = intval($atts['columns']);
$columns_tablet = isset($atts['columns_tablet']) ? intval($atts['columns_tablet']) : 2;
$columns_mobile = isset($atts['columns_mobile']) ? intval($atts['columns_mobile']) : 1;
$date_format = isset($atts['date_format']) ? $atts['date_format'] : 'D, M j';
$time_format = isset($atts['time_format']) ? $atts['time_format'] : 'g:i A T';
$hosted_by_text = isset($atts['hosted_by_text']) ? $atts['hosted_by_text'] : __('Hosted by', 'mec-starter-addons');
$currency_symbol = isset($atts['currency_symbol']) ? $atts['currency_symbol'] : '$';
$show_price = isset($atts['show_price']) ? ($atts['show_price'] === 'true') : true;
$show_category_tabs = isset($atts['show_category_tabs']) ? ($atts['show_category_tabs'] === 'true') : true;
$all_tab_text = isset($atts['all_tab_text']) ? $atts['all_tab_text'] : __('All', 'mec-starter-addons');
?>
<style>
#<?php echo $widget_id; ?> .mecas-featured-grid { grid-template-columns: repeat(<?php echo $columns; ?>, 1fr); }
@media (max-width: 1024px) { #<?php echo $widget_id; ?> .mecas-featured-grid { grid-template-columns: repeat(<?php echo $columns_tablet; ?>, 1fr); } }
@media (max-width: 767px) { #<?php echo $widget_id; ?> .mecas-featured-grid { grid-template-columns: repeat(<?php echo $columns_mobile; ?>, 1fr); } }
</style>
<div class="mecas-featured-wrapper" id="<?php echo $widget_id; ?>" data-per-page="<?php echo esc_attr($atts['per_page']); ?>" data-date-format="<?php echo esc_attr($date_format); ?>" data-time-format="<?php echo esc_attr($time_format); ?>" data-hosted-by="<?php echo esc_attr($hosted_by_text); ?>" data-currency="<?php echo esc_attr($currency_symbol); ?>">
<?php if ($show_category_tabs && !empty($categories)): ?>
<div class="mecas-category-tabs">
    <button type="button" class="mecas-category-tab active" data-category=""><?php echo esc_html($all_tab_text); ?></button>
    <?php foreach ($categories as $cat): ?>
    <button type="button" class="mecas-category-tab" data-category="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></button>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php if (!empty($events)): ?>
<div class="mecas-featured-grid">
    <?php foreach ($events as $event): 
        $event_id = $event->ID;
        $start_date = get_post_meta($event_id, 'mec_start_date', true);
        $start_time_hour = get_post_meta($event_id, 'mec_start_time_hour', true);
        $start_time_min = get_post_meta($event_id, 'mec_start_time_minutes', true);
        $start_time_ampm = get_post_meta($event_id, 'mec_start_time_ampm', true);
        $cost = get_post_meta($event_id, 'mec_cost', true);
        $formatted_date = $start_date ? date_i18n($date_format, strtotime($start_date)) : '';
        $formatted_time = '';
        if ($start_time_hour) {
            $formatted_time = $start_time_hour . ':' . str_pad($start_time_min, 2, '0', STR_PAD_LEFT) . ' ' . strtoupper($start_time_ampm);
            $timezone = get_option('timezone_string');
            if ($timezone) $formatted_time .= ' ' . (new DateTime('now', new DateTimeZone($timezone)))->format('T');
        }
        $location_id = get_post_meta($event_id, 'mec_location_id', true);
        $location_name = '';
        if ($location_id) { $loc = get_term($location_id, 'mec_location'); if ($loc && !is_wp_error($loc)) $location_name = $loc->name; }
        $organizer_terms = get_the_terms($event_id, 'mec_organizer');
        $organizer_name = ($organizer_terms && !is_wp_error($organizer_terms)) ? $organizer_terms[0]->name : '';
        $event_categories = get_the_terms($event_id, 'mec_category');
        $badge_name = ($event_categories && !is_wp_error($event_categories)) ? $event_categories[0]->name : '';
        $image = get_the_post_thumbnail_url($event_id, 'medium_large');
    ?>
    <div class="mecas-event-card">
        <a href="<?php echo get_permalink($event_id); ?>">
            <div class="mecas-card-image-wrapper">
                <?php if ($image): ?><img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title($event)); ?>" class="mecas-card-image"><?php else: ?><div class="mecas-card-image mecas-card-image-placeholder"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></div><?php endif; ?>
                <?php if ($show_price && $cost): ?><span class="mecas-price-badge"><?php echo esc_html($currency_symbol . number_format((float)$cost, 2)); ?></span><?php endif; ?>
                <div class="mecas-date-bar"><span class="mecas-date-text"><?php echo esc_html($formatted_date); ?><?php if ($formatted_time): ?> | <?php echo esc_html($formatted_time); ?><?php endif; ?></span><?php if ($badge_name): ?><span class="mecas-tag-badge"><?php echo esc_html($badge_name); ?></span><?php endif; ?></div>
            </div>
            <div class="mecas-card-content">
                <h3 class="mecas-card-title"><?php echo get_the_title($event); ?></h3>
                <?php if ($location_name): ?><p class="mecas-card-location"><?php echo esc_html($location_name); ?></p><?php endif; ?>
                <?php if ($organizer_name): ?><p class="mecas-card-organizer"><?php echo esc_html($hosted_by_text . ' ' . $organizer_name); ?></p><?php endif; ?>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?><div class="mecas-no-results"><p class="mecas-no-results-text"><?php esc_html_e('No featured events found.', 'mec-starter-addons'); ?></p></div><?php endif; ?>
</div>
