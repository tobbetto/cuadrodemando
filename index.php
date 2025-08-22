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

// Load CSS libraries
$PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/fontawesome/css/all.min.css');

// Load JavaScript libraries in correct order
// 1. Chart.js (for charts)
$PAGE->requires->js(new moodle_url('https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js'));

// 2. jQuery Knob (for circular progress indicators)
$PAGE->requires->js(new moodle_url('https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js'));

// 3. jQuery UI (for sortable widgets and interactions)
$PAGE->requires->js(new moodle_url('https://code.jquery.com/ui/1.13.2/jquery-ui.min.js'));
$PAGE->requires->css(new moodle_url('https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css'));

// 4. DataTables (for table functionality)
$PAGE->requires->js(new moodle_url('https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js'));
$PAGE->requires->js(new moodle_url('https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js'));
$PAGE->requires->js(new moodle_url('https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js'));
$PAGE->requires->js(new moodle_url('https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js'));
$PAGE->requires->js(new moodle_url('https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js'));
$PAGE->requires->js(new moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'));
$PAGE->requires->js(new moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'));
$PAGE->requires->js(new moodle_url('https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js'));
$PAGE->requires->js(new moodle_url('https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js'));
$PAGE->requires->js(new moodle_url('https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js'));

// DataTables CSS
$PAGE->requires->css(new moodle_url('https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap4.min.css'));
$PAGE->requires->css(new moodle_url('https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css'));

// Custom dashboard CSS
$PAGE->requires->css(new moodle_url('/local/cuadrodemando/assets/css/dashboard.css'));

// Add initialization script for libraries
$init_script = "
<script>
// Wait for all libraries to load
$(document).ready(function() {
    // Initialize Chart.js defaults
    if (typeof Chart !== 'undefined') {
        Chart.defaults.responsive = true;
        Chart.defaults.maintainAspectRatio = false;
    }
    
    // Initialize jQuery Knob defaults
    if (typeof $.fn.knob !== 'undefined') {
        $('.knob').knob();
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
    }
    
    console.log('Dashboard libraries initialized');
});

// Language selector functionality
function changeDashboardLanguage(lang) {
    var url = new URL(window.location);
    url.searchParams.set('lang', lang);
    window.location.href = url.href;
}
</script>
";

// Add the initialization script to the page
$PAGE->requires->js_init_code($init_script);

// Display the dashboard
\local_cuadrodemando\dashboard_controller::display_dashboard();
