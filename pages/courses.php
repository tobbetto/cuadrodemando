
<?php
global $DB, $CFG;
include_once 'views/getdata/getdata.php';
require_once($CFG->dirroot . '/local/cuadrodemando/classes/navbar_helper.php');

echo html_writer::start_div('dashboard-wrapper');

// Use navbar helper
echo \local_cuadrodemando\navbar_helper::render_navbar('courses');

// Content Wrapper
echo html_writer::start_div('content-wrapper');

// Content Header
echo html_writer::start_tag('section', ['class' => 'content-header']);
echo html_writer::start_div('container-fluid');
echo html_writer::start_div('row mb-2');
echo html_writer::start_div('col-sm-6');
echo html_writer::start_div('dashboard-wrapper');

echo html_writer::start_div('content-wrapper');
if (isset($_GET['courseid'])) {
  $course_info = $DB->get_record('course', [ 'id' => $_GET['courseid'] ]);
  echo html_writer::tag('h1', 'Detalles del curso: <b>' . $course_info->fullname . '</b> ( ' . $course_info->shortname . ' )');
} else {
  echo html_writer::tag('h1', 'Vista general de los cursos de ' . date('Y'));
}
echo html_writer::end_div(); // content-wrapper
echo html_writer::end_div(); // dashboard-wrapper
echo html_writer::end_div(); // col-sm-6
echo html_writer::start_div('col-sm-6');
echo html_writer::start_tag('ol', ['class' => 'breadcrumb float-sm-right']);
//echo html_writer::tag('li', html_writer::link($CFG->wwwroot . '/adminlte/', 'Inicio'), ['class' => 'breadcrumb-item']);
//echo html_writer::tag('li', html_writer::link($CFG->wwwroot . '/adminlte/courses', 'Cursos'), ['class' => 'breadcrumb-item active']);
echo html_writer::end_tag('ol');
echo html_writer::end_div(); // col-sm-6
echo html_writer::end_div(); // row mb-2
echo html_writer::end_div(); // container-fluid
echo html_writer::end_tag('section');

// Main content
echo html_writer::start_tag('section', ['class' => 'content']);

              // Construct the SQL query
              $sql_mysql = "SELECT COUNT(*) 
                            FROM {course} 
                            WHERE YEAR(FROM_UNIXTIME(timemodified)) = {date('Y')} 
                            AND category <> 261 and id > 1
                            AND YEAR(FROM_UNIXTIME(startdate)) = {date('Y')} ";
              $sql_oracle = "SELECT COUNT(*) 
                             FROM {course} 
                             WHERE to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(timemodified, 'SECOND'), 'YYYY') = '" . date('Y') . "'
                             AND category <> 261 and id > 1
                             AND to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(startdate, 'SECOND'), 'YYYY') = '" . date('Y') . "'";
              $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;
              // Execute the SQL query
              $countCreatedCourses = $DB->count_records_sql($sql, null);


echo html_writer::start_div('container-fluid');

// Small boxes (Stat box)
$hidden = isset($_GET['courseid']) ? 'hidden' : '';
echo html_writer::start_div('row ' . $hidden);
// Created Courses
echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-info');
echo html_writer::start_div('inner');
echo html_writer::tag('h3', $countCreatedCourses);
echo html_writer::tag('p', '');
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', ['class' => 'fas fa-calendar-plus']);
echo html_writer::end_div(); // icon
echo html_writer::tag('p', 'Cursos creados (' . date('Y') . ')', ['class' => 'small-box-footer']);
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col-lg-3 col-6


            // Get the current date and first of year
            // $currentDate = date('Y-m-d');
            // $firstOfYear = trim(date('Y') . '-01-01');

            // Construct the SQL query
            $sql_mysql = "SELECT COUNT(*) FROM {course} WHERE FROM_UNIXTIME(enddate) > CURDATE()";
            $sql_oracle = "SELECT COUNT(*) FROM {course} WHERE enddate > '" . time() . "' AND visible = 1 AND category <> 261 AND id > 1";

            $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;
            // Execute the SQL query
            $countOpenCourses = $DB->count_records_sql($sql, null);


// Open Courses
echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-success');
echo html_writer::start_div('inner');
echo html_writer::tag('h3', $countOpenCourses);
echo html_writer::tag('p', '');
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', ['class' => 'fas fa-calendar-check']);
echo html_writer::end_div(); // icon
echo html_writer::tag('p', 'Cursos activos (' . date('Y') . ')', ['class' => 'small-box-footer']);
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col-lg-3 col-6


            // Get the current date and first of year
            $currentDate = date('Y-m-d');
            $firstOfYear = trim(date('Y') . '-01-01');

            // Construct the SQL query
            $sql_mysql = "SELECT COUNT(*) FROM {course} WHERE YEAR(FROM_UNIXTIME(enddate)) = '" . date('Y') . "'" . " AND FROM_UNIXTIME(enddate) < CURDATE()";
            $sql_oracle = "SELECT COUNT(*) FROM {course} WHERE to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(enddate/60, 'MINUTE'), 'YYYY') >= '" . date('Y') . "'" . " AND enddate < '" . time() . "'";
            $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;
            // Execute the SQL query
            $countFinishedCourses = $DB->count_records_sql($sql, null);

// Finished Courses
echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-warning');
echo html_writer::start_div('inner');
echo html_writer::tag('h3', $countFinishedCourses);
echo html_writer::tag('p', '');
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', ['class' => 'fas fa-calendar-xmark']);
echo html_writer::end_div(); // icon
echo html_writer::tag('p', 'Cursos finalizados (' . date('Y') . ')', ['class' => 'small-box-footer']);
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col-lg-3 col-6


            // Construct the SQL query

            $sql ="SELECT round(avg(count))
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

            // Execute the SQL query
            $avgCourseEnrolment = $DB->count_records_sql($sql, null);
            //$avgCourseEnrolment = $DB->execute($sql, $params_array);($sql, null);


// Average Course Enrolment
echo html_writer::start_div('col-lg-3 col-6');
echo html_writer::start_div('small-box bg-primary');
echo html_writer::start_div('inner');
echo html_writer::tag('h3', $avgCourseEnrolment);
echo html_writer::tag('p', '');
echo html_writer::end_div(); // inner
echo html_writer::start_div('icon');
echo html_writer::tag('i', '', ['class' => 'fas fa-users-line']);
echo html_writer::end_div(); // icon
echo html_writer::tag('p', 'Media matriculados', ['class' => 'small-box-footer']);
echo html_writer::end_div(); // small-box
echo html_writer::end_div(); // col-lg-3 col-6

echo html_writer::end_div(); // row
echo '<!-- /.row -->';

echo '<!-- Main row -->';
echo html_writer::start_div('row');

echo html_writer::end_div(); // row

      // Main row
      echo html_writer::start_div('row');
      // Left col
      if (isset($_GET['courseid'])) {
        $category_numbers = new adminlte_getdata();
        echo $category_numbers->get_category_numbers($_GET['courseid']);
      } else {
        $category_numbers = new adminlte_getdata();
        echo $category_numbers->get_category_numbers($id = NULL);
      }
      // Right col
      if (isset($_GET['courseid'])) {
        $courseEnrolment = new adminlte_getdata();
        echo $courseEnrolment->get_course_numbers($_GET['courseid']);
      } else {
        $courseEnrolment = new adminlte_getdata();
        echo $courseEnrolment->get_course_numbers($id = NULL);
      }
      echo html_writer::end_div(); // row

      echo html_writer::start_div('row');
      // Left col yearly courses
      if (isset($_GET['courseid'])) {
        $course_data = adminlte_getdata::get_yearly_courses($_GET['courseid']);
      } else {
        $course_data = adminlte_getdata::get_yearly_courses($id = NULL);
      }
      echo $course_data;
      echo html_writer::end_div(); // row

      echo html_writer::end_div(); // container-fluid
      echo html_writer::end_tag('section'); // content
      echo html_writer::end_div(); // content-wrapper
?>
<script>
(function checkLibraries() {
    if (typeof Chart !== 'undefined' && typeof $ !== 'undefined' && $.fn.sortable) {
        initializeDashboard();
    } else {
        setTimeout(checkLibraries, 100);
    }
})();

function initializeDashboard() {
    // Make the dashboard widgets sortable Using jquery UI
    $('.connectedSortable').sortable({
    placeholder: 'sort-highlight',
    connectWith: '.connectedSortable',
    handle: '.card-header, .nav-tabs',
    forcePlaceholderSize: true,
    zIndex: 999999
  })
  $('.connectedSortable .card-header').css('cursor', 'move')

  // jQuery UI sortable for the todo list
  $('.todo-list').sortable({
    placeholder: 'sort-highlight',
    handle: '.handle',
    forcePlaceholderSize: true,
    zIndex: 999999
  })
}
</script>
<script>
// Wait for jQuery and jQuery Knob to be loaded before initializing knob elements
(function checkKnob() {
    if (typeof jQuery !== 'undefined' && jQuery.fn.knob) {
        jQuery(function ($) {
            /* jQueryKnob */

            $('.knob').knob({

              draw: function () {

                // "tron" case
                if (this.$.data('skin') == 'tron') {

                  var a   = this.angle(this.cv)  // Angle
                    ,
                      sa  = this.startAngle          // Previous start angle
                    ,
                      sat = this.startAngle         // Start angle
                    ,
                      ea                            // Previous end angle
                    ,
                      eat = sat + a                 // End angle
                    ,
                      r   = true

                  this.g.lineWidth = this.lineWidth

                  this.o.cursor
                  && (sat = eat - 0.3)
                  && (eat = eat + 0.3)

                  if (this.o.displayPrevious) {
                    ea = this.startAngle + this.angle(this.value)
                    this.o.cursor
                    && (sa = ea - 0.3)
                    && (ea = ea + 0.3)
                    this.g.beginPath()
                    this.g.strokeStyle = this.previousColor
                    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false)
                    this.g.stroke()
                  }

                  this.g.beginPath()
                  this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
                  this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false)
                  this.g.stroke()

                  this.g.lineWidth = 2
                  this.g.beginPath()
                  this.g.strokeStyle = this.o.fgColor
                  this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false)
                  this.g.stroke()

                  return false
                }
              }
            })
            /* END JQUERY KNOB */
        });
    } else {
        setTimeout(checkKnob, 100);
    }
})();
</script>
</script>
<script>
// Wait for Chart.js to be loaded before initializing charts
(function checkChart() {
    if (typeof Chart !== 'undefined') {
        initializePieChart();
    } else {
        setTimeout(checkChart, 100);
    }
})();

function initializePieChart() {
  //-------------
  // - PIE CHART -
  //-------------
  var pieChartElement = document.getElementById('pieChart');
  if (!pieChartElement) return;
  var pieChartCanvas = pieChartElement.getContext('2d');
  
  var pieData = {
    labels: <?php echo json_encode($courseEnrolment->pieChartLabel) ?>,
    datasets: [
      {
        data: <?php echo json_encode($courseEnrolment->pieChartData) ?>,
        backgroundColor: <?php echo json_encode($courseEnrolment->background_color) ?> 
      }
    ]
  }
  var pieOptions = {
    legend: { display: false },
    offset : 1
  }
  var pieChart = new Chart(pieChartCanvas, {
    type: 'doughnut',
    data: pieData,
    options: pieOptions
  })
  //-----------------
  // - END PIE CHART -
  //-----------------
}
</script>
<script>
// Wait for Chart.js before initializing bar chart
(function checkChartForBar() {
    if (typeof Chart !== 'undefined') {
        initializeBarChart();
    } else {
        setTimeout(checkChartForBar, 100);
    }
})();

function initializeBarChart() {
var barChartData = {
      labels  :  <?php 
        if (!isset($_GET['courseid'])) { 
          $get_course_categories = adminlte_getdata::get_category_name_number(); 
          echo  $get_course_categories['name']; 
        } else { 
          $get_course_categories = adminlte_getdata::get_course_enrolments($_GET['courseid']); 
          echo $get_course_categories['name']; 
        } ?>, //['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label               : 'Finalizados',
          backgroundColor     : '#28a745',
          borderColor         : '#28a745',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : <?php echo $get_course_categories['count']; ?> //[28, 48, 40, 19, 86, 27, 90]
        },
        {
          label               : 'No finalizados',
          backgroundColor     : '#dc3545',
          borderColor         : '#dc3545',
          pointRadius         : false,
          pointColor          : '#dc3545',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : <?php echo $get_course_categories['students']; ?> //[65, 59, 80, 81, 56, 55, 40]
        },
      ]
    }

    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackedBarChartElement = document.getElementById('stackedBarChart-canvas');
    if (!stackedBarChartElement) return;
    var stackedBarChartCanvas = stackedBarChartElement.getContext('2d');
    
    // Use vanilla JS object extend instead of jQuery extend
    var stackedBarChartData = JSON.parse(JSON.stringify(barChartData));
 
    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        x: {
          stacked: true
        },
        y: {
          stacked: true,
          gridLines: {
            display: false
          },
          ticks: {
              fontColor: 'black',
              fontSize: 12,
              stepSize: 3,
              beginAtZero: true
          }
        }
      }
    }
 
    new Chart(stackedBarChartCanvas, {
      type: 'bar',
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
}
  </script>

<script>
// Wait for Chart.js before initializing geo chart
(function checkChartForGeo() {
    if (typeof Chart !== 'undefined') {
        initializeGeoChart();
    } else {
        setTimeout(checkChartForGeo, 100);
    }
})();

function initializeGeoChart() {
  'use strict'

  var ticksStyle = {
    fontColor: '#FFFFFF',
    //fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true

  var salesChartElement = document.getElementById('geo-chart-canvas');
  if (!salesChartElement) return;

  var salesChart = new Chart(salesChartElement, {
    type: 'bar',
    data: {
      labels: <?php 
        if (!isset($_GET['courseid'])) { 
          $get_course_categories = adminlte_getdata::get_category_name_number(); 
          echo  $get_course_categories['name']; 
        } else { 
          $get_course_categories = adminlte_getdata::get_course_enrolments($_GET['courseid']); 
          echo $get_course_categories['name']; 
        } ?>, //['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
      color: '#000',
      datasets: [
        {
          label: <?php
          if (!isset($_GET['courseid'])) { 
            echo "'# de cursos en categoría'" ;
          } else {
            echo "'# de provincias de los alumnos'" ; 
          } ?>,
          backgroundColor: '#28a745',
          data: <?php echo $get_course_categories['count']; ?> //[1000, 2000, 3000, 2500, 2700, 2500, 3000]
        }
        // ,{
        //   backgroundColor: '#ced4da',
        //   borderColor: '#ced4da',
        //   data: [700, 1700, 2700, 2000, 1800, 1500, 2000]
        // }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect,
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false,
        labels: {
                    // This more specific font property overrides the global property
                    fontColor: 'blue'
                }
      },
      scales: {
        y: {
          display: true,
          gridLines: {
            display: true,
            lineWidth: '4px',
            zeroLineColor: 'transparent'
          },
          ticks: {
              fontColor: 'black',
              fontSize: 12,
              stepSize: 3,
              beginAtZero: true
          }
        },
        x: {
          display: true,
          gridLines: {
            display: false
          },
          ticks: {
              fontColor: 'black',
              fontSize: 12,
              stepSize: 1,
              beginAtZero: true
          }
        }
      }
    }
  })
}
</script>
<script>
<!-- Page specific script -->

    var docDefinition = {
  // a string or { width: number, height: number }
  pageSize: 'A4',

  // by default we use portrait, you can change it to landscape if you wish
  pageOrientation: 'landscape',

  };
  </script>

<script>
  // Invoke Buttons plugin (Bfrtip...)
$.extend($.fn.DataTable.defaults, {
  buttons: [
            {
                extend:    'copyHtml5',
                text:      '<i class="fas fa-copy"></i>',
                titleAttr: 'Copiar tabla'
            },
            {
                extend:    'csvHtml5',
                text:      '<i class="fas fa-file-csv"></i>',
                titleAttr: 'Exportar CSV'
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i>',
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
            //url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
            buttons: {
                colvis: 'Filtrar columnas'
            }
        }
});

  $(function () {
    // Wait for DataTables to be loaded before initializing
    (function checkDataTables() {
        if (typeof jQuery !== 'undefined' && jQuery.fn.DataTable) {
            jQuery(function ($) {
                $("#enroltable").DataTable({

                  responsive: true,
                  lengthChange: true,
                  autoWidth: false,
                  processing: true,
                  lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "Todos"] ],
                  language: {
        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "datetime": {
        "previous": "Anterior",
        "next": "Proximo",
        "hours": "Horas",
        "minutes": "Minutos",
        "seconds": "Segundos",
        "unknown": "-",
        "amPm": [
          "AM",
          "PM"
        ],
        "months": {
          "0": "Enero",
          "1": "Febrero",
          "2": "Marzo",
          "3": "Abril",
          "4": "Mayo",
          "5": "Junio",
          "6": "Julio",
          "7": "Agosto",
          "8": "Septiembre",
          "9": "Octubre",
          "10": "Noviembre",
          "11": "Diciembre"
        },
        "weekdays": [
          "Dom",
          "Lun",
          "Mar",
          "Mie",
          "Jue",
          "Vie",
          "Sab"
        ]
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
      "loadingRecords": "Cargando...",
      //  url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
      }

    }).buttons().container().prependTo('#exportbuttons');
            });
        } else {
            setTimeout(checkDataTables, 100);
        }
    })();
  });
</script>
<script>
// Wait for Chart.js before initializing time chart
(function checkChartForTime() {
    if (typeof Chart !== 'undefined') {
        initializeTimeChart();
    } else {
        setTimeout(checkChartForTime, 100);
    }
})();

function initializeTimeChart() {
/* Chart.js Charts */
  // Sales chart
  var salesChartCanvas = document.getElementById('time-chart-canvas').getContext('2d')
  // $('#time-chart').get(0).getContext('2d');
  var salesChart2 = new Chart(salesChartCanvas, { 
    type: 'line',
    data: {
      labels: //['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      <?php
                  if (!isset($_GET['courseid'])) { 
                    $get_site_times = adminlte_getdata::get_site_times(); 
                    echo  $get_site_times['course']; 
                  } else { 
                    $get_course_times = adminlte_getdata::get_course_times($_GET['courseid']); 
                    echo $get_course_times['time']; 
                  } ?>, 
      datasets: [
        {
          label: 'Media de Finalización en días',
          backgroundColor: 'rgba(60,141,188,0.9)',
          borderColor: 'rgba(60,141,188,0.8)',
          pointRadius: false,
          pointColor: '#3b8bba',
          pointStrokeColor: 'rgba(60,141,188,1)',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data: <?php 
                  if (!isset($_GET['courseid'])) { 
                    $get_site_times = adminlte_getdata::get_site_times(); 
                    echo  $get_site_times['avgavg']; 
                  } else { 
                    $get_course_times = adminlte_getdata::get_course_times($_GET['courseid']); 
                    echo $get_course_times['avg']; 
                  } ?>
          //data: [28, 48, 40, 19, 86, 27, 90]
        },
        {
          label: 'Finalización en días',
          backgroundColor: 'rgba(210, 214, 222, 1)',
          borderColor: 'rgba(210, 214, 222, 1)',
          pointRadius: false,
          pointColor: 'rgba(210, 214, 222, 1)',
          pointStrokeColor: '#c1c7d1',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data: <?php 
                  if (!isset($_GET['courseid'])) { 
                    echo  $get_site_times['avg']; 
                  } else { 
                    echo $get_course_times['time']; 
                  } ?>, //[15, 19, 10, 11, 16, 5, 4, 5, 9, 10, 11, 6, 5, 4, 8],
          fill: true,
          tension: 0.4
        }
      ]
    },
  options: {
    hoverRadius: 6,
    hoverBackgroundColor: 'yellow',
    maintainAspectRatio: false,
      responsive: true,
      legend: {
        display: false
      },
      scales: {
        x: {
          gridLines: {
            display: false
          }
        },
        y: {
          gridLines: {
            display: false
          }
        }
      }
    }
  })
}
  </script>