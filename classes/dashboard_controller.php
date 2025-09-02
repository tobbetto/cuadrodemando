<?php
/**
 * Dashboard controller class
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cuadrodemando;

defined('MOODLE_INTERNAL') || die();

/**
 * Main dashboard controller
 */
class dashboard_controller {
    
    /**
     * Display the main dashboard page
     * 
     * @return void
     */
    public static function display_dashboard() {
        global $PAGE, $OUTPUT;
        
        // Handle language switching
        self::handle_language_switch();
        
        // Check capabilities
        $context = \context_system::instance();
        require_capability('local/cuadrodemando:view', $context);
        
        // Set up the page
        $PAGE->set_context($context);
        $PAGE->set_url('/local/cuadrodemando/index.php');
        $PAGE->set_title(get_string('dashboard', 'local_cuadrodemando'));
        $PAGE->set_heading(get_string('dashboard', 'local_cuadrodemando'));
        $PAGE->set_pagelayout('admin');
        
        // Load required CSS and JS assets
        self::load_assets();
        
        echo $OUTPUT->header();
        
        // Load dashboard content
        self::display_content('home');
        
        echo $OUTPUT->footer();
    }
    
    /**
     * Display specific page content
     * 
     * @param string $page The page to display
     * @return void
     */
    public static function display_page($page) {
        global $PAGE, $OUTPUT;
        
        // Handle language switching
        self::handle_language_switch();
        
        // Check capabilities
        $context = \context_system::instance();
        require_capability('local/cuadrodemando:view', $context);
        
        $PAGE->set_context($context);
        $PAGE->set_url("/local/cuadrodemando/index.php", ['page' => $page]);
        $PAGE->set_title(get_string($page, 'local_cuadrodemando'));
        $PAGE->set_heading(get_string($page, 'local_cuadrodemando'));
        $PAGE->set_pagelayout('admin');
        
        // Load required CSS and JS assets
        self::load_assets();
        
        echo $OUTPUT->header();
        
        // Load page content
        self::display_content($page);
        
        echo $OUTPUT->footer();
    }
    
    /**
     * Load required CSS and JavaScript assets
     * 
     * @return void
     */
    private static function load_assets() {
 

    }
    
    /**
     * Display content for a specific page
     * 
     * @param string $page The page to display content for
     * @return void
     */
    private static function display_content($page) {
        global $CFG;
        
        $contentfile = $CFG->dirroot . "/local/cuadrodemando/pages/{$page}.php";
        
        if (file_exists($contentfile)) {
            include($contentfile);
        } else {
            echo \html_writer::tag('h1', 'Page not found');
            echo \html_writer::tag('p', "The requested page '{$page}' could not be found.");
        }
    }
    
    /**
     * Handle language switching
     * 
     * @return void
     */
    public static function handle_language_switch() {
        global $SESSION;
        if (isset($_GET['lang'])) {
            $SESSION->lang = clean_param($_GET['lang'], PARAM_LANG);
        }
    }
}
