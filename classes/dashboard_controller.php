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
        
        // Handle language switching
        self::handle_language_switch();
        
        // Check capabilities
        $context = \context_system::instance();
        require_capability('local/cuadrodemando:view', $context);
        
        // Set up the page
        $PAGE->set_context($context);
        $PAGE->set_url('/local/cuadrodemando/index.php');
        $PAGE->set_title(get_string('dashboard', 'local_cuadrodemando'));
        $PAGE->set_heading(get_string('dashboard', 'local_cuadrodemando'));
        $PAGE->set_pagelayout('admin');
        
        // Load required CSS and JS assets
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
        
        // Handle language switching
        self::handle_language_switch();
        
        // Check capabilities
        $context = \context_system::instance();
        require_capability('local/cuadrodemando:view', $context);
        
        $PAGE->set_context($context);
        $PAGE->set_url("/local/cuadrodemando/{$page}.php");
        $PAGE->set_title(get_string($page, 'local_cuadrodemando'));
        $PAGE->set_heading(get_string($page, 'local_cuadrodemando'));
        $PAGE->set_pagelayout('admin');
        
        // Load required CSS and JS assets
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
        
        // Load CSS libraries first
        // Font Awesome CSS
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/fontawesome/css/all.min.css');
        
        // AdminLTE CSS
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/adminlte/css/adminlte.min.css');
        
        // jQuery UI CSS
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/jquery-ui/themes/ui-lightness/jquery-ui.css');
        
        // DataTables CSS
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/datatables/css/dataTables.bootstrap4.min.css');
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/datatables-buttons/css/buttons.bootstrap4.min.css');
        
        // Custom dashboard CSS
        $PAGE->requires->css('/local/cuadrodemando/assets/css/dashboard.css');
        
        // Load JavaScript libraries in correct order
        // 1. Chart.js (load first)
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/chart/chart.min.js');
        
        // 2. jQuery Knob
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/jquery-knob/jquery.knob.min.js');
        
        // 3. jQuery UI
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/jquery-ui/jquery-ui.min.js');
        
        // 4. AdminLTE JavaScript
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/adminlte/js/adminlte.min.js');
        
        // 5. Font Awesome JavaScript (if needed)
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/fontawesome/js/all.min.js');
        
        // 6. DataTables and related libraries
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

        // Library loading check and initialization
        $PAGE->requires->js_init_code('
        console.log("=== Dashboard Library Loading Check ===");
        
        $(document).ready(function() {
            console.log("Chart.js available:", typeof Chart !== "undefined");
            console.log("jQuery available:", typeof $ !== "undefined");
            console.log("jQuery Knob available:", typeof $.fn.knob !== "undefined");
            console.log("jQuery UI available:", typeof $.fn.sortable !== "undefined");
            console.log("DataTables available:", typeof $.fn.DataTable !== "undefined");
            console.log("AdminLTE available:", typeof AdminLTE !== "undefined");

            if (typeof Chart === "undefined") {
                console.error("Chart.js failed to load from: /local/cuadrodemando/thirdpartylibs/chart/chart.min.js");
            } else {
                // Set Chart.js defaults
                Chart.defaults.responsive = true;
                Chart.defaults.maintainAspectRatio = false;
                console.log("Chart.js defaults set successfully");
            }

            // Initialize sortable widgets when jQuery UI is available
            if (typeof $.fn.sortable !== "undefined") {
                $(".connectedSortable").sortable({
                    placeholder: "sort-highlight",
                    connectWith: ".connectedSortable",
                    handle: ".card-header, .nav-tabs",
                    forcePlaceholderSize: true,
                    zIndex: 999999
                });
                $(".connectedSortable .card-header").css("cursor", "move");
                console.log("Sortable widgets initialized");
            }

            // Initialize jQuery Knob when available
            if (typeof $.fn.knob !== "undefined") {
                $(".knob").knob();
                console.log("jQuery Knob initialized");
            }
            
            console.log("=== Dashboard initialization complete ===");
        });
        ');
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
            // Display 404 error or default content
            echo \html_writer::tag('h1', 'Page not found');
            echo \html_writer::tag('p', "The requested page '{$page}' could not be found.");
        }
    }
    
    /**
     * Get dashboard statistics
     * 
     * @return array Array of statistics
     */
    public static function get_statistics() {
        if (function_exists('local_cuadrodemando_get_stats')) {
            return local_cuadrodemando_get_stats();
        }
        return [];
    }

    /**
     * Handle language switching
     * 
     * @return void
     */
    public static function handle_language_switch() {
        global $SESSION;
        if (isset($_GET['lang'])) {
            $SESSION->lang = clean_param($_GET['lang'], PARAM_LANG);
        }
    }
}
