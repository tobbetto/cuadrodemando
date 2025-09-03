<?php

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

global $DB, $CFG;
include_once 'views/getdata/getdata.php';
require_once($CFG->dirroot . '/local/cuadrodemando/classes/navbar_helper.php');

echo html_writer::start_div('dashboard-wrapper');

// Generate custom title based on whether we're viewing a specific course
if (isset($_GET['courseid'])) {
    $course_info = $DB->get_record('course', ['id' => $_GET['courseid']]);
    $custom_title = get_string('coursedetails', 'local_cuadrodemando') . ' ' . $course_info->fullname . ' (' . $course_info->shortname . ')';
} else {
    $custom_title = get_string('coursesoverview', 'local_cuadrodemando') . ' ' . date('Y');
}

// Use navbar helper with custom title
echo \local_cuadrodemando\navbar_helper::render_navbar('courses', $custom_title);

// Content Wrapper
echo html_writer::start_div('content-wrapper');

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
echo html_writer::tag('p', get_string('coursescreated', 'local_cuadrodemando', date('Y')), ['class' => 'small-box-footer']);
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
echo html_writer::tag('p', get_string('coursesactive', 'local_cuadrodemando', date('Y')), ['class' => 'small-box-footer']);
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
echo html_writer::tag('p', get_string('coursesfinished', 'local_cuadrodemando', date('Y')), ['class' => 'small-box-footer']);
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
echo html_writer::tag('p', get_string('averageenrollment', 'local_cuadrodemando'), ['class' => 'small-box-footer']);
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
// Fix for RequireJS/AMD conflicts - disable AMD detection temporarily
if (typeof define === 'function' && define.amd) {
    var originalDefine = define;
    define = undefined;
    window.requirejsVars = {
        originalDefine: originalDefine
    };
}
</script>
<script>
// Use a namespace to avoid conflicts and track initialization
window.coursesPageInitialized = window.coursesPageInitialized || false;

(function checkLibraries() {
    if (typeof Chart !== 'undefined' && typeof $ !== 'undefined' && $.fn.sortable) {
        initializeDashboard();
    } else {
        setTimeout(checkLibraries, 100);
    }
})();

function initializeDashboard() {
    // Prevent multiple initialization
    if (window.coursesPageInitialized) {
        return;
    }
    
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
  
  window.coursesPageInitialized = true;
}
</script>
<script>
// Wait for jQuery and jQuery Knob to be loaded before initializing knob elements
window.knobInitialized = window.knobInitialized || false;

(function checkKnob() {
    if (typeof jQuery !== 'undefined' && jQuery.fn.knob && !window.knobInitialized) {
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
            window.knobInitialized = true;
        });
    } else {
        setTimeout(checkKnob, 100);
    }
})();
</script>
<script>
// Wait for Chart.js to be loaded before initializing charts
window.pieChartInitialized = window.pieChartInitialized || false;
window.pieChartInstance = window.pieChartInstance || null;

(function checkChart() {
    if (typeof Chart !== 'undefined' && !window.pieChartInitialized) {
        initializePieChart();
    } else if (typeof Chart === 'undefined') {
        setTimeout(checkChart, 100);
    }
})();

function initializePieChart() {
  //-------------
  // - PIE CHART -
  //-------------
  var pieChartElement = document.getElementById('pieChart');
  if (!pieChartElement) return;
  
  // Destroy existing chart if it exists
  if (window.pieChartInstance) {
    window.pieChartInstance.destroy();
  }
  
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
  window.pieChartInstance = new Chart(pieChartCanvas, {
    type: 'doughnut',
    data: pieData,
    options: pieOptions
  })
  
  window.pieChartInitialized = true;
  //-----------------
  // - END PIE CHART -
  //-----------------
}
</script>
<script>
// Wait for Chart.js before initializing bar chart
window.barChartInitialized = window.barChartInitialized || false;
window.barChartInstance = window.barChartInstance || null;

(function checkChartForBar() {
    if (typeof Chart !== 'undefined' && !window.barChartInitialized) {
        initializeBarChart();
    } else if (typeof Chart === 'undefined') {
        setTimeout(checkChartForBar, 100);
    }
})();

function initializeBarChart() {
    // Destroy existing chart if it exists
    if (window.barChartInstance) {
        window.barChartInstance.destroy();
    }
    
var barChartData = {
      labels  :  <?php 
        if (!isset($_GET['courseid'])) { 
          $get_course_categories = adminlte_getdata::get_category_name_number(); 
          echo  !empty($get_course_categories['name']) ? $get_course_categories['name'] : '[]'; 
        } else { 
          $get_course_categories = adminlte_getdata::get_course_enrolments($_GET['courseid']); 
          echo  !empty($get_course_categories['name']) ? $get_course_categories['name'] : '[]'; 
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
          data                : <?php echo !empty($get_course_categories['count']) ? $get_course_categories['count'] : '[0]'; ?> //[28, 48, 40, 19, 86, 27, 90]
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
          data                : <?php echo !empty($get_course_categories['students']) ? $get_course_categories['students'] : '[0]'; ?> //[65, 59, 80, 81, 56, 55, 40]
        }
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
 
    window.barChartInstance = new Chart(stackedBarChartCanvas, {
      type: 'bar',
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
    
    window.barChartInitialized = true;
}
  </script>

<script>
// Wait for Chart.js before initializing geo chart
window.geoChartInitialized = window.geoChartInitialized || false;
window.geoChartInstance = window.geoChartInstance || null;

(function checkChartForGeo() {
    if (typeof Chart !== 'undefined' && !window.geoChartInitialized) {
        initializeGeoChart();
    } else if (typeof Chart === 'undefined') {
        setTimeout(checkChartForGeo, 100);
    }
})();

function initializeGeoChart() {
  'use strict'
  
  // Destroy existing chart if it exists
  if (window.geoChartInstance) {
    window.geoChartInstance.destroy();
  }

  var ticksStyle = {
    fontColor: '#FFFFFF',
    //fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true

  var salesChartElement = document.getElementById('geo-chart-canvas');
  if (!salesChartElement) return;

  window.geoChartInstance = new Chart(salesChartElement, {
    type: 'bar',
    data: {
      labels: <?php 
        if (!isset($_GET['courseid'])) { 
          $get_course_categories = adminlte_getdata::get_category_name_number(); 
          echo  !empty($get_course_categories['name']) ? $get_course_categories['name'] : '[]'; 
        } else { 
          $get_course_categories = adminlte_getdata::get_course_enrolments($_GET['courseid']); 
          echo  !empty($get_course_categories['name']) ? $get_course_categories['name'] : '[]'; 
        } ?>, //['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
      color: '#000',
      datasets: [
        {
          label: <?php
          if (!isset($_GET['courseid'])) { 
            echo "'" . get_string('numbercoursesincategory', 'local_cuadrodemando') . "'" ;
          } else {
            echo "'" . get_string('numberstudentprovinces', 'local_cuadrodemando') . "'" ; 
          } ?>,
          backgroundColor: '#28a745',
          data: <?php echo !empty($get_course_categories['count']) ? $get_course_categories['count'] : '[0]'; ?> //[1000, 2000, 3000, 2500, 2700, 2500, 3000]
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
  
  window.geoChartInitialized = true;
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
  $(function () {
    // Wait for DataTables to be loaded before initializing
    window.dataTableInitialized = window.dataTableInitialized || false;
    
    (function checkDataTables() {
        if (typeof jQuery !== 'undefined' && jQuery.fn.DataTable && !window.dataTableInitialized) {
            jQuery(function ($) {
                // Set DataTable defaults only after DataTable is loaded
                $.extend($.fn.DataTable.defaults, {
                  buttons: [
                            {
                                extend:    'copyHtml5',
                                text:      '<i class="fas fa-copy"></i>',
                                titleAttr: <?php get_string('copytable', 'local_cuadrodemando') ?>
                            },
                            {
                                extend:    'csvHtml5',
                                text:      '<i class="fas fa-file-csv"></i>',
                                titleAttr: <?php get_string('exportcsv', 'local_cuadrodemando') ?>
                            },
                            {
                                extend:    'excelHtml5',
                                text:      '<i class="fas fa-file-excel"></i>',
                                titleAttr: <?php get_string('exportexcel', 'local_cuadrodemando') ?>
                            },
                            {
                              extend: 'pdfHtml5',
                              orientation: 'landscape',
                              text: '<i class="fas fa-file-pdf"></i>',
                              titleAttr: <?php get_string('exportpdf', 'local_cuadrodemando') ?>
                            },
                            {
                              extend: 'pdfHtml5',
                              orientation: 'landscape',
                              text: '<i class="fas fa-print"></i>',
                              download: 'open',
                              titleAttr: <?php get_string('printtable', 'local_cuadrodemando') ?>
                            },
                            'colvis'
                          ],
                          language: {
                            //url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
                            buttons: {
                                colvis: <?php get_string('filtercolumns', 'local_cuadrodemando') ?>
                            }
                        }
                });
                
                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#enroltable')) {
                    $('#enroltable').DataTable().destroy();
                }
                
                $("#enroltable").DataTable({

                  responsive: true,
                  lengthChange: true,
                  autoWidth: false,
                  processing: true,
                  lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, get_string('all', 'local_cuadrodemando')] ],
                  language: {
        "info": <?php get_string('showingrecords', 'local_cuadrodemando') ?>,
        "datetime": {
        "previous": <?php get_string('previous', 'local_cuadrodemando') ?>,
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
          "0": <?php get_string('january', 'local_cuadrodemando') ?>,
          "1": <?php get_string('february', 'local_cuadrodemando') ?>,
          "2": <?php get_string('march', 'local_cuadrodemando') ?>,
          "3": <?php get_string('april', 'local_cuadrodemando') ?>,
          "4": <?php get_string('may', 'local_cuadrodemando') ?>,
          "5": <?php get_string('june', 'local_cuadrodemando') ?>,
          "6": <?php get_string('july', 'local_cuadrodemando') ?>,
          "7": <?php get_string('august', 'local_cuadrodemando') ?>,
          "8": <?php get_string('september', 'local_cuadrodemando') ?>,
          "9": <?php get_string('october', 'local_cuadrodemando') ?>,
          "10": <?php get_string('november', 'local_cuadrodemando') ?>,
          "11": <?php get_string('december', 'local_cuadrodemando') ?>
        },
        "weekdays": [
          <?php get_string('sunday', 'local_cuadrodemando') ?>,
          <?php get_string('monday', 'local_cuadrodemando') ?>,
          <?php get_string('tuesday', 'local_cuadrodemando') ?>,
          <?php get_string('wednesday', 'local_cuadrodemando') ?>,
          <?php get_string('thursday', 'local_cuadrodemando') ?>,
          <?php get_string('friday', 'local_cuadrodemando') ?>,
          <?php get_string('saturday', 'local_cuadrodemando') ?>
        ]
      },
      "paginate": {
        "first": <?php get_string('first', 'local_cuadrodemando') ?>,
        "last": <?php get_string('last', 'local_cuadrodemando') ?>,
        "next": <?php get_string('next', 'local_cuadrodemando') ?>,
        "previous": <?php get_string('previous', 'local_cuadrodemando') ?>
      },
      "buttons": {
        "copy": <?php get_string('copy', 'local_cuadrodemando') ?>,
        "colvis": <?php get_string('hidecolumns', 'local_cuadrodemando') ?>,
        "collection": <?php get_string('collection', 'local_cuadrodemando') ?>,
        "colvisRestore": <?php get_string('restorevisibility', 'local_cuadrodemando') ?>,
        "copyKeys": <?php get_string('copykeys', 'local_cuadrodemando') ?>,
        "copySuccess": {
          "1": <?php get_string('copyrow', 'local_cuadrodemando') ?>,
          "_": <?php get_string('copyrows', 'local_cuadrodemando') ?>
        },
        "copyTitle": <?php get_string('copytitle', 'local_cuadrodemando') ?>,
        "csv": "CSV",
        "excel": "Excel",
        "pageLength": {
          "-1": <?php get_string('showallrows', 'local_cuadrodemando') ?>,
          "_": <?php get_string('showrows', 'local_cuadrodemando') ?>
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
        "clearMessage": <?php get_string('clearmessage', 'local_cuadrodemando') ?>,
        "collapse": {
          "0": <?php get_string('searchpanes', 'local_cuadrodemando') ?>,
          "_": <?php get_string('searchpanesplural', 'local_cuadrodemando') ?>
        },
        "count": "{total}",
        "countFiltered": "{shown} ({total})",
        "emptyPanes": <?php get_string('emptypanes', 'local_cuadrodemando') ?>,
        "loadMessage": <?php get_string('loadmessage', 'local_cuadrodemando') ?>,
        "title": <?php get_string('title', 'local_cuadrodemando') ?>,
        "showMessage": <?php get_string('showmessage', 'local_cuadrodemando') ?>,
        "collapseMessage": <?php get_string('collapsemessage', 'local_cuadrodemando') ?>,
      },
      "processing": <?php get_string('processing', 'local_cuadrodemando') ?>,
      "lengthMenu": <?php get_string('lengthmenu', 'local_cuadrodemando') ?>,
      "zeroRecords": <?php get_string('zerorecords', 'local_cuadrodemando') ?>,
      "emptyTable": <?php get_string('emptytable', 'local_cuadrodemando') ?>,
      "infoEmpty": <?php get_string('infoempty', 'local_cuadrodemando') ?>,
      "infoFiltered": <?php get_string('infofiltered', 'local_cuadrodemando') ?>,
      "search": <?php get_string('search', 'local_cuadrodemando') ?>,
      "infoThousands": ",",
      "loadingRecords": <?php get_string('loadingrecords', 'local_cuadrodemando') ?>,
      //  url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
      }

    }).buttons().container().prependTo('#exportbuttons');
                
                window.dataTableInitialized = true;
            });
        } else if (typeof jQuery === 'undefined' || !jQuery.fn.DataTable) {
            setTimeout(checkDataTables, 100);
        }
    })();
  });
</script>
<script>
// Wait for Chart.js before initializing time chart
window.timeChartInitialized = window.timeChartInitialized || false;
window.timeChartInstance = window.timeChartInstance || null;

(function checkChartForTime() {
    if (typeof Chart !== 'undefined' && !window.timeChartInitialized) {
        initializeTimeChart();
    } else if (typeof Chart === 'undefined') {
        setTimeout(checkChartForTime, 100);
    }
})();

function initializeTimeChart() {
  // Destroy existing chart if it exists
  if (window.timeChartInstance) {
    window.timeChartInstance.destroy();
  }
/* Chart.js Charts */
  // Sales chart
  var salesChartCanvas = document.getElementById('time-chart-canvas').getContext('2d')
  // $('#time-chart').get(0).getContext('2d');
  window.timeChartInstance = new Chart(salesChartCanvas, { 
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
          label: <?php get_string('averagecompletionindays', 'local_cuadrodemando') ?>,
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
          label: <?php get_string('completionindays', 'local_cuadrodemando') ?>,
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
  
  window.timeChartInitialized = true;
}
  </script>