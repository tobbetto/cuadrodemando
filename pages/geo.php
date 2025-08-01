<?php
/**
 * Geography page for the cuadrodemando plugin
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.1.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
require_once($CFG->dirroot . '/local/cuadrodemando/lib.php');

// Check if user is logged in
require_login();

// Check permissions
$context = context_system::instance();
require_capability('local/cuadrodemando:view', $context);

// Set page context
$PAGE->set_context($context);
$PAGE->set_url('/local/cuadrodemando/pages/geo.php');
$PAGE->set_title(get_string('geo', 'local_cuadrodemando'));
$PAGE->set_heading(get_string('geo', 'local_cuadrodemando'));
$PAGE->set_pagelayout('admin');

// Add CSS and JS for the map functionality
$PAGE->requires->css('/local/cuadrodemando/styles.css');
$PAGE->requires->js('/local/cuadrodemando/amd/src/map.js');

// Include data classes
require_once($CFG->dirroot . '/local/cuadrodemando/views/pages/geo/data/user_provincia_table.php');
require_once($CFG->dirroot . '/local/cuadrodemando/views/pages/geo/data/province_activity_table.php');

// Navigation breadcrumbs
$PAGE->navbar->add(get_string('home', 'local_cuadrodemando'), new moodle_url('/local/cuadrodemando/index.php'));
$PAGE->navbar->add(get_string('geo', 'local_cuadrodemando'));

echo $OUTPUT->header();

// Navigation menu
echo html_writer::start_div('dashboard-nav mb-4');
echo html_writer::start_tag('nav', array('class' => 'navbar navbar-expand-lg navbar-light bg-light'));
echo html_writer::start_div('container-fluid');

// Brand/Home link
echo html_writer::link(
    new moodle_url('/local/cuadrodemando/index.php'),
    get_string('dashboard', 'local_cuadrodemando'),
    array('class' => 'navbar-brand')
);

// Navigation links
echo html_writer::start_div('navbar-nav');
echo html_writer::start_div('nav-item');
echo html_writer::link(
    new moodle_url('/local/cuadrodemando/pages/home.php'),
    get_string('home', 'local_cuadrodemando'),
    array('class' => 'nav-link')
);
echo html_writer::end_div();

echo html_writer::start_div('nav-item');
echo html_writer::link(
    new moodle_url('/local/cuadrodemando/pages/courses.php'),
    get_string('courses', 'local_cuadrodemando'),
    array('class' => 'nav-link')
);
echo html_writer::end_div();

echo html_writer::start_div('nav-item');
echo html_writer::link(
    new moodle_url('/local/cuadrodemando/pages/users.php'),
    get_string('users', 'local_cuadrodemando'),
    array('class' => 'nav-link')
);
echo html_writer::end_div();

echo html_writer::start_div('nav-item');
echo html_writer::link(
    new moodle_url('/local/cuadrodemando/pages/geo.php'),
    get_string('geo', 'local_cuadrodemando'),
    array('class' => 'nav-link active')
);
echo html_writer::end_div();

echo html_writer::end_div(); // navbar-nav
echo html_writer::end_div(); // container-fluid
echo html_writer::end_tag('nav');
echo html_writer::end_div(); // dashboard-nav

// Get geographical data
$geoData = User_provincia_table::getprovinciainfo();
$provinceData = Activity_province_table::getprovinceactivity();

echo html_writer::start_div('content-wrapper');
echo html_writer::start_tag('section', array('class' => 'content-header'));
echo html_writer::start_div('container-fluid');
echo html_writer::start_div('row mb-2');

// Page header
echo html_writer::start_div('col-sm-6');
echo html_writer::tag('h1', get_string('geo', 'local_cuadrodemando'));
echo html_writer::end_div();

// Breadcrumb
echo html_writer::start_div('col-sm-6');
echo html_writer::start_tag('ol', array('class' => 'breadcrumb float-sm-right'));
echo html_writer::start_tag('li', array('class' => 'breadcrumb-item'));
echo html_writer::link(new moodle_url('/local/cuadrodemando/index.php'), get_string('home', 'local_cuadrodemando'));
echo html_writer::end_tag('li');
echo html_writer::tag('li', get_string('geo', 'local_cuadrodemando'), array('class' => 'breadcrumb-item active'));
echo html_writer::end_tag('ol');
echo html_writer::end_div();

echo html_writer::end_div(); // row mb-2
echo html_writer::end_div(); // container-fluid
echo html_writer::end_tag('section'); // content-header

// Main content
echo html_writer::start_tag('section', array('class' => 'content'));
echo html_writer::start_div('container-fluid');

// Instructions
echo html_writer::start_div('row mb-2');
echo html_writer::start_div('col-sm-12');
echo html_writer::start_div('instruccion');
echo html_writer::start_div('body-instruccion');
echo get_string('geo_instructions', 'local_cuadrodemando');
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();

// Map and data section
echo html_writer::start_div('row');

// Map container
echo html_writer::start_div('col-10');
echo html_writer::div('', '', array('id' => 'mapa-cont'));
echo html_writer::end_div();

// Map statistics knobs
echo html_writer::start_div('col-2');
try {
    // Get map knobs data if the method exists
    if (class_exists('adminlte_getdata') && method_exists('adminlte_getdata', 'get_map_knobs')) {
        $calendar_info = adminlte_getdata::get_map_knobs();
        echo $calendar_info;
    } else {
        // Fallback display for map statistics
        echo html_writer::start_div('info-box shadow');
        echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-map')), array('class' => 'info-box-icon bg-info'));
        echo html_writer::start_div('info-box-content');
        echo html_writer::tag('span', get_string('provinces_total', 'local_cuadrodemando'), array('class' => 'info-box-text'));
        echo html_writer::tag('span', count($geoData), array('class' => 'info-box-number'));
        echo html_writer::end_div();
        echo html_writer::end_div();
    }
} catch (Exception $e) {
    // Fallback in case of error
    echo html_writer::div(get_string('geo_data_loading', 'local_cuadrodemando'), 'alert alert-info');
}
echo html_writer::end_div();

echo html_writer::end_div(); // row

// Modal for province details
echo html_writer::start_div('modal fade', array('id' => 'modalDatosProvincia', 'tabindex' => '-1', 'role' => 'dialog'));
echo html_writer::start_div('modal-dialog modal-xl');
echo html_writer::start_div('modal-content');

// Modal header
echo html_writer::start_div('modal-header');
echo html_writer::tag('h4', '', array('class' => 'modal-title'));
echo html_writer::tag('h4', ': ' . get_string('province_last_30_days', 'local_cuadrodemando'), array('class' => 'modal-subtitle', 'style' => 'line-height: 1.5;'));
echo html_writer::start_tag('button', array('type' => 'button', 'class' => 'close', 'data-bs-dismiss' => 'modal', 'aria-label' => 'Close'));
echo html_writer::tag('span', '&times;', array('aria-hidden' => 'true'));
echo html_writer::end_tag('button');
echo html_writer::end_div();

// Modal body with statistics
echo html_writer::start_div('modal-body');
echo html_writer::start_div('row');

// First column of stats
echo html_writer::start_div('col-md-4 col-sm-2 col-2 connectedSortable');

// Active sessions info box
echo html_writer::start_div('info-box shadow connectedSortable');
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-right-to-bracket')), array('class' => 'info-box-icon bg-success'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('sessions_last_hour', 'local_cuadrodemando'), array('class' => 'info-box-text'));
echo html_writer::tag('span', html_writer::tag('p', '', array('class' => 'sessions dato', 'style' => 'font-size: 1rem')), array('class' => 'info-box-number', 'id' => 'datos-provincia'));
echo html_writer::end_div();
echo html_writer::end_div();

// Active users info box
echo html_writer::start_div('info-box shadow connectedSortable');
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-user-clock')), array('class' => 'info-box-icon bg-success'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('active_users_last_hour', 'local_cuadrodemando'), array('class' => 'info-box-text'));
echo html_writer::tag('span', html_writer::tag('p', '', array('class' => 'views dato', 'style' => 'font-size: 1rem')), array('class' => 'info-box-number', 'id' => 'datos-provincia'));
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::end_div(); // col-md-4

// Second column of stats
echo html_writer::start_div('col-md-4 col-sm-6 col-6 connectedSortable');

// Completions info box
echo html_writer::start_div('info-box shadow connectedSortable');
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-award')), array('class' => 'info-box-icon bg-success'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('completions_last_month', 'local_cuadrodemando'), array('class' => 'info-box-text'));
echo html_writer::tag('span', html_writer::tag('p', '', array('class' => 'graduates dato', 'style' => 'font-size: 1rem')), array('class' => 'info-box-number', 'id' => 'datos-provincia'));
echo html_writer::end_div();
echo html_writer::end_div();

// Enrollments info box
echo html_writer::start_div('info-box shadow connectedSortable');
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-user-graduate')), array('class' => 'info-box-icon bg-success'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('enrollments_last_month', 'local_cuadrodemando'), array('class' => 'info-box-text'));
echo html_writer::tag('span', html_writer::tag('p', '', array('class' => 'enrolments dato', 'style' => 'font-size: 1rem')), array('class' => 'info-box-number', 'id' => 'datos-provincia'));
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::end_div(); // col-md-4

// Third column of stats
echo html_writer::start_div('col-md-4 col-sm-6 col-6 connectedSortable');

// New registrations info box
echo html_writer::start_div('info-box shadow connectedSortable');
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-user-plus')), array('class' => 'info-box-icon bg-success'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('registrations_last_month', 'local_cuadrodemando'), array('class' => 'info-box-text'));
echo html_writer::tag('span', html_writer::tag('p', '', array('class' => 'registrations dato', 'style' => 'font-size: 1rem')), array('class' => 'info-box-number', 'id' => 'datos-provincia'));
echo html_writer::end_div();
echo html_writer::end_div();

// Deletions info box
echo html_writer::start_div('info-box shadow connectedSortable');
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-user-minus')), array('class' => 'info-box-icon bg-danger'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('deletions_last_month', 'local_cuadrodemando'), array('class' => 'info-box-text'));
echo html_writer::tag('span', html_writer::tag('p', '', array('class' => 'deletes dato', 'style' => 'font-size: 1rem')), array('class' => 'info-box-number', 'id' => 'datos-provincia'));
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::end_div(); // col-md-4

echo html_writer::end_div(); // row
echo html_writer::end_div(); // modal-body

echo html_writer::end_div(); // modal-content
echo html_writer::end_div(); // modal-dialog
echo html_writer::end_div(); // modal

// Hidden tooltip for province
echo html_writer::start_div('hidden', array('id' => 'tooltip-provincia'));
echo html_writer::start_div('', array('style' => 'font-size: x-large'));
echo html_writer::tag('span', '', array('class' => 'badge badge-secondary text-justify font-weight-normal p-3', 'style' => 'line-height: 120%'));
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::end_div(); // container-fluid
echo html_writer::end_tag('section'); // content
echo html_writer::end_div(); // content-wrapper

// JavaScript for map functionality
echo html_writer::start_tag('script');
echo "
$(document).ready(function(){
    var geoData = " . json_encode($geoData) . ";
    var provinceData = " . json_encode($provinceData) . ";
    
    // Load map if function exists
    if (typeof $.fn.cargarMapa === 'function') {
        $('#mapa-cont').cargarMapa(geoData, provinceData);
    } else {
        // Fallback: display data in table format
        console.log('Map function not available, displaying data:', geoData, provinceData);
        $('#mapa-cont').html('<div class=\"alert alert-info\">" . get_string('map_loading', 'local_cuadrodemando') . "</div>');
    }
    
    // Initialize tooltips and popovers
    $(function () {
        $('[data-toggle=\"popover\"]').popover();
        $('[data-toggle=\"tooltip\"]').tooltip();
    });
});

// jQuery Knob configuration
$(function () {
    $('.knob').knob({
        draw: function () {
            if (this.$.data('skin') == 'tron') {
                var a   = this.angle(this.cv),
                    sa  = this.startAngle,
                    sat = this.startAngle,
                    ea,
                    eat = sat + a,
                    r   = true;

                this.g.lineWidth = this.lineWidth;

                this.o.cursor
                && (sat = eat - 0.3)
                && (eat = eat + 0.3);

                if (this.o.displayPrevious) {
                    ea = this.startAngle + this.angle(this.value);
                    this.o.cursor
                    && (sa = ea - 0.3)
                    && (ea = ea + 0.3);
                    this.g.beginPath();
                    this.g.strokeStyle = this.previousColor;
                    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
                    this.g.stroke();
                }

                this.g.beginPath();
                this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                this.g.stroke();

                this.g.lineWidth = 2;
                this.g.beginPath();
                this.g.strokeStyle = this.o.fgColor;
                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                this.g.stroke();

                return false;
            }
        }
    });
});
";
echo html_writer::end_tag('script');

echo $OUTPUT->footer();
