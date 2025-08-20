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
require_once($CFG->dirroot . '/local/cuadrodemando/classes/navbar_helper.php');
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/getdata.php');
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/total_logins_json.php');
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/users_logins_json.php');
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/total_user_changes_json.php');

echo html_writer::start_div('dashboard-wrapper');

// Use navbar helper
echo \local_cuadrodemando\navbar_helper::render_navbar('users');

// Content Wrapper
echo html_writer::start_div('content-wrapper');

// Content Header
echo html_writer::start_tag('section', ['class' => 'content-header']);
echo html_writer::start_div('container-fluid');
echo html_writer::start_div('row mb-2');
echo html_writer::start_div('col-sm-6');

// Check if viewing specific user
if (isset($_GET['userid'])) {
    $user_info = $DB->get_record('user', ['id' => $_GET['userid']]);
    $username = $user_info->firstname . ' ' . $user_info->lastname;
    
    if (isset($_GET['roleid']) && $_GET['roleid'] == 5) {
        echo html_writer::tag('h1', get_string('userdetails_student', 'local_cuadrodemando', html_writer::tag('b', $username)));
    } elseif (isset($_GET['roleid']) && $_GET['roleid'] == 3) {
        echo html_writer::tag('h1', get_string('userdetails_teacher', 'local_cuadrodemando', html_writer::tag('b', $username)));
    } else {
        echo html_writer::tag('h1', get_string('userdetails_user', 'local_cuadrodemando', html_writer::tag('b', $username)));
    }
} else {
    echo html_writer::tag('h1', get_string('users_overview', 'local_cuadrodemando'));
}

echo html_writer::end_div();
echo html_writer::start_div('col-sm-6');
echo html_writer::start_tag('ol', ['class' => 'breadcrumb float-sm-right']);
echo html_writer::start_tag('li', ['class' => 'breadcrumb-item']);
echo html_writer::link($CFG->wwwroot . '/local/cuadrodemando/', get_string('home', 'local_cuadrodemando'));
echo html_writer::end_tag('li');
echo html_writer::start_tag('li', ['class' => 'breadcrumb-item active']);
echo html_writer::link($CFG->wwwroot . '/local/cuadrodemando/users', get_string('users', 'local_cuadrodemando'));
echo html_writer::end_tag('li');
echo html_writer::end_tag('ol');
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_tag('section');

// Main content
echo html_writer::start_tag('section', ['class' => 'content']);
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
    echo html_writer::tag('i', '', ['class' => 'fas fa-users']);
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', get_string('totalusers', 'local_cuadrodemando'), ['class' => 'small-box-footer']);
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
    echo html_writer::tag('i', '', ['class' => 'fas fa-user-check']);
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', get_string('activeusers_month', 'local_cuadrodemando'), ['class' => 'small-box-footer']);
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
    echo html_writer::tag('i', '', ['class' => 'fas fa-user-plus']);
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', get_string('newusers_month', 'local_cuadrodemando'), ['class' => 'small-box-footer']);
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
    echo html_writer::tag('i', '', ['class' => 'fas fa-user-clock']);
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', get_string('onlineusers', 'local_cuadrodemando'), ['class' => 'small-box-footer']);
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
echo html_writer::tag('h3', get_string('login_statistics', 'local_cuadrodemando'), ['class' => 'card-title']);
echo html_writer::end_div(); // card-header
echo html_writer::start_div('card-body');
echo html_writer::tag('canvas', '', ['id' => 'loginChart', 'style' => 'height: 400px;']);
echo html_writer::end_div(); // card-body
echo html_writer::end_div(); // card
echo html_writer::end_div(); // col

// User changes
$user_changes = Total_user_changes_json::get_total_user_changes();
echo html_writer::start_div('col-md-6');
echo html_writer::start_div('card');
echo html_writer::start_div('card-header');
echo html_writer::tag('h3', get_string('user_changes', 'local_cuadrodemando'), ['class' => 'card-title']);
echo html_writer::end_div(); // card-header
echo html_writer::start_div('card-body');
echo html_writer::tag('canvas', '', ['id' => 'userChangesChart', 'style' => 'height: 400px;']);
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
