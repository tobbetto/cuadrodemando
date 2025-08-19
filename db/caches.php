<?php
defined('MOODLE_INTERNAL') || die();

$definitions = array(
    'dashboard_data' => array(
        'mode' => cache_store::MODE_APPLICATION,
        'ttl' => 3600, // 1 hour
        'staticacceleration' => true,
        'staticaccelerationsize' => 50,
    ),
    'geo_data' => array(
        'mode' => cache_store::MODE_APPLICATION,
        'ttl' => 86400, // 24 hours
        'staticacceleration' => true,
    ),
);