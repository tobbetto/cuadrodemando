<?php 
require_once 'data/user_provincia_table.php'; 
require_once 'data/province_activity_table.php'; 
include 'views/getdata/getdata.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Geografia</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/moodle/adminlte/">Inicio</a></li>
              <li class="breadcrumb-item active"><a href="/moodle/adminlte/geo">Geografia</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <div class="instruccion">
              <div class="body-instruccion">
                  Pasa el cursor sobre cada provincia para ver sus datos y pulsa para más detalles
              </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-10">     
          <div id="mapa-cont"></div>
        </div>
        <div class="col-2">     
          <div><?php $calendar_info = adminlte_getdata::get_map_knobs(); echo $calendar_info?></div>
        </div>
      </div>
            <!-- Modal -->
            <div class="modal" id="modalDatosProvincia" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"></h4>
                            <h4 class="modal-subtitle" style="line-height: 1.5;">: Los datos de la provincia durante los últimos 30 días</h4>
                            <button type="button" class="close" data-bs-toggle="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                        <div class="row">

                          <div class="col-md-4 col-sm-2 col-2  connectedSortable">
                            <div class="info-box shadow connectedSortable">
                              <span class="info-box-icon bg-success"><i class="fa-solid fa-right-to-bracket"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Sesiones abiertas última hora</span>
                                <span class="info-box-number" id='datos-provincia'><?php echo '<p class="sessions dato" style="font-size: 1rem"></p></span>'; ?></span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->

                            <div class="info-box shadow connectedSortable">
                              <span class="info-box-icon bg-success"><i class="fa-solid fa-user-clock"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Usuarios activos última hora</span>
                                <span class="info-box-number" id='datos-provincia'><?php echo '<p class="views dato" style="font-size: 1rem"></p></span>'; ?></span>
                              </div>

                          </div></div>
                          <!-- /.col -->

                          <div class="col-md-4 col-sm-6 col-6  connectedSortable">
                            <div class="info-box shadow connectedSortable">
                              <span class="info-box-icon bg-success"><i class="fa-solid fa-award"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Finalizaciones el último mes</span>
                                <span class="info-box-number" id='datos-provincia'><?php echo '<p class="graduates dato" style="font-size: 1rem"></p></span>'; ?></span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->

                            <div class="info-box shadow connectedSortable">
                              <span class="info-box-icon bg-success"><i class="fa-solid fa-user-graduate"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Matriculaciones el último mes</span>
                                <span class="info-box-number" id='datos-provincia'><?php echo '<p class="enrolments dato" style="font-size: 1rem"></p></span>'; ?></span>
                              </div>

                          </div></div>
                          <!-- /.col -->

                          <div class="col-md-4 col-sm-6 col-6  connectedSortable">
                            <div class="info-box shadow connectedSortable">
                              <span class="info-box-icon bg-success"><i class="fa-solid fa-user-plus"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Altas el último mes</span>
                                <span class="info-box-number" id='datos-provincia'><?php echo '<p class="registrations dato" style="font-size: 1rem"></p></span>'; ?></span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->

                            <div class="info-box shadow connectedSortable">
                              <span class="info-box-icon bg-danger"><i class="fa-solid fa-user-minus"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Bajas en provincia último mes:</span>
                                <span class="info-box-number" id='datos-provincia'><?php echo '<p class="deletes dato" style="font-size: 1rem"></p></span>'; ?></span>
                              </div>

                          </div></div>
                          <!-- /.col -->

                        </div> <!--  row -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden" id="tooltip-provincia"><div style="font-size: x-large"><span class="badge badge-secondary text-justify font-weight-normal p-3" style="line-height: 120%"></span></div></div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

    <?php $geoDatas = User_provincia_table::getprovinciainfo();?>
    <?php $provinceDatas = Activity_province_table::getprovinceactivity(); 
    $activity_data = array_merge_recursive($geoDatas, $provinceDatas); 
    //var_dump( (array) $activity_data); //array_merge($geoDatas, $provinceDatas ); ?>

<script>
/*LLAMA A LA FUNCIÓN QUE CARGA EL MAPA EN SU CONTEBNEDOR*/
$(document).ready(function(){
  var geoData = <?php echo json_encode($geoDatas) ?>;
  var provinceData = <?php echo json_encode($provinceDatas); ?>;

    $('#mapa-cont').cargarMapa(geoData, provinceData);
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

});
</script>
<script>
  $(function () {
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
  })
</script>
