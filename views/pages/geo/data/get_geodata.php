<?php 
if (!defined('CLI_SCRIPT')) {
    define('CLI_SCRIPT', true);
    include __DIR__.'/../../../../../config.php';
}
adminlte_getgeodata::geoDataJson();

/**
 * File containing the getData class.
 *
 * @package    adminlte_geo
 * @copyright  2023 Thorvaldur Konradsson
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * GetData class.
 *
 * @package    adminlte_geo
 * @copyright  2023 Thorvaldur Konradsson
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class adminlte_getgeodata {

    /** @var object are resets allowed. */
    protected $users;

    protected $provincias = [];

    static public $provinceColor = '';

    /**
    * Undocumented function countUsersProvincia
    *
    * @return $users
    */

    public static function countUsersProvincia($provincename)
    {
        global $DB;
        
        $sql = "select case 
            when exists (select 1 
                         from {user}
                         where deleted = 0 AND lower(department) = lower('{$provincename}')) 
            then 'Y' 
            else 'N' 
        end as rec_exists
        from dual";
        
        $exists = $DB->get_record_sql($sql);
        if ($exists = 'Y') {

            self::$provinceColor = "3";
            $sql = "SELECT COUNT (*)
                    FROM   {user}
                    WHERE deleted = 0 AND lower(department) = lower('{$provincename}')
                    COLLATE binary_ci";
            $provincias = $DB->get_record_sql($sql);

        } else {
            self::$provinceColor = "1";
            $users = 0;
        }

        foreach($provincias as $provincia) {

            if ($provincia <= 100) {
                self::$provinceColor = "1";
            } elseif ($provincia >= 500) {
                self::$provinceColor = "3";
            } else {
                self::$provinceColor = "2";
            }

            return $provincia;
        }
    }

    public static function countCoursesProvincia($provincename)
    {
        global $DB;

        $sql = "SELECT count(DISTINCT e.courseid) over ()
                FROM {enrol} e
                JOIN {user_enrolments} ue ON ue.enrolid = e.id
                JOIN {user} u ON ue.userid  = u.id and lower(u.department) = lower('{$provincename}')
                COLLATE binary_ci
                OFFSET 1 ROWS FETCH NEXT 1 ROWS ONLY";

        $countCourses = $DB->get_record_sql($sql);

        foreach ($countCourses as $countCourse) {

            if ($countCourse >= 1) {
            
                return $countCourse;

            } else {

                return 'No hay cursos';
            }
        }
    }

    public static function count_enrolled_provincia($provincename)
    {
        global $DB;

        $sql = "SELECT COUNT(ue.id)
                FROM {user_enrolments} ue 
                JOIN {user} u ON ue.userid  = u.id AND lower(u.department) = lower('{$provincename}')
                COLLATE binary_ci
                WHERE status = 0";

        $countEnrolled = $DB->get_record_sql($sql);

        foreach ($countEnrolled as $countEnrol) {

            if ($countEnrol >= 1) {

                return $countEnrol;

            } else {

                return 'No hay matriculados';
            }
        }

    }

    public static function count_completed_month_provincia($provincename)
    {
        global $DB;

        $sql = "SELECT count(cc.timecompleted) as completed
                FROM {course_completions} cc
                JOIN {user} u ON cc.userid  = u.id AND lower(u.department) = lower('{$provincename}')
                COLLATE binary_ci
                WHERE TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(cc.timecompleted, 'SECOND' ) > sysdate-30";

        $count_completed = $DB->get_record_sql($sql);

        foreach ($count_completed as $count_complete) {

            if ($count_complete >= 1) {

                return $count_complete;

            } else {

                return 'No hay finalizaciones';
            }
        }

    }

    public static function count_enrolled_month_provincia($provincename)
    {
        global $DB;

        $sql = "SELECT COUNT(ue.id)
                FROM {user_enrolments} ue 
                JOIN {user} u ON ue.userid  = u.id AND lower(u.department) = lower('{$provincename}')
                COLLATE binary_ci
                WHERE status = 0 AND (TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(ue.timecreated, 'SECOND' ) > sysdate-30 OR TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(ue.timemodified, 'SECOND' ) > sysdate-30)";

        $countEnrolled = $DB->get_record_sql($sql);

        foreach ($countEnrolled as $countEnrol) {

            if ($countEnrol >= 1) {

                return $countEnrol;

            } else {

                return 'No hay matriculados';
            }
        }

    }

    public static function count_registered_month_provincia($provincename)
    {
        global $DB;

        $sql = "SELECT count(timecreated) as created
                FROM {user}
                WHERE TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timecreated, 'SECOND' ) > sysdate-30 AND lower(department) = lower('{$provincename}')";

        $count_registered = $DB->get_record_sql($sql);

        foreach ($count_registered as $count_register) {

            if ($count_register >= 1) {

                return $count_register;

            } else {

                return 'No hay altas';
            }
        }

    }

    public static function count_deleted_month_provincia($provincename)
    {
        global $DB;

        $sql = "SELECT count(timemodified) as deleted
                FROM {user}
                WHERE TO_DATE('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(timemodified, 'SECOND' ) > sysdate-30 AND lower(department) = lower('{$provincename}') AND (deleted = 1 OR suspended = 1)";

        $count_deleted = $DB->get_record_sql($sql);

        foreach ($count_deleted as $count_delete) {

            if ($count_delete >= 1) {

                return $count_delete;

            } else {

                return 'No hay bajas';
            }
        }

    }

    public static function geoDataJson()
    {
        global $DB;

        $path = "/moodle/www/adminlte/views/pages/geo/data/user_provincia_table.php";

        $geodata = [
              ['alava', 'Araba, Álava', self::countCoursesProvincia('ARABA-ALAVA'), self::count_enrolled_provincia('ARABA-ALAVA'), self::countUsersProvincia('ARABA-ALAVA'), self::$provinceColor, self::count_completed_month_provincia('ARABA-ALAVA'), self::count_enrolled_month_provincia('ARABA-ALAVA'), self::$provinceColor, self::count_completed_month_provincia('ARABA-ALAVA'), self::count_registered_month_provincia('ARABA-ALAVA'), self::count_deleted_month_provincia('ARABA-ALAVA')],
              ['albacete','Albacete', self::countCoursesProvincia('Albacete'), self::count_enrolled_provincia('Albacete'), self::countUsersProvincia('Albacete'), self::$provinceColor, self::count_completed_month_provincia('Albacete'), self::count_enrolled_month_provincia('Albacete'), self::count_registered_month_provincia('Albacete'), self::count_deleted_month_provincia('Albacete')],
              ['alicante','Alicante/Alacant', self::countCoursesProvincia('ALICANTE'), self::count_enrolled_provincia('ALICANTE'), self::countUsersProvincia('ALICANTE'), self::$provinceColor, self::count_completed_month_provincia('ALICANTE'), self::count_enrolled_month_provincia('ALICANTE'), self::count_registered_month_provincia('ALICANTE'), self::count_deleted_month_provincia('ALICANTE')],
              ['almeria','Almería', self::countCoursesProvincia('Almeria'), self::count_enrolled_provincia('Almeria'), self::countUsersProvincia('Almeria'), self::$provinceColor, self::count_completed_month_provincia('Almeria'), self::count_enrolled_month_provincia('Almeria'), self::count_registered_month_provincia('Almeria'), self::count_deleted_month_provincia('Almeria')],
              ['asturias','Asturias, Asturies', self::countCoursesProvincia('Asturias'), self::count_enrolled_provincia('Asturias'), self::countUsersProvincia('Asturias'), self::$provinceColor, self::count_completed_month_provincia('Asturias'), self::count_enrolled_month_provincia('Asturias'), self::count_registered_month_provincia('Asturias'), self::count_deleted_month_provincia('Asturias')],
              ['avila','Ávila', self::countCoursesProvincia('Avila'), self::count_enrolled_provincia('Avila'), self::countUsersProvincia('Avila'), self::$provinceColor, self::count_completed_month_provincia('Avila'), self::count_enrolled_month_provincia('Avila'), self::count_registered_month_provincia('Avila'), self::count_deleted_month_provincia('Avila')],

              ['badajoz','Badajoz', self::countCoursesProvincia('Badajoz'), self::count_enrolled_provincia('Badajoz'), self::countUsersProvincia('Badajoz'), self::$provinceColor, self::count_completed_month_provincia('Badajoz'), self::count_enrolled_month_provincia('Badajoz'), self::count_registered_month_provincia('Badajoz'), self::count_deleted_month_provincia('Badajoz')],
              ['barcelona','Barcelona', self::countCoursesProvincia('Barcelona'), self::count_enrolled_provincia('Barcelona'), self::countUsersProvincia('Barcelona'), self::$provinceColor, self::count_completed_month_provincia('Barcelona'), self::count_enrolled_month_provincia('Barcelona'), self::count_registered_month_provincia('Barcelona'), self::count_deleted_month_provincia('Barcelona')],
              ['burgos','Burgos', self::countCoursesProvincia('Burgos'), self::count_enrolled_provincia('Burgos'), self::countUsersProvincia('Burgos'), self::$provinceColor, self::count_completed_month_provincia('Burgos'), self::count_enrolled_month_provincia('Burgos'), self::count_registered_month_provincia('Burgos'), self::count_deleted_month_provincia('Burgos')],

              ['caceres','Cáceres', self::countCoursesProvincia('Caceres'), self::count_enrolled_provincia('Caceres'), self::countUsersProvincia('Caceres'), self::$provinceColor, self::count_completed_month_provincia('Caceres'), self::count_enrolled_month_provincia('Caceres'), self::count_registered_month_provincia('Caceres'), self::count_deleted_month_provincia('Caceres')],
              ['cadiz','Cádiz', self::countCoursesProvincia('Cadiz'), self::count_enrolled_provincia('Cadiz'), self::countUsersProvincia('Cadiz'), self::$provinceColor, self::count_completed_month_provincia('Cadiz'), self::count_enrolled_month_provincia('Cadiz'), self::count_registered_month_provincia('Cadiz'), self::count_deleted_month_provincia('Cadiz')],
              ['cantabria','Cantabria', self::countCoursesProvincia('Cantabria'), self::count_enrolled_provincia('Cantabria'), self::countUsersProvincia('Cantabria'), self::$provinceColor, self::count_completed_month_provincia('Cantabria'), self::count_enrolled_month_provincia('Cantabria'), self::count_registered_month_provincia('Cantabria'), self::count_deleted_month_provincia('Cantabria')],
              ['castellon','Castellón, Castelló', self::countCoursesProvincia('Castellon'), self::count_enrolled_provincia('Castellon'), self::countUsersProvincia('Castellon'), self::$provinceColor, self::count_completed_month_provincia('Castellon'), self::count_enrolled_month_provincia('Castellon'), self::count_registered_month_provincia('Castellon'), self::count_deleted_month_provincia('Castellon')],
              ['ciudad_real','Ciudad Real', self::countCoursesProvincia('Ciudad Real'), self::count_enrolled_provincia('Ciudad Real'), self::countUsersProvincia('Ciudad Real'), self::$provinceColor, self::count_completed_month_provincia('Ciudad Real'), self::count_enrolled_month_provincia('Ciudad Real'), self::count_registered_month_provincia('Ciudad Real'), self::count_deleted_month_provincia('Ciudad Real')],
              ['cordoba','Córdoba', self::countCoursesProvincia('Cordoba'), self::count_enrolled_provincia('Cordoba'), self::countUsersProvincia('Cordoba'), self::$provinceColor, self::count_completed_month_provincia('Cordoba'), self::count_enrolled_month_provincia('Cordoba'), self::count_registered_month_provincia('Cordoba'), self::count_deleted_month_provincia('Cordoba')],
              ['corunia','A Coruña', self::countCoursesProvincia('A CORUÑA'), self::count_enrolled_provincia('A CORUÑA'), self::countUsersProvincia('A CORUÑA'), self::$provinceColor, self::count_completed_month_provincia('A CORUÑA'), self::count_enrolled_month_provincia('A CORUÑA'), self::count_registered_month_provincia('A CORUÑA'), self::count_deleted_month_provincia('A CORUÑA')],
              ['cuenca','Cuenca', self::countCoursesProvincia('Cuenca'), self::count_enrolled_provincia('Cuenca'), self::countUsersProvincia('Cuenca'), self::$provinceColor, self::count_completed_month_provincia('Cuenca'), self::count_enrolled_month_provincia('Cuenca'), self::count_registered_month_provincia('Cuenca'), self::count_deleted_month_provincia('Cuenca')],

              ['girona','Girona', self::countCoursesProvincia('Girona'), self::count_enrolled_provincia('Girona'), self::countUsersProvincia('Girona'), self::$provinceColor, self::count_completed_month_provincia('Girona'), self::count_enrolled_month_provincia('Girona'), self::count_registered_month_provincia('Girona'), self::count_deleted_month_provincia('Girona')],
              ['granada','Granada', self::countCoursesProvincia('Granada'), self::count_enrolled_provincia('Granada'), self::countUsersProvincia('Granada'), self::$provinceColor, self::count_completed_month_provincia('Granada'), self::count_enrolled_month_provincia('Granada'), self::count_registered_month_provincia('Granada'), self::count_deleted_month_provincia('Granada')],
              ['guadalajara','Guadalajara', self::countCoursesProvincia('Guadalajara'), self::count_enrolled_provincia('Guadalajara'), self::countUsersProvincia('Guadalajara'), self::$provinceColor, self::count_completed_month_provincia('Guadalajara'), self::count_enrolled_month_provincia('Guadalajara'), self::count_registered_month_provincia('Guadalajara'), self::count_deleted_month_provincia('Guadalajara')],
              ['gipuzkoa','Gipuzkoa', self::countCoursesProvincia('Gipuzkoa'), self::count_enrolled_provincia('Gipuzkoa'), self::countUsersProvincia('Gipuzkoa'), self::$provinceColor, self::count_completed_month_provincia('Gipuzkoa'), self::count_enrolled_month_provincia('Gipuzkoa'), self::count_registered_month_provincia('Gipuzkoa'), self::count_deleted_month_provincia('Gipuzkoa')],
              
              ['huelva','Huelva', self::countCoursesProvincia('Huelva'), self::count_enrolled_provincia('Huelva'), self::countUsersProvincia('Huelva'), self::$provinceColor, self::count_completed_month_provincia('Huelva'), self::count_enrolled_month_provincia('Huelva'), self::count_registered_month_provincia('Huelva'), self::count_deleted_month_provincia('Huelva')],
              ['huesca','Huesca', self::countCoursesProvincia('Huesca'), self::count_enrolled_provincia('Huesca'), self::countUsersProvincia('Huesca'), self::$provinceColor, self::count_completed_month_provincia('Huesca'), self::count_enrolled_month_provincia('Huesca'), self::count_registered_month_provincia('Huesca'), self::count_deleted_month_provincia('Huesca')],
              ['illes_balears','Illes Balears', self::countCoursesProvincia('Illes Balears'), self::count_enrolled_provincia('Illes Balears'), self::countUsersProvincia('Illes Balears'), self::$provinceColor, self::count_completed_month_provincia('Illes Balears'), self::count_enrolled_month_provincia('Illes Balears'), self::count_registered_month_provincia('Illes Balears'), self::count_deleted_month_provincia('Illes Balears')],
              ['jaen','Jaén', self::countCoursesProvincia('Jaen'), self::count_enrolled_provincia('Jaen'), self::countUsersProvincia('Jaen'), self::$provinceColor, self::count_completed_month_provincia('Jaen'), self::count_enrolled_month_provincia('Jaen'), self::count_registered_month_provincia('Jaen'), self::count_deleted_month_provincia('Jaen')],
              ['leon','León', self::countCoursesProvincia('Leon'), self::count_enrolled_provincia('Leon'), self::countUsersProvincia('Leon'), self::$provinceColor, self::count_completed_month_provincia('Leon'), self::count_enrolled_month_provincia('Leon'), self::count_registered_month_provincia('Leon'), self::count_deleted_month_provincia('Leon')],
              ['lleida','Lleida', self::countCoursesProvincia('Lleida'), self::count_enrolled_provincia('Lleida'), self::countUsersProvincia('Lleida'), self::$provinceColor, self::count_completed_month_provincia('Lleida'), self::count_enrolled_month_provincia('Lleida'), self::count_registered_month_provincia('Lleida'), self::count_deleted_month_provincia('Lleida')],
              ['lugo','Lugo', self::countCoursesProvincia('Lugo'), self::count_enrolled_provincia('Lugo'), self::countUsersProvincia('Lugo'), self::$provinceColor, self::count_completed_month_provincia('Lugo'), self::count_enrolled_month_provincia('Lugo'), self::count_registered_month_provincia('Lugo'), self::count_deleted_month_provincia('Lugo')],

              ['madrid','Madrid', self::countCoursesProvincia('Madrid'), self::count_enrolled_provincia('Madrid'), self::countUsersProvincia('Madrid'), self::$provinceColor, self::count_completed_month_provincia('Madrid'), self::count_enrolled_month_provincia('Madrid'), self::count_registered_month_provincia('Madrid'), self::count_deleted_month_provincia('Madrid')],
              ['malaga','Málaga', self::countCoursesProvincia('Malaga'), self::count_enrolled_provincia('Malaga'), self::countUsersProvincia('Malaga'), self::$provinceColor, self::count_completed_month_provincia('Malaga'), self::count_enrolled_month_provincia('Malaga'), self::count_registered_month_provincia('Malaga'), self::count_deleted_month_provincia('Malaga')],
              ['murcia','Murcia', self::countCoursesProvincia('Murcia'), self::count_enrolled_provincia('Murcia'), self::countUsersProvincia('Murcia'), self::$provinceColor, self::count_completed_month_provincia('Murcia'), self::count_enrolled_month_provincia('Murcia'), self::count_registered_month_provincia('Murcia'), self::count_deleted_month_provincia('Murcia')],
              ['navarra','Navarra', self::countCoursesProvincia('Navarra'), self::count_enrolled_provincia('Navarra'), self::countUsersProvincia('Navarra'), self::$provinceColor, self::count_completed_month_provincia('Navarra'), self::count_enrolled_month_provincia('Navarra'), self::count_registered_month_provincia('Navarra'), self::count_deleted_month_provincia('Navarra')],
              ['ourense','Ourense', self::countCoursesProvincia('Ourense'), self::count_enrolled_provincia('Ourense'), self::countUsersProvincia('Ourense'), self::$provinceColor, self::count_completed_month_provincia('Ourense'), self::count_enrolled_month_provincia('Ourense'), self::count_registered_month_provincia('Ourense'), self::count_deleted_month_provincia('Ourense')],
              ['palencia','Palencia', self::countCoursesProvincia('Palencia'), self::count_enrolled_provincia('Palencia'), self::countUsersProvincia('Palencia'), self::$provinceColor, self::count_completed_month_provincia('Palencia'), self::count_enrolled_month_provincia('Palencia'), self::count_registered_month_provincia('Palencia'), self::count_deleted_month_provincia('Palencia')],
              ['las_palmas','Las Palmas', self::countCoursesProvincia('Las Palmas'), self::count_enrolled_provincia('Las Palmas'), self::countUsersProvincia('Las Palmas'), self::$provinceColor, self::count_completed_month_provincia('Las Palmas'), self::count_enrolled_month_provincia('Las Palmas'), self::count_registered_month_provincia('Las Palmas'), self::count_deleted_month_provincia('Las Palmas')],
              ['pontevedra','Pontevedra', self::countCoursesProvincia('Pontevedra'), self::count_enrolled_provincia('Pontevedra'), self::countUsersProvincia('Pontevedra'), self::$provinceColor, self::count_completed_month_provincia('Pontevedra'), self::count_enrolled_month_provincia('Pontevedra'), self::count_registered_month_provincia('Pontevedra'), self::count_deleted_month_provincia('Pontevedra')],
              ['la_rioja','La Rioja', self::countCoursesProvincia('La Rioja'), self::count_enrolled_provincia('La Rioja'), self::countUsersProvincia('La Rioja'), self::$provinceColor, self::count_completed_month_provincia('La Rioja'), self::count_enrolled_month_provincia('La Rioja'), self::count_registered_month_provincia('La Rioja'), self::count_deleted_month_provincia('La Rioja')],
              ['salamanca','Salamanca', self::countCoursesProvincia('Salamanca'), self::count_enrolled_provincia('Salamanca'), self::countUsersProvincia('Salamanca'), self::$provinceColor, self::count_completed_month_provincia('Salamanca'), self::count_enrolled_month_provincia('Salamanca'), self::count_registered_month_provincia('Salamanca'), self::count_deleted_month_provincia('Salamanca')],
              ['segovia','Segovia', self::countCoursesProvincia('Segovia'), self::count_enrolled_provincia('Segovia'), self::countUsersProvincia('Segovia'), self::$provinceColor, self::count_completed_month_provincia('Segovia'), self::count_enrolled_month_provincia('Segovia'), self::count_registered_month_provincia('Segovia'), self::count_deleted_month_provincia('Segovia')],

              ['sevilla','Sevilla', self::countCoursesProvincia('Sevilla'), self::count_enrolled_provincia('Sevilla'), self::countUsersProvincia('Sevilla'), self::$provinceColor, self::count_completed_month_provincia('Sevilla'), self::count_enrolled_month_provincia('Sevilla'), self::count_registered_month_provincia('Sevilla'), self::count_deleted_month_provincia('Sevilla')],
              ['soria','Soria', self::countCoursesProvincia('Soria'), self::count_enrolled_provincia('Soria'), self::countUsersProvincia('Soria'), self::$provinceColor, self::count_completed_month_provincia('Soria'), self::count_enrolled_month_provincia('Soria'), self::count_registered_month_provincia('Soria'), self::count_deleted_month_provincia('Soria')],
              ['tarragona','Tarragona', self::countCoursesProvincia('Tarragona'), self::count_enrolled_provincia('Tarragona'), self::countUsersProvincia('Tarragona'), self::$provinceColor, self::count_completed_month_provincia('Tarragona'), self::count_enrolled_month_provincia('Tarragona'), self::count_registered_month_provincia('Tarragona'), self::count_deleted_month_provincia('Tarragona')],
              ['tenerife','Tenerife', self::countCoursesProvincia('Tenerife'), self::count_enrolled_provincia('Tenerife'), self::countUsersProvincia('Tenerife'), self::$provinceColor, self::count_completed_month_provincia('Tenerife'), self::count_enrolled_month_provincia('Tenerife'), self::count_registered_month_provincia('Tenerife'), self::count_deleted_month_provincia('Tenerife')],
              ['teruel','Teruel', self::countCoursesProvincia('Teruel'), self::count_enrolled_provincia('Teruel'), self::countUsersProvincia('Teruel'), self::$provinceColor, self::count_completed_month_provincia('Teruel'), self::count_enrolled_month_provincia('Teruel'), self::count_registered_month_provincia('Teruel'), self::count_deleted_month_provincia('Teruel')],
              ['toledo','Toledo', self::countCoursesProvincia('Toledo'), self::count_enrolled_provincia('Toledo'), self::countUsersProvincia('Toledo'), self::$provinceColor, self::count_completed_month_provincia('Toledo'), self::count_enrolled_month_provincia('Toledo'), self::count_registered_month_provincia('Toledo'), self::count_deleted_month_provincia('Toledo')],
              ['valencia','València', self::countCoursesProvincia('Valencia'), self::count_enrolled_provincia('Valencia'), self::countUsersProvincia('Valencia'), self::$provinceColor, self::count_completed_month_provincia('Valencia'), self::count_enrolled_month_provincia('Valencia'), self::count_registered_month_provincia('Valencia'), self::count_deleted_month_provincia('Valencia')],
              ['valladolid','Valladolid', self::countCoursesProvincia('Valladolid'), self::count_enrolled_provincia('Valladolid'), self::countUsersProvincia('Valladolid'), self::$provinceColor, self::count_completed_month_provincia('Valladolid'), self::count_enrolled_month_provincia('Valladolid'), self::count_registered_month_provincia('Valladolid'), self::count_deleted_month_provincia('Valladolid')],
              ['bizkaia','Bizkaia', self::countCoursesProvincia('Bizkaia'), self::count_enrolled_provincia('Bizkaia'), self::countUsersProvincia('Bizkaia'), self::$provinceColor, self::count_completed_month_provincia('Bizkaia'), self::count_enrolled_month_provincia('Bizkaia'), self::count_registered_month_provincia('Bizkaia'), self::count_deleted_month_provincia('Bizkaia')],
              ['zamora','Zamora', self::countCoursesProvincia('Zamora'), self::count_enrolled_provincia('Zamora'), self::countUsersProvincia('Zamora'), self::$provinceColor, self::count_completed_month_provincia('Zamora'), self::count_enrolled_month_provincia('Zamora'), self::count_registered_month_provincia('Zamora'), self::count_deleted_month_provincia('Zamora')],
              ['zaragoza','Zaragoza', self::countCoursesProvincia('Zaragoza'), self::count_enrolled_provincia('Zaragoza'), self::countUsersProvincia('Zaragoza'), self::$provinceColor, self::count_completed_month_provincia('Zaragoza'), self::count_enrolled_month_provincia('Zaragoza'), self::count_registered_month_provincia('Zaragoza'), self::count_deleted_month_provincia('Zaragoza')],

              ['melilla','Melilla', self::countCoursesProvincia('Melilla'), self::count_enrolled_provincia('Melilla'), self::countUsersProvincia('Melilla'), self::$provinceColor, self::count_completed_month_provincia('Melilla'), self::count_enrolled_month_provincia('Melilla'), self::count_registered_month_provincia('Melilla'), self::count_deleted_month_provincia('Melilla')],
              ['ceuta','Ceuta', self::countCoursesProvincia('Ceuta'), self::count_enrolled_provincia('Ceuta'), self::countUsersProvincia('Ceuta'), self::$provinceColor, self::count_completed_month_provincia('Ceuta'), self::count_enrolled_month_provincia('Ceuta'), self::count_registered_month_provincia('Ceuta'), self::count_deleted_month_provincia('Ceuta')]
          ];

          $geodatacontents = 
'<?php

    class User_provincia_table {

        public static function getprovinciainfo() {

        $geodata = ' . json_encode($geodata,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) . ';

        return $geodata;

        }
    }';
            
              file_put_contents($path, $geodatacontents);
        //return $geodata;
    }

}