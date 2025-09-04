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

echo '</div>'; // dashboard-wrapper

?>

<script>
console.log('Geo page loaded successfully');
</script>
