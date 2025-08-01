<?php
/**
 * Courses page entry point
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/local/cuadrodemando/classes/dashboard_controller.php');

// Require login
require_login();

// Check capability
$context = context_system::instance();
require_capability('local/cuadrodemando:view', $context);

// Handle language switching if requested
\local_cuadrodemando\dashboard_controller::handle_language_switch();

// Display the courses page
\local_cuadrodemando\dashboard_controller::display_page('courses');
