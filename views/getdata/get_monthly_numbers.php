<?php
if (!defined('CLI_SCRIPT')) {
    define('CLI_SCRIPT', true);
    include __DIR__.'/../../../config.php';
}
adminlte_getmonthlydata::monthDataJson();

class adminlte_getmonthlydata {

    public static function get_monthly_completion($month, $year) {

        global $DB;

        $shortyear = substr($year, -2);
        $month = strtoupper(date('M', strtotime('01-'.$month.'-'.$year)));
        $date = date_parse($month);
        // SQL para ORACLE
        $sql_oracle = "SELECT 
                        extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecompleted, 'SECOND')) as yr, 
                        extract(month from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecompleted, 'SECOND')) as mon, 
                        count(timecompleted) as completed
        FROM {course_completions}
        WHERE TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecompleted, 'SECOND' ) LIKE '%-{$month}-{$shortyear}'
        GROUP BY 
            extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecompleted, 'SECOND')), 
            extract(month from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecompleted, 'SECOND'))
        ORDER BY yr, mon";

        // SQL para MySQL
        $sql_mysql = "SELECT
                    YEAR(FROM_UNIXTIME(timecompleted)) AS yr,
                    MONTH(FROM_UNIXTIME(timecompleted)) AS mon,
                    COUNT(timecompleted) AS completed
                FROM
                    {course_completions}
                WHERE
                    DATE_FORMAT(FROM_UNIXTIME(timecompleted), '%d-%c-%Y') LIKE '%-{$date['month']}-{$year}'
                GROUP BY
                    YEAR(FROM_UNIXTIME(timecompleted)),
                    MONTH(FROM_UNIXTIME(timecompleted))
                ORDER BY
                    yr,
                    mon";

        // Determine the SQL query to use based on the database type
        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

        $statistics = $DB->get_records_sql($sql);

        // Return the result
        return (!empty($statistics)) ? $statistics["{$year}"]->completed : null;
    }

    public static function get_monthly_registrations($month, $year) {

        global $DB;

        $shortyear = substr($year, -2);
        $month = strtoupper(date('M', strtotime('01-'.$month.'-'.$year)));
        $date = date_parse($month);
        /* SQL para ORACLE */
        $sql_oracle = "select extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND')) as yr, extract(month from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND')) as mon, 
        count(timecreated) as created
        from {user}
        WHERE TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND' ) LIKE '%-{$month}-{$shortyear}'
        GROUP BY extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND')), extract(month from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND'))
        ORDER BY yr, mon";
        
        $sql_mysql = "SELECT
                    YEAR(FROM_UNIXTIME(timecreated)) AS yr,
                    MONTH(FROM_UNIXTIME(timecreated)) AS mon,
                    count(timecreated) as created
                FROM
                    {user}
                WHERE
                    DATE_FORMAT(FROM_UNIXTIME(timecreated), '%d-%c-%Y') LIKE '%-{$date['month']}-{$year}'
                GROUP BY
                    YEAR(FROM_UNIXTIME(timecreated)),
                    MONTH(FROM_UNIXTIME(timecreated))
                ORDER BY
                    yr,
                    mon";
        
        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

        $statistics = $DB->get_records_sql($sql);

        // Return the result
        return (!empty($statistics)) ? $statistics["{$year}"]->created : null;

    }

    public static function get_monthly_accesses($month, $year) {
        
        global $DB;

        $shortyear = substr($year, -2);
        $month = strtoupper(date('M', strtotime('01-'.$month.'-'.$year)));
        $date = date_parse($month);
        
        $sql_oracle = "select extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND')) as yr, extract(month from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND')) as mon, 
        count(timecreated) as loggedin
        from {logstore_standard_log}
        WHERE TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND' ) LIKE '%-{$month}-{$shortyear}' AND action = 'loggedin'
        GROUP BY extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND')), extract(month from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND'))
        ORDER BY yr, mon";
        
        $sql_mysql = "SELECT
                    YEAR(FROM_UNIXTIME(timecreated)) AS yr,
                    MONTH(FROM_UNIXTIME(timecreated)) AS mon,
                    count(timecreated) as loggedin
                FROM
                {logstore_standard_log}
                WHERE
                    DATE_FORMAT(FROM_UNIXTIME(timecreated), '%d-%c-%Y') LIKE '%-{$date['month']}-{$year}'
                GROUP BY
                    YEAR(FROM_UNIXTIME(timecreated)),
                    MONTH(FROM_UNIXTIME(timecreated))
                ORDER BY
                    yr,
                    mon";

        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

        $statistics = $DB->get_records_sql($sql);

        // Return the result
        return (!empty($statistics)) ? $statistics["{$year}"]->loggedin : null;
    }

    public static function get_hourly_views() {
        
        global $DB;

        $sql_oracle = "SELECT action , COUNT(DISTINCT userid) AS viewed
        FROM {logstore_standard_log}
        WHERE TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND') >= (sysdate-1/12) and action = 'viewed'
        GROUP BY action
        ORDER BY action";
        
        $sql_mysql = "SELECT action , COUNT(DISTINCT userid) AS viewed
                FROM {logstore_standard_log}
                WHERE FROM_UNIXTIME(timecreated) >= DATE_SUB(NOW(),INTERVAL 1 HOUR) and action = 'viewed'
                GROUP BY action
                ORDER BY action";

        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

        $statistics = $DB->get_records_sql($sql);

        // Return the result
        return (!empty($statistics)) ? $statistics["viewed"]->viewed : null;
    }

    public static function get_monthly_enrolments($month, $year) {

        global $DB;

        $shortyear = substr($year, -2);
        $month = strtoupper(date('M', strtotime('01-'.$month.'-'.$year)));
        $date = date_parse($month);
        
        $sql_oracle = "select extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND')) as yr, extract(month from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND')) as mon, 
        count(timecreated) as created
        from {user_enrolments}
        WHERE TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND' ) LIKE '%-{$month}-{$shortyear}'
        GROUP BY extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND')), extract(month from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND'))
        ORDER BY yr, mon";
        
        $sql_mysql = "SELECT
                    YEAR(FROM_UNIXTIME(timecreated)) AS yr,
                    MONTH(FROM_UNIXTIME(timecreated)) AS mon,
                    count(timecreated) as created
                FROM
                    {user_enrolments}
                WHERE
                    DATE_FORMAT(FROM_UNIXTIME(timecreated), '%d-%c-%Y') LIKE '%-{$date['month']}-{$year}'
                GROUP BY
                    YEAR(FROM_UNIXTIME(timecreated)),
                    MONTH(FROM_UNIXTIME(timecreated))
                ORDER BY
                    yr,
                    mon";

        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

        $statistics = $DB->get_records_sql($sql);

        // Return the result
        return (!empty($statistics)) ? $statistics["{$year}"]->created : null;
    }

    public static function get_monthly_suspensions($month, $year) {

        global $DB;

        $shortyear = substr($year, -2);
        $month = strtoupper(date('M', strtotime('01-'.$month.'-'.$year)));
        $date = date_parse($month);
        
        $sql_oracle = "SELECT 
                        extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timemodified, 'SECOND')) as yr, 
                        extract(month from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timemodified, 'SECOND')) as mon, 
                        count(timemodified) as suspended
                        FROM {user}
                        WHERE TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timemodified, 'SECOND' ) LIKE '%-{$month}-{$shortyear}' AND (deleted = 1 OR suspended = 1)
                        GROUP BY 
                            extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timemodified, 'SECOND')), 
                            extract(month from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timemodified, 'SECOND'))
                        ORDER BY yr, mon";
        
        $sql_mysql = "SELECT
                    YEAR(FROM_UNIXTIME(timemodified)) AS yr,
                    MONTH(FROM_UNIXTIME(timemodified)) AS mon,
                    count(timemodified) as suspended
                FROM
                    {user}
                WHERE
                    DATE_FORMAT(FROM_UNIXTIME(timemodified), '%d-%c-%Y') LIKE '%-{$date['month']}-{$year}' AND (deleted = 1 OR suspended = 1)
                GROUP BY
                    YEAR(FROM_UNIXTIME(timemodified)),
                    MONTH(FROM_UNIXTIME(timemodified))
                ORDER BY
                    yr,
                    mon";

        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

        $statistics = $DB->get_records_sql($sql);

        // Return the result
        return (!empty($statistics)) ? $statistics["{$year}"]->suspended : null;
    }

    public static function get_monthly_messages($month, $year) {

        global $DB;

        $shortyear = substr($year, -2);
        $month = strtoupper(date('M', strtotime('01-'.$month.'-'.$year)));
        $date = date_parse($month);
        
        $sql_oracle = "select extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND')) as yr, extract(month from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND')) as mon, 
        count(timecreated) as sent
        from {notifications}
        WHERE TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND' ) LIKE '%-{$month}-{$shortyear}'
        GROUP BY extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND')), extract(month from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND'))
        ORDER BY yr, mon";
        
        $sql_mysql = "SELECT
                    YEAR(FROM_UNIXTIME(timecreated)) AS yr,
                    MONTH(FROM_UNIXTIME(timecreated)) AS mon,
                    count(timecreated) as sent
                FROM
                    {notifications}
                WHERE
                    DATE_FORMAT(FROM_UNIXTIME(timecreated), '%d-%c-%Y') LIKE '%-{$date['month']}-{$year}'
                GROUP BY
                    YEAR(FROM_UNIXTIME(timecreated)),
                    MONTH(FROM_UNIXTIME(timecreated))
                ORDER BY
                    yr,
                    mon";

        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

        $statistics = $DB->get_records_sql($sql);

        // Return the result
        return (!empty($statistics)) ? $statistics["{$year}"]->sent : null;
    }

    public static function get_access_count() {
        global $DB;

        // Construct the SQL query //"SELECT COUNT(*) FROM {user} WHERE deleted = 0 AND email != ''";
        $sql_mysql = "SELECT COUNT(DISTINCT userid) count FROM {logstore_standard_log} WHERE action = 'loggedin' AND YEAR(FROM_UNIXTIME(timecreated)) = {date('Y')}";
        $sql_oracle = "SELECT COUNT(DISTINCT userid) FROM {logstore_standard_log} WHERE action = 'loggedin' AND to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(timecreated, 'SECOND'), 'yyyy') = '" . date('Y') . "'";
        
        // Determine the SQL query to use based on the database type
        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;
        
        // Execute the SQL query
        $loginCount = $DB->count_records_sql($sql, null);


            return $loginCount;

    }
    public static function monthDataJson()
    {
        global $DB;
        $monthecho = '';
        $monthdata = [];

        $formatter = new \IntlDateFormatter(
            'es_ES.utf8',
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE,
            'Europe/Madrid', //more in: https://www.php.net/manual/en/timezones.europe.php
            \IntlDateFormatter::GREGORIAN
        );

        $start = new DateTime;
        $start->setDate($start->format('Y'), $start->format('n'), 1); // Normalize the day to 1
        $start->setTime(0, 0, 0); // Normalize time to midnight
        $start->sub(new DateInterval('P1Y')); // Mostramos un año entero
        $interval = new DateInterval('P1M');  // Mostramos cada 1 mes
        $recurrences = 12; // un foreach de 12 para cada mes del año

        $path = "/moodle/www/local/cuadrodemando/views/getdata/monthly_numbers_json.php";

        $totalaccesses = self::get_access_count();

        foreach (new DatePeriod($start, $interval, $recurrences, true) as $date) {

            $monthobject = $formatter->formatObject($date, "MM", "es_ES.utf8");
            $yearobject = $formatter->formatObject($date, "y", "es_ES.utf8");
            $monthecho .= 
                ' "' . $monthobject . '" => ["' . $yearobject . '" => ["completions" => "'. self::get_monthly_completion($monthobject, $yearobject) . '", "registrations" => "'. self::get_monthly_registrations($monthobject, $yearobject) . '", "accesses" => "'. self::get_monthly_accesses($monthobject, $yearobject) . '", "enrolments" => "'. self::get_monthly_enrolments($monthobject, $yearobject) . '", "suspensions" => "'. self::get_monthly_suspensions($monthobject, $yearobject) . '", "messages" => "'. self::get_monthly_messages($monthobject, $yearobject) . '", "totalaccess" => "' . $totalaccesses . '"]],' ;

        }

        $monthdata = [
             $monthecho  
        ];

        $month_data_contents = 
'<?php

  class Monthly_numbers_json {

      public static function get_month_numbers() {

      $monthdata = [' . $monthecho .'];

      return $monthdata;

      }
  }';
          
            file_put_contents($path, $month_data_contents);
      //return $geodata;
  }

}
