<?php
if (!defined('CLI_SCRIPT')) {
    define('CLI_SCRIPT', true);
    include __DIR__.'/../../../config.php';
}

adminlte_gethourlyviews::total_hourly_views();
//adminlte_getlogins::province_hourly_views();

class adminlte_gethourlyviews {

    public static function total_hourly_views() {
        
        global $DB;

        $sql_oracle = "SELECT action , COUNT(DISTINCT userid) AS viewed
        FROM {logstore_standard_log}
        -- WHERE TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND') >= (sysdate-1/12)
        WHERE to_char(TO_TIMESTAMP_TZ('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND'),
            'YYYY-MM-DD HH24:MI:SSxFF TZH:TZM'
            )  >= to_char(cast((systimestamp - interval '1' hour) as timestamp) at time zone 'GMT', 'YYYY-MM-DD HH24:MI:SSxFF TZH:TZM')
            -- >= to_char(systimestamp - interval '5' HOUR, 'YYYY-MM-DD HH24:MI:SSxFF TZH:TZM')
            -- to_char(cast((systimestamp - interval '1' hour) as timestamp) at time zone 'GMT','DD/MM/YY HH24:MI:SSxFF TZH:TZM') 
            and action = 'viewed'
        GROUP BY action
        ORDER BY action";
        
        $sql_mysql = "SELECT action , COUNT(DISTINCT userid) AS viewed
                FROM {logstore_standard_log}
                WHERE FROM_UNIXTIME(timecreated) >= DATE_SUB(NOW(),INTERVAL 1 HOUR) and action = 'viewed'
                GROUP BY action
                ORDER BY action";

        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

        $statistics = $DB->get_records_sql($sql);
        $loll = (!empty($statistics)) ? $statistics["viewed"]->viewed : 0;

        $path = "/moodle/www/adminlte/views/getdata/total_hourly_views_json.php";

        $month_data_contents = 
        '<?php
        
          class Total_views_json {
        
              public static function get_total_hourly_views() {
        
              $monthdata = ' . $loll . ';
        
              return $monthdata;
        
              }
          }';

          file_put_contents($path, $month_data_contents);

        // Return the result
        return (!empty($statistics)) ? $statistics["viewed"]->viewed : null;
    }

    public static function province_hourly_views() {
        
        global $DB;

        $provinces = ['MADRID', 'CADIZ'];

        $sql = "select l.action, COUNT(DISTINCT l.userid) AS viewed
        from m_logstore_standard_log l
        JOIN m_user u ON u.id = l.userid AND u.institution = '$province'
        where TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(l.timecreated, 'SECOND') >= (sysdate-1/12) and l.action = 'viewed'
        GROUP BY l.action
        ORDER BY l.action";

        $statistics = $DB->get_records_sql($sql);

        if (!empty($statistics)) {
            echo $statistics["viewed"]->viewed;
        } else {
            echo 'No hay usuarios activos';
        }
    }


}