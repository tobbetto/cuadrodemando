<?php 
if (!defined('CLI_SCRIPT')) {
    define('CLI_SCRIPT', true);
    include __DIR__.'/../../../../../config.php';
}
adminlte_getprovinceactivity::geoProvinceAvtivityJson();

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
class adminlte_getprovinceactivity {

    public static function get_province_sessions($province) {

        global $DB;

        $sql = "SELECT count(userid) AS userid 
        FROM {sessions} s 
        JOIN {user} u ON u.id = s.userid AND lower(u.department) = lower('{$province}')
        WHERE userid != 0";

        $sessions = $DB->get_record_sql($sql);    

        if ($sessions->userid == 0) {
            return 'No hay sesiones abiertas';
        } else {
            return $sessions->userid;
        }

    }

    public static function get_province_hourly_views($province) {

        global $DB;

        $sql = "SELECT l.action, COUNT(DISTINCT l.userid) AS viewed
        FROM m_logstore_standard_log l
        JOIN m_user u ON u.id = l.userid AND lower(u.department) = lower('{$province}')
        WHERE to_char(TO_TIMESTAMP_TZ('1970-01-01', 'YYYY-MM-DD') + NUMTODSINTERVAL(l.timecreated, 'SECOND'),
            'YYYY-MM-DD HH24:MI:SSxFF TZH:TZM'
            )  >= to_char(cast((systimestamp - interval '1' hour) as timestamp) at time zone 'GMT', 'YYYY-MM-DD HH24:MI:SSxFF TZH:TZM')
            -- >= to_char(systimestamp - interval '5' HOUR, 'YYYY-MM-DD HH24:MI:SSxFF TZH:TZM')
            -- to_char(cast((systimestamp - interval '1' hour) as timestamp) at time zone 'GMT','DD/MM/YY HH24:MI:SSxFF TZH:TZM') 
            and l.action = 'viewed'
        GROUP BY l.action
        ORDER BY l.action";

        $statistics = $DB->get_records_sql($sql);

        if (!empty($statistics)) {
            return $statistics["viewed"]->viewed;
        } else {
            return 'No hay usuarios activos';
        }
    }
    public static function geoProvinceAvtivityJson() {

        global $DB;

        $path = "/moodle/www/adminlte/views/pages/geo/data/province_activity_table.php";

        $provincedata = [
            ['alava', 'Araba, Álava', self::get_province_sessions('ARABA-ALAVA'), self::get_province_hourly_views('ARABA-ALAVA')],
            ['albacete','Albacete', self::get_province_sessions('Albacete'), self::get_province_hourly_views('Albacete')],
            ['alicante','Alicante/Alacant', self::get_province_sessions('ALICANTE'), self::get_province_hourly_views('ALICANTE')],
            ['almeria','Almería', self::get_province_sessions('Almeria'), self::get_province_hourly_views('Almeria'), ('Almeria')],
            ['asturias','Asturias, Asturies', self::get_province_sessions('Asturias'), self::get_province_hourly_views('Asturias')],
            ['avila','Ávila', self::get_province_sessions('Avila'), self::get_province_hourly_views('Avila')],

            ['badajoz','Badajoz', self::get_province_sessions('Badajoz'), self::get_province_hourly_views('Badajoz')],
            ['barcelona','Barcelona', self::get_province_sessions('Barcelona'), self::get_province_hourly_views('Barcelona')],
            ['burgos','Burgos', self::get_province_sessions('Burgos'), self::get_province_hourly_views('Burgos')],

            ['caceres','Cáceres', self::get_province_sessions('Caceres'), self::get_province_hourly_views('Caceres')],
            ['cadiz','Cádiz', self::get_province_sessions('Cadiz'), self::get_province_hourly_views('Cadiz')],
            ['cantabria','Cantabria', self::get_province_sessions('Cantabria'), self::get_province_hourly_views('Cantabria')],
            ['castellon','Castellón, Castelló', self::get_province_sessions('Castellon'), self::get_province_hourly_views('Castellon')],
            ['ciudad_real','Ciudad Real', self::get_province_sessions('Ciudad Real'), self::get_province_hourly_views('Ciudad Real')],
            ['cordoba','Córdoba', self::get_province_sessions('Cordoba'), self::get_province_hourly_views('Cordoba')],
            ['corunia','A Coruña', self::get_province_sessions('A CORUÑA'), self::get_province_hourly_views('A CORUÑA')],
            ['cuenca','Cuenca', self::get_province_sessions('Cuenca'), self::get_province_hourly_views('Cuenca')],

            ['girona','Girona', self::get_province_sessions('Girona'), self::get_province_hourly_views('Girona')],
            ['granada','Granada', self::get_province_sessions('Granada'), self::get_province_hourly_views('Granada')],
            ['guadalajara','Guadalajara', self::get_province_sessions('Guadalajara'), self::get_province_hourly_views('Guadalajara')],
            ['gipuzkoa','Gipuzkoa', self::get_province_sessions('Gipuzkoa'), self::get_province_hourly_views('Gipuzkoa')],
            
            ['huelva','Huelva', self::get_province_sessions('Huelva'), self::get_province_hourly_views('Huelva')],
            ['huesca','Huesca', self::get_province_sessions('Huesca'), self::get_province_hourly_views('Huesca')],
            ['illes_balears','Illes Balears', self::get_province_sessions('Illes Balears'), self::get_province_hourly_views('Illes Balears')],
            ['jaen','Jaén', self::get_province_sessions('Jaen'), self::get_province_hourly_views('Jaen')],
            ['leon','León', self::get_province_sessions('Leon'), self::get_province_hourly_views('Leon')],
            ['lleida','Lleida', self::get_province_sessions('Lleida'), self::get_province_hourly_views('Lleida')],
            ['lugo','Lugo', self::get_province_sessions('Lugo'), self::get_province_hourly_views('Lugo')],

            ['madrid','Madrid', self::get_province_sessions('Madrid'), self::get_province_hourly_views('Madrid')],
            ['malaga','Málaga', self::get_province_sessions('Malaga'), self::get_province_hourly_views('Malaga')],
            ['murcia','Murcia', self::get_province_sessions('Murcia'), self::get_province_hourly_views('Murcia')],
            ['navarra','Navarra', self::get_province_sessions('Navarra'), self::get_province_hourly_views('Navarra')],
            ['ourense','Ourense', self::get_province_sessions('Ourense'), self::get_province_hourly_views('Ourense')],
            ['palencia','Palencia', self::get_province_sessions('Palencia'), self::get_province_hourly_views('Palencia')],
            ['las_palmas','Las Palmas', self::get_province_sessions('Las Palmas'), self::get_province_hourly_views('Las Palmas')],
            ['pontevedra','Pontevedra', self::get_province_sessions('Pontevedra'), self::get_province_hourly_views('Pontevedra')],
            ['la_rioja','La Rioja', self::get_province_sessions('La Rioja'), self::get_province_hourly_views('La Rioja')],
            ['salamanca','Salamanca', self::get_province_sessions('Salamanca'), self::get_province_hourly_views('Salamanca')],
            ['segovia','Segovia', self::get_province_sessions('Segovia'), self::get_province_hourly_views('Segovia')],

            ['sevilla','Sevilla', self::get_province_sessions('Sevilla'), self::get_province_hourly_views('Sevilla')],
            ['soria','Soria', self::get_province_sessions('Soria'), self::get_province_hourly_views('Soria')],
            ['tarragona','Tarragona', self::get_province_sessions('Tarragona'), self::get_province_hourly_views('Tarragona')],
            ['tenerife','Tenerife', self::get_province_sessions('Tenerife'), self::get_province_hourly_views('Tenerife')],
            ['teruel','Teruel', self::get_province_sessions('Teruel'), self::get_province_hourly_views('Teruel')],
            ['toledo','Toledo', self::get_province_sessions('Toledo'), self::get_province_hourly_views('Toledo')],
            ['valencia','València', self::get_province_sessions('Valencia'), self::get_province_hourly_views('Valencia')],
            ['valladolid','Valladolid', self::get_province_sessions('Valladolid'), self::get_province_hourly_views('Valladolid')],
            ['bizkaia','Bizkaia', self::get_province_sessions('Bizkaia'), self::get_province_hourly_views('Bizkaia')],
            ['zamora','Zamora', self::get_province_sessions('Zamora'), self::get_province_hourly_views('Zamora')],
            ['zaragoza','Zaragoza', self::get_province_sessions('Zaragoza'), self::get_province_hourly_views('Zaragoza')],

            ['melilla','Melilla', self::get_province_sessions('Melilla'), self::get_province_hourly_views('Melilla')],
            ['ceuta','Ceuta', self::get_province_sessions('Ceuta'), self::get_province_hourly_views('Ceuta')]
        ];

        $provincedatacontents = 
            '<?php

            class Activity_province_table {

                public static function getprovinceactivity() {

                $provincedata = ' . json_encode($provincedata,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) . ';

                return $provincedata;

                }
            }';
                    
                        file_put_contents($path, $provincedatacontents);
    }

}