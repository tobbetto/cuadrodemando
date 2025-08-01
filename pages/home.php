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

// Dashboard header with language selector
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
echo html_writer::tag('h1', get_string('home', 'local_cuadrodemando'));
echo html_writer::end_div();
echo html_writer::start_div('col-sm-6');
echo html_writer::start_tag('ol', array('class' => 'breadcrumb float-sm-right'));
echo html_writer::start_tag('li', array('class' => 'breadcrumb-item active'));
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
echo html_writer::tag('p', 'Matriculaciones activas (' . date('Y') . ')', array('class' => 'small-box-footer'));
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
    $completion_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['totalaccess'];
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
echo html_writer::tag('span', 'Sesiones abiertas ahora:', array('class' => 'info-box-text'));

$sql = "SELECT count(userid) AS userid FROM {sessions} WHERE userid > 1";
$sessions = $DB->get_record_sql($sql);

echo html_writer::tag('span', !empty($sessions) ? $sessions->userid : 'No hay sesiones abiertas', array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

// Completions this month
if (isset($_GET['month'])) {
    $completion_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['completions'];
} else {
    $completion_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['completions'];
}

echo html_writer::start_div('col-md-3 col-sm-6 col-6');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
$icon_class = !empty($completion_info) ? 'bg-success' : 'bg-danger';
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-award')), array('class' => 'info-box-icon ' . $icon_class));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', 'Finalizaciones este mes:', array('class' => 'info-box-text'));
$completion_text = !empty($completion_info) ? $completion_info : 'No hay finalizaciones este mes 😭';
echo html_writer::tag('span', $completion_text, array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

// Registrations this month
if (isset($_GET['month'])) {
    $registration_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['registrations'];
} else {
    $registration_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['registrations'];
}

echo html_writer::start_div('col-md-3 col-sm-6 col-6');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
$icon_class = !empty($registration_info) ? 'bg-success' : 'bg-danger';
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-user-plus')), array('class' => 'info-box-icon ' . $icon_class));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', 'Altas este mes:', array('class' => 'info-box-text'));
$registration_text = !empty($registration_info) ? $registration_info : 'No hay altas este mes 😭';
echo html_writer::tag('span', $registration_text, array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

// Accesses this month
if (isset($_GET['month'])) {
    $access_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['accesses'];
} else {
    $access_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['accesses'];
}

echo html_writer::start_div('col-md-3 col-sm-6 col-6');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
$icon_class = !empty($access_info) ? 'bg-success' : 'bg-danger';
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-key')), array('class' => 'info-box-icon ' . $icon_class));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', 'Accesos este mes:', array('class' => 'info-box-text'));
$access_text = !empty($access_info) ? $access_info : 'No hay accesos este mes 😭';
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
echo html_writer::tag('span', 'Usuarios activos última hora:', array('class' => 'info-box-text'));
$views_info = Total_views_json::get_total_hourly_views();
$views_text = !empty($views_info) ? $views_info : 'No hay usuarios activos 😭';
echo html_writer::tag('span', $views_text, array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

// Enrollments this month
if (isset($_GET['month'])) {
    $enrolment_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['enrolments'];
} else {
    $enrolment_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['enrolments'];
}

echo html_writer::start_div('col-md-3 col-sm-6 col-6');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
$icon_class = !empty($enrolment_info) ? 'bg-success' : 'bg-danger';
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-user-graduate')), array('class' => 'info-box-icon ' . $icon_class));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', 'Matriculaciones este mes:', array('class' => 'info-box-text'));
$enrolment_text = !empty($enrolment_info) ? $enrolment_info : 'No hay matriculaciones este mes 😭';
echo html_writer::tag('span', $enrolment_text, array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

// Suspensions this month
if (isset($_GET['month'])) {
    $suspension_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['suspensions'];
} else {
    $suspension_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['suspensions'];
}

echo html_writer::start_div('col-md-3 col-sm-6 col-6');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
$icon_class = !empty($suspension_info) ? 'bg-danger' : 'bg-success';
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-user-minus')), array('class' => 'info-box-icon ' . $icon_class));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', 'Bajas este mes:', array('class' => 'info-box-text'));
$suspension_text = !empty($suspension_info) ? $suspension_info : 'No hay bajas este mes 😀';
echo html_writer::tag('span', $suspension_text, array('class' => 'info-box-number'));
echo html_writer::end_div(); // info-box-content
echo html_writer::end_div(); // info-box
echo html_writer::end_div(); // col

// Messages this month
if (isset($_GET['month'])) {
    $message_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['messages'];
} else {
    $message_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['messages'];
}

echo html_writer::start_div('col-md-3 col-sm-6 col-6');
echo html_writer::start_div('info-box shadow-sm', array('style' => 'min-height: 106.5px'));
$icon_class = !empty($message_info) ? 'bg-success' : 'bg-danger';
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fas fa-solid fa-envelopes-bulk')), array('class' => 'info-box-icon ' . $icon_class));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', 'Mensajes este mes:', array('class' => 'info-box-text'));
$message_text = !empty($message_info) ? $message_info : 'No hay mensajes este mes 😭';
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
echo html_writer::tag('h3', html_writer::tag('i', '', array('class' => 'fas fa-calendar-alt mr-1')) . ' Calendario', array('class' => 'card-title'));
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
