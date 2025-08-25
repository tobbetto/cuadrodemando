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
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/fontawesome/css/all.min.css');
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/adminlte/css/adminlte.min.css');
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/jquery-ui/themes/ui-lightness/jquery-ui.css');
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/datatables/css/dataTables.bootstrap4.min.css');
        $PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/datatables-buttons/css/buttons.bootstrap4.min.css');
        $PAGE->requires->css('/local/cuadrodemando/assets/css/dashboard.css');
        
        // Use Moodle's AMD system for loading JS modules safely
        // This prevents conflicts with RequireJS
        $PAGE->requires->js_init_code('
        // Disable AMD temporarily to avoid conflicts
        var amdBackup = window.define;
        window.define = undefined;
        
        // Load libraries after DOM is ready
        $(document).ready(function() {
            console.log("=== Dashboard Library Loading (AMD Safe) ===");
            
            // Load Chart.js safely
            if (typeof Chart === "undefined") {
                $.getScript("/local/cuadrodemando/thirdpartylibs/chart/chart.min.js")
                .done(function() {
                    console.log("Chart.js loaded successfully");
                    Chart.defaults.responsive = true;
                    Chart.defaults.maintainAspectRatio = false;
                    Chart.defaults.devicePixelRatio = 1;
                    
                    // Trigger custom event when Chart.js is ready
                    $(document).trigger("chartjs-loaded");
                })
                .fail(function() {
                    console.error("Failed to load Chart.js");
                });
            } else {
                console.log("Chart.js already available");
                $(document).trigger("chartjs-loaded");
            }
            
            // Load jQuery Knob safely
            if (typeof $.fn.knob === "undefined") {
                $.getScript("/local/cuadrodemando/thirdpartylibs/jquery-knob/jquery.knob.min.js")
                .done(function() {
                    console.log("jQuery Knob loaded successfully");
                    $(".knob").knob();
                    $(document).trigger("knob-loaded");
                })
                .fail(function() {
                    console.error("Failed to load jQuery Knob");
                });
            } else {
                console.log("jQuery Knob already available");
                $(".knob").knob();
                $(document).trigger("knob-loaded");
            }
            
            // Load jQuery UI safely
            if (typeof $.fn.sortable === "undefined") {
                $.getScript("/local/cuadrodemando/thirdpartylibs/jquery-ui/jquery-ui.min.js")
                .done(function() {
                    console.log("jQuery UI loaded successfully");
                    initializeSortable();
                    $(document).trigger("jqueryui-loaded");
                })
                .fail(function() {
                    console.error("Failed to load jQuery UI");
                });
            } else {
                console.log("jQuery UI already available");
                initializeSortable();
                $(document).trigger("jqueryui-loaded");
            }
            
            // Load DataTables safely
            if (typeof $.fn.DataTable === "undefined") {
                // Load DataTables core first
                $.getScript("/local/cuadrodemando/thirdpartylibs/datatables/js/jquery.dataTables.min.js")
                .then(function() {
                    return $.getScript("/local/cuadrodemando/thirdpartylibs/datatables/js/dataTables.bootstrap4.min.js");
                })
                .then(function() {
                    return $.getScript("/local/cuadrodemando/thirdpartylibs/datatables-buttons/js/dataTables.buttons.min.js");
                })
                .then(function() {
                    return $.getScript("/local/cuadrodemando/thirdpartylibs/jszip/jszip.min.js");
                })
                .then(function() {
                    return $.getScript("/local/cuadrodemando/thirdpartylibs/pdfmake/pdfmake.min.js");
                })
                .then(function() {
                    return $.getScript("/local/cuadrodemando/thirdpartylibs/datatables-buttons/js/buttons.html5.min.js");
                })
                .done(function() {
                    console.log("DataTables loaded successfully");
                    $(document).trigger("datatables-loaded");
                })
                .fail(function() {
                    console.error("Failed to load DataTables");
                });
            } else {
                console.log("DataTables already available");
                $(document).trigger("datatables-loaded");
            }
            
            // Restore AMD
            window.define = amdBackup;
        });
        
        // Helper function to initialize sortable
        function initializeSortable() {
            $(".connectedSortable").sortable({
                placeholder: "sort-highlight",
                connectWith: ".connectedSortable",
                handle: ".card-header, .nav-tabs",
                forcePlaceholderSize: true,
                zIndex: 999999
            });
            $(".connectedSortable .card-header").css("cursor", "move");
        }
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
