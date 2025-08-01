<?php
/**
 * Dashboard courses page content
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $OUTPUT, $CFG, $DB;

// Include necessary classes
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/getdata.php');

echo html_writer::start_div('dashboard-wrapper');

// Dashboard header with language selector
echo html_writer::start_div('dashboard-header mb-4 d-flex justify-content-between align-items-center');
echo html_writer::start_div('dashboard-title');

// Check if viewing specific course
if (isset($_GET['courseid'])) {
    $course_info = $DB->get_record('course', ['id' => $_GET['courseid']]);
    echo html_writer::tag('h2', 'Detalles del curso: ' . html_writer::tag('b', $course_info->fullname) . ' (' . $course_info->shortname . ')', array('class' => 'h3'));
} else {
    echo html_writer::tag('h2', 'Vista general de los cursos de ' . date('Y'), array('class' => 'h3'));
}

echo html_writer::end_div();

// Language selector
echo html_writer::start_div('language-selector');
echo html_writer::tag('label', get_string('language_selector', 'local_cuadrodemando'), array('for' => 'language-select', 'class' => 'form-label me-2'));

$languages = array(
    'en' => get_string('lang_english', 'local_cuadrodemando'),
    'es' => get_string('lang_spanish', 'local_cuadrodemando'),
    'is' => get_string('lang_icelandic', 'local_cuadrodemando'),
    'ca' => get_string('lang_catalan', 'local_cuadrodemando')
);

$current_lang = current_language();
$select_options = '';
foreach ($languages as $lang_code => $lang_name) {
    $selected = ($lang_code === $current_lang) ? 'selected' : '';
    $select_options .= html_writer::tag('option', $lang_name, array('value' => $lang_code, 'selected' => $selected));
}

echo html_writer::tag('select', $select_options, array(
    'id' => 'language-select',
    'class' => 'form-select',
    'onchange' => 'changeDashboardLanguage(this.value)'
));
echo html_writer::end_div();
echo html_writer::end_div();

// Content Wrapper
echo html_writer::start_div('content-wrapper');

// Content Header
echo html_writer::start_tag('section', array('class' => 'content-header'));
echo html_writer::start_div('container-fluid');
echo html_writer::start_div('row mb-2');
echo html_writer::start_div('col-sm-6');

if (isset($_GET['courseid'])) {
    echo html_writer::tag('h1', 'Detalles del curso: ' . html_writer::tag('b', $course_info->fullname) . ' (' . $course_info->shortname . ')');
} else {
    echo html_writer::tag('h1', 'Vista general de los cursos de ' . date('Y'));
}

echo html_writer::end_div();
echo html_writer::start_div('col-sm-6');
echo html_writer::start_tag('ol', array('class' => 'breadcrumb float-sm-right'));
echo html_writer::start_tag('li', array('class' => 'breadcrumb-item'));
echo html_writer::link($CFG->wwwroot . '/local/cuadrodemando/', get_string('home', 'local_cuadrodemando'));
echo html_writer::end_tag('li');
echo html_writer::start_tag('li', array('class' => 'breadcrumb-item active'));
echo html_writer::link($CFG->wwwroot . '/local/cuadrodemando/courses', 'Cursos');
echo html_writer::end_tag('li');
echo html_writer::end_tag('ol');
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_tag('section');

// Main content
echo html_writer::start_tag('section', array('class' => 'content'));
echo html_writer::start_div('container-fluid');

// Calculate course statistics
// Courses created this year
$sql_mysql = "SELECT COUNT(*) 
              FROM {course} 
              WHERE YEAR(FROM_UNIXTIME(timemodified)) = " . date('Y') . " 
              AND category <> 261 and id > 1
              AND YEAR(FROM_UNIXTIME(startdate)) = " . date('Y');

$sql_oracle = "SELECT COUNT(*) 
               FROM {course} 
               WHERE to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(timemodified, 'SECOND'), 'YYYY') = '" . date('Y') . "'
               AND category <> 261 and id > 1
               AND to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(startdate, 'SECOND'), 'YYYY') = '" . date('Y') . "'";

$sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;
$countCreatedCourses = $DB->count_records_sql($sql, null);

// Active courses
$sql_mysql = "SELECT COUNT(*) FROM {course} WHERE FROM_UNIXTIME(enddate) > CURDATE()";
$sql_oracle = "SELECT COUNT(*) FROM {course} WHERE enddate > '" . time() . "' AND visible = 1 AND category <> 261 AND id > 1";
$sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;
$countOpenCourses = $DB->count_records_sql($sql, null);

// Finished courses
$sql_mysql = "SELECT COUNT(*) FROM {course} WHERE YEAR(FROM_UNIXTIME(enddate)) = '" . date('Y') . "'" . " AND FROM_UNIXTIME(enddate) < CURDATE()";
$sql_oracle = "SELECT COUNT(*) FROM {course} WHERE to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(enddate/60, 'MINUTE'), 'YYYY') >= '" . date('Y') . "'" . " AND enddate < '" . time() . "'";
$sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;
$countFinishedCourses = $DB->count_records_sql($sql, null);

// Average course enrollment
$sql = "SELECT round(avg(count))
        FROM
          (
            SELECT COUNT(*) as count
            FROM {course}            ic
            JOIN {context}           con ON con.instanceid = ic.id
            JOIN {role_assignments}  ra  ON ra.contextid = con.id AND con.contextlevel = 50
            JOIN {role}              r   ON ra.roleid = r.id
            JOIN {user}              u   ON u.id = ra.userid
            WHERE r.id  = 5
            GROUP BY ic.id
      ) counts";
$avgCourseEnrolment = $DB->count_records_sql($sql, null);

// Small boxes (Stat box) - Only show if not viewing specific course
if (!isset($_GET['courseid'])) {
    echo html_writer::start_div('row');

    // Created courses
    echo html_writer::start_div('col-lg-3 col-6');
    echo html_writer::start_div('small-box bg-info');
    echo html_writer::start_div('inner');
    echo html_writer::tag('h3', $countCreatedCourses);
    echo html_writer::tag('p', '');
    echo html_writer::end_div(); // inner
    echo html_writer::start_div('icon');
    echo html_writer::tag('i', '', array('class' => 'fas fa-calendar-plus'));
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', 'Cursos creados (' . date('Y') . ')', array('class' => 'small-box-footer'));
    echo html_writer::end_div(); // small-box
    echo html_writer::end_div(); // col

    // Active courses
    echo html_writer::start_div('col-lg-3 col-6');
    echo html_writer::start_div('small-box bg-success');
    echo html_writer::start_div('inner');
    echo html_writer::tag('h3', $countOpenCourses);
    echo html_writer::tag('p', '');
    echo html_writer::end_div(); // inner
    echo html_writer::start_div('icon');
    echo html_writer::tag('i', '', array('class' => 'fas fa-calendar-check'));
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', 'Cursos activos (' . date('Y') . ')', array('class' => 'small-box-footer'));
    echo html_writer::end_div(); // small-box
    echo html_writer::end_div(); // col

    // Finished courses
    echo html_writer::start_div('col-lg-3 col-6');
    echo html_writer::start_div('small-box bg-warning');
    echo html_writer::start_div('inner');
    echo html_writer::tag('h3', $countFinishedCourses);
    echo html_writer::tag('p', '');
    echo html_writer::end_div(); // inner
    echo html_writer::start_div('icon');
    echo html_writer::tag('i', '', array('class' => 'fas fa-calendar-xmark'));
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', 'Cursos finalizados (' . date('Y') . ')', array('class' => 'small-box-footer'));
    echo html_writer::end_div(); // small-box
    echo html_writer::end_div(); // col

    // Average enrollment
    echo html_writer::start_div('col-lg-3 col-6');
    echo html_writer::start_div('small-box bg-primary');
    echo html_writer::start_div('inner');
    echo html_writer::tag('h3', $avgCourseEnrolment);
    echo html_writer::tag('p', '');
    echo html_writer::end_div(); // inner
    echo html_writer::start_div('icon');
    echo html_writer::tag('i', '', array('class' => 'fas fa-users-line'));
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', 'Media matriculados', array('class' => 'small-box-footer'));
    echo html_writer::end_div(); // small-box
    echo html_writer::end_div(); // col

    echo html_writer::end_div(); // row
}

// Main content row
echo html_writer::start_div('row');

// Category numbers section
if (isset($_GET['courseid'])) {
    $category_numbers = new adminlte_getdata();
    echo $category_numbers->get_category_numbers($_GET['courseid']);
} else {
    $category_numbers = new adminlte_getdata();
    echo $category_numbers->get_category_numbers();
}

// Course enrollment section
if (isset($_GET['courseid'])) {
    $courseEnrolment = new adminlte_getdata();
    echo $courseEnrolment->get_course_numbers($_GET['courseid']);
} else {
    $courseEnrolment = new adminlte_getdata();
    echo $courseEnrolment->get_course_numbers();
}

echo html_writer::end_div(); // row

// Yearly courses data
echo html_writer::start_div('row');

if (isset($_GET['courseid'])) {
    $course_data = adminlte_getdata::get_yearly_courses($_GET['courseid']);
} else {
    $course_data = adminlte_getdata::get_yearly_courses();
}
echo $course_data;

echo html_writer::end_div(); // row

echo html_writer::end_div(); // container-fluid
echo html_writer::end_tag('section'); // content
echo html_writer::end_div(); // content-wrapper

echo html_writer::end_div(); // dashboard-wrapper

// JavaScript for interactivity
?>

<script>
// Make the dashboard widgets sortable Using jquery UI
if (typeof $ !== 'undefined' && $.fn.sortable) {
    $('.connectedSortable').sortable({
        placeholder: 'sort-highlight',
        connectWith: '.connectedSortable',
        handle: '.card-header, .nav-tabs',
        forcePlaceholderSize: true,
        zIndex: 999999
    });
    $('.connectedSortable .card-header').css('cursor', 'move');

    // jQuery UI sortable for the todo list
    $('.todo-list').sortable({
        placeholder: 'sort-highlight',
        handle: '.handle',
        forcePlaceholderSize: true,
        zIndex: 999999
    });
}

$(function () {
    /* jQueryKnob */
    if (typeof $.fn.knob !== 'undefined') {
        $('.knob').knob({
            draw: function () {
                // "tron" case
                if (this.$.data('skin') == 'tron') {
                    var a   = this.angle(this.cv),  // Angle
                        sa  = this.startAngle,      // Previous start angle
                        sat = this.startAngle,      // Start angle
                        ea,                         // Previous end angle
                        eat = sat + a,              // End angle
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
    }
    /* END JQUERY KNOB */

    // PIE CHART
    if (typeof Chart !== 'undefined' && $('#pieChart').length) {
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
        // Additional chart initialization can be added here
    }
});

// Language selector functionality
function changeDashboardLanguage(lang) {
    var url = new URL(window.location);
    url.searchParams.set('lang', lang);
    window.location.href = url.href;
}
</script>
