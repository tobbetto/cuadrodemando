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

global $OUTPUT, $CFG, $DB;

// Disable AMD/RequireJS before loading third-party scripts
echo '<script>';
echo 'if (typeof define === "function" && define.amd) {';
echo '    var originalDefine = define;';
echo '    define = undefined;';
echo '    window.requirejsVars = { originalDefine: originalDefine };';
echo '}';
echo '</script>';

// Load essential assets
echo '<link rel="stylesheet" type="text/bundle" href="/local/cuadrodemando/thirdpartylibs/fonts-googleapi/fonts.googleapi.css">';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/fontawesome/css/all.min.css">';
echo '<link rel="stylesheet" href="/local/cuadrodemando/thirdpartylibs/adminlte/adminlte.min.css">';
echo '<link rel="stylesheet" href="/local/cuadrodemando/assets/scripts/map/estilos.css"/>';

echo '<script src="/local/cuadrodemando/assets/scripts/jquery/jquery.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/fontawesome/js/all.min.js"></script>';
echo '<script src="/local/cuadrodemando/thirdpartylibs/adminlte/adminlte.min.js"></script>';
echo '<script src="/local/cuadrodemando/assets/scripts/map/mapa.js"></script>';

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
echo html_writer::start_div('dashboard-wrapper');
echo \local_cuadrodemando\navbar_helper::render_navbar('geo');

// Content Wrapper
echo html_writer::start_div('content-wrapper');

// Main content
echo html_writer::start_tag('section', ['class' => 'content']);
echo html_writer::start_div('container-fluid');

// Instructions
echo html_writer::start_div('row mb-2');
echo html_writer::start_div('col-sm-12');
echo html_writer::start_div('instruccion');
echo html_writer::start_div('body-instruccion');
echo 'Pasa el cursor sobre cada provincia para ver sus datos y pulsa para más detalles';
echo html_writer::end_div(); // body-instruccion
echo html_writer::end_div(); // instruccion
echo html_writer::end_div(); // col-sm-12
echo html_writer::end_div(); // row

// Map and data section
echo html_writer::start_div('row');
echo html_writer::start_div('col-10');
echo html_writer::div('', '', ['id' => 'mapa-cont']);
echo html_writer::end_div(); // col-10
echo html_writer::start_div('col-2');
try {
    // Try to load getdata for map knobs
    if (file_exists($CFG->dirroot . '/local/cuadrodemando/views/getdata/getdata.php')) {
        include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/getdata.php');
        
        if (class_exists('adminlte_getdata') && method_exists('adminlte_getdata', 'get_map_knobs')) {
            $calendar_info = adminlte_getdata::get_map_knobs();
            echo $calendar_info;
        } else {
            echo html_writer::start_div('info-box shadow');
            echo html_writer::tag('span', html_writer::tag('i', '', ['class' => 'fa-solid fa-map']), ['class' => 'info-box-icon bg-info']);
            echo html_writer::start_div('info-box-content');
            echo html_writer::tag('span', 'Total Provinces', ['class' => 'info-box-text']);
            echo html_writer::tag('span', '52', ['class' => 'info-box-number']);
            echo html_writer::end_div(); // info-box-content
            echo html_writer::end_div(); // info-box
        }
    } else {
        echo html_writer::div('Map statistics loading...', 'alert alert-info');
    }
} catch (Exception $e) {
    echo html_writer::div('Map statistics unavailable', 'alert alert-warning');
}
echo html_writer::end_div(); // col-2
echo html_writer::end_div(); // row

echo html_writer::end_div(); // container-fluid
echo html_writer::end_tag('section'); // content
echo html_writer::end_div(); // content-wrapper

echo html_writer::end_div(); // dashboard-wrapper

// Load data classes and get geographical data
$geoDatas = [];
$provinceDatas = [];

try {
    $user_provincia_file = dirname(__DIR__) . '/views/pages/geo/data/user_provincia_table.php';
    
    if (file_exists($user_provincia_file)) {
        require_once($user_provincia_file);
        
        if (class_exists('User_provincia_table')) {
            if (method_exists('User_provincia_table', 'getprovinciainfo')) {
                $geoDatas = User_provincia_table::getprovinciainfo();
            } else {
                echo '<script>console.error("getprovinciainfo method does not exist");</script>';
            }
        } else {
            echo '<script>console.error("User_provincia_table class does not exist");</script>';
        }
    } else {
        echo '<script>console.warn("user_provincia_table.php not found");</script>';
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
        
        if (class_exists('Activity_province_table')) {
            if (method_exists('Activity_province_table', 'getprovinceactivity')) {
                $provinceDatas = Activity_province_table::getprovinceactivity();
            } else {
                echo '<script>console.error("getprovinceactivity method does not exist");</script>';
            }
        } else {
            echo '<script>console.error("Activity_province_table class does not exist");</script>';
        }
    } else {
        echo '<script>console.warn("province_activity_table.php not found");</script>';
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
                    <div class="col-md-4 col-sm-2 col-2 connectedSortable">
                        <div class="info-box shadow connectedSortable">
                            <span class="info-box-icon bg-success"><i class="fa-solid fa-right-to-bracket"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Sesiones abiertas última hora</span>
                                <span class="info-box-number" id='datos-provincia'><?php echo '<p class="sessions dato" style="font-size: 1rem"></p></span>'; ?></span>
                            </div>
                        </div>
                        <div class="info-box shadow connectedSortable">
                            <span class="info-box-icon bg-success"><i class="fa-solid fa-user-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Usuarios activos última hora</span>
                                <span class="info-box-number" id='datos-provincia'><?php echo '<p class="views dato" style="font-size: 1rem"></p></span>'; ?></span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="col-md-4 col-sm-6 col-6 connectedSortable">
                        <div class="info-box shadow connectedSortable">
                            <span class="info-box-icon bg-success"><i class="fa-solid fa-award"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Finalizaciones el último mes</span>
                                <span class="info-box-number" id='datos-provincia'><?php echo '<p class="graduates dato" style="font-size: 1rem"></p></span>'; ?></span>
                            </div>
                        </div>
                        <div class="info-box shadow connectedSortable">
                            <span class="info-box-icon bg-success"><i class="fa-solid fa-user-graduate"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Matriculaciones el último mes</span>
                                <span class="info-box-number" id='datos-provincia'><?php echo '<p class="enrolments dato" style="font-size: 1rem"></p></span>'; ?></span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="col-md-4 col-sm-6 col-6 connectedSortable">
                        <div class="info-box shadow connectedSortable">
                            <span class="info-box-icon bg-success"><i class="fa-solid fa-user-plus"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Altas el último mes</span>
                                <span class="info-box-number" id='datos-provincia'><?php echo '<p class="registrations dato" style="font-size: 1rem"></p></span>'; ?></span>
                            </div>
                        </div>
                        <div class="info-box shadow connectedSortable">
                            <span class="info-box-icon bg-danger"><i class="fa-solid fa-user-minus"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Bajas en provincia último mes:</span>
                                <span class="info-box-number" id='datos-provincia'><?php echo '<p class="deletes dato" style="font-size: 1rem"></p></span>'; ?></span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div> <!--  row -->
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

/* CSS Circular Progress Indicators */
.circular-progress {
    position: relative;
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: conic-gradient(#28a745 0deg, #28a745 var(--progress-deg), #e9ecef var(--progress-deg), #e9ecef 360deg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
}

.circular-progress.small {
    width: 50px;
    height: 50px;
}

.circular-progress::before {
    content: '';
    position: absolute;
    width: 70%;
    height: 70%;
    border-radius: 50%;
    background: white;
}

.progress-value {
    position: relative;
    z-index: 1;
    font-size: 14px;
    font-weight: bold;
    color: #28a745;
}

.circular-progress.small .progress-value {
    font-size: 10px;
}

.progress-label {
    font-size: 0.8rem;
    margin-top: 5px;
    text-align: center;
}
</style>

<script>
// Initialize map
$(document).ready(function(){
  var geoData = <?php echo json_encode($geoDatas ?: []); ?>;
  var provinceData = <?php echo json_encode($provinceDatas ?: []); ?>;

  // Initialize map if data is available and map container exists
  if ($('#mapa-cont').length) {
    if (typeof $.fn.cargarMapa === 'function') {
      $('#mapa-cont').cargarMapa(geoData, provinceData);
    } else {
      console.warn('cargarMapa function not available');
    }
  } else {
    console.warn('Map container not found');
  }
});
</script>

<script>
// Fix for RequireJS/AMD conflicts - restore AMD detection if needed
if (window.requirejsVars && window.requirejsVars.originalDefine) {
    define = window.requirejsVars.originalDefine;
}
</script>
