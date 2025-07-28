<?php
/**
 * Dashboard main page
 *
 * @package    local_dashboard
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

defined('MOODLE_INTERNAL') || die();

// Require login
require_login();

// Check capabilities
$context = context_system::instance();
require_capability('local/dashboard:view', $context);

// Set up the page
$PAGE->set_context($context);
$PAGE->set_url('/local/dashboard/index.php');
$PAGE->set_title(get_string('dashboard', 'local_dashboard'));
$PAGE->set_heading(get_string('dashboard', 'local_dashboard'));
$PAGE->set_pagelayout('admin');

// Load the dashboard controller
require_once($CFG->dirroot . '/local/dashboard/classes/dashboard_controller.php');

// Display the dashboard
\local_dashboard\dashboard_controller::display_dashboard();
