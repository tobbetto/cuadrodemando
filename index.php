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

// Display the dashboard - controller handles everything
\local_cuadrodemando\dashboard_controller::display_dashboard();
