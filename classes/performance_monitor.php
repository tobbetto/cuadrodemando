<?php
namespace local_cuadrodemando;

class performance_monitor {
    
    public static function log_performance($operation, $duration, $memory_usage) {
        global $DB;
        
        // Only log in development or if debugging is enabled
        if (debugging() || defined('DEVELOPER_DEBUG')) {
            error_log("CUADRODEMANDO: {$operation} took {$duration}ms, memory: {$memory_usage}MB");
        }
    }
    
    public static function measure_operation(callable $operation, string $name) {
        $start_time = microtime(true);
        $start_memory = memory_get_usage();
        
        $result = $operation();
        
        $duration = (microtime(true) - $start_time) * 1000;
        $memory = (memory_get_usage() - $start_memory) / 1024 / 1024;
        
        self::log_performance($name, round($duration, 2), round($memory, 2));
        
        return $result;
    }
}