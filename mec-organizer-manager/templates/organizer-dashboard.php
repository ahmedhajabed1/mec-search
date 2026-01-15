<?php
/**
 * Organizer Dashboard Template
 */

if (!defined('ABSPATH')) exit;

$user_id = get_current_user_id();
$organizer_id = get_user_meta($user_id, 'mecom_linked_organizer_id', true);
$organizer = mecom_get_organizer_data($organizer_id);

$events = get_posts([
    'post_type' => 'mec-events',
    'posts_per_page' => 10,
    'meta_query' => [['key' => 'mec_organizer_id', 'value' => $organizer_id]],
    'orderby' => 'meta_value',
    'meta_key' => 'mec_start_date',
    'order' => 'ASC',
]);

$upcoming_count = count(array_filter($events, function($e) {
    $date = get_post_meta($e->ID, 'mec_start_date', true);
    return $date >= date('Y-m-d');
}));
?>

<div class="mecom-dashboard">
    <div class="mecom-dashboard-header">
        <div class="mecom-dashboard-welcome">
            <h1><?php printf(__('Welcome, %s!', 'mec-organizer-manager'), esc_html($organizer['name'])); ?></h1>
            <p class="mecom-dashboard-tagline"><?php echo esc_html($organizer['tagline']); ?></p>
        </div>
        <div class="mecom-dashboard-actions">
            <a href="<?php echo admin_url('post-new.php?post_type=mec-events'); ?>" class="mecom-btn mecom-btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <?php _e('Create Event', 'mec-organizer-manager'); ?>
            </a>
            <a href="<?php echo esc_url($organizer['url']); ?>" class="mecom-btn mecom-btn-outline" target="_blank">
                <?php _e('View Profile', 'mec-organizer-manager'); ?>
            </a>
        </div>
    </div>
    
    <div class="mecom-dashboard-stats">
        <div class="mecom-stat-card">
            <span class="mecom-stat-number"><?php echo count($events); ?></span>
            <span class="mecom-stat-label"><?php _e('Total Events', 'mec-organizer-manager'); ?></span>
        </div>
        <div class="mecom-stat-card">
            <span class="mecom-stat-number"><?php echo $upcoming_count; ?></span>
            <span class="mecom-stat-label"><?php _e('Upcoming', 'mec-organizer-manager'); ?></span>
        </div>
    </div>
    
    <div class="mecom-dashboard-section">
        <h2><?php _e('Your Events', 'mec-organizer-manager'); ?></h2>
        
        <?php if (!empty($events)): ?>
        <table class="mecom-events-table">
            <thead>
                <tr>
                    <th><?php _e('Event', 'mec-organizer-manager'); ?></th>
                    <th><?php _e('Date', 'mec-organizer-manager'); ?></th>
                    <th><?php _e('Status', 'mec-organizer-manager'); ?></th>
                    <th><?php _e('Actions', 'mec-organizer-manager'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): 
                    $date = get_post_meta($event->ID, 'mec_start_date', true);
                    $is_past = $date < date('Y-m-d');
                ?>
                <tr>
                    <td>
                        <a href="<?php echo get_permalink($event->ID); ?>"><?php echo esc_html($event->post_title); ?></a>
                    </td>
                    <td><?php echo $date ? date_i18n('M j, Y', strtotime($date)) : '-'; ?></td>
                    <td>
                        <span class="mecom-status mecom-status-<?php echo $is_past ? 'past' : 'upcoming'; ?>">
                            <?php echo $is_past ? __('Past', 'mec-organizer-manager') : __('Upcoming', 'mec-organizer-manager'); ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?php echo get_edit_post_link($event->ID); ?>" class="mecom-action-link"><?php _e('Edit', 'mec-organizer-manager'); ?></a>
                        <a href="<?php echo get_permalink($event->ID); ?>" class="mecom-action-link"><?php _e('View', 'mec-organizer-manager'); ?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><a href="<?php echo admin_url('edit.php?post_type=mec-events'); ?>"><?php _e('View All Events', 'mec-organizer-manager'); ?> →</a></p>
        <?php else: ?>
        <div class="mecom-empty-state">
            <p><?php _e('You haven\'t created any events yet.', 'mec-organizer-manager'); ?></p>
            <a href="<?php echo admin_url('post-new.php?post_type=mec-events'); ?>" class="mecom-btn mecom-btn-primary"><?php _e('Create Your First Event', 'mec-organizer-manager'); ?></a>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.mecom-dashboard { max-width: 1000px; margin: 0 auto; padding: 20px; }
.mecom-dashboard-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; flex-wrap: wrap; gap: 20px; }
.mecom-dashboard-welcome h1 { margin: 0 0 5px 0; font-size: 28px; }
.mecom-dashboard-tagline { color: #6B7280; margin: 0; }
.mecom-dashboard-actions { display: flex; gap: 10px; }
.mecom-btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; border-radius: 8px; font-weight: 500; text-decoration: none; transition: all 0.2s; }
.mecom-btn-primary { background: #2D3748; color: #fff; }
.mecom-btn-primary:hover { background: #1A202C; color: #fff; }
.mecom-btn-outline { background: #fff; color: #2D3748; border: 1px solid #D1D5DB; }
.mecom-btn-outline:hover { background: #F9FAFB; color: #2D3748; }
.mecom-dashboard-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin-bottom: 30px; }
.mecom-stat-card { background: #fff; padding: 24px; border-radius: 12px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.mecom-stat-number { display: block; font-size: 36px; font-weight: 700; color: #2D3748; }
.mecom-stat-label { color: #6B7280; font-size: 14px; }
.mecom-dashboard-section { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.mecom-dashboard-section h2 { margin: 0 0 20px 0; font-size: 20px; }
.mecom-events-table { width: 100%; border-collapse: collapse; }
.mecom-events-table th, .mecom-events-table td { padding: 12px; text-align: left; border-bottom: 1px solid #E5E7EB; }
.mecom-events-table th { font-weight: 600; color: #374151; }
.mecom-status { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
.mecom-status-upcoming { background: #D1FAE5; color: #065F46; }
.mecom-status-past { background: #E5E7EB; color: #6B7280; }
.mecom-action-link { color: #2563EB; text-decoration: none; margin-right: 12px; }
.mecom-action-link:hover { text-decoration: underline; }
.mecom-empty-state { text-align: center; padding: 40px; }
@media (max-width: 600px) { .mecom-dashboard-header { flex-direction: column; } .mecom-events-table { font-size: 14px; } }
</style>
