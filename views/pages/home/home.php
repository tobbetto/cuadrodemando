<?php global $DB, $CFG; 
include_once 'views/getdata/getdata.php'; 
include_once 'views/getdata/monthly_numbers_json.php';
include_once 'views/getdata/total_hourly_views_json.php';
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Inicio</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active"><a href="<?php echo $CFG->wwwroot ?>/adminlte/">Inicio</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

<!-- Main content -->
    <section class="content">

    <div class="container-fluid">

        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">

              <?php

                  // Construct the SQL query
                  $sql = "SELECT COUNT(*) FROM {course} WHERE visible = 1 and id > 1";

                  // Execute the SQL query
                  $courseCount = $DB->count_records_sql($sql, null);

                  ?>

                <h3><?php echo $courseCount ?></h3>

                <p></p>
                </div>
                <div class="icon">
                  <i class="fas fa-book-open"></i>
                </div>
              <p class="small-box-footer" >Cursos visibles</p>
            </div>
          </div>
          <!-- ./col -->

          <?php

            // Construct the SQL query
            $sql_mysql = "SELECT COUNT(*) 
            FROM {user_enrolments} 
            WHERE status = 0 
            AND DATE_FORMAT(FROM_UNIXTIME(timestart), '%Y') = {date('Y')}";

            $sql_oracle = "SELECT COUNT(*) 
                    FROM {user_enrolments} 
                    WHERE status = 0 
                    AND to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(timestart, 'SECOND'), 'YYYY') = '" . date('Y') . "'";

            $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

            // Execute the SQL query
            $enrolCount = $DB->count_records_sql($sql, null);

            ?>

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $enrolCount ?></h3>
                <p></p>
                </div>
                <div class="icon">
                  <i class="fas fa-user-graduate"></i>
                </div>
                <p class="small-box-footer" >Matriculaciones activas (<?php echo date('Y'); ?>)</p>
            </div>
          </div>
          <!-- ./col -->

          <?php

            // Construct the SQL query
            $sql = "SELECT COUNT(*) FROM {user} WHERE deleted = 0 AND suspended = 0 AND length(email) > 1 AND length(firstname) > 2 AND length(lastname) > 2  AND NOT regexp_like(firstname, '[0-9]') AND NOT regexp_like(username, '[#]') AND NOT regexp_like(lastname, 'Buzón') AND NOT regexp_like(firstname, 'Buzón')";

            // Execute the SQL query
            $userCount = $DB->count_records_sql($sql, null);

            ?>

          <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?php echo $userCount ?></h3>
              <p></p>
              </div>
              <div class="icon">
              <i class="fas fa-user-plus"></i>
              </div>
              <p class="small-box-footer" >Usuarios registrados</p>
          </div>
          </div>
          <!-- ./col -->

            <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
              <h3><?php if (isset($_GET['month'])) : ?>
                  <?php $completion_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['totalaccess']; echo $completion_info;?>
                <?php else : ?>
                  <?php $completion_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['totalaccess']; echo $completion_info;?>
                <?php endif; ?></h3>
                <p> </p>
              </div>
              <div class="icon">
              <!-- <i class="ion ion-pie-graph"></i> -->
              <i class="fas fa-fingerprint"></i>
              </div>
              <p class="small-box-footer" >Accesos únicos (<?php echo date('Y'); ?>) <br /></p>
            </div>
            </div>
            <!-- ./col -->

        </div>
        <!-- /.row -->


          <?php if (isset($_GET['month'])) : ?>
            <?php $calendar_info = adminlte_getdata::get_month_section($_GET['month'], $_GET['year']); ?>
          <?php else : ?>
            <?php $calendar_info = adminlte_getdata::get_month_section(date('m', time()), date('Y', time())); ?>
          <?php endif; ?>

          <?php echo $calendar_info; ?> 

        <!-- second row -->
        <div class="row">

              <div class="col-md-3 col-sm-6 col-6">
                <div class="info-box shadow-sm" style="min-height: 106.5px">
                  <span class="info-box-icon bg-success"><i class="fas fa-solid fa-right-to-bracket"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Sesiones abiertas ahora:</span>
                    <?php $sql = "SELECT count(userid) AS userid FROM {sessions} WHERE userid > 1"; $sessions = $DB->get_record_sql($sql); ?>
                    <span class="info-box-number"><?php if (!empty($sessions)) { echo $sessions->userid; } else { echo 'No hay sesiones abiertas'; } ?></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
              
                <?php if (isset($_GET['month'])) : ?>
                  <?php $completion_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['completions'];?>
                <?php else : ?>
                  <?php $completion_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['completions'];?>
                <?php endif; ?>

              <div class="col-md-3 col-sm-6 col-6">
                <div class="info-box shadow-sm" style="min-height: 106.5px">
                  <span class="info-box-icon <?php if (!empty($completion_info)) { echo 'bg-success'; } else { echo 'bg-danger'; } ?>"><i class="fas fa-solid fa-award"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Finalizaciones este mes:</span>
                    <span class="info-box-number">
                      <?php if (!empty($completion_info)) {
                          echo $completion_info; 
                        } else {
                          echo 'No hay finalizaciones este mes 😭';
                        }?>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->

              <?php if (isset($_GET['month'])) : ?>
                  <?php $registration_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['registrations']; ?>
                <?php else : ?>
                  <?php $registration_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['registrations']; ?>
                <?php endif; ?>

              <div class="col-md-3 col-sm-6 col-6">
                <div class="info-box shadow-sm" style="min-height: 106.5px">
                  <span class="info-box-icon <?php if (!empty($registration_info)) { echo 'bg-success'; } else { echo 'bg-danger'; } ?>"><i class="fas fa-solid fa-user-plus"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Altas este mes:</span>
                    <span class="info-box-number">
                      <?php if (!empty($registration_info)) {
                          echo $registration_info; 
                        } else {
                          echo 'No hay altas este mes 😭';
                        }?>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->

              <?php if (isset($_GET['month'])) : ?>
                  <?php $access_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['accesses']; ?>
                <?php else : ?>
                  <?php $access_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['accesses']; ?>
                <?php endif; ?>

              <div class="col-md-3 col-sm-6 col-6">
                <div class="info-box shadow-sm" style="min-height: 106.5px">
                  <span class="info-box-icon <?php if (!empty($access_info)) { echo 'bg-success'; } else { echo 'bg-danger'; } ?>"><i class="fas fa-solid fa-key"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Accesos este mes:</span>
                    <span class="info-box-number">
                      <?php if (!empty($access_info)) {
                          echo $access_info; 
                        } else {
                          echo 'No hay accesos este mes 😭';
                        }?>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->

        </div>
        <!-- /.second row -->

        <!-- second row -->
        <div class="row">

              <div class="col-md-3 col-sm-12 col-12">
                <div class="info-box shadow-sm" style="min-height: 106.5px">
                  <span class="info-box-icon bg-success"><i class="fas fa-solid fa-user-clock"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Usuarios activos última hora:</span>
                    <?php $views_info = Total_views_json::get_total_hourly_views(); ?>
                    <span class="info-box-number">
                      <?php if (!empty($views_info)) {
                          echo $views_info; 
                        } else {
                          echo 'No hay usuarios activos 😭';
                        }?>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
              
                <?php if (isset($_GET['month'])) : ?>
                  <?php $enrolment_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['enrolments']; ?>
                <?php else : ?>
                  <?php $enrolment_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['enrolments']; ?>
                <?php endif; ?>

              <div class="col-md-3 col-sm-6 col-6">
                <div class="info-box shadow-sm" style="min-height: 106.5px">
                  <span class="info-box-icon <?php if (!empty($enrolment_info)) { echo 'bg-success'; } else { echo 'bg-danger'; } ?>"><i class="fas fa-solid fa-user-graduate"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Matriculaciones este mes:</span>
                    <span class="info-box-number">
                      <?php if (!empty($enrolment_info)) {
                          echo $enrolment_info; 
                        } else {
                          echo 'No hay matriculaciones este mes 😭';
                        }?>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->

              <?php if (isset($_GET['month'])) : ?>
                  <?php $suspension_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['suspensions']; ?>
                <?php else : ?>
                  <?php $suspension_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['suspensions']; ?>
                <?php endif; ?>

              <div class="col-md-3 col-sm-6 col-6">
                <div class="info-box shadow-sm" style="min-height: 106.5px">
                  <span class="info-box-icon <?php if (!empty($suspension_info)) { echo 'bg-danger'; } else { echo 'bg-success'; } ?>"><i class="fas fa-solid fa-user-minus"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Bajas este mes:</span>
                    <span class="info-box-number">
                      <?php if (!empty($suspension_info)) {
                          echo $suspension_info; 
                        } else {
                          echo 'No hay bajas este mes 😀';
                        }?>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->

              <?php if (isset($_GET['month'])) : ?>
                  <?php $message_info = Monthly_numbers_json::get_month_numbers()[$_GET['month']][$_GET['year']]['messages']; ?>
                <?php else : ?>
                  <?php $message_info = Monthly_numbers_json::get_month_numbers()[date('m', time())][date('Y', time())]['messages']; ?>
                <?php endif; ?>

              <div class="col-md-3 col-sm-6 col-6">
                <div class="info-box shadow-sm" style="min-height: 106.5px">
                  <span class="info-box-icon <?php if (!empty($message_info)) { echo 'bg-success'; } else { echo 'bg-danger'; } ?>"><i class="fas fa-solid fa-envelopes-bulk"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Mensajes este mes:</span>
                    <span class="info-box-number">
                      <?php if (!empty($message_info)) {
                          echo $message_info; 
                        } else {
                          echo 'No hay mensajes este mes 😭';
                        }?>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->

        </div>
        <!-- /.second row -->

        <!-- third row -->
        <div class="row align-items-center"">
          <!-- calendar -->
          <section class="col-lg-12 connectedSortable">
            <!-- Calendar card -->
            <div class="card bg-gradient-muted card-indigo card-outline" data-toggle="tooltip"  data-placement="center">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-calendar--alt mr-1"></i>
                  Calendario
                </h3>
                <!-- card tools -->
                <div class="card-tools">
                  <button type="button" class="btn btn-indigo btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <div class="card-body pt-0">
                 <iframe
                    width="100%"
                    height="538px"
                    style="border:0;" 
                    <?php if (isset($_GET['month'])) : ?>
                      <?php $calendarmonth = strtotime(date('01-' . $_GET['month'] . '-' . $_GET['year'])); ?>
                    <?php else : ?>
                      <?php $calendarmonth = time(); ?>
                    <?php endif; ?>
                    src="<?php echo $CFG->wwwroot ?>/calendar/view.php?view=month&time=<?php echo $calendarmonth ?>&layout=embedded" >
                  </iframe>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->
          </section>
          <!-- /.calendar -->
</div>
        

    </div>
    <!-- /.container-fluid -->

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

<script>
$(function () {
    /*
     * Flot Interactive Chart
     * -----------------------
     */
    // We use an inline data source in the example, usually data would
    // be fetched from a server
    for (var i = 0; i < 60; ++i) {
        <?php $data[] = ''; $sql = "SELECT count(userid) AS userid FROM {sessions} WHERE userid != 0"; $sessions = $DB->get_record_sql($sql); ?>
        <?php $data[] .= $sessions->userid ?>
      }
    var data        = <?php echo json_encode($data) ?>,
        totalPoints = 60

    function getRandomData() {

      if (data.length > 0) {
        data = data.slice(1)
      }

      // Do a random walk
      while (data.length < totalPoints) {
        <?php $sql = "SELECT count(userid) AS userid FROM {sessions} WHERE userid != 0"; $sessions = $DB->get_record_sql($sql); ?>
        var prev = data.length > 0 ? data[data.length - 1] : 5,
            y    =  <?php echo $sessions->userid ?> 
            //y    = <?php echo $sessions->userid ?> 

        if (y < 0) {
          y = 0
        } else if (y > 5) {
          y = 5
        }

        data.push(y)
      }

      // Zip the generated y values with the x values
      var res = []
      for (var i = 0; i < data.length; ++i) {
        res.push([i, data[i]])
      }

      return res
    }

    var interactive_plot = $.plot('#interactive', [
        {
          data: getRandomData(),
        }
      ],
      {
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
      }
    )

    var updateInterval = 500 //Fetch data ever x milliseconds
    var realtime       = 'on' //If == to on then fetch data every x seconds. else stop fetching
    function update() {

      interactive_plot.setData([getRandomData()])

      // Since the axes don't change, we don't need to call plot.setupGrid()
      interactive_plot.draw()
      if (realtime === 'on') {
        setTimeout(update, updateInterval)
      }
    }

    //INITIALIZE REALTIME DATA FETCHING
    if (realtime === 'on') {
      update()
    }
    //REALTIME TOGGLE
    $('#realtime .btn').click(function () {
      if ($(this).data('toggle') === 'on') {
        realtime = 'on'
      }
      else {
        realtime = 'off'
      }
      update()
    })
    /*
     * END INTERACTIVE CHART
     */
})
</script>