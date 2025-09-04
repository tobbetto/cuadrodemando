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

// Load essential assets for the map
echo '<link rel="stylesheet" href="/local/cuadrodemando/assets/scripts/map/estilos.css"/>';
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

// Test basic output first
echo '<div>Basic output working</div>';

echo '<div class="dashboard-wrapper">';

// Use navbar helper
try {
    echo \local_cuadrodemando\navbar_helper::render_navbar('geo');
    echo '<div>Navbar rendered successfully</div>';
} catch (Exception $e) {
    echo '<div>Error rendering navbar: ' . $e->getMessage() . '</div>';
}

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
echo '<div class="alert alert-info">Map statistics will load here</div>';
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
    if (file_exists($CFG->dirroot . '/local/cuadrodemando/views/pages/geo/data/user_provincia_table.php')) {
        require_once($CFG->dirroot . '/local/cuadrodemando/views/pages/geo/data/user_provincia_table.php');
        $geoDatas = User_provincia_table::getprovinciainfo();
    } else {
        echo '<script>console.warn("user_provincia_table.php not found");</script>';
    }
} catch (Exception $e) {
    echo '<script>console.error("Error loading geographical data: ' . addslashes($e->getMessage()) . '");</script>';
}

try {
    if (file_exists($CFG->dirroot . '/local/cuadrodemando/views/pages/geo/data/province_activity_table.php')) {
        require_once($CFG->dirroot . '/local/cuadrodemando/views/pages/geo/data/province_activity_table.php');
        $provinceDatas = Activity_province_table::getprovinceactivity();
    } else {
        echo '<script>console.warn("province_activity_table.php not found");</script>';
    }
} catch (Exception $e) {
    echo '<script>console.error("Error loading province activity data: ' . addslashes($e->getMessage()) . '");</script>';
}

?>

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
});
</script>
