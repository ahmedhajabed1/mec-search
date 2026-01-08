<?php
/**
 * Organizers Grid Template
 */

if (!defined('ABSPATH')) exit;

$widget_id = esc_attr($atts['widget_id']);
$columns = intval($atts['columns']);
$columns_tablet = isset($atts['columns_tablet']) ? intval($atts['columns_tablet']) : 2;
$columns_mobile = isset($atts['columns_mobile']) ? intval($atts['columns_mobile']) : 1;
$show_heart = isset($atts['show_heart']) ? ($atts['show_heart'] === 'true') : true;
$link_to = isset($atts['link_to']) ? $atts['link_to'] : 'events';
$events_url = isset($atts['events_url']) ? $atts['events_url'] : '';
?>

<style>
    #<?php echo $widget_id; ?> .mecas-organizers-grid {
        grid-template-columns: repeat(<?php echo $columns; ?>, 1fr);
    }
    @media (max-width: 1024px) {
        #<?php echo $widget_id; ?> .mecas-organizers-grid {
            grid-template-columns: repeat(<?php echo $columns_tablet; ?>, 1fr);
        }
    }
    @media (max-width: 767px) {
        #<?php echo $widget_id; ?> .mecas-organizers-grid {
            grid-template-columns: repeat(<?php echo $columns_mobile; ?>, 1fr);
        }
    }
</style>

<div class="mecas-organizers-wrapper" id="<?php echo $widget_id; ?>">
    <?php if (!empty($organizers) && !is_wp_error($organizers)): ?>
    <div class="mecas-organizers-grid">
        <?php foreach ($organizers as $organizer): 
            $org_id = $organizer->term_id;
            $org_name = $organizer->name;
            $org_slug = $organizer->slug;
            
            // Get custom meta
            $city = get_term_meta($org_id, 'mecas_organizer_city', true);
            $state = get_term_meta($org_id, 'mecas_organizer_state', true);
            $tagline = get_term_meta($org_id, 'mecas_organizer_tagline', true);
            
            // Get organizer thumbnail from MEC
            $thumbnail = get_term_meta($org_id, 'thumbnail', true);
            
            // Build location string
            $location_str = '';
            if ($city) {
                $location_str = $city;
                if ($state) {
                    $location_str .= ', ' . $state;
                }
            }
            
            // Build link URL
            $link_url = '';
            if ($link_to === 'events' && $events_url) {
                $link_url = add_query_arg('mecas_organizer', $org_slug, $events_url);
            }
        ?>
        <div class="mecas-organizer-card">
            <?php if ($link_url): ?><a href="<?php echo esc_url($link_url); ?>"><?php endif; ?>
                <div class="mecas-organizer-image-wrapper">
                    <?php if ($thumbnail): ?>
                    <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($org_name); ?>" class="mecas-organizer-image">
                    <?php else: ?>
                    <div class="mecas-organizer-image mecas-organizer-image-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($location_str): ?>
                <div class="mecas-organizer-location-bar">
                    <span class="mecas-location-text"><?php echo esc_html($location_str); ?></span>
                    <?php if ($show_heart): ?>
                    <span class="mecas-heart-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <div class="mecas-organizer-content">
                    <h3 class="mecas-organizer-name"><?php echo esc_html($org_name); ?></h3>
                    <?php if ($tagline): ?>
                    <p class="mecas-organizer-tagline"><?php echo esc_html($tagline); ?></p>
                    <?php endif; ?>
                </div>
            <?php if ($link_url): ?></a><?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="mecas-no-results">
        <p class="mecas-no-results-text"><?php esc_html_e('No organizers found.', 'mec-starter-addons'); ?></p>
    </div>
    <?php endif; ?>
</div>
