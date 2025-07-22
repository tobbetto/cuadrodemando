<?php
global $DB, $CFG;
include_once 'views/getdata/getdata.php';

$year = date('Y');
$isOracle = $DB->get_dbfamily() === 'oracle';
$courseid = $_GET['courseid'] ?? null;

// Get course info if needed
$course_info = null;
if ($courseid) {
    $course_info = $DB->get_record('course', ['id' => $courseid]);
}

// SQL for created courses
if ($isOracle) {
    $sql = "SELECT COUNT(*) FROM {course}
            WHERE to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(timemodified, 'SECOND'), 'YYYY') = '$year'
            AND category <> 261 AND id > 1
            AND to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(startdate, 'SECOND'), 'YYYY') = '$year'";
} else {
    $sql = "SELECT COUNT(*) FROM {course}
            WHERE YEAR(FROM_UNIXTIME(timemodified)) = '$year'
            AND category <> 261 AND id > 1
            AND YEAR(FROM_UNIXTIME(startdate)) = '$year'";
}
$countCreatedCourses = $DB->count_records_sql($sql, null);

// SQL for open courses
if ($isOracle) {
    $sql = "SELECT COUNT(*) FROM {course} WHERE enddate > '" . time() . "' AND visible = 1 AND category <> 261 AND id > 1";
} else {
    $sql = "SELECT COUNT(*) FROM {course} WHERE FROM_UNIXTIME(enddate) > CURDATE()";
}
$countOpenCourses = $DB->count_records_sql($sql, null);

// SQL for finished courses
if ($isOracle) {
    $sql = "SELECT COUNT(*) FROM {course}
            WHERE to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(enddate/60, 'MINUTE'), 'YYYY') >= '$year'
            AND enddate < '" . time() . "'";
} else {
    $sql = "SELECT COUNT(*) FROM {course}
            WHERE YEAR(FROM_UNIXTIME(enddate)) = '$year'
            AND FROM_UNIXTIME(enddate) < CURDATE()";
}
$countFinishedCourses = $DB->count_records_sql($sql, null);

// SQL for average enrolment
$sql = "SELECT round(avg(count)) FROM (
            SELECT COUNT(*) as count
            FROM {course} ic
            JOIN {context} con ON con.instanceid = ic.id
            JOIN {role_assignments} ra ON ra.contextid = con.id AND con.contextlevel = 50
            JOIN {role} r ON ra.roleid = r.id
            JOIN {user} u ON u.id = ra.userid
            WHERE r.id = 5
            GROUP BY ic.id
        ) counts";
$avgCourseEnrolment = $DB->count_records_sql($sql, null);

// Prepare data objects only once
$adminlte_getdata = new adminlte_getdata();
$category_numbers = $adminlte_getdata->get_category_numbers($courseid ?? null);
$courseEnrolment = $adminlte_getdata->get_course_numbers($courseid ?? null);
$course_data = adminlte_getdata::get_yearly_courses($courseid ?? null);

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <?php if ($courseid && $course_info): ?>
            <h1>Detalles del curso: <b><?= htmlspecialchars($course_info->fullname) ?></b> ( <?= htmlspecialchars($course_info->shortname) ?> )</h1>
          <?php else: ?>
            <h1>Vista general de los cursos de <?= $year ?></h1>
          <?php endif ?>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $CFG->wwwroot ?>/adminlte/">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="<?= $CFG->wwwroot ?>/adminlte/courses">Cursos</a></li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row<?= $courseid ? ' d-none' : '' ?>">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?= $countCreatedCourses ?></h3>
            </div>
            <div class="icon">
              <i class="fas fa-calendar-plus"></i>
            </div>
            <p class="small-box-footer">Cursos creados (<?= $year ?>)</p>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?= $countOpenCourses ?></h3>
            </div>
            <div class="icon">
              <i class="fas fa-calendar-check"></i>
            </div>
            <p class="small-box-footer">Cursos activos (<?= $year ?>)</p>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?= $countFinishedCourses ?></h3>
            </div>
            <div class="icon">
              <i class="fas fa-calendar-xmark"></i>
            </div>
            <p class="small-box-footer">Cursos finalizados (<?= $year ?>)</p>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-primary">
            <div class="inner">
              <h3><?= $avgCourseEnrolment ?></h3>
            </div>
            <div class="icon">
              <i class="fas fa-users-line"></i>
            </div>
            <p class="small-box-footer">Media matriculados</p>
          </div>
        </div>
      </div>
      <!-- /.row -->

      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <?= $category_numbers ?>
        <!-- /.Left col -->

        <!-- right col -->
        <?= $courseEnrolment ?>
        <!-- right col -->
      </div>
      <div class="row">
        <!-- Yearly courses -->
        <?= $course_data ?>
      </div>
      <!-- /.row (main row) -->
    </div>
    <!-- ./container fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
  // Make the dashboard widgets sortable Using jquery UI
  $(function () {
    $('.connectedSortable').sortable({
      placeholder: 'sort-highlight',
      connectWith: '.connectedSortable',
      handle: '.card-header, .nav-tabs',
      forcePlaceholderSize: true,
      zIndex: 999999
    });
    $('.connectedSortable .card-header').css('cursor', 'move');
    $('.todo-list').sortable({
      placeholder: 'sort-highlight',
      handle: '.handle',
      forcePlaceholderSize: true,
      zIndex: 999999
    });
  });
</script>
<script>
  $(function () {
    $('.knob').knob({
      draw: function () {
        if (this.$.data('skin') == 'tron') {
          var a = this.angle(this.cv),
              sa = this.startAngle,
              sat = this.startAngle,
              eat = sat + a,
              r = true;
          this.g.lineWidth = this.lineWidth;
          this.o.cursor && (sat = eat - 0.3) && (eat = eat + 0.3);
          if (this.o.displayPrevious) {
            var ea = this.startAngle + this.angle(this.value);
            this.o.cursor && (sa = ea - 0.3) && (ea = ea + 0.3);
            this.g.beginPath();
            this.g.strokeStyle = this.previousColor;
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
            this.g.stroke();
          }
          this.g.beginPath();
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
          this.g.stroke();
          this.g.lineWidth = 2;
          this.g.beginPath();
          this.g.strokeStyle = this.o.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
          this.g.stroke();
          return false;
        }
      }
    });
  });
</script>
<script>
  // PIE CHART
  $(function () {
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
    var pieData = {
      labels: <?= json_encode($adminlte_getdata->pieChartLabel ?? []) ?>,
      datasets: [{
        data: <?= json_encode($adminlte_getdata->pieChartData ?? []) ?>,
        backgroundColor: <?= json_encode($adminlte_getdata->background_color ?? []) ?>
      }]
    };
    var pieOptions = {
      legend: { display: false },
      offset: 1
    };
    new Chart(pieChartCanvas, {
      type: 'doughnut',
      data: pieData,
      options: pieOptions
    });
  });
</script>
<script>
  // STACKED BAR CHART
  $(function () {
    var barChartData = {
      labels: <?php
        if (!$courseid) {
          $get_course_categories = adminlte_getdata::get_category_name_number();
          echo $get_course_categories['name'];
        } else {
          $get_course_categories = adminlte_getdata::get_course_enrolments($courseid);
          echo $get_course_categories['name'];
        }
      ?>,
      datasets: [
        {
          label: 'Finalizados',
          backgroundColor: '#28a745',
          borderColor: '#28a745',
          pointRadius: false,
          pointColor: '#3b8bba',
          pointStrokeColor: 'rgba(60,141,188,1)',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data: <?= $get_course_categories['count']; ?>
        },
        {
          label: 'No finalizados',
          backgroundColor: '#dc3545',
          borderColor: '#dc3545',
          pointRadius: false,
          pointColor: '#dc3545',
          pointStrokeColor: '#c1c7d1',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data: <?= $get_course_categories['students']; ?>
        }
      ]
    };
    var stackedBarChartCanvas = $('#stackedBarChart-canvas').get(0).getContext('2d');
    var stackedBarChartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: { stacked: true },
        y: {
          stacked: true,
          gridLines: { display: false },
          ticks: {
            fontColor: 'black',
            fontSize: 12,
            stepSize: 3,
            beginAtZero: true
          }
        }
      }
    };
    new Chart(stackedBarChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: stackedBarChartOptions
    });
  });
</script>
<script>
  // GEO CHART
  $(function () {
    var mode = 'index', intersect = true;
    var $salesChart = $('#geo-chart-canvas');
    var get_course_categories = <?php
      if (!$courseid) {
        echo 'adminlte_getdata::get_category_name_number();';
      } else {
        echo 'adminlte_getdata::get_course_enrolments(' . json_encode($courseid) . ');';
      }
    ?>;
    var salesChart = new Chart($salesChart, {
      type: 'bar',
      data: {
        labels: <?= $get_course_categories['name']; ?>,
        color: '#000',
        datasets: [{
          label: <?= !$courseid ? "'# de cursos en categoría'" : "'# de provincias de los alumnos'" ?>,
          backgroundColor: '#28a745',
          data: <?= $get_course_categories['count']; ?>
        }]
      },
      options: {
        maintainAspectRatio: false,
        tooltips: { mode: mode, intersect: intersect },
        hover: { mode: mode, intersect: intersect },
        legend: {
          display: false,
          labels: { fontColor: 'blue' }
        },
        scales: {
          y: {
            display: true,
            gridLines: { display: true, lineWidth: '4px', zeroLineColor: 'transparent' },
            ticks: { fontColor: 'black', fontSize: 12, stepSize: 3, beginAtZero: true }
          },
          x: {
            display: true,
            gridLines: { display: false },
            ticks: { fontColor: 'black', fontSize: 12, stepSize: 1, beginAtZero: true }
          }
        }
      }
    });
  });
</script>
<script>
  // DataTable defaults and initialization
  $.extend($.fn.DataTable.defaults, {
    buttons: [
      { extend: 'copyHtml5', text: '<i class="fas fa-copy"></i>', titleAttr: 'Copiar tabla' },
      { extend: 'csvHtml5', text: '<i class="fas fa-file-csv"></i>', titleAttr: 'Exportar CSV' },
      { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i>', titleAttr: 'Exportar Excel' },
      { extend: 'pdfHtml5', orientation: 'landscape', text: '<i class="fas fa-file-pdf"></i>', titleAttr: 'Exportar PDF' },
      { extend: 'pdfHtml5', orientation: 'landscape', text: '<i class="fas fa-print"></i>', download: 'open', titleAttr: 'Imprimir tabla' },
      'colvis'
    ],
    language: {
      buttons: { colvis: 'Filtrar columnas' }
    }
  });
  $(function () {
    $("#enroltable").DataTable({
      responsive: true,
      lengthChange: true,
      autoWidth: false,
      processing: true,
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
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
            "0": "Enero", "1": "Febrero", "2": "Marzo", "3": "Abril", "4": "Mayo", "5": "Junio",
            "6": "Julio", "7": "Agosto", "8": "Septiembre", "9": "Octubre", "10": "Noviembre", "11": "Diciembre"
          },
          "weekdays": ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"]
        },
        "paginate": {
          "first": "Primero", "last": "Último", "next": "Siguiente", "previous": "Anterior"
        },
        "buttons": {
          "copy": "Copiar", "colvis": "Ocultar columnas", "collection": "Colección", "colvisRestore": "Restaurar visibilidad",
          "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br /> <br /> Para cancelar, haga clic en este mensaje o presione escape.",
          "copySuccess": { "1": "Copiada 1 fila al portapapeles", "_": "Copiadas %ds fila al portapapeles" },
          "copyTitle": "Copiar al portapapeles", "csv": "CSV", "excel": "Excel",
          "pageLength": { "-1": "Mostrar todas las filas", "_": "Mostrar %d filas" },
          "pdf": "PDF", "print": "Imprimir", "renameState": "Cambiar nombre", "updateState": "Actualizar",
          "createState": "Crear Estado", "removeAllStates": "Remover Estados", "removeState": "Remover",
          "savedStates": "Estados Guardados", "stateRestore": "Estado %d"
        },
        "searchPanes": {
          "clearMessage": "Borrar todo",
          "collapse": { "0": "Paneles de búsqueda", "_": "Paneles de búsqueda (%d)" },
          "count": "{total}", "countFiltered": "{shown} ({total})", "emptyPanes": "Sin paneles de búsqueda",
          "loadMessage": "Cargando paneles de búsqueda", "title": "Filtros Activos - %d",
          "showMessage": "Mostrar Todo", "collapseMessage": "Colapsar Todo"
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
      }
    }).buttons().container().prependTo('#exportbuttons');
  });
</script>
<script>
  // Chart.js Line Chart
  $(function () {
    var salesChartCanvas = document.getElementById('time-chart-canvas').getContext('2d');
    <?php
      if (!$courseid) {
        $get_site_times = adminlte_getdata::get_site_times();
        $labels = $get_site_times['course'];
        $avgavg = $get_site_times['avgavg'];
        $avg = $get_site_times['avg'];
      } else {
        $get_course_times = adminlte_getdata::get_course_times($courseid);
        $labels = $get_course_times['time'];
        $avgavg = $get_course_times['avg'];
        $avg = $get_course_times['time'];
      }
    ?>
    var salesChart2 = new Chart(salesChartCanvas, {
      type: 'line',
      data: {
        labels: <?= $labels ?>,
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
            data: <?= $avgavg ?>
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
            data: <?= $avg ?>,
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
        legend: { display: false },
        scales: {
          x: { gridLines: { display: false } },
          y: { gridLines: { display: false } }
        }
      }
    });
  });
</script>