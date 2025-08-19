<?php
namespace local_cuadrodemando;

class database_helper {
    
    /**
     * Get user statistics with optimized queries
     */
    public static function get_user_stats_optimized() {
        global $DB;
        
        // Use single query instead of multiple calls
        $sql = "SELECT 
                    COUNT(*) as total_users,
                    SUM(CASE WHEN lastaccess > :recent_time THEN 1 ELSE 0 END) as active_users,
                    SUM(CASE WHEN suspended = 1 THEN 1 ELSE 0 END) as suspended_users
                FROM {user} 
                WHERE deleted = 0";
        
        return $DB->get_record_sql($sql, ['recent_time' => time() - (30 * 24 * 3600)]);
    }
    
    /**
     * Get course statistics with joins
     */
    public static function get_course_stats_optimized() {
        global $DB;
        
        $sql = "SELECT 
                    c.id, c.fullname, c.visible,
                    COUNT(DISTINCT ue.userid) as enrolled_users,
                    COUNT(DISTINCT cm.id) as modules_count
                FROM {course} c
                LEFT JOIN {enrol} e ON e.courseid = c.id
                LEFT JOIN {user_enrolments} ue ON ue.enrolid = e.id
                LEFT JOIN {course_modules} cm ON cm.course = c.id
                WHERE c.id > 1
                GROUP BY c.id, c.fullname, c.visible
                ORDER BY enrolled_users DESC";
        
        return $DB->get_records_sql($sql);
    }
}