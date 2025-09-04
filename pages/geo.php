<?php
/**
 * Geography page for the cuadrodemando plugin
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.1.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Load essential assets
echo '<link rel="stylesheet" type="text/bundle" href="/local/cuadrodemando/thirdpartylibs/fonts-googleapi/fonts.googleapi.css">';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/fontawesome/css/all.min.css">';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/adminlte/adminlte.min.css">';
echo '<link rel="stylesheet" href="/local/cuadrodemando/assets/scripts/map/estilos.css"/>';

echo '<script src="/local/cuadrodemando/assets/scripts/jquery/jquery.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/fontawesome/js/all.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/adminlte/adminlte.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/jquery-knob/jquery.knob.min.js"></script>';
echo '<script src="/local/cuadrodemando/assets/scripts/map/mapa.js"></script>';

global $OUTPUT, $CFG, $DB;

// Include necessary classes with error handling
try {
    if (file_exists($CFG->dirroot . '/local/cuadrodemando/classes/navbar_helper.php')) {
        require_once($CFG->dirroot . '/local/cuadrodemando/classes/navbar_helper.php');
    } else {
        echo '<div>navbar_helper.php not found</div>';
        die();
    }
} catch (Exception $e) {
    echo '<div>Error loading navbar_helper: ' . $e->getMessage() . '</div>';
    die();
}

// Use navbar helper
echo '<div class="dashboard-wrapper">';
echo \local_cuadrodemando\navbar_helper::render_navbar('geo');

// Add content wrapper
echo '<div class="content-wrapper">';
echo '<section class="content">';
echo '<div class="container-fluid">';

// Instructions
echo '<div class="row mb-2">';
echo '<div class="col-sm-12">';
echo '<div class="instruccion">';
echo '<div class="body-instruccion">';
echo 'Pasa el cursor sobre cada provincia para ver sus datos y pulsa para más detalles';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';

// Map and data section
echo '<div class="row">';
echo '<div class="col-10">';
echo '<div id="mapa-cont"></div>';
echo '</div>';
echo '<div class="col-2">';
try {
    // Try to load getdata for map knobs
    if (file_exists($CFG->dirroot . '/local/cuadrodemando/views/getdata/getdata.php')) {
        include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/getdata.php');
        
        if (class_exists('adminlte_getdata') && method_exists('adminlte_getdata', 'get_map_knobs')) {
            $calendar_info = adminlte_getdata::get_map_knobs();
            echo $calendar_info;
        } else {
            echo '<div class="info-box shadow">';
            echo '<span class="info-box-icon bg-info"><i class="fa-solid fa-map"></i></span>';
            echo '<div class="info-box-content">';
            echo '<span class="info-box-text">Total Provinces</span>';
            echo '<span class="info-box-number">52</span>';
            echo '</div></div>';
        }
    } else {
        echo '<div class="alert alert-info">Map statistics loading...</div>';
    }
} catch (Exception $e) {
    echo '<div class="alert alert-warning">Map statistics unavailable</div>';
}
echo '</div>';
echo '</div>';

echo '</div>'; // container-fluid
echo '</section>'; // content
echo '</div>'; // content-wrapper

echo '</div>'; // dashboard-wrapper

// Load data classes and get geographical data
$geoDatas = [];
$provinceDatas = [];

try {
    echo '<script>console.log("Current file location:", "' . addslashes(__FILE__) . '");</script>';
    echo '<script>console.log("Parent directory:", "' . addslashes(dirname(__DIR__)) . '");</script>';
    
    $user_provincia_file = dirname(__DIR__) . '/views/pages/geo/data/user_provincia_table.php';
    echo '<script>console.log("Looking for user_provincia_table at:", "' . addslashes($user_provincia_file) . '");</script>';
    echo '<script>console.log("File exists check:", ' . (file_exists($user_provincia_file) ? 'true' : 'false') . ');</script>';
    
    if (file_exists($user_provincia_file)) {
        require_once($user_provincia_file);
        echo '<script>console.log("User provincia table file included successfully");</script>';
        
        if (class_exists('User_provincia_table')) {
            echo '<script>console.log("User_provincia_table class exists");</script>';
            if (method_exists('User_provincia_table', 'getprovinciainfo')) {
                echo '<script>console.log("getprovinciainfo method exists");</script>';
                $geoDatas = User_provincia_table::getprovinciainfo();
                echo '<script>console.log("Geo data class loaded, data count:", ' . (is_array($geoDatas) ? count($geoDatas) : 0) . ');</script>';
                echo '<script>console.log("Geo data type:", "' . gettype($geoDatas) . '");</script>';
            } else {
                echo '<script>console.error("getprovinciainfo method does not exist");</script>';
            }
        } else {
            echo '<script>console.error("User_provincia_table class does not exist");</script>';
        }
    } else {
        echo '<script>console.warn("user_provincia_table.php not found at: ' . addslashes($user_provincia_file) . '");</script>';
    }
} catch (Exception $e) {
    echo '<script>console.error("Error loading geographical data: ' . addslashes($e->getMessage()) . '");</script>';
} catch (Error $e) {
    echo '<script>console.error("PHP Error loading geographical data: ' . addslashes($e->getMessage()) . '");</script>';
}

try {
    $province_activity_file = dirname(__DIR__) . '/views/pages/geo/data/province_activity_table.php';
    if (file_exists($province_activity_file)) {
        require_once($province_activity_file);
        echo '<script>console.log("Province activity table file included successfully");</script>';
        
        if (class_exists('Activity_province_table')) {
            echo '<script>console.log("Activity_province_table class exists");</script>';
            if (method_exists('Activity_province_table', 'getprovinceactivity')) {
                echo '<script>console.log("getprovinceactivity method exists");</script>';
                $provinceDatas = Activity_province_table::getprovinceactivity();
                echo '<script>console.log("Province data class loaded, data count:", ' . (is_array($provinceDatas) ? count($provinceDatas) : 0) . ');</script>';
                echo '<script>console.log("Province data type:", "' . gettype($provinceDatas) . '");</script>';
            } else {
                echo '<script>console.error("getprovinceactivity method does not exist");</script>';
            }
        } else {
            echo '<script>console.error("Activity_province_table class does not exist");</script>';
        }
    } else {
        echo '<script>console.warn("province_activity_table.php not found at: ' . addslashes($province_activity_file) . '");</script>';
    }
} catch (Exception $e) {
    echo '<script>console.error("Error loading province activity data: ' . addslashes($e->getMessage()) . '");</script>';
} catch (Error $e) {
    echo '<script>console.error("PHP Error loading province activity data: ' . addslashes($e->getMessage()) . '");</script>';
}

?>

<!-- Modal for province data -->
<div class="modal" id="modalDatosProvincia" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <h4 class="modal-subtitle" style="line-height: 1.5;">: Los datos de la provincia durante los últimos 30 días</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-6 connectedSortable">
                        <div class="info-box shadow connectedSortable">
                            <span class="info-box-icon bg-success"><i class="fa-solid fa-right-to-bracket"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Sesiones abiertas última hora</span>
                                <span class="info-box-number sessions dato" style="font-size: 1rem">-</span>
                            </div>
                        </div>
                        <div class="info-box shadow connectedSortable">
                            <span class="info-box-icon bg-success"><i class="fa-solid fa-user-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Usuarios activos última hora</span>
                                <span class="info-box-number views dato" style="font-size: 1rem">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-6 connectedSortable">
                        <div class="info-box shadow connectedSortable">
                            <span class="info-box-icon bg-success"><i class="fa-solid fa-award"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Finalizaciones el último mes</span>
                                <span class="info-box-number graduates dato" style="font-size: 1rem">-</span>
                            </div>
                        </div>
                        <div class="info-box shadow connectedSortable">
                            <span class="info-box-icon bg-success"><i class="fa-solid fa-user-graduate"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Matriculaciones el último mes</span>
                                <span class="info-box-number enrolments dato" style="font-size: 1rem">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-6 connectedSortable">
                        <div class="info-box shadow connectedSortable">
                            <span class="info-box-icon bg-success"><i class="fa-solid fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Accesos el último mes</span>
                                <span class="info-box-number accesses dato" style="font-size: 1rem">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tooltip for hover effects -->
<div class="hidden" id="tooltip-provincia">
    <div style="font-size: x-large">
        <span class="badge badge-secondary text-justify font-weight-normal p-3" style="line-height: 120%"></span>
    </div>
</div>

<style>
.hidden {
    display: none;
}

#tooltip-provincia {
    position: absolute;
    z-index: 9999;
    pointer-events: none;
}

.knob {
    font-weight: bold;
}

.knob-label {
    font-size: 0.8rem;
    margin-top: 5px;
    text-align: center;
}
</style>

<script>
console.log('Geo page loaded successfully');

// Initialize map
$(document).ready(function(){
  var geoData = <?php echo json_encode($geoDatas ?: []); ?>;
  var provinceData = <?php echo json_encode($provinceDatas ?: []); ?>;
  
  console.log('Geo data loaded:', geoData.length, 'provinces');
  console.log('Province data loaded:', provinceData.length, 'records');

  // Only initialize map if data is available and map container exists
  if ($('#mapa-cont').length) {
    console.log('Map container found');
    if (typeof $.fn.cargarMapa === 'function') {
      $('#mapa-cont').cargarMapa(geoData, provinceData);
      console.log('Map initialized successfully');
    } else {
      console.warn('cargarMapa function not available');
    }
  } else {
    console.warn('Map container not found');
  }

  // Initialize knobs (circular progress indicators)
  setTimeout(function() {
    if (typeof $.fn.knob === 'function') {
      $('.knob').knob({
        draw: function () {
          // "tron" case
          if (this.$.data('skin') == 'tron') {
            var a = this.angle(this.cv);
            var sa = this.startAngle;
            var sat = this.startAngle;
            var ea;
            var eat = sat + a;
            var r = true;

            this.g.lineWidth = this.lineWidth;

            this.o.cursor
              && (sat = eat - 0.3)
              && (eat = eat + 0.3);

            if (this.o.displayPrevious) {
              ea = this.startAngle + this.angle(this.value);
              this.o.cursor
                && (sa = ea - 0.3)
                && (ea = ea + 0.3);
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
      console.log('Knobs initialized successfully');
    } else {
      console.warn('jQuery Knob plugin not available - checking script loading...');
      console.log('Available jQuery functions:', Object.getOwnPropertyNames($.fn).filter(name => name.includes('knob')));
    }
  }, 500); // Wait 500ms for all scripts to load
});
</script>
