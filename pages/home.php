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

global $OUTPUT, $CFG, $DB;

// Include necessary classes
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/getdata.php');
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/monthly_numbers_json.php');
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/total_hourly_views_json.php');

echo html_writer::start_div('dashboard-wrapper');

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
    array('class' => 'nav-link active')
);
echo html_writer::end_div();

echo html_writer::start_div('nav-item');
echo html_writer::link(
    new moodle_url('/local/cuadrodemando/courses.php'),
    get_string('courses', 'local_cuadrodemando'),
    array('class' => 'nav-link')
);
echo html_writer::end_div();

echo html_writer::start_div('nav-item');
echo html_writer::link(
    new moodle_url('/local/cuadrodemando/users.php'),
    get_string('users', 'local_cuadrodemando'),
    array('class' => 'nav-link')
);
echo html_writer::end_div();

echo html_writer::start_div('nav-item');
echo html_writer::link(
    new moodle_url('/local/cuadrodemando/geo.php'),
    get_string('geo', 'local_cuadrodemando'),
    array('class' => 'nav-link')
);
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::end_div();
echo html_writer::end_tag('nav');
echo html_writer::end_div();

// Dashboard header
echo html_writer::start_div('dashboard-header mb-4 d-flex justify-content-between align-items-center');
echo html_writer::start_div('dashboard-title');
echo html_writer::tag('h2', get_string('welcometodashboard', 'local_cuadrodemando'), array('class' => 'h3'));
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
if (!$current_lang) {
    $current_lang = 'es'; // Default to Spanish
}
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
echo html_writer::tag('h1', get_string('dashboard', 'local_cuadrodemando'));
echo html_writer::end_div();
echo html_writer::start_div('col-sm-6');
echo html_writer::start_tag('ol', array('class' => 'breadcrumb float-sm-right'));
echo html_writer::start_tag('li', array('class' => 'breadcrumb-item'));
echo html_writer::link($CFG->wwwroot . '/local/cuadrodemando/', get_string('home', 'local_cuadrodemando'));
echo html_writer::end_tag('li');
echo html_writer::end_tag('ol');
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_tag('section');

// Main content
echo html_writer::start_tag('section', array('class' => 'content'));
echo html_writer::start_div('container-fluid');

// Small boxes (Stat box) - First row
echo html_writer::start_div('row');

// Visible Courses
echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-info');
echo html_writer::start_div('inner');

$sql = "SELECT COUNT(*) FROM {course} WHERE visible = 1 and id > 1";
$courseCount = $DB->count_records_sql($sql, null);

echo html_writer::tag('h3', $courseCount);
echo html_writer::tag('p', '');
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', array('class' => 'fas fa-book-open'));
echo html_writer::end_div(); // icon
echo html_writer::tag('p', 'Cursos visibles', array('class' => 'small-box-footer'));
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col

// Active Enrollments
$sql_mysql = "SELECT COUNT(*) 
FROM {user_enrolments} 
WHERE status = 0 
AND DATE_FORMAT(FROM_UNIXTIME(timestart), '%Y') = '" . date('Y') . "'";

$sql_oracle = "SELECT COUNT(*)
FROM {user_enrolments}
WHERE status = 0
AND EXTRACT(YEAR FROM TO_DATE('1970-01-01', 'YYYY-MM-DD') + INTERVAL (timestart) SECOND) = " . date('Y');

$sql_postgres = "SELECT COUNT(*)
FROM {user_enrolments}
WHERE status = 0
AND EXTRACT(YEAR FROM to_timestamp(timestart)) = " . date('Y');

// Determine database type and use appropriate SQL
$dbtype = $DB->get_dbfamily();
if ($dbtype === 'mysql') {
    $enrollment_count = $DB->count_records_sql($sql_mysql, null);
} elseif ($dbtype === 'oracle') {
    $enrollment_count = $DB->count_records_sql($sql_oracle, null);
} else { // PostgreSQL or others
    $enrollment_count = $DB->count_records_sql($sql_postgres, null);
}

echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-success');
echo html_writer::start_div('inner');
echo html_writer::tag('h3', $enrollment_count);
echo html_writer::tag('p', '');
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', array('class' => 'fas fa-user-graduate'));
echo html_writer::end_div(); // icon
echo html_writer::tag('p', 'Matriculaciones activas (' . date('Y') . ')', array('class' => 'small-box-footer'));
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col

// Registered Users
$sql = "SELECT COUNT(*) FROM {user} WHERE id > 1 AND deleted = 0";
$userCount = $DB->count_records_sql($sql, null);

echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-warning');
echo html_writer::start_div('inner');
echo html_writer::tag('h3', $userCount);
echo html_writer::tag('p', '');
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', array('class' => 'fas fa-users'));
echo html_writer::end_div(); // icon
echo html_writer::tag('p', 'Usuarios registrados', array('class' => 'small-box-footer'));
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col

// Unique Accesses
echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-primary');
echo html_writer::start_div('inner');

if (isset($_GET['month'])) {
    $completion_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['totalaccess'];
} else {
    $total_access = Monthly_numbers_json::get_total_access();
    $completion_info = end($total_access);
}

echo html_writer::tag('h3', $completion_info);
echo html_writer::tag('p', ' ');
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', array('class' => 'fas fa-fingerprint'));
echo html_writer::end_div(); // icon
echo html_writer::tag('p', 'Accesos únicos (' . date('Y') . ') <br />', array('class' => 'small-box-footer'));
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col

echo html_writer::end_div(); // row

// Calendar section (if exists in getdata class)
if (class_exists('adminlte_getdata') && method_exists('adminlte_getdata', 'get_month_section')) {
    $getdata = new adminlte_getdata();
    echo $getdata->get_month_section();
}

// Dashboard content from getdata class
$getdata = new adminlte_getdata();
echo $getdata->get_dashboard_content();

// Chart sections
echo html_writer::start_div('row');

// Monthly numbers chart
echo html_writer::start_div('col-md-6');
echo html_writer::start_div('card');
echo html_writer::start_div('card-header');
echo html_writer::tag('h3', get_string('monthlystatistics', 'local_cuadrodemando'), array('class' => 'card-title'));
echo html_writer::end_div(); // card-header
echo html_writer::start_div('card-body');
echo html_writer::tag('canvas', '', array('id' => 'monthlyChart', 'style' => 'height: 400px;'));
echo html_writer::end_div(); // card-body
echo html_writer::end_div(); // card
echo html_writer::end_div(); // col

// Hourly views chart
echo html_writer::start_div('col-md-6');
echo html_writer::start_div('card');
echo html_writer::start_div('card-header');
echo html_writer::tag('h3', get_string('hourlyviews', 'local_cuadrodemando'), array('class' => 'card-title'));
echo html_writer::end_div(); // card-header
echo html_writer::start_div('card-body');
echo html_writer::tag('canvas', '', array('id' => 'hourlyChart', 'style' => 'height: 400px;'));
echo html_writer::end_div(); // card-body
echo html_writer::end_div(); // card
echo html_writer::end_div(); // col

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
    // Initialize charts if Chart.js is available
    if (typeof Chart !== 'undefined') {
        // Monthly chart
        if ($('#monthlyChart').length) {
            var monthlyCtx = $('#monthlyChart').get(0).getContext('2d');
            // Chart initialization would go here
        }

        // Hourly chart
        if ($('#hourlyChart').length) {
            var hourlyCtx = $('#hourlyChart').get(0).getContext('2d');
            // Chart initialization would go here
        }
    }
});

// Language selector functionality
function changeDashboardLanguage(lang) {
    var url = new URL(window.location);
    url.searchParams.set('lang', lang);
    window.location.href = url.href;
}
</script>
