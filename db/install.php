<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_cuadrodemando_install() {
    global $DB;
    
    // Verify installation
    try {
        // Check if tables were created
        if (!$DB->get_manager()->table_exists('local_cuadrodemando_stats')) {
            throw new moodle_exception('Installation failed: table not created');
        }
        
        // Initialize default data if needed
        $record = new stdClass();
        $record->stat_type = 'installation';
        $record->stat_value = json_encode(['installed' => time()]);
        $record->timecreated = time();
        $record->timemodified = time();
        
        $DB->insert_record('local_cuadrodemando_stats', $record);
        
        return true;
        
    } catch (Exception $e) {
        error_log('Cuadrodemando installation error: ' . $e->getMessage());
        return false;
    }
}