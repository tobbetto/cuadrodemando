<?php
/**
 * Dashboard home page content
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $OUTPUT, $CFG;

// Get dashboard statistics
$stats = \local_cuadrodemando\dashboard_controller::get_statistics();

echo html_writer::start_div('dashboard-wrapper');

// Dashboard header
echo html_writer::start_div('dashboard-header mb-4');
echo html_writer::tag('h2', get_string('welcometodashboard', 'local_cuadrodemando'), array('class' => 'h3'));
echo html_writer::end_div();

// Statistics cards
echo html_writer::start_div('row');

// Total Users Card
echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-info');
echo html_writer::start_div('inner');
echo html_writer::tag('h3', $stats['total_users'] ?? 0);
echo html_writer::tag('p', get_string('totalusers', 'local_cuadrodemando'));
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', array('class' => 'ion ion-bag'));
echo html_writer::end_div(); // icon
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col

// Total Courses Card
echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-success');
echo html_writer::start_div('inner');
echo html_writer::tag('h3', $stats['total_courses'] ?? 0);
echo html_writer::tag('p', get_string('totalcourses', 'local_cuadrodemando'));
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', array('class' => 'ion ion-stats-bars'));
echo html_writer::end_div(); // icon
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col

// Total Enrollments Card
echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-warning');
echo html_writer::start_div('inner');
echo html_writer::tag('h3', $stats['total_enrollments'] ?? 0);
echo html_writer::tag('p', get_string('totalenrollments', 'local_cuadrodemando'));
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', array('class' => 'ion ion-person-add'));
echo html_writer::end_div(); // icon
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col

// Active Users Card (placeholder)
echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-danger');
echo html_writer::start_div('inner');
echo html_writer::tag('h3', '0'); // Placeholder
echo html_writer::tag('p', get_string('activeusers', 'local_cuadrodemando'));
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', array('class' => 'ion ion-pie-graph'));
echo html_writer::end_div(); // icon
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col

echo html_writer::end_div(); // row

// Charts section (placeholder for future implementation)
echo html_writer::start_div('row mt-4');
echo html_writer::start_div('col-md-6');
echo html_writer::start_div('card');
echo html_writer::start_div('card-header');
echo html_writer::tag('h3', 'User Activity Chart', array('class' => 'card-title'));
echo html_writer::end_div(); // card-header
echo html_writer::start_div('card-body');
echo html_writer::tag('canvas', '', array('id' => 'userActivityChart', 'style' => 'height: 400px;'));
echo html_writer::end_div(); // card-body
echo html_writer::end_div(); // card
echo html_writer::end_div(); // col

echo html_writer::start_div('col-md-6');
echo html_writer::start_div('card');
echo html_writer::start_div('card-header');
echo html_writer::tag('h3', 'Course Enrollment Chart', array('class' => 'card-title'));
echo html_writer::end_div(); // card-header
echo html_writer::start_div('card-body');
echo html_writer::tag('canvas', '', array('id' => 'courseChart', 'style' => 'height: 400px;'));
echo html_writer::end_div(); // card-body
echo html_writer::end_div(); // card
echo html_writer::end_div(); // col
echo html_writer::end_div(); // row

echo html_writer::end_div(); // dashboard-wrapper

// Add JavaScript for charts (placeholder)
echo html_writer::script('
// Placeholder for chart initialization
console.log("Dashboard loaded");
');
