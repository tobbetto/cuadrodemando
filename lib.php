<?php
/**
 * Library of interface functions and constants for module dashboard
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Extends the global navigation tree by adding dashboard nodes if there is a relevant capability
 *
 * @param global_navigation $navigation An object representing the navigation tree
 */
function local_cuadrodemando_extend_navigation(global_navigation $navigation) {
    global $USER, $PAGE;
    
    $context = context_system::instance();
    
    if (has_capability('local/cuadrodemando:view', $context)) {
        $node = $navigation->add(
            get_string('dashboard', 'local_cuadrodemando'),
            new moodle_url('/local/cuadrodemando/index.php'),
            navigation_node::TYPE_CUSTOM,
            null,
            'local_cuadrodemando'
        );
        $node->showinflatnavigation = true;
    }
}

/**
 * Adds dashboard links to the settings navigation
 *
 * @param settings_navigation $navigation
 * @param context $context
 */
function local_cuadrodemando_extend_settings_navigation(settings_navigation $navigation, context $context) {
    global $PAGE;
    
    if (has_capability('local/cuadrodemando:view', context_system::instance())) {
        if ($settingnode = $navigation->find('siteadministration', navigation_node::TYPE_SITE_ADMIN)) {
            $node = $settingnode->add(
                get_string('dashboard', 'local_cuadrodemando'),
                new moodle_url('/local/cuadrodemando/index.php'),
                navigation_node::TYPE_SETTING
            );
        }
    }
}

/**
 * Get dashboard statistics for the home page
 *
 * @return array Dashboard statistics
 */
function local_cuadrodemando_get_stats() {
    global $DB;
    
    $stats = array();
    
    // Get total users
    $stats['total_users'] = $DB->count_records('user', array('deleted' => 0, 'suspended' => 0));
    
    // Get total courses
    $stats['total_courses'] = $DB->count_records('course', array('visible' => 1)) - 1; // Exclude site course
    
    // Get total enrollments
    $stats['total_enrollments'] = $DB->count_records_sql(
        "SELECT COUNT(*) FROM {user_enrolments} ue 
         JOIN {enrol} e ON e.id = ue.enrolid 
         WHERE ue.status = 0 AND e.status = 0"
    );
    
    return $stats;
}
