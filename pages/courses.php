<?php
/**
 * Dashboard courses page content - Updated for AMD compatibility
 */

defined('MOODLE_INTERNAL') || die();

global $OUTPUT, $CFG, $DB;

// Include necessary classes
require_once($CFG->dirroot . '/local/cuadrodemando/classes/navbar_helper.php');
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/getdata.php');

echo html_writer::start_div('dashboard-wrapper');
echo \local_cuadrodemando\navbar_helper::render_navbar('courses');
echo html_writer::start_div('content-wrapper');

// Content Header
echo html_writer::start_tag('section', array('class' => 'content-header'));
echo html_writer::start_div('container-fluid');
echo html_writer::start_div('row mb-2');
echo html_writer::start_div('col-sm-6');

if (isset($_GET['courseid'])) {
    $course_info = $DB->get_record('course', array('id' => $_GET['courseid']));
    echo html_writer::tag('h1', get_string('coursedetails', 'local_cuadrodemando', html_writer::tag('b', $course_info->fullname) . ' (' . $course_info->shortname . ')'));
} else {
    echo html_writer::tag('h1', get_string('coursesoverview', 'local_cuadrodemando', date('Y')));
}

echo html_writer::end_div();
echo html_writer::start_div('col-sm-6');
echo html_writer::start_tag('ol', array('class' => 'breadcrumb float-sm-right'));
echo html_writer::start_tag('li', array('class' => 'breadcrumb-item'));
echo html_writer::link($CFG->wwwroot . '/local/cuadrodemando/', get_string('home', 'local_cuadrodemando'));
echo html_writer::end_tag('li');
echo html_writer::start_tag('li', array('class' => 'breadcrumb-item active'));
echo html_writer::link($CFG->wwwroot . '/local/cuadrodemando/courses', get_string('courses', 'local_cuadrodemando'));
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
    echo html_writer::tag('p', get_string('coursescreated', 'local_cuadrodemando', date('Y')), array('class' => 'small-box-footer'));
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
    echo html_writer::tag('p', get_string('coursesactive', 'local_cuadrodemando', date('Y')), array('class' => 'small-box-footer'));
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
    echo html_writer::tag('p', get_string('coursesfinished', 'local_cuadrodemando', date('Y')), array('class' => 'small-box-footer'));
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
    echo html_writer::tag('p', get_string('averageenrollment', 'local_cuadrodemando'), array('class' => 'small-box-footer'));
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
    echo $category_numbers->get_category_numbers(null);
}

// Course enrollment section
if (isset($_GET['courseid'])) {
    $courseEnrolment = new adminlte_getdata();
    echo $courseEnrolment->get_course_numbers($_GET['courseid']);
} else {
    $courseEnrolment = new adminlte_getdata();
    echo $courseEnrolment->get_course_numbers(null);
}

echo html_writer::end_div(); // row
echo html_writer::end_div(); // container-fluid
echo html_writer::end_tag('section'); // content
echo html_writer::end_div(); // content-wrapper
echo html_writer::end_div(); // dashboard-wrapper
?>

<style>
/* Chart.js fixes for AMD compatibility */
.chart-container {
    position: relative;
    width: 100% !important;
    height: 400px !important;
}

.chart-container canvas {
    width: 100% !important;
    height: 100% !important;
}

canvas {
    display: block;
    box-sizing: border-box;
}

.fas, .fab, .far, .fal {
    display: inline-block;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    line-height: 1;
}
</style>

<script>
// AMD-safe initialization
$(document).ready(function() {
    console.log('Courses page initializing...');
    
    // Wait for Chart.js to be loaded
    $(document).on('chartjs-loaded', function() {
        console.log('Chart.js ready, initializing charts...');
        initializeCharts();
    });
    
    // Wait for jQuery UI to be loaded
    $(document).on('jqueryui-loaded', function() {
        console.log('jQuery UI ready, initializing sortables...');
        initializeSortables();
    });
    
    // Wait for jQuery Knob to be loaded
    $(document).on('knob-loaded', function() {
        console.log('jQuery Knob ready, initializing knobs...');
        initializeKnobs();
    });
    
    // Wait for DataTables to be loaded
    $(document).on('datatables-loaded', function() {
        console.log('DataTables ready, initializing tables...');
        initializeDataTables();
    });
    
    // Check if libraries are already loaded
    if (typeof Chart !== 'undefined') {
        $(document).trigger('chartjs-loaded');
    }
    if (typeof $.fn.sortable !== 'undefined') {
        $(document).trigger('jqueryui-loaded');
    }
    if (typeof $.fn.knob !== 'undefined') {
        $(document).trigger('knob-loaded');
    }
    if (typeof $.fn.DataTable !== 'undefined') {
        $(document).trigger('datatables-loaded');
    }
});

function initializeCharts() {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js not available for initialization');
        return;
    }
    
    // Set Chart.js defaults
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;
    Chart.defaults.devicePixelRatio = 1;
    
    // PIE CHART
    if ($('#pieChart').length) {
        try {
            var pieChartCanvas = $('#pieChart').get(0);
            if (pieChartCanvas && pieChartCanvas.getContext) {
                pieChartCanvas.width = pieChartCanvas.parentElement.clientWidth || 400;
                pieChartCanvas.height = 300;
                
                var ctx = pieChartCanvas.getContext('2d');
                var pieData = {
                    labels: <?php echo json_encode($courseEnrolment->pieChartLabel ?? []); ?>,
                    datasets: [{
                        data: <?php echo json_encode($courseEnrolment->pieChartData ?? []); ?>,
                        backgroundColor: <?php echo json_encode($courseEnrolment->background_color ?? ['#dc3545', '#28a745', '#ffc107', '#17a2b8']); ?>
                    }]
                };
                
                new Chart(ctx, {
                    type: 'doughnut',
                    data: pieData,
                    options: {
                        plugins: {
                            legend: { display: false }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
                console.log('Pie chart initialized successfully');
            }
        } catch (e) {
            console.error('Pie chart initialization error:', e);
        }
    }
    
    // STACKED BAR CHART
    if ($('#stackedBarChart-canvas').length) {
        try {
            var stackedCanvas = $('#stackedBarChart-canvas').get(0);
            if (stackedCanvas && stackedCanvas.getContext) {
                stackedCanvas.width = stackedCanvas.parentElement.clientWidth || 400;
                stackedCanvas.height = 300;
                
                var barChartData = {
                    labels: <?php 
                        if (!isset($_GET['courseid'])) { 
                            $get_course_categories = adminlte_getdata::get_category_name_number(); 
                            echo json_encode($get_course_categories['name'] ?? []); 
                        } else { 
                            $get_course_categories = adminlte_getdata::get_course_enrolments($_GET['courseid']); 
                            echo json_encode($get_course_categories['name'] ?? []); 
                        } ?>,
                    datasets: [
                        {
                            label: 'Finalizados',
                            backgroundColor: '#28a745',
                            borderColor: '#28a745',
                            data: <?php echo json_encode($get_course_categories['count'] ?? []); ?>
                        },
                        {
                            label: 'No finalizados',
                            backgroundColor: '#dc3545',
                            borderColor: '#dc3545',
                            data: <?php echo json_encode($get_course_categories['students'] ?? []); ?>
                        }
                    ]
                };

                var stackedBarChartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true },
                        y: { 
                            stacked: true,
                            beginAtZero: true
                        }
                    }
                };

                new Chart(stackedCanvas.getContext('2d'), {
                    type: 'bar',
                    data: barChartData,
                    options: stackedBarChartOptions
                });
                console.log('Stacked bar chart initialized successfully');
            }
        } catch (e) {
            console.error('Stacked bar chart initialization error:', e);
        }
    }

    // GEO CHART
    if ($('#geo-chart-canvas').length) {
        try {
            var geoCanvas = $('#geo-chart-canvas').get(0);
            if (geoCanvas && geoCanvas.getContext) {
                geoCanvas.width = geoCanvas.parentElement.clientWidth || 400;
                geoCanvas.height = 300;
                
                new Chart(geoCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: <?php 
                            if (!isset($_GET['courseid'])) { 
                                $get_course_categories = adminlte_getdata::get_category_name_number(); 
                                echo json_encode($get_course_categories['name'] ?? []); 
                            } else { 
                                $get_course_categories = adminlte_getdata::get_course_enrolments($_GET['courseid']); 
                                echo json_encode($get_course_categories['name'] ?? []); 
                            } ?>,
                        datasets: [{
                            label: <?php
                                if (!isset($_GET['courseid'])) { 
                                    echo "'# de cursos en categoría'"; 
                                } else {
                                    echo "'# de provincias de los alumnos'"; 
                                } ?>,
                            backgroundColor: '#28a745',
                            data: <?php echo json_encode($get_course_categories['count'] ?? []); ?>
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { beginAtZero: true },
                            x: { beginAtZero: true }
                        }
                    }
                });
                console.log('Geo chart initialized successfully');
            }
        } catch (e) {
            console.error('Geo chart initialization error:', e);
        }
    }

    // TIME CHART
    if ($('#time-chart-canvas').length) {
        try {
            var timeCanvas = document.getElementById('time-chart-canvas');
            if (timeCanvas && timeCanvas.getContext) {
                timeCanvas.width = timeCanvas.parentElement.clientWidth || 400;
                timeCanvas.height = 300;
                
                new Chart(timeCanvas.getContext('2d'), { 
                    type: 'line',
                    data: {
                        labels: <?php
                            if (!isset($_GET['courseid'])) { 
                                $get_site_times = adminlte_getdata::get_site_times(); 
                                echo json_encode($get_site_times['course'] ?? []); 
                            } else { 
                                $get_course_times = adminlte_getdata::get_course_times($_GET['courseid']); 
                                echo json_encode($get_course_times['time'] ?? []); 
                            } ?>, 
                        datasets: [
                            {
                                label: 'Media de Finalización en días',
                                backgroundColor: 'rgba(60,141,188,0.9)',
                                borderColor: 'rgba(60,141,188,0.8)',
                                pointRadius: false,
                                data: <?php 
                                    if (!isset($_GET['courseid'])) { 
                                        $get_site_times = adminlte_getdata::get_site_times(); 
                                        echo json_encode($get_site_times['avgavg'] ?? []); 
                                    } else { 
                                        $get_course_times = adminlte_getdata::get_course_times($_GET['courseid']); 
                                        echo json_encode($get_course_times['avg'] ?? []); 
                                    } ?>
                            },
                            {
                                label: 'Finalización en días',
                                backgroundColor: 'rgba(210, 214, 222, 1)',
                                borderColor: 'rgba(210, 214, 222, 1)',
                                pointRadius: false,
                                data: <?php 
                                    if (!isset($_GET['courseid'])) { 
                                        echo json_encode($get_site_times['avg'] ?? []); 
                                    } else { 
                                        echo json_encode($get_course_times['time'] ?? []); 
                                    } ?>,
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: { grid: { display: false } },
                            y: { grid: { display: false } }
                        }
                    }
                });
                console.log('Time chart initialized successfully');
            }
        } catch (e) {
            console.error('Time chart initialization error:', e);
        }
    }
}

function initializeSortables() {
    if (typeof $.fn.sortable === 'undefined') {
        console.error('jQuery UI sortable not available');
        return;
    }
    
    $('.connectedSortable').sortable({
        placeholder: 'sort-highlight',
        connectWith: '.connectedSortable',
        handle: '.card-header, .nav-tabs',
        forcePlaceholderSize: true,
        zIndex: 999999
    });
    $('.connectedSortable .card-header').css('cursor', 'move');
    console.log('Sortables initialized successfully');
}

function initializeKnobs() {
    if (typeof $.fn.knob === 'undefined') {
        console.error('jQuery Knob not available');
        return;
    }
    
    $('.knob').knob();
    console.log('Knobs initialized successfully');
}

function initializeDataTables() {
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('DataTables not available');
        return;
    }
    
    if ($('#enroltable').length) {
        $("#enroltable").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            processing: true,
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "Todos"] ],
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json"
            },
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis']
        });
        console.log('DataTables initialized successfully');
    }
}

function changeDashboardLanguage(lang) {
    var url = new URL(window.location);
    url.searchParams.set('lang', lang);
    window.location.href = url.href;
}
</script>