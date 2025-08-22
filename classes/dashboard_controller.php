<?php
/**
 * Dashboard controller class
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cuadrodemando;

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
        $PAGE->set_url('/local/cuadrodemando/index.php');
        $PAGE->set_title(get_string('dashboard', 'local_cuadrodemando'));
        $PAGE->set_heading(get_string('dashboard', 'local_cuadrodemando'));
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
        $PAGE->set_url("/local/cuadrodemando/{$page}.php");
        $PAGE->set_title(get_string($page, 'local_cuadrodemando'));
        $PAGE->set_heading(get_string($page, 'local_cuadrodemando'));
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
        $PAGE->requires->css('/local/cuadrodemando/styles.css');
        
        // Load AdminLTE CSS and JS
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/adminlte/adminlte.min.css');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/adminlte/adminlte.min.js');
        
        // Load Chart.js
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/chart/chart.umd.js');
        
        // Load DataTables CSS and JS
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/datatables/datatables.min.css');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/datatables/datatables.min.js');
        
        // Load dashboard AMD modules
        $PAGE->requires->js_call_amd('local_cuadrodemando/dashboard', 'init');

        // Load Font Awesome css 
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/fontawesome/css/all.min.css');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/fontawesome/js/all.min.js');
    
        // Load CSS libraries from local thirdpartylibs
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/fontawesome/css/all.min.css');

        // AdminLTE CSS
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/adminlte/css/adminlte.min.css');

        // DataTables CSS from local files
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/datatables/css/dataTables.bootstrap4.min.css');
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/datatables-buttons/css/buttons.bootstrap4.min.css');

        // Custom dashboard CSS
        $PAGE->requires->css('/local/cuadrodemando/assets/css/dashboard.css');

        // Load JavaScript libraries in correct order from local files
        // IMPORTANT: Load Chart.js FIRST before other scripts
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/chart/chart.min.js');

        // jQuery Knob (for circular progress indicators)
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/jquery-knob/jquery.knob.min.js');

        // jQuery UI (for sortable widgets and interactions)
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/jquery-ui/jquery-ui.min.js');
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/jquery-ui/themes/ui-lightness/jquery-ui.css');

        // DataTables (for table functionality)
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/datatables/js/jquery.dataTables.min.js');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/datatables/js/dataTables.bootstrap4.min.js');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/datatables-buttons/js/dataTables.buttons.min.js');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/datatables-buttons/js/buttons.bootstrap4.min.js');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/jszip/jszip.min.js');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/pdfmake/pdfmake.min.js');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/pdfmake/vfs_fonts.js');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/datatables-buttons/js/buttons.html5.min.js');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/datatables-buttons/js/buttons.print.min.js');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/datatables-buttons/js/buttons.colVis.min.js');

        // AdminLTE JavaScript
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/adminlte/js/adminlte.min.js');

        // Add a small delay to ensure all libraries load before page scripts
        $PAGE->requires->js_init_code('
        console.log("=== Library Loading Check ===");
        console.log("Chart.js available:", typeof Chart !== "undefined");
        console.log("jQuery available:", typeof $ !== "undefined");
        console.log("jQuery Knob available:", typeof $.fn.knob !== "undefined");
        console.log("jQuery UI available:", typeof $.fn.sortable !== "undefined");
        console.log("DataTables available:", typeof $.fn.DataTable !== "undefined");
        console.log("AdminLTE available:", typeof AdminLTE !== "undefined");

        if (typeof Chart === "undefined") {
            console.error("Chart.js failed to load from: /local/cuadrodemando/thirdpartylibs/chart/chart.min.js");
        }

        // Set Chart.js defaults if available
        if (typeof Chart !== "undefined") {
            Chart.defaults.responsive = true;
            Chart.defaults.maintainAspectRatio = false;
            console.log("Chart.js defaults set successfully");
        }
        ');

        // Load charts module if charts are enabled
        if (get_config('local_cuadrodemando', 'enablecharts')) {
            $PAGE->requires->js_call_amd('local_cuadrodemando/charts', 'init');
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
        
        $contentfile = $CFG->dirroot . "/local/cuadrodemando/pages/{$page}.php";
        
        if (file_exists($contentfile)) {
            include($contentfile);
        } else {
            // Display 404 error
            include($CFG->dirroot . "/local/cuadrodemando/pages/404.php");
        }
    }
    
    /**
     * Get dashboard statistics
     * 
     * @return array Array of statistics
     */
    public static function get_statistics() {
        return local_cuadrodemando_get_stats();
    }

    /**
     * Handle language switching
     * 
     * @return void
     */
    public static function handle_language_switch() {
        global $SESSION;
        if (isset($_GET['lang'])) {
            $SESSION->lang = $_GET['lang'];
        }
    }
}
