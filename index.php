<?php
/**
 * Dashboard plugin for Moodle
 *
 * @package    local_cuadrodemando  
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

defined('MOODLE_INTERNAL') || die();

// Require login
require_login();

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

// Load the dashboard controller
require_once($CFG->dirroot . '/local/cuadrodemando/classes/dashboard_controller.php');
require_once($CFG->libdir . '/moodlelib.php');

// Check for 'page' parameter
$page = isset($_GET['page']) ? clean_param($_GET['page'], PARAM_ALPHANUMEXT) : 'home';

if ($page === 'home') {
	\local_cuadrodemando\dashboard_controller::display_dashboard();
} else {
	\local_cuadrodemando\dashboard_controller::display_page($page);
}
