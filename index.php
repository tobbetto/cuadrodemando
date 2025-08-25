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
require_once($CFG->libdir . '/moodlelib.php');

// Check for 'page' parameter
$page = isset($_GET['page']) ? clean_param($_GET['page'], PARAM_ALPHANUMEXT) : 'home';

if ($page === 'home') {
	\local_cuadrodemando\dashboard_controller::display_dashboard();
} else {
	\local_cuadrodemando\dashboard_controller::display_page($page);
}
