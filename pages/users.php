<?php
/**
 * Dashboard users page content
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
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/total_logins_json.php');
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/users_logins_json.php');
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/total_user_changes_json.php');

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
    array('class' => 'nav-link active')
);
echo html_writer::end_div();

echo html_writer::start_div('nav-item');
echo html_writer::link(
    new moodle_url('/local/cuadrodemando/pages/geo.php'),
    get_string('geo', 'local_cuadrodemando'),
    array('class' => 'nav-link')
);
echo html_writer::end_div();

echo html_writer::end_div(); // navbar-nav
echo html_writer::end_div(); // container-fluid
echo html_writer::end_tag('nav');
echo html_writer::end_div(); // dashboard-nav

// Dashboard header with language selector
echo html_writer::start_div('dashboard-header mb-4 d-flex justify-content-between align-items-center');
echo html_writer::start_div('dashboard-title');

// Check if viewing specific user
if (isset($_GET['userid'])) {
    $user_info = $DB->get_record('user', ['id' => $_GET['userid']]);
    if (isset($_GET['roleid']) && $_GET['roleid'] == 5) {
        echo html_writer::tag('h2', 'Detalles del alumno: ' . html_writer::tag('b', $user_info->firstname . ' ' . $user_info->lastname), array('class' => 'h3'));
    } elseif (isset($_GET['roleid']) && $_GET['roleid'] == 3) {
        echo html_writer::tag('h2', 'Detalles del docente: ' . html_writer::tag('b', $user_info->firstname . ' ' . $user_info->lastname), array('class' => 'h3'));
    } else {
        echo html_writer::tag('h2', 'Detalles del usuario: ' . html_writer::tag('b', $user_info->firstname . ' ' . $user_info->lastname), array('class' => 'h3'));
    }
} else {
    echo html_writer::tag('h2', 'Vista general de los usuarios', array('class' => 'h3'));
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

if (isset($_GET['userid'])) {
    if (isset($_GET['roleid']) && $_GET['roleid'] == 5) {
        echo html_writer::tag('h1', 'Detalles del alumno: ' . html_writer::tag('b', $user_info->firstname . ' ' . $user_info->lastname));
    } elseif (isset($_GET['roleid']) && $_GET['roleid'] == 3) {
        echo html_writer::tag('h1', 'Detalles del docente: ' . html_writer::tag('b', $user_info->firstname . ' ' . $user_info->lastname));
    } else {
        echo html_writer::tag('h1', 'Detalles del usuario: ' . html_writer::tag('b', $user_info->firstname . ' ' . $user_info->lastname));
    }
} else {
    echo html_writer::tag('h1', 'Vista general de los usuarios');
}

echo html_writer::end_div();
echo html_writer::start_div('col-sm-6');
echo html_writer::start_tag('ol', array('class' => 'breadcrumb float-sm-right'));
echo html_writer::start_tag('li', array('class' => 'breadcrumb-item'));
echo html_writer::link($CFG->wwwroot . '/local/cuadrodemando/', get_string('home', 'local_cuadrodemando'));
echo html_writer::end_tag('li');
echo html_writer::start_tag('li', array('class' => 'breadcrumb-item active'));
echo html_writer::link($CFG->wwwroot . '/local/cuadrodemando/users', get_string('users', 'local_cuadrodemando'));
echo html_writer::end_tag('li');
echo html_writer::end_tag('ol');
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_tag('section');

// Main content
echo html_writer::start_tag('section', array('class' => 'content'));
echo html_writer::start_div('container-fluid');

// User statistics - Only show if not viewing specific user
if (!isset($_GET['userid'])) {
    echo html_writer::start_div('row');

    // Calculate user statistics
    // Total users
    $sql = "SELECT COUNT(*) FROM {user} WHERE deleted = 0 AND suspended = 0";
    $totalUsers = $DB->count_records_sql($sql);

    // Active users this month
    $sql = "SELECT COUNT(DISTINCT userid) FROM {logstore_standard_log} WHERE timecreated >= " . strtotime('first day of this month');
    $activeUsers = $DB->count_records_sql($sql);

    // New users this month
    $sql = "SELECT COUNT(*) FROM {user} WHERE timecreated >= " . strtotime('first day of this month') . " AND deleted = 0";
    $newUsers = $DB->count_records_sql($sql);

    // Online users now
    $sql = "SELECT COUNT(DISTINCT userid) FROM {sessions} WHERE userid > 1";
    $onlineUsers = $DB->count_records_sql($sql);

    // Total users
    echo html_writer::start_div('col-lg-3 col-6');
    echo html_writer::start_div('small-box bg-info');
    echo html_writer::start_div('inner');
    echo html_writer::tag('h3', $totalUsers);
    echo html_writer::tag('p', '');
    echo html_writer::end_div(); // inner
    echo html_writer::start_div('icon');
    echo html_writer::tag('i', '', array('class' => 'fas fa-users'));
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', 'Total usuarios', array('class' => 'small-box-footer'));
    echo html_writer::end_div(); // small-box
    echo html_writer::end_div(); // col

    // Active users
    echo html_writer::start_div('col-lg-3 col-6');
    echo html_writer::start_div('small-box bg-success');
    echo html_writer::start_div('inner');
    echo html_writer::tag('h3', $activeUsers);
    echo html_writer::tag('p', '');
    echo html_writer::end_div(); // inner
    echo html_writer::start_div('icon');
    echo html_writer::tag('i', '', array('class' => 'fas fa-user-check'));
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', 'Usuarios activos (este mes)', array('class' => 'small-box-footer'));
    echo html_writer::end_div(); // small-box
    echo html_writer::end_div(); // col

    // New users
    echo html_writer::start_div('col-lg-3 col-6');
    echo html_writer::start_div('small-box bg-warning');
    echo html_writer::start_div('inner');
    echo html_writer::tag('h3', $newUsers);
    echo html_writer::tag('p', '');
    echo html_writer::end_div(); // inner
    echo html_writer::start_div('icon');
    echo html_writer::tag('i', '', array('class' => 'fas fa-user-plus'));
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', 'Nuevos usuarios (este mes)', array('class' => 'small-box-footer'));
    echo html_writer::end_div(); // small-box
    echo html_writer::end_div(); // col

    // Online users
    echo html_writer::start_div('col-lg-3 col-6');
    echo html_writer::start_div('small-box bg-primary');
    echo html_writer::start_div('inner');
    echo html_writer::tag('h3', $onlineUsers);
    echo html_writer::tag('p', '');
    echo html_writer::end_div(); // inner
    echo html_writer::start_div('icon');
    echo html_writer::tag('i', '', array('class' => 'fas fa-user-clock'));
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', 'Usuarios en línea', array('class' => 'small-box-footer'));
    echo html_writer::end_div(); // small-box
    echo html_writer::end_div(); // col

    echo html_writer::end_div(); // row
}

// Main content row
echo html_writer::start_div('row');

// User data from adminlte_getdata class
if (isset($_GET['userid'])) {
    $user_data = new adminlte_getdata();
    if (isset($_GET['roleid'])) {
        echo $user_data->get_user_numbers($_GET['userid'], $_GET['roleid']);
    } else {
        echo $user_data->get_user_numbers($_GET['userid']);
    }
} else {
    $user_data = new adminlte_getdata();
    echo $user_data->get_user_numbers();
}

echo html_writer::end_div(); // row

// Additional user statistics and charts
echo html_writer::start_div('row');

// Login statistics
$login_stats = Total_logins_json::get_total_logins();
echo html_writer::start_div('col-md-6');
echo html_writer::start_div('card');
echo html_writer::start_div('card-header');
echo html_writer::tag('h3', 'Estadísticas de acceso', array('class' => 'card-title'));
echo html_writer::end_div(); // card-header
echo html_writer::start_div('card-body');
echo html_writer::tag('canvas', '', array('id' => 'loginChart', 'style' => 'height: 400px;'));
echo html_writer::end_div(); // card-body
echo html_writer::end_div(); // card
echo html_writer::end_div(); // col

// User changes
$user_changes = Total_user_changes_json::get_total_user_changes();
echo html_writer::start_div('col-md-6');
echo html_writer::start_div('card');
echo html_writer::start_div('card-header');
echo html_writer::tag('h3', 'Cambios de usuarios', array('class' => 'card-title'));
echo html_writer::end_div(); // card-header
echo html_writer::start_div('card-body');
echo html_writer::tag('canvas', '', array('id' => 'userChangesChart', 'style' => 'height: 400px;'));
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
        // Login chart
        if ($('#loginChart').length) {
            var loginCtx = $('#loginChart').get(0).getContext('2d');
            // Chart initialization would go here
        }

        // User changes chart
        if ($('#userChangesChart').length) {
            var changesCtx = $('#userChangesChart').get(0).getContext('2d');
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
