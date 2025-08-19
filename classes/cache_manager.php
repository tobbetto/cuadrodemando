<?php
namespace local_cuadrodemando;

class cache_manager {
    
    /**
     * Get cached dashboard data
     */
    public static function get_dashboard_data($force_refresh = false) {
        $cache = \cache::make('local_cuadrodemando', 'dashboard_data');
        
        if (!$force_refresh) {
            $data = $cache->get('stats');
            if ($data !== false) {
                return $data;
            }
        }
        
        // Generate fresh data
        $data = [
            'users' => database_helper::get_user_stats_optimized(),
            'courses' => database_helper::get_course_stats_optimized(),
            'timestamp' => time()
        ];
        
        // Cache for 1 hour
        $cache->set('stats', $data);
        return $data;
    }
}