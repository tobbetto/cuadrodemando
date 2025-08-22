<?php
/**
 * Dashboard plugin for Moodle
 *
 * @package    local_cuadrodemando  
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

defined('MOODLE_INTERNAL') || die();

// Require login
require_login();

// Load the dashboard controller
require_once($CFG->dirroot . '/local/cuadrodemando/classes/dashboard_controller.php');

// Handle language switching
\local_cuadrodemando\dashboard_controller::handle_language_switch();

// Check capabilities
$context = context_system::instance();
require_capability('local/cuadrodemando:view', $context);

// Set up the page
$PAGE->set_context($context);
$PAGE->set_url('/local/cuadrodemando/index.php');
$PAGE->set_title(get_string('dashboard', 'local_cuadrodemando'));
$PAGE->set_heading(get_string('dashboard', 'local_cuadrodemando'));
$PAGE->set_pagelayout('admin');

// Load CSS libraries from local thirdpartylibs
$PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/fontawesome/css/all.min.css');

// AdminLTE CSS
$PAGE->requires->css(new moodle_url('/local/cuadrodemando/thirdpartylibs/adminlte/css/adminlte.min.css'));

// Load JavaScript libraries in correct order from local files
// 1. Chart.js (for charts)
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/chart/chart.min.js'));

// 2. jQuery Knob (for circular progress indicators)
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/jquery-knob/jquery.knob.min.js'));

// 3. jQuery UI (for sortable widgets and interactions)
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/jquery-ui/jquery-ui.min.js'));
$PAGE->requires->css(new moodle_url('/local/cuadrodemando/thirdpartylibs/jquery-ui/themes/ui-lightness/jquery-ui.css'));

// 4. DataTables (for table functionality)
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/datatables/js/jquery.dataTables.min.js'));
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/datatables/js/dataTables.bootstrap4.min.js'));
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/datatables-buttons/js/dataTables.buttons.min.js'));
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/datatables-buttons/js/buttons.bootstrap4.min.js'));
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/jszip/jszip.min.js'));
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/pdfmake/pdfmake.min.js'));
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/pdfmake/vfs_fonts.js'));
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/datatables-buttons/js/buttons.html5.min.js'));
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/datatables-buttons/js/buttons.print.min.js'));
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/datatables-buttons/js/buttons.colVis.min.js'));

// AdminLTE JavaScript
$PAGE->requires->js(new moodle_url('/local/cuadrodemando/thirdpartylibs/adminlte/js/adminlte.min.js'));

// DataTables CSS from local files
$PAGE->requires->css(new moodle_url('/local/cuadrodemando/thirdpartylibs/datatables/css/dataTables.bootstrap4.min.css'));
$PAGE->requires->css(new moodle_url('/local/cuadrodemando/thirdpartylibs/datatables-buttons/css/buttons.bootstrap4.min.css'));

// Custom dashboard CSS
$PAGE->requires->css(new moodle_url('/local/cuadrodemando/assets/css/dashboard.css'));

// Add initialization script for libraries
$init_script = "
// Wait for all libraries to load
$(document).ready(function() {
    // Initialize Chart.js defaults
    if (typeof Chart !== 'undefined') {
        Chart.defaults.responsive = true;
        Chart.defaults.maintainAspectRatio = false;
        console.log('Chart.js loaded successfully');
    } else {
        console.error('Chart.js not loaded');
    }
    
    // Initialize jQuery Knob defaults
    if (typeof $.fn.knob !== 'undefined') {
        $('.knob').knob();
        console.log('jQuery Knob loaded successfully');
    } else {
        console.error('jQuery Knob not loaded');
    }
    
    // Initialize sortable widgets
    if (typeof $.fn.sortable !== 'undefined') {
        $('.connectedSortable').sortable({
            placeholder: 'sort-highlight',
            connectWith: '.connectedSortable',
            handle: '.card-header, .nav-tabs',
            forcePlaceholderSize: true,
            zIndex: 999999
        });
        $('.connectedSortable .card-header').css('cursor', 'move');
        console.log('jQuery UI sortable loaded successfully');
    } else {
        console.error('jQuery UI not loaded');
    }
    
    // Initialize DataTables defaults
    if (typeof $.fn.DataTable !== 'undefined') {
        console.log('DataTables loaded successfully');
    } else {
        console.error('DataTables not loaded');
    }
    
    // Initialize AdminLTE
    if (typeof AdminLTE !== 'undefined') {
        console.log('AdminLTE loaded successfully');
    } else {
        console.error('AdminLTE not loaded');
    }
    
    console.log('Dashboard libraries initialization complete');
});

// Language selector functionality
function changeDashboardLanguage(lang) {
    var url = new URL(window.location);
    url.searchParams.set('lang', lang);
    window.location.href = url.href;
}
";

// Add the initialization script to the page
$PAGE->requires->js_init_code($init_script);

// Display the dashboard
\local_cuadrodemando\dashboard_controller::display_dashboard();
