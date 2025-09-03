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
echo '<script src="/local/cuadrodemando/thirdpartylibs/fontawesome/js/all.min.js" crossorigin="anonymous"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/jquery-ui/jquery-ui.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/jquery-knob/jquery.knob.min.js"></script>';
echo '<script src="/local/cuadrodemando/assets/scripts/bootstrap/bootstrap.bundle.min.js"></script>';
echo '<script src="/local/cuadrodemando/assets/scripts/map/mapa.js"></script>';
echo '<link rel="stylesheet" href="/local/cuadrodemando/assets/scripts/map/estilos.css"/>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/chart/chart.umd.js"></script>';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/overlayscrollbars/overlayscrollbars.min.css">';
echo '<script src="/local/cuadrodemando/thirdpartylibs/overlayscrollbars/overlayscrollbars.browser.es6.min.js"></script>';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/datatables/dataTables.bootstrap5.min.css">';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/datatables/responsive.bootstrap5.min.css">';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/datatables/buttons.bootstrap5.min.css">';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/datatables/datatables.min.css">';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/datatables.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/jquery.dataTables.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/dataTables.buttons.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/jszip.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/pdfmake.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/vfs_fonts.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/buttons.html5.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/buttons.print.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/buttons.bootstrap5.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/datatables/buttons.colVis.min.js"></script>';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/adminlte/adminlte.min.css">';
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

// Use navbar helper
echo \local_cuadrodemando\navbar_helper::render_navbar('users');

// Content Wrapper
echo html_writer::start_div('content-wrapper');

// Content Header (Page header)
echo html_writer::start_tag('section', ['class' => 'content-header']);
echo html_writer::start_div('container-fluid');
echo html_writer::start_div('row mb-2');
echo html_writer::start_div('col-sm-6');

if (isset($_GET['userid'])) {
    $user_info = $DB->get_record('user', ['id' => $_GET['userid']]);
    if (isset($_GET['roleid']) && $_GET['roleid'] == 5) {
        echo html_writer::tag('h1', get_string('studentdetails', 'local_cuadrodemando') . ': <b>' . $user_info->firstname . ' ' . $user_info->lastname . '</b>');
    } elseif (isset($_GET['roleid']) && $_GET['roleid'] == 3) {
        echo html_writer::tag('h1', get_string('teacherdetails', 'local_cuadrodemando') . ': <b>' . $user_info->firstname . ' ' . $user_info->lastname . '</b>');
    } else {
        echo html_writer::tag('h1', get_string('userdetails', 'local_cuadrodemando') . ': <b>' . $user_info->firstname . ' ' . $user_info->lastname . '</b>');
    }
} else {
    echo html_writer::tag('h1', get_string('usersoverview', 'local_cuadrodemando'));
}

echo html_writer::end_div(); // col-sm-6
echo html_writer::start_div('col-sm-6');
echo html_writer::start_tag('ol', ['class' => 'breadcrumb float-sm-right']);
echo html_writer::start_tag('li', ['class' => 'breadcrumb-item']);
echo html_writer::link($CFG->wwwroot . '/local/cuadrodemando/', get_string('home', 'local_cuadrodemando'));
echo html_writer::end_tag('li');
echo html_writer::start_tag('li', ['class' => 'breadcrumb-item active']);
echo html_writer::link($CFG->wwwroot . '/local/cuadrodemando/users', get_string('users', 'local_cuadrodemando'));
echo html_writer::end_tag('li');
echo html_writer::end_tag('ol');
echo html_writer::end_div(); // col-sm-6
echo html_writer::end_div(); // row mb-2
echo html_writer::end_div(); // container-fluid
echo html_writer::end_tag('section'); // content-header

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
        label: '# de accesos',
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
        label: '# de usuarios',
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
            titleAttr: 'Copiar tabla'
        },
        {
            extend: 'csvHtml5',
            text: '<i class="fas fa-file-csv"></i>',
            titleAttr: 'Exportar CSV'
        },
        {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i>',
            titleAttr: 'Exportar Excel'
        },
        {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            text: '<i class="fas fa-file-pdf"></i>',
            titleAttr: 'Exportar PDF'
        },
        {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            text: '<i class="fas fa-print"></i>',
            download: 'open',
            titleAttr: 'Imprimir tabla'
        },
        'colvis'
    ],
    language: {
        buttons: {
            colvis: 'Filtrar columnas'
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
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "Todos"]],
            language: {
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "datetime": {
                    "previous": "Anterior",
                    "next": "Proximo",
                    "hours": "Horas",
                    "minutes": "Minutos",
                    "seconds": "Segundos",
                    "unknown": "-",
                    "amPm": ["AM", "PM"],
                    "months": {
                        "0": "Enero", "1": "Febrero", "2": "Marzo", "3": "Abril",
                        "4": "Mayo", "5": "Junio", "6": "Julio", "7": "Agosto",
                        "8": "Septiembre", "9": "Octubre", "10": "Noviembre", "11": "Diciembre"
                    },
                    "weekdays": ["dom", "lun", "mar", "mié", "jue", "vie", "sab"]
                },
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Ocultar columnas",
                    "collection": "Colección",
                    "colvisRestore": "Restaurar visibilidad",
                    "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br /> <br /> Para cancelar, haga clic en este mensaje o presione escape.",
                    "copySuccess": {
                        "1": "Copiada 1 fila al portapapeles",
                        "_": "Copiadas %ds fila al portapapeles"
                    },
                    "copyTitle": "Copiar al portapapeles",
                    "csv": "CSV",
                    "excel": "Excel",
                    "pageLength": {
                        "-1": "Mostrar todas las filas",
                        "_": "Mostrar %d filas"
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
                    "clearMessage": "Borrar todo",
                    "collapse": {
                        "0": "Paneles de búsqueda",
                        "_": "Paneles de búsqueda (%d)"
                    },
                    "count": "{total}",
                    "countFiltered": "{shown} ({total})",
                    "emptyPanes": "Sin paneles de búsqueda",
                    "loadMessage": "Cargando paneles de búsqueda",
                    "title": "Filtros Activos - %d",
                    "showMessage": "Mostrar Todo",
                    "collapseMessage": "Colapsar Todo"
                },
                "processing": "Procesando...",
                "lengthMenu": "Mostrar _MENU_ registros",
                "zeroRecords": "No se encontraron resultados",
                "emptyTable": "Ningún dato disponible en esta tabla",
                "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                "search": "Buscar:",
                "infoThousands": ",",
                "loadingRecords": "Cargando..."
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
