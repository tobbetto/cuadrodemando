<?php global $DB, $PAGE; $CFG; 
include_once 'views/getdata/getdata.php'; 
include_once 'views/getdata/total_logins_json.php'; 
include_once 'views/getdata/users_logins_json.php';
include_once 'views/getdata/total_user_changes_json.php';
$maxnumber = [];?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">

          <?php if (isset($_GET['userid'])) : $user_info = $DB->get_record('user', [ 'id' => $_GET['userid'] ]); endif ?>
          <?php if (isset($_GET['userid']) AND $_GET['roleid'] == 5) : ?>
            <h1>Detalles del alumno: <b><?php echo $user_info->firstname . ' ' . $user_info->lastname; ?></b></h1>
          <?php  elseif (isset($_GET['userid']) AND $_GET['roleid'] == 3) : ?>
            <h1>Detalles del docente: <b><?php echo $user_info->firstname . ' ' . $user_info->lastname; ?></b></h1>
          <?php  else : ?>
            <h1>Vista general de los usuarios</h1>
          <?php  endif ?>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo $CFG->wwwroot ?>/adminlte/">Inicio</a></li>
              <li class="breadcrumb-item active"><a href="<?php echo $CFG->wwwroot ?>/adminlte/users">Usuarios</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <?php

    global $DB;

    ?>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">

        <!-- Small boxes (Stat box) -->
        <div class="row" <?php if (isset($_GET['userid'])) : echo 'hidden'; endif ?>>

          <div class="col-lg-3 col-6">

            <!-- small box -->
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">

                <h3><?php $countCreatedUsers = Total_user_changes_json::get_total_user_changes(); echo $countCreatedUsers['created']; ?></h3>

                <p></p>
              </div>
              <div class="icon">
                <i class="fas fa-user-plus"></i>
              </div>
              <p class="small-box-footer" >Altas hoy</p>
            </div>
          </div>
          <!-- ./col -->

          <div class="col-lg-3 col-6">
            <!-- small box -->

            <div class="small-box bg-success">
              <div class="inner">

              <h3><?php $countSuspendedUsers = Total_user_changes_json::get_total_user_changes(); echo $countSuspendedUsers['deleted']; ?></h3>
                <p></p>
              </div>
              <div class="icon">
                <i class="fas fa-user-minus"></i>
              </div>
              <p class="small-box-footer" >Bajas hoy</p>
            </div>

          </div>
          <!-- ./col -->

          <div class="col-lg-3 col-6">
          <!-- small box -->
                   
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php $countConfiguredUsers = Total_user_changes_json::get_total_user_changes(); echo $countConfiguredUsers['edited'] ?></h3>
                <p></p>
              </div>
              <div class="icon">
                <i class="fas fa-user-edit"></i></i>
              </div>
              <p class="small-box-footer" >Usuarios editados hoy</p>
          </div>
              <!-- /.card-body -->
            
          </div>
          <!-- ./col -->

          <?php

            // Construct the SQL query
            $sql_mysql = "SELECT COUNT(id) AS useraccesses FROM {user} WHERE FROM_UNIXTIME(lastaccess, '%d-%m-%Y') = '" . date('d-m-Y') . "'";
            $sql_oracle = "SELECT COUNT(id) AS useraccesses FROM {user} WHERE to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(lastaccess, 'SECOND'), 'DD-MM-YYYY') = '" . date('d-m-Y') . "'";
            $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;
            // Execute the SQL query
            $countUserAccesses = $DB->count_records_sql($sql, null);
            //$avgCourseEnrolment = $DB->execute($sql, $params_array);($sql, null);

            ?>

           <div class="col-lg-3 col-6">

           <div class="small-box bg-primary">

              <div class="inner">
                <h3><?php echo $countUserAccesses ?></h3>
                <p></p>
              </div>
              <div class="icon">
                <i class="fas fa-sign-in-alt"></i>
              </div>
              <p class="small-box-footer" >Accesos hoy</p>
            </div>
            <!-- small box  -->
            
            </div>
            <!-- ./col -->

        </div>
        <!-- /.row -->


      <div class="row"> <!-- Main row -->
        <!-- Left col -->

          <section class="col-lg-6 connectedSortable ui-sortable">
          <!-- BAR CHART 1-->
          <?php if (isset($_GET['userid'])) : ?>
            <?php $user_access = adminlte_getdata::count_user_access($_GET['userid']); ?> 
          <?php else : ?>
            <?php $user_access = adminlte_getdata::count_user_access($id = NULL); ?> 
          <?php endif; ?>
          <?php echo $user_access; ?>
          </section>

          <section class="col-lg-6 connectedSortable ui-sortable">
            <!-- BAR CHART 2-->
            
            <?php if (isset($_GET['userid'])) : ?>
              <?php $userInfo = adminlte_getdata::count_province_user_card($_GET['userid']); ?> 
            <?php else : ?>
              <?php $userInfo = adminlte_getdata::count_province_user_card($id = NULL); ?> 
            <?php endif; ?>
            <?php echo $userInfo; ?>
            
          </section>

      </div> <!-- /.row (main row) -->
      
      <div class="row">
        <!-- Left col -->
        <div class="col-12">
          <section class="col-lg-12 connectedSortable ui-sortable">
            <!-- Default box -->
            
            <?php if (isset($_GET['userid'])) : ?>
              <?php $userData = adminlte_getdata::get_user_table($_GET['userid'], $_GET['roleid']); ?> 
            <?php else : ?>
              <?php $userData = adminlte_getdata::get_user_table($userid = NULL, $roleid = NULL); ?> 
            <?php endif; ?>
              <?php echo $userData; ?>
          </section>
        </div>
      </div>
      <!-- ./container fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<script>

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
</script>


<!-- Page specific script -->
<script>
    var docDefinition = {
  // a string or { width: number, height: number }
  pageSize: 'A4',

  // by default we use portrait, you can change it to landscape if you wish
  pageOrientation: 'landscape',

  };
  </script>

<script>

    //--------------
    //- AREA CHART 1-
    //--------------


    var areaChartData = {
      // labels  : ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
      labels :    <?php if (isset($_GET['userid'])) : ?>
                    <?php $userLogins = Users_logins_json::get_users_logins($_GET['userid']); // var_dump($userLogins); $userLogins = adminlte_getdata::generate_login_count($_GET['userid']); ?> 
                  <?php else : ?>
                    <?php $userLogins = Total_logins_json::get_total_logins(); //$userLogins = adminlte_getdata::generate_login_count($id = NULL); ?> 
                  <?php endif; ?>
                  <?php if (empty($userLogins)) : ?>
                    ["dom.", "lun.", "mar.", "mié.", "jue.", "vie.", "sab."]
                  <?php else : ?>
                    <?php echo $userLogins['dayname']; ?>,
                  <?php endif; ?>
      datasets: [
        {
          label               : '# de accesos',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          borderRadius        :  12,
          borderWidth         :  1,
          borderSkipped       :  true,
          hoverBackgroundColor: 'rgb(131 182 234)',
          maxBarThickness     : 48,
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          //data                : [28, 48, 40, 19, 86, 27, 90]
          data                : <?php if (isset($_GET['userid'])) : ?>
                                  <?php $userLogins = Users_logins_json::get_users_logins($_GET['userid']); // var_dump($userLogins); //$userLogins = adminlte_getdata::generate_login_count($_GET['userid']); ?> 
                                <?php else : ?>
                                  <?php  $userLogins = Total_logins_json::get_total_logins(); //var_dump($userLogins); // $userLogins = adminlte_getdata::generate_login_count($id = NULL);  ?>  
                                <?php endif; ?>
                                <?php if (empty($userLogins)) : ?>
                                  [0, 0, 0, 0, 0, 0, 0]
                                <?php else : ?>
                                  <?php echo $userLogins['logins']; ?>
                                <?php endif; ?>
                                <?php $maxlogins = $userLogins['logins']; ?>,
        },
      ]
    }

    //-------------
    //- BAR CHART 1-
    //-------------
    //var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartCanvas =document.getElementById("barChart").getContext('2d');
    var barChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var highest = Math.max(...<?php echo $maxlogins ?>)
    let sum = highest + 3

    barChartData.datasets[0] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : true,
      scales: {
          y: {
                beginAtZero: true,
                stepSize: 1,
                max: sum
          }
      },
      layout: {
            autoPadding : true
        }  
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })

</script>
<?php if (!isset($_GET['userid'])) : ?>
<script>

    //--------------
    //- AREA CHART 2-
    //--------------

    var areaChartData2 = {
      // labels  : ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
      labels :
                    <?php $provinceUsers = adminlte_getdata::generate_province_user_count(); ?> 
                    <?php echo $provinceUsers['province']; ?>,

      datasets: [
        {
          label               : '# de usuarios',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          borderRadius        :  12,
          borderWidth         :  1,
          borderSkipped       :  true,
          hoverBackgroundColor: 'rgb(131 182 234)',
          maxBarThickness     : 48,
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          //data                : [28, 48, 40, 19, 86, 27, 90]
          data                : 
                                  <?php $userLogins = adminlte_getdata::generate_province_user_count(); ?> 
                                  <?php echo $userLogins['count']; ?>
                                  <?php $maxlogins = $userLogins['count']; ?>,

        },
      ]
    }

    //-------------
    //- BAR CHART 2-
    //-------------
    //var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartCanvas =document.getElementById("barChart2").getContext('2d');
    var barChartData = $.extend(true, {}, areaChartData2)
    var temp0 = areaChartData2.datasets[0]
    var highest = Math.max(...<?php echo $maxlogins ?>)
    let sum2 = highest + 3

    barChartData.datasets[0] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : true,
      scales: {
          y: {
                beginAtZero: true,
                stepSize: 1,
                max: sum2
          }
      },
      layout: {
            autoPadding : true
        }  
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })

</script>
<?php endif; ?>
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
    $("#usertable").DataTable({

      responsive: true,
      lengthChange: true,
      autoWidth: false,
      processing: true,
      lengthMenu: [ [25, 50, 100, -1], [25, 50, 100, "Todos"] ],
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
          "dom",
          "lun",
          "mar",
          "mié",
          "jue",
          "vie",
          "ssab"
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

</script>