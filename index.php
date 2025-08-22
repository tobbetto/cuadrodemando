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

// Load CSS and JavaScript libraries
$PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/fontawesome/css/all.min.css');

// Load Chart.js from your thirdpartylibs
$PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/chart/chart.min.js');

// Load jQuery UI for sortable widgets (if you want dashboard widgets to be sortable)
$PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/jquery/jquery-ui.min.js');
$PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/jquery/jquery-ui.css');

// Load any custom CSS for your dashboard
$PAGE->requires->css('/local/cuadrodemando/thirdpartylibs/adminlte/adminlte.min.css');

// Display the dashboard
\local_cuadrodemando\dashboard_controller::display_dashboard();
