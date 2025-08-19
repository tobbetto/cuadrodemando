<?php
defined('MOODLE_INTERNAL') || die();

$functions = array(
    'local_cuadrodemando_get_dashboard_data' => array(
        'classname'   => 'local_cuadrodemando\external\dashboard_api',
        'methodname'  => 'get_dashboard_data',
        'classpath'   => '',
        'description' => 'Get dashboard statistics',
        'type'        => 'read',
        'ajax'        => true,
        'loginrequired' => true,
    ),
);