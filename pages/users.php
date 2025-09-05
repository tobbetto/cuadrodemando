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
echo '<script src="/local/cuadrodemando/assets/scripts/bootstrap/bootstrap.bundle.min.js"></script>';
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
// single adminlte stylesheet include
// Load vendored AdminLTE v4 and bundled dependencies
echo '<script src="/local/cuadrodemando/thirdpartylibs/adminlte/adminlte.min.js"></script>';

// Add CSS to force hide loading spinners after page load
echo '<style>
.page-loaded .loading-table,
.page-loaded .spinner-border {
    display: none !important;
}
.loading-table {
    transition: opacity 0.3s ease-out;
}
</style>';

echo html_writer::start_div('dashboard-wrapper');

// Generate custom title based on whether we're viewing a specific user
if (isset($_GET['userid'])) {
    $user_info = $DB->get_record('user', ['id' => $_GET['userid']]);
    if (isset($_GET['roleid']) && $_GET['roleid'] == 5) {
        $custom_title = get_string('userdetails_student', 'local_cuadrodemando', $user_info->firstname . ' ' . $user_info->lastname);
    } elseif (isset($_GET['roleid']) && $_GET['roleid'] == 3) {
        $custom_title = get_string('userdetails_teacher', 'local_cuadrodemando', $user_info->firstname . ' ' . $user_info->lastname);
    } else {
        $custom_title = get_string('userdetails_user', 'local_cuadrodemando', $user_info->firstname . ' ' . $user_info->lastname);
    }
} else {
    $custom_title = get_string('users_overview', 'local_cuadrodemando');
}

// Use navbar helper with custom title
echo \local_cuadrodemando\navbar_helper::render_navbar('users', $custom_title);

// Content Wrapper
echo html_writer::start_div('content-wrapper');

// Main content
echo html_writer::start_tag('section', ['class' => 'content']);
echo html_writer::start_div('container-fluid');

// Small boxes (Stat box) - Only show if not viewing specific user
if (!isset($_GET['userid'])) {
    echo html_writer::start_div('row');

    $countCreatedUsers = Total_user_changes_json::get_total_user_changes();
    
    // Users created today
    echo html_writer::start_div('col-lg-3 col-6');
    echo html_writer::start_div('small-box bg-info');
    echo html_writer::start_div('inner');
    echo html_writer::tag('h3', $countCreatedUsers['created']);
    echo html_writer::tag('p', '');
    echo html_writer::end_div(); // inner
    echo html_writer::start_div('icon');
    echo html_writer::tag('i', '', ['class' => 'fas fa-user-plus']);
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', get_string('usersaddedtoday', 'local_cuadrodemando'), ['class' => 'small-box-footer']);
    echo html_writer::end_div(); // small-box
    echo html_writer::end_div(); // col

    // Users deleted today
    echo html_writer::start_div('col-lg-3 col-6');
    echo html_writer::start_div('small-box bg-success');
    echo html_writer::start_div('inner');
    echo html_writer::tag('h3', $countCreatedUsers['deleted']);
    echo html_writer::tag('p', '');
    echo html_writer::end_div(); // inner
    echo html_writer::start_div('icon');
    echo html_writer::tag('i', '', ['class' => 'fas fa-user-minus']);
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', get_string('usersdeletedtoday', 'local_cuadrodemando'), ['class' => 'small-box-footer']);
    echo html_writer::end_div(); // small-box
    echo html_writer::end_div(); // col

    // Users edited today
    echo html_writer::start_div('col-lg-3 col-6');
    echo html_writer::start_div('small-box bg-warning');
    echo html_writer::start_div('inner');
    echo html_writer::tag('h3', $countCreatedUsers['edited']);
    echo html_writer::tag('p', '');
    echo html_writer::end_div(); // inner
    echo html_writer::start_div('icon');
    echo html_writer::tag('i', '', ['class' => 'fas fa-user-edit']);
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', get_string('userseditedtoday', 'local_cuadrodemando'), ['class' => 'small-box-footer']);
    echo html_writer::end_div(); // small-box
    echo html_writer::end_div(); // col

    // User accesses today
    $sql_mysql = "SELECT COUNT(id) AS useraccesses FROM {user} WHERE FROM_UNIXTIME(lastaccess, '%d-%m-%Y') = '" . date('d-m-Y') . "'";
    $sql_oracle = "SELECT COUNT(id) AS useraccesses FROM {user} WHERE to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(lastaccess, 'SECOND'), 'DD-MM-YYYY') = '" . date('d-m-Y') . "'";
    $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;
    $countUserAccesses = $DB->count_records_sql($sql, null);

    echo html_writer::start_div('col-lg-3 col-6');
    echo html_writer::start_div('small-box bg-primary');
    echo html_writer::start_div('inner');
    echo html_writer::tag('h3', $countUserAccesses);
    echo html_writer::tag('p', '');
    echo html_writer::end_div(); // inner
    echo html_writer::start_div('icon');
    echo html_writer::tag('i', '', ['class' => 'fas fa-sign-in-alt']);
    echo html_writer::end_div(); // icon
    echo html_writer::tag('p', get_string('accessestoday', 'local_cuadrodemando'), ['class' => 'small-box-footer']);
    echo html_writer::end_div(); // small-box
    echo html_writer::end_div(); // col

    echo html_writer::end_div(); // row
}

// Main content row
echo html_writer::start_div('row');

// Left col - User access chart
echo html_writer::start_tag('section', ['class' => 'col-lg-6 connectedSortable ui-sortable']);
if (isset($_GET['userid'])) {
    $user_access = adminlte_getdata::count_user_access($_GET['userid']);
} else {
    $user_access = adminlte_getdata::count_user_access(null);
}
echo $user_access;
echo html_writer::end_tag('section');

// Right col - Province user info
echo html_writer::start_tag('section', ['class' => 'col-lg-6 connectedSortable ui-sortable']);
if (isset($_GET['userid'])) {
    $userInfo = adminlte_getdata::count_province_user_card($_GET['userid']);
} else {
    $userInfo = adminlte_getdata::count_province_user_card(null);
}
echo $userInfo;
echo html_writer::end_tag('section');

echo html_writer::end_div(); // row

// User data table row
echo html_writer::start_div('row');
echo html_writer::start_div('col-12');
echo html_writer::start_tag('section', ['class' => 'col-lg-12 connectedSortable ui-sortable']);

if (isset($_GET['userid'])) {
    $userData = adminlte_getdata::get_user_table($_GET['userid'], $_GET['roleid']);
} else {
    $userData = adminlte_getdata::get_user_table(null, null);
}
echo $userData;

echo html_writer::end_tag('section');
echo html_writer::end_div(); // col-12
echo html_writer::end_div(); // row

echo html_writer::end_div(); // container-fluid
echo html_writer::end_tag('section'); // content
echo html_writer::end_div(); // content-wrapper

echo html_writer::end_div(); // dashboard-wrapper
?>

<script>
// Define language strings for JavaScript
var numberOfAccessesLabel = <?php echo json_encode(get_string('numberofaccesses', 'local_cuadrodemando')); ?>;
var numberOfUsersLabel = <?php echo json_encode(get_string('numberofusers', 'local_cuadrodemando')); ?>;

// Hide loading spinners when page is loaded
$(document).ready(function() {
    console.log('Document ready - looking for loading spinners');
    
    // Add class to body to trigger CSS hiding
    $('body').addClass('page-loaded');
    
    // Hide all loading spinners immediately
    $('.loading-table').each(function() {
        console.log('Found loading spinner:', this);
        $(this).hide();
    });
    
    // Also hide spinner-border elements
    $('.spinner-border').each(function() {
        console.log('Found spinner-border:', this);
        $(this).hide();
    });
    
    // Show charts after a brief delay to ensure they're properly rendered
    setTimeout(function() {
        $('.chart canvas').show();
        console.log('Charts should be visible now');
    }, 100);
});

// Window load event - fires after all resources are loaded
$(window).on('load', function() {
    console.log('Window fully loaded - hiding remaining spinners');
    $('body').addClass('page-loaded');
    $('.loading-table').fadeOut(300);
    $('.spinner-border').fadeOut(300);
});

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

// Page specific script
var docDefinition = {
    pageSize: 'A4',
    pageOrientation: 'landscape',
};

// Area Chart 1 - User Logins
var areaChartData = {
    labels: <?php
        if (isset($_GET['userid'])) {
            $userLogins = Users_logins_json::get_users_logins($_GET['userid']);
        } else {
            $userLogins = Total_logins_json::get_total_logins();
        }
        if (empty($userLogins)) {
            echo '["dom.", "lun.", "mar.", "mié.", "jue.", "vie.", "sab."]';
        } else {
            echo $userLogins['dayname'];
        }
    ?>,
    datasets: [{
        label: numberOfAccessesLabel,
        backgroundColor: 'rgba(60,141,188,0.9)',
        borderColor: 'rgba(60,141,188,0.8)',
        borderRadius: 12,
        borderWidth: 1,
        borderSkipped: true,
        hoverBackgroundColor: 'rgb(131 182 234)',
        maxBarThickness: 48,
        pointRadius: false,
        pointColor: '#3b8bba',
        pointStrokeColor: 'rgba(60,141,188,1)',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data: <?php
            if (empty($userLogins)) {
                echo '[0, 0, 0, 0, 0, 0, 0]';
            } else {
                echo $userLogins['logins'];
            }
        ?>
    }]
};

// Bar Chart 1
if (document.getElementById("barChart")) {
    var barChartCanvas = document.getElementById("barChart").getContext('2d');
    var barChartData = $.extend(true, {}, areaChartData);
    var temp0 = areaChartData.datasets[0];
    var highest = Math.max(...<?php echo empty($userLogins) ? '[0]' : $userLogins['logins']; ?>);
    let sum = highest + 3;

    barChartData.datasets[0] = temp0;

    var barChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: true,
        scales: {
            y: {
                beginAtZero: true,
                stepSize: 1,
                max: sum
            }
        },
        layout: {
            autoPadding: true
        }
    };

    new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
    });

    // Hide loading spinner for this chart
    $(barChartCanvas).closest('.chart').find('.loading-table').hide();
}

<?php if (!isset($_GET['userid'])): ?>
// Area Chart 2 - Province Users
var areaChartData2 = {
    labels: <?php
        $provinceUsers = adminlte_getdata::generate_province_user_count();
        echo $provinceUsers['province'];
    ?>,
    datasets: [{
        label: numberOfUsersLabel,
        backgroundColor: 'rgba(60,141,188,0.9)',
        borderColor: 'rgba(60,141,188,0.8)',
        borderRadius: 12,
        borderWidth: 1,
        borderSkipped: true,
        hoverBackgroundColor: 'rgb(131 182 234)',
        maxBarThickness: 48,
        pointRadius: false,
        pointColor: '#3b8bba',
        pointStrokeColor: 'rgba(60,141,188,1)',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data: <?php echo $provinceUsers['count']; ?>
    }]
};

// Bar Chart 2
if (document.getElementById("barChart2")) {
    var barChartCanvas2 = document.getElementById("barChart2").getContext('2d');
    var barChartData2 = $.extend(true, {}, areaChartData2);
    var temp0_2 = areaChartData2.datasets[0];
    var highest2 = Math.max(...<?php echo $provinceUsers['count']; ?>);
    let sum2 = highest2 + 3;

    barChartData2.datasets[0] = temp0_2;

    var barChartOptions2 = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: true,
        scales: {
            y: {
                beginAtZero: true,
                stepSize: 1,
                max: sum2
            }
        },
        layout: {
            autoPadding: true
        }
    };

    new Chart(barChartCanvas2, {
        type: 'bar',
        data: barChartData2,
        options: barChartOptions2
    });

    // Hide loading spinner for this chart
    $(barChartCanvas2).closest('.chart').find('.loading-table').hide();
}
<?php endif; ?>

// DataTables configuration
$.extend($.fn.DataTable.defaults, {
    buttons: [
        {
            extend: 'copyHtml5',
            text: '<i class="fas fa-copy"></i>',
            titleAttr: '<?php echo get_string('copytable', 'local_cuadrodemando'); ?>'
        },
        {
            extend: 'csvHtml5',
            text: '<i class="fas fa-file-csv"></i>',
            titleAttr: '<?php echo get_string('exportcsv', 'local_cuadrodemando'); ?>'
        },
        {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i>',
            titleAttr: '<?php echo get_string('exportexcel', 'local_cuadrodemando'); ?>'
        },
        {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            text: '<i class="fas fa-file-pdf"></i>',
            titleAttr: '<?php echo get_string('exportpdf', 'local_cuadrodemando'); ?>'
        },
        {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            text: '<i class="fas fa-print"></i>',
            download: 'open',
            titleAttr: '<?php echo get_string('printtable', 'local_cuadrodemando'); ?>'
        },
        'colvis'
    ],
    language: {
        buttons: {
            colvis: '<?php echo get_string('filtercolumns', 'local_cuadrodemando'); ?>'
        }
    }
});

$(function () {
    if ($("#usertable").length) {
        console.log('Initializing DataTable for usertable');
        
        // Hide spinners before initializing DataTable
        $('.loading-table').hide();
        
        $("#usertable").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            processing: true,
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "<?php echo get_string('all', 'local_cuadrodemando'); ?>"]],
            language: {
                "info": "<?php echo get_string('showingrecords', 'local_cuadrodemando'); ?>",
                "datetime": {
                    "previous": "<?php echo get_string('previous', 'local_cuadrodemando'); ?>",
                    "next": "<?php echo get_string('next', 'local_cuadrodemando'); ?>",
                    "hours": "<?php echo get_string('hours', 'local_cuadrodemando'); ?>",
                    "minutes": "<?php echo get_string('minutes', 'local_cuadrodemando'); ?>",
                    "seconds": "<?php echo get_string('seconds', 'local_cuadrodemando'); ?>",
                    "unknown": "<?php echo get_string('unknown', 'local_cuadrodemando'); ?>",
                    "amPm": ["<?php echo get_string('am', 'local_cuadrodemando'); ?>", "<?php echo get_string('pm', 'local_cuadrodemando'); ?>"],
                    "months": {
                        "0": "<?php echo get_string('january', 'local_cuadrodemando'); ?>",
                        "1": "<?php echo get_string('february', 'local_cuadrodemando'); ?>",
                        "2": "<?php echo get_string('march', 'local_cuadrodemando'); ?>",
                        "3": "<?php echo get_string('april', 'local_cuadrodemando'); ?>",
                        "4": "<?php echo get_string('may', 'local_cuadrodemando'); ?>",
                        "5": "<?php echo get_string('june', 'local_cuadrodemando'); ?>",
                        "6": "<?php echo get_string('july', 'local_cuadrodemando'); ?>",
                        "7": "<?php echo get_string('august', 'local_cuadrodemando'); ?>",
                        "8": "<?php echo get_string('september', 'local_cuadrodemando'); ?>",
                        "9": "<?php echo get_string('october', 'local_cuadrodemando'); ?>",
                        "10": "<?php echo get_string('november', 'local_cuadrodemando'); ?>",
                        "11": "<?php echo get_string('december', 'local_cuadrodemando'); ?>"
                    },
                    "weekdays": ["<?php echo get_string('sunday', 'local_cuadrodemando'); ?>", "<?php echo get_string('monday', 'local_cuadrodemando'); ?>", "<?php echo get_string('tuesday', 'local_cuadrodemando'); ?>", "<?php echo get_string('wednesday', 'local_cuadrodemando'); ?>", "<?php echo get_string('thursday', 'local_cuadrodemando'); ?>", "<?php echo get_string('friday', 'local_cuadrodemando'); ?>", "<?php echo get_string('saturday', 'local_cuadrodemando'); ?>"]
                },
                "paginate": {
                    "first": "<?php echo get_string('first', 'local_cuadrodemando'); ?>",
                    "last": "<?php echo get_string('last', 'local_cuadrodemando'); ?>",
                    "next": "<?php echo get_string('next', 'local_cuadrodemando'); ?>",
                    "previous": "<?php echo get_string('previous', 'local_cuadrodemando'); ?>"
                },
                "buttons": {
                    "copy": "<?php echo get_string('copy', 'local_cuadrodemando'); ?>",
                    "colvis": "<?php echo get_string('hidecolumns', 'local_cuadrodemando'); ?>",
                    "collection": "<?php echo get_string('collection', 'local_cuadrodemando'); ?>",
                    "colvisRestore": "<?php echo get_string('restorevisibility', 'local_cuadrodemando'); ?>",
                    "copyKeys": "<?php echo get_string('copykeys', 'local_cuadrodemando'); ?>",
                    "copySuccess": {
                        "1": "<?php echo get_string('copyrow', 'local_cuadrodemando'); ?>",
                        "_": "<?php echo get_string('copyrows', 'local_cuadrodemando'); ?>"
                    },
                    "copyTitle": "<?php echo get_string('copytitle', 'local_cuadrodemando'); ?>",
                    "csv": "CSV",
                    "excel": "Excel",
                    "pageLength": {
                        "-1": "<?php echo get_string('showallrows', 'local_cuadrodemando'); ?>",
                        "_": "<?php echo get_string('showrows', 'local_cuadrodemando'); ?>"
                    },
                    "pdf": "PDF",
                    "print": "Imprimir",
                    "renameState": "Cambiar nombre",
                    "updateState": "Actualizar",
                    "createState": "Crear Estado",
                    "removeAllStates": "Remover Estados",
                    "removeState": "Remover",
                    "savedStates": "Estados Guardados",
                    "stateRestore": "Estado %d"
                },
                "searchPanes": {
                    "clearMessage": "<?php echo get_string('clearmessage', 'local_cuadrodemando'); ?>",
                    "collapse": {
                        "0": "<?php echo get_string('searchpanes', 'local_cuadrodemando'); ?>",
                        "_": "<?php echo get_string('searchpanesplural', 'local_cuadrodemando'); ?>"
                    },
                    "count": "{total}",
                    "countFiltered": "{shown} ({total})",
                    "emptyPanes": "<?php echo get_string('emptypanes', 'local_cuadrodemando'); ?>",
                    "loadMessage": "<?php echo get_string('loadmessage', 'local_cuadrodemando'); ?>",
                    "title": "<?php echo get_string('title', 'local_cuadrodemando'); ?>",
                    "showMessage": "<?php echo get_string('showmessage', 'local_cuadrodemando'); ?>",
                    "collapseMessage": "<?php echo get_string('collapsemessage', 'local_cuadrodemando'); ?>"
                },
                "processing": "<?php echo get_string('processing', 'local_cuadrodemando'); ?>",
                "lengthMenu": "<?php echo get_string('lengthmenu', 'local_cuadrodemando'); ?>",
                "zeroRecords": "<?php echo get_string('zerorecords', 'local_cuadrodemando'); ?>",
                "emptyTable": "<?php echo get_string('emptytable', 'local_cuadrodemando'); ?>",
                "infoEmpty": "<?php echo get_string('infoempty', 'local_cuadrodemando'); ?>",
                "infoFiltered": "<?php echo get_string('infofiltered', 'local_cuadrodemando'); ?>",
                "search": "<?php echo get_string('search', 'local_cuadrodemando'); ?>",
                "infoThousands": ",",
                "loadingRecords": "<?php echo get_string('loadingrecords', 'local_cuadrodemando'); ?>"
            },
            "preDrawCallback": function(settings) {
                console.log('DataTable preDrawCallback - hiding spinners');
                $('.loading-table').hide();
            },
            "drawCallback": function(settings) {
                console.log('DataTable drawCallback - hiding spinners');
                $('.loading-table').hide();
            },
            "initComplete": function(settings, json) {
                console.log('DataTable initComplete - hiding spinners');
                $('.loading-table').hide();
                $('.spinner-border').hide();
            }
        }).buttons().container().prependTo('#exportbuttons');
        
        console.log('DataTable initialized');
    }

    // Additional aggressive spinner hiding
    setTimeout(function() {
        console.log('Final timeout - hiding all spinners');
        $('.loading-table').fadeOut(300);
        $('.spinner-border').fadeOut(300);
    }, 500);
    
    // Another timeout for stubborn spinners
    setTimeout(function() {
        console.log('Final final timeout - force hiding all spinners');
        $('.loading-table').hide();
        $('.spinner-border').hide();
    }, 2000);
});
</script>
