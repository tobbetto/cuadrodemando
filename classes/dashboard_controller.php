<?php
/**
 * Dashboard controller class
 *
 * @package    local_dashboard
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dashboard;

defined('MOODLE_INTERNAL') || die();

/**
 * Main dashboard controller
 */
class dashboard_controller {
    
    /**
     * Display the main dashboard page
     * 
     * @return void
     */
    public static function display_dashboard() {
        global $PAGE, $OUTPUT;
        
        $context = \context_system::instance();
        $PAGE->set_context($context);
        $PAGE->set_url('/local/dashboard/index.php');
        $PAGE->set_title(get_string('dashboard', 'local_dashboard'));
        $PAGE->set_heading(get_string('dashboard', 'local_dashboard'));
        $PAGE->set_pagelayout('admin');
        
        // Load required CSS and JS
        self::load_assets();
        
        echo $OUTPUT->header();
        
        // Load dashboard content
        self::display_content('home');
        
        echo $OUTPUT->footer();
    }
    
    /**
     * Display specific page content
     * 
     * @param string $page The page to display
     * @return void
     */
    public static function display_page($page) {
        global $PAGE, $OUTPUT;
        
        $context = \context_system::instance();
        $PAGE->set_context($context);
        $PAGE->set_url("/local/dashboard/{$page}.php");
        $PAGE->set_title(get_string($page, 'local_dashboard'));
        $PAGE->set_heading(get_string($page, 'local_dashboard'));
        $PAGE->set_pagelayout('admin');
        
        // Load required CSS and JS
        self::load_assets();
        
        echo $OUTPUT->header();
        
        // Load page content
        self::display_content($page);
        
        echo $OUTPUT->footer();
    }
    
    /**
     * Load required CSS and JavaScript assets
     * 
     * @return void
     */
    private static function load_assets() {
        global $PAGE;
        
        // Load main dashboard CSS
        $PAGE->requires->css('/local/dashboard/styles.css');
        
        // Load AdminLTE CSS and JS
        $PAGE->requires->css('/local/dashboard/thirdpartylibs/adminlte/adminlte.min.css');
        $PAGE->requires->js('/local/dashboard/thirdpartylibs/adminlte/adminlte.min.js');
        
        // Load Chart.js
        $PAGE->requires->js('/local/dashboard/thirdpartylibs/chart/chart.umd.js');
        
        // Load DataTables CSS and JS
        $PAGE->requires->css('/local/dashboard/thirdpartylibs/datatables/datatables.min.css');
        $PAGE->requires->js('/local/dashboard/thirdpartylibs/datatables/datatables.min.js');
        
        // Load dashboard AMD modules
        $PAGE->requires->js_call_amd('local_dashboard/dashboard', 'init');
        
        // Load charts module if charts are enabled
        if (get_config('local_dashboard', 'enablecharts')) {
            $PAGE->requires->js_call_amd('local_dashboard/charts', 'init');
        }
    }
    
    /**
     * Display content for a specific page
     * 
     * @param string $page The page to display content for
     * @return void
     */
    private static function display_content($page) {
        global $CFG;
        
        $contentfile = $CFG->dirroot . "/local/dashboard/pages/{$page}.php";
        
        if (file_exists($contentfile)) {
            include($contentfile);
        } else {
            // Display 404 error
            include($CFG->dirroot . "/local/dashboard/pages/404.php");
        }
    }
    
    /**
     * Get dashboard statistics
     * 
     * @return array Array of statistics
     */
    public static function get_statistics() {
        return local_dashboard_get_stats();
    }
}
