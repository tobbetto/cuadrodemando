<?php

/**
 * File containing the adminlte_getData class.
 *
 * @package    adminlte_data
 * @copyright  2023 Thorvaldur Konradsson
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * GetData class.
 *
 * @package    adminlte_data
 * @copyright  2023 Thorvaldur Konradsson
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class adminlte_getdata {

    public $background_color;
    public $pieChartLabel;

    public $pieChartData;

    public static function get_province_sessions($province) {

        global $DB;

        $sql = "SELECT count(userid) AS userid 
                FROM {sessions} s 
                JOIN {user} u ON u.id = s.userid AND u.institution = '{$province}'
                WHERE userid != 0";

        $sessions = $DB->get_record_sql($sql);    

        if ($sessions->userid == 0) {
            echo 'No hay sesiones abiertas';
        } else {
            echo $sessions->userid;
        }
        
    }

    public static function get_province_hourly_views($province) {
        
        global $DB;

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

    public static function get_month_section($month, $year) {

        global $CFG;
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

        $home_calendar = '<!-- /.row -->
        <div class="row">

          <div class="col-12">
            <div class="card card-outline card-indigo">
              <div class="card-header">
                <h3 class="card-title">Navega por los datos del último año</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                    </button>
                </div>
              </div>
                <div class="card-body">
                <ul class="pagination pagination-month justify-content-center">
                  <li class="page-item"><a class="page-link" href="#"></a></li>';
                
                foreach (new DatePeriod($start, $interval, $recurrences, true) as $date) { 

                    $home_calendar .= 
                    '<li class="page-item ';  
                        if ($formatter->formatObject($date, "MM", "es_ES.utf8") == $month) { 
                            $home_calendar .='active ';
                        }; $home_calendar .='">
                      <a class="page-link"  '; $monthobject0 = $formatter->formatObject($date, "MMMM", "es_ES.utf8"); $yearobject = $formatter->formatObject($date, "y", "es_ES.utf8"); $home_calendar .= ' title="Ver estatísticas de ' . $monthobject0 . ' de ' . $yearobject . '" href="' . $CFG->wwwroot . '/adminlte/index.php?month='; $monthobject1 = $formatter->formatObject($date, "MM", "es_ES.utf8"); $home_calendar .= '' . $monthobject1 . '&year=' . $yearobject . '">
                        <p class="page-month">'; $monthobject2 = $formatter->formatObject($date, "MMM", "es_ES.utf8"); $home_calendar .= '' . $monthobject2 . '</p>
                        <p class="page-year">' . $yearobject . '</p>
                      </a>
                    </li>';
                }
                  $home_calendar .= '
                  <li class="page-item"><a class="page-link" href="' . $yearobject++ . '"></a></a></li>
                </ul>
                </div>
            </div>
          </div>

        </div>
        <!-- /.row -->';

        return $home_calendar;
    }

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

    public static function get_user_table($userid, $roleid) {
        global $CFG, $DB;

        if (!empty($userid)) {

            $usersql = $DB->get_record('user', ['id'  => $userid]);
            
            $sql_oracle = "SELECT  c.id, 
                            c.fullname, 
                            c.shortname, 
                            c.startdate, 
                            c.enddate, 
                            CASE WHEN cc.timecompleted = NULL THEN 'No finalizado'
                            ELSE to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(cc.timecompleted, 'SECOND'), 'dd/mm/yyyy Dy', 'nls_date_language=spanish')
                            END AS fechafin
                    FROM {course}           c
                    JOIN {context}          ctx ON c.id = ctx.instanceid
                    JOIN {role_assignments} ra  ON ra.contextid = ctx.id
                    JOIN {user}             u   ON u.id = ra.userid
                    LEFT OUTER JOIN {course_completions} cc ON u.id = cc.userid AND c.id = cc.course
                    WHERE u.id = {$userid} AND (ra.roleid = {$roleid} OR ra.roleid = 1)"; 

            $sql_mysql = "SELECT  c.id, 
                            c.fullname, 
                            c.shortname, 
                            c.startdate, 
                            c.enddate, 
                            CASE WHEN cc.timecompleted = NULL THEN 'No finalizado'
                            ELSE DATE_FORMAT(FROM_UNIXTIME(cc.timecompleted), '%d.%m.%Y')
                            END AS fechafin
                    FROM {course}           c
                    JOIN {context}          ctx ON c.id = ctx.instanceid
                    JOIN {role_assignments} ra  ON ra.contextid = ctx.id
                    JOIN {user}             u   ON u.id = ra.userid
                    LEFT OUTER JOIN {course_completions} cc ON u.id = cc.userid AND c.id = cc.course
                    WHERE u.id = {$userid} AND (ra.roleid = {$roleid} OR ra.roleid = 1)";

            $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

            $studentcourses = $DB->get_records_sql($sql);

            if ($roleid == 3) {
                $count_teacher_sql = "SELECT COUNT(id) AS count FROM {role_assignments} WHERE (roleid = 1 OR roleid = 3) AND userid = {$userid}";
                $count_teacher_courses = $DB->get_record_sql($count_teacher_sql);
            } else {
                $count_student_sql = "SELECT COUNT(id) AS count FROM {role_assignments} WHERE roleid = 5 AND userid = {$userid}";
                $count_student_courses = $DB->get_record_sql($count_student_sql);
            }
            $usertable = '
            <div class="card card-primary card-outline">
                <div class="card-header ui-sortable-handle">
                    <h3 class="card-title">';
                    if ($roleid == 3) {
                        $usertable .= 'Listado de cursos dónde <b>' . $usersql->firstname . ' ' . $usersql->lastname . '</b> es docente o gestor. En total: <b>' . $count_teacher_courses->count . '</b>';
                    } else {
                        $usertable .= 'Listado de cursos dónde <b>' . $usersql->firstname . ' ' . $usersql->lastname . '</b> es estudiante En total: <b>' . $count_student_courses->count . '</b>';
                    }
            $usertable .= '</h3>
                    <div class="card-tools">
                    <a href="' . $CFG->wwwroot . '/adminlte/users">
                        <button type="button" class="btn btn-primary btn-sm" data-card-widget="back" title="Atrás a listado completo de usuarios" href="' . $CFG->wwwroot . '/adminlte/users" >
                                <i class="fas fa-solid fa-circle-left"></i>
                        </button>
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="usertable" class="table table-bordered table-striped dt-responsive compact">
               <div id="exportbuttons" style="padding-bottom:0.5em;"><div>
            <thead>

                <tr>
                    <th>ID</th>
                    <th>Nombre largo</th>
                    <th>Nombre corto</th>
                    <th>Fecha inicio curso</th>
                    <th>Fecha fin del curso</th>';
                    if ($roleid == 5) {
                        $usertable .= '<th>Fecha finalización</th>';
                    }
            $usertable .= '</tr>
            </thead>
            <tbody>';
            foreach ($studentcourses as $studentcourse) {
            
                $usertable .= '<tr>';
                $usertable .= '<td><a href="' . $CFG->wwwroot . '/adminlte/courses?courseid=' . $studentcourse->id .  '" title="Haz clic aquí para ver los estudiantes matriculados">' . $studentcourse->id . '</a></td>';
                $usertable .= '<td>' . $studentcourse->fullname . '</a></td>';
                $usertable .= '<td>' . $studentcourse->shortname . '</a></td>';
                $usertable .= '<td>' . date('d.m.Y', $studentcourse->startdate) . '</a></td>'; 
                $usertable .= '<td>' . date('d.m.Y', $studentcourse->enddate) . '</a></td>';
                    if ($roleid == 5) {
                        $usertable .= '<td>' . $studentcourse->fechafin . '</a></td>';
                    }
                $usertable .= '</tr>';
                
            } 
            $usertable .= '</tbody>
            </table>
            </div>
            <!-- /.card-body -->
          </div><!-- /.card -->';


        } else {

            $fields_oracle = array('id', 'username', 'firstname', 'lastname', 'email', 'city', 'department', 'address', 'institution');
            $fields_mysql = array('id', 'username', 'firstname', 'lastname', 'email', 'city', 'department', 'address', 'institution');
            $fields = ($DB->get_dbfamily() === 'oracle') ? $fields_oracle : $fields_mysql;
            $sql = "SELECT " . implode(", ", $fields) . " FROM {user} WHERE deleted = 0 AND suspended = 0 AND id > 2 AND length(email) > 1 AND length(firstname) > 2 AND length(lastname) > 2 AND NOT regexp_like(firstname, '[0-9]') AND NOT regexp_like(username, '[#]') AND NOT regexp_like(lastname, 'Buzón') AND NOT regexp_like(firstname, 'Buzón') ORDER BY username DESC"; //  AND (id > 109040 OR id < 5) 
            //$sql = "Select * from cdm_user_table";
            $users = $DB->get_records_sql($sql);

        $usertable = '
        <div class="card card-primary card-outline">
                <div class="card-header ui-sortable-handle">
                    <h3 class="card-title">Usuarios de la plataforma</h3>
                    <div class="card-tools">
                    <a href="' . $CFG->wwwroot . '/adminlte/users">
                        <!-- <button type="button" class="btn btn-primary btn-sm" data-card-widget="back" title="Atrás" href="' . $CFG->wwwroot . '/adminlte/users" >
                                <i class="fas fa-solid fa-circle-left"></i>
                        </button> -->
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="usertable" class="table table-bordered table-striped responsive compact" >
               <div id="exportbuttons" style="padding-bottom:0.5em;"><div>
        <thead>
        <tr>
          <th>Identifiación</th>
          <th>Nombre</th>
          <th>email</th>
          <th>Ciudad</th>
          <th>Departamento</th>
          <th>Provincia</th>
          <th>Dirección</th>
        </tr>
        </thead>
        <tbody>';
        foreach ($users as $user) {
            
            $usertable .= '<tr>';
            $usertable .= '<td><a href="' . $CFG->wwwroot . '/adminlte/users?userid=' . $user->id .  '&roleid=5" title="Haz click aquí para ver su información detallada">' . $user->username . '</a></td>';
            $usertable .= '<td>' . $user->firstname . ' ' . $user->lastname . '</td>';
            $usertable .= '<td><a href="mailto:' . $user->email .  '" title="Enviar email a la persona">' . $user->email . '</a></td>';
            $usertable .= '<td>' . $user->city . '</td>';
            $usertable .= '<td>' . $user->institution . '</td>';
            $usertable .= '<td>' . $user->department . '</td>';
            $usertable .= '<td>' . $user->address . '</td>';
            $usertable .= '</tr>';
            

        } 
        $usertable .= '</tbody></table>
        </div>
        <!-- /.card-body -->
      </div><!-- /.card -->';
        //echo $usertable;
    }
        return $usertable;

    }

    public static function generate_login_count($id) {
        global $DB;
              
            $sql_oracle = "SELECT to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(l.timecreated, 'SECOND' ), 'D') AS daynumber, 
                    to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(l.timecreated, 'SECOND' ), 'DY') AS dayname,  
                    COUNT("; 
                    if (empty($id)) { 
                        $sql_oracle .= "DISTINCT "; 
                    }
                    $sql_oracle .= "userid) AS logins
                    FROM {logstore_standard_log} l WHERE l.eventname LIKE '%loggedin%' " ;
                    if (!empty($id)) {
                        $sql_oracle .= "AND l.userid = {$id} ";
                    } 
                    $sql_oracle .= "AND TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL( l.timecreated, 'SECOND' ) BETWEEN next_day(trunc(sysdate), 'MON') - 365 AND next_day(trunc(sysdate), 'MON')
                    GROUP BY to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL( l.timecreated, 'SECOND' ), 'D'), to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(l.timecreated, 'SECOND' ), 'DY')
                    ORDER BY to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL( l.timecreated, 'SECOND' ), 'D')";

            $sql_mysql = "SELECT DATE_FORMAT(FROM_UNIXTIME(l.timecreated), '%w') AS daynumber, 
                    DATE_FORMAT(FROM_UNIXTIME(l.timecreated), '%a') AS dayname,  
                    COUNT("; 
                    if (empty($id)) { 
                        $sql_mysql .= "DISTINCT "; 
                    }
                    $sql_mysql .= "userid) AS logins
                    FROM {logstore_standard_log} l WHERE l.eventname LIKE '%loggedin%' " ;
                    if (!empty($id)) {
                        $sql_mysql .= "AND l.userid = {$id} ";
                    } 
                    $sql_mysql .= "AND FROM_UNIXTIME(l.timecreated) >= CURDATE() - INTERVAL 1 YEAR
                    GROUP BY DATE_FORMAT(FROM_UNIXTIME(l.timecreated), '%w'), DATE_FORMAT(FROM_UNIXTIME(l.timecreated), '%a')
                    ORDER BY DATE_FORMAT(FROM_UNIXTIME(l.timecreated), '%w')";

            $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

            // Execute the SQL query
            $dayLogins = $DB->get_records_sql($sql, null);

            $formatter = new \IntlDateFormatter(
                'es_ES.utf8',
                \IntlDateFormatter::NONE,
                \IntlDateFormatter::NONE,
                'Europe/Madrid', //more in: https://www.php.net/manual/en/timezones.europe.php
                \IntlDateFormatter::GREGORIAN
            );

            foreach ($dayLogins as $dayLogin) {
                $formatDate = new DateTime($dayLogin->dayname);
                $spaname = $formatter->formatObject($formatDate, "E", "es_ES.utf8");

                $dataName[] = $spaname;
                $dataNumber[] = $dayLogin->logins;
            }
            
            

            if (empty($dataName)) {
                $json_name = "['lun','mar','mié','jue','vie','sáb','dom']";
                $json_number = "['0','0','0','0','0','0','0']";
            } else {
                
                $json_name = json_encode($dataName);
                $json_number = json_encode($dataNumber);
            }
            // Initialize the array to store the data.

            // Convert the data array to JSON for visualization or storage purposes.
            $jsonData = ['dayname' => $json_name, 'logins' => $json_number];

          // Print the JSON data or do something else with it.
          return $jsonData;
    
    }

    public static function get_yearly_courses($courseid) {

        global $DB, $CFG, $OUTPUT, $PAGE;
        $yearlyCoursesTable = '';


        if (empty($courseid)) {
    
        /*    <section class="col-lg-12 connectedSortable">
                <!-- Default box -->
                <div class="card card-outline card-success">
                    <div class="card-header">
                    <h3 class="card-title" id="courses_table">Listado de cursos (' . date('Y') . ') </h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                        </button>
                    </div>
                    </div>
                    <div class="card-body p-0">
                <table class="table table-striped projects"> */



                
                $yearlyCoursesTable .= '<section class="col-lg-12 connectedSortable">
                    <!-- Default box -->
                    <div class="card card-outline card-success">
                        <div class="card-header">
                        <h3 class="card-title" id="courses_table">Listado de cursos (' . date('Y') . ') </h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                            </button>
                        </div>
                        </div>
                        <div class="card-body p-0">
                        <table id="enroltable" class="table table-bordered table-striped overflow-auto responsive compact">
                            <div id="exportbuttons" style="padding-bottom:0.5em; padding-left:0.5em;"><div>
                        <thead>
                            <tr>
                                <th style="width: 1%">
                                    ID
                                </th>
                                <th style="width: 30%">
                                    Nombre largo
                                </th>
                                <th style="width: 10%">
                                    Nombre corto
                                </th>
                                <th style="width: 18%">
                                    Formador(es)
                                </th>
                                <th style="width: 6%">
                                    # de alumnos
                                </th>
                                <th  style="width: 7%">
                                    # Finalizados
                                </th>
                                <th  style="width: 7%">
                                    % Finalizados
                                </th>
                                <th style="width: 6%" class="text-center">
                                    Estatus
                                </th>
                                <th style="width: 15%" class="text-right">
                                    Gestionar en Moodle
                                </th>
                            </tr>
                        </thead>
                        <tbody>';

                        //    $sql_oracle = "SELECT * FROM {course} WHERE visible = 1 AND id > 1 AND to_char(TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + numtodsinterval(timemodified, 'SECOND'), 'YYYY') = '" . date('Y') . "'";
                        //    $sql_mysql = "SELECT * FROM {course} WHERE visible = 1 AND id > 1 AND FROM_UNIXTIME(startdate, '%Y') = '" . date('Y') . "'";
                        //    $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;
                        //    $yearlyCourses = $DB->get_records_sql($sql);

                            $params = [
                                'current_year' => date('Y'),
                                'visible' => 1,
                                'id_greater_than' => 1,
                            ];
                            
                            if ($DB->get_dbfamily() === 'oracle') {
                                $sql = "
                                    SELECT *
                                    FROM {course}
                                    WHERE
                                        visible = :visible
                                        AND id > :id_greater_than
                                        AND TO_CHAR(
                                            TO_TIMESTAMP('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timemodified, 'SECOND'),
                                            'YYYY'
                                        ) = :current_year
                                ";
                            } else {
                                $sql = "
                                    SELECT *
                                    FROM {course}
                                    WHERE
                                        visible = :visible
                                        AND id > :id_greater_than
                                        AND YEAR(FROM_UNIXTIME(startdate)) = :current_year
                                ";
                            }
                            
                            $yearlyCourses = $DB->get_records_sql($sql, $params);

                        foreach ($yearlyCourses as $yearlyCourse):
                            $yearlyCoursesTable .= '<tr>
                                <td>'
                                    . $yearlyCourse->id .
                                '</td>
                                <td>'
                                    . $yearlyCourse->fullname .
                                '</td>
                                <td>
                                    <a href="' . $CFG->wwwroot . '/adminlte/courses?courseid=' . $yearlyCourse->id . '" title="Ver detalle del curso"> ' . $yearlyCourse->shortname . '</a>
                                    <br/>
                                    <small>
                                    Fecha inicio: ' . date('d-m-Y', $yearlyCourse->startdate) . '<br />
                                    Fecha fin: ' . date('d-m-Y', $yearlyCourse->enddate) . 
                                    '</small>
                                </td>
                                <td>
                                    <ul class="list-inline">';

                                 /*   $sql = "SELECT DISTINCT(firstname) as teacher, u.id as id, picture, firstname, lastname, firstnamephonetic, lastnamephonetic, middlename, alternatename, imagealt, email
                                    FROM {course} ic
                                    JOIN {context} con ON con.instanceid = ic.id
                                    JOIN {role_assignments} ra ON con.id = ra.contextid AND con.contextlevel = 50
                                    JOIN {role} r ON ra.roleid = r.id
                                    JOIN {user} u ON u.id = ra.userid
                                    WHERE r.id = 3 AND ic.id = " . $yearlyCourse->id . "";

                                    $courseTeachers = $DB->get_records_sql($sql); */

                                    // Get the list of teachers for the given course ID.
                                    $teacherSql = "SELECT 
                                                        DISTINCT(firstname) as teacher, 
                                                        u.id as id, 
                                                        picture, 
                                                        firstname, 
                                                        lastname, 
                                                        firstnamephonetic, 
                                                        lastnamephonetic, 
                                                        middlename, 
                                                        alternatename, 
                                                        imagealt, 
                                                        email
                                                    FROM {course} ic
                                                    JOIN {context} con ON con.instanceid = ic.id
                                                    JOIN {role_assignments} ra ON con.id = ra.contextid AND con.contextlevel = 50
                                                    JOIN {role} r ON ra.roleid = r.id
                                                    JOIN {user} u ON u.id = ra.userid
                                                    WHERE (r.id = :managerid OR r.id = :teacherid) AND ic.id = :courseid";

                                    $params = [
                                    'managerid' => 1,
                                    'teacherid' => 3,
                                    'courseid' => $yearlyCourse->id,
                                    ];

                                    $courseTeachers = $DB->get_records_sql($teacherSql, $params);

                                    $yearlyCoursesTable .= '<li class="list-inline-item">';
                                        foreach($courseTeachers as $courseTeacher) :

                                            $yearlyCoursesTable .= $OUTPUT->user_picture($courseTeacher, ['size' => 35, 'class' => 'userpicture']) . ' <a href="' . $CFG->wwwroot . '/adminlte/users?userid=' . $courseTeacher->id . '&roleid=3" title="Ver detalle del ponente" > ' . $courseTeacher->firstname . ' ' . $courseTeacher->lastname . '</a> ' . '<br />';
                                        
                                        endforeach;
                                    $yearlyCoursesTable .= '</li>
                                    </ul>
                                </td>';

                                    $sql = "SELECT COUNT(course.id) AS Students
                                            FROM {role_assignments} asg
                                            JOIN {context} context ON asg.contextid = context.id AND context.contextlevel = 50
                                            JOIN {user} u ON u.id = asg.userid
                                            JOIN {course} course ON context.instanceid = course.id
                                            WHERE asg.roleid = 5
                                            AND course.id = " . $yearlyCourse->id . "
                                            GROUP BY course.id
                                            ORDER BY Students DESC";

                                    $completeEnroled = $DB->get_record_sql($sql, null, 'IGNORE_MISSING');

                                    $sql = "SELECT COUNT(cc.timecompleted) AS finalizado
                                            FROM {role_assignments} ra
                                            JOIN {context} context ON context.id = ra.contextid AND context.contextlevel = 50
                                            JOIN {course} c ON c.id = context.instanceid AND c.id = " . $yearlyCourse->id . "
                                            JOIN {user} u ON u.id = ra.userid
                                            JOIN {course_completions} cc ON cc.course = c.id AND cc.userid = u.id";

                                    $completePercent = $DB->get_record_sql($sql, null, 'IGNORE_MISSING');

                                    if (empty($completeEnroled->students)) {
                                        $percentage = 0;
                                    } else {
                                        $percentage = round(($completePercent->finalizado / $completeEnroled->students) * 100);
                                    }

                                $yearlyCoursesTable .=  '
                                <td>' . $completeEnroled->students . '</td>
                                <td>' . $completePercent->finalizado . '</td>
                                <td class="project_progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar ';
                                        if(round($percentage) == 100) :
                                            $yearlyCoursesTable .= ' bg-green ';
                                        elseif(round($percentage) < 50) :
                                            $yearlyCoursesTable .= ' bg-danger ';
                                        else :
                                            $yearlyCoursesTable .= ' bg-warning ';
                                        endif;
                                            $yearlyCoursesTable .= ' progress-bar-striped" role="progressbar" aria-valuenow="' . round($percentage) . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . round($percentage) . '%">
                                        </div>
                                    </div>
                                    <small>';
                                    if (empty($completeEnroled->students)) :
                                        $yearlyCoursesTable .= '0 matriculados';
                                        else :
                                            $yearlyCoursesTable .= $percentage . '%';
                                        endif;
                                        $yearlyCoursesTable .= '</small>
                                </td>
                                <td class="project-state">';
                                if ($yearlyCourse->startdate < $yearlyCourse->enddate) :
                                    if ($yearlyCourse->startdate > time()) : 
                                        $yearlyCoursesTable .= '<span class="badge badge-warning">No empezado</span>';
                                    elseif ($yearlyCourse->enddate < time()) :
                                        $yearlyCoursesTable .= '<span class="badge badge-danger">Finalizado</span>';
                                    else :
                                        $yearlyCoursesTable .= '<span class="badge badge-success">Activo</span>';
                                    endif;
                                else :
                                    $yearlyCoursesTable .= '<span class="badge badge-warning">Sin fecha fin</span>';
                                endif;
                                $yearlyCoursesTable .= '</td>
                                <td class="project-actions text-right">
                                    <a class="btn btn-primary btn-sm" href="' . $CFG->wwwroot . '/course/view.php?id=' . $yearlyCourse->id . '" title="Ver curso"  target="_blank">
                                        <i class="fas fa-eye">
                                        </i>
                                    </a>
                                    <a class="btn btn-info btn-sm" href="' . $CFG->wwwroot . '/course/edit.php?id=' . $yearlyCourse->id . '" title="Configurar curso"  target="_blank">
                                        <i class="fas fa-sliders">
                                        </i>
                                    </a>
                                    <a class="btn btn-success btn-sm" href="' . $CFG->wwwroot . '/user/index.php?id=' . $yearlyCourse->id . '" title="Ver matriculados"  target="_blank">
                                        <i class="fas fa-user-graduate">
                                        </i>
                                    </a>
                                </td>
                            </tr>';
                            endforeach;
                            $yearlyCoursesTable .= '</tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            </section>';
            
                } else {

                    $course = $DB->get_record('course', [ 'id' => $courseid]);
                    $sql_oracle = "SELECT
                                    u.id
                                    , u.username
                                    , u.firstname
                                    , u.lastname
                                    , u.email
                                    , c.fullname as coursename
                                    , u.department as department
                                    , to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(c.enddate, 'SECOND' ), 'DD.MM.YYYY') AS enddate
                                    , to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(ra.timemodified, 'SECOND' ), 'DD.MM.YYYY') AS enrolled
                                    , to_char(TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(cc.timecompleted, 'SECOND' ), 'DD.MM.YYYY') AS completed
                                    FROM {role_assignments}    ra
                                    JOIN {context}             ctx ON ctx.id    = ra.contextid   AND ctx.contextlevel = 50
                                    JOIN {course}              c   ON c.id      = ctx.instanceid AND c.id             = {$courseid}
                                    JOIN {user}                u   ON u.id      = ra.userid
                                    LEFT JOIN {course_completions}  cc  ON cc.course = c.id           AND cc.userid        = u.id
                                    WHERE ra.roleid = 5";

                    $sql_mysql = "SELECT
                                    u.id
                                    , u.username
                                    , u.firstname
                                    , u.lastname
                                    , u.email
                                    , c.fullname as coursename
                                    , u.department as department
                                    , DATE_FORMAT(FROM_UNIXTIME(c.enddate), '%d.%m.%Y') AS enddate
                                    , DATE_FORMAT(FROM_UNIXTIME(ra.timemodified), '%d.%m.%Y') AS enrolled
                                    , DATE_FORMAT(FROM_UNIXTIME(cc.timecompleted), '%d.%m.%Y') AS completed
                                    FROM {role_assignments}    ra
                                    JOIN {context}             ctx ON ctx.id    = ra.contextid   AND ctx.contextlevel = 50
                                    JOIN {course}              c   ON c.id      = ctx.instanceid AND c.id             = {$courseid}
                                    JOIN {user}                u   ON u.id      = ra.userid
                                    LEFT JOIN {course_completions}  cc  ON cc.course = c.id           AND cc.userid        = u.id
                                    WHERE ra.roleid = 5";

                    $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

                    $enrolledUsers = $DB->get_records_sql($sql);

                    $yearlyCoursesTable = '
                    <section class="col-lg-12 connectedSortable">
                    <!-- Default box -->
                    <div class="card card-outline card-success">
                        <div class="card-header">
                        <h3 class="card-title" id="courses_table">Matriculados en el curso: <strong>' . $course->fullname . '</strong></h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                            </button>
                        </div>
                        </div>
                        <div class="card-body p-0">
                        <table id="enroltable" class="table table-bordered table-striped dt-responsive compact">
                            <div id="exportbuttons" style="padding-bottom:0.5em; padding-left:0.5em;"><div>
                            <thead>
                                <tr>
                                    <th>' . $course->fullname . '</th>
                                    <th colspan="2"><a href=https://formaciononline.sepe.es/adminlte/courses?courseid=' . $course->id . '>Ver en el Cuadro de Mando</a></th>
                                    <th colspan="3"><a href=https://formaciononline.sepe.es/course/view.php?id=' . $course->id . '>Ver en Moodle</a></th>
                                </tr>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Nombre</th>
                                    <th>email</th>
                                    <th>Departamento</th>
                                    <th>Fecha matriculación</th>
                                    <th>Fecha fin curso</th>
                                    <th>Curso finalizado</th>
                                </tr>
                            </thead>
                                <tbody>';
                                    foreach ($enrolledUsers as $enrolledUser) :
                    $yearlyCoursesTable .= '
                                    
                                <tr>
                                    <td><a href="' . $CFG->wwwroot . '/adminlte/users?userid=' . $enrolledUser->id . '&roleid=5" title="Ver detalle del estudiante">' . $enrolledUser->username . '</a></td>
                                    <td>' . $enrolledUser->firstname . ' ' . $enrolledUser->lastname . '</td>
                                    <td><a href="mailto:' .  $enrolledUser->email . '" title="Enviar email" >' . $enrolledUser->email . '</a</td>
                                    <td>' . $enrolledUser->department . '</td>
                                    <td>' . $enrolledUser->enrolled . '</td>
                                    <td>' . $enrolledUser->enddate . '</td>
                                    <td>' . $enrolledUser->completed . '</td>
                                </tr>';
                                    endforeach;
                    $yearlyCoursesTable .= '
                                </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    </section>';
                }
                return $yearlyCoursesTable;
     }

public static function get_course_enrolments($courseid) {
    global $DB;

    $sql = "SELECT
            COALESCE(u.department, u.institution, 'Sin institucion') AS institution,
            SUM(CASE WHEN cc.timecompleted IS NULL THEN 1 ELSE 0 END) AS not_completed,
            SUM(CASE WHEN cc.timecompleted IS NOT NULL AND cc.timestarted > 0 THEN 1 ELSE 0 END) AS completed
        FROM {role_assignments} ra
        JOIN {context} ctx ON ctx.id = ra.contextid AND ctx.contextlevel = 50
        JOIN {course} c ON c.id = ctx.instanceid AND c.id = :courseid
        JOIN {user} u ON u.id = ra.userid
        LEFT JOIN {course_completions} cc ON cc.course = c.id AND cc.userid = u.id
        WHERE ra.roleid = 5
        GROUP BY COALESCE(u.department, u.institution, 'Sin institucion')
        ORDER BY completed DESC";

    $params = ['courseid' => $courseid];
    $records = $DB->get_records_sql($sql, $params, 0, 20);

    $category_name = [];
    $count_course = [];
    $number_not_completed = [];

    foreach ($records as $record) {
        $category_name[] = $record->institution;
        $count_course[] = (int)$record->completed;
        $number_not_completed[] = (int)$record->not_completed;
    }

    return [
        'name' => json_encode($category_name),
        'count' => json_encode($count_course),
        'students' => json_encode($number_not_completed)
    ];
}


/**
 * 
 */
public static function get_course_enrolments_old($courseid) {

    global $DB;
    $category_name = [];
    $count_course = [];

            $sql = "SELECT
            u.department AS institution
            ,COUNT(u.department) AS institutions
            FROM {role_assignments}    ra
            JOIN {context}             ctx ON ctx.id    = ra.contextid   AND ctx.contextlevel = 50
            JOIN {course}              c   ON c.id      = ctx.instanceid AND c.id             = {$courseid}
            JOIN {user}                u   ON u.id      = ra.userid
            LEFT JOIN {course_completions}  cc  ON cc.course = c.id      AND cc.userid        = u.id
            WHERE ra.roleid = '5' AND cc.timecompleted IS NULL AND timestarted > 0
            GROUP BY u.department
            ORDER BY institutions DESC";

        $countinstitutions1 = $DB->get_records_sql($sql, null, 0, 20);

        $sql = "SELECT
        u.department AS institution
        ,COUNT(u.department) AS institutions
        FROM {role_assignments}    ra
        JOIN {context}             ctx ON ctx.id    = ra.contextid   AND ctx.contextlevel = 50
        JOIN {course}              c   ON c.id      = ctx.instanceid AND c.id             = {$courseid}
        JOIN {user}                u   ON u.id      = ra.userid
        LEFT JOIN {course_completions}  cc  ON cc.course = c.id      AND cc.userid        = u.id
        WHERE ra.roleid = '5' AND cc.timecompleted IS NOT NULL AND timestarted > 0
        GROUP BY u.department
        ORDER BY institutions DESC";

        $countinstitutions = $DB->get_records_sql($sql, null, 0, 20);

        $sql = "SELECT COUNT(course.id) AS students, u.institution
        FROM {role_assignments} asg
        JOIN {context} context ON asg.contextid = context.id AND context.contextlevel = 50
        JOIN {user} u ON u.id = asg.userid
        JOIN {course} course ON context.instanceid = course.id
        WHERE asg.roleid = 5
        AND course.id = {$courseid}
        GROUP BY course.id, u.institution
        ORDER BY students DESC";

    $completeEnroled = $DB->get_record_sql($sql, null, 'IGNORE_MISSING');    

    foreach ( $countinstitutions as $index => $countinstitution) {

            $category_name[] .= $countinstitution->institution;
            $count_course[] .=  $countinstitution->institutions;
            $number_not_completed[] .= $countinstitutions1[$index]->institutions;
            //$number_completed[] .= $countinstitution->usr;
    }

    //$results = ['name' => json_encode($category_name), 'count' => json_encode($count_course)];
    $results = ['name' => json_encode($category_name), 'count' => json_encode($count_course), 'students' => json_encode($number_not_completed)];

    return $results;

}

    public static function get_course_times($courseid) {
        global $DB;
        $time = [];
        $avg = [];

        $sql_oracle_avg = "SELECT
                            AVG(ROUND((cc.timecompleted-ra.timemodified)/(24*60*60), 2)) AS avg
                            FROM {role_assignments}    ra
                            JOIN {context}             ctx ON ctx.id    = ra.contextid   AND ctx.contextlevel = 50
                            JOIN {course}              c   ON c.id      = ctx.instanceid AND c.id             = {$courseid}
                            JOIN {user}                u   ON u.id      = ra.userid
                            LEFT JOIN {course_completions}  cc  ON cc.course = c.id           AND cc.userid        = u.id
                            WHERE ra.roleid = 5 AND cc.timecompleted > 0";
        
        $sql_oracle_time = "SELECT
                            ROUND((cc.timecompleted-ra.timemodified)/(24*60*60), 2) AS timecompleted
                            FROM {role_assignments}    ra
                            JOIN {context}             ctx ON ctx.id    = ra.contextid   AND ctx.contextlevel = 50
                            JOIN {course}              c   ON c.id      = ctx.instanceid AND c.id             = {$courseid}
                            JOIN {user}                u   ON u.id      = ra.userid
                            LEFT JOIN {course_completions}  cc  ON cc.course = c.id           AND cc.userid        = u.id
                            WHERE ra.roleid = 5 AND cc.timecompleted > 0
                            ORDER BY timecompleted ASC";

//        $sql_avg = "SELECT AVG(ROUND((timecompleted-timestarted)/(24*60*60), 2)) AS avg
//        FROM {course_completions}
//        WHERE timestarted > 0 AND course = {$courseid}";
// var_dump($sql_avg);
//        $sql_mysql = "SELECT id, ROUND((timecompleted-timestarted)/(24*60*60), 2) AS timecompleted
//        FROM {course_completions}
//        WHERE timestarted > 0 AND course = {$courseid}
//        ORDER BY timecompleted ASC";
// var_dump($sql_avg);
 //       $avgtime = $DB->get_record_sql($sql_avg);
        $avgtime = $DB->get_record_sql($sql_oracle_avg);
        $timecompleted = $DB->get_records_sql($sql_oracle_time);

        foreach ($timecompleted as $timecomplete) {

            $time[] .= $timecomplete->timecompleted;
            $avg[] .=  $avgtime->avg;
         
        }

        if (count($time) == 1) {
            $time = [$timecomplete->timecompleted, $timecomplete->timecompleted];
            $avg =  [ $avgtime->avg, $avgtime->avg];
        }

        $results = ['time' => json_encode($time), 'avg' => json_encode($avg)];
        
        return $results;
    }

    public static function get_site_times() {
        global $DB;
        $course = [];
        $avg = [];
        $avg1 = [];
        $avgavg = [];
        $params = [
            'current_year' => date('Y'),
        ];

        $sql_oracle_avg = "SELECT
        cc.course as course, c.shortname as shortname,
        AVG(ROUND((cc.timecompleted-ra.timemodified)/(24*60*60), 2)) AS avg
        FROM {role_assignments}    ra
        JOIN {context}             ctx ON ctx.id    = ra.contextid   AND ctx.contextlevel = 50
        JOIN {course}              c   ON c.id      = ctx.instanceid
        JOIN {user}                u   ON u.id      = ra.userid
        LEFT JOIN {course_completions}  cc  ON cc.course = c.id      AND cc.userid        = u.id
        WHERE extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timestarted, 'SECOND')) = :current_year AND cc.timecompleted > 0
        GROUP BY course, shortname
        ORDER BY course ASC";

        $sql_oracle = "SELECT cc.course as course, c.shortname as shortname, AVG(ROUND((timecompleted-timestarted)/(24*60*60), 2)) AS avg
        FROM {course_completions} cc
        JOIN {course} c ON c.id = cc.course
        WHERE extract(year from TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timestarted, 'SECOND')) = :current_year
        GROUP BY course, shortname
        ORDER BY course ASC";

        $sql_mysql = "SELECT cc.course as course, AVG(ROUND((timecompleted-timestarted)/(24*60*60), 2)) AS avg, c.shortname as shortname
        FROM {course_completions} cc
        JOIN {course} c ON c.id = cc.course
        WHERE YEAR(FROM_UNIXTIME(timestarted)) = :current_year
        GROUP BY course
        ORDER BY course ASC";

        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle_avg : $sql_mysql;
        $avgtimes = $DB->get_records_sql($sql, $params);

        foreach ($avgtimes as $row) {
            $avg1[] .=  $row->avg;
        }

        foreach ($avgtimes as $avgtime) {

            $course[] .= $avgtime->shortname;
            $avg[] .=  $avgtime->avg;
            $avgavg[] .= array_sum($avg1)/count($avg1); // Hay que mejorar este average. Que devuleva el avg total de los cursos y no el avg acumulado ["22,3","34,5","45,6"] .> ["45,6","45,6","45,6"]
            
        }

        if (count($course) == 1) {
            $course = [$avgtime->shortname, $avgtime->shortname];
            $avg =  [ $avgtime->avg, $avgtime->avg];
        }
        
        $results = ['course' => json_encode($course), 'avg' => json_encode($avg), 'avgavg' => json_encode($avgavg)];
        
        return $results;
    }

public static function get_category_name_number() {
    global $DB;
    $category_name = [];
    $count_course = [];

    $fields = array('id', 'name', 'coursecount', 'parent');
    $sql = "SELECT " . implode(", ", $fields) . " FROM {course_categories} WHERE visible = 1 AND coursecount > 0 ORDER BY coursecount DESC";
    $allcategories = $DB->get_records_sql($sql, null, 0, 8);

    foreach ($allcategories as $cat) {
        // Get parent and grandparent.
        $parent = $cat->parent ? $DB->get_record('course_categories', ['id' => $cat->parent], 'id, name, parent') : null;
        $grandparent = ($parent && $parent->parent) ? $DB->get_record('course_categories', ['id' => $parent->parent], 'id, name') : null;

        // Build the path string.
        $parts = [];
        if ($grandparent) $parts[] = $grandparent->name;
        if ($parent) $parts[] = $parent->name;
        $parts[] = $cat->name;
        $category_name[] = implode(' / ', $parts);

        $count_course[] = $cat->coursecount;
    }

    $results = ['name' => json_encode($category_name), 'count' => json_encode($count_course)];
    return $results;
}


    public static function get_category_name_number_2() {
        global $DB;
        $category_name = [];
        $count_course = [];

        $fields = array('id', 'name', 'coursecount');
        $sql = "SELECT " . implode(", ", $fields) . " FROM {course_categories} WHERE coursecount > 0 ORDER BY coursecount DESC";
        $allcategories = $DB->get_records_sql($sql, null, 0, 8);

        foreach ($allcategories as $allcategory) {

            $category_name[] .= $allcategory->name;
            $count_course[] .=  $allcategory->coursecount;
        }

        $results = ['name' => json_encode($category_name), 'count' => json_encode($count_course)];
        
        return $results;
    }

    public function get_category_numbers($courseid) {
        
        global $DB;

        if (empty($courseid)) {

            $categoryNumbersSection = 
                '<section class="col-lg-6 connectedSortable">
                <!-- Custom tabs (Charts with tabs)-->
                <div class="card card-outline card-success" style="height: 98%;">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-chart-pie mr-1"></i>
                      Categorías y tiempos
                    </h3>
                    <div class="card-tools">
                      <ul class="nav nav-pills ml-auto">
                        <li class="nav-item">
                          <a class="nav-link active" href="#geo-chart" data-bs-toggle="tab" role="tab">Categorías</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#time-chart" data-bs-toggle="tab" role="tab">Tiempos</a>
                        </li>
                      </ul>
                    </div>
                  </div><!-- /.card-header -->
                  <div class="card-body">
                    <div class="tab-content p-0">
                      <!-- Morris chart - Sales -->
                      <div class="chart tab-pane active" id="geo-chart"
                           style="position: relative; height: 350px;">
                          <canvas id="geo-chart-canvas" height="350" style="height: 350px;"></canvas>
                       </div>
                      <div class="chart tab-pane" id="time-chart" style="position: relative; height: 350px;">
                        <canvas id="time-chart-canvas" height="350" style="height: 350px;"></canvas>
                      </div>
                    </div>
                  </div><!-- /.card-body -->';

                    $sql = "SELECT SUM(coursecount) AS countcourses FROM {course_categories} ORDER BY coursecount";
                    $totalCourses = $DB->get_record_sql($sql, null, 0, 4);

                    $fields = array('id', 'name', 'coursecount');
                    $sql = "SELECT " . implode(", ", $fields) . " FROM {course_categories} WHERE coursecount > 0 ORDER BY coursecount DESC";
                    $popularcategories = $DB->get_records_sql($sql, null, 0, 4);

                    $categoryNumbersSection .= '<div class="card-footer bg-transparent">
                    <div class="row mb-2">% del total de los cursos de la plataforma</div>
                    <div class="row">';

                    foreach ($popularcategories as $category) :

                        $percent = round($category->coursecount * 100 / $totalCourses->countcourses, 1) . '%';

                    $categoryNumbersSection .= '<div class="col-6 col-md-3 text-center">
                        <input type="text" class="knob" data-readOnly="true" data-skin="tron" data-thickness="0.2" value="' . $percent . '" data-width="90" data-height="90"
                                data-fgColor="#28a745">

                        <div class="knob-label"><a href="/course/management.php?categoryid=' . $category->id . '"  target="_blank" style="color:#28a745;" title="Ir a la categoría en la plataforma"> ' . $category->name . '</a></div>
                        </div>';

                    endforeach;
                    $categoryNumbersSection .= '</div>
                    <!-- /.row -->
                    </div>
                    <!-- /.card-footer -->';
                } else {

            $categoryNumbersSection = 
                '<section class="col-lg-6 connectedSortable">
                <!-- solid sales graph -->
                <div class="card card-outline card-success mh-98"  style="height: 98%;">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-map mr-1"></i>
                            Geografía y tiempos del curso
                        </h3>

                        <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                            <a class="nav-link active" href="#stackedBarChart" data-bs-toggle="tab" role="tab">Geografía</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="#time-chart" data-bs-toggle="tab" role="tab">Tiempos</a>
                            </li>
                        </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0">
                        <!-- Morris chart - Sales -->
                        <div class="chart tab-pane active" id="stackedBarChart"
                            style="position: relative; height: 350px;">
                            <canvas id="stackedBarChart-canvas" height="350" style="height: 350px;"></canvas>
                        </div>
                        <div class="chart tab-pane" id="time-chart" style="position: relative; height: 350px;">
                        <canvas id="time-chart-canvas" height="350" style="height: 350px;"></canvas>
                        </div>
                    </div>
                    </div>
                    <!-- /.card-body -->';
                    
                    $sql = "SELECT COUNT(username) as count
                    FROM {user_enrolments} ue
                    JOIN {enrol} en ON ue.enrolid = en.id
                    JOIN {user} uu ON uu.id = ue.userid
                    WHERE en.courseid = {$courseid}
                    AND NOT EXISTS (
                        SELECT * FROM {user_lastaccess} la
                        WHERE la.userid = ue.userid
                        AND la.courseid = en.courseid
                    )";
                    $never_entered = $DB->get_record_sql($sql);

                    $sql = "SELECT COUNT(DISTINCT u.department) AS count 
                    FROM {role_assignments} asg
                    JOIN {context} context ON asg.contextid = context.id
                    JOIN {user} u ON u.id = asg.userid
                    JOIN {course} course ON context.instanceid = course.id
                    WHERE asg.roleid = 5
                    AND course.id = {$courseid}";
                    $distinct_province = $DB->get_record_sql($sql);

                    $sql = "SELECT COUNT(course.id) AS count
                    FROM {role_assignments} asg
                    JOIN {context} context ON asg.contextid = context.id
                    JOIN {user} u ON u.id = asg.userid
                    JOIN {course} course ON context.instanceid = course.id
                    WHERE asg.roleid <> 5
                    AND course.id = {$courseid}";
                    $number_teachers = $DB->get_record_sql($sql);

                    $sql = "SELECT COUNT(course) AS count
                    FROM {course_modules} WHERE course = {$courseid}";
                    $number_resources = $DB->get_record_sql($sql);

                    $categoryNumbersSection .= '<div class="card-footer bg-transparent">
                    <div class="row mb-2">Información varia del curso</div>
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="position-relative p-2 border border-success text-success rounded" style="height: 100px">
                                <div class="ribbon-wrapper ribbon">
                                </div>
                                <b>' . $never_entered->count . '</b><br />
                                <small class="text-success"># de alumnos que nunca entraron</small>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="position-relative p-2 border border-success text-success rounded" style="height: 100px">
                                <div class="ribbon-wrapper ribbon">
                                </div>
                                <b>' . $distinct_province->count . '</b><br />
                                <small class="text-success"># de provincias de los alumnos</small>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="position-relative p-2 border border-success text-success rounded" style="height: 100px">
                                <div class="ribbon-wrapper ribbon">
                                </div>
                                <b>' . $number_teachers->count . '</b><br />
                                <small class="text-success"># de profesores matriculados</small>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="position-relative p-2 border border-success text-success rounded" style="height: 100px">
                                <div class="ribbon-wrapper ribbon">
                                </div>
                                <b>' . $number_resources->count . '</b><br />
                                <small class="text-success"># de recursos utilizados</small>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                    </div>
                    <!-- /.card-footer -->';

                }

                    $categoryNumbersSection .= '
                </div>
                <!-- /.card -->


                </section>';
        
        return $categoryNumbersSection;
    }

    public function get_course_numbers($courseid) {

        global $DB, $CFG;

        $pieCategorySection = '<section class="col-lg-6 connectedSortable">

        <div class="card card-outline card-success">
            <div class="card-header">
              <h3 class="card-title">';
              if (isset($courseid)) {
                $pieCategorySection .= 'Estado de finalización';
              } else {
                $pieCategorySection .= 'Cursos con mayor # de matriculados último año';
              };       
        $pieCategorySection .= '      </h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-8">
                  <div class="chart-responsive">
                    <canvas id="pieChart" height="150"></canvas>
                  </div>
                  <!-- ./chart-responsive -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                  <ul class="chart-legend clearfix">';

                if (isset($courseid)) { // vamos a sacar los finalizados o no

                    $sql = "SELECT COUNT(course.id) AS Students
                            FROM {role_assignments} asg
                            JOIN {context} context ON asg.contextid = context.id AND context.contextlevel = 50
                            JOIN {user} u ON u.id = asg.userid
                            JOIN {course} course ON context.instanceid = course.id
                            WHERE asg.roleid = 5
                            AND course.id = {$courseid}
                            GROUP BY course.id
                            ORDER BY Students DESC";

                    $completeEnroled = $DB->get_record_sql($sql, null, 'IGNORE_MISSING');

                    $sql = "SELECT COUNT(cc.timecompleted) AS finalizado
                            FROM {role_assignments} ra
                            JOIN {context} context ON context.id = ra.contextid AND context.contextlevel = 50
                            JOIN {course} c ON c.id = context.instanceid AND c.id = {$courseid}
                            JOIN {user} u ON u.id = ra.userid AND ra.roleid = 5
                            JOIN {course_completions} cc ON cc.course = c.id AND cc.userid = u.id
                            WHERE ra.roleid = '5' AND cc.timecompleted IS NOT NULL AND cc.timestarted > 0";

                    $completePercent = $DB->get_record_sql($sql, null, 'IGNORE_MISSING');

                    if (empty($completeEnroled->students)) {
                        $percentage = 0;
                    } else {
                        $percentage = round(($completePercent->finalizado / $completeEnroled->students) * 100);
                        $notcompleted = $completeEnroled->students - $completePercent->finalizado; 

                        $color_array = array('text-success','text-danger');
                        $this->pieChartLabel = ['Finalizados', 'No finalizados'];
                        $this->pieChartData  = [$completePercent->finalizado , $notcompleted];
                        $this->background_color = ['#28a745', '#dc3545'];
                        if ($notcompleted == 0) {
                            $this->pieChartData  = [$completePercent->finalizado , 0];
                            $pieCategorySection .= ' 
                            <li><i class="fas fa-user-graduate ' . $color_array[0] . '"></i>   Finalizados - ' . $completePercent->finalizado . '</li>
                            <li><i class="fas fa-user-graduate ' . $color_array[1] . '"></i>   No Finalizados - 0</li>';

                        } else {
                            $this->pieChartData  = [$completePercent->finalizado , $notcompleted];
                            $pieCategorySection .= ' 
                                <li><i class="fas fa-user-graduate ' . $color_array[0] . '"></i>   Finalizados - ' . $completePercent->finalizado . '</li>
                                <li><i class="fas fa-user ' . $color_array[1] . '"></i></i>   No finalizados - ' . $notcompleted . '</li>';
                        }
        
                        $pieCategorySection .= '  </ul>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <!-- /.card-body  -->
                    <div class="card-footer p-0">
                    <ul class="nav nav-pills flex-column">';

                        $pieCategorySection .= '
                            <li class="nav-item p-1">Total matriculados: <span class="float-right text-primary">
                                <i class="fas fa-solid fa-user"></i>   ' . $completeEnroled->students . '   </span></li>
                            <li class="nav-item p-1">Porcentaje finalizados: <span class="float-right text-success">
                                <i class="fas fa-solid fa-user-graduate"></i>   ' . $percentage . ' % </span></li>';

                    }
                } else { //sacamos los cursos con mayor número de matriculados: 

                    
                    $sql_old = "SELECT course.id, course.fullname, course.shortname, COUNT(course.id) AS enroled
                            FROM {role_assignments} asg
                            JOIN {context} context ON asg.contextid = context.id AND context.contextlevel = 50
                            JOIN {user} u ON u.id = asg.userid
                            JOIN {course} course ON context.instanceid = course.id
                            WHERE asg.roleid = 5 and course.id > 1
                            GROUP BY course.id, course.fullname, course.shortname
                            ORDER BY enroled DESC";

$sql = "SELECT course.id, course.fullname, course.shortname, COUNT(course.id) AS enroled
FROM {role_assignments} asg
JOIN {context} context ON asg.contextid = context.id AND context.contextlevel = 50
JOIN {user} u ON u.id = asg.userid
JOIN {course} course ON context.instanceid = course.id
WHERE asg.roleid = 5
    AND course.id > 1
    AND TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL( course.startdate, 'SECOND' ) BETWEEN next_day(trunc(sysdate), 'MON') - 365 AND next_day(trunc(sysdate), 'MON')
GROUP BY course.id, course.fullname, course.shortname
ORDER BY enroled DESC";


                      $totalEnrolments = $DB->get_records_sql($sql, null, 0, 10);

                      $color_array = array('text-danger','text-info','text-success','text-warning', 'text-secondary', 'text-primary', 'text-dark', 'text-pink', 'text-purple', 'text-orange');
                      $this->background_color = ['#dc3545', '#17a2b8', '#28a745', '#ffc107', '#6c757d', '#007bff', '#343a40', '#e83e8c', 'purple', 'orange'];
                      $n = 0;
                      $pieCourseFullname = [];
                      $pieCourseId = [];

                  foreach ($totalEnrolments as $totalEnrolment) :

                      $this->pieChartLabel[] .= $totalEnrolment->shortname;
                      $this->pieChartData[]  .= $totalEnrolment->enroled;
                      $pieCourseFullname[] .= $totalEnrolment->fullname;
                      $pieCourseId[] .= $totalEnrolment->id;

        $pieCategorySection .= ' <li><i class="far fa-circle ' . $color_array[$n] . '';   
                $n++;
        $pieCategorySection .= '"></i><a href="' . $CFG->wwwroot . '/adminlte/courses?courseid=' . $totalEnrolment->id . '" title="Ver detalle del curso"> ' . $totalEnrolment->shortname . '</a> - ' . $totalEnrolment->enroled . '</li>';

                  endforeach;
        
        $pieCategorySection .= '  </ul>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.card-body  -->
            <div class="card-footer p-0">
              <ul class="nav nav-pills flex-column">';
              for ($i = 0; $i < 4; $i++) :
                $pieCategorySection .= '<li class="nav-item">
                <a href="' . $CFG->wwwroot . '/user/index.php?id=' . $pieCourseId[$i] . '"  target="_blank" class="nav-link" title="Ir al curso en la plataforma">
                    ' . $pieCourseFullname[$i] . '
                    <span class="float-right ' . $color_array[$i] . '">
                    <i class="fas fa-solid fa-user-graduate"></i>
                    ' . $this->pieChartData[$i] . '</span>
                  </a>
                </li>';
              endfor;
            }
        $pieCategorySection .= '  </ul>
            </div>
            <!-- /.footer -->
          </div>
          <!-- /.card -->

        </section>';

        return $pieCategorySection;
    }

    public static function count_user_access($userid = NULL) {
        global $DB;
        $user = $DB->get_record('user', ['id' => $userid]);

            $user_access_graph = '
            
            <div class="card card-primary card-outline">
              <div class="card-header ui-sortable-handle">
                <h3 class="card-title">';
                if (empty($userid)) {
                    $user_access_graph .= 'Accesos únicos a la plataforma el último año por día ';
                } else {
                    $user_access_graph .= 'Accesos de <b>' . $user->firstname . '</b> el último año por día';
                }
            $user_access_graph .= '</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
              </div>
              <div class="card-body">
                <div class="chart">
                <div class="loading-table d-flex justify-content-center">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    </div>
                </div>
                  <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>
                </div>
              </div> <!-- /.card body -->
            </div> <!-- /.card -->';

        return $user_access_graph;
    }

    public static function count_province_user_card($userid = NULL) {
        global $DB, $OUTPUT, $CFG;

        if (empty($userid)) {

            $province_user_graph = '
            <div class="card card-primary card-outline">
              <div class="card-header ui-sortable-handle">
                <h3 class="card-title">Las 10 provincias con más usuarios</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
              </div>
              <div class="card-body">
                <div class="chart">
                <div class="loading-table d-flex justify-content-center">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    </div>
                </div>
                  <canvas id="barChart2" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>
                </div>
              </div> <!-- /.card body -->
            </div> <!-- /.card -->';




        } else {

            $user = $DB->get_record('user', ['id' => $userid]);

            //$province_field = $DB->get_record('user_info_data', ['userid' => $userid, 'fieldid' => '4']);

            $province_user_graph = '<div class="card bg-light d-flex flex-fill card-primary card-outline">
            <div class="card-header text-black border-bottom-1">
            <h3 class="card-title">Persona</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body pt-0 mt-2">
              <div class="row">
                <div class="col-7">
                  <h2 class="lead"><b>' . $user->firstname . ' ' . $user->lastname . '</b></h2>
                  <p class="text-muted text-sm"><b>' . $user->institution .  '</b></p>
                  <ul class="ml-4 mb-0 fa-ul text-muted">
                    <li class="small mb-1"><span class="fas fa-li"><i class="fas fa-lg fa-building"></i></span> Dirección: <b>' . $user->address .'</b></li> <!-- PRO $user->institution. $user->phone2 es CP. $user->address es dirección -->
                    <li class="small mb-1"><span class="fas fa-li pr-2"><i class="fas fa-lg fa-city"></i></span> Ciudad: <b>' . $user->city .'</b></li>
                    <li class="small mb-1"><span class="fas fa-li"><i class="fas fa-lg fa-location-dot"></i></span> Provincia: <b>' . $user->department .'</b></li> <!--  EN PRO $user->department -->
                    <li class="small mb-1"><span class="fas fa-li"><i class="fas fa-lg fa-phone"></i></span> Telefono: <b>' . $user->phone1 .'</b></li> <!--  EN PRO $user->middlename -->
                    <li class="small mb-1"><span class="fas fa-li"><i class="fas fa-lg fa-at"></i></span> Email: <b><a href="mailto:' . $user->email . '  " title="Enviar email">' . $user->email .'</a></b></li> 
                    <li class="small mb-1"><span class="fas fa-li"><i class="fa-brands fa-lg fa-microsoft"></i></span> Teams: <b><a href="https://teams.microsoft.com/l/chat/0/0?users=sgtic041@sepe.es" target="_blank" title="Hablar por Teams">' . strtok($user->email, '@') .'</a></b></li>               
                  </ul>
                </div>
                <div class="col-5 text-center">' .
                $OUTPUT->user_picture($user, ['size' => 100, 'class' => 'userpicture']) . '
                </div>
              </div>
            </div>
            <div class="card-footer">
              <div class="text-right">
                <a href="' . $CFG->wwwroot . '/message/index.php?id=' . $user->id . '  " class="btn btn-sm bg-teal" title="Abrir chat en la plaforma">
                  <i class="fas fa-comments"></i>
                </a>
                <a href="' . $CFG->wwwroot . '/user/profile.php?id=' . $user->id . '" class="btn btn-sm btn-primary" title="Ver perfil en la plataforma">
                  <i class="fas fa-user"></i> Ver perfil
                </a>
              </div>
            </div>
            </div><!-- /.card body -->';

        }

        return $province_user_graph;
    }

    public static function generate_province_user_count() {
        global $DB;
        
       $sql_oracle = "SELECT * FROM
                    (SELECT department as province, COUNT(department) AS count
                    FROM  {user}
                    WHERE deleted = 0
                    GROUP BY department
                    ORDER BY count DESC)
                    WHERE ROWNUM <= 10";

    /*     $sql_oracle = "
        SELECT * FROM
        (SELECT to_char(data) as province, COUNT(data) AS count
        FROM  {user_info_data}
        WHERE fieldid = 4
        GROUP BY to_char(data)
        ORDER BY count DESC)
        WHERE ROWNUM <= 10";  */

        $sql_mysql = "
        SELECT institution as province, COUNT(institution) AS count
        FROM  {user}
        WHERE deleted = 0 AND id > 1
        GROUP BY institution
        ORDER BY count DESC
        LIMIT 10";

        $sql = ($DB->get_dbfamily() === 'oracle') ? $sql_oracle : $sql_mysql;

        $usersbyprovinces = $DB->get_records_sql($sql);

        foreach ($usersbyprovinces as $usersbyprovince) {
            $dataName[] = $usersbyprovince->province;
            $dataNumber[] = $usersbyprovince->count;
        }
        
        if (empty($dataName)) {
            $json_name = "['Lun','Mar','Mié','Jue','Vie','Sab','Dom']";
            $json_number = "['0','0','0','0','0','0','0']";
        } else {
            $json_name = json_encode($dataName);
            $json_number = json_encode($dataNumber);
        }
        // Initialize the array to store the data.

        // Convert the data array to JSON for visualization or storage purposes.
        $jsonData = ['province' => $json_name, 'count' => $json_number];

      // Print the JSON data or do something else with it.
      return $jsonData;
 
    }

    public static function get_map_knobs() {

        global $DB;

        $sql = "SELECT COUNT(*) AS departments FROM {user} WHERE deleted = 0 AND suspended = 0 ORDER BY departments";
        $allProvinces = $DB->get_record_sql($sql, null, 0, 4);

        $sql = "SELECT department, COUNT(*) AS count FROM {user} WHERE deleted = 0 AND suspended = 0 GROUP BY department ORDER BY count DESC";
        $popularProvinces = $DB->get_records_sql($sql, null, 0, 8);

        $categoryNumbersSection = '
        <!-- solid sales graph -->
                <div class="card card-outline card-success">
        <!-- /.card-body --><div class="card-footer bg-transparent">
        <div class="row mb-2 text-center">Provincias con más % de usuarios</div>
        <div class="row justify-content-center">';

        foreach ($popularProvinces as $category) :

            $percent = round($category->count * 100 / $allProvinces->departments, 1) . '%';

        $categoryNumbersSection .= '<div class="col-12 text-center">
            <input type="text" class="knob" data-readOnly="true" data-skin="tron" data-thickness="0.2" value="' . $percent . '" data-width="50" data-height="50"
                    data-fgColor="#28a745"><p class="knob-label">' . $category->department . '</p>
            </div>';

        endforeach;
        $categoryNumbersSection .= '</div>
        <!-- /.row -->
        </div>
        <!-- /.card-footer -->
        </div>
        <!-- /.card -->';

        return $categoryNumbersSection;
    }
}