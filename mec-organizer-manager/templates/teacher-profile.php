<?php
/**
 * Teacher Profile Template
 * This is the default template. Create a teacher-profile.php in your theme to override.
 */

if (!defined('ABSPATH')) exit;

$organizer = mecom_get_current_organizer();

if (!$organizer) {
    get_header();
    echo '<div class="mecom-not-found"><h1>' . __('Organizer Not Found', 'mec-organizer-manager') . '</h1></div>';
    get_footer();
    return;
}

get_header();
?>

<div class="mecom-teacher-profile">
    <div class="mecom-profile-container">
        <!-- Header Section -->
        <div class="mecom-profile-header">
            <div class="mecom-profile-photo-wrap">
                <?php if ($organizer['thumbnail']): ?>
                    <img src="<?php echo esc_url($organizer['thumbnail']); ?>" alt="<?php echo esc_attr($organizer['name']); ?>" class="mecom-profile-photo">
                <?php else: ?>
                    <div class="mecom-profile-photo-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                <?php endif; ?>
                
                <button type="button" class="mecom-share-button" onclick="mecomShareProfile()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="18" cy="5" r="3"></circle>
                        <circle cx="6" cy="12" r="3"></circle>
                        <circle cx="18" cy="19" r="3"></circle>
                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                    </svg>
                    <?php _e('Share Profile', 'mec-organizer-manager'); ?>
                </button>
            </div>
            
            <div class="mecom-profile-info">
                <h1 class="mecom-profile-name"><?php echo esc_html($organizer['name']); ?></h1>
                
                <?php if ($organizer['location']): ?>
                    <p class="mecom-profile-location">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <?php echo esc_html($organizer['location']); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($organizer['tagline']): ?>
                    <p class="mecom-profile-tagline"><?php echo esc_html($organizer['tagline']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Bio Section -->
        <?php if ($organizer['bio']): ?>
        <div class="mecom-profile-section">
            <div class="mecom-section-title-wrap">
                <h2 class="mecom-section-title"><?php _e('Bio', 'mec-organizer-manager'); ?></h2>
                <span class="mecom-section-line"></span>
            </div>
            <div class="mecom-profile-bio">
                <?php echo wpautop($organizer['bio']); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Fun Fact Section -->
        <?php if ($organizer['fun_fact']): ?>
        <div class="mecom-profile-section">
            <div class="mecom-section-title-wrap">
                <h2 class="mecom-section-title"><?php _e('Fun Fact', 'mec-organizer-manager'); ?></h2>
                <span class="mecom-section-line"></span>
            </div>
            <div class="mecom-profile-fun-fact">
                <?php echo wpautop($organizer['fun_fact']); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Offerings Section -->
        <?php if ($organizer['offerings']): ?>
        <div class="mecom-profile-section">
            <div class="mecom-section-title-wrap">
                <h2 class="mecom-section-title"><?php _e('Offerings', 'mec-organizer-manager'); ?></h2>
                <span class="mecom-section-line"></span>
            </div>
            <div class="mecom-profile-offerings">
                <?php echo wpautop($organizer['offerings']); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Social Links -->
        <?php 
        $social_links = array();
        if ($organizer['instagram']) $social_links['instagram'] = $organizer['instagram'];
        if ($organizer['facebook']) $social_links['facebook'] = $organizer['facebook'];
        if ($organizer['twitter']) $social_links['twitter'] = $organizer['twitter'];
        if ($organizer['linkedin']) $social_links['linkedin'] = $organizer['linkedin'];
        if ($organizer['tiktok']) $social_links['tiktok'] = $organizer['tiktok'];
        if ($organizer['page_url']) $social_links['website'] = $organizer['page_url'];
        
        if (!empty($social_links)):
        ?>
        <div class="mecom-profile-section">
            <div class="mecom-section-title-wrap">
                <h2 class="mecom-section-title"><?php _e('Connect', 'mec-organizer-manager'); ?></h2>
                <span class="mecom-section-line"></span>
            </div>
            <div class="mecom-social-links">
                <?php foreach ($social_links as $platform => $url): ?>
                    <a href="<?php echo esc_url($url); ?>" class="mecom-social-link mecom-social-<?php echo esc_attr($platform); ?>" target="_blank" rel="noopener">
                        <?php echo mecom_get_social_icon($platform); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Upcoming Events -->
        <div class="mecom-profile-section mecom-events-section">
            <div class="mecom-section-title-wrap">
                <h2 class="mecom-section-title"><?php _e('Upcoming Events', 'mec-organizer-manager'); ?></h2>
                <span class="mecom-section-line"></span>
            </div>
            <?php
            $events = get_posts(array(
                'post_type' => 'mec-events',
                'posts_per_page' => 6,
                'meta_query' => array(
                    array(
                        'key' => 'mec_organizer_id',
                        'value' => $organizer['id'],
                    ),
                    array(
                        'key' => 'mec_start_date',
                        'value' => date('Y-m-d'),
                        'compare' => '>=',
                        'type' => 'DATE',
                    ),
                ),
                'orderby' => 'meta_value',
                'meta_key' => 'mec_start_date',
                'order' => 'ASC',
            ));
            
            if (!empty($events)):
            ?>
            <div class="mecom-events-grid">
                <?php foreach ($events as $event): 
                    $start_date = get_post_meta($event->ID, 'mec_start_date', true);
                    $thumbnail = get_the_post_thumbnail_url($event->ID, 'medium');
                    $mec_cost = get_post_meta($event->ID, 'mec_cost', true);
                ?>
                <a href="<?php echo get_permalink($event->ID); ?>" class="mecom-event-card">
                    <div class="mecom-event-image-wrap">
                        <?php if ($thumbnail): ?>
                            <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($event->post_title); ?>" class="mecom-event-image">
                        <?php else: ?>
                            <div class="mecom-event-image-placeholder"></div>
                        <?php endif; ?>
                        
                        <?php if ($mec_cost): ?>
                            <span class="mecom-event-price">$<?php echo esc_html($mec_cost); ?></span>
                        <?php endif; ?>
                        
                        <div class="mecom-event-date-bar">
                            <?php echo date_i18n('D, M j | g:i A', strtotime($start_date)); ?>
                        </div>
                    </div>
                    <div class="mecom-event-content">
                        <h3 class="mecom-event-title"><?php echo esc_html($event->post_title); ?></h3>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="mecom-no-events"><?php _e('No upcoming events scheduled.', 'mec-organizer-manager'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function mecomShareProfile() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo esc_js($organizer['name']); ?>',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('<?php _e('Profile link copied!', 'mec-organizer-manager'); ?>');
    }
}
</script>

<?php
get_footer();

/**
 * Get social icon SVG
 */
function mecom_get_social_icon($platform) {
    $icons = array(
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
        'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        'twitter' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
        'tiktok' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>',
        'website' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>',
    );
    
    return $icons[$platform] ?? '';
}
