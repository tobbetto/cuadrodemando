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

// Disable AMD/RequireJS before loading third-party scripts
echo '<script>';
echo 'if (typeof define === "function" && define.amd) {';
echo '    var originalDefine = define;';
echo '    define = undefined;';
echo '    window.requirejsVars = { originalDefine: originalDefine };';
echo '}';
echo '</script>';

// Direct asset loading (like template.php)
echo '<link rel="stylesheet" type="text/bundle" href="/local/cuadrodemando/thirdpartylibs/fonts-googleapi/fonts.googleapi.css">';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/fontawesome/css/all.min.css">';
// Bootstrap 5 quick include (CDN) - Option A
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/adminlte/adminlte.min.css">';
echo '<script src="/local/cuadrodemando/thirdpartylibs/fontawesome/js/all.min.js" crossorigin="anonymous"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/jquery-ui/jquery-ui.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/jquery-knob/jquery.knob.min.js"></script>';
echo '<script src="/local/cuadrodemando/assets/scripts/bootstrap/bootstrap.bundle.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/map/mapa.js"></script>';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/map/estilos.css"/>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/chart/chart.umd.js"></script>';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/overlayscrollbars/overlayscrollbars.min.css">';
echo '<script src="/local/cuadrodemando/thirdpartylibs/overlayscrollbars/overlayscrollbars.browser.es6.min.js"></script>';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/datatables/dataTables.bootstrap5.min.css">';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/datatables/responsive.bootstrap5.min.css">';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/datatables/buttons.bootstrap5.min.css">';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/datatables/datatables.min.css">';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/datatables.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/dataTables.buttons.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/jszip.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/pdfmake.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/vfs_fonts.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/buttons.html5.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/buttons.print.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/buttons.bootstrap5.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/buttons.colVis.min.js"></script>';
// Load vendored AdminLTE v4 and bundled dependencies
echo '<script src="/local/cuadrodemando/thirdpartylibs/adminlte/adminlte.min.js"></script>';

global $OUTPUT, $CFG, $DB;

// Include necessary classes
require_once($CFG->dirroot . '/local/cuadrodemando/classes/navbar_helper.php');
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/getdata.php');
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/monthly_numbers_json.php');
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/total_hourly_views_json.php');

echo html_writer::start_div('dashboard-wrapper');

// Use navbar helper
echo \local_cuadrodemando\navbar_helper::render_navbar('home');

// Content Wrapper
echo html_writer::start_div('content-wrapper');

// Main content
echo html_writer::start_tag('section', array('class' => 'content'));
echo html_writer::start_div('container-fluid');

// Get monthly data once and set up helper variables
$monthly_data = Monthly_numbers_json::get_month_numbers();
if (isset($_GET['month']) && isset($_GET['year'])) {
    $selected_month = $_GET['month'];
    $selected_year = $_GET['year'];
} else {
    $selected_month = date('m', time());
    $selected_year = date('Y', time());
}

// Helper function to safely get monthly data with fallback to 0
function get_monthly_stat($data, $month, $year, $stat) {
    return isset($data[$month][$year][$stat]) ? $data[$month][$year][$stat] : 0;
}

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
echo html_writer::tag('p', get_string('visiblecourses', 'local_cuadrodemando'), array('class' => 'small-box-footer'));
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
        AND to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(timestart, 'SECOND'), 'YYYY') = '" . date('Y') . "'";

$sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;
$enrolCount = $DB->count_records_sql($sql, null);

echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-success');
echo html_writer::start_div('inner');
echo html_writer::tag('h3', $enrolCount);
echo html_writer::tag('p', '');
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', array('class' => 'fas fa-user-graduate'));
echo html_writer::end_div(); // icon
echo html_writer::tag('p', get_string('activeenrolments', 'local_cuadrodemando') . ' (' . date('Y') . ')', array('class' => 'small-box-footer'));
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col

// Registered Users
$sql = "SELECT COUNT(*) FROM {user} WHERE deleted = 0 AND suspended = 0 AND length(email) > 1 AND length(firstname) > 2 AND length(lastname) > 2 AND NOT regexp_like(firstname, '[0-9]') AND NOT regexp_like(username, '[#]') AND NOT regexp_like(lastname, 'Buzón') AND NOT regexp_like(firstname, 'Buzón')";
$userCount = $DB->count_records_sql($sql, null);

echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-warning');
echo html_writer::start_div('inner');
echo html_writer::tag('h3', $userCount);
echo html_writer::tag('p', '');
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', array('class' => 'fas fa-user-plus'));
echo html_writer::end_div(); // icon
echo html_writer::tag('p', get_string('registeredusers', 'local_cuadrodemando'), array('class' => 'small-box-footer'));
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col

// Unique Accesses
echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-primary');
echo html_writer::start_div('inner');

$completion_info = get_monthly_stat($monthly_data, $selected_month, $selected_year, 'totalaccess');

echo html_writer::tag('h3', $completion_info);
echo html_writer::tag('p', ' ');
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', array('class' => 'fas fa-fingerprint'));
echo html_writer::end_div(); // icon
echo html_writer::tag('p', get_string('uniqueaccesses', 'local_cuadrodemando') . ' (' . date('Y') . ') <br />', array('class' => 'small-box-footer'));
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col

echo html_writer::end_div(); // row

// Calendar section (if exists in getdata class)
if (class_exists('adminlte_getdata') && method_exists('adminlte_getdata', 'get_month_section')) {
    if (isset($_GET['month'])) {
        $calendar_info = adminlte_getdata::get_month_section($_GET['month'], $_GET['year']);
    } else {
        $calendar_info = adminlte_getdata::get_month_section(date('m', time()), date('Y', time()));
    }
    echo $calendar_info;
}

// Second row - Info boxes
echo html_writer::start_div('row');

// Open Sessions
echo html_writer::start_div('col-md-3 col-sm-6 col-6');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-right-to-bracket')), array('class' => 'info-box-icon bg-success'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('opensessionsnow', 'local_cuadrodemando'), array('class' => 'info-box-text'));

$sql = "SELECT count(userid) AS userid FROM {sessions} WHERE userid > 1";
$sessions = $DB->get_record_sql($sql);

echo html_writer::tag('span', !empty($sessions) ? $sessions->userid : get_string('noopensessions', 'local_cuadrodemando'), array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

// Completions this month
$completion_info = get_monthly_stat($monthly_data, $selected_month, $selected_year, 'completions');

echo html_writer::start_div('col-md-3 col-sm-6 col-6');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
$icon_class = !empty($completion_info) ? 'bg-success' : 'bg-danger';
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-award')), array('class' => 'info-box-icon ' . $icon_class));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('completionsthismonth', 'local_cuadrodemando'), array('class' => 'info-box-text'));
$completion_text = !empty($completion_info) ? $completion_info : get_string('nocompletionsthismonth', 'local_cuadrodemando');
echo html_writer::tag('span', $completion_text, array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

// Registrations this month
$registration_info = get_monthly_stat($monthly_data, $selected_month, $selected_year, 'registrations');

echo html_writer::start_div('col-md-3 col-sm-6 col-6');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
$icon_class = !empty($registration_info) ? 'bg-success' : 'bg-danger';
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-user-plus')), array('class' => 'info-box-icon ' . $icon_class));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('registrationsthismonth', 'local_cuadrodemando'), array('class' => 'info-box-text'));
$registration_text = !empty($registration_info) ? $registration_info : get_string('noregistrationsthismonth', 'local_cuadrodemando');
echo html_writer::tag('span', $registration_text, array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

// Accesses this month
$access_info = get_monthly_stat($monthly_data, $selected_month, $selected_year, 'accesses');

echo html_writer::start_div('col-md-3 col-sm-6 col-6');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
$icon_class = !empty($access_info) ? 'bg-success' : 'bg-danger';
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-key')), array('class' => 'info-box-icon ' . $icon_class));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('accessesthismonth', 'local_cuadrodemando'), array('class' => 'info-box-text'));
$access_text = !empty($access_info) ? $access_info : get_string('noaccessesthismonth', 'local_cuadrodemando');
echo html_writer::tag('span', $access_text, array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

echo html_writer::end_div(); // row

// Third row
echo html_writer::start_div('row');

// Active users last hour
echo html_writer::start_div('col-md-3 col-sm-12 col-12');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-user-clock')), array('class' => 'info-box-icon bg-success'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('activeuserslasthour', 'local_cuadrodemando'), array('class' => 'info-box-text'));
$views_info = Total_views_json::get_total_hourly_views();
$views_text = !empty($views_info) ? $views_info : get_string('noactiveusers', 'local_cuadrodemando');
echo html_writer::tag('span', $views_text, array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

// Enrollments this month
$enrolment_info = get_monthly_stat($monthly_data, $selected_month, $selected_year, 'enrolments');

echo html_writer::start_div('col-md-3 col-sm-6 col-6');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
$icon_class = !empty($enrolment_info) ? 'bg-success' : 'bg-danger';
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-user-graduate')), array('class' => 'info-box-icon ' . $icon_class));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('enrollmentsthismonth', 'local_cuadrodemando'), array('class' => 'info-box-text'));
$enrolment_text = !empty($enrolment_info) ? $enrolment_info : get_string('noenrollmentsthismonth', 'local_cuadrodemando');
echo html_writer::tag('span', $enrolment_text, array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

// Suspensions this month
$suspension_info = get_monthly_stat($monthly_data, $selected_month, $selected_year, 'suspensions');

echo html_writer::start_div('col-md-3 col-sm-6 col-6');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
$icon_class = !empty($suspension_info) ? 'bg-danger' : 'bg-success';
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-user-minus')), array('class' => 'info-box-icon ' . $icon_class));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('suspensionsthismonth', 'local_cuadrodemando'), array('class' => 'info-box-text'));
$suspension_text = !empty($suspension_info) ? $suspension_info : get_string('nosuspensionsthismonth', 'local_cuadrodemando');
echo html_writer::tag('span', $suspension_text, array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

// Messages this month
$message_info = get_monthly_stat($monthly_data, $selected_month, $selected_year, 'messages');

echo html_writer::start_div('col-md-3 col-sm-6 col-6');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
$icon_class = !empty($message_info) ? 'bg-success' : 'bg-danger';
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-envelopes-bulk')), array('class' => 'info-box-icon ' . $icon_class));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('messagesthismonth', 'local_cuadrodemando'), array('class' => 'info-box-text'));
$message_text = !empty($message_info) ? $message_info : get_string('nomessagesthismonth', 'local_cuadrodemando');
echo html_writer::tag('span', $message_text, array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

echo html_writer::end_div(); // row

// Calendar section
echo html_writer::start_div('row align-items-center');
echo html_writer::start_tag('section', array('class' => 'col-lg-12 connectedSortable'));
echo html_writer::start_div('card bg-gradient-muted card-indigo card-outline', array('data-toggle' => 'tooltip', 'data-placement' => 'center'));
echo html_writer::start_div('card-header border-0');
echo html_writer::tag('h3', html_writer::tag('i', '', array('class' => 'fas fa-calendar-alt mr-1')) . ' ' . get_string('calendar', 'local_cuadrodemando'), array('class' => 'card-title'));
echo html_writer::start_div('card-tools');
echo html_writer::tag('button', html_writer::tag('i', '', array('class' => 'fas fa-minus')), array('type' => 'button', 'class' => 'btn btn-indigo btn-tool', 'data-card-widget' => 'collapse', 'title' => 'Collapse'));
echo html_writer::end_div(); // card-tools
echo html_writer::end_div(); // card-header

echo html_writer::start_div('card-body pt-0');

if (isset($_GET['month'])) {
    $calendarmonth = strtotime(date('01-' . $_GET['month'] . '-' . $_GET['year']));
} else {
    $calendarmonth = time();
}

echo html_writer::tag('iframe', '', array(
    'width' => '100%',
    'height' => '538px',
    'style' => 'border:0;',
    'src' => $CFG->wwwroot . '/calendar/view.php?view=month&time=' . $calendarmonth . '&layout=embedded'
));

echo html_writer::end_div(); // card-body
echo html_writer::end_div(); // card
echo html_writer::end_tag('section');
echo html_writer::end_div(); // row

echo html_writer::end_div(); // container-fluid
echo html_writer::end_tag('section'); // content
echo html_writer::end_div(); // content-wrapper

echo html_writer::end_div(); // dashboard-wrapper

// JavaScript for interactivity
?>

<script>
function changeDashboardLanguage(lang) {
    // Reload the page with the lang parameter
    var url = new URL(window.location.href);
    url.searchParams.set('lang', lang);
    window.location.href = url.toString();
}

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
    // Flot Interactive Chart (if Flot is available)
    if (typeof $ !== 'undefined' && $.plot) {
        var data = [];
        for (var i = 0; i < 60; ++i) {
            <?php 
            $sql = "SELECT count(userid) AS userid FROM {sessions} WHERE userid != 0"; 
            $sessions = $DB->get_record_sql($sql); 
            ?>
            data.push(<?php echo $sessions->userid; ?>);
        }
        
        var totalPoints = 60;

        function getRandomData() {
            if (data.length > 0) {
                data = data.slice(1);
            }

            while (data.length < totalPoints) {
                <?php 
                $sql = "SELECT count(userid) AS userid FROM {sessions} WHERE userid != 0"; 
                $sessions = $DB->get_record_sql($sql); 
                ?>
                var prev = data.length > 0 ? data[data.length - 1] : 5,
                    y = <?php echo $sessions->userid; ?>;

                if (y < 0) {
                    y = 0;
                } else if (y > 5) {
                    y = 5;
                }

                data.push(y);
            }

            var res = [];
            for (var i = 0; i < data.length; ++i) {
                res.push([i, data[i]]);
            }

            return res;
        }

        var interactive_plot = $.plot('#interactive', [
            {
                data: getRandomData(),
            }
        ], {
            grid: {
                borderColor: '#f3f3f3',
                borderWidth: 1,
                tickColor: '#f3f3f3'
            },
            series: {
                color: '#3c8dbc',
                lines: {
                    lineWidth: 2,
                    show: true,
                    fill: true,
                },
            },
            yaxis: {
                min: 0,
                max: 5,
                show: true
            },
            xaxis: {
                show: true
            }
        });

        var updateInterval = 500; // Fetch data every x milliseconds
        var realtime = 'on'; // If == to on then fetch data every x seconds. else stop fetching
        
        function update() {
            interactive_plot.setData([getRandomData()]);
            interactive_plot.draw();
            if (realtime === 'on') {
                setTimeout(update, updateInterval);
            }
        }

        // Initialize realtime data fetching
        if (realtime === 'on') {
            update();
        }

        // Realtime toggle
        $('#realtime .btn').click(function () {
            if ($(this).data('toggle') === 'on') {
                realtime = 'on';
            } else {
                realtime = 'off';
            }
            update();
        });
    }
});
</script>
