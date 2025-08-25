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

        // CSS
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/fontawesome/css/all.min.css');
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/adminlte/css/adminlte.min.css');
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/jquery-ui/themes/ui-lightness/jquery-ui.css');
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/datatables/css/dataTables.bootstrap4.min.css');
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/datatables-buttons/css/buttons.bootstrap4.min.css');
        $PAGE->requires->css('/local/cuadrodemando/assets/css/dashboard.css');

        // JS (order matters!)
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/jquery/jquery-3.4.1.min.js');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/jquery-ui/jquery-ui.min.js');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/jquery-knob/jquery.knob.min.js');
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/chart/chart.min.js');
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
        $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/adminlte/js/adminlte.min.js');

        // Initialization script (no AMD hack needed)
        $PAGE->requires->js_init_code('
            $(function() {
                // Now all libraries are loaded as globals
                // You can safely use Chart, $.fn.knob, $.fn.sortable, $.fn.DataTable, etc.
                console.log("Chart.js available:", typeof Chart !== "undefined");
                console.log("jQuery UI available:", typeof $.fn.sortable !== "undefined");
                console.log("jQuery Knob available:", typeof $.fn.knob !== "undefined");
                console.log("DataTables available:", typeof $.fn.DataTable !== "undefined");
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
            echo \html_writer::tag('h1', 'Page not found');
            echo \html_writer::tag('p', "The requested page '{$page}' could not be found.");
        }
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
