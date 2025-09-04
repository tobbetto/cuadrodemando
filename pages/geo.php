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

// Disable AMD/RequireJS before loading third-party scripts
echo '<script>';
echo 'if (typeof define === "function" && define.amd) {';
echo '    var originalDefine = define;';
echo '    define = undefined;';
echo '    window.requirejsVars = { originalDefine: originalDefine };';
echo '}';
echo '</script>';

// Direct asset loading (like template.php)
echo '<link rel="stylesheet" type="text/css" href="/local/cuadrodemando/thirdpartylibs/fonts-googleapi/fonts.googleapi.css">';
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

global $OUTPUT, $CFG, $DB;

// Include necessary classes
require_once($CFG->dirroot . '/local/cuadrodemando/classes/navbar_helper.php');
include_once($CFG->dirroot . '/local/cuadrodemando/views/getdata/getdata.php');

// Include data classes
require_once($CFG->dirroot . '/local/cuadrodemando/views/pages/geo/data/user_provincia_table.php');
require_once($CFG->dirroot . '/local/cuadrodemando/views/pages/geo/data/province_activity_table.php');

echo html_writer::start_div('dashboard-wrapper');

// Use navbar helper
echo \local_cuadrodemando\navbar_helper::render_navbar('geo');

// Content Wrapper
echo html_writer::start_div('content-wrapper');

// Main content
echo html_writer::start_tag('section', array('class' => 'content'));
echo html_writer::start_div('container-fluid');

// Instructions
echo html_writer::start_div('row mb-2');
echo html_writer::start_div('col-sm-12');
echo html_writer::start_div('instruccion');
echo html_writer::start_div('body-instruccion');
echo get_string('geo_instructions', 'local_cuadrodemando');
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();

// Map and data section
echo html_writer::start_div('row');

// Map container
echo html_writer::start_div('col-10');
echo html_writer::div('', '', array('id' => 'mapa-cont'));
echo html_writer::end_div();

// Map statistics knobs
echo html_writer::start_div('col-2');
try {
    // Get map knobs data if the method exists
    if (class_exists('adminlte_getdata') && method_exists('adminlte_getdata', 'get_map_knobs')) {
        $calendar_info = adminlte_getdata::get_map_knobs();
        echo $calendar_info;
    } else {
        // Fallback display for map statistics
        // Get total provinces count (temporary fallback)
        $tempGeoData = [];
        try {
            $tempGeoData = User_provincia_table::getprovinciainfo();
        } catch (Exception $e) {
            error_log('Error getting province data: ' . $e->getMessage());
        }
        
        echo html_writer::start_div('info-box shadow');
        echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-map')), array('class' => 'info-box-icon bg-info'));
        echo html_writer::start_div('info-box-content');
        echo html_writer::tag('span', get_string('provinces_total', 'local_cuadrodemando'), array('class' => 'info-box-text'));
        echo html_writer::tag('span', count($tempGeoData), array('class' => 'info-box-number'));
        echo html_writer::end_div();
        echo html_writer::end_div();
    }
} catch (Exception $e) {
    // Fallback in case of error
    echo html_writer::div(get_string('geo_data_loading', 'local_cuadrodemando'), 'alert alert-info');
}
echo html_writer::end_div();

echo html_writer::end_div(); // row

// Modal for province details
echo html_writer::start_div('modal fade', array('id' => 'modalDatosProvincia', 'tabindex' => '-1', 'role' => 'dialog'));
echo html_writer::start_div('modal-dialog modal-xl');
echo html_writer::start_div('modal-content');

// Modal header
echo html_writer::start_div('modal-header');
echo html_writer::tag('h4', '', array('class' => 'modal-title'));
echo html_writer::tag('h4', ': ' . get_string('province_last_30_days', 'local_cuadrodemando'), array('class' => 'modal-subtitle', 'style' => 'line-height: 1.5;'));
echo html_writer::start_tag('button', array('type' => 'button', 'class' => 'close', 'data-bs-dismiss' => 'modal', 'aria-label' => 'Close'));
echo html_writer::tag('span', '&times;', array('aria-hidden' => 'true'));
echo html_writer::end_tag('button');
echo html_writer::end_div();

// Modal body with statistics
echo html_writer::start_div('modal-body');
echo html_writer::start_div('row');

// First column of stats
echo html_writer::start_div('col-md-4 col-sm-2 col-2 connectedSortable');

// Active sessions info box
echo html_writer::start_div('info-box shadow connectedSortable');
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-right-to-bracket')), array('class' => 'info-box-icon bg-success'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('sessions_last_hour', 'local_cuadrodemando'), array('class' => 'info-box-text'));
echo html_writer::tag('span', html_writer::tag('p', '', array('class' => 'sessions dato', 'style' => 'font-size: 1rem')), array('class' => 'info-box-number', 'id' => 'datos-provincia'));
echo html_writer::end_div();
echo html_writer::end_div();

// Active users info box
echo html_writer::start_div('info-box shadow connectedSortable');
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-user-clock')), array('class' => 'info-box-icon bg-success'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('active_users_last_hour', 'local_cuadrodemando'), array('class' => 'info-box-text'));
echo html_writer::tag('span', html_writer::tag('p', '', array('class' => 'views dato', 'style' => 'font-size: 1rem')), array('class' => 'info-box-number', 'id' => 'datos-provincia'));
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::end_div(); // col-md-4

// Second column of stats
echo html_writer::start_div('col-md-4 col-sm-6 col-6 connectedSortable');

// Completions info box
echo html_writer::start_div('info-box shadow connectedSortable');
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-award')), array('class' => 'info-box-icon bg-success'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('completions_last_month', 'local_cuadrodemando'), array('class' => 'info-box-text'));
echo html_writer::tag('span', html_writer::tag('p', '', array('class' => 'graduates dato', 'style' => 'font-size: 1rem')), array('class' => 'info-box-number', 'id' => 'datos-provincia'));
echo html_writer::end_div();
echo html_writer::end_div();

// Enrollments info box
echo html_writer::start_div('info-box shadow connectedSortable');
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-user-graduate')), array('class' => 'info-box-icon bg-success'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('enrollments_last_month', 'local_cuadrodemando'), array('class' => 'info-box-text'));
echo html_writer::tag('span', html_writer::tag('p', '', array('class' => 'enrolments dato', 'style' => 'font-size: 1rem')), array('class' => 'info-box-number', 'id' => 'datos-provincia'));
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::end_div(); // col-md-4

// Third column of stats
echo html_writer::start_div('col-md-4 col-sm-6 col-6 connectedSortable');

// New registrations info box
echo html_writer::start_div('info-box shadow connectedSortable');
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-user-plus')), array('class' => 'info-box-icon bg-success'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('registrations_last_month', 'local_cuadrodemando'), array('class' => 'info-box-text'));
echo html_writer::tag('span', html_writer::tag('p', '', array('class' => 'registrations dato', 'style' => 'font-size: 1rem')), array('class' => 'info-box-number', 'id' => 'datos-provincia'));
echo html_writer::end_div();
echo html_writer::end_div();

// Deletions info box
echo html_writer::start_div('info-box shadow connectedSortable');
echo html_writer::tag('span', html_writer::tag('i', '', array('class' => 'fa-solid fa-user-minus')), array('class' => 'info-box-icon bg-danger'));
echo html_writer::start_div('info-box-content');
echo html_writer::tag('span', get_string('deletions_last_month', 'local_cuadrodemando'), array('class' => 'info-box-text'));
echo html_writer::tag('span', html_writer::tag('p', '', array('class' => 'deletes dato', 'style' => 'font-size: 1rem')), array('class' => 'info-box-number', 'id' => 'datos-provincia'));
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::end_div(); // col-md-4

echo html_writer::end_div(); // row
echo html_writer::end_div(); // modal-body

echo html_writer::end_div(); // modal-content
echo html_writer::end_div(); // modal-dialog
echo html_writer::end_div(); // modal

// Hidden tooltip for province
echo html_writer::start_div('hidden', array('id' => 'tooltip-provincia'));
echo html_writer::start_div('', array('style' => 'font-size: x-large'));
echo html_writer::tag('span', '', array('class' => 'badge badge-secondary text-justify font-weight-normal p-3', 'style' => 'line-height: 120%'));
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::end_div(); // container-fluid
echo html_writer::end_tag('section'); // content
echo html_writer::end_div(); // content-wrapper

echo html_writer::end_div(); // dashboard-wrapper

// Get geographical data
try {
    $geoDatas = User_provincia_table::getprovinciainfo();
} catch (Exception $e) {
    error_log('Error loading geographical data: ' . $e->getMessage());
    $geoDatas = []; // Fallback to empty array
}

try {
    $provinceDatas = Activity_province_table::getprovinceactivity();
} catch (Exception $e) {
    error_log('Error loading province activity data: ' . $e->getMessage());
    $provinceDatas = []; // Fallback to empty array
}

$activity_data = array_merge_recursive($geoDatas, $provinceDatas);

?>

<script>
/*LLAMA A LA FUNCIÓN QUE CARGA EL MAPA EN SU CONTEBNEDOR*/
$(document).ready(function(){
  var geoData = <?php echo json_encode($geoDatas ?: []); ?>;
  var provinceData = <?php echo json_encode($provinceDatas ?: []); ?>;

    // Only initialize map if data is available and map container exists
    if ($('#mapa-cont').length && typeof $.fn.cargarMapa === 'function') {
        $('#mapa-cont').cargarMapa(geoData, provinceData);
    } else {
        console.warn('Map container not found or cargarMapa function not available');
    }
    
    $(function () {
        if (typeof $.fn.popover === 'function') {
            $('[data-toggle="popover"]').popover();
        }
    });
    
    $(function () {
        if (typeof $.fn.tooltip === 'function') {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

});
</script>
<script>
  $(function () {
    /* jQueryKnob */
    if (typeof $.fn.knob === 'function') {
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
    });
    /* END JQUERY KNOB */
    } else {
      console.warn('jQuery Knob plugin not loaded');
    }
  })
</script>
