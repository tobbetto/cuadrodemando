<?php
if (!defined('CLI_SCRIPT')) {
    define('CLI_SCRIPT', true);
    include __DIR__.'/../../../config.php';
}
adminlte_getlogins::user_logins_data();
adminlte_getlogins::total_logins_data();
adminlte_getlogins::total_user_changes();

class adminlte_getlogins {

    public static function user_logins_data()
    {
        global $DB;
        $loginarray = '';

        $formatter = new \IntlDateFormatter(
            'es_ES.utf8',
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE,
            'Europe/Madrid', //more in: https://www.php.net/manual/en/timezones.europe.php
            \IntlDateFormatter::GREGORIAN
        );

        $sql_oracle = "SELECT
                        l.userid AS userid, 
                        to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(l.timecreated, 'SECOND' ), 'D') AS daynumber, 
                        to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(l.timecreated, 'SECOND' ), 'DY') AS dayname, count(l.userid) as logins
                        FROM {logstore_standard_log} l WHERE l.eventname LIKE '%loggedin%' 
                        AND TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL( l.timecreated, 'SECOND' ) BETWEEN next_day(trunc(sysdate), 'MON') - 365 AND next_day(trunc(sysdate), 'MON')
                        GROUP BY userid, to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL( l.timecreated, 'SECOND' ), 'D'), to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(l.timecreated, 'SECOND' ), 'DY')
                        ORDER BY userid, to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL( l.timecreated, 'SECOND' ), 'D')";

            $sql_mysql = "SELECT DATE_FORMAT(FROM_UNIXTIME(l.timecreated), '%w') AS daynumber, 
                    DATE_FORMAT(FROM_UNIXTIME(l.timecreated), '%a') AS dayname,  
                    COUNT("; 
                    if (empty($id)) { 
                        $sql_mysql .= "DISTINCT "; 
                    }
                    $sql_mysql .= "userid) AS logins
                    FROM {logstore_standard_log} l WHERE l.eventname LIKE '%loggedin%' " ;
                    if (!empty($id)) {
                        $sql_mysql .= "AND l.userid = {$userid} ";
                    } 
                    $sql_mysql .= "AND FROM_UNIXTIME(l.timecreated) >= CURDATE() - INTERVAL 1 YEAR
                    GROUP BY DATE_FORMAT(FROM_UNIXTIME(l.timecreated), '%w'), DATE_FORMAT(FROM_UNIXTIME(l.timecreated), '%a')
                    ORDER BY DATE_FORMAT(FROM_UNIXTIME(l.timecreated), '%w')";

            $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

            // Execute the SQL query
            $users = $DB->get_recordset_sql($sql);

        $path = "/moodle/www/local/cuadrodemando/views/getdata/users_logins_json.php";
        $lastUser = 0;
        $output = '';
        
        foreach ($users as $user) {

            $id = $user->userid; // 2
            if ($lastUser != $id) {
               
                $output .= "[ 'userid' => '" . $lastUser . "', 'logins' => '" . json_encode($loginsArray) . "', 'dayname' => '" . json_encode($daysArray) . "'],";
                $output2 .= $lastUser . " => [ 'logins' => '" . json_encode($loginsArray) . "', 'dayname' => '" . json_encode($daysArray) . "'],";
                $loginsArray =  [];
                $daysArray =  [];
                $lastUser = $id;
                
            }
            $formatDate = new DateTime($user->dayname);
            $spaname = $formatter->formatObject($formatDate, "E", "es_ES.utf8");
            array_push($loginsArray, $user->logins);
            array_push($daysArray, $spaname);
            
                //$output = [ 'userid' => $id, 'logins' => $loginsArray, 'days' => $daysArray];
                
            // [ '$dayLogin->userid' => [ 'SUN' => 'logins1', 'MON' => 'logins2', 'TUE' => 'logins3', 'WED' => 'logins4', 'THU' => 'logins5', 'FRI' => 'logins6', 'SAT' => 'logins7'] ];
            //    $loginarray .= "['user' => '" . $dayLogin->userid . "', 'daynumber' => '" . $dayLogin->daynumber . "', 'dayname' => '" . $dayLogin->dayname . "', 'logins' => '" . $dayLogin->count . "'],";
            }
            $output .= "[ 'userid' => '" . $lastUser . "', 'logins' => '" . json_encode($loginsArray) . "', 'dayname' => '" . json_encode($daysArray) . "']"; 
            $output2 .=  $lastUser . " => [ 'logins' => '" . json_encode($loginsArray) . "', 'dayname' => '" . json_encode($daysArray) . "']";
        $daynumber = json_encode(['0', '0', '0', '0', '0', '0', '0']);
        $dayname = "'" . json_encode(['dom.', 'lun.', 'mar.', 'mié.', 'jue.', 'vie.', 'sab.']) . "'";
        $month_data_contents = 
'<?php

  class Users_logins_json {

      public static function get_users_logins($userid) {

      $monthdata = [' . $output2 . '];

      if(array_key_exists($userid, $monthdata)){
        return $monthdata[$userid];
      } else {
        return [ "logins" => "[0,0,0,0,0,0,0]", "dayname" => '. $dayname . '];
      }

      }
  }';
          
            file_put_contents($path, $month_data_contents);
      //return $geodata;
  }


  public static function total_logins_data()
    {
        global $DB;

        $formatter = new \IntlDateFormatter(
            'es_ES.utf8',
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE,
            'Europe/Madrid', //more in: https://www.php.net/manual/en/timezones.europe.php
            \IntlDateFormatter::GREGORIAN
        );

        $sql_oracle = "SELECT
        to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(l.timecreated, 'SECOND' ), 'D') AS daynumber, 
        to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(l.timecreated, 'SECOND' ), 'DY') AS dayname, count(DISTINCT l.userid) as logins
        FROM {logstore_standard_log} l WHERE l.eventname LIKE '%loggedin%' 
        AND TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL( l.timecreated, 'SECOND' ) BETWEEN next_day(trunc(sysdate), 'MON') - 365 AND next_day(trunc(sysdate), 'MON')
        GROUP BY to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL( l.timecreated, 'SECOND' ), 'D'), to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(l.timecreated, 'SECOND' ), 'DY')
        ORDER BY to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL( l.timecreated, 'SECOND' ), 'D')";

         $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

        // Execute the SQL query
        $dayLogins = $DB->get_recordset_sql($sql);

        $path = '/moodle/www/local/cuadrodemando/views/getdata/total_logins_json.php';

        foreach ($dayLogins as $dayLogin) {
            $formatDate = new DateTime($dayLogin->dayname);
            $spaname = $formatter->formatObject($formatDate, "E", "es_ES.utf8");

            $dataName[] = $spaname;
            $dataNumber[] = $dayLogin->logins;
        }

        if (empty($dataName)) {
            $json_name = "['lun','mar','mié','jue','vie','sáb','dom']";
            $json_number = "['0','0','0','0','0','0','0']";
        } 
        // Initialize the array to store the data.
$monthdata = '$monthdata';
        $month_data_contents = 
"<?php

class Total_logins_json {

    public static function get_total_logins() {

    $monthdata = [ 'dayname' => '" . json_encode($dataName) . "', 'logins' => '" . json_encode($dataNumber) . "' ];

    return $monthdata;

    }
}";
          
            file_put_contents($path, $month_data_contents);
      //return $geodata;
  }

  public static function total_user_changes()
  {
      global $DB;

      $formatter = new \IntlDateFormatter(
          'es_ES.utf8',
          \IntlDateFormatter::NONE,
          \IntlDateFormatter::NONE,
          'Europe/Madrid', //more in: https://www.php.net/manual/en/timezones.europe.php
          \IntlDateFormatter::GREGORIAN
      );

        // Construct the SQL query
        $sql_registered_mysql = "SELECT COUNT(id) AS userscreated FROM {user} WHERE FROM_UNIXTIME(timecreated, '%d-%m-%Y') = '" . date('d-m-Y') . "'";
        $sql_registered_oracle = "SELECT COUNT(id) AS userscreated FROM {user} WHERE to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(timecreated, 'SECOND'), 'DD-MM-YYYY') = '" . date('d-m-Y') . "'";
        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_registered_oracle : $sql_registered_mysql;
        // Execute the SQL query
        $countCreatedUsers = $DB->count_records_sql($sql, null);

        // Construct the SQL query
        $sql_deleted_mysql = "SELECT COUNT(id) AS userssuspended 
        FROM {user} 
        WHERE FROM_UNIXTIME(timemodified, '%d-%m-%Y') = '" . date('d-m-Y') . "'  AND (suspended = 1 OR deleted = 1)";
        $sql_deleted_oracle = "SELECT COUNT(id) AS userssuspended 
        FROM {user} 
        WHERE to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(timemodified, 'SECOND'), 'DD-MM-YYYY') = '" . date('d-m-Y') . "' AND length(email) > 1 AND length(firstname) > 2 AND length(lastname) > 2 AND (suspended = 1 OR deleted = 1)   AND NOT regexp_like(firstname, '[0-9]') AND NOT regexp_like(username, '[#]') AND NOT regexp_like(lastname, 'Buzón') AND NOT regexp_like(firstname, 'Buzón')";
        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_deleted_oracle : $sql_deleted_mysql;
        // Execute the SQL query
        $countSuspendedUsers = $DB->count_records_sql($sql, null);

        // Construct the SQL query
        $sql_edited_mysql = "SELECT COUNT(id) AS configuredusers FROM {user} WHERE FROM_UNIXTIME(timemodified, '%d-%m-%Y') = '" . date('d-m-Y') . "'";
        $sql_edited_oracle = "SELECT COUNT(id) AS configuredusers 
        FROM {user} 
        WHERE to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(timemodified, 'SECOND'), 'DD-MM-YYYY') = '" . date('d-m-Y') . "' AND length(email) > 1 AND length(firstname) > 2 AND length(lastname) > 2  AND NOT regexp_like(firstname, '[0-9]') AND NOT regexp_like(username, '[#]') AND NOT regexp_like(lastname, 'Buzón') AND NOT regexp_like(firstname, 'Buzón')";
        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_edited_oracle : $sql_edited_mysql;
        // Execute the SQL query
        $countConfiguredUsers = $DB->count_records_sql($sql, null);
      
      $path = '/moodle/www/local/cuadrodemando/views/getdata/total_user_changes_json.php';

      // Initialize the array to store the data.
$userdata = '$userdata';
      $user_data_contents = 
"<?php

class Total_user_changes_json {

  public static function get_total_user_changes() {

    $userdata = [ 'created' => '" . $countCreatedUsers . "', 'deleted' => '" . $countSuspendedUsers . "', 'edited' => '" . $countConfiguredUsers . "'  ];

  return $userdata;

  }
}";
        
          file_put_contents($path, $user_data_contents);
    //return $geodata;
}
}
